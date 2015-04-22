<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroAdicionarInscripcionEstudianteCoordinador extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
        private $configuracion;
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacionInscripcion.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
        

//        $this->administrar=new administrarModulo();
//        $this->administrar->administrarModuloSGA($configuracion, '4');

        $this->validacion=new validacionInscripcion();
        $this->validacion2=new validarInscripcion();
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroAdicionarInscripcionEstudianteCoordinador";
        $this->bloque="inscripcion/registroAdicionarInscripcionEstudianteCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->configuracion=$configuracion;
        
        //Conexion ORACLE
        //
        if($this->nivel==4){
        	$this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");
        }elseif($this->nivel==110){
        	$this->accesoOracle=$this->conectarDB($configuracion,"asistente");
        }

    }


    function consultarEspaciosPermitidos()
    {
        
        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $codProyecto=$_REQUEST['codProyecto'];
        $estado_est=$_REQUEST['estado_est'];

        $cadena_sql_plan=$this->sql->cadena_sql($this->configuracion,"plan_estudio", $codigoEstudiante);
        $resultado_plan=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );
        $planEstudio=$resultado_plan[0][0];
        $carrera=$resultado_plan[0][1];

        $permitidos=array($planEstudio,$codigoEstudiante);

        $cadena_sql_periodo=$this->sql->cadena_sql($this->configuracion,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        $variables=array($codigoEstudiante,$planEstudio,$carrera,$permitidos,$ano[0],$ano[1]);

        if(trim($estado_est)!='A'&& trim($estado_est)!='B')
            {
                echo "<script>alert('El estado del estudiante (".$resultado_plan[0][2].") no permite adicionar espacios académicos')</script>";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                $variable.="&opcion=mostrarConsulta";
                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                $variable.="&planEstudio=".$planEstudio;
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];

                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                echo "<script>location.replace('".$pagina.$variable."')</script>";
                exit;
            }

//*PRUEBA ACADEMICA*
//            if($estado_est=='B')
//            {
//                $cadena_sql_planEstudio=$this->sql->cadena_sql($this->configuracion,"espacios_plan_estudio_prueba", $permitidos);//echo $cadena_sql_planEstudio;exit;
//                $resultado_planEstudio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_planEstudio,"busqueda" );
//            }else
//                {
                    $cadena_sql_planEstudio=$this->sql->cadena_sql($this->configuracion,"espacios_plan_estudio", $permitidos);
                    $resultado_planEstudio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_planEstudio,"busqueda" );
//                }

        $cadena_sql_parametros=$this->sql->cadena_sql($this->configuracion,"parametros_plan", $planEstudio);
        $resultado_parametros=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_parametros,"busqueda" );

        $numeroCreditosRestantes=($resultado_parametros[0][3]-$_REQUEST['creditosInscritos']);
       
        ?><table width="70%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar">
                    <?                   
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&planEstudio=".$planEstudio;
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br>
                <b>Horario Estudiante</b>
            </a>
        </td>
    </tr>
</table>
<?if (is_array($resultado_planEstudio)){
  ?>
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
            $nivelAnterior=0;
                    for($i=0;$i<count($resultado_planEstudio);$i++) {
                        $band = '0';

                        $cadena_sql_espacio=$this->sql->cadena_sql($this->configuracion,"nombre_espacio", $resultado_planEstudio[$i][0]);
                        $resultado_espacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );

                        if(is_array($resultado_espacio)) {

                            $requisito=array($planEstudio, $resultado_planEstudio[$i][0]);
                            $cadena_otroRequisito=$this->sql->cadena_sql($this->configuracion,"otroRequisito", $requisito);
                            $resultado_otroRequisito= $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_otroRequisito,"busqueda" );
                            if ($resultado_otroRequisito[0][0]>0)
                            {
                                $cadena_requisito=$this->sql->cadena_sql($this->configuracion,"requisitos", $requisito);
                                $resultado_requisito=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_requisito,"busqueda" );
                                for ($a=0;$a<$resultado_otroRequisito[0][0];$a++)
                                {
                                    $aprobado=array($resultado_requisito[$a][1],$codigoEstudiante);
                                    $cadena_sql_curso_aprobado=$this->sql->cadena_sql($this->configuracion,"curso_aprobado", $aprobado);
                                    $resultado_aprobado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_curso_aprobado,"busqueda" );
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


                                if(trim($resultado_planEstudio[$i][2])!=$nivelAnterior)
                                    {
                                        $nivelAnterior=$resultado_planEstudio[$i][2];
                                        ?>
                                        <tr>
                                            <td class="sigma_a cuadro_plano centrar" colspan="6"><font size="2"> NIVEL <? echo $resultado_planEstudio[$i][2]?></font></td>
                                        </tr>
                                        <?
                                    }
                                ?> <tr>
                                     <td class='cuadro_plano centrar'><? echo $resultado_planEstudio[$i][0]?></td>
                                     <td class='cuadro_plano '>
                                        <?
                                            $cadena_sql_espacio=$this->sql->cadena_sql($this->configuracion,"nombre_espacio", $resultado_planEstudio[$i][0]);
                                            $resultado_espacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_espacio,"busqueda" );
                                            $infoEspacio=array($codProyecto, $resultado_planEstudio[$i][0], $planEstudioGeneral);
                                            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"infoEspacio", $infoEspacio);
                                            $resultado_clasif=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );


                                            echo $resultado_espacio[0][0];
                                        ?>
                                     </td>
                                    <td class='cuadro_plano centrar'>
                                            <? echo $resultado_clasif[0][1]?>
                                    </td>
                                    <?
                                    if($resultado_espacio[0][1]<=$numeroCreditosRestantes) {
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
                                                    <input type="hidden" name="nivel" value="<?echo $resultado_planEstudio[$i][2]?>">
                                                    <input type="hidden" name="clasificacion" value="<?echo $resultado_clasif[0][0]?>">
                                                    <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                                    <input type="hidden" name="creditos" value="<?echo $resultado_espacio[0][1]?>">
                                                    <input type="hidden" name="creditosInscritos" value="<?echo $_REQUEST['creditosInscritos']?>">
                                                    <input type="hidden" name="estado_est" value="<?echo $estado_est?>">
                                                    <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                                                    <input type="hidden" name="carrera" value="<?echo $carrera?>">
                                                    <input type="hidden" name="año" value="<?echo $ano[0]."-".$ano[1]?>">
                                                    <input type="hidden" name="opcion" value="validar">
                                                    <input type="hidden" name="action" value="<?echo $this->bloque?>">
                                                    <input type="image" name="adicion" width="30" height="30" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" >
                                                </form>
                                            </td>
                                          </tr>
                                            <?
                                        }else if($resultado_espacio[0][1]>$numeroCreditosRestantes) {
                                        ?>
                                            <td class='cuadro_plano centrar'>
                                                <font color="#F90101"><? echo $resultado_espacio[0][1]?></font>
                                            </td>
                                            <td class='cuadro_plano centrar'>
                                                No puede adicionar, El n&uacute;mero de cr&eacute;ditos inscritos supera los <?echo $resultado_parametros[0][3];?>
                                            </td>
                                          </tr>

                                        <?
                                        }
                                    }
                            }
                    }



            ?>
