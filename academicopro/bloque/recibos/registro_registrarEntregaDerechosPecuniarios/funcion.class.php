<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
//@ Clase que permite realizar la inscripcion de un espacio academico por busqueda a un estudiante
class funcion_registroRegistrarEntregaDerechosPecuniarios extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");

        $this->configuracion=$configuracion;
        $this->validacion=new validarInscripcion();
        $this->procedimientos=new procedimientos();
        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=$sql;
        
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registro_registrarEntregaDerechosPecuniarios";
        $this->bloque="recibos/registro_registrarEntregaDerechosPecuniarios";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
        //Conexion ORACLE
        if($this->nivel==4 || $this->nivel==28){
            $this->accesoOracle = $this->conectarDB($configuracion, "coordinador");
        }elseif($this->nivel==83){
            $this->accesoOracle=$this->conectarDB($configuracion,"secretarioacad");
        }elseif($this->nivel==33){
            $this->accesoOracle=$this->conectarDB($configuracion,"admisiones");
        }

        
        ?>
    <head>
        <script language="JavaScript">
            var message = "";
            function clickIE(){
                if (document.all){
                    (message);
                    return false;
                }
            }
            function clickNS(e){
                if (document.layers || (document.getElementById && !document.all)){
                    if (e.which == 2 || e.which == 3){
                        (message);
                        return false;
                    }
                }
            }
            if (document.layers){
                document.captureEvents(Event.MOUSEDOWN);
                document.onmousedown = clickNS;
            } else {
                document.onmouseup = clickNS;
                document.oncontextmenu = clickIE;
            }
            document.oncontextmenu = new Function("return false")
        </script>
    </head>
        <?

    }

    /**
     * Funcion que valida los datos para inscribir el registro en la tabla de recibos
     * @param <array> $_REQUEST 
     * 
     */
    function validarRegistroReciboEntrega()
    {        
        $mensaje="";
        $adicionado='';
        $valida_fecha='';
        $variablesRegistro='';
        
        if($this->nivel==4 || $this->nivel == 83 || $this->nivel == 33){                   
                
            $codEstudiante = (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'');
            $anioRecibo = (isset($_REQUEST['anioRecibo'])?$_REQUEST['anioRecibo']:'');
            $periodoRecibo = (isset($_REQUEST['periodoRecibo'])?$_REQUEST['periodoRecibo']:'');
            $secuencia = (isset($_REQUEST['secuencia'])?$_REQUEST['secuencia']:'');
            $fechaEntregado = (isset($_REQUEST['fechaEntregado'])?$_REQUEST['fechaEntregado']:'');
            $datosBusqueda=array('codEstudiante'=>$codEstudiante,
                                'anioRecibo'=>$anioRecibo,
                                'periodoRecibo'=>$periodoRecibo,
                                'secuencia'=>$secuencia );
            $datosRecibo = $this->consultarReciboDerechoPecuniario($datosBusqueda);
            if(is_array($datosRecibo)){
                    $datosEstudiante = $this->consultarEstudiante($codEstudiante);
                    if(is_array($datosEstudiante)){
                        if($datosRecibo[0]['REALIZO_PAGO']=='SI'){
                                $valida_fecha=$this->validarFechaEntrega($fechaEntregado);
                                if($valida_fecha=='ok'){
                                    $datosRecibo[0]['FECHA_ENTREGA']=$fechaEntregado;
                                    $adicionadoRecibo= $this->adicionarRegistroEntrega($datosRecibo[0]);
                                    //verificamos que se halla realizado la insercion
                                                if($adicionadoRecibo){
                                                        $mensaje="Registro de entrega exitoso";
                                                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                        $variable="pagina=admin_consultarRecibosPecuniariosFuncionario";
                                                        $variable.="&opcion=nuevo";
                                                        $variable.="&datoBusqueda=".$codEstudiante;
                                                        $variable.="&tipoBusqueda=codigo";
                                                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                                                          'evento'=>'89',
                                                                                          'descripcion'=>'Registro entrega Derechos pecuniarios -'.$codEstudiante,
                                                                                          'registro'=>'codEstudiante->'.$codEstudiante.', anioRecibo->'.  $anioRecibo.', perRecibo->'.  $periodoRecibo.', secuencia->'.$secuencia.', fechaEntrega->'.$fechaEntregado,
                                                                                          'afectado'=>$codEstudiante);

                                                        $this->procedimientos->registrarEvento($variablesRegistro);

                                                        $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);                                                   
                                                }else{
                                                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                        $variable="pagina=admin_consultarRecibosPecuniariosFuncionario";
                                                        $variable.="&opcion=nuevo";
                                                        $variable.="&codEstudiante=".$codEstudiante;
                                                        $mensaje="Error al registrar entrega";
                                                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                                                          'evento'=>'89',
                                                                                          'descripcion'=>'Error al registrar entrega Derechos pecuniarios -'.$codEstudiante,
                                                                                          'registro'=>'codEstudiante->'.$codEstudiante.', anioRecibo->'.  $anioRecibo.', perRecibo->'.  $periodoRecibo.', secuencia->'.$secuencia.', fechaEntrega->'.$fechaEntregado,
                                                                                          'afectado'=>$codEstudiante);

                                                       $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

                                                }
                                }else{
                                    $mensaje="Fecha de entrega no valida";
                                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                                    'evento'=>'89',
                                                                    'descripcion'=>'Error al registrar entrega de derechos pecuniarios  -'.$codEstudiante.' codigo estudiante no valido',
                                                                    'registro'=>'codEstudiante->'.$codEstudiante.', anioRecibo->'.  $anioRecibo.', perRecibo->'.  $periodoRecibo.', secuencia->'.$secuencia.', fechaEntrega->'.$fechaEntregado,
                                                                    'afectado'=>$codEstudiante);

                                   $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                   $variable="pagina=admin_registrarEntregaDerechosPecuniarios";
                                   $variable.="&opcion=nuevo";
                                   $variable.="&codEstudiante=".$codEstudiante;
                                   $variable.="&anioRecibo=".$anioRecibo;
                                   $variable.="&periodoRecibo=".$periodoRecibo;
                                   $variable.="&secuencia=".$secuencia;
                                   $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                   $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                                }
                        }
                                        
                    }else{
                        $mensaje="No existe estudiante con el código ingresado";
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                        'evento'=>'89',
                                                        'descripcion'=>'Error al registrar entrega de derechos pecuniarios  -'.$codEstudiante.' codigo estudiante no valido',
                                                        'registro'=>'codEstudiante->'.$codEstudiante.', anioRecibo->'.  $anioRecibo.', perRecibo->'.  $periodoRecibo.', secuencia->'.$secuencia.', fechaEntrega->'.$fechaEntregado,
                                                        'afectado'=>$codEstudiante);

                       $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                       $variable="pagina=admin_registrarEntregaDerechosPecuniarios";
                       $variable.="&opcion=nuevo";
                       $variable.="&codEstudiante=".$codEstudiante;
                                   
                       $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                       $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                    }
                }else{
                    $mensaje="Recibo no valido";
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                    'evento'=>'89',
                                                    'descripcion'=>'Error al registrar entrega recibo derechos pecuniarios -'.$codEstudiante.' ',
                                                    'registro'=>'codEstudiante->'.$codEstudiante.', anioRecibo->'.  $anioRecibo.', perRecibo->'.  $periodoRecibo.', secuencia->'.$secuencia.', fechaEntrega->'.$fechaEntregado,
                                                    'afectado'=>$codEstudiante);

                   $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                   $variable="pagina=admin_consultarRecibosPecuniariosFuncionario";
                   $variable.="&opcion=nuevo";
                   $variable.="&codEstudiante=".$codEstudiante;
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                   $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                }
        }else{
                                                    ?>
                                                        <table class="contenidotabla centrar">
                                                          <tr>
                                                            <td class="cuadro_brownOscuro centrar">
                                                                <?echo "El perfil no tiene permisos para este modulo";?>
                                                            </td>
                                                          </tr>
                                                        </table>
                                                    <?                                    
                                                    $this->mostrarEnlaceRetorno();
                                                    exit;

                                             }
    }
    
   
    
    /**
     * Función para registrar un recibo de pago
     * @param array $datos
     * @return int
     */
    function adicionarRegistroEntrega($datos) {
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_entrega",$datos); 
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_adicionar,"");
               
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);
        
    }
       

    /**
     * Funcion que permite retornar a la pagina especificada
     * Cuando existe mensaje de error, lo presenta
     * @param <string> $pagina
     * @param <string> $variable
     * @param <array> $variablesRegistro (usuario,evento,descripcion,registro,afectado)
     * @param <string> $mensaje
     * Utiliza el metodo enlaceParaRetornar
     */
    function retornar($pagina,$variable,$variablesRegistro,$mensaje=""){     
        if($mensaje=="")
        {
          
        }
        else
        {
          echo "<script>alert ('".$mensaje."');</script>";
        }
        if($variablesRegistro){
            $this->procedimientos->registrarEvento($variablesRegistro);
        }
        $this->enlaceParaRetornar($pagina, $variable);
    }

    /**
     * Funcion que retorna a una pagina 
     * @param <string> $pagina
     * @param <string> $variable
     */
    function enlaceParaRetornar($pagina,$variable) {
        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;
    }

  
      /**
     * Funcion que genera la celda donde se colocan mensajes de confirmacion de cancelacion
     *
     */
    function celdaMensajes() {
      ?>
          <tr class="texto_subtitulo">
              <td colspan="2" align="center"><?
              $this->mensajesConfirmacion();
              ?></td>
          </tr>
      <?
    }

   
     /**
     * Funcion que permite encriptar un enlace
     * @param string $variable
     * @param string $opcion
     * @return string
     */
    function encriptarEnlace($variable,$opcion) {
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable.="&opcion=".$opcion;
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        return $pagina.$variable;
    }

      /**
     * Funcion que genera botones de enlace
     * @param <array> $array
     */
    function botonEnlace($array) {
      ?>
        <a href="<?echo $array['opciones']; ?>" >
            <img src="<? echo $this->configuracion["site"].$this->configuracion["grafico"];?>/<?echo $array['icono']?>" border="0" width="30" height="30"><br><?echo $array['texto']?>
        </a>
      <?
    }
    
  
    /**
     * Función para validar fechas del recibo
     * @param type $fecha_inicio
     * @param type $fecha_fin
     * @return string
     */
    function validarFechaEntrega($fecha_ordinaria){
        $valida='';
        //$fechasIguales=  $this->validarFechasIguales($fecha_ordinaria,$fecha_extraordinaria);
        $hoy = date('Y/m/d');
        $fecha_hoy = str_replace('/', '', $hoy);
        $fecha_uno = str_replace('/', '', $fecha_ordinaria);
        $valida_cadena1=  $this->validarDatoFecha($fecha_ordinaria);
        
        if($fecha_uno>$fecha_hoy || $valida_cadena1==false ){
            $valida="Fechas no validas";
        }else{
            $valida='ok';
        }
        return $valida;
    }
    
    
       
     /**
     * Función para validar que el dato de la fecha tenga caracteres validos
     * @param type $cadena
     * @return boolean
     */
    function validarDatoFecha($cadena){
        $permitidos = ":-/1234567890 ";
        for ($i=0; $i<strlen($cadena); $i++){
        if (strpos($permitidos, substr($cadena,$i,1))===false){
        //no es válido;
        return false;
        }
        } 
        //si estoy aqui es que todos los caracteres son validos
        return true;
    }  
  
       
  
  
 
    /**
     * Función para mostrar el enlace de retorno
     */
    function mostrarEnlaceRetorno(){
            $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";

            $variable2="pagina=admin_registrarEntregaDerechosPecuniarios";
            $variable2.="&opcion=nuevo";

            $variable2=$this->cripto->codificar_url($variable2,$this->configuracion);
            $enlace_aprobar=$indice.$variable2;
            echo "<br><table><tr><td ><a href='".$enlace_aprobar."'>Volver</a></td></tr></table>";
    }
    
    
    /**
     * Funcion para consultar un estudiante
     * @param type $codigo
     * @return type
     */
    function consultarEstudiante($codigo) {
        $cadena_sql = $this->sql->cadena_sql("consultar_estudiante", $codigo);
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }

    /**
     * Funcion para consultar un recibo de derecho pecuniario
     * @param type $codigo
     * @return type
     */
    function consultarReciboDerechoPecuniario($codigo) {
        $cadena_sql = $this->sql->cadena_sql("consultar_recibo_derecho_pecuniario", $codigo);
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }

    
}

?>