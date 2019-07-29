<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_Pedido_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada a las divisiones
	 *@return array: Array con la informacion de lo tipos de divisiones.
	*/
	function Ped_Secundario($Id_Pedido)
	{
		$Consulta = 'select id_ped_primario from pedido_pedido where id_ped_secundario = "'.$Id_Pedido.'"';
		$Resultado = $this->db->query($Consulta);
		$Id_Pedido = $Resultado->row_array();
		
		
		return $Id_Pedido['id_ped_primario'];
		
	}
}

/* Fin del archivo */