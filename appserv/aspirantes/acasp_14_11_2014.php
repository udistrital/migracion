<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect . 'valida_pag.php');
require_once(dir_general . 'msql_ano_per.php');
require_once('../calendario/calendario.php');
require_once(dir_general . 'valida_usuario_prog.php');
require_once(dir_general . 'valida_http_referer.php');
require_once(dir_general . 'valida_formulario_formulario.php');
require_once(dir_general . 'valida_inscripcion.php');
require_once(dir_conect . 'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
//include_once(dir_general . 'ValidaAcasp.js');

$esta_configuracion = new config();
$configuracion = $esta_configuracion->variable("../");

$conexion = new multiConexion();
$accesoOracle = $conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(50);

global $raiz;
$form = "acasp";
$item = "FechaNac";
?>
<html>
    <script language="javascript" src="../generales_asp/datepicker/js/datepicker.js"></script>
    <link href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel = "stylesheet" type = "text/css"/>
    <script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <link type="text/css" href="http://jqueryui.com/latest/themes/base/ui.all.css" rel="stylesheet" />
    <script type="text/javascript" src="http://jqueryui.com/latest/jquery-1.3.2.js"></script>
    <script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.core.js"></script>
    <script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.datepicker.js"></script>
    <head>
        <title>Aspirantes</title>
        <link href="../script/estilo.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="../generales_asp/ValidaCampoFecha.js"></script>
        <script language="javascript" src="../generales_asp/ValidaAcasp.js"></script>

        <script language="JavaScript" src="../calendario/javascripts.js"></script>
        <script language="JavaScript" src="Logout.js"></script>
        <script language="JavaScript" src="../script/BorraLink.js"></script>
        <script language="JavaScript" src="../script/SoloNumero.js"></script>
        <script language="JavaScript" src="../generales_asp/CuentaCaracteres.js"></script>
        <script language="JavaScript" src="../generales_asp/Logout.js"></script>


        <script type="text/javascript">
            $(document).ready(function() {
                $("#FechaNac").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1960:c',
                    dateFormat: 'dd/mm/yy'
                });
            });
        </script>


        <script language="javascript">
            <!--
            var WinOpen = 0;
            function ListaValores(pag, R, S, D, H, an, al, iz, ar) {
                if (WinOpen) {
                    if (!WinOpen.closed)
                        WinOpen.close();
                }
                WinOpen = window.open(pag + '?httpR=' + R + '&httpS=' + S + '&httpD=' + D + '&httpH=' + H, "Lov", "width=" + an + ",height=" + al + ",scrollbars=YES,left=" + iz + ",top=" + ar);
            }
