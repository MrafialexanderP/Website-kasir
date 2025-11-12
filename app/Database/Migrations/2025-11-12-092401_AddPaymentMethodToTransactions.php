<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentMethodToTransactions extends Migration
{
    public function up()
    {
        $fields = [
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'total_price'
            ]
        ];
        $this->forge->addColumn('transactions', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', 'payment_method');
    }
}
