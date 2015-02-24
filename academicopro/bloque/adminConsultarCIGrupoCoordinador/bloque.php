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
class bloque_adminConsultarCIGrupoCoordinador extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_adminConsultarCIGrupoCoordinador($configuracion);
		$this->sql=new sql_adminConsultarCIGrupoCoordinador();
	}


	function html($configuracion)
	{
		//$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion


		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}

		switch($_REQUEST['opcion'])
		{

			case "verGrupo":
				$this->funcion->mostrarDatosGrupo($configuracion);
				break;

			default:
				$this->funcion->mostrarDatosGrupo($configuracion);
				break;
		}
	}


	function action($configuracion)
	{
                switch($_REQUEST['opcion'])
		{
			case "grupoSeleccionado":
                            //var_dump($_REQUEST);exit;
                            switch (trim($_REQUEST['accionCoordinador']))
                            {
                                case "cambiar":

                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=registroCambiarGrupoCIGrupoCoordinador";
                                    $variable.="&opcion=variosEstudiantes";
                                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                    $variable.="&nroGrupo=".$_REQUEST["nroGrupo"];
                                    $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                    $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                    $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
                                    
//var_dump($_REQUEST);exit;
                                    $total=$_REQUEST["total"]+1;//echo $total;exit;
                                        for($i=0;$i<$total;$i++)
                                        {
                                            $variable.="&codEstudiante-".$i."=".$_REQUEST["codEstudiante-".$i];

                                        }
                                    $variable.="&total=".$total;
                                    
                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$configuracion);
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    break;
                                    
                                case "cancelar":
                                    
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=registroCancelarCIGrupoEstudianteCoordinador";
                                    $variable.="&opcion=variosEstudiantes";
                                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                                    $variable.="&nroGrupo=".$_REQUEST['nroGrupo'];
                                    $variable.="&codEspacio=".$_REQUEST['codEspacio'];
                                    $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                    $variable.="&proyecto=".$_REQUEST["proyecto"];
                                    $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];                                    

                                    //$total=$_REQUEST['totalEstudiantes']+1;
                                    $total=$_REQUEST["total"]+1;//echo $total;exit;
                                     for($i=0;$i<$total;$i++)
                                        {
                                            $variable.="&codEstudiante-".$i."=".$_REQUEST['codEstudiante-'.$i];

                                        }
                                    $variable.="&total=".$total;

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$configuracion);
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    break;
                            }
                            
				break;

		}

	}


}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminConsultarCIGrupoCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>