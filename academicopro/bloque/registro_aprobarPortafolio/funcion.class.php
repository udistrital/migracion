
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
class funcion_registro_aprobarPortafolio extends funcionGeneral {
    private $ano;
    private $periodo;

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=new sql_registro_aprobarPortafolio();
        $this->log_us= new log();
        $this->formulario="registro_aprobarPortafolio";

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"asesvice");
        //var_dump($this->accesoOracle);exit;

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
        $this->cadena_sql = $this->sql->cadena_sql($configuracion, "bimestreActual", "");
        $periodo = $this->accesoOracle->ejecutarAcceso($this->cadena_sql, "busqueda");
        $this->ano=$periodo[0]['ANO'];
        $this->periodo=$periodo[0]['PERIODO'];

    }#Cierre de constructor


    //inicia lista de planes de estudios
    function verRegistro($configuracion)
    {
        //var_dump($_REQUEST);
        $cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_facultad",'');
        $registroFacultad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
            $variablesEvento=array('ano'=>  $this->ano,
                                    'periodo'=>  $this->periodo);
            $cadena_sql = $this->sql->cadena_sql($configuracion, "buscarEventoGestionPlanes",$variablesEvento);
            $fechasEventoPlanes = $this->ejecutarSQL($configuracion,$this->accesoOracle,$cadena_sql, "busqueda");
            $fecha=date('Ymd');
            if($fecha>$fechasEventoPlanes[0]['FIN'])
            {
                $permiso=0;
            }else{$permiso=1;}
        
        $this->volverPlanesDeEstudio($configuracion);
        ?>

            <div class="pestanas">
               <ul>
                   <?
                   for($i=0;$i<count($registroFacultad);$i++)
                   {
                       ?>
                        <li id="pestana<?echo $registroFacultad[$i][0]?>" class="pestanainactiva a">
                            <a id="pestanalink<?echo $registroFacultad[$i][0]?>" class="link" onclick="xajax_facultad(<?echo $registroFacultad[$i][0];?>,<?echo $permiso;?>);">
                                <?echo $registroFacultad[$i][1]?>
                            </a>
                            </li>
                       <?
                   }
                    ?>
               </ul>
           </div>
           <div id="cuerpopestanas" class="cuerpopestanas">
               <table class="contenidotabla">
                   <tr>
                       <td class="centrar">
                           <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png" alt="Logo UD">
                           <br>
                           APROBACI&Oacute;N DE PORTAFOLIO DE ELECTIVAS EXTR&Iacute;NSECAS
                       </td>
                   </tr>
                   <tr>
                       <td>
                           <ul>
                               <li class="formal">Seleccione la facultad que desea observar</li>
                               <li class="formal">Una vez cargue los espacios acad&eacute;micos electivos extr&iacute;nsecos ordenados por proyecto curricular, seleccione una de las opciones: Aprobar, No Aprobar, Modificar.</li>
                           </ul>
                       </td>
                   </tr>
               </table>
           </div>

           <?
           if(isset($_REQUEST['facultad']))
               {
               
               ?>
                <script type="text/javascript" language="javascript">
                    window.onload = xajax_facultad(<?echo $registroFacultad[$i][0];?>,<?echo $permiso;?>);
                </script>
               <?
               }
                  
        
    }#Cierre de funcion verRegistro

    function volverPlanesDeEstudio($configuracion) {

      $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
      $enlace="pagina=adminAprobarEspacioPlan";
      $enlace.="&opcion=ver";
      include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
      $this->cripto=new encriptar();
      $enlace=$this->cripto->codificar_url($enlace,$configuracion);
      ?>
      <table class='centralencabezado subrayado' border="0" width="100%">
        <tr>
          <td>
            <a href="<?echo $pagina.$enlace?>" class="centrar">
              Volver a Aprobación de Espacios en Planes de Estudios
            </a>
          </td>
        </tr>
      </table>
      <?
    }
    function aprobarElectiva($configuracion)
    {
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nivel=$_REQUEST['nivel'];
        $creditos=$_REQUEST['creditos'];
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
        $clasificacion=$_REQUEST['clasificacion'];
        $facultad=$_REQUEST['facultad'];

        if(isset($codEspacio) and isset($planEstudio)) {
            #Actualiza la aprobacion de los espacios academicos

            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"aprobarEspacio",$codEspacio, $planEstudio);
            $registroEspaciosPlan=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");
            $totalEspacios=$this->accesoGestion->obtener_conteo_db($registroEspaciosPlan);
            //Vericar que se ejecuto la aprobacion de cada uno de los espacios academicos
            if($registroEspaciosPlan==true) {

                //Busca los datos del espacio academico que se va a aprobar, para poder pasarlos a oracle
                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"datosEspacio",$codEspacio, $planEstudio);
                $registrodatosEspacios=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                //Busca los datos de la carrera
                $this->cadena_sql = $this->sql->cadena_sql($configuracion, "datosNumeroCarreras", $codEspacio, $planEstudio);
                $numeroCarreras = $this->accesoGestion->ejecutarAcceso($this->cadena_sql, "busqueda");
                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"datosCarrera",$codEspacio, $planEstudio);
                $registrodatosCarrera=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"busqueda");

                //Si carga el espacio academico en Oracle en la tabla acasi, debemos cargarlo ahora en acpen
                if($clasificacion=='3' || $clasificacion=='4') {
                    $electivo='S';
                }else {
                    $electivo='N';
                }
                //$espacioNombre=strtr($registrodatosEspacios[0][0],"á,é,í,ó,ú","A,E,I,O,U");
                $nombreEspacio=strtr(strtoupper($registrodatosEspacios[0][0]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                //Arreglo para tener los datos a cargar en ACASI (Oracle)
                $variableAcasi=array($codEspacio,$nombreEspacio,$registrodatosCarrera[0][0], 'A', 'S');
                
                // Arreglo para tener los datos a cargar en ACPEN (Oracle)
                for ($a=0;$a<$numeroCarreras[0][0];$a++) {
                  $variableAcpen[$a]=array($registrodatosCarrera[$a][1],$codEspacio,$nivel,$electivo, $htd,$htc,'A',$creditos,$planEstudio,$hta);
                }
                //buscar datos en ACASI
                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscarEspacioAcasi",$variableAcasi, $planEstudio);
                $busquedaAcasi=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");

                //Si existe el espacio academico en acasi, se busca en acpen si esta registrado con el plan de estudio
                if(is_array($busquedaAcasi)) {
                    //buscar datos en ACPEN
                  for ($a=0;$a<$numeroCarreras[0][0];$a++) {
                    $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscarEspacioAcpen",$variableAcpen[$a], $planEstudio);
                    $busquedaAcpen=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
                    if (is_array($busquedaAcpen)) {break;}
                  }

                    //Si existen datos en acpen con el plan de estudios estipulado, se envia mensaje de que no se puede cargar el espacio academico por que ya esta cargado
                    if(is_array($busquedaAcpen)) {
                        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"DesaprobarEspacio",$variableAcasi[0], $planEstudio);
                        $cambiarEstadoEspacio=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");

                        ?>
<table class='contenidotabla centrar' border="0" width="100%">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <?echo $codEspacio?> NO HA SIDO APROBADO -- ERROR 102 (EL ESPACIO YA ESTA CARGADO PARA EL PLAN DE ESTUDIO)</h4>
            <hr noshade class="hr">

        </td>
    </tr>
                            <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variables="pagina=registro_aprobarPortafolio";
                            $variables.="&opcion=ver";
                            $variables.="&planEstudio=".$planEstudio;
                            $variables.="&facultad=".$facultad;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                        ?>
    <tr align="center">
        <td class="centrar" colspan="4">
            <a href="<?echo $pagina.$variables?>" class="centrar">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
        </td>
    </tr>
</table><?

                    }else {
                      for ($a=0;$a<$numeroCarreras[0][0];$a++) {
                        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"cargarEspacioAcpen",$variableAcpen[$a], $planEstudio);
                        $registroEspaciosCargadoAcpen=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"");

                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"cargarEspacioAcclasificacpen",$variableAcpen[$a], $clasificacion);
                            $registroEspaciosCargadoAcpen=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"");

                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"estadocargarEspacio",$variableAcpen[$a][1], $planEstudio);
                            $registroEstadoCargado=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");

                            //Arreglo para registrar en el log de eventos
                            $variablesRegistro=array($this->usuario,date('YmdHis'),'18','Aprobo Espacio Academico Extrinseco Existente', $this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].", 0, 0, ".$_REQUEST['planEstudio'].", ".$registrodatosCarrera[$a][1], $_REQUEST['planEstudio']);
                            //$this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);

                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"registroEvento",$variablesRegistro);
                            $registroEvento=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");
                      }
                      if($registroEspaciosCargadoAcpen==true) {
                            ?>
<table class='contenidotabla centrar' border="0" width="100%">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <?echo $codEspacio?> HA SIDO APROBADO Y CARGADO CORRECTAMENTE</h4>
            <hr noshade class="hr">

        </td>
    </tr>
                                <?
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variables="pagina=registro_aprobarPortafolio";
                                $variables.="&opcion=ver";
                                $variables.="&planEstudio=".$planEstudio;
                                $variables.="&facultad=".$facultad;

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variables=$this->cripto->codificar_url($variables,$configuracion);
                            ?>
    <tr align="center">
        <td class="centrar" colspan="4">
            <a href="<?echo $pagina.$variables?>" class="centrar">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
        </td>
    </tr>
</table><?
                        }else {
                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"DesaprobarEspacio",$variableAcasi[0], $planEstudio);
                            $cambiarEstadoEspacio=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");

                            if($cambiarEstadoEspacio==true) {?>
<table class='contenidotabla centrar' border="0" width="100%">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h6>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <?echo $codEspacio?> NO HA SIDO APROBADO -- ERROR 101 (EL ESPACIO YA ESTA CARGADO) </h6>
            <hr noshade class="hr">

        </td>
    </tr><?
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variables="pagina=registro_aprobarPortafolio";
                                    $variables.="&opcion=ver";
                                    $variables.="&planEstudio=".$planEstudio;
                                    $variables.="&facultad=".$facultad;

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                                ?>
    <tr align="center">
        <td class="centrar" colspan="4">
            <a href="<?echo $pagina.$variables?>" class="centrar">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
        </td>
    </tr>
</table>
                                <?
                            }

                        }
                    }

                }else {
                  for ($a=0;$a<$numeroCarreras[0][0];$a++) {
                    //buscar datos en ACPEN
                    $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscarEspacioAcpen",$variableAcpen[$a], $planEstudio);
                    $busquedaAcpen=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"busqueda");
                    if (is_array($busquedaAcpen)) {break;}
                  }
                    if(is_array($busquedaAcpen)) {
                        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"DesaprobarEspacio",$variableAcasi[0], $planEstudio);
                        $cambiarEstadoEspacio=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");

                        ?>
                        <table class='contenidotabla centrar' border="0" width="100%">
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <?echo $codEspacio?> NO HA SIDO APROBADO -- ERROR 102 (EL ESPACIO YA ESTA CARGADO PARA EL PLAN DE ESTUDIO)</h4>
                                    <hr noshade class="hr">

                                </td>
                            </tr>
                            <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variables="pagina=registro_aprobarPortafolio";
                            $variables.="&opcion=ver";
                            $variables.="&planEstudio=".$planEstudio;
                            $variables.="&facultad=".$facultad;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                        ?>
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <a href="<?echo $pagina.$variables?>" class="centrar">
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <?

                    }else {
                        //Cargar datos en ACASi
                        $this->cadena_sql=$this->sql->cadena_sql($configuracion,"cargarEspacioAcasi",$variableAcasi, $planEstudio);
                        $registroEspaciosCargadoAcasi=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"");

                        if($registroEspaciosCargadoAcasi==true) {
                            //Cargar datos en ACPEN
                          for ($a=0;$a<$numeroCarreras[0][0];$a++) {
                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"cargarEspacioAcpen",$variableAcpen[$a], $planEstudio);
                            $registroEspaciosCargadoAcpen=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"");
                            if($registroEspaciosCargadoAcpen==true) {
                                //Si se registra en acpen se envia mensaje notificando de que se aprobo correctamente

                                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"estadocargarEspacio",$variableAcpen[$a][1], $planEstudio);
                                $registroEstadoCargado=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");

                                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"cargarEspacioAcclasificacpen",$variableAcpen[$a], $clasificacion);
                                $registroEspaciosCargadoAcpen=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"");


                                //Arreglo para registrar en el log de eventos
                                $variablesRegistro=array($this->usuario,date('YmdHis'),'11','Aprobo Espacio Academico Extrinseco', $this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].", 0, 0, ".$_REQUEST['planEstudio'].", ".$registrodatosCarrera[$a][1], $_REQUEST['planEstudio']);
                                //$this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);

                                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"registroEvento",$variablesRegistro);
                                $registroEvento=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");

                                // $variablesAprobar=array($_REQUEST['planEstudio'], $this->usuario,date('YmdHis'),'11','Aprobo Espacio Academico'," 2010-1, ".$_REQUEST['codEspacio'].", 0, 0, ".$_REQUEST['planEstudio'].", ", $_REQUEST['planEstudio']);

                                // $this->cadena_sql=$this->sql->cadena_sql($configuracion,"comentarioAprobar",$variablesAprobar);//echo $this->cadena_sql;exit;
                                // $registroEvento=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");
                            }
                          }
                          if ($registroEspaciosCargadoAcpen == true) {

                                ?>
                              <table class='contenidotabla centrar' border="0" width="100%">
                                  <tr align="center">
                                      <td class="centrar" colspan="4">
                                          <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <?echo $codEspacio?> HA SIDO APROBADO Y CARGADO CORRECTAMENTE</h4>
                                          <hr noshade class="hr">

                                      </td>
                                  </tr>
                                    <?
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variables="pagina=registro_aprobarPortafolio";
                                    $variables.="&opcion=ver";
                                    $variables.="&planEstudio=".$planEstudio;
                                    $variables.="&facultad=".$facultad;

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                                ?>
    <tr align="center">
        <td class="centrar" colspan="4">
            <a href="<?echo $pagina.$variables?>" class="centrar">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
        </td>
    </tr>
                                   
</table>
                                <?
                            }

                        }else {
                            //Si el espacio no se puede cargar en acpen se debe borrar de acasi y cambiar el estado sga_planEstudio_espacio
                            //Borrar datos en ACASI del espacio que se queria aprobar
                            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"borrarEspacioAcasi",$variableAcasi, $planEstudio);
                            $borrarEspacioAcasi=$this->accesoOracle->ejecutarAcceso($this->cadena_sql,"");

                            if($borrarEspacioAcasi==true) {
                                //Cambiar Estado de aprobacion sga_planEstudio_espacio
                                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"DesaprobarEspacio",$variableAcasi[0], $planEstudio);
                                $cambiarEstadoEspacio=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");

                                if($cambiarEstadoEspacio==true) {?>
<table class='contenidotabla centrar' border="0" width="100%">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h6>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <?echo $codEspacio?> NO HA SIDO APROBADO -- ERROR 101 (EL ESPACIO YA ESTA CARGADO)</h6>
            <hr noshade class="hr">

        </td>
    </tr><?
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variables="pagina=registro_aprobarPortafolio";
                                        $variables.="&opcion=ver";
                                        $variables.="&planEstudio=".$planEstudio;
                                        $variables.="&facultad=".$facultad;

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variables=$this->cripto->codificar_url($variables,$configuracion);
                                    ?>
    <tr align="center">
        <td class="centrar" colspan="4">
            <a href="<?echo $pagina.$variables?>" class="centrar">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
        </td>
    </tr>
</table>
                                    <?
                                }
                            }
                        }

                    }
                }



            }
            else {
                ?>
<table class='contenidotabla centrar' border="0" width="100%">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <?echo $codEspacio?> NO HA SIDO APROBADO -- ERROR 104</h4>
            <hr noshade class="hr">

        </td>
    </tr>
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registro_aprobarPortafolio";
                    $variables.="&opcion=ver";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&facultad=".$facultad;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                ?>
    <tr align="center">
        <td class="centrar" colspan="4">
            <a href="<?echo $pagina.$variables?>" class="centrar">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
        </td>
    </tr>
</table><?
            }
        }


    }

    function NoaprobarElectiva($configuracion)
    {
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nivel=$_REQUEST['nivel'];
        $creditos=$_REQUEST['creditos'];
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
        $clasificacion=$_REQUEST['clasificacion'];
        $facultad=$_REQUEST['facultad'];

        if(isset($codEspacio) and isset($planEstudio))
            {
            #Actualiza la aprobacion de los espacios academicos
            $this->cadena_sql=$this->sql->cadena_sql($configuracion,"NoaprobarEspacio",$codEspacio, $planEstudio);
            $registroEspaciosPlan=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");
            $totalEspacios=$this->accesoGestion->obtener_conteo_db($registroEspaciosPlan);
            //Vericar que se ejecuto la aprobacion de cada uno de los espacios academicos
            if($registroEspaciosPlan==true)
                {

                //Arreglo para registrar en el log de eventos
                $variablesRegistro=array($this->usuario,date('YmdHis'),'12','No Aprobo Espacio Academico'," 2010-3, ".$_REQUEST['codEspacio'].", 0, 0, ".$_REQUEST['planEstudio'].", ", $_REQUEST['planEstudio']);
                //$this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);

                $this->cadena_sql=$this->sql->cadena_sql($configuracion,"registroEvento",$variablesRegistro);
                $registroEvento=$this->accesoGestion->ejecutarAcceso($this->cadena_sql,"");

                
                        ?>
<table class='contenidotabla centrar' border="0" width="100%">
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>EL ESPACIO ACAD&Eacute;MICO CON C&Oacute;DIGO <?echo $codEspacio?> NO HA SIDO APROBADO</h4>
            <hr noshade class="hr">

        </td>
    </tr>
                            <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variables="pagina=registro_aprobarPortafolio";
                            $variables.="&opcion=ver";
                            $variables.="&planEstudio=".$planEstudio;
                            $variables.="&facultad=".$facultad;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                        ?>
    <tr align="center">
        <td class="centrar" colspan="4">
            <a href="<?echo $pagina.$variables?>" class="centrar">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
        </td>
    </tr>
</table><?

                    }

            }
    }

    function FormularioModificarEspacio($configuracion)
    {
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nivel=$_REQUEST['nivel'];
        $nroCreditos=$_REQUEST['creditos'];
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
        $clasificacion=$_REQUEST['clasificacion'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $semanas=$_REQUEST['semanas'];
        $facultad=$_REQUEST['facultad'];
        $mensaje='';

        $cadena_sql=$this->sql->cadena_sql($configuracion,"modificarEspacio_notas",$codEspacio);
        $modificarEspacioNotas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if(!is_array($modificarEspacioNotas)||$modificarEspacioNotas[0][0]>0)
            {
                $porNotas='1';
            }else
                {
                    $porNotas='0';
                }

        $cadena_sql=$this->sql->cadena_sql($configuracion,"modificarEspacio_inscripcion",$codEspacio);
        $modificarEspacioInscripcion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if(!is_array($modificarEspacioInscripcion)||$modificarEspacioInscripcion[0][0]>0)
            {
                $porInscripcion='1';
            }else
                {
                    $porInscripcion='0';
                }

        $cadena_sql=$this->sql->cadena_sql($configuracion,"modificarEspacio_horario",$codEspacio);
        $modificarEspacioHorario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        if(!is_array($modificarEspacioHorario)||$modificarEspacioHorario[0][0]>0)
            {
                $porHorario='1';
            }else
                {
                    $porHorario='0';
                }

        if($porNotas=='1' || $porInscripcion=='1' || $porHorario=='1')
        {
            if($porNotas=='1')
                {
                    $msjError=" - Notas";
                }
                if($porInscripcion=='1')
                {
                    $msjError.=" - Inscripciones";
                }
                if($porHorario=='1')
                {
                    $msjError.=" - Horarios";
                }
            $mensaje="El espacio acad&eacute;mico tiene registros de ".$msjError.".";
//            echo "<script>alert('El espacio académico no se puede modificar porque tiene registros de ".$msjError.".')</script>";
//            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
//            $variables="pagina=registro_aprobarPortafolio";
//            $variables.="&opcion=ver";
//            $variables.="&planEstudio=".$planEstudio;
//            $variables.="&facultad=".$facultad;
//
//            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
//            $this->cripto=new encriptar();
//            $variables=$this->cripto->codificar_url($variables,$configuracion);
//            echo "<script>location.replace('".$pagina.$variables."')</script>";
//            exit;
        }
        $variable=array($planEstudio,$clasificacion, $nombreEspacio, $nroCreditos, $nivel, $htd, $htc, $hta,$codEspacio,$semanas,$facultad,$mensaje);

        $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarEspacioComunOracle",$variable);
        $resultado_comunOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $comunOracle=$this->totalRegistros($configuracion, $this->accesoOracle);


        //$this->encabezadoModulo($configuracion,$variable);

        if($comunOracle>1)
            {
                $this->editarEspacioComun($configuracion,$variable);
            }else
                {
                    $this->editarEspacioPlanEstudio($configuracion,$variable);
                }
                exit;

        ?>
        <table class="contenidotabla centrar" width="100%" border="0">
          <H1>MODIFICACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS ELECTIVOS EXTR&Iacute;NSECOS</H1>
        <tr>
            <td class="cuadro_color centrar" colspan="3">
                <font size="2"> Todos los campos marcados con <font size="2" color="red">*</font> son obligatorios</font>
            </td>
        </tr>
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
            <tr>
                <td colspan="2">
                    <font size="2" color="red">*</font> C&oacute;digo del Espacio:
                </td>
                <td>
                    <?echo $_REQUEST['codEspacio']?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <font size="2" color="red">*</font> Nombre del Espacio:
                </td>
                <td>
                    <input type="text" name="nombreEspacio" size="50" maxlength="100" value="<?echo $_REQUEST['nombreEspacio']?>">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <font size="2" color="red">*</font> N&uacute;mero de Cr&eacute;ditos:
                </td>
                <td>
                    <input type="text" name="nroCreditos" size="5" maxlength="5" value="<?echo $_REQUEST['creditos']?>">
                </td>
            </tr>
            <table class="contenidotabla centrar" width="100%" border="0">
            <tr>
                <td colspan="3" align="center"> <font size="2"><b>Distribuci&oacute;n</b></font></td>
            </tr>
            <tr class="centrar">
                <td width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Directo
                </td>
                <td width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Complementario
                </td>
                <td width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Autonomo
                </td>
            </tr>
            <tr class="centrar">
                <td width="33%">
                    <input type="text" name="htd" size="5" maxlength="5" value="<?echo $_REQUEST['htd']?>">
                </td>
                <td width="33%">
                    <input type="text" name="htc" size="5" maxlength="5" value="<?echo $_REQUEST['htc']?>">
                </td>
                <td width="33%">
                    <input type="text" name="hta" size="5" maxlength="5" value="<?echo $_REQUEST['hta']?>">
                </td>
            </tr>
            <tr class="centrar">
                <td colspan="3" >
                    <font size="2" color="red">*</font>N&uacute;mero de semanas en que se cursa el espacio ac&aacute;demico
                </td>
            </tr>
            <?
              $htd=$_REQUEST['htd'];
              $htc=$_REQUEST['htc'];
              $hta=$_REQUEST['hta'];
              $nroCreditos=$_REQUEST['creditos'];

                if($_REQUEST['semanas']!='')
                    {
                     $semanas=$_REQUEST['semanas'];
                    }
                    else
                    {
                     $semanas=($nroCreditos*48)/($htd+$htc+$hta);
                    }
            ?>
            <tr class="centrar">
                <td colspan="3">
                    <select name="semanas" id="<? echo $semanas;?>" style="width:270px">
                <option value="16" <? if($semanas==16){echo "selected=16";} ?>>Espacios Semestrales (16 semanas)</option>
                <option value="32" <? if($semanas==32){echo "selected=32";} ?>>Espacios Anuales (32 semanas)</option>
            </select>
               </td>
            </tr>
            </table>
            <table class="contenidotabla centrar" width="100%" border="0">
                <tr>
                <td class="centrar" width="50%">
                    <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                    <input type="hidden" name="codEspacio" value="<?echo $_REQUEST['codEspacio']?>">
                    <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
                    <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
                    <input type="hidden" name="facultad" value="<?echo $_REQUEST['facultad']?>">
                    <input type="hidden" name="nivel" value="0">
                    <input type="hidden" name="clasificacion" value="4">
                    <input type="hidden" name="opcion" value="validarEA">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="submit" value="Guardar" >
                </td>
                <td class="centrar" width="50%">
                    <input type="reset" >
                </td>
            </tr>
            </table>
        </form>
        </table>

        <?
    }

    /**
     * Funcion que permite modificar datos de espacios que se encuentran en mas de un plan de estudios
     * 08/03/2012 Se modifica para permitir cambiar creditos, htd, htc, hta en todos los planes asociados 
     * @param <type> $configuracion
     * @param <type> $variable 
     */
    function editarEspacioComun($configuracion,$variable)
    {
        $nombreEspacio=strtr(strtoupper($variable[2]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
        ?>
            <form name="<?echo $this->formulario?>" action="index.php" method="POST">
                <table class="contenidotabla centrar">
                    <tr>
                        <td colspan="4" class="centrar">
                            <font size="2"><?echo $nombreEspacio?> ES UN ESPACIO ACAD&Eacute;MICO COMUN </font><HR>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="centrar">
                            <font size="2">INFORMACI&Oacute;N DEL ESPACIO ACAD&Eacute;MICO</font><HR>
                        </td>
                    </tr>
                    <tr>
                        <td class="cuadro_brownOscuro cuadro_plano" width="20%">Espacio Acad&eacute;mico:</td><td class="cuadro_plano"><b><?echo $variable[8]?></b></td>
                    </tr>
                    <tr>
                        <td class="cuadro_brownOscuro cuadro_plano">Nombre:</td><td class="cuadro_plano"><input type="text" name="nombreEspacio" size="50" value="<?echo $variable[2]?>"></td>

                    </tr>
                    <tr>
                        <td class="cuadro_brownOscuro cuadro_plano">Cr&eacute;ditos:</td><td class="cuadro_plano"><input type="text" name="nroCreditos" size="50" value="<?echo $variable[3]?>"></td>
                    </tr>
                    <tr>
                        <td class="cuadro_brownOscuro cuadro_plano">H.T.D:</td><td class="cuadro_plano"><input type="text" name="htd" size="50" value="<?echo $variable[5]?>"></td>
                    </tr>
                    <tr>
                        <td class="cuadro_brownOscuro cuadro_plano">H.T.C:</td><td class="cuadro_plano"><input type="text" name="htc" size="50" value="<?echo $variable[6]?>"></td>
                    </tr>
                    <tr>
                        <td class="cuadro_brownOscuro cuadro_plano">H.T.A:</td><td class="cuadro_plano"><input type="text" name="hta" size="50" value="<?echo $variable[7]?>"></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="centrar"><b><?echo $variable[11]?></b><br>¿Desea guardar la informaci&oacute;n anteriormente diligenciada?</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="centrar">
                            <table width="100%">
                                <tr>
                                    <td class="centrar">
                                        <input type="hidden" name="opcion" value="validarEA">
                                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                        <input type="hidden" name="codEspacio" value="<?echo $variable[8]?>">
                                        <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                                        <input type="hidden" name="facultad" value="<?echo $variable[10]?>">
                                        <input type="hidden" name="clasificacion" value="4">
                                        <input type="hidden" name="nivel" value="<?echo $variable[4]?>">
                                        <input type="hidden" name="semanas" value="<?echo $variable[9]?>">
                                        <input type="submit" value="Guardar">
                                    </td>
            </form>
            <form name="<?echo $this->formulario?>" action="index.php" method="POST">
                                    <td class="centrar">
                                        <input type="hidden" name="opcion" value="cancelar">
                                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                        <input type="hidden" name="codProyecto" value="<?echo $this->datosEspacio['codProyecto']?>">
                                        <input type="hidden" name="planEstudio" value="<?echo $this->datosEspacio['planEstudio']?>">
                                        <input type="hidden" name="semanas" value="<?echo $this->datosEspacio['semanas']?>">
                                        <input type="hidden" name="facultad" value="<?echo $variable[10]?>">
                                        <input type="submit" value="Cancelar">
                                    </td>
            </form>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <?


        $cadena_sql=$this->sql->cadena_sql($configuracion,"proyectos_involucrados",$variable);
        $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        if(is_array($resultado_proyectos))
            {
                ?>
                <table class="contenidotabla centrar">
                  <caption>Proyectos al que est&aacute; asociado el Espacio Acad&eacute;mico</caption>
                <?

                for($i=0;$i<count($resultado_proyectos);$i++)
                {
                    if($resultado_proyectos[$i][3]!=$resultado_proyectos[$i-1][3])
                        {
                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nombre_facultad",$resultado_proyectos[$i][3]);
                            $resultado_facultad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                           ?>
                                <tr>
                                    <td colspan="4" class="cuadro_brownOscuro centrar">
                                        <font size="2"><b><?echo $resultado_facultad[0][1]?></b></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="cuadro_plano centrar"><font size="1"><b>Plan de Estudio</b></font></td>
                                    <td class="cuadro_plano centrar"><font size="1"><b>Nombre</b></font></td>
                                    <td class="cuadro_plano centrar"><font size="1"><b>Clasificaci&oacute;n</b></font></td>
                                    <td class="cuadro_plano centrar"><font size="1"><b>Estado</b></font></td>
                                </tr>

                            <?
                        }
                     ?>
                                <tr>
                                    <td class="cuadro_plano centrar">
                                        <?echo $resultado_proyectos[$i][0]?>
                                    </td>
                                    <td class="cuadro_plano">
                                        <?echo $resultado_proyectos[$i][1]?>
                                    </td>
                                    <td class="cuadro_plano centrar">
                                        <?echo $resultado_proyectos[$i][2]?>
                                    </td>
                                    <td class="cuadro_plano centrar">
                                        <?if (trim($resultado_proyectos[$i][4])=='A'){echo "Activo";}
                                        else {echo "Inactivo";}?>
                                    </td>
                                </tr>

                        <?
                }
                ?>
                </table>
                <?
            }
    }

    function editarEspacioPlanEstudio($configuracion,$variable)
    {
      $cadena_sql=$this->sql->cadena_sql($configuracion,"clasificacion","");
        $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarAsociacion",$variable);
        $resultado_asociacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        if(is_array($resultado_asociacion)&&$resultado_asociacion[0][0]=$variable[8])
        {$asociado=1;}
        else{$asociado=0;}

       ?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
    <table class="contenidotabla centrar" width="100%" border="0">
            <tr>
                <td class="cuador_color centrar" colspan="4">
                    <font size="2">El espacio acad&eacute;mico tiene asignado el cod&iacute;go<b> <?echo $variable[8]?></b> y contiene la siguiente informaci&oacute;n:</font>
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%" >
                  <font size="2">Plan de Estudio:</font>
                </td>
                <td class="cuadro_plano" colspan="3">
                  <font size="2"><b>&nbsp;<?echo $variable[0]?></b></font>
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%" >
                  <font size="2">Cod&iacute;go del Espacio Acad&eacute;mico:</font>
                </td>
                <td class="cuadro_plano" colspan="3">
                  <font size="2"><b>&nbsp;<?echo $variable[8]?></b></font>
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%">
                  <font size="2">Nombre del Espacio Acad&eacute;mico:</font>
                </td>
                <td class="cuadro_plano" colspan="3">
                  <input type="text" name="nombreEspacio" value="<?echo $variable[2]?>" size="45">
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%">
                  <font size="2">Tipo de clasificaci&oacute;n:</font>
                </td>
                <td class="cuadro_plano" colspan="3">
                  <font size="2"><b>&nbsp;<?
                    for($i=0;$i<count($resultado_clasificacion);$i++)
                    {
                      if ($variable[1]==$resultado_clasificacion[$i][0]){echo $resultado_clasificacion[$i][1];}else{}
                    }
                  ?></b></font>
                  <input type="hidden" name="clasificacion" value="<?echo $variable[1]?>">
                </td>
            </tr>
            <?if($asociado==0){?>
            <tr>
                <td class="cuadro_plano" width="30%">
                  <font size="2">N&uacute;mero de Cr&eacute;ditos:</font>
                </td>
                <td class="cuadro_plano" colspan="3">
                  <input type="text" name="nroCreditos" value="<?echo $variable[3]?>">
                </td>
            </tr>
                <?}else{?>
            <tr>
                <td class="cuadro_plano" width="30%">
                  <font size="2">N&uacute;mero de Cr&eacute;ditos:</font>
                </td>
                <td class="cuadro_plano" colspan="3">
                  <font size="2"><b>&nbsp;<?echo $variable[3]?></b></font>
                  <input type="hidden" name="nroCreditos" value="<?echo $variable[3]?>">
                </td>
            </tr>
                <?}?>
            <tr>
                <td class="cuadro_plano" width="30%"><font size="2">Horas de Trabajo Directo:</font></td><td class="cuadro_plano" colspan="3"><input type="text" name="htd" value="<?echo $variable[5]?>"></td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%"><font size="2">Horas de Trabajo Cooperativo:</font></td><td class="cuadro_plano" colspan="3"><input type="text" name="htc" value="<?echo $variable[6]?>"></td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%"><font size="2">Horas de Trabajo Autonomo:</font></td><td class="cuadro_plano" colspan="3"><input type="text" name="hta" value="<?echo $variable[7]?>"></td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%"><font size="2">N&uacute;mero de semanas en que se cursa el espacio ac&aacute;demico:</font></td><td class="cuadro_plano" colspan="3"><input type="text" name="semanas" value="<?echo $variable[9]?>"></td>
            </tr>
            <tr>
                <td class="cuadro_color_plano centrar" colspan="4"><br><font size="2"><b><?echo $variable[11]?></b><br>¿Desea guardar la informaci&oacute;n anteriormente diligenciada?</font></td>
            </tr>
            <tr>
                <td colspan="2" class="centrar" width="50%"><br>

                    <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                    <input type="hidden" name="codEspacio" value="<?echo $variable[8]?>">
                    <input type="hidden" name="facultad" value="<?echo $variable[10]?>">
                    <input type="hidden" name="nivel" value="<?echo $variable[4]?>">
                    <input type="hidden" name="opcion" value="validarEA">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="image" value="Confirmado" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
                </td>

                <td colspan="2" class="centrar" width="50%"><br>
                    <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variables="pagina=registro_aprobarPortafolio";
                        $variables.="&opcion=ver";
                        $variables.="&planEstudio=".$variable[0];
                        $variables.="&facultad=".$variable[10];
                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
                    <a href="<?echo $pagina.$variables?>">
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/x.png" width="35" height="35" border="0"><br>No
                    </a>
                </td>
            </tr>

        </table>
    </form>

                    <?
    }

    function validarinformacionModificacion($configuracion)
    {
        $codProyecto=$_REQUEST['codProyecto'];
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $clasificacion=$_REQUEST['clasificacion'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nivel=$_REQUEST['nivel'];
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
        $semanas=$_REQUEST['semanas'];
        $facultad=$_REQUEST['facultad'];

        $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion, $nombreEspacio, $nroCreditos, $nivel, $htd, $htc, $hta);

        if(($nombreEspacio=='')||($nroCreditos=='')||($htd=='')||($htc=='')||($hta==''))
            {
                echo "<script>alert('Todos los campos deben ser diligenciados')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registro_aprobarPortafolio";
                $variables.="&opcion=modificarEspacio";
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&codEspacio=".$codEspacio;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;
                $variables.="&clasificacion=".$clasificacion;
                $variables.="&nombreEspacio=".$nombreEspacio;
                $variables.="&creditos=".$nroCreditos;
                $variables.="&nivel=".$nivel;
                $variables.="&htd=".$htd;
                $variables.="&htc=".$htc;
                $variables.="&hta=".$hta;
                $variables.="&semanas=".$semanas;
                $variables.="&facultad=".$facultad;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }

            if(!is_numeric($nroCreditos)||!is_numeric($htd)||!is_numeric($htc)||!is_numeric($hta))
            {
                echo "<script>alert('Los campos (Creditos, Nivel, HTD, HTC, HTA) deben ser númericos')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registro_aprobarPortafolio";
                $variables.="&opcion=modificarEspacio";
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&codEspacio=".$codEspacio;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;
                $variables.="&clasificacion=".$clasificacion;
                $variables.="&nombreEspacio=".$nombreEspacio;
                $variables.="&creditos=".$nroCreditos;
                $variables.="&nivel=".$nivel;
                $variables.="&htd=".$htd;
                $variables.="&htc=".$htc;
                $variables.="&hta=".$hta;
                $variables.="&semanas=".$semanas;
                $variables.="&facultad=".$facultad;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }

         //Determina la distribucion por semestre
         //$totalDistribucion=$hta+$htc+$htd;
         //$horasCreditos=$nroCreditos*3;

         //Determina la distribucion segun las semanas seleccionadas(Semestralizado 16, Anualizado 32)
         $totalDistribucion=($hta+$htc+$htd)*$semanas;
         $horasCreditos=$nroCreditos*48;

         if($totalDistribucion!=$horasCreditos)
             {
                echo "<script>alert('La distribución seleccionada no concuerda con la cantidad de créditos')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registro_aprobarPortafolio";
                $variables.="&opcion=modificarEspacio";
                $variables.="&codEspacio=".$codEspacio;
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;
                $variables.="&clasificacion=".$clasificacion;
                $variables.="&nombreEspacio=".$nombreEspacio;
                $variables.="&creditos=".$nroCreditos;
                $variables.="&nivel=".$nivel;
                $variables.="&htd=".$htd;
                $variables.="&htc=".$htc;
                $variables.="&hta=".$hta;
                $variables.="&semanas=".$semanas;
                $variables.="&facultad=".$facultad;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
             }

            $variable[10]=$codEspacio;
            $variable[11]=$semanas;

                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registro_aprobarPortafolio";
                $variables.="&opcion=Solicitarconfirmar";
                $variables.="&codProyecto=".$codProyecto;
                $variables.="&codEspacio=".$codEspacio;
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&nombreProyecto=".$nombreProyecto;
                $variables.="&clasificacion=".$clasificacion;
                $variables.="&nombreEspacio=".$nombreEspacio;
                $variables.="&nroCreditos=".$nroCreditos;
                $variables.="&nivel=".$nivel;
                $variables.="&htd=".$htd;
                $variables.="&htc=".$htc;
                $variables.="&hta=".$hta;
                $variables.="&semanas=".$semanas;
                $variables.="&facultad=".$facultad;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;

    }

    function solicitarConfirmacionModificacion($configuracion)
    {
        $codProyecto=$_REQUEST['codProyecto'];
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $clasificacion=$_REQUEST['clasificacion'];
        $nombreEspacio=$_REQUEST['nombreEspacio'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nivel=$_REQUEST['nivel'];
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
        $semanas=$_REQUEST['semanas'];
        $facultad=$_REQUEST['facultad'];

        $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion, $nombreEspacio, $nroCreditos, $nivel, $htd, $htc, $hta,$codEspacio,$semanas);
        ?>
            <table class="contenidotabla centrar">
                <tr>
                    <td class="cuador_color centrar" colspan="3">
                        <font size="2">El espacio acad&eacute;mico tiene asignado el c&oacute;digo<b> <?echo $variable[10]?></b> y contiene la siguiente informaci&oacute;n:</font>
                    </td>
                </tr>
                <tr>
                    <td class="cuadro_plano" width="30%" ><font size="2">C&oacute;digo del Espacio Acad&eacute;mico:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[10]?></font></td>
                </tr>
                <tr>
                    <td class="cuadro_plano" width="30%"><font size="2">Nombre del Espacio Acad&eacute;mico:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[4]?></font></td>
                </tr>
                <tr>
                    <td class="cuadro_plano" width="30%"><font size="2">N&uacute;mero de Cr&eacute;ditos:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[5]?></font></td>
                </tr>
                <tr>
                    <td class="cuadro_plano" width="30%"><font size="2">Horas de Trabajo Directo:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[7]?></font></td>
                </tr>
                <tr>
                    <td class="cuadro_plano" width="30%"><font size="2">Horas de Trabajo Cooperativo:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[8]?></font></td>
                </tr>
                <tr>
                    <td class="cuadro_plano" width="30%"><font size="2">Horas de Trabajo Autonomo:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[9]?></font></td>
                </tr>
                <tr>
                    <td class="cuadro_plano" width="30%"><font size="2">N&uacute;mero de semanas en que se cursa el espacio ac&aacute;demico:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $variable[11]?></font></td>
                </tr>
                <tr>
                    <td class="cuadro_color_plano centrar" colspan="3"><font size="2"><font color="red">Este espacio se actualizar&aacute; para todos los planes de estudios a los que est&aacute; asociado.</font><br>¿Desea guardar la informaci&oacute;n anteriormente diligenciada?</font></td>
                </tr>
                <tr>
                    <td width="33%" class="centrar"><br>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                        <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                        <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                        <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                        <input type="hidden" name="nombreEspacio" value="<?echo $variable[4]?>">
                        <input type="hidden" name="nroCreditos" value="<?echo $variable[5]?>">
                        <input type="hidden" name="nivel" value="<?echo $variable[6]?>">
                        <input type="hidden" name="htd" value="<?echo $variable[7]?>">
                        <input type="hidden" name="htc" value="<?echo $variable[8]?>">
                        <input type="hidden" name="hta" value="<?echo $variable[9]?>">
                        <input type="hidden" name="codEspacio" value="<?echo $variable[10]?>">
                        <input type="hidden" name="semanas" value="<?echo $variable[11]?>">
                        <input type="hidden" name="facultad" value="<?echo $facultad?>">
                        <input type="hidden" name="opcion" value="confirmado">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" value="Confirmado" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
                        </form>
                    </td>
                    <td width="33%" class="centrar"><br>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                        <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                        <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                        <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                        <input type="hidden" name="nombreEspacio" value="<?echo $variable[4]?>">
                        <input type="hidden" name="nroCreditos" value="<?echo $variable[5]?>">
                        <input type="hidden" name="nivel" value="<?echo $variable[6]?>">
                        <input type="hidden" name="htd" value="<?echo $variable[7]?>">
                        <input type="hidden" name="htc" value="<?echo $variable[8]?>">
                        <input type="hidden" name="hta" value="<?echo $variable[9]?>">
                        <input type="hidden" name="codEspacio" value="<?echo $variable[10]?>">
                        <input type="hidden" name="semanas" value="<?echo $variable[11]?>">
                        <input type="hidden" name="facultad" value="<?echo $facultad?>">
                        <input type="hidden" name="opcion" value="modificarEspacio">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" value="modificar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/modificar.png" width="35" height="35"><br>Modificar
                        </form>
                    </td>
                    <td width="33%" class="centrar"><br>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                        <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                        <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                        <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                        <input type="hidden" name="nombreEspacio" value="<?echo $variable[4]?>">
                        <input type="hidden" name="nroCreditos" value="<?echo $variable[5]?>">
                        <input type="hidden" name="nivel" value="<?echo $variable[6]?>">
                        <input type="hidden" name="htd" value="<?echo $variable[7]?>">
                        <input type="hidden" name="htc" value="<?echo $variable[8]?>">
                        <input type="hidden" name="hta" value="<?echo $variable[9]?>">
                        <input type="hidden" name="codEspacio" value="<?echo $variable[10]?>">
                        <input type="hidden" name="semanas" value="<?echo $variable[11]?>">
                        <input type="hidden" name="facultad" value="<?echo $facultad?>">
                        <input type="hidden" name="opcion" value="cancelar">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="image" value="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="35" height="35"><br>No
                        </form>
                    </td>
                </tr>

            </table>
            <?
        }

    function guardarEAModificacion($configuracion)
    {
            $usuario=$this->usuario;
            $codProyecto=$_REQUEST['codProyecto'];
            $planEstudio=$_REQUEST['planEstudio'];
            $nombreProyecto=$_REQUEST['nombreProyecto'];
            $clasificacion=$_REQUEST['clasificacion'];
            $nombreEspacio=$_REQUEST['nombreEspacio'];
            $nroCreditos=$_REQUEST['nroCreditos'];
            $nivel=$_REQUEST['nivel'];
            $htd=$_REQUEST['htd'];
            $htc=$_REQUEST['htc'];
            $hta=$_REQUEST['hta'];
            $codEspacio=$_REQUEST['codEspacio'];
            $semanas=$_REQUEST['semanas'];
            $facultad=$_REQUEST['facultad'];

            if((48*$nroCreditos)!=(($htd+$htc+$hta)*$semanas))
                {
                    $distribucion='1';
                    $msjDis=", la distribuci\u00F3n de horas no concuerda con el n\u00FAmero de cr\u00E9ditos";
                }else
                    {
                        $distribucion='0';
                        $msjDis="";
                    }

            $cadena_sql=$this->sql->cadena_sql($configuracion,"modificarEspacio_notas",$codEspacio);
            $modificarEspacioNotas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(!is_array($modificarEspacioNotas)||$modificarEspacioNotas[0][0]>0)
                {
                    $porNotas='1';
                    $msjNotas=", existen registros de notas";
                }else
                    {
                        $porNotas='0';
                        $msjNotas="";
                    }

            $cadena_sql=$this->sql->cadena_sql($configuracion,"modificarEspacio_inscripcion",$codEspacio);
            $modificarEspacioInscripcion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(!is_array($modificarEspacioInscripcion)||$modificarEspacioInscripcion[0][0]>0)
                {
                    $porInscripcion='1';
                    $msjIns=", existen registros de inscripciones";
                }else
                    {
                        $porInscripcion='0';
                        $msjIns="";
                    }

            $cadena_sql=$this->sql->cadena_sql($configuracion,"modificarEspacio_horario",$codEspacio);
            $modificarEspacioHorario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(!is_array($modificarEspacioHorario)||$modificarEspacioHorario[0][0]>0)
                {
                    $porHorario='1';
                    $msjHor=", existen registros de horarios";
                }else
                    {
                        $porHorario='0';
                        $msjHor="";
                    }
/**
 *  Se comenta para permitir la modificación de datos del espacio por parte de Ases Vice
 */
//            if($distribucion=='1' || $porHorario=='1'||$porInscripcion=='1'||$porNotas=='1')
//                {
//                    echo "<script>alert('El espacio acad\u00E9mico no se puede modificar ".$msjDis.$msjNotas.$msjIns.$msjHor.".')</script>";
//
//                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
//                    $ruta="pagina=registro_aprobarPortafolio";
//                    $ruta.="&opcion=modificarEspacio";
//                    $ruta.="&codEspacio=".$codEspacio;
//                    $ruta.="&planEstudio=".$planEstudio;
//                    $ruta.="&nivel=".$nivel;
//                    $ruta.="&creditos=".$nroCreditos;
//                    $ruta.="&htd=".$htd;
//                    $ruta.="&htc=".$htc;
//                    $ruta.="&hta=".$hta;
//                    $ruta.="&clasificacion=".$clasificacion;
//                    $ruta.="&nombreEspacio=".$nombreEspacio;
//                    $ruta.="&semanas=".$semanas;
//                    $ruta.="&facultad=".$facultad;
//
//                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
//                    $this->cripto=new encriptar();
//                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
//
//                    echo "<script>location.replace('".$pagina.$ruta."')</script>";
//                    break;
//                }

            $ano=$this->ano;
            $periodo=$this->periodo;

            if($ano)
            {
            //$variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion,$nombreEspacio,$nroCreditos,$nivel,$htd,$htc,$hta,$codEspacio);

            $variable=array($planEstudio,$clasificacion,$nombreEspacio,$nroCreditos,$nivel,$htd,$htc,$hta,$codEspacio,$semanas);

            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarEspacio_acpen",$variable);
            $resultado_espacioacpen=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(is_array($resultado_espacioacpen))
                {
                    $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarEspacio_acpen",$variable);
                    $resultado_actualizaracpen=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );
                }

            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarEspacio_acasi",$variable);
            $resultado_espacioacasi=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(is_array($resultado_espacioacasi))
                {
                    $nombreEspacio=strtr(strtoupper($variable[2]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                    $variable[10]=$nombreEspacio;

                    $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizarEspacio_acasi",$variable);
                    $resultado_actualizaracasi=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"" );
                }

            $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizar_espacioAcademico",$variable);
            $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

            $cadena_sql=$this->sql->cadena_sql($configuracion,"actualizar_planEstudio",$variable);
            $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );

            $cadena_sql=$this->sql->cadena_sql($configuracion,"buscarEspacioComunOracle",$variable);//echo $cadena_sql;exit;
            $resultado_comunOracle=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            foreach ($resultado_comunOracle as $key => $value) {
                $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $codEspacio, $value[8], $value[0]);
                $cadena_sql_registroModificar=$this->sql->cadena_sql($configuracion,"registroModificarEA",$variablesRegistro);
                $resultadoRegistroModificar==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroModificar,"");
            }

                echo "<script>alert('El Espacio Acad\u00E9mico ".$nombreEspacio." se ha modificado')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registro_aprobarPortafolio";
                    $variables.="&opcion=ver";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&facultad=".$facultad;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    echo "<script>location.replace('".$pagina.$variables."')</script>";
                    break;

                    }else{

                        echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde')</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variables="pagina=registro_aprobarPortafolio";
                            $variables.="&opcion=modificarEspacio";
                            $variables.="&codEspacio=".$codEspacio;
                            $variables.="&codProyecto=".$codProyecto;
                            $variables.="&planEstudio=".$planEstudio;
                            $variables.="&nombreProyecto=".$nombreProyecto;
                            $variables.="&clasificacion=".$clasificacion;
                            $variables.="&nombreEspacio=".$nombreEspacio;
                            $variables.="&nroCreditos=".$nroCreditos;
                            $variables.="&nivel=".$nivel;
                            $variables.="&htd=".$htd;
                            $variables.="&htc=".$htc;
                            $variables.="&hta=".$hta;
                            $variables.="&semanas=".$semanas;
                            $variables.="&facultad=".$facultad;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                            echo "<script>location.replace('".$pagina.$variables."')</script>";
                            break;
            }
        }
  
}
?>
