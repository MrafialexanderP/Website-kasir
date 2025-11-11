<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['sku','name','price','stock','created_at','updated_at'];
    protected $useTimestamps = false;

    /**
     * Decrease stock atomically using SQL: UPDATE ... SET stock = stock - ? WHERE id = ? AND stock >= ?
     * Returns true if affected (success), false otherwise.
     */
    public function decreaseStock(int $productId, int $qty): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->set('stock', 'stock - ' . (int)$qty, false)
                ->where('id', $productId)
                ->where('stock >=', $qty)
                ->update();

        return $db->affectedRows() > 0;
    }
}
