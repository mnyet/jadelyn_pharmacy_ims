<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductTypesTable extends Migration
{
    public function up()
    {
        // Creating the Product Types Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('jadelyn_pharmacy_product_types');
    }

    public function down()
    {
        // Dropping the Product Types Table
        $this->forge->dropTable('jadelyn_pharmacy_product_types');
    }
}
