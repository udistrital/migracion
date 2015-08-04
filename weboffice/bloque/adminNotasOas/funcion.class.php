<?php

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
//echo "ruta funcion.class ".$configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php";
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");


class funcion_adminNotasOas extends funcionGeneral {
public $accesoMoodle;

    function __construct($configuracion, $sql) {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $this->sql=new sql_adminNotasOas();
        $this->formulario="adminNotasOas";
        
        $this->cripto=new encriptar();
	//$this->tema=$tema;
	
        /**
         * Intancia para crear la conexion ORACLE
         */
       $this->accesoOracle=$this->conectarDB($configuracion,"moodle");
       $this->accesoMoodle=$this->conectarDB($configuracion,"moodlenotas");
       
        /**
         * Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
         */
       
    }

    /**
     * Funcion que muestra el la pantalla principal de la preinscripcion
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework     
     */
   function consulta_asignaturas($configuracion)
    {
       $this->cabecera($configuracion);
       
       $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaAsignaturasMoodle"); //echo $cadena_sql;
       $rs=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "busqueda");
       //var_dump($rs);
       if($rs){
      ?>
      <table class="contenidotabla">
          <caption>..::ASIGNATURAS::..</caption>
          <tr>
              <th>C&oacute;digo</th>
              <th>Asignatura</th>
              <th>Grupo</th>
              <th>ver</th>
              <th>Importar</th>
              <th>Inconsistencias</th>
          </tr>
          <?for ($i=0; $i<count($rs); $i++){//echo "for";
            $variable=$rs[$i][1];
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consutaAsiNombre", $variable);
            $rsNom=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

          ?>
          <tr>
              <td class="cuadro_plano"><?echo $rs[$i][1]?></td>
              <td class="cuadro_plano"><?echo $rsNom[0][0]?></td>
              <td class="cuadro_plano"><?echo $rs[$i][0]?></td>
              <?
                  $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                  $variable="pagina=adminNotasOas";
                  $variable.="&opcion=consulta_notas";
                  $variable.="&asi_cod=".$rs[$i][1];
                  $variable.="&grupo=".$rs[$i][0];
                  $variable.="&accion=ver";

                  include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                  $this->cripto=new encriptar();
                  $variable=$this->cripto->codificar_url($variable,$configuracion);
              ?>
              <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/ver.png" border="0"></a></td>
              <?
                  $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                  $variable="pagina=adminNotasOas";
                  $variable.="&opcion=consulta_notas";
                  $variable.="&asi_cod=".$rs[$i][1];
                  $variable.="&grupo=".$rs[$i][0];
                  $variable.="&accion=importar";

                  include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                  $this->cripto=new encriptar();
                  $variable=$this->cripto->codificar_url($variable,$configuracion);
              ?>
              <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/agt_update-product.png" border="0"></a></td>
              <?
                  $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                  $variable="pagina=adminNotasOas";
                  $variable.="&opcion=consulta_notas";
                  $variable.="&asi_cod=".$rs[$i][1];
                  $variable.="&grupo=".$rs[$i][0];
                  $variable.="&accion=noImporta";

                  include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                  $this->cripto=new encriptar();
                  $variable=$this->cripto->codificar_url($variable,$configuracion);
              ?>
              <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/agt_update_critical.png" border="0"></a></td>
          </tr>
          <?}?>
      </table>
      <?
       }//fin if($rs)
       else{
           echo "No hay Asignaturas";
       }
    }

    function cabecera($configuracion)
    {
         ?>
           <table class='contenidotabla centrar'>
                <tr align="center">
                    <td class="centrar"><h4>OFICINA ASESORA DE SISTEMAS</h4>
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png " alt="Logo Universidad">
                    </td>
                </tr>
                <tr align="center">
                    <td class="centrar"><h4>MOODLE<br>
                        UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS</h4>
                      <hr noshade class="hr">
                    </td>
                </tr>
           </table>
        <?    
    }

    function consulta_asig($configuracion){
       $this->cabecera($configuracion);
       $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaAsignaturas"); //echo $cadena_sql;
       $rs=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "busqueda");

       if ($rs){
           ?>
            <table class="contenidotabla">
          <caption>..::ASIGNATURAS::..</caption>
          <tr>
              <th>C&oacute;digo</th>
              <th>Asignatura</th>
              <th>ver</th>
              <th>Importar</th>
              <th>Inconsistencias</th>
          </tr>
          <?for ($i=0; $i<count($rs); $i++){//echo "for";
            $variable=$rs[$i][0];
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consutaAsiNombre", $variable);//secho $cadena_sql;
            $rsNom=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

          ?>
          <tr>
              <td class="cuadro_plano"><?echo $rs[$i][0]?></td>
              <td class="cuadro_plano"><?echo $rsNom[0][0]?></td>
              <?
                  $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                  $variable="pagina=adminNotasOas";
                  $variable.="&opcion=consulta_notas_x_asi";
                  $variable.="&asi_cod=".$rs[$i][0];
                  $variable.="&accion=ver";

                  include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                  $this->cripto=new encriptar();
                  $variable=$this->cripto->codificar_url($variable,$configuracion);
              ?>
              <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/ver.png" border="0"></a></td>
              <?
                  $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                  $variable="pagina=adminNotasOas";
                  $variable.="&opcion=consulta_notas_x_asi";
                  $variable.="&asi_cod=".$rs[$i][0];
                  $variable.="&accion=importar";

                  include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                  $this->cripto=new encriptar();
                  $variable=$this->cripto->codificar_url($variable,$configuracion);
              ?>
              <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/agt_update-product.png" border="0"></a></td>
              <?
                  $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                  $variable="pagina=adminNotasOas";
                  $variable.="&opcion=consulta_notas_x_asi";
                  $variable.="&asi_cod=".$rs[$i][0];
                  $variable.="&accion=noImporta";

                  include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                  $this->cripto=new encriptar();
                  $variable=$this->cripto->codificar_url($variable,$configuracion);
              ?>
              <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/agt_update_critical.png" border="0"></a></td>
          </tr>
          <?}?>
      </table>
           <?
       }

    }//fin funcion consulta_asig


     function consulta_notas($configuracion){
        $this->cabecera($configuracion);
        $asi_cod=$_REQUEST['asi_cod'];
        $grupo=$_REQUEST['grupo'];
        $accion=$_REQUEST['accion'];

        if ($accion == "ver"){
            $variable=array($asi_cod, $grupo);
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaTodos", $variable);//echo $cadena_sql;
            $rs=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "busqueda");
            $msj="No hay notas para el grupo seleccionado";
        }
        else if($accion == "importar"){
            $variable=array($asi_cod, $grupo, $estado='0');
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consultanotas", $variable);//echo $cadena_sql;
            $rs=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "busqueda");
            $msj="No hay notas para el grupo seleccionado";
        }
        else if($accion == "noImporta"){
            $variable=array($asi_cod, $grupo, $estado='-1');
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consultanotas", $variable);//echo $cadena_sql;
            $rs=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "busqueda");
            $msj="No hay inconsistencias para el grupo seleccionado";
        }
        $variable=$asi_cod;
        $cadena_sql=$this->sql->cadena_sql($configuracion, "consutaAsiNombre", $variable);
        $rsNom=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        if ($rs){
            ?>
        <form enctype='multipart/form-data' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <table class="contenidotabla">
                <caption><?echo "ASIGNATURA ".$rsNom[0][0]; ?></caption>
                <tr>
                    <th class="sigma" colspan="3" align="center">Grupo <?echo $grupo?></th>
                </tr>
                <tr>
                    <th class="sigma">C&oacute;digo</th>
                    <th class="sigma">Nombre</th>
                    <th class="sigma">Nota</th>
                </tr>
            <?
            for ($i=0;$i<count($rs);$i++){
            ?>
                <tr>
                    <td class="cuadro_plano"><?echo $rs[$i][2]?></td>
                    <td class="cuadro_plano"><?echo $rs[$i][3]." ".$rs[$i][4]?></td>
                    <td class="cuadro_plano"><?echo $rs[$i][5]?></td>
                </tr>
            <?
            }
            if ($accion == "ver"){
            ?>
            <tr>
                <td colspan="3" align="center">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="hidden" name="opcion" value="importar">
                    <input type="hidden" name="asi_cod" value="<?echo $asi_cod?>">
                    <input type="hidden" name="grupo" value="<?echo $grupo?>">
                    <input type="submit" name="cancelar" value="Volver">
                </td>
            </tr>
            <?}
            else if ($accion == "importar" || $accion == "noImporta"){
            ?>
            <tr>
                <td colspan="3" align="center"><input type="button" name="enviar" value="Importar a Condor" onClick="if (confirm('Realmente desea importar las notas a C&oacute;ndor?\n\n')){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="hidden" name="opcion" value="importar">
                    <input type="hidden" name="asi_cod" value="<?echo $asi_cod?>">
                    <input type="hidden" name="grupo" value="<?echo $grupo?>">
                    <input type="hidden" name="accion" value="<?echo $accion?>">
                    <input type="submit" name="cancelar" value="Cancelar">
                </td>
            </tr>
            <?}?>
            </table>
        </form>
            <?
        }
        else{
            ?>
            <table class="contendidotabla centrar" width="100%">
                <tr>
                    <td class="sigma centrar"><font color="red"><?echo $msj;?></font></td>
                </tr>
            </table>

            <?
        }
    }//fin funcion

    function consulta_notas_x_asi($configuracion){
        $this->cabecera($configuracion);
        $asi_cod=$_REQUEST['asi_cod'];
        $accion=$_REQUEST['accion'];

        if ($accion == "ver"){
            $variable=$asi_cod;
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaTodosAsi", $variable);//echo $cadena_sql;
            $rs=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "busqueda");
            $msj="No hay notas para el grupo seleccionado";
        }
        else if($accion == "importar"){
            $variable=array($asi_cod, $estado='0');
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consultanotasAsi", $variable);//echo $cadena_sql;
            $rs=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "busqueda");
            $msj="No hay notas para el grupo seleccionado";
        }
        else if($accion == "noImporta"){
            $variable=array($asi_cod, $estado='-1');
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consultanotasAsi", $variable);//echo $cadena_sql;
            $rs=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "busqueda");
            $msj="No hay inconsistencias para el grupo seleccionado";
        }
        $variable=$asi_cod;
        $cadena_sql=$this->sql->cadena_sql($configuracion, "consutaAsiNombre", $variable);
        $rsNom=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

        if ($rs){
            ?>
        <form enctype='multipart/form-data' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <table class="contenidotabla">
                <caption><?echo "ASIGNATURA ".$rsNom[0][0]; ?></caption>
                <tr>
                    <th class="sigma">C&oacute;digo</th>
                    <th class="sigma">Nombre</th>
                    <th class="sigma">Grupo</th>
                    <?if ($accion == "noImporta"){?>
                    <th class="sigma">Grupo en Condor</th>
                    <?}?>
                    <th class="sigma">Nota</th>
                </tr>
            <?
            for ($i=0;$i<count($rs);$i++){
            ?>
                <tr>
                    <td class="cuadro_plano"><?echo $rs[$i][2]?></td>
                    <td class="cuadro_plano"><?echo $rs[$i][3]." ".$rs[$i][4]?></td>
                    <td class="cuadro_plano"><?echo $rs[$i][1]?></td>
                    <?if($accion == "noImporta"){

                    $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaPeriodo");//echo $cadena_sql;
                    $rsPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                    $variable=array($rs[$i][2], $asi_cod, $rsPer[0][0], $rsPer[0][1]);
                    $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaGrupoCondor", $variable);//echo $cadena_sql;
                    $rsGrupoCondor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                    ?>
                    <td class="cuadro_plano"><?echo $rsGrupoCondor[0][0]?></td>
                    <?}?>
                    <td class="cuadro_plano"><?echo $rs[$i][5]?></td>
                </tr>
            <?
            }
            if ($accion == "ver"){
            ?>
            <tr>
                <td colspan="3" align="center">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="hidden" name="opcion" value="consultaNotasAsignatura">
                    <input type="submit" name="cancelar" value="Volver">
                </td>
            </tr>
            <?}
            else if ($accion == "importar" || $accion == "noImporta"){
            ?>
            <tr>
                <td colspan="3" align="center"><input type="button" name="enviar" value="Importar a Condor" onClick="if (confirm('Realmente desea importar las notas a C&oacute;ndor?\n\n')){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input type="hidden" name="opcion" value="importarAsi">
                    <input type="hidden" name="asi_cod" value="<?echo $asi_cod?>">
                    <input type="hidden" name="accion" value="<?echo $accion?>">
                    <input type="submit" name="cancelar" value="Cancelar">
                </td>
            </tr>
            <?}?>
            </table>
        </form>
            <?
        }
        else{
            ?>
            <table class="contendidotabla centrar" width="100%">
                <tr>
                    <td class="sigma centrar"><font color="red"><?echo $msj;?></font></td>
                </tr>
            </table>

            <?
        }
    }//fin funcion

    function importar($configuracion){
        $asi_cod=$_REQUEST['asi_cod'];
        $grupo=$_REQUEST['grupo'];
        $accion=$_REQUEST['accion'];

	$cadena_sql=$this->sql->cadena_sql($configuracion, "anioper", $variable);
	$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
	//echo $cadena_sql."mmm<br>";
	$ano=$resultAnioPer[0][0];
	$per=$resultAnioPer[0][1];
	//echo $resultAnioPer[0][0]."mmm";
	$porcentaje='10';
	$variable=array($asi_cod, $grupo,$porcentaje);
	$cadena_sql=$this->sql->cadena_sql($configuracion, "actualizaPorcentaje", $variable); //echo $cadena_sql;exit;
	$rsCurso=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");

        if($accion == 'importar')
	{
            $variable=array($asi_cod, $grupo, $estado='0');
	}
        else if($accion == 'noImporta')
	{
            $variable=array($asi_cod, $grupo, $estado='-1');
	}
        
        $cadena_sql=$this->sql->cadena_sql($configuracion, "consultanotas", $variable);//echo $cadena_sql;
        $rs=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "busqueda");///var_dump($rs);

        if ($rs){//var_dump($rs);
            for ($i=0; $i<count($rs); $i++){
                $variable=array($asi_cod, $grupo, $rs[$i][2]);
                $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaCondor", $variable);//echo "<br>".$cadena_sql."<br>";
                $rsCondor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");//var_dump($rsCondor);
                if (is_array($rsCondor)){
                    if ($rs[$i][5]>=0 && $rs[$i][5]<=50){
			  $variable=array($rs[$i][5],$asi_cod, $grupo, $rs[$i][2]);
			  $cadena_sql=$this->sql->cadena_sql($configuracion, "actualizaAcins", $variable);//echo "<br>".$cadena_sql."<br>";
			  $rsUpdate=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");//echo "rs";exit;

			  $calc= "BEGIN pck_pr_notaspar.pra_calnotdef_cur(".$ano.", ".$per.", ".$asi_cod.", ".$grupo."); END; ";
			  $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $calc, "calc");
			
			  //echo  $calc."mmm<br>";
			  //exit;
    
			  if ($rsUpdate){
				$estado='1';
				$variable=array($rs[$i][6], $estado);
				$cadena_sql=$this->sql->cadena_sql($configuracion, "actualizaEstadoVista", $variable);//echo $cadena_sql;
				$rsMoodle=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "");//var_dump($rsMoodle);exit;
			  }
                        else{
                            $estado='-1';
                            $variable=array($rs[$i][6], $estado);
                            $cadena_sql=$this->sql->cadena_sql($configuracion, "actualizaEstadoVista", $variable);//echo $cadena_sql;
                            $rsMoodle=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "");
                        }
                    }
                }
                else{
                    $estado='-1';
                    $variable=array($rs[$i][6], $estado);
                    $cadena_sql=$this->sql->cadena_sql($configuracion, "actualizaEstadoVista", $variable);
                    $rsMoodle=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "");
                }
            }//fin for
            echo "<script>alert('Proceso Finalizado')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminNotasOas";

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";


        }//fin if ($rs)
    }//fin function importar()

    function importarAsi($configuracion){
        $asi_cod=$_REQUEST['asi_cod'];
        $accion=$_REQUEST['accion'];

	$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
	$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
	
	$ano=$resultAnioPer[0][0];
	$per=$resultAnioPer[0][1];

        if (is_array($rsAsi)){
            for ($i=0; $i<count($rsAsi);$i++){
                $porcentaje='15';
                $variable=array($rsAsi[$i][1], $rsAsi[$i][0],$porcentaje);
                $cadena_sql=$this->sql->cadena_sql($configuracion, "actualizaPorcentaje", $variable);//echo $cadena_sql;exit;
                //$rsCurso=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
            }
        }

        
        if($accion == 'importar')
            $variable=array($asi_cod, $estado='0');
        else if($accion == 'noImporta')
            $variable=array($asi_cod, $estado='-1');


        $cadena_sql=$this->sql->cadena_sql($configuracion, "consultanotasAsi", $variable);//echo $cadena_sql;
        $rs=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "busqueda");///var_dump($rs);



        if ($rs){//var_dump($rs);
            for ($i=0; $i<count($rs); $i++){
                if ($rs[$i][1] >0){
		$grupo=$rs[$i][1];
                $variable=array($asi_cod, $rs[$i][1], $rs[$i][2]);
                $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaCondor", $variable);//echo "<br>consulta".$cadena_sql."<br>";
                $rsCondor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");//var_dump($rsCondor);
                if (is_array($rsCondor)){
                    if ($rs[$i][5]>=0 && $rs[$i][5]<=50){
                        $variable=array($rs[$i][5],$asi_cod, $rs[$i][1], $rs[$i][2]);
                        $cadena_sql=$this->sql->cadena_sql($configuracion, "actualizaAcins", $variable);//echo "<br>actualiza".$cadena_sql."<br>";
                        $rsUpdate=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");//echo "rs";exit;

			 $calc= "BEGIN pck_pr_notaspar.pra_calnotdef_cur(".$ano.", ".$per.", ".$asi_cod.", ".$grupo."); END; ";
			 $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $calc, "calc");
		
                        if ($rsUpdate){
                            $estado='1';
                            $variable=array($rs[$i][6], $estado);
                            $cadena_sql=$this->sql->cadena_sql($configuracion, "actualizaEstadoVista", $variable);//echo $cadena_sql;
                            $rsMoodle=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "");//var_dump($rsMoodle);exit;
                        }
                        else{
                            $estado='-1';
                            $variable=array($rs[$i][6], $estado);
                            $cadena_sql=$this->sql->cadena_sql($configuracion, "actualizaEstadoVista", $variable);//echo $cadena_sql;
                            $rsMoodle=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "");
                        }//echo $cadena_sql;var_dump($rsMoodle);exit;
                    }
                }
                else{
                    $estado='-1';
                    $variable=array($rs[$i][6], $estado);
                    $cadena_sql=$this->sql->cadena_sql($configuracion, "actualizaEstadoVista", $variable);//echo $cadena_sql;
                    $rsMoodle=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "");//var_dump($rsMoodle);
                }
                }//fin if
                else{
                    $estado='-1';
                    $variable=array($rs[$i][6], $estado);
                    $cadena_sql=$this->sql->cadena_sql($configuracion, "actualizaEstadoVista", $variable);//echo $cadena_sql;
                    $rsMoodle=$this->ejecutarSQL($configuracion, $this->accesoMoodle, $cadena_sql, "");//var_dump($rsMoodle);
                }
            }//fin for
//            echo $cadena_sql;
//            exit;
            echo "<script>alert('Proceso Finalizado')</script>";
            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
            $variable="pagina=adminNotasOas";
            $variable.="&opcion=consultaNotasAsignatura";

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);

            echo "<script>location.replace('".$pagina.$variable."')</script>";


        }//fin if ($rs)
    }//fin function importar()

   
}
?>
