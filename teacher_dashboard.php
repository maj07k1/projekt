<?php
require 'auth.php';
preveriVlogo(2); // Only accessible to teachers
require 'db_connect.php';

// Get the teacher's subjects
$query = "SELECT predmeti.id_predmet, predmeti.ime_predmeta 
          FROM predmeti
          JOIN predmeti_ucitelji ON predmeti.id_predmet = predmeti_ucitelji.id_predmet
          WHERE predmeti_ucitelji.id_ucitelj = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['id_uporabnik']);
$stmt->execute();
$result = $stmt->get_result();
$subjects = $result->fetch_all(MYSQLI_ASSOC);

// Get submitted assignments for each subject
$submitted_assignments = [];
if (!empty($subjects)) {
    $subject_ids = implode(",", array_column($subjects, "id_predmet"));
    $query = "SELECT naloge.naslov_naloge, naloge.id_predmet, uporabniki.ime, uporabniki.priimek 
              FROM naloge
              JOIN uporabniki ON naloge.id_ucenec = uporabniki.id_uporabnik
              WHERE naloge.id_predmet IN ($subject_ids)";
    $assignments_result = $conn->query($query);
    while ($row = $assignments_result->fetch_assoc()) {
        $submitted_assignments[$row['id_predmet']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Teacher Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Teacher Menu</h2>
            <a href="view_materials.php">Prikaz materialov</a>
            <a href="submit_assignment.php">Oddaj nalogo</a>
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
            <h3>Oddane naloge</h3>
            <ul>
                <?php foreach ($submitted_assignments as $subject_id => $tasks): ?>
                    <li><strong><?php echo $subjects[$subject_id]['ime_predmeta'] ?? 'Unknown'; ?>:</strong></li>
                    <?php foreach ($tasks as $task): ?>
                        <li><?php echo $task['ime'] . " " . $task['priimek'] . " - " . $task['naslov_naloge']; ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
