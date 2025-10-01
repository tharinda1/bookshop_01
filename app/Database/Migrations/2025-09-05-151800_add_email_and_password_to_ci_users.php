<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailAndPasswordToCiUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('ci_users', [
            'email'          => ['type' => 'varchar', 'constraint' => 255],
            'password_hash'  => ['type' => 'varchar', 'constraint' => 255],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('ci_users', ['email', 'password_hash']);
    }
}
