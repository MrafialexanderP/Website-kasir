<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 16px;
            color: #666;
            font-weight: normal;
        }
        .info {
            margin-bottom: 20px;
        }
        .info-row {
            margin: 5px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .statistics {
            margin: 20px 0;
            display: table;
            width: 100%;
        }
        .stat-box {
            display: table-cell;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            width: 33%;
        }
        .stat-label {
            color: #666;
            font-size: 10px;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #343a40;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 25px 0 10px 0;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN TRANSAKSI PENJUALAN</h1>
        <h2>Alat Tulis Kantor (ATK)</h2>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="info-label">Periode:</span>
            <span><?= $month ?> <?= $year ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            <span><?= $generatedDate ?></span>
        </div>
    </div>

    <div class="statistics">
        <div class="stat-box">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value"><?= number_format($totalTransactions) ?></div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Penjualan</div>
            <div class="stat-value">Rp <?= number_format($totalSales, 0, ',', '.') ?></div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Item Terjual</div>
            <div class="stat-value"><?= number_format($totalItems) ?></div>
        </div>
    </div>

    <div class="section-title">Riwayat Transaksi</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Invoice</th>
                <th width="20%">Tanggal</th>
                <th width="15%">Waktu</th>
                <th width="15%" class="text-center">Qty</th>
                <th width="20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($transactions)): ?>
                <?php 
                date_default_timezone_set('Asia/Jakarta');
                $no = 1; 
                foreach ($transactions as $trx): 
                    // Convert UTC to Asia/Jakarta timezone
                    $datetime = new DateTime($trx['created_at'], new DateTimeZone('UTC'));
                    $datetime->setTimezone(new DateTimeZone('Asia/Jakarta'));
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= esc($trx['invoice_no']) ?></td>
                        <td><?= $datetime->format('d/m/Y') ?></td>
                        <td><?= $datetime->format('H:i:s') ?></td>
                        <td class="text-center"><?= $trx['total_qty'] ?></td>
                        <td class="text-right">Rp <?= number_format($trx['total_price'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada transaksi</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if (!empty($topProducts)): ?>
    <div class="section-title">Top 10 Produk Terlaris</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="50%">Nama Produk</th>
                <th width="20%" class="text-center">Qty Terjual</th>
                <th width="25%" class="text-right">Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($topProducts as $product): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= esc($product['name']) ?></td>
                    <td class="text-center"><?= number_format($product['total_qty']) ?></td>
                    <td class="text-right">Rp <?= number_format($product['total_revenue'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh Sistem Aplikasi Kasir ATK</p>
        <p>© <?= date('Y') ?> - Semua hak dilindungi</p>
    </div>
</body>
</html>
