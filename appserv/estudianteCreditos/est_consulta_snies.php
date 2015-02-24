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
$consulta1=new form();
$consulta2=new form();
$consulta3=new form();
$consulta4=new form();
$consulta5=new form();
$consulta6=new form();
$consulta7=new form();
$consulta8=new form();
$consulta9=new form();
$consulta10=new form();
$consulta11=new form();
$consulta12=new form();
$consulta13=new form();
$consulta14=new form();
$consulta15=new form();
$consulta16=new form();
$consulta17=new form();
$consulta18=new form();
$consulta19=new form();
$consulta20=new form();
$consulta21=new form();
$consulta22=new form();
$consulta23=new form();
$consulta24=new form();
$consulta25=new form();
$consulta26=new form();
$consulta27=new form();
$consulta26=new form();
$consulta28=new form();
$consulta29=new form();
$consulta30=new form();
$consulta31=new form();
$consulta32=new form();

//Armamos el formulario y traemos los resultados de las consultas a la bases de datos
$consulta1->setTabla(SQL("Estudiantes"));
$consulta1->agregarcampo('','result' ,'EST_NOMBRE');
$consulta2->setTabla(SQL("acestotr"));
$consulta2->agregarcampo('','result' ,1);
$consulta3->setTabla(SQL("ciudadEstudiante"));
$consulta3->agregarcampo('','result' ,2);
$consulta4->setTabla(SQL("acestotr"));
$consulta4->agregarcampo('','result' ,4);
$consulta5->setTabla(SQL("acestotr"));
$consulta5->agregarcampo('','result' ,5);
$consulta6->setTabla(SQL("ciudadEstudiante"));
$consulta6->agregarcampo('','result' ,2);
$consulta7->setTabla(SQL("acestotr"));
$consulta7->agregarcampo('','result' ,2);
$consulta8->setTabla(SQL("acestotr"));
$consulta8->agregarcampo('','result' ,7);
$consulta9->setTabla(SQL("acestotr"));
$consulta9->agregarcampo('','result' ,8);
$consulta10->setTabla(SQL("acestotr"));
$consulta10->agregarcampo('','result' ,9);
$consulta11->setTabla(SQL("acestotr"));
$consulta11->agregarcampo('','result' ,10);
$consulta12->setTabla(SQL("acestotr"));
$consulta12->agregarcampo('','result' ,11);
$consulta13->setTabla(SQL("acestotr"));
$consulta13->agregarcampo('','result' ,12);
$consulta14->setTabla(SQL("acestotr"));
$consulta14->agregarcampo('','result' ,13);
$consulta15->setTabla(SQL("acestotr"));
$consulta15->agregarcampo('','result' ,14);
$consulta16->setTabla(SQL("acestotr"));
$consulta16->agregarcampo('','result' ,15);
$consulta17->setTabla(SQL("acestotr"));
$consulta17->agregarcampo('','result' ,16);
$consulta18->setTabla(SQL("acestotr"));
$consulta18->agregarcampo('','result' ,17);
$consulta19->setTabla(SQL("acestotr"));
$consulta19->agregarcampo('','result' ,18);
$consulta20->setTabla(SQL("acestotr"));
$consulta20->agregarcampo('','result' ,19);
$consulta21->setTabla(SQL("acestotr"));
$consulta21->agregarcampo('','result' ,20);
$consulta22->setTabla(SQL("acestotr"));
$consulta22->agregarcampo('','result' ,21);
$consulta23->setTabla(SQL("acestotr"));
$consulta23->agregarcampo('','result' ,22);
$consulta24->setTabla(SQL("acestotr"));
$consulta24->agregarcampo('','result' ,23);
$consulta25->setTabla(SQL("acestotr"));
$consulta25->agregarcampo('','result' ,24);
$consulta26->setTabla(SQL("acestotr"));
$consulta26->agregarcampo('','result' ,25);
$consulta27->setTabla(SQL("acestotr"));
$consulta27->agregarcampo('','result' ,26);
$consulta28->setTabla(SQL("acestotr"));
$consulta28->agregarcampo('','result' ,27);
$consulta29->setTabla(SQL("acestotr"));
$consulta29->agregarcampo('','result' ,28);
$consulta30->setTabla(SQL("acestotr"));
$consulta30->agregarcampo('','result' ,29);
$consulta31->setTabla(SQL("acestotr"));
$consulta31->agregarcampo('','result' ,30);
$consulta32->setTabla(SQL("acestotr"));
$consulta32->agregarcampo('','result' ,31);
	//Imprimimos el formulario y le podnemos las etiquetas HTML que deseemos.
	$salida.="<center><h2>ACTUALIZACI&Oacute;N DE DATOS SNIES</h2></center>";
	$salida.= "<center>";
    $salida.= "<div id='registro'>";
	$salida.= "<table class='bloquecentralcuerpo' style='width:350px; backgrund-color:#FFFFFF;'>"; 
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Nombre:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta1->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>C&oacute;digo:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta2->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Fecha de nacimiento:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta7->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Lugar de nacimiento:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta3->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Sexo:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta4->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Estado civil:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta5->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Lugar de donde proviene:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta6->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Vive con:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta8->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Tipo de vivienda:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta9->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Número SNP:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta10->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Puntos SNP:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta11->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Tipo bachillerato:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta12->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Tipo colegio:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta13->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Trabaja:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta14->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Vive Padre:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta15->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Educaci&oacute;n del Padre:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta16->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Trabaja Padre:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta17->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Vive Madre:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta18->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Educaci&oacute;n de la Madre:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta19->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Trabaja Madre:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta20->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Vive Conyuge:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta21->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Educaci&oacute;n del(a) Conyuge:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta22->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Trabaja Conyuge:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta23->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Asp Cr&eacute;dito:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta24->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Forma de ingreso:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta25->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Jornada Colegio:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta26->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Valor matr&iacute;cula Colegio:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta27->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Costea estudios:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta28->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Ingresos:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta29->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Grupo:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta30->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Sexo Colegio:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta31->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	  $salida.= "<tr>";
		$salida.= "<td WIDTH='64' CLASS='off'>Estrato social:</td>";
		$salida.= "<td WIDTH='64' CLASS='off'>".$consulta32->tohtml($configuracion)."</td>";
	  $salida.= "</tr>";
	  
	$salida.= "</table>";
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
