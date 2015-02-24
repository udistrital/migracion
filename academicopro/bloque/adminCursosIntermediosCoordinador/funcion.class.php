
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
class funcion_adminCursosIntermediosCoordinador extends funcionGeneral {


    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_adminCursosIntermediosCoordinador();
        $this->log_us= new log();
        $this->formulario="adminCursosIntermediosCoordinador";

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


    function menuCoordinador($configuracion, $variable)
    {
        
       ?>

<table class="contenidotabla centrar" width="100%" >

   
    <tr class="cuadro_color centrar">
        <td colspan="2" class="cuadro_color centrar"  width="50%">
            <?
            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=adminCIEstudianteCoordinador";
            $ruta.="&opcion=consultar";
            $ruta.="&planEstudio=".$variable[2];
            $ruta.="&nombreProyecto=".$variable[1];
            $ruta.="&codProyecto=".$variable[0];

            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>

            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/solo.png" width="50" height="50" border="0" alt="Administracion por Estudiante">
                <br>Inscripci&oacute;n<br> por Estudiante
            </a>
        </td>
<td colspan="4" class="cuadro_color centrar">
            <font size="2"><b>CURSOS INTERMEDIOS</b></font>
        </td>
        <td colspan="2" class="cuadro_color centrar" width="50%">
            <?
            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=adminCIGrupoCoordinador";
            $ruta.="&opcion=visualizar";
            $ruta.="&planEstudio=".$variable[2];
            $ruta.="&nombreProyecto=".$variable[1];
            $ruta.="&codProyecto=".$variable[0];
           
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>


            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/personas.png" width="50" height="50" border="0" alt="Administracion por Grupo">
                <br>Inscripci&oacute;n<br> por Grupo
            </a>
        </td>

    </tr>
</table>
<?
    }

    function verProyectos($configuracion) {
        //Consultamos los proyectos curriculares con su respectivo plan de estudio, y los mostramos en un <select>
        $cadena_sql_proyectos=$this->sql->cadena_sql($configuracion,"proyectos_curriculares",$this->usuario);//echo $cadena_sql_proyectos;exit;
        $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );

        if(is_array($resultado_proyectos)&&count($resultado_proyectos)>1){

        ?>
<table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>PLANES DE ESTUDIO ASOCIADOS AL USUARIO <?echo $this->usuario?></h4>
            <hr noshade class="hr">

        </td>
    </tr><br><br>
    <tr class="centrar">
        <td class="cuadro_color centrar" colspan="2">
            SELECCIONE EL PLAN DE ESTUDIO
        </td>
    </tr>
    <tr>
        <td class="cuadro_color centrar">Plan de Estudio</td>
        <td class="cuadro_color centrar">Nombre</td>
    </tr>


        <?
            for($i=0;$i<count($resultado_proyectos);$i++) {
                ?>
                    <tr>

                <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminCursosIntermediosCoordinador";
                $variable.="&opcion=mostrar";
                $variable.="&codProyecto=".$resultado_proyectos[$i][0];
                $variable.="&planEstudio=".$resultado_proyectos[$i][2];
                $variable.="&nombreProyecto=".$resultado_proyectos[$i][1];

//var_dump($_REQUEST);exit;
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                ?>
            <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_proyectos[$i][2]?></a></td>
            <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_proyectos[$i][1]?></a></td>
                </tr>
    <?

            }
        ?>

    
</table>

        <?
    }else
        {
            if(is_array($resultado_proyectos))
            {
              $this->mostrarRegistro($configuracion);
            }
            else
            {
              $this->noPlan($configuracion);
              exit;
            }

        }

    
    }

        function noPlan($configuracion) {
?>
                      <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                          <tr align="center">
                              <td class="centrar" colspan="4">
                                  <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                  <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                              </td>
                          </tr>
                          <tr align="center">
                              <td class="centrar" colspan="4">
                                  <h4>NO EXISTEN PLANES DE ESTUDIO ASOCIADOS AL USUARIO <?echo $this->usuario?></h4>
                                  <hr noshade class="hr">

                              </td>
                          </tr>
                      </table>
                  <?


}


    #Llama las funciones "verPlanEstudios", "listaNiveles" y "listaEspacios" para visualizar
    #la informacion general del Plan de Estudios y los Espacios Academicos que lo componen agrupados por niveles
    function mostrarRegistro($configuracion) {
       
        if($_REQUEST['proyecto'])
            {

                $arreglo=explode("-",$_REQUEST['proyecto']);
                $planEstudio=$arreglo[0];
                $codProyecto=$arreglo[1];
                $nombreProyecto=$arreglo[2];

                $variable=array($codProyecto,$nombreProyecto,$planEstudio);
            }else if($_REQUEST['codProyecto'] && $_REQUEST['planEstudio'])
                {
                    $codProyecto=$_REQUEST['codProyecto'];
                    $planEstudio=$_REQUEST['planEstudio'];
                    $variable=array($codProyecto,'',$planEstudio);
                }

            else{ 
                $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$this->usuario);//echo $cadena_sql;exit;
                $resultado_datosCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                $_REQUEST['planEstudio']=$planEstudio=$resultado_datosCoordinador[0][2];
                $_REQUEST['codProyecto']=$codProyecto=$resultado_datosCoordinador[0][0];
                $_REQUEST['$nombreProyecto']=$nombreProyecto=$resultado_datosCoordinador[0][1];

                $variable=array($codProyecto,$nombreProyecto,$planEstudio);
            }

            $this->menuCoordinador($configuracion,$variable);
           
    }

   



}
?>