</table>
<table class="cuadro_color centrar" width="100%">
            <?
            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variablesPag="pagina=adminConsultarInscripcionEstudianteCoordinador";
            $variablesPag.="&opcion=mostrarConsulta";
            $variablesPag.="&codEstudiante=".$_REQUEST["codEstudiante"];
            $variablesPag.="&codProyecto=".$_REQUEST['codProyecto'];
            $variablesPag.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
            $variablesPag.="&planEstudio=".$planEstudio;

            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variablesPag=$this->cripto->codificar_url($variablesPag,$this->configuracion);

            ?>
    <tr class="centrar">
        <td colspan="3">
            <a href="<?= $pagina.$variablesPag ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/go-first.png" width="25" height="25" border="0"><br>
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
</table><?
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


?>


    <?
    }

    function buscarGrupo()
    {
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $codProyecto=$_REQUEST['codProyecto'];
        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $espacio=$_REQUEST['espacio'];
        $clasificacion=$_REQUEST['clasificacion'];
        $nivel=$_REQUEST['nivel'];
        $planEstudio=$_REQUEST['planEstudio'];
        $carrera=$_REQUEST['carrera'];
        $creditos=$_REQUEST['creditos'];
        $creditosInscritos=$_REQUEST['creditosInscritos'];
        $nombre=$_REQUEST['nombre'];

        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"datosCoordinador", $this->usuario);
        $resultado_craCoordinador=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

//        for($u=0;$u<count($resultado_craCoordinador);$u++)
//        {
//            if($codProyecto==$resultado_craCoordinador[$u][1])
//                {
                    $carreraRegistro=$carrera;
//                }else
//                    {
//                    $carreraRegistro=$resultado_craCoordinador[0][1];
//                    }
//
//        }
           $cadena_sql_periodo=$this->sql->cadena_sql($this->configuracion,"ano_periodo", "");
           $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );
           $ano=$resultado_periodo[0][0];
           $periodo=$resultado_periodo[0][1];

        $variables=array($espacio,$carreraRegistro,$planEstudio,$ano, $periodo);

        $cadena_sql_grupos=$this->sql->cadena_sql($this->configuracion,"grupos_proyecto", $variables);
        $resultado_grupos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

        if($resultado_grupos==NULL) {
            switch ($carrera) {
                case "472": $carreraRegistro='72';
                    break;
                case "473": $carreraRegistro='73';
                    break;
                case "474": $carreraRegistro='74';
                    break;
                case "477": $carreraRegistro='77';
                    break;
                case "478": $carreraRegistro='78';
                    break;
                case "479": $carreraRegistro='79';
                    break;
                case "481": $carreraRegistro='81';
                    break;
                case "485": $carreraRegistro='85';
                    break;
            }

            $variables=array($espacio,$carreraRegistro,$planEstudio, $ano, $periodo,$nivel);

            $cadena_sql_grupos=$this->sql->cadena_sql($this->configuracion,"grupos_proyecto", $variables);
            $resultado_grupos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );
        }

        ?>
