<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pruebas_m extends CI_Model {

function __construct()
{
parent::__construct();
}

	function pruebas($Cliente = '', $Mes = '')
	{
		
		$Info = array();
		if('' != $Cliente)
		{
			
			
			$Consulta = 'select proc.proceso, cli.codigo_cliente, proc.nombre,
				ped.id_pedido, date_format(ped.fecha_reale, "%d") as dia
				from pedido ped, procesos proc, cliente cli
				where cli.codigo_cliente = "'.$Cliente.'"
				and ped.fecha_reale >= "2013-'.$Mes.'-01"
				and ped.fecha_reale <= "2013-'.$Mes.'-31"
				and cli.id_cliente = proc.id_cliente
				and ped.id_proceso = proc.id_proceso
				and cli.id_grupo = "1"
				group by ped.id_pedido, date_format(ped.fecha_reale, "%d")
				order by dia
				';
				
				//echo $Consulta.'<br>';
				$Resultado = $this->db->query($Consulta);
				$Informacion = $Resultado->result_array();
				$DiaTemp = $Informacion[0]['dia'];
				foreach($Informacion as $Datos)
				{
					if(!isset($Info[$Datos['dia']]['total']))
					{
						$Info[$Datos['dia']]['total'] = 0;
					}
					$Info[$Datos['dia']]['proceso'][$Datos['id_pedido']] = $Datos['codigo_cliente'].'-'.$Datos['proceso'].'   '.$Datos['nombre'];
					if($Datos['dia'] != $DiaTemp)
					{
						$Info[$Datos['dia']]['total'] = 0;
						$DiaTemp = $Datos['dia'];
					}
					$Info[$Datos['dia']]['total'] += 1;
				}
				
				return $Info;
		}
	}
}
/* Fin del archivo */