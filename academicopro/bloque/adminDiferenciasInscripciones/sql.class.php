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

class sql_adminDiferenciasInscripciones extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
   function cadena_sql($configuracion,$tipo,$variable="")
	{
       
	 switch($tipo)
	 {

             case "consultaPlanEstudioMysql":


                    $cadena_sql="SELECT ";
                    $cadena_sql.="id_planEstudio, ";
                    $cadena_sql.="planEstudio_nombre ";
                    $cadena_sql.="FROM sga_planEstudio ";
                    //echo $this->cadena_sql;
                    //exit;
            break;

                #consulta listado de planes de estudios en mysql
            case "estudiantesRegistroMysql":


                    $cadena_sql="SELECT distinct horario_codEstudiante ";
                    $cadena_sql.="from ".$configuracion['prefijo']."horario_estudiante ";
                    $cadena_sql.="where horario_idPlanEstudio= ".$variable;
                    $cadena_sql.=" order by horario_codEstudiante ";
                    //echo $this->cadena_sql;
                    //exit;
            break;

                #consulta listado de planes de estudios en mysql
            case "registrosMysql":


                    $cadena_sql="SELECT  horario_idProyectoCurricular, horario_idEspacio, horario_grupo  ";
                    $cadena_sql.="from ".$configuracion['prefijo']."horario_estudiante ";
                    $cadena_sql.="where horario_codEstudiante= ".$variable;
                    $cadena_sql.=" and horario_estado!='3' ";
                    $cadena_sql.=" order by horario_idEspacio ";
                    //echo $this->cadena_sql;
                    //exit;
            break;

            case "registrosOracle":


                    $cadena_sql="SELECT  ins_cra_cod, ins_asi_cod, ins_gr  ";
                    $cadena_sql.="from acins ";
                    $cadena_sql.="where ins_est_cod = ".$variable;
                    $cadena_sql.="  and ins_ano = (SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.="  and ins_per = (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.="  order by 2 ";
                    //echo $this->cadena_sql;
                    //exit;
            break;



            
	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
