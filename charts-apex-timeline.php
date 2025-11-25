<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Apex Timeline Chart";
    include('partials/title-meta.php');
    ?>

    <?php include('partials/head-css.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php
        $title = "Timeline Charts";
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
                                <h4 class="header-title mb-3">Basic Timeline</h4>
                                <div dir="ltr">
                                    <div id="basic-timeline" class="apex-charts" data-colors="#ff5b5b"></div>
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
                                <h4 class="header-title mb-3">Distributed Timeline </h4>
                                <div dir="ltr">
                                    <div id="distributed-timeline" class="apex-charts"
                                        data-colors="#5b69bc,#10c469,#fa5c7c,#35b8e0,#39afd1"></div>
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
                                <h4 class="header-title mb-3">Multi Series Timeline</h4>

                                <div dir="ltr">
                                    <div id="multi-series-timeline" class="apex-charts" data-colors="#35b8e0,#39afd1">
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
                                <h4 class="header-title mb-3">Advanced Timeline</h4>
                                <div dir="ltr">
                                    <div id="advanced-timeline" class="apex-charts"
                                        data-colors="#5b69bc,#10c469,#fa5c7c"></div>
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
                                <h4 class="header-title mb-3">Multiple Series - Group Rows</h4>
                                <div dir="ltr">
                                    <div id="group-rows-timeline" class="apex-charts"
                                        data-colors="#5b69bc,#10c469,#fa5c7c,#35b8e0,#39afd1,#ffc35a, #eef2f7, #313a46,#3577f1, #0ab39c, #f0a548,#68eaff">
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

    <!-- Moment js -->
    <script src="assets/vendor/moment/min/moment.min.js"></script>

    <!-- Apex Chart Timeline Demo js -->
    <script src="assets/js/components/chart-apex-timeline.js"></script>

</body>

</html>