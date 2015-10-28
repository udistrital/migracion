<?php
$variable['anio']=$_REQUEST['anio'];
$variable['per']=$_REQUEST['periodo'];
 
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cuenta=  count($registro);
$cierto=0;
for ($i=0; $i < $cuenta; $i++) {
    if(($registro[$i]['aca_anio']==$variable['anio'])&&($registro[$i]['aca_periodo']==$variable['per'])){
        $cierto=1;
    }
}
if($cierto==1){
    $this->funcion->redireccionar ("mostrarMensaje");
}
else    
{   
    $variable['estados']=$registro[0]['aca_id'];
    $variable['estadoInicial']='X';
    $variable['estadoFinal']='A';
    $cadena_sql = $this->sql->cadena_sql("actualizaEstados", $variable);
    $registroEstado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    
    if($registroEstado==true)
    {    
        unset($variable);
        $variable['estados']=$registro[0]['aca_id']-1;
        $variable['estadoInicial']='A';
        $variable['estadoFinal']='P';
        $cadena_sql = $this->sql->cadena_sql("actualizaEstados", $variable);
        $registroEstadoA = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
        
        if($registroEstadoA==true)
        {    
            unset($variable);
            $variable['estadosA']=$registro[0]['aca_id']-2;
            $variable['estadoInicial']='P';
            $variable['estadoFinal']='I';
            $cadena_sql = $this->sql->cadena_sql("actualizaEstadosA", $variable);
            $registroEstadoP = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
        }
        
        $variable['anio']=$_REQUEST['anio'];
        $variable['per']=$_REQUEST['periodo'];  
        $cadena_sql = $this->sql->cadena_sql("insertarRegistro", $variable);
        $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
        
        if($registro==true)
        {
            $cierto=2;
        }    
            
    }
    
}

if ($registro==2)
{
    $this->funcion->redireccionar ("regresaraNuevo");
}

?>

