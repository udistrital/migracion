<?PHP
require_once('dir_relativo.cfg');
require_once(dir_general.'valida_http_referer.php');
require_once(dir_general.'valida_inscripcion.php');

require_once('msql_valor_inscripcion.php');
require_once(dir_general.'msql_ano_per.php');

print 'EntityCode1: '.$_REQUEST['EntityCode'].'<br>';
print 'EntityCode2: '.$_POST['EntityCode'].'<br>';
print 'EntityCode3: '.$HTTP_POST_VARS['EntityCode'].'<br>';
print 'EntityCode4: '.$_GET['EntityCode'].'<br>';
print 'EntityCode5: '.$HTTP_GET_VARS['EntityCode'].'<br>';

print 'SrvCode: '.$_POST['SrvCode'].'<br>';
print 'TransVatValue: '.$_POST['TransVatValue'].'<br>';
print 'Reference1: '.$_POST['Reference1'].'<br>';
print 'TranState: '.$_POST['TranState'].'<br>';
print 'BankProcessDate: '.$_POST['BankProcessDate'].'<br>';
print 'TrazabilityCode: '.$_POST['TrazabilityCode'].'<br>';
print 'Sing: '.$_POST['Sing'].'<br>';
print 'SingFields--: '.$_POST['SingFields'].'<br>'; 
exit;


if(!isset($_POST['EntityCode']) || $_POST['EntityCode'] != 10125)
   header("Location: ../err/err_pse.php");
elseif(!isset($_POST['SrvCode']) || $_POST['SrvCode'] != 1012501)
	   header("Location: ../err/err_pse.php");
elseif(!isset($_POST['TransVatValue']) || $_POST['TransVatValue'] != $VlrInscripcion)
	   header("Location: ../err/err_pse.php");
elseif(!isset($_POST['Reference1']))
	   header("Location: ../err/err_pse.php");
elseif(!isset($_POST['TranState']) || $_POST['TranState'] != 'OK')
	   header("Location: ../err/err_transaccion.php");
elseif(!isset($_POST['BankProcessDate']))
	   header("Location: ../err/err_pse.php");
elseif(!isset($_POST['TrazabilityCode']))
	   header("Location: ../err/err_pse.php");
else{
	 $dia = (int)substr($_POST['BankProcessDate'],0,2);
	 $mes = (int)substr($_POST['BankProcessDate'],3,2);
	 $clave = md5($_POST['TrazabilityCode']);
	 $bancod = 23;
	 $oficina = 0;

	 $$credencial = OCIParse($oci_conecta, "BEGIN :bcred := Fua_genera_credencial(); END;");
	 OCIBindByName($credencial, ":bcred", $cred,10);
	 OCIExecute($credencial) or die(ora_errorcode());
	 
	 $insPse = OCIParse($oci_conecta, "INSERT INTO mntac.acplanorecbanasp
	 VALUES(:bapeano, :bapeper, :brefpago, :bnroiden, :bbancod, :boficina, :bano, :bmes, :bdia, :bvalor, :bclave :baspcred)");			  
	 OCIBindByName($insPse, ":bapeano", $ano);
	 OCIBindByName($insPse, ":bapeper", $per);
	 OCIBindByName($insPse, ":brefpago", $_POST['TrazabilityCode']);
	 OCIBindByName($insPse, ":bnroiden", $_POST['Reference1']);
	 OCIBindByName($insPse, ":bbancod", $bancod);
	 OCIBindByName($insPse, ":boficina", $oficina);
	 OCIBindByName($insPse, ":bano", $ano);
	 OCIBindByName($insPse, ":bmes", $mes);
	 OCIBindByName($insPse, ":bdia", $dia);
	 OCIBindByName($insPse, ":bvalor", $_POST['TransVatValue']);
	 OCIBindByName($insPse, ":bclave", $clave);
	 OCIBindByName($insPse, ":baspcred", $cred);
	 OCIExecute($insPse) or die(Ora_ErrorCode());
	 OCICommit($oci_conecta);
	 
	 $_SESSION["usuario_login"] = $_POST['TrazabilityCode'];
	 $_SESSION["usuario_password"] = $clave;
}
?>