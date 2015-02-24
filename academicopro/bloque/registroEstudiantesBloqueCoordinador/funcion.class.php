<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_registroEstudiantesBloqueCoordinador extends funcionGeneral {
    //Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
//        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
//        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        $this->formulario="registroEstudiantesBloqueCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $this->verificar="control_vacio(".$this->formulario.",'numero')";
        $this->verificar.="&&verificar_numero(".$this->formulario.",'numero')";
        $this->verificar.="&&verificar_rango(".$this->formulario.",'numero','0','99')";
        $this->seleccionar="todos('todos')";
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"ano_periodo",'');//echo $cadena_sql;exit;
        $this->periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );


    }

   
    function registrarBloqueEstudiantes($configuracion)
        {

         $variablesBloque=array($_REQUEST['planEstudio'],$_REQUEST['codProyecto'],$_REQUEST['idBloque'], $this->periodo[0][0], $this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"bloque_publicado",$variablesBloque);//echo $cadena_sql;exit;
        $resultado_bloquePublicado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        if($resultado_bloquePublicado[0][0]=='1')
            {
                echo "<script>alert('El bloque ".$_REQUEST['idBloque']." ya se encuentra publicado, no se pueden modificar datos')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroBloqueEstudiantes";
                $ruta.="&opcion=crear";
                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                $ruta.="&totalCreditos=".$totalCreditos;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                echo "<script>location.replace('".$pagina.$ruta."')</script>";
                exit;
            }
            if($this->periodo[0][1]=='3')
            {
                $perEst=$this->periodo[0][1]-1;
            }
            else
            {
              $perEst=$this->periodo[0][1];
            }
            $variable[0]=$_REQUEST['codProyecto'];
            $variable[1]=$_REQUEST['planEstudio'];
            $variable[2]=$this->periodo[0][0];
            $variable[3]=$perEst;

            $cadena_sql_estudiantes=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"estudiantes_carrera",$variable);//echo $cadena_sql_estudiantes;exit;
            $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_estudiantes,"busqueda" );

        ?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
    <table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
        <tr align="center">
            <td class="centrar" colspan="10">
                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            </td>
        </tr>
        <tr>
            <td class="izquierda" colspan="10">
        <?
        echo "<b>PROYECTO CURRICULAR: </b>".$variable[0]." - ".strtoupper($_REQUEST['nombreProyecto']);
        echo "<br><b>PLAN DE ESTUDIOS: </b>".$variable[1];
        echo "<br><b>BLOQUE: </b>".$_REQUEST['idBloque'];
        ?>
                <hr noshade class="hr">
            </td>
        </tr>
        <?if(is_array($resultado_estudiantes)) {?>
        <tr class="cuadro_color centrar">
            <td colspan="5">
                <h4>SELECCIONE LOS ESTUDIANTES QUE HARAN PARTE DE ESTE BLOQUE</h4>
                <hr noshade class="hr">
            </td>
        <tr>
            <td colspan="5" class="centrar">
                <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                SELECCIONAR LOS
                <select id="algunos" style="width:50" onchange="javascript:seleccionCheck(document.getElementById('seleccionados'),'registroEstudiantesBloqueCoordinador',document.getElementById('algunos').value);"
                        <option value="0">0</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                </select> PRIMEROS ESTUDIANTES
            </td>
        </tr>
        <tr class="cuadro_color centrar">
            <td width="10%">
                N&Uacute;MERO
            </td>
            <td width="10%">
                PROYECTO CURRICULAR
            </td>
            <td width="20%">
                C&Oacute;DIGO ESTUDIANTE
            </td>
            <td width="40%">
                NOMBRE ESTUDIANTE
            </td>
            <td width="10%">
                <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                TODOS
                <br>
                <input align="center" type=checkbox id="seleccionados" name="seleccionados" value="seleccionado" onclick="javascript:todos(this,'registroEstudiantesBloqueCoordinador');">
            </td>
        </tr>
            <?$k=1;
            for($i=0;$i<count($resultado_estudiantes);$i++) {
                $variable[2]=$resultado_estudiantes[$i][0];
                $variable[3]=$_REQUEST['idBloque'];
                $variable[4]=$this->periodo[0][0];
                $variable[5]=$this->periodo[0][1];
                $cadena_sql_estudiantesReg=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"estudiantes_bloques",$variable);//echo $cadena_sql_estudiantesReg;//exit;
                $resultado_estudiantesRegistrados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_estudiantesReg,"busqueda" );

                if($resultado_estudiantesRegistrados==NULL) {
                    ?>
        <tr class="cuadro_plano">
            <td width="20" class="centrar">
                            <?echo $k?>
            </td>
            <td width="20" class="centrar">
                            <?echo $resultado_estudiantes[$i][2]?>
            </td>
            <td width="30" class="centrar">
                    <?echo $resultado_estudiantes[$i][0]?>
            </td>
            <td width="40">
                    <?echo utf8_decode($resultado_estudiantes[$i][1])?>
            </td>
            <td width="20" class="centrar">
                <input type="checkbox" name="estudiante<?echo $i?>" value="<?echo $resultado_estudiantes[$i][0]?>">

            </td>
        </tr>
                    <?$k++;
                                }

            }

            ?>
        <tr class="cuadro_plano centrar">
            <td class="centrar" width="50%" colspan="3">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroEstudiantesBloqueCoordinador";
                    $variable.="&opcion=editar";
                    $variable.="&idBloque=".$_REQUEST['idBloque'];
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                    $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];


                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            ?>
                <a href="<?= $pagina.$variable ?>" on>
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="50" height="50" border="0"><br><font size="2">Regresar</font>
                </a>
            </td>
            <td width="50%" colspan="3">
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
                <input type="hidden" name="idBloque" value="<?echo $_REQUEST['idBloque']?>">
                <input type="hidden" name="totalEstudiantes" value="<?echo $k?>">
                <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
                <input type="hidden" name="opcion" value="guardar">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type='image' src="<?echo $configuracion['site'].$configuracion['grafico']?>/grupoNuevo.png" width="50" height="50" border="0" ><br><font size="2">Guardar</font>

            </td>
        </tr><?}else {
            ?><tr>
            <td class="cuadro_plano">
                No existen estudiantes registrados para el periodo acad&eacute;mico vigente
            </td>
        </tr><?
        }?>
    </table>
