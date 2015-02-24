<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");


//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funciones_registroSalon extends funcionGeneral {
    //@ Método costructor
    function __construct($configuracion, $sql) {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        $this->sql=$sql;

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion Coordinador
        $this->accesoCoordinador=$this->conectarDB($configuracion,"coordinador");

        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $this->formulario='registroSalon';
        
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    }
    // @ Método que invoca el metodo que muestra el formulario que recepciona los datos para crear un nuevo espacio
    function registrarSalon($configuracion) {

       $variable=$this->identificacion;

        $cadena_sql_dia=$this->sql->cadena_sql($configuracion,"dia", $_REQUEST['dia']);
        $resultado_dia=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql_dia,"busqueda");

        $cadena_sql_hora=$this->sql->cadena_sql($configuracion,"hora", $_REQUEST['hora']);
        $resultado_hora=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql_hora,"busqueda");

        ?>
<form enctype='multipart/form-data' method='POST' action='index.php' name='<?$this->formulario?>'>


    <table class="cuadro_plano centrar" width="350px" >
        <thead>
        <th>DIA:&nbsp;<?echo $resultado_dia[0][0] ?></th><th>HORA:&nbsp;<?echo $resultado_hora[0][0]?></th>
        </thead>
        <tbody>
            <tr>
                <td>
                    SEDE
                </td>
                <td>
        <?
        $cadena_sql_sede=$this->sql->cadena_sql($configuracion,"sede", $_REQUEST['espacio']);
        $resultado_sede=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $cadena_sql_sede,"busqueda");
        ?>

                    <select name='sede' id='sede' onchange="xajax_salones(document.getElementById('sede').value,'<?echo $_REQUEST['hora']?>','<?echo $_REQUEST['dia']?>','<?echo $_REQUEST['capacidad']?>','<?echo $_REQUEST['periodo']?>','<?echo $_REQUEST['anio']?>')">
                        <option value="0" >Seleccione la Sede..</option>

        <?
        foreach($resultado_sede as $data) {
            echo "<option value=".$data[0].">".$data[2]."</option>";
        }
                                ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    SALON
                </td>
                <td>
                    <div name="salon" id="salon">
                        <select disabled=yes >
                            <option>Seleccione el Salon</option>
                        </select>
                    </div>
                </td>
            </tr>

        </tbody>
    </table>
    <table class="cuadro_plano centrar" width="350px"  >
        <tr>
            <td  align='center'>
                <input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
                <input type='hidden' name='action' value="<? echo $this->formulario ?>">
                <input type='hidden' name='opcion' value="guardar">
                <input type='hidden' name='dia' value="<? echo $_REQUEST['dia']?>">
                <input type='hidden' name='hora' value="<? echo $_REQUEST['hora']?>">
                <input type='hidden' name='espacio' value="<? echo $_REQUEST['espacio']?>">
                <input type='hidden' name='anio' value="<? echo $_REQUEST['anio']?>">
                <input type='hidden' name='periodo' value="<? echo $_REQUEST['periodo']?>">
                <input type='hidden' name='capacidad' value="<? echo $_REQUEST['capacidad']?>">
                <input type='hidden' name='grupo' value="<? echo $_REQUEST['grupo']?>">
                <!--<input type="hidden" name="proyecto" value="<? //+echo$resultado[2][0]?>">-->
                <input value="Guardar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="submit()">
            </td>
            <td>
                <?
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=asignarSalon";
                $variable.="&opcion=borrarSalon";
                $variable.="&dia=".$_REQUEST["dia"];
                $variable.="&hora=".$_REQUEST["hora"];
                $variable.="&espacio=".$_REQUEST["espacio"];
                $variable.="&anio=".$_REQUEST["anio"];
                $variable.="&periodo=".$_REQUEST["periodo"];
                $variable.="&grupo=".$_REQUEST["grupo"];
                $variable=$this->cripto->codificar_url($variable,$configuracion);
                ?>
                <input type="button" name="borrar" onclick="javascript:location.replace('<?echo $pagina.$variable?>')" value="Borrar">
            </td>

        </tr>
    </table>
