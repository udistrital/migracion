
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
class funcion_registroComentarioPlanAsisVice extends funcionGeneral {


//@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_registroComentarioPlanAsisVice();
        $this->log_us= new log();
        $this->formulario="registroComentarioPlanAsisVice";

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


    function formularioComentario($configuracion) {

        $id_comentario=$_REQUEST['id_comentario'];
        $planEstudio=$_REQUEST['planEstudio'];        
        $usuario=$this->usuario;

        if($id_comentario==true)
        {
            $cadena_sql_Leido=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizaLeido",$id_comentario);//echo $cadena_sql_Leido;exit;
            $resultadoLeido=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_Leido,"");//var_dump($resultadoCarrera);exit;

            $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
            $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
            $ano=$resultadoPeriodo[0][0];
            $periodo=$resultadoPeriodo[0][1];

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"PlanEstudio", $planEstudio);//echo $cadena_sql;exit;
            $resultado_informacionPlanEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_informacionPlanEstudio);exit;

            $variablesRegistro=array($usuario, date('YmdHis'), $ano, $periodo, $planEstudio, $resultado_informacionPlanEstudio[0][6] );
            $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroLogComentarioLeyo",$variablesRegistro);
            $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");
        }

        $variables=array($codEspacio,$planEstudio,$nivel,$creditos,$htd,$htc,$hta,$clasificacion,$nombreEspacio);

        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);

        $this->agregarComentario($configuracion,$variables);

        $cadena_sql_carrera=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"listaCarrera",$planEstudio);//echo $cadena_sql_carrera;exit;
        $resultadoCarrera=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_carrera,"busqueda");//var_dump($resultadoCarrera);exit;

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarComentarios",$planEstudio);//echo $cadena_sql;exit;
        $resultado_comentarios=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda");//var_dump($resultado_comentarios);exit;

        if(count($resultado_comentarios)>0) {
            ?>
<table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr>
        <td class="centrar" colspan="2">
            <hr noshade class="hr">
            <h4>Comentarios realizados anteriormente</h4>

        </td>
    </tr>
</table>
            <?
            for($i=0;$i<count($resultado_comentarios);$i++) {
                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscarPerfilNombre",$resultado_comentarios[$i][3]);//echo $cadena_sql;exit;
                $resultado_perfil=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

                ?>
<table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td class="centrar" colspan="2">
            <h4>Fecha: <?echo $resultado_comentarios[$i][8]?></h4>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="40">Usuario:</td><td class="cuadro_plano" width="15">
            <? echo $resultado_perfil[0][2].": ".$resultado_perfil[0][0]." ".$resultado_perfil[0][1] ?> </td>
    </tr>
                <?
                if ($resultado_comentarios[$i][5]==1 and $resultado_comentarios[$i][6]==1) { ?>
    <tr>
        <td class="cuadro_plano" width="40">Comentario Leido:</td><td class="cuadro_plano" width="15"><textarea name="comentario" rows="3" cols="80" readonly><?echo $resultado_comentarios[$i][7]?></textarea></td>
    </tr>

                    <? }
                else if ($resultado_comentarios[$i][5]==1 and $resultado_comentarios[$i][6]==0) { ?>
    <tr>
        <td class="cuadro_plano" width="40">Comentario:</td><td class="cuadro_plano" width="15"><textarea name="comentario" rows="3" cols="80" readonly><?echo $resultado_comentarios[$i][7]?></textarea></td>
    </tr>

                    <? }
                else if ($resultado_comentarios[$i][5]==0 and $resultado_comentarios[$i][6]==1) { ?>
    <tr>
        <td class="cuadro_plano" width="40">Comentario No Leido:</td>
        <td class="cuadro_plano" width="200"><?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variables="pagina=registroComentarioPlanAsisVice";
                            $variables.="&opcion=verComentarios";
                            $variables.="&planEstudio=".$planEstudio;
                            $variable.="&codProyecto=".$codProyecto;
                            $variables.="&id_comentario=".$resultado_comentarios[$i][0];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                                    ?>
            <a href="<?echo $pagina.$variables?>" class="centrar">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/viewrel.png" width="25" height="25" border="0">Ver

                </td>
                </tr>

                    <? }
                ?>
                <tr>
                    <td class="cuadro_plano" colspan="2">
                        <hr noshade class="hr">
                    </td>
                </tr>
</table>
            <?

            }
        }else {
            ?>
<table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr>
        <td class="centrar" colspan="2">
            <hr noshade class="hr">
            <h4>Comentarios realizados anteriormente</h4>

        </td>
    </tr>
    <tr align="center">
        <td class="cuadro_plano centrar" colspan="2">
            <h4>No existen comentarios del espacio acad&eacute;mico <?echo $codEspacio?> del plan de estudio <?echo $planEstudio?></h4>

        </td>
    </tr>
</table>
        <?
        }



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
        <td class="centrar" colspan="1" width="25%">
            
            <a href="javascript:history.back()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="35" height="35" border="0"><br>Atras
            </a>
        </td>

        <td class="centrar" colspan="2" width="50%">
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
        <td class="centrar" colspan="1" width="25%">

            <a href="javascript:history.forward()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png" width="35" height="35" border="0"><br>Adelante
            </a>
        </td>
    </tr>
