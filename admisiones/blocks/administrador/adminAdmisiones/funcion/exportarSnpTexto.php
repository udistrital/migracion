<?php
$rutaArchivos=$this->miConfigurador->getVariableConfiguracion("raizArchivos");

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['id_periodo']=$_REQUEST['id_periodo'];

if($_REQUEST['tipoInscripcion']=='nuevos')
{    
    $cadena_sql = $this->sql->cadena_sql("consultarInscripcionAcaspw", $variable);
    $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
}
else
{
    $cadena_sql = $this->sql->cadena_sql("consultarInscripcionTransferencia", $variable);
    $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
}


//$archivo=fopen("snp.txt","a") or
//die("Problemas en la creacion");
header('Content-type: application/txt');
	
//también le damos un nombre
header('Content-Disposition: attachment; filename="export.txt"');
for($i=0; $i<=count($registroInscripcion)-1; $i++)
{
    if(isset($registroInscripcion[$i]['aspw_id']))
    {
        $numeroSnp=$registroInscripcion[$i]['aspw_snp'];
    }
    elseif(isset($registroInscripcion[$i]['atr_id']))
    {
        $numeroSnp=$registroInscripcion[$i]['atr_snp'];
    }
    else
    {
        echo "No hay registros...";
    }
    
    /*fputs($archivo,$numeroSnp);
    fputs($archivo,"\n");*/
    //le informamos que será un archivo txt
	
	
	//generamos el contenido del archivo
	echo $numeroSnp."\n";
   
}
 //fclose($archivo);
?>

