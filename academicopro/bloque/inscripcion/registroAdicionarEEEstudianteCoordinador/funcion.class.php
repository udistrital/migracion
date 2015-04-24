<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroAdicionarEEEstudianteCoordinador extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/administrarModulo.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacionInscripcion.class.php");



//        $this->administrar=new administrarModulo();
//        $this->administrar->administrarModuloSGA($configuracion, '4');

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroAdicionarEEEstudianteCoordinador";
        $this->bloque="inscripcion/registroAdicionarEEEstudianteCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->validacion=new validacionInscripcion();
        
        //Conexion ORACLE
        //
        if($this->nivel==4){
        	$this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");
        }elseif($this->nivel==110){
        	$this->accesoOracle=$this->conectarDB($configuracion,"asistente");
        }


    }


    function consultarEspaciosPermitidos($configuracion) {

        //var_dump($_REQUEST);exit;
        $estado_est=$_REQUEST['estado_est'];
        if($_REQUEST['codEstudiante'])
            {
                $codigoEstudiante=$_REQUEST['codEstudiante'];
            }else
                {
                    echo "<script>alert('El código del estudiante no fue leido correctamente')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>alert('".$pagina.$variable."')</script>";
                    exit;

                }
                $retorno['pagina']="adminConsultarInscripcionEstudianteCoordinador";
                $retorno['opcion']="mostrarConsulta";
                $retorno['parametros']="&codEstudiante=".$_REQUEST["codEstudiante"];
                $retorno['parametros'].="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                $retorno['parametros'].="&codProyecto=".$_REQUEST["codProyecto"];

        $this->validacion->validarEstadoEstudiante($configuracion, $codigoEstudiante, $retorno);

//PA
//        if($estado_est=='B')
//            {
//                echo "<script>alert('El estado del estudiante es de prueba académica, no puede adicionar espacios académicos electivos extrinsecos')</script>";
//                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
//                $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
//                $variable.="&opcion=mostrarConsulta";
//                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
//                $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
//                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
//
//                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
//                $this->cripto=new encriptar();
//                $variable=$this->cripto->codificar_url($variable,$configuracion);
//                echo "<script>location.replace('".$pagina.$variable."')</script>";
//                exit;
//            }

        $cadena_sql_plan=$this->sql->cadena_sql($configuracion,"plan_estudio", $codigoEstudiante);
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );
        $planEstudio=$resultado_plan[0][0];
        $carrera=$resultado_plan[0][1];

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );
        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);

        $permitidos=array($planEstudio,$codigoEstudiante,$ano[0],$ano[1]);
        $variables=array($codigoEstudiante,$planEstudio,$carrera,$permitidos,$ano[0],$ano[1]);

        $cadena_sql_planEstudio=$this->sql->cadena_sql($configuracion,"electivas_extrinsecas", $permitidos);
        $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_planEstudio,"busqueda" );
        $c=0;
        $cargados=array();

        /*for($i=0;$i<count($resultado_planEstudio);$i++)
            {
                $varCargado=array($resultado_planEstudio[$i][0],$resultado_planEstudio[$i][3]);
                $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_cargado", $varCargado);echo $cadena_sql;exit;
                $resultado_cargado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if(is_array($resultado_cargado))
                    {
                        $cargados[$c][0]=$resultado_planEstudio[$i][0];//Codigo del espacios academico
                        $cargados[$c][1]=$resultado_cargado[0][0];//Carrera
                        $cargados[$c][2]=$resultado_cargado[0][1];//Plan Estudio
                        $cargados[$c][3]=$resultado_planEstudio[$i][2];//Nivel
                        $c++;
                    }
            }*/

        $cadena_sql=$this->sql->cadena_sql($configuracion,"parametros_plan", $planEstudio);
        $resultado_parametros=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        $varInscritos=array($codigoEstudiante,$ano[0],$ano[1]);

        $creditosInscritos=0;

        $cadena_sql=$this->sql->cadena_sql($configuracion,"asignaturasInscritas", $varInscritos);
        $resultado_asignaturas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if(is_array($resultado_asignaturas))
            {
                for($o=0;$o<count($resultado_asignaturas);$o++)
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"creditosInscritos", $resultado_asignaturas[$o][0]);
                    $resultado_creditos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $creditosInscritos+=$resultado_creditos[0][0];
                }
            }

        

        $numeroCreditosRestantes=$resultado_parametros[0][3] - $creditosInscritos;

        //echo $numeroCreditosRestantes;exit;
        ?><table width="70%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];


                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br><b>Horario<br>Estudiante</b>
            </a>
        </td>
    </tr>
