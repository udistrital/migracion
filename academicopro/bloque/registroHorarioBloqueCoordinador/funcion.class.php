<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroHorarioBloqueCoordinador extends funcionGeneral {
    //Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
//        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
//        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        $this->formulario="registroHorarioBloqueCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $this->verificar="control_vacio(".$this->formulario.",'numero')";
        $this->verificar.="&&verificar_numero(".$this->formulario.",'numero')";
        $this->verificar.="&&verificar_rango(".$this->formulario.",'numero','0','99')";
        $this->seleccionar="todos('todos')";
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"ano_periodo",'');
        $this->periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );


    }

    function horarioBloque($configuracion)
        {
        $totalCreditos=0;
         $variablesBloque=array($_REQUEST['planEstudio'],$_REQUEST['codProyecto'],$_REQUEST['idBloque'],$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"bloque_publicado",$variablesBloque);
        $resultado_bloquePublicado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        if($resultado_bloquePublicado[0][0]=='1')
            {
                echo "<script>alert('El bloque ".$_REQUEST['idBloque']." ya se encuentra publicado, no se pueden modificar datos')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroBloqueEstudiantes";
                $ruta.="&opcion=crear";
                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                $ruta.="&totalCreditos=".$totalCreditos;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                echo "<script>location.replace('".$pagina.$ruta."')</script>";
                exit;
            }
//var_dump($_REQUEST);exit;
        $variableConsulta=array($_REQUEST['idBloque'],$_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$this->periodo[0][0],$this->periodo[0][1]);
        $totalEstudiantes=$_REQUEST['totalEstudiantes'];
        $cadena_sql_horarioBloque=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"espacio_grupoBloque", $variableConsulta);
        $resultado_horarioBloque=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_horarioBloque,"busqueda" );

        ?>
