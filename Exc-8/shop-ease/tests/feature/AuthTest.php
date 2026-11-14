<?php

use App\Database\Seeds\UserSeeder;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class AuthTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate   = true;
    protected $namespace = 'App';
    protected $seed      = UserSeeder::class;

    public function testLoginAndRegisterPagesRender(): void
    {
        $loginResponse = $this->get('login');
        $loginResponse->assertOK();
        $loginResponse->assertSee('Masuk ke ShopEase');

        $registerResponse = $this->get('register');
        $registerResponse->assertOK();
        $registerResponse->assertSee('Daftar Akun Customer');
    }

    public function testCustomerCanRegisterAndPasswordIsHashed(): void
    {
        $uniqueEmail = 'test_' . uniqid('', true) . '@shopease.local';

        $result = $this->post('register', [
            'name'             => 'Test Customer',
            'email'            => $uniqueEmail,
            'phone'            => '08123456789',
            'password'         => 'Rahasia123!',
            'password_confirm' => 'Rahasia123!',
        ]);

        $result->assertRedirectTo(site_url('login'));

        $userModel = new UserModel();
        $user      = $userModel->where('email', $uniqueEmail)->first();

        $this->assertNotNull($user);
        $this->assertSame('customer', $user['role']);
        $this->assertNotSame('Rahasia123!', $user['password_hash']);
        $this->assertTrue(password_verify('Rahasia123!', $user['password_hash']));

        // Cleanup test user
        $userModel->delete($user['id']);
    }

    public function testLoginSucceedsWithValidCredentialsAndLogoutClearsSession(): void
    {
        $loginResult = $this->post('login', [
            'email'    => 'customer@shopease.local',
            'password' => 'Customer123!',
        ]);

        $loginResult->assertRedirectTo(site_url('/'));
        $this->assertTrue((bool) session()->get('is_logged_in'));
        $this->assertSame('customer', session()->get('user_role'));

        $logoutResult = $this->post('logout');
        $logoutResult->assertRedirectTo(site_url('/'));
        $this->assertNull(session()->get('is_logged_in'));
    }

    public function testLoginFailsWithGenericErrorMessage(): void
    {
        $result = $this->post('login', [
            'email'    => 'tidakada@shopease.local',
            'password' => 'Salah123!',
        ]);

        $result->assertRedirect();
        $this->assertSame('Email atau password tidak sesuai.', session()->getFlashdata('error'));
        $this->assertNull(session()->get('is_logged_in'));
    }
}
