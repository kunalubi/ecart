<?php
namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Profile extends Controller
{
    public function viewProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        $userModel = new UserModel();
        $user = $userModel->find(session('user_id'));
        if (!$user) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'User not found.');
        }
        return view('profile/view', ['user' => $user]);
    }

    public function editProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        $userModel = new UserModel();
        $user = $userModel->find(session('user_id'));
        if (!$user) {
            return redirect()->to(base_url('profile/view'))->with('error', 'User not found.');
        }
        $error = $success = '';
        if ($this->request->getMethod() === 'post') {
            $name = trim($this->request->getPost('name'));
            $email = trim($this->request->getPost('email'));
            $password = $this->request->getPost('password');
            $data = ['name' => $name, 'email' => $email];
            if ($password) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            $userModel->update($user['id'], $data);
            session()->set('name', $name);
            session()->set('email', $email);
            $success = 'Profile updated successfully!';
            $user = $userModel->find($user['id']);
        }
        return view('profile/edit', ['user' => $user, 'error' => $error, 'success' => $success]);
    }
} 