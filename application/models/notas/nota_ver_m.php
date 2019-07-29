<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nota_ver_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Funcion que permitira mostrar el correlativo y la fecha.
	 *return id de la nota de envio y el correlativo.
	 **/
	function mostrar_correlativo($id_nota_env)
	{
		
		//Establecemos la consulta para mostrar el ultimo correlativo.
		$Consulta = 'select correlativo, fecha from pedido_nota_envio where id_nota_env = "'.$id_nota_env.'"';
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		//Verificamos si hay informacion para mostrar.
		if(0 < $Resultado->num_rows)
		{
			//Regresamos un array con la informacion.
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
	
	/**
	 *Funcion que permitira mostrar el nombre del cliente
	 *return nombre del cliente y el id del cliente.
	 **/
	function mostrar_cliente($id_nota_env)
	{
		
		//Establecemos la consulta para mostrar el nombre y el Id del cliente
		$Consulta = 'select cli.nombre as cliente, cli.id_cliente
				from cliente cli, procesos proc, pedido ped, pedido_nota_material nota
				where cli.id_cliente = proc.id_cliente
				and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = nota.id_pedido
				and id_nota_env = "'.$id_nota_env.'"';
				
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		//Verificamos si hay informacion para mostrar.
		if(0 < $Resultado->num_rows)
		{
			//Regresamos un array con la informacion.
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
	
	/**
	 *Funcion que permitira mostrar todos los id de los pedidos
	 *return id de los pedidos.
	 **/
	function mostrar_pedidos($id_nota_env)
	{
		//Establecemos la consulta para mostrar el ultimo correlativo.
		$Consulta = 'select distinct id_pedido
									from pedido_nota_material pednotmat
									where id_nota_env = "'.$id_nota_env.'"';
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		$id_pedido = array();
		$Datos = $Resultado->result_array();
		//Verificamos si hay informacion para mostrar.
		if(0 < $Resultado->num_rows)
		{
			//Exploramos el array
			foreach($Datos as $Datos_pedido)
			{
				//Asignamos el resultado a una variable.
				$id_pedido[] =  $Datos_pedido['id_pedido'];
			}
			return $id_pedido;
		}
		else
		{
			return array();
		}
	}
	
	/**
	 *Funcion que permitira mostrar todos los id de los pedidos
	 *return id de los pedidos.
	 **/
	function mostrar_materiales($pedidos, $id_nota_env)
	{
		$info = array();
		if(count($pedidos) != 0)
		{
			//Exploramos el array para obtener los id de los pedidos.
			foreach($pedidos as $Datos_pedido)
			{
				//Asignamos el id del pedido a una variable.
				$id_pedido = $Datos_pedido;
				
				//Establecemos la consulta.
				$Consulta_tipo = 'select id_nota_mat, cantidad, tipo,
													id_material, otro_mat, id_pedido
													from pedido_nota_material
													where id_pedido = "'.$id_pedido.'"
													and id_nota_env = "'.$id_nota_env.'"';
				//Ejecutamos la consulta.
				$Resultado_tipo = $this->db->query($Consulta_tipo);
				//Verificamos si hay informacion para mostrar.
				$Datos_tipo = $Resultado_tipo->result_array();
				//print_r($Datos);
				//Exploramos el array.
				foreach($Datos_tipo as $Datos_tip)
				{
					//Asignamos los resultados a un nuevo array.
					$info[$id_pedido][$Datos_tip['id_nota_mat']]['id_pedido'] = $Datos_tip['id_pedido'];
					$info[$id_pedido][$Datos_tip['id_nota_mat']]['cantidad'] = $Datos_tip['cantidad'];
					$info[$id_pedido][$Datos_tip['id_nota_mat']]['tipo'] = $Datos_tip['tipo'];
					$info[$id_pedido][$Datos_tip['id_nota_mat']]['otro_mat'] = $Datos_tip['otro_mat'];
					$info[$id_pedido][$Datos_tip['id_nota_mat']]['id_material'] = $Datos_tip['id_material'];
				}
			}
		}
		return $info;
	}
}
/* Fin del archivo */