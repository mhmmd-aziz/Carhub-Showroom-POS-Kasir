<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<main class="flex-1 overflow-y-auto p-6 animate-in bg-neutral-50">
  
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Stat card 1 -->
    <div class="stat-card bg-primary-600 rounded-2xl p-5 text-white shadow-sm">
      <div class="flex items-start justify-between mb-4">
        <div class="p-2 bg-white/20 rounded-lg">
          <i data-lucide="shopping-cart" class="w-4 h-4"></i>
        </div>
      </div>
      <p class="text-sm text-white/70 mb-1">Total Pemesanan</p>
      <p class="text-3xl font-bold font-mono"><?= $total_pemesanan ?></p>
      <p class="text-xs text-white/60 mt-1">Bulan ini</p>
    </div>

    <!-- Stat card 2 -->
    <div class="stat-card bg-white rounded-2xl p-5 border border-neutral-200 shadow-sm">
      <div class="flex items-start justify-between mb-4">
        <div class="p-2 bg-neutral-100 rounded-lg">
          <i data-lucide="car-front" class="w-4 h-4 text-neutral-600"></i>
        </div>
      </div>
      <p class="text-sm text-neutral-500 mb-1">Stok Tersedia</p>
      <p class="text-3xl font-bold text-neutral-900 font-mono"><?= $stok_tersedia ?></p>
      <p class="text-xs text-neutral-400 mt-1">Unit mobil</p>
    </div>

    <!-- Stat card 3 -->
    <div class="stat-card bg-white rounded-2xl p-5 border border-neutral-200 shadow-sm">
      <div class="flex items-start justify-between mb-4">
        <div class="p-2 bg-neutral-100 rounded-lg">
          <i data-lucide="users" class="w-4 h-4 text-neutral-600"></i>
        </div>
      </div>
      <p class="text-sm text-neutral-500 mb-1">Total Customer</p>
      <p class="text-3xl font-bold text-neutral-900 font-mono"><?= $total_customer ?></p>
      <p class="text-xs text-neutral-400 mt-1">Terdaftar</p>
    </div>

    <!-- Stat card 4 -->
    <div class="stat-card bg-white rounded-2xl p-5 border border-neutral-200 shadow-sm">
      <div class="flex items-start justify-between mb-4">
        <div class="p-2 bg-neutral-100 rounded-lg">
          <i data-lucide="trending-up" class="w-4 h-4 text-neutral-600"></i>
        </div>
      </div>
      <p class="text-sm text-neutral-500 mb-1">Total Penjualan</p>
      <p class="text-2xl font-bold text-neutral-900 font-mono">Rp <?= number_format($total_penjualan, 0, ',', '.') ?></p>
      <p class="text-xs text-neutral-400 mt-1">Bulan ini</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Bar Chart (Sales Report) -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 stat-card">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h3 class="text-lg font-semibold text-neutral-900">Sales Report</h3>
          <p class="text-sm text-neutral-500">Track your monthly sales performance</p>
        </div>
        <select class="text-sm border border-neutral-200 rounded-lg px-3 py-1.5 bg-neutral-50 text-neutral-600 focus:outline-none focus:ring-2 focus:ring-primary-500">
          <option>This year</option>
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
          <h3 class="text-lg font-semibold text-neutral-900">Product Statistic</h3>
          <p class="text-sm text-neutral-500">Sales by Brand</p>
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
    // Initialize Charts
    const salesData = <?= $chart_sales ?? '[]' ?>;
    const brandsLabels = <?= $chart_brands_labels ?? '[]' ?>;
    const brandsData = <?= $chart_brands_data ?? '[]' ?>;

    // Bar Chart (Sales Report)
    const ctxSales = document.getElementById('salesChart');
    if (ctxSales) {
      new Chart(ctxSales.getContext('2d'), {
        type: 'bar',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          datasets: [{
            label: 'Sales (Rp)',
            data: salesData,
            backgroundColor: '#4f46e5',
            borderRadius: 8,
            barPercentage: 0.6
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
              bodyFont: { size: 14, family: "'Inter', sans-serif" },
              displayColors: false,
              callbacks: {
                label: function(context) {
                  let val = context.raw;
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
                  if(value >= 1000000) return (value/1000000).toFixed(1) + 'M';
                  if(value >= 1000) return (value/1000).toFixed(1) + 'K';
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

    // Doughnut Chart (Product Statistic)
    const ctxBrands = document.getElementById('brandChart');
    if (ctxBrands) {
      new Chart(ctxBrands.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: brandsLabels,
          datasets: [{
            data: brandsData,
            backgroundColor: ['#4f46e5', '#3b82f6', '#0ea5e9', '#6366f1', '#a855f7', '#ec4899', '#f43f5e'],
            borderWidth: 0,
            hoverOffset: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '75%',
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                usePointStyle: true,
                padding: 20,
                font: { family: "'Inter', sans-serif", size: 12 },
                color: '#525252'
              }
            },
            tooltip: {
              backgroundColor: '#171717',
              padding: 12,
              bodyFont: { size: 14, family: "'Inter', sans-serif" },
              displayColors: true
            }
          }
        }
      });
    }
  });
</script>
