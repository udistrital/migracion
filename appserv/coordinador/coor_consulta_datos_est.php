<?php
require_once('dir_relativo.cfg');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
if (!$_REQUEST['tipo']) {
    $_REQUEST['tipo'] = $_SESSION['usuario_nivel'];
}
if($_REQUEST['tipo']==110){
    fu_tipo_user(110);
}elseif($_REQUEST['tipo']==114){
    fu_tipo_user(114);
}else{
fu_tipo_user(4);
}
fu_cabezote("CONSULTA DE ESTUDIANTES");

$enlace=enlaceVideoTutorial();

?>
<HTML>
<HEAD>
<TITLE>Oficina Asesora de Sistemas</TITLE>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY onLoad="this.document.doc.estcod.focus()">
<FORM method="post" NAME='doc' ACTION="<? $_SERVER['PHP_SELF'] ?>">
<table width="700" border="0" align="center"><tr>
  <td align="right" width="35%">C&oacute;digo:</td>
  <td align="right" width="100"><input name='estcod' type='text' size='15' style="text-align:right" onKeyPress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;"></td>
    <td><input type='Submit' value='Consultar Estudiante' title="Ejecutar la consulta" style="cursor:pointer"></td>
  </tr>
  <tr><td colspan="3"><br><br><? echo $enlace;?></td></tr>
</table>
</FORM>
<?php
if($_REQUEST['estcod']){
    
  print'<div align="center" class="Estilo10">Haga clic en el bot&oacute;n correspondiente a la informaci&oacute;n que desea ver.</div>
  <table width="90%" border="0" align="center">
  <tr class="fondo">
    <td align="center">
	<form name="est" method="post" action="coor_actualiza_datos_est.php" target="inferior">
      <input type="submit" name="Submit" value="Datos B&aacute;sicos" class="button" style="cursor:pointer; width:170">		
      <input name="estcod" type="hidden" value="'.$_REQUEST['estcod'].'">
      <input name="tipo" type="hidden" value="'.$_REQUEST['tipo'].'">
    </form>
	</td>
    <td align="center">
	
	<form name="doci" method="post" action="coor_est_asi_ins.php" target="inferior">
        <input type="submit" name="Submit" value="Registro de Asignaturas" class="button" style="cursor:pointer; width:170">
		<input name="estcod" type="hidden" value="'.$_REQUEST['estcod'].'">
    </form>
	</td>
	<td align="center">
	<form name="docpi" method="post" action="coor_est_semaforo.php" target="inferior">
        <input type="submit" name="Submit" value="Plan de Estudio" class="button" style="cursor:pointer; width:170">
		<input name="estcod" type="hidden" value="'.$_REQUEST['estcod'].'">
    </form>
	</td>
  </tr>
  </table>';
} 
?>  
</BODY>
</HTML>

<?
function enlaceVideoTutorial(){
    $htmlEnlace="<a href='https://drive.google.com/file/d/0B8GSpeq64bxIeFJUazd2alBfa2M/preview' target=_blank>Ver video tutorial de Datos Básicos</a>";
    return $htmlEnlace;
}
?>