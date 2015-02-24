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
class funcion_registroGenerarReciboPago extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $ano;
    private $periodo;

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
        $this->formulario="registro_generarReciboPago";
        $this->bloque="recibos/registro_generarReciboPago";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
        //Conexion ORACLE
        $this->accesoOracle = $this->conectarDB($configuracion, "soporteoas");
        
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
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
    function validarRegistroRecibo()
    {        
        $mensaje="";
        $adicionado='';
        $valida_fecha='';
        $variablesRegistro='';
        $codEstudiante=(isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'');
        $valorOrdinario=(isset($_REQUEST['valorOrdinario'])?$_REQUEST['valorOrdinario']:0);
        $valorExtraordinario=(isset($_REQUEST['valorExtraordinario'])?$_REQUEST['valorExtraordinario']:0);
        $periodoRecibo=(isset($_REQUEST['periodoRecibo'])?$_REQUEST['periodoRecibo']:'');
        $cuota=(isset($_REQUEST['cuota'])?$_REQUEST['cuota']:'');
        $fechaOrdinaria=(isset($_REQUEST['fechaOrdinaria'])?$_REQUEST['fechaOrdinaria']:'');
        $fechaExtraordinaria=(isset($_REQUEST['fechaExtraordinaria'])?$_REQUEST['fechaExtraordinaria']:'');
        $observacion=(isset($_REQUEST['observacion'])?$_REQUEST['observacion']:'');
        $datosEstudiante = $this->consultarEstudiante($_REQUEST['codEstudiante']);

        if($this->nivel==80 ){                        
                if($codEstudiante && $valorOrdinario>=0 && $valorExtraordinario>=0 && $periodoRecibo && $cuota && $fechaOrdinaria && $fechaExtraordinaria ){
                    if(is_array($datosEstudiante)){

                        if(is_numeric($valorOrdinario) && is_numeric($valorExtraordinario) && $valorOrdinario<=$valorExtraordinario ||($anio.$periodo==$this->ano.$this->periodo && $valorOrdinario==0 && $valorExtraordinario==0)){

                            $anio = substr($periodoRecibo, 0, 4);
                            $periodo = substr($periodoRecibo, 5, 1);

                            if(is_numeric($anio) && is_numeric($periodo) && $anio.$periodo<=$this->ano.$this->periodo ){
                                if(is_numeric($cuota) && $cuota<=3){

                                    $valida_fecha=$this->validarFechasRecibo($fechaOrdinaria, $fechaExtraordinaria);
                                    if($valida_fecha=='ok'){
                                                    $obs='';
                                            if((isset($_REQUEST['obs1'])?$_REQUEST['obs1']:'')==1){
                                                $obs.=" TER. MATERIAS";
                                            }
                                            if((isset($_REQUEST['obs2'])?$_REQUEST['obs2']:'')==2){
                                                $obs.=" PRUEBA ACADÉMICA";
                                            }
                                            if((isset($_REQUEST['obs3'])?$_REQUEST['obs3']:'')==3){
                                                $obs.=" MOVILIDAD";
                                            }
                                            if((isset($_REQUEST['obs4'])?$_REQUEST['obs4']:'')==4){
                                                $obs.=" DTO CE 10%";
                                            }
                                            if((isset($_REQUEST['obs5'])?$_REQUEST['obs5']:'')==5){
                                                $obs.=" EGRESADO 30% ";
                                            }
                                            if((isset($_REQUEST['obs6'])?$_REQUEST['obs6']:'')==6){
                                                $obs.=" DTO SEGUNDO HERMANO 50% ";
                                            }
                                            if((isset($_REQUEST['obs7'])?$_REQUEST['obs7']:'')==7){
                                                $obs.=" DTO TERCER HERMANO 70%";
                                            }
                                            if((isset($_REQUEST['obs8'])?$_REQUEST['obs8']:'')==8){
                                                $obs.=" MAT. HONOR 100%";
                                            }
                                            if((isset($_REQUEST['obs9'])?$_REQUEST['obs9']:'')==9){
                                                $obs.=" DEPORTISTA ALTO REND.";
                                            }
                                            if((isset($_REQUEST['obs10'])?$_REQUEST['obs10']:'')==10){
                                                $obs.=" E. DISTINGUIDO. 100%";
                                            }
                                            if((isset($_REQUEST['obs11'])?$_REQUEST['obs11']:'')==11){
                                                $obs.=" MER. ACADEMICO 100%";
                                            }
                                            if((isset($_REQUEST['obs12'])?$_REQUEST['obs12']:'')==12){
                                                $obs.=" MONITOR 50%";
                                            }

                                            if((isset($_REQUEST['obs13'])?$_REQUEST['obs13']:'')==13){
                                                $obs.=" DTO SED 70%";
                                            }
                                            if((isset($_REQUEST['obs14'])?$_REQUEST['obs14']:'')==14){
                                                $obs.=" DOCENTE 100%";
                                            }
                                            if($anio.$periodo<$this->ano.$this->periodo){
                                                $obs .= "PAGO PERIODO ".$periodoRecibo;
                                            } 
                                            $datosRegistro=array('anio'=>$this->ano,
                                                                    'periodo'=>  $this->periodo,
                                                                    'codEstudiante'=>$codEstudiante,
                                                                    'valorOrdinario'=>$valorOrdinario,
                                                                    'valorExtraordinario'=>$valorExtraordinario,
                                                                    'anioRecibo'=>$anio,
                                                                    'perRecibo'=>$periodo,
                                                                    'cuota'=>$cuota,
                                                                    'fechaOrdinaria'=>$fechaOrdinaria,
                                                                    'fechaExtraordinaria'=>$fechaExtraordinaria,
                                                                    'observacion'=>$obs
                                                    );

                                            $adicionadoRecibo= $this->adicionarReciboPago($datosRegistro);
                                            if($adicionadoRecibo){
                                                $adicionadoRefMatricula= $this->adicionarReferenciaMatricula($datosRegistro);
                                                if($anio==$this->ano && $periodo==$this->periodo){
                                                        $adicionadoRefSeguro= $this->adicionarReferenciaSeguro($datosRegistro);

                                                }
                                            }              
                                            //verificamos que se halla realizado la insercion
                                            if($adicionadoRecibo && $adicionadoRefMatricula){
                                                    $mensaje="Recibo generado con exito";

                                                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                                                      'evento'=>'83',
                                                                                      'descripcion'=>'Registro generacion recibo soporte -'.$codEstudiante,
                                                                                      'registro'=>'codEstudiante->'.$codEstudiante.', valorOrdinario->'.$valorOrdinario.', valorExtraordinario->'.$valorExtraordinario.', anioRecibo->'.$anio.', perRecibo->'.$periodo.', cuota->'.$cuota.', fechaOrdinaria->'.$fechaOrdinaria.', fechaExtraordinaria->'.$fechaExtraordinaria.', observacion->'.$obs,
                                                                                      'afectado'=>$codEstudiante);

                                                    $this->procedimientos->registrarEvento($variablesRegistro);

                                                    echo "<script>alert ('".$mensaje."');</script>";
                                                   ?>
                                                    <table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                                                            <tr class=texto_elegante >
                                                                <td colspan='2'>
                                                                    <b>::::..</b>  Generar Recibo de Pago
                                                                    <hr class=hr_subtitulo>
                                                                    <br><br>
                                                                </td>
                                                            </tr>	
                                                            <tr>
                                                                <td><? $this->mostrarEnlaceRetorno();?></td>
                                                                <td><? $this->mostrarEnlaceRecibosEstudiante($codEstudiante);?></td>

                                                            </tr>
                                                    </table>        
                                                   <?
                                                   
                                            }else{
                                                   $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                   $variable="pagina=admin_generarReciboPago";
                                                   $variable.="&opcion=nuevo";
                                                   foreach ($_REQUEST as $key => $value) {
                                                       if($key!='opcion' && $key!='pagina'){
                                                            $variable.="&".$key."=".$value;
                                                       }

                                                   }
                                                   $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                                   $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

                                            }



                                                    }else{
                                                       $mensaje="Fechas no válidas";
                                                       $variablesRegistro=array('usuario'=>$this->usuario,
                                                                                         'evento'=>'83',
                                                                                         'descripcion'=>'Error al generar recibo soporte -'.$codEstudiante.' fechas no validas',
                                                                                        'registro'=>'codEstudiante->'.$codEstudiante.', valorOrdinario->'.$valorOrdinario.', valorExtraordinario->'.$valorExtraordinario.', periodoRecibo->'.$periodoRecibo.', cuota->'.$cuota.', fechaOrdinaria->'.$fechaOrdinaria.', fechaExtraordinaria->'.$fechaExtraordinaria.', observacion->'.(isset($obs)?$obs:''),
                                                                                        'afectado'=>$codEstudiante);

                                                      $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                      $variable="pagina=admin_generarReciboPago";
                                                      $variable.="&opcion=nuevo";
                                                      $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                                      $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                                                   }


                                                }else{
                                                       $mensaje="Valor de la cuota no válido";
                                                       $variablesRegistro=array('usuario'=>$this->usuario,
                                                                                    'evento'=>'83',
                                                                                    'descripcion'=>'Error al generar recibo soporte -'.$codEstudiante.' valor cuota no valido',
                                                                                    'registro'=>'codEstudiante->'.$codEstudiante.', valorOrdinario->'.$valorOrdinario.', valorExtraordinario->'.$valorExtraordinario.', periodoRecibo->'.$periodoRecibo.',  cuota->'.$cuota.', fechaOrdinaria->'.$fechaOrdinaria.', fechaExtraordinaria->'.$fechaExtraordinaria.', observacion->'.$obs,
                                                                                    'afectado'=>$codEstudiante);

                                                      $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                      $variable="pagina=admin_generarReciboPago";
                                                      $variable.="&opcion=nuevo";
                                                      $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                                      $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                                                   }

                                        }else{
                                            $mensaje="Valor de período no válido";
                                            $variablesRegistro=array('usuario'=>$this->usuario,
                                                                        'evento'=>'83',
                                                                        'descripcion'=>'Error al generar recibo soporte -'.$codEstudiante.' valor de período no valido',
                                                                        'registro'=>'codEstudiante->'.$codEstudiante.', valorOrdinario->'.$valorOrdinario.', valorExtraordinario->'.$valorExtraordinario.', periodoRecibo->'.$periodoRecibo.',  cuota->'.$cuota.', fechaOrdinaria->'.$fechaOrdinaria.', fechaExtraordinaria->'.$fechaExtraordinaria.', observacion->'.$obs,
                                                                        'afectado'=>$codEstudiante);

                                           $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                           $variable="pagina=admin_generarReciboPago";
                                           $variable.="&opcion=nuevo";
                                           $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                           $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                                        }

                            }else{
                                $mensaje="Valores ordinario o extraordinario no válidos";
                                $variablesRegistro=array('usuario'=>$this->usuario,
                                                            'evento'=>'83',
                                                            'descripcion'=>'Error al generar recibo soporte -'.$codEstudiante.' valor ordinario o extraordinario no validos',
                                                            'registro'=>'codEstudiante->'.$codEstudiante.', valorOrdinario->'.$valorOrdinario.', valorExtraordinario->'.$valorExtraordinario.', periodoRecibo->'.$periodoRecibo.', cuota->'.$cuota.', fechaOrdinaria->'.$fechaOrdinaria.', fechaExtraordinaria->'.$fechaExtraordinaria.', observacion->'.$obs,
                                                            'afectado'=>$codEstudiante);

                               $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                               $variable="pagina=admin_generarReciboPago";
                               $variable.="&opcion=nuevo";
                               $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                               $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                            }

                    }else{
                        $mensaje="No existe estudiante con el código ingresado";
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                        'evento'=>'83',
                                                        'descripcion'=>'Error al generar recibo soporte -'.$codEstudiante.' codigo estudiante no valido',
                                                        'registro'=>'codEstudiante->'.$codEstudiante.', valorOrdinario->'.$valorOrdinario.', valorExtraordinario->'.$valorExtraordinario.', periodoRecibo->'.$periodoRecibo.', cuota->'.$cuota.', fechaOrdinaria->'.$fechaOrdinaria.', fechaExtraordinaria->'.$fechaExtraordinaria.', observacion->'.$obs,
                                                        'afectado'=>$codEstudiante);

                       $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                       $variable="pagina=admin_generarReciboPago";
                       $variable.="&opcion=nuevo";
                       $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                       $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                    }
                }else{
                    $mensaje="Por favor ingrese completamente los campos requeridos";
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                    'evento'=>'83',
                                                    'descripcion'=>'Error al generar recibo soporte -'.$codEstudiante.' campos requeridos',
                                                    'registro'=>'codEstudiante->'.$codEstudiante.', valorOrdinario->'.$valorOrdinario.', valorExtraordinario->'.$valorExtraordinario.', periodoRecibo->'.$periodoRecibo.', cuota->'.$cuota.', fechaOrdinaria->'.$fechaOrdinaria.', fechaExtraordinaria->'.$fechaExtraordinaria.', observacion->'.$obs,
                                                    'afectado'=>$codEstudiante);

                   $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                   $variable="pagina=admin_generarReciboPago";
                   $variable.="&opcion=nuevo";
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
    function adicionarReciboPago($datos) {
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_recibo_pago",$datos); 
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
               
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
    /**
     * Función para registrar una referencia de matricula de un recibo de pago
     * @param array $datos
     * @return int
     */
    function adicionarReferenciaMatricula($datos) {
        
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_referencia_matricula",$datos); 
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
               
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
    /**
     * Función para registrar una referencia de Seguro de un recibo de pago
     * @param array $datos
     * @return int
     */
    function adicionarReferenciaSeguro($datos) {
        
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_referencia_seguro",$datos); 
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
               
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
       
     /**
     * Funcion que consulta los datos de un proyecto curricular
     * @param 
     *  
     */

    
    public function consultarDatosProyecto($codProyecto,$nivel='') {
        $datos=array('codProyecto'=>$codProyecto,
                        'nivel'=>$nivel);
        $cadena_sql = $this->sql->cadena_sql("datos_proyectos", $datos);
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
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
    function validarFechasRecibo($fecha_ordinaria,$fecha_extraordinaria){
        $valida='';
        //$fechasIguales=  $this->validarFechasIguales($fecha_ordinaria,$fecha_extraordinaria);
        $hoy = date('Y/m/d');
        $fecha_hoy = str_replace('/', '', $hoy);
        $fecha_uno = str_replace('/', '', $fecha_ordinaria);
        $fecha_dos = str_replace('/', '', $fecha_extraordinaria);
        $valida_cadena1=  $this->validarDatoFecha($fecha_ordinaria);
        $valida_cadena2=  $this->validarDatoFecha($fecha_extraordinaria);
        
        if($fecha_uno>$fecha_dos || $fecha_uno<$fecha_hoy || $fecha_dos<$fecha_hoy || $valida_cadena1==false || $valida_cadena2==false ){
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

            $variable2="pagina=admin_generarReciboPago";
            $variable2.="&opcion=nuevo";

            $variable2=$this->cripto->codificar_url($variable2,$this->configuracion);
            $enlace_aprobar=$indice.$variable2;
            echo "<br><table><tr><td ><a href='".$enlace_aprobar."'>Volver</a></td></tr></table>";
    }
    
    /**
     * Función para mostrar el enlace de retorno
     */
    function mostrarEnlaceRecibosEstudiante($codEstudiante){
            $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";

            $variable2="pagina=admin_consultarHistoricoRecibos";
            $variable2.="&opcion=consultarEstudiante";
            $variable2.="&datoBusqueda=".$codEstudiante;
            $variable2.="&tipoBusqueda=codigo";

            $variable2=$this->cripto->codificar_url($variable2,$this->configuracion);
            $enlace_aprobar=$indice.$variable2;
            echo "<br><table><tr><td ><a href='".$enlace_aprobar."'>Ver recibos estudiante</a></td></tr></table>";
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
}

?>