</form>
        <?
    }

    function guardarSalon($configuracion) {


        $anio=$_REQUEST['anio'];
        $periodo=$_REQUEST['periodo'];
        $espacio=$_REQUEST['espacio'];
        $grupo=$_REQUEST['grupo'];
        $dia=$_REQUEST['dia'];
        $hora=$_REQUEST['hora'];
        $sede=$_REQUEST['sede'];
        $salon=$_REQUEST['salon'];
        $horaAnterior = $hora - 1;
        $horaSiguiente = $hora + 1;

        $buscar="select sed_nombre from gesede where sed_cod=".$sede;
        $nombre=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $buscar,"busqueda");

        $buscarexiste="select * from achorario where hor_asi_cod=".$espacio." and hor_nro=".$grupo." and hor_dia_nro=".$dia." and hor_hora=".$hora;
        $resultadoexiste=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $buscarexiste,"busqueda");

        if(isset($resultadoexiste[0][0])) {

            $buscarexisteSede="select hor_sed_cod from achorario where hor_asi_cod=".$espacio." and hor_nro=".$grupo." and hor_dia_nro=".$dia." and hor_hora=".$horaAnterior." and hor_ape_ano=".$anio." and hor_ape_per=".$periodo;
            $resultadoexisteAnterior=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $buscarexisteSede,"busqueda");

            $buscarexisteSede="select hor_sed_cod from achorario where hor_asi_cod=".$espacio." and hor_nro=".$grupo." and hor_dia_nro=".$dia." and hor_hora=".$horaSiguiente." and hor_ape_ano=".$anio." and hor_ape_per=".$periodo;
            $resultadoexisteSiguiente=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $buscarexisteSede,"busqueda");

            if(is_array($resultadoexisteAnterior) || is_array($resultadoexisteSiguiente))
                {
                    if( is_array($resultadoexisteAnterior) && ($resultadoexisteAnterior[0][0]!=$sede))
                        {
                            echo "<script>alert('Registro fallido, no puede estar en una sede diferente a mas de 1 hora');</script>";
                            echo "  <script>
                                        window.close();
                                        </script>";
                        }else if (is_array($resultadoexisteSiguiente) && ($resultadoexisteSiguiente[0][0]!=$sede))
                            {
                                echo "<script>alert('Registro fallido, no puede estar en una sede diferente a mas de 1 hora');</script>";
                                echo "  <script>
                                        window.close();
                                        </script>";
                            }
                            else
                            {
                                $borrarexiste="delete from achorario where hor_asi_cod=".$espacio." and hor_nro=".$grupo." and hor_dia_nro=".$dia." and hor_hora=".$hora;
                                $resultadoborrar=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $borrarexiste,"");

                                if($salon=="")
				{
					$salon="";
				}
				else
				{
					$insertar=" INSERT INTO achorario ";
					$insertar.="(hor_ape_ano, hor_ape_per, hor_asi_cod, hor_nro, hor_dia_nro, hor_hora, hor_sed_cod, hor_sal_cod, hor_estado)";
					$insertar.="VALUES (".$anio.",".$periodo.",".$espacio.",".$grupo.",".$dia.",".$hora.",".$sede.",".$salon.",'A')";
					
					$resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $insertar,"");
					
					//echo "mmm".$insertar."<br>";
					//exit;
				}
                                $resultado_1="Sede: ".$nombre[0][0]." <br>Salon: ".$salon;
                                if($resultado==true) {
                    //

                                   echo "  <script>
                                                eval ('window.opener.document.getElementById(\"".$dia."_".$hora."\").innerHTML=\"$resultado_1\"');
                                                alert('Registro exitoso  Sede: ".$nombre[0][0]." - Salon: ".$salon."');
                                                </script>";
                                    echo "  <script>
                                                window.close();
                                                </script>";
                                    }else {
                                            echo "<script>alert('Registro fallido');</script>";
                                            echo "  <script>
                                                        window.close();
                                                        </script>";
                                        }
                            }

                }
                else
                    {
                        $borrarexiste="delete from achorario where hor_asi_cod=".$espacio." and hor_nro=".$grupo." and hor_dia_nro=".$dia." and hor_hora=".$hora;
                        $resultadoborrar=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $borrarexiste,"");
			
			if($salon=="")
			{
				$salon="";
			}
			else
			{
				$insertar=" INSERT INTO achorario ";
				$insertar.="(hor_ape_ano, hor_ape_per, hor_asi_cod, hor_nro, hor_dia_nro, hor_hora, hor_sed_cod, hor_sal_cod, hor_estado)";
				$insertar.="VALUES (".$anio.",".$periodo.",".$espacio.",".$grupo.",".$dia.",".$hora.",".$sede.",".$salon.",'A')";

				$resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $insertar,"");
  
				//echo "mmm".$insertar."<br>";
				//exit;
				
			}
                        $resultado_1="Sede: ".$nombre[0][0]." <br>Salon: ".$salon;
                        if($resultado==true) {
            //

                           echo "  <script>
                                        eval ('window.opener.document.getElementById(\"".$dia."_".$hora."\").innerHTML=\"$resultado_1\"');
                                        alert('Registro exitoso  Sede: ".$nombre[0][0]." - Salon: ".$salon."');
                                        </script>";
                            echo "  <script>
                                        window.close();
                                        </script>";
                            }else {
                                    echo "<script>alert('Registro fallido');</script>";
                                    echo "  <script>
                                                window.close();
                                                </script>";
                                }
                    }
                    
        }else {
            $buscarexisteSede="select hor_sed_cod from achorario where hor_asi_cod=".$espacio." and hor_nro=".$grupo." and hor_dia_nro=".$dia." and hor_hora=".$horaAnterior." and hor_ape_ano=".$anio." and hor_ape_per=".$periodo;
            $resultadoexisteAnterior=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $buscarexisteSede,"busqueda");

            $buscarexisteSede="select hor_sed_cod from achorario where hor_asi_cod=".$espacio." and hor_nro=".$grupo." and hor_dia_nro=".$dia." and hor_hora=".$horaSiguiente." and hor_ape_ano=".$anio." and hor_ape_per=".$periodo;
            $resultadoexisteSiguiente=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $buscarexisteSede,"busqueda");
	    //echo $buscarexisteSede."<br>";
            if(is_array($resultadoexisteAnterior) || is_array($resultadoexisteSiguiente))
                {
                    if( is_array($resultadoexisteAnterior) && ($resultadoexisteAnterior[0][0]!=$sede))
                        {
                            echo "<script>alert('Registro fallido, no puede estar en una sede diferente a mas de 1 hora111');</script>";
                            echo "  <script>
                                        window.close();
                                        </script>";
                        }else if (is_array($resultadoexisteSiguiente) && ($resultadoexisteSiguiente[0][0]!=$sede))
                            {
                                echo "<script>alert('Registro fallido, no puede estar en una sede diferente a mas de 1 hora222');</script>";
                                echo "  <script>
                                        window.close();
                                        </script>";
                            }
                            else
                            {
                                if($salon=="")
				{
					$salon="";
				}
				else
				{
					$insertar=" INSERT INTO achorario ";
					$insertar.="(hor_ape_ano, hor_ape_per, hor_asi_cod, hor_nro, hor_dia_nro, hor_hora, hor_sed_cod, hor_sal_cod, hor_estado)";
					$insertar.="VALUES (".$anio.",".$periodo.",".$espacio.",".$grupo.",".$dia.",".$hora.",".$sede.",".$salon.",'A')";

					$resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $insertar,"");

					//echo "mmm".$insertar."<br>";
					//exit;
				}
                                $resultado_1="Sede: ".$nombre[0][0]." <br>Salon: ".$salon;

                                if($resultado==true) {
                                   echo "  <script>
                                                eval ('window.opener.document.getElementById(\"".$dia."_".$hora."\").innerHTML=\"$resultado_1\"');
                                                alert('Registro exitoso  Sede: ".$nombre[0][0]." - Salon: ".$salon."');
                                                </script>";
                                    echo "  <script>
                                                window.close();
                                                </script>";



                                }else {
                                    echo "<script>alert('Registro fallido');</script>";
                                    echo "  <script>
                                                window.close();
                                                </script>";
                                }
                            }
                }else
                    {
                        if($salon=="")
			{
				$salon="";
			}
			else
			{
				$insertar=" INSERT INTO achorario ";
				$insertar.="(hor_ape_ano, hor_ape_per, hor_asi_cod, hor_nro, hor_dia_nro, hor_hora, hor_sed_cod, hor_sal_cod, hor_estado)";
				$insertar.="VALUES (".$anio.",".$periodo.",".$espacio.",".$grupo.",".$dia.",".$hora.",".$sede.",".$salon.",'A')";

				$resultado=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $insertar,"");

				//echo "mmm".$insertar."<br>";
				//exit;
                        }
			$resultado_1="Sede: ".$nombre[0][0]." <br>Salon: ".$salon;

                        if($resultado==true) {
                           echo "  <script>
                                        eval ('window.opener.document.getElementById(\"".$dia."_".$hora."\").innerHTML=\"$resultado_1\"');
                                        alert('Registro exitoso  Sede: ".$nombre[0][0]." - Salon: ".$salon."');
                                        </script>";
                            echo "  <script>
                                        window.close();
                                        </script>";



                        }else {
                            echo "<script>alert('Registro fallido');</script>";
                            echo "  <script>
                                        window.close();
                                        </script>";
                        }
                    }
        }
    }


    function borrarSalon($configuracion) {


        $anio=$_REQUEST['anio'];
        $periodo=$_REQUEST['periodo'];
        $espacio=$_REQUEST['espacio'];
        $grupo=$_REQUEST['grupo'];
        $dia=$_REQUEST['dia'];
        $hora=$_REQUEST['hora'];
       
        $buscarexiste="select * from achorario where hor_asi_cod=".$espacio." and hor_nro=".$grupo." and hor_dia_nro=".$dia." and hor_hora=".$hora;
        $buscarexiste.=" AND hor_ape_ano= ".$anio." AND hor_ape_per=".$periodo;
        
        $resultadoexiste=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $buscarexiste,"busqueda");

        if(isset($resultadoexiste[0][0])) {
            $borrarexiste="delete from achorario where hor_asi_cod=".$espacio." and hor_nro=".$grupo." and hor_dia_nro=".$dia." and hor_hora=".$hora;
            $resultadoborrar=$this->ejecutarSQL($configuracion, $this->accesoCoordinador, $borrarexiste,"");

            $resultado_1=" - ";
            if($resultadoborrar==true) {
//

               echo "  <script>
                            eval ('window.opener.document.getElementById(\"".$dia."_".$hora."\").innerHTML=\"$resultado_1\"');
                            alert('Registro eliminado exitosamente');
                            </script>";
                echo "  <script>
                            window.close();
                            </script>";

            }else {
                echo "<script>alert('Registro fallido');</script>";
                echo "  <script>
                            window.close();
                            </script>";
            }


        }else
            {
                echo "  <script>
                            alert('No existe registro de horario para esta hora');
                            </script>";
                echo "  <script>
                            window.close();
                            </script>";
            }
    }

    public function corregirRegistro() {
    }
    public function mostrarRegistro($configuracion,$registro,$total,$opcion,$variable) {
    }
    public function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario) {
    }
    public function nuevoRegistro($configuracion,$tema,$acceso_db) {
    }
}
?>
