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

class funciones_registroCambiarGrupoInscripcionEstudCoordinador extends funcionGeneral
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

		//Conexion ORACLE
		$this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		$this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

		//Datos de sesion
		$this->formulario="registroCambiarGrupoInscripcionEstudCoordinador";
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		$this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
	}


        function buscarGrupos($configuracion)
        {
           $codigoEstudiante=$_REQUEST['codEstudiante'];
           $codEspacio=$_REQUEST['codEspacio'];
           $planEstudio=$_REQUEST['planEstudio'];
           $proyecto=$_REQUEST['proyecto'];
           $nombre=$_REQUEST['nombre'];
           $grupo=$_REQUEST['grupo'];

          ?>
           <table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar" width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroCambiarGrupoInscripcionEstudCoordinador";
                    $variable.="&opcion=buscar";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&proyecto=".$_REQUEST['proyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos del Proyecto <br>Curricular <? echo $codProyecto;?></b>
            </a>

            <td class="centrar" width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroCambiarGrupoInscripcionEstudCoordinador";
                    $variable.="&opcion=otrosGrupos";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&proyecto=".$_REQUEST['proyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos en otros<br>Proyectos Curriculares</b>
            </a>
        </td>
    </tr>
</table>
           <?

           $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosCoordinador", $this->usuario);
           $resultado_craCoordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

           $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"ano_periodo", "");
           $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );
           $ano=$resultado_periodo[0][0];
           $periodo=$resultado_periodo[0][1];
           $mostrar=0;

           for($r=0;$r<count($resultado_craCoordinador);$r++)
           {


           $variables=array($codEspacio,$resultado_craCoordinador[$r][1],$planEstudio,$grupo,$ano,$periodo);

           $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"grupos_proyecto", $variables);
           $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

                if($resultado_grupos!=NULL){
                    ?>
                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                            <tr>
                                <td>
                                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <thead class='texto_subtitulo centrar'>
                                        <td><center><?echo "Proyecto Curricular: ".$variables[1]."<br>".$codEspacio." - ".htmlentities($nombre);?></center></td>
                                        </thead>
                                        <tr>
                                            <td>
                                                <table class='contenidotabla'>
                                                    <thead class='cuadro_color'>
                                                        <td class='cuadro_plano centrar' width="25">Grupo </td>
                                                        <td class='cuadro_plano centrar' width="60">Lun </td>
                                                        <td class='cuadro_plano centrar' width="60">Mar </td>
                                                        <td class='cuadro_plano centrar' width="60">Mie </td>
                                                        <td class='cuadro_plano centrar' width="60">Jue </td>
                                                        <td class='cuadro_plano centrar' width="60">Vie </td>
                                                        <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                                        <td class='cuadro_plano centrar' width="60">Dom </td>
                                                        <td class='cuadro_plano centrar' width="20">Cupos </td>
                                                        <td class='cuadro_plano centrar' >Cambiar Grupo</td>
                                                    </thead>

                <?

                for($j=0;$j<count($resultado_grupos);$j++){

                    $variables[6]=$resultado_grupos[$j][0];

                    $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos", $variables);
                    $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos_registrar", $variables);
                    $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    $variableCodigo[0]=$codigoEstudiante;
                    $variableCodigo[1]=$codEspacio;
                    $variableCodigo[3]=$ano;
                    $variableCodigo[4]=$periodo;

                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_registradoCruce", $variableCodigo);
                    $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                    unset($cruce);

                    for($n=0;$n<count($resultado_horarios_registrado);$n++) {
                        for($m=0;$m<count($resultado_horarios_registrar);$m++) {

                            if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n])) {

                                $cruce=true;
                                break;
                                }
                        }
                    }

                    $cupoDisponible=($resultado_horarios[$j][4]-$resultado_horarios[$j][5]);

                    $variableCupo=array($codigoEstudiante,$resultado_grupos[$j][0],$codEspacio, '', $ano, $periodo);

                    $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_ins", $variableCupo);
                    $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                    $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_cupo", $variableCupo);
                    $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                    unset($cupoDisponible);
                    $cupoDisponible=(($resultado_cupoGrupo[0][0])-($resultado_cupoInscritos[0][0]));
                    ?><tr><td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td><?
                    for($i=1; $i<8; $i++)
                    {
                        ?><td class='cuadro_plano centrar' onmouseover="this.bgColor='#E6E6E6'" onmouseout="this.bgColor=''"><?
                                    for ($k = 0; $k < count($resultado_horarios); $k++) {

                                        if ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                                            $l = $k;
                                            while ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                                                $m = $k;
                                                $m++;
                                                $k++;
                                            }
                                            $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$l]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$l]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$l]['ID_SALON'] . " " . $resultado_horarios[$l]['NOM_SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '')) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                            $k++;
                                        } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                                            $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                                            echo $dia . "<br>";
                                            unset($dia);
                                        } elseif ($resultado_horarios[$k]['DIA'] != $i) {                        }

                                                        }
                        ?></td><?
                    }
                    ?><td class='cuadro_plano centrar'><?echo $cupoDisponible?></td>


                      <td class='cuadro_plano centrar'>

                           <?
                            if($cruce!=true){
                        ?>
                                   <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>

                                        <input type="hidden" name="planEstudioGeneral" value="<?echo $_REQUEST['planEstudioGeneral']?>">
                                        <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                                        <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j][0]?>">
                                        <input type="hidden" name="grupoAnterior" value="<?echo $grupo?>">
                                        <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                        <input type="hidden" name="codEspacio" value="<?echo $variables[0]?>">
                                        <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                        <input type="hidden" name="proyecto" value="<?echo $variables[1]?>">
                                        <input type="hidden" name="opcion" value="cambiar">
                                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                        <input type="image" name="cambiar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/reload.png" width="25" height="25">

                                  </form>
                      </td>

                          <?
                }else
                    {
                        ?>No puede adicionar por cruce</td><?
                    }
                }
                ?>
                                                </table>
                                            </td>

                                        </tr>

                                    </table>
                            <?
                                }else
                                    {
                                      $mostrar++;
                                    }
           }
           if ($mostrar==$r)
           {
              ?>
                  <tr>
                      <td class="cuadro_plano centrar">
                          No existen grupos registrados en el Proyecto.<br><font size="2" color="red">Si est&aacute; buscando grupos para espacios Electivos Extr&iacute;nsecos o Segunda Lengua,<br>por favor consulte Grupos en otros Proyectos Curriculares.</font>
                      </td>
                  </tr>
              <?
           }
           else{}
           ?>
                <table class="contenido_tabla centrar" width="100%">
                <?                            
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variablesPag="pagina=adminConsultarInscripcionEstudianteCoordinador";
                            $variablesPag.="&opcion=mostrarConsulta";
                            $variablesPag.="&codProyecto=".$_REQUEST['codProyecto'];
                            $variablesPag.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                            $variablesPag.="&codEstudiante=".$_REQUEST['codEstudiante'];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variablesPag=$this->cripto->codificar_url($variablesPag,$configuracion);

                            ?>
                                <tr class="centrar">
                                    <td class="centrar">
                                        <a href="<?= $pagina.$variablesPag ?>" >
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" width="25" height="25" border="0"><br>
                                        <font size="2"><b>Regresar</b></font>
                                        </a>
                                    </td>
                                </tr>
                </table>
                <?
       }

         function buscarOtrosGrupos($configuracion)
    {
        $codigoEstudiante=$_REQUEST['codEstudiante'];
        $espacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $carrera=$_REQUEST['carrera'];
        $creditos=$_REQUEST['creditos'];
        $nombre=$_REQUEST['nombre'];
        $proyecto=$_REQUEST['proyecto'];
        $planEstudioGeneral=$_REQUEST['planEstudioGeneral'];
        $grupo=$_REQUEST['grupo'];
        
        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );
        $ano=$resultado_periodo[0][0];
        $periodo=$resultado_periodo[0][1];
        $variables=array($espacio,$proyecto,$planEstudio, $ano, $periodo);

        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_carrerasAbiertas", $variables);
        $resultadoAdicionesAbiertas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

        
       ?>
           <table width="100%" align="center" border="0" >
    <tr class="bloquelateralcuerpo">
        <td class="centrar" width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroCambiarGrupoInscripcionEstudCoordinador";
                    $variable.="&opcion=buscar";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&proyecto=".$_REQUEST['proyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos del Proyecto <br>Curricular <? echo $codProyecto;?></b>
            </a>

            <td class="centrar" width="50%">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=registroCambiarGrupoInscripcionEstudCoordinador";
                    $variable.="&opcion=otrosGrupos";
                    $variable.="&grupo=".$_REQUEST["grupo"];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                    $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                    $variable.="&carrera=".$_REQUEST["carrera"];
                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                    $variable.="&creditos=".$_REQUEST['creditos'];
                    $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                    $variable.="&nombre=".$_REQUEST['nombre'];
                    $variable.="&proyecto=".$_REQUEST['proyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                    $variable.="&estado_est=".$_REQUEST['estado_est'];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="35" height="35" border="0"><br><b>Grupos en otros<br>Proyectos Curriculares</b>
            </a>
        </td>
    </tr>
</table>
           <?

        if(is_array($resultadoAdicionesAbiertas))
            {
              $cuenta=0;
          for($p=0;$p<count($resultadoAdicionesAbiertas);$p++){

            $variables[5]=$resultadoAdicionesAbiertas[$p][0];
            $variables[6]=$grupo;
            $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"otros_gruposproyecto", $variables);
            $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

        if($resultado_grupos!=NULL) {
            ?>
<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
    <tbody>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                    <thead class='texto_subtitulo centrar'>
                    <td><center><?echo $espacio." - ".htmlentities($nombre)."<br>Proyecto Curricular: ".$variables[5]?></center></td>
                    </thead>
                    <tr>
                        <td>
                            <table class='contenidotabla'>
                                <thead class='cuadro_color'>
                                <td class='cuadro_plano centrar' width="40">Proyecto</td>
                                <td class='cuadro_plano centrar' width="25">Grupo </td>
                                <td class='cuadro_plano centrar' width="60">Lun </td>
                                <td class='cuadro_plano centrar' width="60">Mar </td>
                                <td class='cuadro_plano centrar' width="60">Mie </td>
                                <td class='cuadro_plano centrar' width="60">Jue </td>
                                <td class='cuadro_plano centrar' width="60">Vie </td>
                                <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                <td class='cuadro_plano centrar' width="60">Dom </td>
                                <td class='cuadro_plano centrar' width="20">Cupo</td>
                                <td class='cuadro_plano centrar' >Cambiar Grupo</td>
                                </thead>
                                            <?
                                            for($j=0;$j<count($resultado_grupos);$j++) {

                                                $variables[5]=$resultado_grupos[$j][0];

                                                $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_otros_grupos", $variables);
                                                $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );


                                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_otrosgrupos_registrar", $variables);
                                                $resultado_horarios_registrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                $variableCodigo=$variables;
                                                $variableCodigo[0]=$codigoEstudiante;
                                                $variableCodigo[1]=$espacio;

                                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_registradoCruce", $variableCodigo);
                                                $resultado_horarios_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                                unset($cruce);

                                                for($n=0;$n<count($resultado_horarios_registrado);$n++) {
                                                                for($m=0;$m<count($resultado_horarios_registrar);$m++) {

                                                                    if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n])) {

                                                                        $cruce=true;
                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                $variableCupo=array($codigoEstudiante,$resultado_grupos[$j][0],$espacio, '', $ano, $periodo);

                                                unset($cupoDisponible);
                                                unset($resultado_cupoGrupo);

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_ins", $variableCupo);
                                                $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_cupo", $variableCupo);
                                                $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                                                unset($cupoDisponible);
                                                
                                                $cupoDisponible=($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]);

                                                ?><tr>
                                                        <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][1];?></td>
                                                        <td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td><?
                                                    for($i=1; $i<8; $i++) {
                                                        ?><td class='cuadro_plano centrar'><?
                                                        for ($k = 0; $k < count($resultado_horarios); $k++) {

                                                            if ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {
                                                                $l = $k;
                                                                while ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['HORA']) ? $resultado_horarios[$k + 1]['HORA'] : '') == ($resultado_horarios[$k]['HORA'] + 1) && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') == ($resultado_horarios[$k]['ID_SALON'])) {

                                                                    $m = $k;
                                                                    $m++;
                                                                    $k++;
                                                                }
                                                                $dia = "<strong>" . $resultado_horarios[$l]['HORA'] . "-" . ($resultado_horarios[$m]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$l]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$l]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$l]['ID_SALON'] . " " . $resultado_horarios[$l]['NOM_SALON'];
                                                                echo $dia . "<br>";
                                                                unset($dia);
                                                            } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] != (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '')) {
                                                                $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                                                                echo $dia . "<br>";
                                                                unset($dia);
                                                                $k++;
                                                            } elseif ($resultado_horarios[$k]['DIA'] == $i && $resultado_horarios[$k]['DIA'] == (isset($resultado_horarios[$k + 1]['DIA']) ? $resultado_horarios[$k + 1]['DIA'] : '') && (isset($resultado_horarios[$k + 1]['ID_SALON']) ? $resultado_horarios[$k + 1]['ID_SALON'] : '') != ($resultado_horarios[$k]['ID_SALON'])) {
                                                                $dia = "<strong>" . $resultado_horarios[$k]['HORA'] . "-" . ($resultado_horarios[$k]['HORA'] + 1) . "</strong><br>Sede:" . $resultado_horarios[$k]['COD_SEDE'] . "<br>Edificio:" . $resultado_horarios[$k]['NOM_EDIFICIO'] . "<br>Sal&oacute;n:" . $resultado_horarios[$k]['ID_SALON'] . " " . $resultado_horarios[$k]['NOM_SALON'];
                                                                echo $dia . "<br>";
                                                                unset($dia);
                                                            } elseif ($resultado_horarios[$k]['DIA'] != $i) {
                                                                }

                                                        }
                                                            ?></td><?
                                                        }
                                                    ?><td class='cuadro_plano centrar'><?echo $cupoDisponible?></td>
                                    <td class='cuadro_plano centrar'>

                                                        <?
                                                        if($cupoDisponible>0) {

                                                            if($cruce!=true){
                                                            ?>

                                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
                                            
                                            <input type="hidden" name="planEstudioGeneral" value="<?echo $_REQUEST['planEstudioGeneral']?>">
                                            <input type="hidden" name="codProyecto" value="<?echo $_REQUEST['codProyecto']?>">
                                            <input type="hidden" name="grupo" value="<?echo $resultado_grupos[$j][0]?>">
                                            <input type="hidden" name="grupoAnterior" value="<?echo $grupo?>">
                                            <input type="hidden" name="codEstudiante" value="<?echo $codigoEstudiante?>">
                                            <input type="hidden" name="codEspacio" value="<?echo $variables[0]?>">
                                            <input type="hidden" name="planEstudio" value="<?echo $variables[2]?>">
                                            <input type="hidden" name="proyecto" value="<?echo $variables[1]?>">
                                            <input type="hidden" name="opcion" value="cambiar">
                                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                            <input type="image" name="cambiar" src="<?echo $configuracion['site'].$configuracion['grafico']?>/reload.png" width="25" height="25">

                                        </form>
                                    </td>
                                                    <?
                                                    }else
                                                        {
                                                            ?>No puede adicionar por cruce</td><?
                                                        }

                                                    }
                                                    else {
                                                        echo "No puede adicionar por cupo";?></td><?
                                                    }
                                                    ?>
                                </tr>
                                            <?
                                            }
                                            ?>
                            </table>

                        </td>

                    </tr>

                </table>
            </td>
        </tr>
                <?
                }else{
                  $cuenta++;
                }
                }