<table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar" width="50%">
                    <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=adicionar";
                    if(isset($_REQUEST["grupo"])) $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&nivel=".$nivel;
                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos del Proyecto <br>Curricular <? echo $carrera;?></b>
            </a>

            <td class="centrar" width="50%">
                    <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=otrosGrupos";
                    if(isset($_REQUEST["grupo"])) $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&nivel=".$nivel;
                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos en otros<br>Proyectos Curriculares</b>
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
<table width="100%" border="0" align="center" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                  <tr><td class='sigma_a centrar'><b>
                    <?echo "PROYECTO CURRICULAR: ".$carrera?></b></td></tr>
                    <tr>
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

                                                $cadena_sql_horarios=$this->sql->cadena_sql($this->configuracion,"horario_grupos", $variables);
                                                $resultado_horarios=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                                                $cadena_sql=$this->sql->cadena_sql($this->configuracion,"horario_grupos_registrar", $variables);
                                                $resultado_horarios_registrar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableCodigo[0]=$codigoEstudiante;
                                                $variableCodigo[4]=$ano;
                                                $variableCodigo[5]=$periodo;

                                                $cadena_sql=$this->sql->cadena_sql($this->configuracion,"horario_registrado", $variableCodigo);
                                                $resultado_horarios_registrado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                                            
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

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($this->configuracion,"cupo_grupo_ins", $variableCupo);
                                                $resultado_cupoInscritos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($this->configuracion,"cupo_grupo_cupo", $variableCupo);
                                                $resultado_cupoGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

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
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
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
                                    <td class='cuadro_plano centrar'<?
                                    if(!isset($cruce)) $cruce =  false ;
                                    if($cupoDisponible<='0' || $cruce==true) {?>bgColor='#F8E0E0'<?}?>>

                                                        <?

                                                        
                                         if($cruce!=true){
                                            ?>
                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                            <input type="hidden" name="planEstudioGeneral" value="<?echo $planEstudioGeneral?>">
                                            <input type="hidden" name="id_grupo" value="<?echo $resultado_grupos[$j][0]?>">
                                            <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j][1]?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                            <input type="hidden" name="espacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="nivel" value="<?echo $nivel?>">
                                            <input type="hidden" name="clasificacion" value="<?echo $_REQUEST["clasificacion"]?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="carrera" value="<?echo $variables[1]?>">
                                            <input type="hidden" name="creditos" value="<?echo $_REQUEST['creditos']?>">
                                            <input type="hidden" name="creditosInscritos" value="<?echo $_REQUEST['creditosInscritos']?>">
                                            <input type="hidden" name="estado_est" value="<?echo $_REQUEST['estado_est']?>">
                                            <input type="hidden" name="opcion" value="inscribir">
                                            <input type="hidden" name="action" value="<?echo $this->bloque?>">
                                            <input type="image" name="adicion" width="30" height="30" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" >
                                        </form>
                                        
                                    </td>
                                                    <?
                                                    }
                                                    else
                                                        {
                                                        ?>No puede adicionar por cruce</td><?
                                                        }
                                                }
                                ?>
                </table>
            </td>

        </tr>
    <tr class="cuadro_plano centrar">
      <th colspan="2">
        <hr>Observaciones
      </th>
    </tr>
    <tr class="cuadro_plano">
      <td colspan="2">
        * Si el fondo del enlace est&aacute; en <font color="#F90101">rojo</font>, significa que el grupo presenta cruce o sobrecupo.
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
      No existen grupos registrados en el Proyecto.<br><font size="2" color="red">Por favor consulte Grupos en otros Proyectos Curriculares.</font>
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
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0">
                <br><b>Horario Estudiante</b>
            </a>
        </td>
        <td class="centrar" width="50%">
                    <?
                    $cadena_sql_plan=$this->sql->cadena_sql($this->configuracion,"datosCoordinador", $this->usuario);
                    $resultado_plan=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
                    if($resultado_plan[0][0]==291)
                        {
                            $variable.="&opcion=electivas";
                        }else
                            {
                            $variable.="&opcion=espacios";
                            }
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/reload.png" width="35" height="35" border="0"><br><b>Cambiar Espacio</b>
            </a>
        </td>
    </tr>
