<?php
/**
 * Funcion cambiarEstados
 *
 * Descripcion
 *
 * @package cierreSemestre
 * @subpackage cambiarEstados
 * @author Milton Parra
 * @version 0.0.0.1
 * Fecha: 17/04/2013
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");

/**
 * Clase funcion_registro
 *
 * descripcion
 * 
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 */
class funcion_registroCambiarEstados extends funcionGeneral
{

    public $configuracion;
    public $datosEstudiantes;
    public $inscripciones;
    public $codProyecto;
    public $periodo;
    public $historicoEstudiantes;
    public $mensaje;
    public $datosEstudiantesRetirados;

    /**
     * 
     * @param array $configuracion contiene todas la variables del sistema almacenadas en la base de datos del framework
     */
    function __construct($configuracion){

          $this->configuracion=$configuracion;
          $this->cripto=new encriptar();
          $this->sql=new sql_registroCambiarEstados($configuracion);
          $this->procedimientos=new procedimientos();
          $this->formulario="registro_cambiarEstados";//nombre del bloque que procesa el formulario

          /**
           * Intancia para crear la conexion ORACLE
           */
          $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
          /**
           * Instancia para crear la conexion General
           */
          $this->acceso_db=$this->conectarDB($configuracion,"");
          /**
           * Instancia para crear la conexion de MySQL
           */
          $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

          $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
          /**
           * ejemplo de validación de un formulario
           */
          $this->verificar="control_vacio(".$this->formulario.",'nombre','')";              
            $cadena_sql=$this->sql->cadena_sql('periodo_activo',$_REQUEST['codProyecto']);
            $this->periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            $this->mensaje=array();

            if($this->usuario=="")
            {
                echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
                EXIT;
            }
            

      }

    /**
       * Funcion que ejecuta el proceso de cambio de estados de estudiantes en el cierre de semestre
       */
    function cambiarEstados(){
        ?>            
        
            <head>
                <script language="javascript">
                //Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
                function callprogress(vValor,vItem,vTotal){
                 document.getElementById("getprogress").innerHTML = 'Cambiando estados '+vItem+' de '+vTotal+' estudiantes.  '+vValor ;
                 document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
                }
                </script>
                <style type="text/css">
                /* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
                  .ProgressBar     { width: 70%; border: 1px solid black; background: #eef; height: 1.25em; display: block; margin-left: auto;margin-right: auto }
                  .ProgressBarText { position: absolute; font-size: 1em; width: 35em; text-align: center; font-weight: normal; }
                  .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible; }
                </style>
            </head>
            <body>
            <!-- Ahora creo la barra de progreso con etiquetas DIV -->
             <div class="ProgressBar">
                  <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% </div>
                  <div id="getProgressBarFill"></div>
                </div>
            </body><?            
        $this->codProyecto=$_REQUEST['codProyecto'];
        $datosProyecto=$this->consultarDatosProyecto($this->codProyecto);
        $this->registrarEventoCambioEstados($datosProyecto);
        //17/06/2014 Milton Parra
        //se adiciona para registrar el historico de estudiantes que han hecho cancelacion de semestre --estados C, R, P
        $this->buscarDatosEstudiantesRetirados();
        //Registra historico y reglamento de estudiantes retirados si los hay para el periodo.
        if(is_array($this->datosEstudiantesRetirados))
        {
            $this->registrarHistoricosRetirados();
        }
        //busca los datos de los estudiantes del proyecto
        $this->buscarDatosEstudiantes();
        //busca si algun estudiante ya tiene registrado historico para el periodo para eliminarlo
        $this->buscarHistoricoEstudiantes();
        if(is_array($this->historicoEstudiantes)&&!empty($this->historicoEstudiantes))
        {
            $this->eliminarEstudiantesCambioEstado();
        }
        $registro=0;
        $a=1;
        $numRegistros=count($this->datosEstudiantes);
        foreach ($this->datosEstudiantes as $estudiante)
        {

            $porcentaje = $a * 100 / $numRegistros; //saco mi valor en porcentaje
            echo "<script>callprogress(".round($porcentaje).",".$a.",".$numRegistros.")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
            flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
            ob_flush();
            $a++;
            usleep (300);
            $datosEstudiante=$this->crearArregloEstudiante($estudiante);
            //echo $datosEstudiante['EST_COD']." estado_ant= ".$datosEstudiante['EST_ESTADO']."***";exit;
            $historico=$this->registrarHistoricoEstudiante($datosEstudiante);
            if ($historico>=1)
            {
                $estado=$this->aplicarCambioEstado($datosEstudiante);
                if ($estado>=1)
                {
                    $reglamento=$this->registrarReglamento($datosEstudiante);
                    if ($reglamento>=1)
                    {
                    }else
                        {
                            $this->mensaje[]="No se pudo registrar datos de reglamento de ".$estudiante['CODIGO'];
                        }
                }else
                    {
                        $this->mensaje[]="No se pudo registrar cambiar estado de ".$estudiante['CODIGO'];
                    }
            }else
                {
                    $this->mensaje[]="No se pudo registrar histórico de ".$estudiante['CODIGO'];
                }
/*       if($variablesRegistro){
           $this->procedimientos->registrarEvento($variablesRegistro);
       }*/
            unset ($datosEstudiante);
        }

