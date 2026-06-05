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

        <!-- Tanggal Pembelian -->
        <div class="space-y-2">
          <label class="text-sm font-medium text-neutral-700">Tanggal Pembelian</label>
          <input type="date" name="tgl_pembelian" value="<?= date('Y-m-d') ?>" required
                 class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- BAGIAN SUPPLIER                                        -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div class="rounded-xl border border-neutral-200 overflow-hidden">
          <div class="px-5 py-4 bg-neutral-50 border-b border-neutral-200 flex items-center justify-between">
            <p class="text-sm font-semibold text-neutral-700 flex items-center gap-2">
              <i data-lucide="building-2" class="w-4 h-4 text-emerald-500"></i>
              Data Supplier
            </p>
            <!-- Toggle Mode Supplier -->
            <div class="flex items-center gap-1 bg-neutral-200 p-1 rounded-lg text-xs font-medium">
              <button type="button" id="btn-supplier-pilih" onclick="setModeSupplier('pilih')"
                class="mode-btn px-3 py-1 rounded-md transition-colors active-mode">
                Pilih Lama
              </button>
              <button type="button" id="btn-supplier-baru" onclick="setModeSupplier('baru')"
                class="mode-btn px-3 py-1 rounded-md transition-colors">
                + Input Baru
              </button>
            </div>
          </div>

          <div class="p-5">
            <input type="hidden" name="mode_supplier" id="mode_supplier" value="pilih">

            <!-- Mode: Pilih Supplier Lama -->
            <div id="blok-supplier-pilih" class="space-y-2">
              <label class="text-sm font-medium text-neutral-700">Pilih Supplier</label>
              <select name="id_supplier" id="id_supplier"
                      class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">-- Pilih Supplier --</option>
                <?php foreach($suppliers as $s): ?>
                  <option value="<?= $s['id_supplier'] ?>"><?= $s['nama_supplier'] ?> — <?= $s['no_telp'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Mode: Input Supplier Baru -->
            <div id="blok-supplier-baru" class="hidden space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">Nama Supplier <span class="text-red-500">*</span></label>
                  <input type="text" name="nama_supplier_baru" placeholder="PT. Nama Supplier"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">No. Telepon <span class="text-red-500">*</span></label>
                  <input type="text" name="no_telp_supplier_baru" placeholder="021-xxxxxxx"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">Email</label>
                  <input type="email" name="email_supplier_baru" placeholder="email@supplier.com"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">Keterangan</label>
                  <input type="text" name="keterangan_supplier_baru" placeholder="Opsional"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
              </div>
              <div class="space-y-2">
                <label class="text-sm font-medium text-neutral-700">Alamat</label>
                <textarea name="alamat_supplier_baru" rows="2" placeholder="Alamat lengkap supplier..."
                          class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
              </div>
              <p class="text-xs text-emerald-600 flex items-center gap-1.5">
                <i data-lucide="info" class="w-3.5 h-3.5"></i>
                Data supplier baru otomatis tersimpan ke Master Data Supplier.
              </p>
            </div>
          </div>
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- BAGIAN MOBIL                                           -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div class="rounded-xl border border-neutral-200 overflow-hidden">
          <div class="px-5 py-4 bg-neutral-50 border-b border-neutral-200 flex items-center justify-between">
            <p class="text-sm font-semibold text-neutral-700 flex items-center gap-2">
              <i data-lucide="car-front" class="w-4 h-4 text-violet-500"></i>
              Data Mobil yang Dibeli
            </p>
            <!-- Toggle Mode Mobil -->
            <div class="flex items-center gap-1 bg-neutral-200 p-1 rounded-lg text-xs font-medium">
              <button type="button" id="btn-mobil-pilih" onclick="setModeMobil('pilih')"
                class="mode-btn px-3 py-1 rounded-md transition-colors active-mode">
                Pilih Lama
              </button>
              <button type="button" id="btn-mobil-baru" onclick="setModeMobil('baru')"
                class="mode-btn px-3 py-1 rounded-md transition-colors">
                + Input Baru
              </button>
            </div>
          </div>

          <div class="p-5">
            <input type="hidden" name="mode_mobil" id="mode_mobil" value="pilih">

            <!-- Mode: Pilih Mobil Lama -->
            <div id="blok-mobil-pilih" class="space-y-2">
              <label class="text-sm font-medium text-neutral-700">Pilih Mobil dari Katalog</label>
              <select name="id_mobil" id="id_mobil" onchange="updateHargaBeli(this)"
                      class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">-- Pilih Mobil --</option>
                <?php foreach($mobils as $m): ?>
                  <option value="<?= $m['id_mobil'] ?>" data-harga="<?= $m['harga_beli'] ?>">
                    <?= $m['nama_mobil'] ?> — <?= $m['merek'] ?> (<?= $m['no_polisi'] ?: 'Tanpa Plat' ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Mode: Input Mobil Baru -->
            <div id="blok-mobil-baru" class="hidden space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">Nama Mobil <span class="text-red-500">*</span></label>
                  <input type="text" name="nama_mobil_baru" placeholder="Toyota Avanza"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">Merek <span class="text-red-500">*</span></label>
                  <input type="text" name="merek_baru" placeholder="Toyota"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">Warna</label>
                  <input type="text" name="warna_baru" placeholder="Putih"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">Tipe</label>
                  <input type="text" name="tipe_baru" placeholder="G MT"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">Tahun</label>
                  <input type="number" name="tahun_baru" placeholder="2024" min="1990" max="2030"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">No. Polisi</label>
                  <input type="text" name="no_polisi_baru" placeholder="B 1234 ABC"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">No. Rangka</label>
                  <input type="text" name="no_rangka_baru" placeholder="MHFM5FB1XN0xxxxxx"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-neutral-700">No. Mesin</label>
                  <input type="text" name="no_mesin_baru" placeholder="1NR-Bxxxxxx"
                         class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="space-y-2 md:col-span-2">
                  <label class="text-sm font-medium text-neutral-700">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                  <input type="text" name="harga_jual_baru" id="harga_jual_baru" onkeyup="formatRupiah(this)"
                         placeholder="0"
                         class="w-full px-4 py-2.5 text-base font-mono border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                  <p class="text-xs text-neutral-400">Harga jual yang ditawarkan ke customer</p>
                </div>
              </div>
              <p class="text-xs text-violet-600 flex items-center gap-1.5">
                <i data-lucide="info" class="w-3.5 h-3.5"></i>
                Mobil baru otomatis tersimpan ke Master Data Mobil. Stok terbentuk setelah pembayaran pembelian selesai.
              </p>
            </div>
          </div>
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- HARGA BELI & KETERANGAN                                -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-2">
            <label class="text-sm font-medium text-neutral-700">Harga Beli Aktual (Rp) <span class="text-red-500">*</span></label>
            <input type="text" name="harga_beli_beli" id="harga_beli_beli" required onkeyup="formatRupiah(this)"
                   placeholder="0"
                   class="w-full px-4 py-2.5 text-lg font-mono border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
            <p class="text-xs text-neutral-400">Harga yang benar-benar dibayar ke supplier</p>
          </div>
          <div class="space-y-2">
            <label class="text-sm font-medium text-neutral-700">Keterangan Kondisi</label>
            <textarea name="keterangan_kondisi" rows="3" placeholder="Catatan kondisi saat beli..."
                      class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
          </div>
        </div>

        <!-- Info alur -->
        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-start gap-3">
          <i data-lucide="info" class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0"></i>
          <p class="text-sm text-blue-700">
            Setelah pembelian tersimpan, Anda akan diarahkan ke <strong>Pembayaran Pembelian</strong> untuk menyelesaikan pembayaran ke supplier.
          </p>
        </div>

        <!-- Tombol -->
        <div class="pt-4 border-t border-neutral-100 flex justify-end gap-3">
          <a href="<?= base_url('pembelian') ?>"
             class="px-5 py-2.5 text-sm font-medium text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">Batal</a>
          <button type="submit"
                  class="px-5 py-2.5 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i>
            Simpan & Lanjut ke Pembayaran
          </button>
        </div>
      </form>
    </div>
  </div>

</main>

<style>
  .active-mode { background: white; color: #1d4ed8; box-shadow: 0 1px 3px rgba(0,0,0,.12); }
  .mode-btn:not(.active-mode) { color: #6b7280; }
  .mode-btn:not(.active-mode):hover { background: rgba(255,255,255,0.5); }
</style>

<script>
  // ── Toggle mode supplier ──────────────────────────────────
  function setModeSupplier(mode) {
    document.getElementById('mode_supplier').value = mode;

    const blokPilih = document.getElementById('blok-supplier-pilih');
    const blokBaru  = document.getElementById('blok-supplier-baru');
    const btnPilih  = document.getElementById('btn-supplier-pilih');
    const btnBaru   = document.getElementById('btn-supplier-baru');

    if (mode === 'baru') {
      blokPilih.classList.add('hidden');
      blokBaru.classList.remove('hidden');
      btnBaru.classList.add('active-mode');
      btnPilih.classList.remove('active-mode');
      // Hapus required dari select lama
      document.getElementById('id_supplier').removeAttribute('required');
    } else {
      blokBaru.classList.add('hidden');
      blokPilih.classList.remove('hidden');
      btnPilih.classList.add('active-mode');
      btnBaru.classList.remove('active-mode');
      document.getElementById('id_supplier').setAttribute('required', 'required');
    }
  }

  // ── Toggle mode mobil ─────────────────────────────────────
  function setModeMobil(mode) {
    document.getElementById('mode_mobil').value = mode;

    const blokPilih = document.getElementById('blok-mobil-pilih');
    const blokBaru  = document.getElementById('blok-mobil-baru');
    const btnPilih  = document.getElementById('btn-mobil-pilih');
    const btnBaru   = document.getElementById('btn-mobil-baru');

    if (mode === 'baru') {
      blokPilih.classList.add('hidden');
      blokBaru.classList.remove('hidden');
      btnBaru.classList.add('active-mode');
      btnPilih.classList.remove('active-mode');
      document.getElementById('id_mobil').removeAttribute('required');
    } else {
      blokBaru.classList.add('hidden');
      blokPilih.classList.remove('hidden');
      btnPilih.classList.add('active-mode');
      btnBaru.classList.remove('active-mode');
      document.getElementById('id_mobil').setAttribute('required', 'required');
    }
  }

  // ── Auto-isi harga beli dari dropdown mobil lama ──────────
  function updateHargaBeli(select) {
    const option = select.options[select.selectedIndex];
    if (option.value !== '') {
      const harga = option.getAttribute('data-harga');
      const input = document.getElementById('harga_beli_beli');
      input.value = new Intl.NumberFormat('id-ID').format(harga);
    } else {
      document.getElementById('harga_beli_beli').value = '';
    }
  }

  // ── Format angka menjadi format Rupiah ────────────────────
  function formatRupiah(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value === '') { input.value = ''; return; }
    input.value = new Intl.NumberFormat('id-ID').format(parseInt(value, 10));
  }
</script>
