<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function consultaMunicipio($valor){

	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable();

	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");



	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"conexion_proveedor");

       	$select=new html();
	$busqueda="SELECT ";
	$busqueda.="DISTINCT(mun_cod),";
	$busqueda.="REPLACE(mun_nombre,'Ñ','N') ";
	$busqueda.="FROM ";
	$busqueda.="gemunicipio ";
	$busqueda.="WHERE mun_dep_cod='".$valor."' ";

        $resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");

         for ($i=0; $i<count($resultado);$i++)
                    {
                       $registro[$i][0]=$resultado[$i][0];
                       $registro[$i][1]=UTF8_DECODE(ucwords(strtolower($resultado[$i][1])));
                    }


	$municipio=$select->cuadro_lista($registro,"municipio",$configuracion,$registro[0][0],2,100,0,"municipio");

			$html="	<table class='sigma_borde'>";
			$html.="<tr>";
			$html.="		<td>".$municipio."</td>";
			$html.="</tr>";
			$html.="</table>";

	$respuesta = new xajaxResponse();
	$respuesta->addAssign("divMunicipio","innerHTML",$html);
	return $respuesta;

}


function consultaEspecialidad($valor){

	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable();

	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"conexion_proveedor");

       	$select=new html();
	$busqueda="SELECT ";
	$busqueda.="espid,";
	$busqueda.="REPLACE(espdescripcion,'Ñ','N') ";
	$busqueda.="FROM ";
	$busqueda.="prespecialidad ";
	$busqueda.="WHERE actid='".$valor."' ";

        $resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
        for ($i=0; $i<count($resultado);$i++)
        {
           $registroEsp[$i][0]=$resultado[$i][0];
           $registroEsp[$i][1]=UTF8_DECODE($resultado[$i][1]);
        }

	$especialidad=$select->cuadro_lista($registroEsp,"especialidad",$configuracion,$registroEsp[0][0],2,100,0,"especialidad");

			$html="	<table class='sigma_borde'>";
			$html.="<tr>";
			$html.="		<td>".$especialidad."</td>";
			$html.="</tr>";
			$html.="</table>";

	$respuesta = new xajaxResponse();
	$respuesta->addAssign("divEspecialidad","innerHTML",$html);
	return $respuesta;

}

function consultaDepartamento($valor){

	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable();

	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");



	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"conexion_proveedor");

       	$select=new html();
	
        $busqueda="SELECT ";
	$busqueda.="dep_cod,";
	$busqueda.="REPLACE(dep_nombre,'Ñ','N') ";
	$busqueda.="FROM ";
	$busqueda.="gedepartamento ";
	$busqueda.="WHERE gepaiscod='".$valor."' ";

        $resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
        $configuracion["ajax_function"]="xajax_consultaMuncipio";
        $configuracion["ajax_control"]="pais";

         for ($i=0; $i<count($resultado);$i++)
        {
           $registroDep[$i][0]=$resultado[$i][0];
           $registroDep[$i][1]=UTF8_DECODE(ucwords(strtolower($resultado[$i][1])));
        }


	$departamento=$select->cuadro_lista($resultadoDep,"departamento",$configuracion,$resultadoDep[0][0],2,100,0,"departamento");

			$html="	<table class='sigma_borde'>";
			$html.="<tr>";
			//$html.="		<td class='renglones'>Seleccione la especialidad</td>";
			$html.="		<td>".$departamento."</td>";
			$html.="</tr>";
			$html.="</table>";

	$respuesta = new xajaxResponse();
	$respuesta->addAssign("divDepartamento","innerHTML",$html);
	return $respuesta;

}

?>
