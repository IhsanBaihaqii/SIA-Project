<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hello World · smooth</title>
  <!-- Tailwind via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* reset & base */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: #f2f6fc; /* solid, soft blue-gray */
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 1.5rem;
    }
    .card {
      background: #ffffff;
      border-radius: 2.5rem;
      box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.12), 0 8px 24px -6px rgba(0, 0, 0, 0.02);
      padding: 3rem 4rem;
      transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease;
      border: 1px solid rgba(226, 232, 240, 0.6);
      text-align: center;
      will-change: transform;
    }
    .card:hover {
      transform: scale(1.01) translateY(-4px);
      box-shadow: 0 30px 50px -16px rgba(0, 0, 0, 0.18);
    }

    .hello-world {
      font-size: 5.5rem;
      font-weight: 700;
      letter-spacing: -0.025em;
      line-height: 1.2;
      color: #1a202c;
      transition: color 0.4s ease, transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
      display: inline-block;
      will-change: transform, color;
    }
    .hello-world .world {
      color: #2563eb;
      transition: color 0.4s ease, transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
      display: inline-block;
    }
    .card:hover .hello-world {
      transform: scale(1.02);
      color: #0f172a;
    }
    .card:hover .hello-world .world {
      color: #1d4ed8;
      transform: scale(1.04) rotate(-0.5deg);
    }

    .divider {
      width: 60px;
      height: 4px;
      background: #2563eb;
      border-radius: 12px;
      margin: 1.5rem auto 0 auto;
      transition: width 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), background 0.4s ease;
    }
    .card:hover .divider {
      width: 100px;
      background: #1d4ed8;
    }

    .dot-ring {
      display: flex;
      justify-content: center;
      gap: 0.6rem;
      margin-top: 1.8rem;
    }
    .dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #e2e8f0;
      transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .dot:nth-child(1) { background: #2563eb; }
    .dot:nth-child(2) { background: #475569; }
    .dot:nth-child(3) { background: #94a3b8; }
    .card:hover .dot:nth-child(1) {
      transform: translateY(-6px) scale(1.2);
      background: #1d4ed8;
    }
    .card:hover .dot:nth-child(2) {
      transform: translateY(-3px) scale(1.1);
      background: #1e293b;
    }
    .card:hover .dot:nth-child(3) {
      transform: translateY(-8px) scale(1.3);
      background: #64748b;
    }

    @keyframes softPulse {
      0% { opacity: 1; }
      50% { opacity: 0.7; }
      100% { opacity: 1; }
    }
    .card {
      animation: softPulse 4s infinite ease-in-out;
    }
    .card:hover {
      animation-play-state: paused;
    }

    /* LINK LOGIN - tambahan baru */
    .login-link-wrapper {
      margin-top: 2rem;
      padding-top: 1.5rem;
      border-top: 1px solid #e2e8f0;
    }
    .login-link {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: #2563eb;
      color: white;
      padding: 0.6rem 1.8rem;
      border-radius: 9999px;
      font-weight: 500;
      font-size: 0.95rem;
      text-decoration: none;
      transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
      border: 1px solid transparent;
    }
    .login-link:hover {
      background: #1d4ed8;
      transform: scale(1.05) translateY(-2px);
      box-shadow: 0 8px 20px -8px rgba(37, 99, 235, 0.4);
    }
    .login-link i {
      font-size: 1rem;
    }
    .login-link .arrow {
      transition: transform 0.3s ease;
      display: inline-block;
    }
    .login-link:hover .arrow {
      transform: translateX(4px);
    }
    .login-desc {
      font-size: 0.85rem;
      color: #64748b;
      margin-top: 0.6rem;
      letter-spacing: 0.3px;
    }
    .login-desc span {
      background: #f1f5f9;
      padding: 0.1rem 0.6rem;
      border-radius: 6px;
      font-family: monospace;
      color: #2563eb;
      font-weight: 600;
    }

    /* responsif */
    @media (max-width: 480px) {
      .card {
        padding: 2.5rem 1.8rem;
        border-radius: 2rem;
      }
      .hello-world {
        font-size: 3.8rem;
      }
      .divider {
        width: 48px;
      }
      .card:hover .divider {
        width: 72px;
      }
      .login-link {
        font-size: 0.85rem;
        padding: 0.5rem 1.4rem;
      }
    }
  </style>
</head>
<body>

  <div class="card">
    <div class="hello-world">
      Hello <span class="world">World</span>
    </div>
    <div class="divider"></div>
    <div class="dot-ring">
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
    </div>

    <!-- TAMBAHAN: Link ke Login dengan keterangan -->
    <div class="login-link-wrapper">
      <a href="login" class="login-link">
        Tampilan halaman Login <span class="arrow">→</span>
      </a>
    </div>
  </div>

</body>
</html>