<?php
		require_once('funerr.php');//??//??

		//***session_register($nuevousuario="newuser");
		//***session_start();  
			session_name($usuarios_sesion="Autentificado");
			session_register($usuarios_sesion="Autentificado");

	if (!isset($_SESSION['usuario_login'])) {
			   session_destroy();
			   }?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="no"> 
<title>Coordinaci&oacute;n de Evaluaci&oacute;n Docente en l&iacute;nea</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"> 


<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">

<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">

<link href="../script/estilo.css" rel="stylesheet" type="text/css">

</head>
<script type="text/javascript" src="evdoc.js"></script>

<script type="text/JavaScript" language="JavaScript">
  function consultar(){
  	var brw = browser();
	if (brw=="NN4"){
		//--
	}else if (brw=="ie"){
		document.forms.item("qydoc").action = "ftocoordev.php"; 
		document.forms.item("qydoc").target = "fra_formato"
		document.forms.item("qydoc").submit()
		//alert("brw "+brw);
	}else if (brw=="NN6"){


	}
  }
  function showopciones(opcion){
/*  	if (opcion == 1){
      document.all['lyopciones'].style.visibility='hidden';
      document.all['lyopciones'].style.overflow ='hidden';
      //document.all['lyopciones'].style.height ='0';
	}else if( opcion == 2 ){
      document.all['lyopciones'].style.visibility='visible';
      document.all['lyopciones'].style.overflow ='visible';
      //document.all['lyopciones'].style.height ='10';
	}
	*/
	if (document.all['lyopciones'].style.visibility == 'hidden'){
		document.all['lyopciones'].style.visibility='visible';
		document.all['lyopciones'].style.overflow ='visible';
      	document.all['lyopciones'].style.height ='10';
	}else{
		document.all['lyopciones'].style.visibility='hidden';
		document.all['lyopciones'].style.overflow ='hidden';
        document.all['lyopciones'].style.height ='0';
	}
  }
</script><?
	$sec = @$_GET["sec"];
	$_SESSION["coor".$sec] = $sec;
?><BODY bgcolor="c6c384" onload="javascript:showopciones(1)">
			Estado de la Evaluaci&oacute;n:<br />
			<font size=2><a href="validcoordev.php?sec=1" return false target="fra_registro"> 
			Docentes</a><br> 
			<a href="validcoordev.php?sec=2" return false target="fra_registro"> 
			Proyectos Curriculares</a><br>
			<a href="validcoordev.php?sec=3" return false target="fra_registro">
			Facultades</a><br>
			<a href="validcoordev.php?sec=4" return false target="fra_registro"> 
			Resumen</a><br></font><?
			
