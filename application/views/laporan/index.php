<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">

  <div class="mb-6">
    <h2 class="text-xl font-bold text-neutral-900">Laporan Showroom</h2>
    <p class="text-sm text-neutral-500">Filter dan cetak laporan transaksi atau stok kendaraan.</p>
  </div>

  <!-- Form Filter -->
  <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 mb-6">
    <form action="<?= base_url('laporan') ?>" method="GET" class="flex flex-col md:flex-row items-end gap-4">
      
      <div class="w-full md:w-1/4 space-y-2">
        <label class="text-sm font-medium text-neutral-700">Jenis Laporan</label>
        <select name="jenis_laporan" id="jenis_laporan" required onchange="togglePeriode()" class="w-full px-4 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
          <option value="">-- Pilih Jenis Laporan --</option>
          <option value="penjualan"  <?= $jenis == 'penjualan'  ? 'selected' : '' ?>>Laporan Penjualan</option>
          <option value="pembelian"  <?= $jenis == 'pembelian'  ? 'selected' : '' ?>>Laporan Pembelian</option>
          <option value="pembayaran" <?= $jenis == 'pembayaran' ? 'selected' : '' ?>>Laporan Pembayaran</option>
          <option value="stok"       <?= $jenis == 'stok'       ? 'selected' : '' ?>>Laporan Stok Mobil</option>
        </select>
      </div>

      <div class="w-full md:w-1/4 space-y-2" id="box_tgl_awal">
        <label class="text-sm font-medium text-neutral-700">Dari Tanggal</label>
        <input type="date" name="tgl_awal" id="tgl_awal" value="<?= htmlspecialchars($tgl_awal ?? '') ?>"
               class="w-full px-4 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
      </div>

      <div class="w-full md:w-1/4 space-y-2" id="box_tgl_akhir">
        <label class="text-sm font-medium text-neutral-700">Sampai Tanggal</label>
        <input type="date" name="tgl_akhir" id="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir ?? '') ?>"
               class="w-full px-4 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
      </div>

      <div class="w-full md:w-1/4 flex gap-2">
        <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm flex items-center justify-center gap-2">
          <i data-lucide="filter" class="w-4 h-4"></i>
          Tampilkan
        </button>
        <?php if($jenis): ?>
        <a href="<?= base_url('laporan') ?>" class="px-3 py-2 text-sm text-neutral-500 hover:text-neutral-800 border border-neutral-200 rounded-lg hover:bg-neutral-50 transition-colors" title="Reset filter">
          <i data-lucide="x" class="w-4 h-4"></i>
        </a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <?php if(isset($error) && $error): ?>
  <div class="p-4 rounded-xl border border-rose-200 bg-rose-50 mb-6 flex items-center gap-3">
    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500 shrink-0"></i>
    <p class="text-sm text-rose-700"><?= $error ?></p>
  </div>
  <?php endif; ?>

  <?php if($jenis && !isset($error)): ?>
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden flex flex-col">
      <!-- Header tabel -->
      <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between gap-4 flex-wrap">
        <div>
          <h3 class="font-semibold text-neutral-900">
            Laporan <?= ucfirst($jenis) ?>
          </h3>
          <?php if($jenis != 'stok' && $tgl_awal && $tgl_akhir): ?>
          <p class="text-xs text-neutral-500 mt-0.5">
            Periode: <?= date('d M Y', strtotime($tgl_awal)) ?> — <?= date('d M Y', strtotime($tgl_akhir)) ?>
            &nbsp;|&nbsp;
            <?php
              $total_rows = is_array($hasil) ? count($hasil) : 0;
              echo $total_rows . ' transaksi ditemukan';
            ?>
          </p>
          <?php endif; ?>
        </div>
        
        <?php if($hasil): ?>
        <a href="<?= base_url('cetak/laporan?jenis_laporan='.$jenis.'&tgl_awal='.($tgl_awal??'').'&tgl_akhir='.($tgl_akhir??'')) ?>"
           target="_blank"
           class="px-4 py-2 text-sm font-medium bg-neutral-800 text-white rounded-lg hover:bg-neutral-900 transition-colors shadow-sm flex items-center gap-2 shrink-0">
          <i data-lucide="printer" class="w-4 h-4"></i>
          Cetak PDF
        </a>
        <?php endif; ?>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-neutral-50 border-b border-neutral-200">
            <tr>
              <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">No</th>
              
              <?php if($jenis == 'penjualan'): ?>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Tgl Jual</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Customer</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Unit Kendaraan</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase text-right">Harga Jual</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase text-right">Total Bayar</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase text-center">Status</th>
              
              <?php elseif($jenis == 'pembelian'): ?>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Tgl Beli</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Supplier</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Unit Kendaraan</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase text-right">Harga Beli</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase text-center">Status Bayar</th>

              <?php elseif($jenis == 'pembayaran'): ?>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Tgl Bayar</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Pihak / Unit</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase text-center">Tipe</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Jenis</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Metode</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase text-right">Jumlah</th>
                  
              <?php elseif($jenis == 'stok'): ?>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Merek & Nama Mobil</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Plat / Rangka / Mesin</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase">Supplier</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase text-right">Harga Jual</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase text-center">Stok</th>
                <th class="px-5 py-3 text-xs font-semibold text-neutral-500 uppercase text-center">Status</th>
              <?php endif; ?>
              
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-100">
            <?php if(empty($hasil)): ?>
            <tr>
              <td colspan="7" class="px-6 py-10 text-center text-neutral-400 text-sm">
                <div class="flex flex-col items-center gap-2">
                  <i data-lucide="inbox" class="w-8 h-8 text-neutral-300"></i>
                  <?php if($jenis == 'stok'): ?>
                    Tidak ada stok mobil yang tersedia.
                  <?php else: ?>
                    Tidak ada data transaksi pada periode
                    <span class="font-medium"><?= date('d M Y', strtotime($tgl_awal)) ?> — <?= date('d M Y', strtotime($tgl_akhir)) ?></span>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
            <?php else: ?>
              <?php $no = 1; $total = 0; foreach($hasil as $row): ?>
              <tr class="hover:bg-neutral-50">
                <td class="px-5 py-3 text-sm text-neutral-500"><?= $no++ ?></td>
                
                <?php if($jenis == 'penjualan'): ?>
                  <td class="px-5 py-3 text-sm text-neutral-900 whitespace-nowrap">
                    <?= date('d/m/Y', strtotime($row['tgl_penjualan'])) ?>
                  </td>
                  <td class="px-5 py-3 text-sm text-neutral-900">
                    <p class="font-medium"><?= htmlspecialchars($row['nama_customer']) ?></p>
                    <p class="text-xs text-neutral-400"><?= htmlspecialchars($row['no_telp'] ?? '-') ?></p>
                  </td>
                  <td class="px-5 py-3 text-sm text-neutral-900">
                    <p><?= htmlspecialchars($row['merek'] . ' ' . $row['nama_mobil']) ?></p>
                    <p class="text-xs font-mono text-neutral-400"><?= htmlspecialchars($row['no_polisi']) ?></p>
                  </td>
                  <td class="px-5 py-3 text-sm text-neutral-900 text-right font-mono">
                    Rp <?= number_format($row['harga_jual_snapshot'], 0, ',', '.') ?>
                  </td>
                  <td class="px-5 py-3 text-sm text-emerald-700 text-right font-mono font-semibold">
                    Rp <?= number_format($row['total_bayaran'], 0, ',', '.') ?>
                  </td>
                  <td class="px-5 py-3 text-sm text-center">
                    <?php if($row['status_pelunasan'] == 'lunas'): ?>
                      <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-md font-medium">Lunas</span>
                    <?php else: ?>
                      <span class="px-2 py-1 bg-rose-100 text-rose-700 text-xs rounded-md font-medium">Belum Lunas</span>
                    <?php endif; ?>
                  </td>
                  <?php $total += $row['total_bayaran']; ?>
                
                <?php elseif($jenis == 'pembelian'): ?>
                  <td class="px-5 py-3 text-sm text-neutral-900 whitespace-nowrap">
                    <?= date('d/m/Y', strtotime($row['tgl_pembelian'])) ?>
                  </td>
                  <td class="px-5 py-3 text-sm text-neutral-900">
                    <?= htmlspecialchars($row['nama_supplier']) ?>
                  </td>
                  <td class="px-5 py-3 text-sm text-neutral-900">
                    <p><?= htmlspecialchars($row['merek'] . ' ' . $row['nama_mobil']) ?></p>
                    <p class="text-xs font-mono text-neutral-400"><?= htmlspecialchars($row['no_polisi']) ?></p>
                  </td>
                  <td class="px-5 py-3 text-sm text-neutral-900 text-right font-mono">
                    Rp <?= number_format($row['harga_beli_beli'], 0, ',', '.') ?>
                  </td>
                  <td class="px-5 py-3 text-sm text-center">
                    <?php if($row['status_pembayaran'] == 'selesai'): ?>
                      <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-md font-medium">Selesai</span>
                    <?php else: ?>
                      <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-md font-medium">Menunggu</span>
                    <?php endif; ?>
                  </td>
                  <?php $total += $row['harga_beli_beli']; ?>

                <?php elseif($jenis == 'pembayaran'): ?>
                  <td class="px-5 py-3 text-sm text-neutral-900 whitespace-nowrap">
                    <?= date('d/m/Y', strtotime($row['tgl_bayar'])) ?>
                  </td>
                  <td class="px-5 py-3 text-sm text-neutral-900">
                    <p class="font-medium"><?= htmlspecialchars($row['nama_pihak']) ?></p>
                    <p class="text-xs text-neutral-400 font-mono"><?= htmlspecialchars($row['nama_mobil']) ?></p>
                  </td>
                  <td class="px-5 py-3 text-sm text-center">
                    <span class="px-2 py-1 rounded-md text-xs font-medium <?= $row['tipe'] === 'Penjualan' ? 'bg-blue-50 text-blue-700' : 'bg-orange-50 text-orange-700' ?>">
                      <?= $row['tipe'] ?>
                    </span>
                  </td>
                  <td class="px-5 py-3 text-sm text-neutral-700">
                    <?= ucwords(str_replace('_', ' ', $row['jenis_pembayaran'])) ?>
                  </td>
                  <td class="px-5 py-3 text-sm text-neutral-700">
                    <?= ucfirst($row['metode_pembayaran']) ?>
                  </td>
                  <td class="px-5 py-3 text-sm text-emerald-700 text-right font-mono font-semibold">
                    Rp <?= number_format($row['jumlah_bayar'], 0, ',', '.') ?>
                  </td>
                  <?php $total += $row['jumlah_bayar']; ?>
                  
                <?php elseif($jenis == 'stok'): ?>
                  <td class="px-5 py-3 text-sm text-neutral-900">
                    <p class="font-semibold"><?= htmlspecialchars($row['merek']) ?></p>
                    <p><?= htmlspecialchars($row['nama_mobil']) ?> <?= htmlspecialchars($row['tipe']) ?> (<?= $row['tahun'] ?>)</p>
                    <p class="text-xs text-neutral-400"><?= htmlspecialchars($row['warna']) ?></p>
                  </td>
                  <td class="px-5 py-3 text-sm text-neutral-900 font-mono text-xs">
                    <?= htmlspecialchars($row['no_polisi']) ?><br>
                    <?= htmlspecialchars($row['no_rangka']) ?><br>
                    <?= htmlspecialchars($row['no_mesin']) ?>
                  </td>
                  <td class="px-5 py-3 text-sm text-neutral-900"><?= htmlspecialchars($row['nama_supplier']) ?></td>
                  <td class="px-5 py-3 text-sm text-neutral-900 text-right font-mono">Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
                  <td class="px-5 py-3 text-sm text-neutral-900 text-center font-bold"><?= $row['stok'] ?></td>
                  <td class="px-5 py-3 text-sm text-center">
                    <?php if($row['status_stok'] == 'tersedia'): ?>
                      <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-md font-medium">Tersedia</span>
                    <?php elseif($row['status_stok'] == 'booking'): ?>
                      <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-md font-medium">Booking</span>
                    <?php else: ?>
                      <span class="px-2 py-1 bg-neutral-100 text-neutral-600 text-xs rounded-md font-medium">Terjual</span>
                    <?php endif; ?>
                  </td>
                  <?php $total += $row['stok']; ?>
                <?php endif; ?>
                
              </tr>
              <?php endforeach; ?>
              
              <!-- Row Total -->
              <?php if($jenis == 'penjualan'): ?>
              <tr class="bg-primary-50 font-bold border-t-2 border-primary-200">
                <td colspan="5" class="px-5 py-3 text-right text-sm text-neutral-700">TOTAL PENJUALAN (<?= count($hasil) ?> transaksi)</td>
                <td class="px-5 py-3 text-right text-sm text-primary-700 font-mono">Rp <?= number_format($total, 0, ',', '.') ?></td>
                <td></td>
              </tr>
              <?php elseif($jenis == 'pembelian'): ?>
              <tr class="bg-primary-50 font-bold border-t-2 border-primary-200">
                <td colspan="4" class="px-5 py-3 text-right text-sm text-neutral-700">TOTAL PEMBELIAN (<?= count($hasil) ?> transaksi)</td>
                <td class="px-5 py-3 text-right text-sm text-primary-700 font-mono">Rp <?= number_format($total, 0, ',', '.') ?></td>
                <td></td>
              </tr>
              <?php elseif($jenis == 'pembayaran'): ?>
              <tr class="bg-primary-50 font-bold border-t-2 border-primary-200">
                <td colspan="5" class="px-5 py-3 text-right text-sm text-neutral-700">TOTAL PEMBAYARAN (<?= count($hasil) ?> transaksi)</td>
                <td class="px-5 py-3 text-right text-sm text-primary-700 font-mono">Rp <?= number_format($total, 0, ',', '.') ?></td>
              </tr>
              <?php elseif($jenis == 'stok'): ?>
              <tr class="bg-primary-50 font-bold border-t-2 border-primary-200">
                <td colspan="4" class="px-5 py-3 text-right text-sm text-neutral-700">TOTAL UNIT</td>
                <td class="px-5 py-3 text-center text-sm text-primary-700"><?= $total ?> Unit</td>
                <td colspan="2"></td>
              </tr>
              <?php endif; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

