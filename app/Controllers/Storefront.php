<?php
namespace App\Controllers;
use App\Models\StoreModel;
use App\Models\ProductModel;
use CodeIgniter\Controller;

class Storefront extends Controller
{
    public function index()
    {
        $subdomain = getSubdomain();
        if (!$subdomain) {
            return redirect()->to('/register');
        }
        $storeModel = new StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        if (!$store) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Store not found');
        }
        // Fetch only this store's products
        $productModel = new ProductModel();
        $products = $productModel->where('store_id', $store['id'])->findAll();

        return view('storefront', [
            'store' => $store,
            'products' => $products
        ]);
    }

    public function trackOrder()
    {
        $order = null;
        $not_found = false;
        if ($this->request->getGet('order_id')) {
            $orderId = $this->request->getGet('order_id');
            $orderModel = new \App\Models\OrderModel();
            $order = $orderModel->find($orderId);
            if (!$order) $not_found = true;
        }
        return view('track_order', [
            'order' => $order,
            'not_found' => $not_found
        ]);
    }
} 