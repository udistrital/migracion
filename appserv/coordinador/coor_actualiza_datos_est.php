<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once("../calendario/calendario.php");
require_once(dir_script.'val-email.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
include_once('../clase/validacion_usu.class.php');

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
$accesoSGA=$conexion->estableceConexion(999);
$obj_validacion=new validarUsu();

if(!$_REQUEST['tipo']){
    $_REQUEST['tipo']=$_SESSION['usuario_nivel'];
}

if($_REQUEST['tipo']==110){
    fu_tipo_user(110);
    $tipo=110; 
}elseif($_REQUEST['tipo']==114){
    fu_tipo_user(114);
    $tipo=114; 
}else{
fu_tipo_user(4);
    $tipo=4; 
}
//LLAMADO DE: coor_consulta_datos_est.php
if($tipo==110 || $tipo==114){
            $verificacion=$obj_validacion->validarProyectoAsistente($_REQUEST['estcod'],$_SESSION['usuario_login'],$conexion,$configuracion,$accesoOracle);
            if($verificacion!='ok')
                {
?>
                          <table class="contenidotabla centrar">
                            <tr>
                              <td class="cuadro_brownOscuro centrar">
                                  <?echo $verificacion;?>
                              </td>
                            </tr>
                          </table>
                    <?
                    exit;
                }
            }
        
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
require_once('msql_coor_consulta_datos_est.php');
//echo "<br>consulta est".$consulta;
if($tipo==4){
    $datos_estudiante = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
}elseif($tipo==110 || $tipo==114){
    $datos_estudiante = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta_asistente,"busqueda");
}
//Actualiza datos
if(isset($_REQUEST['actualizar'])) {
    
   	require_once('msql_coor_actualiza_datos_est.php');
}
if(isset($row_qry)||isset($row_reg))
{	
	$alerta="SE ACTUALIZARON LOS DATOS DEL ESTUDIANTE";
}

//Edita los datos
$datos = "SELECT lug_cod,lug_nombre FROM gelugar ORDER BY lug_nombre";
$rowDatos = $conexion->ejecutarSQL($configuracion,$accesoOracle,$datos,"busqueda");

$consulta_estados= "SELECT ESTADO_COD, ESTADO_COD||' - '||ESTADO_NOMBRE FROM ACESTADO WHERE ESTADO_COD NOT IN ('E') ORDER BY ESTADO_COD, ESTADO_NOMBRE";
$estados = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta_estados,"busqueda");

$consultarTiposDocumentos=" SELECT tdo_codvar, tdo_nombre FROM getipdocu ORDER BY tdo_codigo";
$tipoDocumento = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consultarTiposDocumentos,"busqueda");
if($_REQUEST['estcod'] == "")
{
	die("<h3>No hay registros para esta consulta.</h3>");
	exit;
}
if($tipo==4){
    $rowConsulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
}elseif($tipo==110 || $tipo==114){
    $rowConsulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta_asistente,"busqueda");
}
if(!is_array($rowConsulta))
{
	die("<h3>No hay registros para esta consulta.</h3>");
	exit;
}
//{ header("Location: ../err/err_sin_registros.php"); exit;}
foreach ($tipoDocumento as $key => $tipo) {
    if ($tipo[0]==$rowConsulta[0][25])
        $tipoDocumentoEst=$tipo[1];
}

$vlrmatri = number_format($rowConsulta[0][16]);

$foto = est_foto.$rowConsulta[0][0].'.jpg';

