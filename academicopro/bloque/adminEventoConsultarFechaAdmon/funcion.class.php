<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_adminEventoConsultarFechaAdmon extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
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
        $this->formulario="adminEventoConsultarFechaAdmon";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }

    function consultarFechasEventos($configuracion)
    {
         $this->encabezadoModulo($configuracion);
         $this->menuAdministrador($configuracion);

         $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"periodosAcademicos",'');//echo $cadena_sql;exit;
         $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

         ?>
<table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
    <tr class="centra">
        <td class="cuadro_color">
            Seleccione el periodo a consultar
            <select name="perAcad" id="perAcad">
                    
            <?for($j=0;$j<count($resultado_periodo);$j++)
            {
                echo "<option value='".$resultado_periodo[$j][0]."-".$resultado_periodo[$j][1]."'>".$resultado_periodo[$j][0]."-".$resultado_periodo[$j][1]."</option>";
            }?>
            </select>
            
            <input type="hidden" name="opcion" value="consultar">
            <input type="hidden" name="action" value="<?echo $this->formulario?>">
            <input type="submit" value="Consultar">
        </td>
    </tr>
    </form>
</table>
         <?

         if($_REQUEST['ano'] && $_REQUEST['periodo'])
             {
                $ano=$_REQUEST['ano'];
                $periodo=$_REQUEST['periodo'];

             }else if($_REQUEST['perAcad'])
             {
                $periodoAcad=explode("-",$_REQUEST['perAcad']);
                $ano=$periodoAcad[0];
                $periodo=$periodoAcad[1];
             }else
                 {
                    for($k=0;$k<count($resultado_periodo);$k++)
                    {
                        if(trim($resultado_periodo[$k][2])=='A')
                            {
                                $ano=$resultado_periodo[$k][0];
                                $periodo=$resultado_periodo[$k][1];
                            }
                    }
                    
                 }
         $variablesFechas=array($ano,$periodo);
         ?>
<table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
         <tr>
            <td class="cuadro_color centrar">
                <font size="2"><b>Calendario Acad&eacute;mico del periodo <?echo $ano." - ".$periodo?></b></font>
            </td>
         </tr>
