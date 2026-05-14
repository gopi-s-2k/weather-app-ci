<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <?=  $this->include('search_cities') ?>
    <?=  $this->include('current_location') ?>
</div>
<?= $this->endSection() ?>