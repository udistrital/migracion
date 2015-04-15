
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
class funcion_registroAsociarEstudianteConsejeria extends funcionGeneral {


    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_registroAsociarEstudianteConsejeria();
        $this->log_us= new log();
        $this->formulario="registroAsociarEstudianteConsejeria";

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


    function consultarEstudianteCohorte($configuracion)
    {
        $this->encabezadoModulo($configuracion);

        if($_REQUEST['codProyecto'] && $_REQUEST['planEstudio'])
                {
                    $codProyecto=$_REQUEST['codProyecto'];
                    $planEstudio=$_REQUEST['planEstudio'];
                    $nombreProyecto=$_REQUEST['nombreProyecto'];
                    $codDocente=$_REQUEST['codDocente'];
                    $nombreDocente=$_REQUEST['nombreDocente'];
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio,$codDocente,$nombreDocente);
                }

            else{

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$this->usuario);
                    $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $planEstudio=$resultado_datosCoordinador[0][2];
                    $codProyecto=$resultado_datosCoordinador[0][0];
                    $nombreProyecto=$resultado_datosCoordinador[0][1];
                    $codDocente=$_REQUEST['codDocente'];
                    $nombreDocente=$_REQUEST['nombreDocente'];
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio,$codDocente,$nombreDocente);
                }

     $this->menuAdministracion($configuracion,$variable);

     $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantes_asociados",$variable);
     $resultado_estudiantesAsociados=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

     if(is_array($resultado_estudiantesAsociados))
         {

         ?>
<table class="contenidotabla centrar">
    <caption class="sigma centrar" >
            ESTUDIANTES ASOCIADOS AL DOCENTE <?echo $variable[4]?>
    </caption>
    <tr>
        <th class="sigma centrar">
            Nro
        </th>
        <th class="sigma centrar">
            C&oacute;digo
        </th>
        <th class="sigma centrar">
            Nombre
        </th>
        <th class="sigma centrar">
            Estado
        </th>
    </tr>
                <?
                $p=0;
            for($i=0;$i<count($resultado_estudiantesAsociados);$i++)
            {
                $variable[5]=$resultado_estudiantesAsociados[$i][0];
                $cadena_sql=$this->sql->cadena_sql($configuracion,"consultarDatosEstudiante",$variable);
                $resultado_datosEstudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                if($i%2==0)
                    {
                        $clasetr="";
                    }else
                        {
                            $clasetr="sigma";
                        }

                ?>
    <tr class="<?echo $clasetr?>">
        <td class="cuadro_plano centrar">
            <?echo ++$p?>
        </td>
        <td class="cuadro_plano centrar">
            <?echo $resultado_estudiantesAsociados[$i][2]?>
        </td>
        <td class="cuadro_plano">
            <?echo $resultado_estudiantesAsociados[$i][3]?>
        </td>
        <td class="cuadro_plano">
            <?echo $resultado_estudiantesAsociados[$i][5]?>
        </td>
    </tr>
                <?
            }
            ?>
 </table>
 <?
         }else
             {
             ?>
             <table class="contenidotabla centrar">
                <tr>
                    <td class="sigma_a centrar">
                        EL DOCENTE <?echo $variable[4]?> NO TIENE ESTUDIANTES ASOCIADOS PARA CONSEJERIAS
                    </td>
                </tr>
             </table>
             <?
             }
    }
    

    function menuAdministracion($configuracion,$variable) {

         ?>
<table class="contenidotabla centrar" width="100%" >
    <tr>
        <td class="centrar" colspan="2">
            <a href="javascript:history.back()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/back.png" width="30" height="30" border="0" alt="Regresar"><br>Regresar
            </a>
        </td>
        <td class="centrar" colspan="2" width="33%">
            <?
            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=registroConsejeria";
            $ruta.="&opcion=ver";
            $ruta.="&codProyecto=".$variable[0];
            $ruta.="&planEstudio=".$variable[2];
            $ruta.="&nombreProyecto=".$variable[1];
            $ruta.="&codDocente=".$variable[3];
            $ruta.="&nombreDocente=".$variable[4];

            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>


            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="30" height="30" border="0" alt="Ver plan de estudio">
                <br>Inicio
            </a>
        </td>
        <td class="centrar" colspan="2">
            <a href="javascript:history.forward()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/forward.png" width="30" height="30" border="0" alt="Regresar"><br>Continuar
            </a>
        </td>
    </tr>
    <tr class="centrar">
        
        <td class="centrar" colspan="3"  width="33%">

            <?
            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=registroAsociarEstudianteConsejeria";
            $ruta.="&opcion=asociarNuevos";
            $ruta.="&codProyecto=".$variable[0];
            $ruta.="&planEstudio=".$variable[2];
            $ruta.="&nombreProyecto=".$variable[1];
            $ruta.="&codDocente=".$variable[3];
            $ruta.="&nombreDocente=".$variable[4];

            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>


            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/asociar.png" width="38" height="38" border="0" alt="Ver plan de estudio">
                <br>Asociar<br>Estudiantes
            </a>
        </td>
        <td class="centrar" colspan="3" width="33%">
            <?
            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=registroAsociarEstudianteConsejeria";
            $ruta.="&opcion=desasociarEst";
            $ruta.="&codProyecto=".$variable[0];
            $ruta.="&planEstudio=".$variable[2];
            $ruta.="&nombreProyecto=".$variable[1];
            $ruta.="&codDocente=".$variable[3];
            $ruta.="&nombreDocente=".$variable[4];

            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>


            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/desasociar.png" width="38" height="38" border="0" alt="Ver plan de estudio">
                <br>Desasociar<br>Estudiantes
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


    function asociarEstudiantesCohorte($configuracion)
    {
        $this->encabezadoModulo($configuracion);
        if($_REQUEST['codProyecto'] && $_REQUEST['planEstudio'])
                {
                    $codProyecto=$_REQUEST['codProyecto'];
                    $planEstudio=$_REQUEST['planEstudio'];
                    $nombreProyecto=$_REQUEST['nombreProyecto'];
                    $codDocente=$_REQUEST['codDocente'];
                    $nombreDocente=$_REQUEST['nombreDocente'];
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio,$codDocente,$nombreDocente);
                }

            else{

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$this->usuario);
                    $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $planEstudio=$resultado_datosCoordinador[0][2];
                    $codProyecto=$resultado_datosCoordinador[0][0];
                    $nombreProyecto=$resultado_datosCoordinador[0][1];
                    $codDocente=$_REQUEST['codDocente'];
                    $nombreDocente=$_REQUEST['nombreDocente'];
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio,$codDocente,$nombreDocente);
                }

        $this->menuAdministracion($configuracion,$variable);
        
            ?><script language="javascript" type="text/javascript">
                        function SelectAllCheckBox(chkbox,FormId){
                          for (var i=0;i < document.forms[FormId].elements.length;i++)
                            {
                              var Element = document.forms[FormId].elements[i];
                              if (Element.type == "checkbox")
                              Element.checked = chkbox.checked;
                            }
                          }
                        </script>

