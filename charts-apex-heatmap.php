<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Apex Heatmap Chart";
    include('partials/title-meta.php');
    ?>

    <?php include('partials/head-css.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php
        $title = "Heatmap Charts";
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
                            <div class="card-body">
                                <h4 class="header-title">Basic Heatmap - Single Series</h4>
                                <div dir="ltr">
                                    <div id="basic-heatmap" class="apex-charts" data-colors="#5b69bc"></div>
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
                                <h4 class="header-title">Heatmap - Multiple Series</h4>
                                <div dir="ltr">
                                    <div id="multiple-series-heatmap" class="apex-charts"
                                        data-colors="#F3B415,#F27036,#663F59,#6A6E94,#4E88B4,#00A7C6,#18D8D8,#A9D794,#46AF78">
                                    </div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col-->
                </div>
                <!-- end row-->

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-3">Heatmap - Color Range</h4>
                                <div dir="ltr">
                                    <div id="color-range-heatmap" class="apex-charts"
                                        data-colors="#fa5c7c,#f9c851,#39afd1,#10c469"></div>
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
                                <h4 class="header-title mb-3">Heatmap - Range without Shades</h4>
                                <div dir="ltr">
                                    <div id="rounded-heatmap" class="apex-charts" data-colors="#10c469,#f9c851"></div>
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

    <!-- Apex Chart Heatmap Demo js -->
    <script src="assets/js/components/chart-apex-heatmap.js"></script>

</body>

</html>