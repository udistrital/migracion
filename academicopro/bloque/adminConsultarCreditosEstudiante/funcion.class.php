
<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");


#Realiza la preparacion del formulario para la validacion de javascript

?>

<?
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.

class funcion_adminConsultarCreditosEstudiante extends funcionGeneral {

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validar_fechas.class.php");

        $this->fechas=new validar_fechas();


        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_adminConsultarCreditosEstudiante();
        $this->log_us= new log();
        $this->formulario="adminConsultarCreditosEstudiante";


        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"estudianteCred");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion=new sesiones($configuracion);
        $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
        $this->id_accesoSesion=$this->resultadoSesion[0][0];

        $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

        //echo $this->usuarioSesion[0][0];

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


    #muestra los datos del estudiante y el horario, utiliza los metodos: mostrarDatosEstudiante, mostrarHorarioEstudiante
    function mostrarHorarioEstudiante($configuracion) {
        $codigoEstudiante=$this->usuario;

        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultaEstudiante",$codigoEstudiante);
        $registroEstudiante=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");

        if(isset($registroEstudiante)) {
            $this->datosEstudiante($configuracion,$registroEstudiante);

            $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo",'');//echo $cadena_sql;exit;
            $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $variablesInscritos=array($codigoEstudiante,$resultado_periodo[0][0],$resultado_periodo[0][1]);

            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultaGrupo",$variablesInscritos);//echo $cadena_sql;exit;
            $registroGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            //verifica periodo academico actual para definir codigo de estudiante nuevo y restringir permisos de adicion y cancelacion
            if ($resultado_periodo[0][1]==3)
            {
              $inicioCodigoEstudiante=$resultado_periodo[0][0].'2';
            }else
            {
              $inicioCodigoEstudiante=$resultado_periodo[0][0].$resultado_periodo[0][1];
            }
            if ($inicioCodigoEstudiante==substr($codigoEstudiante, '0', '5'))
            {
              $permitirCancelar=0;
            }else
              {
                $permitirCancelar=1;
              }
            //fin verificacion nuevos

            $registro_permisos=$this->fechas->validar_fechas_estudiante($configuracion,$codigoEstudiante);
            
                     switch ($registro_permisos)
                     {
                         case 'adicion':
                                  $this->HorarioEstudianteInscripcion($configuracion,$registroGrupo,$registroEstudiante,$registroEstudiante[0][2],$registroEstudiante[0][3],$permitirCancelar);
                             break;

                         case 'cancelacion':
                                  $this->HorarioEstudianteCancelacion($configuracion,$registroGrupo,$registroEstudiante,$registroEstudiante[0][2],$registroEstudiante[0][3],$permitirCancelar);
                             break;

                         case 'consulta':
                                  $this->HorarioEstudianteConsulta($configuracion,$registroGrupo,$registroEstudiante);
                             break;

                         default:
                                  $this->HorarioEstudianteConsulta($configuracion,$registroGrupo,$registroEstudiante);
                             break;
                     }

           
            $creditos=$this->calcularCreditos($configuracion,$registroGrupo);
            ?>

<table class="sigma" align="center">
    <tr>
        <td colspan="2" class="sigma derecha">
                        <?if ($creditos==0) {
                            echo "<font size='2'><b>Cr&eacute;ditos Inscritos: 0</b></font>";
                        }
                        else if($creditos>18) {
                            echo "<font size='2' color='red'><b>Total Cr&eacute;ditos Inscritos: ".$creditos."</b></font>";
                        }else if($creditos<=18) {
                            echo "<font size='2' color='green'><b>Total Cr&eacute;ditos Inscritos: ".$creditos."</b></font>";
                        }
                        ?>
        </td>
    </tr>
    <tr>
        <th class="sigma centrar">
            Abreviatura
        </th>
        <th class="sigma centrar">
            Nombre
        </th>
    </tr>
                <?


                $cadena_sql=$this->sql->cadena_sql($configuracion,"clasificacion",'');
                $resultado_clasificacion=$this->accesoGestion->ejecutarAcceso($cadena_sql,"busqueda");

                for($k=0;$k<count($resultado_clasificacion);$k++) {
                    ?>
    <tr>
        <td class="sigma centrar">
                            <?echo $resultado_clasificacion[$k][1]?>
        </td>
        <td class="sigma ">
                            <?echo $resultado_clasificacion[$k][2]?>
        </td>
    </tr>
                    <?
                }

                ?>

    <tr class="sigma centrar">
        <th class="sigma" colspan="2">
            Observaciones
        </th>
    </tr>
    <tr class="sigma">
        <td colspan="2" class="sigma">
            <br>
            * Recuerde que si cancela un espacio académico, no podra adicionarlo de nuevo para el periodo actual
            <br>
            * Recuerde verificar el cruce de horarios de los espacios académicos
            <br>
            * Recuerde que si el grupo no cumple con el cupo mínimo, puede ser cancelado
        </td>
    </tr>
</table>
            <?


        }
        else {
            echo "El código de estudiante: <strong>".$codigoEstudiante."</strong> no está inscrito en Créditos.";
        }




    }

    #Funcion que muestra la informacion del estudiante
    function datosEstudiante($configuracion,$registro) {
        $cadena_sql=$this->sql->cadena_sql($configuracion,"estado_estudiante",$registro[0][0]); //echo $cadena_sql;exit;
        $resultado_estado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        ?>
<table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
    <tr class="texto_subtitulo">
        <td colspan="2">
                    <?echo "Nombre: <strong>".$registro[0][1]."</strong><br>";?>
                    <?echo "C&oacute;digo: <strong>".$registro[0][0]."</strong><br>";?>
            Proyecto Curricular:
                    <?echo "<strong>".$registro[0][3]."</strong><br>";?>
            Plan de Estudios:
                    <?echo "<strong>".$registro[0][2]." - ".$registro[0][4]."</strong><br>";?>
            Estado:
                    <?echo "<strong>".$resultado_estado[0][1]."</strong><br>";?>
            Acuerdo:
                    <?echo "<strong>".substr($registro[0][5], -3)." de ".substr($registro[0][5], 0, 4)."</strong>";?>
            <hr>
        </td>
    </tr>
</table>


        <?
    }

    function HorarioEstudianteConsulta($configuracion, $resultado_grupos,$registroEstudiante) {
        $this->cadena_sql=$this->sql->cadena_sql($configuracion, 'preinscripcion_estudiante', $registroEstudiante[0][0]);
        $resultado_preins=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
        ?>
<table class="sigma" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
    <tbody>
        <tr>
            <td>
                <?
                if(is_array($resultado_preins)&&trim($resultado_preins[0][0])=='N')
                    {
                    ?>
                <table class="sigma contenidotabla" width="100%" border="0" align="center">
                    <tr class="cuadro_brown centrar">
                        <td class="cuadro_plano centrar">
                            <b>
                                En este momento el sistema no registra Preinscripci&oacute;n de su PROYECTO CURRICULAR.<br> Por favor, comun&iacute;quese con el proyecto.
                            </b>
                        </td>
                    </tr>
                </table>
                    <?
                    }
                ?>
                        <?if($resultado_grupos!=NULL) {

                            $variable=array($registroEstudiante[0][5],$registroEstudiante[0][6]);

                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_adiciones_estudiantes",$variable);//echo $this->cadena_sql;
                            $resultado_adicionesHab=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                            ?>

                <table class="sigma contenidotabla" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                    <caption class="sigma">Horario de Clases</caption>

                    <tr>
                        <td>
                            <table class='sigma contenidotabla'>
                                <thead class='cuadro_color'>
                                <th class='sigma centrar' width="25">Cod.</th>
                                <th class='sigma centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </th>
                                <th class='sigma centrar' width="25">Grupo </th>
                                <th class='sigma centrar' width="25">Cr&eacute;ditos</th>
                                <th class='sigma centrar' width="25">Clasificaci&oacute;n</th>
                                <th class='sigma centrar' width="60">Lun </th>
                                <th class='sigma centrar' width="60">Mar </th>
                                <th class='sigma centrar' width="60">Mie </th>
                                <th class='sigma centrar' width="60">Jue </th>
                                <th class='sigma centrar' width="60">Vie </th>
                                <th class='sigma centrar' width="60">S&aacute;b </th>
                                <th class='sigma centrar' width="60">Dom </th>

                                </thead>

                                            <?


                                            //recorre cada uno del los grupos
                                            for($j=0;$j<count($resultado_grupos);$j++) {

                                                //
                                                $variables[0][0]=$resultado_grupos[$j][0];  //idEspacio
                                                $variables[0][1]=$resultado_grupos[$j][1];  //proyecto
                                                $variables[0][2]=$resultado_grupos[$j][2];  //grupo
                                                $variables[0][5]=$resultado_grupos[$j][5];  //nombre del espacio
                                                $variables[0][6]=$resultado_grupos[$j][6];  //codigo del estudiante
                                                $variables[0][7]=$resultado_grupos[$j][7];  //plan de estudios del estudiante
                                                $variables[0][8]=$resultado_grupos[$j][8];  //nombre del estudiante
                                                $variables[0][9]=$resultado_grupos[$j][9];  //creditos del espacio
                                                $variables[0][10]=$resultado_grupos[$j][10];  //clasificacion
                                                //$variables[0][11]=$resultado_grupos[$j][11];  //apellido2 del estudiante

                                                //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                                                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos",$variables);
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

                                                $variablesClasificacion=array($resultado_grupos[$j][0],$planEstudioGeneral);

                                                //busca la clasificacion del espacio academico
//                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"clasificacionEspacio",$variablesClasificacion);//echo "<br>".$cadena_sql;exit;
//                                                $resultado_clasif=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                                                //var_dump($resultado_horarios);
                                                ?>
                                <tr>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td>
                                    <td class='cuadro_plano'><?echo $resultado_grupos[$j][5];?></td>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][2];?></td>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][9];?></td>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][10];?></td>
                                                    <?

                                                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                                                    for($i=1; $i<8; $i++) {
                                                        ?><td class='cuadro_plano centrar' onmouseover="this.bgColor='#E6E6E6'" onmouseout="this.bgColor=''"><?

                                                            //Recorre el arreglo del resultado de los horarios
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

                                                    ?>
                                </tr>
                                                <?}
                                        }else {?>
                                <tr>
                                    <td class='cuadro_plano centrar'>
                                        No se encontraron datos de espacios adicionados
                                    </td>
                                </tr>
                                            <?}
                                        ?>
                            </table>
                        </td>
                    </tr>
                </table>
                        <?
                        $codEstudiante=$this->usuario;
                        list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($configuracion, $codEstudiante);
                        ?>
                <table align='center' width='600' cellspacing='0' cellpadding='2'>
                    <tr class="centrar">
                        <td class='cuadro_plano centrar'>
                                    <?
                                    echo $valor1;
                                    echo $valor2;
                                    echo $valor3;
                                    echo $valor4;
                                    echo $valor5;
                                    echo $valor6;
                                    ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </tbody>