<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>' Id='checBox'>
<table class="contenidotabla centrar">
          <caption class="sigma centrar">
                Seleccione el periodo para buscar los estudiantes
          </caption>
          <tr class="cuadro_plano">
            <td class="centrar">
                <select id="annio" onchange="xajax_estudiantesConsejerias(document.getElementById('annio').value,document.getElementById('periodo').value,'<?echo $codProyecto?>')">
                    <option value="0">Seleccione el A&ntilde;o</option>
                    <option value="2000">2000 y anteriores</option>
                    <?
                    for($i=2001;$i<=date('Y');$i++)
                    {
                        echo "<option value='".$i."'>".$i."</option>";
                    }
                    ?>
                </select>
                <select id="periodo" onchange="xajax_estudiantesConsejerias(document.getElementById('annio').value,document.getElementById('periodo').value,'<?echo $codProyecto?>')">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </td>
          </tr>
          <tr>
              <td>
                  <div id="div_estudiantesConsejerias">

                  </div>
              </td>
          </tr>
          <input type="hidden" name="opcion" value="guardarAsociacion">
          <input type="hidden" name="action" value="<?echo $this->formulario?>">
          <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
          <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
          <input type="hidden" name="codDocente" value="<?echo $codDocente?>">
          <input type="hidden" name="nombreDocente" value="<?echo $nombreDocente?>">
          <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">

