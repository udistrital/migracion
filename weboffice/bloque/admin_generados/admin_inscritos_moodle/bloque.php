<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 05 de febrero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		registro_inscripcion
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
* @author			Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	
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
class bloque_adminInscritos_moodle extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminInscritos_moodle();
 		$this->funcion=new funciones_adminInscritos_moodle($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				switch($accion)
				{
					case "consultar":
						$this->funcion->consultaProyectos($configuracion);
						break;
					case "consultaAsignatura":
						$this->funcion->consultaAsignatura($configuracion);
						break;
					case "mostrarAsignaturas":
                                                $this->funcion->mostrarAsignaturas($configuracion);
                                                break;
					case "mostrarInscritos":
                                                $this->funcion->mostrarInscritos($configuracion);
                                                break;
					case "mostrarInscritosAsig":
                                                $this->funcion->mostrarInscritosAsig($configuracion);
                                                break;
					
				}
			}
			else
			{
				$accion="mostrar";
				$this->funcion->consultaProyectos($configuracion);
			}
		}
		else
		{
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado");	
		}
	}
	
	function action($configuracion)
	{
          
                $this->funcion->revisarFormulario();
		
		switch($_REQUEST['opcion'])
		{
                       case "exportar":
                           $craCod=$_REQUEST['craCod'];
                           $asiCod=$_REQUEST['asiCod'];
                           $grupo=$_REQUEST['grupo'];
                           
                           $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                           $variable="pagina=adminExportaExcel";
                           $variable.="&opcion=exportar";
                           $variable.="&no_pagina=true";
                           $variable.="&craCod=".$craCod;
                           $variable.="&asiCod=".$asiCod;
                           $variable.="&grupo=".$grupo;

                           include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                           $this->cripto=new encriptar();
                           $variable=$this->cripto->codificar_url($variable, $configuracion);

                           echo "<script>location.replace('".$pagina.$variable."')</script>";
                           
                           break;

                       
                       
		}

                $tipo="busqueda";
			
		//Rescatar datos de sesion
		$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
		$_REQUEST["registro"]=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "identificacion");
		
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminInscritos_moodle($configuracion);


if(!isset($_REQUEST['action']))
{echo $_REQUEST['action'];
	$esteBloque->html($configuracion);
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		$esteBloque->action($configuracion);
	}
}


?>
