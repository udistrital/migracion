<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroModificarEACoordinador extends funcionGeneral {
    //Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        $this->formulario="registroModificarEACoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");



    }


    function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto) {

        ?>

<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>MODIFICACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS PARA EL PROYECTO CURRICULAR<br><?echo $nombreProyecto?><br>PLAN DE ESTUDIOS: <?echo $planEstudio?></h4>
            <hr noshade class="hr">

        </td>
    </tr>

    <tr align="center">
        <td class="centrar" colspan="4">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=adminConfigurarPlanEstudioCoordinador";
                    $variables.="&opcion=verProyectos";

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
            </a>
        </td>
    </tr>
</table><?
    }

    function formularioCreacion($configuracion) {
        $planEstudio=$_REQUEST['planEstudio'];
        $codProyecto=$_REQUEST['codProyecto'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");
        $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        ?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
  <table class="sigma centrar" align="center" width="70%" border="0">
    <tr>
        <td class="sigma_a centrar" colspan="3">
            Todos los campos marcados con <font size="3" color="red">*</font> son obligatorios
        </td>
    </tr>
        <tr>
            <td class="sigma" align="right" colspan="2">
                <font size="2" color="red">*</font> C&oacute;digo del Espacio:
            </td>
            <td class="sigma" align="left">
                <?echo $_REQUEST['codEspacio']?>
            </td>
        </tr>
        <tr>
            <td class="sigma" align="right" colspan="2">
                <font size="2" color="red">*</font> Nombre del Espacio:
            </td>
            <td align="left">
                <input type="text" name="nombreEspacio" size="50" maxlength="80" value="<?echo $_REQUEST['nombreEspacio']?>">
            </td>
        </tr>
        <tr>
            <td class="sigma" align="right" colspan="2">
                <font size="2" color="red">*</font> N&uacute;mero de Cr&eacute;ditos:
            </td>
            <td align="left">
                <input type="text" name="nroCreditos" size="5" maxlength="5" value="<?echo $_REQUEST['nroCreditos']?>">
            </td>
        </tr>
        <tr>
            <td class="sigma" align="right" colspan="2">
                <font size="2" color="red">*</font> Nivel:
            </td>
            <td align="left">
                <input type="text" name="nivel" size="5" maxlength="5" value="<?echo $_REQUEST['nivel']?>">
            </td>
        </tr>
        <tr>
            <td class="sigma" align="right" colspan="2">
                <font size="2" color="red">*</font> Clasificaci&oacute;n:
            </td>
            <td align="left">
                <select class="sigma" name="clasificacion">
        <?
                            for($i=0;$i<count($resultado_clasificacion);$i++) {
                                    ?>
                    <option value="<?echo $resultado_clasificacion[$i][0]?>"<?if ($_REQUEST['clasificacion']==$resultado_clasificacion[$i][0]){?>selected<?}?>><?echo strtr(strtoupper($resultado_clasificacion[$i][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ")?></option>
                                    <?
                            }
        ?>
                </select>
            </td>
        </tr>
        <table class="sigma centrar" align="center" width="70%" border="0">
            <tr>
                <td class="sigma_a centrar" colspan="3"> <font size="2"><b>Distribuci&oacute;n</b></font></td>
            </tr>
            <tr class="centrar">
                <td class="sigma" width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Directo
                </td>
                <td class="sigma" width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Complementario
                </td>
                <td class="sigma" width="33%">
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
                <td class="sigma" colspan="3">
                    <font size="2" color="red">*</font>N&uacute;mero de semanas en que se cursa el espacio ac&aacute;demico
                </td>
            </tr>
        <?
        $htd=$_REQUEST['htd'];
        $htc=$_REQUEST['htc'];
        $hta=$_REQUEST['hta'];
                    $nroCreditos=$_REQUEST['nroCreditos'];

                    if($_REQUEST['semanas']!='') {
                        $semanas=$_REQUEST['semanas'];
                    }
                    else {
                        $semanas=($nroCreditos*48)/($htd+$htc+$hta);
                    }
                    ?>
            <tr class="centrar">
                <td colspan="3">
                    <select class="sigma" name="semanas" id="<? echo $semanas;?>" style="width:270px">
                        <option value="16" <? if($semanas==16) {
                        echo "selected=16";
                    } ?>>Espacios Semestrales (16 semanas)</option>
                        <option value="32" <? if($semanas==32) {
            echo "selected=32";
        } ?>>Espacios Anuales (32 semanas)</option>
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
                    <input type="hidden" name="opcion" value="validarEA">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input class="boton" type="submit" value="Guardar" >
                </td>
                <td class="centrar" width="50%">
                    <input class="boton" type="reset" >
                </td>
            </tr>
        </table>
    </table>
  </form>

        <?
    }

    function validarinformacion($configuracion) {
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
        //var_dump($_REQUEST);exit;

        $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion, $nombreEspacio, $nroCreditos, $nivel, $htd, $htc, $hta);



        if(($nombreEspacio=='')||($nroCreditos=='')||($nivel=='')||($htd=='')||($htc=='')||($hta=='')) {
            echo "<script>alert('Todos los campos deben ser diligenciados')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroModificarEACoordinador";
            $variables.="&opcion=solicitar";
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

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
            break;
        }

        if(!is_numeric($nivel)||!is_numeric($nroCreditos)||!is_numeric($htd)||!is_numeric($htc)||!is_numeric($hta)) {
            echo "<script>alert('Los campos (Creditos, Nivel, HTD, HTC, HTA) deben ser numericos')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroModificarEACoordinador";
            $variables.="&opcion=solicitar";
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

        if($totalDistribucion!=$horasCreditos) {
            echo "<script>alert('La distribución seleccionada no concuerda con la cantidad de créditos')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroModificarEACoordinador";
            $variables.="&opcion=solicitar";
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

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
            break;
        }


        $variable[10]=$codEspacio;
        $variable[11]=$semanas;
        $this->solicitarConfirmacion($configuracion,$variable);


    }

    function solicitarConfirmacion($configuracion,$variable) {

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");//echo $cadena_sql;exit;
        $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

        $this->encabezadoModulo($configuracion,$variable[0],$variable[1],$variable[2]);

        ?>
        <table class="sigma" align="center" width="80%" border="0">
          <tr>
            <th class="sigma_a" colspan="3">
              El espacio acad&eacute;mico a modificar contiene la siguiente informaci&oacute;n:
            </th>
          </tr>
          <tr><td><br></td></tr>
          <tr class="sigma">
              <td class="sigma" align="rigth" width="30%" >Plan de Estudio:</td>
              <td class="sigma" colspan="3"><?echo $variable[0]?></td>
          </tr>
          <tr class="sigma_a">
              <td class="sigma" width="30%">Cod&iacute;go del Espacio Acad&eacute;mico:</td>
              <td class="sigma" colspan="3"><font size="2"><?echo $variable[10]?></font></td>
          </tr>
          <tr class="sigma">
              <td class="sigma" width="30%">Nombre del Espacio Acad&eacute;mico:</td>
              <td class="sigma" colspan="3"><font size="2"><?echo $variable[4]?></font></td>
          </tr>
          <tr class="sigma_a">
              <td class="sigma" width="30%">Tipo de clasificaci&oacute;n:</td>
              <?
              for($i=0;$i<count($resultado_clasificacion);$i++)
              {
                  if($resultado_clasificacion[$i][0]==$variable[3])
                  {
                      ?>
                        <td class="sigma" colspan="3"><?echo $resultado_clasificacion[$i][1]?></td>
                      <?
                  }
                      }
                      ?>
          </tr>
          <tr class="sigma">
              <td class="sigma" width="30%">N&uacute;mero de Cr&eacute;ditos:</td>
              <td class="sigma" colspan="3"><?echo $variable[5]?></td>
          </tr>
          <tr class="sigma_a">
              <td class="sigma" width="30%">Nivel:</td>
              <td class="sigma" colspan="3"><?echo $variable[6]?></td>
          </tr>
          <tr class="sigma">
              <td class="sigma" width="30%">Horas de Trabajo Directo:</td>
              <td class="sigma" colspan="3"><?echo $variable[7]?></td>
          </tr>
          <tr class="sigma_a">
              <td class="sigma" width="30%">Horas de Trabajo Cooperativo:</td>
              <td class="sigma" colspan="3"><?echo $variable[8]?></td>
          </tr>
          <tr class="sigma">
              <td class="sigma" width="30%">Horas de Trabajo Autonomo:</td>
              <td class="sigma" colspan="3"><?echo $variable[9]?></td>
          </tr>
          <tr class="sigma_a">
              <td class="sigma" width="30%">N&uacute;mero de semanas en que se cursa el espacio ac&aacute;demico:</td>
              <td class="sigma" colspan="3"><?echo $variable[11]?></td>
          </tr>
          <tr>
              <th class="sigma centrar" colspan="3">¿Desea guardar la informaci&oacute;n anteriormente diligenciada?</th>
          </tr>
          <tr>
              <td width="33%" class="sigma centrar"><br>
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
                      <input type="hidden" name="opcion" value="confirmado">
                      <input type="hidden" name="action" value="<?echo $this->formulario?>">
                      <input type="image" value="Confirmado" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
                  </form>
              </td>
              <td width="33%" class="sigma centrar"><br>
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
                      <input type="hidden" name="opcion" value="modificar">
                      <input type="hidden" name="action" value="<?echo $this->formulario?>">
                      <input type="image" value="modificar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/modificar.png" width="35" height="35"><br>Modificar
                  </form>
              </td>
              <td width="33%" class="sigma centrar"><br>
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
                      <input type="hidden" name="opcion" value="cancelar">
                      <input type="hidden" name="action" value="<?echo $this->formulario?>">
                      <input type="image" value="cancelar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/no.png" width="35" height="35"><br>No
                  </form>
              </td>
          </tr>

      </table>
        <?
    }

    function guardarEA($configuracion) {
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
        //var_dump($_REQUEST);exit;
        $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
        $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
        $ano=$resultadoPeriodo[0][0];
        $periodo=$resultadoPeriodo[0][1];

        if($resultadoPeriodo==true) {

            $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion,$nombreEspacio,$nroCreditos,$nivel,$htd,$htc,$hta,$codEspacio);

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizar_espacioAcademico",$variable);//echo $cadena_sql;exit;
            $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_espacioAcad);exit;

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizar_planEstudio",$variable);//echo $cadena_sql;exit;
            $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

            $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $codEspacio, $planEstudio, $codProyecto);
            $cadena_sql_registroModificar=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroModificarEA",$variablesRegistro);//echo $cadena_sql_registroModificar;exit;
            $resultadoRegistroModificar==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroModificar,"");

            echo "<script>alert('El Espacio Académico ".$nombreEspacio." se ha modificado para su posterior aprobación ')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=adminConfigurarPlanEstudioCoordinador";
            $variables.="&opcion=verProyectos";

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
            break;

        }else {

//                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_registroEspacio",$variable);//echo $cadena_sql;exit;
//                        $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

            echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde 1 ')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroModificarEACoordinador";
            $variables.="&opcion=validarEA";
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

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
            break;
        }

    }

    function formularioModificarEncabezado($configuracion) {

        $id_encabezado=$_REQUEST['id_encabezado'];
        $planEstudio=$_REQUEST['planEstudio'];
        $clasificacion=$_REQUEST['clasificacion'];
        $encabezado_nombre=$_REQUEST['encabezado_nombre'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nivel=$_REQUEST['nivel'];
        $codProyecto=$_REQUEST['codProyecto'];
        $id_encabezado=$_REQUEST['id_encabezado'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];
        //var_dump($_REQUEST);exit;

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");//echo $cadena_sql;exit;
        $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto)
        ?>
  <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
    <table class="sigma centrar" align="center" width="70%" border="0">
    <tr>
        <td class="sigma_a centrar" colspan="2">
            <font size="2">
              <?if ($clasificacion==4)
                {
                  echo "Modificar la <b>Sugerencia de Electivo Extr&iacute;nseco</b>";
                }
                else
                {
                  echo "Modificar el Nombre General <b>".$encabezado_nombre."</b>";
                }
              ?>
            </font>
        </td>
    </tr>
        <tr>
            <td class="sigma" align="right" >
              Nombre General:
            </td>
            <td align="left">
              <input type="text" name="encabezado_nombre" value="<?echo $encabezado_nombre?>" size="45">
            </td>
        </tr>
        <tr>
            <td class="sigma" align="right" >
              Clasificaci&oacute;n:
            </td>
            <?
            if ($clasificacion==4)
            {
            ?>
            <td class="sigma" align="left">ELECTIVO EXTR&Iacute;NSECO
              <input type="hidden" name="clasificacion" value="4">
              <?
            }
            else
            {
            ?>
            <td align="left">
              <select class="sigma" name="clasificacion">
              <?
              for($i=0;$i<count($resultado_clasificacion);$i++) {
                if($resultado_clasificacion[$i][0]!=4)
                {?>
                    <option value="<?echo $resultado_clasificacion[$i][0]?>"<?if ($_REQUEST['clasificacion']==$resultado_clasificacion[$i][0]){?>selected<?}?>><?echo strtr(strtoupper($resultado_clasificacion[$i][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ")?></option>
                <?}
                else{}
              }
              ?>
              </select>
            <?
            }
              ?>
            </td>
        </tr>
        <tr>
            <td class="sigma" align="right" >N&uacute;mero de Cr&eacute;ditos:</td>
            <td class="sigma" align="left"><input type="text" name="nroCreditos" value="<?echo $nroCreditos?>"></td>
        </tr>
        <tr>
            <td class="sigma" align="right" >Nivel:</td>
            <td class="sigma" align="left"><input type="text" name="nivel" value="<?echo $nivel?>"></td>
        </tr>
        <tr>
          <th class="sigma centrar" colspan="2">¿Desea guardar la informaci&oacute;n anteriormente diligenciada?</th>
        </tr>
        <tr>
          <td class="sigma" colspan="3">
            <table class="sigma centrar" align="center" width="100%" border="0">
            <tr>
              <td class="sigma" align="center">
                <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                <input type="hidden" name="id_encabezado" value="<?echo $id_encabezado?>">
                <input type="hidden" name="opcion" value="confirmadoEncabezado">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" value="Confirmado" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
            </td>
            <td class="sigma" align="center">
        <?
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=adminConfigurarPlanEstudioCoordinador";
        $ruta.="&opcion=mostrar";
        $ruta.="&planEstudio=".$planEstudio;

        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>
                <a href="<?echo $pagina.$ruta?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/x.png" width="35" height="35" border="0"><br>No
                </a>
            </td>
            </tr>
            </table>
            </td>
        </tr>
    </table>
    </form>

                        <?

                    }

    function guardarEAEncabezado($configuracion) {
        $usuario=$this->usuario;
        $id_encabezado=$_REQUEST['id_encabezado'];
        $planEstudio=$_REQUEST['planEstudio'];
        $clasificacion=$_REQUEST['clasificacion'];
        $encabezado_nombre=$_REQUEST['encabezado_nombre'];
        $nroCreditos=$_REQUEST['nroCreditos'];
        $nivel=$_REQUEST['nivel'];
        $codProyecto=$_REQUEST['codProyecto'];
        $id_encabezado=$_REQUEST['id_encabezado'];
        //var_dump($_REQUEST);exit;

        $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');//echo $cadena_sql_bimestreActual;exit;
        $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");//var_dump($resultadoPeriodo);exit;
        $ano=$resultadoPeriodo[0][0];
        $periodo=$resultadoPeriodo[0][1];

        if($resultadoPeriodo==true) {

            $variable=array($id_encabezado,$encabezado_nombre,$nroCreditos,$nivel,$planEstudio,$codProyecto,$clasificacion);

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizar_espacioAcademicoEncabezado",$variable);//echo $cadena_sql;exit;
            $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_espacioAcad);exit;
            $totalAfectados=$this->totalAfectados($configuracion, $this->accesoGestion);
            //echo $totalAfectados;exit;

            if($totalAfectados>='1') {
                $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $id_encabezado, $planEstudio, $planEstudio);
                $cadena_sql_registroModificar=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroModificarEA",$variablesRegistro);//echo $cadena_sql_registroModificar;exit;
                $resultadoRegistroModificar==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroModificar,"");

                echo "<script>alert('El Espacio Acad\u00E9mico ".$encabezado_nombre." se ha modificado')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=adminConfigurarPlanEstudioCoordinador";
                $variables.="&opcion=mostrar";
                $variables.="&planEstudio=".$planEstudio;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }else {

                echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=adminConfigurarPlanEstudioCoordinador";
                $variables.="&opcion=mostrar";
                $variables.="&planEstudio=".$planEstudio;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
                break;
            }

        }

    }

}


?>
