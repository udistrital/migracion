<?PHP
$quincena = "SELECT decode(par_quincena
	,1,'Primera'
	,2,'Segunda'
	,3,'Mes'
	,4,'Intereses a la Cesantia'
	,5,'Prima Semestral'
	,6,'Prima de Vacaciones'
	,7,'Sueldo de Vacaciones'
	,8,'Prima de Navidad'
	,0,'Retroactivo'),
	mes_nombre
	FROM mntpe.peparam, gemes
	WHERE par_mes = mes_cod";

$fecha = "SELECT to_char(fpa_fecha_pag,'dd-Mon-yyyy') FROM mntpe.prfecpag";
?>



