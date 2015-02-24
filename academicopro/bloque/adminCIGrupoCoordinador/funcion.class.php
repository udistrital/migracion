
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
class funcion_adminCIGrupoCoordinador extends funcionGeneral {


    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_adminCIGrupoCoordinador();
        $this->log_us= new log();
        $this->formulario="adminCIGrupoCoordinador";

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


    function buscador($configuracion,$planEstudioCoor,$codProyecto)
    {
        ?>
<script>
<!--

function mostrar_div(elemento) {

if(elemento.value=="cod") {
  document.getElementById("campo_palabra").style.display = "none";
  document.getElementById("campo_codigo").style.display = "block";
  document.getElementByTagName("codigoEA").focus();
  document.forms[0].palabraEA.value='';

}else if(elemento.value=="palab") {
  document.getElementById("campo_codigo").style.display = "none";
  document.getElementById("campo_palabra").style.display = "block";
  document.getElementsByTagName("palabraEA").focus();
  document.forms[0].codigoEA.value='';
}else {
 document.getElementById("campo_codigo").style.display = "block";
 document.getElementByTagName("codigoEA").focus();
}

}

-->
</script>
<?
if($planEstudioCoor){
?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
<div align="center">
    <table class="contenidotabla centrar">
                <tr>
                    <td class="cuadro_color centrar" colspan="3">
                       SELECCIONE LA OPCION COMO DESEA BUSCAR EL ESPACIO ACAD&Eacute;MICO
                    </td>
                </tr>
                <tr>
                    <td class="cuadro_plano derecha" width="20%">
                        C&oacute;digo<br>
                        Espacio Acad&eacute;mico
                    </td>
                    <td class="cuadro_plano centrar" width="10%">
                        <input type="radio" name="codigorad" value="cod" checked onclick="javascript:mostrar_div(this)"><br>
                        <input type="radio" name="codigorad" value="palab" onclick="javascript:mostrar_div(this)">
                    </td>
                    <td class="cuadro_plano centrar">
                        <div align="center" id="campo_codigo">
                            <table class="contenidotabla centrar">
                            <tr>
                                <td class="cuadro_plano centrar" colspan="2">
                                    <font size="1">Digite el c&oacute;digo del Espacio Académico que desea buscar</font><br>
                                    <input type="text" name="codigoEA" value="" size="6" maxlength="6">
                                </td>
                                <td class="cuadro_color centrar" rowspan="2">
                                    <input type="hidden" name="opcion" value="buscador">
                                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                    <input type="hidden" name="planEstudioCoor" value="<?echo $planEstudioCoor?>">
                                    <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                    <small><INPUT type="submit" value=" Buscar "></small>
                                </td>
                            </tr>
                            </table>
                        </div>
                        <div align="center" id="campo_palabra" style="display:none">
                            <table class="contenidotabla centrar"  width="50%" >
                            <tr>
                                <td class="cuadro_plano centrar" colspan="3">
                                    <font size="1">Digite el nombre del Espacio Académico que desea buscar</font><br>
                                    <input type="text" name="palabraEA" value="" size="30" maxlength="30">
                                </td>
                                <td class="cuadro_color centrar" rowspan="2">
                                    <input type="hidden" name="opcion" value="buscador">
                                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                    <input type="hidden" name="planEstudioCoor" value="<?echo $planEstudioCoor?>">
                                    <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                    <small><INPUT type="submit" value=" Buscar "></small>
                                </td>
                            </tr>
                            </table>
                        </div>
                    </td>
                    
                </tr></table>
        </div>
                
                
                
</form>
    <?
    }
    
    
    }


