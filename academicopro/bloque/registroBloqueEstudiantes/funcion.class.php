<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroBloqueEstudiantes extends funcionGeneral {
    private $configuracion;
    //Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validar_fechas.class.php");
        $this->configuracion=$configuracion;
        $this->fechas=new validar_fechas();
        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=$sql;

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        $this->formulario="registroBloqueEstudiantes";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"ano_periodo",'');
        $this->periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );


        
    }

    function verProyectos() {

        //Consultamos los proyectos curriculares con su respectivo plan de estudio, y los mostramos en un <select>
        $cadena_sql_proyectos=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"proyectos_curriculares",$this->usuario);
        $resultado_proyectos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );

        if(is_array($resultado_proyectos)&&count($resultado_proyectos)>1) {
        ?>
<table class='contenidotabla centrar' background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/pequeno_universidad.png ">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>PLANES DE ESTUDIO ASOCIADOS AL USUARIO <?echo $this->usuario?></h4>
            <hr noshade class="hr">
        </td>
    </tr>
    <br><br>
    <tr class="centrar">
        <td class="cuadro_color centrar" colspan="3">
            SELECCIONE EL PLAN DE ESTUDIOS
        </td>
    </tr>
    <tr>
        <td class="cuadro_color centrar">Carrera</td>
        <td class="cuadro_color centrar">Plan de Estudios</td>
        <td class="cuadro_color centrar">Nombre</td>
    </tr>
    <?
    for($i=0;$i<count($resultado_proyectos);$i++) 
    {
    ?>
    <tr>
        <?
            if ($resultado_proyectos[$i][0]==97 || $resultado_proyectos[$i][0]==98)
            {
                $cadena_sql_plan=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion, "planEstudio",$resultado_proyectos[$i][2]);
                $nombreProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_plan,"busqueda" );
                $resultado_proyectos[$i][1]=$nombreProyecto[0][0];
            }

            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variable="pagina=registroBloqueEstudiantes";
            $variable.="&opcion=crear";
            $variable.="&codProyecto=".$resultado_proyectos[$i][0];
            $variable.="&planEstudio=".$resultado_proyectos[$i][2];
            $variable.="&nombreProyecto=".$resultado_proyectos[$i][1];

            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        ?>
        <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_proyectos[$i][0]?></a></td>
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
                  $this->moduloHabilitado($this->configuracion);
              }
              else
              {
                $this->noPlan($this->configuracion);
                exit;

              }
            }
    }

        function noPlan() {
?>
                      <table class='contenidotabla centrar' background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                          <tr align="center">
                              <td class="centrar" colspan="4">
                                  <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                  <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/pequeno_universidad.png ">
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

    function moduloHabilitado()
    {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        
        if($_REQUEST['codProyecto'] && $_REQUEST['planEstudio'] && $_REQUEST['nombreProyecto'])
            {
                $variable=array($_REQUEST['codProyecto'],$_REQUEST['nombreProyecto'],$_REQUEST['planEstudio']);
            }else
                {
                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"datos_coordinador",$this->usuario);
                    $resultado_datosCoordinador=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    $variable=array($resultado_datosCoordinador[0][0],$resultado_datosCoordinador[0][1],$resultado_datosCoordinador[0][2]);
                }
        if(is_numeric($variable[0]) && is_numeric($variable[2])){
            $fecha=$this->fechas->validar_fechas_grupo_coordinador($this->configuracion, $variable[0]);

            if($fecha=='adicion')
            {
                $this->crearBloque($variable);
            }
            else
            {
                $this->fechaTerminada($variable);
            }
            $this->enlaceRegresarPrincipal();
        }else{
           echo "El c&oacute;digo de proyecto y el plan de estudios deben ser num&eacute;ricos" ;
        }
        exit;

    }

    function crearBloque($variable)
    {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if($resultado_periodo[0][1]==3)
            {
                $periodoEst=2;
            }else
                {
                    $periodoEst=1;
                }

        if($variable[0] && $variable[2] && $variable[1])
            {
                $variable=array($variable[0], $variable[1],$variable[2]);
            }else
                {
                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"datos_coordinador",$this->usuario);
                    $resultado_datosCoordinador=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                    $variable=array($resultado_datosCoordinador[0][0],$resultado_datosCoordinador[0][1],$resultado_datosCoordinador[0][2]);
                }
        ?>
