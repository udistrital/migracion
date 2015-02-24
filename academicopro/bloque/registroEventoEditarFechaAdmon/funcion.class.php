<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroEventoEditarFechaAdmon extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
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
        $this->formulario="registroEventoEditarFechaAdmon";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $this->calendarioIni="muestraCalendario('".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."','".$this->formulario."','fecIni','".date('m')."','".date('Y')."')";
    }

    function formularioCrear($configuracion)
    {
        $idFechaEvento=$_REQUEST['idFechaEvento'];
        $nombreEvento=$_REQUEST['nombreEvento'];
        $ano=$_REQUEST['ano'];
        $periodo=$_REQUEST['periodo'];
        $idCobertura=$_REQUEST['idCobertura'];
        $idUsuarioAfectado=$_REQUEST['idUsuarioAfectado'];
        $this->encabezadoModulo($configuracion);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEventosActivos",'');//echo $cadena_sql;exit;
        $resultado_eventosActivos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_eventosActivos);exit;
        
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarUsuariosAfectados",'');//echo $cadena_sql;exit;
        $resultado_usuariosAfectados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_usuariosAfectados);exit;

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarUnaFecha", $idFechaEvento);//echo $cadena_sql;exit;
        $resultado_unaFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_unaFecha);exit;
       // echo $resultado_unaFecha[0][0];
        $diaIni=substr($resultado_unaFecha[0][0],6,2);
        $mesIni=substr($resultado_unaFecha[0][0],4,2);
        $yearIni=substr($resultado_unaFecha[0][0],0,4);
        $diaFinal=substr($resultado_unaFecha[0][1],6,2);
        $mesFinal=substr($resultado_unaFecha[0][1],4,2);
        $yearFinal=substr($resultado_unaFecha[0][1],0,4);
       
