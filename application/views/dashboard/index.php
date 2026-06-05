<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">
  
  <!-- Stat Cards: 3 kolom, masing-masing clickable -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    <!-- Card 1: Total Customer -->
    <a href="<?= base_url('customer') ?>" class="stat-card group block bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl p-5 text-white shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 cursor-pointer">
      <div class="flex items-start justify-between mb-4">
        <div class="p-2.5 bg-white/20 rounded-xl group-hover:bg-white/30 transition-colors">
          <i data-lucide="users" class="w-5 h-5"></i>
        </div>
        <div class="p-1.5 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
          <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
        </div>
      </div>
      <p class="text-sm text-white/75 mb-1">Total Customer</p>
      <p class="text-4xl font-bold font-mono"><?= $total_customer ?></p>
      <p class="text-xs text-white/60 mt-2 flex items-center gap-1">
        <i data-lucide="external-link" class="w-3 h-3"></i> Klik untuk lihat data customer
      </p>
    </a>

    <!-- Card 2: Total Supplier -->
    <a href="<?= base_url('supplier') ?>" class="stat-card group block bg-gradient-to-br from-emerald-600 to-emerald-500 rounded-2xl p-5 text-white shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 cursor-pointer">
      <div class="flex items-start justify-between mb-4">
        <div class="p-2.5 bg-white/20 rounded-xl group-hover:bg-white/30 transition-colors">
          <i data-lucide="building-2" class="w-5 h-5"></i>
        </div>
        <div class="p-1.5 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
          <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
        </div>
      </div>
      <p class="text-sm text-white/75 mb-1">Total Supplier</p>
      <p class="text-4xl font-bold font-mono"><?= $total_supplier ?></p>
      <p class="text-xs text-white/60 mt-2 flex items-center gap-1">
        <i data-lucide="external-link" class="w-3 h-3"></i> Klik untuk lihat data supplier
      </p>
    </a>

    <!-- Card 3: Total Mobil -->
    <a href="<?= base_url('mobil') ?>" class="stat-card group block bg-gradient-to-br from-violet-600 to-violet-500 rounded-2xl p-5 text-white shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 cursor-pointer">
      <div class="flex items-start justify-between mb-4">
        <div class="p-2.5 bg-white/20 rounded-xl group-hover:bg-white/30 transition-colors">
          <i data-lucide="car-front" class="w-5 h-5"></i>
        </div>
        <div class="p-1.5 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
          <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
        </div>
      </div>
      <p class="text-sm text-white/75 mb-1">Total Mobil</p>
      <p class="text-4xl font-bold font-mono"><?= $total_mobil ?></p>
      <p class="text-xs text-white/60 mt-2 flex items-center gap-1">
        <i data-lucide="external-link" class="w-3 h-3"></i> Klik untuk lihat katalog mobil
      </p>
    </a>

  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Bar Chart (Sales Report) -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 stat-card">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h3 class="text-lg font-semibold text-neutral-900">Sales Report</h3>
          <p class="text-sm text-neutral-500">Rekap penjualan lunas per bulan</p>
        </div>
        <select class="text-sm border border-neutral-200 rounded-lg px-3 py-1.5 bg-neutral-50 text-neutral-600 focus:outline-none focus:ring-2 focus:ring-primary-500">
          <option>Tahun ini</option>
        </select>
      </div>
      <div class="relative h-[300px] w-full">
        <canvas id="salesChart"></canvas>
      </div>
    </div>

    <!-- Doughnut Chart (Product Statistic) -->
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 stat-card flex flex-col">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h3 class="text-lg font-semibold text-neutral-900">Statistik Produk</h3>
          <p class="text-sm text-neutral-500">Distribusi per Merek</p>
        </div>
      </div>
      <div class="relative flex-1 w-full flex items-center justify-center min-h-[250px]">
        <canvas id="brandChart"></canvas>
      </div>
    </div>
  </div>

