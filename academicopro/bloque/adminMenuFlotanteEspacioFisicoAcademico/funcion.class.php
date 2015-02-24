
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

class funcion_adminMenuFlotanteEspacioFisicoAcademico extends funcionGeneral {

    private $configuracion;

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        $this->configuracion = $configuracion;
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/validar_fechas.class.php");
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->fechas = new validar_fechas();
        $this->cripto = new encriptar();
        //$this->tema = $tema;
        $this->sql = new sql_adminMenuFlotanteEspacioFisicoAcademico();
        $this->log_us = new log();
        $this->parametrosHoras = array();
        $this->formulario = "admin_menuFlotanteInscripciones";
        $this->cripto = new encriptar();



        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");

        //Conexion sga
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

        //Conexion Oracle
        $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        $this->usuarioSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    }

    /**
     * Esta funcion presenta el horario del estudiante
     * Utiliza los metodos datosEstudiante, validar_fechas_estudiante_coordinador, validarEstadoEstudiante, registroAgil,
     *  horarioEstudianteConsulta, calcularCreditos, adicionar, finTabla
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, codProyectoEstudiante, planEstudioEstudiante, nombreProyecto, codEstudiante, xajax, xajax_file)
     */
    function mostrarMenuFlotante() {

        $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        
        /*$pagina = "pagina=adminEspacioFisico";
        $variableConsultar = "&opcion=consultar";
        
        $consultarFacultad = $pagina . $variableConsultar . "&espacio=1";        
        $consultarFacultad = $this->cripto->codificar_url($consultarFacultad, $this->configuracion);
        
        $consultarSede = $pagina . $variableConsultar . "&espacio=2";
        $consultarSede = $this->cripto->codificar_url($consultarSede, $this->configuracion);
        
        $consultarEdificio = $pagina . $variableConsultar . "&espacio=3";
        $consultarEdificio = $this->cripto->codificar_url($consultarEdificio, $this->configuracion);
        
        $consultarEFA = $pagina . $variableConsultar . "&espacio=4";
        $consultarEFA = $this->cripto->codificar_url($consultarEFA, $this->configuracion);*/
        
        $variable = "pagina=adminMenuFlotanteEspacioFisicoAcademico";
        $variable.= "&opcion=menuAuxiliar";

        $variableFacultad.= $variable . "&espacio=1";
        $variableFacultad = $this->cripto->codificar_url($variableFacultad, $this->configuracion);

        $variableSede.= $variable . "&espacio=2";
        $variableSede = $this->cripto->codificar_url($variableSede, $this->configuracion);

        $variableEdificio.= $variable . "&espacio=3";
        $variableEdificio = $this->cripto->codificar_url($variableEdificio, $this->configuracion);

        $variableEFA.= $variable . "&espacio=4";
        $variableEFA = $this->cripto->codificar_url($variableEFA, $this->configuracion);
        ?>
        <script>
                                                            
            function ajaxFunction() {
                var xmlHttp;
                try {
                    // Firefox, Opera 8.0+, Safari
                    xmlHttp=new XMLHttpRequest();
                    return xmlHttp;
                } catch (e) {
                    // Internet Explorer
                    try {
                        xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                        return xmlHttp;
                    } catch (e) {
                        try {
                            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                            return xmlHttp;
                        } catch (e) {
                            alert("Tu navegador no soporta AJAX!");
                            return false;
                        }}}
            }
            function Enviar(_pagina,capa) {
                                                
                var ajax;
                                
                ajax = ajaxFunction();

                ajax.open("POST", _pagina, true);

                ajax.setRequestHeader("Content-Type",
                "application/x-www-form-urlencoded");
                ajax.onreadystatechange = function(){

                    if (ajax.readyState == 4)
                    {
                        document.getElementById(capa).innerHTML =
                            ajax.responseText;

                    }}
                ajax.send(null);
            }           
                                                            
        </script>
        <header>
            <table class="sigma_borde"  width="100%">
                <tr>
                    <td class="cuadro_plano ">
                        <div id="navbar">
                            <span class="inbar">
                                <ul>
                                    <li><a href="javascript:Enviar('<? echo $indice . $variableFacultad ?>','menuAuxiliar')"><span>:: Facultad </span></a></li>
                                    <li><a href="javascript:Enviar('<? echo $indice . $variableSede ?>','menuAuxiliar')"><span>:: Sede </span></a></li>
                                    <li><a href="javascript:Enviar('<? echo $indice . $variableEdificio ?>','menuAuxiliar')"><span>:: Edificio </span></a></li>
                                    <li><a href="javascript:Enviar('<? echo $indice . $variableEFA ?>','menuAuxiliar')"><span>:: Espacio Físico Académico </span></a></li>

                                </ul>
                            </span>                                  
                        </div>
                    </td>
                </tr>
            </table>                
        </div>             
        </header>
        <div id="menuAuxiliar" name="menuAuxiliar">                    
        </div>
        <br>
        <?
    }

    function mostrarMenuAuxiliar($espacioIni) {

        $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";

        $pagina = "pagina=adminEspacioFisico";

        if ($espacioIni == 1) {
            $variableRegistrar = "&opcion=consultar";
            $variableConsultar = "&opcion=consultar";
            $variableRecuperar = "&opcion=consultar";
            $titulo= "Facultad";
        } else {
            if ($espacioIni==2){
                $titulo= "Sede";
            }else if ($espacioIni == 3){
                $titulo= "Edificio";
            }else if ($espacioIni == 4){
                $titulo= "Espacio ";
            }
            $variableRegistrar = "&opcion=registrar";
            $variableConsultar = "&opcion=consultar";
            $variableRecuperar = "&opcion=listadoRecuperar";
        }

        $espacio = "&espacio=" . $espacioIni;

        $registrar = $pagina . $variableRegistrar . $espacio;
        $registrar = $this->cripto->codificar_url($registrar, $this->configuracion);

        $consultar = $pagina . $variableConsultar . $espacio;
        $consultar = $this->cripto->codificar_url($consultar, $this->configuracion);

        $recuperar = $pagina . $variableRecuperar . $espacio;
        $recuperar = $this->cripto->codificar_url($recuperar, $this->configuracion);
        
        echo "<script>location.replace('" . $indice . $consultar . "')</script>";    
        
        ?>        
        <header>
            <div>
                <table class="sigma_borde"  width="100%">                    
                    <tr>
                        <td class="cuadro_plano ">
                            <div id="navbar">
                                <span class="inbar">
                                    <ul> 
                                        
                                        <? if ($espacioIni != '1') { ?>
                                            <li>
                                                <a href="<? echo $indice . $registrar ?>">                                                
                                                    <span>                                                    
                                                        :: Registrar <?echo $titulo?>
                                                        <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/registrar.png" width="30" height="30" border="0" alt="Registrar">
                                                    </span>
                                                </a>
                                            </li>
                                        <? } ?>
                                        <li>
                                            <a href="<? echo $indice . $consultar ?>">
                                                <span>
                                                    :: Consultar <?echo $titulo?>
                                                    <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/viewmag.png" width="30" height="30" border="0" alt="Consultar">
                                                </span>
                                            </a>
                                        </li>
                                        <? if ($espacioIni != 1) { ?>
                                            <li>
                                                <a href="<? echo $indice . $recuperar ?>">
                                                    <span>
                                                        :: Recuperar <?echo $titulo?>
                                                        <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/Options.png" width="30" height="30" border="0" alt="Recuperar">
                                                    </span>
                                                </a>
                                            </li>
                                        <? } ?>
                                    </ul>
                                </span>                                  
                            </div>
                        </td>
                    </tr>
                </table>
            </div> 
        </header>
        <br>
        <?
    }

}
?>
