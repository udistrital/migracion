<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroPortafolioElectivasCoordinador extends funcionGeneral {
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
        $this->formulario="registroPortafolioElectivasCoordinador";
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


    function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto)
    {

        ?>

<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>CREACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS ELECTIVOS EXTR&Iacute;NSECOS<br><?echo $nombreProyecto?><br>PLAN DE ESTUDIO: <?echo $planEstudio?></h4>
            <hr noshade class="hr">

        </td>
    </tr>

    <tr align="center">
        <td class="centrar" colspan="2" width="50%">
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
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
        </td>
        <td class="centrar" colspan="2" width="50%">
                <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroMultipleClasificacionEspacioCoordinador";
                    $variables.="&opcion=ver_planEstudio";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/modificar.png" width="35" height="35" border="0"><br>Ofrecer Espacio Acad&eacute;mico<br>del plan de estudios<br>como Electivo Extr&iacute;nseco
            </a>
        </td>
    </tr>
</table><?
    }


    function verElectivas($configuracion)
    {
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $clasificacion=$_REQUEST['clasificacion'];
        //var_dump($_REQUEST);
        $registroPlan[0][0]=$planEstudio;
        $registroPlan[0][1]=$codProyecto;
        $registroPlan[0][2]=$nombreProyecto;

        //Buscamos los espacios academicos electivos extrinsecos que estan creados para el proyecto
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"consultaEspacioPlan",$planEstudio);//echo $cadena_sql;exit;
        $registroEspaciosPlan=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;
        $totalEspacios=$this->accesoGestion->obtener_conteo_db($registroEspaciosPlan);

        if(is_array($registroEspaciosPlan)) {

            $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
            #Muestra los niveles de un plan de estudios
            $this->listaEspacios($configuracion,$registroEspaciosPlan,$totalEspacios,$registroPlan);
        }else {

            $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
            ?>
<table class="contenidotabla centrar">
    <tr>
        <td colspan="4" class="cuadro_brownOscuro  centrar">
            EL PROYECTO CURRICULAR NO TIENE ESPACIOS ACAD&Eacute;MICOS ELECTIVOS EXTR&Iacute;NSECOS REGISTRADOS EN EL SISTEMA
        </td>
    </tr>
</table>
            <?
        }


    }

    #Muestra los niveles existentes para el Plan de Estudios
    function listaEspacios($configuracion, $registro,$totalEspacios,$registroPlan)
    {
        $creditosNivel=0;
        $creditosTotal=0;
        $creditosAprobados=0;
        $idEncabezado=0;

        ?>
<table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
    <tr class="cuadro_plano">
        <td  align="center">
            <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
                <tbody>
                    <tr>
                        <td>
                            <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                <tr>
                                    <td>
                                        <table class='contenidotabla'>
        <?
        echo "<tr><td colspan='12' align='center'><h2>ELECTIVAS EXTR&Iacute;NSECAS</h2></td></tr>";
        ?>
                                            <tr class='cuadro_color'>
                                                    <?/*<td class='cuadro_plano centrar'>Nivel </td>*/?>
                                                <td class='cuadro_plano centrar'>Cod. </td>
                                                <td class='cuadro_plano centrar'>Nombre </td>
                                                <td class='cuadro_plano centrar'>N&uacute;mero<br>Cr&eacute;ditos</td>
                                                <td class='cuadro_plano centrar'>HTD </td>
                                                <td class='cuadro_plano centrar'>HTC </td>
                                                <td class='cuadro_plano centrar'>HTA </td>
                                                <td class='cuadro_plano centrar'>Clasificaci&oacute;n </td>
                                                <td class='cuadro_plano centrar' colspan="2">Aprobado </td>
                                                <td class='cuadro_plano centrar' colspan="2">Solicitar </td>
                                                <td class='cuadro_plano centrar'>Comentarios </td>
                                            </tr>

        <?
        $ob=0;
        $creob=0;
                                                    $oc=0;
                                                    $creoc=0;
                                                    $ei=0;
                                                    $creei=0;
                                                    $ee=0;
                                                    $creee=0;

                                                    for($a=0; $a<$totalEspacios; $a++) {
                                                        
                                                            ?><tr>
                                                            <?/*<td class='cuadro_plano centrar'><strong><?echo $registro[$a][2]?></strong></td>*/

                                                            //Cuenta los creditos por nivel y los creditos total del plan de estudio
                                                            $creditosNivel+=$registro[$a][3];
                                                            $creditosTotal+=$registro[$a][3];

                                                            //Busca los comentarios no leidos
                                                            $valores=array('COD_ESPACIO'=>$registro[$a][0],'PLAN_ESPACIO'=>$registro[$a][12]);
                                                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"comentariosNoLeidos",$valores);
                                                            $comentariosNoLeidos=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
            ?>
                                                <td class='cuadro_plano centrar'><?echo $registro[$a][0]?></td>
                                                <td class='cuadro_plano'><?if($registro[$a][13]==32) {
                                                                echo $registro[$a][1]." (Anualizado)";
                                                            }else {
                echo $registro[$a][1];
            }?></td>
            <?//verificar que 3*creditos=HTD+HTC+HTA si no se cumple se muestran los registros en rojo
            if(48*$registro[$a][3]==($registro[$a][4]+$registro[$a][5]+$registro[$a][6])*$registro[$a][13]) {
                                                                ?>
                                                <td class='cuadro_plano centrar'><?echo $registro[$a][3]?></td>
                                                <td class='cuadro_plano centrar'><?echo $registro[$a][4]?></td>
                                                <td class='cuadro_plano centrar'><?echo $registro[$a][5]?></td>
                                                <td class='cuadro_plano centrar'><?echo $registro[$a][6]?></td>
                <?
            }else {
                                                                ?>
                                                <td class='cuadro_plano centrar'><font color='red'><?echo $registro[$a][3]?></font></td>
                                                <td class='cuadro_plano centrar'><font color='red'><?echo $registro[$a][4]?></font></td>
                                                <td class='cuadro_plano centrar'><font color='red'><?echo $registro[$a][5]?></font></td>
                                                <td class='cuadro_plano centrar'><font color='red'><?echo $registro[$a][6]?></font></td>
                                                                <?
                                                            }
                                                            ?>
                                                <td class='cuadro_plano'><?echo $registro[$a][7]?></td>

                                                            <?//verifica que este aprobado el espacio academico
                                                            switch ($registro[$a][8]) {
                                                                case '1':
                                                                    $ob++;
                                                                    $creob+=$registro[$a][3];
                                                                    break;
                                                                case '2':
                                                                    $oc++;
                                                                    $creoc+=$registro[$a][3];
                                                                    break;
                                                                case '3':
                                                                    $ei++;
                                                                    $creei+=$registro[$a][3];
                                                                    break;
                                                                case '4':
                                                                    $ee++;
                    $creee+=$registro[$a][3];
                    break;
                                                                }
                                                                if ($registro[$a][11]==1) {
                ?>
                                                <td class='cuadro_plano centrar' colspan="2">
                                                    Aprobado<?
                                                                    $creditosAprobados+=$registro[$a][3];
                                                                    ?></td>
                                                <td class='cuadro_plano centrar' colspan="2">
                                                </td>
                                                <td class='cuadro_plano centrar'>
                                                                    <?
                                                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                    $variables="pagina=registroAgregarComentarioEspacioCoordinador";
                                                                    $variables.="&opcion=verComentarios";
                                                                    $variables.="&codEspacio=".$registro[$a][0];
                                                                    $variables.="&planEstudio=".$registro[$a][12];
                                                                    $variables.="&nivel=".$registro[$a][2];
                                                                    $variables.="&creditos=".$registro[$a][3];
                                                                    $variables.="&htd=".$registro[$a][4];
                                                                    $variables.="&htc=".$registro[$a][5];
                                                                    $variables.="&hta=".$registro[$a][6];
                                                                    $variables.="&clasificacion=".$registro[$a][8];
                                                                    $variables.="&nombreEspacio=". $registro[$a][1];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                                                                        ?>
                                                    <a href="<?echo $pagina.$variables?>" class="centrar">
                                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/viewrel.png" width="25" height="25" border="0"><br>


                                                                        <?
                                                                        if($comentariosNoLeidos[0][0]>0) {
                                                                            echo "Nuevos(".$comentariosNoLeidos[0][0].")";

                }else {

                                                                }
                ?>
                                                    </a>
                                                </td><?
            }else if ($registro[$a][11]==2) {
                ?>
                                                <td class='cuadro_plano centrar' colspan="2">
                                                    No aprobado</td>
                                                <td class='cuadro_plano centrar' colspan="2">
                                                </td>
                                                <td class='cuadro_plano centrar'>
                                                                    <?
                                                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                    $variables="pagina=registroAgregarComentarioEspacioCoordinador";
                                                                    $variables.="&opcion=verComentarios";
                                                                    $variables.="&codEspacio=".$registro[$a][0];
                                                                    $variables.="&planEstudio=".$registro[$a][12];
                                                                    $variables.="&nivel=".$registro[$a][2];
                                                                    $variables.="&creditos=".$registro[$a][3];
                                                                    $variables.="&htd=".$registro[$a][4];
                                                                    $variables.="&htc=".$registro[$a][5];
                                                                    $variables.="&hta=".$registro[$a][6];
                                                                    $variables.="&clasificacion=".$registro[$a][8];
                                                                    $variables.="&nombreEspacio=". $registro[$a][1];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                                                                        ?>
                                                    <a href="<?echo $pagina.$variables?>" class="centrar">
                                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/viewrel.png" width="25" height="25" border="0"><br>


                                                                        <?
                                                                        if(count($comentariosNoLeidos)>0) {
                                                                            echo "Nuevos(".count($comentariosNoLeidos).")";

                }else {

                                                                }
                                                                ?>
                                                    </a>
                                                </td><?
            }
            else {
                                                                    ?>
                                                <td class='cuadro_plano centrar' colspan="2">
                                                    En Proceso
                                                </td>
                                                <td class='cuadro_plano centrar'>
                                                                    <?
                                                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                    $variables="pagina=registroPortafolioElectivasCoordinador";
                                                                    $variables.="&opcion=modificarEspacio";
                                                                    $variables.="&codEspacio=".$registro[$a][0];
                                                                    $variables.="&planEstudio=".$registro[$a][12];
                                                                    $variables.="&nivel=".$registro[$a][2];
                                                                    $variables.="&nroCreditos=".$registro[$a][3];
                                                                    $variables.="&htd=".$registro[$a][4];
                                                                    $variables.="&htc=".$registro[$a][5];
                                                                    $variables.="&hta=".$registro[$a][6];
                                                                    $variables.="&clasificacion=".$registro[$a][8];
                                                                    $variables.="&nombreEspacio=".$registro[$a][1];
                                                                    $variables.="&codProyecto=".$registroPlan[0][1];
                                                                    $variables.="&nombreProyecto=".$registroPlan[0][2];
                                                                    $variables.="&semanas=".$registro[$a][13];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                ?>

                                                    <a href="<?echo $pagina.$variables?>" class="centrar">
                                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/editarGrande.png" width="25" height="25" border="0"><br><font size="1">Editar</font>
                                                    </a>

                                                </td>
                                                <td class='cuadro_plano centrar'>
                                                                    <?
                                                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                    $variables="pagina=registroPortafolioElectivasCoordinador";
                                                                    $variables.="&opcion=confirmarBorrarEA";
                                                                    $variables.="&codEspacio=".$registro[$a][0];
                                                                    $variables.="&planEstudio=".$registro[$a][12];
                                                                    $variables.="&nivel=".$registro[$a][2];
                                                                    $variables.="&nroCreditos=".$registro[$a][3];
                                                                    $variables.="&htd=".$registro[$a][4];
                                                                    $variables.="&htc=".$registro[$a][5];
                                                                    $variables.="&hta=".$registro[$a][6];
                                                                    $variables.="&clasificacion=".$registro[$a][8];
                                                                    $variables.="&nombreEspacio=".$registro[$a][1];
                                                                    $variables.="&semanas=".$registro[$a][13];
                                                                    $variables.="&nombreProyecto=".$registroPlan[0][2];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                ?>

                                                    <a href="<?echo $pagina.$variables?>" class="centrar">
                                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/x.png" width="25" height="25" border="0"><br><font size="1">Borrar</font>
                                                    </a>


                                                </td>
                                                <td class='cuadro_plano centrar'>
                                                                    <?
                                                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                    $variables="pagina=registroAgregarComentarioEspacioCoordinador";
                                                                    $variables.="&opcion=verComentarios";
                                                                    $variables.="&codEspacio=".$registro[$a][0];
                                                                    $variables.="&planEstudio=".$registro[$a][12];
                                                                    $variables.="&nivel=".$registro[$a][2];
                                                                    $variables.="&creditos=".$registro[$a][3];
                                                                    $variables.="&htd=".$registro[$a][4];
                                                                    $variables.="&htc=".$registro[$a][5];
                                                                    $variables.="&hta=".$registro[$a][6];
                                                                    $variables.="&clasificacion=".$registro[$a][8];
                                                                    $variables.="&nombreEspacio=".$registro[$a][1];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                                                                        ?>
                                                    <a href="<?echo $pagina.$variables?>" class="centrar">
                                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/viewrel.png" width="25" height="25" border="0"><br>


                                                                        <?
                                                                        if(count($comentariosNoLeidos)>0) {
                                                                            echo "Nuevos(".count($comentariosNoLeidos).")";

                }else {

                                                                }
                                                                ?>
                                                    </a>
                                                </td>
                <?

            }

                                                        ?>

                                            </tr>

                                                                <? //Mensaje al final de cada semestre numero de creditos
                                                                if((isset($registro[$a+1][2])?$registro[$a+1][2]:'')=='') {
                                                                    ?>
                                            <tr><td class='cuadro_plano centrar'  colspan='6'>TOTAL CRÉDITOS:
                                                                    <?
                                                                    if($registroPlan[0][0]=='261' ||$registroPlan[0][0]=='262'||$registroPlan[0][0]=='263'||$registroPlan[0][0]=='269') {
                                                                        $creditosNiveles='36';
                                                                    }else {
                                                                        $creditosNiveles='18';
                                                                    }

                                                                    if($creditosNivel>$creditosNiveles) {
                                                                        ?><font color=red><?echo $creditosNivel?></font><?
                }else {
                    ?><font color=blue><?echo $creditosNivel?></font><?
                                                                    }
                                                                    ?>
                                                </td>
                                                <td class='cuadro_plano centrar'  colspan='6'>TOTAL CRÉDITOS APROBADOS:
                                                                    <?
                                                                    if($creditosAprobados>$creditosNiveles) {
                                                                        ?><font color=red><?echo $creditosAprobados?></font><?
                }else {
                    ?><font color=blue><?echo $creditosAprobados?></font><?
                }
                ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan='10'></td>
                                            </tr><?
                                                            $creditosNivel=0;
                                                            $creditosAprobados=0;
                                                        }


        }//unset($registroEncabezados);


        ?>
                                        </table>
                                        <table border="0" width="100%">

                                            <tr>
                                                <td class="cuadro_plano centrar">
                                                    H.T.D : Horas de Trabajo Directo<br>
                                                    H.T.C : Horas de Trabajo Cooperativo<br>
                                                    H.T.A : Horas de Trabajo Autonomo
                                                </td>
                                            </tr>
                                        </table>                                        
                            </table>
                        </td>

                    </tr>

            </table>
        </td>
    </tr>

