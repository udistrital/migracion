<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once('valida_http_referer.php');
require_once(dir_conect.'cierra_bd.php');
require_once('valida_adicion.php');
require_once('valida_estudiante_activo.php');
require_once('valida_estudiante_nuevo.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
fu_tipo_user(51);
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</HEAD>
<BODY topmargin="2">

<?php
ob_start();

if($_GET['asicod'] == "") die("<center><font face='Tahoma' size='3' color='#FF0000'><b>No tiene asignaturas inscritas.</font></center>");

$asicod = $_GET['asicod'];
$asigru_ant = $_GET['asigru_ant'];

require_once(dir_script.'NombreAsignatura.php');
$cod_consul = "SELECT EMH_ASI_COD, 
	   				  EMH_NRO, 
				      EMH_CUPO, 
				      EMH_HORARIO 
			     FROM v_acestmathorario
			    WHERE EMH_ASI_COD = $asicod
			      AND EHM_CRA_COD = ".$_SESSION['carrera']."
			 ORDER BY 2";
$consulta = OCIParse($oci_conecta,$cod_consul);
OCIExecute($consulta, OCI_DEFAULT) or die(ora_errorcode());
$row = OCIFetch($consulta);

echo'<div align="center">
	 <table border="0" width="515" cellspacing="0" cellpadding="2">
	 <tr class="tr"><td width="500" colspan="2" align="center"><span class="Estilo1">CAMBIO DE GRUPO</span></td></tr>
     <tr><td width="282" align="right"><span class="Estilo2">Período Académico:</span></td>
     <td width="218" align="left"><span class="Estilo2">'.$ano.'-'.$per.'</span></td></tr>
	 <tr>
       <td colspan="2" align="center" class="Estilo5">'.$Asignatura.'</td>
       </tr>
	 </table></div>';
?>
  <div align="center">
  <table border="1" cellpadding="0" cellspacing="0" width="515">
    <tr bgcolor="#E4E5DB">
      <td width="97" align="center" rowspan="2">Cambio Gr.</td>
	  <td align="center" rowspan="2"></td>
      <td width="61" align="center" rowspan="2">Grupo</td>
      <td width="62" align="center" rowspan="2">Cupo Disponible</td>
      <td width="259" align="center" colspan="7">HORARIO</td>
    </tr>
    <tr bgcolor="#E4E5DB">
      <td width="37" align="center">LU</td>
      <td width="37" align="center">MA</td>
      <td width="37" align="center">MI</td>
      <td width="37" align="center">JU</td>
      <td width="37" align="center">VI</td>
      <td width="37" align="center">SA</td>
      <td width="37" align="center">DO</td>
    </tr>
<?php
do{
   if(OCIResult($consulta, 3)<=0){
	  $boton = '<input type="image" SRC="../img/g_okay.png" name="B1" alt ="No Hay Cupo" disabled>';
	  $cup_disp = '<font color="#FF0000"><b>'.OCIResult($consulta, 3).'</b></font>';
   }
   else{
		$boton = '<input type="image" SRC="../img/s_okay.png" name="B1" alt ="Cambiar" enabled>';
		$cup_disp = OCIResult($consulta, 3);
   }
   
   print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
   <td width="97" align="center" height="10">
	   <form method="POST" action="prg_cambia_grupo.php" name="add">
	   '.$boton.'</td>
   <td align="center" height="10">
	   <input name="asicod" type="hidden" value="'.OCIResult($consulta, 1).'">
	   <input name="asigru_ant" type="hidden" value="'.$_GET['asigru_ant'].'">
	   <input name="asigru_nue" type="hidden" value="'.OCIResult($consulta, 2).'">
	   <input name="estcod" type="hidden" value="'.$_SESSION['usuario_login'].'">
	   <input name="cupo" type="hidden" value="'.OCIResult($consulta, 3).'"></form></td>
   
   <td width="61" align="center">'.OCIResult($consulta, 2).'</td>
   <td width="62" align="center">'.$cup_disp.'</td>
   <td width="288" colspan="7" align="center">'.OCIResult($consulta, 4).'</td></tr>';
}while(OCIFetch($consulta));
cierra_bd($consulta,$oci_conecta);
?>
</table>
</div>
<?php 
print'<br>';
fu_pie(); 
ob_end_flush();
?>
</BODY>
</HTML>