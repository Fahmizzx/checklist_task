<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusColumnToChecklistMaintance extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_checklist_maintance', [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Belum Selesai', 'Sedang Dikerjakan', 'Selesai'],
                'default' => 'Belum Selesai',
                'after' => 'standar'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_checklist_maintance', 'status');
    }
}
