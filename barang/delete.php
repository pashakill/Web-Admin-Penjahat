<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../db.php');

$id = $_POST['id'];
$query = "DELETE FROM barang WHERE id = ?";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Barang berhasil dihapus."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menghapus barang: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Error preparing the query."]);
}