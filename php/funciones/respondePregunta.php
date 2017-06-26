<?php

use modelo\EstadoRespuesta;

require_once("../db/funciones_prop.php");
require_once("../db/funciones_postgres.php");
require_once("../modelo/EstadoRespuesta.php");


$idCuestionarioRespondido = $_POST['idCuestionarioRespondido'];
$idPregunta = $_POST['idPregunta'];
$idOpcionRespondida = $_POST['idOpcionRespondida'];

$consultaCronometro = consulta_arr_pg('select * from resp.spc_cronometro('.$idCuestionarioRespondido.')');

$idOpcionCuestionarioRespondido = null;

if($consultaCronometro['idestadocuestionariorespondido'][1] == 2){
    $consultaRespondePregunta = consulta_arr_pg('select * from resp.spc_responde_pregunta(
                                                '.$idCuestionarioRespondido.'::resp.idcuestionariorespondido,
                                                '.$idPregunta.'::preg.idpregunta,
                                                '.$idOpcionRespondida.'::preg.idpreguntaopcion)');
    $idOpcionCuestionarioRespondido = $consultaRespondePregunta['idopcionrespondida'][1];
}

$fechaHoraFinal = $consultaCronometro['fechahorafinal'][1];
$idEstado = $consultaCronometro['idestadocuestionariorespondido'][1];
$mensajeEstado = $consultaCronometro['estadocuestionariorespondido'][1];

$estadoRespuesta = new EstadoRespuesta();
$estadoRespuesta->setFechaHoraFinal($fechaHoraFinal);
$estadoRespuesta->setIdEstado($idEstado);
$estadoRespuesta->setIdOpcionCuestionarioRespondido($idOpcionCuestionarioRespondido);
$estadoRespuesta->setMensajeEstado($mensajeEstado);

header('Content-Type: application/json');
$jsonResponse = json_encode($estadoRespuesta);
echo $jsonResponse;

