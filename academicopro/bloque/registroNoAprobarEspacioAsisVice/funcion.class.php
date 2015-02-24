
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
class funcion_registroNoAprobarEspacioAsisVice extends funcionGeneral {
    

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_registroNoAprobarEspacioAsisVice();
        $this->log_us= new log();
        $this->formulario="registroNoAprobarEspacioAsisVice";

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

}#Cierre de constructor


    function formularioComentario($configuracion)
    {
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nivel=$_REQUEST['nivel'];
        $creditos=$_REQUEST['creditos'];
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
        $clasificacion=$_REQUEST['clasificacion'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];

        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_id", $planEstudio);
        $resultado_proyecto=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

        $codProyecto= $resultado_proyecto[0][10];
        $nombreProyecto= $resultado_proyecto[0][7];

        $variables=array($codEspacio,$planEstudio,$nivel,$creditos,$htd,$htc,$clasificacion,$nombreEspacio);

        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
    

        ?>
<table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr align="center">
                <td class="centrar" colspan="2">
                    <h2>Informaci&oacute;n del Espacio Acad&eacute;mico</h2>
                    <hr noshade class="hr">
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="40%">Fecha:</td><td class="cuadro_plano"><?echo date('d/m/Y')?></td>
            </tr>
             <tr>
                <td class="cuadro_plano">Plan de Estudios:</td><td class="cuadro_plano"><?echo $planEstudio?></td>
            </tr>            
            <tr>
                <td class="cuadro_plano">C&oacute;digo del Espacio Acad&eacute;mico:</td><td class="cuadro_plano"><?echo $codEspacio?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Nombre del Espacio Acad&eacute;mico:</td><td class="cuadro_plano"><?echo $nombreEspacio?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Cr&eacute;ditos:</td><td class="cuadro_plano"><?echo $creditos?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Horas de Trabajo Directo:</td><td class="cuadro_plano"><?echo $htd?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Horas de Trabajo Cooperativo:</td><td class="cuadro_plano"><?echo $htc?></td>
            </tr>
            <tr>
                <td class="cuadro_plano">Horas de Trabajo Autonomo:</td><td class="cuadro_plano"><?echo $hta?></td>
            </tr>
            <tr>
                <td colspan="2" class="centrar">
              <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                    <font size="2">Justificaci&oacute;n de la no aprobaci&oacute;n del espacio acad&eacute;mico <?echo $nombreEspacio?></font><br>
                <textarea cols="70" rows="7" name="comentario"></textarea>
            </td>
            </tr>
</table>
<table class='contenidotabla centrar' border="0">    
            <tr>
                <td class="centrar" width="50%">
                       <input type="hidden" name="codEspacio" value="<?echo $codEspacio?>">
                        <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                        <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                        <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                        <input type="hidden" name="nombreEspacio" value="<?echo $nombreEspacio?>">
                        <input type="hidden" name="creditos" value="<?echo $creditos?>">
                        <input type="hidden" name="nivel" value="<?echo $nivel?>">
                        <input type="hidden" name="htd" value="<?echo $htd?>">
                        <input type="hidden" name="htc" value="<?echo $htc?>">
                        <input type="hidden" name="hta" value="<?echo $hta?>">

                        <input type="hidden" name="opcion" value="confirmado">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" value="Confirmado" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Enviar
                </td>
                </form>
                <td class="centrar" width="50%">
                 <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
              
                        <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                       
                        <input type="hidden" name="opcion" value="mostrar">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" value="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/x.png" width="35" height="35"><br>Cancelar
                 </form>
                </td>                
            </tr>
        </table>

        <?
    }


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
            <h4>MODULO PARA LA ADMINISTRACI&Oacute;N DE PLANES DE ESTUDIO</h4>
            <hr noshade class="hr">

        </td>
    </tr>

    <tr align="center">
        <td class="centrar" colspan="4">
        <?
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variables="pagina=adminAprobarEspacioPlan";
        $variables.="&opcion=ver";

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
        </td>
    </tr>
