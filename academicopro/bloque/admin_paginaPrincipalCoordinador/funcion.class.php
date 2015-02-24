
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
class funcion_admin_paginaPrincipalCoordinador extends funcionGeneral {


    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_admin_paginaPrincipalCoordinador();
        $this->log_us= new log();
        $this->formulario="admin_paginaPrincipalCoordinador";

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


    function proceso_inscripcion($configuracion)
    {
        $cedula = $this->usuario;
        $cadena_sql=$this->sql->cadena_sql($configuracion, "datos_coordinador", $cedula);//echo $cadena_sql;exit;
        $resultado_datos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        $indice=$configuracion['host'].$configuracion['site']."/index.php?";
        

if(is_array($resultado_datos))
    {
    ?>
    <table width="50%" class="sigma" align="center" border="0">
        <caption class="sigma">PROYECTOS RELACIONADOS</caption>
        <tr>
            <th class="sigma">Cod Proyecto</th>
            <th class="sigma">Nombre Proyecto</th>
        </tr>
    <?
        for($i=0;$i<count($resultado_datos);$i++)
        {
            if($i%2==0)
                {
                    $classtr="sigma";
                }else
                    {
                        $classtr="sigma_a";
                    }
            ?>
            <tr class="<?echo $classtr?>">
                <td class="sigma" align="center">
                            <?echo $resultado_datos[$i][0]?>
                </td>
                <td class="sigma">
                            <?echo $resultado_datos[$i][1]?>
                </td>
            </tr>
            <?
        }
    ?>
    </table>

<table width="100%" class="sigma" border="0">
  <BR><hr><h3 style="text-align:center;color:red">ILUD - Segunda Lengua</h3>
  <tr>
    <td align="center">
      Instructivo para asociar Segunda Lengua<br>al plan de estudios.
    </td>
    <td align="center">
      Instructivo para registrar requisitos<br>en el plan de estudios.
    </td>
  </tr>
  <tr>
    <td align="center">
      <a target="_blank" href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/segunda_lengua_ILUD/SegundaLengua.htm">Ver Manual <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/folder_video.png" width="30" border=0></a>
      <br>
    </td>
    <td align="center">
      <a target="_blank" href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/requisitos/requisitos.htm">Ver Manual <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/folder_video.png" width="30" border=0></a>
      <br>
    </td>
  </tr>
  <tr>
      <td align="center" colspan="2"><hr>
        Distribuci&oacute;n de grupos para Espacios Acad&eacute;micos comunes.
      </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
        <a href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/Espacios_Comunes_Distribucion_de_ Codigos.pdf">Ver documento <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" width="30" border=0></a>
      </td>
  </tr>
</table>
    <?
    }
    if(isset($_REQUEST['carrera']))
        {
        ?>
    <table width="80%" align="center" border="0">
        <tr>

        <?
        /*
         * Verificar si estan habilitadas las fechas para la creacion de horarios
         * Si existe datos para la carrera del coordinador muestra la imagen de horarios habilitado, de lo contrario muestra la
         * imagen deshabilitada
         */
            $cadena_sql=$this->sql->cadena_sql($configuracion, "fechas_horarios", $_REQUEST['carrera']);//echo $cadena_sql;exit;
            $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(is_array($resultado_horarios))
                {
                    $evento=$this->validar_fechas($configuracion, $resultado_horarios);
                    ?>
                    <td width="25%" align="center">
                        <a>
                            <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/pagina_inicial/<?echo $evento?>.png" width="60" heigth="60" onmouseover="xajax_mensaje('<?echo $evento?>')" onmouseout="xajax_mensaje('')">
                            <br><font size="1">Paso 1<br>Horarios</font>
                        </a>
                    </td>
                    <?
                }else
                    {
                         ?>
                        <td width="25%" align="center">
                            <a>
                                <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/pagina_inicial/horario_des.png" width="60" heigth="60" onmouseover="xajax_mensaje('horario_des')" onmouseout="xajax_mensaje('')">
                                <br><font size="1">Paso 1<br>Horarios</font>
                            </a>
                        </td>
                        <?
                    }
                    ?>
                        <td>
                             <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/forward.png">
                        </td>
                    <?
            /*
             * Verificar si estan habilitadas las fechas para la preinscripcion
             * Si existe datos para la carrera del coordinador muestra la imagen de horarios habilitado, de lo contrario muestra la
             * imagen deshabilitada
             */
            $cadena_sql=$this->sql->cadena_sql($configuracion, "fechas_preinscripcion", $_REQUEST['carrera']);//echo $cadena_sql;exit;
            $resultado_preinscripcion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(is_array($resultado_preinscripcion))
                {
                    $evento=$this->validar_fechas($configuracion, $resultado_preinscripcion);
                    ?>
                    <td width="25%" align="center">
                        <a>
                            <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/pagina_inicial/<?echo $evento?>.png" width="60" heigth="60" onmouseover="xajax_mensaje('<?echo $evento?>')" onmouseout="xajax_mensaje('')">
                            <br><font size="1">Paso 2<br>Preinscripci&oacute;n</font>
                        </a>
                    </td>
                    <?
                }else
                    {
                    ?>
                    <td width="25%" align="center">
                        <a>
                            <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/pagina_inicial/preinscripcion_des.png" width="60" heigth="60" onmouseover="xajax_mensaje('preinscripcion_des')" onmouseout="xajax_mensaje('')">
                            <br><font size="1">Paso 2<br>Preinscripci&oacute;n</font>
                        </a>
                    </td>
                    <?
                    }
                    ?>
                        <td>
                             <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/forward.png">
                        </td>
                    <?
            /*
             * Verificar si estan habilitadas las fechas para el proceso de inscripcion de espacios academicos para estudiantes nuevos
             */
            $cadena_sql=$this->sql->cadena_sql($configuracion, "fechas_inscripcion", $_REQUEST['carrera']);//echo $cadena_sql;exit;
            $resultado_nuevos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(is_array($resultado_nuevos))
                {
                    $evento=$this->validar_fechas($configuracion, $resultado_nuevos);
                    if($evento=='inscripcion_hab')
                    {
                      $evento="grupoNuevo_hab";
                    }
                    else
                    {
                      $evento="grupoNuevo_des";
                    }
                    ?>
                    <td width="25%" align="center">
                        <?
                        //Adiciones y cancelaciones coordinadores
                        $indice=$configuracion['host'].$configuracion['site']."/index.php?";
                        $variable="pagina=registroBloqueEstudiantes";
                        $variable.="&opcion=verProyectos";
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        $enlaceAcademicoNuevos=$indice.$variable;
                        ?>
                        <a href="<?=$enlaceAcademicoNuevos?>">
                            <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/pagina_inicial/<?echo $evento?>.png" width="60" heigth="60" border="0" onmouseover="xajax_mensaje('<?echo $evento?>')" onmouseout="xajax_mensaje('')">
                            <br><font size="1">Paso 3<br>Inscripci&oacute;n Nuevos</font>
                        </a>
                    </td>
                    <?
                }else
                    {
                    ?>
                    <td width="25%" align="center">
                        <?
                        //Adiciones y cancelaciones coordinadores
                        $indice=$configuracion['host'].$configuracion['site']."/index.php?";
                        $variable="pagina=registroBloqueEstudiantes";
                        $variable.="&opcion=verProyectos";
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        $enlaceAcademicoNuevos=$indice.$variable;
                        ?>
                        <a href="<?=$enlaceAcademicoNuevos?>">
                            <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/pagina_inicial/grupoNuevo_des.png" width="60" heigth="60" border="0" onmouseover="xajax_mensaje('grupoNuevo_des')" onmouseout="xajax_mensaje('')">
                            <br><font size="1">Paso 3<br>Inscripci&oacute;n Nuevos</font>
                        </a>
                    </td>
                    <?
                    }
                    ?>
                        <td>
                             <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/forward.png">
                        </td>
                    <?

            /*
             * Verificar si estan habilitadas las fechas para el proceso de adiciones y cancelaciones
             */
            $cadena_sql=$this->sql->cadena_sql($configuracion, "fechas_inscripcion", $_REQUEST['carrera']);//echo $cadena_sql;exit;
            $resultado_inscripcion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(is_array($resultado_inscripcion))
                {
                    $evento=$this->validar_fechas($configuracion, $resultado_inscripcion);
                    ?>
                    <td width="25%" align="center">
                        <?
                        //Adiciones y cancelaciones coordinadores
                        $indice=$configuracion['host'].$configuracion['site']."/index.php?";
                        $variable="pagina=adminInscripcionCoordinador";
                        $variable.="&opcion=verProyectos";
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        $enlaceAcademicoAdiciones=$indice.$variable;
                        ?>
                        <a href="<?echo $enlaceAcademicoAdiciones?>">
                            <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/pagina_inicial/<?echo $evento?>.png" width="60" heigth="60" border="0" onmouseover="xajax_mensaje('<?echo $evento?>')" onmouseout="xajax_mensaje('')">
                            <br><font size="1">Paso 4<br>Adici&oacute;n y Cancelaci&oacute;n</font>
                        </a>
                    </td>
                    <?
                }else
                    {
                    ?>
                    <td width="25%" align="center">
                        <?
                        //Adiciones y cancelaciones coordinadores
                        $indice=$configuracion['host'].$configuracion['site']."/index.php?";
                        $variable="pagina=adminInscripcionCoordinador";
                        $variable.="&opcion=verProyectos";
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        $enlaceAcademicoAdiciones=$indice.$variable;
                        ?>
                        <a href="<?echo $enlaceAcademicoAdiciones?>">
                            <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/pagina_inicial/inscripcion_des.png" width="60" heigth="60" border="0" onmouseover="xajax_mensaje('inscripcion_des')" onmouseout="xajax_mensaje('')">
                            <br><font size="1">Paso 4<br>Adici&oacute;n y Cancelaci&oacute;n</font>
                        </a>
                    </td>
                    <?
                    }
                    
                ?>
        </tr>
        <tr>
            <td colspan="7">
                <table width="70%" align="center" border="0">
                    <tr>
                        <td align="center">
                            <div id="mensajeProceso">
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
<table width="80%" align="center">
    <tr>
        <td>

        </td>
        <td>
            <!--
            Tabla que contiene los archivos importantes del sistema de créditos
            -->
            <table class="contenidotabla centrar" border="0">
                <caption class="sigma">INFORMACI&Oacute;N IMPORTANTE</caption>
                <tr>
                    <th class="sigma centrar">
                        Descripcion
                    </th>
                    <th class="sigma centrar">
                        Descargar
                    </th>
                </tr>
                <tr>
                    <td>DIRECTRICES PARA EL PROCESO DE ADICIONES Y CANCELACIONES</td>
                    <td align="center"><a href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/pagina_inicio20103.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" width="30" border=0></a></td>
                </tr>
                <tr>
                    <td>MANUAL DE USUARIO DEL SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</td>
                    <td align="center"><a href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/GUIA PROCEDIMIENTOS CREDITOS.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" width="30" border=0></a></td>
                </tr>
                <tr>
                    <td>DISTRIBUCI&Oacute;N DE C&Oacute;DIGOS PARA ESPACIOS ACAD&Eacute;MICOS COMUNES</td>
                    <td align="center"><a href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/Espacios_Comunes_Distribucion_de_ Codigos.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" width="30" border=0></a></td>
                </tr>
                <tr>
                    <td>ACUERDO 009 DE 2006</td>
                    <td align="center"><a href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/acu_2006-009.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" width="30" border=0></a></td>
                </tr>
                <tr>
                    <td>RESOLUCI&Oacute;N 035 DE 2006</td>
                    <td align="center"><a href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/Resolucion_035_de_2006.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" width="30" border=0></a></td>
                </tr>
                <tr>
                    <td>QUE SON CR&Eacute;DITOS ACAD&Eacute;MICOS</td>
                    <td align="center"><a href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/QUE_SON_CREDITOS_ACADEMICOS.tif"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" width="30" border=0></a></td>
                </tr>
                <tr>
                    <td>RESOLUCI&Oacute;N 026 DE 2009</td>
                    <td align="center"><a href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/Resolucion_026.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" width="30" border=0></a></td>
                </tr>
                <tr>
                    <td>ACUERDO 007 DE 2009</td>
                    <td align="center"><a href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/acu_2009-007.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" width="30" border=0></a></td>
                </tr>
                <tr>
                    <td>ACUERDO 001 DE 2010</td>
                    <td align="center"><a href="<?echo $configuracion["host"].$configuracion["site"]?>/documentos/Acuerdo_01de 2010.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" width="30" border=0></a></td>
                </tr>
                <tr align="center" border="1">
                <td  align="center" colspan="2">
                        <font size=2>Linea de atenci&oacute;n Sistema Cr&eacute;ditos<br>Oficina Asesora de Sistemas<br>Tel:3238400 ext:1109</font><br>
                </td>
                </tr>
            </table>
        </td>
        <td>
            
        </td>
    </tr>
</table>
    
                <?
        }

    }

