
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

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_registroConsejeria extends funcionGeneral {


    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_registroConsejeria();
        $this->log_us= new log();
        $this->formulario="registroConsejeria";

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion=new sesiones($configuracion);
        $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
        $this->id_accesoSesion=$this->resultadoSesion[0][0];

        //Datos de sesion
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }#Cierre de constructor


    function consultarDocente($configuracion)
    {
        $this->encabezadoModulo($configuracion);
        

        if($_REQUEST['codProyecto'] && $_REQUEST['planEstudio'])
                {
                    $codProyecto=$_REQUEST['codProyecto'];
                    $planEstudio=$_REQUEST['planEstudio'];
                    $nombreProyecto=$_REQUEST['nombreProyecto'];
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                }

            else{

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$this->usuario);
                    $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $planEstudio=$resultado_datosCoordinador[0][2];
                    $codProyecto=$resultado_datosCoordinador[0][0];
                    $nombreProyecto=$resultado_datosCoordinador[0][1];

                    $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                }

      $this->menuAdministracion($configuracion,$variable);

      $cadena_sql=$this->sql->cadena_sql($configuracion,"consultarDocentesConsejeros",$codProyecto);
      $resultado_consejeros=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

      if(is_array($resultado_consejeros))
          {
            ?>
<style type="text/css">
#toolTipBox {
        display: none;
        position:absolute;
      
        background:#1C5996;
        border:4px double #fff;
        text-align:left;
        padding:5px;
        -moz-border-radius:8px;
        z-index:1000;
        margin:0;
        padding:1em;
        color:#FFFFFF;
        font:11px/12px verdana,arial,serif;
        margin-top:3px;
        font-style:normal;
        font-weight:bold;
        opacity:0.85;
}
</style>
<table class="contenidotabla centrar">
            <tr>
                <td class="centrar" colspan="6"><h4>DOCENTES CONSEJEROS DEL PROYECTO CURRICULAR <?echo $nombreProyecto?></h4></td>
            </tr>
          <tr class="sigma">
            <th class="sigma centrar">
                Nro Identidad
            </th>
            <th class="sigma centrar">
                Nombre
            </th>
            <th class="sigma centrar">
                Email
            </th>
            <th class="sigma centrar">
                Admin Estudiantes
            </th>
            <th class="sigma centrar">
                Admin Consejero
            </th>
<!--            <th class="sigma centrar">
                Comunicaci&oacute;n
            </th>-->

          </tr>
          <?
            for($i=0;$i<count($resultado_consejeros);$i++)
            {
                $variable_estudiantes=array($resultado_consejeros[$i][0],$codProyecto);
                $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantes_asociados",$variable_estudiantes);
                $resultado_estudiantesAsociados=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if($i%2==0)
                    {
                        $clasetr="sigma";
                    }else
                        {
                            $clasetr="";
                        }
                ?>
                <tr class="<?= $clasetr?>">
                    <td class="cuadro_plano centrar">
                        <?echo $resultado_consejeros[$i][0]?>
                    </td>
                    <td class="cuadro_plano">
                        <?echo $resultado_consejeros[$i][3]." ".$resultado_consejeros[$i][2]?>
                    </td>
                    <td class="cuadro_plano">
                        <?echo $resultado_consejeros[$i][4]?>
                    </td>
                    <td class="cuadro_plano centrar" onmouseover="toolTip('Nro de Estudiantes<BR>Seleccionados: <?echo $resultado_estudiantesAsociados[0][0]?>',this)">

                            <div class="centrar">
                                <span id="toolTipBox" width="300" ></span>
                            </div>
                        <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $ruta="pagina=registroAsociarEstudianteConsejeria";
                            $ruta.="&opcion=asociar";
                            $ruta.="&codProyecto=".$variable[0];
                            $ruta.="&planEstudio=".$variable[2];
                            $ruta.="&nombreProyecto=".$variable[1];
                            $ruta.="&codDocente=".$resultado_consejeros[$i][0];
                            $ruta.="&nombreDocente=".$resultado_consejeros[$i][2]." ".$resultado_consejeros[$i][3];

                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                        ?>
                        <a href="<?echo $pagina.$ruta?>">
                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/grupoNuevo.png" width="30" height="30" border="0">
                        </a>
                    </td>
                    <td class="cuadro_plano centrar">
                        <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $ruta="pagina=registroConsejeria";
                            $ruta.="&opcion=borrarConsejero";
                            $ruta.="&codProyecto=".$variable[0];
                            $ruta.="&planEstudio=".$variable[2];
                            $ruta.="&nombreProyecto=".$variable[1];
                            $ruta.="&docente=".$resultado_consejeros[$i][0];
                            $ruta.="&tipoVin=".$resultado_consejeros[$i][2];
                            $ruta.="&nombreDocente=".$resultado_consejeros[$i][2]." ".$resultado_consejeros[$i][3];

                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                            if($resultado_estudiantesAsociados[0][0]>0)
                                {
                                    echo "Tiene estudiantes<br>asignados";
                                }else
                                    {
                                    ?>
                                        <a href="<?echo $pagina.$ruta?>">
                                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="30" height="30" border="0">
                                        </a>
                                    <?
                                    }
                                    ?>
                    </td>
<!--                    <td class="cuadro_plano centrar">
                        <?
//                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
//                            $ruta="pagina=admin_consejerias";
//                            $ruta.="&opcion=comunicacion";
//                            $ruta.="&codDocente=".$resultado_consejeros[$i][0];
//                            $ruta.="&nombreDocente=".$resultado_consejeros[$i][2]." ".$resultado_consejeros[$i][3];
//
//                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                        ?>
                            <a href="<?//echo $pagina.$ruta?>">
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/messenger.png" width="30" height="30" border="0">
                            </a>
                    </td>-->
                </tr>
                <?
            }
          }
          else
          {
          ?>
            <table class="contenidotabla centrar">
                <tr class="sigma">
                    <td class="sigma_a centrar">
                        NO HAY DOCENTES ASIGNADOS COMO CONSEJEROS
                    </td>
                </tr>
            </table>

          <?
          }
