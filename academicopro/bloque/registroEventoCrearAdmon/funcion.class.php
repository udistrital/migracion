<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroEventoCrearAdmon extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
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
        $this->formulario="registroEventoCrearAdmon";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    function crearEvento($configuracion) {


        ?>
<table class='contenidotabla centrar' background="<? echo
               $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png"
       style="background-attachment:fixed; background-repeat:no-repeat;
       background-position:top">
    <tr align="center">
        <td class="centrar" colspan="9">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            <img src="<?echo
                         $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png
                 " alt="Logo Universidad">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="9">
            <h4>FORMULARIO DE CREACI&Oacute;N DE EVENTO DE CALENDARIO ACAD&Eacute;MICO</h4>
            <hr noshade class="hr">
        </td>
    </tr>
            <?
            #enlace regreso  listado de planes

            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=adminEventoConsultarAdmon";
            $ruta.="&opcion=consultar";

            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            ?>
    <tr>
        <td class="centrar" colspan="9">
            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br>Atras
            </a>
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="9">
            Grupos con clasificación: <br>                        
        </td>
    </tr>     
    <tr align="center">
        <td class="cuadro_color centrar" colspan="9">
            -
        </td>
    </tr>
</table>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain'
      method='GET,POST' action='index.php' name='<?echo
              $this->formulario?>'>
    <table class='contenidotabla centrar' background="<? echo
                   $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png"
           style="background-attachment:fixed; background-repeat:no-repeat;
           background-position:top">

        <tr class="cuadro_plano centrar">
            <td class="cuadro_color centrar">
                Nombre del evento:
            </td>
            <td class="cuadro_plano">
                <input type="text" name="eventoNombre" value="<? echo $_REQUEST["eventoNombre"]?>">
            </td>
        </tr>
        <tr class="cuadro_plano centrar">
            <td class="cuadro_color centrar">
                Descripci&oacute;n del evento:
            </td>
            <td class="cuadro_plano">
                <textarea name="eventoDescripcion"><? echo $_REQUEST["eventoDescripcion"]?></textarea>
            </td>
        </tr>
        <tr class="centrar">
            <td class="cuadro_plano centrar" colspan="2">
                <input type="hidden" name="opcion" value="generar">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input name='crear' value='Crear' type='submit' >

                        <?      #enlace regreso  listado de planes

                        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=adminCalendarioAcademicoAdmon";
                        $ruta.="&opcion=consultar";

                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                        ?>
                <a href="<?= $indice.$ruta ?>">
                    <input src="<?echo $configuracion['site'].$configuracion['grafico']?>" name='crear' value='Cancelar' type='submit' >
                </a>
            </td>
        </tr>


    </table>
</form>

        <?
    }

    function generarEvento($configuracion) {
        $band=0;
        $usuario=$this->usuario;
        $eventoNombre=strtoupper($_REQUEST['eventoNombre']);
        $eventoDescripcion=strtoupper($_REQUEST["eventoDescripcion"]);

        if($_REQUEST['eventoNombre'] and $_REQUEST["eventoDescripcion"]) {
            $band=1;

            $cadena_sql_buscarEventoNombre=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "buscarEventoNombre", $eventoNombre);//echo $cadena_sql_buscarEventoNombre;exit;
            $resultadoBuscarEventoNombre=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEventoNombre,"busqueda" );

            $MayusculaEventoNombre = strtoupper($eventoNombre);
            $cadena_sql_buscarEventoOracle=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "buscarEventoOracle", $MayusculaEventoNombre);//echo $cadena_sql_buscarEventoOracle;exit;
            $resultadoBuscarEventoOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_buscarEventoOracle,"busqueda" );
            
            if($resultadoBuscarEventoNombre==true or $resultadoBuscarEventoOracle==true) {                
                $band=2;
            }
        }

        if($band==0) {
            
            echo "<script>alert ('Debe de utilizar todos los campos');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=registroEventoCrearAdmon";
            $variable.="&opcion=crear";
            $variable.="&eventoNombre=".$eventoNombre;
            $variable.="&eventoDescripcion=".$eventoDescripcion;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
            
        }
        else if($band==1)
       
        {
            $variables=array($eventoNombre, $eventoDescripcion);
            $cadena_sql_buscarEventoMayor=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "buscarEventoMayor", $variables);//echo $cadena_sql_buscarEvento;exit;
            $resultadoBuscarEventoMayor=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEventoMayor,"busqueda" );
            //echo $resultadoBuscarEventoMayor[0][0];exit;

        if($resultadoBuscarEventoMayor[0][0]<=99)
           {
            $cadena_sql_crearEventoCien=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "crearEventoCien", $variables);
            $resultadoCrearEventoCien=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_crearEventoCien,"" );

            $cadena_sql_buscarEvento=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "buscarEvento", $variables);//echo $cadena_sql_buscarEvento;exit;
            $resultadoBuscarEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEvento,"busqueda" );

            $variablesRegistro=array($usuario, date('YmdHis'), $resultadoBuscarEvento[0][0], $resultadoBuscarEvento[0][1], $resultadoBuscarEvento[0][2] );
            $cadena_sql_registroLogEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroLogEvento",$variablesRegistro);//echo $cadena_sql_registroLogEvento;exit;
            $registroLogEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroLogEvento,"");

             }
            else if ($resultadoBuscarEventoMayor[0][0]>99)
            { //echo "mayor a 99"
            $cadena_sql_crearEvento=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "crearEvento", $variables);
            $resultadoCrearEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_crearEvento,"" );

            $cadena_sql_buscarEvento=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "buscarEvento", $variables);//echo $cadena_sql_buscarEvento;exit;
            $resultadoBuscarEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEvento,"busqueda" );

            $variablesRegistro=array($usuario, date('YmdHis'), $resultadoBuscarEvento[0][0], $resultadoBuscarEvento[0][1], $resultadoBuscarEvento[0][2] );
            $cadena_sql_registroLogEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroLogEvento",$variablesRegistro);//echo $cadena_sql_registroLogEvento;exit;
            $registroLogEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroLogEvento,"");
            }

            echo "<script>alert ('El Evento ha sido registrado de forma exitosa');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminEventoConsultarAdmon";
            $variable.="&opcion=consultar";

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
           
        }
        else if($band==2) {
            
            ?>
<table class='contenidotabla centrar' background="<? echo
            $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png"
       style="background-attachment:fixed; background-repeat:no-repeat;
       background-position:top">
    <tr align="center">
        <td class="centrar" colspan="9">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            <img src="<?echo
            $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png
                 " alt="Logo Universidad">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="9">
            <h4>FORMULARIO DE CREACI&Oacute;N DE EVENTO DE CALENDARIO ACAD&Eacute;MICO</h4>
            <hr noshade class="hr">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="9">
            <h4>EL EVENTO "<? echo $eventoNombre;?>" YA EXISTE <br>¿ESTÁ SEGURO DE CREAR ESTE EVENTO?</h4>
        </td>
    </tr>
    <tr align="center">
        <td class="centrar">
            <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=registroEventoCrearAdmon";
            $variable.="&opcion=existe";
            $variable.="&eventoNombre=".$eventoNombre;
                        $variable.="&eventoDescripcion=".$eventoDescripcion;

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="40" height="40" border="0" alt="Editar Requisito">
                <br>SI
            </a>
        </td>
        <td class="centrar">
          <?
        $indice1=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta1="pagina=adminEventoConsultarAdmon";
        $ruta1.="&opcion=consultar";

        $ruta1=$this->cripto->codificar_url($ruta1,$configuracion);

          ?>
        <a href="<?= $indice1.$ruta1 ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/x.png" width="40" height="40" border="0" alt="Editar Requisito">
                <br>NO
        </a>
        </td>

    </tr>
</table>
        <?
        }

    }


    function crearEventoExistente($configuracion) {
    
        $usuario=$this->usuario;
        $eventoNombre=strtoupper($_REQUEST['eventoNombre']);
        $eventoDescripcion=strtoupper($_REQUEST["eventoDescripcion"]);

        $variables=array($eventoNombre, $eventoDescripcion);
            $cadena_sql_buscarEventoMayor=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "buscarEventoMayor", $variables);//echo $cadena_sql_buscarEvento;exit;
            $resultadoBuscarEventoMayor=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEventoMayor,"busqueda" );
           

        if($resultadoBuscarEventoMayor[0][0]<=99)
           {
            $cadena_sql_crearEventoCien=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "crearEventoCien", $variables);
            $resultadoCrearEventoCien=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_crearEventoCien,"" );

            $cadena_sql_buscarEvento=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "buscarEvento", $variables);//echo $cadena_sql_buscarEvento;exit;
            $resultadoBuscarEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEvento,"busqueda" );

            $variablesRegistro=array($usuario, date('YmdHis'), $resultadoBuscarEvento[0][0], $resultadoBuscarEvento[0][1], $resultadoBuscarEvento[0][2] );
            $cadena_sql_registroLogEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroLogEvento",$variablesRegistro);//echo $cadena_sql_registroLogEvento;exit;
            $registroLogEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroLogEvento,"");

             }
            else if ($resultadoBuscarEventoMayor[0][0]>99)
            { 
            $cadena_sql_crearEvento=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "crearEvento", $variables);
            $resultadoCrearEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_crearEvento,"" );

            $cadena_sql_buscarEvento=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "buscarEvento", $variables);//echo $cadena_sql_buscarEvento;exit;
            $resultadoBuscarEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEvento,"busqueda" );

            $variablesRegistro=array($usuario, date('YmdHis'), $resultadoBuscarEvento[0][0], $resultadoBuscarEvento[0][1], $resultadoBuscarEvento[0][2] );
            $cadena_sql_registroLogEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroLogEvento",$variablesRegistro);//echo $cadena_sql_registroLogEvento;exit;
            $registroLogEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroLogEvento,"");
            }


        echo "<script>alert ('El Evento ha sido registrado de forma exitosa');</script>";

        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variable="pagina=adminEventoConsultarAdmon";
        $variable.="&opcion=consultar";

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";

    }
}

?>