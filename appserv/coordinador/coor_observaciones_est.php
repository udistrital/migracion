<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once("../calendario/calendario.php");
require_once(dir_script.'val-email.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
$accesoSGA=$conexion->estableceConexion(999);
fu_tipo_user(4);
//LLAMADO DE: coor_consulta_datos_est.php
?>
<HTML>
    <HEAD>
        <TITLE>Estudiantes</TITLE>
        <link href="../script/estilo.css" rel="stylesheet" type="text/css">
        <script language="JavaScript" src="../calendario/javascripts.js"></script>
        <SCRIPT language="JavaScript" type="text/javascript">
        function seleccion(){
          for(var i = 0; i < document.forms[0].tipo.length; i++ ){
              if(document.forms[0].tipo[i].checked){
                 document.forms[0].sex.value = document.forms[0].tipo[i].value;
                 break;
               }
          }
        }
        </SCRIPT>
    </HEAD>
<BODY>

<?php
$usuario = $_SESSION["usuario_login"];
global $raiz;
$nombreformulario = "dat";
$nombrecampo = "fecnac";

//consultamos datos iniciales del estudiante
require_once('msql_coor_consulta_observaciones_est.php');
$obs_estudiante = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
//Actualiza Observaciones
if($_REQUEST['actualizarObs']) {
    
   	require_once('msql_coor_actualiza_obs_est.php');
}
if($_REQUEST['registrarObs']) {
    
   	require_once('msql_coor_registra_obs_est.php');
}
if(isset($row_qry_act_obs))
{
    $alertaObs="SE ACTUALIZARON LOS DATOS DE LA OBSERVACI&Oacute;N ".$_REQUEST['cons']." DE ".$_REQUEST['ano']."-".$_REQUEST['per']." DEL ESTUDIANTE";
    $obs_estudiante = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
}
if(isset($row_qry_obs))
{
    $alertaObs="SE REGISTR&Oacute; LA OBSERVACI&Oacute;N PARA EL ESTUDIANTE";
    $obs_estudiante = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
}
if($_REQUEST['estcod'] == "")
{
	die("<h3>No hay registros para esta consulta.</h3>");
	exit;
}
$columnas=6;
$estados_obs=array('A'=>'ACTIVO',
                    'I'=>'INACTIVO');
$anos=range(1972, date('Y'));
$periodos=range(1, 3);

if(is_array($obs_estudiante))
{
    ?><br><br>
    <table border="0" width="746" cellspacing="3" cellpadding="1">
    <caption>OBSERVACIONES</caption>
        <tr>
            <td width="100%" align="center" colspan="<?echo $columnas;?>"><span class="Estilo10"><b><?echo $alertaObs;?></b></span></td>
        </tr>

        <tr> 
            <td colspan="'.$columnas.'" align="right">&nbsp;</td>
        </tr>
        <tr>
            <td width="5%" align="center"><span class="Estilo5">A&Ntilde;O</span></td>
            <td width="5%" align="center"><span class="Estilo5">PER</span></td>
            <td width="5%" align="center"><span class="Estilo5">No.</span></td>
            <td width="66%" align="center"><span class="Estilo5">OBSERVACI&Oacute;N</span></td>
            <td width="8%" align="center"><span class="Estilo5">ESTADO</span></td>
            <td width="11%" align="center"><span class="Estilo5">ACTUALIZAR</span></td>
        </tr>
    <?
    foreach ($obs_estudiante as $key => $obser_est)
    {
    ?>
        <tr>
            <form name="dat" method=post action="coor_actualiza_datos_est.php">
                <td align="center"><?echo $obser_est[1];?><input name="ano" type="hidden" id="ano" value="<?echo $obser_est[1];?>" size="10" readonly></td>
                <td align="center"><?echo $obser_est[2];?><input name="per" type="hidden" id="per" value="<?echo $obser_est[2];?>" size="10" readonly></td>
                <td align="center"><?echo $obser_est[3];?><input name="cons" type="hidden" id="cons" value="<?echo $obser_est[3]?>" size="10" readonly></td>
                <td ><textarea style="resize:none"name="obs" id="obs" cols="62" rows="1" maxlength="256"><?echo $obser_est[4];?></textarea></td>
                <td align="center">
                    <select size="1" name="estado" >
                       <?  foreach ($estados_obs as $key2=>$estado_obs)
                       {
                           if (trim($obser_est[5])==$key2)
                           {echo'<option value="'.$key2.'" selected>'.$estado_obs[$key2].'</option>';
                               }else{echo'<option value="'.$key2.'" >'.$estado_obs[$key2].'</option>';}
                       }
                       ?>
                     </select>
                </td>
                <td align="center"><input type=submit name="actualizarObs" value="Actualizar"></td>
                <input name="estcod" type="hidden" id="estcod" value="<?echo $_REQUEST['estcod']?>" size="10" readonly>
                <input name="nro" type="hidden" id="nro" value="<?echo $key?>" size="10" readonly>
                <input name="cracod" type="hidden" id="cracod" value="<?echo $obs_estudiante[0][6]?>" size="10" readonly>
            </form>
        </tr>
    <?

    }    
}else{
        echo"<h3>No hay registros de observaciones para el estudiante.</h3>";
        ?>
        <br>
        <table border="0" width="746" cellspacing="3" cellpadding="1">
        <?
        $obs_estudiante[0][6]=$datos_estudiante[0][23];
    }
?>
        <tr>
            <td colspan="<?echo $columnas?>"><br><HR></td>
        </tr>
        <tr>
            <td align="center" colspan="<?echo $columnas?>"><span class="Estilo5">REGISTRAR OBSERVACI&Oacute;N</span></td>
        </tr>
        <tr>
            <td width="5%" align="center">A&Ntilde;O</td>
            <td width="5%" align="center">PER</td>
            <td width="79%" align="center" colspan='3'>OBSERVACI&Oacute;N</td>
            <td width="11%" align="center"></td>
        </tr>    
        <tr>
            <form name="dat" method=post action="coor_actualiza_datos_est.php">
                <td align="center">
                    <select size="1" name="ano" >
                       <?  foreach ($anos as $key=>$ano)
                       {
                           if ($ano==date('Y'))
                           {echo'<option value="'.$ano.'" selected>'.$ano.'</option>';
                               }else{echo'<option value="'.$ano.'" >'.$ano.'</option>';}
                       }
                       ?>
                     </select>
                </td>
                <td align="center">
                    <select size="1" name="periodo" >
                       <?  foreach ($periodos as $key=>$per)
                       {
                           if (date('n')<7&&$per==1)
                           {echo'<option value="'.$per.'" selected>'.$per.'</option>';
                               }elseif (date('n')>=7&&$per==3)
                                   {echo'<option value="'.$per.'"  selected>'.$per.'</option>';}
                                   else{echo'<option value="'.$per.'" >'.$per.'</option>';}
                       }
                       ?>
                     </select>
                </td>
                <td  colspan='3'><textarea style="resize:vertical"name="obs" id="obs" cols="72" rows="2" maxlength="256"></textarea></td>
                <td align="center"><input type=submit name="registrarObs" value="Registrar"></td>
                <input name="estcod" type="hidden" id="estcod" value="<?echo $_REQUEST['estcod']?>" size="10" readonly>
                <input name="cracod" type="hidden" id="cracod" value="<?echo $obs_estudiante[0][6]?>" size="10" readonly>
            </form>
        </tr>

    <?
    require_once(dir_script.'mensaje_error.inc.php');
    if(isset($_REQUEST['error_login'])){
       $error=$_REQUEST['error_login'];
       echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
                    <a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</a></font>";
    }
    echo'</table>';

?>
</BODY>
</HTML>
