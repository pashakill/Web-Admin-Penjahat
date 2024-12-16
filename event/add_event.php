<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi apakah file diunggah
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Validasi jenis file (hanya menerima gambar)
        $mimeType = mime_content_type($_FILES['image']['tmp_name']);
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
            echo json_encode(['status' => 'error', 'message' => 'Format file tidak didukung.']);
            exit();
        }

        // Konversi gambar ke Base64
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageBase64 = base64_encode($imageData);
    } else {
        error_log("Gambar gagal diupload. Error: " . $_FILES['image']['error']);
        echo json_encode(['status' => 'error', 'message' => 'Gambar gagal diupload.']);
        exit();
    }

    // Ambil input lainnya
    $nama = $_POST['nama'] ?? '';
    $description = $_POST['deskripsi'] ?? '';
    $kategori = $_POST['kategori'] ?? '';

    // Pastikan database terhubung
    include('../db.php');

    // Amankan input
    $nama = mysqli_real_escape_string($conn, $nama);
    $description = mysqli_real_escape_string($conn, $description);
    $kategori = mysqli_real_escape_string($conn, $kategori);

    // Query SQL untuk menyimpan data
    $query = "INSERT INTO events (title, description, category, image)
              VALUES (?, ?, ?, ?)";

    // Gunakan prepared statement untuk menghindari SQL Injection
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssss", $nama, $description, $kategori, $imageBase64);

        // Eksekusi query
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
        } else {
            error_log('Error saat menyimpan data: ' . $stmt->error);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data.']);
        }

        // Tutup statement
        $stmt->close();
    } else {
        error_log('Error saat persiapan query: ' . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'Gagal mempersiapkan query.']);
    }

    // Tutup koneksi database
    $conn->close();
}
?>
