<?php
/**
 * Funcion registroCambiarGrupoInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage CambiarGrupo
 * @author Edwin Sanchez
 * @version 0.0.0.1
 * Fecha: 19/11/2010
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
/**
 * Incluye la clase sesion.class.php
 * Esta clase se encarga de crear, modificar y borrar sesiones
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
/**
 * Clase funciones_registroCambiarGrupoInscripcionGrupoCoordinador
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage CambiarGrupo
 */
class funciones_registroCambiarGrupoInscripcionGrupoCoordinador extends funcionGeneral
{
    /**
     * Método constructor que crea el objeto sql de la clase funciones_registroCambiarGrupoInscripcionGrupoCoordinador
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     */
    function __construct($configuracion, $sql) {
        /**
         * Incluye la clase encriptar.class.php
         *
         * Esta clase incluye funciones de encriptacion para las URL
         */
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        $this->sql=$sql;
        /**
         * Intancia para crear la conexion ORACLE
         */
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");
        /**
         * Instancia para crear la conexion General
         */
        $this->acceso_db=$this->conectarDB($configuracion,"");
        /**
         * Instancia para crear la conexion de MySQL
         */
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        $this->formulario="registroCambiarGrupoInscripcionGrupoCoordinador";
        /**
         * Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
         */        
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    /**
     * Funcion que permite buscar otros grupos donde se pueda realizar el cambio de grupo
     *
     * Esta funcion busca los grupos del espacio academico y verifica que cumpla las reglas como cruce y cupo, esta funcion es valida para un estudiante
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @global int '$_REQUEST['codEstudiante']' Codigo del estudiante
     * @global int '$_REQUEST['codEspacio']' Codigo del espacio academico
     * @global int '$_REQUEST['planEstudio']' Numero del plan de estudio
     * @global int '$_REQUEST['nroGrupo']' Numero del grupo actual
     * @global string '$_REQUEST['nombreEspacio']' Nombre del espacio academico
     * @global int '$_REQUEST['nroCreditos']' Numero de creditos del espacio academico
     * @global int '$_REQUEST['nombreEstudiante']' nombre del estudiante
     * @global int '$_REQUEST['clasificacion']' Clasificacion del espacio academico
     */
    function buscarGrupo($configuracion)
    {
        $codEstudiante=$_REQUEST['codEstudiante'];
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nroGrupo=$_REQUEST['nroGrupo'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nombreEstudiante=$_REQUEST['nombreEstudiante'];
        $clasificacion=$_REQUEST['clasificacion'];
        $proyecto=$_REQUEST['proyecto'];

        $cadena_sql=$this->sql->cadena_sql($configuracion,"datosCoordinador", $this->usuario);
        $resultado_craCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        ?>
<table class="contenidotabla centrar" border="0">
    <tr class="bloquelateralcuerpo">
        <td class="centrar" width="33%">
                    <?

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&opcion2=cuadroRegistro";
                    $variable.="&codEspacio=".$codEspacio;
                    $variable.="&nroGrupo=".$nroGrupo;
                    $variable.="&planEstudio=".$planEstudio;
                    $variable.="&codProyecto=".$proyecto;
                    $variable.="&nombreEspacio=".$nombreEspacio;
                    
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="35" height="35" border="0"><br><b>Atras</b>
            </a>

        <td class="centrar" width="33%">
                    <?

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminInscripcionGrupoCoordinador";
                    $variable.="&opcion=consultar";
                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                    $variable.="&codProyecto=".$proyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br><b>Inicio</b>
            </a>
        </td>
        <td class="centrar" width="33%">
             <a href="javascript:history.forward();" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png" width="35" height="35" border="0"><br><b>Adelante</b>
            </a>
        </td>
    </tr>
</table>
        <?
        for($r=0;$r<count($resultado_craCoordinador);$r++)
        {

        $codProyecto=$resultado_craCoordinador[$r][1];
        $variables=array($codEspacio,$codProyecto,$planEstudio,$nroGrupo);

        if($clasificacion=='4')
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"grupos_electiva", $variables);
                $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            }else
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"grupos_proyecto", $variables);
                    $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                }


