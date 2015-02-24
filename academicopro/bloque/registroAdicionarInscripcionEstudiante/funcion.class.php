<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroAdicionarInscripcionEstudiante extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacionInscripcion.class.php");


        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"estudianteCred");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroAdicionarInscripcionEstudiante";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->validacion=new validacionInscripcion();

        ?>
<head>
    <script language="JavaScript">
        var message = "";
        function clickIE(){
            if (document.all){
                (message);
                return false;
            }
        }
        function clickNS(e){
            if (document.layers || (document.getElementById && !document.all)){
                if (e.which == 2 || e.which == 3){
                    (message);
                    return false;
                }
            }
        }
        if (document.layers){
            document.captureEvents(Event.MOUSEDOWN);
            document.onmousedown = clickNS;
        } else {
            document.onmouseup = clickNS;
            document.oncontextmenu = clickIE;
        }
        document.oncontextmenu = new Function("return false")
    </script>
</head>
        <?

    }


    function consultarEspaciosPermitidos($configuracion) {

        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $codProyecto=$_REQUEST['codProyecto'];
        $estado_est=$_REQUEST['estado_est'];
        //var_dump($_REQUEST);exit;

        $cadena_sql_plan=$this->sql->cadena_sql($configuracion,"plan_estudio", $codigoEstudiante);//echo $cadena_sql_plan;exit;
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );
        $planEstudio=$resultado_plan[0][0];
        $carrera=$resultado_plan[0][1];

        $permitidos=array($planEstudio,$codigoEstudiante);

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        $variables=array($codigoEstudiante,$planEstudio,$carrera,$permitidos,$ano[0],$ano[1]);

        $retorno['pagina']="adminConsultarCreditosEstudiante";
        $retorno['opcion']="mostrarConsulta";
        $retorno['parametros']="&codEstudiante=".$_REQUEST["codEstudiante"];
        $retorno['parametros'].="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
        $retorno['parametros'].="&codProyecto=".$_REQUEST["codProyecto"];
        
        $this->validacion->validarEstadoEstudiante($configuracion, $codigoEstudiante, $retorno);
//*PRUEBA ACADEMICA*
        if(trim($estado_est)=='B') {
            $cadena_sql_planEstudio=$this->sql->cadena_sql($configuracion,"espacios_plan_estudio_prueba", $permitidos);//echo $cadena_sql_planEstudio;exit;
            $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_planEstudio,"busqueda" );
        }else {
            $cadena_sql_planEstudio=$this->sql->cadena_sql($configuracion,"espacios_plan_estudio", $permitidos);//echo $cadena_sql_planEstudio;exit;
            $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_planEstudio,"busqueda" );
        }

        $cadena_sql_parametros=$this->sql->cadena_sql($configuracion,"parametros_plan", $planEstudio);
        $resultado_parametros=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_parametros,"busqueda" );
        
        $numeroCreditosRestantes=($resultado_parametros[0][3]-$_REQUEST['creditosInscritos']);

        ?><table width="70%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar">
        <?
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variable="pagina=adminConsultarCreditosEstudiante";
        $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];

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
    <caption class="sigma">
        <center>
            ESPACIOS PERMITIDOS
        </center>
    </caption>
<table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
<tr >
    <th class="sigma centrar" width="10%"><b>C&oacute;digo Espacio</b></th>
    <th class="sigma centrar" width="40%"><b>Nombre Espacio</b></th>
    <th class="sigma centrar" width="8%"><b>Clasificaci&oacute;n</b></th>
    <th class="sigma centrar" width="8%"><b>Nro Cr&eacute;ditos</b></th>
    <th class="sigma centrar" width="15%"><b>Adicionar</b></th>
