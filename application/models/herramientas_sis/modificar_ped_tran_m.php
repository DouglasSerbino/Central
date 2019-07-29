<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_ped_tran_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function mostrar_material($Codigo, $Orden)
	{
		//Consulta extraer la informacion del material solicitado.
		$Consulta = 'select mate.id_inventario_material, mate.nombre_material,
								tran.cantidad_solicitada, tran.detalle, tran.orden, tran.tipo
								from inventario_material mate, pedido_transito tran
								where mate.id_inventario_material = "'.$Codigo.'"
								and mate.id_inventario_material = tran.id_inventario_material
								and tran.id_grupo = "'.$this->session->userdata('id_grupo').'"
								and orden = "'.$Orden.'"';
		//echo $Consulta;
		//Ejecutamos la consulta.
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
	
	
	function modificar_pedido_tran($Orden_ant, $Orden, $Cantidad, $Tipo, $Id_inventario_material, $Detalle)
	{
		$Fecha = date('Y-m-d');
		//Consulta para almacenar la informacion.
		$Consulta = 'UPDATE pedido_transito set orden = "'.$Orden.'", 
																						cantidad_solicitada = "'.$Cantidad.'",
																						cantidad= "'.$Cantidad.'" ,
																						tipo = "'.$Tipo.'",
																						detalle = "'.$Detalle.'",
																						fecha_ingreso = "'.$Fecha.'"
																						where id_inventario_material = "'.$Id_inventario_material.'"
																						and orden = "'.$Orden_ant.'"';
		//echo $Consulta;
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		return 'ok';
		
	}
	
}
/* Fin del archivo */