</form><?
            }

    function guardarBloque($configuracion)
        {
        if(is_numeric($_REQUEST['planEstudio']) && is_numeric($_REQUEST['codProyecto']) && is_numeric($_REQUEST['idBloque']) ){
            $variables=array($_REQUEST['planEstudio'],$_REQUEST['codProyecto'],$_REQUEST['idBloque'], $this->periodo[0][0], $this->periodo[0][1]);
            $resultado_bloquePublicado=$this->consultarBloquePublicado($configuracion,$variables);
            
            if($resultado_bloquePublicado[0][0]=='1')
                {
                    echo "<script>alert('El bloque ".$_REQUEST['idBloque']." ya se encuentra publicado, no se pueden modificar datos')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $ruta="pagina=registroBloqueEstudiantes";
                    $ruta.="&opcion=crear";
                    $ruta.="&idBloque=".$_REQUEST['idBloque'];
                    $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                    $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                    $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                    $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                    $ruta.="&totalCreditos=".$totalCreditos;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                    echo "<script>location.replace('".$pagina.$ruta."')</script>";
                    exit;
                }
            $variablesBloque=array($_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$_REQUEST['idBloque']);


            if($_REQUEST['idBloque']==NULL) {
                echo "<script>alert ('Por favor seleccione el bloque para inscribir los estudiantes');</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=registroBloqueEstudiantes";
                $variable.="&opcion=crear";
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                $variable.="&idBloque=".$_REQUEST['idBloque'];
                $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
            }
            else if($_REQUEST['estudiante0']==NULL) {
                echo "<script>alert ('Por favor seleccione estudiantes para este bloque');</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=registroBloqueEstudiantes";
                $variable.="&opcion=crear";
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
            }

            $mensaje='';
            $cantidad_insertados=0;
            $proyectos = $this->consultarProyectos($configuracion,$this->identificacion);
                            
                for($i=0;$i<=500;$i++) {
                    $_REQUEST['estudiante'.$i]=(isset($_REQUEST['estudiante'.$i])?$_REQUEST['estudiante'.$i]:'');
                    if($_REQUEST['estudiante'.$i]!=NULL && $_REQUEST['estudiante'.$i]!='') {

                        $variablesBloque[3]=$_REQUEST['estudiante'.$i];
                        $variablesBloque[4]=$this->periodo[0][0];
                        $variablesBloque[5]=$this->periodo[0][1];
                        if($this->periodo[0][1]==3){
                            $variablesBloque[6]=2;
                        }else{
                            $variablesBloque[6]=1;
                        }
                        $registrado_enBloque = $this->consultarRegistradoBloque($configuracion,$variablesBloque);
                        if(is_array($registrado_enBloque) && $registrado_enBloque[0]['bloque_codEstudiante']){
                            $mensaje.= " El estudiante con código ".$registrado_enBloque[0]['bloque_codEstudiante']." ya se encuentra registrado en un bloque. ";
                        }else{
                            $estudiante_proyecto = $this->consultarEstudianteProyecto($configuracion,$variablesBloque);
                            $proyecto_valido = $this->validarProyecto($proyectos, $_REQUEST['codProyecto']);
                            if(is_array($estudiante_proyecto) && (isset($estudiante_proyecto[0]['EST_COD'])?$estudiante_proyecto[0]['EST_COD']:'') && $proyecto_valido==1){
                                $cadena_sql_bloqueEstudiantes=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"guardar_estudiantes", $variablesBloque);//echo $cadena_sql_bloqueEstudiantes;exit;
                                $resultado_bloqueEstudiantes=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_bloqueEstudiantes,"" );
                                $cantidad_insertados++;
                            }else{
                                $mensaje.= " El código ".$_REQUEST['estudiante'.$i]." no es valido, debe pertenecer al proyecto, al pensum, ser estudiante de créditos y en estado Activo. ";
                            }

                        }
                        unset($variablesBloque[3]);
                    }
                }
                echo "<script>alert ('Se han registrado ".$cantidad_insertados." nuevos estudiantes para el bloque ".$_REQUEST['idBloque'].". ".$mensaje."');</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=registroBloqueEstudiantes";
                $variable.="&opcion=crear";
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                $variable.="&idBloque=".$_REQUEST['idBloque'];
                $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
           
        }else{
                echo "<script>alert ('El pensum, el código del proyecto curricular y el identificador del grupo deben ser numéricos');</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=registroEstudiantesBloqueCoordinador";
                $variable.="&opcion=editar";
                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                $variable.="&idBloque=".$_REQUEST['idBloque'];
                $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
           
        }
    }

    function editarBloque($configuracion)
        {
        $variablesBloque=array($_REQUEST['planEstudio'],$_REQUEST['codProyecto'],$_REQUEST['idBloque'], $this->periodo[0][0],$this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"bloque_publicado",$variablesBloque);//echo $cadena_sql;exit;
        $resultado_bloquePublicado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        if($resultado_bloquePublicado[0][0]=='1')
            {
                echo "<script>alert('El bloque ".$_REQUEST['idBloque']." ya se encuentra publicado, no se pueden modificar datos')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroBloqueEstudiantes";
                $ruta.="&opcion=crear";
                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                $ruta.="&totalCreditos=".$totalCreditos;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                echo "<script>location.replace('".$pagina.$ruta."')</script>";
                exit;
            }

        $variables[0]=$_REQUEST['codProyecto'];
        $variables[2]=$_REQUEST['planEstudio'];
        $variables[3]=$_REQUEST['idBloque'];
        $variables[4]=$this->periodo[0][0];
        $variables[5]=$this->periodo[0][1];

        $cadena_sql_registroEstudiante=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"estudiantes_bloquesRegistrados", $variables);//echo $cadena_sql_registroEstudiante;
        $resultado_registroEstudiante=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEstudiante,"busqueda" );

        if($this->periodo[0][1]=='3')
            {
                $perEst=$this->periodo[0][1]-1;
            }
            else
            {
              $perEst=$this->periodo[0][1];
            }
        $variable[0]=$_REQUEST['codProyecto'];
        $variable[1]=$_REQUEST['planEstudio'];
        $variable[2]=$this->periodo[0][0];
        $variable[3]=$perEst;

        $cadena_sql_estudiantes=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"estudiantes_carrera",$variable);//echo $cadena_sql_estudiantes;exit;
        $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_estudiantes,"busqueda" );

        ?>
