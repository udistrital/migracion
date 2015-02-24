<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAdicionarFechasNovedadNotas extends sql {
  private $configuracion;
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
  }
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {
            
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;

            
            case 'adicionar_fechas_calendario':
                    $cadena_sql="INSERT INTO accaleventos ";
                    $cadena_sql.="( ace_cod_evento,
                                    ace_cra_cod,
                                    ace_fec_ini,
                                    ace_fec_fin,
                                    ace_tip_cra,
                                    ace_dep_cod,
                                    ace_anio,
                                    ace_periodo,
                                    ace_estado,
                                    ace_habilitar_ex ) ";
                    $cadena_sql.="VALUES ('".$variable['codEvento']."',";
                    $cadena_sql.="'".$variable['codProyecto']."',";
                    $cadena_sql.="TO_DATE('".$variable['fechahora_inicio']."', 'yyyy/mm/dd hh24:mi'),";
                    $cadena_sql.="TO_DATE('".$variable['fechahora_fin']."', 'yyyy/mm/dd hh24:mi'),";
                    $cadena_sql.="'".$variable['tipoProyecto']."',";
                    $cadena_sql.="'".$variable['codFacultad']."',";
                    $cadena_sql.="'".$variable['anio']."',";
                    $cadena_sql.="'".$variable['periodo']."',";
                    $cadena_sql.="'".$variable['estado']."',";
                    $cadena_sql.="'".$variable['habilitar']."')"; 
                
                break;
            
            case 'actualizar_fechas_calendario':
                    $cadena_sql="UPDATE accaleventos ";
                    $cadena_sql.=" SET ace_fec_ini=TO_DATE('".$variable['fechahora_inicio']."', 'yyyy/mm/dd hh24:mi'),";
                    $cadena_sql.=" ace_fec_fin=TO_DATE('".$variable['fechahora_fin']."', 'yyyy/mm/dd hh24:mi'),";
                    $cadena_sql.=" ace_estado='A'";
                    $cadena_sql.=" WHERE";
                    $cadena_sql.=" ace_cra_cod='".$variable['codProyecto']."' ";
                    $cadena_sql.=" AND ace_cod_evento='".$variable['codEvento']."'";
                    $cadena_sql.=" AND ace_anio='".$variable['anio']."'";
                    $cadena_sql.=" AND ace_periodo='".$variable['periodo']."'";
                
                break;
            
            case 'datos_proyectos':
                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" cra_cod,";
                    $cadena_sql.=" cra_nombre,";
                    $cadena_sql.=" cra_dep_cod,";
                    $cadena_sql.=" cra_tip_cra";
                    $cadena_sql.=" FROM accra";
                    $cadena_sql.=" INNER JOIN actipcra ON cra_tip_cra=tra_cod ";
                    $cadena_sql.=" WHERE cra_cod=".$variable['codProyecto'];
                    if($variable['nivel']){
                        $cadena_sql.=" AND tra_cod_nivel in (".$variable['nivel'].")";
                    }
                    break;
           
            case 'consultarEventoProyecto':

                    $cadena_sql=" SELECT ace_cod_evento,";
                    $cadena_sql.=" ace_cra_cod,";
                    $cadena_sql.=" ace_fec_ini,";
                    $cadena_sql.=" ace_fec_fin,";
                    $cadena_sql.=" ace_tip_cra,";
                    $cadena_sql.=" ace_dep_cod,";
                    $cadena_sql.=" ace_anio,";
                    $cadena_sql.=" ace_periodo,";
                    $cadena_sql.=" ace_estado,";
                    $cadena_sql.=" ace_habilitar_ex ";
                    $cadena_sql.=" FROM accaleventos ";
                    $cadena_sql.=" WHERE";
                    $cadena_sql.=" ace_cra_cod= ".$variable['codProyecto'];
                    $cadena_sql.=" AND ace_cod_evento= ".$variable['codEvento'];
                    $cadena_sql.=" AND ace_anio= ".$variable['anio'];
                    $cadena_sql.=" AND ace_periodo= ".$variable['periodo'];
                    $cadena_sql.=" AND ace_estado= 'A'";
                    break;
                
            case 'consultarEventoInactivoProyecto':

                    $cadena_sql=" SELECT ace_cod_evento,";
                    $cadena_sql.=" ace_cra_cod,";
                    $cadena_sql.=" ace_fec_ini,";
                    $cadena_sql.=" ace_fec_fin,";
                    $cadena_sql.=" ace_tip_cra,";
                    $cadena_sql.=" ace_dep_cod,";
                    $cadena_sql.=" ace_anio,";
                    $cadena_sql.=" ace_periodo,";
                    $cadena_sql.=" ace_estado,";
                    $cadena_sql.=" ace_habilitar_ex ";
                    $cadena_sql.=" FROM accaleventos ";
                    $cadena_sql.=" WHERE";
                    $cadena_sql.=" ace_cra_cod= ".$variable['codProyecto'];
                    $cadena_sql.=" AND ace_cod_evento= ".$variable['codEvento'];
                    $cadena_sql.=" AND ace_anio= ".$variable['anio'];
                    $cadena_sql.=" AND ace_periodo= ".$variable['periodo'];
                    $cadena_sql.=" AND ace_estado= 'I'";
                    break;

	case 'inactivar_fechas':

                    $cadena_sql=" UPDATE accaleventos";
                    $cadena_sql.=" SET ace_estado= 'I'";
                    $cadena_sql.=" WHERE ace_cra_cod= ".$variable['codProyecto'];
                    $cadena_sql.=" AND ace_cod_evento= ".$variable['codEvento'];
                    $cadena_sql.=" AND ace_anio= ".$variable['anio'];
                    $cadena_sql.=" AND ace_periodo= ".$variable['periodo'];
                    $cadena_sql.=" AND TO_CHAR(ace_fec_ini,'YYYY/MM/DD HH24:MI:SS')= '".$variable['fechaInicial']."'";
                    $cadena_sql.=" AND TO_CHAR(ace_fec_fin,'YYYY/MM/DD HH24:MI:SS')= '".$variable['fechaFinal']."'";
                    break;
  
                            
	case 'proyectos_facultad_decano':

                    $cadena_sql=" SELECT cra_cod PROYECTO";
                    $cadena_sql.=" FROM accra ";
                    $cadena_sql.=" INNER JOIN actipcra ON cra_tip_cra=tra_cod ";
                    $cadena_sql.=" INNER JOIN acdecanos ON dec_dep_cod=cra_dep_cod ";
                    $cadena_sql.=" INNER JOIN peemp ON dec_cod=emp_cod ";
                    $cadena_sql.=" WHERE emp_nro_iden='".$variable."'";
                    if($variable['nivel']){
                        $cadena_sql.=" AND tra_cod_nivel in (".$variable['nivel'].")";
                    }
                    break;
  
	case 'proyectos_facultad':

                    $cadena_sql=" SELECT cra_cod COD_PROYECTO";
                    $cadena_sql.=" FROM accra ";
                    $cadena_sql.=" INNER JOIN actipcra ON cra_tip_cra=tra_cod ";
                    $cadena_sql.=" WHERE cra_estado='A'";
                    $cadena_sql.=" AND cra_cod!=999";
                    $cadena_sql.=" AND tra_cod_nivel in (".$variable['nivel'].")";
                    if($variable['codFacultad'] && $variable['codFacultad']!=999){
                        $cadena_sql.=" AND cra_dep_cod='".$variable['codFacultad']."'";
                    }
                    break;
  
	case 'proyectos_coordinador':

                    $cadena_sql=" SELECT cra_cod PROYECTO";
                    $cadena_sql.=" FROM accra ";
                    $cadena_sql.=" WHERE cra_emp_nro_iden='".$variable."'";
                    break;
  
                            
     
        }
        //echo $cadena_sql;exit;
        return $cadena_sql;
    }


}
?>