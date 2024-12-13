<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../db.php');

// Mengecek koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Menentukan jumlah data per halaman dari dropdown atau default
$perPage = isset($_GET['perPage']) ? (int)$_GET['perPage'] : 10;

// Mendapatkan nomor halaman dari URL (default ke halaman 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Menghitung offset untuk query
$offset = ($page - 1) * $perPage;

// Query untuk mengambil data dengan paging
$query = "SELECT * FROM booking_events LIMIT $perPage OFFSET $offset";
$result = $conn->query($query);

// Mengecek jika query berhasil
if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Query untuk menghitung total jumlah data
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM booking_events");
$totalRow = $totalResult->fetch_assoc();
$totalData = $totalRow['total'];

// Menghitung total halaman
$totalPages = ceil($totalData / $perPage);
?>

<div class="container-fluid">
<h1 class="h3 mb-2 text-gray-800">List Booking Events</h1>
<p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
    For more information about DataTables, please visit the <a target="_blank"
        href="https://datatables.net">official DataTables documentation</a>.</p>
<div class="card shadow mb-4">
<div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">List Booking Events</h6>
</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $offset + 1; // Menyelaraskan nomor urut dengan offset
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['subject']; ?></td>
                            <td><?php echo $row['message']; ?></td>
                            <td>
                                <button class="btn btn-success btn-sm" id="editBarang" value="<?php echo $row['id']; ?>">Edit</button>
                                <button class="btn btn-danger btn-sm" id="deleteBarang" value="<?php echo $row['id']; ?>">Delete</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script src="assets/js/demo/datatables-demo.js"></script>

<script>
    $("#addBarang").on("click", function () {
        $.ajax({
            url: '/web-penjahat/barang/add.php',
            type: 'GET',
            success: function (data) {
                $("#contentData").html(data);
            },
            error: function () {
                alert("Gagal memuat form tambah barang.");
            }
        });
    });

    $("#dataTable").on("click", "#editBarang", function () {
        const id = $(this).val();
        $.ajax({
            url: '/web-penjahat/barang/edit_form.php',
            type: 'GET',
            data: { id: id },
            success: function (data) {
                $("#contentData").html(data);
            },
            error: function () {
                alert("Gagal memuat form edit barang.");
            }
        });
    });

    $("#dataTable").on("click", "#deleteBarang", function () {
        const id = $(this).val();
        console.log("Deleting ID:", id);
        if (id) { 
            if (confirm("Apakah Anda yakin ingin menghapus barang ini?")) {
                $.ajax({
                    url: '/web-penjahat/barang/delete.php',
                    type: 'POST',
                    data: { id: id },
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
                        alert("Terjadi kesalahan saat menghapus barang.");
                    }
                });
            }
        } else {
            alert("Invalid or missing ID.");
        }
    });

    // Function to reload the Barang list after add/edit/delete
    function loadBarang() {
        $.ajax({
            url: '/web-penjahat/barang/list_booking.php',
            type: 'GET',
            success: function (data) {
                $("#contentData").html(data);
            },
            error: function () {
                alert("Gagal memuat daftar barang.");
            }
        });
    }
</script>
