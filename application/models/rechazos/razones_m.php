<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Razones_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca las razones mas comunes por las que se rechaza un trabajo.
	 *@return array.
	*/
	function listado()
	{
		
		$Consulta = '
			select *
			from rechazo_razones
			order by rechazo_razon asc
		';
		
		$Result = $this->db->query($Consulta);
		
		return $Result->result_array();
		
	}
	
}

/* Fin del archivo */