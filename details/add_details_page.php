<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK &&
        isset($_FILES['image_header']) && $_FILES['image_header']['error'] === UPLOAD_ERR_OK) {
        
        // Get the image data and convert it to Base64
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageHeaderData = file_get_contents($_FILES['image_header']['tmp_name']); // Perbaikan di sini
        
        $imageBase64 = base64_encode($imageData); // Convert image to Base64
        $imageHeaderBase64 = base64_encode($imageHeaderData); // Convert image header to Base64
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gambar gagal diupload.']);
        exit();
    }

    // Get the rest of the form data
    $id = isset($_POST['id']) ? $_POST['id'] : null; // ID can be empty if it's a new entry
    $title = $_POST['title'];
    $maps = $_POST['maps'];
    $deskripsi = $_POST['deskripsi'];
    $subtitle = $_POST['subtitle'];
    $subdeskripsi = $_POST['subdeskripsi'];
    $notelp = $_POST['notelp'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $twitter = $_POST['twitter'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $linkedin = $_POST['linkedin'];

    include('../db.php');

    // Escape data for security
    $title = mysqli_real_escape_string($conn, $title);
    $maps = mysqli_real_escape_string($conn, $maps);
    $deskripsi = mysqli_real_escape_string($conn, $deskripsi);
    $subtitle = mysqli_real_escape_string($conn, $subtitle);
    $subdeskripsi = mysqli_real_escape_string($conn, $subdeskripsi);
    $notelp = mysqli_real_escape_string($conn, $notelp);
    $address = mysqli_real_escape_string($conn, $address);
    $email = mysqli_real_escape_string($conn, $email);
    $twitter = mysqli_real_escape_string($conn, $twitter);
    $facebook = mysqli_real_escape_string($conn, $facebook);
    $instagram = mysqli_real_escape_string($conn, $instagram);
    $linkedin = mysqli_real_escape_string($conn, $linkedin);

    if (!empty($id)) {
        $query = "UPDATE aboutpage 
                  SET title = '$title',
                      subtitle = '$subtitle',
                      subdescription = '$subdeskripsi',
                      deskripsi = '$deskripsi',
                      phone_number = '$notelp',
                      address = '$address',
                      email = '$email',
                      twitter = '$twitter',
                      facebook = '$facebook',
                      instagram = '$instagram',
                      linkedin = '$linkedin',
                      maps = '$maps',
                      image_header = ?,
                      image = ?
                  WHERE id = ?";
        if ($stmt = $conn->prepare($query)) {
            // Bind the image (Base64 encoded) and ID parameters
            $stmt->bind_param("ssi", $imageHeaderBase64, $imageBase64, $id);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
        }
    } else {
        // If ID is empty, perform INSERT
        $query = "INSERT INTO aboutpage (title, deskripsi, subtitle, subdescription, phone_number, address, email, twitter, facebook, instagram, linkedin, maps, image_header, image) 
                  VALUES ('$title', '$deskripsi', '$subtitle', '$subdeskripsi', '$notelp', '$address', '$email', '$twitter', '$facebook', '$instagram', '$linkedin', '$maps', ?, ?)";
        if ($stmt = $conn->prepare($query)) {
            // Bind the image (Base64 encoded) parameters
            $stmt->bind_param("ss", $imageHeaderBase64, $imageBase64);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambahkan.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan data: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
        }
    }

    $conn->close();
}
?>
