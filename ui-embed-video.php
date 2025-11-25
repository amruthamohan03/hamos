<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Embed Video";
    include('partials/title-meta.php');
    ?>

    <?php include('partials/head-css.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php
        $title = "Embed Video";
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
                                <h4 class="header-title">Responsive embed video 21:9</h4>
                                <p class="text-muted">Use class <code>.ratio-21x9</code></p>

                                <!-- 21:9 aspect ratio -->
                                <div class="ratio ratio-21x9">
                                    <iframe
                                        src="https://www.youtube.com/embed/PrUxWZiQfy4?autohide=0&showinfo=0&controls=0"></iframe>
                                </div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Responsive embed video 1:1</h4>
                                <p class="text-muted">Use class <code>.ratio-1x1</code></p>

                                <!-- 1:1 aspect ratio -->
                                <div class="ratio ratio-1x1">
                                    <iframe src="https://www.youtube.com/embed/PrUxWZiQfy4?ecver=1"></iframe>
                                </div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col -->

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Responsive embed video 16:9</h4>
                                <p class="text-muted">Use class <code>.ratio-16x9</code></p>

                                <!-- 16:9 aspect ratio -->
                                <div class="ratio ratio-16x9">
                                    <iframe
                                        src="https://www.youtube.com/embed/PrUxWZiQfy4?autohide=0&showinfo=0&controls=0"></iframe>
                                </div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Responsive embed video 4:3</h4>
                                <p class="text-muted">Use class <code>.ratio-4x3</code></p>

                                <!-- 4:3 aspect ratio -->
                                <div class="ratio ratio-4x3">
                                    <iframe src="https://www.youtube.com/embed/PrUxWZiQfy4?ecver=1"></iframe>
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