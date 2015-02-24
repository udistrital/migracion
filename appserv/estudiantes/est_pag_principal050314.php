<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");


fu_tipo_user(51);
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
</head>
<body>

<?php

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$usuario = $_SESSION['usuario_login'];
	$carrera = $_SESSION['carrera'];


	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

		$cadena_sql="SELECT ";
		$cadena_sql.="est_estado_est,estado_nombre  ";
		$cadena_sql.="FROM acest, acestado  ";
		$cadena_sql.="WHERE est_cod =". $usuario." ";
		$cadena_sql.="AND estado_cod = est_estado_est ";

		$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");



		$cadena_sql="SELECT  "; 
		$cadena_sql.="CME_AUTOR,"; 
		$cadena_sql.="CME_TITULO,"; 
		$cadena_sql.="TO_CHAR(CME_FECHA_INI,'dd/Mon/yyyy'),";
		$cadena_sql.="CME_HORA_INI,";
		$cadena_sql.="TO_CHAR(CME_FECHA_FIN,'dd/Mon/yyyy'),"; 
		$cadena_sql.="CME_MENSAJE  ";
		$cadena_sql.="FROM accoormensaje  ";
		$cadena_sql.="WHERE CME_CRA_COD = (SELECT est_cra_cod FROM acest WHERE est_cod=$usuario)";
		$cadena_sql.="AND CME_TIPO_USU IN(0,51)   ";
		$cadena_sql.="AND TO_NUMBER(TO_CHAR(sysdate,'yyyymmdd')) BETWEEN   ";		
		$cadena_sql.="TO_NUMBER(TO_CHAR(CME_FECHA_INI,'yyyymmdd')) AND TO_NUMBER(TO_CHAR(CME_FECHA_FIN,'yyyymmdd'))";  

		//echo $cadena_sql;
	//require_once(dir_script.'NumeroVisitas.php');
		$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
		

	echo'<br><div align="center">
	<a href="#" onClick="javascript:popUpWindow(\'add_can.php\', \'no\', 100, 100, 350, 133)">Fechas de Adici&oacute;n y Cancelaci&oacute;n</a>
	<p></p>
	  <table border="0" width="500" cellpadding="0" cellspacing="2">
	  <tr><td width="200" align="left" height="9" colspan="2" class="'.$Estilo.'">'.$Estado.'</td>
	  <td width="300" align="right" height="9" colspan="2"></td></tr></table>
	<caption>&nbsp;</caption>
	  <table border="0" width="500" cellpadding="0">
	    <tr>
	      <td width="100%" align="center" height="9" colspan="2">
		  <hr noshade class="hr">
	      </td>
	    </tr>';
	if($registro[0][5] == ""){    
		echo'<tr><td width="67%" height="258" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
		<p align="justify" style="line-height: 100%">Si cambia su correo electr&oacute;nico, direcci&oacute;n o tel&eacute;fono; no olvide actualizarlos en el men&uacute;  Datos Personales. Recuerde que de la veracidad de sus datos, depende un efectivo ingreso al aplicativo.</p>
		
		<p align="justify" style="line-height: 100%">En esta p&aacute;gina usted podr&aacute;: Actualizar su datos, ver su registro de asignaturas, cambiar la clave, revisar su hist&oacute;rico de notas, ver las notas parciales que va obteniendo durante el semestre y ver su plan de estudio. Cuantas asignaturas ha visto y las notas que ha obtenido en cada una de ellas y cuantas asignaturas le quedan por ver de su plan de estudio.</p>
			
		<p align="justify" style="line-height: 100%">Con un efectivo control por parte de los usuarios, la informaci&oacute;n podr&aacute; ser completa y real, por lo que se sugiere que revise con especial cuidado y reporte a su Coordinador del Proyecto Curricular, cualquier inquietud o correcci&oacute;n que considere necesaria.</p>

		<p align="justify" style="line-height: 100%">La manera segura de salir de esta p&aacute;gina, es haciendo clic en el v&iacute;nculo &quot;<strong><a href="../conexion/salir.php" target="_top" onMouseOver="link();return true;" onClick="link();return true;">Salir</a></strong>&quot;. De esta forma nos aseguramos que otras personas no puedan manipular sus datos.</p>
			
			</td></tr>';
	}
	else{
		echo'<tr><td width="500" bgcolor="#E9E9D1" colspan="2" align="center"><span class="Estilo11">MENSAJES DE LA COORDINACI&Oacute;N</span></td></tr>
		<tr><td width="67%" height="258">';
		$i=0;
		while(isset($registro[$i][0])){
			echo'<table width="500" border=".5" cellpadding="0" cellspacing="0" align="center">
			<tr><td width="234" bgcolor="#E9E9D1"><span class="Estilo12">'.$registro[$i][1].'</span></td>
			<td width="316" align="right" bgcolor="#E9E9D1"><span class="Estilo13">|&nbsp;'.$registro[$i][2].'&nbsp;|
			'.$registro[$i][3].'|&nbsp;</span><span class="Estilo10">'.$registro[$i][0].'</span></td></tr><tr> 
			<td colspan="2" width="500">'.$registro[$i][5].'</td></tr></table><br>';
			$i++;
		}
		
		echo'</td></tr>';

	}
	echo'<tr>
	      <td width="100%" align="center" height="1">
		<hr noshade class="hr">
	      </td>
	    </tr>
		<tr>
		  <td width="100%" align="center" height="40">
		  <p align="center">Con el fin de evitar suplantaciones, la Oficina Asesora de Sistemas recomienda cambiar la clave periodicamente.</p>
		  </td>
		</tr>
	  </table>
	</div><p></p>';

?>
</body>
</html>
