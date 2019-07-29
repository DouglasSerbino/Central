<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_revivir_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada a las divisiones
	 *@return array: Array con la informacion de lo tipos de divisiones.
	*/
	function revivir_pedido($id_pedido, $id_proceso, $fecha)
	{
		$Consulta = 'update pedido set fecha_entrada = "'.date('Y-m-d').'",
				fecha_entrega = "'.$fecha.'",
				fecha_reale= "0000-00-00"
				where id_pedido = "'.$id_pedido.'"
				and id_proceso = "'.$id_proceso.'"
				';
		echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		
		return 'ok';
		
	}
}

/* Fin del archivo */