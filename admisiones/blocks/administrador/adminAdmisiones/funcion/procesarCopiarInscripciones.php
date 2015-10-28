<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$conexion1 = "admisionesAdmin";
$esteRecursoDB1 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

if (!$esteRecursoDB1) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cierto=0;
for($i=0; $i<=count($registro)-1; $i++)
{  
    if($registro[$i]['aca_estado']=="X")
    {
        $cierto=1;
        $variable['id_periodo']=$registro[$i]['aca_id'];
        $variable['anio']=$registro[$i]['aca_anio'];
        $variable['periodo']=$registro[$i]['aca_periodo'];
    }
}

$cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
$registroAcasp = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
if(is_array($registroAcasp))
{
    for($i=0; $i<=count($registroAcasp)-1; $i++)
    {
        $credenciales=array($registroAcasp[$i]['rba_asp_cred']);
    }
}
else
{
    echo "No hay registros.";
}
var_dump($credenciales);
$cadena_sql = $this->sql->cadena_sql("buscarAcaspOracle", $variable);
$registroAcaspOracle = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registroAcaspOracle))
{
    for($i=0; $i<=count($registroAcaspOracle)-1; $i++)
    {
        /*$variable['estrato']=strtoupper($registroPerAnterior[$i][2]);
        $variable['numeroest']=$registroPerAnterior[$i][1];
        $variable['puntosn']=$registroPerAnterior[$i][4];
        $variable['puntosv']=$registroPerAnterior[$i][3];
        $variable['puntos']=$registroPerAnterior[$i][5];
        $variable['id_periodo']=$registroPerAnterior[$i][7]+1;
        
        $cadena_sql = $this->sql->cadena_sql("consultarEstratosRegistrados", $variable);
        $registroLocalidadesRegistradas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        $cierto=0;
        if(is_array($registroLocalidadesRegistradas))
        {
            $cierto=1;
        }    
        else
        {
            
            $cadena_sql = $this->sql->cadena_sql("insertaEstratos", $variable);
            $registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");

            if ($registroEvento==true)
            {
               $this->funcion->redireccionar ("regresar",$valor);
            }
            else
            {
                echo "Ups... error!!!";
            }
        }*/
     }
     if($cierto==1){
        $mensaje="Ya existe un registro con los datos que está intentando guardar.";
        $html="<script>alert('".$mensaje."');</script>";
            echo $html;
            
           // echo "<script>location.replace('.$variable.')</script>"; 
        //$this->funcion->redireccionar ("mostrarMensajeOtros",$valor);    
    }
}
else
{
    echo "No hay registros.";
}
?>

