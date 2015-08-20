<?php

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf_sab_notas/pdf/fpdf.php");

//06/11/2012 Milton Parra: Se ajustan filas para cuando el nombre de un espacio ocupa mas de una

class PDF extends FPDF
{
	function Header()
	{
            require_once("clase/config.class.php");
            $esta_configuracion=new config();
            $configuracion=$esta_configuracion->variable();

            $title='UNIVERSIDAD DISTRITAL';
            $title1=utf8_decode('FRANCISCO JOSÉ DE CALDAS');
            $title2=utf8_decode('SECRETARÍA ACADEMICA');
            $title3='CERTIFICADO DE NOTAS INTERNO';
            $title4=utf8_decode('"DOCUMENTO NO VÁLIDO PARA TRÁMITES"');
            //Arial bold 15
            $this->SetFont('Courier','',12);
            //LOGOTIPO
            $this->Image($configuracion['raiz_documento'].$configuracion['grafico']."/EscudoUDistrital.png",10,5,18);
            //Calculamos ancho y posici�n del t�tulo.
            $w=$this->GetStringWidth($title)+32;
            $w1=$this->GetStringWidth($title1);
            $w2=$this->GetStringWidth($title2)+32;
            $w3=$this->GetStringWidth($title3)+32;
            $w4=$this->GetStringWidth($title4)+32;
            $this->SetX((210-$w)/2);
            $this->SetX((210-$w1)/2);
            //$this->SetX((210-$w2)/2);
            //Colores de los bordes, fondo y texto
            $this->SetDrawColor(250);
            $this->SetFillColor(240);
            $this->SetTextColor(0);
            //Ancho del borde (1 mm)
            $this->SetLineWidth(1);
            //T�tulo
            //$this->Cell($w,25,$title,0,1,'C',0);
            $this->SetFont('Arial','B',15);
            $this->Cell($w,0,$title.' '.$title1,0,1,'C',0);
            $this->Cell(210,10,$title2,0,1,'C',0);
            $this->SetFont('Arial','I',10);
            $this->Cell(210,0,$title3,0,1,'C',0);
            $this->Cell(210,10,$title4,0,1,'C',0);
            //Salto de l�nea
            $this->Ln(2);
	}

function FancyTable($header,$data,$datos)
{
        $ño=utf8_decode("ño");
	//Colores, ancho de l�nea y fuente en negrita
        $this->SetFillColor(0,0,0);
	$this->SetTextColor(255);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(0);
	$this->SetFont('Arial','','8');

        //Datos estudiante
        $h=array(100,60,40);
        $this->Ln();
        //Restauraci�n de colores y fuentes
	$this->SetFillColor(0,0,0);
	$this->SetTextColor(0);
	$this->SetFont('');
    
        $this->Cell($h[0],6,'NOMBRE: '.$datos[0][0],'',0,'L',$fill);
        $this->Cell($h[1],6,utf8_decode('IDENTIFICACIÓN: ').$datos[0][1],'',0,'L',$fill);
        $this->Cell($h[2],6,'PROMEDIO ACUMULADO: '.$datos[0][2],'',0,'L',$fill);
        $this->Ln();
        $this->Cell($h[0],6,'CARRERA: '.$datos[1][0],'',0,'L',$fill);
        $this->Cell($h[1],6,utf8_decode('CÓDIGO: ').$datos[1][1],'',0,'L',$fill);
        $this->Cell($h[2],6,'FECHA: '.$datos[1][2],'',0,'L',$fill);
        $this->Ln();

        //Restauraci�n de colores y fuentes
	$this->SetFillColor(250,250,250);
	$this->SetTextColor(0);
	$this->SetFont('');
	$this->Ln();
        //Cabecera
	$w=array(20,85,18,10,10,10,43);
	for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],0,0,'C',1);
	$this->Ln(7);

	
	//Datos
	$fill=0;
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'',0,'C',$fill);
		$this->Cell($w[1],6,$row[1],'',0,'L',$fill);
		$this->Cell($w[2],6,$row[2],'',0,'C',$fill);
		$this->Cell($w[3],6,$row[3],'',0,'C',$fill);
                $this->Cell($w[4],6,$row[4],'',0,'C',$fill);
		$this->Cell($w[5],6,$row[5],'',0,'C',$fill);
                $this->Cell($w[6],6,$row[6],'',0,'C',$fill);
                $this->Ln(5);
		$fill=!$fill;

	}
        $this->Ln();
        $this->SetFont('Arial','','7');
        $this->Cell(125);
	$this->Cell('','','Dise'.$ño.': Oficina Asesora de Sistemas');
        $this->Ln(5);
        $this->SetFont('Arial','B','10');
        $this->Cell(70);
	$this->Cell('','','FIN CERTIFICADO DE NOTAS');
        $this->Ln(5);
        $this->Cell(55);
	$this->Cell('','',utf8_decode('DOCUMENTO NO VÁLIDO PARA TRÁMITES'));
}

