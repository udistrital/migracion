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
class bloque_registroConsejeria extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_registroConsejeria($configuracion);
		$this->sql=new sql_registroConsejeria();
                $this->configuracion=$configuracion;
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
                        case "ver":
                                $this->funcion->consultarDocente($this->configuracion);
                        break;

                        case "registrarConsejero":
                                $this->funcion->consultarDocentesPlanta($this->configuracion);
                        break;

                        case "guardarConsejeros":
                                $this->funcion->guardarDocenteConsejero($this->configuracion);
                        break;

                        case "borrarConsejero":
                                $this->funcion->borrarDocenteConsejero($this->configuracion);
                        break;

                        case "otrosDocentes":
                                $this->funcion->docentesOtrosProyectos($this->configuracion);
                        break;

                        default:
				$this->funcion->consultarDocente($this->configuracion);
			break;
		}#Cierre de funcion html
	}
	
	
	function action()
	{

            switch($_REQUEST['opcion'])
		{
                        case "registrarConsejero":

                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=registroConsejeria";
                            $variable.="&opcion=registrarConsejero";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;


                        break;

                        case "guardarConsejeros":
                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=registroConsejeria";
                            $variable.="&opcion=guardarConsejeros";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $j=0;
                            for($i=0;$i<$_REQUEST['totalConsejeros'];$i++)
                            {
                                if(isset($_REQUEST['consejero'.$i]))
                                    {
                                        $variable.="&consejero".$j."=".$_REQUEST['consejero'.$i];
                                        $variable.="&tipoVin".$j."=".$_REQUEST['tipoVin'.$i];
                                        $j++;
                                    }
                            }
                            $variable.="&totalSeleccionados=".$j;
                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_registroConsejeria($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>