<table  class="contenidotabla centrar"border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td colspan="2">
            SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA
            <br>
        <?echo $_REQUEST['nombreProyecto']?>
            <br>
            Plan de Estudio <?echo $_REQUEST['planEstudio']?>

        </td>
    </tr>
    <tr align="center">
        <td colspan="2">
            <h6>HORARIO DE CLASES DEL BLOQUE <?echo $_REQUEST['idBloque']?></h6>
            <hr noshade class="hr">
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
        </td>
    </tr>

        <?
        if($resultado_horarioBloque==NULL) {
            ?>
    <tr>
        <td>
            <table class='contenidotabla centrar'>
                <thead class='cuadro_plano centrar'>
                    <tr>
                        <td class='cuadro_plano centrar'>
                            No existen espacios acad&eacute;micos adicionados para este bloque
                        </td>
                    </tr>
                    
                </thead>
                <?
            }else {
                ?>

                <tr>
                    <td>
                        <table class='contenidotabla'>
                            <thead class="cuadro_brownOscuro centrar">
                            <td class='cuadro_plano centrar'>Cod.</td>
                            <td class='cuadro_plano centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </td>
                            <td class='cuadro_plano centrar' width="25">Grupo </td>
                            <td class='cuadro_plano centrar' width="25">Cr&eacute;ditos</td>
                            <td class='cuadro_plano centrar' width="60">Lun </td>
                            <td class='cuadro_plano centrar' width="60">Mar </td>
                            <td class='cuadro_plano centrar' width="60">Mie </td>
                            <td class='cuadro_plano centrar' width="60">Jue </td>
                            <td class='cuadro_plano centrar' width="60">Vie </td>
                            <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                            <td class='cuadro_plano centrar' width="60">Dom </td>
                            <td class='cuadro_plano centrar' width="20">Cupo </td>
                            <td class='cuadro_plano centrar' width="20">Cupos Reservados</td>
                            <td class='cuadro_plano centrar' width="20">SobreCupo </td>
                            <td class='cuadro_plano centrar' width="60">Cambiar Grupo</td>
                            <td class='cuadro_plano centrar' width="60">Cancelar</td>
                            </thead>

            <?
            //recorre cada uno del los grupos
            for($j=0;$j<count($resultado_horarioBloque);$j++) {

                $variables[0]=$resultado_horarioBloque[$j]['ID_ESPACIO'];  //idEspacio
                $variables[1]=$resultado_horarioBloque[$j]['ID_GRUPO'];  //grupo
                $variables[2]=$this->periodo[0][0];  //AÑO
                $variables[3]=$this->periodo[0][1];  //PERIODO

                //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos_registrados",$variables);
                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                $totalCreditos+=$resultado_horarioBloque[$j]['CREDITOS'];
                ?>
                <tr>
                    <td class='cuadro_plano centrar'><?echo $resultado_horarioBloque[$j]['ID_ESPACIO'];?></td>
                    <td class='cuadro_plano'><?echo $resultado_horarioBloque[$j]['ESPACIO'];?></td>
                    <td class='cuadro_plano centrar'><?echo $resultado_horarios[0]['GRUPO'];?></td>
                    <td class='cuadro_plano centrar'><?echo $resultado_horarioBloque[$j]['CREDITOS'];?></td>
                    <?
                        //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                        for($i=1; $i<8; $i++) {
                            ?><td class='cuadro_plano centrar'><?
                                //Recorre el arreglo del resultado de los horarios
                                for ($k=0;$k<count($resultado_horarios);$k++)
                                {
                                    if ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                                            $l = $k;
                                            while ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                                                $m = $k;
                                                $m++;
                                                $k++;
                                            }
                                            $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>Sede: " . (isset($resultado_horarios[$l]['SEDE'])?$resultado_horarios[$l]['SEDE']:'') . "<br>Edificio: " . (isset($resultado_horarios[$l]['EDIFICIO'])?$resultado_horarios[$l]['EDIFICIO']:'') . "<br>Sal&oacute;n:" . (isset($resultado_horarios[$l]['SALON'])?$resultado_horarios[$l]['SALON']:'');
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '')) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                            $k++;
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] != $i) {                        }

                                    }
                                    ?></td><?
                                }

                            $variableCupo=array($_REQUEST['idBloque'],$resultado_horarioBloque[$j]['ID_GRUPO'],$resultado_horarioBloque[$j]['ID_ESPACIO'],$this->periodo[0][0],$this->periodo[0][1]);//BLOQUE, GRUPO, ESPACIO
                            $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_ins", $variableCupo);
                            $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                            $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_cupo", $variableCupo);
                            $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"cupos_bloque", $variableCupo);//echo $cadena_sql;exit;
                            $resultado_cupoBloques=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            $cupoDisponible=($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]-$totalEstudiantes);

                                                    ?>
            <td class='cuadro_plano centrar'>
                <?echo $resultado_cupoGrupo[0][0]?>
            </td>
                    <?$sobrecupo=0-$cupoDisponible;
                        if($sobrecupo>0)
                            {
                                ?><td class="cuadro_plano centrar" onmouseover="toolTip('Estudiantes Inscritos: <?echo $resultado_cupoInscritos[0][0]?><br>Cupos Reservados Otros Bloques: <?echo $resultado_cupoBloques[0][0]?><br>Estudiantes Este Bloque: <?echo $totalEstudiantes?>',this)">
                                    <div class="centrar">
                                        <span id="toolTipBox" width="300" ></span>
                                    </div>

                                    <?if($cupoDisponible>=0)
                                        {
                                        echo "<font color='green'>";
                                        }else
                                            {
                                            echo "<font color='red'>";

                                            }echo $resultado_cupoInscritos[0][0]+$totalEstudiantes+$resultado_cupoBloques[0][0]?></font>
                                    </td>
                                    <td class='cuadro_plano centrar'>
                                        <font color="red"><?echo 0-$cupoDisponible+$resultado_cupoBloques[0][0]?></font>
                                    </td>
                                <?
                            }else
                                {
                                ?><td class="cuadro_plano centrar" onmouseover="toolTip('Estudiantes Inscritos: <?echo $resultado_cupoInscritos[0][0]?><br>Cupos Reservados Otros Bloques: <?echo $resultado_cupoBloques[0][0]?><br>Estudiantes Este Bloque: <?echo $totalEstudiantes?>',this)">
                                    <div class="centrar">
                                        <span id="toolTipBox" width="300" ></span>
                                    </div>
                                    <?if($cupoDisponible>=0)
                                        {
                                        echo "<font color='green'>";
                                        }else
                                            {
                                            echo "<font color='red'>";

                                            }echo $resultado_cupoInscritos[0][0]+$totalEstudiantes+$resultado_cupoBloques[0][0]?></font>
                                    </td>
                                    <td class='cuadro_plano centrar'>
                                    <font color="green">NO</font>
                                    </td>
                                    <?
                                }
                    ?>
                </td>





                            <td class='cuadro_plano centrar'>

                                <?
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$ruta="pagina=registroHorarioBloqueCoordinador";
				$ruta.="&opcion=cambiarGrupo";
                                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                                $ruta.="&idEspacio=".$resultado_horarioBloque[$j]['ID_ESPACIO'];
                                $ruta.="&grupo=".$resultado_horarios[0]['GRUPO'];
                                $ruta.="&id_grupo=".$resultado_horarioBloque[$j]['ID_GRUPO'];
                                $ruta.="&nombreEspacio=".$resultado_horarioBloque[$j]['ESPACIO'];
                                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                                $ruta.="&idBloque=".$_REQUEST['idBloque'];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$ruta=$this->cripto->codificar_url($ruta,$configuracion);


				?>

                                <a href="<?= $pagina.$ruta ?>" >
                                <img src="<?echo $configuracion["site"].$configuracion["grafico"]."/reload.png"?>" border="0" width="25" height="25">
                                </a>

                                </td>

                            <td class='cuadro_plano centrar'>

                                <?//echo $planEstudio;

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$ruta="pagina=registroHorarioBloqueCoordinador";
				$ruta.="&opcion=cancelarEspacio";
                                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                                $ruta.="&idEspacio=".$resultado_horarioBloque[$j]['ID_ESPACIO'];
                                $ruta.="&grupo=".$resultado_horarios[0]['GRUPO'];
                                $ruta.="&id_grupo=".$resultado_horarioBloque[$j]['ID_GRUPO'];
                                $ruta.="&nombreEspacio=".$resultado_horarioBloque[$j]['ESPACIO'];
                                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                                $ruta.="&idBloque=".$_REQUEST['idBloque'];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$ruta=$this->cripto->codificar_url($ruta,$configuracion);

				?>
                                <a href="<?= $pagina.$ruta ?>" >
                                    <img src="<?echo $configuracion["site"].$configuracion["grafico"]."/x.png"?>" border="0" width="25" height="25">
                                </a>
                            </td>
                            </tr>
                            <?
                                }
                            }
                            ?>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="contenidotabla centrar">
                <?if($totalCreditos>0){?>
                <tr class="centrar">
                    <td class="derecha" colspan="2">
                        <font size="1">Total Cr&eacute;ditos Inscritos: <?echo $totalCreditos?></font>
                    </td>
                </tr><?}?>
                <tr class="centrar">
                    <td class="centrar" colspan="2">

        <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=registroHorarioBloqueCoordinador";
            $ruta.="&opcion=espacios";
            $ruta.="&idBloque=".$_REQUEST['idBloque'];
            $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
            $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
            $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
            $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
            $ruta.="&totalCreditos=".$totalCreditos;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            ?>
            <a href="<?= $pagina.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br><font size="1">Adicionar</font>
            </a>
                    </td>
                </tr>

                <tr>
                    <td class="centrar" width="50%">
                        <br>
                                <?
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $ruta="pagina=registroBloqueEstudiantes";
                                $ruta.="&opcion=crear";
                                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                                $ruta.="&totalCreditos=".$totalCreditos;

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                                ?>
                        <a href="<?= $pagina.$ruta ?>">
                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="30" height="30" border="0"><br><font size="1">Regresar</font>
                        </a>
                    </td>
                </tr>

    <tr class="cuadro_brownOscuro centrar">
            <td colspan="10">
                Observaciones
            </td>
        </tr>
        <tr class="cuadro_plano">
            <td colspan="10">
                * Recuerde que la cantidad de estudiantes no debe exceder el cupo m&aacute;ximo del grupo creado en los horarios.
                <br>
                * Recuerde verificar que no se presente cruce entre los horarios de los grupos de espacios acad&eacute;micos registrados a cada bloque.
                <br>
                * Recuerde que si el grupo no cumple con el cupo m&iacute;nimo, puede ser cancelado.
            </td>
        </tr>
