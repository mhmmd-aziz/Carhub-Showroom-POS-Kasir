<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - CarHub</title>
  <link rel="icon" type="image/png" href="<?= base_url('bagus2.png') ?>">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <script src="https://cdn.tailwindcss.com"></script>
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
          colors: {
            primary: {
              50: '#e8ecf8', 100: '#c4cef0', 400: '#5673d6',
              500: '#2f4dbf', 600: '#021d6c', 700: '#011455',
            },
            neutral: {
              50: '#F8FAFC', 100: '#F1F5F9', 200: '#E2E8F0',
              400: '#94A3B8', 600: '#475569', 800: '#1E293B', 900: '#0F172A',
            }
          }
        }
      }
    }
  </script>

  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-white min-h-screen flex font-sans text-neutral-800">

  <div class="min-h-screen w-full flex">
    <!-- Kiri -->
    <div class="hidden lg:flex w-1/2 flex-col items-center justify-center p-12 relative overflow-hidden" style="background:#021d6c;">
      <!-- Noise texture overlay (simulasi) -->
      <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjZmZmIi8+CjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9IiMwMDAiLz4KPC9zdmc+')]"></div>
      
      <div class="relative z-10 text-white text-center">
        <img src="<?= base_url('bagus.png') ?>" alt="CarHub" style="height:220px;width:auto;" class="mx-auto mb-6 drop-shadow-lg">
        <p class="text-primary-100 text-base max-w-sm mx-auto leading-relaxed">Sistem Manajemen Jual Beli Mobil yang efisien, terstruktur, dan mudah digunakan.</p>
      </div>
      
      <!-- Dekorasi -->
      <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-primary-500 rounded-full blur-3xl opacity-50 mix-blend-multiply"></div>
      <div class="absolute -top-32 -right-32 w-96 h-96 bg-primary-400 rounded-full blur-3xl opacity-50 mix-blend-multiply"></div>
    </div>

    <!-- Kanan -->
    <div class="flex-1 flex items-center justify-center p-8 bg-white">
      <div class="w-full max-w-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <div class="lg:hidden mb-6">
          <img src="<?= base_url('bagus2.png') ?>" alt="CarHub" style="height:100px;width:auto;">
        </div>

        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Selamat Datang</h2>
        <p class="text-sm text-neutral-500 mb-8">Silakan masuk ke akun Anda untuk melanjutkan.</p>

        <?php if($this->session->flashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm mb-6 flex items-center gap-2">
          <i data-lucide="alert-circle" class="w-4 h-4"></i>
          <?= $this->session->flashdata('error') ?>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/login') ?>" method="POST" class="space-y-5">
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700 block">Username</label>
            <div class="relative">
              <i data-lucide="user" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
              <input type="text" name="username" placeholder="Masukkan username" required
                     class="w-full pl-10 pr-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-sm font-medium text-neutral-700 block">Password</label>
            <div class="relative">
              <i data-lucide="lock" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
              <input type="password" name="password" placeholder="••••••••" required
                     class="w-full pl-10 pr-4 py-2.5 text-sm border border-neutral-200 rounded-lg bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
            </div>
          </div>

          <button type="submit" class="w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm shadow-primary-600/20 active:scale-[0.98]">
            Masuk ke Sistem
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    lucide.createIcons();
  </script>

  <!-- Framer Motion for login page -->
  <script type="importmap">
  { "imports": { "framer-motion": "https://cdn.jsdelivr.net/npm/framer-motion@11/dist/framer-motion.mjs" } }
  </script>
  <script type="module">
    import { animate, stagger } from "framer-motion";

    // Left panel slide-in
    const leftPanel = document.querySelector('.hidden.lg\\:flex');
    if (leftPanel) {
      animate(leftPanel, { opacity: [0, 1], x: [-40, 0] },
        { duration: 0.6, easing: [0.25, 0.46, 0.45, 0.94] });
    }

    // Logo in left panel
    const logo = document.querySelector('.hidden.lg\\:flex img');
    if (logo) {
      animate(logo, { opacity: [0, 1], scale: [0.8, 1], y: [20, 0] },
        { duration: 0.6, delay: 0.2, easing: [0.34, 1.56, 0.64, 1] });
    }

    // Right form panel
    const rightPanel = document.querySelector('.flex-1.flex.items-center');
    if (rightPanel) {
      animate(rightPanel, { opacity: [0, 1], x: [30, 0] },
        { duration: 0.55, delay: 0.1, easing: [0.25, 0.46, 0.45, 0.94] });
    }

    // Form elements stagger
    const formEls = document.querySelectorAll('h2, p, form > div, form button, .bg-red-50');
    if (formEls.length) {
      animate(formEls,
        { opacity: [0, 1], y: [14, 0] },
        { duration: 0.35, delay: stagger(0.07, { start: 0.3 }), easing: 'ease-out' }
      );
    }

    // Button hover/press
    const submitBtn = document.querySelector('button[type="submit"]');
    if (submitBtn) {
      submitBtn.addEventListener('mouseenter', () => animate(submitBtn, { scale: 1.02 }, { duration: 0.15 }));
      submitBtn.addEventListener('mouseleave', () => animate(submitBtn, { scale: 1 }, { duration: 0.15 }));
      submitBtn.addEventListener('mousedown', () => animate(submitBtn, { scale: 0.97 }, { duration: 0.1 }));
      submitBtn.addEventListener('mouseup', () => animate(submitBtn, { scale: 1 }, { duration: 0.2, easing: [0.34,1.56,0.64,1] }));
    }
  </script>
</body>
</html>
