<?php
#***********FUNCION valor_nulo            **************************************************************
function valor_nulo ($valor_evaluado,$valor_siesnulo){
	if ($valor_evaluado == NULL){
		return $valor_siesnulo;
	} else {
		return $valor_evaluado;
	}
	
}
function tabulaciones($cantidad_tabs){
    $cadenatab='    ';
    $c=0;
    while ($c<$cantidad_tabs){
        $cadenaretorno=$cadenaretorno.$cadenatab;
        $c=$c+1;
    };
    return $cadenaretorno;
}
function saltolinea($cantidad_salto){
    $cadenasalto='
';
    $c=0;
    while ($c<$cantidad_salto){
        $cadenaretorno=$cadenaretorno.$cadenasalto;
        $c=$c+1;
    };
    return $cadenaretorno;
}

function prueba_pg(){

#***********FUNCION consulta registro            **************************************************************
$user = "sa";
$password = "1";
$dbname = "resultados_elec";
$port = "5432";
$host = "web-jep";

$cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

echo $cadenaConexion;

$conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
echo "<h3>Conexion Exitosa PHP - PostgreSQL</h3><hr><br>";

$query = "select idpartido, nombre, nropartido from ele.t_partidos";

$resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

$numReg = pg_num_rows($resultado);

if($numReg>0){
echo "<table border='1' align='center'>
<tr bgcolor='skyblue'>
<th>idpartido</th>
<th>nombre</th>
<th>nropartido</th></tr>";
while ($fila=pg_fetch_array($resultado)) {
echo "<tr><td>".$fila['idpartido']."</td>";
echo "<td>".$fila['nombre']."</td>";
echo "<td>".$fila['nropartido']."</td></tr>";
}
                echo "</table>";
}else{
                echo "No hay Registros";
}

pg_close($conexion);

}

function cliente_ip_mac() {

$ip = $_SERVER['REMOTE_ADDR'];
$mac = shell_exec('arp -a '.$ip);

//Working fine when sample client IP is provided...
//$mac = shell_exec('arp -a 192.168.0.107'); 

//$findme = "Physical";
$pos = strpos($mac, $ip);
$lenip = strlen($ip);
$macp = substr($mac,($pos+$lenip+10),17);


// having it
$retorno['ip']=$ip; 
$retorno['mac']=$macp;
$retorno['posicion']=$pos;
$retorno['longip']=$lenip;
$retorno['string']=shell_exec('arp -a '.$ip);
return $retorno;

}




?>