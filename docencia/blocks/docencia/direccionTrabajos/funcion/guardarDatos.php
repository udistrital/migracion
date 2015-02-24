<?php
// var_dump ( $_REQUEST );
// exit ();
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
} else {
	
	$miSesion = Sesion::singleton ();
	
	$conexion = "estructura";
	$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
        
        if(!is_numeric($_REQUEST ['identificacionFinalCrear']) || $_REQUEST ['identificacionFinalCrear'] == '')
            {                   
                $this->funcion->redireccionar ( 'noDatosDocente', '' );
            }else
                {
                    switch ($_REQUEST ['tipoTrabajo']) {
		
                        case '1' :
                                $arregloDatos = array (
                                                $_REQUEST ['identificacionFinalCrear'],
                                                $_REQUEST ['tituloTrabajo'],
                                                $_REQUEST ['numAuto'],
                                                $_REQUEST ['tipoTrabajo'],
                                                $_REQUEST ['categoriaTrabajo'],
                                                $_REQUEST ['anio_direccion'],
                                                $_REQUEST ['numeActa'],
                                                $_REQUEST ['fechaActa'],
                                                $_REQUEST ['numeCaso'],
                                                $_REQUEST ['puntaje'], 
                                                $_REQUEST ['detalleDocencia'] 
                                );

                                break;
                        case '2' :

                                $arregloDatos = array (
                                                $_REQUEST ['identificacionFinalCrear'],
                                                $_REQUEST ['tituloTrabajo'],
                                                $_REQUEST ['numAuto'],
                                                $_REQUEST ['tipoTrabajo'],
                                                $_REQUEST ['categoriaTrabajo'],
                                                $_REQUEST ['anio_direccion'],
                                                $_REQUEST ['numeActa'],
                                                $_REQUEST ['fechaActa'],
                                                $_REQUEST ['numeCaso'],
                                                $_REQUEST ['puntaje'] , 
                                                $_REQUEST ['detalleDocencia']
                                );

                                break;
                        case '3' :
                                $arregloDatos = array (
                                                $_REQUEST ['identificacionFinalCrear'],
                                                $_REQUEST ['tituloTrabajo'],
                                                $_REQUEST ['numAuto'],
                                                $_REQUEST ['tipoTrabajo'],
                                                $_REQUEST ['categoriaTrabajo'],
                                                $_REQUEST ['anio_direccion'],
                                                $_REQUEST ['numeActa'],
                                                $_REQUEST ['fechaActa'],
                                                $_REQUEST ['numeCaso'],
                                                $_REQUEST ['puntaje'] = 0, 
                                                $_REQUEST ['detalleDocencia'] 
                                );

                                break;
                        case '4' :
                                $arregloDatos = array (
                                                $_REQUEST ['identificacionFinalCrear'],
                                                $_REQUEST ['tituloTrabajo'],
                                                $_REQUEST ['numAuto'],
                                                $_REQUEST ['tipoTrabajo'],
                                                $_REQUEST ['categoriaTrabajo'],
                                                $_REQUEST ['anio_direccion'],
                                                $_REQUEST ['numeActa'],
                                                $_REQUEST ['fechaActa'],
                                                $_REQUEST ['numeCaso'],
                                                $_REQUEST ['puntaje'] = 0, 
                                                $_REQUEST ['detalleDocencia'] 
                                );

                                break;
                }

                {

                        $docente = $_REQUEST ['docente'];

                        $verificarDireccion = array (
                                        $_REQUEST ['identificacionFinalCrear'],
                                        $_REQUEST ['anio_direccion'] 
                        );

                        $puedeInsertar = 0;

                        $this->cadena_sql = $this->sql->cadena_sql ( "numDireccion", $verificarDireccion );
                        $resultadoBusqueda = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );

                        switch ($resultadoBusqueda [0] [0]) {

                                case '3' :

                                        $this->funcion->redireccionar ( 'maxDirecciones', $docente );
                                        $puedeInsertar = 0;
                                        break;

                                default :
                                        // Se realiza la consulta

                                        $puedeInsertar = 1;

                                        break;
                        }
                }
                if ($puedeInsertar == 1) {
                        $cadena_sql = $this->sql->cadena_sql ( "insertarDireccion", $arregloDatos );

                        $resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

                        $arregloLogEvento = array (
                                        'direccion_trabajos',
                                        $arregloDatos,
                                        $miSesion->getSesionUsuarioId (),
                                        $_SERVER ['REMOTE_ADDR'],
                                        $_SERVER ['HTTP_USER_AGENT'] 
                        );

                        $argumento = json_encode ( $arregloLogEvento );
                        $arregloFinalLogEvento = array (
                                        $miSesion->getSesionUsuarioId (),
                                        $argumento 
                        );

                        $cadena_sql = $this->sql->cadena_sql ( "registrarEvento", $arregloFinalLogEvento );
                        $registroAcceso = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "acceso" );

                        switch ($_REQUEST ['numAuto']) {

                                case '1' :

                                        $_REQUEST ['autor_adicio1'];
                                        $_REQUEST ['autor_adicio2'] = "NULL";
                                        $_REQUEST ['autor_adicio3'] = "NULL";

                                        break;
                                case '2' :

                                        $_REQUEST ['autor_adicio1'];
                                        $_REQUEST ['autor_adicio2'];
                                        $_REQUEST ['autor_adicio3'] = "NULL";

                                        break;

                                case '3' :

                                        $_REQUEST ['autor_adicio1'];
                                        $_REQUEST ['autor_adicio2'];
                                        $_REQUEST ['autor_adicio3'];

                                        break;
                        }

                        if ($resultado) {
                                for($i = 1; $i <= 3; $i ++) {
                                        $arregloAutores = array (
                                                        $_REQUEST ['autor_adicio' . $i] 
                                        );

                                        $this->cadena_sql = $this->sql->cadena_sql ( "insertarautor", $arregloAutores, $resultado [0] [0] );
                                        $resultadoAutores = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "acceso" );

                                        $arregloLogEvento = array (
                                                        'autors_direccion',
                                                        $arregloAutores,
                                                        $miSesion->getSesionUsuarioId (),
                                                        $_SERVER ['REMOTE_ADDR'],
                                                        $_SERVER ['HTTP_USER_AGENT'] 
                                        );

                                        $argumento = json_encode ( $arregloLogEvento );

                                        $arregloFinalLogEvento = array (
                                                        $miSesion->getSesionUsuarioId (),
                                                        $argumento 
                                        );

                                        $cadena_sql = $this->sql->cadena_sql ( "registrarEvento", $arregloFinalLogEvento );
                                        $registroAcceso = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "acceso" );
                                }
                        }

                        if ($resultado) {
                                $this->funcion->redireccionar ( 'inserto', $_REQUEST ['docente'] );
                        } else {
                                $this->funcion->redireccionar ( 'noInserto', $_REQUEST ['docente'] );
                        }
                }
            }
        
	
	
}
?>