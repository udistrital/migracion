<?php
/**
 * Funcion adminConsultarInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 10/01/2013
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
/**
 * Incluye la clase sesion.class.php
 * Esta clase se encarga de crear, modificar y borrar sesiones
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");

/**
 * Clase funcion_adminConsultarInscripcionGrupoCoordinador
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 */
class funcion_adminRankingPreinsDemanda extends funcionGeneral
{

      public $configuracion;
      public $mensaje;

      /**
         * Método constructor que crea el objeto sql de la clase funcion_adminConsultarInscripcionGrupoCoordinador
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	function __construct($configuracion) {

            $this->configuracion=$configuracion;

	    /**
             * Incluye la clase encriptar.class.php
             *
             * Esta clase incluye funciones de encriptacion para las URL
             */
	    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/preinscripcion/admin_rankingPreinsDemanda".$configuracion["clases"]."/clasificarEstudiantes.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/preinscripcion/admin_rankingPreinsDemanda".$configuracion["clases"]."/horariosBinario.class.php");

            
	    $this->cripto=new encriptar();
	    $this->clasificacion=new clasificacionEstudiante();
	    $this->horariosBinario=new horariosBinario();

	    $this->sql=new sql_adminRankingPreinsDemanda($configuracion);
            
            $this->formulario="admin_rankingPreinsDemanda";//nombre del bloque que procesa el formulario            
            $this->bloque="preinscripcion/admin_rankingPreinsDemanda";//nombre del bloque que procesa el formulario
            /**
             * Intancia para crear la conexion ORACLE
             */
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");
            /**
             * Instancia para crear la conexion General
             */
            $this->acceso_db=$this->conectarDB($configuracion,"");
            /**
             * Instancia para crear la conexion de MySQL
             */
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");
            /**
             * buscar los datos de ano y periodo actuales
             */
            $cadena_sql = $this->sql->cadena_sql("periodo_activo","");
            $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            $this->ano=$resultado_peridoActivo[0]['ANO'];
            $this->periodo=$resultado_peridoActivo[0]['PERIODO'];

	}
                       
        /**
         * presenta formulario para iniciar el ranking de las inscripciones por demanda
         */
        function presentarFormularioRankingDemanda() {                        
                ?>
                <fieldset>
                   <legend><?echo 'Para todas las facultades';?></legend>
                    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>' id="<? echo $this->formulario ?>">
                          <table>
                                <tr>                                     
                                    <td>
                                        <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                                        <input type="hidden" name="opcion" value="ejecutarRanking">
                                        <input type="submit" value="Ejecutar Ranking Demanda">              
                                    </td>
                                </tr>
                          </table>
                    </form> 
                   <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>' id="<? echo $this->formulario ?>">
          
                         <table>
                                <tr>                                     
                                    <td>
                                        <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                                        <input type="hidden" name="opcion" value="ejecutarClasificacionEstudiante">
                                        <input type="submit" value="Ejecutar clasificación estudiantes">              
                                    </td>
                                </tr>
                                </table>
            
                    </form>  
                   
                     <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>' id="<? echo $this->formulario ?>">

                        <table>
                        <tr>                                     
                            <td>
                                <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                                <input type="hidden" name="opcion" value="cargarHorariosBinarios">
                                <input type="submit" value="Cargar horarios binarios"  onclick="mostrarDiv()">              
                            </td>
                        </tr>
                        </table>
              
                    </form>  
                   <?
                            echo (isset($_REQUEST['mensaje'])?$_REQUEST['mensaje']:'');
                        
                   ?>
                   </fieldset>
                    
                    
                 <?
                
                $tablaRanking=$this->consultarTablaRanking();                
                //$this->presentarTablaRanking($tablaRanking);

              
        }
 
        /**
         * 
         */
        function ejecutarRanking() {
            
            $borrado=$this->vaciarTablaRanking();
            $proyectos=$this->buscarProyectos();
            
            foreach ($proyectos as $proyecto) {
                
                $preinscripciones=  $this->consultarPreinscripciones($proyecto['CODIGO']);    
                if(is_array($preinscripciones)){
                    $espacios=  $this->buscarEspacios($preinscripciones); 
                    $numeroInscritosPorEspacio=  $this->contarPreinscripciones($espacios, $preinscripciones);
                    $rankingPreinscritos=  $this->realizarRankingPreinscritos($numeroInscritosPorEspacio);
                    $posicion=0;
                    foreach ($rankingPreinscritos as $espacio) {
                        $posicion=$posicion+1;
                        $this->insertarRegistros($espacio,$posicion);
                    }
                    
                    
                }else{
                    //echo $proyecto['CODIGO'].' NO TIENE PREINSCRIPCIONES';
                }
                               
                
            }
            $mensaje = "<br>Ejecución de rankin finalizado!!";
               
            $this->redireccionarFormularioInicial($mensaje);
        } 
        
   function buscarProyectos() {
                       
              $variables = array();

              $cadena_sql = $this->sql->cadena_sql("buscarProyectos", $variables);
              $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $resultado;
    }  
        
   function consultarPreinscripciones($codProyecto) {
        
              $variables = array( 
                                    'codProyecto' => $codProyecto,
                                    'ano' => $this->ano,
                                    'periodo' => $this->periodo,
                                );

              $cadena_sql = $this->sql->cadena_sql("consultarPreinscripciones", $variables);
              $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              
              return $resultado;         
        
      }
        
    function buscarEspacios($preinscripciones) {
        
        $espacios=array();

            foreach ($preinscripciones as $preinscripcion) {
                $espacios[]=$preinscripcion['COD_ESPACIO'];                                  
            }

            $listaEspacios=  array_unique($espacios);

            return $listaEspacios;                                            
        
      }
        
     function contarPreinscripciones($espacios, $preinscripciones) {

            foreach ($espacios as $espacio) {
                
                foreach ($preinscripciones as $preinscripcion) {
                    
                    if($espacio==$preinscripcion['COD_ESPACIO']){
                        $resultado[]=1;
                        $nombreEspacio=$preinscripcion['NOMBRE_ESPACIO'];
                        $codProyecto=$preinscripcion['COD_PROYECTO'];
                        $nombreProyecto=$preinscripcion['NOMBRE_PROYECTO'];
                        $cod_Facultad=$preinscripcion['COD_FACULTAD'];
                        $nombre_Facultad=$preinscripcion['NOMBRE_FACULTAD'];
                    }else{}

                }                
                
                $numeroPreinscritos=count($resultado);
                $arregloEspacio[]=array(
                                        'codFacultad'=>$cod_Facultad,
                                        'nombreFacultad'=>$nombre_Facultad,
                                        'codProyecto'=>$codProyecto,
                                        'nombreProyecto'=>$nombreProyecto,
                                        'codEspacio'=>$espacio,
                                        'nombreEspacio'=>$nombreEspacio,
                                        'numeroPreinscritos'=>$numeroPreinscritos);  
                unset($numeroPreinscritos);
                unset($resultado);

            }

            return $arregloEspacio;                                       
        
    }
        
   function realizarRankingPreinscritos($preinscritos) {

        foreach ($preinscritos as $key => $fila) {
            $numeroInscritos[$key]  = $fila['numeroPreinscritos'];             
        }
        
        array_multisort($numeroInscritos, SORT_DESC, $preinscritos);

        return $preinscritos;
        
    }
    
    function redireccionarFormularioInicial() {
        
            $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
            $variable="pagina=admin_rankingPreinsDemanda";
            $variable.="&opcion=ranking";   
            $variable.="&mensaje=".$this->mensaje;

            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
            echo "<script>location.replace('".$pagina.$variable."')</script>";        
        
    }
    
    function insertarRegistros($espacio, $posicion) {

            $datos= array(  
                            'codFacultad'=>$espacio['codFacultad'],
                            'nombreFacultad'=>$espacio['nombreFacultad'],
                            'codProyecto'=>$espacio['codProyecto'],
                            'nombreProyecto'=>$espacio['nombreProyecto'],
                            'codEspacio'=>$espacio['codEspacio'],
                            'nombreEspacio'=>$espacio['nombreEspacio'],
                            'numeroPreinscritos'=>$espacio['numeroPreinscritos'],
                            'posicion'=>$posicion
                        );                   
            

            
            $cadena_sql=$this->sql->cadena_sql("insertarRankingPreinsdemanda",$datos);
            $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"");
            

            return $this->totalAfectados($this->configuracion, $this->accesoGestion);
        
    }
    
    
    function vaciarTablaRanking() {
        
            $cadena_sql=$this->sql->cadena_sql("vaciarTablaRanking",'');
            $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"");
            return $this->totalAfectados($this->configuracion, $this->accesoGestion);
        
    }
    
    function vaciarTablaClasificacionEstudiantes() {
        
            $cadena_sql=$this->sql->cadena_sql("vaciarTablaClasificacionEstudiantes",'');
            $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"");
            return $this->totalAfectados($this->configuracion, $this->accesoGestion);
        
    }
    
    function vaciarTablaHorariosBinarios() {
        
            $cadena_sql=$this->sql->cadena_sql("vaciarTablaHorariosBinarios",'');
            $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"");
            return $this->totalAfectados($this->configuracion, $this->accesoGestion);
        
    }
    
   
    function consultarTablaRanking() {
        
              $variables = array( 
                                    
                                );

              $cadena_sql = $this->sql->cadena_sql("consultarTablaRanking", $variables);
              $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
              
              return $resultado;           
        
    }
    
    function consultarFacultadesTabla() {
        $cadena_sql = $this->sql->cadena_sql("consultarFacultadesTablaInscripciones", '');
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        return $resultado;
        
    }
    
    function consultarProyectosTabla($facultad) {
        $cadena_sql = $this->sql->cadena_sql("consultarProyectosTablaInscripciones", $facultad);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        return $resultado;
        
    }
    
