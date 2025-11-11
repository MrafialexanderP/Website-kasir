<?php
namespace App\Controllers;

use App\Models\ProductModel;

class KasirController extends BaseController
{
    public function index()
    {
        $model = new ProductModel();
        $data['products'] = $model->where('stock >', 0)->findAll();
        $data['title'] = 'Kasir (POS)';
        return view('kasir/index', $data);
    }
}
