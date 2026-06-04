  </div> <!-- Close Main -->
  
  <!-- Overlay & Modal Base -->
  <div class="fixed inset-0 bg-neutral-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" id="modal-overlay" style="display: none;">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto" id="modal-card">
      <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100 sticky top-0 bg-white rounded-t-2xl">
        <h3 class="font-semibold text-neutral-900" id="modal-title">Modal Title</h3>
        <button class="p-1.5 rounded-lg hover:bg-neutral-100 text-neutral-500" onclick="closeModal()">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>
      <div class="px-6 py-5" id="modal-body">
        <!-- Content -->
      </div>
    </div>
  </div>

  <!-- Toast Container -->
  <div id="toast-container" class="fixed bottom-6 right-6 z-[60] flex flex-col gap-2 pointer-events-none"></div>

  <!-- Global non-module JS -->
  <script>
    lucide.createIcons();

    window.openModal = function(title, contentHtml) {
      document.getElementById('modal-title').textContent = title;
      document.getElementById('modal-body').innerHTML = contentHtml;
      const overlay = document.getElementById('modal-overlay');
      const card = document.getElementById('modal-card');
      overlay.style.display = 'flex';
      overlay.style.opacity = '0';
      card.style.transform = 'scale(0.94) translateY(12px)';
      card.style.opacity = '0';
      card.style.transition = 'transform 0.25s cubic-bezier(0.34,1.56,0.64,1), opacity 0.2s ease';
      overlay.style.transition = 'opacity 0.2s ease';
      lucide.createIcons();
      requestAnimationFrame(() => {
        overlay.style.opacity = '1';
        card.style.transform = 'scale(1) translateY(0)';
        card.style.opacity = '1';
      });
    };

    window.closeModal = function() {
      const overlay = document.getElementById('modal-overlay');
      const card = document.getElementById('modal-card');
      card.style.transform = 'scale(0.94) translateY(8px)';
      card.style.opacity = '0';
      overlay.style.opacity = '0';
      setTimeout(() => { overlay.style.display = 'none'; }, 220);
    };

    window.showToast = function(message, type = 'success') {
      const colors = {
        success: 'bg-emerald-600',
        error: 'bg-red-600',
        warning: 'bg-amber-500',
      };
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      toast.className = `pointer-events-auto px-4 py-3 rounded-xl ${colors[type]} text-white text-sm font-medium shadow-lg flex items-center gap-2`;
      toast.style.transform = 'translateY(16px) scale(0.96)';
      toast.style.opacity = '0';
      toast.style.transition = 'all 0.35s cubic-bezier(0.34,1.56,0.64,1)';
      let iconName = type === 'success' ? 'check-circle' : (type === 'error' ? 'alert-circle' : 'alert-triangle');
      toast.innerHTML = `<i data-lucide="${iconName}" class="w-4 h-4"></i> ${message}`;
      container.appendChild(toast);
      lucide.createIcons();
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          toast.style.transform = 'translateY(0) scale(1)';
          toast.style.opacity = '1';
        });
      });
      setTimeout(() => {
        toast.style.transform = 'translateY(8px) scale(0.95)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 350);
      }, 3500);
    };

    document.getElementById('modal-overlay').addEventListener('click', function(e) {
      if (e.target === this) closeModal();
    });
  </script>

  <!-- Framer Motion Importmap -->
  <script type="importmap">
  { "imports": { "framer-motion": "https://cdn.jsdelivr.net/npm/framer-motion@11/dist/framer-motion.mjs" } }
  </script>

  <!-- Framer Motion Module: global animations for all screens -->
  <script type="module">
    import { animate, stagger, inView } from "framer-motion";

    window.animate = animate;
    window.stagger = stagger;

    // ─── PAGE ENTRY ───────────────────────────────────────────────────
    // Fade-slide in the entire main content area
    const mainEl = document.querySelector('main.animate-in, main, .animate-in');
    if (mainEl) {
      animate(mainEl, { opacity: [0, 1], y: [20, 0] },
        { duration: 0.45, easing: [0.25, 0.46, 0.45, 0.94] });
    }

    // ─── SIDEBAR ─────────────────────────────────────────────────────
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    if (sidebarItems.length) {
      animate(sidebarItems,
        { opacity: [0, 1], x: [-14, 0] },
        { duration: 0.35, delay: stagger(0.04, { start: 0.1 }), easing: 'ease-out' }
      );
    }

    // Sidebar logo
    const sidebarLogo = document.querySelector('aside img');
    if (sidebarLogo) {
      animate(sidebarLogo,
        { opacity: [0, 1], scale: [0.85, 1] },
        { duration: 0.4, delay: 0.05, easing: [0.34, 1.56, 0.64, 1] }
      );
    }

    // ─── TOPBAR ───────────────────────────────────────────────────────
    const topbar = document.querySelector('header');
    if (topbar) {
      animate(topbar, { opacity: [0, 1], y: [-8, 0] },
        { duration: 0.35, easing: 'ease-out' });
    }

    // ─── STAT CARDS ──────────────────────────────────────────────────
    const statCards = document.querySelectorAll('.stat-card');
    if (statCards.length) {
      animate(statCards,
        { opacity: [0, 1], y: [24, 0], scale: [0.96, 1] },
        { duration: 0.5, delay: stagger(0.08, { start: 0.15 }), easing: [0.34, 1.56, 0.64, 1] }
      );
    }

    // ─── TABLE ROWS ──────────────────────────────────────────────────
    const tableRows = document.querySelectorAll('#dataTable tbody tr');
    if (tableRows.length) {
      animate(tableRows,
        { opacity: [0, 1], x: [-10, 0] },
        { duration: 0.3, delay: stagger(0.035, { start: 0.2 }), easing: 'ease-out' }
      );
    }

    // ─── PAGE HEADERS / SECTION HEADINGS ─────────────────────────────
    const pageHeadings = document.querySelectorAll('h2.font-semibold, h2.font-bold, h3.font-semibold');
    if (pageHeadings.length) {
      animate(pageHeadings,
        { opacity: [0, 1], y: [10, 0] },
        { duration: 0.35, delay: stagger(0.06, { start: 0.1 }), easing: 'ease-out' }
      );
    }

    // ─── FORM ELEMENTS ────────────────────────────────────────────────
    const formGroups = document.querySelectorAll('form .space-y-1\\.5, form > div');
    if (formGroups.length) {
      animate(formGroups,
        { opacity: [0, 1], y: [12, 0] },
        { duration: 0.3, delay: stagger(0.06, { start: 0.1 }), easing: 'ease-out' }
      );
    }

    // ─── CHART CONTAINERS ─────────────────────────────────────────────
    const charts = document.querySelectorAll('canvas');
    if (charts.length) {
      animate(charts,
        { opacity: [0, 1], scale: [0.97, 1] },
        { duration: 0.5, delay: stagger(0.1, { start: 0.4 }), easing: [0.25, 0.46, 0.45, 0.94] }
      );
    }

    // ─── FLASH MESSAGE BANNERS ────────────────────────────────────────
    const alerts = document.querySelectorAll('[class*="bg-emerald"], [class*="bg-red-50"], [class*="bg-amber-50"]');
    if (alerts.length) {
      animate(alerts,
        { opacity: [0, 1], y: [-8, 0] },
        { duration: 0.3, easing: 'ease-out' }
      );
    }

    // ─── INVIEW: animate elements as they scroll into view ────────────
    inView('.feature-card, [data-animate]', ({ target }) => {
      animate(target,
        { opacity: [0, 1], y: [20, 0] },
        { duration: 0.4, easing: 'ease-out' }
      );
    }, { amount: 0.2 });

    // ─── BUTTON MICRO-INTERACTIONS ───────────────────────────────────
    document.querySelectorAll('button:not([onclick*="close"]):not([onclick*="hapus"]), a.px-3, a.px-4').forEach(btn => {
      btn.addEventListener('mouseenter', () => {
        animate(btn, { scale: 1.03 }, { duration: 0.15, easing: 'ease-out' });
      });
      btn.addEventListener('mouseleave', () => {
        animate(btn, { scale: 1 }, { duration: 0.15, easing: 'ease-out' });
      });
      btn.addEventListener('mousedown', () => {
        animate(btn, { scale: 0.96 }, { duration: 0.1, easing: 'ease-out' });
      });
      btn.addEventListener('mouseup', () => {
        animate(btn, { scale: 1 }, { duration: 0.15, easing: [0.34, 1.56, 0.64, 1] });
      });
    });

    // ─── TABLE ROW HOVER (subtle lift) ───────────────────────────────
    document.querySelectorAll('#dataTable tbody tr').forEach(row => {
      row.addEventListener('mouseenter', () => {
        animate(row, { backgroundColor: '#f8fafc' }, { duration: 0.15 });
      });
      row.addEventListener('mouseleave', () => {
        animate(row, { backgroundColor: '#ffffff' }, { duration: 0.15 });
      });
    });
  </script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</body>
</html>

