<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroBorrarEACoordinador extends funcionGeneral {
    //Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        $this->formulario="registroBorrarEACoordinador";
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
<table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png " alt="Logo Universidad">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="4">
            PLAN DE ESTUDIO: <?echo $planEstudio?></h4>
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
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
        </td>
    </tr>
</table><?
    }

    function validarinformacion($configuracion) {
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
        $aprobado=(isset($_REQUEST['aprobado'])?$_REQUEST['aprobado']:0);

        $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion, $nombreEspacio, $nroCreditos, $nivel, $htd, $htc, $hta);

        if(($nombreEspacio=='')||($nroCreditos=='')||($nivel=='')||($htd=='')||($htc=='')||($hta=='')) {
            echo "<script>alert('Todos los campos deben ser diligenciados')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroModificarEACoordinador";
            $variables.="&opcion=solicitar";
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
            $variables.="&aprobado=".$aprobado;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
            break;
        }

        $totalDistribucion=$hta+$htc+$htd;
        $horasCreditos=$nroCreditos*3;
        $variable[10]=$codEspacio;
        $variable[11]=$aprobado;
        $this->solicitarConfirmacion($configuracion,$variable);
    }

    function solicitarConfirmacion($configuracion,$variable) {
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");
        $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"notasEstudiantesPlan",$variable);
        $resultadoNotasPlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $this->encabezadoModulo($configuracion,$variable[0],$variable[1],$variable[2]);
        ?>
