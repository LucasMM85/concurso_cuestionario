<?php

function parametros_conexion_pg(){
    
        $usuario='sa';
        $clave='1';
        $nombredb='cuestionario';
        $puerto='5438';
        $host='127.0.0.1';
        $cadena_con="host=".$host." port=".$puerto." dbname=".$nombredb." user=".$usuario." password=".$clave."";
        
        /*echo 'cadena conexion '.$conexion;*/
        return $cadena_con;
   
}

function consulta_arr_pg($consulta){

    /**
    Función consulta_pg
    
    El argumento de la función es un comando SELECT ... 

    El array devuelto tiene la siguiente estructura:
     columna    0       1       2       3
     fila   
     0      campo1  campo2  campo3  campo4
     1      valor1  valor2  valor3  valor4
     2      valor1  valor2  valor3  valor4
     3      valor1  valor2  valor3  valor4
     * 
     *  
    **/
    ini_set('display_errors',0);
    $cadenaconexion = parametros_conexion_pg();
    #echo $cadenaconexion;
    
    $conexion = pg_connect($cadenaconexion);
    
    $query=$consulta;
    #echo $query;
    //
    $rs1 = pg_query($conexion, $query);
    $numCampos = pg_num_fields($rs1);
    
    
    $numReg = pg_num_rows($rs1);
    
    //echo '$numCampos'.$numCampos.'<br>$numReg'.$numReg;
   
    $orden=1;
    $retorno['cantregistros'][0]=0;
    if ($numReg>0){
        $retorno['cantregistros'][0]=$numReg;
        $retorno['cantcolumnas'][0]=$numCampos;        
        $c=0;
        while ($c<$numCampos){
            $retorno[0][$c]=pg_field_name($rs1,$c); //incorporación de los nombres de campos en la fila0 del array
            
            $c=$c+1;
        };
        //carga de los registros a partir de la fila 1 del array
        
        while ($orden<=$numReg){
            $arr1=pg_fetch_array($rs1,$orden-1,PGSQL_BOTH);//array de la fila $orden (el array inicia en 0 y los registros se inicializan en 1
            $c=0;

            while ($c<$numCampos){
                $retorno[$c][$orden]=$arr1[$c]; //valores de registros referenciados por nros de columna y nro de fila
                $retorno[pg_field_name($rs1,$c)][$orden]=$arr1[$c]; //valores de registros referenciados por nombres de columna y nros de fila

                $c=$c+1;
            };
            $orden=$orden+1;

        };


    /*}else{*/

    };
    
    $rs1 = pg_send_query($conexion, $query);
    $result=pg_get_result($conexion);
    if (pg_result_status($result)==7){
        $retorno['cantregistros'][0]=0;
        $retorno['cantcolumnas'][0]=0;
        $retorno['errmsg'][0]=pg_last_error($conexion);
        $retorno['error'][0]=1;
        $retorno['comsql'][0]=$consulta;
    };
    if (pg_result_status($result)==0){
        //$retorno['cantregistros'][0]=0;
        //$retorno['cantcolumnas'][0]=0;
        $retorno['errmsg'][0]='consulta vacía';
        $retorno['error'][0]=0;
        $retorno['comsql'][0]=$consulta;
    };        
    if (pg_result_status($result)==2){
        //$retorno['cantregistros'][0]=0;
        //$retorno['cantcolumnas'][0]=0;
        //$retorno['errmsg'][0]='no hay registros';
        $retorno['error'][0]=0;
        $retorno['comsql'][0]=$consulta;
    };        


    return $retorno;    
    /*pg_result_status valores  posibles
    0 = PGSQL_EMPTY_QUERY
    1 = PGSQL_COMMAND_OK
    2 = PGSQL_TUPLES_OK
    3 = PGSQL_COPY_TO
    4 = PGSQL_COPY_FROM
    5 = PGSQL_BAD_RESPONSE
    6 = PGSQL_NONFATAL_ERROR
    7 = PGSQL_FATAL_ERROR
    */
}

