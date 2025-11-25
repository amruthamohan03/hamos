<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Grid Js Tables";
    include('partials/title-meta.php');
    ?>

    <!-- gridjs css -->
    <link rel="stylesheet" href="assets/vendor/gridjs/theme/mermaid.min.css">

    <?php include('partials/head-css.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php
        $title = "Grid Js Tables";
        include('partials/sidenav.php');
        ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <div class="page-container">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title mb-0 flex-grow-1">Base Example</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div id="table-gridjs"></div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title mb-0">Pagination</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div id="table-pagination"></div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title mb-0">Search</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div id="table-search"></div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title mb-0">Sorting</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div id="table-sorting"></div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title mb-0">Loading State</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div id="table-loading-state"></div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title mb-0">Fixed Header</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div id="table-fixed-header"></div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title mb-0">Hidden Columns</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div id="table-hidden-column"></div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
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

    <!-- gridjs js -->
    <script src="assets/vendor/gridjs/gridjs.umd.js"></script>

    <script src="assets/js/components/table-gridjs.js"></script>

</body>

</html>