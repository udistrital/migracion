<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroEditarAgruparEspaciosCoordinador extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
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
        $this->formulario="registroEditarAgruparEspaciosCoordinador";
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


function modificarEncabezado($configuracion) {
     
      $nombreProyecto=$_REQUEST["nombreProyecto"];
      $codProyecto=$_REQUEST["codProyecto"];
      $planEstudio=$_REQUEST["planEstudio"];
      $clasificacion=$_REQUEST["clasificacion"];
      $id_encabezado=$_REQUEST["id_encabezado"];
      $nivel=$_REQUEST["nivel"];
      $creditos=$_REQUEST["creditos"];
      $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);

       $cadena_sql_IdEnacabezado=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarIdEncabezado", $id_encabezado);//echo $cadena_sql_IdEnacabezado;exit;
       $resultadoIdEnacabezado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_IdEnacabezado,"busqueda" );

       $cadena_sql_nombrePlan=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"nombrePlanEstudio", $resultadoIdEnacabezado[0][3]);
       $resultado_nombrePlan=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_nombrePlan,"busqueda" );       
       
        ?>
<table class='contenidotabla centrar' width="80%">
    <tr align="center">
        <th class="sigma_a" colspan="9">
            AGRUPACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS PARA POSTERIOR APROBACI&Oacute;N DE VICERRECTORIA ACAD&Eacute;MICA
        </th>
    </tr>
    <?
        #enlace regreso  listado de planes

        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroConsultarAgrupacionEspaciosCoordinador";
        $ruta.="&opcion=verEncabezado";
        $ruta.="&planEstudio=".$planEstudio;
        $ruta.="&nombreProyecto=".$nombreProyecto;
        $ruta.="&codProyecto=".$codProyecto;
        $ruta.="&clasificacion=".$clasificacion;

        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>
        <tr>
         <td class="centrar" colspan="9">
            <a href="<?= $indice.$ruta ?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br>Atras
            </a>
        </td>
        </tr>
        
</table>
   <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
