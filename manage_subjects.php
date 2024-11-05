<?php
require 'auth.php';
preveriVlogo(1); // Only the administrator can access

require 'db_connect.php';

// Adding a new subject
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ime_predmeta']) && empty($_POST['delete_id'])) {
    $ime_predmeta = $_POST['ime_predmeta'];
    $query = "INSERT INTO predmeti (ime_predmeta) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $ime_predmeta);
    $stmt->execute();
    echo "<p>Predmet uspešno dodan!</p>";
}

// Deleting a subject
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $query = "DELETE FROM predmeti WHERE id_predmet = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    echo "<p>Predmet uspešno odstranjen!</p>";
}

// Fetch all subjects
$query = "SELECT id_predmet, ime_predmeta FROM predmeti";
$result = $conn->query($query);
$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje Predmetov</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dropdown-container { position: relative; display: inline-block; }
        .dropdown-menu { display: none; position: absolute; background-color: white; min-width: 200px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); z-index: 1; padding: 10px; }
        .dropdown-container.active .dropdown-menu { display: block; }
        .search-box { width: 100%; padding: 5px; margin-bottom: 10px; }
        .subject-option { padding: 5px; cursor: pointer; }
        .subject-option:hover { background-color: #f1f1f1; }
    </style>
    <script>
        function toggleDropdown() {
            document.querySelector('.dropdown-container').classList.toggle('active');
        }

        function filterSubjects() {
            const input = document.getElementById('searchSubject').value.toLowerCase();
            const options = document.querySelectorAll('.subject-option');
            options.forEach(option => {
                const text = option.textContent.toLowerCase();
                option.style.display = text.includes(input) ? '' : 'none';
            });
        }

        function selectSubject(id, name) {
            document.getElementById('selectedSubject').textContent = name;
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
        <h1>Upravljanje Predmetov</h1>
        <form action="manage_subjects.php" method="post">
            <label for="ime_predmeta">Ime Predmeta:</label>
            <input type="text" name="ime_predmeta" required>
            <button type="submit">Dodaj Predmet</button>
        </form>

        <h2>Odstrani Predmet</h2>
        <!-- Dropdown trigger button -->
        <button onclick="toggleDropdown()">Izberi Predmet za Odstranitev</button>
        <span id="selectedSubject" style="margin-left: 10px;">Noben predmet ni izbran</span>

        <!-- Dropdown menu for selecting subject -->
        <div class="dropdown-container">
            <div class="dropdown-menu">
                <input type="text" id="searchSubject" class="search-box" onkeyup="filterSubjects()" placeholder="Išči predmet...">
                <?php foreach ($subjects as $subject): ?>
                    <div class="subject-option" onclick="selectSubject(<?php echo $subject['id_predmet']; ?>, '<?php echo $subject['ime_predmeta']; ?>')">
                        <?php echo htmlspecialchars($subject['ime_predmeta']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Form to confirm deletion -->
        <form action="manage_subjects.php" method="post" style="margin-top: 10px;">
            <input type="hidden" name="delete_id" id="delete_id">
            <button type="submit" onclick="return confirm('Ali ste prepričani, da želite odstraniti ta predmet?');">Potrdi Izbris</button>
        </form>

        <h2>Seznam Predmetov</h2>
        <ul>
            <?php foreach ($subjects as $subject): ?>
                <li><?php echo htmlspecialchars($subject['ime_predmeta']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
