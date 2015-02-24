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

class funciones_registroRequisitos extends funcionGeneral
{
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
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
                //Conexion produccion
                //$this->accesoGestion=$this->conectarDB($configuracion,"pro_sga");
		//Datos de sesion
		$this->formulario="registroRequisitos";
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		
	}

        function seleccionarTipo($configuracion)
        {?>
        <table class="sigma" align="center" border="0" width="100%" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                <tr align="center">
                    <th class="sigma_a">
                        REQUISITOS ESPACIOS ACAD&Eacute;MICOS
                	<hr noshade class="hr">
                    </th>
                </tr>
                <tr class="bloquelateralcuerpo">
                    <td  style='text-align:center' align="center">
                    <?
                        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $ruta="pagina=requisitos_espacio";
                        $ruta.="&opcion=registrar";
                        $rutaVer=$ruta."&ejecucion=Consultar";
                        $rutaVer=$this->cripto->codificar_url($rutaVer,$configuracion);
                    ?>
                        <a href="<?= $indice.$rutaVer ?>" >
                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/editarGrande.png" width="20" height="20" border="0"><br><b>Registrar Requisitos de Espacios Acad&eacute;micos</b>
                        </a>
                    </td>
                </tr>
        </table>
        <?

        }
	
	function nuevoRegistro($configuracion)
	{
            if(isset($_REQUEST['planEstudio']))
                {
                    $planEstudio=$_REQUEST['planEstudio'];
                }else
                    {
                        $cadena_sql_plan=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"plan_estudio", $this->usuario);
                        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

                        $planEstudio=$resultado_plan[0][0];
                    }
            
             
             $cadena_sql_espacios=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"espacios_academicos", $planEstudio);//echo $cadena_sql_espacios;exit;
             $resultado_espacios=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacios,"busqueda" );

             $cadena_sql_registrados=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"requisitos_registrados", $planEstudio);
             $resultado_registrados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registrados,"busqueda" );
            
            ?>

        <table class="contenidotabla centrar">
                <tr>
                    <td style='text-align:center' align="center" colspan="6">
                        <h4 class="bloquelateralcuerpo">REQUISITOS ESPACIOS ACAD&Eacute;MICOS</h4>
                        <hr>
                    </td>
                </tr>
                <tr align="center">
                    <td class="centrar" colspan="2">
                        <a href="javascript:history.back()">
                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="35" height="35" border="0"><br>Atras
                        </a>
                    </td>
                    <td class="centrar" colspan="2" width="50%">
                        <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variables="pagina=adminConfigurarPlanEstudioCoordinador";
                        $variables.="&opcion=mostrar";
                        $variables.="&planEstudio=".$planEstudio;

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variables=$this->cripto->codificar_url($variables,$configuracion);
                        ?>
                        <a href="<?echo $pagina.$variables?>">
                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Inicio
                        </a>
                    </td>
                    <td class="centrar" colspan="2" width="25%">
                        <a href="javascript:history.forward()">
                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/continuar.png" width="35" height="35" border="0"><br>Adelante
                        </a>
                    </td>
                </tr>
       
                <tr>
                  <td colspan="6">
                <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>

                <table class='sigma' align='center' width='100%' cellpadding='2' cellspacing='2'>
                    <tr>
                        <th class="sigma_a">
                            REGISTRO DE REQUISITOS DEL PLAN DE ESTUDIO <?echo $planEstudio?>
                        </th>
                    </tr>
                    <tr>
                        <th class="sigma" align="center">Los campos marcados con <font color="red">*</font> son obligatorios </th>
                    </tr>
                <tr>
                <td>
                <table align='center' width='100%' cellpadding='2' cellspacing='2' class='contenidotabla'>
                        <tr class='bloquecentralcuerpo' >
                            <td bgcolor='<? echo $tema->celda ?>' width="50%" align="center">
                                <font color="red">* </font>ESPACIO ACADEMICO<br><font size="1">Seleccione el Espacio Acad&eacute;mico al que quiere asignarle requisitos</font> <br>
                                
                                        <select class="sigma" name='espacio' id='espacio' style="width:200px">
                                        <option value="0">Seleccione...</option>
                                    <?  $nivel=0;
                                        for($i=0;$i<count($resultado_espacios);$i++)
                                        {
                                            if($resultado_espacios[$i][2]!=$nivel)
                                                {
                                                ?>
                                                    <optgroup label="Nivel <?echo $resultado_espacios[$i][2]?>"></optgroup>
                                                <?
                                                $nivel=$resultado_espacios[$i][2];
                                                }
                                            ?>
                                                <option value="<?echo $resultado_espacios[$i][0]?>"><?echo $resultado_espacios[$i][0]."   -   ".$resultado_espacios[$i][1]?></option>
                                            <?
                                        }
                                        
                                    ?>
                                   </select>
                           </td>

                           <td bgcolor='<? echo $tema->celda ?>' width="50%" align="center">
                                <font color="red">* </font>REQUISITO<br><font size="1">Seleccione el Espacio Acad&eacute;mico que ser&aacute; requisito del anterior</font><br>

                                        <select class="sigma" name='espacioRequisito' id='espacioRequisito' style="width:200px">
                                        <option value="0">Seleccione...</option>
                                    <?  $nivel=0;
                                        for($j=0;$j<count($resultado_espacios);$j++)
                                        {
                                            if($resultado_espacios[$j][2]!=$nivel)
                                                {
                                                ?>
                                                    <optgroup label="Nivel <?echo $resultado_espacios[$j][2]?>"></optgroup>
                                                <?
                                                $nivel=$resultado_espacios[$j][2];
                                                }
                                            ?>
                                                    <option value="<?echo $resultado_espacios[$j][0]?>"><?echo $resultado_espacios[$j][0]."   -   ".$resultado_espacios[$j][1]?></option>
                                            <?
                                        }
                                    ?>
                                   </select>
                           </td>

                        </tr>

                        <tr class='bloquecentralcuerpo' >
                            <td colspan="2" bgcolor='<? echo $tema->celda ?>' width="60%" align="center">
                                <font color="red">* </font>REQUISITO APROBADO<br><font size="1">¿El requisito debe estar aprobado?</font> <br>

                                <select class="sigma" name='aprobado' id='aprobado'>
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                   </select>
                            </td>
                        </tr>
                        
                        <tr align='center' >
                                <td colspan='2' rowspan='1'>
                                        <input type='hidden' name='action' value='<?echo $this->formulario?>'>
                                        <input type='hidden' name='opcion' value='guardar'>
                                        <input type='hidden' name='planEstudio' value='<? echo $planEstudio?>'>
                                        <input class="boton" name='aceptar' value='Guardar' type='submit'>
                                </td>
                        </tr>
                </table>
                </td>
                </tr>

                </table>
                </form>
                    </td>
        </tr>