</table>
<table class='sigma contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
  <th class="sigma_a centrar">ELECTIVAS EXTR&Iacute;NSECAS OFRECIDAS</th>

    <table class='sigma contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
    
            <?$facultadAnterior=0;
                    for($i=0;$i<count($resultado_planEstudio);$i++) {
                        $band = '0';
                        //echo "<br>".$cargados[$i][0]."-".$cargados[$i][1]."-".$cargados[$i][2];
                        $cadena_sql_espacio=$this->sql->cadena_sql($configuracion,"nombre_espacio", $resultado_planEstudio[$i][0]);
                        $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );

                        $ofrece=array($resultado_planEstudio[$i][0],$resultado_planEstudio[$i][4],$resultado_planEstudio[$i][3]);
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"carrera_ofrece",$ofrece);
                        $resultado_carrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        if($resultado_carrera[0][1]!=$facultadAnterior)
                            {
                            ?>
                            <thead class='cuadro_color centrar'>
                                <tr>
                                  <td class="sigma_a cuadro_plano centrar" colspan="6"><b><?echo $resultado_carrera[0][2]?></b></td>
                                </tr>
                                <tr>
                                    <th class="sigma centrar">Carrera Ofrece</th>
                                    <th class="sigma centrar">Codigo Espacio</th>
                                    <th class="sigma centrar">Nombre Espacio</th>
                                    <th class="sigma centrar">Clasificaci&oacute;n</th>
                                    <th class="sigma centrar">Nro Cr&eacute;ditos</th>
                                    <th class="sigma centrar">Adicionar</th>
                                </tr>
                            </thead>
                            <?$facultadAnterior=$resultado_carrera[0][1];
                            }

                        $varInscritas=array($resultado_planEstudio[$i][0],$ano[0],$ano[1],$codigoEstudiante);
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarInscrita",$varInscritas);
                        $resultado_Inscrita=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        if($resultado_espacio!='') {

                            $requisito=array($planEstudio, $resultado_planEstudio[$i][0]);
                            $cadena_requisito=$this->sql->cadena_sql($configuracion,"requisitos", $requisito);
                            $resultado_requisito=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_requisito,"busqueda" );

                            $otro_requisito=array($planEstudio, $resultado_requisito[0][2]);
                            $cadena_otroRequisito=$this->sql->cadena_sql($configuracion,"otroRequisito", $otro_requisito);
                            $resultado_otroRequisito= $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_otroRequisito,"busqueda" );

                            if ($resultado_otroRequisito[0][0]=='2') {

                                $cadena_requisitoUno=$this->sql->cadena_sql($configuracion,"requisitoUno", $otro_requisito);
                                $resultado_requisitoUno= $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_requisitoUno,"busqueda" );

                                if ($resultado_requisitoUno[0][0]=='1') {
                                    $aprobado=array($resultado_requisito[0][1],$codigoEstudiante);
                                    $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,"curso_aprobado", $aprobado);
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
                                                $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,"curso_aprobado", $aprobado);
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
                                                $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,"curso_aprobado", $aprobado);
                                                $resultado_aprobado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );
                                                if ($resultado_aprobado[0][0]>="50") {
                                                    $band = '0';
                                                }
                                                break;
                                            }
                                    }
                                }
                                if(!is_array($resultado_Inscrita))
                                    {
                            if ($band == '0') {
//                                            $infoEspacio=array($codProyecto, $resultado_planEstudio[$i][0], $planEstudioGeneral);
//                                            $cadena_sql=$this->sql->cadena_sql($configuracion,"infoEspacio", $infoEspacio);//echo $cadena_sql;exit;
//                                            $resultado_clasif=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                ?> <tr>
                                    <td class='cuadro_plano centrar'><? echo $resultado_carrera[0][0]?></td><!--Carrera que la ofrece-->
                                        <td class='cuadro_plano centrar'><? echo $resultado_planEstudio[$i][0]?></td><!--Codigo del espacio -->
                                        <td class='cuadro_plano '><? echo $resultado_espacio[0][0];?></td> <!--Nombre del Espacio -->
                                        <td class='cuadro_plano centrar'><? echo $resultado_planEstudio[$i][2]?></td>
                                        <?//PA
                                        //if($_REQUEST['estado_est']=='B')
                                            //{
                                              ?><!--  <td class='cuadro_plano centrar'><font color="#F90101"><? //echo $resultado_espacio[0][1]?></font></td>
                                                <td class='cuadro_plano centrar'>
                                                    No puede adicionar, El estudiante se encuentra en prueba académica
                                                </td>
                                                </tr>--><?
                                            //}else
                                              if($resultado_espacio[0][1]<=$numeroCreditosRestantes)
                                                {
                                        ?><td class='cuadro_plano centrar'><font color="#3BAF29"><? echo $resultado_espacio[0][1]?></font></td>
                                        <td class='cuadro_plano centrar'>

            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                <input type="hidden" name="espacio" value="<?echo $resultado_planEstudio[$i][0]?>">
                <input type="hidden" name="clasificacion" value="<?echo $resultado_planEstudio[$i][1]?>">
                <input type="hidden" name="nombre" value="<?echo $resultado_espacio[0][0]?>">
                <input type="hidden" name="opcion" value="adicionar">
                <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                <input type="hidden" name="creditos" value="<?echo $resultado_espacio[0][1]?>">
                <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                <input type="hidden" name="carrera" value="<?echo $carrera?>">
                <input type="hidden" name="ano" value="<?echo $ano[0].$ano[1]?>">
                <input type="hidden" name="planEstudioGeneral" value="<?echo $_REQUEST["planEstudioGeneral"]?>">
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST["codProyecto"]?>">
                <input type="hidden" name="estado_est" value="<?echo $_REQUEST["estado_est"]?>">
                <input type="hidden" name="action" value="<?echo $this->bloque;?>">
                <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >
            </form>
        </td>
    </tr>
                                <?
                                }else if($resultado_espacio[0][1]>$numeroCreditosRestantes) {
                                        ?>
    <td class='cuadro_plano centrar'><font color="#F90101"><? echo $resultado_espacio[0][1]?></font></td>
    <td class='cuadro_plano centrar'>
        No puede adicionar, El n&uacute;mero de cr&eacute;ditos inscritos supera los <?echo $resultado_parametros[0][3];?>
    </td>
    </tr>
                                    <?

                                    }
                            }

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
            $variablesPag="pagina=adminConsultarInscripcionEstudianteCoordinador";
            $variablesPag.="&opcion=mostrarConsulta";
            $variablesPag.="&codEstudiante=".$_REQUEST["codEstudiante"];
            $variablesPag.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
            $variablesPag.="&planEstudio=".$_REQUEST["planEstudioGeneral"];
            $variablesPag.="&codProyecto=".$_REQUEST["codProyecto"];

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

    function buscarGrupo($configuracion) 
    {
        $cadena_sql=$this->sql->cadena_sql($configuracion,"ano_periodo", '');
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $ano=$resultado_periodo[0][0];
        $periodo=$resultado_periodo[0][1];

        $varibleCancelado=array($_REQUEST['codEstudiante'],$_REQUEST['espacio'],$ano,$periodo);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_canceladoPeriodo", $varibleCancelado);
        $resultado_cancelado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        if(is_array($resultado_cancelado))
            {
                ?>
<script type="text/javascript">
    if(confirm('El espacio académico ya fue cancelado el presente periodo.\n¿Desea adicionarlo de nuevo?'))
    {

    }else
    {<?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=adminConsultarInscripcionEstudianteCoordinador";
            $ruta.="&opcion=mostrarConsulta";
            $ruta.="&codEstudiante=".$_REQUEST["codEstudiante"];
            $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
            $variable.="&codProyecto=".$_REQUEST["codProyecto"];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
      ?>
              location.replace('<?echo $pagina.$ruta?>');
    }
</script>
                <?
            }

        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $espacio=$_REQUEST['espacio'];
        $clasificacion=$_REQUEST['clasificacion'];
        $planEstudio=$_REQUEST['planEstudio'];
        $carrera=$_REQUEST['carrera'];
        $creditos=$_REQUEST['creditos'];
        $nombre=$_REQUEST['nombre'];
        $codProyecto=$_REQUEST['carrera'];
        //var_dump($_REQUEST);exit;
        $variables=array($espacio,$carrera,$planEstudio, $ano, $periodo);

        $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,"grupos_proyecto", $variables);
        $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

        if($resultado_grupos==NULL)
               {
                switch ($carrera)
                    {
                        case "472": $carrera='72';break;
                        case "473": $carrera='73';break;
                        case "474": $carrera='74';break;
                        case "477": $carrera='77';break;
                        case "478": $carrera='78';break;
                        case "479": $carrera='79';break;
                        case "481": $carrera='81';break;
                        case "485": $carrera='85';break;
                    }

                        $variables=array($espacio,$carrera,$planEstudio,$ano, $periodo);

                        $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,"grupos_proyecto", $variables);
                        $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );
               }

        ?>
