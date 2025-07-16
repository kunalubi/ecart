<?php
namespace App\Controllers;
use App\Models\StoreModel;
use CodeIgniter\Controller;

class Admin extends Controller
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
        $userModel = new \App\Models\UserModel();
        $users = $userModel->where('store_id', $store['id'])->findAll();
        return view('admin/dashboard', ['store' => $store, 'users' => $users]);
    }

    public function addUser()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
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
        // Prevent cross-store access
        if (session()->get('store_id') != $store['id']) {
            session()->destroy();
            return redirect()->to(base_url('login'));
        }
        $error = null;
        $success = null;
        if ($this->request->getMethod() === 'post') {
            $name = trim($this->request->getPost('name'));
            $email = trim($this->request->getPost('email'));
            $password = $this->request->getPost('password');
            $role = $this->request->getPost('role');
            if (!$name || !$email || !$password || !$role) {
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
                        'role' => $role,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $success = 'User added successfully!';
                }
            }
        }
        return view('admin/add_user', ['store' => $store, 'error' => $error, 'success' => $success]);
    }

    public function categories()
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
}
