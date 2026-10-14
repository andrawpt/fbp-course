using Microsoft.EntityFrameworkCore;

namespace Exc_4;

public class AppDbContext : DbContext
{
    public DbSet<Mahasiswa> Mahasiswas => Set<Mahasiswa>();

    protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
    {
        optionsBuilder.UseSqlite("Data Source=mahasiswa.db");
    }
}