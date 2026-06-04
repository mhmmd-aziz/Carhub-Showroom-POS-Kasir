<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>
    <?php
      $judul = 'Kwitansi';
      if($pembayaran['jenis_pembayaran'] == 'tanda_jadi') $judul = 'Bukti Pesanan (Tanda Jadi)';
      elseif($pembayaran['jenis_pembayaran'] == 'dp') $judul = 'Kwitansi DP 30%';
      elseif($pembayaran['jenis_pembayaran'] == 'pelunasan') $judul = 'Kwitansi Pelunasan';
      echo $judul . ' - ' . $pembayaran['nama_customer'];
    ?>
  </title>
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

  <div class="max-w-2xl mx-auto bg-white p-10 shadow-sm border border-gray-200 print:shadow-none print:border-none" id="document-content">

    <!-- Header -->
    <div class="flex justify-between items-start border-b border-gray-200 pb-6 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">
          <?php
            if($pembayaran['jenis_pembayaran'] == 'tanda_jadi') echo 'BUKTI PESANAN';
            elseif($pembayaran['jenis_pembayaran'] == 'dp') echo 'KWITANSI DP';
            elseif($pembayaran['jenis_pembayaran'] == 'pelunasan') echo 'KWITANSI PELUNASAN';
          ?>
        </h1>
        <p class="text-sm text-gray-500 mt-1">No: KWT-<?= str_pad($pembayaran['id_pembayaran'], 5, '0', STR_PAD_LEFT) ?></p>
        <p class="text-sm text-gray-500">Tanggal: <?= date('d M Y', strtotime($pembayaran['tgl_bayar'])) ?></p>
      </div>
      <div class="text-right">
        <h2 class="text-lg font-bold text-blue-600">AutoDesk Showroom</h2>
        <p class="text-sm text-gray-600">Jl. Contoh Alamat No. 123</p>
        <p class="text-sm text-gray-600">Telp: 0812-3456-7890</p>
      </div>
    </div>

    <!-- Tahap Badge -->
    <div class="mb-6 flex items-center gap-3">
      <?php if($pembayaran['jenis_pembayaran'] == 'tanda_jadi'): ?>
        <span class="px-3 py-1.5 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">Tahap 1 — Tanda Jadi / Bukti Pesanan</span>
      <?php elseif($pembayaran['jenis_pembayaran'] == 'dp'): ?>
        <span class="px-3 py-1.5 bg-amber-100 text-amber-800 text-sm font-semibold rounded-full">Tahap 2 — Pembayaran DP 30%</span>
      <?php elseif($pembayaran['jenis_pembayaran'] == 'pelunasan'): ?>
        <span class="px-3 py-1.5 bg-emerald-100 text-emerald-800 text-sm font-semibold rounded-full">Tahap 3 — Pelunasan (LUNAS)</span>
      <?php endif; ?>
    </div>

    <!-- Customer & Kendaraan -->
    <div class="grid grid-cols-2 gap-8 mb-8">
      <div>
        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Data Customer</h3>
        <p class="font-semibold text-gray-900"><?= $pembayaran['nama_customer'] ?></p>
        <p class="text-sm text-gray-600">No. KTP: <?= $pembayaran['no_ktp'] ?></p>
        <p class="text-sm text-gray-600">Telp: <?= $pembayaran['no_telp'] ?></p>
      </div>
      <div>
        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Unit Kendaraan</h3>
        <p class="font-semibold text-gray-900"><?= $pembayaran['nama_mobil'] ?></p>
        <p class="text-sm text-gray-600"><?= $pembayaran['merek'] ?> <?= $pembayaran['tipe'] ?> <?= $pembayaran['tahun'] ?></p>
        <p class="text-sm text-gray-600 font-mono">Plat: <?= $pembayaran['no_polisi'] ?></p>
        <p class="text-sm text-gray-600">Warna: <?= $pembayaran['warna'] ?></p>
      </div>
    </div>

    <!-- Rincian Pembayaran -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mb-8">
      <h3 class="text-sm font-semibold text-gray-700 mb-4">Rincian Pembayaran</h3>
      <div class="space-y-3">
        <div class="flex justify-between">
          <span class="text-sm text-gray-600">Harga Jual Kendaraan</span>
          <span class="text-sm font-mono font-medium text-gray-900">Rp <?= number_format($pembayaran['harga_jual_snapshot'], 0, ',', '.') ?></span>
        </div>
        
        <?php if($pembayaran['jenis_pembayaran'] == 'tanda_jadi'): ?>
        <div class="flex justify-between">
          <span class="text-sm text-gray-600">Tagihan Tanda Jadi</span>
          <span class="text-sm font-mono font-medium text-gray-900">Rp 500.000</span>
        </div>
        <div class="flex justify-between text-xs text-gray-500">
          <span>Sisa (DP + Pelunasan)</span>
          <span class="font-mono">Rp <?= number_format($pembayaran['harga_jual_snapshot'] - 500000, 0, ',', '.') ?></span>
        </div>

        <?php elseif($pembayaran['jenis_pembayaran'] == 'dp'): ?>
        <div class="flex justify-between text-xs text-gray-500">
          <span>Tanda Jadi (dibayar sebelumnya)</span>
          <span class="font-mono">Rp 500.000</span>
        </div>
        <div class="flex justify-between">
          <span class="text-sm text-gray-600">Tagihan DP 30%</span>
          <span class="text-sm font-mono font-medium text-gray-900">Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></span>
        </div>
        <?php $sisa_pelunasan = $pembayaran['harga_jual_snapshot'] - 500000 - $pembayaran['jumlah_bayar']; ?>
        <div class="flex justify-between text-xs text-gray-500">
          <span>Sisa Pelunasan</span>
          <span class="font-mono">Rp <?= number_format($sisa_pelunasan, 0, ',', '.') ?></span>
        </div>

        <?php elseif($pembayaran['jenis_pembayaran'] == 'pelunasan'): ?>
        <div class="flex justify-between text-xs text-gray-500">
          <span>Tanda Jadi + DP (dibayar sebelumnya)</span>
          <span class="font-mono">Rp <?= number_format($pembayaran['harga_jual_snapshot'] - $pembayaran['jumlah_bayar'], 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between">
          <span class="text-sm text-gray-600">Tagihan Pelunasan</span>
          <span class="text-sm font-mono font-medium text-gray-900">Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></span>
        </div>
        <?php endif; ?>

        <div class="border-t border-gray-200 pt-3 flex justify-between">
          <span class="font-semibold text-gray-900">Jumlah Dibayar</span>
          <span class="font-mono font-bold text-xl text-emerald-700">Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between text-sm text-gray-600">
          <span>Metode</span>
          <span><?= ucfirst($pembayaran['metode_pembayaran']) ?></span>
        </div>
      </div>
    </div>

    <?php if($pembayaran['jenis_pembayaran'] == 'tanda_jadi'): ?>
    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl mb-6 text-sm text-amber-800">
      <strong>Catatan Penting:</strong> DP sebesar <strong>Rp <?= number_format($pembayaran['jumlah_bayar'] > 0 ? $pembayaran['harga_jual_snapshot'] * 0.3 : 0, 0, ',', '.') ?></strong> harus dilunasi paling lambat <strong><?= date('d M Y', strtotime($pembayaran['tgl_jatuh_tempo'])) ?></strong>. Jika tidak, tanda jadi hangus.
    </div>
    <?php endif; ?>

    <!-- Tanda Tangan -->
    <div class="flex justify-between text-center pt-8 mt-6 border-t border-gray-200">
      <div>
        <p class="text-sm text-gray-500 mb-16">Diterima oleh,</p>
        <p class="font-medium text-gray-900 underline decoration-gray-300 underline-offset-4">( <?= $pembayaran['nama_customer'] ?> )</p>
        <p class="text-xs text-gray-500 mt-1">Customer</p>
      </div>
      <div>
        <p class="text-sm text-gray-500 mb-16">Dibuat oleh,</p>
        <p class="font-medium text-gray-900 underline decoration-gray-300 underline-offset-4">( AutoDesk Showroom )</p>
        <p class="text-xs text-gray-500 mt-1">Admin</p>
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
    <button onclick="window.close()" class="px-6 py-2.5 bg-neutral-200 text-neutral-700 font-medium rounded-lg shadow-sm hover:bg-neutral-300 transition-colors">
      Tutup
    </button>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
    function downloadPDF() {
      const element = document.getElementById('document-content');
      const opt = {
        margin: 10,
        filename: 'Kwitansi_<?= strtoupper($pembayaran['jenis_pembayaran']) ?>_KWT-<?= str_pad($pembayaran['id_pembayaran'], 5, '0', STR_PAD_LEFT) ?>.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };
      html2pdf().set(opt).from(element).save();
    }
  </script>
</body>
</html>
