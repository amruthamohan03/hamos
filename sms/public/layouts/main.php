<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? 'My App'; ?></title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/assets/css/adminlte.css">
</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<!-- Main Content -->
<div class="container">
    <?php echo $content; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<script src="<?php echo URL_ROOT; ?>/assets/js/app.js"></script>
</body>
</html>
