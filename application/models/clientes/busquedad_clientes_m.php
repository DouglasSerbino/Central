<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Busquedad_clientes_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Total de clientes para este grupo.
	 *@return $Total.
	*/
	function total_clientes($Activo = 's')
	{
		
		//Total de clientes
		$Consulta = '
			select count(id_cliente) as tt_clientes
			from cliente
			where id_grupo = "'.$this->session->userdata["id_grupo"].'"
			and activo = "'.$Activo.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Fila = $Resultado->row_array();
		
		$Total_Clientes = $Fila['tt_clientes'];
		$Total_Clientes += 0;
		
		return $Total_Clientes;
		
	}
	
	
	/**
	 *Busca en la base de datos la informacion de todos los clientes.
	 *@return array.
	*/
	function mostrar_clientes($Campos = '', $Activos = false)
	{
		
		if('' == $Campos)
		{
			$Campos = 'id_cliente, codigo_cliente, nombre, direccion,
			nit, web, credito, cliente.activo, pais';
		}
		
		//Consultamos la base de datos para obtener la informacion de los clientes.
		$Consulta = '
			select '.$Campos.'
			from cliente
			where cliente.id_grupo = "'.$this->session->userdata["id_grupo"].'"
		';
		if($Activos)
		{
			$Consulta .= '
				and activo = "s"
			';
		}
		$Consulta .= '
			order by codigo_cliente asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los grupos encontrados
		return $Resultado->result_array();
		
	}
	
	
	/**
	 *Busca en la base de datos la informacion de todos los clientes, segun el
	 *rango que se especifique.
	 *@param string $Inicio.
	 *@return array.
	*/
	function rango_clientes($Inicio, $Activo = 's')
	{
		
		//Consultamos la base de datos para obtener la informacion de los clientes.
		$Consulta = '
			select id_cliente, codigo_cliente, nombre, direccion, credito,
			nit, web, activo, cliente.usuario, cliente.contrasena, pais
			from cliente
			where id_grupo = "'.$this->session->userdata["id_grupo"].'"
			and cliente.activo = "'.$Activo.'"
			order by codigo_cliente asc
			limit '.$Inicio.', 50
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los grupos encontrados
		return $Resultado->result_array();
	}
	
	
	/**
	 *Busca en la base de datos la informacion de un cliente en especifico.
	 *@param string $Id del cliente: Id del cliente seleccionado.
	 *@return string 'error': Si ocurre algun error en la busquedad de informacion.
	 *@return array: Si se encuentra el cliente seleccionado.
	*/
	
	function busquedad_especifica($Id_cliente)
	{
		
		//Consultamos la base de datos para obtener la informacion del grupo.
		$Consulta = '
			select codigo_cliente, nombre, direccion, nit, credito,
			web, id_cliente, pais
			from cliente
			where id_grupo = "'.$this->session->userdata["id_grupo"].'"
			and id_cliente = "'.$Id_cliente.'"
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los grupos encontrados
		return $Resultado->result_array();
	}
	
	
	
	
	
	function busquedad_codigo($Codigo_cliente)
	{
		
		//Consultamos la base de datos para obtener la informacion del grupo.
		$Consulta = '
			select id_cliente, nombre, contacto, telefono, credito,
			email, id_usuario, id_cliente, pais
			from cliente
			where id_grupo = "'.$this->session->userdata["id_grupo"].'"
			and codigo_cliente = "'.$Codigo_cliente.'"
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los grupos encontrados
		return $Resultado->row_array();
	}
	
	
	
	
	/**
	 *Clientes agrupados por el tipo.
	 *@return array.
	*/
	function clientes_tipo($Campos = '')
	{
		
		$Tipos = array();
		$Clientes = array();
		
		
		//Seleccion de los tipos de cliente
		$Consulta = '
			select id_tipo, tipo
			from cliente_tipo
			where activo = "s"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Tipos[$Fila['id_tipo']] = $Fila['tipo'];
		}
		
		
		
		
		if('' == $Campos)
		{
			$Campos = 'cliente.id_tipo, id_cliente, codigo_cliente, nombre, contacto,
			telefono, email, id_usuario, cliente.activo, pais';
		}
		
		//Consultamos la base de datos para obtener la informacion de los clientes.
		$Consulta = '
			select '.$Campos.'
			from cliente, cliente_tipo
			where cliente.id_grupo = "'.$this->session->userdata["id_grupo"].'"
			and cliente.id_tipo = cliente_tipo.id_tipo
			order by cliente.id_tipo asc, cliente.nombre asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		
		if(0 < $Resultado->num_rows())
		{
			
			foreach($Resultado->result_array() as $Fila)
			{
				
				$Clientes[$Fila['id_tipo']]['tipo'] = $Tipos[$Fila['id_tipo']];
				$Clientes[$Fila['id_tipo']]['clientes'][] = $Fila;
				
			}
			
		}
		
		
		return $Clientes;
		
	}
	
	
	function cliente_tipo()
	{
		$Consulta = 'select id_tipo, tipo from cliente_tipo where activo = "s"';
		
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
	
	
	
	
	function permisos_usuarios($Id_Usuario)
	{
		
		//Consultamos la base de datos para obtener la informacion de los clientes.
		$Consulta = '
			select *
			from permiso_usuario_cliente
			where id_usuario = "'.$Id_Usuario.'"
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los grupos encontrados
		return $Resultado->result_array();
		
	}


	function cliente_caracteristicas($Id_Cliente)
	{

		$Caracteristicas = array(
			'contacto' => array(),
			'maquina' => array(),
			'plancha' => array(),
			'anilox' => array(),
			'producto' => array()
		);

		//Informacion de Contacto
		$Consulta = '
			select nombre, cargo, email, tel_oficina, tel_directo, tel_celular
			from cliente_contacto
			where id_cliente = "'.$Id_Cliente.'"
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los grupos encontrados
		$Caracteristicas['contacto'] = $Resultado->result_array();


		//Informacion de Maquina
		$Consulta = '
			select maquina, colores
			from cliente_maquina
			where id_cliente = "'.$Id_Cliente.'"
			order by id_cliente_maquina asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los grupos encontrados
		$Caracteristicas['maquina'] = $Resultado->result_array();


		//Informacion de Plancha
		$Consulta = '
			select altura, lineaje, marca
			from cliente_placa
			where id_cliente = "'.$Id_Cliente.'"
			order by id_cliente_placa asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los grupos encontrados
		$Caracteristicas['plancha'] = $Resultado->result_array();


		//Informacion de Anilox
		$Consulta = '
			select anilox, bcm, cantidad
			from cliente_anilox
			where id_cliente = "'.$Id_Cliente.'"
			order by id_cliente_anilox asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los grupos encontrados
		$Caracteristicas['anilox'] = $Resultado->result_array();


		//Informacion de Producto
		$Consulta = '
			select clie.id_producto, precio, concepto, producto
			from producto prod, producto_cliente clie
			where prod.id_producto = clie.id_producto and id_cliente = "'.$Id_Cliente.'"
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los grupos encontrados
		$Caracteristicas['producto'] = $Resultado->result_array();


		return $Caracteristicas;

	}
	
}

/* Fin del archivo */