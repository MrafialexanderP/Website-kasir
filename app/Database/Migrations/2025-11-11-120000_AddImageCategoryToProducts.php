<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddImageCategoryToProducts extends Migration
{
    public function up()
    {
        $fields = [
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'name'
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'category'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'image'
            ]
        ];
        
        $this->forge->addColumn('products', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('products', ['category', 'image', 'description']);
    }
}
