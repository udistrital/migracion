<?php
	$encode='CODABAR';
	if(!isset($bar))
	{
	$bar= new BARCODE("CODABAR",$configuracion);
	
	}
	if($bar==false)
		die($bar->error());
	// OR $bar= new BARCODE("I2O5");

	$barnumber=$codigo;
	//$barnumber="200780";
	//$barnumber="801221905";
	//$barnumber="A40146B";
	//$barnumber="Code 128";
	//$barnumber="TEST8052";
	//$barnumber="TEST93";
	
	$bar->setSymblogy($encode);
	$bar->setHeight(50);
	//$bar->setFont("arial");
	$bar->setScale(1);
	$bar->setHexColor("#000000","#FFFFFF");

	/*$bar->setSymblogy("UPC-E");
	$bar->setHeight(50);
	$bar->setFont("arial");
	$bar->setScale(2);
	$bar->setHexColor("#000000","#FFFFFF");*/

	//OR
	//$bar->setColor(255,255,255)   RGB Color
	//$bar->setBGColor(0,0,0)   RGB Color

  	
	$return = $bar->genBarCode($barnumber,'png',$configuracion["raiz_documento"].$configuracion["documento"]."/codigo");
	if($return==false)
		$bar->error(true);
	
?>