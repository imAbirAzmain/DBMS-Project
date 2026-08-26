<?php
/** Shared footer and application script loading. */
$assetBase = isset($assetBase) && is_string($assetBase) ? $assetBase : '../assets/';
?>
    <footer class="app-footer">
        <p>© <?= date('Y'); ?> Garments Management System</p>
        <p>Frontend prototype · Dummy data only</p>
    </footer>
</div><!-- /.app-content -->
</div><!-- /.app-shell -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>js/script.js"></script>
</body>
</html>
