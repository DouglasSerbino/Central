<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buscar_proceso_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Buscar en la base de datos si existe el proceso indicado por el usuario.
	 *@param string $Id_Cliente.
	 *@param string $Proceso.
	 *@return string array con informacion del proceso, "" si no existe.
	*/
	function cliente_proceso(
		$Codigo_Cliente,
		$Proceso,
		$Id_Grupo = false
	)
	{
		
		if(false == $Id_Grupo)
		{
			$Id_Grupo = $this->session->userdata('id_grupo');
		}
		
		$Consulta = '
			SELECT proc.proceso, proc.id_proceso, cli.nombre,
			proc.nombre as nombre_proc, cli.id_cliente, codigo_cliente
			FROM procesos proc, cliente cli
			WHERE proc.proceso = "'.$Proceso.'"
			AND codigo_cliente = "'.$Codigo_Cliente.'"
			AND proc.id_cliente = cli.id_cliente
			and id_grupo = "'.$Id_Grupo.'"
		';
		//echo $Consulta.'<br />';
		$Resultado = $this->db->query($Consulta);
		
		if(1 == $Resultado->num_rows())
		{
			
			return $Resultado->row_array();
			
		}
		else
		{
			return array();	
		}
		
	}
	
	
  /**
	 *Buscar en la base de datos si existe el proceso indicado por el usuario.
	 *@param string $Id_proceso.
	 *@return string array con informacion del proceso, "" si no existe.
	*/
	function id_proceso($Id_proceso)
	{
		
		$Consulta = '
			select id_proceso, proceso, clie.id_cliente, clie.nombre,
			proc.nombre as nombre_proceso, codigo_cliente
			from procesos proc, cliente clie
			where proc.id_cliente = clie.id_cliente
			and id_proceso = "'.$Id_proceso.'"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		
		$Resultado = $this->db->query($Consulta);
		
		if( 1 == $Resultado->num_rows())
		{
			
			$Informacion = $Resultado->row_array();
			
			return $Informacion;
			
		}
		else
		{
			return array();
		}
	}
	
	
	/**
	 *Buscar en la base de datos todos los pedidos pertenecientes al proceso seleccionado.
	 *@param string $Id_proceso.
	 *@return string array con informacion del pedido.
	*/
	function informacion_pedido($Id_proceso)
	{
		
		$Consulta = '
			SELECT ped.fecha_entrada, ped.fecha_entrega,
			ped.fecha_reale, ped.id_pedido, codigo_cliente
			from pedido ped, procesos proc,cliente clie
			where clie.id_cliente = proc.id_cliente
			and proc.id_proceso = ped.id_proceso
			and proc.id_proceso = "'.$Id_proceso.'"
			and clie.id_grupo = "'.$this->session->userdata('id_grupo').'
			ORDER BY ped.id_pedido desc"
		';
	
		$Resultado = $this->db->query($Consulta);
		
		if($Resultado->num_rows() >= 1)
		{
			
			return $Resultado->result_array();
			
		}
		else
		{
			return array();
		}
		
	}
	
	
  /**
	 *Busca en la base de datos los procesos coincidentes con la descripcion
	 *e id_cliente enviados y que pertenecen a su grupo
	 *@param string $Descripcion.
	 *@param string $Id_Cliente.
	 *@return string listado de procesos.
	*/
	function descripcion($Descripcion, $Id_Cliente = '')
	{
		
		/*$Consulta = '
			select id_proceso, proceso, proc.nombre, codigo_cliente
			from procesos proc, cliente clie
			where proc.id_cliente = clie.id_cliente
			and proc.nombre like "%'.$Descripcion.'%"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
		';*/
		
		$Consulta = '
			select proc.id_proceso, proceso, proc.nombre, codigo_cliente, max(id_pedido) as pedido
			from procesos proc, cliente clie, pedido ped
			where proc.id_cliente = clie.id_cliente and proc.id_proceso = ped.id_proceso
			and proc.nombre like "%'.$Descripcion.'%"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		//echo $Consulta."\n\n";
		if('' != $Id_Cliente)
		{
			$Consulta .= ' and proc.id_cliente = "'.$Id_Cliente.'" ';
		}
		
		$Consulta .= ' group by proc.id_proceso order by pedido desc';
		
		$Resultado = $this->db->query($Consulta);
		
		$Procesos = array();
		foreach($Resultado->result_array() as $Fila)
		{
			//$Fila['proceso'] = $this->seguridad_m->mysql_seguro($Fila['proceso']);
			$Proceso_Turno = '['.$Fila['id_proceso'].',"'.$Fila['codigo_cliente'].'-'.$this->seguridad_m->mysql_seguro($Fila['proceso']).' -- '.$Fila['nombre'].'"]';
			
			$Procesos[] = $Proceso_Turno;
		}
		
		if(0 < count($Procesos))
		{
			return '{"proc":['.implode(',', $Procesos).']}';
		}
		else
		{
			return '';
		}
		
	}
	
	
	/**
	 *Cuantos procesos hay en la base de datos para este cliente?
	 *@param string $Id_Cliente.
	 *@return string array con informacion de los procesos.
	*/
	function total_procesos($Id_Cliente)
	{
		
		$Consulta = '
			select count(id_proceso) as tt_procesos
			from procesos
			where id_cliente = "'.$Id_Cliente.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Tt_Procesos = 0;
		foreach($Resultado->result_array() as $Fila)
		{
			$Tt_Procesos = $Fila['tt_procesos'];
		}
		
		$Tt_Procesos += 0;
		
		return $Tt_Procesos;
		
	}
	
	
	/**
	 *Buscar en la base de datos los procesos que pertenecen al cliente proporcionado.
	 *@param string $Id_Cliente.
	 *@param string $Inicio.
	 *@return string array con informacion de los procesos.
	*/
	function listado_procesos($Id_Cliente, $Inicio)
	{
		
		$Consulta = '
			SELECT proc.proceso, proc.id_proceso, proc.nombre, cli.id_cliente
			FROM procesos proc, cliente cli, grupos
			WHERE cli.id_cliente = "'.$Id_Cliente.'"
			AND cli.id_grupo = grupos.id_grupo
			AND proc.id_cliente = cli.id_cliente
			and grupos.id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by id_proceso asc
			limit '.$Inicio.', 50
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if($Resultado->num_rows() >= 1)
		{
			
			return $Resultado->result_array();
			
		}
		else
		{
			return 0;	
		}
	}
	
	/**
	 *Buscar en la base de datos los procesos por medio del id del pedido.
	 *@param string $Id_Pedido.
	 *@return string array con informacion del procesos al que pertenece el pedido.
	*/
	function busqueda_pedido($Id_Pedido)
	{
		
		$Consulta = '
			select proc.id_proceso, cli.id_cliente as id_cliente, proc.proceso, cli.nombre,
			proc.nombre as nombre_proceso, codigo_cliente, cli.id_grupo, proc.id_proceso
			FROM procesos proc, cliente cli, pedido ped
			WHERE proc.id_cliente = cli.id_cliente
			AND ped.id_proceso = proc.id_proceso
			AND ped.id_pedido = "'.$Id_Pedido.'"
			and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		
		if(1 == $Resultado->num_rows())
		{
			$Informacion = $Resultado->row_array();
			
			return $Informacion;
			
		}
		else
		{
			return 0;	
		}
		
	}
	
	
	/**
	 *De repente surge la necesidad de conocer el id_proceso de un proceso que pertenece
	 *a otro grupo, debido a que forma parte de la ruta de trabajo.
	*/
	function id_cliente_proceso($Id_Cliente, $Proceso, $Id_Grupo)
	{
		
		$Id_Proceso = 0;
		
		$Consulta = '
			select id_proceso
			from procesos proc, cliente clie
			where proc.id_cliente = clie.id_cliente
			and clie.id_cliente = "'.$Id_Cliente.'" and proceso = "'.$Proceso.'"
			and id_grupo = "'.$Id_Grupo.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
			$Id_Proceso = $Fila['id_proceso'];
		}
		
		return $Id_Proceso;
		
	}
	
	
	
	function todos()
	{
		
		$Consulta = '
			select count(id_proceso) as todos
			from procesos
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Fila = $Resultado->row_array();
		
		return $Fila['todos'];
		
	}
	
	
}

/* Fin del archivo */