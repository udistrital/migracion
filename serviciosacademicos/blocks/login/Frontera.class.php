<?php
include_once("core/manager/Configurador.class.php");
include_once("core/auth/Sesion.class.php");

class FronteraLogin{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	
	var $miConfigurador;
	
	function __construct()
	{
		
	
		$this->miConfigurador=Configurador::singleton();
		
	}

	public function setRuta($unaRuta){
		$this->ruta=$unaRuta;
	}

	public function setLenguaje($lenguaje){
		$this->lenguaje=$lenguaje;
	}

	public function setFormulario($formulario){
		$this->formulario=$formulario;
	}

	function frontera()
	{
		$this->html();
	}

	function setSql($a)
	{
		$this->sql=$a;

	}

	function setFuncion($funcion)
	{
		$this->funcion=$funcion;

	}

	function html()
	{

            if(isset($_REQUEST['datos']))
                {
                    $rutaDecod = $this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['datos']);
 					
                    $datos = explode('&', $rutaDecod);
                
                    $opcion = $datos[0];
                    
                    $r = explode('=',$datos[1]);                    
                    $rol = $r[1]; 
                   
                    $pag = explode('=',$datos[2]);
                    $pagina = $pag[1];
                    
                    $usr= explode('=',$datos[3]);
                    $usuario = $usr[1];
                    
                    $op= explode('=',$datos[4]);
                    $opcionPagina = $op[1];
                    
                    $mod= explode('=',$datos[5]);
                    $modulo = $mod[1];
                    
                    $tok= explode('=',$datos[6]);
                    $token = $tok[1];
                    
                    $tokenDecodificado = $this->miConfigurador->fabricaConexiones->crypto->decodificar($token);
                  
                    if($tokenDecodificado == "condorSara2014")
                        {
                            
                                if($rol=="soporte"||$rol="laboratorios"||$rol="bienestarInstitucional"||$rol=="asistenteContabilidad"||$rol=="estudiante"){
                                    include_once("core/builder/FormularioHtml.class.php");
                                    
                                    $this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");


                                    $this->miFormulario=new formularioHtml();
                                    
                                    $conexion="soporteoas";
                                    $esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
                                    if(!$esteRecursoDB){
                                    	//Este se considera un error fatal
                                    	exit;
                                    	 
                                    }
                                    
                                    $_REQUEST['pagina'] = $pagina;
                                    $_REQUEST['opcion'] = $opcion;
                                    $_REQUEST['usuario'] = $usuario;
                                    $_REQUEST['modulo'] = $modulo;
                                    $_REQUEST['opcionPagina'] = $opcionPagina;

                                   
                                    //Si es un estudiante busca el nivel
                                    if($modulo==51||$modulo==52){
                                    	
                                    	$variable =  $usuario;
                                    	$cadena_sql=$this->sql->cadena_sql("buscarNivelUsuario",$variable);
                                    	$registro=$esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
                                    	if($registro){
                                    		$_REQUEST['modulo'] = $registro[0][1];
                                    		
                                    	}
                                    }
                                    
                                    
                                    
                                    if(isset($usuario)&&$usuario!='')
                                        {
                                        	
                                            include_once($this->ruta."/funcion/procesarLoginCondor.php");
                                        }else
                                            {
                                                echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                                                include_once($this->ruta."/formulario/formLogin.php");
                                            }
                                    
                                    
                                
                                }
                        }
                }else
                    {
                    
                    include_once("core/builder/FormularioHtml.class.php");
		
                    $this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");


                    $this->miFormulario=new formularioHtml();

                    include_once($this->ruta."/formulario/formLogin.php");
                    
                    }
            
            
		
		
	}


}
?>
