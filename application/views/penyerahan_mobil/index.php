<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">
  
  <?php if($this->session->flashdata('success')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      showToast('<?= $this->session->flashdata('success') ?>', 'success');
    });
  </script>
  <?php endif; ?>
  <?php if($this->session->flashdata('error')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      showToast('<?= $this->session->flashdata('error') ?>', 'error');
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
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Customer &amp; Unit</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Metode &amp; Penerima</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Tanggal Serah</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Status Penyerahan</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200 w-28">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($penyerahan)): ?>
          <tr>
            <td colspan="6" class="px-6 py-10 text-center text-neutral-500 text-sm">
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
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= $p['nama_mobil'] ?> (<?= $p['no_polisi'] ?: '-' ?>)</p>
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
                <p class="text-xs text-neutral-500 mt-0.5 data-search">BPKB: <?= $p['tgl_serah_bpkb'] ? date('d/m/Y', strtotime($p['tgl_serah_bpkb'])) : '-' ?></p>
              </td>

              <!-- ── Kolom Status Penyerahan ── -->
              <td class="px-6 py-4">
                <?php $status = $p['status_penyerahan'] ?? 'dalam_proses'; ?>
                <?php if($status === 'selesai'): ?>
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                    Selesai
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    Dalam Proses
                  </span>
                <?php endif; ?>
              </td>

              <!-- ── Kolom Aksi ── -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <!-- Cetak Surat Jalan -->
                  <?php if($p['metode_serah'] == 'diantar'): ?>
                    <a href="<?= base_url('cetak/surat_jalan/'.$p['id_penyerahan']) ?>" target="_blank"
                       class="p-1.5 rounded-md hover:bg-neutral-100 text-neutral-500 hover:text-indigo-600 transition-colors"
                       title="Cetak Surat Jalan">
                      <i data-lucide="file-text" class="w-4 h-4"></i>
                    </a>
                  <?php endif; ?>
                  <!-- Tombol Konfirmasi Selesai -->
                  <?php if(($p['status_penyerahan'] ?? 'dalam_proses') !== 'selesai'): ?>
                    <button onclick="konfirmasiSelesai(<?= $p['id_penyerahan'] ?>, '<?= addslashes($p['nama_customer']) ?>')"
                            class="p-1.5 rounded-md hover:bg-emerald-50 text-neutral-400 hover:text-emerald-600 transition-colors"
                            title="Konfirmasi Sudah Diterima">
                      <i data-lucide="check-circle" class="w-4 h-4"></i>
                    </button>
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

  <!-- Modal Konfirmasi Selesai -->
  <div id="modalSelesai" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6 animate-in">
      <div class="flex items-center gap-3 mb-4">
        <div class="p-2.5 bg-emerald-100 rounded-xl">
          <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
        </div>
        <div>
          <h3 class="font-semibold text-neutral-900">Konfirmasi Penerimaan</h3>
          <p class="text-sm text-neutral-500">Ubah status menjadi Selesai</p>
        </div>
      </div>
      <p class="text-sm text-neutral-700 mb-1">Konfirmasi bahwa kendaraan untuk:</p>
      <p id="modalNamaCustomer" class="text-base font-semibold text-neutral-900 mb-4"></p>
      <p class="text-sm text-neutral-600 mb-6">
        sudah diterima oleh customer / penerima. Status penyerahan akan diubah menjadi <strong class="text-emerald-600">Selesai</strong>.
      </p>
      <div class="flex gap-3 justify-end">
        <button onclick="tutupModal()" 
                class="px-4 py-2 text-sm font-medium text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">
          Batal
        </button>
        <a id="linkSelesai" href="#"
           class="px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors flex items-center gap-1.5">
          <i data-lucide="check" class="w-4 h-4"></i>
          Ya, Sudah Diterima
        </a>
      </div>
    </div>
  </div>

</main>

<script>
  // Search filter
  document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#dataTable tbody tr');
    rows.forEach(row => {
      if(row.cells.length === 1) return;
      const searchItems = row.querySelectorAll('.data-search');
      let text = '';
      searchItems.forEach(item => text += item.textContent.toLowerCase() + ' ');
      row.style.display = text.includes(filter) ? '' : 'none';
    });
  });

  // Modal konfirmasi selesai
  function konfirmasiSelesai(idPenyerahan, namaCustomer) {
    document.getElementById('modalNamaCustomer').textContent = namaCustomer;
    document.getElementById('linkSelesai').href = '<?= base_url('penyerahan_mobil/selesai/') ?>' + idPenyerahan;
    document.getElementById('modalSelesai').classList.remove('hidden');
    document.getElementById('modalSelesai').classList.add('flex');
  }

  function tutupModal() {
    document.getElementById('modalSelesai').classList.add('hidden');
    document.getElementById('modalSelesai').classList.remove('flex');
  }

  // Tutup modal saat klik backdrop
  document.getElementById('modalSelesai').addEventListener('click', function(e) {
    if(e.target === this) tutupModal();
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
