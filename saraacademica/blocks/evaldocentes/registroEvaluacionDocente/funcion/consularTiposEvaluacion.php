<?php

if($_REQUEST['tiporesultados']==1 && $_REQUEST['periodo']!=-1)
{
    $this->funcion->redireccionar ("iraObservaciones");
}    
elseif($_REQUEST['tiporesultados']==2 && $_REQUEST['periodo']!=-1)
{
    $this->funcion->redireccionar ("iraResultadosParciales");
}
elseif($_REQUEST['tiporesultados']==3 && $_REQUEST['periodo']!=-1)
{
    $this->funcion->redireccionar ("iraAvanceEvaluacion");
}
else
{
    $mensaje= "Seleccione una opción para consultar...";
    $html="<script>alert('".$mensaje."');</script>";
    echo $html;
    echo "<script>location.replace('')</script>";
}    
?>

