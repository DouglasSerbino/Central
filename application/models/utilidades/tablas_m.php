<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tablas_m extends CI_Model {
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Lista las tablas de la base de datos.
	 *@param nada.
	 *@return array.
	*/
	function listar()
	{
		
		$Consulta = '
			SELECT TABLE_NAME as tabla
			FROM INFORMATION_SCHEMA.TABLES
			where table_schema = "corporativo" and table_comment like "%explorable%"
		';
		
		$Resultados = $this->db->query($Consulta);
		
		$Tablas = array();
		
		foreach($Resultados->result_array() as $Fila)
		{
			$Tablas[$Fila['tabla']] = true;
		}
		
		return $Tablas;
		
	}
	
}


/* Fin del archivo */
