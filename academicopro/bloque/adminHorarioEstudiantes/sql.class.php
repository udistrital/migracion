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

class sql_adminHorarioEstudiantes extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
   function cadena_sql($configuracion,$tipo,$variable="")
	{

	 switch($tipo)
	 {


                #consulta de caracteristicas generales de espacios academicos en un plan de estudios
                case "consultaEstudiante":
                    $this->cadena_sql="SELECT est_cod, ";
                    $this->cadena_sql.="est_nombre, ";
                    $this->cadena_sql.="est_pen_nro, ";
                    $this->cadena_sql.="est_cra_cod, ";
                    $this->cadena_sql.="cra_nombre, ";
                    $this->cadena_sql.="est_acuerdo ";
                    $this->cadena_sql.="FROM acest ";
                    $this->cadena_sql.="INNER JOIN accra ON acest.est_cra_cod=";
                    $this->cadena_sql.="accra.cra_cod ";
                    $this->cadena_sql.="WHERE est_cod=".$variable;
//                    echo $this->cadena_sql;
//                    exit;
                break;


                case 'consultaGrupo':

                    $this->cadena_sql = "SELECT ins_asi_cod                         CODIGO,";
                    $this->cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)    GRUPO,";
                    $this->cadena_sql.=" asi_nombre                                 NOMBRE,";
                    $this->cadena_sql.=" ins_cred                                   CREDITOS, ";
                    $this->cadena_sql.=" cea_abr                                    CLASIFICACION, ";
                    $this->cadena_sql.= "ins_ano                                    ANIO,";
                    $this->cadena_sql.= "ins_per                                    PERIODO,";
                    $this->cadena_sql.=" ins_gr                                     CURSO ";
                    $this->cadena_sql.=" FROM acins";
                    $this->cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
                    $this->cadena_sql.=" INNER JOIN accursos ON ins_asi_cod=cur_asi_cod AND ins_gr=cur_id";
                    $this->cadena_sql.=" LEFT outer join geclasificaespac on cea_cod=ins_cea_cod ";
                    $this->cadena_sql.=" WHERE ins_est_cod=" . $variable;
                    $this->cadena_sql.=" AND ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $this->cadena_sql.=" AND ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $this->cadena_sql.=" AND ins_estado LIKE '%A%'";
                    //$this->cadena_sql.=" AND ins_cra_cod=" . $variable['codProyectoEstudiante'];
                    $this->cadena_sql.=" ORDER BY CODIGO";

                break;


                case 'horario_grupos':
                                        
                    $this->cadena_sql =" SELECT DISTINCT";
                    $this->cadena_sql.=" HOR_DIA_NRO HORA,";
                    $this->cadena_sql.=" HOR_HORA DIA,";
                    $this->cadena_sql.=" SED_ID COD_SEDE,";
                    $this->cadena_sql.=" SED_ID NOM_SEDE,";
                    $this->cadena_sql.=" SAL_EDIFICIO ID_EDIFICIO,";
                    $this->cadena_sql.=" EDI_NOMBRE NOM_EDIFICIO,";
                    $this->cadena_sql.=" HOR_SAL_ID_ESPACIO ID_SALON,";
                    $this->cadena_sql.=" SAL_NOMBRE NOM_SALON";
                    $this->cadena_sql.=" FROM ACHORARIOS";
                    $this->cadena_sql.=" INNER JOIN ACCURSOS ON hor_id_curso=cur_id";
                    $this->cadena_sql.=" Left Outer Join Gesalones ON hor_sal_id_espacio = sal_id_espacio AND sal_estado='A'";   
                    $this->cadena_sql.=" LEFT OUTER JOIN gesede ON sal_sed_id=sed_id ";
                    $this->cadena_sql.=" LEFT OUTER JOIN geedificio On Sal_Edificio=Edi_Cod";
                    $this->cadena_sql.=" WHERE CUR_ASI_COD=".$variable['CODIGO']; //codigo del espacio
                    $this->cadena_sql.=" AND cur_ape_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $this->cadena_sql.=" And Cur_Ape_Per=(Select Ape_Per From Acasperi Where Ape_Estado Like '%A%')";
                    $this->cadena_sql.=" AND Hor_id_curso=".$variable['GRUPO'];//numero de grupo
                    $this->cadena_sql.=" ORDER BY 1,2,3";   
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

                case 'clasificacion':

                    $this->cadena_sql =" SELECT id_clasificacion, ";
                    $this->cadena_sql.=" clasificacion_abrev, ";
                    $this->cadena_sql.=" clasificacion_nombre  ";
                    $this->cadena_sql.=" FROM ".$configuracion['prefijo']."espacio_clasificacion";
                    break;
                
                case "consultarDocentesGrupo":

                    $this->cadena_sql=" SELECT DISTINCT car_doc_nro,";
                    $this->cadena_sql.=" ((doc_nombre)||' '||(doc_apellido)) NOMBRE";
                    $this->cadena_sql.=" FROM accursos";
                    $this->cadena_sql.=" INNER JOIN achorarios ON hor_id_curso=cur_id";
                    $this->cadena_sql.=" INNER JOIN accargas ON car_hor_id=hor_id";
                    $this->cadena_sql.=" INNER JOIN acdocente on car_doc_nro=doc_nro_iden";
                    $this->cadena_sql.=" WHERE cur_ape_ano=".$variable['ANIO'];
                    $this->cadena_sql.=" AND cur_ape_per=".$variable['PERIODO'];
                    $this->cadena_sql.=" AND car_estado = 'A'";
                    $this->cadena_sql.=" and cur_id=".$variable['CURSO'];
                    break;

	}#Cierre de switch

	return $this->cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