        if(is_array($resultado_grupos)) {
            ?>
<table width="100%" border="0" align="center" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                    <thead class='texto_subtitulo centrar'>
                        <tr>
                            <td><center><?echo "Proyecto Curricular - ".$codProyecto;?></center></td>
                        </tr>
                        <tr>
                            <td><center><?echo $codEspacio." - ".$nombreEspacio;?></center></td>
                        </tr>
                        <tr>
                            <td><center><?echo $codEstudiante." - ".$nombreEstudiante;?></center></td>
                        </tr>
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
                                <td class='cuadro_plano centrar' >Cambiar Grupo</td>
                                </thead>
                                            <?

                                            for($j=0;$j<count($resultado_grupos);$j++) {

                                                $variables[3]=$resultado_grupos[$j][0];

                                                $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_grupos", $variables);
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos_registrar", $variables);
                                                $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableCodigo=array($codEstudiante,$codEspacio);

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

                                                $variableCupo=array($codEstudiante,$resultado_grupos[$j][0],$codEspacio);

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_ins", $variableCupo);
                                                $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_cupo", $variableCupo);
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
                                         if($cruce!=true){
                                                            ?>
                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                            <input type="hidden" name="nroGrupoAnt" value="<?echo $nroGrupo?>">
                                            <input type="hidden" name="nroGrupoNue" value="<?echo $resultado_grupos[$j][0]?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codEstudiante?>">
                                            <input type="hidden" name="codEspacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                                            <input type="hidden" name="proyecto" value="<?echo $proyecto?>">
                                            <input type="hidden" name="opcion" value="cambiar">
                                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                            <input type="image" name="cambiar" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/reload.png" >

                                        </form>
                                    </td>
                                                    <?
                                                    }
                                                    else
                                                        {
                                                        ?>No puede cambiar de grupo por cruce</td><?
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
        No existen grupos registrados
    </td>
</tr>
        <?
        }
        ?>
</tbody>
</table>

    <?
        }

    }

