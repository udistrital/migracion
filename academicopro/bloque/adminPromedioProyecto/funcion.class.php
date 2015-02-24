
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

class funcion_adminPromedioProyecto extends funcionGeneral {

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_adminPromedioProyecto();
        $this->log_us= new log();
        $this->formulario="adminPromedioProyecto";


        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");


        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion=new sesiones($configuracion);
        $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
        $this->id_accesoSesion=$this->resultadoSesion[0][0];

        $this->usuarioSesion=$obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

        //echo $this->usuarioSesion[0][0];

    }

    function verProyectos($configuracion) {
        //Consultamos los proyectos curriculares con su respectivo plan de estudio, y los mostramos en un <select>
        $cadena_sql=$this->sql->cadena_sql($configuracion,"proyectos_curriculares",$this->usuario);//echo $cadena_sql;exit;
        $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if(is_array($resultado_proyectos)&&count($resultado_proyectos)>1) {
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
            <h4>REPORTE DE ESTUDIANTES Y SUS RESPECTIVOS PROMEDIOS POR PERIODO ACAD&Eacute;MICO</h4>
            <hr noshade class="hr">

        </td>
    </tr><br><br>
    <tr class="centrar">
        <td>
            Seleccione el proyecto curricular
        </td>
    </tr>
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <tr class="centrar">
            <td>
                <select name="proyecto" id="proyecto" style="width:380px">
                                <?

                                for($i=0;$i<count($resultado_proyectos);$i++) {
                                    ?>
                    <option value="<?echo $resultado_proyectos[$i][2]."-".$resultado_proyectos[$i][0]."-".$resultado_proyectos[$i][1]?>"><?echo $resultado_proyectos[$i][2]."      ( ".$resultado_proyectos[$i][0]." - ".$resultado_proyectos[$i][1].")"?></option>
                                    <?
                                }
                                ?>
                </select>
            </td>
        </tr>

        <tr class="cuadro_plano centrar">
            <td>

                <input type="hidden" name="opcion" value="registrados">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input name='seleccionar' value='Seleccionar' type='submit' >
            </td>
        </tr>
    </form>

</table>

            <?
        }else {
          if (is_array($resultado_proyectos))
            {
            $this->seleccion($configuracion);
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

    #Entrada al bloque para ver estudiantes con promedio menor a 3
    function seleccion($configuracion) {

        //Organizamos en un array el string que trae el plan de estudio, codigo del proyecto y el nombre del proyecto
        if($_REQUEST['proyecto']) {
            $arreglo=explode("-",$_REQUEST['proyecto']);
            $planEstudio=$arreglo[0];
            $codProyecto=$arreglo[1];
            $nombreProyecto=$arreglo[2];
        }else if($_REQUEST['codProyecto'] && $_REQUEST['planEstudio']) {
            $planEstudio=$_REQUEST['planEstudio'];
            $codProyecto=$_REQUEST['codProyecto'];
            $nombreProyecto=$_REQUEST['nombreProyecto'];
        }else {
            $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$this->usuario);//echo $cadena_sql;exit;
            $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $planEstudio=$resultado_datosCoordinador[0][2];
            $codProyecto=$resultado_datosCoordinador[0][0];
            $nombreProyecto=$resultado_datosCoordinador[0][1];
        }
        $variable=array($planEstudio, $codProyecto, $nombreProyecto);
        ?>
<table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td colspan="2">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>

        </td>
    </tr>
    <tr align="center">
        <td colspan="2">
            <h4>PROMEDIO DE ESTUDIANTES DEL PROYECTO CURRICULAR <?echo $nombreProyecto?></h4>
            <hr noshade class="hr">
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
        </td>
    </tr>
</table>
        <?
        $this->mostrarEstudiantes($configuracion,$variable);
    }


    function mostrarEstudiantes($configuracion,$variable) {

        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultarEstudiantesAcumulado",$variable[1]);//echo $this->cadena_sql;exit;
        $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"periodo_anterior",$variable[1]);//echo $this->cadena_sql;exit;
        $ano_anterior=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );


        ?>
<table class='contenidotabla centrar'>
    <thead class='texto_subtitulo centrar'>
    <td><center>Promedio de estudiantes y estado actual</center></td>
</thead>
<tr>
    <td class="cuadro_color centrar" colspan="10">
        Convenciones
    </td>
</tr>
<tr>
    <td class="cuadro_color" colspan="10">
        P.M.:Promedio Mayor a 4.0<br>
        P.A.:Prueba Acad&eacute;mica<br>
        Motivo 1: Promedio Menor a 3.0<br>
        Motivo 2: Perdio mas de 3 veces el mismo Espacio Acad&eacute;mico<br>
        Motivo 3: Perdio 3 o mas Espacios Acad&eacute;micos<br>
    </td>
</tr>
<table class='contenidotabla'>
    <tr>
        <td class='cuadro_brownOscuro centrar'>Nº</td>
        <td class='cuadro_brownOscuro centrar'>Carrera</td>
        <td class='cuadro_brownOscuro centrar'>Codigo Estudiante</td>
        <td class='cuadro_brownOscuro centrar'>Nombre Estudiante</td>
        <td class='cuadro_brownOscuro centrar' width="10%">Promedio Acumulado</td>
        <td class='cuadro_brownOscuro centrar' width="20%">Promedio Ponderado <?echo "<br>Periodo ".$ano_anterior[0][0]."-".$ano_anterior[0][1]?></td>
        <td class='cuadro_brownOscuro centrar' width="5%">Estado</td>
        <td class='cuadro_brownOscuro centrar' width="5%">Motivo</td>
    </tr>

            <?
            $nota=0;
            $totalEst=0;
            $promedioBajo=0;
            for($i=0;$i<count($resultado_estudiantes);$i++) {

                

                if($resultado_estudiantes[$i][0]==$resultado_estudiantes[$i+1][0]) {
                    $suma+=$resultado_estudiantes[$i][4];
                    $sumaCreditos+=$resultado_estudiantes[$i][3];

                    if((number_format($resultado_estudiantes[$i][1],1)/10) < 3) {
                        $nota++;
                    }

                }else {
                    $totalEst++;
                    $suma+=$resultado_estudiantes[$i][4];
                    $sumaCreditos+=$resultado_estudiantes[$i][3];

                    if((number_format($resultado_estudiantes[$i][1],1)/10) < 3) {
                        $nota++;
                    }
//                                       echo $suma."<br>".$sumaCreditos;
                    if($sumaCreditos==0) {
                        ?>
                        <?
                    }else {
                        $resultado=$suma/$sumaCreditos;

//                                       echo number_format($resultado,2)/10;exit;
                        ?>
                        <?
                        $promedioBajo++;

                        if( ($promedioBajo % 2) >'0')
                        {
                            $colorFila="#CEE3F6";
                        }else
                            {
                                $colorFila="#F5EFFB";
                            }

                        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultarEstudiante",$resultado_estudiantes[$i][0]);//echo $this->cadena_sql;exit;
                        $resultado_estudiantePrueba=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

                        ?>
    <tr bgcolor="<?echo $colorFila?>">
        <td class='centrar'><?echo $promedioBajo?></td><!--Numero estudiante-->
        <td class='centrar'><?echo $resultado_estudiantes[$i][7]?></td><!--Carrera-->
        <td class='centrar'><?echo $resultado_estudiantes[$i][0]?></td><!--Código estudiante-->
                            <?
                                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"consultarEstudiantesPonderado",$resultado_estudiantes[$i][0]);//echo $this->cadena_sql;exit;
                                $resultado_promedioPonderado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $this->cadena_sql,"busqueda" );

                                $ponderado=$this->calcularPromedioPonderado($configuracion, $resultado_promedioPonderado)
                            ?>
        <td><?echo $resultado_estudiantes[$i][5]?></td><!--Nombre estudiante-->
                            <?
                            if((number_format($resultado,1)/10)<3 && $nota>3) {
                                ?>
        <td class='centrar'>
            <font color="#FF0000"><?echo number_format($resultado,1)/10?></font></td><!--Promedio Acum-->
        <td class='centrar'>
            <font><?echo number_format($ponderado,1)/10?></font></td><!--Promedio Pond-->
        <td class='centrar'>
            <font color="#FF0000">P.A.</font></td><!--En prueba académica-->
        <td class='centrar'>
            <font color="#FF0000">1 | 3 </font></td><!--Motivo-->
    </tr>
                            <?
                        }else if((number_format($resultado,1)/10)<3) {
                            ?>

    <td class='centrar'>
        <font color="#FF0000"><?echo number_format($resultado,1)/10?></font></td><!--Promedio Acum-->
    <td class='centrar'>
        <font><?echo number_format($ponderado,1)/10?></font></td><!--Promedio Pond-->
    <td class='centrar'>
        <font color="#FF0000">P.A.</font></td>
    <td class='centrar'>
        <font color="#FF0000">1</font></td><!--Motivo-->
    </tr>
                            <?
                        }else if($nota>3) {
                            ?>

    <td class='centrar'>
        <font><?echo number_format($resultado,1)/10?></font></td><!--Promedio Acum-->
    <td class='centrar'>
        <font><?echo number_format($ponderado,1)/10?></font></td><!--Promedio Pond-->
    <td class='centrar'>
        <font color="#FF0000">P.A.</font></td>
    <td class='centrar'>
        <font color="#FF0000">3</font></td><!--Motivo-->
    </tr>
                            <?
                        }else if((number_format($ponderado,1)/10)>4) {
                            ?>

    <td class='centrar'>
        <font color="#088A08"><?echo number_format($resultado,1)/10?></font></td><!--Promedio-->
    <td class='centrar'>
        <font color="#088A08"><?echo number_format($ponderado,1)/10?></font></td><!--Promedio-->
    <td class='centrar'>
        <font color="#088A08">P.M.</font></td>
    <td class='centrar'>
        <font color="#088A08"></font></td><!--Motivo-->
    </tr><?
                        }
                        else {
                            ?>

    <td class='centrar'>
        <font><?echo number_format($resultado,1)/10?></font></td><!--Promedio-->
    <td class='centrar'>
        <font><?echo number_format($ponderado,1)/10?></font></td><!--Promedio-->
    <td class='centrar'>
    </td>
    <td class='centrar'>
    </td><!--Motivo-->
    </tr>
                            <?
                        }
                    }
                    $suma=0;
                    $sumaCreditos=0;
                    $resultado=0;
                    $nota=0;

                }
            }
            ?>