if(!file_exists($foto))
{
	$foto="../img/sinfoto.png";
	$imgfoto='<img border="0" src="'.$foto.'" width="130" height="100"  alt="Sin fotografia almacenada">';
}
else
{
	$imgfoto='<img border="0" src="'.$foto.'" width="130" height="100" alt="Fotograf&iacute;a del Estudiante">';
}
$consultaPlanes="select distinct pen_nro from acpen where pen_cra_cod=".$rowConsulta[0][23]." and pen_estado='A' order by pen_nro";
$planes = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consultaPlanes,"busqueda");
$alerta= isset($alerta)?(alerta):"";
echo'<form name="dat" method=post action="coor_actualiza_datos_est.php">
<div align="center">
<table border="0" width="746" cellspacing="3" cellpadding="1">
<caption>DATOS B&Aacute;SICOS</caption>
 <tr><td width="100%" align="center" colspan="5"><span class="Estilo10"><b>'.$alerta.'</b></span></td></tr>
 <tr>
  <td width="136" align="center" rowspan="5" valign="middle">'.$imgfoto.'</td>
  <td width="91" align="right"><span class="Estilo5">C&oacute;digo:</span></td>
  <td width="505" style="font-weight: bold" colspan="3">'.$rowConsulta[0][0].'</td></tr>
 <tr>
  <td width="91" align="right"><span class="Estilo5">Py. Curricular:</span></td>
  <td width="505" style="font-weight: bold" colspan="3">'.$rowConsulta[0][15].'</td></tr>
 <tr>
  <td width="91" align="right"><span class="Estilo5">Estado:</span></td>
  <td width="505" style="font-weight: bold" colspan="3">';
	if(trim($rowConsulta[0][24])=='E')
        {
            echo 'E - EGRESADO';
        }else
            {
                echo '<select size="1" name="cod_estado" onclick="javascript:document.forms.dat.estestado.value = document.forms.dat.cod_estado.value">'; 

                    foreach ($estados as $estado) {
                        if($estado[0]==$rowConsulta[0][24]){
                            echo'<option value="'.$estado[0].'" selected>'.$estado[1].'</option>\n';
                        }else{
                            echo'<option value="'.$estado[0].'" >'.$estado[1].'</option>\n';
                        }
                    }
                echo'\n</select>';
            }
      echo '<input name="estestado" type="hidden" id="estestado" value="'.$rowConsulta[0][24].'" size="3" style="text-align: center" readonly></td>
  </td></tr>

   <tr> 
    <td width="91" align="right"><span class="Estilo5">Tipo de Estudiante:</span></td>
  <td width="190" colspan="1">
   <select size="1" name="estcred" onclick="javascript:document.forms.dat.estincred.value = document.forms.dat.estcred.value">';
	if(isset($rowConsulta[0][20])&&!empty($rowConsulta[0][20]))
        {
            echo '<option value="'.$rowConsulta[0][20].'" selected>'.$rowConsulta[0][21].'</option>';
            if(trim($rowConsulta[0][20])=='S')
            {
                    echo '<option value="N">Horas</option>';
            }
            else
            {
                    echo '<option value="S">Creditos</option>';
            }
        }else
            {
                echo '<option value="" selected>Seleccione uno</option>';
                echo '<option value="N">Horas</option>';
                echo '<option value="S">Creditos</option>';
            }
   echo '</select>
  <input name="estincred" type="hidden" id="estc" value="'.$rowConsulta[0][20].'" size="3" style="text-align: center" readonly></td>
      <td width="" colspan="2" rowspan="2" align="left"><span class="Estilo10"><b>* No olvide que el n&uacute;mero de pensum debe corresponder con el tipo de estudiante (Horas, Cr&eacute;ditos).</b></span><br></td>
</tr>


   <tr> 
	<td width="91" align="right"><span class="Estilo5">Pensum:</span></td>
  <td width="190">
   <select size="1" name="pennro" onclick="javascript:document.forms.dat.estpennro.value = document.forms.dat.pennro.value">
    <option value="'.$rowConsulta[0][19].'" selected>'.$rowConsulta[0][19].'</option>';
   foreach ($planes as $key => $value) {
       echo '<option value="'.$value[0].'">'.$value[0].'</option>';
}
   echo '</select>
  <input name="estpennro" type="hidden" id="estpen" value="'.$rowConsulta[0][19].'" size="3" style="text-align: center" readonly></td>
        
	
	
	  
</tr>


 <tr> 
 	<td  width="142">&nbsp;</td>
    <td width="91"  colspan="4" align="right"<br></td>
</tr>


