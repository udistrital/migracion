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

class sql_noticia extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
	function cadena_sql($configuracion,$tipo,$variable="")
		{
			
			switch($tipo)
			{
								
				case "insertar":
					$this->cadena_sql="INSERT INTO ";
					$this->cadena_sql.=$configuracion["prefijo"]."noticia"; 
					$this->cadena_sql.="( ";
					$this->cadena_sql.="`id_noticia`, ";
					$this->cadena_sql.="`tipo_noticia`, ";
					$this->cadena_sql.="`titulo_noticia`, ";
					$this->cadena_sql.="`noticia`, ";
					$this->cadena_sql.="`fecha_publicacion`, ";
					$this->cadena_sql.="`id_usuario` ";
					$this->cadena_sql.=") ";
					$this->cadena_sql.="VALUES ";
					$this->cadena_sql.="( ";
					$this->cadena_sql.="NULL, ";
					$this->cadena_sql.="'".$variable[0]."', ";
					$this->cadena_sql.="'".$variable[1]."', ";
					$this->cadena_sql.="'".$variable[2]."', ";
					$this->cadena_sql.="'".time()."', ";
					$this->cadena_sql.="'".$variable[3]."' ";
					$this->cadena_sql.=")";
					break;
				
			}
			
			
		
			return $this->cadena_sql;
		
		}
}
?>
