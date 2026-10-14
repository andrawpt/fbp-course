using System;
using System.Collections.ObjectModel;
using System.Threading.Tasks;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using Microsoft.Data.SqlClient;
using Exc_6.Data;
using Exc_6.Models;

namespace Exc_6.ViewModels;

public partial class MainWindowViewModel : ViewModelBase
{
    private readonly StudentRepository _repository;

    [ObservableProperty]
    private int _id;

    [ObservableProperty]
    private string _nim = string.Empty;

    [ObservableProperty]
    private string _fullName = string.Empty;

    [ObservableProperty]
    private string _major = string.Empty;

    [ObservableProperty]
    private string _email = string.Empty;

    [ObservableProperty]
    private Student? _selectedStudent;

    [ObservableProperty]
    private string _statusMessage = "Aplikasi siap. Klik 'Refresh Data' untuk memuat daftar mahasiswa.";

    [ObservableProperty]
    private bool _isSuccess = true;

    [ObservableProperty]
    private bool _isLoading = false;

    public ObservableCollection<Student> Students { get; } = new();

    public MainWindowViewModel()
    {
        _repository = new StudentRepository();
        _ = LoadStudentsAsync();
    }

    public MainWindowViewModel(StudentRepository repository)
    {
        _repository = repository;
        _ = LoadStudentsAsync();
    }

    partial void OnSelectedStudentChanged(Student? value)
    {
        if (value != null)
        {
            Id = value.Id;
            Nim = value.Nim;
            FullName = value.FullName;
            Major = value.Major;
            Email = value.Email;
            SetStatus($"Data '{value.FullName}' dipilih untuk diedit atau dihapus.", true);
        }
    }

    [RelayCommand]
    public async Task RefreshDataAsync()
    {
        await LoadStudentsAsync();
    }

    [RelayCommand]
    public async Task AddStudentAsync()
    {
        if (!ValidateForm(isNew: true))
        {
            return;
        }

        var newStudent = new Student
        {
            Nim = Nim.Trim(),
            FullName = FullName.Trim(),
            Major = Major.Trim(),
            Email = Email.Trim(),
            RegistrationDate = DateTime.Now
        };

        try
        {
            IsLoading = true;
            int rowsAffected = await _repository.InsertStudentAsync(newStudent);
            if (rowsAffected > 0)
            {
                SetStatus($"Mahasiswa '{newStudent.FullName}' ({newStudent.Nim}) berhasil didaftarkan!", true);
                ClearForm();
                await LoadStudentsAsync();
            }
            else
            {
                SetStatus("Gagal menambahkan mahasiswa baru.", false);
            }
        }
        catch (SqlException ex)
        {
            if (ex.Number == 2627 || ex.Number == 2601)
            {
                SetStatus($"Error: NIM '{newStudent.Nim}' sudah terdaftar dalam sistem!", false);
            }
            else
            {
                SetStatus($"SQL Server Error: {ex.Message}", false);
            }
        }
        catch (Exception ex)
        {
            SetStatus($"Terjadi kesalahan sistem: {ex.Message}", false);
        }
        finally
        {
            IsLoading = false;
        }
    }

    [RelayCommand]
    public async Task UpdateStudentAsync()
    {
        if (Id <= 0)
        {
            SetStatus("Silakan pilih data mahasiswa dari tabel terlebih dahulu untuk di-update.", false);
            return;
        }

        if (!ValidateForm(isNew: false))
        {
            return;
        }

        var updatedStudent = new Student
        {
            Id = Id,
            Nim = Nim.Trim(),
            FullName = FullName.Trim(),
            Major = Major.Trim(),
            Email = Email.Trim()
        };

        try
        {
            IsLoading = true;
            int rowsAffected = await _repository.UpdateStudentAsync(updatedStudent);
            if (rowsAffected > 0)
            {
                SetStatus($"Data mahasiswa ID #{updatedStudent.Id} ({updatedStudent.FullName}) berhasil diperbarui!", true);
                ClearForm();
                await LoadStudentsAsync();
            }
            else
            {
                SetStatus($"Gagal memperbarui: Data ID #{updatedStudent.Id} tidak ditemukan.", false);
            }
        }
        catch (SqlException ex)
        {
            if (ex.Number == 2627 || ex.Number == 2601)
            {
                SetStatus($"Error: NIM '{updatedStudent.Nim}' sudah digunakan oleh mahasiswa lain!", false);
            }
            else
            {
                SetStatus($"SQL Server Error: {ex.Message}", false);
            }
        }
        catch (Exception ex)
        {
            SetStatus($"Terjadi kesalahan sistem: {ex.Message}", false);
        }
        finally
        {
            IsLoading = false;
        }
    }

