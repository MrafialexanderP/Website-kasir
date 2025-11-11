<?php
namespace App\Controllers;

use App\Models\TransactionModel;

class LaporanController extends BaseController
{
    public function index()
    {
        $month = $this->request->getGet('month') ?? date('n');
        $year = $this->request->getGet('year') ?? date('Y');

        $db = \Config\Database::connect();

        // Total transaksi bulan ini
        $query = $db->table('transactions')
            ->selectCount('id', 'total')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->get();
        $data['totalTransactions'] = $query->getRow()->total ?? 0;

        // Total penjualan
        $query = $db->table('transactions')
            ->selectSum('total_price', 'total')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->get();
        $data['totalSales'] = $query->getRow()->total ?? 0;

        // Total item terjual
        $query = $db->table('transactions')
            ->selectSum('total_qty', 'total')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->get();
        $data['totalItems'] = $query->getRow()->total ?? 0;

        // Produk terlaris (single)
        $query = $db->table('transaction_items ti')
            ->select('p.name, SUM(ti.qty) as total_qty')
            ->join('products p', 'p.id = ti.product_id')
            ->join('transactions t', 't.id = ti.transaction_id')
            ->where('YEAR(t.created_at)', $year)
            ->where('MONTH(t.created_at)', $month)
            ->groupBy('ti.product_id')
            ->orderBy('total_qty', 'DESC')
            ->limit(1)
            ->get();
        $data['topProduct'] = $query->getRowArray() ?? [];

        // List transaksi terbaru
        $data['transactions'] = $db->table('transactions')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->orderBy('created_at', 'DESC')
            ->limit(20)
            ->get()
            ->getResultArray();

        // Top 10 produk terlaris
        $data['topProducts'] = $db->table('transaction_items ti')
            ->select('p.name, SUM(ti.qty) as total_qty, SUM(ti.subtotal) as total_revenue')
            ->join('products p', 'p.id = ti.product_id')
            ->join('transactions t', 't.id = ti.transaction_id')
            ->where('YEAR(t.created_at)', $year)
            ->where('MONTH(t.created_at)', $month)
            ->groupBy('ti.product_id')
            ->orderBy('total_qty', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $data['selectedMonth'] = $month;
        $data['selectedYear'] = $year;
        $data['title'] = 'Laporan Penjualan';

        return view('laporan/index', $data);
    }
}
