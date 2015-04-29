
<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_admin_codificarEstudiantesNuevos extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias

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

            case 'periodoSiguiente':

                $cadena_sql="SELECT APE_ANO ANO, APE_PER PER FROM ACASPERI";
                $cadena_sql.=" WHERE APE_ESTADO LIKE '%X%'";
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

                $cadena_sql=" SELECT ead_cod AS CODIGO,";
                $cadena_sql.=" asp_cra_cod AS CRA_COD,";
                $cadena_sql.=" (asp_apellido||' '||asp_nombre) AS NOMBRE,";
                $cadena_sql.=" asp_nro_iden AS NRO_IDEN,";
                $cadena_sql.=" tdo_codvar,";
                $cadena_sql.=" asp_dis_militar,";
                $cadena_sql.=" asp_direccion,";
                $cadena_sql.=" asp_telefono,";
                $cadena_sql.=" ead_exento,";
                $cadena_sql.=" ead_motivo_exento,";
                $cadena_sql.=" exe_nombre,";
                $cadena_sql.=" ead_renta_liquida,";
                $cadena_sql.=" ead_patrimonio_liquido,";
                $cadena_sql.=" ead_valor_matricula,";
                $cadena_sql.=" ead_pen_nro,";
                $cadena_sql.=" asp_sexo,";
                $cadena_sql.=" ead_pbm,";
                $cadena_sql.=" ead_ingresos_anuales,";
                $cadena_sql.=" to_char(asp_fecha_nac,'YYYY/MM/DD') asp_fecha_nac,";
                $cadena_sql.=" ('1'|| LTRIM (RTRIM ((lpad(asp_dep_nac::text,2,'0') || '0'||SUBSTR (asp_lug_nac::text, -3, 3))))) asp_lug_nac,";
                $cadena_sql.=" asp_estado_civil,";
                $cadena_sql.=" asp_estrato,";
                $cadena_sql.=" asp_email,";
                $cadena_sql.=" acc_correo,";
                $cadena_sql.=" asp_tipo_sangre,";
                $cadena_sql.=" asp_rh,";
                $cadena_sql.=" asp_snp,";
                $cadena_sql.=" asp_ptos,";
                $cadena_sql.=" asp_tipo_colegio,";
                $cadena_sql.=" asp_cred";
                $cadena_sql.=" FROM acasp";
//                $cadena_sql.=" INNER JOIN ACASPERI ON ape_ano=asp_ape_ano AND ape_per=asp_ape_per AND ape_estado='A'";
                $cadena_sql.=" INNER JOIN acestadm ON ead_asp_ano=asp_ape_ano AND ead_asp_per=asp_ape_per AND ead_asp_cred=asp_cred";
                $cadena_sql.=" LEFT OUTER JOIN getipdocu ON cast(asp_tip_doc_act as integer) = tdo_codigo";
                $cadena_sql.=" LEFT OUTER JOIN acexento ON exe_cod=ead_motivo_exento";
                $cadena_sql.=" LEFT OUTER JOIN accorreo ON ead_cod=acc_est_cod";
                $cadena_sql.=" WHERE asp_admitido='A'";
                $cadena_sql.=" AND asp_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND asp_ape_per=".$variable['periodo'];
                if($variable['opcion']=='codigo')
                    {
                        $cadena_sql.=" AND ead_cod=".$variable['codigo'];
                    }else
                        {
                            $cadena_sql.=" AND ead_asp_cred=".$variable['codigo'];
						}  
                break;      
            
            case 'consultarEstadosAcademicos':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" estado_cod , ";
                $cadena_sql.=" (estado_cod ||' - '||estado_nombre) ESTADO";
                $cadena_sql.=" FROM ";
                $cadena_sql.=" acestado ";
                $cadena_sql.=" ORDER BY ESTADO ";
                break;

            case 'consultarMunicipios':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" lug_cod mun_cod,";
                $cadena_sql.=" lug_nombre mun_nombre";
                $cadena_sql.=" FROM ";
                $cadena_sql.=" gelugar ";
                $cadena_sql.=" WHERE lug_estado='A' ";
                $cadena_sql.=" ORDER BY lug_nombre ";
                break;
            
            case 'consultarEstadosCiviles':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" tec_codigo,";
                $cadena_sql.=" tec_nombre";
                $cadena_sql.=" FROM ";
                $cadena_sql.=" getipescivil ";
                $cadena_sql.=" WHERE tec_codigo<>0";
                $cadena_sql.=" ORDER BY tec_codigo ";
                break;
            
            case 'consultarTiposDocumentos':
                $cadena_sql=" SELECT tdo_codvar,";
                $cadena_sql.=" tdo_nombre";
                $cadena_sql.=" FROM getipdocu";
                $cadena_sql.=" ORDER BY tdo_codigo";
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
            
            case "consultarPlanesProyecto":
                $cadena_sql=" SELECT DISTINCT pen_nro PLAN";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" WHERE pen_cra_cod=".$variable;
                $cadena_sql.=" ORDER BY pen_nro desc";
                break;
            
        }#Cierre de switch     
        
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
