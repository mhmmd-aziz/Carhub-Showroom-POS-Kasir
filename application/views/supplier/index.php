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

  <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden flex flex-col" style="height: calc(100vh - 100px);">
    <div class="px-6 py-5 border-b border-neutral-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 flex-shrink-0">
      <div>
        <h2 class="font-semibold text-neutral-900 text-lg">Daftar Supplier</h2>
        <p class="text-xs text-neutral-500 mt-1">Kelola data pemasok / supplier unit kendaraan.</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative">
          <i data-lucide="search" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
          <input type="text" id="searchInput" placeholder="Cari supplier..."
                 class="pl-9 pr-4 py-2 text-sm border border-neutral-200 rounded-lg bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 w-64">
        </div>
        <?php if($this->session->userdata('role') === 'admin'): ?>
        <button id="btn-tambah" class="flex items-center gap-2 px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
          <i data-lucide="plus" class="w-4 h-4"></i>
          Tambah Supplier
        </button>
        <?php endif; ?>
      </div>
    </div>

    <div class="flex-1 overflow-auto">
      <table class="w-full text-left border-collapse" id="dataTable">
        <thead class="sticky top-0 bg-neutral-50 z-10 border-b border-neutral-200">
          <tr>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Nama Supplier</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Kontak</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Alamat</th>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Keterangan</th>
            <?php if($this->session->userdata('role') === 'admin'): ?>
            <th class="text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3 w-24">Aksi</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 bg-white">
          <?php if(empty($suppliers)): ?>
          <tr>
            <td colspan="5" class="px-6 py-12 text-center text-neutral-400 text-sm">
              <div class="flex flex-col items-center gap-3">
                <i data-lucide="building-2" class="w-10 h-10 text-neutral-200"></i>
                <p>Belum ada data supplier. Klik <strong>Tambah Supplier</strong> untuk menambahkan.</p>
              </div>
            </td>
          </tr>
          <?php else: ?>
            <?php foreach($suppliers as $s): ?>
            <tr class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-3">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 font-bold text-sm flex items-center justify-center flex-shrink-0">
                    <?= strtoupper(substr($s['nama_supplier'], 0, 1)) ?>
                  </div>
                  <span class="text-sm font-medium text-neutral-900 data-search"><?= htmlspecialchars($s['nama_supplier']) ?></span>
                </div>
              </td>
              <td class="px-6 py-3">
                <p class="text-sm text-neutral-700 data-search"><?= htmlspecialchars($s['no_telp']) ?></p>
                <p class="text-xs text-neutral-400 data-search"><?= htmlspecialchars($s['email']) ?></p>
              </td>
              <td class="px-6 py-3 text-sm text-neutral-600 max-w-xs truncate data-search"><?= htmlspecialchars($s['alamat']) ?></td>
              <td class="px-6 py-3 text-sm text-neutral-500 max-w-xs truncate data-search"><?= htmlspecialchars($s['keterangan']) ?></td>
              <?php if($this->session->userdata('role') === 'admin'): ?>
              <td class="px-6 py-3">
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button onclick='openEditModal(<?= json_encode($s) ?>)' class="p-1.5 rounded-md hover:bg-amber-50 text-neutral-400 hover:text-amber-600 transition-colors" title="Edit">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                  </button>
                  <button onclick="hapusData('<?= base_url('supplier/delete/'.$s['id_supplier']) ?>', 'Hapus supplier <?= addslashes($s['nama_supplier']) ?>?')" class="p-1.5 rounded-md hover:bg-red-50 text-neutral-400 hover:text-red-600 transition-colors" title="Hapus">
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
  document.getElementById('btn-tambah').addEventListener('click', function() {
    const html = `
      <form action="<?= base_url('supplier/store') ?>" method="POST" class="space-y-4">
        <div class="space-y-1.5">
          <label class="text-sm font-medium text-neutral-700">Nama Supplier <span class="text-red-500">*</span></label>
          <input type="text" name="nama_supplier" required placeholder="Nama perusahaan/perorangan" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">No. Telepon</label>
            <input type="text" name="no_telp" placeholder="08xx..." class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Email</label>
            <input type="email" name="email" placeholder="email@..." class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
        </div>
        <div class="space-y-1.5">
          <label class="text-sm font-medium text-neutral-700">Alamat</label>
          <textarea name="alamat" rows="2" placeholder="Alamat lengkap..." class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
        </div>
        <div class="space-y-1.5">
          <label class="text-sm font-medium text-neutral-700">Keterangan</label>
          <textarea name="keterangan" rows="2" placeholder="Catatan tambahan..." class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
        </div>
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-100">
          <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">Batal</button>
          <button type="submit" class="px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm">Simpan Data</button>
        </div>
      </form>`;
    openModal('Tambah Supplier Baru', html);
  });

  function openEditModal(d) {
    const html = `
      <form action="<?= base_url('supplier/update') ?>" method="POST" class="space-y-4">
        <input type="hidden" name="id_supplier" value="${d.id_supplier}">
        <div class="space-y-1.5">
          <label class="text-sm font-medium text-neutral-700">Nama Supplier <span class="text-red-500">*</span></label>
          <input type="text" name="nama_supplier" required value="${escHtml(d.nama_supplier)}" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">No. Telepon</label>
            <input type="text" name="no_telp" value="${escHtml(d.no_telp || '')}" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700">Email</label>
            <input type="email" name="email" value="${escHtml(d.email || '')}" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
        </div>
        <div class="space-y-1.5">
          <label class="text-sm font-medium text-neutral-700">Alamat</label>
          <textarea name="alamat" rows="2" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">${escHtml(d.alamat || '')}</textarea>
        </div>
        <div class="space-y-1.5">
          <label class="text-sm font-medium text-neutral-700">Keterangan</label>
          <textarea name="keterangan" rows="2" class="w-full px-3.5 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">${escHtml(d.keterangan || '')}</textarea>
        </div>
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-100">
          <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">Batal</button>
          <button type="submit" class="px-4 py-2 text-sm font-medium bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors shadow-sm">Update Data</button>
        </div>
      </form>`;
    openModal('Edit Supplier: ' + d.nama_supplier, html);
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
