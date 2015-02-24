
        <script language="javascript">
            function deshabilitarControlNivel(nivel, nombreControl){
                 if(nombreControl=='clasificacion'){
                                     //formulario un solo plan
                    document.registroModificarEspacioAsisVice_modificarTodo.nivel.disabled=true;
                    document.registroModificarEspacioAsisVice_modificarTodo.nivel.value=nivel
                    document.registroModificarEspacioAsisVice_modificarTodo.nivel.size=20
                 }
                 if(nombreControl=='clasificacionTodos'){
                    //formulario para todos los planes
                    document.registroModificarEspacioAsisVice_nivelTodos.nivelTodos.disabled=true;
                    document.registroModificarEspacioAsisVice_nivelTodos.nivelTodos.value=nivel
                    document.registroModificarEspacioAsisVice_nivelTodos.nivelTodos.size=20
                 }
        }

            function habilitarControlNivel(nivel,nombreControl)
            {
                 if(nombreControl=='clasificacion'){
                //formulario para todos los planes
                document.registroModificarEspacioAsisVice_modificarTodo.nivel.disabled=false;
                document.registroModificarEspacioAsisVice_modificarTodo.nivel.value=nivel
                 }

                 if(nombreControl=='clasificacionTodos'){
                //formulario un solo plan
                document.registroModificarEspacioAsisVice_nivelTodos.nivelTodos.disabled=false;
                document.registroModificarEspacioAsisVice_nivelTodos.nivelTodos.value=nivel
                 }
            }
        </script>

