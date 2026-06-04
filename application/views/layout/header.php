<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($title) ? $title . ' - CarHub' : 'CarHub - Sistem Manajemen Jual Beli Mobil'; ?></title>
  <link rel="icon" type="image/png" href="<?= base_url('bagus2.png') ?>">
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Tailwind Config -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Plus Jakarta Sans', 'sans-serif'],
            mono: ['DM Mono', 'monospace'],
          },
          colors: {
            primary: {
              50: '#e8ecf8',
              100: '#c4cef0',
              400: '#5673d6',
              500: '#2f4dbf',
              600: '#021d6c',
              700: '#011455',
            },
            neutral: {
              50: '#F8FAFC',
              100: '#F1F5F9',
              200: '#E2E8F0',
              400: '#94A3B8',
              600: '#475569',
              800: '#1E293B',
              900: '#0F172A',
            }
          }
        }
      }
    }
  </script>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <!-- Framer Motion import map -->
  <script type="importmap">
  {
    "imports": {
      "framer-motion": "https://cdn.jsdelivr.net/npm/framer-motion@11/dist/framer-motion.mjs"
    }
  }
  </script>

  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 14px;
      color: #1E293B;
      line-height: 1.6;
    }
    
    /* Scrollbar minimalis */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: transparent;
    }
    ::-webkit-scrollbar-thumb {
      background: #E2E8F0;
      border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #94A3B8;
    }
  </style>

</head>
<body class="bg-neutral-50 flex h-screen overflow-hidden">
