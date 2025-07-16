<?php
namespace App\Models;
use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'store_id', 'user_id', 'customer_name', 'customer_email', 'phone', 'address',
        'total', 'status', 'payment_method', 'created_at'
    ];
}
