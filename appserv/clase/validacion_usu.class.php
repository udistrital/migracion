<?php
/*
 * Funcion que tiene las validaciones para usuarios asistenciales
 *
 */

class validarUsu {

  
  public function __construct() {

  }

  /**
   * Esta funcion permite verificar si el estudiante pertenece al proyecto del asistente y el contrato del asistente esta vigente
   * @param <int> $codEstudiante
   * @param <int> $codAsistente
   * @return string
   */  
   public function validarProyectoAsistente($codEstudiante, $codAsistente,$conexion,$configuracion,$accesoOracle)
    {
        if(is_numeric($codEstudiante))
        {
          $cadena_sql=$this->cadena_sql("buscarInfoEstudiante",$codEstudiante); 
          $resultado_estudiante = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
          
          if(is_array($resultado_estudiante))
            {
                $mensaje=  $this->verificarProyectoAsistente($resultado_estudiante[0][1],$codAsistente,$_SESSION['usuario_nivel'],$conexion,$configuracion,$accesoOracle);
                if($mensaje=="Proyecto Curricular no valido para el asistente"){
                    $mensaje="El estudiante con código ".$codEstudiante." no pertenece al Proyecto Curricular.";
                }
            }else
                {
                    $mensaje="El dato ingresado no corresponde a un código válido de estudiante. Digite de nuevo el código";
                }
        }else
            {
                $mensaje="El código del estudiante debe ser numerico, digite de nuevo el código";
            }
            return $mensaje;
    }  
  
    /**
     * Función para verificar los proyectos de un asistente
     * @param type $codProyecto
     * @param type $codAsistente
     * @param type $tipoUsuario
     * @return string
     */   
    function verificarProyectoAsistente($codProyecto,$codAsistente,$tipoUsuario,$conexion,$configuracion,$accesoOracle){
                $resultado_proyectos = $this->consultarProyectosAsistente($codAsistente,$tipoUsuario,$conexion,$configuracion,$accesoOracle);

                $tipo=0;
                $total=count($resultado_proyectos);
                for($i=0;$i<$total;$i++)
                {
                    if($codProyecto==$resultado_proyectos[$i][0] && $resultado_proyectos[$i][1]!=9)
                    {
                        $tipo=1;
                        $valor=$i;
                    }elseif($codProyecto==$resultado_proyectos[$i][0] && $resultado_proyectos[$i][1]==9)
                        {
                            $fecha=date('YmdHis');
                            if($fecha>=$resultado_proyectos[$i][2]&&$fecha<=$resultado_proyectos[$i][3])
                            {
                                $tipo=1;
                                $valor=$i;
                            }else
                                {
                                    $tipo=2;
                                }
                        }
                }
                if($tipo==1)
                {
                    $mensaje='ok';
                }elseif($tipo==2)
                    {
                        $mensaje='La fecha de contrato no es válida';
                    }else
                        {
                            $mensaje="Proyecto Curricular no valido para el asistente";
                        }
                return $mensaje;
    }
    
    /**
     * Función para consultar los proyectos relacionados a un asistente, o secretaria dependiendo el tipo de usuario
     * @param type $codAsistente
     * @param type $tipoUsuario
     * @return type
     */
    function consultarProyectosAsistente($codAsistente,$tipoUsuario,$conexion,$configuracion,$accesoOracle){
        $datos=array('codAsistente'=>$codAsistente,
                     'tipoUsuario'=>$tipoUsuario);
        $cadena_sql_proyectos=$this->cadena_sql("proyectos_curriculares_asistente",$datos);
        $resultado_proyectos = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql_proyectos,"busqueda");
        return $resultado_proyectos;         
    }


  /**
   * Esta funcion construye las cadenas SQL para ejecutar
   * @param <string> $tipo
   * @param <array> $variable
   */
  public function cadena_sql($tipo, $variable) {
    switch ($tipo) {
      case 'buscarInfoEstudiante':

        $cadena_sql= "SELECT est_ind_cred INDICA_CREDITOS,";
        $cadena_sql.=" est_cra_cod PROYECTO_ESTUDIANTE,";
        $cadena_sql.=" est_pen_nro PLAN_ESTUDIANTE,";
        $cadena_sql.=" cra_dep_cod FACULTAD";
        $cadena_sql.=" FROM acest ";
        $cadena_sql.=" INNER JOIN accra ON est_cra_cod=cra_cod";
        $cadena_sql.=" WHERE est_cod=" . $variable;
        break;


    case 'proyectos_curriculares_asistente':

        $cadena_sql="SELECT DISTINCT usuweb_codigo_dep DEPENDENCIA,";
        $cadena_sql.=" usuweb_tipo_vinculacion TIPO,";
        $cadena_sql.=" NVL(TO_CHAR(usuweb_fecha_inicio,'yyyymmddhh24miss'),0) FECHA_INICIO,";
        $cadena_sql.=" NVL(TO_CHAR(usuweb_fecha_fin,'yyyymmddhh24miss'),0) FECHA_FIN,";
        $cadena_sql.=" cra_abrev NOMBRE ";
        $cadena_sql.=" FROM geusuweb";
        $cadena_sql.=" LEFT OUTER JOIN accra ON usuweb_codigo_dep=cra_cod";
        $cadena_sql.=" WHERE usuweb_codigo=".$variable['codAsistente'];
        $cadena_sql.=" AND usuweb_estado='A'";
        $cadena_sql.=" AND usuweb_tipo='".$variable['tipoUsuario']."'";
        $cadena_sql.=" ORDER BY DEPENDENCIA,FECHA_FIN";
    break;
    
    
    }
    return $cadena_sql;
  }

}
?>
