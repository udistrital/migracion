<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminConsultarProveedor extends sql
{
   
	function cadena_sql($configuracion,$opcion,$variable="")
	{
		switch($opcion)
		{
			
			case "actividad":
				$cadena_sql="SELECT ";
				$cadena_sql.="actid, ";
				$cadena_sql.="actdescripcion ";
				$cadena_sql.="FROM ";
				$cadena_sql.="practividad ";
			break;

			case "especialidad":
				$cadena_sql="SELECT ";
				$cadena_sql.="espid, ";
				$cadena_sql.="espdescripcion ";
				$cadena_sql.="FROM ";
				$cadena_sql.="prespecialidad ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="actid=".$variable;
			break;

			case "listaEspec":
				$cadena_sql="SELECT ";
				$cadena_sql.="espid, ";
				$cadena_sql.="espdescripcion, ";
				$cadena_sql.="actid ";
				$cadena_sql.="FROM ";
				$cadena_sql.="prespecialidad ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="actid=".$variable;
			break;

			case "tipoDocumento":
				$cadena_sql="SELECT ";
				$cadena_sql.="tdo_codigo, ";
				$cadena_sql.="tdo_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="getipdocu ";
			break;

                        case "municipio":
				$cadena_sql="SELECT ";
				$cadena_sql.="mun_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gemunicipio ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="mun_cod = ".$variable;
                        break;

                        case "pais":
				$cadena_sql="SELECT ";
				$cadena_sql.="gepaisnombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gepais ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="gepaiscod=".$variable;
                        break;
                    
			case "proveedor":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="PRIDENTIFICACION, "; //0
                                $cadena_sql.="PRDIGITOVERIFICACION, ";//1
                                $cadena_sql.="PRRAZONSOCIAL, ";//2
                                $cadena_sql.="PRDIRECCION, ";//3
                                $cadena_sql.="PRTELEFONO1, ";//4
                                $cadena_sql.="PRTELEFONO2, ";//5
                                $cadena_sql.="PRMOVIL, ";//6
                                $cadena_sql.="PRFAX, ";//7
                                $cadena_sql.="PRMAIL, ";//8
                                $cadena_sql.="PRURL, ";//9
                                $cadena_sql.="PRTDREPRESENTANTE, ";//10
                                $cadena_sql.="PRIDENREPRESENTANTE, ";//11
                                $cadena_sql.="PRAPE1REPRESENTA, ";//12
                                $cadena_sql.="PRAPE2REPRESENTA, ";//13
                                $cadena_sql.="PRNOM1REPRESENTA, ";//14
                                $cadena_sql.="PRNOM2REPRESENTA, ";//15
                                $cadena_sql.="PRTIPOPERSONA, ";//16
                                $cadena_sql.="PRREGIMEN, ";//17
                                $cadena_sql.="PRREGISTROUNICO, ";//18
                                $cadena_sql.="PRPYME, ";//19
                                $cadena_sql.="PRFECHAINS, ";//20
                                $cadena_sql.="PRFECHAACTUALIZA, ";//21
                                $cadena_sql.="PRCODDEPTO, ";//22
                                $cadena_sql.="PRCODMUN, ";//23
                                $cadena_sql.="PRCODPAIS, ";//24
                                $cadena_sql.="PRNOMASESOR, ";//25
                                $cadena_sql.="PRTELASESOR, ";//26
                                $cadena_sql.="PRRUP, ";//27
                                $cadena_sql.="PRK, ";//28
                                $cadena_sql.="PRPRODIMPORTA, ";//29
                                $cadena_sql.="PRESTADO, ";//30
                                $cadena_sql.="PRDESCRIPPRODUCTO, ";//31
                                $cadena_sql.="PRREGISTROMERCANTIL ";//32
                                $cadena_sql.="FROM ";
                                $cadena_sql.="PRPROVEEDOR ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="PRIDENTIFICACION=".$variable[0]." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="PRDIGITOVERIFICACION=".$variable[1];
                                
                                break;

                            case "proveedorRazonSocial":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="PRIDENTIFICACION, "; //0
                                $cadena_sql.="PRDIGITOVERIFICACION, ";//1
                                $cadena_sql.="PRRAZONSOCIAL, ";//2
                                $cadena_sql.="PRDIRECCION, ";//3
                                $cadena_sql.="PRTELEFONO1, ";//4
                                $cadena_sql.="PRTELEFONO2, ";//5
                                $cadena_sql.="PRMOVIL, ";//6
                                $cadena_sql.="PRFAX, ";//7
                                $cadena_sql.="PRMAIL, ";//8
                                $cadena_sql.="PRURL, ";//9
                                $cadena_sql.="PRTDREPRESENTANTE, ";//10
                                $cadena_sql.="PRIDENREPRESENTANTE, ";//11
                                $cadena_sql.="PRAPE1REPRESENTA, ";//12
                                $cadena_sql.="PRAPE2REPRESENTA, ";//13
                                $cadena_sql.="PRNOM1REPRESENTA, ";//14
                                $cadena_sql.="PRNOM2REPRESENTA, ";//15
                                $cadena_sql.="PRTIPOPERSONA, ";//16
                                $cadena_sql.="PRREGIMEN, ";//17
                                $cadena_sql.="PRREGISTROUNICO, ";//18
                                $cadena_sql.="PRPYME, ";//19
                                $cadena_sql.="PRFECHAINS, ";//20
                                $cadena_sql.="PRFECHAACTUALIZA, ";//21
                                $cadena_sql.="PRCODDEPTO, ";//22
                                $cadena_sql.="PRCODMUN, ";//23
                                $cadena_sql.="PRCODPAIS, ";//24
                                $cadena_sql.="PRNOMASESOR, ";//25
                                $cadena_sql.="PRTELASESOR, ";//26
                                $cadena_sql.="PRRUP, ";//27
                                $cadena_sql.="PRK, ";//28
                                $cadena_sql.="PRPRODIMPORTA, ";//29
                                $cadena_sql.="PRESTADO, ";//30
                                $cadena_sql.="PRDESCRIPPRODUCTO, ";//31
                                $cadena_sql.="PRREGISTROMERCANTIL ";//32
                                $cadena_sql.="FROM ";
                                $cadena_sql.="PRPROVEEDOR ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="PRRAZONSOCIAL LIKE '%".$variable."%' ";
                            break;

                            case "proveedorActividadComercial":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="PRO.PRIDENTIFICACION, "; //0
                                $cadena_sql.="PRO.PRDIGITOVERIFICACION, ";//1
                                $cadena_sql.="PRO.PRRAZONSOCIAL, ";//2
                                $cadena_sql.="PRO.PRDIRECCION, ";//3
                                $cadena_sql.="PRO.PRTELEFONO1, ";//4
                                $cadena_sql.="PRO.PRTELEFONO2, ";//5
                                $cadena_sql.="PRO.PRMOVIL, ";//6
                                $cadena_sql.="PRO.PRFAX, ";//7
                                $cadena_sql.="PRO.PRMAIL, ";//8
                                $cadena_sql.="PRO.PRURL, ";//9
                                $cadena_sql.="PRO.PRTDREPRESENTANTE, ";//10
                                $cadena_sql.="PRO.PRIDENREPRESENTANTE, ";//11
                                $cadena_sql.="PRO.PRAPE1REPRESENTA, ";//12
                                $cadena_sql.="PRO.PRAPE2REPRESENTA, ";//13
                                $cadena_sql.="PRO.PRNOM1REPRESENTA, ";//14
                                $cadena_sql.="PRO.PRNOM2REPRESENTA, ";//15
                                $cadena_sql.="PRO.PRTIPOPERSONA, ";//16
                                $cadena_sql.="PRO.PRREGIMEN, ";//17
                                $cadena_sql.="PRO.PRREGISTROUNICO, ";//18
                                $cadena_sql.="PRO.PRPYME, ";//19
                                $cadena_sql.="PRO.PRFECHAINS, ";//20
                                $cadena_sql.="PRO.PRFECHAACTUALIZA, ";//21
                                $cadena_sql.="PRO.PRCODDEPTO, ";//22
                                $cadena_sql.="PRO.PRCODMUN, ";//23
                                $cadena_sql.="PRO.PRCODPAIS, ";//24
                                $cadena_sql.="PRO.PRNOMASESOR, ";//25
                                $cadena_sql.="PRO.PRTELASESOR, ";//26
                                $cadena_sql.="PRO.PRRUP, ";//27
                                $cadena_sql.="PRO.PRK, ";//28
                                $cadena_sql.="PRO.PRPRODIMPORTA, ";//29
                                $cadena_sql.="PRO.PRESTADO, ";//30
                                $cadena_sql.="PRO.PRDESCRIPPRODUCTO, ";//31
                                $cadena_sql.="PRO.PRREGISTROMERCANTIL ";//32
                                $cadena_sql.="FROM ";
                                $cadena_sql.="PRPROVEEDOR PRO ";
                                $cadena_sql.="INNER JOIN PRESPECPROVEEDOR ESP ON PRO.PRIDENTIFICACION=ESP.PRIDENTIFICACION ";
                                $cadena_sql.="AND PRO.PRDIGITOVERIFICACION=ESP.PRDIGITOVERIFICACION ";
                                $cadena_sql.="AND ESPID = ".$variable[0]." ";
                                $cadena_sql.="AND ACTID= ".$variable[1];
                                //$cadena_sql.="WHERE ";
                                //$cadena_sql.="PRRAZONSOCIAL LIKE '%".$variable[0]."%' ";
                            break;


                            case "departamento":
				$cadena_sql="SELECT ";
				$cadena_sql.="dep_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gedepartamento ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="dep_cod=".$variable;
                             break;

                            case "consultaActividad":
				$cadena_sql="SELECT ";
				$cadena_sql.="PRACTIVIDAD.ACTID, ";
				$cadena_sql.="PRACTIVIDAD.ACTDESCRIPCION, ";
				$cadena_sql.="PRESPECIALIDAD.ESPID, ";
				$cadena_sql.="PRESPECIALIDAD.ESPDESCRIPCION ";
				$cadena_sql.="FROM ";
				$cadena_sql.="PRESPECPROVEEDOR ";
                                $cadena_sql.="INNER JOIN PRACTIVIDAD ON PRACTIVIDAD.ACTID=PRESPECPROVEEDOR.ACTID ";
                                $cadena_sql.="INNER JOIN PRESPECIALIDAD ON PRESPECIALIDAD.ESPID=PRESPECPROVEEDOR.ESPID ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="PRESPECIALIDAD.ACTID=PRESPECPROVEEDOR.ACTID ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="PRIDENTIFICACION=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="PRDIGITOVERIFICACION=".$variable[1];
  			break;

                        case "consultaEspecialidad":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="'S' ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="PRESPECPROVEEDOR ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="ESPID=".$variable[0]." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ACTID=".$variable[1]." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="PRIDENTIFICACION=".$variable[2]." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="PRDIGITOVERIFICACION=".$variable[3]." ";
                        break;

                        default:
				$cadena_sql="";
				break;
                 }

                 return $cadena_sql;
        }
	
	
}
?>

