<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">

  <div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
      <a href="<?= base_url('pembelian') ?>" class="p-2 rounded-lg bg-white border border-neutral-200 text-neutral-500 hover:text-neutral-900 hover:bg-neutral-50 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
      </a>
      <div>
        <h2 class="text-xl font-bold text-neutral-900">Buat Transaksi Pembelian</h2>
        <p class="text-sm text-neutral-500">Isi detail pembelian mobil dari supplier</p>
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden p-6">
      <form action="<?= base_url('pembelian/store') ?>" method="POST" class="space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-2">
            <label class="text-sm font-medium text-neutral-700">Tanggal Pembelian</label>
            <input type="date" name="tgl_pembelian" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          
          <div class="space-y-2">
            <label class="text-sm font-medium text-neutral-700">Supplier</label>
            <select name="id_supplier" required class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
              <option value="">-- Pilih Supplier --</option>
              <?php foreach($suppliers as $s): ?>
                <option value="<?= $s['id_supplier'] ?>"><?= $s['nama_supplier'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="p-4 bg-primary-50 rounded-xl border border-primary-100">
          <div class="space-y-2">
            <label class="text-sm font-medium text-primary-900">Pilih Mobil yang Dibeli</label>
            <select name="id_mobil" id="id_mobil" required onchange="updateHargaBeli(this)" class="w-full px-4 py-2.5 text-sm border border-primary-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
              <option value="">-- Pilih Mobil di Katalog --</option>
              <?php foreach($mobils as $m): ?>
                <option value="<?= $m['id_mobil'] ?>" data-harga="<?= $m['harga_beli'] ?>">
                  <?= $m['nama_mobil'] ?> (<?= $m['no_polisi'] ?>)
                </option>
              <?php endforeach; ?>
            </select>
            <p class="text-xs text-primary-600 mt-1 flex items-center gap-1">
              <i data-lucide="info" class="w-3.5 h-3.5"></i>
              Jika mobil belum ada di katalog, daftarkan dulu di menu Data Mobil.
            </p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-2">
            <label class="text-sm font-medium text-neutral-700">Harga Beli Aktual (Rp)</label>
            <input type="text" name="harga_beli_beli" id="harga_beli_beli" required onkeyup="formatRupiah(this)" placeholder="0" class="w-full px-4 py-2.5 text-lg font-mono border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          
          <div class="space-y-2">
            <label class="text-sm font-medium text-neutral-700">Keterangan Kondisi</label>
            <textarea name="keterangan_kondisi" rows="2" placeholder="Catatan kondisi saat beli..." class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
          </div>
        </div>

        <div class="pt-6 border-t border-neutral-100 flex justify-end gap-3">
          <a href="<?= base_url('pembelian') ?>" class="px-5 py-2.5 text-sm font-medium text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">Batal</a>
          <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i>
            Simpan Transaksi Pembelian
          </button>
        </div>
      </form>
    </div>
  </div>

</main>

<script>
  function updateHargaBeli(select) {
    const option = select.options[select.selectedIndex];
    if(option.value !== "") {
      const harga = option.getAttribute('data-harga');
      const input = document.getElementById('harga_beli_beli');
      input.value = new Intl.NumberFormat('id-ID').format(harga);
    } else {
      document.getElementById('harga_beli_beli').value = "";
    }
  }

  function formatRupiah(input) {
    let value = input.value.replace(/[^,\d]/g, '').toString();
    let split = value.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      let separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    input.value = rupiah;
  }
</script>
