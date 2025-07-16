<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['store_id', 'name', 'email', 'password', 'role', 'created_at'];
    protected $useTimestamps = false;
} 