</table>
                <table align='center' width='100%' cellpadding='2' cellspacing='2' class='sigma contenidotabla'>

                    <caption class="sigma">
                        REQUISITOS REGISTRADOS
                    </caption>
                    <thead class='sigma centrar'>
                        <tr class='sigma centrar'>
                            <th class='sigma centrar' colspan="2">ESPACIO ACAD&Eacute;MICO</th>
                            <th class='sigma centrar' colspan="2">REQUISITO</th>
                            <th class='sigma centrar'>¿REQUISITO<br>APROBADO?</th>
                            <th class='sigma centrar' colspan="2"> ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <?

                        for($i=0;$i<count($resultado_registrados);$i++)
                        {
                            //var_dump($resultado_registrados[$i][4]);echo"<br>";
                            if($resultado_registrados[$i][4]==1)
                                {
                                    $aprobado="SI";
                                }else
                                    {
                                    $aprobado="NO";
                                    }
                            if($i%2==0)
                                {
                                    $clase="sigma";
                                }else
                                    {
                                        $clase="";
                                    }
                            ?><tr class="<?echo $clase?>">
                                <td class="derecha">
                                    <?echo $resultado_registrados[$i][2]." -";?>
                                </td>
                                <td>
                                    <?echo $resultado_registrados[$i][3]?>
                                </td>
                                <td class="derecha">
                                    <?echo $resultado_registrados[$i][0]." -";?>
                                </td>
                                <td>
                                    <?echo $resultado_registrados[$i][1]?>
                                </td>
                                <td class="centrar">
                                    <?echo $aprobado?>
                                </td>
                                <td class="centrar">
                                    <?
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=requisitos_espacio";
                                        $variable.="&opcion=editar";
                                        $variable.="&requisito=".$resultado_registrados[$i][0];
                                        $variable.="&nombreRequisito=".$resultado_registrados[$i][1];
                                        $variable.="&codEspacio=".$resultado_registrados[$i][2];
                                        $variable.="&nombreEspacio=".$resultado_registrados[$i][3];
                                        $variable.="&aprobado=".$resultado_registrados[$i][4];
                                        $variable.="&planEstudio=".$planEstudio;

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                    ?>
                                    <a href="<?= $pagina.$variable ?>" >
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/editarGrande.png" width="20" height="20" border="0" alt="Editar Requisito">
                                    </a>
                                </td>
                                <td class="centrar">
                                    <?
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variable="pagina=requisitos_espacio";
                                        $variable.="&opcion=borrar";
                                        $variable.="&requisito=".$resultado_registrados[$i][0];
                                        $variable.="&nombreRequisito=".$resultado_registrados[$i][1];
                                        $variable.="&codEspacio=".$resultado_registrados[$i][2];
                                        $variable.="&nombreEspacio=".$resultado_registrados[$i][3];
                                        $variable.="&aprobado=".$resultado_registrados[$i][4];
                                        $variable.="&planEstudio=".$planEstudio;

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                                    ?>
                                    <a href="<?= $pagina.$variable ?>" >
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/x.png" width="20" height="20" border="0" alt="Borrar Requisito">
                                    </a>
                                </td>
                            </tr>
                                        <?
                        }

                    ?>
                </table>

					
	<?
	}

        function guardarRegistro($configuracion)
        {
            $valores=array($_REQUEST['id_planEstudio'],$_REQUEST['id_espacioRequisito'],$_REQUEST['id_espacio'],$_REQUEST['aprobado']);  
            //var_dump($_REQUEST);exit;

           if(($_REQUEST['id_espacio']=='0') || ($_REQUEST['id_espacioRequisito']=='0'))
                {
                    echo "<script>alert ('Debe seleccionar el espacio academico y su requisito')</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=requisitos_espacio";
                    $variable.="&opcion=registrar";
                    $variable.="&planEstudio=".$_REQUEST['id_planEstudio'];
                    
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                }else{

                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"insertar_registro", $valores);
                    $resultado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "");

                    if($resultado==true)
                        {
                            echo "<script>alert ('Registro Exitoso');</script>";
                        }

                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=requisitos_espacio";
                        $variable.="&opcion=registrar";
                        $variable.="&planEstudio=".$_REQUEST['id_planEstudio'];
                        
                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
            
        }

        function editarRegistro($configuracion)
        {
            if(isset($_REQUEST['planEstudio']))
                {
                    $planEstudio=$_REQUEST['planEstudio'];
                }else
                    {
                        $cadena_sql_plan=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"plan_estudio", $this->usuario);
                        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

                        $planEstudio=$resultado_plan[0][0];
                    }

             $cadena_sql_espacios=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"espacios_academicos", $planEstudio);
             $resultado_espacios=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacios,"busqueda" );

             $cadena_sql_registrados=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"requisitos_registrados", $planEstudio);
             $resultado_registrados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registrados,"busqueda" );

            ?>