function consulta_pg($consulta,$tiporetorno,$clase_ccs,$clase_ccs_col,$clase_ccs_reg,$RDH){
    
    /**
    Función consulta_pg
    
    El argumento de la función es un comando SELECT ... 
    La función puede devolver:
     * $tiporetorno: DIV devuelve una estructura basada en divs
     * $tiporetorno: UL devuelve una estructura de listado
     * $tiporetorno: NULL devuelve un array
     * $tiporetorno: ARRDIV devuelve una estructura basada en divs + Array
     * $tiporetorno: ARRUL devuelve una estructura basada en divs + Array
     * 
    El array devuelto tiene la siguiente estructura:
     columna    0       1       2       3
     fila   
     0      campo1  campo2  campo3  campo4
     1      valor1  valor2  valor3  valor4
     2      valor1  valor2  valor3  valor4
     3      valor1  valor2  valorA  valor4
     * 
     *  
     * 
    retorno[-numero de la columna-][-numero de la fila-]
    donde retorno[0][0]=campo1 , retorno[0][1]=valor1 , retorno[1][0]=campo1 , retorno[2][3]=valorA
    retorno[2][max]= máximo valor de la columna 2
    retorno[2][min]= mínimo valor de la columna 2
    El array devuelve además (p/ej.) el valor del campo1 para el registro 20 invocando el array de la
    siguiente manera retorno['campo1'][20]
    
    **/
    $cadenaconexion = parametros_conexion_pg();
    #echo $cadenaconexion;
    
    $conexion = pg_connect($cadenaconexion);
    
    $query=$consulta;
    #echo $query;
    $rs1 = pg_query($conexion, $query);
    
    $numCampos = pg_num_fields($rs1);
    
    
    $numReg = pg_num_rows($rs1);
    
    //echo '$numCampos'.$numCampos.'<br>$numReg'.$numReg;
   

    
    
    if ($numReg>0){
        
        
        $retorno['totalregistros'][0]=$numReg; //es el total de registros que se reciben de la consulta sql
        
        $orden=1;
        $ordenarr=1;
        //el array RDH informa desde y hasta que registro mostrar en el retorno de la consulta
        if ($RDH['desde']>0){
            $ordenarr=$RDH['desde'];
        };

        if ($RDH['hasta']>0 and $RDH['desde']<=$RDH['hasta'] and $RDH['hasta']<=$numReg){
            $numReg=$RDH['hasta']-$ordenarr; // se determina cuantos registros se van a mostrar
        };
      
        $cadenaul='<div class="'.valor_nulo($clase_ccs,'lista').'"><ul>';
        $cadenadiv='<div class="'.valor_nulo($clase_ccs,'tabla').'">';
        $cadenadiv=$cadenadiv.'<div class="'.valor_nulo($clase_ccs_col,'tabla_col').'">';
        
        $retorno['cantregistros'][0]=$numReg;
        $retorno['cantcolumnas'][0]=$numCampos;
        
        $c=0;
        while ($c<$numCampos){
            $retorno[$c][0]=pg_field_name($rs1,$c); //incorporación de los nombres de campos en la fila0 del array
            
            $cadenadiv=$cadenadiv.'<div class="tabla_cel">'.$retorno[$c][0].'</div>';
            $c=$c+1;
        };
        //carga de los registros a partir de la fila 1 del array
        $cadenadiv=$cadenadiv.'</div>';
        
        while ($orden<=$numReg){
             
            $arr1=pg_fetch_array($rs1,$ordenarr-1,PGSQL_BOTH);//array de la fila $orden (el array inicia en 0 y los registros se inicializan en 1
            $c=0;
            $cadenadiv=$cadenadiv.'<div class="'.valor_nulo($clase_ccs_reg,'tabla_reg').'" >';
            
            while ($c<$numCampos){
                
                $retorno[$c][$orden]=$arr1[$c]; //valores de registros referenciados por nros de columna y nro de fila
                $retorno[pg_field_name($rs1,$c)][$orden]=$arr1[$c]; //valores de registros referenciados por nombres de columna y nros de fila
                
                if ($arr1<=0 or $arr1>=0) {
                    if ($arr1[$c]>$retorno[$c]['max'] or $retorno[$c]['max']==null){
                        $retorno[$c]['max']=$arr1[$c];
                    };
                    if ($arr1[$c]<$retorno[$c]['min'] or $retorno[$c]['min']==null){
                        $retorno[$c]['min']=$arr1[$c];
                    };                    
                };
                $cadenaul=$cadenaul.
                        '<li class="'.valor_nulo($clase_ccs_col,'lista_col').'">'.$retorno[0][$c].' '.
                            '<span class="'.valor_nulo($clase_ccs_reg,'lista_reg').'">'.$retorno[$c][$orden].
                            '</span>'.
                        '</li>';
                $cadenadiv=$cadenadiv.'<div class="tabla_cel">'.$retorno[$c][$orden].'</div>';
                $c=$c+1;
            };
            $orden=$orden+1;
            $ordenarr=$ordenarr+1;
            $cadenadiv=$cadenadiv.'</div>';
        };
        $cadenaul=$cadenaul.'</ul></div>';
        $cadenadiv=$cadenadiv.'</div>';
        if ($tiporetorno==NULL){
            return $retorno;
        }{
            if ($tiporetorno=='DIV'){
                echo $cadenadiv;
            };
            if ($tiporetorno=='ARRDIV'){
                echo $cadenadiv;
                return $retorno;
            };
            if ($tiporetorno=='UL'){
                echo $cadenaul;
            };
            if ($tiporetorno=='ARRUL'){
                echo $cadenaul;
                
                return $retorno;
            };            
        };
    }else{
        echo '<div class="msg_error">No se encontraron registros para la consulta: '.$consulta.'</div>';
    };    
    
}
function menu_pg(){
    
    
    $cadenaconexion = parametros_conexion_pg();
    //echo $cadenaconexion;
    
    $conexion = pg_connect($cadenaconexion);
    
    $query='select * from conf.vw_menus order by ordencarga';
    //echo $query;
    $resultado = pg_query($conexion, $query);
   
    $numReg = pg_num_rows($resultado);
    
    $nivel_ant=-1;
        
    if ($numReg>0){
        //echo 'registros '.$numReg;
        echo '<nav class="menu-nav">';
        //echo '<ul class="lista-'.$nivel_act.'">';        
        while ($reg=pg_fetch_array($resultado)) {
            $nivel_act=$reg['nivel'];
            /*$item='<a id="'.$reg['codigorel'].'" class="enlace" href="'.valor_nulo($reg['destinomenu'],'#').'">'.
            '<span class="'.$reg['icononombre'].'">'.
            '</span>'.$reg [ 'descripcion' ].
            '</a>';*/
            $item='<a id="'.$reg['codigorel'].'" class="enlace" href="'.valor_nulo($reg['destinomenu'],'#').'">'.
            '<span class="'.$reg['icononombre'].'">'.
            '</span>'.$reg [ 'descripcion' ].
            '</a>';
            ///////////// final del registro anterior
            if ($nivel_ant>= 0){////ya se procesó un registro
                /*hay que verificar:
                 1- Si se debe incorporar un submenu: abre lista y abre item
                 2- Si se debe incorporar un menú del mismo nivel: abre lista
                 3- Si se debe cerrar un submenú abierto y bajar un nivel
                 */
                if ($nivel_act>$nivel_ant){ //incrementa un nivel=> agrega un submenú en nueva lista
                    /*echo '<ul id="pad'.$reg['codigorelpadre'].'" class="lista-'.$nivel_act.'"><li class="submenu">'.$item;*/
                    /*echo '<ul id="pad'.$reg['codigorelpadre'].'" class="lista"><li '.$reg['etiquetasubmenu'].'>'.$item;*/
                    echo '<ul id="pad'.$reg['codigorelpadre'].'" class="lista"><li class="submenu">'.$item;
   
                } else {};
                if ($nivel_ant==$nivel_act){ //mismo nivel=> cierra menu anterior y agrega un menú en la misma lista
                    /*echo '</li><li '.$reg['etiquetasubmenu'].'>'.$item;*/
                    /*echo '</li><li class="submenu'.$nivel_act.'">'.$item;*/
                    /*echo '</li><li class="submenu0">'.$item;*/
                    if ($reg['nivel']==0){
                        echo '</li><li class="submenu0">'.$item;
                    } else {
                        echo '</li><li class="submenu">'.$item;
                    };                    
        
                } else {};
                if ($nivel_act<$nivel_ant){ //baja nivel => bajará tantos niveles como listas se cierren
                    while ($nivel_act<$nivel_ant){
                        echo '</li></ul>'; /*baja un nivel*/
                        $nivel_ant=$nivel_ant - 1;
                    }; 
                    /*echo '<li class="submenu'.$nivel_act.'">'.$item;*/
                    /*echo '<li '.$reg['etiquetasubmenu'].'>'.$item;*/
                    if ($reg['nivel']== 0){
                        echo '<li class="submenu0">'.$item;
                    } else {
                        echo '<li class="submenu">'.$item;
                    };                    
                };
                
            } else {////ingresa por primera vez
                /*se abre listado e ítem del listado pero no se cierra hasta saber 
                si el cierre es cierre del ítem o apertura de una nueva sublista
                 */
                /*echo '<ul class="lista-0'.$nivel_act.'"><li class="submenu">'.$item;*/
                /*echo '<ul class="lista-0"><li '.$reg['etiquetasubmenu'].'>'.$item;*/
                echo '<ul class="nav"><li class="submenu'.$nivel_act.'">'.$item;
            };
            $nivel_ant=$nivel_act;
        };
        while (0<$nivel_ant){
            echo '</li></ul>'; /*baja un nivel*/
            $nivel_ant=$nivel_ant - 1;
        };
        echo '</li></ul>';
        echo '</nav>';      
    };

    
}