</tr>
        <?
        list($OBEst, $OCEst, $EIEst, $EEEst, $OB, $OC, $EI, $EE)=$this->evaluaRangos($configuracion, $codigoEstudiante);
        //echo $OBEst."-OC".$OCEst."-EI".$EIEst."-EE".$EEEst."-Rang".$OB."-".$OC."-".$EI."-".$EE;

            $nivelAnterior=0;
            for($i=0;$i<count($resultado_planEstudio);$i++) {
                $band = '0';

                $cadena_sql_espacio=$this->sql->cadena_sql($configuracion,"nombre_espacio", $resultado_planEstudio[$i][0]);
                $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );

                if(is_array($resultado_espacio)) {

                    $requisito=array($planEstudio, $resultado_planEstudio[$i][0]);
                    $cadena_otroRequisito=$this->sql->cadena_sql($configuracion,"otroRequisito", $requisito);
                    $resultado_otroRequisito= $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_otroRequisito,"busqueda" );
                    if ($resultado_otroRequisito[0][0]>0)
                    {
                        $cadena_requisito=$this->sql->cadena_sql($configuracion,"requisitos", $requisito);
                        $resultado_requisito=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_requisito,"busqueda" );
                        for ($a=0;$a<$resultado_otroRequisito[0][0];$a++)
                        {
                            $aprobado=array($resultado_requisito[$a][1],$codigoEstudiante);
                            $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,"curso_aprobado", $aprobado);
                            $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );
                            if ($resultado_aprobado[0][0]>="30") {
                                $band = '0';
                            }
                            else if($resultado_aprobado[0][0]<"30") {
                                      if ($resultado_requisito[$a][0]==1)
                                      {$band = '1';
                                        break;}
                                        else
                                          {
                                            $band=0;
                                          }
                                      }
                        }
                    }


                    if ($band == '0') {



                        if(trim($resultado_planEstudio[$i][2])!=$nivelAnterior) {
                            $nivelAnterior=$resultado_planEstudio[$i][2];
                            ?>


    <tr>
        <td class="sigma_a cuadro_plano centrar" colspan="6">
            <font size="2">
                <?
                if ($resultado_planEstudio[$i][2]==98){
                    ?>COMPONENTE PROPED&Eacute;UTICO<?
                }else{
                ?>
                PER&Iacute;ODO DE FORMACI&Oacute;N <? echo $resultado_planEstudio[$i][2];}?>
            </font></td>
    </tr>
                        <?
                    }
                    ?> <tr>
        <td class='cuadro_plano centrar'><? echo $resultado_planEstudio[$i][0]?></td>
        <td class='cuadro_plano '>
                        <?
                        $cadena_sql_espacio=$this->sql->cadena_sql($configuracion,"nombre_espacio", $resultado_planEstudio[$i][0]);
                        $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );

                    $variablesCancelado=array($codigoEstudiante,$resultado_planEstudio[$i][0],$ano[0],$ano[1]);

                                $cadena_sql=$this->sql->cadena_sql($configuracion,"estudianteCancelo", $variablesCancelado);
                                $resultado_cancelo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                echo $resultado_espacio[0][0];
                                ?>
        </td>
        <td class='cuadro_plano centrar'>
                                <?
                        $infoEspacio=array($codProyecto, $resultado_planEstudio[$i][0], $planEstudioGeneral);
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"infoEspacio", $infoEspacio);//echo $cadena_sql;exit;
                        $resultado_clasif=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );


                                echo $resultado_clasif[0][1];
                                $totalOB=$resultado_espacio[0][1]+$OBEst;
                                $totalOC=$resultado_espacio[0][1]+$OCEst;
                                $totalEI=$resultado_espacio[0][1]+$EIEst;
                                $totalEE=$resultado_espacio[0][1]+$EEEst;
                                $bandClasifica=0;
                                switch($resultado_clasif[0][1]){
                                    case OB:
                                        if ($OB<$totalOB) {
                                            $bandClasifica=1;
                                        }
                                        break;

                                    case OC:
                                        if ($OC<$totalOC) {
                                            $bandClasifica=1;
                                        }
                                        break;

                                    case EI:
                                        if ($EI<$totalEI) {
                                            $bandClasifica=1;
                                        }
                                        break;

                                    case EE:
                                        if ($EE<$totalEE) {
                                            $bandClasifica=1;
                                        }
                                        break;
//                                    case CP:
//                                        if ($OB<$totalOB) {
//                                            $bandClasifica=1;
//                                        }
//                                        break;
                                }

                                ?>
        </td>
                                <?
                    if($resultado_espacio[0][1]<=$numeroCreditosRestantes && !is_array($resultado_cancelo)) {
                                if($bandClasifica==0) {
                                    ?>
        <td class='cuadro_plano centrar'>
            <font color="#3BAF29"><? echo $resultado_espacio[0][1]?></font>
        </td>
        <td class='cuadro_plano centrar'>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                <input type="hidden" name="planEstudioGeneral" value="<?echo $_REQUEST['planEstudioGeneral']?>">
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                <input type="hidden" name="espacio" value="<?echo $resultado_planEstudio[$i][0]?>">
                <input type="hidden" name="nombre" value="<?echo $resultado_espacio[0][0]?>">
                <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                <input type="hidden" name="creditos" value="<?echo $resultado_espacio[0][1]?>">
                <input type="hidden" name="creditosInscritos" value="<?echo $_REQUEST['creditosInscritos']?>">
                <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                <input type="hidden" name="estado_est" value="<?echo $estado_est?>">
                <input type="hidden" name="carrera" value="<?echo $carrera?>">
                <input type="hidden" name="año" value="<?echo $ano?>">
                <input type="hidden" name="opcion" value="validar">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >
            </form>

        </td>
                            <?
                        }
                                else {
                                    ?>
        <td class='cuadro_plano centrar'>
            <font color="#F90101"><? echo $resultado_espacio[0][1]?></font>
        </td>
        <td class='cuadro_plano centrar' width="15%">
            No puede adicionar, el n&uacute;mero de cr&eacute;ditos inscritos supera los establecidos para esta clasificaci&oacute;n
        </td>
                            <?}
                    }else if($resultado_espacio[0][1]>$numeroCreditosRestantes) {
                                ?>
        <td class='cuadro_plano centrar'>
            <font color="#F90101"><? echo $resultado_espacio[0][1]?></font>
        </td>
        <td class='cuadro_plano centrar' width="15%">
            No puede adicionar, el n&uacute;mero de cr&eacute;ditos inscritos supera los <?echo $resultado_parametros[0][3];?>
        </td>
                        <?
                    }else if(is_array($resultado_cancelo) && $resultado_espacio[0][1]<=$numeroCreditosRestantes) {
                                ?>
        <td class='cuadro_plano centrar'>
            <font color="#3BAF29"><? echo $resultado_espacio[0][1]?></font>
        </td>
        <td class='cuadro_plano centrar' width="15%">
            No se puede adicionar porque ha sido cancelado
        </td>
                        <?
                    }else if(is_array($resultado_cancelo) && $resultado_espacio[0][1]>$numeroCreditosRestantes) {
                        ?>
        <td class='cuadro_plano centrar'>
            <font color="#F90101"><? echo $resultado_espacio[0][1]?></font>
        </td>
        <td class='cuadro_plano centrar' width="15%">
            No se puede adicionar porque ha sido cancelado
        </td>
                        <?
                    }
                    ?>

    </tr>
                            <?

                }
            }


                else {
                    ?>
    <tr><th colspan="5">&zwnj;</th> </tr>
    <tr>
        <th class='cuadro_plano centrar' colspan="6">
            No se encontraron espacios acad&eacute;micos para adicionar.
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
        $variablesPag="pagina=adminConsultarCreditosEstudiante";
        $variablesPag.="&opcion=mostrarConsulta";
            $variablesPag.="&codEstudiante=".$_REQUEST["codEstudiante"];
            $variablesPag.="&codProyecto=".$_REQUEST['codProyecto'];
            $variablesPag.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];

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
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $codProyecto=$_REQUEST['codProyecto'];
        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $espacio=$_REQUEST['espacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $carrera=$_REQUEST['carrera'];
        $creditos=$_REQUEST['creditos'];
        $creditosInscritos=$_REQUEST['creditosInscritos'];
        $nombre=$_REQUEST['nombre'];

        //echo $carrera."<br>".$codProyecto;
         $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"ano_periodo", "");
         $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );
         $ano=$resultado_periodo[0][0];
         $periodo=$resultado_periodo[0][1];

        $variables=array($espacio,$carrera,$planEstudio,$ano, $periodo);

        $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,"grupos_proyecto", $variables);//echo $cadena_sql_grupos;exit;
        $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

        if($resultado_grupos==NULL) {
            switch ($carrera) {
                case "472": $carrera='72';
                    break;
                case "473": $carrera='73';
                    break;
                case "474": $carrera='74';
                    break;
                case "477": $carrera='77';
                    break;
                case "478": $carrera='78';
                    break;
                case "479": $carrera='79';
                    break;
                case "481": $carrera='81';
                    break;
                case "485": $carrera='85';
                    break;
            }

            $variables=array($espacio,$carrera,$planEstudio,$ano, $periodo);

            $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,"grupos_proyecto", $variables);//echo $cadena_sql_grupos;exit;
            $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );//var_dump($resultado_grupos);exit;
        }

        ?>
