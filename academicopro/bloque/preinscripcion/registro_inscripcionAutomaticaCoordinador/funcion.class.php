<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/**
 * Funcion registroInscripcionAutomaticaCoordinador
 *
 * Esta clase se encarga de crear la logica y mostrar la interfaz de usuario
 *
 * @package Inscripciones
 * @subpackage Admin
 * @author Milton Parra
 * @version 0.0.0.1
 * Fecha: 14/01/2013
 *
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la clase abstracta funcionGeneral.class.php
 *
 * Esta clase contiene funciones que se utilizan durante el desarrollo del aplicativo.
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
/**
 * Clase funcion_registroInscripcionAutomaticaCoordinador
 *
 * Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
 * @package Inscripciones
 * @subpackage Coordinador
 */
class funcion_registroInscripcionAutomaticaCoordinador extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $codProyecto;
    private $nombreProyecto;
    private $ano;
    private $periodo;
    private $gruposProyecto;
    private $gruposProyectoTodos;
    private $equivalentes;
    private $preinscripcionesDemanda;
    private $preinscripcionesDemandaReprobados;
    private $preinscripciones;
    private $estudiantesFinal;
    private $estudiantesReprobados;
    private $proyectos;//contiene los codigos de proyectos asociados al coordinador
    private $arregloProyectos;//contiene el arreglo de los proyectos asociados al coordinador
            
                function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/preinscripcion/registro_inscripcionAutomaticaCoordinador".$configuracion["clases"]."/combinaciones.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/preinscripcion/registro_inscripcionAutomaticaCoordinador".$configuracion["clases"]."/horariosBinario.class.php");


        $this->configuracion=$configuracion;
        $this->procedimientos=new procedimientos();
        $this->cripto=new encriptar();
        $this->miCombinacion=new combinacionArreglos();
        //$this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registro_inscripcionAutomaticaCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        if(!$this->usuario)
        {
            echo "Sesi&oacute;n cerrada. Por favor ingrese nuevamente.";
            exit;
        }
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
        $this->horariosBinario=new horariosBinario();

    }
    
    function verificarInscripcion() {
        ?>
<script  language="javascript">
    var navegador = navigator.userAgent;
    function navega()
    {
                    if (navigator.userAgent.indexOf('MSIE') !=-1)
                    {
                        document.write("Por favor utilice <b>Firefox</b> para realizar este proceso.");
                        window.stop();
                    } else if (navigator.userAgent.indexOf('Firefox') !=-1)
                    {
                    } else if (navigator.userAgent.indexOf('Chrome') !=-1)
                    {
                        document.write("Por favor utilice <b>Firefox</b> para realizar este proceso.");
                        window.stop();
                    } else if (navigator.userAgent.indexOf('Opera') !=-1)
                    {
                        document.write("Por favor utilice <b>Firefox</b> para realizar este proceso.");
                        window.stop();
                    } else
                    {  
                        document.write("Por favor utilice <b>Firefox</b> para realizar este proceso.");
                        window.stop();
                    }
    }</script><?
        $this->codProyecto=$_REQUEST['codProyecto'];
        $this->arregloProyectos=$this->consultarProyectosCoordinador();
        $this->nombreProyecto=$_REQUEST['nombreProyecto'];
        $variable=array('codProyecto'=>$this->codProyecto,'nombreProyecto'=>$this->nombreProyecto);
        //si esta registrado el evento de ejecucion no pwrmite que se vuelva a iniciar el proceso.
        $inscripcion=$this->consultarInscripcionAutomatica();
        //verifica si hay datos registrados por el proceso
        $ejecucion=$this->consultarEjecucionInscripcion();
        if(is_array($inscripcion)&&!empty($inscripcion['FIN']))
        {
        ?>    
<tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px;">
            <?echo "<br>Este Proyecto Curricular ya ejecutó la Inscripci&oacute;n autom&aacute;tica.<br>";?>
        </td>
    </tr><?exit;
    }elseif(is_array($ejecucion))
    {
        ?>    
        
<tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px;">
            <?echo "<br>En este momento se est&aacute; ejecutando la Inscripci&oacute;n autom&aacute;tica para el Proyecto. Por favor espere.<br>";?>
        </td>
    </tr><?exit;
    }elseif(is_array($inscripcion)) {
        ?>    
        
    <tr class="centrar">
        <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
                <div class="navdiv">
                    <script type="text/javascript">
                        navega();
                    </script>
                </div>            
            <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $ruta="pagina=registro_inscripcionAutomaticaCoordinador";
                    $ruta.="&opcion=borrarInscripcion";
                    $ruta.="&codProyecto=".$variable['codProyecto'];
                    $ruta.="&nombreProyecto=".$variable['nombreProyecto'];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        ?>

            <a href="<?= $pagina.$ruta ?>" onclick="mostrarDiv()" style="color:#FF0000;">Ya existen datos de Inscripci&oacute;n autom&aacute;tica<br>¿Desea borrarlos y ejecutarla nuevamente?<br>
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/preinscripcion/borrar.png" width="50" height="50" border="0" alt="Inscripcion por Grupo"><br>Borrar datos y continuar
            </a>
        </td>

    </tr>
<?
        }else
            {
                $datosProyecto=$this->consultarDatosProyecto($variable);
                $variables=array_merge($variable,$datosProyecto[0]);
                $eventoCierre=$this->insertarEventoCierre($variables);
                $eventoEjecutar=$this->insertarEventoEjecutar($variables);
                $this->ejecutarInscripcion();
                $this->borrarEventoEjecutar();
            }
        
    }
    
    /**
     * Funcion que permite borrar los datos de una preinscripcion automatica previa y la ejecuta nuevamente
     */
    function borrarInscripcion() {
        $this->codProyecto=$_REQUEST['codProyecto'];
        $this->nombreProyecto=$_REQUEST['nombreProyecto'];
        $variable=array('codProyecto'=>$this->codProyecto,'nombreProyecto'=>$this->nombreProyecto);
        $inscripcion=$this->consultarInscripcionAutomatica();
        $ejecucion=$this->consultarEjecucionInscripcion();
        if(is_array($inscripcion)&&!empty($inscripcion['FIN']))
        {
            ?>
            <table class="sigma_borde centrar" width="100%">
                <caption class="sigma">INSCRIPCI&Oacute;N AUTOM&Aacute;TICA DEL PROYECTO CURRICULAR</caption>
                <tr class="centrar">
                    <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
                        <?echo $variable['nombreProyecto']?>
                    </td>
                </tr>
                <tr class="centrar">
                    <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px;">
                        <?echo "<br>Este Proyecto Curricular ya ejecutó la Inscripci&oacute;n autom&aacute;tica.<br>";?>
                    </td>
                </tr>
            </table>
            <?exit;
        }elseif(is_array($ejecucion))
        {
                ?>    

        <tr class="centrar">
                <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px;">
                    <?echo "<br>En este momento se est&aacute; ejecutando la Inscripci&oacute;n autom&aacute;tica para el Proyecto. Por favor espere.<br>";?>
                </td>
            </tr><?exit;
        }
        $datosProyecto=$this->consultarDatosProyecto($variable);
        $variables=array_merge($variable,$datosProyecto[0]);
        $eventoEjecutar=$this->insertarEventoEjecutar($variables);
        $this->borrarPreinscripcionesAutomática();
        $this->actualizarCuposGrupos();
        $this->actualizarEstadoEventoPreinscripcion();
        $this->borrarEventoEjecutar();
        $this->redireccionarFormularioInicial();
    }

    /**
     * Funcion que ejecuta el proceso de preinscripcion automatica para el proyecto
     */
    function ejecutarInscripcion()
    {   
/*        echo "Verificando Horarios del Proyecto...";echo '<img src="'.$this->configuracion['site'].  $this->configuracion['grafico'].'/preinscripcion/procesando.gif" width="400px" height="20">';
        flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
        ob_flush();
        echo "";
        flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
        ob_flush();*/
        
           ?> <head>
                <script language="javascript">
                //Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
                function callprogressRepro(vValor,vItem,vTotal){
                 document.getElementById("getprogressRepro").innerHTML = 'Preinscribiendo espacios reprobados '+vItem+' de '+vTotal+' estudiantes.  '+vValor ;
                 document.getElementById("getProgressBarFillRepro").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
                }
                function callprogress(vValor,vItem,vTotal){
                 document.getElementById("getprogress").innerHTML = 'Preinscribiendo espacios a estudiantes '+vItem+' de '+vTotal+' estudiantes.  '+vValor+'&nbsp;%';
                 document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
                }
                function callprogressHora(vValor,vItem,vTotal){
                 document.getElementById("getprogress").innerHTML = 'Verificando Horarios '+vItem+' de '+vTotal+' horarios.  '+vValor+'&nbsp;%';
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
                  <div class="ProgressBarText"><span id="getprogress"></span></div>
                  <div id="getProgressBarFill"></div>
                </div>
            </body><?
/*            $a=1;
            for ($a=1;$a<=100;$a++)
            {
        echo "<script>callprogressHora(".round($a).")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
                        flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
                        ob_flush();
                        sleep(2);
            }*/
        echo "<center><b>".$this->codProyecto."-".  $this->nombreProyecto."</b><br></center>";
        $i=0;
        $this->estudiantesFinal = array();
        $this->cargarHorariosBinarios();
        $estudiantes=$this->consultarEstudiantesClasificados();
        if(is_array($estudiantes))
        {
            $this->equivalentes = $this->consultarEquivalentes();
            $consultarHorarios=$this->consultarHorario(); 
            $this->gruposProyecto=$this->consultarGruposProyectos($consultarHorarios);
            if(is_array($this->gruposProyecto))
            {
                $this->preinscripcionesDemanda=$this->consultarPreinscripcionesDemandaProyecto();
/*                $this->buscarPreinscripcionesReprobados();
                $this->preinscripciones=$this->consultarPreinscripcionesProyecto();
                if(is_array($this->preinscripcionesDemandaReprobados)&&!empty($this->preinscripcionesDemandaReprobados))
                {
                    $this->buscarEstudiantesPreinsReprobados($estudiantes);
                    $preinsdemanda=$this->preinscripcionesDemanda;
                    $this->preinscripcionesDemanda=$this->preinscripcionesDemandaReprobados;
                    $a=1;
                    $numRegistros=count($this->estudiantesReprobados);
                    foreach($this->estudiantesReprobados as $datosEstudiante)
                        {
                            $porcentaje = $a * 100 / $numRegistros; //saco mi valor en porcentaje
                            echo "<script>callprogressRepro(".round($porcentaje).",".$a.",".$numRegistros.")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
                            flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
                            ob_flush();
                            $a++;
                            $this->estudiantesFinal[] = $this->ejecutarInscripcionEstudiante($datosEstudiante['codEstudiante'],$datosEstudiante['tipo'],$datosEstudiante['clasificacion']);
                            $i++;
                        }
                   $this->preinscripcionesDemanda=$preinsdemanda;
                }
                unset($this->estudiantesFinal);*/
                $this->preinscripciones=$this->consultarPreinscripcionesProyecto();
                $a=1;
                $numRegistros=count($estudiantes);
                $habilitar=0;
                foreach($estudiantes as $datosEstudiante)
                {
                    foreach ($this->preinscripcionesDemanda as $key2 => $preinscripciones) {
                        if($datosEstudiante['codEstudiante']==$preinscripciones['ESTUDIANTE'])
                        {
                            $habilitar=1;
                        }
                    }
                    if ($habilitar==1)
                    {
                        $porcentaje = $a * 100 / $numRegistros; //saco mi valor en porcentaje
                        echo "<script>callprogress(".round($porcentaje).",".$a.",".$numRegistros.")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
                        flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
                        ob_flush();
                        $a++;
                        $this->estudiantesFinal[] = $this->ejecutarInscripcionEstudiante($datosEstudiante['codEstudiante'],$datosEstudiante['tipo'],$datosEstudiante['clasificacion']);
                        $i++;
                    }
                }
                $this->enlaceVolverProyectos();
                $this->mostrarReporteInscripcion('1');

            }else{echo "No se encontraron grupos registrados para el Proyecto.";}
        }else{echo "No se encontraron estudiantes activos para el Proyecto.";}
      ?>
        </body>
        <?
  }

      
  /**
   * Funcion que ejecuta el proceso de inscripcon automatica por estudiante
   * @param type $codEstudiante
   * @param type $tipoEstudiante
   * @return type
   */  
    function ejecutarInscripcionEstudiante($codEstudiante,$tipoEstudiante,$clasificacion){
      //echo $codEstudiante."<br>";
        $totalInscritos=0;
        //Busca los espacios que se encuentran inscritos en acinspre, antes de publicar
        $espaciosInscritos=$this->buscarEspaciosPreinscritosEstudiante($codEstudiante);
        //busca los espacios que han sido preinscritos por el estudiante
        $espaciosPreinscritosDemanda=$this->buscarEspaciosPreinscritosDemandaEstudiante($codEstudiante);
        if(is_array($espaciosPreinscritosDemanda)&&!empty($espaciosPreinscritosDemanda))
        {
            //Consulta el ranking de espacios preinscritos y no preinscritos por demanda
            $espaciosRanking=$this->consultarRanking($espaciosPreinscritosDemanda);
            $gruposInscritas='';
            //si ya existen espacios preinscritos por automatica
            if(is_array($espaciosInscritos)&&!empty($espaciosInscritos))
            {
                //extrae los codigos de los espacios preinscritos por automatica
                $codigosEspaciosInscritos=$this->extraerColumna($espaciosInscritos, 'ASIGNATURA');
                //busca los grupos para los espacios que han sido preinscritos por automatica
                $gruposInscritas=$this->buscarGruposEspaciosInscritos($espaciosInscritos);
                //extrae los codigos de los espacios preinscritos por automatica que tiene grupos
                $codigosGruposEspaciosInscritos=$this->extraerColumna($gruposInscritas, 'codEspacio');
                //Halla si hay espacios inscritos por automatica que no tienen grupos
                $espaciosSinGrupo=array_diff($codigosEspaciosInscritos, $codigosGruposEspaciosInscritos);
                //En caso que para un preinscrito se haya aliminado el grupo, debe eliminarlo de los preinscritos
                if (is_array($espaciosSinGrupo)&&!empty($espaciosSinGrupo))
                {
                    foreach ($espaciosSinGrupo as $key => $espacio) {
                        $this->borrarEspacioPreinscripcionNoPublicada($codEstudiante,$espacio);
                    }
                }
            }
            if(is_array($espaciosPreinscritosDemanda))
            {
                //busca grupo y horario de los espacios preinscritos por demanda
                $gruposDeEspaciosPreinscritos=$this->buscarGruposDeEspaciosPreinscritos($espaciosPreinscritosDemanda, $espaciosRanking);
            }
            if(is_array($gruposDeEspaciosPreinscritos))
            {
                //si tiene preinscritos por demanda, busca los espacios por demanda que aun no tienen grupo
                $espaciosPreinscritosSinGrupo=$this->buscarEspaciosPreincritosSinGrupo($espaciosPreinscritosDemanda,$gruposDeEspaciosPreinscritos);
            }else
            {
                $espaciosPreinscritosSinGrupo=$espaciosPreinscritosDemanda;
            }
            //si no ha encontrado grupos para los espacios, busca equivalentes, para los estudiantes de horas
            if(is_array($espaciosPreinscritosSinGrupo) && !empty($espaciosPreinscritosSinGrupo) && $tipoEstudiante=='N')
            {
                $gruposEspaciosEquivalentes=$this->buscarGruposDeEspaciosEquivalentes($espaciosPreinscritosSinGrupo);
                if(is_array($gruposEspaciosEquivalentes)&&!empty($gruposEspaciosEquivalentes[0]))
                {
                    if (is_array($gruposDeEspaciosPreinscritos))
                    {
                        foreach ($gruposEspaciosEquivalentes as $gruposEspacio)
                        {
                            //$gruposDeEspaciosPreinscritos=array_merge($gruposDeEspaciosPreinscritos,$gruposEspaciosEquivalentes[0]);
                            if (is_array($gruposEspacio))
                            {
                                $gruposDeEspaciosPreinscritos=array_merge($gruposDeEspaciosPreinscritos,$gruposEspacio);
                            }
                        }
                    }else
                        {
                            foreach ($gruposEspaciosEquivalentes as $gruposEspacio)
                            {
                                if (is_array($gruposEspacio))
                                {
                                    foreach ($gruposEspacio as $cadaGrupo)
                                    {
                                        //$gruposDeEspaciosPreinscritos=array_merge($gruposDeEspaciosPreinscritos,$gruposEspaciosEquivalentes[0]);
                                        $gruposDeEspaciosPreinscritos[]=$cadaGrupo;
                                    }
                                }
                            }
                        }
                }
            }else                       
                {
                    
                }
                        //busca los espacios preinscritos por demanda que aun no se han preinscrito por auto
                        if(is_array($gruposDeEspaciosPreinscritos)){
                            if(isset($gruposInscritas)&&is_array($gruposInscritas))
                            {
                                $gruposDeEspaciosPreinscritos=$this->buscarEspaciosPreincritosSinInscribir($gruposDeEspaciosPreinscritos,$gruposInscritas);
                                //$gruposDeEspaciosPreinscritos=array_merge($gruposInscritas,$gruposDeEspaciosPreinscritos);
                            }
                            if(count($gruposDeEspaciosPreinscritos) > 1)
                            {
//este ajuste es para evitar sacar mas de un grupo de un solo espacio pero se generan errores al verificar horarios, por eso se comenta para que la llave de la tabla evite registro doble
//                                $numeroDeEspacios=$this->miCombinacion->valoresDistintos($gruposDeEspaciosPreinscritos,'codEspacio');
//                                if (count($numeroDeEspacios)>1)
//                                {
                                    $combinaciones=$this->miCombinacion->buscarCombinacionesHorario($gruposDeEspaciosPreinscritos,'codEspacio'); 
                                //}else
//                                    {
//                                        $rnd=rand(0, count($gruposDeEspaciosPreinscritos)-1);
//                                        $combinaciones[0]=$gruposDeEspaciosPreinscritos[$rnd];
//                                    }
                            }else
                                {
                                    $combinaciones[0]=$gruposDeEspaciosPreinscritos;
                                }
                        }
               if(isset($combinaciones)&&is_array($combinaciones)&&!empty($combinaciones[0]))
               {
                   foreach ($combinaciones as $horarios) {
                       if(is_array($gruposInscritas))
                       {
                            $horarios=array_merge_recursive($gruposInscritas,$horarios);
                       }
                       $horarioParaEvaluar[0]=$horarios;
                       $horario[]=$this->verificarHorarios($horarioParaEvaluar);
                   }
               }else
                   {
                   $horario='';
                   }
               $conteoHorario=count($horario);
               $horarioAInscribir = '';
               if (is_array($horario)&&$conteoHorario>1)
               {
                   $horarioAInscribir=$this->ponderarHorarios($horario);
               }elseif(is_array($horario))
                   {
                        $horarioAInscribir=$horario;
                        $horarioAInscribir=$this->limpiarArreglo($horarioAInscribir[0]);
                   }
               if(is_array($horarioAInscribir)){
                   $totalInscritos = $this->inscribirHorario($horarioAInscribir,$codEstudiante);
               }
       }else{}
       $reprobadosNoInscritos =$this->consultarReprobadosNoInscritos($espaciosPreinscritosDemanda,$totalInscritos);
       if(is_array($totalInscritos)){
           $cantidadTotalInscritos = count($totalInscritos);
       }elseif(is_array($espaciosInscritos))
           {
               $cantidadTotalInscritos = count($espaciosInscritos);
               $reprobadosNoInscritos =$this->consultarReprobadosNoInscritos($espaciosPreinscritosDemanda,$espaciosInscritos);
           }else
                {
                    $cantidadTotalInscritos = 0;
                }
       if(is_array($espaciosPreinscritosDemanda)){
           $cantidadTotalPreInscritos = count($espaciosPreinscritosDemanda);
       }else
           {
               $cantidadTotalPreInscritos = 0;
           }
       $resultadoProyecto['codEstudiante'] = $codEstudiante;
       $resultadoProyecto['totalPreinscritos'] = $cantidadTotalPreInscritos;
       $resultadoProyecto['totalInscritosAuto'] = $cantidadTotalInscritos;
       $resultadoProyecto['totalReprobadosNoInscritos'] = $reprobadosNoInscritos;

       return $resultadoProyecto;
    }
    /**
     * Funcion que consulta los estudiantes con la clasificación de prioridad
     * @param <array> $datos
     * @return <int>
     */
    function consultarEstudiantesClasificados() {
        $cadena_sql=$this->sql->cadena_sql("consultarEstudiantesClasificados",$this->codProyecto);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda");
        return $resultado;
    }
    
/**
 *funcion que consulta los horarios binarios de una carrera.
 * @return type 
 */
     function consultarHorario(){
             $variables=array('proyectos'=>$this->proyectos
                                );
        $cadena_sql=$this->sql->cadena_sql("buscarHorarios",$variables);  
        $resultado_grupos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda"); 
        return $resultado_grupos;
       }
       
   /**
    * Funcion que busca informacion de los grupos creados en el proyecto
    * La funcion consulta informacion de los grupos que existen en Oracle (grupo, espacio, cupo, inscritos, sede) y adiciona a esta informacion el horario en binario
    * @param type $codCarrera
    * @param type $consultarHorarios
    * @return type 
    */
     function consultarGruposProyectos($consultarHorarios){
        $arregloDisponibles='';
        $arregloDisponiblesSinCupo='';
        
	$variables=array('ano'=>$this->ano,
			 'periodo'=>$this->periodo,
                         'codCarrera'=>$this->codProyecto);
        $cadena_sql=$this->sql->cadena_sql("buscarGrupos",$variables);
        $resultado_grupos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            if(is_array($resultado_grupos)){
                foreach($resultado_grupos as $cupos){
                foreach($consultarHorarios as $horario){
                    $inscritos=(isset($cupos['INSCRITOS'])?$cupos['INSCRITOS']:0);
                    $disponibles=$cupos['CUPOS'];
                        if($inscritos < $disponibles && $horario['ID']==$cupos['ID']){
                        $arregloDisponibles[]=array( 'codGrupo'=>$cupos['ID'],
                                                    'codEspacio'=>$cupos['ASIGNATURA'],
                                                    'cupo'=>$disponibles,
                                                    'numeroInscritos'=>(isset($cupos['INSCRITOS'])?$cupos['INSCRITOS']:0),
                                                    'codSede'=>$horario['SEDE'],
                                                    'sedeDiferente'=>$horario['SEDE_DIF'],
                                                    'lunes'=>$horario['LUNES'],
                                                    'martes'=>$horario['MARTES'],
                                                    'miercoles'=>$horario['MIERCOLES'],
                                                    'jueves'=>$horario['JUEVES'],
                                                    'viernes'=>$horario['VIERNES'],
                                                    'sabado'=>$horario['SABADO'],
                                                    'domingo'=>$horario['DOMINGO']);                                                                              
                        }elseif($inscritos>=$disponibles && $horario['ID']==$cupos['ID']){
                        $arregloDisponiblesSinCupo[]=array( 'codGrupo'=>$cupos['ID'],
                                                    'codEspacio'=>$cupos['ASIGNATURA'],
                                                    'cupo'=>$disponibles,
                                                    'numeroInscritos'=>(isset($cupos['INSCRITOS'])?$cupos['INSCRITOS']:0),
                                                    'codSede'=>$horario['SEDE'],
                                                    'sedeDiferente'=>$horario['SEDE_DIF'],
                                                    'lunes'=>$horario['LUNES'],
                                                    'martes'=>$horario['MARTES'],
                                                    'miercoles'=>$horario['MIERCOLES'],
                                                    'jueves'=>$horario['JUEVES'],
                                                    'viernes'=>$horario['VIERNES'],
                                                    'sabado'=>$horario['SABADO'],
                                                    'domingo'=>$horario['DOMINGO']);                                                                              
                        }

                   }
                } 
                
            }else{
                echo "No existen grupos asociados al proyecto ";exit;
            }
            if(is_array($arregloDisponiblesSinCupo)&&!empty($arregloDisponiblesSinCupo))
            {
                $this->gruposProyectoTodos=array_merge($arregloDisponiblesSinCupo,$arregloDisponibles);
            }else
                {
                    $this->gruposProyectoTodos=$arregloDisponibles;
                }
        return $arregloDisponibles;
   }
   
/**
    *Funcion que consulta los espacios académicos inscritos por un estudiante en (acinspre)
 * 
 * @param type $codCarrera
 * @param type $codEstudiante
 * @return type 
 */
function consultarEspaciosInscritos($codEstudiante){
        $variables=array('codCarrera'=>$this->codProyecto,
                          'ano'=>$this->ano,
                          'periodo'=>$this->periodo,
                          'codEstudiante'=>$codEstudiante);
        $cadena_sql=$this->sql->cadena_sql("buscarInscritas",$variables);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");        
        return $resultado;
}

/**
 *funcion que consulta los espacios académicos preinscritos por demanda a un estudiante.
 * @param type $codCarrera
 * @param type $codEstudiante
 * @return type 
 */
function consultarEspaciosPreinscritosDemanda($codEstudiante){
   	$variables=array('ano'=>$this->ano,
			 'periodo'=>$this->periodo,
                         'codCarrera'=>$this->codProyecto,
                         'codEstudiante'=>$codEstudiante);
        $cadena_sql=$this->sql->cadena_sql("buscarPreinscritas",$variables);
        $resultado_grupos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");  
        return $resultado_grupos;
      
  }
  
/**
 *Consulta el ranking de los espacios académicos preinscritos.
 * @param type $consultarPreinscritos
 * @param type $codCarrera
 * @return type 
 */
  function consultarRanking($consultarPreinscritos){  
        $ranking='';
        //consulta ranking de espacios academicos para el proyecto
        $resultado_grupos=$this->consultarRankingEspacios();
        //si hay preinscritos
        if(is_array($consultarPreinscritos))
        {
            foreach ($consultarPreinscritos as $preinscritos)
            {
                foreach($resultado_grupos as $posicion)
                {
                    if($preinscritos['ASIGNATURA']==$posicion['ASIGNATURA'])
                    {
                        $preinscritosReprobado[]=array('codEspacio'=>$preinscritos['ASIGNATURA'],
                                                        'REPROBADO'=>$preinscritos['REPROBADOS'],
                                                        'RANKING'=>$posicion['POSICION']);

                    }else{}
//                    if($preinscritos['REPROBADOS']=='S' && $preinscritos['ASIGNATURA']==$posicion['ASIGNATURA'])
//                    {
//                        $preinscritosReprobado[]=array('codEspacio'=>$preinscritos['ASIGNATURA'],
//                                                        'REPROBADO'=>'S',
//                                                        'RANKING'=>$posicion['POSICION']);
//
//                    }elseif($preinscritos['REPROBADOS']=='N' && $preinscritos['ASIGNATURA']==$posicion['ASIGNATURA'])
//                        {    $preinscritosReprobado[]=array('codEspacio'=>$preinscritos['ASIGNATURA'],
//                                                            'REPROBADO'=>'N',
//                                                            'RANKING'=>$posicion['POSICION']);
//                        }else{}
                }
            }
            if (isset($preinscritosReprobado))
            {
                //organiza los espacios de acuerdo al ranking
                $ranking=$this->organizarRankingPreinscritos($preinscritosReprobado);
                //busca espacios que esten preinscritos pero no en el ranking
                $preinscritosSinRankin = $this->preinscritosSinRankin($consultarPreinscritos,$preinscritosReprobado);
                if(is_array($preinscritosSinRankin))
                {
                    $ranking = $this->adicionarARankin($preinscritosSinRankin,$ranking);
                }
            }else
                {
                    $ranking= $this->adicionarARankin($consultarPreinscritos,'');
                }
        }else{}
            
        return $ranking;
  }

/**
 *Funcion que organiza los espacios académicos según su ranking.
 * @param type $preinscritos
 * @return type 
 */
  function organizarRankingPreinscritos($preinscritos) {      
         
      foreach (  $preinscritos as $key => $fila) {            
            $reprobadosPreinscritos[$key]  = $fila['REPROBADO'];            
            $rankingPreinscritos[$key]  = $fila['RANKING'];            
        }
        
        array_multisort($reprobadosPreinscritos, SORT_DESC, $rankingPreinscritos, SORT_ASC, $preinscritos);

        return $preinscritos;
       
    }
    
 /**
  *Funcion que retorna los grupos con cupo.
  * @param type $consultarGrupos 
  */
  function buscarGrupoEspacioConCupo(){
     if(is_array($this->gruposProyecto)){
      foreach($this->gruposProyecto as $grupos){         
         if($grupos['cupo'] > $grupos['numeroInscritos']){  
             $disponibles[]=array('cupo'=>$grupos['cupo'], 
                                  'codGrupo'=>$grupos['codGrupo']);        
         }
      }
     }
     return $disponibles;
 }

 /**
  *Funcion que retorna los grupos con cupo.
  * @param type $consultarGrupos 
  */
  function buscarEspaciosPreinscritosEstudiante($codEstudiante){
     $inscripcionEstudiante='';
     if(is_array($this->preinscripciones)){
      foreach($this->preinscripciones as $key=>$preinscripcion){         
         if($preinscripcion['ESTUDIANTE']==$codEstudiante){  
             $inscripcionEstudiante[]=$preinscripcion;
             unset($this->preinscripciones[$key]);
         }
      }
     }
     return $inscripcionEstudiante;
 }

 /**
  *Funcion que retorna los grupos con cupo.
  * @param type $consultarGrupos 
  */
  function buscarEspaciosPreinscritosDemandaEstudiante($codEstudiante){
      $preinsEstudiante='';
     if(is_array($this->preinscripcionesDemanda)){
      foreach($this->preinscripcionesDemanda as $key=>$preinsDemanda){         
         if($preinsDemanda['ESTUDIANTE']==$codEstudiante){  
             $preinsEstudiante[]=$preinsDemanda;
             unset($this->preinscripcionesDemanda[$key]);
         }
      }
     }
     return $preinsEstudiante;
 }

 /**
  *funcion que busca los grupos de un espacio homologo
  * 
  * @param type $parejaPrincipalHomologo
  * @return string 
  */
 function buscarGruposHomologo($codEspacioHomologo,$espacio) {
    $arregloGrupoHomologo='';
    foreach($this->gruposProyecto as $grupo){
            if($grupo['codEspacio']==$codEspacioHomologo&&$grupo['cupo']>$grupo['numeroInscritos']){              
                $arregloGrupoHomologo[]=array(  'codGrupo'=>$grupo['codGrupo'],
                                                'codEspacio'=>$grupo['codEspacio'],
                                                'cupo'=>$grupo['cupo'],
                                                'numeroInscritos'=>$grupo['numeroInscritos'],
                                                'codSede'=>$grupo['codSede'],
                                                'sedeDiferente'=>$grupo['sedeDiferente'],
                                                'lunes'=>$grupo['lunes'],
                                                'martes'=>$grupo['martes'],
                                                'miercoles'=>$grupo['miercoles'],
                                                'jueves'=>$grupo['jueves'],
                                                'viernes'=>$grupo['viernes'],
                                                'sabado'=>$grupo['sabado'],
                                                'domingo'=>$grupo['domingo'],
                                                'creditos'=>(isset($espacio['CREDITOS'])?$espacio['CREDITOS']:0),
                                                'ht'=>(isset($espacio['HT'])?$espacio['HT']:''),
                                                'hp'=>(isset($espacio['HP'])?$espacio['HP']:''),
                                                'haut'=>(isset($espacio['HAUT'])?$espacio['HAUT']:''),
                                                'clasificacion'=>(isset($espacio['CLASIFICACION'])?$espacio['CLASIFICACION']:''),
                                                'nivel'=>(isset($espacio['NIVEL'])?$espacio['NIVEL']:'')
//                                                'creditos'=>'',
//                                                'ht'=>'',
//                                                'hp'=>'',
//                                                'haut'=>'',
//                                                'clasificacion'=>'',
//                                                'nivel'=>''
                                                );                
                                    }
                              
                        }
                        if(is_array($arregloGrupoHomologo))
                        {
                            $numeroEspacios=count($arregloGrupoHomologo);
                            array_unshift($arregloGrupoHomologo, $numeroEspacios);
                        }
                return $arregloGrupoHomologo;
 }
 /**
  * Funcion que busca los datos de los espacios Inscritos en la tabla acinspre.
  * @param type $consultarGrupos
  * @param type $consultarInscritas
  * @return type 
  */
 function buscarGruposEspaciosInscritos($consultarInscritas) {
                $arregloInscritas='';
                    foreach($this->gruposProyectoTodos as $grupos){
                            if(is_array($consultarInscritas)){
                                foreach($consultarInscritas as $inscritas){                          
                                    if($grupos['codEspacio']==$inscritas['ASIGNATURA'] && $grupos['codGrupo']==$inscritas['GRUPO']  ){              
                                        $arregloInscritas[]=array(  'codGrupo'=>$grupos['codGrupo'],
                                                                    'codEspacio'=>$grupos['codEspacio'],
                                                                    'codSede'=>$grupos['codSede'],
                                                                    'sedeDiferente'=>$grupos['sedeDiferente'],
                                                                    'inscrito'=>1,
                                                                    'lunes'=>$grupos['lunes'],
                                                                    'martes'=>$grupos['martes'],
                                                                    'miercoles'=>$grupos['miercoles'],
                                                                    'jueves'=>$grupos['jueves'],
                                                                    'viernes'=>$grupos['viernes'],
                                                                    'sabado'=>$grupos['sabado'],
                                                                    'domingo'=>$grupos['domingo'],
                                                                    );                
                                    }
                              }
                           }else{}
                        }
                return $arregloInscritas;
 }
 
 /**
  *Funcion que busca los grupos disponibles para los espacios academicos preinscritos 
  * 
  * @param type $consultarGrupos
  * @param type $consultarPreinscritos
  * @return type 
  */
 function buscarGruposDeEspaciosPreinscritos($consultarPreinscritos, $consultarRanking){     
    $arregloPreinscribir='';
    foreach($consultarRanking as $ranking){
        foreach($this->gruposProyecto as $grupos){  
            if(is_array($consultarPreinscritos)){               
            foreach($consultarPreinscritos as $preinscritos){
                if($grupos['codEspacio']==$ranking['codEspacio'] &&$grupos['codEspacio']==$preinscritos['ASIGNATURA'] && $grupos['cupo']>$grupos['numeroInscritos']){
                        $arregloPreinscribir[]=array(   'codGrupo'=>$grupos['codGrupo'],
                                                        'codEspacio'=>$grupos['codEspacio'],
                                                        'cupo'=>$grupos['cupo'],
                                                        'numeroInscritos'=>$grupos['numeroInscritos'],
                                                        'codSede'=>$grupos['codSede'],
                                                        'sedeDiferente'=>$grupos['sedeDiferente'],
                                                        'lunes'=>$grupos['lunes'],
                                                        'martes'=>$grupos['martes'],
                                                        'miercoles'=>$grupos['miercoles'],
                                                        'jueves'=>$grupos['jueves'],
                                                        'viernes'=>$grupos['viernes'],
                                                        'sabado'=>$grupos['sabado'],
                                                        'domingo'=>$grupos['domingo'],
                                                        'creditos'=>(isset($preinscritos['CREDITOS'])?$preinscritos['CREDITOS']:0),
                                                        'ht'=>$preinscritos['HT'],
                                                        'hp'=>$preinscritos['HP'],
                                                        'haut'=>$preinscritos['HAUT'],
                                                        'clasificacion'=>(isset($preinscritos['CLASIFICACION'])?$preinscritos['CLASIFICACION']:''),
                                                        'nivel'=>(isset($preinscritos['NIVEL'])?$preinscritos['NIVEL']:'')
                                                    ); 
                    }          
                  }                
                }                 
             }
        }
        if(is_array($arregloPreinscribir)){
        foreach ($arregloPreinscribir as $key=>$espacios) {
            if(!$espacios){
                unset($arregloPreinscribir[$key]);
            }
          }
        }
         return $arregloPreinscribir;
 }

    /**
  * Funcion que inscribe los espacios de acuerdo al horario seleccionado
  * @param type $horarioAInscribir 
  */
 function inscribirHorario($horarioAInscribir,$codEstudiante){
        $total='';
        $horarioAInscribir = $this->limpiarArregloFinal($horarioAInscribir);
        foreach ($horarioAInscribir as $key => $espacioAInscribir)
        {
            if(!isset($espacioAInscribir['inscrito'])||$espacioAInscribir['inscrito']!=1)
            {
                        $codEstudiante = $codEstudiante;
                        $codEspacio = $espacioAInscribir['codEspacio'];
                        $codGrupo = $espacioAInscribir['codGrupo'];
                        $creditos = $espacioAInscribir['creditos'];
                        $ht = $espacioAInscribir['ht'];
                        $hp = $espacioAInscribir['hp'];
                        $aut = $espacioAInscribir['haut'];
                        $cea = $espacioAInscribir['clasificacion'];
                        $inscritos = $espacioAInscribir['numeroInscritos'];
                        $sem=$espacioAInscribir['nivel'];
                        $resultadoInscripcion = $this->inscribirEspacio($codEstudiante,$codEspacio,$codGrupo,$creditos,$ht,$hp,$aut,$cea,$inscritos,$sem);
                        if($resultadoInscripcion==1){
                            $total[]=$espacioAInscribir;
                        }
            }
        }//fin foreach    
        return $total;
 }
 
    /**
     * Funcion que realiza la inscripcion de un espacio academico
      * Utiliza los metodos adicionarOracleInscripcion, buscarInscritos y actualizarInscritos
     * @param <array> $_REQUEST (pagina,opcion,codProyecto)
     */
    function inscribirEspacio($codEstudiante,$codEspacio,$codGrupo,$creditos,$ht,$hp,$aut,$cea,$inscritos,$sem){
        $datosRegistro= array(  'INS_CRA_COD'=>$this->codProyecto, 
                        'INS_EST_COD'=>$codEstudiante, 
                        'INS_ASI_COD'=>$codEspacio, 
                        'INS_GR'=>$codGrupo, 
                        'INS_SEM'=>$sem, 
                        'INS_ESTADO'=>'A', 
                        'INS_ANO'=>$this->ano, 
                        'INS_PER'=>$this->periodo, 
                        'INS_CRED'=>$creditos, 
                        'INS_NRO_HT'=>$ht, 
                        'INS_NRO_HP'=>$hp, 
                        'INS_NRO_AUT'=>$aut, 
                        'INS_CEA_COD'=>$cea);
        $inscrito = $this->adicionarOracleInscripcion($datosRegistro);
         if($inscrito>0)
            {   $datosEspacio = array(  'ano'=>$datosRegistro['INS_ANO'],
                                        'periodo'=>$datosRegistro['INS_PER'],
                                        'codProyecto'=>$datosRegistro['INS_CRA_COD'],
                                        'codEspacio'=>$datosRegistro['INS_ASI_COD'],
                                        'codGrupo'=>$datosRegistro['INS_GR']);
                $numeroInscritos = $inscritos+1;
                $this->actualizarInscritos($datosRegistro, $numeroInscritos);
             
                $variablesRegistro=array('usuario'=>$this->usuario,
                                            'evento'=>'57',
                                            'descripcion'=>'Realiza inscripción automatica',
                                            'registro'=>"cod_espacio-> ".$datosRegistro['INS_ASI_COD'].", grupo->".$datosRegistro['INS_GR'].", proyecto->".$datosRegistro['INS_CRA_COD'],
                                            'afectado'=>$datosRegistro['INS_EST_COD']);
            }else{
                $variablesRegistro=array('usuario'=>$this->usuario,
                                                'evento'=>'57',
                                                'descripcion'=>'Conexion Error Oracle al ejecutar inscripción automatica',
                                                'registro'=>"cod_espacio-> ".$datosRegistro['INS_ASI_COD'].", grupo->".$datosRegistro['INS_GR'].", proyecto->".$datosRegistro['INS_CRA_COD'],
                                                 'afectado'=>$datosRegistro['INS_EST_COD']);
                }
                 $this->procedimientos->registrarEvento($variablesRegistro);
                 return $inscrito;
    }
    
    function buscarPreinscripcionesReprobados() {
        foreach ($this->preinscripcionesDemanda as $key => $preinscripciones)
            {
                if ($preinscripciones['REPROBADOS']=='S')
                {
                    $this->preinscripcionesDemandaReprobados[]=$preinscripciones;
                    //unset($this->preinscripcionesDemanda[$key]);
                }
            }
    }
    
    function buscarEstudiantesPreinsReprobados($datosEstudiantes) {
        foreach ($this->preinscripcionesDemandaReprobados as $key => $reprobado) {
            foreach ($datosEstudiantes as $key1 => $estudiante) {
                if ($estudiante['codEstudiante']==$reprobado['ESTUDIANTE'])
                {
                    $this->estudiantesReprobados[]=$estudiante;
                    unset($datosEstudiantes[$key1]);
                }
            }
        }
        
    }
    
    /**
     *  Funcion que permite extraer una columna de un arreglo
     * @param type $arreglo
     * @param type $columna
     * @return type
     */
    function extraerColumna($arreglo, $columna) {
         foreach ($arreglo as $fila)
        {
            $arregloColumna[]=$fila[$columna];
        }
        return $arregloColumna;
    }
    
    /**
     * Funcion que adiciona un registro de preinscripcion automatica en Oracle
     * @param <array> $datos(INS_CRA_COD, INS_EST_COD, INS_ASI_COD, INS_GR, INS_ESTADO, INS_ANO, INS_PER, 
     * INS_CRED, INS_NRO_HT, INS_NRO_HP, INS_NRO_AUT, INS_CEA_COD, INS_TOT_FALLAS)
     */
    function adicionarOracleInscripcion($datos) {
        foreach ($datos as $key => $value) {
            if($value=='')
            {
                $datos[$key]='null';
            }
        }        
        $cadena_sql=$this->sql->cadena_sql("adicionar_inscripcion",$datos);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);

    }
    
    /**
     * Funcion que borra un registro de preinscripcion automatica en Oracle
     * @param type $codEstudiante
     * @param type $codEspacio
     * @return type
     */
    function borrarEspacioPreinscripcionNoPublicada($codEstudiante,$codEspacio) {
        $variables=array('codEstudiante'=>$codEstudiante,
                        'codEspacio'=>$codEspacio,
                        'codProyecto'=>$this->codProyecto,
                        'ano'=>  $this->ano,
                        'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("borrar_inscripcion",$variables);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }
    
        
    /**
     * Funcion que actualiza los inscritos
     * @param <array> $datosRegistro()
     * @param <int> $numeroInscritos
     * @return <array> $resultado
     */
    function actualizarInscritos($datosRegistro,$numeroInscritos){
        $this->actualizarOracleInscritos($datosRegistro['INS_ASI_COD'],$datosRegistro['INS_GR'],$datosRegistro['INS_CRA_COD'],$numeroInscritos);
        $this->actualizarArregloInscritos($numeroInscritos,$datosRegistro);
    }


    /**
     * Funcion que actualiza en Oracle la cantidad de inscritos de un curso
     * @param <int> $ano,$periodo,$codEspacio,$codGrupo,$codProyecto,$numeroInscritos
     * @return <int> 
     */
    function actualizarOracleInscritos($codEspacio,$codGrupo,$codProyecto,$numeroInscritos){
        $datos=array('ano'=>$this->ano,
                    'periodo'=>$this->periodo,
                    'codEspacio'=>$codEspacio,
                    'codGrupo'=>$codGrupo,
                    'codProyecto'=>$codProyecto,
                    'numeroInscritos'=>$numeroInscritos);
        $cadena_sql=$this->sql->cadena_sql("actualizar_cupo",$datos);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
        
    }
    

    /**
     * Funcion que actualiza el arreglo con la cantidad de inscritos del curso
     * @param type $numeroInscritos
     * @param type $datosGrupo 
     */
    function actualizarArregloInscritos($numeroInscritos,$datosGrupo){
        foreach ($this->gruposProyecto as $key => $grupos) {
            if($grupos['codEspacio'] == $datosGrupo['INS_ASI_COD'] && $grupos['codGrupo']== $datosGrupo['INS_GR'] ){
                $this->gruposProyecto[$key]['numeroInscritos']= $numeroInscritos;
            }
        }
    }

    /**
     * Funcion que verifica cruces para los horarios de los grupos que va a inscribir y retorna el horario sin cruces.
     * @param type $horarios
     * @return string
     */
    function verificarHorarios($horarios) {
        $horas='';
        $horarioAnterior=$horarios;
        unset($horarios);
        $horarios=$horarioAnterior[0];
        $conteo=count($horarios);
        $b=1;
        $horario='';
        if($conteo>1)
        {
            for($a=0;$a<$conteo;$a++){
                if (is_array($horarios[$a])&&isset($horarios[$a])&&isset($horarios[$b])&&is_array($horarios[$b]))
                {
                    $and=$this->compararArreglos($horarios[$a],$horarios[$b]);
                    if($and['lunes']==0&&$and['martes']==0&&$and['miercoles']==0&&$and['jueves']==0&&$and['viernes']==0&&$and['sabado']==0&&$and['domingo']==0)
                    {
                        $horas=$this->sumarArreglos($horarios[$a],$horarios[$b]);
                        $horarios[$b]=$horas;
                        unset ($horario);
                        $horario=$horarios[$b];
                        unset($horas);
                        $b++;
                    }else
                        {
                            $horarios[$b]=$horarios[$a];
                            $b++;
                        }
                }elseif(is_array($horarios[$a])&&isset($horarios[$a]))
                    {
                        $horarios[$b]=$horarios[$a];
                        unset ($horario);
                        $horario=$horarios[$b];
                        $b++;
                    }
            }
            if(count($horario)>17)
            {
                $horarioUnico[0]=$this->limpiarArreglo($horario);
                $semana=array('lunes'=>$horario['lunes'],
                            'martes'=>$horario['martes'],
                            'miercoles'=>$horario['miercoles'],
                            'jueves'=>$horario['jueves'],
                            'viernes'=>$horario['viernes'],
                            'sabado'=>$horario['sabado'],
                            'domingo'=>$horario['domingo']);
                unset($horario);
                $horario=array_merge($horarioUnico,$semana);
            }
        }elseif($conteo==1)
        {
		if(is_array($horarios))
		{
                    $horarios=$horarios[0];
		$horarioUnico[0]=$this->limpiarArreglo($horarios);
                $semana=array('lunes'=>$horarios['lunes'],
                            'martes'=>$horarios['martes'],
                            'miercoles'=>$horarios['miercoles'],
                            'jueves'=>$horarios['jueves'],
                            'viernes'=>$horarios['viernes'],
                            'sabado'=>$horarios['sabado'],
                            'domingo'=>$horarios['domingo']);
                unset($horarios);
                $horario=array_merge($horarioUnico,$semana);
		}
        }else
            {
                $horario='';
            }
            return $horario;
    }
    
    /**
     * Funcion que compara el horario entre dos arreglos de espacios
     * @param type $arreglo1
     * @param type $arreglo2
     * @return type
     */
    function compararArreglos($arreglo1,$arreglo2) {
        $resultado=array('lunes'=>$arreglo1['lunes']&$arreglo2['lunes'],
                    'martes'=>$arreglo1['martes']&$arreglo2['martes'],
                    'miercoles'=>$arreglo1['miercoles']&$arreglo2['miercoles'],
                    'jueves'=>$arreglo1['jueves']&$arreglo2['jueves'],
                    'viernes'=>$arreglo1['viernes']&$arreglo2['viernes'],
                    'sabado'=>$arreglo1['sabado']&$arreglo2['sabado'],
                    'domingo'=>$arreglo1['domingo']&$arreglo2['domingo']);
        return $resultado;
    }

    /**
     * Funcion que suma los horarios de dos arreglos de espacios
     * @param type $arreglo1
     * @param type $arreglo2
     * @return type
     */
    function sumarArreglos($arreglo1,$arreglo2) {
        $resultado=array('lunes'=>$arreglo1['lunes']|$arreglo2['lunes'],
                    'martes'=>$arreglo1['martes']|$arreglo2['martes'],
                    'miercoles'=>$arreglo1['miercoles']|$arreglo2['miercoles'],
                    'jueves'=>$arreglo1['jueves']|$arreglo2['jueves'],
                    'viernes'=>$arreglo1['viernes']|$arreglo2['viernes'],
                    'sabado'=>$arreglo1['sabado']|$arreglo2['sabado'],
                    'domingo'=>$arreglo1['domingo']|$arreglo2['domingo']);
        $arreglo1=$this->limpiarArreglo($arreglo1);
        $arreglo2=$this->limpiarArreglo($arreglo2);
        if (isset($arreglo1[0]))
        {
            array_push($arreglo1,$arreglo2);
        }else
        {
            $arreglo1=array($arreglo1, $arreglo2);
        }
        $resultado=array_merge($arreglo1,$resultado);
        return $resultado;
    }
          
    /**
     * Función que elimina el horario de un arreglo. Retorna la información de espacios academicos
     * @param type $arreglo
     * @return type
     */
    function limpiarArreglo($arreglo) {
        if(isset($arreglo['lunes']))
        {
            unset ($arreglo['lunes'],$arreglo['martes'],$arreglo['miercoles'],$arreglo['jueves'],$arreglo['viernes'],$arreglo['sabado'],$arreglo['domingo']);
        }
        return $arreglo;
    }
    
    /**
     * Funcion que permite hacer la ponderacion de los horarios para seleccionar el que se va a inscribir
     * @param type $horariosPosibles
     * @return type
     */
    function ponderarHorarios($horariosPosibles) {
        $totalHorarios=count($horariosPosibles);
        $horariosPosibles=$this->contarEspaciosPorHorario($horariosPosibles);
        $horariosPosibles=$this->contarHorasYHuecosPorHorario($horariosPosibles);
        $ea=$ht=$hh=$h=0;
        $horariosPosibles=$this->organizarArreglosHorarios($horariosPosibles);
        foreach ($horariosPosibles as $horario) {
            if ($horariosPosibles[0]['totalespacios']==$horario['totalespacios'])
	    {
		$ea++;
	    }
        }
        if($ea>1)
        {
            for($a=0;$a<$ea;$a++) {
                if ($horariosPosibles[0]['horasTotales']==$horariosPosibles[$a])
		{
		     $ht++;
		}
            }
            if($ht>1)
            {
                for($b=0;$b<$ht;$b++) {
                    if ($horariosPosibles[0]['horasHuecos']==$horariosPosibles[$b])
		    {
			$hh++;
		    }
                }
                if($hh>1)
                {
                    for($c=0;$c<$hh;$c++) {
                        if ($horariosPosibles[0]['huecos']==$horariosPosibles[$c])
			{
			     $h++;
			}
                    }
                    if($h>1)
                    {
                        $rnd=rand(0,$h);
                        $horarioInscribir=$horariosPosibles[$rnd-1];
                    }else
                        {
                            $horarioInscribir=$horariosPosibles[0];
                        }
                }else
                    {
                        $horarioInscribir=$horariosPosibles[0];
                    }
            }else
                {
                    $horarioInscribir=$horariosPosibles[0];
                }
        }else
            {
                $horarioInscribir=$horariosPosibles[0];
            }
            return $horarioInscribir;
    }
    
    /**
     * Funcion que retorna el numero de espacios que tiene un horario
     * @param type $horarios
     * @return type
     */
    function contarEspaciosPorHorario($horarios) {
        foreach ($horarios as $key=> $cadaHorario) {
            if(isset($cadaHorario['codEspacio']))
            {
                $totalEspacios['totalespacios']=1;
            }else
                {
                    $totalEspacios['totalespacios']=count($cadaHorario)-7;
                }
            $horarios[$key]=array_merge($horarios[$key], $totalEspacios);
        }
        return $horarios;
    }
    
    /**
     * Funcion que permite contar el numero de horas y huecos de un horario
     * @param type $horario
     * @return type
     */
    function contarHorasYHuecosPorHorario($horario) {
        foreach ($horario as $key=> $cadaHorario) {
            $unos=$cadaHorario['lunes'].$cadaHorario['martes'].$cadaHorario['miercoles'].$cadaHorario['jueves'].$cadaHorario['viernes'].$cadaHorario['sabado'].$cadaHorario['domingo'];
            $total['horasTotales']=substr_count($unos, '1');
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['lunes']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['martes']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['miercoles']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['jueves']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['viernes']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['sabado']);
            $ceros[]=$this->contarVaciosEnHorario($cadaHorario['domingo']);
            $cuenta['huecos']=$cuenta['horasHuecos']=0;
            foreach ($ceros as $conteo) {
                $cuenta['horasHuecos']+=$conteo['cantidadHorasVacias'];
                $cuenta['huecos']+=$conteo['cantidadVacios'];
            }
            $horario[$key]=array_merge($horario[$key],$total,$cuenta);
            unset ($unos,$total,$cuenta,$ceros);
        }
        return $horario;
        
    }
    
    /**
     * Funcion que permite organizar los arreglos de horarios de acuerdo a los criterios establecidos
     * @param type $horarios
     * @return type
     */
    function organizarArreglosHorarios($horarios) {
        foreach ($horarios as $key => $fila) {
            $numeroEspacios[$key]  = $fila['totalespacios'];             
            $numeroHoras[$key]  = $fila['horasTotales'];             
            $horasHuecos[$key]  = $fila['horasHuecos'];             
            $numeroHuecos[$key]  = $fila['huecos'];
        }
        array_multisort($numeroEspacios,SORT_DESC,$numeroHoras,SORT_DESC,$horasHuecos,SORT_ASC,$numeroHuecos,SORT_DESC,$horarios);
        return $horarios;
    }    
    


   /**
     * Funcion que cuenta la cantidad de vacios o huecos y la cantidad de
horas que se encuentra en un horario
     * @param <string> $horario_bin
     * @return <array> $resultado
     */

    function contarVaciosEnHorario($horario_bin){
        $resultado = array();
        //buscamos la primer hora y la ultima que se encuentran ocupadas
        $pos_inicial = strpos($horario_bin, '1');
        $pos_final = strrpos($horario_bin, '1');
        if($pos_inicial===false && $pos_final===false){
            // No encuentra horas ocupadas
        }else{
            //extraemos la cadena desde la primer hora ocupada hasta la ultima hora ocupada
            $cantidad = ($pos_final - $pos_inicial)+1;
            $cadena = substr($horario_bin, $pos_inicial,$cantidad);
            $longitud = strlen($cadena);
            $horas = 0;
            $vacio = 0;
            $digito_anterior = 1;
            if($longitud > 1){
                    //recorremos la cadena y evaluamos cuantas horas y vacios existen
                    for($i=0; $i<$longitud; $i++){
                        $digito = substr($cadena, $i,1);
                        if($digito==0 ){
                            $horas++;
                            if($digito!=$digito_anterior){
                                $vacio++;
                            }
                        }
                        $digito_anterior=$digito;
                    }
            }
            $resultado['cantidadVacios'] = $vacio;
            $resultado['cantidadHorasVacias'] = $horas;
        }
        if(empty($resultado))
        {
            $resultado['cantidadVacios'] = 0;
            $resultado['cantidadHorasVacias'] = 0;
        }
        return $resultado;
    }    

     /**
     * Función que elimina el horario de un arreglo y totales. Retorna la información de espacios academicos
     * @param type $arreglo
     * @return type
     */
    function limpiarArregloFinal($arreglo) {
        $arreglo = $this->limpiarArreglo($arreglo);
        unset ($arreglo['totalespacios'],$arreglo['horasTotales'],$arreglo['horasHuecos'],$arreglo['huecos']);
        return $arreglo;
}

    /**
     * Funcion que verifica que espacios reprobados no ahn sido inscritos para un estudiante
     * @param type $preinscritos
     * @param type $inscritos
     * @return int
     */
    function consultarReprobadosNoInscritos($preinscritos,$inscritos){
        $reprobadosNoInscritos = 0;
        if(is_array($preinscritos)){
            foreach ($preinscritos as $key => $espacio) {
                $band=0;
                if(is_array($inscritos)){
                    foreach ($inscritos as $key2 => $inscrito) {
                        if($espacio['ASIGNATURA']==(isset($inscrito['codEspacio'])?$inscrito['codEspacio']:0)||$espacio['ASIGNATURA']==(isset($inscrito['ASIGNATURA'])?$inscrito['ASIGNATURA']:0)){
                            $band=1;
                        }
                    }
                }
                if($band==0 && $espacio['REPROBADOS']=='S'){
                    $reprobadosNoInscritos++;
                }

            }
        }
        return $reprobadosNoInscritos;
    }
    
    /**
     * Funcion que arma el reporte de inscripcion
     * @param type $estudiantes
     */
    function mostrarReporteInscripcion($tipo=''){
        $e=1;
        switch ($tipo) {
            case '1':
                $tabla='acinspre';
                break;

            case '2':
                $tabla='acinspre';
                break;

            default:
                break;
        }
        
/*        $totalEstudiantes = $this->contarEstudiantesInscritos($estudiantes);
        $totalInscritos = $this->contarInscritos($estudiantes);
        $totalPerdidosNoInscritos = $this->contarPerdidosNoInscritos($estudiantes);
        echo "<br>Total estudiantes con inscripciones ".$tipo." ".$totalEstudiantes;
        echo "<br>Total espacios inscritos ".$totalInscritos;
        echo "<br>Total espacios reprobados no inscritos ".$totalPerdidosNoInscritos."<br>";
        $this->mostrarListadoEstudiantes($estudiantes);*/
        $estudiantes=$this->consultarEstudiantesClasificados();
        $totalClasificados=count($estudiantes);
        $inscripciones=$this->consultarPreinscripciones();
        $totalPreinscripciones=count($inscripciones);
        $totalInscritos=$this->consultarInscripcionesProyecto();
        $preinscritos=$this->consultarPreinscripcionesDemandaProyecto();
        $totalPrenscritos=count($preinscritos);
        $totalNoInscritos=$totalPrenscritos-$totalInscritos;
        echo "<br>...:: <b>Resultados del Proceso de Inscripci&oacute;n Autom&aacute;tica</b> ::...";
        echo "<br>Total estudiantes con Preinscripciones por demanda: ".$totalPreinscripciones;
        echo "<br>Total espacios Preinscritos por demanda en el proyecto: ".$totalPrenscritos;
        echo "<br>Total estudiantes procesados: ".$totalClasificados;
        echo "<br>Total espacios inscritos: ".$totalInscritos;
        echo "<br>Total espacios no inscritos: ".$totalNoInscritos." (incluye espacios de estudiantes en vacaciones).<br><br>";
        if($totalClasificados>=1)
        {
            echo "<table class='contenidotabla' width='80%' border='0' align='center' cellpadding='4 px' cellspacing='0px' >";
            echo "<thead class='sigma'>
                <th class='niveles centrar' width='10'>No.</th>
                <th class='niveles centrar' width='15'>C&oacute;digo Estudiante</th>
                <th class='niveles centrar' width='15'>Preinscritas por demanda</th>
                <th class='niveles centrar' width='15'>Inscritas</th>
                <th class='niveles centrar' width='15'>% Preinscritas</th>
                <th class='niveles centrar' width='20'>Observaci&oacute;n</th>
                </thead>";
            foreach ($estudiantes as $key => $datos)
            {
                $datos['totalPreinscritos']=0;
                foreach ($inscripciones as $key2 => $total)
                {
                    if($datos['codEstudiante']==$total['CODIGO'])
                    {
                        $datos['totalPreinscritos']=$total['TOTAL'];
                    }
                }
                $datos['totalInscritosAuto']=0;
                if($datos['totalPreinscritos']>0){
                    $datos['totalInscritosAuto']=$this->consultarRegistrosProceso($tabla, $datos['codEstudiante']);
                    $porcentaje = number_format((($datos['totalInscritosAuto']/$datos['totalPreinscritos'])*100), 0)." %";
                    $observacion=" ";
                }else{
                    $porcentaje ="-";
                    $observacion="No realiz&oacute; preinscripci&oacute;n por demanda";
                }
                echo "<tr>";
                echo "<td class='cuadro_plano centrar' >".$e."</td>";$e++;
                echo "<td class='cuadro_plano centrar' >".$datos['codEstudiante']."</td>";
                echo "<td class='cuadro_plano centrar' >".$datos['totalPreinscritos']."</td>";
                echo "<td class='cuadro_plano centrar' >".$datos['totalInscritosAuto']."</td>";
                echo "<td class='cuadro_plano centrar' >".$porcentaje."</td>";
                echo "<td class='cuadro_plano' >".$observacion;
                echo "</tr>";
            }
        }
         echo "</table>";        
    }
    
    /**
     * Funcion que cuenta el numero total de espacios preinscritos por automatica
     * @param type $estudiantes
     * @return type
     */
    function contarInscritos($estudiantes){
        $total=0;
        foreach ($estudiantes as $key => $datos) {
            $total = $total + $datos['totalInscritosAuto'];
        }
        return $total;
    }

    /**
     * Funcion que cuenta el numero de espacios reprobados que no fueron inscritos para un estudiante
     * @param type $estudiantes
     * @return type
     */
    function contarPerdidosNoInscritos($estudiantes){
        $total=0;
        foreach ($estudiantes as $key => $datos) {
            if($datos['totalPreinscritos']>0){
            $total = $total + $datos['totalReprobadosNoInscritos'];
            }
        }
        return $total;
    }

   /**
    * Funcion que cuenta el numero de estudiantes con inscripciones
    * @param type $estudiantes
    * @return int
    */
    function contarEstudiantesInscritos($estudiantes){
        $total=0;
        foreach ($estudiantes as $key => $datos) {
            if($datos['totalInscritosAuto']>0){
                $total++;
            }
        }
        return $total;
    }
    
    /**
     * Funcion que presenta el listado de estudiantes procesados con numero de preinscritas por demanda, inscritas por auto y porcentaje de inscripcion
     * @param type $estudiantes
     */
    function mostrarListadoEstudiantes($estudiantes){
         echo "<table class='contenidotabla' width='80%' border='0' align='center' cellpadding='4 px' cellspacing='0px' >";
         echo "<thead class='sigma'>
                <th class='niveles centrar' width='15'>C&oacute;digo Estudiante</th>
                <th class='niveles centrar' width='15'>Preinscritas por demanda</th>
                <th class='niveles centrar' width='15'>Inscritas</th>
                <th class='niveles centrar' width='15'>% Preinscritas</th>
                <th class='niveles centrar' width='20'>Observaci&oacute;n</th>
                </thead>";
        foreach ($estudiantes as $key => $datos) {
            if($datos['totalPreinscritos']>0){
                $porcentaje = number_format((($datos['totalInscritosAuto']/$datos['totalPreinscritos'])*100), 0)." %";
                $observacion=" ";
            }else{
                $porcentaje ="-";
                $observacion="No realiz&oacute; preinscripci&oacute;n por demanda";
            }
            echo "<tr>";
            echo "<td class='cuadro_plano centrar' >".$datos['codEstudiante']."</td>";
            echo "<td class='cuadro_plano centrar' >".$datos['totalPreinscritos']."</td>";
            echo "<td class='cuadro_plano centrar' >".$datos['totalInscritosAuto']."</td>";
            echo "<td class='cuadro_plano centrar' >".$porcentaje."</td>";
            echo "<td class='cuadro_plano' >".$observacion;
            echo "</tr>";
        }
         echo "</table>";
    }

    /**
     * Funcion que presenta un mensaje de espera al usuario
     */
    function mostrarMensajeDeEspera(){
        ?>
            <center><br><div id="cargando" style="z-index:2;background-color: #ffffff; layer-background-color: #336699; border: 0px double #000000; font-size: 24px; color:#CCCCCC; height:40px; width:600px ">
                <p align="center" style="margin-top: 0; margin-bottom: 0">
		    <b>
		        <img style="width: 25px;  height: 25px; border-width: 0px;" src="<? echo $this->configuracion["host"].$this->configuracion["site"].$this->configuracion["grafico"] ?>/preinscripcion/ajax-loader.gif">
                        <font face="Verdana" size="3" color="#CC0000">
                            <?
  
                                echo " Ejecutando proceso ... ";
                            
                            ?>
                        </font>
		    </b>
		    </p>
                   
		</div>
                 </center>
         <?
    
    }//fin funcion mostrarMensajeDeEspera
    
    /**
     * 
     * @param type $numeroEstudiantes
     */
    function redireccionarFormularioInicial() {
        $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_inscripcionAutomaticaCoordinador";
        $variable.="&opcion=parametrosInscripcionAuto";   
        $variable.="&codProyecto=".$this->codProyecto;
        $variable.="&nombreProyecto=".$this->nombreProyecto;
        include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
        echo "<script>location.replace('".$pagina.$variable."')</script>"; 
    }
    
         /**
     * Funcion que muestra el enlace para regresar al menú inicial de Preinscricpion por demanda
     */
    function enlaceVolverProyectos() {
        $pagina = $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_inscripcionAutomaticaCoordinador";
        $variable.="&opcion=parametrosInscripcionAuto";
        $variable.="&codProyecto=".$this->codProyecto;
        $variable.="&nombreProyecto=".$this->nombreProyecto;
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
       echo "<br><div align='right' > <a href='".$pagina.$variable."' class='enlaceHomologaciones'>.:: Volver</a></div><br>";
    }
    
    /**
     *Este metodo resta a los espacio preinscritos por demanda los espacios que se les encuenta grupo
     * con esto se obtiene los espacios que preinscritos que no tiene grupos
     * 
     * 
     * @param type $consultarPreinscritos
     * @param type $porInscribir espacios con grupo
     * @return type espacios preinscritos sin grupo
     */
    function buscarEspaciosPreincritosSinGrupo($preinscritos,$espaciosConGrupo){
        $codPreinscritos=$this->extraerColumna($preinscritos, 'ASIGNATURA');
        $codEspaciosConGrupo=$this->extraerColumna($espaciosConGrupo, 'codEspacio');
        $espaciosSinGrupo=array_diff($codPreinscritos, $codEspaciosConGrupo);
        foreach ($espaciosSinGrupo as $key=>$espacio)
        {
            foreach ($preinscritos as $datosEspacio) {
                if ($espacio==$datosEspacio['ASIGNATURA'])
                {
                    $espaciosSinGrupo[$key]=$datosEspacio;
                }
            }
        }
        return $espaciosSinGrupo;
    }
 
    /**
     * Funcion que busca los espacios equivalentes con homologacion uno a uno
     * @return type
     */
    function consultarEquivalentes(){
        $cadena_sql=$this->sql->cadena_sql("consultarEquivalentes",$this->codProyecto);
        $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
        return $resultado;
    }
 
    /**
     * Esta funcion permite consultar los espacios que se han preinscrito pero que no estan en el ranking
     * @param type $consultarPreinscritos
     * @param type $preinscritosReprobado
     * @return type 
     */
    function preinscritosSinRankin($consultarPreinscritos,$preinscritosReprobado){
        $faltantes='';
        foreach ($consultarPreinscritos as $key => $preinscritos) {
            $existe=0;
            foreach ($preinscritosReprobado as $key2 => $espacios) {
                if($preinscritos['ASIGNATURA']==$espacios['codEspacio']){
                    //si esta entre el ranking lo marca
                    $existe=1;
                }
            }
            //si no esta entre el ranking lo adiciona
            if($existe==0){
                $faltantes[]=$preinscritos;
            }
        }
        return $faltantes;
    }
   
    /**
     *Adiciona los espacios preinscritos que no estan en la tabla ranking
     * 
     * @param type $preinscritosSinRankin
     * @param type $rankin
     * @return type 
     */
    function adicionarARankin($preinscritosSinRankin,$rankin){        
        if(is_array($rankin))
        {
            $ultimoRegistro=end($rankin);
            $mayorRanking=$ultimoRegistro['RANKING'];
        }else
            {
                $rankin=array();
                $mayorRanking=0;
            }
        foreach ($preinscritosSinRankin as $preinscritos) {
                        $mayorRanking=$mayorRanking+1;
                    
            $espacios[]=array('codEspacio'=>$preinscritos['ASIGNATURA'],
                        'REPROBADO'=>$preinscritos['REPROBADOS'],
                        'RANKIN'=> ''.$mayorRanking.''
                );
        }    
        $rankinCompleto=array_merge_recursive($rankin, $espacios);
        return $rankinCompleto;
    }
    
    /**
     * Funcion que busca grupos para los espacios equivalentes
     * @param type $espacios
     * @return type
     */
    function buscarGruposDeEspaciosEquivalentes($espacios){
         $equivalentes='';
         $parejaConGrupo='';
         $equivalenteConGrupo='';
            foreach ($espacios as $espacio) {
                $equivalentes[]=$this->buscarEquivalentes($espacio);       
            }
            return $equivalentes;
    }
    
    /**
     *Este metodo resta a los espacio preinscritos por demanda los espacios que se encuentran inscritos
     * @param type $espaciosPreinscritos
     * @param type $inscritos
     * @return type 
     */
    function buscarEspaciosPreincritosSinInscribir($espaciosPreinscritos,$espaciosInscritos){
        $faltantes = '';
        foreach ($espaciosPreinscritos as $key => $preinscritos) {
            $existe=0;
            foreach ($espaciosInscritos as $key2 => $inscritos) {
                if($preinscritos['codEspacio']==$inscritos['codEspacio']){
                    $existe=1;
                }
            }
            if($existe==0){
                $faltantes[]=$preinscritos;
            }
        }
        return $faltantes;
    }

    
    /**
     *busca los espacios equivalentes de un espacio academico (actablahomologacion)
     * Retorna el arreglo con la relacion de espacios principales y equivalentes
     * 
     * @param type $codEspacio
     * @return type 
     */
    function buscarEquivalentes($codEspacio){
//        if (isset($codEspacio['ASIGNATURA']))
//        {}else{$codEspacio=array('ASIGNATURA'=>$codEspacio);}
        $resultado='';
        $gruposEspacioEquivalente='';
        if(is_array($this->equivalentes))
        {
            foreach ($this->equivalentes as $equivalente) {
                //para el espacio principal busca los homologos
                if($equivalente['HOM_ASI_COD_PPAL']==$codEspacio['ASIGNATURA']){
                    //crea arreglo de ppal con homologo                
                    $resultado[]=$equivalente;
                    $gruposEspacioEquivalente[]=$this->buscarGruposHomologo($equivalente['HOM_ASI_COD_HOM'],$codEspacio);                
                }
            }
        }
        if(is_array($gruposEspacioEquivalente)&&!empty($gruposEspacioEquivalente[0]))
        {
            $gruposHomologosOrdenados=$this->ordenarGruposHomologos($gruposEspacioEquivalente);
            unset($gruposHomologosOrdenados[0][0]);
            return $gruposHomologosOrdenados[0];//pasa los grupos del espacio homologo que tiene mayor cantidad de grupos
        }
        return '';
    }
    
/**
 *Funcion que organiza los espacios académicos según su ranking.
 * @param type $preinscritos
 * @return type 
 */
  function ordenarGruposHomologos($gruposHomologos) {
      foreach (  $gruposHomologos as $key => $fila) {            
                    $cantidadGrupos[$key]  = $fila[0];                          
        }
        array_multisort($cantidadGrupos, SORT_DESC, $gruposHomologos);
        return $gruposHomologos;
    }    
    
/**
*Función que carga la tabla de horarios binarios para el proyecto
*/
   function cargarHorariosBinarios(){
       //borra los registros de horarios binarios del proyecto
       $borrado=$this->vaciarTablaHorariosBinarios();
       //Consulta y carga los horarios binarios del proyecto desde Oracle a MySQL
       foreach ($this->arregloProyectos as $key => $value) {
           $resultadoHorarios =$this->horariosBinario->ConsultarHorarios($value['CRA_COD']);
       }
    }    
    
  /**
   * Funcion que borra de la tabla de horarios los del proyecto
   * @return int 
   */
    function vaciarTablaHorariosBinarios() {
        
            $cadena_sql=$this->sql->cadena_sql("vaciarTablaHorariosBinarios",  $this->proyectos);
            $this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"");
            return $this->totalAfectados($this->configuracion, $this->accesoGestion);
    }
    
    /**
     * Esta funcion permite publicar las inscripciones de los estudiantes del proyecto y registrar los eventos
     */
    function publicarInscripcion() {
        $variables=$_REQUEST;
        $this->codProyecto=$_REQUEST['codProyecto'];
        $this->nombreProyecto=$_REQUEST['nombreProyecto'];
        $inscripcion=$this->consultarInscripcionAutomatica();
        $ejecucion=$this->consultarEjecucionInscripcion();
        if(is_array($inscripcion)&&!empty($inscripcion['FIN']))
        {
            ?>
            <table class="sigma_borde centrar" width="100%">
                <caption class="sigma">INSCRIPCI&Oacute;N AUTOM&Aacute;TICA DEL PROYECTO CURRICULAR</caption>
                <tr class="centrar">
                    <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px">
                        <?echo $variable['nombreProyecto']?>
                    </td>
                </tr>
                <tr class="centrar">
                    <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px;">
                        <?echo "<br>Este Proyecto Curricular ya ejecutó la Inscripci&oacute;n autom&aacute;tica.<br>";?>
                    </td>
                </tr>
            </table>
            <?exit;
        }elseif(is_array($ejecucion))
        {
                ?>    

        <tr class="centrar">
                <td colspan="2" class="sigma centrar" width="50%" style="font-size: 15px;">
                    <?echo "<br>En este momento se est&aacute; ejecutando la Inscripci&oacute;n autom&aacute;tica para el Proyecto. Por favor espere.<br>";?>
                </td>
            </tr><?exit;
        }
        
        if(is_array($inscripcion)&&!empty($inscripcion['FIN']))
        {
            $this->redireccionarFormularioInicial();
            exit;
        }
        $inscripcion=$this->consultarNumeroInscripcionesProyecto($variables);
        if($inscripcion[0][0]==0)
        {
            $this->redireccionarFormularioInicial();
            exit;
        }
        $publicar=$this->publicarInscripcionesProyecto($variables);
        $datosProyecto=$this->consultarDatosProyecto($variables);
        $variables=array_merge($variables,$datosProyecto[0]);
        $eventoCierre=$this->actualizarEventoFinPreinscripcion($variables);
        $eventosCalendario=$this->consultarEventosCalendario();
        $insertarEventosInscripciones=$this->publicarEventosInscripciones($eventosCalendario,$variables);
        echo "Se han publicado ".$publicar." Inscripciones del Proyecto ".$variables['codProyecto']." ".$variables['nombreProyecto'];
    }
    
    /**
     * Esta funcion permite registrar los eventos del proyecto cuando solicitan no ejecutar preinscripcion
     */
    function registrarEventosInscripcion() {
        $variable=$_REQUEST;
        $this->codProyecto=$_REQUEST['codProyecto'];
        $this->nombreProyecto=$_REQUEST['nombreProyecto'];
        $datosProyecto=$this->consultarDatosProyecto($variable);
        $variables=array_merge($variable,$datosProyecto[0]);
        $eventoCierre=$this->insertarEventoCierre($variables);
        $eventoCierre=$this->actualizarEventoFinPreinscripcion($variables);
        $eventosCalendario=$this->consultarEventosCalendario();
        $insertarEventosInscripciones=$this->publicarEventosInscripciones($eventosCalendario,$variables);
        echo "Se han registrado los permisos para Inscripciones del Proyecto ".$variables['codProyecto']." ".$variables['nombreProyecto'];
    }
    
    /**
     * Funcion que permite publicar los eventos de adiciones y cancelaciones
     * @param type $evento
     * @param type $variables
     */
    function publicarEventosInscripciones($evento,$variables) {
        for ($a=0;$a<4;$a++)
        {
            $this->insertarEventoInscripciones($evento[$a],$variables);
        }
    }
    
    /**
     * Funcion que permite borrar los datos de la tabla acinspre
     */
    function borrarPreinscripcionesAutomática() {
        $datos=array('codProyecto'=>$this->codProyecto,
                     'ano'=>  $this->ano,
                     'periodo'=>  $this->periodo
                    );
        $cadena_sql=$this->sql->cadena_sql("borrarPreinscripcionesProyecto",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
    }
    
    /**
     * Funcion que borra la cantidad de inscritos en los grupos del proyecto
     */
    function actualizarCuposGrupos() {
        $datos=array('codProyecto'=>$this->codProyecto,
                     'ano'=>  $this->ano,
                     'periodo'=>  $this->periodo
                    );
        $cadena_sql=$this->sql->cadena_sql("actualizarGruposProyecto",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
    }
    
    /**
     * Funcion que busca el evento de inscripcion automatica
     * @param type $variable
     * @return type
     */
    function consultarInscripcionAutomatica() {
        $datos=array('codProyecto'=>  $this->codProyecto,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo
                    );
        $cadena_sql=$this->sql->cadena_sql("consultarInscripcionAutomatica",$datos);
        $resultadoInscripcion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoInscripcion[0];
    }
    
    /**
     * Funcion que permite inactivar el evento de inicio de preinscripcon
     */
    function actualizarEstadoEventoPreinscripcion() {
        $datos=array('codProyecto'=>  $this->codProyecto,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo
                    );
        $cadena_sql=$this->sql->cadena_sql("actualizarEstadoEventoPreins",$datos);
        $resultadoInscripcion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
    }

    /**
     * Funcion que permite registrar la fecha de fin y publicacion de la preinscripcion
     */
    function actualizarEventoFinPreinscripcion($variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo
                    );
        $cadena_sql=$this->sql->cadena_sql("actualizarEventoFinPreins",$datos);
        $resultadoInscripcion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
    }

    
    /**
     * Consulta el ranking de espacios academicos
     * @return type
     */
    function consultarRankingEspacios() {
        $variables=array('codCarrera'=>$this->codProyecto);
        $cadena_sql=$this->sql->cadena_sql("buscarRanking",$variables);
        $resultado_grupos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda");
        return $resultado_grupos;
    }

    /**
     * Funcion que permite consultar si existen las incripciones del proyecto sin publicar
     * @param type $variable
     * @return type
     */
    function consultarNumeroInscripcionesProyecto($variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarNumeroInscripcionesProyecto",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoInscripcionProyecto;
    }
    
    /**
     * Funcion que permite consultar las inscripciones del proyecto sin publicar
     * @return type
     */
    function consultarPreinscripcionesProyecto() {
        $datos=array('codProyecto'=>$this->codProyecto,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarPreinscripcionesProyecto",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoInscripcionProyecto;
    }
    
    /**
     * Funcion que permite consultar las preincripciones por demanda de los estudiantes del proyecto
     * @return type
     */
    function consultarPreinscripcionesDemandaProyecto() {
        $datos=array('codProyecto'=>$this->codProyecto,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarPreinscripcionesDemandaProyecto",$datos);
        $resultadoPreinscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoPreinscripcionProyecto;
    }
    
    /**
     * Funcion que permite cambiar el estado de las incripciones para publicar
     * @param type $variable
     * @return type
     */
    function publicarInscripcionesProyecto($variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("publicarInscripcionesProyecto",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        $afectados=$this->totalAfectados($this->configuracion, $this->accesoOracle);
        return $afectados;
    }

    /**
     * Consulta el total de las inscripciones del proyecto en la tabla de acinspre
     * @return type
     */
    function consultarInscripcionesProyecto() {
        $datos=array('codProyecto'=>$this->codProyecto,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql_proyectos=$this->sql->cadena_sql("consultarInscripcionesProyecto",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
        return $resultadoInscripcionProyecto[0][0];
    }

    /**
     * Consulta el total de preinscripciones por demanda de cada estudiante del proyecto
     * @return type
     */
    function consultarPreinscripciones() {
        $datos=array('codProyecto'=>$this->codProyecto,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarPreinscripciones",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoInscripcionProyecto;
    }

    /**
     * Consulta el total de inscripciones realizadas a traves de la inscripcion automatica para cada estudiante
     * @param type $tabla
     * @param type $codEstudiante
     * @return type
     */
    function consultarRegistrosProceso($tabla,$codEstudiante) {
        $datos=array('tabla'=>$tabla,
                    'codEstudiante'=>$codEstudiante,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarRegistrosProceso",$datos);
        $resultadoInscripcionProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoInscripcionProyecto[0][0];
    }
    
    /**
     * Funcion que permite consultar los datos del proyecto
     * @param type $variable
     * @return type
     */
    function consultarDatosProyecto($variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarDatosProyecto",$datos);
        $resultadoDatosProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoDatosProyecto;
    }
    
    /**
     * Funcion que permite consultar los eventos de inscripciones del calendario
     * @return type
     */
    function consultarEventosCalendario() {
        $datos=array('ano'=>$this->ano,
                    'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("consultarEventosCalendario",$datos);
        $resultadoDatosProyecto=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultadoDatosProyecto;
    }
    
    /**
     * Funcion que permite insertar el evento de cierre de inscripcion automatica
     * @param type $variable
     * @return type
     */
    function insertarEventoCierre($variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo,
                    'facultad'=>$variable['FACULTAD'],
                    'tipo'=>$variable['TIPO']);
        
        $cadena_sql=$this->sql->cadena_sql("insertarEventoInscripcionAutomatica",$datos);
        $resultadoEvento=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        $afectados=$this->totalAfectados($this->configuracion, $this->accesoOracle);
        if ($afectados<1)
        {
            $cadena_sql=$this->sql->cadena_sql("actualizarEstadoEventoInscripcionAutomatica",$datos);
            $resultadoEvento=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
            $afectados=$this->totalAfectados($this->configuracion, $this->accesoOracle);
        }
        return $afectados;
    }
    /**
     * Funcion que registra la ejecucion del proceso de inscripcion automatica
     * @param type $variable
     * @return type
     */
    function insertarEventoEjecutar($variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo,
                    'facultad'=>$variable['FACULTAD'],
                    'tipo'=>$variable['TIPO']);
        
        $cadena_sql=$this->sql->cadena_sql("insertarEventoEjecutar",$datos);
        $resultadoEvento=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
        $afectados=$this->totalAfectados($this->configuracion, $this->accesoGestion);
        return $afectados;
    }
    
    /**
     * Funcion que elimina el registro de ejecucion del proceso al finalizar
     */
    function borrarEventoEjecutar() {
        $datos=array('codProyecto'=>  $this->codProyecto,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo
                    );
        $cadena_sql=$this->sql->cadena_sql("borrarEventoEjecutar",$datos);
        return $resultadoInscripcion=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
    }

    /**
     * Funcion que consulta la ejecucion del proceso
     */
    function consultarEjecucionInscripcion() {
        $datos=array('codProyecto'=>  $this->codProyecto,
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo
                    );
        $cadena_sql=$this->sql->cadena_sql("consultarEjecucionInscripcion",$datos);
        return $resultadoInscripcion=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    }

    
    /**
     * Funcion que permite insertar los eventos de adiciones y cancelaciones
     * @param type $datosEvento
     * @param type $variable
     * @return type
     */
    function insertarEventoInscripciones($datosEvento,$variable) {
        $datos=array('codProyecto'=>$variable['codProyecto'],
                    'ano'=>$this->ano,
                    'periodo'=>$this->periodo,
                    'facultad'=>$variable['FACULTAD'],
                    'tipo'=>$variable['TIPO'],
                    'evento'=>$datosEvento['EVENTO'],
                    'inicio'=>$datosEvento['INICIO'],
                    'fin'=>$datosEvento['FIN']);
        $cadena_sql=$this->sql->cadena_sql("insertarEventoInscripciones",$datos);
        $resultadoEvento=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        $afectados=$this->totalAfectados($this->configuracion, $this->accesoOracle);
        return $afectados;
    } 
    
    /*
     * Funcion que permite consultar los proyectos a los que esta asociado el coordinador
     */
    function consultarProyectosCoordinador() {
        $cadena_sql=$this->sql->cadena_sql("consultarProyectosCoordinador",$this->codProyecto);
        $resultadoProyectos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        foreach ($resultadoProyectos as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if(is_numeric($key2))
                {
                    $this->proyectos.=$value2.",";
                }
            }
        }
        $this->proyectos=rtrim($this->proyectos,',');
        return $resultadoProyectos;
    }
}

?>
