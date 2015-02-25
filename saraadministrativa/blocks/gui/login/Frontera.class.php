<?php

include_once("core/manager/Configurador.class.php");

class FronteraLogin {

    var $ruta;
    var $sql;
    var $funcion;
    var $lenguaje;
    var $formulario;
    var $miConfigurador;

    function __construct() {

        $this->miConfigurador = Configurador::singleton();
    }

    public function setRuta($unaRuta) {
        $this->ruta = $unaRuta;
    }

    public function setLenguaje($lenguaje) {
        $this->lenguaje = $lenguaje;
    }

    public function setFormulario($formulario) {
        $this->formulario = $formulario;
    }

    function frontera() {
        $this->html();
    }

    function setSql($a) {
        $this->sql = $a;
    }

    function setFuncion($funcion) {
        $this->funcion = $funcion;
    }
    
    function html() {
        
        //var_dump($_REQUEST); 
        if (isset($_REQUEST['temasys'])) {
            $rutaDecod = $this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['temasys']);
            
            $datos = explode('&', $rutaDecod);

            $opcion = $datos[0];

            $pag = explode('=', $datos[1]);
            $pagina = $pag[1];

            $usu = explode('=', $datos[2]);
            $usuario = $usu[1];

            $tip = explode('=', $datos[3]);
            $tipo = $tip[1];

            $tok = explode('=', $datos[4]);
            $token = $tok[1];

            $opcPag = explode('=', $datos[5]);
            $opcionPagina = $opcPag[1];
            
            //$email = explode('=', $datos[6]);
            //$mail = $email[1];

            $tokenDecodificado = $this->miConfigurador->fabricaConexiones->crypto->decodificar($token);
                      
            if ($tokenDecodificado == "condorSara2013!") {
                switch ($pagina) {
                    case "decano":
                        include_once("core/builder/FormularioHtml.class.php");

                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

                        $this->miFormulario = new formularioHtml();

                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;

                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            include_once($this->ruta . "/funcion/procesarGestionPassword.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;
                    case "coordinador":
                        include_once("core/builder/FormularioHtml.class.php");

                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");


                        $this->miFormulario = new formularioHtml();

                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;

                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            include_once($this->ruta . "/funcion/procesarGestionPassword.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;
                    case "docencia":
                        include_once("core/builder/FormularioHtml.class.php");

                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

                        $this->miFormulario = new formularioHtml();

                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;

                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            include_once($this->ruta . "/funcion/procesarLoginCondor.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;
                    case "docentes":
                        include_once("core/builder/FormularioHtml.class.php");

                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");


                        $this->miFormulario = new formularioHtml();

                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;

                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            include_once($this->ruta . "/funcion/procesarGestionPassword.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;
                    case "estudiantes":
                       
                        include_once("core/builder/FormularioHtml.class.php");

                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

                        $this->miFormulario = new formularioHtml();

                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;

                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            include_once($this->ruta . "/funcion/procesarGestionPassword.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;
                    case "funcionario":
                        include_once("core/builder/FormularioHtml.class.php");

                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

                        $this->miFormulario = new formularioHtml();

                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;

                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            include_once($this->ruta . "/funcion/procesarGestionPassword.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;                        
                    case "administrador":
                        include_once("core/builder/FormularioHtml.class.php");

                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

                        $this->miFormulario = new formularioHtml();

                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;

                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            include_once($this->ruta . "/funcion/procesarLoginCondor.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;
                    case "soporte":
                        include_once("core/builder/FormularioHtml.class.php");

                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

                        $this->miFormulario = new formularioHtml();

                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;

                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            include_once($this->ruta . "/funcion/procesarGestionPassword.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;
                    case "otrosCambioPassword":
                        include_once("core/builder/FormularioHtml.class.php");

                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

                        $this->miFormulario = new formularioHtml();

                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;

                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            include_once($this->ruta . "/funcion/procesarGestionPassword.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;
                    case "validarActualizacion":
                        include_once("core/builder/FormularioHtml.class.php");

                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

                        $this->miFormulario = new formularioHtml();
                        
                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;

                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            include_once($this->ruta . "/funcion/procesarGestionPassword.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;      
                        
                }
            }
            elseif ($tokenDecodificado == "l4v3rn42013!r3cup3raci0ncl4v3s2013") {
                switch ($pagina) {
                    case "claves":
                        include_once("core/builder/FormularioHtml.class.php");
                       
                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
                        
                        $this->miFormulario = new formularioHtml();
                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;
                        
                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            //$this->funcion->redireccionar("gestionPassword");
                            include_once($this->ruta . "/funcion/procesarLoginCondor.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;
                      case "recuperaClaves":
                        include_once("core/builder/FormularioHtml.class.php");
                        $email = explode('=', $datos[6]);
                        $mail = $email[1];
                        
                        $docAct=explode('=', $datos[7]);
                        $documentoActual=$docAct[1];
                        
                        $nomUsu=explode('=', $datos[8]);
                        $nombreUsuario=$nomUsu[1];
                        
                        $nom=explode('=', $datos[9]);
                        $nombre=$nom[1];
                        
                        $fec=explode('=', $datos[10]);
                        $fechaHoy=$fec[1];
                        
                        //var_dump($_REQUEST); 
                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

                        $this->miFormulario=new formularioHtml();
                        $_REQUEST['pagina']=$pagina;
                        $_REQUEST['opcion']=$opcion;
                        $_REQUEST['usuario']=$usuario;
                        $_REQUEST['tipo']=$tipo;
                        $_REQUEST['opcionPagina']=$opcionPagina;
                        $_REQUEST['mail']=$mail;
                        $_REQUEST['documentoActual']=$documentoActual;
                        $_REQUEST['nombreUsuario']=$nombreUsuario;
                        $_REQUEST['nombre']=$nombre;
                        $_REQUEST['fechaHoy']=$fechaHoy;
                        
                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            //$this->funcion->redireccionar("gestionPassword");
                            include_once($this->ruta . "/funcion/procesarLoginCondor.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;
                }
            }
            elseif ($tokenDecodificado == "s4r44dm1n1str4t1v4C0nd0r2014!") {
                switch ($pagina) {
                    case "certificaciones":
                        include_once("core/builder/FormularioHtml.class.php");
                       
                        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
                        
                        $this->miFormulario = new formularioHtml();
                        $_REQUEST['pagina'] = $pagina;
                        $_REQUEST['opcion'] = $opcion;
                        $_REQUEST['usuario'] = $usuario;
                        $_REQUEST['tipo'] = $tipo;
                        $_REQUEST['opcionPagina'] = $opcionPagina;
                         
                        if (isset($_REQUEST['usuario']) || isset($_REQUEST['tipo'])) {
                            //$this->funcion->redireccionar("gestionPassword");
                            include_once($this->ruta . "/funcion/procesarLoginCondor.php");
                        } else {
                            echo "<script>alert('No existe una sesion creada, por favor ingrese con usuario y clave')</script>";
                            echo "<h2>SESION TERMINADA</h2>";
                        }
                        break;
                }
            }
            else {
                    include_once("core/builder/FormularioHtml.class.php");

                    $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");


                    $this->miFormulario = new formularioHtml();

                    include_once($this->ruta . "/formulario/formLogin.php");
                }
        } else {

            include_once("core/builder/FormularioHtml.class.php");

            $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");


            $this->miFormulario = new formularioHtml();

            include_once($this->ruta . "/formulario/formLogin.php");
        }
    }

}

?>