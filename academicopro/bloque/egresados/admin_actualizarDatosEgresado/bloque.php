<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
 
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");
//Clase
class bloque_admin_actualizarDatosEgresado extends bloque
{
        public $configuracion;

	 public function __construct($configuracion)
	{	
//                include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->configuracion=$configuracion;
                //$this->tema=$tema;
		$this->funcion=new funcion_admin_actualizarDatosEgresado($configuracion);
		$this->sql=new sql_admin_actualizarDatosEgresado($configuracion);
	}
	
	
	function html()
	{
		$this->acceso_db=$this->funcion->conectarDB($this->configuracion);
		// @ Crear un objeto de la clase funcion
		
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
                        case "verEgresado":                            
                                $this->funcion->consultarEgresado();
                        break;
                    
                        default:
                            echo 'Opci&oacute;n html no v&aacutelida!';
                            break;                    

		}
	}
	
	
	function action()
	{
            switch($_REQUEST['opcion'])
		{
                
                        case "enviarCodigo":
                                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                                $variable = "pagina=admin_actualizarDatosEgresado";
                                $variable.="&opcion=verEgresado";
                                $variable.="&tipoBusqueda=".$_REQUEST['tipoBusqueda'];
                                $variable.="&datoBusqueda=".$_REQUEST['datoBusqueda'];
                                
                                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();                                
                                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                echo "<script>location.replace('".$pagina.$variable."')</script>";                                
                            
                            break;
                        case 'actualizar':
                                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                            if(isset($_REQUEST['actualizarDatosContacto']))
                            {
                                $variable = "pagina=registro_actualizarDatoEgresado";
                                $_REQUEST['tipoActualizacion']='actualizarDatosContacto';
                                $_REQUEST['action']='egresados/registro_actualizarDatoEgresado';
                                unset ($_REQUEST['actualizarDatosContacto']);
                            }
                            
                            foreach ($_REQUEST as $key => $value) {
                                $variable.="&".$key."=".$value;
                            }                            
                                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();                                
                                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                                echo "<script>location.replace('".$pagina.$variable."')</script>";                                
                            
                            break;
                        default:
                            echo 'Opci&oacute;n action no v&aacutelida!';
                            break;
                      
                        
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_admin_actualizarDatosEgresado($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>