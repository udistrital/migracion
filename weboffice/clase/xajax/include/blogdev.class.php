<?php
//Se invoca cuando se cambia de pais

function clasedeObjeto($valor){
	
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 

	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
	//Buscar un registro que coincida con el valor
			
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"bitacora");
		
	$html=new html();
	
	$busqueda="SELECT unique ";
	$busqueda.="apl_cod||'#'||clo_cod, ";
	$busqueda.="clo_nom ";
	$busqueda.="FROM ";
	$busqueda.="claobj,aplicaciones,tipobj ";
	$busqueda.="WHERE apl_cod=tio_apl_cod ";
	$busqueda.="AND tio_clo_cod=clo_cod ";
	$busqueda.="AND apl_cod=".$valor;
	//echo "mmm".$busqueda;
				
	$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
	$variable=$resultado[0][0];					
	if(is_array($resultado))
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		$html=new html();
		$configuracion["ajax_function"]="xajax_tipodeObjeto";
		$configuracion["ajax_control"]="claseobjeto";
		for ($i=0; $i<count($resultado);$i++)
		{
			$registro[$i][0]=$resultado[$i][0];
			$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
		}		
		$mi_cuadro=$html->cuadro_lista($registro,'claseobjeto',$configuracion,-1,2,FALSE,$tab++,"claseobjeto",100);
	}
	else
	{
		$mi_cuadro="No hay registros relacionados";
	}					

	$respuesta = new xajaxResponse();
	$respuesta->addAssign("divClaobjeto","innerHTML",$mi_cuadro);
	
	return $respuesta;						

}
function tipodeObjeto($valor){
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$variable=explode('#',$valor);
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"bitacora");

	$busqueda="SELECT unique ";
	$busqueda.="tio_cod, ";
	$busqueda.="tio_nom ";
	$busqueda.="FROM ";
	$busqueda.="tipobj,claobj,aplicaciones ";
	$busqueda.="WHERE ";
	$busqueda.="apl_cod=tio_apl_cod ";
	$busqueda.="AND ";
	$busqueda.="tio_clo_cod=clo_cod ";
	$busqueda.=" AND ";
	$busqueda.="tio_clo_cod =".$variable[1];	
	$busqueda.=" AND ";
	$busqueda.="apl_cod =".$variable[0] ;
	$busqueda.=" ORDER BY tio_nom";
	
	//echo "mmm".$busqueda;	
	$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");	
					
	if(is_array($resultado))
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		$html=new html();
		$configuracion["ajax_function"]="xajax_Objeto";
		$configuracion["ajax_control"]="tipoobjeto";
		for ($i=0; $i<count($resultado);$i++)
		{
			$registro[$i][0]=$resultado[$i][0];
			$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
		}
		$mi_cuadro=$html->cuadro_lista($registro,'tipoobjeto',$configuracion,-1,2,FALSE,$tab++,"tipoobjeto",100);
	}					
	else
	{
		$mi_cuadro="No hay registros relacionados";
	}
					
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divTipobj","innerHTML",$mi_cuadro);
	
	return $respuesta;
}

function Objeto($valor){
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"bitacora");
		
	
	$html=new html();
	
			$busqueda="SELECT ";
			$busqueda.="obj_cod, ";
			$busqueda.="obj_nombre ";
			$busqueda.="FROM ";
			$busqueda.="objetos ";
			$busqueda.="WHERE ";
			$busqueda.="obj_tio_cod =".$valor;
			$busqueda.=" ORDER BY obj_nombre";		
							
		$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");					
		
		if(is_array($resultado))
		{					
			for ($i=0; $i<count($resultado);$i++)
			{
				$registro[$i][0]=$resultado[$i][0];
				$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
			}
			$mi_cuadro=$html->cuadro_lista($registro,'objeto',$configuracion,-1,2,FALSE,$tab++,"objeto",100);
		}					
		else
		{
			$mi_cuadro="No hay registros relacionados";
		}
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divObjeto","innerHTML",$mi_cuadro);
	
	
	return $respuesta;						


}
?>