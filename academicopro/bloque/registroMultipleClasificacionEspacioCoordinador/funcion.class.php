<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
    include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
    include_once ($configuracion['raiz_documento'].$configuracion['clases']."/procedimientos.class.php");

class funciones_registroMultipleClasificacionEspacioCoordinador extends funcionGeneral {
    
    private $configuracion;
    private $planEstudio;
    private $codProyecto;
    private $nombreProyecto;
    private $espaciosPlan;

    function __construct($configuracion, $sql) {
        
        $this->configuracion=$configuracion;
        $this->planEstudio=$_REQUEST['planEstudio'];
        $this->codProyecto=$_REQUEST['codProyecto'];
        $this->nombreProyecto=$_REQUEST['nombreProyecto'];
        
        $this->cripto=new encriptar();
        $this->tema=(isset($tema)?$tema:'');
        $this->sql=$sql;
        $this->procedimientos=new procedimientos;

        //Conexion General
        $this->acceso_db=$this->conectarDB($this->configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($this->configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($this->configuracion,"oraclesga");

        //Datos de sesion
        $this->usuario=$this->rescatarValorSesion($this->configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($this->configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($this->configuracion, $this->acceso_db, "nivelUsuario");
    }

    /**
     * Funcion que muestra los espacios academicos del plan de estudio
     * @param <array> $this->configuracion variables de configuracion
     * @param <int> $_REQUEST['planEstudio'] plan de estudio
     */
    function verPlanEstudio(){
        $this->encabezadoModulo();
        $this->mostrarEnlacesModulo();        
        $this->espaciosPlan=$this->buscarEspaciosPlan();
        if(is_array($this->espaciosPlan)){
            $this->mostrarPlan();
        }else
            {echo 'No existen espacios acad&eacute;micos registrados en el plan de estudios!!';}
    }
  
    /**
     * Función que crea el encabezado del modulo
     * @param <array> $this->configuracion
     */
    function encabezadoModulo(){
        ?>
            <table class='contenidotabla centrar' background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                <tr align="center">
                    <td class="centrar" colspan="6">
                        <h4>AGREGAR ESPACIOS ACAD&Eacute;MICOS COMO ELECTIVOS EXTR&Iacute;NSECOS</h4>
                        <hr noshade class="hr">
                    </td>
                </tr>
            </table>
        <?
    }

    /**
     * 
     */
    function mostrarEnlacesModulo() {
        if(isset($this->planEstudio)&&isset($this->codProyecto)&&isset($this->nombreProyecto)){
            
            ?>            
            <table class="contenidotabla centrar" border="0">
                <tr>
                    <td colspan="4" class="centrar" width="50%">
                        <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=adminConfigurarPlanEstudioCoordinador";
                        $ruta.="&opcion=mostrar";
                        $ruta.="&planEstudio=".  $this->planEstudio;
                        $ruta.="&codProyecto=".  $this->codProyecto;
                        $ruta.="&nombreProyecto=".  $this->nombreProyecto;
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                        ?>
                        <a href="<?echo $pagina.$ruta?>">
                            <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                        </a>
                    </td>
                    <td colspan="4" class="centrar">
                        <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=registroPortafolioElectivasCoordinador";
                        $ruta.="&opcion=ver";
                        $ruta.="&planEstudio=".  $this->planEstudio;
                        $ruta.="&codProyecto=".  $this->codProyecto;
                        $ruta.="&nombreProyecto=".  $this->nombreProyecto;
                        $ruta.="&clasificacion=4";
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                        ?>
                        <a href="<?echo $pagina.$ruta?>">
                        <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/portafolio2.png" width="35" height="35" border="0"><br>Portafolio<br>Eectivas Extr&iacute;nsecas
                        </a>
                    </td>
                </tr>
            </table>
            <?             
            
        }
        else{echo 'Faltan datos de plan de estudios, codigo y nombre del proyecto!!';exit;}
    }
    
    /**
     * 
     */
    function mostrarPlan() {
                ?>
                <table class="contenidotabla centrar">
                    <tr><td class="centrar" colspan="8">Plan de estudios: <?echo $this->planEstudio?></td></tr>
                <?
                            
                                foreach ($this->espaciosPlan as $espacio) {
                                    $nivelesPlan[]= $espacio['NIVEL'];
                                }
                                
                                $nivelesPlan= array_unique($nivelesPlan);
                                
                                foreach ($nivelesPlan as $nivel) {
                                    $this->mostrarEncabezadoNivel($nivel);
                                    $this->mostrarEspacios($nivel);
                                }
                                        ?>
                                    </tr>

                </table>
                <?        
    }
    
    /**
     *
     * @param type $nivel 
     */
    function mostrarEncabezadoNivel($nivel) {
                                    ?>
                                    <tr>
                                        <th colspan="8" class="sigma_a centrar">
                                        <?
                                        if($nivel==98)
                                        {
                                            ?><b>COMPONENTE PROPED&Eacute;UTICO</b><?
                                        }
                                        else{
                                                ?><b>NIVEL <?echo $nivel?></b><?
                                        }
                                        ?>
                                        </th>
                                    </tr>
                                    <tr class="cuadro_plano">
                                        <th class="sigma centrar">Cod Espacio</th>
                                        <th class="sigma centrar">Nombre Espacio</th>
                                        <th class="sigma centrar">Nro Cr&eacute;ditos</th>
                                        <th class="sigma centrar">Clasif</th>
                                        <th class="sigma centrar">HTD</th>
                                        <th class="sigma centrar">HTC</th>
                                        <th class="sigma centrar">HTA</th>
                                        <th class="sigma centrar">Electivo Extr&iacute;nseco</th>
                                    </tr>
                                <?
    }
    
    /**
     *
     * @param type $nivel 
     */
    function mostrarEspacios($nivel){
        foreach ($this->espaciosPlan as $espacio) {
                                            
            if($nivel==$espacio['NIVEL']){                
                ?>                               
                    <tr>
                        <td class="cuadro_plano centrar"><?echo $espacio['CODIGO']?></td>
                        <td class="cuadro_plano"><?echo $espacio['NOMBRE']?></td>
                        <td class="cuadro_plano centrar"><?echo $espacio['CREDITOS']?></td>
                        <td class="cuadro_plano centrar"><?echo $espacio['ABREVIATURA']?></td>
                        <td class="cuadro_plano centrar"><?echo$espacio['HTD']?></td>
                        <td class="cuadro_plano centrar"><?echo $espacio['HTC']?></td>
                        <td class="cuadro_plano centrar"><?echo $espacio['HTA']?></td>                        
                            <?
                             $this->mostrarEnlaceActivarInactivar($espacio['PORTAFOLIO'],$espacio);                                                                                                       
                ?></tr><?                
            }
        }
    }
    
    /**
     *
     * @param type $portafolio
     * @param type $espacio 
     */
    function mostrarEnlaceActivarInactivar($portafolio, $espacio) {
        //si esta activa an el portafolio de EE su valor es 1, si esta inactiva es 0
        if($portafolio==0){
            $grafica='gris.png';
            $opcionActivacion='activar';
            }
        else{
            $grafica='rojo.png';
            $opcionActivacion='inactivar';
            }
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=registroMultipleClasificacionEspacioCoordinador";
                        $ruta.="&opcion=actualizarEspacioPortafolio";
                        $ruta.="&opcionActivacion=".$opcionActivacion;
                        $ruta.="&planEstudio=".$espacio['CODIGO_PLAN'];
                        $ruta.="&codEspacio=".$espacio['CODIGO'];
                        $ruta.="&nombreEspacio=".$espacio['NOMBRE'];
                        $ruta.="&clasificacion_abrev=".$espacio['ABREVIATURA'];
                        $ruta.="&codProyecto=".  $this->codProyecto;
                        $ruta.="&nombreProyecto=".  $this->nombreProyecto;
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                                            
                        ?>
                            <td class="cuadro_plano centrar">
                                <a href="<?echo $pagina.$ruta?>" title="<?echo $opcionActivacion?>">
                                    <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico'].'/'.$grafica?>" width="18" height="18" border="0">
                                </a>
                            </td>
                        <?        
    }

    
    /****************************************************************************************
    *Los siguientes métodos ejecutan las modificaciones de los espacios academicos y log de eventos
    ****************************************************************************************/    
    
    
    /**
     * 
     */    
    function actualizarEspacioPortafolio() {
        $opcionActivacion=$_REQUEST['opcionActivacion'];
        $periodoActivo=$this->buscarPeriodoActivo();
        $anio=$periodoActivo['anio'];
        $periodo=$periodoActivo['periodo'];
        
        $codEspacio=$_REQUEST['codEspacio'];
        //$idClasificacion=$_REQUEST['id_clasificacion'];
        $planEstudio=$_REQUEST['planEstudio'];
              

                $this->actualizarEspacioElectivo($opcionActivacion,$codEspacio);
                $this->registrarEvento($opcionActivacion,$codEspacio,$anio,$periodo);                    
                $this->redireccionarPaginaInicial();

    }

    /**
     * 
     */
    function redireccionarPaginaInicial() {
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $ruta="pagina=registroMultipleClasificacionEspacioCoordinador";
        $ruta.="&opcion=ver_planEstudio";
        $ruta.="&planEstudio=".  $this->planEstudio;
        $ruta.="&codProyecto=".  $this->codProyecto;
        $ruta.="&nombreProyecto=".  $this->nombreProyecto;
        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        echo "<script>location.replace('".$pagina.$ruta."')</script>";
        exit;
    }

    
    /****************************************************************************************
    *Los siguientes métodos son consultas a las bases de datos
    ****************************************************************************************/    

    /**
     *
     * @return type 
     */
    function buscarEspaciosPlan(){
        $variables=array('planEstudio'=>  $this->planEstudio);
        
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,'espaciosPlanEstudio',$variables);//echo $cadena_sql;exit;
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        
        if(is_array($resultado)){
                    return $resultado;
        }
        else{echo 'No se encontaron espacios acad&eacute;micos registrados en el plan de estudios!!';exit;}
    }
    
    /**
     *
     * @return type 
     */
    function buscarPeriodoActivo(){
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,'buscarPeriodoActivo');
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        
        if(is_array($resultado)){
            $periodo['anio']=$resultado[0]['ANO'];
            $periodo['periodo']=$resultado[0]['PERIODO'];
        return $periodo;            
        }else
            {echo 'No se encontr&oacute; per&iacute;odo acad&eacute;mico!!';exit;}
    }    
    
    /**
     *
     * @param type $opcionActivacion
     * @param type $codEspacio
     * @param type $planEstudio
     * @return type 
     */
    function actualizarEspacioElectivo($opcionActivacion,$codEspacio){
        //cambiamos los valores para ajustarlos al: campo ofrecido_portafolio(mysql)
        switch ($opcionActivacion){            
            case 'activar':
                $opcionActivacion=1;
                break;
            
            case 'inactivar':
                $opcionActivacion=0;
                break;
            
            default:
                echo 'Opci&oacute;n de activacion inv&aacute;lida';exit;
                break;
        }
        
        $variables=array('ofrecido_portafolio'=>$opcionActivacion,
                         'planEstudio'=>  $this->planEstudio,
                         'codEspacio'=> $codEspacio   );
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,'actualizarEspacioPortafolio',$variables);//echo $cadena_sql;exit;
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, ""); 
        return $resultado;
    }
    
    /**
     *
     * @param type $opcionActivacion
     * @param type $codEspacio
     * @param type $planEstudio
     * @param type $anio
     * @param type $periodo
     * @return type 
     */
    function registrarEvento($opcionActivacion,$codEspacio,$anio,$periodo) {
        //cambiamos los valores para ajustarlos con el codigo y la descripcion del evento
        switch ($opcionActivacion){            
            case 'activar':
                $log_evento=44;
                $descripcion='Activa espacio académico como electivo extrínseco';
                break;
            
            case 'inactivar':
                $log_evento=45;
                $descripcion='Inactiva espacio académico como electivo extrínseco';
                break;
            
            default:
                echo 'Opci&oacute;n de activacion inv&aacute;lida';exit;
                break;
        }
        $variable=array('usuario'=>$this->usuario, 
                         'fecha'=>date('YmdHis'),
                         'evento'=>$log_evento,
                         'descripcion'=>$descripcion,
                         'registro'=> $anio."-".$periodo.", EA:".$codEspacio.", Plan:".$this->planEstudio,
                         'afectado'=>0);
        $registro=$this->procedimientos->registrarEvento($variable);
        return $registro;
    }

}



?>
