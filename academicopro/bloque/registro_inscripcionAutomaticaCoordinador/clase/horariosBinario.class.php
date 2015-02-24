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
            $resultado=$this->horarios();            
            $horario='';
           
          foreach($resultado as $key=>$horarios){ 
             
              $horas=24;
              $hora='';
                for ($i = 1; $i <= $horas; $i++) {
                    if($i==$horarios['HORA_HOR']){
                     $hora.=1;
                    }
                    elseif($i!=$horas+1){$hora.=0;}
                }
                   if(isset($horario["'".$horarios['ESPACIO']."'"][$horarios['DIA_HOR']])){
                            $horario[$horarios['ESPACIO']][$horarios['DIA_HOR']]=$hora|$horario[$horarios['ESPACIO']][$horarios['DIA_HOR']]; 
                          
                    }else{
                            $horario[$horarios['ESPACIO']][$horarios['DIA_HOR']]=$hora; 
                         }
						
	          $arregloHorarios[]= $horarios['SEDE'];
		    	
          } 
               
       $arreglo=$this->crearArreglo($horario, $arregloHorarios); 
       $registrados=$this->registrarArreglo($arreglo);
       if($registrados>0){
            $mensaje = "<br>Registro Exitoso de horarios Binarios!!";
       }else{
            $mensaje = "<br>No se registraron horarios Binarios!!";
       }
       return $mensaje;
       
      }

 
    function horarios() {
            $variables=array('ano'=>  $this->ano,
                               'periodo'=> $this->periodo               
                              );     
            $cadena_sql=$this->cadena_sql("ConsultarHorarios",$variables);
            $resultado_horario=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            return $resultado_horario;
            
    }
        
  function crearArreglo($horario,$sede){    
 
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
		
          $arreglo[$f]=array('ano'=>$resul[0],
                        'periodo'=>$resul[1],
                        'asignatura'=>$resul[2],
                        'grupo'=>$resul[4],
                        'sede'=>$sede[$f],
                        'sedeDiferente'=>$sede_dif,
			'carrera'=>$resul[5],
			'facultad'=>$resul[6]
                        ); 
          $arreglo[$f]=  array_merge($arreglo[$f], $horBinario);
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
        $variables=array('ano'=>$resul[0],
                        'periodo'=>$resul[1],
                        'asignatura'=>$resul[2],
                        'grupo'=>$resul[4]); 
        
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
                      $cadena_sql.=" hor_ape_ano||'|'||";
                      $cadena_sql.=" hor_ape_per||'|'||";
                      $cadena_sql.=" hor_asi_cod||'|'||";
                      $cadena_sql.=" hor_estado||'|'||";
		      $cadena_sql.=" hor_nro||'|'||";
                      $cadena_sql.=" cur_cra_cod||'|'||";
                      $cadena_sql.=" cur_dep_cod ESPACIO,";
                      $cadena_sql.=" hor_dia_nro DIA_HOR,";  
                      $cadena_sql.=" hor_hora HORA_HOR,";
                      $cadena_sql.=" hor_sal_id_Espacio SALON,";
                      $cadena_sql.=" hor_sed_cod SEDE";
                      $cadena_sql.=" FROM achorario_2012";
                      $cadena_sql.=" INNER JOIN accurso ON cur_asi_cod=hor_asi_cod and cur_nro=hor_nro and hor_ape_ano=cur_ape_ano and hor_ape_per=cur_ape_per";
                      $cadena_sql.=" WHERE hor_ape_ano=" . $variable['ano'];
                      $cadena_sql.=" AND hor_ape_per=" . $variable['periodo'];
                      $cadena_sql.=" AND hor_estado LIKE '%A%'";
                      $cadena_sql.=" AND cur_estado LIKE '%A%'";
                      $cadena_sql.=" ORDER BY hor_ape_ano";  
                      break; 
                  
                  case 'insertarRegistroHorarios':
                      
                    $cadena_sql="INSERT INTO sga_horario_binario ";
                    $cadena_sql.="(hor_ano,hor_per,hor_grupo,hor_asignatura,hor_sede,hor_sede_dif,hor_carrera,hor_facultad,hor_lunes,hor_martes,hor_miercoles,hor_jueves,hor_viernes,hor_sabado,hor_domingo) ";
                    $cadena_sql.="VALUES ('".$variable['ano']."',";
                    $cadena_sql.="'".$variable['periodo']."',";
                    $cadena_sql.="'".$variable['grupo']."',";
                    $cadena_sql.="'".$variable['asignatura']."',";
                    $cadena_sql.="'".$variable['sede']."',";
                    $cadena_sql.="'".$variable['sedeDiferente']."',";
		    $cadena_sql.="'".$variable['carrera']."',";
		    $cadena_sql.="'".$variable['facultad']."',";
                    $cadena_sql.="'".(isset($variable['dia1'])?$variable['dia1']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia2'])?$variable['dia2']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia3'])?$variable['dia3']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia4'])?$variable['dia4']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia5'])?$variable['dia5']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia6'])?$variable['dia6']:'0')."',";
                    $cadena_sql.="'".(isset($variable['dia7'])?$variable['dia7']:'0')."')";
		    break;
				
                    case 'Buscar_sede':
                    $cadena_sql="select DISTINCT HOR_SED_COD";
                    $cadena_sql.=" FROM achorario_2012";
                    $cadena_sql.=" where hor_asi_cod=".$variable['asignatura']; 
                    $cadena_sql.=" AND HOR_APE_ANO=".$variable['ano']; 
                    $cadena_sql.=" AND hor_ape_per=".$variable['periodo'];
                    $cadena_sql.=" AND hor_nro=".$variable['grupo']."";
                    break;

                }
                return $cadena_sql;
        }
}
?>
