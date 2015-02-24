<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registro_codificarEstudiantesNuevos extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias

  public $configuracion;


  function __construct($configuracion){

    $this->configuracion=$configuracion;

  }
    function cadena_sql($tipo,$variable="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios
                        

            case 'periodoActivo':

                $cadena_sql="SELECT APE_ANO ANO, APE_PER PER FROM ACASPERI";
                $cadena_sql.=" WHERE APE_ESTADO LIKE '%A%'";
                break;

            case "consultarPermisosCodificacion":
                $cadena_sql="SELECT ";
                $cadena_sql.="TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24miss') FEC_INI, ";//TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD')), ";
                $cadena_sql.="TO_CHAR(ACE_FEC_FIN,'YYYYmmddhh24miss') FEC_FIN, ";
                $cadena_sql.="TO_CHAR(ACE_FEC_FIN,'dd-Mon-yy') FEC_FIN_DIA ";
                $cadena_sql.="FROM ";
                $cadena_sql.="accaleventos ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ACE_ANIO =".$variable['anio'];
                $cadena_sql.=" AND ";
                $cadena_sql.="ACE_PERIODO =".$variable['periodo'];
                $cadena_sql.=" AND ";
                $cadena_sql.="ACE_CRA_COD =".$variable['proyecto'];
                $cadena_sql.=" AND ";
                $cadena_sql.="ACE_COD_EVENTO IN (91) ";
                $cadena_sql.=" AND ";
                $cadena_sql.="'".date('YmdHis')."' BETWEEN TO_CHAR(ACE_FEC_INI, 'YYYYmmddhh24miss') AND TO_CHAR(ACE_FEC_FIN, 'YYYYmmddhh24miss') ";
            break;
            
            case 'consultarProyectosCoordinador':
                $cadena_sql="SELECT ";
                $cadena_sql.="cra_cod CODIGO, ";
                $cadena_sql.="cra_abrev NOMBRE, ";
                $cadena_sql.="tra_nivel NIVEL ";
                $cadena_sql.="FROM accra ";
                $cadena_sql.="INNER JOIN actipcra ON cra_tip_cra=tra_cod ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="CRA_EMP_NRO_IDEN='".$variable."'";
                break;

            case 'consultarDatosEstudiante':

                $cadena_sql=" SELECT *";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" WHERE est_cod=".$variable['codigo'];
                break;      
            

            case 'consultarCodigosEstudiantes':
                $cadena_sql="SELECT substr(est_cod,-3)";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" WHERE est_cod LIKE '".$variable['anoper']."%'";
                $cadena_sql.=" and est_cra_cod=".$variable['proyecto'];
                $cadena_sql.=" order by est_cod desc";
                break;
            
            case 'consultarTotalCodigosEstudiantes':
                $cadena_sql="SELECT count(*)";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" WHERE est_cod LIKE '".$variable['anoper']."%'";
                $cadena_sql.=" and est_cra_cod=".$variable['proyecto'];
                break;

            case 'insertarDatosBasicos':
                $cadena_sql=" INSERT INTO ACEST";
                $cadena_sql.=" (EST_COD,";
                $cadena_sql.=" EST_CRA_COD,";
                $cadena_sql.=" EST_NOMBRE,";
                $cadena_sql.=" EST_NRO_IDEN,";
                $cadena_sql.=" EST_TIPO_IDEN,";
                $cadena_sql.=" EST_LIB_MILITAR,";
                $cadena_sql.=" EST_NRO_DIS_MILITAR,";
                $cadena_sql.=" EST_CORRESPONDENCIA,";
                $cadena_sql.=" EST_DIRECCION,";
                $cadena_sql.=" EST_TELEFONO,";
                $cadena_sql.=" EST_ZONA_POSTAL,";
                $cadena_sql.=" EST_EXENTO,";
                $cadena_sql.=" EST_MOTIVO_EXENTO,";
                $cadena_sql.=" EST_PORCENTAJE,";
                $cadena_sql.=" EST_RENTA_LIQUIDA,";
                $cadena_sql.=" EST_PATRIMONIO_LIQUIDO,";
                $cadena_sql.=" EST_VALOR_MATRICULA,";
                $cadena_sql.=" EST_ESTADO_EST,";
                $cadena_sql.=" EST_JORNADA,";
                $cadena_sql.=" EST_ESTADO,";
                $cadena_sql.=" EST_PEN_NRO,";
                $cadena_sql.=" EST_SEXO,";
                $cadena_sql.=" EST_PBM,";
                $cadena_sql.=" EST_OPCION_MAT,";
                $cadena_sql.=" EST_INGRESOS_ANUALES,";
                $cadena_sql.=" EST_DIFERIDO,";
                $cadena_sql.=" EST_IND_CRED,";
                $cadena_sql.=" EST_NRO_IDEN_ANT,";
                $cadena_sql.=" EST_TIPO_IDEN_ANT,";
                $cadena_sql.=" EST_ACUERDO,";
                $cadena_sql.=" EST_FALLECIDO)";
                $cadena_sql.=" VALUES";
                $cadena_sql.=" (";
                $cadena_sql.=" '".$variable['codigo']."',";
                $cadena_sql.=" '".$variable['proyecto']."',";
                $cadena_sql.=" '".$variable['nombre']."',";
                $cadena_sql.=" '".$variable['documento']."',";
                $cadena_sql.=" '".$variable['tipo_documento']."',";
                $cadena_sql.=" '".$variable['num_libreta_militar']."',";
                $cadena_sql.=" '".$variable['distrito_militar']."',";
                $cadena_sql.=" '',";
                $cadena_sql.=" '".$variable['direccion']."',";
                $cadena_sql.=" '".$variable['telefono']."',";
                $cadena_sql.=" '".$variable['zona_postal']."',";
                $cadena_sql.=" '".$variable['exento']."',";
                $cadena_sql.=" '".$variable['motivo_exento']."',";
                $cadena_sql.=" '".$variable['porcentaje']."',";
                $cadena_sql.=" '".$variable['renta_liquida']."',";
                $cadena_sql.=" '".$variable['patrimonio_liquido']."',";
                $cadena_sql.=" '".$variable['matricula']."',";
                $cadena_sql.=" '".$variable['estado_academico']."',";
                $cadena_sql.=" 'M',";
                $cadena_sql.=" 'A',";
                $cadena_sql.=" '".$variable['pen_nro']."',";
                $cadena_sql.=" '".$variable['sexo']."',";
                $cadena_sql.=" '".$variable['pbm']."',";
                $cadena_sql.=" '',";
                $cadena_sql.=" '".$variable['ingresos_anuales']."',";
                $cadena_sql.=" 'N',";
                $cadena_sql.=" '".$variable['tipo_estudiante']."',";
                $cadena_sql.=" '".$variable['documento']."',";
                $cadena_sql.=" '".$variable['tipo_documento']."',";
                $cadena_sql.=" '".$variable['acuerdo']."',";
                $cadena_sql.=" 'N'";
                $cadena_sql.=" )";
                break;            

            

            case 'insertarOtrosDatos':
                $cadena_sql=" INSERT INTO ACESTOTR";
                $cadena_sql.=" (";
                $cadena_sql.=" EOT_COD,";
                $cadena_sql.=" EOT_FECHA_NAC,";
                $cadena_sql.=" EOT_COD_LUG_NAC,";
                $cadena_sql.=" EOT_SEXO,";
                $cadena_sql.=" EOT_ESTADO_CIVIL,";
                $cadena_sql.=" EOT_NRO_SNP,";
                $cadena_sql.=" EOT_PUNTOS_SNP,";
                $cadena_sql.=" EOT_TIPO_COLEGIO,";
                $cadena_sql.=" EOT_ASP_CRED,";
                $cadena_sql.=" EOT_INGRESOS_COSTEA,";
                $cadena_sql.=" EOT_ESTRATO_SOCIAL,";
                $cadena_sql.=" EOT_APE_ANO,";
                $cadena_sql.=" EOT_APE_PER,";
                $cadena_sql.=" EOT_ESTADO,";
                $cadena_sql.=" EOT_EMAIL,";
                $cadena_sql.=" EOT_TIPOSANGRE,";
                $cadena_sql.=" EOT_RH,";
                $cadena_sql.=" EOT_EMAIL_INS,";
                $cadena_sql.=" EOT_TEL_CEL";
                $cadena_sql.=" )";
                $cadena_sql.=" VALUES";
                $cadena_sql.=" (";
                $cadena_sql.=" '".$variable['codigo']."',";
                $cadena_sql.=" to_date('".$variable['fecha_nac']."','YYYY/MM/DD'),";
                $cadena_sql.=" '".$variable['lug_nac']."',";
                $cadena_sql.=" '".$variable['sexo']."',";
                $cadena_sql.=" '".$variable['estado_civil']."',";
                $cadena_sql.=" '".$variable['snp']."',";
                $cadena_sql.=" '".$variable['puntos']."',";
                $cadena_sql.=" '".$variable['tipo_colegio']."',";
                $cadena_sql.=" '".$variable['credencial']."',";
                $cadena_sql.=" '".$variable['ingresos_anuales']."',";
                $cadena_sql.=" '".$variable['estrato']."',";
                $cadena_sql.=" '".$variable['ano']."',";
                $cadena_sql.=" '".$variable['periodo']."',";
                $cadena_sql.=" 'A',";
                $cadena_sql.=" '".$variable['email']."',";
                $cadena_sql.=" '".$variable['grupo_sanguineo']."',";
                $cadena_sql.=" '".$variable['rh']."',";
                $cadena_sql.=" '".$variable['email_ins']."',";
                $cadena_sql.=" '".$variable['celular']."'";
                $cadena_sql.=" )";
                break;       
            
            

           
        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
