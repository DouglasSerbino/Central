<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lsol_pedido_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function info_solicitud()
	{
		//Consulta para insertar el usuario
		$Consulta = '
			select mat.id_inventario_material, mat.nombre_material, pedtra.fecha
			from pedido_transito_solicitud pedtra, inventario_material mat
			where pedtra.activo = "s"
			and pedtra.id_inventario_material = mat.id_inventario_material
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
	
	
	function info_solicitud_material()
	{
		//Consulta para insertar el usuario
		$Consulta = '
			select mat.id_inventario_material,  pedmat.tipo, mat.nombre_material, pedmat.fecha,
			usu.usuario, pedmat.observaciones, pedmat.cantidad, mat.codigo_sap, pedmat.id_solicitud
			from pedido_material_solicitud pedmat, inventario_material mat, usuario usu
			where pedmat.activo = "s"
			and usu.id_usuario = pedmat.id_usuario
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
	
	
	function eliminar_solicitud($Id_material, $Id_solicitud)
	{
		//Consulta para insertar el usuario
		$Consulta = '
			update pedido_material_solicitud set activo = "d",
			deshabilitado = "Se deshabilita porque no aplica"
			where id_inventario_material = "'.$Id_material.'"
			and id_solicitud = "'.$Id_solicitud.'"
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		if($Resultado)
		{
			return 'ok';
		}
		else
		{
			return 'error';
		}
	}
}

/* Fin del archivo */