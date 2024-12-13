<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Penjahat Baru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form id="addFormPenjahat" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama Penjahat</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan Saat ini</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                    </div>
                    <div class="form-group">
                        <label for="facebook">Facebook</label>
                        <input class="form-control" id="facebook" name="facebook" required>
                    </div>
                    <div class="form-group">
                        <label for="instagram">Instagram</label>
                        <input class="form-control" id="instagram" name="instagram" required>
                    </div>
                    <div class="form-group">
                        <label for="linkedin">Linkedin</label>
                        <input class="form-control" id="linkedin" name="linkedin" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Unggah Gambar Penjahat</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
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
    $('#addFormPenjahat').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
        url: '/web-penjahat/add_penjahat/add_penjahat.php',
        type: 'POST',
        data: formData,
        processData: false, 
        contentType: false, 
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.status === "success") {
                    alert(data.message);
                    loadListPenjahat();
                } else {
                    alert(data.message);
                }
            } catch (e) {
                console.error('Error parsing JSON response', e);
                alert("Terjadi kesalahan dalam respons dari server.");
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ', status, error);
            alert("Terjadi kesalahan saat menambah data.");
        }
        });
    });

    // Cancel action
    $('#cancel').on('click', function () {
        loadListPenjahat(); 
    });
</script>