    function consultarGrupos($configuracion)
    {
        
        //var_dump($_REQUEST);
        if($_REQUEST['planEstudio'] && $_REQUEST['codProyecto'])
            {
                $planEstudio=$_REQUEST['planEstudio'];
                $codProyecto=$_REQUEST['codProyecto'];
            }else //if($_REQUEST['opcion']=="verProyectos")
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$this->usuario);//echo $cadena_sql;exit;
                    $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $planEstudio=$resultado_datosCoordinador[0][2];
                    $codProyecto=$resultado_datosCoordinador[0][0];
                }

        if($_REQUEST['opcion']=='verProyectos' && count($resultado_datosCoordinador)>1)
            {
                break 2;
            }
        $this->buscador($configuracion,$planEstudio,$codProyecto);
        $nivel=0;
        $espacio=0;

        $variablesPlan=array($planEstudio,$codProyecto);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_activos",$variablesPlan);//echo $cadena_sql;exit;
        $resultado_espacios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $cadena_sql=$this->sql->cadena_sql($configuracion,"año_periodo",'');//echo $cadena_sql;exit;
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if(is_array($resultado_espacios))
            {
            ?>
                        <table class="contenidotabla">
                            <tr>
                            <hr align="center">
                            </tr>
                                <tr>
                                    <td class="cuadro_color centrar" width="10%"><font size="2" ><b>Espacios Academicos activos para el periodo <?echo $resultado_periodo[0][0]." - ".$resultado_periodo[0][1]?> </b></font></td>
                        </tr>
                        </table><?

                for($p=0;$p<count($resultado_espacios);$p++)
                //for($i=0;$i<6;$i++)
                {
                    if($resultado_espacios[$p][5]!=$nivel)
                        {?>
                        <table class="contenidotabla">
                            <tr>
                            <hr align="center">
                            </tr>
                                <tr>
                                    <td class="cuadro_color centrar" width="10%"><font size="2" ><b>Nivel <?echo $resultado_espacios[$p][5]?></b></font></td>
                        </tr>
                        </table><?
                        $nivel=$resultado_espacios[$p][5];
                        }
                    if($resultado_espacios[$p][0]!=$espacio)
                        {

                        ?>
                            <table class="contenidotabla">
                                <tr>
                                    <td colspan="11">
                                        <hr align="center">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar" width="10%">C&oacute;digo</td>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar" width="30%" colspan="6">Nombre Espacio Acad&eacute;mico</td>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar" width="10%">Nro Cr&eacute;ditos</td>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar" width="10%">H.T.D</td>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar" width="10%">H.T.C</td>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar" width="10%">H.T.A</td>
                                </tr>
                            
                        <?
                            $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_espacio",$resultado_espacios[$p][0]);//echo $cadena_sql;exit;
                            $resultado_espaciosDesc=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            ?>
                                
                                <tr>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar"><font size="2"><?echo $resultado_espaciosDesc[0][1]?></font></td>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar" colspan="6"><font size="2"><?echo $resultado_espaciosDesc[0][2]?></font></td>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar"><font size="2"><?echo $resultado_espaciosDesc[0][3]?></font></td>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar"><font size="2"><?echo $resultado_espaciosDesc[0][4]?></font></td>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar"><font size="2"><?echo $resultado_espaciosDesc[0][5]?></font></td>
                                    <td class="cuadro_brownOscuro cuadro_plano centrar"><font size="2"><?echo $resultado_espaciosDesc[0][6]?></font></td>
                                </tr>
                                                                
                                <tr>
                                    <td class="cuadro_color centrar">Nro Grupo</td>
                                    <td class="cuadro_color centrar" width="12">Lunes</td>
                                    <td class="cuadro_color centrar" width="12">Martes</td>
                                    <td class="cuadro_color centrar" width="12">Miercoles</td>
                                    <td class="cuadro_color centrar" width="12">Jueves</td>
                                    <td class="cuadro_color centrar" width="12">Viernes</td>
                                    <td class="cuadro_color centrar" width="12">Sabado</td>
                                    <td class="cuadro_color centrar" width="12">Domingo</td>
                                    <td class="cuadro_color centrar">Nro Cupos</td>
                                    <td class="cuadro_color centrar">Disponibles</td>
                                    <td class="cuadro_color centrar">Administrar</td>
                                </tr>
                            <?
                            $espacio=$resultado_espacios[$p][0];
                        }
                        $variablesInscritos=array($espacio,$resultado_espacios[$p][1]);
                        $variables=array($resultado_espacios[$p][0],$resultado_espacios[$p][2],'',$resultado_espacios[$p][1]);
                        $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_grupos", $variables);//echo $cadena_sql_horarios;exit;
                        $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );
                        
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacio_grupoInscritos", $variablesInscritos);//echo $cadena_sql;exit;
                        $resultado_inscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        ?>
                        <tr>
                            <td class="cuadro_plano centrar"><?echo $resultado_espacios[$p][1]?></td>
                        <?
                            $this->mostrarHorario($configuracion,$resultado_horarios);
                        
                        ?>
                        </td>
                                <td class="cuadro_plano centrar"><?echo $resultado_espacios[$p][3]?></td>
                                <td class="cuadro_plano centrar"><?echo ($resultado_espacios[$p][3]-$resultado_inscritos[0][0])?></td>
                                <td class="cuadro_plano centrar">
                                    <?

                                    if(is_array($resultado_horarios))
                                        {
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=adminConsultarCIGrupoCoordinador";
                                        $variable.="&opcion=verGrupo";
                                        $variable.="&opcion2=cuadroRegistro";
                                        $variable.="&codEspacio=".$resultado_espaciosDesc[0][1];
                                        $variable.="&nombreEspacio=".$resultado_espaciosDesc[0][2];
                                        $variable.="&nroCreditos=".$resultado_espaciosDesc[0][3];
                                        $variable.="&nroGrupo=".$resultado_espacios[$p][1];
                                        $variable.="&planEstudio=".$planEstudio;
                                        $variable.="&codProyecto=".$codProyecto;

                                        //var_dump($_REQUEST);exit;
                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                                    ?>
                                    <a href="<?echo $pagina.$variable?>">
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kate.png" width="20" height="20" border="0">
                                    </a>
                                    <?
                                    }else
                                        {
                                            echo "Falta Horario";
                                        }
                                    ?>
                                </td>
                        </tr>
                        <?

                }
            }else
                {
                ?>
                        <table class="contenidotabla">
                                <tr>
                                    <td class="cuadro_color centrar" width="10%">
                                        <font size="2" ><b>No tiene cursos disponibles para el periodo <?echo $resultado_periodo[0][0]." - ".$resultado_periodo[0][1]?></b></font>
                                    </td>
                                </tr>
                        </table>
                <?
                }


    }

    function mostrarHorario($configuracion,$resultado_horarios)
                {
       if(is_array($resultado_horarios))
       {
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
                                    $dia="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong>";
                                    echo $dia."<br>";
                                    unset ($dia);
                                }
                                elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0]) {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong>";
                                    echo $dia."<br>";
                                    unset ($dia);
                                    $k++;
                                }
                                elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3])) {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong>";
                                    echo $dia."<br>";
                                    unset ($dia);
                                }
                                elseif ($resultado_horarios[$k][0]!=$i) {

                                }
                            }

                        }

                        }else
                            {
                                echo "<td class='cuadro_plano centrar' colspan='7'>No existe registrado un horario para este grupo</td>";
                            }
                }

       function consultarGruposSeleccionado($configuracion)
       {
           $atributos[0]['inicio']="pagina=adminCursosIntermediosCoordinador";
           $atributos[1]['inicio']="&opcion=mostrar";
           $atributos[2]['inicio']="&planEstudio=".$_REQUEST['planEstudioCoor'];
           $atributos[3]['inicio']="&codProyecto=".$_REQUEST['codProyecto'];
           $atributos[0]['atras']="pagina=adminCursosIntermediosCoordinador";
           $atributos[1]['atras']="&opcion=mostrar";
           $atributos[2]['atras']="&planEstudio=".$_REQUEST['planEstudioCoor'];
           $atributos[3]['atras']="&codProyecto=".$_REQUEST['codProyecto'];
           
           $this->encabezado($configuracion,$atributos);


                $nivel=0;
                $espacio=0;
if($_REQUEST['codEspacio']){
        if(!is_numeric($_REQUEST['codEspacio']))
        {
            echo "<script>alert('El código del espacio académico debe ser numerico')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=adminCIGrupoCoordinador";
            $variables.="&opcion=consultar";
            $variables.="&planEstudio=".$_REQUEST['planEstudioCoor'];
            $variables.="&codProyecto=".$_REQUEST['codProyecto'];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
        }
        $variableCodigo=array($_REQUEST['planEstudioCoor'],$_REQUEST['codEspacio'],$_REQUEST['codProyecto']);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_seleccionadoCodigo",$variableCodigo);//echo $cadena_sql;exit;
        $resultado_espacios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

        $this->buscador($configuracion, $_REQUEST['planEstudioCoor'],$_REQUEST['codProyecto']);


        if(is_array($resultado_espacios))
            {
                for($p=0;$p<count($resultado_espacios);$p++)
                {
                    if($resultado_espacios[$p][5]!=$nivel)
                        {?>
                            <table class="contenidotabla">
                                <tr>
                                <hr align="center">
                                </tr>
                                    <tr>
                                        <td class="cuadro_color centrar" width="10%"><font size="2" ><b>Nivel <?echo $resultado_espacios[$p][5]?></b></font></td>
                            </tr>
                            </table>
                        <?
                            $nivel=$resultado_espacios[$p][5];
                        }

                    if($resultado_espacios[$p][0]!=$espacio)
                    {
                        ?>
                        <table class="contenidotabla">
                            <tr>
                                <td colspan="11">
                                    <hr align="center">
                                </td>
                            </tr>
                            <tr>
                                <td class="cuadro_brownOscuro centrar" width="10%">C&oacute;digo</td>
                                <td class="cuadro_brownOscuro centrar" width="30%" colspan="6">Nombre Espacio Acad&eacute;mico</td>
                                <td class="cuadro_brownOscuro centrar" width="10%">Nro Cr&eacute;ditos</td>
                                <td class="cuadro_brownOscuro centrar" width="10%">H.T.D</td>
                                <td class="cuadro_brownOscuro centrar" width="10%">H.T.C</td>
                                <td class="cuadro_brownOscuro centrar" width="10%">H.T.A</td>
                            </tr>

                    <?
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_espacio",$resultado_espacios[$p][0]);//echo $cadena_sql;exit;
                        $resultado_espaciosDesc=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                        ?>

                            <tr>
                                <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][1]?></font></td>
                                <td class="cuadro_brownOscuro centrar" colspan="6"><font size="2"><?echo $resultado_espaciosDesc[0][2]?></font></td>
                                <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][3]?></font></td>
                                <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][4]?></font></td>
                                <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][5]?></font></td>
                                <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][6]?></font></td>
                            </tr>

                            <tr>
                                <td class="cuadro_color centrar">Nro Grupo</td>
                                <td class="cuadro_color centrar" width="12">Lunes</td>
                                <td class="cuadro_color centrar" width="12">Martes</td>
                                <td class="cuadro_color centrar" width="12">Miercoles</td>
                                <td class="cuadro_color centrar" width="12">Jueves</td>
                                <td class="cuadro_color centrar" width="12">Viernes</td>
                                <td class="cuadro_color centrar" width="12">Sabado</td>
                                <td class="cuadro_color centrar" width="12">Domingo</td>
                                <td class="cuadro_color centrar">Nro Cupos</td>
                                <td class="cuadro_color centrar">Disponibles</td>
                                <td class="cuadro_color centrar">Administrar</td>
                            </tr>
        <?
        $espacio=$resultado_espacios[$p][0];
    }

    $variables=array($resultado_espacios[$p][0],$resultado_espacios[$p][2],'',$resultado_espacios[$p][1]);
    $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_grupos", $variables);//echo $cadena_sql_horarios;exit;
    $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

    ?>
    <tr>
        <td class="cuadro_plano centrar"><?echo $resultado_espacios[$p][1]?></td>
    <?
        $this->mostrarHorario($configuracion,$resultado_horarios);

    ?>
    </td>
            <td class="cuadro_plano centrar"><?echo $resultado_espacios[$p][3]?></td>
            <td class="cuadro_plano centrar"><?echo ($resultado_espacios[$p][3]-$resultado_espacios[$p][4])?></td>
            <td class="cuadro_plano centrar">
                <?
                if(is_array($resultado_horarios))
                    {
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarCIGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&codEspacio=".$resultado_espaciosDesc[0][1];
                    $variable.="&nombreEspacio=".$resultado_espaciosDesc[0][2];
                    $variable.="&nroCreditos=".$resultado_espaciosDesc[0][3];
                    $variable.="&nroGrupo=".$resultado_espacios[$p][1];
                    $variable.="&planEstudio=".$_REQUEST['planEstudioCoor'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    //var_dump($_REQUEST);exit;
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);
                ?>
                <a href="<?echo $pagina.$variable?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kate.png" width="20" height="20" border="0">
                </a>
                <?
                                    }else
                                        {
                                            echo "Falta Horario";
                                        }
                                    ?>
            </td>
    </tr>
    <?

                }
      }
      else{
              ?>
                <table class="contenidotabla">
                    <tr>
                        <td class="centrar">
                            No hay grupos habilitados en el periodo actual que corresponda con su busqueda
                        </td>
                    </tr>
                </table>
              <?
          }
}
                        
 else if($_REQUEST['palabraEA'])
     {

        $nombreEspacio=strtr(strtoupper($_REQUEST['palabraEA']), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");

        $variableCodigo=array($_REQUEST['planEstudioCoor'],$nombreEspacio,$_REQUEST['codProyecto']);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"espacios_seleccionadoPalabra",$variableCodigo);//echo $cadena_sql;exit;
        $resultado_espacios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $this->buscador($configuracion, $_REQUEST['planEstudioCoor'],$_REQUEST['codProyecto']);


        if(is_array($resultado_espacios))
            {
                for($p=0;$p<count($resultado_espacios);$p++)
                {
                    if($resultado_espacios[$p][5]!=$nivel)
                        {?>
                            <table class="contenidotabla">
                                <tr>
                                <hr align="center">
                                </tr>
                                    <tr>
                                        <td class="cuadro_color centrar" width="10%"><font size="2" ><b>Nivel <?echo $resultado_espacios[$p][5]?></b></font></td>
                            </tr>
                            </table>
                        <?
                            $nivel=$resultado_espacios[$p][5];
                        }

                    if($resultado_espacios[$p][0]!=$espacio)
                    {
                        ?>
                        <table class="contenidotabla">
                            <tr>
                                <td colspan="11">
                                    <hr align="center">
                                </td>
                            </tr>
                            <tr>
                                <td class="cuadro_brownOscuro centrar" width="10%">C&oacute;digo</td>
                                <td class="cuadro_brownOscuro centrar" width="30%" colspan="6">Nombre Espacio Acad&eacute;mico</td>
                                <td class="cuadro_brownOscuro centrar" width="10%">Nro Cr&eacute;ditos</td>
                                <td class="cuadro_brownOscuro centrar" width="10%">H.T.D</td>
                                <td class="cuadro_brownOscuro centrar" width="10%">H.T.C</td>
                                <td class="cuadro_brownOscuro centrar" width="10%">H.T.A</td>
                            </tr>

                    <?
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_espacio",$resultado_espacios[$p][0]);//echo $cadena_sql;exit;
                        $resultado_espaciosDesc=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                        ?>

                            <tr>
                                <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][1]?></font></td>
                                <td class="cuadro_brownOscuro centrar" colspan="6"><font size="2"><?echo $resultado_espaciosDesc[0][2]?></font></td>
                                <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][3]?></font></td>
                                <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][4]?></font></td>
                                <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][5]?></font></td>
                                <td class="cuadro_brownOscuro centrar"><font size="2"><?echo $resultado_espaciosDesc[0][6]?></font></td>
                            </tr>

                            <tr>
                                <td class="cuadro_color centrar">Nro Grupo</td>
                                <td class="cuadro_color centrar" width="12">Lunes</td>
                                <td class="cuadro_color centrar" width="12">Martes</td>
                                <td class="cuadro_color centrar" width="12">Miercoles</td>
                                <td class="cuadro_color centrar" width="12">Jueves</td>
                                <td class="cuadro_color centrar" width="12">Viernes</td>
                                <td class="cuadro_color centrar" width="12">Sabado</td>
                                <td class="cuadro_color centrar" width="12">Domingo</td>
                                <td class="cuadro_color centrar">Nro Cupos</td>
                                <td class="cuadro_color centrar">Disponibles</td>
                                <td class="cuadro_color centrar">Administrar</td>
                            </tr>
        <?
        $espacio=$resultado_espacios[$p][0];
    }

    $variables=array($resultado_espacios[$p][0],$resultado_espacios[$p][2],'',$resultado_espacios[$p][1]);
    $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,"horario_grupos", $variables);//echo $cadena_sql_horarios;exit;
    $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

    ?>
    <tr>
        <td class="cuadro_plano centrar"><?echo $resultado_espacios[$p][1]?></td>
    <?
        $this->mostrarHorario($configuracion,$resultado_horarios);

    ?>
    </td>
            <td class="cuadro_plano centrar"><?echo $resultado_espacios[$p][3]?></td>
            <td class="cuadro_plano centrar"><?echo ($resultado_espacios[$p][3]-$resultado_espacios[$p][4])?></td>
            <td class="cuadro_plano centrar">
                <?
                if(is_array($resultado_horarios))
                {
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarCIGrupoCoordinador";
                    $variable.="&opcion=verGrupo";
                    $variable.="&codEspacio=".$resultado_espaciosDesc[0][1];
                    $variable.="&nombreEspacio=".$resultado_espaciosDesc[0][2];
                    $variable.="&nroCreditos=".$resultado_espaciosDesc[0][3];
                    $variable.="&nroGrupo=".$resultado_espacios[$p][1];
                    $variable.="&planEstudio=".$_REQUEST['planEstudioCoor'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    //var_dump($_REQUEST);exit;
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);
                ?>
                <a href="<?echo $pagina.$variable?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kate.png" width="20" height="20" border="0">
                </a>
                <?
                                    }else
                                        {
                                            echo "Falta Horario";
                                        }
                                    ?>
            </td>
    </tr>
    <?

                }
            }else
                {
                    ?>
    <table class="contenidotabla">
        <tr>
            <td class="centrar">
                No hay grupos habilitados en el periodo actual que corresponda con su busqueda
            </td>
        </tr>
    </table>
                    <?
                }
     }else if($_REQUEST['codEspacio']=='' && $_REQUEST['palabraEA']=='')
         {
            echo "<script>alert('Digite el nombre o código del espacio académico')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminCursosIntermediosCoordinador";
                $variable.="&opcion=mostrar";
                $variable.="&planEstudio=".$_REQUEST['planEstudioCoor'];
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
         }

                        }

     function encabezado($configuracion,$atributos)
        {
            ?>
    <table class="contenidotabla centrar">
        <tr>
            <td width="33%" class="centrar">
                <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable=$atributos[0]['atras'];
                $variable.=$atributos[1]['atras'];
                $variable.=$atributos[2]['atras'];
                $variable.=$atributos[3]['atras'];
                $variable=$this->cripto->codificar_url($variable,$configuracion);
                ?>
                <a href="<?echo $pagina.$variable?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="35" height="35" border="0">
                </a>
            </td>
            <td width="33%" class="centrar">
                <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable=$atributos[0]['inicio'];
                $variable.=$atributos[1]['inicio'];
                $variable.=$atributos[2]['inicio'];
                $variable.=$atributos[3]['inicio'];
                $variable=$this->cripto->codificar_url($variable,$configuracion);
                ?>
                <a href="<?echo $pagina.$variable?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0">
                </a>
            </td>
            <td width="33%" class="centrar">
                <a href="history.forward()">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png" width="35" height="35" border="0">
                </a>
            </td>
        </tr>
    </table>
            <?
        }
}
?>
