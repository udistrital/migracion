<?PHP
require_once('../clase/forms.class.php');
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_consulta_datos_est.php');
fu_tipo_user(51);
?>
<HTML>
<HEAD>
<TITLE>Estudiantes</TITLE>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?php
//instanciamos la clase
$html1 = new form();
$consulta=new form();
$html2 = new form();
$html3 = new form();
$html4 = new form();
$select1 = new form();
$select2 = new form();
$select3 = new form();
$submit = new form();
//Armamos el formulario y traemos los resultados de las consultas a la bases de datos
$consulta->setTabla(SQL("Estudiantes"));
$consulta->agregarcampo('nombre','result' ,'EST_NOMBRE',2,'','35',1,'','');

$html1->setTabla(SQL("Estudiantes"));
$html1->agregarcampo('nombre','text' ,'EST_NOMBRE',2,'','35',1,'','');									   

$html2->setTabla(SQL("Estudiantes"));
$html2->agregarcampo('codigo','text','EST_COD',2,'','35',1,'','');

$html3->setTabla(SQL("acestotr"));
$html3->agregarcampo('fec_nac','text','EOT_FECHA_NAC','EOT_FECHA_NAC','','35',1,'','');

$select1->setTabla(SQL("ciudades"));
$select1->setSelected(SQL("ciudadEstudiante"),1,2,'','35',1,'','');
$select1->agregarcampo('ciudad_nac','select',1,2,'','',1,'','');

$html4->setTabla(SQL("Estudiantes"));
$html4->agregarcampo('direccion','text',4,4,'','35',1,'','');

$select2->setTabla(SQL("ciudades"));
$select2->setSelected(SQL("ciudadEstudiante"),1,2,'','35',1,'','');
$select2->agregarcampo('ciudad','select',1,2,'','',1,'','');

$select3->setSelected(SQL("acestotr"),4,4);
$select3->setArray(SQL("M"),0,0);
$select3->agregarcampo('sexo','select',1,2,'','',1,'','');

$submit->agregarcampo('Actualizar','submit','','','','','','','');

	//Imprimimos el formulario y le podnemos las etiquetas HTML que deseemos.
	$salida.= "<center>";
    $salida.= "<div id='registro'>";
	$salida.="<br><center>:::::::::::::::::::::::::::::::::::::::::::::::::::::</center><br>";
	$salida.="<form action='prueba.php' name='form' id='form' enctype='multipart/form-data'  method='post'>"; 
	$salida.= "<table class='bloquecentralcuerpo' style='width:158px; backgrund-color:#FFFFFF;'>"; 
	  
	  $salida.= "<tr>";
		$salida.= "<td>Nombre:</td>";
		$salida.="<td>".OCIResult($consulta,1)."</td>" ;
	  $salida.= "</tr>";
	
	  $salida.= "<tr>";
		$salida.= "<td>Código:</td>";
		$salida.= "<td>".$_SESSION['usuario_login']."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Prueba:</td>";
		$salida.= "<td>".$consulta->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  
	  $salida.= "<tr>";
		$salida.= "<td>Fecha de nacimiento:</td>";
		$salida.= "<td>".$html3->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	
	  $salida.= "<tr>";
		$salida.= "<td>Lugar de nacimiento:</td>";
		$salida.= "<td>".$select1->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Sexo:</td>";
		$salida.= "<td>".$select3->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Lug:</td>";
		$salida.="<td>".$html4->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Ciudad:</td>";
		$salida.= "<td>".$select2->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>";
		$salida.= $submit->tohtml($configuracion);
		$salida.= "</td>";	
	  $salida.= "</tr>";
	  
	$salida.= "</table>";
	$salida.="</form>";
	$salida.= "</div>";
    $salida.= "</center>";
	echo $salida;     
//Función para realizar las consultas a las bases de datos	
function SQL($opcion="")
{
	switch($opcion){
		case "Estudiantes":
		$cadena_sql = "SELECT * 
					   FROM ACEST
 					   WHERE EST_COD =".$_SESSION['usuario_login']."";
		break;
		
		case "acestotr":
		$cadena_sql = "SELECT * 
					   FROM ACESTOTR
 					   WHERE EOT_COD =".$_SESSION['usuario_login']."";
		break;
		case "ciudades":
		$cadena_sql="SELECT lug_cod,lug_nombre FROM gelugar ORDER BY lug_nombre";
		break;
		case "ciudadEstudiante":
		$cadena_sql="SELECT lug_cod,lug_nombre,EOT_COD_LUG_NAC
		            FROM gelugar,ACESTOTR
		            WHERE LUG_COD = EOT_COD_LUG_NAC
                    AND EOT_COD = ".$_SESSION['usuario_login']."";
		break;
		case "M":
		//$cadena_sql="M";
		$array="M";
		break;
    }
//Retornamos la cadena de las consultas
return $cadena_sql;
}
?>
</BODY>
</HTML>
