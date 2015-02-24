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

Última revisión 22 de julio de 2008

******************************************************************************
* @subpackage   
* @package	formulario
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* 
*
* Script de procesamiento del formulario de autenticacion de usuarios
*
*******************************************************************************/

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbms.class.php");
$acceso_db=new dbms($configuracion);

if ($acceso_db->probar_conexion()==TRUE)
{
	$nueva_sesion=new sesiones($configuracion);
	$nueva_sesion->especificar_enlace($acceso_db->obtener_enlace());
	$esta_sesion=$nueva_sesion->numero_sesion();
	$acceso_db->vaciar_temporales($configuracion,$esta_sesion);
	$nueva_sesion->borrar_sesion($configuracion,$esta_sesion);
	
	$id_usuario=$_REQUEST["usuario"];
	$usuario=$_REQUEST["usuario"];
	$nombre_pagina=$_REQUEST["pagina"];

        
	if(!isset($_REQUEST["tipoUser"]))
	{
		$acceso=1;
	}
	else
	{
		
		switch($_REQUEST["modulo"])
		{
			case "Coordinador":
			      $acceso=3;
			break;

			case "Estudiante":
			     $acceso=2;	
			break;

			case "estudiante":
			     $acceso=2;	
			break;

			case "admin_sga":
			     $acceso=4;
			break;

			case "AsistenteVicerrectoria":
			      $acceso=5;	  
			break;

			case "secretario":
			      $acceso=6;
			break;

                        case "decano":
			      $acceso=7;
			break;

                        case "Docente":
			      $acceso=8;
			break;

                        case "Funcionario":
			      $acceso=9;
			break;
                        case "AsistenteContabilidad":
                              $acceso=109;
                        break;
			case "Bienestar":
			      $acceso=68;
			break;
			case "soporte":
				$acceso=11;
			break;
			case "asistente":
				$acceso=12;
			break;
			case "secgeneral":
				$acceso=14;
			break;
			case "secretario":
				$acceso=15;
			break;
			case "asistenteCeri":
				$acceso=16;
			break;
			case "laboratorios":
				$acceso=17;
			break;
			case "asistenteILUD":
				$acceso=18;
			break;
			case "consultor":
				$acceso=19;
			break;
			case "egresado":
				$acceso=20;
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
		$resultado = $nueva_sesion->guardar_valor_sesion($configuracion,"nivelUsuario",$_REQUEST['tipoUser'],$la_sesion);
		$resultado = $nueva_sesion->guardar_valor_sesion($configuracion,"identificacion",$_REQUEST['usuario'],$la_sesion);
	
	}
	else 
	{
		if(!isset($_REQUEST["tipoUser"]))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
			$conexion=new dbConexion($configuracion);
			$accesoOracle=$conexion->recursodb($configuracion,"oraclesga");
			$enlace=$accesoOracle->conectar_db();
			
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
			
			if(is_array($registro))
			{
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
		$nueva_sesion->borrar_valor_sesion($configuracion,"nivelUsuario",$esta_sesion);
		$resultado = $nueva_sesion->guardar_valor_sesion($configuracion,"nivelUsuario",$_REQUEST["tipoUser"],$esta_sesion);
	}
	
	
        if(isset($nombre_pagina)){
        	$cadena_sql="SELECT PAG.id_pagina, PAG.nombre FROM ";
		$cadena_sql.=$configuracion["prefijo"]."pagina PAG WHERE ";
		$cadena_sql.="PAG.nombre='".$nombre_pagina."'";
		
        }  
	else{
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
	
	}

	$campos=$acceso_db->registro_db($cadena_sql,0);

	
	if($campos>0)
	{
		$registro=$acceso_db->obtener_registro_db();
		
                $tipopagina= isset($_REQUEST["tipopagina"])?$_REQUEST["tipopagina"]:'';
		if(!isset($_REQUEST['tipopagina'])){
			$tipopagina="pagina";
		}
		$variable=$tipopagina."=".$registro[0][1];
                foreach ($_REQUEST as $key => $value) {
                    if($key != 'pagina' && $key != 'action' && $key != 'aplicacion'){
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
	$variable=$cripto->codificar_url($variable,$configuracion);
       
	
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
