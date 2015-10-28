<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlinscripcionAdmisiones extends sql {

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
                $cadena_sql.="ORDER BY des_id ";
                break;
            
            case "seleccionarInscripcion":
                $cadena_sql = "SELECT ";
                $cadena_sql.="des_id, ";
                $cadena_sql.="des_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_deseventos ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="des_estado='A' ";
                $cadena_sql.="AND ";
                $cadena_sql.="des_id != 6 ";
                $cadena_sql.="ORDER BY des_id ";
                break;
            
            case "fechaSistema":
                $cadena_sql="SELECT to_char(current_date, 'mm/dd/yyyy')";
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
                $cadena_sql.="ORDER BY a.des_id ASC";
                break;
            
            case "consultarEventosInscripcion":
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
                $cadena_sql.="AND ";
                $cadena_sql.="a.des_id=".$variable['evento']." ";
                $cadena_sql.="ORDER BY a.des_id ASC";
                break;
            
            case "estadoEventos":
                $cadena_sql = "SELECT ";
                $cadena_sql.="even_id, ";
                $cadena_sql.="a.des_id, ";
                $cadena_sql.="des_nombre, ";
                $cadena_sql.="TO_CHAR(even_fecha_ini, 'mm/dd/yyyy'), ";
                $cadena_sql.="TO_CHAR(even_fecha_fin, 'mm/dd/yyyy'), ";
                $cadena_sql.="aca_id, ";
                $cadena_sql.="even_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_eventos a ";
                $cadena_sql.="INNER JOIN admisiones.admisiones_deseventos b ON a.des_id=b.des_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id= ".$variable['id_periodo']." ";
                $cadena_sql.="AND a.des_id=".$variable['evento']."";
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
                $cadena_sql.="WHERE ";
                $cadena_sql.="ins_tipo='".$variable['tipoInstructivo']."' ";
                $cadena_sql.="ORDER BY ins_orden ASC ";
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
                $cadena_sql.="ins_tipo='".$variable['tipoInstructivo']."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="ins_nombre='".$variable['seccion']."' ";
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
                $cadena_sql.="WHERE ";
                $cadena_sql.="colilla_carreras='".$variable['carrera']."' ";
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
                $cadena_sql.="colilla_nombre='".trim($variable['nombre'])."' ";
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
                $cadena_sql.="'".$variable['nombre']."', ";
                $cadena_sql.="'".$variable['contenido']."', ";
                $cadena_sql.="'".$variable['carreras']."', ";
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
            
            case "consultarInscripcionAcasp":
                $cadena_sql="SELECT ";
                $cadena_sql.="asp_id, ";
                $cadena_sql.="ti_id, ";
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
                $cadena_sql.="rba_asp_cred ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_acasp a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.rba_id = b.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.rba_id=".$variable['rba_id']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="asp_estado='A' ";
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
                $cadena_sql.="a.rba_id=".$variable['rba_id']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.rba_id = b.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="aspw_estado='A' ";
                break;
            
            case "consultarTodosAcaspw":
                $cadena_sql="SELECT ";
                $cadena_sql.="aspw_id, ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="med_id, ";
                $cadena_sql.="a.rba_id as rba_id, ";
                $cadena_sql.="aspw_cra_cod, ";
                $cadena_sql.="aspw_nro_iden_act, ";
                $cadena_sql.="aspw_nro_tip_act, ";
                $cadena_sql.="aspw_nro_iden_icfes, ";
                $cadena_sql.="aspw_nro_tip_icfes, ";
                $cadena_sql.="aspw_snp, ";
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
                $cadena_sql.="aspw_estado='A' ";
                break;
            
            case "insertaInscripcionAcaspw":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_acaspw ";
                $cadena_sql.="( ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="med_id, ";
                $cadena_sql.="rba_id, ";
                $cadena_sql.="aspw_veces, ";
                $cadena_sql.="aspw_cra_cod, ";
                $cadena_sql.="aspw_nacionalidad, ";
                $cadena_sql.="aspw_dep_cod_nac, ";
                $cadena_sql.="aspw_mun_cod_nac, ";
                $cadena_sql.="aspw_fec_nac, ";
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
                $cadena_sql.="des_id ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="".$variable['tipoInscripcion'].", ";
                $cadena_sql.="".$variable['medio'].", ";
                $cadena_sql.="".$variable['rba_id'].", ";
                $cadena_sql.="".$variable['prestentaPor'].", ";
                $cadena_sql.="".$variable['carreras'].", ";
                $cadena_sql.="'".$variable['pais']."', ";
                $cadena_sql.="".$variable['departamento'].", ";
                $cadena_sql.="".$variable['municipio'].", ";
                $cadena_sql.="to_date('" . $variable['fechaNac'] . "', 'dd/mm/yyyy'), ";
                $cadena_sql.="'".$variable['sexo']."', ";
                $cadena_sql.="".$variable['estadoCivil'].", ";
                $cadena_sql.="'".$variable['tipoSangre']."', ";
                $cadena_sql.="'".$variable['rh']."', ";
                $cadena_sql.="'".$variable['email']."', ";
                $cadena_sql.="".$variable['documentoActual'].", ";
                $cadena_sql.="".$variable['tipDocActual'].", ";
                $cadena_sql.="".$variable['documentoIcfes'].", ";
                $cadena_sql.="".$variable['tipDocIcfes'].", ";
                $cadena_sql.="'', ";//aspw_def_sit_militar
                $cadena_sql.="'', ";//aspw_ser_militar
                $cadena_sql.="null, ";//aspw_dis_militar
                $cadena_sql.="'".$variable['registroIcfes']."', ";
                $cadena_sql.="'', "; //aspw_discapacidad
                $cadena_sql.="null, ";//aspw_dep_cod_pro
                $cadena_sql.="null, ";//aspw_mun_cod_pro
                $cadena_sql.="'', ";//aspw_rural  
                $cadena_sql.="'', ";//aspw_cabecera_mun
                $cadena_sql.="".$variable['estratoResidencia'].", ";
                $cadena_sql.="'".$variable['direccionResidencia']."', ";
                $cadena_sql.="'".$variable['localidadResidencia']."', ";
                $cadena_sql.="'".$variable['telefono']."', ";
                $cadena_sql.="null, ";//aspw_vive_con  
                $cadena_sql.="'', ";//aspw_trabaja
                $cadena_sql.="'".$variable['tipoColegio']."', ";
                $cadena_sql.="'".$variable['localidadColegio']."', ";
                $cadena_sql.="null, ";//aspw_matricula_colegio
                $cadena_sql.="'', ";//aspw_hermanos
                $cadena_sql.="null, ";//aspw_renta_liquida
                $cadena_sql.="null, ";//aspw_patrimonio_liquido
                $cadena_sql.="null, ";//aspw_ingresos_anuales
                $cadena_sql.="'', ";//aspw_vive_padre
                $cadena_sql.="'', ";//aspw_vive_madre
                $cadena_sql.="'', ";//aspw_direccion_padres
                $cadena_sql.="'', ";//aspw_telefono_padres
                $cadena_sql.="'".$variable['discapacidad']."', ";
                $cadena_sql.="'".$variable['valido']."', ";
                $cadena_sql.="".$variable['numSemestres'].", ";
                $cadena_sql.="".$variable['estratoCosteara'].", ";
                $cadena_sql.="'".$variable['observaciones']."', ";
                $cadena_sql.="'A', ";
                $cadena_sql.="".$variable['evento']." ";
                $cadena_sql.=")";
                break;
            
            case "consultarInscripcionReingreso":
                $cadena_sql="SELECT ";
                $cadena_sql.="are_id, ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="a.rba_id as rba_id, ";
                $cadena_sql.="are_nro_iden, ";
                $cadena_sql.="are_est_cod, ";
                $cadena_sql.="are_cancelo_sem, ";
                $cadena_sql.="are_motivo_retiro, ";
                $cadena_sql.="are_telefono, ";
                $cadena_sql.="are_email, ";
                $cadena_sql.="are_cra_cursando, ";
                $cadena_sql.="are_cra_transferencia, ";
                $cadena_sql.="are_admitido, ";
                $cadena_sql.="are_estado, ";
                $cadena_sql.="rba_asp_cred ";
                $cadena_sql.="FROM admisiones.admisiones_reingreso a, ";
                $cadena_sql.="admisiones.admisiones_acrecbanasp b ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.rba_id=".$variable['rba_id']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.rba_id = b.rba_id ";
                 $cadena_sql.="AND ";
                $cadena_sql.="are_estado='A' ";
                break;
            
            case "insertaInscripcionReingreso":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_reingreso ";
                $cadena_sql.="( ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="rba_id, ";
                $cadena_sql.="are_nro_iden, ";
                $cadena_sql.="are_est_cod, ";
                $cadena_sql.="are_cancelo_sem, ";
                $cadena_sql.="are_motivo_retiro, ";
                $cadena_sql.="are_telefono, ";
                $cadena_sql.="are_email, ";
                $cadena_sql.="are_cra_cursando, ";
                $cadena_sql.="are_cra_transferencia, ";
                $cadena_sql.="are_estado ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="".$variable['tipoInscripcion'].", ";
                $cadena_sql.="".$variable['rba_id'].", ";
                $cadena_sql.="".$variable['documento'].", ";
                $cadena_sql.="".$variable['codigoEstudiante'].", ";
                $cadena_sql.="'".$variable['cancelo']."', ";
                $cadena_sql.="'".$variable['motivo']."', ";
                $cadena_sql.="'".$variable['telefono']."', ";
                $cadena_sql.="'".$variable['email']."', ";
                $cadena_sql.="".$variable['carreraCursando'].", ";
                $cadena_sql.="".$variable['carreraInscribe'].", ";
                $cadena_sql.="'A' ";
                $cadena_sql.=")";
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
                $cadena_sql.="a.rba_id=".$variable['rba_id']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.rba_id = b.rba_id ";
                $cadena_sql.="AND ";
                $cadena_sql.="atr_estado='A' ";
                break;
            
            case "insertaInscripcionTransferencia":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="admisiones.admisiones_transferencia ";
                 $cadena_sql.="( ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="rba_id, ";
                $cadena_sql.="atr_cra_cod, ";
                $cadena_sql.="atr_universidad_proviene, "; 
                $cadena_sql.="atr_cra_proviene, ";
                $cadena_sql.="atr_semestre, ";
                $cadena_sql.="atr_motivo, ";
                $cadena_sql.="atr_nacionalidad, ";
                $cadena_sql.="atr_dep_cod_nac, ";
                $cadena_sql.="atr_mun_cod_nac, ";
                $cadena_sql.="atr_fec_nac, ";
                $cadena_sql.="atr_sexo, ";
                $cadena_sql.="atr_estado_civil, ";
                $cadena_sql.="atr_tipo_sangre, ";
                $cadena_sql.="atr_rh, ";
                $cadena_sql.="atr_email, ";
                $cadena_sql.="atr_nro_iden_act, ";
                $cadena_sql.="atr_nro_tip_act, ";
                $cadena_sql.="atr_nro_iden_icfes, ";
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
                $cadena_sql.="atr_estado ";
                $cadena_sql.=")";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="".$variable['tipoInscripcion'].", ";
                $cadena_sql.="".$variable['rba_id'].", ";
                $cadena_sql.="".$variable['carreras'].", ";
                $cadena_sql.="'".$variable['universidadProviene']."', ";
                $cadena_sql.="'".$variable['carreraVeniaCursando']."', ";
                $cadena_sql.="".$variable['semestreCursado'].", ";
                $cadena_sql.="'".$variable['motivoTransferencia']."', ";
                $cadena_sql.="'".$variable['pais']."', ";
                $cadena_sql.="".$variable['departamento'].", ";
                $cadena_sql.="".$variable['municipio'].", ";
                $cadena_sql.="to_date('" . $variable['fechaNac'] . "', 'dd/mm/yyyy'), ";
                $cadena_sql.="'".$variable['sexo']."', ";
                $cadena_sql.="".$variable['estadoCivil'].", ";
                $cadena_sql.="'".$variable['tipoSangre']."', ";
                $cadena_sql.="'".$variable['rh']."', ";
                $cadena_sql.="'".$variable['email']."', ";
                $cadena_sql.="".$variable['documentoActual'].", ";
                $cadena_sql.="".$variable['tipDocActual'].", ";
                $cadena_sql.="".$variable['documentoIcfes'].", ";
                $cadena_sql.="".$variable['tipDocIcfes'].", ";
                $cadena_sql.="'', ";//aspw_def_sit_militar
                $cadena_sql.="'', ";//aspw_ser_militar
                $cadena_sql.="null, ";//aspw_dis_militar
                $cadena_sql.="'".$variable['registroIcfes']."', ";
                $cadena_sql.="'', "; //aspw_discapacidad
                $cadena_sql.="null, ";//aspw_dep_cod_pro
                $cadena_sql.="null, ";//aspw_mun_cod_pro
                $cadena_sql.="'', ";//aspw_rural  
                $cadena_sql.="'', ";//aspw_cabecera_mun
                $cadena_sql.="".$variable['estratoResidencia'].", ";
                $cadena_sql.="'".$variable['direccionResidencia']."', ";
                $cadena_sql.="'".$variable['localidadResidencia']."', ";
                $cadena_sql.="'".$variable['telefono']."', ";
                $cadena_sql.="null, ";//aspw_vive_con  
                $cadena_sql.="'', ";//aspw_trabaja
                $cadena_sql.="null, ";
                $cadena_sql.="'".$variable['localidadColegio']."', ";
                $cadena_sql.="null, ";//aspw_matricula_colegio
                $cadena_sql.="'', ";//aspw_hermanos
                $cadena_sql.="null, ";//aspw_renta_liquida
                $cadena_sql.="null, ";//aspw_patrimonio_liquido
                $cadena_sql.="null, ";//aspw_ingresos_anuales
                $cadena_sql.="'', ";//aspw_vive_padre
                $cadena_sql.="'', ";//aspw_vive_madre
                $cadena_sql.="'', ";//aspw_direccion_padres
                $cadena_sql.="'', ";//aspw_telefono_padres
                $cadena_sql.="'".$variable['observaciones']."', ";
                $cadena_sql.="'A' ";
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
                $cadena_sql.="mntac.accra ";
                $cadena_sql.="SET ";
                $cadena_sql.="cra_se_ofrece='".$variable['seOfrece']."', ";
                $cadena_sql.="cra_cod=".$variable['codCra']."";
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
            
            case "carrerasOfrecidas":
                $cadena_sql="SELECT ";
                $cadena_sql.="cra_cod, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="cra_abrev, ";
                $cadena_sql.="cra_se_ofrece ";
                $cadena_sql.="FROM ";
                $cadena_sql.="accra ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="cra_estado='A' ";
                $cadena_sql.="AND ";
                $cadena_sql.="cra_se_ofrece='S'";
                if(isset($variable['carrerasNoOfrecidas']))
                {    
                    $cadena_sql.="AND ";
                    $cadena_sql.="cra_cod NOT IN ".$variable['carrerasNoOfrecidas']." ";
                }
                $cadena_sql.="ORDER BY cra_cod ASC ";
                break;
            
            case "consultarEncabezados":
                $cadena_sql="SELECT ";
                $cadena_sql.="enc_id, ";
                $cadena_sql.="enc_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_encabezados ";
                $cadena_sql.="ORDER BY enc_id ASC ";
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
            
            case "buscarMedios":
                $cadena_sql = "SELECT ";
                $cadena_sql.="med_id, ";
                $cadena_sql.="med_nombre, ";
                $cadena_sql.="med_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_medio ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="med_id=".$variable['medio']." ";
                break;
            
            case "buscartipInscripcion":
                $cadena_sql="SELECT ";
                $cadena_sql.="ti_id, ";
                $cadena_sql.="(ti_cod||' - '|| ti_nombre) as nombre, ";
                $cadena_sql.="ti_cod, ";
                $cadena_sql.="ti_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_tip_ins ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ti_cod NOT IN (20,25,26) ";
                $cadena_sql.="AND ";
                $cadena_sql.="ti_estado='A' ";
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
            
            case "gedepartamento":
                $cadena_sql="SELECT ";
                $cadena_sql.="dep_cod, ";
                $cadena_sql.="dep_nombre ";
                $cadena_sql.="FROM  ";
                $cadena_sql.="mntge.gedepartamento ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="dep_estado = 'A' ";
                $cadena_sql.="ORDER BY 2";
            break;
            
            case "gemunicipio":
                $cadena_sql="SELECT ";
                $cadena_sql.="mun_cod, ";
                $cadena_sql.="mun_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="gemunicipio ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="mun_dep_cod = ".$variable['dep_cod']." ";
                $cadena_sql.="AND mun_estado = 'A' ";
                $cadena_sql.="ORDER BY mun_nombre ";
                break;
            
            case "municipio":
                $cadena_sql="SELECT ";
                $cadena_sql.="mun_cod, ";
                $cadena_sql.="mun_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="gemunicipio ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="mun_estado = 'A' ";
                $cadena_sql.="ORDER BY mun_nombre ";
                break;
            
            case "estadoCivil":
                $cadena_sql="SELECT ";
                $cadena_sql.="id_est_civil, ";
                $cadena_sql.="nombre_est_civil ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_estado_civil ";
                $cadena_sql.="ORDER BY id_est_civil ASC ";
                break;
            
            case "localidad":
                $cadena_sql = "SELECT ";
                $cadena_sql.="loc_id, ";
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
                $cadena_sql.="estrato_id, ";
                $cadena_sql.="estrato_nombre, ";
                $cadena_sql.="estrato_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_estrato ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="aca_id=".$variable['id_periodo']." ";
                $cadena_sql.="AND ";
                $cadena_sql.="estrato_estado='A'";
                break;
            
            case "tipDocumento":
                $cadena_sql="SELECT ";
                $cadena_sql.="tip_documento_id, ";
                $cadena_sql.="tip_documento_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="admisiones.admisiones_tipo_documento ";
                $cadena_sql.="ORDER BY tip_documento_id ASC ";
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
                
        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }

}

?>
