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
<script language="JavaScript" src="../script/blo_campo.js"></script>
</HEAD>
<BODY>
<?php
//instanciamos la clases
$html1 = new form();
$html2 = new form();
$html3 = new form();
$html4 = new form();
$html5 = new form();
$html6 = new form();
$html7 = new form();
//$html8 = new form();
$html9 = new form();
$html10 = new form();
$html11 = new form();
$html12 = new form();
$html13 = new form();

$select1 = new form();
$select2 = new form();
$select3 = new form();
$select4 = new form();
$select5 = new form();
$select6 = new form();
$select7 = new form();
$select8 = new form();
$select9 = new form();
$select10 = new form();
$select11 = new form();
$select12 = new form();
$select13 = new form();
$select14 = new form();
$select15 = new form();
$select16 = new form();
$select17 = new form();
$select18 = new form();
$select19 = new form();
$select20 = new form();
$select21 = new form();

//$submit = new form();
//Armamos el formulario y traemos los resultados de las consultas de las bases de datos
$html1->setTabla(SQL("Estudiantes"));
$html1->agregarcampo('nombre','text' ,'EST_NOMBRE',2,'','35',1,'','');									   

$html2->setTabla(SQL("Estudiantes"));
$html2->agregarcampo('codigo','text','EST_COD',2,'','35',1,'','');

$html3->setTabla(SQL("acestotr"));
$html3->agregarcampo('fec_nac','text',2,2,'','10',1,'','');

$select1->setTabla(SQL("ciudades"));
$select1->setSelected(SQL("ciudadEstudiante"),1,2,'','35',1,'','');
$select1->agregarcampo('ciudad_nac','select',1,2,'','',1,'','');

$select2->setSelected(SQL("acestotr"),4,4);
$select2->agregarcampo('sexo','select',array('M','F'),array('M','F'),'','',1,'','');

$select3->setSelected(SQL("acestotr"),5,5);
$select3->agregarcampo('est_civil','select',array('1','2','3','4'),array('soltero','casado','separado','viudo'),'','',1,'','');

$select4->setTabla(SQL("ciudades"));
$select4->setSelected(SQL("lugarproviene"),1,2,'','35',1,'','');
$select4->agregarcampo('ciudad_prov','select',1,2,'','',1,'','');

$html4->setTabla(SQL("acestotr"));
$html4->agregarcampo('vive_con','text',7,7,'','35',1,'','');

$html5->setTabla(SQL("acestotr"));
$html5->agregarcampo('tipo_vivienda','text',8,8,'','35',1,'','');

$html6->setTabla(SQL("acestotr"));
$html6->agregarcampo('tipo_bach','text',11,11,'','35',1,'','');

$select5->setSelected(SQL("acestotr"),13,13);
$select5->agregarcampo('trabaja','select',array('S','N'),array('S','N'),'','',1,'','');

$select6->setSelected(SQL("acestotr"),14,14);
$select6->agregarcampo('vive_padre','select',array('S','N'),array('S','N'),'','',1,'','');

$select7->setSelected(SQL("acestotr"),16,16);
$select7->agregarcampo('trabaja_padre','select',array('S','N'),array('S','N'),'','',1,'','');

$select8->setSelected(SQL("acestotr"),15,15);
$select8->agregarcampo('ed_padre','select',array('S','N'),array('S','N'),'','',1,'','');

$select9->setSelected(SQL("acestotr"),17,17);
$select9->agregarcampo('vive_madre','select',array('S','N'),array('S','N'),'','',1,'','');

$select10->setSelected(SQL("acestotr"),18,18);
$select10->agregarcampo('ed_madre','select',array('S','N'),array('S','N'),'','',1,'','');

$select11->setSelected(SQL("acestotr"),19,19);
$select11->agregarcampo('trabaja_madre','select',array('S','N'),array('S','N'),'','',1,'','');

$select12->setSelected(SQL("acestotr"),20,20);
$select12->agregarcampo('vive_conyuge','select',array('S','N'),array('S','N'),'','',1,'','');

$select13->setSelected(SQL("acestotr"),21,21);
$select13->agregarcampo('ed_conyuge','select',array('S','N'),array('S','N'),'','',1,'','');

$select14->setSelected(SQL("acestotr"),22,22);
$select14->agregarcampo('trabaja_conyuge','select',array('S','N'),array('S','N'),'','',1,'','');

$select15->setSelected(SQL("acestotr"),27,27);
$select15->agregarcampo('costea_estudios','select',array('S','N'),array('S','N'),'','',1,'','');

$html7->setTabla(SQL("acestotr"));
$html7->agregarcampo('ingresos_costea','text',28,28,'','35',1,'','');

$select16->setSelected(SQL("acestotr"),35,35);
$select16->agregarcampo('arural','select',array('S','N'),array('S','N'),'','',1,'','');

$html9->setTabla(SQL("acestotr"));
$html9->agregarcampo('email','text',36,36,'','35',1,'','');

$html10->setTabla(SQL("acestotr"));
$html10->agregarcampo('tiposangre','text',37,37,'','3',1,'','');

$html11->setTabla(SQL("acestotr"));
$html11->agregarcampo('rh','text',38,38,'','3',1,'','');

$select17->setSelected(SQL("acestotr"),40,40);
$select17->agregarcampo('grupo_vulnera','select',array('01','02'),array('SI','NO'),'','',1,'','');

$html12->setTabla(SQL("acestotr"));
$html12->agregarcampo('grupo_etnico','text',41,41,'','35',1,'','');

