<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registro_parametrosPlanEstudio extends funcionGeneral
{
    //Crea un objeto tema y un objeto SQL.

    function __construct($configuracion, $sql)
     {
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
        $this->formulario="registro_parametrosPlanEstudio";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    }
    /**
     * Funcion que presenta la vista inicial de los parametros del plan
     * Si estan registrados los presenta, si no, presenta formualrio para registrarlos
     * @param <type> $configuracion 
     */
    function vistaPrincipal($configuracion)
    {
        $nombreProyecto="'".$_REQUEST['nombreProyecto']."'";
        $this->encabezadoModulo($configuracion,$_REQUEST['planEstudio'],$_REQUEST['codProyecto'],$_REQUEST['nombreProyecto']);
        $this->verificar="control_vacio(".$this->formulario.",'totalCreditos')";
        $this->verificar.="&& control_vacio(".$this->formulario.",'OB')";
        $this->verificar.="&& control_vacio(".$this->formulario.",'OC')";
        $this->verificar.="&& control_vacio(".$this->formulario.",'EE')";
        $this->verificar.="&& control_vacio(".$this->formulario.",'EI')";
        $this->verificar.="&& verificar_numero(".$this->formulario.",'totalCreditos')";
        $this->verificar.="&& verificar_numero(".$this->formulario.",'OB')";
        $this->verificar.="&& verificar_numero(".$this->formulario.",'OC')";
        $this->verificar.="&& verificar_numero(".$this->formulario.",'EE')";
        $this->verificar.="&& verificar_numero(".$this->formulario.",'EI')";
            
        $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarParametros",$_REQUEST['planEstudio']);
        $resultado_parametros=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarDatosPlan",$_REQUEST['planEstudio']);
        $resultado_datosPlan=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        if(is_array($resultado_parametros))
            {
                $totalCreditos=$resultado_parametros[0][0];
                $OB=$resultado_parametros[0][1];
                $OC=$resultado_parametros[0][2];
                $EI=$resultado_parametros[0][3];
                $EE=$resultado_parametros[0][4];
                $CP=$resultado_parametros[0][5];
                $maximo = $totalCreditos;
                $porcentajeObligatorios=(($OB+$OC)/$maximo)*100;
                $porcentajeObligatoriosBasicos=(($OB)/($OB+$OC))*100;
                $porcentajeObligatoriosComplementarios=(($OC)/($OB+$OC))*100;
                $porcentajeElectivos=(($EI+$EE)/$maximo)*100;
                $porcentajeElectivosIntrinsecos=(($EI)/($EI+$EE))*100;
                $porcentajeElectivosExtrinsecos=($EE/($EI+$EE))*100;
                if ($resultado_datosPlan[0]['PROPEDEUTICO']==1)
                {
                    $sumaCreditos=$OB+$OC+$EI+$EE+$CP;
                }
                else
                    {
                        $sumaCreditos=$OB+$OC+$EI+$EE;
                    }

                $vista="<table class='contenidotabla centrar'><tr><td class='centrar'>Suma de Cr&eacute;ditos: ".$sumaCreditos."<br>Total de cr&eacute;ditos:".$totalCreditos."</td></tr>";
                $vista.="<tr><td class='centrar'>Obligatorios Basicos: ".$OB."<br>Obligatorios Complementarios: ".$OC."<br>Electivos Intrinsecos: ".$EI."<br>Electivos Extrinsecos: ".$EE;
                if ($CP>0)
                {
                if ($resultado_datosPlan[0]['PROPEDEUTICO']==1)
                {
                    $vista.="<br>Componente Proped&eacute;utico: ".$CP;
                }
                else
                    {
                        $vista.="<br>Componente Proped&eacute;utico (Opcional): ".$CP;
                    }
                }
                $vista.="</td></tr></table>";

                $vista.="<table class='tablaGrafico' align='center' width='100%' cellspacing='0' cellpadding='2'>";
                
            if($porcentajeObligatorios>'0')
                {
                    $vista.="<tr>
                    <td  width='70%' class='centrar' bgcolor='#F3DF8D'> <font color='black'>Obligatorios: ".round($porcentajeObligatorios,0)." %</font>
                    <table class='tablaGrafico' width='100%' cellspacing='0' cellpadding='1' ";
                    if($porcentajeObligatoriosBasicos>'0')
                        {
                            if ($resultado_datosPlan[0]['PROPEDEUTICO']==1)//para planes de estudio de Ingenieria de la facultad tecnologica
                            { $ancho=$porcentajeObligatoriosBasicos-20;
                            $vista.="<tr>
                                        <td width='".$ancho."%' class='centrar'  height='100%'  bgcolor='#29467F'> OB<br>".round($porcentajeObligatoriosBasicos,1)." %
                                        </td>
                                        <td width='20%' class='centrar texto_gris' bgcolor='#CEE3F6' style='border:5px solid #29467F'> CP ".$CP." cred</td>";
                            }else{
                            $vista.="<tr>
                                        <td width='".$porcentajeObligatoriosBasicos."%' class='centrar'  height='100%'  bgcolor='#29467F'> OB<br>".round($porcentajeObligatoriosBasicos,1)." %
                                        </td>";
                        }
                        }
                    if($porcentajeObligatoriosComplementarios>'0')
                        {
                            $vista.="<td width='".$porcentajeObligatoriosComplementarios."%' class='centrar'  height='100%' bgcolor='#6B8FD4'>OC<br> ".round($porcentajeObligatoriosComplementarios,0)." %
                                    </td>
                                    </tr>
                                </table>";
                        }
                }

            if($porcentajeElectivos>'0')
                {
                    $vista.="</td>
                                <td width='30%' class='centrar' bgcolor='#F7EDC5'><font color='black'>Electivos: ".round($porcentajeElectivos,0)." %</font>
                                <table class='tablaGrafico'  width='100%' cellspacing='0' cellpadding='1' >";
                    if($porcentajeElectivosIntrinsecos>'0')
                        {
                            $vista.="<tr>
                                    <td width='".$porcentajeElectivosIntrinsecos."%' class='centrar'  height='100%' bgcolor='#006064'>EI<br> ".round($porcentajeElectivosIntrinsecos,0)." %
                                    </td>";
                        }
                        if($porcentajeElectivosExtrinsecos>'0')
                            {
                                $vista.="<td width='".$porcentajeElectivosExtrinsecos."%' class='centrar'  height='100%' bgcolor='#36979E'>EE<br> ".round($porcentajeElectivosExtrinsecos,0)." %
                                         </td>
                                         </tr>
                                    </table>";
                            }
                }


                    $vista.="</td>
                    </tr>
                    <tr>
                    <td class='centrar' colspan='6'>
                        <input type='button' value='Imprimir' onclick='javascript:window.print();'>
                    </td>
                    </tr>
                    </table>";
                    
                    echo $vista;

                    ?>
<table class="contenidotabla centrar">
    <tr>
        <td class="centrar">
            <?
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=registro_parametrosPlanEstudio";
            $ruta.="&opcion=comentario";
            $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
            $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
            $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
            ?>
            <a href="<?echo $pagina.$ruta?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico'];?>/kword.png" width="25" height="25" alt="Continuar" border="0"><br>Enviar Comentario
            </a>
        </td>
    </tr>
</table>
                    <?
                    }else
                {
                ?>
<table class="contenidotabla centrar">
    <tr class="centrar">
        <td colspan="4">
            Digite el n&uacute;mero total de cr&eacute;ditos del plan de estudio:<br>
            <input type="text" id="totalCreditos" name="totalCreditos" value="<?echo isset($_REQUEST['totalCreditos']);?>" size="3" maxlength="3">
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png" width="25" height="25" alt="Continuar" border="0" onclick="xajax_creditosPlan(document.getElementById('totalCreditos').value, <?echo $_REQUEST['codProyecto'];?>,<?echo $_REQUEST['planEstudio'];?>,<?echo $nombreProyecto;?>)">
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <div id="div_creditos">

            </div>
        </td>
    </tr>
    
    
    <tr class="centrar">
        <td colspan="4">
            <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio'];?>">
            <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto'];?>">
            <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto'];?>">
            <input type="hidden" name="opcion" value="guardarParametros">
            <input type="hidden" name="action" value="<?echo $this->formulario?>">
            <div id="div_graficas">

            </div>
        </td>
    </tr>
    <tr class="cuadro_plano">
        <td>
            <center>OBSERVACI&Oacute;N</center>
            El artículo 12 del acuerdo 009 de 2006 establece:
            <ul>
                <li>El total de cr&eacute;ditos para pregrados de nivel profesional tecnológico debe estar entre noventa y seis (96) y ciento ocho (108) créditos académicos.</li>
                <li>El total de cr&eacute;ditos para pregrados de nivel profesional debe estar entre ciento sesenta (160) y ciento ochenta (180) créditos académicos.</li>
                <li>Del total de cr&eacute;ditos acad&eacute;micos, entre el 80% y 85% corresponden a espacios acad&eacute;micos Obligatorios</li>
                <li>Del total de cr&eacute;ditos acad&eacute;micos, entre el 15% y 20% corresponden a espacios acad&eacute;micos Electivos</li>
                <li>Del total de cr&eacute;ditos acad&eacute;micos obligatorios, el 90% se destina a espacios acad&eacute;micos b&aacute;sicos y el 10% a espacios acad&eacute;micos complementarios</li>
                <li>Del total de cr&eacute;ditos acad&eacute;micos electivos, el 70% se destina a espacios acad&eacute;micos intr&iacute;nsecos y el 30% a espacios acad&eacute;micos extr&iacute;nsecos</li>
            </ul>
            El art&iacute;culo 7 de la resoluci&oacute;n 048 de 2011 establece para los planes de la Facultad Tecnol&oacute;gica:
            <ul>
                <li>Del total de cr&eacute;ditos acad&eacute;micos obligatorios, entre el 70% y 90% se destinan a espacios acad&eacute;micos b&aacute;sicos y, entre el 10% y 30% a espacios acad&eacute;micos complementarios.</li>
                <li>Del total de cr&eacute;ditos acad&eacute;micos electivos, entre el 70% y 90% se destinan a espacios acad&eacute;micos intr&iacute;nsecos y, entre el 10% y 30% a espacios acad&eacute;micos extr&iacute;nsecos.</li>
                <li>Los cr&eacute;ditos acad&eacute;micos del componente proped&eacute;utico corresponden a un rango entre 8 y 12 y se cuentan como obligatorios b&aacute;sicos en los planes de estudios de los programas del ciclo de ingenier&iacute;a.</li>
            </ul>
        </td>
    </tr>
</table>
                <?
                }

       
    }

    /**
     * Funcion que presenta el encabezado del modulo
     * @param <type> $configuracion
     * @param <type> $planEstudio
     * @param <type> $codProyecto
     * @param <type> $nombreProyecto
     */
    function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto)
    {
        ?>

<table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico'];?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr>
        <td class="centrar" colspan="5">
            <font size="2"><b>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA<br>
                MODULO PARA LA ADMINISTRACI&Oacute;N DE PLANES DE ESTUDIO</b></font>
        </td>
    </tr>
    <tr>
        <td class="izquierda" colspan="5">
            <font size="2">
                <b>Plan de Estudio: <?echo $planEstudio?><br>
            Proyecto Curricular: <?echo $codProyecto." - ".$nombreProyecto?></b></font>

            <hr noshade class="hr">

        </td>
    </tr>

</table><?
    }


    function registrarInformacion($configuracion)
    {
        if (!is_null($_REQUEST['planEstudio'])&&!is_null($_REQUEST['codProyecto'])&&!is_null($_REQUEST['nombreProyecto']))
            {
                $planEstudio=$_REQUEST['planEstudio'];
                $codProyecto=$_REQUEST['codProyecto'];
                $nombreProyecto=$_REQUEST['nombreProyecto'];
            }
            else
            {
                $variablesCoordinador=array($this->usuario,$_REQUEST['planEstudio']);
                $cadena_sql=$this->sql->cadena_sql($configuracion,"datos_coordinador",$variablesCoordinador);
                $resultado_datos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
                $planEstudio=$resultado_datos[0][2];
                $codProyecto=$resultado_datos[0][0];
                $nombreProyecto=$resultado_datos[0][1];
            }


        $variablesParametros=array($_REQUEST['planEstudio'],$_REQUEST['totalCreditos'],'40','18','8',$_REQUEST['OB'],$_REQUEST['OC'],$_REQUEST['EI'],$_REQUEST['EE'],$_REQUEST['CP']);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarParametrosPlan",$_REQUEST['planEstudio']);
        $resultado_existe=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        if(is_array($resultado_existe))
            {
                echo "<script>alert('Las parametros para este plan de estudio ya estan registrados')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registro_parametrosPlanEstudio";
                $ruta.="&opcion=administrar";
                $ruta.="&codProyecto=".$codProyecto;
                $ruta.="&planEstudio=".$planEstudio;
                $ruta.="&nombreProyecto=".$nombreProyecto;


                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                echo "<script>location.replace('".$pagina.$ruta."')</script>";
            }else{
        $cadena_sql=$this->sql->cadena_sql($configuracion,"registrarParametros",$variablesParametros);
        $resultado_parametros=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

        if($resultado_parametros==true)
            {
                $variablesLog=array($this->usuario,date('YmdGis'),'38','Creo parametros plan Estudio',"T:".$_REQUEST['totalCreditos']." - OB:".$_REQUEST['OB']." - OC:".$_REQUEST['OC']." - EI:".$_REQUEST['EI']." - EE:".$_REQUEST['EE']." - CP:".$_REQUEST['CP'],$_REQUEST['planEstudio']);

                $cadena_sql=$this->sql->cadena_sql($configuracion,"registroEvento",$variablesLog);
                $resultado_evento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registro_parametrosPlanEstudio";
                $ruta.="&opcion=administrar";
                $ruta.="&codProyecto=".$codProyecto;
                $ruta.="&planEstudio=".$planEstudio;
                $ruta.="&nombreProyecto=".$nombreProyecto;
               

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                echo "<script>location.replace('".$pagina.$ruta."')</script>";
            }
            }
    }

    /**
     * Funcion que presenta formulario para ingresar comentario de parametros
     * @param <type> $configuracion 
     */
    function comentarioCoordinador($configuracion)
    {
        $this->encabezadoModulo($configuracion, $_REQUEST['planEstudio'], $_REQUEST['codProyecto'], $_REQUEST['nombreProyecto']);
        ?>
<form name="<?echo $this->formulario?>" action="index.php" method="POST">
<table class="contenidotabla centrar">
    <tr>
        <td class="cuadro_plano centrar">
            Digite el comentario que desea enviar a vicerrectoria<br>
            <textarea cols="35" rows="3" name="comentario"></textarea>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano centrar">
            <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
            <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
            <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
            <input type="hidden" name="opcion" value="guardarComentario">
            <input type="hidden" name="action" value="<?echo $this->formulario?>">
            <input type="submit" name="guardar" value="Guardar">
        </td>
    </tr>
</table>
</form>
        <?
    }

    /**
     * Funcion que registra comentario de parametros del plan
     * @param <type> $configuracion 
     */
    function guardarComentario($configuracion)
    {
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $comentario=$_REQUEST['comentario'];

        $variablesComentario=array('',$planEstudio,$codProyecto,$this->usuario,date('YmdGis'),'0','1',$comentario);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"registroComentario",$variablesComentario);
        $resultado_comentario=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

        if($resultado_comentario==true)
            {
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registro_parametrosPlanEstudio";
                $ruta.="&planEstudio=".$planEstudio;
                $ruta.="&codProyecto=".$codProyecto;
                $ruta.="&nombreProyecto=".$nombreProyecto;
                
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                echo "<script>location.replace('".$pagina.$ruta."')</script>";
            }
    }

    }

?>