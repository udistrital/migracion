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

class sql_adminContarCupos extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
   function cadena_sql($configuracion,$tipo,$variable="")
	{
	 switch($tipo)
	 {
                case 'proyectos_curriculares':

                            $this->cadena_sql="select distinct cra_cod, cra_nombre, pen_nro ";
                            $this->cadena_sql.="from accra ";
                            $this->cadena_sql.="inner join acpen on accra.cra_cod=acpen.pen_cra_cod ";
                            $this->cadena_sql.="where pen_nro>200 ";
                            $this->cadena_sql.=" order by 3";

                        break;
                //Consulta los estudiantes oracle en creditos
                case "buscarInscripciones":
 		        $this->cadena_sql="select cur_cra_cod, cur_asi_cod, cur_nro, cur_nro_ins, cur_nro_cupo from accurso ";
                        $this->cadena_sql.="inner join acpen ON (cur_cra_cod=pen_cra_cod and cur_asi_cod=pen_asi_cod) ";
                        $this->cadena_sql.="where cur_ape_ano=2010 ";
                        $this->cadena_sql.="and cur_ape_per=1 ";
                        $this->cadena_sql.="and pen_nro>200 ";
                        $this->cadena_sql.="and cur_cra_cod= ".$variable;
                        $this->cadena_sql.="order By cur_cra_cod, cur_asi_cod, cur_nro ";

                        //echo $this->cadena_sql;
                        //exit;

                break;


                            //Consulta los estudiantes oracle en creditos
                case "contarCupos":
 		        $this->cadena_sql="select count(*) from acins ";
                        $this->cadena_sql.="inner join accurso ON (ins_asi_cod = cur_asi_cod and ins_gr= cur_nro and ins_ano= cur_ape_ano and ins_per= cur_ape_per) ";
                        $this->cadena_sql.="where ins_ano=2010 ";
                        $this->cadena_sql.="and ins_per=1 ";
                        $this->cadena_sql.="and cur_cra_cod=".$variable[0]." ";
                        $this->cadena_sql.="and ins_asi_cod=".$variable[1]." ";
                        $this->cadena_sql.="and ins_gr=".$variable[2]." ";
                        //echo $this->cadena_sql;
                        //exit;
                break;


                case 'actualizar_cupo':

                        $this->cadena_sql="UPDATE ACCURSO ";
                        $this->cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[0]." and ins_gr=".$variable[1]." and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'))";
                        $this->cadena_sql.=" WHERE CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                        $this->cadena_sql.="AND CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                        $this->cadena_sql.="AND CUR_ASI_COD=".$variable[0]." AND CUR_NRO=".$variable[1];


                break;


	}

	return $this->cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
