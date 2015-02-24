<?php


/**
 * Funcion registro_actualizarIntensidadHorariaEgresado
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package 
 * @subpackage registro_actualizarIntensidadHorariaEgresado
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 01/11/2013
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
/**
 * Incluye la clase sesion.class.php
 * Esta clase se encarga de crear, modificar y borrar sesiones
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");

/**
 * Clase funcion_registroActualizarIntensidadHorariaEgresado
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package registro_actualizarIntensidadHorariaEgresado
 * @subpackage Admin
 */
class funcion_registroActualizarIntensidadHorariaEgresado extends funcionGeneral {

  public $configuracion;
  public $accesoOracle;
  private $datos_estudiante;
  /**
     * Método constructor que crea el objeto sql de la clase funcion_registroActualizarIntensidadHorariaEgresado
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function __construct($configuracion) {
        /**
         * Incluye la clase encriptar.class.php
         *
         * Esta clase incluye funciones de encriptacion para las URL
         */
        $this->configuracion=$configuracion;
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        $this->formulario = "registro_actualizarIntensidadHorariaEgresado";//nombre del BLOQUE que procesa el formulario
        $this->bloque = "registro_actualizarIntensidadHorariaEgresado";//nombre del BLOQUE que procesa el formulario
        
        $this->cripto = new encriptar();
        $this->sql = new sql_registroActualizarIntensidadHorariaEgresado($configuracion);
       /**
         * Intancia para crear la conexion ORACLE
         */
        $this->accesoOracle = $this->conectarDB($configuracion, "administrador");
        /**
         * Instancia para crear la conexion General
         */
        $this->acceso_db = $this->conectarDB($configuracion, "");
        /**
         * Instancia para crear la conexion de MySQL
         */
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

        /**
         * Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
         */
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        /**
         * Datos de sesion
         */
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    /**
     * Funcion para mostrar el formulario de consulta para ingresar el codigo del estudiante
     */
    function mostrarFormulario() {
        $datos_estudiante='';
        $codEstudiante='';
      ?>
    <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
      <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>

          <table id="tabla" class="sigma" width="100%">
          <caption class="sigma centrar">
            ACTUALIZAR INTENSIDAD HORARIA EGRESADO
          </caption><br>
            <tr class="sigma derecha">
                <td width="50%"> C&oacute;digo</td><td width="1%"><input type="text" name="datoBusqueda" size="20" onkeypress="return soloNumerosYLetras(event)" value="<? echo (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'')?>">
                <input type="hidden" name="opcion" value="verificar">
                <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                
              </td>
              
            <td align="left">
              <input class="boton" type="button" value="Consultar" onclick="document.forms['<? echo $this->formulario?>'].submit()">
            </td>
          </tr>
        </table>
        
     </form>
<?
        
    }

