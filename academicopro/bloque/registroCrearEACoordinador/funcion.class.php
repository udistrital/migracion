<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroCrearEACoordinador extends funcionGeneral {
    //Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=$sql;

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        $this->formulario="registroCrearEACoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        if ($this->nivel==28||$this->nivel==4)
        {
            $this->pagina="adminConfigurarPlanEstudioCoordinador";
            $this->opcion="mostrar";
        }
        elseif($this->nivel==61)
        {
            $this->pagina="adminAprobarEspacioPlan";
            $this->opcion="mostrar";
        }

    }


    function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto) {

        ?>

<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>CREACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS PARA EL PROYECTO CURRICULAR<br><?echo $nombreProyecto?><br>PLAN DE ESTUDIOS: <?echo $planEstudio?></h4>
            <hr noshade class="hr">

        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=".$this->pagina;
                    $variables.="&opcion=".$this->opcion;
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Volver al Plan de estudios
            </a>
        </td>
    </tr>
</table><?
    }



    function seleccionarClasificacion($configuracion) {
        
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];

        $this->encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto);

        $variable=array($planEstudio,$codProyecto,$nombreProyecto);

        //Buscamos los espacios academicos que pertenecen al plan de estudio seleccionado
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");//echo $cadena_sql;exit;
        $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;
        ?>
        <style type="text/css">
        #toolTipBox {
                display: none;
                position:absolute;
                width:300px;
                background:#E9EFE6;
                border:4px double #fff;
                text-align:left;
                padding:5px;
                -moz-border-radius:8px;
                z-index:1000;
                margin:0;
                padding:0;
                color:#1E3B86;
                font:11px/12px verdana,arial,serif;
                margin-top:3px;
                font-style:normal;
                font-weight:bold;
                opacity:0.85;
        }
        </style>
<table class="sigma contenidotabla centrar" width="100%" border="0">
        <tr>
            <th class="sigma_a centrar" colspan="2">
                <font size="2"> Seleccione la clasificaci&oacute;n que va a tener el nuevo espacio acad&eacute;mico</font>
            </th>
        </tr>
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <?

        for($i=0;$i<count($resultado_clasificacion);$i++){

            if($resultado_clasificacion[$i][0]==4)
                {
                ?>
                    <tr>
                        <td class="centrar" colspan="2">
                            <input type="radio" name="clasificacion" value="<?echo $resultado_clasificacion[$i][0]?>" onmouseover="toolTip('Esta opción le permite sugerir al estudiante la cantidad de créditos y el nivel en que puede cursar sus espacios académicos Electivos Extrínsecos dentro del plan de estudios',this)"><br><b><?echo $resultado_clasificacion[$i][1]?></b>
                            <div class="centrar">
                                <span id="toolTipBox" width="300" ></span>
                            </div>
                        </td>
                    </tr>
        <?
                }else{
                    
                ?>
                    <tr>
                        <td class="centrar" colspan="2">
                            <input type="radio" name="clasificacion" value="<?echo $resultado_clasificacion[$i][0]?>"><br><b><?echo $resultado_clasificacion[$i][1]?></b>
                        </td>
                    </tr>
                <?}
        }
        ?>
            <td class="centrar">
                
                <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                <input type="hidden" name="opcion" value="sinOpciones">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" value="Sin Opciones" src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png" width="35" height="35"><br><font size="1">Continuar</font>
                </form>
            </td>
        </tr>
</table>
        <?


    }

    function crearNoOpciones($configuracion)
    {
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $clasificacion=$_REQUEST['clasificacion'];
        //var_dump($_REQUEST);

        if($clasificacion=='')
            {
                echo "<script>alert('Por favor seleccione un tipo de clasificación para el espacio académico a crear')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroCrearEACoordinador";
                $variables.="&opcion=seleccionClasificacion";
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }else

        $this->encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto);

        $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion);

        //Buscamos los espacios academicos que pertenecen al plan de estudio seleccionado
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");
        $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        ?>
