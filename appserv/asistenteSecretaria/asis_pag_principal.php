<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(112);
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="../script/jquery.js"></script>
<style type="text/css">
	#content{
		display:none;
		width:80%;
		margin-left: auto ;
  		margin-right: auto ;
	}

.shadow {
	-moz-box-shadow: 3px 3px 4px #000;
	-webkit-box-shadow: 3px 3px 4px #000;
	box-shadow: 3px 3px 4px #000;
	/* For IE 8 */
	-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000')";
	/* For IE 5.5 - 7 */
	filter: progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000');
}

td{
	 font-size:14px;

}

ul{
	 font-size:12px;	
}

</style>

<script type="text/javascript">
$(document).ready(function(){
  $("#content").slideDown('slow');

});
</script>
</head>
<body topmargin="0" leftmargin="0">

<?php
echo'
<div id="content" class="shadow">
<p>&nbsp;</p>
  <table border="0" width="100%" align="center" cellpadding="10">
    <tr>
      <td width="100%" align="center" height="9" colspan="2">
        <hr noshade class="hr">
      </td>
    </tr>
    <tr>
      <td width="67%" height="200" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
<ul>
	<li>Si tiene m&aacute;s de un tipo de usuario como: (Decano, Coordinador &oacute; Docente), haga clic en el usuario deseado, en la lista &quot;<span class="Estilo5">Cambiar a Usuario</span>&quot;.<br></li>
	<li>Con un efectivo control por parte de los usuarios, la informaci&oacute;n podr&aacute; ser completa y real, por lo que se sugiere que revise con especial cuidado y reporte a su Coordinador del Proyecto Curricular, cualquier inquietud o correcci&oacute;n que considere necesaria.<br></li>
	<li>La manera segura de salir de esta p&aacute;gina, es haciendo clic en el v&iacute;nculo &quot;<strong><a href="../conexion/salir.php" target="_top" onMouseOver="link();return true;" onClick="link();return true;">Salir</a></strong>&quot;. De esta forma nos aseguramos que otras personas no puedan manipular sus datos.<br></li>
	</ul>
      </td>
    </tr>
    <tr>
      <td width="100%" align="center" height="1">
        <hr noshade class="hr">
      </td>
    </tr>
    <tr>
      <td width="100%" align="center">
	  <a href="../generales/cambiar_mi_clave.php" target="_self" onMouseOver="link();return true;" onClick="link();return true;">Por seguridad, cambie su clave con frecuencia.</a></td>
    </tr>
  </table>
</div>	
<br><br><br><br><br>';
fu_pie();
ob_end_flush();
?>
</body>
</html>
