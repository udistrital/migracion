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

class sql_adminActivo extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
	function cadena_sql($configuracion,$tipo,$variable)
		{
			switch($tipo)
			{
			case "contar":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="COUNT";
					$this->cadena_sql.="(id_activo) ";
					$this->cadena_sql.="AS REG ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."activo ";
					/*if($variable)
						{ $this->cadena_sql.="WHERE ";
						  $this->cadena_sql.="`tipo_noticia`= ";
						  $this->cadena_sql.=$variable;
						}*/
					
					break;			
			case "completa":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="ACT.id_activo, ";
					$this->cadena_sql.="ACT.nombre, ";
					$this->cadena_sql.="NACT.id_nivel, ";
					$this->cadena_sql.="TACT.id_tipo ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."activo AS ACT, ";
					$this->cadena_sql.=$configuracion["prefijo"]."nivel_activo AS NACT,";
					$this->cadena_sql.=$configuracion["prefijo"]."tipo_activo AS TACT";
					$this->cadena_sql.=" WHERE";
					$this->cadena_sql.=" ACT.id_activo=NACT.id_activo ";
					$this->cadena_sql.=" AND";
					$this->cadena_sql.=" ACT.id_activo=TACT.id_activo ";
					break;
					
			case "buscar":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`id_proyecto`, ";
					$this->cadena_sql.="`nombre`, ";
					$this->cadena_sql.="`descripcion` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."proyecto ";
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="`id_proyecto`= ";
					$this->cadena_sql.=$variable;
					break;
			case "recuperar":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`id_activo` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."activo"; 
					$this->cadena_sql.=" WHERE ";
					$this->cadena_sql.="`nombre`= ";
					$this->cadena_sql.="'".$variable[2]."' ";
					$this->cadena_sql.="AND ";
					$this->cadena_sql.="`descripcion` = ";
					$this->cadena_sql.="'".$variable[3]."' ";
					$this->cadena_sql.="AND ";
					$this->cadena_sql.="`fecha_ingreso`= ";
					$this->cadena_sql.="'".$variable[4]."' ";
					break;
			case "insertar":
					$this->cadena_sql="INSERT INTO ";
					$this->cadena_sql.=$configuracion["prefijo"]."activo"; 
					$this->cadena_sql.="(`id_activo`, ";
					$this->cadena_sql.="`nombre`, ";
					$this->cadena_sql.="`descripcion`, ";
					$this->cadena_sql.="`fecha_ingreso` ";
					$this->cadena_sql.=") ";
					$this->cadena_sql.="VALUES ";
					$this->cadena_sql.="( ";
					$this->cadena_sql.="NULL, ";
					$this->cadena_sql.="'".$variable[2]."', ";
					$this->cadena_sql.="'".$variable[3]."', ";
					$this->cadena_sql.="'".$variable[4]."' ";
					$this->cadena_sql.=")";
					break;	
					
			case "insertar_nivel":
					$this->cadena_sql="INSERT INTO ";
					$this->cadena_sql.=$configuracion["prefijo"]."nivel_activo"; 
					$this->cadena_sql.="(`id_nivel`, ";
					$this->cadena_sql.="`id_activo`, ";
					$this->cadena_sql.="`id_RIA`, ";
					$this->cadena_sql.="`fecha` ";
					$this->cadena_sql.=") ";
					$this->cadena_sql.="VALUES ";
					$this->cadena_sql.="( ";
					$this->cadena_sql.="'".$variable[0]."', ";
					$this->cadena_sql.="'".$variable[5]."', ";
					$this->cadena_sql.="0, ";
					$this->cadena_sql.="'".$variable[4]."' ";
					$this->cadena_sql.=")";
					break;		
																case "insertar_tipo":
					$this->cadena_sql="INSERT INTO ";
					$this->cadena_sql.=$configuracion["prefijo"]."tipo_activo"; 
					$this->cadena_sql.="(`id_tipo`, ";
					$this->cadena_sql.="`id_activo`, ";
					$this->cadena_sql.="`fecha` ";
					$this->cadena_sql.=") ";
					$this->cadena_sql.="VALUES ";
					$this->cadena_sql.="( ";
					$this->cadena_sql.="'".$variable[1]."',";
					$this->cadena_sql.="'".$variable[5]."', ";
					$this->cadena_sql.="'".$variable[4]."' ";
					$this->cadena_sql.=")";
					break;	
					
			case "editar":
					$this->cadena_sql="UPDATE basico_noticia "; 
					$this->cadena_sql.="SET " ; 
					$this->cadena_sql.="`tipo_noticia`='".$variable[1]."', ";
					$this->cadena_sql.="`titulo_noticia`='".$variable[2]."', ";
					$this->cadena_sql.="`noticia`='".$variable[3]."'";
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="`id_noticia`= ";
					$this->cadena_sql.=$variable[0];
					
					break;
			case "borrar":
					$this->cadena_sql="DELETE "; 
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."noticia ";
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="`id_noticia`= ";
					$this->cadena_sql.=$variable[0];
					
					break;		
					
			case "select":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`valor` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."variable ";
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="`id_tipo`= ";
					$this->cadena_sql.=$variable;
					break;
			case "usuario":
					$this->cadena_sql="SELECT "; 
					$this->cadena_sql.="`usuario` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."registrado "; 
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="`id_usuario`= ";
					$this->cadena_sql.=$variable[0][0];
					
					break;			
			
			}
			
		return $this->cadena_sql;
		
		}
}
?>
