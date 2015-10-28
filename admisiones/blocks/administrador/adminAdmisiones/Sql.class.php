<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqladminAdmisiones extends sql {

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

            case "insertarRegistro":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_acasperi ";
                $cadena_sql.="( ";
                //$cadena_sql.="instructivo_id, ";
                $cadena_sql.="aca_anio, ";
                $cadena_sql.="aca_periodo, ";
                $cadena_sql.="aca_estado ";
                $cadena_sql.=") ";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                //$cadena_sql.="'' , ";
                $cadena_sql.="'" . $variable['anio'] . "', ";
                $cadena_sql.="'" . $variable['per'] . "', ";
                $cadena_sql.="'X' ";
                $cadena_sql.=")";
                break;

            case "consultaEstados":
                $cadena_sql = "SELECT ";
                $cadena_sql.="est_nombre, ";
                $cadena_sql.="est_descripcion ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_estados ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="est_estado='A'";
                break;

            case "actualizaEstados":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_acasperi ";
                $cadena_sql.="SET ";
                $cadena_sql.="aca_estado='" . $variable['estadoFinal'] . "' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_estado ='" . $variable['estadoInicial'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id=" . $variable['estados'] . " ";
                break;

            case "actualizaEstadosA":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_acasperi ";
                $cadena_sql.="SET ";
                $cadena_sql.="aca_estado='" . $variable['estadoFinal'] . "' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_estado ='" . $variable['estadoInicial'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id<=" . $variable['estadosA'] . " ";
                break;

            case "actualizaEstado":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_acasperi ";
                $cadena_sql.="SET ";
                $cadena_sql.="aca_estado = '" . $variable['estadoFinal'] . "' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_estado ='" . $variable['estadoInicial'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_anio =" . $variable['anio'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_periodo =" . $variable['per'] . " ";
                break;

            case "consultarDesEventos":
                $cadena_sql = "SELECT ";
                $cadena_sql.="des_id, ";
                $cadena_sql.="des_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_deseventos ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="des_estado='A' ";
                $cadena_sql.="ORDER BY des_id ASC ";
                break;

            case "consultarEventos":
                $cadena_sql = "SELECT ";
                $cadena_sql.="even_id, ";
                $cadena_sql.="a.des_id, ";
                $cadena_sql.="des_nombre, ";
                $cadena_sql.="TO_CHAR(even_fecha_ini, 'dd/mm/yyyy'), ";
                $cadena_sql.="TO_CHAR(even_fecha_fin, 'dd/mm/yyyy'), ";
                $cadena_sql.="aca_id, ";
                $cadena_sql.="even_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_eventos a ";
                $cadena_sql.="INNER JOIN admisiones.admisiones_deseventos b ON a.des_id=b.des_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id= " . $variable['id_periodo'] . " ";
                break;

            case "consultarEventosRegistrados":
                $cadena_sql = "SELECT ";
                $cadena_sql.="even_id, ";
                $cadena_sql.="a.des_id, ";
                $cadena_sql.="des_nombre, ";
                $cadena_sql.="aca_id, ";
                $cadena_sql.="even_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_eventos a ";
                $cadena_sql.="INNER JOIN admisiones.admisiones_deseventos b ON a.des_id=b.des_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="even_estado='A' ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id= " . $variable['id_periodo'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.des_id= " . $variable['evento'] . " ";
                break;

            case "insertaEventos":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_eventos ";
                $cadena_sql.="( ";
                $cadena_sql.="des_id, ";
                $cadena_sql.="aca_id, ";
                $cadena_sql.="even_fecha_ini, ";
                $cadena_sql.="even_fecha_fin, ";
                $cadena_sql.="even_estado ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="" . $variable['evento'] . ", ";
                $cadena_sql.="" . $variable['id_periodo'] . ", ";
                $cadena_sql.="to_date('" . $variable['fechaIni'] . "', 'mm/dd/yyyy'), ";
                $cadena_sql.="to_date('" . $variable['fechaFin'] . "', 'mm/dd/yyyy'), ";
                $cadena_sql.="'A'";
                $cadena_sql.=")";
                break;

            case "actualizaEventos":
                $cadena_sql = "UPDATE admisiones.admisiones_eventos ";
                $cadena_sql.="SET ";
                $cadena_sql.="even_fecha_ini=(TO_DATE('" . $variable['fechaIni'] . "', 'mm/dd/yyyy')), ";
                $cadena_sql.="even_fecha_fin=(TO_DATE('" . $variable['fechaFin'] . "', 'mm/dd/yyyy')) ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="des_id='" . $variable['evento'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id='" . $variable['id_periodo'] . "' ";
                break;

            case "buscarMedio":
                $cadena_sql = "SELECT ";
                $cadena_sql.="med_id, ";
                $cadena_sql.="med_nombre, ";
                $cadena_sql.="med_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_medio ";
                break;

            case "insertaMedio":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_medio ";
                $cadena_sql.="( ";
                $cadena_sql.="med_nombre, ";
                $cadena_sql.="med_estado ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="'" . $variable['medio'] . "', ";
                $cadena_sql.="'A'";
                $cadena_sql.=")";
                break;

            case "actualizaMedio":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_medio ";
                $cadena_sql.="SET ";
                $cadena_sql.="med_nombre = '".$variable['medioNuevo']."', ";
                $cadena_sql.="med_estado = '".$variable['estadoNuevo']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="med_id =".$variable['id_medio']." ";
                break;

            case "consultaAnio":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(aca_anio), ";
                $cadena_sql.="aca_anio as anio ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acasperi ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_estado IN ('X','A','P')";
                break;

            case "consultarSalMinRegistrados":
                $cadena_sql = "SELECT ";
                $cadena_sql.="sal_anio, ";
                $cadena_sql.="sal_valor, ";
                $cadena_sql.="sal_porcentaje, ";
                $cadena_sql.="sal_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_salmin ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="sal_anio =" . $variable['anio'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="sal_estado ='A'";
                break;
            
            case "buscarSalMin":
                $cadena_sql = "SELECT ";
                $cadena_sql.="sal_id, ";
                $cadena_sql.="sal_anio, ";
                $cadena_sql.="sal_valor, ";
                $cadena_sql.="sal_porcentaje, ";
                $cadena_sql.="sal_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_salmin ";
                break;

            case "guardarSalMin":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_salmin ";
                $cadena_sql.="( ";
                $cadena_sql.="sal_anio, ";
                $cadena_sql.="sal_valor, ";
                $cadena_sql.="sal_porcentaje, ";
                $cadena_sql.="sal_estado ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="" . $variable['anio'] . ", ";
                $cadena_sql.="" . $variable['valor'] . ", ";
                $cadena_sql.="" . $variable['porcentaje'] . ", ";
                $cadena_sql.="'A'";
                $cadena_sql.=")";
                break;
        
            case "cambiarSalMin":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_salmin ";
                $cadena_sql.="SET ";
                $cadena_sql.="sal_anio = ".$variable['anio'].", ";
                $cadena_sql.="sal_valor = ".$variable['valor'].", ";
                $cadena_sql.="sal_porcentaje = ".$variable['porcentaje'].", ";
                $cadena_sql.="sal_estado = '".$variable['estado']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="sal_id =".$variable['id_salmin']." ";
                break;
            
            case "consultarLocalidadesRegistradas":
                $cadena_sql = "SELECT ";
                $cadena_sql.="loc_id, ";
                $cadena_sql.="loc_numero, ";
                $cadena_sql.="loc_nombre, ";
                $cadena_sql.="loc_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_localidad ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="loc_numero=".$variable['numero']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="loc_nombre='".$variable['localidad']."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="loc_estado='A'";
                break;
            
            case "insertaLocalidades":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_localidad ";
                $cadena_sql.="( ";
                $cadena_sql.="aca_id, ";
                $cadena_sql.="loc_numero, ";
                $cadena_sql.="loc_nombre, ";
                $cadena_sql.="loc_puntos_v, ";
                $cadena_sql.="loc_puntos_n, ";
                $cadena_sql.="loc_estado ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="".$variable['id_periodo'].", ";
                $cadena_sql.="".$variable['numero'].", ";
                $cadena_sql.="'".$variable['localidad']."', ";
                $cadena_sql.="".$variable['puntosn'].", ";
                $cadena_sql.="".$variable['puntosv'].", ";
                $cadena_sql.="'A'";
                $cadena_sql.=")";
                break;
            
            case "buscarLocalidades":
                $cadena_sql = "SELECT ";
                $cadena_sql.="loc_id, ";
                $cadena_sql.="loc_numero, ";
                $cadena_sql.="loc_nombre, ";
                $cadena_sql.="loc_puntos_v, ";
                $cadena_sql.="loc_puntos_n, ";
                $cadena_sql.="loc_estado, ";
                $cadena_sql.="aca_id ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_localidad ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                break;
            
            case "actualizaLocalidad":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_localidad ";
                $cadena_sql.="SET ";
                $cadena_sql.="loc_numero = ".$variable['numeroNuevo'].", ";
                $cadena_sql.="loc_nombre = '".$variable['localidadNueva']."', ";
                $cadena_sql.="loc_puntos_v = ".$variable['puntosvNuevo'].", ";
                $cadena_sql.="loc_puntos_n = ".$variable['puntosnNuevo'].", ";
                $cadena_sql.="loc_estado = '".$variable['estadoNuevo']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="loc_id =".$variable['id_localidad']." ";
                break;
            
            case "consultarEstratosRegistrados":
                $cadena_sql = "SELECT ";
                $cadena_sql.="estrato_id, ";
                $cadena_sql.="estrato_numero, ";
                $cadena_sql.="estrato_nombre, ";
                $cadena_sql.="estrato_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_estrato ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="estrato_numero=".$variable['numeroest']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="estrato_nombre='".$variable['estrato']."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="estrato_estado='A'";
                break;
            
             case "insertaEstratos":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_estrato ";
                $cadena_sql.="( ";
                $cadena_sql.="aca_id, ";
                $cadena_sql.="estrato_numero, ";
                $cadena_sql.="estrato_nombre, ";
                $cadena_sql.="estrato_puntos_v, ";
                $cadena_sql.="estrato_puntos_n, ";
                $cadena_sql.="estrato_estado, ";
                $cadena_sql.="estrato_puntos ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="".$variable['id_periodo'].", ";
                $cadena_sql.="".$variable['numeroest'].", ";
                $cadena_sql.="'".$variable['estrato']."', ";
                $cadena_sql.="".$variable['puntosn'].", ";
                $cadena_sql.="".$variable['puntosv'].", ";
                $cadena_sql.="'A', ";
                $cadena_sql.="".$variable['puntos']." ";
                $cadena_sql.=")";
                break;
            
            case "buscarEstratos":
                $cadena_sql = "SELECT ";
                $cadena_sql.="estrato_id, ";
                $cadena_sql.="estrato_numero, ";
                $cadena_sql.="estrato_nombre, ";
                $cadena_sql.="estrato_puntos_v, ";
                $cadena_sql.="estrato_puntos_n, ";
                $cadena_sql.="estrato_puntos, ";
                $cadena_sql.="estrato_estado, ";
                $cadena_sql.="aca_id ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_estrato ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                break;
            
            case "actualizaEstrato":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_estrato ";
                $cadena_sql.="SET ";
                $cadena_sql.="estrato_numero = ".$variable['numeroestNuevo'].", ";
                $cadena_sql.="estrato_nombre = '".$variable['estratoNuevo']."', ";
                $cadena_sql.="estrato_puntos_v = ".$variable['puntosvNuevo'].", ";
                $cadena_sql.="estrato_puntos_n = ".$variable['puntosnNuevo'].", ";
                $cadena_sql.="estrato_puntos = '".$variable['puntosestNuevo']."', ";
                $cadena_sql.="estrato_estado = '".$variable['estadoNuevo']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="estrato_id =".$variable['id_estrato']." ";
                break;
            
            case "buscarNombreInstructivo":
                $cadena_sql="SELECT ";
                $cadena_sql.="ins_id, ";
                $cadena_sql.="ins_nombre, ";
                $cadena_sql.="ins_contenido, ";
                $cadena_sql.="ins_tipo, ";
                $cadena_sql.="ins_orden ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_instructivo ";
                //$cadena_sql.="WHERE ";
                //$cadena_sql.="ins_tipo IN (".$variable['tipoInstructivo'].") ";
                $cadena_sql.="ORDER BY ins_orden ASC";
                break;
            
            case "buscarContenidoInstructivo":
                $cadena_sql="SELECT ";
                $cadena_sql.="ins_id, ";
                $cadena_sql.="ins_nombre, ";
                $cadena_sql.="ins_contenido, ";
                $cadena_sql.="ins_tipo ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_instructivo ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ins_tipo IN (".$variable['tipoInstructivo'].") ";
                $cadena_sql.="AND ";
                $cadena_sql.="ins_nombre IN ('".$variable['seccion']."') ";
                break;
            
            case "actualizaInstructivo":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_instructivo ";
                $cadena_sql.="SET ";
                $cadena_sql.="ins_contenido = '".$variable['instructivo']."', ";
                $cadena_sql.="aca_id = ".$variable['id_periodo']." ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ins_nombre ='".$variable['insNombre']."' ";
                break;
            
            case "consultarColillas":
                $cadena_sql="SELECT ";
                $cadena_sql.="colilla_id, ";
                $cadena_sql.="colilla_nombre, ";
                $cadena_sql.="colilla_contenido, ";
                $cadena_sql.="colilla_carreras, ";
                $cadena_sql.="colilla_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_colillas ";
                $cadena_sql.="ORDER BY 1";
                break;
            
            case "consultarColillasEditar":
                $cadena_sql="SELECT ";
                $cadena_sql.="colilla_id, ";
                $cadena_sql.="colilla_nombre, ";
                $cadena_sql.="colilla_contenido, ";
                $cadena_sql.="colilla_carreras, ";
                $cadena_sql.="colilla_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_colillas ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="colilla_id='".$variable['codColilla']."' ";
                $cadena_sql.="ORDER BY 1";
                break;
            
            case "consultarColillasRegistradas":
                $cadena_sql="SELECT ";
                $cadena_sql.="colilla_id, ";
                $cadena_sql.="colilla_nombre, ";
                $cadena_sql.="colilla_contenido, ";
                $cadena_sql.="colilla_carreras, ";
                $cadena_sql.="colilla_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_colillas ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="colilla_nombre='".$variable['nombre']."' ";
                break;
            
            case "insertaColillas":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_colillas ";
                $cadena_sql.="( ";
                $cadena_sql.="colilla_nombre, ";
                $cadena_sql.="colilla_contenido, ";
                $cadena_sql.="colilla_carreras, ";
                $cadena_sql.="colilla_estado ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="'".trim($variable['nombre'])."', ";
                $cadena_sql.="'".trim($variable['contenido'])."', ";
                $cadena_sql.="'".trim($variable['carreras'])."', ";
                $cadena_sql.="'A' ";
                $cadena_sql.=")";
                break;
            
            case "actualizaColillas":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_colillas ";
                $cadena_sql.="SET ";
                $cadena_sql.="colilla_nombre = '".$variable['nombreNuevo']."', ";
                $cadena_sql.="colilla_contenido = '".$variable['contenidoNuevo']."', ";
                $cadena_sql.="colilla_carreras = '".$variable['carrerasNuevas']."', ";
                $cadena_sql.="colilla_estado = '".$variable['estadoNuevo']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="colilla_id =".$variable['codColilla']." ";
                break;
            
            case "consultarPinesRegistrados":
                $cadena_sql="SELECT ";
                $cadena_sql.="rba_id, ";
                $cadena_sql.="aca_id, ";
                $cadena_sql.="rba_ref_pago, ";
                $cadena_sql.="rba_nro_iden, ";
                $cadena_sql.="rba_ban_cod, ";
                $cadena_sql.="rba_oficina, ";
                $cadena_sql.="rba_anio, ";
                $cadena_sql.="rba_mes, ";
                $cadena_sql.="rba_dia, ";
                $cadena_sql.="rba_valor, ";
                $cadena_sql.="rba_asp_cred, ";
                $cadena_sql.="rba_clave ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                break;
            
            case "insertaPines":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp ";
                $cadena_sql.="( ";
                $cadena_sql.="aca_id, ";
                $cadena_sql.="rba_ref_pago, ";
                $cadena_sql.="rba_nro_iden, ";
                $cadena_sql.="rba_ban_cod, ";
                $cadena_sql.="rba_oficina, ";
                $cadena_sql.="rba_anio, ";
                $cadena_sql.="rba_mes, ";
                $cadena_sql.="rba_dia, ";
                $cadena_sql.="rba_valor, ";
                $cadena_sql.="rba_asp_cred, ";
                $cadena_sql.="rba_clave, ";
                $cadena_sql.="rba_tipo ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="".$variable['id_periodo'].", ";
                $cadena_sql.="'".trim($variable['referencia'])."', ";
                $cadena_sql.="".trim($variable['identificacion']).", ";
                $cadena_sql.="".$variable['banco'].", ";
                $cadena_sql.="".$variable['codOf'].", ";
                $cadena_sql.="".$variable['anio'].", ";
                $cadena_sql.="".$variable['mes'].", ";
                $cadena_sql.="".$variable['dia'].", ";
                $cadena_sql.="".$variable['valor'].", ";
                $cadena_sql.="".$variable['credencial'].", ";
                $cadena_sql.="'".$variable['clave']."', ";
                $cadena_sql.="".$variable['rba_tipo']." ";
                $cadena_sql.=")";
                break;
            
            case "consultarPines":
                $cadena_sql="SELECT ";
                $cadena_sql.="rba_id, ";
                $cadena_sql.="aca_id, ";
                $cadena_sql.="rba_ref_pago, ";
                $cadena_sql.="rba_nro_iden, ";
                $cadena_sql.="rba_ban_cod, ";
                $cadena_sql.="rba_oficina, ";
                $cadena_sql.="rba_anio, ";
                $cadena_sql.="rba_mes, ";
                $cadena_sql.="rba_dia, ";
                $cadena_sql.="rba_valor, ";
                $cadena_sql.="rba_asp_cred, ";
                $cadena_sql.="rba_clave ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="ORDER BY rba_id DESC ";
                $cadena_sql.="LIMIT 100 ";
                break;
            
            case "consultarCarreras":
                $cadena_sql="SELECT ";
                $cadena_sql.="cra_cod, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="cra_abrev, ";
                $cadena_sql.="cra_se_ofrece ";
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.accra ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="cra_estado='A'";
                break;
            
            case "actualizaCarrera":
                $cadena_sql="UPDATE ";
                $cadena_sql.="accra ";
                $cadena_sql.="SET ";
                $cadena_sql.="cra_se_ofrece='".$variable['seOfrece']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="cra_cod=".$variable['codCra']."";
                break;
            
            case "buscartipInscripcion":
                $cadena_sql="SELECT ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="ti_cod, ";
                $cadena_sql.="ti_nombre, ";
                $cadena_sql.="ti_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_tip_ins ";
                $cadena_sql.="ORDER BY ti_id ASC ";
                break;
            
            case "tiposInscripcion":
                $cadena_sql="SELECT ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="(ti_cod||' - '|| ti_nombre) as nombre, ";
                $cadena_sql.="ti_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_tip_ins ";
                $cadena_sql.="ORDER BY ti_id ASC ";
                break;
                
            case "tiposInscripcionEspeciales":
                $cadena_sql="SELECT ";
                if(isset($variable['Idacasp'])){
                    $cadena_sql.="(ti_id ||'-'||".$variable['Idacasp']."||'-'||".$variable['id'].") as estrato_id, ";
                }else{
                    $cadena_sql.="ti_id, ";
                } 
                $cadena_sql.="(ti_cod||' - '|| ti_nombre) as nombre, ";
                $cadena_sql.="ti_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_tip_ins ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ti_estado='A' ";
                $cadena_sql.="ORDER BY ti_id ASC ";
                break;    
            
            case "insertaTipInscripcion":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_tip_ins ";
                $cadena_sql.="( ";
                $cadena_sql.="ti_cod, ";
                $cadena_sql.="ti_nombre, ";
                $cadena_sql.="ti_estado ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="".$variable['codTipIns'].", ";
                $cadena_sql.="'".$variable['nombreTipIns']."', ";
                $cadena_sql.="'".$variable['estado']."' ";
                $cadena_sql.=")";
                break;
            
            case "actualizaTipInscripcion":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_tip_ins ";
                $cadena_sql.="SET ";
                $cadena_sql.="ti_cod = ".$variable['numeroTipInsNuevo'].", ";
                $cadena_sql.="ti_nombre = '".$variable['nombreTipInsNuevo']."', ";
                $cadena_sql.="ti_estado = '".$variable['estadoNuevo']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ti_id =".$variable['id_tipIns']." ";
                break;
            
            case "buscarDiscapacidad":
                $cadena_sql="SELECT ";
                $cadena_sql.="dis_id, ";
                $cadena_sql.="dis_cod, ";
                $cadena_sql.="dis_nombre, ";
                $cadena_sql.="dis_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_discapacidad ";
                $cadena_sql.="ORDER BY dis_id ASC ";
                break;
            
            case "insertaDiscapacidad":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_discapacidad ";
                $cadena_sql.="( ";
                $cadena_sql.="dis_cod, ";
                $cadena_sql.="dis_nombre, ";
                $cadena_sql.="dis_estado ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="".$variable['numeroDiscapacidad'].", ";
                $cadena_sql.="'".$variable['nombreDiscapacidad']."', ";
                $cadena_sql.="'".$variable['estado']."' ";
                $cadena_sql.=")";
                break;
            
            case "actualizaDiscapacidad":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_discapacidad ";
                $cadena_sql.="SET ";
                $cadena_sql.="dis_cod = ".$variable['numeroDiscapacidadNuevo'].", ";
                $cadena_sql.="dis_nombre = '".$variable['nombreDiscapacidadNuevo']."', ";
                $cadena_sql.="dis_estado = '".$variable['estadoNuevo']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="dis_id =".$variable['id_discapacidad']." ";    
                break;
            
            case "consultarTipoPregunta":
                $cadena_sql="SELECT ";
                $cadena_sql.="tip_preg_id, ";
                $cadena_sql.="tip_preg_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_tipo_pregunta ";
                $cadena_sql.="ORDER BY tip_preg_id ASC ";
                break;
            
            case "buscarPreguntas":
                $cadena_sql="SELECT ";
                $cadena_sql.="preg_id, ";
                $cadena_sql.="preg_nombre, ";
                $cadena_sql.="preg_estado, ";
                $cadena_sql.="tip_preg_id, ";
                $cadena_sql.="des_id ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_preguntas ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="preg_nombre='".$variable['nombrePregunta']."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="des_id='".$variable['evento']."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="preg_estado='A' ";
                break;
            
            case "insertaPreguntas":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_preguntas ";
                $cadena_sql.="( ";
                $cadena_sql.="preg_nombre, ";
                $cadena_sql.="preg_estado, ";
                $cadena_sql.="tip_preg_id, ";
                $cadena_sql.="des_id, ";
                $cadena_sql.="pre_parametro1, ";
                $cadena_sql.="pre_parametro2, ";
                $cadena_sql.="pre_parametro3, ";
                $cadena_sql.="pre_parametro4 ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="'".$variable['nombrePregunta']."', ";
                $cadena_sql.="'".$variable['estado']."', ";
                $cadena_sql.="".$variable['preguntaTipo'].", ";
                $cadena_sql.="".$variable['evento'].", ";
                $cadena_sql.="'".$variable['parametro1']."', ";
                $cadena_sql.="'".$variable['parametro2']."', ";
                $cadena_sql.="'".$variable['parametro3']."', ";
                $cadena_sql.="'".$variable['parametro4']."' ";
                $cadena_sql.=")";
                break;
            
            case "buscarPreguntasRegistradas":
                $cadena_sql="SELECT ";
                $cadena_sql.="preg_id, ";
                $cadena_sql.="preg_nombre, ";
                $cadena_sql.="preg_estado, ";
                $cadena_sql.="a.tip_preg_id, ";
                $cadena_sql.="a.des_id, ";
                $cadena_sql.="b.tip_preg_nombre, ";
                $cadena_sql.="c. des_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_preguntas a, ";
                $cadena_sql.="admisiones.admisiones_tipo_pregunta b, ";
                $cadena_sql.="admisiones.admisiones_deseventos c ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.tip_preg_id=b.tip_preg_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.des_id=c.des_id ";
                break;
            
            case "actualizaPreguntas":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_preguntas ";
                $cadena_sql.="SET ";
                $cadena_sql.="preg_nombre = '".$variable['nuevoNombrePregunta']."', ";
                $cadena_sql.="preg_estado = '".$variable['estadoNuevo']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="preg_id =".$variable['preg_id']." "; 
                break;
            
            case "buscarEncabezados":
                $cadena_sql="SELECT ";
                $cadena_sql.="enc_id, ";
                $cadena_sql.="enc_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_encabezados ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="enc_nombre='".$variable['nombreEncabezado']."' ";
                break;
            
            case "insertaEncabezado":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_encabezados ";
                $cadena_sql.="( ";
                $cadena_sql.="enc_nombre ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="'".$variable['nombreEncabezado']."' ";
                $cadena_sql.=")";
                break;
            
            case "buscarEncabezadosRegistrados":
                $cadena_sql="SELECT ";
                $cadena_sql.="enc_id, ";
                $cadena_sql.="enc_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_encabezados ";
                $cadena_sql.="ORDER BY enc_id ASC ";
                break;
            
            case "actualizaEncabezado":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_encabezados ";
                $cadena_sql.="SET ";
                $cadena_sql.="enc_nombre = '".$variable['nuevoNombreEncabezado']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="enc_id =".$variable['enc_id']." "; 
                break;
            
            case "consultarInscripcionAcaspw":
                $cadena_sql="SELECT ";
                $cadena_sql.="aspw_id, ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="med_id, ";
                $cadena_sql.="a.rba_id as rba_id, ";
                $cadena_sql.="aspw_veces, ";
                $cadena_sql.="aspw_cra_cod, ";
                $cadena_sql.="aspw_nacionalidad, ";
                $cadena_sql.="aspw_dep_cod_nac, ";
                $cadena_sql.="aspw_mun_cod_nac, ";
                $cadena_sql.="TO_CHAR(aspw_fec_nac, 'mm/dd/yyyy') as aspw_fec_nac, ";
                $cadena_sql.="aspw_sexo, ";
                $cadena_sql.="aspw_estado_civil, ";
                $cadena_sql.="aspw_tipo_sangre, ";
                $cadena_sql.="aspw_rh, ";
                $cadena_sql.="aspw_email, ";
                $cadena_sql.="aspw_nro_iden_act, ";
                $cadena_sql.="aspw_nro_tip_act, ";
                $cadena_sql.="aspw_nro_iden_icfes, ";
                $cadena_sql.="aspw_nro_tip_icfes, ";
                $cadena_sql.="aspw_def_sit_militar, ";
                $cadena_sql.="aspw_ser_militar, ";
                $cadena_sql.="aspw_dis_militar, ";
                $cadena_sql.="aspw_snp, ";
                $cadena_sql.="aspw_discapacidad, ";
                $cadena_sql.="aspw_dep_cod_pro, ";
                $cadena_sql.="aspw_mun_cod_pro, ";
                $cadena_sql.="aspw_rural, ";
                $cadena_sql.="aspw_cabecera_mun, ";
                $cadena_sql.="aspw_estrato, ";
                $cadena_sql.="aspw_direccion, ";
                $cadena_sql.="aspw_localidad, ";
                $cadena_sql.="aspw_telefono, ";
                $cadena_sql.="aspw_vive_con, ";
                $cadena_sql.="aspw_trabaja, ";
                $cadena_sql.="aspw_tipo_colegio, ";
                $cadena_sql.="aspw_localidad_colegio, ";
                $cadena_sql.="aspw_matricula_colegio, ";
                $cadena_sql.="aspw_hermanos, ";
                $cadena_sql.="aspw_renta_liquida, ";
                $cadena_sql.="aspw_patrimonio_liquido, ";
                $cadena_sql.="aspw_ingresos_anuales, ";
                $cadena_sql.="aspw_vive_padre, ";
                $cadena_sql.="aspw_vive_madre, ";
                $cadena_sql.="aspw_direccion_padres, ";
                $cadena_sql.="aspw_telefono_padres, ";
                $cadena_sql.="aspw_tipo_discap, ";
                $cadena_sql.="aspw_valida_bto, ";
                $cadena_sql.="aspw_sem_transcurridos, ";
                $cadena_sql.="aspw_estrato_costea, ";
                $cadena_sql.="aspw_observacion, ";
                $cadena_sql.="aspw_estado, ";
                $cadena_sql.="rba_asp_cred, ";
                $cadena_sql.="des_id ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acaspw a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.rba_id = b.rba_id ";
                $cadena_sql.="AND ";
                    $cadena_sql.="a.rba_id NOT IN (SELECT rba_id ";
                    $cadena_sql.=" FROM ";
                    $cadena_sql.="admisiones.admisiones_acasp) ";
                $cadena_sql.="AND ";
                $cadena_sql.="aspw_estado='A' ";
                break;
            
            case "consultarInscripcionTransferencia":
                $cadena_sql="SELECT ";
                $cadena_sql.="atr_id, ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="a.rba_id as rba_id, ";
                $cadena_sql.="atr_cra_cod, ";
                $cadena_sql.="atr_universidad_proviene, "; 
                $cadena_sql.="atr_cra_proviene, ";
                $cadena_sql.="atr_semestre, ";
                $cadena_sql.="atr_motivo, ";
                $cadena_sql.="atr_nacionalidad, ";
                $cadena_sql.="atr_dep_cod_nac, ";
                $cadena_sql.="atr_mun_cod_nac, ";
                $cadena_sql.="TO_CHAR(atr_fec_nac, 'mm/dd/yyyy') as atr_fec_nac, ";
                $cadena_sql.="atr_sexo, ";
                $cadena_sql.="atr_estado_civil, ";
                $cadena_sql.="atr_tipo_sangre, ";
                $cadena_sql.="atr_rh, ";
                $cadena_sql.="atr_email, ";
                $cadena_sql.="atr_nro_iden_act, ";//17
                $cadena_sql.="atr_nro_tip_act, ";
                $cadena_sql.="atr_nro_iden_icfes, ";//18
                $cadena_sql.="atr_nro_tip_icfes, ";
                $cadena_sql.="atr_def_sit_militar, ";
                $cadena_sql.="atr_ser_militar, ";
                $cadena_sql.="atr_dis_militar, ";
                $cadena_sql.="atr_snp, ";
                $cadena_sql.="atr_discapacidad, ";
                $cadena_sql.="atr_dep_cod_pro, ";
                $cadena_sql.="atr_mun_cod_pro, ";
                $cadena_sql.="atr_rural, ";
                $cadena_sql.="atr_cabecera_mun, ";
                $cadena_sql.="atr_estrato, ";
                $cadena_sql.="atr_direccion, "; 
                $cadena_sql.="atr_localidad, ";
                $cadena_sql.="atr_telefono, ";
                $cadena_sql.="atr_vive_con, ";
                $cadena_sql.="atr_trabaja, ";
                $cadena_sql.="atr_tipo_colegio, ";
                $cadena_sql.="atr_localidad_colegio, ";
                $cadena_sql.="atr_matricula_colegio, ";
                $cadena_sql.="atr_hermanos, ";
                $cadena_sql.="atr_renta_liquida, ";
                $cadena_sql.="atr_patrimonio_liquido, ";
                $cadena_sql.="atr_ingresos_mensuales, ";
                $cadena_sql.="atr_vive_padre, ";
                $cadena_sql.="atr_vive_madre, ";
                $cadena_sql.="atr_direccion_padres, ";
                $cadena_sql.="atr_telefono_padres, ";
                $cadena_sql.="atr_observacion, ";
                $cadena_sql.="atr_estado, ";
                $cadena_sql.="rba_asp_cred ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_transferencia a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                    $cadena_sql.="a.rba_id NOT IN (SELECT rba_id ";
                    $cadena_sql.=" FROM ";
                    $cadena_sql.="admisiones.admisiones_acasp) ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.rba_id = b.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="atr_estado='A' ";
                break;
            
            case "actualizaAcaspw":
                $cadena_sql="UPDATE ";
                $cadena_sql.="admisiones.admisiones_acaspw ";
                $cadena_sql.="SET ";
                $cadena_sql.="aspw_snp='".$variable['nuevoSnp']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aspw_id='".$variable['id_aspw']."'";
                break;
            
            case "actualizaTransferencia":
                $cadena_sql="UPDATE ";
                $cadena_sql.="admisiones.admisiones_transferencia ";
                $cadena_sql.="SET ";
                $cadena_sql.="atr_snp='".$variable['nuevoSnp']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="atr_id='".$variable['id_aspw']."'";
                break;
            
            case "consultarReferenciaBancaria":
                $cadena_sql="SELECT ";
                $cadena_sql.="rba_id, ";
                $cadena_sql.="aca_id, ";
                $cadena_sql.="rba_ref_pago, ";
                $cadena_sql.="rba_nro_iden, ";
                $cadena_sql.="rba_ban_cod, ";
                $cadena_sql.="rba_oficina, ";
                $cadena_sql.="rba_anio, ";
                $cadena_sql.="rba_mes, ";
                $cadena_sql.="rba_dia, ";
                $cadena_sql.="rba_valor, ";
                $cadena_sql.="rba_asp_cred, ";
                $cadena_sql.="rba_clave ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="rba_ref_pago='".$variable['consulta']."' ";
                $cadena_sql.="OR ";
                $cadena_sql.="rba_nro_iden=".$variable['consulta']." ";
                $cadena_sql.="ORDER BY rba_id DESC ";
                break;
            
            case "consultarAcaspw":
                $cadena_sql="SELECT ";
                $cadena_sql.="aspw_id, ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="med_id, ";
                $cadena_sql.="a.rba_id as rba_id, ";
                $cadena_sql.="aspw_veces, ";
                $cadena_sql.="aspw_cra_cod, ";
                $cadena_sql.="aspw_nacionalidad, ";
                $cadena_sql.="aspw_dep_cod_nac, ";
                $cadena_sql.="aspw_mun_cod_nac, ";
                $cadena_sql.="TO_CHAR(aspw_fec_nac, 'dd/mm/yyyy') as aspw_fec_nac, ";
                $cadena_sql.="aspw_sexo, ";
                $cadena_sql.="aspw_estado_civil, ";
                $cadena_sql.="aspw_tipo_sangre, ";
                $cadena_sql.="aspw_rh, ";
                $cadena_sql.="aspw_email, ";
                $cadena_sql.="aspw_nro_iden_act, ";
                $cadena_sql.="aspw_nro_tip_act, ";
                $cadena_sql.="aspw_nro_iden_icfes, ";
                $cadena_sql.="aspw_nro_tip_icfes, ";
                $cadena_sql.="aspw_def_sit_militar, ";
                $cadena_sql.="aspw_ser_militar, ";
                $cadena_sql.="aspw_dis_militar, ";
                $cadena_sql.="aspw_snp, ";
                $cadena_sql.="aspw_discapacidad, ";
                $cadena_sql.="aspw_dep_cod_pro, ";
                $cadena_sql.="aspw_mun_cod_pro, ";
                $cadena_sql.="aspw_rural, ";
                $cadena_sql.="aspw_cabecera_mun, ";
                $cadena_sql.="aspw_estrato, ";
                $cadena_sql.="aspw_direccion, ";
                $cadena_sql.="aspw_localidad, ";
                $cadena_sql.="aspw_telefono, ";
                $cadena_sql.="aspw_vive_con, ";
                $cadena_sql.="aspw_trabaja, ";
                $cadena_sql.="aspw_tipo_colegio, ";
                $cadena_sql.="aspw_localidad_colegio, ";
                $cadena_sql.="aspw_matricula_colegio, ";
                $cadena_sql.="aspw_hermanos, ";
                $cadena_sql.="aspw_renta_liquida, ";
                $cadena_sql.="aspw_patrimonio_liquido, ";
                $cadena_sql.="aspw_ingresos_anuales, ";
                $cadena_sql.="aspw_vive_padre, ";
                $cadena_sql.="aspw_vive_madre, ";
                $cadena_sql.="aspw_direccion_padres, ";
                $cadena_sql.="aspw_telefono_padres, ";
                $cadena_sql.="aspw_tipo_discap, ";
                $cadena_sql.="aspw_valida_bto, ";
                $cadena_sql.="aspw_sem_transcurridos, ";
                $cadena_sql.="aspw_estrato_costea, ";
                $cadena_sql.="aspw_observacion, ";
                $cadena_sql.="aspw_estado, ";
                $cadena_sql.="rba_asp_cred, ";
                $cadena_sql.="des_id ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acaspw a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.rba_id = b.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="aspw_snp='".$variable['snp']."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="aspw_estado='A' ";
                break;
            
            case "consultarTransferencia":
                $cadena_sql="SELECT ";
                $cadena_sql.="atr_id, ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="a.rba_id as rba_id, ";
                $cadena_sql.="atr_cra_cod, ";
                $cadena_sql.="atr_universidad_proviene, "; 
                $cadena_sql.="atr_cra_proviene, ";
                $cadena_sql.="atr_semestre, ";
                $cadena_sql.="atr_motivo, ";
                $cadena_sql.="atr_nacionalidad, ";
                $cadena_sql.="atr_dep_cod_nac, ";
                $cadena_sql.="atr_mun_cod_nac, ";
                $cadena_sql.="TO_CHAR(atr_fec_nac, 'dd/mm/yyyy') as atr_fec_nac, ";
                $cadena_sql.="atr_sexo, ";
                $cadena_sql.="atr_estado_civil, ";
                $cadena_sql.="atr_tipo_sangre, ";
                $cadena_sql.="atr_rh, ";
                $cadena_sql.="atr_email, ";
                $cadena_sql.="atr_nro_iden_act, ";//17
                $cadena_sql.="atr_nro_tip_act, ";
                $cadena_sql.="atr_nro_iden_icfes, ";//18
                $cadena_sql.="atr_nro_tip_icfes, ";
                $cadena_sql.="atr_def_sit_militar, ";
                $cadena_sql.="atr_ser_militar, ";
                $cadena_sql.="atr_dis_militar, ";
                $cadena_sql.="atr_snp, ";
                $cadena_sql.="atr_discapacidad, ";
                $cadena_sql.="atr_dep_cod_pro, ";
                $cadena_sql.="atr_mun_cod_pro, ";
                $cadena_sql.="atr_rural, ";
                $cadena_sql.="atr_cabecera_mun, ";
                $cadena_sql.="atr_estrato, ";
                $cadena_sql.="atr_direccion, "; 
                $cadena_sql.="atr_localidad, ";
                $cadena_sql.="atr_telefono, ";
                $cadena_sql.="atr_vive_con, ";
                $cadena_sql.="atr_trabaja, ";
                $cadena_sql.="atr_tipo_colegio, ";
                $cadena_sql.="atr_localidad_colegio, ";
                $cadena_sql.="atr_matricula_colegio, ";
                $cadena_sql.="atr_hermanos, ";
                $cadena_sql.="atr_renta_liquida, ";
                $cadena_sql.="atr_patrimonio_liquido, ";
                $cadena_sql.="atr_ingresos_mensuales, ";
                $cadena_sql.="atr_vive_padre, ";
                $cadena_sql.="atr_vive_madre, ";
                $cadena_sql.="atr_direccion_padres, ";
                $cadena_sql.="atr_telefono_padres, ";
                $cadena_sql.="atr_observacion, ";
                $cadena_sql.="atr_estado, ";
                $cadena_sql.="rba_asp_cred ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_transferencia a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.rba_id = b.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="atr_snp='".$variable['snp']."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="atr_estado='A' ";
                break;
            
            case "consultarUltimasInscripciones":
                $cadena_sql="SELECT ";
                $cadena_sql.="asp_id, ";
                $cadena_sql.="rba_asp_cred, ";
                $cadena_sql.="asp_nombre, ";
                $cadena_sql.="asp_apellido, ";
                $cadena_sql.="asp_nro_iden_act, ";
                $cadena_sql.="asp_snp, ";
                $cadena_sql.="asp_cra_cod ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acasp a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.rba_id = b.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="asp_estado='A' ";
                $cadena_sql.="ORDER BY asp_id DESC ";
                $cadena_sql.="LIMIT 100 ";
                break;
            
            case "consultarAcaspRegistrados":
                $cadena_sql="SELECT ";
                $cadena_sql.="asp_id, ";
                $cadena_sql.="b.aca_id as aca_id, ";
                $cadena_sql.="a.ti_id as ti_id, ";
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
                if(isset($variable['consultaCredencial']))
                {
                    $cadena_sql.="AND ";
                    $cadena_sql.="rba_asp_cred=".$variable['consultaCredencial']." ";
                }
                if(isset($variable['codcra']))
                {
                    $cadena_sql.="AND ";
                    $cadena_sql.="asp_cra_cod=".$variable['codcra']." ";
                }
                if(isset($variable['noEspeciales'])){
                    $cadena_sql.="AND ";
                    $cadena_sql.="a.ti_id IN (".$variable['tipoInscripcion'].") ";
                }
                $cadena_sql.="AND ";
                $cadena_sql.="b.aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="b.aca_id = c.aca_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.ti_id = d.ti_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="asp_estado='A' ";
                $cadena_sql.="ORDER BY rba_asp_cred ";
                break;
            
            case "insertaAcasp":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_acasp ";
                $cadena_sql.="( ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="med_id, ";
                $cadena_sql.="rba_id, ";
                $cadena_sql.="asp_veces, ";
                $cadena_sql.="asp_ele_cod, ";
                $cadena_sql.="asp_apellido, ";
                $cadena_sql.="asp_nombre, ";
                $cadena_sql.="asp_electiva, ";
                $cadena_sql.="asp_nro_iden, ";
                $cadena_sql.="asp_cra_cod, ";
                $cadena_sql.="asp_dep_nac, ";
                $cadena_sql.="asp_lug_nac, ";
                $cadena_sql.="asp_fecha_nac, ";
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
                $cadena_sql.="asp_interdis, "; //27
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
                $cadena_sql.="asp_nro_iden_icfes, ";
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
                $cadena_sql.="asp_snp, ";
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
                $cadena_sql.="asp_estado ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="".$variable['tipoInscripcion'].", ";
                $cadena_sql.="".$variable['medio'].", ";
                $cadena_sql.="".$variable['rba_id'].", ";
                $cadena_sql.="".$variable['prestentaPor'].", ";
                $cadena_sql.="".$variable['cod_elec'].", ";
                $cadena_sql.="'".$variable['apellidos']."', ";
                $cadena_sql.="'".$variable['nombres']."', ";
                $cadena_sql.="".$variable['electiva'].", ";
                $cadena_sql.="".$variable['idenIcfes'].", ";
                $cadena_sql.="".$variable['carreras'].", ";
                $cadena_sql.="".$variable['departamento'].", ";
                $cadena_sql.="".$variable['municipio'].", ";
                $cadena_sql.="to_date('" . $variable['fechaNac'] . "', 'dd/mm/yyyy'), ";
                $cadena_sql.="'".$variable['sexo']."', ";
                $cadena_sql.="".trim($variable['biologia']).", ";
                $cadena_sql.="".trim($variable['quimica']).", ";
                $cadena_sql.="".trim($variable['fisica']).", ";
                $cadena_sql.="".trim($variable['sociales']).", ";
                $cadena_sql.="".trim($variable['aptVerbal']).", ";
                $cadena_sql.="".trim($variable['lenguaje']).", ";
                $cadena_sql.="".trim($variable['aptMatematica']).", ";
                $cadena_sql.="".trim($variable['matematica']).", ";
                $cadena_sql.="".trim($variable['filosofia']).", ";
                $cadena_sql.="".trim($variable['historia']).", ";
                $cadena_sql.="".trim($variable['geografia']).", ";
                $cadena_sql.="".trim($variable['ingles']).", ";
                $cadena_sql.="".trim($variable['interdis']).", ";
                $cadena_sql.="".trim($variable['cod_inter']).", ";
                $cadena_sql.="".trim($variable['asp_ptos']).", ";
                $cadena_sql.="".trim($variable['asp_ptos_hom']).", ";
                $cadena_sql.="".trim($variable['asp_profund']).", ";
                $cadena_sql.="".trim($variable['asp_val_prof']).", ";
                $cadena_sql.="".trim($variable['asp_profund2']).", ";
                $cadena_sql.="".trim($variable['asp_val_prof2']).", ";
                $cadena_sql.="".trim($variable['asp_profund3']).", ";
                $cadena_sql.="".trim($variable['asp_val_prof3']).", ";
                $cadena_sql.="".trim($variable['asp_entrevista']).", ";
                $cadena_sql.="'".$variable['admitido']."', ";
                $cadena_sql.="".$variable['secuencia'].", ";
                $cadena_sql.="".$variable['convertir'].", ";
                $cadena_sql.="'".$variable['procesando']."', ";
                $cadena_sql.="'".$variable['tipoIcfes']."', ";
                $cadena_sql.="".$variable['ptos_cal'].", ";
                $cadena_sql.="".$variable['cienciasSociales'].", ";
                $cadena_sql.="".$variable['puesto'].", ";
                $cadena_sql.="".$variable['cod_plantel'].", ";
                $cadena_sql.="".$variable['idenIcfes'].", ";
                $cadena_sql.="".$variable['codColegio'].", ";
                $cadena_sql.="".$variable['estadoCivil'].", ";
                $cadena_sql.="'".$variable['tipoSangre']."', ";
                $cadena_sql.="'".$variable['rh']."', ";
                $cadena_sql.="'".$variable['email']."', ";
                $cadena_sql.="".$variable['documentoActual'].", ";
                $cadena_sql.="".$variable['tipDocActual'].", ";
                //$cadena_sql.="".$variable['documentoIcfes'].", ";
                $cadena_sql.="'', ";
                $cadena_sql.="'', ";
                $cadena_sql.="null, ";
                $cadena_sql.="'".$variable['snp']."', ";
                $cadena_sql.="".$variable['estratoResidencia'].", ";
                $cadena_sql.="'".$variable['direccionResidencia']."', ";
                $cadena_sql.="".$variable['localidadResidencia'].", ";
                $cadena_sql.="'".$variable['telefono']."', ";
                $cadena_sql.="'".$variable['tipoColegio']."', ";
                $cadena_sql.="'".$variable['localidadColegio']."', ";
                $cadena_sql.="'', ";
                $cadena_sql.="".$variable['numSemestres'].", ";
                $cadena_sql.="".$variable['estratoCosteara'].", ";
                $cadena_sql.="'".$variable['valido']."', ";
                $cadena_sql.="".$variable['discapacidad'].", ";
                $cadena_sql.="'".$variable['observaciones']."', ";
                $cadena_sql.="'A' ";
                $cadena_sql.=")";
                break;
            
            case "actualizaInscripcion":
                $cadena_sql="UPDATE ";
                $cadena_sql.="admisiones.admisiones_acasp ";
                $cadena_sql.="SET ";
                $cadena_sql.="asp_snp='".$variable['registroIcfes']."', ";
                $cadena_sql.="asp_cra_cod =".$variable['carrera'].", ";
                $cadena_sql.="ti_id =".$variable['tipoInscripcion'].", ";
                $cadena_sql.="asp_apellido ='".$variable['apellidos']."', ";
                $cadena_sql.="asp_nombre ='".$variable['nombres']."', ";
                $cadena_sql.="asp_nro_iden_act =".$variable['documento'].", ";
                $cadena_sql.="asp_sexo ='".$variable['sexo']."', ";
                $cadena_sql.="med_id =".$variable['medio'].", ";
                $cadena_sql.="asp_localidad_colegio =".$variable['localidadColegio'].", ";
                $cadena_sql.="asp_localidad =".$variable['localidadResidencia'].", ";        
                $cadena_sql.="asp_estrato =".$variable['estratoResidencia'].", ";       
                $cadena_sql.="asp_ser_militar ='".$variable['serMilitar']."', ";
                $cadena_sql.="asp_ptos =".$variable['puntajeTotal'].", ";
                $cadena_sql.="asp_ptos_cal =".$variable['puntajeCal'].", ";
                $cadena_sql.="asp_cie_soc =".$variable['cieSociales'].", ";
                $cadena_sql.="asp_bio =".$variable['biologia'].", ";
                $cadena_sql.="asp_qui =".$variable['quimica'].", ";
                $cadena_sql.="asp_fis =".$variable['fisica'].", ";
                $cadena_sql.="asp_soc =".$variable['sociales'].", ";
                $cadena_sql.="asp_apt_verbal =".$variable['aptitudVerbal'].", ";
                $cadena_sql.="asp_esp_y_lit =".$variable['espaniolLit'].", ";
                $cadena_sql.="asp_apt_mat =".$variable['aptitudMat'].", ";
                $cadena_sql.="asp_con_mat =".$variable['conMat'].", ";
                $cadena_sql.="asp_idioma =".$variable['idioma'].", ";
                $cadena_sql.="asp_geo =".$variable['geografia'].", ";
                $cadena_sql.="asp_his =".$variable['historia'].", ";
                $cadena_sql.="asp_fil =".$variable['filosofia'].", ";
                $cadena_sql.="asp_interdis =".$variable['interdiciplinaria'].", ";
                $cadena_sql.="asp_cod_inter =".$variable['codInter'].", "; 
                $cadena_sql.="asp_electiva =".$variable['electiva'].", ";
                $cadena_sql.="asp_ptos_hom =".$variable['puntosHom']." ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="asp_id=".$variable['asp_id']." ";
                break;
            
            case "actualizaAcaspResultados":
                $cadena_sql="UPDATE ";
                $cadena_sql.="admisiones.admisiones_acasp ";
                $cadena_sql.="SET ";
                $cadena_sql.="asp_ptos_cal=".$variable['pts_cal'].", ";
                $cadena_sql.="asp_ptos_hom =".$variable['pts_hom'].", ";
                $cadena_sql.="asp_ptos =".$variable['ptos']." ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="asp_id = ".$variable['asp_id']." ";
                break;
            
            case "actualizaAcaspAdmitidos":
                $cadena_sql="UPDATE ";
                $cadena_sql.="admisiones.admisiones_acasp ";
                $cadena_sql.="SET ";
                $cadena_sql.="asp_admitido='".$variable['admitido']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="rba_id= (";
                $cadena_sql.="SELECT rba_id ";
                $cadena_sql.="FROM  admisiones.admisiones_acrecbanasp ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="rba_asp_cred= ".$variable['credencial']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id= ".$variable['id_periodo']."";
                $cadena_sql.=") ";
                break;
            
            case "actualizaAcaspAdmitidosRangos":
                $cadena_sql="UPDATE ";
                $cadena_sql.="admisiones.admisiones_acasp ";
                $cadena_sql.="SET ";
                $cadena_sql.="asp_admitido='".$variable['admitido']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="rba_id IN (";
                $cadena_sql.="SELECT acr.rba_id ";
                $cadena_sql.="FROM admisiones.admisiones_acrecbanasp acr ";
                $cadena_sql.="inner join admisiones.admisiones_acasp asp ";
                $cadena_sql.="on asp.rba_id=acr.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="asp_tip_icfes='".$variable['tipoIcfes']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="acr.aca_id= ".$variable['id_periodo']." ";
                $cadena_sql.="AND asp.asp_cra_cod=".$variable['carrera']."   ";
                $cadena_sql.="AND asp.asp_ptos_cal>=".$variable['rangoInferior']." "; 
                $cadena_sql.="AND asp.asp_ptos_cal<=".$variable['rangoSuperior']." ";
                $cadena_sql.=") ";
                break;
            
            case "consultaDocumentacion":
                $cadena_sql="SELECT ";
                $cadena_sql.="doc_id, ";
                $cadena_sql.="doc_nombre, ";
                $cadena_sql.="doc_nombre_corto, ";
                $cadena_sql.="doc_prefijo, ";
                $cadena_sql.="doc_carreras, ";
                $cadena_sql.="doc_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_documentos ";
                if(isset($variable['prefijo']))
                {
                    $cadena_sql.="WHERE ";
                    $cadena_sql.="doc_prefijo='".$variable['prefijo']."' ";
                    $cadena_sql.="AND ";
                    $cadena_sql.="doc_nombre='".$variable['nombreDocumento']."' ";
                }    
                $cadena_sql.="ORDER BY doc_id ASC ";
                break;
                
            case "insertaDocumentacion":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_documentos ";
                $cadena_sql.="( ";
                $cadena_sql.="doc_nombre, ";
                $cadena_sql.="doc_nombre_corto, ";
                $cadena_sql.="doc_prefijo, ";
                $cadena_sql.="doc_carreras, ";
                $cadena_sql.="doc_estado ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="'".$variable['nombreDocumento']."', ";
                $cadena_sql.="'".$variable['nombreCorto']."', ";
                $cadena_sql.="'".$variable['prefijo']."', ";
                $cadena_sql.="'".$variable['carreras']."', ";
                $cadena_sql.="'".$variable['estado']."' ";
                $cadena_sql.=")";
                break;
            
            case "actualizaDocumentacion":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="admisiones.admisiones_documentos ";
                $cadena_sql.="SET ";
                $cadena_sql.="doc_nombre = '".$variable['nombreDocumento']."', ";
                $cadena_sql.="doc_nombre_corto = '".$variable['nombreCorto']."', ";
                $cadena_sql.="doc_prefijo = '".$variable['prefijo']."', ";
                $cadena_sql.="doc_carreras = '".$variable['carreras']."', ";
                $cadena_sql.="doc_estado = '".$variable['estado']."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="doc_id =".$variable['doc_id']." "; 
                break;
            
            case "consultarCarrera":
                $cadena_sql="SELECT ";
                $cadena_sql.="cra_cod, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="cra_abrev, ";
                $cadena_sql.="cra_se_ofrece ";
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.accra ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="cra_estado='A'";
                $cadena_sql.="AND ";
                $cadena_sql.="cra_cod= ".$variable['carrera']."";
                break;
            
            case "consultarPreguntas":
                $cadena_sql="SELECT ";
                $cadena_sql.="preg_id, ";
                $cadena_sql.="preg_nombre, ";
                $cadena_sql.="preg_estado, ";
                $cadena_sql.="a.tip_preg_id, ";
                $cadena_sql.="a.des_id, ";
                $cadena_sql.="b.tip_preg_nombre, ";
                $cadena_sql.="c. des_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_preguntas a, ";
                $cadena_sql.="admisiones.admisiones_tipo_pregunta b, ";
                $cadena_sql.="admisiones.admisiones_deseventos c ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.tip_preg_id=b.tip_preg_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.des_id=c.des_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="preg_estado='A' ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.des_id=".$variable['evento']." ";
                $cadena_sql.="ORDER BY preg_id ASC";
                break;
            
            case "localidad":
                $cadena_sql = "SELECT ";
                if(isset($variable['Idacasp'])){
                    $cadena_sql.="(loc_id ||'-'||".$variable['Idacasp']."||'-'||".$variable['id'].") as loc_id, ";
                }else{
                    $cadena_sql.="loc_id, ";
                }
                $cadena_sql.="loc_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_localidad ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="loc_estado='A'";
                break;
            
             case "estrato":
                $cadena_sql = "SELECT ";
                if(isset($variable['Idacasp'])){
                    $cadena_sql.="(estrato_id ||'-'||".$variable['Idacasp']."||'-'||".$variable['id'].") as estrato_id, ";
                }else{
                   $cadena_sql.="estrato_id, "; 
                } 
                $cadena_sql.="estrato_nombre, ";
                $cadena_sql.="estrato_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_estrato ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="estrato_estado='A'";
                break;
            
            case "discapacidad":
                $cadena_sql="SELECT ";
                $cadena_sql.="dis_id, ";
                $cadena_sql.="dis_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_discapacidad ";
                $cadena_sql.="ORDER BY dis_id ASC ";
                break;
            
            case "buscarSesion":
                $cadena_sql="SELECT ";
                $cadena_sql.="valor, sesionid, variable, expiracion ";
                $cadena_sql.="FROM sara_valor_sesion ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="sesionid ='".$variable['sesionId']."' ";
                $cadena_sql.="AND variable='rba_id' ";
                break;
            
            case "buscarDocumentos":
                $cadena_sql="SELECT ";
                $cadena_sql.="doc_id, ";
                $cadena_sql.="doc_nombre, ";
                $cadena_sql.="doc_nombre_corto, ";
                $cadena_sql.="doc_carreras, ";
                $cadena_sql.="doc_prefijo, ";
                $cadena_sql.="doc_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_documentos ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="doc_carreras LIKE '%,".$variable['carrera'].",%' ";
                if(isset($variable['prefijo']))
                {
                    $cadena_sql.="AND ";
                    $cadena_sql.="doc_prefijo='".$variable['prefijo']."' ";
                }
                $cadena_sql.="AND ";
                $cadena_sql.="doc_estado='A' ";
                $cadena_sql.="ORDER BY doc_id ASC ";
                break;
                
             case "consultarEncabezados":
                $cadena_sql="SELECT ";
                $cadena_sql.="enc_id, ";
                $cadena_sql.="enc_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_encabezados ";
                $cadena_sql.="ORDER BY enc_id ASC ";
                break; 
            
            case "carrerasOfrecidas":
                $cadena_sql="SELECT ";
                $cadena_sql.="cra_cod, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="cra_abrev, ";
                $cadena_sql.="cra_se_ofrece, ";
                $cadena_sql.="cra_dep_cod, ";
                $cadena_sql.="cra_ind_ciclo ";
                $cadena_sql.="FROM ";
                $cadena_sql.="accra ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="cra_estado='A' ";
                $cadena_sql.="AND ";
                $cadena_sql.="cra_se_ofrece='S' ";
                if(isset($variable['carrera']))
                {
                	$cadena_sql.="AND ";
                	$cadena_sql.="cra_cod= ".$variable['carrera']." ";
                	
                }
                $cadena_sql.="ORDER BY cra_nombre";
                break;
            
            case "buscarAcaspOracle":
                $cadena_sql="SELECT ";
                $cadena_sql.="asp_ape_ano, ";
                $cadena_sql.="asp_ape_per, ";
                $cadena_sql.="asp_cred, ";
                $cadena_sql.="asp_apellido, ";
                $cadena_sql.="asp_nombre, ";
                $cadena_sql.="asp_nro_iden, ";
                $cadena_sql.="asp_cra_cod, ";
                $cadena_sql.="asp_snp ";
                $cadena_sql.="FROM ";
                $cadena_sql.="acasp ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="asp_ape_ano = ".$variable['anio']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="asp_ape_per=".$variable['periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="asp_estado='A' ";
                break;
            
            case "actualizaAcaspId":
                $cadena_sql="UPDATE ";
                $cadena_sql.="admisiones.admisiones_acasp ";
                $cadena_sql.="SET ";
                if(isset($variable['localidadRes'])){
                    $cadena_sql.="asp_localidad=".$variable['localidadRes']." ";
                }elseif(isset($variable['localidadCol'])){
                    $cadena_sql.="asp_localidad_colegio=".$variable['localidadCol']." ";
                }elseif(isset($variable['estrato'])){
                    $cadena_sql.="asp_estrato=".$variable['estrato']." ";
                }else{
                    $cadena_sql.="ti_id=".$variable['tipIns']." ";
                }
                $cadena_sql.="WHERE ";
                $cadena_sql.="asp_id= ".$variable['idacasp']." ";
                break;
                
            case "facultades":
                $cadena_sql= "SELECT unique(cra_dep_cod), dep_nombre ";
                $cadena_sql.="FROM mntac.accra, gedep ";
                $cadena_sql.="WHERE dep_cod = cra_dep_cod ";
                $cadena_sql.="AND cra_estado = 'A' ";
                $cadena_sql.="AND dep_estado = 'A' ";
                $cadena_sql.="AND cra_dep_cod NOT IN(0,500) ";
                if(isset($variable['facultad'])){
                    $cadena_sql.="AND dep_cod = ".$variable['facultad']." ";
                }
                $cadena_sql.="ORDER BY 1 ";
                break;
            
            case "cuentaInscritos":
                $cadena_sql= "SELECT aspw_cra_cod::text as cod_cra, count (aspw_id) as total, 'aspirantes' as tipo_inscripcion  ";
                $cadena_sql.="FROM admisiones.admisiones_acaspw a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";	
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.rba_id=b.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="GROUP BY aspw_cra_cod, tipo_inscripcion ";
                $cadena_sql.="UNION ";
                $cadena_sql.="SELECT atr_cra_cod::text as cod_cra, count(atr_id) as total, 'externa' as tipo_inscripcion  ";
                $cadena_sql.="FROM admisiones.admisiones_transferencia a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.rba_id=b.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="ti_id=4 ";
                $cadena_sql.="GROUP BY atr_cra_cod, tipo_inscripcion ";
                $cadena_sql.="UNION ";
                $cadena_sql.="SELECT are_cra_transferencia::text as cod_cra, count(are_id) as total, 'interna' as tipo_inscripcion "; 
                $cadena_sql.="FROM admisiones.admisiones_reingreso a, admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE a.rba_id=b.rba_id ";
                $cadena_sql.="AND aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ti_id=2 ";
                $cadena_sql.="GROUP BY are_cra_transferencia, tipo_inscripcion ";
                $cadena_sql.="UNION ";
                $cadena_sql.="SELECT substring(are_est_cod::text from 6 for 3) as cod_cra, count(are_id) as total, 'reingreso' as tipo_inscripcion "; 
                $cadena_sql.="FROM admisiones.admisiones_reingreso a, admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE a.rba_id=b.rba_id ";
                $cadena_sql.="AND aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ti_id = 3 ";
                $cadena_sql.="GROUP BY cod_cra,tipo_inscripcion ";
                break;
            
            case "consultaCarrerasxFacultad":
                $cadena_sql= "SELECT cra_cod, cra_nombre ";
                $cadena_sql.="FROM mntac.accra, mntac.gedep ";
                $cadena_sql.="WHERE dep_cod = cra_dep_cod ";
                $cadena_sql.="AND dep_cod=".$variable['facultad']." ";
                $cadena_sql.="AND cra_estado = 'A' ";
                $cadena_sql.="AND dep_estado = 'A' ";
                $cadena_sql.="AND cra_estado= 'A' ";
                $cadena_sql.="AND cra_dep_cod NOT IN(0,500) ";
                $cadena_sql.="ORDER BY 1 ";
                break;
            
            case "consultarCodCarrera":
                $cadena_sql="SELECT ";
                $cadena_sql.="cra_cod, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="cra_abrev, ";
                $cadena_sql.="cra_se_ofrece ";
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.accra ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="cra_cod=".$variable['codCra']."";
                break;
            
            case "cuentaAspirantes":
                $cadena_sql= "SELECT count(aspw_id) as totalaspirantes ";
                $cadena_sql.="FROM admisiones.admisiones_acaspw a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";	
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.rba_id=b.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                break;
            
            case "cuaentaTransExt":
                $cadena_sql="SELECT count(atr_id) as totaltransferenciaext ";
                $cadena_sql.="FROM admisiones.admisiones_transferencia a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.rba_id=b.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="ti_id=4 ";
                break;
            
            case "cuaentaReingTransInt":
                $cadena_sql="SELECT count(are_id) as totalreingreso "; 
                $cadena_sql.="FROM admisiones.admisiones_reingreso a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE a.rba_id=b.rba_id ";
                $cadena_sql.="AND aca_id=".$variable['id_periodo']." ";
                break;
            
            case "actualizaTipInscripcionId":
                $cadena_sql="UPDATE ";
                $cadena_sql.="admisiones.admisiones_acasp ";
                $cadena_sql.="SET ";
                $cadena_sql.="ti_id=".$variable['tipIns']." ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="asp_id= ".$variable['idacasp']." ";
                
                break;
           
        }
        //echo $cadena_sql."<br><br>";
        return $cadena_sql;
    }

}

?>
