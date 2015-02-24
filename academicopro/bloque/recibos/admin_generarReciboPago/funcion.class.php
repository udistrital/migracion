
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

class funcion_adminGenerarReciboPago extends funcionGeneral {

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
        $this->sql = new sql_adminGenerarReciboPago($configuracion);
        $this->log_us = new log();
        $this->parametros = array();
        $this->formulario = "admin_generarReciboPago";
        $this->bloque = "recibos/admin_generarReciboPago";

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
        $this->tipoUser = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        //Conexion Oracle
        $this->accesoOracle = $this->conectarDB($configuracion, "soporteoas");
    }

  
    /**
     * Funvión para mostrar el formulario de un nuevo registro de fechas
     */
    function mostrarFormularioNuevoRecibo() {
        $tab = 1;

        if ($this->tipoUser == 80) {//perfil soporte
            $this->verificar = "seleccion_valida(" . $this->formulario . ",'periodoRecibo')";
            $this->verificar.="&&seleccion_valida(" . $this->formulario . ",'cuota')";
            $this->verificar.="&&control_vacio(" . $this->formulario . ",'codEstudiante')";
            $this->verificar.="&&control_vacio(" . $this->formulario . ",'valorOrdinario')";
            $this->verificar.="&&control_vacio(" . $this->formulario . ",'valorExtraordinario')";
            $this->verificar.="&&control_vacio(" . $this->formulario . ",'fechaOrdinaria')";
            $this->verificar.="&&control_vacio(" . $this->formulario . ",'fechaExtraordinaria')";
            $this->verificar.="&&control_vacio(" . $this->formulario . ",'valorOrdinario')";

            $periodos = $this->consultarPeriodos();
            if ($periodos) {
                $descripcionPeriodo[0][0] = '';
                $descripcionPeriodo[0][1] = '--';
                foreach ($periodos as $key => $periodo) {
                    $descripcionPeriodo[$key + 1][0] = $periodo['PERIODO'];
                    $descripcionPeriodo[$key + 1][1] = $periodo['PERIODO'];
                }
            } else {
                $descripcionPeriodo = '';
                $lista_periodos = '';
            }
            $cuota[0][0] = '1';
            $cuota[0][1] = '1';
            $cuota[1][0] = '2';
            $cuota[1][1] = '2';
            $cuota[2][0] = '3';
            $cuota[2][1] = '3';
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
                            <td colspan='2'>
                                <b>::::..</b>  Generar Recibo de Pago
                                <hr class=hr_subtitulo>
                            </td>
                        </tr>		
                        <tr>    
                            <td width="30%"><span style='color:red;' > * </span>Código del estudiante</td>
                            <td > <input type='text' name='codEstudiante' id="codEstudiante" size="20" maxlength="14" value="<? echo (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'')?>" onKeyPress="return solo_numero_sin_slash(event)">

                            </td>
                        </tr>
                        <tr>    
                            <td width="30%"><span style='color:red;' > * </span>Valor ordinario ($) Sin puntos ni comas</td>
                            <td > <input type='text' name='valorOrdinario' id="valorOrdinario" size="20" maxlength="14" value="<? echo (isset($_REQUEST['valorOrdinario'])?$_REQUEST['valorOrdinario']:'')?>" onKeyPress="return solo_numero_sin_slash(event)">
                            </td>
                        </tr>
                        <tr>    
                            <td width="30%"><span style='color:red;' > * </span>Valor extraordinario ($) Sin puntos ni comas</td>
                            <td >  <input type='text' name='valorExtraordinario' id="valorExtraordinario" size="20" maxlength="14" value="<? echo (isset($_REQUEST['valorExtraordinario'])?$_REQUEST['valorExtraordinario']:'')?>"onKeyPress="return solo_numero_sin_slash(event)">
                            </td>
                        </tr>
                        <tr>    
                            <td ><span style='color:red;' > * </span>Período que paga</td>
                            <td >
            <?
            if ($descripcionPeriodo) {
                $lista_periodos = $this->html->cuadro_lista($descripcionPeriodo, 'periodoRecibo', $this->configuracion, (isset($_REQUEST['periodoRecibo']) ? $_REQUEST['periodoRecibo'] : 0), 0, FALSE, $tab++, 'periodoRecibo', '');
            }
            echo $lista_periodos;
            ?>
                            </td>
                        </tr>
                        <tr>    
                            <td ><span style='color:red;' > * </span>Número de la cuota</td>
                            <td >
                                <?
                                if ($cuota) {
                                    $lista_cuotas = $this->html->cuadro_lista($cuota, 'cuota', $this->configuracion, (isset($_REQUEST['cuota']) ? $_REQUEST['cuota'] : 0), 0, FALSE, $tab++, 'cuota', '');
                                }
                                echo $lista_cuotas;
                                ?>
                            </td>
                        </tr>
                        <tr>    
                            <td ><span style='color:red;' > * </span>Fecha para pago ordinario
                                <br>        
                                <input name="fechaOrdinaria" type="text" id="fechaOrdinaria" size="10" <? if (isset($_REQUEST['fechaOrdinaria'])) echo "value=" . $_REQUEST['fechaOrdinaria']; ?> readonly="readonly">
                                <input align="center"type=image src="<? echo $this->configuracion['site'] . $this->configuracion['grafico']; ?>/cal.png" name="botonFechaOrdinaria" id="botonFechaOrdinaria" tabindex='<? echo $tab++; ?>'>

                                <script type="text/javascript">
                Calendar.setup({
                    inputField: "fechaOrdinaria",
                    button: "botonFechaOrdinaria",
                    align: "Tr"
                });
                                </script>
                            </td>

                            <td ><span style='color:red;' > * </span>Fecha para pago extraordinario
                                <br>
                                <input name="fechaExtraordinaria" type="text" id="fechaExtraordinaria" size="10" <? if (isset($_REQUEST['fechaExtraordinaria'])) echo "value=" . $_REQUEST['fechaExtraordinaria']; ?> readonly="readonly">
                                <input align="center"type=image src="<? echo $this->configuracion['site'] . $this->configuracion['grafico']; ?>/cal.png" name="botonFechaExtraordinaria" id="botonFechaExtraordinaria" tabindex='<? echo $tab++; ?>'>

                                <script type="text/javascript">
                                    Calendar.setup({
                                        inputField: "fechaExtraordinaria",
                                        button: "botonFechaExtraordinaria",
                                        align: "Tr"
                                    });
                                </script>
                            </td>
                        </tr>
                        <tr>    
                            <td width="30%"></span>Observación </td>
                            <td >   <table> 
                                    <tr><td>
                                            
                                            <input type="checkbox" name="obs1" value="1" <? if((isset($_REQUEST['obs1'])?$_REQUEST['obs1']:0)==1){echo "checked";}?>> TERMINO MATERIAS<br>
                                            <input type="checkbox" name="obs2" value="2" <? if((isset($_REQUEST['obs2'])?$_REQUEST['obs2']:0)==2){echo "checked";}?>> PRUEBA ACADÉMICA<br>
                                            <input type="checkbox" name="obs3" value="3" <? if((isset($_REQUEST['obs3'])?$_REQUEST['obs3']:0)==3){echo "checked";}?>> MOVILIDAD<br>
                                            <input type="checkbox" name="obs4" value="4" <? if((isset($_REQUEST['obs4'])?$_REQUEST['obs4']:0)==4){echo "checked";}?>> DTO CERTIFICADO ELECTORAL 10%<br>
                                            <input type="checkbox" name="obs5" value="5" <? if((isset($_REQUEST['obs5'])?$_REQUEST['obs5']:0)==5){echo "checked";}?>> EGRESADO 30%<br>
                                            <input type="checkbox" name="obs6" value="6" <? if((isset($_REQUEST['obs6'])?$_REQUEST['obs6']:0)==6){echo "checked";}?>> DTO SEGUNDO HERMANO 50%<br>
                                            <input type="checkbox" name="obs7" value="7" <? if((isset($_REQUEST['obs7'])?$_REQUEST['obs7']:0)==7){echo "checked";}?>> DTO TERCER HERMANO 70%<br>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="obs8" value="8" <? if((isset($_REQUEST['obs8'])?$_REQUEST['obs8']:0)==8){echo "checked";}?>> MATRICULA DE HONOR 100%<br>
                                            <input type="checkbox" name="obs9" value="9" <? if((isset($_REQUEST['obs9'])?$_REQUEST['obs9']:0)==9){echo "checked";}?>> DEPORTISTA ALTO RENDIMIENTO<br>
                                            <input type="checkbox" name="obs10" value="10" <? if((isset($_REQUEST['obs10'])?$_REQUEST['obs10']:0)==10){echo "checked";}?>> ESTUDIANTE DISTINGUIDO 100%<br>
                                            <input type="checkbox" name="obs11" value="11" <? if((isset($_REQUEST['obs11'])?$_REQUEST['obs11']:0)==11){echo "checked";}?>> MERITO ACADEMICO 100%<br>
                                            <input type="checkbox" name="obs12" value="12" <? if((isset($_REQUEST['obs12'])?$_REQUEST['obs12']:0)==12){echo "checked";}?>> MONITOR 50%<br>
                                            <input type="checkbox" name="obs13" value="13" <? if((isset($_REQUEST['obs13'])?$_REQUEST['obs13']:0)==13){echo "checked";}?>> DTO SED 70%<br>
                                            <input type="checkbox" name="obs14" value="14" <? if((isset($_REQUEST['obs14'])?$_REQUEST['obs14']:0)==14){echo "checked";}?>> DOCENTE 100%<br>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>

                        <tr>    

                            <td colspan='2' align='center'><? $this->enlaceValidar(); ?></td>
                        </tr>

                    </table>
                    <div id="div_mensaje1" align="center" class="ab_name">
                        Espacios requeridos (*)     

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
     * Función para consultar todas las facultades
     * @param type $datos
     * @return type
     */
    function consultarPeriodos() {
        $cadena_sql = $this->sql->cadena_sql("consultar_periodos", '');
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
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
        <input value="Registrar" name="aceptar" tabindex='20' type="button" onclick="document.forms['<? echo $this->formulario ?>'].submit()">                              


        <?
    }

    function enlaceValidar() {
        ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
         <input type='hidden' name='action' value="<? echo $this->bloque ?>">
        <input type='hidden' name='opcion' value="validar">
        <input value="Validar" name="aceptar" tabindex='20' type="button" onclick="if (<? echo $this->verificar; ?>) {document.forms['<? echo $this->formulario ?>'].submit()} else {false}">                              
        <? /* <input value="Validar" name="aceptar" tabindex='20' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">                              */ ?>


        <?
    }

    function mostrarFormularioDatosRecibo() {
        $datosEstudiante = $this->consultarEstudiante($_REQUEST['codEstudiante']);
        ?>
        <table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                        <tr class=texto_elegante >
                            <td colspan='2'>
                                <b>::::..</b>  Generar Recibo de Pago
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
                        <tr>    
                            <td width="30%">Código del estudiante</td>
                            <td> <? echo $_REQUEST['codEstudiante'];?></td>
                        </tr>
                        <tr>    
                            <td width="30%">Datos estudiante</td>
                            <td> <? echo $datosEstudiante[0]['NOMBRE'] ;?><br>
                            <? echo $datosEstudiante[0]['COD_PROYECTO']."-".$datosEstudiante[0]['PROYECTO'];?><br></td>
                        </tr>
                        <tr>    
                            <td width="30%">Valor ordinario ($)</td>
                            <td> <? echo $_REQUEST['valorOrdinario'];?></td>
                        </tr>
                        <tr>    
                            <td width="30%">Valor extraordinario ($)</td>
                             <td> <? echo $_REQUEST['valorExtraordinario'];?></td>
                        </tr>
                        <tr>    
                            <td >Período que paga</td>
                            <td> <? echo $_REQUEST['periodoRecibo'];?></td>
                        </tr>
                        <tr>    
                            <td >Número de la cuota</td>
                            <td> <? echo $_REQUEST['cuota'];?></td>
                        </tr>
                        <tr>    
                            <td >Fecha para pago ordinario</td>
                            <td> <? echo $_REQUEST['fechaOrdinaria'];?></td>
                        </tr>
                        <tr>    
                            <td >Fecha para pago extraordinario</td>
                            <td> <? echo $_REQUEST['fechaExtraordinaria'];?></td>
                        </tr>
                        <tr>    
                            <td width="30%"></span>Observación </td>
                             <td> <? 
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
                                    
                                     
                                 echo $obs;?></td>
                        </tr>

                        <tr>    
                            
        
                            <td colspan='2' align='center'>
                                <input type='hidden' name='codEstudiante' value="<? echo (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:''); ?>">
                                <input type='hidden' name='valorOrdinario' value="<? echo (isset($_REQUEST['valorOrdinario'])?$_REQUEST['valorOrdinario']:''); ?>">
                                <input type='hidden' name='valorExtraordinario' value="<? echo (isset($_REQUEST['valorExtraordinario'])?$_REQUEST['valorExtraordinario']:''); ?>">
                                <input type='hidden' name='periodoRecibo' value="<? echo (isset($_REQUEST['periodoRecibo'])?$_REQUEST['periodoRecibo']:''); ?>">
                                <input type='hidden' name='cuota' value="<? echo (isset($_REQUEST['cuota'])?$_REQUEST['cuota']:''); ?>">
                                <input type='hidden' name='fechaOrdinaria' value="<? echo (isset($_REQUEST['fechaOrdinaria'])?$_REQUEST['fechaOrdinaria']:''); ?>">
                                <input type='hidden' name='fechaExtraordinaria' value="<? echo (isset($_REQUEST['fechaExtraordinaria'])?$_REQUEST['fechaExtraordinaria']:''); ?>">
                                <input type='hidden' name='observacion' value="<? echo (isset($_REQUEST['observacion'])?$_REQUEST['observacion']:''); ?>">
                                <? $this->enlaceRegistrar(); ?></td>
                        </tr>

                    </table>
                    <div id="div_mensaje1" align="center" class="ab_name">
                        Espacios requeridos (*)     

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
    }

    /**
     * Función para mostrar enlace de retorno
     */
    function mostrarEnlaceRetorno() {
        $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";

        $variable2 = "pagina=admin_generarReciboPago";
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

}
?>
