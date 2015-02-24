<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 4 de febrero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		admin_solicitud
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
* @author			Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	caso de uso: REGISTRAR ESTUDIANTE PARA GRADO
*
/*--------------------------------------------------------------------------------------------------------------------------*/


if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

//Evitar que se ingrese codigo HTML y PHP en los campos de texto
foreach ($_REQUEST as $clave => $valor) 
{
    $_REQUEST[$clave]= strip_tags($valor);   
}

//Conexion a la base de datos ORACLE
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
$conexion=new dbConexion($configuracion);
$accesoOracle=$conexion->recursodb($configuracion,"oracle");
$enlace=$accesoOracle->conectar_db();

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
$cripto=new encriptar();

$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
if (is_resource($enlace))
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
	
	$sesion=new sesiones($configuracion);
	$sesion->especificar_enlace($enlace);
	$registro=$sesion->rescatar_valor_sesion($configuracion,"id_usuario");
	
	if(is_array($registro))
	{
		$usuario=$registro[0][0];
	
	
		$registro=$sesion->rescatar_valor_sesion($configuracion,"identificacion");
		
		if(is_array($registro))
		{
			$_REQUEST["registro"]=$registro[0][0];
		}
		
		
		if(!isset($_REQUEST['cancelar']))
		{
			if(!isset($_REQUEST["confirmacion"]))
			{
				//Verificacion de validez de los datos en el servidor
				if((!(strlen($_REQUEST['nombre'])>2)||!(strlen($_REQUEST['apellido'])>2)||!(strlen($_REQUEST['correo'])>6) && (!isset($_REQUEST['confirmacion']))))
				{
					//Instanciar a la clase pagina con mensaje de correcion de datos
				}
				else
				{
					//Verificar validez del correo electronico
					
								
						include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
						if(cadenas::verificarCorreoElectronico($_REQUEST["correo"]))
						{
								
							//Validar las variables para evitar ataques por insercion de SQL
				
							$_REQUEST=$acceso_db->verificar_variables($_REQUEST);	
									
									
							$cadena_sql=sqlRegistroUsuario($configuracion, "usuario",$_REQUEST['registro']);
							$campos=$acceso_db->registro_db($cadena_sql,0);
							$identificador=time();
							//Si el usuario ya existe
							if($campos>0)
							{
								if(!isset($_REQUEST["solicitud"]))
								{
									usuarioAntiguo($configuracion,$acceso_db);
								}
								else
								{
									nuevoUsuario($configuracion ,$acceso_db, $accesoOracle);
								}
							}
							else
							{
								nuevoUsuario($configuracion ,$acceso_db, $accesoOracle);
							}
							
						
						}
					
					
				}
			}
			else
			{
				confirmarInscripcion($configuracion ,$acceso_db, $enlace);
			}
		}
		else
		{
			redireccionarInscripcion($configuracion, "principal");	
		}
	}
	else
	{
		//No se tiene un usuario valido
	
	}
} 
else
{
	//Mensaje de error de no disponibilidad de base de datos 
		
}


