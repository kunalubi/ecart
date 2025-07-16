<?php
namespace App\Models;
use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['store_id', 'name', 'parent_id', 'created_at'];
    protected $useTimestamps = false;
} 