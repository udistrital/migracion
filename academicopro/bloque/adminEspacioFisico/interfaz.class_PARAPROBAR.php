<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
include_once("sql.class.php");
//include_once("espacioFisico.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");

class Interfaz extends funcionGeneral {

//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion) {
//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo

        include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");

        $this->cripto = new encriptar();
        //$this->tema = $tema;
        $this->sql = new SqlAdminEspacioFisico();
        //$this->funcionGeneral = new funcionGeneral();
        $this->html = new html();

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");

        //Conexion sga
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

        //Conexion Oracle
        $this->accesoOracle = $this->conectarDB($configuracion, "coordinador");

        //Datos de sesion
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    }

    /*     * *******************************************************************************************
     * Esta función es la encargada de crear el formulario de registro con los campos necesarios *
     * para la inserción de la sede en la Base de Datos
     * ******************************************************************************************** */

    function desplegarFormularioRegistro($configuracion, $atributos, $espacio) {

        if ($espacio == "1")
            $titulo = "Facultad";
        if ($espacio == "2")
            $titulo = "Sede";
        if ($espacio == "3")
            $titulo = "Edificio";
        if ($espacio == "4")
            $titulo = "Espacio Físico Académico";

        $this->formulario = 'RegistrarEspacio';
        $flag = 0;
        ?>
        <script>
            function validaFloat(numero){
                var er1_EntradaS = /^[0-9]*\,?[0-9]*$/
                if(!er1_EntradaS.test(numero)) {
                    alert("El campo solo permite numeros separados por ','");
                    return false;
                }}
        </script>
        <html>
            <head>

                <!-- Meta Tags -->
                <meta charset="utf-8">

                <!-- CSS -->
                <link href="<? echo $configuracion["host"] . $configuracion["site"]; ?>/bloque/adminEspacioFisico/css/structure.css" rel="stylesheet" type="text/css">
                <link href="<? echo $configuracion["host"] . $configuracion["site"]; ?>/bloque/adminEspacioFisico/css/form.css" rel="stylesheet" type="text/css">

            </head>

            <body id="public">
                <div id="container" class="ltr">
                    <form id="<? echo $this->formulario ?>" name="<? echo $this->formulario ?>" class="wufoo  page" autocomplete="off" enctype="multipart/form-data">

                        <header class="espaciosFisicos" id="header" class="info" style="color: #FFF; "><b>
                                Registrar <? echo $titulo; ?></b>
                            <div></div>
                        </header>

                        <ul>
                            <?
                            $cant_atributos = count($atributos);

                            for ($i = 0; $i < $cant_atributos; $i++) {

                                if ($atributos[$i]['TIPO'] == 'COMBOBOX') {

                                    $datos_combo = $this->datosCombo($configuracion, $atributos[$i], "");
                                    $cant_datos = count($datos_combo);
                                    $seleccion = -1;
                                    ?>
                                    <li id="foli6" class="notranslate leftHalf      ">
                                        <label class="desc" id="title6" for="Field6">
                                            <? echo $atributos[$i]['NOM_FORM'] ?>
                                        </label>
                                        <div id="DIV_<? echo $atributos[$i]["NOM_ID"] ?>">
                                            <?
                                            if ($espacio == 4) {
                                                $configuracion["ajax_function"] = "xajax_" . $atributos[$i + 1]['NOM_ID'];
                                                $configuracion["ajax_control"] = $atributos[$i]['NOM_ID'];
                                            } else if ($atributos[$i]['NOM_ID'] == 'E01') {
                                                $configuracion["ajax_function"] = "xajax_armarCodigoEdificio";
                                                $configuracion["ajax_control"] = $atributos[$i]['NOM_ID'];
                                            } else if ($atributos[$i]['NOM_ID'] == 'S01') {
                                                $configuracion["ajax_function"] = "xajax_armarCodigoSede";
                                                $configuracion["ajax_control"] = $atributos[$i]['NOM_ID'];
                                            }

                                            $cuadro_tipo = $this->html->cuadro_lista($datos_combo, $atributos[$i]['NOM_ID'], $configuracion, $seleccion, 2, FALSE, -1, $atributos[$i]['NOM_ID'], "");
                                            echo $cuadro_tipo;
                                            ?>
                                        </div>
                                    </li>
                                    <?
                                }if ($atributos[$i]['TIPO'] == 'INBOX') {

                                    $validacion = $atributos[$i]['VALIDA'];
                                    $parametros = $atributos[$i]['PARA_VALIDA'];
                                    $comp = strcmp($atributos[$i]['NOM_BD'], 'SAL_ID_ESPACIO');

                                    if ($validacion != NULL) {

                                        $validacion = explode(";", $validacion);

                                        if ($parametros != NULL) {
                                            $parametros = explode(";", $parametros);
                                        }

                                        $cantValidaciones = count($validacion);

                                        for ($j = 0; $j < $cantValidaciones; $j++) {

                                            if ($validacion[$j] == 'verificarNumeroNomCampo') {
                                                if ($flag == 0) {
                                                    $cadenaValidacion = $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "','" . $atributos[$i]['NOM_FORM'] . "')";
                                                    $this->verificar = $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "','" . $atributos[$i]['NOM_FORM'] . "')";
                                                    $flag++;
                                                } else {
                                                    $cadenaValidacion.=";" . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "','" . $atributos[$i]['NOM_FORM'] . "')";
                                                    $this->verificar .= "&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "','" . $atributos[$i]['NOM_FORM'] . "')";
                                                }
                                            }if ($validacion[$j] == 'longitudCadenaSuperior') {

                                                if ($parametros[$j] != 'NA') {
                                                    if ($flag == 0) {
                                                        $cadenaValidacion = $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                        $this->verificar = $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                        $flag++;
                                                    } else {
                                                        $cadenaValidacion.="; " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                        $this->verificar .= "&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    //echo "<br> cadena validacion ";var_dump($cadenaValidacion);
                                    ?>

                                    <li id="foli6" class="notranslate leftHalf      ">
                                        <label class="desc" id="title6" for="Field6">
                                            <? echo $atributos[$i]['NOM_FORM'] ?>
                                        </label>
                                        <div id="DIV_<? echo $atributos[$i]["NOM_ID"] ?>">
                                            <? if ($atributos[$i]['NOM_ID'] == 'EFA04') {
                                                ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="" maxlength="255" tabindex="<? $tab++ ?>" onKeyPress="return solo_numero(event)" />    
                                                       <?
                                                   } else {
                                                ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="" maxlength="255" tabindex="<? $tab++ ?>" onKeyPress="<? if ($cadenaValidacion != NULL)
                                echo $cadenaValidacion ?>" onkeyup = "this.value=this.value.toUpperCase()"/>
                                    <? } ?>
                                        </div>
                                    </li>
                                    <?
                                }if ($atributos[$i]['TIPO'] == 'CHECKBOX') {
                                    echo "<br> ";
                                }
                            }
                            // echo "<br> validacion ";var_dump($this->verificar);
                            ?>
                            <li class="buttons ">
                                <div>
                                    <input type='hidden' name='action' value='adminEspacioFisico'>
                                    <input type='hidden' name='opcion' value='almacenar'>
                                    <input type='hidden' name='espacio' value='<? echo $espacio ?>'>
                                    <!--input class="btTxt submit" type="button" style='cursor:pointer;' onclick='submit()' name="registrar" value='Registrar'/></div-->
                                    <!--input class="btTxt submit" type="button" style='cursor:pointer;' onclick="if(<?/// echo $this->verificar; ?>){document.forms['<? /// echo $this->formulario  ?>'].submit()}else{false}" name="registrar" value='Registrar'/></div--->
                                    <!---input class="btTxt submit" type="button" style='cursor:pointer;' onclick="if(confirm('Señor usuario, ¿Confirma que desea realizar el registro?'))  {<?/// echo $this->formulario ?>.submit()} " name="registrar" value='Registrar'/></div--->
                                    <input class="btTxt submit" type="button" style='cursor:pointer;' 
                                           onclick="if(confirm('Señor usuario, ¿Confirma que desea realizar el registro?')){if (<? echo $this->verificar; ?>)  {<? echo $this->formulario ?>.submit()}}" name="registrar" value='Registrar'/></div>
                            </li>
                        </ul>
                    </form>
                </div><!--container-->
            </body>
        </html>
        <?
    }

    function datosCombo($configuracion, $atributo, $variable) {

        $est = strpos($atributo['NOM_BD'], '_ESTADO');
        $facS = strpos($atributo['NOM_BD'], 'SED_DEP_ID_FAC');
        $sede = strpos($atributo['NOM_BD'], 'SED_ID');
        $sede_e = strpos($atributo['NOM_BD'], 'EDI_SED_ID');
        $sede_f = strpos($atributo['NOM_BD'], 'DEP_SED_COD');
        $tipo = strpos($atributo['NOM_BD'], 'SAL_GET_COD_ES');
        $fac_e = strpos($atributo['NOM_BD'], 'SAL_ID_FAC');
        $edf = strpos($atributo['NOM_BD'], 'SAL_EDIFICIO');
        $dep_e = strpos($atributo['NOM_BD'], 'SAL_GDE_COD');
        $sTipo = strpos($atributo['NOM_BD'], 'SAL_COD_SUB');
        $tipoDE = strpos($atributo['NOM_BD'], 'SAL_GDE_TIPO');

        if ($est > -1) {

            $datos[0][0] = 'A';
            $datos[0][1] = 'ACTIVO';
            $datos[1][0] = 'I';
            $datos[1][1] = 'INACTIVO';
        }if ($facS > -1 || $fac_e > -1) {

            $cadenaSql = $this->sql->cadena_sql($configuracion, "consultar_facultades", "", "", "");
            $resultados = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaSql, "busqueda");
        }if ($sede > -1 || $sede_e > -1 || $sede_f > -1) {

            $cadenaSql = $this->sql->cadena_sql($configuracion, "consultar_sedes", "", "", "");
            $resultados = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaSql, "busqueda");
        }if ($tipo > -1) {

            $cadenaSql = $this->sql->cadena_sql($configuracion, "consultar_tipos", "", "", "");
            $resultados = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaSql, "busqueda");
        }if ($edf > -1) {

            $cadenaSql = $this->sql->cadena_sql($configuracion, "consultar_edificios", $variable, "", "");
            $resultados = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaSql, "busqueda");
        }if ($dep_e > -1) {

            $cadenaSql = $this->sql->cadena_sql($configuracion, "consultar_depEncargada", "", "", "");
            $resultados = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaSql, "busqueda");
        }if ($sTipo > -1) {

            $cadenaSql = $this->sql->cadena_sql($configuracion, "consultar_subtipos", $variable, "", "");
            $resultados = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaSql, "busqueda");
        }if ($tipoDE > -1) {

            $cadenaSql = $this->sql->cadena_sql($configuracion, "consultarTipoDE", $variable, "", "");
            $resultados = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadenaSql, "busqueda");
        }

        $cant_resultados = count($resultados);

        for ($i = 0; $i < $cant_resultados; $i++) {

            $datos[$i][0] = $resultados[$i]['COD'];
            $datos[$i][1] = utf8_decode($resultados[$i]['NOM']);
        }

        return $datos;
    }

}
?>
