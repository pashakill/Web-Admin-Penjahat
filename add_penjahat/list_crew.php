<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../db.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$perPage = isset($_GET['perPage']) ? (int)$_GET['perPage'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$query = "SELECT * FROM penjahat_crew LIMIT $perPage OFFSET $offset";
$result = $conn->query($query);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

$totalResult = $conn->query("SELECT COUNT(*) AS total FROM penjahat_crew");
$totalRow = $totalResult->fetch_assoc();
$totalData = $totalRow['total'];

$totalPages = ceil($totalData / $perPage);
?>

<div class="container-fluid">
<h1 class="h3 mb-2 text-gray-800">List Penjahat Crew</h1>
<p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
    For more information about DataTables, please visit the <a target="_blank"
        href="https://datatables.net">official DataTables documentation</a>.</p>
<div class="card shadow mb-4">
<div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">List Penjahat Crew</h6>
</div>
    <div class="card-body">
        <button class="btn btn-info btn-user" style="margin-bottom: 30px;" id="addBarang">Tambah</button>    
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Image</th>
                        <th>Facebook</th>
                        <th>Instagram</th>
                        <th>Linkedin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $offset + 1; 
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['jabatan']; ?></td>
                            <td>
                                <?php 
                                $imageData = $row['image'];
                                $base64Image = base64_encode($imageData);
                                echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Image" style="width:100px; height:100px;" />';
                                ?>
                            </td>
                            <td><?php echo $row['facebook']; ?></td>
                            <td><?php echo $row['instagram']; ?></td>
                            <td><?php echo $row['linkedin']; ?></td>
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
            url: '/web-penjahat/add_penjahat/add_penjahat_form.php',
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
            url: '/web-penjahat/add_penjahat/edit_form.php',
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
                    url: '/web-penjahat/add_penjahat/delete.php',
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
            url: '/web-penjahat/barang/list.php',
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
