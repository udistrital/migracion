
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
class funcion_admin_consejeriasEstudiante extends funcionGeneral {


    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_admin_consejeriasEstudiante();
        $this->log_us= new log();
        $this->formulario="admin_consejeriasEstudiante";

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


    function datosEstudianteConsejerias($configuracion)
    {
        $codEstudiante=$this->usuario;
        $nivelDocente=$this->nivel;

//var_dump($_REQUEST);exit;
            $cadena_sql=$this->sql->cadena_sql($configuracion,"docente_asociado",$codEstudiante);//echo $cadena_sql;exit;
            $resultado_docente=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_docente",$resultado_docente[0][1]);//echo $cadena_sql;exit;
            $resultado_nombreDocente=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

     if(is_array($resultado_docente))
         {
exit;
            $codDocente=$resultado_docente[0][1];

            $html="<div class='pestanas'>";
            $html.="<ul>";
//            $html.="<li id='pestana1' class='pestanainactiva a'>";
//            $html.="<a id='pestanalink1' class='link' onclick='xajax_comunicacion(".$codEstudiante.", ".$codDocente.");'>";
//            $html.="<font size='1'><b>Comunicación</b></font></a></li>";
            //$html.="<li id='pestana2' class='pestanainactiva a'>";
            //$html.="<a id='pestanalink2' class='link' onclick='xajax_otros(".$codEstudiante.", ".$codDocente.");'>";
            //$html.="<font size='1'><b>Otras Actividades</b></font></a></li>";
            $html.="</ul>";
            $html.="</div>";
            $html.="<div id='cuerpopestanas' class='cuerpopestanas'>";
            $html.="<table class='contenidotabla'>";

            $html.="<tr>
                       <td class='centrar' colspan='6'>
                           <b>ADMINISTRACI&Oacute;N DE CONSEJERIAS<BR>UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS
                           <hr noshade class='hr'></b>
                       </td>
                   </tr>
                     ";
            $cadena_sql=$this->sql->cadena_sql($configuracion,"datosEstudiante",$codEstudiante);//echo $cadena_sql;exit;
            $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            //var_dump($resultado_estudiante);exit;
            if(is_array($resultado_estudiante))
                {
                    //echo $consulta;exit;
                    $datos=array($codDocente, $codEstudiante);
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"mensajes",$datos);//echo $cadena_sql;exit;
                    $resultado_mensaje=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
                    //var_dump($resultado_mensaje);exit;
//nuevo mensaje
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"cuenta_nuevos",$datos);//echo $cadena_sql;exit;
                    $resultado_cuenta=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
if($resultado_cuenta[0][0]>0)
{
                    //echo $nuevo;exit;
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"mensajes_nuevos",$datos);//echo $cadena_sql;exit;
                    $resultado_nuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
                    //echo count($resultado_nuevo); exit;

    $html.="
<tr>
    <td>
        <div id='div_nuevo'>
            <table class='contenidotabla' centrar>
                <tr>
                    <td class='centrar' colspan='2'>Tiene ".$resultado_cuenta[0][0]." mensajes por leer de ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."<br>";
                    for($n=0;$n<count($resultado_nuevo);$n++)
                    {
                        $html.="<a id='mensajeleer' class='link' onclick='xajax_mensajesPorLeer(".$resultado_nuevo[$n][0].", ".$codDocente.", ".$codEstudiante.");'>
                                <font size='1'><b>".$resultado_nuevo[$n][1]." a las ".$resultado_nuevo[$n][2]."</b></font></a><br>";
                    }
                    $html.="</td>
                </tr>
            </table>
        </div>
    </td>
</tr>


";
}


//fin nuevo mensaje
                    if(is_array($resultado_mensaje))
                    {
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"tipomensaje","");//echo $cadena_sql;exit;
                        $resultado_tipo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

