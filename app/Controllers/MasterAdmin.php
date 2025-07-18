<?php
namespace App\Controllers;

use App\Models\StoreModel;
use App\Models\UserModel;
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

class MasterAdmin extends MasterAdminBaseController
{
    public function dashboard()
    {
        $this->requireMasterAdmin();
        error_log('DASHBOARD SESSION: ' . print_r(session()->get(), true));
        $storeModel = new StoreModel();
        $userModel = new UserModel();
        $stores = $storeModel->findAll();
        // Attach superadmin and user count to each store
        foreach ($stores as &$store) {
            $superadmin = $userModel->where('store_id', $store['id'])->where('role', 'superadmin')->first();
            $userCount = $userModel->where('store_id', $store['id'])->countAllResults();
            $store['superadmin'] = $superadmin;
            $store['user_count'] = $userCount;
        }
        return view('masteradmin/dashboard', ['stores' => $stores]);
    }

    public function approve_store($id)
    {
        $this->requireMasterAdmin();
        $storeModel = new StoreModel();
        $storeModel->update($id, ['status' => 'approved']);
        return redirect()->to(base_url('masteradmin/dashboard'));
    }

    public function reject_store($id)
    {
        $this->requireMasterAdmin();
        $storeModel = new StoreModel();
        $storeModel->update($id, ['status' => 'rejected']);
        return redirect()->to(base_url('masteradmin/dashboard'));
    }

    public function delete_store($id)
    {
        $this->requireMasterAdmin();
        $userModel = new \App\Models\UserModel();
        $storeModel = new \App\Models\StoreModel();
        // Delete all users for this store
        $userModel->where('store_id', $id)->delete();
        // Delete the store
        $storeModel->delete($id);
        session()->setFlashdata('success', 'Store and all its users deleted successfully!');
        header('Location: ' . base_url('masteradmin/dashboard'));
        exit;
    }

    public function logout()
    {
        $this->requireMasterAdmin();
        session()->destroy();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        header('Location: ' . base_url('/'));
        exit;
    }
} 