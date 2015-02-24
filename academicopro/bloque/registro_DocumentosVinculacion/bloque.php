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
class bloque_registroVinculacion extends bloque
{
    private $configuracion;
    private $docente;
    
    public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_registroVinculacion();
            $this->funcion=new funciones_registroVinculacion($configuracion, $this->sql);

	}


	function html()
	{   
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{          case "mostrar":
                                                $this->funcion->formBuscar();
                                            	$this->funcion->mostrarDatos($_REQUEST['docente']);
                                    		break;                                            
                                           case "historial":
                                                $this->funcion->formBuscar();
                                            	$this->funcion->mostrarDatos($_REQUEST['docente']);
                                            	$this->funcion->historialVinculacion($_REQUEST['docente']);
                                    		break;    
                                            
                                           case "nuevoVinculacion":
                                                $this->funcion->mostrarDatos($_REQUEST['docente']);
                                                //se envian los datos para el regostro de la resolucion 
                                                $vinculacion=array('opcion' => 'resolucion',
                                                                   'carpeta' => $_REQUEST['carpeta'], 
                                                                   'docente' => $_REQUEST['docente'],
                                                                   'vinAnio' => $_REQUEST['vinAnio'],
                                                                   'vinPer' => $_REQUEST['vinPer'],
                                                                   'vinCra' => $_REQUEST['vinCra'],
                                                                   'vinCod' => $_REQUEST['vinCod']);    
                                                $directorio=$_REQUEST['carpeta'];
                                                $titulo= 'Resolución para el periodo '.$_REQUEST['vinAnio'].'-'.$_REQUEST['vinPer'].' y proyecto '.$_REQUEST['vinCra'];
                                                //invoca la funcion apar cargar archivo
                                                $this->funcion->formCargarArchivo($directorio,$titulo,$vinculacion);
                                            	$this->funcion->historialVinculacion($_REQUEST['docente']);
                                    		break;     
                                            
                                          case "borrarVinculacion":
                                                $this->funcion->borrarArchivo($_REQUEST['carpeta'],$_REQUEST['archivo']);
                                                $vinculacion=array('opcion' => 'resolucion',
                                                              'identificacion' => $_REQUEST['docente'],
                                                              'vinAnio' => $_REQUEST['vinAnio'],
                                                              'vinPer' => $_REQUEST['vinPer'],
                                                              'vinCra' => $_REQUEST['vinCra'],
                                                              'vinCod' => $_REQUEST['vinCod'],
                                                              'resolucion' => '',
                                                              'internoRes' => ''); 

                                                $resultado=$this->funcion->guardarRegistro($vinculacion); 
                                                $mensaje='Resolución Eliminada Correctamente';
                                                echo "<script>alert('".$mensaje."')</script>"; 
                                                $this->funcion->formBuscar();
                                            	$this->funcion->mostrarDatos($_REQUEST['docente']);
                                            	$this->funcion->historialVinculacion($_REQUEST['docente']);
						break;

      
                                            
                                            case "actos":
                                                $this->funcion->formBuscar();
                                            	$this->funcion->mostrarDatos($_REQUEST['docente']);
                                            	$this->funcion->historialActos($_REQUEST['docente']);
						break;  
                                            
                                            case "nuevoActo":
                                                //$this->funcion->formBuscar();
                                            	$this->funcion->mostrarDatos($_REQUEST['docente']);
                                            	$acto=array('opcion' => 'actos',
                                                            'carpeta' => $_REQUEST['carpeta'], 
                                                            'docente' => $_REQUEST['docente']);    
                                                $directorio=$_REQUEST['carpeta'];
                                                $titulo= ' Acto Administrativo para el Docente con identificaciòn '.$_REQUEST['docente'];
                                                //invoca la funcion apar cargar archivo
                                                $this->funcion->formCargarArchivo($directorio,$titulo,$acto);
						break;               
                                            case "normatividad":
                                                $this->funcion->consultarArchivos('normatividad','NORMATIVIDAD');
						break;                                            
                                            case "borrarnormatividad":
                                                $this->funcion->borrarArchivo($_REQUEST['carpeta'],$_REQUEST['archivo']);
                                                $mensaje='Norma Eliminada Correctamente';
                                                echo "<script>alert('".$mensaje."')</script>"; 
                                                $this->funcion->consultarArchivos('normatividad','NORMATIVIDAD');
						break;
                                            case "convocatoria":
                                            	$this->funcion->consultarArchivos('convocatoria','CONVOCATORIAS');
						break;           
                                            case "borrarconvocatoria":
                                                $doc=(isset($_REQUEST['docente'])?$_REQUEST['docente']:'');
                                                $this->funcion->borrarArchivo($_REQUEST['carpeta'],$_REQUEST['archivo']);
                                                $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                $ruta="pagina=registroDocumentosVinculacion";
                                                $ruta.="&opcion=convocatoria";
                                                $ruta.="&docente=".$doc;
                                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                                $this->cripto=new encriptar();
                                                $ruta=$this->cripto->codificar_url($ruta,$this->configuracion); 
                                                echo "<script>location.replace('".$indice.$ruta."')</script>";
                                                exit;
                                                    
						break;                                               

                                            case "borrarActo":
                                                
                                                $resultado=$this->funcion->borrarRegistro($_REQUEST['opcion'],$_REQUEST['cod_acto']);
                                                
                                                if($resultado==1)
                                                    {$this->funcion->borrarArchivo($_REQUEST['carpeta'],$_REQUEST['archivo']);
                                                     $mensaje='Acto Administrativo Eliminado Corectamente';}
                                                else{$mensaje='No fue posible Eliminar el Acto Administrativo';}    
                                                echo "<script>alert('".$mensaje."')</script>";    
                                                $this->funcion->formBuscar();
                                            	$this->funcion->mostrarDatos($_REQUEST['docente']);
                                            	$this->funcion->historialActos($_REQUEST['docente']);
						break;                                               
                                            
                                            
                                            default :    
                                                $this->funcion->formBuscar();
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
                   case "resolucion":
                            $resultado=$this->funcion->cargarArchivo($_REQUEST['ruta'],$_REQUEST['opcion']);
                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=registroDocumentosVinculacion";
                            $variable.="&docente=".$_REQUEST['docente'];
                            if(!is_array($resultado))
                                    {  $variable.="&opcion=nuevoVinculacion";
                                       $variable.="&msgError=".$this->funcion->errorCarga["noCarga"];
                                       $variable.="&carpeta=".$_REQUEST['carpeta']; 
                                       $variable.="&vinAnio=".$_REQUEST['vinAnio'];
                                       $variable.="&vinPer=".$_REQUEST['vinPer'];
                                       $variable.="&vinCra=".$_REQUEST['vinCra'];
                                       $variable.="&vinCod=".$_REQUEST['vinCod'];
                                       $variable.="&resolucion=".$_REQUEST['resolucion'];
                                    }
                               else {
                                        $vinculacion=array('opcion' => 'resolucion',
                                                            'identificacion' => $_REQUEST['docente'],
                                                            'vinAnio' => $_REQUEST['vinAnio'],
                                                            'vinPer' => $_REQUEST['vinPer'],
                                                            'vinCra' => $_REQUEST['vinCra'],
                                                            'vinCod' => $_REQUEST['vinCod'],
                                                            'resolucion' => $_REQUEST['resolucion'],
                                                            /*'resolucion' => $resultado['nombreArchivo'],*/
                                                            'internoRes' => $resultado['nombreInterno']); 
                                        $this->funcion->guardarRegistro($vinculacion);                    
                                        $variable.="&opcion=historial";
                                     }

                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;
                            
                   case "actos":
                            $resultado=$this->funcion->cargarArchivo($_REQUEST['ruta'],$_REQUEST['opcion']);
                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=registroDocumentosVinculacion";
                            $variable.="&docente=".$_REQUEST['docente'];
                            if(!is_array($resultado))
                                    {  $variable.="&opcion=nuevoActo";
                                       $variable.="&msgError=".$this->funcion->errorCarga["noCarga"];
                                       $variable.="&carpeta=".$_REQUEST['carpeta']; 
                                       $variable.="&descripcion=".$_REQUEST['descripcion'];
                                    }
                               else {
                                       $actos=array('opcion' => 'actos',
                                                    'identificacion' => $_REQUEST['docente'],
                                                    'descripcion' => $_REQUEST['descripcion'],
                                                    'acto' => $resultado['nombreArchivo'],
                                                    'internoActo' => $resultado['nombreInterno'],
                                                    'fecha'=>  date('Y-m-d')
                                                    ); 
                                        $this->funcion->guardarRegistro($actos);                    
                                        $variable.="&opcion=actos";
                                     }

                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;
                            
                   case "normatividad":
                        $Ruta=$_REQUEST['ruta'].$_REQUEST['normatividad']."/";
                        $resultado=$this->funcion->cargarArchivo($Ruta,$_REQUEST['opcion']);
                  	$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=registroDocumentosVinculacion";
                        $variable.="&opcion=normatividad";
                        $variable.="&docente=".$_REQUEST['docente'];
                        if(!is_array($resultado))
                                { $variable.="&msgError=".$this->funcion->errorCarga["noCarga"];}
                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";

                        break;

                   case "convocatoria":
                       
                        if(isset($_REQUEST['facultad']))
                            {$Ruta=$_REQUEST['ruta'].$_REQUEST['facultad']."/";                            
                            }
                        else{$Ruta=$_REQUEST['ruta'];}    
                       
                        $resultado=$this->funcion->cargarArchivo($Ruta,$_REQUEST['opcion']);
                  	$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=registroDocumentosVinculacion";
                        $variable.="&opcion=convocatoria";
                        $variable.="&docente=".$_REQUEST['docente'];

                        if(!is_array($resultado))
                                { $variable.="&msgError=".$this->funcion->errorCarga["noCarga"];}

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";

                        break;
                        
                        
                   case "buscar":
                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=registroDocumentosVinculacion";
                            $variable.="&opcion=mostrar";
                            $variable.="&docente=".$_REQUEST['docente'];
                            
                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                    
                    
                        break;                                            
                    
                    default :    
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=registroDocumentosVinculacion";
                        $variable.="&opcion=inicio";
                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        break;
                }
	}
}



// @ Crear un objeto bloque especifico
$esteBloque=new bloque_registroVinculacion($configuracion);
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