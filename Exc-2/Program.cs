using System;
using System.Collections.Generic;

namespace DataMahasiswa
{
    class Mahasiswa
    {
        public string NIM { get; set; }
        public string Nama { get; set; }
        public string Prodi { get; set; }
        public double IPK { get; set; }

        public Mahasiswa(string nim, string nama, string prodi, double ipk)
        {
            NIM = nim;
            Nama = nama;
            Prodi = prodi;
            IPK = ipk;
        }
    }

    class Program
    {
        static List<Mahasiswa> daftarMahasiswa = new List<Mahasiswa>();

        static void Main()
        {
            int pilihan;

            do
            {
                TampilkanMenu();

                Console.Write("Pilih menu: ");
                int.TryParse(Console.ReadLine(), out pilihan);

                switch (pilihan)
                {
                    case 1:
                        TambahMahasiswa();
                        break;

                    case 2:
                        TampilkanMahasiswa();
                        break;

                    case 3:
                        CariMahasiswa();
                        break;

                    case 4:
                        HapusMahasiswa();
                        break;

                    case 5:
                        Console.WriteLine("Program selesai.");
                        break;

                    default:
                        Console.WriteLine("Pilihan tidak valid.");
                        break;
                }

                Console.WriteLine();

            } while (pilihan != 5);
        }

        static void TampilkanMenu()
        {
            Console.WriteLine("================================");
            Console.WriteLine("     SISTEM DATA MAHASISWA      ");
            Console.WriteLine("================================");
            Console.WriteLine("1. Tambah Data Mahasiswa");
            Console.WriteLine("2. Tampilkan Semua Mahasiswa");
            Console.WriteLine("3. Cari Mahasiswa (berdasarkan NIM)");
            Console.WriteLine("4. Hapus Mahasiswa (berdasarkan NIM)");
            Console.WriteLine("5. Keluar");
            Console.WriteLine("--------------------------------");
        }

        static void TambahMahasiswa()
        {
            Console.WriteLine();
            Console.WriteLine("Tambah Data Mahasiswa");

            Console.Write("NIM   : ");
            string nim = Console.ReadLine() ?? "";

            Console.Write("Nama  : ");
            string nama = Console.ReadLine() ?? "";

            Console.Write("Prodi : ");
            string prodi = Console.ReadLine() ?? "";

            double ipk;

            while (true)
            {
                Console.Write("IPK (0.00 - 4.00): ");

                if (double.TryParse(Console.ReadLine(), out ipk)
                    && ipk >= 0 && ipk <= 4)
                {
                    break;
                }

                Console.WriteLine("IPK harus berada di antara 0.00 sampai 4.00.");
            }

            Mahasiswa mahasiswaBaru = new Mahasiswa(nim, nama, prodi, ipk);
            daftarMahasiswa.Add(mahasiswaBaru);

            Console.WriteLine("Data mahasiswa berhasil ditambahkan.");
        }

        static void TampilkanMahasiswa()
        {
            Console.WriteLine();
            Console.WriteLine("Daftar Mahasiswa");

            if (daftarMahasiswa.Count == 0)
            {
                Console.WriteLine("Belum ada data mahasiswa.");
                return;
            }

            Console.WriteLine("----------------------------------------------------------------------");
            Console.WriteLine("{0,-15} | {1,-25} | {2,-20} | {3,-5}", "NIM", "Nama", "Prodi", "IPK");
            Console.WriteLine("----------------------------------------------------------------------");

            foreach (var mhs in daftarMahasiswa)
            {
                Console.WriteLine("{0,-15} | {1,-25} | {2,-20} | {3,-5:F2}", mhs.NIM, mhs.Nama, mhs.Prodi, mhs.IPK);
            }

            Console.WriteLine("----------------------------------------------------------------------");
        }

        static void CariMahasiswa()
        {
            Console.WriteLine();
            Console.WriteLine("Cari Mahasiswa");
            Console.Write("Masukkan NIM: ");
            string nim = Console.ReadLine() ?? "";

            Mahasiswa? mhs = daftarMahasiswa.Find(m => m.NIM == nim);

            if (mhs != null)
            {
                Console.WriteLine("\nData Ditemukan:");
                Console.WriteLine($"NIM   : {mhs.NIM}");
                Console.WriteLine($"Nama  : {mhs.Nama}");
                Console.WriteLine($"Prodi : {mhs.Prodi}");
                Console.WriteLine($"IPK   : {mhs.IPK:F2}");
            }
            else
            {
                Console.WriteLine($"Mahasiswa dengan NIM {nim} tidak ditemukan.");
            }
        }

        static void HapusMahasiswa()
        {
            Console.WriteLine();
            Console.WriteLine("Hapus Mahasiswa");
            Console.Write("Masukkan NIM: ");
            string nim = Console.ReadLine() ?? "";

            Mahasiswa? mhs = daftarMahasiswa.Find(m => m.NIM == nim);

            if (mhs != null)
            {
                daftarMahasiswa.Remove(mhs);
                Console.WriteLine($"Data mahasiswa dengan NIM {nim} berhasil dihapus.");
            }
            else
            {
                Console.WriteLine($"Mahasiswa dengan NIM {nim} tidak ditemukan.");
            }
        }
    }
}