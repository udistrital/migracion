
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
class funcion_registroAgregarComentarioEspacioAsisVice extends funcionGeneral {
    

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_registroAgregarComentarioEspacioAsisVice();
        $this->log_us= new log();
        $this->formulario="registroAgregarComentarioEspacioAsisVice";

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

        $variables=array($codEspacio,$planEstudio,$nivel,$creditos,$htd,$htc,$hta,$clasificacion,$nombreEspacio);
        $codProyecto = (isset($codProyecto)?$codProyecto:'');
        $nombreProyecto = (isset($nombreProyecto)?$nombreProyecto:'');
        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);

        $this->agregarComentario($configuracion,$variables);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarComentarios",$variables);//echo $cadena_sql;exit;
        $resultado_comentarios=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda");//var_dump($resultado_comentarios);exit;

        if(count($resultado_comentarios)>0)
            {
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizarEstadoComentario",$variables);//echo $cadena_sql;exit;
            $resultado_Actualizarcomentarios=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"");//var_dump($resultado_comentarios);exit;
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
                for($i=0;$i<count($resultado_comentarios);$i++)
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscarPerfilNombre",$resultado_comentarios[$i][5]);//echo $cadena_sql;exit;
                    $resultado_perfil=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;
                    
                    ?>
                    <table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr align="center">
                            <td class="centrar" colspan="2">
                                <h4>Fecha: <?echo $resultado_comentarios[$i][10]?></h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="cuadro_plano" width="40">Perfil:</td><td class="cuadro_plano" width="15"><? echo $resultado_perfil[0][2].": ".$resultado_perfil[0][0]." ".$resultado_perfil[0][1] ?></td>
                        </tr>
                        <tr>
                            <td class="cuadro_plano" width="40">Comentario:</td><td class="cuadro_plano" width="15"><textarea name="comentario" rows="3" cols="80" readonly><?echo $resultado_comentarios[$i][9]?></textarea></td>
                        </tr>
                        <tr>
                            <td class="cuadro_plano" colspan="2">
                            <hr noshade class="hr">
                            </td>
                        </tr>
                    </table>
                    <?
                    
                }
            }else
                {
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

<table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
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
            <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=adminAprobarEspacioPlan";
            $variables.="&opcion=mostrar";
            $variables.="&planEstudio=".$planEstudio;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
                        $variables=$this->cripto->codificar_url($variables,$configuracion);
                        ?>
                <a href="<?echo $pagina.$variables?>">
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
            <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroAdministrarPlanCoordinador";
            $variables.="&opcion=verProyectos";

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
                        $variables=$this->cripto->codificar_url($variables,$configuracion);
                        ?>
                <a href="<?//echo $pagina.$variables?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png" width="35" height="35" border="0"><br>Adelante
                </a>

        </td>
    </tr>
</table><?
                }

   function agregarComentario($configuracion,$variables)
    {
       $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");//echo $cadena_sql;exit;
       $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;
       ?>
       <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo  $this->formulario?>'>
        <table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr align="center">
                            <td class="centrar" colspan="2">
                                <hr noshade class="hr">
                                <h4>Informaci&oacute;n del Espacio Acad&eacute;mico</h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="izquierda" width="50%" >
                                <b>C&oacute;digo E.A.:</b> <?echo $variables[0]?><br>
                                <b>Nivel:</b> <?echo $variables[2]?><br>
                                <b>Nro Cr&eacute;ditos:</b> <?echo $variables[3]?><br>
                                <b>Fecha:</b> <?echo date('d/m/Y')?><br>
                                
                            </td>
                            <td class="izquierda" width="50%" >
                                <b>Nombre E.A.:</b> <?echo $variables[8]?><br>
                                <?
                                    for($i=0;$i<count($resultado_clasificacion);$i++)
                                    {
                                        if($resultado_clasificacion[$i][0]==$variables[7])
                                            {
                                                ?>
                                                <b>Clasificaci&oacute;n:</b> <?echo $resultado_clasificacion[$i][1]?><br>
                                                <?
                                            }
                                    }
                                    ?>
                                <b>H.T.D:</b> <?echo $variables[4]?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    <b>H.T.C:</b> <?echo $variables[5]?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   <b>H.T.A:</b> <?echo $variables[6]?><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                            <td class="centrar" width="50%">
                                <input type="hidden" name="planEstudio" value="<?echo $variables[1]?>">
                                <input type="hidden" name="nombreEspacio" value="<?echo $variables[8]?>">
                                <input type="hidden" name="clasificacion" value="<?echo $variables[7]?>">
                                <input type="hidden" name="creditos" value="<?echo $variables[3]?>">
                                <input type="hidden" name="nivel" value="<?echo $variables[2]?>">
                                <input type="hidden" name="htd" value="<?echo $variables[4]?>">
                                <input type="hidden" name="htc" value="<?echo $variables[5]?>">
                                <input type="hidden" name="hta" value="<?echo $variables[6]?>">
                                <input type="hidden" name="codEspacio" value="<?echo $variables[0]?>">
                                <input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
                                <input type='hidden' name='opcion' value="confirmado">
                                <input type='hidden' name='action' value='<? echo  $this->formulario ?>'>
                                <input type="submit" value="Enviar">
                            </td>
                            <td class="centrar" width="50%">
                                <input type="reset" value="Borrar">
                            </td>
                        </tr>
        </table>
       </form>
       <?
    }

    function guardarRegistros($configuracion)
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
        $comentario=$_REQUEST['comentario'];

        if($comentario!=NULL){
        
        $variables=array($codEspacio,$planEstudio,$nivel,$creditos,$htd,$htc,$hta,$clasificacion,$nombreEspacio);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"encabezado",$variables);//echo $cadena_sql;exit;
        $resultado_encabezado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

        if($resultado_encabezado!=NULL)
            {
                for($q=0;$q<count($resultado_encabezado);$q++)
                {
                    $nombreEncabezado=$resultado_encabezado[$q][1];
                    $codProyecto=$resultado_encabezado[$q][0];
                }
            }else
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"proyectoCurricular",$variables);//echo $cadena_sql;exit;
                    $resultado_proyecto=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

                    for($w=0;$w<count($resultado_proyecto);$w++)
                        {
                            $nombreProyecto=$resultado_proyecto[$w][1];
                            $codProyecto=$resultado_proyecto[$w][0];
                            $nombreEncabezado='';
                        }
                }

       
                        $leidoCoord=0;
                        $leidoVice=1;
                   
                   //echo date('d/m/Y H:m');exit;
       $registrar=array($codEspacio,$nombreEncabezado,$planEstudio,$codProyecto,$this->usuario,date('YmdHis'),$leidoVice,$leidoCoord,$comentario);

       $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"insertarComentario",$registrar);//echo $cadena_sql;exit;
       $resultado_comentario=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;
       
       if($resultado_comentario==true)
           {
                    
                    echo "<script type='text/javascript'>alert('Se agrego el comentario con exito para el espacio académico ".$codEspacio." ')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroAgregarComentarioEspacioAsisVice";
                    $variables.="&opcion=verComentarios";
                    $variables.="&codEspacio=".$codEspacio;
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&nivel=".$nivel;
                    $variables.="&creditos=".$creditos;
                    $variables.="&htd=".$htd;
                    $variables.="&htc=".$htc;
                    $variables.="&hta=".$hta;
                    $variables.="&clasificacion=".$clasificacion;
                    $variables.="&nombreEspacio=".$nombreEspacio;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    echo "<script>location.replace('".$pagina.$variables."')</script>";
            }else
                {
                    echo "<script>alert('La base de datos se encuentra ocupada, por favor intente mas tarde, o comuniquese con la Oficina Asesora de Sistemas ')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroAgregarComentarioEspacioAsisVice";
                    $variables.="&opcion=verComentarios";
                    $variables.="&codEspacio=".$codEspacio;
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&nivel=".$nivel;
                    $variables.="&creditos=".$creditos;
                    $variables.="&htd=".$htd;
                    $variables.="&htc=".$htc;
                    $variables.="&hta=".$hta;
                    $variables.="&clasificacion=".$clasificacion;
                    $variables.="&nombreEspacio=".$nombreEspacio;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    echo "<script>location.replace('".$pagina.$variables."')</script>";
                }

    }

    else
        {
            echo "<script>alert('Debe agregar un comentario para este espacio académico ')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroAgregarComentarioEspacioAsisVice";
                    $variables.="&opcion=verComentarios";
                    $variables.="&codEspacio=".$codEspacio;
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&nivel=".$nivel;
                    $variables.="&creditos=".$creditos;
                    $variables.="&htd=".$htd;
                    $variables.="&htc=".$htc;
                    $variables.="&hta=".$hta;
                    $variables.="&clasificacion=".$clasificacion;
                    $variables.="&nombreEspacio=".$nombreEspacio;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    echo "<script>location.replace('".$pagina.$variables."')</script>";
        }

    }

}
?>
