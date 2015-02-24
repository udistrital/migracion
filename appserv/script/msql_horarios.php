<?PHP
$HorCra = "SELECT ACCURSO.CUR_SEMESTRE, 
       ACASI.ASI_COD,
       ACASI.ASI_NOMBRE,
       ACCURSO.CUR_NRO,
       GESALON_2012.SAL_ID_ESPACIO,
       GEDIA.DIA_COD,
	     GEDIA.DIA_NOMBRE,
       GESEDE.SED_COD,
	     GESEDE.SED_NOMBRE,
       MIN(hor_hora)||'-'||(MAX(hor_hora)+1)
FROM ACHORARIO_2012, ACCURSO, ACASI,GESALON_2012, ACASPERI, GEDIA, GESEDE
WHERE ACASI.ASI_COD = ACHORARIO_2012.HOR_ASI_COD
      AND ACASPERI.APE_ANO = ACHORARIO_2012.HOR_APE_ANO
      AND ACASPERI.APE_PER = ACHORARIO_2012.HOR_APE_PER
      AND GEDIA.DIA_COD = ACHORARIO_2012.HOR_DIA_NRO
      AND GESEDE.SED_COD = ACHORARIO_2012.HOR_SED_COD
      AND ACCURSO.CUR_CRA_COD = $carrera
      AND ACCURSO.CUR_SEMESTRE = $semestre
      AND ACCURSO.CUR_ESTADO='A'
	  AND CUR_APE_ANO=HOR_APE_ANO 
      AND CUR_APE_PER=HOR_APE_PER 
      AND ACCURSO.CUR_ASI_COD = ACHORARIO_2012.HOR_ASI_COD
      AND ACCURSO.CUR_NRO = ACHORARIO_2012.HOR_NRO
      AND GESALON_2012.SAL_ID_ESPACIO=ACHORARIO_2012.HOR_SAL_ID_ESPACIO
GROUP BY ACCURSO.CUR_SEMESTRE, 
       ACASI.ASI_COD,
       ACASI.ASI_NOMBRE,
       ACCURSO.CUR_NRO,
       GESALON_2012.SAL_ID_ESPACIO,
       GEDIA.DIA_COD,
	     GEDIA.DIA_NOMBRE,
       GESEDE.SED_COD,
	     GESEDE.SED_NOMBRE
ORDER BY ACCURSO.CUR_SEMESTRE, 
       ACASI.ASI_COD,
       ACCURSO.CUR_NRO";

?>