<table class='contenidotabla centrar' border="0" background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td class="centrar" colspan="10">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
        </td>
    </tr>
    <tr>
        <td class="izquierda" colspan="10">
        <?
            echo "<b>PROYECTO CURRICULAR: </b>".$variable[0]." - ".strtoupper($variable[1]);
            echo "<br><b>PLAN DE ESTUDIOS: </b>".$variable[2];
        ?>
            <hr noshade class="hr">
        </td>
    </tr>
        <?
        $variable[3]=$resultado_periodo[0][0];
        $variable[4]=$periodoEst;
        $variable[5]=$resultado_periodo[0][1];
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"bloques_registrados",$variable);
            $resultado_bloquesRegistrados=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
            
            if($resultado_bloquesRegistrados[0][0]==NULL)
                {
                ?>
                    <tr>
                        <td class="centrar" colspan="10">
                            No tiene bloques de estudiantes registrados
                        </td>
                    </tr>
                <?
                }else
                    {
                ?>
                    <tr class="cuadro_brownOscuro centrar">
                        <td width="12%">Nombre del Bloque</td>
                        <td width="5%">Estudiantes</td>
                        <td width="5%">Espacios Asignados</td>
                        <td width="40%" colspan="4">Administrar</td>
                    </tr>
                <?
                for($i=0;$i<count($resultado_bloquesRegistrados);$i++) {

                    $variable[3]=$resultado_bloquesRegistrados[$i][0];
                    $variable[5]=$resultado_periodo[0][0];
                    $variable[6]=$resultado_periodo[0][1];

                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"estudiantes_bloquesRegistrados",$variable);
                    $resultado_estudiantesBloque=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"espaciosRegistrados",$variable);
                    $resultado_espaciosRegistrados=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                    ?>
    <tr class="cuadro_plano centrar">
        <td width="12%">
            <font size="2"><b>Bloque <?echo $resultado_bloquesRegistrados[$i][0]?></b></font>
        </td>
        <td width="5%">
            <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=registroBloqueEstudiantes";
                        $ruta.="&opcion=editar";
                        $ruta.="&numeroBloque=".$resultado_bloquesRegistrados[$i][0];
                        $ruta.="&codProyecto=".$variable[0];
                        $ruta.="&planEstudio=".$variable[2];
                        $ruta.="&nombreProyecto=".$variable[1];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);

                        if(count($resultado_estudiantesBloque)>0)
                        {
                            ?>
                            <a href="<?= $pagina.$ruta ?>" >
                                <font size="2"><b><?echo count($resultado_estudiantesBloque)?></b></font>
                            </a><?
                        }else
                            {?>
                               <font size="2"><b><?echo count($resultado_estudiantesBloque)?></b></font>
                               <?
                            }?>
            
        </td>
        <td width="5%">
            <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=registroBloqueEstudiantes";
                        $ruta.="&opcion=horario";
                        $ruta.="&idBloque=".$resultado_bloquesRegistrados[$i][0];
                        $ruta.="&codProyecto=".$variable[0];
                        $ruta.="&planEstudio=".$variable[2];
                        $ruta.="&nombreProyecto=".$variable[1];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);

                        if(count($resultado_espaciosRegistrados)>0)
                        {
                            ?>
                            <a href="<?= $pagina.$ruta ?>" >
                                <font size="2"><b><?echo count($resultado_espaciosRegistrados)?></b></font>
                            </a><?
                        }else
                            {?>
                               <font size="2"><b><?echo count($resultado_espaciosRegistrados)?></b></font>
                                <?
                            }?>            
       </td>
                <?if($resultado_bloquesRegistrados[$i][3]=='0') {?>
        <td width="10%">
                    <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=registroBloqueEstudiantes";
                        $ruta.="&opcion=borrar";
                        $ruta.="&codProyecto=".$variable[0];
                        $ruta.="&planEstudio=".$variable[2];
                        $ruta.="&nombreProyecto=".$variable[1];
                        $ruta.="&idBloque=".$resultado_bloquesRegistrados[$i][0];
                        $ruta.="&estudiantesRegistrados=".count($resultado_estudiantesBloque);
                        $ruta.="&espaciosRegistrados=".count($resultado_espaciosRegistrados);

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                    ?>
            <a href="<?echo $pagina.$ruta?>">
                <img alt="Horario" border="0" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/no.png"onmouseover="this.width=50;this.height=50" onmouseout="this.width=30;this.height=30" width="30" height="30"><br><font size="1">Borrar Bloque</font>
            </a>
        </td>
        <td width="10%">
                    <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=registroHorarioBloqueCoordinador";
                        $ruta.="&opcion=horario";
                        $ruta.="&codProyecto=".$variable[0];
                        $ruta.="&planEstudio=".$variable[2];
                        $ruta.="&nombreProyecto=".$variable[1];
                        $ruta.="&idBloque=".$resultado_bloquesRegistrados[$i][0];
                        $ruta.="&totalEstudiantes=".count($resultado_estudiantesBloque);

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                    ?>
            <a href="<?echo $pagina.$ruta?>">
                <img alt="Horario" border="0" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/vcalendar.png"onmouseover="this.width=50;this.height=50" onmouseout="this.width=30;this.height=30" width="30" height="30"><br><font size="1">Horario</font>
            </a>
        </td>
        <td width="10%">
                    <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=registroEstudiantesBloqueCoordinador";
                        $ruta.="&opcion=editar";
                        $ruta.="&codProyecto=".$variable[0];
                        $ruta.="&planEstudio=".$variable[2];
                        $ruta.="&nombreProyecto=".$variable[1];
                        $ruta.="&idBloque=".$resultado_bloquesRegistrados[$i][0];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                    ?>
            <a href="<?echo $pagina.$ruta?>">
                <img alt="Horario" border="0" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/personas.png"onmouseover="this.width=50;this.height=50" onmouseout="this.width=30;this.height=30" width="30" height="30"><br><font size="1">Estudiantes</font>
            </a>
        </td>
        <td width="10%">
                    <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=registroBloqueEstudiantes";
                        $ruta.="&opcion=inscripcion";
                        $ruta.="&codProyecto=".$variable[0];
                        $ruta.="&planEstudio=".$variable[2];
                        $ruta.="&nombreProyecto=".$variable[1];
                        $ruta.="&idBloque=".$resultado_bloquesRegistrados[$i][0];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                    ?>
            <a href="<?echo $pagina.$ruta?>">
                <img alt="Borrar" border="0" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/web.png"onmouseover="this.width=50;this.height=50" onmouseout="this.width=30;this.height=30" width="30" height="30"><br><font size="1">Publicar</font>
            </a>
        </td>
                <?
                }else if($resultado_bloquesRegistrados[$i][3]=='1')
                    {
                    ?>
        <td colspan="4">
            <font color="blue">Los estudiantes de este bloque ya tienen registrados espacios acad&eacute;micos</font>
        </td>
                            <?
                }
            }
        }
                ?>

    <tr class="centrar">
        <td class="centrar" colspan="7">
                <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $ruta="pagina=registroBloqueEstudiantes";
                    $ruta.="&opcion=registrar";
                    $ruta.="&codProyecto=".$variable[0];
                    $ruta.="&planEstudio=".$variable[2];
                    $ruta.="&nombreProyecto=".$variable[1];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                    ?>
            <a href="<?echo $pagina.$ruta?>">
                <img border="0" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/kword.png" width="40" height="40"><br><font size="1">Registrar Bloque</font>
            </a>
        </td>
    </tr>
