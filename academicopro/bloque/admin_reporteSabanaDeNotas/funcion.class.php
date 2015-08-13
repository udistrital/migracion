<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.0.1    Maritza Callejas    11/06/2013
 0.0.0.2    Maritza Callejas    01/10/2013
 0.0.0.3    Maritza Callejas    28/01/2015  Impresión de líneas - formato
 0.0.0.4    Milton Parra        13/08/2015  Ajustes para impresion y registro de generacion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");
//include_once("sql.class.php");


//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_reporteSabanaDeNotas extends funcionGeneral
{
    private $variable;
    private $mpdf;
    //@ Método costructor
	function __construct($configuracion)
	{
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        //Se llama a la clase utilizada para el pdf
        require_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf_sab_notas/pdf/mpdf.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");

                $this->variable=$variable;
		$this->configuracion = $configuracion;
                $this->cripto=new encriptar();
		//$this->tema=$tema;
                $this->sql=new sql_reporteSabanaDeNotas($configuracion);

                 //Conexion General
                $this->acceso_db=$this->conectarDB($configuracion,"");

                //Conexion sga
                $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

                //Conexion Oracle
                $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

                //Datos de sesion
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");

                $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");
                
                $this->formulario='admin_reporteSabanaDeNotas';
                
                $this->procedimientos=new procedimientos();
                
                $this->nivel = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "nivelUsuario");
                
                $this->validacion=new validarInscripcion();
    
	}
        
        /**
         * Funcion para mostrar el formulario de solicitud de sabana de notas 
         */
        function mostrarFormSolicitudSabanaNotas(){
            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/html.class.php");
            $this->html = new html();
            $tab=0;
            //$datos_proyectos = $this->consultarProyectos();
            
   	?>
        <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
                <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <table  class="sigma" width="100%">
                    <caption class="sigma centrar">
                        GENERACI&Oacute;N DE CERTIFICADOS SABANA DE NOTAS
                        </caption><br>
                    
                </table>
                <table id="tabla" class="sigma" width="100%">
                    <tr class="sigma derecha">
                        <td width="50%"> Por C&oacute;digo<input type="radio" name="tipoBusqueda" value="codigo" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="codigo" || !(isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')){echo "checked";} ?>><br>
                                        Por No. de Identificaci&oacute;n<input type="radio" name="tipoBusqueda" value="identificacion" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="identificacion"){echo "checked";} ?> ><br>
                                        Por Nombre<input type="radio" name="tipoBusqueda" value="nombre" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="nombre"){echo "checked";} ?> >
                                         </td>
                      <td width="1%">

                        <input type="text" name="datoBusqueda" size="20" onkeypress="return soloNumerosYLetras(event)" value="<? echo (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'')?>">
                        <input type="hidden" name="opcion" value="registrarEstudiante">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">

                      </td>

                    <td align="left">
                      <input class="boton" type="button" value="Verificar" onclick="document.forms['<? echo $this->formulario?>'].submit()">
                    </td>
                  </tr>
                    
                </table>
                    <br>
                    <br>
                
            </form>
    <?            
            
        }
        
        /**
         *Funcion para consultar todos los proyectos curriculares
         * @return <array> 
         */
        function consultarProyectos(){
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"proyectos", '');
            return $resultado_est=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        }
        
        /**
         * Funcion para consultar los datos de un proyecto curricular especifico
         * @param int $cod_proyecto
         * @return <array>
         */
        function consultarDatosProyecto($cod_proyecto){
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"datos_proyecto", $cod_proyecto);
            return $resultado_est=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        }
        
        
        /**
         * Función para revisar los datos ingresados por el usuario antes de generar la sabanas de notas 
         */
        function revisarDatos(){
            
                $datoBusqueda = (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'');
                $tipoBusqueda = (isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'');
                $cod_proyecto = (isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:0);
                $datoValido = $this->validarDatoBusqueda($datoBusqueda);
                if($datoValido==true){
                
                        if(($tipoBusqueda=='codigo' || $tipoBusqueda=='identificacion') && is_numeric($datoBusqueda)){
                            if($tipoBusqueda=='codigo'){
                                $codEstudiante = $datoBusqueda;
                            }  
                            if($tipoBusqueda=='identificacion'){
                                $codEstudiante = $this->consultarCodigoEstudiantePorIdentificacion($datoBusqueda);
                                if(is_array($codEstudiante )){
                                        $this->mostrarListadoProyectos($codEstudiante);
                                    }
                            }

                        }

                        if($tipoBusqueda=='nombre'){
                            $codEstudiante = $this->consultarCodigoEstudiantePorNombre($datoBusqueda);
                            if(is_array($codEstudiante )){
                                    $this->mostrarListadoProyectos($codEstudiante);
                                }
                        }

                        if(is_numeric($codEstudiante) ){
                                if(!$cod_proyecto){
                                    $proyectos = $this->consultarProyectoEstudiante($codEstudiante);
                                    $cod_proyecto=$proyectos[0]['COD_PROYECTO'];
                                    
                                }
                                //

                                if($cod_proyecto){

                                        $estudiantes = $this->consultarDatosEstudiantes($codEstudiante);
                                        
                                        if($this->nivel==83){
                                            $verificacion=$this->validacion->validarFacultadSecAcademico($codEstudiante,$this->usuario);        
                                            if($verificacion!='ok')
                                                {
                                                    ?>
                                                          <table class="contenidotabla centrar">
                                                            <tr>
                                                              <td class="cuadro_brownOscuro centrar">
                                                                  <?echo $verificacion;?>
                                                              </td>
                                                            </tr>
                                                          </table>
                                                    <?
                                                    exit;
                                                }
                                            }
                                        if($this->nivel==112||$this->nivel==116){
                                            $verificacion=$this->validacion->validarFacultadAsistente($codEstudiante,$this->usuario);        
                                            if($verificacion!='ok')
                                                {
                                                    ?>
                                                          <table class="contenidotabla centrar">
                                                            <tr>
                                                              <td class="cuadro_brownOscuro centrar">
                                                                  <?echo $verificacion;?>
                                                              </td>
                                                            </tr>
                                                          </table>
                                                    <?
                                                    exit;
                                                }
                                            }

                                        $notas_estudiantes = $this->consultarNotasEstudiantes($codEstudiante,$cod_proyecto);

                                        $secretario = $this->consultarSecretario($estudiantes[0]['CARRERA']);
                                        $proyecto = $this->consultarDatosProyecto($cod_proyecto);
                                        $mensaje_resultado='';
                                        $datos_estudiante = $this->buscarDatosEstudiante($codEstudiante,$estudiantes);
                                        if(is_array($datos_estudiante)){
                                            if($datos_estudiante['PENSUM']){
                                                $notas_estudiante = $this->buscarNotasEstudiante($codEstudiante,$notas_estudiantes);
                                                $resultado_electivas = $this->extraerElectivas($notas_estudiante,$datos_estudiante['IND_CRED']);
                                                $electivas = $resultado_electivas['electivas'];
                                                $electivas = $this->ordenarNotas($electivas);
                                                $notas = $resultado_electivas['notas'];
                                                $notas = $this->ordenarNotas($notas);
                                                if(is_array($notas)){
                                                        $encabezado = $this->armarEncabezado($datos_estudiante,$proyecto,1,$secretario);
                                                        $documento = $this->armarDocumento($proyecto,$datos_estudiante,$notas,$electivas,1);
                                                        $this->mostrarHtmlSabana($documento,$encabezado);
                                                        $this->mostrarFormularioEnvio();

                                                }else{
                                                    $mensaje_resultado .= "<br>El estudiante con código ".$codEstudiante." no tiene Notas registradas con el proyecto seleccionado";

                                                }
                                            }else{
                                                $mensaje_resultado .= "<br>El estudiante con código ".$codEstudiante." no tiene información del Pensum";

                                            }
                                        }else{
                                            $mensaje_resultado .= "<br>No se encontraron datos de estudiante con el código ".$codEstudiante;
                                        }
                            if($mensaje_resultado){
                                echo $mensaje_resultado;
                                $this->enlaceRetornar();
                            }

                        }
                        if(!$estudiantes){
                            echo "<br>Código del estudiante no valido";
                            $this->enlaceRetornar();
                        }
                    }else{
                            echo "<br>Código del estudiante no valido";
                            $this->enlaceRetornar();
                    }
                }else{
                    echo "Valor no valido para la busqueda";
                    $this->enlaceRetornar();
                }
        }
        
        /**
         * Funcion para generar la sabana de notas luego de revisar datos
         */
        function generarSabana(){
            $datoBusqueda = (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'');
                $tipoBusqueda = (isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'');
                $cod_proyecto = (isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:0);
                $datoValido = $this->validarDatoBusqueda($datoBusqueda);
                if($datoValido==true){
                
                        if(($tipoBusqueda=='codigo' || $tipoBusqueda=='identificacion') && is_numeric($datoBusqueda)){
                            if($tipoBusqueda=='codigo'){
                                $codEstudiante = $datoBusqueda;
                            }  
                            if($tipoBusqueda=='identificacion'){
                                $codEstudiante = $this->consultarCodigoEstudiantePorIdentificacion($datoBusqueda);
                                if(is_array($codEstudiante )){
                                        $this->mostrarListadoProyectos($codEstudiante);
                                    }
                            }

                        }else{
                            
                            if($tipoBusqueda=='identificacion'){
                                echo "Identificaci&oacute;n de estudiante no valida";
                            }
                        }

                        if($tipoBusqueda=='nombre'){
                            $codEstudiante = $this->consultarCodigoEstudiantePorNombre($datoBusqueda);
                            if(is_array($codEstudiante )){
                                    $this->mostrarListadoProyectos($codEstudiante);
                                }
                        }
                        
                        if(is_numeric($codEstudiante) ){
                                if(!$cod_proyecto){
                                    $proyectos = $this->consultarProyectoEstudiante($codEstudiante);
                                    $cod_proyecto=$proyectos[0]['COD_PROYECTO'];
                                    
                                }
                                //

                                if($cod_proyecto){

                                        $estudiantes = $this->consultarDatosEstudiantes($codEstudiante);
                                        
                                        if($this->nivel==83){
                                            $verificacion=$this->validacion->validarFacultadSecAcademico($codEstudiante,$this->usuario);        
                                            if($verificacion!='ok')
                                                {
                                                    ?>
                                                          <table class="contenidotabla centrar">
                                                            <tr>
                                                              <td class="cuadro_brownOscuro centrar">
                                                                  <?echo $verificacion;?>
                                                              </td>
                                                            </tr>
                                                          </table>
                                                    <?
                                                    exit;
                                                }
                                            }
                                        if($this->nivel==112||$this->nivel==116){
                                            $verificacion=$this->validacion->validarFacultadAsistente($codEstudiante,$this->usuario);        
                                            if($verificacion!='ok')
                                                {
                                                    ?>
                                                          <table class="contenidotabla centrar">
                                                            <tr>
                                                              <td class="cuadro_brownOscuro centrar">
                                                                  <?echo $verificacion;?>
                                                              </td>
                                                            </tr>
                                                          </table>
                                                    <?
                                                    exit;
                                                }
                                            }

                                        $notas_estudiantes = $this->consultarNotasEstudiantes($codEstudiante,$cod_proyecto);

                                        $secretario = $this->consultarSecretario($estudiantes[0]['CARRERA']);
                                        $proyecto = $this->consultarDatosProyecto($cod_proyecto);
                                        $mensaje_resultado='';
                                        $datos_estudiante = $this->buscarDatosEstudiante($codEstudiante,$estudiantes);
                                        if(is_array($datos_estudiante)){
                                            if($datos_estudiante['PENSUM']){
                                            $notas_estudiante = $this->buscarNotasEstudiante($codEstudiante,$notas_estudiantes);
                                            $resultado_electivas = $this->extraerElectivas($notas_estudiante,$datos_estudiante['IND_CRED']);
                                            $electivas = $resultado_electivas['electivas'];
                                            $electivas = $this->ordenarNotas($electivas);
                                            $notas = $resultado_electivas['notas'];
                                            $notas = $this->ordenarNotas($notas);
                                            if(is_array($notas)){
                                                    $encabezado = $this->armarEncabezado($datos_estudiante,$proyecto,2,'');
                                                    $documento = $this->armarDocumento($proyecto,$datos_estudiante,$notas,$electivas,2);
                                                    $pie_pagina = $this->armarPiePagina($datos_estudiante['MARCA'],$secretario[0]['NOMBRE']);
//                                                  
//                                                    $variablesRegistro=array('usuario'=>$this->usuario,
//                                                                            'evento'=>'0',
//                                                                            'descripcion'=>'Generar Sabana de Notas',
//                                                                            'registro'=>"cod_proyecto-> ".$cod_proyecto.", cod_estudiante->".$datos_estudiante['CODIGO'],
//                                                                            'afectado'=>$datos_estudiante['CODIGO']);
//
//                                                    $this->procedimientos->registrarEvento($variablesRegistro);
                                                    $this->generarPDF($documento,$encabezado,$pie_pagina,$codEstudiante,$datos_estudiante);
                                            }else{
                                                $mensaje_resultado .= "<br>El estudiante con código ".$codEstudiante." no tiene Notas registradas";
                                            
                                            }
                                            }else{
                                                $mensaje_resultado .= "<br>El estudiante con código ".$codEstudiante." no tiene información del Pensum";

                                            }
                                        }else{
                                            $mensaje_resultado .= "<br>No se encontraron datos de estudiante con el código ".$codEstudiante;
                                        }

                            echo $mensaje_resultado;
                        }
                    }else{
                            echo "<br>Código del estudiante no valido";
                    }
                }else{
                    echo "Valor no valido para la busqueda";
                }
            
        
        }

        /**
         * Función para consultar los datos de estudiantes a partir de una cadena de codigos de estudiantes 
         * @param string $codigos_estudiantes
         * @return <array> 
         */
        function consultarDatosEstudiantes($codigos_estudiantes) {
            $cadena_sql_est=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"datos_estudiantes", $codigos_estudiantes);
            return $resultado_est=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_est,"busqueda");

        }//fin funcion consultarDatosEstudiantes

        /**
         * Función para consultar las notas de estudiantes a partir de una cadena de codigos de estudiantes
         * @param string $codigos_estudiantes
         * @return <array> 
         */
        function consultarNotasEstudiantes($codigos_estudiantes,$carrera){
            $datos = array($codigos_estudiantes,$carrera);
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"notas_estudiantes", $datos);
            return $resultado_est=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        }
        
        /**
         * Función para ordenar notas por semestre y codigo de espacio
         * @param <array> $notas_estudiante
         * @return <array> 
         */
        function ordenarNotas($notas_estudiante){
            if(is_array($notas_estudiante)){
                // Obtener una lista de columnas
                foreach ($notas_estudiante as $key => $row) {
                    $nivel[$key]  = $row['SEMESTRE'];
                    $codigos_espacios[$key] = $row['COD_ESPACIO'];
                }
                
                // Ordenar los datos con codigos del estudiante ascendente, nivel del espacio ascendente, y codigo del espacio ascendente
                // Agregar $datos como el último parámetro, para ordenar por la llave común
                array_multisort($nivel, SORT_ASC,$codigos_espacios, SORT_ASC,  $notas_estudiante);
                
            }
            return $notas_estudiante;

        }
        
        /**
         * Función para consultar los datos del secretario de la facultad del proyecto curricular
         * @param type $cod_proyecto
         * @return type 
         */
        function consultarSecretario($cod_proyecto){
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"secretario", $cod_proyecto);
            return $resultado_est=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
    
        }
        
        /**
         * Función para buscar los datos de un estudiante en el arreglo general de estudiantes
         * @param int $codigo_estudiante
         * @param <array> $estudiantes
         * @return <array> 
         */
        function buscarDatosEstudiante($codigo_estudiante,$estudiantes){
            $resultado= '';
            if($estudiantes && $codigo_estudiante){
                foreach ($estudiantes as $estudiante) {
                    if($codigo_estudiante==$estudiante['CODIGO']){
                        $resultado['CODIGO']=$estudiante['CODIGO'];
                        $resultado['NOMBRE']=$estudiante['NOMBRE'];
                        $resultado['CARRERA']=$estudiante['CARRERA'];
                        $resultado['PENSUM']=$estudiante['PENSUM'];
                        $resultado['DOCUMENTO']=$estudiante['DOCUMENTO'];
                        $resultado['IND_CRED']=$estudiante['IND_CRED'];
                        $resultado['PROMEDIO']=$estudiante['PROMEDIO'];
                        $resultado['MARCA']=$estudiante['MARCA'];
                        break;
                    }
                }
            }
            return $resultado;
            
        }
        
        
        
        /**
         * Función para buscar las notas de un estudiante en el arreglo general de notas
         * @param int $codigo_estudiante
         * @param <array> $notas
         * @return <array> 
         */
        function buscarNotasEstudiante($codigo_estudiante,$notas){
            $resultado= '';
            $indice=0;
            if($notas && $codigo_estudiante){
                foreach ($notas as $nota) {
                    if($codigo_estudiante==$nota['COD_ESTUDIANTE']){
                        $resultado[$indice]['PROYECTO']=$nota['PROYECTO'];
                        $resultado[$indice]['COD_ESTUDIANTE']=$nota['COD_ESTUDIANTE'];
                        $resultado[$indice]['PENSUM']=$nota['PENSUM'];
                        $resultado[$indice]['SEMESTRE']=$nota['SEMESTRE'];
                        $resultado[$indice]['COD_ESPACIO']=$nota['COD_ESPACIO'];
                        $resultado[$indice]['ESPACIO']=$nota['ESPACIO'];
                        $resultado[$indice]['NOT_SEM']=$nota['NOT_SEM'];
                        $resultado[$indice]['OBSERVACION']=$nota['OBSERVACION'];
                        $resultado[$indice]['NOT_NOTA']=$nota['NOT_NOTA'];
                        $resultado[$indice]['NOT_NOTA_BASE']=$nota['NOT_NOTA_BASE'];
                        $resultado[$indice]['LETRAS']=$nota['LETRAS'];
                        $resultado[$indice]['INTENSIDAD']=$nota['INTENSIDAD'];
                        $resultado[$indice]['CREDITOS']=(isset($nota['CREDITOS'])?$nota['CREDITOS']:'');
                        $resultado[$indice]['CLASIFICACION']=(isset($nota['CLASIFICACION'])?$nota['CLASIFICACION']:'');
                        $resultado[$indice]['HT']=$nota['HT'];
                        $resultado[$indice]['HP']=$nota['HP'];
                        $resultado[$indice]['HA']=$nota['HA'];
                        $indice++;
                    }
                }
            }
            return $resultado;
            
        }
        
        
        /**
         * Funcion para armar el documento en html que se va a generar en pdf para la sabana de notas
         * @param int $proyecto
         * @param <array> $datos_estudiante
         * @param <array> $notas
         * @param <array> $electivas
         * @param int $tipo 1=>verificar datos 2=>generar sabana
         * @return string 
         */
        function armarDocumento($proyecto,$datos_estudiante,$notas,$electivas,$tipo){
            $html='<table  cellspacing="0">';
            $html .=$this->armarNotasEstudiante($notas,$datos_estudiante['IND_CRED'],$tipo);
            if(is_array($electivas)){
                $html .=$this->armarElectivasEstudiante($electivas,$datos_estudiante['IND_CRED'],$tipo);
            }
            $html .=$this->armarMensajeNotaAprobatoria($proyecto[0]['NOTA_APROBATORIA'],$datos_estudiante['IND_CRED']);
            if($tipo!=1){
                $html .=$this->armarMensajeFinalSabanaNotas($datos_estudiante['IND_CRED']);
            }
            $html .="</table>";
            return $html;
        }
        
        /**
         * Función para armar el html con los datos del estudiante para el PDF
         * @param <array> $datos_estudiante
         * @param <array> $proyecto
         * @return string 
         */
        function armarEncabezado($datos_estudiante,$proyecto,$tipo,$secretario){
            setlocale(LC_ALL,"es_ES");
            $html='<div class="datos">';
            $html.='<table border="0" cellpadding="0"  cellpadding="0">';
            $html.='<tr >';
            $html.='<td class="columnaTitulo1"><b> NOMBRE:</b></td>';
            $html.='<td class="columnaNombre"> ';
            $html.=$datos_estudiante['NOMBRE'];
            $html.='</td>';
            $html.='<td class="columnaTitulo2"> <b> IDENTIFICACIÓN:</b></td>';
            $html.='<td class="columnaIdentificacion"> ';
            $html.=$datos_estudiante['DOCUMENTO'];
            $html.='</td>';
            $html.='<td class="columnaTitulo3"> <b> PROMEDIO:</b></td>';
            $html.='<td class="columnaPromedio"> ';
            $html.=number_format($datos_estudiante['PROMEDIO'],2);
            $html.='</td>';
            $html.='</tr>';
            
            $html.='<tr>';
            $html.='<td class="columnaTitulo1"> <b> PROYECTO:</b></td>';
            $html.='<td class="columnaNombre"> ';
            $html.=$proyecto[0]['NOMBRE'];
            $html.='</td>';
            $html.='<td class="columnaTitulo2"> <b> C&Oacute;DIGO:</b></td>';
            $html.='<td class="columnaIdentificacion"> ';
            $html.=$datos_estudiante['CODIGO'];
            $html.='</td>';
            $html.='<td class="columnaTitulo3"> <b> FECHA:</b></td>';
            $html.='<td class="columnaPromedio"> ';
            $html.=date('d/m/Y');
            $html.='</td>';
            $html.='</tr>';
            
            if($tipo==1){
            $html.='<tr >';
                $html.='<td colspan="6">';
                $html.='<div class="datos"><b>FACULTAD CORRESPONDIENTE: </b>'.$secretario[0]['FACULTAD'].'</div><br>';
            $html.='</td>';
            $html.='</tr>';
                $html.='<tr >';
                $html.='<td colspan="6">';
                $html.='<div class="datos"><b>NOMBRE SECRETARIO ACAD&Eacute;MICO DE LA FACULTAD: </b>'.$secretario[0]['NOMBRE'].'<br><br></div>';
                $html.='</td>';
                $html.='</tr>';
            }
            $html.='</table>';
            $html.='</div>';
            if(trim($datos_estudiante['IND_CRED'])=='S') {
                    $estilo = "encabezadoNombreCreditos";
                    $columnas=3;
            }else{
                    $estilo = "encabezadoNombre";
                    $columnas=2;
                }
                if($tipo==1){
                    $html.='<br>';
                    $html.='<br>';
                    $html.='<br>';
                    $html.='<br>';
                    $html.='<br>';
                }else{
                    $html.='<table  cellspacing="0">';
                    $html.='<tr><td><td class="bordeEncabezadoNotas"></tr>';
                    $html.='<tr>';
                    $html.='<td align="center"  class="bordeSupIzquierdo" colspan="'.$columnas.'" > <div class "rounded">ESPACIO ACAD&Eacute;MICO</div></td>';
                    $html.='<td align="center" class="bordeSuperior" colspan="3"> INT.</td>';
                    $html.='<td align="center"  class="bordeSupDerecho" colspan="2"> CALIFICACIÓN</td>';
                    $html.='</tr>';
            
                    $html.='<tr>';
                    $html.='<td align="center"  class="encabezadoCodigo" rowspan="2"> CODIGO</td>';
                    $html.='<td  align="center" class="'.$estilo.'" rowspan="2"> NOMBRE</td>';
                    if(trim($datos_estudiante['IND_CRED'])=='S') {
                        $html.='<td class="encabezadoCreditos" rowspan="2"> CREDITOS</td>';
                    }
                    $html.='<td align="center" class="encabezadoHoras" colspan="3"> HORAS</td>';
                    $html.='<td align="center"  class="encabezadoNumero" rowspan="2"> N&Uacute;MERO</td>';
                    $html.='<td align="center"  class="encabezadoLetras" rowspan="2"> LETRAS</td>';
                    $html.='</tr>';
                    $html.='<tr>';
                    $html.='<td align="center" class="encabezadoHTD" > HTD</td>';
                    $html.='<td align="center" class="encabezadoHTC" > HTC</td>';
                    $html.='<td align="center" class="encabezadoHTA" > HTA</td>';
                    $html.='</tr>';
                        
                    $html.='<tr>';
                    $html.='<td class="columnaCodigo">&nbsp; </td>';
                    $html.='<td class="niveles1" >&nbsp;</td>';
                    if(trim($datos_estudiante['IND_CRED'])=='S') {
                        $html.='<td class="columnaCreditos"> &nbsp;</td>';
                    }
                    $html.='<td class="columnaTD">&nbsp; </td>';
                    $html.='<td class="columnaTC">&nbsp; </td>';
                    $html.='<td class="columnaTA">&nbsp; </td>';
                    $html.='<td class="columnaNota">&nbsp; </td>';
                    $html.='<td class="columnaNotaLetras">&nbsp; </td>';
                    $html.='</tr>';                 
                    $html.='</table>';
          
                }
                

            return $html;
        }
        
        
                
        /**
         * Función para armar el html con los datos de las notas (no electivas) del estudiante
         * @param type $notas
         * @param type $estudiante_creditos
         * @return string 
         */
        function armarNotasEstudiante($notas, $estudiante_creditos,$tipo){
            setlocale(LC_ALL,"es_ES");
            if(trim($estudiante_creditos)=='S') {
                    $estilo = "columnaNombreEspacioCreditos";
                    $columnas=3;
            }else{
                    $estilo = "columnaNombreEspacio";
                    $columnas=2;
                }

            if($tipo==1){
                    $html.='<tr>';
                    $html.='<td align="center"  class="bordeSupIzquierdo" colspan="'.$columnas.'" > <div class "rounded">ESPACIO ACAD&Eacute;MICO</div></td>';
                    $html.='<td align="center" class="bordeSuperior" colspan="3"> INT.</td>';
                    $html.='<td align="center"  class="bordeSupDerecho" colspan="2"> CALIFICACIÓN</td>';
                    $html.='</tr>';

                    $html.='<tr>';
                    $html.='<td align="center"  class="encabezadoCodigo" rowspan="2"> CODIGO</td>';
                    $html.='<td  align="center" class="'.$estilo.'" rowspan="2"> NOMBRE</td>';
                    if(trim($estudiante_creditos)=='S') {
                        $html.='<td class="encabezadoCreditos" rowspan="2"> CREDITOS</td>';
                    }
                    $html.='<td align="center" class="encabezadoHoras" colspan="3"> HORAS</td>';
                    $html.='<td align="center"  class="encabezadoNumero" rowspan="2"> N&Uacute;MERO</td>';
                    $html.='<td align="center"  class="encabezadoLetras" rowspan="2"> LETRAS</td>';
                    $html.='</tr>';
                    $html.='<tr>';
                    $html.='<td align="center" class="encabezadoHTD" > HTD</td>';
                    $html.='<td align="center" class="encabezadoHTC" > HTC</td>';
                    $html.='<td align="center" class="encabezadoHTA" > HTA</td>';
                    $html.='</tr>';

            }
            
            foreach ($notas as $key => $nota) {
                    if($notas[$key]['NOT_SEM']<>(isset($notas[$key-1]['NOT_SEM'])?$notas[$key-1]['NOT_SEM']:'')){

                        $html.='<tr>';
                        $html.='<td class="columnaCodigo">&nbsp; </td>';
                        $html.='<td class="niveles" >NIVEL '.$nota['NOT_SEM'].' </td>';
                        if(trim($estudiante_creditos)=='S') {
                            $html.='<td class="columnaCreditos"> &nbsp;</td>';
                        }
                        $html.='<td class="columnaTD">&nbsp; </td>';
                        $html.='<td class="columnaTC">&nbsp; </td>';
                        $html.='<td class="columnaTA">&nbsp; </td>';
                        $html.='<td class="columnaNota">&nbsp; </td>';
                        $html.='<td class="columnaNotaLetras">&nbsp; </td>';
                        $html.='</tr>';

                    }
                    $html.='<tr>';
                    $html.='<td class="columnaCodigo"> '.$nota['COD_ESPACIO'].'</td>';
                    $html.='<td class="'.$estilo.'"> '.$nota['ESPACIO'].'</td>';
                    if(trim($estudiante_creditos)=='S') {
                        $html.='<td class="columnaCreditos"> '.$nota['CREDITOS'].'</td>';
                    }
                    $html.='<td class="columnaTD"> '.$nota['HT'].'</td>';
                    $html.='<td class="columnaTC"> '.$nota['HP'].'</td>';
                    $html.='<td class="columnaTA"> '.$nota['HA'].'</td>';
                   
                     if(($nota['NOT_NOTA_BASE']==0 || $nota['NOT_NOTA_BASE']==0.0) && ($nota['OBSERVACION']==19 || $nota['OBSERVACION']==20  || $nota['OBSERVACION']==22 || $nota['OBSERVACION']==23 || $nota['OBSERVACION']==24 || $nota['OBSERVACION']==25)){
                        $nota['NOT_NOTA_BASE']='';
                     } else{ 
                        $nota['NOT_NOTA_BASE']=(double)($nota['NOT_NOTA_BASE']);
                        if($nota['NOT_NOTA_BASE']>0){
                            $nota['NOT_NOTA_BASE'] = ($nota['NOT_NOTA_BASE']/10);
                        }
                     }
                    
                    if(is_numeric($nota['NOT_NOTA_BASE'])){
                        $html.='<td class="columnaNota"> '.number_format($nota['NOT_NOTA_BASE'], 1).'</td>';
                    }else{
                        $html.='<td class="columnaNota"> '.$nota['NOT_NOTA_BASE'].'</td>';
                    }
                    $html.='<td class="columnaNotaLetras"> '.$nota['LETRAS'].'</td>';
                    $html.='</tr>';

            }
            return $html;
        }
        
        /**
         * Función para armar el html de las notas electivas del estudiante
         * @param <array> $notasElectivas
         * @param string $estudiante_creditos
         * @return string 
         */
        function armarElectivasEstudiante($notasElectivas, $estudiante_creditos,$tipo){
            setlocale(LC_ALL,"es_ES");
            $html='';
            if(trim($estudiante_creditos)=='S') {
                    $estilo = "columnaNombreEspacioCreditos";
                    $columnas = 7;
            }else{
                    $estilo = "columnaNombreEspacio";
                    $columnas = 6;
                }
                
            if(is_array($notasElectivas)){
                    $html='<tr>';
                    $html.='<td  class="columnaCodigo">&nbsp; </td>';
                    $html.='<td  class="niveles" >ELECTIVAS </td>';
                    if(trim($estudiante_creditos)=='S') {
                        $html.='<td class="columnaCreditos"> &nbsp;</td>';
                    }
                    $html.='<td class="columnaTD">&nbsp; </td>';
                    $html.='<td class="columnaTC">&nbsp; </td>';
                    $html.='<td class="columnaTA">&nbsp; </td>';
                    $html.='<td class="columnaNota">&nbsp; </td>';
                    $html.='<td class="columnaNotaLetras">&nbsp; </td>';
                    $html.='</tr>';
                    //valida si se genera el html
                    if($tipo==1){
                            $html.='<br>';
                            $html.='<tr>';
                            $html.='<td align="center"> CODIGO</td>';
                            $html.='<td  align="center"> ESPACIO</td>';
                            if(trim($estudiante_creditos)=='S') {
                                $html.='<td class="columnaCreditos"> CREDITOS</td>';
                            }
                            $html.='<td align="center"> HTD</td>';
                            $html.='<td align="center"> HTC</td>';
                            $html.='<td align="center"> HTA</td>';
                            $html.='<td align="center" colspan="2"> NOTA</td>';
                            $html.='</tr>';

                    }
                    
                    foreach ($notasElectivas as $nota) {
                            $html.='<tr>';
                            $html.='<td class="columnaCodigo"> '.$nota['COD_ESPACIO'].'</td>';
                            $html.='<td class="'.$estilo.'"> '.$nota['ESPACIO'].'</td>';
                            if(trim($estudiante_creditos)=='S') {
                                $html.='<td class="columnaCreditos"> '.$nota['CREDITOS'].'</td>';
                            }
                            $html.='<td class="columnaTD"> '.$nota['HT'].'</td>';
                            $html.='<td class="columnaTC"> '.$nota['HP'].'</td>';
                            $html.='<td class="columnaTA"> '.$nota['HA'].'</td>';
                             if(($nota['NOT_NOTA_BASE']==0 || $nota['NOT_NOTA_BASE']==0.0) && ($nota['OBSERVACION']==19 || $nota['OBSERVACION']==20  || $nota['OBSERVACION']==22 || $nota['OBSERVACION']==23 || $nota['OBSERVACION']==24 || $nota['OBSERVACION']==25)){
                                $nota['NOT_NOTA_BASE']='';
                             } else{ 
                                $nota['NOT_NOTA_BASE']=(double)($nota['NOT_NOTA_BASE']);
                                if($nota['NOT_NOTA_BASE']>0){
                                    $nota['NOT_NOTA_BASE'] = ($nota['NOT_NOTA_BASE']/10);
                                }
                             }
                            if(is_numeric($nota['NOT_NOTA_BASE'])){
                                $html.='<td class="columnaNota"> '.number_format($nota['NOT_NOTA_BASE'], 1).'</td>';
                            }else{
                                $html.='<td class="columnaNota"> '.$nota['NOT_NOTA_BASE'].'</td>';
                            }
                                
                            $html.='<td class="columnaNotaLetras"> '.$nota['LETRAS'].'</td>';
                            $html.='</tr>';

                    }
            }
            
            return $html;
        }
 
        /**
         * Función para armar el html de mensaje de nota aprobatoria
         * @param type $nota_aprobatoria
         * @return string 
         */
        function armarMensajeNotaAprobatoria($nota_aprobatoria,$estudiante_creditos){
            $nota_aprobatoria = (double)$nota_aprobatoria;
            if($nota_aprobatoria>10){
                $nota_aprobatoria = ($nota_aprobatoria/10);
            }
            $html='<tr>';
            $html.='<td  class="columnaCodigo">&nbsp; </td>';
            $html.='<td class="columnaMensaje"> Las notas van de 0.0 a 5.0, Nota m&iacute;nima aprobatoria: '.number_format($nota_aprobatoria, 1).'</td>';
            if(trim($estudiante_creditos)=='S') {
                $html.='<td class="columnaCreditos"> &nbsp;</td>';
            }
            $html.='<td class="columnaTD">&nbsp; </td>';
            $html.='<td class="columnaTC">&nbsp; </td>';
            $html.='<td class="columnaTA">&nbsp; </td>';
            $html.='<td class="columnaNota">&nbsp; </td>';
            $html.='<td class="columnaNotaLetras">&nbsp; </td>';
            $html.='</tr>';
            return $html;
        }
        
        /**
         * Función para armar el html para el mesaje de finalización de la sabana de notas
         * @return string 
         */
        function armarMensajeFinalSabanaNotas($estudiante_creditos){
            $html='<tr>';
            $html.='<td  class="columnaCodigo">&nbsp; </td>';
            $html.='<td class="columnaMensaje"> Aqu&iacute; termina este certificado de notas </td>';
            if(trim($estudiante_creditos)=='S') {
                $html.='<td class="columnaCreditos"> &nbsp;</td>';
            }
            $html.='<td class="columnaTD">&nbsp; </td>';
            $html.='<td class="columnaTC">&nbsp; </td>';
            $html.='<td class="columnaTA">&nbsp; </td>';
            $html.='<td class="columnaNota">&nbsp; </td>';
            $html.='<td class="columnaNotaLetras">&nbsp; </td>';
            $html.='</tr>';
            $html.='<tr>';
            $html.='<td  class="celdaFinal">&nbsp; </td>';
            $html.='<td class="celdaFinal"> &nbsp; </td>';
            if(trim($estudiante_creditos)=='S') {
                $html.='<td class="celdaFinal"> &nbsp;</td>';
            }
            $html.='<td class="celdaFinal">&nbsp; </td>';
            $html.='<td class="celdaFinal">&nbsp; </td>';
            $html.='<td class="celdaFinal">&nbsp; </td>';
            $html.='<td class="celdaFinal">&nbsp; </td>';
            $html.='<td class="celdaFinal">&nbsp; </td>';
            $html.='</tr>';
            return $html;
        }
        
        /**
         * Función para generar el archivo pdf con los htmls creados de documento y pie de pagina
         * @param string $doc_html
         * @param string $pie_pagina
         * @param int $cod_estudiante 
         */
        function generarPDF($doc_html,$encabezado, $pie_pagina,$cod_estudiante,$datosRegistro){
            //rescatamos valores para correr las margenes
            $espacioSuperior = (isset($_REQUEST['espacioSuperior'])?$_REQUEST['espacioSuperior']:0);
            $espacioIzquierda = (isset($_REQUEST['espacioIzquierda'])?$_REQUEST['espacioIzquierda']:0);
            //calculamos las demas margenes del pdf, la distancia desde el borde de la hoja a cada parte del PDF
            //Margen superior
            $margenEncabezado   = 19 + $espacioSuperior;
            //Margen izquierda de todo el documento
            $margenIzquierda    = 4 + $espacioIzquierda;
            //Margen derecha detodo el documento
            $margenDerecha      = 5 - $espacioIzquierda;
            //Margen superior del cuadro grande de notas
            $margenSuperior     = 32 + $margenEncabezado;
            //Margen inferior del cuadro de notas grande
            $margenInferior     = 25 - $espacioSuperior;
            //Margen firma secretario
            $margenPiePagina    = 8 - $espacioSuperior;
            $this->mpdf=new mPDF('','LETTER',16,'ARIAL',$margenIzquierda,$margenDerecha,$margenSuperior,$margenInferior,$margenEncabezado,$margenPiePagina);
            //inicia la hoja
            $this->mpdf->AddPage();
            $ruta_estilo = $this->configuracion["raiz_documento"].$this->configuracion["bloques"]."/admin_reporteSabanaDeNotas/clase/estilos_pdf.css";
            //establecemos el archivo de estilos
            $stylesheet =file_get_contents($ruta_estilo);  
            $this->mpdf->WriteHTML($stylesheet,1);
            //colocamos el html para el encabezado de pagina
            $this->mpdf->SetHTMLHeader($encabezado,'O',true);
            //colocamos el html para el pie de pagina
            $this->mpdf->setHTMLFooter($pie_pagina) ;
            //colocamos el html para el documento
            $this->mpdf->WriteHTML($doc_html); 
            $ruta_imagen = $this->configuracion["raiz_documento"]."/grafico/fondo_notas.png";
            //establecemos el nombre del archivo
            $nombre_archivo = "sabana_de_notas_".$cod_estudiante;
            $registrarDatosSabanaOficial=$this->registrarDatosSabanaOficial($datosRegistro);
            $this->mpdf->Output($nombre_archivo.'.pdf','D');
            echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";exit;
             
        }
        
        /**
         * Función para extraer de un arreglo de todas las notas, las que son de tipo electivas
         * @param <array> $notas_estudiante
         * @param string $estudiante_creditos
         * @return <array> 
         */
        function extraerElectivas($notas_estudiante,$estudiante_creditos){
            $indice=0;
            if(trim($estudiante_creditos)=='S') {
                if(is_array($notas_estudiante))  {  
                    foreach ($notas_estudiante as $key => $nota) {
                        if($nota['CLASIFICACION']=='4' || $nota['NOT_SEM']=='0'){
                            $electivas[$indice]['PROYECTO']=$nota['PROYECTO'];
                            $electivas[$indice]['COD_ESTUDIANTE']=$nota['COD_ESTUDIANTE'];
                            $electivas[$indice]['PENSUM']=$nota['PENSUM'];
                            $electivas[$indice]['SEMESTRE']=$nota['SEMESTRE'];
                            $electivas[$indice]['COD_ESPACIO']=$nota['COD_ESPACIO'];
                            $electivas[$indice]['ESPACIO']=$nota['ESPACIO'];
                            $electivas[$indice]['NOT_SEM']=$nota['NOT_SEM'];
                            $electivas[$indice]['OBSERVACION']=$nota['OBSERVACION'];
                            $electivas[$indice]['NOT_NOTA']=$nota['NOT_NOTA'];
                            $electivas[$indice]['NOT_NOTA_BASE']=$nota['NOT_NOTA_BASE'];
                            $electivas[$indice]['LETRAS']=$nota['LETRAS'];
                            $electivas[$indice]['INTENSIDAD']=$nota['INTENSIDAD'];
                            $electivas[$indice]['CREDITOS']=(isset($nota['CREDITOS'])?$nota['CREDITOS']:'');
                            $electivas[$indice]['HT']=$nota['HT'];
                            $electivas[$indice]['HP']=$nota['HP'];
                            $electivas[$indice]['HA']=$nota['HA'];
                            $indice++;
                            unset($notas_estudiante[$key]);
                        }
                    }
                }
            }elseif(trim($estudiante_creditos)=='N'){
                if(is_array($notas_estudiante))  {      
                    foreach ($notas_estudiante as $key => $nota) {
                        if($nota['NOT_SEM']=='0'){
                            $electivas[$indice]['PROYECTO']=$nota['PROYECTO'];
                            $electivas[$indice]['COD_ESTUDIANTE']=$nota['COD_ESTUDIANTE'];
                            $electivas[$indice]['PENSUM']=$nota['PENSUM'];
                            $electivas[$indice]['SEMESTRE']=$nota['SEMESTRE'];
                            $electivas[$indice]['COD_ESPACIO']=$nota['COD_ESPACIO'];
                            $electivas[$indice]['ESPACIO']=$nota['ESPACIO'];
                            $electivas[$indice]['NOT_SEM']=$nota['NOT_SEM'];
                            $electivas[$indice]['OBSERVACION']=$nota['OBSERVACION'];
                            $electivas[$indice]['NOT_NOTA']=$nota['NOT_NOTA'];
                            $electivas[$indice]['NOT_NOTA_BASE']=$nota['NOT_NOTA_BASE'];
                            $electivas[$indice]['LETRAS']=$nota['LETRAS'];
                            $electivas[$indice]['INTENSIDAD']=$nota['INTENSIDAD'];
                            $electivas[$indice]['CREDITOS']=(isset($nota['CREDITOS'])?$nota['CREDITOS']:'');
                            $electivas[$indice]['HT']=$nota['HT'];
                            $electivas[$indice]['HP']=$nota['HP'];
                            $electivas[$indice]['HA']=$nota['HA'];
                            $indice++;
                            unset($notas_estudiante[$key]);
                        }
                    }
                }

            }
             
            $resultado['electivas'] = $electivas;
            $resultado['notas'] = $notas_estudiante;
            return $resultado;
        }
        
        /**
         * Función para armar el html del pie de pagina de la sabana de notas
         * @param string $marca
         * @param string $secretario
         * @return string 
         */
        function armarPiePagina($marca,$secretario){
            $html= '<div class="pie">';
            $html.= '<table>';
            $html.= '<tr>';
            $html.= '<td class="columnaNumHoja"></td>';
            $html.= '<td class="columnaHojas">{PAGENO} de {nbpg}</td>';
            $html.= '<td class="columnaMarca">'.$marca.'</td>';
            $html.= '<td class="columnaSecretario">'.$secretario.'</td>';
            $html.= '</tr>';
            $html.= '</table>';
            $html.= '</div>';
            
            return $html;
        }
        
        /**
         * Función para mostrar el html de una sabana de notas
         * @param type $documento
         * @param type $encabezado
         */
        function mostrarHtmlSabana($documento,$encabezado){
            ?>
                <link href="<? echo $this->configuracion["raiz_documento"].$this->configuracion["bloques"]."/admin_reporteSabanaDeNotas/clase/estilos_pdf.css";?>" rel="stylesheet" type="text/css">
            <?
            echo "<br><b>Nota:</b>Por favor verifique los datos antes de generar el documento.<br><br>";
            echo $encabezado;
            echo "<br><br>";
            echo $documento;
            
        }
        
        /**
         * Función para mostrar el formulario para generar el documento 
         */
        function mostrarFormularioEnvio(){
            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/html.class.php");
            $this->html = new html();
            $indice=0;
            for($i=-10;$i<0;$i++)
                {
                $margenes[$indice][0]=$i;
                $margenes[$indice][1]=$i." mm";
                $indice++;
                }
            $margenes[$indice][0]=0;
            $margenes[$indice][1]=" 0";
            $indice++;
            for($i=1;$i<=10;$i++)
                {
                $margenes[$indice][0]=$i;
                $margenes[$indice][1]=$i." mm";
                $indice++;
                }
           
            $lista_espacioSuperior = $this->html->cuadro_lista($margenes,'espacioSuperior',$this->configuracion,0,0,FALSE,$tab++,'espacioSuperior','');
            $lista_espacioIzquierda = $this->html->cuadro_lista($margenes,'espacioIzquierda',$this->configuracion,0,0,FALSE,$tab++,'espacioIzquierda','');
            
            ?>
                <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>' target="_blank" >
                <br><div style = "display: table; width: 190px;">
                         
                        <div style = "float: right; height: 20px; width: 230px;text-align:center;font-weight: bold; ">AJUSTES DE IMPRESION</div>
                        <div style = "float: left; height: 80px; width: 112px;">&nbsp;</div>
                        <div style = "float: right; height: 80px; width: 78px;text-align:center;">
                            <div style = "float: left; height: 80px; width: 38px;"><img src='<? echo $this->configuracion['site'].$this->configuracion['grafico']."/flecha_rango_vertical.png";?>' alt='' ></div>
                            <div style = "float: right; height: 80px; width: 40px;"><br>
                            <? echo $lista_espacioSuperior;?></div>
                        </div>
                        <div style = "float: left; height: 103px; width: 112px;text-align:right;"><img src='<? echo $this->configuracion['site'].$this->configuracion['grafico']."/flecha_rango_horizontal.png";?>' alt='' >&nbsp;&nbsp;
                            <br><? echo $lista_espacioIzquierda;?></div>
                        <div style = "float: right; height: 103px; width: 78px;"><img src='<? echo $this->configuracion['site'].$this->configuracion['grafico']."/documento_margenes.jpg";?>' alt='margenes documento' width="100" height="140"></div>
                        
                    </div> 
                <table  class="sigma" width="100%">
                <tr>
                    <td align="center">
                        <input type="hidden" name="opcion" value="generar">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="hidden" name="tipoBusqueda" value="<? echo $_REQUEST['tipoBusqueda']; ?>">
                        <input type="hidden" name="datoBusqueda" value="<? echo $_REQUEST['datoBusqueda']; ?>">
                        <input type="hidden" name="codProyecto" value="<? echo $_REQUEST['codProyecto']; ?>">
                        
                    <input class="boton" type="button" value="Generar Sabana"  onClick="document.forms['<? echo $this->formulario?>'].submit();" >
                    </td>
                </tr>
                </table>
            </form>
            <?
           
        }
    /**
     * Función para consultar los codigos de estudiante relacionados a una identificación
     * @param int $identificacion
     * @return <array>
     */    
    function consultarCodigoEstudiantePorIdentificacion($identificacion){
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"consultar_codigo_estudiante_por_id", $identificacion);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
        
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
            echo "<br>C&oacute;digos relacionados a la busqueda:";
            echo "<br><br><table align='center' >";
            foreach ($codigos as $codigo) {
                    $variable="pagina=".$this->formulario;
                    $variable.="&opcion=registrarEstudiante";
                    $variable.="&action=".$this->formulario;
                    $variable.="&tipoBusqueda=codigo";
                    $variable.="&datoBusqueda=".$codigo['CODIGO'];
                    $variable.="&codProyecto=".$codigo['COD_PROYECTO'];
                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
?>
                    <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                        <td width="23%"><a href="<? echo $pagina.$variable;?>"><? echo $codigo['CODIGO'];?></a></td>
                        <? if (isset($codigo['NOMBRE'])?$codigo['NOMBRE']:''){?>
                        <td width="23%"><a href="<? echo $pagina.$variable;?>"><? echo $codigo['NOMBRE'];?></a></td>
                        <? }?>
                        <td><a href="<? echo $pagina.$variable;?>"  ><? echo " Proyecto: ".$codigo['COD_PROYECTO']." - ".$codigo['PROYECTO'];?></a></td>
                    </tr>
  <?          }
            echo "</table>";
            
        }
    }
    
    /**
     * Función para consultar los codigos de estudiante relacionados a un nombre
     * @param String $nombre
     * @return <array>
     */
    function consultarCodigoEstudiantePorNombre($nombre){
        $cadena_nombre='';
        $nombre = explode(" ", strtoupper($nombre));
        $palabras = count($nombre);
        $i=1;
            
        foreach ($nombre as $parte) {
            if($i==1){
                $cadena_nombre="'%".$parte."%'";
            }else{
                
                $cadena_nombre.=" AND est_nombre like '%".$parte."%'";
            }
            $i++;
        }
        
        $nombre=str_replace(" ", "%", $nombre);
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"consultar_codigo_estudiante_por_nombre", $cadena_nombre);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
     
        if(count($resultado)>1){
            return $resultado;
        }else{
            return $resultado[0][0];
        }
    }
    
    /**
     * Función para consultar los proyectos en los cuales un estudiante tiene notas registradas
     * @param int $codEstudiante
     * @return <array>
     */
    function consultarProyectoEstudiante($codEstudiante){
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"proyecto_de_estudiante", $codEstudiante);
        return $resultado_est=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
        
    }
    
    /**
     * Función para validar que el dato de la buequeda tenga caracteres validos, solo números y letras
     * @param type $cadena
     * @return boolean
     */
    function validarDatoBusqueda($cadena){
        $permitidos = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ1234567890 ";
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
     * Función para mostrar enlace a la pagina principal de sabana de notas
     */
    function enlaceRetornar(){
            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variable="pagina=admin_reporteSabanaDeNotas";
            $variable.="&opcion=mostrar";
            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $variable=$this->cripto->codificar_url($variable,$this->configuracion);
            $enlace = "<br><br><a href='".$pagina.$variable."'>::Volver</a>";
            echo $enlace;
				
    }
    
    function registrarDatosSabanaOficial($datosRegistro)
    {
        $registro=array('usuario'=>$this->usuario,
                        'fecha'=>date('YmdHis'),
                        'tiempo'=>time(),
                        'codigo'=>$datosRegistro['CODIGO'],
                        'documento'=>$datosRegistro['DOCUMENTO'],
                        'nombre'=>$datosRegistro['NOMBRE'],
                        'marca'=>$datosRegistro['MARCA'],
                        );
        //verifica si el registro ya existe para la sabana de notas generada
        //se comenta esta parte para que registre cada vez que se genera una sabana
/*        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"consultarRegistroSabana", $registro);
        $resultadoRegistro=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda");
        //si el registro no existe lo registra
        if (!is_array($resultadoRegistro))
        {*/
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"registrarGeneraSabanaOficial", $registro);
            $resultadoRegistro=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"");
        /*}*/
        return $resultadoRegistro;

}
}
?>