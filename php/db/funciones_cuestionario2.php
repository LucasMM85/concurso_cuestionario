<?php
function Responder(){

    $rec=consulta_arr_pg('select * from resp.spc_responde_pregunta('.$_POST['idcuestionariorespondido'].'::resp.idcuestionariorespondido,'.$_POST['idpregunta'].'::preg.idpregunta, '.valor_nulo ($_POST['idpreguntaopcion'],'null').'::preg.idpreguntaopcion)');    
    if ($rec['idopcionrespondida'][1]>0) {
        //ok
    }else{
        echo '<div class="container text-center"><div class="alert alert-danger"><strong>No se ha seleccionado ninguna opción para la consigna</strong></div></div>';
    };
    $rec=consulta_arr_pg('select * from web.vw_form_responder where idcuestionariorespondido='.$_POST['idcuestionariorespondido'].' and idpregunta='.$_POST['idpregunta']);
    $cadenadiv='    
    <div class="container text-center">
        <form class="form-signin" class="form_responder" name="Fresponder" method="post" action="preguntas2.php#pregunta_'.$_POST['idpregunta'].'">

            <input type="text" name="idcuestionariorespondido" id="idcuestionariorespondido" value="'.$rec['idcuestionariorespondido']['1'].'" style="visibility:hidden;width:0px;height:0px" />

            <button type="submit" name="Bresponder" class="btn btn-primary" id="Bresponder">Volver al Cuestionario</button>
        </form>        

    </div>
    <div class="container text-center">
        <div class="panel panel-default">
            <div class="panel-heading"><h3>'.$rec['pregunta']['1'].'</h3></div>';
    $c=1;'
    <div class="panel-body">
        <div class="list-group">';
    while ($c<=$rec['cantregistros'][0]){
        if ($rec['idopcionrespondida'][$c]>0){ 
            $respuesta='
                <form class="form_responder" class="form_responder-'.$rec['idpregunta'][$c].'" name="Fresponder" id="Fresponder" method="post" action="responder2.php">

                    <input type="text" name="idcuestionariorespondido" id="txidcuestionariorespondido-'.$rec['idcuestionariorespondido'][$c].'" value="'.$rec['idcuestionariorespondido'][$c].'" style="display:none;width:0px;height:0px"/>

                    <input type="text" name="idpregunta" id="txidpregunta-'.$rec['idpregunta'][$c].'" value="'.$rec['idpregunta'][$c].'" style="display:none;width:0px;height:0px"/>

                    <input type="text" name="idpreguntaopcion" id="txidpreguntaopcion-'.$rec['idpreguntaopcion'][$c].'" value="'.$rec['idpreguntaopcion'][$c].'" style="display:none;width:0px;height:0px"/>    

                    <input type="text" name="idopcionrespondida" id="txidopcionrespondida-'.$rec['idopcionrespondida'][$c].'" value="'.$rec['idopcionrespondida'][$c].'" style="display:none;width:0px;height:0px"/>    

                    <button type="submit" name="BOpcion" class="list-group-item active" id="Bresponder-'.$rec['idpregunta'][$c].'"><div>'.$rec['opcion'][$c].'</div> </button>
                </form>';
        }else{
            $respuesta='
                <form class="form_responder" class="form_responder-'.$rec['idpregunta'][$c].'" name="Fresponder" id="Fresponder" method="post" action="responder2.php">

                    <input type="text" name="idcuestionariorespondido" id="txidcuestionariorespondido-'.$rec['idcuestionariorespondido'][$c].'" value="'.$rec['idcuestionariorespondido'][$c].'" style="display:none;width:0px;height:0px"/>

                    <input type="text" name="idpregunta" id="txidpregunta-'.$rec['idpregunta'][$c].'" value="'.$rec['idpregunta'][$c].'" style="display:none;width:0px;height:0px"/>

                    <input type="text" name="idpreguntaopcion" id="txidpreguntaopcion-'.$rec['idpreguntaopcion'][$c].'" value="'.$rec['idpreguntaopcion'][$c].'" style="display:none;width:0px;height:0px"/>    

                    <button type="submit" name="BOpcion" class="list-group-item" id="Bresponder-'.$rec['idpregunta'][$c].'"><div>'.$rec['opcion'][$c].'</div> </button>

                </form>';
        };

        $cadenadiv=$cadenadiv.$respuesta;
        $c=$c+1;
    };
    $cadenadiv='</div></div></div></div>'.$cadenadiv;
    echo $cadenadiv;
    
}
function MuestraCuestionario(){ 
    
    $rec=consulta_arr_pg('select * from web.vw_form_preguntas where idcuestionariorespondido='.$_POST['idcuestionariorespondido']);
    $cadenadiv='<div class="row">
                    <div class="col-sm-4">
                        <div class="form-signin text-left">
                            <div class="panel panel-success">
                                <div class="panel-heading"><h4>Fecha:</h4></div>
                                <div class="panel-body"><strong>'.$rec['fecha']['1'].'</strong></div>
                            </div>
                            <div class="panel panel-success">
                                <div class="panel-heading"><h4>Nro cuestionario:</h4></div>
                                <div class="panel-body"><strong>'.$rec['idcuestionariorespondido']['1'].'</strong></div> 
                            </div>
                            <div class="panel panel-success">
                                <div class="panel-heading"><h4>Persona: </h4></div>
                                <div class="panel-body"><strong>'.$rec['persona']['1'].'</strong></div>
                            </div>
                            <div class="panel panel-success">
                                <div class="panel-heading"><h4>Cuestionario: </h4></div>
                                <div class="panel-body"><strong>'.$rec['cuestionario']['1'].'</strong></div>
                            </div>
                            <div class="panel panel-success">
                                <div class="panel-heading"><h4>Límite de tiempo en minutos: </div>
                                <div class="panel-body"><strong>'.$rec['limitetiempo']['1'].'</strong></div>
                            </div>
                        </div>    
                    </div>
                    <div class="col-sm-8">
                        <div class="contenedor-cuestionario" align="left">
                            <div class="panel panel-warning">
                                <div class="panel-heading"><h4>Cuestionario: </h4></div>';
    
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $c=1;'
    <div class="panel-body">';
    while ($c<=$rec['cantregistros'][0]){
        if ($rec['idopcionrespondida'][$c]>0){
            $respuesta='
                <form class="text-center" id="form_responder-'.$rec['idpregunta'][$c].'" name="Fresponder" method="post" action="responder2.php">
                        
                    <input type="text" name="idcuestionariorespondido" id="idcuestionariorespondido" value="'.$rec['idcuestionariorespondido'][$c].'" style="display:none;width:0px;height:0px"/>
                              
                    <input type="text" name="idpregunta" id="txidpregunta-'.$rec['idpregunta'][$c].'" value="'.$rec['idpregunta'][$c].'" style="display:none;width:0px;height:0px"/>
                                               
                    <input type="text" name="idopcionrespondida" id="txidopcionrespondida-'.$rec['idopcionrespondida'][$c].'" value="'.$rec['idopcionrespondida'][$c].'" style="display:none;width:0px;height:0px"/>    
                        
                    <div class="alert-cuestionario alert-success"><strong>'.$rec['opcion'][$c].'</strong></div>
                        
                    <button align="center" class="btn btn-primary text-center" name="Bresponder" id="Bresponder-'.$rec['idpregunta'][$c].'" type="submit">Modificar Respuesta</button>
                          
                </form>
                ';
        }else{
            $respuesta='
                <form class="text-center" id="form_responder-'.$rec['idpregunta'][$c].'" name="Fresponder" method="post" action="responder2.php">
                        
                    <input type="text" name="idcuestionariorespondido" id="txidcuestionariorespondido-'.$rec['idcuestionariorespondido'][$c].'" value="'.$rec['idcuestionariorespondido'][$c].'" style="display:none;width:0px;height:0px"/>
                        
                        
                    <input type="text" name="idpregunta" id="txidpregunta-'.$rec['idpregunta'][$c].'" value="'.$rec['idpregunta'][$c].'" style="display:none;width:0px;height:0px"/>
                        
                                               
                    <input type="text" name="idopcionrespondida" id="txidopcionrespondida-'.$rec['idopcionrespondida'][$c].'" value="'.$rec['idopcionrespondida'][$c].'" style="display:none;width:0px;height:0px"/>    
                        
                    <button class="btn btn-primary text-center" name="Bresponder" id="Bresponder-'.$rec['idpregunta'][$c].'" type="submit">Responder</button>
                        
                </form>';
        };
        
        $cadenadiv=$cadenadiv.'<div class="panel panel-info"><div class="panel-heading"><h5><strong>'.$rec['ordentexto'][$c].'</strong> - '.$rec['pregunta'][$c].'</h5></div>';
        $cadenadiv=$cadenadiv.$respuesta.'</div>';
        $c=$c+1;
    };
    $cadenadiv='</div></div></div></div>'.$cadenadiv;
    echo $cadenadiv;
}

