<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2005                                      #
#    paulo_cesar@berosa.com                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/****************************************************************************
  
html.class.php 
 
Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

******************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		http://acreditacion.udistrital.edu.co
* @description  Clase para el manejo de elementos HTML (XML)
*******************************************************************************
*Atributos
*
*@access private
*@param  $conexion_id
*		Identificador del enlace a la base de datos 
*******************************************************************************
*/

/*****************************************************************************
*Métodos
*
*@access public
*
******************************************************************************
* @USAGE
* 
* 
* 
*/

class  html
{
	/** Aggregations: */

	/** Compositions: */

	/*** Attributes: ***/

	/**
	 * Miembros privados de la clase 
	 * @access private
	 */
     	var $conexion_id;
     	

	/**
     * @name html
	 * constructor
	 */
	function html()
	{
		
		
	}//Fin del método session
	
	
	function enlace_wiki($cadena,$titulo="",$configuracion,$el_enlace="")
	{
		if($el_enlace!="")
		{
			$enlace_wiki="<a class='wiki' href='".$configuracion["wikipedia"].$cadena."' title='".$titulo."'>".$el_enlace."</a>";
		}
		else
		{
			$enlace_wiki="<a class='wiki' href='".$configuracion["wikipedia"].$cadena."' title='".$titulo."'>".$cadena."</a>";
		}
		return $enlace_wiki;
	}
	
	/**
     * @name cuadro_lista
	 * @param string $cuadro_sql  Clausula SQL desde donde se extraen los valores de la lista
	 * @param string $nombre      Nombre del control que se va a crear
	 * @param string $configuracion
	 * @param int    $seleccion   id (o nombre??) del elemento seleccionado en la lista
	 * @param int    $evento      Evento javascript  que desencadena el control	
	 * @return void
	 * @access public
	 */    
	 //Si la seleccion<0 entonces se muestra una linea vacia al inicio de la lista
	function cuadro_lista($cuadro_sql,$nombre,$configuracion,$seleccion,$evento,$limitar,$tab=0,$id="",$tamano="200")
    	{
		//echo $cuadro_sql;
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");	
		$this->formato=new cadenas();
		
		if(!is_array($cuadro_sql))
		{
			$this->cuadro_acceso_db=new dbms($configuracion);
			$this->cuadro_acceso_db->registro_db($cuadro_sql,0);
			$this->cuadro_registro=$this->cuadro_acceso_db->obtener_registro_db();
			$this->cuadro_campos=$this->cuadro_acceso_db->obtener_conteo_db();
		}
		else
		{
			$this->cuadro_registro=$cuadro_sql;
			$this->cuadro_campos=count($cuadro_sql);
			
		}
		$this->mi_cuadro="";
		if($this->cuadro_campos>0)
		{
		
			//metodo para rescatar el numero de columnas de una matriz n X m
			list ($clave, $val)=each($this->cuadro_registro);
			$this->columnas=sizeof($val);
			
			//Diferentes eventos que va a menejar el control
			//
			
			switch($evento)
			{
				case 1:					
					$mi_evento='onchange="this.form.submit()"';
					break;
					
				case 2 :
					//echo "Este es evento que se esta:".$evento;
					$mi_evento="onchange=\"".$configuracion["ajax_function"]."(document.getElementById('".$configuracion["ajax_control"]."').value)\"";
					break;
				case 3 :
					//echo "Este es evento que se esta:".$evento;
					$mi_evento=" style='width: 300px;' onchange=\"".$configuracion["ajax_function"]."(document.getElementById('".$configuracion["ajax_control"]."').value)\"";
					break;
				
				default:				
					$mi_evento="";			
				
			}
			
			
			if($id=="")
			{
				$this->mi_cuadro="<select name='".$nombre."' size='1' ".$mi_evento." tabindex='".$tab."' style='width:".$tamano."px'>\n";
			}
			else
			{
				$this->mi_cuadro="<select id='".$id."' name='".$nombre."' size='1' ".$mi_evento." tabindex='".$tab."' style='width:".$tamano."px'>\n";
			}
			
			//Si no se especifica una seleccion se agrega un espacio en blanco
			if($seleccion<0)
			{
				$this->mi_cuadro.="<option value='-1'> </option>\n";
			}		
			
			//Recorrer todos los registros devueltos
			for($this->cuadro_contador=0;$this->cuadro_contador<$this->cuadro_campos;$this->cuadro_contador++)
			{
 				$this->cuadro_contenido="";	
 				//Si ningun registro debe ser seleccionado
 				if($seleccion<0)
				{
					
					
					for($this->otro_contador=1;$this->otro_contador<$this->columnas;$this->otro_contador++)
					{
						
						$this->cuadro_contenido.=$this->cuadro_registro[$this->cuadro_contador][$this->otro_contador]." ";
						if($limitar==1)
						{
							$this->mi_cuadro.="<option value='".$this->cuadro_registro[$this->cuadro_contador][0]."'>".substr($this->cuadro_contenido,0,20)."</option>\n";
						}
						else
						{
							$this->mi_cuadro.="<option value='".$this->cuadro_registro[$this->cuadro_contador][0]."'>".htmlentities($this->cuadro_contenido)."</option>\n";
						}
					}
				
				
				}
				//Si debe seleccionarse un registro
				else
				{
					for($this->otro_contador=1;$this->otro_contador<$this->columnas;$this->otro_contador++)
					{
						//echo substr($this->cuadro_contenido,0,20);
						$this->cuadro_contenido.=$this->cuadro_registro[$this->cuadro_contador][$this->otro_contador]." ";
						if($limitar==1)
						{
							if($this->cuadro_registro[$this->cuadro_contador][0]==$seleccion)
							{
								$this->mi_cuadro.="<option selected='true' value='".$this->cuadro_registro[$this->cuadro_contador][0]."'>".$this->formato->unhtmlentities(substr($this->cuadro_contenido,0,20))."</option>\n";
							}
							else
							{
								$this->mi_cuadro.="<option value='".$this->cuadro_registro[$this->cuadro_contador][0]."'>".$this->formato->unhtmlentities($this->cuadro_contenido)."</option>\n";
							}
						}
						else
						{
							if($this->cuadro_registro[$this->cuadro_contador][0]==$seleccion)
							{
								$this->mi_cuadro.="<option selected='true' value='".$this->cuadro_registro[$this->cuadro_contador][0]."'>".$this->formato->formatohtml($this->cuadro_contenido)."</option>\n";
							}
							else
							{
								$this->mi_cuadro.="<option value='".$this->cuadro_registro[$this->cuadro_contador][0]."'>".$this->formato->formatohtml($this->cuadro_contenido)."</option>\n";
							}
						}
					
					}
					
				}
			}
			$this->mi_cuadro.="</select>\n";
		}
		else
		{
			echo "Imposible rescatar los datos.";
		
		}
			
		return $this->mi_cuadro;
	}//Fin del método cuadro lista
	
	
	function cuadro_texto($nombre,$configuracion,$valor,$evento,$tab=0,$id="",$tamanno=40,$maximo=100,$estilo="")
	{
		$this->mi_cuadro="";
		if($id=="")
		{
			$this->mi_cuadro="<input class='".$estilo."' type='text' name='".$nombre."' value='".$valor."' size='".$tamanno."' maxlength='".$maximo."' tabindex='".$tab."' >";
		}		
		return $this->mi_cuadro;
	}

	
}//Fin de la clase html
	
	?>
