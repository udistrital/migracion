<?php
$conexion = "evaldocentes";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
    if (!$esteRecursoDB) {

        echo "//Este se considera un error fatal";
        exit;
    }
    $variable['anio']=$_REQUEST['anio'];
    $variable['per']=$_REQUEST['per'];

    if($_REQUEST['estado']=='A'){
        $variable['estadoInicial']=$_REQUEST['estado'];
        $variable['estadoFinal']='I';
    }
     else{
        $variable['estadoInicial']=$_REQUEST['estado'];
        $variable['estadoFinal']='A';
    }
    
        $cadena_sql = $this->sql->cadena_sql("actualizaEstados", $variable);
        $registroEstado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
        if($registroEstado==true){
            $cadena_sql = $this->sql->cadena_sql("actualizaEstado", $variable);
            $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
        }
    if ($registro==true) {
         $this->funcion->redireccionar('regresaraNuevo');
    }
?>

