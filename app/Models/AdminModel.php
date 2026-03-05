<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'admins';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'name', 'email', 'password', 'is_active', 'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function verifyLogin(string $email, string $password): array|false
    {
        $admin = $this->where('email', $email)
                      ->where('is_active', 1)
                      ->first();

        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }

        return false;
    }
}
