<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");


$conexion1 = "autoevaluadoc";
$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

if (!$esteRecursoDBORA) {

    echo "Este se considera un error fatal";
    exit;
}

$variable['anio']= $_REQUEST['anio'];
$variable['periodo']= $_REQUEST['periodo'];

$cadena_sql = $this->sql->cadena_sql("consultarEventos", "");
$registroEventos = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");

$confec = "SELECT TO_CHAR(SYSDATE, 'dd/mm/yyyy') FROM dual";
$rows=$esteRecursoDBORA->ejecutarAcceso($confec, "busqueda");
$registro3=explode('/',$rows[0][0]);
$dia3=$registro3[0];
$mes3=$registro3[1];
$anno3=$registro3[2];
$fecha =date($anno3."/".$mes3."/".$dia3);
$fechaHoy=strtotime($fecha);

        if(isset($_REQUEST['fechaIni']) || isset($_REQUEST['fechaFin']))
        {        
            $variable['fechaIni']=$_REQUEST['fechaIni'];
            $variable['fechaFin']=$_REQUEST['fechaFin'];
            
            $fechaIni=strtotime($_REQUEST['fechaIni']);
            $fechaFin=strtotime($_REQUEST['fechaFin']);
            
        }
        
        if(isset($_REQUEST['evento']))
        {
            $variable['evento']=$_REQUEST['evento'];
        }
        else
        {    
            $variable['evento']=11;
        }
        
        $cadena_sql = $this->sql->cadena_sql("consultarEventosAnteriores", $variable);
        $registroEventosAnteriores = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
       
        if($fechaIni>$fechaFin)
        {
             $this->funcion->redireccionar ("mostrarMensaje");
        }
        else
        {    
            if($registroEventosAnteriores)
            {
                $cadena_sql = $this->sql->cadena_sql("actualizaEventos", $variable);
                $registroEvento = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "acceso");
               
                if ($registroEvento==true)
                {
                   $this->funcion->redireccionar ("regresaraAbrirFechas");
                }
                else
                {
                    echo "Ups... error!!!";
                }
            }    
            else
            {
                $cadena_sql = $this->sql->cadena_sql("insertaEventos", $variable);
                $registroEvento = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "");
                
                if ($registroEvento==true)
                {
                   $this->funcion->redireccionar ("regresaraAbrirFechas");
                }
                else
                {
                    echo "Ups... error!!!";
                }
            }
           
        }
?>

