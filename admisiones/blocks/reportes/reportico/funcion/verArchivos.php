<?php
$rutaArchivos=$this->miConfigurador->getVariableConfiguracion("raizArchivoDocumentos");

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

# incluimos la libreria fdpf
# http://www.fpdf.org/
require_once('fpdf/fpdf17/fpdf.php');
# incluimos la libreria fpdi
# http://www.setasign.com/products/fpdi/about/
require_once('fpdf/FPDI-1.5.2/fpdi.php');

# inicializamos el objeto
$pdf = new FPDI();

# definimos el archivo pdf a leer. Nos devuel el numero de paginas
$id = $_REQUEST['archivo']; 
$enlace = $rutaArchivos.$id; 
$paginas=$pdf->setSourceFile($enlace);

for($pagina=1; $pagina < $paginas+1; $pagina++){
    # importamos cada una de las paginas
    $templateId=$pdf->importPage($pagina);
    # obtenemos el temaño de cada hoja
    $size=$pdf->getTemplateSize($templateId);

    // create a page definiendo el formato y tamaños
    if($size['w'] > $size['h'])
    {
            $pdf->AddPage('L',array($size['w'],$size['h']));
    }else {
            $pdf->AddPage('P',array($size['w'],$size['h']));
    }
    $pdf->useTemplate($templateId);
}
# devolvemos el documento
$pdf->Output();
?>

