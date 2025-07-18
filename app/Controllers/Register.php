<?php
namespace App\Controllers;

use App\Models\StoreModel;
use App\Models\UserModel;

class Register extends MasterAdminBaseController
{
    public function index()
    {
        $this->requireMasterAdmin();
        if ($this->request->getMethod() === 'post') {
            $storeName = trim($this->request->getPost('store_name'));
            $subdomain = strtolower(trim($this->request->getPost('subdomain')));
            $email = trim($this->request->getPost('email'));
            $password = $this->request->getPost('password');
            $confirmPassword = $this->request->getPost('confirm_password');

            // Basic validation
            if (!$storeName || !$subdomain || !$email || !$password || !$confirmPassword) {
                return redirect()->back()->withInput()->with('error', 'All fields are required.');
            }
            if ($password !== $confirmPassword) {
                return redirect()->back()->withInput()->with('error', 'Passwords do not match.');
            }
            if (!preg_match('/^[a-z0-9]+$/', $subdomain)) {
                return redirect()->back()->withInput()->with('error', 'Subdomain must be alphanumeric and lowercase.');
            }

            $storeModel = new StoreModel();
            $userModel = new UserModel();

            // Check for unique subdomain
            if ($storeModel->where('subdomain', $subdomain)->first()) {
                return redirect()->back()->withInput()->with('error', 'Subdomain already taken.');
            }
            // Check for unique email
            if ($userModel->where('email', $email)->first()) {
                return redirect()->back()->withInput()->with('error', 'Email already registered.');
            }

            // Create store (status = pending by default)
            $storeId = $storeModel->insert([
                'name' => $storeName,
                'subdomain' => $subdomain,
                'owner_email' => $email,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
            ], true);

            // Create user (superadmin)
            $roleModel = new \App\Models\RoleModel();
            $superadminRole = $roleModel->where('name', 'superadmin')->first();
            $userModel->insert([
                'store_id' => $storeId,
                'name' => 'Superadmin',
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'superadmin',
                'role_id' => $superadminRole ? $superadminRole['id'] : null,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Show success message and redirect
            session()->setFlashdata('success', 'Store registered successfully!');
            header('Location: ' . base_url('masteradmin/dashboard'));
            exit;
        }
        return view('masteradmin/register');
    }
} 