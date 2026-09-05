<footer class="bg-white border-t border-gray-200 py-4 text-center text-sm text-gray-500">
    &copy; <?= date('Y') ?> SIA - Sistem Informasi Akuntansi. All rights reserved.
</footer>
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
    });
</script>
</body>
</html>