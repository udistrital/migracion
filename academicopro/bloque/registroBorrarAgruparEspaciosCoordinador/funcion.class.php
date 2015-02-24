<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroBorrarAgruparEspaciosCoordinador extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroEditarAgruparEspaciosCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    } 

     function cambiarEstadoEncabezado($configuracion)

        {         
         $usuario=$this->usuario;
         $planEstudio=$_REQUEST["planEstudio"];
         $codProyecto=$_REQUEST["codProyecto"];
         $nombreProyecto=$_REQUEST["nombreProyecto"];
         $clasificacion=$_REQUEST["clasificacion"];
         $encabezadoNombre=$_REQUEST["encabezadoNombre"];
         $encabezadoDescripcion=$_REQUEST["encabezadoDescripcion"];
         $encabezadoCreditos=$_REQUEST["encabezadoCreditos"];
         $encabezadoNivel=$_REQUEST["encabezadoNivel"];
         $id_encabezado=$_REQUEST["id_encabezado"];

         //$band=0;

          if($_REQUEST["id_encabezado"])
                {
                 $band=1;

                 $cadena_sql_buscarEspaciosAsociados=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEspacioAsociados", $id_encabezado);//echo $cadena_sql_modificarEncabezado;exit;
                 $resultadoBuscarEspaciosAsociados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEspaciosAsociados,"busqueda" );

                 $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                 $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                 $ano=$resultadoPeriodo[0][0];
                 $periodo=$resultadoPeriodo[0][1];               
                               
                 if($resultadoPeriodo==false)
                 {
                   $band=2;
                 }
                 if(is_array($resultadoBuscarEspaciosAsociados))
                 {
                   $band=3;
                 }
                 
                }
           
           if($band==1)
                {
                    $cadena_sql_modificarEstado=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"modificarEstadoEncabezado", $id_encabezado);//echo $cadena_sql_modificarEstado;exit;
                    $resultadoModificarEstado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_modificarEstado,"" );

                    $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $encabezadoNombre, $planEstudio, $codProyecto, $id_encabezado );
                    $cadena_sql_registroModificar=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroBorrarEncabezado",$variablesRegistro);
                    $resultadoRegistroModificar==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroModificar,"");

                   echo "<script>alert ('El Encabezado de Espacios Académicos ha sido Borrado');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
		   $variable.="&opcion=verEncabezado";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
                else if($band==2)
                {
                   echo "<script>alert ('La información no pudo ser registrada intente de nuevo');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
		   $variable.="&opcion=verEncabezado";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
                else if($band==3)
                {
                   echo "<script>alert ('El nombre general no debe tener espacios asociados para borrarlo');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
		   $variable.="&opcion=verEncabezado";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }               
        }

    }

?>