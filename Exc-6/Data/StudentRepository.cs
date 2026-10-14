using System;
using System.Collections.Generic;
using System.Data;
using System.Threading.Tasks;
using Microsoft.Data.SqlClient;
using Exc_6.Models;

namespace Exc_6.Data;

public class StudentRepository
{
    private readonly string _connectionString;

    public StudentRepository(string? connectionString = null)
    {
        _connectionString = connectionString ??
            "Server=localhost,1433;Database=StudentDb;User Id=sa;Password=Atmin123;TrustServerCertificate=True;Encrypt=True;";
    }

    public async Task<List<Student>> GetAllStudentsAsync()
    {
        var students = new List<Student>();

        const string query = @"
            SELECT Id, Nim, FullName, Major, Email, RegistrationDate 
            FROM Students 
            ORDER BY Id DESC";

        using var connection = new SqlConnection(_connectionString);
        using var command = new SqlCommand(query, connection);

        await connection.OpenAsync();
        using var reader = await command.ExecuteReaderAsync();

        while (await reader.ReadAsync())
        {
            var student = new Student
            {
                Id = reader.GetInt32(reader.GetOrdinal("Id")),
                Nim = reader.GetString(reader.GetOrdinal("Nim")),
                FullName = reader.GetString(reader.GetOrdinal("FullName")),
                Major = reader.GetString(reader.GetOrdinal("Major")),
                Email = reader.GetString(reader.GetOrdinal("Email")),
                RegistrationDate = reader.GetDateTime(reader.GetOrdinal("RegistrationDate"))
            };

            students.Add(student);
        }

        return students;
    }

    public async Task<int> InsertStudentAsync(Student student)
    {
        const string query = @"
            INSERT INTO Students (Nim, FullName, Major, Email, RegistrationDate)
            VALUES (@Nim, @FullName, @Major, @Email, @RegistrationDate)";

        using var connection = new SqlConnection(_connectionString);
        using var command = new SqlCommand(query, connection);

        command.Parameters.Add("@Nim", SqlDbType.NVarChar, 50).Value = student.Nim;
        command.Parameters.Add("@FullName", SqlDbType.NVarChar, 150).Value = student.FullName;
        command.Parameters.Add("@Major", SqlDbType.NVarChar, 100).Value = student.Major;
        command.Parameters.Add("@Email", SqlDbType.NVarChar, 150).Value = student.Email;
        command.Parameters.Add("@RegistrationDate", SqlDbType.DateTime2).Value = student.RegistrationDate;

        await connection.OpenAsync();
        return await command.ExecuteNonQueryAsync();
    }

    public async Task<int> UpdateStudentAsync(Student student)
    {
        const string query = @"
            UPDATE Students 
            SET Nim = @Nim, 
                FullName = @FullName, 
                Major = @Major, 
                Email = @Email 
            WHERE Id = @Id";

        using var connection = new SqlConnection(_connectionString);
        using var command = new SqlCommand(query, connection);

        command.Parameters.Add("@Id", SqlDbType.Int).Value = student.Id;
        command.Parameters.Add("@Nim", SqlDbType.NVarChar, 50).Value = student.Nim;
        command.Parameters.Add("@FullName", SqlDbType.NVarChar, 150).Value = student.FullName;
        command.Parameters.Add("@Major", SqlDbType.NVarChar, 100).Value = student.Major;
        command.Parameters.Add("@Email", SqlDbType.NVarChar, 150).Value = student.Email;

        await connection.OpenAsync();
        return await command.ExecuteNonQueryAsync();
    }

    public async Task<int> DeleteStudentAsync(int id)
    {
        const string query = "DELETE FROM Students WHERE Id = @Id";

        using var connection = new SqlConnection(_connectionString);
        using var command = new SqlCommand(query, connection);

        command.Parameters.Add("@Id", SqlDbType.Int).Value = id;

        await connection.OpenAsync();
        return await command.ExecuteNonQueryAsync();
    }
}