</table>

    <?


    }

    function inscribirCredito()
    {
    	
    	//validacion para asistentes
    	$codEstudiante = $_REQUEST['codEstudiante'];
    	$codAsistente =$this->usuario;
    	if($this->nivel==110){
			$valAsistente = $this->validacion2->validarProyectoAsistente($codEstudiante, $codAsistente);
			if($valAsistente!="ok")	{
				echo $valAsistente;
				exit;
				}
    		
    	}


    	
    	
        $variablesVerifica['codEstudiante']=$_REQUEST["codEstudiante"];
        $variablesVerifica['codEspacio']=$_REQUEST["espacio"];
        $variablesVerifica['clasificacion']=$_REQUEST["clasificacion"];
        $variablesVerifica['codProyecto']=$_REQUEST["carrera"];
        $variablesVerifica['planEstudio']=$_REQUEST["planEstudio"];
        $variablesVerifica['nroGrupo']=$_REQUEST["id_grupo"];
        $variablesVerifica['nroCreditos']=$_REQUEST["creditos"];
        $variablesVerifica['nivel']=$_REQUEST["nivel"];

        $cadena_sql_periodo=$this->sql->cadena_sql($this->configuracion,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $var_espacio=array($variablesVerifica['codEspacio'],$variablesVerifica['planEstudio']);
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"espacio_planEstudio", $var_espacio);
        $resultado_datosEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        $variables[0]=$_REQUEST['codEstudiante'];
        $variables[1]=$_REQUEST['id_grupo'];
        $variables[2]=$_REQUEST['espacio'];
        $variables[3]=$_REQUEST['carrera'];
        $variables[4]=$ano[0];
        $variables[5]=$ano[1];
        $variables[6]=$_REQUEST['planEstudio'];
        $variables[7]=$resultado_datosEspacio[0][0];//Creditos E.A.
        $variables[8]=$resultado_datosEspacio[0][1];//H.T.D del E.A.
        $variables[9]=$resultado_datosEspacio[0][2];//H.T.C del E.A.
        $variables[10]=$resultado_datosEspacio[0][3];//H.T.A del E.A.
        $variables[11]=$_REQUEST["clasificacion"];//Clasificacion E.A.
        $variables[12]=$_REQUEST["nivel"];//SEMESTRE O NIVEL

        $cadena_sql_buscarEspacioOracle=$this->sql->cadena_sql($this->configuracion,"buscar_espacio_oracle", $variables);
        $resultado_EspacioOracle=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_buscarEspacioOracle,"busqueda" );

        $retorno['pagina']="adminConsultarInscripcionEstudianteCoordinador";
        $retorno['opcion']="mostrarConsulta";
        $retorno['parametros']="&codEstudiante=".$variablesVerifica["codEstudiante"];
        $retorno['parametros'].="&codProyecto=".$variablesVerifica['codProyecto'];
        $retorno['parametros'].="&planEstudioGeneral=".$variablesVerifica['planEstudio'];
        $retorno['parametros'].="&planEstudio=".$variablesVerifica["planEstudio"];
        //valida si el estudiante no excede los creditos de la clasificacion del espacio
        $this->validacion->verificarRangos($this->configuracion, $variablesVerifica['planEstudio'], $variablesVerifica['codEspacio'], $variablesVerifica['codEstudiante'], $variablesVerifica['clasificacion'], $retorno);
//*PRUEBA ACADEMICA*
//        if($_REQUEST['estado_est']=='B'||$_REQUEST['estado_est']=='J')
//            {
//                $variablesPrueba=array($_REQUEST['planEstudio'],$_REQUEST['codEstudiante']);
//
//                $cadena_sql=$this->sql->cadena_sql($this->configuracion,"espacios_plan_estudio_prueba", $variablesPrueba);//echo $_REQUEST['espacio']."<br>".$cadena_sql;exit;
//                $resultado_planEstudio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
//
//                for($m=0;$m<count($resultado_planEstudio);$m++)
//                {
//                    if($resultado_planEstudio[$m][0]==$_REQUEST['espacio'])
//                        {
//                            $inscribir=1;
//                            break;
//                        }
//                        else
//                        {
//                            $inscribir=0;
//                        }
//                }
//                if ($inscribir==0)
//                {
//                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,"nombre_espacio", $_REQUEST['espacio']);
//                    $resultado_nombreEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
//
//                    echo "<script>alert ('No se puede inscribir el Espacio Académico ".$_REQUEST['espacio']." - ".$resultado_nombreEspacio[0][0].". El estudiante está en Prueba Académica (Parágrafo 1, Artículo 1, Acuerdo 07 de 2009).');</script>";
//                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
//                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
//                    $variable.="&opcion=mostrarConsulta";
//                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
//                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
//                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
//                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
//
//                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
//                    $this->cripto=new encriptar();
//                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);
//
//                    echo "<script>location.replace('".$pagina.$variable."')</script>";
//                    exit;
//                }
//
//            }
        
