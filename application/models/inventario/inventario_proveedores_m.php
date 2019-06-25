<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventario_proveedores_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos los proveedores existentes
	 *@return array.
	*/
	function mostrar_proveedor()
	{
			
			$Consulta = '
				select *
				from inventario_proveedor
				where id_grupo = "'.$this->session->userdata["id_grupo"].'"
				order by proveedor_nombre asc
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
	
	/**
	 *Funcion que sirve para agregar un nuevo proveedor
	 *@return OK si se agrega el proveedor.
	 *@return ERROR sie el proveedor no se puede agregar.
	*/
	function agregar_proveedor($Proveedor)
	{
		$aleatorio = rand(100,999);
		$contra = substr($Proveedor, 0, 3);
		$usuario = $contra."-".$aleatorio;
		$aleatorio = rand(10000,99999);
		$contra .= $aleatorio;

			$Consulta = '
							insert into inventario_proveedor values(
																					NULL,
																					"'.$Proveedor.'",
																					"'.$usuario.'",
																					"'.$contra.'",
																					"'.$this->session->userdata["id_grupo"].'"
							)';
		
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