<table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
    <tr>
        <td class="centrar" colspan="4">
            <font size="2">SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</font><br>
        </td>
    </tr>
    <tr>
        <td class="centrar" colspan="4">
            <font size="2"><?echo $_REQUEST['nombreProyecto']?></font><br>
            <font size="2">ESTUDIANTES REGISTRADOS BLOQUE <?echo $variables[3]?></font>
            <hr noshade class="hr">

        </td>
    </tr>
    <tr>
        <td class="centrar" colspan="2" width="50%">
            <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroEstudiantesBloqueCoordinador";
                $ruta.="&opcion=nuevosEstudiantes";
                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

            ?>
                <a href="<?= $pagina.$ruta ?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/asociar.png" width="35" height="35" border="0"><br><font size="2">Asociar Estudiantes</font>
                </a>
            
        </td>
        <td class="centrar" colspan="2" width="50%">
            <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroEstudiantesBloqueCoordinador";
                $ruta.="&opcion=borrar";
                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

            ?>
                <a href="<?= $pagina.$ruta ?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/desasociar.png" width="35" height="35" border="0"><br><font size="2">Desasociar Estudiantes</font>
                </a>
        </td>
    </tr>
    <tr class="cuadro_brownOscuro centrar">
        <td>
            N&uacute;mero
        </td>
        <td>
            C&oacute;digo Estudiante
        </td>
        <td>
            Nombre Estudiante
        </td>

    </tr>
        <?
        $k=1;
        for($i=0;$i<count($resultado_estudiantes);$i++)
        {
            for($j=0;$j<count($resultado_registroEstudiante);$j++)
            {
                if($resultado_estudiantes[$i][0]==$resultado_registroEstudiante[$j][3])
                    {
                    ?><tr class="cuadro_plano centrar">
                        <td class="cuadro_plano centrar">
                                    <?echo $k?>
                        </td>
                        <td class="cuadro_plano centrar">
                                        <?echo $resultado_estudiantes[$i][0]?>
                        </td>
                        <td class="cuadro_plano">
                                        <?echo htmlentities($resultado_estudiantes[$i][1])?>
                        </td>
                      </tr>
                      <?$k++;
                        break;
                    }
             }

        }
                    ?>
    <tr>
    <hr noshade class="hr">
    </tr>

            <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroBloqueEstudiantes";
                $ruta.="&opcion=crear";
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);

            ?>
    <tr class="centrar">
        <td colspan="3">
            <a href="<?= $pagina.$ruta ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="35" height="35" border="0"><br>
                <font size="2"><b>Regresar</b></font>
            </a>
        </td>
    </tr>