        if(is_array($this->mensaje)&&!empty($this->mensaje))
        {
            foreach ($this->mensaje as $key => $value) {
            $variablesRegistro=array('usuario'=>$this->usuario,
                                     'evento'=>'70',
                                     'descripcion'=>'Error ejecutar cierre de semestre',
                                     'registro'=>'codProyecto->'.$this->codProyecto.','.$this->mensaje[$key],
                                     'afectado'=>$this->codProyecto);
            $this->procedimientos->registrarEvento($variablesRegistro);
            }
        }

        $this->actualizarEventoCambioEstados($datosProyecto);
        $this->volverFormularioCierre();
      }
      
      /**
       * Funcion que crea un arreglo de datos de estudiantes del proyecto
       */
      function buscarDatosEstudiantes() {
            $variables=array('estados'=>"'A', 'B', 'D', 'F', 'H', 'J', 'K', 'M', 'O', 'V'",
                            'codProyecto'=>$this->codProyecto);
            $this->datosEstudiantes=$this->consultarDatosEstudiantes($variables);
            $this->inscripciones=$this->consultaInscripcionesEstudiantesProyecto($variables);
            foreach ($this->datosEstudiantes as $numero=>$estudiante)
            {
                $this->datosEstudiantes[$numero]['INSCRITAS']=0;
                foreach ($this->inscripciones as $inscripciones) {
                    if ($estudiante['CODIGO']==$inscripciones['CODIGO'])
                    {
                        $this->datosEstudiantes[$numero]['INSCRITAS']=1;
                        break;
                    }
                }
            }
      }
      
      /**
       * Funcion que crea un arreglo de datos de estudiantes del proyecto
       */
      function buscarDatosEstudiantesRetirados() {
            $variables=array('estados'=>"'C', 'R', 'P'",
                            'codProyecto'=>$this->codProyecto);
            $this->retirados=$this->consultarDatosEstudiantes($variables);
            //Verifica de los estudiantes retirados cuales lo hicieron en el periodo
            $this->retiros=$this->consultarRetirosAprobadosProyecto($variables);
            if(is_array($this->retiros))
            {
                foreach ($this->retirados as $numero=>$estudiante)
                {
                    foreach ($this->retiros as $retirado) {
                        if ($estudiante['CODIGO']==$retirado['CODIGO'])
                        {
                            $this->datosEstudiantesRetirados[]=$estudiante;
                            break;
                        }
                    }
                }
            }
      }
      
      /**
       * Funcion que busca los datos de historico de los estudiantes del proyecto
       */
      function buscarHistoricoEstudiantes() {
            $variables=array('ano'=>$this->periodo[0]['ANO'],
                            'periodo'=>$this->periodo[0]['PERIODO'],
                            'codProyecto'=>$this->codProyecto);
            $this->historicoEstudiantes=$this->consultarHistoricoEstudiantes($variables);
      }
      
      
      /**
       * Función que registra el historico y el reglamento adicionando un retiro a los estudiantes que se retiraron en el periodo.
       */
      
      function registrarHistoricosRetirados() {
          foreach ($this->datosEstudiantesRetirados as $key => $retirado) {
                $datosRetirado=$this->crearArregloEstudiante($retirado);
                $datosRetirado['EST_NUM_RETIROS']=$datosRetirado['EST_NUM_RETIROS']+1;
                $historico=$this->registrarHistoricoEstudiante($datosRetirado);
                $reglamento=$this->registrarReglamento($datosRetirado);
                unset($datosRetirado);
          }
      }      
      
      /**
       * Funcion que crea el arreglo de datos de un estudiante
       * @param type $datosEstudiante
       * @return type
       */
      function crearArregloEstudiante($datosEstudiante) {
            $promedio=$this->consultarPromedioEstudiante($datosEstudiante['CODIGO']);
            $retiros=$this->consultarHistoricosReglamentoRetirados($datosEstudiante['CODIGO']);            
            $promedio=(isset($promedio[0]['PROMEDIO'])?$promedio[0]['PROMEDIO']*100:0);
            $retiros=(isset($retiros[0]['NUM_RETIROS'])?$retiros[0]['NUM_RETIROS']:0);
            $arregloEstudiante=array('EST_COD'=>$datosEstudiante['CODIGO'],
                            'EST_CRA_COD'=>$datosEstudiante['CRA_CODIGO'],   
                            'EST_VALOR_MATRICULA'=>(isset($datosEstudiante['MATRICULA'])?$datosEstudiante['MATRICULA']:''),   
                            'EST_EXENTO'=>(isset($datosEstudiante['EXENTO'])?$datosEstudiante['EXENTO']:''), 
                            'EST_MOTIVO_EXENTO'=>(isset($datosEstudiante['MOTIVO_EXENTO'])?$datosEstudiante['MOTIVO_EXENTO']:''), 
                            'EST_ESTADO'=>$datosEstudiante['ESTADO'], 
                            'EST_ANO'=>$this->periodo[0]['ANO'],
                            'EST_PER'=>$this->periodo[0]['PERIODO'],
                            'EST_REG'=>'A',
                            'EST_PORCENTAJE'=>(isset($datosEstudiante['PORCENTAJE'])?$datosEstudiante['PORCENTAJE']:''),
                            'EST_PROMEDIO'=>$promedio,
                            'EST_INSCRITAS'=>(isset($datosEstudiante['INSCRITAS'])?$datosEstudiante['INSCRITAS']:''),
                            'EST_ACUERDO'=>(isset($datosEstudiante['ACUERDO'])?$datosEstudiante['ACUERDO']:''),
                            'EST_NUM_RETIROS'=>$retiros,
                            'EST_ACUERDO'=>(isset($datosEstudiante['ACUERDO'])?$datosEstudiante['ACUERDO']:''),
                            'EST_PEN_NRO'=>(isset($datosEstudiante['PLAN'])?$datosEstudiante['PLAN']:''),
                            'EST_IND_CRED'=>(isset($datosEstudiante['CREDITOS'])?$datosEstudiante['CREDITOS']:'')
                );
            return $arregloEstudiante;
      }
      /**
       * Funcion que elimina del arreglo los estudiantes a los que ya se les ha aplicado el cambio de estado
       */
      function eliminarEstudiantesCambioEstado() {
          foreach ($this->historicoEstudiantes as $numHistorico=>$historico)
          {
              $borrar=2;
            foreach ($this->datosEstudiantes as $numEstudiante=>$estudiante)
            {
                if ($historico['CODIGO']==$estudiante['CODIGO'])
                {
                    $datosEstudiante=$this->crearArregloEstudiante($estudiante);
                    $borrar=0;
                    if($historico['ESTADO']==$estudiante['ESTADO'])
                    {
                        $borrar=1;
                    }
                    if($borrar==1)
                    {
                        //cambia estado
                        $estado=$this->aplicarCambioEstado($datosEstudiante);
                        if ($estado>=1)
                        {
                            //registra reglamento
                            $reglamento=$this->registrarReglamento($datosEstudiante);
                            if ($reglamento>=1)
                            {}else
                                {
                                    $this->mensaje[]="No se pudo registrar datos de reglamento de ".$estudiante['CODIGO'];
                                }
                        }else
                            {
                                $this->mensaje[]="No se pudo registrar cambiar estado de ".$estudiante['CODIGO'];
                            }                        
                        unset ($this->datosEstudiantes[$numEstudiante]);
                    }else
                        {
                            //registra reglamento
                            $reglamento=$this->registrarReglamento($datosEstudiante);
                            if ($reglamento>=1)
                            {

                            }else
                                {
                                    $this->mensaje[]="No se pudo registrar datos de reglamento de ".$estudiante['CODIGO'];
                                }
                            unset ($this->datosEstudiantes[$numEstudiante]);
                        }
                    unset ($datosEstudiante);                        
                }else
                    {}
            }
          }
      }
      
      /**
       * funcion que registra los datos de los estudiantes del proyecto en el historico
       */
      function registrarHistoricoEstudiante($estudiante) {
            $registro=$this->registrarDatosHistorico($estudiante);
            return $registro;
              
      }
      
      /**
       * funcion que realiza el cambio de estado de los estudiantes del proyecto al cierre de semestre
       */
      function aplicarCambioEstado($datosEstudiante) {
          switch ($datosEstudiante['EST_ESTADO'])
          {
              case 'A':
                switch ($datosEstudiante['EST_INSCRITAS'])
                    {
                    case '1':
                        $datosEstudiante['EST_ESTADO']='V';
                        break;
                    case '0':
                        $datosEstudiante['EST_ESTADO']='D';
                        break;
                    }
                  break;
              case 'B':
                switch ($datosEstudiante['EST_INSCRITAS'])
                    {
                    case '1':
                        $datosEstudiante['EST_ESTADO']='V';
                        break;
                    case '0':
                        $datosEstudiante['EST_ESTADO']='K';
                        break;
                    }
                break;
              case 'D':
                  $datosEstudiante['EST_ESTADO']='W';
                  break;
              case 'F':
                  $datosEstudiante['EST_ESTADO']='W';
                  break;
              case 'H':
                  $datosEstudiante['EST_ESTADO']='T';
                  break;
              case 'J':
                  $datosEstudiante['EST_ESTADO']='W';
                  break;
              case 'K':
                  $datosEstudiante['EST_ESTADO']='W';
                  break;
              case 'M':
                  $datosEstudiante['EST_ESTADO']='W';
                  break;
              case 'V':
                  $datosEstudiante['EST_ESTADO']='W';
                  break;
              case 'O':
                  $datosEstudiante['EST_ESTADO']='Q';
                  break;
          }
          //actualiza estado del estudiante
          $estado=$this->actualizarEstadoEstudiante($datosEstudiante);
          return $estado;
          
      }
      /**
       * funcion que realiza el cambio de estado de los estudiantes del proyecto al cierre de semestre.
       *  Se cambia el 03/07/2014
       */
      function aplicarCambioEstadoAntiguo($datosEstudiante) {
          switch ($datosEstudiante['EST_ESTADO'])
          {
              case 'A':
                switch ($datosEstudiante['EST_INSCRITAS'])
                    {
                    case '1':
                        $datosEstudiante['EST_ESTADO']='V';
                        break;
                    case '0':
                        $datosEstudiante['EST_ESTADO']='D';
                        break;
                    }
                  break;
              case 'B':
                switch ($datosEstudiante['EST_INSCRITAS'])
                    {
                    case '1':
                        $datosEstudiante['EST_ESTADO']='V';
                        break;
                    case '0':
                        $datosEstudiante['EST_ESTADO']='K';
                        break;
                    }
                break;
              case 'D':
                  $datosEstudiante['EST_ESTADO']='R';
                  break;
              case 'F':
                  if (isset($datosEstudiante['EST_ACUERDO'])&&$datosEstudiante['EST_ACUERDO']=='2011004')
                  {
                      $datosEstudiante['EST_ESTADO']='U';
                  }else
                    {
                        $datosEstudiante['EST_ESTADO']='Z';
                    }
                  break;
              case 'H':
                  $datosEstudiante['EST_ESTADO']='T';
                  break;
              case 'J':
                  $datosEstudiante['EST_ESTADO']='F';
                  break;
              case 'K':
                  if (isset($datosEstudiante['EST_ACUERDO'])&&$datosEstudiante['EST_ACUERDO']=='2011004')
                  {
                      $datosEstudiante['EST_ESTADO']='U';
                  }else
                    {
                        $datosEstudiante['EST_ESTADO']='Z';
                    }
                  break;
              case 'M':
                  $datosEstudiante['EST_ESTADO']='R';
                  break;
              case 'V':
                  $datosEstudiante['EST_ESTADO']='M';
                  break;
              case 'O':
                  $datosEstudiante['EST_ESTADO']='Q';
                  break;
          }
          //actualiza estado del estudiante
          $estado=$this->actualizarEstadoEstudiante($datosEstudiante);
          return $estado;
          
      }
      
      /*
       * Funcion que genera el retorno al formulario del cierre de semestre
       */
      function volverFormularioCierre() {
                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";          
		$variable="&pagina=adminCierreSemestre";
		$variable.="&opcion=consultarProyecto";
		$variable.="&codProyecto=".$this->codProyecto;
		$variable= $this->cripto->codificar_url($variable,$this->configuracion);
		echo "<script>location.replace('".$pagina.$variable."')</script>";
      }
      
      /**
       * Funcion que inserta el evento de cambio de estados
       * @param type $proyecto
       * @return type
       */  
      function registrarEventoCambioEstados($proyecto) {
            
            $datos= array(
                        'codProyecto'=>$proyecto['CODIGO'],
                        'tipoProyecto'=>$proyecto['TIPO'],
                        'codDependencia'=>$proyecto['COD_DEPENDENCIA'],
                        'ano'=>$this->periodo[0]['ANO'],
                        'periodo'=>$this->periodo[0]['PERIODO'],                       
                        );                        
            
            $cadena_sql=$this->sql->cadena_sql("insertarEvento",$datos);
            $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
            
            return $this->totalAfectados($this->configuracion, $this->accesoOracle);        

        }
      
        /**
         * Funcion quye actualiza la fecha y hora de cierre del cambio de estados
         * @param type $proyecto
         * @return type
         */
        function actualizarEventoCambioEstados($proyecto) {
            
            $datos= array(
                        'codProyecto'=>$proyecto['CODIGO'],
                        'tipoProyecto'=>$proyecto['TIPO'],
                        'codDependencia'=>$proyecto['COD_DEPENDENCIA'],
                        'ano'=>$this->periodo[0]['ANO'],
                        'periodo'=>$this->periodo[0]['PERIODO'],                       
                        );                        
            
            $cadena_sql=$this->sql->cadena_sql("actualizarEvento",$datos);
            $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
            
            return $this->totalAfectados($this->configuracion, $this->accesoOracle);        

        }
      
      
      /**
       * Funcion que registra los datos de reglamento para los estudiantes del proyecto
       */
      function registrarReglamento($datosEstudiante) {
            $cadena_sql=$this->sql->cadena_sql('registrarReglamentoEstudiante',$datosEstudiante);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->accesoOracle);
      }
      
      /**
       * Funcion que permite registrar el historico de datos del estudiante
       * @param type $datosEstudiante
       * @return type
       */
      function registrarDatosHistorico($datosEstudiante) {
            $cadena_sql=$this->sql->cadena_sql('registrarHistoricoDatosEstudiantes',$datosEstudiante);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        }
      /**
       * Funcion que consulta los datos de los estudiantes del proyecto
       * @return type
       */
      function consultarDatosEstudiantes($variables) {
            $cadena_sql=$this->sql->cadena_sql('consultarDatosEstudiantes',$variables);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado;
          
      }

      /**
       * Funcion que consulta los datos de los estudiantes del proyecto
       * @return type
       */
      function consultarPromedioEstudiante($codEstudiante) {
            $cadena_sql=$this->sql->cadena_sql('consultarPromedioEstudiante',$codEstudiante);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado;
          
      }

      /**
       * Funcion que permite consultar las inscripciones de los estudiantes del proyecto
       * @param type $variables
       * @return type
       */
      function consultaInscripcionesEstudiantesProyecto($variables) {
            $cadena_sql=$this->sql->cadena_sql('consultarInscripciones',$variables);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado;
          
      }
      
      /**
       * Funcion que consulta el historico de los estudiants del proyecto
       * @param type $variables
       * @return type
       */
      function consultarHistoricoEstudiantes($variables) {
            $cadena_sql=$this->sql->cadena_sql('consultarHistoricoEstudiantes',$variables);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            return $resultado;
      }
      
      /**
       * Funcion que permite consultar los datos del proyecto
       * @param type $codProyecto
       * @return type
       */ 
      function consultarDatosProyecto($codProyecto) {
          
         $datos= array('codProyecto'=>$codProyecto
                        );
         $cadena_sql = $this->sql->cadena_sql("consultarDatosProyecto",$datos);
         $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
         return $resultado[0];
           
        }
      
      /**
       * Funcion que permite actualizar el estado del estudiante
       * @param type $datosEstudiante
       * @return type
       */
      function actualizarEstadoEstudiante($datosEstudiante) {
            $cadena_sql=$this->sql->cadena_sql('actualizarDatosEstudiante',$datosEstudiante);
            $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "");
            return $this->totalAfectados($this->configuracion, $this->accesoOracle);
          
      }
      
      /**
       * Funcion que permite consultar los estudiantes que han solicitado y se les aprobo retiro para el periodo
       * Se incluye
       * Milton Parra 17/06/2014.
       * @return type
       */
      function consultarRetirosAprobadosProyecto() {
            $variables=array('ano'=>$this->periodo[0]['ANO'],
                            'periodo'=>$this->periodo[0]['PERIODO'],
                            'codProyecto'=>$this->codProyecto);
            $cadena_sql=$this->sql->cadena_sql('consultarRetirosAprobadosProyecto',$variables);
            return $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          
      }
      
      /**
       * Funcion que permite consultar el ultimo historico de reglamento de cada estudiante retirado.
       * @param type $codigos
       * @return type
       */
      function consultarHistoricosReglamentoRetirados($codigo) {
          $variable=array('codigo'=>$codigo,
                           'anioper'=>$this->periodo[0]['ANO'].$this->periodo[0]['PERIODO']
                        );
            $cadena_sql=$this->sql->cadena_sql('consultarHistoricosReglamentoRetirados',$variable);
            return $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
      }

    function nombreMetodoDefecto(){

        echo 'este un bloque b&aacute;sico metodo por defecto';

      }

}
?>
