<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listado_coti_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function listado()
	{
		$Consulta = 'select proc.proceso, clie.codigo_cliente, ped.id_pedido, peds.sap
							from pedido_sap peds, pedido ped, procesos proc, cliente clie, pedido_usuario usu
							where actualizar = "s"
							and proc.id_proceso = ped.id_proceso
							and clie.id_cliente = proc.id_cliente
							and peds.id_pedido = ped.id_pedido
							and peds.id_cliente = clie.id_cliente
							and usu.id_pedido = ped.id_pedido
							and usu.id_usuario = 27';
		$Resultado = $this->db->query($Consulta);				
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
	public function eliminar($Id_Pedido)
	{
		$Consulta = 'update pedido_sap set actualizar="n" where id_pedido = "'.$Id_Pedido.'"';
		$Resultado = $this->db->query($Consulta);				
		return 'ok';
	}
}

/* Fin del archivo */