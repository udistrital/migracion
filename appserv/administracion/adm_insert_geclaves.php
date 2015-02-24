<?PHP
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
<head>
<title>Crea Clave</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/KeyIntro.js"></script>
</head>

<body onLoad="this.document.ins.cod.focus();">
<?PHP
fu_cabezote("CREACI&Oacute;N DE USUARIOS");

$perfiles = "SELECT DISTINCT(CLA_TIPO_USU), USUTIPO_TIPO
			FROM GECLAVES,GEUSUTIPO
			WHERE USUTIPO_COD = CLA_TIPO_USU
			ORDER BY 2";
	
$RowPer = $conexion->ejecutarSQL($configuracion,$accesoOracle,$perfiles,"busqueda");

echo'<br><br><br><br><form name="ins" method="POST" action="msql_insert_geclaves.php">
 
<table border="0" width="350" align="center" cellpadding="0" cellspacing="1" cellpadding="0" class="fondoTab">
  <caption>INSERTAR UN REGISTRO EN GECLAVES</caption>
  	<tr>
    		<td width="350" colspan="2">
		<p align="justify">Diligencie cada uno de 
		los campos del formulario, seleccione de la lista el tipo de usuario 
		a crear y haga clic en el bot&oacute;n &quot;<strong>Insertar</strong>&quot;.</td>
	</tr>
	<tr>
		<td width="130" align="right">&nbsp;</td>
		<td width="220">&nbsp;</td>
	</tr>
	<tr>
		<td width="130" align="left"><span class="Estilo11">*</span>C&oacute;digo:</td>
		<td width="220"><p><input type="text" name="cod" size="20" onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;"></p>
		</td>
	</tr>
	<tr>
		<td width="130" align="left"><span class="Estilo11">*</span>Clave:</td>
		<td width="220"><input type="password" name="cla" size="20" onChange="javascript:this.value=this.value.toLowerCase();"></td>
	</tr>
	<tr>
		<td width="130" align="left"><span class="Estilo11">*</span>Confirme Clave:</td>
		<td width="220"><input type="password" name="rcla" size="20" onChange="javascript:this.value=this.value.toLowerCase();"></td>
	</tr> 
    
	<tr>
		<td width="130" align="left"><span class="Estilo11">*</span>Tipo:</td>
		<td width="220">
		<select size="1" name="tip">';
		$i=0;
			while(isset($RowPer[$i][0]))
			{
				print'<option value="'.$RowPer[$i][0].'">'.$RowPer[$i][1].'</option>';
				$i++;
			}
			print'</select></td>
        </tr>
        <tr>
		<td width="350" align="center" colspan="2">&nbsp;';
		if(isset($_REQUEST['error_login'])){
			$error=$_REQUEST['error_login'];
			echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
			<a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</a>";
	  	}
		echo'</td></tr><tr><td width="370" align="center" colspan="2"><input type="submit" name="insertar" value="Insertar" class="button" '.$evento_boton.'></td>
	</tr>
</table></form><br><br><br><br><br><br><br><br><br><br>';
?>
</body>
</html>