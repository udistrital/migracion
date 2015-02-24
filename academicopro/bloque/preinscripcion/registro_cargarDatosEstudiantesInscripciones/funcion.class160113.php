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

class funcion_registroCargarDatosEstudiantesInscripciones extends funcionGeneral {      //Crea un objeto tema y un objeto SQL.

  private $configuracion;
  private $ano;
  private $periodo;
  private $espaciosCancelados;
  private $espaciosPlanEstudio;
  private $parametrosCreditos;
  private $parametrosHoras;
  private $estudiantes;
  private $notas;
  private $requisitosCreditos;
  private $requisitosHoras;
  private $espaciosEquivalentes;
  private $carreras;
  private $total;
  private $totalEstudiantes;
  private $estudiantesSinEspacios;



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
    $this->accesoOracle = $this->conectarDB($configuracion, "oraclesga");

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
    $this->formulario = "registro_inscripcionesPrecarga";
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    $cadena_sql = $this->sql->cadena_sql("periodoActivo", ''); 
    $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
    $this->ano = $resultado_periodo[0]['ANO'];
    $this->periodo = $resultado_periodo[0]['PERIODO'];

  }

    /**
     * Funciom que busca los datos básicos del estudiante, los espacios por cursar, requisitos, equivalencias parametros del plan y los registra en la tabla de carga.
     * Utiliza los metodos consultarDatosCarreras, consultarDatosEstudiantes, buscarNotaAprobatoria, consultarEspaciosPlanEstudio, consultarNotasEstudiantes, ConsultarRequisitos,
     *          consultarEspaciosEquivalentes, consultarParametros, buscarDatosCarreraEstudiante, buscarEspaciosCursados, buscarPlanEstudiosEstudiante,
     *          buscarParametrosPlanEstudianteHoras, buscarParametrosPlanEstudianteCreditos, crearArregloEstudiante, buscarEspaciosPorCursar,
     *          buscarRequisitosPlanEstudios, buscarNombreRequisitos, buscarObservaciones, buscarParametrosEstudiante, evaluarParametros, buscarEquivalencias,
     *          colocarObservaciones, registrarArregloEstudiantes
     */
    function ejecutarCargaPorFacultad() {
        //Verifica codigo de facultad
        $codFacultad=(isset($_REQUEST['facultad'])?$_REQUEST['facultad']:'');
        if($codFacultad=='')
        {
            echo "No seleccionó facultad";exit;
        }
        //Consulta carreras de la facultad
        $this->carreras=$this->consultarDatosCarreras($codFacultad);
        $this->totalEstudiantes=0;
        $this->consultarRequisitos();    
        $this->consultarParametros();
        $this->consultarEspaciosCancelados();
        $this->espaciosEquivalentes=$this->consultarEspaciosEquivalentes();
        echo $this->carreras[0]['NOMBRE_FACULTAD']."<br>";
        //ejecuta proceso por cada proyecto
        foreach ($this->carreras as $proyecto)
        {
            $this->ejecutarCargaPorProyecto($proyecto);
        }
        //Final del reporte
        echo "TOTAL ESTUDIANTES: ".$this->totalEstudiantes."<br>";
        $this->volver();
    }
    
    /**
     * Fncion que ejecuta la carga de datos por proyecto
     * @param type $proyectos
     */
    function ejecutarCargaPorProyecto($proyectos) {
            $this->estudiantesSinEspacios=0;
            $codigoProyecto=$proyectos['CARRERA'];

            $this->estudiantes=$this->consultarDatosEstudiantes($codigoProyecto);

            $notaAprobatoria=$this->buscarNotaAprobatoria($codigoProyecto);

            $this->espaciosPlanEstudio=$this->consultarEspaciosPlanEstudio($codigoProyecto);
            
            $this->escaparCaracteresEspacios();

            $this->notas=$this->consultarNotasEstudiantes($codigoProyecto);
            $numeroEstudiantesProyecto=0;
            //verificar cuando un proyecto no tiene estudiantes
            if (is_array($this->estudiantes)&&isset($this->estudiantes[0]['CARRERA']))
            {
                $this->total=0;
                foreach ($this->estudiantes as $estudiante)
                {
                    $numeroEstudiantesProyecto++;
                    $this->ejecutarCargaPorEstudiante($estudiante, $notaAprobatoria);

                }
                echo "<br>".$proyectos['CARRERA']." - ".$proyectos['NOMBRE_CARRERA'].": ".$numeroEstudiantesProyecto." Estudiantes.<br>";
                echo "ESTUDIANTES SIN ESPACIOS POR CURSAR: ".$this->estudiantesSinEspacios."<br>";                
                $this->totalEstudiantes+=$numeroEstudiantesProyecto;
                
        echo "TOTAL NO REGISTRADOS: ".  $this->total."<br>";
                
            }else
                {
                    echo "<br>".$proyectos['CARRERA']." - ".$proyectos['NOMBRE_CARRERA'].":0<br>";
                }
                unset($this->estudiantes);
                unset($this->espaciosPlanEstudio);
                unset($this->notas);        
    }    
    
    /**
     * Funcion que ejecuta la carga de datos por estudiante
     * @param type $estudiante
     * @param type $notaAprobatoria
     */
    function ejecutarCargaPorEstudiante($estudiante,$notaAprobatoria) {
                    $arregloEstudiante=array();        
                    if (trim($estudiante['TIPO_ESTUD'])=='N'&& strlen(trim((isset($estudiante['PLAN'])?$estudiante['PLAN']:'')))>2)
                    {
                        echo "Codigo: ".$estudiante['CODIGO']." Tipo: ".$estudiante['TIPO_ESTUD']." Plan: ".(isset($estudiante['PLAN'])?$estudiante['PLAN']:'')."<BR>";
                    }
                    if (trim($estudiante['TIPO_ESTUD'])=='S'&& strlen(trim((isset($estudiante['PLAN'])?$estudiante['PLAN']:'')))<3)
                    {
                        echo "Codigo: ".$estudiante['CODIGO']." Tipo: ".$estudiante['TIPO_ESTUD']." Plan: ".(isset($estudiante['PLAN'])?$estudiante['PLAN']:'')."<BR>";
                    }
                    if (trim($estudiante['TIPO_ESTUD'])==''||is_null((isset($estudiante['PLAN'])?$estudiante['PLAN']:''))||trim((isset($estudiante['PLAN'])?$estudiante['PLAN']:''))==''||is_null($estudiante['TIPO_ESTUD']))
                    {
                        echo "Codigo: ".$estudiante['CODIGO']." Tipo: ".$estudiante['TIPO_ESTUD']." Plan: ".(isset($estudiante['PLAN'])?$estudiante['PLAN']:'')."<BR>";
                    }
                    $datosAdicionalesEstudiante=$this->buscarDatosCarreraEstudiante($estudiante['CARRERA']);
                    //busca las notas del estudiante
                    $espaciosCursados=$this->buscarEspaciosCursados($estudiante['CODIGO'],$notaAprobatoria);
                    //crea arreglo de espacios aprobados
                    $espaciosAprobados=$espaciosCursados['aprobados'];
                    //crea arreglo de espacios reprobados
                    $espaciosReprobados=$espaciosCursados['reprobados'];
                    //busca los espacios del plan de estudios del estudiante
                    $planEstudiosEstudiante=$this->buscarPlanEstudiosEstudiante((isset($estudiante['PLAN'])?$estudiante['PLAN']:''));
                    //Cuando el estudiante no tiene plan de estudios
                    if (!is_array($planEstudiosEstudiante))
                    {
                        if($estudiante['TIPO_ESTUD']=='N')
                        {
                            $parametrosPlanEstudiante=$this->buscarParametrosPlanEstudianteHoras($estudiante['CARRERA']);
                            $cadenaParametrosPlan=json_encode($parametrosPlanEstudiante);
                            $cadenaCreditosAprobados='';
                            echo "Sin espacios por cursar: ".$estudiante['CODIGO']." Plan: ".(isset($estudiante['PLAN'])?$estudiante['PLAN']:'Sin Plan')."<br>";
                            $this->estudiantesSinEspacios++;
                        }else
                            {
                                $parametrosPlanEstudiante=$this->buscarParametrosPlanEstudianteCreditos($estudiante['PLAN']);
                                $cadenaParametrosPlan=json_encode($parametrosPlanEstudiante);
                                $parametrosEstudiante=$this->buscarParametrosEstudiante('',$estudiante['TIPO_ESTUD'],$espaciosAprobados, $estudiante['PLAN']);
                                $cadenaCreditosAprobados=json_encode($parametrosEstudiante);
                                echo "Sin espacios por cursar: ".$estudiante['CODIGO']." Plan: ".(isset($estudiante['PLAN'])?$estudiante['PLAN']:'Sin Plan')."<br>";
                                $this->estudiantesSinEspacios++;
                            }
                        $arregloEstudiante=$this->crearArregloEstudiante($estudiante,$datosAdicionalesEstudiante,'','','',$cadenaParametrosPlan,$cadenaCreditosAprobados,'');
                        $this->registrarArregloEstudiantes($arregloEstudiante);
                    }else
                        {
                            //Hace espacios del plan menos los aprobados
                            $espaciosPorCursar=$this->buscarEspaciosPorCursar($planEstudiosEstudiante,$espaciosAprobados);

                            //Si el estudiante aun tiene espacios por cursar
                            if(is_array($espaciosPorCursar))
                            {
                                //Devuelve los espacios que no cumplen con requisitos
                                $requisitosEspacios=$this->buscarRequisitosPlanEstudios($estudiante['PLAN'],$estudiante['CARRERA'],$espaciosPorCursar,$espaciosAprobados,$estudiante['TIPO_ESTUD']);
                                //Coloca el nombre de los requisitos
                                $requisitosEspacios=$this->buscarNombreRequisitos($planEstudiosEstudiante,$requisitosEspacios);
                                //Coloca observaciones a los espacios por cursar que no cumplen con requisitos
                                $observacionesRequisitos=$this->buscarObservaciones($requisitosEspacios,$espaciosPorCursar,'REQUISITOS');
                                //marcar espacios reprobados
                                $observacionesReprobados=$this->buscarObservaciones($espaciosReprobados, $espaciosPorCursar, 'REPROBADO');
                                //Busca los parametros del plan de estudios del estudiante
                                $parametrosEstudiante=$this->buscarParametrosEstudiante($espaciosPorCursar,$estudiante['TIPO_ESTUD'],$espaciosAprobados, $estudiante['PLAN']);
                                //Buscar espacios cancelados
                                $espaciosCancelados=$this->buscarEspaciosCancelados($espaciosPorCursar,$estudiante['CODIGO']);
                                //Para los estudiantes de horas
                                if($estudiante['TIPO_ESTUD']=='N')
                                {
                                    //Filtra los espacios que no cumplen con el maximo de semestres
                                    $espaciosaMostrar=$this->evaluarParametros($parametrosEstudiante,$espaciosPorCursar,$estudiante['CARRERA']);
                                    //Coloca observaciones a los espacios por cursar que no cumplen con niveles
                                    $observacionesNiveles=$this->buscarObservaciones($espaciosaMostrar['No cumplen parametros'],$espaciosPorCursar,'NIVELES');
                                    //Busca equivalencias de los espacios por cursar
                                    $equivalencias=$this->buscarEquivalencias($espaciosPorCursar,$estudiante['CARRERA']);
                                    //Crea cadena de espacios equivalentes
                                    $cadenaEquivalencias=json_encode($equivalencias,JSON_UNESCAPED_UNICODE);
                                    //crea arreglo parametros del plan de estudios del estudiante
                                    $parametrosPlanEstudiante=$this->buscarParametrosPlanEstudianteHoras($estudiante['CARRERA']);
                                    $cadenaParametrosPlan=json_encode($parametrosPlanEstudiante);
                                    $cadenaCreditosAprobados='';

                                }else
                                    {
                                        $cadenaEquivalencias='';
                                        $observacionesNiveles='';
                                        $parametrosPlanEstudiante=$this->buscarParametrosPlanEstudianteCreditos($estudiante['PLAN']);
                                        $cadenaParametrosPlan=json_encode($parametrosPlanEstudiante);
                                        $cadenaCreditosAprobados=json_encode($parametrosEstudiante);
                                    }
                                //Crear arreglo de espacios por cursar con observaciones
                                $espaciosPorCursar=$this->colocarObservaciones($espaciosPorCursar,$observacionesRequisitos,$observacionesNiveles,$observacionesReprobados);
                                //Crea cadena de espacios por cursar
                                $cadenaEspaciosPorCursar=json_encode($espaciosPorCursar,JSON_UNESCAPED_UNICODE);
                                //Crea cadena de los requisitos para el estudiante
                                $cadenaRequisitos=json_encode($requisitosEspacios,JSON_UNESCAPED_UNICODE);
                                //Crea cadena de spacios cancelados
                                $cadenaCancelados=json_encode($espaciosCancelados);
                                //crea arreglo por estudiante
                                $arregloEstudiante=$this->crearArregloEstudiante($estudiante,$datosAdicionalesEstudiante,$cadenaEspaciosPorCursar,$cadenaEquivalencias,$cadenaRequisitos,$cadenaParametrosPlan,$cadenaCreditosAprobados,$cadenaCancelados);
                                $this->registrarArregloEstudiantes($arregloEstudiante);
                            }else
                                {
                                    echo "Sin espacios por cursar: ".$estudiante['CODIGO']." Plan: ".(isset($estudiante['PLAN'])?$estudiante['PLAN']:'Sin Plan')."<br>";
                                    $this->estudiantesSinEspacios++;
                                    if($estudiante['TIPO_ESTUD']=='N')
                                    {
                                        $parametrosPlanEstudiante=$this->buscarParametrosPlanEstudianteHoras($estudiante['CARRERA']);
                                        $cadenaParametrosPlan=json_encode($parametrosPlanEstudiante);
                                        $cadenaCreditosAprobados='';
                                    }else
                                        {
                                            $parametrosEstudiante=$this->buscarParametrosEstudiante('',$estudiante['TIPO_ESTUD'],$espaciosAprobados, $estudiante['PLAN']);
                                            $parametrosPlanEstudiante=$this->buscarParametrosPlanEstudianteCreditos($estudiante['PLAN']);
                                            $cadenaParametrosPlan=json_encode($parametrosPlanEstudiante);
                                            $cadenaCreditosAprobados=json_encode($parametrosEstudiante);
                                        }
                                    $arregloEstudiante=$this->crearArregloEstudiante($estudiante,$datosAdicionalesEstudiante,'','','',$cadenaParametrosPlan,$cadenaCreditosAprobados,'');
                                    $this->registrarArregloEstudiantes($arregloEstudiante);
                                }
                        }
                        unset($arregloEstudiante);
    }
    
    
    /**
     * Funcion que permite borrar datos de la tabla de carga de inscripciones
     */
    function borrarDatosPrecarga($variables) {
        $total=$this->borrarDatosTablaPrecarga($variables);
        return $total;
    }
    
    /**
     * Funcion que permite borrar datos de la tabla de carga de inscripciones
     */
    function borrarDatosFacultad() {
        $variables=array('CAMPO'=>'ins_fac_cod',
                        'VALOR_CAMPO'=>$_REQUEST['codFacultad']);
        $borradas=$this->borrarDatosPrecarga($variables);
        echo "Se han borrado ".$borradas." registros de la facultad ".$_REQUEST['codFacultad'];
        $this->volver();
        exit;
    }
    
    /**
     * Funcion que permite borrar datos de la tabla de carga de inscripciones
     */
    function cargarDatosProyecto() {
        //verifica el codigo de proyecto
        $codProyecto=(isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'');
        if($codProyecto=='')
        {
            echo "No seleccionó proyecto";exit;
        }
        //borra los registros de estudiantes del proyecto
        $variables=array('CAMPO'=>'ins_est_cra_cod',
                        'VALOR_CAMPO'=>$_REQUEST['codProyecto']);
        $borradas=$this->borrarDatosPrecarga($variables);
        echo "Se han borrado ".$borradas." registros de ";
        //consulta datos delproyecto
        $this->carreras=$this->consultarDatosProyecto($codProyecto);
        echo $this->carreras[0]['NOMBRE_CARRERA'].".<br>";
        $this->totalEstudiantes=0;
        $this->consultarRequisitos();    
        $this->consultarParametros();
        $this->consultarEspaciosCancelados();
        $this->espaciosEquivalentes=$this->consultarEspaciosEquivalentes();
        //ejecuta la carga para el proyecto
        $this->ejecutarCargaPorProyecto($this->carreras[0]);
        //finaliza reporte
        echo "TOTAL ESTUDIANTES: ".$this->totalEstudiantes."<br>";
        $this->volver();
        exit;
    }
    
    /**
     * Funcion que permite realizar la carga de datos de inscripciones por estudiante
     * @param type $$_REQUEST['codEstudiante']
     */
    function cargarDatosEstudiante() {
        //verifica el codigo de estudiante
        $codEstudiante=$_REQUEST['codEstudiante'];
        if($codEstudiante==''||!is_numeric($codEstudiante))
        {
            echo "Digite un c&oacute;digo de estudiante v&aacute;lido.";exit;
        }
        //consulta datos del estudiante
        $estudiante=$this->consultarDatosEstudiante($codEstudiante);
        if(!is_array($estudiante)||empty($estudiante))
        {
            echo "El estado del estudiante no le permite realizar inscripci&oacute;n.";
            exit;
        }
        //borra registro de carga del estudiante
        $variables=array('CAMPO'=>'ins_est_cod',
                        'VALOR_CAMPO'=>$codEstudiante);
        $borradas=$this->borrarDatosPrecarga($variables);
        if ($borradas>0)
        {
            echo "Se han borrado los datos del estudiante c&oacute;digo ".$_REQUEST['codEstudiante']." de ";
        }
        $codProyecto=$estudiante['CARRERA'];
        //consulta datos del proyecto del estudiante
        $this->carreras=$this->consultarDatosProyecto($codProyecto);
        echo $this->carreras[0]['NOMBRE_CARRERA'].".<br>";
        $proyectos=$this->carreras[0];
        $this->totalEstudiantes=0;
        $this->consultarRequisitos();    
        $this->consultarParametros();
        $this->consultarEspaciosCancelados();
        $this->espaciosEquivalentes=$this->consultarEspaciosEquivalentes();
        
        $arregloEstudiante=array();
        $this->estudiantesSinEspacios=0;
        $codigoProyecto=$proyectos['CARRERA'];
        $notaAprobatoria=$proyectos['NOTA'];

        $this->espaciosPlanEstudio=$this->consultarEspaciosPlanEstudio($codigoProyecto);
        $this->escaparCaracteresEspacios();
        $this->notas=$this->consultarNotasEstudiante($codEstudiante);
        $numeroEstudiantesProyecto=0;
        $this->total=0;
        $numeroEstudiantesProyecto++;
        //ejecuta el proceso de carga para el estudiante
        $this->ejecutarCargaPorEstudiante($estudiante, $notaAprobatoria);
        echo "<br>".$proyectos['CARRERA']." - ".$proyectos['NOMBRE_CARRERA'].": ".$numeroEstudiantesProyecto." Estudiante.<br>";
        echo "ESTUDIANTES SIN ESPACIOS POR CURSAR: ".$this->estudiantesSinEspacios."<br>";                
        $this->totalEstudiantes+=$numeroEstudiantesProyecto;
        //Reporte
        echo "TOTAL NO REGISTRADOS: ".  $this->total."<br>";
        unset($this->estudiantes);
        unset($this->espaciosPlanEstudio);
        unset($this->notas);
        $this->volver();
        exit;
    }
    
    /**
     * Funcion que presenta boton para retornar a la pagina inicial de carga
     */
    function volver() {
        ?>
            <table>
                <tr>                  
                    <td>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>' id="<? echo $this->formulario ?>">
                            <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                            <input type="hidden" name="opcion" value="mostrarProyectos">
                            <input type="submit" value="Volver">                        
                        </form>       
                    </td>
                </tr>
            </table>

            <?        
    }

    /**
    * Funcion que busca la nota con la que un estudiante aprueba un espacio academico en su proyecto.
    * @return type 
    */ 
    function buscarNotaAprobatoria($codProyecto) {
        foreach ($this->carreras as $carrera) {
            if($carrera['CARRERA']==$codProyecto)
            {
                $notaAprobatoria=$carrera['NOTA'];
                break;
            }
        }
        return $notaAprobatoria;
    }
   
    /**
     *Funcion que busca los requisitos de los planes de estudios de horas y de los planes de estudios de creditos
     * utiliza los metodos consultarRequisitosCreditos, consultarRequisitoshoras
     * @return array retorna arreglo con dos subarreglos 'creditos' y 'horas' 
     */
    function consultarRequisitos() {
        $this->requisitosCreditos=$this->consultarRequisitosCreditos();
        $this->requisitosHoras=$this->consultarRequisitosHoras();
    }
  
    /**
     *
     * @param type $codProyecto
     * @param type $codPlan
     * @return type 
     */
    function consultarParametros(){
        $this->parametrosCreditos=$this->consultarParametrosCreditos();
        $this->parametrosHoras=$this->consultarParametrosHoras();
    }

    /**
     * Funcion que permite buscar datos adicionales de la carrera del estudiante
     * @param type $carrera
     * @return type
     */
    function buscarDatosCarreraEstudiante($carrera) {
        foreach ($this->carreras as $carreras) {
            if ($carreras['CARRERA']==$carrera)
            {
                $datosAdicionales=$carreras;
                break;
            }
        }
        return$datosAdicionales;
    }    
    
    /**
     *Funcion que busca los espacios aprobados y reprobados de un estudiante.
     * @param string $codEstudiante
     * @return array
     */   
    function buscarEspaciosCursados($codEstudiante,$notaAprobatoria) {
        $espaciosAprobados=array();
        $espaciosReprobados=array();
        if(is_array($this->notas)&&isset($this->notas[0]['PROYECTO']))
        {
            foreach ($this->notas as $nota)
            {
                if($nota['COD_ESTUDIANTE']==$codEstudiante)
                {
                    if($nota['NOTA']>=$notaAprobatoria||$nota['OBSERVACION']==19||$nota['OBSERVACION']==22)
                    {
                        $espaciosAprobados[]=$nota;
                    }else
                        {
                            $espaciosReprobados[]=$nota;
                        }
                }
            }
        }else
            {
                $resultado=array('aprobados'=>'','reprobados'=>'');
            }
        $resultado=array('aprobados'=>$espaciosAprobados,'reprobados'=>$espaciosReprobados);
        return $resultado;
    }
    
   /**
    *Funcion que busca los códigos del los espacios académicos del plan de estudios.
    * @param string $codigoPlanEstudios
    * @return array
    */
    function buscarPlanEstudiosEstudiante($codigoPlanEstudios) {
        if($codigoPlanEstudios==''||is_null($codigoPlanEstudios))
        {
            $planEstudiosEstudiante='';
        }else
            {
                foreach ($this->espaciosPlanEstudio as $espacios)
                {
                    if($espacios['PLAN']==$codigoPlanEstudios && $espacios['NIVEL']!=0 )
                    {
                        $planEstudiosEstudiante[]=$espacios;
                    }
                }
                if(!isset($planEstudiosEstudiante))
                {
                    $planEstudiosEstudiante='';
                }
            }
        return $planEstudiosEstudiante;
    }

    /**
     * Funcion que busca los parametros de un plan de estudios de horas
     * @param type $carrera
     */
    function buscarParametrosPlanEstudianteHoras($carrera) {
        foreach ($this->parametrosHoras as $parametro) {
            if($parametro['CARRERA']==$carrera)
            {
                $parametros=array('total'=>'',
                    'OB'=>'',
                    'OC'=>'',
                    'EI'=>'',
                    'EE'=>'',
                    'CP'=>'',
                    'maxcreditos'=>'',
                    'maxespacios'=>$parametro['MAXIMO_ASIGNATURAS'],
                    'maxniveles'=>$parametro['SEMESTRES']);
            }
            if(!isset($parametros))
            {
                $parametros='';
            }
        }
        return $parametros;
    }    
    
    /**
     * Funcion que busca los parametros de un plan de estudios de creditos
     * @param type $plan
     */
    function buscarParametrosPlanEstudianteCreditos($plan) {
        
        $parametros="";
        foreach ($this->parametrosCreditos as $parametro) {
            if($parametro['PLAN']==$plan)
            {
                $parametros=array('total'=>$parametro['TOTAL'],
                    'OB'=>$parametro['OB'],
                    'OC'=>$parametro['OC'],
                    'EI'=>$parametro['EI'],
                    'EE'=>$parametro['EE'],
                    'CP'=>$parametro['CP'],
                    'maxcreditos'=>$parametro['CREDITOS_NIVEL'],
                    'maxespacios'=>'',
                    'maxniveles'=>'');
            }
            if(!isset($parametros))
            {
                $parametros='';
            }
        }
        return $parametros;
    }    
    
    /**
     * Funcion que escapa las comillas en los nombres de los espacios
     */
    function escaparCaracteresEspacios() {
        if(is_array($this->espaciosPlanEstudio))
        {
            foreach ($this->espaciosPlanEstudio as $clave => $espacio) {
                $this->espaciosPlanEstudio[$clave]['NOMBRE']=$this->escaparCaracteresNombre($this->espaciosPlanEstudio[$clave]['NOMBRE']);
                $this->espaciosPlanEstudio[$clave][1]=$this->escaparCaracteresNombre($this->espaciosPlanEstudio[$clave][1]);
            }
        }
    }
    
    /**
     * Funcion que escapa las comillas en una cadena dada
     * @param type $nombre
     * @return type
     */
    function escaparCaracteresNombre($nombre) {
            $nombre=str_replace('"', "'",$nombre);
            $nombre=str_replace("'", "/'",$nombre);
            $nombre=trim($nombre);
            return $nombre;
    }
    
    /**
     * Funcion que elimina / de una cadena
     * @param type $cadena
     * @return type
     */
    function escaparSlashes($cadena) {
            $cadena=str_replace("/", "",$cadena);
            return $cadena;
    }
    
    /**
     * Funcion que halla la diferencia entre el plan de estudios de un estudiante y los espacios aprobados.
     * @param type $espaciosPlanEstudiante
     * @param type $espaciosAprobados 
     */
     function buscarEspaciosPorCursar($espaciosPlanEstudiante,$espaciosAprobados) {
        $espaciosPlan=$this->extraerColumna($espaciosPlanEstudiante, 'CODIGO');
        if(is_array($espaciosAprobados)&&isset($espaciosAprobados[0]['CODIGO']))
        {
            $codigosAprobados=$this->extraerColumna($espaciosAprobados,'CODIGO');
            $resultadoEspacios=array_diff($espaciosPlan,$codigosAprobados);
        }else
            {
                $resultadoEspacios=$espaciosPlan;
            }
        foreach ($resultadoEspacios as $espacio)
        {
            foreach ($espaciosPlanEstudiante as $espacioPlan) {
                if($espacio==$espacioPlan['CODIGO'])
                {
                    $espacioPlan['REQUISITOS']='1';
                    $espacioPlan['NIVELES']='1';
                    $espacioPlan['REPROBADO']='0';
                    $espaciosPorCursar[]=$espacioPlan;
                }
            }
        }
        unset($espaciosPlan);
        unset($resultadoEspacios);
        unset($codigosAprobados);
        if(!isset($espaciosPorCursar))
        {
            $espaciosPorCursar='';
        }
        return $espaciosPorCursar;
    }

     /**
      *Funcion que permite Buscar si un espacio academico tiene requisitos y evaluarlos.
      * Utiliza los metodos evaluarRequisitosCreditos, evaluarRequisitosHoras
     * @param type $plan
     * @param type $proyectoEstudiante
     * @param type $espaciosPorCursar
     * @param type $espaciosAprobados
     * @param type $tipoEstudiante
     * @return type
      */ 
    function buscarRequisitosPlanEstudios($plan,$proyectoEstudiante,$espaciosPorCursar,$espaciosAprobados,$tipoEstudiante){
        switch ($tipoEstudiante){
            case 'S':
                $requisitos=$this->evaluarRequisitosCreditos($plan, $espaciosAprobados, $espaciosPorCursar);
                break;
            case 'N':
                $requisitos=$this->evaluarRequisitosHoras($proyectoEstudiante, $espaciosAprobados, $espaciosPorCursar);
                break;
            default :
                break;
            }
            return $requisitos;
        }

    /**
     * Funcion que busca los espacios equivalentess para los espacios por cursar del estudiante
     * @param type $espaciosPorCursar
     * @param type $carrera
     * @return type
     */
    function buscarEquivalencias($espaciosPorCursar,$carrera) {
        foreach ($espaciosPorCursar as $key => $espacio)
        {
            foreach ($this->espaciosEquivalentes as $equivalencia) {
                if ((isset($equivalencia['ASI_COD_ANTERIOR'])?$equivalencia['ASI_COD_ANTERIOR']:'')==$espacio['CODIGO']&&$equivalencia['CARRERA']==$carrera)
                {
                    $espacioEquivalente[]=array('CODIGO_ESPACIO'=>$espacio['CODIGO'],
                        'CODIGO_EQUIVALENCIA'=>$equivalencia['CODIGO'],
                        'NOMBRE'=>$this->escaparCaracteresNombre($equivalencia['NOMBRE']),
                        'CLASIFICACION'=>(isset($espacio['CLASIFICACION'])?$espacio['CLASIFICACION']:''),
                        'ELECTIVA'=>(isset($espacio['ELECTIVA'])?$espacio['ELECTIVA']:''),
                        'HTD'=>(isset($espacio['HTD'])?$espacio['HTD']:''),
                        'HTC'=>(isset($espacio['HTC'])?$espacio['HTC']:''),
                        'HTA'=>(isset($espacio['HTA'])?$espacio['HTA']:''),
                        'CREDITOS'=>(isset($espacio['CREDITOS'])?$espacio['CREDITOS']:''),
                        'NIVEL'=>(isset($espacio['NIVEL'])?$espacio['NIVEL']:''));
                }
            }
        }
        if(!isset($espacioEquivalente))
        {
            $espacioEquivalente='';
        }
        return $espacioEquivalente;
    }
         
    /**
    *Funcion que evalua si un esapcio academico cumple con los requisitos de un plan de estudios de creditos.
    * @param type $plan
    * @param type $espaciosAprobados
    * @param type $espaciosPorCursar
    * @return string
    */
    function evaluarRequisitosCreditos ($plan, $espaciosAprobados, $espaciosPorCursar){
        foreach($espaciosPorCursar as $espacio)
        {
            if(is_array($this->requisitosCreditos)){
            foreach ($this->requisitosCreditos as $requisito)
            {
                $numeroRequisitos=0;
                $numeroAprobados=0;
                if ($requisito['COD_PLAN']==$plan&&$espacio['CODIGO']==$requisito['COD_ASIGNATURA'])
                {
                    $numeroRequisitos++;
                    foreach ($espaciosAprobados as $aprobado)
                    {
                        if ($aprobado['CODIGO']==$requisito['COD_REQUISITO'])
                        {
                            $numeroAprobados++;
                        }
                    }
                    if ($numeroAprobados!=$numeroRequisitos)
                    {
                        $noCumple[]=array('CODIGO'=>$espacio['CODIGO'], 'REQUISITO'=>$requisito['COD_REQUISITO']);
                    }
                }
            }
            }
        }
        if (!isset($noCumple))
        {
            $noCumple='';
        }
        return $noCumple;
        }

    /**
    *Funcion que evalua si un esapcio academico cumple con los requisitos de un plan de estudios de horas.
    * @param type $proyecto
    * @param type $espaciosAprobados
    * @param type $espaciosPorCursar
    * @return string
    */
    function evaluarRequisitosHoras ($proyecto, $espaciosAprobados, $espaciosPorCursar){
        foreach($espaciosPorCursar as $espacio)
        {
            foreach ($this->requisitosHoras as $requisito)
            {
                $numeroRequisitos=0;
                $numeroAprobados=0;
                if ($requisito['CARRERA']==$proyecto&&$espacio['CODIGO']==$requisito['COD_ASIGNATURA'])
                {
                    $numeroRequisitos++;
                    foreach ($espaciosAprobados as $aprobado)
                    {
                        if ($aprobado['CODIGO']==$requisito['COD_REQUISITO'])
                        {
                            $numeroAprobados++;
                        }
                    }    
                    if ($numeroAprobados!=$numeroRequisitos)
                    {
                        $noCumple[]=array('CODIGO'=>$espacio['CODIGO'], 'REQUISITO'=>$requisito['COD_REQUISITO']);
                    }
                }
            }
        }
        if (!isset($noCumple))
        {
            $noCumple='';
        }
        return $noCumple;
        }

    /**
     * Funcion que inserta el nombre del espacio requisito
     * @param type $planEstudiosEstudiante
     * @param type $requisitosEspacios
     */
    function buscarNombreRequisitos($planEstudiosEstudiante,$requisitosEspacios) {
        if(is_array($requisitosEspacios))
        {
            foreach ($planEstudiosEstudiante as $plan) {
                foreach ($requisitosEspacios as $clave=>$requisito) {
                    if ($requisito['REQUISITO']==$plan['CODIGO'])
                    {
                        $requisitosEspacios[$clave]['NOMBRE']=$plan['NOMBRE'];
                    }
                }
            }
        }else
        {
            $requisitosEspacios='';
        }
        return $requisitosEspacios;
    }

    /**
     * Funcion que determina si un espacio por cursar ha sido cancelado en el período actual
     * @param type $espaciosPorCursar
     * @param type $codEstudiante
     * @return string
     */
    function buscarEspaciosCancelados($espaciosPorCursar,$codEstudiante) {
        if(is_array($this->espaciosCancelados))
        {
            foreach ($espaciosPorCursar as $espacios) {
                foreach ($this->espaciosCancelados as $cancelados) {
                    if ($espacios['CODIGO']==$cancelados['CODIGO']&&$cancelados['COD_ESTUDIANTE']==$codEstudiante)
                    {
                        $espaciosCancelados[]=$espacios['CODIGO'];
                    }
                }
            }
            
        }
        if (!isset($espaciosCancelados))
        {
            $espaciosCancelados='';
        }
        return $espaciosCancelados;
    }
    
    /**
     * Funcion que determina si un espacio por cursar debe tener observaciones por requisitos o por niveles
     * @param type $parametrosObservaciones
     * @param type $espaciosPorCursar
     * @param type $campo
     * @return string
     */
    function buscarObservaciones($parametrosObservaciones,$espaciosPorCursar,$campo) {
        if(is_array($parametrosObservaciones)&&isset($parametrosObservaciones[0]))
        {
            foreach ($espaciosPorCursar as $espacios) {
                foreach ($parametrosObservaciones as $observacion) {
                    if ($espacios['CODIGO']==$observacion['CODIGO'])
                    {
                        $observacionesEspaciosRequisitos[]=array('CODIGO'=>$espacios['CODIGO'],$campo=>'0');
                    }
                }
            }
        }else
            {
                $observacionesEspaciosRequisitos='';
            }
            if(!isset($observacionesEspaciosRequisitos))
            {
                $observacionesEspaciosRequisitos='';
            }
        return $observacionesEspaciosRequisitos;
    }
    
    /**
     * Funcion que coloca las observaciones de requisitos o nivel a espacios academicos por cursar
     * @param type $espaciosPorCursar
     * @param type $observacionesRequisitos
     * @param type $observacionesNiveles
     * @return string
     */
    function colocarObservaciones($espaciosPorCursar,$observacionesRequisitos,$observacionesNiveles,$observacionesReprobados) {
        foreach ($espaciosPorCursar as $clave=>$espacios)
        {
            if (is_array($observacionesRequisitos))
            {
                foreach ($observacionesRequisitos as $requisitos) {
                    if ($espacios['CODIGO']==$requisitos['CODIGO'])
                        $espaciosPorCursar[$clave]['REQUISITOS']='0';
                }
            }
            if (is_array($observacionesNiveles))
            {
                foreach ($observacionesNiveles as $niveles) {
                    if ($espacios['CODIGO']==$niveles['CODIGO'])
                        $espaciosPorCursar[$clave]['NIVELES']='0';
                }
            }
            if (is_array($observacionesReprobados))
            {
                foreach ($observacionesReprobados as $reprobados) {
                    if ($espacios['CODIGO']==$reprobados['CODIGO'])
                        $espaciosPorCursar[$clave]['REPROBADO']='1';
                }
            }
        }
        return $espaciosPorCursar;
    }    

    /**
    *Funcion que cuenta los creditos aprobados segun la clasificación, ademas de obtener el maximo de niveles
    * que puede cursar un estudiante de horas. 
    * @param type $espaciosPorCursar
    * @param type $tipoEstudiante
    * @param type $espaciosAprobados
    * @param type $plan
    * @param type $estudiante
    * @param type $carrera
    * @return type
    */
    function buscarParametrosEstudiante($espaciosPorCursar, $tipoEstudiante, $espaciosAprobados, $plan) {
        if($tipoEstudiante=='N')
        {
            foreach ($this->espaciosPlanEstudio as $espacio)
            {
                foreach ($espaciosPorCursar as $porCursar)
                {                    
                    if ($espacio['CODIGO']==$porCursar['CODIGO'] && $plan==$espacio['PLAN'] && $espacio['NIVEL']!='0')
                    { 
                        $arreglo_niveles[]=$espacio['NIVEL'];                  
                    }
                }
            }
            sort($arreglo_niveles);
            return $nivelMinimo=$arreglo_niveles[0];
        }else
            {
                $OBEst=0;
                $OCEst=0;
                $EIEst=0;
                $EEEst=0;
                $CPEst=0;
                $totalCreditosEst=0;
                foreach($espaciosAprobados as $aprobados)
                {
                    switch((isset($aprobados['CLASIFICACION'])?$aprobados['CLASIFICACION']:''))
                    {
                        case '1':
                            $OBEst=$OBEst+(isset($aprobados['CREDITOS'])?$aprobados['CREDITOS']:0);
                            break;

                        case '2':
                            $OCEst=$OCEst+(isset($aprobados['CREDITOS'])?$aprobados['CREDITOS']:0);
                            break;

                        case '3':
                            $EIEst=$EIEst+(isset($aprobados['CREDITOS'])?$aprobados['CREDITOS']:0); 
                            break;

                        case '4':
                            $EEEst=$EEEst+(isset($aprobados['CREDITOS'])?$aprobados['CREDITOS']:0);
                            break;

                        case '5':
                            $CPEst=$CPEst+(isset($aprobados['CREDITOS'])?$aprobados['CREDITOS']:0);
                            break;

                        default :
                            break;
                    }
                }
                $totalCreditosEst=$OBEst+$OCEst+$EIEst+$EEEst+$CPEst;
                $resultado=array('total'=>$totalCreditosEst,
                                'OB'=>$OBEst,
                                'OC'=>$OCEst,
                                'EI'=>$EIEst,
                                'EE'=>$EEEst,
                                'CP'=>$CPEst);
                return $resultado;
            }
        }

    /**
     *Funcion que calcula el numero de niveles consecutivos que puede cursar un estudiante y determina los espacios que cumplen la condicion.
     * utiliza el metodo maximoSemestres
     * @param type $nivelMinimo
     * @param type $espaciosPorCursar
     * @param type $carrera
     * @return type
     */
    function evaluarParametros($nivelMinimo, $espaciosPorCursar,$carrera){ 
        $espaciosAMostrar=array();
        $semestreMayor=$nivelMinimo+$this->buscarMaximoSemestres($carrera);   
            for ($i = $nivelMinimo; $i < $semestreMayor; $i ++)
            {
                foreach ($espaciosPorCursar as $porCursar) {

                    if($i==$porCursar['NIVEL'] )
                    {
                        $espaciosAMostrar[]=$porCursar;
                    }else
                        {
                        }
                }
            }
        if (!isset($espaciosAMostrar))
        {
            $espaciosAMostrar='';
        }
            //elimina del arreglo de espacios por cursar los que cumplen con el maximo de niveles
            foreach ($espaciosAMostrar as $original)
            {
                foreach ($espaciosPorCursar as $clave => $espacio) {
                    if ($original['CODIGO']==$espacio['CODIGO'])
                    {
                        unset($espaciosPorCursar[$clave]);
                    }
                }
            }
        if (!isset($espaciosPorCursar))
        {
            $espaciosPorCursar='';
        }
        return array('Cumplen parametros'=>$espaciosAMostrar, 'No cumplen parametros'=>$espaciosPorCursar);    
    }
    
    /**
    *Funcion que busca el maximo de semestres consecutivos que puede cursar un estudiante.
    * @param type $carrera
    * @return type
    */
    function buscarMaximoSemestres($carrera){
        foreach($this->parametrosHoras as $parametros)
        {
            if($carrera==$parametros['CARRERA'])
            {
                $resultado=$parametros['SEMESTRES'];
            }
        }
        if (!isset($resultado))
        {
            $resultado='';
        }
        return $resultado;
    } 

    /**
     *  Funcion que permite extraer una columna de un arreglo
     * @param type $arreglo
     * @param type $columna
     * @return type
     */
    function extraerColumna($arreglo, $columna) {
         foreach ($arreglo as $fila)
        {
            $arregloColumna[]=$fila[$columna];
        }
        return $arregloColumna;
    }
    
    /**
     * Funcion que crea el arreglo de datos del estudiante para ser registrado en la tabla de carga de inscripciones
     * @param type $estudiante
     * @param type $datosAdicionalesEstudiante
     * @param type $cadenaEspaciosPorCursar
     * @param type $cadenaEquivalencias
     * @param type $cadenaRequisitos
     * @param type $cadenaParametrosPlan
     * @param type $cadenaCreditosAprobados
     * @return type
     */
    function crearArregloEstudiante($estudiante,$datosAdicionalesEstudiante,$cadenaEspaciosPorCursar,$cadenaEquivalencias,$cadenaRequisitos,$cadenaParametrosPlan,$cadenaCreditosAprobados,$cadenaCancelados){
        $arregloDatosEstudiante=array('CODIGO'=>$estudiante['CODIGO'],
                                        'NOMBRE'=>$this->escaparCaracteresNombre($estudiante['NOMBRE']),
                                        'ESTADO'=>$estudiante['ESTADO'],
                                        'DESCRIPCION'=>$estudiante['DESCRIPCION'],
                                        'PENSUM'=>(isset($estudiante['PLAN'])?$estudiante['PLAN']:''),
                                        'PROYECTO'=>$estudiante['CARRERA'],
                                        'NOMBRE_CARRERA'=>$datosAdicionalesEstudiante['NOMBRE_CARRERA'],
                                        'FACULTAD'=>$datosAdicionalesEstudiante['FACULTAD'],
                                        'NOMBRE_FACULTAD'=>$datosAdicionalesEstudiante['NOMBRE_FACULTAD'],
                                        'TIPO_ESTUDIANTE'=>(isset($estudiante['TIPO_ESTUD'])?$estudiante['TIPO_ESTUD']:''),
                                        'ACUERDO'=>(isset($estudiante['ACUERDO'])?$estudiante['ACUERDO']:''),
                                        'POR_CURSAR'=>$this->escaparSlashes($cadenaEspaciosPorCursar),
                                        'EQUIVALENCIAS'=>$this->escaparSlashes($cadenaEquivalencias),
                                        'REQUISITOS'=>$this->escaparSlashes($cadenaRequisitos),
                                        'PARAMETROS_PLAN'=>$cadenaParametrosPlan,
                                        'CREDITOS_APROBADOS'=>$cadenaCreditosAprobados,
                                        'CANCELADOS'=>$cadenaCancelados,
                                        'ANO'=>$this->ano,
                                        'PERIODO'=>$this->periodo);
        return $arregloDatosEstudiante;
    }
    
    /**
     * Funcion que registra el arreglo de datos del estudiante
     * @param type $arregloEstudiante
     */
    function registrarArregloEstudiantes($estudiante){
            $resultado=$registrado=0;
            $cadena_sql=$this->sql->cadena_sql("insertarRegistroDatosEstudiante", $estudiante);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
            $registrado=$this->totalAfectados($this->configuracion, $this->accesoGestion);
            if ($registrado<1||$resultado===FALSE)
            {
                echo "*** NO SE PUDO REGISTRAR ".$estudiante['CODIGO']." ***<br>";
                $this->total++;
            }
    }

    /**
    *Funcion que consulta los datos de los estudiantes de un proyecto
    * 
    * @param string $codProyecto
    * @return array 
    */
    function consultarDatosEstudiantes($codProyecto){
        $variables=array('codProyecto'=>$codProyecto);
        $cadena_sql=$this->sql->cadena_sql("consultarDatosEstudiantes", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
      
    /**
    *Funcion que consulta los datos de un estudiante
    * 
    * @param string $codProyecto
    * @return array 
    */
    function consultarDatosEstudiante($codEstudiante){
        $variables=array('codEstudiante'=>$codEstudiante);
        $cadena_sql=$this->sql->cadena_sql("consultarDatosEstudiante", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0];
    }
      
    /**
    *Funcion que consulta los datos del proyecto
    * 
    * @param string $codProyecto
    * @return array 
    */
    function consultarDatosProyecto($codProyecto){
        $variables=array('codProyecto'=>$codProyecto);
        $cadena_sql=$this->sql->cadena_sql("consultarDatosProyecto", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
      
    /**
     *Funcion que consulta las notas de los estudiantes del proyecto
     * 
     * @param type $codProyecto
     * @return type 
     */
    function consultarNotasEstudiantes($codProyecto) {
        $variables=array('codProyecto'=>$codProyecto);
        $cadena_sql = $this->sql->cadena_sql("consultarNotasEstudiantes",$variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
    /**
     *Funcion que consulta las notas de un estudiante
     * 
     * @param type $codEstudiante
     * @return type 
     */
    function consultarNotasEstudiante($codEstudiante) {
        $variables=array('codEstudiante'=>$codEstudiante);
        $cadena_sql = $this->sql->cadena_sql("consultarNotasEstudiante",$variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
      /**
     *Funcion que consulta los planes de estudio de una carrera, una carrera puede tener varios planes de estudios
    * No se rescatan los Electivos Extrinsecos
     * 
     * @param string $codProyecto
     * @return array espacios academicos del plan
     */
   function consultarEspaciosPlanEstudio($codProyecto) {
        $variables=array('codProyecto'=>$codProyecto
                          );
        $cadena_sql = $this->sql->cadena_sql("consultarEspaciosPlanEstudio",$variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
    
    /**
     *Funcion que busca los requisitos de todos los planes de estudios de creditos
     * @return array 
     */
    function consultarRequisitosCreditos() {
        $variables=array(
                        );
        $cadena_sql = $this->sql->cadena_sql("consultarRequisitosPlanesCreditos", $variables); 
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda"); 
        return $resultado;
    }
    
    /**
     *Funcion que busca los requisitos de todos los planes de estudios de horas
     * @return array 
     */
    function consultarRequisitosHoras() {
        
        $variables=array(
                        );
        $cadena_sql = $this->sql->cadena_sql("consultarRequisitosPlanesHoras", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda"); 
        return $resultado;
    }

    /**
     * Funcion que consulta los parametros de los planes de estudios de Créditos.
     * @return array 
     */
    function consultarParametrosCreditos(){
        $variables=array();
        $cadena_sql_parametros=$this->sql->cadena_sql("consultarParametrosCreditos", $variables);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_parametros,"busqueda" );   
        return $resultado;
    }
  
    /**
     * Funcion que borra registros de la tabla de carga.
     * @return array 
     */
    function borrarDatosTablaPrecarga($variables){
        $cadena_sql=$this->sql->cadena_sql("consultarDatos", $variables);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        if ($resultado[0]['TOTAL']<1)
        {
            echo "NO SE ENCONTRARON DATOS PARA BORRAR.<br>";
            $resultado=0;
            return $resultado;
        }else
            {
                $cadena_sql=$this->sql->cadena_sql("borrarDatos", $variables);
                $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
                $afectados=$this->totalAfectados($this->configuracion, $this->accesoGestion);
                if ($afectados<1||$resultado===FALSE)
                {
                    switch ($variables['CAMPO']) {
                        case 'ins_fac_cod':
                            $nombre='FACULTAD';
                            break;
                        case 'ins_est_cra_cod':
                            $nombre='PROYECTO';
                            break;
                        case 'ins_est_cod':
                            $nombre='ESTUDIANTE';
                            break;
                        default:
                            break;
                    }
                    echo "*** NO SE PUDO BORRAR ".$nombre." = ".$variables['VALOR_CAMPO']."***<br>";
                    exit;
                }
                return $afectados;
            }
    }
  
    /**
     *Funcion que consulta los parametros de los planes de estudios de horas
     * @return array
     */
    function consultarParametrosHoras(){
        $variables=array('ano'=>$this->ano, 'periodo'=>$this->periodo);
        $cadena_sql_parametros=$this->sql->cadena_sql("consultarParametrosHoras", $variables);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_parametros,"busqueda" ); 
        return $resultado;
    }

    /**
     * Funcion que consulta las equivalencias de un espacio academico, para estudiantes de horas.
     * @return array 
     */   
    function consultarEspaciosEquivalentes() {
       $cadena_sql = $this->sql->cadena_sql("consultarEspaciosEquivalentes", '');
       $resultado  = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
       return $resultado;
      }

    /**
    * Funcion que consulta la información de las carreras de una facultad
     * @param type $codFacultad
     * @return type
    */ 
    function consultarDatosCarreras($codFacultad) {
        $variables=array('codFacultad'=>$codFacultad);
        $cadena_sql = $this->sql->cadena_sql("consultarDatosCarreras", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado;
    }
      
    /**
    * Funcion que consulta los espacios cancelados en el período
    */ 
    function consultarEspaciosCancelados() {
        $variables=array('ano'=>$this->ano, 'periodo'=>$this->periodo);
        $cadena_sql = $this->sql->cadena_sql("consultarEspaciosCancelados", $variables);
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        $this->espaciosCancelados=$resultado;
    }
      
  }
?>