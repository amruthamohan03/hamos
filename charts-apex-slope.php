<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Slope";
    include('partials/title-meta.php');
    ?>

    <?php include('partials/head-css.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php include('partials/sidenav.php'); ?>


        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <?php
            $subtitle = "Apex";
            $title = "Slope Charts";
            include('partials/page-title.php');
            ?>

            <div class="page-container">

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-4">Basic Slope Chart</h4>
                                <div dir="ltr">
                                    <div id="slope-basic" class="apex-charts" data-colors="#39afd1"></div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col-->

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-4">Multiple Slop Chart</h4>
                                <div dir="ltr">
                                    <div id="slope-mluti-group" class="apex-charts"
                                        data-colors="#45bbe0,#f9bc0b,#777edd,#0acf97"></div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col-->
                </div>
                <!-- end row-->

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

    <!-- Apex Chart js -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

    <!-- Apex Chart Slope Demo js -->
    <script src="assets/js/components/chart-apex-slope.js"></script>

</body>

</html>