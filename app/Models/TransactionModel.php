<?php
namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table      = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['invoice_no','user_id','total_qty','total_price','created_at'];

    // Simple helper to generate invoice numbers
    public function generateInvoice(): string
    {
        return 'INV' . date('YmdHis') . mt_rand(100, 999);
    }
}