function columnasvistas_pg($actualizar){
    $cadenaconexion = parametros_conexion_pg();
    #echo $cadenaconexion;
    
    $conexion = pg_connect($cadenaconexion);
    
    $query='select * from conf.vw_columnasvistas order by idvista,posicioncolumna';
    #echo $query;
    $rs1 = pg_query($conexion, $query);
    
    $numReg = pg_num_rows($rs1);
    $cantcolumnas=1;
    $orden=0;    
    if ($numReg>0){
        /*echo '<div><ul>';*/
        
        while ($orden<=$numReg){
            
            $arr1=pg_fetch_array($rs1,$orden,PGSQL_BOTH);
            
            $idvistaproc[$orden]=$arr1[0]; //valor del campo idvista para el registro que se está procesando

            //parametros de la vista: por cada cambio en la vista graba los datos de la vista finalizada
            if (($idvistaproc[$orden]!==$idvistaproc[$orden-1]) and ($idvistaproc[$orden-1]>0) ){ //hubo un cambio en el campo idvista=> se trata de una nueva vista
               $cantcolumnas=1;
               $ordencorrelativo=0;

            };
            $retorno['idvista'][$arr1[4]]=$arr1[0];
            $retorno['descripcion'][$arr1[4]]=$arr1[1];
            $retorno['tipopresentacion'][$arr1[4]]=$arr1[2];
            $retorno['origendatos'][$arr1[4]]=$arr1[3];
            $retorno['posicioncolumna'][$arr1[4]]=$arr1[5];
            $retorno['columnavista'][$arr1[4]]=$arr1[6];
            $retorno['muestra'][$arr1[4]]=$arr1[7];
            $retorno['posicioncolarray'][$arr1[4]]=$cantcolumnas;
            $retorno['ordencorrelativo'][$arr1[4]]=$ordencorrelativo;
            
            $orden=$orden+1;
            $ordencorrelativo=$ordencorrelativo+1;
            
                       
        };
        /*echo '</div></ul>';*/
        return $retorno;
    }else{
        echo '<div class="msg_error">No se encontró la vista solicitada</div>';
    };    
    
}

