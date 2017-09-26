<?php

use modelo\Opcion;
use modelo\Pregunta;
use modelo\Cuestionario;
use modelo\Persona;

require_once("../modelo/Opcion.php");
require_once("../modelo/Pregunta.php");
require_once("../modelo/Persona.php");
require_once("../modelo/Cuestionario.php");

require_once("../db/funciones_prop.php");
require_once("../db/funciones_postgres.php");

$idCuestionarioRespondido = $_POST['idcuestionarioRespondido'];

$consultaCuestionario = consulta_arr_pg('select * from web.vw_form_cuestionario WHERE idcuestionariorespondido='.$idCuestionarioRespondido);
$consultaCronometro = consulta_arr_pg('select * from resp.spc_cronometro('.$idCuestionarioRespondido.')');

$cuestionario = new Cuestionario();

$preguntas = array();
$cuestionario->setPreguntas($preguntas);
$cuestionario->setIdCuestionarioRespondido($consultaCuestionario['idcuestionariorespondido'][1]);
$cuestionario->setFechaHoraInicio($consultaCronometro['fechahorainicio'][1]);
$cuestionario->setFechaHoraFinal($consultaCronometro['fechahorafinal'][1]);
$cuestionario->setTiempoRestante($consultaCronometro['tiemporestante'][1]);
$cuestionario->setUnidadTiempo($consultaCronometro['unidadtiempo'][1]);
$cuestionario->setTxUnidadTiempo($consultaCronometro['txunidadtiempo'][1]);
$cuestionario->setTxTiempoRestante($consultaCronometro['txtiemporestante'][1]);
$cuestionario->setMensaje($consultaCronometro['mensaje'][1]);
$cuestionario->setTemario($consultaCuestionario['cuestionario'][1]);
$cuestionario->setEstadoCuestionarioRespondido($consultaCronometro['estadocuestionariorespondido'][1]);

$persona = new Persona();
$persona->setApellidosNombres($consultaCuestionario['persona'][1]);
$persona->setCuil($consultaCuestionario['cuit'][1]);
$cuestionario->setPersona($persona);

for($i = 1; $i <= $consultaCuestionario['cantregistros'][0]; $i++){

    if ($i == 1) {
        $pregunta = new Pregunta();
        $pregunta->setId($consultaCuestionario['idpregunta'][$i]);
        $pregunta->setDescripcion($consultaCuestionario['pregunta'][$i]);
        $pregunta->setOrden($consultaCuestionario['ordentexto'][$i]);
        $pregunta->setTipodato($consultaCuestionario['preguntatipodato'][$i]);
        $opciones = array();
        $pregunta->setOpciones($opciones);
    } else {
        if($consultaCuestionario['idpregunta'][$i] != $consultaCuestionario['idpregunta'][$i-1]){
            $pregunta = new Pregunta();
            $pregunta->setId($consultaCuestionario['idpregunta'][$i]);
            $pregunta->setDescripcion($consultaCuestionario['pregunta'][$i]);
            $pregunta->setOrden($consultaCuestionario['ordentexto'][$i]);
            $pregunta->setTipodato($consultaCuestionario['preguntatipodato'][$i]);
            $opciones = array();
            $pregunta->setOpciones($opciones);
        }
    }

    $opcion = new Opcion();
    $opcion->setId($consultaCuestionario['idpreguntaopcion'][$i]);
    $opcion->setOrden($consultaCuestionario['opcionvineta'][$i]);
    $opcion->setDescripcion($consultaCuestionario['opcion'][$i]);
    $opcion->setTipodato($consultaCuestionario['opciontipodato'][$i]);
    if($consultaCuestionario['idopcionrespondida'][$i] != null){
        $opcion->setIsSeleccionada(true);
    } else {
        $opcion->setIsSeleccionada(false);
    }
    $opciones = $pregunta->getOpciones();
    array_push($opciones, $opcion);
    $pregunta->setOpciones($opciones);

    if($i < $consultaCuestionario['cantregistros'][0]) {
        if ($consultaCuestionario['idpregunta'][$i] != $consultaCuestionario['idpregunta'][$i + 1]) {
            $preguntas = $cuestionario->getPreguntas();
            array_push($preguntas, $pregunta);
            $cuestionario->setPreguntas($preguntas);
        }
    } else {
        $preguntas = $cuestionario->getPreguntas();
        array_push($preguntas, $pregunta);
        $cuestionario->setPreguntas($preguntas);
    }
}

header('Content-Type: application/json');
$jsonResponse = json_encode($cuestionario);
echo $jsonResponse;