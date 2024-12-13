<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form id="addUserForm">
                    <div class="form-group">
                        <label for="nama">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Password</label>
                        <input type="text" class="form-control" id="password" name="password" required>
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
    $('#addUserForm').on('submit', function (e) {
        e.preventDefault();
        
        const data = {
            username: $('#username').val(),
            password: $('#password').val(),
        };

        $.ajax({
            url: '/web/user/user.php?action=save',
            type: 'POST',
            data: $(this).serialize(),
            success: function (data) {
                var dat = parseInt(data);
                if (dat == 1) {
                    alert('Input Gagal, Username sudah ada');
                    $("#addUserForm").trigger('reset');
                    $("#id").focus();
                } else {
                    alert('Berhasil');
                    loadUser();
                }
            },
            error: function () {
                alert("Terjadi kesalahan saat menambah barang.");
            }
        });
    });

    // Cancel action
    $('#cancel').on('click', function () {
        loadUser();
    });
</script>
