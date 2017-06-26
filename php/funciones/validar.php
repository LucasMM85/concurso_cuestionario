<?php

use modelo\Persona;
use modelo\DatosCuestionario;
use modelo\Opcion;
use modelo\Pregunta;
use modelo\Cuestionario;

require_once("../modelo/Persona.php");
require_once("../modelo/DatosCuestionario.php");
require_once("../db/funciones_prop.php");
require_once("../db/funciones_postgres.php");

if(isset($_POST['cuit'])) {

    $cuit = $_POST['cuit'];
    $arr_inicializa=consulta_arr_pg('select * from web.vw_form_inicializa where cuit='.$_POST['cuit']);

    if($arr_inicializa['cantregistros'][0] > 0){
        $datosCuestionario = new DatosCuestionario();
        $datosCuestionario->setIdCuestionario($arr_inicializa['idcuestionariorespondido']['1']);
        $datosCuestionario->setPersona($arr_inicializa['persona']['1']);
        $datosCuestionario->setCuit($arr_inicializa['cuit']['1']);
        $datosCuestionario->setCuestionario($arr_inicializa['cuestionario']['1']);
        $datosCuestionario->setTiempo($arr_inicializa['limitetiempo']['1']);
        $jsonResponse = json_encode($datosCuestionario);
    } else {
        $jsonResponse = '{"error":1,"errorMessage":"El usuario no se encuentra inicializado"}';
    }

    header('Content-Type: application/json');
    echo $jsonResponse;
} else {
    echo "NO HAY DATOS";
}