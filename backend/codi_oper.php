<?php

include "../../../librerias/conection.inc.php";
$acceso = do_conection();
require "../../../librerias/sesiones/acse_busc_sesi.php";

header("Content-Type: text/json");

//$codi_oper = '9999';   //administrador
//$codi_oper = 'fips';   //facultad
//$codi_oper = 'gradis';   //unid de investigacion
//$codi_oper = '4282_1'; //presidente de jurado aedo
//$codi_oper = '4686_1';  //asesor anterior adriazola
//$codi_oper = '4282_1';  //nuevo asesor  
//$codi_oper = '0030_1';  //ultimo asesor     
//$codi_oper = 'grad1';  //grados y titulos
//$codi_oper = 'repo';  //repositorio institucional

$result = array('error' => false);

if (isset($codi_oper) && $codi_oper != null) {
   $result['codi_oper'] = $codi_oper;
   $result['codi_menu_grup'] = 15;
} else {
   $result['error'] = true;
   $result['message'] = "Usuario no autenticado, por favor vuelva a iniciar sesión.";
}

echo json_encode($result);
