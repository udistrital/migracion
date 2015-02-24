<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/multiConexion.class.php");

class funciones_registroSolicitudCertificadoOficial extends funcionGeneral {
    //Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $tipoUser=$_REQUEST['clase'];

        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;
        //Conexion ORACLE
        //$this->accesoOracle=$this->conectarDB($configuracion,"");
        $conexion=new multiConexion();
        $this->accesoOracle=$this->conectarDB($configuracion, "oraclesga");
        //$this->accesoOracle=$this->conectarDB($configuracion, "oraclesga");
        $acceso=$this->accesoOracle;

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion DB
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion

        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "usuario");

        $this->formulario="registroSolicitudCertificadoOficial";
        $this->verificar="control_vacio(".$this->formulario.",'codigo')";
        $this->verificar.="&& verificar_numero(".$this->formulario.",'codigo')";
        $this->verificar.="&& control_vacio(".$this->formulario.",'nombre')";
        $this->verificar.="&& longitud_cadena(".$this->formulario.",'nombre', 6)";
        //$this->verificar="control_vacio(".$this->formulario.",'fecha')";



    }

    //Función que arma el formulario de nuevos registors de bitácora.
    function nuevoRegistro($configuracion,$conexion) {

        $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_sesion")."<br>";
        $registroUsuario=$this->verificarUsuario(); //se comenta cuando no hay sesion
        $contador=0;
        $tab=1;
        $tipoUser=$_REQUEST['clase'];
        switch ($tipoUser) {
            case "51":
                $datosUsuario="datosEstudiante";

                ?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
    <!--Este formulario envía la información a index, es decir al bloque-->
    <table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
        <tr>
            <td>
                <table class="formulario" align="center">
                    <tr>
                        <td class="cuadro_brown" >
                        </td>
                    </tr>
                </table>
                <br>
                <table class="formulario" align="center">
                    <tr  class="bloquecentralencabezado">
                        <td colspan="2" align="center">
                            <p><span class="texto_negrita">CREAR SOLICITUD DE CERTIFICADO DE CALIFICACIONES</span></p>
                        </td>
                    </tr>
                <?/*<tr>
							<td colspan="3" rowspan="1"><br>Ingreso de Solicitud de Certificado<hr class="hr_subtitulo"></td>
						</tr>*/?>
                    <tr>
                        <td colspan="3">
                            <table class="formulario" align="center">
                                <tr>
                                    <td  class="centrar texto_negrita" colspan="2">
                <?
                //unset($valor);
                if($this->usuario) {
                    $usuario=$this->usuario;
                }
                                                        else {
                                                            $usuario=$this->identificacion;
                                                        }
                                                        //Las cadenas siguientes se utilizan cuando hay una sesion


                                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, $datosUsuario,$usuario);
                                                        $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle,$cadena_sql,"busqueda");
                                                        //var_dump($resultado);
                                                        //exit;
                                                        if(is_array($resultado)) {
                                                            echo "Usuario: ".$resultado[0][2];
                                                            $valor[0]=$resultado[0][0];
                                                            $id_usuario=$resultado[0][0];
                                                            $codigo=$resultado[0][0];
                                                            $nombre=$resultado[0][2];
                                                            $facultad=$resultado[0][6];

                                                        }
                                                        else {
                                                            echo "Imposible mostrar los datos de registro";

                                                        }
                                                        //echo "Usuario :".$registroUsuario[0][1]
                                                        ?>
                                    </td>
                                    <td  class="centrar texto_negrita" colspan="2">
                                                        <?$fecha = time ();
                                                        echo "Fecha: ". date ( "d/m/Y", $fecha); ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                                                        <?//encabezados de espacios academicos?>

                <table class="formulario" align="center">
                    <tr align="center">
                        <td>
                            C&oacute;digo:<?echo $resultado[0][0]?>
                        </td>
                        <!--<td>
                            <font color="red">*</font>Fecha de solicitud (DD/MM/AAAA):
							</td>-->
                        <td>
                            Seleccione el tipo de Certificado:
                <?//genera un cuadro de selección para el tipo de certificado
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
                $html=new html();
                $cadenas=new cadenas();

                $busqueda="SELECT ";
                $busqueda.="id_tipo, ";
                                            $busqueda.="tipo_nombre ";
                                            $busqueda.="FROM ";
                                            $busqueda.="sga_tipo_certificado ";
                                            $resultado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $busqueda, "busqueda");

                                            $mi_cuadro=$html->cuadro_lista($resultado,'tipo',$configuracion,1,0,FALSE,$tab++,"id_tipo");
                                            //$mi_lista=$cadenas->formatohtml($mi_cuadro);
                                            echo $mi_cuadro;
                                            ?>

                        </td>
                    </tr>
                                            <?//primer espacio academico?>
                </table>

                <table class="formulario" align="center">
                    <tr align='center'>
                        <td colspan="9">
                            <table class="tablaBase">
                                <tr>
                                    <td align="center">
                                        <input type='hidden' name='action' value='<? echo $this->formulario ?>'>
                                        <input type='hidden' name='codigo' value='<? echo $id_usuario ?>'>
                                        <input type='hidden' name='clase' value='<? echo $tipoUser ?>'>
                                        <input type='hidden' name='opcion' value='nuevo'>
                                        <input type='hidden' name='nombre' value='<?echo $nombre?>'>
                                        <input type="hidden" name='facultad' value="<?echo $facultad?>">
                                        <input type="hidden" name="estado" value="1">
                                        <input value="Enviar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
                                    </td>
                                    <td align="center">
                                        <input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
                <?
                break;

            case "83":
                $datosUsuario="datosUsuario";
