<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
//@ Clase que permite realizar la inscripcion de un espacio academico por busqueda a un estudiante
class funcion_registroAdicionarTablaHomologaciones extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $ano;
    private $periodo;
    private $datosInscripcion;

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
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
       
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registro_adicionarTablaHomologacion";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
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
     * Funcion que valida los datos para inscribir el registro en la tabla de homologacion
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,cod_proyecto,cod_padre,cod_proyecto_hom,cod_hijo)
     * Utiliza los metodos verificarEspaciosDiferentes, verificarEspacioAcademico,verificarRegistro,
     * verificarPlanEstudios, verificarEspacioPrincipalPlanEstudios,inscribirRegistro, retornar
     */
    function validarRegistro()
    {
        $datosRegistro=array('cod_proyecto'=>$_REQUEST['cod_proyecto'],
                                              'cod_padre'=>$_REQUEST['cod_padre1'],
                                              'cod_proyecto_hom'=>$_REQUEST['cod_proyecto_hom'],
                                              'cod_hijo'=>$_REQUEST['cod_hijo1']);
        //iniciamos las validaciones
        $valida_diferentes = $this->verificarEspaciosDiferentes($datosRegistro);
        $datos_padre = array( 'cod_proyecto'=>"",
                        'cod_espacio'=>$datosRegistro['cod_padre']);
        $valida_padre = $this->verificarEspacioAcademico($datos_padre);
        
        $datos_hijo = array( 'cod_proyecto'=>"",
                        'cod_espacio'=>$datosRegistro['cod_hijo']);
        $valida_hijo = $this->verificarEspacioAcademico($datos_hijo);
          
        $valida_registro = $this->verificarRegistro($datosRegistro);
        $valida_plan_estudio = $this->verificarPlanEstudios($datosRegistro);
        $valida_espacio_ppal = $this->verificarEspacioPrincipalPlanEstudios($datosRegistro);
        
        $mensaje="";
        //revisamos los mensajes de errores
        if($valida_espacio_ppal <>'ok'){
           $mensaje=$valida_espacio_ppal;
        }
        
        if($valida_plan_estudio <>'ok'){
           $mensaje=$valida_plan_estudio;
        }
        
        if($valida_registro <>'ok' ){
           $mensaje=$valida_registro;
        }
        
        if($valida_hijo <>'ok' ){
           $mensaje=$valida_hijo;
        //echo "<br>mensaje".$mensaje; exit;
        }
        
        if($valida_padre <>'ok' ){
           $mensaje=$valida_padre;
       // echo "<br>mensaje".$mensaje; exit;
        }
        
        if($valida_diferentes <>'ok'){
           $mensaje=$valida_diferentes;
        }
        
        //verificamos que las validaciones esten ok para realizar la insercion
        if($valida_diferentes=='ok' && $valida_padre =='ok' && $valida_hijo =='ok' && $valida_registro=='ok' && $valida_plan_estudio=='ok' && $valida_espacio_ppal=='ok' ){
            //echo "inscribir reg"   ;exit;
            $this->inscribirRegistro($datosRegistro);
                
        }else{
           $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'54',
                                              'descripcion'=>'Error al registrar -'.$mensaje,
                                              'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo->".$datosRegistro['cod_hijo'],
                                              'afectado'=>$_REQUEST['cod_proyecto']);
           
           $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
           $variable="pagina=admin_homologaciones";
           $variable.="&opcion=crearTablaHomologacion";
           $variable.="&tipo_hom=normal";
           $variable.="&cod_proyecto=".$datosRegistro['cod_proyecto'];
           $variable.="&cod_padre1=".$datosRegistro['cod_padre'];
           $variable.="&cod_proyecto_hom=".$datosRegistro['cod_proyecto_hom'];
           $variable.="&cod_hijo1=".$datosRegistro['cod_hijo'];
           $variable=$this->cripto->codificar_url($variable,$this->configuracion);
              
           $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
       }
    }


    /**
     * Funcion que valida que el espacio padre ingresado sea diferente al espacio hijo ingresado
     * @param <array> $datosRegistro
     * 
     */
    
    function verificarEspaciosDiferentes($datosRegistro) {
        
        if ($datosRegistro['cod_padre']==$datosRegistro['cod_hijo']) {
                return 'Los espacios académicos deben ser diferentes.';
                exit;
        }else{
             return 'ok';
        } 
       
    }
    
  /**
     * Funcion que valida que un espacio academico exista en un plan de estudios
     * @param <array> $datos(cod_espacio, cod_proyecto)
     * Utiliza el metodo buscarEspacioAcademico
     */
       
    function verificarEspacioAcademico($datos) {
        $registro = $this->buscarEspacioAcademico($datos);
        if (is_array($registro)) {
            $nroRegistros = count($registro);
            if ($nroRegistros > 0) {
                return 'ok';
            } else{
                return $datos['cod_espacio'].': No corresponde a un espacio académico de la Universidad.';
                exit;
            }
        }else{
                return $datos['cod_espacio'].': No corresponde a un espacio académico de la Universidad.';
                exit;
            } 
       //    var_dump($registro);exit;
         
    }
    
  /**
     * Funcion que busca un espacio academico 
     * @param <array> $datos(cod_espacio, cod_proyecto)
     
     */
    public function buscarEspacioAcademico($datos) {
        $cadena_sql = $this->sql->cadena_sql("buscarEspacioAcademico", $datos);
        //echo $cadena_sql ; //exit;
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

    }

  /**
     * Funcion que valida que el registro no exista en el sistema
     * @param <array> $datosRegistro(cod_padre, cod_proyecto,cod_hijo,cod_proyecto_hom)
     * Utiliza el metodo buscarRegistroHomologacion
     */
    
    function verificarRegistro($datosRegistro) {
        $registro = $this->buscarRegistroHomologacion($datosRegistro);
        //var_dump($registro);
        if (is_array($registro)) {
            $nroRegistros = count($registro);
            if ($nroRegistros > 0) {
                return 'El registro de homologación uno a uno, del espacio '.$datosRegistro['cod_hijo'].' con el espacio '.$datosRegistro['cod_padre'].' ya existe en el sistema.';
                exit;
            } else{
                return 'ok';
            } 
        }else{
             return 'ok';
        } 
       
    }
    
  /**
     * Funcion que busca un registro de homologacion
     * @param <array> $datosRegistro(cod_padre, cod_proyecto,cod_hijo,cod_proyecto_hom)
     * 
     */
     public function buscarRegistroHomologacion($datosRegistro) {

        $cadena_sql = $this->sql->cadena_sql("buscarRegistroHomologacion", $datosRegistro);
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

    }

  /**
     * Funcion que valida que el espacio padre no pertenezca al mismo espacio hijo
     * @param <array> $datosRegistro(cod_padre, cod_proyecto,cod_hijo,cod_proyecto_hom)
     * utiliza los metodos buscarPlanEstudios,calcular_interseccion
     */
    
    function verificarPlanEstudios($datosRegistro) {
               //var_dump($registro_padre);exit;
        $datos = array( 'cod_proyecto'=>$datosRegistro['cod_proyecto'],
                        'cod_espacio'=>$datosRegistro['cod_padre']);
        $registro_padre = $this->buscarPlanEstudios($datos);
        
        if (is_array($registro_padre)) {
                if($registro_padre[0]['PEN_NRO']<>null){
                     return 'ok';
                    exit;
                }else{
                    return $datos['cod_espacio'].': No pertenece a ningún plan de estudios del Proyecto Curricular.';
                    exit;
                }
        }else{ //echo "No verifica";exit;
                    return " ".$datos['cod_espacio'].': No pertenece a ningún plan de estudios del Proyecto Curricular.';
                    exit;
        } 
       
    }

  /**
     * Funcion que busca el/los plan(es) de estudio de un espacio académico
     * @param <array> $datos(cod_espacio, cod_proyecto)
     */
    public function buscarPlanEstudios($datos) {

        $cadena_sql = $this->sql->cadena_sql("buscarPlanEstudios", $datos);
        //echo "<br>cadena pE ".$cadena_sql ;exit;
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

    }

   /**
     * Funcion que revisa los valores iguales que se encuentran en dos arreglos
     * @param <array> $registro_padre(pen_nro)
     * @param <array> $registro_hijo(pen_nro)
     * 
     */
    
    function calcular_interseccion($registro_padre,$registro_hijo){
        $cant_padre = count($registro_padre);
        $cant_hijo = count($registro_hijo);
        $k=0;
        $coincidencias=NULL;
        for($i=0;$i<$cant_padre;$i++){
            for($j=0;$j<$cant_hijo;$j++){
                if($registro_padre[$i]['PEN_NRO']==$registro_hijo[$j]['PEN_NRO'])
                    $coincidencias[$k]['PEN_NRO']=$registro_padre[$i]['PEN_NRO'];
            }
        }
        return $coincidencias;
        
    }
  
     /**
     * Funcion que valida que no exista un registro de homologo con un espacio del mismo plan de estudios
     * del espacio hijo ingresado
     * @param <array> $datosRegistro(cod_padre, cod_proyecto,cod_hijo,cod_proyecto_hom)
     *  
     */
    function verificarEspacioPrincipalPlanEstudios($datosRegistro) {
        //var_dump($registro_padre);exit;
        $datos = array( 'cod_proyecto'=>$datosRegistro['cod_proyecto'],
                        'cod_espacio'=>$datosRegistro['cod_padre']);
        $registro_padre = $this->buscarPlanEstudios($datos);

        $datos2 = array( 'cod_proyecto'=>$datosRegistro['cod_proyecto'],
                        'cod_hijo'=>$datosRegistro['cod_hijo']);
        if(is_array($registro_padre)){ 
            $datos2['pe_padre']= $this->convertirArregloString($registro_padre);}
        else {
            $datos2['pe_padre']= "";
        
        }
        
        $registro_homologaciones = $this->buscarHomologacionesPlanEstudios($datos2);
       //var_dump($registro_homologaciones);exit;
        if(is_array($registro_homologaciones)) 
            $coincidencias=count($registro_homologaciones);
        else 
            $coincidencias=0;

        if ($coincidencias>0) {
                return 'El espacio '.$datos2['cod_hijo'].' homologa al espacio '.$registro_homologaciones[0]['COD_ASI_PPAL'].'. Un espacios no puede homologar más de un espacios del mismo plan de estudios.';
                exit;
        }else{
             return 'ok';
        } 
       
    }

     /**
     * Funcion que convierte en cadena separado por comas (,) un arreglo para los planes de estudio
     * @param <array> $arreglo(PEN_NRO)
     *  
     */
    
    function convertirArregloString($arreglo){
        $variable = " ";
        for($i=0;$i<count($arreglo);$i++)
        {
        //echo "variable ".$variable;//exit;
         if($variable<>" ")
            $variable.=",".$arreglo[$i]['PEN_NRO'];
          else 
            $variable = $arreglo[$i]['PEN_NRO'];
        } 
        return $variable;
    }

     /**
     * Funcion que busca registros de homologaciones de un espacio y una carrera 
      * que no se encuentren en otros planes de estudios
     * @param <array> $datos(cod_padre, cod_proyecto,cod_hijo,pe_padre)
     *  
     */

    public function buscarHomologacionesPlanEstudios($datos) {

        $cadena_sql = $this->sql->cadena_sql("buscarHomologacionesPlanEstudios", $datos);
        //echo "<br>cadena HomPlan ".$cadena_sql;exit;
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

    }

     /**
     * Funcion que realiza el registro de homologaciones 
     * @param <array> $datosRegistro(cod_padre, cod_proyecto,cod_hijo,cod_proyecto_hom)
     *  
     */

    function inscribirRegistro($datosRegistro)
    {
       
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_homologaciones";
        $variable.="&opcion=crearTablaHomologacion";
        $variable.="&tipo_hom=normal";
        $variable.="&cod_proyecto=".$_REQUEST['cod_proyecto'];
         var_dump($datosRegistro);exit;
        if(is_array($datosRegistro))
          { 
            $datosRegistro['time']=time();
            $datosRegistro['tipo']=0;
            $resultado_adicionar=$this->adicionarOracle($datosRegistro);            
            if($resultado_adicionar>=1)
                {
                    $mensaje="Registro de homologacion registrado.";
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'54',
                                              'descripcion'=>'Registra en tabla de Homologaciones',
                                              'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo->".$datosRegistro['cod_hijo'].", tipo->".$datosRegistro['tipo'],
                                              'afectado'=>$_REQUEST['cod_proyecto']);
                    
                }
                else
                    {
                        $variable.="&cod_padre1=".$datosRegistro['cod_padre'];
                        $variable.="&cod_proyecto_hom=".$datosRegistro['cod_proyecto_hom'];
                        $variable.="&cod_hijo1=".$datosRegistro['cod_hijo'];

                        $mensaje="En este momento la base de datos O se encuentra ocupada, por favor intente mas tarde.";
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'54',
                                              'descripcion'=>'Conexion Error Oracle al registrar',
                                              'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo->".$datosRegistro['cod_hijo'].", tipo->".$datosRegistro['tipo'],
                                              'afectado'=>$_REQUEST['cod_proyecto']);
                                          
                    }
        }
        else
        {
           $variable.="&cod_padre1=".$datosRegistro['cod_padre'];
           $variable.="&cod_proyecto_hom=".$datosRegistro['cod_proyecto_hom'];
           $variable.="&cod_hijo1=".$datosRegistro['cod_hijo'];
           
            $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'54',
                                              'descripcion'=>'Conexion Error Oracle al registrar',
                                              'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo->".$datosRegistro['cod_hijo'],
                                              'afectado'=>$_REQUEST['cod_proyecto']);
        }
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
    }
   

    /**
     * Funcion que permite retornar a la pagina de administracion de homologaciones
     * Cuando existe mensaje de error, lo presenta
     * @param <string> $pagina
     * @param <string> $variable
     * @param <array> $variablesRegistro (usuario,evento,descripcion,registro,afectado)
     * @param <string> $mensaje
     * Utiliza el metodo enlaceParaRetornar
     */
    function retornar($pagina,$variable,$variablesRegistro,$mensaje=""){     
        //echo "<br>retornar ";exit;
        if($mensaje=="")
        {
          
        }
        else
        {
          echo "<script>alert ('".$mensaje."');</script>";
        }
        $this->procedimientos->registrarEvento($variablesRegistro);
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

     /**
     * Funcion que permite insertar el registro de la homologacion en Oracle
     * @param <array> $datos
     */

    function adicionarOracle($datos) {
        $consecutivo = $this->ultimoIndiceTablaHomologacion();        
        if (!$consecutivo)
            $consecutivo=1;
        else 
            $consecutivo++;
        //echo "consecutivo ".$consecutivo;exit;
        $datos['identificador']= $consecutivo;
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_tabla_homologacion",$datos);
        //echo "<br>cadena adicionar oracle ".$cadena_sql_adicionar;exit;
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
        //echo "<br> consecutivo ".$consecutivo;exit;
        //var_dump($resultado_adicionar);exit;
        if($this->totalAfectados($this->configuracion, $this->accesoOracle)>=1)
            return $consecutivo;
        else
            return 0;
        
    }


     /**
     * Funcion que busca el ultimo identificador registrado de la tabla de Homologaciones 
     * @param 
     *  
     */

    
    public function ultimoIndiceTablaHomologacion() {

        $cadena_sql = $this->sql->cadena_sql("buscarUltimoIndiceTablaHomologacion", "");
        //echo "<br>cadena ultimo indice ".$cadena_sql;//exit;
        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado[0][0];
    }
 

     /**
     * Funcion que realiza las validaciones correspondientes para Homologaciones de tipo 1=Union
     * @param <array> $datosRegistro(cod_padre, cod_proyecto,cod_proyecto_hom, cod_hijo1,cod_hijo2,porc_hijo1
     * porc_hijo2,req_hijo1,req_hijo2)
     * 
     *  Utiliza los metodos verificarEspacioAcademico, validarPorcentaje, verificarPlanEstudios, verificarRegistro
      * verificarRegistroUnion,inscribirRegistroUnion
     */

    function validarRegistroUnion()
    {
        $datosRegistro=array('cod_proyecto'=>$_REQUEST['cod_proyecto'],
                                              'cod_padre'=>$_REQUEST['cod_padre2'],
                                              'cod_proyecto_hom'=>$_REQUEST['cod_proyecto_hom'],
                                              'cod_hijo1'=>$_REQUEST['cod_hijo2'],
                                              'cod_hijo2'=>$_REQUEST['cod_hijo3'],
                                              'porc_hijo1'=>$_REQUEST['porc_hijo2'],
                                              'porc_hijo2'=>$_REQUEST['porc_hijo3'],
                                              'req_hijo1'=>$_REQUEST['req_hijo2'],
                                              'req_hijo2'=>$_REQUEST['req_hijo3']);
        
        //iniciamos las validaciones
        
        $datos_padre=array( 'cod_espacio'=>$_REQUEST['cod_padre2'],
                            'cod_proyecto'=>"");
        $valida_padre = $this->verificarEspacioAcademico($datos_padre);
        
        $datos_hijo1=array( 'cod_espacio'=>$_REQUEST['cod_hijo2'],
                            'cod_proyecto'=>"");
        $valida_hijo1 = $this->verificarEspacioAcademico($datos_hijo1);

        $datos_hijo2=array( 'cod_espacio'=>$_REQUEST['cod_hijo3'],
                            'cod_proyecto'=>"");
        $valida_hijo2 = $this->verificarEspacioAcademico($datos_hijo2);
          
        $valida_porcentaje = $this->validarPorcentaje($datosRegistro);
       
        $valida_plan_estudio = $this->verificarPlanEstudios($datosRegistro);
        
        $datos1=array( 'cod_proyecto'=>$datosRegistro['cod_proyecto'],
                        'cod_padre'=>$datosRegistro['cod_padre'],
                        'cod_proyecto_hom'=>"",
                        'cod_hijo'=>$datosRegistro['cod_hijo1']);
        $valida_diferentes1 = $this->verificarEspaciosDiferentes($datos1);
        $valida_registro_normal_hijo1= $this->verificarRegistro($datos1);
        
        $datos2=array( 'cod_proyecto'=>$datosRegistro['cod_proyecto'],
                        'cod_padre'=>$datosRegistro['cod_padre'],
                        'cod_proyecto_hom'=>"",
                        'cod_hijo'=>$datosRegistro['cod_hijo2']);
        $valida_diferentes2 = $this->verificarEspaciosDiferentes($datos2);
        $valida_registro_normal_hijo2= $this->verificarRegistro($datos2);
        
        $datos3=array(  'cod_padre'=>$datosRegistro['cod_hijo1'],
                        'cod_hijo'=>$datosRegistro['cod_hijo2']);
        $valida_diferentes3 = $this->verificarEspaciosDiferentes($datos3);
        
        $valida_registro_union = $this->verificarRegistroUnion($datosRegistro);
        
       //Revisamos los mensajes para mostrar
        if($valida_registro_union <>'ok'){
           $mensaje=$valida_registro_union;
        }
       
        if($valida_registro_normal_hijo2 <>'ok'){
           $mensaje=$valida_registro_normal_hijo2;
        }
        
        if($valida_registro_normal_hijo1 <>'ok'){
           $mensaje=$valida_registro_normal_hijo1;
        }
        
        if($valida_plan_estudio <>'ok'){
           $mensaje=$valida_plan_estudio;
        }
        
        if($valida_porcentaje <>'ok' ){
           $mensaje=$valida_porcentaje;
       // echo "<br>mensaje".$mensaje; exit;
        }
        
        if($valida_hijo2 <>'ok' ){
           $mensaje=$valida_hijo2;
        //echo "<br>mensaje".$mensaje; exit;
        }
        if($valida_hijo1 <>'ok' ){
           $mensaje=$valida_hijo1;
        //echo "<br>mensaje".$mensaje; exit;
        }
        
        if($valida_padre <>'ok' ){
           $mensaje=$valida_padre;
       // echo "<br>mensaje".$mensaje; exit;
        }

        if($valida_diferentes3 <>'ok' ){
           $mensaje=$valida_diferentes3;
       // echo "<br>mensaje".$mensaje; exit;
        }
        
        if($valida_diferentes2 <>'ok' ){
           $mensaje=$valida_diferentes2;
       // echo "<br>mensaje".$mensaje; exit;
        }

        if($valida_diferentes1 <>'ok' ){
           $mensaje=$valida_diferentes1;
       // echo "<br>mensaje".$mensaje; exit;
        }
        //verificamos que las validaciones esten ok para realizar la insercion
        if($valida_diferentes1=='ok' && $valida_diferentes2=='ok' && $valida_diferentes3=='ok' && $valida_registro_union=='ok' && $valida_registro_normal_hijo2 =='ok' && $valida_registro_normal_hijo1 =='ok' && $valida_plan_estudio=='ok' && $valida_porcentaje=='ok' && $valida_hijo2=='ok'  && $valida_hijo1=='ok' && $valida_padre=='ok'){
            //echo "inscribir reg"   ;exit;
            $this->inscribirRegistroUnion($datosRegistro);
                
        }else{
           $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'54',
                                              'descripcion'=>'Error al registrar -'.$mensaje,
                                              'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo->".$datosRegistro['cod_hijo'],
                                              'afectado'=>$_REQUEST['cod_proyecto']);

           $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
           $variable="pagina=admin_homologaciones";
           $variable.="&opcion=crearTablaHomologacion";
           $variable.="&tipo_hom=union";
           $variable.="&cod_proyecto=".$_REQUEST['cod_proyecto'];
           $variable.="&cod_padre2=".$_REQUEST['cod_padre2'];
           $variable.="&cod_proyecto_hom=".$_REQUEST['cod_proyecto_hom'];
           $variable.="&cod_hijo2=".$_REQUEST['cod_hijo2'];
           $variable.="&cod_hijo3=".$_REQUEST['cod_hijo3'];
           $variable.="&porc_hijo2=".$_REQUEST['porc_hijo2'];
           $variable.="&porc_hijo3=".$_REQUEST['porc_hijo3'];
           $variable.="&req_hijo2=".$_REQUEST['req_hijo2'];
           $variable.="&req_hijo3=".$_REQUEST['req_hijo3'];
           $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        
           $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
       }
    }
    
  function validarRegistroBifurcacion()
    {
     
        $datosRegistro=array('cod_proyecto'=>$_REQUEST['cod_proyecto'],
                                              'cod_padre'=>$_REQUEST['cod_hijo4'],
                                              'cod_proyecto_hom'=>$_REQUEST['cod_proyecto_hom'],
                                              'cod_hijo1'=>$_REQUEST['cod_padre3'],
                                              'cod_hijo2'=>$_REQUEST['cod_padre4'],
                                              'req_hijo1'=>$_REQUEST['req_padre3'],
                                              'req_hijo2'=>$_REQUEST['req_padre4']);                                         
        
        //iniciamos las validaciones
        
        $datos_padre=array( 'cod_espacio'=>$_REQUEST['cod_hijo4'],
                            'cod_proyecto'=>""); 
        $valida_padre = $this->verificarEspacioAcademico($datos_padre);
        
         $datos_hijo1=array( 'cod_espacio'=>$_REQUEST['cod_padre3'],
                            'cod_proyecto'=>"");
        $valida_hijo1 = $this->verificarEspacioAcademico($datos_hijo1); 

        $datos_hijo2=array( 'cod_espacio'=>$_REQUEST['cod_padre4'],
                            'cod_proyecto'=>"");
        $valida_hijo2 = $this->verificarEspacioAcademico($datos_hijo2); 
          
       // $valida_porcentaje = $this->validarPorcentaje($datosRegistro);
 
        $valida_plan_estudio = $this->verificarPlanEstudios($datosRegistro); 
        
        $datos1=array( 'cod_proyecto'=>$datosRegistro['cod_proyecto'],
                        'cod_padre'=>$datosRegistro['cod_padre'],
                        'cod_proyecto_hom'=>"",
                        'cod_hijo'=>$datosRegistro['cod_hijo1']);
        $valida_diferentes1 = $this->verificarEspaciosDiferentes($datos1);
        $valida_registro_normal_hijo1= $this->verificarRegistro($datos1);
        
        $datos2=array( 'cod_proyecto'=>$datosRegistro['cod_proyecto'],
                        'cod_padre'=>$datosRegistro['cod_padre'],
                        'cod_proyecto_hom'=>"",
                        'cod_hijo'=>$datosRegistro['cod_hijo2']);
        $valida_diferentes2 = $this->verificarEspaciosDiferentes($datos2); 
        $valida_registro_normal_hijo2= $this->verificarRegistro($datos2); 
        
        $datos3=array(  'cod_padre'=>$datosRegistro['cod_hijo1'],
                        'cod_hijo'=>$datosRegistro['cod_hijo2']);
        $valida_diferentes3 = $this->verificarEspaciosDiferentes($datos3);
        
        $valida_registro_union = $this->verificarRegistroUnion($datosRegistro); 
        
       //Revisamos los mensajes para mostrar
        if($valida_registro_union <>'ok'){
           $mensaje=$valida_registro_union;
        }
       
        if($valida_registro_normal_hijo2 <>'ok'){
           $mensaje=$valida_registro_normal_hijo2;
        }
        
        if($valida_registro_normal_hijo1 <>'ok'){
           $mensaje=$valida_registro_normal_hijo1;
        }
        
        if($valida_plan_estudio <>'ok'){
           $mensaje=$valida_plan_estudio;
        }
        
//        if($valida_porcentaje <>'ok' ){
//           $mensaje=$valida_porcentaje;
//       // echo "<br>mensaje".$mensaje; exit;
//        }
        
        if($valida_hijo2 <>'ok' ){
           $mensaje=$valida_hijo2;
        //echo "<br>mensaje".$mensaje; exit;
        }
        if($valida_hijo1 <>'ok' ){
           $mensaje=$valida_hijo1;
        //echo "<br>mensaje".$mensaje; exit;
        }
        
        if($valida_padre <>'ok' ){
           $mensaje=$valida_padre;
       // echo "<br>mensaje".$mensaje; exit;
        }

        if($valida_diferentes3 <>'ok' ){
           $mensaje=$valida_diferentes3;
       // echo "<br>mensaje".$mensaje; exit;
        }
        
        if($valida_diferentes2 <>'ok' ){
           $mensaje=$valida_diferentes2;
       // echo "<br>mensaje".$mensaje; exit;
        }

        if($valida_diferentes1 <>'ok' ){
           $mensaje=$valida_diferentes1;
       // echo "<br>mensaje".$mensaje; exit;
        }
        //verificamos que las validaciones esten ok para realizar la insercion
        if($valida_diferentes1=='ok' && $valida_diferentes2=='ok' && $valida_diferentes3=='ok' && $valida_registro_union=='ok' && $valida_registro_normal_hijo2 =='ok' && $valida_registro_normal_hijo1 =='ok' && $valida_plan_estudio=='ok' && $valida_hijo2=='ok'  && $valida_hijo1=='ok' && $valida_padre=='ok'){
            //echo "inscribir reg"   ;exit;
            $this->inscribirRegistroBifurcacion($datosRegistro);
                
        }else{
           $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'54',
                                              'descripcion'=>'Error al registrar -'.$mensaje,
                                              'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo->".$datosRegistro['cod_hijo'],
                                              'afectado'=>$_REQUEST['cod_proyecto']);                                          //var_dump($variablesRegistro);Exit;

           $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
           $variable="pagina=admin_homologaciones";
           $variable.="&opcion=crearTablaHomologacion";
           $variable.="&tipo_hom=union";
           $variable.="&cod_proyecto=".$_REQUEST['cod_proyecto'];
           $variable.="&cod_padre2=".$_REQUEST['cod_hijo4'];
           $variable.="&cod_proyecto_hom=".$_REQUEST['cod_proyecto_hom'];
           $variable.="&cod_hijo2=".$_REQUEST['cod_padre3'];
           $variable.="&cod_hijo3=".$_REQUEST['cod_padre4'];
           $variable.="&req_hijo2=".$_REQUEST['req_padre3'];
           $variable.="&req_hijo3=".$_REQUEST['req_padre4'];
           $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        
           $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
       }
    }

     /**
     * Funcion que verifica que la suma de los porcentajes ingresados sea 100
     * @param <array> $datosRegistro(cod_padre, cod_proyecto,cod_proyecto_hom, cod_hijo1,cod_hijo2,porc_hijo1
     * porc_hijo2,req_hijo1,req_hijo2)
     * 
     */
     function validarPorcentaje($datosRegistro)
    {
      $porc_total = $datosRegistro['porc_hijo1']+$datosRegistro['porc_hijo2'];
        
        if ($porc_total==100) {
                return 'ok';
             
        }else{
            //var_dump($porc_total);exit;
             return 'La suma de los porcentajes debe ser 100%.';
             exit;
        }     
     }

    /**
     * Funcion que verifica que el registro de la union ya no se encuentre en el sistema
     * @param <array> $datosRegistro(cod_padre, cod_proyecto,cod_proyecto_hom, cod_hijo1,cod_hijo2,porc_hijo1
     * porc_hijo2,req_hijo1,req_hijo2)
     * Utiliza el metodo buscarRegistroUnion
     * 
     */
    function verificarRegistroUnion($datosRegistro) {
        $registro = $this->buscarRegistroUnion($datosRegistro);
        //var_dump($registro);exit;
        if (is_array($registro)) {
            if($registro[0]['FEC_REG']==$registro[1]['FEC_REG'])
            {
                return 'El registro de homologación de unión ya existe en el sistema.';
                exit;
            } else{
                return 'ok';
            } 
        }else{
             return 'ok';
        } 
       
    }

   /**
     * Funcion que busca un registro de homologaciones union 
     * @param <array> $datosRegistro(cod_padre, cod_proyecto,cod_proyecto_hom, cod_hijo1,cod_hijo2,porc_hijo1
     * porc_hijo2,req_hijo1,req_hijo2)
     */
   public function buscarRegistroUnion($datosRegistro) {

        $cadena_sql = $this->sql->cadena_sql("buscarRegistroUnion", $datosRegistro);
        //echo "<br>sql union ".$cadena_sql; exit;
        return $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

    }

   /**
     * Funcion que realiza las verificaciones para registrar en las tablas de homologacion
     * @param <array> $datosRegistro(cod_padre, cod_proyecto,cod_proyecto_hom, cod_hijo1,cod_hijo2,porc_hijo1
     * porc_hijo2,req_hijo1,req_hijo2)
     */
  function inscribirRegistroUnion($datosRegistro)
    {

        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_homologaciones";
        $variable.="&opcion=crearTablaHomologacion";
        $variable.="&tipo_hom=union";
        $variable.="&cod_proyecto=".$_REQUEST['cod_proyecto'];
        
        //include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        //$this->cripto=new encriptar();
        //var_dump($datosRegistro);exit;
        if(is_array($datosRegistro))
          {
            $time = time();
            
            $datos_hijo1=array('time'=>$time,
                                'tipo'=>1,
                                'cod_proyecto'=>$datosRegistro['cod_proyecto'],
                                'cod_padre'=>$datosRegistro['cod_padre'],
                                'cod_proyecto_hom'=>"0",
                                'cod_hijo'=>$datosRegistro['cod_hijo1'],
                                'porc_hijo'=>$datosRegistro['porc_hijo1'],
                                'req_hijo'=>$datosRegistro['req_hijo1'],
                                'annio'=>$this->ano,
                                'periodo'=>$this->periodo);
          
           
            $res_adicionar_hijo1=$this->adicionarOracle($datos_hijo1);
            
            $datos_hijo2=array('time'=>$time,
                                'tipo'=>1,
                                'cod_proyecto'=>$datosRegistro['cod_proyecto'],
                                'cod_padre'=>$datosRegistro['cod_padre'],
                                'cod_proyecto_hom'=>"0",
                                'cod_hijo'=>$datosRegistro['cod_hijo2'],
                                'porc_hijo'=>$datosRegistro['porc_hijo2'],
                                'req_hijo'=>$datosRegistro['req_hijo2'],
                                'annio'=>$this->ano,
                                'periodo'=>$this->periodo);
          
           
            $res_adicionar_hijo2=$this->adicionarOracle($datos_hijo2);
            
            if($res_adicionar_hijo1>=1 && $res_adicionar_hijo2>=1){
                $datos_hijo1['identificador']=$res_adicionar_hijo1;
                $res_adicionar_porc_hijo1=$this->adicionarOraclePorcentajes($datos_hijo1);

                $datos_hijo2['identificador']=$res_adicionar_hijo2;
                $res_adicionar_porc_hijo2=$this->adicionarOraclePorcentajes($datos_hijo2);

                //verificamos que se hallan registrado en tabla de homologacion, para registrar los porcentajes
                if($res_adicionar_porc_hijo1>=1 && $res_adicionar_porc_hijo2>=1)
                    {
                        $mensaje="Registro de homologacion registrado.";
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'54',
                                                'descripcion'=>'Registra en tabla Homologaciones (Union)',
                                                'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo1->".$datosRegistro['cod_hijo1'].", porcentaje_hijo1->".$datosRegistro['porc_hijo1'].", id_hijo1->".$datos_hijo1['identificador'].", cod_hijo2->".$datosRegistro['cod_hijo2'].", porcentaje_hijo2->".$datosRegistro['porc_hijo2'].", id_hijo2->".$datos_hijo2['identificador'],
                                                'afectado'=>$_REQUEST['cod_proyecto']);
                    }
                    else
                        {
                            $this->eliminarHomologacionOracle($datos_hijo1);
                            $this->eliminarHomologacionOracle($datos_hijo2);
                            if($res_adicionar_porc_hijo1>=1){
                                $this->eliminarPorcentajeHomologacionOracle($datos_hijo1);
                            }
                            if($res_adicionar_porc_hijo2>=1){
                                $this->eliminarPorcentajeHomologacionOracle($datos_hijo2);
                            }
                            $mensaje="En este momento la base de datos O se encuentra ocupada, por favor intente mas tarde.";
                            $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'54',
                                                'descripcion'=>'Conexion Error Oracle al registrar Homologacion Union',
                                                'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo1->".$datosRegistro['cod_hijo1'].", porcentaje_hijo1->".$datosRegistro['porc_hijo1'].", id_hijo1->".$datos_hijo1['identificador'].", cod_hijo2->".$datosRegistro['cod_hijo2'].", porcentaje_hijo2->".$datosRegistro['porc_hijo2'].", id_hijo2->".$datos_hijo2['identificador'],
                                                'afectado'=>$_REQUEST['cod_proyecto']);

                        }
            }
            else{
                if($res_adicionar_hijo1>=1){
                        $this->eliminarHomologacionOracle($datos_hijo1);
                }
                if($res_adicionar_hijo2>=1){
                        $this->eliminarHomologacionOracle($datos_hijo2);
                }
            }
        }
        else
        {
           $variable.="&cod_padre2=".$datosRegistro['cod_padre2'];
           $variable.="&cod_proyecto_hom=".$datosRegistro['cod_proyecto_hom'];
           $variable.="&cod_hijo2=".$datosRegistro['cod_hijo1'];
           $variable.="&cod_hijo3=".$datosRegistro['cod_hijo2'];
           $variable.="&porc_hijo2=".$datosRegistro['porc_hijo1'];
           $variable.="&porc_hijo3=".$datosRegistro['porc_hijo2'];
           $variable.="&req_hijo2=".$datosRegistro['req_hijo1'];
           $variable.="&req_hijo3=".$datosRegistro['req_hijo2'];
           
            $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'54',
                                              'descripcion'=>'Conexion Error Oracle al registrar Homologacion Union',
                                              'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo1->".$datosRegistro['cod_hijo1'].", porcentaje_hijo1->".$datosRegistro['porc_hijo1'].", id_hijo1->".$datos_hijo1['identificador'].", cod_hijo2->".$datosRegistro['cod_hijo2'].", porcentaje_hijo2->".$datosRegistro['porc_hijo2'].", id_hijo2->".$datos_hijo2['identificador'],
                                              'afectado'=>$_REQUEST['cod_proyecto']);
        }
        
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
    }
   /**
     * Funcion que realiza las verificaciones para registrar en las tablas de homologacion
     * @param <array> $datosRegistro(cod_padre, cod_proyecto,cod_proyecto_hom, cod_hijo1,cod_hijo2,porc_hijo1
     * porc_hijo2,req_hijo1,req_hijo2)
     */
  function inscribirRegistroBifurcacion($datosRegistro)
    {

        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_homologaciones";
        $variable.="&opcion=crearTablaHomologacion";
        $variable.="&tipo_hom=union";
        $variable.="&cod_proyecto=".$_REQUEST['cod_proyecto'];

        if(is_array($datosRegistro))
          {
            $time = time();
            
            $datos_hijo1=array('time'=>$time,
                                'tipo'=>2,
                                'cod_proyecto'=>$datosRegistro['cod_proyecto'],
                                'cod_padre'=>$datosRegistro['cod_hijo2'],
                                'cod_proyecto_hom'=>"0",
                                'cod_hijo'=>$datosRegistro['cod_padre'],
                                'req_hijo'=>$datosRegistro['req_hijo2'],
                                'annio'=>$this->ano,
                                'periodo'=>$this->periodo);
          
           
            $res_adicionar_hijo1=$this->adicionarOracle($datos_hijo1);
            
            $datos_hijo2=array('time'=>$time,
                                'tipo'=>2,
                                'cod_proyecto'=>$datosRegistro['cod_proyecto'],
                                'cod_padre'=>$datosRegistro['cod_hijo1'],
                                'cod_proyecto_hom'=>"0",
                                'cod_hijo'=>$datosRegistro['cod_padre'],
                                'req_hijo'=>$datosRegistro['req_hijo1'],
                                'annio'=>$this->ano,
                                'periodo'=>$this->periodo);
          
           
            $res_adicionar_hijo2=$this->adicionarOracle($datos_hijo2);
            
            if($res_adicionar_hijo1>=1 && $res_adicionar_hijo2>=1){
               
                $datos_hijo1['identificador']=$res_adicionar_hijo1; 
                $datos_hijo2['identificador']=$res_adicionar_hijo2;                

            }
            else{
                if($res_adicionar_hijo1>=1){
                        $this->eliminarHomologacionOracle($datos_hijo1);
                }
                if($res_adicionar_hijo2>=1){
                        $this->eliminarHomologacionOracle($datos_hijo2);
                }
            }
        }
        else
        {       
           $variable.="&cod_padre2=".$datosRegistro['cod_padre2'];
           $variable.="&cod_proyecto_hom=".$datosRegistro['cod_proyecto_hom'];
           $variable.="&cod_hijo2=".$datosRegistro['cod_hijo1'];
           $variable.="&cod_hijo3=".$datosRegistro['cod_hijo2'];
           $variable.="&porc_hijo2=".$datosRegistro['porc_hijo1'];
           $variable.="&porc_hijo3=".$datosRegistro['porc_hijo2'];
           $variable.="&req_hijo2=".$datosRegistro['req_hijo1'];
           $variable.="&req_hijo3=".$datosRegistro['req_hijo2'];
           
           $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'54',
                                              'descripcion'=>'Conexion Error Oracle al registrar Homologacion Bifurcación',
                                              'registro'=>"cod_proyecto-> ".$datosRegistro['cod_proyecto'].", cod_padre->".$datosRegistro['cod_padre'].", cod_proyecto_hom->".$datosRegistro['cod_proyecto_hom'].", cod_hijo1->".$datosRegistro['cod_hijo1'].", id_hijo1->".$datos_hijo1['identificador'].", cod_hijo2->".$datosRegistro['cod_hijo2'].", id_hijo2->".$datos_hijo2['identificador'],
                                              'afectado'=>$_REQUEST['cod_proyecto']);                                       
        }
        $mensaje="Registro Exitoso";
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);  
        $this->retornar($pagina,$variable,(isset($variablesRegistro)),$mensaje);       
    }

   /**
     * Funcion que inserta el registro de homologacion de Union en la base de datos oracle en la tabla de porcentajes
     * @param <array> $datos(cod_padre, cod_proyecto,cod_proyecto_hom, cod_hijo1,cod_hijo2,porc_hijo1
     * porc_hijo2,req_hijo1,req_hijo2)
     */    
    function adicionarOraclePorcentajes($datos) {
        //$datos['identificador']= $consecutivo;
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_tabla_porcentajes",$datos);
        //echo "<br>cadena adicionar UNION oracle ".$cadena_sql_adicionar;//exit;
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
        //var_dump($resultado_adicionar);exit;
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }

   /**
     * Funcion que eliminar registro en la tabla de homologaciones, cuando se presenta un error
     * @param <array> $datos(cod_padre, cod_proyecto,cod_proyecto_hom, cod_hijo, identificador)
     */    
    function eliminarHomologacionOracle($datos) {
        $cadena_sql_eliminar=$this->sql->cadena_sql("eliminar_tabla_homologacion",$datos);
        //echo "<br>cadena eliminar oracle ".$cadena_sql_eliminar;//exit;
        $resultado_eliminar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_eliminar,"");
        //var_dump($resultado_adicionar);exit;
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }

   /**
     * Funcion que eliminar registro en la tabla de porcentajes de homologaciones, cuando se presenta un error
     * @param <array> $datos(cod_padre, cod_proyecto,cod_proyecto_hom, cod_hijo, identificador, time)
     */    
    function eliminarPorcentajeHomologacionOracle($datos) {
        $cadena_sql_eliminar=$this->sql->cadena_sql("eliminar_tabla_porcentaje_homologacion",$datos);
        //echo "<br>cadena eliminar oracle ".$cadena_sql_eliminar;//exit;
        $resultado_eliminar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_eliminar,"");
        //var_dump($resultado_adicionar);exit;
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    
function deshabilitar(){    
        
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=".$_REQUEST['retorno'];
        $variable.="&opcion=".$_REQUEST['opcionRetorno'];
        $variable.="&tipo_hom=normal";
        $variable.='&cod_proyecto='.$_REQUEST['cod_proyecto'];
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);  
        
    $datos=array('estado'=>$_REQUEST['estado'],
                  'homologo'=>$_REQUEST['codHomologa'],                  
                  'principal'=>$_REQUEST['codPpal']);                  
    $cadena_sql_eliminar=$this->sql->cadena_sql("deshabilitar_homologacion",$datos);
    $resultado_eliminar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_eliminar,"");
    if($_REQUEST['estado']=='A'){
    $mensaje="Se ha activado la Homologación ".$_REQUEST['codHomologa']."=>".$_REQUEST['codPpal'];
    }else{$mensaje="Se ha inactivado la Homologación ".$_REQUEST['codHomologa']."=>".$_REQUEST['codPpal'];}
    $variablesRegistro=array('usuario'=>$this->usuario,
                        'evento'=>'54',
                        'descripcion'=>'Registra en tabla Homologaciones (Union)',
                        'registro'=>"homologo-> ".$_REQUEST['codHomologa'].", principal->".$_REQUEST['codPpal'],
                        'afectado'=>$_REQUEST['cod_proyecto']);
    $this->retornar($pagina, $variable,$variablesRegistro,$mensaje); 
    
}

}

?>