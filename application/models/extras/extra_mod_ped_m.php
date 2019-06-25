<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_mod_ped_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Horas extras que se le programaron al usuario seleccionado.
	 
	 *@param $id_extra. Id de la extra que queremos modificar.
	 *@return array
	*/
	function mostrar_extras($id_extra)
	{
        $Consulta = '
									select id_extped, id_cliente, proceso, nombre, fecha_entrega
									from procesos proc, pedido ped, extra_pedido extped
									where proc.id_proceso = ped.id_proceso
									and ped.id_pedido = extped.id_pedido
									and id_extra = "'.$id_extra.'"
        ';
        
		$Resultado = $this->db->query($Consulta);
			
		return $Resultado->result_array();
	}
}