<?= $this->extend('layout/menu') ?>

<?= $this->section('judul') ?>
<h3><i class="fa fa-fw fa-table"></i> Form Edit Produk</h3>
<?= $this->endSection() ?>


<?= $this->section('isi') ?>
<script src="<?= base_url('assets/plugins/autoNumeric.js') ?>"></script>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <button type="button" class="btn btn-warning" onclick="window.location='<?= site_url('produk') ?>'">
                <i class="fa fa-backward"></i> Kembali
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
        <?= form_open_multipart('produk/updatedata', ['class' => 'formsimpan']) ?>
        <?= csrf_field() ?>
        <div class="form-group row">
            <label for="kodebarcode" class="col-sm-4 col-form-label">Kode Barcode</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="kodebarcode" name="kodebarcode" value="<?= $kode ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="namaproduk" class="col-sm-4 col-form-label">Nama Produk</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="namaproduk" name="namaproduk" value="<?= $nama ?>" autofocus>
                <div class="invalid-feedback errorNamaProduk" style="display: none;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="stok" class="col-sm-4 col-form-label">Stok Tersedia</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="stok" name="stok" value="<?= $stok ?>">
                <div class="invalid-feedback errorStok" style="display: none;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="kategori" class="col-sm-4 col-form-label">Kategori</label>
            <div class="col-sm-4">
                <select class="form-control" name="kategori" id="kategori">
                    <?php
                    foreach ($datakategori as $k) :
                        if ($k['katid'] == $produkkategori) :
                            echo "<option value=\"$k[katid]\" selected>$k[katnama]</option>";
                        else :
                            echo "<option value=\"$k[katid]\">$k[katnama]</option>";
                        endif;
                    endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="satuan" class="col-sm-4 col-form-label">Satuan</label>
            <div class="col-sm-4">
                <select class="form-control" name="satuan" id="satuan">
                    <?php
                    foreach ($datasatuan as $s) :
                        if ($s['satid'] == $produksatuan) :
                            echo "<option value=\"$s[satid]\" selected>$s[satnama]</option>";
                        else :
                            echo "<option value=\"$s[satid]\">$s[satnama]</option>";
                        endif;
                    endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="hargabeli" class="col-sm-4 col-form-label">Harga Beli (Rp)</label>
            <div class="col-sm-4">
                <input style="text-align: right;" type="text" class="form-control" name="hargabeli" id="hargabeli" value="<?= $hargabeli ?>">
                <div class="invalid-feedback errorHargaBeli" style="display: none;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="hargajual" class="col-sm-4 col-form-label">Harga Jual (Rp)</label>
            <div class="col-sm-4">
                <input style="text-align: right;" type="text" class="form-control" name="hargajual" id="hargajual" value="<?= $hargajual ?>">
                <div class="invalid-feedback errorHargaJual" style="display: none;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="uploadgambar" class="col-sm-4 col-form-label">Gambar Produk</label>
            <div class="col-sm-4">
                <img src="<?= base_url($gambarproduk) ?>" alt="" style="width: 100%" class="img-thumbnail">
            </div>
        </div>
        <div class="form-group row">
            <label for="uploadgambar" class="col-sm-4 col-form-label">Upload Gambar (Jika Ada)</label>
            <div class="col-sm-4">
                <input type="file" name="uploadgambar" id="uploadgambar" class="form-control form-control-md" accept=".jpg,.jpeg,.png">
                <div class="invalid-feedback errorUpload" style="display: none;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="uploadgambar" class="col-sm-4 col-form-label"></label>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-success tombolSimpan">
                    Simpan
                </button>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>

<div class="viewmodal" style="display:none;"></div>

<script>
    $(document).ready(function() {
        $('#hargabeli').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });
        $('#hargajual').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });

        $('.tombolSimpan').click(function(e) {
            e.preventDefault();
            let form = $('.formsimpan')[0];
            let data = new FormData(form);

            $.ajax({
                type: "post",
                url: "<?= site_url('produk/updatedata') ?>",
                data: data,
                dataType: "json",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $('.tombolSimpan').html('<i class="fa fa-spin fa-spinner"></i>');
                    $('.tombolSimpan').prop('disabled', true);
                },
                complete: function() {
                    $('.tombolSimpan').html('Perbarui');
                    $('.tombolSimpan').prop('disabled', false);
                },
                success: function(response) {
                    if (response.error) {
                        let msg = response.error;
                        if (msg.errorNamaProduk) {
                            $('.errorNamaProduk').html(msg.errorNamaProduk).show();
                            $('#namaproduk').addClass('is-invalid');
                        } else {
                            $('.errorNamaProduk').fadeOut();
                            $('#namaproduk').removeClass('is-invalid');
                            $('#namaproduk').addClass('is-valid');
                        }

                        if (msg.errorStok) {
                            $('.errorStok').html(msg.errorStok).show();
                            $('#stok').addClass('is-invalid');
                        } else {
                            $('.errorStok').fadeOut();
                            $('#stok').removeClass('is-invalid');
                            $('#stok').addClass('is-valid');
                        }

                        if (msg.errorKategori) {
                            $('.errorKategori').html(msg.errorKategori).show();
                            $('#kategori').addClass('is-invalid');
                        } else {
                            $('.errorKategori').fadeOut();
                            $('#kategori').removeClass('is-invalid');
                            $('#kategori').addClass('is-valid');
                        }

                        if (msg.errorSatuan) {
                            $('.errorSatuan').html(msg.errorSatuan).show();
                            $('#satuan').addClass('is-invalid');
                        } else {
                            $('.errorSatuan').fadeOut();
                            $('#satuan').removeClass('is-invalid');
                            $('#satuan').addClass('is-valid');
                        }

                        if (msg.errorHargaBeli) {
                            $('.errorHargaBeli').html(msg.errorHargaBeli).show();
                            $('#hargabeli').addClass('is-invalid');
                        } else {
                            $('.errorHargaBeli').fadeOut();
                            $('#hargabeli').removeClass('is-invalid');
                            $('#hargabeli').addClass('is-valid');
                        }

                        if (msg.errorHargaJual) {
                            $('.errorHargaJual').html(msg.errorHargaJual).show();
                            $('#hargajual').addClass('is-invalid');
                        } else {
                            $('.errorHargaJual').fadeOut();
                            $('#hargajual').removeClass('is-invalid');
                            $('#hargajual').addClass('is-valid');
                        }

                        if (msg.errorUpload) {
                            $('.errorUpload').html(msg.errorUpload).show();
                            $('#uploadgambar').addClass('is-invalid');
                        }
                    } else {
                        alert(response.sukses);
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>