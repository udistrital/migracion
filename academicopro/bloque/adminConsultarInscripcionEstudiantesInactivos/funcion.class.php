<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminConsultarInscripcionEstudiantesInactivos extends funcionGeneral {

    public $ano;
    public $periodo;

    function __construct($configuracion, $sql) {
    
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;
        

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="adminConsultarInscripcionEstudiantesInactivos";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $cadena_sql = $this->sql->cadena_sql($configuracion,"periodo_activo","");
        $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        $this->ano=$resultado_peridoActivo[0]['ANO'];
        $this->periodo=$resultado_peridoActivo[0]['PERIODO'];


    }

    /**
     * Consulta los proyectos curriculares que tenga a cargo el coordinador
     * Si existe mas de un proyecto, el coordinador el proyecto a administrar
     * de lo contrario continua con la funcion seleccionarEstado($configuracion);
     * @param <array> $configuracion
     */
    function verProyectos($configuracion)
    {
        //Consultamos los proyectos curriculares con su respectivo plan de estudio, y los mostramos en un <select>
        $cadena_sql_proyectos=$this->sql->cadena_sql($configuracion,"proyectos_curriculares",$this->usuario);//echo $cadena_sql_proyectos;exit;
        $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );

        if(is_array($resultado_proyectos)&&count($resultado_proyectos)>1){

        ?>
<table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>PLANES DE ESTUDIO ASOCIADOS AL USUARIO <?echo $this->usuario?></h4>
            <hr noshade class="hr">

        </td>
    </tr><br><br>
    <tr class="centrar">
        <td class="cuadro_color centrar" colspan="2">
            SELECCIONE EL PLAN DE ESTUDIO
        </td>
    </tr>
    <tr>
        <td class="cuadro_color centrar">Plan de Estudio</td>
        <td class="cuadro_color centrar">Nombre</td>
    </tr>


        <?
            for($i=0;$i<count($resultado_proyectos);$i++) {
                ?>
                    <tr>
                <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminConsultarInscripcionEstudiantesInactivos";
                $variable.="&opcion=reporte";
                $variable.="&codProyecto=".$resultado_proyectos[$i][0];
                $variable.="&planEstudio=".$resultado_proyectos[$i][2];
                $variable.="&nombreProyecto=".$resultado_proyectos[$i][1];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                ?>
            <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_proyectos[$i][2]?></a></td>
            <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_proyectos[$i][1]?></a></td>
                </tr>
    <?

            }
        ?>


</table>

        <?
    }else
        {
          if(is_array($resultado_proyectos))
          {
            $this->seleccionarEstado($configuracion);
          }
          else
          {
            $this->noPlan($configuracion);
            exit;

          }
        }


    }

        function noPlan($configuracion) {
?>
                      <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                          <tr align="center">
                              <td class="centrar" colspan="4">
                                  <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                  <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                              </td>
                          </tr>
                          <tr align="center">
                              <td class="centrar" colspan="4">
                                  <h4>NO EXISTEN PLANES DE ESTUDIO ASOCIADOS AL USUARIO <?echo $this->usuario?></h4>
                                  <hr noshade class="hr">

                              </td>
                          </tr>
                      </table>
                  <?


}

    /**
     * Muestra los estudiantes que esten inactivos y tengan inscripciones
     * asi como muestra el procentaje de estudiantes por estado
     * @param <array> $configuracion
     */
    function seleccionarEstado($configuracion)
    {
        $totalEstudiantes='';
        $totalInscripciones='';
        if(isset($_REQUEST['proyecto']))
            {

                $arreglo=explode("-",$_REQUEST['proyecto']);
                $planEstudio=$arreglo[0];
                $codProyecto=$arreglo[1];
                $nombreProyecto=$arreglo[2];

                $variable=array($codProyecto,$nombreProyecto,$planEstudio);
            }else if(isset($_REQUEST['codProyecto']) && isset($_REQUEST['planEstudio']))
                {
                    $codProyecto=$_REQUEST['codProyecto'];
                    $planEstudio=$_REQUEST['planEstudio'];
                    $nombreProyecto=$_REQUEST['nombreProyecto'];
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                }

            else{
                $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$this->usuario);//echo $cadena_sql;exit;
                $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $_REQUEST['planEstudio']=$planEstudio=$resultado_datosCoordinador[0][2];
                $_REQUEST['codProyecto']=$codProyecto=$resultado_datosCoordinador[0][0];
                $_REQUEST['nombreProyecto']=$nombreProyecto=$resultado_datosCoordinador[0][1];

                $variable=array($codProyecto,$nombreProyecto,$planEstudio);
            }
        $variables=array($codProyecto, $this->ano, $this->periodo);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"consultar_estudiantes", $variables);//echo $cadena_sql;exit;
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
        
        if(is_array($resultado_periodo))
        {
            ?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
<table class="contenidotabla centrar">
    <tr>
        <td class="centrar">
            <select id="id_estado" name="id_estado" >
            <?
                for($i=0;$i<count($resultado_periodo);$i++)
                {
                    if($resultado_periodo[$i][2]>0)
                        {
                            ?>
                                <option value="<?echo $resultado_periodo[$i][0]?>"><?echo $resultado_periodo[$i][0]." - ".$resultado_periodo[$i][1]?></option>
                            <?
                        }
                }
            ?>
            </select>
            <input type="hidden" name="opcion" value="cancelarEstado">
            <input type="hidden" name="action" value="<?echo $this->formulario?>">
            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
            <input type="image" src="<?echo $configuracion['site'].$configuracion['grafico']?>/viewrel.png">
        </td>
    </tr>
</table>
</form>
<table class="contenidotabla centrar" align="center">
    <tr class="cuadro_brownOscuro">
        <td class="texto_subtituloPrincipal centrar">
            ESTADO
        </td>
        <td class="texto_subtituloPrincipal centrar">
            NOMBRE ESTADO
        </td>
        <td class="texto_subtituloPrincipal centrar">
            N&Uacute;MERO DE ESTUDIANTES
        </td>
        <td class="texto_subtituloPrincipal centrar">
            N&Uacute;MERO DE INSCRIPCIONES
        </td>
    </tr>

            <?
            for($a=0;$a<count($resultado_periodo);$a++)
            {
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=registroCancelarInscripcionEstudiantesInactivos";
                $variable.="&opcion=cancelar";
                $variable.="&codProyecto=".$codProyecto;
                $variable.="&nombreProyecto=".$nombreProyecto;
                $variable.="&planEstudio=".$planEstudio;
                $variable.="&id_estado=".$resultado_periodo[$a][0];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                $totalEstudiantes=$totalEstudiantes+$resultado_periodo[$a][2];
                $totalInscripciones=$totalInscripciones+$resultado_periodo[$a][3];
                
                ?>
                <tr>
                    <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_periodo[$a][0]?></a></td>
                    <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_periodo[$a][1]?></a></td>
                    <td class="cuadro_plano centrar"><?echo $resultado_periodo[$a][2]?></td>
                    <td class="cuadro_plano centrar"><?echo $resultado_periodo[$a][3]?></td>
                </tr>
                <?
            }
            ?></table>
<table class="contenidotabla centrar">
    <tr class="centrar">
        <td class="centrar">
            <table class="contenidotablaNotamanno centrar" align="center">
                <tr class="centrar">
                    <td class="texto_subtituloPrincipal" colspan="<?echo count($resultado_periodo)?>">
                        ESTUDIANTES INACTIVOS CON ESPACIOS ACAD&Eacute;MICOS INSCRITOS
                    </td>
                </tr>
                <tr>

                    <?
                        for($i=0;$i<count($resultado_periodo);$i++)
                        {
                            $tamanno=($resultado_periodo[$i][2]/$totalEstudiantes)*100;
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $ruta="pagina=registroCancelarInscripcionEstudiantesInactivos";
                            $ruta.="&opcion=cancelar";
                            $ruta.="&codProyecto=".$codProyecto;
                            $ruta.="&nombreProyecto=".$nombreProyecto;
                            $ruta.="&planEstudio=".$planEstudio;
                            $ruta.="&id_estado=".$resultado_periodo[$i][0];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                            ?>
                                <td class="centrar" valign="bottom">
                                    <a class="centrar" href="<?echo $pagina.$ruta?>">
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/barra_<?echo $i?>.png" height="<?echo $tamanno?>" width="30" border="0" alt="Estado <?echo $resultado_periodo[$i][0]?>"><br>
                                        <strong><?echo round($tamanno,1)."%"?>
                                        <br>
                                        <?echo $resultado_periodo[$i][0]?></strong>
                                    </a>
                                </td>
                            <?
                        }
                    ?>

                </tr>
                <tr class="centrar">
                    <td colspan="<?echo count($resultado_periodo)?>">
                        TOTAL DE ESTUDIANTES INACTIVOS CON ASIGNATURAS INSCRITAS: <?echo $totalEstudiantes?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


            <?
        }
        else
            {
            ?><table class="contenidotabla2" align="center">
                <tr>
                <td class="cuadro_color centrar">
                    No existen estudiantes inactivos con espacios inscritos
                </td>
                </tr>
            </table>
            <?
            }

    }

    /**
     * Muestra el encabezado del modulo
     *
     * @param <array> $configuracion
     * @param <int> $planEstudio
     * @param <int> $codProyecto
     * @param <String> $nombreProyecto
     */
    function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto)
    {
        ?>
<table class='contenidotabla centrar'>
        <tr>
            <td class="centrar" colspan="8">
                <b>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</b><br>
                <b>REPORTE DE INSCRIPCIONES DE ESTUDIANTES INACTIVOS</b><br>
                <?echo $nombreProyecto?><br>
                PLAN DE ESTUDIOS EN CR&Eacute;DITOS N&Uacute;MERO <?echo "<strong>".$planEstudio." - ".$nombreProyecto."</strong>"?>
                <hr noshade class="hr">
            </td>
        </tr>
</table>
        <?
    }

   
}

?>