<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ime = $_POST['ime'];
    $priimek = $_POST['priimek'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "INSERT INTO uporabniki (ime, priimek, email, geslo, vloga) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $ime, $priimek, $email, $password, $role);
    
    if ($stmt->execute()) {
        echo "<script>alert('Registracija uspešna! Sedaj se lahko prijavite.'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Napaka pri registraciji. Poskusite znova.');</script>";
    }
}
?>