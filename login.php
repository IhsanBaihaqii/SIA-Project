<?php
session_start();
include 'config/koneksi.php';

if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
  header("Location: dashboard/");
  exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $password = $_POST["password"] ?? "";

  if ($username === "" || $password === "") {
    $error = "Username dan password wajib diisi!";
  } else {
    $query = "SELECT * FROM tbl_user WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute(["username" => $username]);
    $user = $stmt->fetch();

    if (!$user) {
      $error = "Username atau password salah!";
    } elseif ($password === $user["password"]) {
      $_SESSION["login"] = true;
      $_SESSION["id_user"] = $user["id"];
      $_SESSION["username"] = $user["username"];
      $_SESSION["role"] = $user["role"];

      session_regenerate_id(true);
      header("Location: dashboard/");
      exit;
    } else {
      $error = "Username atau password salah!";
    }
  }
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - KASIR</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    * {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    body {
      background-color: #eef2ff;
      /* solid indigo-50 */
      min-height: 100vh;
    }

    /* === Card === */
    .login-card {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      box-shadow: 0 20px 40px -15px rgba(55, 48, 163, 0.18);
      border-radius: 1.25rem;
      overflow: hidden;
    }

    /* === Left panel (solid indigo) === */
    .left-panel {
      background-color: #4f46e5;
      /* solid indigo-600 */
      position: relative;
      overflow: hidden;
    }

    .left-panel::before {
      content: '';
      position: absolute;
      width: 180px;
      height: 180px;
      background: #6366f1;
      /* indigo-500 */
      border-radius: 50%;
      top: -60px;
      right: -60px;
      opacity: 0.7;
    }

    .left-panel::after {
      content: '';
      position: absolute;
      width: 130px;
      height: 130px;
      background: #4338ca;
      /* indigo-700 */
      border-radius: 50%;
      bottom: -40px;
      left: -40px;
      opacity: 0.7;
    }

    .left-panel>* {
      position: relative;
      z-index: 2;
    }

    .brand-icon {
      width: 80px;
      height: 80px;
      background-color: #4338ca;
      /* solid indigo-700 */
      border-radius: 1.1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 2rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      transition: transform 0.35s ease;
    }

    .brand-icon:hover {
      transform: rotate(-8deg) scale(1.05);
    }

    /* === Input === */
    .input-wrapper {
      position: relative;
    }

    .input-field {
      width: 100%;
      padding: 0.8rem 1rem 0.8rem 2.75rem;
      border-radius: 0.6rem;
      border: 1.5px solid #d1d5db;
      background: #f9fafb;
      font-size: 0.9rem;
      color: #1f2937;
      transition: all 0.2s ease;
      outline: none;
    }

    .input-field:focus {
      border-color: #4f46e5;
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
    }

    .input-field::placeholder {
      color: #9ca3af;
    }

    .input-icon {
      position: absolute;
      left: 0.95rem;
      top: 50%;
      transform: translateY(-50%);
      color: #9ca3af;
      font-size: 0.9rem;
      transition: color 0.2s;
      pointer-events: none;
    }

    .input-wrapper:focus-within .input-icon {
      color: #4f46e5;
    }

    .toggle-pass {
      position: absolute;
      right: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      color: #9ca3af;
      cursor: pointer;
      padding: 0.35rem 0.5rem;
      border-radius: 0.4rem;
      transition: all 0.2s;
    }

    .toggle-pass:hover {
      color: #4f46e5;
      background: #eef2ff;
    }

    /* === Checkbox === */
    .checkbox-custom {
      appearance: none;
      width: 1.1rem;
      height: 1.1rem;
      border: 1.5px solid #d1d5db;
      border-radius: 0.3rem;
      background: #ffffff;
      cursor: pointer;
      position: relative;
      transition: all 0.2s;
      flex-shrink: 0;
    }

    .checkbox-custom:checked {
      background-color: #4f46e5;
      border-color: #4f46e5;
    }

    .checkbox-custom:checked::after {
      content: '\2713';
      position: absolute;
      color: white;
      font-size: 0.75rem;
      font-weight: bold;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    /* === Links === */
    .link-forgot {
      color: #4f46e5;
      font-weight: 500;
      transition: color 0.2s;
      text-decoration: none;
    }

    .link-forgot:hover {
      color: #3730a3;
      text-decoration: underline;
    }

    /* === Button (solid) === */
    .btn-login {
      width: 100%;
      padding: 0.85rem;
      border-radius: 0.6rem;
      background-color: #4f46e5;
      /* solid */
      color: white;
      font-weight: 600;
      font-size: 0.95rem;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 6px 16px -4px rgba(79, 70, 229, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-login:hover {
      background-color: #4338ca;
      /* indigo-700 */
      transform: translateY(-2px);
      box-shadow: 0 10px 22px -6px rgba(79, 70, 229, 0.6);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .btn-login.loading {
      pointer-events: none;
      opacity: 0.9;
    }

    /* === Error message === */
    .error-msg {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.75rem 1rem;
      background-color: #fef2f2;
      border: 1px solid #fecaca;
      border-left: 4px solid #ef4444;
      border-radius: 0.6rem;
      color: #b91c1c;
      font-size: 0.85rem;
      font-weight: 500;
      animation: shake 0.45s ease, fadeIn 0.35s ease;
    }

    @keyframes shake {

      0%,
      100% {
        transform: translateX(0);
      }

      20%,
      60% {
        transform: translateX(-6px);
      }

      40%,
      80% {
        transform: translateX(6px);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-6px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* === Entry animation === */
    .fade-up {
      animation: fadeUp 0.5s ease both;
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(12px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="login-card w-full max-w-4xl grid grid-cols-1 md:grid-cols-5 fade-up">

      <!-- LEFT PANEL: Brand -->
      <div class="left-panel md:col-span-2 flex flex-col items-center justify-center text-center p-8 md:p-10">
        <div class="brand-icon mb-5">
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-wide">SISTEM KASIR</h1>
        <p class="text-indigo-100 text-sm mt-2 flex items-center gap-1.5">
          <i class="fa-regular fa-circle-check"></i>
          Toko ATK Maju Jaya
        </p>
        <div class="mt-6 flex items-center gap-2 text-indigo-300 text-xs">
        </div>
      </div>

      <!-- RIGHT PANEL: Form -->
      <div class="md:col-span-3 p-8 md:p-10">
        <div class="flex items-center gap-2 mb-1">
          <i class="fa-solid fa-arrow-right-to-bracket text-indigo-600 text-xl"></i>
          <h2 class="text-2xl font-extrabold text-gray-800">Login</h2>
        </div>
        <p class="text-gray-400 text-sm mb-6">Masuk untuk melanjutkan</p>

        <form method="POST" action="" id="loginForm">
          <!-- Username -->
          <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email / Username</label>
            <div class="input-wrapper">
              <input
                type="text"
                name="username"
                class="input-field"
                placeholder="Masukkan username"
                autocomplete="username"
                required />
              <i class="fa-solid fa-user input-icon"></i>
            </div>
          </div>

          <!-- Password -->
          <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
            <div class="input-wrapper">
              <input
                type="password"
                name="password"
                id="password"
                class="input-field"
                style="padding-right:2.75rem;"
                placeholder="Masukkan password"
                autocomplete="current-password"
                required />
              <i class="fa-solid fa-lock input-icon"></i>
              <span class="toggle-pass" id="togglePass" title="Tampilkan password">
                <i class="fa-solid fa-eye"></i>
              </span>
            </div>
          </div>

          <!-- Remember & Forgot -->
          <div class="flex flex-wrap items-center justify-between gap-3 mt-4 mb-6">
            <label class="flex items-center gap-2 cursor-pointer select-none">
              <input type="checkbox" class="checkbox-custom" checked />
              <span class="text-sm font-medium text-gray-700">Ingat saya</span>
            </label>
            <a href="#" class="link-forgot text-sm flex items-center gap-1">
              <i class="fa-solid fa-key text-[0.65rem]"></i> Lupa password?
            </a>
          </div>

          <!-- Submit -->
          <button type="submit" class="btn-login" id="btnLogin">
            <i class="fa-solid fa-arrow-right-to-bracket"></i>
            <span>Login</span>
          </button>

          <!-- Error message -->
          <?php if ($error !== ""): ?>
            <div class="error-msg mt-5">
              <i class="fa-solid fa-circle-exclamation"></i>
              <span><?= htmlspecialchars($error) ?></span>
            </div>
          <?php endif; ?>

          <!-- Footer -->
          <p class="text-center text-gray-400 text-[0.7rem] mt-6 pt-4 border-t border-gray-100 flex items-center justify-center gap-2">
            <i class="fa-regular fa-copyright"></i>Toko ATK Maju Jaya
          </p>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Toggle password visibility
    const togglePass = document.getElementById('togglePass');
    const passwordInput = document.getElementById('password');
    togglePass?.addEventListener('click', () => {
      const isPass = passwordInput.type === 'password';
      passwordInput.type = isPass ? 'text' : 'password';
      togglePass.innerHTML = isPass ?
        '<i class="fa-solid fa-eye-slash"></i>' :
        '<i class="fa-solid fa-eye"></i>';
    });

    // Forgot password (demo)
    document.querySelector('.link-forgot')?.addEventListener('click', (e) => {
      e.preventDefault();
      alert('🔐 Demo: reset password akan dikirim ke email terdaftar.');
    });

    // Loading state saat submit
    document.getElementById('loginForm')?.addEventListener('submit', () => {
      const btn = document.getElementById('btnLogin');
      btn.classList.add('loading');
      btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i><span>Memproses...</span>';
    });
  </script>
</body>

</html>