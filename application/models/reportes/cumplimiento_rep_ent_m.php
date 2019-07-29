<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cumplimiento_rep_ent_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Se define la consulta para poder buscar todos los pedidos
	 *que esten atrasados, a tiempo o que sean reprocesos.
	 *@param string $fmanho1.
	 *@param string $fmmes1.
	 *@param string $cod_cliente.
	 *@return array.
	*/
	function busquedad_pedido($tipo, $anho, $mes, $cod_cliente)
	{
		
		$SQL = '';
		if($cod_cliente != 'gen')
		{
			$SQL = ' and cli.codigo_cliente = "'.$cod_cliente.'"';
		}
		
		switch ($tipo)
		{
    case 'tie':
			$SQL .= ' and ped.fecha_reale <= fecha_entrega and ped.fecha_reale != "0000-00-00"';
			break;
    case 'atr':
			$SQL.= ' and ped.fecha_reale > fecha_entrega and ped.fecha_reale != "0000-00-00"';
			break;
    case 'rep':
			$SQL.= ' and ped.id_tipo_trabajo = "4" and ped.fecha_reale != "0000-00-00"';
			break;
		case 'tot':
			$SQL .= ' and ped.fecha_reale != "0000-00-00"';
			break;
		case '':
			return array();
		}
		
		$Consulta = 'select proc.proceso, proc.nombre, ped.fecha_entrada, ped.id_tipo_trabajo,
			ped.fecha_entrega, ped.fecha_reale, ped.id_pedido, cli.codigo_cliente
			from procesos proc, pedido ped, cliente cli
			where proc.id_proceso = ped.id_proceso
			and cli.id_cliente = proc.id_cliente
			and fecha_entrega >= "'.$anho.'-'.$mes.'-01"
			and fecha_entrega <= "'.$anho.'-'.$mes.'-31"
			'.$SQL.'
			and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by ped.fecha_entrega asc';
			
			//echo $Consulta.'<br><br>';
			$Resultado= $this->db->query($Consulta);
			if(0 < $Resultado->num_rows())
			{
				return $Resultado->result_array();
			}
			else
			{
				return array();
			}
	}
}

/* Fin del archivo */