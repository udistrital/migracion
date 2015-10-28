<?php
$rutaArchivos=$this->miConfigurador->getVariableConfiguracion("raizArchivos");

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

//Valida que el archivo sea tipo texto.
if ($_FILES["subirArchivo"]["type"] == "text/plain"){
    // obtenemos los datos del archivo 
    $tamano = $_FILES["subirArchivo"]['size'];
    $tipo = $_FILES["subirArchivo"]['type'];
    $archivo = $_FILES["subirArchivo"]['name'];
    $temporal=$_FILES["subirArchivo"]['tmp_name'];
    $prefijo=substr(md5(uniqid(rand())),0,6);
    $fecha=date("dmYhm");
    $nombreArchivo=$fecha."-".$prefijo."_".$archivo;
    $destino =  $rutaArchivos.$nombreArchivo;

    //Copiamos el archivo en el servidor
    if (move_uploaded_file($_FILES['subirArchivo']['tmp_name'], $destino))
    {
        //echo "El archivo es válido y fue cargado exitosamente.\n";
    }
    else
    {
        echo "¡Posible ataque de carga de archivos!\n";
    }

    //Lee el archivo en el servidor                
    $file = fopen("$rutaArchivos".$nombreArchivo,"r") or exit("Imposible abrir el archivo!");
    $cierto=0;        
    while(!feof($file))
    {
        $valores=explode(",",fgets($file));
        if($valores[0]!=''){
            $variable["credencial"]=  $valores[0];
            $variable["admitido"]=  trim($valores[1]);
            $variable['id_periodo']=$_REQUEST['id_periodo'];

            $cadena_sql = $this->sql->cadena_sql("actualizaAcaspAdmitidos", $variable);
            $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");  

            if($registro==true){
                $cierto=1;
            }
        }
    }
    fclose($file);
    if($cierto==1)
    {
        $this->funcion->redireccionar ("regresaraCargaAdmitidos");
    }
}
else
{
    $this->funcion->redireccionar ("mostrarMensajeArchivo");
}    
?>

