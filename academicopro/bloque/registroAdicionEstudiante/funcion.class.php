<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroAdicionEstudiante extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/administrarModulo.class.php");


//        $this->administrar=new administrarModulo();
//        $this->administrar->administrarModuloSGA($configuracion, '4');

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroAdicionEstudiante";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }


    function consultarEspaciosPermitidos($configuracion) {
        if($_REQUEST['codEstudiante']==NULL) {
            $codigoEstudiante=$this->usuario;

        }else {
            $codigoEstudiante=$_REQUEST['codEstudiante'];

        }
        $cadena_sql_plan=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"plan_estudio", $codigoEstudiante);//echo $cadena_sql_plan;exit;
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );
        $planEstudio=$resultado_plan[0][0];
        $carrera=$resultado_plan[0][1];

        $permitidos=array($planEstudio,$codigoEstudiante);

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"año_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        $variables=array($codigoEstudiante,$planEstudio,$carrera,$permitidos,$ano[0],$ano[1]);

        $cadena_sql_planEstudio=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"espacios_plan_estudio", $permitidos);//echo $cadena_sql_planEstudio;exit;
        $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_planEstudio,"busqueda" );

        $cadena_sql_parametros=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"parametros_plan", $planEstudio);
        $resultado_parametros=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_parametros,"busqueda" );

        $cadena_sql_numCreditos=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"numero_creditos", $variables);
        $resultado_numCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_numCreditos,"busqueda" );

        if($resultado_numCreditos==NULL) {
            $cadena_sql_numCreditosRegistro=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"crear_creditos", $variables);
            $resultado_numCreditosRegistro=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_numCreditosRegistro,"" );
            $numeroCreditosRestantes=$resultado_parametros[0][3];
        }else {

            $numeroCreditosRestantes=($resultado_parametros[0][3]-$resultado_numCreditos[0][0]);
        }
        ?><table width="70%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminInscripcionCreditos";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br><b>Mi Horario</b>
            </a>
        </td>
    </tr>
</table>
<table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
    <thead class='cuadro_color centrar'>
    <td><center>ESPACIOS PERMITIDOS</center></td>
