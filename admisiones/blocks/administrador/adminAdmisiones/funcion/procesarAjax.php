<?php
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cierto=0;
for($i=0; $i<=count($registro)-1; $i++)
{  
    if($registro[$i]['aca_estado']=="X")
    {
        $cierto=1;
        $variable['id_periodo']=$registro[$i]['aca_id'];
        $variable['anio']=$registro[$i]['aca_anio'];
        $variable['periodo']=$registro[$i]['aca_periodo'];
    }
}

$cadena_sql = $this->sql->cadena_sql("consultarAcaspRegistrados", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if($_REQUEST['funcion']=="#tablaInscripciones" && (!isset($_REQUEST['codLocalidad'])) && (!isset($_REQUEST['codLocalidadCol'])) && (!isset($_REQUEST['estratoRes'])) && (!isset($_REQUEST['tipIns'])))
{
    for($i=0;$i<count($registro);$i++)
    {
        $data[]=array('Año'=>$registro[$i]['aca_anio'],'Periodo'=>$registro[$i]['aca_periodo'],'Credencial'=>$registro[$i]['rba_asp_cred'],'Inscripcion'=>$registro[$i]['ti_nombre'],'Nombre'=>$registro[$i]['asp_nombre'],'Apellido'=>$registro[$i]['asp_apellido'],'Identificacion'=>$registro[$i]['asp_nro_iden_act'],'Carrera'=>$registro[$i]['asp_cra_cod'],'SNP'=>$registro[$i]['asp_snp']);

    }
    $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($registro),
            "iTotalDisplayRecords" => count($registro),
            "aaData"=>$data);
    echo json_encode($results);
}

if(isset($_REQUEST['codLocalidad']))
{
    $localidad=explode('-',$_REQUEST["codLocalidad"]);
    $variable['localidadRes']=$localidad[0];
    $variable['idacasp']=$localidad[1];

    $cadena_sql = $this->sql->cadena_sql("actualizaAcaspId", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    if ($registro==true) {
        echo "Registro exitoso";
    }
}

if(isset($_REQUEST['codLocalidadCol']))
{
    $localidad=explode('-',$_REQUEST["codLocalidadCol"]);
    $variable['localidadCol']=$localidad[0];
    $variable['idacasp']=$localidad[1];
    
    $cadena_sql = $this->sql->cadena_sql("actualizaAcaspId", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

    if ($registro==true) {
        echo "Registro exitoso";
    }
}  

if(isset($_REQUEST['estratoRes']))
{
    $localidad=explode('-',$_REQUEST["estratoRes"]);
    $variable['estrato']=$localidad[0];
    $variable['idacasp']=$localidad[1];
    
    $cadena_sql = $this->sql->cadena_sql("actualizaAcaspId", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

    if ($registro==true) {
        echo "Registro exitoso";
    }
}
if(isset($_REQUEST['tipIns'])){
    $localidad=explode('-',$_REQUEST["tipIns"]);
    $variable['tipIns']=$localidad[0];
    $variable['idacasp']=$localidad[1];
    
    $cadena_sql = $this->sql->cadena_sql("actualizaAcaspId", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
  
    if ($registro==true) {
        echo "Registro exitoso";
    }
}