</table>
        <?



    }

    function adicionarEspacios($configuracion)
        {
        //var_dump($_REQUEST);
        $variablesEspacios=array($_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$_REQUEST['idBloque']);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"proyecto_propedeutico", $variablesEspacios);//echo $cadena_sql;exit;
        $resultado_propedeutico=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        
        if($resultado_propedeutico[0]['planEstudio_propedeutico']==1){
            $nivel=7;
        }else{
            $nivel=1;
        }
        $variablesEspacios[3]=$nivel;
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"espacios_plan_estudio", $variablesEspacios);//echo $cadena_sql;exit;
        $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        ?>
        <table class='contenidotabla centrar'>
            <tr align="center">
                <td colspan="2">
                    SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA
                    <br>
                <?echo $_REQUEST['nombreProyecto']?>
                    <br>
                    Plan de Estudio <?echo $_REQUEST['planEstudio']?>

                </td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <h6>HORARIO DE CLASES DEL BLOQUE <?echo $_REQUEST['idBloque']?></h6>
                    <hr noshade class="hr">
                </td>
            </tr>
            <tr>
                <td class="centrar" width="50%">
                    <br>
                    <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=registroHorarioBloqueCoordinador";
                        $ruta.="&opcion=horario";
                        $ruta.="&idBloque=".$_REQUEST['idBloque'];
                        $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                        $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                        $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                        $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                    ?>
                    <a href="<?= $pagina.$ruta ?>">
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="40" height="40" border="0"><br><font size="2">Horario del <br>bloque</font>
                    </a>
                </td>
            </tr>
            <tr>
                <td class="cuadro_brownOscuro centrar"><center><font size="2">ESPACIOS PERMITIDOS - NIVEL <? echo $nivel;?></font></center></td>
            </tr>
            <table class='contenidotabla centrar'>
                <thead class='centrar'>
                <td class="cuadro_brownOscuro centrar" width="10%">C&oacute;digo Espacio</td>
                <td class="cuadro_brownOscuro centrar" width="20%">Nombre Espacio</td>
                <td class="cuadro_brownOscuro centrar" width="5%">Clasificaci&oacute;n</td>
                <td class="cuadro_brownOscuro centrar" width="5%">Nro Cr&eacute;ditos</td>
                <td class="cuadro_brownOscuro centrar" width="15%">Adicionar</td>
                </thead>
                <?
                $esp=0;
                for($i=0;$i<count($resultado_planEstudio);$i++) {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nombre_espacio", $resultado_planEstudio[$i][0]);//echo "aqui";//$cadena_sql;//exit;
                    $resultado_espacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    $variablesEspacios[3]=$resultado_planEstudio[$i][0];
                    $variablesEspacios[4]=$this->periodo[0][0];
                    $variablesEspacios[5]=$this->periodo[0][1];

                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"horario_bloqueRegistrado", $variablesEspacios);//echo $cadena_sql_horarioRegistrado;exit;
                    $resultado_horarioRegistrado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            if($resultado_horarioRegistrado==NULL) {
                                ?> <tr>
                    <td class='cuadro_plano centrar'><? echo $resultado_planEstudio[$i][0]?></td>
                    <td class='cuadro_plano '><? echo $resultado_espacio[0][0]?></td>
                    <td class='cuadro_plano centrar'><? echo $resultado_espacio[0][2]?></td>
                                <?
                                if(($_REQUEST['totalCreditos']+$resultado_espacio[0][1])>36)
                                    {
                                        ?><td class='cuadro_plano centrar'>
                                            <font color="red"><? echo $resultado_espacio[0][1]?></font>
                                        </td>
                                        <td class='cuadro_plano centrar'>
                                                No puede adicionar, supera los creditos permitidos
                                        </td>
                                        <?
                                    }else{
                                ?><td class='cuadro_plano centrar'><font color="#3BAF29"><? echo $resultado_espacio[0][1]?></font></td>
                    <td class='cuadro_plano centrar'>

                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                            <input type="hidden" name="idEspacio" value="<?echo $resultado_planEstudio[$i][0]?>">
                            <input type="hidden" name="nombreEspacio" value="<?echo $resultado_espacio[0][0]?>">
                            <input type="hidden" name="opcion" value="adicionar">
                            <input type="hidden" name="idBloque" value="<?echo $_REQUEST['idBloque']?>">
                            <input type="hidden" name="creditos" value="<?echo $resultado_espacio[0][1]?>">
                            <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
                            <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
                            <input type="hidden" name="totalEstudiantes" value="<?echo $_REQUEST['totalEstudiantes'];?>">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >
                        </form>
                    </td><?}?>
                </tr>


                        <?
                    }
        /*            else
                    {
                        $esp++;
                        if($esp==count($resultado_planEstudio))
                        {
                            ?><td class='cuadro_plano centrar' colspan ="5"><?echo "No hay m&aacute;s espacios para adicionar";?></td><?
                        }
                    }*/
                }
                ?>


                <tr>
                    <td class="centrar" colspan="10">
                        <br>
                        <a href="javascript:history.back()" on>
                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="30" height="30" border="0"><br><font size="2">Regresar</font>
                        </a>
                    </td>
                </tr>
                <tr class="cuadro_brownOscuro centrar">
                    <td colspan="10">
                        Observaciones
                    </td>
                </tr>
                <tr class="cuadro_plano">
                    <td colspan="10">
                        * Recuerde que la cantidad de estudiantes no debe exceder el cupo m&aacute;ximo del grupo creado en los horarios.
                        <br>
                        * Recuerde verificar que no se presente cruce entre los horarios de los grupos de espacios acad&eacute;micos registrados a cada bloque.
                        <br>
                        * Recuerde que si el grupo no cumple con el cupo m&iacute;nimo, puede ser cancelado.
                    </td>
                </tr>
            </table>
        </table>

        <?


    }

    function buscarGrupo($configuracion)
        {
        $idBloque=$_REQUEST['idBloque'];
        $idEspacio=$_REQUEST['idEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $codProyecto=$_REQUEST['codProyecto'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $totalEstudiantes=$_REQUEST['totalEstudiantes'];

        $variables=array($idEspacio,$codProyecto,$planEstudio,$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"grupos_proyecto", $variables);
        $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $variablesInscritos=array($idBloque,$codProyecto,$planEstudio,$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"espacio_grupoBloque", $variablesInscritos);//echo $cadena_sql;exit;
        $resultado_EspaciosInscritos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        ?>
<table class='contenidotabla'>
    <tr align="center">
        <td colspan="2">
            SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA
            <br>
        <?echo $_REQUEST['nombreProyecto']?>
            <br>
            Plan de Estudio <?echo $_REQUEST['planEstudio']?>

        </td>
    </tr>
    <tr align="center">
        <td colspan="2">
            <h6>HORARIO DE CLASES DEL BLOQUE <?echo $_REQUEST['idBloque']?></h6>
            <hr noshade class="hr">
        </td>
    </tr>
    <tr>
        <td class="centrar">
            <br>
            <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroHorarioBloqueCoordinador";
                $ruta.="&opcion=horario";
                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            ?>
            <a href="<?= $pagina.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="40" height="40" border="0"><br><font size="2">Horario del <br>bloque</font>
            </a>
        </td>
    </tr>
</table>
<table width="100%" border="0" align="center" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                    <thead class='texto_subtitulo centrar'>
                    <td><center><?echo $idEspacio." - ".$nombreEspacio;?></center></td>
                    </thead>
                    <tr>
                        <td class="cuadro_plano centrar" colspan="10">Total de Estudiantes del bloque: <?echo $totalEstudiantes?></td>
                    </tr>
                    <?if(is_array($resultado_grupos)){?>
                    <tr>
                        <td>
                            <table class='contenidotabla'>
                                <thead class="cuadro_brownOscuro centrar">
                                <td class='cuadro_plano centrar' width="25">Grupo </td>
                                <td class='cuadro_plano centrar' width="60">Lun </td>
                                <td class='cuadro_plano centrar' width="60">Mar </td>
                                <td class='cuadro_plano centrar' width="60">Mie </td>
                                <td class='cuadro_plano centrar' width="60">Jue </td>
                                <td class='cuadro_plano centrar' width="60">Vie </td>
                                <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                <td class='cuadro_plano centrar' width="60">Dom </td>
                                <td class='cuadro_plano centrar' width="20">Cupo </td>
                                <td class='cuadro_plano centrar' width="20">Cupos Reservados</td>
                                <td class='cuadro_plano centrar' width="20">SobreCupo </td>
                                <td class='cuadro_plano centrar' >Adicionar</td>
                                </thead>

        <?

        $cruce='';
        for($j=0;$j<count($resultado_grupos);$j++) {

            $variables[3]=$resultado_grupos[$j]['ID_GRUPO'];
            $variables[4]=$this->periodo[0][0];
            $variables[5]=$this->periodo[0][1];

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos", $variables);//echo $cadena_sql;echo"<br>";
            $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(is_array($resultado_EspaciosInscritos))
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos_registrar", $variables);//echo $cadena_sql;echo"<br>";
                    $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    
                    for($g=0;$g<count($resultado_EspaciosInscritos);$g++)
                    {
                        
                        $variableInscritos=array($resultado_EspaciosInscritos[$g][0],$resultado_EspaciosInscritos[$g][1],$this->periodo[0][0],$this->periodo[0][1]);
                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_registrado", $variableInscritos);//echo $cadena_sql;exit;
                        $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        unset($cruce);
                        $cruce='';
                        for($n=0;$n<count($resultado_horarios_registrado);$n++)
                        {
                            for($m=0;$m<count($resultado_horarios_registrar);$m++)
                                {
                                    if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n]))
                                        {
                                            $cruce=true;
                                            break;
                                        }
                                }
                        }
                        if($cruce==true){break;}
                    }
                }

                
        $variableCupo=array($idBloque,$resultado_grupos[$j]['ID_GRUPO'],$idEspacio,$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_ins", $variableCupo);
        $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );//inscritos

        $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_cupo", $variableCupo);
        $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );//cupo

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"cupos_bloque", $variableCupo);//echo $cadena_sql;exit;
        $resultado_cupoBloques=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//bloques
        unset($cupoDisponible);

        $cupoDisponible=($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]-$totalEstudiantes);

        ?><tr><td class='cuadro_plano centrar'><?echo $resultado_grupos[$j]['GRUPO'];?></td><?
        for($i=1; $i<8; $i++) {
            ?><td class='cuadro_plano centrar'><?
            for ($k=0;$k<count($resultado_horarios);$k++) {

                if ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                                            $l = $k;
                                            while ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                                                $m = $k;
                                                $m++;
                                                $k++;
                                            }
                                            $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>Sede: " . (isset($resultado_horarios[$l]['SEDE'])?$resultado_horarios[$l]['SEDE']:'') . "<br>Edificio: " . (isset($resultado_horarios[$l]['EDIFICIO'])?$resultado_horarios[$l]['EDIFICIO']:'') . "<br>Sal&oacute;n:" . (isset($resultado_horarios[$l]['SALON'])?$resultado_horarios[$l]['SALON']:'');
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '')) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                            $k++;
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] != $i) {                        }

                    }
                    ?></td><?
                }
                ?>
            <td class='cuadro_plano centrar'>
                <?echo $resultado_cupoGrupo[0][0]?>
            </td>
                    <?$sobrecupo=0-$cupoDisponible;
                        if($sobrecupo>0)
                            {
                                ?><td class="cuadro_plano centrar" onmouseover="toolTip('Estudiantes Inscritos: <?echo $resultado_cupoInscritos[0][0]?><br>Cupos Reservados Otros Bloques: <?echo $resultado_cupoBloques[0][0]?><br>Estudiantes Este Bloque: <?echo $totalEstudiantes?>',this)">
                                    <div class="centrar">
                                        <span id="toolTipBox" width="300" ></span>
                                    </div>
                                    
                                    <?if($cupoDisponible>=0)
                                        {
                                        echo "<font color='green'>";
                                        }else
                                            {
                                            echo "<font color='red'>";

                                            }echo $resultado_cupoInscritos[0][0]+$totalEstudiantes+$resultado_cupoBloques[0][0]?></font>
                                    </td>
                                    <td class='cuadro_plano centrar'>
                                        <font color="red"><?echo 0-$cupoDisponible+$resultado_cupoBloques[0][0]?></font>
                                    </td>
                                <?
                            }else
                                {
                                ?><td class="cuadro_plano centrar" onmouseover="toolTip('Estudiantes Inscritos: <?echo $resultado_cupoInscritos[0][0]?><br>Cupos Reservados Otros Bloques: <?echo $resultado_cupoBloques[0][0]?><br>Estudiantes Este Bloque: <?echo $totalEstudiantes?>',this)">
                                    <div class="centrar">
                                        <span id="toolTipBox" width="300" ></span>
                                    </div>
                                    <?if($cupoDisponible>=0)
                                        {
                                        echo "<font color='green'>";
                                        }else
                                            {
                                            echo "<font color='red'>";

                                            }echo $resultado_cupoInscritos[0][0]+$totalEstudiantes+$resultado_cupoBloques[0][0]?></font>
                                    </td>
                                    <td class='cuadro_plano centrar'>
                                    <font color="green">NO</font>
                                    </td>
                                    <?
                                }
                    ?>
                </td>


                    <td class='cuadro_plano centrar'>
                        
                        <?if($cruce==true)
                            {
                                echo "No puede adicionar por cruce";
                            }else{?>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                            <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j]['GRUPO']?>">
                            <input type="hidden" name="id_grupo" value="<?echo $resultado_grupos[$j]['ID_GRUPO']?>">
                            <input type="hidden" name="idBloque" value="<?echo $idBloque?>">
                            <input type="hidden" name="idEspacio" value="<?echo $variables[0]?>">
                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                            <input type="hidden" name="codProyecto" value="<?echo $variables[1]?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
                            <input type="hidden" name="totalEstudiantes" value="<?echo $totalEstudiantes?>">
                            <input type="hidden" name="opcion" value="inscribir">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >
                        </form><?}?>
                   </td>
                </tr>
                        <?}
                        ?>
                            </table>
                        </td>

                    </tr>

                </table>
            </td>
        </tr><?

        }else
            {
                echo "<tr class='cuadro_plano centrar'><td colspan='5'>No existen grupos registrados en el sistema para el espacio acad&eacute;mico ".$idEspacio." - ".$nombreEspacio."</td></tr>";
            }?>
    </tbody>