</thead>
<table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
    <thead class='cuadro_color centrar'>
    <td>Nivel</td><td>Codigo Espacio</td><td>Nombre Espacio</td><td>Clasificaci&oacute;n</td><td>Nro Cr&eacute;ditos</td><td>Adicionar</td>
    </thead>
            <?
            if($this->nivel==52) {
                for($i=0;$i<count($resultado_planEstudio);$i++) {
                    $band = '0';

                    $cadena_sql_espacio=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nombre_espacio", $resultado_planEstudio[$i][0]);
                    $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );

                    if($resultado_espacio!='') {

                        $requisito=array($planEstudio, $resultado_planEstudio[$i][0]);
                        $cadena_requisito=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"requisitos", $requisito);
                        $resultado_requisito=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_requisito,"busqueda" );

                        $otro_requisito=array($planEstudio, $resultado_requisito[0][2]);
                        $cadena_otroRequisito=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"otroRequisito", $otro_requisito);
                        $resultado_otroRequisito= $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_otroRequisito,"busqueda" );

                        if ($resultado_otroRequisito[0][0]=='2') {

                            $cadena_requisitoUno=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"requisitoUno", $otro_requisito);
                            $resultado_requisitoUno= $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_requisitoUno,"busqueda" );

                            if ($resultado_requisitoUno[0][0]=='1') {
                                $aprobado=array($resultado_requisito[0][1],$codigoEstudiante);
                                $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso_aprobado", $aprobado);
                                $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );

                                if ($resultado_aprobado[0][0]>="30") {
                                    $band = '0';
                                }
                                else if($resultado_aprobado[0][0]<"30") {
                                        $band = '1';
                                    }
                            }
                        }

                        else if ($resultado_otroRequisito[0][0]=='1') {
                                switch ($resultado_requisito[0][0]) {
                                    case '1': {
                                            $aprobado=array($resultado_requisito[0][1],$codigoEstudiante);
                                            $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso_aprobado", $aprobado);
                                            $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );
                                            if ($resultado_aprobado[0][0]>="30") {
                                                $band = '0';
                                            }
                                            else if($resultado_aprobado[0][0]<"30") {
                                                    $band = '1';
                                                }
                                        }
                                        break;
                                    case '0': {
                                            $aprobado=array($resultado_requisito[0][1],$codigoEstudiante);
                                            $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso_aprobado", $aprobado);
                                            $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );
                                            if ($resultado_aprobado[0][0]>="50") {
                                                $band = '0';
                                            }
                                            break;
                                        }
                                }
                            }
                        if ($band == '0') {
                            ?> <tr>
        <td class='cuadro_plano centrar'><? echo $resultado_planEstudio[$i][2]?></td> <!-- Nivel del espacio academico -->
        <td class='cuadro_plano centrar'><? echo $resultado_planEstudio[$i][0]?></td> <!-- Codigo espacio académico -->
        <td class='cuadro_plano '><?
                                    $cadena_sql_espacio=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nombre_espacio", $resultado_planEstudio[$i][0]);
                                    $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );
                                    echo $resultado_espacio[0][0];
                                    ?></td> <!-- Nombre espacio académico -->
        <td class='cuadro_plano centrar'><? echo $resultado_espacio[0][2]?></td> <!-- Clasificación -->

                                <?if($resultado_espacio[0][1]<=$numeroCreditosRestantes) {
                                    $verBusqueda=array($codigoEstudiante,$resultado_planEstudio[$i][0],$ano[0],$ano[1]);
                                    $cadena_sql_espacioCancelado=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"espacio_cancelado", $verBusqueda);
                                    $resultado_espacioCancelado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacioCancelado,"busqueda" );

                                    if($resultado_espacioCancelado[0][0]==3) {
                                        ?>  <td class='cuadro_plano centrar'><font color="#3BAF29"><? echo $resultado_espacio[0][1]?></font></td><!-- Número de créditos -->
        <td class='cuadro_plano centrar'>No se puede adicionar porque ha sido cancelado</td>
    </tr>
                                <?
                                }else {
                                    ?><td class='cuadro_plano centrar'><font color="#3BAF29"><? echo $resultado_espacio[0][1]?></font></td><!-- Número de créditos -->
    <td class='cuadro_plano centrar'>

        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <input type="hidden" name="espacio" value="<?echo $resultado_planEstudio[$i][0]?>">
            <input type="hidden" name="nombre" value="<?echo $resultado_espacio[0][0]?>">
            <input type="hidden" name="opcion" value="adicionar">
            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
            <input type="hidden" name="creditos" value="<?echo $resultado_espacio[0][1]?>">
            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
            <input type="hidden" name="carrera" value="<?echo $carrera?>">
            <input type="hidden" name="año" value="<?echo $ano?>">
            <input type="hidden" name="action" value="<?echo $this->formulario?>">
            <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >
        </form>
    </td>
    </tr>
                                <?}

                            }else if($resultado_espacio[0][1]>$numeroCreditosRestantes) {

                                    ?>  <td class='cuadro_plano centrar'><font color="#F90101"><? echo $resultado_espacio[0][1]?></font></td>
    <td class='cuadro_plano centrar'>No se puede adicionar por cr&eacute;ditos</td>
    </tr>
                                <?

                                }
                        }
                    }
                    else {
                        ?>   
    <tr><th colspan="5">&zwnj;</th> </tr>
    <tr>
        <th class='cuadro_plano centrar' colspan="5">
            No se encontrar&oacute;n espacios acad&eacute;micos para adicionar.
        </th>
    </tr>
    <tr><th colspan="5">&zwnj;</th> </tr>
                    <?
                    }
                }
            }else if($this->nivel==28) {
                    for($i=0;$i<count($resultado_planEstudio);$i++) {
                        $band = '0';

                        $cadena_sql_espacio=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nombre_espacio", $resultado_planEstudio[$i][0]);
                        $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );

                        if($resultado_espacio!='') {

                            $requisito=array($planEstudio, $resultado_planEstudio[$i][0]);
                            $cadena_requisito=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"requisitos", $requisito);
                            $resultado_requisito=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_requisito,"busqueda" );

                            $otro_requisito=array($planEstudio, $resultado_requisito[0][2]);
                            $cadena_otroRequisito=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"otroRequisito", $otro_requisito);
                            $resultado_otroRequisito= $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_otroRequisito,"busqueda" );

                            if ($resultado_otroRequisito[0][0]=='2') {

                                $cadena_requisitoUno=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"requisitoUno", $otro_requisito);
                                $resultado_requisitoUno= $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_requisitoUno,"busqueda" );

                                if ($resultado_requisitoUno[0][0]=='1') {
                                    $aprobado=array($resultado_requisito[0][1],$codigoEstudiante);
                                    $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso_aprobado", $aprobado);
                                    $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );

                                    if ($resultado_aprobado[0][0]>="30") {
                                        $band = '0';
                                    }
                                    else if($resultado_aprobado[0][0]<"30") {
                                            $band = '1';
                                        }
                                }
                            }

                            else if ($resultado_otroRequisito[0][0]=='1') {
                                    switch ($resultado_requisito[0][0]) {
                                        case '1': {
                                                $aprobado=array($resultado_requisito[0][1],$codigoEstudiante);
                                                $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso_aprobado", $aprobado);
                                                $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );
                                                if ($resultado_aprobado[0][0]>="30") {
                                                    $band = '0';
                                                }
                                                else if($resultado_aprobado[0][0]<"30") {
                                                        $band = '1';
                                                    }
                                            }
                                            break;
                                        case '0': {
                                                $aprobado=array($resultado_requisito[0][1],$codigoEstudiante);
                                                $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso_aprobado", $aprobado);
                                                $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );
                                                if ($resultado_aprobado[0][0]>="50") {
                                                    $band = '0';
                                                }
                                                break;
                                            }
                                    }
                                }
                            if ($band == '0') {
                                ?> <tr>
                                     <td class='cuadro_plano centrar'><? echo $resultado_planEstudio[$i][2]?></td> <!-- Nivel del espacio academico -->
        <td class='cuadro_plano centrar'><? echo $resultado_planEstudio[$i][0]?></td>
        <td class='cuadro_plano '><?
                                        $cadena_sql_espacio=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nombre_espacio", $resultado_planEstudio[$i][0]);
                                        $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );
                                        echo $resultado_espacio[0][0];
                                        ?></td>
        <td class='cuadro_plano centrar'><? echo $resultado_espacio[0][2]?></td>
                                    <?
                                    if($resultado_espacio[0][1]<=$numeroCreditosRestantes) {
                                        ?><td class='cuadro_plano centrar'><font color="#3BAF29"><? echo $resultado_espacio[0][1]?></font></td>
        <td class='cuadro_plano centrar'>

            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                <input type="hidden" name="espacio" value="<?echo $resultado_planEstudio[$i][0]?>">
                <input type="hidden" name="nombre" value="<?echo $resultado_espacio[0][0]?>">
                <input type="hidden" name="opcion" value="adicionar">
                <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                <input type="hidden" name="creditos" value="<?echo $resultado_espacio[0][1]?>">
                <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                <input type="hidden" name="carrera" value="<?echo $carrera?>">
                <input type="hidden" name="año" value="<?echo $ano?>">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >
            </form>
        </td>
    </tr>
                                <?
                                }else if($resultado_espacio[0][1]>$numeroCreditosRestantes) {
                                        ?>
    <td class='cuadro_plano centrar'><font color="#F90101"><? echo $resultado_espacio[0][1]?></font></td>
    <td class='cuadro_plano centrar'>
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <input type="hidden" name="espacio" value="<?echo $resultado_planEstudio[$i][0]?>">
            <input type="hidden" name="nombre" value="<?echo $resultado_espacio[0][0]?>">
            <input type="hidden" name="opcion" value="adicionar">
            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
            <input type="hidden" name="creditos" value="<?echo $resultado_espacio[0][1]?>">
            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
            <input type="hidden" name="carrera" value="<?echo $carrera?>">
            <input type="hidden" name="año" value="<?echo $ano?>">
            <input type="hidden" name="action" value="<?echo $this->formulario?>">
            <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >
        </form>
    </td>
    </tr>
                                    <?

                                    }
                            }

                        }
                        else {
                            ?>
    <tr><th colspan="5">&zwnj;</th> </tr>
    <tr>
        <th class='cuadro_plano centrar' colspan="6">
            No se encontrar&oacute;n espacios acad&eacute;micos para adicionar.
        </th>
    </tr>
    <tr><th colspan="6">&zwnj;</th> </tr>
                        <?
                        }

                    }

                }

            ?>
