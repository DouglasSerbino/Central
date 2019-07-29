<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle_proy_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function info_proyecto($Id_proyecto)
	{
		
		$Condicion = '';
		if(1 != $this->session->userdata('id_grupo'))
		{
			$Condicion = ' and proy.id_grupo = "'.$this->session->userdata('id_grupo').'"';
		}
		
		$Consulta = '
			SELECT proy.proyecto, proc.nombre, proc.proceso, cli.codigo_cliente,
			proy.id_usuario, fecha, aprobacion, fecha_reale, proy.activo
			from cliente cli, procesos proc, pedido ped, proyecto_tiempo proy
			where cli.id_cliente = proc.id_cliente
			and proc.id_proceso = ped.id_proceso
			and ped.id_proyecto = proy.id_proyecto
			and proy.id_proyecto = "'.$Id_proyecto.'"'.$Condicion.'
			limit 0, 1
		';
		$Resultado = $this->db->query($Consulta);
		
		$Fila = $Resultado->row_array();
		
		$Proyecto = array();
		$Proyecto['fech'] = $Fila['fecha'];
		$Proyecto['acti'] = $Fila['activo'];
		$Proyecto['nomb'] = $Fila['nombre'];
		$Proyecto['proc'] = $Fila['proceso'];
		$Proyecto['proy'] = $Fila['proyecto'];
		$Proyecto['apro'] = $Fila['aprobacion'];
		$Proyecto['codi'] = $Fila['codigo_cliente'];
		
		$Proyecto['esta'] = 'En Producci&oacute;n';
		if('0000-00-00' != $Fila['fecha_reale'])
		{
			$Proyecto['esta'] = 'Pendiente de Aprobaci&oacute;n';
		}
		if(0 < $Fila['id_usuario'])
		{
			$Consulta = '
				select nombre
				from usuario
				where id_usuario = "'.$Fila['id_usuario'].'"
			';
			$Resultado = $this->db->query($Consulta);
			$Fila2 = $Resultado->row_array();
			$Proyecto['esta'] = 'Aprobado por: '.$Fila2['nombre'];
		}
		if('n' == $Proyecto['acti'] && 0 == $Fila['id_usuario'])
		{
			$Proyecto['esta'] = 'Finalizado';
		}
		

		if(isset($Proyecto['proy']))
		{
			
			$Consulta = '
				SELECT ped.id_pedido, ped.fecha_entrada, ped.fecha_reale,
				pedtie.tiempo_venta, pedtie.tiempo_repro, pedtie.tiempo_cs
				from pedido ped, pedido_tie_repro pedtie
				where ped.id_pedido = pedtie.id_pedido
				and ped.id_proyecto = "'.$Id_proyecto.'"
				order by ped.fecha_reale desc, id_pedido desc
			';
			
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				
				$Proyecto['pedi'][$Fila['id_pedido']]['plan'] = $Fila['tiempo_cs'];
				$Proyecto['pedi'][$Fila['id_pedido']]['real'] = $Fila['fecha_reale'];
				$Proyecto['pedi'][$Fila['id_pedido']]['vent'] = $Fila['tiempo_venta'];
				$Proyecto['pedi'][$Fila['id_pedido']]['repr'] = $Fila['tiempo_repro'];
				$Proyecto['pedi'][$Fila['id_pedido']]['entr'] = $Fila['fecha_entrada'];
			}
			
			
		}
		
		//print_r($Proyecto);
		
		
		if(isset($Proyecto['proy']))
		{
			return $Proyecto;
		}
		else
		{
			return array();
		}
	}
  
}

/* Fin del archivo */