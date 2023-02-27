<?= $this->extend('layout/menu') ?>

<?= $this->section('judul') ?>
<h3><i class="fa fa-fw fa-table"></i> Manajemen Data Produk</h3>
<?= $this->endSection() ?>


<?= $this->section('isi') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <button type="button" class="btn btn-primary" onclick="window.location='<?= site_url('produk/formTambah') ?>'">
                <i class="fa fa-plus"></i> Tambah Data
            </button>
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
        <div class="table-responsive">
            <?= form_open('produk') ?>
            <?= csrf_field() ?>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari kode/nama produk" name="cariproduk" value="<?= $cari ?>" autofocus>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary" name="tombolcariproduk"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
            <?= form_close() ?>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barcode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Harga Beli (Rp)</th>
                        <th>Harga Jual (Rp)</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $nomor = 1 + (($nohalaman - 1) * 10);
                    foreach ($dataproduk as $r) :
                    ?>
                        <tr>
                            <td><?= $nomor++; ?></td>
                            <td><?= $r['kodebarcode'] ?></td>
                            <td><?= $r['namaproduk'] ?></td>
                            <td><?= $r['katnama'] ?></td>
                            <td><?= $r['satnama'] ?></td>
                            <td><?= $r['harga_jual'] ?></td>
                            <td><?= $r['harga_beli'] ?></td>
                            <td><?= $r['stok_tersedia'] ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" onclick="window.location='<?= site_url('produk/formEdit/' . $r['kodebarcode']) ?>'"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="hapus('<?= $r['kodebarcode'] ?>', '<?= $r['namaproduk'] ?>')"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="float-left">
                <?= $pager->links('produk', 'custom_paging') ?>
            </div>
        </div>
    </div>
</div>

<script>
    function hapus(id, nama) {
        Swal.fire({
            title: 'Hapus Produk',
            html: `Apakah anda ingin menghapus produk <strong>${nama}</strong>?`,
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
                    url: "<?= site_url('produk/hapus') ?>",
                    data: {
                        idproduk: id,
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
</script>

<?= $this->endSection() ?>