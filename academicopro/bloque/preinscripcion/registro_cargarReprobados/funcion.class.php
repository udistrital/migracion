<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");

/*
 *@ Esta clase presenta los espacios academicos que se pueden inscribir a un estudiante de Creditos.
 */

class funcion_registroCargarReprobados extends funcionGeneral {      //Crea un objeto tema y un objeto SQL.

  private $configuracion;
  private $ano;
  private $periodo;
  private $estudiantes;
  private $asignaturas;
  private $usuario;


function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/procedimientos.class.php");

    $this->configuracion = $configuracion;
    $this->cripto = new encriptar();
    $this->procedimientos=new procedimientos();
    //$this->tema = $tema;
    $this->sql = $sql;
   $this->requisitosCreditos=array();
   $this->requisitosHoras=array();
   $this->espaciosEquivalentes=array();
   $this->carreras=array();

    //Conexion ORACLE
    $this->accesoOracle = $this->conectarDB($configuracion, "estudianteCred");

    //Conexion Distribuida - se evalua la variable $configuracion['dbdistribuida']
    //donde si es =1 la conexion la realiza a Mysql, de lo contrario la realiza a ORACLE
    if($configuracion["dbdistribuida"]==1){
        $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
    }else{
        $this->accesoMyOracle = $this->accesoOracle;
    }
    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Datos de sesion
    $this->formulario = "admin_buscarGruposEstudianteCreditos";
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    $cadena_sql = $this->sql->cadena_sql("periodoActivo", '');
    $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
    $this->anos = $resultado_periodo[0]['ANO'];
    $this->periodos = $resultado_periodo[0]['PERIODO'];
    ?>
    <head>
        <script language="JavaScript">
            var message = "";
            function clickIE(){
                if (document.all){
                    (message);
                    return false;
                }
            }
            function clickNS(e){
                if (document.layers || (document.getElementById && !document.all)){
                    if (e.which == 2 || e.which == 3){
                        (message);
                        return false;
                    }
                }
            }
            if (document.layers){
                document.captureEvents(Event.MOUSEDOWN);
                document.onmousedown = clickNS;
            } else {
                document.onmouseup = clickNS;
                document.oncontextmenu = clickIE;
            }
            document.oncontextmenu = new Function("return false")
        </script>
    </head>
    <?
  }

    /**
     * Funciom que busca los datos básicos del estudiante, los espacios por cursar, requisitos, equivalencias parametros del plan y los registra en la tabla de carga.
     * Utiliza los metodos consultarDatosCarreras, consultarDatosEstudiantes, buscarNotaAprobatoria, consultarEspaciosPlanEstudio, consultarNotasEstudiantes, ConsultarRequisitos,
     *          consultarEspaciosEquivalentes, consultarParametros, buscarDatosCarreraEstudiante, buscarEspaciosCursados, buscarPlanEstudiosEstudiante,
     *          buscarParametrosPlanEstudianteHoras, buscarParametrosPlanEstudianteCreditos, crearArregloEstudiante, buscarEspaciosPorCursar,
     *          buscarRequisitosPlanEstudios, buscarNombreRequisitos, buscarObservaciones, buscarParametrosEstudiante, evaluarParametros, buscarEquivalencias,
     *          colocarObservaciones, registrarArregloEstudiantes
     */
    function consultarEspaciosPermitidos() {
            
            $codFacultad=$_REQUEST['facultad'];
            $totalEstudiantes=0;
            $this->carreras=$this->consultarDatosCarreras($codFacultad);  
        foreach ($this->carreras as $proyectos)
          {
            $codigoProyecto=$proyectos['COD_CARRERA'];
            $estudiantes=$this->consultarDatosEstudiantes($codigoProyecto);
                foreach ($estudiantes as $estudiante)
                {
                    $creditosReprobados=json_decode($estudiante['ESPACIOS_POR_CURSAR'],true);   
                   if(is_array($creditosReprobados)){
                    foreach ($creditosReprobados as $reprobados)
                    {
                        $creditos=$reprobados['REPROBADO'];                      
                          if($creditos==1)
                           {
                              $this->clasificacionReprobados($reprobados);
                              $arreglo[]=$this->crearArregloReprobados($estudiante,$reprobados);
                              $estudiantesReprobados[]=array('codEstudiante'=>$estudiante['CODIGO'],
                                                            'codEspacio'=>$reprobados['CODIGO']);
                           }
                    }
                    if (is_array(isset($arreglo)?$arreglo:'')&&isset($arreglo[0]['codEspacio']))
                    {
                        $this->registrarArregloReprobados($arreglo);
                        $totalEstudiantes++;
                    }
                    unset($arreglo);
             }
             
         }
       }
          echo $totalEstudiantes;exit;
             
    }

 /**
  *Funcion que permite consultar los despacios reprobados por los estudiantes.
  * @param type $codEstudiante
  * @return type 
  */
   
  function consultarDatosEstudiantes($codProyecto){
        $variables=array('codProyecto'=>$codProyecto);
        $cadena_sql=$this->sql->cadena_sql("consultarDatosEstudiantes", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        return $resultado;
    }
    
function clasificacionReprobados($espacio){
            $respuesta=(isset($espacio['CLASIFICACION'])?$espacio['CLASIFICACION']:''); 
            $letras=array('OB','OC','EI','EE','CP');
            $numeros=array(1,2,3,4,5);
            $this->clasificacion=str_replace($letras,$numeros,trim(isset($respuesta)?$respuesta:''));
}
    
 function crearArregloReprobados($datosEstudiante,$reprobado){
     $variable=array('ano'=>$this->anos,
                            'periodo'=>$this->periodos,
                            'codEstudiante'=>$datosEstudiante['CODIGO'],
                            'codEspacio'=>$reprobado['CODIGO'],
                            'codProyectoEstudiante'=>$datosEstudiante['COD_CARRERA'],
                            'creditos'=>(isset($reprobado['CREDITOS'])?$reprobado['CREDITOS']:''),
                            'htd'=>$reprobado['HTD'],
                            'htc'=>$reprobado['HTC'],
                            'hta'=>$reprobado['HTA'],
                            'cea'=>$this->clasificacion,
                            'sem'=>$reprobado['NIVEL'],
                            'perdido'=>'S');              
     return $variable;
     
 }
    
    /**
     * Funcion que registra el arreglo los espacios reprobados por un estudiante
     * @param type $arregloEstudiante
     */
    function registrarArregloReprobados($arregloEstudiante){        

        foreach ($arregloEstudiante as $estudiante)
        {
            $cadena_sql_adicionar=$this->sql->cadena_sql("insertarRegistroDatosEstudiante",$estudiante);
            $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");  
            //return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        }

    }
    
    /**
    * Funcion que consulta la información de las carreras de una facultad
     * @param type $codFacultad
     * @return type
    */ 
    function consultarDatosCarreras($codFacultad) {        
        $variables=array('codFacultad'=>$codFacultad);
        $cadena_sql = $this->sql->cadena_sql("consultarDatosCarreras", $variables); 
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");       
        return $resultado;
    }

      
  }
?>