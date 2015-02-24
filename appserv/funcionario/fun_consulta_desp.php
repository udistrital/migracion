<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(24);
fu_cabezote("CONSULTA DESPRENDIBLES DE PAGO");
?>
<html>
<head>
<title>Pagina nueva 1</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="doublecombo" method="POST" action="fun_desprendibles.php" target="inferior">
  <!--webbot bot="SaveResults" u-file="C:\AppServ\www\oas_Nuevo\funcionario\_private\form_results.csv" s-format="TEXT/CSV" s-label-fields="TRUE" -->
<table border="1" align="center">
	<tr>
		<td>
			A&ntilde;o:
		</td>
		<td>
			<?PHP
			//$QryAnios = "SELECT unique(ape_ano) FROM acasperi WHERE ape_ano >= 1995 AND ape_estado != 'X' ORDER BY 1 DESC";
			$QryAnios = "SELECT unique(ape_ano) FROM acasperi WHERE ape_ano >= 1995 ORDER BY 1 DESC";
			$RowAnios = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryAnios,"busqueda");
			
			print'<select size="1" name="anio">';
			$i=0;
			while(isset($RowAnios[$i][0]))
			{
			echo'<option value="'.$RowAnios[$i][0].'">'.$RowAnios[$i][0].'</option>';
			$i++;
			}
			print'</select>';
			?>
		</td>
	</tr>
	<tr>
		<td>
			Mes:
		</td>
		<td>
			<?PHP
			$QryMes = "SELECT mes_cod,mes_abrev FROM gemes WHERE mes_cod <= 12 ORDER BY 1";
			$RowMes = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryMes,"busqueda");
			
			print'<select size="1" name="mes" onChange="redirect(this.options.selectedIndex)">';
			$i=0;
			while(isset($RowMes[$i][0]))
			{
			echo'<option value="'.$RowMes[$i][0].'">'.$RowMes[$i][1].'</option>';
			$i++;
			}
			print'</select>';
			?>  
		</td>
	</tr>
	<tr>
		<td>
			Pago:
		</td>
		<td width="178">
			<select size="1" name="tipq">
			<option value="3">Mes</option>
			<option value="4">Intereses a la Cesantia</option>
			</select>
		</td>
	</tr>
	<?PHP
	//$QryCod = "select emp_cod,emp_nombre from peemp where emp_estado_e <> 'R' AND emp_nro_iden = '".$_SESSION['usuario_login']."'";
	$QryCod = "select EMP_COD,EMP_NOMBRE from PEEMP
                    where ((EMP_ESTADO_E <> 'R') or (EMP_COD in (select unique LIQ_EMP_COD
                                                        from PEPARAM, PRLIQUID
                                                        where PAR_ANO = LIQ_ANO
                                                        union
                                                        select unique LIQ_EMP_COD
                                                        from PEPARAM, PRLIQUID
                                                        where (PAR_ANO-1) = LIQ_ANO)))
                     AND emp_nro_iden = '".$_SESSION['usuario_login']."'";
        
        $RowCod = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCod,"busqueda");
	$cuenta=count($RowCod);
	
	if($cuenta>1)
	{
		echo '<tr>
			<td>
				Nombre:
			</td>
			<td>
				<select size="1" name="codigo">';
				$i=0;
				while(isset($RowCod[$i][0]))
				{
					echo '<option value="'.$RowCod[$i][0].'">'.$RowCod[$i][1].'</option>';
				$i++;
				}
				echo '</select>
			</td>
		</tr>';
	}
	?>
	<tr>
		<td align="center" colspan="2">
			<input type="submit" value="Consultar" id="B1" name="B1" style="cursor:pointer" title="Ejecutar la consulta">
		</td>
	</tr>
</table>

<script language="javascript" type="text/javascript">
<!--
var groups=document.doublecombo.mes.options.length
var group=new Array(groups)
for(i=0; i<groups; i++)
	group[i]=new Array()

group[0][0]=new Option("Mes","3")
group[0][1]=new Option("Intereses a la Cesantia","4")

group[1][0]=new Option("Primera quincena","1")
group[1][1]=new Option("Segunda quincena","2")
group[1][2]=new Option("Mes","3")
group[1][3]=new Option("Adicional","0")

group[2][0]=new Option("Primera quincena","1")
group[2][1]=new Option("Segunda quincena","2")
group[2][2]=new Option("Mes","3")
group[2][3]=new Option("Adicional","0")

group[3][0]=new Option("Primera quincena","1")
group[3][1]=new Option("Segunda quincena","2")
group[3][2]=new Option("Mes","3")
group[3][3]=new Option("Adicional","0")

group[4][0]=new Option("Primera quincena","1")
group[4][1]=new Option("Segunda quincena","2")
group[4][2]=new Option("Mes","3")
group[4][3]=new Option("Adicional","0")

group[5][0]=new Option("Primera quincena","1")
group[5][1]=new Option("Segunda quincena","2")
group[5][2]=new Option("Mes","3")
group[5][3]=new Option("Prima Semestral","5")
group[5][4]=new Option("Adicional","0")

group[6][0]=new Option("Primera quincena","1")
group[6][1]=new Option("Segunda quincena","2")
group[6][2]=new Option("Mes","3")
group[6][3]=new Option("Adicional","0")

group[7][0]=new Option("Primera quincena","1")
group[7][1]=new Option("Segunda quincena","2")
group[7][2]=new Option("Mes","3")
group[7][3]=new Option("Adicional","0")

group[8][0]=new Option("Primera quincena","1")
group[8][1]=new Option("Segunda quincena","2")
group[8][2]=new Option("Mes","3")
group[8][3]=new Option("Adicional","0")

group[9][0]=new Option("Primera quincena","1")
group[9][1]=new Option("Segunda quincena","2")
group[9][2]=new Option("Mes","3")
group[9][3]=new Option("Adicional","0")

group[10][0]=new Option("Primera quincena","1")
group[10][1]=new Option("Segunda quincena","2")
group[10][2]=new Option("Mes","3")
group[10][3]=new Option("Adicional","0")

group[11][0]=new Option("Primera quincena","1")
group[11][1]=new Option("Segunda quincena","2")
group[11][2]=new Option("Mes","3")
group[11][3]=new Option("Prima Semestral","5")
group[11][4]=new Option("Prima de Vacaciones","6")
group[11][5]=new Option("Sueldo de Vacaciones","7")
group[11][6]=new Option("Prima de Navidad","8")
group[11][7]=new Option("Adicional","0")

var temp=document.doublecombo.tipq
function redirect(x){
	for(m=temp.options.length-1;m>0;m--)
		temp.options[m]=null
	for(i=0;i<group[x].length;i++){
		temp.options[i]=new Option(group[x][i].text,group[x][i].value)
	}
	temp.options[0].selected=true
}

function go(){
	location=temp.options[temp.selectedIndex].value
}
//-->
</script>

</form>
<?PHP
?>
</body>
</html>