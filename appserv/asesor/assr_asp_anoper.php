<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(34);
?>
<html>
<head>
<title>Aspirantes por a&ntilde;o y per&iacute;odo</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">

</head>

<body>
<?PHP 
fu_cabezote("ASPIRANTES POR A&Ntilde;O Y PER&Iacute;ODO"); 

$domainURL=(((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || $_SERVER['SERVER_PORT']==443) ? 'https://':'http://' ).$_SERVER['HTTP_HOST'];

$directorio=explode('/',substr($_SERVER["REQUEST_URI"],1));


$ruta=$domainURL.'/'.$directorio[0];


$dir=$ruta.'/informes/rasp_fac_cra_';
require_once('msql_nom_archivo.php');

$RowPartNom = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryPartNom,"busqueda");

print'<div align="center"><br><br><br>
  <form name="form1">
		<select name="asp" id="asp"><option value="">Seleccione el periodo</option>';

		
			$i=0;
			
			while(isset($RowPartNom[$i][0]))
			{
				echo'<option value="'.$RowPartNom[$i][0].'">'.$RowPartNom[$i][1].'</option>';
				$i++;
			}
			
		print'</select>
	</form>
  <p>&nbsp;</p>
  <table width="478" border="0" align="center">
	<tr>
		<td><p style="line-height: 100%" align="justify">De acuerdo al per&iacute;odo seleccionado, se despliega la informaci&oacute;n del total de aspirantes por Facultad y Carrera. Graficando el porcentaje de aspirantes por carrera frente al total de la Facultad.</td>
	</tr>
  </table>
  <p>&nbsp;</p>
</div>';
?>
<script language="JavaScript" type="text/JavaScript">
var selectEl = document.getElementById("asp");

function redirect(goto){
    if (goto != '') {
        window.location = goto;
    }
}


selectEl.onchange = function(){
    var goto = this.value;
    redirect(goto);

};
</script>
</body>
</html>