<table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar" width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarInscripcionEstudiante";
                    $variable.="&opcion=adicionar";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

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
                    $variable="pagina=registroAdicionarInscripcionEstudiante";
                    $variable.="&opcion=otrosGrupos";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

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
        <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="0px" >
      <th class='sigma_a centrar'>
    <? echo $espacio . " - " . $nombre; ?>
  </th>

        <?
        if(is_array($resultado_grupos)) {
            ?>
                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                  <tr><td class='sigma_a centrar'><b>
                    <?echo "PROYECTO CURRICULAR: ".$codProyecto?></b></td></tr>
                    <tr>
                        <td>
                            <table class='contenidotabla sigma'>
                                <thead class='sigma'>
                                <th class='cuadro_plano centrar' width="25">Grupo </th>
                                <th class='cuadro_plano centrar' width="60">Lun </th>
                                <th class='cuadro_plano centrar' width="60">Mar </th>
                                <th class='cuadro_plano centrar' width="60">Mie </th>
                                <th class='cuadro_plano centrar' width="60">Jue </th>
                                <th class='cuadro_plano centrar' width="60">Vie </th>
                                <th class='cuadro_plano centrar' width="60">S&aacute;b </th>
                                <th class='cuadro_plano centrar' width="60">Dom </th>
                                <th class='cuadro_plano centrar' width="20">Cupo </th>
                                <th class='cuadro_plano centrar' >Adicionar</th>
                                </thead>

                                            <?


                                            for($j=0;$j<count($resultado_grupos);$j++) {

                                                $variables[5]=$resultado_grupos[$j][0];

                                                $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_grupos", $variables);
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos_registrar", $variables);
                                                $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableCodigo[0]=$codigoEstudiante;
                                                $variableCodigo[4]=$ano;
                                                $variableCodigo[5]=$periodo;

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variableCodigo);
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

                                                $variableCupo=array($codigoEstudiante,$resultado_grupos[$j][0],$espacio, '', $ano, $periodo);

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_ins", $variableCupo);
                                                $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_cupo", $variableCupo);
                                                $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                unset($cupoDisponible);

                                                $cupoDisponible=($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]);

                                                    ?><tr><td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td><?
                                                    for($i=1; $i<8; $i++) {
                                                            ?><td class='cuadro_plano centrar' onmouseover="this.bgColor='#E6E6E6'" onmouseout="this.bgColor=''"><?
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
                                    <td class='cuadro_plano centrar' <?if($cupoDisponible<='0' || $cruce==true) {?>bgColor='#F8E0E0'<?}?>>

                                                        <?


                                                        if($cruce!=true) {

                                                            if($cupoDisponible>0) {
                        ?>
                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                            <input type="hidden" name="planEstudioGeneral" value="<?echo $planEstudioGeneral?>">
                                            <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j][0]?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                            <input type="hidden" name="espacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="carrera" value="<?echo $variables[1]?>">
                                            <input type="hidden" name="creditos" value="<?echo $_REQUEST['creditos']?>">
                                            <input type="hidden" name="creditosInscritos" value="<?echo $_REQUEST['creditosInscritos']?>">
                                            <input type="hidden" name="estado_est" value="<?echo $_REQUEST['estado_est']?>">
                                            <input type="hidden" name="opcion" value="inscribir">
                                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                            <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >

                                        </form>
                                    </td>

                                                            <?
                                                        }else {
                                                            ?>No puede adicionar por cupo</td><?
                                                        }
                                                    }
                                                    else {
                                                        ?>No puede adicionar por cruce</td><?
                                                    }



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
            No existen grupos habilitados en el Proyecto.<br><font size="2" color="red">Por favor consulte Grupos en otros Proyectos Curriculares.</font>
          </td>
        </tr>
            <?
                }
                ?>
    <table class="contenidotabla centrar" border="0">
        <tr class="bloquelateralcuerpo">
            <td class="centrar" width="50%">
        <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarCreditosEstudiante";
                        $variable.="&opcion=mostrarConsulta";
                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                        $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        ?>
                <a href="<?= $pagina.$variable ?>" >
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br><b>Mi Horario</b>
                </a>

            <td class="centrar" width="50%">
        <?
                        $cadena_sql_plan=$this->sql->cadena_sql($configuracion,"datosCoordinador", $this->usuario);//echo $cadena_sql_plan;exit;
                        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroAdicionarInscripcionEstudiante";
                        $variable.="&opcion=espacios";
                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                        $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                        $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                        $variable.="&estado_est=".$_REQUEST['estado_est'];

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
</tbody>
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
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );
        $ano=$resultado_periodo[0][0];
        $periodo=$resultado_periodo[0][1];

        $variables=array($espacio,$carrera,$planEstudio, $ano, $periodo);

        $cadena_sql_carreras=$this->sql->cadena_sql($configuracion,"buscar_carrerasAbiertas", $variables);
        $resultadoAdicionesAbiertas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_carreras,"busqueda" );

        ?>
