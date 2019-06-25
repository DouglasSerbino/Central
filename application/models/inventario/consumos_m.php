<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consumos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos los materiales que correspondan a los filtros recibidos.
	 *@param string $Codigo.
	 *@param string $Proveedor.
	 *@param string $Cantidad.
	 *@param string $Equipo.
	 *@return array.
	*/
	function ver_consumos(
		$Pais,
		$Mes,
		$Anho
	)
	{
		

		$Consumos = array('Consolidado' => array(), 'Detalle' => array());

		$Consulta = '
			select codigo_sap, nombre_material, sum(cantidad) as total
			from pedido ped, pedido_material pema, inventario_material mate
			where ped.id_pedido = pema.id_pedido and mat_pais = "'.$Pais.'"
			and pema.id_inventario_material = mate.id_inventario_material
			and fecha_reale >= "'.$Anho.'-'.$Mes.'-01" and fecha_reale <= "'.$Anho.'-'.$Mes.'-31"
			group by codigo_sap
			order by codigo_sap asc
		';
		
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			//Regresamos un array con la informacion.
			$Consumos['Consolidado'] = $Resultado->result_array();
		}





		$Consulta = '
			select codigo_cliente, proceso, proc.nombre, pema.cantidad, nombre_material,
			codigo_sap, fecha_reale
			from cliente clie, procesos proc, pedido ped, pedido_material pema, inventario_material mate
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = pema.id_pedido and mat_pais = "'.$Pais.'"
			and pema.id_inventario_material = mate.id_inventario_material
			and fecha_reale >= "'.$Anho.'-'.$Mes.'-01" and fecha_reale <= "'.$Anho.'-'.$Mes.'-31"
			order by ped.id_pedido asc, codigo_sap asc
		';
		
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			//Regresamos un array con la informacion.
			$Consumos['Detalle'] = $Resultado->result_array();
		}
		
		return $Consumos;
		
	}
	
	
}

/* Fin del archivo */