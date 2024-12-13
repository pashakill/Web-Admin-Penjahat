<?php
include('../db.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch($action)
{
    case 'save':
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);
    
        $cek = $conn->query("SELECT * FROM users WHERE username='$username'");

        if($cek->num_rows > 0){
            echo 1; // USERNAME sudah ada
        } else {
            $query = $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
            echo 0; // Berhasil
        }
        break;

    default:
        echo "Action not recognized.";
        break;
}
?>
