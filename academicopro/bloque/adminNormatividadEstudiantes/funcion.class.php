
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
class funcion_adminNormatividadEstudiantes extends funcionGeneral {


    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_adminNormatividadEstudiantes();
        $this->log_us= new log();
        $this->formulario="adminNormatividadEstudiantes";

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


    //inicia lista de planes de estudios
    function paginaNormatividad($configuracion) {

        ?>
<table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>NORMATIVIDAD UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS</h4>
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png " alt="Logo Universidad">
            <hr>
        </td>
    </tr>
    <tr>
        <td width="50%" class="cuadro_plano centrar">
		<h5>ACUERDO 009 DE 2006</h5>
                <p>
            <a href="<?echo $configuracion['host'].$configuracion['site']?>/documentos/acu_2006-009.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a></p>
        </td>
	<td width="50%" class="cuadro_plano centrar">
		<h5>RESOLUCI&Oacute;N 035 DE 2006</h5><p>
	    <a href="<?echo $configuracion['host'].$configuracion['site']?>/documentos/Resolucion_035_de_2006.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a></p>
	</td>
    </tr>
    <tr>
        <td width="50%" class="cuadro_plano centrar">
		<h5>QUE SON CR&Eacute;DITOS ACAD&Eacute;MICOS</h5><p>
            <a href="<?echo $configuracion['host'].$configuracion['site']?>/documentos/QUE_SON_CREDITOS_ACADEMICOS.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a></p>
        </td>
	<td width="50%" class="cuadro_plano centrar">
		<h5>RESOLUCI&Oacute;N 026 DE 2009</h5><p>
	    <a href="<?echo $configuracion['host'].$configuracion['site']?>/documentos/Resolucion_026.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a></p>
	</td>
    </tr>
    <tr>
        <td width="50%" class="cuadro_plano centrar">
		<h5>ACUERDO 007 DE 2009</h5><p>
            <a href="<?echo $configuracion['host'].$configuracion['site']?>/documentos/acu_2009-007.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a></p>
        </td>
        <td width="50%" class="cuadro_plano centrar">
		<h5>ACUERDO 004 DE 2011</h5><p>
            <a href="<?echo $configuracion['host'].$configuracion['site']?>/documentos/acu_2011_004.pdf"><img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a></p>
        </td>
    </tr>
</table>
        <?
    }
}
?>
