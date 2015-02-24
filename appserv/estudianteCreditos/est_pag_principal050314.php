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

		$cadena_sql="SELECT est_cod codigo, ";
 		$cadena_sql.="  est_nombre nombre, ";
		$cadena_sql.="   est_nro_iden documento, ";
		$cadena_sql.="   est_estado_est estado, ";
		$cadena_sql.="   TRUNC(MONTHS_BETWEEN(SYSDATE,eot_fecha_nac)/12) edad, ";
		$cadena_sql.="   eot_email email, ";
		$cadena_sql.="   eot_email_ins email_ins, ";
		$cadena_sql.="   cra_nombre carrera, ";
		$cadena_sql.="   (SELECT MAX(cnx_fecha||' - '||cnx_hora) ";
		$cadena_sql.="    FROM geconexlog ";
		$cadena_sql.="    WHERE cnx_usuario = acest.est_cod) ultimo ";
		$cadena_sql.="FROM accra, acest, acestotr ";
		$cadena_sql.="WHERE cra_cod = est_cra_cod ";
		$cadena_sql.="   AND est_cod = ". $usuario." ";
 		$cadena_sql.="  AND est_cod = eot_cod ";


	
		$registroInfo=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");

		$info_usuario="<table  width='100%' border='3'>";
		$info_usuario.="<tr><td colspan='2'><br></td></tr>";
		$info_usuario.="<tr><td>C&oacute;digo:</td><td> ".$registroInfo[0][0]."</td></tr>";
		$info_usuario.="<tr><td>Usuario:</td><td> ".$registroInfo[0][1]."</td></tr>";
		$info_usuario.="<tr><td>Identificaci&oacute;n:</td><td> ".$registroInfo[0][2]."</td></tr>";
		$info_usuario.="<tr><td>Estado acad&eacute;mico: </td><td>".$registroInfo[0][3]."</td></tr>";
		$info_usuario.="<tr><td>Edad:</td><td> ".$registroInfo[0][4]."</td></tr>";
		$info_usuario.="<tr><td>Correo: </td><td>".$registroInfo[0][5]."</td></tr>";
		$info_usuario.="<tr><td>Correo Institucional:</td><td> ".$registroInfo[0][6]."</td></tr>";
		$info_usuario.="<tr><td>Proyecto:</td><td> ".$registroInfo[0][7]."</td></tr>";
		$info_usuario.="<tr><td>&Uacute;ltimo acceso: </td><td>".$registroInfo[0][8]."</td></tr>";	
		$info_usuario.="<tr><td colspan='2'><br></td></tr>";			
		$info_usuario.="</table>";
				
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
		$cadena_sql.="TO_NUMBER(TO_CHAR(CME_FECHA_INI,'yyyymmdd')) AND TO_NUMBER(TO_CHAR(CME_FECHA_FIN,'yyyymmdd')) ";  
		$cadena_sql.="ORDER BY CME_CODIGO DESC"; 		

		//echo $cadena_sql;
	//require_once(dir_script.'NumeroVisitas.php');
		$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");



echo'<p>&nbsp;</p><div align="center">

<table border="0" width="600" cellpadding="0">	
<tr><td width="100%" align="center" height="9" ><img src="../img/error.png" width="50" heigth="50" border=0><br><hr noshade class="hr"></td></tr>
    
	<tr><td width="67%" height="200" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
        <font size="2"> 
	<p align="center" style="line-height: 100%"><b>DIRECTRICES A TENER EN CUENTA EN EL PROCESO DE ADICIONES, CANCELACIONES Y CAMBIOS DE GRUPO DE ESPACIOS ACAD&Eacute;MICOS</b></p>

	<p align="center" style="line-height: 100%"><b>DIRIGIDA A ESTUDIANTES EN CR&Eacute;DITOS</b></p>

		<p align="justify" style="line-height: 100%">Señor Estudiante en cr&eacute;ditos:
		</p>
		
		<p align="justify" style="line-height: 100%">Para el presente semestre (2010-1) el proceso de adiciones, cancelaciones y cambios de grupo de sus espacios acad&eacute;micos tenga en cuenta las siguientes directrices: 
		</p>
		
		<p align="justify" style="line-height: 100%">
		<ol>
			<li>Seg&uacute;n lo establece el Acuerdo 009 de 2006, el estudiante en cr&eacute;ditos  s&oacute;lo podr&aacute; inscribir un m&aacute;ximo de 18 cr&eacute;ditos acad&eacute;micos por periodo acad&eacute;mico.
			<li>Una vez cancelado un espacio acad&eacute;mico &eacute;ste no podr&aacute; ser inscrito nuevamente en el mismo periodo acad&eacute;mico.
			<li>Las inscripciones de espacios acad&eacute;micos para los estudiantes de primer semestre las realiza exclusivamente el Proyecto Curricular.
			<li>Los estudiantes que ingresan a segundo semestre en el sistema de cr&eacute;ditos acad&eacute;micos tienen la posibilidad de inscribir espacios acad&eacute;micos en otros Proyectos Curriculares de la Universidad, siempre y cuando &eacute;stos sean comunes y haya disponibilidad de grupos y cupos.
			<li>Los estudiantes que ingresan a segundo semestre en el sistema de cr&eacute;ditos acad&eacute;micos tienen la posibilidad de realizar cambio de grupo de sus espacios acad&eacute;micos ya inscritos durante las fechas definidas para este prop&oacute;sito por el Calendario Acad&eacute;mico.
			<li>El proceso de adiciones y cancelaciones de espacios acad&eacute;micos puede realizarse &uacute;nicamente desde el <b>aplicativo Web CONDOR.</b>
			<li>Cada vez que el estudiante realice una adici&oacute;n, cancelaci&oacute;n o cambio de grupo, <b>el sistema le arrojar&aacute; un n&uacute;mero de verificaci&oacute;n del registro realizado</b>. Se recomienda conservar el n&uacute;mero de registro dado por el sistema para reclamaciones si fuere el caso.
		 
					
		</ol>
		</p>
		
        <p style="line-height: 100%" align="justify">Para mayor informaci&oacute;n, por favor consultar <b>“Manual de usuario del Sistema de Gesti&oacute;n Acad&eacute;mica”</b> publicado en C&oacute;ndor – Perfil Estudiante.</p>

	<p style="line-height: 100%" align="justify">Agradecemos su gentil atenci&oacute;n</p>
	<p style="line-height: 100%" align="justify">Febrero 5 de 2010- Oficina Asesora de Sistemas</p>
	</font>	
      </td>
    </tr>
	
	<tr><td width="100%" align="center" height="9" colspan="3"><hr noshade class="hr"></td></tr>
	<p></p>
</table>';
echo'
<table border="0" width="530" cellpadding="0">	
	<tr align="center" border="1">
        <td width="50%" align="center">
		<font size=1>Manual de usuario del Sistema de Gesti&oacute;n Acad&eacute;mica</font><br>
            <a href="../estudianteCreditos/Manual Estudiante SGA.pdf"><img src="../img/acroread.png" border=0><br>Descargar PDF</a>
        </td>
	</tr>
	<tr align="center" border="0">
	<td  align="center">
		<font size=2>Linea de atenci&oacute;n cr&eacute;ditos<br>Oficina Asesora de Sistemas<br>Tel:3238400 ext:1110</font><br>
	</td>	
	</tr>
	<tr><td width="100%" align="center" height="9" colspan="3"><hr noshade class="hr"></td></tr>
	<p></p>
</table>		
		
			
	</div>';

?>
</body>
</html>