</table>
<table class="cuadro_color centrar" width="100%">
            <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variablesPag="pagina=adminInscripcionCreditos";
            $variablesPag.="&opcion=mostrarConsulta";
            $variablesPag.="&codEstudiante=".$_REQUEST["codEstudiante"];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variablesPag=$this->cripto->codificar_url($variablesPag,$configuracion);

            ?>
    <tr class="centrar">
        <td colspan="3">
            <a href="<?= $pagina.$variablesPag ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" width="25" height="25" border="0"><br>
                <font size="2"><b>Regresar</b></font>
            </a>
        </td>
    </tr>
    <tr class="cuadro_plano centrar">
        <th>
            Observaciones
        </th>
    </tr>
    <tr class="cuadro_plano">
        <td>
            * Si el n&uacute;mero de cr&eacute;ditos est&aacute; en <font color="#3BAF29">verde</font>, significa que puede adicionar el espacio acad&eacute;mico sin exceder el l&iacute;mite de cr&eacute;ditos permitidos
            <br>
            * Si el n&uacute;mero de cr&eacute;ditos est&aacute; en <font color="#F90101">rojo</font>, significa que no puede adicionar porque excede el l&iacute;mite de cr&eacute;ditos permitidos
            <br>
            * Recuerde que si el grupo no cumple con el cupo m&iacute;nimo, puede ser cancelado
        </td>
    </tr>
</table>
</table>

    <?
    }

    function buscarGrupo($configuracion) {
        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $espacio=$_REQUEST['espacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $carrera=$_REQUEST['carrera'];
        $creditos=$_REQUEST['creditos'];
        $nombre=$_REQUEST['nombre'];
        //var_dump($_REQUEST);exit;
        switch($carrera)
        {
            case '485':

                    $carrera='85';
                break;

            case '478':

                    $carrera='78';
                break;
           
            
        }




        $variables=array($espacio,$carrera,$planEstudio);

        $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"grupos_proyecto", $variables);
        //           var_dump($cadena_sql_grupos);exit;
        $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

        ?>
<table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar" width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adicionarCreditos";
                    $variable.="&opcion=adicionar";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos de mi <br>Proyecto Curricular</b>
            </a>

            <td class="centrar" width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adicionarCreditos";
                    $variable.="&opcion=otrosGrupos";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos en otros<br>Proyectos Curriculares</b>
            </a>
        </td>
    </tr>
</table>
        <?
        if($resultado_grupos!=NULL) {
            ?>
<table width="100%" border="0" align="center" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                    <thead class='texto_subtitulo centrar'>
                    <td><center><?echo $espacio." - ".$nombre;?></center></td>
                    </thead>
                    <tr>
                        <td>
                            <table class='contenidotabla'>
                                <thead class='cuadro_color'>
                                <td class='cuadro_plano centrar' width="25">Grupo </td>
                                <td class='cuadro_plano centrar' width="60">Lun </td>
                                <td class='cuadro_plano centrar' width="60">Mar </td>
                                <td class='cuadro_plano centrar' width="60">Mie </td>
                                <td class='cuadro_plano centrar' width="60">Jue </td>
                                <td class='cuadro_plano centrar' width="60">Vie </td>
                                <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                <td class='cuadro_plano centrar' width="60">Dom </td>
                                <td class='cuadro_plano centrar' width="20">Cupo </td>
                                <td class='cuadro_plano centrar' >Adicionar</td>
                                </thead>

                                            <?


                                            for($j=0;$j<count($resultado_grupos);$j++) {

                                                $variables[3]=$resultado_grupos[$j][0];

                                                $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos", $variables);
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos_registrar", $variables);
                                                $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableCodigo[0]=$codigoEstudiante;

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_registrado", $variableCodigo);//echo $cadena_sql;exit;
                                                $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                                            
                                                unset($cruce);

                                                for($n=0;$n<count($resultado_horarios_registrado);$n++) {
                                                                for($m=0;$m<count($resultado_horarios_registrar);$m++) {

                                                                    if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n])) {

                                                                        $cruce=true;
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                $cupoDisponible=($resultado_horarios[$j][4]-$resultado_horarios[$j][5]);

                                                $variableCupo=array($codigoEstudiante,$resultado_grupos[$j][0],$espacio);

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_ins", $variableCupo);
                                                $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_cupo", $variableCupo);
                                                $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                unset($cupoDisponible);

                                                $cupoDisponible=($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]);

                                                ?><tr><td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td><?
                                                    for($i=1; $i<8; $i++) {
                                                        ?><td class='cuadro_plano centrar'><?
                                                            for ($k=0;$k<count($resultado_horarios);$k++) {

                                                                if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3])) {
                                                                    $l=$k;
                                                                    while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3])) {

                                                                        $m=$k;
                                                                        $m++;
                                                                        $k++;
                                                                    }
                                                                    $dia="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                }
                                                                elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0]) {
                                                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                    $k++;
                                                                }
                                                                elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3])) {
                                                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                }
                                                                elseif ($resultado_horarios[$k][0]!=$i) {

                                                                }
                                                            }
                                                            ?></td><?
                                                        }
                                                    ?><td class='cuadro_plano centrar'><?echo $cupoDisponible?></td>
                                    <td class='cuadro_plano centrar'>

                                                        <?

                                                        if($this->nivel==28) {
                                                                if($cruce!=true){
                                                            ?>
                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>

                                            <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j][0]?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                            <input type="hidden" name="espacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="carrera" value="<?echo $variables[1]?>">
                                            <input type="hidden" name="creditos" value="<?echo $_REQUEST['creditos']?>">
                                            <input type="hidden" name="opcion" value="inscribir">
                                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                            <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >

                                        </form>
                                    </td>

                                                    <?
                                                    }
                                                    else
                                                        {
                                                        ?>No puede adicionar por cruce</td><?
                                                        }
                                                    }
                                                    else if($this->nivel==52) {


                                                            if($cupoDisponible>0) {
                                                                ?>

                                <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>

                                    <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j][0]?>">
                                    <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                    <input type="hidden" name="espacio" value="<?echo $variables[0]?>">
                                    <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                    <input type="hidden" name="carrera" value="<?echo $variables[1]?>">
                                    <input type="hidden" name="creditos" value="<?echo $_REQUEST['creditos']?>">
                                    <input type="hidden" name="opcion" value="inscribir">
                                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                    <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >

                                </form>
                        </td>
                                                <?
                                                }
                                                else {
                                                    echo "No puede adicionar por cupo";?></td><?
                                                }
                                                ?>
                    </tr>
                                        <?}
                                

                                            }
                                ?>
                </table>
            </td>

        </tr>

