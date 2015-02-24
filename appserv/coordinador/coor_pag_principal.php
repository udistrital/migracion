<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>

<style>
.mensaje{
	padding:20px;
	font-family: sans-serif;
	border: solid 2px #999999;
	border-radius: 10px 10px 10px 10px;
	box-shadow: 2px 2px 5px #999999;
	background: #FFFFFF;
	width:90%;
	margin:auto;
	font-size: 10pt;
}

.mensaje_title{
	padding:5px 20px 5px 20px ;
	font-family: sans-serif;
	border: solid 2px #999999;
	border-radius: 10px 10px 10px 10px;
	box-shadow: 2px 2px 5px #999999;
	color: #FFFFFF;
	background: #333333;
	width:90%;
	margin:auto;
	font-size: 10pt;
}
.mensaje li{
	font-size: 10pt;
}
</style>

</head>
<body>
<?php
fu_tipo_user(4);

$cedula = $_SESSION['usuario_login'];
$QryCra = "SELECT cra_cod,cra_nombre FROM accra WHERE CRA_EMP_NRO_IDEN = $cedula AND cra_estado = 'A'";

$RowCra = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCra,"busqueda");
if((isset($RowCra))&&!empty($RowCra[0]))
{
    $codProyectos="";
    foreach ($RowCra as $key => $value) {
        $codProyectos.=$RowCra[$key][0].",";
    }
    $codProyectos=  rtrim($codProyectos, ',');
}

echo'
    <div style="padding:20px; width:100%">';
$i=0;
while(isset($RowCra[$i][0]))
{
	echo'<br/><span class="Estilo5">'.$RowCra[$i][0].'-'.$RowCra[$i][1];
$i++;
}
$cadena_sql=" SELECT est_cra_cod COD_PROYECTO,";
$cadena_sql.=" cra_nombre NOMBRE_PROYECTO,";
$cadena_sql.=" ins_est_cod COD_ESTUDIANTE,";
$cadena_sql.=" est_estado_est ESTADO,";
$cadena_sql.=" estado_descripcion DESCRIPCION_ESTADO,";
$cadena_sql.=" est_nombre NOMBRE_ESTUDIANTE,";
$cadena_sql.=" ins_asi_cod COD_ESPACIO,";
$cadena_sql.=" asi_nombre NOMBRE_ESPACIO,";
$cadena_sql.=" ins_nota NOTA_PARCIAL,";
$cadena_sql.=" not_nota NOTA_DEFINITIVA";
$cadena_sql.=" FROM acins";
$cadena_sql.=" INNER JOIN acest ON est_cod=ins_est_cod";
$cadena_sql.=" INNER JOIN acnot ON not_cra_cod = est_cra_cod ";
$cadena_sql.=" AND not_est_cod=ins_est_cod AND ins_asi_cod=not_asi_cod AND ins_ano=not_ano AND ins_per=not_per";
$cadena_sql.=" INNER JOIN accra ON est_cra_cod=cra_cod";
$cadena_sql.=" INNER JOIN acasi ON ins_asi_cod=asi_cod";
$cadena_sql.=" INNER JOIN acestado ON est_estado_est=estado_cod";
$cadena_sql.=" INNER JOIN actipcra ON cra_tip_cra=tra_cod";
$cadena_sql.=" WHERE ins_ano=2014";
$cadena_sql.=" AND ins_per=1";
$cadena_sql.=" AND ins_nota!=not_nota";
$cadena_sql.=" AND tra_nivel='PREGRADO'";
$cadena_sql.=" AND est_cra_cod in (".$codProyectos.")";
$cadena_sql.=" ORDER BY est_cra_cod,ins_est_cod,ins_asi_cod";
$notas_estudiantes = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");

echo '</div>';

	$cadena_sql="SELECT contenido FROM avisos where sysdate between fecha_publicacion and fecha_desfijacion and aplicacion='CONDOR' order by prioridad,fecha_publicacion";
	$mensajes=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");

	if(is_array($mensajes)){
		echo "<div class='mensaje_title'>";
		echo NOTICIAS;
		echo "</div>";
	}

	$m=0;
	while(isset($mensajes[$m][0]))
	{
		echo "<div class='mensaje'>";
		echo $mensajes[$m][0];
		echo "</div>";
		$m++;
	}
