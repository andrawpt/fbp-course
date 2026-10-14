using MySqlConnector;
using Exc_5.Models;
using System.Collections.Generic;

namespace Exc_5.Data
{
    public class DatabaseHelper
    {
        private readonly string _connectionString = 
            "Server=127.0.0.1;Port=3306;Database=student_db;User ID=root;Password=;";

        public List<Student> GetAll()
        {
            var list = new List<Student>();
            using var conn = new MySqlConnection(_connectionString);
            conn.Open();

            const string query = "SELECT id, nim, name, major, email FROM students";
            using var cmd = new MySqlCommand(query, conn);
            using var reader = cmd.ExecuteReader();

            while (reader.Read())
            {
                list.Add(new Student
                {
                    Id = reader.GetInt32("id"),
                    Nim = reader.GetString("nim"),
                    Name = reader.GetString("name"),
                    Major = reader.GetString("major"),
                    Email = reader.GetString("email")
                });
            }
            return list;
        }

        public void Insert(Student s)
        {
            using var conn = new MySqlConnection(_connectionString);
            conn.Open();

            const string query = "INSERT INTO students (nim, name, major, email) VALUES (@nim, @name, @major, @email)";
            using var cmd = new MySqlCommand(query, conn);
            cmd.Parameters.AddWithValue("@nim", s.Nim);
            cmd.Parameters.AddWithValue("@name", s.Name);
            cmd.Parameters.AddWithValue("@major", s.Major);
            cmd.Parameters.AddWithValue("@email", s.Email);
            cmd.ExecuteNonQuery();
        }

        public void Update(Student s)
        {
            using var conn = new MySqlConnection(_connectionString);
            conn.Open();

            const string query = "UPDATE students SET nim=@nim, name=@name, major=@major, email=@email WHERE id=@id";
            using var cmd = new MySqlCommand(query, conn);
            cmd.Parameters.AddWithValue("@id", s.Id);
            cmd.Parameters.AddWithValue("@nim", s.Nim);
            cmd.Parameters.AddWithValue("@name", s.Name);
            cmd.Parameters.AddWithValue("@major", s.Major);
            cmd.Parameters.AddWithValue("@email", s.Email);
            cmd.ExecuteNonQuery();
        }

        public void Delete(int id)
        {
            using var conn = new MySqlConnection(_connectionString);
            conn.Open();

            const string query = "DELETE FROM students WHERE id=@id";
            using var cmd = new MySqlCommand(query, conn);
            cmd.Parameters.AddWithValue("@id", id);
            cmd.ExecuteNonQuery();
        }
    }
}