//echo $inscribir."-".$_REQUEST['espacio']."-".$m;exit;
        $variableInscripcion=$variables;
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"carrera_estudiante", $variables);
        $resultado_carreraEstudiante=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $variableInscripcion[3]=$resultado_carreraEstudiante[0]['CARRERA_ESTUDIANTE'];

        //$cadena_sql_buscarEspacioMysql=$this->sql->cadena_sql($this->configuracion,"buscar_espacio_mysql", $variableInscripcion);
        //$resultado_EspacioMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_buscarEspacioMysql,"busqueda" );

        if ($resultado_EspacioOracle ==''/* and $resultado_EspacioMysql == ''*/) {

            $cadena_sql_horario_registrado=$this->sql->cadena_sql($this->configuracion,"horario_registrado", $variableInscripcion);
            $resultado_horario_registrado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"busqueda" );

            $cadena_sql_horario_grupo_nuevo=$this->sql->cadena_sql($this->configuracion,"horario_grupo_nuevo", $variableInscripcion);
            $resultado_horario_grupo_nuevo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_horario_grupo_nuevo,"busqueda" );

            for($i=0;$i<count($resultado_horario_registrado);$i++) {
                for($j=0;$j<count($resultado_horario_grupo_nuevo);$j++) {

                    if(($resultado_horario_grupo_nuevo[$j])==($resultado_horario_registrado[$i])) {
                        echo "<script>alert ('El horario del grupo seleccionado presenta cruce con el horario que el estudiante tiene inscrito');</script>";
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                        $variable.="&opcion=mostrarConsulta";
                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                        $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                        
                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }
                }
            }

            $cadena_sql_cupo_grupo=$this->sql->cadena_sql($this->configuracion,"cupo_grupo_cupo", $variables);
            $resultado_cupo_grupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_cupo_grupo,"busqueda" );

            $cadena_sql_cupo_inscritos=$this->sql->cadena_sql($this->configuracion,"cupo_grupo_ins", $variables);
            $resultado_cupo_inscritos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_cupo_inscritos,"busqueda" );
            
            if($resultado_cupo_inscritos[0][0]<=$resultado_cupo_grupo[0][0] || $this->nivel=28) {
              //valida si el estudiante no excede el maximo de creditos por periodo del plan de estudios.
              
              $validaCreditos = $this->validacion->validarCreditosPeriodo($this->configuracion, $variablesVerifica['codEstudiante'], $variablesVerifica['planEstudio'], $variablesVerifica['codEspacio'], $retorno);
              
				
              $cadena_sql_adicionarMysql=$this->sql->cadena_sql($this->configuracion,"adicionar_espacio_mysql", $variableInscripcion);
                    $resultado_adicionarMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_adicionarMysql,"" );
                    
                    if($resultado_adicionarMysql==true)
                        {
                            $cadena_sql_adicionar=$this->sql->cadena_sql($this->configuracion,"adicionar_espacio_oracle", $variableInscripcion);
                            $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"" );
                            
                            if($resultado_adicionar==true)
                                {
                                    $cadena_sql_actualizarCupo=$this->sql->cadena_sql($this->configuracion,"actualizar_cupo", $variables);
                                    $resultado_actualizarCupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

                                    $variablesRegistro=array($this->usuario,date('YmdGis'),'1','Adiciona Espacio académico',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['id_grupo'].", ".$_REQUEST['planEstudio'].", ".$resultado_cupo_grupo[0]['CARRERA'],$_REQUEST['codEstudiante']);

                                    $cadena_sql_registroEvento=$this->sql->cadena_sql($this->configuracion,"registroEvento", $variablesRegistro);
                                    $resultado_registroEvento=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,"buscarIDRegistro", $variablesRegistro);
                                    $resultado_buscarRegistroEvento=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                    echo "<script>alert ('Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                    $variable.="&opcion=mostrarConsulta";
                                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                    
                                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    exit;
                                }
                                else
                                    {
                                        echo "<script>alert ('En este momento la base de datos O se encuentra ocupada, por favor intente mas tarde');</script>";

                                        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"borrar_datos_mysql_no_conexion", $variableInscripcion);
                                        $resultado_adicionarMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );

                                        $variablesRegistro=array($this->usuario,date('YmdGis'),'50','Conexion Error Oracle',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['id_grupo'].", ".$_REQUEST['planEstudio'].", ".$resultado_cupo_grupo[0]['CARRERA'],$_REQUEST['codEstudiante']);

                                        $cadena_sql_registroEvento=$this->sql->cadena_sql($this->configuracion,"registroEvento", $variablesRegistro);
                                        $resultado_registroEvento=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                        $variable.="&opcion=mostrarConsulta";
                                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                        $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                        exit;
                                    }
                        }
                        else
                                    {
                                        echo "<script>alert ('En este momento la base de datos M se encuentra ocupada, por favor intente mas tarde');</script>";

                                        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"borrar_datos_mysql_no_conexion", $variableInscripcion);
                                        $resultado_adicionarMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );

                                        $variablesRegistro=array($this->usuario,date('YmdGis'),'51','Conexion Error MySQL',$ano[0]."-".$ano[1].", ".$_REQUEST['espacio'].", 0, ".$_REQUEST['id_grupo'].", ".$_REQUEST['planEstudio'].", ".$resultado_cupo_grupo[0]['CARRERA'],$_REQUEST['codEstudiante']);

                                        $cadena_sql_registroEvento=$this->sql->cadena_sql($this->configuracion,"registroEvento", $variablesRegistro);
                                        $resultado_registroEvento=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                        $variable.="&opcion=mostrarConsulta";
                                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                        $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                        
                                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                        exit;
                                    }

            }else {
                echo "<script>alert ('Este curso tiene el máximo número de inscritos, no puede adicionar ');</script>";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                $variable.="&opcion=mostrarConsulta";
                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                
                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
            }
        }
        else {
            echo "<script>alert ('Este espacio ya había sido registrado y no puede adicionarse nuevamente');</script>";
            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
            
            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }

    }

    function verificarCancelacion()
    {
             $_REQUEST['codProyecto'];
             $_REQUEST['planEstudioGeneral'];

            $cadena_sql_periodo=$this->sql->cadena_sql($this->configuracion,"ano_periodo", "");
            $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

            $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);

            $variablesCancelado=array($_REQUEST['codEstudiante'],$_REQUEST['espacio'],$ano[0],$ano[1]);

            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"estudianteCancelo", $variablesCancelado);
            $resultado_cancelo=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                
            if(is_array($resultado_cancelo))
                {
                    ?>
<table class="contenidotabla">
    <tr class="cuadro_color">
        <td class="cuadro_plano centrar" colspan="2">
            El espacio acad&eacute;mico <?echo $_REQUEST['nombre']?> ya fue cancelado en este periodo<br> ¿ Desea adicionarlo de todas formas ?
        </td>
    </tr>
    <tr>
        <td class="centrar">
            <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=adicionar";
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&nivel=".$_REQUEST["nivel"];
                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST["creditos"];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST["nombre"];
                    $variable.="&estado_est=".$_REQUEST["estado_est"];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);
            ?>
            <a href="<?echo $pagina.$variable?>">
                <img width="30" height="30" border="0" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" >
            </a>
        </td>
        <td class="centrar">
            <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);
            ?>
            <a href="<?echo $pagina.$variable?>">
                <img width="30" height="30" border="0" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/x.png" >
            </a>
        </td>
    </tr>