</tbody>
</table>


        <?

        ?>
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

        if($clasificacion=='') {
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
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");//echo $cadena_sql;exit;
        $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

        ?>
<table class="contenidotabla centrar" width="100%" border="0">
    <tr>
                <?
                for($i=0;$i<count($resultado_clasificacion);$i++) {
                    if($resultado_clasificacion[$i][0]==$clasificacion) {
                        ?>
        <td class="centrar"><font size="2"><b>Clasificaci&oacute;n del espacio acad&eacute;mico: <?echo $resultado_clasificacion[$i][1]?></b></font></td>
                        <?

                    }
                }
                ?>
    </tr>
</table>

        <?

        $this->formularioCreacion($configuracion,$variable,$_REQUEST);

    }


    function formularioCreacion($configuracion,$variable,$datos)
    {
        ?>
<table class="contenidotabla centrar" width="100%" border="0">
    <tr>
        <td class="cuadro_color centrar" colspan="3">
            <font size="2"> Todos los campos marcados con <font size="2" color="red">*</font> son obligatorios</font>
        </td>
    </tr>
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <tr>
            <td colspan="2">
                <font size="2" color="red">*</font> Nombre del Espacio:
            </td>
            <td>
                <input type="text" name="nombreEspacio" size="50" maxlength="80" value="<?echo $datos['nombreEspacio']?>">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <font size="2" color="red">*</font> N&uacute;mero de Cr&eacute;ditos:
            </td>
            <td>
                <input type="text" name="nroCreditos" size="5" maxlength="5" value="<?echo $datos['nroCreditos']?>">
            </td>
        </tr>
        <table class="contenidotabla centrar" width="100%" border="0">
            <tr>
                <td colspan="3" align="center"> <font size="2"><b>Distribuci&oacute;n</b></font></td>
            </tr>
            <tr class="centrar">
                <td width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Directo
                </td>
                <td width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Cooperativo
                </td>
                <td width="33%">
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
                <td colspan="3" >
                    <font size="2" color="red">*</font>N&uacute;mero de semanas en que se cursa el espacio ac&aacute;demico
                </td>
            </tr>
            <tr class="centrar">
                <td colspan="3">
                    <select name="semanas" id="<? echo $datos['semanas'];?>" style="width:270px">
                        <option value="16" <? if($datos['semanas']==16) {
            echo "selected=16";
        } ?>>Espacios Semestrales (16 semanas)</option>
                        <option value="32" <? if($datos['semanas']==32) {
            echo "selected=32";
        } ?>>Espacios Anuales (32 semanas)</option>
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
                    <input type="hidden" name="nivel" value="0">
                    <input type="hidden" name="opcion" value="validarEA">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="submit" value="Guardar" >
                </td>
                <td class="centrar" width="50%">
                    <input type="reset" >
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
        //var_dump($_REQUEST);exit;

        $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion, $nombreEspacio, $nroCreditos, $nivel, $htd, $htc, $hta);



        if(($nombreEspacio=='')||($nroCreditos=='')||($nivel=='')||($htd=='')||($htc=='')||($hta=='')) {
            echo "<script>alert('Todos los campos deben ser diligenciados')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroPortafolioElectivasCoordinador";
            $variables.="&opcion=crear";
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

        if(!is_numeric($nivel)||!is_numeric($nroCreditos)||!is_numeric($htd)||!is_numeric($htc)||!is_numeric($hta)) {
            echo "<script>alert('Los campos (Creditos, Nivel, HTD, HTC, HTA) deben ser númericos')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroPortafolioElectivasCoordinador";
            $variables.="&opcion=crear";
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

        //Determina la distribucion por semestre
        //$totalDistribucion=$hta+$htc+$htd;
        //$horasCreditos=$nroCreditos*3;

        //Determina la distribucion segun las semanas seleccionadas(Semestralizado 16, Anualizado 32)
        $totalDistribucion=($hta+$htc+$htd)*$semanas;
        $horasCreditos=$nroCreditos*48;

        if($totalDistribucion!=$horasCreditos) {
            echo "<script>alert('La distribución seleccionada no concuerda con la cantidad de créditos')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroPortafolioElectivasCoordinador";
            $variables.="&opcion=crear";
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
<table class="contenidotabla centrar" width="100%" border="0">
    <tr>
        <td class="cuador_color centrar" colspan="3">
            <font size="2">Al espacio que se va a crear se le asign&oacute; el c&oacute;digo<b> <?echo $variable[10]?></b> y contiene la siguiente informaci&oacute;n:</font>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%" ><font size="2">Cod&iacute;go del Espacio Acad&eacute;mico:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[10]?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%"><font size="2">Nombre del Espacio Acad&eacute;mico:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[4]?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%"><font size="2">Clasificaci&oacute;n:</font></td>
        <?
        for($i=0;$i<count($resultado_clasificacion);$i++) {
            if($resultado_clasificacion[$i][0]==$variable[3]) {
                ?>
        <td class="cuadro_plano" colspan="3"><font size="2"><?echo $resultado_clasificacion[$i][1]?></font></td>
                        <?
                    }
                }
                ?>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%"><font size="2">N&uacute;mero de Cr&eacute;ditos:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[5]?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%"><font size="2">Horas de Trabajo Directo:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[7]?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%"><font size="2">Horas de Trabajo Cooperativo:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[8]?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%"><font size="2">Horas de Trabajo Autonomo:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[9]?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%"><font size="2">N&uacute;mero de semanas en que se cursa el espacio ac&aacute;demico:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[11]?></font></td>
    </tr>
    <tr>
        <td class="cuadro_color_plano centrar" colspan="3"><br><font size="2">¿Desea guardar la informaci&oacute;n anteriormente diligenciada?</font></td>
    </tr>
    <tr>
        <td width="33%" class="centrar"><br>
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
        <td width="33%" class="centrar"><br>
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
                <input type="hidden" name="opcion" value="modificarCreacion">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" value="modificar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/modificar.png" width="35" height="35"><br>Modificar
            </form>
        </td>
        <td width="33%" class="centrar"><br>
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
                  $variables.="&opcion=".$this->opcion;
                  $variables.="&planEstudio=".$planEstudio;
                  $variables.="&codProyecto=".$codProyecto;
                  $variables.="&nombreProyecto=".$nombreProyecto;

                  include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                  $this->cripto=new encriptar();
                  $variables=$this->cripto->codificar_url($variables,$configuracion);
                  echo "<script>location.replace('".$pagina.$variables."')</script>";
                  exit;
              }

//var_dump($_REQUEST);exit;
        $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion,$nombreEspacio,$nroCreditos,$nivel,$htd,$htc,$hta,$id_espacio,$semanas);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registro_espacioAcademico",$variable);//echo $cadena_sql;exit;
        $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_espacioAcad);exit;

        if($resultado_espacioAcad == true) {

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registro_planEstudio",$variable);//echo $cadena_sql;exit;
            $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

            if($resultado_planEstudio == true) {

                $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                $ano=$resultadoPeriodo[0][0];
                $periodo=$resultadoPeriodo[0][1];

                $variablesRegistro=array($usuario, date('YmdHis'), $ano, $periodo, $id_espacio, $planEstudio, $codProyecto);
                $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroLogEvento",$variablesRegistro);
                $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");

                $variables=array($planEstudio, $codProyecto, $usuario, date('YmdHis'),$id_espacio,$nombreEspacio);
                $cadena_sql_comentario=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"ingresarComentario", $variables);//echo $cadena_sql_comentario;exit;
                $resultadoComentario=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_comentario,"" );//var_dump($resultadoComentario);exit;

                echo "<script>alert('El Espacio Académico ".$nombreEspacio." se ha creado para su posterior aprobación ')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroPortafolioElectivasCoordinador";
                $variables.="&opcion=ver";
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

            }else {

                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_registroEspacio",$variable);//echo $cadena_sql;exit;
                $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

                echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde ')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroPortafolioElectivasCoordinador";
                $variables.="&opcion=ver";
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
        }else {
            echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde ')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroPortafolioElectivasCoordinador";
            $variables.="&opcion=ver";
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


    function modificarEspacioElectivo($configuracion)
    {
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nivel=$_REQUEST['nivel'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
        $clasificacion=$_REQUEST['clasificacion'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $codProyecto=$_REQUEST['codProyecto'];
        $semanas=$_REQUEST['semanas'];

        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto)
        ?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
<table class="contenidotabla centrar">
    <tr>
        <td colspan="3" class="centrar">
            <h3><b>MODIFICAR ESPACIO ACAD&Eacute;MICO ELECTIVO EXTR&Iacute;NSECO</b></h3>
        </td>
    </tr>
    <tr>
        <td>
            Nombre Espacio Acad&eacute;mico
        </td>
        <td colspan="2">
            <input type="text" name="nombreEspacio" value="<?echo $nombreEspacio?>" size="45">
        </td>
    </tr>
    <tr>
        <td>
            Nro Cr&eacute;ditos
        </td>
        <td colspan="2">
            <input type="text" name="nroCreditos" value="<?echo $nroCreditos?>" size="3">
        </td>
    </tr>
    <tr>
        <td class="centrar" colspan="3">
            <h3><b>DISTRIBUCI&Oacute;N DE HORAS</b></h3>
        </td>
    </tr>
    <tr>
        <td class="centrar" width="33%">
            Horas Trabajo Directo
        </td>
        <td class="centrar" width="33%">
            Horas Trabajo Cooperativo
        </td>
        <td class="centrar" width="33%">
            Horas Trabajo Autonomo
        </td>
    </tr>
    <tr>
        <td class="centrar">
            <input type="text" name="htd" value="<?echo $htd?>" size="3">
        </td>
        <td class="centrar">
            <input type="text" name="htc" value="<?echo $htc?>" size="3">
        </td>
        <td class="centrar">
            <input type="text" name="hta" value="<?echo $hta?>" size="3">
        </td>
    </tr>
    <tr class="centrar">
        <td colspan="3">
            <select name="semanas" id="<? echo $_REQUEST['semanas'];?>" style="width:270px">
                <option value="16" <? if($_REQUEST['semanas']==16){echo "selected=16";} ?>>Espacios Semestrales (16 semanas)</option>
                <option value="32" <? if($_REQUEST['semanas']==32){echo "selected=32";} ?>>Espacios Anuales (32 semanas)</option>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="centrar">
            <table class="contenidotabla centrar">
                <tr>
                    <td class="centrar">
                        
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                            <input type="hidden" name="nivel" value="<?echo $nivel?>">
                            <input type="hidden" name="codEspacio" value="<?echo $codEspacio?>">
                            <input type="hidden" name="opcion" value="confirmarModificar">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" value="modificar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/modificar.png" width="35" height="35"><br>Modificar
                        </form>
                    </td>
                    <td class="centrar">
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                            <input type="hidden" name="nombreEspacio" value="<?echo $nombreEspacio?>">
                            <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                            <input type="hidden" name="nivel" value="<?echo $nivel?>">
                            <input type="hidden" name="htd" value="<?echo $htd?>">
                            <input type="hidden" name="htc" value="<?echo $htc?>">
                            <input type="hidden" name="hta" value="<?echo $hta?>">
                            <input type="hidden" name="codEspacio" value="<?echo $codEspacio?>">
                            <input type="hidden" name="semanas" value="<?echo $semanas?>">
                            <input type="hidden" name="opcion" value="cancelar">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" value="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="35" height="35"><br>Cancelar
                        </form>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?
    }


    function confirmacionModificarElectivo($configuracion)
    {
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nivel=$_REQUEST['nivel'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
        $clasificacion=$_REQUEST['clasificacion'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $codProyecto=$_REQUEST['codProyecto'];
        $semanas=$_REQUEST['semanas'];
//var_dump($_REQUEST);//exit;
        
        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto)
        ?>
<table class="contenidotabla centrar">
    <tr>
        <td colspan="3" class="cuadro_plano centrar">
            <h3>Por favor confirme la siguiente informaci&oacute;n relacionado al espacio acad&eacute;mico <?echo $codEspacio?></h3>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano centrar">
            <font size="2"><b>Codigo Espacio Acad&eacute;mico</b></font>
        </td>
        <td colspan="2" class="cuadro_plano centrar">
            <font size="2"><?echo $codEspacio?></font>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano centrar">
            <font size="2"><b>Nombre Espacio Acad&eacute;mico</b></font>
        </td>
        <td colspan="2" class="cuadro_plano centrar">
            <font size="2"><?echo $nombreEspacio?></font>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano centrar">
            <font size="2"><b>Nro Cr&eacute;ditos</b></font>
        </td>
        <td colspan="2" class="cuadro_plano centrar">
            <font size="2"><?echo $nroCreditos?></font>
        </td>
    </tr>
    <tr>
        <td class="centrar" colspan="3" >
            <h3><b>DISTRIBUCI&Oacute;N DE HORAS</b></h3>
        </td>
    </tr>
    <tr>
        <td width="33%" class="cuadro_plano centrar">
            <font size="2"><b>Horas Trabajo Directo</b></font>
        </td>
        <td width="33%" class="cuadro_plano centrar">
            <font size="2"><b>Horas Trabajo Cooperativo</b></font>
        </td>
        <td width="33%" class="cuadro_plano centrar">
            <font size="2"><b>Horas Trabajo Autonomo</b></font>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano centrar">
            <font size="2"><?echo $htd?></font>
        </td>
        <td class="cuadro_plano centrar">
            <font size="2"><?echo $htc?></font>
        </td>
        <td class="cuadro_plano centrar">
            <font size="2"><?echo $hta?></font>
        </td>
    </tr>
    <tr class="centrar">
        <td colspan="3" class="cuadro_plano centrar">
            <? if($_REQUEST['semanas']==16){echo "Espacios Semestrales (16 semanas)";}?>
            <? if($_REQUEST['semanas']==32){echo "Espacios Anuales (32 semanas)";}?>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="centrar">
            <table class="contenidotabla centrar">
                <tr>
                    <td class="centrar">
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                            <input type="hidden" name="nombreEspacio" value="<?echo $nombreEspacio?>">
                            <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                            <input type="hidden" name="nivel" value="<?echo $nivel?>">
                            <input type="hidden" name="htd" value="<?echo $htd?>">
                            <input type="hidden" name="htc" value="<?echo $htc?>">
                            <input type="hidden" name="hta" value="<?echo $hta?>">
                            <input type="hidden" name="codEspacio" value="<?echo $codEspacio?>">
                            <input type="hidden" name="semanas" value="<?echo $semanas?>">
                            <input type="hidden" name="opcion" value="confirmadoModificacion">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" value="modificar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Modificar
                        </form>
                    </td>
                    <td class="centrar">
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                            <input type="hidden" name="nombreEspacio" value="<?echo $nombreEspacio?>">
                            <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                            <input type="hidden" name="nivel" value="<?echo $nivel?>">
                            <input type="hidden" name="htd" value="<?echo $htd?>">
                            <input type="hidden" name="htc" value="<?echo $htc?>">
                            <input type="hidden" name="hta" value="<?echo $hta?>">
                            <input type="hidden" name="codEspacio" value="<?echo $codEspacio?>">
                            <input type="hidden" name="semanas" value="<?echo $semanas?>">
                            <input type="hidden" name="opcion" value="modificar">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" value="modificar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/modificar.png" width="35" height="35"><br>Modificar
                        </form>
                    </td>
                    <td class="centrar">
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                            <input type="hidden" name="nombreEspacio" value="<?echo $nombreEspacio?>">
                            <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                            <input type="hidden" name="nivel" value="<?echo $nivel?>">
                            <input type="hidden" name="htd" value="<?echo $htd?>">
                            <input type="hidden" name="htc" value="<?echo $htc?>">
                            <input type="hidden" name="hta" value="<?echo $hta?>">
                            <input type="hidden" name="codEspacio" value="<?echo $codEspacio?>">
                            <input type="hidden" name="semanas" value="<?echo $semanas?>">
                            <input type="hidden" name="opcion" value="cancelar">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" value="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="35" height="35"><br>Cancelar
                        </form>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?
    }


    function actualizarElectivo($configuracion)
    {
        $codProyecto=$_REQUEST['codProyecto'];
        $codEspacio=$_REQUEST['codEspacio'];
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

        //var_dump($_REQUEST);exit;

        if(($nombreEspacio=='')||($nroCreditos=='')||($nivel=='')||($htd=='')||($htc=='')||($hta=='')) {
            echo "<script>alert('Todos los campos deben ser diligenciados')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroPortafolioElectivasCoordinador";
            $variables.="&opcion=modificarEspacio";
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
            $variables.="&codEspacio=".$codEspacio;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
            break;
        }

        if(!is_numeric($nivel)||!is_numeric($nroCreditos)||!is_numeric($htd)||!is_numeric($htc)||!is_numeric($hta)) {
            echo "<script>alert('Los campos (Creditos, Nivel, HTD, HTC, HTA) deben ser númericos')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroPortafolioElectivasCoordinador";
            $variables.="&opcion=modificarEspacio";
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
            $variables.="&codEspacio=".$codEspacio;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
            break;
        }

        //Determina la distribucion por semestre
        //$totalDistribucion=$hta+$htc+$htd;
        //$horasCreditos=$nroCreditos*3;

        //Determina la distribucion segun las semanas seleccionadas(Semestralizado 16, Anualizado 32)
        $totalDistribucion=($hta+$htc+$htd)*$semanas;
        $horasCreditos=$nroCreditos*48;

        if($totalDistribucion!=$horasCreditos) {
            echo "<script>alert('La distribución seleccionada no concuerda con la cantidad de créditos')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroPortafolioElectivasCoordinador";
            $variables.="&opcion=modificarEspacio";
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
            $variables.="&codEspacio=".$codEspacio;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
            break;
        }

        $variable=array($codEspacio,$nombreEspacio,$nroCreditos,$htd,$htc,$hta,$nivel,$clasificacion,$planEstudio,$codProyecto,$semanas);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizar_espacioAcademico",$variable);//echo $cadena_sql;exit;
        $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_espacioAcad);exit;

         if($resultado_espacioAcad == true) {

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizar_planEstudio",$variable);//echo $cadena_sql;exit;
            $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

            if($resultado_planEstudio == true) {

                $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                $ano=$resultadoPeriodo[0][0];
                $periodo=$resultadoPeriodo[0][1];

                $variablesRegistro=array($usuario, date('YmdHis'), $ano, $periodo, $codEspacio, $planEstudio, $codProyecto);
                $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroLogEvento",$variablesRegistro);
                $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");

                $variables=array($planEstudio, $codProyecto, $this->usuario, date('YmdHis'),$codEspacio,$nombreEspacio);
                $cadena_sql_comentario=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"ingresarComentario", $variables);//echo $cadena_sql_comentario;exit;
                $resultadoComentario=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_comentario,"" );//var_dump($resultadoComentario);exit;

                echo "<script>alert('El Espacio Académico ".$nombreEspacio." se ha modificado para su posterior aprobación ')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroPortafolioElectivasCoordinador";
                $variables.="&opcion=ver";
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

            }else {

                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_registroEspacio",$variable);//echo $cadena_sql;exit;
                $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

                echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroPortafolioElectivasCoordinador";
                $variables.="&opcion=confirmarModificar";
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
                $variables.="&codEspacio=".$codEspacio;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }
        }else {
            echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde ')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroPortafolioElectivasCoordinador";
            $variables.="&opcion=confirmarModificar";
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
            $variables.="&codEspacio=".$codEspacio;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
            break;
        }


    }


    function confirmarBorrar($configuracion)
    {
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nivel=$_REQUEST['nivel'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
        $clasificacion=$_REQUEST['clasificacion'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $codProyecto=$_REQUEST['codProyecto'];
        $semanas=$_REQUEST['semanas'];

        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto)
        ?>
        <table class="contenidotabla centrar">
    <tr>
        <td colspan="3" class="cuadro_plano centrar">
            ¿Realmente desea borrar el espacio acad&eacute;mico <?echo $nombreEspacio?>?
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano centrar">
            <font size="2">Codigo Espacio Acad&eacute;mico</font>
        </td>
        <td colspan="2" class="cuadro_plano centrar">
            <font size="2"><?echo $codEspacio?></font>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano centrar">
            <font size="2">Nombre Espacio Acad&eacute;mico</font>
        </td>
        <td colspan="2" class="cuadro_plano centrar">
            <font size="2"><?echo $nombreEspacio?></font>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano centrar">
            <font size="2">Nro Cr&eacute;ditos</font>
        </td>
        <td colspan="2" class="cuadro_plano centrar">
            <font size="2"><?echo $nroCreditos?></font>
        </td>
    </tr>
    <tr>
        <td class="centrar" valign="middle" colspan="3" >
            <h3>DISTRIBUCI&Oacute;N DE HORAS</h3>
        </td>
    </tr>
    <tr>
        <td width="33%" class="cuadro_plano centrar">
            <font size="2">Horas Trabajo Directo</font>
        </td>
        <td width="33%" class="cuadro_plano centrar">
            <font size="2">Horas Trabajo Cooperativo</font>
        </td>
        <td width="33%" class="cuadro_plano centrar">
            <font size="2">Horas Trabajo Autonomo</font>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano centrar">
            <font size="2"><?echo $htd?></font>
        </td>
        <td class="cuadro_plano centrar">
            <font size="2"><?echo $htc?></font>
        </td>
        <td class="cuadro_plano centrar">
            <font size="2"><?echo $hta?></font>
        </td>
    </tr>
    <tr class="centrar">
        <td colspan="3" class="cuadro_plano centrar">
            <? if($_REQUEST['semanas']==16){echo "Espacios Semestrales (16 semanas)";}?>
            <? if($_REQUEST['semanas']==32){echo "Espacios Anuales (32 semanas)";}?>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="centrar">
            <table class="contenidotabla centrar">
                <tr>
                    <td class="centrar">
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                            <input type="hidden" name="nombreEspacio" value="<?echo $nombreEspacio?>">
                            <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                            <input type="hidden" name="nivel" value="<?echo $nivel?>">
                            <input type="hidden" name="htd" value="<?echo $htd?>">
                            <input type="hidden" name="htc" value="<?echo $htc?>">
                            <input type="hidden" name="hta" value="<?echo $hta?>">
                            <input type="hidden" name="codEspacio" value="<?echo $codEspacio?>">
                            <input type="hidden" name="semanas" value="<?echo $semanas?>">
                            <input type="hidden" name="opcion" value="confirmadoBorrado">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" value="modificar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
                        </form>
                    </td>
                    <td class="centrar">
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                            <input type="hidden" name="nombreEspacio" value="<?echo $nombreEspacio?>">
                            <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                            <input type="hidden" name="nivel" value="<?echo $nivel?>">
                            <input type="hidden" name="htd" value="<?echo $htd?>">
                            <input type="hidden" name="htc" value="<?echo $htc?>">
                            <input type="hidden" name="hta" value="<?echo $hta?>">
                            <input type="hidden" name="codEspacio" value="<?echo $codEspacio?>">
                            <input type="hidden" name="semanas" value="<?echo $semanas?>">
                            <input type="hidden" name="opcion" value="cancelar">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" value="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="35" height="35"><br>No
                        </form>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?
    }


    function BorrarElectivo($configuracion)
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
        $codEspacio=$_REQUEST['codEspacio'];
        $semanas=$_REQUEST['semanas'];
//var_dump($_REQUEST);exit;
        $variable=array($codEspacio,$nombreEspacio,$nroCreditos,$htd,$htc,$hta,$nivel,$clasificacion,$planEstudio,$codProyecto,$semanas);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_espacioAcademico",$variable);//echo $cadena_sql;exit;
        $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_espacioAcad);exit;

        if($resultado_espacioAcad == true) {

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_planEstudio",$variable);//echo $cadena_sql;exit;
            $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

            if($resultado_planEstudio == true) {

                $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                $ano=$resultadoPeriodo[0][0];
                $periodo=$resultadoPeriodo[0][1];

                $variablesRegistro=array($usuario, date('YmdHis'), $ano, $periodo, $codEspacio, $planEstudio, $codProyecto);
                $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroLogEvento",$variablesRegistro);
                $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");

                echo "<script>alert('El Espacio Académico ".$nombreEspacio." se ha borrado del sistema')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroPortafolioElectivasCoordinador";
                $variables.="&opcion=ver";
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
    }

}


?>