$html13->setTabla(SQL("acestotr"));
$html13->agregarcampo('resguardo','text',42,42,'','35',1,'','');

$select18->setSelected(SQL("acestotr"),43,43);
$select18->agregarcampo('victima','select',array('01','02'),array('SI','NO'),'','',1,'','');

$select19->setTabla(SQL("ciudades"));
$select19->setSelected(SQL("lugarexpulsion"),1,2,'','35',1,'','');
$select19->agregarcampo('lugar_expul','select',1,2,'','',1,'','');

$select20->setSelected(SQL("acestotr"),45,45);
$select20->agregarcampo('prov_sec_priv','select',array('01','02'),array('SI','NO'),'','',1,'','');

$select21->setSelected(SQL("acestotr"),46,46);
$select21->agregarcampo('especiales','select',array('01','02'),array('SI','NO'),'','',1,'','');
//$submit->agregarItemBusqueda('Actualizar','submit');

	//Imprimimos el formulario y le podnemos las etiquetas HTML .
	$salida= "<center>";
	$salida.= "<div id='registro'>";
	$salida.="<H2>:.ACTUALIZACI&Oacute;N DE DATOS SNIES.:<H2>";
	$salida.="<form action='prueba1.php' name='form' id='form' method='get'>"; 
	$salida.= "<table class='bloquecentralcuerpo' style='width:800px; backgrund-color:#FFFFFF;'>"; 
	  
	  $salida.= "<tr>";
		$salida.= "<td>Nombre:</td>";
		$salida.="<td>".OCIResult($consulta,1)."</td>" ;
	  //$salida.= "</tr>";
		
	  //$salida.= "<tr>";
		$salida.= "<td>C�digo:</td>";
		$salida.= "<td>".$_SESSION['usuario_login']."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Fecha de nacimiento:</td>";
		$salida.= "<td>".$html3->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	
	  //$salida.= "<tr>";
		$salida.= "<td>Lugar de nacimiento:</td>";
		$salida.= "<td>".$select1->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Sexo:</td>";
		$salida.= "<td>".$select2->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>Estado Civil:</td>";
		$salida.= "<td>".$select3->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Ciudad de donde proviene:</td>";
		$salida.= "<td>".$select4->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>Vive con:</td>";
		$salida.="<td>".$html4->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Tipo de vivienda:</td>";
		$salida.="<td>".$html5->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>Trabaja:</td>";
		$salida.="<td>".$select5->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Vive el Padre:</td>";
		$salida.="<td>".$select6->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>Nivel educativo del Padre:</td>";
		$salida.="<td>".$select8->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Trabaja el Padre:</td>";
		$salida.="<td>".$select7->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>Vive la Madre:</td>";
		$salida.="<td>".$select9->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Nivel educativo de la Madre:</td>";
		$salida.="<td>".$select10->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>Trabaja la Madre:</td>";
		$salida.="<td>".$select11->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Vive Conyuge:</td>";
		$salida.="<td>".$select12->tohtml($configuracion)."</td>";
	  //$s2alida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>Nivel educativo Conyuge:</td>";
		$salida.="<td>".$select13->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Trabaja Conyuge:</td>";
		$salida.="<td>".$select14->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>Costea estudios:</td>";
		$salida.="<td>".$select15->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Ingresos:</td>";
		$salida.= "<td>".$html7->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>A�o rural:</td>";
		$salida.="<td>".$select16->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>E-mail:</td>";
		$salida.= "<td>".$html9->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Tipo de sangre:</td>";
		$salida.= "<td>".$html10->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>RH:</td>";
		$salida.= "<td>".$html11->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Grupo vulnerable:</td>";
		$salida.="<td>".$select17->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>Grupo Etnico:</td>";
		$salida.="<td>".$html12->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Resguardo:</td>";
		$salida.="<td>".$html13->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>Victima conflicto armado:</td>";
		$salida.="<td>".$select18->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td>Lugar de expulsi�n:</td>";
		$salida.="<td>".$select19->tohtml($configuracion)."</td>";
	  //$salida.= "</tr>";
	  
	  //$salida.= "<tr>";
		$salida.= "<td>Proviene sector privado:</td>";
		$salida.="<td>".$select20->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	   $salida.= "<tr>";
		$salida.= "<td>Condici�n especial:</td>";
		$salida.="<td>".$select21->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td colspan='2'><div align='center'>";
		$salida.= "<input type='submit' value='Actualizar datos!'>"; 
		$salida.= "</div></td>";	
	  $salida.= "</tr>";
	  
	$salida.= "</table>";
	$salida.="</form>";
	$salida.= "</div>";
    $salida.= "</center>";
	echo $salida;     
//C�digo para realizar los querys o consultas a las bases de datos.	
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
		case "lugarproviene":
		$cadena_sql="SELECT lug_cod,lug_nombre,EOT_LUG_COD_PROVIENE
		            FROM gelugar,ACESTOTR
		            WHERE LUG_COD = EOT_LUG_COD_PROVIENE
                    AND EOT_COD = ".$_SESSION['usuario_login']."";
		break;
				case "lugarexpulsion":
		$cadena_sql="SELECT lug_cod,lug_nombre,EOT_LUGAR_EXPUL
		            FROM gelugar,ACESTOTR
		            WHERE LUG_COD = EOT_LUGAR_EXPUL
                    AND EOT_COD = ".$_SESSION['usuario_login']."";
		break;
		
    }
//Retornamos la cadena de las consultas
return $cadena_sql;
}
?>
</BODY>
</HTML>
