<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "solska_aplikacija";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Povezava ni uspela: " . $conn->connect_error);
}
?>