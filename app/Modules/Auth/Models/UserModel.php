<?php namespace App\Modules\Auth\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'tb_users';
    protected $primaryKey       = 'id_users';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nama',
        'username',
        'email',
        'password',
        'role',
        'aktivasi',
        'banned',
        'reset_token',
        'reset_token_expires_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = []; 

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            if (strpos($data['data']['password'], '$2y$') !== 0) {
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            }
        }
        return $data;
    }
}
