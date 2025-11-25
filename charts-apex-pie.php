<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Apex Pie Chart";
    include('partials/title-meta.php');
    ?>

    <?php include('partials/head-css.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php
        $title = "Pie Charts";
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
                                <h4 class="header-title">Simple Pie Chart</h4>
                                <div dir="ltr">
                                    <div id="simple-pie" class="apex-charts"
                                        data-colors="#5b69bc,#35b8e0,#10c469,#fa5c7c,#e3eaef"></div>
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
                                <h4 class="header-title">Simple Donut Chart</h4>
                                <div dir="ltr">
                                    <div id="simple-donut" class="apex-charts"
                                        data-colors="#39afd1,#f9c851,#313a46,#fa5c7c,#10c469"></div>
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
                                <h4 class="header-title mb-4">Monochrome Pie Chart</h4>
                                <div dir="ltr">
                                    <div id="monochrome-pie" class="apex-charts"></div>
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
                                <h4 class="header-title mb-4">Gradient Donut Chart</h4>
                                <div dir="ltr">
                                    <div id="gradient-donut" class="apex-charts"
                                        data-colors="#5b69bc,#35b8e0,#10c469,#fa5c7c,#e3eaef"></div>
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
                                <h4 class="header-title mb-4">Patterned Donut Chart</h4>
                                <div dir="ltr">
                                    <div id="patterned-donut" class="apex-charts"
                                        data-colors="#39afd1,#f9c851,#313a46,#fa5c7c,#10c469"></div>
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
                                <h4 class="header-title mb-4">Pie Chart with Image fill</h4>
                                <div dir="ltr">
                                    <div id="image-pie" class="apex-charts"
                                        data-colors="#39afd1,#f9c851,#5b69bc,#10c469"></div>
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
                                <h4 class="header-title mb-4">Donut Update</h4>
                                <div dir="ltr">
                                    <div id="update-donut" class="apex-charts"
                                        data-colors="#5b69bc,#35b8e0,#10c469,#fa5c7c"></div>
                                </div>

                                <div class="text-center mt-2">
                                    <button class="btn btn-sm btn-primary" id="randomize">RANDOMIZE</button>
                                    <button class="btn btn-sm btn-primary" id="add">ADD</button>
                                    <button class="btn btn-sm btn-primary" id="remove">REMOVE</button>
                                    <button class="btn btn-sm btn-primary" id="reset">RESET</button>
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

    <!-- Apex Chart Pie Demo js -->
    <script src="assets/js/components/chart-apex-pie.js"></script>

</body>

</html>