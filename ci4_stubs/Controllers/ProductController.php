<?php
namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    public function index()
    {
        $model = new ProductModel();
        $products = $model->findAll();
        return $this->response->setJSON($products);
    }

    public function store()
    {
        $model = new ProductModel();
        $data = $this->request->getJSON(true) ?: $this->request->getPost();

        if (empty($data['name']) || !isset($data['price'])) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid input']);
        }

        $insertId = $model->insert([
            'sku' => $data['sku'] ?? null,
            'name' => $data['name'],
            'price' => $data['price'],
            'stock' => $data['stock'] ?? 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['success' => true, 'id' => $insertId]);
    }

    public function delete($id = null)
    {
        if (!$id) return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing id']);

        $model = new ProductModel();
        $deleted = $model->delete((int)$id);
        if ($deleted) return $this->response->setJSON(['success' => true]);
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
    }
}