    /**
     * Funcion que se encarga de guardar el nuevo grupo
     *
     * Esta funcion permite guardar el nuevo grupo al que desea ser cambiado el estudiante
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @global int '$_REQUEST['codEstudiante']' Codigo del estudiante
     * @global int '$_REQUEST['codEspacio']' Codigo del espacio academico
     * @global int '$_REQUEST['planEstudio']' Numero del plan de estudio
     * @global int '$_REQUEST['nroGrupoAnt']' Numero del grupo antes del cambio
     * @global int '$_REQUEST['nroGrupoNue']' Numero del grupo despues del cambio
     * @global int '$_REQUEST['nroCreditos']' Numero de creditos del espacio academico
     * @global int '$_REQUEST['codProyecto']' Codigo del proyecto curricular
     */
    function cambiarGrupoEstudiante($configuracion)
    {

        $variable['codEspacio']=$_REQUEST['codEspacio'];
        $variable['planEstudio']=$_REQUEST['planEstudio'];
        $variable['codProyecto']=$_REQUEST['codProyecto'];
        $variable['codEstudiante']=$_REQUEST['codEstudiante'];
        $variable['nroGrupoNue']=$_REQUEST['nroGrupoNue'];
        $variable['nroGrupoAnt']=$_REQUEST['nroGrupoAnt'];
        $variable['nroCreditos']=$_REQUEST['nroCreditos'];
        $variable['proyecto']=$_REQUEST['proyecto'];

        $cadena_sql_pertenecePlan=$this->sql->cadena_sql($configuracion,"pertenecePlanEstudio", $variable['codEstudiante']);
        $resultado_pertenecePlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_pertenecePlan,"busqueda" );
        if($resultado_pertenecePlan[0][0]==$variable['planEstudio'])
          {
            
//verifica cruce
        $variables=array($variable['codEspacio'],$variable['codProyecto'],$variable['planEstudio'],$variable['nroGrupoNue'],$variable['proyecto']);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos_registrar", $variables);
        $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $variableCodigo=array($variable['codEstudiante'],$variable['codEspacio']);
            $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variableCodigo);
            $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            for($n=0;$n<count($resultado_horarios_registrado);$n++)
                {
                    for($m=0;$m<count($resultado_horarios_registrar);$m++)
                    {
                        if($band==0)
                            {
                                if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n]))
                                    {
                                        $band=1;
                                        $cruce=true;
                                        break;
                                    }
                            }
                     }
                }
            if ($band==1)
            {
                        echo "<script>alert ('No se pudo realizar el cambio de grupo. Presenta cruce.');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=adminConsultarInscripcionGrupoCoordinador";
                        $ruta.="&opcion=verGrupo";
                        $ruta.="&opcion2=cuadroRegistro";
                        $ruta.="&codEspacio=".$variable['codEspacio'];
                        $ruta.="&nroGrupo=".$variable['nroGrupoAnt'];
                        $ruta.="&planEstudio=".$variable['planEstudio'];
                        $ruta.="&codProyecto=".$variable['proyecto'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                        echo "<script>location.replace('".$pagina.$ruta."')</script>";
                        exit;
            }
//fin verifica cruce            
            $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarGrupoOracle", $variable);
            $resultado_actGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

            if($resultado_actGrupo)
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo", $variable);
                    $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $variable['anno']=$resultadoPeriodo[0][0];
                    $variable['periodo']=$resultadoPeriodo[0][1];

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarGrupoMySQL", $variable);
                    $resultado_actGrupo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                    $variablesRegistro[0]=$this->usuario;
                    $variablesRegistro[1]=date('YmdGis');
                    $variablesRegistro[2]='3';
                    $variablesRegistro[3]='Cambio grupo del Espacio Académico';
                    $variablesRegistro[4]=$variable['anno']."-".$variable['periodo'].",".$variable['codEspacio'].",".$variable['nroGrupoAnt'].",".$variable['nroGrupoNue'].",".$variable['planEstudio'].",".$variable['codProyecto'];
                    $variablesRegistro[5]=$variable['codEstudiante'];

                    $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                    $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
                    $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    $variablesInscritosAnt=array($variable['codEspacio'],$variable['nroGrupoAnt']);

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos", $variablesInscritosAnt);
                    $resultado_InscritosAnt=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $variablesInscritosAnt[2]=$resultado_InscritosAnt[0][0];
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarCupos", $variablesInscritosAnt);
                    $resultado_ActualizacionAnt=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

                    //Actualiza el cupo del nuevo grupo de los estudiantes
                    $variablesInscritosNue=array($variable['codEspacio'],$variable['nroGrupoNue']);
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos", $variablesInscritosNue);
                    $resultado_InscritosNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $variablesInscritosNue[2]=$resultado_InscritosNue[0][0];
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarCupos", $variablesInscritosNue);
                    $resultado_ActualizacionNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

                    echo "<script>alert ('El cambio de grupo para el estudiante ".$variable['codEstudiante']." se ejecuto exitosamente. Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta="pagina=adminConsultarInscripcionGrupoCoordinador";
                    $ruta.="&opcion=verGrupo";
                    $ruta.="&opcion2=cuadroRegistro";
                    $ruta.="&codEspacio=".$variable['codEspacio'];
                    $ruta.="&nroGrupo=".$variable['nroGrupoAnt'];
                    $ruta.="&planEstudio=".$variable['planEstudio'];
                    $ruta.="&codProyecto=".$variable['proyecto'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                    echo "<script>location.replace('".$pagina.$ruta."')</script>";
                    exit;
                }else
                    {
                        echo "<script>alert ('El cambio de grupo para el estudiante ".$variable['codEstudiante']." no se pudo ejecutar');</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=adminConsultarInscripcionGrupoCoordinador";
                        $ruta.="&opcion=verGrupo";
                        $ruta.="&opcion2=cuadroRegistro";
                        $ruta.="&codEspacio=".$variable['codEspacio'];
                        $ruta.="&nroGrupo=".$variable['nroGrupoAnt'];
                        $ruta.="&planEstudio=".$variable['planEstudio'];
                        $ruta.="&codProyecto=".$variable['proyecto'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                        echo "<script>location.replace('".$pagina.$ruta."')</script>";
                        exit;
                    }
          }
          else
          {
              echo "<script>alert ('El estudiante no pertenece al plan de estudios ".$variable['planEstudio'].". No se puede realizar el cambio de grupo.');</script>";
              $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
              $ruta="pagina=adminConsultarInscripcionGrupoCoordinador";
              $ruta.="&opcion=verGrupo";
              $ruta.="&opcion2=cuadroRegistro";
              $ruta.="&codEspacio=".$variable['codEspacio'];
              $ruta.="&nroGrupo=".$variable['nroGrupoAnt'];
              $ruta.="&planEstudio=".$variable['planEstudio'];
              $ruta.="&codProyecto=".$variable['proyecto'];

              include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
              $this->cripto=new encriptar();
              $ruta=$this->cripto->codificar_url($ruta,$configuracion);

              echo "<script>location.replace('".$pagina.$ruta."')</script>";
              exit;

          }
    }

    /**
     * Funcion que permite buscar otros grupos para realizar el cambio de grupo
     *
     * Funcion que busca los grupos del espacio academico y realiza validaciones como cruce y cupo, esta funcion es valida para 1 o mas estudiantes
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @global int '$_REQUEST['total']' Total de estudiantes inscritos en el grupo
     * @global int '$_REQUEST['codEspacio']' Codigo del espacio academico
     * @global int '$_REQUEST['planEstudio']' Numero del plan de estudio
     * @global int '$_REQUEST['nroGrupo']' Numero de grupo actual
     * @global string '$_REQUEST['nombreEspacio']' Nombre del espacio academico
     * @global int '$_REQUEST['nroCreditos']' Numero de creditos del espacio academico
     * @global int '$_REQUEST['clasificacion']' Clasificacion del espacio academico
     */
    function buscarGrupoVariosEstudiantes($configuracion)
    {
        $totalSeleccionados=0;
        $total=$_REQUEST['total'];
        
            for($i=0;$i<$total;$i++)
            {
                $codigo['codEstudiante-'.$i]=$_REQUEST['codEstudiante-'.$i];
                if($codigo['codEstudiante-'.$i]!=NULL)
                    {
                        $totalSeleccionados++;
                    }
            }

        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nroGrupo=$_REQUEST['nroGrupo'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $clasificacion=$_REQUEST['clasificacion'];
        $proyecto=$_REQUEST['proyecto'];

        $cadena_sql=$this->sql->cadena_sql($configuracion,"datosCoordinador", $this->usuario);
        $resultado_craCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if($totalSeleccionados==0)
            {
                echo "<script>alert('Por favor seleccione los estudiantes a los que desea cambiar de grupo')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                $variable.="&opcion=verGrupo";
                $variable.="&opcion2=cuadroRegistro";
                $variable.="&codEspacio=".$codEspacio;
                $variable.="&nroGrupo=".$nroGrupo;
                $variable.="&planEstudio=".$planEstudio;
                $variable.="&codProyecto=".$proyecto;
                $variable.="&nombreEspacio=".$nombreEspacio;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
                exit;
            }


        ?>
<style type="text/css">
#toolTipBox {
       display: none;
       padding: 5;
       font-size: 12px;
       border: black solid 1px;
       font-family: verdana;
       position: absolute;
       background-color: #ffd038;
       color: 000000;
}
</style>
<table class="contenidotabla centrar" border="0">
    <tr class="bloquelateralcuerpo">

        <td class="centrar" width="33%">
                    <?

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&opcion2=cuadroRegistro";
                    $variable.="&codEspacio=".$codEspacio;
                    $variable.="&nroGrupo=".$nroGrupo;
                    $variable.="&planEstudio=".$planEstudio;
                    $variable.="&codProyecto=".$proyecto;
                    $variable.="&nombreEspacio=".$nombreEspacio;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br><b>Inicio</b>
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
                        <tr>
                            <td><center><?echo $codEspacio." - ".$nombreEspacio;?></center></td>
                        </tr>
                        <tr>
                            <td><center><?echo "Estudiantes seleccionados: ".$totalSeleccionados;?></center></td>
                        </tr>
                    </thead>
        <?

        for($a=0;$a<count($resultado_craCoordinador);$a++)
          {
        $codProyecto=$resultado_craCoordinador[$a][1];

        $variables=array($codEspacio,$codProyecto,$planEstudio,$nroGrupo);

        if($clasificacion==4)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,"grupos_electiva", $variables);
                $gruposProyecto=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            }else
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"grupos_proyecto", $variables);
                    $gruposProyecto=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                }

        if(is_array($gruposProyecto)) {
            ?>
                        <tr class="cuadro_plano centrar">
                            <td><?echo "Proyecto Curricular: ".$codProyecto;?></td>
                        </tr>
                    <tr>
                        <td>
                            <table class='contenidotabla'>
                                <thead class='cuadro_color'>
                                  <tr>
                                <td class='cuadro_plano centrar' width="25">Grupo </td>
                                <td class='cuadro_plano centrar' width="60">Lun </td>
                                <td class='cuadro_plano centrar' width="60">Mar </td>
                                <td class='cuadro_plano centrar' width="60">Mie </td>
                                <td class='cuadro_plano centrar' width="60">Jue </td>
                                <td class='cuadro_plano centrar' width="60">Vie </td>
                                <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                <td class='cuadro_plano centrar' width="60">Dom </td>
                                <td class='cuadro_plano centrar' width="20">Cupo Disponible </td>
                                <td class='cuadro_plano centrar' width="30">Estudiantes con Cruce </td>
                                <td class='cuadro_plano centrar' >Cambiar Grupo</td>
                                </tr>
                                </thead>

                                            <?
                                            $resultado_grupos=$gruposProyecto;
                                            for($j=0;$j<count($resultado_grupos);$j++) {

                                                $variables[3]=$resultado_grupos[$j][0];
                                               
                                                $cruce=0;

                                                $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_grupos", $variables);
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos_registrar", $variables);
                                                $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                unset($estudiantesCruce);
                                                
                                                for($e=1;$e<$total;$e++)
                                                {
                                                    $band=0;
                                                    $variableCodigo=$codigo['codEstudiante-'.$e];

                                                    if($variableCodigo[0]!=NULL)
                                                    {
                                                        $variablesCambio=array($variableCodigo,$codEspacio);
                                                        $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variablesCambio);
                                                        $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                        for($n=0;$n<count($resultado_horarios_registrado);$n++) {
                                                                    for($m=0;$m<count($resultado_horarios_registrar);$m++) {

                                                                        if($band==0){

                                                                        if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n])) {

                                                                            $estudiantesCruce[$cruce]=$variableCodigo;
                                                                            $band=1;
                                                                            $cruce++;
                                                                            break;
                                                                        }
                                                                        }
                                                                    }
                                                                }
                                                    }
                                                }
                                                
                                                $cupoDisponible=($resultado_horarios[$j][4]-$resultado_horarios[$j][5]);

                                                $variableCupo=array($codEstudiante,$resultado_grupos[$j][0],$codEspacio);

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_ins", $variableCupo);
                                                $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,"cupo_grupo_cupo", $variableCupo);
                                                $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                unset($cupoDisponible);

                                                $cupoDisponible=($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]);

                                                ?><tr>
                                                  <td class='cuadro_plano centrar'>
                                                      <?echo $resultado_grupos[$j][0];?>
                                                  </td><?
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
                                                        }if($cruce>0)
                                                            {
                                                            }
                                                    ?><td class='cuadro_plano centrar'>
                                                            <?echo $cupoDisponible?>
                                                        </td>
                                                        <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                                                        <td class='cuadro_plano centrar' onmouseover="toolTip('<?if($cruce>0){for($v=0;$v<=$cruce;$v++){ echo $estudiantesCruce[$v]."<br>";}}else{echo "No hay cruce";}?>',this)">
                                                            <div class="centrar">
                                                            <span id="toolTipBox" width="200" ></span>
                                                            <?echo $cruce; unset($cruce);?>
                                                            </div>
                                                        </td>

                                    <td class='cuadro_plano centrar'>

                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                                            <?
                                                for($r=1;$r<$total;$r++)
                                                {
                                                    $s=$r-1;
                                                    echo "<input type='hidden' name='codEstudiante-".$r."' value='".$codigo['codEstudiante-'.$s]."'> ";
                                                }
                                            ?>
                                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                            <input type="hidden" name="nroGrupoAnt" value="<?echo $nroGrupo?>">
                                            <input type="hidden" name="nroGrupoNue" value="<?echo $resultado_grupos[$j][0]?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codEstudiante?>">
                                            <input type="hidden" name="codEspacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                                            <input type="hidden" name="totalEstudiantes" value="<?echo $total?>">
                                            <input type="hidden" name="nombreEspacio" value="<?echo $nombreEspacio?>">
                                            <input type="hidden" name="proyecto" value="<?echo $proyecto?>">
                                            <input type="hidden" name="opcion" value="cambiarVarios">
                                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                            <input type="image" name="cambiar" width="30" height="30" src="<?echo $configuracion['site'].$configuracion['grafico']?>/reload.png" >

                                        </form>
                                    </td>
                            </tr>
                                        <?
                                        }
                                        ?>
                </table>
            </td>

        </tr>
