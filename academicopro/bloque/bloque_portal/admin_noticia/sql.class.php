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

class sql_adminNoticia extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
	function cadena_sql($configuracion,$tipo,$variable)
		{
			switch($tipo)
			{
			case "contar":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="COUNT";
					$this->cadena_sql.="(id_noticia) ";
					$this->cadena_sql.="AS REG ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."noticia ";
					if($variable)
						{ $this->cadena_sql.="WHERE ";
						  $this->cadena_sql.="`tipo_noticia`= ";
						  $this->cadena_sql.=$variable;
						}
					
					break;			
			case "completa":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`id_noticia`, ";
					$this->cadena_sql.="`tipo_noticia`, ";
					$this->cadena_sql.="`titulo_noticia`, ";
					$this->cadena_sql.="`noticia`, ";
					$this->cadena_sql.="`fecha_publicacion`, ";
					$this->cadena_sql.="`id_usuario` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."noticia ";
					$this->cadena_sql.=" ORDER BY ";
					$this->cadena_sql.="`fecha_publicacion` ";
					$this->cadena_sql.=" DESC ";
					break;
			case "general":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`id_noticia`, ";
					$this->cadena_sql.="`tipo_noticia`, ";
					$this->cadena_sql.="`titulo_noticia`, ";
					$this->cadena_sql.="`noticia`, ";
					$this->cadena_sql.="`fecha_publicacion`, ";
					$this->cadena_sql.="`id_usuario` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."noticia ";
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="`tipo_noticia`= ";
					$this->cadena_sql.=$variable;
					$this->cadena_sql.=" ORDER BY ";
					$this->cadena_sql.="`fecha_publicacion` ";
					$this->cadena_sql.=" DESC ";
					
					break;		
					
			case "buscar":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="`id_noticia`, ";
					$this->cadena_sql.="`tipo_noticia`, ";
					$this->cadena_sql.="`titulo_noticia`, ";
					$this->cadena_sql.="`noticia`, ";
					$this->cadena_sql.="`fecha_publicacion`, ";
					$this->cadena_sql.="`id_usuario` ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.=$configuracion["prefijo"]."noticia ";
					$this->cadena_sql.="WHERE ";
					$this->cadena_sql.="`id_noticia`= ";
					$this->cadena_sql.=$variable;
					break;		
					
			case "editar":
					$this->cadena_sql="UPDATE ";
					$this->cadena_sql.=$configuracion["prefijo"]."noticia "; 
					$this->cadena_sql.=" SET " ; 
					$this->cadena_sql.="`tipo_noticia`='".$variable[1]."', ";
					$this->cadena_sql.="`titulo_noticia`='".$variable[2]."', ";
					$this->cadena_sql.="`noticia`='".$variable[3]."'";
					$this->cadena_sql.=" WHERE ";
					$this->cadena_sql.="`id_noticia`=";
					$this->cadena_sql.="'".$variable[0]."'";
					
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
					$this->cadena_sql.=$variable[0][0];
					$this->cadena_sql.=" AND ";
					$this->cadena_sql.="tipo='NOTICIA'";					
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
