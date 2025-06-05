<?php
require_once 'php/config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];
    $id_batiment = $_POST['id_batiment'] ?: null;
    $stmt = $pdo->prepare("INSERT INTO Utilisateur (login, mdp, role, id_batiment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$login, $password, $role, $id_batiment]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - SAE23</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>SAE23 - Administration</h1>
        <nav>
            <a href="dashboard.php">Tableau de bord</a>
            <a href="consultation.php">Consultation</a>
            <a href="fairphone.php">Fairphone 3+</a>
            <a href="experience.php">Retours</a>
            <a href="php/logout.php">Déconnexion</a>
        </nav>
    </header>
    <main>
        <h2>Gestion des utilisateurs</h2>
        <form action="administration.php" method="POST">
            <label for="login">Login :</label>
            <input type="text" id="login" name="login" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <label for="role">Rôle :</label>
            <select id="role" name="role">
                <option value="admin">Administrateur</option>
                <option value="gestionnaire">Gestionnaire</option>
                <option value="utilisateur">Utilisateur</option>
            </select>
            <label for="id_batiment">Bâtiment :</label>
            <select id="id_batiment" name="id_batiment">
                <option value="">Aucun</option>
                <?php
                $stmt = $pdo->query("SELECT id_batiment, nom_batiment FROM Batiment");
                while ($row = $stmt->fetch()) {
                    echo "<option value='{$row['id_batiment']}'>{$row['nom_batiment']}</option>";
                }
                ?>
            </select>
            <button type="submit">Ajouter utilisateur</button>
        </form>
    </main>
    <footer>
        <p>© 2025 SAE23 Team - Tous droits réservés</p>
    </footer>
</body>
</html>