echo "<br/>";
echo "<div class='mensaje'>";
echo'	
	<table border="0" width="100%" cellpadding="0">
   
	<tr><td width="67%" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
         
		<p align="justify" style="line-height: 100%">Si tiene m&aacute;s de un tipo de usuario como: (Decano, Coordinador &oacute; Docente), haga clic en el usuario deseado, en la lista &quot;<span class="Estilo5">Cambiar a Usuario</span>&quot;.</p>
		
		<p align="justify" style="line-height: 100%">Si cambia su correo electr&oacute;nico, direcci&oacute;n o tel&eacute;fono; no olvide actualizarlos en el men&uacute; &quot;<a href="coor_actualiza_dat.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Datos Personales</a>&quot;. Recuerde que de la veracidad de sus datos, depende un efectivo ingreso al aplicativo.</p>
		
		<p align="justify" style="line-height: 100%">Con un efectivo control por parte de los usuarios, la informaci&oacute;n podr&aacute; ser completa y real, por lo que se sugiere que revise con especial cuidado y reporte a su Coordinador del Proyecto Curricular, cualquier inquietud o correcci&oacute;n que considere necesaria.</p>
		
        <p style="line-height: 100%" align="justify">La forma segura de salir de esta p&aacute;gina, es haciendo clic en el hiperv&iacute;nculo &quot;<a href="../conexion/salir.php" target="_top" onMouseOver="link();return true;" onClick="link();return true;" title="Salida segura"><strong>Salir</strong></a>&quot;. De esta forma nos aseguramos que otras personas no puedan manipular sus datos.</p>
		
      </td>
    </tr>
    <tr>
      <td width="100%" align="center" height="1">
        <br><hr noshade class="hr">
      </td>
    </tr>
	
</div>';
?>
    <table border="0" width="100%" cellpadding="0">
        <tr>
            <td>
                <div style="color:#FF0000;font-size:15px">
                    <br>Respetado Coordinador de Proyecto Curricular:<br><br>
                </div>
                <div style="font-size:13px">
                    La Oficina Asesora de Sistemas se permite informarle de algunos aspectos a tener en cuenta a fin de facilitar la realización de los procesos de cierre e inicio de semestre que se encuentra en curso, incluidos preinscripción e inscripción de espacios académicos, y para solucionar las dificultades que pueden estar experimentando algunos estudiantes y su coordinación en relación con estos procesos en el sistema.
                    <ol>
                        <li>
                            Para el cálculo correcto y actualizado del promedio acumulado que realiza el sistema se hace necesario verificar que los registros de notas de los estudiantes tengan los datos completos, especialmente en el caso de los estudiantes de créditos.        
                        </li>
                        <li>
                            Es necesario verificar y registrar las homologaciones a que haya lugar, ya que la falta de este registro afecta las inscripciones que el estudiante puede realizar.
                        </li>
                        <li>
                            Es necesario verificar los requisitos de espacios académicos en el plan de estudios, para que los estudiantes puedan inscribir sus espacios de acuerdo a éste. De igual manera se debe comprobar que todos los estudiantes tengan registrado un plan de estudios acorde (HORAS o CRÉDITOS).
                        </li>
                        <li>
                            Tenga en cuenta que como efecto de la falta de los ajustes anteriores al cierre del semestre por proyecto, se puede presentar que algunos estudiantes queden registrados en el sistema en estado de "PRUEBA ACADEMICA" u otro estado que no corresponda con su realidad académica. Por lo anterior se sugiere al cierre, verificar para los casos de Prueba Académica y otros que no sean debidos a las situaciones anteriormente relacionadas.
                        </li>
                    </ol>
                </div>
            </td>
        </tr>
    </table>
    <?if ((isset($notas_estudiantes))&&!empty($notas_estudiantes[0]))
    {
        ?>
    <table border="0" width="100%" cellpadding="0">
        <tr>
            <td>
                <div style="font-size:13px">
                    <b><?echo date('d/M/Y')?>&nbsp; * * * &nbsp;INFORMACI&Oacute;N IMPORTANTE&nbsp; * * * &nbsp;</b><br><br>A continuaci&oacute;n se presenta el listado de la diferencia entre las notas definitivas registradas al Cierre del Semestre de los proyectos y las notas registradas por los Docentes, situación que se presenta en este momento por los cierres en diferentes fechas y otras situaciones particulares de cada proyecto.<br>
                    Favor realizar las verificaciones y ajustes correspondientes, para evitar inconvenientes tanto en los registros de notas de los estudiantes como en el estado académico reflejado en el sistema.
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <table border="1" width="100%" cellpadding="0">
                    <tr align="center">
                        <td>
                            PROYECTO CURRICULAR
                        </td>
                        <td>
                            COD. ESTUDIANTE
                        </td>
                        <td>
                            ESTUDIANTE
                        </td>
                        <td>
                            ESTADO ESTUDIANTE
                        </td>
                        <td>
                            ESPACIO ACAD&Eacute;MICO
                        </td>
                        <td>
                            NOTA REGISTRADA DOCENTE
                        </td>
                        <td>
                            NOTA AL CIERRE DE SEMESTRE
                        </td>
                    </tr>
                    <?
                        foreach ($notas_estudiantes as $key => $value) {
                        ?>
                            <tr align="center">
                                <td  align="left">
                                    <?echo $value[0]." - ".$value[1]?>
                                </td>
                                <td>
                                    <?echo $value[2]?>
                                </td>
                                <td align="left">
                                    <?echo $value[5]?>
                                </td>
                                <td>
                                    <?echo $value[3]." - ".$value[4]?>
                                </td>
                                <td>
                                    <?echo $value[6]." - ".$value[7]?>
                                </td>
                                <td>
                                    <?echo $value[8]?>
                                </td>
                                <td>
                                    <?echo $value[9]?>
                                </td>
                            </tr>
                        <?  
                        }
                    ?>
                </table>
            </td>
        </tr>
    </table>
    
    <?
    }
    ?>
</body>
</html>