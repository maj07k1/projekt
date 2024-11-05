<?php
require 'auth.php';
preveriVlogo(1); // Only the administrator can access

require 'db_connect.php';

// Adding a new student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ime'], $_POST['priimek'], $_POST['email'], $_POST['geslo']) && empty($_POST['delete_id'])) {
    $ime = $_POST['ime'];
    $priimek = $_POST['priimek'];
    $email = $_POST['email'];
    $geslo = password_hash($_POST['geslo'], PASSWORD_DEFAULT); // Hash for password security

    $query = "INSERT INTO uporabniki (ime, priimek, email, geslo, id_vloga) VALUES (?, ?, ?, ?, 3)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $ime, $priimek, $email, $geslo);
    $stmt->execute();
    echo "<p>Učenec uspešno dodan!</p>";
}

// Deleting a student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $query = "DELETE FROM uporabniki WHERE id_uporabnik = ? AND id_vloga = 3";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    echo "<p>Učenec uspešno odstranjen!</p>";
}

// Fetch all students
$query = "SELECT id_uporabnik, ime, priimek FROM uporabniki WHERE id_vloga = 3";
$result = $conn->query($query);
$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje Učencev</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dropdown-container { position: relative; display: inline-block; }
        .dropdown-menu { display: none; position: absolute; background-color: white; min-width: 200px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); z-index: 1; padding: 10px; }
        .dropdown-container.active .dropdown-menu { display: block; }
        .search-box { width: 100%; padding: 5px; margin-bottom: 10px; }
        .student-option { padding: 5px; cursor: pointer; }
        .student-option:hover { background-color: #f1f1f1; }
    </style>
    <script>
        function toggleDropdown() {
            document.querySelector('.dropdown-container').classList.toggle('active');
        }

        function filterStudents() {
            const input = document.getElementById('searchStudent').value.toLowerCase();
            const options = document.querySelectorAll('.student-option');
            options.forEach(option => {
                const text = option.textContent.toLowerCase();
                option.style.display = text.includes(input) ? '' : 'none';
            });
        }

        function selectStudent(id, name) {
            document.getElementById('selectedStudent').textContent = name;
            document.getElementById('delete_id').value = id;
            toggleDropdown();
        }
    </script>
</head>
<body>
    <!-- Back to Admin Dashboard button -->
<div style="margin-bottom: 20px;">
    <a href="admin_dashboard.php" class="back-btn">Nazaj na Nadzorno Ploščo Administratorja</a>
</div>

    <div class="dashboard-container">
        <h1>Upravljanje Učencev</h1>
        <form action="manage_students.php" method="post">
            <label for="ime">Ime:</label>
            <input type="text" name="ime" required>
            
            <label for="priimek">Priimek:</label>
            <input type="text" name="priimek" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="geslo">Geslo:</label>
            <input type="password" name="geslo" required>

            <button type="submit">Dodaj Učenca</button>
        </form>

        <h2>Odstrani Učenca</h2>
        <!-- Dropdown trigger button -->
        <button onclick="toggleDropdown()">Izberi Učenca za Odstranitev</button>
        <span id="selectedStudent" style="margin-left: 10px;">Noben učenec ni izbran</span>

        <!-- Dropdown menu for selecting student -->
        <div class="dropdown-container">
            <div class="dropdown-menu">
                <input type="text" id="searchStudent" class="search-box" onkeyup="filterStudents()" placeholder="Išči učenca...">
                <?php foreach ($students as $student): ?>
                    <div class="student-option" onclick="selectStudent(<?php echo $student['id_uporabnik']; ?>, '<?php echo $student['ime'] . ' ' . $student['priimek']; ?>')">
                        <?php echo htmlspecialchars($student['ime'] . ' ' . $student['priimek']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Form to confirm deletion -->
        <form action="manage_students.php" method="post" style="margin-top: 10px;">
            <input type="hidden" name="delete_id" id="delete_id">
            <button type="submit" onclick="return confirm('Ali ste prepričani, da želite odstraniti tega učenca?');">Potrdi Izbris</button>
        </form>

        <h2>Seznam Učencev</h2>
        <ul>
            <?php foreach ($students as $student): ?>
                <li><?php echo htmlspecialchars($student['ime'] . " " . $student['priimek']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>