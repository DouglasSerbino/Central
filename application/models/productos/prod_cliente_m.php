<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prod_Cliente_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca el listado de los productos para este cliente.
	 *@param string $Id_Cliente.
	 *@param string $Activo.
	 *@return array.
	*/
	function listado($Id_Cliente, $Activo = '', $Id_Grupo = 0)
	{
		
		$Id_Grupo += 0;
		if(0 == $Id_Grupo)
		{
			$Id_Grupo = $this->session->userdata('id_grupo');
		}
		
		$Consulta = '
			select prod.id_producto, clie.id_prod_clie, producto,
			precio, cantidad, concepto, clie.id_producto
			from producto_cliente clie, producto prod
			where clie.id_producto = prod.id_producto and id_cliente = "'.$Id_Cliente.'"
			and id_grupo = "'.$Id_Grupo.'"
		';
		
		
		if('' != $Activo)
		{
			$Consulta .= ' and clie.activo = "'.$Activo.'" and prod.activo = "'.$Activo.'" ';
		}
		
		$Consulta .= ' order by producto asc';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}

	function modificar_producto($id_producto, $descripcion_producto){
		$datos_actualizar = array(
    		'producto' => $descripcion_producto
		);
		$this->db->where('id_producto', $id_producto);
		$this->db->update('producto', $datos_actualizar);

	}

	function ingresar_producto($descripcion_producto){
		$datos_insertar = array(
   			'producto' => $descripcion_producto ,
   			'activo' => 's' ,
		);
		$this->db->insert('producto', $datos_insertar); 
	}
}

/* Fin del archivo */