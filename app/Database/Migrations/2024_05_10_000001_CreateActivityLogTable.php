<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityLogTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'module' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'comment'    => 'chat, proposal, profile, user, dll',
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'comment'    => 'create, update, delete, view, send, download, etc',
            ],
            'description' => [
                'type'   => 'TEXT',
                'null'   => true,
            ],
            'target_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'user, proposal, chat_room, message, dll',
            ],
            'target_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type'   => 'TEXT',
                'null'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('module');
        $this->forge->addKey('action');
        $this->forge->addKey('created_at');
        $this->forge->addKey(['user_id', 'created_at']);
        $this->forge->addKey(['module', 'action']);

        $this->forge->createTable('activity_logs');
    }

    public function down()
    {
        $this->forge->dropTable('activity_logs');
    }
}
