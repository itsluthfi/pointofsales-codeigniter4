<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- DataTables  & Plugins -->
<script src="<?= base_url('assets') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- Modal -->
<div class="modal fade" id="modalproduk" tabindex="-1" role="dialog" aria-labelledby="modalprodukLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalprodukLabel">Data Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="keywordkode" id="keywordkode" value="<?= $keyword ?>">
                <table id="dataproduk" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Harga Jual</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#dataproduk').DataTable({
            'processing': true,
            'serverSide': true,
            'order': [],
            'ajax': {
                'url': '<?= site_url('penjualan/listDataProduk') ?>',
                'type': 'POST',
                'data': {
                    keywordkode: $('#keywordkode').val()
                }
            },
            'columnDefs': [{
                'targets': [0],
                'orderable': false,
            }, ],
        });
    });

    function pilihitem(kode, nama) {
        $('#kodebarcode').val(kode);
        $('#namaproduk').val(nama);
        $('#modalproduk').on('hidden.bs.modal', function(e) {
            $('#kodebarcode').focus();
        });
        $('#modalproduk').modal('hide');
    }
</script>