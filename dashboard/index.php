<?php

// Ini untuk check apakah sudah pernah login?
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login.php");
    exit;
}

// ==================================================
// ROUTING HANDLER
// ==================================================

class Router
{
    private $request;
    private $currentPage;
    private $pageTitle;
    private $routes;

    public function __construct()
    {
        $this->routes = [
            '' => ['page' => 'dashboard'],
            'produk' => ['page' => 'produk'],
            'kasir' => ['page' => 'kasir'],
            'transaksi' => ['page' => 'transaksi'],
        ];

        $this->initializeRequest();
        $this->resolveRoute();
    }

    private function initializeRequest()
    {
        $this->request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->request = trim($this->request, '/');

        // Remove dashboard prefix if present
        $dashboardPath = 'dashboard';
        $position = strpos($this->request, $dashboardPath);

        if ($position !== false) {
            $this->request = substr(
                $this->request,
                $position + strlen($dashboardPath)
            );
        }

        $this->request = trim($this->request, '/');
    }

    private function resolveRoute()
    {
        if (!isset($this->routes[$this->request])) {
            http_response_code(404);
            $this->currentPage = null;
            $this->pageTitle = '404';
        } else {
            $this->currentPage = $this->routes[$this->request]['page'];
            $this->pageTitle = ucfirst(
                str_replace('_', ' ', $this->currentPage)
            );
        }
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    public function isActiveRoute($route)
    {
        return $this->request === $route;
    }

    public function renderContent()
    {
        if ($this->currentPage) {
            $file = __DIR__ . "/pages/{$this->currentPage}.php";
            if (file_exists($file)) {
                include $file;
            } else {
                echo $this->renderNotFound();
            }
        } else {
            echo $this->render404();
        }
    }

    private function renderNotFound()
    {
        return '<h2 class="text-2xl font-bold">Halaman tidak ditemukan</h2>';
    }

    private function render404()
    {
        return '
            <h2 class="text-2xl font-bold text-gray-800">404</h2>
            <p class="text-gray-600 mt-2">Halaman tidak ditemukan.</p>
        ';
    }
}

// ==================================================
// INITIALIZE ROUTER
// ==================================================

$router = new Router();
$currentPage = $router->getCurrentPage();
$pageTitle = $router->getPageTitle();

?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">

        <!-- ==================================================
             SIDEBAR
        ================================================== -->
        <aside class="w-64 bg-gray-900 shadow-xl fixed h-screen flex flex-col justify-between">
            <div>
                <!-- LOGO -->
                <div class="p-5 border-b border-gray-800">
                    <h1 class="text-xl font-bold text-yellow-400 flex items-center gap-2">
                        <i class="fas fa-cart-shopping"></i>
                        SIA KASIR
                    </h1>
                </div>

                <!-- MENU -->
                <nav class="p-4 space-y-2">
                    <!-- DASHBOARD -->
                    <a href="../dashboard" class="flex items-center gap-3 px-4 py-2 rounded-lg transition <?= $router->isActiveRoute('') ? 'bg-yellow-500 text-black' : 'text-gray-200 hover:bg-gray-800' ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- KASIR -->
                    <a href="kasir" class="flex items-center gap-3 px-4 py-2 rounded-lg transition <?= $router->isActiveRoute('kasir') ? 'bg-yellow-500 text-black' : 'text-gray-200 hover:bg-gray-800' ?>">
                        <i class="fas fa-print"></i>
                        <span>KASIR</span>
                    </a>

                    <!-- PRODUK -->
                    <a href="produk" class="flex items-center gap-3 px-4 py-2 rounded-lg transition <?= $router->isActiveRoute('produk') ? 'bg-yellow-500 text-black' : 'text-gray-200 hover:bg-gray-800' ?>">
                        <i class="fas fa-box"></i>
                        <span>Produk</span>
                    </a>

                    <!-- TRANSAKSI -->
                    <a href="transaksi" class="flex items-center gap-3 px-4 py-2 rounded-lg transition <?= $router->isActiveRoute('transaksi') ? 'bg-yellow-500 text-black' : 'text-gray-200 hover:bg-gray-800' ?>">
                        <i class="fas fa-box"></i>
                        <span>Transaksi</span>
                    </a>
                </nav>
            </div>

            <!-- LOGOUT -->
            <div class="p-4 border-t border-gray-800">
                <a href="../logout.php" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg flex items-center justify-center gap-2 transition">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </aside>

        <!-- ==================================================
             CONTENT
        ================================================== -->
        <main class="flex-1 ml-64 p-6 bg-gray-100 min-h-screen">
            <?php $router->renderContent(); ?>
        </main>

    </div>
</body>
</html>