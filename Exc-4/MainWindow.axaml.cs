using System;
using System.Globalization;
using System.Linq;
using Avalonia.Controls;
using Avalonia.Interactivity;

namespace Exc_4;

public partial class MainWindow : Window
{
    private readonly AppDbContext _db = new();
    private Mahasiswa? _mahasiswaTerpilih;

    public MainWindow()
    {
        InitializeComponent();
        _db.Database.EnsureCreated();
        MuatData();
    }

    private void MuatData()
    {
        string query = TxtSearch?.Text?.Trim().ToLowerInvariant() ?? string.Empty;

        var data = _db.Mahasiswas.AsQueryable();

        if (!string.IsNullOrEmpty(query))
        {
            data = data.Where(m => m.Nama.ToLower().Contains(query) || m.Nim.ToLower().Contains(query));
        }

        var listMahasiswa = data.OrderBy(m => m.Nim).ToList();
        GridMahasiswa.ItemsSource = listMahasiswa;

        // Hitung seluruh statistik untuk kartu di UI
        var semuaMahasiswa = _db.Mahasiswas.ToList();
        TxtTotalMahasiswa.Text = semuaMahasiswa.Count.ToString();

        if (semuaMahasiswa.Any())
        {
            TxtAvgIpk.Text = semuaMahasiswa.Average(m => m.Ipk).ToString("F2", CultureInfo.InvariantCulture);
            TxtMaxIpk.Text = semuaMahasiswa.Max(m => m.Ipk).ToString("F2", CultureInfo.InvariantCulture);
        }
        else
        {
            TxtAvgIpk.Text = "0.00";
            TxtMaxIpk.Text = "0.00";
        }
    }

    private void TxtSearch_TextChanged(object? sender, TextChangedEventArgs e)
    {
        MuatData();
    }

    private void BtnTambah_Click(object? sender, RoutedEventArgs e)
    {
        string nimInput = TxtNim.Text?.Trim() ?? string.Empty;
        string namaInput = TxtNama.Text?.Trim() ?? string.Empty;

        if (string.IsNullOrWhiteSpace(nimInput) || string.IsNullOrWhiteSpace(namaInput))
        {
            TxtStatus.Text = "NIM dan Nama tidak boleh kosong!";
            return;
        }

        // Cek duplikasi NIM saat tambah data baru
        if (_db.Mahasiswas.Any(m => m.Nim == nimInput))
        {
            TxtStatus.Text = $"NIM {nimInput} sudah terdaftar!";
            return;
        }

        if (!double.TryParse(TxtIpk.Text?.Trim().Replace(',', '.'), NumberStyles.Any, CultureInfo.InvariantCulture, out double ipk) || ipk < 0.0 || ipk > 4.0)
        {
            TxtStatus.Text = "IPK harus berupa angka antara 0.00 - 4.00!";
            return;
        }

        var mhs = new Mahasiswa
        {
            Nim = nimInput,
            Nama = namaInput,
            Jurusan = TxtJurusan.Text?.Trim() ?? string.Empty,
            Ipk = ipk
        };

        _db.Mahasiswas.Add(mhs);
        _db.SaveChanges();

        BersihkanForm();
        MuatData();
        TxtStatus.Text = "Data berhasil ditambahkan.";
    }

    private void GridMahasiswa_SelectionChanged(object? sender, SelectionChangedEventArgs e)
    {
        if (GridMahasiswa.SelectedItem is Mahasiswa terpilih)
        {
            _mahasiswaTerpilih = terpilih;
            TxtNim.Text = terpilih.Nim;
            TxtNama.Text = terpilih.Nama;
            TxtJurusan.Text = terpilih.Jurusan;
            TxtIpk.Text = terpilih.Ipk.ToString("F2", CultureInfo.InvariantCulture);
            TxtStatus.Text = $"Memilih: {terpilih.Nama}";
        }
    }

    private void BtnUbah_Click(object? sender, RoutedEventArgs e)
    {
        if (_mahasiswaTerpilih == null)
        {
            TxtStatus.Text = "Pilih mahasiswa pada tabel terlebih dahulu!";
            return;
        }

        string nimInput = TxtNim.Text?.Trim() ?? string.Empty;
        string namaInput = TxtNama.Text?.Trim() ?? string.Empty;

        if (string.IsNullOrWhiteSpace(nimInput) || string.IsNullOrWhiteSpace(namaInput))
        {
            TxtStatus.Text = "NIM dan Nama tidak boleh kosong!";
            return;
        }

        // Cek duplikasi NIM (abaikan jika NIM milik mahasiswa yang sedang diedit)
        if (_db.Mahasiswas.Any(m => m.Nim == nimInput && m.Id != _mahasiswaTerpilih.Id))
        {
            TxtStatus.Text = $"NIM {nimInput} sudah digunakan oleh mahasiswa lain!";
            return;
        }

        if (!double.TryParse(TxtIpk.Text?.Trim().Replace(',', '.'), NumberStyles.Any, CultureInfo.InvariantCulture, out double ipk) || ipk < 0.0 || ipk > 4.0)
        {
            TxtStatus.Text = "IPK harus berupa angka antara 0.00 - 4.00!";
            return;
        }

        _mahasiswaTerpilih.Nim = nimInput;
        _mahasiswaTerpilih.Nama = namaInput;
        _mahasiswaTerpilih.Jurusan = TxtJurusan.Text?.Trim() ?? string.Empty;
        _mahasiswaTerpilih.Ipk = ipk;

        _db.SaveChanges();
        BersihkanForm();
        MuatData();
        TxtStatus.Text = "Perubahan berhasil disimpan.";
    }

    private void BtnHapus_Click(object? sender, RoutedEventArgs e)
    {
        if (_mahasiswaTerpilih == null)
        {
            TxtStatus.Text = "Pilih baris yang ingin dihapus!";
            return;
        }

        _db.Mahasiswas.Remove(_mahasiswaTerpilih);
        _db.SaveChanges();

        BersihkanForm();
        MuatData();
        TxtStatus.Text = "Data berhasil dihapus.";
    }

    private void BtnReset_Click(object? sender, RoutedEventArgs e)
    {
        BersihkanForm();
        TxtStatus.Text = "Form dibersihkan.";
    }

    private void BersihkanForm()
    {
        TxtNim.Text = string.Empty;
        TxtNama.Text = string.Empty;
        TxtJurusan.Text = string.Empty;
        TxtIpk.Text = string.Empty;
        _mahasiswaTerpilih = null;
        GridMahasiswa.SelectedItem = null;
    }
}