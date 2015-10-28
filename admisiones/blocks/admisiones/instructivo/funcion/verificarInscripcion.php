<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$rutaArchivos=$this->miConfigurador->getVariableConfiguracion("raizArchivoDocumentos");

$variable['rba_id']=isset($_REQUEST['rba_id'])?$_REQUEST['rba_id']:'';
$variable['carrera']=isset($_REQUEST['carreras'])?$_REQUEST['carreras']:'';
$variable['periodo']=isset($_REQUEST['periodo'])?$_REQUEST['periodo']:'';
$variable['anio']=isset($_REQUEST['anio'])?$_REQUEST['anio']:'';

$cadena_sql = $this->sql->cadena_sql("buscarDocumentos", $variable);
$registroDocumentos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registroDocumentos))
{
    for($i=0; $i<=count($registroDocumentos)-1; $i++)
    {
        if ($_FILES["subirArchivo".$i]["type"] == "application/pdf")
        {
            // obtenemos los datos del archivo 
            $tamano = $_FILES["subirArchivo".$i]['size'];
            $tipo = $_FILES["subirArchivo".$i]['type'];
            $archivo = $_FILES["subirArchivo".$i]['name'];
            $temporal=$_FILES["subirArchivo".$i]['tmp_name'];
            $prefijo=substr(md5(uniqid(rand())),0,6);
            $documento=trim($registroDocumentos[$i]['doc_prefijo']);
            $nombreArchivo=$documento."_".trim($variable['rba_id'])."_".$variable['carrera']."_".$variable['anio']."_".$variable['periodo'];
            $destino =  $rutaArchivos.$nombreArchivo;
            
            //Copiamos el archivo en el servidor
            if (move_uploaded_file($_FILES['subirArchivo'.$i]['tmp_name'], $destino))
            {
                $accion = isset($_REQUEST['evento'])?$_REQUEST['evento']:'';
                switch ($accion) {
                    case 1:
                        $this->funcion->redireccionar('iraverificaInscripcion');
                        break;
                    }
            }
            else
            {
                echo "¡Posible ataque de carga de archivos!\n";
            }
        }
    }
}
else
{
    $accion = $_REQUEST['evento'];
    switch ($accion) {
        case 1:
            $this->funcion->redireccionar('iraverificaInscripcion');
            break;
        case 2:
            $this->funcion->redireccionar('iraverificaInscripcionTrasferenciaInterna');
            break;
        case 3:
            $this->funcion->redireccionar('iraverificaInscripcionTrasferenciaInterna');
            break;
        case 4:
            $this->funcion->redireccionar('iraverificaInscripcionTrasferenciaExterna');
            break;
        }
}    


?>

