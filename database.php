<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "API-perbankan"; 

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
