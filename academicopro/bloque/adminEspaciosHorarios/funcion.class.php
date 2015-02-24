<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminEspaciosHorarios extends funcionGeneral {
//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        //Conexion pruebas
        //$this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");
        //conexion desarrollo
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");
        //Datos de sesion
        $this->formulario="adminEspaciosHorarios";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $this->verificar="control_vacio(".$this->formulario.",'numero')";
        $this->verificar.="&&verificar_numero(".$this->formulario.",'numero')";
        $this->verificar.="&&verificar_rango(".$this->formulario.",'numero','0','99')";

    }


    function verProyectos($configuracion) {
        $cadena_sql_proyectos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"proyectos_curriculares","");//echo $cadena_sql_estudiantes;exit;
        $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );

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
            <h4>Horario de Espacios acad&eacute;micos por proyecto curricular</h4>
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
                    <option value="<?echo $resultado_proyectos[$i][2]."-".$resultado_proyectos[$i][0]."-".$resultado_proyectos[$i][1]?>"><?echo $resultado_proyectos[$i][2]." - ".$resultado_proyectos[$i][1]?></option>
                            <?
                            }
                            ?>
                </select>
            </td>
        </tr>

        <tr class="cuadro_plano centrar">
            <td>

                <input type="hidden" name="opcion" value="horario">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input name='seleccionar' value='Seleccionar' type='submit' >
    </form>
