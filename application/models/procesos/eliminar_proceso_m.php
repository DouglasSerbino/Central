<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eliminar_proceso_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function desaparecer($Id_Proceso)
	{
		
		//Necesito saber los id_pedidos que pertenecen a este proceso para regresarlos
		$Consulta = '
			select id_pedido
			from pedido
			where id_proceso = "'.$Id_Proceso.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Pedidos = $Resultado->result_array();
		
		
		//Desaparicion del proceso
		$Consulta = '
			delete from procesos
			where id_proceso = "'.$Id_Proceso.'"
		';
		$this->db->query($Consulta);
		
		
		return $Pedidos;
		
	}
	
}

/* Fin del archivo */