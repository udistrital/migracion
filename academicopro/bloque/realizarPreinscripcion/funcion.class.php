<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/multiConexion.class.php");


class funciones_realizarPreinscripcion extends funcionGeneral
{
        //Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/administrarModulo.class.php");
		$this->tema=$tema;
		$this->sql=$sql;
                $this->cripto=new encriptar;
		$this->administrar=new administrarModulo();
                $this->administrar->administrarModuloSGA($configuracion, '2');
                //Conexion ORACLE
		$conexion=new multiConexion();
                $this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");
                //Conexion General
                $this->acceso_db=$this->conectarDB($configuracion,"");
                //var_dump($this->acceso_db);
                //Datos de sesion
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		//Conexion DB SGA
                $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

		$this->formulario="realizarPreinscripcion";
		$this->verificar="control_vacio(".$this->formulario.",'planEstudio', 0, 500)";
                $this->forma="realizarPreinscripcion";



	}

    function mensaje ($configuracion)
    {
echo "
<tr><td>Señores<br>
Coordinadores Proyectos Curriculares<br>
<br></td></tr>
<tr><td>Tomando en cuenta que no se han creado la totalidad de los horarios de algunos espacios acad&eacute;micos en algunos Proyectos Curriculares, en especial aquellos que son comunes como las c&aacute;tedras, les informamos que ma&ntilde;ana se activar&aacute; despu&eacute;s de las 8:00 am la opci&oacute;n de preescripci&oacute;n, ya que los horarios son el insumo para que se genere efectivamente este proceso. </td></tr>";

    }


	//Función que arma el formulario de nuevos registors de bitácora.
	function seleccionarProyecto($configuracion,$conexion)
	{

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
                $html=new html();
                $cadenas=new cadenas();

                $cadena_sql=$this->sql->cadena_preins_sql($configuracion,$this->accesoOracle, "planEstudio",$this->usuario);
                $planes_1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
                if(!$planes_1)
                {
                    echo "<script>alert('En este momento no se puede establecer conexión. Intente nuevamente y si el problema persiste, comuníquese con nosotros a través del correo ".$configuracion['correo']." o al tel. 3238400 ext 1110');</script>";
                    echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
                    exit;

                }

                $i=0;
                    while(isset($planes_1[$i][0]))
                    {
                            $planes[$i][0]=$planes_1[$i][0].'-'.$planes_1[$i][1];
                            $planes[$i][1]=$planes_1[$i][1].' - '.$planes_1[$i][2];
                    $i++;
                    }

//                //Genera un formulario para pedir datos de la pre-inscripción

                ?>
            <table class='formulario' align='center'>
                <tr  class='bloquecentralencabezado'>
                    <td align='center'>
                        <p>Sistema de Gesti&oacute;n Acad&eacute;mica</p>
                    </td>
                </tr>
                <tr  class='bloquecentralencabezado'>
                    <td align='center'>
                        <p>Proceso de Preinscripci&oacute;n para estudiantes en Cr&eacute;ditos</p>
                    </td>
                </tr>
            </table>
            <br>
            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
                <table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
                    <tr>
                        <td>
                            <table class='formulario' align='center'>
                                <tr  class='bloquecentralencabezado'>
                                    <td align='center'>
                                        <p>Seleccione el plan de estudios</p>
                                    </td>
                                </tr>
                            </table>
                            <table class='formulario' align='center'>
                                <tr class='cuadro_color'>
                                    <td align='center'>
                                    <?
                                        $tab=1;
                                        $mi_cuadro=$html->cuadro_lista($planes,'planEstudio',$configuracion,0,0,FALSE,$tab++,"id_plan");
                                        echo $mi_cuadro;
                                    ?>
                                    </td>
                                </tr>
                           </table>
                            <br>
                            <table class="formulario">
                                <tr class='bloquecentralencabezado'>
                                    <td align="center">

                                        Defina los par&aacute;metros para la Preinscripci&oacute;n
                                        <br>
                                    </td>
                                </tr>
                            </table>
                            <table class='formulario' align='center'>
                                <tr class='bloquecentralencabezado'>
                                    <td colspan="4" align='center'>
                                        <p>Seleccione el orden de los estudiantes</p>
                                    </td>
                                    <!--<td align='center'>
                                        <p>N&uacute;mero m&aacute;ximo de semestres</p>
                                    </td>-->
                                    <td align='center'>
                                        <p>A&ntilde;o</p>
                                    </td>
                                    <td align='center'>
                                        <p>Per&iacute;odo Acad&eacute;mico</p>
                                    </td>
                                </tr>
                                <tr class='cuadro_color'>
                                    <td colspan="4" align='center'>C&oacute;digo
                                    <?
                                        $orden[0][0]='ASC';
                                        $orden[0][1]='Ascendente';
                                        $orden[1][0]='DESC';
                                        $orden[1][1]='Descendente';
                                        $mi_orden=$html->cuadro_lista($orden,'orden',$configuracion,1,0,FALSE,$tab++,"id_plan");
                                        echo $mi_orden;
                                    ?>
                                    </td>
                                    <!--<td align='center'>
                                    <?
                                        /*$i='0';
                                        while ($i <= 10)
                                        {
                                            $numsem[$i][0]=$i;
                                            $numsem[$i][1]=$i;
                                            $i++;
                                        }

                                        $mi_semestre=$html->cuadro_lista($numsem,'semestres',$configuracion,0,0,FALSE,$tab++,"id_plan");
                                        echo $mi_semestre;*/
                                    ?>
                                    </td>-->
                                                        <td align='center'>
                                        <p><?
                                                //calcula el año
                                                $anno = date ('Y',  time ());
                                                $per = date ('m',  time ());
                                                $anno1=$anno+1;
                                                $anio[0][0]=$anno;
                                                $anio[0][1]=$anno;
                                                $anio[1][0]=$anno1;
                                                $anio[1][1]=$anno1;

                                                if($per>9)
                                                {
                                                    $vig=$anno1;
                                                }
                                                else
                                                {
                                                    $vig=$anno;
                                                }

                                            //$mi_anno=$html->cuadro_lista($anio,'anno',$configuracion,$vig,0,FALSE,$tab++,"anno");
                                            echo $vig;

                                            //echo $anno;?>
                                            </p>
                                    </td>
                                    <td align='center'>
                                        <p><?
                                                //calcula el semestre
                                                if($per>5)
                                                {
                                                    $seme=3;
                                                }
                                                elseif($per>9)
                                                {
                                                    $seme=1;
                                                }
                                                else
                                                {
                                                    $seme=1;
                                                }
//                                                $periodo[0][0]=1;
//                                                $periodo[0][1]=1;
//                                                $periodo[1][0]=2;
//                                                $periodo[1][1]=2;
//                                                $periodo[2][0]=3;
//                                                $periodo[2][1]=3;
                                                //$mi_sem=$html->cuadro_lista($periodo,'sem',$configuracion,$seme,0,FALSE,$tab++,"id_sem");
                                                echo $seme;
                                        //echo $sem."<br>";

                                            ?></p>
                                    </td>
                                </tr>
                           </table>

                            <table class="formulario" align="center">
                                <tr align='center'>
                                    <td colspan="4">
                                        <table class="tablaBase">
                                            <tr>
                                                <td align="center">
                                                    <input type='hidden' name='action' value='<? echo $this->formulario ?>'>
                                                    <input type='hidden' name='id_usuario' value='<? echo $this->usuario ?>'>
                                                    <input type='hidden' name='consecutivo' value='<? echo $consecutivo ?>'>
                                                    <input type='hidden' name='opcion' value='nuevo'>
                                                    <input type="hidden" name='anno' value="<?echo $vig;?>">
                                                    <input type="hidden" name='sem' value="<?echo $seme;?>">
                                                    <input value="Ejecutar Preinscripci&oacute;n" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
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
            <br>
            <table class="cuadro_color centrar" width="100%">
                <tr class="cuadro_plano centrar">
                    <th>
                        Observaciones
                    </th>
                </tr>
                <tr class="cuadro_plano">
                    <td>
                        * Recuerde que este proceso &Uacute;nicamente inscribe espacios acad&eacute;micos a estudiantes que los hayan reprobado el per&iacute;odo acad&eacute;mico anterior.
                        <br>
                        * Recuerde que previo al proceso de preinscripci&oacute;n, debe haber creado los horarios correspondientes para estudiantes reprobados.
                        <br>
                        * Si tiene a su cargo m&aacute;s de un plan de estudios, debe realizar la preinscripci&oacute;n para cada uno.
                    </td>
                </tr>
            </table>


<?
        }


   	function parametros ($configuracion,$acceso_db)
	{

        //trae los datos definidos en el formulario de pre-inscripción

		$cra=explode("-", $_REQUEST['planEstudio']);
                $parametro[0]=$cra[0];//COD CRA
                $parametro[1]=$cra[1];//COD PLANEST
                $parametro[2]=$_REQUEST['orden'];//ORDEN
                $parametro[3]=$_REQUEST['semestres'];//NUM SEMESTRES
                $parametro[4]=$_REQUEST['anno'];//AÑO
                $parametro[5]=$_REQUEST['sem'];//PER
                $parametro[6]=$_REQUEST['id_usuario'];//USUARIO
                $parametro[10]="=1";
                $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $this->accesoGestion, "buscarDatosPreinscripcion", $parametro);
                $resultado_borrar=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_borrar, "busqueda");
                //si los datos se publicaron
                if ($resultado_borrar)
                {
                    echo "<script>alert('Los datos de preinscripción de este plan de estudios y proyecto curricular ya han sido publicados. Puede consultarlos desde la Aplicación Académica');</script>";
                    echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
                    exit;
                }
                $parametro[10]="!= 2";
                //busca si existen datos en db de preinscripción anterior
                $cadena_sql_datos=$this->sql->cadena_preins_sql($configuracion,$this->accesoGestion, "buscarDatosPreinscripcion",$parametro);
                $resultado_datos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_datos, "busqueda");
                if($resultado_datos)
                {
                    //si existen, envia a borrar
                    $this->redireccionarProceso($configuracion, "borrarDatos", $parametro);
                }

                //si no existen, guarda datos de parametros en la tabla.
                $cadena_sql=$this->sql->cadena_preins_sql($configuracion,$this->accesoGestion, "parametros",$parametro);

                
                //var_dump($this->accesoGestion);
                $resultado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "");

                //var_dump($resultado);exit;
                //hace un registro en el log de eventos.
                $variablesRegistro=array($this->usuario,date('YmdGis'),'6','Inicia Preinscripcion para el plan de estudios '.$parametro[1], $parametro[4].", ".$parametro[5].", 0, 0, 0, ".$parametro[1].", ".$parametro[0], '');
                $cadena_sql_registroEvento=$this->sql->cadena_preins_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);

                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                if ($resultado==true && $resultado_registroEvento==true)
                {
                    //echo "Comienza a ejecutarse la Pre-inscripci&oacute;n<br>";
                    $this->redireccionarProceso($configuracion, "preinscribir", $parametro);

                }
                else
                {
                    echo "<script>alert('En este momento no se puede realizar el proceso. Si el problema persiste, comuníquese con nosotros a través del correo ".$configuracion['correo']."');</script>";
                    echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
                    exit;

                }
                        exit;

			if(isset($resultado))
			{
                                $this->redireccionarInscripcion($configuracion,"registroexitoso", $resultado);

			}
			else
			{
				$this->redireccionarInscripcion($configuracion,"no_resultados", $resultado);
			}



	}


        function datos($configuracion, $conexionGestion)
        {
                //busca si existen datos en db de preinscripción anterior
                $parametro[0]=$_REQUEST['carrera'];
                $parametro[1]=$_REQUEST['planEstudio'];
                $parametro[2]=$_REQUEST['orden'];
                $parametro[3]=$_REQUEST['semestres'];
                $parametro[4]=$_REQUEST['anno'];
                $parametro[5]=$_REQUEST['periodo'];

               ?>
                <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->forma?>'>
                    <table class='formulario' align='center'>
                        <tr  class='bloquecentralencabezado'>
                            <td align='center'>
                                <p>Actualmente existen datos registrados de un proceso previo de Preinscripci&oacute;n <br>
                                    para este Plan de Estudios</p>
                            </td>
                        </tr>
                        <tr class='cuadro_color'>
                            <td align='center'>
                                <p>Si desea <font class='bloquecentralencabezado'>borrar</font> los datos e iniciar un nuevo proceso, seleccione
                                    <font class='bloquecentralencabezado'>Continuar</font>, <br>
                                    de lo contrario seleccione <font class='bloquecentralencabezado'>Cancelar</font></p>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
                        <tr>
                            <td>
                                <table class="tablaBase">
                                    <tr>
                                        <td align="center">
                                            <input type='hidden' name='action' value='<? echo $this->forma ?>'>
                                            <input type='hidden' name='id_usuario' value='<? echo $id_usuario ?>'>
                                            <input type='hidden' name='datos' value='nuevo'>
                                            <input type="hidden" name='carrera' value="<?echo $parametro[0]?>">
                                            <input type="hidden" name='planEstudio' value="<?echo $parametro[1]?>">
                                            <input type="hidden" name='anno' value="<?echo $parametro[4]?>">
                                            <input type="hidden" name='periodo' value="<?echo $parametro[5]?>">
                                            <input value="Continuar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit"/><br>
                                        </td>
                                        <td align="center">
                                            <input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </form>


                <?


        }


	function borrarDatosPrevios($configuracion, $conexionGestion, $conexionOracle)
        {
            //borra datos previos de preinscripcion
            $parametro[0]=$_REQUEST['carrera'];
            $parametro[1]=$_REQUEST['planEstudio'];
            $parametro[4]=$_REQUEST['anno'];
            $parametro[5]=$_REQUEST['periodo'];
            $parametro[10]="=1";
            $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "buscarDatosPreinscripcion", $parametro);
            $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "busqueda");
            //si los datos se publicaron
            if ($resultado_borrar)
            {
                echo "<script>alert('Los datos de su preinscripción ya han sido registrados. Puede consultarlos desde la Aplicación Académica');</script>";
                echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
                exit;

                //busca registros de cupos antes de la preinscripcion
                $cadena_sql_cupos=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "buscarRegistrosCupos", $parametro);
                $resultado_cupos=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_cupos, "busqueda");
                $res=0;
                while ($resultado_cupos[$res][0])
                {
                    //busca el valor actual de inscritos en el grupo en oracle
                    $cadena_sql_buscarOracle=$this->sql->cadena_preins_sql($configuracion, $conexionOracle, "buscarOracleCupos", $resultado_cupos[$res]);
                    $resultado_buscarOracle=$this->ejecutarSQL($configuracion, $conexionOracle, $cadena_sql_buscarOracle, "busqueda");
                    //si no puede consultar los cupos en oracle
                    if(!$resultado_buscarOracle)
                    {
                        echo "<script>alert('En este momento no se puede realizar el borrado de datos. Comuníquese con nosotros a través del correo ".$configuracion['correo']." o al 3238400 ext. 1110');</script>";
                        echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
                        exit;
                    }
                    //realiza la resta entre el número de cupos actuales de oracle y los que utilizo la preinsciprcion
                    $resultado_cupos[$res][3]=$resultado_cupos[$res][4]-$resultado_cupos[$res][5];
                    $resultado_cupos[$res][3]=$resultado_buscarOracle[0][0]-$resultado_cupos[$res][3];
                    $cadena_sql_borrarOracleCupos=$this->sql->cadena_preins_sql($configuracion, $conexionOracle, "borrarOracleCupos", $resultado_cupos[$res]);
                    //echo $cadena_sql_guardarOracle."<br>";
                    //actualiza en oracle el numero de cupos
                    //descomentar
                    $resultado_borrarOracleCupos=$this->ejecutarSQL($configuracion, $conexionOracle, $cadena_sql_borrarOracleCupos, "");
                    if(!$resultado_borrarOracleCupos){
                        echo "<script>alert('En este momento la base de cupos se encuentra congestionada. Comuníquese con nosotros a través del correo ".$configuracion['correo']." o al tel. 3238400 ext 1110');</script>";
                        echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
                        $variablesRegistro=array($this->usuario,date('YmdGis'),'6','No se pueden guardar datos de cupos en Oracle del plan de estudios '.$parametro[1], $parametro[4].", ".$parametro[5].", 0, 0, 0, ".$parametro[1].", ".$parametro[0], '');
                        $cadena_sql_registroEvento=$this->sql->cadena_preins_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );
                        exit;
                    }
                    //echo $cadena_sql_buscarOracle."<br>";
                    //echo $resultado_cupos[$res][3]."=".$resultado_buscarOracle[0][0]."- (".$resultado_cupos[$res][4]."-".$resultado_cupos[$res][5].")<br><br>";
                    $res++;
                }
                //busca los codigos de estudiantes del plan de estudios registrados en mysql
                $cadena_sql_registros=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "buscarRegistros", $parametro);
                $resultado_registros=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_registros, "busqueda");
                if ($resultado_registros)
                {
                    $cuenta=0;
                    while($resultado_registros[$cuenta][0])
                    {
                        $parametro[2]=$resultado_registros[$cuenta][0];//cod estudiante
                        $parametro[3]=$resultado_registros[$cuenta][1];//cod espacio
                        //borra cada registro en Oracle
                        $cadena_sqlOracle=$this->sql->cadena_preins_sql($configuracion, $conexionOracle, "borrarEstudiantesOracle", $parametro);
                        $resultado_borrarOracle=$this->ejecutarSQL($configuracion, $conexionOracle, $cadena_sqlOracle, "");
                        $parametro[2]="horario_estudiante";//nombre tabla
                        $parametro[3]="horario" ;//nombre prefijo columna
                        $parametro[6]=$resultado_registros[$cuenta][0];//cod estudiante
                        $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatosEstudiante", $parametro);
                        $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");
                        $cuenta++;
                    }
                }

            }

                $variablesRegistro=array($this->usuario,date('YmdGis'),'6','Borrado de datos de Preinscripcion del plan de estudios '.$parametro[1], $parametro[4].", ".$parametro[5].", 0, 0, 0, ".$parametro[1].", ".$parametro[0], '');
                $cadena_sql_registroEvento=$this->sql->cadena_preins_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );
                $cadena_sql_registros=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "buscarRegistrosProvisionales", $parametro);
                $resultado_registros=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_registros, "busqueda");
                if ($resultado_registros)
                {
                    $cuenta=0;
                    while($resultado_registros[$cuenta][0])
                    {

                    //borra datos de creditos
                        $parametro[2]="semestre_creditos_estudiante";//nombre tabla
                        $parametro[3]="semestre" ;//nombre prefijo columna
                        $parametro[6]=$resultado_registros[$cuenta][0];//cod estudiante
                        $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatosEstudiante", $parametro);
                        $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");
                        //borra horarios de estudiantes
                        $cuenta++;
                    }
                }

                    //actualiza los parametros
            $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatosParametros", $parametro);
            $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");

            //borra notas reprobadas
            $parametro[2]="nota_reprobados";
            $parametro[3]="nota" ;//nombre prefijo columna
            $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatos", $parametro);
            $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");

            //borrar para producción
            //$valores[1]="2009-3";
            //borra errores
            $parametro[2]="errores_preinscripcion";
            $parametro[3]="errores" ;//nombre prefijo columna
            $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatos", $parametro);
            $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");
            //borra horarios provisionales
            $parametro[2]="horario_estudiante_provisional";//nombre tabla
            $parametro[3]="horario" ;//nombre prefijo columna
            $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatos", $parametro);
            $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");
            //actualiza cupos 
            $parametro[2]="cupos_preinscripcion";//nombre tabla
            $parametro[3]="cupos" ;//nombre prefijo columna
            $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatos", $parametro);
            $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");


            $this->redireccionarProceso($configuracion, "iniciar", "");

        }



        function cancelar($configuracion)
	{
            echo "<script>alert('Los datos de Preinscripción se mantienen sin modificación');</script>";
            echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
        }

        function guardarDatos($configuracion, $conexionGestion, $conexionOracle)
	{
            $parametro[0]=$_REQUEST['carrera'];
            $parametro[1]=$_REQUEST['planEstudio'];
            $parametro[4]=$_REQUEST['anno'];
            $parametro[5]=$_REQUEST['periodo'];

            $parametro[10]="= 1";
            $cadena_sql_guardar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "buscarDatosPreinscripcion", $parametro);
            $resultado_guardar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_guardar, "busqueda");
            if ($resultado_guardar)
            {
                echo "<script>alert('Los datos ya han sido almacenados');</script>";
                echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
                exit;
            }
            $variablesRegistro=array($this->usuario,date('YmdGis'),'6','Se guardan datos de Preinscripcion del plan de estudios '.$parametro[1], $parametro[4].", ".$parametro[5].", 0, 0, 0, ".$parametro[1].", ".$parametro[0], '');
            $cadena_sql_registroEvento=$this->sql->cadena_preins_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

            $cadena_sql_guardar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "guardarDatosParametros", $parametro);
            $resultado_guardar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_guardar, "");
            //si no ha podido actualizar el registro de estado de parametros a guardado
            if(!$resultado_guardar){
                echo "<script>alert('En este momento la base de datos se encuentra congestionada. Comuníquese con nosotros a través del correo ".$configuracion['correo']." o al tel. 3238400 ext 1110');</script>";
                echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
                $variablesRegistro=array($this->usuario,date('YmdGis'),'6','No se pueden insertar datos en horario de estudiantes del plan de estudios '.$parametro[1], $parametro[4].", ".$parametro[5].", 0, 0, 0, ".$parametro[1].", ".$parametro[0], '');
                $cadena_sql_registroEvento=$this->sql->cadena_preins_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );
                exit;
            }
            //pasa de la tabla provisional a la de horario
            $cadena_sql_guardar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "guardarDatos", $parametro);
            $resultado_guardar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_guardar, "");
            //busca registros de estudiantes
            $cadena_sql_buscar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "buscarRegistrosHorario", $parametro);
            $resultado_buscar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_buscar, "busqueda");
            if ($resultado_buscar)
            {

                $res=0;
                //inserta cada registro de estudiante
                while ($resultado_buscar[$res][0]) {
                    $casa="VALUES ";
                    //$semestre[0]=2010;
                    //$semestre[1]=1;
                    $casa.="('".$resultado_buscar[$res][1]."', '".$resultado_buscar[$res][0]."', '".$resultado_buscar[$res][5]."', '".$resultado_buscar[$res][6]."',";
                    $casa.="'', '', '', 'A', '".$resultado_buscar[$res][3]."', '".$resultado_buscar[$res][4]."', '', '', '', '', '', '', '', '', '', '')";
                    $cadena_sql_guardarOracle=$this->sql->cadena_preins_sql($configuracion, $conexionOracle, "guardarOracle", $casa);


                    $resultado_guardarOracle=$this->ejecutarSQL($configuracion, $conexionOracle, $cadena_sql_guardarOracle, "");
                    //si no puede realizar el registro de un estudiante en Oracle borra todo
                    if(!$resultado_guardarOracle) {
                        echo "<script>alert('En este momento la base de datos se encuentra congestionada. Comuníquese con nosotros a través del correo ".$configuracion['correo']." o al tel. 3238400 ext 1110');</script>";
                        echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
                        $variablesRegistro=array($this->usuario,date('YmdGis'),'6','No se pueden insertar datos de estudiantes en Oracle del plan de estudios '.$parametro[1], $parametro[4].", ".$parametro[5].", 0, 0, 0, ".$parametro[1].", ".$parametro[0], '');
                        $cadena_sql_registroEvento=$this->sql->cadena_preins_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );
                        //busca los codigos de estudiantes del plan de estudios registrados en mysql
                        $cadena_sql_registros=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "buscarRegistros", $parametro);
                        $resultado_registros=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_registros, "busqueda");
                        if ($resultado_registros) {
                            $cuenta=0;
                            while($resultado_registros[$cuenta][0]) {
                                $parametro[2]=$resultado_registros[$cuenta][0];//cod estudiante
                                $parametro[3]=$resultado_registros[$cuenta][1];//cod espacio
                                //borra cada registro en Oracle
                                $cadena_sqlOracle=$this->sql->cadena_preins_sql($configuracion, $conexionOracle, "borrarEstudiantesOracle", $parametro);
                                $resultado_borrarOracle=$this->ejecutarSQL($configuracion, $conexionOracle, $cadena_sqlOracle, "");
                                //borra registro del estudiante en mysql
                                $parametro[2]="horario_estudiante";//nombre tabla
                                $parametro[3]="horario" ;//nombre prefijo columna
                                $parametro[6]=$resultado_registros[$cuenta][0];//cod estudiante
                                $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatosEstudiante", $parametro);
                                $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");
                                $cuenta++;
                            }
                        }
                        $cadena_sql_registros=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "buscarRegistrosProvisionales", $parametro);
                        $resultado_registros=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_registros, "busqueda");
                        if ($resultado_registros) {
                            $cuenta=0;
                            while($resultado_registros[$cuenta][0]) {
                            //borra datos de creditos
                                $parametro[2]="semestre_creditos_estudiante";//nombre tabla
                                $parametro[3]="semestre" ;//nombre prefijo columna
                                $parametro[6]=$resultado_registros[$cuenta][0];//cod estudiante
                                $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatosEstudiante", $parametro);
                                $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");
                                //borra horarios de estudiantes
                                $cuenta++;
                            }
                        }
                        //actualiza los parametros
                        $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatosParametros", $parametro);
                        $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");
                        //borra notas reprobadas
                        $parametro[2]="nota_reprobados";
                        $parametro[3]="nota" ;//nombre prefijo columna
                        $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatos", $parametro);
                        $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");
                        //borra errores
                        $parametro[2]="errores_preinscripcion";
                        $parametro[3]="errores" ;//nombre prefijo columna
                        $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatos", $parametro);
                        $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");
                        //borra horarios provisionales
                        $parametro[2]="horario_estudiante_provisional";//nombre tabla
                        $parametro[3]="horario" ;//nombre prefijo columna
                        $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatos", $parametro);
                        $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");
                        //borra cupos
                        $parametro[2]="cupos_preinscripcion";//nombre tabla
                        $parametro[3]="cupos" ;//nombre prefijo columna
                        $cadena_sql_borrar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "borrarDatos", $parametro);
                        $resultado_borrar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_borrar, "");
                        exit;
                    }

                    unset ($casa);
                    $res++;
                    //pasa al siguiente estudiante
                }

            }
            //actualiza el registro de los cupos
            $cadena_sql_actualizar=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "actualizarRegistrosCupos", $parametro);
            $resultado_actualizar=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_actualizar, "");

            $cadena_sql_cupos=$this->sql->cadena_preins_sql($configuracion, $conexionGestion, "buscarRegistrosCupos", $parametro);
            $resultado_cupos=$this->ejecutarSQL($configuracion, $conexionGestion, $cadena_sql_cupos, "busqueda");
            if ($resultado_cupos)
            {

                $res=0;
                while ($resultado_cupos[$res][0])
                {
                    $resultado_cupos[$res][3]=$resultado_cupos[$res][3]-$resultado_cupos[$res][5];
                    $cadena_sql_guardarOracle=$this->sql->cadena_preins_sql($configuracion, $conexionOracle, "guardarOracleCupos", $resultado_cupos[$res]);
                    $resultado_guardarOracle=$this->ejecutarSQL($configuracion, $conexionOracle, $cadena_sql_guardarOracle, "");
                    if(!$resultado_guardarOracle){
                        echo "<script>alert('En este momento la base de cupos se encuentra congestionada. Si el problema persiste, comuníquese con nosotros a través del correo ".$configuracion['correo']." o al tel. 3238400 ext 1110');</script>";
                        echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";
                        $variablesRegistro=array($this->usuario,date('YmdGis'),'6','No se pueden actualizar datos de cupos en Oracle del plan de estudios '.$parametro[1], $parametro[4].", ".$parametro[5].", 0, 0, 0, ".$parametro[1].", ".$parametro[0], '');
                        $cadena_sql_registroEvento=$this->sql->cadena_preins_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                        $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );
                        exit;
                    }
                    $res++;
                }
                
            }

            echo "<script>alert('Los datos se han almacenado satisfactoriamente');</script>";
            echo "<script>location.replace('".$configuracion['host']."/academicopro/appserv/coordinadorcred/coorcred_pag_principal.php');</script>";

        }



	function redireccionarProceso($configuracion, $opcion, $valor)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		unset($_REQUEST['action']);
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		switch($opcion)
		{
			case "preinscribir": //envia a ejecutarPreinscripcion con parametro ejecutar
                 		$variable="pagina=ejecutarPreinscripcion";
				$variable.="&opcion=ejecutarPreinscripcion";
                                $variable.="&carrera=".$valor[0];
                                $variable.="&planEstudio=".$valor[1];
                                $variable.="&orden=".$valor[2];
                                $variable.="&semestres=".$valor[3];
                                $variable.="&anno=".$valor[4];
                                $variable.="&periodo=".$valor[5];
                                break;

                        case "borrarDatos":
				$variable="pagina=realizarPreinscripcion";
				$variable.="&opcion=borrar";
				$variable.="&carrera=".$valor[0];
                                $variable.="&planEstudio=".$valor[1];
                                $variable.="&orden=".$valor[2];
                                $variable.="&semestres=".$valor[3];
                                $variable.="&anno=".$valor[4];
                                $variable.="&periodo=".$valor[5];
                                break;

                        case "iniciar":
				$variable="pagina=realizarPreinscripcion";
				$variable.="&opcion=planestudios";
				//$variable.="&registro0="."no_resultados";
                                break;

                        case "cancelar":
				$variable="pagina=realizarPreinscripcion";
				$variable.="&opcion=cancelar";
				break;

                        case "no_resultados":
				$variable="pagina=consultarSolicitudCertificado";
				$variable.="&opcion=nuevo";
				$variable.="&registro0="."no_resultados";
                                break;

                        case "mostrarregistro":
				$variable="pagina=registro_blogdev";
				$variable.="&opcion=generar";
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
				if(isset($_REQUEST['sinCodigo']))
				{
					$variable="pagina=exitoInscripcionSecretario";
				}
				else
				{
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
