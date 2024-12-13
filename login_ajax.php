<?php
ini_set('display_errors', 0);

include('db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        ob_clean();
        echo json_encode(['status' => 'success', 'username' => $username]);
    } else {
        ob_clean();
        echo json_encode(['status' => 'error', 'username' => '']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak valid.']);
}
?>
