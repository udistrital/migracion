<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
//LLAMADO DE: adm_admon.php
?>
<html>
<link href="estilo_adm.css" rel="stylesheet" type="text/css">
<body>
<?php
fu_tipo_user(20);
fu_cabezote("USUARIO");

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

//ACTUALIZA DATOS
if($_POST['actualizar']) {
   require_once('adm_ValAdm.php');
   if($_POST['tipo'] == 51){
      $upd = OCIParse($oci_conecta, "UPDATE GECLAVES
		                                SET CLA_TIPO_USU = :btipo,
										    CLA_ESTADO = :bestado
								      WHERE CLA_CODIGO =".$_POST['codigo']." 
									    AND CLA_TIPO_USU =".$_POST['tipo']);	 
	   OCIBindByName($upd, ":btipo", $_POST['tipo']);
	   OCIBindByName($upd, ":bestado", $_POST['estado']);
	   OCIExecute($upd);
	   OCICommit($oci_conecta);
   }
   elseif($_POST['tipo'] == 20 || $_POST['tipo'] == 24 || $_POST['tipo'] == 30){
   		  $upd = OCIParse($oci_conecta, "UPDATE GECLAVES
		                                SET CLA_TIPO_USU = :btipo,
										    CLA_ESTADO = :bestado
								      WHERE CLA_CODIGO =".$_POST['codigo']." 
									    AND CLA_TIPO_USU =".$_POST['tipo']);	 
	   	  OCIBindByName($upd, ":btipo", $_POST['tipo']);
	   	  OCIBindByName($upd, ":bestado", $_POST['estado']);
	   	  OCIExecute($upd);
	   	  OCICommit($oci_conecta);
   }
   cierra_bd($upd, $oci_conecta);
}
//EDITA LOS DATOS
if($_SESSION["tipo"] == 51)
   require_once('msql_geclaves_acest.php');
elseif($_SESSION["tipo"] == 20 || $_SESSION["tipo"] == 24 || $_SESSION['tipo'] == 30){
	   if($_SESSION["tipo"] == 30){
		  $nom = "DOC_NOMBRE||' '||DOC_APELLIDO";
		  $tab = "GECLAVES,ACDOCENTE";
		  $and = "AND DOC_NRO_IDEN = CLA_CODIGO";
	   }
	   if($_SESSION['tipo'] == 24){
		  $nom = "fua_invierte_nombre(EMP_NOMBRE)";
		  $tab = "GECLAVES,PEEMP";
		  $and = "AND EMP_NRO_IDEN = CLA_CODIGO";
	   }
	   if($_SESSION['tipo'] == 20){
		  $nom = "CLA_CODIGO";
		  $tab = "GECLAVES";
		  $and = "AND CLA_ESTADO IN('A','I')";
	   }
	   require_once('msql_geclaves.php');
}
		  
echo'<FORM method="post" ACTION="adm_actualiza.php" name="act"><div align="center"><center>
   <table border="1" width="55%" cellspacing="0" cellpadding="0">
   <tr class="tr">
   <td colspan="2" align="center"><span class="Estilo11">ACTUALIZACIÓN DE DATOS</span></td></tr>
   <tr align="center">
   <td align="left"><input name="nombre" id="nombre" type="text" value="'.OCIResult($datos, 5).'" size="50" readonly></td>
   <td align="left"><input type="text" name="nroiden" id="nroiden" size="12" value="'.OCIResult($datos, 6).'" style="text-align: right" readonly></td>
   </tr></table>
   
   <table border="1" width="55%" cellspacing="0" cellpadding="0">
   <tr class="tr">
   <td align="center">CÓDIGO</td>
   <td align="center">TIPO</td>
   <td align="center">ESTADO</td></tr>';
do{
   echo'<tr class="td" align="center">
      <td><input type="text" name="codigo" id="codigo" size="15" value="'.OCIResult($datos, 1).'" style="text-align: right" readonly></td>
      <td><input type="text" name="tipo" id="tipo" size="15" value="'.OCIResult($datos, 3).'" style="text-align: center" readonly></td>
      <td><input type="text" name="estado" id="estado" size="4" value="'.OCIResult($datos, 4).'" style="text-align: center" onChange="javascript:this.value=this.value.toUpperCase();"></td></tr>';
}while(OCIFetch($datos));
echo'</table><input type="submit" name="actualizar" value="Grabar"  class="button" '.$evento_boton.'></center></div><br><br><br></form>';
cierra_bd($datos, $oci_conecta);
fu_pie();
?>
</body>
</html>