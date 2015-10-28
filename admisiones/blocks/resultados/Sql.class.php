<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class Sqlresultados extends sql {

    var $miConfigurador;

    function __construct() {
        $this->miConfigurador = Configurador::singleton();
    }

    function cadena_sql($tipo, $variable = "") {

        /**
         * 1. Revisar las variables para evitar SQL Injection
         *
         */
        $prefijo = $this->miConfigurador->getVariableConfiguracion("prefijo");
        $idSesion = $this->miConfigurador->getVariableConfiguracion("id_sesion");

        switch ($tipo) {

            /**
             * Clausulas específicas
             */
            case "consultarAnioPeriodo": //PG: PSTGRESQL
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(aca_anio+1) as anio, ";
                $cadena_sql.="aca_anio+1 as ano ";
                //$cadena_sql.="aca_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acasperi ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_estado IN ('X','A') "; //X periodo nuevo para proceso admisiones.
                //$cadena_sql.="ORDER BY aca_id ASC ";
                //$cadena_sql.="acasperiev_estado NOT IN ('A') ";
                break;

            case "consultarPeriodo":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT (aca_periodo), ";
                $cadena_sql.="aca_periodo as periodo ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acasperi ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_estado IN ('X','A','P','I')";
                break;

            case "buscarPeriodo":
                $cadena_sql = "SELECT ";
                $cadena_sql.="aca_id, ";
                $cadena_sql.="aca_anio, ";
                $cadena_sql.="aca_periodo, ";
                $cadena_sql.="aca_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acasperi ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_estado IN ('X','A','P','I') ";
                $cadena_sql.="ORDER BY aca_id DESC";
                break;
            
            case "buscarContenidoColilla":
                $cadena_sql="SELECT ";
                $cadena_sql.="colilla_id, ";
                $cadena_sql.="colilla_nombre, ";
                $cadena_sql.="colilla_contenido, ";
                $cadena_sql.="colilla_carreras ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_colillas ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="colilla_carreras='".$variable['carreras']."' ";
                break;
            
            case "consultarEventos":
                $cadena_sql = "SELECT COUNT(des_id) des_id "; // --Si calendario vigente..
                $cadena_sql.="FROM admisiones.admisiones_eventos ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="des_id = ".$variable['id_evento']." ";
                $cadena_sql.="AND TO_CHAR(CURRENT_TIMESTAMP,'yyyymmdd') >= TO_CHAR(even_fecha_ini,'yyyymmdd') ";
                $cadena_sql.="AND TO_CHAR(CURRENT_TIMESTAMP,'yyyymmdd') <= TO_CHAR(even_fecha_fin,'yyyymmdd') ";
                $cadena_sql.="AND aca_id = ".$variable['id_periodo']." ";
                break;
            
            case "consultarAcaspRegistrados":
                $cadena_sql="SELECT ";
                $cadena_sql.="asp_id, ";
                $cadena_sql.="b.aca_id as aca_id, ";
                $cadena_sql.="a.ti_id, ";
                $cadena_sql.="med_id, ";
                $cadena_sql.="a.rba_id as rba_id, ";
                $cadena_sql.="asp_veces, ";
                $cadena_sql.="asp_ele_cod, ";
                $cadena_sql.="asp_apellido, ";
                $cadena_sql.="asp_nombre, ";
                $cadena_sql.="asp_electiva, ";
                $cadena_sql.="asp_nro_iden, ";
                $cadena_sql.="asp_cra_cod, ";
                $cadena_sql.="asp_dep_nac, ";
                $cadena_sql.="asp_lug_nac, ";
                $cadena_sql.="TO_CHAR(asp_fecha_nac, 'dd/mm/yyyy') as asp_fecha_nac, ";
                $cadena_sql.="asp_sexo, ";
                $cadena_sql.="asp_bio, ";
                $cadena_sql.="asp_qui, ";
                $cadena_sql.="asp_fis, ";
                $cadena_sql.="asp_soc, ";
                $cadena_sql.="asp_apt_verbal, ";
                $cadena_sql.="asp_esp_y_lit, ";
                $cadena_sql.="asp_apt_mat, ";
                $cadena_sql.="asp_con_mat, ";
                $cadena_sql.="asp_fil, ";
                $cadena_sql.="asp_his, ";
                $cadena_sql.="asp_geo, ";
                $cadena_sql.="asp_idioma, ";
                $cadena_sql.="asp_interdis, ";
                $cadena_sql.="asp_cod_inter, ";
                $cadena_sql.="asp_ptos, ";
                $cadena_sql.="asp_ptos_hom, ";
                $cadena_sql.="asp_profund, ";
                $cadena_sql.="asp_val_prof, ";
                $cadena_sql.="asp_profund2, ";
                $cadena_sql.="asp_val_prof2, ";
                $cadena_sql.="asp_profund3, ";
                $cadena_sql.="asp_val_prof3, ";
                $cadena_sql.="asp_entrevista, ";
                $cadena_sql.="asp_admitido, ";
                $cadena_sql.="asp_secuencia, ";
                $cadena_sql.="asp_convertir, ";
                $cadena_sql.="asp_procesado, ";
                $cadena_sql.="asp_tip_icfes, ";
                $cadena_sql.="asp_ptos_cal, ";
                $cadena_sql.="asp_cie_soc, ";
                $cadena_sql.="asp_puesto, ";
                $cadena_sql.="asp_cod_plantel, ";
                $cadena_sql.="asp_nro_iden_icfes, "; //47
                $cadena_sql.="asp_cod_colegio, ";
                $cadena_sql.="asp_estado_civil, ";
                $cadena_sql.="asp_tipo_sangre, ";
                $cadena_sql.="asp_rh, ";
                $cadena_sql.="asp_email, ";
                $cadena_sql.="asp_nro_iden_act, ";
                $cadena_sql.="asp_tip_doc_act, ";
                //$cadena_sql.="asp_nro_iden_icfes, ";
                $cadena_sql.="asp_def_sit_militar, ";
                $cadena_sql.="asp_ser_militar, ";
                $cadena_sql.="asp_dis_militar, ";
                $cadena_sql.="asp_snp, "; //59
                $cadena_sql.="asp_estrato, ";
                $cadena_sql.="asp_direccion, ";
                $cadena_sql.="asp_localidad, ";
                $cadena_sql.="asp_telefono, ";
                $cadena_sql.="asp_tipo_colegio, ";
                $cadena_sql.="asp_localidad_colegio, ";
                $cadena_sql.="asp_hermanos, ";
                $cadena_sql.="asp_sem_transcurridos, ";
                $cadena_sql.="asp_estrato_costea, ";
                $cadena_sql.="asp_valido_bto, ";
                $cadena_sql.="asp_tip_discap, ";
                $cadena_sql.="asp_observacion, ";
                $cadena_sql.="asp_estado, ";
                $cadena_sql.="rba_asp_cred, ";
                $cadena_sql.="aca_anio, ";
                $cadena_sql.="aca_periodo, ";
                $cadena_sql.="d.ti_nombre as ti_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acasp a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b, ";
                $cadena_sql.="admisiones.admisiones_acasperi c, ";
                $cadena_sql.="admisiones.admisiones_tip_ins d ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.rba_id = b.rba_id ";
                if(isset($variable['rba_id']))
                {    
                    $cadena_sql.="AND ";
                    $cadena_sql.="a.rba_id=".$variable['rba_id']." ";
                }
                if(isset($variable['aspirantes_id']))
                {
                    $cadena_sql.="AND ";
                    $cadena_sql.="asp_id=".$variable['aspirantes_id']." ";
                }
                if(isset($variable['credencial']))
                {
                    $cadena_sql.="AND ";
                    $cadena_sql.="rba_asp_cred=".$variable['credencial']." ";
                }    
                $cadena_sql.="AND ";
                $cadena_sql.="b.aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="b.aca_id = c.aca_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.ti_id = d.ti_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="asp_estado='A' ";
                break;
            
            case "consultarCarreras":
                $cadena_sql="SELECT ";
                $cadena_sql.="cra_cod, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="cra_abrev, ";
                $cadena_sql.="cra_se_ofrece ";
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.accra ";
                if(isset($variable['carrera']))
                {    
                    $cadena_sql.="WHERE ";
                    $cadena_sql.="cra_cod=".$variable['carrera']." ";
                }
                if(isset($variable['ingenieraTecnologica']))
                {
                    $cadena_sql.="AND ";
                    $cadena_sql.="cra_cod NOT IN ".$variable['ingenieraTecnologica']." ";
                }
                if(isset($variable['soloIngenieraTecnologica']))
                {
                    $cadena_sql.="AND ";
                    $cadena_sql.="cra_cod IN ".$variable['soloIngenieraTecnologica']." ";
                }    
                break; 
            
            case "tiposInscripcion":
                $cadena_sql="SELECT ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="(ti_cod||' - '|| ti_nombre) as nombre, ";
                $cadena_sql.="ti_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_tip_ins ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ti_id=".$variable['tipoInscripcion']." ";
                break;
                
            case "buscarSesion":
                $cadena_sql="SELECT ";
                $cadena_sql.="valor, sesionid, variable, expiracion ";
                $cadena_sql.="FROM sara_valor_sesion ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="sesionid ='".$variable['sesionId']."' ";
                $cadena_sql.="AND variable='rba_id' ";
                break;
                
        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }

}

?>
