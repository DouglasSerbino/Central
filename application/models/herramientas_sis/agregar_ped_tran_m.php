<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar_ped_tran_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function agregar_pedido_tran($Orden, $Cantidad, $Tipo, $Id_inventario_material, $Detalle)
	{
		$Fecha = date('Y-m-d');
		//Consulta para almacenar la informacion.
		$Consulta = 'INSERT INTO pedido_transito values(null, "'.$Orden.'", 
																										"'.$Cantidad.'",
																										"'.$Cantidad.'" ,
																										"'.$Id_inventario_material.'",
																										"'.$Tipo.'",
																										"'.$Detalle.'",
																										"'.$Fecha.'",
																										"n",
																										"'.$this->session->userdata('id_grupo').'"
																										)';
		
		
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		
		$Consulta = 'select * from pedido_transito_solicitud where id_inventario_material = "'.$Id_inventario_material.'"';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado-> num_rows())
		{
			$Consulta = 'update pedido_transito_solicitud set activo = "n" where id_inventario_material = "'.$Id_inventario_material.'"';
			$this->db->query($Consulta);
		}
		
		$Consulta = 'select * from pedido_material_solicitud where id_inventario_material = "'.$Id_inventario_material.'"';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado-> num_rows())
		{
			$Consulta = 'update pedido_material_solicitud set activo = "n" where id_inventario_material = "'.$Id_inventario_material.'"';
			$this->db->query($Consulta);
		}
		
		
		return 'ok';
		
	}
	
	
	function mostrar_material($Codigo)
	{
		//Consulta extraer la informacion del material solicitado.
		$Consulta = 'select * from inventario_material
								where id_inventario_material = "'.$Codigo.'"
								and id_grupo = "'.$this->session->userdata('id_grupo').'"';
		//echo $Consulta;
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->row_array();
		}
		else
		{
			return array();
		}
	}
}
/* Fin del archivo */