/****************************************************************************************
*				Funciones						*
****************************************************************************************/
function confirmarInscripcion($configuracion ,$acceso_db,$enlace)
{
	//Revisar si el identificador existe.
	//Pasar de la tabla borrador a la tabla definitiva... 
	//Si han cancelado entonces borrar borrador y redireccionar al indice...
	$cadena_sql=sqlRegistroUsuario($configuracion, "rescatarBorrador",$_REQUEST['identificador']);
	$campos=$acceso_db->registro_db($cadena_sql,0);
	$registro=$acceso_db->obtener_registro_db();
	if($campos>0)
	{
			$valor[0]=$registro[0][0];
			//Borrar registros anteriores TODO implementar UPDATE
			$cadena_sql=sqlRegistroUsuario($configuracion, "borrarUsuario",$valor);
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
			
			$cadena_sql=sqlRegistroUsuario($configuracion, "borrarUsuarioDatos",$valor);
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
			
			$cadena_sql=sqlRegistroUsuario($configuracion, "borrarUsuarioDocumento",$valor);
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
			
			$valor[0]=$registro[0][0];
			$valor[1]=$registro[0][1];
			$valor[2]=$registro[0][2];
			$valor[3]=$registro[0][3];
			$valor[4]=time();
			
			$cadena_sql=sqlRegistroUsuario($configuracion, "insertarUsuario",$valor);
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql); 
			
			unset($valor);
			$valor[0]=$registro[0][0];
			$valor[1]=$registro[0][7];
			$valor[2]=$registro[0][8];
			$valor[3]=$registro[0][9];
			$valor[4]=$registro[0][10];
			$valor[5]=$registro[0][11];
			$valor[6]=$registro[0][12];
			$valor[7]=$registro[0][13];
			$valor[8]=time();
			
			$cadena_sql=sqlRegistroUsuario($configuracion, "insertarUsuarioDatos",$valor);	
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);	
			
			unset($valor);
			$valor[0]=$registro[0][0];
			$valor[1]=$registro[0][5];
			$valor[2]=$registro[0][6];
			$valor[3]=$registro[0][4];
			$valor[4]=time();
			
			$cadena_sql=sqlRegistroUsuario($configuracion, "insertarUsuarioDocumento",$valor);	
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);	
			
			
			unset($valor);
			$valor[0]=$registro[0][0];
			$valor[1]=time();
			$valor[2]=$registro[0][14];
			$valor[3]=$registro[0][15];
			$valor[4]=$registro[0][16];
			$valor[5]=time();
			
			//Actualizar Inscripciones
			$cadena_sql=sqlRegistroUsuario($configuracion, "actualizarInscripcion",$valor);
			$resultado=$acceso_db->ejecutarAcceso($cadena_sql,"");
			
			$cadena_sql=sqlRegistroUsuario($configuracion, "insertarInscripcionGrado",$valor);	
			$resultado=$acceso_db->ejecutarAcceso($cadena_sql,"");	
			
			if($resultado==TRUE)
			{
				$ultimoInsertado=$acceso_db-> ultimo_insertado($enlace);
				$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador1",$valor);
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
				$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador2",$valor);
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
				$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador3",$valor);
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
				$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador4",$valor);
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
				
				redireccionarInscripcion($configuracion, "exitoInscripcion",$ultimoInsertado);
			}
			else
			{
				exit;
			}
	}
}

function enviar_correo($configuracion)
{

	$destinatario=$configuracion["correo"];
	$encabezado="Nuevo Usuario ".$configuracion["titulo"];
	
	$mensaje="Administrador:\n";
	$mensaje.=$_REQUEST['nombre']." ".$_REQUEST['apellido']."\n";
	$mensaje.="Correo Electronico:".$_REQUEST['correo']."\n";
	$mensaje.="Telefono:".$_REQUEST['telefono']."\n\n";
	$mensaje.="Ha solicitado acceso a ".$configuracion["titulo"]."\n\n";
	$mensaje.="Por favor visite la seccion de administracion para gestionar esta peticion.\n";
	$mensaje.="_____________________________________________________________________\n";
	$mensaje.="Por compatibilidad con los servidores de correo, en este mensaje se han omitido a\n";
	$mensaje.="proposito las tildes.";
	
	$correo= mail($destinatario, $encabezado,$mensaje) ;
	
	
	$destinatario=$_REQUEST['correo'];
	$encabezado="Solicitud de Confirmacion ".$configuracion["titulo"];
	
	
	$mensaje="Hemos recibido una solicitud para acceder al portal web\n";
	$mensaje.=$configuracion["titulo"];
	$mensaje.="en donde se referencia esta direccion de correo electronico.\n\n";
	$mensaje.="Si efectivamente desea inscribirse a nuestra comunidad por favor seleccione el siguiente enlace:\n";	
	$mensaje="En caso contrario por favor omita el contenido del presente mensaje.";
	$mensaje.="_____________________________________________________________________\n";
	$mensaje.="Por compatibilidad con los servidores de correo en este mensaje se han omitido a\n";
	$mensaje.="proposito las tildes.";
	$mensaje.="_____________________________________________________________________\n";
	$mensaje.="Si tiene inquietudes por favor envie un correo a: ".$configuracion["correo"]."\n";
	
	$correo= mail($destinatario, $encabezado,$mensaje) ;


}


