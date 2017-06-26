<?php

require_once("../db/funciones_prop.php");
require_once("../db/funciones_postgres.php");

$idCuestionarioRespondido = $_POST['idCuestionarioRespondido'];

$consultaCorregir = consulta_arr_pg("select * from proc.spc_puntaje_cuestionario(".$idCuestionarioRespondido."::resp.idcuestionariorespondido)");

if($consultaCorregir['error'][0] == 1 && $consultaCorregir['error'][1] != null){
    $idError = $consultaCorregir['error'][0];
    $errorMessage = $consultaCorregir['errmsg'][1];
    $jsonResponse = '{"error":'.$idError.',"errorMessage:"'.$errorMessage.'}';
} else {
    $cuestionario = $consultaCorregir['cuestionario'][1];
    $persona = $consultaCorregir['persona'][1];
    $textoResultado = $consultaCorregir['txaprobado'][1];
    $puntaje = $consultaCorregir['puntaje'][1];
    $jsonResponse = '{"cuestionario":"'.$cuestionario.'","persona":"'.$persona .'","textoResultado":"'.$textoResultado.'","puntaje":"'.$puntaje.'"}';
}

header('Content-Type: application/json');
echo $jsonResponse;