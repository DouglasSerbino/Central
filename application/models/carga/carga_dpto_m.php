<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carga_dpto_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *
	*/
	function pedidos($Usuarios)
	{
		
		$Id_Usuarios_v = array();
		$Trabajos = array();
		
		foreach($Usuarios as $Dptos)
		{
			
			if('n' == $Dptos['tiempo'])
			{
				continue;
			}
			
			
			foreach($Dptos['usuarios'] as $Id_Usuario => $Info)
			{
				$Id_Usuarios_v[] = 'peus.id_usuario = "'.$Id_Usuario.'"';
			}
			
		}
		
		
		if(0 < count($Id_Usuarios_v))
		{
			
			$Consulta = '
				select codigo_cliente, proceso, proc.nombre, ped.id_pedido,
				tiempo_asignado, peus.id_usuario, id_peus
				from cliente clie, procesos proc, pedido ped, pedido_usuario peus
				where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = peus.id_pedido and estado != "Terminado"
				and ('.implode(' or ', $Id_Usuarios_v).')
			';
			
			$Resultado = $this->db->query($Consulta);
			
			
			
			if(0 < $Resultado->num_rows())
			{
				
				foreach($Resultado->result_array() as $Fila)
				{
					
					//Array temporal para no tener que escribir todos los campos en cada asignacion
					$Un_Array['pro'] = $Fila['codigo_cliente'].'-'.$Fila['proceso'];
					$Un_Array['nom'] = $Fila['nombre'];
					$Un_Array['tie'] = $Fila['tiempo_asignado'];
					$Un_Array['peu'] = $Fila['id_peus'];
					
					$Trabajos[$Fila['id_usuario']]['trabajos'][$Fila['id_pedido']] = $Un_Array;
					
				}
				
			}
			
			
		}
		
		return $Trabajos;
		
	}
	
	
	
	
	function asignar(
		$Pedidos
	)
	{
		
		foreach($Pedidos->usu as $Id_Usuario => $Peus)
		{
			
			if(0 < count($Peus))
			{
				
				$Consulta = '
					update pedido_usuario
					set id_usuario = "'.$Id_Usuario.'"
					where id_peus = "'.implode('" or id_peus = "', $Peus).'"
				';
				
				$this->db->query($Consulta);
			}
			
		}
		
		return 'ok';
		
	}
	
	
}

/* Fin del archivo */