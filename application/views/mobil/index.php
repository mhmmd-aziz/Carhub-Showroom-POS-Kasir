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

  <!-- Simpan data suppliers untuk JS -->
  <script>
    const suppliersData = <?= json_encode($suppliers) ?>;
  </script>

  <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden flex flex-col" style="height: calc(100vh - 100px);">
    <div class="px-6 py-5 border-b border-neutral-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 flex-shrink-0">
      <div>
        <h2 class="font-semibold text-neutral-900 text-lg">Katalog Mobil</h2>
        <p class="text-xs text-neutral-500 mt-1">Kelola data unit kendaraan yang tersedia di showroom.</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative">
          <i data-lucide="search" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
          <input type="text" id="searchInput" placeholder="Cari mobil..."
                 class="pl-9 pr-4 py-2 text-sm border border-neutral-200 rounded-lg bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 w-64">
        </div>
        <?php if($this->session->userdata('role') === 'admin'): ?>
        <button id="btn-tambah" class="flex items-center gap-2 px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
          <i data-lucide="plus" class="w-4 h-4"></i>
          Tambah Mobil
        </button>
        <?php endif; ?>
      </div>
    </div>

    <div class="flex-1 overflow-auto">
      <table class="w-full text-left border-collapse" id="dataTable">
        <thead class="sticky top-0 bg-neutral-50 z-10 border-b border-neutral-200">
          <tr>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Unit Kendaraan</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Nomor</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Harga</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3 text-center">Stok</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Status</th>
            <?php if($this->session->userdata('role') === 'admin'): ?>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3 w-24">Aksi</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($mobils)): ?>
          <tr>
            <td colspan="6" class="px-6 py-12 text-center text-neutral-400 text-sm">
              <div class="flex flex-col items-center gap-3">
                <i data-lucide="car" class="w-10 h-10 text-neutral-200"></i>
                <p>Belum ada data mobil. Klik <strong>Tambah Mobil</strong> untuk menambahkan.</p>
              </div>
            </td>
          </tr>
          <?php else: ?>
            <?php foreach($mobils as $m): ?>
            <tr class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-3">
                <p class="text-sm font-semibold text-neutral-900 data-search"><?= htmlspecialchars($m['merek']) ?> <?= htmlspecialchars($m['nama_mobil']) ?></p>
                <p class="text-xs text-neutral-500 data-search"><?= htmlspecialchars($m['tipe']) ?> · <?= htmlspecialchars($m['warna']) ?> · <?= $m['tahun'] ?></p>
              </td>
              <td class="px-6 py-3">
                <p class="text-sm font-mono text-neutral-700 data-search"><?= htmlspecialchars($m['no_polisi']) ?></p>
                <p class="text-xs text-neutral-400 font-mono data-search"><?= htmlspecialchars($m['no_rangka']) ?></p>
              </td>
              <td class="px-6 py-3">
                <p class="text-sm font-mono text-emerald-600 font-medium data-search">Rp <?= number_format($m['harga_jual'], 0, ',', '.') ?></p>
                <p class="text-xs text-neutral-400 font-mono data-search">Beli: Rp <?= number_format($m['harga_beli'], 0, ',', '.') ?></p>
              </td>
              <td class="px-6 py-3 text-center">
                <span class="text-sm font-bold text-neutral-900 data-search"><?= $m['stok'] ?></span>
                <span class="text-xs text-neutral-400"> unit</span>
              </td>
              <td class="px-6 py-3">
                <?php if($m['status_stok'] == 'tersedia'): ?>
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 data-search">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Tersedia
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 data-search">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Booking
                  </span>
                <?php endif; ?>
              </td>
              <?php if($this->session->userdata('role') === 'admin'): ?>
              <td class="px-6 py-3">
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button onclick='openEditModal(<?= json_encode($m) ?>)' class="p-1.5 rounded-md hover:bg-amber-50 text-neutral-400 hover:text-amber-600 transition-colors" title="Edit">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                  </button>
                  <button onclick="hapusData('<?= base_url('mobil/delete/'.$m['id_mobil']) ?>', 'Hapus mobil <?= addslashes($m['nama_mobil']) ?>?')" class="p-1.5 rounded-md hover:bg-red-50 text-neutral-400 hover:text-red-600 transition-colors" title="Hapus">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              </td>
              <?php endif; ?>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</main>

