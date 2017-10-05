<?php

require_once("../db/funciones_prop.php");
require_once("../db/funciones_postgres.php");

$idCuestionarioRespondido = $_GET['corregirIdCuestionario'];

$consultaCorregido = consulta_arr_pg("select * from web.vw_form_cuestionariorespondido where idcuestionariorespondido=".$idCuestionarioRespondido);
$persona = $consultaCorregido['persona'][1];
$cuestionario = $consultaCorregido['cuestionario'][1];
$txAprobado = $consultaCorregido['txaprobado'][1];
$puntaje = $consultaCorregido['puntaje'][1];
$fecha = $consultaCorregido['txfecha'][1];
$cantidadRegistros = $consultaCorregido['cantregistros'][0];

?>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../css/signin.css">
        <link rel="stylesheet" type="text/css" href="../../css/style2.css">
        <link rel="stylesheet" type="text/css" href="../../css/print.css">

        <script type="application/javascript" src="../../js/jquery.js"></script>
        <script type="application/javascript" src="../../js/bootstrap.js"></script>
        <script type="application/javascript" src="../../js/funciones.js"></script>

        <title>Sistema de Evaluación - Ministerio Público Fiscal</title>
    </head>
    <body>
        <h1 align="center">Ministerio Público Fiscal</h1>
        <h2 align="center">Examen - <?php echo $cuestionario?></h2>
        <div class="row text-center">
            <button class="btn btn-lg btn-success" onclick="window.print()">Imprimir</button>
            <button class="btn btn-lg btn-danger" onclick="window.location.href='/cuestionario_concurso'">Retornar al Inicio</button>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-signin text-left">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4>Persona</h4>
                        </div>
                        <div class="panel-body">
                            <strong><?php echo $persona?></strong>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4>Resultado</h4>
                        </div>
                        <div class="panel-body">
                            <strong><?php echo $txAprobado?></strong>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4>Puntaje</h4>
                        </div>
                        <div class="panel-body">
                            <strong><?php echo $puntaje." Punto/s"?></strong>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4>Fecha</h4>
                        </div>
                        <div class="panel-body">
                            <strong><?php echo $fecha?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="contenedor-cuestionario" align="left">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4>Cuestionario: </h4>
                        </div>
                    </div>
                    <?php
                        for ($i=1;$i<=$cantidadRegistros;$i++){ ?>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <?php
                                    if(strcmp($consultaCorregido['preguntatipodato'][$i],"texto") === 0){?>
                                        <h5><?php echo $consultaCorregido['ordentexto'][$i]." - ".$consultaCorregido['pregunta'][$i] ?></h5><?php
                                    } else if(strcmp($consultaCorregido['preguntatipodato'][$i],"imagen") === 0){
                                        ?><div style="text-align: left; float: left;">
                                            <?php echo $consultaCorregido['ordentexto'][$i]." - "?>
                                        </div>
                                        <div style="text-align: center">
                                            <img width="600" src="../../img/<?php echo $consultaCorregido['pregunta'][$i]?>">
                                        </div><?php
                                    }
                                    ?>
                                </div>
                                <?php if($consultaCorregido['opcion'][$i] != null){ ?>
                                    <div class="panel-body alert-cuestionario text-center">
                                        <?php
                                        if(strcmp($consultaCorregido['opciontipodato'][$i],"texto") === 0){
                                            ?><strong><?php echo $consultaCorregido['opcion'][$i] ?></strong><?php
                                        } else if(strcmp($consultaCorregido['opciontipodato'][$i],"imagen") === 0){
                                            ?><img src="../../img/<?php echo $consultaCorregido['opcion'][$i]?>"><?php
                                        }
                                        ?>
                                    </div>
                                <?php
                                } ?>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>