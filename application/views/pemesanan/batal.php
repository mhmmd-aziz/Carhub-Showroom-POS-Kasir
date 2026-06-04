<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">

  <div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
      <a href="<?= base_url('pemesanan') ?>" class="p-2 rounded-lg bg-white border border-neutral-200 text-neutral-500 hover:text-neutral-900 hover:bg-neutral-50 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
      </a>
      <div>
        <h2 class="text-xl font-bold text-neutral-900">Batalkan Pemesanan</h2>
        <p class="text-sm text-neutral-500">ORD-<?= str_pad($pemesanan['id_pemesanan'], 4, '0', STR_PAD_LEFT) ?></p>
      </div>
    </div>

    <!-- Info Pemesanan -->
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 mb-6">
      <h3 class="text-sm font-semibold text-neutral-900 mb-4 flex items-center gap-2">
        <i data-lucide="file-text" class="w-4 h-4 text-primary-500"></i>
        Detail Pemesanan
      </h3>
      <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
          <p class="text-xs text-neutral-500">Customer</p>
          <p class="font-medium text-neutral-900"><?= $pemesanan['nama_customer'] ?></p>
        </div>
        <div>
          <p class="text-xs text-neutral-500">Unit Kendaraan</p>
          <p class="font-medium text-neutral-900"><?= $pemesanan['nama_mobil'] ?> (<?= $pemesanan['no_polisi'] ?>)</p>
        </div>
        <div>
          <p class="text-xs text-neutral-500">Tanggal Pesan</p>
          <p class="font-medium text-neutral-900"><?= date('d M Y', strtotime($pemesanan['tgl_pesan'])) ?></p>
        </div>
        <div>
          <p class="text-xs text-neutral-500">Jatuh Tempo DP</p>
          <p class="font-medium <?= $dalam_7_hari ? 'text-amber-700' : 'text-rose-700' ?>">
            <?= date('d M Y', strtotime($pemesanan['tgl_jatuh_tempo'])) ?>
            <?php if($dalam_7_hari): ?>
              <span class="text-xs">(Dalam 7 hari)</span>
            <?php else: ?>
              <span class="text-xs">(Sudah Terlewat)</span>
            <?php endif; ?>
          </p>
        </div>
        <?php if($sudah_bayar_tanda_jadi): ?>
        <div>
          <p class="text-xs text-neutral-500">Tanda Jadi</p>
          <p class="font-medium text-neutral-900">Rp 500.000 (sudah dibayar)</p>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Peringatan -->
    <?php if($sudah_bayar_tanda_jadi): ?>
    <div class="p-4 rounded-xl border mb-6 <?= $dalam_7_hari ? 'bg-amber-50 border-amber-200' : 'bg-rose-50 border-rose-200' ?>">
      <div class="flex items-start gap-3">
        <i data-lucide="alert-triangle" class="w-5 h-5 <?= $dalam_7_hari ? 'text-amber-600' : 'text-rose-600' ?> mt-0.5 shrink-0"></i>
        <div>
          <?php if($dalam_7_hari): ?>
            <p class="text-sm font-semibold text-amber-800">Pembatalan dalam masa 7 hari</p>
            <p class="text-xs text-amber-700 mt-1">Tanda jadi sebesar <strong>Rp 500.000</strong> dapat dikembalikan ke customer. Sistem akan mencatat pembatalan dengan alasan yang Anda isi.</p>
          <?php else: ?>
            <p class="text-sm font-semibold text-rose-800">Jatuh tempo sudah terlewat</p>
            <p class="text-xs text-rose-700 mt-1">Tanda jadi sebesar <strong>Rp 500.000 HANGUS</strong> (tidak dikembalikan). Status pemesanan akan diubah menjadi "Hangus".</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php else: ?>
    <div class="p-4 rounded-xl border border-blue-200 bg-blue-50 mb-6">
      <div class="flex items-start gap-3">
        <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5 shrink-0"></i>
        <div>
          <p class="text-sm font-semibold text-blue-800">Tanda jadi belum dibayar</p>
          <p class="text-xs text-blue-700 mt-1">Pembatalan ini tidak mempengaruhi refund karena belum ada pembayaran tanda jadi. Mobil akan dikembalikan ke status Tersedia.</p>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Form Pembatalan -->
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden p-6">
      <form action="<?= base_url('pemesanan/proses_batal') ?>" method="POST" class="space-y-6" onsubmit="return confirm('Yakin ingin membatalkan pemesanan ini? Tindakan ini tidak dapat dibatalkan.')">
        <input type="hidden" name="id_pemesanan" value="<?= $pemesanan['id_pemesanan'] ?>">

        <div class="space-y-2">
          <label class="text-sm font-medium text-neutral-700">Alasan Pembatalan <span class="text-rose-500">*</span></label>
          <textarea name="alasan_batal" rows="3" required placeholder="Tuliskan alasan pembatalan pemesanan ini..." class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-rose-500 resize-none"></textarea>
          <p class="text-xs text-neutral-500">Alasan ini akan dicatat dalam sistem untuk keperluan audit.</p>
        </div>

        <div class="pt-4 border-t border-neutral-100 flex justify-end gap-3">
          <a href="<?= base_url('pemesanan') ?>" class="px-5 py-2.5 text-sm font-medium text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">Kembali</a>
          <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="x-circle" class="w-4 h-4"></i>
            <?= $dalam_7_hari ? 'Batalkan Pesanan (Proses Refund)' : 'Nyatakan Hangus' ?>
          </button>
        </div>
      </form>
    </div>
  </div>

</main>
