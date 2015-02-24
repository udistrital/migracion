<?php
/* 
 * Funcion que tiene metodos para la clasificacion de los estudiantes para las inscripciones
 * 
 */

/**
 * @author Maritza Callejas
 * Fecha 09 de Enero de 2013
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

class clasificacionEstudiante {
  private $configuracion;
  public $sesion;


  public function __construct() {

        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $this->configuracion=$configuracion;
        
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");

        $this->cripto=new encriptar();
        $this->funcionGeneral=new funcionGeneral();
        $this->sesion=new sesiones($configuracion);

        //Conexion General
        $this->acceso_db=$this->funcionGeneral->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->funcionGeneral->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->funcionGeneral->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        
        $this->usuario=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        
        $this->identificacion=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $cadena_sql=$this->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];

    }

    /**
     * Funcion que consulta los estudiantes en prueba academica
     */
    function consultarEstudiantesPruebaAcademica(){
        $cadena_sql=$this->cadena_sql("estudiantesPruebaAcademica",'');
        $estudiantes_prueba=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        return $estudiantes_prueba;
    }
    
    /**
     * Funcion que consulta los estudiantes que no estan en prueba academica
     */
    function consultarEstudiantesSinPrueba(){
        
        $cadena_sql=$this->cadena_sql("estudiantesSinPrueba",'');
        $estudiantes=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        return $estudiantes;
    }
    
    /**
     * Funcion que busca en los espacios por cursar la cantidad de espacios reprobados y realiza la clasificacion 
     * estudiantes con 1 EA reprobado clasificacion = 4
     * estudiantes con 2 EA reprobados clasificacion = 5
     * estudiantes con 3 EA reprobados clasificacion = 6
     * estudiantes con mas de 3 EA reprobados clasificacion = 7
     * @param array $estudiantes
     * @param array $estudiantes_clasificados
     * @return array 
     */
    function buscarEstudiantesConEspaciosReprobados($estudiantes,$estudiantes_clasificados){
            if(count($estudiantes)>0){
                foreach ($estudiantes as $key => $arreglo_estudiante) {
                        $cantidad_reprobados = 0;
                        

                        $espacios_por_cursar = json_decode($arreglo_estudiante['ESPACIOS_POR_CURSAR'],true);
                        $cantidad_reprobados = $this->contarReprobados($espacios_por_cursar);
                        switch($cantidad_reprobados){
                            case 1:
                                $resultado = array( 'COD_ESTUDIANTE'=>$arreglo_estudiante['COD_ESTUDIANTE'],
                                                    'COD_PROYECTO'=>$arreglo_estudiante['COD_PROYECTO'],
                                                    'CLASIFICACION'=>'4',
                                                    'TIPO'=>$arreglo_estudiante['TIPO']
                                    );
                                $estudiantes_clasificados[] = $resultado;
                                unset($estudiantes[$key]);
                                break;
                            case 2:
                                $resultado = array( 'COD_ESTUDIANTE'=>$arreglo_estudiante['COD_ESTUDIANTE'],
                                                    'COD_PROYECTO'=>$arreglo_estudiante['COD_PROYECTO'],
                                                    'CLASIFICACION'=>'5',
                                                    'TIPO'=>$arreglo_estudiante['TIPO']
                                    );
                                $estudiantes_clasificados[] = $resultado;
                                unset($estudiantes[$key]);
                                break;
                            case 3:
                                $resultado = array( 'COD_ESTUDIANTE'=>$arreglo_estudiante['COD_ESTUDIANTE'],
                                                    'COD_PROYECTO'=>$arreglo_estudiante['COD_PROYECTO'],
                                                    'CLASIFICACION'=>'6',
                                                    'TIPO'=>$arreglo_estudiante['TIPO']
                                    );
                                $estudiantes_clasificados[] = $resultado;
                                unset($estudiantes[$key]);
                                break;
                            default:
                                if($cantidad_reprobados>3){
                                    $resultado = array( 'COD_ESTUDIANTE'=>$arreglo_estudiante['COD_ESTUDIANTE'],
                                                    'COD_PROYECTO'=>$arreglo_estudiante['COD_PROYECTO'],
                                                    'CLASIFICACION'=>'7',
                                                    'TIPO'=>$arreglo_estudiante['TIPO']
                                    );
                                $estudiantes_clasificados[] = $resultado;
                                unset($estudiantes[$key]);
                                }
                                break;

                        }
                }
            }
            $resultado = array( 'ESTUDIANTES'=>$estudiantes,
                                'CLASIFICADOS'=>$estudiantes_clasificados);
            return $resultado;
    }
    
    /**
     * Funcion que busca en un arreglo de espacios los reprobados y retorna la cantidad total de reprobados
     * @param array $espacios_por_cursar
     * @return int 
     */
     function contarReprobados($espacios_por_cursar){
        $cantidad=0;
         if(count($espacios_por_cursar)>0){
           foreach ($espacios_por_cursar as $key => $espacio) {
            if($espacio['REPROBADO']==1){
                $cantidad++;
            }
                
            }
         }
        return $cantidad;      
    }

    /**
     * Funcion que busca las notas de los estudiantes y realiza la clasificacion de los estudiantes nivelados (clasificacion = 1), que no han perdido ningun EA
     * @param array $estudiantes
     * @param array $estudiantes_clasificados
     * @return array 
     */
        function buscarEstudiantesNivelados($estudiantes,$estudiantes_clasificados){
            
            //dividimos el arreglo en cada 500 estudiantes
            $estudiantes = array_chunk($estudiantes, 500,true);
            $k=0;
            foreach ($estudiantes as $key => $arreglo_estudiante) {
                //recorremos cada 500 estudiantes y buscamos las notas
               $notas = $this->obtenerNotas($arreglo_estudiante);
               foreach($arreglo_estudiante as $key2 => $estudiante){
                    $reprobados = 0;
                    $notas_estudiante = array();
                    $notas_estudiante = $this->obtenerNotasEstudiante($notas,$estudiante['COD_ESTUDIANTE']); 
                    $reprobados = $this->verificarReprobados($notas_estudiante);
                    unset($notas_estudiante);
                    if($reprobados==0){                        
                        $resultado = array( 'COD_ESTUDIANTE'=>$estudiante['COD_ESTUDIANTE'],
                                                    'COD_PROYECTO'=>$estudiante['COD_PROYECTO'],
                                                    'CLASIFICACION'=>'1',
                                                    'TIPO'=>$estudiante['TIPO']
                                    );
                        $estudiantes_clasificados[] = $resultado;
                        unset($estudiantes[$key][$key2]);
                        
                    }
                }
                unset($notas);
                    
            }
            foreach ($estudiantes as $key => $arreglo_estudiante) {
                foreach ($arreglo_estudiante as $key2 => $arreglo_estudiante2) {
                    $estudiantes_final[] = $estudiantes[$key][$key2];
                }
            }
            $resultado = array( 'ESTUDIANTES'=>$estudiantes_final,
                                'CLASIFICADOS'=>$estudiantes_clasificados);
                            
            return $resultado;
                
  }
   
    /**
     * Funcion que elabora una cadena con los codigos de los estudiantes de un arreglo
     * @param array $estudiantes
     * @return string 
     */
   function obtenerCadenaCodigos($estudiantes){
            $cadena_codigos='';
            foreach ($estudiantes as $key => $row) {
                if(!$cadena_codigos){
                    $cadena_codigos = $row['COD_ESTUDIANTE'];
                }else{
                    $cadena_codigos .= ",".$row['COD_ESTUDIANTE'];
                }
            }
            return $cadena_codigos;
   }

   function obtenerNotas($estudiantes){
                $cadena_codigos = $this->obtenerCadenaCodigos($estudiantes);
                $notas = $this->consultarNotas($cadena_codigos);

        return $notas;       
    }
   
    /**
     * Funcion que consulta en la base de datos las notas de los estudiantes en estado A,B,V y J
     * @param String $codigos
     * @return Array 
     */
   function consultarNotas($codigos){     
        $cadena_sql=$this->cadena_sql("notas",$codigos);
        $estudiantes=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $estudiantes;
    }

    
 /**
     * Funcion que busca las notas de un estudiante en un arreglo de notas general
     * @param String $codigos
     * @return Array 
     */
    function obtenerNotasEstudiante($notas,$codigo_estudiante){
            $notas_estudiante=array();
            foreach ($notas as $key => $row) {
                if($row['COD_ESTUDIANTE']==$codigo_estudiante){
                    $notas_estudiante[]=$row;
                }
            }
            return $notas_estudiante;
    }
    
    /**
     * Funcion que busca si tiene EA reprobados un estudiante dentro de un arreglo de notas
     * @param Array $informacion_reprobados
     * @param Array $codigo_estudiante
     * @return int 
     */
    function verificarReprobados($notas_estudiante){
        $reprobados = 0;
        if(count($notas_estudiante)>0){
            foreach ($notas_estudiante as $key => $estudiantes) {
                if ((isset($estudiantes['NOTA'])?$estudiantes['NOTA']:0)<30 || $estudiantes['OBSERVACION']==20 || $estudiantes['OBSERVACION']==23 || $estudiantes['OBSERVACION']==25){
                    $reprobados=1;
                    break;
                }
            }
        }
        return $reprobados;
    }
    
    /**
     * Funcion que busca los estudiantes que no reprobaron EA en el semestre anterior
     * @param Array $estudiantes
     * @param Array $estudiantes_clasificados
     * @return Array 
     */
    function buscarEstudiantesSinEspaciosReprobadosSemAnterior($estudiantes,$estudiantes_clasificados){
        if(!is_array($estudiantes_clasificados)){
             $estudiantes_clasificados= array();
        }
        
          
        if(count($estudiantes)>0){
                foreach ($estudiantes as $key => $arreglo_estudiante) {
                        $cantidad_reprobados = 0;
                        $espacios_por_cursar = json_decode($arreglo_estudiante['ESPACIOS_POR_CURSAR'],true);
                        $cantidad_reprobados = $this->contarReprobados($espacios_por_cursar);
                        if($cantidad_reprobados==0){
                                $resultado = array( 'COD_ESTUDIANTE'=>$arreglo_estudiante['COD_ESTUDIANTE'],
                                                    'COD_PROYECTO'=>$arreglo_estudiante['COD_PROYECTO'],
                                                    'CLASIFICACION'=>'2',
                                                    'TIPO'=>$arreglo_estudiante['TIPO']
                                    );
                                $estudiantes_clasificados[] = $resultado;    
                                unset($estudiantes[$key]);
                        }
               }
            }
            $resultado = array( 'ESTUDIANTES'=>$estudiantes,
                                'CLASIFICADOS'=>$estudiantes_clasificados);
            return $resultado;
    }

    /**
     * Funcion que ordena los estudiantes clasificados por nivel y codigos de estudiantes
     * @param Array $estudiantes_clasificados
     * @return Array 
     */
    function ordenarEstudiantesClasificados($estudiantes_clasificados){
        // Obtener una lista de columnas
        foreach ($estudiantes_clasificados as $key => $row) {
            $nivel[$key]  = $row['CLASIFICACION'];
            $codigos[$key] = $row['COD_ESTUDIANTE'];
        }

        // Ordenar los datos con nivel ascendente, codigos ascendente
        // Agregar $datos como el último parámetro, para ordenar por la llave común
        array_multisort($nivel, SORT_ASC, $codigos, SORT_ASC, $estudiantes_clasificados);
        
        //adicionamos ID a cada estudiante
        foreach ($estudiantes_clasificados as $key3 => $informacion){
            $estudiantes_clasificados[$key3]['ID'] = $key3+1;
        }
        
        return $estudiantes_clasificados;
    }
    
    /**
     * Funcion que recorre los estudiantes clasificados para insertarlos en la tabla de la Base de datos
     * @param Array $estudiantes
     * @return int 
     */
    function insertarEstudiantesClasificados($estudiantes){
        $cantidad = 0;
        foreach ($estudiantes as $key => $clasificados) {
            $id = $clasificados['ID'];
            $codigo = $clasificados['COD_ESTUDIANTE'];
            $proyecto = $clasificados['COD_PROYECTO'];
            $clasificacion = $clasificados['CLASIFICACION'];
            $tipo = $clasificados['TIPO'];
            $insertado = $this->adicionarMysqlEstudianteClasificacion($id,$codigo,$proyecto,$clasificacion,$tipo);
            if($insertado >0){
                $cantidad++;
            }
        }
        return $cantidad;
    }
    
   /**
     * Funcion que inserta en mysql los estudiantes con la respectiva clasificacion
     * @param int $id
     * @param int $codigo
     * @param int $proyecto
     * @param int $clasificacion
     * @return int 
     */
   function adicionarMysqlEstudianteClasificacion($id,$codigo,$proyecto,$clasificacion,$tipo) {
        $datos = array( 'ID'=>$id,
                        'COD_ESTUDIANTE'=>$codigo,
                        'COD_PROYECTO'=>$proyecto,
                        'CLASIFICACION'=>$clasificacion,
                        'TIPO'=>$tipo);
        $cadena_sql=$this->cadena_sql("adicionar_estudianteClasificacion",$datos);
        $resultado=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
        
        return $this->funcionGeneral->totalAfectados($this->configuracion, $this->accesoGestion);
    }

   /**
     * Funcion que revisa los estudiantes, los clasifica e inserta en la tabla de clasificacion el correspondiente registro
     * @return int 
     */
    function ejecutarClasificacionEstudiantes(){
        //buscamos estudiantes en prueba academica
        $estudiantes_clasificados = $this->consultarEstudiantesPruebaAcademica();
        //buscamos estudiantes que no estan en prueba academica
        $estudiantes = $this->consultarEstudiantesSinPrueba();
        //buscamos estudiantes con 1 y 2 espacios reprobados
        $resultado = $this->buscarEstudiantesConEspaciosReprobados($estudiantes, $estudiantes_clasificados);
        $estudiantes = $resultado['ESTUDIANTES'];
        $estudiantes_clasificados = $resultado['CLASIFICADOS'];
        unset($resultado);
        //buscamos estudiantes nivelados (sin ningun espacio reprobado)
        $resultado_nivelados = $this->buscarEstudiantesNivelados($estudiantes, $estudiantes_clasificados);
        $estudiantes = $resultado_nivelados['ESTUDIANTES'];
        $estudiantes_clasificados = $resultado_nivelados['CLASIFICADOS'];
        //buscamos estudiantes que no reprobaron el semestre anterior
        $resultado_semAnterior = $this->buscarEstudiantesSinEspaciosReprobadosSemAnterior($estudiantes, $estudiantes_clasificados);
        $estudiantes = $resultado_semAnterior['ESTUDIANTES'];
        $estudiantes_clasificados = $resultado_semAnterior['CLASIFICADOS'];
        //ordenar arreglo de estudiantes clasificados
        $estudiantes_clasificados_final= $this->ordenarEstudiantesClasificados($estudiantes_clasificados);
        //var_dump($estudiantes);
        $insercion = $this->insertarEstudiantesClasificados($estudiantes_clasificados_final);
        return $insercion ;
    }
    
   /**
     * Funcion para obtener las cadenas de consultas
     * @return int 
     */
    function cadena_sql($tipo,$variable)
        {
            switch ($tipo)
                {
                  case 'periodoActivo':

                    $cadena_sql="SELECT ape_ano ANO,";
                    $cadena_sql.=" ape_per PERIODO";
                    $cadena_sql.=" FROM acasperi";
                    $cadena_sql.=" WHERE";
                    $cadena_sql.=" ape_estado LIKE '%A%'";
                    break;

                  case "estudiantesPruebaAcademica":

                      $cadena_sql =" SELECT ins_est_cod     AS COD_ESTUDIANTE, ";
                      $cadena_sql.=" ins_est_cra_cod        AS COD_PROYECTO, ";
                      $cadena_sql.=" 3                      AS CLASIFICACION, ";
                      $cadena_sql.=" ins_est_tipo           AS TIPO";
                      $cadena_sql.=" FROM sga_carga_inscripciones ";
                      $cadena_sql.=" WHERE ins_est_estado in ('B')";
                      break;

                  case "estudiantesSinPrueba":
                      if($this->periodo==1){
                          $periodo= $this->periodo;
                      }elseif($this->periodo==3){
                          $periodo= 2;
                      }
                        $cadena_sql =" SELECT ins_est_cod           AS COD_ESTUDIANTE,  ";
                        //$cadena_sql .=" ins_est_nombre              AS NOMBRE,  ";
                        $cadena_sql .=" ins_est_estado              AS COD_ESTADO, "; 
                        $cadena_sql .=" ins_estado_descripcion      AS ESTADO,  ";
                        //$cadena_sql .=" ins_est_pensum              AS PENSUM,  ";
                        $cadena_sql .=" ins_est_cra_cod             AS COD_PROYECTO,  ";
                        $cadena_sql .=" ins_cra_nombre              AS PROYECTO,  ";
                        //$cadena_sql .=" ins_fac_cod                 AS COD_FACULTAD,  ";
                        //$cadena_sql .=" ins_fac_nombre              AS FACULTAD,  ";
                        $cadena_sql .=" ins_est_tipo                AS TIPO,  ";
                        //$cadena_sql .=" ins_est_acuerdo             AS ACUERDO,  ";
                        $cadena_sql .=" ins_espacios_por_cursar     AS ESPACIOS_POR_CURSAR,  ";
                        //$cadena_sql .=" ins_equivalencias           AS EQUIVALENCIAS, ";
                        //$cadena_sql .=" ins_requisitos_no_aprobados AS REQ_NO_APROBADOS,  ";
                        //$cadena_sql .=" ins_parametros_plan         AS PARAMETROS_PLAN,  ";
                        //$cadena_sql .=" ins_creditos_aprobados      AS CREDITOS_APROBADOS,  ";
                        //$cadena_sql .=" ins_espacios_cancelados     AS ESPACIOS_CANCELADOS,  ";
                        //$cadena_sql .=" ins_espacios_aprobados      AS ESPACIOS_APROBADOS,  ";
                        $cadena_sql .=" ins_ano                     AS ANO,  ";
                        $cadena_sql .=" ins_periodo                 AS PERIODO ";
                        $cadena_sql .=" FROM sga_carga_inscripciones ";
                        $cadena_sql .=" WHERE ins_est_estado in ('A')";
                        $cadena_sql.=" AND ins_est_cod  not like '".$this->ano.$periodo."%'";
                        break;

                    case "notas":
                        $cadena_sql =" SELECT not_est_cod   AS COD_ESTUDIANTE,";
                        $cadena_sql.=" not_asi_cod          AS COD_ASIGNATURA,";
                        $cadena_sql.=" not_nota             AS NOTA,";
                        $cadena_sql.=" not_obs              AS OBSERVACION";
                        $cadena_sql.=" FROM acnot  ";
                        //$cadena_sql.=" INNER JOIN acest ON not_est_cod = est_cod ";
                        $cadena_sql.=" WHERE not_est_reg='A' ";
                        //$cadena_sql.=" AND est_estado_est IN ('A','B','V','J') ";
                        $cadena_sql.=" AND not_est_cod IN (".$variable.") ";
                        break;

                    case "notasReprobadas":
                        $cadena_sql =" SELECT not_est_cod COD_ESTUDIANTE,";
                        $cadena_sql.=" count(not_asi_cod) TOTAL ";
                        $cadena_sql.=" FROM acnot  ";
                        $cadena_sql.=" WHERE not_est_cod in (".$variable.")";
                        $cadena_sql.=" AND not_est_reg='A' ";
                        $cadena_sql.=" AND (not_nota<30 OR not_obs IN (20,23,25)) ";
                        $cadena_sql.=" GROUP BY not_est_cod ";
                        break;

                    case 'adicionar_estudianteClasificacion':
                        $cadena_sql="INSERT INTO sga_clasificacion_estudiantes ";
                        $cadena_sql.="(cle_id, cle_codEstudiante, cle_codProyectoCurricular, cle_clasificacion, cle_tipoEstudiante) ";
                        $cadena_sql.="VALUES (";
                        $cadena_sql.="'".$variable['ID']."',";
                        $cadena_sql.="'".$variable['COD_ESTUDIANTE']."',";
                        $cadena_sql.="'".$variable['COD_PROYECTO']."',";
                        $cadena_sql.="'".$variable['CLASIFICACION']."',";
                        $cadena_sql.="'".$variable['TIPO']."')"; 
                    break; 
            
                }
                 return $cadena_sql;
        }
}
?>