</main>

<script>
  function togglePeriode() {
    const jenis = document.getElementById('jenis_laporan').value;
    const boxAwal  = document.getElementById('box_tgl_awal');
    const boxAkhir = document.getElementById('box_tgl_akhir');
    const tglAwal  = document.getElementById('tgl_awal');
    const tglAkhir = document.getElementById('tgl_akhir');
    
    if (jenis === 'stok' || jenis === '') {
      boxAwal.classList.add('opacity-40', 'pointer-events-none');
      boxAkhir.classList.add('opacity-40', 'pointer-events-none');
      tglAwal.removeAttribute('required');
      tglAkhir.removeAttribute('required');
    } else {
      boxAwal.classList.remove('opacity-40', 'pointer-events-none');
      boxAkhir.classList.remove('opacity-40', 'pointer-events-none');
      tglAwal.setAttribute('required', 'required');
      tglAkhir.setAttribute('required', 'required');
    }
  }

  // Validasi: tgl_akhir tidak boleh sebelum tgl_awal
  document.getElementById('tgl_akhir').addEventListener('change', function() {
    const awal  = document.getElementById('tgl_awal').value;
    const akhir = this.value;
    if (awal && akhir && akhir < awal) {
      this.setCustomValidity('Tanggal akhir tidak boleh sebelum tanggal awal.');
      this.reportValidity();
    } else {
      this.setCustomValidity('');
    }
  });

  document.addEventListener('DOMContentLoaded', () => {
    togglePeriode();
  });
</script>