</table>

        <?



    }

    function HorarioEstudianteInscripcion($configuracion, $resultado_grupos,$registroEstudiante,$planEstudioGeneral,$codProyecto,$permitirCancelar) {
        $cadena_sql=$this->sql->cadena_sql($configuracion,"estado_estudiante",$registroEstudiante[0][0]); //echo $cadena_sql;exit;
        $resultado_estado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        ?>
<table width="100%">
    <tr class="texto_subtitulo centrar">
        <td colspan="5" >
            <font color="red">Recuerde que para inscribir <b>Segunda Lengua</b> puede ingresar por el enlace <b>Adicionar</b>,
            <br>seleccionar el espacio y buscar grupos disponibles por la opci&oacute;n
            <br><b>Grupos en otros Proyectos Curriculares.</b></font>
        </td>
    </tr>
</table>
<table class="sigma contenidotabla" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
    <tbody>
        <tr>
            <td>
                        <?if($resultado_grupos!=NULL) {

                            $variable=array($registroEstudiante[0][5],$registroEstudiante[0][6]);

                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_adiciones_estudiantes",$variable);
                            $resultado_adicionesHab=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                            ?>

                <table class="sigma contenidotabla" width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                    <caption class="sigma">Horario de Clases</caption>
                    <tr>
                        <td>
                            <table class='sigma contenidotabla'>
                                <thead class='sigma'>
                                <th class='sigma centrar' width="20">Cod.</th>
                                <th class='sigma centrar' width="250">Nombre Espacio<br>Acad&eacute;mico </th>
                                <th class='sigma centrar' width="25">Grupo </th>
                                <th class='sigma centrar' width="25">Cr&eacute;ditos</th>
                                <th class='sigma centrar' width="25">Clasificaci&oacute;n</th>
                                <th class='sigma centrar' width="60">Lun </th>
                                <th class='sigma centrar' width="60">Mar </th>
                                <th class='sigma centrar' width="60">Mie </th>
                                <th class='sigma centrar' width="60">Jue </th>
                                <th class='sigma centrar' width="60">Vie </th>
                                <th class='sigma centrar' width="60">S&aacute;b </th>
                                <th class='sigma centrar' width="60">Dom </th>
                                <th class='sigma centrar' width="20">Cambiar<br>Grupo</th>
                                <?if ($permitirCancelar==1){//no permite cancelar para estudiantes nuevos?>
                                <th class='sigma centrar' width="20">Cancelar</th>
                                <?}
                                else{}?>
                                </thead>

                                            <?


                                            //recorre cada uno del los grupos
                                            for($j=0;$j<count($resultado_grupos);$j++) {

                                                //
                                                $variables[0][0]=$resultado_grupos[$j][0];  //idEspacio
                                                $variables[0][1]=$resultado_grupos[$j][1];  //proyecto
                                                $variables[0][2]=$resultado_grupos[$j][2];  //grupo
                                                $variables[0][5]=$resultado_grupos[$j][5];  //nombre del espacio
                                                $variables[0][6]=$resultado_grupos[$j][6];  //codigo del estudiante
                                                $variables[0][7]=$resultado_grupos[$j][7];  //plan de estudios del estudiante
                                                $variables[0][8]=$resultado_grupos[$j][8];  //nombre1 del estudiante
                                                $variables[0][9]=$resultado_grupos[$j][9];  //creditos del espacio
                                                $variables[0][10]=$resultado_grupos[$j][10];  //clasificacion
                                                //$variables[0][11]=$resultado_grupos[$j][11];  //apellido2 del estudiante

                                                //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                                                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos",$variables);
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

                                                $variablesClasificacion=array($resultado_grupos[$j][0],$planEstudioGeneral);


                                                ?>
                                <tr>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td>
                                    <td class='cuadro_plano'><?echo $resultado_grupos[$j][5];?></td>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][2];?></td>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][9];?></td>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][10];?></td>
                                                    <?

                                                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                                                    for($i=1; $i<8; $i++) {
                                                        ?><td class='cuadro_plano centrar' onmouseover="this.bgColor='#E6E6E6'" onmouseout="this.bgColor=''"><?

                                                            //Recorre el arreglo del resultado de los horarios
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


                                                    ?>
                                    <td class='cuadro_plano centrar'>

                                                        <?
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=registroCambiarGrupoInscripcionEstudiante";
                                                        $variable.="&opcion=buscar";
                                                        $variable.="&planEstudioGeneral=".$planEstudioGeneral;
                                                        $variable.="&codEstudiante=".$registroEstudiante[0][0];
                                                        $variable.="&codProyecto=".$codProyecto;
                                                        $variable.="&carrera=".$codProyecto;
                                                        $variable.="&proyecto=".$variables[0][1];
                                                        $variable.="&codEspacio=".$variables[0][0];
                                                        $variable.="&grupo=".$variables[0][2];
                                                        $variable.="&planEstudio=".$variables[0][7];
                                                        $variable.="&nombre=".$resultado_grupos[$j][5];
                                                        $variable.="&estado_est=".trim($resultado_estado[0][0]);


                                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$configuracion);


                                                        ?>

                                        <a href="<?= $pagina.$variable ?>" >
                                            <img src="<?echo $configuracion["site"].$configuracion["grafico"]."/reload.png"?>" border="0" width="25" height="25">
                                        </a>

                                    </td>
                                    <?if ($permitirCancelar==1){//no permite cancelar para estudiantes nuevos?>
                                    <td class='cuadro_plano centrar'>

                                                        <?
                                                        $creditosInscritos=$this->calcularCreditos($configuracion, $resultado_grupos);
                                                        
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=registroCancelarInscripcionCreditosEstudiante";
                                                        $variable.="&opcion=verificar";
                                                        $variable.="&planEstudioGeneral=".$planEstudioGeneral;
                                                        $variable.="&codProyecto=".$codProyecto;
                                                        $variable.="&codEstudiante=".$variables[0][6];
                                                        $variable.="&proyecto=".$variables[0][1];
                                                        $variable.="&codEspacio=".$variables[0][0];
                                                        $variable.="&grupo=".$variables[0][2];
                                                        $variable.="&planEstudio=".$variables[0][7];
                                                        $variable.="&nombre=".$resultado_grupos[$j][5];
                                                        $variable.="&creditosInscritos=".$creditosInscritos;
                                                        $variable.="&estado_est=".trim($resultado_estado[0][0]);


                                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                        ?>
                                        <a href="<?= $pagina.$variable ?>" >
                                            <img src="<?echo $configuracion["site"].$configuracion["grafico"]."/x.png"?>" border="0" width="25" height="25">
                                        </a>


                                    </td>
                                    <?}else{}?>
                                </tr>
                                                <?}

                                            $codEstudiante=$variables[0][6];
                                            list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($configuracion, $codEstudiante);

                                            ?>
                                <table class="sigma" align='center' width='85%' cellspacing='0' cellpadding='0'>
                                    <tr>
                                        <td width="85%" class="centrar">
                                            <?
                                            echo $valor1;
                                            echo $valor2;
                                            echo $valor3;
                                            echo $valor4;
                                            ?>
                                        </td>
                                        <td width="15%" class="centrar">
                                            <?if ($permitirCancelar==1){//no permite adicionar para estudiantes nuevos
                                                $creditosInscritos=$this->calcularCreditos($configuracion, $resultado_grupos);
                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                $variable="pagina=registroAdicionarInscripcionEstudiante";
                                                $variable.="&opcion=espacios";
                                                $variable.="&planEstudioGeneral=".$planEstudioGeneral;
                                                $variable.="&codProyecto=".$codProyecto;
                                                $variable.="&codEstudiante=".$registroEstudiante[0][0];
                                                $variable.="&creditosInscritos=".$creditosInscritos;
                                                $variable.="&estado_est=".trim($resultado_estado[0][0]);

                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                $this->cripto=new encriptar();
                                                $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                ?>
                                                <a href="<?= $pagina.$variable ?>">
                                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br><font size="1">Adicionar<br></font>
                                                </a>
                                            <?}
                                            else{}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="85%" class="centrar">
                                            <?
                                            echo $valor5;
                                            echo $valor6;
                                            ?>
                                        </td>
                                        <td  width="15%" class="centrar">
                                            <?if ($permitirCancelar==1){//no permite adicionar para estudiantes nuevos
                                            $creditosInscritos=$this->calcularCreditos($configuracion, $resultado_grupos);
                                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $variable="pagina=registroAdicionarInscripcionEEEstudiante";
                                            $variable.="&opcion=espacios";
                                            $variable.="&planEstudioGeneral=".$planEstudioGeneral;
                                            $variable.="&codProyecto=".$codProyecto;
                                            $variable.="&codEstudiante=".$registroEstudiante[0][0];
                                            $variable.="&creditosInscritos=".$creditosInscritos;
                                            $variable.="&estado_est=".trim($resultado_estado[0][0]);

                                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                            $this->cripto=new encriptar();
                                            $variable=$this->cripto->codificar_url($variable,$configuracion);
                                            ?>
                                            <a href="<?echo $pagina.$variable ?>">
                                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br><font size="1">Adicionar<br>Electivos Extrinsecos</font>
                                            </a>
                                            <?}
                                            else{}?>
                                        </td>
                                    </tr>
                                   
                                </table>
                                            <?

                                        }else {
                                            $codEstudiante=$registroEstudiante[0][0];
                                            list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($configuracion, $codEstudiante);
                                            ?>
                                <tr>
                                    <td class='cuadro_plano centrar'>
                                        No se encontraron datos de espacios adicionados
                                    </td>
                                </tr>
                                <table align='center' width='85%' cellspacing='0' cellpadding='2'>
                                    <tr class="centrar">
                                        <td class='cuadro_plano centrar' width='85%'>
                                                        <?
                                                        echo $valor1;
                                                        echo $valor2;
                                                        echo $valor3;
                                                        echo $valor4;
                                                        ?>
                                        </td>
                                        <td class='cuadro_plano centrar' width='15%'>
                                                        <?

                                                        $creditosInscritos=$this->calcularCreditos($configuracion, $resultado_grupos);
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=registroAdicionarInscripcionEstudiante";
                                                        $variable.="&opcion=espacios";
                                                        $variable.="&planEstudioGeneral=".$planEstudioGeneral;
                                                        $variable.="&codProyecto=".$codProyecto;
                                                        $variable.="&codEstudiante=".$registroEstudiante[0][0];
                                                        $variable.="&creditosInscritos=".$creditosInscritos;
                                                        $variable.="&estado_est=".trim($resultado_estado[0][0]);

                                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                        ?>
                                            <a href="<?= $pagina.$variable ?>" on>
                                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br><font size="1">Adicionar<br></font>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="centrar">
                                        <td class='cuadro_plano centrar' width='85%'>
                                                        <?
                                                        echo $valor5;
                                                        echo $valor6;
                                                        ?>
                                        </td>
                                        <td class='cuadro_plano centrar' width='15%'>
                                                        <?
                                                        $creditosInscritos=$this->calcularCreditos($configuracion, $resultado_grupos);
                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=registroAdicionarInscripcionEEEstudiante";
                                                        $variable.="&opcion=espacios";
                                                        $variable.="&planEstudioGeneral=".$planEstudioGeneral;
                                                        $variable.="&codProyecto=".$codProyecto;
                                                        $variable.="&codEstudiante=".$registroEstudiante[0][0];
                                                        $variable.="&creditosInscritos=".$creditosInscritos;
                                                        $variable.="&estado_est=".trim($resultado_estado[0][0]);

                                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                        ?>
                                            <a href="<?echo $pagina.$variable ?>">
                                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="30" height="30" border="0"><br><font size="1">Adicionar<br>Electivos Extrinsecos</font>
                                            </a>
                                        </td>
                                    </tr>
                                </table>

                                            <?
                                        }


                                        ?>
                            </table>
                        </td>

                    </tr>

                </table>
            </td>
        </tr>

    </tbody>
