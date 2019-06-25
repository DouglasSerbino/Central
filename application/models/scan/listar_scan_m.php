<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listar_scan_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca los archivos adjuntos pertenecientes al pedido
	 *@param string $Id_Proceso.
	 *@param string $Id_Pedido.
	 *@return array
	*/
	function listar($Id_Pedido)
	{
		
		$Consulta = '
			select id_pedido_adjuntos as id_pa, url, nombre_adjunto, tipo_adjunto
			from cliente clie, procesos proc, pedido ped, pedido_adjuntos pead
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = pead.id_pedido and pead.id_pedido = "'.$Id_Pedido.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Adjuntos = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Adjuntos[$Fila['id_pa']] = '{';
			$Adjuntos[$Fila['id_pa']] .= '"i":"'.$Fila['id_pa'].'",';
			$Adjuntos[$Fila['id_pa']] .= '"n":"'.$Fila['nombre_adjunto'].'",';
			$Adjuntos[$Fila['id_pa']] .= '"t":"'.$Fila['tipo_adjunto'].'",';
			$Adjuntos[$Fila['id_pa']] .= '"url":"'.$Fila['url'].'"';
			$Adjuntos[$Fila['id_pa']] .= '}';
		}
		
		
		return implode(',', $Adjuntos);
		
	}
}

/* Fin del archivo */