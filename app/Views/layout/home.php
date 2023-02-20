<?= $this->extend('layout/menu') ?>

<?= $this->section('judul') ?>
<h3>Point of Sales CodeIgniter 4</h3>
<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-info"></i> Selamat datang!</h5>
    Selamat datang di aplikasi Point of Sales CodeIgniter 4!
</div>
<?= $this->endSection() ?>