<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroCrearAgrupacionEspaciosCoordinador extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
    private $usuario;
    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registroCrearAgrupacionEspaciosCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        if ($this->nivel==28||$this->nivel==4)
        {
            $this->pagina="adminConfigurarPlanEstudioCoordinador";
            $this->opcion="mostrar";
        }
        elseif($this->nivel==61)
        {
            $this->pagina="adminAprobarEspacioPlan";
            $this->opcion="mostrar";
        }

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");//echo $cadena_sql;exit;
        $this->clasificaciones=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;



    }

    function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto) {

        ?>

<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>ESPACIOS ACAD&Eacute;MICOS CON OPCIONES PARA EL PROYECTO CURRICULAR<br><?echo $nombreProyecto?><br>PLAN DE ESTUDIOS: <?echo $planEstudio?></h4>
            <hr noshade class="hr">

        </td>
    </tr>

    <tr align="center">
        <td class="centrar" colspan="4">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=".$this->pagina;
                    $variables.="&opcion=".$this->opcion;
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Volver al Plan de estudios
            </a>
        </td>
    </tr>
</table><?
    }


    function clasificacion($configuracion) {
    $this->encabezadoModulo($configuracion, $_REQUEST["planEstudio"], $_REQUEST["codProyecto"], $_REQUEST["nombreProyecto"]);

    //Consultamos los proyectos curriculares con su respectivo
    //  plan de estudio, y los mostramos en un <select>
        $cadena_sql_proyectos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"proyectos_curriculares",$this->usuario);
        $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
        ?>
