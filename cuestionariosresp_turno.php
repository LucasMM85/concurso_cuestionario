<!DOCTYPE html>
<html xmlns="dgubian">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sistema de Evaluación Web - Ministerio Fiscal V 1.0</title>
<!-- Seccion "A" -->

<?php
    
    require_once("php/db/funciones_postgres.php");
    
 
?>
<style type="text/css">
#cuestionariosrespondidos{
    border:1px solid black;

}

td{
    border:1px solid black;
}

</style>
</head>

 


<body >
    
    <h1>Sistema de Evaluación Web - Ministerio Fiscal V 1.0</h1>
    <h2>Acceso a Reimpresión de Exámenes</h2>

    <table id="cuestionariosrespondidos">
        <tr>
            <th>
                Nro. Exámen
            </th>
            <th>
                Cuestionario
            </th>
            <th>
                Apellidos, Nombres
            </th>
            <th>
                Documento
            </th>
            <th>
                Estado del exámen
            </th>            
        </tr>
<?php 


$cadenadiv='';

$rec= consulta_arr_pg('select * from web.vw_form_cuestionariosresp_turno');

$c=1;

while ($c<=$rec['cantregistros'][0]){

    $cadenadiv=$cadenadiv.'<tr>';
    $cadenadiv=$cadenadiv.'<td>';
    $cadenadiv=$cadenadiv.'<a href="php/funciones/reporteCuestionario.php?corregirIdCuestionario='.$rec['idcuestionariorespondido'][$c].'">'.$rec['idcuestionariorespondido'][$c].'</a>';
    $cadenadiv=$cadenadiv.'</td>';

    $cadenadiv=$cadenadiv.'<td>';
    $cadenadiv=$cadenadiv.$rec['cuestionario'][$c];
    $cadenadiv=$cadenadiv.'</td>';
    
    $cadenadiv=$cadenadiv.'<td>';
    $cadenadiv=$cadenadiv.$rec['persona'][$c];
    $cadenadiv=$cadenadiv.'</td>';
    
    $cadenadiv=$cadenadiv.'<td>';
    $cadenadiv=$cadenadiv.$rec['documento'][$c];
    $cadenadiv=$cadenadiv.'</td>';

    $cadenadiv=$cadenadiv.'<td>';
    $cadenadiv=$cadenadiv.$rec['estadocuestionariorespondido'][$c];
    $cadenadiv=$cadenadiv.'</td>';
            
    $cadenadiv=$cadenadiv.'</tr>';    
    $c=$c+1;
};
      
echo $cadenadiv;  

?>
</table>
</body></html>