</table>
               <?
        }else
            {
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=adicionar";
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&nivel=".$_REQUEST["nivel"];
                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST["creditos"];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST["nombre"];
                    $variable.="&estado_est=".$_REQUEST["estado_est"];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
            }
    }

    function buscarOtrosGrupos()
    {
        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $espacio=$_REQUEST['espacio'];
        $clasificacion=$_REQUEST['clasificacion'];
        $nivel=$_REQUEST['nivel'];
        $planEstudio=$_REQUEST['planEstudio'];
        $carrera=$_REQUEST['carrera'];
        $creditos=$_REQUEST['creditos'];
        $nombre=$_REQUEST['nombre'];
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];

        $cadena_sql_periodo=$this->sql->cadena_sql($this->configuracion,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );
        $ano=$resultado_periodo[0][0];
        $periodo=$resultado_periodo[0][1];

        $variables=array($espacio,$carrera,$planEstudio, $ano, $periodo);
        $cadena_sql_carreras=$this->sql->cadena_sql($this->configuracion,"buscar_carrerasAbiertas", $variables);
        $resultadoAdicionesAbiertas=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_carreras,"busqueda" );

        ?>
<table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar" width="50%">
                    <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=adicionar";
                    $variable.="&grupo=".(isset($_REQUEST["grupo"])?$_REQUEST["grupo"]:'');
                    $variable.="&id_grupo=".(isset($_REQUEST["id_grupo"])?$_REQUEST["id_grupo"]:'');
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&nivel=".$_REQUEST["nivel"];
                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&estado_est=".$_REQUEST["estado_est"];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos del Proyecto <br>Curricular <? echo $codProyecto;?></b>
            </a>

            <td class="centrar" width="50%">
                    <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=otrosGrupos";
                    $variable.="&id_grupo=".(isset($_REQUEST["id_grupo"])?$_REQUEST["id_grupo"]:'');
                    $variable.="&grupo=".(isset($_REQUEST["grupo"])?$_REQUEST["grupo"]:'');
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&espacio=".$_REQUEST["espacio"];
                    $variable.="&nivel=".$_REQUEST["nivel"];
                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&estado_est=".$_REQUEST["estado_est"];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos en otros<br>Proyectos Curriculares</b>
            </a>
        </td>
    </tr>
</table>

        <?

        if(is_array($resultadoAdicionesAbiertas))
            {$cuenta=0;
?>                <table width="100%" border="0" align="center" cellpadding="1 px" cellspacing="0px" >
                    <th class='sigma_a centrar'>
                        <?echo $espacio." - ".$nombre?>
                    </th>
                </table>
  <?

        for($p=0;$p<count($resultadoAdicionesAbiertas);$p++){

            $variables[5]=$resultadoAdicionesAbiertas[$p][0];
            $cadena_sql_grupos=$this->sql->cadena_sql($this->configuracion,"otros_grupos", $variables);
            $resultado_grupos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );
        if($resultado_grupos!=NULL) {
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

                                                $cadena_sql_horarios=$this->sql->cadena_sql($this->configuracion,"horario_otros_grupos", $variables);
                                                $resultado_horarios=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );


                                                $cadena_sql=$this->sql->cadena_sql($this->configuracion,"horario_otrosgrupos_registrar", $variables);
                                                $resultado_horarios_registrar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableCodigo[0]=$codigoEstudiante;
                                                $variableCodigo[4]=$ano;
                                                $variableCodigo[5]=$periodo;

                                                $cadena_sql=$this->sql->cadena_sql($this->configuracion,"horario_registrado", $variableCodigo);
                                                $resultado_horarios_registrado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

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

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($this->configuracion,"cupo_grupo_ins", $variableCupo);
                                                $resultado_cupoInscritos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($this->configuracion,"cupo_grupo_cupo", $variableCupo);
                                                $resultado_cupoGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

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

                                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                            <input type="hidden" name="planEstudioGeneral" value="<?echo $planEstudioGeneral?>">
                                            <input type="hidden" name="id_grupo" value="<?echo $resultado_grupos[$j][0]?>">
                                            <!--<input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j][0]?>">-->
                                            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                            <input type="hidden" name="espacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="nivel" value="<?echo $nivel;?>">
                                            <input type="hidden" name="clasificacion" value="<?echo $_REQUEST["clasificacion"]?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="carrera" value="<?echo $variables[1]?>">
                                            <input type="hidden" name="creditos" value="<?echo $_REQUEST['creditos']?>">
                                            <input type="hidden" name="estado_est" value="<?echo $_REQUEST['estado_est']?>">
                                            <input type="hidden" name="creditosInscritos" value="<?echo $_REQUEST['creditosInscritos']?>">
                                            <input type="hidden" name="opcion" value="inscribir">
                                            <input type="hidden" name="action" value="<?echo $this->bloque?>">
                                            <input type="image" name="adicion" width="30" height="30" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" >

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
    <tr class="cuadro_plano centrar">
      <th colspan="2">
        <hr>Observaciones
      </th>
    </tr>
    <tr class="cuadro_plano">
      <td colspan="2">
        * Si el fondo del enlace est&aacute; en <font color="#F90101">rojo</font>, significa que el grupo presenta cruce o sobrecupo.
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
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0">
                <br><b>Horario Estudiante</b>
            </a>
        </td>
        <td class="centrar" width="50%">
                    <?
                    $cadena_sql_plan=$this->sql->cadena_sql($this->configuracion,"datosCoordinador", $this->usuario);
                    $resultado_plan=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
                    if($resultado_plan[0][0]==291)
                        {
                            $variable.="&opcion=electivas";
                        }else
                            {
                            $variable.="&opcion=espacios";
                            }
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&estado_est=".$_REQUEST["estado_est"];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/reload.png" width="35" height="35" border="0"><br><b>Cambiar Espacio</b>
            </a>
        </td>
    </tr>
