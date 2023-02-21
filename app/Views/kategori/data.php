<?= $this->extend('layout/menu') ?>

<?= $this->section('judul') ?>
<h3>Manajemen Data Kategori</h3>
<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kategori</button>
        </h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>4</td>
                    <td>7</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>5</td>
                    <td>8</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>6</td>
                    <td>9</td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<?= $this->endSection() ?>