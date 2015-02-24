<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_general.'msql_ano_per.php');
require_once(dir_general.'fecha_inscripcion.php');
//require_once(dir_general.'valida_inscripcion.php');


require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

/*$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");*/

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(50);

?>
<html>
<head>
<title>Aspirantes</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../script/BorraLink.js"></script>
<script language="JavaScript" src="Logout.js"></script>
</head>

<body>

<p align="center" class="Estilo6">PROCESO DE ADMISIONES<br><? print $periodo; ?></p>

 <style type="text/css">
            <!--
            .Estilo18 {
                font-size: 11px;
                color: #FF0000;
            }
            .Estilo19 {
                color: #FF0000}
            -->

            .conFondo {
                background-color: #EBEBEB;
                font-family: sans-serif,Helvetica,Arial;  
                color: #0040ED; 
                font-size: 14px; 
                font-weight: bold
            }

            /* #### bootstrap Form #### */
            .bootstrap-frm {
                width: 900px;
                margin-right: auto;
                margin-left: auto;
                background: #FFF;
                padding: 20px 30px 20px 30px;
                font: 11px "Helvetica Neue", Helvetica, Arial, sans-serif;
                color: #888;
                text-shadow: 1px 1px 1px #FFF;
                border:1px solid #DDD;
                border-radius: 5px;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
            }
            .bootstrap-frm h1 {
                font: 25px "Helvetica Neue", Helvetica, Arial, sans-serif;
                padding: 0px 0px 10px 40px;
                display: block;
                border-bottom: 1px solid #DADADA;
                margin: -10px -30px 30px -30px;
                color: #888;
            }
            .bootstrap-frm h1>span {
                display: block;
                font-size: 11px;
            }
            .bootstrap-frm label {
                display: block;
                margin: 0px 0px 5px;
            }
            .bootstrap-frm label>span {
                float: left;
                width: 80px;
                text-align: right;
                padding-right: 10px;
                margin-top: 10px;
                color: #333;
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                font-weight: bold;
            }
            .bootstrap-frm input[type="text"], .bootstrap-frm input[type="email"], .bootstrap-frm textarea, .bootstrap-frm select{
                border: 1px solid #CCC;
                color: #888;
                height: 20px;
                margin-bottom: 2px;
                margin-right: 6px;
                margin-top: 2px;
                margin-left:4px;
                outline: 0 none;
                width: 68%;
                border-radius: 4px;
                -webkit-border-radius: 4px;
                -moz-border-radius: 4px;
                font: normal 13px/13px "Helvetica Neue", Helvetica, Arial, sans-serif;
                -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            }
     
            .bootstrap-frm textarea{
                height:100px;
            }

            .bootstrap-frm .button {
                background: #FFF;
                border: 1px solid #CCC;
                padding: 10px 25px 10px 25px;
                color: #333;
                border-radius: 4px;
            }
            .bootstrap-frm .button:hover {
                color: #333;
                background-color: #EBEBEB;
                border-color: #ADADAD;
            }
        </style>
<form name="form1" method="post" class="bootstrap-frm" action="<?echo $_REQUEST['form']?>.php">

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="3">
<caption><span class="Estilo3"><? print 'Fecha inicial: '.$FormFecIni.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Fecha final: '.$FormFecFin; ?></span>
</caption>
	<tr>
	

		<td width="50%" align="center">
			<fieldset style="margin:3; padding:20px; font-size:12pt; width:70%;">
			<p align="justify">
			Por medio del presente manifiesto que conozco y he leido el <b><a href="../instructivo/index.php" target="_blank">instructivo</a></b> Oficial de Admisiones del <? print $periodo; ?> y acorde con la ley 1581 de 2012, autorizo de manera expresa e inequ&iacute;voca, que mis datos personales sean tratados conforme a las funciones propias de la Universidad, en su condici&oacute;n de Instituci&oacute;n de Educaci&oacute;n Superior.</p>
			</p><br>
			<input class="button" type="submit" value="Continuar con la inscripci&oacute;n" name="acepto" id="acepto"></center>
			</fieldset>
		</td>
	</tr>
</table>
<center>
</form>
			
</body>
</html>