if($cuenta==$p){
                    ?>
                    <tr>
                        <td class="cuadro_plano centrar">
                        En el momento no existen grupos registrados en otros Proyectos
                        </td>
                    </tr>
                    <?
}
                }
                else {
                    ?>
                    <tr>
                        <td class="cuadro_plano centrar">
                        En el momento no existen grupos registrados en otros Proyectos
                        </td>
                    </tr>
                    <?}
                ?>
    </tbody>
</table>
<table class="contenido_tabla centrar" width="100%">
                <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variablesPag="pagina=adminConsultarInscripcionEstudianteCoordinador";
                            $variablesPag.="&opcion=mostrarConsulta";
                            $variablesPag.="&codProyecto=".$_REQUEST['codProyecto'];
                            $variablesPag.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                            $variablesPag.="&codEstudiante=".$_REQUEST['codEstudiante'];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variablesPag=$this->cripto->codificar_url($variablesPag,$configuracion);

                            ?>
                                <tr class="centrar">
                                    <td class="centrar">
                                        <a href="<?= $pagina.$variablesPag ?>" >
                                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" width="25" height="25" border="0"><br>
                                        <font size="2"><b>Regresar</b></font>
                                        </a>
                                    </td>
                                </tr>
                </table>
              <?
    }

    function cambiarGrupo($configuracion)
    {
        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"ano_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $ano=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        $variables=array($_REQUEST['codEstudiante'],$_REQUEST['grupo'],$_REQUEST['codEspacio'],$_REQUEST['grupoAnterior'],$ano[0],$ano[1],$resultado_periodo[0][0], $resultado_periodo[0][1]);

        $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_registrado", $variables);
        $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"busqueda" );

        $cadena_sql_horario_grupo_nuevo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupo_nuevo", $variables);
        $resultado_horario_grupo_nuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_grupo_nuevo,"busqueda" );

        for($i=0;$i<count($resultado_horario_registrado);$i++)
                    {
                            for($j=0;$j<count($resultado_horario_grupo_nuevo);$j++)
                                {

                                    if(($resultado_horario_grupo_nuevo[$j])==($resultado_horario_registrado[$i]))
                                        {
                                                    echo "<script>alert ('El horario del grupo seleccionado presenta cruce con el horario que tiene inscrito el estudiante');</script>";
                                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                                    $variable.="&opcion=mostrarConsulta";
                                                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                    $this->cripto=new encriptar();
                                                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                    exit;
                                        }
                                }
                    }

               $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_ins", $variables);
               $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

               $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_cupo", $variables);
               $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );
               if ($resultado_cupoGrupo[0][0]-$resultado_cupoInscritos[0][0]<=0&&$resultado_cupoGrupo[0][1]!=$_REQUEST['proyecto'])
               {
                    echo "<script>alert ('El grupo presenta sobrecupo. No se puede realizar el cambio de grupo.');</script>";
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                    $variable.="&opcion=mostrarConsulta";
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
               }

               $cadena_sql_actualizarGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizar_grupo_espacio_oracle", $variables);
               $resultado_actualizarGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarGrupo,"" );
               $actualizo=$this->totalAfectados($configuracion, $this->accesoOracle);
               
               if($actualizo>=1)
                    {
                        $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizar_cupo", $variables);
                        $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

                        $cadena_sql_cupo_grupoAnterior=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupoAnterior", $variables);
                        $resultado_cupo_grupoAnterior=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupo_grupoAnterior,"" );

                        $disminuyeCupo=($resultado_cupo_grupoAnterior[0][1]-1);
                        $variables[6]=$disminuyeCupo;
                        $cadena_sql_actualizarCupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizar_cupoAnterior", $variables);
                        $resultado_actualizarCupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarCupo,"" );

                        $cadena_sql_actualizarGrupo=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizar_grupo_espacio_mysql", $variables);
                        $resultado_actualizarGrupo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_actualizarGrupo,"" );

                        if($resultado_actualizarGrupo==true)
                            {

                                $variablesRegistro=array($this->usuario,date('YmdGis'),'3','Cambio grupo del espacio académico',$ano[0]."-".$ano[1].", ".$_REQUEST['codEspacio'].", ".$_REQUEST['grupoAnterior'].", ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['proyecto'],$_REQUEST['codEstudiante']);

                                $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarIDRegistro", $variablesRegistro);
                                $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                echo "<script>alert ('Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";

                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                    $variable.="&opcion=mostrarConsulta";
                                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$configuracion);
        
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";

                             }else
                              {

                                    echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                                    $variablesRegistro=array($this->usuario,date('YmdGis'),'51','Conexion Error MySQL',$ano[0]."-".$ano[1].", ".$_REQUEST['codEspacio'].", ".$_REQUEST['grupoAnterior'].", ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['proyecto'],$_REQUEST['codEstudiante']);

                                    $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                    $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );
                                            
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                                    $variable.="&opcion=mostrarConsulta";
                                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                    $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    exit;

                               }

                       }else
                        {
                            echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarInscripcionEstudianteCoordinador";
                            $variable.="&opcion=mostrarConsulta";
                            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                            $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                            $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                   
                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            exit;
                         }
        }
}


?>