<?php
namespace App\Controllers;
use App\Models\StoreModel;
use App\Models\UserModel;
use App\Models\MasterAdminModel;
use CodeIgniter\Controller;

class Login extends Controller
{
    public function index()
    {
        $subdomain = getSubdomain();
        // echo 'LOGIN SUBDOMAIN: ' . $subdomain; exit;
        if (!$subdomain) {
            // Master admin login flow
            $error = null;
            if ($this->request->getMethod() === 'post') {
                $email = trim($this->request->getPost('email'));
                $password = $this->request->getPost('password');
                $masterModel = new MasterAdminModel();
                $admin = $masterModel->findByEmail($email);
                if ($admin && $admin['password'] === $password) {
                    session()->set([
                        'master_admin_id' => $admin['id'],
                        'master_admin_email' => $admin['email'],
                        'isMasterAdmin' => true,
                    ]);
                    return redirect()->to(base_url('masteradmin/dashboard'));
                } else {
                    $error = 'Invalid email or password.';
                }
            }
            return view('masteradmin/login', ['error' => $error]);
        }
        $storeModel = new StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        if ($store['status'] !== 'approved') {
            $error = 'Your store is not approved yet. Please wait for admin approval.';
            return view('login', ['store' => $store, 'error' => $error]);
        }
        $error = null;
        if ($this->request->getMethod() === 'post') {
            $email = trim($this->request->getPost('email'));
            $password = $this->request->getPost('password');
            $userModel = new UserModel();
            $user = $userModel->where('store_id', $store['id'])->where('email', $email)->first();
            if ($user && $user['status'] === 'active' && password_verify($password, $user['password'])) {
                session()->set([
                    'user_id' => $user['id'],
                    'store_id' => $store['id'],
                    'user_email' => $user['email'],
                    'user_role' => $user['role'],
                    'role_id' => $user['role_id'],
                    'isLoggedIn' => true,
                ]);
                return redirect()->to(base_url('admin/dashboard'));
            } else if ($user && $user['status'] !== 'active') {
                $error = 'Your account is not active. Please contact admin.';
            } else {
                $error = 'Invalid email or password.';
            }
        }
        return view('login', ['store' => $store, 'error' => $error]);
    }
} 