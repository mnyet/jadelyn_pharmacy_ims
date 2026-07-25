<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Jadelyn Pharmacy' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Local CSS and JS libraries -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link href="<?= base_url('assets/Datatables/datatables.min.css') ?>" rel="stylesheet">
    <input type="hidden" id="baseUrl" value="<?= base_url() ?>">
    <script src="<?= base_url('assets/dist/sweetalert2.all.min.js') ?>"></script>
    <link href="<?= base_url('assets/dist/sweetalert2.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>" />
</head>
<body>
    <?= $this->include('layout/navbar') ?>

    <script src="<?= base_url('assets/js/jquery-4.0.0.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/Datatables/datatables.min.js') ?>"></script>
    <link href="<?= base_url('assets/css/select2.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/css/select2-bootstrap-5-theme.min.css') ?>" />
    <script src="<?= base_url('assets/js/select2.min.js') ?>"></script>

    <?= $this->renderSection('content') ?>
    <?= $this->include('layout/footer') ?>
</body>
</html>