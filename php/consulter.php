<?php
require_once 'config.php';
$stmt = $pdo->query("SELECT m.temperature, m.humidity, m.co2, m.date_heure, c.nom_capteur 
                     FROM Mesure m JOIN Capteur c ON m.id_capteur = c.id_capteur 
                     ORDER BY m.date_heure DESC LIMIT 10");
$mesures = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($mesures);
?>
