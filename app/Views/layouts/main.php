<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Application</title>
    <script src="<?= base_url('js/jquery.min.js') ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/style.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-light">
    <?= $this->include('layouts/header') ?>

    <?= $this->include('popups/alerts') ?>

    <main class="container">
        <?= $this->renderSection('content') ?>
    </main>
    <div id="global-loader" class="d-none justify-content-center align-items-center position-fixed top-0 start-0 w-100 h-100" style="background-color: rgba(255, 255, 255, 0.8); z-index: 9999;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <?= $this->renderSection('scripts') ?>
    <script>
        let activeAjaxCount = 0;

        $(document).ready(function() {
            $(document).ajaxSend(function() {
                if (activeAjaxCount === 0) {
                    $('#global-loader').removeClass('d-none').addClass('d-flex');
                }
                activeAjaxCount++;
            });

            $(document).ajaxComplete(function() {
                activeAjaxCount--;
                if (activeAjaxCount <= 0) {
                    activeAjaxCount = 0;
                    $('#global-loader').removeClass('d-flex').addClass('d-none');
                }
            });
        });
    </script>
</body>

</html>