<?php

namespace App\Modules\CS\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tb_checklist_task';
    protected $primaryKey = 'id_checklist_task';
    protected $allowedFields = [
        'id_checklist_maintance',
        'id_users',
        'created_task',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = false;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