<script>
  function buildSupplierOptions(selectedId = '') {
    return suppliersData.map(s =>
      `<option value="${s.id_supplier}" ${s.id_supplier == selectedId ? 'selected' : ''}>${escHtml(s.nama_supplier)}</option>`
    ).join('');
  }

  document.getElementById('btn-tambah').addEventListener('click', function() {
    const html = `
      <form action="<?= base_url('mobil/store') ?>" method="POST" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2 space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Supplier <span class="text-red-500">*</span></label>
            <select name="id_supplier" required class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
              <option value="">-- Pilih Supplier --</option>
              ${buildSupplierOptions()}
            </select>
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Nama Mobil <span class="text-red-500">*</span></label>
            <input type="text" name="nama_mobil" required placeholder="Avanza, Civic, dll" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Merek <span class="text-red-500">*</span></label>
            <input type="text" name="merek" required placeholder="Toyota, Honda, dll" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Tipe</label>
            <input type="text" name="tipe" placeholder="G, E, V, dll" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Warna</label>
            <input type="text" name="warna" placeholder="Putih, Hitam, dll" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Tahun</label>
            <input type="number" name="tahun" min="1990" max="2030" placeholder="2023" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">No. Polisi <span class="text-red-500">*</span></label>
            <input type="text" name="no_polisi" required placeholder="B 1234 XYZ" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 font-mono uppercase">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">No. Rangka</label>
            <input type="text" name="no_rangka" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 font-mono">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">No. Mesin</label>
            <input type="text" name="no_mesin" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 font-mono">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Harga Beli (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="harga_beli" required min="0" placeholder="0" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 font-mono">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Harga Jual (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="harga_jual" required min="0" placeholder="0" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 font-mono">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Stok Awal</label>
            <input type="number" name="stok" min="0" value="1" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Status BPKB</label>
            <select name="status_bpkb" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
              <option value="ada">Ada</option>
              <option value="belum_ada">Belum Ada</option>
              <option value="proses">Proses</option>
            </select>
          </div>
        </div>
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-100">
          <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">Batal</button>
          <button type="submit" class="px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm">Simpan Mobil</button>
        </div>
      </form>`;
    openModal('Tambah Mobil Baru', html);
  });

  function openEditModal(d) {
    const html = `
      <form action="<?= base_url('mobil/update') ?>" method="POST" class="space-y-4">
        <input type="hidden" name="id_mobil" value="${d.id_mobil}">
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2 space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Supplier <span class="text-red-500">*</span></label>
            <select name="id_supplier" required class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
              <option value="">-- Pilih Supplier --</option>
              ${buildSupplierOptions(d.id_supplier)}
            </select>
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Nama Mobil <span class="text-red-500">*</span></label>
            <input type="text" name="nama_mobil" required value="${escHtml(d.nama_mobil)}" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Merek <span class="text-red-500">*</span></label>
            <input type="text" name="merek" required value="${escHtml(d.merek)}" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Tipe</label>
            <input type="text" name="tipe" value="${escHtml(d.tipe || '')}" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Warna</label>
            <input type="text" name="warna" value="${escHtml(d.warna || '')}" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Tahun</label>
            <input type="number" name="tahun" value="${d.tahun || ''}" min="1990" max="2030" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">No. Polisi <span class="text-red-500">*</span></label>
            <input type="text" name="no_polisi" required value="${escHtml(d.no_polisi)}" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 font-mono uppercase">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">No. Rangka</label>
            <input type="text" name="no_rangka" value="${escHtml(d.no_rangka || '')}" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 font-mono">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">No. Mesin</label>
            <input type="text" name="no_mesin" value="${escHtml(d.no_mesin || '')}" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 font-mono">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Harga Beli (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="harga_beli" required value="${d.harga_beli}" min="0" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 font-mono">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Harga Jual (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="harga_jual" required value="${d.harga_jual}" min="0" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 font-mono">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Stok</label>
            <input type="number" name="stok" value="${d.stok}" min="0" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Status BPKB</label>
            <select name="status_bpkb" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
              <option value="ada" ${d.status_bpkb=='ada'?'selected':''}>Ada</option>
              <option value="belum_ada" ${d.status_bpkb=='belum_ada'?'selected':''}>Belum Ada</option>
              <option value="proses" ${d.status_bpkb=='proses'?'selected':''}>Proses</option>
            </select>
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Status Kondisi Mobil</label>
            <select name="status_mobil" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
              <option value="baik" ${d.status_mobil=='baik'?'selected':''}>Baik</option>
              <option value="perlu_servis" ${d.status_mobil=='perlu_servis'?'selected':''}>Perlu Servis</option>
            </select>
          </div>
        </div>
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-100">
          <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">Batal</button>
          <button type="submit" class="px-4 py-2 text-sm font-medium bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors shadow-sm">Update Data Mobil</button>
        </div>
      </form>`;
    openModal('Edit Mobil: ' + d.merek + ' ' + d.nama_mobil, html);
  }

  function hapusData(url, pesan) {
    if(confirm(pesan + '\n\nData tidak akan hilang permanen.')) {
      window.location.href = url;
    }
  }

  document.getElementById('searchInput').addEventListener('keyup', function() {
    const f = this.value.toLowerCase();
    document.querySelectorAll('#dataTable tbody tr').forEach(row => {
      if(row.cells.length === 1) return;
      let text = '';
      row.querySelectorAll('.data-search').forEach(el => text += el.textContent.toLowerCase() + ' ');
      row.style.display = text.includes(f) ? '' : 'none';
    });
  });

  function escHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }
</script>
