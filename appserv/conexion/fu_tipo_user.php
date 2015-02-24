<?PHP
function fu_tipo_user($tipo)
{
	if(!isset($_SESSION["usuario_nivel"]))
	{
		//require_once('inactiva_usuario.php');
		session_destroy();
		die('<p align="center"><b><font color="#FF0000"><u>Sesi&oacute;n Cerrada!</u></font></b></p>');
		exit;
	}
	else
	{
	
		if($_SESSION['usuario_nivel']==28 && $tipo==4)
		{
			$tipo=28;	
		}	

		if($_SESSION['usuario_nivel']==110 && $tipo==4)
		{
			$tipo=110;
		}
		if($_SESSION['usuario_nivel']==114 && $tipo==4)
		{
			$tipo=114;
		}

		if($_SESSION['usuario_nivel']==52 && $tipo==51)
		{
			$tipo=52;	
		}			
		if($_SESSION['usuario_nivel']!=$tipo)
		{
			session_destroy();
			print '<p align="center"><b><font color="#FF0000"><u>No est&aacute; autorizado para entrar en 
			esta p&aacute;gina.</u></font></b></p><br>';
			die('<p align="center"><b><font color="#FF0000"><u>Sesi&oacute;n Cerrada!</u></font></b></p>');
			exit;
		}
	
	
	}
}
?>
