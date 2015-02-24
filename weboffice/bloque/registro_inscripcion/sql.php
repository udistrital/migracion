<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroInscripcionGrado extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "inscripcionBorrador":
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
				$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo='".$_REQUEST["identificador"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo=".$configuracion["prefijo"]."borradorUsuarioDocumento.codigo ";
				$cadena_sql.="AND ";
				$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo=".$configuracion["prefijo"]."borradorUsuarioDatos.codigo ";
				$cadena_sql.="AND ";
				$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo=".$configuracion["prefijo"]."borradorInscripcionGrado.codigo ";
				$cadena_sql.="LIMIT 1";	
				//echo $cadena_sql;exit;
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
				$cadena_sql.=$configuracion["prefijo"]."usuario.codigo='".$_REQUEST["identificador"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."usuarioDocumento.codigo ";
				$cadena_sql.="AND ";
				$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."usuarioDatos.codigo ";
				$cadena_sql.="AND ";
				$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."inscripcionGrado.codigo ";
				$cadena_sql.="ORDER BY ";
				$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.fecha DESC ";	
				$cadena_sql.="LIMIT 1";	
				//echo $cadena_sql;
				break;
				
			case "tipoDocumento":
				$cadena_sql="SELECT ";
				$cadena_sql.="tipo ";
				$cadena_sql.="FROM ".$configuracion["prefijo"]."tipo_documento ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_tipo=".$variable;			
				break;
			
			case "pais":
				$cadena_sql="SELECT ";
				$cadena_sql.="nombre ";
				$cadena_sql.="FROM ".$configuracion["prefijo"]."pais ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="isonum=".$variable;			
				break;
			
			case "paises":
				$cadena_sql="SELECT ";
				$cadena_sql.="isonum, ";
				$cadena_sql.="nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."pais ";
				$cadena_sql.="ORDER BY nombre";
				break;
				
			case "region":
				$cadena_sql="SELECT ";
				$cadena_sql.="nombre ";
				$cadena_sql.="FROM ".$configuracion["prefijo"]."localidad ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_pais=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_localidad=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="tipo=1 ";
										
				break;
			
			case "regiones":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_localidad, ";
				$cadena_sql.="nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."localidad ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_pais=".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.="tipo=1 ";
				$cadena_sql.="ORDER BY nombre";
				break;
						
			case "ciudad":
				$cadena_sql="SELECT ";
				$cadena_sql.="nombre ";
				$cadena_sql.="FROM ".$configuracion["prefijo"]."localidad ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_localidad=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_padre=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="tipo=2 ";									
				break;
			
			case "ciudades":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_localidad, ";
				$cadena_sql.="nombre ";
				$cadena_sql.="FROM ".$configuracion["prefijo"]."localidad ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_pais=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_padre=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="tipo=2 ";	
				$cadena_sql.="ORDER BY nombre";								
				break;
				
			case "formacion":
				$cadena_sql="SELECT ";
				$cadena_sql.="tipo ";
				$cadena_sql.="FROM ".$configuracion["prefijo"]."formacion ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_formacion=".$variable." ";
				$cadena_sql.="LIMIT 1 ";			
				break;
				
			case "formaciones":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_formacion, ";
				$cadena_sql.="tipo ";
				$cadena_sql.="FROM ".$configuracion["prefijo"]."formacion ";
				$cadena_sql.="ORDER BY id_formacion ";			
				break;	
				
			case "codigoUsuario":
				$cadena_sql="SELECT ";
				$cadena_sql.="codigo ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="idInscripcion=".$variable." ";
				$cadena_sql.="LIMIT 1";
				break;	
				
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
		
			default:
				$cadena_sql="";
				break;
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}
	
	
}
?>
