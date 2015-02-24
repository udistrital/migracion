<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminPlanGeneral extends sql {

    function cadena_sql($configuracion, $conexion, $opcion, $variable="") {

        switch ($opcion) {

            case "consultar_franja":
                $cadena_sql = "SELECT ";
                $cadena_sql.= "cod_actividad, ";
                $cadena_sql.= "actividad, ";
                $cadena_sql.= "cod_facultad, ";
                $cadena_sql.= "fecha_inicio, ";
                $cadena_sql.= "fecha_fin ";
                $cadena_sql.= "FROM ";
                $cadena_sql.= "sesion_temp ";
                break;

            /*****************************ESTA ES LA CONSULTA QUE SE DEBE UTILIZAR **********************
            case "consultar_franja":
                $cadena_sql = "SELECT ";
                $cadena_sql.= "AAC_COD_FRANJA, ";
                $cadena_sql.= "AAC_CRA_COD, ";
                $cadena_sql.= "AAC_DEP_COD, ";
                $cadena_sql.= "TO_CHAR(AAC_FECHA_INI, 'YYYYMMDDhh24mmss'), ";
                $cadena_sql.= "TO_CHAR(AAC_FECHA_FIN, 'YYYYMMDDhh24mmss') ";
                $cadena_sql.= "FROM ";
                $cadena_sql.= "acfranjasadican ";
                break;*******************************/
            
            case "consulta_codigo":
                
                $cadena_sql = "SELECT (CASE ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='001' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='003' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='004' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='010' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='013' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='014' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='021' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='030' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='031' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='032' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='033' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='080' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='081' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='085' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='110' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='113' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='114' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='180' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='185' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='186' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='481' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='485' then 23 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='023' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='035' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='037' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='039' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='040' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='045' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='047' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='048' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='050' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='055' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='056' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='057' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='060' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='061' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='062' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='063' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='064' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='065' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='068' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='070' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='075' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='076' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='087' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='134' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='135' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='140' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='145' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='150' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='155' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='160' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='164' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='165' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='167' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='170' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='176' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='187' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='188' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='191' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='192' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='601' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='602' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='603' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='604' then 24 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='071' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='072' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='073' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='074' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='077' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='078' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='079' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='083' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='172' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='173' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='174' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='177' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='178' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='179' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='271' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='272' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='273' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='274' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='275' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='277' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='278' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='279' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='283' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='372' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='378' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='472' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='473' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='474' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='477' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='478' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='479' then 32 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='002' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='005' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='007' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='015' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='017' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='018' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='019' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='020' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='022' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='025' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='090' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='091' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='092' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='093' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='094' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='095' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='099' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='100' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='101' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='117' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='193' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='194' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='195' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='196' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='197' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='198' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='199' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='295' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='331' then 33 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='011' then 100 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='012' then 101 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='016' then 101 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='089' then 101 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='096' then 101 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='097' then 101 ";
                $cadena_sql.= "when SUBSTR('$variable',6,3)='098' then 101 ";
                $cadena_sql.= "else 'no encontrado' end )";     
                

                break;
            
            /*(CASE when SUBSTR('$nick',6,3)='001' then  'ADMINISTRACION DEPORTIVA'when SUBSTR('$nick',6,3)='003' 
                then  'TECNOLOGIA EN ADMINISTRACION DEPORTIVA'when SUBSTR('$nick',6,3)='004' then  
                'ADMINISTRACION DEPORTIVA (NOCTURNA)' when SUBSTR('$nick',6,3)='010' then  
                'INGENIERIA FORESTAL'when SUBSTR('$nick',6,3)='013' then  'ESP. EN GERENCIA DE RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='014' then  'ESP. EN DISEÑO DE VIAS URBANAS, TRANSITO Y TRANSP...'when 
                SUBSTR('$nick',6,3)='021' then  'MAESTRIA EN MANEJO, USO Y CONSERVACION DEL BOSQUE'when 
                SUBSTR('$nick',6,3)='030' then  'TECNOLOGIA EN TOPOGRAFIA'when SUBSTR('$nick',6,3)='031' then  
                'TECNOLOGIA EN TOPOGRAFIA(DIURNO)'when SUBSTR('$nick',6,3)='032' then  'INGENIERIA TOPOGRAFICA'when 
                SUBSTR('$nick',6,3)='033' then  'INGENIERIA TOPOGRAFICA'when SUBSTR('$nick',6,3)='080' then  
                'SERVICIOS PUBLICOS SANITARIOS'when SUBSTR('$nick',6,3)='081' then  'GESTION AMBIENTAL Y SERVICIOS PUBLICOS'
                when SUBSTR('$nick',6,3)='085' then  'SANEAMIENTO AMBIENTAL'when SUBSTR('$nick',6,3)='110' then  
                'MAESTRIA EN DESARROLLO SUSTENTABLE Y GESTION AMBIE...'when SUBSTR('$nick',6,3)='113' then  
                'ESP. EN GERENCIA DE RECURSOS NATURALES (CONV. U. T...'when SUBSTR('$nick',6,3)='114' then  
                'ESPECIALIZACION EN AMBIENTE Y DESARROLLO LOCAL'when SUBSTR('$nick',6,3)='180' then  'INGENIERIA AMBIENTAL'
                when SUBSTR('$nick',6,3)='185' then  'ADMINISTRACION AMBIENTAL'when SUBSTR('$nick',6,3)='186' then  
                'ADMINISTRACION AMBIENTAL'when SUBSTR('$nick',6,3)='481' then  'GESTION AMBIENTAL Y SERVICIOS PUBLICOS 
                (CONVENIO 1...'when SUBSTR('$nick',6,3)='485' then  'SANEAMIENTO AMBIENTAL (CONVENIO 174 SED)'when 
                SUBSTR('$nick',6,3)='023' then  'ESP. EN INFANCIA CULT. Y DESA.'when SUBSTR('$nick',6,3)='035' then  
                'LICENCIATURA EN FISICA'when SUBSTR('$nick',6,3)='037' then  'ESP. EN EDU. PED. Y GES. AMBI'when 
                SUBSTR('$nick',6,3)='039' then  'ESP. EN EDUCACION EN TECNOLOGIA'when SUBSTR('$nick',6,3)='040' then  
                'LICENCIATURA EN BIOLOGIA'when SUBSTR('$nick',6,3)='045' then  'LICENCIATURA EN MATEMATICAS'when 
                SUBSTR('$nick',6,3)='047' then  'ESPECIALIZACION EN EDUCACION MATEMATICA'when SUBSTR('$nick',6,3)='048' then 
                'ESPECIALIZACION EN EDUCACION MATEMATICA (SINCELEJO...'when SUBSTR('$nick',6,3)='050' then  
                'LICENCIATURA EN QUIMICA'when SUBSTR('$nick',6,3)='055' then  'LICENCIATURA EN SOCIALES'when 
                SUBSTR('$nick',6,3)='056' then  'ESP. GERENCIA DE PROYECTOS EDUC.'when SUBSTR('$nick',6,3)='057' then  
                'MAESTRIA EN INVESTIGACION SOCIAL INTERDISCIPLINARI...'when SUBSTR('$nick',6,3)='060' then  
                'LICENCIATURA EN LINGUISTICA'when SUBSTR('$nick',6,3)='061' then  'ESP. LENGUAJE Y PEDAGOGIA DE PROYECTOS'
                when SUBSTR('$nick',6,3)='062' then  'MAESTRIA EN LINGUISTICA'when SUBSTR('$nick',6,3)='063' then  
                'ESPECIALIZACION EN PSICOLINGUISTICA'when SUBSTR('$nick',6,3)='064' then  'ESP. EN MET. Y APREN. DEL ESP.'
                when SUBSTR('$nick',6,3)='065' then  'LICENCIATURA EN ESPANOL-INGLES'when SUBSTR('$nick',6,3)='068' then  
                'ESP. EN EDUCACION SEXUAL (PR)'when SUBSTR('$nick',6,3)='070' then  'ESP. EN EDU. PED. Y GESTION A.'when 
                SUBSTR('$nick',6,3)='075' then  'LICENCIATURA EN PRIMARIA'when SUBSTR('$nick',6,3)='076' then  
                'ESPECIALIZACION EN EDUCACION SEXUAL'when SUBSTR('$nick',6,3)='087' then  'LIC. EN EDUC. PARA LA INFANCIA'
                when SUBSTR('$nick',6,3)='134' then  'INVESTIGACION Y EXTENSION DE PEDAGOGIA'when SUBSTR('$nick',6,3)='135' then  
                'LICENCIATURA EN FISICA'when SUBSTR('$nick',6,3)='140' then  'LICENCIATURA EN BIOLOGIA'when SUBSTR('$nick',6,3)='145' 
                then  'LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN MATEMATICAS'when SUBSTR('$nick',6,3)='150' then  
                'LICENCIATURA EN QUIMICA'when SUBSTR('$nick',6,3)='155' then  'LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN CIENCIAS 
                SOCIALES'when SUBSTR('$nick',6,3)='160' then  'LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN LENGUA CASTELLANA'when 
                SUBSTR('$nick',6,3)='164' then  'ESPEC. EN PEDAGOGIA DE LA COMUNICACION Y MEDIOS IN...'when SUBSTR('$nick',6,3)='165' 
                then  'LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN INGLES'when SUBSTR('$nick',6,3)='167' then  'MATEMATICAS'when 
                SUBSTR('$nick',6,3)='170' then  'ESPECIALIZACION EN EDUCACION Y GESTION AMBIENTAL'when SUBSTR('$nick',6,3)='176' then  
                'DESARROLLO HUMANO CON ENFASIS EN PROCESOS AFECTIVO...'when SUBSTR('$nick',6,3)='187' then  'LICENCIATURA EN PEDAGOGIA 
                INFANTIL'when SUBSTR('$nick',6,3)='188' then  'LICENCIATURA EDUACION BASICA CON ENFASIS EN EDUCACION ARTISTICA'when 
                SUBSTR('$nick',6,3)='191' then  'MAESTRÃA EN COMUNICACIÃ“N - EDUCACIÃ“N'when SUBSTR('$nick',6,3)='192' then  'MAESTRÃA 
                EN PEDAGOGÃA DE LA LENGUA MATERNA'when SUBSTR('$nick',6,3)='601' then  'DOCTORADO INTERINST . EN EDUCACION - HISTORIA DE 
                L...'when SUBSTR('$nick',6,3)='602' then  'DOCTORADO INTERINST. EN EDUCACION - EDUCACION EN C...'when 
                SUBSTR('$nick',6,3)='603' then  'DOCTORADO INTERINST. EN EDUCACION - EDUCACION MATE...'when SUBSTR('$nick',6,3)='604' 
                then  'DOCTORADO INTERISNT. EN EDUCACION - LENGUAJE Y EDU...'when SUBSTR('$nick',6,3)='071' then  
                'ESP. EN CONTROL ELECTRONICO'when SUBSTR('$nick',6,3)='072' then  'TECNOLOGIA EN ELECTRICIDAD'when 
                SUBSTR('$nick',6,3)='073' then  'TECNOLOGIA EN ELECTRONICA'when SUBSTR('$nick',6,3)='074' then  'TECNOLOGIA EN MECANICA'
                when SUBSTR('$nick',6,3)='077' then  'TECNOLOGIA EN INDUSTRIAL'when SUBSTR('$nick',6,3)='078' then  
                'TECNOLOGIA SISTEMATIZACION DE DATOS'when SUBSTR('$nick',6,3)='079' then  'TECNOLOGIA CONSTRUC. CIVILES'when 
                SUBSTR('$nick',6,3)='083' then  'ING. EN CONTROL ELECTRONICO E INSTRUMENTACION'when SUBSTR('$nick',6,3)='172' then  
                'ESP. TECNOLOGICA EN REDES DE DISTRIBUCION ELECTRIC...'when SUBSTR('$nick',6,3)='173' then  
                'ESP. TECNOLOGICA EN TELECOMUNICACIONES'when SUBSTR('$nick',6,3)='174' then  
                'ESP. TECNOLOGICA EN MECANICA CON ENFASIS EN PROCES...'when SUBSTR('$nick',6,3)='177' then  
                'ESP. TECNOLOGICA EN SISTEMAS AVANZADOS DE PRODUCCI...'when SUBSTR('$nick',6,3)='178' then  
                'ESP. TECNOLOGICA EN REDES DE COMPUTADORES'when SUBSTR('$nick',6,3)='179' then  
                'ESP. TECNOLOGICA EN DISEÃ‘O Y CONSTRUCCION DE VIAS'when SUBSTR('$nick',6,3)='271' then  
                'DISTRIBUCION Y REDES ELECTRICAS'when SUBSTR('$nick',6,3)='272' then  'ING.DISTRIBUCION Y REDES ELECTRICAS'when 
                SUBSTR('$nick',6,3)='273' then  'INGENIERIA EN TELECOMUNICACIONES'when SUBSTR('$nick',6,3)='274' then  
                'INGENIERIA MECANICA'when SUBSTR('$nick',6,3)='275' then  'INGENIERIA MECANICA (NOCTURNO)'when SUBSTR('$nick',6,3)='277' 
                then  'INGENIERIA DE PRODUCCION'when SUBSTR('$nick',6,3)='278' then  'INGENIERIA EN REDES DE COMPUTADORES'when 
                SUBSTR('$nick',6,3)='279' then  'INGENIERIA CIVIL'when SUBSTR('$nick',6,3)='283' then  'INGENIERIA EN CONTROL'when 
                SUBSTR('$nick',6,3)='372' then  'INGENIERIA ELECTRICA (CICLOS PROPEDEUTICOS)'when SUBSTR('$nick',6,3)='378' then  
                'INGENIERIA EN TELEMATICA'when SUBSTR('$nick',6,3)='472' then  'TECNOLOGIA EN ELECTRICIDAD (CONVENIO 174 SED)'when 
                SUBSTR('$nick',6,3)='473' then  'TECNOLOGIA EN ELECTRONICA (CONVENIO 174 SED)'when SUBSTR('$nick',6,3)='474' then  
                'TECNOLOGIA EN MECANICA (CONVENIO 174 SED)'when SUBSTR('$nick',6,3)='477' then  'TECNOLOGIA EN INDUSTRIAL 
                (CONVENIO 174 SED)'when SUBSTR('$nick',6,3)='478' then  'TECNOLOGIA SISTEMATIZACION DE DATOS (CONVENIO 174 ...'when 
                SUBSTR('$nick',6,3)='479' then  'TECNOLOGIA CONSTRUC. CIVILES (CONVENIO 174 SED)'when SUBSTR('$nick',6,3)='002' then  
                'ESPECIALIZACION EN BIOINGENIERIA'when SUBSTR('$nick',6,3)='005' then  'INGENIERIA ELECTRONICA'when 
                SUBSTR('$nick',6,3)='007' then  'INGENIERIA ELECTRICA'when SUBSTR('$nick',6,3)='015' then  'INGENIERIA INDUSTRIAL'
                when SUBSTR('$nick',6,3)='017' then  'ESP. INGENIERIA DE PRODUCCION'when SUBSTR('$nick',6,3)='018' then  
                'ESP. HIGIENE Y SALUD OCUPACIONAL'when SUBSTR('$nick',6,3)='019' then  'ESP. INGENIERIA DE PRODUCCION Y LOGISTICA'when 
                SUBSTR('$nick',6,3)='020' then  'INGENIERIA DE SISTEMAS'when SUBSTR('$nick',6,3)='022' then  'INGENIERIA DE SISTEMAS 
                (CONVENIO UNIV. AMAZONIA)'when SUBSTR('$nick',6,3)='025' then  'INGENIERIA CATASTRAL Y GEODESIA'when 
                SUBSTR('$nick',6,3)='090' then  'ESPECIALIZACION EN TELECOMUNICACIONES MOVILES'when SUBSTR('$nick',6,3)='091' then  
                'ESP. TELEFONIA MOVIL CEL.'when SUBSTR('$nick',6,3)='092' then  'ESP. INFORMATICA INDUSTRIAL'when SUBSTR('$nick',6,3)='093'
                then  'ESPECIALIZACION EN TELEINFORMATICA'when SUBSTR('$nick',6,3)='094' then  'ESP. SISTEMAS DE INFORMACION GEOGRAFICA'
                when SUBSTR('$nick',6,3)='095' then  'MAESTRIA EN TELEINFORMATICA'when SUBSTR('$nick',6,3)='099' then  'ESP. INGENIERIA 
                DEL SOFTWARE'when SUBSTR('$nick',6,3)='100' then  'ESP. EN INGENIERIA DE SOFTWARE (CONV. UNIVERSIDAD ...'when 
                SUBSTR('$nick',6,3)='101' then  'ESP. INFORMÃTICA Y AUTOMÃTICA INDUSTRIAL'when SUBSTR('$nick',6,3)='117' then  
                'ESPECIALIZACION EN AVALUOS'when SUBSTR('$nick',6,3)='193' then  'ESPECIALIZACION EN TELEINFORMATICA (CUCUTA)'when 
                SUBSTR('$nick',6,3)='194' then  'ESPECIALIZACION EN TELEINFORMATICA (NEIVA)'when SUBSTR('$nick',6,3)='195' then  
                'MAE. EN CIENCIAS DE LA INF. Y LAS COMUNICACIONES E...'when SUBSTR('$nick',6,3)='196' then  
                'MAESTRIA EN INGENIERIA INDUSTRIAL'when SUBSTR('$nick',6,3)='197' then  
                'ESPECIALIZACION EN GESTION DE PROYECTOS DE INGENIE...'when SUBSTR('$nick',6,3)='198' then  
                'ESPECIALIZACION EN INGENIERIA DE SOFTWARE (CORDOBA...'when SUBSTR('$nick',6,3)='199' then  
                'ESPECIALIZACION EN PROYECTOS INFORMATICOS'when SUBSTR('$nick',6,3)='295' then  
                'MAE. EN CIENCIAS DE LA INF. Y LAS COMUNICACIONES E...'when SUBSTR('$nick',6,3)='331' then  
                'CICLO BASICO INGENIERIA' when SUBSTR('$nick',6,3)='011' then  'ARTES ESCENICAS (CTG)' when SUBSTR('$nick',6,3)='012' 
                then  'ARTES PLASTICAS (CTG)' when SUBSTR('$nick',6,3)='016' then  'ARTES PLASTICAS' when SUBSTR('$nick',6,3)='089' 
                then  'ESP. EN VOZ ESCENICA' when SUBSTR('$nick',6,3)='096' then  'ARTES PLASTICAS'when SUBSTR('$nick',6,3)='097' 
                then  'ARTES ESCENICAS'when SUBSTR('$nick',6,3)='098' then  
                'ARTES MUSICALES'else 'no encontrado'end ),
                (CASE when SUBSTR('$nick',6,3)='001' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='003' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='004' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='010' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when
                SUBSTR('$nick',6,3)='013' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='014' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='021' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='030' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='031' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='032' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='033' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='080' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='081' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='085' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='110' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='113' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='114' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='180' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='185' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='186' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='481' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='485' then 'FACULTAD DE MEDIO AMBIENTE Y RECURSOS NATURALES'when 
                SUBSTR('$nick',6,3)='023' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='035' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='037' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='039' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='040' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='045' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='047' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='048' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='050' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='055' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='056' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='057' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='060' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='061' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='062' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='063' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='064' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='065' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='068' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='070' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='075' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='076' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='087' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='134' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='135' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='140' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='145' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='150' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='155' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='160' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='164' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='165' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='167' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='170' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='176' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='187' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='188' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='191' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='192' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='601' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='602' then 
                'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='603' then 'FACULTAD DE CIENCIAS Y EDUCACION'
                when SUBSTR('$nick',6,3)='604' then 'FACULTAD DE CIENCIAS Y EDUCACION'when SUBSTR('$nick',6,3)='071' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='072' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='073' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='074' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='077' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='078' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='079' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='083' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='172' then                 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='173' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='174' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='177' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='178' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='179' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='271' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='272' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='273' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='274' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='275' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='277' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='278' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='279' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='283' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='372' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='378' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='472' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='473' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='474' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='477' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='478' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='479' then 
                'FACULTAD DE TECNOLOGIA - POLITECNICA / TECNOLOGICA'when SUBSTR('$nick',6,3)='002' then 
                'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='005' then 'FACULTAD DE INGENIERIA'when 
                SUBSTR('$nick',6,3)='007' then 'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='015' then 
                'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='017' then 'FACULTAD DE INGENIERIA'when 
                SUBSTR('$nick',6,3)='018' then 'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='019' then 
                'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='020' then 'FACULTAD DE INGENIERIA'when 
                SUBSTR('$nick',6,3)='022' then 'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='025' then 
                'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='090' then 'FACULTAD DE INGENIERIA'when 
                SUBSTR('$nick',6,3)='091' then 'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='092' then 
                'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='093' then 'FACULTAD DE INGENIERIA'when 
                SUBSTR('$nick',6,3)='094' then 'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='095' then 
                'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='099' then 'FACULTAD DE INGENIERIA'when 
                SUBSTR('$nick',6,3)='100' then 'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='101' then 
                'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='117' then 'FACULTAD DE INGENIERIA'when 
                SUBSTR('$nick',6,3)='193' then 'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='194' then 
                'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='195' then 'FACULTAD DE INGENIERIA'when 
                SUBSTR('$nick',6,3)='196' then 'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='197' then 
                'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='198' then 'FACULTAD DE INGENIERIA'when 
                SUBSTR('$nick',6,3)='199' then 'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='295' then 
                'FACULTAD DE INGENIERIA'when SUBSTR('$nick',6,3)='331' then 'FACULTAD DE INGENIERIA'when 
                SUBSTR('$nick',6,3)='011' then 'VICERRECTORIA CONVENIOS'when SUBSTR('$nick',6,3)='012' then 
                'FACULTAD DE ARTES-ASAB'when SUBSTR('$nick',6,3)='016' then 'FACULTAD DE ARTES-ASAB'when 
                SUBSTR('$nick',6,3)='089' then 'FACULTAD DE ARTES-ASAB'when SUBSTR('$nick',6,3)='096' then 
                'FACULTAD DE ARTES-ASAB'when SUBSTR('$nick',6,3)='097' then 'FACULTAD DE ARTES-ASAB'when 
                SUBSTR('$nick',6,3)='098' then 'FACULTAD DE ARTES-ASAB'else 'no encontrado' end )*/

            default:
                $cadena_sql = "";
                break;
        }//fin switch
        return $cadena_sql;
    }

// fin funcion cadena_sql
}

//fin clase sql_adminProyecto
?>