//-->
        </script>

        <script type="text/javascript">
            function visible(valor)
            {
                if (valor == "375" || valor == "383" || valor == "377" || valor == "373" || valor == "678" || valor == "579" || valor == "372")
                {
                    //document.getElementById("TextArea1").style.visibility = "visible"
                    //document.getElementById("TextArea1").value = valor ;
                    document.getElementById("Enunciado").innerHTML = "Digite el n&uacute;mero de acta de Grado del T&iacute;tulo de Tecn&oacute;logo del cual es egresado"
                    document.getElementById("TextArea_Div").innerHTML = "<input name='acta' type='text' id='acta' size='50' maxlength='50'>"
                }
                else
                {
                    document.getElementById("Enunciado").innerHTML = ""
                    document.getElementById("TextArea_Div").innerHTML = "<input name='acta' type='hidden' id='acta' value='000'>"
                }
            }
        </script>
        <SCRIPT LANGUAGE="JavaScript">
            <!--
                //Disable right click script III- By Renigade (renigade@mediaone.net)
            //For full source code, visit http://www.dynamicdrive.com
            var message = "El click derecho ha sido disabilitado";
            ///////////////////////////////////
            function clickIE() {
                if (document.all) {
                    (message);
                    return false;
                }
            }
            function clickNS(e) {
                if
                        (document.layers || (document.getElementById && !document.all)) {
                    if (e.which == 2 || e.which == 3) {
                        (message);
                        return false;
                    }
                }
            }
            if (document.layers)
            {
                document.captureEvents(Event.MOUSEDOWN);
                document.onmousedown = clickNS;
            }
            else {
                    document.onmouseup = clickNS;
                    document.oncontextmenu = clickIE;
                }
                document.oncontextmenu = new Function("return false")
                        // -->
        </SCRIPT>


        <script>
            function verifica() {
                        var str = document.forms["acasp"].elements["NroIcfes"].value;
                var str_carrera = parseInt(document.forms["acasp"].elements["Select1"].value);


                if ((str_carrera !== 373)) {
                    if ((str_carrera !== 678)) {
                        if ((str_carrera !== 579)) {
                            if ((str_carrera !== 383)) {
                                if ((str_carrera !== 372)) {
                                    if ((str_carrera !== 375)) {
                                        if ((str_carrera !== 377)) {
                                            var res = str.substring(0, 4);
                                            if (res < 2010) {
                                                alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2010");
                                                document.forms["acasp"].elements["NroIcfes"].focus();
                                                document.forms["acasp"].elements["NroIcfes"].value = '';
                                                return false;
                                            }
                                        } else {
                                            var res2 = str.substring(0, 2);
                                            if (68 <= res2 && res2 <= 99) {
                                                alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                                                document.forms["acasp"].elements["NroIcfes"].focus();
                                                document.forms["acasp"].elements["NroIcfes"].value = '';
                                                return false;
                                            }

                                            var res2 = str.substring(0, 4);
                                            if (1968 <= res2 && res2 <= 1999) {
                                                alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                                                document.forms["acasp"].elements["NroIcfes"].focus();
                                                document.forms["acasp"].elements["NroIcfes"].value = '';
                                                return false;
                                            }
                                        }
                                          return true;
                                    } else {
                                      
                                        var res2 = str.substring(0, 2);
                                        if (68 <= res2 && res2 <= 99) {
                                            alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                                            document.forms["acasp"].elements["NroIcfes"].focus();
                                            document.forms["acasp"].elements["NroIcfes"].value = '';
                                            return false;
                                        }

                                        var res2 = str.substring(0, 4);
                                        if (1968 <= res2 && res2 <= 1999) {
                                            alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                                            document.forms["acasp"].elements["NroIcfes"].focus();
                                            document.forms["acasp"].elements["NroIcfes"].value = '';
                                            return false;
                                        }
                                    }
                                    return true;
                                } else {
                                    var res2 = str.substring(0, 2);
                                    if (68 <= res2 && res2 <= 99) {
                                        alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                                        document.forms["acasp"].elements["NroIcfes"].focus();
                                        document.forms["acasp"].elements["NroIcfes"].value = '';
                                        return false;
                                    }

                                    var res2 = str.substring(0, 4);
                                    if (1968 <= res2 && res2 <= 1999) {
                                        alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                                        document.forms["acasp"].elements["NroIcfes"].focus();
                                        document.forms["acasp"].elements["NroIcfes"].value = '';
                                        return false;
                                    }
                                }
                                return true;
                            } else {
                                var res2 = str.substring(0, 2);
                                if (68 <= res2 && res2 <= 99) {
                                    alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                                    document.forms["acasp"].elements["NroIcfes"].focus();
                                    document.forms["acasp"].elements["NroIcfes"].value = '';
                                    return false;
                                }

                                var res2 = str.substring(0, 4);
                                if (1968 <= res2 && res2 <= 1999) {
                                    alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                                    document.forms["acasp"].elements["NroIcfes"].focus();
                                    document.forms["acasp"].elements["NroIcfes"].value = '';
                                    return false;
                                }
                            }
                            return true;
                        } else {
                            var res2 = str.substring(0, 2);
                            if (68 <= res2 && res2 <= 99) {
                                alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                                document.forms["acasp"].elements["NroIcfes"].focus();
                                document.forms["acasp"].elements["NroIcfes"].value = '';
                                return false;
                            }

                            var res2 = str.substring(0, 4);
                            if (1968 <= res2 && res2 <= 1999) {
                                alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                                document.forms["acasp"].elements["NroIcfes"].focus();
                                document.forms["acasp"].elements["NroIcfes"].value = '';
                                return false;
                            }
                        }
                        return true;
                    } else {
                        var res2 = str.substring(0, 2);
                        if (68 <= res2 && res2 <= 99) {
                            alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                            document.forms["acasp"].elements["NroIcfes"].focus();
                            document.forms["acasp"].elements["NroIcfes"].value = '';
                            return false;
                        }

                        var res2 = str.substring(0, 4);
                        if (1968 <= res2 && res2 <= 1999) {
                            alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                            document.forms["acasp"].elements["NroIcfes"].focus();
                            document.forms["acasp"].elements["NroIcfes"].value = '';
                            return false;
                        }
                    }
                    return true;
                } else {
                    var res2 = str.substring(0, 2);
                    if (68 <= res2 && res2 <= 99) {
                        alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                        document.forms["acasp"].elements["NroIcfes"].focus();
                        document.forms["acasp"].elements["NroIcfes"].value = '';
                        return false;
                    }

                    var res2 = str.substring(0, 4);
                    if (1968 <= res2 && res2 <= 1999) {
                        alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 2000");
                        document.forms["acasp"].elements["NroIcfes"].focus();
                        document.forms["acasp"].elements["NroIcfes"].value = '';
                        return false;
                    }
                }
                return true;
            }

        </script>

        <style type="text/css">
            <!--
            .Estilo18 {
                font-size: 11px;
                color: #FF0000;
            }
            .Estilo19 {
                color: #FF0000}
            -->

            .conFondo {
                background-color: #EBEBEB;
                font-family: sans-serif,Helvetica,Arial;  
                color: #0040ED; 
                font-size: 14px; 
                font-weight: bold
            }

            /* #### bootstrap Form #### */
            .bootstrap-frm {
                width: 900px;
                margin-right: auto;
                margin-left: auto;
                background: #FFF;
                padding: 20px 30px 20px 30px;
                font: 11px "Helvetica Neue", Helvetica, Arial, sans-serif;
                color: #888;
                text-shadow: 1px 1px 1px #FFF;
                border:1px solid #DDD;
                border-radius: 5px;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
            }
            .bootstrap-frm h1 {
                font: 25px "Helvetica Neue", Helvetica, Arial, sans-serif;
                padding: 0px 0px 10px 40px;
                display: block;
                border-bottom: 1px solid #DADADA;
                margin: -10px -30px 30px -30px;
                color: #888;
            }
            .bootstrap-frm h1>span {
                display: block;
                font-size: 11px;
            }
            .bootstrap-frm label {
                display: block;
                margin: 0px 0px 5px;
            }
            .bootstrap-frm label>span {
                float: left;
                width: 80px;
                text-align: right;
                padding-right: 10px;
                margin-top: 10px;
                color: #333;
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                font-weight: bold;
            }
            .bootstrap-frm input[type="text"], .bootstrap-frm input[type="email"], .bootstrap-frm textarea, .bootstrap-frm select{
                border: 1px solid #CCC;
                color: #888;
                height: 20px;
                margin-bottom: 2px;
                margin-right: 6px;
                margin-top: 2px;
                margin-left:4px;
                outline: 0 none;
                width: 68%;
                border-radius: 4px;
                -webkit-border-radius: 4px;
                -moz-border-radius: 4px;
                font: normal 13px/13px "Helvetica Neue", Helvetica, Arial, sans-serif;
                -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            }
            .bootstrap-frm select {
                background: #FFF url('down-arrow.png') no-repeat right;
                background: #FFF url('down-arrow.png') no-repeat right;
                appearance:none;
                -webkit-appearance:none; 
                -moz-appearance: none;
                text-indent: 0.01px;
                text-overflow: '';
                width: 72%;
                height: 25px;
            }
            .bootstrap-frm textarea{
                height:100px;
            }

            .bootstrap-frm .button {
                background: #FFF;
                border: 1px solid #CCC;
                padding: 10px 25px 10px 25px;
                color: #333;
                border-radius: 4px;
            }
            .bootstrap-frm .button:hover {
                color: #333;
                background-color: #EBEBEB;
                border-color: #ADADAD;
            }
        </style>
    </head>

    <body onload="visible(document.getElementById('Select1').value)">
        <?
        //require_once(dir_general.'cabezote.php'); 
        require_once(dir_general . 'msql_querys.php');

        require_once(dir_script . 'mensaje_error.inc.php');
        if (isset($_REQUEST['error_login'])) {
        $error = $_REQUEST['error_login'];
        print $err = "<center><a OnMouseOver='history.go(-1)'><img src='../img/asterisco.gif'>$error_login_ms[$error]</a></center>";
        }
        ?>
        <p align="center" class="Estilo6">FORMULARIO DE INSCRIPCI&Oacute;N PARA INGRESO<br><? print $periodo; ?></p>

        <form name="acasp" onsubmit="return verifica();" method="post" action="asp_verifica_acasp.php" class="bootstrap-frm">
            <table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
                <tr>
                    <td>
                        <fieldset style="padding:10">
                            <br>
                            <? //require_once('ins.php');  ?>
                            <table width="99%" border="1" align="center" cellpadding="0" cellspacing="2" style="border-collapse:collapse">
                                <tr>
                                    <td colspan="6" align="center" class="conFondo">UNIVERSIDAD Y PROGRAMA</td>
                                </tr>
                                <tr>
                                    <td width="150" align="right"><span class="Estilo18">*</span>¿Por qué medio se enter&oacute; de la Universidad Distrital?: </td>
                                    <td colspan="5">
                                        <?php
                                        print'<select size="1" name="MedPub">
						<option value="0" selected>Seleccione el medio de publicidad</option>';
                                        $i = 0;
                                        while (isset($RowMed[$i][0])) {
                                            echo'<option value="' . $RowMed[$i][0] . '">' . $RowMed[$i][1] . '</option>\n';
                                            $i++;
                                        }
                                        print'</select>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="150" align="right"><span class="Estilo18">*</span>Se presenta a la Universidad por:
                                    </td>
                                    <td colspan="5">
                                        <select name="SePresentaPor" id="SePresentaPor">
                                            <option value="1" selected>Primera vez</option>
                                            <option value="2">Segunda vez</option>
                                            <option value="3">Tercera o m&aacute;s veces</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="150" align="right"><span class="Estilo18">*</span>Carrera en la que se inscribe:</td>
                                    <td colspan="5">
                                        <?php
                                        $mensaje = "<font color='red'>(Esta carrera requiere t&iacute;tulo de Tecn&oacute;logo.)</font>";
                                        echo '<select size="1" name="CraCod" id="Select1" onchange="visible(this.value)">
						<option value="0" selected>Seleccione el Proyecto Curricular</option>';
//echo '<option value="185">185 Administración Ambiental</option>\n';
//No olvidar habilitar esto para las admisiones de 2011-3
                                        $i = 0;

                                        while (isset($RowCra[$i][0])) {
                                            if ($RowCra[$i][0] == 373 || $RowCra[$i][0] == 678 || $RowCra[$i][0] == 579 || $RowCra[$i][0] == 383 || $RowCra[$i][0] == 372 || $RowCra[$i][0] == 375 || $RowCra[$i][0] == 377) {
                                                //if($RowCra[$i][0] == 1)
                                                $mensaje = "<font color='red'>(Esta carrera requiere t&iacute;tulo de Tecn&oacute;logo.)</font>";
                                                $mensaje1 = "<font color='red'>(CICLOS PROPED&Eacute;UTICOS)</font>";
                                                echo'<option value="' . $RowCra[$i][0] . '">' . $RowCra[$i][0] . ' - ' . $RowCra[$i][1] . '</option>\n';
                                            } else {
                                                echo'<option value="' . $RowCra[$i][0] . '">' . $RowCra[$i][0] . ' - ' . $RowCra[$i][1] . '</option>\n';
                                            }
                                            $i++;
                                        }

                                        echo '</select>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="150" align="right"><div id="Enunciado"></div>
                                    </td>
                                    <td colspan="5"><div id="TextArea_Div" ></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="150" align="right"><span class="Estilo18">*</span>Tipo de inscripci&oacute;n:</td>
                                    <td colspan="5"> 
                                        <?php
                                        echo '<select size="1" name="TipoIns" id="TipoIns">
						<option value="1" selected>Seleccione el Tipo de Inscripci&oacute;n</option>';

                                        $QryTipoIns = "SELECT ti_cod, ti_nombre
						FROM actipins
						WHERE ti_estado = 'A'
						AND ti_cod NOT IN(25,26,20)
						ORDER BY ti_cod ASC";

                                        $RowTipoIns = $conexion->ejecutarSQL($configuracion, $accesoOracle, $QryTipoIns, "busqueda");


//No olvidar habilitar esto para las admisiones de 2011-3
                                        $i = 0;
                                        while (isset($RowTipoIns[$i][0])) {
                                            echo '<option value="' . $RowTipoIns[$i][0] . '">' . $RowTipoIns[$i][0] . ' - ' . $RowTipoIns[$i][1] . '</option>\n';

                                            $i++;
                                        }

                                        echo '</select>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" align="justify" class="Estilo10">Si selecciona un <b>Tipo de inscripci&oacute;n</b> diferente de normal, debe presentar los soportes en el sitio y la fecha indicadas, (ver instructivo de inscripciones especiales).</td>
                                </tr>
                                <tr>
                                    <td colspan="6" align="center" class="conFondo">LUGAR Y FECHA DE NACIMIENTO</td>
                                </tr>
                                <tr>
                                    <td width="20" align="right"><span class="Estilo18">*</span>Pa&iacute;s:</td>
                                    <td width="130">
                                        <select name="PaisNac" id="PaisNac">
                                            <option value="COLOMBIA" selected>COLOMBIA</option>
                                            <option value="EXTRANJERO">EXTRANJERO</option>
                                        </select>	  </td>
                                    <td width="20"><div align="right"><span class="Estilo18">*</span>Departamento: </div></td>
                                    <td width="130"><? print'<input name="DptoNac" type="text" readonly="readonly" id="DptoNac" value="" size="12" onClick="ListaValores(\'lov_departamento.php\', this.name, DptoNac.value, \'NomDep\', DptoNac.value, 340, 200, 650, 390)" title="Haga clic para ver la lista de valores" onKeypress="return SoloNumero(event)">'; ?></td>
                                    <td width="20"><div align="right"><span class="Estilo18">*</span>Municipio:</div></td>
                                    <td width="130"><?
                                        print''
                                        . '<input name="CiudadNac" type="text" readonly="readonly" id="CiudadNac" value="" size="12" onClick="ListaValores(\'lov_municipio.php\', this.name, DptoNac.value, DptoNac.value, DptoNac.value, 340, 200, 650, 390)" title="Haga clic para ver la lista de valores" onKeypress="return SoloNumero(event)">';
                                        ?></td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Fecha de nacimiento:</td>
                                    <td colspan="5">
                                        <? print'<input style="width:100px" placeholder="DD/MM/AAAA" name="FechaNac" type="text" id="FechaNac" maxlength="10" size="12" onBlur="ValidaCampoFecha();">'; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Sexo:</td>
                                    <td colspan="5">        
                                        <select name="Sexo" id="Sexo" style="width:100px">
                                            <option value="M" selected>Masculino</option>
                                            <option value="F">Femenino</option>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Estado Civil:</td>
                                    <td colspan="5">        
                                        <select name="EstCivil" id="EstCivil">
                                            <option value="1" selected>Soltero</option>
                                            <option value="2">Casado</option>
                                            <option value="3">Otro</option>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Direcci&oacute;n:</td>
                                    <td colspan="5" align="left"><input name="dir" type="text" id="dir" size="60" onChange="javascript:this.value = this.value.toUpperCase();" maxlength="50"></td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Localidad de residencia: </td>
                                    <td colspan="5" align="left">
                                        <?php
                                        print'<select size="1" name="LocRes">
						<option value="99" selected>Seleccione la localidad de residencia</option>';
                                        $i = 0;
                                        while (isset($RowLoc[$i][0])) {
                                            echo'<option value="' . $RowLoc[$i][0] . '">' . $RowLoc[$i][1] . '</option>\n';
                                            $i++;
                                        }
                                        print'</select>';
                                        ?>
                                        <span class="Estilo18"><br><br>Nota: Se&ntilde;or aspirante, si su residencia es fuera de Bogot&aacute;, deber&aacute;  marcar la opci&oacute;n "Fuera de Bogot&aacute;" <br><br></span>	
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Estrato de residencia:</td>
                                    <td colspan="5" align="left">
                                        <?php
                                        print'<select size="1" name="StrRes">
						<option value="99" selected>Seleccione el estrato de la residencia</option>';
                                        $i = 0;
                                        while (isset($RowEstrato[$i][0])) {
                                            echo'<option value="' . $RowEstrato[$i][0] . '">' . $RowEstrato[$i][1] . '</option>\n';
                                            $i++;
                                        }
                                        print'</select>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Estrato socioecon&oacute;mico de quien costear&aacute; los estudios:</td>
                                    <td colspan="5" align="left">
                                        <?php
                                        print'<select size="1" name="StrResCost">
						<option value="99" selected>Seleccione el estrato de la residencia</option>';
                                        $i = 0;
                                        while (isset($RowEstrato[$i][0])) {
                                            echo'<option value="' . $RowEstrato[$i][0] . '">' . $RowEstrato[$i][1] . '</option>\n';
                                            $i++;
                                        }
                                        print'</select>';
                                        ?>
                                    </td>
                                </tr>  
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Tel&eacute;fono:</td>
                                    <td colspan="5" align="left"><input name="tel" type="text" maxlength="12" onkeyup="this.value = this.value.slice(0, 12)" id="tel" size="15"></td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Correo electr&oacute;nico: </td>
                                    <td colspan="5" align="left"><input name="CtaCorreo" placeholder="correo@ejemplo.com" type="text" id="CtaCorreo" size="60" onChange="javascript:this.value = this.value.toLowerCase();" maxlength="50"></td>
                                </tr>
                                <tr>
                                    <td width="100" colspan="6" align="center" class="conFondo">DOCUMENTO DE IDENTIDAD</td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Documento actual: </td>
                                    <td colspan="3" align="left"><input name="DocActual" type="text" id="DocActual" maxlength="14" onkeyup="this.value = this.value.slice(0, 14)" size="15" onKeypress="return SoloNumero(event)"></td>
                                    <td width="100" align="left"><div align="right">Tipo:</div></td>
                                    <td align="left">
                                        <div align="left">
                                            <select name="TipDocAct" id="TipDocAct" style="width:50px">
                                                <option value="1" selected>C.C.</option>
                                                <option value="2">T.I.</option>
                                                <option value="3">C.E.</option>
                                            </select>
                                        </div></td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Documento de identidad con el que present&oacute; el ex&aacute;men de estado ICFES o SABER 11: </td>
                                    <td colspan="3" align="left">
                                        <input name="DocIcfes" type="text" id="DocIcfes" size="15" maxlength="14" onkeyup="this.value = this.value.slice(0, 14)" onKeypress="return SoloNumero(event)"></td>
                                    <td width="100" align="left"><div align="right">Tipo:</div></td>
                                    <td align="left">
                                        <div align="left">
                                            <select name="TipDocIcfes" id="TipDocIcfes" style="width:50px">
                                                <option value="1" selected>C.C.</option>
                                                <option value="2">T.I.</option>
                                                <option value="3">C.E.</option>
                                            </select>
                                        </div></td>
                                </tr>
                                <tr>
                                    <td colspan="6" align="center" class="conFondo">REGISTRO ICFES</td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>N&uacute;mero del registro del icfes (SNP): </td>
                                    <td colspan="5" align="left">
                                        <select name="TipoIcfes" id="TipoIcfes" style="width:50px" >
                                            <option value="AC" selected>AC</option>
                                            <option value="VG">VG</option>
                                        </select>
                                        <input name="NroIcfes" type="text" id="NroIcfes" size="15" onKeypress="return SoloNumero(event)" oncopy="return false;" onpaste="return false;" onchange="return verifica()" oncopy="return false" onpaste="return false" maxlength="12"> 
                                        <span class="Estilo18"><br><br>Nota: Este dato corresponde a los 12 n&uacute;meros en La cuadricula marcada como <strong>REGISTRO N&deg;</strong> (ICFES)<br><br> </span></td>
                                </tr>
                                <tr>
                                    <td  width="100" align="right"><span class="Estilo18">*</span><strong>CONFIRME</strong> el N&uacute;mero del registro del icfes (SNP):</td>
                                    <td colspan="5" align="left">
                                        <select name="CVTipoIcfes" id="CVTipoIcfes" style="width:50px">
                                            <option value="AC" selected>AC</option>
                                            <option value="VG">VG</option>
                                        </select>
                                        <input name="CNroIcfes" type="text" id="CNroIcfes" size="15" oncopy="return false;" onpaste="return false;" onKeypress="return SoloNumero(event)" onBlur="ValidaSNP()" onpaste="return false" maxlength="12"> 
                                        <span class="Estilo18"><br>Recuerde que en estos dos campos no se deben digitar letras (AC o VG)<br><br></span> </td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Localidad del colegio donde culmin&oacute; el grado 11: </td>
                                    <td colspan="5" align="left">
                                        <?php
                                        print'<select size="1" name="LocCol">
						<option value="99" selected>Seleccione la localidad del colegio</option>';
                                        $i = 0;
                                        while (isset($RowLocCol[$i][0])) {
                                            echo'<option value="' . $RowLocCol[$i][0] . '">' . $RowLocCol[$i][1] . '</option>\n';
                                            $i++;
                                        }
                                        print'</select>';
                                        ?>
                                        <span class="Estilo18">
                                            <br>Nota: Se&ntilde;or aspirante, si el Colegio donde termin&oacute; el grado 11, es fuera de Bogot&aacute;, deber&aacute;  marcar la opci&oacute;n "Fuera de Bogot&aacute;"<br><br></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>Tipo de Colegio donde culmin&oacute; el Grado 11: </td>
                                    <td colspan="5" align="left">
                                        <select size="1" name="TipCol">
                                            <option value="O" selected>Oficial</option>
                                            <option value="P">Privado</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>¿Posee alg&uacute;n tipo de discapacidad?</td>
                                    <td colspan="5" align="left">
                                        <?php
                                        print'<select size="1" name="TipDis">
						<option value="0" selected>Seleccione el tipo de discapacidad si la presenta</option>';
                                        $i = 0;
                                        while (isset($RowTipDis[$i][0])) {
                                            echo'<option value="' . $RowTipDis[$i][0] . '">' . $RowTipDis[$i][1] . '</option>\n';
                                            $i++;
                                        }
                                        print'</select>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><span class="Estilo18">*</span>¿Valid&oacute; el bachillerato? </td>
                                    <td colspan="5" align="left">
                                        <select size="1" name="valido" style="width:50px">
                                            <option value="si">Si</option>
                                            <option value="no" selected>No</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100" align="right">
                                        <span class="Estilo18">*</span>N&uacute;mero de semestres transcurridos desde la terminaci&oacute;n  del grado once:
                                    </td>
                                    <td colspan="5">
                                        <select name="semestresTranscurridos" id="semestresTranscurridos">
                                            <option value="0" selected>0  reci&eacute;n Graduado</option>
                                            <option value="1">1 semestre</option>
                                            <option value="2">2 semestres</option>
                                            <option value="3">3 semestres</option>
                                            <option value="4">4 semestres</option>
                                            <option value="5">5 semestres</option>
                                            <option value="6">6 semestres</option>
                                            <option value="7">M&aacute;s de 3 a&ntilde;os</option>
                                        </select>
                                        <span class="Estilo18">
                                            <br>Nota: N&uacute;mero de semestres transcurridos entre la terminaci&oacute;n  del grado once y la postulaci&oacute;n a la Universidad Distrital Francisco Jos&eacute; de Caldas.<br><br></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100" align="right">Observaciones:</td>
                                    <td colspan="5" align="left">	  <textarea rows="3" cols="70" name="obs" onKeyDown="ConTex(this.form.obs, this.form.contador);" onKeyUp="ConTex(this.form.obs, this.form.contador);"></textarea>
                                        <br>
                                        S&oacute;lo puede digitar 100 caracteres.</td>
                                <br>
                                </tr>
                            </table>
                            <br>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <p align="center">
                <input class="button" type="button" value="Continuar" title="Continuar con la inscripción" onClick="ValidaInscripcionAspirante()" style="width:120; cursor:pointer">
                <input class="button"  type="reset" value="Borrar" title="Limpiar el formulario" style="width:120; cursor:pointer">
            </p>
        </form>
        <?php
        print '<center>' . $err . '</center>';
        ?>
    </fieldset>
</body>
</html>
