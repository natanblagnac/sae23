<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Chiffrement MD5 (optionnel, per document)

    $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE login = ? AND mdp = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id_utilisateur'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../dashboard.php");
        exit();
    } else {
        header("Location: ../index.html?error=Identifiants incorrects");
        exit();
    }
}
?>
