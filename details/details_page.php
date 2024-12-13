<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data Halaman</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                ini_set('display_errors', 1);
                error_reporting(E_ALL);
                // Koneksi ke database
                include('../db.php');

                // Ambil data dari tabel aboutpage
                $id = 1; // Ganti dengan ID yang sesuai jika dinamis
                $query = "SELECT * FROM aboutpage";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();

                // Jika data kosong, beri nilai default kosong
                if (!$data) {
                    $data = [
                        'title' => '',
                        'deskripsi' => '',
                        'phone_number' => '',
                        'address' => '',
                        'email' => '',
                        'twitter' => '',
                        'facebook' => '',
                        'instagram' => '',
                        'linkedin' => '',
                        'image' => ''
                    ];
                }

                // Tutup koneksi
                $stmt->close();
                $conn->close();
                ?>

                <form id="editFormPenjahat" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Judul Halaman</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($data['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Halaman</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" required><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="notelp">Nomor Telepon</label>
                        <input type="text" class="form-control" id="notelp" name="phone_number" value="<?php echo htmlspecialchars($data['phone_number']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($data['address']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="twitter">Twitter</label>
                        <input type="text" class="form-control" id="twitter" name="twitter" value="<?php echo htmlspecialchars($data['twitter']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="facebook">Facebook</label>
                        <input type="text" class="form-control" id="facebook" name="facebook" value="<?php echo htmlspecialchars($data['facebook']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="instagram">Instagram</label>
                        <input type="text" class="form-control" id="instagram" name="instagram" value="<?php echo htmlspecialchars($data['instagram']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="linkedin">Linkedin</label>
                        <input type="text" class="form-control" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($data['linkedin']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Unggah Gambar</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                        <?php if (!empty($data['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($data['image']); ?>" alt="Current Image" style="max-width: 200px; margin-top: 10px;">
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" id="cancel">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script>
    $('#editFormPenjahat').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: '/path/to/update_script.php', // Ganti dengan path ke script PHP Anda
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                try {
                    const data = JSON.parse(response);
                    alert(data.message);
                } catch (error) {
                    console.error('Error parsing response', error);
                    alert('Terjadi kesalahan.');
                }
            },
            error: function () {
                alert('Gagal mengirim data.');
            }
        });
    });
</script>
