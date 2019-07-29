<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_consumo_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Se agrega en la base de datos el consumo para el usuario y pedido.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	function reportar($Id_Pedido)
	{
		
		//Se validara desde aca.
		$Materiales = array();
		
		$Bodega_uso = array();
		
		for($i = 0; $i < 6; $i++)
		{
			if('' != $this->input->post('material_'.$i))
			{
				$Material = $this->seguridad_m->mysql_seguro(
					$this->input->post('material_'.$i)
				);
				$Cantidad = $this->seguridad_m->mysql_seguro(
					$this->input->post('cantidad_'.$i)
				);
				$Reproceso = $this->seguridad_m->mysql_seguro(
					$this->input->post('reproceso_'.$i)
				);
				$Materiales[] = '(NULL, "'.$Id_Pedido.'", "'.$Material.'", "'.$Cantidad.'", "'.$Reproceso.'")';
				
				$Bodega_uso[] = array($Material, $Cantidad);
			}
		}
		
		if(0 < count($Materiales))
		{
			$Consulta = 'insert into pedido_material values '.implode(', ', $Materiales);
			$this->db->query($Consulta);
		}
		
		
		if(0 < count($Bodega_uso))
		{
			foreach($Bodega_uso as $Info)
			{
				$Consulta = '
					update inventario_bodega_9000
					set cantidad = cantidad - '.$Info[1].'
					where id_inventario_material = "'.$Info[0].'"
				';
				$this->db->query($Consulta);
			}
		}
		
	}
	
	
	/**
	 *Busca el detalle de los consumos realizados en para este pedido.
	 *@param string $Id_Pedido.
	 *@return array.
	*/
	function detalle($Id_Pedido)
	{
		
		$Consulta = '
			select inma.id_inventario_material, id_pedido_material, codigo_sap, nombre_material, tipo, cantidad,
			reproceso
			from pedido ped, pedido_material pema, inventario_material inma
			where ped.id_pedido = pema.id_pedido and ped.id_pedido = "'.$Id_Pedido.'"
			and pema.id_inventario_material = inma.id_inventario_material
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by id_pedido_material asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
	
}

/* Fin del archivo */