<tr>
	<td align="right" width="142">&nbsp;</td>
	<td width="91" align="right">
		<span class="Estilo5">Nombre:</span>
	</td>
  	<td width="505" style="font-weight: bold" colspan="3">
  		<input name="estnom" type="text" id="estnom" value="'.$rowConsulta[0][1].'" size="66" onChange="javascript:this.value=this.value.toUpperCase();" maxlength="50" >
  	</td>
</tr>
<tr>
	<td align="right" width="142">&nbsp;</td>
 	<td width="91" align="right">
 	 	<span class="Estilo5">Identificaci&oacute;n:</span>
 	</td>
	<td width="190">
		<input name="nroiden" type="text" id="nroiden" value="'.$rowConsulta[0][2].'" size="15" maxlength="14">
		<input name="lugnac" type="hidden" id="lugnac" value="'.$rowConsulta[0][13].'" size="12">
	</td>
        <td width="85">
  <p align="right"><span class="Estilo5">Tipo de Documento:</span></td>
  <td width="85">
   <select size="1" name="tipo_iden" onclick="javascript:document.forms.dat.tipoiden.value = document.forms.dat.tipo_iden.value">';
    //<option value="'.$rowConsulta[0][25].'" selected>'.$tipoDocumentoEst.'</option>';
   foreach ($tipoDocumento as $key => $value) {
       if($value[0]==$rowConsulta[0][25]){
       echo '<option value="'.$value[0].'" selected>'.$value[1].'</option>';
                        }else{
                            echo'<option value="'.$value[0].'" >'.$value[1].'</option>\n';
                        }
}
   echo '</select>
  <input name="tipoiden" type="hidden" id="tip_iden" value="'.$rowConsulta[0][25].'" size="3" style="text-align: center" readonly></td>        

