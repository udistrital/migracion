<?php
//Se invoca cuando se cambia de pais

function rescatarSalon($valor){
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$variable=explode('#',$valor);
//	echo "salon";var_dump($variable);
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"docente");
	$tab=0;	
	
	$html=new html();
	
			$busqueda="SELECT ";
			$busqueda.="sal_id_espacio, ";
			$busqueda.="sal_id_espacio||' '||sal_nombre ";
			$busqueda.="FROM ";
			$busqueda.="gesalones x, geedificio ";
			$busqueda.="WHERE ";
			$busqueda.=" sal_edificio ='".$variable[0]."'";
			$busqueda.=" AND sal_estado ='A'";
			$busqueda.=" AND sal_edificio=edi_cod ";
                if($variable[0]<>'EPAS')            
                        {
			$busqueda.=" AND sal_id_espacio NOT IN (SELECT hor_sal_id_espacio ";
			$busqueda.=" FROM achorarios ";
			$busqueda.=" INNER JOIN accursos ON cur_id=hor_id_curso ";
			$busqueda.=" INNER JOIN gesalones ON hor_sal_id_espacio=sal_id_espacio ";
			$busqueda.=" WHERE sal_sed_id = x.sal_sed_id ";
			$busqueda.=" AND cur_ape_ano = ".$variable[1]." ";
			$busqueda.=" AND cur_ape_per = ".$variable[2]." ";
			$busqueda.=" AND hor_dia_nro = ".$variable[3]." ";
			$busqueda.=" AND hor_hora = ".$variable[4].") ";
                        }   
			$busqueda.=" ORDER BY sal_id_espacio";
		
                
		$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");					
		
		if(is_array($resultado))
		{					
			for ($i=0; $i<count($resultado);$i++)
			{
				$registro[$i][0]=$resultado[$i][0];
				$registro[$i][1]=$resultado[$i][1];
                                //$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
			}
			$mi_cuadro=$html->cuadro_lista($registro,'salon',$configuracion,-1,0,FALSE,$tab++,"salon",200);
		}					
		else
		{
			$mi_cuadro="No hay registros relacionados";
		}
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divSalon","innerHTML",$mi_cuadro);
	
	
	return $respuesta;						


}



function rescatarEdificio($valor){
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$variable=explode('#',$valor);
//        echo "edificio";var_dump($variable);
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"docente");
		
	$tab=0;
	$html=new html();
	
			$busqueda="SELECT ";
			$busqueda.="edi_cod||'#'||'".$variable[1]."'||'#'||'".$variable[2]."'||'#'||'".$variable[3]."'||'#'||'".$variable[4]."', ";
			$busqueda.="edi_cod||' '||edi_nombre ";
			$busqueda.="FROM ";
			$busqueda.="geedificio x ";
			$busqueda.="WHERE ";
			$busqueda.="edi_sed_id ='".$variable[0]."'";
			$busqueda.=" AND ";
			$busqueda.="edi_estado ='A' ";
			$busqueda.=" ORDER BY edi_cod";
		$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");					
		$configuracion["ajax_function"]="xajax_rescatarSalon";
		$configuracion["ajax_control"]="edificio";
													
		if(is_array($resultado)){					
		
			for ($i=0; $i<count($resultado);$i++)
			{
				$registro[$i][0]=$resultado[$i][0];
				$registro[$i][1]=$resultado[$i][1];
                                //$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
			}
			$mi_cuadro=$html->cuadro_lista($registro,'edificio',$configuracion,-1,3,FALSE,$tab++,"edificio",100);
                        $mi_salon="Seleccione el Edificio";
		}					
		else
		{
			$mi_cuadro="No hay registros relacionados";
		}
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divEdificio","innerHTML",$mi_cuadro);
		$respuesta->addAssign("divSalon","innerHTML",$mi_salon);
	
	
	return $respuesta;						


}

        function borrarHorario($cod_salon,$cod_hora,$cod_curso,$anio,$periodo){
            echo "a borrar Horario";
        }


?>