</table>
</td>
</tr>
        <?
        }else {
            ?>
<tr>
    <td class="cuadro_plano centrar">
        No existen grupos registrados
    </td>
</tr>
        <?
        }
        ?>
</tbody>
</table>

<table class="contenidotabla centrar" border="0">
    <tr class="bloquelateralcuerpo">
        <td class="centrar" width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminInscripcionCreditos";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br><b>Mi Horario</b>
            </a>

        <td class="centrar" width="50%">
                    <?
                    $cadena_sql_plan=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosCoordinador", $this->usuario);//echo $cadena_sql_plan;exit;
                    $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adicionarCreditos";
                    if($resultado_plan[0][0]==291)
                        {
                            $variable.="&opcion=electivas";
                        }else
                            {
                            $variable.="&opcion=espacios";
                            }
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/reload.png" width="35" height="35" border="0"><br><b>Cambiar Espacio</b>
            </a>
        </td>
    </tr>
</table>

    <?





    }




    function buscarOtrosGrupos($configuracion) {
        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $espacio=$_REQUEST['espacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $carrera=$_REQUEST['carrera'];
        $creditos=$_REQUEST['creditos'];
        $nombre=$_REQUEST['nombre'];

        switch($carrera)
        {
            case '485':

                    $carrera='85';
                break;
            
            case '478':

                    $carrera='78';
                break;
        }

        $variables=array($espacio,$carrera,$planEstudio);

        $cadena_sql_carreras=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscar_carrerasAbiertas", $variables);
        $resultadoAdicionesAbiertas=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_carreras,"busqueda" );
        
        ?>
<table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class='centrar' width="50%" >
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adicionarCreditos";
                    $variable.="&opcion=adicionar";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos de mi<br>Proyecto Curricular</b>
            </a>

        <td class='centrar' width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adicionarCreditos";
                    $variable.="&opcion=otrosGrupos";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos en otros<br>Proyectos Curriculares</b>
            </a>
        </td>
    </tr>
</table>

        <?

        if(is_array($resultadoAdicionesAbiertas))
            {
        for($p=0;$p<count($resultadoAdicionesAbiertas);$p++){

            $variables[3]=$resultadoAdicionesAbiertas[$p][0];
            $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"otros_grupos", $variables);//echo $cadena_sql_grupos;exit;
            $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );
            
        if($resultado_grupos!=NULL) {
            ?>
<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                    <thead class='texto_subtitulo centrar'>
                    <td><center><?echo $espacio." - ".$nombre."   Carrera: ".$variables[3]?></center></td>
                    </thead>
                    <tr>
                        <td>
                            <table class='contenidotabla'>
                                <thead class='cuadro_color'>
                                <td class='cuadro_plano centrar' width="40">Proyecto</td>
                                <td class='cuadro_plano centrar' width="25">Grupo </td>
                                <td class='cuadro_plano centrar' width="60">Lun </td>
                                <td class='cuadro_plano centrar' width="60">Mar </td>
                                <td class='cuadro_plano centrar' width="60">Mie </td>
                                <td class='cuadro_plano centrar' width="60">Jue </td>
                                <td class='cuadro_plano centrar' width="60">Vie </td>
                                <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                <td class='cuadro_plano centrar' width="60">Dom </td>
                                <td class='cuadro_plano centrar' width="20">Cupo</td>
                                <td class='cuadro_plano centrar' >Adicionar</td>
                                </thead>

                                            <?


                                            for($j=0;$j<count($resultado_grupos);$j++) {

                                                $variables[3]=$resultado_grupos[$j][0];

                                                $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_otros_grupos", $variables);//echo $cadena_sql_horarios;exit;
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );
            
                                                
                                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_otrosgrupos_registrar", $variables);//echo $cadena_sql;exit;
                                                $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableCodigo[0]=$codigoEstudiante;

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_registrado", $variableCodigo);//echo $cadena_sql;exit;
                                                $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                unset($cruce);

                                                for($n=0;$n<count($resultado_horarios_registrado);$n++) {
                                                                for($m=0;$m<count($resultado_horarios_registrar);$m++) {

                                                                    if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n])) {

                                                                        $cruce=true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                $variableCupo=array($codigoEstudiante,$resultado_grupos[$j][0],$espacio);
                                                unset($cupoDisponible);
                                                unset($resultado_cupoGrupo);
                                                
                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_ins", $variableCupo);
                                                $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_cupo", $variableCupo);
                                                $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                unset($cupoDisponible);

                                                $cupoDisponible=($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]);

                                                
                                                ?><tr>

                                                        <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][1];?></td>
                                                        <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td><?
                                                    for($i=1; $i<8; $i++) {
                                                        ?><td class='cuadro_plano centrar'><?
                                                            for ($k=0;$k<count($resultado_horarios);$k++) {

                                                                if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3])) {
                                                                    $l=$k;
                                                                    while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3])) {

                                                                        $m=$k;
                                                                        $m++;
                                                                        $k++;
                                                                    }
                                                                    $dia="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                }
                                                                elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0]) {
                                                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                    $k++;
                                                                }
                                                                elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3])) {
                                                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                                                    echo $dia."<br>";
                                                                    unset ($dia);
                                                                }
                                                                elseif ($resultado_horarios[$k][0]!=$i) {

                                                                }
                                                            }
                                                            ?></td><?
                                                        }
                                                    ?><td class='cuadro_plano centrar'><?echo $cupoDisponible?></td>
                                    <td class='cuadro_plano centrar'>

                                                        <?
                                                        if($cupoDisponible>0) {

                                                            if($cruce!=true){
                                                            ?>

                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>

                                            <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j][0]?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                            <input type="hidden" name="espacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="carrera" value="<?echo $variables[1]?>">
                                            <input type="hidden" name="creditos" value="<?echo $_REQUEST['creditos']?>">
                                            <input type="hidden" name="opcion" value="inscribir">
                                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                            <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >

                                        </form>
                                    </td>
                                                    <?
                                                    }else
                                                        {
                                                            ?>No puede adicionar por cruce</td><?
                                                        }

                                                    }
                                                    else {
                                                        echo "No puede adicionar por cupo";?></td><?
                                                    }
                                                    ?>


                                </tr>
                                            <?
                                            }

                                            ?>
                            </table>

                        </td>

                    </tr>

                </table>
            </td>
        </tr>
                <?
                }

                }
                
                }
                else {
                    ?>
                    <tr>
                        <td class="cuadro_plano centrar">
                            
                        </td>
                    </tr>
                    <?}
                ?>
    </tbody>
