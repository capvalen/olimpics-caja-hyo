<?php
include 'conectInfocat.php';
$ip = $_SERVER['REMOTE_ADDR']; // Obtiene la IP del usuario

$sql = $db->prepare("INSERT INTO `visitante`(`registro`, `ip`) VALUES ( CONVERT_TZ(NOW(), @@session.time_zone, '-05:00'), ? );");
$sql->execute([ $ip ]);
echo 'ok';
?>