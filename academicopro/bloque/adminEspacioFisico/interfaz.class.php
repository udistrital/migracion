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

    function desplegarFormularioRegistro($configuracion, $atributos, $espacio, $valorIngresado) {

        if ($espacio == "1")
            $titulo = "Facultad";
        if ($espacio == "2")
            $titulo = "Sede";
        if ($espacio == "3")
            $titulo = "Edificio";
        if ($espacio == "4")
            $titulo = "Espacio Físico Académico";
        $tab=0;
        $this->formulario = 'RegistrarEspacio';
        $flag = 0;
        ?>
        <script>
            function solo_decimal(evt, numero) {

                var er1_EntradaS = /^[0-9]*\.?[0-9]*$/
                var charCode = (evt.which) ? evt.which : event.keyCode

                if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 8 || charCode > 8) && (charCode < 110 || charCode > 110))
                    return false;
                if (!er1_EntradaS.test(numero)) {
                    alert("El campo solo permite numeros separados por '.'");
                    return false;
                }
                return true;
            }
            function sin_comillas(e) {
                tecla = (document.all) ? e.keyCode : e.which;
                patron = /[\x5C'"]/;
                te = String.fromCharCode(tecla);
                return !patron.test(te);
            }
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

                                $dato = NULL;

                                $nomAtributo = $atributos[$i]['NOM_ID'];
                                if(is_array($valorIngresado))
                                {
                                    for ($j = 0; $j < count($valorIngresado); $j++) {
                                        $dato = $valorIngresado[$j][$nomAtributo];
                                        if ($dato != NULL)
                                            break;
                                    }
                                }

                                if ($atributos[$i]['TIPO'] == 'COMBOBOX') {

                                    $datos_combo = $this->datosCombo($configuracion, $atributos[$i], "");
                                    $cant_datos = count($datos_combo);

                                    if ($valorIngresado != NULL) {

                                        if ($dato != NULL) {
                                            $seleccion = $dato;
                                        } else {
                                            $seleccion = "-1";
                                        }
                                    } else {
                                        $seleccion = "-1";
                                    }

                                    $cadenaValidacion = NULL;
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

                                            $cuadro_tipo = $this->html->cuadro_lista($datos_combo, $atributos[$i]['NOM_ID'], $configuracion, $seleccion, 2, FALSE, $tab++, $atributos[$i]['NOM_ID'], "");
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
                                                    if (isset($this->verificar))
                                                    {
                                                        $this->verificar .= "&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "','" . $atributos[$i]['NOM_FORM'] . "')";
                                                    }else
                                                        {
                                                            $this->verificar= "&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "','" . $atributos[$i]['NOM_FORM'] . "')";
                                                        }
                                                    
                                                    }
                                            }if ($validacion[$j] == 'longitudCadenaSuperior') {

                                                if ((isset($parametros[$j])) != 'NA') {
                                                    if ($flag == 0) {
                                                        $cadenaValidacion = $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                        $this->verificar = $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                        $flag++;
                                                    } else {
                                                        $cadenaValidacion.="; " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                        if(isset($this->verificar))
                                                        {
                                                            $this->verificar .= "&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                        }else
                                                            {
                                                                $this->verificar= "&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                            }
                                                            
                                                        
                                                        }
                                                }
                                            }if ($validacion[$j] == 'solo_numero') {

                                                if ((isset($parametros[$j])) == 'NA') {
                                                    if ($flag == 0) {
                                                        $cadenaValidacion = "return " . $validacion[$j] . "(event)";
                                                        $flag++;
                                                    } else {
                                                        $cadenaValidacion .= "; return " . $validacion[$j] . "(event)";
                                                        $flag++;
                                                    }
                                                }
                                            }if($atributos[$i]['NOM_ID'] == 'EFA06' || $atributos[$i]['NOM_ID'] == 'E02' || $atributos[$i]['NOM_ID'] == 'S02'){
                                                if ($flag == 0) {
                                                        $cadenaValidacion = "return sin_comillas(event)";
                                                        $flag++;
                                                    } else {
                                                        $cadenaValidacion .= "; return sin_comillas(event)";
                                                        $flag++;
                                                    }
                                            }
                                        }
                                    }
                                    ?>

                                    <li id="foli6" class="notranslate leftHalf      ">
                                        <label class="desc" id="title6" for="Field6">
                                            <? echo $atributos[$i]['NOM_FORM'] ?>
                                        </label>
                                        <div id="DIV_<? echo $atributos[$i]["NOM_ID"] ?>">
                                            <? if ($atributos[$i]['NOM_ID'] == 'EFA04') {
                                                ?>
                                                <script>
                                                    var temp = document.getElementById('EFA04').value;
                                                </script>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="2" tabindex="<? $tab++ ?>" onBlur="xajax_armarCodigoEspacio(document.getElementById('EFA04').value, document.getElementById('EFA06').value, temp)" onKeyPress= "<?
                                                if ($cadenaValidacion != NULL)
                                                    echo $cadenaValidacion
                                                    ?>" />                                                
                                                       <?
                                                   }else if ($atributos[$i]['NOM_ID'] == 'EFA05') {
                                                       ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="2" tabindex="<? $tab++ ?>" onBlur="xajax_armarCodigoEspacio(document.getElementById('EFA05').value, document.getElementById('EFA06').value, 'EFA05')" onKeyPress= "<?
                                                if ($cadenaValidacion != NULL)
                                                    echo $cadenaValidacion
                                                    ?>"/>
                                                       <?
                                                   }else if ($atributos[$i]['NOM_ID'] == 'EFA12') {
                                                       ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="8" tabindex="<? $tab++ ?>" onKeyPress="return solo_decimal(event, this.value)" />
                                                <?
                                            } else if ($atributos[$i]['NOM_ID'] == 'EFA15') {
                                                ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="4" tabindex="<? $tab++ ?>" onKeyPress= "<?
                                                if ($cadenaValidacion != NULL)
                                                    echo $cadenaValidacion
                                                    ?>" />
                                                       <?
                                                   } else if ($atributos[$i]['NOM_ID'] == 'EFA06') {
                                                       ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="11" tabindex="<? $tab++ ?>" onBlur="<?
                                                if ($cadenaValidacion != NULL)
                                                    echo $cadenaValidacion
                                                    ?>" onkeyup = "this.value = this.value.toUpperCase()"/>
                                                       <?
                                                   }else if ($atributos[$i]['NOM_ID'] == 'EFA11' || $atributos[$i]['NOM_ID'] == 'S03' || $atributos[$i]['NOM_ID'] == 'S04' || $atributos[$i]['NOM_ID'] == 'EFA10' || $atributos[$i]['NOM_ID'] == 'E03') {
                                                       ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="125" tabindex="<? $tab++ ?>" onBlur="" onkeyup = "this.value = this.value.toUpperCase()" onKeyPress="return sin_comillas(event)"/>
                                                <?
                                            } else {
                                                ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<?
                                                if ($dato != '')
                                                    echo $dato
                                                    ?>" maxlength="50" tabindex="<? $tab++ ?>" onKeyPress="<?
                                                       if ($cadenaValidacion != NULL)
                                                           echo $cadenaValidacion
                                                           ?>" onkeyup = "this.value = this.value.toUpperCase()"/>
                                                   <? } ?>
                                        </div>
                                    </li>
                                    <?
                                }if ($atributos[$i]['TIPO'] == 'CHECKBOX') {
                                    echo "<br> ";
                                }
                            }
                            ?>
                            <li class="buttons ">
                                <div>
                                    <input type='hidden' name='action' value='adminEspacioFisico'>
                                    <input type='hidden' name='opcion' value='almacenar'>
                                    <input type='hidden' name='espacio' value='<? echo $espacio ?>'>
                                    <!--input class="btTxt submit" type="button" style='cursor:pointer;' onclick='submit()' name="registrar" value='Registrar'/></div-->
                                    <!--input class="btTxt submit" type="button" style='cursor:pointer;' onclick="if(<? echo $this->verificar; ?>){document.forms['<? /// echo $this->formulario          ?>'].submit()}else{false}" name="registrar" value='Registrar'/></div--->
                                    <input class="btTxt submit" type="button" style='cursor:pointer;' onclick="if (confirm('Señor usuario, ¿Confirma que desea realizar el registro?')) {<? echo $this->formulario ?>.submit()
                                                                }" name="registrar" value='Registrar'/></div>
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

        if(isset($resultados))
        {
            $cant_resultados = count($resultados);
            for ($i = 0; $i < $cant_resultados; $i++) {

                $datos[$i][0] = $resultados[$i]['COD'];
                $datos[$i][1] = utf8_decode($resultados[$i]['NOM']);
            }
        }

        return (isset($datos)?$datos:'');
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Funcion:     desplegarListado                                                                      //
    // Descripción: Esta funcion permite desplegar un listado de datos según el campo ATR_LISTAR          //
    //              de la tabla ATRIBUTOESPACIO, y adicionarle a cada uno la opción de modificar y        //
    //              eliminar o de recuperar.                                                              //
    // Parametros de entrada: - $configuracion: Datos básicos del sistema                                 //
    //                        - $atributos: Atributos relacionados con el espacio físico según la tabla   //
    //                          ATRIBUTOESPACIO.                                                          //
    //                        - $espacio: Si es facultad (1), sede (2), edificio (3), espacio físico aca- //
    //                          démico                                                                    //
    //                        - $resultados: Datos a mostrar en el listado                                //
    //                        - $opcion: Si el listado es de eliminación y modificación o de recuperación //
    // Valores de salida: Interfaz del listado                                                            //
    ////////////////////////////////////////////////////////////////////////////////////////////////////////

    function desplegarListado($configuracion, $atributos, $espacio, $resultados, $opcion) {

        $cant_atributos = count($atributos);
        $cant_res = count($resultados);
        ?>
        <html>
            <head>
                <!-- Meta Tags -->
                <meta charset="utf-8">

                <!-- CSS -->
                <link href="<? echo $configuracion["host"] . $configuracion["site"]; ?>/bloque/adminEspacioFisico/css/structure.css" rel="stylesheet" type="text/css">
                <link href="<? echo $configuracion["host"] . $configuracion["site"]; ?>/bloque/adminEspacioFisico/css/form.css" rel="stylesheet" type="text/css">

            </head>
            <body>

                <table align='center' width='80%' cellpadding='2' cellspacing='2' class='sigma contenidotabla'>
                    <thead class='espaciosFisicos' style="color: #FFF; ">
                        <tr class='espaciosFisicos' style="font-size: 10px">
                            <?
                            for ($i = 0; $i < $cant_atributos; $i++) {
                                if ($atributos[$i]['LIS']) {
                                    ?>
                                    <th class='espaciosFisicos' colspan="1" style="font-size: 10px"><? echo strtoupper($atributos[$i]['NOM_FORM']) ?></th>
                                    <?
                                }
                            }if ($opcion == "EliminarModificar" && $espacio != '1') {
                                ?>
                                <th class='espaciosFisicos' colspan="1" style="font-size: 10px">MODIFICAR</th>
                                <th class='espaciosFisicos' colspan="1" style="font-size: 10px">ELIMINAR</th>
                                <?
                            }if ($opcion == "Recuperar") {
                                ?>
                                <th class='espaciosFisicos' colspan="1" style="font-size: 10px">RECUPERAR</th>     
                                <?
                            }
                            ?>
                        </tr>
                    </thead>
                    <?
                    for ($i = 0; $i < $cant_res; $i++) {
                        if ($i % 2 == 0) {
                            $clase = "sigma";
                        } else {
                            $clase = "";
                        }
                        $variable_aux = "";
                        ?>
                        <tr class="<? echo $clase ?>">
                            <?
                            for ($j = 0; $j < $cant_atributos; $j++) {

                                if ($atributos[$j]['LIS']) {
                                    ?>
                                    <td class="centrar">
                                        <?
                                        $nombre_campo = $atributos[$j]['NOM_BD'];

                                        if ($resultados[$i][$nombre_campo] == NULL || $resultados[$i][$nombre_campo] == '-1' || $resultados[$i][$nombre_campo] == ' ') {
                                            echo "Sin Dato ";
                                            $resultados[$i][$nombre_campo] = "Sin Dato ";
                                        } else {
                                            echo $resultados[$i][$nombre_campo];
                                        }
                                        ?>
                                    </td>
                                    <?
                                    //$variable_aux[$nombre_campo] = $resultados[$i][$nombre_campo];
                                    $variable_aux .= $nombre_campo . "=" . $resultados[$i][$nombre_campo] . ";";
                                }
                            }

                            $variable_aux = rtrim($variable_aux, ";");

                            $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";

                            if ($opcion == "EliminarModificar" && $espacio != '1') {

                                $variable = "pagina=adminEspacioFisico";
                                $variable.="&opcion=modificar";
                                $variable.="&espacio=" . $espacio;
                                $variable.="&seleccion=" . $variable_aux;
                                $variable = $this->cripto->codificar_url($variable, $configuracion);
                                ?>
                                <td class="centrar">
                                    <a href="<?= $indice . $variable ?>">
                                        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/Modificar.png" width="25" height="25" border="0" alt="Registrar">
                                    </a>
                                </td>
                                <?
                                $variable = "pagina=adminEspacioFisico";
                                $variable.="&opcion=eliminar";
                                $variable.="&espacio=" . $espacio;
                                $variable.="&seleccion=" . $variable_aux;
                                $variable = $this->cripto->codificar_url($variable, $configuracion);
                                ?>
                                <td class="centrar">
                                    <a onclick="if (confirm('Señor usuario, ¿Confirma que desea eliminar el registro?')) {
                                                                            window.location = '<? echo $indice . $variable; ?>'
                                                                        }">
                                        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/Eliminar.png" width="25" height="25" border="0" alt="Registrar">
                                    </a>
                                </td>
                                <?
                            }if ($opcion == "Recuperar") {

                                $variable = "pagina=adminEspacioFisico";
                                $variable.="&opcion=recuperar";
                                $variable.="&espacio=" . $espacio;
                                $variable.="&seleccion=" . $variable_aux;
                                $variable = $this->cripto->codificar_url($variable, $configuracion);
                                ?>
                                <td class="centrar">
                                    <a onclick="if (confirm('Señor usuario, ¿Confirma que desea recuperar el registro?')) {
                                                                            window.location = '<? echo $indice . $variable; ?>'
                                                                        }">
                                        <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/Recuperar.png" width="25" height="25" border="0" alt="Registrar">
                                    </a>
                                </td>
                                <?
                            }
                            ?>
                        </tr>
                        <?
                    }
                    ?>
                </table>
            <frame></frame>
        </body>
        </html>
        <?
    }

    function desplegarFormularioModificación($configuracion, $atributos, $espacio, $resultado) {
        $flag='';
        $tab=0;
        $cant_atributos = count($atributos);
        $this->formulario = 'ModificarEspacio';
        $valorInicial = "";

        foreach ($resultado[0] as $key => $value) {

            $valorInicial.=$key . "=" . $value . ";";
        }

        $valorInicial = rtrim($valorInicial, ";");
        if ($espacio == 1)$titulo = 'Facultad';
        if ($espacio == 2)$titulo = 'Sede';
        if ($espacio == 3)$titulo = 'Edificio';
        if ($espacio == 4)$titulo = 'Espacio Físico Académico';
        ?>
        <script>
                                                    function solo_decimal(evt, numero) {

                                                        var er1_EntradaS = /^[0-9]*\.?[0-9]*$/
                                                        var charCode = (evt.which) ? evt.which : event.keyCode

                                                        if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 8 || charCode > 8) && (charCode < 110 || charCode > 110))
                                                            return false;
                                                        if (!er1_EntradaS.test(numero)) {
                                                            alert("El campo solo permite numeros separados por '.'");
                                                            return false;
                                                        }
                                                        return true;
                                                    }
                                                    function sin_comillas(e) {
                                                        tecla = (document.all) ? e.keyCode : e.which;
                                                        patron = /[\x5C'"]/;
                                                        te = String.fromCharCode(tecla);
                                                        return !patron.test(te);
                                                    }
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

                        <header  class="espaciosFisicos" id="header" class="info" style="color: #FFF; "><b>
                                Modificar <? echo $titulo; ?>
                                <div></div>
                        </header>

                        <ul>
                            <?
                            $cant_atributos = count($atributos);

                            for ($i = 0; $i < $cant_atributos; $i++) {

                                $nom_atributo = $atributos[$i]['NOM_BD'];
                                $dato = $resultado[0][$nom_atributo];

                                if (!$dato) {
                                    $valorInicial.=";" . $nom_atributo . "= -1;";
                                }

                                if ($atributos[$i]['TIPO'] == 'COMBOBOX') {


                                    if ($atributos[$i]['NOM_BD'] == 'SAL_GDE_TIPO') {
                                        $datos_combo = $this->datosCombo($configuracion, $atributos[$i], "");
                                    } else {
                                        $datos_combo = $this->datosCombo($configuracion, $atributos[$i], "-1");
                                    }

                                    $cant_datos = count($datos_combo);

                                    if ($dato == NULL) {
                                        $seleccion = "-1";
                                    } else {
                                        $seleccion = $dato;
                                    }
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
                                    $cadenaValidacion = NULL;
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
                                                    if (isset($this->verificar))
                                                    {
                                                        $this->verificar.="&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "','" . $atributos[$i]['NOM_FORM'] . "')";
                                                    }else
                                                        {
                                                            $this->verificar="&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "','" . $atributos[$i]['NOM_FORM'] . "')";
                                                        }
                                                    
                                                }
                                            }if ($validacion[$j] == 'longitudCadenaSuperior') {

                                                if ((isset($parametros[$j]))&&$parametros[$j] != 'NA') {
                                                    if ($flag == 0) {
                                                        $cadenaValidacion = $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                        $this->verificar = $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                        $flag++;
                                                    } else {
                                                        $cadenaValidacion.="; " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                        if(isset($this->verificar))
                                                        {
                                                            $this->verificar.="&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                        }else
                                                            {
                                                                $this->verificar="&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_ID'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
                                                            }
                                                    }
                                                }
                                            }if ($validacion[$j] == 'solo_numero') {

                                                if ((isset($parametros[$j]))&&$parametros[$j] == 'NA') {
                                                    if ($flag == 0) {
                                                        $cadenaValidacion = "return " . $validacion[$j] . "(event)";
                                                        $flag++;
                                                    } else {
                                                        $cadenaValidacion .= "; return " . $validacion[$j] . "(event)";
                                                        $flag++;
                                                    }
                                                }
                                            }if($atributos[$i]['NOM_ID'] == 'EFA06' || $atributos[$i]['NOM_ID'] == 'E02' || $atributos[$i]['NOM_ID'] == 'S02'){
                                                if ($flag == 0) {
                                                        $cadenaValidacion = "return sin_comillas(event)";
                                                        $flag++;
                                                    } else {
                                                        $cadenaValidacion .= "; return sin_comillas(event)";
                                                        $flag++;
                                                    }
                                            }
                                        }
                                    }
                                    ?>

                                    <li id="foli6" class="notranslate leftHalf      ">
                                        <label class="desc" id="title6" for="Field6">
                                            <? echo $atributos[$i]['NOM_FORM'] ?>
                                        </label>
                                        <div id="DIV_<? echo $atributos[$i]["NOM_ID"] ?>">
                                            <? if ($atributos[$i]['NOM_ID'] == 'EFA04') { ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="2" tabindex="19" onBlur="xajax_armarCodigoEspacio(document.getElementById('EFA04').value, document.getElementById('EFA06').value)" onKeyPress= "<?
                                                if ($cadenaValidacion != NULL)
                                                    echo $cadenaValidacion
                                                    ?>" />                                                
                                                       <?
                                                   }else if ($atributos[$i]['NOM_ID'] == 'EFA05') {
                                                       ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="2" tabindex="19" onBlur="xajax_armarCodigoEspacio(document.getElementById('EFA05').value, document.getElementById('EFA06').value)" onKeyPress= "<?
                                                if ($cadenaValidacion != NULL)
                                                    echo $cadenaValidacion
                                                    ?>"/>
                                                       <?
                                                   }else if ($atributos[$i]['NOM_ID'] == 'EFA12') {
                                                       ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="8" tabindex="19" onKeyPress="return solo_decimal(event, this.value)" />
                                                <?
                                            } else if ($atributos[$i]['NOM_ID'] == 'EFA15') {
                                                ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="4" tabindex="19" onKeyPress= "<?
                                                if ($cadenaValidacion != NULL)
                                                    echo $cadenaValidacion
                                                    ?>" />
                                                       <?
                                                   }else if ($atributos[$i]['NOM_ID'] == 'EFA11' || $atributos[$i]['NOM_ID'] == 'S03' || $atributos[$i]['NOM_ID'] == 'S04' || $atributos[$i]['NOM_ID'] == 'EFA10' || $atributos[$i]['NOM_ID'] == 'E03') {
                                                       ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="125" tabindex="<? $tab++ ?>" onBlur="" onkeyup = "this.value = this.value.toUpperCase()" onKeyPress="return sin_comillas(event)"/>
                                                <?
                                            } else {
                                                ?>
                                                <input id="<? echo $atributos[$i]['NOM_ID']; ?>" name="<? echo $atributos[$i]['NOM_ID']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="50" tabindex="19" onKeyPress="<?
                                                if ($cadenaValidacion != NULL)
                                                    echo $cadenaValidacion
                                                    ?>" onkeyup = "this.value = this.value.toUpperCase()"/>
                                                   <? } ?>
                                        </div>
                                    </li>
                                    <?
                                }if ($atributos[$i]['TIPO'] == 'CHECKBOX') {
                                    echo "<br> ";
                                }
                            }
                            ?>
                            <li class="buttons ">
                                <div>
                                    <input type='hidden' name='action' value='adminEspacioFisico'>
                                    <input type='hidden' name='opcion' value='modificar'>
                                    <input type='hidden' name='espacio' value='<? echo $espacio ?>'>
                                    <input type='hidden' name='infoEspacio' value='<? echo $valorInicial ?>'>                                    
                                    <input class="btTxt submit" type="button" style='cursor:pointer;' onclick="if (confirm('Señor usuario, ¿Confirma que desea realizar la modificación?')) {<? echo $this->formulario ?>.submit()
                                                                }" name="modificar" value='Modificar'/></div>
                            </li>
                        </ul>
                    </form>
                </div><!--container-->
            </body>
        </html>
        <?
    }

    /*     * *function desplegarFormularioModificación($configuracion, $atributos, $espacio, $resultado) {

      $cant_atributos = count($atributos);
      $this->formulario = 'ModificarEspacio';

      foreach ($resultado[0] as $key => $value) {

      $valorInicial.=$key . "=" . $value . ";";
      }

      $valorInicial = rtrim($valorInicial, ";");

      if ($espacio == 1)
      $titulo = 'Facultad';
      if ($espacio == 2)
      $titulo = 'Sede';
      if ($espacio == 3)
      $titulo = 'Edificio';
      ?>
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
      <form id="form92" name="<? echo $this->formulario ?>" class="wufoo  page" autocomplete="off" enctype="multipart/form-data">

      <header id="header" class="info">
      <h2>Modificar <? echo $titulo; ?></h2>
      <div></div>
      </header>

      <ul>

      <?
      for ($i = 0; $i < $cant_atributos; $i++) {

      $nom_atributo = $atributos[$i]['NOM_BD'];
      $dato = $resultado[0][$nom_atributo];

      if (!$dato) {
      $valorInicial.=";" . $nom_atributo . "= -1;";
      }

      if ($atributos[$i]['TIPO'] == 'COMBOBOX') {

      $nomAtributoAux = $atributos[$i - 1]['NOM_BD'];
      //Se obtiene el dato del registro anterior con el fin de enviar para la consulta de los datos del combo,
      //ya que en el caso del edificio la llave es compuesta y se necesita para la búsqueda el codigo de la sede
      $datoAux = $resultado[0][$nomAtributoAux];

      $datos_combo = $this->datosCombo($configuracion, $atributos[$i], $datoAux);
      $cant_datos = count($datos_combo);
      $seleccion = $dato;
      ?>
      <li id="foli6" class="notranslate leftHalf      ">
      <label class="desc" id="title6" for="Field6">
      <? echo $atributos[$i]['NOM_FORM'] ?>
      </label>
      <div id="DIV_<? echo $atributos[$i]["NOM_BD"] ?>">
      <?
      $configuracion["ajax_function"] = "xajax_" . $atributos[$i + 1]['NOM_BD'];
      $configuracion["ajax_control"] = $atributos[$i]['NOM_BD'];
      $cuadro_tipo = $this->html->cuadro_lista($datos_combo, $atributos[$i]['NOM_BD'], $configuracion, $seleccion, 2, FALSE, -1, $atributos[$i]['NOM_BD'], "");
      echo $cuadro_tipo;
      ?>
      </div>
      </li>
      <?
      }if ($atributos[$i]['TIPO'] == 'INBOX') {

      $validacion = $atributos[$i]['VALIDA'];
      $parametros = $atributos[$i]['PARA_VALIDA'];

      if ($validacion != NULL) {

      $validacion = explode(";", $validacion);

      if ($parametros != NULL) {
      $parametros = explode(";", $parametros);
      }

      $cantValidaciones = count($validacion);

      for ($j = 0; $j < $cantValidaciones; $j++) {

      if ($validacion[$j] == 'verificarNumeroNomCampo') {
      if ($flag == 0) {
      $this->verificar = $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_BD'] . "','" . $atributos[$i]['NOM_FORM'] . "')";
      $flag++;
      } else {
      $this->verificar .= "&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_BD'] . "','" . $atributos[$i]['NOM_FORM'] . "')";
      }
      }if ($validacion[$j] == 'longitudCadenaSuperior') {

      if ($parametros[$j] != 'NA') {
      if ($flag == 0) {
      $this->verificar = $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_BD'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
      $flag++;
      } else {
      $this->verificar .= "&& " . $validacion[$j] . "(" . $this->formulario . ",'" . $atributos[$i]['NOM_BD'] . "'," . $parametros[$j] . ",'" . $atributos[$i]['NOM_FORM'] . "')";
      }
      }
      }
      }
      }
      ?>
      <li id="foli6" class="notranslate leftHalf      ">
      <label class="desc" id="title6" for="Field6">
      <? echo $atributos[$i]['NOM_FORM'] ?>
      </label>
      <input id="<? echo $atributos[$i]['NOM_BD']; ?>" name="<? echo $atributos[$i]['NOM_BD']; ?>" type="text" class="field text large" value="<? echo $dato ?>" maxlength="255" tabindex="19" onkeyup="" />
      </li>
      <?
      }
      }
      ?>

      <li class="buttons ">
      <div>
      <input type='hidden' name='action' value='adminEspacioFisico'>
      <input type='hidden' name='opcion' value='modificar'>
      <input type='hidden' name='espacio' value='<? echo $espacio ?>'>
      <input type='hidden' name='infoEspacio' value='<? echo $valorInicial ?>'>
      <!--input class="btTxt submit" type="button" style='cursor:pointer;' onclick='submit()' name="modificar" value='Modificar'/></div-->
      <input class="btTxt submit" type="button" style='cursor:pointer;' onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario ?>'].submit()}else{false}" name="modificar" value='Modificar'/></div>
      </li>
      </ul>
      </form>
      </div>
      </body>
      </html>
      <?
      }** */

    function desplegarBuscarEspacio($configuracion, $atributos, $espacio) {
        ?>
        <html>

            <head>
                <!-- Meta Tags -->
                <meta charset="utf-8">

                <!-- CSS -->
                <link href="<? echo $configuracion["host"] . $configuracion["site"]; ?>/bloque/adminEspacioFisico/css/structure.css" rel="stylesheet" type="text/css">
                <link href="<? echo $configuracion["host"] . $configuracion["site"]; ?>/bloque/adminEspacioFisico/css/form.css" rel="stylesheet" type="text/css">

                <!-- JavaScript -->
                <script src="scripts/wufoo.js"></script>
            </head>
            <body id="public">
                <div id="container" class="ltr">
                    <form id="form92" name="form92" class="wufoo  page" autocomplete="off" enctype="multipart/form-data">

                        <header class="espaciosFisicos" id="header" class="info" style="color: #FFF; "><b>
                                Consultar Espacio Físico Académico
                                <div></div>
                        </header>
                        <ul>
                            <li id="foli6" class="notranslate leftHalf      ">
                                <label class="desc" id="title6" for="Field6">
                                    Digite código o nombre del espacio físico académico que desea buscar
                                </label>
                                <input id="id_espacio" type="text" class="field text large" value="" maxlength="255" tabindex="19" onkeypress="javascript:if (event.keyCode == 13) {
                                                                    xajax_buscarEspacioFisicoAcademico(document.getElementById('id_espacio').value);
                                                                    return false;
                                                                }" />
                            </li>
                            <br><br><br>
                            <img src="<? echo $configuracion["site"] . $configuracion["grafico"] . "/viewmag.png" ?>" style="cursor:pointer" width="30" height="30" border="0" onclick="xajax_buscarEspacioFisicoAcademico(document.getElementById('id_espacio').value)" ><br> 
                        </ul>                        
                        <div id="div_infoEFA">
                        </div>
                    </form>   
                </div> 
            </body>
        </html>
        <?
    }

    /*     * *************************************************************************************************
     * Esta función permite desplegar la información completa del espacio físico académico previamente *
     * seleccionado por el usuario tras la busqueda por nombre o por código                            *
     * ************************************************************************************************ */

    function desplegarInformacionEFA($configuracion, $atributos, $espacioFisicoA) {

        $cantAtributos = count($atributos);
        ?>
        <html>
            <head>
                <!-- Meta Tags -->
                <meta charset="utf-8">
                <!-- CSS -->
                <link href="<? echo $configuracion["host"] . $configuracion["site"]; ?>/bloque/adminEspacioFisico/css/structureMostrarConsulta.css" rel="stylesheet" type="text/css">
                <link href="<? echo $configuracion["host"] . $configuracion["site"]; ?>/bloque/adminEspacioFisico/css/formMostrarConsulta.css" rel="stylesheet" type="text/css">
                <!--[if lt IE 10]>
                <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
                <![endif]-->
            </head>

            <body id="public">
                <div id="container" class="ltr">
                    <form id="form44" name="form44" class="wufoo  page" autocomplete="off" enctype="multipart/form-data">
                        <header id="header" class="info">
                            <h2>Espacio Físico Académico <? echo $espacioFisicoA[0]['SAL_ID_ESPACIO'] ?></h2>                           
                        </header>

                        <ul>
                            <li id="foli435" class="likert notranslate 
                                col5
                                ">
                                <table cellspacing="0">
                                    <tbody>
                                        <?
                                        for ($i = 0; $i < $cantAtributos; $i++) {
                                            $nombreCampo = $atributos[$i]['NOM_BD'];
                                            if(isset($variable_aux))
                                            {
                                            $variable_aux.=$nombreCampo . "=" . $espacioFisicoA[0][$nombreCampo] . ";";
                                            }else
                                                {
                                                    $variable_aux=$nombreCampo . "=" . $espacioFisicoA[0][$nombreCampo] . ";";
                                                }
                                            ?>
                                            <tr class="statement435">
                                                <th><label><? echo $atributos[$i]['NOM_FORM'] ?></label></th>
                                                <td title="Dato">                                                    
                                                    <label for="Field435_1"><?
                                                        if ($espacioFisicoA[0][$nombreCampo] == NULL) {
                                                            echo " ";
                                                        } else {
                                                            echo $espacioFisicoA[0][$nombreCampo];
                                                        }
                                                        ?></label>
                                                </td>
                                            </tr>
                                        <? } ?>
                                    </tbody>
                                </table>  
                                <table>
                                    <tr>
                                        <td><label for="Field435_1">Modificar</label></td>
                                        <td><label for="Field435_1">Eliminar</label></td>
                                    </tr>
                                    <tr>                                       
                                        <td> 
                                            <?
                                            $variable_aux = rtrim($variable_aux, ";");

                                            $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                            $variable = "pagina=adminEspacioFisico";
                                            $variable.="&opcion=modificar";
                                            $variable.="&espacio=4";
                                            $variable.="&seleccion=" . $variable_aux;
                                            $variable = $this->cripto->codificar_url($variable, $configuracion);
                                            ?>
                                            <a href="<?= $indice . $variable ?>"> 
                                                <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/Modificar.png" width="45" height="45" border="0" alt="Modificar" title="Modificar">
                                            </a>
                                        </td>                                        
                                        <td>
                                            <?
                                            $indice = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                                            $variable = "pagina=adminEspacioFisico";
                                            $variable.="&opcion=eliminar";
                                            $variable.="&espacio=4";
                                            $variable.="&seleccion=" . $variable_aux;
                                            $variable = $this->cripto->codificar_url($variable, $configuracion);
                                            ?>
                                            <a href="<?= $indice . $variable ?>" title="Eliminar"> 
                                                <img src="<? echo $configuracion['site'] . $configuracion['grafico'] ?>/Eliminar.png" width="45" height="45" border="0" alt="Eliminar" title="Eliminar">
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                        </ul>
                    </form> 

                </div><!--container-->
            </body>
        </html>
        <?
    }

}
?>
