<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Apex Treemap Chart";
    include('partials/title-meta.php');
    ?>

    <?php include('partials/head-css.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php
        $title = "Treemap Charts";
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
                                <h4 class="header-title">Basic Treemap</h4>
                                <div dir="ltr">
                                    <div id="basic-treemap" class="apex-charts" data-colors="#39afd1"></div>
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
                                <h4 class="header-title">Treemap Multiple Series</h4>
                                <div dir="ltr">
                                    <div id="multiple-treemap" class="apex-charts" data-colors="#fa5c7c,#35b8e0"></div>
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
                                <h4 class="header-title">Distributed Treemap</h4>
                                <div dir="ltr">
                                    <div id="distributed-treemap" class="apex-charts"
                                        data-colors="#5b69bc,#10c469,#fa5c7c,#35b8e0,#39afd1,#ffc35a, #eef2f7, #313a46">
                                    </div>
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
                                <h4 class="header-title">Color Range Treemap</h4>
                                <div dir="ltr">
                                    <div id="color-range-treemap" class="apex-charts" data-colors="#5b69bc,#39afd1">
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

    <!-- Apex Chart Treemap Demo js -->
    <script src="assets/js/components/chart-apex-treemap.js"></script>
    <!-- demo end -->

</body>

</html>