<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2006                                      #
#    paulo_cesar@etb.net.co                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/

/****************************************************************************
  
loginCondor.action.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 14 de marzo de 2011

******************************************************************************
* @subpackage   
* @package	formulario
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @Actualización      	14/03/2011
* @author 		Jesús Neira Guio
* 
*
* Script de procesamiento del formulario de autenticacion de usuarios
*
*******************************************************************************/

$acceso_db=new dbms($configuracion);

if ($acceso_db->probar_conexion()==TRUE)
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");

	$nueva_sesion=new sesiones($configuracion);
	$nueva_sesion->especificar_enlace($acceso_db->obtener_enlace());
	$esta_sesion=$nueva_sesion->numero_sesion();
	$acceso_db->vaciar_temporales($configuracion,$esta_sesion);
	$nueva_sesion->borrar_sesion($configuracion,$esta_sesion);
	
	$id_usuario=$_REQUEST["usuario"];
	$usuario=$_REQUEST["usuario"];
	$tipo=isset($_REQUEST["nivel"])?$_REQUEST["nivel"]:"";
	
	if(!isset($_REQUEST["tipoUser"]))
	{
		$acceso=1;
	}
	else
	{
		
		switch($_REQUEST["modulo"])
		{
			case "matriculaEstudiante":
				$acceso=9;
			break;

			case "inscripcionGrado":
				$acceso=5;
			break;
			
			case "ActualizaDatos":
				$acceso=11;
			break;
			case "AdminBlogdev":
				$acceso=15;
			break;
			case "proyectoCurricular":
				$acceso=13;
			break;
			case "funcionarioCapacitacion":
				$acceso=24001;		//23=tipo de usuario  001=codigo de bloque
			break;
			case "adminInsertaAprobacionExt":
				$acceso=83001;		//83=tipo de usuario  001=codigo de bloque
			break;	
			case "adminInscritoGrado":
				$acceso=83002;		//83=tipo de usuario  001=codigo de bloque
			break;				
			case "inscribirCoordinacion":
				$acceso=83003;		//83=tipo de usuario  001=codigo de bloque
			break;
			case "consultaVotoEstudiante":
				$acceso=37001;		//37=tipo de usuario  001=codigo de bloque
			break;
			case "RegistroECAES":
				$acceso=14;		
			break;
			case "docentes":
				$acceso=16;		
			break;
			case "recibosAdmisiones":
				$acceso=17;		
			break;
			case "Preinscripcion":
				$acceso=18;		
			break;
			case "planTrabajo":
				$acceso=19;		
			break;
			case "adminUsuario":
				$acceso=20;		
			break;
			case "certificadoIngRet":
				$acceso=21;		
			break;
			case "desprendiblesPagos":
				$acceso=22;		
			break;
			case "cargaAcademica":
				$acceso=23;		
			break;
			case "controlPlanTrabajo":
				$acceso=24;		
			break;
			case "listaCursos":
				$acceso=25;
			break;
			case "IntensidadHoraria":
				$acceso=26;
			break;
			case "gestionHorarios":
				$acceso=27;
			break;
			case "adminReportes":
				$acceso=28;
			break;
			case "adminNovedadesNotas":
				$acceso=30;
			break;
			case "adminEvaldocentes":
				$acceso=31;
			break;
			case "admisiones":
				$acceso=32;
			break;
			case "adminIntensidadHorariaLote":
				$acceso=33;
			break;
			case "adminClave":
				$acceso=34;
			break;
                        case "imprimirFactura":
                                $acceso=83006;
                        break;
                        case "AsistenteContabilidad":
                                $acceso=109;
                        break;
		}
	}
	
	$esta_sesion=$nueva_sesion->numero_sesion();
	if (strlen($esta_sesion) != 32) 
	{
		$nueva_sesion->especificar_usuario($usuario);
		$nueva_sesion->especificar_nivel($acceso);
		$la_sesion=$nueva_sesion->crear_sesion($configuracion,'','');	
		$resultado = $nueva_sesion->guardar_valor_sesion($configuracion,"id_usuario",$id_usuario,$la_sesion);
	
	} 
	else 
	{
		if(!isset($_REQUEST["tipoUser"]))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
                        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion_usu_wo.class.php");

			$conexion=new dbConexion($configuracion);
			if($tipo==4 || $tipo==28){
                            $accesoOracle=$conexion->recursodb($configuracion,"coordinador");
                        }elseif($tipo==110 || $tipo==114){
                            $accesoOracle=$conexion->recursodb($configuracion,"asistente");
                        }
			$enlace=$accesoOracle->conectar_db();
                        $this->validacion=new validarUsu();
                        if($tipo==4 || $tipo==28){
			//Rescatar las Carreras a las que pertenece el coordinador
			
			$cadena_sql="SELECT ";
			$cadena_sql.="cra_cod ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACCRA ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="CRA_EMP_NRO_IDEN=".$usuario;
			
			//echo $cadena_sql;exit;
			$accesoOracle->registro_db($cadena_sql,0);
			$registro=$accesoOracle->obtener_registro_db();
                        }elseif($tipo==110 || $tipo==114){
                                $proyectos =$this->validacion->consultarProyectosAsistente($usuario,  $tipo,$accesoOracle,$configuracion,$acceso_db);
                                if(is_array($proyectos)){
                                        foreach ($proyectos as $key => $proyecto) {
                                           $registro[$key][0]= $proyecto[0];
                                           $registro[$key][1]= $proyecto[4];
                                       }
                                }
                        }
			if(is_array($registro))
			{
				error_reporting(0);
                                $i=0;
				$carrerasCoordinador="";
				while($registro[$i][0])
				{
					$carrerasCoordinador.=$registro[$i][0]."|";
					$i++;
				}
				$carrerasCoordinador=substr($carrerasCoordinador,0,(strlen($carrerasCoordinador)-1));
			}
			else
			{
			
				echo "Imposible determinar la carrera a la cual pertenece el coordinador";
			}
		}
		
		$resultado = $nueva_sesion->borrar_valor_sesion($configuracion,"usuario",$esta_sesion);
		$resultado = $nueva_sesion->guardar_valor_sesion($configuracion,"usuario",$usuario,$esta_sesion);
		$resultado = $nueva_sesion->borrar_valor_sesion($configuracion,"acceso",$esta_sesion);
		$resultado = $nueva_sesion->guardar_valor_sesion($configuracion,"acceso",$acceso,$esta_sesion);
		$resultado = $nueva_sesion->borrar_valor_sesion($configuracion,"id_usuario",$esta_sesion);
		$resultado = $nueva_sesion->guardar_valor_sesion($configuracion,"id_usuario",$id_usuario,$esta_sesion);
		$resultado = $nueva_sesion->borrar_valor_sesion($configuracion,"expiracion",$esta_sesion);
		$resultado = $nueva_sesion->guardar_valor_sesion($configuracion,"expiracion",time()+$configuracion["expiracion"],$esta_sesion);
		$resultado = $nueva_sesion->borrar_valor_sesion($configuracion,"identificacion",$esta_sesion);
		$resultado = $nueva_sesion->guardar_valor_sesion($configuracion,"identificacion",$_REQUEST["usuario"],$esta_sesion);
		$resultado = $nueva_sesion->borrar_valor_sesion($configuracion,"nivelUsuario",$esta_sesion);
		$resultado = $nueva_sesion->guardar_valor_sesion($configuracion,"nivelUsuario",$tipo,$esta_sesion);
	}
	
	$cadena_sql="SELECT ";
	$cadena_sql.=$configuracion["prefijo"]."subsistema.id_pagina, ";
	$cadena_sql.=$configuracion["prefijo"]."pagina.nombre ";
	$cadena_sql.="FROM ";
	$cadena_sql.=$configuracion["prefijo"]."subsistema, ";
	$cadena_sql.=$configuracion["prefijo"]."pagina ";
	$cadena_sql.="WHERE ";
	$cadena_sql.="id_subsistema='".$acceso."' ";
	$cadena_sql.="AND ";
	$cadena_sql.=$configuracion["prefijo"]."subsistema.id_pagina=".$configuracion["prefijo"]."pagina.id_pagina ";			
	$cadena_sql.="LIMIT 1";
	
	$campos=$acceso_db->registro_db($cadena_sql,0);
	
	if($campos>0)
	{
		$registro=$acceso_db->obtener_registro_db();
                  
		$tipopagina= isset($_REQUEST["tipopagina"])?$_REQUEST["tipopagina"]:'';
		if(!isset($_REQUEST['tipopagina'])){
			$tipopagina="pagina";
		}
		$variable=$tipopagina."=".$registro[0][1];
		$variable.="&nivel=".$tipo;	
                foreach ($_REQUEST as $key => $value) {
                    if($key != 'pagina' && $key != 'action'){
                        $variable.="&".$key."=".$value;	
                    }
                    
                }
	}
	else
	{
		echo "<table align=center><tr><td><h1>Sistema C&oacute;ndor</h1></td></tr></table>";
	}
		
		
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
	$cripto=new encriptar();
	if(isset($_REQUEST['parametro'])){
		$variable.=str_replace('@','&',$_REQUEST['parametro']);	
	}
	
	$variable=$cripto->codificar_url($variable,$configuracion);

	//echo $pagina.$variable;
	echo "<script>location.replace('".$pagina.$variable."')</script>";     	
		
}
else
{
	include_once($configuracion["raiz_documento"].$configuracion["clase"]."/mensaje.class.php");
	
	$el_mensaje=new mensaje;
	$el_mensaje->mensaje("error_conexion",$configuracion);
	exit;
}


	
?>
