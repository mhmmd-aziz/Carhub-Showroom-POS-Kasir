<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">

  <div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
      <a href="<?= base_url('pembelian') ?>" class="p-2 rounded-lg bg-white border border-neutral-200 text-neutral-500 hover:text-neutral-900 hover:bg-neutral-50 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
      </a>
      <div>
        <h2 class="text-xl font-bold text-neutral-900">Proses Pembayaran Pembelian</h2>
        <p class="text-sm text-neutral-500">TRX-B-<?= str_pad($pembelian['id_pembelian'], 4, '0', STR_PAD_LEFT) ?></p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Ringkasan Tagihan -->
      <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-5">
          <h3 class="text-sm font-semibold text-neutral-900 mb-4 flex items-center gap-2">
            <i data-lucide="receipt" class="w-4 h-4 text-primary-500"></i>
            Ringkasan Tagihan
          </h3>
          
          <div class="space-y-4">
            <div>
              <p class="text-xs text-neutral-500">Supplier</p>
              <p class="text-sm font-medium text-neutral-900"><?= $pembelian['nama_supplier'] ?></p>
            </div>
            
            <div>
              <p class="text-xs text-neutral-500">Unit Kendaraan</p>
              <p class="text-sm font-medium text-neutral-900"><?= $pembelian['nama_mobil'] ?></p>
              <p class="text-xs text-neutral-500 font-mono"><?= $pembelian['no_polisi'] ?></p>
            </div>

            <div class="pt-4 border-t border-dashed border-neutral-200">
              <p class="text-xs text-neutral-500 mb-1">Total Tagihan</p>
              <p class="text-2xl font-bold text-primary-600 font-mono">
                Rp <?= number_format($pembelian['harga_beli_beli'], 0, ',', '.') ?>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Pembayaran -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden p-6">
          <form action="<?= base_url('pembayaran_pembelian/proses_bayar') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id_pembelian" value="<?= $pembelian['id_pembelian'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-sm font-medium text-neutral-700">Tanggal Bayar</label>
                <input type="date" name="tgl_bayar" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
              </div>
              
              <div class="space-y-2">
                <label class="text-sm font-medium text-neutral-700">Metode Pembayaran</label>
                <select name="jenis_pembayaran" id="jenis_pembayaran" required onchange="toggleTransferInfo()" class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                  <option value="tunai">Tunai / Cash</option>
                  <option value="transfer">Transfer Bank</option>
                </select>
              </div>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-medium text-neutral-700">Jumlah Dibayar (Rp)</label>
              <input type="text" name="jumlah_bayar" required value="<?= number_format($pembelian['harga_beli_beli'], 0, ',', '.') ?>" onkeyup="formatRupiah(this)" class="w-full px-4 py-2.5 text-lg font-mono border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
              <p class="text-xs text-neutral-500">Nominal otomatis diisi sesuai total tagihan, ubah jika perlu.</p>
            </div>

            <!-- Bagian Transfer Bank (Disembunyikan jika tunai) -->
            <div id="transfer_section" class="hidden space-y-6 p-5 bg-neutral-50 rounded-xl border border-neutral-200 mt-4">
              <div class="space-y-2">
                <label class="text-sm font-medium text-neutral-700">Nama Bank / Tujuan Transfer</label>
                <input type="text" name="metode_pembayaran" placeholder="BCA / Mandiri / BNI a.n Supplier..." class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
              </div>
              
              <div class="space-y-2">
                <label class="text-sm font-medium text-neutral-700">Upload Bukti Transfer</label>
                <input type="file" name="bukti_transfer" accept="image/*,.pdf" class="w-full px-4 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-all cursor-pointer">
                <p class="text-xs text-neutral-500">Format: JPG, PNG, atau PDF (Max 2MB)</p>
              </div>
            </div>

            <div class="pt-6 border-t border-neutral-100 flex justify-end gap-3">
              <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors shadow-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Proses Pembayaran & Selesaikan Transaksi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</main>

<script>
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

  function toggleTransferInfo() {
    const jenis = document.getElementById('jenis_pembayaran').value;
    const section = document.getElementById('transfer_section');
    const inputBukti = document.querySelector('input[name="bukti_transfer"]');
    if (jenis === 'transfer') {
      section.classList.remove('hidden');
      inputBukti.required = true;
    } else {
      section.classList.add('hidden');
      inputBukti.required = false;
    }
  }
</script>