</td>
</tr>
</table>

    <?
    }


    function verHorarios($configuracion) {

        if($_REQUEST['proyecto'])
            {
                $arreglo= explode("-",$_REQUEST['proyecto']);
                $planEstudio=$arreglo[0];
                $codProyecto=$arreglo[1];
                $nombreCarrera=$arreglo[2];
            }
            else
                {

                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datos_coordinador",$this->usuario);//echo $cadena_sql;exit;
                    $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    if (is_array($resultado_datosCoordinador))
                    {
                      $planEstudio=$resultado_datosCoordinador[0][1];
                      $codProyecto=$resultado_datosCoordinador[0][0];
                    }
                    else
                      {
                        $this->noPlan($configuracion);
                        exit;
                      }
                }

        

        $variable=array($planEstudio,$codProyecto);

        $cadena_sql_espaciosCarrera=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"espacios_carrera",$variable);//echo $cadena_sql_estudiantes;exit;
        $resultado_espaciosCarrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_espaciosCarrera,"busqueda" );
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
            <h4>Horario de Espacios acad&eacute;micos por proyecto curricular</h4>
            <hr noshade class="hr">

        </td>
    </tr>
    <tr>
        <td class="centrar" colspan="4">
            <font size=2><?echo $nombreCarrera?></font>
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
            <a href="javascript:history.back()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" width="35" height="35" border="0"><br>Regresar
            </a>
        </td>
    </tr><br>
            <?
            for($z=0;$z<count($resultado_espaciosCarrera);$z++) {

                $variables[0]=$resultado_espaciosCarrera[$z][0];
                $variables[1]=$codProyecto;
                $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"grupos_proyecto", $variables);//echo $cadena_sql_grupos."<br><br><br>";
                $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

                if($resultado_grupos[0][0]!=NULL) {
                    ?>

    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
        <thead class='texto_subtitulo cuadro_color centrar'>
        <td><center><?echo $resultado_espaciosCarrera[$z][0]." - ".$resultado_espaciosCarrera[$z][1];?></center></td>
        </thead>
        <tr>
            <td>
                <table class='contenidotabla'>
                    <tr class="cuadro_color">
                        <td class='cuadro_plano centrar' width="25">Grupo </td>
                        <td class='cuadro_plano centrar' width="60">Lun </td>
                        <td class='cuadro_plano centrar' width="60">Mar </td>
                        <td class='cuadro_plano centrar' width="60">Mie </td>
                        <td class='cuadro_plano centrar' width="60">Jue </td>
                        <td class='cuadro_plano centrar' width="60">Vie </td>
                        <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                        <td class='cuadro_plano centrar' width="60">Dom </td>
                    </tr>

                                    <?


                                    for($j=0;$j<count($resultado_grupos);$j++) {
                                        $variables[3]=$resultado_grupos[$j][0];

                                        $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos", $variables);
                                        $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                                        ?><tr>
                        <td class='cuadro_plano centrar'>

                                                <?
                                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                $variable="pagina=adminEspaciosHorarios";
                                                $variable.="&opcion=verEstudiantes";
                                                $variable.="&grupo=".$resultado_grupos[$j][0];
                                                ;
                                                $variable.="&codProyecto=".$codProyecto;
                                                $variable.="&planEstudio=".$planEstudio;
                                                $variable.="&idEspacio=".$resultado_espaciosCarrera[$z][0];
                                                $variable.="&nombreEspacio=".$resultado_espaciosCarrera[$z][1];

                                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                $this->cripto=new encriptar();
                                                $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                ?>
                            <a href="<?= $pagina.$variable ?>" >
                                                    <?echo $resultado_grupos[$j][0];?><br><font size="1">Ver Estudiantes</font>
                            </a>
                        </td><?
                                            for($i=1; $i<8; $i++) {
                                                ?><td class='cuadro_plano centrar'><?
                                                    for ($k=0;$k<count($resultado_horarios);$k++) {

                                                        //if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3])) {
                                                        if ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']==$resultado_horarios[$k+1]['DIA'] && $resultado_horarios[$k+1]['HORA']==($resultado_horarios[$k]['HORA']+1) && $resultado_horarios[$k+1]['SALON_NVO']==($resultado_horarios[$k]['SALON_NVO'])) {    
                                                            $l=$k;
                                                            while ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']==$resultado_horarios[$k+1]['DIA'] && $resultado_horarios[$k+1]['HORA']==($resultado_horarios[$k]['HORA']+1) && $resultado_horarios[$k+1]['SALON_NVO']==($resultado_horarios[$k]['SALON_NVO'])) {

                                                                $m=$k;
                                                                $m++;
                                                                $k++;
                                                            }
                                                            
                                                            $dia="<strong>".$resultado_horarios[$l]['HORA']."-".($resultado_horarios[$m]['HORA']+1)."</strong><br>Sede: ".$resultado_horarios[$l]['NOM_SEDE']."<br>Edificio: ".$resultado_horarios[$l]['NOM_EDIFICIO']."<br>Salon: ".$resultado_horarios[$l]['SALON_NVO']."<BR> ".$resultado_horarios[$l]['NOM_SALON']."";
                                                            echo $dia."<br>";
                                                            unset ($dia);
                                                        }
                                                        elseif ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']!=$resultado_horarios[$k+1]['DIA']) {
                                                            $dia="<strong>".$resultado_horarios[$k]['HORA']."-".($resultado_horarios[$k]['HORA']+1)."</strong><br>Sede: ".$resultado_horarios[$k]['NOM_SEDE']."<br>Edificio: ".$resultado_horarios[$k]['NOM_EDIFICIO']."<br>Salon: ".$resultado_horarios[$k]['SALON_NVO']."<BR> ".$resultado_horarios[$k]['NOM_SALON']."";
                                                            echo $dia."<br>";
                                                            unset ($dia);
                                                            $k++;
                                                        }
                                                        elseif ($resultado_horarios[$k]['DIA']==$i && $resultado_horarios[$k]['DIA']==$resultado_horarios[$k+1]['DIA'] && $resultado_horarios[$k+1]['SALON_NVO']!=($resultado_horarios[$k]['SALON_NVO'])) {
                                                            $dia="<strong>".$resultado_horarios[$k]['HORA']."-".($resultado_horarios[$k]['HORA']+1)."</strong><br>Sede: ".$resultado_horarios[$k]['NOM_SEDE']."<br>Edificio: ".$resultado_horarios[$k]['NOM_EDIFICIO']."<br>Salon: ".$resultado_horarios[$k]['SALON_NVO']."<BR> ".$resultado_horarios[$k]['NOM_SALON']."";
                                                            echo $dia."<br>";
                                                            unset ($dia);
                                                        }
                                                        elseif ($resultado_horarios[$k]['dia']!=$i) {

                                                        }
                                                    }
                                                    ?></td><?
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

                <?
                }
                else {
                    ?>
    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
        <thead class='texto_subtitulo cuadro_color centrar'>
        <td><center><?echo $resultado_espaciosCarrera[$z][0]." - ".$resultado_espaciosCarrera[$z][1]?></center></td>
        </thead>
        <tr class="cuadro_plano centrar">
            <td class="cuadro_plano centrar">
                No tiene grupos registrados.
            </td>
        </tr>
    </table>
                <?
                }

            }

            ?>
