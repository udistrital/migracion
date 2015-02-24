<?php
$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$cierto=0;
$i=1;

$variable['formatoId']=$_REQUEST['formatoId'];

if(isset($_REQUEST['estudianteCod']))
{    
    $variable['usuario']=$_REQUEST['estudianteCod'];
}
else
{
    $variable['usuario']=$_REQUEST['usuario'];
}

$variable['documentoId']=$_REQUEST['documentoId'];
$variable['anio']=$_REQUEST['anio'];
$variable['periodo']=$_REQUEST['periodo'];
$variable['per']=$_REQUEST['periodo'];
$variable['carrera']=$_REQUEST['carrera'];
$variable['asignatura']=$_REQUEST['asignatura'];
$variable['grupo']=$_REQUEST['grupo'];
$variable['tipoVinculacion']=$_REQUEST['tipoVinculacion'];
$variable['tipoId']=$_REQUEST['tipoId'];
$variable['fechaHoy']=date("d/m/Y");
$variable['estado']='A';


foreach ($_REQUEST as $clave => $valor)
{
    //echo $clave ."=>". $valor."<br>";
    $cadena = $clave;
    $buscar = "valores";
    $resultadoValores = strpos($cadena, $buscar);

    if($resultadoValores !== FALSE)
    {

        /* @var $_REQUEST type */
        $variable['preguntaNumero']=$i;

        if(isset($_REQUEST['estudianteCod']))
        {    
            $variable['usuario']=$_REQUEST['estudianteCod'];
        }
        else
        {
            $variable['usuario']=$_REQUEST['usuario'];
        }

        $respuesta=explode('-',$valor);

        $variable['documentoId']=$_REQUEST['documentoId'];
        $variable['anio']=$_REQUEST['anio'];
        $variable['periodo']=$_REQUEST['periodo'];
        $variable['per']=$_REQUEST['periodo'];
        $variable['carrera']=$_REQUEST['carrera'];
        $variable['nombreCarrera']=$_REQUEST['nombreCarrera'];
        $variable['asignatura']=$_REQUEST['asignatura'];
        $variable['grupo']=$_REQUEST['grupo'];
        $variable['tipoVinculacion']=$_REQUEST['tipoVinculacion'];
        $variable['nombreVinculacion']=$_REQUEST['nombreVinculacion'];
        $variable['tipoId']=$_REQUEST['tipoId'];
        $variable['fechaHoy']=date("d/m/Y");
        $variable['respuesta']=$respuesta[0];
        $variable['formularioId']=$respuesta[1];
        $variable['estado']='A';


        $cadena_sql = $this->sql->cadena_sql("consultarEvaluacion", $variable);
        $registroEvaluacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        if(is_array($registroEvaluacion))
        {
            $variable['perAcad']=$_REQUEST['perAcad'];
            //$this->funcion->redireccionar ("regresaraFormularios");
            $cierto=2;
        }
        else
        {    
            $cadena_sql = $this->sql->cadena_sql("insertaEvaluacion", $variable);
            $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

            if($registro==true)
            {
                $cierto=1;
            }
        }

        $i++;
    }
}

$variable['formularioObsId']=$variable['formularioId']-3;
if(isset($_REQUEST['observaciones']))
{
    $variable['observaciones']= $_REQUEST['observaciones'];

    $cadena_sql = $this->sql->cadena_sql("insertaObservacion", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

    if($registro==true)
    {
        $cierto=3;
    }
}

if($cierto==1 || $cierto==2 || $cierto==3)
{
    $this->funcion->redireccionar ("regresaraFormularios");
}
else
{
    echo "Error";
}
?>

