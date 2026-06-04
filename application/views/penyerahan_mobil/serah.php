<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">

  <div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
      <a href="<?= base_url('penjualan') ?>" class="p-2 rounded-lg bg-white border border-neutral-200 text-neutral-500 hover:text-neutral-900 hover:bg-neutral-50 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
      </a>
      <div>
        <h2 class="text-xl font-bold text-neutral-900">Serah Terima Mobil</h2>
        <p class="text-sm text-neutral-500">Invoice: INV-<?= str_pad($penjualan['id_penjualan'], 4, '0', STR_PAD_LEFT) ?></p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-5">
          <h3 class="text-sm font-semibold text-neutral-900 mb-4 flex items-center gap-2">
            <i data-lucide="car" class="w-4 h-4 text-primary-500"></i>
            Detail Kendaraan & Pembeli
          </h3>
          
          <div class="space-y-4">
            <div>
              <p class="text-xs text-neutral-500">Customer</p>
              <p class="text-sm font-medium text-neutral-900"><?= $penjualan['nama_customer'] ?></p>
            </div>
            
            <div>
              <p class="text-xs text-neutral-500">Unit Kendaraan</p>
              <p class="text-sm font-medium text-neutral-900"><?= $penjualan['nama_mobil'] ?></p>
              <p class="text-xs text-neutral-500 font-mono"><?= $penjualan['no_polisi'] ?></p>
            </div>
            
            <div class="pt-4 border-t border-dashed border-neutral-200">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Pembayaran Lunas
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden p-6">
          <form action="<?= base_url('penyerahan_mobil/proses_serah') ?>" method="POST" class="space-y-6">
            <input type="hidden" name="id_penjualan" value="<?= $penjualan['id_penjualan'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-sm font-medium text-neutral-700">Tanggal Serah Unit</label>
                <input type="date" name="tgl_serah_unit" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
              </div>
              <div class="space-y-2">
                <label class="text-sm font-medium text-neutral-700">Tanggal Serah BPKB</label>
                <input type="date" name="tgl_serah_bpkb" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
              </div>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-medium text-neutral-700">Metode Penyerahan</label>
              <select name="metode_serah" id="metode_serah" required onchange="toggleAlamat()" class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="ambil_sendiri">Ambil Sendiri di Showroom</option>
                <option value="diantar">Diantar ke Lokasi Customer</option>
              </select>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-medium text-neutral-700">Nama Penerima</label>
              <input type="text" name="nama_penerima" value="<?= $penjualan['nama_customer'] ?>" required placeholder="Nama orang yang menerima kunci/mobil..." class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div id="alamat_section" class="hidden space-y-2 p-5 bg-neutral-50 rounded-xl border border-neutral-200 mt-4">
              <label class="text-sm font-medium text-neutral-700">Alamat Tujuan Pengiriman</label>
              <textarea name="alamat_tujuan" rows="3" placeholder="Alamat lengkap pengiriman mobil..." class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
              <p class="text-xs text-neutral-500">Alamat ini akan digunakan untuk mencetak Surat Jalan.</p>
            </div>

            <div class="pt-6 border-t border-neutral-100 flex justify-end gap-3">
              <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Simpan Data Penyerahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</main>

<script>
  function toggleAlamat() {
    const metode = document.getElementById('metode_serah').value;
    const section = document.getElementById('alamat_section');
    if (metode === 'diantar') {
      section.classList.remove('hidden');
      document.querySelector('textarea[name="alamat_tujuan"]').setAttribute('required', 'required');
    } else {
      section.classList.add('hidden');
      document.querySelector('textarea[name="alamat_tujuan"]').removeAttribute('required');
    }
  }
</script>
