<?

session_name("autentificado");

session_start();

unset($_SESSION['autentificado']);
unset($_SESSION['usuario_login']);
unset($_SESSION['usuario_password']);
unset($_SESSION['usuario_nivel']);
for ($sec = 1;$sec < 30;sec++){
	unset($_SESSION["fmto".$sec]);
	session_unregister($_SESSION["fmto".$sec]);
	unset($_SESSION["docente".$sec]);
	session_unregister($_SESSION["docente".$sec]);
	unset($_SESSION["nomdoc".$sec]);
	session_unregister($_SESSION["nomdoc".$sec]);
	unset($_SESSION["codigo".$sec]);
	session_unregister($_SESSION["codigo".$sec]);
	unset($_SESSION["asig".$sec]);
	session_unregister($_SESSION["asig".$sec]);
	unset($_SESSION["grupo".$sec]);
	session_unregister($_SESSION["grupo".$sec]);
	unset($_SESSION["nombre".$sec]);
	session_unregister($_SESSION["nombre".$sec]);
	unset($_SESSION["nomtvi".$sec]);
	session_unregister($_SESSION["nomtvi".$sec]);
	unset($_SESSION["cra".$sec]);
	session_unregister($_SESSION["cra".$sec]);
	unset($_SESSION["cranom".$sec]);
	session_unregister($_SESSION["cranom".$sec]);
	unset($_SESSION["tipovin".$sec]);
	session_unregister($_SESSION["tipovin".$sec]);
	unset($_SESSION["coordinacion".$sec]);
	session_unregister($_SESSION["coordinacion".$sec]);
	unset($_SESSION["vinsel".$sec]);
	session_unregister($_SESSION["vinsel".$sec]);
	unset($_SESSION["craactual".$sec]);
	session_unregister($_SESSION["craactual".$sec]);
	unset($_SESSION["vinselactual".$sec]);
	session_unregister($_SESSION["vinselactual".$sec]);
	unset($_SESSION["nomvinselactual".$sec]);
	session_unregister($_SESSION["nomvinselactual".$sec]);

}
session_destroy();

?>