</table>
<table class="contenidotabla">
    <tr class="cuadro_plano centrar">
        <th>
            Observaciones
        </th>
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
        <?
    }

    function registrarBloque()
    {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if($resultado_periodo[0][1]=='3')
            {
                $perEst=$resultado_periodo[0][1]-1;
            }else
                {
                    $perEst=$resultado_periodo[0][1];
                }

        $variable=array($_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$resultado_periodo[0][0].$perEst);

        $this->verificar="control_vacio(".$this->formulario.",'idBloque')";
        $this->verificar.="&&verificar_numero(".$this->formulario.",'idBloque')";
        $this->verificar.="&&verificar_rango(".$this->formulario.",'idBloque','0','99')";
        ?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
    <table class='contenidotabla centrar' border="0" background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
        <tr align="center">
            <td class="centrar" colspan="10">
                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            </td>
        </tr>
        <tr>
            <td class="izquierda" colspan="10">
        <?
        echo "<b>PROYECTO CURRICULAR: </b>".$variable[0]." - ".strtoupper($_REQUEST['nombreProyecto']);
        echo "<br><b>PLAN DE ESTUDIOS: </b>".$variable[1];
        ?>
                <hr noshade class="hr">
            </td>
        </tr>
                        <?

        $cadena_sql_estudiantes=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"estudiantes_carrera",$variable);
        $resultado_estudiantes=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_estudiantes,"busqueda" );
                if($resultado_estudiantes[0][0]==NULL) {
                    ?>
        <tr>
            <td class="centrar" colspan="5">
                    <?
                    echo "No hay estudiantes inscritos en el proyecto curricular";
            ?>
            </td>
        </tr>
        <tr>
            <td class="centrar" width="50%">
                <br>
                        <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=registroBloqueEstudiantes";
                        $ruta.="&opcion=crear";
                        $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                        $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                        $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);

                        ?>
                <a href="<?= $pagina.$ruta ?>">
                    <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/atras.png" width="30" height="30" border="0"><br><font size="1">Regresar</font>
                </a>
            </td>
        </tr>
                            <?
                        }else {?>
        <tr class="centrar">
            <td colspan="5">
                Digite un n&uacute;mero para identificar el bloque:
                <br>
                <input type="text" name="idBloque" size="2" maxlength="2"  onKeyPress="return solo_numero(event)" >
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>" >
                <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>" >
                <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>" >
                <input type="hidden" name="opcion" value="estudiantes" >
                <input type="hidden" name="action" value="<?echo $this->formulario?>" ><br>
                <input type="button" name="Siguiente" value="Siguiente" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
            </td>
        </tr>
        <tr>
            <td class="centrar" width="50%">
                <br>
                        <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=registroBloqueEstudiantes";
                        $ruta.="&opcion=crear";
                        $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                        $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                        $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);

                        ?>
                <a href="<?= $pagina.$ruta ?>">
                    <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/atras.png" width="30" height="30" border="0"><br><font size="1">Regresar</font>
                </a>
            </td>
        </tr>
            <?
        }
        ?>
    </table>
