<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");

//include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");

//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/phpmailer.class.php");
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_registro_codificarEstudiantesNuevos extends funcionGeneral {

  public $configuracion;
  public $usuario;
  public $nivel;
  public $datosEstudiante;
  public $periodo;
  public $formulario;
  public $bloque;
  public $proyecto;
  public $nivelProyecto;
  public $codigoEstudiante;
  //public $datosDocente;
  //private $notasDefinitivas;

  //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion)
    {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($this->configuracion["raiz_documento"].$this->configuracion["estilo"]."/".$this->estilo."/tema.php");

        $this->configuracion = $configuracion;
        $this->cripto = new encriptar();
        $this->sql = new sql_registro_codificarEstudiantesNuevos($configuracion);
        $this->log_us = new log();
        $this->validacion=new validarInscripcion();
        $this->procedimientos=new procedimientos();
        $this->formulario="admin_codificarEstudiantesNuevos";//nombre del bloque que procesa el formulario
        $this->bloque="estudiantes/registro_codificarEstudiantesNuevos";//nombre del bloque que procesa el formulario


        //Conexion General
        $this->acceso_db = $this->conectarDB($this->configuracion, "");
        $this->accesoGestion = $this->conectarDB($this->configuracion, "mysqlsga");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion = new sesiones($this->configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($this->configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        //Datos de sesion
        $this->usuario = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "identificacion");
        $this->nivel = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "nivelUsuario");

        //Conexion ORACLE
        if($this->nivel==4){
            $this->accesoOracle = $this->conectarDB($configuracion, "coordinador");
        }elseif($this->nivel==110){
            $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
        }elseif($this->nivel==114){
            $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
        }

        $this->verificar="control_vacio(".$this->formulario.",'codigo','')";
        $cadena_sql = $this->sql->cadena_sql("periodoActivo");
        $resultado_periodo = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        $this->periodo=$resultado_periodo;

        
    }
    
    /**
     * Funcion que permite consultar la información de los proyectos a los cuales esta asociado el coordinador
     */
    function validarFechas()
    {
        $this->proyecto=$_REQUEST['proyecto'];
        $this->nivelProyecto=$_REQUEST['nivel'];
        if(empty($_REQUEST['nombre'])||empty($_REQUEST['documento'])||empty($_REQUEST['tipo_documento'])||empty($_REQUEST['estado_academico'])||empty($_REQUEST['pen_nro'])||empty($_REQUEST['sexo'])||empty($_REQUEST['tipo_estudiante'])||empty($_REQUEST['acuerdo'])||empty($_REQUEST['email'])||empty($_REQUEST['periodo_codif']))
        {
            $this->mensajeError('noDatos');
            exit;
        }
        if(empty($_REQUEST['codigo'])&&trim($this->nivelProyecto)=='PREGRADO')
        {
            $this->mensajeError('noDatos');
            exit;
        }
        $_REQUEST['nombre']=strtr(strtoupper($_REQUEST['nombre']), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
        $valores=array('anio'=>  $this->periodo[0]['ANO'],
                        'periodo'=>  $this->periodo[0]['PER'],
                        'proyecto'=>$this->proyecto
                );
        $permiso=$this->consultarPermisosCodificacion($valores);
        if(!is_array($permiso))
            {
                echo "Las fechas para codificaci&oacute;n de estudiantes se encuentran cerradas";
                $this->mostrarEnlaceRegresar();
            }else
            {
                $this->registrarDatosEstudiante($_REQUEST);
            }
    }

    /**
     * 
     */
    function registrarDatosEstudiante($datos)
    {	
        $this->proyecto=$datos['proyecto'];
        if(!isset($datos['codigo']))
        {
            $datos['codigo']=$this->generarCodigoEstudianteNuevo($datos['periodo_codif']);
        }else{}
        $this->codigoEstudiante=$datos['codigo'];
        $estudiante=$this->consultarDatosEstudiante($datos['codigo']);
        if(!$estudiante)
            {
                $datosBasicos=$this->insertarDatosBasicos($datos);
                if($datosBasicos>=1)
                {
                    $otrosDatos=$this->insertarOtrosDatos($datos);
                    if($otrosDatos>=1)
                    {
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'70',
                                                'descripcion'=>'Registra estudiante nuevo',
                                                'registro'=>"codEstudiante-> ".$this->codigoEstudiante.", proyecto->".$this->proyecto.", plan->".$datos['pen_nro'],
                                                'afectado'=>$this->codigoEstudiante);
                
                        $this->procedimientos->registrarEvento($variablesRegistro);
                        $this->mensajeError('exito');
                        exit;
                        
                    }else
                        {
                            $variablesRegistro=array('usuario'=>$this->usuario,
                                                    'evento'=>'71',
                                                    'descripcion'=>'Error al registrar otros datos estudiante nuevo',
                                                    'registro'=>"codEstudiante-> ".$this->codigoEstudiante.", proyecto->".$this->proyecto.", plan->".$datos['pen_nro'],
                                                    'afectado'=>$this->codigoEstudiante);
                
                            $this->procedimientos->registrarEvento($variablesRegistro);
                            $this->mensajeError('otros_datos');
                            exit;
                        }
                }else
                    {
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'71',
                                                'descripcion'=>'Error al registrar  datos estudiante nuevo',
                                                'registro'=>"codEstudiante-> ".$this->codigoEstudiante.", proyecto->".$this->proyecto.", plan->".$datos['pen_nro'],
                                                'afectado'=>$this->codigoEstudiante);

                        $this->procedimientos->registrarEvento($variablesRegistro);
                        $this->mensajeError('datos');
                        exit;
                        
                    }
            }else
                {
                    $this->mensajeError('estudiante');exit;
                }
    }    
    
        /**
         * Funcion que permite diferenciar el ingreso para Pregrado o posgrado
         */
    function insertarDatosBasicos($datos)
        {
            $cadena_sql=$this->sql->cadena_sql("insertarDatosBasicos",$datos);
            $registro=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        }
  
    function insertarOtrosDatos($datos)
        {
        if($datos['rh']=='p')
        {$datos['rh']='+';}
        else{$datos['rh']='-';}
        $datos['ano']=  $this->periodo[0]['ANO'];
        $datos['periodo']=  $this->periodo[0]['PER'];
            $cadena_sql=$this->sql->cadena_sql("insertarOtrosDatos",$datos);
            $registro=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        }
  

    /**
     * Funcion que presenta mensajes de error y hace el retorno a la pagina inicial.
     * @param type $tipo
     */
    function mensajeError($tipo)
        {
            switch ($tipo)
            {
                case 'exito':
                    echo "<script>alert('Se registraron los datos para el estudiante con código ".$this->codigoEstudiante.".')</script>";
                break;

                case 'otros_datos':
                    echo "<script>alert('No se han podido registrar los datos adicionales del estudiante.')</script>";
                break;

                case 'datos':
                    echo "<script>alert('No se han podido registrar los datos básicos del estudiante.')</script>";
                break;

                case 'estudiante':
                    echo "<script>alert('Los datos del estudiante con código ".$this->codigoEstudiante." ya se encuentran registrados.')</script>";
                break;

                case 'codigo':
                    echo "<script>alert('Por favor ingrese un valor de código.')</script>";
                break;

                case 'noDatos':
                    echo "<script>alert('Todos los campos marcados con * son obligatorios.')</script>";
                break;
            }

            $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
            $parametro="pagina=".$this->formulario;
            $parametro.="&opcion=registroNuevoEstudiante";
            $parametro.="&codProyecto=".  $this->proyecto;
            $parametro.="&nivel=".  $this->nivelProyecto;

            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $parametro=$this->cripto->codificar_url($parametro,  $this->configuracion);
            echo "<script>location.replace('".$pagina.$parametro."')</script>";
            exit;
        }

  
    /**
     * Función para mostrar enlace que envia los datos para consultar informacion de estudiante
     */
    function enlaceConsultar()
        {
            ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
              <input type='hidden' name='action' value="<? echo $this->bloque?>">
              <input type='hidden' name='opcion' value="enviarCodigo">
              <input type='hidden' name='proyecto' value="<? echo $this->proyecto;?>">
              <input type='hidden' name='nivel' value="<? echo $this->nivelProyecto;?>">
              <input value="Consultar" name="aceptar" tabindex='20' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">                              
            <?
        }
    
    
    /**
     * Funcion que presenta enlace para regresar a la pagina inicial
     */
    function mostrarEnlaceRegresar() {
      ?>
        <div>
    <?
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        
        $parametro="pagina=".$this->formulario;
        $parametro.="&opcion=registroNuevoEstudiante";
        $parametro.="&codProyecto=".  $this->proyecto;
        $parametro.="&nivel=".  $this->nivelProyecto;

        include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $parametro=$this->cripto->codificar_url($parametro,  $this->configuracion);
    ?>
        <a href="<? echo $pagina . $parametro ?>">
            <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/atras.png" width="20" height="15" border="0" style="vertical-align:middle"><font size="2">Regresar</font>
        </a>
        </div>      
      
      <?
  }
  
    function generarCodigoEstudianteNuevo($periodo)
    {
        $secuencia=0;
        $periodo=  explode('-', $periodo);
        $ano=$periodo[0];
        if($periodo[1]==3)
        {
            $periodo=2;
        }else
            {
              $periodo=1;
            }
            $secuencia=$this->buscarCodigosEstudiantes($ano.$periodo);
            return $codEstudiante=$ano.$periodo.str_pad($this->proyecto, 3, "0", STR_PAD_LEFT).str_pad($secuencia+1, 3, "0", STR_PAD_LEFT);
    }
    
    function buscarCodigosEstudiantes($anoper)
    {
        $total=$this->consultarTotalCodigosEstudiantes($anoper);
        if(isset($total)&&is_array($total)&&$total[0][0]==0)
        {
            return $secuencia=0;
        }elseif(isset($total)&&is_array($total)&&$total[0][0]>0)
        {
            $secuencia=$this->consultarCodigosEstudiantes($anoper);
            return $secuencia[0][0];
        }else
            {
                echo "Ha ocurrido un error. Vuelva a intentar";
            }
    }

    /**
     *Consulta datos del estudiante 
     */          
    function consultarDatosEstudiante($codigo) {
               
        $variables=array(
                            'codigo'=>$codigo
                        );   

        $cadena_sql = $this->sql->cadena_sql("consultarDatosEstudiante", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        return $resultado[0];
        
    }
    
    function consultarProyectosCoordinador()
    {
        $cadena_sql=$this->sql->cadena_sql("consultarProyectosCoordinador",$this->usuario);
        $registro=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $registro;
    }
    
    /**
     * Permite verificar si existen hay fechas abiertas para codificación de estudiantes nuevos del proyecto.
     * @param type $variables
     * @return type
     */
    function consultarPermisosCodificacion($variables) {
        $cadena_sql=$this->sql->cadena_sql("consultarPermisosCodificacion",$variables);
        $registro=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $registro;
    }

    /**
     * permite consultar los tres ultimos digitos de los codigos de los estudiantes nuevos que se encuentren registrados
     * @param type $anoper Parte inicial del codigo de estudiante
     * @return type
     */
    function consultarCodigosEstudiantes($anoper) {
        $variables=array('anoper'=>$anoper,
                        'proyecto'=>$this->proyecto
                        );   
        $cadena_sql=$this->sql->cadena_sql("consultarCodigosEstudiantes",$variables);
        $registro=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $registro;
    }
    
    /**
     * permite consultar el total de estudiantes nuevos del proyecto registrados
     * @param type $anoper Parte inicial del codigo de estudiante
     * @return type
     */
    function consultarTotalCodigosEstudiantes($anoper) {
        $variables=array('anoper'=>$anoper,
                        'proyecto'=>$this->proyecto
                        );   
        $cadena_sql=$this->sql->cadena_sql("consultarTotalCodigosEstudiantes",$variables);
        $registro=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $registro;
    }
    
}
?>
