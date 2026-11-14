<?php

use App\Database\Seeds\ShopSeeder;
use App\Database\Seeds\UserSeeder;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class HomeLandingTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate   = true;
    protected $namespace = 'App';
    protected $seed      = [UserSeeder::class, ShopSeeder::class];

    public function testHomeLandingPageRendersSuccessfully(): void
    {
        $result = $this->get('/');

        $result->assertOK();
        $result->assertSee('ShopEase');
        $result->assertSee('Produk Unggulan');
        $result->assertSee('Headphone Wireless Studio Pro');
    }

    public function testHomeLandingPageFiltersByCategory(): void
    {
        $result = $this->get('/?kategori=elektronik');

        $result->assertOK();
        $result->assertSee('Headphone Wireless Studio Pro');
        $result->assertDontSee('Jaket Oversize Canvas Minimalis');
    }

    public function testHomeLandingPageFiltersBySearchKeyword(): void
    {
        $result = $this->get('/?q=Keyboard');

        $result->assertOK();
        $result->assertSee('Mechanical Keyboard Compact 75%');
        $result->assertDontSee('Headphone Wireless Studio Pro');
    }
}
