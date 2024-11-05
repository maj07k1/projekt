<?php
require 'auth.php';
preveriVlogo(3); // Only accessible to students
require 'db_connect.php';

// Get the student's enrolled subjects
$query = "SELECT predmeti.id_predmet, predmeti.ime_predmeta 
          FROM predmeti
          JOIN predmeti_ucenci ON predmeti.id_predmet = predmeti_ucenci.id_predmet
          WHERE predmeti_ucenci.id_ucenec = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['id_uporabnik']);
$stmt->execute();
$result = $stmt->get_result();
$subjects = $result->fetch_all(MYSQLI_ASSOC);

// Get assignments for the student's subjects
$assignments = [];
if (!empty($subjects)) {
    $subject_ids = implode(",", array_column($subjects, "id_predmet"));
    $query = "SELECT naloge.naslov_naloge, naloge.id_predmet 
              FROM naloge 
              WHERE id_predmet IN ($subject_ids)";
    $assignments_result = $conn->query($query);
    while ($row = $assignments_result->fetch_assoc()) {
        $assignments[$row['id_predmet']][] = $row['naslov_naloge'];
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Student Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Student Menu</h2>
            <a href="view_materials.php">Preglej material</a>
            <a href="upload_assignment.php">Oddaj nalogo</a>
            <a href="logout.php">Odjava</a>
        </div>

        <div class="card">
            <h3>Moji predmeti</h3>
            <ul>
                <?php foreach ($subjects as $subject): ?>
                    <li><?php echo $subject['ime_predmeta']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card">
            <h3>Moje naloge</h3>
            <ul>
                <?php foreach ($assignments as $subject_id => $tasks): ?>
                    <li><strong><?php echo $subjects[$subject_id]['ime_predmeta'] ?? 'Unknown'; ?>:</strong></li>
                    <?php foreach ($tasks as $task): ?>
                        <li><?php echo $task; ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
