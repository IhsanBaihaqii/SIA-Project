

  <main class="p-4 md:p-8">

    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-6">
      <div class="relative flex-1 max-w-md">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input id="searchInput" type="text" placeholder="Cari produk....."
          class="w-full bg-gray-100 border border-gray-200 rounded-xl pl-11 pr-4 py-3.5 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:bg-white transition">
      </div>
      <button id="addBtn" class="bg-purple-700 hover:bg-purple-800 text-white font-semibold px-6 py-3.5 rounded-xl flex items-center justify-center gap-2 transition whitespace-nowrap">
        <i class="fa-solid fa-plus"></i>
        Tambah Produk
      </button>
    </div>

    <!-- Table -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-x-auto">
      <table class="w-full min-w-[720px] text-left">
        <thead>
          <tr class="text-gray-700 font-semibold border-b border-gray-200">
            <th class="px-6 py-4">ID Produk</th>
            <th class="px-6 py-4">Nama Produk</th>
            <th class="px-6 py-4">Harga</th>
            <th class="px-6 py-4">Stok</th>
            <th class="px-6 py-4">Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody" class="divide-y divide-gray-200">
          <!-- injected by JS -->
        </tbody>
      </table>
      <div id="emptyState" class="hidden text-center text-gray-400 py-14">
        <i class="fa-solid fa-box-open text-3xl mb-2"></i>
        <p>Produk tidak ditemukan</p>
      </div>
    </div>
  </main>

  <!-- Modal -->
  <div id="modalOverlay" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6">
      <div class="flex items-center justify-between mb-5">
        <h2 id="modalTitle" class="text-xl font-bold">Tambah Produk</h2>
        <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition">
          <i class="fa-solid fa-xmark text-xl"></i>
        </button>
      </div>

      <form id="productForm" class="flex flex-col gap-4">
        <input type="hidden" id="editId">

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Nama Produk</label>
          <input id="nameInput" type="text" required
            class="w-full bg-gray-100 border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-400 transition">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Harga</label>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
            <input id="priceInput" type="text" inputmode="numeric" required
              class="w-full bg-gray-100 border border-gray-200 rounded-lg pl-11 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-400 transition">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Stok</label>
          <input id="stockInput" type="number" min="0" required
            class="w-full bg-gray-100 border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-400 transition">
        </div>

        <div class="flex gap-3 mt-2">
          <button type="button" id="cancelBtn" class="flex-1 border border-gray-300 text-gray-600 font-semibold py-2.5 rounded-lg hover:bg-gray-50 transition">Batal</button>
          <button type="submit" class="flex-1 bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2.5 rounded-lg transition">Simpan</button>
        </div>
      </form>
    </div>
  </div>

<script>
  let products = [
    { id: "RBX001", name: "Air Mineral",   price: 3000,  stock: 50 },
    { id: "RBX002", name: "Minyak Goreng", price: 15000, stock: 43 },
    { id: "RBX003", name: "Gula Pasir",    price: 12000, stock: 12 },
    { id: "RBX004", name: "Roti Tawar",    price: 18000, stock: 40 },
  ];

  let nextNum = 5;
  const rupiah = (n) => "Rp " + n.toLocaleString("id-ID");

  const tableBody = document.getElementById("tableBody");
  const emptyState = document.getElementById("emptyState");
  const searchInput = document.getElementById("searchInput");

  const modalOverlay = document.getElementById("modalOverlay");
  const modalTitle = document.getElementById("modalTitle");
  const productForm = document.getElementById("productForm");
  const editId = document.getElementById("editId");
  const nameInput = document.getElementById("nameInput");
  const priceInput = document.getElementById("priceInput");
  const stockInput = document.getElementById("stockInput");

  function renderTable(filter = "") {
    const filtered = products.filter(p => p.name.toLowerCase().includes(filter.toLowerCase()));

    if (filtered.length === 0) {
      tableBody.innerHTML = "";
      emptyState.classList.remove("hidden");
      return;
    }
    emptyState.classList.add("hidden");

    tableBody.innerHTML = filtered.map(p => `
      <tr>
        <td class="px-6 py-5 font-semibold">${p.id}</td>
        <td class="px-6 py-5">${p.name}</td>
        <td class="px-6 py-5">${rupiah(p.price)}</td>
        <td class="px-6 py-5">${p.stock}</td>
        <td class="px-6 py-5">
          <div class="flex items-center gap-4">
            <button data-edit="${p.id}" class="text-blue-500 hover:text-blue-700 transition">
              <i class="fa-solid fa-pen"></i>
            </button>
            <button data-delete="${p.id}" class="text-red-500 hover:text-red-700 transition">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>
    `).join("");
  }

  function openModal(mode, product = null) {
    modalOverlay.classList.remove("hidden");
    if (mode === "edit") {
      modalTitle.textContent = "Edit Produk";
      editId.value = product.id;
      nameInput.value = product.name;
      priceInput.value = product.price.toLocaleString("id-ID");
      stockInput.value = product.stock;
    } else {
      modalTitle.textContent = "Tambah Produk";
      editId.value = "";
      productForm.reset();
    }
  }

  function closeModalFn() {
    modalOverlay.classList.add("hidden");
    productForm.reset();
  }

  document.getElementById("addBtn").addEventListener("click", () => openModal("add"));
  document.getElementById("closeModal").addEventListener("click", closeModalFn);
  document.getElementById("cancelBtn").addEventListener("click", closeModalFn);
  modalOverlay.addEventListener("click", (e) => { if (e.target === modalOverlay) closeModalFn(); });

  priceInput.addEventListener("input", (e) => {
    let val = e.target.value.replace(/\D/g, "");
    e.target.value = val ? parseInt(val).toLocaleString("id-ID") : "";
  });

  productForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const price = parseInt(priceInput.value.replace(/\D/g, "")) || 0;
    const stock = parseInt(stockInput.value) || 0;

    if (editId.value) {
      const product = products.find(p => p.id === editId.value);
      product.name = nameInput.value;
      product.price = price;
      product.stock = stock;
    } else {
      const id = "RBX" + String(nextNum++).padStart(3, "0");
      products.push({ id, name: nameInput.value, price, stock });
    }
    closeModalFn();
    renderTable(searchInput.value);
  });

  tableBody.addEventListener("click", (e) => {
    const editBtn = e.target.closest("[data-edit]");
    const delBtn = e.target.closest("[data-delete]");
    if (editBtn) {
      const product = products.find(p => p.id === editBtn.dataset.edit);
      openModal("edit", product);
    }
    if (delBtn) {
      if (confirm("Hapus produk ini?")) {
        products = products.filter(p => p.id !== delBtn.dataset.delete);
        renderTable(searchInput.value);
      }
    }
  });

  searchInput.addEventListener("input", (e) => renderTable(e.target.value));

  renderTable();
</script>

