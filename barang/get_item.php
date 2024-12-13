<?php
include('../db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM barang WHERE id = $id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $item]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Barang tidak ditemukan']);
    }
}
?>
