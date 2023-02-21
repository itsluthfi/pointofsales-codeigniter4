<!-- Modal -->
<div class="modal fade" id="modaltambahkategori" tabindex="-1" aria-labelledby="modaltambahkategoriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaltambahkategoriLabel"><i class="fas fa-plus"></i> Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('kategori/simpandata', ['class' => 'formsimpan']) ?>
            <div class="modal-body">
                <div class="form-grup">
                    <label for="">Nama Kategori</label>
                    <input type="text" name="namakategori" id="namakategori" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary tombolSimpan">Simpan</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.formsimpan').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function(e) {
                    $('.tombolSimpan').prop('disabled', true);
                    $('.tombolSimpan').html('<i class="fas fa-spin fa-spinner"></i>');
                },
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire(
                            'Berhasil menambah kategori!',
                            response.sukses,
                            'success'
                        ).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });

            return false;
        })
    });
</script>