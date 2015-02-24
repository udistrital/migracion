<?php 
function encab_config() {
	$resta = 555;
	$sesnom="ingresook";
	
	Response.$Buffer = true;
	Response.$Expires = 60;
	Response.$Expiresabsolute = date("Y-n-d H:i:s",strtotime(" -$resta day"));
	header("Cache-Control: no-store, no-cache");
	header("Pragma: no-cache");
	/*Response.AddHeader "cache-control","private";
	Response.CacheControl = "post-check=0";
	response.CacheControl = "pre-check=0"; */
	server.$ScriptTimeout = 120;
		$id = @$_GET["id"];
		echo $id;
}
?>