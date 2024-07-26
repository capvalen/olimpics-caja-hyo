<?php
error_reporting(E_ALL); ini_set("display_errors", 1);
sleep(2);

require 'conectInfocat.php';
$buenas = 0;
$fijas = [];

$cliente = json_decode($_POST['cliente'], true);
//insertar al postulante
$sqlCliente = $db->prepare("INSERT INTO `postulante`(
`nombre`, `correo`, `celular`, `ciudad`, `registro`) VALUES (
?, ?, ?, ?, CONVERT_TZ(NOW(), @@session.time_zone, '-05:00'))");
$sqlCliente->execute([
	$cliente['nombre'], $cliente['correo'], $cliente['celular'], $cliente['ciudad']
]);

$idCliente = $db->lastInsertId();

$respuestas = json_decode($_POST['respuestas'], true);
$resppuesta_formateada = json_encode($respuestas);
//calificamos:
for ($i=0; $i < 10 ; $i++) { 
	if( $fijas[$i] == $respuestas[$i] ) $buenas++;
}


$sqlRespuestas = $db->prepare("INSERT INTO `respuestas`( `idPostulante`, `respuestas`, `buenas`, `registro`) VALUES (
	?, ?, ?, CONVERT_TZ(NOW(), @@session.time_zone, '-05:00') )");
$sqlRespuestas->execute([
	$idCliente, $resppuesta_formateada, $buenas
]);

$puntaje = 0;
if($buenas<=2) $puntaje=10;
else if($buenas<=4) $puntaje=15;
else if($buenas<=6) $puntaje=20;
else if($buenas<=8) $puntaje=25;
else if($buenas<=10) $puntaje=35;


echo json_encode(
	array( 'mensaje'=> 'grabado', 'idCliente'=> $idCliente, 'buenas' => $buenas, 'puntaje'=> $puntaje)
);
?>
