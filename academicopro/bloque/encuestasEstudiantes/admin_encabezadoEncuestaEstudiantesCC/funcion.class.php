<?php
/**
* Funcion nombreFuncion
*
* Esta clase se encarga de crear la logica
*
* @package nombrePaquete
* @subpackage nombreSubpaquete
* @author Karen Palacios
* @version 0.0.0.1
* Fecha: 26/02/2013
*/

if(!isset($GLOBALS["autorizado"]))
{
include("../index.php");
exit; 
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

/**
* Descripcion de la clase
*
* @package paquete de la Clase
* @subpackage Subpaquete de la Clase
*/
class funcion_admin_navegacion extends funcionGeneral
{
    
    private $configuracion;

    /**
*
* @param array $configuracion contiene todas la variables del sistema almacenadas en la base de datos del framework
*/
    function __construct($configuracion) {

        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        $this->sql=new sql_admin_navegacion($configuracion);
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

        /**
* Intancia para crear la conexion ORACLE
*/
      //  $this->accesoOracle=$this->conectarDB($configuracion,"docente");
        /**
* Instancia para crear la conexion General
*/
      //  $this->acceso_db=$this->conectarDB($configuracion,"");
        /**
* Instancia para crear la conexion de MySQL
*/
       // $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

    }
                       
    /**
*
*/
    function htmlBarraNavegacion() {

	//$datosUsuario=$this->consultarDatosUsuario();
	//$rolesUsuario=$this->consultarRolesUsuario();
	
	$html="";
	$html.="<div class='barraNavegacionSuperior'>";
	$html.="	<div id='nombreUsuario'>Usuario: {$this->usuario} </div>";
	$html.="</div>";
        
	return $html;
    }

    /**
*
*/
/////

    function consultarDatosProyectoEstudiante($parametro) {

            
	$datos=array('codEstudiante'=> $parametro['CODIGO'],
		'codProyecto'=> $parametro['CODIGO'],
		'planEstudio'=> $parametro['CODIGO']);
		$cadena_sql=$this->sql->cadena_sql("datos_estudiante",$datos);
		$resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                return $resultado;


    }

}
?>