</table>
    <?
    }

    function verificarRangos($variablesVerifica)
    {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"rangos_proyecto", $variablesVerifica['planEstudio']);
        $resultado_parametros=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        $OBEst=$OCEst=$EIEst=$EEEst=$credEst=0;
        if(is_array($resultado_parametros))
        {
            $OB=$resultado_parametros[0][1];
            $OC=$resultado_parametros[0][2];
            $EI=$resultado_parametros[0][3];
            $EE=$resultado_parametros[0][4];

            $variablesClasificacion=array($variablesVerifica['planEstudio'],$variablesVerifica['codEspacio']);

            $cadena_sql=$this->sql->cadena_sql($this->configuracion, "clasificacion_espacioAdicionar", $variablesClasificacion);
            $resultado_clasificacionEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
            if(is_array($resultado_clasificacionEspacio))
                {
                    $variables=array($variablesVerifica['codEstudiante'],$resultado_clasificacionEspacio[0][1]);
                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,"espaciosAprobados",$variables);
                    $registroEspaciosAprobados=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    for($i=0;$i<=count($registroEspaciosAprobados);$i++)
                    {
                        $credEst=$credEst+$registroEspaciosAprobados[$i][2];
                    }
                    $creditos=$credEst+$resultado_clasificacionEspacio[0][0];
                    if($creditos<=$resultado_parametros[0][$resultado_clasificacionEspacio[0][1]])
                        {
                          return true;
                        }
                        else
                        {
                            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"clasificacion",$resultado_clasificacionEspacio[0][1]);
                            $resultadoClasificacion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                            echo "<script>alert ('No se puede adicionar el espacio académico ".$variablesVerifica['codEspacio']." - ".$resultado_clasificacionEspacio[0][2].". Supera el número de créditos permitidos para la clasificación ".$resultadoClasificacion[0][0].".');</script>";
                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                            $variable.="&opcion=mostrarConsulta";
                            $variable.="&codEstudiante=".$variablesVerifica["codEstudiante"];
                            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                            $variable.="&planEstudioGeneral=".$variablesVerifica['planEstudio'];
                            $variable.="&planEstudio=".$variablesVerifica["planEstudio"];

                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            exit;
                        }
                }
                else
                {
                    echo "<script>alert ('Imposible rescatar los datos de la clasificación del espacio académico');</script>";
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codEstudiante=".$variablesVerifica["codEstudiante"];
                    $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                    $variable.="&planEstudioGeneral=".$variablesVerifica['planEstudio'];
                    $variable.="&planEstudio=".$variablesVerifica['planEstudio'];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }
        }
        else
        {
            echo "<script>alert ('Los rangos de créditos no estan definidos por el proyecto curricular. No se puede inscribir el espacio académico');</script>";
            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&codEstudiante=".$variablesVerifica["codEstudiante"];
            $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
            $variable.="&planEstudioGeneral=".$variablesVerifica['planEstudio'];
            $variable.="&planEstudio=".$variablesVerifica['planEstudio'];

            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
            exit;
        }
    }



    function verificaRangos($variablesVerifica)
    {
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"rangos_proyecto", $variablesVerifica['planEstudio']);
            $resultado_parametros=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
            $OBEst=$OCEst=$EIEst=$EEEst=0;
            if(is_array($resultado_parametros))
                {
                    $OB=$resultado_parametros[0][1];
                    $OC=$resultado_parametros[0][2];
                    $EI=$resultado_parametros[0][3];
                    $EE=$resultado_parametros[0][4];

                    $variablesClasificacion=array($variablesVerifica['planEstudio'],$variablesVerifica['codEspacio']);

                    $cadena_sql=$this->sql->cadena_sql($this->configuracion, "clasificacion_espacioAdicionar", $variablesClasificacion);
                    $resultado_clasificacionEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                    if(is_array($resultado_clasificacionEspacio))
                        {
                            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"espaciosAprobados",$variablesVerifica['codEstudiante']);
                            $registroEspaciosAprobados=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );


                                for($i=0;$i<=count($registroEspaciosAprobados);$i++)
                                {
//                                    $idEspacio= $registroEspaciosAprobados[$i][0];
//                                    $variables=array($idEspacio, $planEstudiante);
//                                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,"valorCreditosPlan",$variables);
//                                    $registroCreditosEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                        switch(trim($registroEspaciosAprobados[$i][3]))
                                        {
                                            case 1:
                                                    $OBEst=$OBEst+$registroEspaciosAprobados[$i][2];
                                                    $totalCreditosEst=$totalCreditosEst+$OBEst;
                                                break;

                                            case 2:
                                                    $OCEst=$OCEst+$registroEspaciosAprobados[$i][2];
                                                    $totalCreditosEst=$totalCreditosEst+$OCEst;
                                                break;

                                            case 3:
                                                    $EIEst=$EIEst+$registroEspaciosAprobados[$i][2];
                                                    $totalCreditosEst=$totalCreditosEst+$EIEst;
                                                break;

                                            case 4:
                                                    $EEEst=$EEEst+$registroEspaciosAprobados[$i][2];
                                                    $totalCreditosEst=$totalCreditosEst+$EEEst;
                                                break;

                                         }
                                }
