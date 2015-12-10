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
class funcion_registroGenerarReciboDerechosPecuniarios extends funcionGeneral {
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
        if($this->nivel==51 || $this->nivel==52 ){
            $this->accesoOracle = $this->conectarDB($configuracion, "estudiante");
        }elseif($this->nivel==121 ){
            $this->accesoOracle = $this->conectarDB($configuracion, "egresado");
        }else{
                echo "NO TIENE PERMISOS PARA ESTE MODULO";
                exit;
            }
        
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
        if($this->nivel==51 || $this->nivel==52 || $this->nivel==121){                   
                
                $codEstudiante=  (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'');
                $tipoRecibo = (isset($_REQUEST['tipoRecibo'])?$_REQUEST['tipoRecibo']:'');
                if($codEstudiante && $tipoRecibo ){
                    $datosEstudiante = $this->consultarEstudiante($codEstudiante);
                    if(is_array($datosEstudiante)){
                        $codProyecto=$datosEstudiante[0]['COD_PROYECTO'];
                        $valorReferencia=  $this->obtenerValorDerecho($tipoRecibo);
                        $valorOrdinario= 0;
                        $valorExtraordinario=$valorOrdinario;
                        $fechaOrdinaria=  $this->obtenerFechaPago();
                        $fechaExtraordinaria=$fechaOrdinaria;
                        $obs="";
                        $validaCantidad = $this->validarCantidadRecibos($tipoRecibo,$codEstudiante);
                        if(is_numeric($valorReferencia) && $valorReferencia){
                            if($validaCantidad=='ok'){
                                            $secuencia =  $this->consultarSecuencia();
                                            $datosRegistro=array('anio'=>$this->ano,
                                                                    'periodo'=>  $this->periodo,
                                                                    'codEstudiante'=>$codEstudiante,
                                                                    'codProyecto'=>$codProyecto,
                                                                    'secuencia'=>$secuencia,
                                                                    'codConcepto'=>$tipoRecibo,
                                                                    'valorReferencia'=>$valorReferencia,
                                                                    'valorOrdinario'=>$valorOrdinario,
                                                                    'valorExtraordinario'=>$valorExtraordinario,
                                                                    'anioRecibo'=>$this->ano,
                                                                    'perRecibo'=>$this->periodo,
                                                                    'cuota'=>1,
                                                                    'fechaOrdinaria'=>$fechaOrdinaria,
                                                                    'fechaExtraordinaria'=>$fechaExtraordinaria,
                                                                    'observacion'=>$obs
                                                    );
                                            $adicionadoRecibo= $this->adicionarReciboPago($datosRegistro);
                                            if($adicionadoRecibo){
                                                //$adicionadoRefMatricula= $this->adicionarReferenciaMatricula($datosRegistro);
                                                $adicionadoRefDerechoPecuniario= $this->adicionar_referencia_derecho_pecuniario($datosRegistro);
                                            }
                                            //verificamos que se halla realizado la insercion
                                            if($adicionadoRecibo /*&& $adicionadoRefMatricula */&& $adicionadoRefDerechoPecuniario){
                                                    $mensaje="Recibo generado con exito";
                                                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                    $variable="pagina=admin_reciboDerechosPecuniarios";
                                                    $variable.="&opcion=nuevo";
                                                    $variable.="&datoBusqueda=".$codEstudiante;
                                                    $variable.="&tipoBusqueda=codigo";
                                                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                                   
                                                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                                                      'evento'=>'88',
                                                                                      'descripcion'=>'Registro generacion recibo Derechos pecuniarios -'.$codEstudiante,
                                                                                      'registro'=>'codEstudiante->'.$codEstudiante.', valorReferencia->'.$valorReferencia.', anioRecibo->'.  $this->ano.', perRecibo->'.  $this->periodo.', fechaOrdinaria->'.$fechaOrdinaria.', observacion->'.$obs,
                                                                                      'afectado'=>$codEstudiante);

                                                    $this->procedimientos->registrarEvento($variablesRegistro);

                                                    $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);                                                   
                                            }else{
                                                   $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                   $variable="pagina=admin_reciboDerechosPecuniarios";
                                                   $variable.="&opcion=nuevo";
                                                   $variable.="&datoBusqueda=".$codEstudiante;
                                                    $variable.="&tipoBusqueda=codigo";
                                                    $mensaje="Error al generar recibo";
                                                   $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                                                      'evento'=>'88',
                                                                                      'descripcion'=>'Error al generar recibo Derechos pecuniarios -'.$codEstudiante,
                                                                                      'registro'=>'codEstudiante->'.$codEstudiante.', valorReferencia->'.$valorReferencia.', anioRecibo->'.  $this->ano.', perRecibo->'.  $this->periodo.', fechaOrdinaria->'.$fechaOrdinaria.', observacion->'.$obs,
                                                                                      'afectado'=>$codEstudiante);

                                                   $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

                                            }
                                }else{
                                    $mensaje="Error al generar recibo. ".$validaCantidad;
                                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                                'evento'=>'88',
                                                                'descripcion'=>'Error al generar recibo derechos pecuniarios -'.$codEstudiante.' valor de recibo no valido',
                                                                'registro'=>'codEstudiante->'.$codEstudiante.', valorReferencia->'.$valorReferencia.', anioRecibo->'.  $this->ano.', perRecibo->'.  $this->periodo.', fechaOrdinaria->'.$fechaOrdinaria.', observacion->'.$obs,
                                                                'afectado'=>$codEstudiante);

                                   $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                   $variable="pagina=admin_reciboDerechosPecuniarios";
                                   $variable.="&opcion=nuevo";
                                   $variable.="&datoBusqueda=".$codEstudiante;
                                    $variable.="&tipoBusqueda=codigo";
                                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                   $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                                }
                            }else{
                                $mensaje="Valor del recibo no válido";
                                $variablesRegistro=array('usuario'=>$this->usuario,
                                                            'evento'=>'88',
                                                            'descripcion'=>'Error al generar recibo derechos pecuniarios -'.$codEstudiante.' valor de recibo no valido',
                                                            'registro'=>'codEstudiante->'.$codEstudiante.', valorReferencia->'.$valorReferencia.', anioRecibo->'.  $this->ano.', perRecibo->'.  $this->periodo.', fechaOrdinaria->'.$fechaOrdinaria.', observacion->'.$obs,
                                                            'afectado'=>$codEstudiante);

                               $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                               $variable="pagina=admin_reciboDerechosPecuniarios";
                               $variable.="&opcion=nuevo";
                               $variable.="&datoBusqueda=".$codEstudiante;
                                $variable.="&tipoBusqueda=codigo";
                                $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                               $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                            }

                    }else{
                        $mensaje="No existe estudiante con el código ingresado";
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                        'evento'=>'88',
                                                        'descripcion'=>'Error al generar recibo derechos pecuniarios  -'.$codEstudiante.' codigo estudiante no valido',
                                                        'registro'=>'codEstudiante->'.$codEstudiante.', valorReferencia->'.$valorReferencia.', anioRecibo->'.  $this->ano.', perRecibo->'.  $this->periodo.', fechaOrdinaria->'.$fechaOrdinaria.', observacion->'.$obs,
                                                        'afectado'=>$codEstudiante);

                       $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                       $variable="pagina=admin_reciboDerechosPecuniarios";
                       $variable.="&opcion=nuevo";
                       $variable.="&datoBusqueda=".$codEstudiante;
                        $variable.="&tipoBusqueda=codigo";
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                       $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                    }
                }else{
                    $mensaje="Código de estudiante o tipo de recibo no valido";
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                    'evento'=>'88',
                                                    'descripcion'=>'Error al generar recibo derechos pecuniarios -'.$codEstudiante.' ',
                                                    'registro'=>'codEstudiante->'.$codEstudiante.', valorReferencia->'.$valorReferencia.', anioRecibo->'.  $this->ano.', perRecibo->'.  $this->periodo.', fechaOrdinaria->'.$fechaOrdinaria.', observacion->'.$obs,
                                                    'afectado'=>$codEstudiante);

                   $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                   $variable="pagina=admin_reciboDerechosPecuniarios";
                   $variable.="&opcion=nuevo";
                   $variable.="&datoBusqueda=".$codEstudiante;
                    $variable.="&tipoBusqueda=codigo";
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                   $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                }
        }else{
                                                    ?>
                                                        <table class="contenidotabla centrar">
                                                          <tr>
                                                            <td class="cuadro_brownOscuro centrar">
                                                                <?echo "El perfil no tiene permisos para este módulo";?>
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
     * Función para registrar una referencia de matricula de un recibo de pago.
     *  No se utiliza ya que los recibos de derechos pecuniarios no llevan ref de matricula
     * @param array $datos
     * @return int
     */
    function adicionarReferenciaMatricula($datos) {
        
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_referencia_matricula",$datos); 
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
               
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
    function adicionar_referencia_derecho_pecuniario($datos) {
        
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_referencia_derecho_pecuniario",$datos); 
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
               
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
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
    function validarFechasRecibo($fecha){
        
        include_once($this->configuracion["raiz_documento"].$this->configuracion["bloques"]."/recibos/registro_generarReciboDerechosPecuniarios".$this->configuracion["clases"]."/festivos.class.php");

        $this->dias_festivos = new festivos();
        $valida='';
        $hoy = date('Y-m-d');
        $fecha_hoy = str_replace('-', '', $hoy);
        $fecha_uno = str_replace('-', '', $fecha);
        //verifica que sea un dato valido de fecha
        $valida_cadena1=  $this->validarDatoFecha($fecha);
        //separa dia mes ano
        $valores = explode('-',$fecha);
        $ano=$valores[0];
        $mes=$valores[1];
        $dia=$valores[2];
        //informa que dia de la semana es 
        $diaFecha = $this->diaSemana($ano,$mes,$dia);
        //verifica si es festivo
        $festivo = $this->consultarFestivo($fecha);
        $festivo=$this->dias_festivos->esFestivo($dia,$mes);
        if($fecha_uno<=$fecha_hoy || $valida_cadena1==false || $diaFecha==0 || $diaFecha==6 || $festivo===true){
            $valida="Fechas no validas";

        }else{
            $valida='ok';
        }
        return $valida;
    }
    
    function diaSemana($ano,$mes,$dia)
    {
        // 0->domingo     | 6->sabado
        $dia= date("w",mktime(0, 0, 0, $mes, $dia, $ano));
            return $dia;
    }
    
    function consultarFestivo($fecha) {
        $cadena_sql = $this->sql->cadena_sql("consultar_festivo", $fecha);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(is_array($resultado)){
            return $resultado[0][0];
        }else{
            return '';
        }
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

            $variable2="pagina=admin_reciboDerechosPecuniarios";
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
    function obtenerValorDerecho($tipoRecibo){
        if($tipoRecibo){
            switch ($tipoRecibo) {
                case 5:
                    $valor=  $this->consultarValorCertificadoNotas();
                    break;
                
                case 6:
                    $valor=  $this->consultarValorConstanciaEstudios();
                    break;
                
                case 8:
                    $valor=  $this->consultarValorDerechosGrado();
                    break;
                
                case 9:
                    $valor=  $this->consultarValorDuplicadoDiploma();
                    break;
                
                case 10:
                    $valor=  $this->consultarValorDuplicadoCarnet();
                    break;

                case 13:
                    $valor=  $this->consultarValorVacacional();
                    break;

                default:
                    $valor=  0;
                    
                    break;
            }
        }
        
        return $valor;
    }
    
    function consultarValorCertificadoNotas() {
        $cadena_sql = $this->sql->cadena_sql("consultar_valor_certificado_notas", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
    }

    function consultarValorConstanciaEstudios() {
        $cadena_sql = $this->sql->cadena_sql("consultar_valor_constancia_estudios", '');
         $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
    }

    function consultarValorDerechosGrado() {
        $cadena_sql = $this->sql->cadena_sql("consultar_valor_derechos_grado", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
    }

    function consultarValorDuplicadoCarnet() {
        $cadena_sql = $this->sql->cadena_sql("consultar_valor_duplicado_carnet", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
    }

    function consultarValorVacacional() {
        $cadena_sql = $this->sql->cadena_sql("consultar_valor_curso_vacacional", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
    }

    function consultarValorDuplicadoDiploma() {
        $cadena_sql = $this->sql->cadena_sql("consultar_valor_duplicado_diploma", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
    }
    
    function consultarSecuencia() {
        $cadena_sql = $this->sql->cadena_sql("secuencia", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
    }
    
    function obtenerFechaPago(){
        $fecha = date('Y-m-d');
        //agrega tres meses a la fecha actual
        $nuevafecha = strtotime ( '+89 day' , strtotime ( $fecha ) ) ;
        //establece 20 de diciembre como la fecha maxima
        $ultimo_dia= mktime(0, 0, 0, 12, 14, date('Y'));
        //reemplaza la fecha de pago si es mayor al 20 de diciembre
        if($nuevafecha>$ultimo_dia)
        {$nuevafecha=$ultimo_dia;}
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        //valida si la fecha es habil para pago
        $valida='-';
        while ($valida!='ok') {
            $nuevafecha = strtotime ( '+1 day' , strtotime ( $nuevafecha ) ) ;
            $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
            $valida = $this->validarFechasRecibo($nuevafecha);
        }
        return $nuevafecha;
    }

    function validarCantidadRecibos($tipo,$codEstudiante){
        $registros = $this->consultarCantidadRecibosPorTipo($tipo,$codEstudiante);
        $total =count($registros);
        if(is_array($registros)&&$total>=1){
            $mensaje='La cantidad máxima permitida es de 1 recibo por período y tipo de recibo.';
        }else{
            $mensaje='ok';
        }
        return $mensaje;
    }
    
    function consultarCantidadRecibosPorTipo($tipo,$codEstudiante) {
        $datos=array('tipoRecibo'=>$tipo,
                        'codEstudiante'=>  $codEstudiante);
        $cadena_sql = $this->sql->cadena_sql("cantidad_recibos", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
}

?>