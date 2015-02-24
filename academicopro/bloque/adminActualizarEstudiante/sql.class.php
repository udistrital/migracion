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

class sql_adminActualizarEstudiante extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
   function cadena_sql($configuracion,$tipo,$variable="")
	{
	 switch($tipo)
	 {



                //Consulta los estudiantes oracle en creditos
                case "consultaEstudiantesOracle":
 		        $this->cadena_sql="SELECT ";
			$this->cadena_sql.="est_cod estudiante_codEstudiante, ";
                        $this->cadena_sql.="SUBSTR(trim(est_nombre),0,INSTR(trim(est_nombre),' ',1,1)) estudiante_primerApellido, ";
 		        $this->cadena_sql.="SUBSTR(trim(est_nombre),INSTR(trim(est_nombre),' ',1,1) +1 ,INSTR(trim(est_nombre),' ',1,2) - INSTR(trim(est_nombre),' ',1,1)) estudiante_segundoApellido, ";
			$this->cadena_sql.="(case when INSTR(trim(est_nombre),' ',1,3)='0' AND INSTR(trim(est_nombre),' ',1,2)='0' then SUBSTR(trim(est_nombre),instr(trim(est_nombre),' ',1,1) +1,length(trim(est_nombre)) - instr(trim(est_nombre),' ',1,1)) when INSTR(trim(est_nombre),' ',1,3)='0' AND INSTR(trim(est_nombre),' ',1,2)>'0' then SUBSTR(trim(est_nombre),instr(trim(est_nombre),' ',1,2) +1,length(trim(est_nombre)) - instr(trim(est_nombre),' ',1,2)) else SUBSTR(trim(est_nombre),INSTR(trim(est_nombre),' ',1,2) +1 ,INSTR(trim(est_nombre),' ',1,3) - INSTR(trim(est_nombre),' ',1,2)) end) estudiante_primerNombre, ";
                        $this->cadena_sql.="(case when INSTR(trim(est_nombre),' ',1,3)='0' then ' ' else SUBSTR(trim(est_nombre),instr(trim(est_nombre),' ',1,3) +1,length(est_nombre) - instr(est_nombre,' ',1,3)) end) estudiante_segundoNombre, ";
 		        $this->cadena_sql.="est_pen_nro estudiante_idPlanEstudio, ";
 		        $this->cadena_sql.="est_cra_cod ";
			$this->cadena_sql.="FROM acest ";
                        $this->cadena_sql.="WHERE est_ind_cred like '%S%' ";
                        //$this->cadena_sql.="AND ROWNUM<=10 ";
                        //echo $this->cadena_sql;
                        //exit;
                break;

                //vaciar la tabla estudiante mysql
                case "borrarEstudiantes":
 		        $this->cadena_sql="TRUNCATE TABLE ";
			$this->cadena_sql.="sga_estudiante_creditos ";
                        //echo $this->cadena_sql;
                        //exit;
                break;


                //cargar estudiantes en la taba sga_estudiante_creditos
                case "insertarEstudiantes":
 		        $this->cadena_sql="INSERT INTO ";
			$this->cadena_sql.="sga_estudiante_creditos ";
 		        $this->cadena_sql.="(id_estudiante, ";
			$this->cadena_sql.="estudiante_codEstudiante, ";
                        $this->cadena_sql.="estudiante_primerNombre, ";
			$this->cadena_sql.="estudiante_segundoNombre, ";
                        $this->cadena_sql.="estudiante_primerApellido, ";
			$this->cadena_sql.="estudiante_segundoApellido, ";
                        $this->cadena_sql.="estudiante_idPlanEstudio, ";
			$this->cadena_sql.="estudiante_idProyectoCurricular ";
			$this->cadena_sql.=") ";
			$this->cadena_sql.="VALUES ( ";
                        $this->cadena_sql.="NULL, ";
                        $this->cadena_sql.="'".$variable[0]."', ";
                        $this->cadena_sql.="'".$variable[1]."', ";
                        $this->cadena_sql.="'".$variable[2]."', ";
                        $this->cadena_sql.="'".$variable[3]."', ";
                        $this->cadena_sql.="'".$variable[4]."', ";
                        $this->cadena_sql.="'".$variable[5]."', ";
                        $this->cadena_sql.="'".$variable[6]."' ";
                        $this->cadena_sql.="); ";
                        //echo $this->cadena_sql;
                        //exit;
                break;

	}#Cierre de switch

	return $this->cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
