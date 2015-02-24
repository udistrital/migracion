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
class funcion_registroCambiarEstadoReciboPago extends funcionGeneral {
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
     * Función para cambiar estado de un recibo
     */
    function cambiarEstado(){
        $codEstudiante = (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'');
         if($this->nivel==80 ){
             $operacion =(isset($_REQUEST['operacion'])?$_REQUEST['operacion']:'');
                $datos=array('anio'=>(isset($_REQUEST['anioRecibo'])?$_REQUEST['anioRecibo']:''),
                              'codEstudiante'=>$codEstudiante,
                              'periodo'=>(isset($_REQUEST['periodoRecibo'])?$_REQUEST['periodoRecibo']:''),
                              'secuencia'=>(isset($_REQUEST['secuencia'])?$_REQUEST['secuencia']:'')
                    );
                
                if(is_numeric($datos['anio']) && is_numeric($datos['secuencia']) ){
                    $recibo = $this->consultaRecibo($datos);
                    if(is_array($recibo) && $operacion && (($operacion=='inactivar' && $recibo[0]['REALIZO_PAGO']=='NO' ) || ($operacion=='activar'))){
                            if($operacion=='inactivar'){
                                $datos['nvo_estado']='I';
                            }elseif($operacion=='activar'){
                                $datos['nvo_estado']='A';
                            }
                            $modificada = $this->actualizarEstadoRecibo($datos);
                
                            if($modificada){
                                $mensaje="Estado del recibo actualizado con exito.";
                                $variablesRegistro=array('usuario'=>$this->usuario,
                                                                      'evento'=>'84',
                                                                      'descripcion'=>'Cambio de estado del recibo - soporte '.$datos['codEstudiante'],
                                                                      'registro'=>"año-> ".$datos['anio'].", secuencia->".$datos['secuencia'].", operacion->".$operacion,
                                                                      'afectado'=>$datos['codEstudiante']);
                            }else{
                                $mensaje="Error al cambiar estado del recibo.";
                                $variablesRegistro=array('usuario'=>$this->usuario,
                                                                  'evento'=>'84',
                                                                  'descripcion'=>'Error al cambiar estado del recibo - soporte',
                                                                  'registro'=>"año-> ".$datos['anio'].", secuencia->".$datos['secuencia'].", operacion->".$operacion,
                                                                  'afectado'=>$datos['codEstudiante']);


                            }
                        }else{
                            if(!is_array($recibo)){$mensaje="Recibo no valido ";}
                            if(!$operacion){$mensaje="Recibo no valido ";}
                            if(($operacion=='inactivar' && $recibo[0]['REALIZO_PAGO']=='SI' )){$mensaje="No se puede inactivar un recibo que se encuentra pagado. ";}
                            
                            $variablesRegistro=array('usuario'=>$this->usuario,
                                                            'evento'=>'84',
                                                            'descripcion'=>'Error al cambiar estado del recibo - soporte '.$mensaje,
                                                            'registro'=>"año-> ".$datos['anio'].", secuencia->".$datos['secuencia'].", operacion->".$operacion,
                                                            'afectado'=>$datos['codEstudiante']);

                        }
                    
                }else{
                    
                    $mensaje="Recibo no valido ";
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                    'evento'=>'84',
                                                    'descripcion'=>'Error al cambiar estado del recibo - soporte '.$mensaje,
                                                    'registro'=>"año-> ".$datos['anio'].", secuencia->".$datos['secuencia'].", operacion->".$operacion,
                                                    'afectado'=>$datos['codEstudiante']);

                   
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
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_cambiarEstadoRecibo";
        $variable.="&opcion=consultar";
        $variable.="&datoBusqueda=".$codEstudiante;
        $variable.="&tipoBusqueda=codigo";

        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
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
     * Función para mostrar el enlace de retorno
     */
    function mostrarEnlaceRetorno(){
            $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";

            $variable2="pagina=admin_cambiarEstadoRecibo";
            $variable2.="&opcion=consultarEstudiante";

            $variable2=$this->cripto->codificar_url($variable2,$this->configuracion);
            $enlace_aprobar=$indice.$variable2;
            echo "<br><table><tr><td ><a href='".$enlace_aprobar."'>Volver</a></td></tr></table>";
    }
    
    
    
     /**
     * busca los datos de los recibos de un estudiante en la base de datos
     * @param type $codEstudiante
     * @return type 
     */
    function consultaRecibo($datos) {
          $cadena_sql = $this->sql->cadena_sql("consultar_recibo", $datos);
          return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    /**
     * Función para actualizar estado de recibo
     * @param int $idSolicitud
     * @return int
     */  
    function actualizarEstadoRecibo($datos) {
        
        $cadena_sql=$this->sql->cadena_sql("actualizar_estado_recibo",$datos);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
     
}

?>