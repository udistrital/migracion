<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroEventoBorrarFechaAdmon extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
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
        $this->formulario="registroEventoBorrarFechaAdmon";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    function solicitarConfirmacion($configuracion)
    {
        $this->encabezadoModulo($configuracion);

        $variablesFecha=array($_REQUEST['idFechaEvento'],$_REQUEST['nombreEvento'], $_REQUEST['ano'],$_REQUEST['periodo'],$_REQUEST['idCobertura'],$_REQUEST['idUsuarioAfectado']);
        //var_dump($variablesFecha);exit;
        
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarCobertura",$_REQUEST['idCobertura']);//echo $cadena_sql;exit;
        $resultado_cobertura=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

        $fechaInicio=explode("-",$_REQUEST['fechaHoraInicio']);
        $fechaFin=explode("-",$_REQUEST['fechaHoraFin']);
        $fechaInicial=$fechaInicio[0];
        $horaInicial=$fechaInicio[1];
        $fechaFinal=$fechaFin[0];
        $horaFinal=$fechaFin[1];

        ?>

        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr class="centrar">
                <td colspan="2">
                    BORRAR FECHAS DEL EVENTO <?echo $_REQUEST['nombreEvento']?>
                </td>
            </tr>
            <tr>
                <td class="cuadro_color derecha">Fecha Inicio : </td>
                <td class="cuadro_plano"><?echo $fechaInicial."<br>".$horaInicial?></td>
            </tr>
            <tr>
                <td class="cuadro_color derecha">Fecha Final : </td>
                <td class="cuadro_plano"><?echo $fechaFinal."<br>".$horaFinal?></td>
            </tr>
            <tr>
                <td class="cuadro_color derecha">Nombre de Evento : </td>
                <td class="cuadro_plano"><?echo $_REQUEST['nombreEvento']?></td>
            </tr>
            <tr>
                <td class="cuadro_color derecha">Cobertura : </td>
                <td class="cuadro_plano"><?echo $resultado_cobertura[0][0]?></td>
            </tr>
            <tr class="centrar">
                <td colspan="2">¿Esta seguro de borrar esta fecha?</td>
            </tr>
        </table>
        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr class="centrar" >
                <td class="cuadro_color centrar">
                    <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=registroEventoBorrarFechaAdmon";
			$variable.="&opcion=borrar";
			$variable.="&idFechaEvento=".$_REQUEST['idFechaEvento'];
			$variable.="&nombreEvento=".$_REQUEST['nombreEvento'];
			$variable.="&idEvento=".$_REQUEST['idEvento'];
			$variable.="&idUsuarioAfectado=".$_REQUEST['idUsuarioAfectado'];
			
                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
                    <a href="<?echo $pagina.$variable?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" alt="Si" width="25" height="25" border="0"><br>Si</a>
                </td>
                <td class="cuadro_color centrar" >
                    <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=adminEventoConsultarFechaAdmon";
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
            

         
    
    }

     function encabezadoModulo($configuracion)
    {
        
        ?>

<body oncontextmenu="return false">
        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr align="center">
                <td class="centrar" colspan="4">
                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png " alt="Logo Universidad">
                </td>
            </tr>
            <tr align="center">
                <td class="centrar" colspan="4">
                    <h4>BORRAR FECHAS REGISTRADAS EN EL CALENDARIO ACAD&Eacute;MICO</h4>
                    <hr noshade class="hr">

                </td>
            </tr>

        </table>
     <?
    }

    function borrarFecha($configuracion)
    {
        $variableFecha=array($_REQUEST['idFechaEvento'],$_REQUEST['nombreEvento'],$_REQUEST['$idEvento'],$_REQUEST['idUsuarioAfectado']);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrarFecha",$variableFecha);//echo $cadena_sql;exit;
        $resultado_borrarFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

       if($resultado_borrarFecha==true)
            {
                $log=array($this->usuario, date('YmdHis'),'29', 'Borro fecha del calendario academico', $_REQUEST["idEvento"]."-".$_REQUEST["nombreEvento"]."-".$_REQUEST['idFechaEvento'], $_REQUEST['idUsuarioAfectado']);
                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"logEventos",$log);//echo $cadena_sql;exit;
                $resultado_log=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

                echo "<script>alert('La fecha del evento ".$_REQUEST['nombreEvento']." fue borrado correctamente')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=adminEventoConsultarFechaAdmon";
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
                    $variables="pagina=adminEventoConsultarFechaAdmon";
                    $variables.="&opcion=consultar";

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    echo "<script>location.replace('".$pagina.$variables."')</script>";
                    break;
                }
                

    }
}

?>