<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Materiales_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Todos los materiales recibidos que corresponden al grupo que visualiza la pagina.
	 *@return array.
	*/
	function recibidos($Activos)
	{
		
		$Condicion = '';
		if('' != $Activos)
		{
			$Condicion =' and activo = "'.$Activos.'"';
		}
		
		$Consulta = '
			select material_recibido, id_material_recibido_grupo
			from material_recibido mate, material_recibido_grupo grup
			where mate.id_material_recibido = grup.id_material_recibido
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			'.$Condicion.'
			order by material_recibido asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
  /**
	 *Todos los materiales recibidos que corresponden al grupo que visualiza la pagina.
	 *@return array.
	*/
	function recibidos_id($Activos)
	{
		
		$Condicion = '';
		if('' != $Activos)
		{
			$Condicion =' and activo = "'.$Activos.'"';
		}
		
		$Consulta = '
			select id_material_recibido_grupo as id_mat
			from material_recibido mate, material_recibido_grupo grup
			where mate.id_material_recibido = grup.id_material_recibido
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			'.$Condicion.'
			order by material_recibido asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Materiales = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Materiales[] = $Fila['id_mat'];
		}
		
		return $Materiales;
		
	}
	
  /**
	 *Todos los materiales solicitados que corresponden al grupo que visualiza la pagina.
	 *@return array.
	*/
	function solicitados($Activos)
	{
		
		$Condicion = '';
		if('' != $Activos)
		{
			$Condicion =' and activo = "'.$Activos.'"';
		}
		
		$Consulta = '
			select material_solicitado, id_material_solicitado_grupo
			from material_solicitado mate, material_solicitado_grupo grup
			where mate.id_material_solicitado = grup.id_material_solicitado
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			'.$Condicion.'
			order by material_solicitado asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
  /**
	 *Todos los materiales solicitados que corresponden al grupo que visualiza la pagina.
	 *@return array.
	*/
	function solicitados_id($Activos)
	{
		
		$Condicion = '';
		if('' != $Activos)
		{
			$Condicion =' and activo = "'.$Activos.'"';
		}
		
		$Consulta = '
			select id_material_solicitado_grupo as id_mat
			from material_solicitado mate, material_solicitado_grupo grup
			where mate.id_material_solicitado = grup.id_material_solicitado
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			'.$Condicion.'
			order by material_solicitado asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Materiales = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Materiales[] = $Fila['id_mat'];
		}
		
		return $Materiales;
		
	}
	
	
	/*
	 *Mostrar los materiales de cada pedido.
	 *Detalle de pedido.
	 *Mostrar el material.
	*/
	function tipo_material($Id_pedido)
	{
		$Consulta = '
			select mate.id_material_solicitado
			from material_solicitado mate, material_solicitado_grupo grup,
			pedido ped, especificacion_matsolgru espe
			where mate.id_material_solicitado = grup.id_material_solicitado
			and grup.id_grupo = "'.$this->session->userdata('id_grupo').'"
			and ped.id_pedido = "'.$Id_pedido.'"
			and espe.id_pedido = ped.id_pedido
			and espe.id_material_solicitado_grupo = grup.id_material_solicitado_grupo
			order by mate.material_solicitado asc limit 0,1
		';
		
		$Resultado = $this->db->query($Consulta);
		$Id_material = 0;
		if(0 != $Resultado->num_rows())
		{
			$Id_material = $Resultado->result_array();
			$Id_material = $Id_material[0]['id_material_solicitado'];
		}
		return $Id_material;
		
	}
	
	
	
}

/* Fin del archivo */