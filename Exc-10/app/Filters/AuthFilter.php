<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $isApi   = str_starts_with(ltrim($request->getUri()->getPath(), '/'), 'api/')
            || $request->hasHeader('Accept') && str_contains($request->getHeaderLine('Accept'), 'application/json');

        if (! $session->get('isLoggedIn')) {
            if ($isApi) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON(['ok' => false, 'message' => 'Silakan login terlebih dahulu.']);
            }

            $session->setFlashdata('error', 'Silakan login terlebih dahulu.');

            return redirect()->to('/login');
        }

        if (
            ! empty($arguments) &&
            ! in_array($session->get('role'), $arguments, true)
        ) {
            if ($isApi) {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON(['ok' => false, 'message' => 'Anda tidak memiliki izin untuk mengakses endpoint ini.']);
            }

            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada proses tambahan
    }
}
