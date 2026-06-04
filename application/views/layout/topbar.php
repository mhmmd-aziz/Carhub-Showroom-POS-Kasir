  <!-- Main -->
  <div class="flex-1 flex flex-col overflow-hidden relative">
    <!-- Topbar -->
    <header class="h-14 bg-white border-b border-neutral-200 flex items-center justify-between px-6 z-10">
      <div>
        <h1 class="text-lg font-semibold text-neutral-900"><?= isset($title) ? $title : 'Dashboard' ?></h1>
        <p class="text-xs text-neutral-400" id="current-date"></p>
      </div>

      <div class="flex items-center gap-3">
        <div class="flex items-center gap-2.5 pl-3 border-l border-neutral-200">
          <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold text-sm">
            <?= strtoupper(substr($this->session->userdata('nama_lengkap') ?: 'A', 0, 1)) ?>
          </div>
          <div>
            <p class="text-sm font-medium text-neutral-900 leading-none"><?= $this->session->userdata('nama_lengkap') ?: 'Admin' ?></p>
            <p class="text-xs text-neutral-400 mt-0.5"><?= ucfirst($this->session->userdata('role') ?: 'Administrator') ?></p>
          </div>
        </div>
      </div>
    </header>

    <script>
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      document.getElementById('current-date').textContent = new Date().toLocaleDateString('id-ID', options);
    </script>
