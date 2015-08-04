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

class sql_adminCierreSemestre extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion="",$variable="")
	{            
        	switch($opcion)
		{	
			case "carreraCoordinador":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="cra_cod CODIGO, ";
				$this->cadena_sql.="cra_abrev NOMBRE ";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="ACCRA ";
				$this->cadena_sql.="WHERE ";
				$this->cadena_sql.="CRA_EMP_NRO_IDEN='".$variable."'";
				break;
                            
			case "proyecto_curricular":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="cra_cod CODIGO, ";
				$this->cadena_sql.="cra_abrev NOMBRE ";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="ACCRA ";
				$this->cadena_sql.="WHERE ";
                                $this->cadena_sql.="CRA_COD='".$variable."'";
				break;                            
                        
                        case "periodo":

                            $this->cadena_sql=" SELECT DISTINCT ape_ano ANIO, ";
                            $this->cadena_sql.=" ape_per PERIODO, ";
                            $this->cadena_sql.=" ape_estado ESTADO ";
                            $this->cadena_sql.="  FROM acasperi ";
                            $this->cadena_sql.=" WHERE ape_estado IN ('A')";
                            $this->cadena_sql.=" ORDER BY ape_estado  ASC";
                        break;

     
                            case "fechaactual":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="TO_CHAR(SYSDATE, 'YYYYmmddhh24mmss') FECHA  ";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="dual";
				break;
                            
                            case "valida_fecha":
                                $this->cadena_sql="SELECT ";
                                $this->cadena_sql.="Ace_Anio, ";//TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD')), ";
                                $this->cadena_sql.="Ace_Cod_Evento, ";
                                $this->cadena_sql.="Ace_Cra_Cod, ";
                                $this->cadena_sql.="Ace_Dep_Cod, ";
                                $this->cadena_sql.="Ace_Estado, ";
                                $this->cadena_sql.="to_char(Ace_Fec_Fin,'YYYYMMDD') ACE_FEC_FIN, ";
                                $this->cadena_sql.="to_char(Ace_Fec_Ini,'YYYYMMDD') ACE_FEC_INI, ";
                                $this->cadena_sql.="Ace_Periodo, ";
                                $this->cadena_sql.="Ace_Tip_Cra ";
                                $this->cadena_sql.="FROM ";
                                $this->cadena_sql.="accaleventos ";
                                $this->cadena_sql.="WHERE ";
                                $this->cadena_sql.="ACE_ANIO =".$variable['anio'];
                                $this->cadena_sql.=" AND ";
                                $this->cadena_sql.="ACE_PERIODO =".$variable['periodo'];
                                $this->cadena_sql.=" AND ";
                                $this->cadena_sql.="ACE_CRA_COD =".$variable['proyecto'];
                                $this->cadena_sql.=" AND ";
                                $this->cadena_sql.="ACE_COD_EVENTO =".$variable['evento'];
                                //$this->cadena_sql.=" AND ";
                                //$this->cadena_sql.="'".$variable['fecha']."' BETWEEN TO_CHAR(ACE_FEC_INI, 'YYYYmmddhh24mmss') AND TO_CHAR(ACE_FEC_FIN, 'YYYYmmddhh24mmss') ";
                            break;
                        
                            case 'insertarDatosEventos':
						        $this->cadena_sql="INSERT INTO ACCALEVENTOS ";
                                $this->cadena_sql.="(Ace_Cod_Evento, Ace_Cra_Cod,Ace_Tip_Cra, Ace_Dep_Cod, Ace_Anio, Ace_Periodo, Ace_Estado) ";
                                $this->cadena_sql.="VALUES ('".$variable['evento']."',";
                                $this->cadena_sql.="'".$variable['carrera']."',";
								$this->cadena_sql.="(select cra_tip_cra from accra where cra_cod='".$variable['carrera']."'),";
                                $this->cadena_sql.="(select cra_dep_cod from accra where cra_cod='".$variable['carrera']."'),";
                                $this->cadena_sql.="'".$variable['periodo'][0]['ANIO']."',";
								$this->cadena_sql.="'".$variable['periodo'][0]['PERIODO']."',";
                                $this->cadena_sql.="'A')";
								

                
                break;

			
			default:
				$this->cadena_sql="";
				break;
		}
		//echo "<br/><br/>".$opcion."=".$this->cadena_sql;
		return $this->cadena_sql;
	}
	
	
}
?>