</table>
<table class="contenidotabla centrar" border="0">
    <tr class="bloquelateralcuerpo">
        <td class="centrar" width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminInscripcionCreditos";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br><b>Mi Horario</b>
            </a>

        <td class="centrar" width="50%">
                    <?
                    $cadena_sql_plan=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosCoordinador", $this->usuario);//echo $cadena_sql_plan;exit;
                    $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adicionarCreditos";
                    if($resultado_plan[0][0]==291)
                        {
                            $variable.="&opcion=electivas";
                        }else
                            {
                                $variable.="&opcion=espacios";
                            }
                    
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/reload.png" width="35" height="35" border="0"><br><b>Cambiar Espacio</b>
            </a>
        </td>
    </tr>
</table>
    <?
    }

    function inscribirCredito($configuracion) {

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"año_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        $variables=array($_REQUEST['codEstudiante'],$_REQUEST['grupo'],$_REQUEST['espacio'],$_REQUEST['carrera'],$ano[0],$ano[1]);

        $cadena_sql_buscarEspacioOracle=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_espacio_oracle", $variables);
        $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_buscarEspacioOracle,"busqueda" );

        $cadena_sql_buscarEspacioMysql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscar_espacio_mysql", $variables);
        $resultado_EspacioMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEspacioMysql,"busqueda" );

        if ($resultado_EspacioOracle =='' and $resultado_buscarEspacioMysql == '') {

            $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_registrado", $variables);
            $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"busqueda" );

            $cadena_sql_horario_grupo_nuevo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupo_nuevo", $variables);
            $resultado_horario_grupo_nuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_grupo_nuevo,"busqueda" );

            for($i=0;$i<count($resultado_horario_registrado);$i++) {
                for($j=0;$j<count($resultado_horario_grupo_nuevo);$j++) {

                    if(($resultado_horario_grupo_nuevo[$j])==($resultado_horario_registrado[$i])) {
                        echo "<script>alert ('El horario del grupo seleccionado presenta cruce con el horario que usted tiene inscrito');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminInscripcionCreditos";
                        $variable.="&opcion=mostrarConsulta";
                        $variable.="&grupo=".$_REQUEST["grupo"];
                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                        $variable.="&espacio=".$_REQUEST["espacio"];
                        $variable.="&carrera=".$_REQUEST["carrera"];
                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }
                }
            }

            $cadena_sql_cupo_grupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_cupo", $variables);
            $resultado_cupo_grupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupo_grupo,"busqueda" );

            $cadena_sql_cupo_inscritos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_ins", $variables);
            $resultado_cupo_inscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupo_inscritos,"busqueda" );

            if($resultado_cupo_inscritos[0][0]<=$resultado_cupo_grupo[0][0] || $this->nivel=28) {

                $cadena_sql_numCreditos=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"numero_creditos", $variables);
                $resultado_numCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_numCreditos,"busqueda" );

                //echo $cadena_sql_numCreditos;
                $creditos=($resultado_numCreditos[0][0]+$_REQUEST['creditos']);
                //echo $creditos;
                if ($creditos<='18') {
                    

                    $variables[6]=$_REQUEST['planEstudio'];

                    $cadena_sql_adicionarMysql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"adicionar_espacio_mysql", $variables); 
                    $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_adicionarMysql,"" );
                    
                    if($resultado_adicionarMysql==true)
                        {
                            $cadena_sql_adicionar=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"adicionar_espacio_oracle", $variables);
                            $resultado_adicionar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_adicionar,"" );
                            
                            if($resultado_adicionar==true)
                                {
                                        //$creditos=($resultado_numCreditos[0][0]+$_REQUEST['creditos']);
                                    $variables[6]=$creditos;

                                    $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizar_creditos", $variables);
                                    $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );

                                    $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizar_cupo", $variables);
                                    $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

                                    $variablesRegistro=array($this->usuario,date('YmdGis'),'1','Adiciona Espacio académico',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],$_REQUEST['codEstudiante']);

                                    $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                    $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarIDRegistro", $variablesRegistro);
                                    $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                    //echo "<script>alert ('Usted registro el espacio académico.Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";
                                    echo "<script>alert ('Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=adminInscripcionCreditos";
                                    $variable.="&opcion=mostrarConsulta";
                                    $variable.="&grupo=".$_REQUEST["grupo"];
                                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                    $variable.="&espacio=".$_REQUEST["espacio"];
                                    $variable.="&carrera=".$_REQUEST["carrera"];
                                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    exit;
                                }
                                else
                                    {
                                        echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_datos_mysql_no_conexion", $variables);
                                        $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                                        $variablesRegistro=array($this->usuario,date('YmdGis'),'50','Conexion Error Oracle',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],$_REQUEST['codEstudiante']);

                                        $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=adminInscripcionCreditos";
                                        $variable.="&opcion=mostrarConsulta";
                                        $variable.="&grupo=".$_REQUEST["grupo"];
                                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                        $variable.="&espacio=".$_REQUEST["espacio"];
                                        $variable.="&carrera=".$_REQUEST["carrera"];
                                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                        exit;
                                    }
                        }
                        else
                                    {
                                        echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_datos_mysql_no_conexion", $variables);
                                        $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                                        $variablesRegistro=array($this->usuario,date('YmdGis'),'51','Conexion Error MySQL',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],$_REQUEST['codEstudiante']);

                                        $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=adminInscripcionCreditos";
                                        $variable.="&opcion=mostrarConsulta";
                                        $variable.="&grupo=".$_REQUEST["grupo"];
                                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                        $variable.="&espacio=".$_REQUEST["espacio"];
                                        $variable.="&carrera=".$_REQUEST["carrera"];
                                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                        exit;
                                    }



                    
                                        
                    
                }
                else {

                    $variables[6]=$_REQUEST['planEstudio'];

                    $cadena_sql_adicionarMysql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"adicionar_espacio_mysql", $variables);
                    $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_adicionarMysql,"" );
                    

                    if($resultado_adicionarMysql==true)
                        {
                            $cadena_sql_adicionar=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"adicionar_espacio_oracle", $variables);
                            $resultado_adicionar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_adicionar,"" );

                            if($resultado_adicionar==true)
                                {
                                        //$creditos=($resultado_numCreditos[0][0]+$_REQUEST['creditos']);
                                    $variables[6]=$creditos;

                                    $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizar_creditos", $variables);
                                    $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );

                                    $aumentaCupo=($resultado_cupo_grupo[0][1]+1);
                                    $variables[6]=$aumentaCupo;
                                    $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizar_cupo", $variables);
                                    $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

                                    $variablesRegistro=array($this->usuario,date('YmdGis'),'1','Adiciona Espacio académico',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],$_REQUEST['codEstudiante']);

                                    $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                    $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarIDRegistro", $variablesRegistro);
                                    $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                    //echo "<script>alert ('Usted registro el espacio académico.Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";
                                    echo "<script>alert ('El número de creditos es mayor al permitido por semestre. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=adminInscripcionCreditos";
                                    $variable.="&opcion=mostrarConsulta";
                                    $variable.="&grupo=".$_REQUEST["grupo"];
                                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                    $variable.="&espacio=".$_REQUEST["espacio"];
                                    $variable.="&carrera=".$_REQUEST["carrera"];
                                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    exit;
                                }
                                else
                                    {
                                        echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_datos_mysql_no_conexion", $variables);
                                        $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                                        $variablesRegistro=array($this->usuario,date('YmdGis'),'50','Conexion Error Oracle',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],$_REQUEST['codEstudiante']);

                                        $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=adminInscripcionCreditos";
                                        $variable.="&opcion=mostrarConsulta";
                                        $variable.="&grupo=".$_REQUEST["grupo"];
                                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                        $variable.="&espacio=".$_REQUEST["espacio"];
                                        $variable.="&carrera=".$_REQUEST["carrera"];
                                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                        exit;
                                    }
                        }
                        else
                                    {
                                        echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_datos_mysql_no_conexion", $variables);
                                        $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                                        $variablesRegistro=array($this->usuario,date('YmdGis'),'51','Conexion Error MySQL',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],$_REQUEST['codEstudiante']);

                                        $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=adminInscripcionCreditos";
                                        $variable.="&opcion=mostrarConsulta";
                                        $variable.="&grupo=".$_REQUEST["grupo"];
                                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                        $variable.="&espacio=".$_REQUEST["espacio"];
                                        $variable.="&carrera=".$_REQUEST["carrera"];
                                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                        exit;
                                    }