<table class="contenidotabla centrar" width="100%" border="0">
    <?
    if(isset($resultadoNotasPlan)&&$resultadoNotasPlan[0][0]>0)
    {?>
        <tr>
            <td class="cuador_color centrar" colspan="3">
                <font size="2" color="red">Existen estudiantes en este plan de estudios con notas del Espacio Acad&eacute;mico.</font>
            </td>
        </tr>
    <?}
    ?>
    <tr>
        <td class="cuador_color centrar" colspan="3">
            <font size="2">El espacio con cod&iacute;go<b> <?echo $variable[10]?></b>, contiene la siguiente informaci&oacute;n:</font>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%" ><font size="2">Plan de Estudio:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[0]?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%" ><font size="2">Cod&iacute;go del Espacio Acad&eacute;mico:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[10]?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%"><font size="2">Nombre del Espacio Acad&eacute;mico:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[4]?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%"><font size="2">Tipo de clasificaci&oacute;n:</font></td>
        <?
        for($i=0;$i<count($resultado_clasificacion);$i++) {
            if($resultado_clasificacion[$i][0]==$variable[3]) {
                        ?>
        <td class="cuadro_plano" colspan="3"><font size="2"><?echo strtr(strtoupper($resultado_clasificacion[$i][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");?></font></td>
                        <?
                    }
                }
                ?>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%"><font size="2">N&uacute;mero de Cr&eacute;ditos:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[5]?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="30%"><font size="2">Nivel:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[6]?></font></td>
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
        <?
        if (isset($variable[11])&&$variable[11]==1)
        {
        ?>
            <td class="cuadro_color_plano centrar" colspan="3"><br><font size="2">¿Realmente desea inactivar este Espacio Acad&eacute;mico para el Plan de Estudios <?echo $variable[0];?>?</font></td>
        <?
        }else{
            ?>
                <td class="cuadro_color_plano centrar" colspan="3"><br><font size="2">¿Realmente desea borrar este Espacio Acad&eacute;mico?</font></td>
            <?}?>
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
                <input type="hidden" name="codEspacio" value="<?echo $variable[10]?>">
                <input type="hidden" name="aprobado" value="<?echo $variable[11]?>">
                <input type="hidden" name="opcion" value="borrar">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" value="borrar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
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
                <input type="hidden" name="codEspacio" value="<?echo $variable[10]?>">
                <input type="hidden" name="aprobado" value="<?echo $variable[11]?>">
                <input type="hidden" name="opcion" value="cancelar">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" value="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="35" height="35"><br>No
            </form>
        </td>
    </tr>
</table>
        <?
    }

    function borrarEspacio($configuracion) {
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
        $aprobado=$_REQUEST['aprobado'];

        $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
        $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual, "busqueda");
        $ano=$resultadoPeriodo[0][0];
        $periodo=$resultadoPeriodo[0][1];
        
        $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion,$nombreEspacio,$nroCreditos,$nivel,$htd,$htc,$hta,$codEspacio,$aprobado);
        //si puede consultar el período
        if($resultadoPeriodo==true) {
            //si el espacio esta aprobadso para el plan
            if ($aprobado==1)
            {
                //inactiva el espacio en la DB academica
                $cadena_sql=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "inactivarEspacioPlan", $variable);
                $resultadoInactivarEspacioPlan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
                $total=$this->totalAfectados($configuracion, $this->accesoOracle);
                //Si pudo ejecutar la consulta y hay al menos una fila afectada, lo inactiva en MySQL
                if($resultadoInactivarEspacioPlan&&$total>0)
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrarEspacioPlanEstudio",$variable);
                    $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                    $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $codEspacio, $planEstudio, $codProyecto);
                    $cadena_sql_registroModificar=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroBorrarEA",$variablesRegistro);
                    $resultadoRegistroModificar=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroModificar,"");

                    echo "<script>alert('El Espacio Académico ".$nombreEspacio." ha sido inactivado en el plan de estudios ')</script>";
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
                }else
                    {
                       //si no se puedo inactivar en la DB Academica
                        echo "<script>alert('El Espacio Académico ".$nombreEspacio." no pudo inactivarse en el plan de estudios $planEstudio.')</script>";
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
                
            }else
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrarEspacioPlanEstudio",$variable);
                    $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

                    $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $codEspacio, $planEstudio, $codProyecto);
                    $cadena_sql_registroModificar=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroBorrarEA",$variablesRegistro);
                    $resultadoRegistroModificar=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroModificar,"");

                    echo "<script>alert('El Espacio Académico ".$nombreEspacio." ha sido borrado del plan de estudios ')</script>";
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
        }else {
            echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde 1 ')</script>";
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

    function formularioBorrarEncabezado($configuracion) {
        $id_encabezado=$_REQUEST['id_encabezado'];
        $encabezado_nombre=$_REQUEST['encabezado_nombre'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nivel=$_REQUEST['nivel'];
        $planEstudio=$_REQUEST['planEstudio'];
        $codProyecto=$_REQUEST['codProyecto'];
        $clasificacion=$_REQUEST['clasificacion'];
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");
        $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);
        ?>
<table class="contenidotabla centrar" width="100%" border="0">
    <tr>
        <td class="cuador_color centrar" colspan="3">
            <font size="2">El Nombre General <?echo $encabezado_nombre?> tiene la siguiente informaci&oacute;n</font>
        </td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="20%"><font size="2">Nombre:</font></td><td class="cuadro_plano" colspan="3" width="80%"><font size="2"><?echo $encabezado_nombre?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="20%"><font size="2">Clasificaci&oacute;n:</font></td>
        <?
        for($i=0;$i<count($resultado_clasificacion);$i++) {
            if($resultado_clasificacion[$i][0]==$clasificacion) {
                        ?>
        <td class="cuadro_plano" colspan="3" width="80%"><font size="2"><?echo strtr(strtoupper($resultado_clasificacion[$i][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");?></font></td>
                        <?
                    }
                }
                ?>
    </tr>
    <tr>
        <td class="cuadro_plano" width="20%"><font size="2">N&uacute;mero de Cr&eacute;ditos:</font></td><td class="cuadro_plano" colspan="3" width="80%"><font size="2"><?echo $nroCreditos?></font></td>
    </tr>
    <tr>
        <td class="cuadro_plano" width="20%"><font size="2">Nivel:</font></td><td class="cuadro_plano" colspan="3" width="80%"><font size="2"><?echo $nivel?></font></td>
    </tr>
    <tr>
        <td class="cuadro_color_plano centrar" colspan="3"><br><font size="2">¿Realmente desea borrar este Nombre General?</font></td>
    </tr>
    <tr>
        <td width="50%" class="centrar"><br>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                <input type="hidden" name="id_encabezado" value="<?echo $id_encabezado?>">
                <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                <input type="hidden" name="encabezado_nombre" value="<?echo $encabezado_nombre?>">
                <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                <input type="hidden" name="nivel" value="<?echo $nivel?>">
                <input type="hidden" name="opcion" value="borrarEncabezado">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" value="borrar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
            </form>
        </td>
        <td width="50%" class="centrar"><br>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                <input type="hidden" name="encabezado_nombre" value="<?echo $encabezado_nombre?>">
                <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                <input type="hidden" name="nivel" value="<?echo $nivel?>">
                <input type="hidden" name="opcion" value="cancelar">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" value="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="35" height="35"><br>No
            </form>
        </td>
    </tr>
</table>
        <?
    }

    function borrarEncabezado($configuracion){
        $id_encabezado=$_REQUEST['id_encabezado'];
        $encabezado_nombre=$_REQUEST['encabezado_nombre'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nivel=$_REQUEST['nivel'];
        $planEstudio=$_REQUEST['planEstudio'];
        $codProyecto=$_REQUEST['codProyecto'];
        $clasificacion=$_REQUEST['clasificacion'];

        $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
        $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual, "busqueda");
        $ano=$resultadoPeriodo[0][0];
        $periodo=$resultadoPeriodo[0][1];

        if($resultadoPeriodo==true) {
            $variable=array($id_encabezado,$encabezado_nombre,$nroCreditos,$nivel,$planEstudio,$codProyecto);

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEspaciosAsociados",$variable);
            $resultado_asociados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            if ($resultado_asociados[0][0]==0){
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrarEspacioEncabezado",$variable);
            $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );
            $totalAfectados=$this->totalAfectados($configuracion, $this->accesoGestion);

            if($totalAfectados>='1')
                {
                    $variablesRegistro=array($this->usuario, date('YmdGis'), $ano, $periodo, $id_encabezado, $planEstudio, $codProyecto);
                    $cadena_sql_registroModificar=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroBorrarEA",$variablesRegistro);
                    $resultadoRegistroModificar==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroModificar,"");

                    echo "<script>alert('El Espacio Académico ".$encabezado_nombre." ha sido borrado del plan de estudios ')</script>";
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
                else {
                        echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde')</script>";
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
    elseif($resultado_asociados[0][0]>0)
        {
                        echo "<script>alert('El nombre general no debe tener espacios asociados. No se puede borrar')</script>";
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
        else{
                        echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde')</script>";
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
}

    function cancelar($configuracion) {
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=".$this->pagina;
                    $variables.="&opcion=".$this->opcion;
                    $variables.="&planEstudio=".$_REQUEST['planEstudio'];
                    $variables.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variables.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    echo "<script>location.replace('".$pagina.$variables."')</script>";
                    break;
    }
}
?>
