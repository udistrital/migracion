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
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/reglasConsejerias.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
//include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");

//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/phpmailer.class.php");
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_admin_codificarEstudiantesNuevos extends funcionGeneral {

  public $configuracion;
  public $usuario;
  public $nivel;
  public $datosEstudiante;
  public $periodo;
  public $periodoSiguiente;
  public $formulario;
  public $bloque;
  public $proyecto;
  public $nivelProyecto;
  //public $datosDocente;
  //private $notasDefinitivas;

  //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion)
    {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($this->configuracion["raiz_documento"].$this->configuracion["estilo"]."/".$this->estilo."/tema.php");

        $this->configuracion = $configuracion;
        $this->cripto = new encriptar();
        $this->sql = new sql_admin_codificarEstudiantesNuevos($configuracion);
        $this->log_us = new log();
        $this->validacion=new validarInscripcion();
        $this->formulario="admin_codificarEstudiantesNuevos";//nombre del bloque que procesa el formulario
        $this->bloque="estudiantes/admin_codificarEstudiantesNuevos";//nombre del bloque que procesa el formulario


        //Conexion General
        $this->acceso_db = $this->conectarDB($this->configuracion, "");
        $this->accesoGestion = $this->conectarDB($this->configuracion, "mysqlsga");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion = new sesiones($this->configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($this->configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        //Datos de sesion
        $this->usuario = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "id_usuario");
        if(!$this->usuario)
        {
            echo "Sesi&oacute;n cerrada. Por favor ingrese nuevamente.";
            exit;
        }
        
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
        $cadena_sql = $this->sql->cadena_sql("periodoSiguiente");
        $resultado_periodo_sig = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        $this->periodoSiguiente=$resultado_periodo_sig;

        
    }
    
    /**
     * Funcion que permite consultar la información de los proyectos a los cuales esta asociado el coordinador
     */
    function consultarProyectos()
    {
        if($this->nivel==110 || $this->nivel==114){
            $proyectos =$this->validacion->consultarProyectosAsistente($this->usuario,$this->nivel);
            foreach ($proyectos as $key => $proyecto) {
                $proyectos[$key]['CODIGO']= $proyecto['DEPENDENCIA'];
            }
            $registro=$proyectos;
            $totalRegistros=count($registro);
        }elseif($this->nivel==4  || $this->nivel==28){
            $registro=$this->consultarProyectosCoordinador();
            $totalRegistros=$this->totalRegistros($this->configuracion, $this->accesoOracle);
                                
        }
                                
        if(is_array($registro))
        {//Obtener el total de registros
            //Si tiene asignado mas de uin proyecto, pasa a presentar los proyectos para que seleccione el actor
            if($totalRegistros>1)
            {
                $this->mostrarProyectos($registro, $totalRegistros);
            }else
                {   //si solo tiene asignado un proyecto, pasa directo
                    unset($_REQUEST['action']);		
                    $pagina=$this->configuracion['host'].$this->configuracion['site'].'/index.php?';
                    $variable='pagina='.$this->formulario;
                    $variable.='&opcion=registroNuevoEstudiante';
                    $variable.='&codProyecto='.$registro[0]['CODIGO'];
                    $variable.='&nivel='.$registro[0]['NIVEL'];
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
        }else
            {
                //En caso de no encontrar datos de proyectos asociados
                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");
                $cadena="No Existen Proyectos Curriculares Registrados.";
                alerta::sin_registro($this->configuracion,$cadena);
            }
    }

    /**
     * Funcio uqe presenta los diferentes proyectos a los que esta asociado un actor cuando es mas de uno
     * @param type $registro datos de cada proyecto
     * @param type $total numero total de registros
     */
    function mostrarProyectos($registro, $total)
        {	
            $indice=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
            ?><table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
                <tbody>
                    <tr>
                        <td>
                            <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                <tr class="texto_subtitulo">
                                    <td>Codificación de Estudiantes Nuevos - Proyectos Curriculares<br><hr class="hr_subtitulo"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <table class='contenidotabla'>
                                            <tr class='cuadro_color'>
                                                <td class='cuadro_plano centrar ancho10' >C&oacute;digo</td>
                                                <td class='cuadro_plano centrar'>Nombre </td>
                                            </tr>
                                            <?
                                            for($contador=0;$contador<$total;$contador++)
                                            {
                                                $parametro="pagina=".$this->formulario;
                                                $parametro.="&hoja=1";
                                                $parametro.="&opcion=registroNuevoEstudiante";
                                                $parametro.="&codProyecto=".$registro[$contador]['CODIGO'];
                                                $parametro.="&nivel=".$registro[$contador]['NIVEL'];
                                                $parametro=  $this->cripto->codificar_url($parametro,  $this->configuracion);
                                                echo "<tr> 
                                                        <td class='cuadro_plano centrar'><a href='".$indice.$parametro."'>".$registro[$contador]['CODIGO']."</td>
                                                        <td class='cuadro_plano'><a href='".$indice.$parametro."'>".$registro[$contador]['NOMBRE']."</a></td>
                                                    </tr>";
                                            }?>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class='cuadro_plano cuadro_brown'>
                            <p class="textoNivel0">Por favor haga click sobre el proyecto curricular que desee consultar.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?
        }    
    
        /**
         * Funcion que permite diferenciar el ingreso para Pregrado o posgrado
         */
    function registroNuevoEstudiante()
        {
            $this->nivelProyecto=trim($_REQUEST['nivel']);
            $this->proyecto=trim($_REQUEST['codProyecto']);            ;     
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
                    if ($this->nivelProyecto=='PREGRADO')
                    {
                        //solicita codigo o credencial
                        $this->presentarFormularioCodigoCredencial();
                    }else
                        {
                            $this->presentarFormularioDatosEstudiante();
                        }
                }
            exit;
        }
  
    /**
     * Funcion que permite consultar los datos del estudiante con el numero de credencial o codigo ingresado
     */
    function consultarEstudianteAdmitido()
        {
            $this->proyecto=$_REQUEST['proyecto'];
            $this->nivelProyecto=$_REQUEST['nivel'];
            $periodo=explode('-', $_REQUEST['periodo_codif']);
            if(isset($_REQUEST['busqueda'])&&$_REQUEST['busqueda']=='codigo'&&!empty($_REQUEST['codigo']))
            {
                if(is_numeric($_REQUEST['codigo']))
                {
                    $this->datosEstudiante=$this->consultarDatosEstudiante('codigo',$_REQUEST['codigo'],$periodo);
                    if(!is_array($this->datosEstudiante))
                    {
                        $this->mensajeError('estudianteCodigo');
                    }elseif($this->datosEstudiante['CRA_COD']==$this->proyecto)
                        {
                            $this->presentarFormularioDatosEstudiante();
                        }else
                            {
                                $this->mensajeError('proyecto');
                            }
                }else
                    {
                        $this->mensajeError('numerico');
                    }
            }elseif (isset($_REQUEST['busqueda'])&&$_REQUEST['busqueda']=='credencial'&&!empty($_REQUEST['codigo']))
                {
                    if(is_numeric($_REQUEST['codigo']))
                    {
                        $this->datosEstudiante=$this->consultarDatosEstudiante('credencial',$_REQUEST['codigo'],$periodo);
                    if(!is_array($this->datosEstudiante))
                    {
                        $this->mensajeError('estudianteCredencial');
                    }elseif ($this->datosEstudiante['CRA_COD']==$this->proyecto)
                        {
                            $this->presentarFormularioDatosEstudiante();
                        }else
                            {
                                $this->mensajeError('proyecto');
                            }
                    }else
                        {
                            $this->mensajeError('numerico');
                        }
                }else
                    {
                        $this->mensajeError('codigo');
                    }
                    exit;
        }
  
    /**
     * Funcion que presenta mensajes al usuario y hace el retorno a la pagina inicial.
     * @param type $tipo
     */
    function mensajeError($tipo)
        {
            switch ($tipo)
            {
                case 'numerico':
                    echo "<script>alert('Por favor ingrese un valor numérico.')</script>";
                break;

                case 'proyecto':
                    echo "<script>alert('El estudiante no pertenece al Proyecto Curricular ".$this->proyecto.".')</script>";
                break;

                case 'estudianteCodigo':
                    echo "<script>alert('No hay datos del estudiante con el código ingresado para el periodo.')</script>";
                break;

                case 'estudianteCredencial':
                    echo "<script>alert('No hay datos del estudiante con la credencial ingresada para el periodo.')</script>";
                break;

                case 'codigo':
                    echo "<script>alert('Por favor ingrese un valor.')</script>";
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
     * Función para mostrar el formulario donde el usuario ingresará el codigo del estudiante para realizar la busqueda de información
     */
    function presentarFormularioCodigoCredencial()
        {
            $tab=1;
            $datos='';
            $codigo  =(isset($_REQUEST['codigo'])?$_REQUEST['codigo']:'');
            ?>
            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <div id="normal" >
                    <table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                        <tr class=texto_elegante >
                            <td colspan='2'>
                                <b>::::..</b>  Codificar estudiantes nuevos
                                <hr class=hr_subtitulo>
                            </td>
                        </tr>        
                        <tr>    
                            <td width="20%">
                                <input type="radio" name="busqueda" value="codigo" checked> Código<br>
                                <input type="radio" name="busqueda" value="credencial"> Credencial<br>
                            </td>
                            <td width="20%" >
                                <input type="text" name='codigo' id="codigo" size="18"  <? if($codigo) echo "value='".$codigo."'";?> onKeyPress="return solo_numero_sin_slash(event)">
                            </td>
                        </tr>
                        <tr>
                                <td width="100%" colspan='2'>Seleccione el período para codificar...
<?                                                $html_perCod="<select id='periodo_codif' tabindex='".$tab++."' size='1' name='periodo_codif'>";
                                                    $html_perCod.="<option value='".$this->periodo[0]['ANO']."-".$this->periodo[0]['PER']."'";
                                                     $html_perCod.=" >".$this->periodo[0]['ANO']."-".$this->periodo[0]['PER']."</option>  ";          
                                                    $html_perCod.="<option value='".$this->periodoSiguiente[0]['ANO']."-".$this->periodoSiguiente[0]['PER']."'";
                                                     $html_perCod.=" >".$this->periodoSiguiente[0]['ANO']."-".$this->periodoSiguiente[0]['PER']."</option>  ";          
                                            $html_perCod.="</select>";
                                            $html_perCod.="<font color='red'> *</font>";
                                            echo $html_perCod;
                                                
                                            ?> 
                                </td>
                        </tr>
                        <tr>
                            <td width="100%" colspan='2'>
                                <? $this->enlaceConsultar('Consultar','enviarCodigo');?>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
            <?
        }

    /**
     * Función para mostrar enlace que envia los datos para consultar informacion de estudiante
     */
    function enlaceConsultar($nombre,$opcion)
        {
            ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
              <input type='hidden' name='action' value="<? echo $this->bloque?>">
              <input type='hidden' name='opcion' value="<?echo $opcion;?>">
              <input type='hidden' name='proyecto' value="<? echo $this->proyecto;?>">
              <input type='hidden' name='nivel' value="<? echo $this->nivelProyecto;?>">
              <input value="<?echo $nombre;?>" name="aceptar" tabindex='20' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">                              
            <?
        }
    
    
    /**
     * Funcion que presenta el formulario para completar o registrar los datos del estudiante
     */
    function presentarFormularioDatosEstudiante() {
            $tab=1;
            $datos='';
            $codigo  =(isset($this->datosEstudiante['CODIGO'])?$this->datosEstudiante['CODIGO']:'');
            $cra_cod  =(isset($this->datosEstudiante['CRA_COD'])?$this->datosEstudiante['CRA_COD']:'');
            $nombre  =(isset($this->datosEstudiante['NOMBRE'])?$this->datosEstudiante['NOMBRE']:'');
            $nro_iden  =(isset($this->datosEstudiante['NRO_IDEN'])?$this->datosEstudiante['NRO_IDEN']:'');
            $tipo_iden  =(isset($this->datosEstudiante['TDO_CODVAR'])?$this->datosEstudiante['TDO_CODVAR']:'');
            $distrito_militar  =(isset($this->datosEstudiante['ASP_DIS_MILITAR'])?$this->datosEstudiante['ASP_DIS_MILITAR']:'');
            $direccion  =(isset($this->datosEstudiante['ASP_DIRECCION'])?$this->datosEstudiante['ASP_DIRECCION']:'');
            $telefono  =(isset($this->datosEstudiante['ASP_TELEFONO'])?$this->datosEstudiante['ASP_TELEFONO']:'');
            $exento  =(isset($this->datosEstudiante['EAD_EXENTO'])?$this->datosEstudiante['EAD_EXENTO']:'');
            $motivo_exento  =(isset($this->datosEstudiante['EAD_MOTIVO_EXENTO'])?$this->datosEstudiante['EAD_MOTIVO_EXENTO']:'');
            $nombre_exento  =(isset($this->datosEstudiante['EXE_NOMBRE'])?$this->datosEstudiante['EXE_NOMBRE']:'');
            $renta_liquida  =(isset($this->datosEstudiante['EAD_RENTA_LIQUIDA'])?$this->datosEstudiante['EAD_RENTA_LIQUIDA']:'');
            $patrimonio_liquido  =(isset($this->datosEstudiante['EAD_PATRIMONIO_LIQUIDO'])?$this->datosEstudiante['EAD_PATRIMONIO_LIQUIDO']:'');
            $valor_matricula  =(isset($this->datosEstudiante['EAD_VALOR_MATRICULA'])?$this->datosEstudiante['EAD_VALOR_MATRICULA']:'');
            $pen_nro  =(isset($this->datosEstudiante['EAD_PEN_NRO'])?$this->datosEstudiante['EAD_PEN_NRO']:'');
            $sexo =(isset($this->datosEstudiante['ASP_SEXO'])?$this->datosEstudiante['ASP_SEXO']:'');
            $pbm  =(isset($this->datosEstudiante['EAD_PBM'])?$this->datosEstudiante['EAD_PBM']:'');
            $ingresos_anuales  =(isset($this->datosEstudiante['EAD_INGRESOS_ANUALES'])?$this->datosEstudiante['EAD_INGRESOS_ANUALES']:'');
            $fecha_nac  =(isset($this->datosEstudiante['ASP_FECHA_NAC'])?$this->datosEstudiante['ASP_FECHA_NAC']:'');
            $lug_nac  =(isset($this->datosEstudiante['ASP_LUG_NAC'])?$this->datosEstudiante['ASP_LUG_NAC']:'');
            $estado_civil  =(isset($this->datosEstudiante['ASP_ESTADO_CIVIL'])?$this->datosEstudiante['ASP_ESTADO_CIVIL']:'');
            $estrato =(isset($this->datosEstudiante['ASP_ESTRATO'])?$this->datosEstudiante['ASP_ESTRATO']:'');
            $email =(isset($this->datosEstudiante['ASP_EMAIL'])?$this->datosEstudiante['ASP_EMAIL']:'');
            $email_ins =(isset($this->datosEstudiante['ACC_CORREO'])?$this->datosEstudiante['ACC_CORREO']:'');
            $grupo_sanguineo =(isset($this->datosEstudiante['ASP_TIPO_SANGRE'])?$this->datosEstudiante['ASP_TIPO_SANGRE']:'');
            $rh =(isset($this->datosEstudiante['ASP_RH'])?$this->datosEstudiante['ASP_RH']:'');
            $zona_postal =(isset($this->datosEstudiante['zona_postal'])?$this->datosEstudiante['zona_postal']:'');
            $porcentaje =(isset($this->datosEstudiante['porcentaje'])?$this->datosEstudiante['porcentaje']:'');
            $snp =(isset($this->datosEstudiante['ASP_SNP'])?$this->datosEstudiante['ASP_SNP']:'');
            $puntos =(isset($this->datosEstudiante['ASP_PTOS'])?$this->datosEstudiante['ASP_PTOS']:'');
            $tipo_colegio =(isset($this->datosEstudiante['ASP_TIPO_COLEGIO'])?$this->datosEstudiante['ASP_TIPO_COLEGIO']:'');
            $credencial =(isset($this->datosEstudiante['ASP_CRED'])?$this->datosEstudiante['ASP_CRED']:'');
            
            $num_libreta_militar=(isset($_REQUEST['num_libreta_militar'])?$_REQUEST['num_libreta_militar']:'');
            $celular =(isset($_REQUEST['celular'])?$_REQUEST['celular']:'');
            //$email_ins =(isset($_REQUEST['email_ins'])?$_REQUEST['email_ins']:'');
            $acuerdo =2011004;
            $estado_academico='A';
            $tipo_estudiante='C';
            
            $grupoSanguineo[0][0]='O';
            $grupoSanguineo[0][1]='O';
            $grupoSanguineo[1][0]='A';
            $grupoSanguineo[1][1]='A';
            $grupoSanguineo[2][0]='B';
            $grupoSanguineo[2][1]='B';
            $grupoSanguineo[3][0]='AB';
            $grupoSanguineo[3][1]='AB';
            
            $rhs[0][0]='p';
            $rhs[0][1]='+';
            $rhs[1][0]='n';
            $rhs[1][1]='-';

            $generos[0][0]='F';
            $generos[0][1]='F';
            $generos[1][0]='M';
            $generos[1][1]='M';
            
            $tipoEstudiante[0][0]='N';
            $tipoEstudiante[0][1]='Horas';
            $tipoEstudiante[1][0]='S';
            $tipoEstudiante[1][1]='Creditos';
            
            $estratos[0][0]='0';
            $estratos[0][1]='0';
            $estratos[1][0]='1';
            $estratos[1][1]='1';
            $estratos[2][0]='2';
            $estratos[2][1]='2';
            $estratos[3][0]='3';
            $estratos[3][1]='3';
            $estratos[4][0]='4';
            $estratos[4][1]='4';
            $estratos[5][0]='5';
            $estratos[5][1]='5';
            $estratos[6][0]='6';
            $estratos[6][1]='6';
            
            $registroEstados=$this->consultarEstadosAcademicos();
            $registroMunicipios=$this->consultarMunicipios();
            $registroEstadosCiviles=$this->consultarEstadosCiviles();
            $registroTipoDocumento=$this->consultarTiposDocumentos();
            if($cra_cod)
            {
                $planes=$this->consultarPlanesProyecto($cra_cod);
            }else
                {
                    $planes=$this->consultarPlanesProyecto($this->proyecto);
                }
            ?>
        <link rel='stylesheet' type='text/css' media='all' href='<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-blue2.css"?>' title="win2k-cold-1"/>
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar.js"?>></script> 
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-es.js"?>></script>
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-setup.js"?>></script>
        <style type="text/css">.mayus {text-transform: uppercase;}</style>
                <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                    
                <div id="normal" >

                        <table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                            <tr class=texto_elegante >
                                <td colspan='2'>
                                            <b>..:::: </b>  Codificar estudiantes nuevos.
                                        <hr class=hr_subtitulo>
                                </td>
                            </tr>        
                            <tr class=texto_elegante >
                                <td colspan='2'>
                                            &nbsp;&nbsp;&nbsp;Los campos marcados con <font color="red">*</font> son obligatorios.
                                </td>
                            </tr>        
                            <tr>    
                                <td width="30%">Proyecto Curricular</td>
                                <td >
                                            <? if($cra_cod){ echo $cra_cod;}else{echo $this->proyecto;}?> 
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">Código estudiante</td>
                                <td >
                                            <? if($codigo) {
                                                echo $codigo;?><input type='hidden' name='codigo' value="<? echo $codigo ?>"><?
                                                
                                            }else{
                                                echo "<font color='red'>Por generar... seleccione el per&iacute;odo para codificar</font> ";
                                                $html_perCod="<select id='periodo_codif' tabindex='".$tab++."' size='1' name='periodo_codif'>";
                                                    $html_perCod.="<option value='".$this->periodo[0]['ANO']."-".$this->periodo[0]['PER']."'";
                                                     $html_perCod.=" >".$this->periodo[0]['ANO']."-".$this->periodo[0]['PER']."</option>  ";          
                                                    $html_perCod.="<option value='".$this->periodoSiguiente[0]['ANO']."-".$this->periodoSiguiente[0]['PER']."'";
                                                     $html_perCod.=" >".$this->periodoSiguiente[0]['ANO']."-".$this->periodoSiguiente[0]['PER']."</option>  ";          
                                            $html_perCod.="</select>";
                                            $html_perCod.="<font color='red'> *</font>";
                                            echo $html_perCod;
                                                
                                            }?> 
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%"><font color="red">*</font> Apellidos y Nombres</td>
                                <td >
                                            <input type="text" name='nombre' id="nombre" size="40" class="mayus" <? if($nombre) echo "value='".$nombre."'";?> >
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%"><? if(!$tipo_iden){?><font color="red">*</font><?}?> Tipo de documento de identificación</td>
                                <td >
                                            <? if($tipo_iden) {echo $tipo_iden;?><input type='hidden' name='tipo_documento' value="<? echo $tipo_iden ?>"><?}else{
                                            $html_tipoDoc="<select id='tipo_documento' tabindex='".$tab++."' size='1' name='tipo_documento'>";
                                            $html_tipoDoc.="<option value=''>Seleccione uno</option>  ";          
                                            foreach ($registroTipoDocumento as $key => $tipoDoc) {
                                                    $html_tipoDoc.="<option value='".$tipoDoc[0]."' ";
                                                    if($tipo_iden==$tipoDoc[0]){
                                                         $html_tipoDoc.=" selected ";
                                                    }
                                                     $html_tipoDoc.=" >".$tipoDoc[1]."</option>  ";          
                                            }
                                            $html_tipoDoc.="</select>";
                                            echo $html_tipoDoc;
                                                
                                            }?>
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%"><?if(!$nro_iden) {?><font color="red">*</font><?}?> N&uacute;mero de documento de identificaci&oacute;n</td>
                                <td >
                                            <? if($nro_iden) {echo $nro_iden;?><input type='hidden' name='documento' value="<? echo $nro_iden ?>"><?}else{?><input type="text" name='documento' id="documento" size="40" onKeyPress="return solo_numero_sin_slash(event)">  Digite el valor sin comas ni puntos.<?}?>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">&nbsp;Número de libreta Militar</td>
                                <td >
                                            <input type="text" name='num_libreta_militar' id="num_libreta_militar" size="18"  <? if($num_libreta_militar) echo "value='".$num_libreta_militar."'";?> onKeyPress="return solo_numero_sin_slash(event)">
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Número de distrito Militar</td>
                                <td >
                                            <input type="text" name='distrito_militar' id="distrito_militar" size="2" maxlength="2" <? if($distrito_militar) echo "value='".$distrito_militar."'";?> onKeyPress="return solo_numero_sin_slash(event)">
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Dirección</td>
                                <td >
                                            <input type="text" name='direccion' id="direccion" size="40"  <? if($direccion) echo "value='".$direccion."'";?> onKeyPress="return soloNumerosYLetras(event)">
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Número telefónico</td>
                                <td >
                                            <input type="text" name='telefono' id="telefono" size="10"  <? if($telefono) echo "value='".$telefono."'";?> onKeyPress="return solo_numero_sin_slash(event)">
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Número celular </td>
                                <td >
                                            <input type="text" name='celular' id="celular" size="10"  maxlength="10"<? if($celular) echo "value='".$celular."'";?> onKeyPress="return solo_numero_sin_slash(event)">
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Valor matrícula</td>
                                <td >
                                            <? if($valor_matricula) {echo "$".number_format ($valor_matricula);?><input type='hidden' name='matricula' value="<? echo $valor_matricula ?>"><?}else{?><input type="text" name='matricula' id="matricula" size="18" onKeyPress="return solo_numero_sin_slash(event)"> Digite el valor sin comas ni puntos.<?}?> 
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%"><font color="red">*</font> Estado Académico</td>
                                <td >
                                            
                                            <?
                                            $html_estadoAc="<select id='estado_academico' tabindex='".$tab++."' size='1' name='estado_academico'>";
                                            $html_estadoAc.="<option value=''>Seleccione uno</option>  ";          
                                            foreach ($registroEstados as $key => $estadoAc) {
                                                    $html_estadoAc.="<option value='".$estadoAc[0]."' ";
                                                    if($estado_academico==$estadoAc[0]){
                                                         $html_estadoAc.=" selected ";
                                                    }
                                                     $html_estadoAc.=" >".$estadoAc[1]."</option>  ";          
                                            }
                                            $html_estadoAc.="</select>";
                                            echo $html_estadoAc;
                                            ?>
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%"><font color="red">*</font> No. Plan de estudios</td>
                                <td >
                                            <?
                                            $html_plan="<select id='pen_nro' tabindex='".$tab++."' size='1' name='pen_nro'>";
                                            $html_plan.="<option value=''>Seleccione uno</option>  ";          
                                            foreach ($planes as $key => $plan) {
                                                    $html_plan.="<option value='".$plan[0]."' ";
                                                    if($pen_nro==$plan[0]){
                                                         $html_plan.=" selected ";
                                                    }
                                                     $html_plan.=" >".$plan[0]."</option>  ";
                                            }
                                            $html_plan.="</select>";
                                            echo $html_plan;
                                            ?>
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%"><font color="red">*</font> Sexo</td>
                                <td >
                                            <?
                                            $html_sexo="<select id='sexo' tabindex='".$tab++."' size='1' name='sexo'>";
                                            $html_sexo.="<option value=''>Seleccione uno</option>  ";          
                                            foreach ($generos as $key => $genero) {
                                                    $html_sexo.="<option value='".$genero[0]."' ";
                                                    if($sexo==$genero[0]){
                                                         $html_sexo.=" selected ";
                                                    }
                                                     $html_sexo.=" >".$genero[1]."</option>  ";          
                                            }
                                            $html_sexo.="</select>";
                                            echo $html_sexo;
                                            ?>
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%"><font color="red">*</font> Tipo estudiante </td>
                                <td >
                                            
                                             <?
                                            $html_tipo_estudiante="<select id='tipo_estudiante' tabindex='".$tab++."' size='1' name='tipo_estudiante'>";
                                            $html_tipo_estudiante.="<option value=''>Seleccione uno</option>  ";          
                                            foreach ($tipoEstudiante as $key => $tipo) {
                                                    $html_tipo_estudiante.="<option value='".$tipo[0]."' ";
                                                    if($tipo_estudiante==$tipo[0]){
                                                         $html_tipo_estudiante.=" selected ";
                                                    }
                                                     $html_tipo_estudiante.=" >".$tipo[1]."</option>  ";          
                                            }
                                            $html_tipo_estudiante.="</select>";
                                            echo $html_tipo_estudiante;
                                            ?>
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Acuerdo </td>
                                <td >
                                            <? if($acuerdo==2011004){echo "Acuerdo 004 de 2011";?><input type='hidden' name='acuerdo' value="<? echo $acuerdo ?>"><?}?>
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Fecha de Nacimiento </td>
                                <td >
                                        
                                    <table>
                                        <tr>
                                            <td >
                                                <input name="fecha_nac" type="text" id="fecha_nac" size="15" <? if($fecha_nac)echo "value=".$fecha_nac;?>   readonly="readonly"/></td>
                                            <td >
                                                <input align="center"type=image src="<? echo $this->configuracion['site'] . $this->configuracion['grafico']; ?>/cal.png" name="btnFecha" id="btnFecha" tabindex='<? echo $tab++;?>'>

                                                <script type="text/javascript">  
                                                    Calendar.setup({
                                                        inputField    : "fecha_nac",
                                                        button        : "btnFecha",
                                                        align         : "Tr"
                                                    });
                                                </script>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Lugar de Nacimiento</td>
                                <td >
                                            <?
                                            $html_municipios="<select id='lug_nac' tabindex='".$tab++."' size='1' name='lug_nac'>";
                                            $html_municipios.="<option value='0'>Seleccione uno</option>  ";          
                                            foreach ($registroMunicipios as $key => $municipio) {
                                                    $html_municipios.="<option value='".$municipio[0]."' ";
                                                    if($lug_nac==$municipio[0]){
                                                         $html_municipios.=" selected ";
                                                    }
                                                     $html_municipios.=" >".$municipio[1]."</option>  ";          
                                            }
                                            $html_municipios.="</select>";
                                            echo $html_municipios;
                                            ?>
                                            
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Estado Civil  </td>
                                <td >
                                            
                                            <?
                                            $html_estadosC="<select id='estado_civil' tabindex='".$tab++."' size='1' name='estado_civil'>";
                                            $html_estadosC.="<option value=''>Seleccione uno</option>  ";          
                                            foreach ($registroEstadosCiviles as $key => $estados) {
                                                    $html_estadosC.="<option value='".$estados[0]."' ";
                                                    if($estado_civil==$estados[0]){
                                                         $html_estadosC.=" selected ";
                                                    }
                                                     $html_estadosC.=" >".$estados[1]."</option>  ";          
                                            }
                                            $html_estadosC.="</select>";
                                            echo $html_estadosC;
                                            ?>
                                </td>
                            </tr>
                            
                            <tr>    
                                <td width="30%">&nbsp;Estrato  </td>
                                <td >
                                            
                                             <?
                                            $html_estrato="<select id='estrato' tabindex='".$tab++."' size='1' name='estrato'>";
                                            $html_estrato.="<option value=''>Seleccione uno</option>  ";          
                                            foreach ($estratos as $key => $valor) {
                                                    $html_estrato.="<option value='".$valor[0]."' ";
                                                    if($estrato==$valor[0]){
                                                         $html_estrato.=" selected ";
                                                    }
                                                     $html_estrato.=" >".$valor[1]."</option>  ";          
                                            }
                                            $html_estrato.="</select>";
                                            echo $html_estrato;
                                            ?>
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%"><font color="red">*</font> E-mail </td>
                                <td >
                                            <input type="text" name='email' id="email" size="40"  <? if($email) echo "value='".$email."'";?> ">
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;E-mail institucional </td>
                                <td >
                                            <input type="text" name='email_ins' id="email_ins" size="40"  <? if($email_ins) echo "value='".$email_ins."'";?> >
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Grupo sanguíneo</td>
                                <td >
                                            <?
                                            $html_sangre="<select id='grupo_sanguineo' tabindex='".$tab++."' size='1' name='grupo_sanguineo'>";
                                            $html_sangre.="<option value=''>Seleccione uno</option>  ";          
                                            foreach ($grupoSanguineo as $key => $grupo) {
                                                    $html_sangre.="<option value='".$grupo[0]."' ";
                                                    if($grupo_sanguineo==$grupo[0]){
                                                         $html_sangre.=" selected ";
                                                    }
                                                     $html_sangre.=" >".$grupo[1]."</option>  ";          
                                            }
                                            $html_sangre.="</select>";
                                            echo $html_sangre;
                                            ?>
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Factor RH </td>
                                <td >
                                            <?
                                            $html_rh="<select id='rh' tabindex='".$tab++."' size='1' name='rh'>";
                                            $html_rh.="<option value=''>Seleccione uno</option>  ";          
                                            foreach ($rhs as $key => $valor) {
                                                    $html_rh.="<option value='".$valor[0]."' ";
                                                    if($rh==$valor[0]){
                                                         $html_rh.=" selected ";
                                                    }
                                                     $html_rh.=" >".$valor[1]."</option>  ";          
                                            }
                                            $html_rh.="</select>";
                                            echo $html_rh;
                                            ?>
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Zona Postal </td>
                                <td >
                                            <input type="text" name='zona_postal' id="zona_postal" size="6" maxlength="6" <? if($zona_postal) echo "value='".$zona_postal."'";?> onKeyPress="return solo_numero_sin_slash(event)">
                                </td>
                            </tr>
                            
                            <tr>    
                                <td width="30%">&nbsp;Excención </td>
                                <td >
                                            <? if($motivo_exento||$motivo_exento==0){ echo $nombre_exento;?>
                                                <input type='hidden' name='motivo_exento' value="<? echo $motivo_exento ?>">
                                                <input type='hidden' name='exento' value="<? echo $exento ?>"><?
                                                }else{echo "0";?>
                                                <input type='hidden' name='motivo_exento' value="0">
                                                <input type='hidden' name='exento' value="N">
                                                <?}?> 
                                </td>
                            </tr>
                            <tr>    
                                <td width="30%">&nbsp;Porcentaje </td>
                                <td >
                                            <? if($porcentaje) {echo $porcentaje;?>
                                                <input type='hidden' name='porcentaje' value="<? echo $porcentaje ?>"><?
                                                }else{echo "0";?>
                                                <input type='hidden' name='porcentaje' value="0"><?}?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type='hidden' name='renta_liquida' value="<? echo $renta_liquida ?>">
                                    <input type='hidden' name='patrimonio_liquido' value="<? echo $patrimonio_liquido ?>">
                                    <input type='hidden' name='pbm' value="<? echo $pbm ?>">
                                    <input type='hidden' name='ingresos_anuales' value="<? echo $ingresos_anuales ?>">
                                    <input type='hidden' name='snp' value="<? echo $snp ?>">
                                    <input type='hidden' name='puntos' value="<? echo $puntos ?>">
                                    <input type='hidden' name='tipo_colegio' value="<? echo $tipo_colegio ?>">
                                    <input type='hidden' name='credencial' value="<? echo $credencial ?>">
                                    <?if(($_REQUEST['nivel'])=='PREGRADO')
                                    {?><input type='hidden' name='periodo_codif' value="<? echo $_REQUEST['periodo_codif'] ?>">
                                    <?}?>
                                </td>
                            </tr>
                            
                            <tr>    
                                <td colspan='3'><? $this->enlaceConsultar('Registrar','registrarDatos');?></td>
                            </tr>

                        </table>
                    
                    </div>

                   </form>
                <?
        $this->mostrarEnlaceRegresar();exit;
        
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

    /**
     *Consulta datos del estudiante 
     */          
    function consultarDatosEstudiante($opcion,$codigo,$periodo) {
               
        $variables=array(
                            'opcion'=>$opcion,
                            'codigo'=>$codigo,
                            'ano'=>$periodo[0],
                            'periodo'=>$periodo[1]
                        );   

        $cadena_sql = $this->sql->cadena_sql("consultarDatosEstudiante", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        return $resultado[0];
        
    }
  
    /**
     * Permite consultar los proyectos a los que esta asociado el coordinador
     * @return type
     */
    function consultarProyectosCoordinador()
    {
        $cadena_sql=$this->sql->cadena_sql("consultarProyectosCoordinador",$this->usuario);
        $registro=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $registro;
    }
    
    
    /**
     * Permite obtenrer el listado de los estados academicos registrados
     * @return type
     */
    function consultarEstadosAcademicos() {
       
        $cadena_sql = $this->sql->cadena_sql("consultarEstadosAcademicos", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
    }
    
    /**
     * Permite obtener el listado de municipios registrados
     * @return type
     */
    function consultarMunicipios() {
       
        $cadena_sql = $this->sql->cadena_sql("consultarMunicipios", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
    }

    /**
     * Permite obtener el listado de estados civiles registrados
     * @return type
     */
    function consultarEstadosCiviles() {
       
        $cadena_sql = $this->sql->cadena_sql("consultarEstadosCiviles", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
    }
    
    /**
     * Permite obtener el listado de los tipos de documentos de identidad registrados
     * @return type
     */
    function consultarTiposDocumentos() {
       
        $cadena_sql = $this->sql->cadena_sql("consultarTiposDocumentos", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
        
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
     * Permite verificar si existen hay fechas abiertas para codificación de estudiantes nuevos del proyecto.
     * @param type $variables
     * @return type
     */
    function consultarPlanesProyecto($codProyecto) {
        $cadena_sql=$this->sql->cadena_sql("consultarPlanesProyecto",$codProyecto);
        $registro=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $registro;
    }
}
?>
