<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat Jalan - DLV-<?= str_pad($penyerahan['id_penyerahan'], 4, '0', STR_PAD_LEFT) ?></title>
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
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">SURAT JALAN KENDARAAN</h1>
        <p class="text-sm text-gray-500 mt-1">No. Surat Jalan: <span class="font-mono font-medium text-gray-900">DLV-<?= str_pad($penyerahan['id_penyerahan'], 4, '0', STR_PAD_LEFT) ?></span></p>
        <p class="text-sm text-gray-500">Tanggal Kirim: <?= date('d M Y', strtotime($penyerahan['tgl_serah_unit'])) ?></p>
      </div>
      <div class="text-right">
        <h2 class="text-xl font-bold text-blue-600">AutoDesk Showroom</h2>
        <p class="text-sm text-gray-600">Jl. Contoh Alamat No. 123</p>
        <p class="text-sm text-gray-600">Telp: 0812-3456-7890</p>
      </div>
    </div>

    <div class="mb-8">
      <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Tujuan Pengiriman:</h3>
      <p class="text-base font-medium text-gray-900"><?= $penyerahan['nama_penerima'] ?></p>
      <?php if($penyerahan['metode_serah'] == 'diantar'): ?>
        <p class="text-sm text-gray-700 max-w-sm"><?= $penyerahan['alamat_tujuan'] ?></p>
      <?php else: ?>
        <p class="text-sm text-gray-700 max-w-sm italic">Ambil Sendiri di Showroom</p>
      <?php endif; ?>
      <p class="text-sm text-gray-700 mt-1">Telp: <?= $penyerahan['no_telp'] ?></p>
    </div>

    <p class="text-sm text-gray-700 mb-4">Mohon diterima kendaraan di bawah ini dalam keadaan baik dan lengkap:</p>

    <table class="w-full text-left border border-gray-200 mb-8">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-200">
          <th class="py-3 px-4 text-sm font-semibold text-gray-700">Spesifikasi Kendaraan</th>
          <th class="py-3 px-4 text-sm font-semibold text-gray-700 text-center">Kelengkapan</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="py-4 px-4 border-r border-gray-200 w-2/3">
            <div class="grid grid-cols-3 gap-2 text-sm">
              <div class="text-gray-500">Merek/Tipe</div>
              <div class="col-span-2 font-medium text-gray-900">: <?= $penyerahan['merek'] ?> <?= $penyerahan['nama_mobil'] ?></div>
              
              <div class="text-gray-500">Warna</div>
              <div class="col-span-2 font-medium text-gray-900">: <?= $penyerahan['warna'] ?></div>
              
              <div class="text-gray-500">No. Polisi</div>
              <div class="col-span-2 font-medium text-gray-900 font-mono">: <?= $penyerahan['no_polisi'] ?></div>
              
              <div class="text-gray-500">No. Rangka</div>
              <div class="col-span-2 font-medium text-gray-900 font-mono">: <?= $penyerahan['no_rangka'] ?></div>
              
              <div class="text-gray-500">No. Mesin</div>
              <div class="col-span-2 font-medium text-gray-900 font-mono">: <?= $penyerahan['no_mesin'] ?></div>
            </div>
          </td>
          <td class="py-4 px-4 align-top">
            <ul class="text-sm space-y-2 text-gray-700">
              <li class="flex items-center gap-2"><div class="w-4 h-4 border border-gray-400 rounded-sm"></div> 1 Unit Kendaraan</li>
              <li class="flex items-center gap-2"><div class="w-4 h-4 border border-gray-400 rounded-sm"></div> Kunci Kontak</li>
              <li class="flex items-center gap-2"><div class="w-4 h-4 border border-gray-400 rounded-sm"></div> STNK Asli</li>
              <li class="flex items-center gap-2"><div class="w-4 h-4 border border-gray-400 rounded-sm"></div> Buku Manual / Servis</li>
              <li class="flex items-center gap-2"><div class="w-4 h-4 border border-gray-400 rounded-sm"></div> Dongkrak & Toolkit</li>
            </ul>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="flex justify-between text-center pt-8 mt-12">
      <div class="w-1/3">
        <p class="text-sm text-gray-500 mb-20">Yang Menyerahkan,</p>
        <p class="font-medium text-gray-900 underline decoration-gray-300 underline-offset-4">( AutoDesk Showroom )</p>
      </div>
      <div class="w-1/3">
        <p class="text-sm text-gray-500 mb-20">Pengemudi / Ekspedisi,</p>
        <p class="font-medium text-gray-900 underline decoration-gray-300 underline-offset-4">( ...................................... )</p>
      </div>
      <div class="w-1/3">
        <p class="text-sm text-gray-500 mb-20">Tanda Terima,</p>
        <p class="font-medium text-gray-900 underline decoration-gray-300 underline-offset-4">( <?= $penyerahan['nama_penerima'] ?> )</p>
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
        filename:     'Surat_Jalan_DLV-<?= str_pad($penyerahan['id_penyerahan'], 4, '0', STR_PAD_LEFT) ?>.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };
      
      html2pdf().set(opt).from(element).save();
    }
  </script>
</body>
</html>
