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

class sql_verNoticia extends sql
{	//@ Método que crea las sentencias sql para el modulo ver_noticias
	function cadena_sql($configuracion,$tipo,$variable)
		{
			
			switch($tipo)
			{
		
			case "ver":
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
					$this->cadena_sql.="id_noticia=";
					$this->cadena_sql.="'".$variable."'";
					
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
