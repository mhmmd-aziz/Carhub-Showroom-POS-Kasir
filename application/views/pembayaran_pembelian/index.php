<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">
  
  <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden flex flex-col h-full max-h-[calc(100vh-120px)]">
    <div class="px-6 py-5 border-b border-neutral-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="font-semibold text-neutral-900 text-lg">Pembayaran Pembelian</h2>
        <p class="text-xs text-neutral-500 mt-1">Kelola tagihan pembelian ke supplier dan histori pembayaran.</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative">
          <i data-lucide="search" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
          <input type="text" id="searchInput" placeholder="Cari data..." 
                 class="pl-9 pr-4 py-2 text-sm border border-neutral-200 rounded-lg bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full sm:w-64 transition-all">
        </div>
      </div>
    </div>

    <!-- Tab Navigasi -->
    <div class="border-b border-neutral-100 flex gap-6 px-6 pt-2 bg-neutral-50/50">
      <button onclick="switchTab('tagihan')" id="tabTagihan" class="pb-3 text-sm font-medium border-b-2 border-primary-600 text-primary-600 transition-colors">Menunggu Pembayaran</button>
      <button onclick="switchTab('histori')" id="tabHistori" class="pb-3 text-sm font-medium border-b-2 border-transparent text-neutral-500 hover:text-neutral-700 transition-colors">Histori Pembayaran</button>
    </div>

    <div class="flex-1 overflow-auto p-0" id="contentTagihan">
      <table class="w-full text-left border-collapse" id="dataTableTagihan">
        <thead class="sticky top-0 bg-neutral-50 z-10 shadow-sm">
          <tr>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">ID TRX / Tgl</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Supplier & Unit</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Total Tagihan</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200 w-24">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($menunggu)): ?>
          <tr>
            <td colspan="4" class="px-6 py-10 text-center text-neutral-500 text-sm">
              <div class="flex flex-col items-center justify-center">
                <i data-lucide="check-circle" class="w-10 h-10 text-neutral-300 mb-3"></i>
                <p>Tidak ada tagihan yang menunggu pembayaran.</p>
              </div>
            </td>
          </tr>
          <?php else: ?>
            <?php foreach($menunggu as $m): ?>
            <tr class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-4">
                <p class="text-sm font-semibold text-primary-600 data-search">TRX-B-<?= str_pad($m['id_pembelian'], 4, '0', STR_PAD_LEFT) ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= date('d M Y', strtotime($m['tgl_pembelian'])) ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm font-medium text-neutral-900 data-search"><?= $m['nama_supplier'] ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= $m['nama_mobil'] ?></p>
              </td>
              <td class="px-6 py-4 text-sm font-mono font-medium text-amber-600 data-search">
                Rp <?= number_format($m['harga_beli_beli'], 0, ',', '.') ?>
              </td>
              <td class="px-6 py-4">
                <?php if($this->session->userdata('role') === 'admin'): ?>
                  <a href="<?= base_url('pembayaran_pembelian/bayar/'.$m['id_pembelian']) ?>" class="px-3 py-1.5 bg-primary-50 text-primary-600 hover:bg-primary-100 rounded-md text-xs font-medium transition-colors whitespace-nowrap">Proses Bayar</a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="flex-1 overflow-auto p-0 hidden" id="contentHistori">
      <table class="w-full text-left border-collapse" id="dataTable">
        <thead class="sticky top-0 bg-neutral-50 z-10 shadow-sm">
          <tr>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">ID / Transaksi</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Supplier & Unit</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Metode & Tanggal</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Jumlah Dibayar</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200 w-24">Bukti</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($pembayaran)): ?>
          <tr>
            <td colspan="5" class="px-6 py-10 text-center text-neutral-500 text-sm">
              <div class="flex flex-col items-center justify-center">
                <i data-lucide="receipt" class="w-10 h-10 text-neutral-300 mb-3"></i>
                <p>Belum ada histori pembayaran pembelian</p>
              </div>
            </td>
          </tr>
          <?php else: ?>
            <?php foreach($pembayaran as $p): ?>
            <tr class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-4">
                <p class="text-sm font-semibold text-primary-600 data-search">PAY-B-<?= str_pad($p['id_pembayaran'], 4, '0', STR_PAD_LEFT) ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search">TRX-B-<?= str_pad($p['id_pembelian'], 4, '0', STR_PAD_LEFT) ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm font-medium text-neutral-900 data-search"><?= $p['nama_supplier'] ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= $p['nama_mobil'] ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm text-neutral-700 capitalize data-search flex items-center gap-1.5">
                  <?php if($p['jenis_pembayaran'] == 'tunai'): ?>
                    <i data-lucide="banknote" class="w-3.5 h-3.5 text-emerald-500"></i> Tunai
                  <?php else: ?>
                    <i data-lucide="credit-card" class="w-3.5 h-3.5 text-blue-500"></i> Transfer
                  <?php endif; ?>
                </p>
                <p class="text-xs text-neutral-400 mt-0.5 data-search"><?= date('d M Y', strtotime($p['tgl_bayar'])) ?></p>
              </td>
              <td class="px-6 py-4 text-sm font-mono font-medium text-emerald-600 data-search">
                Rp <?= number_format($p['jumlah_bayar'], 0, ',', '.') ?>
              </td>
              <td class="px-6 py-4">
                <?php if($p['jenis_pembayaran'] == 'transfer' && $p['bukti_transfer']): ?>
                  <a href="<?= base_url('uploads/bukti_bayar/'.$p['bukti_transfer']) ?>" target="_blank" class="p-1.5 rounded-md hover:bg-neutral-100 text-neutral-500 hover:text-primary-600 transition-colors tooltip" title="Lihat Bukti Transfer">
                    <i data-lucide="image" class="w-4 h-4"></i>
                  </a>
                <?php else: ?>
                  <span class="text-xs text-neutral-400">-</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</main>