</table>

        <?



    }

    function HorarioEstudianteCancelacion($configuracion, $resultado_grupos,$registroEstudiante,$planEstudioGeneral,$codProyecto,$permitirCancelar) {

        $cadena_sql=$this->sql->cadena_sql($configuracion,"estado_estudiante",$registroEstudiante[0][0]); //echo $cadena_sql;exit;
        $resultado_estado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        ?>
<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
    <tbody>
        <tr>
            <td>
                        <?if($resultado_grupos!=NULL) {

                            $variable=array($registroEstudiante[0][5],$registroEstudiante[0][6]);

                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_adiciones_estudiantes",$variable);
                            $resultado_adicionesHab=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                            ?>

                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                    <thead class='cuadro_plano centrar'>
                    <th><center><?echo "Horario de Clases";?></center></th>
                    </thead>


                    <tr>
                        <td>
                            <table class='contenidotabla'>
                                <thead class='cuadro_color'>
                                <td class='cuadro_plano centrar'>Cod.</td>
                                <td class='cuadro_plano centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </td>
                                <td class='cuadro_plano centrar' width="25">Grupo </td>
                                <td class='cuadro_plano centrar' width="25">Cr&eacute;ditos</td>
                                <td class='cuadro_plano centrar' width="25">Clasificaci&oacute;n</td>
                                <td class='cuadro_plano centrar' width="60">Lun </td>
                                <td class='cuadro_plano centrar' width="60">Mar </td>
                                <td class='cuadro_plano centrar' width="60">Mie </td>
                                <td class='cuadro_plano centrar' width="60">Jue </td>
                                <td class='cuadro_plano centrar' width="60">Vie </td>
                                <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                <td class='cuadro_plano centrar' width="60">Dom </td>
                                <?if ($permitirCancelar==1){?>
                                <td class='cuadro_plano centrar'>Cancelar</td>
                                <?}
                                else{}?>
                                </thead>

                                            <?
                                            //recorre cada uno del los grupos
                                            for($j=0;$j<count($resultado_grupos);$j++) {

                                                //
                                                $variables[0][0]=$resultado_grupos[$j][0];  //idEspacio
                                                $variables[0][1]=$resultado_grupos[$j][1];  //proyecto
                                                $variables[0][2]=$resultado_grupos[$j][2];  //grupo
                                                $variables[0][5]=$resultado_grupos[$j][5];  //nombre del espacio
                                                $variables[0][6]=$resultado_grupos[$j][6];  //codigo del estudiante
                                                $variables[0][7]=$resultado_grupos[$j][7];  //plan de estudios del estudiante
                                                $variables[0][8]=$resultado_grupos[$j][8];  //nombre del estudiante
                                                $variables[0][9]=$resultado_grupos[$j][9];  //creditos del espacio
                                                $variables[0][10]=$resultado_grupos[$j][10];  //clasificacion
                                                //$variables[0][11]=$resultado_grupos[$j][11];  //apellido2 del estudiante

                                                //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                                                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos",$variables);
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );
                                                ?>
                                <tr>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td>
                                    <td class='cuadro_plano'><?echo $resultado_grupos[$j][5];?></td>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][2];?></td>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][9];?></td>
                                    <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][10];?></td>
                                                    <?


                                                    //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                                                    for($i=1; $i<8; $i++) {
                                                        ?><td class='cuadro_plano centrar' onmouseover="this.bgColor='#E6E6E6'" onmouseout="this.bgColor=''"><?

                                                            //Recorre el arreglo del resultado de los horarios
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

                                                    if ($permitirCancelar==1){
                                                    ?>
                                    <td class='cuadro_plano centrar'>

                                                        <?
                                                        $creditosInscritos=$this->calcularCreditos($configuracion, $resultado_grupos);

                                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                        $variable="pagina=registroCancelarInscripcionCreditosEstudiante";
                                                        $variable.="&opcion=verificar";
                                                        $variable.="&planEstudioGeneral=".$planEstudioGeneral;
                                                        $variable.="&codProyecto=".$codProyecto;
                                                        $variable.="&codEstudiante=".$variables[0][6];
                                                        $variable.="&proyecto=".$variables[0][1];
                                                        $variable.="&codEspacio=".$variables[0][0];
                                                        $variable.="&grupo=".$variables[0][2];
                                                        $variable.="&planEstudio=".$variables[0][7];
                                                        $variable.="&nombre=".$resultado_grupos[$j][5];
                                                        $variable.="&creditosInscritos=".$creditosInscritos;
                                                        $variable.="&estado_est=".trim($resultado_estado[0][0]);


                                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                        $this->cripto=new encriptar();
                                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                        ?>
                                        <a href="<?= $pagina.$variable ?>" >
                                            <img src="<?echo $configuracion["site"].$configuracion["grafico"]."/x.png"?>" border="0" width="25" height="25">
                                        </a>


                                    </td>
                                    <?}
                                    else{}
                                    ?>
                                </tr>
                                                <?}
                                        }else {?>
                                <tr>
                                    <td class='cuadro_plano centrar'>
                                        No se encontraron datos de espacios adicionados
                                    </td>
                                </tr>
                                            <?}


                                        ?>
                            </table>
                        </td>

                    </tr>

                </table>

                        <?
                        $codEstudiante=$this->usuario;
                        list($valor1,$valor2,$valor3,$valor4,$valor5,$valor6)=$this->porcentajeParametros($configuracion, $codEstudiante);
                        ?>
                <table align='center' width='85%' cellspacing='0' cellpadding='2'>
                    <tr class="centrar">
                        <td class='cuadro_plano centrar'>
                                    <?
                                    echo $valor1;
                                    echo $valor2;
                                    echo $valor3;
                                    echo $valor4;
                                    echo $valor5;
                                    echo $valor6;
                                    ?>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>

    </tbody>
