<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */
// Buscar proveedores



echo '<div id="consulta" style="text-align: center;">';
echo '<form name="formulario" id="formulario">';
echo "<b>".utf8_encode($this->lenguaje->getCadena("seleccionar"))."</b>:";

echo '<select name="opcionConsulta">';
echo "<option value='codigo'>".$this->lenguaje->getCadena("codigo")."</option>";
echo "<option value='identificacion'>".$this->lenguaje->getCadena("identificacion")."</option>";
echo "<option value='nombre'>".$this->lenguaje->getCadena("nombre")."</option>";
echo "</select>";
echo '<input type="text" width="80px" id="valorConsulta" name="valorConsulta" class="validate[required, custom[onlyLetterNumber]]" title="'.utf8_encode($this->lenguaje->getCadena("ingreseValor")).'"></input>';
echo "</form>";
echo '<input type="button" width="30px" id="consultarUsuario" ';

if(isset($_REQUEST["soloConsulta"])) echo ' onclick="consultarUsuarioNoEdicion();"';
else echo '		onclick="consultarUsuario();" ';

echo '		value="'.utf8_encode($this->lenguaje->getCadena("consultarBoton")).'"></input>';
echo "</div>";
echo '<div id="consultaResultado">';
echo '<div id="resultadoUsuario">';
echo "</div>";
echo '<div id="resultadoDeudas">';
echo "</div>";
echo "</div>";
echo '<div id="listado">';
echo "</div>";


/*
$datosArray['ID']=$_REQUEST['id'];
$cadena_sql = $this->sql->cadena_sql("consultarDeuda",$datosArray);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
if($registros==null){
	$arra["resp"] =utf8_encode($this->lenguaje->getCadena("errorConsultaDeuda"));;
	echo json_encode($arra);
	exit;
}

$cadena = '<br><table style="margin: 0 auto;">';
foreach ($registros[0] as  $att => $val){
	$nombre = str_replace("deudas","",$att);
	if(!is_numeric($att)&&strpos($att,"Estado")===false)
		$cadena .="<tr><td>". $this->lenguaje->getCadena(strtolower($nombre))."</td><td> ".$val."</td></tr>";
	elseif (strpos($att,"Estado")!=false&&$val==1)
		$cadena .="<tr><td>". ucwords(strtolower($nombre))."</td><td> ".utf8_encode($this->lenguaje->getCadena("textoActivo"))."</td></tr>";
	elseif (strpos($att,"Estado")!=false&&$val==0)
		$cadena .="<tr><td>". ucwords(strtolower($nombre))."</td><td> ".utf8_encode($this->lenguaje->getCadena("textoInactivo"))."</td></tr>";
}
$cadena .= "</table><br>";
$arra["resp"] = $cadena;

echo utf8_encode($cadena);
*/