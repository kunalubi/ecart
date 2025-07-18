<?php
namespace App\Models;

use CodeIgniter\Model;

class MasterAdminModel extends Model
{
    protected $table = 'master_admins';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'created_at'];
    public $timestamps = false;

    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    
} 