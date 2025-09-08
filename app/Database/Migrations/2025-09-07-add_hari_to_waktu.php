<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHariToWaktu extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_waktu', [
            'hari' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'waktu'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_waktu', 'hari');
    }
}
