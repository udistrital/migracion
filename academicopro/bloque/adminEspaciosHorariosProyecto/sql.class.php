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

class sql_adminEspaciosHorariosProyecto extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{
			case 'datos_coordinador':

                            $cadena_sql="select distinct pen_cra_cod, pen_nro from geusucra  ";
                            $cadena_sql.="inner join acpen on geusucra.usucra_cra_cod=acpen.pen_cra_cod ";
                            $cadena_sql.="inner join accra on geusucra.usucra_cra_cod=accra.cra_cod ";
                            $cadena_sql.="where usucra_nro_iden=".$variable;
                            $cadena_sql.=" and pen_nro>200 and pen_estado like '%A%'";
                            $cadena_sql.=" and cra_se_ofrece like '%S%'";
                            
                        break;

                     case 'grupos_proyecto':

                            $cadena_sql="SELECT DISTINCT HOR_NRO ";
                            $cadena_sql.="FROM ACHORARIO ";
                            $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                            $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                            $cadena_sql.=" AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                            $cadena_sql.=" AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                            $cadena_sql.=" ORDER BY 1";

                        break;
                     case 'espacios_carrera':

                            $cadena_sql="select distinct pen_asi_cod, asi_nombre, pen_sem ";
                            $cadena_sql.="from acpen ";
                            $cadena_sql.="inner join acasi on acpen.pen_asi_cod=acasi.asi_cod ";
                            $cadena_sql.="where pen_nro=".$variable[0];
                            $cadena_sql.=" and pen_cra_cod=".$variable[1];
                            $cadena_sql.=" order by pen_sem";

                        break;

                     case 'horario_grupos':

                            $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                            $cadena_sql.="FROM ACHORARIO ";
                            $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                            $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                            $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                            $cadena_sql.=" AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                            $cadena_sql.=" AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                            $cadena_sql.=" AND HOR_NRO=".$variable[3];
                            $cadena_sql.=" ORDER BY 1,2,3";

                        break;

                     case 'estudiantes_inscritos':

                            $cadena_sql="select ins_est_cod, est_nombre, cra_nombre ";
                            $cadena_sql.="from acins ";
                            $cadena_sql.="inner join acest on acins.ins_est_cod=acest.est_cod ";
                            $cadena_sql.="inner join accra on acins.ins_cra_cod=accra.cra_cod ";
                            $cadena_sql.=" and ins_asi_cod=".$variable[1];
                            $cadena_sql.=" and ins_gr=".$variable[2];
                            $cadena_sql.=" AND INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
//                            $cadena_sql.=" AND INS_ANO=2009 ";
                            $cadena_sql.=" AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
//                            $cadena_sql.=" AND INS_PER=3";
                            $cadena_sql.=" ORDER BY 1";

                        break;



		}
//		echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>