<?php
/*ini_set("display_errors", "1");

$resultado = consultaPersona('1','1022348774','');

echo "<pre>";
	print_r($resultado); 
echo "</pre>";
exit;*/
function conectar()
{

        $usuario = "rhumanos";
        $clave = "hum4n0sKyr0n=DOC";
        $servidor = "10.20.0.22";
        $puerto = "5432";
        $db = "recursosHumanos";
	
	$enlace =  pg_connect("host=".$servidor." port=".$puerto." dbname=".$db." user=".$usuario." password=".$clave."");
	
	return $enlace;
}

    function consultarPersona($tipo_docu, $nume_docu, $codi_inte)
    {   
        
        include_once("sql.class.php");
        
        $sql=new sql_demanda();
                
        $conexion = conectar();
        
        if($tipo_docu != '' && $nume_docu != '')
            {
                $arreglo = array($tipo_docu, $nume_docu, $codi_inte);
                
                $cadena_sql = $sql->cadena_sql("consultarPersona",$arreglo);
                $resultadoPersona=ejecutarSQL($conexion, $cadena_sql,"busqueda");

                if(is_array($resultadoPersona))
                    {
                        $retorno['error'] = 0;
                        $retorno['descripcionError'] = 'OK';
                        $retorno['id_persona'] = $resultadoPersona[0][0];
                        $retorno['codigo_interno'] = $resultadoPersona[0][1];
                        $retorno['tipo_identificacion'] = $resultadoPersona[0][2];
                        $retorno['nume_identificacion'] = $resultadoPersona[0][3];
                        $retorno['primer_nombre'] = $resultadoPersona[0][4];
                        $retorno['segundo_nombre'] = $resultadoPersona[0][5];
                        $retorno['primer_apellido'] = $resultadoPersona[0][6];
                        $retorno['segundo_apellido'] = $resultadoPersona[0][7];
                        $retorno['fecha_nacimiento'] = $resultadoPersona[0][8];
                        $retorno['lugar_nacimiento'] = $resultadoPersona[0][9];
                        $retorno['sexo'] = $resultadoPersona[0][10];
                        $retorno['estado_civil'] = $resultadoPersona[0][11];
                        $retorno['direccion'] = $resultadoPersona[0][12];
                        $retorno['ciudad'] = $resultadoPersona[0][13];
                        $retorno['telefono'] = $resultadoPersona[0][14];
                        $retorno['celular'] = $resultadoPersona[0][15];
                        $retorno['correo'] = $resultadoPersona[0][16];
                        $retorno['estado'] = $resultadoPersona[0][17];
                    }else
                        {
                            $retorno['error'] = 1;
                            $retorno['descripcionError'] = 'No se encuentra el funcionario registrado';
                            $retorno['id_persona'] = "";
                            $retorno['codigo_interno'] = "";
                            $retorno['tipo_identificacion'] = "";
                            $retorno['nume_identificacion'] = "";
                            $retorno['primer_nombre'] = "";
                            $retorno['segundo_nombre'] = "";
                            $retorno['primer_apellido'] = "";
                            $retorno['segundo_apellido'] = "";
                            $retorno['fecha_nacimiento'] = "";
                            $retorno['lugar_nacimiento'] = "";
                            $retorno['sexo'] = "";
                            $retorno['estado_civil'] = "";
                            $retorno['direccion'] = "";
                            $retorno['ciudad'] = "";
                            $retorno['telefono'] = "";
                            $retorno['celular'] = "";
                            $retorno['correo'] = "";
                            $retorno['estado'] = "";
                        }
            }else
                {
                    $retorno['error'] = 1;
                    $retorno['descripcionError'] = 'Para realizar la consulta, se debe diligenciar los campos obligatorios.';
                    $retorno['id_persona'] = "";
                    $retorno['codigo_interno'] = "";
                    $retorno['tipo_identificacion'] = "";
                    $retorno['nume_identificacion'] = "";
                    $retorno['primer_nombre'] = "";
                    $retorno['segundo_nombre'] = "";
                    $retorno['primer_apellido'] = "";
                    $retorno['segundo_apellido'] = "";
                    $retorno['fecha_nacimiento'] = "";
                    $retorno['lugar_nacimiento'] = "";
                    $retorno['sexo'] = "";
                    $retorno['estado_civil'] = "";
                    $retorno['direccion'] = "";
                    $retorno['ciudad'] = "";
                    $retorno['telefono'] = "";
                    $retorno['celular'] = "";
                    $retorno['correo'] = "";
                    $retorno['estado'] = "";
                }
        
        
                           
        return $retorno;
    }
	
	        
      
/**
 * FUNCIONES PARA EJECUTAR EL SQL
 */

function ejecutarSQL($conexion,$cadena_sql,$tipo)
{
if($tipo=="busqueda")
        {

        $busqueda=pg_query($conexion,$cadena_sql);
        //var_dump($busqueda);exit;
        if($busqueda)
        {
                unset($registro);
                @$campo = pg_num_fields($busqueda);
                @$conteo = pg_affected_rows($busqueda);

                $j=0;
                while($salida = pg_fetch_row($busqueda))
                {
                        $conteo = $conteo + 1;
                                if(is_array($salida))
                                {
                                        if($j==0)
                                        {
                                                $keys=array_keys($salida);
                                                $i=0;
                                                foreach($keys as $clave=>$valor){
                                                                $claves[$i]=$valor;
                                                                $i++;
                                                                //echo $this->clave[$i++]."->".$valor."<br>";
                                        }
                                }

                                        for($un_campo=0; $un_campo<$campo; $un_campo++)
                                        {
                                                        //Si se desea dejar el resultado con el numero del campo

                                                        $registro[$j][$un_campo] = $salida[$claves[$un_campo]];

                                                        //Si se desea dejar con el nombre del campo
                                                        $registro[$j][$claves[$un_campo]] = $salida[$claves[$un_campo]];
                                        }
                                }
                                //$salida = ifx_fetch_row($busqueda);
                                $j++;
                        }	
                        //echo "Consulta=>".$cadena_sql."Total=>".$j."<br>";

                        if($j>0)
                            {
                                @pg_free_result($busqueda);
                                return $registro;
                            }else
                                {
                                    return FALSE;
                                }
                        		

                }		
        }else if($tipo=="accion")
        {
                $execute = pg_query($cadena_sql,$conexion);

                if(!$execute) 
                {			
                        
                }else 
                        {
                                $rowcount = pg_affected_rows ($execute);

                                if($rowcount>0)
                                {
                                        $registro = array(TRUE,$rowcount);
                                        return $registro;
                                }else
                                {
                                        $registro = array(FALSE,$rowcount);
                                        return $registro;
                                }
                        }
        }

}
?>