    function verificarDatos(){
        $this->mostrarFormulario();
        $datoBusqueda = (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'');
        
        $datoValido = $this->validarDatoBusqueda($datoBusqueda);
        if($datoValido==true && is_numeric($datoBusqueda)){
                $codEstudiante = $datoBusqueda;
                $this->datos_estudiante = $this->consultaDatosEstudiante($codEstudiante);
                if(is_array($this->datos_estudiante) && $this->datos_estudiante[0]['ESTADO']){
                    $estadoValido = $this->validarEstado($this->datos_estudiante[0]['ESTADO']);
                    if($estadoValido=='ok'){
                        
                            if($this->datos_estudiante[0]['MODALIDAD']=='S'){
                                $notasEstudiante = $this->consultarNotasSinIntensidadEstudianteCreditos($codEstudiante);
                            }else{
                                $notasEstudiante = $this->consultarNotasSinIntensidadEstudianteHoras($codEstudiante);
                               
                            }
                            if(is_array($notasEstudiante)){
                                $datosEspacios = $this->consultarIntensidadHoraria($this->datos_estudiante[0]['COD_PROYECTO']);
                                if(is_array($datosEspacios) ){
                                    $html_tabla=$this->verificarCamposParaActualizar($datosEspacios,$notasEstudiante);
                                    echo $html_tabla;
                                }else{
                                    echo "No se encontro informaci&oacute;n de espacios en el proyecto curricular del estudiante";
                                }
                            }else{
                                echo "No se encontraron notas sin datos de intensidad horaria relacionadas al C&oacute;digo ingresado ";
                            }
                            
                    }else{
                        echo $estadoValido;
                    }
                }else{
                    echo "No se encontraron datos con el C&oacute;digo ingresado";
                }
        }else{
            echo "C&oacute;digo no valido";
        }
    }
 
    
    /**
     * Función para consultar los datos de un estudiante
     * @param type $codEstudiante
     * @return type 
     */
    function consultaDatosEstudiante($codEstudiante) {
          $cadena_sql = $this->sql->cadena_sql("datos_estudiante", $codEstudiante);
          return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    
    function validarEstado($estado){
        $valido='';
        if($estado=='E'){
            $valido='ok';
        }else{
            $valido='Estado del estudiante no valido para este proceso';
        }
        return $valido;
    }
    
    function consultarNotasSinIntensidadEstudianteCreditos($codEstudiante) {
          $cadena_sql = $this->sql->cadena_sql("notas_sin_intensidad_estudiante_creditos", $codEstudiante);
          return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    function consultarNotasSinIntensidadEstudianteHoras($codEstudiante) {
          $cadena_sql = $this->sql->cadena_sql("notas_sin_intensidad_estudiante_horas", $codEstudiante);
          return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    
    function consultarIntensidadHoraria($codProyecto) {
          $cadena_sql = $this->sql->cadena_sql("plan_estudios", $codProyecto);
          return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    
    function verificarCamposParaActualizar($datosEspacios,$notasEstudiante){
        $html='';
        
        $html.='<form enctype="tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain" method="POST" action="index.php" name="'.$this->formulario.'2">';
        $html.='<br><table border="1" cellpadding="10" cellspacing="0" >';
        $html.='<caption>Espacios con datos incompletos</caption>';
        $html.='<tr>';
        $html.='<td>COD. ESPACIO</td>';
        $html.='<td>NOMBRE ESPACIO</td>';
        $html.='<td>NIVEL</td>';
        $html.='<td>AÑO</td>';
        $html.='<td>PERIODO</td>';
        $html.='<td>NOTA</td>';
        $html.='<td>HT</td>';
        $html.='<td>HP</td>';
        $html.='<td>HAUT</td>';
        if($this->datos_estudiante[0]['MODALIDAD']=='S'){
            $html.='<td>CREDITOS</td>';
            $html.='<td>CLASIFICACION</td>';
        }
        $html.='<td>OBSERVACIONES</td>';
        $html.='</tr>';
        foreach ($notasEstudiante as $key => $nota) {
            $html.='<tr>';
            $html.='<td>'.$nota['NOT_ASI_COD'].'</td>';
            $html.='<td>'.$nota['ASI_NOMBRE'].'</td>';
            $html.='<td>'.(isset($nota['NOT_SEM'])?$nota['NOT_SEM']:' ').'</td>';
            $html.='<td>'.$nota['NOT_ANO'].'</td>';
            $html.='<td>'.$nota['NOT_PER'].'</td>';
            $html.='<td>NOTA</td>';
            
            $espacio = $this->buscarDatosIntensidadHoraria($datosEspacios,$nota);
            if($espacio ){
                //revisamos horas teoricas
                if(!(isset($nota['NOT_NRO_HT'])?$nota['NOT_NRO_HT']:'')){
                    $html.='<td style="color:#FF0000;">'.$espacio['PEN_NRO_HT'].'</td>';
                }else{
                    $html.='<td >'.$nota['NOT_NRO_HT'].'</td>';
                }
                //revisamos horas practicas
                if(!(isset($nota['NOT_NRO_HP'])?$nota['NOT_NRO_HP']:'')){
                    $html.='<td style="color:#FF0000;">'.$espacio['PEN_NRO_HP'].'</td>';
                }else{
                    $html.='<td >'.$nota['NOT_NRO_HP'].'</td>';
                }
                //revisamos horas trabajo autonomo
                if(!(isset($nota['NOT_NRO_AUT'])?$nota['NOT_NRO_AUT']:'')){
                    $html.='<td style="color:#FF0000;">'.$espacio['PEN_NRO_AUT'].'</td>';
                }else{
                    $html.='<td >'.$nota['NOT_NRO_AUT'].'</td>';
                }
                if($this->datos_estudiante[0]['MODALIDAD']=='S'){
                        //revisamos creditos
                        if(!(isset($nota['NOT_CRED'])?$nota['NOT_CRED']:'')){
                            $html.='<td style="color:#FF0000;">'.(isset($espacio['PEN_CRE'])?$espacio['PEN_CRE']:'--').'</td>';
                        }else{
                            $html.='<td >'.$nota['NOT_CRED'].'</td>';
                        }
                        //revisamos clasificacion
                        if(!(isset($nota['NOT_CEA_COD'])?$nota['NOT_CEA_COD']:'')){
                            $html.='<td style="color:#FF0000;">'.(isset($espacio['CEA_NOM'])?$espacio['CEA_NOM']:'--').'</td>';
                        }else{
                            $html.='<td >'.$nota['CEA_NOM'].'</td>';
                        }
                }
            }else{
                $html.='<td>'.$nota['NOT_NRO_HT'].'</td>';
                $html.='<td>'.$nota['NOT_NRO_HP'].'</td>';
                $html.='<td>'.$nota['NOT_NRO_AUT'].'</td>';
                if($this->datos_estudiante[0]['MODALIDAD']=='S'){
                    $html.='<td>'.(isset($nota['NOT_CRED'])?$nota['NOT_CRED']:'--').'</td>';
                    $html.='<td>'.(isset($nota['NOT_CEA_COD'])?$nota['NOT_CEA_COD']:'--').'</td>';
                }
                $html.='<td>No existen datos para el espacio acad&eacute;mico en los planes de estudios relacionados al proyecto</td>';

            }
            $html.='</tr>';
            
        }
        $html.='</table>';
        $html.='<table width="100%">';
        $html.='<tr>';
        $html.='<td>Los valores en color <font color="red">Rojo </font>son los valores encontrados para cada espacio, que se pueden actualizar.</td>';
        $html.='</tr>';
        $html.='<tr >
                <td width="50%" align="center">
                <input type="hidden" name="datoBusqueda" value="'.$this->datos_estudiante[0]['CODIGO'].'">
                <input type="hidden" name="opcion" value="actualizar">
                <input type="hidden" name="action" value="'.$this->bloque.'">
                <input class="boton" type="button" value="Actualizar" onclick="document.forms[\''.$this->formulario.'2\'].submit()">
            </td>
          </tr>';
        $html.='</table>';
        $html.='</form>';
        
        return $html;
        
    }
    
    function buscarDatosIntensidadHoraria($datosEspacios,$nota){
        $espacio='';
        $espacioPlanEstudio= $this->buscarEspacioEnPlanEstudio($datosEspacios,$nota);
        if(!$espacioPlanEstudio){
            $espacioProyecto= $this->buscarEspacioEnProyecto($datosEspacios,$nota);
            if($espacioProyecto){
                $espacio = $espacioProyecto;
            }
        }else{
            $espacio = $espacioPlanEstudio;
        }
        return $espacio;
    }
    
    function buscarEspacioEnPlanEstudio($datosEspacios,$nota){
        $espacio='';
        foreach ($datosEspacios as $key => $datosEspacio) {
            if($this->datos_estudiante[0]['COD_PROYECTO']==$datosEspacio['PEN_CRA_COD'] && $this->datos_estudiante[0]['PLAN_ESTUDIOS']==$datosEspacio['PEN_NRO'] && $nota['NOT_ASI_COD']==$datosEspacio['ASI_COD']){
                $espacio = $datosEspacio;
            }
        }
        return $espacio;
    }
    
    function buscarEspacioEnProyecto($datosEspacios,$nota){
        $espacio='';
        foreach ($datosEspacios as $key => $datosEspacio) {
            if($this->datos_estudiante[0]['COD_PROYECTO']==$datosEspacio['PEN_CRA_COD'] && $nota['NOT_ASI_COD']==$datosEspacio['ASI_COD']){
                $espacio = $datosEspacio;
                break;
            }
        }
        return $espacio;
    }
    
    /**
     * Función para validar que el dato de la buequeda tenga caracteres validos, solo números y letras
     * @param type $cadena
     * @return boolean
     */
    function validarDatoBusqueda($cadena){
        $permitidos = "1234567890";
        for ($i=0; $i<strlen($cadena); $i++){
        if (strpos($permitidos, substr($cadena,$i,1))===false){
        //no es válido;
        return false;
        }
        } 
        //si estoy aqui es que todos los caracteres son validos
        return true;
    }  
 
    function verificarActualizacion(){
        $this->mostrarFormulario();
        $datoBusqueda = (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'');
        
        $datoValido = $this->validarDatoBusqueda($datoBusqueda);
        if($datoValido==true && is_numeric($datoBusqueda)){
                $codEstudiante = $datoBusqueda;
                $this->datos_estudiante = $this->consultaDatosEstudiante($codEstudiante);
                if(is_array($this->datos_estudiante) && $this->datos_estudiante[0]['ESTADO']){
                    $estadoValido = $this->validarEstado($this->datos_estudiante[0]['ESTADO']);
                    if($estadoValido=='ok'){
                        
                            if($this->datos_estudiante[0]['MODALIDAD']=='S'){
                                $notasEstudiante = $this->consultarNotasSinIntensidadEstudianteCreditos($codEstudiante);
                            }else{
                                $notasEstudiante = $this->consultarNotasSinIntensidadEstudianteHoras($codEstudiante);
                               
                            }
                            if(is_array($notasEstudiante)){
                                $datosEspacios = $this->consultarIntensidadHoraria($this->datos_estudiante[0]['COD_PROYECTO']);
                                if(is_array($datosEspacios) ){
                                    $espaciosActualizados=$this->actualizarIntensidad($datosEspacios,$notasEstudiante);
                                    $this->mostrarReporteResultadoProceso($espaciosActualizados);
                                    
                                }else{
                                    echo "No se encontro informaci&oacute;n de espacios en el proyecto curricular del estudiante";
                                }
                            }else{
                                echo "No se encontraron notas sin datos de intensidad horaria relacionadas al C&oacute;digo ingresado ";
                            }
                            
                    }else{
                        echo $estadoValido;
                    }
                }else{
                    echo "No se encontraron datos con el C&oacute;digo ingresado";
                }
        }else{
            echo "C&oacute;digo no valido";
        }

    }
    function actualizarIntensidad($datosEspacios,$notasEstudiante){
        ?>
        <html><head>
                    <script language="javascript">
                    //Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
                    function callprogress(vValor,vItem,vTotal){
                     document.getElementById("getprogress").innerHTML = vItem+' de '+vTotal+' - '+vValor ;
                     document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
                    }
                    </script>
                    <style type="text/css">
                    /* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
                      .ProgressBar     { width: 70%; border: 1px solid black; background: #eef; height: 1.25em; display: block; margin-left: auto;margin-right: auto }
                      .ProgressBarText { position: absolute; font-size: 1em; width: 20em; text-align: center; font-weight: normal; }
                      .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible; }
                    </style>
                </head>
                <body>
                <!-- Ahora creo la barra de progreso con etiquetas DIV -->
                <br><div class="ProgressBar">
                      <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% </div>
                      <div id="getProgressBarFill"></div>
                    </div>
                </body>
    <?
        $indice=0;
        $a=1;
         $total=count($notasEstudiante);
         foreach ($notasEstudiante as $nota) {
            $actualizado='';
            $espacio = $this->buscarDatosIntensidadHoraria($datosEspacios,$nota);
            if($espacio ){
                //revisamos horas teoricas
                if(!(isset($nota['NOT_NRO_HT'])?$nota['NOT_NRO_HT']:'')){
                    $actualizado = $this->actualizarRegistroIntensidad($nota,'NOT_NRO_HT',$espacio['PEN_NRO_HT']);
                }
                //revisamos horas practicas
                if(!(isset($nota['NOT_NRO_HP'])?$nota['NOT_NRO_HP']:'')){
                    $actualizado = $this->actualizarRegistroIntensidad($nota,'NOT_NRO_HP',$espacio['PEN_NRO_HP']);
                }
                //revisamos horas trabajo autonomo
                if(!(isset($nota['NOT_NRO_AUT'])?$nota['NOT_NRO_AUT']:'')){
                    $actualizado = $this->actualizarRegistroIntensidad($nota,'NOT_NRO_AUT',$espacio['PEN_NRO_AUT']);
                }
                if($this->datos_estudiante[0]['MODALIDAD']=='S'){
                        //revisamos creditos
                        if(!(isset($nota['NOT_CRED'])?$nota['NOT_CRED']:'')){
                            $actualizado = $this->actualizarRegistroIntensidad($nota,'NOT_CRED',$espacio['PEN_CRE']);
                        }
                        //revisamos clasificacion
                        if(!(isset($nota['NOT_CEA_COD'])?$nota['NOT_CEA_COD']:'')){
                            $actualizado = $this->actualizarRegistroIntensidad($nota,'NOT_CEA_COD',$espacio['CLP_CEA_COD']);
                        }
                }
                $espacios[$indice]=$nota;
                $indice++;  

            }else{
                $espacios[$indice]=$nota;
                $espacios[$indice]['OBSERVACION']='<td>No existen datos para el espacio acad&eacute;mico en los planes de estudios relacionados al proyecto</td>';
                $indice++;
            }
            
            $porcentaje = $a * 100 / $total; //saco mi valor en porcentaje
            echo "<script>callprogress(".round($porcentaje).",".$a.",".$total.")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
            flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
            ob_flush();
            $a++;
                
        }
                
        return $espacios;
        
    }
    
    function actualizarRegistroIntensidad($nota,$nombre_campo,$valor_campo){
        $datos=array('codEstudiante'=>$nota['NOT_EST_COD'],
                    'codProyecto'=>$nota['NOT_CRA_COD'],
                    'codEspacio'=>$nota['NOT_ASI_COD'],
                    'anio'=>$nota['NOT_ANO'],
                    'periodo'=>$nota['NOT_PER'],
                    'nivel'=>(isset($nota['NOT_SEM'])?$nota['NOT_SEM']:''),
                    'nombreCampo'=>$nombre_campo,
                    'valorCampo'=>$valor_campo
            );
        $cadena_sql = $this->sql->cadena_sql("actualizar_intensidad_horaria", $datos);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }
    
    function mostrarReporteResultadoProceso($datos){
        $total=count($datos);
        $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";

        $variable="pagina=reporte_interno";
        $variable.="&opcion=informe";
        $variable.="&action=admin_reporte_interno";
        $variable.="&no_pagina=true";
        $variable.="&codigo=".$this->datos_estudiante[0]['CODIGO'];
        $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
        echo "<h1>Resultados del Proceso</h1>";
        
        $html = "<table>";
        $html .= "<tr>";
        $html .= "<td>Total Registros procesados:</td>";
        $html .= "<td>".$total."</td>";
        $html .= "</tr>";
        $html .= "</table>";
        $html .= "<tr>";
        $html .= "<td colspan='2'><a href='".$pagina.$variable."' target='_blank'>::Ver Reporte interno de notas</a></td>";
        $html .= "</tr>";
        $html .= "</table>";
        
        echo $html;
        
    }
    
}
    ?>