</table>
            <table width='100%'>
                <tr>
                    <td class="centrar" width="50%">
                        <br>
                                <?
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $ruta="pagina=registroHorarioBloqueCoordinador";
                                $ruta.="&opcion=espacios";
                                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                                $ruta.="&totalCreditos=".$totalCreditos;

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                                ?>
                        <a href="<?= $pagina.$ruta ?>">
                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="30" height="30" border="0"><br><font size="1">Regresar</font>
                        </a>
                    </td>
                </tr>
            </table>            
<table class="cuadro_color centrar" width="100%">
    <tr class="cuadro_brownOscuro centrar">
        <td>
            Observaciones
        </td>
    </tr>
    <tr class="cuadro_plano">
        <td>
            * Recuerde que la cantidad de estudiantes no debe exceder el cupo m&aacute;ximo del grupo creado en los horarios.
            <br>
            * Recuerde verificar que no se presente cruce entre los horarios de los grupos de espacios acad&eacute;micos registrados a cada bloque.
            <br>
            * Recuerde que si el grupo no cumple con el cupo m&iacute;nimo, puede ser cancelado.
        </td>
    </tr>
</table>
</table>
        <?
    }

    function inscribirEspacios($configuracion)
        {

        $variablesBloque=array($_REQUEST['planEstudio'],$_REQUEST['codProyecto'],$_REQUEST['idBloque'],$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"bloque_publicado",$variablesBloque);//echo $cadena_sql;exit;
        $resultado_bloquePublicado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        if($resultado_bloquePublicado[0][0]=='1')
            {
                echo "<script>alert('El bloque ".$_REQUEST['idBloque']." ya se encuentra publicado, no se pueden modificar datos')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroBloqueEstudiantes";
                $ruta.="&opcion=crear";
                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                $ruta.="&totalCreditos=".$totalCreditos;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                echo "<script>location.replace('".$pagina.$ruta."')</script>";
                exit;
            }
        $variables=array($_REQUEST['idBloque'],
                        $_REQUEST['idEspacio'],
                        $_REQUEST['codProyecto'],
                        $_REQUEST['planEstudio'],
                        $_REQUEST['id_grupo'],
                        $this->periodo[0][0],
                        $this->periodo[0][1]);

        $resultado_adicionado = $this->consultarEspacioAdicionado($configuracion,$variables);
        if (is_array($resultado_adicionado) && $resultado_adicionado[0]['horario_idBloque']){
                echo "<script>alert('El espacio ".$_REQUEST['idEspacio']." ya se encuentra adicionado a este bloque de estudiantes.')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroHorarioBloqueCoordinador";
                $ruta.="&opcion=espacios";
                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                $ruta.="&totalCreditos=".$totalCreditos;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                echo "<script>location.replace('".$pagina.$ruta."')</script>";
        }else{
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"adicionar_creditos", $variables);//echo $cadena_sql;exit;
            $resultado_inscribir=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

            if($resultado_inscribir)
                {
                    $variablesRegistro=array($this->usuario,date('YmdGis'),'35','Adiciono E.A. Bloque',$this->periodo[0][0]."-".$this->periodo[0][1].", ".$_REQUEST['idEspacio'].", 0, ".$_REQUEST['id_grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['codProyecto'],$_REQUEST['planEstudio']);

                    $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                    $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarIDRegistro", $variablesRegistro);
                    $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    echo "<script>alert ('El Espacio Académico ha sido adicionado al bloque ".$variables[0].", Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroHorarioBloqueCoordinador";
                    $variable.="&opcion=horario";
                    $variable.="&codProyecto=".$variables[2];
                    $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                    $variable.="&planEstudio=".$variables[3];
                    $variable.="&idBloque=".$variables[0];
                    $variable.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                }else
                    {
                        echo "<script>alert ('El espacio académico no ha sido adicionado, por favor vuelva a intentarlo');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroHorarioBloqueCoordinador";
                        $variable.="&opcion=horario";
                        $variable.="&codProyecto=".$variables[2];
                        $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                        $variable.="&planEstudio=".$variables[3];
                        $variable.="&idBloque=".$variables[0];
                        $variable.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                    }
        }
    }

    function buscarGruposCambio($configuracion)
        {
        $idBloque=$_REQUEST['idBloque'];
        $idEspacio=$_REQUEST['idEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $codProyecto=$_REQUEST['codProyecto'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $totalEstudiantes=$_REQUEST['totalEstudiantes'];
        $grupoAnt=$_REQUEST['grupo'];
        $id_grupoAnt=$_REQUEST['id_grupo'];

        $variables=array($idEspacio,$codProyecto,$planEstudio,$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"grupos_proyecto", $variables);//echo $cadena_sql;exit;
        $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $variablesInscritos=array($idBloque,$codProyecto,$planEstudio,$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"espacio_grupoBloque", $variablesInscritos);//echo $cadena_sql;exit;
        $resultado_EspaciosInscritos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        ?>
<table class='contenidotabla'>
    <tr align="center">
        <td colspan="2">
            SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA
            <br>
        <?echo $_REQUEST['nombreProyecto']?>
            <br>
            Plan de Estudio <?echo $_REQUEST['planEstudio']?>

        </td>
    </tr>
    <tr align="center">
        <td colspan="2">
            <h6>HORARIO DE CLASES DEL BLOQUE <?echo $_REQUEST['idBloque']?></h6>
            <hr noshade class="hr">
        </td>
    </tr>
    <tr>
        <td class="centrar">
            <br>
            <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroHorarioBloqueCoordinador";
                $ruta.="&opcion=horario";
                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            ?>
            <a href="<?= $pagina.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="40" height="40" border="0"><br><font size="2">Horario del<br>bloque</font>
            </a>
        </td>
    </tr>
</table>
<table width="100%" border="0" align="center" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                    <thead class='texto_subtitulo centrar'>
                    <td><center><?echo $idEspacio." - ".$nombreEspacio;?></center></td>
                    </thead>
                    <tr>
                        <td class="cuadro_plano centrar" colspan="10">Total de Estudiantes del bloque: <?echo $totalEstudiantes?></td>
                    </tr>
                    <?if(is_array($resultado_grupos)){?>
                    <tr>
                        <td>
                            <table class='contenidotabla'>
                                <thead class="cuadro_brownOscuro centrar">
                                <td class='cuadro_plano centrar' width="25">Grupo </td>
                                <td class='cuadro_plano centrar' width="60">Lun </td>
                                <td class='cuadro_plano centrar' width="60">Mar </td>
                                <td class='cuadro_plano centrar' width="60">Mie </td>
                                <td class='cuadro_plano centrar' width="60">Jue </td>
                                <td class='cuadro_plano centrar' width="60">Vie </td>
                                <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                <td class='cuadro_plano centrar' width="60">Dom </td>
                                <td class='cuadro_plano centrar' width="20">Cupo </td>
                                <td class='cuadro_plano centrar' width="20">Cupos Reservados</td>
                                <td class='cuadro_plano centrar' width="20">SobreCupo </td>
                                <td class='cuadro_plano centrar' >Adicionar</td>
                                </thead>

        <?


        for($j=0;$j<count($resultado_grupos);$j++) {

            if($resultado_grupos[$j][0]!=$grupoAnt)
            {

            $variables[3]=$resultado_grupos[$j][0];
            $variables[4]=$this->periodo[0][0];
            $variables[5]=$this->periodo[0][1];

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos", $variables);//echo $cadena_sql;echo"<br>";
            $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(is_array($resultado_EspaciosInscritos))
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos_registrar", $variables);//echo $cadena_sql;echo"<br>";
                    $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    for($g=0;$g<count($resultado_EspaciosInscritos);$g++)
                    {

                        $variableInscritos=array($resultado_EspaciosInscritos[$g][0],$resultado_EspaciosInscritos[$g][1],$this->periodo[0][0],$this->periodo[0][1]);
                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_registrado", $variableInscritos);//echo $cadena_sql;exit;
                        $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                        unset($cruce);

                        for($n=0;$n<count($resultado_horarios_registrado);$n++)
                        {
                            for($m=0;$m<count($resultado_horarios_registrar);$m++)
                                {
                                    if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n]))
                                        {
                                            $cruce=true;
                                            break;
                                        }
                                }
                        }
                        if($cruce==true){break;}
                    }
                }



        $variableCupo=array($idBloque,$resultado_grupos[$j][0],$idEspacio,$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_ins", $variableCupo);
        $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

        $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_cupo", $variableCupo);
        $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"cupos_bloque", $variableCupo);//echo $cadena_sql;exit;
        $resultado_cupoBloques=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//bloques
        unset($cupoDisponible);

        $cupoDisponible=($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]-$totalEstudiantes);

        ?><tr><td class='cuadro_plano centrar'><?echo $resultado_grupos[$j]['GRUPO'];?></td><?
        for($i=1; $i<8; $i++) {
            ?><td class='cuadro_plano centrar'><?
            for ($k=0;$k<count($resultado_horarios);$k++) {

                                        if ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                                            $l = $k;
                                            while ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                                                $m = $k;
                                                $m++;
                                                $k++;
                                            }
                                            $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>Sede: " . (isset($resultado_horarios[$l]['SEDE'])?$resultado_horarios[$l]['SEDE']:'') . "<br>Edificio: " . (isset($resultado_horarios[$l]['EDIFICIO'])?$resultado_horarios[$l]['EDIFICIO']:'') . "<br>Sal&oacute;n:" . (isset($resultado_horarios[$l]['SALON'])?$resultado_horarios[$l]['SALON']:'');
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '')) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                            $k++;
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede: " . $resultado_horarios[$k]['SEDE'] . "<br>Edificio: " . $resultado_horarios[$k]['EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] != $i) {                        }

                    }
                    ?></td><?
                }
                ?>
                    <td class='cuadro_plano centrar'>
                        <?echo $resultado_cupoGrupo[0][0]?>
                    </td>

                    <?$sobrecupo=0-$cupoDisponible;
                        if($sobrecupo>0)
                            {
                                ?><td class="cuadro_plano centrar" onmouseover="toolTip('Estudiantes Inscritos: <?echo $resultado_cupoInscritos[0][0]?><br>Cupos Reservados Otros Bloques: <?echo $resultado_cupoBloques[0][0]?><br>Estudiantes Este Bloque: <?echo $totalEstudiantes?>',this)">
                                    <div class="centrar">
                                        <span id="toolTipBox" width="300" ></span>
                                    </div>

                                    <?if($cupoDisponible>=0)
                                        {
                                        echo "<font color='green'>";
                                        }else
                                            {
                                            echo "<font color='red'>";

                                            }echo $resultado_cupoInscritos[0][0]+$totalEstudiantes+$resultado_cupoBloques[0][0]?></font>
                                    </td>
                                    <td class='cuadro_plano centrar'>
                                        <font color="red"><?echo 0-$cupoDisponible+$resultado_cupoBloques[0][0]?></font>
                                    </td>
                                <?
                            }else
                                {
                                ?><td class="cuadro_plano centrar" onmouseover="toolTip('Estudiantes Inscritos: <?echo $resultado_cupoInscritos[0][0]?><br>Cupos Reservados Otros Bloques: <?echo $resultado_cupoBloques[0][0]?><br>Estudiantes Este Bloque: <?echo $totalEstudiantes?>',this)">
                                    <div class="centrar">
                                        <span id="toolTipBox" width="300" ></span>
                                    </div>
                                    <?if($cupoDisponible>=0)
                                        {
                                        echo "<font color='green'>";
                                        }else
                                            {
                                            echo "<font color='red'>";

                                            }echo $resultado_cupoInscritos[0][0]+$totalEstudiantes+$resultado_cupoBloques[0][0]?></font>
                                    </td>
                                    <td class='cuadro_plano centrar'>
                                    <font color="green">NO</font>
                                    </td>
                                    <?
                                }
                    ?>
                </td>


                    <td class='cuadro_plano centrar'>

                        <?if($cruce==true)
                            {
                                echo "No puede adicionar por cruce";
                            }else{?>
                                <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                                    <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j]['GRUPO']?>">
                                    <input type="hidden" name="id_grupo" value="<?echo $resultado_grupos[$j]['ID_GRUPO']?>">
                                    <input type="hidden" name="grupoAnt" value="<?echo $grupoAnt?>">
                                    <input type="hidden" name="id_grupoAnt" value="<?echo $id_grupoAnt?>">
                                    <input type="hidden" name="idBloque" value="<?echo $idBloque?>">
                                    <input type="hidden" name="idEspacio" value="<?echo $variables[0]?>">
                                    <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                    <input type="hidden" name="codProyecto" value="<?echo $variables[1]?>">
                                    <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
                                    <input type="hidden" name="totalEstudiantes" value="<?echo $totalEstudiantes?>">
                                    <input type="hidden" name="opcion" value="confirmarCambio">
                                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                    <input type="image" name="adicion" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" >
                                </form>
                            <?}?>
                   </td>
                </tr>
                        <?
        }
                        }
                        ?>
                            </table>
                        </td>

                    </tr>
                </table>
            </td>
        </tr><?

        }else
            {
                echo "<tr class='cuadro_plano centrar'><td colspan='5'>No existen grupos registrados en el sistema para el espacio acad&eacute;mico ".$idEspacio." - ".$nombreEspacio."</td></tr>";
            }?>
    </tbody>
