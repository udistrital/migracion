<?php
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.2.0    Maritza Callejas    12/08/2013
---------------------------------------------------------------------------------------------------------------------------*/

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/funcionGeneral.class.php");

class crearDocumento  extends funcionGeneral{

    public function __construct($configuracion)
	{
            require_once("clase/config.class.php");
            $esta_configuracion=new config();
            $configuracion=$esta_configuracion->variable();
            $this->configuracion=$configuracion;

            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
            require_once($configuracion["raiz_documento"].$configuracion["clases"]."/mpdf/mpdf.php");

            $this->cripto=new encriptar();
            $this->funcionGeneral=new funcionGeneral();
            $this->sesion=new sesiones($configuracion);

            
            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion ORACLE
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

            //Conexion SGA
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Datos de sesion

            $this->usuario=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

            $this->identificacion=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
            $this->nivel=$this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
                

               
	}
       
        
        /**
        * Funcion que crea el certificado
        * @param int $tipo
        * @param <array> $parametro_sql
        * Utiliza los metodos consultarSeccionesDocumento, consultarParametrosDocumento, ejecutarSqlParametros, verificarValoresConjuntos,
        * reemplazarParametrosEnSecciones, generarPDF
        */
       function crearDocumento($tipo,$parametro_sql,$cod_archivo){
           $secciones = $this->consultarSeccionesDocumento($tipo);
           if(is_array($secciones)){
                $parametros = $this->consultarParametrosDocumento($tipo);
                if (is_array($parametros)){
                    $parametros = $this->ejecutarSqlParametros($parametros, $parametro_sql);
                    //revisamos si los parametros devuelven mas de una columna
                    $parametros = $this->verificarValoresConjuntos($parametros);
                    $secciones = $this->reemplazarParametrosEnSecciones($parametros,$secciones);
                    //consultamos el tipo de documento para tomar la ubicacion y mnombre de los pdf.
                    $tipo_documento = $this->consultarDocumento($tipo);
                    $ubicacion = $this->configuracion["raiz_documento"].$tipo_documento[0]['ubicacion'];
                    $nombre_pdf = $tipo_documento[0]['nombre_pdf'].$cod_archivo.".pdf";
            
                    $documento_html = $this->generarHtml($secciones);
                    $this->generarPDF($documento_html,"", "",$nombre_pdf);
                }else{
                    echo "El documento no tiene parametros relacionados";
                }
                                        
           }else{
               echo "Error al consultar formato del documento";
           }
          
       }
       
       /**
        * Funcion que consulta las secciones que tiene un documento
        * @param int $tipo
        * @return <array> 
        */
       function consultarSeccionesDocumento($tipo){
            $cadena_sql=$this->cadena_sql("secciones_documento",$tipo);
            echo $cadena_sql;//exit;
            return $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
		    
       }
       
       /**
        * Funcion que consulta los parametros de un documento
        * @param int $tipo
        * @return <array> 
        */
       function consultarParametrosDocumento($tipo){
            $cadena_sql=$this->cadena_sql("parametros_documento",$tipo);
            return $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
           
       }
       
       /**
        * Función que ejecuta los sql de los parametros para obtener los valores correspondientes
        * @param <array> $parametros
        * @param <array> $parametro_sql
        * @return <array> 
        */
       function ejecutarSqlParametros($parametros,$parametro_sql){
           $parametros= $this->reemplazarParametrosEnSql($parametros,$parametro_sql);
           
           foreach ($parametros as $key => $parametro) {
               $cadena_sql = $parametro['sentencia_sql'];

               if($cadena_sql ){
                    $this->acceso=$this->conectarDB($this->configuracion,$parametro['dbms']);
                    $resultado=$this->ejecutarSQL($this->configuracion, $this->acceso, $cadena_sql, "busqueda");
                    if (is_array($resultado)){
                        
                        $indices = array_keys($resultado[0]);
                            if(count($indices)>2){
                                $parametros[$key]['valor']= $resultado[0];
                            }else{
                            
                                $parametros[$key]['valor']= $resultado[0][0];
                            }
                            
                    }
                }
         
            }
           return $parametros;
       }
       
       /**
        * Funcion que reemplaza los valores de los parametros de SQL en el SQL
        * @param <array> $parametros
        * @param <array> $parametro_sql
        * @return <array> 
        */
       function reemplazarParametrosEnSql($parametros,$parametro_sql){
           foreach ($parametros as $key => $parametro) {
               $cadena = $parametro['sentencia_sql'];
               if($cadena){
                    foreach ($parametro_sql as $key2 => $value) {
                        if($key2 && $value){
                                $nombre_parametro = "P[".$key2."]";
                                $valor_parametro = "'".$value."'";
                                $parametros[$key]['sentencia_sql']=$this->reemplazarParametroEnCadena($cadena,$nombre_parametro,$valor_parametro);
                                $cadena = $parametros[$key]['sentencia_sql'];
                                
                        }
                    }
               }
           }
           return $parametros;
       }
       
       /**
        * Funcion para reemplazar los valores de los parametros en una cadena
        * @param String $cadena
        * @param String $nombre
        * @param String $valor
        * @return String
        */
       function reemplazarParametroEnCadena($cadena,$nombre,$valor){
           $cadena=str_replace($nombre, $valor, $cadena);
           return $cadena;
           
       }
       
