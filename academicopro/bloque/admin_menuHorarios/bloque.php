<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 12 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		admin_solicitud
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.3
* @author		Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar las solicitudes de recibos de pago
*				realizadas por las diferentes coordinaciones. Implementa el
*				caso de uso: CONSULTAR SOLICITUD DE RECIBO DE PAGO
*
/*--------------------------------------------------------------------------------------------------------------------------*/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloqueAdminMenuHorario extends bloque
{   
         private $coordinador;

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminMenuHorario();
 		$this->funcion=new funciones_adminMenuHorario($configuracion, $this->sql);
	}
	function html($configuracion)
	{	switch ($_REQUEST['opcion']){ }
                //verifica que exista un proyecto
                if(isset($_REQUEST['proyecto']))
                    { $this->funcion->mostrarRegistro($configuracion,$_REQUEST['proyecto'], $totalRegistros='', $opcion='', $variable='');
                    }
	}
	function action($configuracion)
	{
		//$this->funcion->editar_carrera($configuracion);
	}
	
	
}
// @ Crear un objeto bloque especifico
$esteBloque=new bloqueAdminMenuHorario($configuracion);
if(!isset($_REQUEST['action']))
    {$esteBloque->html($configuracion);}
else
    {$esteBloque->action($configuracion);}
?>























