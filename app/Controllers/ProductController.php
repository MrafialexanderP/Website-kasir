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

        // Validasi input
        if (empty($data['name']) || !isset($data['price']) || empty($data['category'])) {
            session()->setFlashdata('error', 'Nama, kategori, dan harga produk wajib diisi!');
            return redirect()->to('/products');
        }

        // Handle image upload
        $imageName = null;
        $file = $this->request->getFile('image');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validasi ukuran file (max 2MB)
            if ($file->getSize() > 2048000) {
                session()->setFlashdata('error', 'Ukuran gambar maksimal 2MB!');
                return redirect()->to('/products');
            }
            
            // Validasi ekstensi file
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($file->getExtension()), $allowedExtensions)) {
                session()->setFlashdata('error', 'Format gambar harus JPG, PNG, atau GIF!');
                return redirect()->to('/products');
            }
            
            $imageName = $file->getRandomName();
            $uploadPath = FCPATH . 'uploads/products';
            
            // Pastikan folder exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            if (!$file->move($uploadPath, $imageName)) {
                log_message('error', 'Failed to upload image: ' . $file->getErrorString());
                session()->setFlashdata('error', 'Gagal mengupload gambar: ' . $file->getErrorString());
                return redirect()->to('/products');
            }
        }

        // Insert data
        $insertData = [
            'sku' => $data['sku'] ?? null,
            'name' => $data['name'],
            'category' => $data['category'],
            'image' => $imageName,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'] ?? 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        
        $insertId = $model->insert($insertData);

        if ($insertId) {
            session()->setFlashdata('success', 'Produk "' . $data['name'] . '" berhasil ditambahkan!');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan produk. Errors: ' . json_encode($model->errors()));
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

        // Validasi input
        if (empty($data['name']) || !isset($data['price']) || empty($data['category'])) {
            session()->setFlashdata('error', 'Nama, kategori, dan harga produk wajib diisi!');
            return redirect()->to('/products');
        }

        // Handle image upload
        $imageName = $data['old_image'] ?? null; // Keep old image by default
        $file = $this->request->getFile('image');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validasi ukuran file (max 2MB)
            if ($file->getSize() > 2048000) {
                session()->setFlashdata('error', 'Ukuran gambar maksimal 2MB!');
                return redirect()->to('/products');
            }
            
            // Validasi ekstensi file
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($file->getExtension()), $allowedExtensions)) {
                session()->setFlashdata('error', 'Format gambar harus JPG, PNG, atau GIF!');
                return redirect()->to('/products');
            }
            
            // Delete old image if exists
            if ($data['old_image'] && file_exists(FCPATH . 'uploads/products/' . $data['old_image'])) {
                @unlink(FCPATH . 'uploads/products/' . $data['old_image']);
            }
            
            // Upload new image
            $imageName = $file->getRandomName();
            $uploadPath = FCPATH . 'uploads/products';
            
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            if (!$file->move($uploadPath, $imageName)) {
                log_message('error', 'Failed to upload image: ' . $file->getErrorString());
                session()->setFlashdata('error', 'Gagal mengupload gambar: ' . $file->getErrorString());
                return redirect()->to('/products');
            }
        }

        $updateData = [
            'sku' => $data['sku'] ?? null,
            'name' => $data['name'],
            'category' => $data['category'],
            'image' => $imageName,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $updated = $model->update($id, $updateData);

        if ($updated) {
            session()->setFlashdata('success', 'Produk "' . $data['name'] . '" berhasil diupdate!');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate produk. Errors: ' . json_encode($model->errors()));
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
