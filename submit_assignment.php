<?php
require 'auth.php';
preveriVlogo(3); // Dostop dovoljen samo učencem

require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    $naslov = $_POST['naslov'];
    $file = $_FILES['file'];
    $uploadDir = 'uploads/assignments/';

    // Preveri, če imenik obstaja; če ne, ga ustvari
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Sestavimo pot do datoteke, vključno z imenom učenca in naslovom naloge
    $filePath = $uploadDir . $_SESSION['ime'] . '_' . $naslov . '_' . basename($file["name"]);

    if (move_uploaded_file($file["tmp_name"], $filePath)) {
        // Vstavi nalogo v bazo
        $query = "INSERT INTO naloge (id_ucenec, naslov_naloge, pot_do_datoteke, datum_oddaje) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $_SESSION['id_uporabnik'], $naslov, $filePath);
        $stmt->execute();
        echo "<p>Naloga je bila uspešno oddana!</p>";
    } else {
        echo "<p>Prišlo je do napake pri nalaganju datoteke.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Oddaja Naloge</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Oddaja Naloge</h1>
        <form action="submit_assignment.php" method="post" enctype="multipart/form-data">
            <label for="naslov">Naslov naloge:</label>
            <input type="text" name="naslov" required>
            <label for="file">Izberi datoteko:</label>
            <input type="file" name="file" required>
            <button type="submit">Oddaj</button>
        </form>
    </div>
</body>
</html>
