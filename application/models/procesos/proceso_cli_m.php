<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proceso_cli_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada a cliente
	 *@return array: nombre del cliente.
	*/
	function buscar_cliente($Codigo_Cliente)
	{
		$nombre_cliente = '';
		
		if($Codigo_Cliente != '')
		{
			//Consultamos la base de datos para obtener la informacion de los clientes.
			$Consulta = '
				select id_cliente, nombre
				from cliente
				where codigo_cliente = "'.$Codigo_Cliente.'"
				and id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
			
			//Ejecuto la consulta
			$Resultado = $this->db->query($Consulta);
			
			$nombre_cliente = '';
			//Exploramos el array para mostrar el nombre del cliente.
			foreach($Resultado->result_array() as $Fila)
			{
				$nombre_cliente = $Fila['id_cliente'].'[-]'.$Fila["nombre"];
			}
		}
			//Regresamos el nombre del cliente.
			return $nombre_cliente;

	}
	
	
	/**
	 *Busca en la base de datos la informacion necesaria para poder generar
	 *el correlativo.
	 *@return  Numero de correlativo
	*/
	function genera_correlativo($Codigo_Cliente)
	{
		
		$Condicion = 'and codigo_cliente = "'.$Codigo_Cliente.'"';
		if('cli' == $this->session->userdata('tipo_grupo'))
		{
			$Condicion = '';
		}
		
		//Se genera la consulta.
		$Consulta = '
			SELECT proceso
			FROM procesos proc, cliente clie
			WHERE proc.id_cliente = clie.id_cliente
			'.$Condicion.'
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by proceso+0 desc limit 0, 1
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Verificamos si se encontraron numeros de procesos anteriores.
		if($Resultado->num_rows() > 0)
		{
			$Proceso = $Resultado->row_array();
			echo $Proceso['proceso'] + 1;
		}
		else{
			echo "1";
		}
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada a cliente
	 *@return array: nombre del cliente.
	*/
	function buscar_informacion_cliente($Codigo_Cliente)
	{
		$nombre_cliente = '';
		
		if($Codigo_Cliente != '')
		{
			//Consultamos la base de datos para obtener la informacion de los clientes.
			$Consulta = '
				select *
				from cliente
				where codigo_cliente = "'.$Codigo_Cliente.'"
				and id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
			
			//Ejecuto la consulta
			$Resultado = $this->db->query($Consulta);
			
			$nombre_cliente = '';
			//Exploramos el array para mostrar el nombre del cliente.
			foreach($Resultado->result_array() as $Fila)
			{
				$nombre_cliente = $Fila['nombre'].'[-]'.$Fila["contacto"].'[-]'.$Fila["telefono"].'[-]'.$Fila["email"].'[-]'.$Fila["id_cliente"];
			}
		}
			//Regresamos el nombre del cliente.
			return $nombre_cliente;

	}
}

/* Fin del archivo */