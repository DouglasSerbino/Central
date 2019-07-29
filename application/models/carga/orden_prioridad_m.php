<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orden_prioridad_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca los trabajos en carga segun los datos del usuario.
	 *@param string $Fechas.
	 *@param string $Puesto.
	 *@param string $Id_Cliente.
	 *@param string $Trabajo.
	 *@return array.
	*/
	function ordena()
	{
		
		$Pedidos = json_decode($this->input->post('prioridades'));
		
		foreach($Pedidos as $Index => $Id_Pedido)
		{
			$Id_Pedido += 0;
			if(0 < $Id_Pedido)
			{
				
				$Consulta = '
					update pedido
					set ped_prioridad = "'.$Index.'"
					where id_pedido = "'.$Id_Pedido.'"
				';
				$this->db->query($Consulta);
				
			}
			
		}
		
		return true;
		
	}
	
}

/* Fin del archivo */