//echo $this->usuario;

                ?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
    <!--Este formulario envía la información a index, es decir al bloque-->
    <table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
        <tr>
            <td>
                <table class="formulario" align="center">
                    <tr>
                        <td class="cuadro_brown" >
                        </td>
                    </tr>
                </table>
                <br>
                <table class="formulario" align="center">
                    <tr  class="bloquecentralencabezado">
                        <td colspan="3" align="center">
                            <p><span class="texto_negrita">INGRESAR SOLICITUD DE CERTIFICADO DE CALIFICACIONES</span></p>
                        </td>
                    </tr>
                <?/*<tr>
							<td colspan="3" rowspan="1"><br>Ingreso de Solicitud de Certificado<hr class="hr_subtitulo"></td>
						</tr>*/?>
                    <tr>
                        <td colspan="3">
                            <table class="formulario" align="center">
                                <tr>
                                    <td  class="centrar texto_negrita" colspan="2">
                                    <?
                                    //unset($valor);
                                    if($this->usuario) {
                    $usuario=$this->usuario;
                }
                else {
                    $usuario=$this->identificacion;
                }
                                                        //Las cadenas siguientes se utilizan cuando hay una sesion
                                                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosUsuario",$usuario);//echo $cadena_sql;exit;
                                                        $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                        if(is_array($resultado)) {
                                                            echo "Usuario: ".$resultado[0][1]." ".$resultado[0][2];
                                                            $valor[0]=$resultado[0][0];
                                                            $id_usuario=$resultado[0][0];
                                                            $facultad=$resultado[0][3];
                                                        }
                                                        else {
                                                            echo "Imposible mostrar los datos de registro";

                                                        }

                                                        ?>
                                    </td>
                                    <td  class="centrar texto_negrita" colspan="2">
                                                        <?$fecha = time ();
                                                        echo "Fecha: ". date ( "d/m/Y", $fecha); ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                                                        <?//encabezados de espacios academicos?>

                <table class="formulario" align="center">
                    <tr>
                        <td>
                            <font color="red">*</font>C&oacute;digo:
                        </td>
                        <td>
                            <font color="red">*</font>Nombre del Solicitante:
                        </td>
                        <!--<td>
                            <font color="red">*</font>Fecha de solicitud (DD/MM/AAAA):
							</td>-->
                        <td>
                            <font color="red">*</font>Tipo de Certificado:
                        </td>
                    </tr>
                <?//primer espacio academico?>
                    <tr>
                        <td>
                            <input type='text' name='codigo' size='15' maxlength='20' tabindex='<? echo $tab++ ?>'
                        </td>
                        <td>
                            <input type='text' name='nombre' size='30' maxlength='100' tabindex='<? echo $tab++ ?>'
                        </td>
                            <!--<input type="hidden" name="fecha" value="<?// echo $fecha ?>">
                            <input type='hidden' name='fecha' tabindex='<?// echo $tab++ ?>'-->
                        <td>
                                    <?//genera un cuadro de selección para el tipo de certificado
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
                $html=new html();

                $busqueda="SELECT ";
                $busqueda.="id_tipo, ";
                $busqueda.="tipo_nombre ";
                $busqueda.="FROM ";
                $busqueda.="sga_tipo_certificado ";
                $resultado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $busqueda, "busqueda");
                $mi_cuadro=$html->cuadro_lista($resultado,'tipo',$configuracion,1,0,FALSE,$tab++,"id_tipo");

                                            echo $mi_cuadro;
                                            ?>
                            <input type="hidden" name="estado" value="1">

                        </td>
                    </tr>
                </table>

                <table class="formulario" align="center">
                    <tr align='center'>
                        <td colspan="9">
                            <table class="tablaBase">
                                <tr>
                                    <td align="center">
                                        <input type='hidden' name='action' value='<? echo $this->formulario ?>'>
                                        <input type='hidden' name='id_usuario' value='<? echo $id_usuario ?>'>
                                        <input type='hidden' name='clase' value='<? echo $tipoUser ?>'>											<input type='hidden' name='consecutivo' value='<? echo $consecutivo ?>'>
                                        <input type='hidden' name='opcion' value='nuevo'>
                                        <input type="hidden" name='facultad' value="<?echo $facultad?>">
                                        <input value="Enviar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
                                    </td>
                                    <td align="center">
                                        <input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr class="bloquecentralcuerpo">
                        <td colspan="9" rowspan="1">
								Los campos marcados con <font color="red">*</font> deben ser diligenciados obligatoriamente.<br><br>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</form>




                <?
                break;
        }






    }

    function guardarRegistro ($configuracion,$acceso_db, $accesoGestion) {

        //$unUsuario=$this->verificarUsuario();

        if(!is_array($unUsuario)) {
            //si no han rescatado los datos de la sesion, esto no se muestra (original is_array()
            $valor[0]=$_REQUEST['codigo'];
            $valor[1]=$_REQUEST['nombre'];
            $valor[2]=$_REQUEST['tipo'];
            $valor[3]=$_REQUEST['estado'];
            $valor[4]=$_REQUEST['facultad'];

            $cadena_sql=$this->sql->cadena_sql($configuracion,$accesoGestion, "insertarRegistro",$valor);
            $resultado=$this->ejecutarSQL($configuracion, $accesoGestion, $cadena_sql, "");

            if(isset($resultado)) {
                $cadena_sql=$this->sql->cadena_sql($configuracion,$accesoGestion, "consultarMaxId",$valor[0]);
                //echo $cadena_sql;
                //exit;
                // SELECT max(id_solicitud) FROM academico_registro_solicitud;
                $variable=$this->ejecutarSQL($configuracion, $accesoGestion, $cadena_sql, "busqueda");
                $this->redireccionarInscripcion($configuracion,"registroexitoso", $variable[0][0]);
            }
            else {
                exit;
            }
        }
        else {
            echo "<table align=center><tr><td><h3>IMPOSIBLE GUARDAR EL FORMULARIO</h3></td></tr></table>";
        }


    }

    function RegistroExitoso($configuracion, $acceso_db, $accesoGestion, $registro, $total, $variable, $opcion="") {
        //la siguiente cadena se utiliza para rescatar valores de sesion
        $this->nivelUsuario=$this->rescatarValorSesion($configuracion, $acceso_db, "nivelUsuario")."<br>";
        /*la siguiente cadena se utiliza para guardar datos de sesion
                /$this->guardarValorSesion($configuracion, $this->acceso_db, "perritas",12334);
        */


        //$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "consultarMaxId","");
        // SELECT max(id_solicitud) FROM academico_registro_solicitud;
        //$variable=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
        $cadena2_sql=$this->sql->cadena_sql($configuracion,$accesoGestion, "consultarRegistro",$registro);
        $resultado=$this->ejecutarSQL($configuracion, $accesoGestion, $cadena2_sql, "busqueda");
//		echo $cadena2_sql;
//                echo "<br><br>";
//                var_dump($this->accesoGestion);
//                exit;

        //echo "RegistroExitoso"."<br>";
        if(is_array($resultado)) {
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
            $cadenas=new cadenas();
            $tipocert=$resultado[0][4];
            $tipo=$cadenas->formatohtml($tipocert);

            if($resultado[0][7]=='C') {
                $accion="CREAR";
            }
            elseif($resultado[0][7]=='A') {
                $accion="Actualizar";
            }
            elseif($resultado[0][7]=='B') {
                $accion="Borrar";
            }
            echo "<table class='formulario' align='center'>
                                        <br>
				 	<tr  class='bloquecentralencabezado'>
                                            <td colspan='5'align ='center'>
						<p>La solicitud se ha registrado exitosamente con los siguientes datos:</p>
                                            </td>
					</tr>
                                </table>
					<br>
                                        <table class='formulario' align='center'>
					<tr  class='cuadro_color'>
                                            <td colspan='5' align='center'>
						<p><span class='texto_negrita'>Certificado de calificaciones para c&oacute;digo ".$resultado[0][0]."</span></p>
                                            </td>
					</tr>
					<tr class='cuadro_color'>
                                            <td align='center'>
						N&uacute;mero de Solicitud
                                            </td>
                                            <td align='center'>
						Nombre Solicitante
                                            </td>
                                            <td align='center'>
						Fecha
                                            </td>
                                            <td align='center'>
						Tipo de Certificado
                                            </td>
                                            <td align='center'>
						Estado de la Solicitud
                                            </td>
					</tr>
					<tr>
						<td align='center'>
							".$registro."
						</td>
						<td align='center'>
							".$resultado[0][1]."
						</td>
						<td align='center'>
							".date( 'd/m/Y', $resultado[0][2])."
						</td>
						<td align='center'>
							".$tipo."
						</td>
                                                <td align='center'>
							".$resultado[0][3]."
						</td>
                                        </tr>
				 </table>";
            ?><tr class="bloquelateralcuerpo">
    <td align="center">
        <a href="<?
            $variable="pagina=adminSolicitudCertificado";
            $variable.="&opcion=".$this->nivelUsuario;
            $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable=$this->cripto->codificar_url($variable,$configuracion);
            echo $indice.$variable;

            ?>">  Volver </a>
    </td>
</tr>
            <?


        }
        else {
            echo "Imposible mostrar los datos de registro";
        }
        //$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consutaTiprel",$valor);
        //$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                //$totreg=count($resultado1);

            }


            function datosUsuario() {
                   $registro=$this->verificarUsuario();
        if(is_array($registro)) {
            ?><table class="formulario" align="center">
    <tr  class="bloquecentralencabezado">
        <td colspan="2">
            <p><span class="texto_negrita">Datos Registrados del Estudiante</span></p>
        </td>
    </tr>
    <tr >
        <td>
								Nombre:
        </td>
        <td class="texto_negrita">
            <? echo $registro[0][2] ?>
        </td>
    </tr>
    <tr >
        <td>
								identificaci&oacute;n:
        </td>
        <td class="texto_negrita">
            <? echo $registro[0][1] ?>
        </td>
    </tr>
    <tr >
        <td>
								Tipo de Documento:
        </td>
        <td class="texto_negrita">
            <? echo $registro[0][6] ?>
        </td>
    </tr>
    <tr >
        <td>
								G&eacute;nero:
        </td>
        <td class="texto_negrita">
            <? echo $registro[0][7] ?>
        </td>
    </tr>
</table>
            <?
                    }
        else {
            return false;

        }

    }


    function confirmarRegistro($configuracion,$accion) {
        //para confirmar el registro o borrado de datos
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "inscripcionBorrador","");
        $registro=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");

        if(is_array($registro)) {
            $this->htmlConfirmar($configuracion,$accion,$registro);
                    }
        else {
            echo "Imposible mostrar los datos de Inscripci&oacute;n";
        }

    }


    function verificarUsuario() {
        //Verificar existencia del usuario

        switch ($tipoUser) {
            case "51":
                $datosUsuario="datosEstudiante";
                break;

            case "83":
                $datosUsuario="datosUsuario";
                break;
        }

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "datosEstudiante",$this->identificacion);
        $unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        if(is_array($unUsuario)) {
            return $unUsuario;
        }
        else {
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "datosEstudiante",$this->usuario);
            $unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            if(is_array($unUsuario)) {
                return $unUsuario;
            }
            else {
                return false;
            }

        }

    }

    function nuevoUsuario($configuracion,$acceso_db, $accesoOracle) {

        $unUsuario=$this->verificarUsuario();
        if(is_array($unUsuario)) {

            //Valores a ingresar
            if(isset($_REQUEST['codigo'])) {
                $elUsuario=$_REQUEST['codigo'];
            }
            else {
                $elUsuario=$_REQUEST['registro'];
            }


            $valor[0]=$elUsuario;
            $valor[1]=$_REQUEST['nombre'];
            $valor[2]=$_REQUEST['apellido'];
            if($unUsuario[0][7]=="M") {
                $valor[3]=1;
            }
            else {
                $valor[3]=0;
            }

            for($i=1;$i<5;$i++) {
                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "eliminarBorrador".$i,$valor);
                $resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");
            }

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "insertarBorrador",$valor);
            $resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");


            unset($valor);
            $valor[0]=$elUsuario;
            $valor[1]=$_REQUEST['direccion'];
            $valor[2]=$_REQUEST['pais'];
            $valor[3]=$_REQUEST['region'];
            $valor[4]=$_REQUEST['ciudad'];
            $valor[5]=$_REQUEST['telefono'];
            $valor[6]=$_REQUEST['celular'];
            $valor[7]=$_REQUEST['correo'];

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "insertarBorradorDatos",$valor);
            $resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");

            unset($valor);
            $valor[0]=$elUsuario;
            $valor[1]=$unUsuario[0][1];
            $valor[2]=$_REQUEST['ciudadIdentificacion'];
            $valor[3]=$_REQUEST['id_tipo_documento'];

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "insertarBorradorDocumento",$valor);
            $resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");

            unset($valor);
            $valor[0]=$elUsuario;
            $valor[1]=$_REQUEST['tituloTrabajo'];
            $valor[2]=$_REQUEST['directorTrabajo'];
            $valor[3]=$_REQUEST['tipoTrabajo'];

            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "insertarBorradorinscripcionGrado",$valor);
            $resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");

            if($resultado==TRUE) {
                if(!isset($_REQUEST["admin"])) {
                    //enviar_correo($configuracion);
                    if(isset($_REQUEST['codigo'])) {

                        reset($_REQUEST);
                        while(list($clave,$value)=each($_REQUEST)) {
                            unset($_REQUEST[$clave]);

                        }
                        $this->redireccionarInscripcion($configuracion, "confirmacionCoordinador",$valor[0]);
                    }
                    else {

                        reset($_REQUEST);
                        while(list($clave,$value)=each($_REQUEST)) {
                            unset($_REQUEST[$clave]);

                        }
                        $this->redireccionarInscripcion($configuracion, "confirmacion",$valor[0]);
                    }
                }
                else {

                    $this->redireccionarInscripcion($configuracion,"administracion");

                }
            }
            else {
                exit;
            }
        }
        else {
            echo "<table align=center><tr><td><h3>IMPOSIBLE GUARDAR EL FORMULARIO</h3></td></tr></table>";
        }
    }


    function redireccionarInscripcion($configuracion, $opcion, $valor="") {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        unset($_REQUEST['action']);
        $cripto=new encriptar();
        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        switch($opcion) {
            case "registroexitoso": //envia a la misma pagina con parametro exito
                $variable="pagina=solicitudNuevaCertificado";
                $variable.="&opcion=exito";
                $variable.="&registro=".$valor;
                break;

            case "cancelar":
                $variable="pagina=adminSolicitudCertificado";
                $variable.="&opcion=".$valor;
                break;

            case "administracion":
                $variable="pagina=admin_usuario";
                $variable.="&accion=1";
                $variable.="&hoja=0";
                break;

            case "confirmacion":
                $variable="pagina=confirmacionInscripcionGrado";
                $variable.="&opcion=confirmar";
                $variable.="&identificador=".$valor;
                break;

            case "formgrado":
                $variable="pagina=registro_inscripcionGrado";
                $variable.="&opcion=verificar";
                $variable.="&xajax=pais|region|paisFormacion|regionFormacion";
                $variable.="&xajax_file=inscripcion";
                break;

            case "confirmacionCoordinador":
                $variable="pagina=confirmacionInscripcionCoordinador";
                $variable.="&opcion=confirmar";
                $variable.="&sinCodigo=1";
                $variable.="&identificador=".$valor;
                break;

            case "corregirUsuario":
                $variable="pagina=registroInscripcionCorregir";
                $variable.="&opcion=corregir";
                $variable.="&identificador=".$valor;
                break;

            case "exitoInscripcion":
                if(isset($_REQUEST['sinCodigo'])) {
                    $variable="pagina=exitoInscripcionSecretario";
                }
                else {
                    $variable="pagina=exitoInscripcion";
                }

                $variable.="&identificador=".$valor;
                $variable.="&opcion=verificar";
                break;

            case "principal":
                $variable="pagina=index";
                break;



        }

        $variable=$cripto->codificar_url($variable,$configuracion);
        echo "<script>location.replace('".$indice.$variable."')</script>";
        exit();
    }


}


?>