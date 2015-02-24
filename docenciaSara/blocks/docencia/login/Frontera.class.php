<?php
include_once("core/manager/Configurador.class.php");

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
            if($_REQUEST['temasys'])
                {
                    $rutaDecod = $this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['temasys']);
            
                    $datos = explode('&', $rutaDecod);
                    
                    $opcion = $datos[0];
                    
                    $pag = explode('=',$datos[1]);                    
                    $pagina = $pag[1]; 
                    
                    $usu = explode('=',$datos[2]);
                    $usuario = $usu[1];
                    
                    $mod = explode('=',$datos[3]);
                    $modulo = $mod[1];
                    
                    $tok= explode('=',$datos[4]);
                    $token = $tok[1];
                    
                    $opcPag= explode('=',$datos[5]);
                    $opcionPagina = $opcPag[1];
                    
                    $tokenDecodificado = $this->miConfigurador->fabricaConexiones->crypto->decodificar($token);
                    
                    if($tokenDecodificado == "condorSara2013")
                        {
                            switch($pagina)
                            {
                                case "docencia":
                                    include_once("core/builder/FormularioHtml.class.php");
		
                                    $this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");


                                    $this->miFormulario=new formularioHtml();
                                    
                                    //validamos si existe una sesion activa desde condor
                                    $conexion="condor";                
                                    $esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

                                    if(!$esteRecursoDB){

                                            //Este se considera un error fatal
                                            exit;

                                    }
                                    
                                    $cadena_sql=$this->sql->cadena_sql("validarSesion",$usuario);
                                    
                                    $registro=$esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
                                    
                                    if(is_array($registro))
                                        {
                                            include_once($this->ruta."/formulario/formIngreso.php");
                                        }else
                                            {
                                                echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                                                include_once($this->ruta."/formulario/formLogin.php");
                                            }
                                    
                                    
                                break;
                                
                                case "coordinador":
                                    include_once("core/builder/FormularioHtml.class.php");
		
                                    $this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");


                                    $this->miFormulario=new formularioHtml();
                                    
                                    $_REQUEST['pagina'] = $pagina;
                                    $_REQUEST['opcion'] = $opcion;
                                    $_REQUEST['usuario'] = $usuario;
                                    $_REQUEST['modulo'] = $modulo;
                                    $_REQUEST['opcionPagina'] = $opcionPagina;
                                    
                                    if(isset($_REQUEST['usuario']) || isset($_REQUEST['modulo']))
                                        {
                                            include_once($this->ruta."/funcion/procesarLoginCondor.php");
                                        }else
                                            {
                                                echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                                                echo "<h2>SESION TERMINADA</h2>";
                                            }
                                    
                                    
                                break;
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