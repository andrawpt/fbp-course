using System;
using System.ComponentModel.DataAnnotations.Schema;

namespace Exc_4;

public class Mahasiswa
{
    public int Id { get; set; }
    public string Nim { get; set; } = string.Empty;
    public string Nama { get; set; } = string.Empty;
    public string Jurusan { get; set; } = string.Empty;
    public double Ipk { get; set; }

    [NotMapped]
    public string Inisial
    {
        get
        {
            if (string.IsNullOrWhiteSpace(Nama)) return "?";
            var parts = Nama.Trim().Split(' ', StringSplitOptions.RemoveEmptyEntries);
            if (parts.Length == 1) return parts[0][0].ToString().ToUpper();
            return $"{parts[0][0]}{parts[^1][0]}".ToUpper();
        }
    }

    [NotMapped]
    public string AvatarBgColor
    {
        get
        {
            string[] colors = { "#4F46E5", "#0D9488", "#E11D48", "#D97706", "#7C3AED", "#2563EB", "#059669", "#DB2777" };
            int hash = Math.Abs(Nama.GetHashCode());
            return colors[hash % colors.Length];
        }
    }

    [NotMapped]
    public string StatusIpk => Ipk switch
    {
        >= 3.50 => "Cumlaude",
        >= 3.00 => "Sangat Memuaskan",
        _ => "Memuaskan"
    };

    [NotMapped]
    public string IpkBadgeBg => Ipk switch
    {
        >= 3.50 => "#DCFCE7",
        >= 3.00 => "#DBEAFE",
        _ => "#FEF3C7"
    };

    [NotMapped]
    public string IpkBadgeFg => Ipk switch
    {
        >= 3.50 => "#166534",
        >= 3.00 => "#1E40AF",
        _ => "#92400E"
    };
}