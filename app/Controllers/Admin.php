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

        // Inventory for graph
        $inventoryModel = new \App\Models\InventoryModel();
        $inventory = $inventoryModel->where('store_id', $store_id)->findAll();
        $inventoryLabels = [];
        $inventoryData = [];
        foreach ($inventory as $inv) {
            $prod = $productModel->find($inv['product_id']);
            $inventoryLabels[] = $prod ? $prod['name'] : 'Unknown';
            $inventoryData[] = $inv['stock'];
        }

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
            'inventoryLabels' => $inventoryLabels,
            'inventoryData' => $inventoryData,
        ]);
    }

    public function users()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'users', 'view')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to view users.');
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
        $userModel = new \App\Models\UserModel();
        $users = $userModel->where('store_id', $store['id'])->findAll();
        return view('admin/users', ['store' => $store, 'users' => $users]);
    }

    public function addUser()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'users', 'add')) {
            return redirect()->to(base_url('admin/users'))->with('error', 'You do not have permission to add users.');
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
            $role_id = $this->request->getPost('role');
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
                        'role' => $role['name'],
                        'role_id' => $role['id'],
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $success = 'User added successfully!';
                }
            }
        }
        return view('admin/add_user', ['store' => $store, 'roles' => $roles, 'error' => $error, 'success' => $success]);
    }

    public function editUser($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'users', 'edit')) {
            return redirect()->to(base_url('admin/users'))->with('error', 'You do not have permission to edit users.');
        }
        $userModel = new \App\Models\UserModel();
        $roleModel = new \App\Models\RoleModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User not found.');
        }
        $roles = $roleModel->where('store_id', $user['store_id'])->where('name !=', 'superadmin')->findAll();
        $error = $success = '';
        if ($this->request->getMethod() === 'post') {
            $name = trim($this->request->getPost('name'));
            $email = trim($this->request->getPost('email'));
            $role_id = $this->request->getPost('role');
            $password = $this->request->getPost('password');
            $role = $roleModel->find($role_id);
            $data = ['name' => $name, 'email' => $email, 'role_id' => $role_id, 'role' => $role['name']];
            if ($password) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            $userModel->update($id, $data);
            $success = 'User updated successfully!';
            $user = $userModel->find($id);
        }
        return view('admin/edit_user', ['user' => $user, 'roles' => $roles, 'error' => $error, 'success' => $success]);
    }

    public function deleteUser($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'users', 'delete')) {
            return redirect()->to(base_url('admin/users'))->with('error', 'You do not have permission to delete users.');
        }
        $userModel = new \App\Models\UserModel();
        $userModel->delete($id);
        return redirect()->to(base_url('admin/users'))->with('success', 'User deleted successfully!');
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
            $img = $this->request->getFile('image');
            $imageName = null;
            if ($img && $img->isValid() && !$img->hasMoved()) {
                $ext = strtolower($img->getExtension());
                if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $error = 'Only JPG and PNG images are allowed.';
                } else {
                    $productName = url_title($this->request->getPost('name'), '_', true);
                    $storeDir = ROOTPATH . 'public/uploads/' . $store['id'];
                    if (!is_dir($storeDir)) {
                        mkdir($storeDir, 0777, true);
                    }
                    $imageName = $productName . '.' . $ext;
                    $img->move($storeDir, $imageName, true);
                }
            }
            if (!$name || !$category_id || !$price) {
                $error = 'Name, category, and price are required.';
            } else if (empty($error)) {
                $productModel = new \App\Models\ProductModel();
                $productModel->insert([
                    'store_id' => $store['id'],
                    'category_id' => $category_id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'image' => $imageName,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $success = 'Product added successfully!';
            }
        }
        return view('admin/add_product', ['store' => $store, 'categories' => $categories, 'error' => $error, 'success' => $success]);
    }

    public function deleteProduct($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'products', 'delete')) {
            return redirect()->to(base_url('admin/products'))->with('error', 'You do not have permission to delete products.');
        }
        $productModel = new \App\Models\ProductModel();
        $product = $productModel->find($id);
        if ($product && $product['image']) {
            // Get store id for correct folder
            $store_id = $product['store_id'];
            $imagePath = ROOTPATH . 'public/uploads/' . $store_id . '/' . $product['image'];
            if (is_file($imagePath)) {
                @unlink($imagePath);
            }
        }
        $productModel->delete($id);
        return redirect()->to(base_url('admin/products'))->with('success', 'Product deleted successfully!');
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

    public function editCategory($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'categories', 'edit')) {
            return redirect()->to(base_url('admin/categories'))->with('error', 'You do not have permission to edit categories.');
        }
        $subdomain = getSubdomain();
        $storeModel = new \App\Models\StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        if (session()->get('store_id') != $store['id']) {
            session()->destroy();
            return redirect()->to(base_url('login'));
        }
        $categoryModel = new \App\Models\CategoryModel();
        $category = $categoryModel->find($id);
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Category not found');
        }
        $error = null;
        $success = null;
        if ($this->request->getMethod() === 'post') {
            $name = trim($this->request->getPost('name'));
            $parent_id = $this->request->getPost('parent_id') ?: null;
            if (!$name) {
                $error = 'Category name is required.';
            } else {
                $categoryModel->update($id, [
                    'name' => $name,
                    'parent_id' => $parent_id
                ]);
                $success = 'Category updated successfully!';
                // Optionally redirect to categories list
                return redirect()->to(base_url('admin/categories'))->with('success', $success);
            }
        }
        return view('admin/edit_category', [
            'store' => $store, // <-- yeh line add karo
            'category' => $category,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function deleteCategory($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'categories', 'delete')) {
            return redirect()->to(base_url('admin/categories'))->with('error', 'You do not have permission to delete categories.');
        }
        $categoryModel = new \App\Models\CategoryModel();
        $categoryModel->delete($id);
        return redirect()->to(base_url('admin/categories'))->with('success', 'Category deleted successfully!');
    }

    public function editProduct($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'products', 'edit')) {
            return redirect()->to(base_url('admin/products'))->with('error', 'You do not have permission to edit products.');
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
        $productModel = new \App\Models\ProductModel();
        $categoryModel = new \App\Models\CategoryModel();
        $product = $productModel->where('store_id', $store['id'])->find($id);
        if (!$product) {
            return redirect()->to(base_url('admin/products'))->with('error', 'Product not found.');
        }
        $categories = $categoryModel->where('store_id', $store['id'])->findAll();
        $error = $success = '';
        if ($this->request->getMethod() === 'post') {
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'price' => $this->request->getPost('price'),
                'category_id' => $this->request->getPost('category_id'),
            ];
            $img = $this->request->getFile('image');
            if ($img && $img->isValid() && !$img->hasMoved()) {
                $ext = strtolower($img->getExtension());
                if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $error = 'Only JPG and PNG images are allowed.';
                } else {
                    $productName = url_title($this->request->getPost('name'), '_', true);
                    $storeDir = ROOTPATH . 'public/uploads/' . $store['id'];
                    if (!is_dir($storeDir)) {
                        mkdir($storeDir, 0777, true);
                    }
                    $imageName = $productName . '.' . $ext;
                    $img->move($storeDir, $imageName, true);
                    // Delete old image if exists
                    if ($product['image']) {
                        $oldPath = $storeDir . '/' . $product['image'];
                        if (file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                    $data['image'] = $imageName;
                }
            }
            if (empty($error)) {
                $productModel->update($id, $data);
                $success = 'Product updated successfully!';
                $product = $productModel->find($id);
            }
        }
        return view('admin/edit_product', [
            'store' => $store,
            'product' => $product,
            'categories' => $categories,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function inventory()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'inventory', 'view')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to view inventory.');
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
        $inventoryModel = new \App\Models\InventoryModel();
        $productModel = new \App\Models\ProductModel();
        $inventory = $inventoryModel->where('store_id', $store['id'])->findAll();
        // Attach product names
        foreach ($inventory as &$inv) {
            $product = $productModel->find($inv['product_id']);
            $inv['product_name'] = $product ? $product['name'] : 'Unknown';
        }
        return view('admin/inventory', ['store' => $store, 'inventory' => $inventory]);
    }

    public function addInventory()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'inventory', 'add')) {
            return redirect()->to(base_url('admin/inventory'))->with('error', 'You do not have permission to add inventory.');
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
        $productModel = new \App\Models\ProductModel();
        $products = $productModel->where('store_id', $store['id'])->findAll();
        $error = $success = '';
        if ($this->request->getMethod() === 'post') {
            $product_id = $this->request->getPost('product_id');
            $stock = $this->request->getPost('stock');
            if (!$product_id || $stock === '' || !is_numeric($stock)) {
                $error = 'Product and stock are required.';
            } else {
                $inventoryModel = new \App\Models\InventoryModel();
                // Check if already exists
                $existing = $inventoryModel->where('store_id', $store['id'])->where('product_id', $product_id)->first();
                if ($existing) {
                    $error = 'Inventory for this product already exists.';
                } else {
                    $inventoryModel->insert([
                        'store_id' => $store['id'],
                        'product_id' => $product_id,
                        'stock' => $stock,
                    ]);
                    $success = 'Inventory added successfully!';
                }
            }
        }
        return view('admin/add_inventory', ['store' => $store, 'products' => $products, 'error' => $error, 'success' => $success]);
    }

    public function editInventory($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'inventory', 'edit')) {
            return redirect()->to(base_url('admin/inventory'))->with('error', 'You do not have permission to edit inventory.');
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
        $inventoryModel = new \App\Models\InventoryModel();
        $productModel = new \App\Models\ProductModel();
        $inventory = $inventoryModel->where('store_id', $store['id'])->find($id);
        if (!$inventory) {
            return redirect()->to(base_url('admin/inventory'))->with('error', 'Inventory not found.');
        }
        $product = $productModel->find($inventory['product_id']);
        $error = $success = '';
        if ($this->request->getMethod() === 'post') {
            $stock = $this->request->getPost('stock');
            if ($stock === '' || !is_numeric($stock)) {
                $error = 'Stock is required.';
            } else {
                $inventoryModel->update($id, ['stock' => $stock]);
                $success = 'Inventory updated successfully!';
                $inventory = $inventoryModel->find($id);
            }
        }
        return view('admin/edit_inventory', ['store' => $store, 'inventory' => $inventory, 'product' => $product, 'error' => $error, 'success' => $success]);
    }

    public function deleteInventory($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        if (!has_permission(session('role_id'), 'inventory', 'delete')) {
            return redirect()->to(base_url('admin/inventory'))->with('error', 'You do not have permission to delete inventory.');
        }
        $inventoryModel = new \App\Models\InventoryModel();
        $inventoryModel->delete($id);
        return redirect()->to(base_url('admin/inventory'))->with('success', 'Inventory deleted successfully!');
    }
}
