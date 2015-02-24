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

class sql_adminActualizarInscripcion extends sql
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

                case 'periodo_academico':

                            $this->cadena_sql="select ape_ano,ape_per from acasperi WHERE ape_estado LIKE '%A%'  ";

                        break;

                //Consulta los estudiantes oracle en creditos
                case "consultaInscripcionOracle":
                    
 		        $this->cadena_sql="select distinct ins_est_cod, ins_cra_cod, est_pen_nro, ins_ano, ins_per, ins_asi_cod, ins_gr ";
 		        $this->cadena_sql.="from acins ";
			$this->cadena_sql.=" inner join acest on acins.ins_est_cod=acest.est_cod";
                        $this->cadena_sql.=" inner join acpen on acins.ins_asi_cod=acpen.pen_asi_cod ";
                        $this->cadena_sql.=" where ins_ano = (select ape_ano from acasperi WHERE ape_estado LIKE '%A%' ) ";
                        $this->cadena_sql.=" and ins_per = (select ape_per from acasperi WHERE ape_estado LIKE '%A%' ) ";
                        $this->cadena_sql.=" and est_pen_nro>200 and pen_nro>200  ";
                        $this->cadena_sql.=" and est_ind_cred like '%S%' ";
                        $this->cadena_sql.=" and ins_cra_cod=".$variable[0];
                        $this->cadena_sql.=" ORDER BY 1 ";
                       // echo $this->cadena_sql;exit;
 		        
                break;

                //vaciar la tabla estudiante mysql
                case "borrarInscritos":
 		        $this->cadena_sql="delete from sga_horario_estudiante ";
			$this->cadena_sql.="where horario_idProyectoCurricular=".$variable[0];
			$this->cadena_sql.=" and horario_ano=".$variable[2];
			$this->cadena_sql.=" and horario_periodo=".$variable[3];
                        //echo $this->cadena_sql;
                        //exit;
                break;

                case "borrarCreditosEstudiantes":
 		        $this->cadena_sql="delete from  sga_semestre_creditos_estudiante ";
			$this->cadena_sql.=" where semestre_idProyectoCurricular=".$variable[0];
                        //echo $this->cadena_sql;
                        //exit;
                break;

                case "buscar_notas":
 		        $this->cadena_sql="select * from acnot ";
			$this->cadena_sql.=" where not_est_cod=".$variable[0];
			$this->cadena_sql.=" and not_asi_cod=".$variable[5];
			$this->cadena_sql.=" and not_nota<'30'";
                        //echo $this->cadena_sql;
                        //exit;
                break;


                //cargar estudiantes en la taba sga_estudiante_creditos
                case "insertarInscritos":
 		        $this->cadena_sql="INSERT INTO ";
			$this->cadena_sql.="sga_horario_estudiante ";
 		        $this->cadena_sql.="VALUES ( '".$variable[0]."', ";
                        $this->cadena_sql.="'".$variable[1]."', ";
                        $this->cadena_sql.="'".$variable[2]."', ";
                        $this->cadena_sql.="'".$variable[3]."', ";
                        $this->cadena_sql.="'".$variable[4]."', ";
                        $this->cadena_sql.="'".$variable[5]."', ";
                        $this->cadena_sql.="'".$variable[6]."', ";
                        $this->cadena_sql.="'".$variable[7]."' ";
                        $this->cadena_sql.="); ";
//                        echo $this->cadena_sql;
//                        exit;
                break;

                //cargar estudiantes en la taba sga_horario_estudiante
                case "insertarHorarioEstudiante":
 		        $this->cadena_sql="INSERT INTO ";
			$this->cadena_sql.="sga_horario_estudiante ";
 		        $this->cadena_sql.="VALUES ( '".$variable[0]."', ";
                        $this->cadena_sql.="'".$variable[1]."', ";
                        $this->cadena_sql.="'".$variable[2]."', ";
                        $this->cadena_sql.="'".$variable[3]."', ";
                        $this->cadena_sql.="'".$variable[4]."', ";
                        $this->cadena_sql.="'".$variable[5]."', ";
                        $this->cadena_sql.="'".$variable[6]."', ";
                        $this->cadena_sql.="'".$variable[7]."' ";
                        $this->cadena_sql.="); ";
//                        echo $this->cadena_sql;
//                        exit;
                break;
            
                //cargar estudiantes en la taba sga_estudiante_creditos
                case "actualizarInscritos":
 		        $this->cadena_sql="UPDATE sga_horario_estudiante SET  ";
			$this->cadena_sql.="horario_estado='2'";
 		        $this->cadena_sql.=" WHERE horario_codEstudiante=".$variable[0];
 		        $this->cadena_sql.=" AND horario_idEspacio=".$variable[5];
 		        $this->cadena_sql.=" AND horario_grupo=".$variable[6];
                        
//                        echo $this->cadena_sql;
//                        exit;
                break;

                case "verEstudiantesNuevos":
 		        $this->cadena_sql="select * from acins ";
			$this->cadena_sql.=" where ins_cra_cod=".$variable[0];
			$this->cadena_sql.=" and ins_est_cod like '20101%'";
                        //echo $this->cadena_sql;
                        //exit;
                break;

                case "verEstudiantesNuevosCod":
 		        $this->cadena_sql="select distinct ins_est_cod from acins ";
			$this->cadena_sql.=" where ins_cra_cod=".$variable[0];
			$this->cadena_sql.=" and ins_est_cod like '20101%'";
//                        echo $this->cadena_sql;
//                        exit;
                break;

                case "insertarBloquesEstudiantes":
 		        $this->cadena_sql="insert into ".$configuracion['prefijo']."registroBloquePlanEstudio ";
			$this->cadena_sql.=" VALUES (";
			$this->cadena_sql.="'".$variable[0]."'," ;
			$this->cadena_sql.="'".$variable[1]."'," ;
			$this->cadena_sql.="'".$variable[2]."'," ;
			$this->cadena_sql.="'".$variable[3]."'" ;
			$this->cadena_sql.=" )";
//                        echo $this->cadena_sql;
//                        exit;
                break;


	}#Cierre de switch

	return $this->cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