function estructuravista_pg($actualizar){
    
    //static $ret_estr_vista;
    
    if ($actualizar==true){ // se contecta con el motor de bd e inicia el proceso de carga del array
        $cadenaconexion = parametros_conexion_pg();
        #echo $cadenaconexion;

        $conexion = pg_connect($cadenaconexion);

        $query='select * from conf.vw_columnasvistas order by idvista,posicioncolumna';
        #echo $query;
        $rs1 = pg_query($conexion, $query);

        $numReg = pg_num_rows($rs1);
        $orden=0;
        //$idvistaproc[$orden-1]=null; //inicialización del valor del campo idvista para el registro inicial
        if ($numReg>0){ //hay columnas definidas para la vista $idvistaproc[orden]

            $cantcolumnas=1;
            $cantagrup=0;
            while ($orden<$numReg){
                $arr1=pg_fetch_array($rs1,$orden,PGSQL_BOTH);
                $idvistaproc[$orden]=$arr1[0]; //valor del campo idvista para el registro que se está procesando

                //parametros de la vista: por cada cambio en la vista graba los datos de la vista finalizada
                if (($idvistaproc[$orden]!==$idvistaproc[$orden-1]) and ($idvistaproc[$orden-1]>0) ){ //hubo un cambio en el campo idvista=> se trata de una nueva vista
                   $cantcolumnas=1;
                   $cantagrup=0;
                    //$ret_estr_vista[$arr1[0]]['cantgrupos'][0]=$cantgrupos;
                };
                if ($arr1[9]>0){
                    $cantagrup=$cantagrup+1;
                };                
                
                //parametros de las columnas de la vista: por cada cambio en el registro (orden)
                $ret_estr_vista[$idvistaproc[$orden]]['idcolumnavista'][$cantcolumnas]=$arr1[4];
                $ret_estr_vista[$idvistaproc[$orden]]['posicioncolumna'][$cantcolumnas]=$arr1[5];
                $ret_estr_vista[$idvistaproc[$orden]]['columnavista'][$cantcolumnas]=$arr1[6];
                $ret_estr_vista[$idvistaproc[$orden]]['muestra'][$cantcolumnas]=$arr1[7];
                //$ret_estr_vista[$idvista]['ordenagrup'][$orden+1]=$arr1[8];
                $ret_estr_vista[$idvistaproc[$orden]]['idagrupvista'][$cantcolumnas]=$arr1[9];
                $ret_estr_vista[$idvistaproc[$orden]]['posicionagrup'][$cantcolumnas]=$arr1[10];
                $ret_estr_vista[$idvistaproc[$orden]]['etiquetacolumna'][$cantcolumnas]=$arr1[11];
                $ret_estr_vista[$idvistaproc[$orden]]['idagrupvistamuestra'][$cantcolumnas]=$arr1[12];
                $ret_estr_vista[$idvistaproc[$orden]]['formatocolumna'][$cantcolumnas]=$arr1[14];
                $ret_estr_vista[$idvistaproc[$orden]]['idnodomultiple'][$cantcolumnas]=$arr1[17];
                
                $ret_estr_vista[$idvistaproc[$orden]]['descripcion'][0]=$arr1[1];
                $ret_estr_vista[$idvistaproc[$orden]]['tipopresentacion'][0]=$arr1[2];
                $ret_estr_vista[$idvistaproc[$orden]]['origendatos'][0]=$arr1[3];
                //echo '<div>idvista '.$idvistaproc[$orden].' orden '.$cantcolumnas.' formato'.$ret_estr_vista[$idvistaproc[$orden]]['formatocolumna'][$cantcolumnas].'</div>';
         
                $ret_estr_vista[$idvistaproc[$orden]]['cantcolumnas'][0]=$cantcolumnas;
                $ret_estr_vista[$idvistaproc[$orden]]['cantagrup'][0]=$cantagrup;
                $ret_estr_vista[$idvistaproc[$orden]]['idtipoagrup'][0]=$arr1[13];
                //echo '<div>idtipoagrup='.$ret_estr_vista[$idvistaproc[$orden]]['idtipoagrup'][0].'</div>';
                $ret_estr_vista[$idvistaproc[$orden]]['titulovista'][0]=$arr1[15];
                $ret_estr_vista[$idvistaproc[$orden]]['etiquetavista'][0]=$arr1[16];
                $ret_estr_vista[$idvistaproc[$orden]]['maxcantregvisible'][0]=$arr1[17];
                $ret_estr_vista[$idvistaproc[$orden]]['columnavistafiltro'][0]=$arr1[19];
                $ret_estr_vista[$idvistaproc[$orden]]['etiquetafiltro'][0]=$arr1[20];
                $cantcolumnas=$cantcolumnas+1;

                $orden=$orden+1;

            };
            //echo '<div>columna='.$ret_estr_vista[2]['columnavista'][2].'</div>';
            return $ret_estr_vista;
        }else{
            echo '<div class="msg_error">Carga array $estructuravista: No se encontró la vista solicitada</div>';
        };        
    }else{ //$actualizar=false => no se consulta a la base de datos, se devuelven los valores acumulados en el array
        
    };
    
    
}

