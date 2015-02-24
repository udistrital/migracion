<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('valida_http_referer.php');
//require_once('valida_estudiante_activo.php');
require_once('valida_estudiante_nuevo.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

fu_tipo_user(51);


	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
</HEAD>

<?php

fu_cabezote("ADICIONAR Y CANCELAR ASIGNATURAS");
$estcod = $_SESSION['usuario_login'];

$b_insrow ='<IMG SRC='.dir_img.'b_insrow.png height="15" width="17" alt="Adicionar asignatura" border="0">';
$b_edit ='<IMG SRC='.dir_img.'b_edit.png height="15" width="17" alt="Cambiar de Grupo" border="0">';
$b_deltbl='<IMG SRC='.dir_img.'b_deltbl.png height="15" width="17" alt="Cancelar asignatura" border="0">';

$estados = "'A','B','H','J','L','T','V'";
$consulta = "SELECT est_cod,
				  est_nombre,
				  est_nro_iden,
				  cra_cod,
				  cra_nombre,
				  TRUNC(Fa_Promedio_Nota(est_cod),2),
				  asi_cod,
				  asi_nombre,
				  ins_gr
				 FROM ACCRA, ACEST,ACINS, ACASI,ACASPERI
				WHERE cra_cod = est_cra_cod
				  AND est_cod     = ins_est_cod
				  AND est_cra_cod = ins_cra_cod
				  AND est_estado_est IN($estados)
				  AND asi_cod     = ins_asi_cod
				  AND ape_ano     = ins_ano
				  AND ape_per     = ins_per
				  AND ape_estado  = 'A'
				  AND ins_estado  = 'A'
				  AND est_cod = ".$_SESSION['usuario_login']."
			order by ins_asi_cod";
			
$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");			
			


	$url = explode("?",$_SERVER['HTTP_REFERER']);
	$redir = $url[1];
	$mensaje = explode("=",$redir);
	$opcion = $mensaje[1];
	

	if(isset($opcion)){	
		
		
		switch($opcion){
			case "100":
				$salida="El registro ha sido exitoso";
			break;
			case "101":
				$salida="No se puede actualizar, el registro presenta cruce";
			break;
			case "107":
				$salida="No puede cancelar una asignatura que fue reprobada";
			break;	
			case "108":
				$salida="El registro se cancelo exitosamente";
			break;						
			case "105":
			case "106":			
				$salida="No hay cupos disponibles";
			break;		
		}
		echo "<br><center><div class='aviso_mensaje'>".$salida."</div><br></center>";
		unset($mensaje);
		unset($salida);
	}
	


    
?>
<!-- <div align="center"><a href="#" onClick="javascript:popUpWindow('est_graf_addcan.php', 'no', 100, 100, 550, 201)">Gráfica del Proceso</a></div> -->
  
 <br> <table border="1" width="90%" align="center" cellspacing="0" cellpadding="1">
    <tr class="tr">
	  <td align="center">C&oacute;digo</td>
      <td align="center">Nombre De La Asignatura</td>
      <td align="center">Grupo</td>
      <td colspan="2" align="center">Gestión</td>
    </tr>
<?php

$i=0;
while(isset($registro[$i][0])){
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td align="right">
		<a href="est_asi_hor.php?asicod='.$registro[$i][6].'&asigr='.$registro[$i][8].'" target="inferior" title="Horario de la asignatura">'.$registro[$i][6].'</a></td>
  	  <td align="left">'.$registro[$i][7].'</td>
  	  <td align="center">'.$registro[$i][8].'</td>

   	 <td align="center">
	<a href="est_borra_asi.php?asicod='.$registro[$i][6].'&asigru='.$registro[$i][8].'" target="inferior">'.$b_deltbl.'</a></td></tr>';
	$i++;
}



print'</table>
<div align="center"><a href="est_fre_adicion.php" target="principal" title="Adicionar asignatura"><b>ADICIONAR ASIGNATURA</b>'.$b_insrow.'</a></div>
<p></p>
<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td width="50%" align="center"><a href="#" onClick="javascript:popUpWindow(\'../generales/inf_recomendacion.htm\', \'no\', 100, 100, 650, 430)">Recomendaciones</a></td>
    <td width="50%" align="center"><a href="#" onClick="javascript:popUpWindow(\'add_can.php\', \'no\', 100, 100, 350, 133)">Fechas de Adición y Cancelación</a></td>
  </tr>
</table>';


	


	



?>
</BODY>
</HTML>
