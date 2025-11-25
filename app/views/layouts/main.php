<?php
// Check if user is logged in
if (!SessionManager::isLoggedIn()) {
    // Not logged in - redirect to login page
    header('Location: ' . BASE_URL . '/auth/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include('partials/title-meta.php');
    ?>

    <?php include('partials/head-css.php'); ?>
</head>
<!-- Session Configuration -->
<script>
    window.BASE_URL = '<?php echo BASE_URL; ?>';
    window.SESSION_CONFIG = <?php echo json_encode(SessionManager::getConfig()); ?>;
</script>
<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php
        include('partials/sidenav.php');
        ?>
        
        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <main class="py-4">
                <?php echo $content ?? ''; ?>
        </main>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <?php include('partials/customizer.php'); ?>
    
    <?php include('partials/footer-scripts.php'); ?>

    <?php if (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false): ?>
        <script src="<?php echo BASE_URL;?>/assets/vendor/apexcharts/apexcharts.min.js"></script>
        <script src="<?php echo BASE_URL;?>/assets/js/pages/dashboard.js"></script>
    <?php endif; ?>
    
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net/js/dataTables.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net-fixedcolumns-bs5/js/fixedColumns.bootstrap5.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/vendor/datatables.net-select/js/dataTables.select.min.js"></script>

    <!-- Datatable Demo js -->
    <script src="<?php echo BASE_URL; ?>/assets/js/components/table-datatable.js"></script>

<!-- Before closing body tag -->
<?php if (SessionManager::isLoggedIn()): ?>
    <script src="<?php echo BASE_URL; ?>/assets/js/session-manager.js"></script>
<?php endif; ?>
</body>

</html>