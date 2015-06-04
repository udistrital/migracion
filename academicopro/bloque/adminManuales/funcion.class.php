
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



    //inicia lista de planes de estudios
    function verManualesCoordinador($configuracion) {

        ?>     
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
          <font size=1><b>PREINSCRIPCI&Oacute;N POR DEMANDA</b></font><br>
          <table class="contenidotabla centrar">
              <tr>
                  <td class="centrar" colspan="2">
                      <a onclick="window.open('<?echo $configuracion['site'].$configuracion['bloques']?>/adminManuales/preinscripciones/inscripcionPorDem-perfilCoordinador.htm','Preinscripcion por demanda')" >
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/folder_video.png" border=0><br>Ver Tutorial</a>
                  </td>
              </tr>
          </table>
	    
       </td>
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

    /**
     *metodo para mostrar listado de manuales, se debe crear un registro por cada manual en el arreglo $arregloEnlace
     * @param type $configuracion 
     */
    function verManualesAsisVice($configuracion) {

                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta="pagina=admin_consultarReporteGruposAsisVice";
                    $ruta.="&usuario=".$this->usuario;
                    $ruta.="&opcion=reporteGrupos";                    
                    $ruta.="&tipoUser=61";

                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        
        
        $arregloEnlace[0]=array(    'etiqueta' => 'Reporte grupos por espacio acad&eacute;mico', 
                                    'ruta'=>$indice.$ruta,
                                    'imagen'=>'kate.png');
                

                    $indice=$configuracion["host"].$configuracion["site"]."/index.php?";

                    $ruta="pagina=adminConsultarIncritosEspacioPorFacultadAsisVice";
                    $ruta.="&usuario=".$this->usuario;
                    $ruta.="&opcion=select";
                    $ruta.="&tipoUser=61";

                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);        
        

        $arregloEnlace[1]=array(    'etiqueta' => 'Reporte inscritos por grupo', 
                                    'ruta'=>$indice.$ruta,
                                    'imagen'=>'kate.png');             
        
   
        $arregloEnlace[2]=array(    'etiqueta' => 'Manual de usuario Asesor Vicerrector', 
                                    'ruta'=>$configuracion['site'].$configuracion['bloques'].'/adminManuales/MANUAL_SGA_AsesorVice_Planes_Estudio.pdf',
                                    'imagen'=>'acroread.png');
        
        
        $arregloEnlace[3]=array(    'etiqueta' => 'Planes de Estudio', 
                                    'ruta'=>$configuracion['site'].$configuracion['bloques'].'/adminManuales/MANUAL_SGA_Coordinador_Planes_Estudio.pdf',
                                    'imagen'=>'acroread.png');
                
        $arregloEnlace[4]=array(    'etiqueta' => 'Rangos Planes de Estudios', 
                                    'ruta'=>$configuracion['site'].$configuracion['bloques'].'/adminManuales/Rangos/Turor Rangos.htm',
                                    'imagen'=>'folder_video.png');
                
        $arregloEnlace[5]=array(    'etiqueta' => 'Cursos intermedios', 
                                    'ruta'=>$configuracion['site'].$configuracion['bloques'].'/adminManuales/Cursos intermedios/TutorCursosIntermedios.htm',
                                    'imagen'=>'folder_video.png');
               
               
                    
        ?>

        <table  class="contenidotabla centrar" width="100%" >
                <tr class="texto_subtitulo">
                    <td class="cuadro_color centrar" colspan="6">
                                <?
                                echo "Manuales, tutoriales y reportes";
                                ?>
                        <hr class="hr_subtitulo">
                    </td>
                </tr>

                <?

                    foreach ($arregloEnlace as $key => $value) {

                        $this->armarEnlace($arregloEnlace[$key],$configuracion);

                    }
                ?>


        </table>
        <?
    }

    
    function armarEnlace($datosEnlace, $configuracion) {
        ?>
            <tr class="cuadro_plano centrar">
                <td class="cuadro_plano" width="10%" align="center">
                    <font size=3><?echo $datosEnlace['etiqueta']?></font><br>
                    </td>
                    <td class="cuadro_plano" width="10%" align="center">
                    <a href="<?echo $datosEnlace['ruta']?>"> <img src="<?echo $configuracion['site'].$configuracion['grafico'].'/'.$datosEnlace['imagen']?>" width="30" height="30"></a>
                </td>
            </tr>        
        <?
        
    }
    
    
        function verManualesEstudiante($configuracion) {

        ?>
<table width="100%" border="0" align="center"  cellpadding="5" cellspacing="2">
    <tr><td class="cuadro_color centrar" width="10%" align="center">MANUALES PARA ESTUDIANTES DE CR&Eacute;DITOS</td></tr>
</table>
<table  class="contenidotabla centrar" width="100%" >
    <tr class="cuadro_plano centrar">
      <td class="cuadro_plano centrar" width="10%" align="center" colspan="2">
          <font size=1><b>PRE-INSCRIPCIONES 2012-1</b></font><br>
          <table class="contenidotabla centrar">
              <tr>
                  <td class="centrar" colspan="2">
                      <a onclick="window.open('<?echo $configuracion['site'].$configuracion['bloques']?>/adminManuales/preinscripciones/inscripcionPorDemanda.htm','Preinscripciones 2012-1')" >
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/folder_video.png" border=0 style="cursor: pointer;"><br>Ver Tutorial</a>
                  </td>
              </tr>
          </table>

       </td>
    </tr>
    <tr class="cuadro_plano centrar">
      <td class="cuadro_plano centrar" width="10%" align="center" colspan="2">
          <font size=1><b>ADICIONES Y CANCELACIONES</b></font><br>
          <table class="contenidotabla centrar">
              <tr>
                  <td class="centrar" colspan="2">
                      <a onclick="window.open('<?echo $configuracion['site'].$configuracion['bloques']?>/adminManuales/InscripcionesEstud/adicionesCancelacionesEstud.htm','Adiciones y Cancelaciones Estudiante')" >
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/folder_video.png" border=0 style="cursor: pointer;"><br>Ver Tutorial</a>
                  </td>
              </tr>
          </table>

       </td>
    </tr>
    <tr class="cuadro_plano centrar">
      <td class="cuadro_plano centrar" width="10%" align="center">
          <font size=1>MANUAL INSCRIPCIONES ESTUDIANTE</font><br>
	    <a href="<?echo $configuracion['site'].$configuracion['bloques']?>/adminManuales/Manual_Estudiante_Inscripciones.pdf"> <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a>
       </td>
      <td class="cuadro_plano centrar" width="10%" align="center">
          <font size=1>MANUAL PLANES DE ESTUDIO ESTUDIANTE</font><br>
	    <a href="<?echo $configuracion['site'].$configuracion['bloques']?>/adminManuales/Manual_Estudiante_Planes_de_Estudio.pdf"> <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/acroread.png" border=0><br>Descargar PDF</a>
       </td>
    </tr>
</table>
        <?
    }

}
?>
