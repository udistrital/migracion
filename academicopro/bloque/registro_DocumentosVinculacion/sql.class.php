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

class sql_registroVinculacion extends sql
{
	function cadena_sql($opcion,$variable="")
	{

		switch($opcion)
		{       
                        case "datosUsuario":
                            //En ORACLE
                            $cadena_sql="SELECT ";
                            $cadena_sql.="doc_apellido DOC_APEL, ";
                            $cadena_sql.="doc_nombre DOC_NOM, ";
                            $cadena_sql.="doc_tip_iden DOC_TIP_IDEN, ";
                            $cadena_sql.="doc_nro_iden DOC_NRO_IDEN ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.="acdocente ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="doc_nro_iden=".$variable['identificacion']." ";
                            $cadena_sql.="AND ";
                            $cadena_sql.="doc_estado = 'A' ";
                            break;
                        
                        case "vinculaciones":
                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="dvin.dtv_ape_ano VIN_ANIO, ";
                            $cadena_sql.="dvin.dtv_ape_per VIN_PER, ";
                            $cadena_sql.="dvin.dtv_cra_cod VIN_CRA_COD , ";
                            $cadena_sql.="cra.cra_nombre VIN_CRA_NOM , ";
                            $cadena_sql.="dvin.dtv_tvi_cod VIN_COD, ";
                            $cadena_sql.="tvin.tvi_nombre VIN_NOMBRE, ";
                            $cadena_sql.="dvin.dtv_estado VIN_ESTADO, ";
                            $cadena_sql.="dvin.dtv_resolucion VIN_RESOLUCION, ";
                            $cadena_sql.="dvin.dtv_interno_res VIN_INT_RES ";
                            $cadena_sql.=" FROM ";
                            $cadena_sql.="acdoctipvin dvin ,actipvin tvin ,accra cra ";
                            $cadena_sql.=" WHERE ";
                            $cadena_sql.="dvin.dtv_tvi_cod=tvin.tvi_cod";
                            $cadena_sql.=" AND ";
                            $cadena_sql.="dvin.dtv_cra_cod=cra.cra_cod";
                            if(isset($variable['anio']))
                                    {
                                    $cadena_sql.=" AND ";
                                    $cadena_sql.="dvin.dtv_ape_ano=".$variable['anio']." ";
                                    $cadena_sql.=" AND ";
                                    $cadena_sql.="dvin.dtv_ape_per=".$variable['periodo']." ";
                                    $cadena_sql.=" AND ";
                                    $cadena_sql.="dvin.dtv_estado='A' ";
                                    }
                            $cadena_sql.=" AND ";
                            $cadena_sql.="dvin.dtv_doc_nro_iden=".$variable['identificacion']." ";
                            $cadena_sql.=" ORDER BY dvin.dtv_ape_ano DESC,dvin.dtv_ape_per DESC,dvin.dtv_cra_cod ";
                            break;
                            
                           
                       case "actualizaVinculacion":
                           
                            $cadena_sql=" UPDATE acdoctipvin ";
                            $cadena_sql.=" SET ";
                            $cadena_sql.=" DTV_RESOLUCION='".$variable['resolucion']."' ,";
                            $cadena_sql.=" DTV_INTERNO_RES='".$variable['internoRes']."' ";
                            $cadena_sql.=" WHERE DTV_APE_ANO=".$variable['vinAnio']." ";
                            $cadena_sql.=" AND DTV_APE_PER=".$variable['vinPer']." ";
                            $cadena_sql.=" AND DTV_CRA_COD=".$variable['vinCra']." ";
                            $cadena_sql.=" AND DTV_DOC_NRO_IDEN=".$variable['identificacion']." ";
                            $cadena_sql.=" AND DTV_TVI_COD=".$variable['vinCod']." ";

                            break;    
                       

                        case 'consultaProyectosCoordinador':
                            
                            $cadena_sql="SELECT cra_cod, ";
                            $cadena_sql.="cra_nombre  ";
                            $cadena_sql.="FROM accra  ";
                            $cadena_sql.="WHERE CRA_EMP_NRO_IDEN = ". $variable;
                            $cadena_sql.=" AND cra_estado = 'A'";
                            
                        break;

                        case "datosSecretario":
                            //En ORACLE
                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="usu_apellido SEC_APEL, ";
                            $cadena_sql.="usu_nombre SEC_NOM, ";
                            $cadena_sql.="usu_nro_iden SEC_NRO_IDEN, ";
                            $cadena_sql.="usu_dep_cod SEC_FACULTAD ";
                            $cadena_sql.="FROM ";
                            //$cadena_sql.="geclaves, geusuario  ";
                            $cadena_sql.=" geusuario  ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="usu_nro_iden=".$variable['identificacion']." ";
                            //$cadena_sql.="AND cla_tipo_usu=83  ";
                            $cadena_sql.="AND usu_tipo=8 ";
                            //$cadena_sql.="AND cla_estado='A' ";
                            $cadena_sql.="AND usu_estado='A'";
                            break;
                        
                      case "actosAdministrativos":
                            //En MYSQL
                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="acto_cod ACTO_COD, ";
                            $cadena_sql.="acto_nro_doc_docente ACTO_DOC_ID, ";
                            $cadena_sql.="acto_descripcion ACTO_DESC, ";
                            $cadena_sql.="acto_fecha_registro ACTO_FECHA, ";
                            $cadena_sql.="acto_nombre_archivo ACTO_NOM_ARCHIVO, ";
                            $cadena_sql.="acto_archivo_interno ACTO_AINT, ";
                            $cadena_sql.="acto_usuario ACTO_US ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.="sga_acto_administrativo_docente  ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="acto_nro_doc_docente=".$variable['identificacion']." ";
                            break;
                      
                      case "insertarActo":
                            //En MYSQL
                            $cadena_sql=" INSERT INTO ";
                            $cadena_sql.=" sga_acto_administrativo_docente (";
                            $cadena_sql.=" acto_cod,";
                            $cadena_sql.=" acto_nro_doc_docente,";
                            $cadena_sql.=" acto_descripcion,";
                            $cadena_sql.=" acto_fecha_registro,";
                            $cadena_sql.=" acto_nombre_archivo,";
                            $cadena_sql.=" acto_archivo_interno,";
                            $cadena_sql.=" acto_usuario)";
                            $cadena_sql.=" VALUES (";
                            $cadena_sql.=" ".$variable['codigo'].",";
                            $cadena_sql.=" ".$variable['identificacion'].",";
                            $cadena_sql.=" '".$variable['descripcion']."',";
                            $cadena_sql.=" '".$variable['fecha']."',";
                            $cadena_sql.=" '".$variable['acto']."',";
                            $cadena_sql.=" '".$variable['internoActo']."',";
                            $cadena_sql.=" ".$variable['usuario']." )";
                            break;
                        
                      case "borrarActo":
                            //En MYSQL
                            $cadena_sql="DELETE ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.="sga_acto_administrativo_docente  ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="acto_cod='".$variable."' ";
                            break;
                        
                      case "codActo":
                            //En MYSQL
                            $cadena_sql="SELECT max(acto_cod)+1 cod ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.="sga_acto_administrativo_docente  ";
                             break;  
                        
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>