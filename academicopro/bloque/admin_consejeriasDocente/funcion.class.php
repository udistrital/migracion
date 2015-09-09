<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/reglasConsejerias.class.php");


//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_admin_consejeriasDocente extends funcionGeneral {

    public $configuracion;
    public $numero=0; //cuenta los filas de la tabla de estudiantes asociados
    public $usuario;
    public $nivel;
    public $codProyecto;


        //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($this->configuracion["raiz_documento"].$this->configuracion["estilo"]."/".$this->estilo."/tema.php");
        
		//this->codProyecto=$_REQUEST['codProyecto'];		
		$this->codProyecto=isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:"";
		
		
      
        $this->configuracion=$configuracion;
        $this->cripto = new encriptar();
        
		//$this->tema = $tema;
		$this->tema=isset($tema)?$tema:"";
		
        $this->sql = new sql_admin_consejeriasDocente($configuracion);
        $this->log_us = new log();
        $this->formulario = "admin_consejeriasDocente";
        $this->reglasConsejerias = new reglasConsejerias();

        //Conexion ORACLE
        $this->accesoOracle = $this->conectarDB($this->configuracion, "oraclesga");


        //Conexion General
        $this->acceso_db = $this->conectarDB($this->configuracion, "");
     

        $this->accesoGestion = $this->conectarDB($this->configuracion, "mysqlsga");


        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion = new sesiones($this->configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($this->configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        //Datos de sesion
        $this->usuario = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "identificacion");
        $this->nivel = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "nivelUsuario");

    }


    function verProyectos() {        
      $proyectoConsejeriaDocente=$this->consultarProyectoConsejeriaDocente();
      //presenta listado de proyectos en caso de que el docente esté asignado a más de uno
        if(count($proyectoConsejeriaDocente)>1){

        ?>
<table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <br><br>
    <tr class="centrar">
        <td class="cuadro_color centrar" colspan="2">
          SELECCIONE EL PROYECTO CURRICULAR
        </td>
    </tr>
    <tr>
      <td class="cuadro_color centrar" width="20%"><b>C&oacute;digo</b></td>
      <td class="cuadro_color centrar" width="80%"><b>Nombre</b></td>
    </tr>


        <?               
            for($i=0;$i<count($proyectoConsejeriaDocente);$i++) {
                ?>
                    <tr>

                <?
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";                
                $variable="pagina=admin_consejeriasDocente";
                $variable.="&opcion=verEstudiantes";
                $variable.="&codProyecto=".$proyectoConsejeriaDocente[$i]['COD_PROYECTO'];
                $variable.="&nombreProyecto=".$proyectoConsejeriaDocente[$i]['NOMBRE_PROYECTO'];
                $variable= $this->cripto->codificar_url($variable, $this->configuracion);

                ?>
            <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $proyectoConsejeriaDocente[$i]['COD_PROYECTO']?></a></td>
            <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $proyectoConsejeriaDocente[$i]['NOMBRE_PROYECTO']?></a></td>
                </tr>
    <?

            }
        ?>

    
</table>

        <?
    }else
        {
            $this->codProyecto=$proyectoConsejeriaDocente[0]['COD_PROYECTO'];
            $this->consultarEstudiantes();
        }


    }    
    
    /**
     *Presenta encabezado de lista de estudiantes por riesgo 
     */
    function consultarEstudiantes() {
        if(!isset($this->codProyecto))
        {
            ?>
                <table class="contenidotabla centrar">
                    <tr class="sigma">
                        <td class="sigma_a centrar">
                            NO TIENE PROYECTOS ASOCIADOS PARA CONSEJERIAS
                        </td>
                    </tr>
                </table>
            <?
            exit;
        }
        $codDocente = $this->usuario;//numero de documento del docente
        $nivelDocente = $this->nivel;
        
        $variablesDocente = array($codDocente, $nivelDocente);
        //$periodoActual=  $this->buscarPeriodoActual();       
             
        $estudiantesAsociados=  $this->buscarEstudiantesAsociados();        
        
        if (is_array($estudiantesAsociados)) {            
                            
              $this->mostrarEnlaceComunicaciones();
              
?>
        <table class="contenidotabla centrar">
            
<?
                 $this->mostrarEncabezadoListaEstudiantes();
               
                 foreach ($estudiantesAsociados as $estudiante) {                     
                     $this->mostrar_registro_estudiante($estudiante, $codDocente);                     
                 }                 

        ?>
                        

            </table>
                <?
              $this->mostrarEnlaceComunicaciones();
                ?>


<?
                } else {
?>
                <table class="contenidotabla centrar">
                    <tr class="sigma">
                        <td class="sigma_a centrar">
                            NO TIENE ESTUDIANTES ASOCIADOS PARA CONSEJERIAS
                        </td>
                    </tr>
                </table>
<?
                }
            }

    /**
     *
     * @return type 
     */
    function consultarProyectoConsejeriaDocente() {

          //$variablesDocente = array(codDocente => $this->usuario);
		  $variablesDocente = array('codDocente' => $this->usuario);

          $cadena_sql_proyectos=$this->sql->cadena_sql("buscarProyectosConsejeriaDocente",$variablesDocente);
          $arreglo_proyectos=$this->ejecutarSQL("", $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
          return $arreglo_proyectos;
    }            
    
    function mostrarEncabezadoListaEstudiantes(){
        ?>
        <caption class="sigma">ESTUDIANTES ASIGNADOS CONSEJERIAS PROYECTO <?echo $this->codProyecto.'-'.(isset($_REQUEST['nombreProyecto'])?$_REQUEST['nombreProyecto']:'')?></caption>
            <tr>
                <th class="sigma centrar" colspan="8">
                </th>
            </tr>
            <tr>
                <th class="sigma centrar" colspan="1">
                  Nro.
                </th>
                <th class="sigma centrar"><u>
                  <?
                  //filtro para ordenar por codigo
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variable = "pagina=admin_consejeriasDocente";
                  $variable.="&opcion=verEstudiantes";
                  $variable.="&codProyecto=".$this->codProyecto;
                  $variable.="&filtro=";
                  $variable= $this->cripto->codificar_url($variable, $this->configuracion);
                  ?><a href="<?= $pagina.$variable?>">C&oacute;digo <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/filtro_tabla.png" width="10" height="12" border="0"></a>
                  </u></th>
                <th class="sigma centrar" colspan="2">
                  Nombre del estudiante
                </th>
                <th class="sigma centrar" colspan="2">
                  Estado
                </th>
                <th class="sigma centrar" colspan="1">
                  Promedio
                </th>
                <th class="sigma centrar" colspan="1">
                  Modalidad
                </th>
            </tr>
            <?
        
    }


    /**
     *funcion que presenta el listado (registros) de los estudiantes ordenados por su riesgo
     * @param type $estudiante
     * @param type $docente 
     */
    function mostrar_registro_estudiante($estudiante, $docente){

                    //Las siguientes líneas se comentan porque ya no se presentan los circulos de riesgo.
                    //$motivoPrueba=  $this->buscarMotivoPrueba($estudiante);
                    //$riesgo=$this->reglasConsejerias->calcularRiesgo($motivoPrueba);
                    //$riesgo=explode(';', $riesgo);      
                    
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = "pagina=admin_consejeriasConsultaEstudiante";
                    $variable.="&opcion=verEstudiante";
                    $variable.="&filtro=".(isset($_REQUEST['filtro'])?$_REQUEST['filtro']:'');
                    $variable.="&codProyecto=".$this->codProyecto;
                    $variable.="&codEstudiante=" . $estudiante['CODIGO'];
                    $variable.="&codDocente=" .$docente;
                    //$variable.="&motivoPrueba=" .(isset($riesgo[1])?$riesgo[1]:'');                  
                    $variable.="&datoBusqueda=" .$estudiante['CODIGO'];
                    $variable.="&tipoBusqueda=codigo";

                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    $this->numero++;

                ?>
                    <tr>
                    <td class="cuadro_plano centrar" width=10% colspan="1"><a href="<? echo $pagina . $variable ?>"><? echo $this->numero?></a></td>
                    <td class="cuadro_plano centrar" width=10% colspan="1"><a href="<? echo $pagina . $variable ?>"><? echo $estudiante['CODIGO'] ?></a></td>
                    <td class="cuadro_plano" width=30% colspan="2"><a href="<? echo $pagina . $variable ?>"><? echo $estudiante['NOMBRE'] ?></a></td>
                    <td class="cuadro_plano" width=25% colspan="2"><a href="<? echo $pagina . $variable ?>"><? echo "<b>".$estudiante['ESTADO'].": </b> ".$estudiante['ESTADO_DESCRIPCION'] ?></a></td>
                    <?$promedio=$estudiante['PROMEDIO']?>
                    <td class="cuadro_plano centrar" width=5% colspan="1"><a href="<? echo $pagina . $variable ?>"><? echo $promedio;?></a></td>
                    <td class="cuadro_plano centrar" width=5% colspan="1">
                        <a href="<? echo $pagina . $variable ?>">
                          <?                                          
                            if(trim($estudiante['MODALIDAD'])=='S')
                              {echo 'CREDITOS';}
                            else
                              {echo 'HORAS';}
                          ?>
                        </a>
                    </td>
                    </tr>
            <?
        }


    function buscarEstudiantesAsociados() {
      
			$variables = array(  'codDocente' => $this->usuario,
                                             'codProyecto'=> $this->codProyecto  ,                                             
			);

          $cadena_sql = $this->sql->cadena_sql("buscarEstudiantesAsociados", $variables);
          $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado;
    }        
        
        
    function mostrarEnlaceComunicaciones() {

      $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
      $variable = "pagina=admin_mensajeDocente";
      $variable.="&opcion=verMensajesRecibidos";
      $variable.="&codProyecto=".$this->codProyecto;
      $variable= $this->cripto->codificar_url($variable, $this->configuracion);
      ?>
          <div>              
          <a href="<?= $pagina.$variable?>"><img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/mensaje.png" width="40" height="40" border="0">Mensajes</a>
          </div>
                  <?



        }

    function buscarPeriodoActual() {

        $cadena_sql = $this->sql->cadena_sql("periodoActual", '');

        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle,$cadena_sql, "busqueda");
        
        return $resultado;

    }
        
    function buscarPeriodoAnterior() {

        $cadena_sql = $this->sql->cadena_sql("periodoAnterior", '');

        $resultado = $this->ejecutarSQL($this->configuracion, $this->accesoOracle,$cadena_sql, "busqueda");
        
        return $resultado[0];

    }


}
?>