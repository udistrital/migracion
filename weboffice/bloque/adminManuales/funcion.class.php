
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
class funcion_adminManuales extends funcionGeneral {


    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_adminManuales();
        $this->log_us= new log();
        $this->formulario="adminManuales";

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

        function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto) {

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
                <h4>UNIVERSIDAD DISTRITAL FRANCISCO JOSE DE CALDAS<br>MANUALES <BR> SISTEMA DE GESTION ACAD&Eacute;MICA</h4>
            <hr noshade class="hr">

        </td>
    </tr>


</table><?
    }

    //inicia lista de planes de estudios
    function verManualesCoordinador($configuracion) {

        ?>
<table width="90%" border="0" align="center"  cellpadding="5" cellspacing="2">
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>
                    <?
                    $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);                  
                     ?>
        </td>
    </tr>
</table>       
<table  class="contenidotabla centrar" width="100%" >
    <tr class="texto_subtitulo">
        <td class="cuadro_color centrar" colspan="6">
                    <?
                    echo "Planes de Estudio";
                    ?>
            <hr class="hr_subtitulo">
        </td>
    </tr>           
     <tr class="cuadro_plano centrar">
      <td class="cuadro_plano centrar" width="10%" align="center">
          <font size=1><b>PLANES DE ESTUDIO</b></font><br>
	    <a href="<?echo $configuracion['site'].$configuracion['bloques']?>/adminManuales/MANUAL_SGA_Coordinador_Planes_Estudio.pdf"> <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a>
       </td>       
    </tr>
     <tr class="cuadro_plano centrar">
      <td class="cuadro_plano centrar" width="10%" align="center">
          <font size=1><b>RANGOS PLANES DE ESTUDIO</b></font><br>
          <table class="contenidotabla centrar">
              <tr>
                  <td class="centrar" colspan="2">
                      <a onclick="window.open('<?echo $configuracion['site'].$configuracion['bloques']?>/adminManuales/Rangos/Turor Rangos.htm','Rangos Plan Estudio')" >
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/folder_video.png" border=0><br>Ver Tutorial</a>
                  </td>
              </tr>
          </table>
	    
       </td>
    </tr>
     <tr class="cuadro_plano centrar">
      <td class="cuadro_plano centrar" width="10%" align="center">
          <font size=1><b>CURSOS INTERMEDIOS</b></font><br>
          <table class="contenidotabla centrar">
              <tr>
                  <td class="centrar" width="50%">
                      <a href="<?echo $configuracion['site'].$configuracion['bloques']?>/adminManuales/MANUAL_SGA_Coordinador_Planes_Estudio.pdf"> <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a>
                  </td>
                  <td class="centrar" width="50%">
                      <a onclick="window.open('<?echo $configuracion['site'].$configuracion['bloques']?>/adminManuales/Cursos intermedios/TutorCursosIntermedios.htm','Cursos Intermedios')" >
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/folder_video.png" border=0><br>Ver Tutorial</a>
                  </td>
              </tr>
          </table>

       </td>
    </tr>
    
</table>
        <?
    }

    function verManualesAsisVice($configuracion) {

        ?>
<table width="90%" border="0" align="center"  cellpadding="5" cellspacing="2">
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>
                    <?
                    $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
                     ?>
        </td>
    </tr>
</table>
<table  class="contenidotabla centrar" width="100%" >
    <tr class="texto_subtitulo">
        <td class="cuadro_color centrar" colspan="6">
                    <?
                    echo "Planes de Estudio";
                    ?>
            <hr class="hr_subtitulo">
        </td>
    </tr>
     <tr class="cuadro_plano centrar">
      <td class="cuadro_plano centrar" width="10%" align="center">
          <font size=1>MANUAL DE USUARIO ASESOR VICERRECTOR</font><br>
	    <a href="<?echo $configuracion['site'].$configuracion['bloques']?>/adminManuales/MANUAL_SGA_AsesorVice_Planes_Estudio.pdf"> <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a>
       </td>
    </tr>
</table>
        <?
    }

        function verManualesEstudiante($configuracion) {

        ?>
<table width="90%" border="0" align="center"  cellpadding="5" cellspacing="2">
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>
                    <?
                    $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
                     ?>
        </td>
    </tr>
</table>
<table  class="contenidotabla centrar" width="100%" >
    <tr class="texto_subtitulo">
        <td class="cuadro_color centrar" colspan="6">
                    <?
                    echo "Planes de Estudio";
                    ?>
            <hr class="hr_subtitulo">
        </td>
    </tr>
     <tr class="cuadro_plano centrar">
      <td class="cuadro_plano centrar" width="10%" align="center">
          <font size=1>MANUAL DE USUARIO ESTUDIANTE</font><br>
	    <a href="<?echo $configuracion['site'].$configuracion['bloques']?>/adminManuales/Manual_Estudiante_SGA.pdf"> <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a>
       </td>
    </tr>
</table>
        <?
    }

}
?>
