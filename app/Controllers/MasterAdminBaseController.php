<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class MasterAdminBaseController extends Controller
{
    protected function requireMasterAdmin()
    {
        $sessionEmail = session()->get('master_admin_email');
        $masterModel = new \App\Models\MasterAdminModel();
        $admin = $masterModel->find(1);
        if (!session()->get('isMasterAdmin') || !$admin || $sessionEmail !== $admin['email']) {
            session()->destroy();
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 42000, '/');
            }
            header('Location: ' . base_url('/'));
            exit;
        }
    }
} 