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
class funcion_registroHomologacionPendientes extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $ano;
    private $periodo;
    
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/ejecutarHomologaciones.class.php");

        $this->configuracion=$configuracion;
        $this->validacion=new validarInscripcion();
        $this->procedimientos=new procedimientos();
        $this->homologaciones=new homologaciones();
        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"administrador");
       
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="admin_homologacionesPendientes";
        //$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->usuario='79709508';
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
     * Funcion que valida el tipo de homologacion solicitada, por estudiantes o por cohorte
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,cod_proyecto,cod_estudiante,cod_estudianteAnt, tipo_homologacion)
     * Utiliza los metodos validarRegistroEstudiantes, validarRegistroCohorte
     */
    function validarRegistroHomPendientes()
    { 
        $tipo_homologacion= isset($_REQUEST['tipo_homologacion'])?$_REQUEST['tipo_homologacion']:'';       
        switch ($tipo_homologacion) {
            case 'estudiantes':
       
                $this->validarRegistroEstudiantes(); 

                break;
            
            default:
                break;
        }
      
    }

    /**
     * Funcion que valida los datos para realizar el registro de homologacion por ciclos a un grupo de estudiantes
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,cod_proyecto,cod_estudiante,cod_estudianteAnt)
     * Utiliza los metodos consultarTablaHomologaciones, verificarTablaHomologaciones, buscarHomologacionTipo
     * buscarUniones, buscarBifurcaciones, verificarEstudiante,consultarNotasEstudiante,consultarDatosEstudiante
     * buscarAprobadosUnoUno, buscarAprobadosUnion, buscarAprobadosBifurcacion, homologarUnoUno,homologarUnion
     * homologarBifurcacion, mostrarResultadoEspaciosHomologados
     */
    function validarRegistroEstudiantes(){
        $k=0;
        $cantidadEstudiantesHomologados=0;
        $cod_proyecto = $_REQUEST['cod_proyecto'];   
        if ($cod_proyecto > 0){
            //iniciamos las validaciones
            $estudiantes = $this->consultarEstudiantesParaHomologacion($cod_proyecto);
        }
        $cantidadEstudiantes=count($estudiantes);        
        $tabla_homologaciones = $this->consultarTablaHomologaciones($cod_proyecto); 
        $validar_tabla_homologaciones = $this->verificarTablaHomologaciones($tabla_homologaciones);     
        $notaAprobatoria=0;
        //verifica si existe tabla de homologaciones
        if($validar_tabla_homologaciones =='ok'){
            $espaciosProyecto = $this->consultarEspaciosProyecto($cod_proyecto);           
                                
                if($cantidadEstudiantes>0){
                    //recorre los estudiantes a homologar
                    $l=0;
                    $resultadoImplicitas='';
                    $resultadoUnoAUno='';
                    $resultadoUnion='';
                    $resultadoBifurcacion='';
                        foreach ($estudiantes as $key => $datos_estudiante) {
                            $cod_estudiante = $datos_estudiante['COD_ESTUDIANTE'];
                            //echo "<br><br>".$cod_estudiante;exit;
                            $validar_estudiante = $this->verificarEstudiante($cod_estudiante,$cod_proyecto);                            
                            //verifica que los codigo de estudiante sean validos
                            if($validar_estudiante=='ok')
                            {
                                $datosEstudiante= $this->consultarDatosEstudiante($cod_estudiante,$cod_proyecto);                                
                                $notasActuales = $this->consultarNotasEstudiante($cod_estudiante,$cod_proyecto);                        
                                //consulta espacios del plan de estudios del estudiante
                                $espaciosPlanEstudio = $this->buscarEspaciosPlan((isset($datosEstudiante[0]['PENSUM'])?$datosEstudiante[0]['PENSUM']:''),$espaciosProyecto);                                       
                                
                                if(is_array($espaciosPlanEstudio)){
                                        $notasActuales = $this->consultarNotasEstudiante($cod_estudiante,$cod_proyecto);                                
                                        $notasAnteriores = $this->restarEspaciosPlan($notasActuales,$espaciosPlanEstudio);
                                        $resultadoHomologaciones=$this->homologaciones->ejecutarHomologacion($datosEstudiante,$notasAnteriores,$notasActuales,$espaciosPlanEstudio, $tabla_homologaciones,'unoauno',$notaAprobatoria[0]['NOTA_APROBATORIA']);
                                if(is_array($resultadoHomologaciones['UNOAUNO']))
                                {
                                    $resultadoUnoAUno[$l]=$this->ejecutarUnoAUno($datosEstudiante[0],$resultadoHomologaciones['UNOAUNO']);
                                   
                                }
                                $notasActuales = $this->consultarNotasEstudiante($cod_estudiante,$cod_proyecto);                                
                                        $notasAnteriores = $this->restarEspaciosPlan($notasActuales,$espaciosPlanEstudio);
                                        $resultadoHomologaciones=$this->homologaciones->ejecutarHomologacion($datosEstudiante,$notasAnteriores,$notasActuales,$espaciosPlanEstudio, $tabla_homologaciones,'union',$notaAprobatoria[0]['NOTA_APROBATORIA']);
                                if(is_array($resultadoHomologaciones['UNION']))
                                {
                                    $resultadoUnion[$l]=$this->ejecutarUnion($datosEstudiante[0],$resultadoHomologaciones['UNION']);
                                }
                                
                                $notasActuales = $this->consultarNotasEstudiante($cod_estudiante,$cod_proyecto);                                
                                        $notasAnteriores = $this->restarEspaciosPlan($notasActuales,$espaciosPlanEstudio);
                                        $resultadoHomologaciones=$this->homologaciones->ejecutarHomologacion($datosEstudiante,$notasAnteriores,$notasActuales,$espaciosPlanEstudio, $tabla_homologaciones,'bifurcacion',$notaAprobatoria[0]['NOTA_APROBATORIA']);
                                if(is_array($resultadoHomologaciones['BIFURCACION']))
                                {
                                    $resultadoBifurcacion[$l]=$this->ejecutarBifurcacion($datosEstudiante[0],$resultadoHomologaciones['BIFURCACION']);
                                  
                                }
                                if((isset($resultadoImplicitas[$l])?$resultadoImplicitas[$l]:'') || (isset($resultadoUnoAUno[$l])?$resultadoUnoAUno[$l]:'') || (isset($resultadoUnion[$l])?$resultadoUnion[$l]:'') || (isset($resultadoBifurcacion[$l])?$resultadoBifurcacion[$l]:'')){

                                    $estudianteHomologado = $this->verificarEstudianteHomologado((isset($resultadoImplicitas[$l])?$resultadoImplicitas[$l]:''),(isset($resultadoUnoAUno[$l])?$resultadoUnoAUno[$l]:''),(isset($resultadoUnion[$l])?$resultadoUnion[$l]:''),(isset($resultadoBifurcacion[$l])?$resultadoBifurcacion[$l]:''));
                                    if($estudianteHomologado=='ok'){
                                        $cantidadEstudiantesHomologados++; 
                                    }
                                }
                                
                                 unset ($resultadoHomologaciones);
                                }
                                $l++;
                                echo "<br>Estudiante ".$l." procesado - ".$cod_estudiante." ...";
                            }else{
                                $estudiantesNoValidos[$k]['cod_estudiante']=$cod_estudiante;
                                $k++;
                            }
                        }
                         $this->mostrarReporteResultados($resultadoImplicitas,$resultadoUnoAUno,$resultadoUnion,$resultadoBifurcacion,$cantidadEstudiantes,$cantidadEstudiantesHomologados);
                }else{
                    $variablesRegistro='';
                    $mensaje = "No ingreso estudiante para homologar";
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=admin_homologacionesPendientesPorProyecto";
                    $variable.="&opcion=realizarHomologacionProyectoPendientes";
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                    $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

            }
            
        }else{
            $variablesRegistro='';
            $mensaje = $validar_tabla_homologaciones;
            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
            $variable="pagina=admin_homologacionesPendientesPorProyecto";
            $variable.="&opcion=realizarHomologacionProyectoPendientes";
            $variable=$this->cripto->codificar_url($variable,$this->configuracion);
            $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);

         }
       
    }

    /**
     * Funcion que registra homologaciones implicitas
     * @param type $datos_estudiante
     * @param type $homologaciones
     */
    function ejecutarImplicitas($datos_estudiante,$homologaciones){       
        foreach ($homologaciones as $homologacion) {
            $datosRegistro = array('NOT_CRA_COD'=>$datos_estudiante['CRA_COD'],
                                            'NOT_EST_COD'=>$datos_estudiante['COD_ESTUDIANTE'],
                                            'NOT_ASI_COD'=>$homologacion['COD_ESPACIO'],
                                            'NOT_ANO'=>$homologacion['ANO_NOTA'],
                                            'NOT_PER'=>$homologacion['PERIODO_NOTA'],
                                            'NOT_SEM'=>$homologacion['NIVEL_NOTA'],
                                            'NOT_NOTA'=>$homologacion['NOTA'],
                                            'NOT_GR'=>$homologacion['GRUPO'],
                                            'NOT_OBS'=>17,
                                            'NOT_FECHA'=> date('d/m/Y'),
                                            'NOT_EST_REG'=>'A',
                                            'NOT_CRED'=>$homologacion['CREDITOS'],
                                            'NOT_NRO_HT'=>$homologacion['HTD'],
                                            'NOT_NRO_HP'=>$homologacion['HTC'],
                                            'NOT_NRO_AUT'=>$homologacion['HTA'],
                                            'NOT_CEA_COD'=>$homologacion['CLASIFICACION'],
                                            'NOT_ASI_COD_INS'=>$homologacion['COD_ESPACIO'],
                                            'NOT_ASI_HOMOLOGA'=>$homologacion['COD_ESPACIO'],
                                            'NOT_EST_HOMOLOGA'=>'S'  );
            $ingresado=$this->adicionarOracleHomologacion($datosRegistro);
            
            if($ingresado>0)
            {
                $homologado=$this->actualizarOracleHomologacion($datosRegistro);
                if($homologado>0)
                    {
                        $variablesRegistro2=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Actualiza estado registro Homologacion Pendiente',
                                                    'registro'=>" cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                    'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);

                    }else
                    {
                        $variablesRegistro2=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Conexion Error Oracle al actualiza estado registro Homologacion Pendiente',
                                                    'registro'=>" cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                    'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                    }
                $this->procedimientos->registrarEvento($variablesRegistro2);
                $notas[]=array('HOMOLOGADO'=>'S',
                                'COD_ESTUDIANTE'=>$datos_estudiante['COD_ESTUDIANTE'],
                                'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                'COD_PPAL'=>$homologacion['COD_ESPACIO'],
                                'NOTA'=>$datosRegistro['NOT_NOTA'],
                                'PERIODO'=>$datosRegistro['NOT_ANO']."-".$datosRegistro['NOT_PER']);
                $variablesRegistro=array('usuario'=>$this->usuario,
                                            'evento'=>'50',
                                            'descripcion'=>'Registra Homologacion',
                                            'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                            'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
            }else
                {
                    $notas[]=array('HOMOLOGADO'=>'N',
                                    'COD_ESTUDIANTE'=>$datos_estudiante['COD_ESTUDIANTE'],
                                    'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                    'COD_PPAL'=>$homologacion['COD_ESPACIO']);
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'50',
                                                'descripcion'=>'Conexion Error Oracle al ejecutar Homologacion',
                                                'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                 'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                }
            $this->procedimientos->registrarEvento($variablesRegistro);
        }
        return $notas;
               
       }

    /**
     * Funcion que registra homologaciones uno a uno
     * @param type $datos_estudiante
     * @param type $homologaciones
     */
    function ejecutarUnoAUno($datos_estudiante,$homologaciones){
        foreach ($homologaciones as $homologacion)
        {   
            switch($homologacion['NOTA']['OBSERVACION']){
                case 19:
                    $observacion = 22;
                    break;
                case 20:
                    $observacion = 23;
                    break;
                default:
                    $observacion = 17;
                    break;
            }
            $datosRegistro = array('NOT_CRA_COD'=>$datos_estudiante['CRA_COD'],
                                            'NOT_EST_COD'=>$datos_estudiante['COD_ESTUDIANTE'],
                                            'NOT_ASI_COD'=>$homologacion['ESPACIO_PPAL'],
                                            'NOT_ANO'=>$homologacion['NOTA']['ANO_NOTA'],
                                            'NOT_PER'=>$homologacion['NOTA']['PERIODO_NOTA'],
                                            'NOT_SEM'=>$homologacion['NOTA']['NIVEL_NOTA'],
                                            'NOT_NOTA'=>$homologacion['NOTA']['NOTA'],
                                            'NOT_GR'=>$homologacion['NOTA']['GRUPO'],
                                            'NOT_OBS'=>$observacion,
                                            'NOT_FECHA'=> date('d/m/Y'),
                                            'NOT_EST_REG'=>'A',
                                            'NOT_CRED'=>$homologacion['NOTA']['CREDITOS'],
                                            'NOT_NRO_HT'=>$homologacion['NOTA']['HTD'],
                                            'NOT_NRO_HP'=>$homologacion['NOTA']['HTC'],
                                            'NOT_NRO_AUT'=>$homologacion['NOTA']['HTA'],
                                            'NOT_CEA_COD'=>$homologacion['NOTA']['CLASIFICACION'],
                                            'NOT_ASI_COD_INS'=>$homologacion['ESPACIO_HOM'],
                                            'NOT_ASI_HOMOLOGA'=>$homologacion['ESPACIO_HOM'],
                                            'NOT_EST_HOMOLOGA'=>'S'  );                                        
            $ingresado=$this->adicionarOracleHomologacion($datosRegistro);
            
            if($ingresado>0)
            {
                $homologado=$this->actualizarOracleHomologacion($datosRegistro);
                if($homologado>0)
                    {
                        $variablesRegistro2=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Actualiza estado registro Homologacion Pendiente',
                                                    'registro'=>" cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                    'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);

                    }else
                    {
                        $variablesRegistro2=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Conexion Error Oracle al actualiza estado registro Homologacion Pendiente',
                                                    'registro'=>" cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                    'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                    }
                        $this->procedimientos->registrarEvento($variablesRegistro2);
                        $notas[]=array('HOMOLOGADO'=>'S',
                                'COD_ESTUDIANTE'=>$datos_estudiante['COD_ESTUDIANTE'],
                                'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                'COD_PPAL'=>$homologacion['ESPACIO_PPAL'],
                                'NOTA'=>$datosRegistro['NOT_NOTA'],
                                'PERIODO'=>$datosRegistro['NOT_ANO']."-".$datosRegistro['NOT_PER']
                                );
                $variablesRegistro=array('usuario'=>$this->usuario,
                                            'evento'=>'50',
                                            'descripcion'=>'Registra Homologacion',
                                            'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                            'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
            }else
                {
                    $notas[]=array('HOMOLOGADO'=>'N',
                                    'COD_ESTUDIANTE'=>$datos_estudiante['COD_ESTUDIANTE'],
                                    'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                    'COD_PPAL'=>$homologacion['ESPACIO_PPAL']);
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'50',
                                                'descripcion'=>'Conexion Error Oracle al ejecutar Homologacion',
                                                'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                 'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                }
            $this->procedimientos->registrarEvento($variablesRegistro);
        }
        return $notas;
    }
    
    /**
     * Funcion que registra homologaciones uno a uno
     * @param type $datos_estudiante
     * @param type $homologaciones
     */
    function ejecutarUnion($datos_estudiante,$homologaciones){
        foreach ($homologaciones as $homologacion)
        {
            switch($homologacion['NOTA']['OBSERVACION']){
                case 19:
                    $observacion = 22;
                    break;
                case 20:
                    $observacion = 23;
                    break;
                default:
                    $observacion = 17;
                    break;
            }
            $datosRegistro = array('NOT_CRA_COD'=>$datos_estudiante['CRA_COD'],
                                            'NOT_EST_COD'=>$datos_estudiante['COD_ESTUDIANTE'],
                                            'NOT_ASI_COD'=>$homologacion['ESPACIO_PPAL'],
                                            'NOT_ANO'=>$homologacion['NOTA']['ANO_NOTA'],
                                            'NOT_PER'=>$homologacion['NOTA']['PERIODO_NOTA'],
                                            'NOT_SEM'=>$homologacion['NOTA']['NIVEL_NOTA'],
                                            'NOT_NOTA'=>$homologacion['NOTA']['NOTA'],
                                            'NOT_GR'=>0,
                                            'NOT_OBS'=>$observacion,
                                            'NOT_FECHA'=> date('d/m/Y'),
                                            'NOT_EST_REG'=>'A',
                                            'NOT_CRED'=>$homologacion['NOTA']['CREDITOS'],
                                            'NOT_NRO_HT'=>$homologacion['NOTA']['HTD'],
                                            'NOT_NRO_HP'=>$homologacion['NOTA']['HTC'],
                                            'NOT_NRO_AUT'=>$homologacion['NOTA']['HTA'],
                                            'NOT_CEA_COD'=>$homologacion['NOTA']['CLASIFICACION'],
                                            'NOT_ASI_COD_INS'=>$homologacion['ESPACIO_HOM_1'],
                                            'NOT_ASI_HOMOLOGA'=>$homologacion['ESPACIO_HOM_2'],
                                            'NOT_EST_HOMOLOGA'=>'S'  );
            $ingresado=$this->adicionarOracleHomologacion($datosRegistro);
            
            
            if($ingresado>0)
            {
                $homologado=$this->actualizarOracleHomologacion($datosRegistro);
                if($homologado>0)
                    {
                        $variablesRegistro2=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Actualiza estado registro Homologacion Pendiente',
                                                    'registro'=>" cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                    'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                    }else
                    {
                        $variablesRegistro2=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Conexion Error Oracle al actualiza estado registro Homologacion Pendiente',
                                                    'registro'=>" cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                    'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                    }
                $this->procedimientos->registrarEvento($variablesRegistro2);
                $notas[]=array('HOMOLOGADO'=>'S',
                                'COD_ESTUDIANTE'=>$datos_estudiante['COD_ESTUDIANTE'],
                                'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                'COD_PPAL'=>$homologacion['ESPACIO_PPAL'],
                                'NOTA'=>$datosRegistro['NOT_NOTA'],
                                'PERIODO'=>$datosRegistro['NOT_ANO']."-".$datosRegistro['NOT_PER']);
                $variablesRegistro=array('usuario'=>$this->usuario,
                                            'evento'=>'50',
                                            'descripcion'=>'Registra Homologacion',
                                            'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                            'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
            }else
                {
                    $notas[]=array('HOMOLOGADO'=>'N',
                                    'COD_ESTUDIANTE'=>$datos_estudiante['COD_ESTUDIANTE'],
                                    'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                    'COD_PPAL'=>$homologacion['ESPACIO_PPAL']);
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'50',
                                                'descripcion'=>'Conexion Error Oracle al ejecutar Homologacion',
                                                'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                 'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                }
            $this->procedimientos->registrarEvento($variablesRegistro);
        }
        return $notas;
    }

    /**
     * Funcion que registra homologaciones de bifurcacion
     * @param type $datos_estudiante
     * @param type $homologaciones
     */
    function ejecutarBifurcacion($datos_estudiante,$homologaciones){

        foreach ($homologaciones as $homologacion)
        {   
            
            if(isset($homologacion['ESPACIO_PPAL_1']))
            {
                switch($homologacion['NOTA_1']['OBSERVACION']){
                    case 19:
                        $observacion = 22;
                        break;
                    case 20:
                        $observacion = 23;
                        break;
                    default:
                        $observacion = 17;
                        break;
                }
                $datosRegistro = array('NOT_CRA_COD'=>$datos_estudiante['CRA_COD'],
                                                'NOT_EST_COD'=>$datos_estudiante['COD_ESTUDIANTE'],
                                                'NOT_ASI_COD'=>$homologacion['ESPACIO_PPAL_1'],
                                                'NOT_ANO'=>$homologacion['NOTA_1']['ANO_NOTA'],
                                                'NOT_PER'=>$homologacion['NOTA_1']['PERIODO_NOTA'],
                                                'NOT_SEM'=>$homologacion['NOTA_1']['NIVEL_NOTA'],
                                                'NOT_NOTA'=>$homologacion['NOTA_1']['NOTA'],
                                                'NOT_GR'=>$homologacion['NOTA_1']['GRUPO'],
                                                'NOT_OBS'=>$observacion,
                                                'NOT_FECHA'=> date('d/m/Y'),
                                                'NOT_EST_REG'=>'A',
                                                'NOT_CRED'=>$homologacion['NOTA_1']['CREDITOS'],
                                                'NOT_NRO_HT'=>$homologacion['NOTA_1']['HTD'],
                                                'NOT_NRO_HP'=>$homologacion['NOTA_1']['HTC'],
                                                'NOT_NRO_AUT'=>$homologacion['NOTA_1']['HTA'],
                                                'NOT_CEA_COD'=>$homologacion['NOTA_1']['CLASIFICACION'],
                                                'NOT_ASI_COD_INS'=>$homologacion['ESPACIO_HOM'],
                                                'NOT_ASI_HOMOLOGA'=>$homologacion['ESPACIO_HOM'],
                                                'NOT_EST_HOMOLOGA'=>'S'  );
                $ingresado=$this->adicionarOracleHomologacion($datosRegistro);                
                
                if($ingresado>0)
                {
                    $homologado=$this->actualizarOracleHomologacion($datosRegistro);
                    if($homologado>0)
                    {
                        $variablesRegistro2=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Actualiza estado registro Homologacion Pendiente',
                                                    'registro'=>" cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                    'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                    }else
                    {
                        $variablesRegistro2=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Conexion Error Oracle al actualiza estado registro Homologacion Pendiente',
                                                    'registro'=>" cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                    'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                    }
                    $this->procedimientos->registrarEvento($variablesRegistro2);
                    $notas[]=array('HOMOLOGADO'=>'S',
                                    'COD_ESTUDIANTE'=>$datos_estudiante['COD_ESTUDIANTE'],
                                    'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                    'COD_PPAL'=>$homologacion['ESPACIO_PPAL_1'],
                                    'NOTA'=>$datosRegistro['NOT_NOTA'],
                                    'PERIODO'=>$datosRegistro['NOT_ANO']."-".$datosRegistro['NOT_PER']);
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'50',
                                                'descripcion'=>'Registra Homologacion',
                                                'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                }else
                    {
                        $notas[]=array('HOMOLOGADO'=>'N',
                                        'COD_ESTUDIANTE'=>$datos_estudiante['COD_ESTUDIANTE'],
                                        'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                        'COD_PPAL'=>$homologacion['ESPACIO_PPAL_1']);
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Conexion Error Oracle al ejecutar Homologacion',
                                                    'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                     'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                    }
                $this->procedimientos->registrarEvento($variablesRegistro);
            }
            if(isset($homologacion['ESPACIO_PPAL_2']))
            {
                switch($homologacion['NOTA_2']['OBSERVACION']){
                    case 19:
                        $observacion2 = 22;
                        break;
                    case 20:
                        $observacion2 = 23;
                        break;
                    default:
                        $observacion2 = 17;
                        break;
                }
                $datosRegistro = array('NOT_CRA_COD'=>$datos_estudiante['CRA_COD'],
                                                'NOT_EST_COD'=>$datos_estudiante['COD_ESTUDIANTE'],
                                                'NOT_ASI_COD'=>$homologacion['ESPACIO_PPAL_2'],
                                            'NOT_ANO'=>$homologacion['NOTA_2']['ANO_NOTA'],
                                            'NOT_PER'=>$homologacion['NOTA_2']['PERIODO_NOTA'],
                                                'NOT_SEM'=>$homologacion['NOTA_2']['NIVEL_NOTA'],
                                                'NOT_NOTA'=>$homologacion['NOTA_2']['NOTA'],
                                                'NOT_GR'=>$homologacion['NOTA_2']['GRUPO'],
                                                'NOT_OBS'=>$observacion2,
                                                'NOT_FECHA'=> date('d/m/Y'),
                                                'NOT_EST_REG'=>'A',
                                                'NOT_CRED'=>$homologacion['NOTA_2']['CREDITOS'],
                                                'NOT_NRO_HT'=>$homologacion['NOTA_2']['HTD'],
                                                'NOT_NRO_HP'=>$homologacion['NOTA_2']['HTC'],
                                                'NOT_NRO_AUT'=>$homologacion['NOTA_2']['HTA'],
                                                'NOT_CEA_COD'=>$homologacion['NOTA_2']['CLASIFICACION'],
                                                'NOT_ASI_COD_INS'=>$homologacion['ESPACIO_HOM'],
                                                'NOT_ASI_HOMOLOGA'=>$homologacion['ESPACIO_HOM'],
                                                'NOT_EST_HOMOLOGA'=>'S'  );
                $ingresado=$this->adicionarOracleHomologacion($datosRegistro);
                if($ingresado>0)
                {
                    $homologado=$this->actualizarOracleHomologacion($datosRegistro);
                
                    if($homologado>0)
                    {
                        $variablesRegistro2=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Actualiza estado registro Homologacion Pendiente',
                                                    'registro'=>" cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                    'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                    }else
                    {
                        $variablesRegistro2=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Conexion Error Oracle al actualiza estado registro Homologacion Pendiente',
                                                    'registro'=>" cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                    'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                    }
                    $this->procedimientos->registrarEvento($variablesRegistro2);
                    $notas[]=array('HOMOLOGADO'=>'S',
                                    'COD_ESTUDIANTE'=>$datos_estudiante['COD_ESTUDIANTE'],
                                    'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                    'COD_PPAL'=>$homologacion['ESPACIO_PPAL_2'],
                                    'NOTA'=>$datosRegistro['NOT_NOTA'],
                                    'PERIODO'=>$datosRegistro['NOT_ANO']."-".$datosRegistro['NOT_PER']);
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'50',
                                                'descripcion'=>'Registra Homologacion',
                                                'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                }else
                    {
                        $notas[]=array('HOMOLOGADO'=>'N',
                                        'COD_ESTUDIANTE'=>$datos_estudiante['COD_ESTUDIANTE'],
                                        'NOMBRE'=>$datos_estudiante['NOMBRE'],
                                        'COD_PPAL'=>$homologacion['ESPACIO_PPAL_2']);
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                                    'evento'=>'50',
                                                    'descripcion'=>'Conexion Error Oracle al ejecutar Homologacion',
                                                    'registro'=>"cod_espacio-> ".$datosRegistro['NOT_ASI_COD'].", cod_homologo->".$datosRegistro['NOT_ASI_COD_INS'],
                                                     'afectado'=>$datos_estudiante['COD_ESTUDIANTE']);
                    }
                $this->procedimientos->registrarEvento($variablesRegistro);                
            }
        }
        return $notas;
    }



   /**
     * Funcion que consulta los registro que existen en la base de datos de la tabla de homologaciones
     * y retorna el resultado
     * @param <int> $cod_proyecto
     */
   function consultarTablaHomologaciones($cod_proyecto) {
      $cadena_sql = $this->sql->cadena_sql("consultarTablaHomologaciones",$cod_proyecto); 
     return $resultadoTabla = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");    
 
    }

   /**
     * Funcion que valida si existen registros en la tabla de homologaciones
     * @param <array> $tabla_homologaciones
     */
   function verificarTablaHomologaciones($tabla_homologaciones){
        if(is_array($tabla_homologaciones)){
            if(count($tabla_homologaciones)>0){
                return 'ok';
            }else{
                return 'No existen registros en la tabla de homologaciones para el Proyecto Curricular.';
                exit;
            }
        }else{
                return 'No existen registros en la tabla de homologaciones para el Proyecto Curricular.';
                exit;
            }
    }
    
   /**
     * Funcion que consulta las notas de un estudiante que existen en la base de datos
     * @param <int> $cod_estudiante
     */
   function consultarNotasEstudiante($cod_estudiante,$cod_proyecto){      
       $datos= array('cod_estudiante'=>$cod_estudiante,
                     'cod_proyecto'=>$cod_proyecto);
      $cadena_sql = $this->sql->cadena_sql("consultarNotasEstudiante",$datos);
      return $resultadoTabla = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        
    }

    /**
     * Funcion que consulta los datos de un estudiante que se encuentran registrados en el sistema
     * y retorna el resultado
     * @param <int> $cod_estudiante
     * @param <int> $cod_proyecto
     */
  function consultarDatosEstudiante($cod_estudiante,$cod_proyecto){
      $datos= array('cod_estudiante'=>$cod_estudiante,
                    'cod_proyecto'=>$cod_proyecto);
      $cadena_sql = $this->sql->cadena_sql("consultarDatosEstudiante",$datos); 
      return $resultadoEstudiante = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        
    }


  /**
   * Funcion que consulta la nota aprobatoria para el proyecto
   * @param type $cod_proyecto
   * @return type
   */
    function consultarNotaAprobatoria($cod_proyecto){
      $datos= array('cod_proyecto'=>$cod_proyecto);
      $cadena_sql = $this->sql->cadena_sql("consultarNotaAprobatoria",$datos);
      return $resultadoNota = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        
    }

 
    /**
     * Funcion que valida si un estudiante pertenece a un Proyecto curricular
     * @param <int> $cod_estudiante
     * @param <int> $cod_proyecto
     * Utiliza el metodo consultarDatosEstudiante
     */
  function verificarEstudiante($cod_estudiante,$cod_proyecto) {     
         $registro = $this->consultarDatosEstudiante($cod_estudiante,$cod_proyecto);         
        if (is_array($registro)) {
            $nroRegistros = count($registro);
            if ($nroRegistros > 0) {
                return 'ok';
            } else{
                return 'El dato ingresado '.$cod_estudiante.' no corresponde al código de un estudiante del Proyecto Curricular.';
                exit;
            }
        }else{
                return 'El dato ingresado '.$cod_estudiante.' no corresponde al código de un estudiante del Proyecto Curricular.';
                exit;
            } 
         
    }
    
    /**
     * Funcion que adiciona un registro de homologacion en Oracle
     * @param <array> $datos(NOT_CRA_COD, NOT_EST_COD, NOT_ASI_COD, NOT_ANO, NOT_PER, NOT_SEM, NOT_NOTA,NOT_GR, NOT_OBS, NOT_FECHA, NOT_EST_REG,
     * NOT_CRED, NOT_NRO_HT, NOT_NRO_HP, NOT_NRO_AUT, NOT_CEA_COD,  NOT_ASI_COD_INS, NOT_ASI_HOMOLOGA, NOT_EST_HOMOLOGA)
     */
    function adicionarOracleHomologacion($datos) {
        $cadena_sql_homologacion=$this->sql->cadena_sql("adicionar_homologacion",$datos);
        $resultado_homologacion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_homologacion,""); 
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
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

 
     /**
     * Funcion que consulta los datos de un espacio academico
     * @param <int> $cod_espacio
     * @param <int> $cod_plan
     */
    function consultarEspaciosProyecto($cod_proyecto){
       $cadena_sql = $this->sql->cadena_sql("consultarEspaciosProyecto",$cod_proyecto);
      return $resultadoEspacio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
       
    }

     /**
     * Funcion que consulta los datos de un espacio academico
     * @param <int> $cod_plan
     */
    function buscarEspaciosPlan($cod_plan,$espacios_proyecto){
        $espacios_plan="";
        if(is_array($espacios_proyecto)) {
            $k=0;
       
            for($i=0;$i<count($espacios_proyecto); $i++) {
                if($cod_plan == $espacios_proyecto[$i]['PENSUM']){
                    $espacios_plan[$k]['COD_CRA'] = $espacios_proyecto[$i]['COD_CRA']; 
                    $espacios_plan[$k]['COD_ASI'] = $espacios_proyecto[$i]['COD_ASI']; 
                    $espacios_plan[$k]['SEMESTRE'] = $espacios_proyecto[$i]['SEMESTRE']; 
                    $espacios_plan[$k]['INDICA_ELECTIVA'] = (isset($espacios_proyecto[$i]['INDICA_ELECTIVA'])?$espacios_proyecto[$i]['INDICA_ELECTIVA']:'N'); 
                    $espacios_plan[$k]['H_TEORICAS'] = $espacios_proyecto[$i]['H_TEORICAS']; 
                    $espacios_plan[$k]['H_PRACTICAS'] = $espacios_proyecto[$i]['H_PRACTICAS']; 
                    $espacios_plan[$k]['ESTADO'] = (isset($espacios_proyecto[$i]['ESTADO'])?$espacios_proyecto[$i]['ESTADO']:''); 
                    $espacios_plan[$k]['CREDITOS'] = (isset($espacios_proyecto[$i]['CREDITOS'])?$espacios_proyecto[$i]['CREDITOS']:''); 
                    $espacios_plan[$k]['PENSUM'] = $espacios_proyecto[$i]['PENSUM']; 
                    $espacios_plan[$k]['H_AUTONOMO'] = $espacios_proyecto[$i]['H_AUTONOMO']; 
                    $espacios_plan[$k]['INDICA_PROMEDIO'] = $espacios_proyecto[$i]['INDICA_PROMEDIO']; 
                    $espacios_plan[$k]['CLASIFICACION'] = (isset($espacios_proyecto[$i]['CLASIFICACION'])?$espacios_proyecto[$i]['CLASIFICACION']:''); 
                    $k++;
                    
                }
            }
           
       }
        
      return $espacios_plan;
       
    }
    
    /**
     * Funcion que presenta los resultados de las homologaciones
     * @param <array> $resultado(NOT_CRA_COD, NOT_EST_COD, NOT_ASI_COD, NOT_ANO, NOT_PER, NOT_SEM, NOT_NOTA,NOT_GR, NOT_OBS, NOT_FECHA, 
     * NOT_ASI_COD_INS, HOMOLOGADO, COD_ESTUDIANTE, NOMBRE, COD_PPAL)
     * @param <int> $tipo
     */
    function mostrarResultadoHomologacion($resultado, $tipo){
        $cant_hom_exitosas=0;
        switch ($tipo){
            case 'IMPLICITAS':
                $nombre_tipo_hom = "Homologaciones implicitas";
                break;
            case 'UNOAUNO':
                $nombre_tipo_hom = "Homologaciones espacios uno a uno";
                break;
            case 'UNION':
                $nombre_tipo_hom = "Homologaciones espacios unión (dos homologan uno)";
                break;
            case 'BIFURCACION':
                $nombre_tipo_hom = "Homologaciones espacios bifurcacion (uno homologa dos)";
                break;
        }
        if($tipo=='IMPLICITAS'){
            echo "<br><div align='center' ><b>Resultado de Homologaciones</b></div><hr>";
        }
                 ?>
  
        
        <table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
        
        <?
         if (is_array($resultado)) {
      ?><br>
      <div ><b><?echo $nombre_tipo_hom;?></b></div><br>
        
              <thead class='sigma'>
                <th class='niveles centrar' width="20">Codigo Estudiante</th>
                <th class='niveles centrar' width="50">Nombre</th>
                <th class='niveles centrar' width="10">Cantidad Homologaciones</th>
                <th class='niveles centrar' width="20">Espacio Acad&eacute;mico principal</th>
                <th class='niveles centrar' width="10">Nota</th>
                <th class='niveles centrar' width="10">Periodo</th>
              </thead>
              <?
              
              //recorre los resultados
              $codigo_antecesor='';
              foreach ($resultado as $i => $resultado_estudiante) {
                  
                  $cantidad_espacios_hom = $this->verificaEspaciosHomologado($resultado_estudiante);
                  $filas= "rowspan=".$cantidad_espacios_hom;
                  $cantidad = count($resultado_estudiante);
                for ($j = 0; $j < $cantidad; $j++) {
                    $homologado= (isset($resultado_estudiante[$j]['HOMOLOGADO'])?$resultado_estudiante[$j]['HOMOLOGADO']:'');
                            if($homologado=='S'){
                                $codigo = (isset($resultado_estudiante[$j]['COD_ESTUDIANTE'])?$resultado_estudiante[$j]['COD_ESTUDIANTE']:''); 
                                //$codigo_antecesor = (isset($resultado_estudiante[$j-1]['COD_ESTUDIANTE'])?$resultado_estudiante[$j-1]['COD_ESTUDIANTE']:''); 
                                $nombre = (isset($resultado_estudiante[$j]['NOMBRE'])?$resultado_estudiante[$j]['NOMBRE']:'');
                                $espacios_hom_estudiante = $cantidad_espacios_hom;
                                $cod_espacio = (isset($resultado_estudiante[$j]['COD_PPAL'])?$resultado_estudiante[$j]['COD_PPAL']:'');
                                $nota_espacio = (isset($resultado_estudiante[$j]['NOTA'])?$resultado_estudiante[$j]['NOTA']:'');
                                $periodo_espacio = (isset($resultado_estudiante[$j]['PERIODO'])?$resultado_estudiante[$j]['PERIODO']:'');
                                $cant_hom_exitosas++;
                ?>
                        <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
                                <? 
                                if($codigo != $codigo_antecesor ) { ?>
                                <td class='cuadro_plano centrar' <? echo $filas;?>><? echo $codigo?></td>
                                <td class='cuadro_plano ' <? echo $filas;?>><? echo htmlentities($nombre); ?></td>
                                <td class='cuadro_plano centrar' <? echo $filas;?>><? echo $espacios_hom_estudiante?></td>
                                <? }?>
                                <td class='cuadro_plano centrar'><? echo $cod_espacio ?></td>
                                <td class='cuadro_plano centrar'><? echo $nota_espacio ?></td>
                                <td class='cuadro_plano centrar'><? echo $periodo_espacio ?></td>

                        </tr>
                <?
                            $codigo_antecesor = $codigo;
                
                            }
                    }

                }
?>
              <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
                  <td class='cuadro_plano centrar' colspan="4"><b>Total <? if ($nombre_tipo_hom!='ESTUDIANTESNOVALIDOS') echo $nombre_tipo_hom." : ".$cant_hom_exitosas; ;?></b></td>
              </tr>
              
              <?
              }else
                    {
                  ?><tr><td class='cuadro_plano centrar' colspan="5">NO HAY REGISTROS DE HOMOLOGACIONES EJECUTADAS</td></tr><?

                    }
              ?>
        </table>
      <?
        $cant_homologaciones=$cant_hom_exitosas;
        return $cant_homologaciones;
    }  
    
    /**
     * Funcion que presenta los totales de la homologacion
     * @param <int> $total
     * @param <int> $estudiantes_procesados
     * @param <int> $cantidadEstudiantesHomologados
     */
    
    function mostrarTotalesHomologaciones($total, $estudiantes_procesados,$cantidadEstudiantesHomologados){
        ?>
            <br>
            <table width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
                  <td colspan="4"><b>Total Homologaciones : <? echo $total; ?></b></td>
                </tr>
                <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
                  <td colspan="4"><b>Total Estudiantes procesados : <? echo $estudiantes_procesados; ?></b></td>
                <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
                  <td colspan="3"><b>Total Estudiantes Homologados : <? echo $cantidadEstudiantesHomologados; ?></b></td>
                </tr>
            </table>
    <?
}

    /**
     * Funcion que arma el reporte con los resultados de las homologaciones
     * @param <array> $resultadoImplicitas (HOMOLOGADO, COD_ESTUDIANTE, NOMBRE, COD_PPAL)
     * @param <array> $resultadoUnoAUno (HOMOLOGADO, COD_ESTUDIANTE, NOMBRE, COD_PPAL)
     * @param <array> $resultadoUnion (HOMOLOGADO, COD_ESTUDIANTE, NOMBRE, COD_PPAL)
     * @param <array> $resultadoBifurcacion (HOMOLOGADO, COD_ESTUDIANTE, NOMBRE, COD_PPAL)
     * @param <int> $cantidadEstudiantes 
     * @param <int> $cantidadEstudiantesHomologados
     * Utiliza los metodos mostrarResultadoHomologacion, mostrarTotalesHomologaciones
     */
    function mostrarReporteResultados($resultadoImplicitas,$resultadoUnoAUno,$resultadoUnion,$resultadoBifurcacion,$cantidadEstudiantes,$cantidadEstudiantesHomologados){
            $cant_implicitas=0;
            $cant_unoauno=0;
            $cant_union=0;
            $cant_bifurcacion=0;
            if(is_array($resultadoImplicitas) && count($resultadoImplicitas)>0){
                $cant_implicitas = $this->mostrarResultadoHomologacion($resultadoImplicitas,'IMPLICITAS');
            }
            if(is_array($resultadoUnoAUno) && count($resultadoUnoAUno)>0){
                $cant_unoauno = $this->mostrarResultadoHomologacion($resultadoUnoAUno,'UNOAUNO');
            }
            if(is_array($resultadoUnion) && count($resultadoUnion)>0){
                $cant_union = $this->mostrarResultadoHomologacion($resultadoUnion,'UNION');
            }
            if(is_array($resultadoBifurcacion) && count($resultadoBifurcacion)>0){
                $cant_bifurcacion = $this->mostrarResultadoHomologacion($resultadoBifurcacion,'BIFURCACION');
            }
            $total_exitosas=$cant_implicitas+$cant_unoauno+$cant_union+$cant_bifurcacion;
            $this->mostrarTotalesHomologaciones($total_exitosas,$cantidadEstudiantes,$cantidadEstudiantesHomologados);

    }
    
    /**
     * Funcion que verifica que a un estudiante se le halla realizado alguna homologacion
     * @param <array> $resultadoImplicitas (HOMOLOGADO, COD_ESTUDIANTE, NOMBRE, COD_PPAL)
     * @param <array> $resultadoUnoAUno (HOMOLOGADO, COD_ESTUDIANTE, NOMBRE, COD_PPAL)
     * @param <array> $resultadoUnion (HOMOLOGADO, COD_ESTUDIANTE, NOMBRE, COD_PPAL)
     * @param <array> $resultadoBifurcacion (HOMOLOGADO, COD_ESTUDIANTE, NOMBRE, COD_PPAL)
     * Utiliza el metodo verificaEspaciosHomologado
     */
    function verificarEstudianteHomologado($resultadoImplicitas,$resultadoUnoAUno,$resultadoUnion,$resultadoBifurcacion){
        $hom_implicitas = $this->verificaEspaciosHomologado($resultadoImplicitas);
        $hom_unoauno = $this->verificaEspaciosHomologado($resultadoUnoAUno);
        $hom_union = $this->verificaEspaciosHomologado($resultadoUnion);
        $hom_bifurcacion = $this->verificaEspaciosHomologado($resultadoBifurcacion);
        
        if ($hom_implicitas>0 || $hom_unoauno>0 || $hom_union>0 || $hom_bifurcacion>0){
            return 'ok';
        }else{
            return 'N';
        }
        
        
    }
    
    /**
     * Funcion que cuenta los espacios homologados satisfactoriamente
     * @param <array> $resultadoHomologacion (HOMOLOGADO, COD_ESTUDIANTE, NOMBRE, COD_PPAL)
     *
     */
    function verificaEspaciosHomologado($resultadoHomologacion){
        $total=0;
        if(is_array($resultadoHomologacion) && count($resultadoHomologacion)>0){
            for($i=0;$i<count($resultadoHomologacion);$i++){
                if($resultadoHomologacion[$i]['HOMOLOGADO']=='S'){
                    $total++;
                }
            }
        }
        return $total;
    }
    
    function actualizarOracleHomologacion($datosRegistro){
        
        $datos=array('NOT_ASI_COD_INS'=>$datosRegistro['NOT_ASI_COD_INS'],
                      'estado'=>'I',
                      'NOT_EST_COD'=>$datosRegistro['NOT_EST_COD'],
                      'NOT_ANO'=>$datosRegistro['NOT_ANO'],
                      'NOT_PER'=>$datosRegistro['NOT_PER']);
               
        $cadena_sql_homologacion=$this->sql->cadena_sql("actualizar_homologacion",$datos);
        $resultado_homologacion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_homologacion,""); 
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }

    /**
     * Funcion que busca la diferencia entre los espacios con notas y los espacios del plan de estudios
     * @param <array> $notasActuales (COD_CRA, COD_ASI, SEMESTRE, INDICA_ELECTIVA, H_TEORICAS, H_PRACTICAS, ESTADO, CREDITOS, PENSUM, H_AUTONOMO, INDICA_PROMEDIO, CLASIFICACION)
     * @param <array> $espaciosPlanEstudio (COD_CARRERA, COD_ESTUDIANTE, COD_ESPACIO, ANO_NOTA, PERIODO_NOTA, NOTA, GRUPO, OBSERVACION, COD_INSCRITA)
     *
     */ 
    function restarEspaciosPlan($notasActuales,$espaciosPlanEstudio){
        $espaciosHomologar='';
        foreach ($espaciosPlanEstudio as $espacios) {
            $espaciosPE[] = $espacios['COD_ASI']; 
        }
        
        foreach ($notasActuales as $notas) {
            $espacios_notas[] = $notas['COD_ESPACIO']; 
        }
        $espacios_notas = array_unique($espacios_notas);
        //busca los espacios que tienen nota pero no se encuentran en el plan de estudios
        $resultado = array_diff($espacios_notas,$espaciosPE);
        
        //busca los datos de la nota de cada espacio a homologar
        foreach ($resultado as $espacio) {
            foreach ($notasActuales as $notas2) {
                if($espacio==$notas2['COD_ESPACIO']) { 
                    $espaciosHomologar[] =$notas2;
                }
            }
        }
       
        return $espaciosHomologar;
    }
  
       /**
     * Funcion que consulta los datos de los estudiantes de una carrera, para realizarles el proceso de homologacion
     * @param <int> $cod_proyecto
     */
    function consultarEstudiantesParaHomologacion($cod_proyecto){
        $cadena_sql = $this->sql->cadena_sql("consultarEstudiantesProyecto",$cod_proyecto);
        return $resultadoEspacio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
 
}

?>