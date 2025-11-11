<?php
namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    public function index()
    {
        $model = new ProductModel();
        $data['products'] = $model->findAll();
        $data['title'] = 'Katalog Produk';
        return view('products/index', $data);
    }

    public function store()
    {
        $model = new ProductModel();
        $data = $this->request->getPost();

        if (empty($data['name']) || !isset($data['price'])) {
            session()->setFlashdata('error', 'Nama dan harga produk wajib diisi!');
            return redirect()->to('/products');
        }

        $insertId = $model->insert([
            'sku' => $data['sku'] ?? null,
            'name' => $data['name'],
            'price' => $data['price'],
            'stock' => $data['stock'] ?? 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if ($insertId) {
            session()->setFlashdata('success', 'Produk berhasil ditambahkan!');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan produk.');
        }
        
        return redirect()->to('/products');
    }

    public function update()
    {
        $model = new ProductModel();
        $data = $this->request->getPost();
        $id = $data['id'] ?? null;

        if (!$id) {
            session()->setFlashdata('error', 'ID produk tidak valid.');
            return redirect()->to('/products');
        }

        $updated = $model->update($id, [
            'sku' => $data['sku'] ?? null,
            'name' => $data['name'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($updated) {
            session()->setFlashdata('success', 'Produk berhasil diupdate!');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate produk.');
        }

        return redirect()->to('/products');
    }

    public function delete($id = null)
    {
        if (!$id) {
            session()->setFlashdata('error', 'ID produk tidak valid.');
            return redirect()->to('/products');
        }

        $model = new ProductModel();
        $deleted = $model->delete((int)$id);
        
        if ($deleted) {
            session()->setFlashdata('success', 'Produk berhasil dihapus!');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus produk.');
        }
        
        return redirect()->to('/products');
    }
}