function reingresar(){
    $cadenadiv='<form id="form_inicio" name="inicio" method="post" action="index.php">'
            . '<button type="submit" id="Breingreso" ><div> '
            . ' Los datos que Ud. respondió fueron correctamente guardados, reingrese al sistema por favor...'
            .'</div></button></form>';
    echo $cadenadiv;
}

function validacliente($idcuestionariorespondido) {
    
    $ip_mac=cliente_ip_mac();
    
    $rec=consulta_arr_pg("select * from resp.spc_validacliente(".$idcuestionariorespondido."::resp.idcuestionariorespondido,'".$ip_mac["ip"]."'::pub.url)");
    if ($rec['validado']['1']=='0') {
        $cadenadiv='<form class="text-center" id="form_inicio" name="inicio" method="post" action="index2.php">'
                . '<button class="btn btn-danger btn-lg" type="submit" id="Breingreso" ><div> '
                . $rec['mensaje']['1']
                .'</div></button></form>';
        
        $retorno['cadenadiv']= $cadenadiv;  
        $retorno['valor']=0;
          
    }else{
        $cadenadiv='<div> '
                . $rec['mensaje']['1']
                .'</div>';
        $retorno['cadenadiv']= $cadenadiv;  
        $retorno['valor']='1';        
    }

    return $retorno;

}

function cronometro($idcuestionariorespondido) {
    $rec=consulta_arr_pg("select * from resp.spc_cronometro(".$idcuestionariorespondido."::resp.idcuestionariorespondido)");
    if ($rec['tiemporestante']['1']>0){
        $retorno['habilitado']=1;
        $cadenadiv='<div> '
                . $rec['mensaje']['1']
                .'</div>';
     
    }else{
        $retorno['tiemporestante']=0;
        $cadenadiv='<form id="form_inicio" name="inicio" method="post" action="index2.php">'
                . '<button type="submit" id="Breingreso" ><div> '
                . $rec['mensaje']['1']
                .'</div></button></form>';

    };
    $retorno['tiemporestante']=$rec['tiemporestante']['1'];
    $retorno['fechahorainicio']=$rec['fechahorainicio']['1'];
    $retorno['fechahorafinal']=$rec['fechahorafinal']['1'];
    $retorno['txunidadtiempo']=$rec['txunidadtiempo']['1'];
    $retorno['txtiemporestante']=$rec['txtiemporestante']['1'];
    $retorno['cadenadiv']= $cadenadiv;  
    
    return $retorno;
}



?>