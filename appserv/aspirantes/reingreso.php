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
$form = "reingreso";
$item = "FechaNac";
?>
<html>
    <head>
        <title>Reingreso</title>
        <link href="../script/estilo.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="../generales_asp/ValidaReingreso.js"></script>
        <script language="JavaScript" src="../calendario/javascripts.js"></script>
        <script language="JavaScript" src="Logout.js"></script>
        <script language="JavaScript" src="../script/LisLov.js"></script>
        <script language="JavaScript" src="../script/BorraLink.js"></script>
        <script language="JavaScript" src="../script/SoloNumero.js"></script>
        <script type='text/javascript' src='../generales_asp/formexp.js'></script>
        <script language="JavaScript" src="../script/Logout.js"></script>
        <script language="JavaScript" src="../generales_asp/CuentaCaracteres.js"></script>
        <script>
            function expandir_formulario(valor) {
                if (valor == "0") {
                    xDisplay('CapCra', 'none')
                }
                if (valor == "26") {
                    xDisplay('CapCra', 'none')
                }
                if (valor == "25") {
                    xDisplay('CapCra', 'block')
                }
            }
            /*window.addEvent("domready", function() {  
             var exValidatorA = new fValidator("exA");  
             }); */

        </script>
        <style type="text/css">
            #capainicio{position:relative;}
            #CapCra{position:relative; display:none; }
            #capafinal{position:relative;}
        </style>

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
        <p align="center" class="Estilo6">FORMULARIO DE REINGRESO O TRANSFERENCIA INTERNA <BR><? print $periodo; ?></p>

        <form name="reingreso" method="post" action="asp_verifica_reingreso.php" class="bootstrap-frm">
            <table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
                <tr>
                    <td>
                        <fieldset style="padding:10">
                            <br>
<? //require_once('ins.php');  ?>
                            <div id=capainicio>
                                <table width="98%" border="1" align="center" cellpadding="0" cellspacing="2" style="border-collapse:collapse">
                                    <tr>
                                        <td width="150" align="right"><b>Seleccione el tipo de inscripci&oacute;n:</b></td>
                                        <td>
                                            <?php
                                            //var_dump($RowTipIns);
                                            print'<select size="1" name="TipoIns" onchange="expandir_formulario(this.value)">';
                                            $i = 0;
                                            while (isset($RowTipIns[$i][0])) {
                                                //if ($RowTipIns[$i][0]==26)
                                                //{
                                                echo'<option value="' . $RowTipIns[$i][0] . '">' . $RowTipIns[$i][1] . '</option>\n';
                                                $i++;
                                                //}else{$i++;}
                                            }

                                            print'</select>';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">Documento de identidad: </td>
                                        <td><input name="DocActual" type="text" id="DocActual" size="14" maxlength="12" onkeyup="this.value = this.value.slice(0, 12)" onKeyPress="return SoloNumero(event)"></td>
                                    </tr>
                                    <tr>
                                        <td align="right">C&oacute;digo de estudiante en la Universidad Distrital:</td>
                                        <td><input name="EstCod" type="text" id="EstCod"  pattern="/\d/" required="required" size="15" maxlength="11" onkeyup="this.value = this.value.slice(0, 11)" onKeypress="return SoloNumero(event)"></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><b>Confirme</b> el c&oacute;digo de estudiante en la Universidad Distrital:</td>
                                        <td>
                                            <input name="ConEstCod" type="text"  pattern="/\d/" required="required" id="ConEstCod" size="15" maxlength="11" onkeyup="this.value = this.value.slice(0, 11)" onKeypress="return SoloNumero(event)" onBlur="ComparaEstCod();">	  </td>
                                    </tr>
                                    <tr>
                                        <td align="right">Cancel&oacute; semestre: </td>
                                        <td>
                                            <label>&nbsp;Si&nbsp;<input name="CanSem" type="radio" value="S"></label>
                                            <label>&nbsp;No<input name="CanSem" type="radio" value="N" checked></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">Motivo del retiro: </td>
                                        <td colspan="5" align="left">	  
                                            <textarea rows="3" cols="70" name="MotRetiro" onKeyDown="ConTex(this.form.MotRetiro, this.form.contador);" onKeyUp="ConTex(this.form.MotRetiro, this.form.contador);"></textarea>
                                            <br>
                                            S&oacute;lo puede digitar <input type="text" name="contador" size="2" value="100" style="text-align:center; border:0; height:auto" readonly> caracteres.</td>
                                    </tr>
                                    <tr>
                                        <td align="right">Tel&eacute;fono:</td>
                                        <td colspan="3" align="left"><input name="tel" type="text" id="tel" maxlength="15" onkeyup="this.value = this.value.slice(0, 15)" size="15"></td>
                                    </tr>
                                    <tr>
                                        <td align="right">Correo electr&oacute;nico: </td>
                                        <td align="left"><input id="exA_Id" class="" name="CtaCorreo" type="text"  size="60" onChange="javascript:this.value = this.value.toLowerCase();" maxlength="50"></td>
                                    </tr>
                                </table>
                            </div>

                            <div id=CapCra>
                                <table width="98%" border="1" align="center" cellpadding="0" cellspacing="2" style="border-collapse:collapse">
                                    <tr>
                                        <td width="40%" align="right">Carrera que ven&iacute;a cursando: </td>
                                        <td align="left">
                                            <?php
                                            print'<select size="1" name="CraCod">
							<option value="0" selected>Seleccione el Proyecto Curricular</option>';
                                            $i = 0;
                                            while (isset($RowCra[$i][0])) {
                                                echo'<option value="' . $RowCra[$i][0] . '">' . $RowCra[$i][1] . '</option>\n';
                                                $i++;
                                            }

                                            print'</select>';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">Carrera a la cual se transfiere:</td>
                                        <td colspan="3">
                                            <?php
                                            print'<select size="1" name="TCraCod">
						  <option value="0" selected>Seleccione el Proyecto Curricular</option>';
                                            $i = 0;
                                            while (isset($RowTCra[$i][0])) {
                                                //if ($RowTCra[$i][0] != 181) {
                                                    echo'<option value="' . $RowTCra[$i][0] . '">' . $RowTCra[$i][1] . '</option>\n';
                                                //}
                                                $i++;
                                            }
                                            print'</select>';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <br>
                        </fieldset>
                    </td></tr>
            </table>
            <p align="center">
                <input class="button" type="button" value="Continuar" title="Continuar con la inscripción" onClick="ValidaInscripcion()">
                <input class="button"  type="reset" value="Borrar" title="Limpiar el formulario" style="width:120; cursor:pointer">
            </p>  
        </form>
    </fieldset>
    <p></p>

</body>
</html>
