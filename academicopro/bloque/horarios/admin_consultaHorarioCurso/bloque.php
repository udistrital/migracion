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
class bloque_adminHorarios extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{             
             $this->sql=new sql_adminHorarios();
             $this->funcion=new funciones_adminHorarios($configuracion,$this->sql);
             $this->configuracion=$configuracion;
	}
	
	public function jxajax(){

		switch($_REQUEST['jxajax']){
			case "consultarOcupacion":
				$this->funcion->consultarOcupacion($_REQUEST['cod_salon'],$_REQUEST['anio'],$_REQUEST['periodo']);
			break;
			case "actualizarHorario":
				$this->funcion->actualizarHorario($_REQUEST['cod_salon'],$_REQUEST['cod_hora'],$_REQUEST['cod_curso'],$_REQUEST['anio'],$_REQUEST['periodo']);
			break;
			case "borrarHorario":
				$this->funcion->borrarHorario($_REQUEST['cod_salon'],$_REQUEST['cod_hora'],$_REQUEST['cod_curso'],$_REQUEST['anio'],$_REQUEST['periodo']);
			break;
			case "rescatarSalonesCompletos":
				$this->funcion->rescatarSalonesCompletos($_REQUEST['anio'],$_REQUEST['periodo'],$_REQUEST['capacidad'],$_REQUEST['cod_sede'],$_REQUEST['anio'],$_REQUEST['periodo']);
			break;
			
		}
	}
	
		
	function html()
	{
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="proyectos";
		}
        
		switch($_REQUEST['opcion'])
		{
			
                        case "generar":
                                $this->funcion->formCrear($this->configuracion);
                                break;

                        case "guardado":
                                $this->funcion->guardarCurso($this->configuracion);
                                break;

                        case "consultaHorarios":
                                //$this->funcion->consultaHorario($configuracion);
                                $this->funcion->verProyectos($this->configuracion);
                                break;

                        case "consulta":
                                $this->funcion->consultaHorario($this->configuracion);
                            break;

                        case "verHorarioGrupo"://echo "ver"; exit;
                                $this->funcion->verHorarioGrupo($this->configuracion);
                            break;

                        case "eliminaCurso":
                                $this->funcion->eliminaCurso($this->configuracion);
                        break;
			
			default:
				 $this->funcion->verHorarioGrupo($this->configuracion);
				break;	
		}
	}
	
	
	function action()
	{	
		include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
		$this->cripto=new encriptar();
							
		switch($_REQUEST['opcion'])
		{
			case "plan":
				$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=adminConsultaHorarioCurso";
				$variable.="&opcion=generar";
				$variable.="&proyecto=".$_REQUEST["proyecto"];
				$variable.="&plan=".$_REQUEST["plan"];
				if(isset($_REQUEST["espacio"])){
					$variable.="&espacio=".$_REQUEST["espacio"];
				}
				if(isset($_REQUEST["periodo"])){
					$variable.="&periodo=".$_REQUEST["periodo"];
				}				
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);
				echo "<script>location.replace('".$pagina.$variable."')</script>";

			break;

			case "guardado":
			//$this->funcion->generarHorario($configuracion,$this->tema, $this->acceso_db,$_REQUEST['espacio'], $_REQUEST['proyecto']);
			$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
			$variable="pagina=adminConsultaHorarioCurso";
			$variable.="&opcion=guardado";
			$variable.="&espacio=".$_REQUEST["espacio"];
			$variable.="&cupos=".$_REQUEST["cupos"];
                        $variable.="&max_capacidad=".$_REQUEST["max_capacidad"];
			$variable.="&grupo=".$_REQUEST["grupo"];
			$variable.="&curso=".(isset($_REQUEST["curso"])?$_REQUEST["curso"]:'');
			$variable.="&periodo=".$_REQUEST["periodo"];
			$variable.="&proyecto=".$_REQUEST["proyecto"];
			$variable.="&plan=".$_REQUEST["plan"];
			$variable.="&docEncargado=".(isset($_REQUEST["docEncargado"])?$_REQUEST["docEncargado"]:'');
			$variable.="&tipocurso=".(isset($_REQUEST["tipocurso"])?$_REQUEST["tipocurso"]:'');
			

			//echo "periodo".$_REQUEST['periodo'];exit;

			if(isset($_REQUEST['verHorario']))
			{
			$variable.="&verHorario=".$_REQUEST["verHorario"];
			}

			$variable=$this->cripto->codificar_url($variable,$this->configuracion);
			echo "<script>location.replace('".$pagina.$variable."')</script>";

			break;

			case "consulta":
				$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=adminConsultaHorarioCurso";
				$variable.="&opcion=consulta";
				$variable.="&proyecto=".$_REQUEST["proyecto"];
				$variable.="&plan=".$_REQUEST["plan"];

				include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

				case "nuevaconsulta":
				$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=adminConsultaHorarioCurso";
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);
				echo "<script>location.replace('".$pagina.$variable."')</script>";
			break;


			case "consultagrupos":
				$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=adminConsultaHorarioCurso";
				$variable.="&opcion=consultagrupos";
				$variable.="&proyecto=".$_REQUEST["proyecto"];
				$variable.="&plan=".$_REQUEST["plan"];
				$variable.="&tipoConsulta=".$_REQUEST["tipoConsulta"];
				$variable.="&order=".$_REQUEST["order"];
				$variable.="&espacio=".$_REQUEST["espacio"];
				$variable.="&periodo=".$_REQUEST["periodo"];

				include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);
				echo "<script>location.replace('".$pagina.$variable."')</script>";
			break;
		}
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminHorarios($configuracion);

