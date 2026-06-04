<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">

  <div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
      <a href="<?= base_url('pemesanan') ?>" class="p-2 rounded-lg bg-white border border-neutral-200 text-neutral-500 hover:text-neutral-900 hover:bg-neutral-50 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
      </a>
      <div>
        <h2 class="text-xl font-bold text-neutral-900">Buat Pesanan Baru</h2>
        <p class="text-sm text-neutral-500">Booking mobil untuk customer</p>
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden p-6">
      <form action="<?= base_url('pemesanan/store') ?>" method="POST" class="space-y-6">
        
        <?php if($this->session->flashdata('error')): ?>
          <div class="p-4 bg-rose-50 text-rose-700 rounded-lg text-sm mb-4 border border-rose-100">
            <?= $this->session->flashdata('error') ?>
          </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-2">
            <label class="text-sm font-medium text-neutral-700">Customer</label>
            <select name="id_customer" required class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
              <option value="">-- Pilih Customer --</option>
              <?php foreach($customers as $c): ?>
                <option value="<?= $c['id_customer'] ?>"><?= $c['nama'] ?> - <?= $c['no_telp'] ?></option>
              <?php endforeach; ?>
            </select>
            <p class="text-xs text-neutral-500 mt-1">Jika belum terdaftar, tambahkan dulu di menu Master Customer.</p>
          </div>
        </div>

        <div class="p-4 bg-primary-50 rounded-xl border border-primary-100">
          <div class="space-y-2">
            <label class="text-sm font-medium text-primary-900">Pilih Mobil (Tersedia)</label>
            <select name="id_mobil" id="id_mobil" required onchange="updateInfoMobil(this)" class="w-full px-4 py-2.5 text-sm border border-primary-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
              <option value="">-- Pilih Mobil --</option>
              <?php foreach($mobils as $m): ?>
                <option value="<?= $m['id_mobil'] ?>" data-harga="<?= $m['harga_jual'] ?>">
                  <?= $m['nama_mobil'] ?> (<?= $m['no_polisi'] ?>) - Harga: Rp <?= number_format($m['harga_jual'], 0, ',', '.') ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-4 p-4 rounded-xl border border-neutral-200 bg-neutral-50">
            <div>
              <p class="text-xs font-medium text-neutral-500 mb-1">Tanda Jadi (Bukti Pesanan)</p>
              <p class="text-lg font-bold text-neutral-900 font-mono">Rp 500.000</p>
              <p class="text-xs text-neutral-500 mt-0.5">Nominal tetap dan tidak dapat dikembalikan jika hangus.</p>
            </div>
          </div>
          
          <div class="space-y-4 p-4 rounded-xl border border-neutral-200 bg-neutral-50">
            <div>
              <p class="text-xs font-medium text-neutral-500 mb-1">Estimasi Minimal DP (30%)</p>
              <p class="text-lg font-bold text-neutral-900 font-mono" id="estimasi_dp">Rp 0</p>
              <p class="text-xs text-rose-500 mt-0.5">Jatuh tempo maksimal 7 hari setelah Tanda Jadi.</p>
            </div>
          </div>
        </div>

        <div class="pt-6 border-t border-neutral-100 flex justify-end gap-3">
          <a href="<?= base_url('pemesanan') ?>" class="px-5 py-2.5 text-sm font-medium text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">Batal</a>
          <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            Buat Pesanan & Booking Mobil
          </button>
        </div>
      </form>
    </div>
  </div>

</main>

<script>
  function updateInfoMobil(select) {
    const option = select.options[select.selectedIndex];
    if(option.value !== "") {
      const harga = parseFloat(option.getAttribute('data-harga'));
      const dp = harga * 0.3; // 30%
      document.getElementById('estimasi_dp').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(dp);
    } else {
      document.getElementById('estimasi_dp').innerText = 'Rp 0';
    }
  }
</script>
