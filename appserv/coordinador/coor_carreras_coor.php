<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');

$usuario = $_SESSION["usuario_login"];
require_once('msql_coor_carreras.php');

do{
   for($i=1; $i<$fil; $i++){
	   if($i == $fil)
	      $cra_coor .= OCIresult($qry_cra, 1);
	   else
		  $cra_coor .= OCIresult($qry_cra, 1).',';
	}
}while(OCIFetch($qry_cra));
$cra_coor .= 9999;
OCIFreeCursor($qry_cra);
?>