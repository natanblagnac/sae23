<?php
require_once 'config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'gestionnaire') {
    header("Location: ../index.html");
    exit();
}
$mesures = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $capteur = $_POST['capteur'];
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $stmt = $pdo->prepare("SELECT m.temperature, m.humidity, m.co2, m.date_heure, c.nom_capteur 
                           FROM Mesure m JOIN Capteur c ON m.id_capteur = c.id_capteur 
                           WHERE c.id_capteur = ? AND m.date_heure BETWEEN ? AND ?");
    $stmt->execute([$capteur, $debut, $fin]);
    $mesures = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion - SAE23</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>SAE23 - Gestion des capteurs</h1>
        <nav>
            <a href="../dashboard.php">Tableau de bord</a>
            <a href="../consultation.php">Consultation</a>
            <a href="../fairphone.php">Fairphone 3+</a>
            <a href="../experience.php">Retours</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </header>
    <main>
        <h2>Consultation des données</h2>
        <form action="gestionnaire.php" method="POST">
            <label for="capteur">Capteur :</label>
            <select id="capteur" name="capteur" required>
                <?php
                $stmt = $pdo->query("SELECT id_capteur, nom_capteur FROM Capteur");
                while ($row = $stmt->fetch()) {
                    echo "<option value='{$row['id_capteur']}'>{$row['nom_capteur']}</option>";
                }
                ?>
)</select>
            <label for="debut">Date de début :</label>
            <input type="datetime-local" id="debut" name="debut" required>
            <label for="fin">Date de fin :</label>
            <input type="datetime-local" id="fin" name="fin" required>
            <button type="submit">Consulter</button>
        </form>
        <?php if (!empty($mesures)): ?>
            <table>
                <tr><th>Capteur</th><th>Température (°C)</th><th>Humidité (%)</th><th>CO2 (ppm)</th><th>Date et Heure</th></tr>
                <?php foreach ($mesures as $mesure): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($mesure['nom_capteur']); ?></td>
                        <td><?php echo htmlspecialchars($mesure['temperature']); ?></td>
                        <td><?php echo htmlspecialchars($mesure['humidity']); ?></td>
                        <td><?php echo htmlspecialchars($mesure['co2']); ?></td>
                        <td><?php echo htmlspecialchars($mesure['date_heure']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </main>
    <footer>
        <p>© 2025 SAE23 Team - Tous droits réservés</p>
    </footer>
</body>
</html>
