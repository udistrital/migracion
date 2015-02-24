<?

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloque_registroAgruparEspaciosCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroAgruparEspaciosCoordinador();
 		$this->funcion=new funciones_registroAgruparEspaciosCoordinador($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                       case "determinarClasificacion":
						$this->funcion->clasificacion($configuracion);
						break;

                                       case "verEncabezado":
						$this->funcion->ver_Encabezados($configuracion);
						break;

                                       case "ver_registrados":
						$this->funcion->verEspaciosxEstado($configuracion);
						break;

                                       case "agrupar":
						$this->funcion->agruparEspacios($configuracion);
						break;

                                       case "crear":
						$this->funcion->crearEncabezado($configuracion);
						break;

                                        case "generar":
						$this->funcion->generarEncabezado($configuracion);
						break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->clasificacion($configuracion);
			}


	}

	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{                    
                        case "verEncabezado":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAgruparEspaciosCoordinador";
				$variable.="&opcion=verEncabezado";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "consultar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
				$variable.="&opcion=verEncabezado";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "ver_registrados":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAgruparEspaciosCoordinador";
				$variable.="&opcion=ver_registrados";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&id_encabezado=".$_REQUEST["id_encabezado"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                $variable.="&nivel=".$_REQUEST["nivel"];
                                $variable.="&creditos=".$_REQUEST["creditos"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "agrupar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAgruparEspaciosCoordinador";
				$variable.="&opcion=agrupar";
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                $variable.="&nivel=".$_REQUEST["nivel"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&id_encabezado=".$_REQUEST["id_encabezado"];
                                 $variable.="&creditos=".$_REQUEST["creditos"];
                                for($i=0;$i<=100;$i++)
                                {                                   
                                  if($_REQUEST['codEspacio'.$i])
                                    {
                                     $codEspacio=$_REQUEST['codEspacio'.$i];
                                     $variable.="&codEspacio".$i."=".$codEspacio;
                                    }
                                }
                                //var_dump ($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "crear":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCrearAgrupacionEspaciosCoordinador";
				$variable.="&opcion=determinarClasificacion";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];                                
//var_dump ($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);
                                
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "consultar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
				$variable.="&opcion=consultar";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                }
	}
}

// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroAgruparEspaciosCoordinador($configuracion);
//echo $_REQUEST['action'];
if(!isset($_REQUEST['action']))
{
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