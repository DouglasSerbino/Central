<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hoja_produccion_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
  /**
	 *Buscar el proceso
	 *@param string $proceso.
	 *@param string $cod_Cliente.
	 *@return array.
	*/
	function buscar_procesos($cod_cliente, $proceso)
	{
		
		$Procesos = array();
		
		//Establecemos la consulta para extraer la informacion
		//relacionada al proceso.
		$Consulta = '
			select id_pedido, fecha_reale
			from procesos proc, pedido ped, cliente cli
			where proc.id_proceso = ped.id_proceso
				and cli.codigo_cliente = "'.$cod_cliente.'"
				and proc.proceso = "'.$proceso.'"
				and cli.id_cliente = proc.id_cliente
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
				and fecha_reale > "2010-01-01"
			order by id_pedido desc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		//Verificamos si hay informacion que regresar.
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Procesos[$Fila['id_pedido']]['id_pedido'] = $Fila['id_pedido'];
				$Procesos[$Fila['id_pedido']]['fecha'] = $Fila['fecha_reale'];
				$Procesos[$Fila['id_pedido']]['id_pedido_sap'] = '';
				$Procesos[$Fila['id_pedido']]['sap'] = 'Pendiente';
			}
		}
		
		
		$Consulta = '
			select sapo.id_pedido, sapo.fecha, sapo.id_pedido_sap, sapo.sap
			from procesos proc, pedido ped, pedido_sap sapo, cliente cli
			where proc.id_proceso = ped.id_proceso
				and cli.codigo_cliente = "'.$cod_cliente.'"
				and proc.proceso = "'.$proceso.'"
				and ped.id_pedido = sapo.id_pedido
				and cli.id_cliente = proc.id_cliente
				and cli.id_cliente = sapo.id_cliente
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by id_pedido_sap desc
		';
		//echo $Consulta;
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		//Verificamos si hay informacion que regresar.
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Procesos[$Fila['id_pedido']]['id_pedido'] = $Fila['id_pedido'];
				$Procesos[$Fila['id_pedido']]['fecha'] = $Fila['fecha'];
				$Procesos[$Fila['id_pedido']]['id_pedido_sap'] = $Fila['id_pedido_sap'];
				$Procesos[$Fila['id_pedido']]['sap'] = $Fila['sap'];
			}
		}
		
		return $Procesos;
	}
}

/* Fin del archivo */