<?php
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idLama = $_POST['idLama'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $idBaru = $_POST['id'];

    $query = "UPDATE barang SET nama = ?, deskripsi = ?, id = ? WHERE id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssss", $nama, $deskripsi, $idBaru, $idLama);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Barang berhasil diperbarui."]);

        } else {
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui barang: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error preparing the query."]);
    }
}
?>
