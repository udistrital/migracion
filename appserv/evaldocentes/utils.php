<?
function crearlink($nom, $val) {
		$items = count($nom);
		$lnk = $nom[0] .$val[0];
	for ( $i = 0; $i < $items; $i++ ) {
		$lnk.= next($nom). next($val);
		}
		return $lnk;
}
?>
