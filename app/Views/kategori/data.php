<?= $this->extend('layout/menu') ?>

<?= $this->section('judul') ?>
<h3>Manajemen Data Kategori</h3>
<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <button type="button" class="btn btn-primary tombolTambah"><i class="fas fa-plus"></i> Tambah Kategori</button>
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

        <form method="POST" action="/kategori">
            <?= csrf_field() ?>

            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari nama kategori" name="carikategori" value="<?= $cari ?>" autofocus>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary" name="tombolkategori">Cari</button>
                </div>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $nomor = 1 + (($nohalaman - 1) * 2);
                foreach ($datakategori as $row) : ?>
                    <tr>
                        <td><?= $nomor++ ?></td>
                        <td><?= $row['katnama'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="hapus('<?= $row['katid'] ?>', '<?= $row['katnama'] ?>')"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="float">
            <?= $pager->links('kategori', 'custom_paging') ?>
        </div>
    </div>
    <!-- /.card-body -->
</div>

<div class="viewmodal" style="display: none;"></div>

<script>
    function hapus(id, nama) {
        Swal.fire({
            title: 'Hapus Kategori',
            html: `Apakah anda ingin menghapus kategori <strong>${nama}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, saya ingin menghapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('kategori/hapus') ?>",
                    data: {
                        idkategori: id,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire(
                                'Terhapus!',
                                response.sukses,
                                'success'
                            ).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        };
                    }
                })
            }
        })
    }

    $(document).ready(function() {
        $('.tombolTambah').click(function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?= site_url('kategori/formTambah') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.viewmodal').html(response.data).show();
                        $('#modaltambahkategori').on('shown.bs.modal', function(event) {
                            $('#namakategori').focus();
                        })
                        $('#modaltambahkategori').modal('show');
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        })
    });
</script>
<?= $this->endSection() ?>