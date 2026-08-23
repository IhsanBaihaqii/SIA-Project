<!-- Header -->
<header
  class="bg-white border-b border-gray-200 px-6 md:px-8 py-4 flex items-center justify-between sticky top-0 z-10"
>
  <h1 class="text-2xl font-bold tracking-tight">Kasir</h1>
  <div class="flex items-center gap-5">
    <button class="relative text-gray-500 hover:text-gray-700 transition">
      <i class="fa-regular fa-bell text-xl"></i>
      <span
        id="notifDot"
        class="hidden absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"
      ></span>
    </button>
    <div class="flex items-center gap-3">
      <div
        class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white overflow-hidden"
      >
        <i class="fa-solid fa-user-tie"></i>
      </div>
      <span class="font-medium text-gray-700">Admin</span>
    </div>
  </div>
</header>

<!-- Main -->
<main class="p-4 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
  <!-- LEFT: Produk -->
  <section>
    <div class="relative mb-4">
      <i
        class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
      ></i>
      <input
        id="searchInput"
        type="text"
        placeholder="Cari produk....."
        class="w-full bg-gray-100 border border-gray-200 rounded-xl pl-11 pr-4 py-3.5 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition"
      />
    </div>
    <div id="productGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-4">
      <!-- injected by JS -->
    </div>
  </section>

  <!-- RIGHT: Keranjang -->
  <section class="flex flex-col gap-4">
    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-200">
        <h2 class="text-2xl font-bold">Keranjang</h2>
      </div>

      <div
        class="px-6 py-3 grid grid-cols-[1fr_60px_90px] gap-2 text-gray-700 font-semibold border-b border-gray-200"
      >
        <span>Produk</span>
        <span>Jumlah</span>
        <span class="text-right">Subtotal</span>
      </div>

      <div
        id="cartBody"
        class="max-h-72 overflow-y-auto divide-y divide-gray-200"
      >
        <!-- injected by JS -->
      </div>

      <div id="emptyCart" class="hidden px-6 py-10 text-center text-gray-400">
        <i class="fa-solid fa-cart-shopping text-3xl mb-2"></i>
        <p>Keranjang masih kosong</p>
      </div>

      <div
        class="px-6 py-5 border-t border-gray-200 flex items-center justify-between"
      >
        <span class="text-lg font-semibold">Total</span>
        <span id="totalAmount" class="text-lg font-bold">Rp0</span>
      </div>
    </div>

    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
      <h3 class="text-lg font-semibold mb-3">Uang Pembayaran</h3>
      <div class="relative mb-3">
        <span
          class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium"
          >Rp</span
        >
        <input
          id="paymentInput"
          type="text"
          inputmode="numeric"
          placeholder="0"
          class="w-full bg-white border border-gray-300 rounded-lg pl-11 pr-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
        />
      </div>

      <div class="flex items-center justify-between text-gray-600 mb-4">
        <span>Kembalian</span>
        <span id="changeAmount" class="font-semibold">Rp0</span>
      </div>

      <button
        id="payButton"
        class="w-full bg-blue-500 hover:bg-blue-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold py-3.5 rounded-lg transition flex items-center justify-center gap-2"
      >
        <i class="fa-solid fa-cash-register"></i>
        Bayar
      </button>
    </div>
  </section>
</main>

