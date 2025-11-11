<?php
namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\TransactionModel;

class TransactionController extends BaseController
{
    /**
     * Store a transaction (expects JSON or form field 'cart' as array of {product_id, qty})
     */
    public function store()
    {
        $db = \Config\Database::connect();
        $productModel = new ProductModel();

        $cart = $this->request->getJSON(true);
        if (empty($cart)) {
            $cart = $this->request->getVar('cart');
            if (is_string($cart)) $cart = json_decode($cart, true);
        }

        if (empty($cart) || !is_array($cart)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Cart invalid or empty']);
        }

        $db->transStart();

        // Validate stock and decrease atomically
        foreach ($cart as $index => $item) {
            $product = $productModel->find((int)$item['product_id']);
            if (!$product) {
                $db->transRollback();
                return $this->response->setStatusCode(404)->setJSON(['success' => false, 'error' => 'Product not found']);
            }
            if ($product['stock'] < (int)$item['qty']) {
                $db->transRollback();
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Stok tidak cukup untuk ' . $product['name']]);
            }

            // atomic decrease
            $builder = $db->table('products');
            $builder->set('stock', 'stock - ' . (int)$item['qty'], false)
                    ->where('id', (int)$item['product_id'])
                    ->where('stock >=', (int)$item['qty'])
                    ->update();

            if ($db->affectedRows() === 0) {
                $db->transRollback();
                return $this->response->setStatusCode(409)->setJSON(['success' => false, 'error' => 'Gagal mengupdate stok']);
            }

            // fill price/subtotal for later
            $cart[$index]['price'] = $product['price'];
            $cart[$index]['subtotal'] = $product['price'] * (int)$item['qty'];
        }

        // Insert transaction
        $invoice = 'INV' . date('YmdHis') . mt_rand(100, 999);
        $totalQty = array_sum(array_column($cart, 'qty'));
        $totalPrice = array_sum(array_column($cart, 'subtotal'));

        $db->table('transactions')->insert([
            'invoice_no' => $invoice,
            'user_id' => null,
            'total_qty' => $totalQty,
            'total_price' => $totalPrice,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $transactionId = $db->insertID();

        $tiBuilder = $db->table('transaction_items');
        foreach ($cart as $item) {
            $tiBuilder->insert([
                'transaction_id' => $transactionId,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        $db->transComplete();
        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'error' => 'Gagal menyimpan transaksi']);
        }

        return $this->response->setJSON(['success' => true, 'invoice' => $invoice, 'transaction_id' => $transactionId]);
    }
}