<table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar" width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarEEEstudianteCoordinador";
                    $variable.="&opcion=adicionar";
                    $variable.="&id_grupo=".(isset($_REQUEST["id_grupo"])?$_REQUEST["id_grupo"]:'');
                    $variable.="&grupo=".(isset($_REQUEST["grupo"])?$_REQUEST["grupo"]:'');
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos del<br>Proyecto Curricular</b>
            </a>

            <td class="centrar" width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarEEEstudianteCoordinador";
                    $variable.="&opcion=otrosGrupos";
                    $variable.="&id_grupo=".(isset($_REQUEST["id_grupo"])?$_REQUEST["id_grupo"]:'');
                    $variable.="&grupo=".(isset($_REQUEST["grupo"])?$_REQUEST["grupo"]:'');
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];

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
          <?echo $espacio." - ".$nombre;?>
      </th>
        <?
        if($resultado_grupos!=NULL) {
            ?>
<table width="100%" border="0" align="center" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px">
                  <tr><td class='sigma_a centrar'><b>
                    <?echo "PROYECTO CURRICULAR: ".$codProyecto?></b></td></tr>
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
                                                $cruce=0;

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

                                                ?><tr><td class='cuadro_plano centrar'><?echo $resultado_grupos[$j]['GRUPO'];?></td><?
                                                    for($i=1; $i<8; $i++) {
                                                        ?><td class='cuadro_plano centrar'><?
                                                             for ($k = 0; $k < count($resultado_horarios); $k++) {

                                                                    if ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                                                                        $l = $k;
                                                                        while ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                                                                            $m = $k;
                                                                            $m++;
                                                                            $k++;
                                                                        }
                                                                        $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$l]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$l]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$l]['ID_SALON'] . " " . $resultado_horarios[$l]['NOM_SALON'];
                                                                        echo $dia . "<br>";
                                                                        unset($dia);
                                                                    } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '')) {
                                                                        $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                                                                        echo $dia . "<br>";
                                                                        unset($dia);
                                                                        $k++;
                                                                    } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                                                                        $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                                                                        echo $dia . "<br>";
                                                                        unset($dia);
                                                                    } elseif ($resultado_horarios[$k]['DIA'] != $i) {                        }

                                                                }
                                                            ?></td><?
                                                        }
                                                    ?><td class='cuadro_plano centrar'><?echo $cupoDisponible?></td>
                                    <td class='cuadro_plano centrar'<?if($cupoDisponible<='0' || $cruce==true) {?>bgColor='#F8E0E0'<?}?>>

                                                        <?
                                                                if($cruce!=true){
                                                                    if($cupoDisponible>0) {
                                                            ?>
                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>

                                            <input type="hidden" name="id_grupo" value="<?echo $resultado_grupos[$j]['CUR_ID']?>">
                                            <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j]['GRUPO']?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                            <input type="hidden" name="espacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="clasificacion" value="<?echo $_REQUEST['clasificacion']?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="carrera" value="<?echo $variables[1]?>">
                                            <input type="hidden" name="creditos" value="<?echo $_REQUEST['creditos']?>">
                                            <input type="hidden" name="nombre" value="<?echo $_REQUEST['nombre']?>">
                                            <input type="hidden" name="planEstudioGeneral" value="<?echo $_REQUEST["planEstudioGeneral"]?>">
                                            <input type="hidden" name="codProyecto" value="<?echo $_REQUEST["codProyecto"]?>">
                                            <input type="hidden" name="estado_est" value="<?echo $_REQUEST["estado_est"]?>">
                                            <input type="hidden" name="opcion" value="inscribir">
                                            <input type="hidden" name="action" value="<?echo $this->bloque;?>">
                                            <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >

                                        </form>
                                    </td>

                                                    <?
                                                    }else
                                                        {
                                                        ?>No puede adicionar por cupo</td><?
                                                        }
                                                                }
                                                    else
                                                        {
                                                        ?>No puede adicionar por cruce</td><?
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
        }else {
            ?>
<tr>
    <td class="cuadro_plano centrar">
      No existen grupos registrados en el Proyecto.<br><font size="2" color="red">Si est&aacute; adicionando espacios acad&eacute;micos Electivos Extr&iacute;nsecos<br>por favor consulte Grupos en otros Proyectos Curriculares.</font>
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
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br><b>Horario<br>Estudiante</b>
            </a>

        <td class="centrar" width="50%">
                    <?
                    $cadena_sql_plan=$this->sql->cadena_sql($configuracion,"datosCoordinador", $this->usuario);
                    $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarEEEstudianteCoordinador";
                    $variable.="&opcion=espacios";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&estado_est=".$_REQUEST["estado_est"];
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];

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

    function buscarOtrosGrupos($configuracion)
    {
        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $espacio=$_REQUEST['espacio'];
        $clasificacion=$_REQUEST['clasificacion'];
        $planEstudio=$_REQUEST['planEstudio'];
        $carrera=$_REQUEST['carrera'];
        $creditos=$_REQUEST['creditos'];
        $nombre=$_REQUEST['nombre'];

         $codProyecto=$_REQUEST['carrera'];
        //var_dump($_REQUEST);exit;
        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );
        $ano=$resultado_periodo[0][0];
        $periodo=$resultado_periodo[0][1];

        $variables=array($espacio,$carrera,$planEstudio, $ano, $periodo);

        $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,"grupos_proyecto", $variables);
        $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

        if($resultado_grupos==NULL)
               {
                switch ($carrera)
                    {
                        case "472": $carrera='72';break;
                        case "473": $carrera='73';break;
                        case "474": $carrera='74';break;
                        case "477": $carrera='77';break;
                        case "478": $carrera='78';break;
                        case "479": $carrera='79';break;
                        case "481": $carrera='81';break;
                        case "485": $carrera='85';break;
                    }

                        $variables=array($espacio,$carrera,$planEstudio,$ano, $periodo);

                        $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,"grupos_proyecto", $variables);
                        $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );
               }

        $cadena_sql_carreras=$this->sql->cadena_sql($configuracion,"buscar_carrerasAbiertas", $variables);
        $resultadoAdicionesAbiertas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_carreras,"busqueda" );
        
        ?>
