<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form id="addBarangForm">
                    <div class="form-group">
                        <label for="id">ID Barang</label>
                        <input type="text" class="form-control" id="id" name="id" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Barang</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" id="cancel">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script src="assets/js/demo/datatables-demo.js"></script>
<script src="assets/js/jquery.min.js"></script>

<script>
    // Handle form submission for adding barang
    $('#addBarangForm').on('submit', function (e) {
        e.preventDefault();
        
        const data = {
            id: $('#id').val(),
            nama: $('#nama').val(),
            deskripsi: $('#deskripsi').val(),
        };

        $.ajax({
            url: '/web/barang/barang.php?action=save',
            type: 'POST',
            data: $(this).serialize(),
            success: function (data) {
                var dat = parseInt(data);
                if (dat == 1) {
                    alert('Input Gagal, ID sudah ada');
                    $("#addBarangForm").trigger('reset');
                    $("#id").focus();
                } else {
                    alert('Berhasil');
                    loadBarang();
                }
            },
            error: function () {
                alert("Terjadi kesalahan saat menambah barang.");
            }
        });
    });

    // Cancel action
    $('#cancel').on('click', function () {
        loadBarang();
    });
</script>
