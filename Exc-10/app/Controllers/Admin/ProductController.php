<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;

class ProductController extends BaseController
{
    private const MAX_PHOTOS       = 5;
    private const MAX_FILE_BYTES   = 2097152; // 2 MB
    private const ALLOWED_MIMES    = ['image/jpeg', 'image/png', 'image/webp'];
    private const ALLOWED_EXTS     = ['jpg', 'jpeg', 'png', 'webp'];
    private const PLACEHOLDER_PATH = '/images/placeholder.svg';

    public function index(): string
    {
        $keyword     = trim((string) $this->request->getGet('q'));
        $categoryId  = (int) $this->request->getGet('category_id');
        $stockFilter = (string) $this->request->getGet('stock_filter');

        $productModel  = new ProductModel();
        $categoryModel = new CategoryModel();
        $categories    = $categoryModel->orderBy('name', 'ASC')->findAll();

        $productModel->select('products.*, categories.name AS category_name')
            ->join('categories', 'categories.id = products.category_id', 'left');

        if ($keyword !== '') {
            $productModel->like('products.name', $keyword);
        }

        if ($categoryId > 0) {
            $productModel->where('products.category_id', $categoryId);
        }

        if ($stockFilter === 'low') {
            $productModel->where('products.stock >=', 1)->where('products.stock <=', 5);
        } elseif ($stockFilter === 'out') {
            $productModel->where('products.stock', 0);
        }

        $products = $productModel->orderBy('products.id', 'DESC')->paginate(8);
        $pager    = $productModel->pager;

        $db = \Config\Database::connect();
        $summary = [
            'total_products'   => $db->table('products')->countAllResults(),
            'total_categories' => $db->table('categories')->countAllResults(),
            'active_products'  => $db->table('products')->where('status', 'active')->countAllResults(),
            'inactive_products'=> $db->table('products')->where('status', 'inactive')->countAllResults(),
        ];

        return view('admin/products/index', [
            'title'       => 'Kelola Produk – ShopEase',
            'products'    => $products,
            'pager'       => $pager,
            'categories'  => $categories,
            'keyword'     => $keyword,
            'categoryId'  => $categoryId,
            'stockFilter' => $stockFilter,
            'summary'     => $summary,
        ]);
    }

    public function create(): string
    {
        $categories = (new CategoryModel())->orderBy('name', 'ASC')->findAll();

        return view('admin/products/form', [
            'title'      => 'Tambah Produk – ShopEase',
            'product'    => null,
            'images'     => [],
            'categories' => $categories,
            'action'     => site_url('admin/products/store'),
        ]);
    }

