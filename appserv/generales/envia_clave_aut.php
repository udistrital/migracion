<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once("../clase/funcionGeneral.class.php");

$conexion=new funcionGeneral();
$registro=$conexion->ejecutarSQL($configuracion,$oci_conecta, $cod_consul,"busqueda");

$url = explode("?",$_SERVER['HTTP_REFERER']);

//$redir = 'https://condor.udistrital.edu.co/appserv/generales/cambia_clave_aut.php';
$redir=$url[0];
//echo "nnn".$redir."<br>";

//echo $_REQUEST['email'];

if($_REQUEST['email'] == "")
{
	echo "<script>location.replace('$redir?error_login=15')</script>";
	exit;
}
if($_REQUEST['user'] == "")
{
	echo "<script>location.replace('$redir?error_login=10')</script>";
	exit;
}

$QryExiste="SELECT 'S' ";
$QryExiste.="FROM ";	 
$QryExiste.="geclaves ";
$QryExiste.="WHERE ";
$QryExiste.="cla_codigo ='".$_REQUEST['user']."' ";
$QryExiste.="AND ";
$QryExiste.="cla_tipo_usu ='".$_REQUEST['tipo']."'";

if($_REQUEST['tipo']==51){
	$QryExiste="SELECT 'S' ";
	$QryExiste.="FROM ";	 
	$QryExiste.="geclaves ";
	$QryExiste.="WHERE ";
	$QryExiste.="cla_codigo ='".$_REQUEST['user']."' ";
}


$RowExiste = $conexion->ejecutarSQL($configuracion,$oci_conecta, $QryExiste,"busqueda");

if($RowExiste[0][0]!='S')
{
	echo "<script>location.replace('$redir?error_login=4')</script>";
	exit;
}

if($_REQUEST['tipo'] == 4 || $_REQUEST['tipo'] == 16 || $_REQUEST['tipo'] == 30)
{
	$consulta = "SELECT doc_nro_iden,doc_nombre||' '||doc_apellido,TRIM(LOWER(doc_email)),TRIM(LOWER(doc_email_ins))
		FROM acdocente
		WHERE doc_nro_iden =".$_REQUEST['user']."
		AND (trim(doc_email) like('%".$_REQUEST['email']."')
		OR trim(doc_email_ins) like('%".$_REQUEST['email']."'))";
}
if($_REQUEST['tipo'] == 24)
{
   $consulta = "SELECT emp_nro_iden,emp_nombre,TRIM(LOWER(emp_email))
		FROM mntpe.peemp
		WHERE emp_nro_iden = ".$_REQUEST['user']."
		AND trim(emp_email) like('%".$_REQUEST['email']."')
		AND emp_estado_e != 'R'";
}
if(($_REQUEST['tipo'] == 51) ||  ($_REQUEST['tipo'] == 52))
{
   $consulta = "SELECT est_cod,est_nombre,TRIM(LOWER(eot_email)),TRIM(LOWER(eot_email_ins))
		FROM acest,acestotr
		WHERE est_cod = ".$_REQUEST['user']."
		AND est_cod = eot_cod
		AND (trim(eot_email) like('%".$_REQUEST['email']."')
		OR trim(eot_email_ins) like('%".$_REQUEST['email']."'))
		AND est_estado_est IN('A','B','H','J','L','T','V')";
}
if(isset($consulta))
{
	$resultado = $conexion->ejecutarSQL($configuracion,$oci_conecta, $consulta,"busqueda");
}



/*
if($_POST['tipo'] != 24){require_once('dir_relativo.cfg');
if(OCIResult($consulta, 3)!="" && OCIResult($consulta, 4)!="") $cuentas = OCIResult($consulta, 3).';'.OCIResult($consulta, 4);
else $cuentas = OCIResult($consulta, 3);
*/

if(!is_array($resultado))
{
	echo "<script>location.replace('$redir?error_login=8')</script>";
	exit;
}
else
{
	require_once(dir_script.'fu_genera_clave.php');
	 //$_POST['largo']
	 $largo = rand(6,9);
	 $nueclave = genera_clave($largo);
	 $encriptaclave = md5($nueclave);
	 $usuario = $resultado[0][0];
	 $nombre = $resultado[0][1];
	 $cuenta_per = $resultado[0][2];
	 $cuenta_ins = $resultado[0][3];
	 $tipo = $_REQUEST['tipo'];

	 $qry="UPDATE ";
	 $qry.="geclaves ";
	 $qry.="SET ";
	 $qry.="cla_clave ='".$encriptaclave."' ";
	 $qry.="WHERE ";
	 $qry.="cla_codigo = $usuario ";
 
        // echo "<br>".$qry;exit; 
        
	 $registro = $conexion->ejecutarSQL($configuracion,$oci_conecta, $qry,"busqueda");
         //consulta la conexion para mysql
         $accesoMY=$conexion->conectarDB($configuracion,"cambio_claveMY");
        
         require_once(dir_script.'fu_envia_clave_aut.php');
	 
	if(isset($registro))
	{       
                //actualiza la conexion para mysql
                if(isset($accesoMY)){$resultado2 =$conexion->ejecutarSQL($configuracion,$accesoMY,$qry,"busqueda");} 
		
                fu_envia_clave($nombre, $cuenta_per, $cuenta_ins, $usuario, $nueclave, $tipo);
                echo "<script>location.replace('$redir?error_login=9')</script>";
		exit;
                
	        
	}
}
?>
