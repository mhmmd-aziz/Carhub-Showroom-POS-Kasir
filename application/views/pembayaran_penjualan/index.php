<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">
  
  <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden flex flex-col h-full max-h-[calc(100vh-120px)]">
    <div class="px-6 py-5 border-b border-neutral-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="font-semibold text-neutral-900 text-lg">Histori Pembayaran Penjualan (Customer)</h2>
        <p class="text-xs text-neutral-500 mt-1">Daftar pembayaran yang telah diterima dari customer.</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative">
          <i data-lucide="search" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
          <input type="text" id="searchInput" placeholder="Cari pembayaran..." 
                 class="pl-9 pr-4 py-2 text-sm border border-neutral-200 rounded-lg bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full sm:w-64 transition-all">
        </div>
      </div>
    </div>

    <div class="flex-1 overflow-auto">
      <table class="w-full text-left border-collapse" id="dataTable">
        <thead class="sticky top-0 bg-neutral-50 z-10 shadow-sm">
          <tr>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">ID / Transaksi</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Customer & Unit</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Tahap & Tgl</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Metode</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Jumlah Bayar</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200 w-28">Bukti / Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($pembayaran)): ?>
          <tr>
            <td colspan="6" class="px-6 py-10 text-center text-neutral-500 text-sm">
              <div class="flex flex-col items-center justify-center">
                <i data-lucide="receipt" class="w-10 h-10 text-neutral-300 mb-3"></i>
                <p>Belum ada histori pembayaran dari customer</p>
              </div>
            </td>
          </tr>
          <?php else: ?>
            <?php foreach($pembayaran as $p): ?>
            <tr class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-4">
                <p class="text-sm font-semibold text-primary-600 data-search">PAY-C-<?= str_pad($p['id_pembayaran'], 4, '0', STR_PAD_LEFT) ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search">ORD-<?= str_pad($p['id_pemesanan'], 4, '0', STR_PAD_LEFT) ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm font-medium text-neutral-900 data-search"><?= $p['nama_customer'] ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= $p['nama_mobil'] ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm text-neutral-700 capitalize data-search font-medium">
                  <?= str_replace('_', ' ', $p['jenis_pembayaran']) ?>
                </p>
                <p class="text-xs text-neutral-400 mt-0.5 data-search"><?= date('d M Y', strtotime($p['tgl_bayar'])) ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm text-neutral-700 capitalize data-search flex items-center gap-1.5">
                  <?php if($p['metode_pembayaran'] == 'tunai'): ?>
                    <i data-lucide="banknote" class="w-3.5 h-3.5 text-emerald-500"></i> Tunai
                  <?php else: ?>
                    <i data-lucide="credit-card" class="w-3.5 h-3.5 text-blue-500"></i> Transfer
                  <?php endif; ?>
                </p>
              </td>
              <td class="px-6 py-4 text-sm font-mono font-medium text-emerald-600 data-search">
                Rp <?= number_format($p['jumlah_bayar'], 0, ',', '.') ?>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-1.5">
                  <!-- Cetak Kwitansi -->
                  <a href="<?= base_url('cetak/kwitansi_pembayaran/'.$p['id_pembayaran']) ?>" target="_blank"
                     class="p-1.5 rounded-md hover:bg-primary-50 text-neutral-500 hover:text-primary-600 transition-colors"
                     title="Cetak Kwitansi">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                  </a>
                  <!-- Lihat Bukti Transfer -->
                  <?php if($p['metode_pembayaran'] == 'transfer' && !empty($p['bukti_transfer'])): ?>
                    <a href="<?= base_url('uploads/bukti_bayar/'.$p['bukti_transfer']) ?>" target="_blank"
                       class="p-1.5 rounded-md hover:bg-blue-50 text-neutral-500 hover:text-blue-600 transition-colors"
                       title="Lihat Bukti Transfer">
                      <i data-lucide="image" class="w-4 h-4"></i>
                    </a>
                  <?php endif; ?>
                  <!-- Lihat KTP — sesuai CLAUDE.md: KTP wajib di DP dan Pelunasan via Transfer -->
                  <?php if(!empty($p['bukti_ktp'])): ?>
                    <a href="<?= base_url('uploads/bukti_ktp/'.$p['bukti_ktp']) ?>" target="_blank"
                       class="p-1.5 rounded-md hover:bg-amber-50 text-neutral-500 hover:text-amber-600 transition-colors"
                       title="Lihat Fotokopi KTP Customer">
                      <i data-lucide="contact" class="w-4 h-4"></i>
                    </a>
                  <?php endif; ?>
                </div>
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
  document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#dataTable tbody tr');

    rows.forEach(row => {
      if(row.cells.length === 1) return;
      
      const searchItems = row.querySelectorAll('.data-search');
      let text = '';
      searchItems.forEach(item => text += item.textContent.toLowerCase() + ' ');
      
      if(text.includes(filter)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
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
