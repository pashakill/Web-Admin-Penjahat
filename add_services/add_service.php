<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Cek jika ada file gambar dan tidak ada error
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        error_log("File size: " . $_FILES['image']['size']);
    } else {
        error_log("Gambar gagal diupload. Error: " . $_FILES['image']['error']);
        echo json_encode(['status' => 'error', 'message' => 'Gambar gagal diupload.']);
        exit();
    }

    $title = $_POST['layanan'];
    $description = $_POST['deskripsi'];
    include('../db.php');

    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);

    $query = "INSERT INTO services (title, description, image)
              VALUES ('$title', '$description', ?)";
    
    // Persiapkan query untuk dieksekusi
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $imageData);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data Penjahat berhasil disimpan.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
    }

    $conn->close();
}
?>
