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

class sql_adminPruebaAcademica extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
   function cadena_sql($configuracion,$tipo,$variable="")
	{
       
	 switch($tipo)
	 {


                #consulta de caracteristicas generales de espacios academicos en un plan de estudios
                case "consultarEstudiantes":
                    
                    $this->cadena_sql="select DISTINCT est_cod, not_nota, not_asi_cod, pen_cre, (not_nota*pen_cre), est.est_nombre, est.est_pen_nro, est.est_cra_cod ";
                    $this->cadena_sql.="from acnot notas ";
                    $this->cadena_sql.="INNER JOIN ACEST est ON notas.NOT_EST_COD=est.EST_COD ";
                    $this->cadena_sql.="INNER JOIN acpen acpen on notas.not_asi_cod=acpen.pen_asi_cod ";
                    $this->cadena_sql.="INNER JOIN accra on est.est_cra_cod=accra.cra_cod ";
                    $this->cadena_sql.="where notas.not_ano=2009 and notas.not_per=3 AND est.EST_IND_CRED like'%S%' and notas.not_est_reg like '%A%' ";
                    $this->cadena_sql.="ORDER BY 1 ";
//                   $this->cadena_sql="select DISTINCT
//                            est.est_cod,
//                            not_nota,
//                            not_asi_cod,
//                            pen_cre, (not_nota*pen_cre),
//                            est.est_nombre, est.est_pen_nro, est.est_cra_cod,
//                            cra_nombre, est.est_direccion, est.est_telefono,
//                            acestotr.eot_email, acestotr.eot_email_ins,
//                            acestotr.eot_nro_snp, acestotr.eot_puntos_snp, nombreoficial,mun_nombre
//                            from acnot notas
//                            INNER JOIN ACEST est ON notas.NOT_EST_COD=est.EST_COD
//                            INNER JOIN acpen acpen on notas.not_asi_cod=acpen.pen_asi_cod
//                            INNER JOIN accra on est.est_cra_cod=accra.cra_cod
//                            INNER JOIN acestotr  on est.est_cod= acestotr.eot_cod
//                            inner join acasp on est.est_nro_iden = acasp.asp_nro_iden
//                            inner join gecolegio on acasp.ASP_COD_COLEGIO = gecolegio.codigocolegio
//                            inner join gemunicipio on acestotr.eot_cod_mun_nac = gemunicipio.mun_cod
//                            where notas.not_ano=2009 and notas.not_per=3 AND est.EST_IND_CRED like'%S%'
//                            ORDER BY 1
//                            ";
//                    exit;
                break;

//                case "consultarEstudiantes":
//
//                    $this->cadena_sql="select DISTINCT est_cod, not_nota, not_asi_cod, pen_cre, (not_nota*pen_cre), est.est_nombre, est.est_pen_nro, est.est_cra_cod ";
//                    $this->cadena_sql.="from acnot notas ";
//                    $this->cadena_sql.="INNER JOIN ACEST est ON notas.not_est_cod= est.est_cod ";
//                    $this->cadena_sql.="INNER JOIN acpen on notas.not_asi_cod=acpen.pen_asi_cod and est.est_pen_nro= acpen.pen_nro ";
//                    $this->cadena_sql.="where not_ano=2009 and not_per=3 AND EST_IND_CRED like'%S%' and notas.not_est_reg like '%A%' ";
//                    $this->cadena_sql.="ORDER BY 1 ";
////                    echo $this->cadena_sql;
////                    exit;
//                break;
//
                case "estudiantesProblema":

                    $this->cadena_sql="select DISTINCT est_cod, not_nota, not_asi_cod, pen_cre, (not_nota*pen_cre), est.est_nombre, est.est_pen_nro, est.est_cra_cod ";
                    $this->cadena_sql.="from acnot notas ";
                    $this->cadena_sql.="INNER JOIN ACEST est ON notas.not_est_cod= est.est_cod ";
                    $this->cadena_sql.="INNER JOIN acpen on notas.not_asi_cod=acpen.pen_asi_cod and est.est_pen_nro= acpen.pen_nro ";
                    $this->cadena_sql.="where not_ano=2009 and not_per=3 AND EST_IND_CRED like '%S%' and est.est_estado like '%A%' and pen_nro<200 ";
                    $this->cadena_sql.="ORDER BY 1 ";
//                    echo $this->cadena_sql;
//                    exit;
                break;

                case "consultarEstudiantePrueba":

                    $this->cadena_sql="SELECT DISTINCT cra_nombre, est.est_direccion, est.est_telefono, acestotr.eot_email, acestotr.eot_email_ins, ";
                    $this->cadena_sql.=" acestotr.eot_nro_snp, acestotr.eot_puntos_snp, nombreoficial,mun_nombre";
                    $this->cadena_sql.=" from ACEST est ";
                    $this->cadena_sql.=" LEFT JOIN acestotr on est.est_cod= acestotr.eot_cod ";
                    $this->cadena_sql.=" LEFT JOIN accra on est.est_cra_cod=accra.cra_cod ";
                    $this->cadena_sql.=" LEFT join acasp on est.est_nro_iden = acasp.asp_nro_iden ";
                    $this->cadena_sql.=" LEFT join gecolegio on acasp.ASP_COD_COLEGIO = gecolegio.codigocolegio ";
                    $this->cadena_sql.=" LEFT join gemunicipio on acestotr.eot_cod_mun_nac = gemunicipio.mun_cod ";
                    $this->cadena_sql.=" where est.est_cod=".$variable;
//                    echo $this->cadena_sql;
//                    exit;
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
