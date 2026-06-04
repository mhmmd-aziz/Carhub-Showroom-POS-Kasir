<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Faktur Penjualan - INV-<?= str_pad($penjualan['id_penjualan'], 4, '0', STR_PAD_LEFT) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      @page { margin: 1cm; }
      body { -webkit-print-color-adjust: exact; }
      .no-print { display: none !important; }
    }
    body { font-family: sans-serif; color: #111827; }
  </style>
</head>
<body class="bg-gray-100 p-8">

  <div class="max-w-4xl mx-auto bg-white p-10 shadow-sm border border-gray-200 print:shadow-none print:border-none print:p-0" id="document-content">
    
    <div class="flex justify-between items-start border-b border-gray-200 pb-6 mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">FAKTUR PENJUALAN</h1>
        <p class="text-sm text-gray-500 mt-1">No. Invoice: <span class="font-mono font-medium text-gray-900">INV-<?= str_pad($penjualan['id_penjualan'], 4, '0', STR_PAD_LEFT) ?></span></p>
        <p class="text-sm text-gray-500">Tanggal: <?= date('d M Y', strtotime($penjualan['tgl_penjualan'])) ?></p>
      </div>
      <div class="text-right">
        <h2 class="text-xl font-bold text-blue-600">AutoDesk Showroom</h2>
        <p class="text-sm text-gray-600">Jl. Contoh Alamat No. 123</p>
        <p class="text-sm text-gray-600">Telp: 0812-3456-7890</p>
      </div>
    </div>

    <div class="flex justify-between mb-8">
      <div>
        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Ditagihkan Kepada (Customer):</h3>
        <p class="text-base font-medium text-gray-900"><?= $penjualan['nama_customer'] ?></p>
      </div>
      <div class="text-right">
        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Status Pembayaran:</h3>
        <?php if($penjualan['status_pelunasan'] == 'lunas'): ?>
          <p class="text-lg font-bold text-green-600 uppercase">LUNAS</p>
        <?php else: ?>
          <p class="text-lg font-bold text-red-600 uppercase">BELUM LUNAS</p>
        <?php endif; ?>
      </div>
    </div>

    <table class="w-full text-left border-collapse mb-8">
      <thead>
        <tr class="bg-gray-50 border-y border-gray-200">
          <th class="py-3 px-4 text-sm font-semibold text-gray-700">Deskripsi Kendaraan</th>
          <th class="py-3 px-4 text-sm font-semibold text-gray-700 text-center">Qty</th>
          <th class="py-3 px-4 text-sm font-semibold text-gray-700 text-right">Harga Satuan</th>
          <th class="py-3 px-4 text-sm font-semibold text-gray-700 text-right">Total</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-b border-gray-100">
          <td class="py-4 px-4">
            <p class="font-medium text-gray-900"><?= $penjualan['nama_mobil'] ?></p>
            <p class="text-sm text-gray-500">Plat: <?= $penjualan['no_polisi'] ?></p>
          </td>
          <td class="py-4 px-4 text-center">1</td>
          <td class="py-4 px-4 text-right font-mono text-gray-700">Rp <?= number_format($penjualan['total_bayaran'], 0, ',', '.') ?></td>
          <td class="py-4 px-4 text-right font-mono font-medium text-gray-900">Rp <?= number_format($penjualan['total_bayaran'], 0, ',', '.') ?></td>
        </tr>
      </tbody>
    </table>

    <div class="flex justify-end mb-12">
      <div class="w-1/2 max-w-sm">
        <div class="flex justify-between py-2 border-b border-gray-200">
          <span class="text-sm font-medium text-gray-700">Subtotal</span>
          <span class="font-mono text-gray-900">Rp <?= number_format($penjualan['total_bayaran'], 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between py-3 border-b-2 border-gray-800">
          <span class="text-base font-bold text-gray-900">Total Tagihan</span>
          <span class="text-lg font-mono font-bold text-gray-900">Rp <?= number_format($penjualan['total_bayaran'], 0, ',', '.') ?></span>
        </div>
        
        <?php 
        $total_dibayar = 0;
        if($pembayaran): 
          foreach($pembayaran as $pay): 
            $total_dibayar += $pay['jumlah_bayar'];
        ?>
        <div class="flex justify-between py-1 text-sm text-green-600 mt-2">
          <span>- <?= ucwords(str_replace('_', ' ', $pay['jenis_pembayaran'])) ?> (<?= date('d/m/Y', strtotime($pay['tgl_bayar'])) ?>)</span>
          <span class="font-mono">Rp <?= number_format($pay['jumlah_bayar'], 0, ',', '.') ?></span>
        </div>
        <?php 
          endforeach; 
        endif; 
        ?>

        <div class="flex justify-between py-3 border-t border-gray-200 mt-3 text-red-600">
          <span class="text-sm font-bold">Sisa Tagihan</span>
          <span class="font-mono font-bold">Rp <?= number_format($penjualan['total_bayaran'] - $total_dibayar, 0, ',', '.') ?></span>
        </div>
      </div>
    </div>

    <div class="flex justify-between text-center pt-8">
      <div>
        <p class="text-sm text-gray-500 mb-16">Hormat Kami,</p>
        <p class="font-medium text-gray-900 underline decoration-gray-300 underline-offset-4">( AutoDesk Showroom )</p>
      </div>
      <div>
        <p class="text-sm text-gray-500 mb-16">Tanda Terima,</p>
        <p class="font-medium text-gray-900 underline decoration-gray-300 underline-offset-4">( <?= $penjualan['nama_customer'] ?> )</p>
      </div>
    </div>

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
        filename:     'Faktur_Penjualan_INV-<?= str_pad($penjualan['id_penjualan'], 4, '0', STR_PAD_LEFT) ?>.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };
      
      html2pdf().set(opt).from(element).save();
    }
  </script>
</body>
</html>
