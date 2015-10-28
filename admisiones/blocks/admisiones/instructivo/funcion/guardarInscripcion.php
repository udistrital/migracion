<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/adminAdmisiones/";
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
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

if($cierto==1)
{
    if(isset($_REQUEST['carreras']) && $_REQUEST['evento']==1)
    {   
        $variable['evento']=$_REQUEST['evento'];
        $variable['rba_id']=$_REQUEST['rba_id'];
        $variable['carreras']=$_REQUEST['carreras'];
        $variable['medio']=$_REQUEST['medio'];
        $variable['prestentaPor']=$_REQUEST['prestentaPor'];
        $variable['tipoInscripcion']=$_REQUEST['tipoInscripcion'];
        $variable['pais']=$_REQUEST['pais'];
        $variable['departamento']=$_REQUEST['departamento'];
        $variable['municipio']=$_REQUEST['municipio'];
        $variable['fechaNac']=$_REQUEST['fechaNac'];
        $variable['sexo']=$_REQUEST['sexo'];
        $variable['estadoCivil']=$_REQUEST['estadoCivil'];
        $variable['direccionResidencia']=$_REQUEST['direccionResidencia'];
        $variable['localidadResidencia']=$_REQUEST['localidadResidencia'];
        $variable['estratoResidencia']=$_REQUEST['estratoResidencia'];
        $variable['estratoCosteara']=$_REQUEST['estratoCosteara'];
        $variable['telefono']=$_REQUEST['telefono'];
        $variable['email']=$_REQUEST['email'];
        $variable['tipDocActual']=$_REQUEST['tipDocActual'];
        $variable['documentoActual']=$_REQUEST['documentoActual'];
        $variable['tipDocIcfes']=$_REQUEST['tipDocIcfes'];
        $variable['documentoIcfes']=$_REQUEST['documentoIcfes'];
        $variable['tipoSangre']=$_REQUEST['tipoSangre'];
        if($_REQUEST['rh']=='0')
        {    
            $variable['rh']='+';
        }
        else
        {
            $variable['rh']='-';
        }
        $variable['registroIcfes']=$_REQUEST['registroIcfes'];
        $variable['localidadColegio']=$_REQUEST['localidadColegio'];
        $variable['tipoColegio']=$_REQUEST['tipoColegio'];
        $variable['valido']=$_REQUEST['valido'];
        $variable['numSemestres']=$_REQUEST['numSemestres'];
        $variable['discapacidad']=$_REQUEST['discapacidad'];
        $variable['observaciones']=$_REQUEST['observaciones'];
        
        $cadena_sql = $this->sql->cadena_sql("consultarInscripcionAcaspw", $variable);
        $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        if(is_array($registroInscripcion))
        {
            $valor['evento']=$_REQUEST['evento'];
            $valor['carreras']=$_REQUEST['carreras'];
            $this->funcion->redireccionar('mostrarMensajeExiste',$valor);
        }
        else
        {
            $cadena_sql = $this->sql->cadena_sql("consultarTodosAcaspw", $variable);
            $registroInscripcionTodos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            $cierto=0;
            for($i=0; $i<=count($registroInscripcionTodos)-1; $i++)
            {
                if(trim($registroInscripcionTodos[$i]['aspw_snp'])==trim($variable['registroIcfes']) && $registroInscripcionTodos[$i]['aspw_cra_cod']==$variable['carreras'])
                {
                    $cierto=2;
                }    
            }
            if($cierto==2)
            {
                $valor['evento']=$_REQUEST['evento'];
                $valor['carreras']=$_REQUEST['carreras'];
                $this->funcion->redireccionar('mostrarMensajeExiste',$valor);
            }
            else
            {
                $cadena_sql = $this->sql->cadena_sql("insertaInscripcionAcaspw", $variable);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");

                if($registro==true)
                {
                    $this->funcion->redireccionar('iraVerInscripcion');
                }
            }
        }
    }
    if(isset($_REQUEST['evento']))
    {    
        if($_REQUEST['evento']==2 || $_REQUEST['evento']==3)
        {
            $variable['rba_id']=$_REQUEST['rba_id'];
            $variable['tipo']=$_REQUEST['tipo'];
            $variable['tipoInscripcion']=$_REQUEST['evento'];
            $variable['usuario']=$_REQUEST['usuario'];
            $variable['documento']=$_REQUEST['documento'];
            $variable['codigoEstudiante']=$_REQUEST['codigoEstudiante'];
            $variable['confirmarCodigoEstudiante']=$_REQUEST['confirmarCodigoEstudiante'];
            $variable['cancelo']=$_REQUEST['cancelo'];
            $variable['telefono']=$_REQUEST['telefono'];
            $variable['email']=$_REQUEST['email'];
            if($_REQUEST['evento']==2)
            {    
                $variable['carreraCursando']=$_REQUEST['carreraCursando'];
                $variable['carreraInscribe']=$_REQUEST['carreraInscribe'];
            }
            else
            {
                $variable['carreraCursando']='null';
                $variable['carreraInscribe']='null';
            }    
            $variable['motivo']=$_REQUEST['motivo'];

            $cadena_sql = $this->sql->cadena_sql("consultarInscripcionReingreso", $variable);
            $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

            if(is_array($registroInscripcion))
            {
                $valor['evento']=$_REQUEST['evento'];
                if($_REQUEST['evento']==2)
                {
                    $valor['carreras']=$_REQUEST['carreraInscribe'];
                }
                $this->funcion->redireccionar('mostrarMensajeExiste',$valor);
            }
            else
            {    
                $cadena_sql = $this->sql->cadena_sql("insertaInscripcionReingreso", $variable);
                $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");

                if($registro==true)
                {
                    $this->funcion->redireccionar('iraVerInscripcion');
                }    
            }
        }
    }
    if(isset($_REQUEST['carreras']) && $_REQUEST['evento']==4)
    {
        $variable['tipoInscripcion']=$_REQUEST['evento'];
        $variable['rba_id']=$_REQUEST['rba_id'];
        $variable['carreras']=$_REQUEST['carreras'];
        $variable['universidadProviene']=$_REQUEST['universidadProviene'];
        $variable['carreraVeniaCursando']=$_REQUEST['carreraVeniaCursando'];
        $variable['semestreCursado']=$_REQUEST['semestreCursado'];
        $variable['motivoTransferencia']=$_REQUEST['motivoTransferencia'];
        $variable['pais']=$_REQUEST['pais'];
        $variable['departamento']=$_REQUEST['departamento'];
        $variable['municipio']=$_REQUEST['municipio'];
        $variable['fechaNac']=$_REQUEST['fechaNac'];
        $variable['sexo']=$_REQUEST['sexo'];
        $variable['estadoCivil']=$_REQUEST['estadoCivil'];
        $variable['direccionResidencia']=$_REQUEST['direccionResidencia'];
        $variable['localidadResidencia']=$_REQUEST['localidadResidencia'];
        $variable['estratoResidencia']=$_REQUEST['estratoResidencia'];
        $variable['telefono']=$_REQUEST['telefono'];
        $variable['email']=$_REQUEST['email'];
        $variable['tipDocActual']=$_REQUEST['tipDocActual'];
        $variable['documentoActual']=$_REQUEST['documentoActual'];
        $variable['tipDocIcfes']=$_REQUEST['tipDocIcfes'];
        $variable['documentoIcfes']=$_REQUEST['documentoIcfes'];
        $variable['tipoSangre']=$_REQUEST['tipoSangre'];
        if($_REQUEST['rh']=='0')
        {    
            $variable['rh']='+';
        }
        else
        {
            $variable['rh']='-';
        }
        $variable['registroIcfes']=$_REQUEST['registroIcfes'];
        $variable['localidadColegio']=$_REQUEST['localidadColegio'];
        $variable['observaciones']=$_REQUEST['observaciones'];
        
        $cadena_sql = $this->sql->cadena_sql("consultarInscripcionTransferencia", $variable);
        $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        if(is_array($registroInscripcion))
        {
            $valor['evento']=$_REQUEST['evento'];
            $valor['carreras']=$_REQUEST['carreras'];
            $this->funcion->redireccionar('mostrarMensajeExiste',$valor);
        }    
        else
        {    
            $cadena_sql = $this->sql->cadena_sql("insertaInscripcionTransferencia", $variable);
            $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "");
           
            if($registro==true)
            {
                $this->funcion->redireccionar('iraVerInscripcion');
            }    
        }
    }
    
}