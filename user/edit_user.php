<?php
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $password = $_POST['password'];
    $username = $_POST['username'];

    // Check for valid values
    if (empty($id) || empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "ID, username, or password cannot be empty."]);
        exit;
    }

    // Updated query to match the number of parameters
    $query = "UPDATE users SET username = ?, password = ? WHERE id = ?";

    // Prepare the query
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameters properly: "ss" for username and password, "i" for id
        $stmt->bind_param("ssi", $username, $password, $id);

        // Execute the query and check for success
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "User berhasil diperbarui."]);
        } else {
            // Output error message if the update fails
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui user: " . $stmt->error]);
        }

        // Close the statement
        $stmt->close();
    } else {
        // Query preparation error
        echo json_encode(["status" => "error", "message" => "Error preparing the query."]);
    }
} else {
    // If the request method is not POST
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
