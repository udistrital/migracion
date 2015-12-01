
<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");

//@ Esta clase presenta el horario registrado para el estudiante y los enlaces para realizar inscripcion por busqeda
//@ Tambien se puede realizar cambio de grupo y cancelacion si hay permisos para inscripciones

class funcion_adminGenerarReciboDerechosPecuniarios extends funcionGeneral {

    private $configuracion;
    private $parametros;

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");

        $this->configuracion = $configuracion;
        $this->fechas = new validar_fechas();
        $this->cripto = new encriptar();
        $this->procedimientos = new procedimientos();
        $this->html = new html();

        //$this->tema = $tema;
        $this->sql = new sql_adminGenerarReciboDerechosPecuniarios($configuracion);
        $this->log_us = new log();
        $this->parametros = array();
        $this->formulario = "admin_reciboDerechosPecuniarios";
        $this->bloque = "recibos/admin_reciboDerechosPecuniarios";

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");

        //Conexion sga
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];
        $this->usuarioSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        //Conexion Oracle
        
        if($this->nivel==51 || $this->nivel==52 ){
            $this->accesoOracle = $this->conectarDB($configuracion, "estudiante");
        }elseif($this->nivel==121 ){
            $this->accesoOracle = $this->conectarDB($configuracion, "egresado");
        }else{
                echo "NO TIENE PERMISOS PARA ESTE MODULO";
                exit;
            }
    }

  
    /**
     * Funvión para mostrar el formulario de un nuevo registro de fechas
     */
    function mostrarFormularioReciboDerechos() {
        $tab = 1;
        $tipoBusqueda = (isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'codigo');
        $datoBusqueda = (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:$this->usuario);;
        if(($tipoBusqueda=='codigo' || $tipoBusqueda=='identificacion') && is_numeric($datoBusqueda)){
                    if($tipoBusqueda=='codigo'){
                        $codEstudiante = $datoBusqueda;
                    }  
                    if($tipoBusqueda=='identificacion'){//esta opcion es para egresado que ingresa por identificación
                        $codEstudiante = $this->consultarCodigoEgresadoPorIdentificacion($datoBusqueda);
                        
                        if(is_array($codEstudiante )){
                                $this->mostrarListadoProyectos($codEstudiante);
                                exit;
                        }
                    }

                    
                }else{
                    if($tipoBusqueda=='codigo'){
                        echo "C&oacute;digo de estudiante no valido";
                    }
                    if($tipoBusqueda=='identificacion'){
                        echo "Identificaci&oacute;n de estudiante no valida";
                    }
                }
            
        if ($this->nivel == 51 || $this->nivel == 52 || $this->nivel == 121) {//perfil estudiantes y egresado
            $this->verificar = "seleccion_valida(" . $this->formulario . ",'tipoRecibo')";
            $datosEstudiante = $this->consultarEstudiante($codEstudiante);
            if($datosEstudiante[0]['COD_ESTADO']=='E'){
                $derechosPecuniarios = $this->consultarTiposDerechosPecuniariosEgresados();
                
            }else{
                $derechosPecuniarios = $this->consultarTiposDerechosPecuniarios();
            }
            if ($derechosPecuniarios) {
                $descripcionDerechoP[0][0] = '';
                $descripcionDerechoP[0][1] = '--';
                foreach ($derechosPecuniarios as $key => $derecho) {
                    $descripcionDerechoP[$key + 1][0] = $derecho['CODIGO'];
                    $descripcionDerechoP[$key + 1][1] = $derecho['DESCRIPCION'];
                }
            } else {
                $descripcionDerechoP = '';
                $lista_derechos = '';
            }
            ?>

            <link rel='stylesheet' type='text/css' media='all' href='<? echo $this->configuracion['host'] . $this->configuracion['site'] . $this->configuracion['estilo'] . "/calendario/calendar-blue2.css" ?>' title="win2k-cold-1"/>
            <script type='text/javascript' src=<? echo $this->configuracion['host'] . $this->configuracion['site'] . $this->configuracion['estilo'] . "/calendario/calendar.js" ?>></script> 
            <script type='text/javascript' src=<? echo $this->configuracion['host'] . $this->configuracion['site'] . $this->configuracion['estilo'] . "/calendario/calendar-es.js" ?>></script>
            <script type='text/javascript' src=<? echo $this->configuracion['host'] . $this->configuracion['site'] . $this->configuracion['estilo'] . "/calendario/calendar-setup.js" ?>></script>


            <!---->

            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <div id="normal" >

                    <table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                        <tr class=texto_elegante >
                            <td colspan='3'>
                                <b>::::..</b>  Generar Recibo de Derechos Pecuniarios
                                <hr class=hr_subtitulo>
                            </td>
                        </tr>		
                        <tr>    
                            <td width="25%"><span style='color:red;' > * </span>Derecho pecuniario</td>
                            <td width="40%">
                            <?
                            if ($descripcionDerechoP) {
                                $lista_derechos = $this->html->cuadro_lista($descripcionDerechoP, 'tipoRecibo', $this->configuracion, (isset($_REQUEST['periodoRecibo']) ? $_REQUEST['periodoRecibo'] : 0), 0, FALSE, $tab++, 'periodoRecibo', '');
                            }
                            echo $lista_derechos;
                            ?>
                            </td>

                            <td ><? $this->enlaceValidar($codEstudiante); ?></td>
                        </tr>

                    </table>
                    <div id="div_mensaje1" align="center" class="ab_name">
                        Espacios requeridos (*)
                        <br>Recuerde que puede generar 1 recibo por cada tipo de derecho pecuniario. Para generar uno nuevo, debe pagar el anterior.

                    </div>

                </div>

            </form>
            <?
        }else {
            ?>
            <table class="contenidotabla centrar">
                <tr>
                    <td class="cuadro_brownOscuro centrar">
            <? echo "El perfil no tiene permisos para este modulo"; ?>
                    </td>
                </tr>
            </table>
            <?
            $this->mostrarEnlaceRetorno();
            exit;
        }
    }

    /**
     * Función para consultar los derechos pecuniarios de los que se puede generar recibos
     * @param type $datos
     * @return type
     */
    function consultarTiposDerechosPecuniarios() {
        $cadena_sql = $this->sql->cadena_sql("consultar_derechos_pecuniarios", '');
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    function consultarTiposDerechosPecuniariosEgresados() {
        $cadena_sql = $this->sql->cadena_sql("consultar_derechos_pecuniarios_egr", '');
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
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


    
    /**
     * Funcion que muestra el enlace para registrar en la tabla de solicitud
     * @param <int> $identificacion
     * @param <array> $this->configuracion
     * @param $this->accesoOracle
     * @param  $sql
     * Utiliza el metodo ejecutarSQL
     */
    function enlaceRegistrar() {
        ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
        <input type='hidden' name='action' value="<? echo $this->bloque ?>">
        <input type='hidden' name='opcion' value="registrar">
        <input value="Generar" name="aceptar" tabindex='20' type="button" onclick="document.forms['<? echo $this->formulario ?>'].submit()">                              


        <?
    }

    function enlaceValidar($codEstudiante) {
        ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
         <input type='hidden' name='action' value="<? echo $this->bloque ?>">
        <input type='hidden' name='codEstudiante' value="<? echo $codEstudiante; ?>">
        <input type='hidden' name='opcion' value="validar">
        <input value="Aceptar" name="aceptar" tabindex='20' type="button" onclick="if (<? echo $this->verificar; ?>) {document.forms['<? echo $this->formulario ?>'].submit()} else {false}">                              
        <? /* <input value="Validar" name="aceptar" tabindex='20' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">                              */ ?>


        <?
    }

    /**
     * Función para mostrar enlace de retorno
     */
    function mostrarEnlaceRetorno() {
        $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";

        $variable2 = "pagina=admin_reciboDerechosPecuniarios";
        $variable2.="&opcion=nuevo";
        foreach ($_REQUEST as $key => $value) {
                if($key!='opcion' && $key!='action'){
                    $variable2.="&".$key."=".$value;
                }
            }
        $variable2 = $this->cripto->codificar_url($variable2, $this->configuracion);
        $enlace_aprobar = $indice . $variable2;
        echo "<br><table><tr><td ><a href='" . $enlace_aprobar . "'>Volver</a></td></tr></table>";
    }

    /**
     * Función para mostrar los datos del recibo  y que el actor verifique los datos
     */
    function mostrarDatosReciboDerechos() {
        $_REQUEST['tipoRecibo']=(isset($_REQUEST['tipoRecibo'])?$_REQUEST['tipoRecibo']:'');
        $codEstudiante=(isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:$this->usuario);
        $datosEstudiante = $this->consultarEstudiante($codEstudiante);
        if($_REQUEST['tipoRecibo']){
            
            switch ($_REQUEST['tipoRecibo']) {
                case 5:
                    $valor=  $this->consultarValorCertificadoNotas();
                    $descripcion= "Certificado de notas";
                    break;
                
                case 6:
                    $valor=  $this->consultarValorConstanciaEstudios();
                    $descripcion= "Constancia de estudios";
                    break;
                
                case 8:
                    $valor=  $this->consultarValorDerechosGrado();
                    $descripcion= "Derechos de grado";
                    
                    break;
                
                case 9:
                    $valor=  $this->consultarValorDuplicadoDiploma();
                    $descripcion= "Duplicado de diploma";
                    break;
                
                case 10:
                    $valor=  $this->consultarValorDuplicadoCarnet();
                    $descripcion= "Duplicado de carnet";
                    break;

                case 13:
                    $valor=  $this->consultarValorVacacional();
                    $descripcion= "Curso Intersemestral";
                    break;

                default:
                    $valor=  0;
                    $descripcion= "Tipo de recibo no valido.";
                    
                    break;
            }
            
            $validaCantidad = $this->validarCantidadRecibos($_REQUEST['tipoRecibo']);
            if($_REQUEST['tipoRecibo']==8){
                $validaDerechosGrado = $this->validarDerechosDeGrado($datosEstudiante[0]['COD_ESTADO']);
            }else{
                $validaDerechosGrado='ok';//Para cualquier tipo de recibo diferente a Derechos de grado
            }
            
            if($validaCantidad=='ok' && $validaDerechosGrado=='ok'){
                    
        ?>
        <table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                        <tr class=texto_elegante >
                            <td colspan='2'>
                                <b>::::..</b>  Generar Recibo de Derechos Pecuniarios
                                <hr class=hr_subtitulo>
                            </td>
                        </tr>	
        </table>
        <?
        
        if(is_array($datosEstudiante)){
        ?>
        <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <div id="normal" >

                    <table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                        		
                        <tr>    
                            <td colspan="2"><b>Por favor verifique los datos para generar el recibo</b><br><br></td>
                        </tr>
                        <?
                        if($this->nivel==121){
                        ?>
                        <tr>    
                            <td width="30%">Identificación</td>
                            <td> <? echo $this->usuario;?></td>
                        </tr>
                        <?
                        }
                        ?>
                        <tr>    
                            <td width="30%">Código de estudiante</td>
                            <td> <? echo $codEstudiante;?></td>
                        </tr>
                        <tr>    
                            <td width="30%">Datos estudiante</td>
                            <td> <? echo $datosEstudiante[0]['NOMBRE'] ;?><br>
                            <? echo $datosEstudiante[0]['COD_PROYECTO']."-".$datosEstudiante[0]['PROYECTO'];?><br></td>
                        </tr>
                        <tr>    
                            <td width="30%">Concepto recibo</td>
                            <td> <? echo $descripcion;?></td>
                        </tr>
                        <tr>    
                            <td width="30%">Valor ($)</td>
                            <td> <? echo number_format($valor, 0,'','.'); ?></td>
                        </tr>
                                          <tr>    
        
                            <td colspan='2' align='center'>
                                <input type='hidden' name='tipoRecibo' value="<? echo (isset($_REQUEST['tipoRecibo'])?$_REQUEST['tipoRecibo']:''); ?>">
                                <input type='hidden' name='codEstudiante' value="<? echo $codEstudiante; ?>">
                                <?
                                //establece como fecha maxima paara generar recibos el 15 de diciembre
                                $ultimo_dia= date('Ymd',mktime(0, 0, 0, 12, 15, date('Y')));
                                if ((date('Ymd')<=$ultimo_dia))
                                    {
                                    if($valor){
                                        $this->enlaceRegistrar(); 
                                    }
                                }else{?>No se pueden generar más recibos para el año actual<?}
                                ?></td>
                        </tr>

                    </table>
                    <div id="div_mensaje1" align="center" class="ab_name">
                        El recibo se generará con fecha de pago de 90 días calendario posteriores a esta solicitud (o hasta el 15 de Diciembre).

                    </div>

                </div>

            </form>
            <?
        }else{
             ?>
            <table class="contenidotabla centrar">
                <tr>
                    <td class="cuadro_brownOscuro centrar">
            <? echo "No existe estudiante con el código ingresado"; ?>
                    </td>
                </tr>
            </table>
            <?
            $this->mostrarEnlaceRetorno();
            exit;
        }
            }else{
                                    if($validaCantidad!='ok'){
                                        $mensaje="Error al generar recibo. ".$validaCantidad;
                                    }else{
                                        $mensaje=$validaDerechosGrado;
                                    }
                                    
                                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                                'evento'=>'88',
                                                                'descripcion'=>'Error al generar recibo derechos pecuniarios -'.$this->usuario.' valor de recibo no valido',
                                                                'registro'=>'codEstudiante->'.$this->usuario.', valorReferencia->'.$valor,
                                                                'afectado'=>$this->usuario);

                                   $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                   $variable="pagina=admin_reciboDerechosPecuniarios";
                                   $variable.="&opcion=nuevo";
                                   $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                   $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
                                }
       }else{
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=admin_reciboDerechosPecuniarios";
                    $variable.="&opcion=nuevo";
                    $mensaje="Error al generar recibo. Código de derecho pecuniario no valido.";
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                     $variablesRegistro=array('usuario'=>$this->usuario,
                                                       'evento'=>'88',
                                                       'descripcion'=>'Error al generar recibo Derechos pecuniarios -'.$codEstudiante,
                                                       'registro'=>'codEstudiante->'.$codEstudiante.', valorReferencia->'.$valorReferencia.', anioRecibo->'.  $this->ano.', perRecibo->'.  $this->periodo.', fechaOrdinaria->'.$fechaOrdinaria.', observacion->'.$obs,
                                                       'afectado'=>$codEstudiante);

                    $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

       }
       
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
     * Función para validar cantidad máxima de recibos generados
     * @param type $tipo
     * @return string
     */
    function validarCantidadRecibos($tipo){
        $registros = $this->consultarCantidadRecibosPorTipo($tipo);
        //inactivar recibos pecuniarios de vigencia anterior
        
        //inactiva recibos pecuniarios con fechas vencidas
        if(is_array($registros))
        {
        foreach ($registros as $recibo => $concepto) {
            if ($concepto[5]<date('Ymd'))
            {
                $datos=array('tipo'=>$tipo,
                             'codEstudiante'=>$this->usuario,
                             'secuencia'=>$concepto[3],
                             'ano'=>$concepto[0],
                             'periodo'=>$concepto[1]
                    );
                $this->inactivarReciboVencidoPecuniario($datos);
            }
        }
        }
        //volver a consultar recibos activos
        unset($registros);
        $registros = $this->consultarCantidadRecibosPorTipo($tipo);
        $total =count($registros);
        if($total>=1){
            $mensaje='La cantidad máxima permitida es de 1 recibo por período y tipo de recibo.';
        }else{
            $mensaje='ok';
        }
        return $mensaje;
    }
    
    
    function validarDerechosDeGrado($estado){
        if($estado=='E' || $estado=='T' || $estado=='H'){
            $mensaje='ok';
        }else{
            $mensaje='Estado académico no válido para generar este tipo de recibo.';
        }
            
        return $mensaje;
    }
    
    /**
     * Función para consultar la cantidad de recibos de derechos pecuniarios que ha generado un estudiante por tipo
     * @param type $tipo
     * @return type
     */
    function consultarCantidadRecibosPorTipo($tipo) {
        $datos=array('tipoRecibo'=>$tipo,
                        'codEstudiante'=>  $this->usuario);
        $cadena_sql = $this->sql->cadena_sql("cantidad_recibos", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
    /**
     * Función que inactiva los recibos de derechos pecuniarios de un año que ya estan vencidos y no se pagaron
     * @param type $datos
     * @return type
     */
    function inactivarReciboVencidoPecuniario($datos) {
        /*$datos=array('tipoRecibo'=>$tipo,
                        'codEstudiante'=>  $this->usuario);*/
        $cadena_sql = $this->sql->cadena_sql("inactivarReciboVencidoPecuniario", $datos);
        //$resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $resultado;
    }
    
    /**
     * Función para consultar los codigos de estudiantes relacionados a un número de identificación
     * @param int $identificacion
     * @return <array>
     */
    function consultarCodigoEgresadoPorIdentificacion($identificacion){
        $cadena_sql = $this->sql->cadena_sql("consultar_codigo_egresado_por_id", $identificacion);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(count($resultado)>1){
            return $resultado;
        }else{
            return $resultado[0][0];
        }
    }
    
    /**
     * Función para mostrar el listado de proyectos relacionados a un estudiante con el respectivo enlace
     * @param <array> $codigos
     */
    function mostrarListadoProyectos($codigos){
        $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
        if(is_array($codigos)){
            echo "<br>C&oacute;digos relacionados al n&uacute;mero de identificaci&oacute;n:";
            echo "<br><br><table align='center' >";
            foreach ($codigos as $codigo) {
                    $variable="pagina=".$this->formulario;
                    $variable.="&opcion=nuevo";
                    $variable.="&action=".$this->bloque;
                    $variable.="&tipoBusqueda=codigo";
                    $variable.="&datoBusqueda=".$codigo['CODIGO'];
                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
?>
                    <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                        <td width="23%"><a href="<? echo $pagina.$variable;?>"><? echo $codigo['CODIGO'];?></a></td>
                        <? if (isset($codigo['NOMBRE'])?$codigo['NOMBRE']:''){?>
                        <td width="23%"><a href="<? echo $pagina.$variable;?>"><? echo $codigo['NOMBRE'];?></a></td>
                        <? }?>
                        <td><a href="<? echo $pagina.$variable;?>"><? echo " Proyecto: ".$codigo['COD_PROYECTO']." - ".$codigo['PROYECTO'];?></a></td>
                    </tr>
  <?          }
            echo "</table>";
           echo "<br>Seleccione el c&oacute;digo para seguir el proceso.";
             
        }
    }
}
?>