<script>
  (function () {
    // ----- DATA PRODUK -----
    const products = [
      {
        id: 1,
        name: "Air Mineral",
        price: 3000,
        icon: "fa-bottle-water",
        color: "text-sky-500",
      },
      {
        id: 2,
        name: "Minyak Goreng",
        price: 15000,
        icon: "fa-oil-can",
        color: "text-amber-500",
      },
      {
        id: 3,
        name: "Gula Pasir",
        price: 12000,
        icon: "fa-bowl-rice",
        color: "text-lime-600",
      },
      {
        id: 4,
        name: "Roti Tawar",
        price: 18000,
        icon: "fa-bread-slice",
        color: "text-orange-400",
      },
      {
        id: 5,
        name: "Kopi Sachet",
        price: 2000,
        icon: "fa-mug-saucer",
        color: "text-yellow-800",
      },
      {
        id: 6,
        name: "Telur Ayam",
        price: 28000,
        icon: "fa-egg",
        color: "text-yellow-500",
      },
    ];

    // ----- STATE KERANJANG (contoh awal) -----
    let cart = {
      2: { qty: 2 },
      5: { qty: 10 },
    };

    const rupiah = (n) => "Rp" + n.toLocaleString("id-ID");

    // ----- DOM refs -----
    const productGrid = document.getElementById("productGrid");
    const cartBody = document.getElementById("cartBody");
    const emptyCart = document.getElementById("emptyCart");
    const totalAmount = document.getElementById("totalAmount");
    const paymentInput = document.getElementById("paymentInput");
    const changeAmount = document.getElementById("changeAmount");
    const payButton = document.getElementById("payButton");
    const searchInput = document.getElementById("searchInput");

    // ----- RENDER PRODUK (dengan filter) -----
    function renderProducts(filter = "") {
      const filtered = products.filter((p) =>
        p.name.toLowerCase().includes(filter.toLowerCase()),
      );
      productGrid.innerHTML =
        filtered
          .map(
            (p) => `
        <button data-id="${p.id}" class="product-card text-left bg-gray-100 hover:bg-gray-200 rounded-xl p-4 transition">
          <div class="w-full aspect-square rounded-lg bg-white flex items-center justify-center mb-3">
            <i class="fa-solid ${p.icon} ${p.color} text-4xl"></i>
          </div>
          <p class="font-medium text-gray-800 leading-tight">${p.name}</p>
          <p class="text-gray-500 text-sm mt-1">${rupiah(p.price)}</p>
        </button>
      `,
          )
          .join("") ||
        `<p class="col-span-full text-center text-gray-400 py-10">Produk tidak ditemukan</p>`;
    }

    // ----- RENDER KERANJANG (dengan kontrol +/- dan input manual) -----
    function renderCart() {
      const entries = Object.entries(cart);
      if (entries.length === 0) {
        cartBody.innerHTML = "";
        emptyCart.classList.remove("hidden");
      } else {
        emptyCart.classList.add("hidden");
        cartBody.innerHTML = entries
          .map(([id, item]) => {
            const product = products.find((p) => p.id == id);
            if (!product) return "";
            const subtotal = product.price * item.qty;
            return `
          <div class="px-6 py-4 grid grid-cols-[1fr_60px_90px] gap-2 items-center" data-id="${id}">
            <span class="text-gray-800">${product.name}</span>
            <div class="cart-item-qty">
              <button class="qty-decr" data-id="${id}">−</button>
              <input type="text" inputmode="numeric" class="qty-input" data-id="${id}" value="${item.qty}" />
              <button class="qty-incr" data-id="${id}">+</button>
            </div>
            <div class="flex items-center justify-end gap-2">
              <span class="text-gray-800">${rupiah(subtotal)}</span>
              <button class="btn-remove" data-remove="${id}">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
          </div>`;
          })
          .join("");
      }
      updateTotals();
      // pasang event listener untuk qty (delegasi)
      attachQtyEvents();
    }

    // ----- FUNGSI QTY (delegasi event) -----
    function attachQtyEvents() {
      // kita pasang listener di cartBody untuk tombol +/-, dan input manual
      // tapi karena cartBody di-render ulang, kita pasang sekali di luar (gunakan event delegation)
    }

    // Event delegation untuk cartBody (+, -, input, remove)
    cartBody.addEventListener("click", function (e) {
      const target = e.target.closest("button");
      if (!target) return;

      // tombol hapus
      if (target.hasAttribute("data-remove")) {
        const id = target.dataset.remove;
        delete cart[id];
        renderCart();
        return;
      }

      // tombol increment / decrement
      const id = target.dataset.id;
      if (!id) return;
      const product = products.find((p) => p.id == id);
      if (!product) return;

      if (target.classList.contains("qty-incr")) {
        cart[id].qty = (cart[id].qty || 0) + 1;
        renderCart();
      } else if (target.classList.contains("qty-decr")) {
        const newQty = (cart[id].qty || 0) - 1;
        if (newQty <= 0) {
          delete cart[id];
        } else {
          cart[id].qty = newQty;
        }
        renderCart();
      }
    });

    // Event delegation untuk input qty (perubahan manual)
    cartBody.addEventListener("focusout", function (e) {
      const input = e.target.closest(".qty-input");
      if (!input) return;
      const id = input.dataset.id;
      if (!id) return;
      const product = products.find((p) => p.id == id);
      if (!product) return;

      let raw = input.value.replace(/\D/g, "");
      let newQty = parseInt(raw, 10);
      if (isNaN(newQty) || newQty < 1) {
        // jika invalid atau 0, hapus item
        delete cart[id];
      } else {
        cart[id].qty = newQty;
      }
      renderCart();
    });

    // juga tangani input keydown Enter agar langsung apply
    cartBody.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        const input = e.target.closest(".qty-input");
        if (input) {
          input.blur(); // trigger focusout
        }
      }
    });

    // ----- TOTAL & UPDATE -----
    function getTotal() {
      return Object.entries(cart).reduce((sum, [id, item]) => {
        const product = products.find((p) => p.id == id);
        return sum + (product ? product.price * item.qty : 0);
      }, 0);
    }

    function updateTotals() {
      const total = getTotal();
      totalAmount.textContent = rupiah(total);

      const paid = parseInt(paymentInput.value.replace(/\D/g, "")) || 0;
      const change = paid - total;
      changeAmount.textContent = rupiah(Math.max(change, 0));
      changeAmount.classList.toggle("text-red-500", change < 0);
      changeAmount.classList.toggle("text-green-600", change >= 0 && total > 0);

      payButton.disabled = total === 0 || paid < total;
    }

    // ----- TAMBAH PRODUK (klik card) -----
    productGrid.addEventListener("click", (e) => {
      const card = e.target.closest("[data-id]");
      if (!card) return;
      const id = card.dataset.id;
      if (cart[id]) {
        cart[id].qty += 1;
      } else {
        cart[id] = { qty: 1 };
      }
      renderCart();
    });

    // ----- SEARCH -----
    searchInput.addEventListener("input", (e) =>
      renderProducts(e.target.value),
    );

    // ----- PAYMENT INPUT (format rupiah & update) -----
    paymentInput.addEventListener("input", (e) => {
      let val = e.target.value.replace(/\D/g, "");
      e.target.value = val ? parseInt(val).toLocaleString("id-ID") : "";
      updateTotals();
    });

    // ----- PAY BUTTON -----
    payButton.addEventListener("click", () => {
      if (payButton.disabled) return;
      alert("Pembayaran berhasil!");
      cart = {};
      paymentInput.value = "";
      renderCart();
    });

    // ----- INIT -----
    renderProducts();
    renderCart();
  })();
</script>