function nodosorigen_pg($actualizar){
    if ($actualizar==true){ // se contecta con el motor de bd e inicia el proceso de carga del array
        $cadenaconexion = parametros_conexion_pg();
        #echo $cadenaconexion;

        $conexion = pg_connect($cadenaconexion);

        $query='select * from conf.vw_nodos order by idcolumnavistaorigen';
        #echo $query;
        $rs1 = pg_query($conexion, $query);

        $numReg = pg_num_rows($rs1);
        $orden=0;    
        if ($numReg>0){
            /*
            el array obtenido de la consulta se carga según el siguiente esquema
            
                (entidad)
            idcolumnavistaorigen    atributo        valor del atributo
             *      2               'idnodo'                5                        
                    2              'idvistaorigen'          4
                    2              'columnavista'    'iddepartamento'
                    2             'posicioncolumna'         3
                    2          'idcolumnavistadestino'      12
                    2          (....................................)
            la variable $repetido representa una secuencia asignada a todos los nodos 
            en donde se encuentra presente la idcolumnaorigen o idcolumnadestino que se consulta.
            Hay que tener en cuenta que la clave primaria de la tabla de nodos es el idnodo,
            pudiendo existir mas de un nodo que tenga la misma idcolumnaorigen o idcolumnadestino.
            La variable $repetido se incrementa cuando se produce alguna de estas repeticiones.
             */
            $idcolumnavistaorigen_ant=0;
            
            while ($orden<$numReg){
                $arr1=pg_fetch_array($rs1,$orden,PGSQL_BOTH); 
                // $arr1[1] contiene el idcolumnavistaorigen
                if ($idcolumnavistaorigen_ant==$arr1[1]){
                    //incrementa el $repetido
                    //echo '<div>repetido origen incrementa: '.$arr1[1].'</div>';
                    $repetido=$repetido+1;
                }else{
                    //echo '<div>repetido origen igual: '.$arr1[1].'</div>';
                    $repetido=1;
                };
                $ret_estr_nodo[$arr1[1]][$repetido]['idnodo']=$arr1[0];
                $ret_estr_nodo[$arr1[1]][$repetido]['idvistaorigen']=$arr1[2];
                $ret_estr_nodo[$arr1[1]][$repetido]['columnavistaorigen']=$arr1[3];
                $ret_estr_nodo[$arr1[1]][$repetido]['posicioncolumnaorigen']=$arr1[4];
                $ret_estr_nodo[$arr1[1]][$repetido]['idcolumnavistadestino']=$arr1[5];
                $ret_estr_nodo[$arr1[1]][$repetido]['idvistadestino']=$arr1[6];
                $ret_estr_nodo[$arr1[1]][$repetido]['columnavistadestino']=$arr1[7];
                $ret_estr_nodo[$arr1[1]][$repetido]['posicioncolumnadestino']=$arr1[8];
                $ret_estr_nodo[$arr1[1]][$repetido]['observaciones']=$arr1[9];
                $ret_estr_nodo[$arr1[1]][$repetido]['descvistaorigen']=$arr1[10];
                $ret_estr_nodo[$arr1[1]][$repetido]['descvistadestino']=$arr1[11];
                $ret_estr_nodo[$arr1[1]][$repetido]['columnadescripvistaorigen']=$arr1[12];
                $ret_estr_nodo[$arr1[1]][$repetido]['idcolumnadescripvistaorigen']=$arr1[13];
                $ret_estr_nodo[$arr1[1]][$repetido]['etiquetanodoorigen']=$arr1[14];
                $ret_estr_nodo[$arr1[1]][$repetido]['etiquetanododestino']=$arr1[15]; 
                
                $idcolumnavistaorigen_ant = $arr1[1];

                $orden=$orden+1;
                
            };
            //echo '</div></ul>';
            return $ret_estr_nodo;
        }else{
            echo '<div class="msg_error">No se encontraron nodos origen</div>';
        };        
    }else{ //$actualizar=false => no se consulta a la base de datos, se devuelven los valores acumulados en el array
         
    };
    
    
}