<table class="sigma centrar" align="center" width="80%" border="0">
<!--<table class='contenidotabla' width="80%">-->
    <tr align="center">
        <th class="sigma centrar" colspan="9">
          Modificar encabezado con clasificaci&oacute;n:
            <?
              foreach ($this->clasificaciones as $key=>$value)
              {
                if($this->clasificaciones[$key][0]==$clasificacion)
                {
                  $clas=strtr(strtoupper($this->clasificaciones[$key][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                }else{}
              }
                echo $clas;
            ?>
        </th>
    </tr>
    <tr class="sigma_a">
        <td class="sigma derecha">
           Plan de Estudios:
        </td>
        <td class="sigma izquierda">
            <? echo $planEstudio." - ".$resultado_nombrePlan[0][0];?>
        </td>
    </tr>
    <tr class="sigma">
        <td class="sigma derecha">
           Proyecto Curricular:
        </td>
        <td class="sigma izquierda">
            <? echo $codProyecto." - ".$nombreProyecto;?>
        </td>
    </tr>
    <tr class="sigma_a">
        <td class="sigma derecha">
            Clasificaci&oacute;n:
        </td>
        <td class="sigma izquierda">
              <select class="sigma" name="clasificacion" id="<? echo $resultadoIdEnacabezado[0][5];?>">
              <?
              for($i=0;$i<count($this->clasificaciones);$i++) {
                if($this->clasificaciones[$i][0]!=4)
                {?>
                    <option value="<?echo $this->clasificaciones[$i][0]?>"<?if ($resultadoIdEnacabezado[0][5]==$this->clasificaciones[$i][0]){?>selected<?}?>><?echo strtr(strtoupper($this->clasificaciones[$i][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ")?></option>
                <?}
                else{}
              }
              ?>
              </select>
        </td>
    </tr>
    <tr class="sigma">
        <td class="sigma derecha">
           Nombre del grupo de opciones:
        </td>
        <td class="sigma izquierda">
            <input type="text" name="encabezadoNombre" value="<? echo $resultadoIdEnacabezado[0][1];?>">
        </td>  
    </tr>
     <tr class="sigma_a">
        <td class="sigma derecha">
            Descripci&oacute;n del grupo de opciones:
        </td>
        <td class="sigma izquierda">
            <textarea name="encabezadoDescripcion" cols="40" rows="2"><? echo $resultadoIdEnacabezado[0][2];?></textarea>
        </td>
    </tr>
    <tr class="sigma">
        <td class="sigma derecha">
            Cr&eacute;ditos del grupo:
        </td>
        <td class="sigma izquierda">
            <select class="sigma" name="encabezadoCreditos" id="encabezadoCreditos" style="width:50px">
                           <?
                            $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarCreditos", $resultadoIdEnacabezado[0][3]);
                            $resultadoCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditos,"busqueda" );

                            for($i=0;$i<count($resultadoCreditos);$i++) {
                                ?>
                    <option value="<?echo $resultadoCreditos[$i][0]?>" 
                         <? if($resultadoCreditos[$i][0]==$resultadoIdEnacabezado[0][9]){echo "selected=".$resultadoIdEnacabezado[0][9];} ?>>
                         <?echo $resultadoCreditos[$i][0]?></option>
                                <?
                            }
                            ?>
           </select>
        </td>       
    </tr>
    <tr class="sigma_a">
        <td class="sigma derecha">
            Nivel del grupo:
        </td>
        <td class="sigma izquierda">
            <select class="sigma" name="encabezadoNivel" id="encabezadoNivel" style="width:50px">

                            <?
                            //selected="selected"
                            $cadena_sql_nivel=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarNivel", $resultadoIdEnacabezado[0][3]);
                            $resultadoNivel=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_nivel,"busqueda" );

                            for($i=0;$i<count($resultadoNivel);$i++) {
                                ?>
                    <option value="<?echo $resultadoNivel[$i][0]?>" 
                          <? if($resultadoNivel[$i][0]==$resultadoIdEnacabezado[0][10]){echo "selected=".$resultadoIdEnacabezado[0][10];} ?>>
                          <?echo $resultadoNivel[$i][0]?></option>
                                <?
                            }
                            ?>
           </select>
        </td>
    </tr>
    <tr class="centrar">
        <td class="centrar" colspan="2">
                    <input type="hidden" name="nombreProyecto" value="<? echo $nombreProyecto; ?>">
                    <input type="hidden" name="planEstudio" value="<? echo $planEstudio; ?>">
                    <input type="hidden" name="codProyecto" value="<? echo $codProyecto; ?>">
                    <input type="hidden" name="id_encabezado" value="<? echo $id_encabezado; ?>">
                    <input type="hidden" name="opcion" value="guardarCambios">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input class="boton" name='modificar' value='Modificar' type='submit' >&nbsp;&nbsp;
                           
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
                <input src="<?echo $configuracion['site'].$configuracion['grafico']?>" name='cancelar' class="boton" value='Cancelar' type='submit' >
               
            </a>
        </td>
        </tr>


        </table>
    </form>

        <?
        }

          function guardarCambiosEncabezado($configuracion)
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
         $id_encabezado=$_REQUEST["id_encabezado"];

         $band=0;

          if($_REQUEST['encabezadoNombre'] and $_REQUEST["encabezadoDescripcion"] and $_REQUEST["encabezadoCreditos"] and $_REQUEST["encabezadoNivel"] and $_REQUEST["clasificacion"])
                {
                 $band=1;

                 $cadena_sql_buscarEspaciosAsociados=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEspacioAsociados", $id_encabezado);
                 $resultadoBuscarEspaciosAsociados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_buscarEspaciosAsociados,"busqueda" );

                 $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                 $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                 $ano=$resultadoPeriodo[0][0];
                 $periodo=$resultadoPeriodo[0][1];               
                               
                 if($resultadoPeriodo==false)
                 {
                   $band=2;
                 }
                 if($resultadoBuscarEspaciosAsociados==true)
                 {
                   $band=3;
                 }
                 
                }
           
           if($band==1)
                {
                    $variables=array($encabezadoNombre, $encabezadoDescripcion, $clasificacion, $encabezadoCreditos, $encabezadoNivel, $id_encabezado);
                    $cadena_sql_modificarEncabezado=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"modificarEncabezado", $variables);
                    $resultadoModificarEncabezado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_modificarEncabezado,"" );

                    $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $encabezadoNombre, $planEstudio, $codProyecto, $id_encabezado );
                    $cadena_sql_registroModificar=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroModificarEncabezado",$variablesRegistro);
                    $resultadoRegistroModificar==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroModificar,"");

                   echo "<script>alert ('El Encabezado de Espacios Académicos ha sido registrado de forma exitosa');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
		   $variable.="&opcion=verEncabezado";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
                else if($band==2)
                {
                   echo "<script>alert ('La información no pudo ser registrada intente de nuevo');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
		   $variable.="&opcion=verEncabezado";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
                else if($band==3)
                {
                   echo "<script>alert ('El nombre general no debe tener espacios asociados para poder editarlo');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
		   $variable.="&opcion=verEncabezado";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }

                else if($band==0)
                {
                   echo "<script>alert ('Debe utilizar todos los campos');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroEditarAgruparEspaciosCoordinador";
		   $variable.="&opcion=modificar";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                   $variable.="&id_encabezado=".$_REQUEST["id_encabezado"];
                   $variable.="&nivel=".$_REQUEST["encabezadoNivel"];
                   $variable.="&creditos=".$_REQUEST["encabezadoCreditos"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
        }

    }

?>