<?php
namespace App\Controllers;
use App\Models\StoreModel;
use App\Controllers\BaseController;

class Admin extends BaseController
{
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }

    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        $subdomain = getSubdomain();
        if (!$subdomain) {
            return redirect()->to(base_url('register'));
        }
        $storeModel = new StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        // Prevent cross-store access
        if (session()->get('store_id') != $store['id']) {
            session()->destroy();
            return redirect()->to(base_url('login'));
        }
        // Get current store id
        $store_id = session('store_id');

        // Products
        $productModel = new \App\Models\ProductModel();
        $products = $productModel->where('store_id', $store_id)->findAll();

        // Categories
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->where('store_id', $store_id)->findAll();

        // Users
        $userModel = new \App\Models\UserModel();
        $users = $userModel->where('store_id', $store_id)->findAll();

        // Orders
        $orderModel = new \App\Models\OrderModel();
        $orders = $orderModel->where('store_id', $store_id)->findAll();

        // Orders by status
        $orderStatusCounts = [];
        $statuses = ['pending', 'processing', 'completed', 'cancelled', 'delivered', 'shipped'];
        foreach ($statuses as $status) {
            $orderStatusCounts[$status] = $orderModel->where('store_id', $store_id)->where('status', $status)->countAllResults();
        }

        // Pass all to view
        return view('admin/dashboard', [
            'store' => $store,
            'products' => $products,
            'categories' => $categories,
            'users' => $users,
            'orders' => $orders,
            'orderStatusCounts' => $orderStatusCounts,
        ]);
    }

    public function addUser()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'users', 'add')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to add users.');
        }
        $subdomain = getSubdomain();
        if (!$subdomain) {
            return redirect()->to(base_url('register'));
        }
        $storeModel = new \App\Models\StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        if (session()->get('store_id') != $store['id']) {
            session()->destroy();
            return redirect()->to(base_url('login'));
        }
        $roleModel = new \App\Models\RoleModel();
        $roles = $roleModel->where('store_id', $store['id'])->where('name !=', 'superadmin')->findAll();
        $error = null;
        $success = null;
        if ($this->request->getMethod() === 'post') {
            $name = trim($this->request->getPost('name'));
            $email = trim($this->request->getPost('email'));
            $password = $this->request->getPost('password');
            $role_id = $this->request->getPost('role'); // dropdown se selected role ka id
            $role = $roleModel->find($role_id);
            if (!$name || !$email || !$password || !$role_id || !$role) {
                $error = 'All fields are required.';
            } else {
                $userModel = new \App\Models\UserModel();
                if ($userModel->where('email', $email)->where('store_id', $store['id'])->first()) {
                    $error = 'Email already exists for this store.';
                } else {
                    $userModel->insert([
                        'store_id' => $store['id'],
                        'name' => $name,
                        'email' => $email,
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'role' => $role['name'],   // role ka name
                        'role_id' => $role['id'],  // role ka id (yeh zaruri hai!)
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $success = 'User added successfully!';
                }
            }
        }
        return view('admin/add_user', ['store' => $store, 'roles' => $roles, 'error' => $error, 'success' => $success]);
    }

    public function categories()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'categories', 'view')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to view categories.');
        }
        $subdomain = getSubdomain();
        if (!$subdomain) {
            return redirect()->to(base_url('register'));
        }
        $storeModel = new StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        if (session()->get('store_id') != $store['id']) {
            session()->destroy();
            return redirect()->to(base_url('login'));
        }
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->where('store_id', $store['id'])->findAll();
        return view('admin/categories', ['store' => $store, 'categories' => $categories]);
    }

    public function addCategory()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'categories', 'add')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to add categories.');
        }
        $subdomain = getSubdomain();
        if (!$subdomain) {
            return redirect()->to(base_url('register'));
        }
        $storeModel = new StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        if (session()->get('store_id') != $store['id']) {
            session()->destroy();
            return redirect()->to(base_url('login'));
        }
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->where('store_id', $store['id'])->findAll();
        $error = null;
        $success = null;
        if ($this->request->getMethod() === 'post') {
            $name = trim($this->request->getPost('name'));
            $parent_id = $this->request->getPost('parent_id') ?: null;
            if (!$name) {
                $error = 'Category name is required.';
            } else {
                $categoryModel->insert([
                    'store_id' => $store['id'],
                    'name' => $name,
                    'parent_id' => $parent_id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $success = 'Category added successfully!';
            }
        }
        return view('admin/add_category', ['store' => $store, 'categories' => $categories, 'error' => $error, 'success' => $success]);
    }

    public function products()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'products', 'view')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to view products.');
        }
        $subdomain = getSubdomain();
        if (!$subdomain) {
            return redirect()->to(base_url('register'));
        }
        $storeModel = new StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        if (session()->get('store_id') != $store['id']) {
            session()->destroy();
            return redirect()->to(base_url('login'));
        }
        $productModel = new \App\Models\ProductModel();
        $categoryModel = new \App\Models\CategoryModel();
        $products = $productModel->where('store_id', $store['id'])->findAll();
        $categories = $categoryModel->where('store_id', $store['id'])->findAll();
        return view('admin/products', ['store' => $store, 'products' => $products, 'categories' => $categories]);
    }

    public function addProduct()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'products', 'add')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to add products.');
        }
        $subdomain = getSubdomain();
        if (!$subdomain) {
            return redirect()->to(base_url('register'));
        }
        $storeModel = new StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        if (session()->get('store_id') != $store['id']) {
            session()->destroy();
            return redirect()->to(base_url('login'));
        }
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->where('store_id', $store['id'])->findAll();
        $error = null;
        $success = null;
        if ($this->request->getMethod() === 'post') {
            $name = trim($this->request->getPost('name'));
            $category_id = $this->request->getPost('category_id');
            $price = $this->request->getPost('price');
            $description = $this->request->getPost('description');
            if (!$name || !$category_id || !$price) {
                $error = 'Name, category, and price are required.';
            } else {
                $productModel = new \App\Models\ProductModel();
                $productModel->insert([
                    'store_id' => $store['id'],
                    'category_id' => $category_id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $success = 'Product added successfully!';
            }
        }
        return view('admin/add_product', ['store' => $store, 'categories' => $categories, 'error' => $error, 'success' => $success]);
    }

    public function orders()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'orders', 'view')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to view orders.');
        }
        helper('subdomain');
        $storeId = getSubdomainStoreId();
        $storeModel = new \App\Models\StoreModel();
        $store = $storeModel->find($storeId);

        $orderModel = new \App\Models\OrderModel();
        $orders = $orderModel->where('store_id', $storeId)->orderBy('id', 'DESC')->findAll();

        return view('admin/orders', [
            'store' => $store,   // <-- Yeh line add karo
            'orders' => $orders
        ]);
    }

    public function orderDetails($orderId)
    {
        // TODO: Implement order details and invoice
        return view('admin/order_details');
    }

    public function updateOrderStatus()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'orders', 'edit')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to update order status.');
        }
        $orderId = $this->request->getPost('order_id');
        $status = $this->request->getPost('status');
        $orderModel = new \App\Models\OrderModel();
        $orderModel->update($orderId, ['status' => $status]);
        return redirect()->to(base_url('admin/orders'))->with('success', 'Order status updated!');
    }

    public function invoice($orderId)
    {
        $orderModel = new \App\Models\OrderModel();
        $order = $orderModel->find($orderId);

        $orderItemModel = new \App\Models\OrderItemModel();
        $items = $orderItemModel->where('order_id', $orderId)->findAll();

        return view('admin/invoice', [
            'order' => $order,
            'items' => $items
        ]);
    }

    public function roles()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'roles', 'view')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to view roles.');
        }
        $subdomain = getSubdomain();
        if (!$subdomain) {
            return redirect()->to(base_url('register'));
        }
        $storeModel = new \App\Models\StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        if (session()->get('store_id') != $store['id']) {
            session()->destroy();
            return redirect()->to(base_url('login'));
        }
        $roleModel = new \App\Models\RoleModel();
        $roles = $roleModel->where('store_id', $store['id'])->where('name !=', 'superadmin')->findAll();
        $error = null;
        $success = null;
        if ($this->request->getMethod() === 'post') {
            $name = trim($this->request->getPost('name'));
            $description = trim($this->request->getPost('description'));
            if (!$name) {
                $error = 'Role name is required.';
            } else {
                if ($roleModel->where('name', $name)->first()) {
                    $error = 'Role already exists.';
                } else {
                    $roleModel->insert([
                        'name' => $name,
                        'description' => $description,
                        'store_id' => $store['id'],
                    ]);
                    $success = 'Role added successfully!';
                    $roles = $roleModel->findAll();
                }
            }
        }
        return view('admin/roles', [
            'store' => $store,
            'roles' => $roles,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function manageRolePermissions($roleId = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'roles', 'edit')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to manage role permissions.');
        }
        $subdomain = getSubdomain();
        if (!$subdomain) {
            return redirect()->to(base_url('register'));
        }
        $storeModel = new \App\Models\StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        if (session()->get('store_id') != $store['id']) {
            session()->destroy();
            return redirect()->to(base_url('login'));
        }
        $roleModel = new \App\Models\RoleModel();
        // Only show roles for this store, and never show superadmin
        $roles = $roleModel->where('store_id', $store['id'])->where('name !=', 'superadmin')->findAll();
        $permissionModel = new \App\Models\PermissionModel();
        // Permissions are global, or you can filter by store if you want per-store permissions
        $permissions = $permissionModel->findAll();
        $rolePermissionModel = new \App\Models\RolePermissionModel();
        $selectedRole = null;
        $rolePermissions = [];
        $success = null;
        $error = null;
        if ($roleId) {
            $selectedRole = $roleModel->where('store_id', $store['id'])->where('name !=', 'superadmin')->find($roleId);
            if ($selectedRole) {
                $rolePermissions = $rolePermissionModel->where('role_id', $roleId)->where('store_id', $store['id'])->findAll();
                $rolePermissions = array_column($rolePermissions, 'permission_id');
                if ($this->request->getMethod() === 'post') {
                    $selectedPermissions = $this->request->getPost('permissions') ?? [];
                    // Remove old permissions
                    $rolePermissionModel->where('role_id', $roleId)->where('store_id', $store['id'])->delete();
                    // Add new permissions
                    foreach ($selectedPermissions as $permId) {
                        $rolePermissionModel->insert([
                            'role_id' => $roleId,
                            'permission_id' => $permId,
                            'store_id' => $store['id'],
                        ]);
                    }
                    $success = 'Permissions updated!';
                    $rolePermissions = $rolePermissionModel->where('role_id', $roleId)->where('store_id', $store['id'])->findAll();
                    $rolePermissions = array_column($rolePermissions, 'permission_id');
                }
            } else {
                $error = 'Invalid role selected.';
            }
        }
        return view('admin/manage_role_permissions', [
            'store' => $store,
            'roles' => $roles,
            'permissions' => $permissions,
            'selectedRole' => $selectedRole,
            'rolePermissions' => $rolePermissions,
            'success' => $success,
            'error' => $error
        ]);
    }
}
