<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">
  
  <?php if($this->session->flashdata('success')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      showToast('<?= $this->session->flashdata('success') ?>', 'success');
    });
  </script>
  <?php endif; ?>

  <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden flex flex-col h-full max-h-[calc(100vh-120px)]">
    <div class="px-6 py-5 border-b border-neutral-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="font-semibold text-neutral-900 text-lg">Transaksi Pembelian</h2>
        <p class="text-xs text-neutral-500 mt-1">Kelola data pembelian unit mobil dari supplier.</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative">
          <i data-lucide="search" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
          <input type="text" id="searchInput" placeholder="Cari transaksi..." 
                 class="pl-9 pr-4 py-2 text-sm border border-neutral-200 rounded-lg bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full sm:w-64 transition-all">
        </div>
        <?php if($this->session->userdata('role') === 'admin'): ?>
        <a href="<?= base_url('pembelian/tambah') ?>" class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm active:scale-95 whitespace-nowrap">
          <i data-lucide="plus" class="w-4 h-4"></i>
          Beli Mobil
        </a>
        <?php endif; ?>
      </div>
    </div>

    <div class="flex-1 overflow-auto">
      <table class="w-full text-left border-collapse" id="dataTable">
        <thead class="sticky top-0 bg-neutral-50 z-10 shadow-sm">
          <tr>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">ID / Tanggal</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Unit Mobil</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Supplier</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Total Harga</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Status</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200 w-24">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($pembelian)): ?>
          <tr>
            <td colspan="6" class="px-6 py-10 text-center text-neutral-500 text-sm">
              <div class="flex flex-col items-center justify-center">
                <i data-lucide="shopping-bag" class="w-10 h-10 text-neutral-300 mb-3"></i>
                <p>Belum ada data transaksi pembelian</p>
              </div>
            </td>
          </tr>
          <?php else: ?>
            <?php foreach($pembelian as $p): ?>
            <tr class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-4">
                <p class="text-sm font-semibold text-primary-600 data-search">TRX-B-<?= str_pad($p['id_pembelian'], 4, '0', STR_PAD_LEFT) ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= date('d M Y', strtotime($p['tgl_pembelian'])) ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm font-medium text-neutral-900 data-search"><?= $p['nama_mobil'] ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= $p['no_polisi'] ?></p>
              </td>
              <td class="px-6 py-4 text-sm text-neutral-700 data-search">
                <?= $p['nama_supplier'] ?>
              </td>
              <td class="px-6 py-4 text-sm font-mono font-medium text-neutral-900 data-search">
                Rp <?= number_format($p['harga_beli_beli'], 0, ',', '.') ?>
              </td>
              <td class="px-6 py-4">
                <?php if($p['status_pembayaran'] == 'menunggu'): ?>
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100 data-search">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Menunggu Pembayaran
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100 data-search">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Selesai
                  </span>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <?php if($p['status_pembayaran'] == 'menunggu'): ?>
                    <?php if($this->session->userdata('role') === 'admin'): ?>
                      <a href="<?= base_url('pembayaran_pembelian/bayar/'.$p['id_pembelian']) ?>" class="px-3 py-1.5 bg-primary-50 text-primary-600 hover:bg-primary-100 rounded-md text-xs font-medium transition-colors">Bayar</a>
                    <?php else: ?>
                      <span class="text-xs text-neutral-400">Menunggu</span>
                    <?php endif; ?>
                  <?php else: ?>
                    <a href="<?= base_url('cetak/faktur_pembelian/'.$p['id_pembelian']) ?>" target="_blank" class="p-1.5 rounded-md hover:bg-neutral-100 text-neutral-500 transition-colors tooltip" title="Cetak Faktur">
                      <i data-lucide="printer" class="w-4 h-4"></i>
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
