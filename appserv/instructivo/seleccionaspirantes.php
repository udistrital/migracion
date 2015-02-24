<?PHP
require_once('fu_ins_cabezote.php');
require_once('fu_ins_pie.php');
?>
<html>
    <head>
        <title>Instructivo de Admisiones</title>
        <META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
        <META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
        <link href="../script/estilo_div.css" rel="stylesheet" type="text/css">
        <link href="../script/estilo.css" rel="stylesheet" type="text/css">
        <script language="JavaScript" src="../script/clicder.js"></script>
        <script language="JavaScript" src="../script/modificado.js"></script>
        <script language="JavaScript" src="../script/ventana.js"></script>
        <script language="JavaScript" src="../script/MuestraLayer.js"></script>
    </head>

    <body class="td">
        <? fu_ins_cabezote(); ?>
        <p align="center" class="Estilo6">Selecci&oacute;n de Aspirantes</p>
        <table width="90%" align="center" border="0" cellspacing="2" cellpadding="0">
            <tr>
                <td>
                    <fieldset style="padding:13">

                        <table width="100%" border="0" cellspacing="0" cellpadding="0" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:center">
                            <tr>

                                <td>&nbsp;</td>
                                <td align="justify">
                                    <p align="center"><strong>SELECCI&Oacute;N DE ASPIRANTES</strong></p>

                                    <p align="justify" style="font-size:15px">
                                        La Asignaci&oacute;n de Cupos será directamente proporcional a la demanda de aspirantes inscritos, teniendo en cuenta los ICFES que fueron presentados hasta el primer semestre
                                        del año 2014 y los presentados a partir del segundo semestre de 2014</p>

                                    <p align="justify" style="font-size:15px"><b>EJEMPLO</b></p>

                                    <p style="font-size:15px">Si para 80 cupos se inscriben 500 aspirantes discriminados así:<br><br>

                                        <b> A. ICFES presentados hasta 2014-I: <b>300</b> aspirantes<br>
                                            B. ICFES presentados a partir 2014-II: <b>200</b> aspirantes<br><br></b>

                                        Para determinar el porcentaje de la demanda de cada grupo que entra a ser admitida, se efectuar&aacute; la siguiente operaci&oacute;n:<br>
                                    </p>
                                    <table width="50%" align="center">
                                        <tr>
                                            <td width="40%" align="right" style="font-size: 15px"><b>500</b></td>
                                            <td width="30%" align="center"><img src="flecha.png" alt="Flecha" height="6" width="100"/></td>
                                            <td width="40%" align="left"  style="font-size: 15px"><b>100 %</b></td>
                                        </tr>
                                        <tr onMouseOver="this.className = 'raton_arr'" onMouseOut="this.className = 'raton_aba'">
                                            <td align="right" style="font-size: 15px"><b>300</b></td>
                                            <td align="center"><img src="flecha.png" alt="Flecha" height="6" width="100"/></td>
                                            <td  align="left"   style="font-size: 15px"><b>X</b></td>
                                        </tr>
                                    </table>
                                    <br><br>
                                    <table width="50%" align="center">
                                        <tr>
                                            <td width="40%" align="right" style="font-size: 15px"><b>500</b></td>
                                            <td width="30%" align="center"><img src="flecha.png" alt="Flecha" height="6" width="100"/></td>
                                            <td width="40%" align="left"  style="font-size: 15px"><b>100 %</b></td>
                                        </tr>
                                        <tr onMouseOver="this.className = 'raton_arr'" onMouseOut="this.className = 'raton_aba'">
                                            <td align="right"  style="font-size: 15px"><b>200</b></td>
                                            <td align="center"><img src="flecha.png" alt="Flecha" height="6" width="100"/></td>
                                            <td  align="left"   style="font-size: 15px" ><b>X</b></td>
                                        </tr>
                                    </table>
                                    <p style="font-size:15px">

                                        Como resultado de la operación anterior, se tiene:<br><br>

                                        <b>A. X para ICFES presentados hasta 2014-I: 60%<br>
                                            B. X para ICFES presentados a partir 2014-II: 40%<br></b>
                                        <br><br>
                                        Estos porcentajes se multiplicaran por el total de cupos de cada Proyecto Curricular, cuyo resultado para el ejemplo será:<br><br>

                                        <b> A. ICFES presentados hasta 2014-I: los primeros 48 con mayor puntaje<br>
                                            B. ICFES presentados a partir 2014-II: los primeros 32 con mayor puntaje<br></b>
                                        <br>
                                        Luego se establerecer&aacute; un listado de admitidos y opcionados, de acuerdo al tipo de ICFES.
                                    </p></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
        <? fu_ins_pie(); ?>
    </body>
</html>