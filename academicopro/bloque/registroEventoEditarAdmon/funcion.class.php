<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroEventoEditarAdmon extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
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
        $this->formulario="registroEventoEditarAdmon";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    function formularioEditar($configuracion)
    {
        $this->encabezadoModulo($configuracion);
?>

        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
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
                <td class="cuadro_plano"><textarea name="descEvento" cols="60" rows="2"><?echo $_REQUEST['descEvento']?></textarea></td>
            </tr>
        </table>
        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr class="centrar" >
                <td class="cuadro_color centrar">
                    <input type="hidden" name="idEvento" value="<?echo $_REQUEST['idEvento']?>">
                    <input type="hidden" name="nombreEvento" value="<?echo $_REQUEST['nombreEvento']?>">
                    <input type="hidden" name="descEventoAnt" value="<?echo $_REQUEST['descEvento']?>">
                    <input type="hidden" name="opcion" value="guardar">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="submit" name="Guardar" value="Guardar">
                </td></form>
                <td class="cuadro_color centrar" >
                    <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=adminEventoConsultarAdmon";
			$variable.="&opcion=consultar";

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
                    <a href="<?echo $pagina.$variable?>"><input type="button" name="Cancelar" value="Cancelar"></a>
                </td>
            </tr>
        </table>

<?
        

         
    
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

    function guardarModificacion($configuracion)
    {
        if($_REQUEST['descEvento']=='')
            {
                echo "<script>alert('Debe diligenciar los campos obligatorios')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroEventoEditarAdmon";
                $variables.="&opcion=formulario";
                $variables.="&idEvento=".$_REQUEST['idEvento'];
                $variables.="&nombreEvento=".$_REQUEST['nombreEvento'];
                $variables.="&descEvento=".$_REQUEST['descEventoAnt'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }
            else if(trim($_REQUEST['descEvento'])==trim($_REQUEST['descEventoAnt']))
                {
                    echo "<script>alert('El evento no se ha modificado')</script>";
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
                        $evento=array($_REQUEST['idEvento'],$_REQUEST['descEvento'], $_REQUEST['nombreEvento']);
                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizarEvento",$evento);//echo $cadena_sql;exit;
                        $resultado_actualizar=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

                        if($resultado_actualizar==true)
                            {
                                $log=array($this->usuario, date('YmdHis'),'26', 'Modifico evento del calendario academico', $_REQUEST["idEvento"]."-".$_REQUEST["nombreEvento"]."-".$_REQUEST['descEvento'], $_REQUEST['idEvento']);
                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"logEventos",$log);//echo $cadena_sql;exit;
                                $resultado_log=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

                                echo "<script>alert('El evento ".$_REQUEST['nombreEvento']." fue modificado correctamente')</script>";
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