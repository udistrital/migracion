<?PHP
$quincena = "SELECT (
CASE WHEN par_quincena  = 1 THEN 'Primera'  
    WHEN par_quincena  = 2 THEN 'Segunda'  
    WHEN par_quincena  = 3 THEN 'Mes'  
    WHEN par_quincena  = 4 THEN 'Intereses a la Cesantia'  
    WHEN par_quincena  = 5 THEN 'Prima Semestral'  
    WHEN par_quincena  = 6 THEN 'Prima de Vacaciones'  
    WHEN par_quincena  = 7 THEN 'Sueldo de Vacaciones'  
    WHEN par_quincena  = 8 THEN 'Prima de Navidad'  
    WHEN par_quincena  = 0 THEN 'Retroactivo' END),
	mes_nombre
	FROM mntpe.peparam, gemes
	WHERE par_mes = mes_cod";

$fecha = "SELECT to_char(fpa_fecha_pag,'dd-Mon-yyyy') FROM mntpe.prfecpag";
?>



