<?php

use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class AuthHandsOnTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate   = true;
    protected $namespace = 'App';

    public function testRegisterAndLoginPagesRender(): void
    {
        $this->get('register')->assertOK();
        $this->get('login')->assertOK();
    }

    public function testScenario1RegisterNewUserSucceedsWithHashedPassword(): void
    {
        $result = $this->post('register', [
            'name'             => 'Budi Santoso',
            'email'            => 'budi@shopease.com',
            'phone'            => '081234567890',
            'address'          => 'Jl. Merdeka No. 1',
            'password'         => 'Password123',
            'password_confirm' => 'Password123',
        ]);

        $result->assertRedirectTo('/login');

        $userModel = new UserModel();
        $user      = $userModel->where('email', 'budi@shopease.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('customer', $user['role']);
        $this->assertNotSame('Password123', $user['password']);
        $this->assertTrue(password_verify('Password123', $user['password']));
    }

    public function testScenario2DuplicateEmailIsRejected(): void
    {
        $userModel = new UserModel();
        $userModel->insert([
            'name'     => 'Existing User',
            'email'    => 'dup@shopease.com',
            'password' => password_hash('Password123', PASSWORD_DEFAULT),
            'role'     => 'customer',
        ]);

        $result = $this->post('register', [
            'name'             => 'Another User',
            'email'            => 'dup@shopease.com',
            'password'         => 'Password123',
            'password_confirm' => 'Password123',
        ]);

        $result->assertOK();
        $result->assertSee('Email sudah terdaftar.');
    }

    public function testScenario3ShortPasswordIsRejected(): void
    {
        $result = $this->post('register', [
            'name'             => 'Short Pass',
            'email'            => 'short@shopease.com',
            'password'         => '12345',
            'password_confirm' => '12345',
        ]);

        $result->assertOK();
        $result->assertSee('Password minimal 8 karakter.');
    }

    public function testScenario4MismatchedPasswordConfirmIsRejected(): void
    {
        $result = $this->post('register', [
            'name'             => 'Mismatch Pass',
            'email'            => 'mismatch@shopease.com',
            'password'         => 'Password123',
            'password_confirm' => 'Different123',
        ]);

        $result->assertOK();
        $result->assertSee('Konfirmasi password tidak sama.');
    }

    public function testScenario5And7LoginValidAndLogoutEndsSession(): void
    {
        $userModel = new UserModel();
        $userModel->insert([
            'name'     => 'Customer Satu',
            'email'    => 'cust@shopease.com',
            'password' => password_hash('Password123', PASSWORD_DEFAULT),
            'role'     => 'customer',
        ]);
        $userModel->insert([
            'name'     => 'Admin Satu',
            'email'    => 'admin@shopease.com',
            'password' => password_hash('Password123', PASSWORD_DEFAULT),
            'role'     => 'admin',
        ]);
        $userModel->insert([
            'name'     => 'Operator Satu',
            'email'    => 'operator@shopease.com',
            'password' => password_hash('Password123', PASSWORD_DEFAULT),
            'role'     => 'operator',
        ]);

        $loginCustomer = $this->post('login', [
            'email'    => 'cust@shopease.com',
            'password' => 'Password123',
        ]);
        $loginCustomer->assertRedirectTo('/');
        $this->assertTrue((bool) session()->get('isLoggedIn'));
        $this->assertSame('customer', session()->get('role'));

        $logout = $this->post('logout');
        $logout->assertRedirectTo('/login');
        $this->assertNull(session()->get('isLoggedIn'));

        $this->post('login', [
            'email'    => 'admin@shopease.com',
            'password' => 'Password123',
        ])->assertRedirectTo('/admin');

        $this->post('login', [
            'email'    => 'operator@shopease.com',
            'password' => 'Password123',
        ])->assertRedirectTo('/operator');
    }

    public function testScenario6WrongPasswordIsRejected(): void
    {
        $userModel = new UserModel();
        $userModel->insert([
            'name'     => 'User Test',
            'email'    => 'user@shopease.com',
            'password' => password_hash('Password123', PASSWORD_DEFAULT),
            'role'     => 'customer',
        ]);

        $result = $this->post('login', [
            'email'    => 'user@shopease.com',
            'password' => 'WrongPassword',
        ]);

        $result->assertRedirect();
        $this->assertSame('Email atau password salah.', session()->getFlashdata('error'));
        $this->assertNull(session()->get('isLoggedIn'));
    }

    public function testScenario8ProtectedPagesRedirectWhenUnauthenticatedOrWrongRole(): void
    {
        $this->get('admin')->assertRedirectTo('/login');
        $this->get('operator')->assertRedirectTo('/login');

        $customerSession = [
            'isLoggedIn' => true,
            'user_id'    => 1,
            'name'       => 'Customer',
            'email'      => 'cust@shopease.com',
            'role'       => 'customer',
        ];

        $this->withSession($customerSession)->get('admin')->assertRedirectTo('/');
        $this->withSession($customerSession)->get('operator')->assertRedirectTo('/');

        $adminSession = [
            'isLoggedIn' => true,
            'user_id'    => 2,
            'name'       => 'Admin',
            'email'      => 'admin@shopease.com',
            'role'       => 'admin',
        ];
        $this->withSession($adminSession)->get('admin')->assertOK();
    }
}