<table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class='centrar' width="50%" >
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarEEEstudianteCoordinador";
                    $variable.="&opcion=adicionar";
                    $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos del<br>Proyecto Curricular</b>
            </a>

        <td class='centrar' width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarEEEstudianteCoordinador";
                    $variable.="&opcion=otrosGrupos";
                    $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];

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
            {$cuenta=0;
            ?>
                        <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="0px" >
                    <th class='sigma_a centrar'>
                        <?echo $espacio." - ".$nombre?>
                    </th>
                </table>
<?
        for($p=0;$p<count($resultadoAdicionesAbiertas);$p++){

            $variables[5]=$resultadoAdicionesAbiertas[$p][0];
            $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,"otros_grupos", $variables);
            $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );
            
        if($resultado_grupos!=NULL) {
            ?>
<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                  <tr><td class='sigma_a centrar'><b>
                    <?echo "PROYECTO CURRICULAR: ".$variables[5]?></b>
                    </td></tr>
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

                                                $variables[5]=$resultado_grupos[$j][0];

                                                $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_otros_grupos", $variables);
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );
            
                                                
                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_otrosgrupos_registrar", $variables);
                                                $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableCodigo[0]=$codigoEstudiante;
                                                $variableCodigo[4]=$ano;
                                                $variableCodigo[5]=$periodo;

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variableCodigo);
                                                $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                unset($cruce);
                                                $cruce=0;

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
                                                        ?><td class='cuadro_plano centrar'><?
                                                             for ($k = 0; $k < count($resultado_horarios); $k++) {

                                                                if ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                                                                    $l = $k;
                                                                    while ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                                                                        $m = $k;
                                                                        $m++;
                                                                        $k++;
                                                                    }
                                                                    $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$l]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$l]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$l]['ID_SALON'] . " " . $resultado_horarios[$l]['NOM_SALON'];
                                                                    echo $dia . "<br>";
                                                                    unset($dia);
                                                                } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '')) {
                                                                    $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                                                                    echo $dia . "<br>";
                                                                    unset($dia);
                                                                    $k++;
                                                                } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                                                                    $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                                                                    echo $dia . "<br>";
                                                                    unset($dia);
                                                                } elseif ($resultado_horarios[$k]['DIA'] != $i) {                        }

                                                            }
                                                            ?></td><?
                                                        }
                                                    ?><td class='cuadro_plano centrar'><?echo $cupoDisponible?></td>
                                    <td class='cuadro_plano centrar'<?if($cupoDisponible<='0' || $cruce==true) {?>bgColor='#F8E0E0'<?}?>>

                                                        <?
                                                        if($cupoDisponible>0) {

                                                            if($cruce!=true){
                                                            ?>

                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>

                                            <input type="hidden" name="id_grupo" value="<?echo $resultado_grupos[$j][0]?>">
                                            <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j][1]?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                            <input type="hidden" name="espacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="clasificacion" value="<?echo $_REQUEST['clasificacion']?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="carrera" value="<?echo $variables[1]?>">
                                            <input type="hidden" name="creditos" value="<?echo $_REQUEST['creditos']?>">
                                            <input type="hidden" name="nombre" value="<?echo $_REQUEST['nombre']?>">
                                            <input type="hidden" name="planEstudioGeneral" value="<?echo $_REQUEST["planEstudioGeneral"]?>">
                                            <input type="hidden" name="codProyecto" value="<?echo $_REQUEST["codProyecto"]?>">
                                            <input type="hidden" name="estado_est" value="<?echo $_REQUEST["estado_est"]?>">
                                            <input type="hidden" name="opcion" value="inscribir">
                                            <input type="hidden" name="action" value="<?echo $this->bloque;?>">
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
                }else
                  {
                    $cuenta++;
                  }

                }
                if ($cuenta==$p){
                    ?>
                    <tr>
                        <td class="cuadro_plano centrar">
                        En el momento no existen grupos registrados en otros Proyectos
                        </td>
                    </tr>
                    <?

                }
                }
                else {
                    ?>
                    <tr>
                        <td class="cuadro_plano centrar">
                        En el momento no existen grupos registrados en otros Proyectos
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
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br><b>Horario<br>Estudiante</b>
            </a>

        <td class="centrar" width="50%">
                    <?
                    $cadena_sql_plan=$this->sql->cadena_sql($configuracion,"datosCoordinador", $this->usuario);
                    $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarEEEstudianteCoordinador";
                    $variable.="&opcion=espacios";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&estado_est=".$_REQUEST["estado_est"];
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];

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

    function inscribirCredito($configuracion)
    {

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $retorno['pagina']="adminConsultarInscripcionEstudianteCoordinador";
        $retorno['opcion']="mostrarConsulta";
        $retorno['parametros']="&codEstudiante=".$_REQUEST['codEstudiante'];
        $retorno['parametros'].="&codProyecto=".$_REQUEST['carrera'];
        $retorno['parametros'].="&planEstudioGeneral=".$_REQUEST['planEstudio'];
        $retorno['parametros'].="&planEstudio=".$_REQUEST['planEstudio'];
        $this->validacion->verificarRangos($configuracion, $_REQUEST['planEstudio'], $_REQUEST['espacio'], $_REQUEST['codEstudiante'], $_REQUEST['clasificacion'], $retorno);
        
        $var_espacio=array($_REQUEST['espacio'],$_REQUEST['planEstudio']);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_planEstudio", $var_espacio);
        $resultado_datosEspacio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        $variables[0]=$_REQUEST['codEstudiante'];//*
        $variables[1]=$_REQUEST['id_grupo'];
        $variables[2]=$_REQUEST['espacio'];//*
        $variables[3]=$_REQUEST['carrera'];//*
        $variables[4]=$ano[0];
        $variables[5]=$ano[1];
        $variables[6]=$_REQUEST['planEstudio'];//*
        $variables[7]=$resultado_datosEspacio[0][0];//Creditos E.A.
        $variables[8]=$resultado_datosEspacio[0][1];//H.T.D del E.A.
        $variables[9]=$resultado_datosEspacio[0][2];//H.T.C del E.A.
        $variables[10]=$resultado_datosEspacio[0][3];//H.T.A del E.A.
        $variables[11]=$_REQUEST['clasificacion'];//Clasificacion E.A.*
        $variables[12]=0;//nivel-semestre
        
        //creditos
        //planEstudioGeneral
        //codProyecto
       
        $variableInscripcion=$variables;
        $cadena_sql=$this->sql->cadena_sql($configuracion,"carrera_estudiante", $variables);
        $resultado_carreraEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $variableInscripcion[3]=$resultado_carreraEstudiante[0]['CARRERA_ESTUDIANTE'];

        $cadena_sql_buscarEspacioOracle=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variableInscripcion);
        $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_buscarEspacioOracle,"busqueda" );

        $cadena_sql_buscarEspacioMysql=$this->sql->cadena_sql($configuracion,"buscar_espacio_mysql", $variableInscripcion);
        $resultado_EspacioMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEspacioMysql,"busqueda" );

        if ($resultado_EspacioOracle =='') {

            $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,"horario_registrado", $variableInscripcion);
            $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"busqueda" );

            $cadena_sql_horario_grupo_nuevo=$this->sql->cadena_sql($configuracion,"horario_grupo_nuevo", $variableInscripcion);
            $resultado_horario_grupo_nuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_grupo_nuevo,"busqueda" );

            for($i=0;$i<count($resultado_horario_registrado);$i++) {
                for($j=0;$j<count($resultado_horario_grupo_nuevo);$j++) {

                    if(($resultado_horario_grupo_nuevo[$j])==($resultado_horario_registrado[$i])) {
                        echo "<script>alert ('El horario del grupo seleccionado presenta cruce con el horario que tiene inscrito el estudiante');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                        $variable.="&opcion=mostrarConsulta";
                        $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                        $variable.="&grupo=".$_REQUEST["grupo"];
                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                        $variable.="&espacio=".$_REQUEST["espacio"];
                        $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                        $variable.="&carrera=".$_REQUEST["carrera"];
                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                        $variable.="&creditos=".$_REQUEST["creditos"];
                        $variable.="&nombre=".$_REQUEST["nombre"];
                        $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                        $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }
                }
            }

            $cadena_sql_cupo_grupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_cupo", $variables);
            $resultado_cupo_grupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupo_grupo,"busqueda" );

            $cadena_sql_cupo_inscritos=$this->sql->cadena_sql($configuracion,"cupo_grupo_ins", $variables);
            $resultado_cupo_inscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupo_inscritos,"busqueda" );

            if($resultado_cupo_inscritos[0][0]<=$resultado_cupo_grupo[0][0] || $this->nivel=28) {

                $varInscritos=array($_REQUEST['codEstudiante'],$ano[0],$ano[1]);

                $creditosInscritos=0;

                $cadena_sql=$this->sql->cadena_sql($configuracion,"asignaturasInscritas", $varInscritos);
                $resultado_asignaturas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if(is_array($resultado_asignaturas))
                    {
                        for($o=0;$o<count($resultado_asignaturas);$o++)
                        {
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"creditosInscritos", $resultado_asignaturas[$o][0]);
                            $resultado_creditos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            $creditosInscritos+=$resultado_creditos[0][0];
                        }
                    }

                $cadena_sql_numCreditos=$this->sql->cadena_sql($configuracion,"numero_creditos", $variableInscripcion);
                $resultado_numCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_numCreditos,"busqueda" );

                //echo $cadena_sql_numCreditos;
                $creditos=($creditosInscritos+$_REQUEST['creditos']);
                //echo $creditos;
                if ($creditos<='18') {
                    
                    $cadena_sql_adicionarMysql=$this->sql->cadena_sql($configuracion,"adicionar_espacio_mysql", $variableInscripcion);
                    $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_adicionarMysql,"" );
                    
                    if($resultado_adicionarMysql==true)
                        {
                            $cadena_sql_adicionar=$this->sql->cadena_sql($configuracion,"adicionar_espacio_oracle", $variableInscripcion);
                            $resultado_adicionar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_adicionar,"" );
                            
                            if($resultado_adicionar==true)
                                {
                                        //$creditos=($resultado_numCreditos[0][0]+$_REQUEST['creditos']);
                                    $variables[6]=$creditos;

                                    $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,"actualizar_creditos", $variableInscripcion);
                                    $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );

                                    $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,"actualizar_cupo", $variables);
                                    $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

                                    $variablesRegistro=array($this->usuario,date('YmdGis'),'1','Adiciona Espacio académico',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['id_grupo'].", ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$resultado_cupo_grupo[0]['CARRERA'],$_REQUEST['codEstudiante']);

                                    $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                                    $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                    $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
                                    $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                    //echo "<script>alert ('Usted registro el espacio académico.Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";
                                    echo "<script>alert ('Registro Exitoso. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                    $variable.="&opcion=mostrarConsulta";
                                    $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                                    $variable.="&grupo=".$_REQUEST["grupo"];
                                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                    $variable.="&espacio=".$_REQUEST["espacio"];
                                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                    $variable.="&carrera=".$_REQUEST["carrera"];
                                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    exit;
                                }
                                else
                                    {
                                        echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                                        $cadena_sql=$this->sql->cadena_sql($configuracion,"borrar_datos_mysql_no_conexion", $variables);
                                        $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                                        $variablesRegistro=array($this->usuario,date('YmdGis'),'50','Conexion Error Oracle',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['id_grupo'].", ".$_REQUEST['planEstudio'].", ".$resultado_cupo_grupo[0]['CARRERA'],$_REQUEST['codEstudiante']);

                                        $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                        $variable.="&opcion=mostrarConsulta";
                                        $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                                        $variable.="&grupo=".$_REQUEST["grupo"];
                                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                        $variable.="&espacio=".$_REQUEST["espacio"];
                                        $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                        $variable.="&carrera=".$_REQUEST["carrera"];
                                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                        $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                                        $variable.="&codProyecto=".$_REQUEST["codProyecto"];

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

                                        $cadena_sql=$this->sql->cadena_sql($configuracion,"borrar_datos_mysql_no_conexion", $variables);
                                        $resultado_adicionarMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                                        $variablesRegistro=array($this->usuario,date('YmdGis'),'51','Conexion Error MySQL',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['id_grupo'].", ".$_REQUEST['planEstudio'].", ".$resultado_cupo_grupo[0]['CARRERA'],$_REQUEST['codEstudiante']);

                                        $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                        $variable.="&opcion=mostrarConsulta";
                                        $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                                        $variable.="&grupo=".$_REQUEST["grupo"];
                                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                        $variable.="&espacio=".$_REQUEST["espacio"];
                                        $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                        $variable.="&carrera=".$_REQUEST["carrera"];
                                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                        $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                                        $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                        exit;
                                    }
                 }
                else {

                        echo "<script>alert ('El número de creditos es mayor al permitido por semestre. No se ejecuta la adición');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                        $variable.="&opcion=mostrarConsulta";
                        $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                        $variable.="&grupo=".$_REQUEST["grupo"];
                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                        $variable.="&espacio=".$_REQUEST["espacio"];
                        $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                        $variable.="&carrera=".$_REQUEST["carrera"];
                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                        $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                        $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                                

                    }

            }else {
                echo "<script>alert ('Este curso tiene el máximo número de inscritos, no puede adicionar ');</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                $variable.="&opcion=mostrarConsulta";
                $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                $variable.="&grupo=".$_REQUEST["grupo"];
                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                $variable.="&espacio=".$_REQUEST["espacio"];
                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                $variable.="&carrera=".$_REQUEST["carrera"];
                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
            }
        }
        else {
            echo "<script>alert ('Este espacio ya había sido registrado y no puede adicionarse nuevamente');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&id_grupo=".$_REQUEST["id_grupo"];
            $variable.="&grupo=".$_REQUEST["grupo"];
            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
            $variable.="&espacio=".$_REQUEST["espacio"];
            $variable.="&clasificacion=".$_REQUEST["clasificacion"];
            $variable.="&carrera=".$_REQUEST["carrera"];
            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
            $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
            $variable.="&codProyecto=".$_REQUEST["codProyecto"];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }

    }

    function consultarElectivasPermitidos($configuracion)
    {
        if($_REQUEST['codEstudiante']==NULL) {
            $codigoEstudiante=$this->usuario;

        }else {
            $codigoEstudiante=$_REQUEST['codEstudiante'];

        }

        $cadena_sql_plan=$this->sql->cadena_sql($configuracion,"datosCoordinador", $this->usuario);
        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

         $cadena_sql_plan=$this->sql->cadena_sql($configuracion,"plan_estudio", $codigoEstudiante);
        $resultado_planEst=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );
        $planEstudioEst=$resultado_planEst[0][0];
        $carreraEst=$resultado_planEst[0][1];

        $planEstudio=$resultado_plan[0][0];
        $carrera=$resultado_plan[0][1];

        $permitidos=array($planEstudio,$codigoEstudiante);

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        $variables=array($codigoEstudiante,$planEstudio,$carrera,$permitidos,$ano[0],$ano[1]);

        $cadena_sql_planEstudio=$this->sql->cadena_sql($configuracion,"espacios_plan_estudio", $permitidos);echo $cadena_sql_planEstudio;exit;
        $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_planEstudio,"busqueda" );

        $cadena_sql_parametros=$this->sql->cadena_sql($configuracion,"parametros_plan", $planEstudio);
        $resultado_parametros=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_parametros,"busqueda" );

        $cadena_sql_numCreditos=$this->sql->cadena_sql($configuracion,"numero_creditos", $variables);
        $resultado_numCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_numCreditos,"busqueda" );

        if($resultado_numCreditos==NULL) {
            $cadena_sql_numCreditosRegistro=$this->sql->cadena_sql($configuracion,"crear_creditos", $variables);
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
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];

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

                        $cadena_sql_espacio=$this->sql->cadena_sql($configuracion,"nombre_espacio", $resultado_planEstudio[$i][0]);
                        $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );

                        if($resultado_espacio!='') {

                            $requisito=array($planEstudio, $resultado_planEstudio[$i][0]);
                            $cadena_requisito=$this->sql->cadena_sql($configuracion,"requisitos", $requisito);
                            $resultado_requisito=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_requisito,"busqueda" );

                            $otro_requisito=array($planEstudio, $resultado_requisito[0][2]);
                            $cadena_otroRequisito=$this->sql->cadena_sql($configuracion,"otroRequisito", $otro_requisito);
                            $resultado_otroRequisito= $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_otroRequisito,"busqueda" );

                            if ($resultado_otroRequisito[0][0]=='2') {

                                $cadena_requisitoUno=$this->sql->cadena_sql($configuracion,"requisitoUno", $otro_requisito);
                                $resultado_requisitoUno= $this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_requisitoUno,"busqueda" );

                                if ($resultado_requisitoUno[0][0]=='1') {
                                    $aprobado=array($resultado_requisito[0][1],$codigoEstudiante);
                                    $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,"curso_aprobado", $aprobado);
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
                                                $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,"curso_aprobado", $aprobado);
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
                                                $cadena_sql_curso_aprobado=$this->sql->cadena_sql($configuracion,"curso_aprobado", $aprobado);
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
                                        $cadena_sql_espacio=$this->sql->cadena_sql($configuracion,"nombre_espacio", $resultado_planEstudio[$i][0]);
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
                <input type="hidden" name="ano" value="<?echo $ano?>">
                <input type="hidden" name="planEstudioGeneral" value="<?echo $_REQUEST["planEstudioGeneral"]?>">
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST["codProyecto"]?>">
                <input type="hidden" name="action" value="<?echo $this->bloque;?>">
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
            <input type="hidden" name="ano" value="<?echo $ano?>">
            <input type="hidden" name="planEstudioGeneral" value="<?echo $_REQUEST["planEstudioGeneral"]?>">
            <input type="hidden" name="codProyecto" value="<?echo $_REQUEST["codProyecto"]?>">
            <input type="hidden" name="action" value="<?echo $this->bloque;?>">
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
            $variablesPag="pagina=adminConsultarInscripcionEstudianteCoordinador";
            $variablesPag.="&opcion=mostrarConsulta";
            $variablesPag.="&codEstudiante=".$_REQUEST["codEstudiante"];
            $variablesPag.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
            $variablesPag.="&planEstudio=".$_REQUEST["planEstudioGeneral"];
            $variablesPag.="&codProyecto=".$_REQUEST["codProyecto"];

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