<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroModificarEspacioAsisVice extends funcionGeneral {
  private $configuracion;
  private $datosEspacio;
  private $resultadoClasificaciones; //listado de tipos de clasificacion del un espacio academico
  private $control; //arreglo con las caracteristicas de los controles del formulario
  private $usuario;

  //Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");
        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        $this->procedimientos=new procedimientos();
        $this->tema=$tema;
        $this->sql=$sql;
        $this->datosEspacio=array();
        $this->resultadoClasificaciones=array();

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"asesvice");

        //Datos de sesion
        $this->formulario="registroModificarEspacioAsisVice";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
    }


    /**
     * Funcion que realiza el ingreso al modulo y crea las variables del espacio a editar
     */
    function modificarEspacio()
    {
        $this->datosEspacio=$this->buscarDatosEspacio();
        $this->datosEspacio['codProyecto']=$_REQUEST['codProyecto'];
        $this->encabezadoModulo($this->datosEspacio);
        $this->editarEspacio();
    }


    /**
     * Estructura los formularios necesario para la edicion del
     * espacio academico
     *
     */
    function editarEspacio(){
        //Rescata los tipos de clasificion existentes en la base de datos
        $this->resultadoClasificaciones=$this->buscarClasificacion();
        $this->control=$this->crearArregloControl();   //arreglo con las caracteristicas de los controles utilizados en el formulario

        $resultado_proyectos=$this->consultarPlanesEspacio($this->datosEspacio);
        ?>

        <div align="center">
            <font size="2">INFORMACI&Oacute;N DEL ESPACIO ACAD&Eacute;MICO</font><HR>
        </div>

        <?
        $this->mostrarFormularioNombreEspacio();

        if(is_array($resultado_proyectos)){
            $numeroProyectos=count($resultado_proyectos);
            if($numeroProyectos==1)
                {
                $this->mostrarFormularioDatosEspacio($numeroProyectos);  //modifica todos los datos del espacio para el plan seleccionado
                }
            else{
                $this->mostrarFormularioDatosEspacio($numeroProyectos); //modifica todos los datos del espacio para el plan seleccionado
                $this->mostrarFormulariosDatosEspacioTodos();    //presenta formularios que actualizan datos para el espacio en todos los planes de estudios
            }
            $this->mostrarBotonCancelar();
            if (is_array($resultado_proyectos))
            {
              $this->generarListadoPlanesAsociados($resultado_proyectos);
            }
        }
    }

    /**
     *Crea un arreglo con los datos para cada control text de un formulario
     * etiqueta del control, nombre del control, valor del control(variable), opcion del boton enviar del control
     *
     * @return array
     */
    function crearArregloControl(){

        //para un solo plan de estudios
        $control['clasificacion']=  array(etiqueta=>'Clasificaci&oacute;n', nombre=>'clasificacion',valor=>'clasificacion',              opcion=>'modificarClasificacion');
        $control['creditos']=       array(etiqueta=>'Cr&eacute;ditos',      nombre=>'nroCreditos',  valor=>'nroCreditos',   opcion=>'modificarCreditos');
        $control['htd']=            array(etiqueta=>'HTD',                  nombre=>'htd',          valor=>'htd',           opcion=>'modificarHtd');
        $control['htc']=            array(etiqueta=>'HTC',                  nombre=>'htc',          valor=>'htc',           opcion=>'modificarHtc');
        $control['hta']=            array(etiqueta=>'HTA',                  nombre=>'hta',          valor=>'hta',           opcion=>'modificarHta');
        $control['nivel']=          array(etiqueta=>'Nivel',                nombre=>'nivel',        valor=>'nivel',         opcion=>'modificarNivel');

        //Para todos los planes de estudios
        $control['clasificacionTodos']= array(etiqueta=>'Clasificaci&oacute;n', nombre=>'clasificacionTodos', valor=>'clasificacion',opcion=>'modificarClasificacion');
        $control['creditosTodos']=      array(etiqueta=>'Cr&eacute;ditos',      nombre=>'nroCreditos',   valor=>'nroCreditos', opcion=>'modificarCreditos');
        $control['htdTodos']=           array(etiqueta=>'HTD',                  nombre=>'htd',           valor=>'htd', opcion=>'modificarHtd');
        $control['htcTodos']=           array(etiqueta=>'HTC',                  nombre=>'htc',           valor=>'htc',  opcion=>'modificarHtc');
        $control['htaTodos']=           array(etiqueta=>'HTA',                  nombre=>'hta',           valor=>'hta',    opcion=>'modificarHta');
        $control['nivelTodos']=         array(etiqueta=>'Nivel',                nombre=>'nivelTodos',         valor=>'nivel',opcion=>'modificarNivel');

        return $control;

    }

    /**
     * Presenta formulario con opcion de actualizacion del
     * nombre del espacio academico, utiliza el atributo de la clase $this->datosEspacio
     *
     */
    function mostrarFormularioNombreEspacio(){
                ?>

            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario.'_Nombre'?>'>
                <fieldset>
                    <legend><b>Actualizaci&oacute;n de Nombre</b></legend>

                                <table style="width:100%">
                                    <tr>
                                        <td>C&oacute;digo:
                                        <b><?echo $this->datosEspacio['codEspacio']?></b></td>
                                    </tr>
                                    <tr>
                                        <td class="cuadro_brownOscuro cuadro_plano">*Nombre:</td>
                                        <td>
                                            <input type="text"  style="width:100%" name="nombreEspacio" value="<?echo $this->datosEspacio['nombreEspacio']?>"maxlength ="100">
                                        </td>
                                        <td>
                                            <?$this->presentarBotonEnviar('modificarNombre');?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="font-size:12px; color: red">
                                            * El cambio de nombre se aplicar&aacute; en todos los planes de estudios
                                        </td>
                                    </tr>
                                </table>

                </fieldset>
            </form>
                <?

    }

    /**
     *Presenta el formulario para actualizacion de los datos del espacio academico en el
     * plan de estudios seleccionado:
     * clasificacion, creditos,HTD, HTC, HTA y Nivel.
     *
     * @param type  $numeroProyectos
     */
    function mostrarFormularioDatosEspacio($numeroProyectos){

        ?>
        <div style="float:left; max-width: 50%">
            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario.'_modificarTodo';?>'>
                <fieldset>
                        <legend>
                            <b>
                                Modificar plan de estudios <?echo $this->datosEspacio['planEstudio']; ?>
                            </b>
                        </legend>
                            <table border="0">
                                <tr>
                                    <td class="cuadro_brownOscuro cuadro_plano">
                                        <?echo $this->control['clasificacion']['etiqueta']?>:
                                    </td>
                                    <td colspan="2">
                                        <?$this->mostrarControlClasificacion($this->control['clasificacion']['nombre']);?>
                                    </td>
                                </tr>
                                <tr><td  colspan="2"><hr></td></tr>
                                <tr>
                                        <?
                                        $this->presentarControlText($this->control['creditos'], $numeroProyectos);
                                        ?>
                                </tr>
                                <tr>
                                        <?
                                        $this->presentarControlText($this->control['htd'], $numeroProyectos);
                                        ?>
                                </tr>
                                <tr>
                                        <?
                                        $this->presentarControlText($this->control['htc'], $numeroProyectos);
                                        ?>
                                </tr>
                                <tr>
                                        <?
                                        $this->presentarControlText($this->control['hta'], $numeroProyectos);
                                        ?>
                                </tr>
                                <tr><td  colspan="2"><hr></td></tr>
                                <tr>
                                        <?
                                        $this->presentarControlText($this->control['nivel'], $numeroProyectos);
                                        ?>
                                </tr>
                                <tr><td  colspan="2"><hr></td></tr>
                                <tr class="centrar">
                                    <td align="center">
                                        <?
                                        $this->presentarBotonEnviar('confirmar');
                                        ?>
                                    </td>
                                </tr>
                            </table>
                 </fieldset>
            </form>
        </div>
        <?

    }

    /**
     *Presenta el formulario para actualizacion de los datos del espacio academico en el
     * plan de estudios seleccionado:
     * clasificacion, creditos,HTD, HTC, HTA y Nivel.
     *
     * @param type  $numeroProyectos
     */
    function mostrarFormulariosDatosEspacioTodos(){

   ?>
        <div>
                <fieldset>
                        <legend>
                            <b>
                                Modificar para todos los planes de estudios
                            </b>
                        </legend>
                            <table border="0">
                                <tr>
                                    <td>
                                    <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario.'_'.$this->control['clasificacionTodos']['nombre'];?>'>
                                    <table border="0">
                                        <tr>
                                           <td class="cuadro_brownOscuro cuadro_plano" style="max-width: 20%">
                                                <?echo $this->control['clasificacionTodos']['etiqueta']?>:
                                           </td>
                                           <td>
                                                <?$this->mostrarControlClasificacion($this->control['clasificacionTodos']['nombre']);?>
                                           </td>
                                           <td align="right">
                                                <?
                                                $this->presentarBotonEnviar($this->control['clasificacionTodos']['opcion']);
                                                ?>
                                           </td>
                                        </tr>
                                    </table>
                                    </form>
                                    </td>
                                </tr>
                                <tr><td><hr></td></tr>
                                <tr>
                                    <td>
                                        <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario.'_'.$this->control['creditosTodos']['nombre'];?>'>
                                            <table style="width:100%">
                                                <tr>
                                                        <?
                                                        $this->presentarControlText($this->control['creditosTodos'], $numeroProyectos);
                                                        ?>
                                                    <td rowspan="4" align="right">
                                                        <?
                                                        $this->presentarBotonEnviar($this->control['creditosTodos']['opcion']);
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                        <?
                                                        $this->presentarControlText($this->control['htdTodos'], $numeroProyectos);
                                                        ?>
                                                </tr>
                                                <tr>
                                                        <?
                                                        $this->presentarControlText($this->control['htcTodos'], $numeroProyectos);
                                                        ?>
                                                </tr>
                                                <tr>
                                                        <?
                                                        $this->presentarControlText($this->control['htaTodos'], $numeroProyectos);
                                                        ?>
                                                </tr>
                                            </table>
                                        </form>
                                    </td>
                                </tr>
                                <tr><td><hr></td></tr>
                                <tr>
                                    <td>
                                        <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario.'_'.$this->control['nivelTodos']['nombre'];?>'>
                                            <table  style="width: 100%">
                                                <tr>
                                                        <?
                                                        $this->presentarControlText($this->control['nivelTodos'], $numeroProyectos);
                                                        ?>
                                                    <td rowspan="4" align="right">
                                                        <?
                                                        $this->presentarBotonEnviar($this->control['nivelTodos']['opcion']);
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>
                                    </td>
                                </tr>
                                <tr><td  colspan="3"><hr></td></tr>
                            </table>
                 </fieldset>
        </div>
        <?

    }

    /**
     *Presenta el titulo del marco de los formularios
     *
     * @param type $tipoFormulario
     */
    function presentarLegend($tipoFormulario){
    ?>
            <legend><b>
                <?
                if($tipoFormulario=='modificarTodosPlanes')
                    {
                    echo 'Modificaci&oacute;n para todos los planes de estudios';

                    }
                else
                    {
                    echo 'Modificaci&oacute;n para el plan de estudios '.$this->datosEspacio['planEstudio'];

                    }
                    ?>
                    </b>
            </legend>
            <?
    }

    /**
     *Arma el control de clasificacion
     *
     * @param type $tipoFormulario
     */
    function mostrarControlClasificacion($nombreControl){
     ?>

        <select class="cuadro_plano" style="width:200px" name="<?echo  $nombreControl?>">
              <?
                foreach ($this->resultadoClasificaciones as $key => $value) {
 ?>
                <option value="<?echo $value['CODIGO_CLASIFICACION']?>"<?
                    if ($this->datosEspacio['clasificacion']==$value['CODIGO_CLASIFICACION']){?>selected <?}

                    if($value['CODIGO_CLASIFICACION']==4){
                        ?>onClick="deshabilitarControlNivel('Portafolio Extr&iacute;nsecas', '<?echo $nombreControl?>')"<?
                        }
                    if($value['CODIGO_CLASIFICACION']==5){
                        ?>onClick="deshabilitarControlNivel('Componente Propede&uacute;tico',  '<?echo $nombreControl?>')"<?
                        }
                    else{
                        ?>onClick="habilitarControlNivel(<?echo $this->datosEspacio['nivel']?>,  '<?echo $nombreControl?>')"<?
                        }?>
                        ><?
                    echo strtr(strtoupper($value['NOMBRE_CLASIFICACION']), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ")
                    ?>
                </option>
              <?}?>
              </select>
<?

    }

    /**
     *Relaciona los atributos de cada uno de los controles
     *tipo text que se implemantaran en el formulario
     *
     * @param type $control
     * @param type $tipoFormulario
     */
    function presentarControlText($control, $numeroProyectos){
        ?>
            <td class="cuadro_brownOscuro cuadro_plano" style="max-width: 20%">
                <?echo $control['etiqueta']?>:
            </td>
            <td>
                    <?if($control['nombre']=='nivel'&&$this->datosEspacio['clasificacion']==4){
                        ?><input type="text" size="21" name="<?echo $control['nombre']?>" value="Portafolio Extr&iacute;nsecas" disabled=true<?
                        }
                    elseif($control['nombre']=='nivel'&&$this->datosEspacio['clasificacion']==5){
                        ?><input type="text" size="21" name="<?echo $control['nombre']?>" value="Componente Proped&eacute;utico" disabled=true<?
                        }
                        else
                            {
                            ?><input type="text" size="2" name="<?echo $control['nombre']?>" value="<?echo $this->datosEspacio[$control['valor']]?>"<?
                            }
                       //El control numero de creditos es de solo lectura  para un plan de estudios cuando exista mas de
                       //un proyecto que contiene el espacio
                       if(($control['nombre']=='nroCreditos' OR $control['nombre']=='hta' ) AND $numeroProyectos>1){
                        echo 'readonly';
                       }
                       ?>
                       >

            </td>
        <?
    }

    /**
     *presenta boton submit
     *
     * @param type $opcion
     * @param type $tipoFormulario modificacion para uno o vario planes
     */
    function presentarBotonEnviar($opcion) {
        ?>
            <input type="hidden" name="opcion" value="<?echo $opcion?>">
            <input type="hidden" name="action" value="<?echo $this->formulario?>">
            <input type="hidden" name="codEspacio" value="<?echo $this->datosEspacio['codEspacio']?>">
            <?
            if ($opcion!='modificarNombre')
            {
                ?>
                    <input type="hidden" name="nombreEspacio" value="<?echo $this->datosEspacio['nombreEspacio']?>">
                <?
            }
            if ($opcion!='modificarClasificacion' && $opcion!='confirmar')
            {
                ?>
                    <input type="hidden" name="clasificacion" value="<?echo $this->datosEspacio['clasificacion']?>">
                <?
            }
            ?>
            <input type="hidden" name="planEstudio" value="<?echo $this->datosEspacio['planEstudio']?>">
            <input type="hidden" name="semanas" value="<?echo $this->datosEspacio['semanas']?>">
            <input type="hidden" name="codProyecto" value="<?echo $this->datosEspacio['codProyecto']?>">
            <input type="submit" value="Modificar" >
        <?
    }


    /**
     * Funcion que presenta la pagina para solicitar confirmacion de la modificacion
     */
    function solicitarConfirmacion() {
        $this->crearArregloDatosEspacio();
        ?>
        <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
        <?
          $this->celdaMensajes();
          $this->formularioConfirmacion();
        ?>
        </table>
        <?
    }

    /**
     * Funcion que genera la celda donde se colocan mensajes de confirmacion de edicion del espacio
     */
    function celdaMensajes() {
      ?>
          <tr class="texto_subtitulo">
              <td colspan="2" align="center"><?
              $this->mensajesConfirmacion();
              ?></td>
          </tr>
      <?
    }

    /**
     * Funcion que genera los mensajes de confirmacion de edicion del espacio
     */
    function mensajesConfirmacion() {
        if (isset($this->datosEspacio['mensaje']))
          {
          echo $this->datosEspacio['mensaje']."<br><br>";
          }
          else
            {
            }
    }

    /**
     * Funcion que genera el formulario para confirmar o cancelar la edicion de datos del espacio
     */
    function formularioConfirmacion() {
      ?>
          <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <tr class="texto_subtitulo">
              <td align="center">
                  <?
                    $this->datosEspacio['opcion']=$this->datosEspacio['opcionConfirmado'];
                    $this->datosEspacio['action']=$this->formulario;
                    $cancelar=$this->generarRetorno($this->datosEspacio);
                    $array=array(icono=>'clean.png',texto=>'Si');
                    $this->botonEnlace($cancelar,$array)
                  ?>
              </td>
              <td align="center">
                  <?
                    $this->datosEspacio['opcion']='cancelar';
                    $cancelar=$this->generarRetorno($this->datosEspacio);
                    $array=array(icono=>'x.png',texto=>'No');
                    $this->botonEnlace($cancelar,$array)
                  ?>
              </td>
            </tr>
          </form>
      <?
    }

    /**
     * Funcion que genera botones de enlace
     * @param <type> $retorno
     * @param <type> $array
     */
    function botonEnlace($retorno,$array) {
      ?>
        <a href="<?echo $retorno['pagina'].$retorno['variable']?>" >
          <img src="<? echo $this->configuracion["site"] . $this->configuracion["grafico"] . "/".$array['icono']; ?>" border="0" width="25" height="25"><br><?echo $array['texto'];?>
        </a>
      <?
    }

    /**
     * Funcion que realiza verificacion de datos para modificar datos de espacio en un plan
     */
    function verificarEspacioPlan() {
      $this->crearArregloDatosEspacio();
      $this->verificarCreditosDistribucionHoras();
      $this->verificarNivelClasificacion();
      $this->realizarVerificaciones();
      $this->guardarDatosEspacio();
    }

    /**
     * Funcion que realiza verificacion de datos para modificar nombre del espacio en todos los planes
     */
    function verificarNombreEspacio() {
      $this->crearArregloDatosEspacio();
      $this->realizarVerificaciones();
      $this->modificarNombreEspacio();
    }

    /**
     * Funcion que realiza verificacion de datos para modificar los creditos y distribucion del espacio en todos los planes
     */
    function verificarCreditosEspacio() {
      $this->crearArregloDatosEspacio();
      $this->verificarCreditosDistribucionHoras();
      $this->realizarVerificaciones();
      $this->modificarCreditosEspacio();
    }

    /**
     * Funcion que realiza verificacion de datos para modificar la clasificacion del espacio en todos los planes
     */
    function verificarClasificacionEspacio() {
      $this->crearArregloDatosEspacio();
      $this->datosEspacio['clasificacion']=$this->datosEspacio['clasificacionTodos'];
      $this->realizarVerificaciones();
      $this->modificarClasificacionEspacioAcademico();
    }

    /**
     * Funcion que realiza verificacion de datos para modificar el nivel del espacio en todos los planes
     */
    function verificarNivelEspacio() {
      $this->crearArregloDatosEspacio();
      $this->datosEspacio['nivel']=$this->datosEspacio['nivelTodos'];
      $this->verificarNivelClasificacion();
      $this->realizarVerificaciones();
      $this->modificarNivelEspacio();
    }

    /**
     * Funcion que permite crear el arreglo de los datos del espacio.
     */
    function crearArregloDatosEspacio() {
      foreach ($_REQUEST as $key => $value)
        {
          $this->datosEspacio[$key]=$value;
        }
    }

    /**
     * Funcion que realiza verificaciones para editar el espacio
     */
    function realizarVerificaciones() {
        $modificarEspacioNotas=$this->buscarNotasEspacio($this->datosEspacio['codEspacio']);
        $msjError="";
        if(!is_array($modificarEspacioNotas)||$modificarEspacioNotas[0][0]>0)
            {
                $porNotas='1';
                $msjError.=" - Notas";
            }else
                {
                    $porNotas='0';
                }
        $modificarEspacioInscripcion=$this->buscarInscripcionesEspacio($this->datosEspacio['codEspacio']);
        if(!is_array($modificarEspacioInscripcion)||$modificarEspacioInscripcion[0][0]>0)
            {
                $porInscripcion='1';
                $msjError.=" - Inscripciones";
            }else
                {
                    $porInscripcion='0';
                }
        $modificarEspacioHorario=$this->buscarHorariosEspacio($this->datosEspacio['codEspacio']);
        if(!is_array($modificarEspacioHorario)||$modificarEspacioHorario[0][0]>0)
            {
                $porHorario='1';
                $msjError.=" - Horarios";
            }else
                {
                    $porHorario='0';
                }
        if($porNotas=='1' || $porInscripcion=='1' || $porHorario=='1')
        {
          switch ($this->datosEspacio['opcion'])
          {
            case 'confirmar':
              $this->datosEspacio['opcionConfirmado']='confirmadoEspacio';
              break;

            case 'modificarNombre':
              $this->datosEspacio['opcionConfirmado']='confirmadoNombre';
              break;

            case 'modificarCreditos':
              $this->datosEspacio['opcionConfirmado']='confirmadoCreditos';
              break;

            case 'modificarClasificacion':
              $this->datosEspacio['opcionConfirmado']='confirmadoClasificacion';
              break;

            case 'modificarNivel':
              $this->datosEspacio['opcionConfirmado']='confirmadoNivel';
              break;
          }
            $this->datosEspacio['mensaje']='El espacio académico tiene registros de '.$msjError.'. ¿Desea Modificarlo?';
            $this->datosEspacio['pagina']=$this->formulario;
            $this->datosEspacio['opcion']="solicitarConfirmacion";
            unset ($this->datosEspacio['action']);
            $retorno=$this->generarRetorno($this->datosEspacio);
            $this->retornar($retorno);
            exit;
        }else
            {
                
            }
     }

     /**
      * Funcion que presenta el listado de planes de estudios a los que esta asociado un espacio academico
      * @param <type> $resultado_proyectos 
      */
     function generarListadoPlanesAsociados($resultado_proyectos) {
        ?>
        <table class="contenidotabla centrar">
            <th colspan="11" class="cuadro_plano centrar">PLANES A LOS QUE EST&Aacute; ASOCIADO EL ESPACIO ACAD&Eacute;MICO</th>
            <?
                for($i=0;$i<count($resultado_proyectos);$i++)
                {
                    if($resultado_proyectos[$i]['FACULTAD']!=$resultado_proyectos[$i-1]['FACULTAD'])
                        {
                           ?>
                                <tr>
                                    <td colspan="11" class="cuadro_brownOscuro centrar">
                                        <font size="2"><b><?echo $resultado_proyectos[$i]['NOMBRE_FACULTAD']?></b></font>
                                    </td>
                                </tr>
                                <tr>
                                  <?  foreach ($resultado_proyectos[$i] as $key => $value) {
                                    if (!is_numeric($key)){
                                    ?><td class="cuadro_plano centrar"><font size="1"><b><?echo $key?></b></font></td><?
                                    if($key=='ESTADO')
                                    {break;}
                                    }
                                           }?>
                                </tr>

                            <?
                        }
                     ?>
                                <tr>
                                  <?  foreach ($resultado_proyectos[$i] as $key => $value) {
                                    if (!is_numeric($key)){
                                    ?><td class="cuadro_plano centrar"><font size="1"><b><?echo $value?></b></font></td><?
                                    if($key=='ESTADO')
                                    {break;}
                                    }
                                   }?>
                                </tr>

                        <?
                }
                ?>
                </table>
                <?
     }

     /**
      * Funcion que verifica que la dsitribucion horaria corresponda con el numero de creditos y las semanas del espacio
      */
     function verificarCreditosDistribucionHoras() {
         if($this->datosEspacio['planEstudio']==261||$this->datosEspacio['planEstudio']==262||$this->datosEspacio['planEstudio']==263||$this->datosEspacio['planEstudio']==269)
            {

            }else
                {
        if((48*$this->datosEspacio['nroCreditos'])!=(($this->datosEspacio['htd']+$this->datosEspacio['htc']+$this->datosEspacio['hta'])*$this->datosEspacio['semanas']))
            {
                $mensajeRetorno="La distribuci\u00F3n de horas no concuerda con el n\u00FAmero de cr\u00E9ditos. Por favor verifique los datos.";
                $this->datosEspacio['pagina']=$this->formulario;
                $this->datosEspacio['opcion']="modificarEspacio";
                unset ($this->datosEspacio['action']);
                echo "<script>alert('".$mensajeRetorno."')</script>";
                $retorno=$this->generarRetorno($this->datosEspacio);
                $this->retornar($retorno);
            }else
              {
              }
     }
     }

     /**
      * Funcion que genera las variables para el retorno
      * @param array $datosRetorno
      * @return array
      */
     function generarRetorno($datosRetorno) {
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variables="pagina=".$datosRetorno['pagina'];
        unset ($datosRetorno['pagina']);
        foreach ($datosRetorno as $key => $value) {
          $variables.="&".$key."=".$datosRetorno[$key];
        }
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variables=$this->cripto->codificar_url($variables,$this->configuracion);
        $retorno['pagina']=$pagina;
        $retorno['variable']=$variables;
        return $retorno;
     }

     /**
      * Funcion que realiza el redireccionamiento de la pagina
      * @param <type> $retorno 
      */
     function retornar($retorno) {
        echo "<script>location.replace('".$retorno['pagina'].$retorno['variable']."')</script>";
     }

     /**
      * Funcion que guarda los datos modificados del espacio academico para un plan de estudios
      */
     function guardarDatosEspacio()
      {
        $this->crearArregloDatosEspacio();
        $this->verificarCreditosDistribucionHoras();
        $resultadoPeriodo=$this->consultarPeriodoActivo();
        $ano=$resultadoPeriodo[0][0];
        $periodo=$resultadoPeriodo[0][1];

        if($resultadoPeriodo==true)
        {
            if($this->datosEspacio['clasificacion']==3||$this->datosEspacio['clasificacion']==4)
            {
              $this->datosEspacio['electiva']='S';
            }
            else
            {
              $this->datosEspacio['electiva']='N';
            }
            if ($this->datosEspacio['clasificacion']==4)
            {
              $this->datosEspacio['nivel']=0;
            }
            if ($this->datosEspacio['clasificacion']==5)
            {
              $this->datosEspacio['nivel']=98;
            }
            $resultado_espacioacpen=$this->buscarEspacioPlanEstudio();
            if(is_array($resultado_espacioacpen)&&$resultado_espacioacpen[0][0]>0)
                {
                    $modificarEspacio=$this->modificarDatosEspacioPlan();
                    if ($modificarEspacio>0)
                    {
                        $modificarClasificacion=$this->modificarClasificacionPlan();
                        $modificarPlanEspacio=$this->modificarDatosPlanEspacio();
                        $planes=$this->consultarPlanesEspacio();
                        if (count($planes)==1)
                            {
                                $modificarDatosEspacio=$this->modificarDatosEspacioAcademico();
                            }
                    }
                    else{
                        $modificarPlanEspacio=$this->modificarDatosPlanEspacio();
                        $planes=$this->consultarPlanesEspacio();
                        if (count($planes)==1)
                            {
                                $modificarDatosEspacio=$this->modificarDatosEspacioAcademico();
                            }
                    }
                    $datosRegistro=array(usuario=>$this->usuario,
                                        evento=>'19',
                                        descripcion=>'Modifica Espacio en Plan Asesor',
                                        registro=>$ano.'-'.$periodo.', '.$this->datosEspacio['codEspacio'].', 0, 0, '.$this->datosEspacio['planEstudio'],
                                        afectado=>$this->datosEspacio['planEstudio']);

                    $this->procedimientos->registrarEvento($datosRegistro);
                    $this->datosEspacio['pagina']="adminAprobarEspacioPlan";
                    $this->datosEspacio['opcion']="mostrar";
                    unset ($this->datosEspacio['action']);
                    echo "<script>alert('El Espacio Acad\u00E9mico ".$this->datosEspacio['nombreEspacio']." se ha modificado para el plan de estudios ".$this->datosEspacio['planEstudio'].".')</script>";
                    $retorno=$this->generarRetorno($this->datosEspacio);
                    $this->retornar($retorno);
                }else
                    {
                        $this->errorConexion();
                    }
        }else
            {
                $this->errorConexion();
            }
    }

    /**
     * Funcion que presenta el encabezado en el modulo de modificar espacio
     * @param <type> $variable 
     */
    function encabezadoModulo($variable)
    {
      ?>
      <table class="contenidotabla">
        <tr>
          <td class="centrar" colspan="3">
            <b>SISTEMA DE GESTION ACAD&Eacute;MICA</b>
          </td>
        </tr>
        <tr>
          <td class="centrar" colspan="3">
            PLAN DE ESTUDIOS <?echo $variable['planEstudio']?>
          </td>
        </tr>
        <tr>
          <td class="centrar" colspan="3">
            <?
              $variable['pagina']="adminAprobarEspacioPlan";
              $variable['opcion']="mostrar";
              $retorno=$this->generarRetorno($variable);
            ?>
            <a href="<?echo $retorno['pagina'].$retorno['variable']?>">
              <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
            <hr>
          </td>
        </tr>
      </table>
      <?
    }

    /**
     * funcion que permite modificar el nombre de un espacio academico para todos los planes de estudio
     */
    function modificarNombreEspacio() {
        $this->crearArregloDatosEspacio();
        $resultadoPeriodo=$this->consultarPeriodoActivo();
        $ano=$resultadoPeriodo[0][0];
        $periodo=$resultadoPeriodo[0][1];

        $espacio=$this->buscarEspacio();
        if (is_array($espacio)&&$espacio[0][0]>0)
        {
            $this->actualizarNombreEspacio();
            $this->actualizarNombreAcasi();
        }else
          {
            $this->errorConexion();
          }

        $planOriginal=$this->datosEspacio['planEstudio'];
        $resultado_proyectos=$this->consultarPlanesEspacio();
        foreach ($resultado_proyectos as $key => $value) {
            $this->datosEspacio['planEstudio']=$value['PLAN'];

            $datosRegistro=array(usuario=>$this->usuario,
                                evento=>'19',
                                descripcion=>'Modifica Nombre Espacio Asesor',
                                registro=>$ano.'-'.$periodo.', '.$this->datosEspacio['codEspacio'].', 0, 0, '.$this->datosEspacio['planEstudio'],
                                afectado=>$this->datosEspacio['planEstudio']);

            $this->procedimientos->registrarEvento($datosRegistro);
        }
        $this->datosEspacio['planEstudio']=$planOriginal;
        
        $this->datosEspacio['pagina']="adminAprobarEspacioPlan";
        $this->datosEspacio['opcion']="mostrar";
        unset ($this->datosEspacio['action']);
        echo "<script>alert('El Espacio Acad\u00E9mico ".$this->datosEspacio['nombreEspacio']." se ha modificado')</script>";
        $retorno=$this->generarRetorno($this->datosEspacio);
        $this->retornar($retorno);
    }

    /**
     * funcion que permite modificar el numero de creditos y distribucion horaria de un espacio academico para todos los planes de estudio
     */
    function modificarCreditosEspacio() {
        $this->crearArregloDatosEspacio();
        $resultadoPeriodo=$this->consultarPeriodoActivo();
        $ano=$resultadoPeriodo[0][0];
        $periodo=$resultadoPeriodo[0][1];

        $espacio=$this->buscarEspacio();
        if (is_array($espacio)&&$espacio[0][0]>0)
        {
            $this->actualizarCreditosEspacio();
            $creditos=$this->actualizarCreditosPlanEspacio();
            $this->actualizarCreditosAcpen();
        }else
          {
            $this->errorConexion();
          }

        $planOriginal=$this->datosEspacio['planEstudio'];
        $resultado_proyectos=$this->consultarPlanesEspacio();
        foreach ($resultado_proyectos as $key => $value) {
            $this->datosEspacio['planEstudio']=$value['PLAN'];

            $datosRegistro=array(usuario=>$this->usuario,
                                evento=>'19',
                                descripcion=>'Modifica Creditos Espacio Asesor',
                                registro=>$ano.'-'.$periodo.', '.$this->datosEspacio['codEspacio'].', 0, 0, '.$this->datosEspacio['planEstudio'],
                                afectado=>$this->datosEspacio['planEstudio']);

            $this->procedimientos->registrarEvento($datosRegistro);
        }
        $this->datosEspacio['planEstudio']=$planOriginal;

        $this->datosEspacio['pagina']="adminAprobarEspacioPlan";
        $this->datosEspacio['opcion']="mostrar";
        unset ($this->datosEspacio['action']);
        echo "<script>alert('Los cr\u00E9ditos y distribuci\u00F3n horaria del Espacio Acad\u00E9mico ".$this->datosEspacio['nombreEspacio']." se han modificado')</script>";
        $retorno=$this->generarRetorno($this->datosEspacio);
        $this->retornar($retorno);
    }

    /**
     * funcion que permite modificar el nivel de un espacio academico para todos los planes de estudio
     */
    function modificarNivelEspacio() {
        $this->crearArregloDatosEspacio();
        $resultadoPeriodo=$this->consultarPeriodoActivo();
        $ano=$resultadoPeriodo[0][0];
        $periodo=$resultadoPeriodo[0][1];

        $espacio=$this->buscarEspacio();
        if (is_array($espacio)&&$espacio[0][0]>0)
        {
            $this->modificarNivelPlanEspacio();
            $this->modificarNivelAcpen();
        }else
          {
            $this->errorConexion();
          }

        $planOriginal=$this->datosEspacio['planEstudio'];
        $resultado_proyectos=$this->consultarPlanesEspacio();
        foreach ($resultado_proyectos as $key => $value) {
            $this->datosEspacio['planEstudio']=$value['PLAN'];

            $datosRegistro=array(usuario=>$this->usuario,
                                evento=>'19',
                                descripcion=>'Modifica Nivel Espacio Asesor',
                                registro=>$ano.'-'.$periodo.', '.$this->datosEspacio['codEspacio'].', 0, 0, '.$this->datosEspacio['planEstudio'],
                                afectado=>$this->datosEspacio['planEstudio']);

            $this->procedimientos->registrarEvento($datosRegistro);
        }
        $this->datosEspacio['planEstudio']=$planOriginal;

        $this->datosEspacio['pagina']="adminAprobarEspacioPlan";
        $this->datosEspacio['opcion']="mostrar";
        unset ($this->datosEspacio['action']);
        echo "<script>alert('El nivel del Espacio Acad\u00E9mico ".$this->datosEspacio['nombreEspacio']." se ha modificado')</script>";
        $retorno=$this->generarRetorno($this->datosEspacio);
        $this->retornar($retorno);
    }

    /**
     * Funcion que permite modificar la clasificacion de un espacio academico para todos los planes de estudio.
     * Cuando la clasificacion del espacio es electivo extrinseco, se modifica el nivel a cero.
     * Cuando la clasificacion del espacio es componente propedeutico, el nivel es noventa y ocho.
     */
    function modificarClasificacionEspacioAcademico() {
        $this->crearArregloDatosEspacio();
        $resultadoPeriodo=$this->consultarPeriodoActivo();
        $ano=$resultadoPeriodo[0][0];
        $periodo=$resultadoPeriodo[0][1];

        $espacio=$this->buscarEspacio();
        if (is_array($espacio)&&$espacio[0][0]>0)
        {
            $this->modificarClasificacion();
            $this->modificarClasificacionPlanEspacio();
            if($this->datosEspacio['clasificacionTodos']==3||$this->datosEspacio['clasificacionTodos']==4)
            {
              $this->datosEspacio['electiva']='S';
            }
            else
            {
              $this->datosEspacio['electiva']='N';
            }
            $this->modificarClasificacionAcpen();

            if ($this->datosEspacio['clasificacionTodos']==4)
                {
                    $this->datosEspacio['nivel']=0;
                    $this->modificarNivelPlanEspacio();
                    $this->modificarNivelAcpen();
                }
            elseif ($this->datosEspacio['clasificacionTodos']==5)
                {
                    $this->datosEspacio['nivel']=98;
                    $this->modificarNivelPlanEspacio();
                    $this->modificarNivelAcpen();
                }
        }else
          {
            $this->errorConexion();
          }

        $planOriginal=$this->datosEspacio['planEstudio'];
        $resultado_proyectos=$this->consultarPlanesEspacio();
        foreach ($resultado_proyectos as $key => $value) {
            $this->datosEspacio['planEstudio']=$value['PLAN'];

            $datosRegistro=array(usuario=>$this->usuario,
                                evento=>'19',
                                descripcion=>'Modifica Clasificacion Espacio Asesor',
                                registro=>$ano.'-'.$periodo.', '.$this->datosEspacio['codEspacio'].', 0, 0, '.$this->datosEspacio['planEstudio'],
                                afectado=>$this->datosEspacio['planEstudio']);
            $this->procedimientos->registrarEvento($datosRegistro);
        }
        $this->datosEspacio['planEstudio']=$planOriginal;

        $this->datosEspacio['pagina']="adminAprobarEspacioPlan";
        $this->datosEspacio['opcion']="mostrar";
        unset ($this->datosEspacio['action']);
        echo "<script>alert('La clasificaci\u00F3n del Espacio Acad\u00E9mico ".$this->datosEspacio['nombreEspacio']." se ha modificado')</script>";
        $retorno=$this->generarRetorno($this->datosEspacio);
        $this->retornar($retorno);
    }

    /**
     * Funcion que presenta mensaje de error cuando no se puede conectar a Oracle
     */
    function errorConexion() {
        $this->datosEspacio['pagina']="adminAprobarEspacioPlan";
        $this->datosEspacio['opcion']="mostrar";
        unset ($this->datosEspacio['action']);
        echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde')</script>";
        $retorno=$this->generarRetorno($this->datosEspacio);
        $this->retornar($retorno);
        exit;
    }

    /**
     * Funcion que permite verificar si el nivel ingresado para el espacio corresponde con la clasificacion
     * Para espacios electivos extrinsecos el nivel es cero
     * Para espacios componente propedeutico el nivel es noventa y ocho
     */
    function verificarNivelClasificacion() {
      if ($this->datosEspacio['clasificacion']!=4 && $this->datosEspacio['clasificacion']!=5)
      {
        if ($this->datosEspacio['nivel']==0 || $this->datosEspacio['nivel']==98)
            {
                $mensajeRetorno="Por favor ingrese el nivel al que corresponde el espacio acad\u00E9mico.";
                $this->datosEspacio['pagina']=$this->formulario;
                $this->datosEspacio['opcion']="modificarEspacio";
                unset ($this->datosEspacio['action']);
                echo "<script>alert('".$mensajeRetorno."')</script>";
                $retorno=$this->generarRetorno($this->datosEspacio);
                $this->retornar($retorno);
            }
      }
    }

    /**
     * Funcion que presenta el boton cancelar para volver al plan de estudios
     */
    function mostrarBotonCancelar() {
        ?>
          <table class="centrar" width="100%">
              <tr>
                <form name="<?echo $this->formulario?>" action="index.php" method="POST">
                    <td class="centrar">
                        <input type="hidden" name="opcion" value="cancelar">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="hidden" name="codProyecto" value="<?echo $this->datosEspacio['codProyecto']?>">
                        <input type="hidden" name="planEstudio" value="<?echo $this->datosEspacio['planEstudio']?>">
                        <input type="hidden" name="semanas" value="<?echo $this->datosEspacio['semanas']?>">
                        <input type="submit" value="Cancelar">
                        <hr>
                    </td>
                </form>
              </tr>
          </table>
        <?
    }

     /**
      * Funcion que consulta las clasificaciones de espacio existentes
      * @return <type> 
      */
     function buscarClasificacion() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"clasificacion","");
        return $resultado_clasificacion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
      }

      /**
       * Funcion que consulta los planes de estudio a los que esta asociado un espacio
       * @return <type> 
       */
      function consultarPlanesEspacio() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarPlanesDeEspacio",$this->datosEspacio);
        return $resultado_proyectos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
      }

      /**
       * Funcion que consulta las notas que haya registradas para un espacio academico
       * @param <type> $codEspacio
       * @return <type> 
       */
      function buscarNotasEspacio($codEspacio) {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"modificarEspacio_notas",$codEspacio);
        return $modificarEspacioNotas=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
      }

      /**
       * Funcion que consulta las inscripciones que haya registradas para un espacio academico
       * @param <type> $codEspacio
       * @return <type> 
       */
      function buscarInscripcionesEspacio($codEspacio) {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"modificarEspacio_inscripcion",$codEspacio);
        return $modificarEspacioInscripcion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
      }

      /**
       * Funcion que consulta los horarios que haya registrados para un espacio academico
       * @param <type> $codEspacio
       * @return <type> 
       */
      function buscarHorariosEspacio($codEspacio) {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"modificarEspacio_horario",$codEspacio);
        return $modificarEspacioHorario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
      }

      /**
       * Funcion que busca un espacio academico en un plan de estudios
       * @return <type> 
       */
      function buscarEspacioPlanEstudio() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarEspacioPlanEstudio",$this->datosEspacio);
        return $resultado_espacioacpen=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
      }

     /**
      *
      * @return <type> Funcion que consulta los datos del espacio academico
      */
      function buscarDatosEspacio() {

       $variable=array(codEspacio=>  $_REQUEST['codEspacio'],
                       planEstudio=>$_REQUEST['planEstudio']);
        $cadena_sql=$this->sql->cadena_sql($this->configuracion, "buscarDatosEspacio",$variable);
        $resultado_datosEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        return $resultado_datosEspacio[0];
     }

      /**
       * Funcion que permite modificar los datos del espacio en ACPEN
       * @return <type> 
       */
      function modificarDatosEspacioPlan() {
          $cadena_sql=$this->sql->cadena_sql($this->configuracion,"modificarDatosEspacioAcpen",$this->datosEspacio);
          $resultado_actualizaracpen=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
          return $this->totalAfectados($this->configuracion, $this->accesoOracle);
      }

      /**
       * Funcion que permite modificar la clasificacion del espacio en Oracle para un plan de estudios
       * @return <type> 
       */
      function modificarClasificacionPlan() {
          $cadena_sql=$this->sql->cadena_sql($this->configuracion,'modificarClasificacionPlan',$this->datosEspacio);
          $registroClasificacion = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
          return $this->totalAfectados($this->configuracion, $this->accesoOracle);
      }

      /**
       * Funcion que permite modificar la clasificacion del espacio en Oracle para todos los planes de estudios
       * @return <type>
       */
      function modificarClasificacion() {
          $cadena_sql=$this->sql->cadena_sql($this->configuracion,'modificarClasificacion',$this->datosEspacio);
          $registroClasificacion = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
          return $this->totalAfectados($this->configuracion, $this->accesoOracle);
      }

      /**
       * Funcion que permite modificar la clasificaion del espacio en planEstudio_espacio en todos los planes de estudio
       * @return <type>
       */
      function modificarClasificacionPlanEspacio() {
          $cadena_sql=$this->sql->cadena_sql($this->configuracion,"modificarClasificacionPlanEspacio",$this->datosEspacio);
          $resultado_planEstudio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
          return $this->totalAfectados($this->configuracion, $this->accesoGestion);
      }

      /**
       * Funcion que permite modificar los datos del espacio en planEstudio_espacio
       * @return <type> 
       */
      function modificarDatosPlanEspacio() {
          $cadena_sql=$this->sql->cadena_sql($this->configuracion,"modificarDatosPlanEspacio",$this->datosEspacio);
          $resultado_planEstudio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
          return $this->totalAfectados($this->configuracion, $this->accesoGestion);
      }

      /**
       * Funcion que permite modificar los datos del espacio en espacio_academico
       * @return <type>
       */
      function modificarDatosEspacioAcademico() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"actualizar_espacioAcademico",$this->datosEspacio);
        $resultado_espacioAcad=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);
      }

      /**
       * Funcion que permite modificar la distribucion horaria del espacio en planEstudio_espacio para todos los planes de estudios
       * @return <type>
       */
      function actualizarCreditosPlanEspacio() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"actualizarCreditosPlanEspacio",$this->datosEspacio);
        $resultado_espacioAcad=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);
      }

      /**
       * Funcion que permite cambiar el nombre del espacio en ORACLE
       * @return <type>
       */
      function actualizarNombreAcasi() {
        $this->datosEspacio['nombreEspacio']=strtr(strtoupper($this->datosEspacio['nombreEspacio']), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"actualizarNombreAcasi",$this->datosEspacio);
        $resultado_actualizarNombreacasi=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
      }

      /**
       * Funcion que permite cambiar el nombre del espacio en espacio_academico
       * @return <type> 
       */
      function actualizarNombreEspacio() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"actualizarNombreEspacio",$this->datosEspacio);
        $resultado_actualizarNombreEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);
      }

      /**
       * Funcion que permite cambiar la clasificacion del espacio en acpen en todos los planes de estudios
       * @return <type>
       */
      function modificarClasificacionAcpen() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"modificarClasificacionAcpen",$this->datosEspacio);
        $resultado_actualizarClasificacion=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
      }

      /**
       * Funcion que permite cambiar el numero de creditos y distribucion horaria
       * del espacio en ORACLE en todos los planes de estudios
       * @return <type>
       */
      function actualizarCreditosAcpen() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"actualizarCreditosAcpen",$this->datosEspacio);
        $resultado_actualizarCreditos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
      }

      /**
       * Funcion que permite cambiar el numero de creditos y distribucion horaria
       * del espacio en espacio_academico en todos los planes de estudios
       * @return <type>
       */
      function actualizarCreditosEspacio() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"actualizarCreditosEspacio",$this->datosEspacio);
        $resultado_actualizarCreditos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);
      }

      /**
       * Funcion que permite cambiar el nivel del espacio en ORACLE en todos los planes de estudios
       * @return <type>
       */
      function modificarNivelAcpen() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"modificarNivelAcpen",$this->datosEspacio);
        $resultado_actualizarCreditos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
      }

      /**
       * Funcion que actualiza en oracle un espacio asociado a un nombre general
       * @param <type> $datosEspacio
       * @return <type> 
       */
      function actualizarEspacioAsociadoOracle($datosEspacio) {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"modificarNivelEspacioAsociadoAcpen",$datosEspacio);
        $resultado_actualizarCreditos=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
      }

      /**
       * Funcion que permite cambiar el nivel del espacio en planEstudio_espacio en todos los planes de estudios
       * @return <type>
       */
      function modificarNivelPlanEspacio() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"modificarNivelPlanEspacio",$this->datosEspacio);
        $resultado_actualizarCreditos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);
      }

      /**
       * Funcion que permite consultar el periodo academico activo
       * @return <type> 
       */
      function consultarPeriodoActivo() {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"bimestreActual",'');
        return $resultadoPeriodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
      }

      /**
       * Funcion que buscar si un espacio esta registrado en oracle para un plan de estudios
       * @param <type> $datosEspacio
       * @return <type> 
       */
      function buscarEspacioEnPlanOracle($datosEspacio) {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"buscarEspacioEnPlanOracle",$datosEspacio);
        return $resultadoPeriodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
      }

      /**
       * Funcion que permite consultar si un espacio existe en el sistema
       * @param <type> $codEspacio
       * @return <type> 
       */
      function buscarEspacio(){
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"consultarEspacio",$this->datosEspacio);
        return $resultado_espacio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

      }

      /**
       * Funcion que consulta los espacios asociados a un nombre general
       * @param <type> $datosEncabezado
       * @return <type> 
       */
      function buscarEspaciosAsociadosEncabezado($datosEncabezado) {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"buscarEspaciosAsociadosEncabezado",$datosEncabezado);
        return $resultado_asociacion=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
      }

      /**
       * Funcion que actualiza en MySQL un espacio asociado a un nombre general
       * @param <type> $datosEspacioAsociado
       * @return <type> 
       */
      function actualizarEspacioAsociado($datosEspacioAsociado) {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"modificarNivelEspacioAsociadoPlanEspacio",$datosEspacioAsociado);
        $resultado_actualizarCreditos=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);

      }