if (isset ($_REQUEST['busqueda'])){
     $proyecto=$_REQUEST['proyecto'];
     $plan=$_REQUEST['plan'];
     $periodo=$_REQUEST['periodo'];
     $espacio=$_REQUEST['espacio'];
     
     $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
     $variable="pagina=adminConsultaHorarios";
     $variable.="&opcion=consultarGrupos";
     $variable.="&proyecto=".$proyecto;
     $variable.="&plan=".$plan;
     $variable.="&periodo=".$periodo;
     $variable.="&espacio=".$espacio;
     $variable.="&tipoConsulta=rapida";
     include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
     $this->cripto=new encriptar();
     $variable=$this->cripto->codificar_url($variable, $configuracion);

     echo "<script>location.replace('".$pagina.$variable."')</script>";
}

if (isset ($_REQUEST['elimina'])){
     $proyecto=$_REQUEST['proyecto'];
     $plan=$_REQUEST['plan'];
     $periodo=$_REQUEST['periodo'];
     $espacio=$_REQUEST['espacio'];
     $grupo=$_REQUEST['grupo'];
     $curso=$_REQUEST['id_curso'];

     $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
     $variable="pagina=adminConsultaHorarioCurso";
     $variable.="&opcion=eliminaCurso";
     $variable.="&proyecto=".$proyecto;
     $variable.="&plan=".$plan;
     $variable.="&periodo=".$periodo;
     $variable.="&espacio=".$espacio;
     $variable.="&grupo=".$grupo;
     $variable.="&curso=".$curso;
     $variable.="&max_capacidad=".$_REQUEST['max_capacidad'];
     $variable.="&cupos=".$_REQUEST['cupos'];
     $variable.="&funcion=".$_REQUEST['funcion'];

     include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
     $this->cripto=new encriptar();
     $variable=$this->cripto->codificar_url($variable, $configuracion);

     echo "<script>location.replace('".$pagina.$variable."')</script>";
}


if(!isset($_REQUEST['jxajax'])){

    include_once("funcion.js.php");
    include_once("css.php");

    if(!isset($_REQUEST['action']))
    {
	    $esteBloque->html();
    }
    else
    {
	    $esteBloque->action();
    }
    
}else{

    $esteBloque->jxajax();
}

?>