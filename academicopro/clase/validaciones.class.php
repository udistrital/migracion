<?php
/* 
 * Funcion que tiene todas las validaciones para el proceso de inscripciones
 * 
 */

/**
 * Permite hacer las diferentes validaciones para la inscripción de espacios académicos
 * Cada funcion recibe unos parametros especificos
 *
 * @author Edwin Sánchez
 * Fecha 03 de Septiembre de 2010
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

class validacionInscripcion {
  private $configuracion;
  private $ano;
  private $periodo;


  public function __construct() {

        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $this->configuracion=$configuracion;
        
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->cripto=new encriptar();
        $this->funcionGeneral=new funcionGeneral();

        //Conexion General
        $this->acceso_db=$this->funcionGeneral->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->funcionGeneral->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->funcionGeneral->conectarDB($configuracion,"oraclesga");

        //Datos de sesion
        $this->usuario=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        
        $this->identificacion=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $cadena_sql=$this->cadena_sql("periodoActual",'');//echo $cadena_sql;exit;
        $resultado_periodo=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];



    }

    /**
     * Función que permite validar si el estudiante pertenece al plan de estudios.
     * 
     * @param <array> $configuracion
     * @param <int> $codEstudiante
     * @return <array> $mensaje
     * 
     */

    public function validarEstudiante($codEstudiante, $planEstudio)
    {
        if(is_numeric($codEstudiante))
        {
          $cadena_sql=$this->cadena_sql("buscarInfoEstudiante",$codEstudiante);//echo $cadena_sql;exit;
          $resultado_estudiante=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

          if(trim($resultado_estudiante[0][0])=='S')
            {
                $cadena_sql_proyectos=$this->cadena_sql("proyectos_curriculares",$this->usuario);//echo $cadena_sql_proyectos;exit;
                $resultado_proyectos=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
                $tipo=0;
                for($i=0;$i<count($resultado_proyectos);$i++)
                    {
                     if($resultado_estudiante[0][1]==$resultado_proyectos[$i][0] && $resultado_estudiante[0][2]==$resultado_proyectos[$i][2])
                      {
                         $tipo=1;
                         $valor=$i;
                      }
                    }
                if($tipo==1)
                   {
                      $mensaje=array($resultado_proyectos[$valor][0],$resultado_proyectos[$valor][2],$resultado_proyectos[$valor][1]);
                   }
                   else
                   {
                    $mensaje="El estudiante con código ".$codEstudiante." no pertenece al plan de estudios ".$planEstudio." del proyecto curricular";
                   }
            }else if(trim($resultado_estudiante[0][0])=='N')
                {
                    $mensaje="El estudiante con código ".$codEstudiante." no pertenece al plan de estudios ".$planEstudio." del proyecto curricular";
                }else
                    {
                        $mensaje="El dato ingresado no corresponde a un código válido de estudiante. Digite de nuevo el código";
                    }
        }else
            {
                $mensaje="El código del estudiante debe ser numerico, digite de nuevo el código";
            }
            return $mensaje;
    }


    /**
     * Función que permite validar si el horario del nuevo espacio académico
     * presenta cruce con el horario inscrito por el estudiante.
     *
     * @param <array> $configuracion
     * @param <int> $codEstudiante
     * @param <int> $codEspacio
     * @param <int> $grupo
     * @param <array> $retorno
     * @return <boolean>
     *
     */

    public function validarCruceHorario($datosHorario)
	{
            $cadena_sql=$this->cadena_sql("horario_grupos_registrar", $datosHorario);//echo $cadena_sql;exit;
            $resultado_horarios_registrar=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            $cadena_sql=$this->cadena_sql("horario_registrado", $datosHorario);//echo $cadena_sql;exit;
            $resultado_horarios_registrado=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            if(is_array($resultado_horarios_registrado))
            {
                unset($cruce);

                for($n=0;$n<count($resultado_horarios_registrado);$n++)
                {
                    $espacio=array_pop($resultado_horarios_registrado[$n]);
                    $espacio=array_pop($resultado_horarios_registrado[$n]);
                    for($m=0;$m<count($resultado_horarios_registrar);$m++)
                    {
                        if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n]))
                        {
                            $cruce=TRUE;
                            break;
                        }
                    }
                    if(isset($cruce))
                    {
                      break;
                    }
                }
            }
            if (isset($cruce))
              {
              return $espacio;
            }
            else
              {
              return FALSE;
              }
        }

    /**
     * Función que permite verificar el estado academico del estudiante para inscribir espacios
     * si es verdadero permite inscribir
     *
     * @param <array> $configuracion
     * @param <int> $codEstudiante
     * @param <int> $codProyecto
     * @param <int> $planEstudio
     * @param <array> $retorno
     * @return <boolean>
     */
    public function validarEstadoEstudiante($codEstudiante,$retorno)
        {
            $cadena_sql=$this->cadena_sql("estado_estudiante", $codEstudiante);//echo $cadena_sql;exit;
            $resultado_estudiante=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(trim($resultado_estudiante[0][0])!='A'&& trim($resultado_estudiante[0][0])!='B')
            {
                echo "<script>alert('*El estado del estudiante (".$resultado_estudiante[0][2].") no permite adicionar espacios académicos*')</script>";
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=".$retorno['pagina'];
                $variable.="&opcion=".$retorno['opcion'];
                $variable.=$retorno['parametros'];

                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
                exit;
            }
                else
                    {
                        return true;
                    }
        }

    public function validarEspacioInscrito($datosInscripcion,$retorno)
        {
            $cadena_sql=$this->cadena_sql("consultar_espacioInscrito",$datosInscripcion);//echo $cadena_sql;exit;
            $resultado_espacioInscrito=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(is_array($resultado_espacioInscrito))
                {
                    echo "<script>alert ('*El espacio académico ya esta inscrito en el periodo actual, no se puede inscribir de nuevo*');</script>";
                    $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                    $variable="pagina=".$retorno['pagina'];
                    $variable.="&opcion=".$retorno['opcion'];
                    $variable.=$retorno['parametros'];

                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                    exit;
                }else
                    {
                        return true;
                    }
        }

