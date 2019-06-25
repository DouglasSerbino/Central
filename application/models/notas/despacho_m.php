<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Despacho_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Funcion que permitira buscar el nombre del cliente.
	 *@param $id_cliente.
	 *return Nombre del cliente.
	 **/
	function clientes($id_cliente)
	{
		$Consulta = 'select nombre from cliente where codigo_cliente = "'.$id_cliente.'"';
		
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
	 *Busca en la base los procesos a los que se le generaran notas de envio.
	 *@return array.
	*/
	function mostrar_notas($id_cliente, $fecha)
	{
		//Establecemos la consulta para buscar los procesos correspondientes.
		$Consulta = '
							select proc.proceso, cli.nombre, proc.nombre as producto,
								ped.id_pedido, ped.fecha_reale
							from procesos proc, cliente cli, pedido ped
							where proc.id_cliente=cli.id_cliente
								and proc.id_proceso = ped.id_proceso
								and cli.codigo_cliente="'.$id_cliente.'"
								and ped.fecha_reale = "'.$fecha.'"
								and id_grupo = "'.$this->session->userdata('id_grupo').'"
		';

		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		$Datos = $Resultado->result_array();
		$info = array();
		foreach($Datos as $Datos_notas)
		{
			
			$info[$Datos_notas['id_pedido']]['id_pedido'] = $Datos_notas['id_pedido'];
			$info[$Datos_notas['id_pedido']]['proceso'] = $Datos_notas['proceso'];
			$info[$Datos_notas['id_pedido']]['nombre'] = $Datos_notas['nombre'];
			$info[$Datos_notas['id_pedido']]['producto'] = $Datos_notas['producto'];
			$info[$Datos_notas['id_pedido']]['fecha_reale'] = $Datos_notas['fecha_reale'];
			$info[$Datos_notas['id_pedido']]['id_nota_env'] = '';
			//Establecemos la consulta para verificar si los pedidos ya tienen nota de envio.
			$Consulta = '
								select ped_env.id_nota_env
								from pedido_nota_envio ped_env, pedido_nota_material ped_mat
								where ped_env.id_nota_env = ped_mat.id_nota_env
								and id_pedido = "'.$Datos_notas["id_pedido"].'" limit 0, 1
			';
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			//Asignamos el array a una variable.
			$Datos = $Resultado->result_array();
			//Exploramos el array.
			foreach($Datos as $Datos_codigo)
			{
				$info[$Datos_notas['id_pedido']]['id_nota_env'] = $Datos_codigo['id_nota_env']; 
			}
		}
		return $info;
	}
}
/* Fin del archivo */