<?
        }else {
            ?>
<tr class="cuadro_plano centrar">
    <td><?echo "Proyecto Curricular: ".$codProyecto;?></td>
</tr>
<tr>
    <td class="cuadro_plano centrar">
        No existen grupos registrados
    </td>
</tr>
        <?
        }
          }
        ?>
</table>
</td>
</tr>
</tbody>
</table>
    <?
    }

    /**
     * Funcion que se encarga de guardar el nuevo grupo para 1 o varios estudiantes
     *
     * Esta funcion permite guardar el nuevo grupo al que desea ser cambiado los estudiantes
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @global int '$_REQUEST['codEstudiante']' Codigo del estudiante
     * @global int '$_REQUEST['codEspacio']' Codigo del espacio academico
     * @global int '$_REQUEST['planEstudio']' Numero del plan de estudio
     * @global int '$_REQUEST['nroGrupoAnt']' Numero del grupo antes del cambio
     * @global int '$_REQUEST['nroGrupoNue']' Numero del grupo despues del cambio
     * @global int '$_REQUEST['nroCreditos']' Numero de creditos del espacio academico
     * @global int '$_REQUEST['codProyecto']' Codigo del proyecto curricular
     * @global string '$_REQUEST['nombreEspacio']' Nombre del espacio academico
     * @global int '$_REQUEST['totalEstudiantes']' Total de estudiantes seleccionados
     */
    function cambiarGrupoVarios($configuracion)
    {
        
        $variable['codEspacio']=$_REQUEST['codEspacio'];
        $variable['planEstudio']=$_REQUEST['planEstudio'];
        $variable['codProyecto']=$_REQUEST['codProyecto'];
        $variable['nroGrupoNue']=$_REQUEST['nroGrupoNue'];
        $variable['nroGrupoAnt']=$_REQUEST['nroGrupoAnt'];
        $variable['nroCreditos']=$_REQUEST['nroCreditos'];
        $variable['nombreEspacio']=$_REQUEST['nombreEspacio'];
        $variable['proyecto']=$_REQUEST['proyecto'];

        $variables=array($variable['codEspacio'],$variable['codProyecto'],$variable['planEstudio'],$variable['nroGrupoNue'],$variable['proyecto']);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_grupos_registrar", $variables);
        $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $totalSeleccionados=0;
        $total=$_REQUEST['totalEstudiantes'];
        $j=0;
            for($i=0;$i<$total;$i++)
            {
                if($_REQUEST['codEstudiante-'.$i]!=NULL)
                    {
                        $codigo['codEstudiante-'.$j]=$_REQUEST['codEstudiante-'.$i];
                        $j++;
                        $totalSeleccionados++;
                    }

            }

            $noexito=1;
            $exito=1;
        for($q=0;$q<$totalSeleccionados;$q++)
        {
            
            unset ($cruce);
            $band=0;
            $variableCodigo=$codigo['codEstudiante-'.$q];
            $variableCruce=array($variableCodigo,$variable['codEspacio']);
            
            $cadena_sql=$this->sql->cadena_sql($configuracion,"horario_registrado", $variableCruce);
            $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            for($n=0;$n<count($resultado_horarios_registrado);$n++)
                {
                    for($m=0;$m<count($resultado_horarios_registrar);$m++)
                    {
                        if($band==0)
                            {
                                if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n]))
                                    {
                                        $band=1;
                                        $cruce=true;
                                        break;
                                    }
                            }
                     }
                }

           if($cruce==true)
               {
                    $variable['codEstudiante']=$variableCodigo;

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo", $variable);
                    $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $variable['anno']=$resultadoPeriodo[0][0];
                    $variable['periodo']=$resultadoPeriodo[0][1];

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variableCodigo);
                    $resultado_datosEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $reporteNoExitos[$noexito]=$variableCodigo.",".$resultado_datosEstudiante[0][2].",".$resultado_datosEstudiante[0][3].",No se puede cambiar de grupo, presenta cruce con el horario que tiene registrado el estudiante";
                    
                    $noexito++;

                    $variablesRegistro[0]=$this->usuario;
                    $variablesRegistro[1]=date('YmdGis');
                    $variablesRegistro[2]='32';
                    $variablesRegistro[3]='No pudo cambiar de grupo, problemas estudiante';
                    $variablesRegistro[4]=$variable['anno']."-".$variable['periodo'].",".$variable['codEspacio'].",".$variable['nroGrupoAnt'].",".$variable['nroGrupoNue'].",".$variable['planEstudio'].",".$variable['codProyecto'];
                    $variablesRegistro[5]=$variable['codEstudiante'];

                    $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                    $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );
               }else
                   {
                    $variable['codEstudiante']=$variableCodigo;
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarGrupoOracle", $variable);
                    $resultado_actGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

                    if($resultado_actGrupo)
                        {
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActivo", $variable);
                            $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            $variable['anno']=$resultadoPeriodo[0][0];
                            $variable['periodo']=$resultadoPeriodo[0][1];

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarGrupoMySQL", $variable);
                            $resultado_actGrupo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                            $variablesRegistro[0]=$this->usuario;
                            $variablesRegistro[1]=date('YmdGis');
                            $variablesRegistro[2]='3';
                            $variablesRegistro[3]='Cambio grupo del Espacio Académico';
                            $variablesRegistro[4]=$variable['anno']."-".$variable['periodo'].",".$variable['codEspacio'].",".$variable['nroGrupoAnt'].",".$variable['nroGrupoNue'].",".$variable['planEstudio'].",".$variable['codProyecto'];
                            $variablesRegistro[5]=$variable['codEstudiante'];

                            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
                            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
                            $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante", $variableCodigo);
                            $resultado_datosEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            $reporteExitos[$exito]=$variableCodigo.",".$resultado_datosEstudiante[0][2].",".$resultado_datosEstudiante[0][3].",Nuevo grupo: ".$variable['nroGrupoNue'];

                            $exito++;
                        }else
                            {
                                $reporteNoExitos[$noexito]=$variableCodigo.",".$resultado_datosEstudiante[0][2].",".$resultado_datosEstudiante[0][3].",¡Problemas, no se pueden rescatar los datos!";
                                
                                $noexito++;
                            }
                   }
        }

        $this->generarReporte($configuracion,$reporteExitos,$reporteNoExitos,$variable);

        
    }

    /**
     * Funcion que permite generar el reporte de cambios de grupo
     *
     * Esta funcion muestra el reporte de cambios de grupo para los estudiantes seleccionados
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param array $reporteExitos Arreglo que contiene la informacion de estudiantes que se pudo hacer el cambio de grupo exitosamente
     * @param array $reporteNoExitos Arreglo que contiene la informacion de estudiantes que no se pudo hacer el cambio de grupo
     * @param array $variableRetorno Arreglo que contiene las variables para retornar a la pagina principal
     */
    function generarReporte($configuracion,$reporteExitos,$reporteNoExitos,$variableRetorno)
     {
        //Actualiza los cupos del grupo por el que se hizo el cambio
        $variablesInscritosAnt=array($variableRetorno['codEspacio'],$variableRetorno['nroGrupoAnt']);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos", $variablesInscritosAnt);
        $resultado_InscritosAnt=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $variablesInscritosAnt[2]=$resultado_InscritosAnt[0][0];
        $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarCupos", $variablesInscritosAnt);
        $resultado_ActualizacionAnt=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

        //Actualiza el cupo del nuevo grupo de los estudiantes
        $variablesInscritosNue=array($variableRetorno['codEspacio'],$variableRetorno['nroGrupoNue']);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos", $variablesInscritosNue);
        $resultado_InscritosNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $variablesInscritosNue[2]=$resultado_InscritosNue[0][0];
        $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarCupos", $variablesInscritosNue);
        $resultado_ActualizacionNue=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );
            ?>
