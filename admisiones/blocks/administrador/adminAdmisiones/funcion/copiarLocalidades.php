<?php

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variables['id_periodo']=$_REQUEST['id_periodo']-1;
$variable['id_periodoNuevo']=$_REQUEST['id_periodo'];
$variable['id_periodoAnterior']=$_REQUEST['id_periodo']-1;
$valor['opcionPagina']="localidades";

$cadena_sql = $this->sql->cadena_sql("buscarLocalidades", $variables);
$registroPerAnterior = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registroPerAnterior))
{
    for($i=0; $i<=count($registroPerAnterior)-1; $i++)
    {
        $variable['localidad']=strtoupper($registroPerAnterior[$i][2]);
        $variable['numero']=$registroPerAnterior[$i][1];
        $variable['puntosn']=$registroPerAnterior[$i][4];
        $variable['puntosv']=$registroPerAnterior[$i][3];
        $variable['id_periodo']=$registroPerAnterior[$i][6]+1;
        
        $cadena_sql = $this->sql->cadena_sql("consultarLocalidadesRegistradas", $variable);
        $registroLocalidadesRegistradas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        $cierto=0;
        if(is_array($registroLocalidadesRegistradas))
        {
            $cierto=1;
            //$this->funcion->redireccionar ("mostrarMensajeRegistroExistente",$valor);
        }    
        else
        {
            
            $cadena_sql = $this->sql->cadena_sql("insertaLocalidades", $variable);
            $registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");

            if ($registroEvento==true)
            {
               $this->funcion->redireccionar ("regresar",$valor);
            }
            else
            {
                echo "Ups... error!!!";
            }
        }
     }
     if($cierto==1){
        $mensaje="Ya existe un registro con los datos que está intentando guardar.";
        $html="<script>alert('".$mensaje."');</script>";
            echo $html;
            
           // echo "<script>location.replace('.$variable.')</script>"; 
        $this->funcion->redireccionar ("mostrarMensajeOtros",$valor);    
    }
}
else
{
    echo "No hay registros para periodo anterior.";
}
?>

