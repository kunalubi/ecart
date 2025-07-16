<?php
namespace App\Models;
use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['store_id', 'category_id', 'name', 'description', 'price', 'image', 'created_at'];
    protected $useTimestamps = false;
}