    public function store(): RedirectResponse
    {
        $validated = $this->validateProductPayload();
        if (! $validated['ok']) {
            return redirect()->back()->withInput()->with('errors', $validated['errors']);
        }

        $filesResult = $this->validateUploadedImages(0);
        if (! $filesResult['ok']) {
            return redirect()->back()->withInput()->with('error', $filesResult['error']);
        }

        $data       = $validated['data'];
        $randomId   = bin2hex(random_bytes(3));
        $slug       = url_title($data['name'], '-', true) . '-' . $randomId;
        $now        = date('Y-m-d H:i:s');
        $movedPaths = [];

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $productModel = new ProductModel();
            $productId    = $productModel->insert([
                'category_id' => $data['category_id'],
                'name'        => $data['name'],
                'slug'        => $slug,
                'description' => $data['description'],
                'price'       => $data['price'],
                'stock'       => $data['stock'],
                'image'       => self::PLACEHOLDER_PATH,
                'icon'        => $data['icon'],
                'badge'       => $data['badge'],
                'status'      => $data['status'],
            ], true);

            $primaryUrl = $this->saveUploadedImages((int) $productId, $filesResult['files'], true, $movedPaths, $now);
            if ($primaryUrl !== null) {
                $productModel->update($productId, ['image' => $primaryUrl]);
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan produk.');
            }

            $db->transCommit();

            return redirect()->to(site_url('admin/products'))
                ->with('success', 'Produk "' . $data['name'] . '" berhasil ditambahkan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            $this->cleanupMovedFiles($movedPaths);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(int $id): string
    {
        $product = (new ProductModel())->find($id);
        if (! is_array($product)) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        $categories = (new CategoryModel())->orderBy('name', 'ASC')->findAll();
        $images     = (new ProductImageModel())->getByProduct($id);

        return view('admin/products/form', [
            'title'      => 'Edit Produk – ShopEase',
            'product'    => $product,
            'images'     => $images,
            'categories' => $categories,
            'action'     => site_url('admin/products/update/' . $id),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $productModel = new ProductModel();
        $existing     = $productModel->find($id);
        if (! is_array($existing)) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        $validated = $this->validateProductPayload();
        if (! $validated['ok']) {
            return redirect()->back()->withInput()->with('errors', $validated['errors']);
        }

        $imageModel    = new ProductImageModel();
        $existingCount = count($imageModel->getByProduct($id));
        $filesResult   = $this->validateUploadedImages($existingCount);
        if (! $filesResult['ok']) {
            return redirect()->back()->withInput()->with('error', $filesResult['error']);
        }

        $data       = $validated['data'];
        $now        = date('Y-m-d H:i:s');
        $movedPaths = [];

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            if ($db->DBDriver === 'MySQLi') {
                $db->query('SELECT id FROM products WHERE id = ? FOR UPDATE', [$id]);
            }

            $productModel->update($id, [
                'category_id' => $data['category_id'],
                'name'        => $data['name'],
                'description' => $data['description'],
                'price'       => $data['price'],
                'stock'       => $data['stock'],
                'icon'        => $data['icon'],
                'badge'       => $data['badge'],
                'status'      => $data['status'],
            ]);

            $setFirstPrimary = $existingCount === 0;
            $newPrimaryUrl   = $this->saveUploadedImages($id, $filesResult['files'], $setFirstPrimary, $movedPaths, $now);
            if ($newPrimaryUrl !== null) {
                $productModel->update($id, ['image' => $newPrimaryUrl]);
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui produk.');
            }

            $db->transCommit();

            return redirect()->to(site_url('admin/products/edit/' . $id))
                ->with('success', 'Produk "' . $data['name'] . '" berhasil diperbarui.');
        } catch (\Throwable $e) {
            $db->transRollback();
            $this->cleanupMovedFiles($movedPaths);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete(int $id): RedirectResponse
    {
        $productModel = new ProductModel();
        $product      = $productModel->find($id);

        if (! is_array($product)) {
            return redirect()->to(site_url('admin/products'))
                ->with('error', 'Produk tidak ditemukan.');
        }

        $db       = \Config\Database::connect();
        $inCart   = $db->table('cart_items')->where('product_id', $id)->countAllResults();
        if ($inCart > 0) {
            return redirect()->to(site_url('admin/products'))
                ->with('error', 'Produk "' . $product['name'] . '" tidak dapat dihapus karena masih ada di keranjang pelanggan. Ubah status menjadi Tidak Tersedia jika ingin menonaktifkan.');
        }

        $imageModel = new ProductImageModel();
        $images     = $imageModel->getByProduct($id);

        try {
            $productModel->delete($id);
        } catch (\Throwable $e) {
            return redirect()->to(site_url('admin/products'))
                ->with('error', 'Produk gagal dihapus karena masih direferensikan oleh data lain.');
        }

        foreach ($images as $img) {
            $this->deleteLocalUpload((string) $img['image_path']);
        }

        return redirect()->to(site_url('admin/products'))
            ->with('success', 'Produk "' . $product['name'] . '" berhasil dihapus.');
    }

    public function setPrimaryImage(int $productId, int $imageId): RedirectResponse
    {
        $imageModel = new ProductImageModel();
        $target     = $imageModel->where('id', $imageId)->where('product_id', $productId)->first();

        if (! is_array($target)) {
            return redirect()->to(site_url('admin/products/edit/' . $productId))
                ->with('error', 'Foto produk tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $imageModel->where('product_id', $productId)->set(['is_primary' => 0])->update();
            $imageModel->update($imageId, ['is_primary' => 1]);
            (new ProductModel())->update($productId, ['image' => $target['image_path']]);

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->to(site_url('admin/products/edit/' . $productId))
                ->with('error', 'Gagal menjadikan foto utama.');
        }

        return redirect()->to(site_url('admin/products/edit/' . $productId))
            ->with('success', 'Foto utama produk berhasil diperbarui.');
    }

    public function deleteImage(int $productId, int $imageId): RedirectResponse
    {
        $imageModel = new ProductImageModel();
        $target     = $imageModel->where('id', $imageId)->where('product_id', $productId)->first();

        if (! is_array($target)) {
            return redirect()->to(site_url('admin/products/edit/' . $productId))
                ->with('error', 'Foto produk tidak ditemukan.');
        }

        $wasPrimary = (int) $target['is_primary'] === 1;
        $imagePath  = (string) $target['image_path'];

        $imageModel->delete($imageId);
        $this->deleteLocalUpload($imagePath);

        if ($wasPrimary) {
            $next = $imageModel->where('product_id', $productId)->orderBy('id', 'ASC')->first();
            if (is_array($next)) {
                $imageModel->update($next['id'], ['is_primary' => 1]);
                (new ProductModel())->update($productId, ['image' => $next['image_path']]);
            } else {
                (new ProductModel())->update($productId, ['image' => self::PLACEHOLDER_PATH]);
            }
        }

        return redirect()->to(site_url('admin/products/edit/' . $productId))
            ->with('success', 'Foto berhasil dihapus dari galeri.');
    }

    /**
     * @return array{ok: bool, errors?: array<string, string>, data?: array<string, mixed>}
     */
    private function validateProductPayload(): array
    {
        $rules = [
            'name'        => 'required|min_length[3]|max_length[150]',
            'category_id' => 'required|integer|greater_than[0]',
            'price'       => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[1000000000]',
            'stock'       => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[1000000000]',
            'status'      => 'required|in_list[tersedia,tidak_tersedia,active,inactive]',
            'description' => 'permit_empty|max_length[10000]',
        ];

        if (! $this->validate($rules)) {
            return ['ok' => false, 'errors' => $this->validator->getErrors()];
        }

        $categoryId = (int) $this->request->getPost('category_id');
        if ((new CategoryModel())->find($categoryId) === null) {
            return ['ok' => false, 'errors' => ['category_id' => 'Kategori yang dipilih tidak ditemukan.']];
        }

        $stock     = (int) $this->request->getPost('stock');
        $rawStatus = (string) $this->request->getPost('status');
        $status    = in_array($rawStatus, ['tersedia', 'active'], true) ? 'active' : 'inactive';

        // Jika stok bernilai nol, produk otomatis disimpan sebagai inactive
        if ($stock === 0) {
            $status = 'inactive';
        }

        $desc = trim((string) $this->request->getPost('description'));

        return [
            'ok'   => true,
            'data' => [
                'name'        => trim((string) $this->request->getPost('name')),
                'category_id' => $categoryId,
                'price'       => (int) $this->request->getPost('price'),
                'stock'       => $stock,
                'status'      => $status,
                'description' => $desc !== '' ? $desc : null,
                'icon'        => trim((string) $this->request->getPost('icon')) ?: 'bi-box',
                'badge'       => trim((string) $this->request->getPost('badge')) ?: 'Produk',
            ],
        ];
    }

    /**
     * @return array{ok: bool, error?: string, files?: list<UploadedFile>}
     */
    private function validateUploadedImages(int $existingCount): array
    {
        $uploaded = $this->request->getFileMultiple('images');
        if (! is_array($uploaded)) {
            return ['ok' => true, 'files' => []];
        }

        $validFiles = [];
        foreach ($uploaded as $file) {
            if (! $file instanceof UploadedFile || $file->getError() === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if (! $file->isValid()) {
                return ['ok' => false, 'error' => 'Terjadi kesalahan saat mengunggah file gambar.'];
            }

            $ext = strtolower($file->getClientExtension());
            if (! in_array($ext, self::ALLOWED_EXTS, true)) {
                return ['ok' => false, 'error' => 'Format foto harus JPG, PNG, atau WEBP.'];
            }

            if ($file->getSize() > self::MAX_FILE_BYTES) {
                return ['ok' => false, 'error' => 'Ukuran maksimal per foto adalah 2 MB.'];
            }

            $mime = $file->getMimeType();
            if (! in_array($mime, self::ALLOWED_MIMES, true)) {
                return ['ok' => false, 'error' => 'Tipe MIME foto tidak valid.'];
            }

            if (@getimagesize($file->getTempName()) === false) {
                return ['ok' => false, 'error' => 'Isi file bukan gambar yang valid.'];
            }

            $validFiles[] = $file;
        }

        if ($existingCount + count($validFiles) > self::MAX_PHOTOS) {
            return ['ok' => false, 'error' => 'Maksimal ' . self::MAX_PHOTOS . ' foto per produk.'];
        }

        return ['ok' => true, 'files' => $validFiles];
    }

    /**
     * @param list<UploadedFile> $files
     * @param list<string>       $movedPaths
     */
    private function saveUploadedImages(
        int $productId,
        array $files,
        bool $setFirstPrimary,
        array &$movedPaths,
        string $now
    ): ?string {
        if ($files === []) {
            return null;
        }

        $uploadDir = FCPATH . 'uploads/products';
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $imageModel = new ProductImageModel();
        $primaryUrl = null;

        foreach ($files as $idx => $file) {
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);
            $movedPaths[] = $uploadDir . '/' . $newName;

            $publicPath = '/uploads/products/' . $newName;
            $isPrimary  = ($setFirstPrimary && $idx === 0) ? 1 : 0;

            $imageModel->insert([
                'product_id' => $productId,
                'image_path' => $publicPath,
                'is_primary' => $isPrimary,
                'created_at' => $now,
            ]);

            if ($isPrimary === 1) {
                $primaryUrl = $publicPath;
            }
        }

        return $primaryUrl;
    }

    /**
     * @param list<string> $paths
     */
    private function cleanupMovedFiles(array $paths): void
    {
        foreach ($paths as $path) {
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    private function deleteLocalUpload(string $publicPath): void
    {
        if (! str_starts_with($publicPath, '/uploads/products/')) {
            return;
        }

        $fullPath = FCPATH . ltrim($publicPath, '/');
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
