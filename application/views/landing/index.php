<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CarHub - Jual Beli Mobil Terpercaya</title>
  <meta name="description" content="CarHub adalah platform jual beli mobil terpercaya di Indonesia. Temukan mobil impian Anda dengan harga terbaik, proses mudah, dan terjamin.">
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/bagus2.png') ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
          colors: {
            brand: {
              50:  '#e8ecf8',
              100: '#c4cef0',
              200: '#a0b2e6',
              400: '#5673d6',
              500: '#2f4dbf',
              600: '#021d6c',
              700: '#011455',
              800: '#010e3f',
              900: '#00092a',
            }
          },
          animation: {
            'float': 'float 6s ease-in-out infinite',
            'float-slow': 'float 9s ease-in-out infinite',
            'fade-up': 'fadeUp 0.7s ease-out forwards',
          },
          keyframes: {
            float: { '0%, 100%': { transform: 'translateY(0px)' }, '50%': { transform: 'translateY(-16px)' } },
            fadeUp: { '0%': { opacity: '0', transform: 'translateY(24px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
          }
        }
      }
    }
  </script>
  <style>
    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }

    .hero-gradient {
      background: linear-gradient(135deg, #00092a 0%, #021d6c 40%, #011455 70%, #010e3f 100%);
    }
    .card-glass {
      background: rgba(255,255,255,0.07);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255,255,255,0.12);
    }
    .shine-border {
      position: relative;
    }
    .shine-border::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: inherit;
      padding: 1px;
      background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0));
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      pointer-events: none;
    }
    .text-gradient {
      background: linear-gradient(135deg, #ffffff 0%, #c7d2fe 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .nav-glass {
      backdrop-filter: blur(24px) saturate(180%);
      -webkit-backdrop-filter: blur(24px) saturate(180%);
      background: rgba(255, 255, 255, 0.08);
      border-bottom: 1px solid rgba(255,255,255,0.12);
    }
    .feature-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 24px 48px rgba(10,17,64,0.15);
    }
    .feature-card { transition: all 0.3s ease; }
    .car-blur-1 {
      background: radial-gradient(ellipse at center, rgba(99, 102, 241, 0.35) 0%, transparent 70%);
    }
    .car-blur-2 {
      background: radial-gradient(ellipse at center, rgba(30, 46, 120, 0.5) 0%, transparent 70%);
    }
    .stat-number {
      background: linear-gradient(135deg, #ffffff, #a5b4fc);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
  </style>
</head>
<body class="font-sans bg-white text-gray-900 antialiased">

  <!-- NAV -->
  <header class="fixed top-0 w-full z-50 nav-glass">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
      <a href="#" class="flex items-center gap-3">
        <img src="<?= base_url('assets/img/bagus.png') ?>" alt="CarHub Logo" style="height:44px;width:auto;"></a>
      <nav class="hidden md:flex items-center gap-8">
        <a href="#fitur" class="text-sm text-white/70 hover:text-white transition-colors">Fitur</a>
        <a href="#cara-kerja" class="text-sm text-white/70 hover:text-white transition-colors">Cara Kerja</a>
        <a href="#keunggulan" class="text-sm text-white/70 hover:text-white transition-colors">Keunggulan</a>
        <a href="#testimoni" class="text-sm text-white/70 hover:text-white transition-colors">Testimoni</a>
      </nav>
      <div class="flex items-center gap-3">
        <a href="<?= base_url('auth') ?>" class="hidden sm:block text-sm text-white/80 hover:text-white px-4 py-2 rounded-lg hover:bg-white/10 transition-colors">Masuk</a>
        <a href="<?= base_url('auth') ?>" class="text-sm font-semibold bg-white text-brand-600 px-4 py-2 rounded-lg hover:bg-brand-50 transition-colors shadow-md">
          Masuk Panel
        </a>
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section class="relative min-h-screen flex flex-col overflow-hidden">

    <!-- Background image -->
    <div class="absolute inset-0 z-0">
      <img src="<?= base_url('show.png') ?>" alt="CarHub Showroom"
           class="w-full h-full object-cover object-center">
      <!-- Cinematic multi-layer overlay -->
      <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(0,9,42,0.92) 0%, rgba(2,29,108,0.75) 50%, rgba(0,9,42,0.55) 100%);"></div>
      <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 60%);"></div>
    </div>

    <!-- Subtle animated particles -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
      <div class="absolute w-96 h-96 rounded-full opacity-20 animate-pulse" style="background: radial-gradient(circle, #021d6c, transparent); top: 10%; left: 5%; animation-duration: 4s;"></div>
      <div class="absolute w-72 h-72 rounded-full opacity-10 animate-pulse" style="background: radial-gradient(circle, #5673d6, transparent); top: 40%; right: 10%; animation-duration: 6s;"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 flex-1 flex flex-col justify-center px-6 pt-24 pb-16 max-w-7xl mx-auto w-full">
      <div class="max-w-3xl">

        <!-- Headline -->
        <h1 class="font-extrabold text-white leading-none tracking-tight mb-6" style="font-size: clamp(3rem, 7vw, 5.5rem); text-shadow: 0 4px 32px rgba(0,0,0,0.4);">
          Temukan Mobil<br>
          <span style="background: linear-gradient(90deg, #60a5fa, #a5b4fc, #93c5fd); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Impian</span>
          &nbsp;Anda
        </h1>

        <!-- Subtext -->
        <p class="text-white/65 text-lg leading-relaxed mb-10 max-w-xl" style="text-shadow: 0 2px 16px rgba(0,0,0,0.5);">
          CarHub menghadirkan pengalaman jual beli mobil yang <strong class="text-white/85 font-semibold">transparan</strong>, <strong class="text-white/85 font-semibold">terpercaya</strong>, dan <strong class="text-white/85 font-semibold">efisien</strong>, langsung di ujung jari Anda.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mb-16">
          <a href="<?= base_url('auth') ?>"
             class="inline-flex items-center justify-center gap-2.5 font-bold text-sm px-8 py-4 rounded-xl transition-all"
             style="background: #021d6c; color: white; box-shadow: 0 8px 32px rgba(2,29,108,0.5); border: 1px solid rgba(255,255,255,0.15);">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            Masuk ke Panel Admin
          </a>
          <a href="#fitur"
             class="inline-flex items-center justify-center gap-2.5 font-semibold text-sm px-8 py-4 rounded-xl transition-all"
             style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(8px);">
            Lihat Fitur Lengkap
            <i data-lucide="arrow-down" class="w-4 h-4"></i>
          </a>
        </div>

        <!-- Stats row -->
        <div class="flex gap-0 flex-wrap">
          <div class="pr-8 mr-8 border-r border-white/15">
            <p class="text-4xl font-black text-white" style="text-shadow: 0 2px 20px rgba(2,29,108,0.6);">500<span class="text-blue-400">+</span></p>
            <p class="text-xs text-white/50 mt-1 font-medium tracking-wide uppercase">Mobil Terjual</p>
          </div>
          <div class="pr-8 mr-8 border-r border-white/15">
            <p class="text-4xl font-black text-white" style="text-shadow: 0 2px 20px rgba(2,29,108,0.6);">1.2K<span class="text-blue-400">+</span></p>
            <p class="text-xs text-white/50 mt-1 font-medium tracking-wide uppercase">Customer Puas</p>
          </div>
          <div>
            <p class="text-4xl font-black text-white" style="text-shadow: 0 2px 20px rgba(2,29,108,0.6);">98<span class="text-blue-400">%</span></p>
            <p class="text-xs text-white/50 mt-1 font-medium tracking-wide uppercase">Rating Kepuasan</p>
          </div>
        </div>
      </div>
    </div>

   

    <!-- Bottom fade to white -->
    <div class="absolute bottom-0 left-0 right-0 h-24 z-10" style="background: linear-gradient(to bottom, transparent, white);"></div>
  </section>


  <!-- FITUR SECTION -->
  <section id="fitur" class="py-24 bg-white px-6">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-16" data-reveal>
        <p class="text-sm font-semibold text-brand-600 uppercase tracking-widest mb-3">Fitur Lengkap</p>
        <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Semua yang Anda Butuhkan</h2>
        <p class="text-gray-500 max-w-xl mx-auto">Dari pencatatan stok hingga laporan keuangan, semua tersedia dalam satu sistem yang terintegrasi.</p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
          $features = [
            ['icon'=>'car-front', 'color'=>'bg-blue-50 text-blue-600', 'title'=>'Manajemen Stok Mobil', 'desc'=>'Pantau stok kendaraan secara real-time. Status tersedia, booking, dan terjual terupdate otomatis setiap transaksi.'],
            ['icon'=>'users', 'color'=>'bg-violet-50 text-violet-600', 'title'=>'Data Customer & Supplier', 'desc'=>'Kelola database pelanggan dan pemasok dengan lengkap. Cari, filter, dan edit data kapan saja dengan mudah.'],
            ['icon'=>'file-text', 'color'=>'bg-emerald-50 text-emerald-600', 'title'=>'Laporan & Faktur Otomatis', 'desc'=>'Cetak faktur pembelian, penjualan, dan laporan rekap transaksi secara otomatis hanya dengan satu klik.'],
            ['icon'=>'wallet', 'color'=>'bg-amber-50 text-amber-600', 'title'=>'Manajemen Pembayaran', 'desc'=>'Proses tanda jadi, DP, hingga pelunasan dengan terpadu. Semua tahap pembayaran tercatat rapi dan transparan.'],
            ['icon'=>'key', 'color'=>'bg-rose-50 text-rose-600', 'title'=>'Serah Terima Kendaraan', 'desc'=>'Proses serah terima unit dan BPKB langsung dari sistem. Dokumen lengkap, tidak ada yang terlewat.'],
            ['icon'=>'shield-check', 'color'=>'bg-teal-50 text-teal-600', 'title'=>'Hak Akses Multi-Role', 'desc'=>'Pisahkan akses antara Admin operasional dan Owner pemilik. Owner hanya bisa melihat dan cetak laporan.'],
          ];
          foreach ($features as $f): ?>
        <div class="feature-card bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:border-brand-200" data-reveal>
          <div class="w-11 h-11 <?= $f['color'] ?> rounded-xl flex items-center justify-center mb-4">
            <i data-lucide="<?= $f['icon'] ?>" class="w-5 h-5"></i>
          </div>
          <h3 class="font-bold text-gray-900 mb-2"><?= $f['title'] ?></h3>
          <p class="text-sm text-gray-500 leading-relaxed"><?= $f['desc'] ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CARA KERJA -->
  <section id="cara-kerja" class="py-24 bg-gray-50 px-6">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-16" data-reveal>
        <p class="text-sm font-semibold text-brand-600 uppercase tracking-widest mb-3">Alur Sistem</p>
        <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Cara Kerja CarHub</h2>
        <p class="text-gray-500 max-w-xl mx-auto">Alur transaksi yang terstruktur dari pembelian stok dari supplier hingga penyerahan unit ke customer.</p>
      </div>

      <div class="grid md:grid-cols-2 gap-12 items-center">
        <!-- Pembelian flow -->
        <div class="space-y-4" data-reveal>
          <p class="text-xs font-bold text-brand-600 uppercase tracking-widest mb-6">Alur Pembelian (Stok Masuk)</p>
          <?php
            $purchase_steps = [
              ['n'=>'01', 'title'=>'Input Supplier & Mobil', 'desc'=>'Daftarkan supplier dan detail unit kendaraan yang dibeli.'],
              ['n'=>'02', 'title'=>'Buat Transaksi Pembelian', 'desc'=>'Catat transaksi pembelian dari supplier dengan harga beli.'],
              ['n'=>'03', 'title'=>'Proses Pembayaran ke Supplier', 'desc'=>'Bayar via tunai atau transfer — upload bukti pembayaran.'],
              ['n'=>'04', 'title'=>'Stok Otomatis Bertambah', 'desc'=>'Setelah dibayar, stok mobil otomatis terupdate di sistem.'],
            ];
            foreach ($purchase_steps as $i => $step): ?>
          <div class="flex gap-4 items-start">
            <div class="w-10 h-10 rounded-xl bg-brand-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0"><?= $step['n'] ?></div>
            <div>
              <p class="font-semibold text-gray-900 text-sm"><?= $step['title'] ?></p>
              <p class="text-sm text-gray-500 mt-0.5"><?= $step['desc'] ?></p>
            </div>
          </div>
          <?php if ($i < count($purchase_steps)-1): ?>
          <div class="ml-5 border-l-2 border-dashed border-brand-200 h-4"></div>
          <?php endif; endforeach; ?>
        </div>

        <!-- Penjualan flow -->
        <div class="space-y-4" data-reveal>
          <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-6">Alur Penjualan (Ke Customer)</p>
          <?php
            $sales_steps = [
              ['n'=>'01', 'title'=>'Buat Pemesanan Customer', 'desc'=>'Input data customer dan pilih unit mobil yang dipesan.'],
              ['n'=>'02', 'title'=>'Bayar Tanda Jadi (Rp 500rb)', 'desc'=>'Customer membayar tanda jadi untuk mengamankan unit.'],
              ['n'=>'03', 'title'=>'Bayar DP (Min. 30%)', 'desc'=>'Setelah DP masuk, data otomatis pindah ke modul Penjualan.'],
              ['n'=>'04', 'title'=>'Pelunasan & Serah Terima', 'desc'=>'Bayar sisa harga, lalu proses serah terima unit + BPKB.'],
            ];
            foreach ($sales_steps as $i => $step): ?>
          <div class="flex gap-4 items-start">
            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0"><?= $step['n'] ?></div>
            <div>
              <p class="font-semibold text-gray-900 text-sm"><?= $step['title'] ?></p>
              <p class="text-sm text-gray-500 mt-0.5"><?= $step['desc'] ?></p>
            </div>
          </div>
          <?php if ($i < count($sales_steps)-1): ?>
          <div class="ml-5 border-l-2 border-dashed border-emerald-200 h-4"></div>
          <?php endif; endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- KEUNGGULAN / WHY US -->
  <section id="keunggulan" class="py-24 hero-gradient px-6 relative overflow-hidden">
    <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 60px 60px;"></div>
    <div class="max-w-7xl mx-auto relative z-10">
      <div class="text-center mb-16" data-reveal>
        <p class="text-sm font-semibold text-brand-200 uppercase tracking-widest mb-3">Keunggulan Kami</p>
        <h2 class="text-4xl font-extrabold text-white mb-4">Kenapa Pilih CarHub?</h2>
        <p class="text-white/50 max-w-xl mx-auto">Kami hadir untuk mempermudah operasional bisnis showroom mobil Anda dari A sampai Z.</p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <?php
          $whys = [
            ['icon'=>'zap', 'title'=>'Cepat & Efisien', 'desc'=>'Semua proses dari pemesanan hingga penyerahan dapat dilakukan dalam hitungan menit, bukan hari.'],
            ['icon'=>'shield', 'title'=>'Aman & Terpercaya', 'desc'=>'Data tersimpan aman dengan sistem otentikasi multi-role. Admin dan Owner memiliki akses yang terpisah.'],
            ['icon'=>'trending-up', 'title'=>'Laporan Real-Time', 'desc'=>'Pantau performa penjualan, stok, dan cashflow bisnis Anda kapan saja langsung dari dashboard.'],
          ];
          foreach ($whys as $w): ?>
        <div class="card-glass shine-border rounded-2xl p-7 text-center" data-reveal>
          <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i data-lucide="<?= $w['icon'] ?>" class="w-7 h-7 text-white"></i>
          </div>
          <h3 class="text-lg font-bold text-white mb-3"><?= $w['title'] ?></h3>
          <p class="text-sm text-white/55 leading-relaxed"><?= $w['desc'] ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- TESTIMONI -->
  <section id="testimoni" class="py-24 bg-white px-6">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-16" data-reveal>
        <p class="text-sm font-semibold text-brand-600 uppercase tracking-widest mb-3">Testimoni</p>
        <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Yang Mereka Katakan</h2>
        <p class="text-gray-500 max-w-xl mx-auto">Dipercaya oleh puluhan showroom mobil di seluruh Indonesia.</p>
      </div>
      <div class="grid md:grid-cols-3 gap-6">
        <?php
          $testimonials = [
            ['name'=>'Budi Santoso', 'role'=>'Owner — Showroom Anugrah Motor, Jakarta', 'avatar'=>'BS', 'text'=>'"CarHub benar-benar mengubah cara kami mengelola showroom. Laporan yang dulunya manual kini tersaji otomatis. Tim kami sangat terbantu!"'],
            ['name'=>'Dewi Rahayu', 'role'=>'Manager Operasional — Prima Auto, Surabaya', 'avatar'=>'DR', 'text'=>'"Fitur manajemen pembayaran multi-tahap sangat membantu kami. Tidak ada lagi bukti transfer yang hilang atau pembayaran yang terlewat."'],
            ['name'=>'Hendra Kusuma', 'role'=>'Direktur — Berkah Jaya Otomotif, Bandung', 'avatar'=>'HK', 'text'=>'"Sebagai owner, saya bisa pantau performa penjualan kapan saja dari dashboard. Data akurat, real-time, dan sangat informatif."'],
          ];
          foreach ($testimonials as $t): ?>
        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-7 feature-card" data-reveal>
          <div class="flex gap-1 mb-4">
            <?php for($i=0;$i<5;$i++): ?><i data-lucide="star" class="w-4 h-4 text-amber-400 fill-amber-400"></i><?php endfor; ?>
          </div>
          <p class="text-gray-700 text-sm leading-relaxed mb-6"><?= $t['text'] ?></p>
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center"><?= $t['avatar'] ?></div>
            <div>
              <p class="text-sm font-semibold text-gray-900"><?= $t['name'] ?></p>
              <p class="text-xs text-gray-400"><?= $t['role'] ?></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CTA SECTION -->
  <section class="py-24 bg-gray-50 px-6">
    <div class="max-w-3xl mx-auto text-center" data-reveal>
      <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Siap Mengelola Showroom Anda?</h2>
      <p class="text-gray-500 mb-8 leading-relaxed">Masuk ke panel admin CarHub dan mulai optimalkan bisnis jual beli mobil Anda sekarang.</p>
      <a href="<?= base_url('auth') ?>"
         class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold px-10 py-4 rounded-xl transition-all shadow-xl hover:shadow-2xl hover:-translate-y-0.5 text-base">
        <i data-lucide="log-in" class="w-5 h-5"></i>
        Masuk ke Panel Sekarang
      </a>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="hero-gradient text-white py-12 px-6">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-3">
          <img src="<?= base_url('assets/img/bagus.png') ?>" alt="CarHub" style="height:90px;width:auto;">
        </div>
        <p class="text-sm text-white/40">&copy; <?= date('Y') ?> CarHub. Platform Jual Beli Mobil Terpercaya.</p>
        <div class="flex items-center gap-6">
          <a href="#fitur" class="text-sm text-white/50 hover:text-white transition-colors">Fitur</a>
          <a href="#cara-kerja" class="text-sm text-white/50 hover:text-white transition-colors">Cara Kerja</a>
          <a href="<?= base_url('auth') ?>" class="text-sm text-white/50 hover:text-white transition-colors">Login</a>
        </div>
      </div>
    </div>
  </footer>

  <script>
    lucide.createIcons();
  </script>

  <!-- Framer Motion importmap -->
  <script type="importmap">
  { "imports": { "framer-motion": "https://cdn.jsdelivr.net/npm/framer-motion@11/dist/framer-motion.mjs" } }
  </script>

  <script type="module">
    import { animate, stagger, inView } from "framer-motion";

    // ── NAV entrance
    animate('header nav a', { opacity: [0, 1], y: [-6, 0] },
      { duration: 0.3, delay: stagger(0.06, { start: 0.4 }), easing: 'ease-out' });

    // ── Hero content entrance
    const heroContent = document.querySelectorAll('h1, h1 + p, h1 ~ div.flex');
    if (heroContent.length) {
      animate(heroContent, { opacity: [0, 1], y: [16, 0], scale: [0.98, 1] },
        { duration: 0.5, delay: stagger(0.15, { start: 0.1 }), easing: [0.34, 1.56, 0.64, 1] });
    }

    // ── All [data-reveal] elements with stagger
    const reveals = document.querySelectorAll('[data-reveal]');
    reveals.forEach((el, i) => {
      inView(el, () => {
        animate(el, { opacity: [0, 1], y: [28, 0] },
          { duration: 0.55, delay: (i % 4) * 0.07, easing: [0.25, 0.46, 0.45, 0.94] }
        );
      }, { amount: 0.15 });
    });

    // ── Stat numbers count-up effect
    inView('.stat-number', ({ target }) => {
      animate(target, { opacity: [0, 1], scale: [0.8, 1] },
        { duration: 0.5, easing: [0.34, 1.56, 0.64, 1] });
    }, { amount: 0.5 });

    // ── Feature cards stagger on scroll
    inView('#fitur', () => {
      animate('.feature-card',
        { opacity: [0, 1], y: [30, 0], scale: [0.97, 1] },
        { duration: 0.45, delay: stagger(0.07), easing: [0.25, 0.46, 0.45, 0.94] }
      );
    }, { amount: 0.1 });

    // ── Step items stagger on scroll (cara kerja)
    inView('#cara-kerja', () => {
      animate('#cara-kerja .flex.gap-4',
        { opacity: [0, 1], x: [-16, 0] },
        { duration: 0.4, delay: stagger(0.08), easing: 'ease-out' }
      );
    }, { amount: 0.1 });

    // ── Why cards
    inView('#keunggulan', () => {
      animate('#keunggulan .card-glass',
        { opacity: [0, 1], y: [24, 0], scale: [0.96, 1] },
        { duration: 0.5, delay: stagger(0.1), easing: [0.34, 1.56, 0.64, 1] }
      );
    }, { amount: 0.1 });

    // ── Testimonials
    inView('#testimoni', () => {
      animate('#testimoni .feature-card',
        { opacity: [0, 1], y: [24, 0] },
        { duration: 0.45, delay: stagger(0.1), easing: [0.25, 0.46, 0.45, 0.94] }
      );
    }, { amount: 0.1 });

    // ── CTA section
    inView('section:last-of-type', () => {
      animate('section:last-of-type h2, section:last-of-type p, section:last-of-type a',
        { opacity: [0, 1], y: [20, 0] },
        { duration: 0.4, delay: stagger(0.1), easing: 'ease-out' }
      );
    }, { amount: 0.3 });

    // ── Nav CTA buttons micro-interaction
    document.querySelectorAll('a[href]').forEach(link => {
      if (!link.closest('nav') && !link.closest('footer')) return;
      link.addEventListener('mouseenter', () => animate(link, { scale: 1.04 }, { duration: 0.15 }));
      link.addEventListener('mouseleave', () => animate(link, { scale: 1 }, { duration: 0.15 }));
    });

    // ── Hero CTA buttons
    document.querySelectorAll('.hero-gradient a').forEach(btn => {
      btn.addEventListener('mouseenter', () => animate(btn, { scale: 1.04 }, { duration: 0.15 }));
      btn.addEventListener('mouseleave', () => animate(btn, { scale: 1 }, { duration: 0.15 }));
      btn.addEventListener('mousedown', () => animate(btn, { scale: 0.97 }, { duration: 0.1 }));
      btn.addEventListener('mouseup', () => animate(btn, { scale: 1 }, { duration: 0.2, easing: [0.34,1.56,0.64,1] }));
    });

    // ── Wave SVG entrance
    animate('svg path', { opacity: [0, 1], y: [20, 0] },
      { duration: 0.6, delay: 0.5, easing: 'ease-out' });
  </script>
</body>
</html>