function nodosdestino_pg($actualizar){
    
    
    if ($actualizar==true){ // se contecta con el motor de bd e inicia el proceso de carga del array
        $cadenaconexion = parametros_conexion_pg();
        #echo $cadenaconexion;

        $conexion = pg_connect($cadenaconexion);

        $query='select * from conf.vw_nodos order by idcolumnavistadestino';
        #echo $query;
        $rs1 = pg_query($conexion, $query);

        $numReg = pg_num_rows($rs1);
        $orden=0;    
        if ($numReg>0){
            /*
            el array obtenido de la consulta se carga según el siguiente esquema
            
                (entidad)
            idcolumnavistadestino   atributo        valor del atributo
             *      2               'idnodo'                5                        
                    2              'idvistaorigen'          4
                    2              'columnavista'    'iddepartamento'
                    2             'posicioncolumna'         3
                    2          (....................................)
            la variable $repetido representa una secuencia asignada a todos los nodos 
            en donde se encuentra presente la idcolumnaorigen o idcolumnadestino que se consulta.
            Hay que tener en cuenta que la clave primaria de la tabla de nodos es el idnodo,
            pudiendo existir mas de un nodo que tenga la misma idcolumnaorigen o idcolumnadestino.
            La variable $repetido se incrementa cuando se produce alguna de estas repeticiones.
             */
            $idcolumnavistadestino_ant=0;
            
            while ($orden<$numReg){
                $arr1=pg_fetch_array($rs1,$orden,PGSQL_BOTH); 
                // $arr1[5] contiene el idcolumnavistadestino
                if ($idcolumnavistadestino_ant==$arr1[5]){
                    //incrementa el $repetido
                    //echo '<div>repetido destino incrementa: </div>';
                    $repetido=$repetido+1;
                }else{
                    //echo '<div>repetido destino igual: </div>';
                    $repetido=1;
                };
                // $arr1[5] contiene el idcolumnavistadestino
                $ret_estr_nodo[$arr1[5]][$repetido]['idnodo']=$arr1[0];
                $ret_estr_nodo[$arr1[5]][$repetido]['idcolumnavistaorigen']=$arr1[1];
                $ret_estr_nodo[$arr1[5]][$repetido]['idvistaorigen']=$arr1[2];
                $ret_estr_nodo[$arr1[5]][$repetido]['columnavistaorigen']=$arr1[3];
                $ret_estr_nodo[$arr1[5]][$repetido]['posicioncolumnaorigen']=$arr1[4];
                $ret_estr_nodo[$arr1[5]][$repetido]['idvistadestino']=$arr1[6];
                $ret_estr_nodo[$arr1[5]][$repetido]['columnavistadestino']=$arr1[7];
                $ret_estr_nodo[$arr1[5]][$repetido]['posicioncolumnadestino']=$arr1[8];
                $ret_estr_nodo[$arr1[5]][$repetido]['observaciones']=$arr1[9];
                $ret_estr_nodo[$arr1[5]][$repetido]['descvistaorigen']=$arr1[10];
                $ret_estr_nodo[$arr1[5]][$repetido]['descvistadestino']=$arr1[11];
                $ret_estr_nodo[$arr1[5]][$repetido]['columnadescripvistaorigen']=$arr1[12];
                $ret_estr_nodo[$arr1[5]][$repetido]['idcolumnadescripvistaorigen']=$arr1[13];
                $ret_estr_nodo[$arr1[5]][$repetido]['etiquetanodoorigen']=$arr1[14];
                $ret_estr_nodo[$arr1[5]][$repetido]['etiquetanododestino']=$arr1[15];
                
                $idcolumnavistadestino_ant = $arr1[5];
                $orden=$orden+1;
                //echo '<div>idnododestino: '.$ret_estr_nodo[$arr1[5]][$repetido]['idnodo'].' array['.$arr1[5].'],['.$repetido.'][idnodo]</div>';
            };
            //echo '</div></ul>';
            return $ret_estr_nodo;
        }else{
            echo '<div class="msg_error">No se encontraron nodos destino</div>';
        };        
    }else{ //$actualizar=false => no se consulta a la base de datos, se devuelven los valores acumulados en el array
         
    };
    
}

function param_nodosorigen_pg($actualizar){
    if ($actualizar==true){ // se contecta con el motor de bd e inicia el proceso de carga del array
        $cadenaconexion = parametros_conexion_pg();
        

        $conexion = pg_connect($cadenaconexion);

        $query='SELECT  
            n.idnodo,
            v.origendatos,
            v.columnavistaid,
            cv1.columnavista as columnadescripvistaorigen,
            n.observaciones,
            cvd.posicioncolumna as posicioncolumnadestino,
            cvd.idvista as idvistadestino
        FROM
            conf.t_nodos n
            INNER JOIN conf.t_columnasvistas cv ON n.idcolumnavistaorigen=cv.idcolumnavista
            INNER JOIN conf.t_vistas v ON cv.idvista=v.idvista
            INNER JOIN conf.t_columnasvistas cvd ON n.idcolumnavistadestino=cvd.idcolumnavista
            LEFT JOIN conf.t_columnasvistas cv1 ON n.idcolumnadescripvistaorigen=cv1.idcolumnavista
        ORDER BY v.columnavistaid,n.idnodo';
        
        
        //echo '<div>'.$query.'</div>';
        
        $arr1=consulta_arr_pg($query);
        
        $orden=1;   

        while ($arr1[0][$orden]>0){//idnodo es mayor que 0
            $idnodo=$arr1[0][$orden];
            $query='SELECT '.$arr1[2][$orden].','.$arr1[3][$orden].' FROM '.$arr1[1][$orden];
            //echo '<div>********************************'.$query.'******************************</div>';
            $arr2=consulta_arr_pg($query);
            $orden2=1;
            while ($arr2[0][$orden2]>0){
                $clavevista=$arr2[0][$orden2];
                $nodosparam[$idnodo][$clavevista]=$arr2[1][$orden2];
                //echo '<div>idnodo:'.$idnodo.' //clavevista:'.$clavevista.' //valor almacenado:'.$nodosparam[$idnodo][$clavevista].'</div>';
                $orden2=$orden2+1;
            };
            $nodosparam[$idnodo]['observaciones']=$arr1[4][$orden];
            $nodosparam[$idnodo]['posicioncolumnadestino']=$arr1[5][$orden];
            $nodosparam[$idnodo]['idvistadestino']=$arr1[6][$orden];
            $orden=$orden+1;

        };
        //echo '</div></ul>';
        return $nodosparam;
    };
 
}