</form>
                <?
            }

    function registrarBloqueEstudiantes()
    {
        if(is_numeric($_REQUEST['codProyecto']) && is_numeric($_REQUEST['planEstudio'])){
                $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"periodoActivo",'');
                $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if($resultado_periodo[0][1]==3)
                    {
                        $periodoEst=2;
                    }else
                        {
                            $periodoEst=1;
                        }

                if($_REQUEST['idBloque'] && is_numeric($_REQUEST['idBloque']) && $_REQUEST['idBloque']>0 && $_REQUEST['idBloque']<99) {
                    $variablesBloque=array($_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$_REQUEST['idBloque'],$resultado_periodo[0][0],$periodoEst,$resultado_periodo[0][1]);

                    $cadena_sql_verificarBloque=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"verificar_bloque", $variablesBloque);
                    $resultado_verificarBloque=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_verificarBloque,"busqueda" );

                    if(is_array($resultado_verificarBloque)) {
                        echo "<script>alert ('El bloque ".$variablesBloque[2]." ya esta registrado, por favor seleccione otro número');</script>";
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=registroBloqueEstudiantes";
                        $variable.="&opcion=registrar";
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                        $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                        $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";

                    }
                }else {
                    echo "<script>alert ('El bloque de estudiantes debe identificarse con un valor numérico entre 0 y 99');</script>";
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=registroBloqueEstudiantes";
                    $variable.="&opcion=crear";
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                    $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                }

                $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"periodoActivo",'');
                $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if($resultado_periodo[0][1]=='3')
                    {
                        $perEst=$resultado_periodo[0][1]-1;
                    }
                    else
                    {
                    $perEst=$resultado_periodo[0][1];
                    }

                $variable=array($_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$resultado_periodo[0][0].$perEst);

                $cadena_sql_estudiantes=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"estudiantes_carrera",$variable);
                $resultado_estudiantes=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_estudiantes,"busqueda" );

                ?>
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <table class='contenidotabla centrar' border="0" background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                <tr align="center">
                    <td class="centrar" colspan="10">
                        <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                    </td>
                </tr>
                <tr>
                    <td class="izquierda" colspan="10">
                <?
                echo "<b>PROYECTO CURRICULAR: </b>".$variable[0]." - ".strtoupper($_REQUEST['nombreProyecto']);
                echo "<br><b>PLAN DE ESTUDIOS: </b>".$variable[1];
                ?>
                        <hr noshade class="hr">
                    </td>
                </tr>
                <?if(is_array($resultado_estudiantes)) {?>
                <tr class="cuadro_color centrar">
                    <td colspan="5">
                        <h4>SELECCIONE LOS ESTUDIANTES QUE HARAN PARTE DE ESTE BLOQUE</h4>
                        <hr noshade class="hr">
                    </td>
                <tr>
                    <td colspan="5" class="centrar">
                        <script src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                        SELECCIONAR LOS
                        <select id="algunos" style="width:50" onchange="javascript:seleccionCheck(document.getElementById('seleccionados'),'registroBloqueEstudiantes',document.getElementById('algunos').value);"
                                <option value="0">0</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="30">30</option>
                        </select> PRIMEROS ESTUDIANTES
                    </td>
                </tr>
                <tr class="cuadro_color centrar">
                    <td width="10%">
                        N&Uacute;MERO
                    </td>
                    <td width="10%">
                        PROYECTO CURRICULAR
                    </td>
                    <td width="20%">
                        C&Oacute;DIGO ESTUDIANTE
                    </td>
                    <td width="40%">
                        NOMBRE ESTUDIANTE
                    </td>
                    <td width="10%">
                        <script src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                        TODOS
                        <br>
                        <input align="center" type=checkbox id="seleccionados" name="seleccionados" value="seleccionado" onclick="javascript:todos(this,'registroBloqueEstudiantes');">
                    </td>
                </tr>
                    <?$k=1;
                    $l=0;
                    for($i=0;$i<count($resultado_estudiantes);$i++) {
                        $variable[2]=$resultado_estudiantes[$i][0];
                        $cadena_sql_estudiantesReg=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"estudiantes_bloques",$variable);
                        $resultado_estudiantesRegistrados=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_estudiantesReg,"busqueda" );

                        if($resultado_estudiantesRegistrados==NULL) {
                            ?>
                <tr class="cuadro_plano">
                    <td width="20" class="centrar">
                                    <?echo $k?>
                    </td>
                    <td width="20" class="centrar">
                                    <?echo $resultado_estudiantes[$i][2]?>
                    </td>
                    <td width="30" class="centrar">
                            <?echo $resultado_estudiantes[$i][0]?>
                    </td>
                    <td width="40">
                            <?echo htmlentities($resultado_estudiantes[$i][1])?>
                    </td>
                    <td width="20" class="centrar">
                        <input type="checkbox" name="estudiante<?echo $i?>" value="<?echo $resultado_estudiantes[$i][0]?>">

                    </td>
                </tr>
                            <?$k++;$l++;
                                        }

                    }
                    if($l==0)
                    {
                    ?>
            <tr>
                <td class="cuadro_plano centrar" colspan="5">
                    <font size="2" color="red">No hay estudiantes disponibles para asociar a este bloque</font>
                </td>
            </tr>
                    <?
                    }
                    ?>
                <tr class="cuadro_plano centrar">
                    <td class="centrar" width="50%" colspan="3">
                            <?
                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=registroBloqueEstudiantes";
                            $variable.="&opcion=crear";
                            $variable.="&idBloque=".$_REQUEST['idBloque'];
                            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                            $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                            $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                                    ?>
                        <a href="<?= $pagina.$variable ?>" on>
                            <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/atras.png" width="50" height="50" border="0"><br><font size="2">Regresar</font>
                        </a>
                    </td>
                    <td width="50%" colspan="3">
                        <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                        <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
                        <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
                        <input type="hidden" name="idBloque" value="<?echo $_REQUEST['idBloque']?>">
                        <input type="hidden" name="periodo" value="<?echo $resultado_periodo[0][0].$resultado_periodo[0][1]?>">
                        <input type="hidden" name="opcion" value="guardar">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type='image' src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/grupoNuevo.png" width="50" height="50" border="0" ><br><font size="2">Guardar</font>

                    </td>
                </tr><?}else {
                    ?><tr>
                    <td class="cuadro_plano">
                        No existen estudiantes registrados para el periodo acad&eacute;mico vigente
                    </td>
                </tr><?
                }?>
            </table>
        </form><?
            }else{
                echo "<script>alert ('Código de proyecto curricular y/o plan de estudios incorrecto');</script>";
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=registroBloqueEstudiantes";
                        $variable.="&opcion=registrar";
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                        $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                        $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";

            }
            }

    function guardarBloque()
    {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

      if($resultado_periodo[0][1]=='3')
            {
                $perEst=$resultado_periodo[0][1]-1;
            }
            else
            {
              $perEst=$resultado_periodo[0][1];
            }
      $variablesBloque=array($_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$_REQUEST['idBloque'],$resultado_periodo[0][0], $perEst, $resultado_periodo[0][1]);

                $cadena_sql_verificarBloque=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"verificar_bloque", $variablesBloque);
        $resultado_verificarBloque=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_verificarBloque,"busqueda" );

        if($_REQUEST['idBloque']==NULL) {
            echo "<script>alert ('Por favor digite un número para identificar el bloque de estudiantes');</script>";
            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variable="pagina=registroBloqueEstudiantes";
            $variable.="&opcion=crear";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudio=".$_REQUEST['planEstudio'];
            $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
        else if($_REQUEST['estudiante0']==NULL) {
            echo "<script>alert ('Por favor seleccione estudiantes para este bloque');</script>";
            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variable="pagina=registroBloqueEstudiantes";
            $variable.="&opcion=crear";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudio=".$_REQUEST['planEstudio'];
            $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }

        else if($resultado_verificarBloque==NULL) {

            $cadena_sql_bloquePlanEstudio=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"guardar_bloque", $variablesBloque);
            $resultado_bloquePlanEstudio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_bloquePlanEstudio,"" );

            for($i=0;$i<=400;$i++) {
                $_REQUEST['estudiante'.$i]=(isset($_REQUEST['estudiante'.$i])?$_REQUEST['estudiante'.$i]:'');
                if($_REQUEST['estudiante'.$i]!=NULL && $_REQUEST['estudiante'.$i]!='') {

                    $variablesBloque[3]=$_REQUEST['estudiante'.$i];
                    $variablesBloque[4]=$resultado_periodo[0][0];
                    $cadena_sql_bloqueEstudiantes=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"guardar_estudiantes", $variablesBloque);
                    $resultado_bloqueEstudiantes=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_bloqueEstudiantes,"" );
                    unset($variablesBloque[3]);
                }
            }

            echo "<script>alert ('Se ha registrado un nuevo bloque, con los estudiantes seleccionados');</script>";
            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variable="pagina=registroBloqueEstudiantes";
            $variable.="&opcion=crear";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudio=".$_REQUEST['planEstudio'];
            $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }else {
            echo "<script>alert ('El bloque ".$variablesBloque[2]." ya esta registrado, por favor seleccione otro número');</script>";
            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variable="pagina=registroBloqueEstudiantes";
            $variable.="&opcion=registrar";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudio=".$_REQUEST['planEstudio'];
            $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }

    }

    function inscribirEstudiantes()
    {
        
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if($resultado_periodo[0][1]==3)
            {
                $periodoEst=2;
            }else
                {
                    $periodoEst=1;
                }


        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);

        $variables=array($_REQUEST['idBloque'],$_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$ano[0],$periodoEst, $ano[1]);

        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"seleccionar_estudiantes", $variables);
        $resultado_estudiantes=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"seleccionar_espaciosInscritos", $variables);
        $resultado_espaciosInscritos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        $creditos=0;

        if(is_array($resultado_estudiantes))
            {
                if(is_array($resultado_espaciosInscritos))
                    {
                    for($i=0;$i<count($resultado_estudiantes);$i++) {

                        for($j=0;$j<count($resultado_espaciosInscritos);$j++) {

                            $creditos+=$resultado_espaciosInscritos[$j]['espacio_nroCreditos'];
                            $inscripcion=array($resultado_estudiantes[$i][0],
                                $resultado_espaciosInscritos[$j]['horario_idEspacio'],
                                $resultado_espaciosInscritos[$j]['horario_grupo'],
                                $ano[0],
                                $ano[1],
                                $_REQUEST['idBloque'],
                                $_REQUEST['codProyecto'],
                                $_REQUEST['planEstudio'],
                                '',
                                $resultado_espaciosInscritos[$j]['espacio_nroCreditos'],
                                $resultado_espaciosInscritos[$j]['id_clasificacion'],
                                $resultado_espaciosInscritos[$j]['horasDirecto'],
                                $resultado_espaciosInscritos[$j]['horasCooperativo'],
                                $resultado_espaciosInscritos[$j]['espacio_horasAutonomo'],
                                $resultado_espaciosInscritos[$j]['id_nivel']);
                                $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"inscribir_espaciosOracle", $inscripcion);
                                $resultado_inscripcionOracle=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );

                                if($resultado_inscripcionOracle==true)
                                    {
                                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"actualizar_cupo", $inscripcion);
                                    $resultado_actualizarCupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );

                                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"inscribir_espaciosMysql", $inscripcion);
                                    $resultado_inscripcionMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );

                                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"estado_bloque", $variables);
                                    $resultado_estadoBloqueMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );

                                    $variablesRegistro=array($this->usuario,date('YmdGis'),'37','Publico Espacio Académico en Bloque '.$variables[0],$inscripcion[3]."-".$inscripcion[4].", ".$resultado_espaciosInscritos[$j]['horario_idEspacio'].", 0, ".$resultado_espaciosInscritos[$j]['horario_grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['codProyecto'],$resultado_estudiantes[$i][0]);

                                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                    $resultado_registroEvento=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );

                                    }
                           
                            $variables[5]=$resultado_estudiantes[$i][0];
                            
                        }
                        
                    }
                        echo "<script>alert ('Se han registrado los espacios académicos a los estudiantes del bloque ".$_REQUEST['idBloque']."');</script>";
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=registroBloqueEstudiantes";
                        $variable.="&opcion=crear";
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                        $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                        $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        

                }else
                    {
                        echo "<script>alert ('No se encontraron registros de espacios académicos para el bloque ".$_REQUEST['idBloque']."');</script>";
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=registroBloqueEstudiantes";
                        $variable.="&opcion=crear";
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                        $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                        $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                    }
          }else
                    {
                        echo "<script>alert ('No se encontraron registros de estudiantes inscritos para el bloque ".$_REQUEST['idBloque']."');</script>";
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=registroBloqueEstudiantes";
                        $variable.="&opcion=crear";
                        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                        $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                        $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                    }
    }
    
    
    function editarBloque()
    {
        $variables[0]=$_REQUEST['codProyecto'];
        $variables[2]=$_REQUEST['planEstudio'];
        $variables[3]=$_REQUEST['numeroBloque'];
        $variables[5]=$this->periodo[0][0];
        $variables[6]=$this->periodo[0][1];

        $cadena_sql_registroEstudiante=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"estudiantes_bloquesRegistrados", $variables);
        $resultado_registroEstudiante=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_registroEstudiante,"busqueda" );
        $variable[0]=$_REQUEST['codProyecto'];
        $variable[1]=$_REQUEST['planEstudio'];
        if($this->periodo[0][1]==3)
            {
                $periodoEst=2;
            }else
                {
                    $periodoEst=1;
                }
        $variable[2]=$this->periodo[0][0].$periodoEst;

        $cadena_sql_estudiantes=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"estudiantes_carrera",$variable);
        $resultado_estudiantes=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_estudiantes,"busqueda" );
        ?><table class='contenidotabla centrar' background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td colspan="4">
            <font size="2">
            SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA
            <br>
        <?echo $_REQUEST['nombreProyecto']?>
            <br>
            Plan de Estudio <?echo $_REQUEST['planEstudio']?>
            </font>
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
            <font size="2"><b>Estudiantes registrados en el bloque <?echo $variables[3]?></b></font>
            <hr noshade class="hr">

        </td>
    </tr>
    <tr>
        <td colspan="4" class="centrar">
            <?
            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $ruta="pagina=registroBloqueEstudiantes";
            $ruta.="&opcion=crear";
            $ruta.="&codProyecto=".$variable[0];
            $ruta.="&planEstudio=".$variable[1];
            $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
            ?>
            <a href="<?echo $pagina.$ruta?>">
                <img border="0" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/inicio.png" width="40" height="40"><br><font size="1">Inicio</font>
            </a>
        </td>
    </tr>
    <tr class="cuadro_brownOscuro centrar">
        <td>
            N&uacute;mero
        </td>
        <td>
            C&oacute;digo Estudiante
        </td>
        <td>
            Nombre Estudiante
        </td>

    </tr>
        <?
        $l=0;
        $k=1;
        for($i=0;$i<count($resultado_estudiantes);$i++) {
            for($j=0;$j<count($resultado_registroEstudiante);$j++) {
                if($resultado_estudiantes[$i][0]==$resultado_registroEstudiante[$j][3]) {
                    ?><tr class="cuadro_plano centrar">
        <td>
                    <?echo $k?>
        </td>
        <td>
                        <?echo $resultado_estudiantes[$i][0]?>
        </td>
        <td class="cuadro_plano">
                        <?echo $resultado_estudiantes[$i][1]?>
        </td>

    </tr>
                                <?$k++;$l++;
                    break;
                }
                        }

        }
        if($l==0)
            {
            ?>
    <tr>
        <td class="cuadro_plano" colspan="4">
            <font size="2" color="red">No hay estudiantes asociados a este bloque</font>
        </td>
    </tr>
            <?
            }
                    ?>
    <tr>
    <hr noshade class="hr">
    </tr>

            <?


            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variablesPag="pagina=registroBloqueEstudiantes";
            $variablesPag.="&opcion=crear";
            $variablesPag.="&codProyecto=".$_REQUEST['codProyecto'];
            $variablesPag.="&planEstudio=".$_REQUEST['planEstudio'];
            $variablesPag.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variablesPag=$this->cripto->codificar_url($variablesPag,$this->configuracion);

            ?>
    <tr class="centrar">
        <td colspan="3">
            <a href="<?= $pagina.$variablesPag ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/atras.png" width="35" height="35" border="0"><br>
                <font size="2"><b>Regresar<?echo (isset($resultado_bloquesRegistrados[$i][0])?$resultado_bloquesRegistrados[$i][0]:'')?></b></font>
            </a>
        </td>
    </tr>

