<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(30);
	
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body topmargin="0" leftmargin="0">

<?php
require_once(dir_script.'NumeroVisitas.php');
enlaceEncuesta();
echo'<p>&nbsp;</p>
  <table border="0" width="500" align="center" cellpadding="0" cellspacing="2">
  <tr><td width="200" align="left" height="9" colspan="2"></td>
  <td width="300" align="right" height="9" colspan="2"><span class="Estilo7">Visita No. '.$Nro.' de '.$Tot.' desde 28-Jun-2006</span></td></tr></table>
  <p></p>
  <table border="0" width="500" align="center" cellpadding="0">
    <tr>
      <td width="100%" align="center" height="9" colspan="2">
        <hr noshade class="hr">
      </td>
    </tr>
    <tr>
      <td width="67%" height="200" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
	     <p align="justify" style="line-height: 100%">Si tiene m&aacute;s de un tipo de usuario como: (Decano, Coordinador &oacute; Docente), haga clic en el usuario deseado, en la lista &quot;<span class="Estilo5">Cambiar a Usuario</span>&quot;.</p>
		
         <p align="justify" style="line-height: 100%">Si cambia su correo electr&oacute;nico, no olvide actualizarlo en la p&aacute;gina de actualizaci&oacute;n de datos, haciendo clic en el men&uacute; &quot;Datos Personales&quot;. Recuerde que de la veracidad de sus datos, depende un efectivo ingreso al aplicativo.</p>
                      
         <p style="line-height: 100%" align="justify">El rango de las notas debe estar entre 15 y 50, para las notas cuantitativas. Art. 43, Estatuto Estudiantil.</p>
                      
         <p style="line-height: 100%" align="justify">A las notas cualitativas se les debe digitar un cero (0) y la observaci&oacute;n 19 &oacute; 20, aprobado y no aprobado, respectivamente.</p>
         
		 <p align="justify" style="line-height: 100%">Con un efectivo control por parte de los usuarios, la informaci&oacute;n podr&aacute; ser completa y real, por lo que se sugiere que revise con especial cuidado y reporte a su Coordinador del Proyecto Curricular, cualquier inquietud o correcci&oacute;n que considere necesaria.</p>
		        
         <p align="justify" style="line-height: 100%">La manera segura de salir de esta p&aacute;gina, es haciendo clic en el v&iacute;nculo &quot;<strong><a href="../conexion/salir.php" target="_top" onMouseOver="link();return true;" onClick="link();return true;">Salir</a></strong>&quot;. De esta forma nos aseguramos que otras personas no puedan manipular sus datos.</p>          
      </td>
    </tr>
    <tr>
      <td width="100%" align="center" height="1">
        <hr noshade class="hr">
      </td>
    </tr>
    
  </table>
<br><br><br><br><br>';
fu_pie();
ob_end_flush();
?>
</body>
</html>
<?
    function enlaceEncuesta() {
        require_once("../clase/config.class.php");
        require_once("../clase/encriptar.class.php");
        $esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../");
        $cripto=new encriptar();
        $indiceAcademico= $configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
        
        //Encuesta
	$variable="pagina=registro_encuestaDocente";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=1";
        $variable.="&tipoUser=30";
        $variable.="&modulo=Docente";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceEncuesta=$indiceAcademico.$variable;
        
        $enlace = "<br><div align='center' >"; 
        $enlace .= "<a href='".$enlaceEncuesta."'>";
        $enlace .= "<img alt='Encuesta-diagn&oacute;stico de necesidades' src='".$configuracion["host"].$configuracion["raiz_sga"].$configuracion["grafico"]."/encuesta.png'>";
        $enlace .= "<br><font color='red'>Se&ntilde;or Docente </font>por favor diligencie la Encuesta-diagn&oacute;stico de necesidades <br>e intereses de formaci&oacute;n docente";
        $enlace .= "</a></div>";
        $enlace .= "<br>";
        echo $enlace;
    }   
    
    ?>