/**
*Función que clasifica los estudiantes en un ranking para la ejecución de las inscripciones. 
*/
   function cargarHorariosBinarios(){
           ?> <head>
                <script language="javascript">
                //Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
                function callprogressHora(vValor,vItem,vTotal,Fac){
                 document.getElementById("getprogress").innerHTML = 'Verificando Horarios de '+Fac+': '+vItem+' de '+vTotal+' horarios.  '+vValor+'&nbsp;%';
                 document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
                }
                </script>
                <style type="text/css">
                /* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
                  .ProgressBar     { width: 70%; border: 1px solid black; background: #eef; height: 1.25em; display: block; margin-left: auto;margin-right: auto }
                  .ProgressBarText { position: absolute; font-size: 1em; width: 35em; text-align: center; font-weight: normal; }
                  .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible; }
                </style>
            </head>
            <body>
            <!-- Ahora creo la barra de progreso con etiquetas DIV -->
             <div class="ProgressBar">
                  <div class="ProgressBarText"><span id="getprogress"></span></div>
                  <div id="getProgressBarFill"></div>
                </div>
            </body><?       
       $borrado=$this->vaciarTablaHorariosBinarios();
       $resultadoHorarios =$this->horariosBinario->ConsultarHorarios();
       $this->mensaje.="Total horarios Binarios registrados: ".$resultadoHorarios;
       $this->redireccionarFormularioInicial($this->mensaje);
      
    }
    
    /**
     * Funcion que permite ejecutar ranking de estudiantes para una facultad
     * @param type $facultad
     */
    function rankingEstudiantesPorFacultad($facultad) {
        $registradosFacultad=0;
        $proyectos=$this->consultarProyectosTabla($facultad);
        foreach ($proyectos as $proyecto) {
            $registradosFacultad+=$this->rankingEstudiantesPorProyecto($proyecto['PROYECTO']);
        }
        $this->mensaje.="Total Estudiantes Facultad ".$facultad.": ".$registradosFacultad.".<br>";
        return $registradosFacultad;
    }
    
    /**
     * Permite ejecutar ranking de estudiantes pro proyecto
     * @param type $proyecto
     */
    function rankingEstudiantesPorProyecto($proyecto) {
       $registradosProyecto=$this->clasificacion->ejecutarClasificacionEstudiantes($proyecto);
       $this->mensaje.="Total Proyecto ".$proyecto.": ".$registradosProyecto.".<br>";
       return $registradosProyecto;
        
    }
    
    /**
*Función que clasifica los estudiantes en un ranking para la ejecución de las inscripciones. 
*/
   function ejecutarEstudiantes(){
        $registrados=0;
        $borrado=$this->vaciarTablaClasificacionEstudiantes();
        $this->mensaje='';
        $facultades=$this->consultarFacultadesTabla();
        if(is_array($facultades)&&!empty($facultades))
        {
        foreach ($facultades as $facultad)
        {
            $registrados+=$this->rankingEstudiantesPorFacultad($facultad['FACULTAD']);
        }
        }else
            {
                $this->mensaje.="<br><br>No hay datos de estudiantes para realizar clasificacion.";
            }
        $this->mensaje.="<br><br>Cantidad de estudiantes clasificados e insertados en la tabla ".$registrados;
       $this->redireccionarFormularioInicial();
    }
    
}
?>
