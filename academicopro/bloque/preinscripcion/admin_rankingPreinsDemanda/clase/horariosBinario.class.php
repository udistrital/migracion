<?php
/* 
 * Funcion que tiene todas las validaciones para el proceso de inscripciones
 * 
 */

/**
 * Permite convertir las hora de un horario en binario.
 *
 * @author Monica Monroy
 * Fecha 12 de enero de 2013
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

class horariosBinario {
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
     * Función que permite consultar los horarios.
     * 
     * @param array $datosGrupo
     * @return <boolean>
     */
    public function ConsultarHorarios()
        {   
            //consulta los horarios creados
            $facultades=  $this->facultades();
            $mensaje=0;
            $totalHorarios=0;
            foreach ($facultades as $key => $facultad) {

                $resultado=$this->horarios($facultad[0]);
                $horario='';
                $total=count($resultado);
                $a=2;
                
                //crea el horario binario para cada registro de curso
                foreach($resultado as $key=>$horarios){
                    //muestra el avance de creación del arreglo de horarios
                    $porcentaje = $a * 100 / $total; //saco mi valor en porcentaje
                    echo "<script>callprogressHora(".round($porcentaje).",".$a.",".$total.",".$facultad[0].")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
                    flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle
                    ob_flush();
                    $a++;
                    
                    $horas=24;
                    $hora='';
                      //Convierte las horas del horario registrado en una cadena de 24 bits, colocando 1 en la hora respectiva y 0 en los demas
                    for ($i = 1; $i <= $horas; $i++) {
                        if($i==$horarios['HORA_HOR']){
                                $hora.=1;
                            }
                            elseif($i!=$horas+1){$hora.=0;}
                        }
                        //concatena todas las horas del dia en un solo registro utilizando una operacion OR binario. La clave de cada registro se forma con el id del grupo, proyecto, facultad, alternativa y dia
                    if(isset($horario[$horarios['CURSO']."|".$horarios['PROYECTO']."|".$horarios['FACULTAD']."|".$horarios['ALTERNATIVA']][$horarios['DIA_HOR']])){
                             //suma una nueva cadena de bits con otra anterior, si la hay
                             $horario[$horarios['CURSO']."|".$horarios['PROYECTO']."|".$horarios['FACULTAD']."|".$horarios['ALTERNATIVA']][$horarios['DIA_HOR']]=$hora|$horario[$horarios['CURSO']."|".$horarios['PROYECTO']."|".$horarios['FACULTAD']."|".$horarios['ALTERNATIVA']][$horarios['DIA_HOR']]; 

                     }else{
                             //ingresa la primer secuencia de bits en el horario binario del curso
                             $horario[$horarios['CURSO']."|".$horarios['PROYECTO']."|".$horarios['FACULTAD']."|".$horarios['ALTERNATIVA']][$horarios['DIA_HOR']]=$hora; 
                          }
                          //$arregloHorarios[]= $horarios['FACULTAD'];
                }
               //crea los arreglos de horario correspondientes para cada curso
                $arreglo=$this->crearArreglo($horario);
               //registra el horario del curso en binario.
                $registrados=$this->registrarArreglo($arreglo);
                if($registrados>0){
                    $mensaje+=$registrados;
                }else{
                    
                }
                echo "<br>Registrados $registrados Horarios<br>";
            }
            sleep(2);
       return $mensaje;
       
      }

    /**
     * Funcion que permite consultar los horarios creados 
     * @return type
     */
      function horarios($facultad) {
            $variables=array('ano'=>  $this->ano,
                               'periodo'=> $this->periodo,
                               'facultad'=>$facultad
                              );     
            $cadena_sql=$this->cadena_sql("ConsultarHorarios",$variables);
            $resultado_horario=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            return $resultado_horario;
            
    }
        
    /**
     * Funcion que permite consultar los horarios creados 
     * @return type
     */
      function facultades() {
            $cadena_sql=$this->cadena_sql("ConsultarFacultades","");
            $resultado_horario=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            return $resultado_horario;
            
    }
        
  function crearArreglo($horario){    
 
      $f=0;
     foreach($horario as $key=>$value){
          $resul=(explode('|', $key));
       foreach($value as $key1=>$hora){
          $horBinario['dia'.$key1]=$hora;         
        }
        
       $sede_diferente=$this->sedeDiferente($resul);      
		
	  if(count($sede_diferente)>1)
                    {$sede_dif=1;
                    
                    }else{$sede_dif=0;}
		
          $arreglo[$f]=array('curso'=>$resul[0],
                        'carrera'=>$resul[1],
                        'facultad'=>$resul[2],
                        'sedeDiferente'=>$sede_dif,
                        'alternativa'=>$resul[3]
                        ); 
          $arreglo[$f]=array_merge($arreglo[$f], $horBinario);
          $f++;
      unset($horBinario);
      }
         
  return $arreglo;
  }
  
  function registrarArreglo($arreglo){
      $total=0;
	for ($i = 0; $i < count($arreglo); $i++) {	
            $afectado=0;
            $cadena_sql_adicionar=$this->cadena_sql("insertarRegistroHorarios",$arreglo[$i]);
            $resultado_adicionar=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_adicionar,""); 
            $afectado = $this->funcionGeneral->totalAfectados($this->configuracion, $this->accesoGestion);
            if($afectado == 1){
                $total++;
            }
        }
        return $total;
    }
     

        
    function sedeDiferente($resul){
        $variables=array('curso'=>$resul[0]
                         ); 
        
	$cadena_sql=$this->cadena_sql("Buscar_sede",$variables);
        $sede_diferente=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $sede_diferente;
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

                  case "ConsultarHorarios":

                      $cadena_sql = "SELECT";
		      $cadena_sql.=" hor_id_curso CURSO,";
		      $cadena_sql.=" hor_id ID,";
                      $cadena_sql.=" cur_cra_cod PROYECTO,";
                      $cadena_sql.=" cur_dep_cod FACULTAD,";
                      $cadena_sql.=" hor_dia_nro DIA_HOR,";  
                      $cadena_sql.=" hor_hora HORA_HOR,";
                      $cadena_sql.=" hor_alternativa ALTERNATIVA";
                      $cadena_sql.=" FROM achorarios";
                      $cadena_sql.=" INNER JOIN accursos ON hor_id_curso=cur_id";
                      $cadena_sql.=" WHERE cur_ape_ano=".$variable['ano'];
                      $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                      $cadena_sql.=" AND cur_cra_cod in (select cra_cod from accra where cra_dep_cod =".$variable['facultad']." and cra_estado='A')";
                      $cadena_sql.=" AND hor_estado LIKE '%A%'";
                      $cadena_sql.=" AND cur_estado LIKE '%A%'";
                      $cadena_sql.=" ORDER BY hor_id_curso,hor_dia_nro";
                      break; 
                  
                  case "ConsultarFacultades":

                    $cadena_sql=" select distinct cra_dep_cod";
                    $cadena_sql.=" from accra";
                    $cadena_sql.=" where cra_dep_cod not in (0,20,100,500)";
                    $cadena_sql.=" and cra_estado='A'";
                      break; 
                  
                  case 'insertarRegistroHorarios':
                      
                    $cadena_sql="INSERT INTO sga_horarios_binarios ";
                    $cadena_sql.="(hor_id_curso,hor_carrera,hor_facultad,hor_sede_dif,hor_alternativa,hor_lunes,hor_martes,hor_miercoles,hor_jueves,hor_viernes,hor_sabado,hor_domingo) ";
                    $cadena_sql.="VALUES ('".$variable['curso']."',";
                    $cadena_sql.="'".$variable['carrera']."',";
                    $cadena_sql.="'".$variable['facultad']."',";
                    $cadena_sql.="'".$variable['sedeDiferente']."',";
                    $cadena_sql.="'".$variable['alternativa']."',";
                    $cadena_sql.="'".(isset($variable['dia1'])?$variable['dia1']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia2'])?$variable['dia2']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia3'])?$variable['dia3']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia4'])?$variable['dia4']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia5'])?$variable['dia5']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia6'])?$variable['dia6']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia7'])?$variable['dia7']:'0')."')";
		    break;
				
                    case 'Buscar_sede':
                    $cadena_sql=" SELECT DISTINCT sal_facultad";
                    $cadena_sql.=" FROM gesalones";
                    $cadena_sql.=" INNER JOIN achorarios ON sal_id_espacio=hor_sal_id_espacio";
                    $cadena_sql.=" INNER JOIN accursos ON cur_id=hor_id_curso";
                    $cadena_sql.=" WHERE hor_id_curso=".$variable['curso'];
                    $cadena_sql.=" AND sal_facultad<>0";
                    break;

                }
                return $cadena_sql;
        }
}
?>
