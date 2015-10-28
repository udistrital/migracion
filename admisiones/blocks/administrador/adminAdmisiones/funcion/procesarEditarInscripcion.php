<?php
$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
    
$variable['asp_id']=$_REQUEST['asp_id'];
$variable['registroIcfes']=$_REQUEST['registroIcfes'];
$variable['carrera']=$_REQUEST['carrera'];
$variable['tipoInscripcion']=$_REQUEST['tipoInscripcion'];
$variable['apellidos']=$_REQUEST['apellidos'];
$variable['nombres']=$_REQUEST['nombres'];
$variable['documento']=$_REQUEST['documento'];
$variable['sexo']=$_REQUEST['sexo'];
if($_REQUEST['medio']==''){ $variable['medio']='null';} else {$variable['medio']=$_REQUEST['medio'];}
if($_REQUEST['localidadColegio']==''){ $variable['localidadColegio']='null';} else {$variable['localidadColegio']=$_REQUEST['localidadColegio'];}
if($_REQUEST['localidadResidencia']==''){ $variable['localidadResidencia']='null';} else {$variable['localidadResidencia']=$_REQUEST['localidadResidencia'];}
if($_REQUEST['estratoResidencia']==''){ $variable['estratoResidencia']='null';} else {$variable['estratoResidencia']=$_REQUEST['estratoResidencia'];}
if($_REQUEST['serMilitar']==''){ $variable['serMilitar']='';} else {$variable['serMilitar']=$_REQUEST['serMilitar'];}
if($_REQUEST['puntajeTotal']==''){ $variable['puntajeTotal']='null';} else {$variable['puntajeTotal']=$_REQUEST['puntajeTotal'];}
if($_REQUEST['puntajeCal']==''){ $variable['puntajeCal']='null';} else {$variable['puntajeCal']=$_REQUEST['puntajeCal'];}
if($_REQUEST['cieSociales']==''){ $variable['cieSociales']='null';} else {$variable['cieSociales']=$_REQUEST['cieSociales'];}
if(isset($_REQUEST['biologia'])==''){ $variable['biologia']='null';} else {$variable['biologia']=$_REQUEST['biologia'];}
if($_REQUEST['quimica']==''){ $variable['quimica']='null';} else {$variable['quimica']=$_REQUEST['quimica'];}
if($_REQUEST['fisica']==''){ $variable['fisica']='null';} else {$variable['fisica']=$_REQUEST['fisica'];}
if($_REQUEST['sociales']==''){ $variable['sociales']='null';} else {$variable['sociales']=$_REQUEST['sociales'];}
if($_REQUEST['aptitudVerbal']==''){ $variable['aptitudVerbal']='null';} else {$variable['aptitudVerbal']=$_REQUEST['aptitudVerbal'];}
if($_REQUEST['espaniolLit']==''){ $variable['espaniolLit']='null';} else {$variable['espaniolLit']=$_REQUEST['espaniolLit'];}
if($_REQUEST['aptitudMat']==''){ $variable['aptitudMat']='null';} else {$variable['aptitudMat']=$_REQUEST['aptitudMat'];}
if($_REQUEST['conMat']==''){ $variable['conMat']='null';} else {$variable['conMat']=$_REQUEST['conMat'];}
if($_REQUEST['idioma']==''){ $variable['idioma']='null';} else {$variable['idioma']=$_REQUEST['idioma'];}
if($_REQUEST['geografia']==''){ $variable['geografia']='null';} else {$variable['geografia']=$_REQUEST['geografia'];}
if($_REQUEST['historia']==''){ $variable['historia']='null';} else {$variable['historia']=$_REQUEST['historia'];}
if($_REQUEST['filosofia']==''){ $variable['filosofia']='null';} else {$variable['filosofia']=$_REQUEST['filosofia'];}
if(isset($_REQUEST['interdiciplinaria'])==''){ $variable['interdiciplinaria']='null';} else {$variable['interdiciplinaria']=$_REQUEST['interdiciplinaria'];}
if($_REQUEST['codInter']==''){ $variable['codInter']='null';} else {$variable['codInter']=$_REQUEST['codInter'];}
if($_REQUEST['electiva']==''){ $variable['electiva']='null';} else {$variable['electiva']=$_REQUEST['electiva'];}
if($_REQUEST['puntosHom']==''){ $variable['puntosHom']='null';} else {$variable['puntosHom']=$_REQUEST['puntosHom'];}
        
$cadena_sql = $this->sql->cadena_sql("actualizaInscripcion", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

$valor['opcionPagina']="formEditarInscripcion";   

if ($registro==true) {
 $this->funcion->redireccionar('regresar',$valor);
}
    
   
?>

