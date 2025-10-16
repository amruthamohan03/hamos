<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Error 404";
    include('partials/title-meta.php');
    ?>

    <?php include('partials/head-css.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php
        $title = "404 Error";
        include('partials/sidenav.php');
        ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <div class="page-container">

                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="text-center">
                            <img src="assets/images/error/error-404.png" height="230" alt="File not found Image">

                            <h4 class="text-uppercase text-danger mt-3">Page Not Found</h4>
                            <p class="text-muted mt-3">It's looking like you may have taken a wrong turn. Don't worry...
                                it
                                happens to the best of us. Here's a
                                little tip that might help you get back on track.</p>

                            <a class="btn btn-info mt-3" href="index.php"><i class="ti ti-home me-1"></i> Return
                                Home</a>
                        </div> <!-- end /.text-center-->
                    </div> <!-- end col-->
                </div>
                <!-- end row -->

            </div> <!-- container -->

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