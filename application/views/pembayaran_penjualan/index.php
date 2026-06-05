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

  <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden flex flex-col" style="min-height: calc(100vh - 120px)">
    
    <!-- Header -->
    <div class="px-6 py-5 border-b border-neutral-100">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h2 class="font-semibold text-neutral-900 text-lg">Pembayaran Penjualan</h2>
          <p class="text-xs text-neutral-500 mt-1">Kelola tagihan pelanggan dan histori pembayaran.</p>
        </div>
        <div class="relative">
          <i data-lucide="search" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
          <input type="text" id="searchInput" placeholder="Cari data..."
                 class="pl-9 pr-4 py-2 text-sm border border-neutral-200 rounded-lg bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full sm:w-64 transition-all">
        </div>
      </div>
    </div>

    <!-- Tab Navigasi -->
    <div class="border-b border-neutral-100 flex gap-6 px-6 pt-2 bg-neutral-50/50 overflow-x-auto">
      <button onclick="switchTab('tanda_jadi')" id="tab_tanda_jadi" class="pb-3 text-sm font-medium border-b-2 border-primary-600 text-primary-600 transition-colors whitespace-nowrap">Tanda Jadi <span class="ml-1 bg-primary-100 text-primary-700 text-xs px-1.5 py-0.5 rounded-full font-mono"><?= count($menunggu_tanda_jadi) ?></span></button>
      <button onclick="switchTab('dp')" id="tab_dp" class="pb-3 text-sm font-medium border-b-2 border-transparent text-neutral-500 hover:text-neutral-700 transition-colors whitespace-nowrap">DP (Down Payment) <span class="ml-1 bg-neutral-200 text-neutral-700 text-xs px-1.5 py-0.5 rounded-full font-mono"><?= count($menunggu_dp) ?></span></button>
      <button onclick="switchTab('pelunasan')" id="tab_pelunasan" class="pb-3 text-sm font-medium border-b-2 border-transparent text-neutral-500 hover:text-neutral-700 transition-colors whitespace-nowrap">Pelunasan <span class="ml-1 bg-neutral-200 text-neutral-700 text-xs px-1.5 py-0.5 rounded-full font-mono"><?= count($menunggu_pelunasan) ?></span></button>
      <button onclick="switchTab('histori')" id="tab_histori" class="pb-3 text-sm font-medium border-b-2 border-transparent text-neutral-500 hover:text-neutral-700 transition-colors whitespace-nowrap">Histori Pembayaran / Refund</button>
    </div>

    <!-- ── KONTEN TANDA JADI ──────────────────────────────────────────────── -->
    <div class="flex-1 overflow-auto p-0 tab-content" id="content_tanda_jadi">
      <table class="w-full text-left border-collapse dataTable">
        <thead class="sticky top-0 bg-neutral-50 z-10 shadow-sm">
          <tr>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">ID Pesanan</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Customer &amp; Unit</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Total Harga</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200 w-28">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($menunggu_tanda_jadi)): ?>
          <tr><td colspan="4" class="px-6 py-12 text-center text-neutral-500 text-sm">Tidak ada tagihan Tanda Jadi.</td></tr>
          <?php else: ?>
            <?php foreach($menunggu_tanda_jadi as $p): ?>
            <tr class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-4">
                <p class="text-sm font-semibold text-primary-600 data-search">ORD-<?= str_pad($p['id_pemesanan'], 4, '0', STR_PAD_LEFT) ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= date('d M Y', strtotime($p['tgl_pesan'])) ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm font-medium text-neutral-900 data-search"><?= $p['nama_customer'] ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= $p['nama_mobil'] ?></p>
              </td>
              <td class="px-6 py-4 text-sm font-mono font-medium text-neutral-900 data-search">Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
              <td class="px-6 py-4">
                <a href="<?= base_url('pembayaran_penjualan/bayar/'.$p['id_pemesanan']) ?>" class="px-3 py-1.5 bg-primary-50 text-primary-600 hover:bg-primary-100 rounded-md text-xs font-medium transition-colors whitespace-nowrap">Proses Bayar</a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- ── KONTEN DP ──────────────────────────────────────────────── -->
    <div class="flex-1 overflow-auto p-0 hidden tab-content" id="content_dp">
      <table class="w-full text-left border-collapse dataTable">
        <thead class="sticky top-0 bg-neutral-50 z-10 shadow-sm">
          <tr>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">ID Pesanan</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Customer &amp; Unit</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Total Harga</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200 w-28">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($menunggu_dp)): ?>
          <tr><td colspan="4" class="px-6 py-12 text-center text-neutral-500 text-sm">Tidak ada tagihan DP.</td></tr>
          <?php else: ?>
            <?php foreach($menunggu_dp as $p): ?>
            <tr class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-4">
                <p class="text-sm font-semibold text-primary-600 data-search">ORD-<?= str_pad($p['id_pemesanan'], 4, '0', STR_PAD_LEFT) ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm font-medium text-neutral-900 data-search"><?= $p['nama_customer'] ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= $p['nama_mobil'] ?></p>
              </td>
              <td class="px-6 py-4 text-sm font-mono font-medium text-neutral-900 data-search">Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
              <td class="px-6 py-4">
                <a href="<?= base_url('pembayaran_penjualan/bayar/'.$p['id_pemesanan']) ?>" class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-md text-xs font-medium transition-colors whitespace-nowrap">Proses DP</a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- ── KONTEN PELUNASAN ──────────────────────────────────────────────── -->
    <div class="flex-1 overflow-auto p-0 hidden tab-content" id="content_pelunasan">
      <table class="w-full text-left border-collapse dataTable">
        <thead class="sticky top-0 bg-neutral-50 z-10 shadow-sm">
          <tr>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">ID Penjualan</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Customer &amp; Unit</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Total Harga</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200 w-28">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($menunggu_pelunasan)): ?>
          <tr><td colspan="4" class="px-6 py-12 text-center text-neutral-500 text-sm">Tidak ada tagihan Pelunasan.</td></tr>
          <?php else: ?>
            <?php foreach($menunggu_pelunasan as $p): ?>
            <tr class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-4">
                <p class="text-sm font-semibold text-primary-600 data-search">JUAL-<?= str_pad($p['id_penjualan'], 4, '0', STR_PAD_LEFT) ?></p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm font-medium text-neutral-900 data-search"><?= $p['nama_customer'] ?></p>
                <p class="text-xs text-neutral-500 mt-0.5 data-search"><?= $p['nama_mobil'] ?></p>
              </td>
              <td class="px-6 py-4 text-sm font-mono font-medium text-neutral-900 data-search">Rp <?= number_format($p['total_bayaran'], 0, ',', '.') ?></td>
              <td class="px-6 py-4">
                <a href="<?= base_url('pembayaran_penjualan/bayar/'.$p['id_pemesanan']) ?>" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-md text-xs font-medium transition-colors whitespace-nowrap">Proses Lunas</a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- ── KONTEN HISTORI ──────────────────────────────────────────────── -->
    <div class="flex-1 overflow-auto p-0 hidden tab-content" id="content_histori">
      <table class="w-full text-left border-collapse dataTable">
        <thead class="sticky top-0 bg-neutral-50 z-10 shadow-sm">
          <tr>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">ID / Transaksi</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Customer &amp; Unit</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Tahap &amp; Tgl</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200">Jumlah Bayar</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-4 border-b border-neutral-200 w-28">Bukti</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($pembayaran)): ?>
          <tr><td colspan="5" class="px-6 py-12 text-center text-neutral-500 text-sm">Belum ada histori pembayaran dari customer</td></tr>
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
                <?php
                  $badge_cfg = [
                    'tanda_jadi' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200',   'label' => 'Bukti Pesanan'],
                    'dp'         => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200',       'label' => 'DP'],
                    'pelunasan'  => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200','label' => 'Pelunasan'],
                    'refund'     => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200',         'label' => 'Refund'],
                  ];
                  $jenis = $p['jenis_pembayaran'];
                  $bcfg  = $badge_cfg[$jenis] ?? ['bg' => 'bg-neutral-100 text-neutral-600 border-neutral-200', 'label' => ucfirst($jenis)];
                ?>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border <?= $bcfg['bg'] ?> data-search">
                  <?= $bcfg['label'] ?>
                </span>
                <p class="text-xs text-neutral-400 mt-1 data-search"><?= date('d M Y', strtotime($p['tgl_bayar'])) ?></p>
              </td>
              <td class="px-6 py-4 text-sm font-mono font-semibold text-emerald-600 data-search">
                Rp <?= number_format($p['jumlah_bayar'], 0, ',', '.') ?>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-1.5">
                  <a href="<?= base_url('cetak/kwitansi_pembayaran/'.$p['id_pembayaran']) ?>" target="_blank" class="p-1.5 rounded-md hover:bg-primary-50 text-neutral-400 hover:text-primary-600 transition-colors tooltip" title="Cetak Kwitansi"><i data-lucide="printer" class="w-4 h-4"></i></a>
                  <?php if($p['metode_pembayaran'] == 'transfer' && !empty($p['bukti_transfer'])): ?>
                    <a href="<?= base_url('uploads/bukti_bayar/'.$p['bukti_transfer']) ?>" target="_blank" class="p-1.5 rounded-md hover:bg-blue-50 text-neutral-400 hover:text-blue-600 transition-colors tooltip" title="Lihat Bukti Transfer"><i data-lucide="image" class="w-4 h-4"></i></a>
                  <?php endif; ?>
                  <?php if(!empty($p['bukti_ktp'])): ?>
                    <a href="<?= base_url('uploads/bukti_ktp/'.$p['bukti_ktp']) ?>" target="_blank" class="p-1.5 rounded-md hover:bg-amber-50 text-neutral-400 hover:text-amber-600 transition-colors tooltip" title="Lihat Fotokopi KTP"><i data-lucide="contact" class="w-4 h-4"></i></a>
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
  function switchTab(tab) {
    const tabs = ['tanda_jadi', 'dp', 'pelunasan', 'histori'];
    tabs.forEach(t => {
      const tabBtn = document.getElementById('tab_' + t);
      const content = document.getElementById('content_' + t);
      
      if(t === tab) {
        tabBtn.classList.add('border-primary-600', 'text-primary-600');
        tabBtn.classList.remove('border-transparent', 'text-neutral-500');
        if(tabBtn.querySelector('span')) {
            tabBtn.querySelector('span').classList.replace('bg-neutral-200', 'bg-primary-100');
            tabBtn.querySelector('span').classList.replace('text-neutral-700', 'text-primary-700');
        }
        content.classList.remove('hidden');
      } else {
        tabBtn.classList.remove('border-primary-600', 'text-primary-600');
        tabBtn.classList.add('border-transparent', 'text-neutral-500');
        if(tabBtn.querySelector('span')) {
            tabBtn.querySelector('span').classList.replace('bg-primary-100', 'bg-neutral-200');
            tabBtn.querySelector('span').classList.replace('text-primary-700', 'text-neutral-700');
        }
        content.classList.add('hidden');
      }
    });
  }

  document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const tables = document.querySelectorAll('.dataTable');
    
    tables.forEach(table => {
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
          if(row.cells.length === 1) return;
          let text = '';
          row.querySelectorAll('.data-search').forEach(item => text += item.textContent.toLowerCase() + ' ');
          row.style.display = text.includes(filter) ? '' : 'none';
        });
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