</table>
<tr>
    <td class='centrar'>
        Total de estudiantes:<b><?echo $totalEst?></b>
    </td>
</tr>

</table>
        <?
    }

    function calcularPromedioPonderado($configuracion,$resultado_estudiantes) {
        for($i=0;$i<count($resultado_estudiantes);$i++) {
            if($resultado_estudiantes[$i][0]==$resultado_estudiantes[$i+1][0]) {
                $suma+=$resultado_estudiantes[$i][4];
                $sumaCreditos+=$resultado_estudiantes[$i][3];

                if((number_format($resultado_estudiantes[$i][1],1)/10) < 3) {
                    $nota++;
                }

            }else {
                $totalEst++;
                $suma+=$resultado_estudiantes[$i][4];
                $sumaCreditos+=$resultado_estudiantes[$i][3];

                if((number_format($resultado_estudiantes[$i][1],1)/10) < 3) {
                    $nota++;
                }
//                                       echo $suma."<br>".$sumaCreditos;
                if($sumaCreditos==0) {
                    ?>
                    <?
                }else {
                    $resultado=$suma/$sumaCreditos;
                    return $resultado;
                }
            }
        }
    }


    function verPlanes($configuracion) {
        //Consultamos los proyectos curriculares con su respectivo plan de estudio, y los mostramos en un <select>
        $cadena_sql=$this->sql->cadena_sql($configuracion,"planes_estudio",$this->usuario);//echo $cadena_sql;exit;
        $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if(count($resultado_proyectos)>1) {
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
            <h4>REPORTE DE ESTUDIANTES Y SUS RESPECTIVOS PROMEDIOS POR PERIODO ACAD&Eacute;MICO</h4>
            <hr noshade class="hr">

        </td>
    </tr><br><br>
    <tr class="centrar">
        <td>
            Seleccione el proyecto curricular
        </td>
    </tr>
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <tr class="centrar">
            <td>
                <select name="proyecto" id="proyecto" style="width:380px">
                                <?

                                for($i=0;$i<count($resultado_proyectos);$i++) {
                                    ?>
                    <option value="<?echo $resultado_proyectos[$i][2]."-".$resultado_proyectos[$i][0]."-".$resultado_proyectos[$i][1]?>"><?echo $resultado_proyectos[$i][2]."      ( ".$resultado_proyectos[$i][0]." - ".$resultado_proyectos[$i][1].")"?></option>
                                    <?
                                }
                                ?>
                </select>
            </td>
        </tr>

        <tr class="cuadro_plano centrar">
            <td>

                <input type="hidden" name="opcion" value="registrados">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input name='seleccionar' value='Seleccionar' type='submit' >
            </td>
        </tr>
    </form>

</table><?
        }
    }

}
?>
