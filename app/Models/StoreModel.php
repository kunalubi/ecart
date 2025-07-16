<?php
namespace App\Models;
use CodeIgniter\Model;

class StoreModel extends Model
{
    protected $table = 'stores';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'subdomain', 'email', 'password', 'created_at'];
} 