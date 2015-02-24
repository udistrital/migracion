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

class sql_registroInconsistenciasEstudiantes extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
   function cadena_inconsistencias_sql($configuracion,$tipo,$variable="")
	{
       
	 switch($tipo)
	 {


                #consulta de caracteristicas generales de espacios academicos en un plan de estudios
            case "planEstudio":

                    //obtiene la fecha del sistema en formato timestamp
                    $fecha = date ( time ());
                    //$fecha = time();

                    $this->cadena_sql="SELECT DISTINCT ";
                    $this->cadena_sql.="CRA_COD, ";
                    $this->cadena_sql.="PEN_NRO, ";
                    $this->cadena_sql.="CRA_NOMBRE ";
                    $this->cadena_sql.="FROM ACCRA ";
                    $this->cadena_sql.="INNER JOIN GEUSUCRA ";
                    $this->cadena_sql.="ON ACCRA.CRA_COD = ";
                    $this->cadena_sql.="GEUSUCRA.USUCRA_CRA_COD ";
                    $this->cadena_sql.="INNER JOIN ACPEN ";
                    $this->cadena_sql.="ON ACCRA.CRA_COD = ";
                    $this->cadena_sql.="ACPEN.PEN_CRA_COD ";
                    $this->cadena_sql.="WHERE ";
                    $this->cadena_sql.="GEUSUCRA.USUCRA_NRO_IDEN = ";
                    $this->cadena_sql.=$variable." ";
                    //$this->cadena_sql.="'".$variable."' ";
                    $this->cadena_sql.="AND PEN_NRO > 200 ";
                    $this->cadena_sql.="ORDER BY ";
                    $this->cadena_sql.="CRA_NOMBRE";
                    //echo $this->cadena_sql;
                    //exit;
                    break;

            case "planEstudioAdmin":

                    //obtiene la fecha del sistema en formato timestamp
                    $fecha = date ( time ());
                    //$fecha = time();
                    $this->cadena_sql="SELECT DISTINCT ";
                    $this->cadena_sql.="CRA_COD, ";
                    $this->cadena_sql.="PEN_NRO, ";
                    $this->cadena_sql.="CRA_NOMBRE ";
                    $this->cadena_sql.="FROM ACCRA ";
                    $this->cadena_sql.="INNER JOIN ACPEN ";
                    $this->cadena_sql.="ON ACCRA.CRA_COD = ";
                    $this->cadena_sql.="ACPEN.PEN_CRA_COD ";
                    $this->cadena_sql.="WHERE ";
                    $this->cadena_sql.="PEN_NRO > 200 ";
                    $this->cadena_sql.="ORDER BY 2";
                    //echo $this->cadena_sql;
                    //exit;
                    break;

//                case "consultarEstudiantes":
//
//                    $this->cadena_sql="select est_cod, est_nombre, cra_cod, cra_nombre, est_pen_nro, est_ind_cred, est_estado ";
//                    $this->cadena_sql.="FROM ACEST ";
//                    $this->cadena_sql.="INNER JOIN ACCRA ON ACEST.EST_CRA_COD=ACCRA.CRA_COD ";
//                    $this->cadena_sql.="WHERE (est_ind_cred like '%S%' and est_pen_nro<200) ";
//                    $this->cadena_sql.="or (est_ind_cred like '%S%' and est_pen_nro is null) ";
//                    $this->cadena_sql.="order by est_cra_cod, est_cod";
//                    echo $this->cadena_sql;
//                    exit;
//                break;

                case "consultarEstudiantes":

                    $this->cadena_sql="select est_cod, est_nombre, cra_cod, cra_nombre, est_pen_nro, est_ind_cred, est_estado ";
                    $this->cadena_sql.="FROM ACEST ";
                    $this->cadena_sql.="INNER JOIN ACCRA ON ACEST.EST_CRA_COD=ACCRA.CRA_COD ";
                    $this->cadena_sql.="WHERE (est_ind_cred like '%S%' and est_cra_cod = ".$variable." and est_pen_nro<200) ";
                    $this->cadena_sql.="or (est_ind_cred like '%S%' and est_cra_cod = ".$variable." and est_pen_nro is null) ";
                    $this->cadena_sql.="order by est_cra_cod, est_cod";
//                    echo $this->cadena_sql;
//                    exit;
                break;

                case "consultarNoCreditos":

                    $this->cadena_sql="select est_cod, est_nombre, cra_nombre, est_pen_nro, est_ind_cred ";
                    $this->cadena_sql.="from acest ";
                    $this->cadena_sql.="INNER JOIN accra on (acest.est_cra_cod = accra.cra_cod) ";
                    $this->cadena_sql.="where est_cod>20092000000 ";
                    $this->cadena_sql.="and est_ind_cred not like '%S%' ";
                    $this->cadena_sql.="and est_cra_cod = ".$variable." ";
                    $this->cadena_sql.="ORDER BY est_cra_cod, est_cod";
//                    echo $this->cadena_sql;
//                    exit;
                break;
//                case "consultarNoCreditos":
//
//                    $this->cadena_sql="select distinct est_cod, est_nombre, cra_nombre, est_pen_nro, est_ind_cred ";
//                    $this->cadena_sql.="from acest ";
//                    $this->cadena_sql.="INNER JOIN accra on (acest.est_cra_cod = accra.cra_cod) ";
//                    $this->cadena_sql.="INNER JOIN acpen on (acpen.pen_cra_cod = accra.cra_cod) ";
//                    $this->cadena_sql.="where est_cod>20092000000 ";
//                    $this->cadena_sql.="and pen_nro>200 ";
//                    $this->cadena_sql.="and est_ind_cred not like '%S%' ";
//                    $this->cadena_sql.="ORDER BY est_cra_cod, est_cod";
//
//
                case "consultarAsignaturas":

                    $this->cadena_sql="select distinct not_est_cod, est_nombre, est_pen_nro, not_asi_cod, asi_nombre, not_cra_cod, pen_cre ";
                    $this->cadena_sql.="from acnot ";
                    $this->cadena_sql.="inner join acest on (acnot.not_est_cod = acest.est_cod) ";
                    $this->cadena_sql.="inner join acpen on (acnot.not_asi_cod = acpen.pen_asi_cod) ";
                    $this->cadena_sql.="and (acnot.not_cra_cod = acpen.pen_cra_cod) ";
                    $this->cadena_sql.="inner join acasi on (acpen.pen_asi_cod = acasi.asi_cod) ";
                    $this->cadena_sql.="where acest.est_cod>20092000000 and pen_cra_cod= ".$variable." and acpen.pen_cre is null and not_est_reg like '%A%' ";
                    $this->cadena_sql.="ORDER BY 1, 4";
                break;

                case "estudiantesReprobados":

                    $this->cadena_sql="select DISTINCT not_est_cod,  est_nombre, not_asi_cod, not_nota, est_cra_cod, est_pen_nro ";
                    $this->cadena_sql.="from acnot ";
                    $this->cadena_sql.="INNER JOIN ACEST ON ACNOT.NOT_EST_COD=ACEST.EST_COD ";
                    $this->cadena_sql.="where not_ano=2009 and not_per=3 and not_nota <30 and not_est_reg like '%A%' AND EST_IND_CRED like '%S%' ";
                    $this->cadena_sql.="ORDER BY 4, 5, 1 ";
//                    echo $this->cadena_sql;
//                    exit;
                break;

                case "estudiantes":

                    $this->cadena_sql="select count (DISTINCT not_est_cod) ";
                    $this->cadena_sql.="from acnot ";
                    $this->cadena_sql.="INNER JOIN ACEST ON ACNOT.NOT_EST_COD=ACEST.EST_COD ";
                    $this->cadena_sql.="where not_ano=2009 and not_per=3 and not_est_reg like '%A%' AND EST_IND_CRED like '%S%' ";
                    $this->cadena_sql.="ORDER BY 1 ";
//                    echo $this->cadena_sql;
//                    exit;
                break;

                case "insertarRep":

                    $this->cadena_sql="insert into ".$configuracion['prefijo']."prueba ";
                    $this->cadena_sql.="values ";
                    $this->cadena_sql.="(".$variable.")";
//                    echo $this->cadena_sql;
//                    exit;
                break;


            
	}#Cierre de switch

	return $this->cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
