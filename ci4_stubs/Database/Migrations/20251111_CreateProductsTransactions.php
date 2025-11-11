<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateProductsTransactions extends Migration
{
    public function up()
    {
        // products
        $this->forge->addField([
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'sku' => ['type'=>'VARCHAR','constraint'=>50,'null'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>255],
            'price' => ['type'=>'DECIMAL','constraint'=>'12,2','default'=>0],
            'stock' => ['type'=>'INT','constraint'=>11,'default'=>0],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('sku', false, true);
        $this->forge->createTable('products', true);

        // transactions
        $this->forge->addField([
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'invoice_no' => ['type'=>'VARCHAR','constraint'=>50,'null'=>true],
            'user_id' => ['type'=>'INT','constraint'=>11,'null'=>true],
            'total_qty' => ['type'=>'INT','constraint'=>11,'default'=>0],
            'total_price' => ['type'=>'DECIMAL','constraint'=>'14,2','default'=>0],
            'created_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('invoice_no', false, true);
        $this->forge->createTable('transactions', true);

        // transaction_items
        $this->forge->addField([
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'transaction_id' => ['type'=>'INT','constraint'=>11],
            'product_id' => ['type'=>'INT','constraint'=>11],
            'qty' => ['type'=>'INT','constraint'=>11,'default'=>0],
            'price' => ['type'=>'DECIMAL','constraint'=>'12,2','default'=>0],
            'subtotal' => ['type'=>'DECIMAL','constraint'=>'14,2','default'=>0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('transaction_items', true);
    }

    public function down()
    {
        $this->forge->dropTable('transaction_items', true);
        $this->forge->dropTable('transactions', true);
        $this->forge->dropTable('products', true);
    }
}
