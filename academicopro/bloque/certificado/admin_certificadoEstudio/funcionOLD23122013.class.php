<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.0.1    Maritza Callejas    11/06/2013
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

class funcion_adminCertificadoEstudio extends funcionGeneral
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
                $this->sql=new sql_adminCertificadoEstudio($configuracion);

                 //Conexion General
                $this->acceso_db=$this->conectarDB($configuracion,"");

                //Conexion sga
                $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

                //Conexion Oracle
                $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

                //Datos de sesion
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");
                $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

                $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");
                
                $this->formulario='admin_certificadoEstudio';
                $this->bloque='certificado/admin_certificadoEstudio';
                
                $this->procedimientos=new procedimientos();
                $this->validacion=new validarInscripcion();
        
	}
        
        /**
         * Funcion para mostrar el formulario de solicitud de certificado
         */
        function mostrarFormulario(){
            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/html.class.php");
            $this->html = new html();
            $tab=0;
        
   	?>
        <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
                <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <table  class="sigma" width="100%">
                    <caption class="sigma centrar">
                        GENERACI&Oacute;N DE CERTIFICADOS DE ESTUDIO
                        </caption><br>
                </table>
                <table id="tabla" class="sigma" width="100%">
                    <tr class="sigma derecha">
                    <td width="18%">
                        <input type="text" id="codEstudiante" name="codEstudiante" size="11" onKeyPress="return solo_numero(event)" onchange="xajax_nombreEstudiante(document.getElementById('codEstudiante').value)">
                        <input type="hidden" name="opcion" value="consultarEstudiante">
                        <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                        
                    </td>
                    <td colspan="2">
                        <div id="div_nombreEstudiante" class="cuadro_plano"><&minus;&minus; Ingrese c&oacute;digo de estudiante</div>
                    </td>
                    </tr>
                </table>
                <table width="100%">
                <tr>
                    <td align="center">
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
        function revisarDatos(){
            
                    //obtenemos los codigos de los estudiantes digitados
                    $codEstudiante = (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'') ;
                    
                    if($codEstudiante && is_numeric($codEstudiante)){
                            $estudiante = $this->consultarDatosEstudiante($codEstudiante);
                            if(is_array($estudiante)){
                                if($this->nivel==110){
                                    $proyecto_valido = $this->validacion->validarProyectoAsistente($codEstudiante, $this->identificacion);
                                }else{
                                    $dato=array('codEstudiante'=>$codEstudiante);
                                    $proyecto_valido = $this->validacion->validarEstudiante($dato);
                                }
                                
                                if($proyecto_valido=='ok' || is_array($proyecto_valido)){
                                        if($estudiante[0]['ESTADO_ACTIVO']==='S'){
                                                $inscritas = $this->consultarEspaciosInscritos($codEstudiante);
                                                if(is_array($inscritas)){
                                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                                    $variable="pagina=admin_certificadoEstudio";
                                                    $variable.="&opcion=generar";
                                                    $variable.="&action=".$this->bloque;
                                                    $variable.="&codEstudiante=".$codEstudiante;
                                                    $variable.="&idenEstudiante=".$estudiante[0]['DOCUMENTO'];
                                                    include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");

                                                    $this->cripto=new encriptar();
                                                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                    
                                                }else{
                                                    $mensaje = "El estudiante con código ".$codEstudiante." no tiene inscripciones para el per&iacute;odo actual.";
                                                }
                                        }else{
                                            $mensaje = "El estudiante con código ".$codEstudiante." no se encuentra en un estado activo.";
                                        }
                                        
                                }else{
                                    $mensaje = $proyecto_valido;
                                }
                            }else{
                                    $mensaje = "Código de estudiante no valido.";
                                }
                                
                    }else{
                        $mensaje = "Código de estudiante no valido";
                    }
                    
                    if($mensaje){
                        $this->retornarPagina($mensaje);
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
        function generarCertificado(){
            $tipo_documento=1;
            $cod_estudiante=(isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'');
            $iden_estudiante=(isset($_REQUEST['idenEstudiante'])?$_REQUEST['idenEstudiante']:'');

            $numero_semestre=$this->calcularSemestreCertificado($cod_estudiante);
            $semestre = $this->semestreEnLetras($numero_semestre);
            $marca=$this->generarMarcaCertificado($cod_estudiante,$iden_estudiante,$numero_semestre);
            $dia = date('d');
            $mes = date('m');
            $anio = date('Y');
            if($cod_estudiante && $semestre && $marca && $dia && $mes && $anio){
                    $parametro_sql=array('COD_ESTUDIANTE'=>$cod_estudiante,
                                        'NIVEL_ACTUAL'=>$semestre,
                                        'MARCA_CERTIFICADO_ESTUDIO'=>$marca,
                                        'DIA_EXP'=>$dia,
                                        'MES_EXP'=>$this->mesEnLetras($mes),
                                        'ANIO_EXP'=>$anio
                        );

                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/crearDocumento.class.php");
                    $this->Documento = new crearDocumento($this->configuracion);
                    $this->Documento->crearDocumento($tipo_documento,$parametro_sql,$cod_estudiante);
            }else{
                    echo "DATOS INCOMPLETOS PARA GENERAR CERTIFICADO";
            }
        }
       
        /**
         * Funcion para calcular el semestre que le aparece en el certificado, que es el semestre del que tiene
         * más materias inscritas
         * @param type $cod_estudiante
         * @return type 
         */
        function calcularSemestreCertificado($cod_estudiante){
            $semestre=0;
            $cantidad=0;
            $inscritas = $this->consultarCantidadInscritasPorSemestre($cod_estudiante);
            if(is_array($inscritas)){
                foreach ($inscritas as $value) {
                    if($value['ASIGNATURAS']>$cantidad){
                        $cantidad=$value['ASIGNATURAS'];
                        $semestre=$value['PEN_SEM'];
                    }
                }
            }
            return $semestre;
        }
        
        /**
         * Funcion para consultar la cantidad de materias por semestre inscritas de un estudiante
         * @param type $cod_estudiante
         * @return type 
         */
        function consultarCantidadInscritasPorSemestre($cod_estudiante){
            $cadena_sql=$this->sql->cadena_sql("cantidad_inscritas_xperiodo", $cod_estudiante);//echo $cadena_sql;exit;
            return $resultado_est=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        }
        
        /**
         * Funcion que retorna el semestre en letras
         * @param type $num_semestre
         * @return string 
         */ 
        function semestreEnLetras($num_semestre){
            $semestre='';
            
            switch ($num_semestre) {
                case 1:
                    $semestre='PRIMER ';
                    break;
                case 2:
                    $semestre='SEGUNDO ';
                    break;
                case 3:
                    $semestre='TERCER ';
                    break;
                case 4:
                    $semestre='CUARTO ';
                    break;
                case 5:
                    $semestre='QUINTO ';
                    break;
                case 6:
                    $semestre='SEXTO ';
                    break;
                case 7:
                    $semestre='SEPTIMO ';
                    break;
                case 8:
                    $semestre='OCTAVO ';
                    break;
                case 9:
                    $semestre='NOVENO ';
                    break;
                case 10:
                    $semestre='DECIMO ';
                    break;
                case 11:
                    $semestre='DECIMO PRIMERO ';
                    break;
                case 12:
                    $semestre='DECIMO SEGUNDO ';
                    break;
                case 98:
                    $semestre='SEXTO ';
                    break;

                default:
                    $semestre='';
                    break;
            }
            return $semestre;
        }
        
        /**
         * Funcion para generar la marca del certificado de estudio
         * @param type $cod_estudiante
         * @param type $identificacion
         * @param type $num_semestre
         * @return type 
         */
        function generarMarcaCertificado($cod_estudiante,$identificacion,$num_semestre){
            $marca = '';
            if($cod_estudiante && $identificacion && $num_semestre){
                $marca = abs($cod_estudiante+$identificacion*$num_semestre-date('Ymd')+12);
            }
            return $marca;

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
        function retornarPagina($mensaje){
            echo "<script>alert('".$mensaje."');</script>";
            $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
            $variable="pagina=admin_certificadoEstudio";
            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");

            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,  $this->configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
        
}
?>