</table><?
                }

   function guardarRegistros($configuracion)
    {
        $usuario=$this->usuario;
       // echo $_REQUEST['comentario'];exit;

          if($_REQUEST['comentario'])
                {$band=1;
                }
          
          if($band==1)
                {
                   $variables1=array($_REQUEST["codEspacio"], $_REQUEST["planEstudio"], $_REQUEST["codProyecto"], $usuario, date('YmdGis'), $_REQUEST['comentario']);
                   $variables2=array($_REQUEST["codEspacio"], $_REQUEST["planEstudio"]);
                 
                   $cadena_sql_comentarioNoAprobar=$this->sql->cadena_sql($configuracion, "comentarioNoAprobar", $variables1);
                   $resultadoComentarioNoAprobar=$this->accesoGestion->ejecutarAcceso ($cadena_sql_comentarioNoAprobar, "" );

                   if($resultadoComentarioNoAprobar==true)
                   {
                   $cadena_sql_desaprobarEspacio=$this->sql->cadena_sql($configuracion, "DesaprobarEspacio", $variables2);
                   $resultadoDesaprobarEspacio=$this->accesoGestion->ejecutarAcceso ($cadena_sql_desaprobarEspacio, "busqueda" );

                   $cadena_sql=$this->sql->cadena_sql($configuracion, "comentarioAutomaticoNoAprobo", $variables1);// echo $cadena_sql;exit;
                   $resultadoMensaje=$this->accesoGestion->ejecutarAcceso ($cadena_sql, "" );

                   $this->cadena_sql=$this->sql->cadena_sql($configuracion,"bimestreActual", '');
                   $resultadoPeriodo=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
                   $ano=$resultadoPeriodo[0][0];
                   $periodo=$resultadoPeriodo[0][1];

                   $variablesRegistro=array($usuario, date('Ymd'), $ano, $periodo, $_REQUEST["codEspacio"], $_REQUEST["planEstudio"], $_REQUEST["codProyecto"] );
                   $this->cadena_sql=$this->sql->cadena_sql($configuracion,"registroEvento",$variablesRegistro);                   
                   $registroEvento=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");
                   
                   echo "<script>alert ('El espacio académico NO fue aprobado y el comentario fue enviado');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=adminAprobarEspacioPlan";
		   $variable.="&opcion=mostrar";
                   $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nivel=".$_REQUEST["nivel"];
                   $variable.="&creditos=".$_REQUEST["creditos"];
                   $variable.="&htd=".$_REQUEST["htd"];
                   $variable.="&htc=".$_REQUEST["htc"];
                   $variable.="&hta=".$_REQUEST["hta"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                   $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                   }
                   else
                   {
                   echo "<script>alert ('La base de datos se encuentra ocupada intente más tarde ');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroNoAprobarEspacioAsisVice";
		   $variable.="&opcion=no_aprobar";
                   $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nivel=".$_REQUEST["nivel"];
                   $variable.="&creditos=".$_REQUEST["creditos"];
                   $variable.="&htd=".$_REQUEST["htd"];
                   $variable.="&htc=".$_REQUEST["htc"];
                   $variable.="&hta=".$_REQUEST["hta"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                   $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                  }
                }
            else
                {
                   echo "<script>alert ('Debe de diligenciar el campo: Justificación de la no aprobación ');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroNoAprobarEspacioAsisVice";
		   $variable.="&opcion=no_aprobar";
                   $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nivel=".$_REQUEST["nivel"];
                   $variable.="&creditos=".$_REQUEST["creditos"];
                   $variable.="&htd=".$_REQUEST["htd"];
                   $variable.="&htc=".$_REQUEST["htc"];
                   $variable.="&hta=".$_REQUEST["hta"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                   $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }


    }



}
?>
