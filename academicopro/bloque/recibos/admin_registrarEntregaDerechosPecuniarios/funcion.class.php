
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

class funcion_adminRegistrarEntregaDerechosPecuniarios extends funcionGeneral {

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
        $this->sql = new sql_adminRegistrarEntregaDerechosPecuniarios($configuracion);
        $this->log_us = new log();
        $this->parametros = array();
        $this->formulario = "admin_registrarEntregaDerechosPecuniarios";
        $this->bloque = "recibos/admin_registrarEntregaDerechosPecuniarios";

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
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        //Conexion ORACLE
        if($this->nivel==4 || $this->nivel==28){
            $this->accesoOracle = $this->conectarDB($configuracion, "coordinador");
        }elseif($this->nivel==83){
            $this->accesoOracle=$this->conectarDB($configuracion,"secretarioacad");
        }elseif($this->nivel==33){
            $this->accesoOracle=$this->conectarDB($configuracion,"admisiones");
        }
    }

  
    /**
     * Funvión para mostrar el formulario de un nuevo registro de fechas
     */
    function mostrarFormularioNuevoRecibo() {
        $tab = 1;

        if ($this->tipoUser == 4 || $this->tipoUser == 83 || $this->tipoUser == 33) {//perfil coordinador, sec academico y admisiones
            $this->verificar = "control_vacio(" . $this->formulario . ",'fechaEntregado')";
            $codEstudiante = (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'');
            $anioRecibo = (isset($_REQUEST['anioRecibo'])?$_REQUEST['anioRecibo']:'');
            $periodoRecibo = (isset($_REQUEST['periodoRecibo'])?$_REQUEST['periodoRecibo']:'');
            $secuencia = (isset($_REQUEST['secuencia'])?$_REQUEST['secuencia']:'');
            $datosBusqueda=array('codEstudiante'=>$codEstudiante,
                                'anioRecibo'=>$anioRecibo,
                                'periodoRecibo'=>$periodoRecibo,
                                'secuencia'=>$secuencia );
            $datosRecibo = $this->consultarReciboDerechoPecuniario($datosBusqueda);
            if(is_array($datosRecibo)){
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
                                <b>::::..</b>  Registrar entrega
                                <hr class=hr_subtitulo>
                            </td>
                        </tr>		
   <tr>    
                            <td width="30%">Código del estudiante</td>
                            <td> <? echo $datosRecibo[0]['COD_ESTUDIANTE'];?></td>
                        </tr>
                        <tr>    
                            <td >Período recibo</td>
                            <td> <? echo $datosRecibo[0]['ANIO']."-".$datosRecibo[0]['PERIODO'];?></td>
                        </tr>
                        <tr>    
                            <td >Secuencia Recibo</td>
                            <td> <? echo $datosRecibo[0]['SECUENCIA'];?></td>
                        </tr>
                        <tr>    
                            <td width="30%">Concepto</td>
                            <td> <? echo $datosRecibo[0]['COD_CONCEPTO']." - ".$datosRecibo[0]['CONCEPTO'];?></td>
                        </tr>
                        <tr>    
                            <td width="30%">Valor pagado ($)</td>
                             <td> <? echo $datosRecibo[0]['VALOR'];?></td>
                        </tr>
                     <tr>    
                            <td ><span style='color:red;' > * </span>Fecha entregado</td>
                            <td>
                                <input name="fechaEntregado" type="text" id="fechaEntregado" size="10" <? if (isset($_REQUEST['fechaEntregado'])) echo "value=" . $_REQUEST['fechaEntregado']; ?> readonly="readonly">
                                <input align="center"type=image src="<? echo $this->configuracion['site'] . $this->configuracion['grafico']; ?>/cal.png" name="boronFechaEntregado" id="boronFechaEntregado" tabindex='<? echo $tab++; ?>'>

                                <script type="text/javascript">
                Calendar.setup({
                    inputField: "fechaEntregado",
                    button: "boronFechaEntregado",
                    align: "Tr"
                });
                                </script>
                            </td>

                            
                        </tr>
                     
                        <tr>    

                            <td colspan='2' align='center'>
                                <input type='hidden' name='codEstudiante' value="<? echo (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:''); ?>">
                                <input type='hidden' name='anioRecibo' value="<? echo (isset($_REQUEST['anioRecibo'])?$_REQUEST['anioRecibo']:''); ?>">
                                <input type='hidden' name='periodoRecibo' value="<? echo (isset($_REQUEST['periodoRecibo'])?$_REQUEST['periodoRecibo']:''); ?>">
                                <input type='hidden' name='secuencia' value="<? echo (isset($_REQUEST['secuencia'])?$_REQUEST['secuencia']:''); ?>">
                                
                                <? $this->enlaceRegistrar(); ?></td>
                        </tr>

                    </table>
                    <div id="div_mensaje1" align="center" class="ab_name">
                        Espacios requeridos (*)     

                    </div>

                </div>

            </form>
            <?
        }
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
     * Funcion para consultar un recibo de derecho pecuniario
     * @param type $codigo
     * @return type
     */
    function consultarReciboDerechoPecuniario($codigo) {
        $cadena_sql = $this->sql->cadena_sql("consultar_recibo_derecho_pecuniario", $codigo);
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

        
    /**
     * Función para mostrar enlace de retorno
     */
    function mostrarEnlaceRetorno() {
        $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";

        $variable2 = "pagina=admin_registrarEntregaDerechosPecuniarios";
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
