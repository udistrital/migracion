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

class funciones_adminEspaciosHorariosProyecto extends funcionGeneral
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
		$this->formulario="adminEspaciosHorariosProyecto";
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		$this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

                $this->verificar="control_vacio(".$this->formulario.",'numero')";
                $this->verificar.="&&verificar_numero(".$this->formulario.",'numero')";
                $this->verificar.="&&verificar_rango(".$this->formulario.",'numero','0','99')";

	}


        

function verHorarios($configuracion)
        {
            $variable=$this->identificacion;

            $cadena_sql_datos_coordinador=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datos_coordinador",$variable);//echo $cadena_sql_estudiantes;exit;
            $resultado_datos_coordinador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_datos_coordinador,"busqueda" );

            ?>

             <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr align="center">
                            <td class="centrar" colspan="4">
                                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                           </td>
                        </tr>
                        <tr align="center">
                            <td class="centrar" colspan="4">
                                <h4>Horario de Espacios acad&eacute;micos por proyecto curricular</h4>
                                          <hr noshade class="hr">

                          </td>
                        </tr>
                        <tr align="center">
                            <td class="centrar" colspan="4">
                                <a href="javascript:history.back()">
                                      <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" width="35" height="35" border="0"><br>Regresar
                                </a>
                            </td>
                        </tr><br>
            <?
            $planEstudio=$resultado_datos_coordinador[0][1];
            $codProyecto=$resultado_datos_coordinador[0][0];

            $variable=array($planEstudio,$codProyecto);

            $cadena_sql_espaciosCarrera=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"espacios_carrera",$variable);//echo $cadena_sql_estudiantes;exit;
            $resultado_espaciosCarrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_espaciosCarrera,"busqueda" );

            for($z=0;$z<count($resultado_espaciosCarrera);$z++)
                {
                    
                    $variables[0]=$resultado_espaciosCarrera[$z][0];
                    $variables[1]=$codProyecto;
                    $cadena_sql_grupos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"grupos_proyecto", $variables);//echo $cadena_sql_grupos."<br><br><br>";
                    $resultado_grupos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_grupos,"busqueda" );

                    if($resultado_grupos[0][0]!=NULL){
                            ?>
                            
                                            <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                                <thead class='texto_subtitulo cuadro_color centrar'>
                                                <td><center><?echo $resultado_espaciosCarrera[$z][0]." - ".$resultado_espaciosCarrera[$z][1];?></center></td>
                                                </thead>
                                                <tr>
                                                    <td>
                                                        <table class='contenidotabla'>
                                                            <tr class="cuadro_color">
                                                                <td class='cuadro_plano centrar' width="25">Grupo </td>
                                                                <td class='cuadro_plano centrar' width="60">Lun </td>
                                                                <td class='cuadro_plano centrar' width="60">Mar </td>
                                                                <td class='cuadro_plano centrar' width="60">Mie </td>
                                                                <td class='cuadro_plano centrar' width="60">Jue </td>
                                                                <td class='cuadro_plano centrar' width="60">Vie </td>
                                                                <td class='cuadro_plano centrar' width="60">S&aacute;b </td>
                                                                <td class='cuadro_plano centrar' width="60">Dom </td>
                                                            </tr>

                            <?


                        for($j=0;$j<count($resultado_grupos);$j++)
                        {
                                    $variables[3]=$resultado_grupos[$j][0];

                                    $cadena_sql_horarios=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horario_grupos", $variables);
                                    $resultado_horarios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_horarios,"busqueda" );

                                   ?><tr>
                                       <td class='cuadro_plano centrar'>
                                            
                                            <?
                                                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                    $variable="pagina=adminEspaciosHorarios";
                                                    $variable.="&opcion=verEstudiantes";
                                                    $variable.="&grupo=".$resultado_grupos[$j][0];;
                                                    $variable.="&codProyecto=".$codProyecto;
                                                    $variable.="&planEstudio=".$planEstudio;
                                                    $variable.="&idEspacio=".$resultado_espaciosCarrera[$z][0];
                                                    $variable.="&nombreEspacio=".$resultado_espaciosCarrera[$z][1];
                                                    
                                                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                    $this->cripto=new encriptar();
                                                    $variable=$this->cripto->codificar_url($variable,$configuracion);

                                                ?>
                                                <a href="<?= $pagina.$variable ?>" >
                                                    <?echo $resultado_grupos[$j][0];?><br><font size="1">Ver Estudiantes</font>
                                                </a>
                                       </td><?
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
                                    ?>
                                   </tr>
                                    <?
                          }
                                    ?>
                                            </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        
                         <?
                }
                        else
                            {
                                ?>
                                    <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                        <thead class='texto_subtitulo cuadro_color centrar'>
                                        <td><center><?echo $resultado_espaciosCarrera[$z][0]." - ".$resultado_espaciosCarrera[$z][1]?></center></td>
                                        </thead>
                                        <tr class="cuadro_plano centrar">
                                            <td class="cuadro_plano centrar">
                                                No tiene grupos registrados.
                                            </td>
                                        </tr>
                                    </table>
                                 <?
                            }
                    
        }

        ?>
             </table>
        <?
    }

    function verEstudiantes($configuracion)
    {
        $variable=array($_REQUEST['codProyecto'],$_REQUEST['idEspacio'],$_REQUEST['grupo']);

        $cadena_sql_estudiantes=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"estudiantes_inscritos",$variable);//echo $cadena_sql_estudiantes;exit;
        $resultado_estudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_estudiantes,"busqueda" );

            ?>

             <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr align="center">
                            <td class="centrar" colspan="4">
                                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                           </td>
                        </tr>
                        <tr align="center">
                            <td class="centrar" colspan="4">
                                <h4>ESTUDIANTES QUE PERTENECEN AL GRUPO <?echo $_REQUEST['grupo']?> DE <?echo $_REQUEST['nombreEspacio']?></h4>
                                          <hr noshade class="hr">

                          </td>
                        </tr>
                        <tr align="center">
                            <td class="centrar" colspan="4">
                                <a href="javascript:history.back()">
                                      <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" width="35" height="35" border="0"><br>Regresar
                                </a>
                            </td>
                        </tr>
                        <?
                        if($resultado_estudiantes!=NULL){
                        ?>
                        <tr class="cuadro_color centrar">
                            <td>
                                C&oacute;digo
                            </td>
                            <td>
                                Nombre Estudiante
                            </td>
                            <td>
                                Proyecto Curricular
                            </td>
                        </tr>
            <?
            
        for($i=0;$i<count($resultado_estudiantes);$i++)
        {
                ?>
                        <tr>
                            <td class="cuadro_plano centrar">
                                <?echo $resultado_estudiantes[$i][0]?>
                            </td>
                            <td class="cuadro_plano">
                                <?echo $resultado_estudiantes[$i][1]?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $resultado_estudiantes[$i][2]?>
                            </td>
                        </tr>
                <?
        }
        ?>
        </table>
        <?
    }else
        {
            ?>
                <tr>
                    <td class="cuadro_plano centrar" colspan="4">
                        No existen estudiantes inscritos.
                    </td>
                </tr>
            <?
        }

    }

}
?>