/*Esta funcion valida si el espacio academico que se va a inscribir
 *  corresponde al plan de estudios del estudiante
 *
 */
    public function validarEspacioPlan($datosInscripcion,$retorno)
        {

            $cadena_sql=$this->cadena_sql("espacios_planEstudiante",$datosInscripcion);//echo $cadena_sql;exit;
            $resultado_espacioPlan=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

            if(is_array($resultado_espacioPlan))
                {
                  return true;
                }else
                    {
                        echo "<script>alert ('*El espacio académico no pertenece al plan de estudio del estudiante. No se puede inscribir el espacio académico*');</script>";
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=".$retorno['pagina'];
                        $variable.="&opcion=".$retorno['opcion'];
                        $variable.=$retorno['parametros'];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        exit;
                    }
        }

    /**
     * Función que permite validar si el horario del nuevo espacio académico
     * presenta cruce con el horario inscrito por el estudiante.
     *
     * @param <array> $configuracion
     * @param <int> $codEstudiante
     * @param <int> $codEspacio
     * @param <int> $grupo
     * @param <array> $retorno
     * @return <boolean>
     *
     */

    public function validarCruce($datosInscripcion, $retorno)
	{
            $cadena_sql=$this->cadena_sql("horario_grupos_registrar", $datosInscripcion);//echo $cadena_sql;exit;
            $resultado_horarios_registrar=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            $cadena_sql=$this->cadena_sql("horario_registrado", $datosInscripcion);//echo $cadena_sql;exit;
            $resultado_horarios_registrado=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            if(is_array($resultado_horarios_registrado))
            {
                unset($cruce);

                for($n=0;$n<count($resultado_horarios_registrado);$n++)
                {
                    $espacio=array_pop($resultado_horarios_registrado[$n]);
                    $espacio=array_pop($resultado_horarios_registrado[$n]);
                    for($m=0;$m<count($resultado_horarios_registrar);$m++)
                    {
                        if(($resultado_horarios_registrar[$m])==($resultado_horarios_registrado[$n]))
                        {
                            $cruce=TRUE;
                            break;
                        }
                    }
                    if(isset($cruce))
                    {
                      break;
                    }
                }
            }
            if (isset($cruce))
              {
                  echo "<script>alert ('*El horario del espacio académico presenta cruce con el horario del estudiante. No se ha realizado la inscripción*');</script>";
                  $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                  $variable="pagina=".$retorno['pagina'];
                  $variable.="&opcion=".$retorno['opcion'];
                  $variable.=$retorno['parametros'];

                  include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                  $this->cripto=new encriptar();
                  $variable=$this->cripto->codificar_url($variable,$configuracion);

                  echo "<script>location.replace('".$pagina.$variable."')</script>";
                  exit;
              }
              else
                {
                  return TRUE;
                }
        }


    function encabezadoSistema($configuracion)
        {
        ?>
<table class="contenidotabla centrar">
    <tr>
        <td colspan="6" class="centrar">
            SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA<br>
            <img src="<?echo $configuracion['site'].$configuracion['grafico']."/pequeno_universidad.png";?>"alt='UD' border='0'>
            <hr>
        </td>
    </tr>
</table>
        <?
        }

    function cadena_sql($tipo,$variable)
        {
            switch ($tipo)
                {
                    case 'buscarInfoEstudiante':

                        $cadena_sql="SELECT EST_IND_CRED, EST_CRA_COD, EST_PEN_NRO ";
                        $cadena_sql.="FROM ACEST ";
                        $cadena_sql.="WHERE EST_COD=".$variable;
                    break;

                    case 'proyectos_curriculares':

                        $cadena_sql="SELECT DISTINCT CRA_COD, CRA_NOMBRE, CTP_PEN_NRO";
                        $cadena_sql.=" FROM ACCRA";
                        $cadena_sql.=" INNER JOIN V_CRA_TIP_PEN ON CTP_CRA_COD=CRA_COD";
                        $cadena_sql.=" INNER JOIN ACTIPCRA ON CRA_TIP_CRA=TRA_COD";
                        $cadena_sql.=" WHERE CRA_EMP_NRO_IDEN=".$variable." AND CTP_IND_CRED LIKE '%S%'";
                        $cadena_sql.=" AND TRA_COD_NIVEL IN (2,3,4)";
                        $cadena_sql.=" ORDER BY 1,3";
                    break;

                    case 'horario_grupos_registrar':

                        $cadena_sql="SELECT DISTINCT hor_dia_nro DIA,";
                        $cadena_sql.=" hor_hora HORA";
                        $cadena_sql.=" FROM achorario";
                        $cadena_sql.=" INNER JOIN accurso ON achorario.hor_asi_cod=accurso.cur_asi_cod AND achorario.hor_nro=accurso.cur_nro";
                        $cadena_sql.=" INNER JOIN gesede ON achorario.hor_sed_cod=gesede.sed_cod";
                        $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                        $cadena_sql.=" AND cur_cra_cod=".$variable['codProyecto'];
                        $cadena_sql.=" AND hor_ape_ano=".$this->ano;
                        $cadena_sql.=" AND hor_ape_per=".$this->periodo;
                        $cadena_sql.=" AND hor_nro=".$variable['grupo'];
                        $cadena_sql.=" ORDER BY 1,2";
                        break;

                    case 'horario_registrado':

                        $cadena_sql="SELECT DISTINCT hor_dia_nro DIA,";
                        $cadena_sql.=" hor_hora HORA,";
                        $cadena_sql.=" ins_asi_cod CODIGO";
                        $cadena_sql.=" FROM achorario";
                        $cadena_sql.=" INNER JOIN acins ON achorario.hor_asi_cod=acins.ins_asi_cod AND achorario.hor_nro=acins.ins_gr";
                        $cadena_sql.=" AND achorario.hor_ape_ano=acins.ins_ano AND achorario.hor_ape_per=acins.ins_per";
                        $cadena_sql.=" WHERE acins.ins_est_cod=".$variable['codEstudiante'];
                        $cadena_sql.=" AND ins_ano=".$this->ano;
                        $cadena_sql.=" AND ins_per=".$this->periodo;
                        $cadena_sql.=" AND ins_estado LIKE '%A%'";
                        $cadena_sql.=" ORDER BY 1,2";
                        break;

                    case "consultar_espacioInscrito":

                        $cadena_sql="SELECT ins_asi_cod CODIGO,";
                        $cadena_sql.=" ins_est_cod ESTUDIANTE";
                        $cadena_sql.=" FROM acins";
                        $cadena_sql.=" WHERE ins_est_cod=".$variable['codEstudiante'];
                        $cadena_sql.=" AND ins_asi_cod=".$variable['codEspacio'];
                        $cadena_sql.=" AND ins_ano=".$this->ano;
                        $cadena_sql.=" AND ins_per=".$this->periodo;
                        $cadena_sql.=" AND ins_estado like '%A%'";
                        break;

                    case 'espacios_planEstudiante':

                        $cadena_sql="SELECT pen_asi_cod CODIGO,";
                        $cadena_sql.=" pen_nro PLANESTUDIO,";
                        $cadena_sql.=" pen_cra_cod CARRERA ";
                        $cadena_sql.=" FROM acpen";
                        $cadena_sql.=" where pen_asi_cod=".$variable['codEspacio'];
                        $cadena_sql.=" and pen_nro= ".$variable['planEstudio'];
                        $cadena_sql.=" and pen_cra_cod= ".$variable['codProyecto'];
                        $cadena_sql.=" and pen_estado like '%A%'";
                        break;

                    case "periodoActual":

                        $cadena_sql="SELECT ape_ano ANO,";
                        $cadena_sql.=" ape_per PERIODO";
                        $cadena_sql.=" FROM acasperi";
                        $cadena_sql.=" WHERE ape_estado like '%A%'";
                        break;



                      

                }
                return $cadena_sql;
        }
}
?>
