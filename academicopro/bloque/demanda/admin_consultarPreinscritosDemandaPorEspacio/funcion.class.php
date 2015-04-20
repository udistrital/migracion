
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

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_adminConsultarPreinscritosDemandaPorEspacio extends funcionGeneral {
    
    public $configuracion;
    public $ano;
    public $periodo;
    public $totalInscritos;
    public $totalGrupos;

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        $this->configuracion=$configuracion;
        $this->totalInscritos=0;
        $this->totalGrupos=0;
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->sql=new sql_adminConsultarPreinscritosDemandaPorEspacio($configuracion);
        $this->formulario="admin_consultarPreinscritosDemandaPorEspacio";
        $this->bloque="demanda/admin_consultarPreinscritosDemandaPorEspacio";

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion=new sesiones($configuracion);
        $this->resultadoSesion=$obj_sesion->rescatar_valor_sesion($configuracion,"acceso");
        $this->id_accesoSesion=$this->resultadoSesion[0][0];

        //Datos de sesion
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        
        //Buscar año y periodo activos
        $cadena_sql = $this->sql->cadena_sql("periodo_activo","");
        $resultado_peridoActivo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        $this->ano=$resultado_peridoActivo[0]['ANO'];
        $this->periodo=$resultado_peridoActivo[0]['PERIODO'];        



    }
    
    function mostrarListadoEspacios() {
        $this->buscador();
        
    }
    
    /**
     * Funcion que crea el buscador de espacios academicos por codigo (tambien existe en el framework el buscador por nombre)
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param int $planEstudioCoor plan de estudio
     * @param int $codProyecto Codigo del proyecto curricular
     */
    function buscador() {

?>
      <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <div align="center">
                    <table class="sigma centrar" cellspacing=0px>
                        <caption class="sigma centrar">
                            ESPACIO ACAD&Eacute;MICO
                        </caption>
                        <tr class="sigma centrar">

                                <td class="sigma" rowspan="2">
                                  <input type="hidden" name="action" value="<? echo $this->bloque ?>">
                                  <input type="radio" name="opcion" value="buscarCodigo" checked> C&oacute;digo<br>                                  
                                </td>

                                <td>
                                  <input type="text" name="datosBusqueda" size="20px">
                                </td>                                
                                <td>
                                    <small><input class="boton" type="submit" value=" Buscar "></small>
                                </td>
                                </tr>

                       </table>
                    </div>
            </form><hr>
          <table class="contenidotabla centrar">
              <tr>
                  <td class="centrar" colspan="2">
                      <a onclick="window.open('<?echo $this->configuracion['site'].$this->configuracion['bloques']?>/adminManuales/preinscripciones/inscripcionPorDem-perfilCoordinador.htm','Rangos Plan Estudio')" >
                          <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/folder_video.png" border=0><br>Ver Tutorial de Preinscripci&oacute;n por demanda</a>
                  </td>
              </tr>
          </table>            
<?

        }             
                    
    /**
     * Presenta los espacios el espacio academico que ha sido buscado por codigo, con la siguiente información:
     * Datos del espacio academico
     * Datos de la facultad
     * Registro de cada grupo creado con:proyecto que lo ofrece, numero del grupo, cupos, numero de inscritos
     * 
     */
    function mostrarEspaciosOpcionCodigo(){

          $this->buscador();

          //verifica el dato ingresado en el buscador
          if($_REQUEST['codEspacio'] && is_numeric($_REQUEST['codEspacio'])){

                $datosEspacio=$this->buscarDatosEspacio($_REQUEST['codEspacio']);
                
                //si no encuentra el espacio en la base de datos presenta mensaje               
                if(!is_array($datosEspacio)){
                    echo 'El espacio acad&eacute;mico con c&oacute;digo <b>'.$_REQUEST['codEspacio'].'</b> no existe';
                    exit;
                    }
                
                $this->mostrarDatosEspacio($datosEspacio);

                $facultad=  $this->buscarFacultad($_REQUEST['codEspacio']);
                
                ?><table class="Cuadricula" style="font-size:12px"><?
                    
                        foreach ($facultad as $key => $value) {

                            $this->mostrarDatosFacultad($value['CODIGO'],$value['NOMBRE']);                    

                            $resultadoProyectosFacultad=$this->buscarProyectosFacultad($_REQUEST['codEspacio'], $value['CODIGO']);

                            $this->mostrarProyectos($resultadoProyectosFacultad, $_REQUEST['codEspacio']);

                        }
                        
                ?></table>

                    <br>
                    <?
              
                    $this->mostrarTotales();


          }
          else {echo 'Por favor ingrese un valor num&eacute;rico';}

      }  
   
     /**
     *
     */
    function mostrarDatosEspacio($datosEspacio) {
      
        ?>                
        <div class="h1">
            <table align="center">
                <tr class="encabezado_registro centrar">
                    <td>
                        <b><?echo $datosEspacio[0]['CODIGO']?></b>
                    </td>
                    <td>
                        -
                    </td>
                    <td>
                        <b><?echo $datosEspacio[0]['NOMBRE']?></b>
                    </td>
                </tr>
            </table>            
        </div>             
                
        <?
                 

    } 
    
    /**
     *Presenta codigo y nombre de la facultad
     * @param type $codigo Codigo de la facultad
     * @param type $nombre Nombre de la facultad
     */
    function mostrarDatosFacultad($codigo,$nombre) {
        
        ?>
            <tr>
                <td colspan="4">
                   <b><?echo $codigo.' - '.$nombre;?></b>                                   
                </td>
            </tr>
        <?          
        
    }
      
    /**
     *Presenta los datos de los grupos en en filas de una tabla (COD_PROYECTO, NOMBRE_PROYECTO, GRUPO, CUPO, INSCRITOS)
     * 
     * @param type $grupos 
     */
    function mostrarProyectos($proyectos,$codEspacio) {
    
                        ?>
                    <tr align="center">
                        <td>C&oacute;digo</td>
                        <td>Proyecto</td>
                        <td>Preinscritos</td>
                    </tr>
                <?
        
        $totalInscritosFacultad=0;
        $totalProyectosFacultad=0;
        foreach ($proyectos as $key => $value) {
          $enlace=$this->crearEnlaceProyecto($proyectos[$key],$codEspacio);
          $totalPreinscritosProyecto=$this->buscarNumeroEstudiantesPreinscritosProyecto($proyectos[$key]['CODIGO'],$codEspacio);
                ?>
                    <tr class='bloquecentralcuerpo' onclick="location.href='<?=$enlace?>'" style="cursor:pointer" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                    <!--<tr align="center">-->
                        <td class="contenidotabla" border="1px"><?echo $value['CODIGO']?></td>
                        <td align="left"><?echo $value['NOMBRE']?></td>
                        <td><?echo $totalPreinscritosProyecto?></td>
                    </tr>
                <?
        $totalInscritosFacultad=$totalInscritosFacultad+$totalPreinscritosProyecto;
        $this->totalInscritos=$this->totalInscritos+$totalPreinscritosProyecto;
        $totalProyectosFacultad=$totalProyectosFacultad+1;
        $this->totalGrupos=$this->totalGrupos+1;
        }
        ?><tr>
            <td align="right" colspan="2"><br>Total inscritos Facultad:</td>
            <td align="center">
                <b>
             <?             
             echo $totalInscritosFacultad;    
             ?> </b> 
            </td>
        </tr>
        <tr>
            <td align="right" colspan="2">Total Proyectos que incluyen el espacio:</td>
            <td align="center"><b>
             <?             
             echo $totalProyectosFacultad;             
             ?>   </b>
            </td>
        </tr>  
        <tr><td colspan="5"><hr></td></tr>
       <?
    }        
    
    /**
     * Presenta el valor de los atributos totalInscritos y totalGrupos
     */
    function mostrarTotales() {
     
        ?>
            <table  class="Cuadricula" style="font-size:15px">
                <tr>
                    <td>
                        <?echo 'Total inscritos: <b>'.$this->totalInscritos?></b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?echo 'Total proyectos: <b>'.$this->totalGrupos?></b>
                    </td>
                </tr>
            </table>
        <?
    }
    
    /**
         *
         */
    function buscarDatosEspacio($codigo) {
                       
              $variablesEspacios = array( 'codEspacio' => $codigo,
                                          'ano'=>$this->ano,
                                          'periodo'=>  $this->periodo,
                                          'opcion' => 'codigo');

              $cadena_sql = $this->sql->cadena_sql("buscarDatosEspacio", $variablesEspacios);
              $arreglo_datos_espacio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_datos_espacio;
    }            

    function mostrarListadoProyecto() {                      

      $this->buscador();
      $datosEspacio=$this->buscarDatosEspacio($_REQUEST['codEspacio']);
      $this->mostrarDatosEspacio($datosEspacio);

      $this->mostrarEnlaceRetorno($_REQUEST['codProyecto'],$_REQUEST['codEspacio']);
      
        $numeroPreinscritos=$this->buscarNumeroEstudiantesPreinscritosProyecto($_REQUEST['codProyecto'],$_REQUEST['codEspacio']);      
        $preinscritos=  $this->buscarEstudiantesPreinscritosProyecto($_REQUEST['codProyecto'],$_REQUEST['codEspacio']);   
      if (is_array($preinscritos)&& $preinscritos[0][0]>0)
      {        
                
        $this->PresentarDatosProyecto($_REQUEST['codProyecto'],$_REQUEST['nombreProyecto'],$numeroPreinscritos);
        $this->presentarEstudiantesInscritos($preinscritos);
      }else
      {
        $this->presentarDatosProyecto($_REQUEST['codProyecto'],$_REQUEST['nombreProyecto'],$numeroPreinscritos);
        $this->presentarNoEstudiantesInscritos();
      }
    }

    function crearEnlaceProyecto($proyecto,$codEspacio) {
           
            $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $ruta = "pagina=".$this->formulario;
            $ruta.= "&opcion=consultarListadoEstudiantes";
            $ruta.= "&codEspacio=" . $codEspacio;
            $ruta.= "&codProyecto=" . $proyecto['CODIGO'];
            $ruta.= "&nombreProyecto=" . $proyecto['NOMBRE'];
            $ruta = $this->cripto->codificar_url($ruta,  $this->configuracion);
            return $enlace=$indice.$ruta;

}

    /**
     *Busca las facultades que ofrecen el espacio academico
     * 
     * @param type $codigo
     * @return type 
     */
    function buscarFacultad($codigo) {

              $variablesEspacios = array( 'codEspacio' => $codigo,
                                          'ano'=>$this->ano,
                                          'periodo'=>  $this->periodo,
                                          'opcion' => 'codigo');

              $cadena_sql = $this->sql->cadena_sql("buscarFacultad", $variablesEspacios);
              $arreglo_facultad = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");              
              return $arreglo_facultad;
    }
    
    /**
         *
         */
    function buscarProyectosFacultad($codigo, $facultad) {
                       
              $variablesEspacios = array( 'codEspacio' => $codigo,
                                          'codFacultad' => $facultad,
                                        );

              $cadena_sql = $this->sql->cadena_sql("buscarProyectosFacultad", $variablesEspacios);
              $arreglo_proyectos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_proyectos;
    }   
    
    /**
     *
     * @param type $proyecto
     * @param type $codEspacio 
     */
    function mostrarEnlaceRetorno($proyecto,$codEspacio) {
        
        
            $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $ruta = "pagina=".$this->formulario;
            $ruta.= "&opcion=consultarPorCodigo";
            $ruta.= "&codEspacio=" . $codEspacio;
            $ruta.= "&codProyecto=" . (isset($proyecto['CODIGO'])?$proyecto['CODIGO']:'');
            $ruta.= "&nombreProyecto=" . (isset($proyecto['NOMBRE'])?$proyecto['NOMBRE']:'');
            $ruta = $this->cripto->codificar_url($ruta,  $this->configuracion);
            $enlace=$indice.$ruta;            
            ?>
            <a href="<?echo $enlace?>">Regresar</a>
            <?
            
    }

    function presentarDatosProyecto($codigo,$nombre,$preinscritos) {
      ?>
        <table class='contenidotabla'>
              <tr class="cuadro_color">
                  <td class='cuadro_plano centrar' colspan="1"><?echo $codigo;?></td>
                  <td class='cuadro_plano centrar' colspan="2"><?echo $nombre;?></td>
                  <td class='cuadro_plano centrar'>Preinscritos: <?echo $preinscritos;?></td>
              </tr>
              <?

    }

    function presentarEstudiantesInscritos($datosInscritos) {
      ?>
        
              <tr class="cuadro_color">
                  <td class='cuadro_plano centrar'>Nro</td>
                  <td class='cuadro_plano centrar'>Cod. Estudiante</td>
                  <td class='cuadro_plano centrar' width="250">Nombre</td>
                  <td class='cuadro_plano centrar'>Estado</td>
              </tr>
              <?  for ($a = 0; $a < count($datosInscritos); $a++) {
                
              ?>
              <tr>
                  <td class='cuadro_plano centrar'><?echo $a+1;?></td>
                  <td class='cuadro_plano centrar'><?echo $datosInscritos[$a]['CODIGO'];?></td>
                  <td class='cuadro_plano centrar'><?echo $datosInscritos[$a]['NOMBRE'];?></td>
                  <?
                  switch($datosInscritos[$a]['ESTADO'])
                  {
                  case "A":
                      $estado="Activo";
                      break;

                  case "B":
                      $estado="Prueba y Activo";
                      break;

                  case "J":
                      $estado="Prueba y Vacaciones";
                      break;

                  case "V":
                      $estado="Vacaciones";
                      break;

                  default :
                      $estado="Inactivo";
                    break;

                  }
                  ?>
                  <td class='cuadro_plano centrar'><?echo $estado;?>
              </tr>
              <?}?>
          </table>
      <?
    }

    function presentarNoEstudiantesInscritos() {
      ?>

              <tr class="cuadro_color">
                <td class='cuadro_plano centrar' colspan="5">NO HAY ESTUDIANTES PREINSCRITOS</td>
              </tr>
          </table>
      <?
    }

    function buscarNumeroEstudiantesPreinscritosProyecto($proyecto,$espacio) {
              
    $variables = array( 'codProyecto' => $proyecto,
                        'codEspacio' => $espacio,
                        'ano'=>$this->ano,
                        'periodo'=>  $this->periodo,
                       );      
      
      $cadena_sql = $this->sql->cadena_sql("buscarNumeroEstudiantesPreinscritos", $variables);
      $numeroEstudiantesPreinscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
      
      return $numeroEstudiantesPreinscritos[0][0];

}

    function buscarEstudiantesPreinscritosProyecto($proyecto, $espacio) {
        
    $variables = array( 'codProyecto' => $proyecto,
                        'codEspacio' => $espacio,
                        'ano'=>$this->ano,
                        'periodo'=>  $this->periodo,
                       );         
        
      $cadena_sql = $this->sql->cadena_sql("buscarEstudiantesPreinscritosProyecto", $variables);
      return $datosEstudiantesInscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

}

}
?>
