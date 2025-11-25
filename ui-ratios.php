<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Ratio Video";
    include('partials/title-meta.php');
    ?>


    <?php include('partials/head-css.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php
        $title = "Ratio Video";
        include('partials/sidenav.php');
        ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <div class="page-container">

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                <h4 class="header-title">Responsive Ratio video 21:9</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-muted">Use class <code>.ratio-21x9</code>
                                    <!-- 21:9 aspect ratio -->
                                <div class="ratio ratio-21x9">
                                    <iframe src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0"></iframe>
                                </div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->

                        <div class="card">
                            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                <h4 class="header-title">Responsive Ratio video 1:1</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-muted">Use class <code>.ratio-1x1</code></p>
                                <!-- 1:1 aspect ratio -->
                                <div class="ratio ratio-1x1">
                                    <iframe src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0"></iframe>
                                </div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col -->

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                <h4 class="header-title">Responsive Ratio video 16:9</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-muted">Use class <code>.ratio-16x9</code>
                                    <!-- 16:9 aspect ratio -->
                                <div class="ratio ratio-16x9">
                                    <iframe src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0"></iframe>
                                </div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->

                        <div class="card">
                            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                <h4 class="header-title">Responsive Ratio video 4:3</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-muted">Use class <code>.ratio-4x3</code>
                                    <!-- 4:3 aspect ratio -->
                                <div class="ratio ratio-4x3">
                                    <iframe src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0"></iframe>
                                </div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
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