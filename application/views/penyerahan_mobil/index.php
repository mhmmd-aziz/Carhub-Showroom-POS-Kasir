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
        <h2 class="font-semibold text-neutral-900 text-lg">Histori Penyerahan Mobil</h2>
        <p class="text-xs text-neutral-500 mt-1">Daftar serah terima kendaraan kepada customer.</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative">
          <i data-lucide="search" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
          <input type="text" id="searchInput" placeholder="Cari penyerahan..." 
                 class="pl-9 pr-4 py-2 text-sm border border-neutral-200 rounded-lg bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full sm:w-64 transition-all">
        </div>
      </div>
    </div>

    <div class="flex-1 overflow-auto">
      <table class="w-full text-left border-collapse" id="dataTable">
        <thead class="sticky top-0 bg-neutral-50 z-10 shadow-sm">
          <tr>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">ID / Invoice</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Customer & Unit</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Metode & Penerima</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Tanggal Serah</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200 w-24">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($penyerahan)): ?>
          <tr>
            <td colspan="5" class="px-6 py-10 text-center text-neutral-500 text-sm">
              <div class="flex flex-col items-center justify-center">
                <i data-lucide="truck" class="w-10 h-10 text-neutral-300 mb-3"></i>
                <p>Belum ada data penyerahan mobil.</p>
              </div>
            </td>
          </tr>
          <?php else: ?>
            <?php foreach($penyerahan as $p): ?>
            <tr class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-4">
                <p class="text-sm font-semibold text-primary-600 data-search">DLV-<?= str_pad($p['id_penyerahan'], 4, '0', STR_PAD_LEFT) ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search">INV-<?= str_pad($p['id_penjualan'], 4, '0', STR_PAD_LEFT) ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm font-medium text-neutral-900 data-search"><?= $p['nama_customer'] ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= $p['nama_mobil'] ?> (<?= $p['no_polisi'] ?>)</p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm text-neutral-700 data-search capitalize">
                  <?php if($p['metode_serah'] == 'diantar'): ?>
                    <i data-lucide="truck" class="w-3 h-3 inline-block mr-1 text-indigo-500"></i> Diantar
                  <?php else: ?>
                    <i data-lucide="map-pin" class="w-3 h-3 inline-block mr-1 text-emerald-500"></i> Ambil Sendiri
                  <?php endif; ?>
                </p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search">Penerima: <?= $p['nama_penerima'] ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm text-neutral-900 data-search">Unit: <?= date('d/m/Y', strtotime($p['tgl_serah_unit'])) ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search">BPKB: <?= date('d/m/Y', strtotime($p['tgl_serah_bpkb'])) ?></p>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <?php if($p['metode_serah'] == 'diantar'): ?>
                    <a href="<?= base_url('cetak/surat_jalan/'.$p['id_penyerahan']) ?>" target="_blank" class="p-1.5 rounded-md hover:bg-neutral-100 text-neutral-500 transition-colors tooltip" title="Cetak Surat Jalan">
                      <i data-lucide="file-text" class="w-4 h-4"></i>
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
