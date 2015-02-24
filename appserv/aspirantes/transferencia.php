<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect . 'valida_pag.php');
require_once(dir_general . 'msql_ano_per.php');
require_once('../calendario/calendario.php');
require_once(dir_conect . 'fu_tipo_user.php');
require_once(dir_general . 'valida_usuario_prog.php');
require_once(dir_general . 'valida_http_referer.php');
require_once(dir_general . 'valida_formulario_formulario.php');
require_once(dir_general . 'valida_inscripcion.php');

fu_tipo_user(50);

global $raiz;
$form = "transferencia";
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

        <title>Aspirantes</title>
        <link href="../script/estilo.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="../generales_asp/ValidaTransferencia.js"></script>
        <script language="javascript" src="../generales_asp/ValidaCampoFecha.js"></script>
        <script language="JavaScript" src="../calendario/javascripts.js"></script>
        <script language="JavaScript" src="Logout.js"></script>
        <script language="JavaScript" src="../script/BorraLink.js"></script>
        <script language="JavaScript" src="../script/SoloNumero.js"></script>
        <script type='text/javascript' src='../generales_asp/formexp.js'></script>
        <script language="JavaScript" src="../generales_asp/CuentaCaracteres.js"></script>
        <script language="javascript">
            var WinOpen = 0;
            function ListaValores(pag, R, S, D, H, an, al, iz, ar) {
                if (WinOpen) {
                    if (!WinOpen.closed)
                        WinOpen.close();
                }
                WinOpen = window.open(pag + '?httpR=' + R + '&httpS=' + S + '&httpD=' + D + '&httpH=' + H, "Lov", "width=" + an + ",height=" + al + ",scrollbars=YES,left=" + iz + ",top=" + ar);
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
    <body>
        <?
        require_once(dir_general . 'msql_querys.php');
        ?>
        <p align="center" class="Estilo6">FORMULARIO DE TRANSFERENCIA EXTERNA<br><? print $periodo; ?></p>

        <form name="transferencia" method="post" action="asp_verifica_transferencia.php" class="bootstrap-frm">
            <table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
                <tr>
                    <td>
                        <fieldset style="padding:10">
                            <br>
                            <? //require_once('ins.php');  ?>
                            <table width="99%" border="1" align="center" cellpadding="0" cellspacing="2" style="border-collapse:collapse">
                                <tr>
                                    <td colspan="6" align="center" class="Estilo9">
                                        DATOS TRANSFERENCIA
                                    </td>
                                </tr>
                                <tr>
                                    <td width="150" align="right">
                                        Carrera a la que se transfiere:
                                    </td>
                                    <td colspan="5">
                                        <?php
                                        print'<select size="1" name="CraCodT">
						<option value="0" selected>Seleccione el Proyecto Curricular</option>';
                                        $i = 0;
                                        while (isset($RowCra[$i][0])) {
                                            if (($RowCra[$i][0] != 273) && ($RowCra[$i][0] != 275) && ($RowCra[$i][0] != 277) && ($RowCra[$i][0] != 378) && ($RowCra[$i][0] != 373) && ($RowCra[$i][0] != 383) && ($RowCra[$i][0] != 279) && ($RowCra[$i][0] != 283) && ($RowCra[$i][0] != 372) && ($RowCra[$i][0] != 375) && ($RowCra[$i][0] != 377) && ($RowCra[$i][0] != 579) && ($RowCra[$i][0] != 678)) {
                                                echo'<option value="' . $RowCra[$i][0] . '">' . $RowCra[$i][0] . ' - ' . $RowCra[$i][1] . '</option>\n';
                                            }
                                            $i++;
                                        }
                                        print'</select>';
                                        ?>
                                        <br><b>NOTA:</b><font color='red'> Las inscripciones a los programas de ciclo profesional de ingenierias de la facultad tecnol&oacute;gica se realizan en la opci&oacute;n "INGRESO A PRIMER SEMESTRE".</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Tipo de inscripci&oacute;n:
                                    </td>
                                    <td colspan="5">
                                        <? print $RowTipInsEx[0][1]; ?>
                                        <input name="TiCod" type="hidden" value="<? print $RowTipInsEx[0][0]; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Universidad de donde viene:
                                    </td>
                                    <td colspan="5">
                                        <input type="text" name="UdPro"  id="UdPro" size="60"  maxlength="50">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Carrera que venia cursando:
                                    </td>
                                    <td colspan="5">
                                        <input type="text" name="CraCur"  id="CraCur" size="60" maxlength="50">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        &Uacute;ltimo semestre cursado:
                                    </td>
                                    <td colspan="5">        
                                        <input name="LastSem" type="text" id="LastSem" size="5" maxlength="2" onKeypress="return SoloNumero(event)">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Motivo de la transferencia:
                                    </td>
                                    <td colspan="5" align="left">	  
                                        <textarea rows="3" cols="70" name="motivo" onKeyDown="ConTex(this.form.motivo, this.form.contador);" onKeyUp="ConTex(this.form.motivo, this.form.contador);"></textarea>
                                        <br>
                                        S&oacute;lo puede digitar <input type="text" name="contador" size="2" value="100" style="text-align:center; border:0; height:auto" readonly> caracteres.</td>
                                </tr>
                                <tr>
                                    <td colspan="6" align="center" class="Estilo9">
                                        LUGAR Y FECHA DE NACIMIENTO
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Pais:
                                    </td>
                                    <td width="150">
                                        <select name="PaisNac" id="PaisNac">
                                            <option value="COLOMBIA" selected>COLOMBIA</option>
                                            <option value="EXTRANJERO">EXTRANJERO</option>
                                        </select>
                                    </td>
                                    <td width="60"><div align="right">
                                            Departamento:</div>
                                    </td>
                                    <td width="180">
                                        <? print'<input name="DptoNac" type="text" readonly="readonly" id="DptoNac" value="" size="12" onClick="ListaValores(\'lov_departamento.php\', this.name, DptoNac.value, \'NomDep\', DptoNac.value, 340, 200, 650, 390)" title="Haga clic para ver la lista de valores" onKeypress="return SoloNumero(event)">'; ?>
                                    </td>
                                    <td width="98"><div align="right">
                                            Municipio:</div>
                                    </td>
                                    <td width="150">
                                        <? print'<input name="CiudadNac" type="text" readonly="readonly" id="CiudadNac" value="" size="12" onClick="ListaValores(\'lov_municipio.php\', this.name, DptoNac.value, DptoNac.value, DptoNac.value, 340, 200, 650, 390)" title="Haga clic para ver la lista de valores" onKeypress="return SoloNumero(event)">'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Fecha de nacimiento:
                                    </td>
                                    <td colspan="5">
                                        <? print'<input name="FechaNac" placeholder="DD/MM/AAAA" type="text" id="FechaNac" maxlength="10" size="12" onBlur="ValidaCampoFecha();">';
                                        ?> <span class="Estilo3">dd/mm/aaaa </span>
                                    </td>
                                </tr>
                                                         <tr>
                                    <td align="right">
                                        Sexo:
                                    </td>
                                    <td colspan="5">        
                                        <select name="Sexo" id="Sexo">
                                            <option value="M" selected>Masculino</option>
                                            <option value="F">Femenino</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Estado civil:
                                    </td>
                                    <td colspan="5">
                                        <select name="EstCivil" id="EstCivil">
                                            <option value="1" selected>Soltero</option>
                                            <option value="2">Casado</option>
                                            <option value="3">Otro</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Direcci&oacute;n:
                                    </td>
                                    <td colspan="5" align="left">
                                        <input name="dir" type="text" id="dir" size="60" onChange="javascript:this.value=this.value.toUpperCase();" maxlength="50">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Localidad de residencia:
                                    </td> 
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
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Estrato de residencia:
                                    </td>
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
                                    <td align="right">
                                        Tel&eacute;fono:
                                    </td>
                                    <td colspan="5" align="left">
                                        <input name="tel" type="text" id="tel" maxlength="12" onkeyup="this.value=this.value.slice(0, 12)" size="15">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Correo electr&oacute;nico: 
                                    </td>
                                    <td colspan="5" align="left">
                                        <input name="CtaCorreo" type="text" id="CtaCorreo" size="60" onChange="javascript:this.value = this.value.toLowerCase();" maxlength="50">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" align="center" class="Estilo9">
                                        DOCUMENTO DE IDENTIDAD
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">
                                        Documento actual:
                                    </td>
                                    <td colspan="2" align="left">
                                        <input name="DocActual"  pattern="/\d/" required="required" type="text" id="DocActual"  maxlength="14" onkeyup="this.value=this.value.slice(0, 14)" size="15" onKeypress="return SoloNumero(event)">
                                    </td>
                                    <td width="30" align="left">
                                        <div align="right">Tipo:</div>
                                    </td>
                                    <td align="left">
                                        <select name="TipDocAct" id="TipDocAct">
                                            <option value="1" selected>C.C.</option>
                                            <option value="2">T.I.</option>
                                            <option value="3">C.E.</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">
                                        Documento de identidad con el que present&oacute; el ICFES:
                                    </td>
                                    <td colspan="2" align="left">
                                        <input name="DocIcfes"  pattern="/\d/" required="required" type="text" id="DocIcfes" size="15"  maxlength="14" onkeyup="this.value=this.value.slice(0, 14)" onKeypress="return SoloNumero(event)"> 
                                    </td>
                                    <td width="30" align="left"><div align="right">
                                            Tipo:</div>
                                    </td>
                                    <td align="left">
                                        <select name="TipDocIcfes" id="TipDocIcfes">
                                            <option value="1" selected>C.C.</option>
                                            <option value="2">T.I.</option>
                                            <option value="3">C.E.</option>
                                        </select>
                                    </td>
                                                                </tr>
                                <tr>
                                    <td colspan="6" align="center" class="Estilo9">
                                        REGISTRO ICFES
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        N&uacute;mero del registro del icfes (SNP):
                                    </td>
                                    <td colspan="5" align="left">
                                        <select name="TipoIcfes" id="TipoIcfes">
                                            <option value="AC" selected>AC</option>
                                            <option value="VG">VG</option>
                                        </select>
                                        <input name="NroIcfes" type="text" id="NroIcfes" size="15" onKeypress="return SoloNumero(event)" oncopy="return false" onpaste="return false" maxlength="12">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right"><strong>
                                            CONFIRME</strong> el N&uacute;mero del registro del icfes (SNP):
                                    </td>
                                    <td colspan="5" align="left">
                                        <select name="CVTipoIcfes" id="CVTipoIcfes">
                                            <option value="AC" selected>AC</option>
                                            <option value="VG">VG</option>
                                        </select>
                                        <input name="CNroIcfes" type="text" id="CNroIcfes" size="15" onKeypress="return SoloNumero(event)" onBlur="ValidaSNP()" oncopy="return false" onpaste="return false" maxlength="12">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Localidad del colegio donde culmin&oacute; el grado 11:
                                    </td>
                                    <td colspan="5" align="left">
                                        <?php
                                        print'<select size="1" name="LocCol">
						<option value="99" selected>Seleccione la localidad del colegio</option>';
                                        $i = 0;
                                        while (isset($RowLocCol[$i][0])) {
                                            echo'<option value="' . $RowLocCol[$i][0] . '">' . UTF8_DECODE($RowLocCol[$i][1]) . '</option>\n';
                                            $i++;
                                        }

                                        print'</select>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        Observaciones:
                                    </td>
                                    <td colspan="5" align="left">
                                        <textarea rows="3" cols="80" name="obs" onKeyDown="ConTex(this.form.obs,this.form.contador);" onKeyUp="ConTex(this.form.obs,this.form.contador);"></textarea><br>
                                        S&oacute;lo puede digitar <input type="text" name="contador" size="2" value="100" style="text-align:center; border:0; height:auto" readonly> caracteres.
                                    </td>
                                </tr>
                            </table>
                            <br>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <p align="center">
                <input class="button" type="button" value="Continuar" title="Continuar con la inscripción" onClick="ValidaInscripcion()" style="width:120; cursor:pointer">
                <input class="button"  type="reset" value="Borrar" title="Limpiar el formulario" style="width:120; cursor:pointer">
            </p>
        </form>
        <p></p>
    </body>
</html>
