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
$query = "SELECT * FROM users LIMIT $perPage OFFSET $offset";
$result = $conn->query($query);

// Mengecek jika query berhasil
if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Query untuk menghitung total jumlah data
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM users");
$totalRow = $totalResult->fetch_assoc();
$totalData = $totalRow['total'];

// Menghitung total halaman
$totalPages = ceil($totalData / $perPage);
?>

<div class="container-fluid">
<h1 class="h3 mb-2 text-gray-800">Data User</h1>
<p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
    For more information about DataTables, please visit the <a target="_blank"
        href="https://datatables.net">official DataTables documentation</a>.</p>
<div class="card shadow mb-4">
<div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
</div>
    <div class="card-body">
        <button class="btn btn-info btn-user" style="margin-bottom: 30px;" id="addUser">Tambah</button>    
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID User</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Aksi</th>
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
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['password']; ?></td>
                            <td>
                                <button class="btn btn-success btn-sm" id="editUser" value="<?php echo $row['id']; ?>">Edit</button>
                                <button class="btn btn-danger btn-sm" id="deleteUser" value="<?php echo $row['id']; ?>">Delete</button>
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
    $("#addUser").on("click", function () {
        $.ajax({
            url: '/web/user/adduser.php',
            type: 'GET',
            success: function (data) {
                $("#contentData").html(data);
            },
            error: function () {
                alert("Gagal memuat form tambah user.");
            }
        });
    });

    $("#dataTable").on("click", "#editUser", function () {
        const id = $(this).val();
        $.ajax({
            url: '/web/user/edituser.php',
            type: 'GET',
            data: { id: id },
            success: function (data) {
                $("#contentData").html(data);
            },
            error: function () {
                alert("Gagal memuat form edit user.");
            }
        });
    });

    $("#dataTable").on("click", "#deleteUser", function () {
        const id = $(this).val();
        if (id) { 
            if (confirm("Apakah Anda yakin ingin menghapus user ini?")) {
                $.ajax({
                    url: '/web/user/deleteuser.php',
                    type: 'POST',
                    data: { id: id },
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            alert(res.message);
                            loadUser();
                        } else {
                            alert(res.message);
                        }
                    },
                    error: function () {
                        alert("Terjadi kesalahan saat menghapus user.");
                    }
                });
            }
        } else {
            alert("Invalid or missing ID.");
        }
    });

    // Function to reload the user list after add/edit/delete
    function loadUser() {
        $.ajax({
            url: '/web/user/userlist.php',
            type: 'GET',
            success: function (data) {
                $("#contentData").html(data);
            },
            error: function () {
                alert("Gagal memuat daftar user.");
            }
        });
    }
</script>