//                                echo 'OB: '.$OB.'-'.$OBEst.' OC: '.$OC.'-'.$OCEst.' EI: '.$EI.'-'.$EIEst.' EE: '.$EE.'-'.$EEEst.'<br>Total: '.$totalCreditosEst;
//                                exit;

                                switch ($resultado_clasificacionEspacio[0][1])
                                {
                                    case "1":
                                        $OBEst=$OBEst+$resultado_clasificacionEspacio[0][0];
                                            if($OBEst<=$OB)
                                                {
                                                    return true;
                                                }else
                                                    {
                                                        echo "<script>alert ('No se puede adicionar el espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']." Supera el número de créditos Obligatorios Basicos permitidos ');</script>";
                                                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                                        $variable.="&opcion=mostrarConsulta";
                                                        $variable.="&codEstudiante=".$variablesVerifica["codEstudiante"];
                                                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                        $variable.="&planEstudioGeneral=".$variablesVerifica['planEstudio'];
                                                        $variable.="&planEstudio=".$variablesVerifica["planEstudio"];

                                                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                        exit;
                                                    }
                                        break;

                                    case "2":
                                        $OCEst=$OCEst+$resultado_clasificacionEspacio[0][0];
                                            if($OCEst<=$OC)
                                                {
                                                    return true;
                                                }else
                                                    {
                                                        echo "<script>alert ('No se puede adicionar el espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']." Supera el número de créditos Obligatorios Complementarios permitidos ');</script>";
                                                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                                        $variable.="&opcion=mostrarConsulta";
                                                        $variable.="&codEstudiante=".$variablesVerifica["codEstudiante"];
                                                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                        $variable.="&planEstudioGeneral=".$variablesVerifica['planEstudio'];
                                                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];

                                                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                        exit;
                                                    }
                                        break;

                                    case "3":
                                        $EIEst=$EIEst+$resultado_clasificacionEspacio[0][0];
                                            if($EIEst<=$EI)
                                                {
                                                    return true;
                                                }else
                                                    {
                                                        echo "<script>alert ('No se puede adicionar el espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']." Supera el número de créditos Electivos Intrinsecos permitidos ');</script>";
                                                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                                        $variable.="&opcion=mostrarConsulta";
                                                        $variable.="&codEstudiante=".$variablesVerifica["codEstudiante"];
                                                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                        $variable.="&planEstudioGeneral=".$variablesVerifica['planEstudio'];
                                                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];

                                                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                        exit;
                                                    }
                                        break;

                                    case "4":
                                        $EEEst=$EEEst+$resultado_clasificacionEspacio[0][0];
                                            if($EEEst<=$EE)
                                                {
                                                    return true;
                                                }else
                                                    {
                                                        echo "<script>alert ('No se puede adicionar el espacio académico ".$variablesVerifica['codEspacio']." - ".$variablesVerifica['nombreEspacio']." Supera el número de créditos Electivos Extrinsecos permitidos ');</script>";
                                                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                                        $variable.="&opcion=mostrarConsulta";
                                                        $variable.="&codEstudiante=".$variablesVerifica["codEstudiante"];
                                                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                                        $variable.="&planEstudioGeneral=".$variablesVerifica['planEstudio'];
                                                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];

                                                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                        exit;
                                                    }
                                        break;
                                }

                        }else
                            {
                                echo "<script>alert ('Imposible rescatar los datos de la clasificación del espacio académico');</script>";
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                $variable.="&opcion=mostrarConsulta";
                                $variable.="&codEstudiante=".$variablesVerifica["codEstudiante"];
                                $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                                $variable.="&planEstudioGeneral=".$variablesVerifica['planEstudio'];
                                $variable.="&planEstudio=".$variablesVerifica['planEstudio'];

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                                exit;
                            }

                }else
                    {
                        echo "<script>alert ('Los rangos de créditos no estan definidos por el proyecto curricular. No se puede inscribir el espacio académico');</script>";
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                        $variable.="&opcion=mostrarConsulta";
                        $variable.="&codEstudiante=".$variablesVerifica["codEstudiante"];
                        $variable.="&codProyecto=".$variablesVerifica['codProyecto'];
                        $variable.="&planEstudioGeneral=".$variablesVerifica['planEstudio'];
                        $variable.="&planEstudio=".$variablesVerifica['planEstudio'];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }
        }

}

?>