<table class="contenidotabla centrar" width="100%" border="0">
            <?
            for($i=0;$i<count($resultado_clasificacion);$i++)
            {
                if($resultado_clasificacion[$i][0]==$clasificacion)
                    {
                        ?>
                        <caption class="sigma centrar">Clasificaci&oacute;n del espacio acad&eacute;mico: <?echo $resultado_clasificacion[$i][1]?></caption>
                        <?
                    }
            }
            ?>
</table>

        <?

        if($clasificacion!=4)
            {
                $this->formularioCreacion($configuracion,$variable,$_REQUEST);
            }else
                {
                    $this->formularioCreacionExtrinsecas($configuracion,$variable,$_REQUEST);
                }
    }


    function formularioCreacion($configuracion,$variable,$datos)
    {
       ?>
        <table class="sigma centrar" align="center" width="70%" border="0">
        <tr>
            <td class="sigma_a centrar" colspan="3">
                Todos los campos marcados con <font size="3" color="red">*</font> son obligatorios
            </td>
        </tr>
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
            <tr class="sigma_a">
                <td class="sigma" align="right" colspan="2">
                    <font size="2" color="red">*</font> Nombre del Espacio:
                </td>
                <td align="left">
                    <input type="text" name="nombreEspacio" size="50" maxlength="80" value="<?echo $datos['nombreEspacio']?>">
                </td>
            </tr>
            <tr class="sigma">
                <td class="sigma" align="right" colspan="2">
                    <font size="2" color="red">*</font> N&uacute;mero de Cr&eacute;ditos:
                </td>
                <td align="left">
                    <input  type="text" name="nroCreditos" size="5" maxlength="5" value="<?echo $datos['nroCreditos']?>">
                </td>
            </tr>
            <tr class="sigma_a">
                <td class="sigma" align="right" colspan="2">
                    <font size="2" color="red">*</font> Nivel:
                </td>
                <td align="left">
                    <input type="text" name="nivel" size="5" maxlength="5" value="<?echo $datos['nivel']?>">
                </td>
            </tr>
            <table class="sigma centrar" align="center" width="70%" border="0">
            <tr>
                <td class="sigma_a centrar" colspan="3">Distribuci&oacute;n</td>
            </tr>
            <tr class="centrar">
                <td class="sigma" width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Directo
                </td>
                <td class="sigma" width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Cooperativo
                </td>
                <td class="sigma" width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Autonomo
                </td>
            </tr>
            <tr class="centrar">
                <td width="33%">
                    <input type="text" name="htd" size="5" maxlength="5" value="<?echo $datos['htd']?>">
                </td>
                <td width="33%">
                    <input type="text" name="htc" size="5" maxlength="5" value="<?echo $datos['htc']?>">
                </td>
                <td width="33%">
                    <input type="text" name="hta" size="5" maxlength="5" value="<?echo $datos['hta']?>">
                </td>
            </tr>
            <tr class="centrar">
                <td class="sigma" colspan="3" >
                    <font size="2" color="red">*</font>N&uacute;mero de semanas en que se cursa el espacio ac&aacute;demico
                </td>
            </tr>
            <tr class="centrar">
                <td colspan="3">
                    <select class="sigma" name="semanas" id="<? echo $datos['semanas'];?>" style="width:270px">
                <option value="16" <? if($datos['semanas']==16){echo "selected=16";} ?>>Espacios Semestrales (16 semanas)</option>
                <option value="32" <? if($datos['semanas']==32){echo "selected=32";} ?>>Espacios Anuales (32 semanas)</option>
            </select>
               </td>
            </tr>
            
            </table>
            <table class="contenidotabla centrar" width="100%" border="0">
                <tr>
                <td class="centrar" width="50%">
                    <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                    <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                    <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                    <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                    <input type="hidden" name="opcion" value="validarEA">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input class="boton" type="submit" value="Guardar" >
                </td>
                <td class="centrar" width="50%">
                    <input class="boton" type="reset" >
                </td>
            </tr>
            </table>
        </form>
        </table>

        <?
    }

    function validarinformacion($configuracion)
    {
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $clasificacion=$_REQUEST['clasificacion'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nivel=$_REQUEST['nivel'];
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
        $semanas=$_REQUEST['semanas'];

        $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion, $nombreEspacio, $nroCreditos, $nivel, $htd, $htc, $hta);

        

        if(($nombreEspacio=='')||($nroCreditos=='')||($nivel=='')||($htd=='')||($htc=='')||($hta==''))
            {
                echo "<script>alert('Todos los campos deben ser diligenciados')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroCrearEACoordinador";
                $variables.="&opcion=sinOpciones";
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;
                $variables.="&clasificacion=".$clasificacion;
                $variables.="&nombreEspacio=".$nombreEspacio;
                $variables.="&nroCreditos=".$nroCreditos;
                $variables.="&nivel=".$nivel;
                $variables.="&htd=".$htd;
                $variables.="&htc=".$htc;
                $variables.="&hta=".$hta;
                $variables.="&semanas=".$semanas;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }

            if(!is_numeric($nivel)||!is_numeric($nroCreditos)||!is_numeric($htd)||!is_numeric($htc)||!is_numeric($hta))
            {
                echo "<script>alert('Los campos (Creditos, Nivel, HTD, HTC, HTA) deben ser numericos')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroCrearEACoordinador";
                $variables.="&opcion=sinOpciones";
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;
                $variables.="&clasificacion=".$clasificacion;
                $variables.="&nombreEspacio=".$nombreEspacio;
                $variables.="&nroCreditos=".$nroCreditos;
                $variables.="&nivel=".$nivel;
                $variables.="&htd=".$htd;
                $variables.="&htc=".$htc;
                $variables.="&hta=".$hta;
                $variables.="&semanas=".$semanas;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }

         //Determina la distribucion segun las semanas seleccionadas(Semestralizado 16, Anualizado 32)
         $totalDistribucion=($hta+$htc+$htd)*$semanas;
         $horasCreditos=$nroCreditos*48;

         if($totalDistribucion!=$horasCreditos)
             {
                echo "<script>alert('La distribución seleccionada no concuerda con la cantidad de créditos')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroCrearEACoordinador";
                $variables.="&opcion=sinOpciones";
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;
                $variables.="&clasificacion=".$clasificacion;
                $variables.="&nombreEspacio=".$nombreEspacio;
                $variables.="&nroCreditos=".$nroCreditos;
                $variables.="&nivel=".$nivel;
                $variables.="&htd=".$htd;
                $variables.="&htc=".$htc;
                $variables.="&hta=".$hta;
                $variables.="&semanas=".$semanas;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
             }

            if($nivel==0)
            {
                echo "<script>alert('El nivel del espacio debe ser diferente de cero (0).')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroCrearEACoordinador";
                $variables.="&opcion=sinOpciones";
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;
                $variables.="&clasificacion=".$clasificacion;
                $variables.="&nombreEspacio=".$nombreEspacio;
                $variables.="&nroCreditos=".$nroCreditos;
                $variables.="&nivel=".$nivel;
                $variables.="&htd=".$htd;
                $variables.="&htc=".$htc;
                $variables.="&hta=".$hta;
                $variables.="&semanas=".$semanas;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }


           //Buscamos los espacios academicos que pertenecen al plan de estudio seleccionado
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"rango_codigos",$planEstudio);//echo $cadena_sql;exit;
            $resultado_rango=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

            for ($a=0;$a<count($resultado_rango);$a++)
            {
                $codigos=range($resultado_rango[$a][0], $resultado_rango[$a][1]);
                for($i=0;$i<count($codigos);$i++)
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"codigos_no_asignadosMysql",$codigos[$i]);//echo $cadena_sql;exit;
                    $resultado_codigo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

                    if($resultado_codigo[0][0]=='')
                    {
                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"codigos_no_asignadosOracle",$codigos[$i]);//echo $cadena_sql;exit;
                        $resultado_codigoOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

                        if($resultado_codigoOracle[0][0]=='')
                        {
                            $codigoSeleccionado=$codigos[$i];
                            break;
                        }
                    }
                }
            }
            $variable[10]=$codigoSeleccionado;
            $variable[11]=$semanas;
            $this->solicitarConfirmacion($configuracion,$variable);


    }

    function solicitarConfirmacion($configuracion,$variable)
        {

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");//echo $cadena_sql;exit;
            $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;
            
            $this->encabezadoModulo($configuracion,$variable[0],$variable[1],$variable[2]);

            ?>
<table class="sigma" align="center" width="80%" border="0">
                <tr>
                    <th class="sigma_a" colspan="3">
                      Al espacio que se va a crear se le asign&oacute; el c&oacute;digo <?echo $variable[10]?> y contiene la siguiente informaci&oacute;n:
                    </th>
                </tr>
                <tr class="sigma">
                    <td class="sigma" align="rigth" width="30%" >Plan de Estudio:</td>
                    <td class="sigma" colspan="3"><?echo $variable[0]?></td>
                </tr>
                <tr class="sigma_a">
                    <td class="sigma" width="30%" >Cod&iacute;go del Espacio Acad&eacute;mico:</td>
                    <td class="sigma" colspan="3"><font size="2"><?echo $variable[10]?></font></td>
                </tr>
                <tr class="sigma">
                    <td class="sigma" width="30%">Nombre del Espacio Acad&eacute;mico:</td>
                    <td class="sigma" colspan="3"><font size="2"><?echo $variable[4]?></font></td>
                </tr>
                <tr class="sigma_a">
                    <td class="sigma" width="30%">Tipo de clasificaci&oacute;n:</td>
                    <?
                        for($i=0;$i<count($resultado_clasificacion);$i++)
                        {
                            if($resultado_clasificacion[$i][0]==$variable[3])
                                {
                                    ?>
                                        <td class="sigma" colspan="3"><?echo $resultado_clasificacion[$i][1]?></td>
                                    <?
                                }
                        }
                        ?>
                </tr>
                <tr class="sigma">
                    <td class="sigma" width="30%">N&uacute;mero de Cr&eacute;ditos:</td>
                    <td class="sigma" colspan="3"><?echo $variable[5]?></td>
                </tr>
                <tr class="sigma_a">
                    <td class="sigma" width="30%">Nivel:</td>
                    <td class="sigma" colspan="3"><?echo $variable[6]?></td>
                </tr>
                <tr class="sigma">
                    <td class="sigma" width="30%">Horas de Trabajo Directo:</td>
                    <td class="sigma" colspan="3"><?echo $variable[7]?></td>
                </tr>
                <tr class="sigma_a">
                    <td class="sigma" width="30%">Horas de Trabajo Cooperativo:</td>
                    <td class="sigma" colspan="3"><?echo $variable[8]?></td>
                </tr>
                <tr class="sigma">
                    <td class="sigma" width="30%">Horas de Trabajo Autonomo:</td>
                    <td class="sigma" colspan="3"><?echo $variable[9]?></td>
                </tr>
                <tr class="sigma_a">
                    <td class="sigma" width="30%">N&uacute;mero de semanas en que se cursa el espacio ac&aacute;demico:</td>
                    <td class="sigma" colspan="3"><?echo $variable[11]?></td>
                </tr>
                <tr>
                    <th class="sigma centrar" colspan="3">¿Desea guardar la informaci&oacute;n anteriormente diligenciada?</th>
                </tr>
                <tr>
                    <td width="33%" class="sigma centrar"><br>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                        <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                        <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                        <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                        <input type="hidden" name="nombreEspacio" value="<?echo $variable[4]?>">
                        <input type="hidden" name="nroCreditos" value="<?echo $variable[5]?>">
                        <input type="hidden" name="nivel" value="<?echo $variable[6]?>">
                        <input type="hidden" name="htd" value="<?echo $variable[7]?>">
                        <input type="hidden" name="htc" value="<?echo $variable[8]?>">
                        <input type="hidden" name="hta" value="<?echo $variable[9]?>">
                        <input type="hidden" name="id_espacio" value="<?echo $variable[10]?>">
                        <input type="hidden" name="semanas" value="<?echo $variable[11]?>">
                        <input type="hidden" name="opcion" value="confirmado">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" value="Confirmado" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
                        </form>
                    </td>
                    <td width="33%" class="sigma centrar"><br>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                        <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                        <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                        <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                        <input type="hidden" name="nombreEspacio" value="<?echo $variable[4]?>">
                        <input type="hidden" name="nroCreditos" value="<?echo $variable[5]?>">
                        <input type="hidden" name="nivel" value="<?echo $variable[6]?>">
                        <input type="hidden" name="htd" value="<?echo $variable[7]?>">
                        <input type="hidden" name="htc" value="<?echo $variable[8]?>">
                        <input type="hidden" name="hta" value="<?echo $variable[9]?>">
                        <input type="hidden" name="id_espacio" value="<?echo $variable[10]?>">
                        <input type="hidden" name="semanas" value="<?echo $variable[11]?>">
                        <input type="hidden" name="opcion" value="modificar">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" value="modificar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/modificar.png" width="35" height="35"><br>Modificar
                        </form>
                    </td>
                    <td width="33%" class="sigma centrar"><br>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                        <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                        <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                        <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                        <input type="hidden" name="nombreEspacio" value="<?echo $variable[4]?>">
                        <input type="hidden" name="nroCreditos" value="<?echo $variable[5]?>">
                        <input type="hidden" name="nivel" value="<?echo $variable[6]?>">
                        <input type="hidden" name="htd" value="<?echo $variable[7]?>">
                        <input type="hidden" name="htc" value="<?echo $variable[8]?>">
                        <input type="hidden" name="hta" value="<?echo $variable[9]?>">
                        <input type="hidden" name="id_espacio" value="<?echo $variable[10]?>">
                        <input type="hidden" name="semanas" value="<?echo $variable[11]?>">
                        <input type="hidden" name="opcion" value="cancelar">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" value="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="35" height="35"><br>No
                        </form>
                    </td>
                </tr>
                
            </table>
            <?
        }

    function guardarEA($configuracion)
    {
            $usuario=$this->usuario;
            $codProyecto=$_REQUEST['codProyecto'];
            $planEstudio=$_REQUEST['planEstudio'];
            $nombreProyecto=$_REQUEST['nombreProyecto'];
            $clasificacion=$_REQUEST['clasificacion'];
            $nombreEspacio=$_REQUEST['nombreEspacio'];
            $nroCreditos=$_REQUEST['nroCreditos'];
            $nivel=$_REQUEST['nivel'];
            $htd=$_REQUEST['htd'];
            $htc=$_REQUEST['htc'];
            $hta=$_REQUEST['hta'];
            $id_espacio=$_REQUEST['id_espacio'];
            $semanas=$_REQUEST['semanas'];
            if (is_null($id_espacio)||$id_espacio==''||$planEstudio=='')
              {
                  echo "<script>alert('No se ha asignado un codigo para el espacio academico. Por favor, comuniqese con la Oficina Asesora de Sistemas.')</script>";
                  $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                  $variables="pagina=".$this->pagina;
                  $variables.="&planEstudio=".$planEstudio;
                  $variables.="&codProyecto=".$codProyecto;
                  $variables.="&nombreProyecto=".$nombreProyecto;

                  include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                  $this->cripto=new encriptar();
                  $variables=$this->cripto->codificar_url($variables,$configuracion);
                  echo "<script>location.replace('".$pagina.$variables."')</script>";
                  exit;
              }

            $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion,$nombreEspacio,$nroCreditos,$nivel,$htd,$htc,$hta,$id_espacio,$semanas);

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registro_espacioAcademico",$variable);
            $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

            if($resultado_espacioAcad == true){
                
                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registro_planEstudio",$variable);
                $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                if($resultado_planEstudio == true){

                $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                $ano=$resultadoPeriodo[0][0];
                $periodo=$resultadoPeriodo[0][1];

                $variablesRegistro=array(usuario=>$usuario,
                                          evento=>'11',
                                          descripcion=>'Creo Espacio Académico',
                                          registro=>$ano."-".$periodo.", ".$id_espacio.", ".$planEstudio.", ".$codProyecto,
                                          afectado=>$planEstudio);

                $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroLogEvento",$variablesRegistro);
                $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");

                $variables=array($planEstudio, $codProyecto, $usuario, date('YmdHis'),$id_espacio,$nombreEspacio);
                $cadena_sql_comentario=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"ingresarComentario", $variables);
                $resultadoComentario=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_comentario,"" );

                echo "<script>alert('El Espacio Académico ".$nombreEspacio." se ha creado para su posterior aprobación ')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=".$this->pagina;
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;
                   
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    echo "<script>location.replace('".$pagina.$variables."')</script>";
                    break;

                    }else{

                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_registroEspacio",$variable);
                        $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                        echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde ')</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variables="pagina=registroCrearEACoordinador";
                            $variables.="&opcion=validarEA";
                            $variables.="&codProyecto=".$codProyecto;
                            $variables.="&planEstudio=".$planEstudio;
                            $variables.="&nombreProyecto=".$nombreProyecto;
                            $variables.="&clasificacion=".$clasificacion;
                            $variables.="&nombreEspacio=".$nombreEspacio;
                            $variables.="&nroCreditos=".$nroCreditos;
                            $variables.="&nivel=".$nivel;
                            $variables.="&htd=".$htd;
                            $variables.="&htc=".$htc;
                            $variables.="&hta=".$hta;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                            echo "<script>location.replace('".$pagina.$variables."')</script>";
                            break;
            }
            }else{
            echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde ')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroCrearEACoordinador";
                $variables.="&opcion=validarEA";
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;
                $variables.="&clasificacion=".$clasificacion;
                $variables.="&nombreEspacio=".$nombreEspacio;
                $variables.="&nroCreditos=".$nroCreditos;
                $variables.="&nivel=".$nivel;
                $variables.="&htd=".$htd;
                $variables.="&htc=".$htc;
                $variables.="&hta=".$hta;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }
        }


    function formularioCreacionExtrinsecas($configuracion,$variable,$datos)
    {
       ?>
        <table class="sigma centrar" align="center" width="70%" border="0">
        <tr>
            <td class="sigma_a centrar" colspan="3">
                Todos los campos marcados con <font size="3" color="red">*</font> son obligatorios
            </td>
        </tr>
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
            <tr>
                <td class="sigma" align="right" colspan="2">
                    <font size="2" color="red">*</font> Nombre del Espacio:
                </td>
                <td align="left">
                    <input type="text" name="nombreEspacio" size="50" maxlength="80" value="<?echo $datos['nombreEspacio']?>">
                </td>
            </tr>
            <tr>
                <td class="sigma" align="right" colspan="2">
                    <font size="2" color="red">*</font> N&uacute;mero de Cr&eacute;ditos:
                </td>
                <td align="left">
                    <input type="text" name="nroCreditos" size="5" maxlength="5" value="<?echo $datos['nroCreditos']?>">
                </td>
            </tr>
            <tr>
                <td class="sigma" align="right" colspan="2">
                    <font size="2" color="red">*</font> Nivel:
                </td>
                <td align="left">
                    <input type="text" name="nivel" size="5" maxlength="5" value="<?echo $datos['nivel']?>">
                </td>
            </tr>
            
            <table class="sigma centrar" width="100%" border="0">
                <tr>
                <td class="derecha" width="50%">
                    <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                    <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                    <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                    <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                    <input type="hidden" name="opcion" value="validarEAExtrinsecas">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input class="boton" type="submit" value="Guardar" >
                </td>
                <td class="izquierda" width="50%">
                    <input class="boton" type="reset" >
                </td>
            </tr>
            </table>
        </form>
        </table>

        <?
    }


    function validarinformacionExtrinsecas($configuracion)
    {
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $clasificacion=$_REQUEST['clasificacion'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nivel=$_REQUEST['nivel'];
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
        $semanas=$_REQUEST['semanas'];

        $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion, $nombreEspacio, $nroCreditos, $nivel);



        if(($nombreEspacio=='')||($nroCreditos=='')||($nivel==''))
            {
                echo "<script>alert('Todos los campos deben ser diligenciados')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroCrearEACoordinador";
                $variables.="&opcion=sinOpciones";
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;
                $variables.="&clasificacion=".$clasificacion;
                $variables.="&nombreEspacio=".$nombreEspacio;
                $variables.="&nroCreditos=".$nroCreditos;
                $variables.="&nivel=".$nivel;
                
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }

            if(!is_numeric($nivel)||!is_numeric($nroCreditos)||$nivel=='0'||$nroCreditos=='0')
            {
                echo "<script>alert('Los campos (Creditos y Nivel) deben ser numericos y diferentes de 0')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroCrearEACoordinador";
                $variables.="&opcion=sinOpciones";
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;
                $variables.="&clasificacion=".$clasificacion;
                $variables.="&nombreEspacio=".$nombreEspacio;
                $variables.="&nroCreditos=".$nroCreditos;
                $variables.="&nivel=".$nivel;
                

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nivel_maximoPlan",$planEstudio);
            $resultado_niveles=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            if($nivel>$resultado_niveles[0][0])
                {
                    echo "<script>alert('El nivel no puede superar el total de niveles registrados del plan de estudios')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroCrearEACoordinador";
                    $variables.="&opcion=sinOpciones";
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&nombreProyecto=".$nombreProyecto;
                    $variables.="&clasificacion=".$clasificacion;
                    $variables.="&nombreEspacio=".$nombreEspacio;
                    $variables.="&nroCreditos=".$nroCreditos;
                    $variables.="&nivel=".$nivel;


                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    echo "<script>location.replace('".$pagina.$variables."')</script>";
                    break;
                }

         //Determina la distribucion segun las semanas seleccionadas(Semestralizado 16, Anualizado 32)
           $this->solicitarConfirmacionExtrinsecas($configuracion,$variable);


    }

    function solicitarConfirmacionExtrinsecas($configuracion,$variable)
        {

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");
            $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            $this->encabezadoModulo($configuracion,$variable[0],$variable[1],$variable[2]);

            ?>
            <table class="sigma" align="center" width="80%" border="0">
                <tr>
                    <th class="sigma_a" colspan="3">
                        Esta opci&oacute;n le permite sugerir al estudiante la cantidad de cr&eacute;ditos y el nivel en que puede cursar sus espacios acad&eacute;micos Electivos Extr&iacute;nsecos dentro del plan de estudios
                    </th>
                </tr>

                <tr class="sigma">
                    <td class="sigma" width="30%"><font size="2">Nombre del Espacio Acad&eacute;mico:</font></td>
                    <td class="sigma" colspan="3"><font size="2"><?echo $variable[4]?></font></td>
                </tr>
                <tr class="sigma_a">
                    <td class="sigma" width="30%"><font size="2">Clasificaci&oacute;n:</font></td>
                    <?
                        for($i=0;$i<count($resultado_clasificacion);$i++)
                        {
                            if($resultado_clasificacion[$i][0]==$variable[3])
                                {
                                    ?>
                                        <td class="sigma" colspan="3"><font size="2"><?echo $resultado_clasificacion[$i][1]?></font></td>
                                    <?
                                }
                        }
                        ?>
                </tr>
                <tr class="sigma">
                    <td class="sigma" width="30%"><font size="2">N&uacute;mero de Cr&eacute;ditos:</font></td>
                    <td class="sigma" colspan="3"><font size="2"><?echo $variable[5]?></font></td>
                </tr>
                <tr>
                    <th class="sigma centrar" colspan="3">¿Desea guardar la informaci&oacute;n anteriormente diligenciada?</th>
                </tr>
                <tr>
                    <td width="33%" class="sigma centrar"><br>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                        <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                        <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                        <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                        <input type="hidden" name="nombreEspacio" value="<?echo $variable[4]?>">
                        <input type="hidden" name="nroCreditos" value="<?echo $variable[5]?>">
                        <input type="hidden" name="nivel" value="<?echo $variable[6]?>">
                        <input type="hidden" name="opcion" value="confirmadoExtrinsecas">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" value="Confirmado" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
                        </form>
                    </td>
                    <td width="33%" class="sigma centrar"><br>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                        <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                        <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                        <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                        <input type="hidden" name="nombreEspacio" value="<?echo $variable[4]?>">
                        <input type="hidden" name="nroCreditos" value="<?echo $variable[5]?>">
                        <input type="hidden" name="nivel" value="<?echo $variable[6]?>">
                        <input type="hidden" name="opcion" value="modificarExtrinsecas">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" value="modificar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/modificar.png" width="35" height="35"><br>Modificar
                        </form>
                    </td>
                    <td width="33%" class="sigma centrar"><br>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                        <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                        <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                        <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                        <input type="hidden" name="nombreEspacio" value="<?echo $variable[4]?>">
                        <input type="hidden" name="nroCreditos" value="<?echo $variable[5]?>">
                        <input type="hidden" name="nivel" value="<?echo $variable[6]?>">
                        <input type="hidden" name="htd" value="<?echo $variable[7]?>">
                        <input type="hidden" name="htc" value="<?echo $variable[8]?>">
                        <input type="hidden" name="hta" value="<?echo $variable[9]?>">
                        <input type="hidden" name="id_espacio" value="<?echo $variable[10]?>">
                        <input type="hidden" name="semanas" value="<?echo $variable[11]?>">
                        <input type="hidden" name="opcion" value="cancelar">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" value="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="35" height="35"><br>No
                        </form>
                    </td>
                </tr>

            </table>
            <?
        }

    function guardarEAExtrinsecas($configuracion)
    {
            $usuario=$this->usuario;
            $codProyecto=$_REQUEST['codProyecto'];
            $planEstudio=$_REQUEST['planEstudio'];
            $nombreProyecto=$_REQUEST['nombreProyecto'];
            $clasificacion=$_REQUEST['clasificacion'];
            $nombreEspacio=$_REQUEST['nombreEspacio'];
            $nroCreditos=$_REQUEST['nroCreditos'];
            $nivel=$_REQUEST['nivel'];

            $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion,$nombreEspacio,$nroCreditos,$nivel);

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registro_espacioAcademicoExtrinseco",$variable);
            $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

            if($resultado_espacioAcad == true){
                $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                $ano=$resultadoPeriodo[0][0];
                $periodo=$resultadoPeriodo[0][1];

                $variablesRegistro=array(usuario=>$usuario,
                                          evento=>'4',
                                          descripcion=>'Creo sugerencia extrinseca',
                                          registro=>$ano."-".$periodo.", ".$nombreEspacio.", ".$planEstudio.", ".$codProyecto,
                                          afectado=>$planEstudio);
                $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroLogEvento",$variablesRegistro);
                $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");

                $variables=array($planEstudio, $codProyecto, $usuario, date('YmdHis'),$id_espacio,$nombreEspacio);
                $cadena_sql_comentario=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"ingresarComentario", $variables);
                $resultadoComentario=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_comentario,"" );

                echo "<script>alert('El Espacio Académico ".$nombreEspacio." se ha creado como sugerencia del plan de estudio ')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=".$this->pagina;
                    $variables.="&opcion=".$this->opcion;
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    echo "<script>location.replace('".$pagina.$variables."')</script>";
                    break;

                    
            }else{
            echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde ')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=".$this->pagina;
                $variables.="&opcion=".$this->opcion;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&nombreProyecto=".$nombreProyecto;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }
        }

        function cancelar($configuracion, $opciones) {
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=".$this->pagina;
            $variables.="&opcion=".$this->opcion;
            $variables.="&planEstudio=".$opciones['planEstudio'];
            $variables.="&codProyecto=".$opciones['codProyecto'];
            $variables.="&nombreProyecto=".$opciones['nombreProyecto'];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
            break;


}

}


?>
