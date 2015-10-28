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
                
        while(!feof($file))
        {
           // echo fgets($file). "<br />";
            $valores=fgets($file);
            $valor=substr($valores,67,15);
            $valor_inicio=  substr($valor,0,1);
            //Se valida que el campo correspondiente al valor inicie en valor diferente a 0, debido a que en el archivo texto que
            //reporta el banco, algunos registros aparentemente aparecen corridos.
            if($valor_inicio!=0)
            {
                $variable['codOf']=substr($valores,2,3);
                $variable['anio']=substr($valores,5,4);
                $variable['mes']=substr($valores,9,2);
                $variable['dia']=substr($valores,11,2);
                $referencia=substr($valores,45,11);
                $variable['identificacion']=substr($valores,56,12);
                $variable['valor']=substr($valores,68,15);
                $variable['anioAcad']=$_REQUEST['anio'];
                $variable['periodo']=$_REQUEST['periodo'];
                $variable['id_periodo']=$_REQUEST['id_periodo'];
                $variable['banco']=23;
                $variable['rba_tipo']=1;
            }
            else
            {    
                $variable['codOf']=substr($valores,2,3);
                $variable['anio']=substr($valores,5,4);
                $variable['mes']=substr($valores,9,2);
                $variable['dia']=substr($valores,11,2);
                $referencia=substr($valores,44,11);
                $variable['identificacion']=substr($valores,55,12);
                $variable['valor']=substr($valores,67,15);
                $variable['anioAcad']=$_REQUEST['anio'];
                $variable['periodo']=$_REQUEST['periodo'];
                $variable['id_periodo']=$_REQUEST['id_periodo'];
                $variable['banco']=23;
                $variable['rba_tipo']=1;
            }
            //Los campos en 0 o vacíos los descarta
            if($variable['anio']!='0000' && $variable['anio']!="")
            {    
                //Consulta los PINES registrados en el sistema
                $cadena_sql = $this->sql->cadena_sql("consultarPinesRegistrados", $variable);
                $registroPinesRegistrados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                $cuenta=  count($registroPinesRegistrados);
                $variable['credencial']=$cuenta+1;
               
                for($i=0; $i<=$cuenta-1; $i++)
                {
                    //Compara si referencias de pago reportadas en el banco están registradas en el sistema.
                    if(trim($registroPinesRegistrados[$i]['rba_ref_pago'])== trim($referencia))
                    {
                        $yaregistrados=$registroPinesRegistrados[$i]['rba_ref_pago'];
                        
                    }
                }
                
                $cierto=0;
                //Descarta las referencias de pago reportadas y registradas en el sistema, para no duplicar registros.
                $yaregistrados=isset($yaregistrados)?$yaregistrados:'';
                if($yaregistrados!=$referencia)
                {   
                    
                    $variable['referencia']=$referencia;
                    $variable['clave']=$this->miConfigurador->fabricaConexiones->crypto->codificarClave(trim($variable['referencia']));
                    $cadena_sql = $this->sql->cadena_sql("insertaPines", $variable);
                    $registroEvento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");
                    
                    if ($registroEvento==true)
                    {
                        $cierto=1;
                    }
                    else
                    {
                        echo "Ups... error!!!";
                    }
                }
                else
                {
                    //echo "MMM";
                    $cierto=2;
                }    
                
            }
        }
        fclose($file);
        if($cierto==1)
        {
            //echo "Se insertó";
            $this->funcion->redireccionar ("regresaraRegistroPines");
        }
        if($cierto==2)
        {
            $this->funcion->redireccionar ("mostrarMensajeArchivoRepetido");
        }  
}
else
{
    $this->funcion->redireccionar ("mostrarMensajeArchivoPines");
}    
?>

