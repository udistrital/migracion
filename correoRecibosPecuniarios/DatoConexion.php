<?php

class DatoConexion{
	
	
	private $motorDB;
	private $direccionServidor;
	private $puerto;
    private $db;
    private $usuario;
    private $clave;

    
    function __construct(){
    	
		
    	
    }
    
    function setDatosConexion($nombre){
    	
    	
    	switch($nombre){
    		
    		case "oracle":
    			
    			$this->motorDB='pgsql';
    			$this->direccionServidor='10.20.0.105';
    			$this->puerto='5432';
    			$this->db="academica";
    			$this->usuario="QRY_ACESTMAT";
    			$this->clave="qry_acestmat@tst_sudd";   			
    			break;
    			
    		case "postgresql":
    			    			 
    			$this->motorDB="pgsql";
    			$this->direccionServidor="10.20.0.9";
    			$this->puerto="5432";
    			$this->db="reportepagos";
    			$this->usuario="reportepagos";
    			$this->clave="reportepagos";
    			break;
    		
    		
    	}
    	
    	return true;
    	
    	
    }    
    
    function getMotorDB(){
    	return $this->motorDB;
    }

    function getDireccionServidor(){
    	return $this->direccionServidor;
    }
    
    function getPuerto(){
    	return $this->puerto;
    }
    
    function getDb(){
    	return $this->db;
    }
    
    function getUsuario(){
    	return $this->usuario;
    }
    
    function getClave(){
    	return $this->clave;
    }
    
	
}



?>
