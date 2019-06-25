<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bodega_uso_m extends CI_Model {
	
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
	function listar(
		$Codigo,
		$Proveedor,
		$Cantidad,
		$Equipo
	)
	{
		
		$Consulta = '
			select mate.id_inventario_material, codigo_sap, valor, cantidad_unidad, tipo,
			nombre_material, numero_individual, numero_cajas, bode.cantidad
			from inventario_material mate, inventario_bodega_9000 bode
			where mate.id_inventario_material = bode.id_inventario_material
			order by codigo_sap+0 asc;
		';
		
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			//Regresamos un array con la informacion.
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
		
	}
	
	
	
	function detalle(
		$Id_Inventario_Material,
		$Mes,
		$Anho
	)
	{
		
		$Consulta = '
			select codigo_cliente, proceso, proc.nombre, fecha_reale, cantidad
			from cliente clie, procesos proc, pedido ped, pedido_material pema,
			inventario_material mate
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = pema.id_pedido and pema.id_inventario_material = mate.id_inventario_material
			and fecha_reale >= "'.$Anho.'-'.$Mes.'-01" and fecha_reale <= "'.$Anho.'-'.$Mes.'-31"
			and mate.id_inventario_material = "'.$Id_Inventario_Material.'"
			order by fecha_reale asc;
		';
		
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			//Regresamos un array con la informacion.
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
		
	}
	
	
}

/* Fin del archivo */