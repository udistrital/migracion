
<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/planEstudios.class.php");

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_adminConfigurarPlanEstudioCoordinador extends funcionGeneral {
  private $configuracion;
  private $creditosOB;
  private $creditosOC;
  private $creditosEI;
  private $creditosEE;
  private $creditosCP;
  private $creditosTotal;
  private $espaciosAsociados;
  private $datosPlan;

//@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=new sql_adminConfigurarPlanEstudioCoordinador();
        $this->log_us= new log();
        $this->parametrosPlan=new planEstudios();
        $this->formulario="adminConfigurarPlanEstudioCoordinador";

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion=new sesiones($configuracion);
        $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
        $this->id_accesoSesion=$this->resultadoSesion[0][0];

        //Datos de sesion
        $this->formulario="adminConfigurarPlanEstudioCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->creditosOB=0;
        $this->creditosOC=0;
        $this->creditosEI=0;
        $this->creditosEE=0;
        $this->creditosCP=0;
        $this->creditosTotal=0;
        $this->espaciosAsociados=0;
        $this->datosPlan=array();

    }#Cierre de constructor

    /**
     * Funcion que permite seleccionar el plan de estudios o redirecciona si solo hay uno
     */
    function verProyectos() {
        //Consultamos los proyectos curriculares con su respectivo plan de estudio, y los mostramos en un <select>
        $cadena_sql_proyectos=$this->sql->cadena_sql($this->configuracion,"proyectos_curriculares",$this->usuario);
        $resultado_proyectos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );

        if(is_array ($resultado_proyectos)&&count($resultado_proyectos)>1) {
            ?>
        <table class='sigma_borde centrar' align="center" width="70%">
            <caption class="centrar">SELECCIONE EL PLAN DE ESTUDIOS</caption>
            <tr>
                <th class="sigma centrar">Plan de Estudios</th>
                <th class="sigma centrar">Nombre</th>
            </tr>
            <?
            $totalProyectos=count($resultado_proyectos);
            for($i=0;$i<$totalProyectos;$i++) {
              if((isset($resultado_proyectos[$i][0])?$resultado_proyectos[$i][0]:'')!=(isset($resultado_proyectos[$i-1][0])?$resultado_proyectos[$i-1][0]:'')) {
                $cadena_sql=$this->sql->cadena_sql($this->configuracion,"planesCarrera",$resultado_proyectos[$i][0]);
                $resultado_planesEstudio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
            if(is_array($resultado_planesEstudio)){
                $totalPlanes=count($resultado_planesEstudio);
                for($j=0;$j<$totalPlanes;$j++) {
                  $nombreProyecto=strtr(strtoupper($resultado_planesEstudio[$j][2]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                  ?>
                  <tr>
                    <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=adminConfigurarPlanEstudioCoordinador";
                    $variable.="&opcion=registrados";
                    $variable.="&codProyecto=".$resultado_planesEstudio[$j][1];
                    $variable.="&planEstudio=".$resultado_planesEstudio[$j][0];
                    $variable.="&nombreProyecto=".$nombreProyecto;

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    ?>
                      <td class="sigma centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_planesEstudio[$j][0]?></a></td>
                      <td class="sigma centrar "><a href="<?echo $pagina.$variable?>"><?echo $resultado_planesEstudio[$j][1]." - ".$nombreProyecto?></a></td>
                  </tr>
                  <?
                }
              }
              }
            }
          ?>
        </table>
        <?
        }else
          {
            if (is_array ($resultado_proyectos)){
              $cadena_sql=$this->sql->cadena_sql($this->configuracion,"planesCarrera",$resultado_proyectos[0][0]);
              $resultado_planesEstudio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
              if (is_array($resultado_planesEstudio)&&count($resultado_planesEstudio)>1){
                ?>
                <table class='sigma_borde centrar' align="center" width="70%"  background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                    <caption class="centrar">SELECCIONE EL PLAN DE ESTUDIOS</caption>
                    <tr>
                        <th class="sigma centrar">Plan de Estudios</th>
                        <th class="sigma centrar">Nombre</th>
                    </tr>
                    <?
                    for($j=0;$j<count($resultado_planesEstudio);$j++) {
                      $nombreEspacio=strtr(strtoupper($resultado_planesEstudio[$j][2]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                      ?>
                      <tr>
                      <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=adminConfigurarPlanEstudioCoordinador";
                        $variable.="&opcion=registrados";
                        $variable.="&codProyecto=".$resultado_planesEstudio[$j][1];
                        $variable.="&planEstudio=".$resultado_planesEstudio[$j][0];
                        $variable.="&nombreProyecto=".$nombreEspacio;

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                      ?>
                      <td class="sigma centrar"><a href="<?echo $pagina.$variable?>"><?echo $resultado_planesEstudio[$j][0]?></a></td>
                      <td class="sigma centrar "><a href="<?echo $pagina.$variable?>"><?echo $resultado_planesEstudio[$j][1]." - ".$nombreEspacio?></a></td>
                    </tr>
                    <?
                  }
                  ?>
                </table>
                  <?
              }else
                  {
                    if (is_array($resultado_planesEstudio)){
                    $_REQUEST['codProyecto']=(isset($resultado_proyectos[0][0])?$resultado_proyectos[0][0]:'');
                    $_REQUEST['planEstudio']=(isset($resultado_planesEstudio[0][0])?$resultado_planesEstudio[0][0]:'');
                    $_REQUEST['nombreProyecto']=(isset($resultado_proyectos[0][2])?$resultado_proyectos[0][2]:'');
                    $this->verRegistrados();
                    }else
                      {
                        $this->noPlan();
                      }
                  }
          }else
            {
              $this->noPlan();
            }
        }
    }
    
    /**
     * Funcion que presenta mensaje si no hay planes de estudio asociados al coordinador
     */
    function noPlan() {
?>
                      <table class='contenidotabla centrar' background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                          <tr align="center">
                              <td class="centrar" colspan="4">
                                  <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                  <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/pequeno_universidad.png ">
                              </td>
                          </tr>
                          <tr align="center">
                              <td class="centrar" colspan="4">
                                  <h4>NO EXISTEN PLANES DE ESTUDIO ASOCIADOS AL USUARIO <?echo $this->usuario?></h4>
                                  <hr noshade class="hr">

                              </td>
                          </tr>
                      </table>
                  <?


    }

    /**
     * Funcion que verifica los datos del coordinador para presentar el plan de estudios
     */
    function verRegistrados() {

        //Organiza en un array el string que trae el plan de estudio, codigo del proyecto y el nombre del proyecto
        if(isset($_REQUEST['proyecto'])) {
            $arreglo=explode("-",$_REQUEST['proyecto']);
            $planEstudio=$arreglo[0];
            $codProyecto=$arreglo[1];
            $nombreProyecto=$arreglo[2];
        }else if(isset($_REQUEST['codProyecto']) && isset($_REQUEST['planEstudio'])) {
            $planEstudio=$_REQUEST['planEstudio'];
            $codProyecto=$_REQUEST['codProyecto'];
            $nombreProyecto=$_REQUEST['nombreProyecto'];
        }else {
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"datos_coordinador",$this->usuario);
            $resultado_datosCoordinador=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $planEstudio=$resultado_datosCoordinador[0][2];
            $codProyecto=$resultado_datosCoordinador[0][0];
            $nombreProyecto=$resultado_datosCoordinador[0][1];
        }
        $variable=array($planEstudio,$codProyecto,$nombreProyecto);

        $this->mostrarRegistro($planEstudio);
    }

    #Llama las funciones "verPlanEstudios", "listaNiveles" y "listaEspacios" para visualizar
    # presenta la informacion general del Plan de Estudios y los Espacios Academicos que lo componen agrupados por niveles
    /**
     * Se actualiza para presentar el componente propedeutico 24/11/2011
     * @param <type> $configuracion
     * @param <type> $id codigo del plan de estudios
     */
    function mostrarRegistro($id) {
        $this->datosPlan=  $this->consultarDatosPlan($id);
        $this->verPlanEstudios();
        $asociados=$this->consultarEspaciosAsociados($id);
        $lista="0";
        if (is_array($asociados))
        {
          foreach ($asociados as $key => $value) {
            $lista.=",".$value['id_espacio'];
          }
        }
        else{

            }
        $this->espaciosAsociados=array('PLAN'=>$id, 'LISTA'=>$lista);
        #Consulta los Espacios Academicos del plan de estudios
        $registroEspaciosPlan=$this->consultarEspaciosPlan($this->espaciosAsociados);
        $totalEspacios=$this->accesoGestion->obtener_conteo_db($registroEspaciosPlan);
        $registroEncabezadosEspacios= $this->consultarEncabezadosConEspacios($id);
        $niveles=$this->consultarNiveles($id);
        $this->contarCreditosPlan($registroEspaciosPlan,$registroEncabezadosEspacios);
        #Muestra los niveles de un plan de estudios
        $this->listaEspacios($niveles,$registroEspaciosPlan,$totalEspacios,$registroEncabezadosEspacios);


    }

    #Funcion que muestra la informacion del Plan de Estudios
    /**
     * 
     * @param <type> $configuracion
     * @param <type> $registro 
     */
    function verPlanEstudios() {

        #enlace regreso  listado de planes
        $this->menuAdministrar();

        ?>
        <br>
        <table class="sigma centrar" width="100%" >
            <tr><th class="sigma_a centrar"><?echo $this->datosPlan[0]['NOMBRE_PROYECTO_PLAN_ESTUDIO']?><br>
          PLAN DE ESTUDIOS EN CR&Eacute;DITOS N&Uacute;MERO <?echo "<strong>".$this->datosPlan[0]['COD_PLAN_ESTUDIO']." - ".$this->datosPlan[0]['NOMBRE_PROYECTO_PLAN_ESTUDIO']."</strong>"?></th></tr>
        </table>
        <hr>
        <?
    }


    /**
     * Funcion que presenta los espacios del plan de estudios en modo configuracion
     * Se modifica para presentar el componente propedeutico 24/11/2011
     * @param <type> $niveles Niveles del plan de estudios
     * @param <type> $registroEspacios Espacios del plan de estudios
     * @param <type> $totalEspacios Numero de espacios del plan de estudios
     * @param <type> $this->datosPlan Datos del plan de estudios
     * @param <type> $registroEncabezadosEspacios Encabezados y espacios asociados
     */
    function listaEspacios($niveles,$registroEspacios,$totalEspacios,$registroEncabezadosEspacios) {

        $id_encabezado=isset($id_encabezado)?$id_encabezado:'';

        $creditosAprobadosNivel=0;
        $nivelPropedeutico=6;
        $creditosAprobados=0;
        $creditosNivel=0;
        $idEncabezado=0;
        $creob=0;
        $creoc=0;
        $creei=0;
        $creee=0;
        $ob=0;
        $oc=0;
        $ei=0;
        $ee=0;
        if(is_array($niveles)){
          ?><table class='sigma contenidotabla'><?
          foreach ($niveles as $key => $value) {
            $this->mostrarEncabezadoNivel('PERIODO DE FORMACI&Oacute;N '.$value['NIVEL']);
            if(is_array($registroEncabezadosEspacios))
            {
//******** presenta los encabezados del nivel junto con sus espacios
              foreach ($registroEncabezadosEspacios as $key1 => $value1) {
                if ($registroEncabezadosEspacios[0]['NIVEL_ENCABEZADO']==$niveles[$key]['NIVEL'])
                  {
                    $encabezadoEliminado=array_shift($registroEncabezadosEspacios);
                    if($encabezadoEliminado['COD_ENCABEZADO']!=$id_encabezado)
                    {
                      $this->generarCeldaEncabezado($encabezadoEliminado);
                      if($encabezadoEliminado['CLASIF_ENCABEZADO']!=4)
                        {
                          if (!is_null($encabezadoEliminado['COD_ESPACIO'])&&$encabezadoEliminado['ESTADO_ESPACIO']==1)
                            {
                              $this->generarCeldaEspaciosEncabezados($encabezadoEliminado);
                            }
                        }
                      $id_encabezado=$encabezadoEliminado['COD_ENCABEZADO'];
                      $creditosNivel+=$encabezadoEliminado['CREDITOS_ENCABEZADO'];
                      if ($encabezadoEliminado['APROBADO_ENCABEZADO']==1)
                        {
                          $creditosAprobadosNivel+=$encabezadoEliminado['CREDITOS_ENCABEZADO'];
                        }
                    }else
                      {
                        if($encabezadoEliminado['CLASIF_ENCABEZADO']!=4)
                          {
                            if (!is_null($encabezadoEliminado['COD_ESPACIO'])&&$encabezadoEliminado['ESTADO_ESPACIO']==1)
                              {
                                $this->generarCeldaEspaciosEncabezados($encabezadoEliminado);
                              }
                          }
                        $id_encabezado=$encabezadoEliminado['COD_ENCABEZADO'];
                      }
                  }else
                    {
                    }
               }
//******** fin encabezados con espacios
            }else
              {

              }
  //******** presenta los espacios no asociados
            if (is_array($registroEspacios))
            {
              foreach($registroEspacios as $key2 => $value2){
                if ($registroEspacios[0]['NIVEL_ESPACIO']==$niveles[$key]['NIVEL'])
                {
                  $espacioEliminado=array_shift($registroEspacios);
                  $this->generarCeldaEspacios($espacioEliminado);
                  $creditosNivel+=$espacioEliminado['CREDITOS_ESPACIO'];
                  if ($espacioEliminado['APROBADO_ESPACIO']==1)
                    {
                      $creditosAprobadosNivel+=$espacioEliminado['CREDITOS_ESPACIO'];
                    }
                }else{
                      }
              }
            }else {

                  }
  //******** presenta los creditos del nivel
            $this->mostrarCeldaCreditosNivel($creditosNivel,$creditosAprobadosNivel);
            $creditosAprobadosNivel=0;
            $creditosNivel=0;
  //******** Presenta el componente propedeutico
            if ($value['NIVEL']==$nivelPropedeutico)
              {
                $registroPropedeuticos=$this->consultarEspaciosPropedeuticosPlan();
                $registroEncabezadosPropedeuticos=$this->consultarEncabezadosPropedeuticosConEspacios();
                if(is_array($registroEncabezadosPropedeuticos) || is_array($registroPropedeuticos))
                  {
                    $this->contarCreditosPlan($registroPropedeuticos,$registroEncabezadosPropedeuticos);
                    $this->mostrarEncabezadoNivel('COMPONENTE PROPED&Eacute;UTICO');
                    $this->presentarComponentePropedeutico($registroEncabezadosPropedeuticos,$registroPropedeuticos,$niveles[$key]['NIVEL']);
  //************* fin Propedeutico
                    $nivelPropedeutico=0;
                    $creditosAprobadosNivel=0;
                    $creditosNivel=0;
                  }
              }else
                {
                }
          }
          ?></table><?
          $this->parametrosPlan->presentarAbreviaturas();
          $this->presentarCreditosRegistradosPlan();
          }else
            {
//*** si no hay espacios registrados
            $this->presentarMensajeNoEspacios();
            }
    }

/**
 * Funcion que presenta el encabezado del módulo
 * @param <type> $planEstudio
 * @param <type> $codProyecto
 * @param <type> $nombreProyecto
 */
    function encabezadoModulo($planEstudio,$codProyecto,$nombreProyecto) {

        ?>

<table class='contenidotabla centrar' background="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr align="center">
        <td class="centrar" colspan="5">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="5">
            <h4>MODULO PARA LA ADMINISTRACI&Oacute;N DE PLANES DE ESTUDIO</h4>
            <hr noshade class="hr">

        </td>
    </tr>

</table><?
    }

    /**
     * Funcion que presenta el encabezado del nivel
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <int/string> $nivel 
     */
    function mostrarEncabezadoNivel($nivel) {
      ?>
      <tr>
          <th class="sigma_a centrar" colspan="12"><?echo $nivel?></th>
      </tr>
      <tr class='sigma'>
          <th class='sigma centrar'>Cod. </th>
          <th class='sigma centrar'>Nombre </th>
          <th class='sigma centrar'>N&uacute;mero<br>Cr&eacute;ditos</th>
          <th class='sigma centrar'>HTD </th>
          <th class='sigma centrar'>HTC </th>
          <th class='sigma centrar'>HTA </th>
          <th class='sigma centrar'>Clasificaci&oacute;n </th>
          <th class='sigma centrar' colspan="2">Aprobado </th>
          <th class='sigma centrar' colspan="2">Solicitar </th>
          <th class='sigma centrar'>Comentarios </th>
      </tr>
      <?
    }

    /**
     * Funcion que presenta los enlaces de las opciones de configurar plan de estudios para el coordinador
     */
    function menuAdministrar() {
        //Organiza en un array el string que trae el plan de estudio, codigo del proyecto y el nombre del proyecto
        if(isset($_REQUEST['proyecto'])) {
            $arreglo=explode("-",$_REQUEST['proyecto']);
            $planEstudio=$arreglo[0];
            $codProyecto=$arreglo[1];
            $nombreProyecto=$arreglo[2];
        }else if(isset($_REQUEST['codProyecto']) && isset($_REQUEST['planEstudio'])) {
            $planEstudio=$_REQUEST['planEstudio'];
            $codProyecto=$_REQUEST['codProyecto'];
            $nombreProyecto=$_REQUEST['nombreProyecto'];
        }else {
            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"datos_coordinador",$this->usuario);
            $resultado_datosCoordinador=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            $planEstudio=$resultado_datosCoordinador[0][2];
            $codProyecto=$resultado_datosCoordinador[0][0];
            $nombreProyecto=$resultado_datosCoordinador[0][1];
        }

        $variable=array($planEstudio,$codProyecto,$nombreProyecto);

        ?>
<table class='contenidotabla centrar' >
    <tr align="center">
<!--        <td class="centrar" >
                    <?
//                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
//                    $variable="pagina=registroCrearEACoordinador";
//                    $variable.="&opcion=seleccionClasificacion";
//                    $variable.="&codProyecto=".$codProyecto;
//                    $variable.="&planEstudio=".$planEstudio;
//                    $variable.="&nombreProyecto=".$nombreProyecto;
//
//                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
//                    $this->cripto=new encriptar();
//                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        ?>
            <a href="<?//echo $pagina.$variable?>">
                <img src="<?//echo $this->configuracion['site'].$this->configuracion['grafico']?>/kword.png" width="38" height="38" border="0" ><br><font size="1">Solicitar Creaci&oacute;n de<br> Espacio Acad&eacute;mico</font>
            </a>
        </td>
        <td class="centrar" >
                    <?
//                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
//                    $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
//                    $variables.="&opcion=listaPlanes";
//                    $variables.="&planEstudio=".$planEstudio;
//                    $variables.="&codProyecto=".$codProyecto;
//                    $variables.="&nombreProyecto=".$nombreProyecto;
//
//                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
//                    $this->cripto=new encriptar();
//                    $variables=$this->cripto->codificar_url($variables,$this->configuracion);
        ?>
            <a href="<?//echo $pagina.$variables?>">
                <img src="<?//echo $this->configuracion['site'].$this->configuracion['grafico']?>/favorito.png" width="35" height="35" border="0"><br>Solicitar Agregar<br> Espacio Existente
            </a>
        </td>-->
        <td class="centrar" >
                    <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variables="pagina=registroPortafolioElectivasCoordinador";
                    $variables.="&opcion=ver";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;
                    $variables.="&clasificacion=4";


                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$this->configuracion);
        ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/portafolio2.png" width="35" height="35" border="0"><br>
                Portafolio<br>Electivas Extr&iacute;nsecas
            </a>
        </td>
<!--        <td class="centrar" >
                    <?
//                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
//                    $variables="pagina=registroConsultarAgrupacionEspaciosCoordinador";
//                    $variables.="&opcion=verEncabezado";
//                    $variables.="&planEstudio=".$planEstudio;
//                    $variables.="&codProyecto=".$codProyecto;
//                    $variables.="&nombreProyecto=".$nombreProyecto;
//
//
//                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
//                    $this->cripto=new encriptar();
//                    $variables=$this->cripto->codificar_url($variables,$this->configuracion);
        ?>
            <a href="<?//echo $pagina.$variables?>">
                <img src="<?//echo $this->configuracion['site'].$this->configuracion['grafico']?>/agrupar.png" width="35" height="35" border="0"><br>
                Administrar Espacios Acad&eacute;micos<br> con Opciones
            </a>
        </td>-->
        <td class="centrar" >
                    <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variables="pagina=requisitos_espacio";
                    $variables.="&opcion=registrar";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;


                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$this->configuracion);
        ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/kivio.png" width="35" height="35" border="0"><br>
                Administrar Requisitos
            </a>
        </td>
        <td class="centrar" >
                    <?
                    $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $ruta="pagina=registroComentarioPlanCoordinador";
                    $ruta.="&opcion=verComentarios";
                    $ruta.="&planEstudio=".$planEstudio;
                    $ruta.="&codProyecto=".$codProyecto;
                    $ruta.="&nombreProyecto=".$nombreProyecto;

                    $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        ?>
            <a href="<?= $indice.$ruta ?>">
                        <?
                        $variable=$this->datosPlan[0]['COD_PLAN_ESTUDIO'];
                        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"mensajeGeneral",$planEstudio);
                        $resultado_numMensaje=$this->accesoGestion->ejecutarAcceso($cadena_sql,"busqueda");
                        if($resultado_numMensaje[0][0]==0) {
                            echo  "<img src='".$this->configuracion['site'].$this->configuracion['grafico']."/kopete.png' width='38' height='38' border='0' alt='Editar Requisito'>";
                            echo "<br>Enviar un<br>Mensaje General<br>";
                        }
                        else if($resultado_numMensaje[0][0]==1) {
                            echo  "<img src='".$this->configuracion['site'].$this->configuracion['grafico']."/search.png' width='30' height='30' border='0' alt='Editar Requisito'>";
                            echo "<font color=red><br>Existe un<br>Mensaje General<br>Nuevo ";
                        }
                        else if($resultado_numMensaje[0][0]>1) {
                            echo  "<img src='".$this->configuracion['site'].$this->configuracion['grafico']."/search.png' width='30' height='30' border='0' alt='Editar Requisito'>";
                            echo "<br><font color=red>Existen ".$resultado_numMensaje[0][0]."<br>Mensajes Generales<br> Nuevos</font> ";
                        }
        ?>
            </a>
        </td>
        <td class="centrar" >
                    <?
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variables="pagina=registro_parametrosPlanEstudio";
                    $variables.="&opcion=administrar";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$this->configuracion);
        ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/parametros.png" width="35" height="35" border="0"><br>
                Par&aacute;metros <br>Plan Estudios
            </a>
        </td>
    </tr>


