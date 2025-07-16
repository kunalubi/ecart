<?php
namespace App\Controllers;
use App\Models\StoreModel;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use CodeIgniter\Controller;

class Cart extends Controller
{
    private function getStore()
    {
        $subdomain = getSubdomain();
        $storeModel = new StoreModel();
        return $storeModel->where('subdomain', $subdomain)->first();
    }

    public function add()
    {
        $store = $this->getStore();
        $productId = $this->request->getPost('product_id');
        $qty = max(1, (int)$this->request->getPost('qty'));
        $productModel = new ProductModel();
        $product = $productModel->where('store_id', $store['id'])->find($productId);
        if (!$product) return redirect()->back()->with('error', 'Product not found.');
        $cart = session()->get('cart') ?? [];
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += $qty;
        } else {
            $cart[$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'qty' => $qty
            ];
        }
        session()->set('cart', $cart);
        return redirect()->to(base_url('cart'))->with('success', 'Added to cart!');
    }

    public function index()
    {
        $store = $this->getStore();
        $cart = session()->get('cart') ?? [];
        return view('cart', ['store' => $store, 'cart' => $cart]);
    }

    public function update()
    {
        $cart = session()->get('cart') ?? [];
        foreach ($this->request->getPost('qty') as $id => $qty) {
            if (isset($cart[$id])) {
                $cart[$id]['qty'] = max(1, (int)$qty);
            }
        }
        session()->set('cart', $cart);
        return redirect()->to(base_url('cart'));
    }

    public function remove()
    {
        $id = $this->request->getPost('id');
        $cart = session()->get('cart') ?? [];
        unset($cart[$id]);
        session()->set('cart', $cart);
        return redirect()->to(base_url('cart'));
    }

    public function checkout()
    {
        helper('subdomain');
        $storeId = getSubdomainStoreId();
        $storeModel = new StoreModel();
        $store = $storeModel->find($storeId);

        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to(base_url('cart'))->with('error', 'Cart is empty.');
        }

        return view('checkout', [
            'store' => $store,
            'cart' => $cart,
            'cartTotal' => array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart)),
            'error' => null
        ]);
    }

    public function placeOrder()
    {
        helper('subdomain');
        $storeId = getSubdomainStoreId();
        $storeModel = new StoreModel();
        $store = $storeModel->find($storeId);

        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to(base_url('cart'))->with('error', 'Cart is empty.');
        }

        // No validation, just get fields (can be empty)
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $payment = $this->request->getPost('payment_method');
        $phone = $this->request->getPost('phone');
        $address = $this->request->getPost('address');
 


        $cartTotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));
        $orderModel = new OrderModel();
        $orderData = [
            'store_id'        => $storeId,
            'user_id'         => null,
            'customer_name'   => $name,
            'customer_email'  => $email,
            'phone'           => $phone,
            'address'           => $address,
            'total'           => $cartTotal,
            'status'          => 'pending',
            'payment_method'  => $payment,
            'created_at'      => date('Y-m-d H:i:s'),
        ];
        if (!$orderModel->insert($orderData)) {
            dd($orderModel->errors());
        }
        $orderId = $orderModel->getInsertID();

        $orderItemModel = new OrderItemModel();
        foreach ($cart as $item) {
            $orderItemModel->insert([
                'order_id'   => $orderId,
                'product_id' => $item['id'],
                'quantity'   => $item['qty'],
                'price'      => $item['price'],
            ]);
        }

        session()->remove('cart');
        return view('order_success', [
            'store' => $store,
            'cart' => [],
            'order_id' => $orderId,
            'payment' => $payment, // <-- add this line
            'error' => null
        ]);
    }
} 