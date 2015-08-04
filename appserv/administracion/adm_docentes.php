<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(20);
?>
<html>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<body>
<?php
ob_start();

fu_cabezote("ADMINISTRACI&Oacute;N DE DOCENTES");

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

$b_edit ='<IMG SRC='.dir_img.'b_edit.png alt="Editar" border="0">';
$b_insrow ='<IMG SRC='.dir_img.'b_insrow.png alt="Insertar" border="0">';
$b_browse='<IMG SRC='.dir_img.'b_browse.png alt="Consultar" border="0">';
$b_deltbl='<IMG SRC='.dir_img.'b_deltbl.png alt="Borrar" border="0">';

if(!isset($_REQUEST['R1'])){ ?>
  <p>&nbsp;</p>
  <FORM NAME='doc' method="post" ACTION="<?PHP echo $_SERVER['PHP_SELF']?>">
   <table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolorlight="#DCDCB6" bordercolordark="#C0C080">
   <caption><span class="Estilo11">CRITERIOS DE ORDENAMIENTO Y BUSQUEDA DE DOCENTES</span>
   </caption>
  <tr align="center" valign="middle">
  <td colspan="13"><b class="Estilo1">
    <input type="radio" name="RN" value="1" checked>PRIMER NOMBRE</b></td>
  <td colspan="13"><b class="Estilo1">
    <input type="radio" name="RN" value="2">PRIMER APELLIDO</b></td>
  </tr>
  <tr align="center" valign="middle">
  <td><span class="Estilo1"><input type="radio" name="R1" value="A" ><br>A</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="B"><br>B</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="C"><br>C</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="D"><br>D</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="E"><br>E</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="F"><br>F</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="G"><br>G</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="H"><br>H</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="I"><br>I</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="J"><br>J</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="K"><br>K</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="L"><br>L</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="M"><br>M</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="N"><br>N</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="O"><br>O</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="P"><br>P</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="Q"><br>Q</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="R"><br>R</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="S"><br>S</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="T"><br>T</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="U"><br>U</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="V"><br>V</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="W"><br>W</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="X"><br>X</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="Y"><br>Y</span></td>
  <td><span class="Estilo1"><input type="radio" name="R1" value="Z"><br>Z</span></td>
  </tr>
  <tr align="center" valign="middle">
    <td colspan="26"><input type='Submit' value='Consultar' class="button" <? print $evento_boton;?>></td>
    </tr>
</table>
  </FORM><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php

}
else{
	 $R1 = $_REQUEST['R1'];
	 if($_REQUEST['RN'] == "1")
	 { 
		$varnom = "doc_nombre LIKE('$R1%')"; 
		$varorder = "doc_nombre";
	 }
	 if($_REQUEST['RN'] == "2")
	 { 
		$varnom = "doc_apellido LIKE('$R1%')"; 
		$varorder = "doc_apellido";
	 }
	 
	 $consulta = "SELECT cla_codigo,
		doc_nombre, 
		doc_apellido,
		cla_tipo_usu,
		cla_estado
		FROM geclaves,acdocente
		WHERE cla_codigo = doc_nro_iden
		AND $varnom
		AND cla_tipo_usu = 30
		ORDER BY $varorder";
		
	$row = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
	 	  	
	echo'<div align="center">
	<table border="0" width="625">
		<tr>
			<td width="625" colspan="2" align="center">&nbsp;';
			if(isset($_REQUEST['error_login']))
			{ 
				$error=$_REQUEST['error_login']; 
				echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'> 
				<a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</a></font>"; 
			}
			echo'</td>
		</tr>
	</table>
   	<p></p>
	<table border="1" width="90%" cellspacing="0" cellpadding="0" align="center">
		<tr class="tr" align="center">
			<td>Documento</td>
			<td>Nombres</td>
			<td>Apellidos</td>
			<td>Tipo</td>
			<td>Est</td>
			<!-- <td align="center" colspan="2">Acci&oacute;n</td> --> </tr>';
			$i=0;
			while(isset($row[$i][0]))
			{
				echo'<tr class="td" onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
				<td align="right">'.$row[$i][0].'</td>
				<td align="left">'.$row[$i][1].'</td>
				<td align="left">'.$row[$i][2].'</td>
				<td align="center">'.$row[$i][3].'</td>
				<td align="center">'.$row[$i][4].'</td>
				</tr>';
				$i++;
			}
	echo'</table></center></div><br><br>';
}
?>
</body>
</html>