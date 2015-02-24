<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
//@ Clase que permite realizar el registro de datos de planes de estudio de horas
class funcion_registroAdicionarDatosPlanEstudiosHoras extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");

        $this->configuracion=$configuracion;
        $this->validacion=new validarInscripcion();
        $this->procedimientos=new procedimientos();
        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"asesvice");
       
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registro_adicionarDatosPlanEstudiosHoras";
        $this->bloque="planEstudiosHoras/registro_adicionarDatosPlanEstudiosHoras";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
        ?>
    <head>
        <script language="JavaScript">
            var message = "";
            function clickIE(){
                if (document.all){
                    (message);
                    return false;
                }
            }
            function clickNS(e){
                if (document.layers || (document.getElementById && !document.all)){
                    if (e.which == 2 || e.which == 3){
                        (message);
                        return false;
                    }
                }
            }
            if (document.layers){
                document.captureEvents(Event.MOUSEDOWN);
                document.onmousedown = clickNS;
            } else {
                document.onmouseup = clickNS;
                document.oncontextmenu = clickIE;
            }
            document.oncontextmenu = new Function("return false")
        </script>
    </head>
        <?

    }

    /**
     * Funcion que valida los datos para inscribir el registro en la tabla de parametros 
     * @param <array> $_REQUEST 
     * 
     */
    function validarRegistro()
    {
        $mensaje="";
        
        //var_dump($_REQUEST);exit;
        $idProyecto=(isset($_REQUEST['idProyecto'])?$_REQUEST['idProyecto']:'');
        $idPlanEstudios=(isset($_REQUEST['idPlanEstudios'])?$_REQUEST['idPlanEstudios']:'');
        $numObligatorios=(isset($_REQUEST['numObligatorios'])?$_REQUEST['numObligatorios']:0);
        $numElectivos=(isset($_REQUEST['numElectivos'])?$_REQUEST['numElectivos']:0);
        
        if($numObligatorios>0 || $numElectivos>0){
            
            if(is_numeric($numObligatorios) && is_numeric($numElectivos)){
                
                $total =  $numObligatorios+$numElectivos;  

                $datosRegistro=array('idProyecto'=>$idProyecto,
                                        'idPlanEstudios'=>$idPlanEstudios,
                                        'numObligatorios'=>$numObligatorios,
                                        'numElectivos'=>$numElectivos,
                                        'totalEspacios'=>$total
                        );
                
                $parametrosPlan = $this->consultarParametrosPlan($datosRegistro);
                $obligatorios=$this->buscarNumEspaciosObligatorios($parametrosPlan);
                $electivos=$this->buscarNumEspaciosElectivos($parametrosPlan);
                if($obligatorios==''){
                    $adicionadoO= $this->adicionarParametro($datosRegistro,6);
                }else{
                    $adicionadoO= $this->actualizarParametro($datosRegistro,6);
                }
                
                if($electivos==''){
                    $adicionadoE= $this->adicionarParametro($datosRegistro,8);

                }else{
                   $adicionadoE= $this->actualizarParametro($datosRegistro,8);
                }
                $this->actualizarTotalEspacios($datosRegistro);
                //verificamos que se halla realizado la insercion o actualizacion correctamente
                if($adicionadoO && $adicionadoE){
                        $mensaje="Parametros registrados con exito.";

                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'67',
                                                          'descripcion'=>'Registro parametros plan estudios ',
                                                          'registro'=>"idProyecto-> ".$datosRegistro['idProyecto'].", idPlanEstudios->".$datosRegistro['idPlanEstudios'].", numObligatorios-> ".$datosRegistro['numObligatorios'].", numElectivos->".$datosRegistro['numElectivos'],
                                                          'afectado'=>$datosRegistro['idProyecto']);

                       $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                       $variable="pagina=admin_consultarPlanEstudiosHoras";
                       $variable.="&opcion=consultar";
                       $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                       $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

                }else{
                       $variablesRegistro=array('usuario'=>$this->usuario,
                                                          'evento'=>'67',
                                                          'descripcion'=>'Error al registrar parametros plan estudios ',
                                                          'registro'=>"idProyecto-> ".$datosRegistro['idProyecto'].", idPlanEstudios->".$datosRegistro['idPlanEstudios'].", numObligatorios-> ".$datosRegistro['numObligatorios'].", numElectivos->".$datosRegistro['numElectivos'],
                                                          'afectado'=>$datosRegistro['idProyecto']);

                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                       $variable="pagina=admin_consultarPlanEstudiosHoras";
                       $variable.="&opcion=adicionar";
                       foreach ($_REQUEST as $key => $value) {
                           if($key!='opcion' && $key!='pagina'){
                                $variable.="&".$key."=".$value;
                           }

                       }
                       $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                       
                       $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

                }
               
            }else{
                $mensaje="los valores deben ser númericos";
                $variablesRegistro=array('usuario'=>$this->usuario,
                                        'evento'=>'67',
                                        'descripcion'=>'Error al registrar parametros Plan de estudios Horas -'.$mensaje,
                                        'registro'=>"idProyecto-> ".$idProyecto.", idPlanEstudios->".$idPlanEstudios.", obligatorios->".$numObligatorios.", electivos->".$numElectivos,
                                        'afectado'=>$idProyecto);
           
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=admin_consultarPlanEstudiosHoras";
                $variable.="&action=planEstudiosHoras/admin_consultarPlanEstudiosHoras";
                $variable.="&opcion=parametros";
                $variable.="&idProyecto=".$idProyecto;
                $variable.="&idPlanEstudios=".$idPlanEstudios;
                $variable.="&numObligatorios=".(int)$numObligatorios;
                $variable.="&numElectivos=".(int)$numElectivos;
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
            }
        }else{
            $mensaje="Por favor ingrese completamente los campos requeridos";
            $variablesRegistro=array('usuario'=>$this->usuario,
                                        'evento'=>'67',
                                        'descripcion'=>'Error al registrar parametros Plan de estudios Horas -'.$mensaje,
                                        'registro'=>"idProyecto-> ".$idProyecto.", idPlanEstudios->".$idPlanEstudios.", obligatorios->".$numObligatorios.", electivos->".$numElectivos,
                                        'afectado'=>$idProyecto);
           
           $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
           $variable="pagina=admin_consultarPlanEstudiosHoras";
           $variable.="&action=planEstudiosHoras/admin_consultarPlanEstudiosHoras";
           $variable.="&opcion=parametros";
           $variable.="&idProyecto=".$idProyecto;
           $variable.="&idPlanEstudios=".$idPlanEstudios;
           $variable.="&numObligatorios=".(int)$numObligatorios;
           $variable.="&numElectivos=".(int)$numElectivos;
           $variable=$this->cripto->codificar_url($variable,$this->configuracion);
              
           $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
        }
        
        
        
        
    }
    
    /**
     * Función para consultar los parametros de un plan de horas
     * @param type $datos
     * @return type
     */ 
    function consultarParametrosPlan($datos){
            $cadena_sql = $this->sql->cadena_sql("parametros_plan", $datos);
            return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }

    /**
     * Función para buscar en un arreglo de parametros de un plan de estudios el numero de espacios obligatorios
     * @param type $parametrosPlan
     * @return string
     */
    function buscarNumEspaciosObligatorios($parametrosPlan){
        $valor='';
        if(is_array($parametrosPlan)){
            foreach ($parametrosPlan as  $parametro) {
                if($parametro['PAR_CLASIFICACION']==6){
                    $valor=$parametro['PAR_NUMERO'];
                }
            }
        }
        return $valor;
    }
    
    /**
     * Función para buscar en un arreglo de parametros de un plan de estudios el numero de espacios electivos
     * @param <array> $parametrosPlan
     * @return type
     */
    function buscarNumEspaciosElectivos($parametrosPlan){
        $valor='';
        if(is_array($parametrosPlan)){
            foreach ($parametrosPlan as $parametro) {
                if($parametro['PAR_CLASIFICACION']==8){
                    $valor=$parametro['PAR_NUMERO'];
                }
            }
        }
        return $valor;
    }
    
    /**
     * Función para adicionar un parametro
     * @param <array> $datosRegistro
     * @param int $tipoParametro
     * @return type
     */
    function adicionarParametro($datosRegistro,$tipoParametro){
        $datos=$datosRegistro;
        $datos['clasificacion']=$tipoParametro;
        if($tipoParametro==6){
            $datos['numero']=$datosRegistro['numObligatorios'];
        }elseif($tipoParametro==8){
            $datos['numero']=$datosRegistro['numElectivos'];
        }
        $cadena_sql=$this->sql->cadena_sql("adicionar_parametro",$datos);//echo $cadena_sql_adicionar;exit;
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
    /**
     * Función para actualizar un parametro
     * @param type $datosRegistro
     * @param type $tipoParametro
     * @return type
     */
    function actualizarParametro($datosRegistro,$tipoParametro){
        $datos=$datosRegistro;
        $datos['clasificacion']=$tipoParametro;
        if($tipoParametro==6){
            $datos['numero']=$datosRegistro['numObligatorios'];
        }elseif($tipoParametro==8){
            $datos['numero']=$datosRegistro['numElectivos'];
        }
        $cadena_sql=$this->sql->cadena_sql("actualizar_parametro",$datos);//echo $cadena_sql_adicionar;exit;
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
    /**
     * Función para actualizar total de espacios de un plan de estudios
     * @param <array> $datosRegistro
     * @return type
     */
    function actualizarTotalEspacios($datosRegistro){
        $datos=$datosRegistro;
        
        $cadena_sql=$this->sql->cadena_sql("actualizar_totalEspacios",$datos);//echo $cadena_sql_adicionar;exit;
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
    
   
    /**
     * Funcion que permite retornar a la pagina especificada
     * Cuando existe mensaje de error, lo presenta
     * @param <string> $pagina
     * @param <string> $variable
     * @param <array> $variablesRegistro (usuario,evento,descripcion,registro,afectado)
     * @param <string> $mensaje
     * Utiliza el metodo enlaceParaRetornar
     */
    function retornar($pagina,$variable,$variablesRegistro,$mensaje=""){     
        if($mensaje=="")
        {
          
        }
        else
        {
          echo "<script>alert ('".$mensaje."');</script>";
        }
        if($variablesRegistro){
            $this->procedimientos->registrarEvento($variablesRegistro);
        }
        $this->enlaceParaRetornar($pagina, $variable);
    }

    /**
     * Funcion que retorna a una pagina 
     * @param <string> $pagina
     * @param <string> $variable
     */
    function enlaceParaRetornar($pagina,$variable) {
        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;
    }
    
    
}

?>