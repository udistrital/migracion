<?php

if(strtoupper(trim($tipo_campo))=="NUMERICO"){$script_validacion = "return solo_numero(event)";}
if(strtoupper(trim($tipo_campo))=="TEXTO"){$script_validacion = "return solo_texto(event)";}
if(strtoupper(trim($tipo_campo))=="CORREO"){$script_validacion = "";}        
return $script_validacion;

?>
