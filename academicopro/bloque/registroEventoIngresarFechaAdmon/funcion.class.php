<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroEventoIngresarFechaAdmon extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
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
        $this->formulario="registroEventoIngresarFechaAdmon";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $this->calendarioIni="muestraCalendario('".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."','".$this->formulario."','fecIni','".date('m')."','".date('Y')."')";
    }

    function formularioCrear($configuracion)
    {
        $this->encabezadoModulo($configuracion);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEventosActivos",'');//echo $cadena_sql;exit;
        $resultado_eventosActivos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_eventosActivos);exit;
        
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarUsuariosAfectados",'');//echo $cadena_sql;exit;
        $resultado_usuariosAfectados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_eventosActivos);exit;
        
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"periodosAcademicos",'');//echo $cadena_sql;exit;
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );//var_dump($resultado_periodo);exit;
?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <body>
            
            <tr>
                <td class="cuadro_color" colspan="2" width="10%">Evento</td>
                <td colspan="2">
                    <select name="evento" id="evento" style="width:400px">
                        <?
                        for($i=0;$i<count($resultado_eventosActivos);$i++)
                        {
                            echo "<option value=".$resultado_eventosActivos[$i][0].">".$resultado_eventosActivos[$i][1]."</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="cuadro_color" colspan="2" width="10%">Periodo</td>
                <td colspan="2">
                    <select name="periodo" id="periodo">
                        <?
                        for($j=0;$j<count($resultado_periodo);$j++)
                        {
                            echo "<option value=".$resultado_periodo[$j][0]."-".$resultado_periodo[$j][1].">".$resultado_periodo[$j][0]."-".$resultado_periodo[$j][1]."</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="cuadro_color">Fecha Inicial</td>
                <td class="cuadro_color">
                    <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                    <input type="text" name="fechaInicial" value='<?echo date('d/n/Y')?>' tabindex='<? echo $tab++ ?> ' maxlength='25' size="10" readonly="readonly">
                    <a href="javascript:muestraCalendario('<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>','<? echo $this->formulario;?>','fechaInicial')">
				<img border="0" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/cal.png"?>" width="24" height="24" alt="DD-MM-YYYY"></a>
                </td>
                <td class="cuadro_color derecha">Hora</td>
                <td>
                    <select name="horaIni" id="horaIni">
                       <?
                       for($k=00;$k<24;$k++)
                       {
                          echo "<option value=".$k.">$k</option>";
                       }
                       ?>
                    </select>
                    <select name="minIni" id="minIni">
                       <option value="00">00</option>
                       <option value="10">10</option>
                       <option value="20">20</option>
                       <option value="30">30</option>
                       <option value="40">40</option>
                       <option value="50">50</option>
                    </select>
                    <?/* <select name="meridianoFin" id="meridianoFin">
                        <option value="1">a.m.</option>
                        <option value="2">p.m.</option>
                    </select>*/
                    ?>
                </td>
            </tr>
            <tr>
                <td class="cuadro_color">Fecha Final</td>
                <td class="cuadro_color">
                    <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                    <input type="text" name="fechaFinal" value='<?echo date('d/n/Y')?>' tabindex='<? echo $tab++ ?> ' maxlength='25' size="10" readonly="readonly">
                    <a href="javascript:muestraCalendario('<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>','<? echo $this->formulario;?>','fechaFinal')">
				<img border="0" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/cal.png"?>" width="24" height="24" alt="DD-MM-YYYY"></a>
                </td>
                <td class="cuadro_color derecha">Hora</td>
                <td>
                    <select name="horaFin" id="horaFin">
                       <?
                       for($k=00;$k<24;$k++)
                       {
                          echo "<option value=".$k.">$k</option>";
                       }
                       ?>
                    </select>
                    <select name="minFin" id="minFin">
                       <option value="00">00</option>
                       <option value="10">10</option>
                       <option value="20">20</option>
                       <option value="30">30</option>
                       <option value="40">40</option>
                       <option value="50">50</option>
                    </select>
                   <?/* <select name="meridianoFin" id="meridianoFin">
                        <option value="1">a.m.</option>
                        <option value="2">p.m.</option>
                    </select>*/
                    ?>
                </td>
            </tr>
            <tr>
                <td class="cuadro_color">Usuario Afectado</td>
                <td>
                    <select name="usuario" id="usuario" onchange="xajax_usuario(document.getElementById('usuario').value)">
                        <?
                        for($m=0;$m<count($resultado_usuariosAfectados);$m++)
                        {
                            echo "<option value=".$resultado_usuariosAfectados[$m][0].">".$resultado_usuariosAfectados[$m][1]."</option>";
                        }
                        ?>
                    </select>
                </td>
                <td width="70%" colspan="2">
                    <div id="div_seleccion">
                       
                    </div>
                </td>
            </tr>
            </body>
        </table>
   

        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr class="centrar" >
                <td class="cuadro_color centrar">
                    <input type="hidden" name="opcion" value="guardar">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <a href="#"><input type="submit" name="Guardar" value="Guardar">
                </td>
                <td class="cuadro_color centrar" >
                    <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=adminEventoConsultarFechaAdmon";
			$variable.="&opcion=consultar";

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
                    <a href="<?echo $pagina.$variable?>"><input type="button" name="Cancelar" value="Cancelar"></a>
                </td>
            </tr>
        </table>
 </form>
<?
        

         
    
    }

     function encabezadoModulo($configuracion)
    {
        ?>
        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr align="center">
                <td class="centrar" colspan="6">
                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png " alt="Logo Universidad">
                </td>
            </tr>
            <tr align="center">
                <td class="centrar" colspan="6">
                    <h4>CREACI&Oacute;N DE FECHAS DE EVENTOS EN EL CALENDARIO ACAD&Eacute;MICO</h4>
                    <hr noshade class="hr">

                </td>
            </tr>
            <tr>
                <td class="centrar" colspan="2">
                    <a href="javascript:history.back();" >
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png " width="35" height="35" alt="Atras" border="0">
                    </a>
                </td>
                <td class="centrar" colspan="2">
                    <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=adminEventoConsultarFechaAdmon";
			$variable.="&opcion=consultar";

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$variable=$this->cripto->codificar_url($variable,$configuracion);
                    ?>
                    <a href="<?echo $pagina.$variable?>">
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png " border="0" width="35" height="35" alt="Atras">
                    </a>
                </td>
                <td class="centrar" colspan="2">
                    <a href="javascript:history.forward();">
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png " width="35" height="35" alt="Atras" border="0">
                    </a>
                </td>
            </tr>

        </table>
     <?
    }

    function guardarFecha($configuracion)
    {
        $valoresInicio = explode ("/", $_REQUEST['fechaInicial']);
        $diaIni=$valoresInicio[0];
        if(strlen($valoresInicio[0])<=1)
                    {
                        $diaIni="0".$valoresInicio[1];
                    }
        
        $mesIni=$valoresInicio[1];
        if(strlen($valoresInicio[1])<=1)
                    {
                        $mesIni="0".$valoresInicio[1];
                    }
        $anoIni=$valoresInicio[2];
        $valoresFinal = explode ("/", $_REQUEST['fechaFinal']);
        $diaFin=$valoresFinal[0];
        if(strlen($valoresFinal[0])<=1)
                    {
                        $diaFin="0".$valoresFinal[1];
                    }
        $mesFin=$valoresFinal[1];
        if(strlen($valoresFinal[1])<=1)
                    {
                        $mesFin="0".$valoresFinal[1];
                    }
        $anoFin=$valoresFinal[2];
        $periodoAcad = explode ("-", $_REQUEST['periodo']);
        $ano=$periodoAcad[0];
        $periodo=$periodoAcad[1];

        
                if(strlen($_REQUEST['horaIni'])<=1)
                    {
                        $horaIni="0".$_REQUEST['horaIni'];
                    }else
                        {
                        $horaIni=$_REQUEST['horaIni'];
                    }
                if(strlen($_REQUEST['horaFin'])<=1)
                    {
                        $horaFin="0".$_REQUEST['horaFin'];
                    }else
                        {
                            $horaFin=$_REQUEST['horaFin'];
                        }

                
                
//echo $diaIni.$mesIni.$anoIni."<br>".date('dmY')."<br>".$horaIni.$_REQUEST['minIni']."<br>".date('Hi')."<br>".date('YmdHis');exit;
//echo $anoIni.$mesIni.$diaIni."<br>".date('Ymd');exit;
        if($anoIni.$mesIni.$diaIni>$anoFin.$mesFin.$diaFin)
            {
                echo "<script>alert(' ¡ Error ! La fecha inicial no puede ser mayor a la fecha final')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=registroEventoIngresarFechaAdmon";
                $variable.="&opcion=crear";
                
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
                exit;
            }else if($anoIni.$mesIni.$diaIni < date('Ymd'))
                {
                    echo "<script>alert(' ¡ Error ! La fecha y hora inicial es menor a la fecha y hora actual')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroEventoIngresarFechaAdmon";
                    $variable.="&opcion=crear";

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }else if(($anoIni.$mesIni.$diaIni == date('Ymd'))&&($horaIni.$_REQUEST['minIni'] < date('Hi')))
                {
                    echo "<script>alert(' ¡ Error ! La fecha inicial es igual a la fecha actual pero la hora inicial es menor a la hora actual')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroEventoIngresarFechaAdmon";
                    $variable.="&opcion=crear";

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                   
                }else if(($diaIni.$mesIni.$anoIni==$diaFin.$mesFin.$anoFin)&&($horaIni.$_REQUEST['minIni']>$horaFin.$_REQUEST['minFin']))
                    {
                        echo "<script>alert(' ¡ Error ! La fecha inicial es igual a la fecha final pero la hora inicial es mayor a la hora final')</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroEventoIngresarFechaAdmon";
                        $variable.="&opcion=crear";

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }

        $variablesFecha=array($ano,$periodo,$_REQUEST['evento'],$anoIni.$mesIni.$diaIni.$horaIni.$_REQUEST['minIni']."00",$anoFin.$mesFin.$diaFin.$horaFin.$_REQUEST['minFin']."00",$_REQUEST['usuario'],'1');
        
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"insertarFecha",$variablesFecha);//echo $cadena_sql;exit;
        $resultado_fechaInsertada=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_eventosActivos);exit;

        if($resultado_fechaInsertada==true)
            {
                if($_REQUEST['proyecto']!=null)
                    {
                        $descProyecto = explode (" - ", $_REQUEST['proyecto']);
                        $usuarioAfectado=$descProyecto[0];
                        $descUsuario=$descProyecto[1];

                    }else if($_REQUEST['facultad']!=null)
                    {
                        $descProyecto = explode (" - ", $_REQUEST['facultad']);
                        $usuarioAfectado=$descProyecto[0];
                        $descUsuario=$descProyecto[1];
                    }else if($_REQUEST['planEstudio']!=null)
                    {
                        $descProyecto = explode (" - ", $_REQUEST['planEstudio']);
                        $usuarioAfectado=$descProyecto[0];
                        $descUsuario=$descProyecto[1];
                    }else if($_REQUEST['grupo']!=null)
                    {
                        $descProyecto = explode (" - ", $_REQUEST['grupo']);
                        $usuarioAfectado=$descProyecto[0];
                        $descUsuario=$descProyecto[1];
                    }else
                        {
                            $usuarioAfectado=$_REQUEST['usuario'];

                            switch($usuarioAfectado)
                            {
                                case "1":
                                    $descUsuario="Calendario";
                                    break;
                                case "2":
                                    $descUsuario="General";
                                    break;
                                case "6":
                                    $descUsuario="Grupo";
                                    break;
                            }

                        }
                
                

                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarIDFecha",$variablesFecha);//echo $cadena_sql;exit;
                $resultado_idFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_eventosActivos);exit;

                $variablesUsuario=array($resultado_idFecha[0][0],$_REQUEST['usuario'],$usuarioAfectado,$descUsuario);

                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"insertarUsuario",$variablesUsuario);//echo $cadena_sql;exit;
                $resultado_usuarioInsertado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_eventosActivos);exit;

                if($resultado_usuarioInsertado==true)
                    {

                        $variablesLog=array($this->usuario,date('YmdGis'),'28','Creo nueva fecha para calendario académico',$ano."-".$periodo.",".$_REQUEST['usuario'].",".$usuarioAfectado.",".$descUsuario,$usuarioAfectado);

                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"logEventos",$variablesLog);//echo $cadena_sql;exit;
                        $resultado_logInsertado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_eventosActivos);exit;

                        echo "<script>alert('La fecha se creo exitosamente en el calendario académico')</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroEventoIngresarFechaAdmon";
                        $variable.="&opcion=crear";

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";

                    }else
                        {
                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrarFecha",$variablesFecha);//echo $cadena_sql;exit;
                            $resultado_logInsertado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_eventosActivos);exit;

                            echo "<script>alert(' ¡ Error ! La base de datos se encuentra ocupada, intente mas tarde')</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=registroEventoIngresarFechaAdmon";
                            $variable.="&opcion=crear";

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                        }
            }else
                {
                        echo "<script>alert(' ¡ Error ! La base de datos se encuentra ocupada, intente mas tarde')</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroEventoIngresarFechaAdmon";
                        $variable.="&opcion=crear";

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                }


    }
    }

?>