?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <body>           
            <tr class="cuadro_plano">
                <td class="cuadro_color" colspan="2" width="40%">Evento:</td>
                <td class="cuadro_color" colspan="2"><? echo $nombreEvento; ?> </td>
            </tr>
            <tr class="cuadro_plano">
                <td class="cuadro_color" colspan="2" width="40%">Periodo:</td>
                <td class="cuadro_color" colspan="2"><? echo $ano."-".$periodo; ?> </td>
            </tr>
            <tr class="cuadro_plano">
                <td class="cuadro_color">Fecha Inicial:</td>
                <td class="cuadro_color">
                    <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                    <input type="text" name="fechaInicial" value='<?echo $diaIni."/".$mesIni."/".$yearIni?>' tabindex='<? echo $tab++ ?> ' maxlength='25' size="10" readonly="readonly">
                    <a href="javascript:muestraCalendario('<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>','<? echo $this->formulario;?>','fechaInicial')">
				<img border="0" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/cal.png"?>" width="24" height="24" alt="DD-MM-YYYY"></a>
                </td>
                <td class="cuadro_color">Hora:</td>
                <td class="cuadro_color">
                    <select name="horaIni" id="horaIni">
                       <?   

                        
                        $horaIni=substr($resultado_unaFecha[0][0],8,2);
                         for($k=0;$k<24;$k++)
                         {
                           if($k<10)
                            {
                             if($k==$horaIni)
                            {
                            echo "<option value=0".$k." selected='selected'>$k</option>";
                            }
                            else
                            {
                            echo "<option value=0".$k.">$k</option>";
                            }
                            }
                            else
                            {
                            if($k==$horaIni)
                            {
                            echo "<option value=".$k." selected='selected'>$k</option>";
                            }
                            else
                            {
                            echo "<option value=".$k.">$k</option>";
                            }
                            }
                         }

                       ?>
                    </select>
                    <select name="minIni" id="minIni">
                       <?
                       $minIni=substr($resultado_unaFecha[0][0],10,2);
                       for($k=00;$k<60;$k=$k+10)
                       {
                         if($k==00)
                           {
                            echo "<option value='00'>00</option>";
                           }
                         else if($k==$minIni)
                          {
                          echo "<option value=".$k." selected='selected'>$k</option>";
                          }
                          else
                          {
                          echo "<option value=".$k.">$k</option>";
                          }
                       }
                       ?>
                    </select>                   
                </td>
            </tr>
            <tr class="cuadro_plano">
                <td class="cuadro_color">Fecha Final:</td>
                <td class="cuadro_color">
                    <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                    <input type="text" name="fechaFinal" value='<?echo $diaFinal."/".$mesFinal."/".$yearFinal?>' tabindex='<? echo $tab++ ?> ' maxlength='25' size="10" readonly="readonly">
                    <a href="javascript:muestraCalendario('<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>','<? echo $this->formulario;?>','fechaFinal')">
				<img border="0" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/cal.png"?>" width="24" height="24" alt="DD-MM-YYYY"></a>
                </td>
                <td class="cuadro_color">Hora:</td>
                <td class="cuadro_color">                    
                    <select name="horaFin" id="horaFin">                        
                       <?             
                        $horaFin=substr($resultado_unaFecha[0][1],8,2);
                         for($k=0;$k<24;$k++)
                         {
                           if($k<10)
                            {
                            if($k==$horaFin)
                            {
                            echo "<option value=0".$k." selected='selected'>$k</option>";
                            }
                            else
                            {
                            echo "<option value=0".$k.">$k</option>";
                            }
                            }
                            else
                            {
                             if($k==$horaFin)
                            {
                            echo "<option value=".$k." selected='selected'>$k</option>";
                            }
                            else
                            {
                            echo "<option value=".$k.">$k</option>";
                            }
                            }
                         }                         
                       ?>
                    </select>
                    <select name="minFin" id="minFin">
                       <?
                       $minFin=substr($resultado_unaFecha[0][1],10,2);
                       for($k=00;$k<60;$k=$k+10)
                       {
                         if($k==00)
                           {
                            echo "<option value='00'>00</option>";
                           }
                        else if($k==$minFin)
                          {
                          echo "<option value=".$k." selected='selected'>$k</option>";
                          }
                         
                          else
                          {
                          echo "<option value=".$k.">$k</option>";
                          }
                       }
                       ?>
                    </select>                    
                </td>
            </tr>
            <tr class="cuadro_plano">
                <td class="cuadro_color">Usuario Afectado:</td>
                <td class="cuadro_color">
                        <?
                        
                        if($resultado_usuariosAfectados==true)
                        { 
                            $a=$idUsuarioAfectado-1;
                            echo $resultado_usuariosAfectados[$a][1];
                        }
                         
                        ?>
                   </td>
                <td class="cuadro_color" width="70%" colspan="2">
                    <div id="div_seleccion">
                       
                    </div>
                </td>
            </tr>
            </body>
        </table>
   

        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
            <tr class="centrar" >
                <td class="cuadro_color centrar">
                    <input type="hidden" name="evento" value=<? echo $resultado_unaFecha[0][2]; ?>>
                    <input type="hidden" name="idCobertura" value=<? echo $_REQUEST['idCobertura']; ?>>
                    <input type="hidden" name="idFechaEvento" value=<? echo $idFechaEvento;  ?>>
                    <input type="hidden" name="periodo" value=<? echo $ano."-".$periodo;  ?>>
                    <input type="hidden" name="usuario" value=<? echo $idUsuarioAfectado;  ?>>
                    <input type="hidden" name="descUsuario" value=<? echo $resultado_usuariosAfectados[$a][1];  ?>>
                    <input type="hidden" name="opcion" value="editar">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <a href="#"><input type="submit" name="Actualizar" value="Actualizar">
                    </a>
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
                <td class="centrar" colspan="4">
                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png " alt="Logo Universidad">
                </td>
            </tr>
            <tr align="center">
                <td class="centrar" colspan="4">
                    <h4>CREACI&Oacute;N DE FECHAS DE EVENTOS EN EL CALENDARIO ACAD&Eacute;MICO</h4>
                    <hr noshade class="hr">

                </td>
            </tr>

        </table>
     <?
    }

    function editarFecha($configuracion)
    {
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEvento",$_REQUEST['evento']);//echo $cadena_sql;exit;
        $resultado_evento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_eventosActivos);exit;
       
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
            
        
        
        if($anoIni.$mesIni.$diaIni>$anoFin.$mesFin.$diaFin)
            {
                echo "<script>alert(' ¡ Error ! La fecha inicial no puede ser mayor a la fecha final')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=registroEventoEditarFechaAdmon";
                $variable.="&opcion=modifica";
                $variable.="&idFechaEvento=".$_REQUEST['idFechaEvento'];
                $variable.="&evento=".$_REQUEST['evento'];
                $variable.="&nombreEvento=".$resultado_evento[0][1];
                $variable.="&ano=".$_REQUEST['ano'];
                $variable.="&periodo=".$_REQUEST['periodo'];
                $variable.="&idCobertura=".$_REQUEST['idCobertura'];
                $variable.="&idUsuarioAfectado=".$_REQUEST['usuario'];
                $variable.="&descUsuario=".$_REQUEST["descUsuario"];
                
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
                exit;
            }else if($anoIni.$mesIni.$diaIni < date('Ymd'))
                {
                    echo "<script>alert(' ¡ Error ! La fecha y hora inicial es menor a la fecha y hora actual')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroEventoEditarFechaAdmon";
                    $variable.="&opcion=modifica";
                    $variable.="&idFechaEvento=".$_REQUEST['idFechaEvento'];
                    $variable.="&evento=".$_REQUEST['evento'];
                    $variable.="&nombreEvento=".$resultado_evento[0][1];
                    $variable.="&ano=".$_REQUEST['ano'];
                    $variable.="&periodo=".$_REQUEST['periodo'];
                    $variable.="&idCobertura=".$_REQUEST['idCobertura'];
                    $variable.="&idUsuarioAfectado=".$_REQUEST['usuario'];
                    $variable.="&descUsuario=".$_REQUEST["descUsuario"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }else if(($anoIni.$mesIni.$diaIni == date('Ymd'))&&($horaIni.$_REQUEST['minIni'] < date('Hi')))
                {
                    echo "<script>alert(' ¡ Error ! La fecha inicial es igual a la fecha actual pero la hora inicial es menor a la hora actual')</script>";

                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroEventoEditarFechaAdmon";
                    $variable.="&opcion=modifica";
                    $variable.="&idFechaEvento=".$_REQUEST['idFechaEvento'];
                    $variable.="&evento=".$_REQUEST['evento'];
                    $variable.="&nombreEvento=".$resultado_evento[0][1];
                    $variable.="&ano=".$_REQUEST['ano'];
                    $variable.="&periodo=".$_REQUEST['periodo'];
                    $variable.="&idCobertura=".$_REQUEST['idCobertura'];
                    $variable.="&idUsuarioAfectado=".$_REQUEST['usuario'];
                    $variable.="&descUsuario=".$_REQUEST["descUsuario"];


                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                   
                }else if(($diaIni.$mesIni.$anoIni==$diaFin.$mesFin.$anoFin)&&($horaIni.$_REQUEST['minIni']>$horaFin.$_REQUEST['minFin']))
                    {
                        echo "<script>alert(' ¡ Error ! La fecha inicial es igual a la fecha final pero la hora inicial es mayor a la hora final')</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroEventoEditarFechaAdmon";
                        $variable.="&opcion=modifica";
                        $variable.="&idFechaEvento=".$_REQUEST['idFechaEvento'];
                        $variable.="&evento=".$_REQUEST['evento'];
                        $variable.="&nombreEvento=".$resultado_evento[0][1];
                        $variable.="&ano=".$_REQUEST['ano'];
                        $variable.="&periodo=".$_REQUEST['periodo'];
                        $variable.="&idCobertura=".$_REQUEST['idCobertura'];
                        $variable.="&idUsuarioAfectado=".$_REQUEST['usuario'];
                        $variable.="&descUsuario=".$_REQUEST["descUsuario"];


                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }

       $variablesFecha=array($ano,$periodo,$_REQUEST['evento'],$anoIni.$mesIni.$diaIni.$horaIni.$_REQUEST['minIni']."00",$anoFin.$mesFin.$diaFin.$horaFin.$_REQUEST['minFin']."00",$_REQUEST['idCobertura'],'1');
     
       $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"insertarFecha",$variablesFecha);//echo $cadena_sql;exit;
       $resultado_fechaInsertada=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_eventosActivos);exit;
             
        if($resultado_fechaInsertada==true)
            {
                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarIDFecha",$variablesFecha);//echo $cadena_sql;exit;
                $resultado_idFecha=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_eventosActivos);exit;

                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"modificarFecha",$_REQUEST['idFechaEvento']);//echo $cadena_sql;exit;
                $resultado_fechaModificada=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_eventosActivos);exit;

                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarUsuario",$_REQUEST['idFechaEvento']);//echo $cadena_sql;exit;
                $resultado_fechaUsuario=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_fechaUsuario);exit;
                //echo $resultado_fechaUsuario[0][0]."algo";exit;

                $variablesUsuario=array($resultado_idFecha[0][0],$resultado_fechaUsuario[0][0],$resultado_fechaUsuario[0][1],$resultado_fechaUsuario[0][2]);

                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"insertarUsuario",$variablesUsuario);//echo $cadena_sql;exit;
                $resultado_usuarioInsertado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_eventosActivos);exit;

                if($resultado_usuarioInsertado==true)
                    {
                        $variablesLog=array($this->usuario,date('YmdGis'),'30','Edito fecha del calendario academico',$ano."-".$periodo.",".$_REQUEST['usuario'].",".$_REQUEST['idCobertura'].",".$_REQUEST['descUsuario'],$_REQUEST['idCobertura']);
                        
                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"logEventosEdito",$variablesLog);//echo $cadena_sql;exit;
                        $resultado_logInsertado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_eventosActivos);exit;

                        $variablesLog=array($this->usuario,date('YmdGis'),'28','Creo nueva fecha para calendario académico',$ano."-".$periodo.",".$_REQUEST['usuario'].",".$_REQUEST['idCobertura'].",".$_REQUEST['descUsuario'],$_REQUEST['idCobertura']);
                        
                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"logEventos",$variablesLog);//echo $cadena_sql;exit;
                        $resultado_logInsertado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_eventosActivos);exit;

                        echo "<script>alert('La fecha se modifico de manera exitosa en el calendario académico')</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminEventoConsultarFechaAdmon";
                        $variable.="&opcion=consultar";

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
                            $variable="pagina=adminEventoConsultarFechaAdmon";
                            $variable.="&opcion=consultar";

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                        }
            }else
                {
                        echo "<script>alert(' ¡ Error ! La base de datos se encuentra ocupada, intente mas tarde ')</script>";
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminEventoConsultarFechaAdmon";
                        $variable.="&opcion=consultar";

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                }


    }
    }

?>