function agrupamientovistas_pg($actualizar){
    $cadenaconexion = parametros_conexion_pg();
    #echo $cadenaconexion;
    
    $conexion = pg_connect($cadenaconexion);
    
    $query='select * from conf.vw_agrupamientovistas';
    #echo $query;
    $rs1 = pg_query($conexion, $query);
    
    $numReg = pg_num_rows($rs1);
    $orden=1;    
    if ($numReg>0){
        /*echo '<div><ul>';*/
        
        $cant_grupos_vista=0;
        $vista_ant=0;
        while ($orden<=$numReg){
                        
            //$arr1[1] contiene idvista
            $arr1=pg_fetch_array($rs1,$orden-1,PGSQL_BOTH);
            
            if ($arr1[1]!==$vista_ant){ //es una nueva vista, comienza a contar los agrupamientos extistentes para esta vista
                $cant_grupos_vista = 1;
            }else{ //la vista es la misma, pero tiene mas de un agrupamiento
                $cant_grupos_vista=$cant_grupos_vista + 1;
            };
            $retorno[$arr1[1]]['nrogrupos'][0]=$cant_grupos_vista;
            $retorno[$arr1[1]]['idagrupvista'][$cant_grupos_vista]=$arr1[0];
            $retorno[$arr1[1]]['idcolumnavista'][$cant_grupos_vista]=$arr1[2];
            $retorno[$arr1[1]]['columnavista'][$cant_grupos_vista]=$arr1[3];
            $retorno[$arr1[1]]['orden'][$cant_grupos_vista]=$arr1[4];
            //echo '<div>idagrupvista:'.$arr1[0].' // vista: '.$arr1[1].' // idcolumnavista:'.$arr1[2].' // columnavista:'.$arr1[3].' // orden:'.$arr1[4].'</div>';
           
            //echo '<div> vista: '.$arr1[1].' //idagrupvista:'.$retorno[$arr1[1]]['idagrupvista'][$orden].' //idcolumnavista:'.$retorno[$arr1[1]]['idcolumnavista'][$orden]=$arr1[2].' // columnavista:'.$retorno[$arr1[1]]['columnavista'][$orden]=$arr1[3].' // orden:'.$retorno[$arr1[1]]['orden'][$orden]=$arr1[4].'</div>';
            $vista_ant=$arr1[1];
            $orden=$orden+1;
            
        };
        /*echo '</div></ul>';*/
        return $retorno;
    }else{
        echo '<div class="msg_error">No se encontraron agrupamientos</div>';
    };    
    
}

function datos_vista_pg($idvista,$estructuravista,$arr_filtro,$agrupamientovistas,$RDH,$filtrotexto){
    $c=1;
    //echo '<div class="msg">cccccaaaa idvista:'.$idvista.' '.$estructuravista[$idvista]['tipopresentacion'][0].'c'.$c.' '.$estructuravista[$idvista]['cantcolumnas'][0].'</div>';
    //echo '<div>columnas:'.$estructuravista[$idvista]['cantcolumnas'][0].'</div>';
    //echo '<div>columna filtro:'.$estructuravista[$idvista]['columnavistafiltro'][0].'</div>';
    //echo '<div>columna filtrotexto:'.$filtrotexto.'</div>';
    while ($c<=$estructuravista[$idvista]['cantcolumnas'][0]){
        //echo '<div>columna:'.$c.' de '.$estructuravista[$idvista]['cantcolumnas'][0].' columnavista:'.$estructuravista[$idvista]['idcolumnavista'][$c].'</div>';
        if ($cadenacampos==NULL){
            $cadenacampos=$estructuravista[$idvista]['columnavista'][$c];
            
        }else{
            $cadenacampos=$cadenacampos.','.$estructuravista[$idvista]['columnavista'][$c];
        };
        if ($arr_filtro['posicioncolumna'][$c]>0){
            if (strlen($cadenawhere)>0){
                $cadenawhere=$cadenawhere.' AND '.$arr_filtro['columnavista'][$c].'='.$arr_filtro['filtro'][$c];
            }else{
                $cadenawhere=' where '.$arr_filtro['columnavista'][$c].'='.$arr_filtro['filtro'][$c];                    
            };
            //echo '<div >'..'</div>';
        };

        if ($estructuravista[$idvista]['idagrupvista'][$c]>0){ //existen agrupamientos de registros
            if (strlen($cadenaorder)>0){
                $cadenaorder=$cadenaorder.','.$estructuravista[$idvista]['columnavista'][$c];
            }else{
                $cadenaorder='order by '.$estructuravista[$idvista]['columnavista'][$c];
            };
        };        
        
        $c=$c+1;

    };
  
    if (strlen($estructuravista[$idvista]['columnavistafiltro'][0])>0 and strlen($filtrotexto)>0){
        if (strlen($cadenawhere)>0){
            $cadenawhere=$cadenawhere." and sp_ascii(".$estructuravista[$idvista]["columnavistafiltro"][0].") ilike sp_ascii('%".$filtrotexto."%')";
        }else{
            $cadenawhere=" where sp_ascii(".$estructuravista[$idvista]["columnavistafiltro"][0].") ilike sp_ascii('%".$filtrotexto."%')";
        };
    };

    //echo '<div class="info">cadena campos: '.$cadenacampos.' cadena where: '.$cadenawhere.' cadena order: '.$cadenaorder.'</div>';
    $rsvista=consulta_pg('select '.$cadenacampos.' from '.$estructuravista[$idvista]['origendatos'][0].valor_nulo($cadenawhere, ''),NULL,NULL,NULL,NULL,$RDH);
    return $rsvista;
}