?>
</table>
<?
    }
    

    function menuAdministracion($configuracion,$variable) {

         ?>
<table class="contenidotabla centrar" width="100%" >

    <tr class="centrar">
        <td colspan="2" class="centrar"  width="33%">
            <?
            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=registroConsejeria";
            $ruta.="&opcion=registrarConsejero";
            $ruta.="&codProyecto=".$variable[0];
            $ruta.="&planEstudio=".$variable[2];
            $ruta.="&nombreProyecto=".$variable[1];

            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>


            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kword.png" width="38" height="38" border="0" alt="Ver plan de estudio">
                <br>Seleccionar<br>Docentes Consejeros
            </a>
        </td>
        </tr>
</table>
<?
    }

    
    // @ Funcion que permite ver el encabezado del subsistema de consejerias
    function encabezadoModulo($configuracion)
    {
    ?>
    <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
        <tr align="center">
            <td class="centrar" colspan="4">
                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png " alt="Logo Universidad">
            </td>
        </tr>
        <tr align="center">
            <td class="centrar" colspan="4">
                <h4>ADMINISTRACI&Oacute;N DE CONSEJERIAS<BR>UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS</h4>
                <hr noshade class="hr">
           </td>
        </tr>
    </table>
    <?
    }


    function consultarDocentesPlanta($configuracion)
    {
        $this->encabezadoModulo($configuracion);

        if($_REQUEST['codProyecto'] && $_REQUEST['planEstudio'])
                {
                    $codProyecto=$_REQUEST['codProyecto'];
                    $planEstudio=$_REQUEST['planEstudio'];
                    $nombreProyecto=$_REQUEST['nombreProyecto'];
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                }

            else{

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$this->usuario);
                    $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $planEstudio=$resultado_datosCoordinador[0][2];
                    $codProyecto=$resultado_datosCoordinador[0][0];
                    $nombreProyecto=$resultado_datosCoordinador[0][1];

                    $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                }

        $cadena_sql=$this->sql->cadena_sql($configuracion,"consultarDocentesPlanta",$codProyecto);
        $resultado_consejeros=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        ?>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <table class="contenidotabla centrar">
                            <tr>
                                <td class="centrar" colspan="6"><h4>DOCENTES DEL PROYECTO CURRICULAR <?echo $nombreProyecto?></h4></td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <table class="contenidotabla centrar">
                                        <tr>
                                            <td width="50%" class="centrar">
                                                <?
                                                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                    $ruta="pagina=registroConsejeria";
                                                    $ruta.="&opcion=ver";
                                                    $ruta.="&codProyecto=".$codProyecto;
                                                    $ruta.="&planEstudio=".$planEstudio;
                                                    $ruta.="&nombreProyecto=".$nombreProyecto;

                                                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                                ?>


                                                    <a href="<?= $indice.$ruta ?>">
                                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="30" height="30" border="0" alt="Ver plan de estudio">
                                                        <br>Inicio
                                                    </a>
                                            </td>
                                            <td width="50%" class="centrar">
                                                <?
                                                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                    $ruta="pagina=registroConsejeria";
                                                    $ruta.="&opcion=otrosDocentes";
                                                    $ruta.="&codProyecto=".$codProyecto;
                                                    $ruta.="&planEstudio=".$planEstudio;
                                                    $ruta.="&nombreProyecto=".$nombreProyecto;

                                                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                                ?>


                                                    <a href="<?= $indice.$ruta ?>">
                                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kuzer.png" width="30" height="30" border="0" alt="Ver plan de estudio">
                                                        <br>Docentes Otros Proyectos
                                                    </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                           
                        <?

        if(is_array($resultado_consejeros))
            {
            ?>
             <tr class="sigma_a">
                <th class="sigma centrar">
                    Nro Identidad
                </th>
                <th class="sigma centrar">
                    Nombre
                </th>
                <th class="sigma centrar">
                    Email
                </th>
                <th class="sigma centrar">
                    Seleccionar
                </th>
            </tr>
            <?
                    $muestra=0;
                    $docentes=0;
                    
        for($i=0;$i<count($resultado_consejeros);$i++)
        {
            $variables_consejeros=array($resultado_consejeros[$i][1],$codProyecto);
            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultarDocentesConsejerosActivos",$variables_consejeros);
            $resultado_consejerosActivos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(!is_array($resultado_consejerosActivos))
                {
                    if($resultado_consejeros[$i][2]!=$muestra)
                    {
                    ?>
                    <tr class="sigma">
                        <td class="sigma_a centrar" colspan="6">
                            TIPO DE VINCULACI&Oacute;N: <?echo $resultado_consejeros[$i][3]?>
                        </td>
                    </tr>
                    <?
                    }
                    if($docentes%2==0)
                        {
                            $clasetr="sigma";
                        }else
                            {
                                $clasetr="";
                            }
                    ?>
                        <tr class="<?= $clasetr?>">
                            <td class="cuadro_plano centrar">
                                <?echo $resultado_consejeros[$i][1]?>
                            </td>
                            <td class="cuadro_plano">
                                <?echo $resultado_consejeros[$i][4]." ".$resultado_consejeros[$i][5]?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $resultado_consejeros[$i][6]?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <input type="checkbox" name="consejero<?echo $i?>" value="<?echo $resultado_consejeros[$i][1]?>">
                                <input type="hidden" name="tipoVin<?echo $i?>" value="<?echo $resultado_consejeros[$i][2]?>">
                            </td>
                        </tr>
                    <?
                    $muestra=$resultado_consejeros[$i][2];
                    $docentes++;
                    }
                    /*else
                    {
                        $muestra=$resultado_consejeros[$i][2];
                    }*/


            }//fin for
            if($docentes==0)
            {
             ?>
                <table class="contenidotabla centrar" width="100%" >
                    <tr>
                        <td class="centrar" colspan="2" width="33%">
                            <?
                            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $ruta="pagina=registroConsejeria";
                            $ruta.="&opcion=ver";
                            $ruta.="&codProyecto=".$codProyecto;
                            $ruta.="&planEstudio=".$planEstudio;
                            $ruta.="&nombreProyecto=".$nombreProyecto;

                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                        ?>


                            <a href="<?= $indice.$ruta ?>">
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="30" height="30" border="0" alt="Ver plan de estudio">
                                <br>Inicio
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td class="sigma_a centrar">
                            NO HAY DOCENTES PARA SELECCIONAR COMO CONSEJEROS
                        </td>
                    </tr>
                </table>
             <?
            }
            else
            {
                ?>
                    <tr>
                        <td class="centrar" colspan="6">
                            <input type="submit" name="Guardar" value="Guardar">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="hidden" name="opcion" value="guardarConsejeros">
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="totalConsejeros" value="<?echo count($resultado_consejeros)?>">
                        </td>
                    </tr>
                </table>
                </form>
                <?
            }
        }//fin resultado consejeros
        else
        {
             ?>
             <table class="contenidotabla centrar">
                <tr>
                    <td class="sigma_a centrar">
                        EL PROYECTO ACAD&Eacute;MICO NO TIENE REGISTRADOS DOCENTES
                    </td>
                </tr>
             </table>
             <?

        }
    }


    function docentesOtrosProyectos($configuracion)
    {
        $this->encabezadoModulo($configuracion);

        if($_REQUEST['codProyecto'] && $_REQUEST['planEstudio'])
                {
                    $codProyecto=$_REQUEST['codProyecto'];
                    $planEstudio=$_REQUEST['planEstudio'];
                    $nombreProyecto=$_REQUEST['nombreProyecto'];
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                }

            else{

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$this->usuario);
                    $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $planEstudio=$resultado_datosCoordinador[0][2];
                    $codProyecto=$resultado_datosCoordinador[0][0];
                    $nombreProyecto=$resultado_datosCoordinador[0][1];

                    $variable=array($codProyecto,$nombreProyecto,$planEstudio);
                }

        $cadena_sql=$this->sql->cadena_sql($configuracion,"consultarDocentesPlantaOtros",$codProyecto);
        $resultado_consejeros=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        ?>
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <table class="contenidotabla centrar">
            <tr>
                <td colspan="6">
                    <table class="contenidotabla">
                        <tr>
                            <td width="50%" class="centrar">
                                <?
                                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $ruta="pagina=registroConsejeria";
                                    $ruta.="&opcion=ver";
                                    $ruta.="&codProyecto=".$codProyecto;
                                    $ruta.="&planEstudio=".$planEstudio;
                                    $ruta.="&nombreProyecto=".$nombreProyecto;

                                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                ?>
                                    <a href="<?= $indice.$ruta ?>">
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="30" height="30" border="0" alt="Ver plan de estudio">
                                        <br>Inicio
                                    </a>
                            </td>
                            <td width="50%" class="centrar">
                                <?
                                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $ruta="pagina=registroConsejeria";
                                    $ruta.="&opcion=registrarConsejero";
                                    $ruta.="&codProyecto=".$codProyecto;
                                    $ruta.="&planEstudio=".$planEstudio;
                                    $ruta.="&nombreProyecto=".$nombreProyecto;

                                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                                ?>
                                    <a href="<?= $indice.$ruta ?>">
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kuzer.png" width="30" height="30" border="0" alt="Ver plan de estudio">
                                        <br>Docentes de Mi Proyecto
                                    </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?

        if(is_array($resultado_consejeros))
            {
                    $muestra=0;
                    $docentes=0;
                    $carrera=0;

        for($i=0;$i<count($resultado_consejeros);$i++)
        {
            $variables_consejeros=array($resultado_consejeros[$i][1],$codProyecto);
            $cadena_sql=$this->sql->cadena_sql($configuracion,"consultarDocentesConsejerosActivos",$variables_consejeros);
            $resultado_consejerosActivos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(!is_array($resultado_consejerosActivos))
                {
                    if($resultado_consejeros[$i][0]!=$carrera)
                        {$muestra=0;
                        ?>
                            <tr>
                                <th class="sigma_a centrar" colspan="6">DOCENTES DEL PROYECTO CURRICULAR <?echo $resultado_consejeros[$i][7]?></th>
                            </tr>
                            <tr class="sigma_a">
                                <th class="sigma centrar">
                                    Nro Identidad
                                </th>
                                <th class="sigma centrar">
                                    Nombre
                                </th>
                                <th class="sigma centrar">
                                    Email
                                </th>
                                <th class="sigma centrar">
                                    Seleccionar
                                </th>
                            </tr>
                        <?$carrera=$resultado_consejeros[$i][0];
                        }
                    if($resultado_consejeros[$i][2]!=$muestra)
                    {
                    ?>
                    <tr class="sigma">
                        <td class="sigma_a centrar" colspan="6">
                            TIPO DE VINCULACI&Oacute;N: <?echo $resultado_consejeros[$i][3]?>
                        </td>
                    </tr>
                    <?
                    $muestra=$resultado_consejeros[$i][2];
                    }
                    if($docentes%2==0)
                        {
                            $clasetr="sigma";
                        }else
                            {
                                $clasetr="";
                            }
                    ?>
                        <tr class="<?= $clasetr?>">
                            <td class="cuadro_plano centrar">
                                <?echo $resultado_consejeros[$i][1]?>
                            </td>
                            <td class="cuadro_plano">
                                <?echo $resultado_consejeros[$i][4]." ".$resultado_consejeros[$i][5]?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $resultado_consejeros[$i][6]?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <input type="checkbox" name="consejero<?echo $i?>" value="<?echo $resultado_consejeros[$i][1]?>">
                            </td>
                        </tr>
                    <?
                    //$muestra=$resultado_consejeros[$i][2];
                    $docentes++;
                    }
                    /*else
                    {
                        $muestra=$resultado_consejeros[$i][2];
                    }*/


            }//fin for
            if($docentes==0)
            {
             ?>
                <table class="contenidotabla centrar" width="100%" >
                    <tr>
                        <td class="centrar" colspan="2" width="33%">
                            <?
                            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $ruta="pagina=registroConsejeria";
                            $ruta.="&opcion=ver";
                            $ruta.="&codProyecto=".$codProyecto;
                            $ruta.="&planEstudio=".$planEstudio;
                            $ruta.="&nombreProyecto=".$nombreProyecto;

                            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                        ?>
                            <a href="<?= $indice.$ruta ?>">
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="30" height="30" border="0" alt="Ver plan de estudio">
                                <br>Inicio
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="sigma_a centrar">
                            NO HAY DOCENTES PARA SELECCIONAR COMO CONSEJEROS
                        </td>
                    </tr>
                </table>
             <?
            }
            else
            {
                ?>
                    <tr>
                        <td class="centrar" colspan="6">
                            <input type="submit" name="Guardar" value="Guardar">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="hidden" name="opcion" value="guardarConsejeros">
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="totalConsejeros" value="<?echo count($resultado_consejeros)?>">
                        </td>
                    </tr>
                </table>
                </form>
                <?
            }
        }//fin resultado consejeros
        else
        {
             ?>
             <table class="contenidotabla centrar">
                <tr>
                    <td class="sigma_a centrar">
                        EL PROYECTO ACAD&Eacute;MICO NO TIENE REGISTRADOS DOCENTES
                    </td>
                </tr>
             </table>
             <?

        }
    }


    function guardarDocenteConsejero($configuracion)
    {
        if($_REQUEST['totalSeleccionados']<=0)
            {
                echo "<script>alert('Seleccione los docentes que seran consejeros')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroConsejeria";
                $ruta.="&opcion=ver";
                $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
                $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
                $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                echo "<script>location.replace('".$pagina.$ruta."')</script>";
                exit;
            }

        for($i=0;$i<$_REQUEST['totalSeleccionados'];$i++)
        {
            $variablesRegistro=array($_REQUEST['consejero'.$i],$_REQUEST['codProyecto'],$_REQUEST['tipoVin'.$i]);

            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarDocenteActivo",$variablesRegistro);
            $resultado_activo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(is_array($resultado_activo))
                {
                    echo "<script>alert('El docente con identificación ".$variablesRegistro[0]." ya se encuentra registrado como consejero en el proyecto curricular')</script>";
                }else
                    {
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"registrarDocente",$variablesRegistro);
                        $resultado_consejeros=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");

                        if($resultado_consejeros==true)
                            {
                                $variablesLog=array($this->usuario,date('YmdGis'),'40','Registro Docente Consejero',$_REQUEST['consejero'.$i]." - cra:".$_REQUEST['codProyecto']." - vin:".$_REQUEST['tipoVin'.$i],$_REQUEST['codProyecto']);

                                $cadena_sql=$this->sql->cadena_sql($configuracion,"registroEvento",$variablesLog);
                                $resultado_evento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );
                            }
                    }
        }

        echo "<script>alert('Se han registrado nuevos docentes consejeros')</script>";
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroConsejeria";
        $ruta.="&opcion=ver";
        $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $ruta=$this->cripto->codificar_url($ruta,$configuracion);

        echo "<script>location.replace('".$pagina.$ruta."')</script>";
    }


    function borrarDocenteConsejero($configuracion)
    {
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $docente=$_REQUEST['docente'];
        $nombreDocente=$_REQUEST['nombreDocente'];
        $tipoVin=$_REQUEST['tipoVin'];

        $variableBorrar=array($docente,$codProyecto);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"borrarDocenteSeleccionado",$variableBorrar);
        $resultado_borrarConsejeros=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );

        if($resultado_borrarConsejeros==true)
            {
                $variablesLog=array($this->usuario,date('YmdGis'),'47','Borro Docente Consejero',$_REQUEST['docente']." - cra:".$_REQUEST['codProyecto']." - vin:".$_REQUEST['tipoVin'],$_REQUEST['codProyecto']);

                $cadena_sql=$this->sql->cadena_sql($configuracion,"registroEvento",$variablesLog);
                $resultado_evento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );
        
                echo "<script>alert('Se ha borrado el docente como consejero')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroConsejeria";
                $ruta.="&opcion=ver";
                $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
                $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
                $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                echo "<script>location.replace('".$pagina.$ruta."')</script>";
            }else
                {
                    echo "<script>alert('La base de datos se encuentra ocupado, intente mas tarde')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta="pagina=registroConsejeria";
                    $ruta.="&opcion=ver";
                    $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
                    $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
                    $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                    echo "<script>location.replace('".$pagina.$ruta."')</script>";
                }

    }

}
?>