</table>
        <?
    }

    function borrarBloqueEstudiantes($configuracion)
        {

        $variablesBloque=array($_REQUEST['planEstudio'],$_REQUEST['codProyecto'],$_REQUEST['idBloque'], $this->periodo[0][0], $this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"bloque_publicado",$variablesBloque);//echo $cadena_sql;exit;
        $resultado_bloquePublicado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        if($resultado_bloquePublicado[0][0]=='1')
            {
                echo "<script>alert('El bloque ".$_REQUEST['idBloque']." ya se encuentra publicado, no se pueden modificar datos')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroBloqueEstudiantes";
                $ruta.="&opcion=crear";
                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                $ruta.="&totalCreditos=".$totalCreditos;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                echo "<script>location.replace('".$pagina.$ruta."')</script>";
                exit;
            }
            if($this->periodo[0][1]=='3')
            {
                $perEst=$this->periodo[0][1]-1;
            }
            else
            {
              $perEst=$this->periodo[0][1];
            }
            $variable[0]=$_REQUEST['codProyecto'];
            $variable[1]=$_REQUEST['planEstudio'];
            $variable[2]=$this->periodo[0][0];
            $variable[3]=$perEst;

            $cadena_sql_estudiantes=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"estudiantes_carrera",$variable);//echo $cadena_sql_estudiantes;exit;
            $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_estudiantes,"busqueda" );

        ?>
