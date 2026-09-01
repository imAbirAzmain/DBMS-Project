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

<div class="toast-container position-fixed bottom-0 end-0 p-3" aria-live="polite" aria-atomic="true">
    <div class="toast app-toast" id="appToast" role="status" aria-live="polite" aria-atomic="true">
        <div class="d-flex align-items-center">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill" data-toast-icon aria-hidden="true"></i>
                <span data-toast-message>Action completed.</span>
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>js/script.js"></script>
</body>
</html>
