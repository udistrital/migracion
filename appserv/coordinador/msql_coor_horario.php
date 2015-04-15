<?PHP

$QryHor= "SELECT dia_nombre, SAL_ID_ESPACIO, 
SAL_NOMBRE ,
 sed_nombre||'-'||EDI_NOMBRE, min(hor_hora),
 (min(hor_hora)||'-'||(max(hor_hora+1))), dia_cod 
 FROM (gesalones left outer join gesede on sal_sed_cod = sed_cod) left outer join geedificio on sal_edificio=edi_cod , achorarios hor ,accursos,acasperi,gehora,gedia
  WHERE hor_id_curso=cur_id 
  AND ape_ano = cur_ape_ano 
  AND ape_per = cur_ape_per 
  AND ape_estado = 'A' 
  AND cur_asi_cod = $Asi 
  AND cur_id = $idGrupo 
  AND hor.hor_estado = 'A' 
  AND cur_estado = 'A' 
  AND hor.HOR_HORA = hor_cod 
  AND dia_cod = hor.hor_dia_nro 
  AND SAL_ID_ESPACIO = hor.HOR_SAL_ID_ESPACIO 
  group by dia_nombre,
  SAL_ID_ESPACIO,SAL_NOMBRE,sed_nombre,EDI_NOMBRE,dia_cod 
  order by 7,5 asc ";

?>