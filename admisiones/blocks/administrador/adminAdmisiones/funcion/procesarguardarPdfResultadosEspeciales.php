<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$rutaArchivos=$this->miConfigurador->getVariableConfiguracion("raizArchivoDocumentos");

$variable['carrera']=isset($_REQUEST['carreras'])?$_REQUEST['carreras']:'';
$variable['periodo']=isset($_REQUEST['periodo'])?$_REQUEST['periodo']:'';
$variable['anio']=isset($_REQUEST['anio'])?$_REQUEST['anio']:'';

	
	//$i=isset($variable)?$variable:''
    if ($_FILES["subirArchivo"]["type"] == "application/pdf")
    {
        // obtenemos los datos del archivo 
        $tamano = $_FILES["subirArchivo"]['size'];
        $tipo = $_FILES["subirArchivo"]['type'];
        $archivo = $_FILES["subirArchivo"]['name'];
        $temporal=$_FILES["subirArchivo"]['tmp_name'];
        $prefijo=substr(md5(uniqid(rand())),0,6);
        //$documento=trim($registroDocumentos[$i]['doc_prefijo']);
        $nombreArchivo="Resultados_especiales_".$variable['anio']."_".$variable['periodo'];
        $destino =  $rutaArchivos.$nombreArchivo;
            
        //Copiamos el archivo en el servidor
        if (move_uploaded_file($_FILES['subirArchivo']['tmp_name'], $destino))
        {
            $valor['opcionPagina']="subirPdfEspeciales";
            $this->funcion->redireccionar('regresar',$valor);
        }
        else
        {
            echo "¡Posible ataque de carga de archivos!\n";
        }
    }
    else 
    {
    	$mensaje="El archivo debe estar en formato pdf.";
    	
    	$html="<script>alert('".$mensaje."');</script>";
    	echo $html;
    	echo "Redireccionando.";
    	// $indice = $registro[0][10];
    	echo "<script>location.replace('')</script>";
    }

?>

