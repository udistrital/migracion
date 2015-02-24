<?php
//Se invoca cuando se cambia de sede

function nombreCurso($valor){
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$variable=explode('#',$valor);
	//echo "mmm".$valor;
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
		
	
	$html=new html();
	
			$busqueda="SELECT ";
			$busqueda.="ASI_COD||'#'||REPLACE(ASI_NOMBRE, '&', '')||'#'||CUR_NRO, ";
			$busqueda.="ASI_COD||' '||ASI_NOMBRE||' '||CUR_NRO ";
			$busqueda.="FROM ";
			$busqueda.="ACASPERI, ACASI, ACCRA, ACCURSO ";
			$busqueda.="WHERE ";
			$busqueda.="CRA_COD =".$variable[0];
			$busqueda.=" AND ";
			$busqueda.="APE_ESTADO ='".$variable[1]."' ";
			$busqueda.="AND ";
			$busqueda.="APE_ANO = CUR_APE_ANO ";
			$busqueda.="AND ";
			$busqueda.="APE_PER = CUR_APE_PER ";
			$busqueda.="AND ";
			$busqueda.="ASI_COD = CUR_ASI_COD ";
			$busqueda.="AND ";
			$busqueda.="CRA_COD = CUR_CRA_COD ";
			$busqueda.="AND ";
			$busqueda.="CUR_ESTADO = 'A' ";
			$busqueda.="order by ASI_COD, CUR_NRO asc ";

		//echo $busqueda;
		$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");					
		
		if(is_array($resultado))
		{					
			for ($i=0; $i<count($resultado);$i++)
			{
				$registro[$i][0]=$resultado[$i][0];
				$registro[$i][1]=$resultado[$i][1];
                                //$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
			}
			$tab1=isset($tab)?$tab:'';
			$mi_cuadro=$html->cuadro_lista($registro,'curso',$configuracion,-1,3,FALSE,$tab1++,"curso",100);
		}					
		else
		{
			$mi_cuadro="No hay registros relacionados";
		}
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divCurso","innerHTML",$mi_cuadro);
	
	
	return $respuesta;						
}

function horaCurso($valor){
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$variable=explode('#',$valor);
	//echo "mmm".$valor;
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
		
	
	$html=new html();
	
			$busqueda="SELECT ";
			$busqueda.="COUNT(asignatura) ";
			$busqueda.="FROM ";
			$busqueda.="v_horario_curso_registrado ";
			$busqueda.="WHERE ";
			$busqueda.="asignatura=".$variable[0]." ";
			$busqueda.=" AND ";
			$busqueda.="grupo=".$variable[1]." ";
			$busqueda.="AND ";
			$busqueda.="anio=".$variable[2]." ";
			$busqueda.="AND ";
			$busqueda.="periodo=".$variable[3]."";
			
		//echo $busqueda;
		$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");					
		//echo $resultado[0][0];
		if(is_array($resultado))
		{
			$registro=$resultado[0][0];
			
			$mi_cuadro='<input maxlength="25" size="25" name="numhoras" value="'.$registro.'" id="numhoras" tabindex="'.$tab++.'">';
			//$mi_cuadro=$html->cuadro_texto('numhoras',$configuracion,$registro,'',0,'',5);
			
			  //cuadro_texto($nombre,$configuracion,$valor,$evento,$tab=0,$id="",$tamanno=40,$maximo=100,$estilo="")
			//$mi_cuadro=$html->cuadro_texto($registro,$configuracion,'horas',100,FALSE,$tab++);
		}					
		else
		{
			$mi_cuadro="No hay registros relacionados";
		}
		$respuesta = new xajaxResponse();
		$respuesta->addAssign("divHora","innerHTML",$mi_cuadro);
	
	
	return $respuesta;						


}

?>