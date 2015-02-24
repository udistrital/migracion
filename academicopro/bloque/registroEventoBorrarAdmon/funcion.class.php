<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroEventoBorrarAdmon extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
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
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroEventoBorrarAdmon";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    function solicitarConfirmacion($configuracion)
    {
        $this->encabezadoModulo($configuracion);

        $evento=array($_REQUEST['idEvento'],$_REQUEST['descEvento'], $_REQUEST['nombreEvento']);
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"verificarEvento",$evento);//echo $cadena_sql;exit;
        $resultado_verificaEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

        if(!is_array($resultado_verificaEvento))
            {
?>

        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr class="centrar">
                <td colspan="2">
                    Borrar evento <?echo $_REQUEST['nombreEvento']?>
                </td>
            </tr>
            <tr>
                <td class="cuadro_color derecha">ID Evento</td>
                <td class="cuadro_plano"><?echo $_REQUEST['idEvento']?></td>
            </tr>
            <tr>
                <td class="cuadro_color derecha">Nombre de Evento</td>
                <td class="cuadro_plano"><?echo $_REQUEST['nombreEvento']?></td>
            </tr>
            <tr>
                <td class="cuadro_color derecha">Descripci&oacute;n de Evento</td>
                <td class="cuadro_plano"><?echo $_REQUEST['descEvento']?></td>
            </tr>
            <tr class="centrar">
                <td colspan="2">¿Esta seguro de borrar este evento?</td>
            </tr>
        </table>
        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr class="centrar" >
                <td class="cuadro_color centrar">
                    <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=registroEventoBorrarAdmon";
			$variable.="&opcion=borrar";
			$variable.="&idEvento=".$_REQUEST['idEvento'];
			$variable.="&nombreEvento=".$_REQUEST['nombreEvento'];
			$variable.="&descEvento=".$_REQUEST['descEvento'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
                    <a href="<?echo $pagina.$variable?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" alt="Si" width="25" height="25" border="0"><br>Si</a>
                </td>
                <td class="cuadro_color centrar" >
                    <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=adminEventoConsultarAdmon";
			$variable.="&opcion=consultar";

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
                    <a href="<?echo $pagina.$variable?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" alt="No" width="25" height="25" border="0"><br>No</a>
                </td>
            </tr>
        </table>

<?
            }else
                {?>
                <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                <tr class="centrar">
                    <td colspan="2">
                        El evento no puede ser borrado por tener registros de fechas dentro del calendario acad&eacute;mico.
                    </td>
                </tr>
                </table><?
                }

         
    
    }

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
                    <h4>MODIFICAR EVENTOS REGISTRADOS EN EL CALENDARIO ACAD&Eacute;MICO</h4>
                    <hr noshade class="hr">

                </td>
            </tr>

        </table>
     <?
    }

    function borrarEvento($configuracion)
    {
        
        $evento=array($_REQUEST['idEvento'],$_REQUEST['descEvento'], $_REQUEST['nombreEvento']);
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"verificarEvento",$evento);//echo $cadena_sql;exit;
        $resultado_verificaEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

        if(is_array($resultado_verificaEvento))
            {
                echo "<script>alert('El evento ".$_REQUEST['nombreEvento']." no se puede eliminar por que existen fechas activas o inactivas')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=adminEventoConsultarAdmon";
                $variables.="&opcion=consultar";

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }else
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrarEvento",$evento);//echo $cadena_sql;exit;
                    $resultado_borrarEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

                    if($resultado_borrarEvento==true)
                        {
                                $log=array($this->usuario, date('YmdHis'),'27', 'Borro Evento para del calendario académico', $_REQUEST["idEvento"]."-".$_REQUEST["nombreEvento"]."-".$_REQUEST['descEvento'], $_REQUEST['idEvento']);
                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"logEventos",$log);//echo $cadena_sql;exit;
                                $resultado_log=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

                                echo "<script>alert('El evento ".$_REQUEST['nombreEvento']." fue borrado correctamente')</script>";
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variables="pagina=adminEventoConsultarAdmon";
                                $variables.="&opcion=consultar";

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variables=$this->cripto->codificar_url($variables,$configuracion);
                                echo "<script>location.replace('".$pagina.$variables."')</script>";
                                break;
                        }
                        else
                            {
                                echo "<script>alert('El evento ".$_REQUEST['nombreEvento']." no se pudo borrar intente de nuevo')</script>";
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variables="pagina=adminEventoConsultarAdmon";
                                $variables.="&opcion=consultar";

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variables=$this->cripto->codificar_url($variables,$configuracion);
                                echo "<script>location.replace('".$pagina.$variables."')</script>";
                                break;
                            }
                }

    }
}

?>