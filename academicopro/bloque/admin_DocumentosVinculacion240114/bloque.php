<?

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
//echo "<br>action ".$_REQUEST['action'];
//echo "<br>opcion ".$_REQUEST['opcion'];
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("sql.class.php");
include_once("funcion.class.php");
//Clase
class bloque_adminVinculacion extends bloque
{
    private $configuracion;
    private $docente;
    
    public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_adminVinculacion();
            $this->funcion=new funciones_adminVinculacion($configuracion, $this->sql);

	}


	function html()
	{       
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                           case "pagos":
                                                $this->funcion->mostrarDatos();
                                                $this->funcion->formBuscarpagos();
						break;
                                            
                                           case "buscarPagos":
                                                $this->funcion->mostrarDatos();
                                                $this->funcion->formBuscarpagos();
                                            	$this->funcion->mostrar_Pagos();
						break; 
                                           case "buscarPagosOLD":
                                                $this->funcion->mostrarDatos();
                                                $this->funcion->formBuscarpagos();
                                            	$this->funcion->historialPagos();
						break;  
                                           case "historial":
                                               	$this->funcion->mostrarDatos();
                                            	$this->funcion->historialVinculacion();
						break;                                            
                                            case "actos":
                                                $this->funcion->mostrarDatos();
                                            	$this->funcion->historialActos();
						break;                                            
                                            case "normatividad":
                                            	$this->funcion->consultarArchivos('normatividad','NORMATIVIDAD');
						break;                                            
                                            case "convocatoria":
                                            	$this->funcion->consultarArchivos('convocatoria','CONVOCATORIAS');
						break;  
                                            case "generarDespendible"://echo "<br>paso informe";exit;
                                                $this->funcion->generarDespendible();
                                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                
                                                break;
                                            default :    
                                                $this->funcion->mostrarInicio();
                                                break;
				
				}
			}
			else
			{
				$accion="inicio";
				$this->funcion->mostrarInicio();
			}


	}

	function action()
	{
            switch($_REQUEST['opcion'])
		{
                 case "buscarPagos":
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=adminDocumentosVinculacion";
                        $variable.="&opcion=buscarPagos";
                        $variable.="&vigenciaPago=".$_REQUEST['vigenciaPago'];
                        $variable.="&mesPago=".$_REQUEST['mesPago'];
                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                    break;
                
                 case "generarDespendible":
                        $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                        $variable="no_pagina=adminDocumentosVinculacion";
                        $variable.="&opcion=generarDespendible";
                        $variable.="&vigenciaPago=".$_REQUEST['vigenciaPago'];
                        $variable.="&mesPago=".$_REQUEST['mesPago'];
                        include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                  break;
                
                }
                
                
                
                
	}
}



// @ Crear un objeto bloque especifico
$esteBloque=new bloque_adminVinculacion($configuracion);
//echo var_dump($_REQUEST);exit;
//"blouqe ".$_REQUEST['action'];exit;
if(!isset($_REQUEST['action']))
{
	$esteBloque->html();
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		$esteBloque->action();
	}
}


?>