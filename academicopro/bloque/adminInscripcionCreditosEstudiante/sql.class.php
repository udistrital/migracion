<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminInscripcionCreditosEstudiante extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
   function cadena_sql($configuracion,$tipo,$variable="")
	{

	 switch($tipo)
	 {


                #consulta de caracteristicas generales de espacios academicos en un plan de estudios
                case "consultaEstudiante":
                    $this->cadena_sql="SELECT estudiante_codEstudiante, ";
                    $this->cadena_sql.="estudiante_primerNombre, ";
                    $this->cadena_sql.="estudiante_segundoNombre, ";
                    $this->cadena_sql.="estudiante_primerApellido, ";
                    $this->cadena_sql.="estudiante_segundoApellido, ";
                    $this->cadena_sql.="estudiante_idPlanEstudio, ";
                    $this->cadena_sql.="estudiante_idProyectoCurricular, ";
                    $this->cadena_sql.="planEstudio_nombre ";
                    $this->cadena_sql.="FROM sga_estudiante_creditos ";
                    $this->cadena_sql.="INNER JOIN sga_planEstudio ON sga_estudiante_creditos.estudiante_idPlanEstudio=";
                    $this->cadena_sql.="sga_planEstudio.id_planEstudio ";
                    $this->cadena_sql.="WHERE estudiante_codEstudiante=".$variable;
//                    echo $this->cadena_sql;
//                    exit;
                break;

                case "buscar_adiciones_estudiantes":
                    
                    $this->cadena_sql="SELECT modulos_estadoEstudiantes ";
                    $this->cadena_sql.="FROM ".$configuracion['prefijo']."modulosProyecto ";
                    $this->cadena_sql.="WHERE modulos_idProyectoCurricular = ".$variable[1];
                    $this->cadena_sql.=" AND modulos_idPlanEstudio = ".$variable[0];
                    $this->cadena_sql.=" AND modulos_idModulo = 4 ";
                    $this->cadena_sql.=" AND modulos_idEstado = 1 ";

//                    echo $this->cadena_sql;
//                    exit;
                break;


                case 'consultaGrupo':

                    $this->cadena_sql="SELECT horario_idEspacio, ";//0
                    $this->cadena_sql.="horario_idProyectoCurricular, ";//1
                    $this->cadena_sql.="horario_grupo, ";               //2
                    $this->cadena_sql.="horario_ano, ";                 //3
                    $this->cadena_sql.="horario_periodo, ";              //4
                    $this->cadena_sql.="espacio_nombre, ";              //5
                    $this->cadena_sql.="horario_codEstudiante, ";              //6
                    $this->cadena_sql.="horario_idPlanEstudio, ";              //7
                    $this->cadena_sql.="estudiante_primerNombre, estudiante_segundoNombre, estudiante_primerApellido, estudiante_segundoApellido ";    //8-11
                    $this->cadena_sql.="FROM sga_horario_estudiante ";
                    $this->cadena_sql.="INNER JOIN sga_espacio_academico ";
                    $this->cadena_sql.="ON horario_idEspacio=id_espacio ";
                    $this->cadena_sql.="INNER JOIN sga_estudiante_creditos ";
                    $this->cadena_sql.="ON horario_codEstudiante=estudiante_codEstudiante ";
                    $this->cadena_sql.="WHERE horario_codEstudiante=".$variable ;
                    $this->cadena_sql.=" AND horario_estado!=3";

//                    echo $this->cadena_sql;
//                    exit;

                break;


                case 'horario_grupos':

                    $this->cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                    $this->cadena_sql.="FROM ACHORARIO ";
                    $this->cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                    $this->cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                    $this->cadena_sql.="WHERE CUR_ASI_COD=".$variable[0][0]; //codigo del espacio
//                    $this->cadena_sql.=" AND CUR_CRA_COD=".$variable[0][1];  //codigo del proyecto curricular
                    $this->cadena_sql.=" AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $this->cadena_sql.=" AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $this->cadena_sql.=" AND HOR_NRO=".$variable[0][2];//numero de grupo
                    $this->cadena_sql.=" ORDER BY 1,2,3";//no cambiar el orden

                    //echo "cadena".$this->cadena_sql;
                    //exit;

                break;

                case 'consultaCreditosSemestre':

                    $this->cadena_sql="SELECT semestre_nroCreditosEstudiante ";
                    $this->cadena_sql.="FROM ".$configuracion['prefijo']."semestre_creditos_estudiante ";
                    $this->cadena_sql.="WHERE semestre_codEstudiante=".$variable; //codigo del espacio

                    //echo "cadena".$this->cadena_sql;
                    //exit;

                break;

                case 'consultaRegistroHorario':

                    $this->cadena_sql="SELECT horario_codEstudiante, horario_idProyectoCurricular, horario_idPlanEstudio, horario_ano, horario_periodo, espacio_nroCreditos ";
                    $this->cadena_sql.=" FROM ".$configuracion['prefijo']."horario_estudiante HE ";
                    $this->cadena_sql.="inner join ".$configuracion['prefijo']."espacio_academico EA on HE.horario_idEspacio=EA.id_espacio ";
                    $this->cadena_sql.="WHERE horario_codEstudiante=".$variable; //codigo del estudiante
                    $this->cadena_sql.=" AND horario_estado!='3'"; //codigo del estudiante

                    //echo "cadena".$this->cadena_sql;
                    //exit;

                break;

                case 'grabarCreditosNuevo':

                    $this->cadena_sql="INSERT INTO ".$configuracion['prefijo']."semestre_creditos_estudiante ";
                    $this->cadena_sql.=" VALUES( ";
                    $this->cadena_sql.="'".$variable[0]."',";
                    $this->cadena_sql.="'".$variable[1]."',";
                    $this->cadena_sql.="'".$variable[2]."',";
                    $this->cadena_sql.="'".$variable[3]."',";
                    $this->cadena_sql.="'".$variable[4]."',";
                    $this->cadena_sql.="'".$variable[5]."',";
                    $this->cadena_sql.="'0')";

                    //echo "cadena".$this->cadena_sql;
                    //exit;

                break;

	}#Cierre de switch

	return $this->cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>