<script>
  function switchTab(tab) {
    const tabTagihan = document.getElementById('tabTagihan');
    const tabHistori = document.getElementById('tabHistori');
    const contentTagihan = document.getElementById('contentTagihan');
    const contentHistori = document.getElementById('contentHistori');

    if(tab === 'tagihan') {
      tabTagihan.classList.add('border-primary-600', 'text-primary-600');
      tabTagihan.classList.remove('border-transparent', 'text-neutral-500');
      tabHistori.classList.remove('border-primary-600', 'text-primary-600');
      tabHistori.classList.add('border-transparent', 'text-neutral-500');
      contentTagihan.classList.remove('hidden');
      contentHistori.classList.add('hidden');
    } else {
      tabHistori.classList.add('border-primary-600', 'text-primary-600');
      tabHistori.classList.remove('border-transparent', 'text-neutral-500');
      tabTagihan.classList.remove('border-primary-600', 'text-primary-600');
      tabTagihan.classList.add('border-transparent', 'text-neutral-500');
      contentHistori.classList.remove('hidden');
      contentTagihan.classList.add('hidden');
    }
  }

  document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    
    // Cari di tabel tagihan
    const rowsTagihan = document.querySelectorAll('#dataTableTagihan tbody tr');
    rowsTagihan.forEach(row => {
      if(row.cells.length === 1) return;
      let text = '';
      row.querySelectorAll('.data-search').forEach(item => text += item.textContent.toLowerCase() + ' ');
      row.style.display = text.includes(filter) ? '' : 'none';
    });

    // Cari di tabel histori
    const rowsHistori = document.querySelectorAll('#dataTable tbody tr');
    rowsHistori.forEach(row => {
      if(row.cells.length === 1) return;
      let text = '';
      row.querySelectorAll('.data-search').forEach(item => text += item.textContent.toLowerCase() + ' ');
      row.style.display = text.includes(filter) ? '' : 'none';
    });
  });

  document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      if(typeof window.animate === 'function' && typeof window.stagger === 'function') {
        window.animate(
          "tbody tr",
          { opacity: [0, 1], x: [-8, 0] },
          { duration: 0.3, delay: window.stagger(0.04), easing: "ease-out" }
        );
      }
    }, 200);
  });
</script>