</table>
        <?
    }

    function confirmarBorrar()
    {

        $estudiantesRegistrados=$_REQUEST['estudiantesRegistrados'];
        $espaciosRegistrados=$_REQUEST['espaciosRegistrados'];

        if($espaciosRegistrados>0)
            {
                echo "<script>alert('Para poder borrar el bloque se debe borrar el horario del bloque')</script>";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $ruta="pagina=registroBloqueEstudiantes";
                $ruta.="&opcion=crear";
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);

                echo "<script>location.replace('".$pagina.$ruta."')</script>";
                break;
            }else if($estudiantesRegistrados>0)
                    {
                        echo "<script>alert('Para poder borrar el bloque se deben borrar los estudiantes del bloque')</script>";
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=registroBloqueEstudiantes";
                        $ruta.="&opcion=crear";
                        $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                        $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                        $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);

                        echo "<script>location.replace('".$pagina.$ruta."')</script>";
                        break;
                    }
        ?>
<table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
    <tr class="texto_subtitulo">
        <td colspan="2">
            <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px">
                <tr class="texto_subtitulo">
                    <td  align="center">
                        Esta a punto de cancelar el bloque <?echo $_REQUEST['idBloque']?><br><br> ¿Est&aacute; seguro que desea cancelar el bloque <? echo $_REQUEST['idBloque'] ?>?
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                <input type="hidden" name="idBloque" value="<?echo $_REQUEST['idBloque']?>">
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
                <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
                <input type="hidden" name="opcion" value="borrarBloque">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" name="aceptar" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" width="30" height="30">
            </form>
        </td>
        <td align="center">
        <?
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variablesPag="pagina=registroBloqueEstudiantes";
        $variablesPag.="&opcion=crear";
        $variablesPag.="&codProyecto=".$_REQUEST['codProyecto'];
        $variablesPag.="&planEstudio=".$_REQUEST['planEstudio'];
        $variablesPag.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variablesPag=$this->cripto->codificar_url($variablesPag,$this->configuracion);

                    ?>
            <a href="<?= $pagina.$variablesPag ?>" >
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/x.png" width="35" height="35" border="0"><br>
            </a>
        </td>
    </tr>
