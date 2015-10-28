<?php
$conexion = "admisionesAdmin";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
    if (!$esteRecursoDB) {

        echo "//Este se considera un error fatal";
        exit;
    }
    $variable['codCra']=$_REQUEST['codCra'];
    
    if($_REQUEST['seOfrece']=='S')
    {
        $variable['seOfrece']='N';
    }
    else
    {
        $variable['seOfrece']='S';
    }
    
    $cadena_sql = $this->sql->cadena_sql("actualizaCarrera", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    
    if ($registro==true) {
     $this->funcion->redireccionar('regresaraHabilitarCarrera');
    }
   
?>