</table>

        <?



    }

    function calcularCreditos($configuracion,$registroGrupo) {
        $suma=0;
        for($i=0;$i<count($registroGrupo);$i++) {
            $suma+=$registroGrupo[$i][9];
        }

        return $suma;

    }




    function porcentajeParametros($configuracion,$codEstudiante) {
        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscarPlan",$codEstudiante);//echo $this->cadena_sql;exit;
        $registroPlan=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
        $planEstudiante=$registroPlan[0][1];

        $cadena_sql=$this->sql->cadena_sql($configuracion,"creditosPlan",$planEstudiante);//echo $cadena_sql;exit;
        $registroCreditosGeneral=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        $totalCreditos= $registroCreditosGeneral[0][0];
        $OB= $registroCreditosGeneral[0][1];
        $OC= $registroCreditosGeneral[0][2];
        $EI= $registroCreditosGeneral[0][3];
        $EE= $registroCreditosGeneral[0][4];

        $OBEst=0;
        $OCEst=0;
        $EIEst=0;
        $EEEst=0;
        $CPEst=0;

        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"espaciosAprobados",$codEstudiante);//echo $this->cadena_sql;exit;
        $registroEspaciosAprobados=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
        //var_dump($registroEspaciosAprobados);//exit;

        for($i=0;$i<=count($registroEspaciosAprobados);$i++) {

            switch($registroEspaciosAprobados[$i][3]) {
                case 1:
                    $OBEst=$OBEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 2:
                    $OCEst=$OCEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 3:
                    $EIEst=$EIEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 4:
                    $EEEst=$EEEst+$registroEspaciosAprobados[$i][2];
                    break;

                case 5:
                        $CPEst=$CPEst+$registroEspaciosAprobados[$i][2];
                    break;

                case '':
                    $totalCreditosEst=$totalCreditosEst+0;
                    break;

            }
        }
        $OBEst=$OBEst+$CPEst;
        $totalCreditosEst=$OBEst+$OCEst+$EIEst+$EEEst;

            if($totalCreditos==0){$porcentajeCursado=0;}
            else{$porcentajeCursado=$totalCreditosEst*100/$totalCreditos;}
            if($OB==0){$porcentajeOBCursado=0;}
            else{$porcentajeOBCursado=$OBEst*100/$OB;}
            if($OC==0){$porcentajeOCCursado=0;}
            else{$porcentajeOCCursado=$OCEst*100/$OC;}
            if($EI==0){$porcentajeEICursado=0;}
            else{$porcentajeEICursado=$EIEst*100/$EI;}
            if($EE==0){$porcentajeEECursado=0;}
            else{$porcentajeEECursado=$EEEst*100/$EE;}

        if($totalCreditos>0) {
            $vista="
            <table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                 <caption class='sigma'>Cr&eacute;ditos Ac&aacute;demicos</caption>
                      <tr>
                          <th class='sigma centrar' width='16%'>Clasificaci&oacute;n</th>
                          <th class='sigma centrar' width='10%'>Total</th>
                          <th class='sigma centrar' width='14%'>Aprobados</th>
                          <th class='sigma centrar' width='14%'>Por Aprobar</th>
                          <th class='sigma centrar' width='46%'>% Cursado</th>
                      </tr></table>";

            $vistaOB="<table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                   <tr>
                      <td class='sigma centrar cuadro_plano' width='16%'>OB
                      </td>
                      <td class='sigma centrar cuadro_plano' width='10%'>".$OB."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$OBEst."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$FaltanOB=$OB-$OBEst."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='46%'>";
            if($porcentajeOBCursado==0) {
                $vistaOB.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='sigma centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td>
                       </table>";
                $OBEst=0;
            }else if($porcentajeOBCursado==100) {
                $vistaOB.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='sigma centrar' colspan='2' bgcolor='#5471ac'> ".round($porcentajeOBCursado,1)."%
                           </td>
                           </table>";
            }else if($porcentajeOBCursado!=0 AND $porcentajeOBCursado!=100) {
                $vistaOB.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeOBCursado."%' class='sigma centrar' bgcolor='#5471ac'> ".round($porcentajeOBCursado,1)."%
                           </td>
                           <td class='sigma centrar' width='".$TotalOB=100-$porcentajeOBCursado."%' bgcolor='#fffcea'>
                           </td>
                           </table>";
            }
            $vistaOB.="</td>
                        </tr></table>
                      ";


            $vistaOC="<table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                   <tr>
                      <td class='sigma centrar cuadro_plano' width='16%'>OC
                      </td>
                      <td class='sigma centrar cuadro_plano' width='10%'>".$OC."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$OCEst."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$FaltanOC=$OC-$OCEst."
                      </td>
                      <td class='sigma centrar cuadro_plano' width='46%'>";
            if($porcentajeOCCursado==0) {
                $vistaOC.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='sigma centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td>
                       </table>";
                $OCEst=0;
            }else if($porcentajeOCCursado==100) {
                $vistaOC.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='sigma centrar' colspan='2' bgcolor='#6b8fd4'> ".round($porcentajeOCCursado,1)."%
                           </td>
                           </table>";
            }else if($porcentajeOCCursado!=0 AND $porcentajeOCCursado!=100) {
                $vistaOC.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeOCCursado."%' class='sigma centrar' bgcolor='#6b8fd4'> ".round($porcentajeOCCursado,1)."%
                           </td>
                           <td class='sigma centrar' width='".$TotalOC=100-$porcentajeOCCursado."%' bgcolor='#fffcea'>
                           </td>
                           </table>";
            }
            $vistaOC.="</td>
                        </tr></table>";



            $vistaEI="<table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                    <tr>
                      <td class='sigma centrar cuadro_plano' width='16%'>EI</td>
                      <td class='sigma centrar cuadro_plano' width='10%'>".$EI."</td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$EIEst."</td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$FaltanEI=$EI-$EIEst."</td>
                      <td class='sigma centrar cuadro_plano' width='46%'>";
            if($porcentajeEICursado==0) {
                $vistaEI.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='sigma centrar' colspan='2' bgcolor='#fffcea'> 0%</td>
                       </table>";
                $EIEst=0;
            }else if($porcentajeEICursado==100) {
                $vistaEI.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='sigma centrar' colspan='2' bgcolor='#238387'> ".round($porcentajeEICursado,1)."%</td>
                           </table>";
            }else if($porcentajeEICursado!=0 AND $porcentajeEICursado!=100) {
                $vistaEI.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeEICursado."%' class='sigma centrar' bgcolor='#238387'> ".round($porcentajeEICursado,1)."%</td>
                           <td class='sigma centrar' width='".$TotalEI=100-$porcentajeEICursado."%' bgcolor='#fffcea'></td>
                           </table>";
            }
            $vistaEI.="</td>
                        </tr></table>";


            $vistaEE="<table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                      <tr>
                      <td class='sigma centrar cuadro_plano' width='16%'>EE</td>
                      <td class='sigma centrar cuadro_plano' width='10%'>".$EE."</td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$EEEst."</td>
                      <td class='sigma centrar cuadro_plano' width='14%'>".$FaltanEE=$EE-$EEEst."</td>
                      <td class='sigma centrar cuadro_plano' width='46%'>";
            if($porcentajeEECursado==0) {
                $vistaEE.="
                       <table align='center' width='100%' cellspacing='0'>
                        <td width='100%' class='sigma centrar' colspan='2' bgcolor='#fffcea'> 0%
                       </td>
                       </table>";
                $EEEst=0;
            }else if($porcentajeEECursado==100) {
                $vistaEE.="
                           <table align='center' width='100%' cellspacing='0'>
                           <td width='100%' class='sigma centrar' colspan='2' bgcolor='#61b7bc'> ".round($porcentajeEECursado,1)."%
                           </td>
                           </table>";
            }else if($porcentajeEECursado!=0 AND $porcentajeEECursado!=100) {
                $vistaEE.="<table align='center' width='100%' cellspacing='0'>
                           <td width='".$porcentajeEECursado."%' class='sigma centrar' bgcolor='#61b7bc'> ".round($porcentajeEECursado,1)."%
                           </td>
                           <td class='sigma centrar' width='".$TotalEE=100-$porcentajeEECursado."%' bgcolor='#fffcea'>
                           </td>
                           </table>";
            }
            $vistaEE.="</td>
                        </tr></table>";

            $vistaTotal="<table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                         <tr>
                          <td class='sigma centrar cuadro_plano' width='16%'>Total</td>
                          <td class='sigma centrar cuadro_plano' width='10%'>".$totalCreditos."</td>
                          <td class='sigma centrar cuadro_plano' width='14%'>".$totalCreditosEst."</td>
                          <td class='sigma centrar cuadro_plano' width='14%'>".$Faltan=$totalCreditos-$totalCreditosEst."</td>
                          <td class='sigma centrar cuadro_plano' width='46%'>";
            if($porcentajeCursado==0) {
                $vistaTotal.="
                               <table align='center' width='100%' cellspacing='0'>
                                <td width='100%' class='sigma centrar' colspan='2' bgcolor='#fffcea'> 0%</td>
                               </table>";
                $totalCreditosEst=0;
            }else if($porcentajeCursado==100) {
                $vistaTotal.="
                           <table align='center' width='100%' cellspacing='0'>
                                <td width='100%' class='sigma centrar' colspan='2' bgcolor='#b1232d'> ".round($porcentajeCursado,1)."%</td>
                           </table>";
            }else if($porcentajeCursado!=0 AND $porcentajeCursado!=100) {
                $vistaTotal.="<table align='center' width='100%' cellspacing='0'>
                                   <td width='".$porcentajeCursado."%' class='sigma centrar' bgcolor='#b1232d'> ".round($porcentajeCursado,1)."%</td>
                                   <td class='sigma centrar' width='".$Total=100-$porcentajeCursado."%' bgcolor='#fffcea'></td>
                                </table>";
            }
            $vistaTotal.="</td>
                            </tr>
                      </table>";

        }
        else {
            $vista="
            <table class='sigma contenidotabla' align='center' width='100%' cellspacing='0' bgcolor='#fffffa'>
                 <tr>
                      <td class='cuadro_plano centrar texto_negrita' colspan='6'>El Proyecto Curricular no ha definido los rangos de cr&eacute;ditos<br>para el plan de estudios
                      </td>
                 </tr>
                 <tr>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='16%'>Clasificaci&oacute;n
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='10%'>Total
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='14%'>Aprobados
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='14%'>Por Aprobar
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='46%'>% Cursado
                      </td>
                      <td class='bloquelateralayuda cuadro_plano centrar texto_negrita'  width='54%'>
                      </td>
                   </tr>
                   </table>";

            $vistaOB="<table align='center' width='550%' cellspacing='0' cellpadding='2' bgcolor='#fffffa'>
                                <tr>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='16%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='10%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar'  width='14%'>-
                                </td>
                                <td class='bloquelateralayuda cuadro_plano centrar' bgcolor='#fffcea' width='46%'> 0%
                                </td>
                                </tr>
                             </table>";
            $vistaOC=$vistaOB;
            $vistaEI=$vistaOB;
            $vistaEE=$vistaOB;
            $vistaTotal=$vistaOB;
        }
        return array($vista, $vistaOB, $vistaOC, $vistaEI, $vistaEE, $vistaTotal);

    }

}
?>