function nodosmultiples_pg($actualizar){
    $cadenaconexion = parametros_conexion_pg();
    #echo $cadenaconexion;
    
    $conexion = pg_connect($cadenaconexion);
    
    $query='select * from conf.vw_nodosmultiples order by idvistaorigen,idnodomultiple,posicioncolumnaorigen';
    #echo $query;
    $rs1 = pg_query($conexion, $query);
    
    $numReg = pg_num_rows($rs1);
    $cantcolumnas=1;
    $cantnodos=1;
    $orden=0;    
    if ($numReg>0){
        /*echo '<div><ul>';*/
        
        while ($orden<$numReg){
            
            $arr1=pg_fetch_array($rs1,$orden,PGSQL_BOTH);
            
            $idvistaproc[$orden]=$arr1[8]; //valor del campo idvista para el registro que se está procesando
            $idnodoproc[$orden]=$arr1[0];
            //parametros de la vista: por cada cambio en la vista graba los datos de la vista finalizada
            if (($idvistaproc[$orden]!==$idvistaproc[$orden-1]) and ($idvistaproc[$orden-1]>0) ){ //hubo un cambio en el campo idvista=> se trata de una nueva vista
                $cantcolumnas=1;
                //$cantnodos=1;
                $cantnodos=1;
            }else{
                if (($idnodoproc[$orden]!==$idnodoproc[$orden-1]) and ($idnodoproc[$orden-1]>0)) {
                    $cantcolumnas=1;
                };
            };
            
            /*
            la referencia [$cantnodos] en el array de salida $retorno['....'][$arr1[9]][$cantnodos][0]
            permite recuperar los datos del nodo invocando la secuencia de carga del array, no desde el idnododestino 

            la referencia [$cantcolumnas] en el array de salida $retorno['....'][$arr1[9]][$cantnodos][$cantcolumnas]
            permite recuperar los datos de la columna para un nodo invocando la secuencia de carga del array

             * 
             * 
             */
            $retorno['idvistaorigen'][$arr1[8]][0][0]=$arr1[8];
            $retorno['cantnodosmultiples'][$arr1[8]][0][0]=$cantnodos;
            $retorno['cantcolumnas'][$arr1[8]][$cantnodos][0]=$cantcolumnas; 
            $retorno['idnodomultiple'][$arr1[8]][$cantnodos][0]=$arr1[0];            
            $retorno['etiqueta'][$arr1[8]][$cantnodos][0]=$arr1[1]; 
            $retorno['idvistaorigen'][$arr1[8]][$cantnodos][0]=$arr1[8];            
            $retorno['idvistadestino'][$arr1[8]][$cantnodos][0]=$arr1[9];   
            $retorno['tipoactivacionnodomul'][$arr1[8]][$cantnodos][0]=$arr1[10]; 
            $retorno['columnaactivanodo'][$arr1[8]][$cantnodos][0]=$arr1[11]; 
            $retorno['idcolumnavistaorigen'][$arr1[8]][$cantnodos][$cantcolumnas]=$arr1[2];
            $retorno['idcolumnavistadestino'][$arr1[8]][$cantnodos][$cantcolumnas]=$arr1[3];            
            $retorno['columnavistaorigen'][$arr1[8]][$cantnodos][$cantcolumnas]=$arr1[4];
            $retorno['columnavistadestino'][$arr1[8]][$cantnodos][$cantcolumnas]=$arr1[5];
            $retorno['posicioncolumnaorigen'][$arr1[8]][$cantnodos][$cantcolumnas]=$arr1[6];
            $retorno['posicioncolumnadestino'][$arr1[8]][$cantnodos][$cantcolumnas]=$arr1[7];
            
            //echo '<div>idvista '.$arr1[8].'/nronodo '.$cantnodos.'/nrocolumna '.$cantcolumnas.'-- columna:'.$retorno['columnavistaorigen'][$arr1[8]][$cantnodos][$cantcolumnas].'</div>';
            //echo '<div> nodos multiples '.$idvistaproc[$orden].' </div>';
            
            $cantcolumnas=$cantcolumnas+1;
            $orden=$orden+1;
           
                       
        };
        /*echo '</div></ul>';*/
        return $retorno;
    }else{
        //echo '<div class="msg_error">No se encontró la vista solicitada</div>';
    };    
    
}


