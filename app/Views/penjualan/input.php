<?= $this->extend('layout/menu') ?>

<?= $this->section('judul') ?>
<h3><i class="fa fa-fw fa-table"></i> Input Kasir</h3>
<?= $this->endSection() ?>


<?= $this->section('isi') ?>

<div class="card card-default color-palette-box">
    <div class="card-header">
        <h3 class="card-title">
            <button type="button" class="btn btn-warning" onclick="window.location='<?= site_url('penjualan') ?>'">
                <i class="fa fa-backward"></i> Kembali</button>
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="nofaktur">Faktur</label>
                    <input type="text" class="form-control" style="color:red;font-weight:bold;" name="nofaktur" id="nofaktur" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" id="tanggal" readonly value="<?= date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="napel">Pelanggan</label>
                    <div class="input-group mb-3">
                        <input type="text" value="-" class="form-control" name="napel" id="napel" readonly>
                        <input type="hidden" name="kopel" id="kopel" value="0">
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-primary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="tanggal">Aksi</label>
                    <div class="input-group">
                        <button class="btn btn-danger" type="button" id="btnHapusTransaksi">
                            <i class="fa fa-trash-alt"></i>
                        </button>&nbsp;
                        <button class="btn btn-success" type="button" id="btnSimpanTransaksi">
                            <i class="fa fa-save"></i>
                        </button>&nbsp;
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="kodebarcode">Kode Produk</label>
                    <input type="text" class="form-control" name="kodebarcode" id="kodebarcode" autofocus>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="jml">Jumlah</label>
                    <input type="number" class="form-control" name="jumlah" id="jumlah" value="1">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="jml">Total Bayar</label>
                    <input type="text" class="form-control form-control-lg" name="totalbayar" id="totalbayar" style="text-align: right; color:blue; font-weight : bold; font-size:30pt;" value="0" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 dataDetailPenjualan">

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('body').addClass('sidebar-collapse');

        buatFaktur();
        dataDetailPenjualan();
    });

    function buatFaktur() {
        $.ajax({
            type: "post",
            url: "<?= site_url('penjualan/buatFaktur') ?>",
            data: {
                tanggal: $('#tanggal').val()
            },
            dataType: "json",
            success: function(response) {
                if (response.fakturpenjualan) {
                    $('#nofaktur').val(response.fakturpenjualan);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function dataDetailPenjualan() {
        $.ajax({
            type: "post",
            url: "<?= site_url('penjualan/dataDetail') ?>",
            data: {
                nofaktur: $('#nofaktur').val()
            },
            dataType: "json",
            beforeSend: function() {
                $('.dataDetailPenjualan').html('<i class="fa fa-spin fa-spinner"></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('.dataDetailPenjualan').html(response.data);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }
</script>

<?= $this->endSection() ?>