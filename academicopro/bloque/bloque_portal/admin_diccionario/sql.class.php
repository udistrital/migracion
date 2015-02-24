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

class sql_adminDiccionario extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
	function cadena_sql($configuracion,$tipo,$variable)
		{
			switch($tipo)
			{
			case "contar":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="COUNT";
					$this->cadena_sql.="(id_objeto) ";
					$this->cadena_sql.="AS REG ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."objeto ";
					if($variable)
						{ $this->cadena_sql.="WHERE ";
						  $this->cadena_sql.="`tipo`= ";
						  $this->cadena_sql.=$variable;
						}
					
					break;			
			case "completa":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`id_objeto`, ";
					$this->cadena_sql.="`nombre`, ";
					$this->cadena_sql.="`tipo`, ";
					$this->cadena_sql.="`etiqueta`, ";
					$this->cadena_sql.="`fecha`, ";
					$this->cadena_sql.="`nivel` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."objeto ";
					break;
					
			case "buscar_tipo":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`id_objeto`, ";
					$this->cadena_sql.="`nombre`, ";
					$this->cadena_sql.="`tipo`, ";
					$this->cadena_sql.="`etiqueta`, ";
					$this->cadena_sql.="`fecha`, ";
					$this->cadena_sql.="`nivel` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."objeto ";
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="`id_tipo`= ";
					$this->cadena_sql.=$variable;
					break;
			case "buscar_id":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`id_objeto`, ";
					$this->cadena_sql.="`nombre`, ";
					$this->cadena_sql.="`tipo`, ";
					$this->cadena_sql.="`etiqueta`, ";
					$this->cadena_sql.="`fecha`, ";
					$this->cadena_sql.="`nivel` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."objeto ";
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="`id_objeto`= ";
					$this->cadena_sql.=$variable;
					break;		
			case "recuperar":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`id_objeto` ";
					$this->cadena_sql.="`nivel` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."objeto "; 
					$this->cadena_sql.=" WHERE ";
					$this->cadena_sql.="`nombre`= ";
					$this->cadena_sql.="'".$variable[0]."' ";
					$this->cadena_sql.="AND ";
					$this->cadena_sql.="`tipo` = ";
					$this->cadena_sql.="'".$variable[1]."' ";
					$this->cadena_sql.="AND ";
					$this->cadena_sql.="`etiqueta` = ";
					$this->cadena_sql.="'".$variable[2]."' ";
					$this->cadena_sql.="AND ";
					$this->cadena_sql.="`fecha`= ";
					$this->cadena_sql.="'".$variable[3]."' ";
					break;
			
			case "recuperar_nivel":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`nivel`, ";
					$this->cadena_sql.="`nombre` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."objeto "; 
					$this->cadena_sql.=" WHERE ";
					$this->cadena_sql.="`id_objeto`= ";
					$this->cadena_sql.="'".$variable."' ";
					break;
			case "insertar":
					$this->cadena_sql="INSERT INTO ";
					$this->cadena_sql.=$configuracion["prefijo"]."objeto "; 
					$this->cadena_sql.="(`id_objeto`, ";
					$this->cadena_sql.="`nombre`, ";
					$this->cadena_sql.="`tipo`, ";
					$this->cadena_sql.="`etiqueta`, ";
					$this->cadena_sql.="`fecha`, ";
					$this->cadena_sql.="`nivel` ";
					$this->cadena_sql.=") ";
					$this->cadena_sql.="VALUES ";
					$this->cadena_sql.="( ";
					$this->cadena_sql.="NULL, ";
					$this->cadena_sql.="'".$variable[0]."', ";
					$this->cadena_sql.="'".$variable[1]."', ";
					$this->cadena_sql.="'".$variable[2]."', ";
					$this->cadena_sql.="'".$variable[3]."', ";
					$this->cadena_sql.="'".$variable[4]."' ";
					$this->cadena_sql.=")";
					break;	
					
			case "relaciona":
					$this->cadena_sql="INSERT INTO ";
					$this->cadena_sql.=$configuracion["prefijo"]."relacion_objeto"; 
					$this->cadena_sql.="(`id_objeto1`, ";
					$this->cadena_sql.="`id_objeto2`, ";
					$this->cadena_sql.="`fecha`, ";
					$this->cadena_sql.="`tipo` ";	
					$this->cadena_sql.=") ";
					$this->cadena_sql.="VALUES ";
					$this->cadena_sql.="('".$variable[0]."', ";
					$this->cadena_sql.="'".$variable[1]."', ";
					$this->cadena_sql.="'".$variable[2]."', ";
					$this->cadena_sql.="'".$variable[3]."' ";
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
			case "relacionado":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="OBJ.id_objeto, ";
					$this->cadena_sql.="OBJ.nombre, ";
					$this->cadena_sql.="OBJ.tipo, ";
					$this->cadena_sql.="OBJ.etiqueta, ";
					$this->cadena_sql.="OBJ.fecha, ";
					$this->cadena_sql.="OBJ.nivel ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."objeto AS OBJ ";
					$this->cadena_sql.="INNER JOIN ";
					$this->cadena_sql.=$configuracion["prefijo"]."relacion_objeto AS REL ";
					$this->cadena_sql.=" ON OBJ.id_objeto=REL.id_objeto2 ";
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="REL.id_objeto1= ";
					$this->cadena_sql.=$variable;
					break;
			case "contar_relacionado":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="COUNT";
					$this->cadena_sql.="(OBJ.id_objeto) ";
					$this->cadena_sql.="AS REG ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."objeto AS OBJ ";
					$this->cadena_sql.="INNER JOIN ";
					$this->cadena_sql.=$configuracion["prefijo"]."relacion_objeto AS REL ";
					$this->cadena_sql.=" ON OBJ.id_objeto=REL.id_objeto2 ";
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="REL.id_objeto1= ";
					$this->cadena_sql.=$variable;
					
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
