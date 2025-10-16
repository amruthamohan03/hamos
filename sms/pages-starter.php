<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Starter Page";
    include('partials/title-meta.php');
    ?>


    <?php include('partials/head-css.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php
        $title = "Starter";
        include('partials/sidenav.php');
        ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="page-content">

            <div class="page-container">


            </div>
            <!-- container -->

            <?php include('partials/footer.php'); ?>

        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <?php include('partials/customizer.php'); ?>

    <?php include('partials/footer-scripts.php'); ?>

</body>

</html>