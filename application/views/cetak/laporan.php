<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan <?= ucfirst($jenis) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      @page { margin: 1cm; size: landscape; }
      body { -webkit-print-color-adjust: exact; }
      .no-print { display: none !important; }
    }
    body { font-family: sans-serif; color: #111827; }
  </style>
</head>
<body class="bg-gray-100 p-8">

  <div class="max-w-6xl mx-auto bg-white p-10 shadow-sm border border-gray-200 print:shadow-none print:border-none print:p-0" id="document-content">
    
    <div class="text-center border-b border-gray-200 pb-6 mb-6">
      <h1 class="text-2xl font-bold text-gray-900 uppercase">LAPORAN <?= strtoupper($jenis) ?></h1>
      <h2 class="text-lg font-bold text-blue-600 mt-1">AutoDesk Showroom</h2>
      <?php if($jenis != 'stok'): ?>
        <p class="text-sm text-gray-500 mt-2">Periode: <?= date('d M Y', strtotime($tgl_awal)) ?> - <?= date('d M Y', strtotime($tgl_akhir)) ?></p>
      <?php else: ?>
        <p class="text-sm text-gray-500 mt-2">Tanggal Cetak: <?= date('d M Y') ?></p>
      <?php endif; ?>
    </div>

    <table class="w-full text-left border-collapse border border-gray-200 mb-8">
      <thead>
        <tr class="bg-gray-100 border-b border-gray-300">
          <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase border-r border-gray-200">No</th>
          
          <?php if($jenis == 'penjualan'): ?>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase border-r border-gray-200">Tanggal</th>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase border-r border-gray-200">Customer</th>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase border-r border-gray-200">Mobil</th>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase text-right">Total Transaksi</th>
          
          <?php elseif($jenis == 'pembelian'): ?>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase border-r border-gray-200">Tanggal</th>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase border-r border-gray-200">Supplier</th>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase border-r border-gray-200">Mobil</th>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase text-right">Harga Beli</th>
            
          <?php elseif($jenis == 'stok'): ?>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase border-r border-gray-200">Mobil</th>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase border-r border-gray-200">Spesifikasi (Plat/Rangka/Mesin)</th>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase border-r border-gray-200">Supplier</th>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase text-center border-r border-gray-200">Stok</th>
            <th class="py-2 px-3 text-xs font-semibold text-gray-700 uppercase text-center">Status</th>
          <?php endif; ?>
          
        </tr>
      </thead>
      <tbody>
        <?php if(empty($hasil)): ?>
        <tr>
          <td colspan="6" class="py-6 text-center text-gray-500 text-sm">Tidak ada data.</td>
        </tr>
        <?php else: ?>
          <?php $no = 1; $total = 0; foreach($hasil as $row): ?>
          <tr class="border-b border-gray-200 text-sm">
            <td class="py-2 px-3 text-gray-700 border-r border-gray-200"><?= $no++ ?></td>
            
            <?php if($jenis == 'penjualan'): ?>
              <td class="py-2 px-3 text-gray-900 border-r border-gray-200"><?= date('d/m/Y', strtotime($row['tgl_penjualan'])) ?></td>
              <td class="py-2 px-3 text-gray-900 border-r border-gray-200"><?= $row['nama_customer'] ?></td>
              <td class="py-2 px-3 text-gray-900 border-r border-gray-200"><?= $row['nama_mobil'] ?> (<?= $row['no_polisi'] ?>)</td>
              <td class="py-2 px-3 text-gray-900 text-right font-mono">Rp <?= number_format($row['total_bayaran'], 0, ',', '.') ?></td>
              <?php $total += $row['total_bayaran']; ?>
            
            <?php elseif($jenis == 'pembelian'): ?>
              <td class="py-2 px-3 text-gray-900 border-r border-gray-200"><?= date('d/m/Y', strtotime($row['tgl_pembelian'])) ?></td>
              <td class="py-2 px-3 text-gray-900 border-r border-gray-200"><?= $row['nama_supplier'] ?></td>
              <td class="py-2 px-3 text-gray-900 border-r border-gray-200"><?= $row['nama_mobil'] ?> (<?= $row['no_polisi'] ?>)</td>
              <td class="py-2 px-3 text-gray-900 text-right font-mono">Rp <?= number_format($row['harga_beli_beli'], 0, ',', '.') ?></td>
              <?php $total += $row['harga_beli_beli']; ?>
              
            <?php elseif($jenis == 'stok'): ?>
              <td class="py-2 px-3 text-gray-900 border-r border-gray-200"><b><?= $row['merek'] ?></b><br><?= $row['nama_mobil'] ?> <?= $row['tipe'] ?> (<?= $row['tahun'] ?>)</td>
              <td class="py-2 px-3 text-gray-900 font-mono text-xs border-r border-gray-200"><?= $row['no_polisi'] ?><br><?= $row['no_rangka'] ?><br><?= $row['no_mesin'] ?></td>
              <td class="py-2 px-3 text-gray-900 border-r border-gray-200"><?= $row['nama_supplier'] ?></td>
              <td class="py-2 px-3 text-gray-900 text-center font-bold border-r border-gray-200"><?= $row['stok'] ?></td>
              <td class="py-2 px-3 text-center">
                <?= ucfirst($row['status_stok']) ?>
              </td>
              <?php $total += $row['stok']; ?>
            <?php endif; ?>
            
          </tr>
          <?php endforeach; ?>
          
          <?php if($jenis != 'stok'): ?>
          <tr class="bg-gray-100 font-bold border-t-2 border-gray-400">
            <td colspan="4" class="py-3 px-3 text-right text-sm text-gray-900 border-r border-gray-200">TOTAL KESELURUHAN</td>
            <td class="py-3 px-3 text-right text-sm text-gray-900 font-mono">Rp <?= number_format($total, 0, ',', '.') ?></td>
          </tr>
          <?php else: ?>
          <tr class="bg-gray-100 font-bold border-t-2 border-gray-400">
            <td colspan="3" class="py-3 px-3 text-right text-sm text-gray-900 border-r border-gray-200">TOTAL UNIT MOBIL TERSEDIA</td>
            <td class="py-3 px-3 text-center text-sm text-gray-900 border-r border-gray-200"><?= $total ?></td>
            <td></td>
          </tr>
          <?php endif; ?>
        <?php endif; ?>
      </tbody>
    </table>

  </div>

  <div class="flex justify-center gap-4 mt-6 no-print">
    <button onclick="window.print()" class="px-6 py-2.5 bg-gray-800 text-white font-medium rounded-lg shadow-sm hover:bg-gray-900 transition-colors flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
      Cetak Printer
    </button>
    <button onclick="downloadPDF()" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
      Download PDF
    </button>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
    function downloadPDF() {
      const element = document.getElementById('document-content');
      const opt = {
        margin:       10,
        filename:     'Laporan_<?= ucfirst($jenis) ?>_<?= date('Ymd') ?>.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
      };
      
      html2pdf().set(opt).from(element).save();
    }
  </script>
</body>
</html>
