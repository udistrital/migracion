<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroAdicionarEspacioExistenteCoordinador extends funcionGeneral {
    //Crea un objeto tema y un objeto SQL.
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

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        $this->formulario="registroAdicionarEspacioExistenteCoordinador";
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
    }

    function listarPlanes($configuracion) {
        $planEstudioCoordinador=$_REQUEST['planEstudio'];
        $codProyecto=$_REQUEST['codProyecto'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"listaPlanesEstudio",$planEstudioCoordinador);//echo $cadena_sql;exit;
        $resultado_planesEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

        $this->encabezadoModulo($configuracion, $planEstudioCoordinador, $codProyecto, $nombreProyecto);

        $this->buscador($configuracion,$planEstudioCoordinador,$codProyecto,$nombreProyecto);
        ?>
<table  class="sigma" align="center" width="50%">
    <caption class="sigma">
        Seleccione el plan de estudio
    </caption>
    <hr class="hr_subtitulo">

        <?
            if (is_array($resultado_planesEstudio)) {
                ?>
    <tr class='cuadro_color centrar'>
        <th width="10%" class='sigma centrar'>C&oacute;digo</th>
        <th class='sigma centrar'>Nombre</th>
    </tr>
            <?
            for($i=0; $i<count($resultado_planesEstudio); $i++) 
            {
                if($i%2==0)
                    {
                        $claseFila="sigma";
                    }else
                        {
                            $claseFila="sigma_a";
                        }
                ?>
    <tr class='<?echo $claseFila;?>' >
        <?
            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $ruta="pagina=registroAdicionarEspacioExistenteCoordinador";
            $ruta.="&opcion=verPlanSeleccionado";
            $ruta.="&planEstudio=".$resultado_planesEstudio[$i][0];
            $ruta.="&planEstudioCoor=".$planEstudioCoordinador;
            $ruta.="&codProyecto=".$codProyecto;
            $ruta.="&nombreProyecto=".$nombreProyecto;
            $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>
        <td width="10%" class="sigma centrar">
            <a href="<?= $indice.$ruta ?>"><?= $resultado_planesEstudio[$i][0]?></a>
        </td>
        <td class="sigma">
            <a href="<?= $indice.$ruta ?>"><?= $resultado_planesEstudio[$i][1]?></a>
        </td>
    </tr>
                <?
            }#Cierre de for
            }#Cierre de is_array($resultado_planesEstudio)
            else {?>
    <tr>
        <td class='sigma' align="center"><strong>No exiten planes de estudio</strong></td>
    </tr>
            <? }#Cierre de else  is_array($resultado_planesEstudio)
        ?>

</table>
        <?


    }


    function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto)
    {

        ?>

<table class='contenidotabla centrar'>
    
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>SOLICITAR AGREGAR UN ESPACIO ACAD&Eacute;MICO EXISTENTE AL PLAN DE ESTUDIOS<br><?echo $nombreProyecto?><br>PLAN DE ESTUDIOS: <?echo $planEstudio?></h4>
            <hr noshade class="hr">

        </td>
    </tr>

    <tr align="center">
        <td class="centrar" colspan="1" width="25%">
        <?
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
                    $variables.="&opcion=listaPlanes";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>

            <a href="javascript:history.back()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="35" height="35" border="0"><br>Atras
            </a>
        </td>
        <td class="centrar" colspan="2" width="50%">
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
        <td class="centrar" colspan="1" width="25%">
        <?
        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
                    $variables.="&opcion=listaPlanes";
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
            <a href="javascript:history.forward()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png" width="35" height="35" border="0"><br>Adelante
            </a>
        </td>
    </tr>
</table><?
    }

    function planSeleccionado($configuracion)
    {
        $planEstudio=$_REQUEST['planEstudio'];
        $planEstudioCoor=$_REQUEST['planEstudioCoor'];
        $codProyecto=$_REQUEST['codProyecto'];
        $nombreProyecto=$_REQUEST['nombreProyecto'];

        //Buscamos los espacios academicos que pertenecen al plan de estudio seleccionado
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscar_registrados",$planEstudio);//echo $cadena_sql;exit;
        $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);

        $this->organizarPlanEstudio($configuracion,$resultado_planEstudio,$planEstudioCoor,$codProyecto,$nombreProyecto);
    }

    function organizarPlanEstudio($configuracion,$resultado_planEstudio,$planEstudioCoor,$codProyecto,$nombreProyecto)
    {
    ?>

<table class="sigma" width="100%" ><br>
    <caption class="sigma">Plan de Estudio <?echo $resultado_planEstudio[0][0]?></caption>
        <hr noshade class="hr">
        <?
        $totalCreditos=0;
        $nivel=1;
        for($i=0;$i<count($resultado_planEstudio);$i++)
        {
            if($i%2==0)
                {
                    $claseFila="sigma";
                }else
                    {
                        $claseFila="sigma_a";
                    }
            if($resultado_planEstudio[$i][2]!=$resultado_planEstudio[$i-1][2])
                {
                ?>
    <tr>
        <th class="sigma_a centrar" colspan="9">
            <font size="2"><b>PER&Iacute;ODO DE FORMACI&Oacute;N <?echo $resultado_planEstudio[$i][2]?></b></font>
        </th>
    </tr>
    <tr>
        <th class="sigma centrar">Cod&iacute;go Espacio Acad&eacute;mico</th>
        <th class="sigma centrar">Nombre Espacio Acad&eacute;mico</th>
        <th class="sigma centrar">Clasificaci&oacute;n</th>
        <th class="sigma centrar">Cr&eacute;ditos</th>
        <th class="sigma centrar">H.T.D</th>
        <th class="sigma centrar">H.T.C</th>
        <th class="sigma centrar">H.T.A</th>
        <th class="sigma centrar">Seleccionar</th>
    </tr>
    <tr class="<?echo $claseFila;?>">
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][1]?></td>
        <td class="sigma "><?echo $resultado_planEstudio[$i][6]?></td>
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][3]?></td>
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][7]?></td>
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][8]?></td>
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][9]?></td>
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][10]?></td>
        <td class="sigma centrar">
                <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
                            $variables.="&opcion=espacioSeleccionado";
                            $variables.="&codEspacio=".$resultado_planEstudio[$i][1];
                            $variables.="&nivelInicial=".$resultado_planEstudio[$i][2];
                            $variables.="&clasificacionInicial=".$resultado_planEstudio[$i][3];
                            $variables.="&clasificacion=".$resultado_planEstudio[$i][11];
                            $variables.="&nombreEspacio=".$resultado_planEstudio[$i][6];
                            $variables.="&nroCreditos=".$resultado_planEstudio[$i][7];
                            $variables.="&horastd=".$resultado_planEstudio[$i][8];
                            $variables.="&horastc=".$resultado_planEstudio[$i][9];
                            $variables.="&hta=".$resultado_planEstudio[$i][10];
                            $variables.="&planEstudioCoor=".$planEstudioCoor;
                            $variables.="&planEstudio=".$resultado_planEstudio[0][0];
                            $variables.="&codProyecto=".$codProyecto;
                            $variables.="&nombreProyecto=".$nombreProyecto;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                            ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35" border="0">
            </a>
        </td>    
    </tr>
                <?

            }else {?>
    <tr class="<?echo $claseFila;?>">
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][1]?></td>
        <td class="sigma "><?echo $resultado_planEstudio[$i][6]?></td>
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][3]?></td>
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][7]?></td>
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][8]?></td>
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][9]?></td>
        <td class="sigma centrar"><?echo $resultado_planEstudio[$i][10]?></td>
        <td class="sigma centrar">
                <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
                            $variables.="&opcion=espacioSeleccionado";
                            $variables.="&codEspacio=".$resultado_planEstudio[$i][1];
                            $variables.="&nivelInicial=".$resultado_planEstudio[$i][2];
                            $variables.="&clasificacionInicial=".$resultado_planEstudio[$i][3];
                            $variables.="&clasificacion=".$resultado_planEstudio[$i][11];
                            $variables.="&nombreEspacio=".$resultado_planEstudio[$i][6];
                            $variables.="&nroCreditos=".$resultado_planEstudio[$i][7];
                            $variables.="&horastd=".$resultado_planEstudio[$i][8];
                            $variables.="&horastc=".$resultado_planEstudio[$i][9];
                            $variables.="&hta=".$resultado_planEstudio[$i][10];
                            $variables.="&planEstudio=".$resultado_planEstudio[0][0];
                            $variables.="&planEstudioCoor=".$planEstudioCoor;
                            $variables.="&codProyecto=".$codProyecto;
                            $variables.="&nombreProyecto=".$nombreProyecto;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                            ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35" border="0">
            </a>
        </td>    

    </tr>
                <?
            }
        }

            ?></table>

            <?
        }

    function formularioEASeleccionado($configuracion) {

    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion",$planEstudio);//echo $cadena_sql;exit;
    $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

    $codProyecto=$_REQUEST['codProyecto'];
    $nombreProyecto=$_REQUEST['nombreProyecto'];
        $this->encabezadoModulo($configuracion, $_REQUEST['planEstudioCoor'], $codProyecto, $nombreProyecto);
        //var_dump($_REQUEST);exit;
        ?>
<table class="sigma" align="center" width="80%" border="0">
    <tr>
        <th class="sigma_a centrar" colspan="3">
            <font size="2"> Todos los campos marcados con <font size="2" color="red">*</font> son obligatorios</font>
        </th>
    </tr>
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <tr class="sigma">
            <td class="sigma derecha" colspan="2" width="20%">
                <font size="2" color="red">*</font> C&oacute;digo del Espacio:
            </td>
            <td class="sigma izquierda">
                <font size="2"><?echo $_REQUEST['codEspacio']?></font>
            </td>
        </tr>
        <tr class="sigma_a">
            <td class="sigma derecha" colspan="2" width="20%">
                <font size="2" color="red">*</font> Nombre del Espacio:
            </td>
            <td  class="sigma izquierda">
                <font size="2"><?echo $_REQUEST['nombreEspacio']?></font>
            </td>
        </tr>
        <tr class="sigma">
            <td class="sigma derecha" colspan="2">
                <font size="2" color="red">*</font> N&uacute;mero de Cr&eacute;ditos:
            </td>
            <td  class="sigma izquierda">
                <font size="2" ><?echo $_REQUEST['nroCreditos']?></font>
            </td>
        </tr>
        <tr class="sigma_a">
            <td class="sigma derecha" colspan="2">
                <font size="2" color="red">*</font> Per&iacute;odo de Formaci&oacute;n:
            </td>
            <td  class="sigma izquierda">
                <input type="text" name="nivel" size="5" maxlength="5" value="<?echo $_REQUEST['nivel']?>">
            </td>
        </tr>
        <tr class="sigma">
            <td class="sigma derecha" colspan="2">
                <font size="2" color="red">*</font> Clasificaci&oacute;n:
            </td>
            <td  class="sigma izquierda">
                <select  class="sigma" name="clasificacion" id="clasificacion" style="width:270px">
                  <?
                  if(is_array($resultado_clasificacion))
                    {
                      foreach($resultado_clasificacion as $key=>$value){
                      ?><option value="<?echo $value[0]?>"<?if ($_REQUEST['clasificacion']==$value[0]){?>selected<?}?>><?echo strtr(strtoupper($value[1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ")?></option><?}
                    }else {?>
                    <option value="">Seleccione...</option>
                    <option value="1">OBLIGATORIO B&Aacute;SICO</option>
                    <option value="2">OBLIGATORIO COMPLEMENTARIO</option>
                    <option value="3">ELECTIVO INTR&Iacute;NSECO</option>
                    <option value="4">ELECTIVO EXTR&Iacute;NSECO</option>
                    <option value="5">COMPONENTE PROPED&Eacute;UTICO</option>
                                <?}?>
                </select>
            </td>
        </tr>
        <table class="sigma" align="center" width="80%" border="0">
            <tr>
                <td class="sigma_a" colspan="3" align="center"> <font size="2"><b>Distribuci&oacute;n</b></font></td>
            </tr>
            <tr class="centrar">
                <td class="sigma" width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Directo
                </td>
                <td class="sigma" width="33%">
                    <font size="2" color="red">*</font> Horas Trabajo Cooperativo
                </td>
                <td class="sigma" width="33%">
                  <font size="2" color="red">*</font> Horas Trabajo Aut&oacute;nomo
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
                    <font size="2" color="blue"><?echo $_REQUEST['hta']?></font>
                </td>
            </tr>
            <tr>
                <td class="sigma" align="center" colspan="3">
                  <font size="2" color="red">*</font> N&uacute;mero de semanas en que se cursa el espacio acad&eacute;mico:
                    <font size="2">
                        <?
                        $horastd=$_REQUEST['horastd'];
                        $horastc=$_REQUEST['horastc'];
                        $hta=$_REQUEST['hta'];
                        $nroCreditos=$_REQUEST['nroCreditos'];
                        $semanas=$_REQUEST['semanas'];
                        if($semanas!='') {
                            echo $semanas;
                            $horastd=$_REQUEST['htd'];
                            $horastc=$_REQUEST['htc'];
                        }
                        else if($_REQUEST['horastd']!='') {
                            $Nrosemanas=($nroCreditos*48)/($horastd+$horastc+$hta);
                            echo $Nrosemanas;
                            $semanas=$Nrosemanas;
                        }
                                ?>
                    </font>      
                </td>
            </tr>

        </table>
        <table class="contenidotabla centrar" width="100%" border="0">
            <tr>
                <td class="centrar" width="50%">
                    <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                    <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                    <input type="hidden" name="hta" value="<?echo $_REQUEST['hta']?>">
                    <input type="hidden" name="codEspacio" value="<?echo $_REQUEST['codEspacio']?>">
                    <input type="hidden" name="nombreEspacio" value="<?echo $_REQUEST['nombreEspacio']?>">
                    <input type="hidden" name="nroCreditos" value="<?echo $_REQUEST['nroCreditos']?>">
                    <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudioCoor']?>">
                    <input type="hidden" name="semanas" value="<?echo $semanas?>">
                    <input type="hidden" name="opcion" value="validarEA">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input class="boton" type="submit" value="Guardar" >
                </td>
                <td class="centrar" width="50%">
                    <input class="boton" type="reset" >
                </td>
            </tr>
        </table>
    </form>
</table>

        <?
    }

    function validarinformacion($configuracion) {
        $codEspacio=$_REQUEST['codEspacio'];
        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $planEstudioCoor=$_REQUEST['planEstudio'];
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

        $variable=array($planEstudio,$codProyecto,$nombreProyecto,$clasificacion, $nombreEspacio, $nroCreditos, $nivel, $htd, $htc, $hta,$codEspacio,$planEstudioCoor, $semanas);
        //var_dump($variable);exit;

        if(!is_numeric($nivel)||!is_numeric($hta)||!is_numeric($htd)||!is_numeric($htc)) {
            echo "<script>alert('Los campos digitables deben ser numericos')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
            $variables.="&opcion=espacioSeleccionado";
            $variables.="&codEspacio=".$codEspacio;
            $variables.="&planEstudio=".$planEstudio;
            $variables.="&planEstudioCoor=".$planEstudioCoor;
            $variables.="&codProyecto=".$codProyecto;
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

            if($nivel==0)
            {echo "<script>alert('Por favor ingrese el Período de Formación del espacio para el plan de estudios.')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
            $variables.="&opcion=espacioSeleccionado";
            $variables.="&codEspacio=".$codEspacio;
            $variables.="&planEstudio=".$planEstudio;
            $variables.="&planEstudioCoor=".$planEstudioCoor;
            $variables.="&codProyecto=".$codProyecto;
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
            break;}

        if(($nombreEspacio=='')||($nroCreditos=='')||($nivel=='')||($htd=='')||($htc=='')||($hta=='')||($clasificacion=='')) {
            echo "<script>alert('Todos los campos deben ser diligenciados')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
            $variables.="&opcion=espacioSeleccionado";
            $variables.="&codEspacio=".$codEspacio;
            $variables.="&planEstudio=".$planEstudio;
            $variables.="&planEstudioCoor=".$planEstudioCoor;
            $variables.="&codProyecto=".$codProyecto;
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
            $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
            $variables.="&opcion=espacioSeleccionado";
            $variables.="&codEspacio=".$codEspacio;
            $variables.="&planEstudio=".$planEstudio;
            $variables.="&planEstudioCoor=".$planEstudioCoor;
            $variables.="&codProyecto=".$codProyecto;
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


        $this->solicitarConfirmacion($configuracion,$variable);


    }

    function solicitarConfirmacion($configuracion,$variable) {
//var_dump($variable);exit;
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");//echo $cadena_sql;exit;
        $resultado_clasificacion=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

        $this->encabezadoModulo($configuracion,$variable[0],$variable[1],$variable[2]);

        ?>
<table class="sigma" align="center" width="80%" border="0">
    <tr>
        <th class="sigma_a centrar" colspan="3">
          <font size="2">El espacio acad&eacute;mico <?echo $variable[4]?> tendr&aacute; las siguientes caracter&iacute;sticas</font>
        </th>
    </tr>
    <tr class="sigma">
        <td class="sigma" width="30%" >Plan de Estudios:</td>
        <td class="sigma" colspan="3"><?echo $variable[11]?></td>
    </tr>
    <tr class="sigma_a">
        <td class="sigma" width="30%" >Cod&iacute;go del Espacio Acad&eacute;mico:</td>
        <td class="sigma" colspan="3"><font size="2"><?echo $variable[10]?></font></td>
    </tr>
    <tr class="sigma">
        <td class="sigma" width="30%">Nombre del Espacio Acad&eacute;mico:</td>
        <td class="sigma" colspan="3"><font size="2"><?echo $variable[4]?></font></td>
    </tr>
    <tr class="sigma_a">
        <td class="sigma" width="30%">Tipo de clasificaci&oacute;n:</td>
        <?
        for($i=0;$i<count($resultado_clasificacion);$i++) {
            if($resultado_clasificacion[$i][0]==$variable[3]) {
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
        <td class="sigma" width="30%">Per&iacute;odo de Formaci&oacute;n:</td>
        <? if ($variable[3]==5)
        {$variable[6]=98;
            ?><td class="sigma" colspan="3">Componente Proped&eacute;utico</td><?
            }else{
        ?>
        <td class="sigma" colspan="3"><?echo $variable[6]?></td>
        <?}?>
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
        <td class="sigma" colspan="3"><?echo $variable[12]?></td>
    </tr>
    <tr>
        <th class="sigma centrar" colspan="3">¿Desea guardar la informaci&oacute;n anteriormente diligenciada?</th>
    </tr>
    <tr>
        <td width="33%" class="sigma centrar"><br>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                <input type="hidden" name="planEstudioCoor" value="<?echo $variable[11]?>">
                <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                <input type="hidden" name="nombreEspacio" value="<?echo $variable[4]?>">
                <input type="hidden" name="nroCreditos" value="<?echo $variable[5]?>">
                <input type="hidden" name="nivel" value="<?echo $variable[6]?>">
                <input type="hidden" name="htd" value="<?echo $variable[7]?>">
                <input type="hidden" name="htc" value="<?echo $variable[8]?>">
                <input type="hidden" name="hta" value="<?echo $variable[9]?>">
                <input type="hidden" name="codEspacio" value="<?echo $variable[10]?>">
                <input type="hidden" name="semanas" value="<?echo $variable[12]?>">
                <input type="hidden" name="opcion" value="confirmado">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type="image" value="Confirmado" src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35"><br>Si
            </form>
        </td>
        <td width="33%" class="sigma centrar"><br>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                <input type="hidden" name="codProyecto" value="<?echo $variable[1]?>">
                <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                <input type="hidden" name="planEstudioCoor" value="<?echo $variable[11]?>">
                <input type="hidden" name="nombreProyecto" value="<?echo $variable[2]?>">
                <input type="hidden" name="clasificacion" value="<?echo $variable[3]?>">
                <input type="hidden" name="nombreEspacio" value="<?echo $variable[4]?>">
                <input type="hidden" name="nroCreditos" value="<?echo $variable[5]?>">
                <input type="hidden" name="nivel" value="<?echo $variable[6]?>">
                <input type="hidden" name="htd" value="<?echo $variable[7]?>">
                <input type="hidden" name="htc" value="<?echo $variable[8]?>">
                <input type="hidden" name="hta" value="<?echo $variable[9]?>">
                <input type="hidden" name="codEspacio" value="<?echo $variable[10]?>">
                <input type="hidden" name="semanas" value="<?echo $variable[12]?>">
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
                <input type="hidden" name="semanas" value="<?echo $variable[12]?>">
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

        $codProyecto=$_REQUEST['codProyecto'];
        $planEstudio=$_REQUEST['planEstudio'];
        $planEstudioCoor=$_REQUEST['planEstudioCoor'];
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

        //var_dump($_REQUEST);exit;

        $variable=array($planEstudioCoor,$codProyecto,$nombreProyecto,$clasificacion,$nombreEspacio,$nroCreditos,$nivel,$htd,$htc,$hta,$codEspacio,$semanas);
        //busca si el espacio a asociar ya ha sido registrado para el plan de estudios
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEspacioARegistrar",$variable);//echo $cadena_sql;exit;
        $buscarEspacioARegistrar=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;


        if(is_array($buscarEspacioARegistrar)) {
          echo "<script>alert('El Espacio Académico ".$nombreEspacio." ya ha sido creado con anterioridad en el período de formación ".$buscarEspacioARegistrar[0][0]." del Plan de Estudios')</script>";
          $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
          $variables="pagina=".$this->pagina;
          $variables.="&opcion=".$this->opcion;
          $variables.="&planEstudio=".$planEstudio;
          $variables.="&codProyecto=".$codProyecto;
          $variables.="&nombreProyecto=".$nombreProyecto;

          include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
          $this->cripto=new encriptar();
          $variables=$this->cripto->codificar_url($variables,$configuracion);
          echo "<script>location.replace('".$pagina.$variables."')</script>";
          break;
        }
        else {


          $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registro_planEstudio",$variable);//echo $cadena_sql;exit;
          $resultado_planEstudio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

          if($resultado_planEstudio == true) {

            $cadena_sql=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "semestreActual", '');//echo $cadena_sql;exit;
            $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            $ano=$resultadoPeriodo[0][0];
            $periodo=$resultadoPeriodo[0][1];

            $variablesRegistro=array($this->usuario,date('YmdHis'),'17','Agrego E.A. existente en otro plan',$ano."-".$periodo." , ".$codEspacio.", 0, 0, ".$planEstudio.", ".$codProyecto.", clas=".$clasificacion, $planEstudioCoor);

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento",$variablesRegistro);//echo $cadena_sql;exit;
            $resultado_Evento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

            echo "<script>alert('El Espacio Académico ".$nombreEspacio." se ha creado para su posterior aprobación ')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=".$this->pagina;
            $variables.="&opcion=".$this->opcion;
            $variables.="&planEstudio=".$planEstudio;
            $variables.="&codProyecto=".$codProyecto;
            $variables.="&nombreProyecto=".$nombreProyecto;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
            break;

          }else {

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_registroEspacio",$variable);//echo $cadena_sql;exit;
            $resultado_espacioAcad=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"" );//var_dump($resultado_planEstudio);exit;

            echo "<script>alert('La base de datos se encuentra ocupada por favor intente mas tarde ')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
            $variables.="&opcion=validarEA";
            $variables.="&codProyecto=".$codProyecto;
            $variables.="&planEstudio=".$planEstudio;
            $variables.="&planEstudioCoor=".$planEstudioCoor;
            $variables.="&nombreProyecto=".$nombreProyecto;
            $variables.="&clasificacion=".$clasificacion;
            $variables.="&nombreEspacio=".$nombreEspacio;
            $variables.="&nroCreditos=".$nroCreditos;
            $variables.="&codEspacio=".$codEspacio;
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

        }
    }

    function buscador($configuracion,$planEstudioCoor,$codProyecto, $nombreProyecto) {
        ?>
<script>
    <!--

    function mostrar_div(elemento) {

        if(elemento.value=="cod") {
            document.getElementById("campo_palabra").style.display = "none";
            document.getElementById("campo_codigo").style.display = "block";
            document.forms[0].palabraEA.value='';
        }else if(elemento.value=="palab") {
            document.getElementById("campo_codigo").style.display = "none";
            document.getElementById("campo_palabra").style.display = "block";
            document.forms[0].codigoEA.value='';
        }else {
            document.getElementById("campo_codigo").style.display = "block";
        }

    }

    -->
</script>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
    <div align="center"><table class="sigma_borde centrar" width="100%">
                <caption class="sigma centrar">
                    SELECCIONE LA OPCI&Oacute;N PARA BUSCAR EL ESPACIO ACAD&Eacute;MICO
                </caption>
                <tr class="sigma">
                    <td class="sigma derecha" width="20%">
                        C&oacute;digo<br>
                        Espacio Acad&eacute;mico
                    </td>
                    <td class="sigma centrar" width="2%">
                        <input type="radio" name="codigorad" value="cod" checked onclick="javascript:mostrar_div(this)"><br>
                        <input type="radio" name="codigorad" value="palab" onclick="javascript:mostrar_div(this)">
                    </td>
                    <td  class="sigma centrar">
                        <div align="center" id="campo_codigo">
                            <table class="sigma centrar" width="80%" border="0">
                            <tr>
                                <td class="sigma centrar" colspan="2">
                                    <font size="1">Digite el c&oacute;digo del Espacio Académico que desea buscar</font><br>
                                    <input type="text" name="codigoEA" value="" size="6" maxlength="6">
                                </td>
                                <td class="sigma centrar" rowspan="2">
                                    <input type="hidden" name="opcion" value="buscador">
                                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                    <input type="hidden" name="planEstudioCoor" value="<?echo $planEstudioCoor?>">
                                    <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                    <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                                    <small><input class="boton" type="submit" value=" Buscar "></small>
                                </td>
                            </tr>
                            </table>
                        </div>
                        <div align="center" id="campo_palabra" style="display:none">
                            <table class="sigma centrar"  width="80%" border="0" >
                            <tr>
                                <td class="sigma centrar" colspan="3">
                                    <font size="1">Digite el nombre del Espacio Académico que desea buscar</font><br>
                                    <input type="text" name="palabraEA" value="" size="30" maxlength="30">
                                </td>
                                <td class="sigma centrar" rowspan="2">
                                    <input type="hidden" name="opcion" value="buscador">
                                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                    <input type="hidden" name="planEstudioCoor" value="<?echo $planEstudioCoor?>">
                                    <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                                    <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                                    <small><input class="boton" type="submit" value=" Buscar "></small>
                                </td>
                            </tr>
                            </table>
                        </div>
                    </td>

                </tr></table>
    </div>
</form>
        <?
    }

    function buscarEA($configuracion) {
    $codProyecto=$_REQUEST['codProyecto'];
    $nombreProyecto=$_REQUEST['nombreProyecto'];
        if($_REQUEST['codEspacio']) {
            if(!is_numeric($_REQUEST['codEspacio'])) {
                echo "<script>alert('El código del espacio académico debe ser numerico')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
                $variables.="&opcion=listaPlanes";
                $variables.="&planEstudio=".$_REQUEST['planEstudioCoor'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
                echo "<script>location.replace('".$pagina.$variables."')</script>";
            }
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEACodigo",$_REQUEST['codEspacio']);//echo $cadena_sql;exit;
            $resultado_Espacios=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

            $this->encabezadoModulo($configuracion, $_REQUEST['planEstudioCoor'], $codProyecto, $nombreProyecto);
            $this->buscador($configuracion, $_REQUEST['planEstudioCoor'],$codProyecto,$nombreProyecto);

            if(is_array($resultado_Espacios)) {

                ?>

<table class="sigma" width="100%">
    <caption class="sigma">RESULTADOS</caption>
    <tr>
        <th class="sigma centrar" width="10%">Plan Estudio</th>
        <th class="sigma centrar">Proyecto Curricular</th>
        <th class="sigma centrar" width="15%">Codigo Espacio Acad&eacute;mico</th>
        <th class="sigma centrar">Nombre E.A</th>
        <th class="sigma centrar" width="10%">Cr&eacute;ditos</th>
        <th class="sigma centrar" width="10%">Seleccionar</th>
    </tr>

                <?

                for($i=0;$i<count($resultado_Espacios);$i++) 
                {
                    if($i%2==0)
                        {
                            $claseFila="sigma";
                        }else
                            {
                                $claseFila="sigma_a";
                            }
                    ?><form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <tr class="<?echo $claseFila;?>">
                            <td class="sigma centrar"><? echo $resultado_Espacios[$i][0]?></td>
                            <td class="sigma"><? echo $resultado_Espacios[$i][1]?></td>
                            <td class="sigma centrar"><? echo $resultado_Espacios[$i][2]?></td>
                            <td class="sigma"><? echo $resultado_Espacios[$i][3]?></td>
                            <td class="sigma centrar"><? echo $resultado_Espacios[$i][4]?></td>
                            <td class="sigma centrar">
                            <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
                            $variables.="&opcion=espacioSeleccionado";
                            $variables.="&codEspacio=".$resultado_Espacios[$i][2];
                            $variables.="&nombreEspacio=".$resultado_Espacios[$i][3];
                            $variables.="&nroCreditos=".$resultado_Espacios[$i][4];
                            $variables.="&hta=".$resultado_Espacios[$i][5];
                            $variables.="&horastc=".$resultado_Espacios[$i][6];
                            $variables.="&horastd=".$resultado_Espacios[$i][7];
                            $variables.="&nivel=".$resultado_Espacios[$i][8];
                            $variables.="&planEstudio=".$resultado_Espacios[$i][0];
                            $variables.="&clasificacion=".$resultado_Espacios[$i][9];
                            $variables.="&semanas=".$resultado_Espacios[$i][10];
                            $variables.="&planEstudioCoor=".$_REQUEST['planEstudioCoor'];
                            $variables.="&codProyecto=".$codProyecto;
                            $variables.="&nombreProyecto=".$nombreProyecto;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variables=$this->cripto->codificar_url($variables,$configuracion);
                            ?>
                            <a href="<?echo $pagina.$variables?>">
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35" border="0">
                            </a>
                            </td>
        </tr>
    </form>
                                    <?
                                }
                                ?>
</table>
                                <?
                            }else {
                                ?>
<table class="sigma centrar">
    <tr>
        <td class="sigma centrar">
            No se encontraron registros del espacio acad&eacute;mico
        </td>
    </tr>
</table>
                <?
            }

        }
        else if($_REQUEST['palabraEA']) {
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEANombre",$_REQUEST['palabraEA']);//echo $cadena_sql;exit;
                $resultado_Espacios=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;

                $this->encabezadoModulo($configuracion, $_REQUEST['planEstudioCoor'], $codProyecto, $nombreProyecto);
            $this->buscador($configuracion, $_REQUEST['planEstudioCoor'],$codProyecto,$nombreProyecto);

            if(is_array($resultado_Espacios)) {

                ?>

<table class="sigma" width="100%">
    <caption class="sigma">RESULTADOS</caption>
    <tr>
        <th class="sigma centrar" width="10%">Plan Estudio</th>
        <th class="sigma centrar">Proyecto Curricular</th>
        <th class="sigma centrar" width="15%">Codigo Espacio Acad&eacute;mico</th>
        <th class="sigma centrar">Nombre E.A</th>
        <th class="sigma centrar" width="10%">Cr&eacute;ditos</th>
        <th class="sigma centrar" width="10%">Seleccionar</th>
    </tr>

                <?

                for($i=0;$i<count($resultado_Espacios);$i++) 
                {
                    if($i%2==0)
                        {
                            $claseFila="sigma";
                        }else
                            {
                                $claseFila="sigma_a";
                            }
                    ?><form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <tr class="<?echo $claseFila?>">
            <td class="sigma centrar"><? echo $resultado_Espacios[$i][0]?></td>
            <td class="sigma"><? echo $resultado_Espacios[$i][1]?></td>
            <td class="sigma centrar"><? echo $resultado_Espacios[$i][2]?></td>
            <td class="sigma"><? echo $resultado_Espacios[$i][3]?></td>
            <td class="sigma centrar"><? echo $resultado_Espacios[$i][4]?></td>
            <td class="sigma centrar">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
                    $variables.="&opcion=espacioSeleccionado";
                    $variables.="&codEspacio=".$resultado_Espacios[$i][2];
                    $variables.="&nombreEspacio=".$resultado_Espacios[$i][3];
                    $variables.="&nroCreditos=".$resultado_Espacios[$i][4];
                    $variables.="&hta=".$resultado_Espacios[$i][5];
                    $variables.="&horastc=".$resultado_Espacios[$i][6];
                    $variables.="&horastd=".$resultado_Espacios[$i][7];
                    $variables.="&nivel=".$resultado_Espacios[$i][8];
                    $variables.="&planEstudio=".$resultado_Espacios[$i][0];
                    $variables.="&clasificacion=".$resultado_Espacios[$i][9];
                    $variables.="&planEstudioCoor=".$_REQUEST['planEstudioCoor'];
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
                <a href="<?echo $pagina.$variables?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/clean.png" width="35" height="35" border="0">
                </a>
            </td>
        </tr>
    </form>
                                    <?
                                }
                                ?>
</table>
                                <?
                            }else {
                                ?>
<table class="contenidotabla centrar">
    <tr>
        <td class="cuadro_plano centrar">
            No se encontraron registros del espacio acad&eacute;mico
        </td>
    </tr>
</table>
                                <?
                            }
                        }else {
            echo "<script>alert('Por favor ingrese el código o parte del nombre del espacio académico para realizar la busqueda')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variables="pagina=registroAdicionarEspacioExistenteCoordinador";
            $variables.="&opcion=listaPlanes";
            $variables.="&planEstudio=".$_REQUEST['planEstudioCoor'];
            $variables.="&codProyecto=".$codProyecto;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variables=$this->cripto->codificar_url($variables,$configuracion);
            echo "<script>location.replace('".$pagina.$variables."')</script>";
        }
    }

}


?>
