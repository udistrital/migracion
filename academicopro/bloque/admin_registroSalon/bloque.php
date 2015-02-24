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
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/multiConexion.class.php");



//Clase
class bloque_registroSalon extends bloque
{

	 public function __construct($configuracion)
	{	
            $this->sql=new sql_registroSalon();
            $this->funcion=new funciones_registroSalon($configuracion,$this->sql);
	}
	
	
	function html($configuracion)
	{
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
                switch($_REQUEST['opcion'])
                        {
                                case "asignar":
                                        $this->funcion->registrarSalon($configuracion);
                                        break;

                                case "guardar":
                                        $this->funcion->guardarSalon($configuracion);
                                        break;

                                case "borrarSalon":
                                        $this->funcion->borrarSalon($configuracion);
                                        break;

                                default:
                                        $this->funcion->registrarSalon($configuracion);
                                        break;
                        }
	}
	
	
	function action($configuracion)
	{	
        switch($_REQUEST['opcion'])
		{
            case "guardar":
                
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminasignarSalon";
                $variable.="&opcion=guardar";
                $variable.="&sede=".$_REQUEST["sede"];
                $variable.="&salon=".$_REQUEST["salon"];
                $variable.="&dia=".$_REQUEST["dia"];
                $variable.="&hora=".$_REQUEST["hora"];
                $variable.="&espacio=".$_REQUEST["espacio"];
                $variable.="&anio=".$_REQUEST["anio"];
                $variable.="&periodo=".$_REQUEST["periodo"];
                $variable.="&capacidad=".$_REQUEST["capacidad"];
                $variable.="&grupo=".$_REQUEST["grupo"];
                
                
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
                break;


                default:

                    unset($_REQUEST['action']);

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminasignarSalon";
                    $variable.="&opcion=guardar";
                    $variable.="&proyecto=".$_REQUEST["proyecto"];

                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    break;
		}
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroSalon($configuracion);


if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>