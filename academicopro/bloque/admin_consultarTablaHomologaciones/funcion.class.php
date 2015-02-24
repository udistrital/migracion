<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminConsultarTablaHomologaciones extends funcionGeneral {
    //Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
    function __construct($configuracion,$sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql = $sql;
    
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");

        //Datos de sesion
        $this->formulario="registroTablaHomologaciones";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->pagina="registroTablaHomologaciones";
        $this->opcion="mostrar";
        //Conexion sga
        $this->configuracion = $configuracion;
    

    }

  
   /**
   * Esta funcion realiza el llamado a las funciones para mostrar el listado de los registros de la tabla de 
   * homoplogaciones
   * Utiliza los metodos consultarTablaHomologacion, presentarTablaHomologacion
   * @param <array> 
   * @param <array> 
   */
 
    function mostrarTablaHomologacion() {
        //echo "<br>cod_proyecto ".$_REQUEST['cod_proyecto'];exit;
            
        if (isset($_REQUEST['cod_proyecto'])?$_REQUEST['cod_proyecto']:''){ 
            $cod_proyecto=$_REQUEST['cod_proyecto'];
            //$cod_proyecto=77;
             $resultadoTHomologacionNormal=$this->consultarTablaHomologacion($cod_proyecto,0);
             $resultadoTHomologacionUnion=$this->consultarTablaHomologacion($cod_proyecto,1);
            $resultadoTHomologacionBifurcacion=$this->consultarTablaHomologacion($cod_proyecto,2);
            $this->tituloTabla();
           //var_dump($resultadoTHomologacionNormal);exit;
            $this->presentarTablaHomologacion($resultadoTHomologacionNormal,0);
            $this->presentarTablaHomologacion($resultadoTHomologacionUnion,1);
            $this->presentarTablaHomologacion($resultadoTHomologacionBifurcacion,2);
        }
        
    }
    
   /**
   * Funcion que permite consultar los registros de la tabla de homologacion
   * @param <array> $cod_proyecto
   * @return <array> $resultadoTablaHomologacion
   */
  function consultarTablaHomologacion($cod_proyecto,$tipo) {
      $datos=array('cod_proyecto'=>$cod_proyecto,
                    'tipo'=>$tipo);
       $cadena_sql = $this->sql->cadena_sql("consultaTablaHomologacion", $datos);
     return $resultadoTablaHomologacion = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

   /**
   * Funcion que permite presentar en pantalla los registros de la tabla de homologaciones
   * @param <array> $resultadoTHomologacion
   * @return <array>
   */
     function presentarTablaHomologacion($resultadoTHomologacion,$tipo) {
?>
        <table class="contenidotabla" width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
        <?
        if($tipo==1){
            $columnas=8;
        }else{
            $columnas=6;
        }
        $this->encabezadoTabla($tipo,$resultadoTHomologacion);
                
         if (is_array($resultadoTHomologacion)) {
                $filas="";
              //recorre los resultados
                $cantidad=0;
              for ($j = 0; $j < count($resultadoTHomologacion); $j++) {
                
                  if($resultadoTHomologacion[$j]['ESTADO']=='A'){
                        $cantidad++;
                    }
              ?>
                <tr onmouseover="this.style.background='#F4F4EA'" onmouseout="this.style.background=''">
                  <?
                  if ($tipo<>1){
                      if($resultadoTHomologacion[$j]['ESTADO']=='A'){
                      echo " <td class='cuadro_plano centrar'>".$resultadoTHomologacion[$j]['COD_ASI_PPAL']."</td>";
                      echo "<td class='cuadro_plano ' >".htmlentities($resultadoTHomologacion[$j]['NOM_ASI_PPAL'])."</td>";}
                  }else{
                      if($resultadoTHomologacion[$j]['FEC_REG']==(isset($resultadoTHomologacion[$j+1]['FEC_REG'])?$resultadoTHomologacion[$j+1]['FEC_REG']:''))
                          $filas=" rowspan='2' ";
                      else
                          $filas="";
                      if($resultadoTHomologacion[$j]['FEC_REG']!=(isset($resultadoTHomologacion[$j-1]['FEC_REG'])?$resultadoTHomologacion[$j-1]['FEC_REG']:'')&& $resultadoTHomologacion[$j]['ESTADO']=='A'){
                          echo "<td class='cuadro_plano centrar' ".$filas.">".$resultadoTHomologacion[$j]['COD_ASI_PPAL']."</td>";
                          echo "<td class='cuadro_plano '".$filas.">".htmlentities($resultadoTHomologacion[$j]['NOM_ASI_PPAL'])."</td>";
                  
                      }
                  }
                  
                  if ($tipo<>2){
                      if($resultadoTHomologacion[$j]['ESTADO']=='A'){
                        echo "<td class='cuadro_plano centrar'>".$resultadoTHomologacion[$j]['COD_ASI_HOM']."</td>";
                        echo "<td class='cuadro_plano '>".htmlentities($resultadoTHomologacion[$j]['NOM_ASI_HOM'])."</td>";
      
                        }
                 
                  }else{
                        if($resultadoTHomologacion[$j]['FEC_REG']==(isset($resultadoTHomologacion[$j+1]['FEC_REG'])?$resultadoTHomologacion[$j+1]['FEC_REG']:''))
                            $filas_hom=" rowspan='2' ";
                        else
                            $filas_hom="";
                        if($resultadoTHomologacion[$j]['FEC_REG']!=(isset($resultadoTHomologacion[$j-1]['FEC_REG'])?$resultadoTHomologacion[$j-1]['FEC_REG']:'')&& $resultadoTHomologacion[$j]['ESTADO']=='A'){
                      
                            echo "<td class='cuadro_plano centrar' ".$filas_hom.">".$resultadoTHomologacion[$j]['COD_ASI_HOM']."</td>";
                            echo "<td class='cuadro_plano '".$filas_hom.">".htmlentities($resultadoTHomologacion[$j]['NOM_ASI_HOM'])."</td>";
                                               ?>
                        <td class='cuadro_plano centrar' <? echo $filas_hom; ?>>Activo</td>
                        <td class='cuadro_plano centrar' <? echo $filas_hom;?>><? if($resultadoTHomologacion[$j]['ESTADO']=='A'){echo $this->enlaceDesahabilitar($resultadoTHomologacion[$j]);}
                         ?></td>
                        <?
                        }
                  }
                  if ($tipo==0 && $resultadoTHomologacion[$j]['ESTADO']=='A'){
                      ?>
                        <td class='cuadro_plano centrar'>Activo</td>
                        <td class='cuadro_plano centrar'><? if($resultadoTHomologacion[$j]['ESTADO']=='A'){echo $this->enlaceDesahabilitar($resultadoTHomologacion[$j]);}
                        else {echo $this->enlaceHabilitar($resultadoTHomologacion[$j]);}?></td>
                     <?
                  }
                  if($tipo==1 && $resultadoTHomologacion[$j]['ESTADO']=='A'){
                        echo "<td class='cuadro_plano centrar'>".htmlentities($resultadoTHomologacion[$j]['PORCENTAJE'])." %</td>";
                        echo "<td class='cuadro_plano centrar'>".htmlentities($resultadoTHomologacion[$j]['REQ_APROBAR'])."</td>";
                        if($resultadoTHomologacion[$j]['FEC_REG']!=(isset($resultadoTHomologacion[$j-1]['FEC_REG'])?$resultadoTHomologacion[$j-1]['FEC_REG']:'')&& $resultadoTHomologacion[$j]['ESTADO']=='A'){
                      
                        ?>
                        <td class='cuadro_plano centrar' <? echo $filas; ?>>Activo</td>
                        <td class='cuadro_plano centrar' <? echo $filas; ?>><? if($resultadoTHomologacion[$j]['ESTADO']=='A'){echo $this->enlaceDesahabilitar($resultadoTHomologacion[$j]);}
                        else {echo $this->enlaceHabilitar($resultadoTHomologacion[$j]);}?></td>
                     <?
                        }
                    }
  
              }
                if($cantidad==0){
                        echo "<tr><td class='cuadro_plano centrar' colspan='".$columnas."'>NO EXISTEN HOMOLOGACIONES REGISTRADAS PARA EL PROYECTO CURRICULAR</td></tr>";
                    
                }  
                ?>
              </tr>
              <?
              
              }else
                    {
                    
                        echo "<tr><td class='cuadro_plano centrar' colspan='".$columnas."'>NO EXISTEN HOMOLOGACIONES REGISTRADAS PARA EL PROYECTO CURRICULAR</td></tr>";
                    
                }
              ?>
        </table>
      <?
          }

     /**
     * Funcion que muestra el formulario con el listado de proyectos curriculares asociados, para que se seleccione un espacio
     * @param <int> $this->identificacion
     * @param <int> $this->configuracion
     * @param <int> $this->crypto
     * Utiliza el metodo consultarProyectosCoordinador
     */
          

function formSeleccionarProyecto() {
        $datos['identificacion'] = $this->identificacion;
        $carreras = $this->consultarProyectosCoordinador($datos);
        $indiceAcademico=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_consultarTablaHomologaciones";
        $variable.="&opcion=ConsultarHomologacion";
  
        if (count($carreras)>1){        
            echo "<p>Seleccione un proyecto curricular:&nbsp;</p>
                <div align='center'>
                <table border='0' width='530' cellpadding='0'>
                <tr>
                <table border='0' cellpadding='0' cellspacing='0' width='530'>";
                    
            $i=0;
            
            while(isset($carreras[$i][0]))
            {
                $variable_enlace=$variable."&cod_proyecto=".$carreras[$i][0];
                $variable_enlace=$this->cripto->codificar_url($variable_enlace,$this->configuracion);
                $enlaceHomologaciones=$indiceAcademico.$variable_enlace;
                echo'<tr><td width="100%"><a href="'.$enlaceHomologaciones.'">'.$carreras[$i][0].' - '.$carreras[$i][1].'</a></td></tr>';
                $i++;
            }
        }elseif(isset($carreras[0][0])){ 
            $variable_enlace=$variable."&cod_proyecto=".$carreras[0][0];
            $variable_enlace=$this->cripto->codificar_url($variable_enlace,$this->configuracion);
            $enlaceHomologaciones=$indiceAcademico.$variable_enlace;
            echo "<script>location.replace('".$enlaceHomologaciones."')</script>";

        }else{
            echo "No existen proyectos curriculares asociados.";
        }
           
    }     
    
    /**
     * Funcion que consulta en la base de datos los proyectos curriculares asociados a un coordinador
     * @param <int> $identificacion
     * @param <array> $this->configuracion
     * @param $this->accesoOracle
     * @param  $sql
     * Utiliza el metodo ejecutarSQL
     */
  function consultarProyectosCoordinador($datos) {
      $cadena_sql = $this->sql->cadena_sql("consultaProyectosCoordinador", $datos);
      //echo "<br>cadena ".$cadena_sql;exit;
      return $resultadoProyectos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  

     /**
     * Funcion que muestra el enlace par Habilitar una homologacion
     * @param <array> $resultadoTHomologacion(ID_HOM,COD_CRA_PPAL,COD_ASI_PPAL, NOM_ASI_PPAL,COD_CRA_HOM, COD_ASI_HOM,NOM_ASI_HOM, ESTADO, FEC_REG, TIPO_HOMOLOGACION, PORCENTAJE, REQ_APROBAR, ESTADO_PORCENTAJE,ANIO_PORCENTAJE, PERIODO_PORCENTAJE)
     *
      */
  function enlaceHabilitar($resultadoTHomologacion) {
     
                  $parametros = "&estado=A";
                  $parametros.="&codHomologa=" . $resultadoTHomologacion['COD_ASI_HOM'];
                  $parametros.="&codPpal=" . $resultadoTHomologacion['COD_ASI_PPAL'];
                  $parametros.="&codCraPpal=" . $resultadoTHomologacion['COD_CRA_PPAL'];
                  $parametros.="&cod_proyecto=" . $resultadoTHomologacion['COD_CRA_PPAL'];
                  $parametros.="&fec_reg=" . $resultadoTHomologacion['FEC_REG'];
                  $parametros.="&tipo_homologacion=" . $resultadoTHomologacion['TIPO_HOMOLOGACION'];
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=registro_adicionarTablaHomologacion";
                  $variables.="&opcion=deshabilitar";
                  $destino="&retorno=admin_homologaciones";
                  $destino.="&opcionRetorno=crearTablaHomologacion";
            
                  $variable = $variables . $parametros.$destino;

                  include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                  $this->cripto = new encriptar();
                  $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                   
                  ?>
                       <center><button class="botonEnlacePreinscripcion" onclick="window.location ='<?echo $pagina . $variable;?>'">
                           <img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/x.png" ?>" border="0" width="25" height="25">
                        </button>    
                       </center>
                  <?
      }
      
     /**
     * Funcion que muestra el enlace par Deshabilitar una homologacion
     * @param <array> $resultadoTHomologacion(ID_HOM,COD_CRA_PPAL,COD_ASI_PPAL, NOM_ASI_PPAL,COD_CRA_HOM, COD_ASI_HOM,NOM_ASI_HOM, ESTADO, FEC_REG, TIPO_HOMOLOGACION, PORCENTAJE, REQ_APROBAR, ESTADO_PORCENTAJE,ANIO_PORCENTAJE, PERIODO_PORCENTAJE)
     *
      */
    function enlaceDesahabilitar($resultadoTHomologacion) {      
      //var_dump($resultadoTHomologacion);exit;
                  $parametros = "&estado=I";
                  $parametros.="&codHomologa=" . $resultadoTHomologacion['COD_ASI_HOM'];
                  $parametros.="&codPpal=" . $resultadoTHomologacion['COD_ASI_PPAL'];
                  $parametros.="&codCraPpal=" . $resultadoTHomologacion['COD_CRA_PPAL'];
                  $parametros.="&cod_proyecto=" . $resultadoTHomologacion['COD_CRA_PPAL'];
                  $parametros.="&fec_reg=" . $resultadoTHomologacion['FEC_REG'];
                  $parametros.="&tipo_homologacion=" . $resultadoTHomologacion['TIPO_HOMOLOGACION'];
                  $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                  $variables = "pagina=registro_adicionarTablaHomologacion";
                  $variables.="&opcion=deshabilitar";
                  $destino="&retorno=admin_homologaciones";
                  $destino.="&opcionRetorno=crearTablaHomologacion";
                  $variable = $variables . $parametros.$destino;
                  $espacios = $resultadoTHomologacion['COD_ASI_PPAL']." - ".$resultadoTHomologacion['COD_ASI_HOM'];
                  include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                  $this->cripto = new encriptar();
                  $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                   
                  ?>

                <button class="botonEnlacePreinscripcion" onclick="if(confirm('Va a inactivar el registro de homologacion de los espacios <? echo $espacios ?> ¿Desea continuar?'))
    {window.location ='<? echo $pagina . $variable;?>'}">
                        <center><img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/x.png" ?>" border="0" width="25" height="25"></center>  
                        </button>                 
                         
               <?
      }
  
     /**
     * Funcion que muestra el encabezado de una tabla, para agrupar los tipos de homologacion
     * @param <int> $tipo
     *
      */
  function encabezadoTabla($tipo, $resultadoTHomologacion){ 
      $cadena = "";
      if ($tipo==0 ){
       $cadena .=  "<br>
                      <thead class='sigma'>
                       <th class='espacios_proyecto' colspan='2'> ESPACIOS DE MI PROYECTO CURRICULAR</th>
                       <th class='espacios_homologos' colspan='2'>ESPACIOS HOMÓLOGOS</th>
                      </thead>";
      }
      switch ($tipo) {
            case 1:
             
               $cadena .= "<br>
                          <thead class='sigma'>
                           <th  class='niveles centrar'colspan='8'> UNION (dos espacios homologan uno)</th>
                          </thead>";

              break;
            case 2:
                
               $cadena .= "<br>
                          <thead class='sigma'>
                           <th  class='niveles centrar' colspan='6'> BIFURCACION (un espacio homologa dos)</th>
                          </thead>";

              break;
        }

        $cadena .= "<thead class='sigma'>
               <th class='sub_espacios_proyecto' width='20'>Codigo</th>
                <th class='sub_espacios_proyecto' width='50'>Espacio Acad&eacute;mico</th>
                <th class='sub_espacios_homologos' width='20'>Codigo </th>
                <th class='sub_espacios_homologos' width='50'>Espacio Acad&eacute;mico</th>";
   
               
        if($tipo==1 ){
                    $cadena .= "<th class='sub_espacios_homologos' width='10'>Porcentaje</th>
                        <th class='sub_espacios_homologos' width='10'>Requiere Aprobar</th>
                        <th class='niveles centrar' width='10'>Estado</th>";
                
                }
                
        if($tipo==2 ){
            $cadena .= "<th class='niveles centrar' width='20'>Estado</th>";
        }
        
        if($tipo==0){
                $cadena .= " <th class='niveles centrar' width='20'>Estado</th>";

                }
                

        $cadena .= "<th class='niveles centrar' width='5'>Inactivar</th>
                    </thead>";
       echo $cadena ;
  }

     /**
     * Funcion que muestra el titulo de la tabla general
     * Utiliza metodo consultarProyectosCoordinador
      */

   function tituloTabla(){
          $datos['identificacion']=$this->identificacion;
          $datos['cod_proyecto']=$_REQUEST['cod_proyecto'];
          $proyecto = $this->consultarProyectosCoordinador($datos);
          $titulo ="<br><div align='center' ><b>Tabla de Homologaciones - ".$proyecto[0][0]." ".$proyecto[0][1]."</b></div><hr>";
          echo $titulo;
  }
}


?>
