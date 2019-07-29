<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Informacion_material_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos el material solicitado para poder modificarlo.
	 *@return array.
	*/
	function mostrar_materiales($Id_material)
	{
			$Consulta = '
				select *
				from inventario_material mat
				where mat.id_grupo = "'.$this->session->userdata["id_grupo"].'"
				and mat.id_inventario_material = "'.$Id_material.'"
			';
		//echo $Consulta;
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
	
	
	/**
	 *Busca en la base de datos si el materia esta asignado a un proveedor.
	 *@return array.
	*/
	function mostrar_id_proveedor($Id_material)
	{
			$Consulta = '
				select id_inventario_proveedor
				from inventario_material_proveedor
				where id_inventario_material = "'.$Id_material.'"
			';
		//echo $Consulta;	
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
	
	
	/**
	 *Busca en la base de datos todos los materiales existentes
	 *@return array.
	*/
	function mostrar_todos_materiales()
	{
			$Consulta = '
				select *
				from inventario_material
				where id_grupo = "'.$this->session->userdata["id_grupo"].'"
				order by codigo_sap asc
			';
		//echo $Consulta;	
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