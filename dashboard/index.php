<?php
// ================= MENU CONFIG =================
$menus = [
  [
    "title" => "Dashboard",
    "icon" => "fas fa-home",
    "page" => "dashboard"
  ],
  [
    "title" => "Stok",
    "icon" => "fas fa-warehouse",
    "children" => [
      [
        "title" => "Stok Masuk",
        "icon" => "fas fa-arrow-down",
        "page" => "stok_masuk"
      ],
      [
        "title" => "Stok Keluar",
        "icon" => "fas fa-arrow-up",
        "page" => "stok_keluar"
      ]
    ]
  ],
  [
    "title" => "Transaksi",
    "icon" => "fas fa-tags",
    "page" => "transaksi"
  ],
  [
    "title" => "Supplier",
    "icon" => "fas fa-truck",
    "page" => "supplier"
  ],
  [
    "title" => "Laporan",
    "icon" => "fas fa-file-alt",
    "page" => "laporan"
  ]
];

$currentPage = $_GET['page'] ?? 'dashboard';
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pencatatan Stok</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    /* Minimal CSS untuk transisi & scrollbar */
    .sidebar {
      transition: transform 0.3s ease;
    }
    .chevron {
      transition: transform 0.3s ease;
    }
    .chevron.open {
      transform: rotate(180deg);
    }
    .sidebar-nav::-webkit-scrollbar {
      width: 4px;
    }
    .sidebar-nav::-webkit-scrollbar-track {
      background: #e2e8f0;
    }
    .sidebar-nav::-webkit-scrollbar-thumb {
      background: #94a3b8;
      border-radius: 10px;
    }
    
    /* Mobile */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        width: 280px;
      }
      .sidebar.open {
        transform: translateX(0);
      }
      .hamburger {
        display: flex !important;
      }
      .main-content {
        margin-left: 0 !important;
      }
    }
    .hamburger {
      display: none;
    }
  </style>
</head>

<body class="bg-slate-50 text-slate-800">

<!-- Hamburger Mobile -->
<button class="hamburger fixed top-4 left-4 z-50 bg-white p-2.5 rounded-lg shadow-md border border-slate-200 hover:bg-slate-50 transition" onclick="toggleSidebar()">
  <i class="fas fa-bars text-slate-700 text-lg"></i>
</button>

<div class="flex min-h-screen">

  <!-- ==================== SIDEBAR ==================== -->
  <aside class="sidebar fixed h-full w-64 bg-white border-r border-slate-200 shadow-sm flex flex-col z-40">

    <!-- HEADER -->
    <div class="px-5 py-4 border-b border-slate-200">
      <h1 class="text-lg font-bold text-slate-800 flex items-center gap-2.5">
        <i class="fas fa-box text-blue-600"></i>
        <span>Stok App</span>
      </h1>
    </div>

    <!-- NAVIGASI -->
    <nav class="sidebar-nav flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

      <?php foreach ($menus as $index => $menu): ?>

        <?php if (!isset($menu['children'])): ?>

          <!-- Menu item -->
          <a href="?page=<?= $menu['page'] ?>" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 
                    <?= $currentPage == $menu['page'] 
                       ? 'bg-blue-50 text-blue-700' 
                       : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' ?>">
            <i class="<?= $menu['icon'] ?> w-5 text-center text-base <?= $currentPage == $menu['page'] ? 'text-blue-600' : 'text-slate-400' ?>"></i>
            <?= $menu['title'] ?>
          </a>

        <?php else: ?>

          <?php
            $isOpen = false;
            foreach ($menu['children'] as $child) {
              if ($currentPage == $child['page']) $isOpen = true;
            }
          ?>

          <!-- Menu dengan child -->
          <div>
            <button onclick="toggleMenu(<?= $index ?>)" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 text-slate-600 hover:bg-slate-100 hover:text-slate-800">
              <span class="flex items-center gap-3">
                <i class="<?= $menu['icon'] ?> w-5 text-center text-base text-slate-400"></i>
                <?= $menu['title'] ?>
              </span>
              <i class="fas fa-chevron-down chevron text-slate-400 text-xs <?= $isOpen ? 'open' : '' ?>"></i>
            </button>

            <!-- Child items -->
            <div class="ml-7 mt-0.5 space-y-0.5 <?= $isOpen ? '' : 'hidden' ?>" id="child-<?= $index ?>">
              <?php foreach ($menu['children'] as $child): ?>
                <a href="?page=<?= $child['page'] ?>" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                          <?= $currentPage == $child['page'] 
                             ? 'bg-blue-50 text-blue-700' 
                             : 'text-slate-500 hover:bg-slate-100 hover:text-slate-700' ?>">
                  <i class="<?= $child['icon'] ?> w-5 text-center text-sm <?= $currentPage == $child['page'] ? 'text-blue-600' : 'text-slate-400' ?>"></i>
                  <?= $child['title'] ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>

        <?php endif; ?>

      <?php endforeach; ?>

      <!-- Separator -->
      <div class="border-t border-slate-200 my-3"></div>

      <!-- Menu tambahan -->
      <a href="?page=pengaturan" 
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                <?= $currentPage == 'pengaturan' 
                   ? 'bg-blue-50 text-blue-700' 
                   : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' ?>">
        <i class="fas fa-cog w-5 text-center text-base <?= $currentPage == 'pengaturan' ? 'text-blue-600' : 'text-slate-400' ?>"></i>
        Pengaturan
      </a>

    </nav>

    <!-- FOOTER -->
    <div class="px-3 py-3 border-t border-slate-200">
      <button class="w-full flex items-center justify-center gap-2.5 bg-red-50 hover:bg-red-100 text-red-600 font-medium text-sm py-2.5 rounded-lg transition-all duration-200">
        <i class="fas fa-sign-out-alt text-red-500"></i>
        Keluar
      </button>
    </div>

  </aside>

  <!-- ==================== MAIN CONTENT ==================== -->
  <main class="main-content flex-1 ml-64 p-6 min-h-screen">

    <?php
      $file = "pages/$currentPage.php";

      if (file_exists($file)) {
        include $file;
      } else {
        echo '
          <!-- Page Header -->
          <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-800">Dashboard</h2>
            <p class="text-sm text-slate-500 mt-0.5">Selamat datang di Sistem Informasi Akuntansi</p>
          </div>

          <!-- Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5">
              <p class="text-sm text-slate-500 font-medium">Total Stok</p>
              <p class="text-2xl font-bold text-slate-800 mt-1">1.234</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5">
              <p class="text-sm text-slate-500 font-medium">Stok Masuk</p>
              <p class="text-2xl font-bold text-emerald-600 mt-1">56</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5">
              <p class="text-sm text-slate-500 font-medium">Stok Keluar</p>
              <p class="text-2xl font-bold text-red-600 mt-1">23</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5">
              <p class="text-sm text-slate-500 font-medium">Supplier Aktif</p>
              <p class="text-2xl font-bold text-blue-600 mt-1">12</p>
            </div>
          </div>
        ';
      }
    ?>

  </main>

</div>

<!-- SCRIPT -->
<script>
function toggleMenu(index) {
  const child = document.getElementById('child-' + index);
  const chevron = child.previousElementSibling.querySelector('.chevron');
  child.classList.toggle('hidden');
  chevron.classList.toggle('open');
}

function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
}

// Close sidebar on outside click (mobile)
document.addEventListener('click', function(e) {
  const sidebar = document.getElementById('sidebar');
  const hamburger = document.querySelector('.hamburger');
  if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
    if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
      sidebar.classList.remove('open');
    }
  }
});
</script>

</body>
</html>