<table class='sigma' align="center" width="80%">
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <?
        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroConsultarAgrupacionEspaciosCoordinador";
        $ruta.="&opcion=verEncabezado";
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
        $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>
        
        <tr>
            <th class="sigma_a" align="center" colspan="4">
                Seleccione la clasificaci&oacute;n del nombre general 
            </th>
        </tr>
        <tr>
          <td class="sigma centrar" colspan="4">
            <table class='sigma' align="center">
              <?
              for($i=0;$i<count($this->clasificaciones);$i++) {
                if($this->clasificaciones[$i][0]!=4)
                {?>
                  <tr>
                    <td class="sigma derecha">
                      <input type="radio" name="clasificacion" value="<?echo $this->clasificaciones[$i][0]?>">
                    </td>
                    <td class="sigma izquierda">
                      <?echo strtr(strtoupper($this->clasificaciones[$i][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ")?>
                    </td>
                  </tr>
                <?}
                else{}
              }
              ?>
            </table>
          </td>
        </tr>
        <tr>
          <td class="sigma centrar" colspan="4">
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto'];?>">
                <input type="hidden" name="planEstudio" value="<?echo $_REQUEST["planEstudio"];?>">
                <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST["nombreProyecto"];?>">
                <input type="hidden" name="opcion" value="crear">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="button" class="boton" onclick="location.replace('<?echo $indice.$ruta?>')" value="< Regresar">
                <input name='enviar' class="boton" value='Continuar >' type='submit' >
            </td>
        </tr>
    </form>
        
</table>
    <?
    }

    function crearEncabezado($configuracion) {
      
       $nombreProyecto=$_REQUEST["nombreProyecto"];
       $codProyecto=$_REQUEST["codProyecto"];
       $planEstudio=$_REQUEST["planEstudio"];       
       $clasificacion=$_REQUEST["clasificacion"];
       $this->encabezadoModulo($configuracion, $_REQUEST["planEstudio"], $_REQUEST["codProyecto"], $_REQUEST["nombreProyecto"]);

       $cadena_sql_nombrePlan=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nombrePlanEstudio", $planEstudio);
       $resultado_nombrePlan=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_nombrePlan,"busqueda" );

       $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
       $ruta="pagina=registroCrearAgrupacionEspaciosCoordinador";
       $ruta.="&opcion=determinarClasificacion";
       $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
       $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
       $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
       $ruta=$this->cripto->codificar_url($ruta,$configuracion);

       if($clasificacion=='')
           {
                echo "<script>alert('Debe seleccionar una clasificación')</script>";
                echo "<script>location.replace('".$indice.$ruta."')</script>";
                exit;
           }
        foreach ($this->clasificaciones as $key=>$value)
        {
          if($this->clasificaciones[$key][0]==$clasificacion)
          {
            $clas=strtr(strtoupper($this->clasificaciones[$key][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
          }else{}
        }
        ?>
          <table class='sigma' align="center" width="80%">
            <th class="sigma_a" align="center" colspan="4">
              INFORMACI&Oacute;N DEL NOMBRE GENERAL
            </th>
          </table>
       <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
           <table class='sigma' width="70%" align="center">
              <tr class="sigma">
                  <td class="sigma derecha">
                     Plan de Estudios:
                  </td>
                  <td class="sigma izquierda">
                      <? echo $planEstudio." - ".$resultado_nombrePlan[0][0];?>
                  </td>
              </tr>
              <tr class="sigma_a">
                  <td class="sigma derecha">
                     Proyecto Curricular:
                  </td>
                  <td class="sigma izquierda">
                      <? echo $codProyecto." - ".$nombreProyecto;?>
                  </td>
              </tr>
              <tr class="sigma">
                  <td class="sigma derecha">
                      Clasificaci&oacute;n:
                  </td>
                  <td class="sigma izquierda">
                      <?echo $clas;?>
                  </td>
              </tr>
              <tr class="sigma_a">
                  <td class="sigma derecha">
                     Nombre del grupo de opciones:
                  </td>
                  <td class="sigma izquierda">
                      <input type="text" name="encabezadoNombre">
                  </td>
              </tr>
               <tr class="sigma">
                  <td class="sigma derecha">
                      Descripci&oacute;n del grupo de opciones:
                  </td>
                  <td class="sigma izquierda">
                      <textarea name="encabezadoDescripcion" cols="40" rows="2"></textarea>
                  </td>
              </tr>
              <tr class="sigma_a">
                  <td class="sigma derecha">
                      Cr&eacute;ditos del grupo:
                  </td>
                  <td class="sigma izquierda">
                      <select class="sigma" name="encabezadoCreditos" id="encabezadoCreditos" style="width:50px">
                         <?
                          for($i=1;$i<11;$i++) {
                            ?>
                              <option value="<?echo $i?>"><?echo $i?></option>
                            <?
                          }
                          ?>
                     </select>
                  </td>
              </tr>
              <tr class="sigma">
                  <td class="sigma derecha">
                      Nivel del grupo:
                  </td>
                  <td class="sigma izquierda">
                      <select class="sigma" name="encabezadoNivel" id="encabezadoNivel" style="width:50px">
                          <?
                          for($i=1;$i<11;$i++) {
                            ?>
                              <option value="<?echo $i?>"><?echo $i?></option>
                            <?
                          }
                          ?>
                     </select>
                  </td>
              </tr>
              <tr class="centrar">
                  <td class="sigma centrar" colspan="2">
                      <input type="button" class="boton" onclick="location.replace('<?echo $indice.$ruta?>')" value="< Regresar">&nbsp;&nbsp;
                      <?      #enlace regreso  listado de planes

                      $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                      $ruta="pagina=registroConsultarAgrupacionEspaciosCoordinador";
                      $ruta.="&opcion=verEncabezado";
                      $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
                      $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                      $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
                      $ruta.="&clasificacion=".$_REQUEST["clasificacion"];

                      $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                      ?>
                      <a href="<?= $indice.$ruta ?>">
                          <input class="boton" src="<?echo $configuracion['site'].$configuracion['grafico']?>" name='crear' value='Cancelar' type='submit' >&nbsp;&nbsp;
                      </a>
                      <input type="hidden" name="nombreProyecto" value="<? echo $nombreProyecto; ?>">
                      <input type="hidden" name="planEstudio" value="<? echo $planEstudio; ?>">
                      <input type="hidden" name="codProyecto" value="<? echo $codProyecto; ?>">
                      <input type="hidden" name="clasificacion" value="<? echo $clasificacion; ?>">
                      <input type="hidden" name="opcion" value="generar">
                      <input type="hidden" name="action" value="<?echo $this->formulario?>">
                      <input class="boton" name='crear' value='Crear >' type='submit' >
                  </td>
              </tr>
          </table>
      </form>

        <?
        }

          function generarEncabezado($configuracion)
        {
          $usuario=$this->usuario;
          $planEstudio=$_REQUEST["planEstudio"];
          $codProyecto=$_REQUEST["codProyecto"];
          $nombreProyecto=$_REQUEST["nombreProyecto"];
          $clasificacion=$_REQUEST["clasificacion"];
          $encabezadoNombre=$_REQUEST["encabezadoNombre"];
          $encabezadoDescripcion=$_REQUEST["encabezadoDescripcion"];
          $encabezadoCreditos=$_REQUEST["encabezadoCreditos"];
          $encabezadoNivel=$_REQUEST["encabezadoNivel"];

          $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
          $parametros="&codProyecto=".$_REQUEST["codProyecto"];
          $parametros.="&planEstudio=".$_REQUEST["planEstudio"];
          $parametros.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
          $parametros.="&clasificacion=".$_REQUEST["clasificacion"];


          if($_REQUEST['encabezadoNombre'] and $_REQUEST["encabezadoDescripcion"] and $_REQUEST["encabezadoCreditos"] and $_REQUEST["encabezadoNivel"])
            {
                 
                if($_REQUEST['confirmacion']==1)
                {
                   unset ($_REQUEST['comfirmacion']);
                   $variables=array($encabezadoNombre, $encabezadoDescripcion, $planEstudio, $codProyecto, $clasificacion, $encabezadoCreditos, $encabezadoNivel);
                   $cadena_sql_crearEncabezado=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"crearEncabezado", $variables);
                   $resultadoCrearEncabezado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_crearEncabezado,"" );

                   $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                   $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                   $ano=$resultadoPeriodo[0][0];
                   $periodo=$resultadoPeriodo[0][1];

                   $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $encabezadoNombre, $planEstudio, $codProyecto );
                   $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroNombreGeneral",$variablesRegistro);
                   $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");

                   echo "<script>alert('El Nombre General ".$encabezadoNombre." ha sido registrado de forma exitosa')</script>";
                   $variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
                   $variable.="&opcion=verEncabezado";
                   $variable.=$parametros;

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);
                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                   exit;
                }else{
                       $buscarEncabezado=array($_REQUEST['encabezadoNombre'],$planEstudio);
                       $cadena_sql=$this->sql->cadena_sql($configuracion, $this->accesoGestion, "buscarNombreExistente",$buscarEncabezado);//echo $cadena_sql;exit;
                       $resultadoNombreExistente=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda");
                       if(is_array($resultadoNombreExistente))
                          {
                              $this->solicitarConfirmacion($configuracion);
                          }
                          else
                          {
                              $variables=array($encabezadoNombre, $encabezadoDescripcion, $planEstudio, $codProyecto, $clasificacion, $encabezadoCreditos, $encabezadoNivel);
                              $cadena_sql_crearEncabezado=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"crearEncabezado", $variables);
                              $resultadoCrearEncabezado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_crearEncabezado,"" );

                              $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                              $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                              $ano=$resultadoPeriodo[0][0];
                              $periodo=$resultadoPeriodo[0][1];

                              $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $encabezadoNombre, $planEstudio, $codProyecto );
                              $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroNombreGeneral",$variablesRegistro);
                              $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");

                              echo "<script>alert ('El Nombre General de Espacios Académicos ha sido registrado de forma exitosa');</script>";
                              $variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
                              $variable.="&opcion=verEncabezado";
                              $variable.=$parametros;

                              include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                              $this->cripto=new encriptar();
                              $variable=$this->cripto->codificar_url($variable,$configuracion);

                              echo "<script>location.replace('".$pagina.$variable."')</script>";
                              exit;
                          }
                }

            }
            else
            {
                   echo "<script>alert ('Debe utilizar todos los campos');</script>";
                   $variable="pagina=registroCrearAgrupacionEspaciosCoordinador";
		   $variable.="&opcion=crear";
                   $variable.=$parametros;

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                   exit;
                }
        }

