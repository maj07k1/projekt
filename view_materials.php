<?php
session_start();
require 'auth.php';

// Preverimo, če je uporabnik učitelj ali učenec
if ($_SESSION['id_vloga'] == 2 || $_SESSION['id_vloga'] == 3) {
    require 'db_connect.php';

    // Poizvedba za pridobitev gradiv
    $query = "SELECT naslov, pot_datoteke, datum_objave FROM gradiva";
    $result = $conn->query($query);
} else {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Gradiva</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Gradiva</h1>
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while($row = $result->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo $row['naslov']; ?></strong> - 
                        <a href="<?php echo $row['pot_datoteke']; ?>" download>Prenesi</a>
                        <em>(Objavljeno: <?php echo $row['datum_objave']; ?>)</em>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Ni gradiv za prikaz.</p>
        <?php endif; ?>
        <a href="logout.php" class="logout-btn">Odjava</a>
    </div>
</body>
</html>
