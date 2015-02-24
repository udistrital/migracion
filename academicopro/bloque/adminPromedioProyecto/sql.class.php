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

class sql_adminPromedioProyecto extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
   function cadena_sql($configuracion,$tipo,$variable="")
	{
       
	 switch($tipo)
	 {

                case 'proyectos_curriculares':

                    $cadena_sql="select distinct cra_cod, cra_nombre, pen_nro ";
                    $cadena_sql.="from accra ";
                    $cadena_sql.="inner join acpen on accra.cra_cod=acpen.pen_cra_cod ";
                    //$cadena_sql.="inner join geusucra on accra.cra_cod=geusucra.USUCRA_CRA_COD ";
                    $cadena_sql.="where pen_nro>200 ";
                    $cadena_sql.=" and CRA_EMP_NRO_IDEN=".$variable;
                    $cadena_sql.=" order by 3";

                    break;
                
                case 'planes_estudio':

                    $cadena_sql="select distinct cra_cod, cra_nombre, pen_nro ";
                    $cadena_sql.="from accra ";
                    $cadena_sql.="inner join acpen on accra.cra_cod=acpen.pen_cra_cod ";
                    $cadena_sql.="where pen_nro>200 ";
                    $cadena_sql.=" order by 3";

                    break;

                case 'periodo_anterior':

                    $cadena_sql="SELECT APE_ANO, APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%P%' ";

                    break;

                case 'datos_coordinador':
                    $cadena_sql="select distinct cra_cod, cra_nombre, pen_nro ";
                    $cadena_sql.="from accra ";
                    //$cadena_sql.="INNER JOIN accra ON geusucra.usucra_cra_cod=accra.cra_cod ";
                    $cadena_sql.="INNER JOIN ACPEN ON accra.cra_cod=acpen.pen_cra_cod ";
                    $cadena_sql.=" where cra_emp_nro_iden=".$variable;
//                            $cadena_sql.=" and cra_se_ofrece like '%S%'";
                    $cadena_sql.=" and pen_nro>200";

                    break;

                case "consultarEstudiantesAcumulado":

                    $cadena_sql="select DISTINCT est_cod, not_nota, not_asi_cod, pen_cre, (not_nota*pen_cre), est.est_nombre, est.est_pen_nro, est.est_cra_cod ";
                    $cadena_sql.="from acnot notas ";
                    $cadena_sql.="INNER JOIN ACEST est ON notas.NOT_EST_COD=est.EST_COD ";
                    $cadena_sql.="INNER JOIN acpen acpen on notas.not_asi_cod=acpen.pen_asi_cod ";
                    $cadena_sql.="INNER JOIN accra on est.est_cra_cod=accra.cra_cod ";
                    $cadena_sql.=" WHERE est.EST_IND_CRED like'%S%' and notas.not_est_reg like '%A%' ";
                    $cadena_sql.=" AND est.est_cra_cod= ".$variable;
                    $cadena_sql.=" AND PEN_NRO>200 ";
                    $cadena_sql.="ORDER BY 1 ";
                    break;

                #consulta de caracteristicas generales de espacios academicos en un plan de estudios
                case "consultarEstudiantesPonderado":
                    
                    $cadena_sql="select DISTINCT est_cod, not_nota, not_asi_cod, pen_cre, (not_nota*pen_cre), est.est_nombre, est.est_pen_nro, est.est_cra_cod ";
                    $cadena_sql.="from acnot notas ";
                    $cadena_sql.="INNER JOIN ACEST est ON notas.NOT_EST_COD=est.EST_COD ";
                    $cadena_sql.="INNER JOIN acpen acpen on notas.not_asi_cod=acpen.pen_asi_cod ";
                    $cadena_sql.="INNER JOIN accra on est.est_cra_cod=accra.cra_cod ";
                    $cadena_sql.="where notas.not_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%P%') ";
                    $cadena_sql.=" and notas.not_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%P%') ";
                    $cadena_sql.=" AND est.EST_IND_CRED like'%S%' and notas.not_est_reg like '%A%' ";
                    $cadena_sql.=" AND est.est_cod= ".$variable;
                    $cadena_sql.=" AND PEN_NRO>200 ";
                    $cadena_sql.="ORDER BY 1 ";
                    
                break;

                case "estudiantesProblema":

                    $cadena_sql="select DISTINCT est_cod, not_nota, not_asi_cod, pen_cre, (not_nota*pen_cre), est.est_nombre, est.est_pen_nro, est.est_cra_cod ";
                    $cadena_sql.="from acnot notas ";
                    $cadena_sql.="INNER JOIN ACEST est ON notas.not_est_cod= est.est_cod ";
                    $cadena_sql.="INNER JOIN acpen on notas.not_asi_cod=acpen.pen_asi_cod and est.est_pen_nro= acpen.pen_nro ";
                    $cadena_sql.="where not_ano=2009 and not_per=3 AND EST_IND_CRED like '%S%' and est.est_estado like '%A%' and pen_nro<200 ";
                    $cadena_sql.="ORDER BY 1 ";
//                    echo $cadena_sql;
//                    exit;
                break;

                case "consultarEstudiante":

                    $cadena_sql="SELECT DISTINCT cra_nombre, est.est_direccion, est.est_telefono, acestotr.eot_email, acestotr.eot_email_ins, ";
                    $cadena_sql.=" acestotr.eot_nro_snp, acestotr.eot_puntos_snp, nombreoficial,mun_nombre";
                    $cadena_sql.=" from ACEST est ";
                    $cadena_sql.=" LEFT JOIN acestotr on est.est_cod= acestotr.eot_cod ";
                    $cadena_sql.=" LEFT JOIN accra on est.est_cra_cod=accra.cra_cod ";
                    $cadena_sql.=" LEFT join acasp on est.est_nro_iden = acasp.asp_nro_iden ";
                    $cadena_sql.=" LEFT join gecolegio on acasp.ASP_COD_COLEGIO = gecolegio.codigocolegio ";
                    $cadena_sql.=" LEFT join gemunicipio on acestotr.eot_cod_mun_nac = gemunicipio.mun_cod ";
                    $cadena_sql.=" where est.est_cod=".$variable;
//                    echo $cadena_sql;
//                    exit;
                break;

                case "estudiantesReprobados":

                    $cadena_sql="select DISTINCT not_est_cod,  est_nombre, not_asi_cod, not_nota, est_cra_cod, est_pen_nro ";
                    $cadena_sql.="from acnot ";
                    $cadena_sql.="INNER JOIN ACEST ON ACNOT.NOT_EST_COD=ACEST.EST_COD ";
                    $cadena_sql.="where not_ano=2009 and not_per=3 and not_nota <30 and not_est_reg like '%A%' AND EST_IND_CRED like '%S%' ";
                    $cadena_sql.="ORDER BY 4, 5, 1 ";
//                    echo $cadena_sql;
//                    exit;
                break;

                case "estudiantes":

                    $cadena_sql="select count (DISTINCT not_est_cod) ";
                    $cadena_sql.="from acnot ";
                    $cadena_sql.="INNER JOIN ACEST ON ACNOT.NOT_EST_COD=ACEST.EST_COD ";
                    $cadena_sql.="where not_ano=2009 and not_per=3 and not_est_reg like '%A%' AND EST_IND_CRED like '%S%' ";
                    $cadena_sql.="ORDER BY 1 ";
//                    echo $cadena_sql;
//                    exit;
                break;

                case "insertarRep":

                    $cadena_sql="insert into ".$configuracion['prefijo']."prueba ";
                    $cadena_sql.="values ";
                    $cadena_sql.="(".$variable.")";
//                    echo $cadena_sql;
//                    exit;
                break;


            
	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