//
//                    $cadena_sql_adicionar=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"adicionar_espacio_oracle", $variables);
//                    $resultado_adicionar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_adicionar,"" );
//
//                    $variables[6]=$_REQUEST['planEstudio'];
//
//                    $cadena_sql_adicionarMysql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"adicionar_espacio_mysql", $variables);
//                    $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_adicionarMysql,"" );
//
//                    //$creditos=($resultado_numCreditos[0][0]+$_REQUEST['creditos']);
//                    $variables[6]=$creditos;
//
//                    $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizar_creditos", $variables);
//                    $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );
//
//                    $aumentaCupo=($resultado_cupo_grupo[0][1]+1);
//                    $variables[6]=$aumentaCupo;
//                    $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizar_cupo", $variables);
//                    $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );
//
//                    $variablesRegistro=array($this->usuario,date('YmdGis'),'1','Adiciona Espacio académico',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],$_REQUEST['codEstudiante']);
//
//                    $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
//                    $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );
//
//

//                    echo "<script>alert ('El número de creditos es mayor al permitido por semestre. Usted registro el espacio académico.');</script>";
//                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
//                    $variable="pagina=adminInscripcionCreditos";
//                    $variable.="&opcion=mostrarConsulta";
//                    $variable.="&grupo=".$_REQUEST["grupo"];
//                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
//                    $variable.="&espacio=".$_REQUEST["espacio"];
//                    $variable.="&carrera=".$_REQUEST["carrera"];
//                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
//
//                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
//                    $this->cripto=new encriptar();
//                    $variable=$this->cripto->codificar_url($variable,$configuracion);
//
//                    echo "<script>location.replace('".$pagina.$variable."')</script>";
//                    exit;

                }

            }else {
                echo "<script>alert ('Este curso tiene el máximo número de inscritos, no puede adicionar ');</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminInscripcionCreditos";
                $variable.="&opcion=mostrarConsulta";
                $variable.="&grupo=".$_REQUEST["grupo"];
                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                $variable.="&espacio=".$_REQUEST["espacio"];
                $variable.="&carrera=".$_REQUEST["carrera"];
                $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
            }
        }
        else {
            echo "<script>alert ('Este espacio ya había sido registrado y no puede adicionarse nuevamente');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminInscripcionCreditos";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&grupo=".$_REQUEST["grupo"];
            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
            $variable.="&espacio=".$_REQUEST["espacio"];
            $variable.="&carrera=".$_REQUEST["carrera"];
            $variable.="&planEstudio=".$_REQUEST["planEstudio"];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }

    }

    function consultarElectivasPermitidos($configuracion) {
        if($_REQUEST['codEstudiante']==NULL) {
            $codigoEstudiante=$this->usuario;

        }else {
            $codigoEstudiante=$_REQUEST['codEstudiante'];

        }

        $cadena_sql_plan=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosCoordinador", $this->usuario);//echo $cadena_sql_plan;exit;
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

         $cadena_sql_plan=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"plan_estudio", $codigoEstudiante);//echo $cadena_sql_plan;exit;
        $resultado_planEst=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );
        $planEstudioEst=$resultado_planEst[0][0];
        $carreraEst=$resultado_planEst[0][1];

        $planEstudio=$resultado_plan[0][0];
        $carrera=$resultado_plan[0][1];

        $permitidos=array($planEstudio,$codigoEstudiante);

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"año_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        $variables=array($codigoEstudiante,$planEstudio,$carrera,$permitidos,$ano[0],$ano[1]);

        $cadena_sql_planEstudio=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"espacios_plan_estudio", $permitidos);//echo $cadena_sql_planEstudio;exit;
        $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_planEstudio,"busqueda" );

        $cadena_sql_parametros=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"parametros_plan", $planEstudio);
        $resultado_parametros=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_parametros,"busqueda" );

        $cadena_sql_numCreditos=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"numero_creditos", $variables);
        $resultado_numCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_numCreditos,"busqueda" );

        if($resultado_numCreditos==NULL) {
            $cadena_sql_numCreditosRegistro=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"crear_creditos", $variables);
            $resultado_numCreditosRegistro=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_numCreditosRegistro,"" );
            $numeroCreditosRestantes=$resultado_parametros[0][3];
        }else {

            $numeroCreditosRestantes=($resultado_parametros[0][3]-$resultado_numCreditos[0][0]);
        }
        ?><table width="70%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminInscripcionCreditos";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br><b>Mi Horario</b>
            </a>
        </td>
    </tr>
