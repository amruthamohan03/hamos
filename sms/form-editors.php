<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Editors";
    include('partials/title-meta.php');
    ?>

    <!-- Quill css -->
    <link href="assets/vendor/quill/quill.core.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet" type="text/css" />

    <?php include('partials/head-css.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include('partials/topbar.php'); ?>

        <?php
        $title = "Editors";
        include('partials/sidenav.php');
        ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <div class="page-container">

                <div class="card">
                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Quill Editor</h4>
                    </div>

                    <div class="card-body">
                        <p class="text-muted">Snow is a clean, flat toolbar theme.</p>

                        <div id="snow-editor" style="height: 300px;">
                            <h3><span class="ql-size-large">Hello World!</span></h3>
                            <p><br></p>
                            <h3>This is an simple editable area.</h3>
                            <p><br></p>
                            <ul>
                                <li>
                                    Select a text to reveal the toolbar.
                                </li>
                                <li>
                                    Edit rich document on-the-fly, so elastic!
                                </li>
                            </ul>
                            <p><br></p>
                            <p>
                                End of simple area
                            </p>
                        </div><!-- end Snow-editor-->
                    </div>
                </div> <!-- end card-->

                <div class="card">
                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Bubble Editor</h4>
                    </div>

                    <div class="card-body">
                        <p class="text-muted">Bubble is a simple tooltip based theme.</p>

                        <div id="bubble-editor" style="height: 300px;">
                            <h3><span class="ql-size-large">Hello World!</span></h3>
                            <p><br></p>
                            <h3>This is an simple editable area.</h3>
                            <p><br></p>
                            <ul>
                                <li>
                                    Select a text to reveal the toolbar.
                                </li>
                                <li>
                                    Edit rich document on-the-fly, so elastic!
                                </li>
                            </ul>
                            <p><br></p>
                            <p>
                                End of simple area
                            </p>
                        </div> <!-- end Snow-editor-->
                    </div>
                </div> <!-- end card-->

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

    <!-- Quill Editor js -->
    <script src="assets/vendor/quill/quill.js"></script>

    <!-- Quill Demo js -->
    <script src="assets/js/components/form-quilljs.js"></script>

</body>

</html>