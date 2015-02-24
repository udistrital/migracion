<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroCancelarCIEstudianteCoordinador extends funcionGeneral {
//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

         //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle
            $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        $this->formulario="registroCancelarCIEstudianteCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");



    }

    
    function cancelarCreditos($configuracion) {

        //echo "estoy aca";exit;
        $codEstudiante=$_REQUEST['codEstudiante'];
        $proyecto=$_REQUEST['proyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $espacio=$_REQUEST['codEspacio'];
        $grupo=$_REQUEST['grupo'];
        $nombre=$_REQUEST['nombre'];
        $creditos=$_REQUEST['creditos'];
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $codProyecto=$_REQUEST['codProyecto'];

        $cadena_sql=$this->sql->cadena_sql($configuracion,"periodo", '');//echo $cadena_sql_buscarEspacioOracle;exit;
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        $ano=$resultado_periodo[0][0];
        $periodo=$resultado_periodo[0][1];

        $variables=array($codEstudiante,$proyecto,$ano,$periodo,$planEstudio,$espacio,$grupo,$nombre,$creditos);
        $cadena_sql_buscarEspacioOracle=$this->sql->cadena_sql($configuracion,"buscar_espacio_oracle", $variables);//echo $cadena_sql_buscarEspacioOracle;exit;
        $resultado_EspacioOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_buscarEspacioOracle,"busqueda" );

        $cadena_sql_buscarEspacioMysql=$this->sql->cadena_sql($configuracion,"buscar_espacio_mysql", $variables);//echo $cadena_sql_buscarEspacioMysql;exit;
        $resultado_EspacioMysql=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEspacioMysql,"busqueda" );

        if($resultado_EspacioOracle[0][3]==$grupo)
        {
            $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,"verificar_creditos", $variables);
            $resultado_creditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditos,"busqueda");

            $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,"cancelar_espacio_oracle", $variables);
            $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"" );

            $cadena_sql_actualizarCreditos=$this->sql->cadena_sql($configuracion,"cancelar_espacio_mysql", $variables);
            $resultado_actualizarCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarCreditos,"" );

            $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_ins", $variables);
            $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

            $variables[10]=(($resultado_cupoInscritos[0][0])-1);
            
            $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,"actualizar_cupo", $variables);
            $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

            $variablesRegistro=array($this->usuario,date('YmdGis'),'2','Cancela Espacio académico',$ano.", ".$periodo.", ".$espacio.", ".$grupo.", 0, ".$planEstudio.", ".$proyecto, $codEstudiante);
            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,"registroEvento", $variablesRegistro);
            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarIDRegistro", $variablesRegistro);
            $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

            echo "<script>alert ('Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";
            
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarCIEstudianteCoordinador";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
            $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
           /* $variable.="&proyecto=".$proyecto;
            $variable.="&espacio=".$espacio;
            $variable.="&grupo=".$grupo;
            $variable.="&ano=".$ano;
            $variable.="&periodo=".$periodo;
            $variable.="&nombre=".$nombre;
            $variable.="&creditos=".$creditos;*/

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
        else{
            echo "<script>alert ('La base de datos se encuentra ocupada. El Espacio Académico ".$nombre." no ha sido cancelado');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminConsultarCIEstudianteCoordinador";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
            $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
    }

    function cancelar($configuracion) {
       
       // var_dump($_REQUEST);exit;
        $codEstudiante=$_REQUEST['codEstudiante'];
        $proyecto=$_REQUEST['proyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $espacio=$_REQUEST['espacio'];
        $grupo=$_REQUEST['grupo'];
        $ano=$_REQUEST['ano'];
        $periodo=$_REQUEST['periodo'];
        $nombre=$_REQUEST['nombre'];
        $creditos=$_REQUEST['creditos'];
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $codProyecto=$_REQUEST['codProyecto'];
        
        echo "<script>alert ('El Espacio Académico ".$nombre.$codEstudiante." no ha sido cancelado');</script>";
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $variable="pagina=adminConsultarCIEstudianteCoordinador";
        $variable.="&opcion=mostrarConsulta";
        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
        $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
        $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";
    }


    function redireccionarProceso($configuracion, $opcion, $valor="") {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        unset($_REQUEST['action']);
        $cripto=new encriptar();
        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        switch($opcion) {
            case "verificarCreditos":
                $variable="pagina=registroCancelarCIEstudianteCoordinador";
                $variable.="&opcion=verificarCreditos";
                $variable.="&codEstudiante=".$valor[0];
                $variable.="&proyecto=".$valor[1];
                $variable.="&ano=".$valor[2];
                $variable.="&periodo=".$valor[3];
                $variable.="&codEspacio=".$valor[4];
                $variable.="&grupo=".$valor[5];
                $variable.="&planEstudio=".$valor[6];
                $variable.="&nombre=".$valor[7];
                $variable.="&verificar=".$valor[11];
                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                break;

            case "solicitar_confirmacion":
                $variable="pagina=registroCancelarCIEstudianteCoordinador";
                $variable.="&opcion=solicitarConfirmacion";
                $variable.="&carrera=".$valor[0];
                $variable.="&planEstudio=".$valor[1];
                $variable.="&orden=".$valor[2];
                break;

            case "cancelar":
                $variable="pagina=registroCancelarCIEstudianteCoordinador";
                $variable.="&opcion=cancelarCreditos";
                $variable.="&codEstudiante=".$valor[0];
                $variable.="&proyecto=".$valor[1];
                $variable.="&ano=".$valor[2];
                $variable.="&periodo=".$valor[3];
                $variable.="&codEspacio=".$valor[5];
                $variable.="&grupo=".$valor[6];
                $variable.="&planEstudio=".$valor[4];
                $variable.="&nombre=".$valor[7];
                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];

        }


        $variable=$cripto->codificar_url($variable,$configuracion);
        echo "<script>location.replace('".$indice.$variable."')</script>";

    }


}


?>