//******************    OLD    *************************

    /*****************************************************
     * Las siguientes lineas de codigo modifica espacios académicos
     * electivos extrinsecos como sugerencias del plan de estudios
     *****************************************************/

     function formularioModificarAsisViceEncabezado()
    {

        $id_encabezado=$_REQUEST['id_encabezado'];
        $planEstudio=$_REQUEST['planEstudio'];
        $clasificacion=$_REQUEST['clasificacion'];
        $encabezado_nombre=$_REQUEST['encabezado_nombre'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nivel=$_REQUEST['nivel'];
        $codProyecto=$_REQUEST['codProyecto'];
        $id_encabezado=$_REQUEST['id_encabezado'];

        $resultado_clasificacion=$this->buscarClasificacion();
        $resultado_asociacion=$this->buscarEspaciosAsociadosEncabezado($_REQUEST);
        if(is_array($resultado_asociacion)&&$resultado_asociacion[0]['COD_PLAN']==$planEstudio)
        {$asociado=1;}
        else{$asociado=0;}
        

       ?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
    <table class="contenidotabla centrar" width="100%" border="0">
            <tr>
                <td class="cuador_color centrar" colspan="4">
                    <font size="2">Modificar el Nombre General <?echo $encabezado_nombre?></font>
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%" ><font size="2">Plan de Estudio:</font></td><td class="cuadro_plano" colspan="3"><font size="2"><?echo $planEstudio?></font></td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%"><font size="2">Nombre del Espacio Acad&eacute;mico:</font></td><td class="cuadro_plano" colspan="3"><input type="text" name="encabezado_nombre" value="<?echo $encabezado_nombre?>" size="45"></td>
            </tr>
<?if($asociado==1){?>
            <tr>
                <td class="cuadro_plano" width="30%">
                  <font size="2">Tipo de clasificaci&oacute;n:</font>
                </td>
                <td class="cuadro_plano" colspan="3" >
                        <?
                            for($i=0;$i<count($resultado_clasificacion);$i++)
                            {
                                if($resultado_clasificacion[$i]['CODIGO_CLASIFICACION']==$clasificacion)
                                    {
                                        ?>
                                            <?echo $resultado_clasificacion[$i]['NOMBRE_CLASIFICACION']?>
                                            <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                                        <?
                                    }else{}
                            }
                            ?>
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%">
                  <font size="2">N&uacute;mero de Cr&eacute;ditos:</font>
                </td>
                <td class="cuadro_plano" colspan="3"><?echo $nroCreditos;?>
                  <input type="hidden" name="nroCreditos" value="<?echo $nroCreditos?>">
                </td>
            </tr>
                <?}else{?>
            <tr>
                <td class="cuadro_plano" width="30%"><font size="2">Tipo de clasificaci&oacute;n:</font></td>
                <td class="cuadro_plano" colspan="3" >
                    <select id="clasificacion" name="clasificacion">
                        <?for($i=0;$i<count($resultado_clasificacion);$i++) {?>
                          <option value="<?echo $resultado_clasificacion[$i]['CODIGO_CLASIFICACION']?>"<?if ($clasificacion==$resultado_clasificacion[$i]['CODIGO_CLASIFICACION']){?>selected<?}?>><?echo $resultado_clasificacion[$i]['NOMBRE_CLASIFICACION'];?></option>
                        <?}?>
                    </select>
            </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%"><font size="2">N&uacute;mero de Cr&eacute;ditos:</font></td><td class="cuadro_plano" colspan="3"><input type="text" name="nroCreditos" value="<?echo $nroCreditos?>"></td>
            </tr>
            <?}?>
            <tr>
                <td class="cuadro_plano" width="30%"><font size="2">Nivel:</font></td><td class="cuadro_plano" colspan="3"><input type="text" name="nivel" value="<?echo $nivel?>"></td>
            </tr>
<?if($asociado==1){?>
            <tr>
                <td class="cuadro_color_plano centrar" colspan="4"><br><font size="2"><b>Este cambio afectar&aacute; a los espacios asociados a <?echo $encabezado_nombre?></font></b></td>
            </tr>
            <tr>
                <td class="cuadro_color_plano centrar" colspan="4">
                    <table class="contenidotabla centrar" width="100%">
                                <tr>
                                  <?  foreach ($resultado_asociacion[0] as $key => $value) {
                                    if (!is_numeric($key)){
                                    ?><td class="cuadro_brownOscuro centrar"><font size="1"><b><?echo $key?></b></font></td><?
                                    if($key=='ESTADO')
                                    {break;}
                                    }
                                           }?>
                                </tr>

                            <?
                                           foreach ($resultado_asociacion as $fila => $columna) {
                     ?>
                                <tr>
                                  <?  foreach ($columna as $key => $value) {
                                    if (!is_numeric($key)){
                                    ?><td class="cuadro_plano centrar"><font size="1"><b><?echo $value?></b></font></td><?
                                    if($key=='ESTADO')
                                    {break;}
                                    }
                                   }?>
                                </tr>
                                <?}?>
                    </table>
                </td>
            </tr>
            <?}?>
            <tr>
                <td class="cuadro_color_plano centrar" colspan="4"><br><font size="2">¿Desea guardar la informaci&oacute;n anteriormente diligenciada?</font></td>
            </tr>
            <tr>
                <td colspan="2" class="centrar" width="50%"><br>

                    <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                    <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                    <input type="hidden" name="id_encabezado" value="<?echo $id_encabezado?>">
                    <input type="hidden" name="opcion" value="confirmadoEncabezado">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="image" value="Confirmado" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
                </td>

                <td colspan="2" class="centrar" width="50%"><br>
                    <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=adminAprobarEspacioPlan";
                        $ruta.="&opcion=mostrar";
                        $ruta.="&planEstudio=".$planEstudio;
                        $ruta.="&codProyecto=".$codProyecto;

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                    ?>
                    <a href="<?echo $pagina.$ruta?>">
                        <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/x.png" width="35" height="35" border="0"><br>No
                    </a>
                </td>
            </tr>

        </table>
    </form>
                    <?
        }


/**
 * Funcion que actualiza los datos de un encabezado en MySQL
 */
     function guardarEAEncabezado()
     {
        $usuario=$this->usuario;
        $id_encabezado=$_REQUEST['id_encabezado'];
        $planEstudio=$_REQUEST['planEstudio'];
        $clasificacion=$_REQUEST['clasificacion'];
        $encabezado_nombre=$_REQUEST['encabezado_nombre'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nivel=$_REQUEST['nivel'];
        $codProyecto=$_REQUEST['codProyecto'];
        $id_encabezado=$_REQUEST['id_encabezado'];

        $resultadoPeriodo=$this->consultarPeriodoActivo();
        $ano=$resultadoPeriodo[0][0];
        $periodo=$resultadoPeriodo[0][1];

        if($resultadoPeriodo==true)
        {

            $variable=array($id_encabezado,$encabezado_nombre,$nroCreditos,$nivel,$planEstudio,$codProyecto,$clasificacion);

            $cadena_sql=$this->sql->cadena_sql($this->configuracion,"actualizar_espacioAcademicoEncabezado",$variable);
            $resultado_espacioAcad=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
            $totalAfectados=$this->totalAfectados($this->configuracion, $this->accesoGestion);
            $espaciosAsociados=$this->buscarEspaciosAsociadosEncabezado($_REQUEST);
            //var_dump($espaciosAsociados);exit;
            if(isset($resultado_espacioAcad))
            {
                $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $id_encabezado, $planEstudio, $planEstudio);
                $cadena_sql_registroModificar=$this->sql->cadena_sql($this->configuracion,"registroModificarEA",$variablesRegistro);
                $resultadoRegistroModificar==$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_registroModificar,"");
                $modificado=$this->totalAfectados($this->configuracion, $this->accesoGestion);

                if (is_array($espaciosAsociados)&&$espaciosAsociados[0]['COD_PLAN']==$planEstudio)
                {
                    foreach ($espaciosAsociados as $key => $value) {
                        $value['nivel']=$nivel;
                        $actualizarEspaciosAsociados=$this->actualizarEspacioAsociado($value);
                        $espacioOracle=$this->buscarEspacioEnPlanOracle($value);
                        if (is_array($espacioOracle)&&$espacioOracle[0]['COD_PLAN']==$planEstudio)
                            {
                                $actualizarEspaciosOracle=$this->actualizarEspacioAsociadoOracle($value);
                            }
                        $datosRegistro=array(usuario=>$this->usuario,
                                            evento=>'19',
                                            descripcion=>'Modifica Nivel EA Asociado Asesor',
                                            registro=>$ano.'-'.$periodo.', '.$value['COD'].', '.$value['NIVEL'].', '.$nivel.', '.$value['COD_PLAN'],
                                            afectado=>$value['COD_PLAN']);
                    $this->procedimientos->registrarEvento($datosRegistro);

                    }
                }

                echo "<script>alert('El Nombre General ".$encabezado_nombre." se ha modificado')</script>";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variables="pagina=adminAprobarEspacioPlan";
                $variables.="&opcion=mostrar";
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&codProyecto=".$codProyecto;

                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$this->configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }else{

                echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde')</script>";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variables="pagina=adminAprobarEspacioPlan";
                $variables.="&opcion=mostrar";
                $variables.="&planEstudio=".$planEstudio;
                $variables.="&codProyecto=".$codProyecto;

                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$this->configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
                }

        }

     }

    function editarEspacioPlanEstudio($variable,$mensaje)
    {
        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"clasificacion","");
        $resultado_clasificacion=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        $cadena_sql=$this->sql->cadena_sql($this->configuracion,"buscarAsociacion",$variable);
        $resultado_asociacion=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
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
                <?if($asociado==0){?>
            <tr>
                <td class="cuadro_plano" width="30%">
                  <font size="2">Tipo de clasificaci&oacute;n:</font>
                </td>
                <td class="cuadro_plano" colspan="3" >
                    <select id="clasificacion" name="clasificacion">
                        <?
                            for($i=0;$i<count($resultado_clasificacion);$i++)
                            {
                                if($resultado_clasificacion[$i][0]==$variable[1])
                                    {
                                        ?>
                                            <option value="<?echo $resultado_clasificacion[$i][0]?>" selected><?echo $resultado_clasificacion[$i][1]?></option>
                                        <?
                                    }else{
                                    ?>
                                            <option value="<?echo $resultado_clasificacion[$i][0]?>"><?echo $resultado_clasificacion[$i][1]?></option>
                                    <?}
                            }
                            ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%">
                  <font size="2">N&uacute;mero de Cr&eacute;ditos:</font>
                </td>
                <td class="cuadro_plano" colspan="3">
                  <input type="text" name="nroCreditos" value="<?echo $variable[3]?>">
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%">
                  <font size="2">Nivel:</font>
                </td>
                <td class="cuadro_plano" colspan="3">
                  <input type="text" name="nivel" value="<?echo $variable[4]?>">
                </td>
            </tr>
                <?}else{?>
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
            <tr>
                <td class="cuadro_plano" width="30%">
                  <font size="2">N&uacute;mero de Cr&eacute;ditos:</font>
                </td>
                <td class="cuadro_plano" colspan="3">
                  <font size="2"><b>&nbsp;<?echo $variable[3]?></b></font>
                  <input type="hidden" name="nroCreditos" value="<?echo $variable[3]?>">
                </td>
            </tr>
            <tr>
                <td class="cuadro_plano" width="30%">
                  <font size="2">Nivel:</font>
                </td>
                <td class="cuadro_plano" colspan="3">
                  <font size="2"><b>&nbsp;<?echo $variable[4]?></b></font>
                  <input type="hidden" name="nivel" value="<?echo $variable[4]?>">
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
                <td class="cuadro_color_plano centrar" colspan="4"><br><font size="2">¿Desea guardar la informaci&oacute;n anteriormente diligenciada?</font></td>
            </tr>
            <tr>
                <td colspan="2" class="centrar" width="50%"><br>

                    <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                    <input type="hidden" name="codEspacio" value="<?echo $variable[8]?>">
                    <input type="hidden" name="codProyecto" value="<?echo $variable[11]?>">
                    <input type="hidden" name="semanas" value="<?echo $variable[9]?>">
                    <input type="hidden" name="opcion" value="confirmado">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="image" value="Confirmado" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
                </td>

                <td colspan="2" class="centrar" width="50%"><br>
                    <?
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $ruta="pagina=adminAprobarEspacioPlan";
                        $ruta.="&opcion=mostrar";
                        $ruta.="&planEstudio=".$variable[0];
                        $ruta.="&codProyecto=".$variable[11];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
                    ?>
                    <a href="<?echo $pagina.$ruta?>">
                        <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/x.png" width="35" height="35" border="0"><br>No
                    </a>
                </td>
            </tr>

        </table>
    </form>

                    <?
    }


//******************           **************************



}


?>
