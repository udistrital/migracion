<?php
/* 
 * Funcion que tiene metodos para los procesos de homologaciones
 * 
 */

/**
 * Permite realizar procedimientos de homologaciones
 * Cada funcion recibe unos parametros especificos
 *
 * @author Fernando Torres
 * @author Milton Parra
 * Fecha 11 de Diciembre de 2012
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

class homologaciones {
  private $configuracion;
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

    }

    /**
     * Funcion que crea los arreglos para las homologaciones de auerdo a los tipos existentes
     * @param type $datosEstudiante
     * @param type $notasAnteriores
     * @param type $notasActuales
     * @param type $espaciosPlanEstudio
     * @param type $tabla_homologaciones
     * @param type $tipo_homologacion
     * @param type $notaAprobatoria
     */
    function ejecutarHomologacion($datosEstudiante,$notasAnteriores,$notasActuales,$espaciosPlanEstudio, $tabla_homologaciones,$tipo_homologacion,$notaAprobatoria) {
        if (is_array($notasAnteriores)&&isset($notasAnteriores[0]['COD_ESPACIO']))
        {
            $notas=$this->buscarNotasAprobadas($notasAnteriores,$notaAprobatoria);
            switch ($tipo_homologacion) {
                case 'implicitas':
                    $implicitos=$this->buscarEspaciosImplicitos($notas['aprobados'],$espaciosPlanEstudio);
                    //Se adicionan estas líneas para que se realice la homologación de espacios Electivos extrinsecos y se tomen 
                    //como homologaciones implicitas y toman los datos del espacio que vio el estudiante
                    $electivosExtrinsecos=$this->buscarEspaciosElectivosExtrinsecos($notas['aprobados']);
                    if(is_array($electivosExtrinsecos))
                    {
                         if(is_array($implicitos)){
                             foreach ($electivosExtrinsecos as $ee) {
                                 $implicitos[]=$ee;
                             }
                         }else{
                             $implicitos=$electivosExtrinsecos;
                         }
                        
                    }
                    
                    if(is_array($implicitos))
                    {
                        $homologacionesImplicitas=$this->verificarImplicitas($implicitos,$notasActuales,$espaciosPlanEstudio);
                    }else
                        {
                            $homologacionesImplicitas='';
                        }
                    $homologaciones=array('IMPLICITAS'=>$homologacionesImplicitas);
                    return$homologaciones;
                    break;
                    
                case 'unoauno':
                    $unoAUno=$this->buscarHomologacionesUnoAUno($notas['aprobados'],$tabla_homologaciones);
                    if(is_array($unoAUno))
                    {
                        $homologacionesUnoAUno=$this->verificarTransitividad($unoAUno, $tabla_homologaciones, $espaciosPlanEstudio, $notasActuales,'0');
                    }else
                        {
                            $homologacionesUnoAUno='';
                        }
                    $homologaciones=array('UNOAUNO'=>$homologacionesUnoAUno);
                    return$homologaciones;
                    break;
                    
                case 'union':
                    $union=$this->buscarHomologacionesUnion($notasAnteriores,$tabla_homologaciones);
                    if(is_array($union))
                    {
                        $homologacionesUnion=$this->verificarUnion($union,$espaciosPlanEstudio,$notasActuales,$notaAprobatoria);
                    }else
                        {
                            $homologacionesUnion='';
                        }
                    $homologaciones=array('UNION'=>$homologacionesUnion);
                    return$homologaciones;
                    break;
                    
                case 'bifurcacion':
                    $bifurcacion=$this->buscarHomologacionesBifurcacion($notas['aprobados'],$tabla_homologaciones);
                    if(is_array($bifurcacion))
                    {
                        $homologacionesBifurcacion=$this->verificarBifurcacion($bifurcacion,$notasActuales,$espaciosPlanEstudio);
                    }else
                        {
                            $homologacionesBifurcacion='';
                        }
                    $homologaciones=array('BIFURCACION'=>$homologacionesBifurcacion);
                    return$homologaciones;
                    break;

                default:
                    break;
            }
        }else{}
    }
    
    /**
     * Funcion que busca homologaciones de espacios que existen en el plan de estudios del estudiante
     * @param type $notasAnterioresAprobadas
     * @param type $espaciosPlanEstudio
     * @return type
     */
    function buscarEspaciosImplicitos($notasAnterioresAprobadas,$espaciosPlanEstudio){
        
        $espaciosImplicitos=array();
        if(is_array($notasAnterioresAprobadas)&&isset($notasAnterioresAprobadas[0]['COD_ESPACIO']))
        {
            foreach ($notasAnterioresAprobadas as $nota)
            {
                if(is_array($espaciosPlanEstudio))
                {
                    $espacio=$this->buscarEspacioEnPlan($espaciosPlanEstudio,$nota['COD_ESPACIO']);
                    if(is_array($espacio))
                    {
                        $nota=$this->remplazarDatosNota($nota, $espacio);
                        $espaciosImplicitos[]=$nota;
                    }else
                        {
                        }
                }else
                    {
                        
                    }
            }
        }else
            {
                
            }
        return $espaciosImplicitos;
    }
    
    /**
     * Esta funcion evalua si las homologaciones implicitas ya estan en notas actuales del estudiante
     * @param type $implicitas
     */
    function verificarImplicitas($homologacionesImplicitas,$notasActuales) {
        
        if(is_array($notasActuales))
        {
            foreach ($notasActuales as $notas)
            {
                foreach ($homologacionesImplicitas as $key=>$implicitas)
                {
                    if($implicitas['COD_ESPACIO']==$notas['COD_ESPACIO'])
                    {
                        unset($homologacionesImplicitas[$key]);
                    }
                }
            }
            if(is_array($homologacionesImplicitas)&&!empty($homologacionesImplicitas))
            {
            }else
                {
                    $homologacionesImplicitas='';
                }
                return $homologacionesImplicitas;
        }else
            {
                return $homologacionesImplicitas;
            }
    }    
    
    /**
     * Funcion que busca entre las notas y la tabla de homologaciones las homologaciones directas
     * @param type $arreglo
     * @return string 
     */
    function buscarHomologacionesUnoAUno($notasAnterioresAprobadas,$tablaHomologaciones){
        $espaciosHomologos=array();
        if(is_array($notasAnterioresAprobadas) && isset($notasAnterioresAprobadas[0]['COD_ESPACIO']))
        {
            foreach ($notasAnterioresAprobadas as $nota)
            {
                if(is_array($tablaHomologaciones))
                {
                    foreach ($tablaHomologaciones as $homologo)
                    {
                        if($homologo['COD_ASI_HOM']==$nota['COD_ESPACIO']&&$homologo['TIPO_HOMOLOGACION']==0)
                        {
                            $espaciosHomologos[]=array('ESPACIO_HOM'=>$homologo['COD_ASI_HOM'],
                                                       'ESPACIO_PPAL'=>$homologo['COD_ASI_PPAL'],
                                                       'NOTA'=>$nota);
                        }else
                            {
                            }
                    }
                }else
                    {
                        
                    }
            }
        }else
            {
                
            }
        return $espaciosHomologos;
    }
    
    /**
     * Funcion que verifica una homologacion uno a uno y su transitividad
     * @param type $homologaciones
     * @param type $tablaHomologaciones
     * @param type $planEstudio
     * @param type $notasActuales
     * @param type $tipo
     * @return string
     */
    function verificarTransitividad($homologaciones,$tablaHomologaciones,$planEstudio,$notasActuales,$tipo) {
        $transitivo='';
        //verifica homologo en tabla
        $total=count($homologaciones);
        for($a=0;$a<$total;$a++)
        {
            $noEjecuta=0;
            $borrar=0;
            $verificadoTabla=1;
            if(is_array($transitivo))
            {
                $verificadoTabla=$this->verificarEspacioEnTabla($tablaHomologaciones,'COD_ASI_HOM',$homologaciones[$a]['ESPACIO_HOM'],$tipo);
                $homologaciones[$a]['ESPACIO_HOM']=$verificadoTabla['COD_ASI_HOM'];
                $homologaciones[$a]['ESPACIO_PPAL']=$verificadoTabla['COD_ASI_PPAL'];
            }
            if ($verificadoTabla)
            {
                //verifica principal en plan
                $verificadoPlan=$this->buscarEspacioEnPlan($planEstudio,$homologaciones[$a]['ESPACIO_PPAL']);
                if(is_array($verificadoPlan))
                {
                    //verifica si homologacion esta en nota actual
                    if(is_array($notasActuales))
                    {
                        foreach ($notasActuales as $notas)
                        {
                            if($homologaciones[$a]['ESPACIO_PPAL']==$notas['COD_ESPACIO'])
                            {
                                $borrar=1;
                                $transitivo='';
                            }
                                if ($borrar==1)break;
                        }
                    }
                    //si no esta en notas actuales, crea el registro con el valor original de homologo
                    if ($borrar==0)
                    {
                        if(is_array($transitivo))
                        {
                            $homologaciones[$a]['ESPACIO_HOM']=$transitivo[0];
                            $transitivo='';
                        }
                        $nota=$this->remplazarDatosNota($homologaciones[$a]['NOTA'], $verificadoPlan);
                        $homologaciones[$a]['NOTA']=$nota;
                        $homologacionesUnoAUno[]=$homologaciones[$a];
                    }
                }else
                    {
                        //cuando el ppal no esta en el plan hace transitividad
                        if(is_array($transitivo))
                        {
                            //busca si el homologo es igual a algun transitivo para evitar bucles
                            foreach ($transitivo as $valor) {
                                if ($valor==$homologaciones[$a]['ESPACIO_HOM'])
                                {
                                    $noEjecuta=1;
                                    $transitivo='';
                                }
                            }
                        }
                        //si no esta en transitivos, va a buscar nuevamente
                        if ($noEjecuta==0)
                        {
                            $transitivo[]=$homologaciones[$a]['ESPACIO_HOM'];
                            $homologaciones[$a]['ESPACIO_HOM']=$homologaciones[$a]['ESPACIO_PPAL'];
                            $a=$a-1;
                        }
                    }
            }else
                {
                    $transitivo='';
                }
        }
        if(!isset($homologacionesUnoAUno))
        {
            $homologacionesUnoAUno='';
        }
        if(is_array($homologacionesUnoAUno)){
            $espacios_notas=array();
            foreach ($homologacionesUnoAUno as $notas) {
                $espacios_notas[] = $notas['ESPACIO_HOM']; 
            }
            $espacios_notas = array_unique($espacios_notas);
            $espaciosAHomologar=array();
            foreach ($espacios_notas as $notas2) {
                foreach ($homologacionesUnoAUno as $notas3) {

                    if($notas2 == $notas3['ESPACIO_HOM']){
                    
                        $espaciosAHomologar[] = $notas3; 
                       // break;
                    }
                }
            }
            $homologacionesUnoAUno = $espaciosAHomologar;
            
        }
        return $homologacionesUnoAUno;
    }
    
    
    /**
     * Funcion que busca entre las notas y la tabla de homologaciones las parejas que correspondan a una homologacion por union
     * es decir los casos en los que hay un espacio principal y dos homologos
     * @param type $notasAnteriores
     * @param type $tablaHomologaciones
     * @return type
     */
    function buscarHomologacionesUnion($notasAnteriores,$tablaHomologaciones){
        $espaciosHomologosUnion=array();
        $tablaHomologaciones2=$tablaHomologaciones;
        //De notas anteriores se debe filtrar la nota mas alta de cada espacio
        if(is_array($notasAnteriores)&&isset($notasAnteriores[0]['COD_ESPACIO']))
        {
            $total=count($notasAnteriores);
            $a=0;
            for($t=0;$t<$total;$t++)
            {
                if(is_array($tablaHomologaciones))
                {
                    foreach ($tablaHomologaciones as $homologo1)
                    {
                        if($homologo1['COD_ASI_HOM']==(isset($notasAnteriores[$t]['COD_ESPACIO'])?$notasAnteriores[$t]['COD_ESPACIO']:'')&&$homologo1['TIPO_HOMOLOGACION']==1)
                        {
                            foreach ($tablaHomologaciones2 as $homologo2)
                            {
                                if($homologo2['COD_ASI_HOM']!=$homologo1['COD_ASI_HOM']&&$homologo1['FEC_REG']==$homologo2['FEC_REG'])
                                {
                                    if($homologo1['COD_ASI_HOM']!=(isset($espaciosHomologosUnion[$a-1]['ESPACIO_HOM_1'])?$espaciosHomologosUnion[$a-1]['ESPACIO_HOM_1']:''))
                                    {
                                        foreach ($notasAnteriores as $key=>$nota2)
                                        {
                                            if($nota2['COD_ESPACIO']==$homologo2['COD_ASI_HOM'])
                                            {
                                                $notasAnteriores[$t]['PORCENTAJE']=$homologo1['PORCENTAJE'];
                                                $notasAnteriores[$t]['REQ_APROBAR']=$homologo1['REQ_APROBAR'];
                                                $nota2['PORCENTAJE']=$homologo2['PORCENTAJE'];
                                                $nota2['REQ_APROBAR']=$homologo2['REQ_APROBAR'];
                                                $espaciosHomologosUnion[$a]=array('ESPACIO_HOM_1'=>$homologo1['COD_ASI_HOM'],
                                                                           'ESPACIO_HOM_2'=>$homologo2['COD_ASI_HOM'],
                                                                           'ESPACIO_PPAL'=>$homologo1['COD_ASI_PPAL'],
                                                                           'NOTA_1'=>$notasAnteriores[$t],
                                                                           'NOTA_2'=>$nota2);
                                                unset($notasAnteriores[$t]);
                                                $a++;
                                            }
                                        }
                                    }
                                }
                            }
                        }else
                            {
                            }
                    }
                }else
                    {
                        
                    }
            }
        }else
            {
                
            }
        return $espaciosHomologosUnion;
    }
    
    /**
     * Funcion que verifica las condiciones de una homologacion de union
     * @param type $homologaciones
     * @param type $espaciosPlan
     * @param type $notaAprobatoria
     * @return string
     */
    function verificarUnion($homologaciones,$espaciosPlan,$notasActuales,$notaAprobatoria) {
        foreach ($homologaciones as $homologo) {
            $noEjecuta=0;
            //Verifica que el espacio principal este en el plan de estudios actual
            $verificarPlan=$this->buscarEspacioEnPlan($espaciosPlan, $homologo['ESPACIO_PPAL']);
            if(is_array($verificarPlan))
            {
                //verifica condicion de aprobado para el espacio uno
                if($homologo['NOTA_1']['NOTA']<$notaAprobatoria)
                {
                    if($homologo['NOTA_1']['REQ_APROBAR']=='S')
                    {
                        $noEjecuta=1;
                    }
                }
                //verifica condicion de aprobado para espacio dos
                if($homologo['NOTA_2']['NOTA']<$notaAprobatoria)
                {
                    if($homologo['NOTA_2']['REQ_APROBAR']=='S')
                    {
                        $noEjecuta=1;
                    }
                }
                //calcula nota final
                $notaFinal=($homologo['NOTA_1']['NOTA']*$homologo['NOTA_1']['PORCENTAJE'])/100+($homologo['NOTA_2']['NOTA']*$homologo['NOTA_2']['PORCENTAJE'])/100;
                //verifica si nota final es aprobada
                if($notaFinal<$notaAprobatoria)
                {
                    $noEjecuta=1;
                }
                //verifica si el espacio ppal esta en las notas actuales
                foreach ($notasActuales as $nota) {
                    if($homologo['ESPACIO_PPAL']==$nota['COD_ESPACIO'])
                    {
                        $noEjecuta=1;
                        break;
                    }
                }
                //si alguna de las condiciones no se cumple no crea la homologacion
                if ($noEjecuta==0)
                {
                    $notaUnion=$this->remplazarDatosNota($homologo['NOTA_1'], $verificarPlan);
                    unset($homologo['NOTA_1']);
                    unset($homologo['NOTA_2']);
                    $homologo['NOTA']=$notaUnion;
                    $homologo['NOTA']['NOTA']=$notaFinal;
                    $homologacionesUnion[]=$homologo;
                }
            }
        }
        if(!isset($homologacionesUnion))
        {
            $homologacionesUnion='';
        }
        return $homologacionesUnion;
    }
    
    /**
     * Funcion que busca entre las notas y la tabla de homologaciones las parejas que correspondan a una homologacion por bifurcacion
     * es decir los casos en los que hay un espacio homologo y dos principales
     * @param type $notasAnterioresAprobadas
     * @param type $tablaHomologaciones
     * @return type
     */
    function buscarHomologacionesBifurcacion($notasAnterioresAprobadas,$tablaHomologaciones){
        $espaciosHomologosBifurcacion=array();
        $tablaHomologaciones2=$tablaHomologaciones;
        //De notas anteriores se debe filtrar la nota mas alta de cada espacio
        if(is_array($notasAnterioresAprobadas)&&isset($notasAnterioresAprobadas[0]['COD_ESPACIO']))
        {
            $a=0;
            foreach ($notasAnterioresAprobadas as $nota)
            {
                if(is_array($tablaHomologaciones))
                {
                    foreach ($tablaHomologaciones as $homologo1)
                    {
                        if($homologo1['COD_ASI_HOM']==$nota['COD_ESPACIO']&&$homologo1['TIPO_HOMOLOGACION']==2)
                        {
                            foreach ($tablaHomologaciones2 as $homologo2)
                            {
                                if($homologo2['COD_ASI_PPAL']!=$homologo1['COD_ASI_PPAL']&&$homologo1['FEC_REG']==$homologo2['FEC_REG']&&(isset($espaciosHomologosBifurcacion[$a-1]['ESPACIO_HOM'])?$espaciosHomologosBifurcacion[$a-1]['ESPACIO_HOM']:'')!=$homologo1['COD_ASI_HOM'])
                                {
                                    $espaciosHomologosBifurcacion[$a]=array('ESPACIO_HOM'=>$homologo1['COD_ASI_HOM'],
                                                               'ESPACIO_PPAL_1'=>$homologo1['COD_ASI_PPAL'],
                                                               'ESPACIO_PPAL_2'=>$homologo2['COD_ASI_PPAL'],
                                                               'NOTA'=>$nota);
                                    $a++;
                                }
                            }
                        }else
                            {
                            }
                    }
                }else
                    {
                        
                    }
            }
        }else
            {
                
            }
        return $espaciosHomologosBifurcacion;
    }
    
    /**
     * Funcion que verifica homologaciones de bifurcacion
     * @param type $homologacionesBifurcacion
     * @param type $notasActuales
     * @param type $planEstudio
     */
    function verificarBifurcacion($homologacionesBifurcacion,$notasActuales,$planEstudio) {
        foreach ($homologacionesBifurcacion as $bifurcacion) {
            //verifica ppal1
            $bifurcacion1=$this->verificarEspacioBifurcacion($planEstudio,$bifurcacion,'ESPACIO_PPAL_1',$notasActuales,'NOTA_1');
            //verifica ppal2
            $bifurcacion2=$this->verificarEspacioBifurcacion($planEstudio,$bifurcacion,'ESPACIO_PPAL_2',$notasActuales,'NOTA_2');
            if(is_array($bifurcacion1))
            {
                if(is_array($bifurcacion2))
                {
                    $homologacionBifurcacion[]=array_merge($bifurcacion1,$bifurcacion2);
                }else
                    {
                        $homologacionBifurcacion[]=$bifurcacion1;
                    }
            }else
                {
                    if(is_array($bifurcacion2))
                    {
                        $homologacionBifurcacion[]=$bifurcacion2;
                    }else
                        {

                        }
                }
        }
        if(!isset($homologacionBifurcacion))
        {
            $homologacionBifurcacion='';
        }
        return $homologacionBifurcacion;
    }
    
    /**
     * Funcion que verifica la existencia de un espacio en el plan de estudios
     * Si existe, retorna los datos del espacio en el plan
     * @param type $planEstudio
     * @param type $espacio
     * @return boolean
     */
    function buscarEspacioEnPlan($planEstudio, $espacio) {
        if(is_array($planEstudio))
        {
            foreach ($planEstudio as $espacioPlan)
            {
                if($espacioPlan['COD_ASI']==$espacio)
                {
                    return $espacioPlan;
                }else
                    {
                    }
            }
        }
        return false;
    }
    
    /**
     * Funcion que verifica la existencia de un espacio en la tabla de homologaciones
     * Si existe, retorna los datos del espacio en el plan
     * @param type $planEstudio
     * @param type $espacio
     * @return boolean
     */
    function verificarEspacioEnTabla($tablaHomologacion,$campo,$espacio,$tipo) {
        foreach ($tablaHomologacion as $espacioHomologacion)
        {
            if($espacioHomologacion[$campo]==$espacio&&$espacioHomologacion['TIPO_HOMOLOGACION']==$tipo)
            {
                return $espacioHomologacion;
            }else
                {
                }
        }
        return false;
    }


    /**
     * Funcion que remplaza los datos de la nota con los datos del espacio del plan de estudiante si los hay
     * @param type $datosNota
     * @param type $datosEspacioPlan
     * @return type
     */
    function remplazarDatosNota($datosNota, $datosEspacioPlan) {
        $datosNota['NIVEL_NOTA']=$datosEspacioPlan['SEMESTRE'];
        $datosNota['OBSERVACION']=(isset($datosNota['OBSERVACION'])?$datosNota['OBSERVACION']:0);
        $datosNota['CREDITOS']=(isset($datosEspacioPlan['CREDITOS'])?$datosEspacioPlan['CREDITOS']:(isset($datosNota['CREDITOS'])?$datosNota['CREDITOS']:''));
        $datosNota['HTD']=(isset($datosEspacioPlan['H_TEORICAS'])?$datosEspacioPlan['H_TEORICAS']:$datosNota['HTD']);
        $datosNota['HTC']=(isset($datosEspacioPlan['H_PRACTICAS'])?$datosEspacioPlan['H_PRACTICAS']:$datosNota['HTC']);
        $datosNota['HTA']=(isset($datosEspacioPlan['H_AUTONOMO'])?$datosEspacioPlan['H_AUTONOMO']:$datosNota['HTA']);
        $datosNota['CLASIFICACION']=(isset($datosEspacioPlan['CLASIFICACION'])?$datosEspacioPlan['CLASIFICACION']:(isset($datosNota['CLASIFICACION'])?$datosNota['CLASIFICACION']:''));
        return $datosNota;
    }
    
    /**
     * Funcion que verifica cada espacio en las homologaciones de bifurcacion
     * @param type $planEstudio
     * @param type $bifurcacion
     * @param type $nombrePrincipal
     * @param type $notasActuales
     * @param type $nombreNota
     * @return string
     */
    function verificarEspacioBifurcacion($planEstudio,$bifurcacion,$nombrePrincipal,$notasActuales,$nombreNota) {
        $noEjecuta=0;
        //verifica ppal en plan
        $principal=$this->buscarEspacioEnPlan($planEstudio,$bifurcacion[$nombrePrincipal]);
        if(is_array($principal))
        {
            //verifica ppal en notas actuales
            if(is_array($notasActuales))
            {
                foreach ($notasActuales as $nota) {
                    if($bifurcacion[$nombrePrincipal]==$nota['COD_ESPACIO'])
                    {
                        $noEjecuta=1;
                        break;
                    }
                }
            }else{}
        }else
            {
                $noEjecuta=1;
            }
        //si esta en plan y no hay notas crea arreglo de nota principal
        if($noEjecuta==0)
        {
            $notaBifurcacion=$this->remplazarDatosNota($bifurcacion['NOTA'],$principal);
            $homBifurcacion=$bifurcacion;
            unset($homBifurcacion['NOTA']);
            $homBifurcacion[$nombreNota]=$notaBifurcacion;
            return $homBifurcacion;
        }else
            {
                return '';
            }
        
    }
    
    /**
    * Funcion que organiza un arreglo de acuerdo a dos campos
    * @param type $arregloAOrdenar
    * @param type $campo
    * @param type $campo2
    * @param type $inverso
    * @return type
    */
    function ordenarListado ($arregloAOrdenar, $campo1, $campo2, $inverso = false) {
        //organiza el arreglo por el campo1
        $arregloRetorno=$this->ordenarArreglo($arregloAOrdenar, $campo1, $inverso);

        //crea un arreglo por cada valor diferente de campo1
            $a=0;
            $arregloInicial=array();
        foreach ($arregloRetorno as $key => $value) {
            $order=$arregloRetorno[$key][$campo1];
            if(isset($arregloRetorno[$key+1])){
                if ($order==$arregloRetorno[$key+1][$campo1])
            {
                $arregloInicial[$a][]=$arregloRetorno[$key];
            }
            else
                {
                    $arregloInicial[$a][]=$arregloRetorno[$key];
                    $a++;
                }
            }else{
                $arregloInicial[$a][]=$arregloRetorno[$key];
            }
        }
        //Cada arreglo de campo1 lo organiza por campo2
        $arregloFinal=array();
            foreach ($arregloInicial as $key => $value) {
                $arregloRetorno2=$this->ordenarArreglo($arregloInicial[$key], $campo2, $inverso);
            //Crea el arreglo final
            $arregloFinal=array_merge($arregloFinal,$arregloRetorno2);
        }
        return $arregloFinal;
    }

    /**
     * Funcion que permite ordenar un arreglo por el campo especificado en campo
     * @param type $arregloAOrdenar
     * @param type $campo
     * @param type $inverso
     * @return type
     */
    function ordenarArreglo($arregloAOrdenar,$campo,$inverso='false') {
        $posicion = array();
        $nuevaFila = array();
        //realiza un barrido por el arreglo creando un arreglo con los valores del campo a ordenar
        foreach ($arregloAOrdenar as $key => $fila) {
                $posicion[$key]  = $fila[$campo];
                $nuevaFila[$key] = $fila;
        }
        //organiza el nuevo arreglo de acuerdo a los valores del campo
        if ($inverso) {
            arsort($posicion);
        }
        else {
            asort($posicion);
        }
        //crea un arreglo ordenado por campo
        $arregloRetorno = array();
        foreach ($posicion as $key => $pos) {
            $arregloRetorno[] = $nuevaFila[$key];
        }
        return $arregloRetorno;
    }
    
    /**
     * Funcion que busca notas aprobadas dentro de un arreglo
     * @param type $arreglo
     * @param type $notaAprobatoria
     * @return type
     */
    function buscarNotasAprobadas($arreglo,$notaAprobatoria){
        
        $espaciosAprobados=array();
        $espaciosReprobados=array();
        if(is_array($arreglo)&&isset($arreglo[0]['COD_ESPACIO']))
        {
            foreach ($arreglo as $nota)
            {
                if((isset($nota['NOTA'])?$nota['NOTA']:'')>=$notaAprobatoria)
                {
                    $espaciosAprobados[]=$nota;
                }else
                    {
                        $espaciosReprobados[]=$nota;
                    }
            }
        }else
            {
                $resultado=array('aprobados'=>'','reprobados'=>'');
            }
        $resultado=array('aprobados'=>$espaciosAprobados,'reprobados'=>$espaciosReprobados);
        return $resultado;
    }
 
    /**
     * Busca dentro de los espacios aprobados los espacios de clasificación Electivo extrinseco
     * @param type $notasAnterioresAprobadas
     * @return type
     * Se ajusta para evitar mensaje por clasificacion del espacio 25/11/2014 Milton Parra
     */
    function buscarEspaciosElectivosExtrinsecos($notasAnterioresAprobadas){
        $espaciosEExtrinsecos='';
        if(is_array($notasAnterioresAprobadas))
        {
            foreach ($notasAnterioresAprobadas as $nota)
            {   
                if(is_array($nota) && isset($nota['CLASIFICACION']) && $nota['CLASIFICACION']==4)
                {
                    $espaciosEExtrinsecos[]=$nota;
                    
                }else
                    {
}
            }
        }else
            {
                
            }
        return $espaciosEExtrinsecos;
    }
        
    
}
?>
