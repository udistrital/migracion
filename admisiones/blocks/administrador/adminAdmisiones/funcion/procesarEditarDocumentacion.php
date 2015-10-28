<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['nombreDocumento']=$_REQUEST['nombreDocumentoNuevo'];
$variable['doc_id']=$_REQUEST['doc_id'];
$variable['nombreCorto']=$_REQUEST['nombreCortoNuevo'];
$variable['prefijo']=$_REQUEST['prefijoNuevo'];
$variable['carreras']=$_REQUEST['carrerasNuevo'];
$variable['estado']=$_REQUEST['estadoNuevo'];
$valor['opcionPagina']="registrarDocumentacion";

$cadena_sql = $this->sql->cadena_sql("actualizaDocumentacion", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

if ($registro==true) {
     $this->funcion->redireccionar('regresar',$valor);
}
?>

