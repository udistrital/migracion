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

class funciones_registroCambiarGrupo extends funcionGeneral
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
		$this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		$this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

		//Datos de sesion
		$this->formulario="registroCambiarGrupo";
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		$this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");



	}


        function buscarGrupos($configuracion)
        {
//           $codigoEstudiante='20092073012';
//           $codEspacio='1';
//           $planEstudio='223';
//           $proyecto='73';
//           $nombre='Calculo Diferencial';
//           $grupo='67';

           $codigoEstudiante=$_REQUEST['codEstudiante'];
           $codEspacio=$_REQUEST['codEspacio'];
           $planEstudio=$_REQUEST['planEstudio'];
           $proyecto=$_REQUEST['proyecto'];
           $nombre=$_REQUEST['nombre'];
           $grupo=$_REQUEST['grupo'];

           switch($proyecto)
            {
                case '485':

                        $proyecto='85';
                    break;

                case '478':

                        $proyecto='78';
                    break;


            }

           $variables=array($codEspacio,$proyecto,$planEstudio,$grupo);

           $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"grupos_proyecto", $variables);
           $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

                ?>
          <table width="70%" align="center" border="0" >
                    <tr class="bloquelateralcuerpo">
                            <td class="centrar">
                            <?
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $variable="pagina=cambiarGrupo";
                                            $variable.="&opcion=buscar";
                                            $variable.="&grupo=".$grupo;
                                            $variable.="&codEstudiante=".$codigoEstudiante;
                                            $variable.="&codEspacio=".$codEspacio;
                                            $variable.="&proyecto=".$proyecto;
                                            $variable.="&planEstudio=".$planEstudio;
                                            $variable.="&nombre=".$nombre;

                                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                            $this->cripto=new encriptar();
                                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            ?>
                            <a href="<?= $pagina.$variable ?>" >
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="30" height="30"  border="0"><br><b>Grupos del Proyecto Curricular</b>
                            </a>
                            </td>
                            <td class="centrar">
                            <?
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $variable="pagina=cambiarGrupo";
                                            $variable.="&opcion=otrosGrupos";
                                            $variable.="&grupo=".$grupo;
                                            $variable.="&codEstudiante=".$codigoEstudiante;
                                            $variable.="&codEspacio=".$codEspacio;
                                            $variable.="&proyecto=".$proyecto;
                                            $variable.="&planEstudio=".$planEstudio;
                                            $variable.="&nombre=".$nombre;

                                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                            $this->cripto=new encriptar();
                                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            ?>
                            <a href="<?= $pagina.$variable ?>" >
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="30" height="30"  border="0"><br><b>Grupos de otros Proyecto Curricular</b>
                            </a>
                            </td>
                            </tr>
                    </table>
                    <?
                    if($resultado_grupos!=NULL){
                    ?>
                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
			<tbody>
                            <tr>
                                <td>
                                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <thead class='texto_subtitulo centrar'>
                                        <td><center><?echo $codEspacio." - ".$nombre;?></center></td>
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
                                                        <td class='cuadro_plano centrar' width="20">Cupo </td>
                                                        <td class='cuadro_plano centrar' >Cambiar Grupo</td>
                                                    </thead>

                <?


                for($j=0;$j<count($resultado_grupos);$j++){

                    $variables[3]=$resultado_grupos[$j][0];

                    $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos", $variables);
                    $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );
                    $cupoDisponible=($resultado_horarios[$j][4]-$resultado_horarios[$j][5]);

                    $variableCupo=array($codigoEstudiante,$resultado_grupos[$j][0],$codEspacio);


                    $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_ins", $variableCupo);
                    $resultado_cupoInscritos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                    $cadena_sql_cupoGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cupo_grupo_cupo", $variableCupo);
                    $resultado_cupoGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_cupoGrupo,"busqueda" );

                    unset($cupoDisponible);
                    $cupoDisponible=(($resultado_cupoGrupo[0][0])-($resultado_cupoInscritos[0][0]));
                    ?><tr><td class='cuadro_plano centrar'><?echo $resultado_grupos[$j][0];?></td><?
                    for($i=1; $i<8; $i++)
                    {
                        ?><td class='cuadro_plano centrar'><?
                        for ($k=0;$k<count($resultado_horarios);$k++)
                        {

                            if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                            {
                                $l=$k;
                                while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                                {

                                    $m=$k;
                                    $m++;
                                    $k++;
                                }
                                $dia="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                echo $dia."<br>";
                                unset ($dia);
                            }
                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0])
                            {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                    echo $dia."<br>";
                                    unset ($dia);
                                    $k++;
                            }
                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3]))
                            {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                    echo $dia."<br>";
                                    unset ($dia);
                            }
                            elseif ($resultado_horarios[$k][0]!=$i)
                            {

                            }
                        }
                        ?></td><?
                    }
                    ?><td class='cuadro_plano centrar'><?echo $cupoDisponible?></td>
                      <td class='cuadro_plano centrar'>

                            <?

                            if($this->nivel==28){

                            ?>
                                   <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>

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
                                                }
                           else if($this->nivel==52){


                                    if($cupoDisponible>0){
                          ?>

                                  <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>

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
                                                        }
                          else{
                                         echo "No puede cambiar por cupo";?></td><?
                              }
                           ?>
                                                     </tr>
                    <?}
                }


                ?>
                                                </table>
                                            </td>

                                        </tr>

                                    </table>
                                </td>
                            </tr>
                            <?
                                }else
                                    {
                                        ?>
                                            <tr>
                                                <td class="cuadro_plano centrar">
                                                    No existen grupos registrados
                                                </td>
                                            </tr>
                                        <?
                                    }
                            ?>
			</tbody>
		</table>
                <table class="contenido_tabla centrar" width="100%">
                <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variablesPag="pagina=adminInscripcionCreditos";
                            $variablesPag.="&opcion=mostrarConsulta";
                            $variablesPag.="&codEstudiante=".$_REQUEST["codEstudiante"];

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
        $codEspacio=$_REQUEST['codEspacio'];
        $planEstudio=$_REQUEST['planEstudio'];
        $proyecto=$_REQUEST['proyecto'];
        $nombre=$_REQUEST['nombre'];
        $grupo=$_REQUEST['grupo'];

        switch($proyecto)
            {
                case '485':

                        $proyecto='85';
                    break;

                case '478':

                        $proyecto='78';
                    break;


            }

        $variables=array($codEspacio,$proyecto,$planEstudio,$grupo);

         $cadena_sql_carreras=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscar_carrerasAbiertas", $variables);
        $resultadoAdicionesAbiertas=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_carreras,"busqueda" );

        
                ?>
                    <table width="70%" align="center" border="0" >
                    <tr class="bloquelateralcuerpo">
                            <td class="centrar">
                            <?
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $variable="pagina=cambiarGrupo";
                                            $variable.="&opcion=buscar";
                                            $variable.="&grupo=".$grupo;
                                            $variable.="&codEstudiante=".$codigoEstudiante;
                                            $variable.="&codEspacio=".$codEspacio;
                                            $variable.="&proyecto=".$proyecto;
                                            $variable.="&planEstudio=".$planEstudio;
                                            $variable.="&nombre=".$nombre;

                                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                            $this->cripto=new encriptar();
                                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            ?>
                            <a href="<?= $pagina.$variable ?>" >
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="30" height="30"  border="0"><br><b>Grupos del Proyecto Curricular</b>
                            </a>

                            <td class="centrar">
                            <?
                                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $variable="pagina=cambiarGrupo";
                                            $variable.="&opcion=otrosGrupos";
                                            $variable.="&grupo=".$grupo;
                                            $variable.="&codEstudiante=".$codigoEstudiante;
                                            $variable.="&codEspacio=".$codEspacio;
                                            $variable.="&proyecto=".$proyecto;
                                            $variable.="&planEstudio=".$planEstudio;
                                            $variable.="&nombre=".$nombre;

                                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                            $this->cripto=new encriptar();
                                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            ?>
                            <a href="<?= $pagina.$variable ?>" >
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/xmag.png" width="30" height="30"  border="0"><br><b>Grupos en otros<br>Proyectos Curriculares</b>
                            </a>
                            </td>
                            </tr>
                    </table>
                    <?
                    if(is_array($resultadoAdicionesAbiertas))
                        {
                        for($p=0;$p<count($resultadoAdicionesAbiertas);$p++)
                        {
                            $variables[4]=$resultadoAdicionesAbiertas[$p][0];

                            $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"otros_grupos", $variables);
                            $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

                    if($resultado_grupos!=NULL){
                    ?>
                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
			<tbody>
                            <tr>
                                <td>
                                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <thead class='texto_subtitulo centrar'>
                                        <td><center><?echo $codEspacio." - ".$nombre;?></center></td>
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

                for($j=0;$j<count($resultado_grupos);$j++){

                    $variables[3]=$resultado_grupos[$j][0];

                    $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_otros_grupos", $variables);
                    $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                    $variableCupo=array($codigoEstudiante,$resultado_grupos[$j][0],$codEspacio);
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
                    for($i=1; $i<8; $i++)
                    {
                        ?><td class='cuadro_plano centrar'><?
                        for ($k=0;$k<count($resultado_horarios);$k++)
                        {

                            if ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                            {
                                $l=$k;
                                while ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][1]==($resultado_horarios[$k][1]+1) && $resultado_horarios[$k+1][3]==($resultado_horarios[$k][3]))
                                {

                                    $m=$k;
                                    $m++;
                                    $k++;
                                }
                                $dia="<strong>".$resultado_horarios[$l][1]."-".($resultado_horarios[$m][1]+1)."</strong><br>".$resultado_horarios[$l][2]."<br>".$resultado_horarios[$l][3];
                                echo $dia."<br>";
                                unset ($dia);
                            }
                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]!=$resultado_horarios[$k+1][0])
                            {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                    echo $dia."<br>";
                                    unset ($dia);
                                    $k++;
                            }
                            elseif ($resultado_horarios[$k][0]==$i && $resultado_horarios[$k][0]==$resultado_horarios[$k+1][0] && $resultado_horarios[$k+1][3]!=($resultado_horarios[$k][3]))
                            {
                                    $dia="<strong>".$resultado_horarios[$k][1]."-".($resultado_horarios[$k][1]+1)."</strong><br>".$resultado_horarios[$k][2]."<br>".$resultado_horarios[$k][3];
                                    echo $dia."<br>";
                                    unset ($dia);
                            }
                            elseif ($resultado_horarios[$k][0]!=$i)
                            {

                            }
                        }
                        ?></td><?
                    }
                    ?><td class='cuadro_plano centrar'><?echo $cupoDisponible?></td>
                      <td class='cuadro_plano centrar'>

                        <?
                       if($cupoDisponible>0){
                          ?>

                                  <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>

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
                                                        }
                          else{
                                         echo "No puede cambiar por cupo";?></td><?
                              }
                           ?>


                                                     </tr>
                    <?}



                ?>
                                                </table>
                                            </td>

                                        </tr>

                                    </table>
                                </td>
                            </tr>
                            <?
                }else {
                    ?>
                    <tr>
                        <td class="cuadro_plano centrar">
                            No existen horarios en la carrera <?echo $variables[3]?>
                        </td>
                    </tr>
                <?
                }

                }

                }
                else {
                    ?>
                    <tr>
                        <td class="cuadro_plano centrar">

                        </td>
                    </tr>
                    <?}
                ?>
			</tbody>
		</table>
                <table class="contenido_tabla centrar" width="100%">
                <?
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variablesPag="pagina=adminInscripcionCreditos";
                            $variablesPag.="&opcion=mostrarConsulta";
                            $variablesPag.="&codEstudiante=".$_REQUEST["codEstudiante"];

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


        $cadena_sql_periodo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"año_periodo", "");
        $resultado_periodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_periodo,"busqueda" );

        $año=array($resultado_periodo[0][0],$resultado_periodo[0][1]);
        $variables=array($_REQUEST['codEstudiante'],$_REQUEST['grupo'],$_REQUEST['codEspacio'],$_REQUEST['grupoAnterior'],$año[0],$año[1]);

        $cadena_sql_horario_registrado=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_registrado", $variables);//echo $cadena_sql_horario_registrado."<br>";
        $resultado_horario_registrado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_registrado,"busqueda" );

        $cadena_sql_horario_grupo_nuevo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupo_nuevo", $variables);//echo $cadena_sql_horario_grupo_nuevo;exit;
        $resultado_horario_grupo_nuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horario_grupo_nuevo,"busqueda" );

        for($i=0;$i<count($resultado_horario_registrado);$i++)
                    {
                            for($j=0;$j<count($resultado_horario_grupo_nuevo);$j++)
                                {

                                    if(($resultado_horario_grupo_nuevo[$j])==($resultado_horario_registrado[$i]))
                                        {
                                                    echo "<script>alert ('El horario del grupo seleccionado presenta cruce con el horario que usted tiene inscrito');</script>";
                                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                    $variable="pagina=adminInscripcionCreditos";
                                                    $variable.="&opcion=mostrarConsulta";
                                                    $variable.="&grupo=".$_REQUEST["grupoAnterior"];
                                                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                                    $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                                    $variable.="&proyecto=".$_REQUEST["proyecto"];
                                                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];

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

               if($resultado_cupoInscritos[0][0]<$resultado_cupoGrupo[0][0] || $this->nivel==28)
                   {

                        $cadena_sql_actualizarGrupo=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizar_grupo_espacio_oracle", $variables);//echo $cadena_sql_actualizarGrupo;exit;
                        $resultado_actualizarGrupo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_actualizarGrupo,"" );

                        if($resultado_actualizarGrupo==true)
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

                                if($resultado_actualizarGrupo==true){

                                $variablesRegistro=array($this->usuario,date('YmdGis'),'3','Cambio grupo del espacio académico',$año[0]."-".$año[1].", ".$_REQUEST['codEspacio'].", ".$_REQUEST['grupoAnterior'].", ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['proyecto'],$_REQUEST['codEstudiante']);

                                $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );

                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarIDRegistro", $variablesRegistro);
                                $resultado_buscarRegistroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

                                echo "<script>alert ('Número de transacción: ".$resultado_buscarRegistroEvento[0][0]."');</script>";

                                //echo "<script>alert ('Se ha realizado con exito el cambio de grupo. Recuerde que si el grupo no cumple con el cupo minimo, puede ser cancelado');</script>";
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=adminInscripcionCreditos";
                                    $variable.="&opcion=mostrarConsulta";
                                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$configuracion);
        //                            echo $pagina.$variable;exit;
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";}
                                    else
                                        {

                                            echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";

                                            $variablesRegistro=array($this->usuario,date('YmdGis'),'51','Conexion Error MySQL',$año[0]."-".$año[1].", ".$_REQUEST['codEspacio'].", ".$_REQUEST['grupoAnterior'].", ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['proyecto'],$_REQUEST['codEstudiante']);

                                            $cadena_sql_registroEvento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroEvento", $variablesRegistro);
                                            $resultado_registroEvento=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_registroEvento,"" );
                                            
                                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                            $variable="pagina=adminInscripcionCreditos";
                                            $variable.="&opcion=mostrarConsulta";
                                            $variable.="&grupo=".$_REQUEST["grupo"];
                                            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                            $variable.="&espacio=".$_REQUEST["espacio"];
                                            $variable.="&carrera=".$_REQUEST["carrera"];
                                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];


                                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                            $this->cripto=new encriptar();
                                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                                            exit;

                                        }
                            }
                            else
                                {
                                    echo "<script>alert ('En este momento la base de datos se encuentra ocupada, por favor intente mas tarde');</script>";
                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                    $variable="pagina=adminInscripcionCreditos";
                                    $variable.="&opcion=mostrarConsulta";
                                    $variable.="&grupo=".$_REQUEST["grupo"];
                                    $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                    $variable.="&espacio=".$_REQUEST["espacio"];
                                    $variable.="&carrera=".$_REQUEST["carrera"];
                                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    exit;
                                }

                       }
                        else
                           {
                                echo "<script>alert ('Este curso tiene el máximo número de inscritos, no puede adicionar ');</script>";
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variable="pagina=adminInscripcionCreditos";
                                $variable.="&opcion=mostrarConsulta";
                                $variable.="&grupo=".$_REQUEST["grupo"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&espacio=".$_REQUEST["espacio"];
                                $variable.="&carrera=".$_REQUEST["carrera"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                           }

    }



}


?>
