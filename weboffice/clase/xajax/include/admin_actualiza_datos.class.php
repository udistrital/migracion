<?php
//Se invoca cuando se cambia de pais

function consultaMunicipioColegio($valor){
	
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"estudiante");


	$select=new html();
	$busqueda="SELECT ";
	$busqueda.="DISTINCT(municipio),";
	$busqueda.="municipio ";
	$busqueda.="FROM ";
	$busqueda.="gecolegio ";
	$busqueda.="WHERE departamento='".$valor."'";
	
	

	$configuracion["ajax_function"]="xajax_consultaColegio";
	$configuracion["ajax_control"]="muncolegio";
	
	$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
	$muncolegio=$select->cuadro_lista($resultado,"muncolegio",$configuracion,-1,2,100,0,"muncolegio",400);
			$html="	<table class='formulario'  align='center'>";
			$html.="<tr >";
			$html.="		<td width='25%'>Municipio donde curso su ultimo a&ntilde;o de bachillerato:</td>";
			$html.="		<td>".$muncolegio."</td>";
			$html.="</tr>";
			$html.="</table>";

	$respuesta = new xajaxResponse();
	$respuesta->addAssign("divMunColegio","innerHTML",$html);
	return $respuesta;						

}

function consultaColegio($valor){
	
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
/*	$conexion=new dbConexion($configuracion);		
	$recurso=$conexion->recursodb($configuracion,"oracle3");
	$recurso->conectar_db();*/
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"estudiante");
		
	$select=new html();
	$busqueda="SELECT ";
	$busqueda.="codigocolegio,";
	$busqueda.="nombreoficial||'- JORNADA '||JORNADA ";
	$busqueda.="FROM ";
	$busqueda.="gecolegio ";
	$busqueda.="WHERE municipio='".$valor."'";
	

	$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
	$colegio=$select->cuadro_lista($resultado,"colegio",$configuracion,-1,0,100,0,"colegio",400);
	
	//echo $busqueda;
			if(is_array($resultado)){
				$html="	<table class='formulario'  align='center'>";
				$html.="	<tr >";
				$html.="		<td  width='25%'>Colegio donde curso su ultimo a&ntilde;o de bachillerato:</td>";
				$html.="		<td>".$colegio."</td>";
				$html.="	</tr>";
				$html.="</table>";
			}else{
				$html="	<table class='formulario'  align='center'>";
				$html.="	<tr >";
				$html.="		<td  width='25%'>Colegio donde curso su ultimo a&ntilde;o de bachillerato:</td>";
				$html.="		<td> No hay registros para esta consulta</td>";
				$html.="	</tr>";
				$html.="</table>";			
			}

	$respuesta = new xajaxResponse();
	$respuesta->addAssign("divColegio","innerHTML",$html);
	return $respuesta;					

}

function Departamento($id,$divAssign,$funcion){

	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		

		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"estudiante");

	
		$html.="<table  class='formulario'  align='center'>";

				$select=new html();
				$busqueda="SELECT ";
				$busqueda.="dep_cod,";
				$busqueda.="dep_nombre ";
				$busqueda.="FROM ";
				$busqueda.="mntge.gedepartamento";

				$configuracion["ajax_function"]="xajax_".$funcion;
				$configuracion["ajax_control"]="dep".$id;

				$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
				$departamento=$select->cuadro_lista($resultado,"dep".$id,$configuracion,-1,2,100,0,"dep".$id,400);
					
				$html.="<tr >";
				$html.="	<td width='30%'> Departamento de ".$id.":</td>";
				$html.="	<td>".$departamento."</td>";
				$html.="</tr>";

		$html.="</table>";
		$html.="<div id='".$funcion."'>";
		$html.="</div>";

	$respuesta = new xajaxResponse();
	$respuesta->addAssign($divAssign,"innerHTML",$html);
	return $respuesta;					

}
function MunicipioNacimiento($valor){

	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		

		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"estudiante");

	
		$html.="<table  class='formulario'  align='center'>";

				$select=new html();
				$busqueda="SELECT ";
				$busqueda.="mun_cod,";
				$busqueda.="mun_nombre ";
				$busqueda.="FROM ";
				$busqueda.="mntge.gemunicipio ";
				$busqueda.="WHERE mun_dep_cod=".$valor;

				$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
				$municipio=$select->cuadro_lista($resultado,"munnacimiento",$configuracion,-1,0,100,0,"",400);
					
				$html.="<tr >";
				$html.="	<td width='30%'> Municipio de nacimiento:</td>";
				$html.="	<td>".$municipio."</td>";
				$html.="</tr>";

		$html.="</table>";


	$respuesta = new xajaxResponse();
	$respuesta->addAssign("MunicipioNacimiento","innerHTML",$html);
	return $respuesta;					

}

function MunicipioProcedencia($valor){

	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		

		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"estudiante");

	
		$html.="<table  class='formulario'  align='center'>";

				$select=new html();
				$busqueda="SELECT ";
				$busqueda.="mun_cod,";
				$busqueda.="mun_nombre ";
				$busqueda.="FROM ";
				$busqueda.="mntge.gemunicipio ";
				$busqueda.="WHERE mun_dep_cod=".$valor;

				$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
				$municipio=$select->cuadro_lista($resultado,"munprocedencia",$configuracion,-1,0,100,0,"",400);
					
				$html.="<tr >";
				$html.="	<td width='30%'> Municipio de procedencia:</td>";
				$html.="	<td>".$municipio."</td>";
				$html.="</tr>";

		$html.="</table>";

	$respuesta = new xajaxResponse();
	$respuesta->addAssign("MunicipioProcedencia","innerHTML",$html);
	return $respuesta;					

}

function MunicipioExpulsion($valor){

	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		

		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"estudiante");

	
		$html.="<table  class='formulario'  align='center'>";

				$select=new html();
				$busqueda="SELECT ";
				$busqueda.="mun_cod,";
				$busqueda.="mun_nombre ";
				$busqueda.="FROM ";
				$busqueda.="mntge.gemunicipio ";
				$busqueda.="WHERE mun_dep_cod=".$valor;

				$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
				$municipio=$select->cuadro_lista($resultado,"munexpulsion",$configuracion,-1,0,100,0,"",400);
					
				$html.="<tr >";
				$html.="	<td width='30%'> Municipio de expulsi&oacute;n:</td>";
				$html.="	<td>".$municipio."</td>";
				$html.="</tr>";

		$html.="</table>";

	$respuesta = new xajaxResponse();
	$respuesta->addAssign("MunicipioExpulsion","innerHTML",$html);
	return $respuesta;					

}
?>
