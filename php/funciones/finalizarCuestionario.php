<?php

require_once("../db/funciones_prop.php");
require_once("../db/funciones_postgres.php");

$dni = $_POST['dni'];
$idCuestionarioRespondido = $_POST['idCuestionarioRespondido'];

header('Content-Type: application/json');
$consultaFinalizar = consulta_arr_pg('
                                     select * from resp.spc_finaliza_cuestionario(
                                     '.$idCuestionarioRespondido.'::resp.idcuestionariorespondido,
                                     '.$dni.'::pub.documento)');
$jsonResponse = null;
if($consultaFinalizar['error'][0] == 1 && $consultaFinalizar['idestadocuestionariorespondido'][1] == null){
    $mensajeError = $consultaFinalizar['errmsg'][0];
    $jsonResponse = '{"error":1,"errorMessage":"'.$mensajeError.'"}';
} else {
    $mensajeEstado = $consultaFinalizar['estadocuestionariorespondido'][1];
    $idEstado = $consultaFinalizar['idestadocuestionariorespondido'][1];
    $jsonResponse = '{"idEstado":'.$idEstado.',"mensajeEstado":"'.$mensajeEstado.'"}';
}
echo $jsonResponse;