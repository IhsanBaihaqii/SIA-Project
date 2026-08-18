<?php
include 'config/koneksi.php';

  session_start();
  if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
  }
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - SIA KASIR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <style>
      /* sentuhan halus */
      body {
        font-family:
          "Inter",
          system-ui,
          -apple-system,
          sans-serif;
        background: #f8fafc;
      }
      .login-card {
        background: #ffffff;
        border-radius: 2rem;
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
      }
      .left-panel {
        background: linear-gradient(145deg, #f1f5f9 0%, #e9eef3 100%);
        padding: 2.5rem 1.5rem;
      }
      .right-panel {
        background: #ffffff;
        padding: 2.5rem 2rem;
      }
      .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
        transition: color 0.2s;
      }
      .input-field {
        width: 100%;
        padding: 0.8rem 1rem 0.8rem 2.8rem;
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        color: #1e293b;
      }
      .input-field:focus {
        outline: none;
        background: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
      }
      .input-field:focus ~ .input-icon,
      .input-wrapper:focus-within .input-icon {
        color: #6366f1;
      }
      .input-field::placeholder {
        color: #94a3b8;
        font-weight: 400;
      }
      .btn-login {
        background: #1e293b;
        border: none;
        padding: 0.8rem;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 1rem;
        color: white;
        transition: all 0.2s ease;
        width: 100%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
      }
      .btn-login:hover {
        background: #0f172a;
        transform: scale(1.01);
        box-shadow: 0 8px 20px -8px rgba(15, 23, 42, 0.25);
      }
      .btn-login:active {
        transform: scale(0.97);
      }
      .error-msg {
        background: #fef2f2;
        border-radius: 1rem;
        padding: 0.7rem 1.2rem;
        color: #b91c1c;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.7rem;
        border-left: 4px solid #dc2626;
      }
      .error-msg i {
        color: #dc2626;
        font-size: 1.2rem;
      }
      .checkbox-custom {
        width: 1.1rem;
        height: 1.1rem;
        accent-color: #6366f1;
        border-radius: 0.3rem;
        cursor: pointer;
      }
      .link-forgot {
        color: #6366f1;
        font-weight: 500;
        transition: color 0.2s;
        text-decoration: none;
      }
      .link-forgot:hover {
        color: #4338ca;
        text-decoration: underline;
      }
      .brand-icon {
        background: rgba(99, 102, 241, 0.08);
        width: 100px;
        height: 100px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
      }
      .brand-icon i {
        font-size: 3.8rem;
        color: #6366f1;
      }
      /* responsif */
      @media (max-width: 768px) {
        .left-panel {
          padding: 2rem 1rem;
          min-height: 180px;
        }
        .brand-icon {
          width: 80px;
          height: 80px;
        }
        .brand-icon i {
          font-size: 3rem;
        }
        .right-panel {
          padding: 1.8rem 1.2rem;
        }
      }
    </style>
  </head>
  <body>
    <div class="min-h-screen flex items-center justify-center p-4">
      <div class="login-card w-full max-w-4xl grid grid-cols-1 md:grid-cols-5">
        <!-- LEFT PANEL: Brand -->
        <div
          class="left-panel md:col-span-2 flex flex-col items-center justify-center text-center"
        >
          <div class="brand-icon">
            <i class="fa-solid fa-cart-shopping"></i>
          </div>
          <h1 class="text-3xl font-extrabold text-gray-800 tracking-wide">
            SIA KASIR
          </h1>
          <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
            <i class="fa-regular fa-circle-check text-indigo-400 text-xs"></i>
            Sistem Informasi Kasir
          </p>
          <div class="mt-4 flex items-center gap-2 text-gray-400/40 text-xs">
            <span class="w-8 h-px bg-gray-300/40"></span>
            <span>v2.0</span>
            <span class="w-8 h-px bg-gray-300/40"></span>
          </div>
        </div>

        <!-- RIGHT PANEL: Form -->
        <div class="right-panel md:col-span-3">
          <div class="flex items-center gap-2 mb-1">
            <i
              class="fa-solid fa-arrow-right-to-bracket text-indigo-600 text-xl"
            ></i>
            <h2 class="text-2xl font-extrabold text-gray-800">Login</h2>
          </div>
          <p class="text-gray-400 text-sm mb-6">Masuk untuk melanjutkan</p>

          <form
            onsubmit="
              event.preventDefault();
              showError();
            "
          >
            <!-- Email / Username -->
            <div class="mb-5">
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Email / Username
              </label>
              <div class="input-wrapper relative">
                <input
                  type="text"
                  class="input-field"
                  placeholder="Masuk email untuk username"
                  value="kasir@toko.com"
                />
                <i class="fa-solid fa-user input-icon"></i>
              </div>
            </div>

            <!-- Password -->
            <div class="mb-4">
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Password
              </label>
              <div class="input-wrapper relative">
                <input
                  type="password"
                  class="input-field"
                  placeholder="Masuk password"
                  value="rahasia123"
                />
                <i class="fa-solid fa-lock input-icon"></i>
              </div>
            </div>

            <!-- Remember & Forgot -->
            <div
              class="flex flex-wrap items-center justify-between gap-3 mt-4 mb-6"
            >
              <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" class="checkbox-custom" checked />
                <span class="text-sm font-medium text-gray-700">
                  Ingat saya
                </span>
              </label>
              <a href="#" class="link-forgot text-sm flex items-center gap-1">
                <i class="fa-solid fa-key text-[0.65rem]"></i> Lupa password ?
              </a>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-login">
              <i class="fa-solid fa-arrow-right-to-bracket"></i> Login
            </button>

            <!-- Error message (muncul setelah klik) -->
            <div id="errorMessage" class="error-msg hidden mt-5">
              <i class="fa-solid fa-circle-exclamation"></i>
              <span>Email atau password salah!</span>
            </div>

            <!-- footer kecil -->
            <p
              class="text-center text-gray-400 text-[0.7rem] mt-6 pt-4 border-t border-gray-100 flex items-center justify-center gap-2"
            >
              <i class="fa-regular fa-copyright"></i> 2026 • SIA KASIR
            </p>
          </form>
        </div>
      </div>
    </div>

    <script>
      function showError() {
        const err = document.getElementById("errorMessage");
        if (err) {
          err.classList.remove("hidden");
          err.style.opacity = "0";
          setTimeout(() => (err.style.opacity = "1"), 20);
        }
      }

      document
        .querySelector(".link-forgot")
        ?.addEventListener("click", function (e) {
          e.preventDefault();
          alert("🔐 Demo: reset password akan dikirim ke email terdaftar.");
        });
    </script>
  </body>
</html>
