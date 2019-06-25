<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Historial_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function listado($Id_Pedido)
	{
		
		$Consulta = '
			select fecha, usuario, id_revision_pedido
			from revision_pedido hoja, usuario usu
			where hoja.id_usuario = usu.id_usuario and id_pedido = "'.$Id_Pedido.'"
			order by id_revision_pedido asc
		';
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
	function detalle($Id_Pedido, $Id_Hoja)
	{
		
		$Consulta = '
			select fecha, usu.nombre, revision, observacion, usu.id_dpto
			from revision_pedido hoja, usuario usu
			where hoja.id_usuario = usu.id_usuario and id_pedido = "'.$Id_Pedido.'"
			and id_revision_pedido = "'.$Id_Hoja.'"
		';
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->row_array();
		
	}
	
	
}

/* Fin del archivo */