<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sol_material_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function guardar_solicitud($info)
	{
		$SQL = '';
		foreach($info as $a => $Datos)
		{
			if($a > 1)
			{
				$SQL .= '"'.$Datos.'", ';
			}
			
			//echo $Datos;
		}
		
		$Consulta = '
			insert into pedido_material_solicitud values(
				NULL,
				"'.$this->session->userdata('id_usuario').'",
				'.$SQL.'
				"'.date('Y-m-d').'",
				"s",
				""
			)
		';
		//echo $Consulta;
		
		$this->db->query($Consulta);
		
		return 'ok';
		
	}
	
	
	function SolMaterialOperador()
	{
		//Consulta para insertar el usuario
		$Consulta = '
			select pedmat.tipo, mat.nombre_material, pedmat.fecha,
			pedmat.activo, pedmat.id_usuario, usu.usuario, pedmat.observaciones,
			pedmat.cantidad, mat.codigo_sap, pedmat.id_solicitud, pedmat.deshabilitado
			from pedido_material_solicitud pedmat, inventario_material mat, usuario usu
			where usu.id_usuario = pedmat.id_usuario
			and pedmat.id_inventario_material = mat.id_inventario_material
		';
		
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
	
	
}

/* Fin del archivo */