<table class="contenidotabla centrar" border="0">
    <tr class="bloquelateralcuerpo">

        <td class="centrar">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&opcion2=cuadroRegistro";
                    $variable.="&codEspacio=".$variableRetorno['codEspacio'];
                    $variable.="&nroGrupo=".$variableRetorno['nroGrupoAnt'];
                    $variable.="&planEstudio=".$variableRetorno['planEstudio'];
                    $variable.="&codProyecto=".$variableRetorno['proyecto'];
                    $variable.="&nombreEspacio=".$variableRetorno['nombreEspacio'];
                    $variable.="&nroCreditos=".$variableRetorno['nroCreditos'];
                    
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br><b>Inicio</b>
            </a>
        </td>
        
    </tr>
    <tr>
        <td class="cuadro_color centrar">
            <?echo $variableRetorno['codEspacio']." - ".$variableRetorno['nombreEspacio']?>
        </td>
    </tr>
</table>

<table class="contenidotabla">
    <tr class="cuadro_brownOscuro centrar">
        <td colspan="4">
            REPORTE DEL PROCESO DE CAMBIO DE GRUPO
        </td>
    </tr>
    <?

        if(is_array($reporteExitos))
            {
                echo "<tr class='cuadro_brownOscuro centrar'><td colspan='4'>REGISTROS EXITOSOS</td></tr>";
                for($i=1;$i<=count($reporteExitos);$i++)
                {
                    $arreglo=explode(",",$reporteExitos[$i]);
                    $codEstudiante=$arreglo[0];
                    $nombreEstudiante=$arreglo[1];
                    $proyectoEstudiante=$arreglo[2];
                    $Descripcion=$arreglo[3];

                    ?>
                        <tr>
                            <td class="cuadro_plano centrar">
                                <?echo $codEstudiante?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $nombreEstudiante?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $proyectoEstudiante?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $Descripcion?>
                            </td>
                        </tr>
                    <?
                }
            }
        if(is_array($reporteNoExitos))
            {
                echo "<tr class='cuadro_brownOscuro centrar'><td colspan='4'>REGISTROS NO EXITOSOS</td></tr>";
                for($p=1;$p<=count($reporteNoExitos);$p++)
                {
                    $arregloNo=explode(",",$reporteNoExitos[$p]);
                    $codEstudianteNo=$arregloNo[0];
                    $nombreEstudianteNo=$arregloNo[1];
                    $proyectoEstudianteNo=$arregloNo[2];
                    $DescripcionNo=$arregloNo[3];

                    ?>
                        <tr>
                            <td class="cuadro_plano centrar">
                                <?echo $codEstudianteNo?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $nombreEstudianteNo?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $proyectoEstudianteNo?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $DescripcionNo?>
                            </td>
                        </tr>
                    <?
                }
            }
    ?>
</table>
            <?
        }
}

?>