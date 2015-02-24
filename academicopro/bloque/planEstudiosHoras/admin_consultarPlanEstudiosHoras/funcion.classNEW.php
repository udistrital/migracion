
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

//@ Esta clase presenta los datos de planes de estudio de horas

class funcion_adminConsultarPlanesEstudiosHoras extends funcionGeneral {

  private $configuracion;
  private $parametros;
  private $idProyecto;
  private $idPlanEstudios;
  private $planesEstudios;


  //@ Método costructor que crea el objeto sql de la clase sql_noticia
  function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validar_fechas.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");
    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

    $this->configuracion = $configuracion;
    $this->fechas = new validar_fechas();
    $this->cripto = new encriptar();
    $this->procedimientos = new procedimientos();
    $this->html = new html();

    //$this->tema = $tema;
    $this->sql = new sql_adminConsultarPlanesEstudiosHoras($configuracion);
    $this->log_us = new log();
    $this->parametros=array();
    $this->formulario = "admin_consultarPlanEstudiosHoras";
    $this->bloque = "planEstudiosHoras/admin_consultarPlanEstudiosHoras";

    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");

    //Conexion sga
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Conexion Oracle
    $this->accesoOracle = $this->conectarDB($configuracion, "asesvice");
    
    //conexion distribuida 1 conecta a MySUDD de lo contrario conecta a ORACLE
    if ($configuracion['dbdistribuida']==1)
        {
            $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
        }
        else
            {
                $this->accesoMyOracle = $this->accesoOracle;
            }
        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];
        $this->usuarioSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

  }

  
  /**
   * Función para mostrar el formulario de consulta por listado o por detalle
   */
  function mostrarFormulario() {
        $idPlanEstudios=(isset($_REQUEST['idPlanEstudios'])?$_REQUEST['idPlanEstudios']:'');
        $idProyecto=(isset($_REQUEST['idProyecto'])?$_REQUEST['idProyecto']:'');
        
        if(!$idProyecto || !$idPlanEstudios){
            $this->planesEstudios = $this->consultarDatosPlanesEstudios('','');
            //muestra listado planes de estudio
            $this->mostrarDatosPlanesEstudios();
        }else{
            $this->planesEstudios = $this->consultarPlanEstudios($idProyecto,$idPlanEstudios);
            $this->parametrosPlan = $this->consultarParametrosPlan($idProyecto,$idPlanEstudios);
            $this->mostrarDetallePlanEstudios($this->parametrosPlan);
            $this->mostrarEnlaceRetorno();
        }
  }

  
  /**
   * Función para consultar los planes de estudio
   * @return <array>
   */
  function consultarDatosPlanesEstudios($idProyecto,$idPlanEstudios) {
      $datos= array('idProyecto'=>$idProyecto,
                    'idPlanEstudios'=>$idPlanEstudios);
      $cadena_sql = $this->sql->cadena_sql("datos_planes_estudio", $datos);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  
  /**
   * Función para mostrar el formulario con el listado de los datos de los planes de estudio
   */
  function mostrarDatosPlanesEstudios() {
         ?>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.js"></script>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#tablaPlanesHoras').dataTable();
                    })
                </script>
                <link type="text/css" href="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>

        <?
                $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";	

                echo "<table class='tablaMarco' width='100%'>";
                echo "      <tr class=texto_elegante >";
                echo "          <td>";
                echo "          <b>::::..</b>  Planes de estudio de Horas";
                echo "          <hr class=hr_subtitulo>";
                echo "          </td>";
                echo "      </tr>	";
                echo "</table>";
                echo "<table class='contenidotabla' id='tablaPlanesHoras' width='80%'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th >Cod.Proyecto</th>";
                echo "<th >Proyecto Curricular</th>";
                echo "<th >Número plan de estudios</th>";
                echo "<th >Parametros</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                if(is_array($this->planesEstudios)){
                        foreach ($this->planesEstudios as $planEstudios) {
                            $parametrosPlan = $this->consultarParametrosPlan($planEstudios['PLAN_CRA_COD'],$planEstudios['PLAN_PEN_NRO']);
                            $obligatorios=$this->buscarNumEspaciosObligatorios($parametrosPlan);
                            $electivos=$this->buscarNumEspaciosElectivos($parametrosPlan);
                            
                            $variable = "pagina=".$this->formulario;
                            $variable.= "&action=".$this->bloque;
                            $variable.= "&usuario=".$this->usuario;
                            $variable.= "&opcion=consultarPlanEstudios";
                            $variable.= "&idProyecto=".$planEstudios['PLAN_CRA_COD'];
                            $variable.= "&idPlanEstudios=".$planEstudios['PLAN_PEN_NRO'];
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                            $enlace_consultar=$indice.$variable;
                            $variable2= "pagina=".$this->formulario;
                            $variable2.="&action=".$this->bloque;
                            $variable2.="&opcion=parametros";
                            $variable2.="&idProyecto=".$planEstudios['PLAN_CRA_COD'];
                            $variable2.="&idPlanEstudios=".$planEstudios['PLAN_PEN_NRO'];
                            $variable2.="&numObligatorios=".$obligatorios;
                            $variable2.="&numElectivos=".$electivos;

                            $variable2=$this->cripto->codificar_url($variable2,$this->configuracion);
                            $enlace_registrar=$indice.$variable2;

                            
                            echo "<tr>";
                            echo "<td align='center'>".$planEstudios['PLAN_CRA_COD']."</td>";
                            //echo "<td  ><a href='".$enlace_consultar."'>".$planEstudios['PLAN_NOMBRE']."</a></td>";
                            echo "<td  >".$planEstudios['PLAN_NOMBRE']."</td>";
                            echo "<td align='center'>".$planEstudios['PLAN_PEN_NRO']."</td>";
                            if($obligatorios>0 || $electivos>0){
                                echo "<td ><a href='".$enlace_registrar."'>Actualizar</a></td>";
                            }else{
                                echo "<td ><a href='".$enlace_registrar."'>Registrar</a></td>";
                            }
                            echo "</tr>";
                        }
                }
                echo "</tbody>";
        
  }

  /**
   * Función para mostrar el detalle de un plan de estudios
   */
  function mostrarDetallePlanEstudios($parametrosPlan){
      ?>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.js"></script>
                <script type="text/javascript" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#tablaPlanesHoras').dataTable();
                    })
                </script>
                <link type="text/css" href="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"];?>/datatables/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>

        <?
        $obligatorios=$this->buscarNumEspaciosObligatorios($parametrosPlan);
        if($obligatorios==0){$obligatorios= 'SIN DEFINIR';}
        $electivos=$this->buscarNumEspaciosElectivos($parametrosPlan);
        if($electivos==0){$electivos= 'SIN DEFINIR';}
        if(is_numeric($obligatorios) || is_numeric($electivos)){
            $total_espacios = $obligatorios + $electivos;
        }else{
            $total_espacios = "--";
        }
        echo "<table class='tablaMarco' width='90%' align='center' cellpadding='4 px' cellspacing='0px' >";
        echo "      <tr class=texto_elegante >";
        echo "          <td>";
        echo "          <b>::::..</b>  Plan de estudios Horas";
        echo "          <hr class=hr_subtitulo>";
        echo "          </td>";
        echo "      </tr>	";
        echo "</table>";  

        echo "<table  width='80%' border='0' align='center' cellpadding='4 px' cellspacing='0px' >";
        echo "<tr>";    
        echo "<td width='20%' >Proyecto Curricular:</td>";    
        echo "<td colspan='3'>".$this->planesEstudios[0]['PEN_CRA_COD']." - ".$this->planesEstudios[0]['PLAN_NOMBRE']."</td>";    
        echo "</tr>";    
        echo "<tr>";    
        echo "<td >N&uacute;mero plan de estudios:</td>";    
        echo "<td colspan='3'>".$this->planesEstudios[0]['PEN_NRO']."</td>";    
        echo "</tr>";    
        echo "<tr>";    
        echo "<td  width='20%'>N&uacute;mero espacios Obligatorios:</td>";    
        echo "<td  width='20%'>".$obligatorios."</td>";    
        echo "<td  width='15%'>N&uacute;mero espacios Electivos:</td>";    
        echo "<td  width='20%'>".$electivos."</td>";    
        echo "<td  width='15%'>Total espacios :</td>";    
        echo "<td  width='20%'>".$total_espacios."</td>";    
        echo "</tr>";    
        echo "</table>";  
        
        echo "<BR>";  
        
        echo "<table class='contenidotabla' id='tablaPlanesHoras' width='80%'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th >Semestre</th>";
                echo "<th >Cod. Asignatura</th>";
                echo "<th >Nombre Asignatura</th>";
                echo "<th >Clasificaci&oacute;n</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                if(is_array($this->planesEstudios)){
                        foreach ($this->planesEstudios as $planEstudios) {
                            echo "<tr>";
                            echo "<td align='center'>".$planEstudios['PEN_SEM']."</td>";
                            echo "<td align='center'>".$planEstudios['PEN_ASI_COD']."</td>";
                            echo "<td >".$planEstudios['ASI_NOMBRE']."</td>";
                            echo "<td>&nbsp;</td>";
                            echo "</tr>";
                        }
                }
                echo "</tbody>";
    echo "</table>";  
        
  }

  
  /**
   * Función para consultar un plan de estudios
   * @param int $idProyecto
   * @param int $idPlanEstudios
   * @return type
   */
  function consultarPlanEstudios($idProyecto,$idPlanEstudios) {
      $datos= array('idProyecto'=>$idProyecto,
                    'idPlanEstudios'=>$idPlanEstudios);
      $cadena_sql = $this->sql->cadena_sql("plan_estudios", $datos);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  
  /**
   * Función para consultar los datos de un proyecto
   * @param type $codigo
   * @return type
   */
  function consultarDatosProyectoCurricular($codigo) {
      $cadena_sql = $this->sql->cadena_sql("datos_proyecto_curricular", $codigo);
      return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
 
  /**
   * Función para mostrar el formulario de registro y actualización
   */
  function mostrarFormularioRegistro(){ // echo "form registro";exit;
        $tab=1;
        $this->idProyecto = (isset($_REQUEST['idProyecto'])?$_REQUEST['idProyecto']:'');
        $this->idPlanEstudios = (isset($_REQUEST['idPlanEstudios'])?$_REQUEST['idPlanEstudios']:'');
        $numObligatorios = (isset($_REQUEST['numObligatorios'])?$_REQUEST['numObligatorios']:'');
        $numElectivos = (isset($_REQUEST['numElectivos'])?$_REQUEST['numElectivos']:'');
        $total= $numObligatorios+$numElectivos;
        if($numObligatorios || $numElectivos){
            $titulo = "Actualizar ";
        }else{
            $titulo = "Registrar ";
        }
        $datosPlanEstudios = $this->consultarDatosPlanesEstudios($this->idProyecto, $this->idPlanEstudios);
         ?>
        <script src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"] ?>/jquery.js" type="text/javascript" language="javascript"></script>
        <link rel='stylesheet' type='text/css' media='all' href='<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-blue2.css"?>' title="win2k-cold-1"/>
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar.js"?>></script> 
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-es.js"?>></script>
        <script type='text/javascript' src=<? echo $this->configuracion['host'].$this->configuracion['site'].$this->configuracion['estilo']."/calendario/calendar-setup.js"?>></script>
            	
       <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
        <div id="normal" >
                
		<table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                    <tr class=texto_elegante >
                        <td colspan='2'>
                                    <b>::::..</b>  <? echo $titulo;?> Par&aacute;metros de Plan de estudios de Horas
                                <hr class=hr_subtitulo>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2'>
                            <? $this->mostrarDatosPlan($datosPlanEstudios);?>
                        </td>
                    </tr>
                    <tr>    
                        <td  width="30%"><span style='color:red;' > * </span>N&uacute;mero de espacios Obligatorios</td>
                            <td >
                                <div id ="div_numObligatorios"><input type="text" name='numObligatorios' id="numObligatorios" size="6" maxlength='5' <? if(isset($numObligatorios)?$numObligatorios:'')echo "value='".$numObligatorios."'";?> tabindex='<? echo $tab++;?>' onKeyPress="return soloDigitosNumericos(event)" onBlur="xajax_sumarObligatoriosYElectivos(document.getElementById('numObligatorios').value,document.getElementById('numElectivos').value,1,1)" ></div>
                                     

                            </td>
                    </tr>
                    <tr>    
                            <td ><span style='color:red;' > * </span>N&uacute;mero de espacios Electivos</td>
                            <td >
                                <div id ="div_numElectivos"><input type="text" name='numElectivos' id="numElectivos" size="6"  maxlength='5' <? if(isset($numElectivos)?$numElectivos:'')echo "value='".$numElectivos."'";?> tabindex='<? echo $tab++;?>' onKeyPress="return soloDigitosNumericos(event)"  onBlur="xajax_sumarObligatoriosYElectivos(document.getElementById('numObligatorios').value,document.getElementById('numElectivos').value,1,1)"></div>
                            </td>
                    </tr>
                    <tr>    
                            <td ><span style='color:red;' > </span>Total espacios</td>
                            <td >
                                     <div id ="div_total"><input type="text" name='total' id="total" size="6" maxlength='7' <? if(isset($total)?$total:'')echo "value='".$total."'";?> tabindex='<? echo $tab++;?>' readonly='true' ></div>
                            </td>
                    </tr>
                    <tr>    
                            
                            <td colspan='2' align='center'><? $this->enlaceRegistrar($titulo);?></td>
                    </tr>
				
		</table>
            <div id="div_mensaje1" align="center" class="ab_name">
                Espacios requeridos (*)            
            </div>
                   <? $this->mostrarEnlaceRetorno();?>
	    </div>
               
           </form>
        <?
    }

 
  /**
     * Funcion que muestra el enlace para registrar en la tabla de parametros
     * @param <int> $identificacion
     * @param <array> $this->configuracion
     * @param $this->accesoOracle
     * @param  $sql
     * Utiliza el metodo ejecutarSQL
     */
  function enlaceRegistrar($titulo) {
       //$pagina = $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        
        ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
          <input type='hidden' name='action' value="<? echo $this->bloque?>">
          <input type='hidden' name='idProyecto' value="<? echo $this->idProyecto?>">
          <input type='hidden' name='idPlanEstudios' value="<? echo $this->idPlanEstudios?>">
          <input type='hidden' name='opcion' value="registrar">
          <input value="<? echo $titulo;?>" name="aceptar" tabindex='20' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">                              
            
            
         <?
    }
    
    /**
     * Función para mostrar enlace de retorno
     */
    function mostrarEnlaceRetorno(){
            $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";

            $variable2="pagina=admin_consultarPlanEstudiosHoras";
            $variable2.="&opcion=consultar";

            $variable2=$this->cripto->codificar_url($variable2,$this->configuracion);
            $enlace_aprobar=$indice.$variable2;
            echo "<br><table><tr><td ><a href='".$enlace_aprobar."'>Volver</a></td></tr></table>";
    }
    
    /**
     * Función para consultar los parametros de un plan de horas
     * @param int $idProyecto
     * @param int $idPlanEstudios
     * @return <array>
     */
    function consultarParametrosPlan($idProyecto,$idPlanEstudios){
            $datos= array(  'idProyecto'=>$idProyecto,
                            'idPlanEstudios'=>$idPlanEstudios);
            $cadena_sql = $this->sql->cadena_sql("parametros_plan", $datos);
            return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    /**
     * Función para buscar en un arreglo el número de espacios obligatorios
     * @param <array> $parametrosPlan
     * @return int
     */
    function buscarNumEspaciosObligatorios($parametrosPlan){
        $valor=0;
        if(is_array($parametrosPlan)){
            foreach ($parametrosPlan as  $parametro) {
                if($parametro['PAR_CLASIFICACION']==6){
                    $valor=$parametro['PAR_NUMERO'];
                }
            }
        }
        return $valor;
    }
    
    /**
     * Función para buscar en un arreglo el número de espacios electivos
     * @param <array> $parametrosPlan
     * @return int
     */
    function buscarNumEspaciosElectivos($parametrosPlan){
        $valor=0;
        if(is_array($parametrosPlan)){
            foreach ($parametrosPlan as $parametro) {
                if($parametro['PAR_CLASIFICACION']==8){
                    $valor=$parametro['PAR_NUMERO'];
                }
            }
        }
        return $valor;
    }
    
    /**
     * Función para mostrar los datos de un plan de estudios
     * @param type $datosPlanEstudios
     */
    function mostrarDatosPlan($datosPlanEstudios){
        echo "<table  width='100%' border='0' align='center' cellpadding='4 px' cellspacing='0px' >";
        echo "<tr>";    
        echo "<td width='20%' >Proyecto Curricular:</td>";    
        echo "<td colspan='3'>".$datosPlanEstudios[0]['PLAN_CRA_COD']." - ".$datosPlanEstudios[0]['PLAN_NOMBRE']."</td>";    
        echo "</tr>";    
        echo "<tr>";    
        echo "<td >N&uacute;mero plan de estudios:</td>";    
        echo "<td colspan='3'>".$datosPlanEstudios[0]['PLAN_PEN_NRO']."</td>";    
        echo "</tr>";    
        echo "</table>";  
        
    }
}
?>
