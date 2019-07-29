<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buscar_ruta_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Ruta de trabajo para mostrar en reportes.
	 *@param string $Id_pedido.
	 *@return array.
	*/
	function buscar_ruta(
		$Id_pedido
	)
	{
		
		
		$Consulta = '
			SELECT id_peus, iniciales, dep.departamento, usu.usuario, peus.estado,
			peus.fecha_asignado, peus.fecha_fin, usu.id_usuario, puesto, peus.tipo
			FROM departamentos dep, procesos proc, pedido ped, pedido_usuario peus,
			usuario usu, cliente cli
			WHERE proc.id_proceso = ped.id_proceso
			AND ped.id_pedido = peus.id_pedido
			AND peus.id_usuario = usu.id_usuario
			AND dep.id_dpto = usu.id_dpto
			AND ped.id_pedido = "'.$Id_pedido.'"
			AND cli.id_cliente = proc.id_cliente
			AND cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
			ORDER BY peus.id_peus ASC
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if($Resultado->num_rows() >= 1)
		{
			return $Resultado->result_array();
		}
		else
		{
			return '';	
		}	
	}
	
	/**
	 *Informacion de la tabla de pedido_usuario.
	 *@param string $Id_pedido.
	 *@return array.
	*/
	function pedido_usuario($Id_pedido)
	{
		
		$Consulta = '
			SELECT id_ruta_dpto, peus.id_usuario, fecha_asignado, fecha_inicio,
			fecha_fin, tiempo_asignado, peus.estado
			FROM procesos proc, pedido ped, pedido_usuario peus, cliente cli
			WHERE proc.id_proceso = ped.id_proceso
			AND ped.id_pedido = peus.id_pedido
			AND ped.id_pedido = "'.$Id_pedido.'"
			AND cli.id_cliente = proc.id_cliente
			AND cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
			ORDER BY peus.id_peus ASC
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if($Resultado->num_rows() >= 1)
		{
			$Peus = array();
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Peus[$Fila['id_ruta_dpto']]['estado'] = $Fila['estado'];
				$Peus[$Fila['id_ruta_dpto']]['fecha_fin'] = $Fila['fecha_fin'];
				$Peus[$Fila['id_ruta_dpto']]['id_usuario'] = $Fila['id_usuario'];
				$Peus[$Fila['id_ruta_dpto']]['fecha_inicio'] = $Fila['fecha_inicio'];
				$Peus[$Fila['id_ruta_dpto']]['fecha_asignado'] = $Fila['fecha_asignado'];
				$Peus[$Fila['id_ruta_dpto']]['tiempo_asignado'] = $Fila['tiempo_asignado'];
			}
			
			return $Peus;
		}
		else
		{
			return '';	
		}	
	}
	
	
	/**
	 *Busca la ruta del trabajo que va hacia atras desde el puesto que lo solicita.
	 *@param string $Id_Pedido.
	 *@param string $Id_Peus.
	 *@return string.
	*/
	function hacia_atras($Id_Pedido, $Id_Peus)
	{
		
		$Consulta = '
			SELECT id_peus, usu.usuario, usu.id_usuario, peus.tipo
			FROM procesos proc, pedido ped, pedido_usuario peus,
			usuario usu, cliente cli
			WHERE proc.id_proceso = ped.id_proceso
			AND ped.id_pedido = peus.id_pedido
			AND peus.id_usuario = usu.id_usuario
			AND ped.id_pedido = "'.$Id_Pedido.'"
			and id_peus <= "'.$Id_Peus.'"
			AND cli.id_cliente = proc.id_cliente
			AND cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
			ORDER BY peus.id_peus ASC
		';
		
		//echo $Consulta."\n\n";
		
		$Id_Usuarios = array();
		
		$Resultado = $this->db->query($Consulta);
		
		if($Resultado->num_rows() >= 1)
		{
			
			$Ruta = array();
			
			foreach($Resultado->result_array() as $Fila)
			{
				if(isset($Id_Usuarios[$Fila['usuario']]))
				{
					unset($Ruta[$Id_Usuarios[$Fila['usuario']]]);
				}
				$Ruta[$Fila['id_peus']] = '{';
				$Ruta[$Fila['id_peus']] .= '"ti":"'.$Fila['tipo'].'"';
				$Ruta[$Fila['id_peus']] .= ',"ip":"'.$Fila['id_peus'].'"';
				$Ruta[$Fila['id_peus']] .= ',"us":"'.$Fila['usuario'].'"';
				$Ruta[$Fila['id_peus']] .= ',"iu":"'.$Fila['id_usuario'].'"';
				$Ruta[$Fila['id_peus']] .= '}';
				$Id_Usuarios[$Fila['usuario']] = $Fila['id_peus'];
			}
			
			return '{"rut":['.implode(',', $Ruta).']}';
			
		}
		else
		{
			return '';	
		}	
		
	}
	
}

/* Fin del archivo */