//----------------------------------------------------------------------
$_SESSION["sec"] = $sec;	
if ($sec==1) {?>
		<form name="qydoc" action="ftocoordev.php" method="post" target="fra_formato">

			<table border='1'><tr><td colspan=4 " bgcolor=gold width="1000" align="center">
			Docentes</td></tr>
			<tr><td>
				<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1>
					<tr><td align="left">Identificación:</td></tr>						
					<tr>
						<td align="left"><input type="text" name="doc" id="doc" value =""></td>
					</tr>
					<tr><td align="left">Resaltar % de evaluaciones menor a:</td></tr>						
					<tr>
						<td align="left"><input type="text" name="ptje" id="ptje" value ="50"></td>
					</tr>
<? /*				<tr>
						<td align="left">Mostrar:</td>						
					</tr>				
				 </TABLE>
				<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1>
					<tr>
						<td align="left" ><EM>Carga acad&eacute;mica</EM></td><td align="left"><input type="checkbox" name="inc1" id="inc1" value="carga"/></td>
					</tr>					<tr>
						<td align="left" ><EM>Autoevaluaci&oacute;n</EM></td><td align="left"><input type="checkbox" name="inc2" id="inc2" value="auto"/></td>
					</tr><tr>
						<td align="left" ><EM>Estudiantes</EM></td><td align="left"><input type="checkbox" name="inc3" id="inc3" value="est"/></td>
					</tr><tr>
						<td align="left" ><EM>Consejos</EM></td><td align="left"><input type="checkbox" name="inc4" id="inc4" value="cpc"/></td>
					<tr><td><br /></td></tr>
*/?>
				</TABLE>
				<TABLE WIDTH=25% BORDER=4 CELLSPACING=1 CELLPADDING=1 align="center">
					<TR  align="center">
						<td width=12 align="center"><a href="JavaScript:consultar()"><EM>Consultar</EM></a></td>
					</TR>
				</TABLE>
			</td></tr>

	<? }else if($sec==2) {?>
 		<form name="qydoc" action="ftocoordev.php" method="post" target="fra_formato">
			<table border="1"><tr><td colspan=4 style="COLOR: blue" bgcolor=gold width="1000" align="center">	
			Proyectos Curriculares</td></tr>
			<tr><td>
				<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1>
					<tr><td align="left">C&oacute;digo de carrera:</td></tr>						
					<tr>
						<td align="left"><input type="text" name="cra" id="cra" width='6' value ="" size=5></td>
					</tr>
					<tr><td>
						<a href="JavaScript:showopciones(2)"><EM>Fijar opciones del informe:</EM></a>
						<div id='lyopciones'  align='right'><font size=2>
							Resaltar % de evaluaciones menor a:<br><input type="text" name="ptje" id="ptje" value ="50" size="3"><br>
							Incluir Resumen <input type="checkbox" id="resum" ></font>
						</div>
					</td></tr>
				</TABLE>
<? /*			//Sólo se tienen en cuenta <table width="100%" border="0" cellspacing="0" cellpadding="4"><tr bgcolor="#CCCCCC"><td colspan="2">Lorem</td></tr><tr><td colspan="2">Ipsum</td></tr><tr><td>&#149;</td><td width="100%"><a href="#">Dolar</a></td></tr><tr><td>&#149;</td><td><a href="#">Sic Amet</a></td></tr><tr><td colspan="2">Consetetur</td></tr><tr><td>&#149;</td><td><a href="#">Lorem</a></td></tr><tr><td>&#149;</td><td><a href="#">Ipsum</a></td></tr><tr><td>&#149;</td><td><a href="#">Dolar</a></td></tr></table><p>&nbsp;  <table width="100%" border="0" cellspacing="0" cellpadding="4"><tr><td bgcolor="#999999">Lorem</td></tr><tr><td bgcolor="#CCCCCC"><a href="#">Ipsum</a></td></tr><tr><td bgcolor="#CCCCCC"><a href="#">Dolar</a></td></tr><tr><td bgcolor="#CCCCCC"><a href="#">Sic Amet</a></td></tr><tr><td bgcolor="#CCCCCC"><a href="#">Consetetur</a></td></tr><tr><td bgcolor="#CCCCCC"><a href="#">Sadipscing</a></td></tr></table><p>&nbsp; <table width="200" border="0" cellspacing="0" cellpadding="0"><tr><td width="100%"><p><a href="#">Lorem</a><br><a href="#">Ipsum</a><br><a href="#">Dolar</a><br><a href="#">Sic Amet</a><br><a href="#">Consetetur</a></p></td><td width="1" bgcolor="#000000"><table width="1" border="0" cellspacing="0" cellpadding="0"><tr><td> </td></tr></table></td></tr></table> los docentes que tienen carga registrada...
				<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1>
					<tr>
						<td align="left" >Incluir:</td>
					</tr><tr>
						<td align="left" ><font size=2> <EM>Planta T. Completo</EM></font></td><td align="left"><input type="checkbox" id="ptc"></td>
					</tr><tr>
						<td align="left" ><font size=2> <EM>T. Completo Ocasional</EM></font></td><td align="left"><input type="checkbox" id="tco"></td>
					</tr><tr>
						<td align="left" ><font size=2> <EM>Medio T. Ocasional</EM></font></td><td align="left"><input type="checkbox" id="mto"></td>
					</tr><tr>
						<td align="left" ><font size=2> <EM>H. C. (Contrato)</EM></font></td><td align="left"><input type="checkbox" id="hcc"></td>
					</tr><tr>
						<td align="left" ><font size=2> <EM>H. C. (Honorarios)</EM></font></td><td align="left"><input type="checkbox" id="hch"></td>
					</tr><tr>
						<td align="left" ><font size=2> <EM>Planta Medio Tiempo</EM></font></td><td align="left"><input type="checkbox" id="pmt"></td>
*/ ?>					
					<tr><td><br /></td></tr>
				</TABLE>
				<TABLE WIDTH=25% BORDER=4 CELLSPACING=1 CELLPADDING=1 align="center">
					<TR  align="center">
						<td width=12 align="center"><a href="JavaScript:consultar()"><EM>Consultar</EM></a></td>
					</TR>
				</TABLE>
			</td></tr>
			</table>
		</form><? 
	}
	switch ($sec) {
		case 1:
			break;
		case 2:
			;		break;
		case 3:
			;		break;
		case 4:
			;		break;
	}
//------------------------------------------
?>
</BODY>
</HTML>