<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are set
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($email && $password) {
        // Prepare and execute query
        $query = "SELECT id_uporabnik, ime, id_vloga FROM uporabniki WHERE email = ? AND geslo = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id_uporabnik, $ime, $id_vloga);
                $stmt->fetch();

                // Set session variables
                $_SESSION['id_uporabnik'] = $id_uporabnik;
                $_SESSION['ime'] = $ime;
                $_SESSION['id_vloga'] = $id_vloga;

                // Redirect based on role
                switch ($id_vloga) {
                    case 1: header("Location: admin_dashboard.php"); break;
                    case 2: header("Location: teacher_dashboard.php"); break;
                    case 3: header("Location: student_dashboard.php"); break;
                }
                exit();
            } else {
                echo "<p style='color: red;'>Napačen email ali geslo.</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color: red;'>Napaka pri pripravi poizvedbe.</p>";
        }
    } else {
        echo "<p style='color: red;'>Vnesite email in geslo.</p>";
    }
    $conn->close();
} else {
    header("Location: index.html");
    exit();
}
?>