<table class="sigma" width="100%" align="center" border="0" cellpadding="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                <tr>
                    <td style='text-align:center' align="center" colspan="2">
                        <h4 class="bloquelateralcuerpo">REQUISITOS ESPACIOS ACAD&Eacute;MICOS</h4>
                        <hr>
                    </td>
                </tr>
                
                <tr>
                <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>

                <table class='contenidotabla' align='center' width='100%' cellpadding='2' cellspacing='2'>
                    <tr>
                    <th class="sigma_a centrar">REGISTRO DE REQUISITOS DEL PLAN DE ESTUDIO <?echo $planEstudio?></th>
                    </tr>
                    <tr>
                        <td align="center">Los campos marcados con <font color="red">*</font> son obligatorios </td>
                    </tr>
                    <tr>
                    <td>
                    <table align='center' width='100%' cellpadding='2' cellspacing='2' class='contenidotabla'>
                        <tr class='bloquecentralcuerpo' >
                            <td bgcolor='<? echo $tema->celda ?>' width="50%" align="center">
                                <font color="red">* </font>ESPACIO ACADEMICO<br><font size="1">Espacio Acad&eacute;mico al que quiere asignarle requisitos</font> <br>
                                
                                        <select class="sigma" name='espacio' id='espacio' style="width:200px">
                                        <option value="<?echo $_REQUEST['codEspacio']?>"><?echo $_REQUEST['nombreEspacio']?></option>
                                   
                                   </select>
                           </td>

                           <td bgcolor='<? echo $tema->celda ?>' width="50%" align="center">
                                <font color="red">* </font>REQUISITO<br><font size="1">Seleccione el Espacio Acad&eacute;mico que ser&aacute; requisito del anterior</font><br>

                                <select class="sigma" name='espacioRequisito' id='espacioRequisito' style="width:200px">
                                        <option value="<?echo $_REQUEST['requisito']?>"><?echo $_REQUEST['nombreRequisito']?></option>
                                    
                                   </select>
                           </td>

                        </tr>

                        <tr class='bloquecentralcuerpo' >
                            <td colspan="2" bgcolor='<? echo $tema->celda ?>' width="60%" align="center">
                                <font color="red">* </font>REQUISITO APROBADO<br><font size="1">¿El requisito debe estar aprobado?</font> <br>

                                    <?
                                    if($_REQUEST['aprobado']==1){
                                    ?>
                                <select class="sigma" name='aprobado' id='aprobado'>
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                        </select>
                                   <?
                                    }else
                                        {
                                        ?>
                                <select class="sigma" name='aprobado' id='aprobado'>
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                        </select>
                                        <?
                                        }
                                   ?>
                            </td>

                            </td>

                        </tr>
                        
                        <tr align='center' >
                                <td colspan='2' rowspan='1'>
                                        <input type='hidden' name='action' value='<?echo $this->formulario?>'>
                                        <input type='hidden' name='opcion' value='actualizar'>
                                        <input type='hidden' name='planEstudio' value='<? echo $planEstudio?>'>
                                        <input class="boton" name='actualizar' value='Actualizar' type='submit'>
                                </td>
                        </tr>
                </table>
                </td>
                </tr>
                </tbody>
                </table>
                </form></tr>
            </table>


    <?
        }

        function actualizarRegistro($configuracion)
        {
            $valores=array($_REQUEST['id_planEstudio'],$_REQUEST['id_espacioRequisito'],$_REQUEST['id_espacio'],$_REQUEST['aprobado']);
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizar_registro", $valores);
            $resultado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "");

            if($resultado==true)
                {
                    echo "<script>alert ('El registro ha sido actualizado');</script>";
                }

                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=requisitos_espacio";
                $variable.="&opcion=registrar";
                $variable.="&planEstudio=".$_REQUEST['id_planEstudio'];
                
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";

        }

        function borrarRegistro($configuracion)
        {
            if(isset($_REQUEST['planEstudio']))
                {
                    $planEstudio=$_REQUEST['planEstudio'];
                }else
                    {
                        $cadena_sql_plan=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"plan_estudio", $this->usuario);
                        $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

                        $planEstudio=$resultado_plan[0][0];
                    }

             $cadena_sql_plan=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"plan_estudio", $this->usuario);
             $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_plan,"busqueda" );

             $cadena_sql_espacios=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"espacios_academicos", $planEstudio);
             $resultado_espacios=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacios,"busqueda" );

             $cadena_sql_registrados=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"requisitos_registrados", $planEstudio);
             $resultado_registrados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registrados,"busqueda" );

            ?>
            <table align="center" border="0" cellpadding="0" background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                <tr>
                    <td style='text-align:center' align="center" colspan="2">
                        <h4 >SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                    </td>
                <tr>
                    <td style='text-align:center' align="center" colspan="2">
                        <h4 class="bloquelateralcuerpo">REQUISITOS ESPACIOS ACAD&Eacute;MICOS</h4>
                        <hr>
                    </td>
                </tr>
                </tr>

                <br>

                <tr>
                <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>

                <table class='contenidotabla' align='center' width='100%' cellpadding='2' cellspacing='2'>
                    <thead class='texto_subtitulo centrar'>
                        <td><center>REGISTRO DE REQUISITOS DEL PLAN DE ESTUDIO <?echo $planEstudio?></center></td>
                    </thead>

                    <tr></tr>
                    <tbody>
                        <tr>
                            <td align="center"> </td>
                        </tr>
                <tr>
                <td>
                <table align='center' width='100%' cellpadding='2' cellspacing='2' class='contenidotabla'>
                        <tr class='bloquecentralcuerpo' >
                            <td bgcolor='<? echo $tema->celda ?>' width="50%" align="center">
                                ESPACIO ACAD&Eacute;MICO<br>
                                <input type="hidden" name="espacio" value="<?echo $_REQUEST['codEspacio']?>">
                                <font color="<? echo $tema->celda ?>"><?echo $_REQUEST['nombreEspacio']?></font>
                            </td>
                            <td bgcolor='<? echo $tema->celda ?>' width="50%" align="center">
                                REQUISITO<br>
                                <input type="hidden" name="espacioRequisito" value="<?echo $_REQUEST['requisito']?>">
                                <font color="<? echo $tema->celda ?>"><?echo $_REQUEST['nombreRequisito']?></font>
                            </td>

                        </tr>
                        <tr align="center">
                            <td colspan='2' rowspan='1'>
                                <font color='red'>¿ESTA SEGURO DE ELIMINAR ESTE REQUISITO?</font>
                            </td>
                        </tr>

                        <tr align='center' >
                                <form>
                                    <td align="right">
                                        <input type='hidden' name='action' value='<?echo $this->formulario?>'>
                                        <input type='hidden' name='opcion' value='eliminar'>
                                        <input type='hidden' name='planEstudio' value='<? echo $planEstudio?>'>
                                        <input type="hidden" name='aprobado' value='<?echo $_REQUEST['aprobado']?>' >
                                        <input name='eliminar' value='SI' type='submit'>
                                     </td>
                                     </form>
                                     <form>
                                     <td align="left">
                                        <input type='hidden' name='action' value='<?echo $this->formulario?>'>
                                        <input type='hidden' name='opcion' value='eliminar'>                                        
                                        <input name="cancelar" value="NO" type="submit" onclick="history.back()">
                                     </td>
                                     </form>
                        </tr>
                        
                </table>
                </td>
                </tr>
                </tbody>
                </table>
                </form></tr>
            </table>

    <?
         }

         function eliminarRegistro($configuracion)
        {
            $valores=array($_REQUEST['id_planEstudio'],$_REQUEST['id_espacioRequisito'],$_REQUEST['id_espacio'],$_REQUEST['aprobado']);
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"eliminar_registro", $valores);
            $resultado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql, "");
             if($resultado==true)
                {
                    echo "<script>alert ('El registro ha sido eliminado');</script>";
                }
               
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=requisitos_espacio";
                $variable.="&opcion=registrar";
                $variable.="&planEstudio=".$_REQUEST['id_planEstudio'];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";

        }
	
}
	

?>