function nuevoUsuario($configuracion,$acceso_db, $accesoOracle)
{
	//Verificar existencia del usuario 
	//$cadena_sql=sqlRegistroUsuario($configuracion, "datosEstudiante",$_REQUEST['registro']);	
	//$unUsuario=$accesoOracle->ejecutarAcceso($cadena_sql,"busqueda");
	$unUsuario[0]=1;
	if(is_array($unUsuario))
	{
		
		//Valores a ingresar
		if(isset($_REQUEST['codigo']))
		{
			$elUsuario=$_REQUEST['codigo'];
		}
		else
		{
			$elUsuario=$_REQUEST['registro'];
		}
		
		
		$valor[0]=$elUsuario;
		$valor[1]=$_REQUEST['nombre'];
		$valor[2]=$_REQUEST['apellido'];
		$valor[3]=$_REQUEST['sexo'];
		
		$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador1",$valor);
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
		$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador2",$valor);
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
		$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador3",$valor);
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
		$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador4",$valor);
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
		
		$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorrador",$valor);
		//exit;
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
				
		unset($valor);
		$valor[0]=$elUsuario;
		$valor[1]=$_REQUEST['direccion'];
		$valor[2]=$_REQUEST['pais'];
		$valor[3]=$_REQUEST['region'];
		$valor[4]=$_REQUEST['ciudad'];
		$valor[5]=$_REQUEST['telefono'];
		$valor[6]=$_REQUEST['celular'];
		$valor[7]=$_REQUEST['correo'];
		
		$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorradorDatos",$valor);	
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);	
		
		unset($valor);
		$valor[0]=$elUsuario;
		$valor[1]=$_REQUEST['identificacion'];
		$valor[2]=$_REQUEST['ciudadIdentificacion'];
		$valor[3]=$_REQUEST['id_tipo_documento'];
		
		$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorradorDocumento",$valor);	
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);	
		
		
		unset($valor);
		$valor[0]=$elUsuario;
		$valor[1]=$_REQUEST['tituloTrabajo'];
		$valor[2]=$_REQUEST['directorTrabajo'];
		$valor[3]=$_REQUEST['tipoTrabajo'];
		
		$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorradorinscripcionGrado",$valor);	
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);	
		
		if($resultado==TRUE)
		{
			if(!isset($_REQUEST["admin"]))
			{
				//enviar_correo($configuracion);
				if(isset($_REQUEST['codigo']))
				{
				
					reset($_REQUEST);
					while(list($clave,$value)=each($_REQUEST))
					{
						unset($_REQUEST[$clave]);
							
					}
					redireccionarInscripcion($configuracion, "confirmacionCoordinador",$valor[0]);
				}
				else
				{
				
					reset($_REQUEST);
					while(list($clave,$value)=each($_REQUEST))
					{
						unset($_REQUEST[$clave]);
							
					}
					redireccionarInscripcion($configuracion, "confirmacion",$valor[0]);
				}
			}
			else
			{
				
				redireccionarInscripcion($configuracion,"administracion");		
				
			}
		}
		else
		{
			exit;
		}
	}
	else
	{
		echo "<table align=center><tr><td><h3>IMPOSIBLE GUARDAR EL FORMULARIO</h3></td></tr></table>";	
	}
}