</td>
</tr>

</table>
                    <?
    }

    function borrarBloque()
    {
        $variables=array($_REQUEST['idBloque'],$_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql_borrarBloque=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"borrar_bloque", $variables);
        $resultado_borrarBloque=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_borrarBloque,"" );

        $cadena_sql_borrarEstBloque=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"borrar_estudiantesBloque", $variables);
        $resultado_borrarEstBloque=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_borrarEstBloque,"" );

        $cadena_sql_borrarHorBloque=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"borrar_horarioBloque", $variables);
        $resultado_borrarHorBloque=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_borrarHorBloque,"" );

        echo "<script>alert ('Se han eliminado los registros del bloque ".$_REQUEST['idBloque']."');</script>";
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=registroBloqueEstudiantes";
        $variable.="&opcion=crear";
        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
        $variable.="&planEstudio=".$_REQUEST['planEstudio'];
        $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";


    }

    function horarioBloqueInd()
    {
        $variableConsulta=array($_REQUEST['idBloque'],$_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql_horarioBloque=$this->sql->cadena_sql($this->configuracion,$this->accesoGestion,"espacio_grupoBloque", $variableConsulta);
        $resultado_horarioBloque=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_horarioBloque,"busqueda" );
        ?>
<table  class="texto_subtitulo" align="center" border="0" background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
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
        <td colspan="2" class="centrar">
            <?        
            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $ruta="pagina=registroBloqueEstudiantes";
            $ruta.="&opcion=crear";
            $ruta.="&codProyecto=".(isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'');
            $ruta.="&planEstudio=".(isset($_REQUEST['planEstudio'])?$_REQUEST['planEstudio']:'');
            $ruta.="&nombreProyecto=".(isset($_REQUEST['nombreProyecto'])?$_REQUEST['nombreProyecto']:'');

            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
            ?>
            <a href="<?echo $pagina.$ruta?>">
                <img border="0" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/inicio.png" width="40" height="40"><br><font size="1">Inicio</font>
            </a>
        </td>
    </tr>

        <?
        if($resultado_horarioBloque==NULL) {
            ?>
    <tr>
        <td>
            <table class='contenidotabla'>
                <thead class='cuadro_color'>
                    No existen espacios acad&eacute;micos adicionados para este bloque
                </thead>
            <?
        }else {
                ?>

                <tr>
                    <td>
                        <table class='contenidotabla'>
                            <thead class='cuadro_color'>
                            <td class='cuadro_plano centrar'>Cod.</td>
                            <td class='cuadro_plano centrar' width="150">Nombre Espacio<br>Acad&eacute;mico </td>
                            <td class='cuadro_plano centrar' width="25">Grupo </td>
                            <td class='cuadro_plano centrar' width="60">Lun </td>
                            <td class='cuadro_plano centrar' width="60">Mar </td>
                            <td class='cuadro_plano centrar' width="60">Mie </td>
                            <td class='cuadro_plano centrar' width="60">Jue </td>
                            <td class='cuadro_plano centrar' width="60">Vie </td>
                            <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                            <td class='cuadro_plano centrar' width="60">Dom </td>

                            </thead>

            <?


            //recorre cada uno del los grupos
            for($j=0;$j<count($resultado_horarioBloque);$j++) {

                $variables[0]=$resultado_horarioBloque[$j]['ID_ESPACIO'];  //idEspacio
                $variables[1]=$resultado_horarioBloque[$j]['ID_GRUPO'];  //ID_grupo
                $variables[2]=$this->periodo[0][0];  //ano
                $variables[3]=$this->periodo[0][1];  //per


                            //busca el horario de cada grupo, pasar codigo del EA, codigo de carrera y grupo
                            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle,"horario_grupos_registrados",$variables);
                            $resultado_horarios=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                                            ?>
                            <tr>
                                <td class='cuadro_plano centrar'><?echo $resultado_horarioBloque[$j]['ID_ESPACIO'];?></td>
                                <td class='cuadro_plano'><?echo $resultado_horarioBloque[$j]['ESPACIO'];?></td>
                                <td class='cuadro_plano centrar'><?echo $resultado_horarios[0]['GRUPO'];?></td>
                                            <?

                                            //recorre el numero de dias del la semana 1-7 (lunes-domingo)
                                            for($i=1; $i<8; $i++) {
                                                ?><td class='cuadro_plano centrar'><?

                                                //Recorre el arreglo del resultado de los horarios
                                                for ($k = 0; $k < count($resultado_horarios); $k++) {

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


                            </tr>
                                                    <?}
                                            }


                                            ?>
                        </table>
                    </td>

                </tr>

            </table>
                                    <?
                                }

            function fechaTerminada($variable)
                {
                    ?>
            <table class="contenidotabla">
                <tr align="center">
                    <td colspan="2">
                        SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA
                        <br>
                    <?echo $variable[1]?>
                        <br>
                        Plan de Estudio <?echo $variable[2]?>

                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="centrar">
                       Las fechas para la inscripci&oacute;n de estudiantes admitidos ha terminado
                    </td>
                </tr>
            </table>
                    <?
                }
                
    function enlaceRegresarPrincipal(){
             ?>
            <table width="100%">       
                <tr>
                    <td class="centrar" width="50%">
                        <br>
                                <?
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                $ruta="pagina=registroBloqueEstudiantes";
                                $ruta.="&opcion=verProyectos";
                                
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);

                                ?>
                        <a href="<?= $pagina.$ruta ?>">
                            <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/atras.png" width="30" height="30" border="0"><br><font size="1">Regresar </font>
                        </a>
                    </td>
                </tr>
            </table> 
            <?
    }                

}


?>