</table>
</form>
    <?
    }

    function guardarAsociacionEstudiante($configuracion)
    {
        if($_REQUEST['totalSeleccionados']<=0)
            {
                echo "<script>alert('Seleccione los estudiantes que seran aconsejados')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroAsociarEstudianteConsejeria";
                $ruta.="&opcion=asociar";
                $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
                $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
                $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                $ruta.="&codDocente=".$_REQUEST["codDocente"];
                $ruta.="&nombreDocente=".$_REQUEST["nombreDocente"];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                echo "<script>location.replace('".$pagina.$ruta."')</script>";
                exit;
            }

        $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActual",'');
        $periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $annio=$periodo[0][0];
        $perAct=$periodo[0][1];

         for($i=0;$i<$_REQUEST['totalSeleccionados'];$i++)
        {
            $variablesRegistro=array($_REQUEST['estudiante'.$i],$_REQUEST['codDocente'],$_REQUEST["codProyecto"],date('Y-m-d'),'A');

            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarEstudianteAconsejado",$variablesRegistro);
            $resultado_yaExiste=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            if(is_array($resultado_yaExiste))
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarEstadoRelacion",$variablesRegistro);
                    $resultado_relacion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                }else
                    {
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"registrarRelacion",$variablesRegistro);
                        $resultado_relacion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                    }
            
            if($resultado_relacion==true)
                {
                    $variablesLog=array($this->usuario,date('YmdGis'),'41','Registro Relacion Docente Estudiante',$_REQUEST['codDocente']." - ".$_REQUEST['estudiante'.$i]." - ".$annio,$_REQUEST['codProyecto']);

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"registroEvento",$variablesLog);
                    $resultado_evento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );
                }

        }
        
        echo "<script>alert('Se han registrado nuevas relaciones de docentes y estudiantes para consejerias')</script>";
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroAsociarEstudianteConsejeria";
        $ruta.="&opcion=asociar";
        $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
        $ruta.="&codDocente=".$_REQUEST["codDocente"];
        $ruta.="&nombreDocente=".$_REQUEST["nombreDocente"];

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $ruta=$this->cripto->codificar_url($ruta,$configuracion);

        echo "<script>location.replace('".$pagina.$ruta."')</script>";
    }

    function desasociarEstudiantes($configuracion)
    {
        $this->encabezadoModulo($configuracion);

        if($_REQUEST['codProyecto'] && $_REQUEST['planEstudio'])
                {
                    $codProyecto=$_REQUEST['codProyecto'];
                    $planEstudio=$_REQUEST['planEstudio'];
                    $nombreProyecto=$_REQUEST['nombreProyecto'];
                    $codDocente=$_REQUEST['codDocente'];
                    $nombreDocente=$_REQUEST['nombreDocente'];
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio,$codDocente,$nombreDocente);
                }

            else{

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$this->usuario);
                    $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $planEstudio=$resultado_datosCoordinador[0][2];
                    $codProyecto=$resultado_datosCoordinador[0][0];
                    $nombreProyecto=$resultado_datosCoordinador[0][1];
                    $codDocente=$_REQUEST['codDocente'];
                    $nombreDocente=$_REQUEST['nombreDocente'];
                    $variable=array($codProyecto,$nombreProyecto,$planEstudio,$codDocente,$nombreDocente);
                }

     $this->menuAdministracion($configuracion,$variable);

     $cadena_sql=$this->sql->cadena_sql($configuracion,"estudiantes_asociados",$variable);
     $resultado_estudiantesAsociados=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

     if(is_array($resultado_estudiantesAsociados))
         {

         ?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
<table class="contenidotabla centrar">
    <caption class="sigma centrar">
            ESTUDIANTES ASOCIADOS AL DOCENTE <?echo $variable[4]?>
    </caption>
    <tr>
        <th class="sigma centrar">
            Nro
        </th>
        <th class="sigma centrar">
            C&oacute;digo
        </th>
        <th class="sigma centrar">
            Nombre
        </th>
        <th class="sigma centrar">
            Estado
        </th>
        <th class="sigma centrar">
            Seleccionar
        </th>
    </tr>
                <?
                $p=0;
            for($i=0;$i<count($resultado_estudiantesAsociados);$i++)
            {
                $variable[5]=$resultado_estudiantesAsociados[$i][0];
//                $cadena_sql=$this->sql->cadena_sql($configuracion,"consultarDatosEstudiante",$variable);//echo"1". $cadena_sql;exit;
//                $resultado_datosEstudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
if($i%2==0)
    {
        $clasetr="";
    }else
        {
            $clasetr="sigma";
        }
                ?>
    <tr class="<?echo $clasetr?>">
        <td class="cuadro_plano centrar">
            <?echo ++$p;?>
        </td>
        <td class="cuadro_plano centrar">
            <?echo $resultado_estudiantesAsociados[$i][2]?>
        </td>
        <td class="cuadro_plano">
            <?echo $resultado_estudiantesAsociados[$i][3]?>
        </td>
        <td class="cuadro_plano">
            <?echo $resultado_estudiantesAsociados[$i][5]?>
        </td>
        <td class="cuadro_plano centrar">
            <input type="checkbox" name="estudiante<?echo $i?>" value="<?echo $resultado_estudiantesAsociados[$i][2]?>">
        </td>
    </tr>
                <?
            }
            ?>
          <input type="hidden" name="opcion" value="borrarAsociacion">
          <input type="hidden" name="action" value="<?echo $this->formulario?>">
          <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
          <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
          <input type="hidden" name="codDocente" value="<?echo $codDocente?>">
          <input type="hidden" name="nombreDocente" value="<?echo $nombreDocente?>">
          <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
          <tr class="cuadro_plano centrar">
              <td class="centrar" colspan="6">
                  <input type="submit" name="desasociar" value="Desasociar">
              </td>
          </tr>
 </table>
</form>
 <?
         }else
             {
             ?>
             <table class="contenidotabla centrar">
                <tr>
                    <td class="cuadro_brownOscuro centrar">
                        EL DOCENTE <?echo $variable[4]?> NO TIENE ESTUDIANTES ASOCIADOS PARA CONSEJERIAS
                    </td>
                </tr>
             </table>
             <?
             }
    }

    function borrarAsociacionEstudiante($configuracion)
    {
        if($_REQUEST['totalSeleccionados']<=0)
            {
                echo "<script>alert('Seleccione los estudiantes que seran desasociados')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroAsociarEstudianteConsejeria";
                $ruta.="&opcion=asociar";
                $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
                $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
                $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                $ruta.="&codDocente=".$_REQUEST["codDocente"];
                $ruta.="&nombreDocente=".$_REQUEST["nombreDocente"];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                echo "<script>location.replace('".$pagina.$ruta."')</script>";
                exit;
            }

        $cadena_sql=$this->sql->cadena_sql($configuracion,"periodoActual",'');
        $periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        
        $annio=$periodo[0][0];
        $perAct=$periodo[0][1];

         for($i=0;$i<$_REQUEST['totalSeleccionados'];$i++)
        {
            $variablesRegistro=array($_REQUEST['estudiante'.$i],$_REQUEST['codDocente'],$_REQUEST["codProyecto"],date('Ymd'),'I');
            $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarEstadoRelacion",$variablesRegistro);
            $resultado_relacion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");

            if($resultado_relacion==true)
                {
                    $variablesLog=array($this->usuario,date('YmdGis'),'42','Borro Relacion Docente Estudiante',$_REQUEST['codDocente']." - ".$_REQUEST['estudiante'.$i]." - ".$annio."-".$perAct." - ".$_REQUEST['codProyecto'],$_REQUEST['estudiante'.$i]);

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"registroEvento",$variablesLog);
                    $resultado_evento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );
                }

        }

        echo "<script>alert('Se han desasociado las relaciones de docentes y estudiantes seleccionados')</script>";
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroAsociarEstudianteConsejeria";
        $ruta.="&opcion=asociar";
        $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
        $ruta.="&codDocente=".$_REQUEST["codDocente"];
        $ruta.="&nombreDocente=".$_REQUEST["nombreDocente"];

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $ruta=$this->cripto->codificar_url($ruta,$configuracion);

        echo "<script>location.replace('".$pagina.$ruta."')</script>";
    }


}
?>