<table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class='centrar' width="50%" >
        <?
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarInscripcionEstudiante";
                    $variable.="&opcion=adicionar";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

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
                    $variable="pagina=registroAdicionarInscripcionEstudiante";
                    $variable.="&opcion=otrosGrupos";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

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

        if(is_array($resultadoAdicionesAbiertas)) {$cuenta=0;
            ?>
                <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="0px" >
                    <th class='sigma_a centrar'>
                        <?echo $espacio." - ".$nombre?>
                    </th>
                </table>
            <?
            $band=0;
            for($p=0;$p<count($resultadoAdicionesAbiertas);$p++) {

                $variables[5]=$resultadoAdicionesAbiertas[$p][0];
                $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,"otros_grupos", $variables);
                $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

                if($resultado_grupos!=NULL) {
                    $band=1;
                    ?>
<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                  <tr><td class='sigma_a centrar'><b>
                    <?echo "PROYECTO CURRICULAR: ".$variables[5]?></b></td></tr>
                    <tr>
                        <td>
                            <table class='contenidotabla sigma'>
                                <thead class='sigma'>
                                <th class='cuadro_plano sigma centrar' width="60">Proyecto</th>
                                <th class='cuadro_plano sigma centrar' width="25">Grupo </th>
                                <th class='cuadro_plano sigma centrar' width="60">Lun </th>
                                <th class='cuadro_plano sigma centrar' width="60">Mar </th>
                                <th class='cuadro_plano sigma centrar' width="60">Mie </th>
                                <th class='cuadro_plano sigma centrar' width="60">Jue </th>
                                <th class='cuadro_plano sigma centrar' width="60">Vie </th>
                                <th class='cuadro_plano sigma centrar' width="60">S&aacute;b </th>
                                <th class='cuadro_plano sigma centrar' width="60">Dom </th>
                                <th class='cuadro_plano sigma centrar' width="20">Cupo</th>
                                <th class='cuadro_plano sigma centrar' width="30">Adicionar</th>
                                </thead>
                    <?
                        for($j=0;$j<count($resultado_grupos);$j++) {

                            $variables[5]=$resultado_grupos[$j][0];

                            $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_otros_grupos", $variables);
                            $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );


                            $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_otrosgrupos_registrar", $variables);//echo $cadena_sql;exit;
                            $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            $variableCodigo[0]=$codigoEstudiante;
                            $variableCodigo[4]=$ano;
                            $variableCodigo[5]=$periodo;

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variableCodigo);//echo $cadena_sql;exit;
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

                            $variableCupo=array($codigoEstudiante,$resultado_grupos[$j][0],$espacio, '', $ano, $periodo);
                            unset($cupoDisponible);
                            unset($resultado_cupoGrupo);

                            $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_ins", $variableCupo);
                            $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                            $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_cupo", $variableCupo);
                            $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                            unset($cupoDisponible);

                            $cupoDisponible=($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]);


                            ?><tr>

                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][1];?></td>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td><?
                        for($i=1; $i<8; $i++) {
                            ?><td class='cuadro_plano centrar' onmouseover="this.bgColor='#E6E6E6'" onmouseout="this.bgColor=''"><?
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
                                    <td class='cuadro_plano centrar' <?if($cupoDisponible<='0' || $cruce==true) {?>bgColor='#F8E0E0'<?}?>>

                                                                <?
                        if($cupoDisponible>0) {

                                                                    if($cruce!=true) {
                                                                        ?>

                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>

                                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                            <input type="hidden" name="planEstudioGeneral" value="<?echo $planEstudioGeneral?>">
                                            <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j][0]?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                            <input type="hidden" name="espacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="carrera" value="<?echo $variables[1]?>">
                                            <input type="hidden" name="creditos" value="<?echo $_REQUEST['creditos']?>">
                                            <input type="hidden" name="creditosInscritos" value="<?echo $_REQUEST['creditosInscritos']?>">
                                            <input type="hidden" name="estado_est" value="<?echo $_REQUEST['estado_est']?>">
                                            <input type="hidden" name="opcion" value="inscribir">
                                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                            <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >

                                        </form>
                                    </td>
                                <?
                            }else {
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
                }else{
                  $cuenta++;
                }


            }

