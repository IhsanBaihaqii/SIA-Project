<?php

// ==================================================
// AMBIL URL
// ==================================================

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$request = trim($request, '/');


// ==================================================
// CARI POSISI DASHBOARD
// ==================================================

$dashboardPath = 'dashboard';

$position = strpos($request, $dashboardPath);

if ($position !== false) {

    $request = substr(
        $request,
        $position + strlen($dashboardPath)
    );

}

$request = trim($request, '/');


// ==================================================
// ROUTING
// ==================================================

$routes = [

    '' => [
        'page' => 'dashboard'
    ],

    'produk' => [
        'page' => 'produk'
    ],

];


// ==================================================
// CEK ROUTE
// ==================================================

if (!isset($routes[$request])) {

    http_response_code(404);

    $currentPage = null;

    $pageTitle = '404';

} else {

    $currentPage = $routes[$request]['page'];

    $pageTitle = ucfirst(
        str_replace('_', ' ', $currentPage)
    );

}

?>

<!doctype html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($pageTitle) ?>
    </title>


    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>


    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

</head>


<body class="bg-gray-100">

<div class="flex min-h-screen">


    <!-- ==================================================
         SIDEBAR
    ================================================== -->

    <aside
        class="
            w-64
            bg-gray-900
            shadow-xl
            fixed
            h-screen
            flex
            flex-col
            justify-between
        "
    >

        <div>


            <!-- LOGO -->

            <div class="p-5 border-b border-gray-800">

                <h1
                    class="
                        text-xl
                        font-bold
                        text-yellow-400
                        flex
                        items-center
                        gap-2
                    "
                >

                    <i class="fas fa-box"></i>

                    Stok App

                </h1>

            </div>


            <!-- MENU -->

            <nav class="p-4 space-y-2">


                <!-- DASHBOARD -->

                <a
                    href="../dashboard"
                    class="
                        flex
                        items-center
                        gap-3
                        px-4
                        py-2
                        rounded-lg
                        transition

                        <?= $request === ''
                            ? 'bg-yellow-500 text-black'
                            : 'text-gray-200 hover:bg-gray-800'
                        ?>
                    "
                >

                    <i class="fas fa-home"></i>

                    <span>
                        Dashboard
                    </span>

                </a>


                <!-- PRODUK -->

                <a
                    href="produk"
                    class="
                        flex
                        items-center
                        gap-3
                        px-4
                        py-2
                        rounded-lg
                        transition

                        <?= $request === 'produk'
                            ? 'bg-yellow-500 text-black'
                            : 'text-gray-200 hover:bg-gray-800'
                        ?>
                    "
                >

                    <i class="fas fa-box"></i>

                    <span>
                        Produk
                    </span>

                </a>


            </nav>

        </div>


        <!-- LOGOUT -->

        <div class="p-4 border-t border-gray-800">

            <button
                class="
                    w-full
                    bg-red-500
                    hover:bg-red-600
                    text-white
                    py-2
                    rounded-lg
                    flex
                    items-center
                    justify-center
                    gap-2
                    transition
                "
            >

                <i class="fas fa-sign-out-alt"></i>

                Logout

            </button>

        </div>

    </aside>



    <!-- ==================================================
         CONTENT
    ================================================== -->

    <main
        class="
            flex-1
            ml-64
            p-6
            bg-gray-100
            min-h-screen
        "
    >

        <?php if ($currentPage): ?>

            <?php

            $file = __DIR__ . "/pages/{$currentPage}.php";

            if (file_exists($file)) {

                include $file;

            } else {

                echo "
                    <h2 class='text-2xl font-bold'>
                        Halaman tidak ditemukan
                    </h2>
                ";

            }

            ?>

        <?php else: ?>

            <h2 class="text-2xl font-bold text-gray-800">
                404
            </h2>

            <p class="text-gray-600 mt-2">
                Halaman tidak ditemukan.
            </p>

        <?php endif; ?>

    </main>

</div>

</body>

</html>