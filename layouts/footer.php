<footer class="bg-white border-t border-gray-200 py-4 text-center text-sm text-gray-500">
    <div class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2">
        <span class="flex items-center gap-1.5">
            <i class="fas fa-store text-blue-600 text-xs" aria-hidden="true"></i>
            <span class="font-medium text-gray-700">&copy; <?= date('Y') ?> SIA KASIR</span>
        </span>
        <span class="hidden sm:inline text-gray-300">•</span>
        <span>Sistem Informasi Kasir</span>
    </div>
</footer>

<script>
    // === Toggle sidebar mobile (terintegrasi dengan overlay) ===
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        if (typeof window.toggleSidebar === 'function') {
            window.toggleSidebar();
        } else {
            // fallback jika sidebar.php versi lama
            document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
        }
    });
</script>
</body>

</html>