<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.0.1    Maritza Callejas    11/03/2014
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");
//include_once("sql.class.php");


//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_adminGenerarActa extends funcionGeneral
{
	//@ Método costructor
	function __construct($configuracion)
	{
                //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");


		$this->configuracion = $configuracion;
                $this->cripto=new encriptar();
		//$this->tema=$tema;
                $this->sql=new sql_adminGenerarActa($configuracion);

                 //Conexion General
                $this->acceso_db=$this->conectarDB($configuracion,"");

                //Conexion sga
                $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

                //Conexion Oracle
                $this->accesoOracle=$this->conectarDB($configuracion,"secretarioacad");

                //Datos de sesion
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");
                $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

                $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");
                
                $this->formulario='admin_generarActa';
                $this->bloque='actas/admin_generarActa';
                
                $this->procedimientos=new procedimientos();
                $this->validacion=new validarInscripcion();
        
	}
        
        /**
         * Funcion para mostrar el formulario de solicitud de certificado
         */
        function mostrarFormularioActa($tipoActa){
            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/html.class.php");
            $this->html = new html();
            $tab=0;
            switch($tipoActa) {
                case "copiaActaGrado":
                    $titulo = "GENERACI&Oacute;N DE COPIA DE ACTA DE GRADO";
                    break;
                case "actaGrado":
                    $titulo = "GENERACI&Oacute;N DE ACTA DE GRADO";
                    break;
            }
   	?>
        <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
                <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <table  class="sigma" width="100%">
                    <caption class="sigma centrar">
                        <?
                        echo $titulo;
                        ?>
                        </caption><br>
                </table>
                <table id="tabla" class="sigma" width="100%">
                    <tr class="sigma derecha">
                        <td width="50%"> Por C&oacute;digo<input type="radio" name="tipoBusqueda" value="codigo" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="codigo" || !(isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')){echo "checked";} ?>><br>
                                        Por No. de Identificaci&oacute;n<input type="radio" name="tipoBusqueda" value="identificacion" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="identificacion"){echo "checked";} ?> ><br>
                                        Por Nombre<input type="radio" name="tipoBusqueda" value="nombre" <? if ((isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'')=="nombre"){echo "checked";} ?> >
                                         </td>
                      <td width="1%">

                        <input type="text" name="datoBusqueda" size="20" value="<? echo (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'')?>">
                        <input type="hidden" name="opcion" value="consultarEstudiante">
                        <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                        <input type="hidden" name="tipoActa" value="<? echo $tipoActa; ?>">

                      </td>

                    <td align="left">
                      <input class="boton" type="button" value="Verificar" onclick="document.forms['<? echo $this->formulario?>'].submit()">
                    </td>
                  </tr>
                  
                </table>
                
            </form>
    <?            
            
        }
        
         
        /**
         * Función para revisar los datos ingresados por el usuario antes de generar el certificado
         */
        function revisarDatos($tipoActa){
                    //obtenemos los codigos de los estudiantes digitados
                $mensaje='';
                $codEstudiante='';
                $datoBusqueda = (isset($_REQUEST['datoBusqueda'])?$_REQUEST['datoBusqueda']:'');
                $tipoBusqueda = (isset($_REQUEST['tipoBusqueda'])?$_REQUEST['tipoBusqueda']:'');
                
                $datoValido = $this->validarDatoBusqueda($datoBusqueda);
                if(strlen($datoBusqueda)<4)
                {
                    echo "El dato para buscar debe contener al menos 4 caracteres";
                    exit;
                }
                if($datoValido==true){
                        if(($tipoBusqueda=='codigo' || $tipoBusqueda=='identificacion') && is_numeric($datoBusqueda)){
                            if($tipoBusqueda=='codigo'){
                                $codEstudiante = $datoBusqueda;
                            }  
                            if($tipoBusqueda=='identificacion'){
                                $codEstudiante = $this->consultarCodigoEstudiantePorIdentificacion($datoBusqueda);
                                if(is_array($codEstudiante )){
                                        $this->mostrarListadoProyectos($codEstudiante);
                                    }else{
                                         echo "Identificaci&oacute;n de egresado no valida.";
                                    }
                            }

                        }else{
                            if($tipoBusqueda=='codigo'){
                                echo "C&oacute;digo de egresado no valido";
                            }
                            if($tipoBusqueda=='identificacion'){
                                echo "Identificaci&oacute;n de egresado no valida";
                            }
                        }
                        if($tipoBusqueda=='nombre'){
                            $codEstudiante = $this->consultarCodigoEstudiantePorNombre($datoBusqueda);
                            if(is_array($codEstudiante )){
                                    $this->mostrarListadoProyectos($codEstudiante);
                                }else{
                                    echo "Nombre de egresado no valido.";
                                }
                        }

                        if(is_numeric($codEstudiante)){
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

                                    $estudiante = $this->consultarDatosEstudiante($codEstudiante);
                                    if(is_array($estudiante)){
                                                if($estudiante[0]['ESTADO']==='E'){
                                                    switch ($tipoActa) {
                                                        case 'copiaActaGrado':
                                                            $evento=72;
                                                            $descripcionEvento= 'Generación Copia de Acta de grado';
                                                            break;
                                                        case 'actaGrado':
                                                            $evento=73;
                                                            $descripcionEvento= 'Generación de Acta de grado';
                                                            break;

                                                        default:
                                                            break;
                                                    }
                                                            $variablesRegistro=array(   'usuario'=>$this->usuario,
                                                                                        'evento'=>$evento,
                                                                                        'descripcion'=>$descripcionEvento,
                                                                                        'registro'=>$codEstudiante,
                                                                                        'afectado'=>$codEstudiante);
                                                                                    //var_dump($variablesRegistro);exit;
                                                            $this->procedimientos->registrarEvento($variablesRegistro);
                                                            $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                                            $variable="pagina=admin_generarActa";
                                                            $variable.="&opcion=generar";
                                                            $variable.="&action=".$this->bloque;
                                                            $variable.="&codEstudiante=".$codEstudiante;
                                                            $variable.="&idenEstudiante=".$estudiante[0]['DOCUMENTO'];
                                                            $variable.="&tipoActa=".$tipoActa;
                                                            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");

                                                            $this->cripto=new encriptar();
                                                            
                                                            $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                            
                                                }else{
                                                    $mensaje = "El código ".$codEstudiante." no se encuentra en estado Egresado, se encuentra en estado ".$estudiante[0]['ESTADO_DESCRIPCION'].".";
                                                }

                                    }else{
                                            $mensaje = "Código de egresado no valido.";
                                        }

                            }

                            if($mensaje){
                                $this->retornarPagina($mensaje,$tipoActa);
                            }
                }else{
                            echo "Valor no v&aacute;lido para la b&uacute;squeda";
                    }
           
        }
        
        
        /**
         * Función para consultar los datos de estudiantes a partir de una cadena de codigos de estudiantes 
         * @param string $codigos_estudiantes
         * @return <array> 
         */
        function consultarDatosEstudiante($cod_estudiante) {
            $cadena_sql_est=$this->sql->cadena_sql("datos_estudiante", $cod_estudiante);
            return $resultado_est=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_est,"busqueda");

        }//fin funcion consultarDatosEstudiante
        
        
        
        /**
        * Funcion para consultar los proyectos a cargo de una persona, consultando por su identificacion
        * @param type $identificacion
        * @return type 
        */
        function consultarProyectosCoor($identificacion){
                $cadena_sql = $this->sql->cadena_sql("proyectos_curriculares", $identificacion);
                return $arreglo_proyecto = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        }

        
         /**
         * Función para consultar las notas de estudiantes a partir de una cadena de codigos de estudiantes
         * @param string $codigos_estudiantes
         * @return <array> 
         */
        function consultarEspaciosInscritos($cod_estudiante){
            $cadena_sql=$this->sql->cadena_sql("espacios_inscritos", $cod_estudiante);//echo $cadena_sql;exit;
            return $resultado_est=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        }
        
      
       
        /**
         * Funcion para generar el certificado de estudio luego de revisar datos
         */
        function generarActa($tipoActa){
            $cod_estudiante=(isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'');
            $iden_estudiante=(isset($_REQUEST['idenEstudiante'])?$_REQUEST['idenEstudiante']:'');

            $dia = date('d');
            $mes = date('m');
            $anio = date('Y');
                       
            switch($tipoActa) {
            
                case "copiaActaGrado":
                        $tipo_documento=2;
                        if($cod_estudiante ){

                                $parametro_sql=array('COD_ESTUDIANTE'=>$cod_estudiante,
                                                    'DIA_EXP'=>$dia,
                                                    'MES_EXP'=>$this->mesEnLetras($mes),
                                                    'ANIO_EXP'=>$anio
                                    );
                                include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/crearDocumento.class.php");
                                $this->Documento = new crearDocumento($this->configuracion);
                                $this->Documento->crearDocumento($tipo_documento,$parametro_sql,$cod_estudiante);
                        }else{
                                echo "NO SE ENCONTRARON DATOS PARA GENERAR COPIA DEL ACTA DE GRADO";
                        }
                        break;
                        
                case "actaGrado":
                        $tipo_documento=3;
                        if($cod_estudiante ){

                                $parametro_sql=array('COD_ESTUDIANTE'=>$cod_estudiante,
                                                    'DIA_EXP'=>$dia,
                                                    'MES_EXP'=>$this->mesEnLetras($mes),
                                                    'ANIO_EXP'=>$anio
                                    );
                                
                                include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/crearDocumento.class.php");
                                $this->Documento = new crearDocumento($this->configuracion);
                                $this->Documento->crearDocumento($tipo_documento,$parametro_sql,$cod_estudiante);
                        }else{
                                echo "NO SE ENCONTRARON DATOS PARA GENERAR ACTA DE GRADO";
                        }
                        break;
            }
        }
       
       
        /**
         * Funcion que retorna el nombre de un numero de mes
         */
        function mesEnLetras($num_mes){
            $mes='';
            
            switch ($num_mes) {
                case 1:
                    $mes='ENERO';
                    break;
                case 2:
                    $mes='FEBRERO';
                    break;
                case 3:
                    $mes='MARZO';
                    break;
                case 4:
                    $mes='ABRIL';
                    break;
                case 5:
                    $mes='MAYO';
                    break;
                case 6:
                    $mes='JUNIO';
                    break;
                case 7:
                    $mes='JULIO';
                    break;
                case 8:
                    $mes='AGOSTO';
                    break;
                case 9:
                    $mes='SEPTIEMBRE';
                    break;
                case 10:
                    $mes='OCTUBRE';
                    break;
                case 11:
                    $mes='NOVIEMBRE';
                    break;
                case 12:
                    $mes='DICIEMBRE';
                    break;

                default:
                    $mes='';
                    break;
            }
            return $mes;
        }
        
        /**
         * Funcion para mostrar el mensaje y retornar
         * @param string $mensaje 
         */
        function retornarPagina($mensaje, $opcion){
            echo "<script>alert('".$mensaje."');</script>";
            $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
            $variable="pagina=admin_generarActa";
            $variable.="&opcion=".$opcion;
            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");

            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,  $this->configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
        
        function consultarCodigoEstudiantePorIdentificacion($identificacion){
        $cadena_sql = $this->sql->cadena_sql("consultar_codigo_estudiante_por_id", $identificacion);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(count($resultado)>1){
            return $resultado;
        }else{
            return $resultado[0][0];
        }
    }
   
    /**
     * Función para listar los proyectos donde se encontraron resultados para la busqueda
     * @param type $codigos
     */
    function mostrarListadoProyectos($codigos){
        $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
        if(is_array($codigos)){
            echo "<br>C&oacute;digos relacionados a la busqueda:";
            echo "<br><br><table align='center' width='100%'>";
            foreach ($codigos as $codigo) {
                    $variable="pagina=".$this->formulario;
                    $variable.="&opcion=consultarEstudiante";
                    $variable.="&action=".$this->bloque;
                    $variable.="&tipoBusqueda=codigo";
                    $variable.="&datoBusqueda=".$codigo['CODIGO'];
                    $variable.="&tipoActa=".$_REQUEST['tipoActa'];
                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
?>
                    <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                        <td width="18%"><a href="<? echo $pagina.$variable;?>"><? echo $codigo['CODIGO'];?></a></td>
                        <? if (isset($codigo['NOMBRE'])?$codigo['NOMBRE']:''){?>
                        <td width="28%"><a href="<? echo $pagina.$variable;?>"><? echo $codigo['NOMBRE'];?></a></td>
                        <? }?>
                        <td><a href="<? echo $pagina.$variable;?>"  ><? echo " Proyecto: ".$codigo['COD_PROYECTO']." - ".$codigo['PROYECTO'];?></a></td>
                    </tr>
  <?          }
            echo "</table>";
            
        }
    }
    
    /**
     * Función para consultar los códigos relacionados a un nombre
     * @param type $nombre
     * @return type
     */
    function consultarCodigoEstudiantePorNombre($nombre){
        $cadena_nombre='';
        $nombre = explode(" ", strtoupper($nombre));
        $min=array('á','é','í','ó','ú','ä','ë','ï','ö','ü','ñ','ç');
        $may=array('Á','É','Í','Ó','Ú','Ä','Ë','Ï','Ö','Ü','Ñ','ç');
        $nombre=str_replace($min,$may,$nombre);
        
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
        $cadena_sql = $this->sql->cadena_sql("consultar_codigo_estudiante_por_nombre", $cadena_nombre);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        if(count($resultado)>1){
            return $resultado;
        }else{
            return $resultado[0][0];
        }
    }

/**
     * Función para validar que el dato de la buequeda tenga caracteres validos, solo números y letras
     * @param type $cadena
     * @return boolean
     */
    function validarDatoBusqueda($cadena){
        $permitidos = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ1234567890äÄëËïÏöÖüÜçÇ ";
        for ($i=0; $i<strlen($cadena); $i++)
        {
            if (strpos($permitidos, substr($cadena,$i,1))===false)
            {
                //no es válido;
                return false;
            }
        } 
        //si estoy aqui es que todos los caracteres son validos
        return true;
    }
   
}
?>