<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
    <table class='contenidotabla centrar' border="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
        <tr align="center">
            <td class="centrar" colspan="10">
                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            </td>
        </tr>
        <tr>
            <td class="izquierda" colspan="10">
        <?
        echo "<b>PROYECTO CURRICULAR: </b>".$variable[0]." - ".strtoupper($_REQUEST['nombreProyecto']);
        echo "<br><b>PLAN DE ESTUDIOS: </b>".$variable[1];
        echo "<br><b>BLOQUE: </b>".$_REQUEST['idBloque'];
        ?>
                <hr noshade class="hr">
            </td>
        </tr>
        <?if(is_array($resultado_estudiantes)) {?>
        <tr class="cuadro_color centrar">
            <td colspan="5">
                <h4>SELECCIONE LOS ESTUDIANTES QUE DESEA DESASOCIAR DE ESTE BLOQUE</h4>
                <hr noshade class="hr">
            </td>
        <tr>
            <td colspan="5" class="centrar">
                <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                SELECCIONAR LOS
                <select id="algunos" style="width:50" onchange="javascript:seleccionCheck(document.getElementById('seleccionados'),'registroEstudiantesBloqueCoordinador',document.getElementById('algunos').value);"
                        <option value="0">0</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                </select> PRIMEROS ESTUDIANTES
            </td>
        </tr>
        <tr class="cuadro_color centrar">
            <td width="10%">
                N&Uacute;MERO
            </td>
            <td width="10%">
                PROYECTO CURRICULAR
            </td>
            <td width="20%">
                C&Oacute;DIGO ESTUDIANTE
            </td>
            <td width="40%">
                NOMBRE ESTUDIANTE
            </td>
            <td width="10%">
                <script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
                TODOS
                <br>
                <input align="center" type=checkbox id="seleccionados" name="seleccionados" value="seleccionado" onclick="javascript:todos(this,'registroEstudiantesBloqueCoordinador');">
            </td>
        </tr>
            <?$k=1;
            for($i=0;$i<count($resultado_estudiantes);$i++) {
                $variable[2]=$resultado_estudiantes[$i][0];
                $variable[3]=$_REQUEST['idBloque'];
                $variable[4]=$this->periodo[0][0];
                $variable[5]=$this->periodo[0][1];
                $cadena_sql_estudiantesReg=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"estudiantes_bloquesSeleccionado",$variable);//echo $cadena_sql_estudiantesReg;exit;
                $resultado_estudiantesRegistrados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_estudiantesReg,"busqueda" );

                if(is_array($resultado_estudiantesRegistrados))
                    {
                    ?>
        <tr class="cuadro_plano">
            <td width="20" class="centrar">
                            <?echo $k?>
            </td>
            <td width="20" class="centrar">
                            <?echo $resultado_estudiantes[$i][2]?>
            </td>
            <td width="30" class="centrar">
                    <?echo $resultado_estudiantes[$i][0]?>
            </td>
            <td width="40">
                    <?echo htmlentities($resultado_estudiantes[$i][1])?>
            </td>
            <td width="20" class="centrar">
                <input type="checkbox" name="estudiante<?echo $i?>" value="<?echo $resultado_estudiantes[$i][0]?>">

            </td>
        </tr>
                    <?$k++;
                                }

            }

            ?>
        <tr class="cuadro_plano centrar">
            <td class="centrar" width="50%" colspan="3">
                    <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=registroEstudiantesBloqueCoordinador";
                        $ruta.="&opcion=editar";
                        $ruta.="&idBloque=".$_REQUEST['idBloque'];
                        $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                        $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                        $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $ruta=$this->cripto->codificar_url($ruta,$configuracion);

                    ?>
                <a href="<?= $pagina.$ruta ?>">
                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="35" height="35" border="0"><br><font size="2">Regresar</font>
                </a>
            </td>
            <td width="50%" colspan="3">
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                <input type="hidden" name="planEstudio" value="<?echo $_REQUEST['planEstudio']?>">
                <input type="hidden" name="idBloque" value="<?echo $_REQUEST['idBloque']?>">
                <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST['nombreProyecto']?>">
                <input type="hidden" name="opcion" value="borrarSeleccionados">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input type='image' src="<?echo $configuracion['site'].$configuracion['grafico']?>/desasociar.png" width="35" height="35" border="0" ><br><font size="2">Desasociar<br>Seleccionados</font>

            </td>
        </tr><?}else {
            ?><tr>
            <td class="cuadro_plano">
                No existen estudiantes registrados para el periodo acad&eacute;mico vigente
            </td>
        </tr><?
        }?>
    </table>
