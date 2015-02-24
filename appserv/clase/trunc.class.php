<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/***************************************************************************
* @name          trunc.class.php 
* @author        Equipo Oas
* @revision      Última revisión 02 de Julio de 2009
****************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author       Equipo Oas
* @link		
* @description  
*
******************************************************************************/

class trunc
{
	public function __construct()
	{
		
	}
	
	public function transformar($objeto)
	{
		$trans_objeto=md5($objeto);
		$trans_objeto=substr($trans_objeto, 0, -2);
		
		return $trans_objeto;
		
	}
	
}//Fin de la clase db_admin

?>
