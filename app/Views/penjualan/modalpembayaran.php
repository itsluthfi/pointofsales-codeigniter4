<script src="<?= base_url('assets/plugins/autoNumeric.js') ?>"></script>

<!-- Modal -->
<div class="modal fade" id="modalpembayaran" tabindex="-1" role="dialog" aria-labelledby="modalpembayaranLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalpembayaranLabel">Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('penjualan/simpanPembayaran', ['class' => 'frmpembayaran']) ?>
            <div class="modal-body">
                <input type="hidden" name="nofaktur" value="<?= $nofaktur ?>">
                <input type="hidden" name="kopel" value="<?= $kopel ?>">
                <input type="hidden" name="totalkotor" id="totalkotor" value="<?= $totalbayar ?>">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Disc(%)</label>
                            <input type="text" name="dispersen" id="dispersen" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Disc(Rp)</label>
                            <input type="text" name="disuang" id="disuang" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Total Pembayaran</label>
                    <input type="text" name="totalbersih" id="totalbersih" class="form-control form-control-lg" value="<?= $totalbayar ?>" style="font-weight: bold; text-align:right; color:blue; font: size 24;" readonly>
                </div>
                <div class="form-group">
                    <label for="">Jumlah Uang</label>
                    <input type="text" name="jmluang" id="jmluang" class="form-control" style="font-weight: bold; text-align:right; color:red; font: size 20pt;" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="">Sisa Uang</label>
                    <input type="text" name="sisauang" id="sisauang" class="form-control" style="font-weight: bold; text-align:right; color:blue; font: size 24pt;" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success tombolSimpan">Simpan</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    $(document).ready(function() {
        $('#dispersen').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });
        $('#disuang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0 '
        });
        $('#totalbersih').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0 '
        });
        $('#jmluang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0 '
        });
        $('#sisauang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0 '
        });

        $('#dispersen').keyup(function(e) {
            hitungDiskon();
        });
        $('#disuang').keyup(function(e) {
            hitungDiskon();
        });
        $('#jmluang').keyup(function(e) {
            hitungSisaUang();
        });

        $('.frmpembayaran').submit(function(e) {
            e.preventDefault();

            let jmluang = ($('#jmluang').val() != '') ? $('#jmluang').autoNumeric('get') : 0;
            let sisauang = ($('#sisauang').val() != '') ? $('#sisauang').autoNumeric('get') : 0;

            if (parseFloat(jmluang) == 0 || parseFloat(jmluang) == '') {
                Toast.fire({
                    icon: 'warning',
                    title: 'Maaf jumlah uang tidak cukup'
                })
                $('#jmluang').focus();

            } else if (parseFloat(sisauang) < 0) {
                Toast.fire({
                    icon: 'error',
                    title: 'Maaf jumlah uang tidak cukup'
                })
                $('#jmluang').focus();
            } else {
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        $('.tombolSimpan').prop('disabled', true);
                        $('.tombolSimpan').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    complete: function() {
                        $('.tombolSimpan').prop('disabled', false);
                        $('.tombolSimpan').html('Simpan');
                    },
                    success: function(response) {
                        if (response.sukses == 'berhasil') {
                            Swal.fire({
                                title: 'Cetak Struk Pembayaran',
                                text: 'Apakah Anda ingin mencetak struk pembayaran?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Tidak'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    alert('Cetak struk pembayaran');
                                    window.location.reload();
                                } else {
                                    window.location.reload();
                                }
                            })
                        }
                    },
                    error: function(xhr, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    },
                });
            }

            return false;
        });
    });

    function hitungDiskon() {
        let totalkotor = $('#totalkotor').val();
        let dispersen = ($('#dispersen').val() != '') ? $('#dispersen').autoNumeric('get') : 0;
        let disuang = ($('#disuang').val() != '') ? $('#disuang').autoNumeric('get') : 0;

        let hasil;

        hasil = parseFloat(totalkotor) - (parseFloat(totalkotor) * (parseFloat(dispersen) / 100)) - parseFloat(disuang);

        $('#totalbersih').val(hasil);

        let totalbersih = $('#totalbersih').val();
        $('#totalbersih').autoNumeric('set', totalbersih);
    }

    function hitungSisaUang() {
        let totalpembayaran = $('#totalbersih').autoNumeric('get');
        let jumlahuang = ($('#jmluang').val() != '') ? $('#jmluang').autoNumeric('get') : 0;

        sisauang = parseFloat(jumlahuang) - parseFloat(totalpembayaran);

        $('#sisauang').val(sisauang);

        let sisauangx = $('#sisauang').val();

        $('#sisauang').autoNumeric('set', sisauangx);
    }
</script>