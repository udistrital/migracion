<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminHorarios extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

    function cadena_sql($configuracion, $tipo, $variable="") {

        switch ($tipo) {


          
            case "datosCoordinadorCarrera":

                $this->cadena_sql = "SELECT cra_cod, ";
                $this->cadena_sql.="cra_nombre, ";
                $this->cadena_sql.="cra_dep_cod ";
                $this->cadena_sql.="FROM accra ";
                $this->cadena_sql.="WHERE cra_emp_nro_iden='" . $variable . "' ";
                
                break;

            case "proyecto_curricular":

                $this->cadena_sql=" SELECT CRA_COD, CRA_NOMBRE ";
                $this->cadena_sql.="FROM accra ";
                $this->cadena_sql.="WHERE cra_cod = ".$variable;
                $this->cadena_sql.=" ORDER BY CRA_NOMBRE";
                break;

            case "espacios_academicos":
                $this->cadena_sql=" SELECT asi_cod, asi_nombre, pen_sem FROM acasi, acpen ";
                $this->cadena_sql.=" WHERE pen_nro=".$variable[0]. " AND pen_cra_cod=".$variable[1];
                $this->cadena_sql.=" AND pen_asi_cod=asi_cod ";
				$this->cadena_sql.=" AND asi_estado='A' ";
				$this->cadena_sql.=" AND pen_estado='A' ";
                $this->cadena_sql.=" ORDER BY pen_sem,asi_cod";
                break;

            case "rescatarAsignatura":
                $this->cadena_sql=" SELECT asi_cod, asi_nombre, pen_sem FROM acasi, acpen ";
                $this->cadena_sql.=" WHERE asi_cod=".$variable[0]. " AND pen_cra_cod=".$variable[1];
                $this->cadena_sql.=" AND pen_asi_cod=asi_cod ";
				$this->cadena_sql.=" AND asi_estado='A' ";
				$this->cadena_sql.=" AND pen_estado='A' ";
                $this->cadena_sql.=" ORDER BY pen_sem,asi_cod";
                break;
				
				
            case "periodo":
                $this->cadena_sql=" SELECT ape_ano, ape_per, ape_estado FROM acasperi ";
				//$this->cadena_sql.=" WHERE (ape_estado != 'I' and ape_estado != 'P' )";
		$this->cadena_sql.=" WHERE (ape_estado = 'A')";
                $this->cadena_sql.=" ORDER BY ape_estado  ASC";
            break;

            case "periodoconanterior":
                $this->cadena_sql=" SELECT ape_ano, ape_per, ape_estado FROM acasperi ";
                $this->cadena_sql.=" WHERE ape_estado <> 'I' ";
                $this->cadena_sql.=" ORDER BY ape_estado  ASC";
            break;

			
            case "hora":
                $this->cadena_sql="SELECT HOR_COD, HOR_LARGA ";
                $this->cadena_sql.=" FROM gehora ";
                $this->cadena_sql.=" WHERE HOR_COD<=21 ORDER BY HOR_COD ";

                break;

            case "infoAsignatura":
                $this->cadena_sql="SELECT pen_sem FROM acasi, acpen ";
                $this->cadena_sql.="WHERE asi_cod=".$variable[0]." and pen_cra_cod=".$variable[1]." ";
                $this->cadena_sql.="AND asi_cod=pen_asi_cod ";
                
                break;

            case "infoCurso":
               
                $this->cadena_sql="SELECT cur_nro_cupo FROM accurso ";
                $this->cadena_sql.=" WHERE cur_asi_cod=".$variable[0];
                $this->cadena_sql.=" AND cur_nro=".$variable[1];
                $this->cadena_sql.=" AND cur_ape_ano=".$variable[2];
                $this->cadena_sql.=" AND cur_ape_per=".$variable[3];

                break;

            case "insertarCurso":
                $this->cadena_sql=" INSERT INTO accurso ";
                $this->cadena_sql.="(cur_ape_ano, cur_ape_per, cur_asi_cod, cur_nro, cur_cra_cod, cur_nro_cupo, cur_estado, cur_semestre, cur_dep_cod, cur_sesion) ";
                $this->cadena_sql.="VALUES ('".$variable[0]."','".$variable[1]."','".$variable[2]."','".$variable[3]."','".$variable[4]."','".$variable[5]."','".$variable[6]."','".$variable[7]."','".$variable[8]."', '".$variable[9]."')";

                break;

            case "actualizarCurso":
                $this->cadena_sql=" UPDATE accurso ";
                $this->cadena_sql.=" SET cur_nro_cupo = ".$variable[4];
                $this->cadena_sql.=" WHERE cur_ape_ano = '".$variable[2]."'";
                $this->cadena_sql.=" AND cur_ape_per = '".$variable[3]."'";
                $this->cadena_sql.=" AND cur_asi_cod = '".$variable[0]."'";
                $this->cadena_sql.=" AND cur_nro = '".$variable[1]."'";

                break;

            case "verHorarioTemp":

                $this->cadena_sql="select HOR_SED_COD, HOR_SAL_COD, SED_ABREV from achorario";
                $this->cadena_sql.=" INNER JOIN gesede ON achorario.HOR_SED_COD=gesede.SED_COD";
                $this->cadena_sql.=" where hor_asi_cod=".$variable[0]." and hor_nro=".$variable[1]." and hor_dia_nro=".$variable[2]." and hor_hora=".$variable[3];
                $this->cadena_sql.=" AND hor_ape_ano=".$variable[4]." AND hor_ape_per=".$variable[5];
                break;

            case "consultaDependencia":
                $this->cadena_sql="SELECT emp_dep_cod ";
                $this->cadena_sql.="FROM peemp ";
                $this->cadena_sql.="WHERE emp_nro_iden='".$variable."' ";
                break;

            case "consultaGrupos":
                $this->cadena_sql="select ";
                $this->cadena_sql.="cur_nro ";
                $this->cadena_sql.=",cur_asi_cod ";
                $this->cadena_sql.=",asi_nombre ";
                $this->cadena_sql.=",cur_nro_cupo ";
				$this->cadena_sql.=",cur_nro_ins ";
				$this->cadena_sql.=",(cur_nro_cupo-cur_nro_ins) ";				
                $this->cadena_sql.="from ";
                $this->cadena_sql.="accurso ";
                $this->cadena_sql.=",acpen ";
                $this->cadena_sql.=",acasi ";
                $this->cadena_sql.="where ";
                $this->cadena_sql.="PEN_ASI_COD=CUR_ASI_COD ";
                $this->cadena_sql.="and asi_cod=pen_asi_Cod ";
                $this->cadena_sql.="and cur_cra_cod='".$variable[0]."' ";
                $this->cadena_sql.="and pen_nro='".$variable[1]."' ";
                $this->cadena_sql.="and pen_cra_cod='".$variable[0]."' ";
                $this->cadena_sql.="and cur_asi_cod='".$variable[2]."' ";
                $this->cadena_sql.="and cur_ape_ano='".$variable[3]."' ";
                $this->cadena_sql.="and cur_ape_per='".$variable[4]."' ";
                break;

			 case "consultaGruposTodos":
                $this->cadena_sql="select ";
                $this->cadena_sql.="cur_nro ";
                $this->cadena_sql.=",cur_asi_cod ";
                $this->cadena_sql.=",asi_nombre ";
                $this->cadena_sql.=",cur_nro_cupo ";
				$this->cadena_sql.=",cur_nro_ins ";
				$this->cadena_sql.=",(cur_nro_cupo-cur_nro_ins) ";
                $this->cadena_sql.="from ";
                $this->cadena_sql.="accurso ";
                $this->cadena_sql.=",acasi ";
                $this->cadena_sql.="where ";
                $this->cadena_sql.="ASI_COD=CUR_ASI_COD ";
                $this->cadena_sql.="and cur_cra_cod='".$variable[0]."' ";
                $this->cadena_sql.="and cur_ape_ano='".$variable[3]."' ";
                $this->cadena_sql.="and cur_ape_per='".$variable[4]."' ";
				$this->cadena_sql.="ORDER BY ".$variable[5]." ASC ";
                break;				

								
			 case "consultaGruposRapida":
                $this->cadena_sql="select ";
                $this->cadena_sql.="cur_nro ";
                $this->cadena_sql.=",cur_asi_cod ";
                $this->cadena_sql.=",asi_nombre ";
                $this->cadena_sql.=",cur_nro_cupo ";
				$this->cadena_sql.=",cur_nro_ins ";
				$this->cadena_sql.=",(cur_nro_cupo-cur_nro_ins) ";				
                $this->cadena_sql.="from ";
                $this->cadena_sql.="accurso ";
                $this->cadena_sql.=",acasi ";
                $this->cadena_sql.="where ";
                $this->cadena_sql.="ASI_COD=CUR_ASI_COD ";
                $this->cadena_sql.="and cur_cra_cod='".$variable[0]."' ";
                $this->cadena_sql.="and cur_asi_cod='".$variable[2]."' ";
                $this->cadena_sql.="and cur_ape_ano='".$variable[3]."' ";
                $this->cadena_sql.="and cur_ape_per='".$variable[4]."' ";
                break;
				//cambie esta consulta para no tener en cuenta el plan de estudios
             /*case "infoGrupo":
                $this->cadena_sql="select ";
                $this->cadena_sql.="cur_nro ";
                $this->cadena_sql.=",cur_asi_cod ";
                $this->cadena_sql.=",asi_nombre ";
                $this->cadena_sql.=",cur_nro_cupo ";
                $this->cadena_sql.="from ";
                $this->cadena_sql.="accurso ";
                $this->cadena_sql.=",acpen ";
                $this->cadena_sql.=",acasi ";
                $this->cadena_sql.="where ";
                $this->cadena_sql.="PEN_ASI_COD=CUR_ASI_COD ";
                $this->cadena_sql.="and asi_cod=pen_asi_Cod ";
                $this->cadena_sql.="and cur_cra_cod='".$variable[0]."' ";
                $this->cadena_sql.="and pen_nro='".$variable[1]."' ";
                $this->cadena_sql.="and cur_asi_cod='".$variable[2]."' ";
                $this->cadena_sql.="and cur_ape_ano='".$variable[3]."' ";
                $this->cadena_sql.="and cur_ape_per='".$variable[4]."' ";
                $this->cadena_sql.="and cur_nro='".$variable[5]."' ";
                break;*/
				
             case "infoGrupo":
                $this->cadena_sql="select ";
                $this->cadena_sql.="cur_nro ";
                $this->cadena_sql.=",cur_asi_cod ";
                $this->cadena_sql.=",asi_nombre ";
                $this->cadena_sql.=",cur_nro_cupo ";
                $this->cadena_sql.="from ";
                $this->cadena_sql.="accurso ";
                $this->cadena_sql.=",acasi ";
                $this->cadena_sql.="where ";
                $this->cadena_sql.="asi_cod=CUR_ASI_COD ";
                $this->cadena_sql.="and cur_cra_cod='".$variable[0]."' ";
                $this->cadena_sql.="and cur_asi_cod='".$variable[2]."' ";
                $this->cadena_sql.="and cur_ape_ano='".$variable[3]."' ";
                $this->cadena_sql.="and cur_ape_per='".$variable[4]."' ";
                $this->cadena_sql.="and cur_nro='".$variable[5]."' ";
                break;				

            case "eliminaCurso":
                $this->cadena_sql="DELETE ";
                $this->cadena_sql.="accurso ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="cur_cra_cod='".$variable[0]."' ";
                $this->cadena_sql.="AND cur_asi_cod='".$variable[2]."' ";
                $this->cadena_sql.="AND cur_nro='".$variable[3]."' ";
                $this->cadena_sql.="AND cur_ape_ano='".$variable[4]."' ";
                $this->cadena_sql.="AND cur_ape_per='".$variable[5]."' ";
                break;
        
            case "infoHorario":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="HOR_SED_COD, HOR_SAL_COD, DIA_NOMBRE,HOR_HORA ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="gedia,mntac.achorario ";
                $this->cadena_sql.="WHERE ";
				$this->cadena_sql.="dia_cod=HOR_DIA_NRO ";
                $this->cadena_sql.="AND hor_asi_cod='".$variable[0]."' ";
                $this->cadena_sql.="AND hor_nro='".$variable[1]."' ";
                $this->cadena_sql.="AND hor_ape_ano='".$variable[2]."' ";
                $this->cadena_sql.="AND hor_ape_per='".$variable[3]."' ";
                break;

            case "infoCarga":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="'S' ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="accarga ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="car_cur_asi_cod='".$variable[0]."' ";
                $this->cadena_sql.="AND car_cur_nro='".$variable[1]."' ";
                $this->cadena_sql.="AND car_ape_ano='".$variable[2]."' ";
                $this->cadena_sql.="AND car_ape_per='".$variable[3]."' ";
                break;

            case "infoInscritos":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="count(ins_est_cod) ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="acins ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="ins_asi_cod='".$variable[0]."' ";
                $this->cadena_sql.="AND ins_gr='".$variable[1]."' ";
                $this->cadena_sql.="AND ins_ano='".$variable[2]."' ";
                $this->cadena_sql.="AND ins_per='".$variable[3]."' ";
                break;

            case "valida_fecha":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24mmss'), ";//TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD')), ";
                $this->cadena_sql.="TO_CHAR(ACE_FEC_FIN,'YYYYmmddhh24mmss'), ";
                $this->cadena_sql.="TO_CHAR(ACE_FEC_FIN,'dd-Mon-yy') ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="accaleventos ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="ACE_ANIO =".$variable[1];
                $this->cadena_sql.=" AND ";
                $this->cadena_sql.="ACE_PERIODO =".$variable[2];
                $this->cadena_sql.=" AND ";
                $this->cadena_sql.="ACE_CRA_COD =".$variable[0];
                $this->cadena_sql.=" AND ";
                $this->cadena_sql.="ACE_COD_EVENTO = 87 ";
                $this->cadena_sql.=" AND ";
                $this->cadena_sql.="'".$variable[3]."' BETWEEN TO_CHAR(ACE_FEC_INI, 'YYYYmmddhh24mmss') AND TO_CHAR(ACE_FEC_FIN, 'YYYYmmddhh24mmss') ";
                break;

            case "infoSalon":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="SAL_CAPACIDAD ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.="GESALON ";
                $this->cadena_sql.="WHERE ";
                $this->cadena_sql.="SAL_SED_COD='".$variable[0]."' ";
                $this->cadena_sql.="AND ";
                $this->cadena_sql.="SAL_COD='".$variable[1]."' ";
                break;
             
        }



		//echo "<br/>$tipo=".$this->cadena_sql."<br>";

        return $this->cadena_sql;
    }

}

?>