</form><?
            }

    function borrarSeleccionados($configuracion)
        {

        $variablesBloque=array($_REQUEST['planEstudio'],$_REQUEST['codProyecto'],$_REQUEST['idBloque'], $this->periodo[0][0], $this->periodo[0][1]);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"bloque_publicado",$variablesBloque);//echo $cadena_sql;exit;
        $resultado_bloquePublicado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

        if($resultado_bloquePublicado[0][0]=='1')
            {
                echo "<script>alert('El bloque ".$_REQUEST['idBloque']." ya se encuentra publicado, no se pueden modificar datos')</script>";
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $ruta="pagina=registroBloqueEstudiantes";
                $ruta.="&opcion=crear";
                $ruta.="&idBloque=".$_REQUEST['idBloque'];
                $ruta.="&codProyecto=".$_REQUEST['codProyecto'];
                $ruta.="&planEstudio=".$_REQUEST['planEstudio'];
                $ruta.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                $ruta.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                $ruta.="&totalCreditos=".$totalCreditos;

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $ruta=$this->cripto->codificar_url($ruta,$configuracion);
                echo "<script>location.replace('".$pagina.$ruta."')</script>";
                exit;
            }

        $variablesBloque=array($_REQUEST['codProyecto'],$_REQUEST['planEstudio'],$_REQUEST['idBloque']);

        if($_REQUEST['idBloque']==NULL) {
            echo "<script>alert ('Por favor seleccione el bloque para desasociar los estudiantes');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=registroBloqueEstudiantes";
            $variable.="&opcion=crear";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudio=".$_REQUEST['planEstudio'];
            $variable.="&idBloque=".$_REQUEST['idBloque'];
            $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }
        else if($_REQUEST['estudiante0']==NULL) {
            echo "<script>alert ('Por favor seleccione estudiantes para este bloque');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=registroBloqueEstudiantes";
            $variable.="&opcion=crear";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudio=".$_REQUEST['planEstudio'];
            $variable.="&idBloque=".$_REQUEST['idBloque'];
            $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
        }

           
            for($i=0;$i<=400;$i++) {
                $_REQUEST['estudiante'.$i]=(isset($_REQUEST['estudiante'.$i])?$_REQUEST['estudiante'.$i]:'');
                if($_REQUEST['estudiante'.$i]!=NULL && $_REQUEST['estudiante'.$i]!='') {

                    $variablesBloque[3]=$_REQUEST['estudiante'.$i];
                    $cadena_sql_bloqueEstudiantes=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"borrar_estudiantes", $variablesBloque);
                    $resultado_bloqueEstudiantes=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_bloqueEstudiantes,"" );
                    unset($variablesBloque[3]);
                }
            }

            echo "<script>alert ('Se han desasociado los estudiantes seleccionados para el bloque ".$_REQUEST['idBloque']."');</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=registroEstudiantesBloqueCoordinador";
            $variable.="&opcion=editar";
            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
            $variable.="&planEstudio=".$_REQUEST['planEstudio'];
            $variable.="&idBloque=".$_REQUEST['idBloque'];
            $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";
           

    }

    function consultarRegistradoBloque($configuracion,$variablesBloque){
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"estudiante_registrado_bloque", $variablesBloque);
            return $resultado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    }
    
    function consultarBloquePublicado($configuracion,$variablesBloque){
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"bloque_publicado",$variablesBloque);
            return $resultado_bloquePublicado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

    }
    
    function consultarEstudianteProyecto($configuracion,$variablesBloque){
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"estudiante_proyecto",$variablesBloque);
            return $resultado_bloquePublicado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
    }

    function consultarProyectos($configuracion,$identificacion){
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"proyectos_curriculares",$identificacion);
            return $resultado_bloquePublicado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
    }
    
    function validarProyecto($proyectos, $codProyecto){
        $valido=0;
        foreach ($proyectos as $key => $proyecto) {
            if($proyecto['CRA_COD']==$codProyecto){
               $valido=1; 
               break;
            }
        }
        return $valido;
    }
    
    }


?>
