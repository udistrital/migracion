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
class funcion_registroAceptarTerminosMatricula extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $ano;
    private $periodo;
    private $terminos;
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

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"estudiante");
       
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"recibos");

        //Datos de sesion
        $this->formulario="registro_aceptarTerminosMatricula";
        $this->bloque="registro_aceptarTerminosMatricula";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
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
     * Funcion que valida los datos para inscribir el registro en la tabla de solicitudes
     * @param <array> $_REQUEST 
     * 
     */
    function validarRegistroAceptacion()
    {
        $mensaje="";
        $adicionado='';
        $anio=(isset($_REQUEST['anio_recibo'])?$_REQUEST['anio_recibo']:'');
        $periodo=(isset($_REQUEST['per_recibo'])?$_REQUEST['per_recibo']:'');
        $datosRegistro=array(   'anio'=>$anio,
                                'periodo'=>$periodo,
                                'usuario'=>$this->usuario,
                                'tipo_usuario'=>51,
                                'tipo_terminos'=>1,
                                'aceptacion'=>1,
                                'fecha'=>  date('Y-m-d H:i:s'),
                                'estado'=>1,
            );
        
        if($this->usuario){
             $adicionado= $this->registrarAceptacionTerminosMatricula($datosRegistro);
        }else{
            $mensaje="Usuario invalido";
        }
                //verificamos que se halla realizado la insercion
                if($adicionado){
                        $mensaje="Aceptación de términos y condiciones de matrícula registrada con exito.";

                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'68',
                                                          'descripcion'=>'Registro aceptación terminos de matricula-'.$this->usuario,
                                                          'registro'=>"estudiante-> ".$this->usuario.", anio->".$datosRegistro['anio'].", periodo->".$datosRegistro['periodo'],
                                                          'afectado'=>$this->usuario);

                       
                }else{
                    $mensaje="No se pudo registrar la aceptación de terminos.";
                       
                }
               $pagina=$this->configuracion["host"]."/weboffice/index.php?";
                $variable="pagina=adminPago";
                $variable.="&opcion=reciboActual";
                $variable.="&usuario=".$this->usuario;
                $variable.="&tipoUser=51";

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
     * Funcion que genera el mensaje de solicitud de confirmacion
     *
     */
    function solicitarConfirmacion() {
        $this->terminos = $this->consultarDatosTerminosYCondiciones(1);
        ?>
        <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
        <?
          $this->celdaMensajes();
          $this->formularioConfirmacion();
        ?>
        </table>
        <?
    }

      /**
     * Funcion que genera la celda donde se colocan mensajes de confirmacion de aceptar los terminos y condiciones
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
     * Funcion que genera el formulario para confirmar o cancelar la cancelacion
     */
    function formularioConfirmacion() {
      ?>
          <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <tr class="texto_subtitulo">
              <td align="center">
                  <?
                    $opciones=$this->variablesCancelar();
                    $enlace=$this->encriptarEnlace($opciones,"confirmarAceptaTerminos");
                    $array=array('tipo'=>'image','nombre'=>'aceptar','icono'=>'clean.png','texto'=>'Si','opciones'=>$enlace);
                    $this->botonEnlace($array)
                  ?>
              </td>
              <td align="center">
                  <?
                    $enlace=$this->encriptarEnlace($opciones,"cancelarAceptaTerminos");
                    $array=array('tipo'=>'image','nombre'=>'cancelar','icono'=>'x.png','texto'=>'No','opciones'=>$enlace);
                    $this->botonEnlace($array)
                  ?>
              </td>
            </tr>
          </form>
      <?
    }


    /**
     * Funcion que genera los mensajes de confirmacion de cancelacion
     *
     */
    function mensajesConfirmacion() {
        ?>
        <b><? echo $this->terminos[0]['tpc_nombre']; ?><br></b>
        <br>
        <br><? echo $this->terminos[0]['tpc_contenido']; ?><?
    }

     /**
     * Funcion que genera las variables para los enlaces de confirmacion y cancelacion
     */
    function variablesCancelar() {
        unset ($_REQUEST['pagina']);
        $variable="pagina=".$this->formulario;
        $_REQUEST['action']=$this->bloque;
        foreach ($_REQUEST as $key => $value)
        {
            $variable.="&".$key."=".$value;
        }
        return $variable;
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
     * Funcion que permite retornar al cancelar la confirmacion
     */
    function cancelarConfirmacion() {
        $this->datosRegistro=$_REQUEST;
        $this->enlaceNoCancelar();
    }

    /**
     * Esta funcion se utiliza para retornar cuando se cancela la cancelacion del espacio academico
     * @param <string> $mensaje Mensaje que presenta al no poder realizar la cancelacion
     */
    function enlaceNoCancelar($mensaje='')
    {
        if ($mensaje!='')
        {
            echo "<script>alert ('$mensaje');</script>";
        }
        else
            {

            }
        $pagina=$this->configuracion["host"]."/weboffice/index.php?";
        $variable="pagina=adminPago";
        $variable.="&opcion=reciboActual";
        foreach ($_REQUEST as $key => $value)
        {
            if($key!='opcion' && $key!='action' && $key!='pagina'){
                $variable.="&".$key."=".$value;
            }
        }

        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        echo "<script>location.replace('".$pagina.$variable."')</script>";
    }
    
    
    /**
     * Función para registrar la aceptacion de terminos
     * @param int $idSolicitud
     * @return int
     */  
    function registrarAceptacionTerminosMatricula($datosRegistro) {
        $cadena_sql=$this->sql->cadena_sql("registrarAceptacion",$datosRegistro);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);
        
    }
  
    function consultarDatosTerminosYCondiciones($id){
            $cadena_sql = $this->sql->cadena_sql("terminosYCondiciones", $id);
            return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
    }
  
    function verificarYaAcepto(){
        $anio=(isset($_REQUEST['anio_recibo'])?$_REQUEST['anio_recibo']:'');
        $anio=2014;
        $periodo=(isset($_REQUEST['per_recibo'])?$_REQUEST['per_recibo']:'');
        $periodo=1;
        $datosRegistro=array(   'anio'=>$anio,
                                'periodo'=>$periodo,
                                'usuario'=>$this->usuario,
                                'tipo_usuario'=>51,
                                'tipo_terminos'=>1,
            );
        
        $aceptacion = $this->consultarAceptacionDeTerminos($datosRegistro);
        if($aceptacion[0]['con_aceptacion']==1){
            echo "<br>mostrar enlace recibo";
        }else{
                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = "pagina=registro_aceptarTerminosMatricula";
                    $variable.="&opcion=confirmacion";
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }
    
    function consultarAceptacionDeTerminos($datosRegistro){
        $cadena_sql = $this->sql->cadena_sql("AceptacionDeTerminos", $datosRegistro);
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
    }
}

?>