</table>
<table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
    <thead class='cuadro_color centrar'>
    <td><center>ESPACIOS PERMITIDOS</center></td>
</thead>
<table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
    <thead class='cuadro_color centrar'>
    <td>Nivel</td><td>Codigo Espacio</td><td>Nombre Espacio</td><td>Clasificaci&oacute;n</td><td>Nro Cr&eacute;ditos</td><td>Adicionar</td>
    </thead>
            <?
            
                    for($i=0;$i<count($resultado_planEstudio);$i++) {
                        $band = '0';

                        $cadena_sql_espacio=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nombre_espacio", $resultado_planEstudio[$i][0]);//echo $cadena_sql_espacio;exit;
                        $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );

                        if($resultado_espacio!='') {

                            $requisito=array($planEstudio, $resultado_planEstudio[$i][0]);
                            $cadena_requisito=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"requisitos", $requisito);
                            $resultado_requisito=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_requisito,"busqueda" );

                            $otro_requisito=array($planEstudio, $resultado_requisito[0][2]);
                            $cadena_otroRequisito=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"otroRequisito", $otro_requisito);
                            $resultado_otroRequisito= $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_otroRequisito,"busqueda" );

                            if ($resultado_otroRequisito[0][0]=='2') {

                                $cadena_requisitoUno=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"requisitoUno", $otro_requisito);
                                $resultado_requisitoUno= $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_requisitoUno,"busqueda" );

                                if ($resultado_requisitoUno[0][0]=='1') {
                                    $aprobado=array($resultado_requisito[0][1],$codigoEstudiante);
                                    $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso_aprobado", $aprobado);
                                    $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );

                                    if ($resultado_aprobado[0][0]>="30") {
                                        $band = '0';
                                    }
                                    else if($resultado_aprobado[0][0]<"30") {
                                            $band = '1';
                                        }
                                }
                            }

                            else if ($resultado_otroRequisito[0][0]=='1') {
                                    switch ($resultado_requisito[0][0]) {
                                        case '1': {
                                                $aprobado=array($resultado_requisito[0][1],$codigoEstudiante);
                                                $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso_aprobado", $aprobado);
                                                $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );
                                                if ($resultado_aprobado[0][0]>="30") {
                                                    $band = '0';
                                                }
                                                else if($resultado_aprobado[0][0]<"30") {
                                                        $band = '1';
                                                    }
                                            }
                                            break;
                                        case '0': {
                                                $aprobado=array($resultado_requisito[0][1],$codigoEstudiante);
                                                $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso_aprobado", $aprobado);
                                                $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );
                                                if ($resultado_aprobado[0][0]>="50") {
                                                    $band = '0';
                                                }
                                                break;
                                            }
                                    }
                                }
                            if ($band == '0') {
                                ?> <tr>
                                     <td class='cuadro_plano centrar'><? echo $resultado_planEstudio[$i][2]?></td> <!-- Nivel del espacio academico -->
        <td class='cuadro_plano centrar'><? echo $resultado_planEstudio[$i][0]?></td>
        <td class='cuadro_plano '><?
                                        $cadena_sql_espacio=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nombre_espacio", $resultado_planEstudio[$i][0]);
                                        $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );
                                        echo $resultado_espacio[0][0];
                                        ?></td>
        <td class='cuadro_plano centrar'><? echo $resultado_espacio[0][2]?></td>
                                    <?
                                    if($resultado_espacio[0][1]<=$numeroCreditosRestantes) {
                                        ?><td class='cuadro_plano centrar'><font color="#3BAF29"><? echo $resultado_espacio[0][1]?></font></td>
        <td class='cuadro_plano centrar'>

            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                <input type="hidden" name="espacio" value="<?echo $resultado_planEstudio[$i][0]?>">
                <input type="hidden" name="nombre" value="<?echo $resultado_espacio[0][0]?>">
                <input type="hidden" name="opcion" value="adicionar">
                <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                <input type="hidden" name="creditos" value="<?echo $resultado_espacio[0][1]?>">
                <input type="hidden" name="planEstudio" value="<?echo $planEstudioEst?>">
                <input type="hidden" name="carrera" value="<?echo $carreraEst?>">
                <input type="hidden" name="año" value="<?echo $ano?>">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >
            </form>
        </td>
    </tr>
                                <?
                                }else if($resultado_espacio[0][1]>$numeroCreditosRestantes) {
                                        ?>
    <td class='cuadro_plano centrar'><font color="#F90101"><? echo $resultado_espacio[0][1]?></font></td>
    <td class='cuadro_plano centrar'>
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <input type="hidden" name="espacio" value="<?echo $resultado_planEstudio[$i][0]?>">
            <input type="hidden" name="nombre" value="<?echo $resultado_espacio[0][0]?>">
            <input type="hidden" name="opcion" value="adicionar">
            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
            <input type="hidden" name="creditos" value="<?echo $resultado_espacio[0][1]?>">
            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
            <input type="hidden" name="carrera" value="<?echo $carrera?>">
            <input type="hidden" name="año" value="<?echo $ano?>">
            <input type="hidden" name="action" value="<?echo $this->formulario?>">
            <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >
        </form>
    </td>
    </tr>
                                    <?

                                    }
                            }

                        }
                        else {
                            ?>
    <tr><th colspan="5">&zwnj;</th> </tr>
    <tr>
        <th class='cuadro_plano centrar' colspan="6">
            No se encontrar&oacute;n espacios acad&eacute;micos para adicionar.
        </th>
    </tr>
    <tr><th colspan="6">&zwnj;</th> </tr>
                        <?
                        }

                    }

                

            ?>
