<?php
require 'auth.php';
preveriVlogo(2); // Dostop dovoljen samo učiteljem

require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    $naslov = $_POST['naslov'];
    $file = $_FILES['file'];
    $uploadDir = 'uploads/materials/';
    
    // Preveri, če imenik obstaja, če ne, ga ustvari
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $filePath = $uploadDir . basename($file["name"]);
    
    if (move_uploaded_file($file["tmp_name"], $filePath)) {
        // Vstavi gradivo v bazo
        $query = "INSERT INTO gradiva (naslov, pot_datoteke, datum_objave) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $naslov, $filePath);
        $stmt->execute();
        echo "<p>Gradivo je bilo uspešno naloženo!</p>";
    } else {
        echo "<p>Prišlo je do napake pri nalaganju datoteke.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Nalaganje Gradiva</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Nalaganje Gradiva</h1>
        <form action="upload_material.php" method="post" enctype="multipart/form-data">
            <label for="naslov">Naslov gradiva:</label>
            <input type="text" name="naslov" required>
            <label for="file">Izberi datoteko:</label>
            <input type="file" name="file" required>
            <button type="submit">Naloži</button>
        </form>
    </div>
</body>
</html>