    [RelayCommand]
    public async Task DeleteStudentAsync()
    {
        if (Id <= 0)
        {
            SetStatus("Silakan pilih data mahasiswa dari tabel terlebih dahulu untuk dihapus.", false);
            return;
        }

        int targetId = Id;
        string targetName = FullName;

        try
        {
            IsLoading = true;
            int rowsAffected = await _repository.DeleteStudentAsync(targetId);
            if (rowsAffected > 0)
            {
                SetStatus($"Data mahasiswa ID #{targetId} ('{targetName}') berhasil dihapus.", true);
                ClearForm();
                await LoadStudentsAsync();
            }
            else
            {
                SetStatus($"Gagal menghapus: Data ID #{targetId} tidak ditemukan.", false);
            }
        }
        catch (SqlException ex)
        {
            SetStatus($"SQL Server Error saat menghapus data: {ex.Message}", false);
        }
        catch (Exception ex)
        {
            SetStatus($"Terjadi kesalahan sistem: {ex.Message}", false);
        }
        finally
        {
            IsLoading = false;
        }
    }

    [RelayCommand]
    public void ClearForm()
    {
        Id = 0;
        Nim = string.Empty;
        FullName = string.Empty;
        Major = string.Empty;
        Email = string.Empty;
        SelectedStudent = null;
        SetStatus("Formulir input telah dikosongkan.", true);
    }

    private async Task LoadStudentsAsync()
    {
        try
        {
            IsLoading = true;
            var list = await _repository.GetAllStudentsAsync();

            Students.Clear();
            foreach (var student in list)
            {
                Students.Add(student);
            }

            SetStatus($"Berhasil memuat {Students.Count} data mahasiswa dari database.", true);
        }
        catch (SqlException ex)
        {
            SetStatus($"Koneksi ke SQL Server Gagal: {ex.Message}. Periksa konfigurasi ConnectionString pada StudentRepository.cs.", false);
        }
        catch (Exception ex)
        {
            SetStatus($"Gagal memuat data: {ex.Message}", false);
        }
        finally
        {
            IsLoading = false;
        }
    }

    private bool ValidateForm(bool isNew)
    {
        if (string.IsNullOrWhiteSpace(Nim))
        {
            SetStatus("Validasi Gagal: NIM wajib diisi.", false);
            return false;
        }

        if (string.IsNullOrWhiteSpace(FullName))
        {
            SetStatus("Validasi Gagal: Nama Lengkap wajib diisi.", false);
            return false;
        }

        if (string.IsNullOrWhiteSpace(Major))
        {
            SetStatus("Validasi Gagal: Jurusan wajib diisi.", false);
            return false;
        }

        if (string.IsNullOrWhiteSpace(Email))
        {
            SetStatus("Validasi Gagal: Email wajib diisi.", false);
            return false;
        }

        if (!Email.Contains('@') || !Email.Contains('.'))
        {
            SetStatus("Validasi Gagal: Format Email tidak valid (contoh: nama@kampus.ac.id).", false);
            return false;
        }

        return true;
    }

    private void SetStatus(string message, bool isSuccess)
    {
        StatusMessage = message;
        IsSuccess = isSuccess;
    }
}
