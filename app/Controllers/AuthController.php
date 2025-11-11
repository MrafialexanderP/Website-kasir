<?php
namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        // If already logged in, redirect to home
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }
        
        return view('auth/login');
    }
    
    public function authenticate()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // Validation
        if (empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'Username dan password harus diisi');
        }
        
        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'Username atau password salah');
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Username atau password salah');
        }
        
        // Set session
        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'name'       => $user['name'],
            'role'       => $user['role'],
            'logged_in'  => true,
        ]);
        
        return redirect()->to('/')->with('success', 'Selamat datang, ' . $user['name'] . '!');
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda berhasil logout');
    }
}
