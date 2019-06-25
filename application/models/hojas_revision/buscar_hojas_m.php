<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buscar_hojas_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada al cliente y el proceso seleccionado.
	 *@param string $Id_pedido: Id del pedido del que se buscara la informacion.
	 *@return array: Informacion del cliente y del proceso.
	*/
	function cliente_proceso($cod_cliente, $proceso)
	{
		
		//Consultamos la base de datos para obtener la informacion del grupo.
		$Consulta = 'SELECT cli.nombre as cliente, cli.codigo_cliente,
									proc.proceso, proc.nombre, proc.id_proceso
									FROM cliente cli, procesos proc, pedido ped
									WHERE cli.id_cliente = proc.id_cliente
									AND proc.id_proceso = ped.id_proceso
									AND cli.codigo_cliente = "'.$cod_cliente.'"
									AND proc.proceso = "'.$proceso.'"
									and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"';
		
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Si la consulta obtuvo resultados
		if(0 < $Resultado->num_rows())
		{
			//Regreso el array con los grupos encontrados
			return $Resultado->result_array();
		}
		else
		{//Si no hay resultados
			return array();
		}
	}
	
	/**
	 *Funcion que nos permitira mostrar las diferentes hojas de revision que
	 *existen del pedido seleccionado.
	*/
	function hojas_revision($cod_cliente, $proceso)
	{
		
		//Consultamos la base de datos para obtener la informacion del grupo.
		$Consulta = 'SELECT ped.id_pedido, hojas.fecha, hojas.tipo_hoja
									FROM procesos proc, pedido ped, hojas_revision hojas, cliente cli
									WHERE proc.id_proceso = ped.id_proceso
									AND ped.id_pedido = hojas.id_pedido
									AND cli.id_cliente = proc.id_cliente
									AND cli.codigo_cliente = "'.$cod_cliente.'"
									AND proc.proceso = "'.$proceso.'"
									and hojas.id_usuario = "'.$this->session->userdata('id_usuario').'"
									and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
									order by hojas.fecha desc, hojas.id_hoja desc';
		
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Si la consulta obtuvo resultados
		if(0 < $Resultado->num_rows())
		{
			//Regreso el array con la informacion encontrada 
			return $Resultado->result_array();
		}
		else
		{//Si no hay resultados
			return array();
		}
	}
	
	
	/**
	 *Funcion que nos permitira mostrar la hojas de revision que
	 *ha seleccionado el operador.
	*/
	function mostrar_hoja_revision($id_pedido, $tipo_hoja)
	{
		
		//Consultamos la base de datos para obtener la informacion del grupo.
		$Consulta = 'select *
								from hojas_revision hojas, usuario usu
								where hojas.id_pedido = "'.$id_pedido.'"
								and hojas.tipo_hoja = "'.$tipo_hoja.'"
								and usu.id_usuario = hojas.id_usuario
								order by id_hoja asc
								';
		
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Si la consulta obtuvo resultados
		if(0 < $Resultado->num_rows())
		{
			//Regreso el array con los grupos encontrados
			return $Resultado->result_array();
		}
		else
		{//Si no hay resultados
			return array();
		}
	}
	
	
	/**
	 *Funcion que nos permitira mostrar los colores pertenecientes
	 *a la hoja de revision seleccionada.
	*/
	function buscar_colores($Id_pedido)
	{
		
		//Consultamos la base de datos para obtener la informacion del grupo.
		$Consulta = 'select color.color, color.aprobado
									from hojas_revision hojas, hoja_offset_color color
									where hojas.id_pedido = "'.$Id_pedido.'"
									and hojas.id_hoja = color.id_hoja
								';
		
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Si la consulta obtuvo resultados
		if(0 < $Resultado->num_rows())
		{
			//Regreso el array con los grupos encontrados
			return $Resultado->result_array();
		}
		else
		{//Si no hay resultados
			return array();
		}
	}
}

/* Fin del archivo */