</main>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Stagger animation for stat cards
    setTimeout(() => {
      if(typeof window.animate === 'function' && typeof window.stagger === 'function') {
        window.animate(
          ".stat-card",
          { opacity: [0, 1], y: [20, 0], scale: [0.97, 1] },
          {
            duration: 0.5,
            delay: window.stagger(0.08),
            easing: [0.34, 1.56, 0.64, 1]
          }
        );
      }
    }, 100);

    const salesData      = <?= $chart_sales ?? '[]' ?>;
    const brandsLabels   = <?= $chart_brands_labels ?? '[]' ?>;
    const brandsData     = <?= $chart_brands_data ?? '[]' ?>;

    // Palet warna bervariasi untuk bar chart (per bulan)
    const barColors = [
      '#6366f1', '#3b82f6', '#0ea5e9', '#06b6d4',
      '#10b981', '#84cc16', '#eab308', '#f97316',
      '#ef4444', '#ec4899', '#a855f7', '#8b5cf6'
    ];

    // Palet warna bervariasi untuk doughnut chart
    const doughnutColors = [
      '#6366f1', '#10b981', '#f97316', '#3b82f6',
      '#ec4899', '#eab308', '#06b6d4', '#a855f7',
      '#ef4444', '#84cc16', '#0ea5e9', '#f43f5e'
    ];

    // ── Bar Chart (Sales Report) ─────────────────────────────
    const ctxSales = document.getElementById('salesChart');
    if (ctxSales) {
      new Chart(ctxSales.getContext('2d'), {
        type: 'bar',
        data: {
          labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
          datasets: [{
            label: 'Penjualan (Rp)',
            data: salesData,
            backgroundColor: barColors,
            borderRadius: 8,
            barPercentage: 0.65
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#171717',
              padding: 12,
              titleFont: { size: 13, family: "'Inter', sans-serif" },
              bodyFont:  { size: 14, family: "'Inter', sans-serif" },
              displayColors: true,
              callbacks: {
                label: function(context) {
                  let val = context.raw;
                  if (val === 0) return 'Belum ada penjualan';
                  return 'Rp ' + val.toLocaleString('id-ID');
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: { color: '#f5f5f5', drawBorder: false },
              ticks: {
                font: { family: "'Inter', sans-serif", size: 11 },
                color: '#a3a3a3',
                callback: function(value) {
                  if(value >= 1000000000) return (value/1000000000).toFixed(1) + 'M';
                  if(value >= 1000000)    return (value/1000000).toFixed(1) + 'Jt';
                  if(value >= 1000)       return (value/1000).toFixed(1) + 'K';
                  return value;
                }
              }
            },
            x: {
              grid: { display: false, drawBorder: false },
              ticks: { font: { family: "'Inter', sans-serif", size: 11 }, color: '#a3a3a3' }
            }
          }
        }
      });
    }

    // ── Doughnut Chart (Statistik Produk) ────────────────────
    const ctxBrands = document.getElementById('brandChart');
    if (ctxBrands) {
      new Chart(ctxBrands.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: brandsLabels,
          datasets: [{
            data: brandsData,
            backgroundColor: doughnutColors.slice(0, brandsData.length),
            borderWidth: 3,
            borderColor: '#ffffff',
            hoverOffset: 8,
            hoverBorderWidth: 3,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '70%',
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                usePointStyle: true,
                pointStyleWidth: 10,
                padding: 16,
                font: { family: "'Inter', sans-serif", size: 12 },
                color: '#525252'
              }
            },
            tooltip: {
              backgroundColor: '#171717',
              padding: 12,
              bodyFont:  { size: 14, family: "'Inter', sans-serif" },
              displayColors: true,
              callbacks: {
                label: function(context) {
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const pct   = ((context.raw / total) * 100).toFixed(1);
                  return ` ${context.raw} unit (${pct}%)`;
                }
              }
            }
          }
        }
      });
    }
  });
</script>
