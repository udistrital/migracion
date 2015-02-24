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

class sql_panelPrincipal extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "consultarAsignaturasPertenecenCRED":
				$cadena_sql="
				SELECT 
					est_cod,
					not_asi_cod,
					not_cred,
					pen_cre,
					pen_nro_ht,
					pen_nro_hp,
					pen_nro_aut,
					(SELECT clp_cea_cod FROM acclasificacpen WHERE clp_asi_cod=not_asi_cod AND clp_estado='A' AND clp_pen_nro=pen_nro AND clp_cra_cod=not_cra_cod),
					pen_sem
					FROM acest,acpen,acnot
					WHERE est_cod=not_est_cod
					and est_pen_nro=pen_nro
					and pen_asi_cod=not_asi_cod
					and not_cred is null
					and est_ind_cred='S'
					and pen_cre is not null
					and pen_cra_cod=est_cra_cod
					and not_est_reg='A' ";  
	
				break;
			case "consultarAsignaturasPertenecenCLAS":
				$cadena_sql="
				SELECT 
					est_cod,
					not_asi_cod,
					not_cred,
					pen_cre,
					pen_nro_ht,
					pen_nro_hp,
					pen_nro_aut,
					(SELECT clp_cea_cod FROM acclasificacpen WHERE clp_asi_cod=not_asi_cod AND clp_estado='A' AND clp_pen_nro=pen_nro AND clp_cra_cod=not_cra_cod),
					pen_sem
					FROM acest,acpen,acnot
					WHERE est_cod=not_est_cod
					and est_pen_nro=pen_nro
					and pen_asi_cod=not_asi_cod
					and not_cea_cod is null
					and est_ind_cred='S'
					and pen_cre is not null
					and pen_cra_cod=est_cra_cod
					and not_est_reg='A' ";  
	
				break;
			case "consultarAsignaturasNoPertenecenCRED":
				$cadena_sql="
					SELECT 
					  not_est_cod,
					  not_asi_cod,
					  not_cred,
					  pen_cre,
					  pen_nro_ht,
					  pen_nro_hp,
					  pen_nro_aut,
					  (SELECT clp_cea_cod FROM acclasificacpen WHERE clp_asi_cod=not_asi_cod AND clp_estado='A' AND clp_pen_nro=pen_nro AND clp_cra_cod=not_cra_cod) ceacod,
					  pen_sem
					from 
					  acpen,
					  accursohis,
					  acnot,
					  acest
					where cur_asi_cod=pen_asi_cod
					and cur_ape_ano=not_ano
					and cur_ape_per=not_per
					and cur_nro=not_gr
					and pen_asi_cod=not_asi_cod
					and pen_cra_cod=not_cra_cod
					and not_est_cod=est_cod
					and est_ind_cred='S'
					and est_cra_cod=not_cra_cod
					and not_est_reg='A'
					and pen_estado='A'
					and not_cred is null
					and pen_cre is not null";  
	
			break;
			
			case "consultarAsignaturasNoPertenecenCLAS":
				$cadena_sql="
					SELECT 
					  not_est_cod,
					  not_asi_cod,
					  not_cred,
					  pen_cre,
					  pen_nro_ht,
					  pen_nro_hp,
					  pen_nro_aut,
					  (SELECT clp_cea_cod FROM acclasificacpen WHERE clp_asi_cod=not_asi_cod AND clp_estado='A' AND clp_pen_nro=pen_nro AND clp_cra_cod=not_cra_cod) ceacod,
					  pen_sem
					from 
					  acpen,
					  accursohis,
					  acnot,
					  acest
					where cur_asi_cod=pen_asi_cod
					and cur_ape_ano=not_ano
					and cur_ape_per=not_per
					and cur_nro=not_gr
					and pen_asi_cod=not_asi_cod
					and pen_cra_cod=not_cra_cod
					and not_est_cod=est_cod
					and est_ind_cred='S'
					and est_cra_cod=not_cra_cod
					and not_est_reg='A'
					and pen_estado='A'
					and not_cea_cod is null
					and pen_cre is not null";  
	
			break;
				
			case "consultarAsignaturasConHoras":
				$cadena_sql="";  
			break;	
			case "actualizarAsignaturas":
				$cadena_sql="
					UPDATE acnot 
					SET not_cred='{$variable[3]}',
					not_nro_ht='{$variable[4]}',
					not_nro_hp='{$variable[5]}',
					not_nro_aut='{$variable[6]}',
					not_cea_cod='{$variable[7]}'
					WHERE not_est_cod={$variable[0]} 
					and not_asi_cod={$variable[1]}
					and not_cred is null
					and not_est_reg='A' 
				";  
			break;
			case "actualizarCEACOD":
				$cadena_sql="
					UPDATE acnot 
					SET not_cea_cod='{$variable[7]}'
					WHERE not_est_cod={$variable[0]} 
					and not_asi_cod={$variable[1]}
					and not_cred is null
					and not_est_reg='A' 
				";  
			break;
			default:
				$cadena_sql="";
				break;
		}
		echo "<br>$opcion=".$cadena_sql."<br>";
		return $cadena_sql;
	}
	
	
}
?>