</table>
         <?
         $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"listarFechas",$variablesFechas);//echo $cadena_sql;exit;
         $resultado_fechas=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

         if(is_array($resultado_fechas))
             {
                for($i=0;$i<count($resultado_fechas);$i++)
                {
                    if($resultado_fechas[$i][6]!=$resultado_fechas[$i-1][6])
                        {
                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarCobertura",$resultado_fechas[$i][6]);//echo $cadena_sql;exit;
                            $resultado_cobertura=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                            ?>
                                <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                                <tr>
                                    <td class="cuadro_color centrar" colspan="6"><font size="2"><b>Cobertura : <?echo $resultado_cobertura[0][0]?> </b></font></td>
                                </tr>
                                <div id="div_<?echo $resultado_cobertura[0][0]?>">
                                <tr class="centrar">
                                    <td class="cuadro_color" width="10%">Evento</td><td class="cuadro_color" width="30%">Descripci&oacute;n</td><td class="cuadro_color" width="20%">Fecha y hora<br>Inicio</td>
                                    <td class="cuadro_color" width="20%">Fecha y hora<br>Final</td><td class="cuadro_color" width="10%">Modificar</td><td class="cuadro_color" width="10%">Borrar</td>
                                </tr>
                                
                            
                            <?
                        }
//echo "<br>".$resultado_fechas[$i][6]."-".$resultado_fechas[$i][7];
                        if(($resultado_fechas[$i][7]!=$resultado_fechas[$i-1][7]))
                            {
                                switch($resultado_fechas[$i][6])
                                {
                                    case "3":
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarFacultad",$resultado_fechas[$i][7]);//echo $cadena_sql;//exit;
                                            $resultado_facultad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                            if(is_array($resultado_facultad))
                                                {
                                                    $usuarioAfectado=$resultado_facultad[0][0];
                                                    ?>
                                                    <tr>
                                                        <td class="cuadro_color_plano centrar" colspan="6">
                                                            <font color="blue"><b><?echo $usuarioAfectado?></b></font>
                                                        </td>
                                                    </tr>
                                                    <?
                                                }
                                        break;

                                    case "4":
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarProyecto",$resultado_fechas[$i][7]);//echo $cadena_sql;exit;
                                            $resultado_proyecto=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                            if(is_array($resultado_proyecto))
                                                {
                                                    $usuarioAfectado=$resultado_proyecto[0][0]."-".$resultado_proyecto[0][1];
                                                    ?>
                                                    <tr>
                                                        <td class="cuadro_color_plano centrar" colspan="6">
                                                            <font color="blue"><b><?echo $usuarioAfectado?></b></font>
                                                        </td>
                                                    </tr>
                                                    <?
                                                }
                                        break;

                                    case "5":
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarPlanEstudio",$resultado_fechas[$i][7]);//echo $cadena_sql;exit;
                                            $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                            if(is_array($resultado_planEstudio))
                                                {
                                                    $usuarioAfectado=$resultado_planEstudio[0][0]."-".$resultado_planEstudio[0][1];
                                                    ?>
                                                    <tr>
                                                        <td class="cuadro_color_plano centrar" colspan="6">
                                                            <font color="blue"><b><?echo $usuarioAfectado?></b></font>
                                                        </td>
                                                    </tr>
                                                    <?
                                                }
                                        break;
                                        
                                }
                                
                            }

                        $fechaInicioFormato=explode(' ',$resultado_fechas[$i][4]);
                        $fechaFinalFormato=explode(' ',$resultado_fechas[$i][5]);
                        $fechaInicio=$fechaInicioFormato[0];
                        $horaInicio=$fechaInicioFormato[1];
                        $fechaFinal=$fechaFinalFormato[0];
                        $horaFinal=$fechaFinalFormato[1];

                        if($resultado_fechas[$i][2]>date('YmdHis'))
                            {
                                $band=1;
                            }else 
                                {
                                    $band=0;
                                    $mensaje="EVENTO EN PROCESO";
                                }
                                if($resultado_fechas[$i][3]<date('YmdHis'))
                                {
                                    $band=0;
                                    $mensaje="EVENTO FINALIZADO";
                                }


                                ?>
<tr>
    <td class="cuadro_plano"><?echo $resultado_fechas[$i][0]?></td>
    <td class="cuadro_plano"><?echo $resultado_fechas[$i][1]?></td>
    <td class="cuadro_plano centrar"><?echo $fechaInicio."<br>".$horaInicio?></td>
    <td class="cuadro_plano centrar"><?echo $fechaFinal."<br>".$horaFinal?></td>
    <?if($band==1)
        {
        ?>
            <td class="cuadro_plano centrar">
            <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroEventoEditarFechaAdmon";
                $variables.="&opcion=modifica";
                $variables.="&idFechaEvento=".$resultado_fechas[$i][8];
                $variables.="&nombreEvento=".$resultado_fechas[$i][0];
                $variables.="&ano=".$ano;
                $variables.="&periodo=".$periodo;
                $variables.="&idCobertura=".$resultado_fechas[$i][6];
                $variables.="&idUsuarioAfectado=".$resultado_fechas[$i][7];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                ?>
                    <a href="<?echo $pagina.$variables?>" class="centrar">
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kedit.png" width="25" height="25" border="0"><br>
                    </a>
            </td>
            <td class="cuadro_plano centrar">
                 <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroEventoBorrarFechaAdmon";
                $variables.="&opcion=confirmacion";
                $variables.="&idFechaEvento=".$resultado_fechas[$i][8];
                $variables.="&nombreEvento=".$resultado_fechas[$i][0];
                $variables.="&ano=".$ano;
                $variables.="&periodo=".$periodo;
                $variables.="&idCobertura=".$resultado_fechas[$i][6];
                $variables.="&idUsuarioAfectado=".$resultado_fechas[$i][7];
                $variables.="&fechaHoraInicio=".$fechaInicio."-".$horaInicio;
                $variables.="&fechaHoraFin=".$fechaFinal."-".$horaFinal;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                ?>
                    <a href="<?echo $pagina.$variables?>" class="centrar">
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="25" height="25" border="0"><br>
                    </a>
            </td>
</tr></div>
                                <?
        }else
            {
                ?>
<td class="cuadro_plano centrar" colspan="2"><?echo $mensaje?></td></tr>
                <?
            }
                }
               
             }else{
             ?>
<tr>
    <td class="cuadro_plano centrar">
        No existen fechas registradas para el periodo <?echo $ano." - ".$periodo?>
    </td>
</tr>
             <?

             }
    }


    function encabezadoModulo($configuracion)
    {
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variables="pagina=adminEventoConsultarFechaAdmon";
        $variables.="&opcion=consultar";
        $variables=$this->cripto->codificar_url($variables,$configuracion);

        header('"refresh:2"', "'url:".$pagina.$variables."'");
        ?>
        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr align="center">
                <td class="centrar" colspan="10">
                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png " alt="Logo Universidad">
                </td>
            </tr>
            <tr align="center">
                <td class="centrar" colspan="10">
                    <h4>FECHAS REGISTRADAS EN EL CALENDARIO ACAD&Eacute;MICO</h4>
                    <hr noshade class="hr">

                </td>
            </tr>
          
        </table>
     <?
    }

    function menuAdministrador($configuracion)
    {
        ?>
        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
        <tr align="center">
                <td class="centrar" colspan="10">
                            <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variables="pagina=registroEventoIngresarFechaAdmon";
                            $variables.="&opcion=crear";
                            $variables.="&xajax_file=archivo";
                            $variables.="&xajax=usuario";

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                            ?>
                    <a href="<?echo $pagina.$variables?>">
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kword.png" width="35" height="35" border="0"><br>Ingresar nueva fecha<br>de evento
                    </a>
                </td>
            </tr>
        </table>
        <?
    }
}

?>