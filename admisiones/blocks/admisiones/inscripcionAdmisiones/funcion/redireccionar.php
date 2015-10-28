<?php

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    
    $miSesion = Sesion::singleton();

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");
    
    switch ($opcion) {
        
	case "confirmarPeriodo":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=confirmacionPeriodo";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        
        case "mostrarMensaje":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&mensaje=fechasEventos";
            $variable.="&evento=".$valor['evento'];
            $variable.="&id_periodo=".$valor['id_periodo'];
            $variable.="&usuario=".$_REQUEST['usuario'];
            break;
        
        case "mostrarMensajeExiste":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&mensaje=mensajeExiste";
            $variable.="&evento=".$valor['evento'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            if(isset($valor['carreras']))
            {    
                $variable.="&carreras=".$valor['carreras'];
            }
            $variable.="&id_periodo=".$_REQUEST['id_periodo'];
            $variable.="&usuario=".$_REQUEST['usuario'];
            break;
        
        case "iraCarrerasOfrecidas":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=carrerasOfrecidas";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&evento=".$_REQUEST['evento'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "mensajeTituloTecnologo":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=muestraMensaje";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&carreras=".$_REQUEST['carreras'];
            $variable.="&evento=".$_REQUEST['evento'];
            $variable.="&mensaje=tituloTecnologo";
            $variable.="&usuario=".$_REQUEST['usuario'];
            break;
        case "iraFormularioInscripcion":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=formularioInscripcion";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&evento=".$_REQUEST['evento'];
            if(isset($_REQUEST['carreras']))
            {    
                $variable.="&carreras=".$_REQUEST['carreras'];
            }
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "iraFormularioInscripcionDoctoradoIng":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=formularioInscripcionDoctoradoIng";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&evento=".$_REQUEST['evento'];
            if(isset($_REQUEST['carreras']))
            {    
                $variable.="&carreras=".$_REQUEST['carreras'];
            }
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;    
            
        case "iraVerInscripcion":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=verInscripcion";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&evento=".$_REQUEST['evento'];
            if(isset($_REQUEST['carreras']))
            {    
                $variable.="&carreras=".$_REQUEST['carreras'];
            }
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "iraverificaInscripcion":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=verificaInscripcion";
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&carreras=".$_REQUEST['carreras'];
            $variable.="&medio=".$_REQUEST['medio'];
            $variable.="&prestentaPor=".$_REQUEST['prestentaPor'];
            $variable.="&tipoInscripcion=".$_REQUEST['tipoInscripcion'];
            $variable.="&pais=".$_REQUEST['pais'];
            $variable.="&departamento=".$_REQUEST['departamento'];
            $variable.="&municipio=".$_REQUEST['municipio'];
            $variable.="&fechaNac=".$_REQUEST['fechaNac'];
            $variable.="&sexo=".$_REQUEST['sexo'];
            $variable.="&estadoCivil=".$_REQUEST['estadoCivil'];
            $variable.="&direccionResidencia=".$_REQUEST['direccionResidencia'];
            $variable.="&localidadResidencia=".$_REQUEST['localidadResidencia'];
            $variable.="&estratoResidencia=".$_REQUEST['estratoResidencia'];
            $variable.="&estratoCosteara=".$_REQUEST['estratoCosteara'];
            $variable.="&telefono=".$_REQUEST['telefono'];
            $variable.="&email=".$_REQUEST['email'];
            $variable.="&tipDocActual=".$_REQUEST['tipDocActual'];
            $variable.="&documentoActual=".$_REQUEST['documentoActual'];
            $variable.="&tipDocIcfes=".$_REQUEST['tipDocIcfes'];
            $variable.="&documentoIcfes=".$_REQUEST['documentoIcfes'];
            $variable.="&tipoSangre=".$_REQUEST['tipoSangre'];
            if($_REQUEST['rh']=='+')
            {    
                $variable.="&rh=0";
            }
            else
            {
                $variable.="&rh=1";
            }
            if(isset($_REQUEST['registroIcfes1']))
            {    
                $variable.="&registroIcfes1=".$_REQUEST['registroIcfes1'];
            }
            else
            {
                $variable.="&registroIcfes2=".$_REQUEST['registroIcfes2'];
            }
            $variable.="&localidadColegio=".$_REQUEST['localidadColegio'];
            $variable.="&tipoColegio=".$_REQUEST['tipoColegio'];
            $variable.="&valido=".$_REQUEST['valido'];
            $variable.="&numSemestres=".$_REQUEST['numSemestres'];
            $variable.="&discapacidad=".$_REQUEST['discapacidad'];
            $variable.="&observaciones=".$_REQUEST['observaciones'];
            $variable.="&evento=".$_REQUEST['evento'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;
        case "iraverificaInscripcionTrasferenciaInterna":
            $variable = "pagina=" . $miPaginaActual;
            $variable.="&opcion=verificaInscripcionTransInt";
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&evento=".$_REQUEST['evento'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            $variable.="&documento=".$_REQUEST['documento'];
            $variable.="&codigoEstudiante=".$_REQUEST['codigoEstudiante'];
            $variable.="&confirmarCodigoEstudiante=".$_REQUEST['confirmarCodigoEstudiante'];
            $variable.="&cancelo=".$_REQUEST['cancelo'];
            $variable.="&telefono=".$_REQUEST['telefono'];
            $variable.="&email=".$_REQUEST['email'];
            if($_REQUEST['evento']==2)
            {    
                $variable.="&carreraCursando=".$_REQUEST['carreraCursando'];
                $variable.="&carreraInscribe=".$_REQUEST['carreraInscribe'];
            }
            $variable.="&motivo=".$_REQUEST['motivo'];
            break;
           
        case "iraverificaInscripcionTrasferenciaExterna":
            $variable = "pagina=".$miPaginaActual;
            $variable.="&opcion=verificaInscripcionTranExterna";
            $variable.="&carreras=".$_REQUEST['carreras'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&rba_id=".$_REQUEST['rba_id'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&universidadProviene=".$_REQUEST['universidadProviene'];
            $variable.="&carreraVeniaCursando=".$_REQUEST['carreraVeniaCursando'];
            $variable.="&semestreCursado=".$_REQUEST['semestreCursado'];
            $variable.="&motivoTransferencia=".$_REQUEST['motivoTransferencia'];
            $variable.="&pais=".$_REQUEST['pais'];
            $variable.="&departamento=".$_REQUEST['departamento'];
            $variable.="&municipio=".$_REQUEST['municipio'];
            $variable.="&fechaNac=".$_REQUEST['fechaNac'];
            $variable.="&sexo=".$_REQUEST['sexo'];
            $variable.="&estadoCivil=".$_REQUEST['estadoCivil'];
            $variable.="&direccionResidencia=".$_REQUEST['direccionResidencia'];
            $variable.="&localidadResidencia=".$_REQUEST['localidadResidencia'];
            $variable.="&estratoResidencia=".$_REQUEST['estratoResidencia'];
            $variable.="&telefono=".$_REQUEST['telefono'];
            $variable.="&email=".$_REQUEST['email'];
            $variable.="&tipDocActual=".$_REQUEST['tipDocActual'];
            $variable.="&documentoActual=".$_REQUEST['documentoActual'];
            $variable.="&tipDocIcfes=".$_REQUEST['tipDocIcfes'];
            $variable.="&documentoIcfes=".$_REQUEST['documentoIcfes'];
            $variable.="&tipoSangre=".$_REQUEST['tipoSangre'];
            if($_REQUEST['rh']=='+')
            {    
                $variable.="&rh=0";
            }
            else
            {
                $variable.="&rh=1";
            }
            
            if(isset($_REQUEST['registroIcfes1']))
            {    
                $variable.="&registroIcfes1=".$_REQUEST['registroIcfes1'];
            }
            else
            {
                $variable.="&registroIcfes2=".$_REQUEST['registroIcfes2'];
            }
            $variable.="&localidadColegio=".$_REQUEST['localidadColegio'];
            $variable.="&observaciones=".$_REQUEST['observaciones'];
            $variable.="&evento=".$_REQUEST['evento'];
            $variable.="&usuario=" . $_REQUEST['usuario'];
            break;    
            
        case "paginaPrincipal":
            
            $variable = "pagina=admisiones";
            $variable.="&usuario=".$_REQUEST['usuario'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            break;
    }

    foreach ($_REQUEST as $clave => $valor) {
        unset($_REQUEST[$clave]);
    }

    $enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar($variable);

    $_REQUEST[$enlace] = $variable;
    $_REQUEST["recargar"] = true;
}
?>
