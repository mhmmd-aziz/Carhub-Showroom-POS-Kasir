  <!-- Sidebar -->
  <aside class="w-60 flex-shrink-0 bg-white border-r border-neutral-200 flex flex-col z-20 overflow-hidden">
    <!-- Logo — sticky di atas, z-10 supaya tidak tenggelam saat nav di-scroll -->
    <div class="h-24 flex items-center justify-center px-5 border-b border-neutral-100 flex-shrink-0 bg-white relative z-10">
      <a href="<?= base_url('landing') ?>" class="block w-full">
        <img src="<?= base_url('bagus2.png') ?>" alt="CarHub"
             style="width:100%;max-width:168px;height:auto;object-fit:contain;display:block;margin:0 auto;">
      </a>
    </div>

    <!-- Label "MENU" — di luar nav agar tidak ikut scroll -->
    <div class="px-6 pt-4 pb-1 flex-shrink-0">
      <p class="text-xs font-semibold text-neutral-400 uppercase tracking-wider">Menu</p>
    </div>

    <!-- Nav scrollable — hanya berisi item-item menu -->
    <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-3 pb-4 space-y-0.5">

      <?php 
        $menus = [
          ['url' => 'dashboard',            'icon' => 'layout-dashboard', 'title' => 'Dashboard'],
          ['url' => 'customer',             'icon' => 'users',            'title' => 'Customer'],
          ['url' => 'supplier',             'icon' => 'truck',            'title' => 'Supplier'],
          ['url' => 'mobil',                'icon' => 'car-front',        'title' => 'Mobil'],
          ['url' => 'pembelian',            'icon' => 'shopping-bag',     'title' => 'Pembelian'],
          ['url' => 'pembayaran_pembelian', 'icon' => 'banknote',         'title' => 'Pembayaran Pembelian'],
          ['url' => 'pemesanan',            'icon' => 'calendar-check',   'title' => 'Pemesanan'],
          ['url' => 'penjualan',            'icon' => 'shopping-cart',    'title' => 'Penjualan'],
          ['url' => 'pembayaran_penjualan', 'icon' => 'wallet',           'title' => 'Pembayaran Penjualan'],
          ['url' => 'penyerahan_mobil',     'icon' => 'key',              'title' => 'Penyerahan Mobil'],
          ['url' => 'laporan',              'icon' => 'file-text',        'title' => 'Laporan'],
        ];

        $current_uri = $this->uri->segment(1);
        if(empty($current_uri)) $current_uri = 'dashboard';

        foreach($menus as $m): 
          $active = ($current_uri == $m['url'])
            ? 'bg-primary-50 text-primary-600 font-medium'
            : 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900 font-medium';
      ?>
      <a href="<?= base_url($m['url']) ?>"
         class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors <?= $active ?>"
         data-url="<?= $m['url'] ?>">
        <i data-lucide="<?= $m['icon'] ?>" class="w-4 h-4 flex-shrink-0"></i>
        <?= $m['title'] ?>
      </a>
      <?php endforeach; ?>
    </nav>

    <!-- Bottom card — profile & logout, flex-shrink-0 agar tidak ikut ter-scroll -->
    <div class="p-3 border-t border-neutral-100 flex-shrink-0">
      <div class="rounded-xl p-4 text-white" style="background:#021d6c;">
        <div class="flex items-center gap-2 mb-1">
          <img src="<?= base_url('bagus.png') ?>" alt="CarHub" style="height:32px;width:auto;">
        </div>
        <p class="text-xs text-neutral-300 mb-3 truncate"><?= $this->session->userdata('nama_lengkap') ?: 'Administrator' ?></p>
        <a href="<?= base_url('auth/logout') ?>"
           class="block text-center bg-white text-neutral-900 text-xs font-semibold py-2 rounded-lg hover:bg-neutral-100 transition-colors">
          Logout
        </a>
      </div>
    </div>
  </aside>

  <script>
    // ── Simpan & restore posisi scroll sidebar ────────────────────────────
    // Setiap klik menu disimpan dulu posisi scroll sebelum navigasi
    const NAV_KEY = 'sidebar_scroll';
    const nav = document.getElementById('sidebar-nav');

    // Restore posisi scroll saat halaman dimuat
    if (nav) {
      const saved = sessionStorage.getItem(NAV_KEY);
      if (saved !== null) {
        nav.scrollTop = parseInt(saved, 10);
      }
    }

    // Simpan posisi scroll sebelum pindah halaman
    document.querySelectorAll('.sidebar-item').forEach(function(link) {
      link.addEventListener('click', function() {
        if (nav) {
          sessionStorage.setItem(NAV_KEY, nav.scrollTop);
        }
      });
    });
  </script>
