<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageBase64 = base64_encode($imageData);
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
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $imageBase64);

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