       /**
        * Funcion que verifica si un resultado de un parametro es un conjunto de valores que se encuentra en un arreglo, y si corresponde a un arreglo,
        * toma cada valor de cada parametro 
        * @param <array> $parametros
        * @return <array> 
        */
       function verificarValoresConjuntos($parametros){
           foreach ($parametros as $key => $parametro) {
               $parametro['valor']=(isset($parametro['valor'])?$parametro['valor']:'');
                if(is_array($parametro['valor'])){
                    $arreglo_valores=$parametro['valor'];
                    foreach ($arreglo_valores as $key2 => $value) {
                            if(!is_numeric($key2)){
                                $nombre_parametro = strtoupper($key2);
                                $valor_parametro = $value;
                                foreach ($parametros as $key3 => $value3) {
                                    if($nombre_parametro==$value3['nombre']){
                        
                                        $parametros[$key3]['valor']= $valor_parametro; 
                                    }
                                   
                                }
                            }
                                
                     }
                 
                 }
           }
           return $parametros;
       }

       /**
        * Funcion qie reemplaza los valores de los parametros en las secciones del documento
        * @param <array> $parametros
        * @param <array> $secciones
        * @return <array> 
        */
       function reemplazarParametrosEnSecciones($parametros,$secciones){
          // var_dump($parametros);
           if($parametros){
                foreach ($parametros as $key => $parametro) {
                    $nombre_parametro = "P['".$parametro['nombre']."']";
                    $pos = strpos($nombre_parametro, 'valor');
                    if ($pos>0) {
                            $valor_parametro = "$".number_format($parametro['valor'],2);

                    }else{ 
                            $valor_parametro = (isset($parametro['valor'])?$parametro['valor']:'');
                    }
                    if($parametro['nombre'] && $valor_parametro){
                            foreach ($secciones as $key2 => $value) {
                                    $cadena=$value['contenido'];
                                    $secciones[$key2]['contenido']=$this->reemplazarParametroEnCadena($cadena,$nombre_parametro,$valor_parametro);
                            }
                    }
                }
           }
           return $secciones;
       }
       
     
    
       /**
        * Funcion que genera un archivo pdf con el documento
        * @param String $documento
        * Utiliza la clase fpdf
        */
       
       function generarPDF($doc_html,$encabezado, $pie_pagina,$nombre_archivo){
            $this->mpdf=new mPDF('','LETTER',11,'ARIAL',20,20,30,20,7,10);
            $this->mpdf->AddPage();
//            $ruta_estilo = $this->configuracion["raiz_documento"].$this->configuracion["bloques"]."/admin_reporteSabanaDeNotas/clase/estilos_pdf.css";
//            //establecemos el archivo de estilos
//            $stylesheet =file_get_contents($ruta_estilo);                    
//            $this->mpdf->WriteHTML($stylesheet,1);
            //colocamos el html para el encabezado de pagina
            $this->mpdf->SetHTMLHeader($encabezado,'O',true);
            //colocamos el html para el pie de pagina
            $this->mpdf->setHTMLFooter($pie_pagina) ;
            //colocamos el html para el documento
            $this->mpdf->WriteHTML($doc_html); 
            //establecemos el nombre del archivo
            $this->mpdf->Output($nombre_archivo.'.pdf','D');
            
        }
        
       
      /**
        * Funcion que consulta los datos de un documento
        * @param int $codigo
        * @return <array> 
        */
       function consultarDocumento($codigo){
            $cadena_sql=$this->cadena_sql("documento",$codigo);
            return $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
		    
       }

        /**
        * Funcion que genera un archivo html con el documento
        * @param String $documento
        * Utiliza la clase fpdf
        */
       function generarHtml($secciones){
           
           $documento='';
           if(is_array($secciones)){
                $documento='<table>';
                foreach ($secciones as $key => $seccion) {
                    $documento .="<tr><td>".$seccion['contenido']."</td></tr>";
                }
                $documento.='</table>';
           
           }
           return $documento;
       }
       
       /**
        * Sentencias SQl consulta tabla pro_sga de la bd MySQl
        */
       
       function cadena_sql( $opcion,$variable="")
	{
		
		switch($opcion)
		{
						
			case "secciones_documento":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" sec_id           AS id_seccion,";
                                $cadena_sql.=" sec_nombre       AS seccion,";
                                $cadena_sql.=" dos_posicion     AS posicion,";
                                $cadena_sql.=" dos_contenido    AS contenido,";
                                $cadena_sql.=" dos_alineacion   AS alineacion";
                                $cadena_sql.=" FROM sga_cer_documento_seccion ";
                                $cadena_sql.=" INNER JOIN sga_cer_seccion ON dos_id_seccion=sec_id";
                                $cadena_sql.=" WHERE dos_id_documento=".$variable;
                                $cadena_sql.=" AND dos_estado='A'";
                                $cadena_sql.=" ORDER BY POSICION";
                                break;
						
			case "parametros_documento":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" pad_id_parametro AS id_parametro,";
                                $cadena_sql.=" par_nombre       AS nombre, ";
                                $cadena_sql.=" par_sql          AS sentencia_sql, ";
                                $cadena_sql.=" par_dbms         AS dbms";
                                $cadena_sql.=" FROM sga_cer_documento_parametro ";
                                $cadena_sql.=" INNER JOIN sga_cer_parametro ON pad_id_parametro=par_id";
                                $cadena_sql.=" WHERE pad_id_documento=".$variable;
                                $cadena_sql.=" AND pad_estado='A'";
                                $cadena_sql.=" AND par_estado='A'";
                                
                                break;
                        
                        case "documento":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" docu_nombre          AS nombre,";
                                $cadena_sql.=" docu_descripcion     AS descripcion, ";
                                $cadena_sql.=" docu_ubicacion_pdf   AS ubicacion, ";
                                $cadena_sql.=" docu_nombre_pdf      AS nombre_pdf ";
                                $cadena_sql.=" FROM sga_cer_documento ";
                                $cadena_sql.=" WHERE docu_id=".$variable;
                                $cadena_sql.=" AND docu_estado='A'";
                                break;
                        
                            default:
				$cadena_sql="";
				break;
		}
		return $cadena_sql;
	}
	

}
?>