</table><?
    }

    function agregarComentario($configuracion,$variables) {
        $planEstudio=$variables[1];
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"PlanEstudio", $planEstudio);//echo $cadena_sql;exit;
        $resultado_informacionPlanEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_informacionPlanEstudio);exit;
        ?>
<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo  $this->formulario?>'>
    <table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
        <tr align="center">
            <td class="centrar" colspan="2">
                <hr noshade class="hr">
                <h4>Informaci&oacute;n del Plan de Estudios</h4>
            </td>
        </tr>
        <tr>
            <td class="centrar" colspan="2">
                Plan de Estudios: <?echo $resultado_informacionPlanEstudio[0][0]?><br>
        <?echo $resultado_informacionPlanEstudio[0][1]?><br>
                Proyecto Currricular: <?echo $resultado_informacionPlanEstudio[0][6]; ?>
            </td>
        </tr>
        <tr align="center">
            <td class="centrar" colspan="2">
                <hr noshade class="hr">
                <h4>Agregar nuevo comentario</h4>
            </td>
        </tr>
        <tr>
            <td class="centrar" colspan="2">
                <textarea name="comentario" rows="4" cols="70"></textarea>
            </td>
        </tr>
        <tr>
            <td class="centrar">
                <input type="hidden" name="planEstudio" value="<?echo $resultado_informacionPlanEstudio[0][0]?>">
                <input type="hidden" name="codProyecto" value="<?echo $resultado_informacionPlanEstudio[0][6]?>">
                <input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
                <input type='hidden' name='opcion' value="confirmado">
                <input type='hidden' name='action' value='<? echo  $this->formulario ?>'>
                <input type="submit" value="Enviar">
            </td>
            <td class="centrar">
                <input type="reset" value="Borrar">
            </td>
        </tr>
    </table>
</form>
    <?
    }

    function guardarRegistros($configuracion) {
        $planEstudio=$_REQUEST['planEstudio'];
        $codProyecto=$_REQUEST['codProyecto'];
        $usuario=$this->usuario;
        $comentario=$_REQUEST['comentario'];


        if($comentario==true) {
            $variables=array($planEstudio, $codProyecto, $usuario, date('YmdHis'), $comentario);
            $cadena_sql_comentario=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"ingresarComentario", $variables);//echo $cadena_sql_comentario;exit;
            $resultadoComentario=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_comentario,"" );//var_dump($resultadoComentario);exit;

            $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
            $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
            $ano=$resultadoPeriodo[0][0];
            $periodo=$resultadoPeriodo[0][1];

            $variablesRegistro=array($usuario, date('YmdHis'), $ano, $periodo, $planEstudio, $codProyecto);
            $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroLogComentario",$variablesRegistro);
            $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");
            $band=1;

        }

        if($band==1) {
            echo "<script>alert ('El comentario del Plan de Estudios fue registrado con exito');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=registroComentarioPlanAsisVice";
            $variable.="&opcion=verComentarios";
            $variable.="&codProyecto=".$codProyecto;
            $variable.="&planEstudio=".$planEstudio;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
        else {
            echo "<script>alert ('El campo: Agregar nuevo comentario, debe ser diligenciado');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=registroComentarioPlanAsisVice";
            $variable.="&opcion=verComentarios";
            $variable.="&codProyecto=".$codProyecto;
            $variable.="&planEstudio=".$planEstudio;


            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }



    }



}
?>
