
<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");


#Realiza la preparacion del formulario para la validacion de javascript
?>

<?

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_adminHorarioEstudiantes extends funcionGeneral {

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");


        $this->cripto = new encriptar();
        $this->tema = $tema;
        $this->sql = new sql_adminHorarioEstudiantes();
        $this->log_us = new log();
        $this->formulario = "adminHorarioEstudiantes";


        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");

        //Conexion sga
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

        //Conexion Oracle
        $this->accesoOracle = $this->conectarDB($configuracion, "estudiantecred");


        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        $this->usuarioSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

        //echo $this->usuarioSesion[0][0];
    }

    #muestra los datos del estudiante y el horario, utiliza los metodos: mostrarDatosEstudiante, mostrarHorarioEstudiante

    function mostrarHorarioEstudiante($configuracion) {

        $codigoEstudiante = $this->usuario;

        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "consultaEstudiante", $codigoEstudiante);
        $registroEstudiante = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");

        if (isset($registroEstudiante)) {
            $this->datosEstudiante($configuracion, $registroEstudiante);

            //busca los grupos y el horario inscrito por el estudinate
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "consultaGrupo", $codigoEstudiante);
            $registroGrupo = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
            $this->HorarioEstudiante($configuracion, $registroGrupo);
            $var = $this->HorarioEstudiantePrueba($configuracion, $registroGrupo);
            echo "88888888888888".$var;
        } else {
            echo "El código de estudiante: <strong>" . $codigoEstudiante . "</strong> no está inscrito en Créditos.";
        }
    }

    #Funcion que muestra la informacion del estudiante

    function datosEstudiante($configuracion, $registro) {
        ?>
        <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
            <tr class="texto_subtitulo">
                <td colspan="2">
                    <? echo "Nombre: <strong>" . $registro[0][1] . "</strong><br>"; ?>
                    <? echo "C&oacute;digo: <strong>" . $registro[0][0] . "</strong><br>"; ?>
                    Proyecto Curricular:
                    <? echo "<strong>" . $registro[0][3] . "</strong><br>"; ?>
                    Plan de Estudios:
                    <? echo "<strong>" . $registro[0][2] . " - " . $registro[0][4] . "</strong><br>"; ?>
                    Acuerdo:
                    <? echo "<strong>" . substr($registro[0][5], -3) . " de " . substr($registro[0][5], 0, 4) . "</strong>"; ?>

                    <hr>
                </td>
            </tr>

        </table>


        <?
    }

    function HorarioEstudiante($configuracion, $resultado_grupos) {
        ?>
        <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
            <tbody>
                <tr>
                    <td>
                        <? if ($resultado_grupos != NULL) { ?>
                            <table width="100%" border="0" align="center">
                                <thead class='cuadro_plano centrar'>
                                <th><center><? echo "Horario de Clases"; ?></center></th>
                                </thead>


                                <tr>
                                    <td>
                                        <table class='contenidotabla'>
                                            <thead class='cuadro_color'>
                                            <td class='cuadro_plano centrar' width="6%">Cod.</td>
                                            <td class='cuadro_plano centrar' width="30%">Nombre Espacio<br>Acad&eacute;mico </td>
                                            <td class='cuadro_plano centrar' width="5%">Grupo </td>
                                            <td class='cuadro_plano centrar' width="6%">Cr&eacute;ditos </td>
                                            <td class='cuadro_plano centrar' width="11%">Clasificaci&oacute;n </td>
                                            <td class='cuadro_plano centrar' width="6%">Lun </td>
                                            <td class='cuadro_plano centrar' width="6%">Mar </td>
                                            <td class='cuadro_plano centrar' width="6%">Mie </td>
                                            <td class='cuadro_plano centrar' width="6%">Jue </td>
                                            <td class='cuadro_plano centrar' width="6%">Vie </td>
                                            <td class='cuadro_plano centrar' width="6%">S&aacute;b </td>
                                            <td class='cuadro_plano centrar' width="6%">Dom </td>
                                            </thead>

                                            <?
                                            //recorre cada uno del los grupos
                                            for ($j = 0; $j < count($resultado_grupos); $j++) {

                                                //
                                                $variables['CODIGO'] = $resultado_grupos[$j]['CODIGO'];  //Codigo espacio
                                                $variables['GRUPO'] = $resultado_grupos[$j]['GRUPO'];  //grupo
                                                $variables['NOMBRE'] = $resultado_grupos[$j]['NOMBRE'];  //nombre espacio
                                                $variables['CREDITOS'] = $resultado_grupos[$j]['CREDITOS'];  //creditos
                                                $variables['CLASIFICACION'] = $resultado_grupos[$j]['CLASIFICACION'];  //clasificacion
                                                //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                                                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "horario_grupos", $variables);
                                                $resultado_horarios = $this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql, "busqueda");
                                                ?>
                                                <tr>
                                                    <td class='cuadro_plano centrar'><? echo $resultado_grupos[$j]['CODIGO']; ?></td>
                                                    <td class='cuadro_plano'><? echo $resultado_grupos[$j]['NOMBRE']; ?></td>
                                                    <td class='cuadro_plano centrar'><? echo $resultado_grupos[$j]['GRUPO']; ?></td>
                                                    <td class='cuadro_plano centrar'><? echo $resultado_grupos[$j]['CREDITOS']; ?></td>
                                                    <td class='cuadro_plano centrar'><? echo $resultado_grupos[$j]['CLASIFICACION']; ?></td>
                                                    <?
                                                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                                                    for ($i = 1; $i < 8; $i++) {
                                                        ?><td class='cuadro_plano centrar'><?
                                    //Recorre el arreglo del resultado de los horarios
                                    for ($k = 0; $k < count($resultado_horarios); $k++) {

                                        if ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] == (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') && (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') == ($resultado_horarios[$k]['DIA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                                            $l = $k;
                                            while ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] == (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') && (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') == ($resultado_horarios[$k]['DIA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                                                $m = $k;
                                                $m++;
                                                $k++;
                                            }
                                            $dia = "<strong>" . $resultado_horarios[$l]['DIA'] . "-" . ($resultado_horarios[$m]['DIA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$l]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$l]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$l]['ID_SALON'] . " " . $resultado_horarios[$l]['NOM_SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] != (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '')) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['DIA'] . "-" . ($resultado_horarios[$k]['DIA'] + 1) . "</strong><br>" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                            $k++;
                                        } elseif ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] == (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['DIA'] . "-" . ($resultado_horarios[$k]['DIA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['HORA'] != $i) {
                                            
                                        }
                                    }
                                                        ?></td><?
                                    }
                                                    ?>

                                                </tr>
                                            <? }
                                        } else { ?>
                                            <tr>
                                                <td class='cuadro_plano centrar'>
                                                    No se encontraron datos de espacios adicionados
                                                </td>
                                            </tr>
                                        <? }
                                        ?>
                                    </table>
                                </td>

                            </tr>

                        </table>
                    </td>
                </tr>
                <tr>
                    <?
                    $this->mostrar_convenciones_clasificacion($configuracion);
                    ?>                            </tr>

            </tbody>
        </table>

        <?
    }

    function HorarioEstudiantePrueba($configuracion, $resultado_grupos) {

        $var = "<table width='100%' border='0' align='center' cellpadding='5 px' cellspacing='1px' >
			<tbody>
                            <tr>
                                <td>";
                                    if($resultado_grupos!=NULL){
                                    $var .= "<table width='100%' border='0' align='center'>
                                        <thead class='cuadro_plano centrar'>
                                        <th><center><?echo 'Horario de Clases';?></center></th>
                                        </thead>


                                        <tr>
                                            <td>
                                                <table class='contenidotabla'>
                                                    <thead class='cuadro_color'>
                                                        <td class='cuadro_plano centrar' width='6%'>Cod.</td>
                                                        <td class='cuadro_plano centrar' width='30%'>Nombre Espacio<br>Acad&eacute;mico </td>
                                                        <td class='cuadro_plano centrar' width='5%'>Grupo </td>
                                                        <td class='cuadro_plano centrar' width='6%'>Cr&eacute;ditos </td>
                                                        <td class='cuadro_plano centrar' width='11%'>Clasificaci&oacute;n </td>
                                                        <td class='cuadro_plano centrar' width='6%'>Lun </td>
                                                        <td class='cuadro_plano centrar' width='6%'>Mar </td>
                                                        <td class='cuadro_plano centrar' width='6%'>Mie </td>
                                                        <td class='cuadro_plano centrar' width='6%'>Jue </td>
                                                        <td class='cuadro_plano centrar' width='6%'>Vie </td>
                                                        <td class='cuadro_plano centrar' width='6%'>S&aacute;b </td>
                                                        <td class='cuadro_plano centrar' width='6%'>Dom </td>
                                                    </thead>";

        //recorre cada uno del los grupos
        for ($j = 0; $j < count($resultado_grupos); $j++) {

            //
            $variables['CODIGO'] = $resultado_grupos[$j]['CODIGO'];  //Codigo espacio
            $variables['GRUPO'] = $resultado_grupos[$j]['GRUPO'];  //grupo
            $variables['NOMBRE'] = $resultado_grupos[$j]['NOMBRE'];  //nombre espacio
            $variables['CREDITOS'] = $resultado_grupos[$j]['CREDITOS'];  //creditos
            $variables['CLASIFICACION'] = $resultado_grupos[$j]['CLASIFICACION'];  //clasificacion
            //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
            $this->cadena_sql = $this->sql->cadena_sql($configuracion, "horario_grupos", $variables);
            $resultado_horarios = $this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql, "busqueda");
            $var .= "<tr>
                                                    <td class='cuadro_plano centrar'>.$resultado_grupos[$j]['CODIGO'].</td>
                                                    <td class='cuadro_plano'>.$resultado_grupos[$j]['NOMBRE'].</td>
                                                    <td class='cuadro_plano centrar'>.$resultado_grupos[$j]['GRUPO'].</td>
                                                    <td class='cuadro_plano centrar'>.$resultado_grupos[$j]['CREDITOS'].</td>
                                                    <td class='cuadro_plano centrar'>.$resultado_grupos[$j]['CLASIFICACION'].</td>";

            //recorre el numero de dias del la semana 1-7 (lunes-domingo)
            for ($i = 1; $i < 8; $i++) {
                $var .= "<td class='cuadro_plano centrar'>";
                //Recorre el arreglo del resultado de los horarios
                for ($k = 0; $k < count($resultado_horarios); $k++) {

                    if ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] == (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') && (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') == ($resultado_horarios[$k]['DIA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                        $l = $k;
                        while ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] == (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') && (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') == ($resultado_horarios[$k]['DIA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                            $m = $k;
                            $m++;
                            $k++;
                        }
                        $dia = "<strong>" . $resultado_horarios[$l]['DIA'] . "-" . ($resultado_horarios[$m]['DIA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$l]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$l]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$l]['ID_SALON'] . " " . $resultado_horarios[$l]['NOM_SALON'];
                        $var .= $dia . "<br>";
                        unset($dia);
                    } elseif ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] != (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '')) {
                        $dia = "<strong>" . $resultado_horarios[$k]['DIA'] . "-" . ($resultado_horarios[$k]['DIA'] + 1) . "</strong><br>" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                        $var.= $dia . "<br>";
                        unset($dia);
                        $k++;
                    } elseif ($resultado_horarios[$k]['HORA'] == $i && $resultado_horarios[$k]['HORA'] == (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                        $dia = "<strong>" . $resultado_horarios[$k]['DIA'] . "-" . ($resultado_horarios[$k]['DIA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                        $var .= $dia . "<br>";
                        unset($dia);
                    } elseif ($resultado_horarios[$k]['HORA'] != $i) {
                        
                    }
                }
                $var .="</td>";
            }

            $var .= "</tr>";
        }
        } else {
            $var .= "<tr>
                <td class='cuadro_plano centrar'>
                    No se encontraron datos de espacios adicionados
                </td>
            </tr>";
        }

        $var .= "
        </table>
        </td>

        </tr>

        </table>
        </td>
        </tr>
        <tr>";
        $this->mostrar_convenciones_clasificacion($configuracion);
        $var .= $this->mostrar_convenciones_clasificacionPrueba($configuracion);
        $var .= "</tr></tbody>
        </table>";
        
        return $var;
    }

    function mostrar_convenciones_clasificacion($configuracion) {
        ?>
        <table align="center" width="70%" >
            <tr>
                <th class="sigma centrar">
                    Abreviatura
                </th>
                <th class="sigma centrar">
                    Nombre
                </th>
            </tr>
            <?
            $resultado_clasificacion = $this->consultarClasificacion($configuracion);

            for ($k = 0; $k < count($resultado_clasificacion); $k++) {
                ?>
                <tr>
                    <td class="sigma centrar">
                        <? echo $resultado_clasificacion[$k][1] ?>
                    </td>
                    <td class="sigma ">
                        <? echo $resultado_clasificacion[$k][2] ?>
                    </td>
                </tr>
                <?
            }
            ?>
        </table>
        <?
    }

    function mostrar_convenciones_clasificacionPrueba($configuracion) {

        $var .= "<table align='center' width='70%' >
            <tr>
                <th class='sigma centrar'>
                    Abreviatura
                </th>
                <th class='sigma centrar'>
                    Nombre
                </th>
            </tr>";

        $resultado_clasificacion = $this->consultarClasificacion($configuracion);

        for ($k = 0; $k < count($resultado_clasificacion); $k++) {
            $var .= " <tr>
                    <td class='sigma centrar'>";
            $var .= $resultado_clasificacion[$k][1];
            $var .= "</td> <td class='sigma'>";
            $var .= $resultado_clasificacion[$k][2];
            $var .= "</td>
                </tr>";
        }

        $var .= "</table>";
        return $var;
    }

//fin funcion mostrar_convenciones_clasificacion

    function consultarClasificacion($configuracion) {
        $cadena_sql = $this->sql->cadena_sql($configuracion, "clasificacion", '');
        $resultado_clasificacion = $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
        return $resultado_clasificacion;
    }

}
?>
