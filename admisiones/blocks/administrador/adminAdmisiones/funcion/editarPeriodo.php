<?php
$conexion = "admisiones";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
    if (!$esteRecursoDB) {

        echo "//Este se considera un error fatal";
        exit;
    }
    $variable['anio']=$_REQUEST['anio'];
    $variable['per']=$_REQUEST['per'];
    
    $variable['estadoInicial']=$_REQUEST['estadoActual'];
    $variable['estadoFinal']=$_REQUEST['estadoNuevo'];
    
    
    $cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
    $registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    $cierto=0;
    for($i=0; $i<=count($registroPeriodo)-1; $i++)
    {
        if($registroPeriodo[$i]['aca_estado']=='X' && $variable['estadoFinal']=='X')
        {
            $cierto=1;
        }
        if($registroPeriodo[$i]['aca_estado']=='A' && $variable['estadoFinal']=='A')
        {
            $cierto=2;
        }
        if($registroPeriodo[$i]['aca_estado']=='P' && $variable['estadoFinal']=='P')
        {
            $cierto=3;
        } 
    }
    
    if($cierto==1 || $cierto==2 || $cierto==3)
    {
        $variable['estadoNuevo']=$variable['estadoFinal'];
        $this->funcion->redireccionar ("mostrarMensajePerExiste");
    }
    else
    {    
        $cadena_sql = $this->sql->cadena_sql("actualizaEstado", $variable);
        $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

        if ($registro==true) {
         $this->funcion->redireccionar('regresaraNuevo');
        }
    }
?>

