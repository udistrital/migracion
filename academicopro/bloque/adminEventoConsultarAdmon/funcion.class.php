<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_adminEventoConsultarAdmon extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
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
        $this->formulario="adminEventoConsultarAdmon";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    function consultarEventos($configuracion)
    {
         $this->encabezadoModulo($configuracion);
         $this->menuAdministrador($configuracion);

         $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"listarEventos",'');//echo $cadena_sql;exit;
         $resultado_eventos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

         if($resultado_eventos)
             {
             ?>
<table class='contenidotabla centrar' background="<? echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">

    <tr class='cuadro_color centrar'>
        <td width="10%">
            ID Evento
        </td>
        <td width="30%">
            Nombre Evento
        </td>
        <td width="60%">
            Descripci&oacute;n Evento
        </td>
        <td width="15%">
            Modificar
        </td>
        <td width="15%">
            Borrar
        </td>
    </tr>
             <?
                for($i=0;$i<count($resultado_eventos);$i++)
                {
                    $evento=array($resultado_eventos[$i][0],$resultado_eventos[$i][1],$resultado_eventos[$i][2]);
                    $this->verEvento($configuracion, $evento);
                }
                ?>
</table>
                <?
             }else
                 {
                 ?>
                 <table class='contenidotabla centrar' background="<? echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">

                    <tr class='cuadro_color centrar'>
                        <td>
                            No se encuentran eventos registrados en el sistema
                        </td>
                    </tr>
                 </table>
        <?
                 }

         
    }

    function verEvento($configuracion, $evento)
    {
        ?>
        <tr class="cuadro_plano centrar">
            <td class="cuadro_plano centrar">
                <?echo $evento[0]?>
            </td>
            <td class="cuadro_plano centrar">
                <?echo $evento[1]?>
            </td>
            <td class="cuadro_plano centrar">
                <?echo $evento[2]?>
            </td>
            <td class="cuadro_plano centrar">
                <?
                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta="pagina=registroEventoEditarAdmon";
                    $ruta.="&opcion=formulario";
                    $ruta.="&idEvento=".$evento[0];
                    $ruta.="&nombreEvento=".$evento[1];
                    $ruta.="&descEvento=".$evento[2];
                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                ?>
                <a href="<?echo $indice.$ruta?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kate.png" alt="Modificar" width="25" height="25" border="0">
                </a>
            </td>
            <td class="cuadro_plano centrar">
                <?
                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta="pagina=registroEventoBorrarAdmon";
                    $ruta.="&opcion=confirmacion";
                    $ruta.="&idEvento=".$evento[0];
                    $ruta.="&nombreEvento=".$evento[1];
                    $ruta.="&descEvento=".$evento[2];
                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                ?>
                <a href="<?echo $indice.$ruta?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" alt="Borrar" width="25" height="25" border="0">
                </a>
            </td>
        </tr>

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
                    <h4>EVENTOS REGISTRADOS EN EL CALENDARIO ACAD&Eacute;MICO</h4>
                    <hr noshade class="hr">

                </td>
            </tr>
          
        </table>
     <?
    }

    function menuAdministrador($configuracion)
    {
        ?>
        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
        <tr align="center">
                <td class="centrar" colspan="4">
                            <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variables="pagina=registroEventoCrearAdmon";
                            $variables.="&opcion=crear";

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                            ?>
                    <a href="<?echo $pagina.$variables?>">
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kword.png" width="35" height="35" border="0"><br>Crear Evento
                    </a>
                </td>
            </tr>
        </table>
        <?
    }
}

?>