                        $html.="<tr><td colspan='2'>
                            <table class='contenidotabla centrar' border='0' background=".$configuracion['site'].$configuracion['grafico']."/escudo_fondo.png' style='background-attachment:fixed; background-repeat:no-repeat; background-position:top'>
                                <tr align='center'>
                                    <td class='centrar' colspan='2'>
                                        Escr&iacute;bale un mensaje a ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                        <textarea id='mensaje' name='mensaje' rows='2' cols='90')'></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                    <select class='sigma' id='tipo' name='tipo' style='width:300px'>
		                        <optgroup>
		                            <option value=''>Seleccione tema del mensaje...</option>";
		                                for($i=0;$i<count($resultado_tipo);$i++)
		                                    {
		                                        if($i==0)
		                                        {
		                                            $html.="
		                                        <option value='".$resultado_tipo[$i][0]."'selected>".$resultado_tipo[$i][1]."</option>";
		                                        }
		                                        else
		                                        {
		                                            $html.="
		                                        <option value='".$resultado_tipo[$i][0]."'>".$resultado_tipo[$i][1]."</option>";
		                                        }
		                                    }
		                                        $html.="
		                            </optgroup>
                                    </select>
                                </td>
                                </tr>
                                <tr>
                                    <td class='centrar' width='50%'>
                                        <input type='button' value='Guardar' onclick='xajax_guardarMensaje(document.getElementById(\"mensaje\").value, ".$codEstudiante.", ".$codDocente.", document.getElementById(\"tipo\").value);mensaje.value=\"\"'>
                                    </td>
                                    <td class='centrar' width='50%'>
                                        <input type='reset' value='Borrar'>
                                    </td>
                                </tr>
                            </table>
                        <hr noshade class='hr'></td></tr>";


                   $html.="
                            <tr><td colspan='2'><div id='div_mensajes'>
                    <table class='contenidotabla centrar'>
                    <tr>
                       <td class='centrar' colspan='2'>
                           <b>Mensajes entre Usted y ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."
                           </b>
                       </td>
                   </tr>";


                    for($m=0;$m<count($resultado_mensaje);$m++)
                    {
                        if($resultado_mensaje[$m][1]==$codEstudiante)
                        {
                            $resultado_mensaje[$m][1]="USTED";
                        }
                        else
                        {
                            $resultado_mensaje[$m][1]=$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1];
                        }
                            if(trim($resultado_mensaje[$m][6])=='L')
                            {
                                $estado="Ya ha sido le&iacute;do";
                            }
                            else
                            {
                                $estado="No se ha le&iacute;do";
                            }

                        $html.="<tr>
                            <td colspan='1'>El ".$resultado_mensaje[$m][2]." a las ".$resultado_mensaje[$m][3]." ".$resultado_mensaje[$m][1]." escribi&oacute; del tema ".$resultado_mensaje[$m][8].":</td>
                            <td align='right' colspan='1'>".$estado."</td>
                            </tr>
                            <tr>
                                <td colspan='2'>".$resultado_mensaje[$m][5]."<hr noshade class='hr'></td>
                            </tr>
                            ";
                    }
                    $html.="</table></div></td></tr>";

                }
                else
                    {
                        $html.="<tr class='centrar'>
                                    <td class='centrar'>
                                        <b>NO HAY MENSAJES</b>
                                    </td>
                                </tr>";
                        $cadena_sql=$this->sql->cadena_sql($configuracion,"tipomensaje","");//echo $cadena_sql;exit;
                        $resultado_tipo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
                    
                        $html.="<tr><td colspan='2'>
                            <table class='contenidotabla centrar' border='0' background=".$configuracion['site'].$configuracion['grafico']."/escudo_fondo.png' style='background-attachment:fixed; background-repeat:no-repeat; background-position:top'>
                                <tr align='center'>
                                    <td class='centrar' colspan='2'>
                                        Escr&iacute;bale un mensaje a ".$resultado_nombreDocente[0][0]." ".$resultado_nombreDocente[0][1]."
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                        <textarea id='mensaje' name='mensaje' rows='2' cols='90')'></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='centrar' colspan='2'>
                                    <select class='sigma' id='tipo' style='width:300px'>
		                        <optgroup>
		                            <option value=''>Seleccione tema del mensaje...</option>";
		                                for($i=0;$i<count($resultado_tipo);$i++)
		                                    {
		                                        if($i==0)
		                                        {
		                                            $html.="
		                                        <option value='".$resultado_tipo[$i][0]."'selected>".$resultado_tipo[$i][1]."</option>";
		                                        }
		                                        else
		                                        {
		                                            $html.="
		                                        <option value='".$resultado_tipo[$i][0]."'>".$resultado_tipo[$i][1]."</option>";
		                                        }
		                                    }
		                                        $html.="
		                            </optgroup>
                                    </select>
                                </td>
                                </tr>
                                <tr>
                                    <td class='centrar' width='50%'>
                                        <input type='button' value='Guardar' onclick='xajax_guardarMensaje(document.getElementById(\"mensaje\").value, ".$codEstudiante.", ".$codDocente.", document.getElementById(\"tipo\").value);mensaje.value=\"\"'>
                                    </td>
                                    <td class='centrar' width='50%'>
                                        <input type='reset' value='Borrar'>
                                    </td>
                                </tr>
                            </table>
                        <hr noshade class='hr'></td></tr>
                        <tr>
                            <td colspan='2'>
                                <div id='div_mensajes'>
                                </div>
                            </td>
                        </tr>";

                    }

                }else
                    {
                        $html.="<tr><td colspan='2'><div id='div_mensajes'>
        <tr class='centrar'>
                                    <td class='centrar'>
                                        <b>NO SE ENCONTRARON REGISTROS</b>
                                    </td>
                                </tr>
</div></td></tr>";
                    }

            $html.="</table>
                    ";
            /*$respuesta = new xajaxResponse();
            $respuesta->addAssign("mensaje","innerHTML",'');
            //$respuesta->addAssign("div_mensajes","innerHTML","");
            $respuesta->addAssign("cuerpopestanas","innerHTML",$html);

            $respuesta->addAssign("pestana" . 1, "className", "pestanaseleccionada");
            $respuesta->addAssign("pestanalink" . 1, "className", "pestanaseleccionada");
               //Pongo la class css de las pestañas sin pulsar
            for ($h=1; $h<=6; $h++){
                  if ($h != 5){
                     $respuesta->addAssign("pestanalink" . $h, "className", "pestanainactiva");
                     $respuesta->addAssign("pestana" . $h, "className", "pestanainactiva");
                  }
               }

            return $respuesta;
*/
            $html.="</table>
                        </div>";

echo $html;
        }
        else
        {
         ?>
             <table class="contenidotabla centrar">
                <tr class="sigma">
                    <BR><td class="sigma_a centrar">
                        NO TIENE ASIGNADO UN CONSEJERO. POR FAVOR, AC&Eacute;RQUESE A LA COORDINACI&Oacute;N DE SU PROYECTO CURRICULAR.
                        </td>
                </tr>
             </table><BR>
         <?
        }

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





}
?>
