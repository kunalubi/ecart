<?php
namespace App\Controllers;
use App\Models\StoreModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Register extends Controller
{
    public function index()
    {
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

            // Create store
            $storeId = $storeModel->insert([
                'name' => $storeName,
                'subdomain' => $subdomain,
                'owner_email' => $email,
                'created_at' => date('Y-m-d H:i:s'),
            ], true);

            // Create user (superadmin)
            $userModel->insert([
                'store_id' => $storeId,
                'name' => 'Superadmin',
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'superadmin',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Show success message
            return view('register', [
                'success' => 'Registration successful! Now you can access your store at <b>' . $subdomain . '.localhost/ecart/</b> (add this to your hosts file if needed).'
            ]);
        }
        return view('register');
    }
} 