<?php
namespace App\Controllers;
use App\Models\StoreModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Login extends Controller
{
    public function index()
    {
        $subdomain = getSubdomain();
        // echo 'LOGIN SUBDOMAIN: ' . $subdomain; exit;
        if (!$subdomain) {
            return redirect()->to('/register');
        }
        $storeModel = new StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        $error = null;
        if ($this->request->getMethod() === 'post') {
            $email = trim($this->request->getPost('email'));
            $password = $this->request->getPost('password');
            $userModel = new UserModel();
            $user = $userModel->where('store_id', $store['id'])->where('email', $email)->first();
            if ($user && password_verify($password, $user['password'])) {
                session()->set([
                    'user_id' => $user['id'],
                    'store_id' => $store['id'],
                    'user_email' => $user['email'],
                    'user_role' => $user['role'],
                    'isLoggedIn' => true,
                ]);
                return redirect()->to(base_url('admin/dashboard'));
            } else {
                $error = 'Invalid email or password.';
            }
        }
        return view('login', ['store' => $store, 'error' => $error]);
    }
} 