function sqlRegistroUsuario($configuracion, $opcion, $valor)
{
	switch($opcion)
	{
	
		case "usuario":
		$cadena_sql="SELECT ";
		$cadena_sql.="* ";
		$cadena_sql.="FROM ";
		$cadena_sql.="".$configuracion["prefijo"]."inscripcionGrado ";
		$cadena_sql.="WHERE ";
		$cadena_sql.="codigo='".$valor."' ";
		$cadena_sql.="LIMIT 1";
		break;
		
				
		case  "rescatarUsuario":		
			$cadena_sql="SELECT ";
			$cadena_sql.="`id_usuario`, ";
			$cadena_sql.="`nombre`, ";
			$cadena_sql.="`apellido`, ";
			$cadena_sql.="`correo`, ";
			$cadena_sql.="`telefono`, ";
			$cadena_sql.="`usuario`, ";
			$cadena_sql.="`clave` ";
			$cadena_sql.="FROM ";
			$cadena_sql.="".$configuracion["prefijo"]."registrado ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id_usuario='".$valor."' ";			
			$cadena_sql.="LIMIT 1";
			break;
			
	
		case "datosEstudiante":
			//En ORACLE
			$cadena_sql="SELECT ";
			$cadena_sql.="est_cod, ";
			$cadena_sql.="est_nro_iden, ";
			$cadena_sql.="est_nombre, ";
			$cadena_sql.="est_cra_cod, ";
			$cadena_sql.="est_diferido, ";
			$cadena_sql.="est_estado_est, ";
			$cadena_sql.="emb_valor_matricula vr_mat, ";
			$cadena_sql.="cra_abrev ";
			$cadena_sql.="FROM ";
			$cadena_sql.="acest, ";
			$cadena_sql.="V_ACESTMATBRUTO, ";
			$cadena_sql.="ACCRA ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="est_cod =".$variable." ";
			$cadena_sql.="AND ";
			$cadena_sql.="emb_est_cod = est_cod ";
			$cadena_sql.="AND ";
			$cadena_sql.="cra_cod = est_cra_cod";
			break;
			
		case "identificacion":
			$cadena_sql="SELECT ";
			$cadena_sql.="* ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."registrado ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="identificacion='".$valor."'";
			break;
			
		case "insertarBorrador":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario "; 
			$cadena_sql.="( ";
			$cadena_sql.="`codigo`, ";
			$cadena_sql.="`nombre`, ";
			$cadena_sql.="`apellido`, ";
			$cadena_sql.="`sexo` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$valor[0]."', ";
			$cadena_sql.="'".$valor[1]."', ";
			$cadena_sql.="'".$valor[2]."', ";
			$cadena_sql.="'".$valor[3]."' ";
			$cadena_sql.=")";
			break;
		
		case "insertarBorradorDocumento":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDocumento "; 
			$cadena_sql.="( ";
			$cadena_sql.="`codigo`, ";
			$cadena_sql.="`numero`, ";
			$cadena_sql.="`lugar`, ";
			$cadena_sql.="`tipo` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$valor[0]."', ";
			$cadena_sql.="'".$valor[1]."', ";
			$cadena_sql.="'".$valor[2]."', ";
			$cadena_sql.="'".$valor[3]."' ";
			$cadena_sql.=")";
			break;
		
		case "insertarBorradorinscripcionGrado":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."borradorInscripcionGrado "; 
			$cadena_sql.="( ";
			$cadena_sql.="`codigo`, ";
			$cadena_sql.="`fecha`, ";
			$cadena_sql.="`nombreTrabajo`, ";
			$cadena_sql.="`director`, ";
			$cadena_sql.="`tipoTrabajo` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$valor[0]."', ";
			$cadena_sql.="'".time()."', ";
			$cadena_sql.="'".$valor[1]."', ";
			$cadena_sql.="'".$valor[2]."', ";
			$cadena_sql.="'".$valor[3]."' ";
			$cadena_sql.=")";
			
			break;
			
		case "rescatarBorrador":
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.`codigo`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.`nombre`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.`apellido`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.`sexo`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDocumento.`tipo`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDocumento.`numero`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDocumento.`lugar`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`direccion`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`pais`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`region`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`ciudad`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`telefono`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`celular`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`correo`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorInscripcionGrado.`nombreTrabajo`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorInscripcionGrado.`director`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorInscripcionGrado.`tipoTrabajo` ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario, ".$configuracion["prefijo"]."borradorUsuarioDocumento, "; 
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos, ".$configuracion["prefijo"]."borradorInscripcionGrado ";
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo='".$valor."' ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo=".$configuracion["prefijo"]."borradorUsuarioDocumento.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo=".$configuracion["prefijo"]."borradorUsuarioDatos.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo=".$configuracion["prefijo"]."borradorInscripcionGrado.codigo ";
			$cadena_sql.="LIMIT 1";			
			break;
			
		case "actualizarInscripcion":
			$cadena_sql="UPDATE ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado ";
			$cadena_sql.="SET ";
			$cadena_sql.="`estado` = '2' ";
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`codigo` = ".$valor[0];
			break;
		
		case "inscripcionGrado":
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`codigo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`nombre`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`apellido`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`sexo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`tipo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`numero`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`lugar`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`direccion`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`pais`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`region`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`ciudad`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`telefono`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`celular`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`correo`, ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`nombreTrabajo`, ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`director`, ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`tipoTrabajo`, ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`idInscripcion` ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."usuario, ".$configuracion["prefijo"]."usuarioDocumento, "; 
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos, ".$configuracion["prefijo"]."inscripcionGrado ";
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo='".$_REQUEST["registro"]."' ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."usuarioDocumento.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."usuarioDatos.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."inscripcionGrado.codigo ";
			$cadena_sql.="ORDER BY ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.fecha DESC ";	
			$cadena_sql.="LIMIT 1";	
			//echo $cadena_sql;exit;
			break;
			
			
		case "insertarBorradorDatos":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos "; 
			$cadena_sql.="( ";
			$cadena_sql.="`codigo`, ";
			$cadena_sql.="`direccion`, ";
			$cadena_sql.="`pais`, ";
			$cadena_sql.="`region`, ";
			$cadena_sql.="`ciudad`, ";
			$cadena_sql.="`telefono`, ";
			$cadena_sql.="`celular`, ";
			$cadena_sql.="`correo` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$valor[0]."', ";
			$cadena_sql.="'".$valor[1]."', ";
			$cadena_sql.="'".$valor[2]."', ";
			$cadena_sql.="'".$valor[3]."', ";
			$cadena_sql.="'".$valor[4]."', ";
			$cadena_sql.="'".$valor[5]."', ";
			$cadena_sql.="'".$valor[6]."', ";
			$cadena_sql.="'".$valor[7]."' ";	
			$cadena_sql.=")";
					
			break;
		
		
			
		case "insertarInscripcionGrado":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado "; 
			$cadena_sql.="( ";
			$cadena_sql.="`idInscripcion`, ";
			$cadena_sql.="`codigo`, ";
			$cadena_sql.="`fecha`, ";
			$cadena_sql.="`nombreTrabajo`, ";
			$cadena_sql.="`director`, ";
			$cadena_sql.="`tipoTrabajo` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="NULL, ";
			$cadena_sql.="'".$valor[0]."', ";
			$cadena_sql.="'".$valor[1]."',";
			$cadena_sql.="'".$valor[2]."', ";
			$cadena_sql.="'".$valor[3]."', ";
			$cadena_sql.="'".$valor[4]."' ";
			$cadena_sql.=")";	
			break;
		
		
		
		case "insertarUsuarioDocumento":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento "; 
			$cadena_sql.="( ";
			$cadena_sql.="`codigo`, ";
			$cadena_sql.="`numero`, ";
			$cadena_sql.="`lugar`, ";
			$cadena_sql.="`tipo`, ";
			$cadena_sql.="`fecha` ";			
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$valor[0]."', ";
			$cadena_sql.="'".$valor[1]."', ";
			$cadena_sql.="'".$valor[2]."', ";
			$cadena_sql.="'".$valor[3]."', ";
			$cadena_sql.="'".$valor[4]."' ";
			$cadena_sql.=")";
			break;
		
		case "insertarUsuarioDatos":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos "; 
			$cadena_sql.="( ";
			$cadena_sql.="`codigo`, ";
			$cadena_sql.="`direccion`, ";
			$cadena_sql.="`pais`, ";
			$cadena_sql.="`region`, ";
			$cadena_sql.="`ciudad`, ";
			$cadena_sql.="`telefono`, ";
			$cadena_sql.="`celular`, ";
			$cadena_sql.="`correo`, ";
			$cadena_sql.="`fecha` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$valor[0]."', ";
			$cadena_sql.="'".$valor[1]."', ";
			$cadena_sql.="'".$valor[2]."', ";
			$cadena_sql.="'".$valor[3]."', ";
			$cadena_sql.="'".$valor[4]."', ";
			$cadena_sql.="'".$valor[5]."', ";
			$cadena_sql.="'".$valor[6]."', ";
			$cadena_sql.="'".$valor[7]."', ";	
			$cadena_sql.="'".$valor[8]."' ";
			$cadena_sql.=")";
					
			break;
			
		case "insertarUsuario":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."usuario "; 
			$cadena_sql.="( ";
			$cadena_sql.="`codigo`, ";
			$cadena_sql.="`nombre`, ";
			$cadena_sql.="`apellido`, ";
			$cadena_sql.="`sexo`, ";
			$cadena_sql.="`fecha` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$valor[0]."', ";
			$cadena_sql.="'".$valor[1]."', ";
			$cadena_sql.="'".$valor[2]."', ";
			$cadena_sql.="'".$valor[3]."', ";
			$cadena_sql.="'".$valor[4]."' ";
			$cadena_sql.=")";
			break;
			
			
		case "actualizar":
			$cadena_sql="UPDATE ";
			$cadena_sql.=$configuracion["prefijo"]."registrado "; 
			$cadena_sql.="SET "; 
			$cadena_sql.="`id_usuario`='".$valor[0]."', ";
			$cadena_sql.="`nombre`='".$valor[1]."', ";
			$cadena_sql.="`apellido`='".$valor[2]."', ";
			$cadena_sql.="`correo`='".$valor[3]."', ";
			$cadena_sql.="`telefono`='".$valor[4]."', ";
			$cadena_sql.="`usuario`='".$valor[5]."', ";
			$cadena_sql.= "`clave`='".$valor[6]."' ";
			$cadena_sql.="WHERE "; 
			$cadena_sql.="`id_usuario`='".$valor[0]."' ";
			break;
			
		case "eliminarBorrador1":
			$cadena_sql="DELETE ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario ";
			$cadena_sql.="WHERE "; 
			$cadena_sql.="`codigo`='".$valor[0]."' ";
			break;
		
		case "eliminarBorrador2":
			
			$cadena_sql="DELETE ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos ";  
			$cadena_sql.="WHERE "; 
			$cadena_sql.="`codigo`='".$valor[0]."' ";
			break;
			
		case "eliminarBorrador3":
			$cadena_sql="DELETE ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDocumento "; 
			$cadena_sql.="WHERE "; 
			$cadena_sql.="`codigo`='".$valor[0]."' ";
			break;
			
		case "eliminarBorrador4":
			$cadena_sql="DELETE ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."borradorInscripcionGrado "; 
			$cadena_sql.="WHERE "; 
			$cadena_sql.="`codigo`='".$valor[0]."' ";
			
			break;
		
		case "borrarUsuario":
			$cadena_sql="DELETE ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."Usuario ";
			$cadena_sql.="WHERE "; 
			$cadena_sql.="`codigo`='".$valor[0]."' ";
			break;
		
		case "borrarUsuarioDatos":
			
			$cadena_sql="DELETE ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."UsuarioDatos ";  
			$cadena_sql.="WHERE "; 
			$cadena_sql.="`codigo`='".$valor[0]."' ";
			break;
			
		case "borrarUsuarioDocumento":
			$cadena_sql="DELETE ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."UsuarioDocumento "; 
			$cadena_sql.="WHERE "; 
			$cadena_sql.="`codigo`='".$valor[0]."' ";
			break;
			
		
	}
	//echo $cadena_sql."<br>";
	return $cadena_sql;
}



function redireccionarInscripcion($configuracion, $opcion, $valor="")
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	unset($_REQUEST['action']);
	$cripto=new encriptar();
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
	
	switch($opcion)
	{
		case "administracion":
			$variable="pagina=admin_usuario";
			$variable.="&accion=1";
			$variable.="&hoja=0";
			break;
			
		case "confirmacion":
			$variable="pagina=confirmacionInscripcionGrado";
			$variable.="&opcion=confirmar";
			$variable.="&identificador=".$valor;
			break;
		
		case "confirmacionCoordinador":
			$variable="pagina=confirmacionInscripcionCoordinador";
			$variable.="&opcion=confirmar";
			$variable.="&sinCodigo=1";
			$variable.="&identificador=".$valor;
			break;
			
		case "corregirUsuario":			
			$variable="pagina=registroInscripcionCorregir";
			$variable.="&opcion=corregir";
			$variable.="&identificador=".$valor;
			break;
			
		case "exitoInscripcion":
			if(isset($_REQUEST['sinCodigo']))
			{
				$variable="pagina=exitoInscripcionSecretario";
			}
			else
			{
				$variable="pagina=exitoInscripcion";
			}
			
			$variable.="&identificador=".$valor;
			$variable.="&opcion=verificar";
			break;
			
		case "principal":
			$variable="pagina=index";
			break;
		
		
		
	}
	
	$variable=$cripto->codificar_url($variable,$configuracion);
	echo "<script>location.replace('".$indice.$variable."')</script>"; 
	exit();
}

function usuarioAntiguo($configuracion,$acceso_db)
{
	$valor=$_REQUEST['solicitud'];
	$cadena_sql=sqlRegistroUsuario($configuracion, "inscripcionGrado",$valor);	
	$acceso_db->registro_db($cadena_sql,0);
	$registro=$acceso_db->obtener_registro_db();
	$campos=$acceso_db->obtener_conteo_db();
	if($campos>0)
	{
	
		
		unset($valor);
		if($resultado==TRUE)
		{
			if(!isset($_REQUEST["admin"]))
			{
				enviar_correo($configuracion);
				reset($_REQUEST);
				while(list($clave,$valor)=each($_REQUEST))
				{
					unset($_REQUEST[$clave]);
						
				}
				
				redireccionarInscripcion($configuracion, "indice");
				
			}
			else
			{
				redireccionarInscripcion($configuracion,"administracion");
			}
		}
		else
		{
			
		}
						
						
	}
	else
	{
		echo "<h1>Error de Acceso</h1>Por favor contacte con el administrador del sistema.";				
	}
}

	
?>
