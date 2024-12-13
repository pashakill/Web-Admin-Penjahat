<?php
include('../db.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch($action)
{
    case 'save':
        $nama = $conn->real_escape_string($_POST['nama']);
        $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
        $id = $conn->real_escape_string($_POST['id']);
    
        $cek = $conn->query("SELECT * FROM barang WHERE id='$id'");

        if($cek->num_rows > 0){
            echo 1; // ID sudah ada
        } else {
            $query = $conn->query("INSERT INTO barang (id, nama, deskripsi) VALUES ('$id', '$nama', '$deskripsi')");
            echo 0; // Berhasil
        }
        break;

    default:
        echo "Action not recognized.";
        break;
}
?>