</tr>

 <tr>
  <td width="142" align="right"></td>
  <td width="91" align="right"><span class="Estilo5">Fecha Nac.:</span></td>
  <td width="190"><input name="fecnac" type="text" id="fecnac" value="'.$rowConsulta[0][12].'" size="12" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo.'\')" maxlength="10"><input TYPE="image" SRC="../img/cal.gif" width="17" height="17" alt="Calendario" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo.'\')"></td>
  <td width="85">
  <p align="right"><span class="Estilo5">Lugar:</span></td>
  <td width="226">
  <select size="1" name="CIUNOM" onclick="javascript:document.forms.dat.lugnac.value = document.forms.dat.CIUNOM.value" style="font-size: 9px">';
  	$i=0;
	while(isset($rowDatos[$i][0]))
	{
		echo'<option value="'.$rowDatos[$i][0].'" selected>'.$rowDatos[$i][1].'</option>\n';
	$i++;
	}

  echo'<option value="'.$rowConsulta[0][13].'" selected>'.$rowConsulta[0][14].'</option>
  \n</select></td></tr>
 <tr>
  <td width="142" align="right">&nbsp;</td>
  <td width="91" align="right"><span class="Estilo5">Direcci&oacute;n:</span></td>
  <td width="505" colspan="3"><input name="dir" type="text" id="dir" value="'.$rowConsulta[0][3].'" size="66" onChange="javascript:this.value=this.value.toUpperCase();" maxlength="100"></td></tr>
 <tr>
  <td width="142" align="right"></td>
  <td width="91" align="right"><span class="Estilo5">Tel&eacute;fono:</span></td>
  <td width="190"><input name="tel" type="text" id="tel" value="'.$rowConsulta[0][4].'" size="18"  onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" maxlength="10"></td>
  <td width="85">
    <p align="right"><span class="Estilo5">Zona postal:</span></p>
  </td>
  <td width="226"><input name="zonap" type="text" id="zonap" value="'.$rowConsulta[0][5].'" size="4" onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" maxlength="4"></td></tr>
 <tr>
  <td width="142" align="right"></td>
  <td width="91" align="right"><span class="Estilo5">Estado Civil:</span></td>
  <td width="190">
   <select size="1" name="LEC" onclick="javascript:document.forms.dat.estc.value = document.forms.dat.LEC.value">
    <option value="'.$rowConsulta[0][10].'" selected>'.$rowConsulta[0][11].'</option>
	<option value="0">0 Sin dato</option>
	<option value="1">1 Soltero(a)</option>
    <option value="2">2 Casado</option>
    <option value="3">3 Uni&oacute;n libre</option>
    <option value="4">4 Separado</option>
    <option value="5">5 Viudo</option>
   </select>
  <input name="estc" type="hidden" id="estc" value="'.$rowConsulta[0][10].'" size="3" style="text-align: center" readonly></td>
  
  

  
  
  
  
  <td align="left" width="85">
  <p align="right">&nbsp;<span class="Estilo5">Sexo:</span></p>
  </td>
  <td width="226" align="left">
  <select size="1" name="SX" onclick="javascript:document.forms.dat.sex.value = document.forms.dat.SX.value">
    <option value="'.$rowConsulta[0][6].'" selected>'.$rowConsulta[0][6].'</option>
	<option value="M">M</option>
    <option value="F">F</option>
  </select></td>
  </tr>
 <tr>
  <td width="142" align="right"></td>
  <td width="91" align="right"><span class="Estilo5">Tipo sangre:</span></td>
  <td width="190" valign="middle" align="left">
 <select size="1" name="LTS" onclick="javascript:document.forms.dat.tisa.value = document.forms.dat.LTS.value">
    <option value="'.$rowConsulta[0][7].'" selected>'.$rowConsulta[0][7].'</option>
	<option value="A">A</option>
    <option value="B">B</option>
    <option value="AB">AB</option>
    <option value="O">O</option>
  </select><input name="tisa" type="hidden" id="tisa" value="'.$rowConsulta[0][7].'" size="3" style="text-align: center" readonly></td>
  <td width="85" align="right"><span class="Estilo5">RH:</span></td>
  <td width="226"><b><font size="3">
  <input name="rh" type="text" id="rh" value="'.$rowConsulta[0][8].'" size="3" style="text-align: center" style="font-size: 10 pt; font-weight: bold" maxlength="5"></b></td></tr>
 <tr>
  <td width="142" align="right"></td>
  <td width="91" align="right"><span class="Estilo5">E-mail:</span></td>
  <td width="505" colspan="3">
  <input name="mail" type="text" id="mail" value="'.$rowConsulta[0][9].'" size="66" onChange="javascript:this.value=this.value.toLowerCase();" maxlength="50"></td></tr>

  <td width="142" align="right"></td>
  <td width="91" align="right"><span class="Estilo5">E-mail Ins:</span></td>
  <td width="505" colspan="3">
  <input name="mailins" type="text" id="mailins" value="'.$rowConsulta[0][18].'" size="66" onChange="javascript:this.value=this.value.toLowerCase();" maxlength="50" readonly></td></tr>
 <tr>
  <td width="142" align="right"></td>
  <td width="91" align="right"><span class="Estilo5">Vlr.Matr&iacute;cula:</span></td>
  <td width="505" colspan="3"><strong>$'.$vlrmatri.'</strong></td></tr>
 <tr>
 <tr>
  <td width="142" align="right"></td>
  <td width="91" align="right"><span class="Estilo5">Acuerdo:</span></td>
  <td width="505" colspan="3"><strong>'.substr($rowConsulta[0][22],-3).' de '.substr($rowConsulta[0][22],0,-3).'</strong></td></tr>
 <tr>
  <td width="142" align="center" height="30"></td>
  <td width="598" align="center" colspan="4" height="20">&nbsp;'; 
  require_once(dir_script.'mensaje_error.inc.php');
  if(isset($_REQUEST['error_login'])){
     $error=$_REQUEST['error_login'];
     echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
		  <a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</a></font>";
  }
echo'</td></tr>
     <tr><td width="142" align="center" height="10"></td>
	 <td width="746" align="center" colspan="5"><input type=submit name="actualizar" value="Grabar"></td></tr>
</table>
<input name="estcod" type="hidden" id="estcod" value="'.$_REQUEST['estcod'].'" size="10" readonly>
<input name="tipo" type="hidden" id="tipo" value="'.$_REQUEST['tipo'].'" size="10" readonly>
</form>';
require_once('coor_observaciones_est.php');

?>
</BODY>
</HTML>