</table>
<table class="cuadro_color centrar" width="100%">
    <tr class="cuadro_brownOscuro centrar">
        <td>
            Observaciones
        </td>
    </tr>
    <tr class="cuadro_plano">
        <td>
            * Recuerde que la cantidad de estudiantes no debe exceder el cupo m&aacute;ximo del grupo creado en los horarios.
            <br>
            * Recuerde verificar que no se presente cruce entre los horarios de los grupos de espacios acad&eacute;micos registrados a cada bloque.
            <br>
            * Recuerde que si el grupo no cumple con el cupo m&iacute;nimo, puede ser cancelado.
        </td>
    </tr>
</table>
</table>
        <?
    }

    function confirmarCambioGrupo($configuracion)
        {
        $variables=array($_REQUEST['idBloque'],$_REQUEST['idEspacio'],$_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$_REQUEST['id_grupo'],$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"cambiarGrupoBloque", $variables);//echo $cadena_sql;exit;
        $resultado_inscribir=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

        if($resultado_inscribir)
            {
                $variablesRegistro=array($this->usuario,date('YmdGis'),'35','Cambio grupo E.A. Bloque',$this->periodo[0][0]."-".$this->periodo[0][1].", ".$_REQUEST['idEspacio'].", ".$_REQUEST['id_grupoAnt'].", ".$_REQUEST['id_grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['codProyecto'],$_REQUEST['planEstudio']);

                $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarIDRegistro", $variablesRegistro);
                $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                //echo "<script>alert ('Usted registro el espacio académico.Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";
                echo "<script>alert ('El cambio de grupo se registro con exito. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=registroHorarioBloqueCoordinador";
                $variable.="&opcion=horario";
                $variable.="&codProyecto=".$variables[2];
                $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                $variable.="&planEstudio=".$variables[3];
                $variable.="&idBloque=".$variables[0];
                $variable.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
            }else
                {
                    echo "<script>alert ('El cambio de grupo fracaso, intente de nuevo por favor');</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroHorarioBloqueCoordinador";
                    $variable.="&opcion=horario";
                    $variable.="&codProyecto=".$variables[2];
                    $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                    $variable.="&planEstudio=".$variables[3];
                    $variable.="&idBloque=".$variables[0];
                    $variable.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                }

    }

    function solicitaConfirmacionCancelacion($configuracion)
        {
        $variables=array($_REQUEST['idBloque'],$_REQUEST['idEspacio'],$_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$_REQUEST['id_grupo'],$this->periodo[0][0],$this->periodo[0][1]);

        ?>
        <table  class="contenidotabla centrar"border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr align="center">
                <td colspan="2">
                    SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA
                    <br>
                <?echo $_REQUEST['nombreProyecto']?>
                    <br>
                    Plan de Estudio <?echo $_REQUEST['planEstudio']?>

                </td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <h6>HORARIO DE CLASES DEL BLOQUE <?echo $_REQUEST['idBloque']?></h6>
                    <hr noshade class="hr">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                </td>
            </tr>
            <tr>
                <td colspan="5" class="centrar">
                    <font size="2">Esta a punto de cancelar el espacio acad&eacute;mico <?echo $variables[1]." - ".$_REQUEST['nombreEspacio']?><br>¿Realmente desea cancelarlo?</font>
                </td>
            </tr>
            <tr class="centrar">
                <td width="50%">
                        <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=registroHorarioBloqueCoordinador";
                        $ruta.="&opcion=confirmarCancelar";
                        $ruta.="&idBloque=".$_REQUEST['idBloque'];
                        $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                        $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                        $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                        $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                        $ruta.="&grupo=".$_REQUEST['grupo'];
                        $ruta.="&id_grupo=".$_REQUEST['id_grupo'];
                        $ruta.="&idEspacio=".$_REQUEST['idEspacio'];
                        $ruta.="&totalCreditos=".$_REQUEST['totalCreditos'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                        ?>
                        <a href="<?= $pagina.$ruta ?>">
                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br><font size="1">Si</font>
                        </a>
                </td>
                <td width="50%"><?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=registroHorarioBloqueCoordinador";
                        $ruta.="&opcion=horario";
                        $ruta.="&idBloque=".$_REQUEST['idBloque'];
                        $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                        $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                        $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                        $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                        $ruta.="&totalCreditos=".$totalCreditos;

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                        ?>
                        <a href="<?= $pagina.$ruta ?>">
                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="30" height="30" border="0"><br><font size="1">No</font>
                        </a>
                </td>
            </tr>
        </table>

        <?
        }

    function confirmarCancelarEspacio($configuracion)
        {
        $variables=array($_REQUEST['idBloque'],$_REQUEST['idEspacio'],$_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$_REQUEST['id_grupo'],$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrarEABloque", $variables);//echo $cadena_sql;exit;
        $resultado_borrar=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

        if($resultado_borrar)
            {
                $variablesRegistro=array($this->usuario,date('YmdGis'),'36','Cancelo E.A. Bloque',$this->periodo[0][0]."-".$this->periodo[0][1].", ".$_REQUEST['idEspacio'].", 0, ".$_REQUEST['id_grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['codProyecto'],$_REQUEST['planEstudio']);

                $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarIDRegistro", $variablesRegistro);
                $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                //echo "<script>alert ('Usted registro el espacio académico.Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";
                echo "<script>alert ('La cancelacion del espacio académico se ejecuto con exito. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=registroHorarioBloqueCoordinador";
                $variable.="&opcion=horario";
                $variable.="&codProyecto=".$variables[2];
                $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                $variable.="&planEstudio=".$variables[3];
                $variable.="&idBloque=".$variables[0];
                $variable.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
            }else
                {
                    echo "<script>alert ('La cancelacion del espacio académico fracaso, intente de nuevo por favor');</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroHorarioBloqueCoordinador";
                    $variable.="&opcion=horario";
                    $variable.="&codProyecto=".$variables[2];
                    $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                    $variable.="&planEstudio=".$variables[3];
                    $variable.="&idBloque=".$variables[0];
                    $variable.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                }

    }

     function consultarEspacioAdicionado($configuracion,$variables){
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"espacio_adicionado", $variables);
            return $resultado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    }
    
    }


?>
