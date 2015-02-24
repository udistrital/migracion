
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
class funcion_adminInscripcionEstudianteCoordinador extends funcionGeneral {


    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_adminInscripcionEstudianteCoordinador();
        $this->log_us= new log();
        $this->formulario="adminInscripcionEstudianteCoordinador";

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

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


    function consultarEstudiante($configuracion)
    {
        //var_dump($_REQUEST);
        ?>

<table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>Digite el c&oacute;digo del estudiante que desea consultar</h4>
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
            <input type="text" name="codEstudiante" size="11" maxlength="11">
            <input type="hidden" name="opcion" value="validar">
            <input type="hidden" name="action" value="<?echo $this->formulario?>">
            <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
            <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
            <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
            <input type="hidden" name="planEstudioGeneral" value="<?echo $_REQUEST['planEstudio']?>">
            <input type="submit" name="Consultar" value="Consultar">
        </td>
    </tr>
    <tr>
        <td>
            <hr align="center">
        </td>
    </tr>
    </form>
</table>

<?
    }


    function validarEstudiante($configuracion)
    {

        $codEstudiante=$_REQUEST['codEstudiante'];
        

        $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarInfoEstudiante",$codEstudiante);//echo $cadena_sql;exit;
        $resultado_estudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if(is_numeric($codEstudiante))
        {
        if(trim($resultado_estudiante[0][0])=='S')
            {
                $cadena_sql_proyectos=$this->sql->cadena_sql($configuracion,"proyectos_curriculares",$this->usuario);//echo $cadena_sql_proyectos;exit;
                $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
                $band=0;
                for($i=0;$i<count($resultado_proyectos);$i++)
                    {
                     if($resultado_estudiante[0][1]==$resultado_proyectos[$i][0])
                      {
                         $band=1;
                      }
                    }
                 
                if($band==1)
                   {
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudio"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);
                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                   }
                   else
                   {
                    echo "<script>alert('El estudiante no pertenece a ningún proyecto asignado al Coordinador')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminInscripcionEstudianteCoordinador";
                    $variable.="&opcion=consultar";
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);
                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                   }

                

            }else if(trim($resultado_estudiante[0][0])=='N')
                {
                    echo "<script>alert('El estudiante no pertenece al sistema de créditos académicos')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminInscripcionEstudianteCoordinador";
                    $variable.="&opcion=consultar";
                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                    $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);
                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                }else
                    {
                        echo "<script>alert('Digite de nuevo el código del estudiante, datos no validos')</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminInscripcionEstudianteCoordinador";
                        $variable.="&opcion=consultar";
                        $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                        $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                        $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                    }
        }else
            {
                echo "<script>alert('El código del estudiante debe ser numerico, digite de nuevo el código')</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminInscripcionEstudianteCoordinador";
                        $variable.="&opcion=consultar";
                        $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                        $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                        $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                        $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";
            }
    }

}
?>