</table>
    <?
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


    function verEstudiantes($configuracion) {
        $variable=array($_REQUEST['codProyecto'],$_REQUEST['idEspacio'],$_REQUEST['grupo']);

        $cadena_sql_estudiantes=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"estudiantes_inscritos",$variable);//echo $cadena_sql_estudiantes;exit;
        $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_estudiantes,"busqueda" );
        //echo $cadena_sql_estudiantes;
        //exit;
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
            <h4>ESTUDIANTES QUE PERTENECEN AL GRUPO <?echo $_REQUEST['grupo']?> DE <?echo $_REQUEST['nombreEspacio']?></h4>
            <hr noshade class="hr">

        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
            <a href="javascript:history.back()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" width="35" height="35" border="0"><br>Regresar
            </a>
        </td>
    </tr>
            <?
            if($resultado_estudiantes!=NULL) {
                ?>
    <tr class="cuadro_color centrar">
        <td>
            Nro.
        </td>
        <td>
            C&oacute;digo
        </td>
        <td>
            Nombre Estudiante
        </td>
        <td>
            Proyecto Curricular
        </td>
       <!-- <td>

        </td>-->
    </tr>
                <?

                for($i=0;$i<count($resultado_estudiantes);$i++) {
                    ?>
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <tr>
            <td class="cuadro_plano centrar">
                                <?echo $i+1?>
            </td>
            <td class="cuadro_plano centrar">
                                <?echo $resultado_estudiantes[$i][0]?>
            </td>
            <td class="cuadro_plano">
                                <?echo $resultado_estudiantes[$i][1]?>
            </td>
            <td class="cuadro_plano centrar">
                                <?echo $resultado_estudiantes[$i][2]?>
            </td>
           <!-- <td class="cuadro_plano centrar">
                <input type="hidden" name="opcion" value="eliminarRegistro">
                <input type="hidden" name="codEstudiante" value="<?echo $resultado_estudiantes[$i][0]?>">
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                <input type="hidden" name="idEspacio" value="<?echo $_REQUEST['idEspacio']?>">
                <input type="hidden" name="grupo" value="<?echo $_REQUEST['grupo']?>">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input name='eliminar' value='Eliminar' type='submit' >
            </td>-->
        </tr>
    </form>
                <?                
                }
                ?>
</table>
        <?
        }else {
            ?>
<tr>
    <td class="cuadro_plano centrar" colspan="4">
        No existen estudiantes inscritos.
    </td>
</tr>
        <?
        }

    }

    function eliminarEstudiante($configuracion) {

        $variable=array($_REQUEST["codEstudiante"],$_REQUEST['codProyecto'],$_REQUEST['idEspacio'],$_REQUEST['grupo']);

        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        
        $cadena_sql_borrarEstudianteMysql=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "borrarEstudianteMysql", $variable);
        $resultado_borrarEstudianteMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_borrarEstudianteMysql, "");

        if($resultado_borrarEstudianteMysql==true)
            {
                $cadena_sql_borrarEstudianteOracle=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "borrarEstudianteOracle",$variable);
                $resultado_borrarEstudianteOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_borrarEstudianteOracle, "" );

                $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "actualizarCupo", $variable);
                $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo, "" );

                $variablesRegistro=array($this->usuario,date('YmdGis'),'20','Borrar Estudiante de grupo',$ano[0]."-".$ano[1].", ".$_REQUEST['idEspacio'].", 0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['codProyecto'],$_REQUEST['codEstudiante']);

                $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                echo "<script> alert('Registro Eliminado de las bases de datos correctamentemente');</script>";

                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminEspaciosHorarios";
                $variable.="&opcion=verEstudiantes";
                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                $variable.="&idEspacio=".$_REQUEST["idEspacio"];
                $variable.="&grupo=".$_REQUEST["grupo"];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";

            }
            else
                {
                    echo "<script> alert('La base de datos se encuentra ocupada');</script>";

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminEspaciosHorarios";
                    $variable.="&opcion=verEstudiantes";
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                    $variable.="&idEspacio=".$_REQUEST["idEspacio"];
                    $variable.="&grupo=".$_REQUEST["grupo"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                }



        }

}
    ?>