function FancyTableCred($header,$data,$datos, $html, $creditosCursados,$vistaRangos)
{
	$fill=$OB=$OC=$EI=$EE=$CP=0;
//    require_once("clase/config.class.php");
//    $esta_configuracion=new config();
//    $configuracion=$esta_configuracion->variable();
	//Colores, ancho de l�nea y fuente en negrita
        $this->SetFillColor(0,0,0);
	$this->SetTextColor(255);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(0);
	$this->SetFont('Arial','','8');

        //Datos estudiante
        $h=array(100,50,50);
        $this->Ln(5);
        //Restauraci�n de colores y fuentes
	$this->SetFillColor(0,0,0);
	$this->SetTextColor(0);
	$this->SetFont('');

            $this->Cell($h[0],6,'NOMBRE: '.utf8_decode($datos[0][0]),'',0,'L',$fill);
            $this->Cell($h[1],6,utf8_decode('IDENTIFICACIÓN: ').$datos[0][1],'',0,'L',$fill);
            $this->Cell($h[2],6,'PROMEDIO ACUMULADO: '.$datos[0][2],'',0,'L',$fill);
        $this->Ln();
            $this->Cell($h[0],6,utf8_decode('CRÉDITOS CURSADOS: ').$creditosCursados,'',0,'L',$fill);
            $this->Cell($h[1],6,utf8_decode('CÓDIGO: ').$datos[1][1],'',0,'L',$fill);
            $this->Cell($h[2],6,'FECHA: '.$datos[1][2],'',0,'L',$fill);
        $this->Ln();
            $this->Cell($h[0],6,'CARRERA: '.utf8_decode($datos[1][0]),'',0,'L',$fill);
            $this->Cell($h[1],6,'PLAN DE ESTUDIOS: '.$datos[2][0],'',0,'L',$fill);

    //Restauraci�n de colores y fuentes
	$this->SetFillColor(215,215,215);
	$this->SetTextColor(0);
	$this->SetFont('');
	$this->Ln(10);
    //Cabecera
	$w=array(14,60,15,12,10,10,10,10,10,10,30);
	for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],0,0,'C',1);
	$this->Ln(5);
        //Restauraci�n de colores y fuentes
	$this->SetFillColor(250,250,250);
	$this->SetTextColor(0);
	$this->SetFont('');

	//Datos
	foreach($data as $row)
	{
            if((isset($row[3])?$row[3]:'')== 'OB')
            {
                $OB+=1;
            }else if((isset($row[3])?$row[3]:'')== 'OC'){
                $OC+=1;
            }else if((isset($row[3])?$row[3]:'')== 'EI'){
                $EI+=1;
            }else if((isset($row[3])?$row[3]:'')== 'EE'){
                $EE+=1;
            }

            if(strstr($row[1],'XYZ'))//Los nombres de nivel comienzan con XYZ
                {
                    $row[1]=substr($row[1],3);//elimina XYZ al nombre del nivel
                    $this->SetFont('Arial','B','10');
                    $this->Ln(5);
                    $this->Cell(80);
                    $this->Cell('','',$row[1]);
                    $this->Ln(5);
                    $fill=!$fill;
                }else
                    {
                        if (strlen($row[1])>33)
                        {
                            $alto=3; $this->Ln(2);
                        }else
                            {
                                $alto=6;
                            }
                        $this->SetFont('Arial','','8');
                        $this->Cell($w[0],6,$row[0],'',0,'C',$fill);
                        $y=$this->GetY();
                        $this->MultiCell($w[1],$alto,$row[1],'','L',$fill);$this->SetXY(84,$y);
                        $this->Cell($w[2],6,$row[2],'',0,'C',$fill);
                        $this->Cell($w[3],6,$row[3],'',0,'C',$fill);
                        $this->Cell($w[4],6,$row[4],'',0,'C',$fill);
                        $this->Cell($w[5],6,$row[5],'',0,'C',$fill);
                        $this->Cell($w[6],6,$row[6],'',0,'C',$fill);
                        $this->Cell($w[7],6,$row[7],'',0,'C',$fill);
                        $this->Cell($w[8],6,$row[8],'',0,'C',$fill);
                        $this->Cell($w[9],6,$row[9],'',0,'C',$fill);
                        $this->Cell($w[10],6,(isset($row[10])?$row[10]:''),'',0,'C',$fill);
                        $this->Ln(5);
                        $fill=!$fill;
                    }
	}
        $this->Ln();
        $this->SetFont('Arial','','7');
        $this->Cell(125);
            $this->Cell('','',utf8_decode('Diseño: Oficina Asesora de Sistemas'));
        $this->Ln(5);
        $this->SetFont('Arial','B','10');
        $this->Cell(70);
            $this->Cell('','','FIN CERTIFICADO DE NOTAS');
        $this->Ln(5);
        $this->Cell(55);
            $this->Cell('','',utf8_decode('DOCUMENTO NO VÁLIDO PARA TRÁMITES'));

    if (is_array($vistaRangos))
        {
          if($vistaRangos[0][0]=="0")
              {
                  $nombreEspacio=strtr(strtoupper($vistaRangos[0][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                  $this->Ln(5);
                  $this->Cell(40);
                  $this->Cell('','',utf8_decode($nombreEspacio));
              }else
                  {
                      $nombreEspacio=strtr(strtoupper($vistaRangos[0][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                      $this->Ln(5);
                      $this->Cell(70);
                      $this->Cell('','',utf8_decode($nombreEspacio));
                  }

          $this->Ln(5);
          $this->SetFont('Arial','B','10');

          $t=array(25,25,25,25,50);

          $this->Cell(20);
          $this->Cell($t[0],6,utf8_decode($vistaRangos[1][0]),1,0,'C',$fill);
          $this->Cell($t[1],6,utf8_decode($vistaRangos[1][1]),1,0,'C',$fill);
          $this->Cell($t[2],6,utf8_decode($vistaRangos[1][2]),1,0,'C',$fill);
          $this->Cell($t[3],6,utf8_decode($vistaRangos[1][3]),1,0,'C',$fill);
          $this->Cell($t[4],6,utf8_decode($vistaRangos[1][4]),1,0,'C',$fill);
          $this->Ln(5);

          $this->SetFont('Arial','','10');

          $fill=true;

          $colores[2][0]=84;
          $colores[2][1]=113;
          $colores[2][2]=172;
          $colores[3][0]=107;
          $colores[3][1]=143;
          $colores[3][2]=212;
          $colores[4][0]=35;
          $colores[4][1]=131;
          $colores[4][2]=135;
          $colores[5][0]=97;
          $colores[5][1]=183;
          $colores[5][2]=188;
          $colores[6][0]=177;
          $colores[6][1]=35;
          $colores[6][2]=45;

          for($p=2;$p<count($vistaRangos);$p++)
          {
              if($vistaRangos[$p][4]!="0")
                  {
                      $celda=($vistaRangos[$p][4]*$t[4])/100;
                      $celdaRestante=$t[4]-$celda;
                  }else
                      {
                          $celda=$t[4];
                      }

              $this->SetFillColor(250, 250, 250);
              $this->Cell(20);
              $this->Cell($t[0],6,utf8_decode($vistaRangos[$p][0]),1,0,'C',$fill);
              $this->Cell($t[1],6,utf8_decode($vistaRangos[$p][1]),1,0,'C',$fill);
              $this->Cell($t[2],6,utf8_decode($vistaRangos[$p][2]),1,0,'C',$fill);
              $this->Cell($t[3],6,utf8_decode($vistaRangos[$p][3]),1,0,'C',$fill);
              if($vistaRangos[$p][4]!=0)
                  {
                      $this->SetFillColor($colores[$p][0], $colores[$p][1], $colores[$p][2]);
                      $this->Cell($celda,6,utf8_decode($vistaRangos[$p][4])."%",1,0,'C',$fill);
                      $this->SetFillColor(250, 250, 250);
                      $this->Cell($celdaRestante,6,"", 1, 0, 'C', $fill);
                  }else
                      {
                          $this->Cell($celda,6,utf8_decode($vistaRangos[$p][4])."%",1,0,'C',$fill);
                      }

              $this->Ln(5);

          }

    }
    else
    {

    }

        $this->Ln(10);
        $this->Cell(50);
        $this->SetFont('Arial','B','8');
            $this->Cell('','',utf8_decode('DESCRIPCIÓN CLASIFICACIÓN DE ESPACIOS ACADEMICOS'),'','C');

        $this->Ln(5);
        $this->Cell(55);

        $this->SetFillColor(250,250,250);

        $m=array(30,60);
            $this->Cell($m[0],6,'ABREVIATURA',0,0,'C',1);
            $this->Cell($m[1],6,utf8_decode('CLASIFICACIÓN'),0,0,'C',1);
        $this->Ln(5);
       
        $suma_espacios=$OB+$OC+$EI+$EE;
        $clasif=array($OB,$OC,$EI,$EE);
//        var_dump($clasif);
//        echo "<br>Suma: ".$suma_espacios;
//        exit;
	//Datos
	$fill=0;
        
        for($j=0;$j<=count($html);$j++)
        {
                $this->Cell(55);
		$this->Cell($m[0],6,(isset($html[$j][0])?$html[$j][0]:''),'',0,'C',$fill);
                $this->Cell($m[1],6,utf8_decode((isset($html[$j][1])?$html[$j][1]:'')),'',0,'C',$fill);
                $this->Ln(5);
                $fill=!$fill;
	}

        $this->Ln(5);
        $this->Cell(43);
        $this->SetFont('Arial','B','8');
            $this->Cell('','',utf8_decode('PROMEDIO ACUMULADO (Articulo 8, Resolución 035 de Septiembre 19 de 2006)'),'','C');
        $this->Ln(5);
        $this->Cell(20);
            $this->Cell('','',utf8_decode('Para efectos del cálculo del promedio acumulado, éste se obtiene multiplicando la calificación obtenida en '),'','J');
        $this->Ln(5);
        $this->Cell(20);
            $this->Cell('','',utf8_decode('cada Espacio Académico cursado por el estudiante por su correspondiente número de créditos académicos; '),'','J');
        $this->Ln(5);
        $this->Cell(20);
            $this->Cell('','',utf8_decode('posteriormente se calcula la sumatoria de estos resultados la cual se dividirá por el número total de créditos'),'','J');
        $this->Ln(5);
        $this->Cell(20);
            $this->Cell('','',utf8_decode('académicos cursados hasta la fecha.'),'','J');

        if($datos[0][2]=='**')
        {
                $this->Ln(10);
                $this->Cell(10);
                $this->SetFont('Arial','B','8');
                    $this->Cell('','',$datos[0][2].utf8_decode(' El promedio no puede ser calculado por inconsistencias en los datos. Por favor comuníquese con su Proyecto Curricular'),'','C');
        }

}
    
/*	function Footer($firma="")
	{
		//Posici�n a 1,5 cm del final
		$this->SetY(-55);
		//Arial it�lica 8
		
    	$this->SetLineWidth(0.5);
    	$this->Line(72,260,139,260);
		$this->Ln(15);
    	$this->Cell(0,0,$firma,0,1,'C');
		$this->Ln(10);
        $this->SetFont('Arial','B',8);
		$this->Cell(0,0,'Secretario(a) Academico',0,0,'C');
		$this->SetFont('Arial','I',8);
		//Color del texto en gris
		$this->SetTextColor(128);
		//N�mero de p�gina
		$this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
	}*/

function FancyTableHoras($header,$data,$datos)
{
        $fill=0;
        $this->SetFillColor(0,0,0);
	$this->SetTextColor(255);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(0);
	$this->SetFont('Arial','','8');

        //Datos estudiante
        $h=array(100,50,50);
        $this->Ln(5);
        //Restauraci�n de colores y fuentes
	$this->SetFillColor(0,0,0);
	$this->SetTextColor(0);
	$this->SetFont('');

            $this->Cell($h[0],6,'NOMBRE: '.utf8_decode($datos[0][0]),'',0,'L',$fill);
        $this->Cell($h[1],6,utf8_decode('IDENTIFICACIÓN: ').$datos[0][1],'',0,'L',$fill);
            $this->Cell($h[2],6,'PROMEDIO ACUMULADO: '.$datos[0][2],'',0,'L',$fill);
        $this->Ln();
        $this->Cell($h[0],6,utf8_decode('CÓDIGO: ').$datos[1][1],'',0,'L',$fill);
            $this->Cell($h[1],6,'FECHA: '.$datos[1][2],'',0,'L',$fill);
        $this->Ln();
            $this->Cell($h[0],6,'CARRERA: '.utf8_decode($datos[1][0]),'',0,'L',$fill);

        //Restauraci�n de colores y fuentes
	$this->SetFillColor(215,215,215);
	$this->SetTextColor(0);
	$this->SetFont('');
	$this->Ln(10);
        //Cabecera
	$w=array(14,90,10,10,10,10,10,30);
	for($i=0;$i<count($header);$i++)
        {
            $this->Cell($w[$i],7,$header[$i],0,0,'C',1);
            
        }
        $this->Ln(10);
        //Restauraci�n de colores y fuentes
	$this->SetFillColor(250,250,250);
	$this->SetTextColor(0);
	

	//Datos
	$fill=0;
	foreach($data as $row)
	{
            if(strstr((isset($row[1])?$row[1]:''),'XYZ'))//Los nombres de nivel comienzan con XYZ
                {
                    $row[1]=substr($row[1],3);//elimina XYZ al nombre del nivel
                    $this->SetFont('Arial','B','10');
                    $this->Ln(5);
                    $this->Cell(80);
                    $this->Cell('','',$row[1]);
                    $this->Ln(5);
                    $fill=!$fill;
                }else
                    {
                        $this->SetFont('Arial','','8');
                        $this->Cell((isset($w[0])?$w[0]:''),6,(isset($row[0])?$row[0]:''),'',0,'C',$fill);
                        $this->Cell((isset($w[1])?$w[1]:''),6,(isset($row[1])?$row[1]:''),'',0,'L',$fill);
                        $this->Cell((isset($w[2])?$w[2]:''),6,(isset($row[2])?$row[2]:''),'',0,'C',$fill);
                        $this->Cell((isset($w[3])?$w[3]:''),6,(isset($row[3])?$row[3]:''),'',0,'C',$fill);
                        $this->Cell((isset($w[4])?$w[4]:''),6,(isset($row[4])?$row[4]:''),'',0,'C',$fill);
                        $this->Cell((isset($w[5])?$w[5]:''),6,(isset($row[5])?$row[5]:''),'',0,'C',$fill);
                        $this->Cell((isset($w[6])?$w[6]:''),6,(isset($row[6])?$row[6]:''),'',0,'C',$fill);
                        $this->Cell((isset($w[7])?$w[7]:''),6,(isset($row[7])?$row[7]:''),'',0,'C',$fill);
                        $this->Ln(5);
                        $fill=!$fill;
                    }
        }

        $this->Ln();
        $this->SetFont('Arial','','7');
        $this->Cell(125);
            $this->Cell('','',utf8_decode('Diseño: Oficina Asesora de Sistemas'));
        $this->Ln(5);
        $this->SetFont('Arial','B','10');
        $this->Cell(70);
            $this->Cell('','','FIN CERTIFICADO DE NOTAS');
        $this->Ln(5);
        $this->Cell(55);
            $this->Cell('','',utf8_decode('DOCUMENTO NO VÁLIDO PARA TRÁMITES'));

    }

}



?>