</table><?
    }

    /**
     * Este funcion presenta mensaje cuando no hay espacios en el plan de estudios
     * Se crea para presentar el componente propedeutico 24/11/2011
     */
    function presentarMensajeNoEspacios(){
      $this->iniciarTabla();
      ?><br>NO HAY ESPACIOS REGISTRADOS EN EL PLAN DE ESTUDIOS<br><br><?
      $this->cerrarTabla();
    }

    /**
     * Esta funcion permite iniciar la tabla donde se presenta el plan de estudios
     * Se crea para presentar el componente propedeutico 24/11/2011
     */
    function iniciarTabla() {
      ?>
        <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" >
            <tr class="cuadro_plano">
              <td  align="center">
      <?
    }

    /**
     * Esta funcion permite cerrar la tabla donde se presenta el plan de estudios
     * Se crea para presentar el componente propedeutico 24/11/2011
     */
    function cerrarTabla() {
      ?>
              </td>
            </tr>
        </table>
      <?
    }

    /**
     * Presenta los espacios del componente propedeutico
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $registroEncabezadosPropedeuticos
     * @param <type> $registroPropedeuticos
     * @param <type> $datosPlan
     * @param <type> $nivel
     */
    function presentarComponentePropedeutico($registroEncabezadosPropedeuticos,$registroPropedeuticos,$nivel) {
      $creditosAprobadosNivel=0;
      $creditosNivel=0;

            if(is_array($registroEncabezadosPropedeuticos))
            {
//******** presenta los encabezados propedeuticos con sus espacios

              foreach ($registroEncabezadosPropedeuticos as $key1 => $value1) {
                  $encabezadoEliminado=array_shift($registroEncabezadosPropedeuticos);
                  if($encabezadoEliminado['COD_ENCABEZADO']!=$id_encabezado)
                  {
                    $this->generarCeldaEncabezado($encabezadoEliminado);
                    if($encabezadoEliminado['CLASIF_ENCABEZADO']!=4)
                    {
                      if (!is_null($encabezadoEliminado['COD_ESPACIO']))
                      {
                        $this->generarCeldaEspaciosEncabezados($encabezadoEliminado);
                      }
                    }
                    $id_encabezado=$encabezadoEliminado['COD_ENCABEZADO'];
                    $creditosNivel+=$encabezadoEliminado['CREDITOS_ENCABEZADO'];
                    if ($encabezadoEliminado['APROBADO_ENC_ESPACIO']==1)
                      {
                        $creditosAprobadosNivel+=$encabezadoEliminado['CREDITOS_ENCABEZADO'];
                      }
                  }else
                    {
                      if($encabezadoEliminado['CLASIF_ENCABEZADO']!=4)
                      {
                        if (!is_null($encabezadoEliminado['COD_ESPACIO']))
                        {
                          $this->generarCeldaEspaciosEncabezados($encabezadoEliminado);
                        }
                      }
                      $id_encabezado=$encabezadoEliminado['COD_ENCABEZADO'];
                    }
              }
//******** fin encabezados con propedeuticos
            }else
              {

              }
//******** presenta los espacios propedeuticos no asociados
          if (is_array($registroPropedeuticos))
          {
            foreach($registroPropedeuticos as $key2 => $value2){
                $espacioEliminado=array_shift($registroPropedeuticos);
                $this->generarCeldaEspacios($espacioEliminado);
                $creditosNivel+=$espacioEliminado['CREDITOS_ESPACIO'];
                if ($espacioEliminado['APROBADO_ESPACIO']==1)
                  {
                    $creditosAprobadosNivel+=$espacioEliminado['CREDITOS_ESPACIO'];
                  }
            }
          }else {

                }
          $this->mostrarCeldaCreditosNivel($creditosNivel,$creditosAprobadosNivel);

    }
    /**
     * Esta funcion permite crear los enlaces para los encabezados
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $configuracion
     * @param <type> $paginaOpcion
     * @param <type> $opcion
     * @param <type> $datosEncabezado
     * @param <type> $nombreProyecto
     * @return <type>
     */
    function crearEnlaceEncabezado($paginaOpcion,$opcion,$datosEncabezado) {
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $ruta="pagina=".$paginaOpcion;
        $ruta.="&opcion=".$opcion;
        $ruta.="&id_encabezado=".$datosEncabezado['COD_ENCABEZADO'];
        $ruta.="&encabezado_nombre=".$datosEncabezado['NOMBRE_ENCABEZADO'];
        $ruta.="&nroCreditos=".$datosEncabezado['CREDITOS_ENCABEZADO'];
        $ruta.="&nivel=".$datosEncabezado['NIVEL_ENCABEZADO'];
        $ruta.="&planEstudio=".$this->datosPlan[0]['COD_PLAN_ESTUDIO'];
        $ruta.="&codProyecto=".$this->datosPlan[0]['COD_PROYECTO'];
        $ruta.="&nombreProyecto=".$this->datosPlan[0]['NOMBRE_PROYECTO_PLAN_ESTUDIO'];
        $ruta.="&clasificacion=".$datosEncabezado['CLASIF_ENCABEZADO'];

        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        return $pagina.$ruta;

    }


    /**
     *  esta funcion permite crear los enlaces para comentarios de espacios
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $configuracion
     * @param <type> $datosEspacio
     * @param <type> $datosPlan
     * @param <type> $nombreProyecto
     * @return <type>
     */
    function crearEnlaceComentario($datosEspacio) {
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variables="pagina=registroAgregarComentarioEspacioCoordinador";
        $variables.="&opcion=verComentarios";
        $variables.="&codEspacio=".$datosEspacio[0];
        $variables.="&planEstudio=".$this->datosPlan[12];
        $variables.="&codProyecto=".$this->datosPlan[12];
        $variables.="&nivel=".$this->datosPlan[2];
        $variables.="&nroCreditos=".$datosEspacio[3];
        $variables.="&htd=".$datosEspacio[4];
        $variables.="&htc=".$datosEspacio[5];
        $variables.="&hta=".$datosEspacio[6];
        $variables.="&clasificacion=".$datosEspacio[8];
        $variables.="&nombreEspacio=".$datosEspacio[1];
        $variables.="&nombreProyecto=".$this->datosPlan['NOMBRE_PROYECTO_PLAN_ESTUDIO'];

        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variables=$this->cripto->codificar_url($variables,$this->configuracion);
        return $pagina.$variables;

    }

    /**
     *  esta funcion permite crear los enlaces para administrar espacios
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $configuracion
     * @param <type> $paginaOpcion
     * @param <type> $opcion
     * @param <type> $datosEspacio
     * @param <type> $datosPlan
     * @return <type>
     */
    function crearEnlaceEspacio($paginaOpcion,$opcion,$datosEspacio) {
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variables="pagina=".$paginaOpcion;
        $variables.="&opcion=".$opcion;
        $variables.="&codEspacio=".$datosEspacio['COD_ESPACIO'];
        $variables.="&planEstudio=".$this->datosPlan[0]['COD_PLAN_ESTUDIO'];
        $variables.="&codProyecto=".$this->datosPlan[0]['COD_PROYECTO'];
        $variables.="&nivel=".$datosEspacio['NIVEL_ESPACIO'];
        $variables.="&nroCreditos=".$datosEspacio['CREDITOS_ESPACIO'];
        $variables.="&htd=".$datosEspacio['HTD'];
        $variables.="&htc=".$datosEspacio['HTC'];
        $variables.="&hta=".$datosEspacio['HTA'];
        $variables.="&clasificacion=".$datosEspacio['COD_CLASIFICACION_ESPACIO'];
        $variables.="&nombreEspacio=".$datosEspacio['NOMBRE_ESPACIO'];
        $variables.="&nombreProyecto=".$this->datosPlan[0]['NOMBRE_PROYECTO_PLAN_ESTUDIO'];

        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variables=$this->cripto->codificar_url($variables,$this->configuracion);
        return $pagina.$variables;
    }

    /**
     * Esta funcion genera la celda de datos de cada encabezado en los niveles
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $encabezado
     */
    function generarCeldaEncabezado($encabezado) {

      $encabezado['APROBADO_ESPACIO']=$encabezado['APROBADO_ENCABEZADO'];
      $datosCeldaAprobado=array('nombreEnlace'=>'crearEnlaceEncabezado',
                                'paginaModificar'=>'registroModificarEACoordinador',
                                'opcionModificar'=>'solicitarEncabezado',
                                'paginaBorrar'=>'registroBorrarEACoordinador',
                                'opcionBorrar'=>'solicitarEncabezado',
                                'datosEspacio'=>$encabezado,
                                'datosPlan'=>$this->datosPlan);
      $celdaAprobado=$this->generarCeldaEstadoEspacio($datosCeldaAprobado);
      $this->mostrarCeldaEncabezado($encabezado,$celdaAprobado['aprobado'],$celdaAprobado['enlaceEspacio']);
    }

    /**
     * Esta funcion muestra la celda de datos de cada encabezado en los niveles
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $encabezado
     */
    function mostrarCeldaEncabezado($encabezado,$aprobado,$htmlEncabezado) {
    ?>
      <tr>
          <td class='cuadro_plano' colspan="2"><font color="#090497"><?echo $encabezado['NOMBRE_ENCABEZADO']?></font></td>
          <td class='cuadro_plano centrar'><font color="#090497"><?echo $encabezado['CREDITOS_ENCABEZADO']?></font></td>
          <td class='cuadro_plano centrar' colspan="3"></td>
          <td class='cuadro_plano centrar'><font color="#090497"><?echo $encabezado['CLASIFICACION_ENCABEZADO']?></font></td>
          <td class='cuadro_plano centrar' colspan="2"><font color="#090497"><?echo $aprobado?></font></td>
          <?echo $htmlEncabezado;?>
          <td class='cuadro_plano centrar'></td>
      </tr>
    <?
    }

    /**
     * Esta funcion genera la celda de datos de cada espacio asociado en los niveles
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $espaciosEncabezados
     */
    function generarCeldaEspaciosEncabezados($espaciosEncabezados){
      //verifica si el espacio es anual o semestral
      $semanas=$this->generarRotuloSemanas($espaciosEncabezados['SEMANAS_ESPACIO']);
      //verifica si el espacio cumple con relacion creditos-horas
      $color=$this->generarColorTexto($espaciosEncabezados);
      //verifica el estado del espacio
      $datosCeldaAprobado=array('nombreEnlace'=>'crearEnlaceEspacio',
                                'paginaModificar'=>'registroModificarEACoordinador',
                                'opcionModificar'=>'solicitar',
                                'paginaBorrar'=>'registroBorrarEACoordinador',
                                'opcionBorrar'=>'confirmarBorrarEA',
                                'datosEspacio'=>$espaciosEncabezados,
                                'datosPlan'=>$this->datosPlan);
      $celdaAprobado=$this->generarCeldaEstadoEspacio($datosCeldaAprobado);
      $datosComentarios=array('COD_ESPACIO'=>$espaciosEncabezados['COD_ESPACIO'],
                              'PLAN_ESPACIO'=>$espaciosEncabezados['PLAN_ESPACIO']);
      $paginaComentario='registroAgregarComentarioEspacioCoordinador';
      $opcionComentario='verComentarios';
      $comentario=array('numero'=>$this->consultarComentariosNoLeidos($datosComentarios),
                        'enlace'=>$this->crearEnlaceEspacio($paginaComentario,$opcionComentario,$espaciosEncabezados));
      $comentario=$this->generarCeldaComentario($comentario);
      $this->mostrarCeldaEspaciosEncabezados($espaciosEncabezados,$semanas,$celdaAprobado['enlaceEspacio'],$celdaAprobado['aprobado'],$comentario);
    }

    /**
     * Esta funcion muestra la celda de datos de cada espacio asociado en los niveles
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $espaciosEncabezados
     */
    function mostrarCeldaEspaciosEncabezados($espaciosEncabezados,$semanas,$enlaceEspacio,$aprobado,$comentario){
      ?>
        <tr>
            <td class='cuadro_plano derecha' width="40"><font color="#049713"><?echo $espaciosEncabezados['COD_ESPACIO'];?></font></td>
            <td class='cuadro_plano'>&nbsp;&nbsp;&nbsp;<font color="#049713"><?echo $espaciosEncabezados['NOMBRE_ESPACIO'].$semanas;?></font></td>
            <td class='cuadro_plano derecha'><font color='<?echo (isset($color)?$color:'');?>'><?echo $espaciosEncabezados['CREDITOS_ESPACIO']?></font></td>
            <td class='cuadro_plano derecha'><font color="<?echo (isset($color)?$color:'');?>"><?echo $espaciosEncabezados['HTD']?></font></td>
            <td class='cuadro_plano derecha'><font color="<?echo (isset($color)?$color:'');?>"><?echo $espaciosEncabezados['HTC']?></font></td>
            <td class='cuadro_plano derecha'><font color="<?echo (isset($color)?$color:'');?>"><?echo $espaciosEncabezados['HTA']?></font></td>
            <td class='cuadro_plano'>&nbsp;&nbsp;&nbsp;<font color="#049713"><?echo $espaciosEncabezados['CLASIFICACION_ESPACIO']?></font></td>
            <td class='cuadro_plano centrar' colspan="2"><?echo $aprobado?></td>
        <?//verifica que este aprobado el espacio academico
        echo $enlaceEspacio;
        echo $comentario;
    }

    /**
     * Esta funcion genera la celda de datos de cada espacio asociado en los niveles
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $datosEspacio
     */
    function generarCeldaEspacios($datosEspacio){
    //verifica el numero de semanas para cursar un espacios
      $semanas=$this->generarRotuloSemanas($datosEspacio['SEMANAS_ESPACIO']);
      //verifica si el espacio cumple con relacion creditos-horas
      $color=$this->generarColorTexto($datosEspacio);
      //muestra estado de los espacios
      $datosCeldaAprobado=array('nombreEnlace'=>'crearEnlaceEspacio',
                                'paginaModificar'=>'registroModificarEACoordinador',
                                'opcionModificar'=>'solicitar',
                                'paginaBorrar'=>'registroBorrarEACoordinador',
                                'opcionBorrar'=>'confirmarBorrarEA',
                                'datosEspacio'=>$datosEspacio,
                                'datosPlan'=>$this->datosPlan);
      $celdaAprobado=$this->generarCeldaEstadoEspacio($datosCeldaAprobado);
      $datosComentarios=array('COD_ESPACIO'=>$datosEspacio['COD_ESPACIO'],
                              'PLAN_ESPACIO'=>$datosEspacio['PLAN_ESPACIO']);
      $paginaComentario='registroAgregarComentarioEspacioCoordinador';
      $opcionComentario='verComentarios';
      $comentario=array('numero'=>$this->consultarComentariosNoLeidos($datosComentarios),
                        'enlace'=>$this->crearEnlaceEspacio($paginaComentario,$opcionComentario,$datosEspacio));
      $comentario=$this->generarCeldaComentario($comentario);
      $this->mostrarCeldaEspacios($datosEspacio,$semanas,$celdaAprobado['enlaceEspacio'],$celdaAprobado['aprobado'],$comentario);
    }

    /**
     * Esta funcion muestra la celda de datos de cada espacio asociado en los niveles
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $espaciosEncabezados
     */
    function mostrarCeldaEspacios($espaciosEncabezados,$semanas,$enlaceEspacio,$aprobado,$comentario){
      ?>
        <tr>
            <td class='cuadro_plano centrar' width="40"><?echo $espaciosEncabezados['COD_ESPACIO'];?></td>
            <td class='cuadro_plano'><?echo $espaciosEncabezados['NOMBRE_ESPACIO'].$semanas;?></td>
            <td class='cuadro_plano derecha'><font color='<?echo (isset($color)?$color:'');?>'><?echo $espaciosEncabezados['CREDITOS_ESPACIO']?></font></td>
            <td class='cuadro_plano derecha'><font color="<?echo (isset($color)?$color:'');?>"><?echo $espaciosEncabezados['HTD']?></font></td>
            <td class='cuadro_plano derecha'><font color="<?echo (isset($color)?$color:'');?>"><?echo $espaciosEncabezados['HTC']?></font></td>
            <td class='cuadro_plano derecha'><font color="<?echo (isset($color)?$color:'');?>"><?echo $espaciosEncabezados['HTA']?></font></td>
            <td class='cuadro_plano'><?echo $espaciosEncabezados['CLASIFICACION_ESPACIO']?></td>
            <td class='cuadro_plano centrar' colspan="2"><?echo $aprobado?></td>
      <?
      echo $enlaceEspacio;
      echo $comentario;
    }

    /**
     * Esta funcion genera el rotulo correspondiente para espacios con duracion diferente a 16 semanas
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <int> $semanasEspacio
     * @return string 
     */
    function generarRotuloSemanas($semanasEspacio){
      switch ($semanasEspacio) {
        case '16':
          $semanas='';
          break;
        case '32':
          $semanas=' &nbsp;&nbsp;(Anualizado)';
          break;
        default:
          $semanas='';
          break;
      }
      return $semanas;
    }

    /**
     * Esta funcion verifica que el espacio cumple con relacion creditos-horas
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <array> $datosEspacio
     * @return string
     */
    function generarColorTexto($datosEspacio){
    if(48*$datosEspacio['CREDITOS_ESPACIO']==($datosEspacio['HTD']+$datosEspacio['HTC']+$datosEspacio['HTA'])*$datosEspacio['SEMANAS_ESPACIO'])
      {
        $color='#049713';
      }else
        {
          $color='#FF0000';
        }
      return $color;
    }

    /**
     * Esta funcion genera la celda que presenta el estado del espacio y la posibilidad de administrarlo
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <array> $datosCelda
     */
    function generarCeldaEstadoEspacio($datosCelda) {
      switch ($datosCelda['datosEspacio']['APROBADO_ESPACIO']) {
        case '0':
          $aprobado="En Proceso";
          $modificarEspacio=$this->$datosCelda['nombreEnlace']($datosCelda['paginaModificar'],$datosCelda['opcionModificar'],$datosCelda['datosEspacio'],$datosCelda['datosPlan']);
          $borrarEspacio=$this->$datosCelda['nombreEnlace']($datosCelda['paginaBorrar'],$datosCelda['opcionBorrar'],$datosCelda['datosEspacio'],$datosCelda['datosPlan']);
          $enlaceEspacio="<td class='cuadro_plano centrar'>
            <a href='".$modificarEspacio."' class='centrar'>
            <img src='".$this->configuracion['site'].$this->configuracion['grafico']."/editarGrande.png' width='25' height='25' border='0'><br><font size='1'>Editar</font>
            </a>
          </td>
          <td class='cuadro_plano centrar'>
            <a href='".$borrarEspacio."' class='centrar'>
            <img src='".$this->configuracion['site'].$this->configuracion['grafico']."/x.png' width='25' height='25' border='0'><br><font size='1'>Borrar</font>
            </a>
          </td>";
          break;

        case '1':
          $aprobado="Aprobado";
          $enlaceEspacio="<td class='cuadro_plano centrar' colspan='2'></td>";
          break;

        case '2':
          $aprobado="No Aprobado";
          $enlaceEspacio="<td class='cuadro_plano centrar' colspan='2'></td>";
          break;

        default:
          $aprobado="No Especificado";
          $enlaceEspacio="<td class='cuadro_plano centrar' colspan='2'></td>";
          break;
      }
      $celdaAprobado=array('enlaceEspacio'=>$enlaceEspacio,
                            'aprobado'=>$aprobado);
      return $celdaAprobado;
    }

    /**
     * Funcion que genera la celda de comentarios para los espacios academicos
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <array> $datosComentario: enlace, numero
     */
    function generarCeldaComentario($datosComentario) {
        $comentario="
        <td class='cuadro_plano centrar'>
          <a href=".$datosComentario['enlace']." class='centrar'>
            <img src='".$this->configuracion['site'].$this->configuracion['grafico']."/viewrel.png' width='25' height='25' border='0'><br>";
            if($datosComentario['numero'][0][0]>0) {
              $comentario.="Nuevos(".$datosComentario['numero'][0][0].")";
            }else
              {

              }
        $comentario.="</a>
        </td>";
        return $comentario;
    }

    /**
     * Esta funcion presenta el numero de creditos registrados y aprobados por nivel
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $creditosNivel
     * @param <type> $creditosAprobados
     */
    function mostrarCeldaCreditosNivel($creditosNivel,$creditosAprobados) {
    ?>
      <tr>
        <td class='cuadro_plano centrar'  colspan='6'>TOTAL CR&Eacute;DITOS:
          <?
          if($this->datosPlan[0]['COD_PLAN_ESTUDIO']=='261' ||$this->datosPlan[0]['COD_PLAN_ESTUDIO']=='262'||$this->datosPlan[0]['COD_PLAN_ESTUDIO']=='263'||$this->datosPlan[0]['COD_PLAN_ESTUDIO']=='269') {
              $creditosNiveles='36';
          }else {
              $creditosNiveles='18';
          }

          if($creditosNivel>$creditosNiveles) {
              ?><font color=red><?echo $creditosNivel?></font><?
          }else {
              ?><font color=blue><?echo $creditosNivel?></font><?
          }
    ?>
        </td>
        <td class='cuadro_plano centrar'  colspan='6'>TOTAL CR&Eacute;DITOS APROBADOS:
          <?
          if($creditosAprobados>$creditosNiveles) {
              ?><font color=red><?echo $creditosAprobados?></font><?
          }else {
              ?><font color=blue><?echo $creditosAprobados?></font><?
          }
    ?>
        </td>
      </tr>
      <tr>
          <td colspan='10'></td>
      </tr>
    <?
    }

    /**
     * Funcion que cuenta los creditos registrados y aprobados por nivel
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <array> $datosEspacios
     * @param <array> $datosEspaciosAsociados 
     */
    function contarCreditosPlan($datosEspacios,$datosEspaciosAsociados) {
      if (is_array($datosEspacios))
      {
        foreach ($datosEspacios as $key => $value) {
          $this->sumarCreditosClasificacion($value);
        }
      }
      if(is_array($datosEspaciosAsociados))
      {
          //var_dump($datosEspaciosAsociados);exit;
        foreach ($datosEspaciosAsociados as $key => $value) {
          if ($datosEspaciosAsociados[$key]['COD_ENCABEZADO']!=(isset($datosEspaciosAsociados[$key-1]['COD_ENCABEZADO'])?$datosEspaciosAsociados[$key-1]['COD_ENCABEZADO']:''))
          {
            $this->sumarCreditosClasificacionEncabezado($value);
          }
        }
      }
    }

    /**
     * Funcion que realiza la suma de creditos para cada nivel. Registra en variable de clase
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <array> $datosEspacios 
     */
    function sumarCreditosClasificacion($datosEspacios) {
        //Realiza el conteo de los creditos de los espacios aprobados por clasificacion y total
        if ((isset($datosEspacios['APROBADO_ESPACIO'])?$datosEspacios['APROBADO_ESPACIO']:'')==1)
            {
              switch ($datosEspacios['COD_CLASIFICACION_ESPACIO']) {
                case '1':
                  $this->creditosOB+=$datosEspacios['CREDITOS_ESPACIO'];
                  break;

                case '2':
                  $this->creditosOC+=$datosEspacios['CREDITOS_ESPACIO'];
                  break;

                case '3':
                  $this->creditosEI+=$datosEspacios['CREDITOS_ESPACIO'];
                  break;

                case '4':
                  $this->creditosEE+=$datosEspacios['CREDITOS_ESPACIO'];
                  break;

                case '5':
                  $this->creditosCP+=$datosEspacios['CREDITOS_ESPACIO'];
                  break;

                default:
                  break;
              }

              if ($datosEspacios['COD_CLASIFICACION_ESPACIO']!=5)
              {
                $this->creditosTotal+=$datosEspacios['CREDITOS_ESPACIO'];
              }elseif ($this->datosPlan[0]['PLAN_PROPEDEUTICO']==1 )
                    {
                       $this->creditosTotal+=$datosEspacios['CREDITOS_ESPACIO'];
                    }
            }
    }

    /**
     * Funcion que realiza la suma de creditos para cada nivel. Registra en variable de clase
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <array> $datosEspacios
     */
    function sumarCreditosClasificacionEncabezado($datosEspacios) {
        //Realiza el conteo de los creditos de los espacios aprobados por clasificacion y total
        if((isset($datosEspacios['APROBADO_ENCABEZADO'])?$datosEspacios['APROBADO_ENCABEZADO']:'')==1)
            {
              switch ($datosEspacios['CLASIF_ENCABEZADO']) {
                case '1':
                  $this->creditosOB+=$datosEspacios['CREDITOS_ENCABEZADO'];
                  break;

                case '2':
                  $this->creditosOC+=$datosEspacios['CREDITOS_ENCABEZADO'];
                  break;

                case '3':
                  $this->creditosEI+=$datosEspacios['CREDITOS_ENCABEZADO'];
                  break;

                case '4':
                  $this->creditosEE+=$datosEspacios['CREDITOS_ENCABEZADO'];
                  break;

                case '5':
                  $this->creditosCP+=$datosEspacios['CREDITOS_ENCABEZADO'];
                  break;

                default:
                  break;
              }

              if ($datosEspacios['CLASIF_ENCABEZADO']!=5)
              {
                $this->creditosTotal+=$datosEspacios['CREDITOS_ENCABEZADO'];
              }elseif ($this->datosPlan[0]['PLAN_PROPEDEUTICO']==1 )
                    {
                       $this->creditosTotal+=$datosEspacios['CREDITOS_ENCABEZADO'];
                    }

            }
    }

    /**
     * Funcion que presenta los creditos registrados y aprobados en el plan de estudios. Utiliza clase externa para presentar datos
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $datosPlan 
     */
    function presentarCreditosRegistradosPlan() {
      $mensajeEncabezado='CRÉDITOS APROBADOS POR VICERRECTORIA';
      $mensaje='Los cr&eacute;ditos aprobados por vicerrector&iacute;a, corresponden a la suma de cr&eacute;ditos de los espacios acad&eacute;micos<br>
                registrados por el coordinador y aprobados por vicerrector&iacute;a, para el plan de estudios.';
      $creditosAprobados=array(array('OB'=>$this->creditosOB,
                                    'OC'=>$this->creditosOC,
                                    'EI'=>$this->creditosEI,
                                    'EE'=>$this->creditosEE,
                                    'CP'=>$this->creditosCP,
                                    'TOTAL'=>$this->creditosTotal,
                                    'ENCABEZADO'=>$mensajeEncabezado,
                                    'MENSAJE'=>$mensaje,
                                    'PROPEDEUTICO'=>$this->datosPlan[0]['PLAN_PROPEDEUTICO']));
      $this->parametrosPlan->mostrarParametrosRegistradosPlan($creditosAprobados);
      $mensajeEncabezado='RANGOS DE CR&Eacute;DITOS INGRESADOS POR EL COORDINADOR *';
      $mensaje='*Los rangos de cr&eacute;ditos, corresponden a los datos que el Coordinador registr&oacute; como par&aacute;metros iniciales<br>
                del plan de estudio, seg&uacute;n lo establecido en el art&iacute;culo 12 del acuerdo 009 de 2006.';
      $parametrosAprobados=array(array('ENCABEZADO'=>$mensajeEncabezado,
                                        'MENSAJE'=>$mensaje,
                                        'PROPEDEUTICO'=>$this->datosPlan[0]['PLAN_PROPEDEUTICO']));
      $this->parametrosPlan->mostrarParametrosAprobadosPlan($this->datosPlan[0]['COD_PLAN_ESTUDIO'],$parametrosAprobados);
    }

    /**
     * Funcion para consultar datos del plan de estudios
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $planEstudio
     * @return <type>
     */
    function consultarDatosPlan($planEstudio) {
      $cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarDatosPlanEstudio",$planEstudio);
      return $registroPlan=$this->accesoGestion->ejecutarAcceso($cadena_sql,"busqueda");
    }

    /**
     * Esta funcion consulta los niveles del plan de estudios
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $planEstudio
     * @return <type>
     */
    function consultarNiveles($planEstudio) {
      $cadena_sql = $this->sql->cadena_sql($this->configuracion, "consultaNivelesPlan", $planEstudio);
      return $registroNivelesPlan = $this->accesoGestion->ejecutarAcceso($cadena_sql,"busqueda");
    }

    /**
     * Funcion que consulta los espacios academicos del plan de estudios que no estan asociados
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $param
     */
    function consultarEspaciosPlan($datosEspacios) {
      $cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultaEspacioPlan",$datosEspacios);
      return $registroEspaciosPlan=$this->accesoGestion->ejecutarAcceso($cadena_sql,"busqueda");
    }
    /**
     * Este función consulta los codigos de los espacios que estan asociados
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $planEstudio
     * @return <type>
     */
    function consultarEspaciosAsociados($planEstudio) {
      $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarEspaciosAsociados",$planEstudio);
      return $registroAsociados=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
    }

    /**
     * Funcion que consulta los encabezados junto con los espacios asociados a cada uno
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <type> $planEstudio
     * @return <type>
     */
    function consultarEncabezadosConEspacios($planEstudio) {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarDatosEncabezadosEspacios",$planEstudio);
        return $registroEncabezadosEspaciosPlan=$this->accesoGestion->ejecutarAcceso($cadena_sql,"busqueda");
    }

    /**
     * Funcion que consulta los espacios propedeuticos no asociados
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @return <array> 
     */
    function consultarEspaciosPropedeuticosPlan() {
      $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarPropedeutico",$this->espaciosAsociados);
      return $registroPropedeuticos=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");
    }

    /**
     * Funcion que consulta los espacios propedeuticos con encabezados
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @return <array> 
     */
    function consultarEncabezadosPropedeuticosConEspacios() {
      $this->cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarDatosEncabezadosEspaciosPropedeuticos",$this->espaciosAsociados);
      return $registroPropedeuticosAsociados=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

    }

    /**
     * Funcion que permite consultar los somentarios del espacio o nombre general
     * Se crea para presentar el componente propedeutico 24/11/2011
     * @param <array> $datosEspacio
     * @return <type> 
     */
    function consultarComentariosNoLeidos($datosEspacio) {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"comentariosNoLeidos",$datosEspacio);
        return $comentariosNoLeidos=$this->accesoGestion->ejecutarAcceso($cadena_sql,"busqueda");
    }
}
?>
