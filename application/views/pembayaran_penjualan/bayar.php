<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">

  <div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
      <?php $back_link = ($tahap == 'pelunasan') ? base_url('penjualan') : base_url('pemesanan'); ?>
      <a href="<?= $back_link ?>" class="p-2 rounded-lg bg-white border border-neutral-200 text-neutral-500 hover:text-neutral-900 hover:bg-neutral-50 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
      </a>
      <div>
        <h2 class="text-xl font-bold text-neutral-900">Pembayaran Customer</h2>
        <p class="text-sm text-neutral-500">
          <?php if($tahap == 'tanda_jadi') echo 'Tahap 1: Tanda Jadi (Bukti Pesanan) — Rp 500.000'; ?>
          <?php if($tahap == 'dp') echo 'Tahap 2: Pembayaran DP 30%'; ?>
          <?php if($tahap == 'pelunasan') echo 'Tahap 3: Pelunasan Sisa Pembayaran'; ?>
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Ringkasan Tagihan -->
      <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-5">
          <h3 class="text-sm font-semibold text-neutral-900 mb-4 flex items-center gap-2">
            <i data-lucide="receipt" class="w-4 h-4 text-primary-500"></i>
            Data Pesanan
          </h3>
          
          <div class="space-y-4">
            <div>
              <p class="text-xs text-neutral-500">Customer</p>
              <p class="text-sm font-medium text-neutral-900"><?= $pemesanan['nama_customer'] ?></p>
            </div>
            
            <div>
              <p class="text-xs text-neutral-500">Unit Kendaraan</p>
              <p class="text-sm font-medium text-neutral-900"><?= $pemesanan['nama_mobil'] ?></p>
              <p class="text-xs text-neutral-500 font-mono"><?= $pemesanan['no_polisi'] ?></p>
            </div>

            <div class="pt-3 border-t border-dashed border-neutral-200 space-y-2">
              <div class="flex justify-between text-xs">
                <span class="text-neutral-500">Harga Jual</span>
                <span class="font-mono font-medium">Rp <?= number_format($pemesanan['harga_jual_snapshot'], 0, ',', '.') ?></span>
              </div>
              <?php if($tahap !== 'tanda_jadi'): ?>
              <div class="flex justify-between text-xs">
                <span class="text-neutral-500">Tanda Jadi</span>
                <span class="font-mono text-emerald-600">- Rp 500.000</span>
              </div>
              <div class="flex justify-between text-xs">
                <span class="text-neutral-500">DP (30%)</span>
                <span class="font-mono text-emerald-600">- Rp <?= number_format($pemesanan['harga_dp'], 0, ',', '.') ?></span>
              </div>
              <?php endif; ?>
            </div>

            <div class="pt-3 border-t border-dashed border-neutral-200">
              <p class="text-xs text-neutral-500 mb-1">Tagihan Sekarang (<?= ucwords(str_replace('_', ' ', $tahap)) ?>)</p>
              <p class="text-2xl font-bold text-primary-600 font-mono">
                Rp <?= number_format($tagihan, 0, ',', '.') ?>
              </p>
            </div>

            <?php if($tahap == 'dp'): ?>
            <div class="p-3 bg-rose-50 rounded-lg border border-rose-100">
              <p class="text-xs text-rose-700">
                <strong>Jatuh Tempo DP:</strong><br>
                <?= date('d M Y', strtotime($pemesanan['tgl_jatuh_tempo'])) ?>
              </p>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Form Pembayaran -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden p-6">
          <form action="<?= base_url('pembayaran_penjualan/proses_bayar') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id_pemesanan" value="<?= $pemesanan['id_pemesanan'] ?>">
            <input type="hidden" name="tahap" value="<?= $tahap ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-sm font-medium text-neutral-700">Tanggal Bayar</label>
                <input type="date" name="tgl_bayar" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
              </div>
              
              <div class="space-y-2">
                <label class="text-sm font-medium text-neutral-700">Metode Pembayaran</label>
                <!-- FIX BUG-004: field name menggunakan 'metode_pembayaran' (cara bayar) -->
                <select name="metode_pembayaran" id="metode_pembayaran" required onchange="toggleTransferInfo()" class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                  <option value="tunai">Tunai / Cash</option>
                  <option value="transfer">Transfer Bank</option>
                </select>
              </div>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-medium text-neutral-700">Jumlah Dibayar (Rp)</label>
              <input type="text" name="jumlah_bayar" required value="<?= number_format($tagihan, 0, ',', '.') ?>" onkeyup="formatRupiah(this)" class="w-full px-4 py-2.5 text-lg font-mono border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500" <?= ($tahap != 'pelunasan') ? 'readonly' : '' ?>>
              <?php if($tahap != 'pelunasan'): ?>
                <p class="text-xs text-neutral-500">Nominal otomatis diisi sesuai ketentuan tagihan.</p>
              <?php endif; ?>
            </div>

            <div id="transfer_section" class="hidden space-y-6 p-5 bg-neutral-50 rounded-xl border border-neutral-200 mt-4">
              <div class="space-y-2">
                <label class="text-sm font-medium text-neutral-700">Upload Bukti Transfer <span class="text-rose-500">*</span></label>
                <input type="file" name="bukti_transfer" id="bukti_transfer" accept="image/*,.pdf" class="w-full px-4 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-all cursor-pointer">
                <p class="text-xs text-neutral-500">Format: JPG, PNG, atau PDF (Max 5MB)</p>
              </div>
            </div>

            <!-- FIX INKON-004: Upload KTP untuk DP dan Pelunasan -->
            <?php if(in_array($tahap, ['dp', 'pelunasan'])): ?>
            <div class="space-y-6 p-5 bg-amber-50/50 rounded-xl border border-amber-200 mt-4">
              <div class="space-y-2">
                <label class="text-sm font-medium text-neutral-700">Upload Fotokopi KTP Customer <span class="text-rose-500">*</span></label>
                <input type="file" name="bukti_ktp" id="bukti_ktp" accept="image/*,.pdf" required class="w-full px-4 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all cursor-pointer">
                <p class="text-xs text-neutral-500">Wajib untuk pembayaran DP dan Pelunasan baik Tunai maupun Transfer. Format: JPG, PNG, atau PDF (Max 5MB)</p>
              </div>
            </div>
            <?php endif; ?>

            <div class="pt-6 border-t border-neutral-100 flex justify-end gap-3">
              <a href="<?= $back_link ?>" class="px-5 py-2.5 text-sm font-medium text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">Kembali</a>
              <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors shadow-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Proses Pembayaran
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
    const metode = document.getElementById('metode_pembayaran').value;
    const section = document.getElementById('transfer_section');
    const inputBukti = document.getElementById('bukti_transfer');
    
    if (metode === 'transfer') {
      section.classList.remove('hidden');
      if (inputBukti) inputBukti.required = true;
    } else {
      section.classList.add('hidden');
      if (inputBukti) inputBukti.required = false;
    }
  }
</script>
