<?php
$anioPer=$_REQUEST['periodoAcademico'];

$variable=explode('#',$anioPer);

$variable['anio']=$variable[0];
$variable['per']=$variable[1];
 
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//echo $cadena_sql."<br>";
$cuenta=  count($registro);
$cierto=0;
for ($i=0; $i < $cuenta; $i++) {
    
    if(($registro[$i]['acasperiev_anio']==$variable['anio'])&&($registro[$i]['acasperiev_periodo']==$variable['per'])){
    $cierto=1;
    }
   
}
if($cierto==1){
    $this->funcion->redireccionar ("confirmarPeriodo");
}
 else{
        $cadena_sql = $this->sql->cadena_sql("actualizaEstados", $variable);
        $registroEstado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
        if($registroEstado==true){
            $cadena_sql = $this->sql->cadena_sql("insertarRegistro", $variable);
            $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
        }
     }



//echo $cadena_sql;    

if ($registro==true) {
    $this->funcion->redireccionar ("regresaraNuevo");
}
//$this->funcion->mostrarResultados($noEnviados, $enviados);
?>

