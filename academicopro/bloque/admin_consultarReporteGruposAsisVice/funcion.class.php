
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
class funcion_adminConsultarReporteGruposAsisVice extends funcionGeneral {
    
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
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->sql=new sql_adminConsultarReporteGruposAsisVice($configuracion);
        $this->formulario="admin_consultarReporteGruposAsisVice";

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
                                  <input type="hidden" name="action" value="<? echo $this->formulario ?>">
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
            </form>
    <?      echo '<hr>';
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
                $this->mostrarDatosEspacio($datosEspacio);
                $facultad= $this->buscarFacultad($_REQUEST['codEspacio']);
                if(is_array($facultad))
                {
                ?><table class="Cuadricula" style="font-size:12px"><?
                        foreach ($facultad as $key => $value) {
                            $this->mostrarDatosFacultad($value['CODIGO'],$value['NOMBRE']);                    
                            $resultadoGrupos=$this->buscarGruposEspacio($_REQUEST['codEspacio'], $value['CODIGO']);
                            $this->mostrarGrupos($resultadoGrupos, $_REQUEST['codEspacio']);
                        }
                ?></table>
                    <br>
                    <?
                    $this->mostrarTotales();
                }else
                    {
                        echo "<table width ='100%'><tr><td align='center'>No hay estudiantes inscritos en este espacio acad&eacute;mico</tr></td></table>";
                    }
          }
          else {echo 'Por favor ingrese un valor num&eacute;rico';}
      }  

      /**
       * Funcion para presentar los datos del espacio academico
       * @param type $datosEspacio
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
                <td colspan="5">
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
    function mostrarGrupos($grupos,$codEspacio) {
        $totalInscritosFacultad=0;
        $totalGruposFacultad=0;

        if(!is_array($grupos))
        {   
            ?>
                <tr align="center">
                    <td>No hay grupos en la Facultad</td>
                </tr>
            <?
        }else{
                        ?>
                    <tr align="center">
                        <td>C&oacute;digo</td>
                        <td>Proyecto</td>
                        <td>Grupo</td>
                        <td>Cupos</td>
                        <td>Inscritos</td>
                    </tr>
                <?
        
        foreach ($grupos as $key => $value) {
          $enlace=$this->crearEnlaceGrupo($grupos[$key],$codEspacio)
                ?>
                    <tr class='bloquecentralcuerpo' onclick="location.href='<?=$enlace?>'" style="cursor:pointer" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                    <!--<tr align="center">-->
                        <td class="contenidotabla" border="1px"><?echo $value['COD_PROYECTO']?></td>
                        <td align="left"><?echo $value['NOMBRE_PROYECTO']?></td>
                        <td><?echo $value['GRUPO']?></td>
                        <td><?echo $value['CUPO']?></td>
                        <td><?echo $value['INSCRITOS']?></td>
                    </tr>
                <?
        $totalInscritosFacultad=$totalInscritosFacultad+$value['INSCRITOS'];
        $this->totalInscritos=$this->totalInscritos+$value['INSCRITOS'];
        $totalGruposFacultad=$totalGruposFacultad+1;
        $this->totalGrupos=$this->totalGrupos+1;
        }
                ?><tr>
            <td align="right" colspan="4"><br>Total inscritos Facultad:</td>
            <td align="center">
                <b>
             <?             
             echo $totalInscritosFacultad;    
             ?> </b> 
            </td>
        </tr>
        <tr>
            <td align="right" colspan="4">Total grupos Facultad:</td>
            <td align="center"><b>
             <?             
             echo $totalGruposFacultad;             
             ?>   </b>
            </td>
        </tr>  
       <?
        }
        ?>
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
                        <?echo 'Total grupos: <b>'.$this->totalGrupos?></b>
                    </td>
                </tr>
            </table>
        <?
    }
    
    /**
     * Funcion que permite consultar los datos del espacio academico
     * @param type $codigo
     * @return type
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

    /**
     * Funcion que reune los metodos necesario para presentar el reporte de estudiantes inscritos en un grupo
     */
    function mostrarListadoGrupo() {
      $datosGrupo=array('codEspacio' => $_REQUEST['codEspacio'],
                        'ano'=>$this->ano,
                        'periodo'=>  $this->periodo,
                        'grupo' => $_REQUEST['grupo'],
                        'codProyecto'=>$_REQUEST['codProyecto'],
                        'nombreProyecto'=>$_REQUEST['nombreProyecto']);

      $this->buscador();
      $datosEspacio=$this->buscarDatosEspacio($_REQUEST['codEspacio']);
      $this->mostrarDatosEspacio($datosEspacio);

      //crearEnlaceRetorno;
      $infoGrupo=$this->buscarDatosGrupo($datosGrupo);
      $docentesGrupo=$this->buscarDocentesGrupo($datosGrupo);
      $numeroInscritos=$this->buscarNumeroEstudiantesInscritosGrupo($datosGrupo);
      if (is_array($numeroInscritos)&& $numeroInscritos[0][0]>0)
      {
        $inscritos=$this->buscarEstudiantesInscritosGrupo($datosGrupo);
        $this->presentarEnlaceRetorno($_REQUEST['codEspacio']);
        $this->presentarDatosGrupo($infoGrupo[0],$docentesGrupo,$numeroInscritos);
        $this->presentarEstudiantesInscritos($inscritos);
      }else
      {
        $this->presentarDatosGrupo($infoGrupo[0],$docentesGrupo,$numeroInscritos);
        $this->presentarNoEstudiantesInscritos();
      }
    }

    /**
     * Funcion que crea el enlace para consultar el detalle de los inscritos en un grupo
     * @param type $grupo
     * @param type $codEspacio
     * @return type
     */
    function crearEnlaceGrupo($grupo,$codEspacio) {
            $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $ruta = "pagina=".$this->formulario;
            $ruta.= "&opcion=consultarGrupo";
            $ruta.= "&codEspacio=" . $codEspacio;
            $ruta.= "&grupo=" . $grupo['ID_GRUPO'];
            $ruta.= "&codProyecto=" . $grupo['COD_PROYECTO'];
            $ruta.= "&nombreProyecto=" . $grupo['NOMBRE_PROYECTO'];
            $ruta = $this->cripto->codificar_url($ruta,  $this->configuracion);
            return $enlace=$indice.$ruta;

    }
    
    /**
     * Función que crea enlace para retornar al reporte de inscritos en la facultad
     * @param type $codEspacio
     * @return type
     */
    function crearEnlaceRetorno($codEspacio) {
            $indice = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $ruta = "pagina=".$this->formulario;
            $ruta.= "&opcion=consultarPorCodigo";
            $ruta.= "&codEspacio=" . $codEspacio;
            $ruta = $this->cripto->codificar_url($ruta,  $this->configuracion);
            return $enlace=$indice.$ruta;

    }

    /**
     * Función que presenta el enlace para retornar al reporte por facultad
     * @param type $codEspacio
     */
    function presentarEnlaceRetorno($codEspacio) {
    $enlace=$this->crearEnlaceRetorno($codEspacio);
    ?><table class='contenidotabla' width="100%">
        <tr onclick="location.href='<?=$enlace?>'" style="cursor:pointer" onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
            <td class='centrar'>
                Volver al reporte por facultad
            </td>
        </tr>
        </table><?
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
     * Funcion que consulta los grupos creados para un espacio academico en la facultad
     * @param type $codigo
     * @param type $facultad
     * @return type
     */
    function buscarGruposEspacio($codigo, $facultad) {
                       
              $variablesEspacios = array( 'codEspacio' => $codigo,
                                          'codFacultad' => $facultad,
                                          'ano'=>$this->ano,
                                          'periodo'=>  $this->periodo,
                                          'opcion' => 'codigo');

              $cadena_sql = $this->sql->cadena_sql("buscarGruposEspacio", $variablesEspacios);
              $arreglo_espacio_busqueda = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
              return $arreglo_espacio_busqueda;
    }   

    /**
     * Funcion que permite presentar los datos de un grupo del espacio academico
     * @param type $datosGrupo
     * @param type $docentesGrupo
     * @param type $numeroInscritos
     */
    function presentarDatosGrupo($datosGrupo,$docentesGrupo,$numeroInscritos) {
      ?><table class='contenidotabla'>
              <tr><td colspan="5"><br></td></tr>
              <tr class="cuadro_color">
                  <td class='cuadro_plano centrar' colspan="2">Grupo</td>
                  <td class='cuadro_plano centrar' colspan="2">Proyecto que lo ofrece</td>
                  <td class='cuadro_plano centrar'>Cupo: <?echo $datosGrupo['CUPO']?></td>
              </tr>
              <tr class="cuadro_color">
                  <td class='cuadro_plano centrar' colspan="2"><?echo $datosGrupo['GRUPO'];?></td>
                  <td class='cuadro_plano centrar' colspan="2"><?echo $datosGrupo['NOMBRE'];?></td>
                  <td class='cuadro_plano centrar'>Inscritos: <?echo $numeroInscritos[0][0];?></td>
              </tr>
              <?
              
              ?>
              <tr>
                  <td class='cuadro_plano centrar' colspan="2">Docente(s):</td>
                  <td class='cuadro_plano centrar' colspan="3">
              <?
              if(is_array($docentesGrupo))
              {
                  ?><font color=blue><?
                  for($d=0; $d<count($docentesGrupo); $d++)
                  {
                      echo $docentesGrupo[$d][0]." ".$docentesGrupo[$d][1]."<br>";
                  }
              }
              else
              {
                  ?><font color=red><?
                  echo "No hay docentes asignados al grupo";
              }
              ?>
                      </font>
                  </td>
              </tr>
              <?
    }

    /**
     * Funcion que permite presentar el listado de estudiantes inscritos en un grupo
     * @param type $datosInscritos
     */
    function presentarEstudiantesInscritos($datosInscritos) {
      ?>
        
              <tr class="cuadro_color">
                  <td class='cuadro_plano centrar'>Nro</td>
                  <td class='cuadro_plano centrar'>Cod. Estudiante</td>
                  <td class='cuadro_plano centrar' width="250">Nombre</td>
                  <td class='cuadro_plano centrar'>Estado</td>
                  <td class='cuadro_plano centrar'>Proyecto Curricular del Estudiante</td>
              </tr>
              <?  for ($a = 0; $a < count($datosInscritos); $a++) {
                
              ?>
              <tr>
                  <td class='cuadro_plano centrar'><?echo $a+1;?></td>
                  <td class='cuadro_plano centrar'><?echo $datosInscritos[$a][1];?></td>
                  <td class='cuadro_plano centrar'><?echo $datosInscritos[$a][2];?></td>
                  <?
                  switch($datosInscritos[$a][3])
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
                  <td class='cuadro_plano centrar'><?echo $datosInscritos[$a][4];?>
              </tr>
              <?}?>
          </table>
      <?
    }

    /**
     * Funcion para presentar mensaje de grupo sin estudiantes inscritos
     */
    function presentarNoEstudiantesInscritos() {
      ?>

              <tr class="cuadro_color">
                <td class='cuadro_plano centrar' colspan="5">NO HAY ESTUDIANTES INSCRITOS EN ESTE GRUPO</td>
              </tr>
          </table>
      <?
    }

    /**
     * Funcon que permite consultar los datos del grupo
     * @param type $datosGrupo
     * @return type
     */
    function buscarDatosGrupo($datosGrupo) {
      $cadena_sql = $this->sql->cadena_sql("buscarDatosGrupo", $datosGrupo);
      return $arreglo_datosGrupo = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

    }
    /**
     * Funcion que pemrite consultar los docentes de un grupo
     * @param type $datosGrupo
     * @return type
     */
    function buscarDocentesGrupo($datosGrupo) {
      $cadena_sql = $this->sql->cadena_sql("consultaDocenteGrupo", $datosGrupo);
      return $arreglo_datosGrupo = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

    }
    /**
     * Funcion que permite consultar el numero de estudiantes inscritos en un grupo
     * @param type $datosGrupo
     * @return type
     */
    function buscarNumeroEstudiantesInscritosGrupo($datosGrupo) {
      $cadena_sql = $this->sql->cadena_sql("buscarNumeroEstudiantesInscritos", $datosGrupo);
      return $numeroEstudiantesInscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

    }

    /**
     * Funcion que permite consultar los estudiantes inscritos en un grupo
     * @param type $datosGrupo
     * @return type
     */
    function buscarEstudiantesInscritosGrupo($datosGrupo) {
        $cadena_sql = $this->sql->cadena_sql("consultarInscritosGrupo", $datosGrupo);
        return $datosEstudiantesInscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

    }

}
?>
