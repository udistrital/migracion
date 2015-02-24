<?php
/* 
 * Funcion que tiene todas las validaciones para el proceso de inscripciones
 * 
 */

/**
 * Permite hacer las diferentes validaciones para la inscripción de espacios académicos
 * Cada funcion recibe unos parametros especificos
 *
 * @author Fernando Torres
 * @author Milton Parra
 * Fecha 15 de Marzo de 2011
 * Modificado 25 de Junio de 2012 Insertar registros de planes, cursados y requisitos en variables de Sesion. Milton Parra
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

class procedimientos {
  private $configuracion;
  private $ano;
  private $periodo;
  public $sesion;


  public function __construct() {

        require_once("clase/config.class.php");
        $esta_configuracion=new config();
        $configuracion=$esta_configuracion->variable();
        $this->configuracion=$configuracion;
        
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");

        $this->cripto=new encriptar();
        $this->funcionGeneral=new funcionGeneral();
        $this->sesion=new sesiones($configuracion);

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

        $cadena_sql=$this->cadena_sql("periodoActual",'');
        $resultado_periodo=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];



    }

    /**
     * Función que permite actualizar los cupos de un grupo.
     * 
     * @param array $datosGrupo
     * @return <boolean>
     */
    public function actualizarCupo($datosGrupo)
        {
            $cadena_sql=$this->cadena_sql("actualizar_cupo",$datosGrupo);
            $resultado_cupo=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );

            if(is_array($resultado_cupo))
                {
                  return TRUE;
                }
                else
                {
                  return FALSE;
                }
        }


    /**
     * Función que permite registrar un evento.
     *
     * @param array $datosRegistro
     * @return boolean 
     */
    public function registrarEvento($datosRegistro)
        {
            $cadena_sql=$this->cadena_sql("registro_evento",$datosRegistro);
            $resultado_registro=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
            $registro=$this->funcionGeneral->totalAfectados($this->configuracion, $this->accesoGestion);
            if($registro>=0)
                {
                  return TRUE;
                }
                else
                {
                  return FALSE;
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
        
    /**
     * Funcion que permite registrar un arreglo en MySQL
     * @param array $arreglo
     * @param string $nombre 
     */
    function registrarArreglo($arreglo,$nombre) {
        $string=  $this->ArraytoString($arreglo);
        $string=addslashes($string);
        $sesion_id=  $this->sesion->numero_sesion();
        $cadena=  $this->sesion->guardar_valor_sesion($this->configuracion, $nombre, $string, $sesion_id);
    }
     
    /**
     * Funcion que permite registrar un arreglo en MySQL
     * @param array $arreglo
     * @param string $nombre 
     */
    function registrarArregloSesion($arreglo,$nombre) {
        $arreglo=$this->escaparCaracteresEspacios($arreglo);
        $cadenaEspaciosInscritos=json_encode($arreglo,JSON_UNESCAPED_UNICODE);
        $string=$this->escaparSlashes($cadenaEspaciosInscritos);
        $sesion_id=$this->sesion->numero_sesion();
        $cadena=$this->sesion->guardar_valor_sesion($this->configuracion, $nombre, $string, $sesion_id);
    }

    /**
     * Funcion que escapa las comillas en los nombres de los espacios
     * @param array $arreglo
     * @return array 
     */
    function escaparCaracteresEspacios($arreglo) {
        if(is_array($arreglo))
        {
            foreach ($arreglo as $clave => $espacio) {
                $arreglo[$clave]['NOMBRE']=$this->escaparCaracteresNombre($arreglo[$clave]['NOMBRE']);
                $arreglo[$clave][1]=$this->escaparCaracteresNombre($arreglo[$clave][1]);
            }
        }
        return $arreglo;
    }

    /**
     * Funcion que escapa las comillas en una cadena dada
     * @param string $nombre
     * @return string
     */
    function escaparCaracteresNombre($nombre) {
            $nombre=str_replace("'", "/'",$nombre);
            return $nombre;
    }
    
    /**
     * Funcion que elimina / de una cadena
     * @param string $cadena
     * @return string
     */
    function escaparSlashes($cadena) {
            $cadena=str_replace("/", "",$cadena);
            return $cadena;
    }
    
    /**
     * Funcion que permite convertir un arreglo en cadena
     * @param type $arreglo
     * @return string 
     */
    function ArraytoString($arreglo){
        $cadena = '';
        if (is_array($arreglo))
        {
            foreach($arreglo as $value)
            {
                foreach($value as $key=>$valor)
                {
                    if(!is_numeric($key))
                    {
                        $cadena.=$key."=>".$valor."$";
                    }            
                }
                $cadena=rtrim($cadena, '$');
                $cadena.=";"; 
            }
        }else
            {
                
            }
        return $cadena;
    }
 
    /**
     * Funcion que permite convertir una cadena en arreglo
     * @param type $cadena
     * @return type 
     */
    function stringToArray($cadena) {
        $resultado=array();
        if($cadena=='')
            {
            }else
                {
                    $cadena=rtrim($cadena, ';');
                    $array = explode(';', $cadena);
                    foreach ($array as $linea=>$fila)
                    {
                        $arreglo=explode('$', $fila);
                        foreach ($arreglo as $key=>$value) {
                            $final=explode('=>',$value);             
                            $casa[$final[0]]=$final[1];
                        }
                        $resultado[]=$casa;
                        unset($casa);
                    }
                }
        return $resultado;          
    }        

    /**
     *  Funcion que busca los espacios cancelados por estudiante
     * @param array $datosEstudiante
     * @return boolean
     */
    function buscarEspaciosCancelados($datosEstudiante) {
        $espaciosCancelados=$this->consultarEspaciosCancelados($datosEstudiante);
        return $espaciosCancelados;
    }
    
    /**
     * Funcion que consulta los espacios cancelados por estudiante
     * @param array $datosEstudiante
     * @return boolean
     */
    function consultarEspaciosCancelados($datosEstudiante) {
        $cadena_sql=$this->cadena_sql("buscarCancelados",$datosEstudiante);
        $resultado_cancelados=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
        return $resultado_cancelados;
    }
    
    /**
     *  Funcion que busca los espacios cancelados por estudiante
     * @param array $datosEstudiante
     * @return array
     */
    function buscarEspaciosInscritos($datosEstudiante) {
         $cadena=$this->sesion->rescatar_valor_sesion($this->configuracion, "inscritos");
            if($cadena=='')
            {
                $espaciosInscritos=$this->consultarEspaciosInscritos($datosEstudiante);
                $this->registrarArregloSesion($espaciosInscritos,'inscritos');
            }else
                { 
                $espaciosInscritos=json_decode($cadena[0][0],true);
                }
        return $espaciosInscritos;
    }
    
    /**
     *  Funcion que actualiza los datos de los espacios inscritos en la sesion
     * @param array $datosEstudiante
     * @return array
     */
    function actualizarInscritosSesion($datosEstudiante) {
        $espaciosInscritos=$this->consultarEspaciosInscritos($datosEstudiante);
        $this->registrarArregloSesion($espaciosInscritos,'inscritos');
    }
    
    /**
     *  Funcion que actualiza los datos de los espacios inscritos en la sesion
     * @param array $datosEstudiante
     * @return array
     */
    function actualizarPreinscritosSesion($datosEstudiante) {
        $espaciosInscritos=$this->consultarEspaciosInscritosPreinscripcion($datosEstudiante);
        $this->registrarArregloSesion($espaciosInscritos,'inscritos');
    }
    
    /**
     *  Funcion que busca los espacios cancelados por estudiante
     * @param type $datosEstudiante
     * @return type
     */
    function buscarEspaciosInscritosPreinscripcion($datosEstudiante) {
         $cadena=$this->sesion->rescatar_valor_sesion($this->configuracion, "inscritos");
            if($cadena=='')
            {
                $espaciosInscritos=$this->consultarEspaciosInscritosPreinscripcion($datosEstudiante);
                $this->registrarArregloSesion($espaciosInscritos,'inscritos');
            }else
                {
                    $espaciosInscritos=json_decode($cadena[0][0],true);
                    if ($espaciosInscritos[0]['COD_ESTUDIANTE']!=$datosEstudiante['codEstudiante'])
                    {
                        $espaciosInscritos=$this->consultarEspaciosInscritosPreinscripcion($datosEstudiante);
                        $this->registrarArregloSesion($espaciosInscritos,'inscritos');
                    }else
                        {
                        }
                }
        return $espaciosInscritos;
    }
    
    /**
     * Funcion que busca horarios
     * @param array $espaciosInscritos
     * @return array 
     */
    function buscarHorario($espaciosInscritos) {
        $cadena=$this->crearCadenaHorario($espaciosInscritos);
        $horario=$this->consultarHorario($cadena);
        return $horario;
    }

    /**
     * Funcion que crea una cadena para consultar un horario
     * modificada para ajustar a nuevas tablas de horarios, cursos y salones 04/06/2013
     * @param array $espaciosInscritos
     * @return string 
     */
    function crearCadenaHorario($espaciosInscritos) {
        $cadena='';
        foreach ($espaciosInscritos as $inscrito) {
            $cadena.="(hor_id_curso=".$inscrito['ID_GRUPO']." AND hor_alternativa =".$inscrito['HOR_ALTERNATIVO'].") OR ";
        }
        $cadena=rtrim($cadena, "OR ");
        return $cadena;
    }
    
    /**
     * Funcion que crea una cadena para consultar un horario de grupos existentes
     * Creada para ajustar a nuevas tablas de horarios, cursos y salones 04/06/2013
     * @param array $espaciosInscritos
     * @return string 
     */
    function crearCadenaIdGrupos($gruposExistentes) {
        $cadena='';
        if(is_array($gruposExistentes)&&!empty($gruposExistentes))
        {
            foreach ($gruposExistentes as $grupo) {
                $cadena.="(hor_id_curso =".$grupo['ID_GRUPO'].") OR ";
            }
        }
        $cadena=rtrim($cadena, "OR ");
        return $cadena;
    }
    
    /**
     * Funcion que consulta en la base de datos alterna el horario
     * @param array $variable
     * @return array 
     */
    function consultarHorario($variable) {
        $cadena_sql=$this->cadena_sql("consultarHorario",$variable);
        $resultado=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultado;
    }
    
    /**
     * Funcion que consulta los espacios inscritos por estudiante
     * @param array $datosEstudiante
     * @return array
     */
    function consultarEspaciosInscritos($datosEstudiante) {
        $variables=array('codEstudiante'=>$datosEstudiante['codEstudiante'],
                         'codProyectoEstudiante'=>  $datosEstudiante['codProyectoEstudiante'],
                         'ano'=>$datosEstudiante['ano'],
                         'periodo'=>$datosEstudiante['periodo']);
        $cadena_sql=$this->cadena_sql("espacios_inscritos",$variables);
        $resultado_registro=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        
        return $resultado_registro;
    }

    /**
     * Funcion que consulta los espacios cancelados por estudiante
     * @param type $datosEstudiante
     * @return type
     */
    function consultarEspaciosInscritosPreinscripcion($datosEstudiante) {       
        $variables=array('codEstudiante'=>$datosEstudiante['codEstudiante'],
                         'codProyectoEstudiante'=>  $datosEstudiante['codProyectoEstudiante'],
                         'ano'=>$datosEstudiante['ano'],
                         'periodo'=>$datosEstudiante['periodo'],
                         'planEstudioEstudiante' => $datosEstudiante['planEstudioEstudiante']);
        $cadena_sql=$this->cadena_sql("consultaPreinscripcionesEstudiante",$variables);
        $resultado_registro=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
    
        return $resultado_registro;
    }
    
    function cadena_sql($tipo,$variable)
        {
            switch ($tipo)
                {
                  case "periodoActual":

                      $cadena_sql="SELECT ape_ano ANO,";
                      $cadena_sql.=" ape_per PERIODO";
                      $cadena_sql.=" FROM acasperi";
                      $cadena_sql.=" WHERE ape_estado like '%A%'";
                      break;

                  case "espacios_inscritos":
//se actualiza para la nueva estructura de tablas 31/05/2013
                      $cadena_sql = "SELECT ins_asi_cod CODIGO,";
                      $cadena_sql.=" asi_nombre NOMBRE,";
                      $cadena_sql.=" lpad(cur_cra_cod,3,0)||'-'||cur_grupo GRUPO,";
                      $cadena_sql.=" ins_cred CREDITOS,";
                      $cadena_sql.=" ins_cea_cod CLASIFICACION,";
                      $cadena_sql.=" ins_gr ID_GRUPO,";
                      $cadena_sql.=" ins_hor_alternativo HOR_ALTERNATIVO";
                      $cadena_sql.=" FROM acins";
                      $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
                      $cadena_sql.=" INNER JOIN accursos ON cur_id=ins_gr AND cur_ape_ano=ins_ano AND cur_ape_per=ins_per";
                      $cadena_sql.=" WHERE ins_est_cod=" . $variable['codEstudiante'];
                      $cadena_sql.=" AND ins_ano=" . $variable['ano'];
                      $cadena_sql.=" AND ins_per=" . $variable['periodo'];
                      $cadena_sql.=" AND ins_estado LIKE '%A%'";
                      $cadena_sql.=" AND ins_cra_cod=" . $variable['codProyectoEstudiante'];
                      $cadena_sql.=" ORDER BY ins_asi_cod";
                      break;

                  case 'actualizar_cupo':

                      $cadena_sql="UPDATE accursos ";
                      $cadena_sql.="SET cur_nro_ins=";
                      $cadena_sql.="   (SELECT count(*) FROM acins";
                      $cadena_sql.="    WHERE ins_asi_cod = ".$variable['codEspacio'];
                      $cadena_sql.="    and ins_gr=".$variable['id_grupo'];
                      $cadena_sql.="    and ins_ano=".$this->ano;
                      $cadena_sql.="    and ins_per=".$this->periodo.")";
                      $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                      $cadena_sql.=" AND cur_id=".$variable['id_grupo'];
                      $cadena_sql.=" AND cur_ape_ano=".$this->ano;
                      $cadena_sql.=" AND cur_ape_per=".$this->periodo;
                      break;

                  case 'registro_evento':

                      $cadena_sql="insert into ".$this->configuracion['prefijo']."log_eventos ";
                      $cadena_sql.="VALUES('','".$variable['usuario']."',";
                      $cadena_sql.="'".date('YmdHis')."',";
                      $cadena_sql.="'".$variable['evento']."',";
                      $cadena_sql.="'".$variable['descripcion']."',";
                      $cadena_sql.="'".$variable['registro']."',";
                      $cadena_sql.="'".$variable['afectado']."')";
                      break;

                  case 'buscarCancelados':

                      $cadena_sql="SELECT can_idEspacio";
                      $cadena_sql.=" FROM ".$this->configuracion['prefijo']."espacios_cancelados";
                      $cadena_sql.=" WHERE can_codEstudiante=".$variable['CODIGO'];
                      $cadena_sql.=" AND can_ano=".$variable['ANO'];
                      $cadena_sql.=" AND can_periodo=".$variable['PERIODO'];
                      break;

                  case 'consultarHorario':

                      $cadena_sql=" SELECT hor_dia_nro DIA,";
                      $cadena_sql.=" hor_hora HORA";
                      $cadena_sql.=" FROM  achorarios";
                      $cadena_sql.=" WHERE ".$variable;
                      $cadena_sql.=" order by hor_dia_nro, hor_hora";
                      break;

                 case 'consultaPreinscripcionesEstudiante':

                    $cadena_sql = "SELECT insde_est_cod COD_ESTUDIANTE,";
                    $cadena_sql .= " insde_asi_cod ASI_CODIGO,";
                    $cadena_sql .= " asi_nombre NOMBRE,";
                    $cadena_sql .= " insde_cra_cod CARRERA,";
                    $cadena_sql .= " insde_cred CREDITOS,";
                    $cadena_sql .= " insde_htd HTD,";
                    $cadena_sql .= " insde_htc HTC,";
                    $cadena_sql .= " insde_hta HTA,";
                    $cadena_sql .= " insde_cea_cod CLASIFICACION,";
                    $cadena_sql .= " insde_perdido PERDIDO,";
                    $cadena_sql .= " insde_estado ESTADO,";
                    $cadena_sql .= " insde_equivalente EQUIVALENTE";
                    $cadena_sql .= " FROM acinsdemanda";
                    $cadena_sql .= " INNER JOIN acasi ON asi_cod=insde_asi_cod";
                    $cadena_sql .= " where insde_est_cod=".$variable['codEstudiante'];
                    $cadena_sql .= " AND insde_ano=".$variable['ano'];
                    $cadena_sql .= " AND insde_per=".$variable['periodo'];
                    $cadena_sql .= " AND insde_estado LIKE '%A%'";
                    $cadena_sql .= " AND insde_cra_cod=".$variable['codProyectoEstudiante'];
                    $cadena_sql .= " ORDER BY insde_asi_cod";
                    break;


                }
                return $cadena_sql;
        }
}
?>
