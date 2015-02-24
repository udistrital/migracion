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

class sql_registroSalon extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
	function cadena_sql($configuracion,$tipo,$variable="")
		{
			
			switch($tipo)
			{
								
                case "proyectos_curriculares":

                $this->cadena_sql=" SELECT UNIQUE CRA_COD, CRA_NOMBRE ";
                $this->cadena_sql.="FROM ".$configuracion['bd_proyecto'].".carrera ";
                $this->cadena_sql.="INNER JOIN geusucra ON accra.cra_cod = geusucra.usucra_cra_cod ";
                $this->cadena_sql.="WHERE geusucra.usucra_nro_iden = ".$variable;
                $this->cadena_sql.=" ORDER BY CRA_NOMBRE";
                break;

                case "hora":
                $this->cadena_sql=
                "
                SELECT HOR_LARGA FROM gehora
                WHERE hor_cod= ".$variable."
                ";

                break;

                case "dia":
                $this->cadena_sql=
                "
                SELECT DIA_NOMBRE FROM gedia
                WHERE DIA_COD= ".$variable."
                ";

                break;


                case "sede":
                $this->cadena_sql=
                "
                    select sed_cod,
                           sed_abrev,
                           sed_nombre
                      from gesede
                     where sed_estado = 'A'                       
                ";

                break;

                case "salon":
                $this->cadena_sql=
                "
                 select sal_cod,
                 sal_capacidad,
                 sal_tipo,
                 sal_descrip
                 from gesalon x
                 where sal_sed_cod = ".$variable." 
                 and sal_estado ='A'
                 order by sal_cod
                ";
                break;

                case "editar":
				$this->cadena_sql="UPDATE ";
				$this->cadena_sql.=$configuracion["prefijo"]."espacio_academico "; 
				$this->cadena_sql.="SET " ; 
				$this->cadena_sql.="`codigo_academica`='".$variable[1]."', ";
				$this->cadena_sql.="`codigo_creditos`='".$variable[2]."', ";
                              	$this->cadena_sql.="`nombre`='".$variable[3]."', ";
				$this->cadena_sql.="`nro_creditos`='".$variable[4]."', ";
                                $this->cadena_sql.="`horas_directo`='".$variable[5]."', ";
                                $this->cadena_sql.="`horas_cooperativo`='".$variable[6]."', ";
                                $this->cadena_sql.="`horas_autonomo`='".$variable[7]."', ";
				$this->cadena_sql.="`id_tipo`='".$variable[11]."', ";
				$this->cadena_sql.="`id_subtipo`='".$variable[12]."', ";
				$this->cadena_sql.="`id_naturaleza`='".$variable[13]."' ";
				//$this->cadena_sql.="`id_areaAcademica`='".$variable[19]."' ";
				$this->cadena_sql.="WHERE ";
				$this->cadena_sql.="`id_espacio`= ";
				$this->cadena_sql.=$variable[0];
				break;	
					
			
			}

                        
                  
			return $this->cadena_sql;
		
		}
}
?>
