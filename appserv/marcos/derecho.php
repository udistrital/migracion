<?

	include_once("../clase/dbms.class.php");
	require_once('../conexion/valida_pag.php');
	require_once("../conexion/fu_tipo_user.php");
			

fu_tipo_user($_SESSION['usuario_nivel']);


/*	switch($_SESSION['usuario_nivel']){
		case 51:
			$home="onclick=parent.principal.location.href='../estudiantes/est_pag_principal.php'";
			$help="onclick=parent.principal.location.href='http://oasdes.udistrital.edu.co/development/desarrolloweb/manual/estudiante.pdf'";
		break;
		
	
	
	}*/

?>


<html>
	<head>
		<script language="JavaScript" src="../script/ventana.js"></script>
		<link href="apariencia.css" rel="stylesheet" type="text/css">
	</head>
	<body class="derecho">
<center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<table>		


				
</table>
</center>
	</body>
  
</html>


