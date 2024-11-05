<?php
require 'auth.php';
preveriVlogo(1); // Only accessible to admin
require 'db_connect.php';

// Get the count of students
$result = $conn->query("SELECT COUNT(*) AS count FROM uporabniki WHERE id_vloga = 3");
$student_count = $result->fetch_assoc()['count'];

// Get the count of teachers
$result = $conn->query("SELECT COUNT(*) AS count FROM uporabniki WHERE id_vloga = 2");
$teacher_count = $result->fetch_assoc()['count'];

// Get the count of subjects
$result = $conn->query("SELECT COUNT(*) AS count FROM predmeti");
$subject_count = $result->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Admin Menu</h2>
            <a href="manage_students.php">Uredi študente</a>
            <a href="manage_teachers.php">Uredi učitelje</a>
            <a href="manage_subjects.php">Uredi predmete</a>
            <a href="logout.php">Odjava</a>
        </div>

        <div class="card">
            <h3>Število študentov</h3>
            <p><?php echo $student_count; ?></p>
        </div>

        <div class="card">
            <h3>Število učiteljev</h3>
            <p><?php echo $teacher_count; ?></p>
        </div>

        <div class="card">
            <h3>Število predmetov</h3>
            <p><?php echo $subject_count; ?></p>
        </div>
    </div>
</body>
</html>
