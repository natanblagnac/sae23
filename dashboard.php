<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$stmt = $pdo->query("SELECT m.valeur, m.date_heure, c.nom_capteur FROM Mesure m JOIN Capteur c ON m.id_capteur = c.id_capteur ORDER BY m.date_heure DESC LIMIT 10");
$mesures = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - SAE23</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>SAE23 - Gestion des données de capteurs</h1>
        <nav>
            <a href="dashboard.php">Tableau de bord</a>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="administration.php">Administration</a>
            <?php elseif ($_SESSION['role'] == 'gestionnaire'): ?>
                <a href="gestionnaire.php">Gestion</a>
            <?php endif; ?>
            <a href="consultation.php">Consultation</a>
            <a href="fairphone.php">Fairphone 3+</a>
            <a href="experience.php">Retours d'expérience</a>
            <a href="php/logout.php">Déconnexion</a>
        </nav>
    </header>
    <main>
        <h2>Données récentes des capteurs</h2>
        <table>
            <tr><th>Capteur</th><th>Valeur</th><th>Date et Heure</th></tr>
            <?php foreach ($mesures as $mesure): ?>
                <tr>
                    <td><?php echo htmlspecialchars($mesure['nom_capteur']); ?></td>
                    <td><?php echo htmlspecialchars($mesure['valeur']); ?></td>
                    <td><?php echo htmlspecialchars($mesure['date_heure']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <footer>
        <p>&copy; 2025 SAE23 Team - Tous droits réservés</p>
    </footer>
</body>
</html>
