<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Cek jika ada file gambar dan tidak ada error
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Membaca konten gambar dari file
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageBase64 = base64_encode($imageData);
    } else {
        // Debug: Menampilkan error jika upload gagal
        error_log("Gambar gagal diupload. Error: " . $_FILES['image']['error']);
        echo json_encode(['status' => 'error', 'message' => 'Gambar gagal diupload.']);
        exit();
    }

    // Ambil data form selain gambar
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $linkedin = $_POST['linkedin'];

    // Koneksi ke database
    include('../db.php');

    // Menghindari penggunaan bind_param dengan memasukkan data langsung ke dalam query
    $nama = mysqli_real_escape_string($conn, $nama);
    $jabatan = mysqli_real_escape_string($conn, $jabatan);
    $facebook = mysqli_real_escape_string($conn, $facebook);
    $instagram = mysqli_real_escape_string($conn, $instagram);
    $linkedin = mysqli_real_escape_string($conn, $linkedin);

    // Membuat query untuk menyimpan data
    $query = "INSERT INTO penjahat_crew (nama, jabatan, facebook, instagram, linkedin, image) 
              VALUES ('$nama', '$jabatan', '$facebook', '$instagram', '$linkedin', ?)";
    
    // Persiapkan query untuk dieksekusi
    if ($stmt = $conn->prepare($query)) {
        // Binding gambar (binary) secara langsung
        $stmt->bind_param("s", $imageBase64);

        // Eksekusi query
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data Penjahat berhasil disimpan.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
    }

    // Menutup koneksi
    $conn->close();
}
?>
