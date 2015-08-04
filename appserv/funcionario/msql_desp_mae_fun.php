<?PHP
$maestro = "SELECT emp_cod, 
    emp_nombre, 
    emp_nro_iden, 
    car_nombre, 
    emp_dep_cod, 
    dep_nombre, 
    esa_sueldo, 
    max(liq_dias) dias, 
    lne_valor 
    FROM mntpe.peemp
    inner join mntpe.pecargo ON emp_car_cod = car_cod
    inner join mntpe.prliquid ON emp_cod = liq_emp_cod
    inner join mntpe.empsal ON emp_cod = esa_cod 
    inner join gedep ON emp_dep_cod = dep_cod 
    left outer join mntpe.liquidaneto ON emp_cod = lne_emp_cod 
    WHERE  emp_cod = ".$_SESSION["fun_cod"]."
    GROUP BY emp_dep_cod, dep_nombre, emp_cod,emp_nombre, emp_nro_iden, car_nombre, esa_sueldo, lne_valor 
    ORDER BY emp_dep_cod ASC";    
?>