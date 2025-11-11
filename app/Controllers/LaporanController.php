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

        // List transaksi dengan pagination
        $perPage = 10; // 10 transaksi per halaman
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $perPage;
        
        // Get sort order (newest/oldest)
        $sort = $this->request->getGet('sort') ?? 'newest';
        $orderBy = ($sort === 'oldest') ? 'ASC' : 'DESC';
        
        // Get total records for pagination
        $totalQuery = $db->table('transactions')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->countAllResults(false);
        
        $data['transactions'] = $db->table('transactions')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->orderBy('created_at', $orderBy)
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();
        
        // Pagination data
        $data['pager'] = [
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $totalQuery,
            'totalPages' => ceil($totalQuery / $perPage)
        ];
        
        $data['selectedSort'] = $sort;

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

    public function exportPdf()
    {
        $month = $this->request->getGet('month') ?? date('n');
        $year = $this->request->getGet('year') ?? date('Y');

        $db = \Config\Database::connect();

        // Get statistics
        $query = $db->table('transactions')
            ->selectCount('id', 'total')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->get();
        $totalTransactions = $query->getRow()->total ?? 0;

        $query = $db->table('transactions')
            ->selectSum('total_price', 'total')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->get();
        $totalSales = $query->getRow()->total ?? 0;

        $query = $db->table('transactions')
            ->selectSum('total_qty', 'total')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->get();
        $totalItems = $query->getRow()->total ?? 0;

        // Get all transactions
        $transactions = $db->table('transactions')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Get top products
        $topProducts = $db->table('transaction_items ti')
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

        $monthName = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Generate PDF
        $dompdf = new \Dompdf\Dompdf();
        
        $html = view('laporan/pdf_template', [
            'transactions' => $transactions,
            'topProducts' => $topProducts,
            'totalTransactions' => $totalTransactions,
            'totalSales' => $totalSales,
            'totalItems' => $totalItems,
            'month' => $monthName[$month],
            'year' => $year,
            'generatedDate' => date('d/m/Y H:i:s')
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="Laporan_' . $monthName[$month] . '_' . $year . '.pdf"')
            ->setBody($dompdf->output());
    }

    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID transaksi tidak valid'
            ]);
        }

        $db = \Config\Database::connect();
        
        try {
            // Start transaction
            $db->transStart();
            
            // Delete transaction items first
            $db->table('transaction_items')
                ->where('transaction_id', $id)
                ->delete();
            
            // Delete transaction
            $db->table('transactions')
                ->where('id', $id)
                ->delete();
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus transaksi'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteAll()
    {
        $month = $this->request->getPost('month');
        $year = $this->request->getPost('year');
        
        if (!$month || !$year) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter bulan dan tahun tidak valid'
            ]);
        }

        $db = \Config\Database::connect();
        
        try {
            // Start transaction
            $db->transStart();
            
            // Get all transaction IDs for the selected month/year
            $transactionIds = $db->table('transactions')
                ->select('id')
                ->where('YEAR(created_at)', $year)
                ->where('MONTH(created_at)', $month)
                ->get()
                ->getResultArray();
            
            if (empty($transactionIds)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tidak ada transaksi untuk dihapus'
                ]);
            }
            
            $ids = array_column($transactionIds, 'id');
            
            // Delete transaction items first
            $db->table('transaction_items')
                ->whereIn('transaction_id', $ids)
                ->delete();
            
            // Delete transactions
            $db->table('transactions')
                ->whereIn('id', $ids)
                ->delete();
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus semua transaksi'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => count($ids) . ' transaksi berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