</table>
<table class="cuadro_color centrar" width="100%">
            <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variablesPag="pagina=adminInscripcionCreditos";
            $variablesPag.="&opcion=mostrarConsulta";
            $variablesPag.="&codEstudiante=".$_REQUEST["codEstudiante"];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variablesPag=$this->cripto->codificar_url($variablesPag,$configuracion);

            ?>
    <tr class="centrar">
        <td colspan="3">
            <a href="<?= $pagina.$variablesPag ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" width="25" height="25" border="0"><br>
                <font size="2"><b>Regresar</b></font>
            </a>
        </td>
    </tr>
    <tr class="cuadro_plano centrar">
        <th>
            Observaciones
        </th>
    </tr>
    <tr class="cuadro_plano">
        <td>
            * Si el n&uacute;mero de cr&eacute;ditos est&aacute; en <font color="#3BAF29">verde</font>, significa que puede adicionar el espacio acad&eacute;mico sin exceder el l&iacute;mite de cr&eacute;ditos permitidos
            <br>
            * Si el n&uacute;mero de cr&eacute;ditos est&aacute; en <font color="#F90101">rojo</font>, significa que no puede adicionar porque excede el l&iacute;mite de cr&eacute;ditos permitidos
            <br>
            * Recuerde que si el grupo no cumple con el cupo m&iacute;nimo, puede ser cancelado
        </td>
    </tr>
</table>
</table>

    <?
    }

}

?>