<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include('../db.php');

// Get the ID of the barang to be edited
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the data for the barang based on the ID
    $query = "SELECT * FROM barang WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $barang = $result->fetch_assoc();
    } else {
        die("Barang tidak di temukan.");
    }
} else {
    die("ID barang dibutuhkan.");
}
?>

<h1 class="h3 mb-2 text-gray-800">Edit Barang</h1>
<form id="editBarangForm">
    <input type="hidden" name="idLama" id="idLama" value="<?php echo $barang['id']; ?>">

    <div class="form-group">
        <label for="id">ID Barang</label>
        <input type="text" class="form-control" id="id" name="id" value="<?php echo $barang['id']; ?>" required>
    </div>
    <div class="form-group">
        <label for="nama">Nama Barang</label>
        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $barang['nama']; ?>" required>
    </div>
    <div class="form-group">
        <label for="deskripsi">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" required><?php echo $barang['deskripsi']; ?></textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>

<script>
    $('#editBarangForm').on('submit', function (e) {
        e.preventDefault();
        
        const data = {
            idLama: $('#idLama').val(),
            id: $('#id').val(),
            nama: $('#nama').val(),
            deskripsi: $('#deskripsi').val(),
        };

        $.ajax({
            url: '/web-penjahat/barang/edit.php',
            type: 'POST',
            data: data,
            success: function (response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    alert(res.message);
                    loadBarang();
                } else {
                    alert(res.message);
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