function solicitarConfirmacion($configuracion)

{
       $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
       $ruta="pagina=registroCrearAgrupacionEspaciosCoordinador";
       $ruta.="&opcion=determinarClasificacion";
       $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
       $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
       $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
       $ruta=$this->cripto->codificar_url($ruta,$configuracion);

       unset($_REQUEST['opcion']);
       unset($_REQUEST['pagina']);
       foreach ($_REQUEST as $key => $value) {
          $destino.="&".$key."=".$value;
        }

        $this->encabezadoModulo($configuracion, $_REQUEST["planEstudio"], $_REQUEST["codProyecto"], $_REQUEST["nombreProyecto"]);
         $mensaje="<font size='2'>El nombre general &nbsp<b> ".$_REQUEST['encabezadoNombre']."</b>&nbsp ya existe ¿Desea crearlo de nuevo?</font>";

         ?>
          <table class="sigma" border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
            <tr>
              <td  class="sigma centrar" colspan="2">
                <?echo $mensaje?>
              </td>
            </tr>
            <tr class="texto_subtitulo">
              <td class="centrar"><?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=".$this->formulario;
                $variable.="&opcion=crear";
                $variable.=$destino;
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);
                ?>
                <a href="<?= $pagina.$variable ?>">
                    <input class="boton" name='crear' value='< Regresar' type='submit' >
                </a>&nbsp;&nbsp;
              <?
                unset ($variable);
                $variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
                $variable.="&opcion=verEncabezado";
                $variable.=$destino;
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);
                ?>
                <a href="<?= $pagina.$variable ?>">
                    <input class="boton" name='crear' value='Cancelar' type='submit' >
                </a>&nbsp;&nbsp;
              <?
                unset ($variable);
                $variable="pagina=".$this->formulario;
                $variable.="&opcion=generar";
                $variable.="&confirmacion=1";
                $variable.=$destino;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);
                ?>
                <a href="<?= $pagina.$variable ?>">
                    <input class="boton" name='crear' value='Crear >' type='submit' >
                </a>
              </td>
            </tr>
          </table>
          <?exit;

}


    }

?>