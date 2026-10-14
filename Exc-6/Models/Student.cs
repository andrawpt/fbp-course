using System;

namespace Exc_6.Models;

public class Student
{
    public int Id { get; set; }
    public string Nim { get; set; } = string.Empty;
    public string FullName { get; set; } = string.Empty;
    public string Major { get; set; } = string.Empty;
    public string Email { get; set; } = string.Empty;
    public DateTime RegistrationDate { get; set; } = DateTime.Now;
}

