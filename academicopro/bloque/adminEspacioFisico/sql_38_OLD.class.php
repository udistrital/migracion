<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class SqlAdminEspacioFisico extends sql {

    //function cadena_sql($configuracion, $conexion, $opcion, $variable="", $campo) {
    function cadena_sql($configuracion, $opcion, $variable, $campos, $datos_aux) {

        switch ($opcion) {

            case 'atributosEspacio':

                $cadena_sql = " SELECT";
                $cadena_sql.= " ae.atr_nombre_form NOM_FORM,";
                $cadena_sql.= " ae.atr_nombre_bd NOM_BD,";
                $cadena_sql.= " ta.tipa_nombre TIPO,";
                $cadena_sql.= " ae.atr_distinct DIS,";
                $cadena_sql.= " ae.atr_obligatorio OBL,";
                $cadena_sql.= " ae.atr_listar LIS,";
                $cadena_sql.= " ae.atr_validacion VALIDA,";
                $cadena_sql.= " ae.atr_validacion_parametros PARA_VALIDA";
                $cadena_sql.= " FROM atributoespacio ae";
                $cadena_sql.= " INNER JOIN tipoatributo ta ON ae.atr_tipa_id=ta.tipa_id";
                $cadena_sql.= " WHERE ae.atr_estado='A'";
                $cadena_sql.= " and ae.atr_esp_id=" . $variable;
                $cadena_sql.= " ORDER BY atr_id";
                break;

            case 'consultar_facultades':

                $cadena_sql = " SELECT";
                $cadena_sql.= " dep_id_fac COD,";
                $cadena_sql.= " '(' || dep_id_fac || ') ' ||  dep_nombre NOM";
                $cadena_sql.= " FROM";
                $cadena_sql.= " gedep";
                $cadena_sql.= " WHERE";
                $cadena_sql.= " dep_estado='A'";
                $cadena_sql.= " and dep_nombre LIKE '%FACULTAD%'";
                $cadena_sql.= " ORDER BY dep_cod ";
                break;

            case 'consultar_sedes':

                $cadena_sql = " SELECT";
                $cadena_sql.= " sed_id COD,";
                $cadena_sql.= " '(' || sed_id || ') ' ||  sed_nombre NOM";
                $cadena_sql.= " FROM";
                $cadena_sql.= " gesede";
                $cadena_sql.= " WHERE";
                $cadena_sql.= " sed_estado='A'";
                $cadena_sql.= " and sed_id IS NOT NULL";
                if ($variable <> "") {
                    $cadena_sql.=" AND ";
                    $cadena_sql.="sed_dep_id_fac=";
                    $cadena_sql.="'" . $variable . "' ";
                }

                break;

            case 'consultar_tipos':

                $cadena_sql = " SELECT";
                $cadena_sql.= " get_cod_es COD,";
                $cadena_sql.= " get_nombre NOM";
                $cadena_sql.= " FROM";
                //$cadena_sql.= " getipo_espacio";
                $cadena_sql.= " mntge.getipo_espacio";
                $cadena_sql.= " WHERE";
                $cadena_sql.= " get_estado='A'";
                break;

            case 'consultar_edificios':

                $cadena_sql = " SELECT";
                $cadena_sql.= " edi_cod COD,";
                $cadena_sql.= " '(' || edi_cod || ') ' ||  edi_nombre NOM";
                $cadena_sql.= " FROM";
                $cadena_sql.= " geedificio";
                $cadena_sql.= " WHERE";
                $cadena_sql.= " edi_estado='A'";
                if ($variable <> -1) {
                    $cadena_sql.=" AND ";
                    $cadena_sql.="edi_sed_id=";
                    $cadena_sql.="'" . $variable . "' ";
                }
                break;

            case 'consultar_subtipos':

                $cadena_sql = " SELECT";
                $cadena_sql.= " ges_cod_sub COD,";
                $cadena_sql.= " ges_nombre NOM";
                $cadena_sql.= " FROM";
                //$cadena_sql.= " gesubtipo_espacio";
                $cadena_sql.= " mntge.gesubtipo_espacio";
                $cadena_sql.= " WHERE";
                $cadena_sql.= " ges_estado='A'";
                if ($variable <> -1) {
                    $cadena_sql.=" AND ";
                    $cadena_sql.="ges_get_cod_es=";
                    $cadena_sql.="'" . $variable . "' ";
                }
                break;

            case 'consultar_depEncargada':

                $cadena_sql = " SELECT";
                $cadena_sql.= " gde_nombre COD,";
                $cadena_sql.= " gde_nombre NOM";
                $cadena_sql.= " FROM";
                //$cadena_sql.= " gedep_encargada";
                $cadena_sql.= " mntge.gedep_encargada";
                $cadena_sql.= " WHERE";
                $cadena_sql.= " gde_estado='A'";
                $cadena_sql.= " ORDER BY";
                $cadena_sql.= " gde_nombre";

                break;

            case 'consultar_tipo_depE':

                $cadena_sql = " SELECT";
                $cadena_sql.= " gde_tipo COD,";
                $cadena_sql.= " gde_tipo NOM";
                $cadena_sql.= " FROM";
                //$cadena_sql.= " gedep_encargada";
                $cadena_sql.= " mntge.gedep_encargada";
                $cadena_sql.= " WHERE";
                $cadena_sql.= " gde_nombre='" . $variable . "'";
                break;

            case 'existeInfo':

                $cadena_sql = "SELECT ";
                $cadena_sql.= $campos . " ";
                $cadena_sql.= " FROM ";
                if ($datos_aux == '1')
                    $cadena_sql.= " gedep ";
                if ($datos_aux == '2')
                    $cadena_sql.= " gesede ";
                if ($datos_aux == '3')
                    $cadena_sql.= " geedificio ";
                if ($datos_aux == '4')
                    $cadena_sql.= " gesalon_2012 ";
                $cadena_sql.= " WHERE " . $campos . "='" . $variable . "'";
                break;

            case 'insertarEspacio':

                $cadena_sql = " INSERT INTO";
                if ($datos_aux == '1') {
                    $cadena_sql.= " gedep";
                }if ($datos_aux == '2') {
                    $cadena_sql.= " gesede";
                }if ($datos_aux == '3') {
                    $cadena_sql.= " geedificio";
                }if ($datos_aux == '4') {
                    $cadena_sql.= " gesalon_2012";
                }

                $cadena_sql.= " (";

                $cadena_sql2.= " VALUES(";

                if ($datos_aux == 1) {
                    $cadena_sql.=" dep_cod,";
                    $cadena_sql2.=" (select max(dep_cod)+1 from gedep),";
                }if ($datos_aux == 2) {
                    $cadena_sql.=" sed_cod,";
                    $cadena_sql2.=" (select max(sed_cod)+1 from gesede),";
                }

                $cant_campos = count($campos);

                for ($i = 0; $i < $cant_campos; $i++) {

                    $nom_campo = $campos[$i]['NOM_BD'];
                    $valor = $variable[$nom_campo];
                    
                    if($nom_campo=='SAL_AREA'){
                        $valor=str_replace(".", ",", $valor);
                    }

                    if ($i == 0) {
                        $cadena_sql.= $nom_campo;
                        $cadena_sql2.= "'" . utf8_decode($valor) . "'";
                    } else {

                        $cadena_sql.= ", " . $nom_campo;

                        if ($nom_campo == 'SAL_GDE_COD') {
                            //$cadena_sql2.=", (select gde_cod from gedep_encargada where gde_nombre='" . utf8_decode($valor) . "')";
                            $cadena_sql2.=", (select gde_cod from mntge.gedep_encargada where gde_nombre='" . utf8_decode($valor) . "')";
                        }else if ($nom_campo == 'DEP_SED_COD') {
                            $cadena_sql2.=", (select sed_cod from gesede where sed_id='" . utf8_decode($valor) . "')";
                        } else {
                            $cadena_sql2.= ", '" . utf8_decode($valor) . "'";
                        }
                    }
                }

                $cadena_sql.=")";
                $cadena_sql2.=")";
                $cadena_sql = $cadena_sql . $cadena_sql2;
                //echo "<br> cadena sql ".$cadena_sql;//exit;
                break;

            case 'listarEspacios':

                $flag = 0;

                $cadena_sql = " SELECT";

                for ($i = 0; $i < count($campos); $i++) {

                    if ($campos[$i]['LIS'] == 1) {
                        $nomCampo = $campos[$i]['NOM_BD'];

                        if ($nom_campo == 'SED_DEP_ID_FAC') {
                            if ($flag == 0) {
                                $cadena_sql.= " (select dep_nombre from gedep where dep_id_fac='" . $nomCampo . "') " . $nomCampo;
                                $flag++;
                            } else {
                                $cadena_sql.= ", (select dep_nombre from gedep where dep_id_fac='" . $nomCampo . "') " . $nomCampo;
                            }
                        } else {
                            if ($flag == 0) {
                                $cadena_sql.= " " . $nomCampo . " " . $nomCampo;
                                $flag++;
                            } else {
                                $cadena_sql.= ", " . $nomCampo . " " . $nomCampo;
                            }
                        }
                    }
                }

                $cadena_sql.= " FROM";

                if ($datos_aux == 1) {
                    $cadena_sql.= " gedep";
                }if ($datos_aux == 2) {
                    $cadena_sql.= " gesede";
                }if ($datos_aux == 3) {
                    $cadena_sql.= " geedificio";
                }
                $cadena_sql.= " WHERE";

                if ($datos_aux == 1) {
                    $cadena_sql.= " dep_estado='A'and dep_id_fac is not null";
                    $cadena_sql.= " ORDER BY dep_id_fac ";
                }if ($datos_aux == 2) {
                    $cadena_sql.= " sed_estado='A' and sed_id IS NOT NULL";
                }if ($datos_aux == 3) {
                    $cadena_sql.= " edi_estado='A'";
                    $cadena_sql.= " ORDER BY edi_sed_id ";
                }
                break;

            case 'infoFacultad':

                $cant_campos = count($campos);

                $cadena_sql = " SELECT";
                $cadena_sql.= " dep_id_fac DEP_ID_FAC,";
                $cadena_sql.= " dep_nombre DEP_NOMBRE,";
                $cadena_sql.= " dep_sed_cod DEP_SED_COD,";
                $cadena_sql.= " dep_emp_cod DEP_EMP_COD,";
                $cadena_sql.= " dep_estado DEP_ESTADO";
                $cadena_sql.= " FROM";
                $cadena_sql.= " gedep";
                if ($cant_campos > 0) {

                    $cadena_sql.= " WHERE";

                    for ($i = 0; $i < $cant_campos; $i++) {

                        $nom_campo = $campos[$i][0];
                        if ($i == 0) {
                            $cadena_sql.= " " . $nom_campo . "='" . $variable[$i][$nom_campo] . "'";
                        } else {
                            $cadena_sql.= " AND " . $nom_campo . "='" . $variable[$i][$nom_campo] . "'";
                        }
                    }
                }
                break;

            case 'infoSede':

                $cadena_sql = " SELECT";

                for ($i = 0; $i < count($datos_aux); $i++) {
                    $nomAtributo = $datos_aux[$i]['NOM_BD'];
                    if ($i == 0) {
                        $cadena_sql.= " " . $nomAtributo . " " . $nomAtributo;
                    } else {
                        $cadena_sql.= ", " . $nomAtributo . " " . $nomAtributo;
                    }
                }

                $cadena_sql.=" FROM gesede ";
                $cadena_sql.= " WHERE";

                $cant_campos = count($campos);

                if ($cant_campos > 0) {

                    for ($i = 0; $i < $cant_campos; $i++) {

                        $nom_campo = $campos[$i][0];

                        if ($i == 0) {
                            $cadena_sql.= " " . $nom_campo . "='" . $variable[$i][$nom_campo] . "'";
                        } else {
                            $cadena_sql.= " AND " . $nom_campo . "='" . $variable[$i][$nom_campo] . "'";
                        }
                    }
                }
                break;

            case 'infoEdificio':

                $cant_campos = count($campos);

                $cadena_sql = " SELECT";
                for ($i = 0; $i < count($datos_aux); $i++) {
                    $nomAtributo = $datos_aux[$i]['NOM_BD'];
                    if ($i == 0) {
                        $cadena_sql.= " " . $nomAtributo . " " . $nomAtributo;
                    } else {
                        $cadena_sql.= ", " . $nomAtributo . " " . $nomAtributo;
                    }
                }

                $cadena_sql.= " FROM geedificio";

                if ($cant_campos > 0) {

                    $cadena_sql.= " WHERE";

                    for ($i = 0; $i < $cant_campos; $i++) {

                        $nom_campo = $campos[$i][0];
                        if ($i == 0) {
                            $cadena_sql.= " " . $nom_campo . "='" . $variable[$i][$nom_campo] . "'";
                        } else {
                            $cadena_sql.= " AND " . $nom_campo . "='" . $variable[$i][$nom_campo] . "'";
                        }
                    }
                }
                break;

            case 'infoEFA':

                $cant_campos = count($campos);

                $cadena_sql = " SELECT";

                for ($i = 0; $i < $cant_campos; $i++) {

                    if ($campos[$i]['NOM_BD'] == 'SAL_GDE_COD') {
                        //$cadena_sql .= ", (SELECT GDE_NOMBRE FROM GEDEP_ENCARGADA ";
                        $cadena_sql .= ", (SELECT GDE_NOMBRE FROM MNTGE.GEDEP_ENCARGADA ";
                        $cadena_sql .= "  WHERE GDE_COD=" . $campos[$i]['NOM_BD'];
                        $cadena_sql .= "  AND GDE_TIPO=" . $campos[$i + 1]['NOM_BD'] . ") " . $campos[$i]['NOM_BD'];
                    } else if ($campos[$i]['NOM_BD'] == 'SAL_ID_FAC') {
                        $cadena_sql.= ", (select dep_nombre from gedep where dep_id_fac=" . $campos[$i]['NOM_BD'] . ") " . $campos[$i]['NOM_BD'];
                    } else if ($campos[$i]['NOM_BD'] == 'SAL_SED_ID') {
                        $cadena_sql.= ", (select sed_nombre from gesede where sed_id=" . $campos[$i]['NOM_BD'] . ") " . $campos[$i]['NOM_BD'];
                    } else if ($campos[$i]['NOM_BD'] == 'SAL_EDIFICIO') {
                        $cadena_sql.= ", (select edi_nombre from geedificio where edi_cod=" . $campos[$i]['NOM_BD'] . ") " . $campos[$i]['NOM_BD'];
                    } else {
                        if ($i == 0) {
                            $cadena_sql.= " " . $campos[$i]['NOM_BD'] . " " . $campos[$i]['NOM_BD'];
                        } else {
                            $cadena_sql.= ", " . $campos[$i]['NOM_BD'] . " " . $campos[$i]['NOM_BD'];
                        }
                    }
                }

                $cadena_sql.= " FROM";
                $cadena_sql.= " gesalon_2012";
                $cadena_sql.= " WHERE";
                $cadena_sql.= " sal_id_espacio='" . $variable . "'";
                //ECHO "<BR> cadena sql ".$cadena_sql;
                break;

            case 'infoEspacioFisicoAcademico':

                $cant_campos = count($campos);

                $cadena_sql = " SELECT";

                if ($cant_campos > 0) {

                    $cadena_sql2.= " WHERE";

                    for ($i = 0; $i < $cant_campos; $i++) {

                        $nom_campo = $campos[$i][0];

                        if ($nom_campo == 'SAL_GDE_COD') {
                            //$cadena_sql .= ", (SELECT GDE_NOMBRE FROM GEDEP_ENCARGADA";
                            $cadena_sql .= ", (SELECT GDE_NOMBRE FROM MNTGE.GEDEP_ENCARGADA";
                            $cadena_sql .= "  WHERE GDE_NOMBRE='" . $variable[$i][$nom_campo] . "') " . $nom_campo;
                        } else {
                            if ($i == 0) {
                                $cadena_sql.=" " . $nom_campo . " " . $nom_campo;
                            } else {
                                $cadena_sql.=", " . $nom_campo . " " . $nom_campo;
                            }
                        }
                        if ($nom_campo == 'SAL_GDE_COD') {
                            $cadena_sql2 .= " AND " . $nom_campo . "=";
                            //$cadena_sql2.= " (SELECT GDE_COD FROM GEDEP_ENCARGADA";
                            $cadena_sql2.= " (SELECT GDE_COD FROM MNTGE.GEDEP_ENCARGADA";
                            $cadena_sql2 .= "  WHERE GDE_NOMBRE='" . $variable[$i][$nom_campo] . "')";
                        } else if ($nom_campo == 'SAL_ID_FAC') {
                            $cadena_sql2 .= " AND " . $nom_campo . "=";
                            $cadena_sql2.= " (select dep_id_fac from gedep where dep_nombre='" . $variable[$i][$nom_campo] . "')";
                        } else if ($nom_campo == 'SAL_SED_ID') {
                            $cadena_sql2 .= " AND " . $nom_campo . "=";
                            $cadena_sql2.= " (select sed_id from gesede where sed_nombre='" . $variable[$i][$nom_campo] . "')";
                        } else if ($nom_campo == 'SAL_EDIFICIO') {
                            $cadena_sql2 .= " AND " . $nom_campo . "=";
                            $cadena_sql2.= " (select edi_cod from geedificio where edi_nombre='" . $variable[$i][$nom_campo] . "') ";
                        } else {
                            if ($i == 0) {
                                $cadena_sql2.= " " . $nom_campo . "='" . $variable[$i][$nom_campo] . "'";
                            } else {
                                $cadena_sql2.= " AND " . $nom_campo . "='" . $variable[$i][$nom_campo] . "'";
                            }
                        }
                    }
                }

                $cadena_sql = $cadena_sql . " FROM gesalon_2012 " . $cadena_sql2;

                break;

            case 'modificarFacultad':

                $cadena_sql = "UPDATE ";
                $cadena_sql.= "gedep ";
                $cadena_sql.= "SET ";

                $cadena_sql2 .= "WHERE ";

                $cant_campos = count($campos);
                $flag = 0;

                for ($i = 0; $i < $cant_campos; $i++) {

                    $no_anadir = 0;
                    $nom_campo = $campos[$i]['NOM_BD'];
                    $valor_set = $datos_aux[$nom_campo];
                    $valor_where = $variable[$i][$nom_campo];

                    if ($valor_where == -1) {
                        $no_anadir = 1;
                    }

                    if ($no_anadir == 0) {

                        if ($i == 0) {
                            $cadena_sql2 .= $nom_campo . "=";
                            $cadena_sql2 .= "'" . utf8_decode($valor_where) . "' ";
                        } else {
                            $cadena_sql2 .= "AND " . $nom_campo . "=";
                            $cadena_sql2 .= "'" . utf8_decode($valor_where) . "' ";
                        }
                    }
                    if ($nom_campo == 'DEP_SED_COD') {
                        if ($valor_set && $flag == 0) {
                            $flag = 1;
                            $cadena_sql.= $nom_campo . "=";
                            $cadena_sql.= "'" . utf8_decode($valor_set) . "' ";
                        } else if ($valor_set && $flag > 0) {
                            $cadena_sql.= ", " . $nom_campo . "=";
                            $cadena_sql.= "(select sed_cod from gesede where sed_id='" . utf8_decode($valor_set) . "') ";  
                        }
                    } else {
                        if ($valor_set && $flag == 0) {
                            $flag = 1;
                            $cadena_sql.= $nom_campo . "=";
                            $cadena_sql.= "'" . utf8_decode($valor_set) . "' ";
                        } else if ($valor_set && $flag > 0) {
                            $cadena_sql.= ", " . $nom_campo . "=";
                            $cadena_sql.= "'" . utf8_decode($valor_set) . "' ";
                        }
                    }
                }
                $cadena_sql = $cadena_sql . $cadena_sql2;                
                break;

            case 'modificarSede':

                $cadena_sql = "UPDATE ";
                $cadena_sql.= "gesede ";
                $cadena_sql.= "SET ";

                $cadena_sql2 .= "WHERE ";

                $cant_campos = count($campos);
                $flag = 0;

                for ($i = 0; $i < $cant_campos; $i++) {

                    $no_anadir = 0;
                    $nom_campo = $campos[$i]['NOM_BD'];
                    $valor_set = $datos_aux[$nom_campo];
                    $valor_where = $variable[$i][$nom_campo];

                    if ($valor_where == -1) {
                        $no_anadir = 1;
                    }

                    if ($no_anadir == 0) {
                        if ($i == 0) {
                            $cadena_sql2 .= $nom_campo . "=";
                            $cadena_sql2 .= "'" . utf8_decode($valor_where) . "' ";
                        } else {

                            $cadena_sql2 .= "AND " . $nom_campo . "=";
                            $cadena_sql2 .= "'" . utf8_decode($valor_where) . "' ";
                        }
                    }

                    if ($valor_set && $flag == 0) {
                        $flag = 1;
                        $cadena_sql.= $nom_campo . "=";
                        $cadena_sql.= "'" . utf8_decode($valor_set) . "' ";
                    } else if ($valor_set && $flag > 0) {
                        $cadena_sql.= ", " . $nom_campo . "=";
                        $cadena_sql.= "'" . utf8_decode($valor_set) . "' ";
                    }
                }

                $cadena_sql = $cadena_sql . $cadena_sql2;
                break;

            case 'modificarEdificio':

                $cadena_sql = "UPDATE ";
                $cadena_sql.= "geedificio ";
                $cadena_sql.= "SET ";

                $cadena_sql2 .= "WHERE ";

                $cant_campos = count($campos);
                $flag = 0;

                for ($i = 0; $i < $cant_campos; $i++) {

                    $no_anadir = 0;
                    $nom_campo = $campos[$i]['NOM_BD'];
                    $valor_set = $datos_aux[$nom_campo];
                    $valor_where = $variable[$i][$nom_campo];

                    if ($valor_where == -1) {
                        $no_anadir = 1;
                    }

                    if ($no_anadir == 0) {
                        if ($i == 0) {
                            $cadena_sql2 .= $nom_campo . "=";
                            $cadena_sql2 .= "'" . utf8_decode($valor_where) . "' ";
                        } else {

                            $cadena_sql2 .= "AND " . $nom_campo . "=";
                            $cadena_sql2 .= "'" . utf8_decode($valor_where) . "' ";
                        }
                    }

                    if ($valor_set && $flag == 0) {
                        $flag = 1;
                        $cadena_sql.= $nom_campo . "=";
                        $cadena_sql.= "'" . utf8_decode($valor_set) . "' ";
                    } else if ($valor_set && $flag > 0) {
                        $cadena_sql.= ", " . $nom_campo . "=";
                        $cadena_sql.= "'" . utf8_decode($valor_set) . "' ";
                    }
                }

                $cadena_sql = $cadena_sql . $cadena_sql2;
                break;

            case 'modificarEFA':

                $cadena_sql = "UPDATE ";
                $cadena_sql.= "gesalon_2012 ";
                $cadena_sql.= "SET ";

                $cadena_sql2 .= "WHERE ";

                $cant_campos = count($campos);
                $flag = 0;

                for ($i = 0; $i < $cant_campos; $i++) {

                    $no_anadir = 0;
                    $nom_campo = $campos[$i]['NOM_BD'];
                    $valor_set = $datos_aux[$nom_campo];
                    $valor_where = $variable[$i][$nom_campo];

                    if ($valor_where == -1) {
                        $no_anadir = 1;
                    }if ($nom_campo == 'SAL_AREA') {
                        $valor_where = str_replace(".", ",", $valor_where);
                        $valor_set = str_replace(".", ",", $valor_set);
                    }

                    if ($no_anadir == 0 && $campos[$i]['DIS'] == '1') {
                        if ($nom_campo == 'SAL_GDE_COD') {
                            if ($i == 0) {
                                $cadena_sql2 .= $nom_campo . "=";
                                //$cadena_sql2.= " (SELECT GDE_COD FROM GEDEP_ENCARGADA";
                                $cadena_sql2.= " (SELECT GDE_COD FROM MNTGE.GEDEP_ENCARGADA";
                                $cadena_sql2 .= "  WHERE GDE_NOMBRE='" . utf8_decode($valor_where) . "') ";
                            } else {

                                $cadena_sql2 .= "AND " . $nom_campo . "=";
                                //$cadena_sql2.= " (SELECT GDE_COD FROM GEDEP_ENCARGADA";
                                $cadena_sql2.= " (SELECT GDE_COD FROM MNTGE.GEDEP_ENCARGADA";
                                $cadena_sql2 .= "  WHERE GDE_NOMBRE='" . utf8_decode($valor_where) . "') ";
                            }
                        } else {
                            if ($i == 0) {
                                $cadena_sql2 .= $nom_campo . "=";
                                $cadena_sql2 .= "'" . utf8_decode($valor_where) . "' ";
                            } else {

                                $cadena_sql2 .= "AND " . $nom_campo . "=";
                                $cadena_sql2 .= "'" . utf8_decode($valor_where) . "' ";
                            }
                        }
                    }

                    if ($valor_set && $flag == 0) {
                        $flag = 1;
                        $cadena_sql.= $nom_campo . "=";
                        $cadena_sql.= "'" . utf8_decode($valor_set) . "' ";
                    } else if ($valor_set && $flag > 0) {
                        $cadena_sql.= ", " . $nom_campo . "=";
                        $cadena_sql.= "'" . utf8_decode($valor_set) . "' ";
                    }
                }

                $cadena_sql = $cadena_sql . $cadena_sql2;
                //echo "<br> cadena sql ".$cadena_sql;exit;
                break;

            case 'existeInfoFacultad':

                $cadena_sql = " SELECT";
                $cadena_sql.= " gedep";
                $cadena_sql.= " (";

                $cadena_sql2.= " VALUES (";

                $cant_campos = count($campos);

                for ($i = 0; $i < $cant_campos; $i++) {

                    $nom_campo = $campos[$i]['NOM_BD'];
                    $valor = $variable[$nom_campo];

                    if ($i == 0) {
                        $cadena_sql.= $nom_campo;
                        $cadena_sql2.= "'" . utf8_decode($valor) . "'";
                    } else {
                        $cadena_sql.= ", " . $nom_campo;
                        $cadena_sql2.= ", '" . utf8_decode($valor) . "'";
                    }
                }

                $cadena_sql.=")";
                $cadena_sql2.=")";
                $cadena_sql = $cadena_sql . $cadena_sql2;
                break;

            case 'consultarEFA':

                $cadena_sql = " SELECT";
                $cadena_sql.=" sal_id_espacio,";
                $cadena_sql.=" sal_nombre";
                $cadena_sql.=" FROM";
                $cadena_sql.=" gesalon_2012";
                $cadena_sql.=" WHERE sal_nombre LIKE UPPER('%" . $variable . "%')";
                $cadena_sql.=" OR sal_id_espacio LIKE UPPER('%" . $variable . "%')";
                $cadena_sql.=" AND sal_estado='A'";
                break;

            case 'consultarTipoDE':

                $cadena_sql = " SELECT";
                $cadena_sql.=" gde_tipo COD,";
                $cadena_sql.=" gde_tipo NOM";
                $cadena_sql.=" FROM";
                //$cadena_sql.=" gedep_encargada";
                $cadena_sql.=" mntge.gedep_encargada";
                $cadena_sql.=" WHERE gde_nombre LIKE UPPER('%" . $variable . "%')";
                break;

            case 'eliminarEspacio':

                $cantCampos = count($campos);
                $cantDatos = count($variable);
                $flag = 0;

                $cadena_sql = " UPDATE";
                if ($datos_aux == '1') {
                    $cadena_sql .= " gedep SET DEP_ESTADO='I'";
                }if ($datos_aux == '2') {
                    $cadena_sql .= " gesede SET SED_ESTADO='I'";
                }if ($datos_aux == '3') {
                    $cadena_sql .= " geedificio SET EDI_ESTADO='I'";
                }if ($datos_aux == '4') {
                    $cadena_sql .= " gesalon_2012 SET SAL_ESTADO='I'";
                }

                $cadena_sql.=" WHERE";

                for ($i = 0; $i < $cantCampos; $i++) {

                    $nomAtributo = $campos[$i]['NOM_BD'];

                    for ($j = 0; $j < $cantDatos; $j++) {

                        $atributoVar = $variable[$j][0];
                        $dato = $variable[$j][1];

                        if ($atributoVar == $nomAtributo) {

                            if ($nomAtributo != 'SAL_GDE_COD' && $campos[$i]['DIS'] == '1') {
                                if ($flag == 0) {
                                    $cadena_sql.=" " . $atributoVar . "='" . utf8_encode($dato) . "'";
                                    $flag++;
                                } else {
                                    $cadena_sql.=" AND " . $atributoVar . "='" . utf8_encode($dato) . "'";
                                }
                            }
                        }
                    }
                }
                
                //echo "<br> cadena sql ".$cadena_sql;exit;

                break;

            case 'listarEliminados':

                $cantCampos = count($campos);

                $cadena_sql = " SELECT";

                for ($i = 0; $i < $cantCampos; $i++) {
                    $nomAtributo = $campos[$i]['NOM_BD'];
                    if ($i == 0) {
                        $cadena_sql .= " " . $nomAtributo;
                    } else {
                        $cadena_sql .= ", " . $nomAtributo;
                    }
                }

                $cadena_sql.= " FROM";
                if ($datos_aux == '1') {
                    $cadena_sql .= " gedep";
                }if ($datos_aux == '2') {
                    $cadena_sql .= " gesede";
                }if ($datos_aux == '3') {
                    $cadena_sql .= " geedificio";
                }if ($datos_aux == '4') {
                    $cadena_sql .= " gesalon_2012";
                }

                $cadena_sql.=" WHERE";

                if ($datos_aux == '1') {
                    $cadena_sql .= " DEP_ESTADO='I'";
                }if ($datos_aux == '2') {
                    $cadena_sql .= " SED_ESTADO='I'";
                }if ($datos_aux == '3') {
                    $cadena_sql .= " EDI_ESTADO='I'";
                }if ($datos_aux == '4') {
                    $cadena_sql .= " SAL_ESTADO='I'";
                }
                break;

            case 'recuperarEspacio':

                $cantCampos = count($campos);
                $cantDatos = count($variable);
                $flag = 0;

                $cadena_sql = " UPDATE";
                if ($datos_aux == '1') {
                    $cadena_sql .= " gedep SET DEP_ESTADO='A'";
                }if ($datos_aux == '2') {
                    $cadena_sql .= " gesede SET SED_ESTADO='A'";
                }if ($datos_aux == '3') {
                    $cadena_sql .= " geedificio SET EDI_ESTADO='A'";
                }if ($datos_aux == '4') {
                    $cadena_sql .= " gesalon_2012 SET SAL_ESTADO='A'";
                }

                $cadena_sql.=" WHERE";

                for ($i = 0; $i < $cantCampos; $i++) {

                    $nomAtributo = $campos[$i]['NOM_BD'];

                    for ($j = 0; $j < $cantDatos; $j++) {

                        $atributoVar = $variable[$j][0];
                        $dato = $variable[$j][1];

                        if ($atributoVar == $nomAtributo) {

                            if ($nomAtributo != 'SAL_GDE_COD') {
                                if ($flag == 0) {
                                    $cadena_sql.=" " . $atributoVar . "='" . $dato . "'";
                                } else {
                                    $cadena_sql.=" AND " . $atributoVar . "='" . $dato . "'";
                                }
                            }
                            $flag++;
                        }
                    }
                }

                break;
        }
        return $cadena_sql;
    }

}
?>