    function validar_fechas($configuracion,$resultado_fechas)
        {
            for($i=0;$i<count($resultado_fechas);$i++)
                {
                    if($evento=='')
                        {
                            switch ($resultado_fechas[$i][0]) {
                                case '2':
                                        $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                        $final = $resultado_fechas[$i][3]-date('YmdHis');
                                        if(($inicio>=0)&&($final<=0))
                                               {
                                                    $evento='horario_hab';
                                                    break;
                                              }else if(($inicio<=0)&&($final>=0))
                                               {
                                                    $evento='horario_hab';
                                                    break;
                                               }else
                                                   {
                                                    $evento='horario_des';
                                                   }
                                    break;

                                case '8':
                                        $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                        $final = $resultado_fechas[$i][3]-date('YmdHis');
                                        if(($inicio>=0)&&($final<=0))
                                               {
                                                    $evento='inscripcion_hab';
                                                    break;
                                              }else if(($inicio<=0)&&($final>=0))
                                               {
                                                    $evento='inscripcion_hab';
                                                    break;
                                               }else
                                                   {
                                                    $evento='inscripcion_des';
                                                   }
                                    break;

                                case '9':
                                        $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                        $final = $resultado_fechas[$i][3]-date('YmdHis');
                                        if(($inicio>=0)&&($final<=0))
                                               {
                                                    $evento='inscripcion_hab';
                                                    break;
                                              }else if(($inicio<=0)&&($final>=0))
                                               {
                                                    $evento='inscripcion_hab';
                                                    break;
                                               }else
                                                   {
                                                    $evento='inscripcion_des';
                                                   }
                                    break;

                                case '15':
                                        $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                        $final = $resultado_fechas[$i][3]-date('YmdHis');
                                        if(($inicio>=0)&&($final<=0))
                                               {
                                                    $evento='preinscripcion_hab';
                                                    break;
                                              }else if(($inicio<=0)&&($final>=0))
                                               {
                                                    $evento='preinscripcion_hab';
                                                    break;
                                               }else
                                                   {
                                                    $evento='preinscripcion_des';
                                                   }
                                    break;

                                default:
                                                    $evento='consulta';
                                    break;
                            }
                        }
                }
                return $evento;
        }
}
?>