if($cuenta==$p){
                    ?>
                    <tr>
                        <td class="cuadro_plano centrar">
                        En el momento no hay grupos habilitados en otros Proyectos
                        </td>
                    </tr>
                    <?
}

                }
                else {
                    ?>
        <tr>
                        <td class="cuadro_plano centrar">
                        En el momento no hay grupos habilitados en otros Proyectos
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
        $variable="pagina=adminConsultarCreditosEstudiante";
        $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br><b>Mi Horario</b>
            </a>

        <td class="centrar" width="50%">
        <?
        $cadena_sql_plan=$this->sql->cadena_sql($configuracion,"datosCoordinador", $this->usuario);//echo $cadena_sql_plan;exit;
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarInscripcionEstudiante";
                    $variable.="&opcion=espacios";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

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
        exit;
        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $var_espacio=array($_REQUEST['espacio'],$_REQUEST['planEstudio']);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_planEstudio", $var_espacio);//echo $cadena_sql;exit;
        $resultado_datosEspacio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        $variables[0]=$_REQUEST['codEstudiante'];
        $variables[1]=$_REQUEST['grupo'];
        $variables[2]=$_REQUEST['espacio'];
        $variables[3]=$_REQUEST['codProyecto'];
        $variables[4]=$ano[0];
        $variables[5]=$ano[1];
        $variables[6]=$_REQUEST['planEstudio'];
        $variables[7]=$resultado_datosEspacio[0][0];//Creditos E.A.
        $variables[8]=$resultado_datosEspacio[0][1];//H.T.D del E.A.
        $variables[9]=$resultado_datosEspacio[0][2];//H.T.C del E.A.
        $variables[10]=$resultado_datosEspacio[0][3];//H.T.A del E.A.
        $variables[11]=$resultado_datosEspacio[0][4];//Clasificacion E.A.

        $retorno['pagina']="adminConsultarCreditosEstudiante";
        $retorno['opcion']="mostrarConsulta";
        $retorno['parametros']="&codEstudiante=".$_REQUEST["codEstudiante"];
        $retorno['parametros'].="&codProyecto=".$_REQUEST['codProyecto'];
        $retorno['parametros'].="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
        //valida si el estudiante no excede los creditos de la clasificacion del espacio
        $this->validacion->verificarRangos($configuracion, $_REQUEST['planEstudio'], $_REQUEST['espacio'], $_REQUEST['codEstudiante'], $resultado_datosEspacio[0][4], $retorno);

        if(trim($_REQUEST['estado_est'])=='B' || trim($_REQUEST['estado_est'])=='J') {
            $bandPrueba='0';
            $variablesPrueba=array($_REQUEST['planEstudio'],$_REQUEST['codEstudiante']);

            $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_plan_estudio_prueba", $variablesPrueba);//echo $cadena_sql;exit;
            $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            for($m=0;$m<count($resultado_planEstudio);$m++) {
                if($resultado_planEstudio[$m][0]==$_REQUEST['espacio']) {

                    $bandPrueba='1';

                }
            }

            if($bandPrueba=='0')
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"nombre_espacio", $_REQUEST['espacio']);
                    $resultado_nombreEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    echo "<script>alert ('No se puede inscribir el Espacio Académico ".$_REQUEST['espacio']." - ".$resultado_nombreEspacio[0][0].". El estudiante está en Prueba Académica (Parágrafo 1, Artículo 1, Acuerdo 07 de 2009).');</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarCreditosEstudiante";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }
        }
        $variableInscripcion=$variables;
        $cadena_sql=$this->sql->cadena_sql($configuracion,"carrera_estudiante", $variables);
        $resultado_carreraEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $variableInscripcion[3]=$resultado_carreraEstudiante[0]['CARRERA_ESTUDIANTE'];

        $cadena_sql_buscarEspacioOracle=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variableInscripcion);
        $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_buscarEspacioOracle,"busqueda" );

        if (!is_array($resultado_EspacioOracle)) {

            $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,"horario_registrado", $variableInscripcion);
            $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"busqueda" );

            $cadena_sql_horario_grupo_nuevo=$this->sql->cadena_sql($configuracion,"horario_grupo_nuevo", $variableInscripcion);
            $resultado_horario_grupo_nuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_grupo_nuevo,"busqueda" );

            for($i=0;$i<count($resultado_horario_registrado);$i++) {
                for($j=0;$j<count($resultado_horario_grupo_nuevo);$j++) {

                    if(($resultado_horario_grupo_nuevo[$j])==($resultado_horario_registrado[$i])) {
                        echo "<script>alert ('El horario del grupo seleccionado presenta cruce con el horario que usted tiene inscrito');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarCreditosEstudiante";
                        $variable.="&opcion=mostrarConsulta";
                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                        $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];


                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }
                }
            }

            $cadena_sql_cupo_grupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_cupo", $variables);
            $resultado_cupo_grupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupo_grupo,"busqueda" );//echo $cadena_sql_cupo_grupo."<br>";

            $cadena_sql_cupo_inscritos=$this->sql->cadena_sql($configuracion,"cupo_grupo_ins", $variables);//echo $cadena_sql_cupo_inscritos;exit;
            $resultado_cupo_inscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupo_inscritos,"busqueda" );

            if($resultado_cupo_inscritos[0][0]<=$resultado_cupo_grupo[0][0]) {
                //valida si el estudiante no ha inscrito mas creditos de los permitidos para el periodo
                $this->validacion->validarCreditosPeriodo($configuracion, $_REQUEST['codEstudiante'], $_REQUEST['planEstudio'], $_REQUEST['espacio'], $retorno);

                    $cadena_sql_adicionarMysql=$this->sql->cadena_sql($configuracion,"adicionar_espacio_mysql", $variableInscripcion);
                    $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_adicionarMysql,"" );

                    if($resultado_adicionarMysql==true) {
                        $cadena_sql_adicionar=$this->sql->cadena_sql($configuracion,"adicionar_espacio_oracle", $variableInscripcion);//echo $cadena_sql_adicionar;exit;
                        $resultado_adicionar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_adicionar,"" );

                        if($resultado_adicionar==true) {

                            $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,"actualizar_cupo", $variables);
                            $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

                            $variablesRegistro=array($this->usuario,date('YmdGis'),'1','Adiciona Espacio académico',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$resultado_cupo_grupo[0]['CARRERA'],$_REQUEST['codEstudiante']);

                            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
                            $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            //echo "<script>alert ('Usted registro el espacio académico.Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";
                            echo "<script>alert ('Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarCreditosEstudiante";
                            $variable.="&opcion=mostrarConsulta";
                            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            exit;
                        }
                        else {
                            echo "<script>alert ('En este momento la base de datos esta atendiendo el limite maximo de usuarios. Por favor intente mas tarde');</script>";

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"borrar_datos_mysql_no_conexion", $variableInscripcion);
                            $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                            $variablesRegistro=array($this->usuario,date('YmdGis'),'50','Conexion Error Oracle',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$resultado_cupo_grupo[0]['CARRERA'],$_REQUEST['codEstudiante']);

                            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarCreditosEstudiante";
                            $variable.="&opcion=mostrarConsulta";
                            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            exit;
                        }
                    }
                    else {
                        echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                        $cadena_sql=$this->sql->cadena_sql($configuracion,"borrar_datos_mysql_no_conexion", $variableInscripcion);
                        $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                        $variablesRegistro=array($this->usuario,date('YmdGis'),'51','Conexion Error MySQL',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$resultado_cupo_grupo[0]['CARRERA'],$_REQUEST['codEstudiante']);

                        $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarCreditosEstudiante";
                        $variable.="&opcion=mostrarConsulta";
                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                        $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }


            }else {
                echo "<script>alert ('Este curso tiene el máximo número de inscritos, no puede adicionar ');</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminConsultarCreditosEstudiante";
                $variable.="&opcion=mostrarConsulta";
                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
            }
        }
        else {
            echo "<script>alert ('Este espacio ya había sido registrado y no puede adicionarse nuevamente');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarCreditosEstudiante";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }

    }

    function verificarCancelacion($configuracion) {
        $_REQUEST['codProyecto'];
        $_REQUEST['planEstudioGeneral'];

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);

        $variablesCancelado=array($_REQUEST['codEstudiante'],$_REQUEST['espacio'],$ano[0],$ano[1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"estudianteCancelo", $variablesCancelado);
        $resultado_cancelo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        if(is_array($resultado_cancelo)) {

        }else {
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=registroAdicionarInscripcionEstudiante";
            $variable.="&opcion=adicionar";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
            $variable.="&espacio=".$_REQUEST["espacio"];
            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
            $variable.="&carrera=".$_REQUEST["carrera"];
            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
            $variable.="&creditos=".$_REQUEST["creditos"];
            $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
            $variable.="&nombre=".$_REQUEST["nombre"];
            $variable.="&estado_est=".$_REQUEST["estado_est"];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
    }

    function evaluaRangos($configuracion, $codEstudiante) {
        //echo $codEstudiante;
        $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarPlan", $codEstudiante);//echo $cadena_sql;exit;
        $registroPlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $planEstudiante=$registroPlan[0][1];

        $cadena_sql=$this->sql->cadena_sql($configuracion,"creditosPlan", $planEstudiante);//echo $cadena_sql;exit;
        $registroCreditosGeneral=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        $totalCreditos= $registroCreditosGeneral[0][0];
        $OB= $registroCreditosGeneral[0][1];
        $OC= $registroCreditosGeneral[0][2];
        $EI= $registroCreditosGeneral[0][3];
        $EE= $registroCreditosGeneral[0][4];

        $cadena_sql=$this->sql->cadena_sql($configuracion,"espaciosAprobados", $codEstudiante);//echo $cadena_sql;exit;
        $registroEspaciosAprobados=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        // var_dump($registroEspaciosAprobados);exit;
        $OBEst=0;
        $OCEst=0;
        $EIEst=0;
        $EEEst=0;
        $totalCreditosEst=0;
        //echo $registroEspaciosAprobados[0][1];exit;
        for($i=0;$i<=count($registroEspaciosAprobados);$i++) {
            $idEspacio= $registroEspaciosAprobados[$i][0];
            $variables=array($idEspacio, $planEstudiante);

            $cadena_sql=$this->sql->cadena_sql($configuracion,"valorCreditosPlan", $variables);//echo $cadena_sql;exit;
            $registroCreditosEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            switch($registroCreditosEspacio[0][1]) {
                case 1:
                    $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                    break;

                case 2:
                    $OCEst=$OCEst+$registroCreditosEspacio[0][0];
                    break;

                case 3:
                    $EIEst=$EIEst+$registroCreditosEspacio[0][0];
                    break;

                case 4:
                    $EEEst=$EEEst+$registroCreditosEspacio[0][0];
                    break;

                case 5:
                    $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                    break;

            }
        }
//echo "OB:".$OBEst."  OC:".$OCEst."  EI:".$EIEst."  EE:".$EEEst;exit;
        $cadena_sql=$this->sql->cadena_sql($configuracion,"espaciosInscritos", $codEstudiante);//echo $cadena_sql;exit;
        $registroEspaciosInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        //var_dump($registroEspaciosInscritos);exit;

        //echo $registroEspaciosAprobados[0][1];exit;
        for($i=0;$i<count($registroEspaciosInscritos);$i++) {
            $idEspacio= $registroEspaciosInscritos[$i][0];
            $variables=array($idEspacio, $planEstudiante);//var_dump($variables);

            $cadena_sql=$this->sql->cadena_sql($configuracion,"valorCreditosPlan", $variables);//echo "<br>".$cadena_sql;//exit;
            $registroCreditosEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            if(is_array($registroCreditosEspacio)) {

                switch($registroCreditosEspacio[0][1]) {
                    case 1:
                        $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                        break;

                    case 2:
                        $OCEst=$OCEst+$registroCreditosEspacio[0][0];
                        break;

                    case 3:
                        $EIEst=$EIEst+$registroCreditosEspacio[0][0];
                        break;

                    case 4:
                        $EEEst=$EEEst+$registroCreditosEspacio[0][0];
                        break;

                    case 5:
                        $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                        break;

                }
            }else {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"valorCreditos", $variables);//echo "<br>".$cadena_sql;//exit;
                $registroCreditosEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                switch($registroCreditosEspacio[0][1]) {
                    case 1:
                        $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                        break;

                    case 2:
                        $OCEst=$OCEst+$registroCreditosEspacio[0][0];
                        break;

                    case 3:
                        $EIEst=$EIEst+$registroCreditosEspacio[0][0];
                        break;

                    case 4:
                        $EEEst=$EEEst+$registroCreditosEspacio[0][0];
                        break;

                    case 5:
                        $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                        break;

                }
            }
        }
//echo "OB:".$OBEst."  OC:".$OCEst."  EI:".$EIEst."  EE:".$EEEst;exit;
        $totalCreditosEst=$OBEst+$OCEst+$EIEst+$EEEst;

        /*   $totalCreditosEst=180;
            $OBEst=73;
            $OCEst=8;
            $EIEst=70;
            $EEEst=30;

           $totalCreditosEst=100;
            $OBEst=40;
            $OCEst=6;
            $EIEst=53;
            $EEEst=20;*/
        return array($OBEst, $OCEst, $EIEst, $EEEst, $OB, $OC, $EI, $EE);

    }


    function calcularCreditosInscritos($configuracion,$codEstudiante) {
      echo "Esta funcion no esta disponible";
      exit;
        $cadena_sql=$this->sql->cadena_sql($configuracion,"espaciosInscritos", $codEstudiante);//echo $cadena_sql;exit;
        $registroEspaciosInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        //var_dump($registroEspaciosInscritos);exit;

        //echo $registroEspaciosAprobados[0][1];exit;
        for($i=0;$i<count($registroEspaciosInscritos);$i++) {
            $idEspacio= $registroEspaciosInscritos[$i][0];
            $variables=array($idEspacio, $planEstudiante);//var_dump($variables);

            $cadena_sql=$this->sql->cadena_sql($configuracion,"valorCreditos", $variables);//echo "<br>".$cadena_sql;//exit;
            $registroCreditosEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            switch($registroCreditosEspacio[0][1]) {
                case 1:
                    $OBEst=$OBEst+$registroCreditosEspacio[0][0];
                    break;

                case 2:
                    $OCEst=$OCEst+$registroCreditosEspacio[0][0];
                    break;

                case 3:
                    $EIEst=$EIEst+$registroCreditosEspacio[0][0];
                    break;

                case 4:
                    $EEEst=$EEEst+$registroCreditosEspacio[0][0];
                    break;

            }
        }
        $totalCreditosEst=$OBEst+$OCEst+$EIEst+$EEEst;

        return $totalCreditosEst;
    }

}

?>