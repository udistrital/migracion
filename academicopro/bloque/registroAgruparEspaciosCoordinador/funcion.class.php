<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroAgruparEspaciosCoordinador extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
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
        $this->formulario="registroAgruparEspaciosCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto) {

        ?>

<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>ESPACIOS ACAD&Eacute;MICOS CON OPCIONES PARA EL PROYECTO CURRICULAR<br><?echo $nombreProyecto?><br>PLAN DE ESTUDIOS: <?echo $planEstudio?></h4>
            <hr noshade class="hr">

        </td>
    </tr>

    <tr align="center">
        <td class="centrar" colspan="4">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=adminConfigurarPlanEstudioCoordinador";
                    $variables.="&opcion=mostrar";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Volver al Plan de estudios
            </a>
        </td>
    </tr>
</table><?
    }

    

    function ver_Encabezados($configuracion) {
        $nombreProyecto=$_REQUEST["nombreProyecto"];
        $codProyecto=$_REQUEST["codProyecto"];
        $planEstudio=$_REQUEST["planEstudio"];
        $this->encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto)

        ?>
<table class='sigma' align="center" width="80%">
  <tr><th colspan="6" class="sigma_a centrar">ADMINISTRACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS CON OPCIONES<BR>PARA POSTERIOR APROBACI&Oacute;N DE VICERRECTOR&Iacute;A ACAD&Eacute;MICA</th></tr>
    <tr align="center">
        <td class="sigma centrar" colspan="3">
                       <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="opcion" value="crear">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" value="Crear Encabezado" src="<?echo $configuracion['site'].$configuracion['grafico']?>/kate.png" width="35" height="35"><br>
                            Crear nombre general de<br>Espacio Acad&eacute;mico con opciones
                            </form>
        </td>
        <td class="sigma centrar" colspan="3">
                       <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="opcion" value="consultar">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" value="Crear Encabezado" src="<?echo $configuracion['site'].$configuracion['grafico']?>/kate.png" width="35" height="35"><br>
                            Consultar Espacios<br>Acad&eacute;micos con opciones
                            </form>
        </td>
    </tr>   
    <tr align="center">
        <td class="centrar" colspan="6"> <hr noshade class="hr"> </td>
    </tr>
    
</table>
    <?
      
    }
}

    
?>