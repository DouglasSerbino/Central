<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lecturas_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function compensacion()
	{
		
		$Consulta = '
			select *
			from pla_medicion_comp
			order by compensacion asc
		';
		$Resultado = $this->db->query($Consulta);
		
		$Compensacion = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Compensacion[$Fila['id_pla_medicion_comp']] = $Fila['compensacion'];
		}
		
		return $Compensacion;
		
	}
	
	
	function planchas()
	{
		
		$Consulta = '
			select *
			from pla_medicion_plancha
			order by plancha asc
		';
		$Resultado = $this->db->query($Consulta);
		
		$Plancha = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Plancha[$Fila['id_pla_medicion_plancha']] = $Fila['plancha'];
		}
		
		return $Plancha;
		
	}
	
	
	function sistema()
	{
		
		$Consulta = '
			select *
			from pla_medicion_sist
			order by sistema asc
		';
		$Resultado = $this->db->query($Consulta);
		
		$Sistema = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Sistema[$Fila['id_pla_medicion_sist']] = $Fila['sistema'];
		}
		
		return $Sistema;
		
	}
	
	
	function altura()
	{
		
		$Consulta = '
			select *
			from pla_medicion_alt
			order by altura asc
		';
		$Resultado = $this->db->query($Consulta);
		
		$Altura = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Altura[$Fila['id_pla_medicion_alt']] = $Fila['altura'];
		}
		
		return $Altura;
		
	}
	
	
	function trama()
	{
		
		$Consulta = '
			select *
			from pla_medicion_tra
			order by trama asc
		';
		$Resultado = $this->db->query($Consulta);
		
		$Trama = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Trama[$Fila['id_pla_medicion_tra']] = $Fila['trama'];
		}
		
		return $Trama;
		
	}
	
	
	function lineaje()
	{
		
		$Consulta = '
			select *
			from pla_medicion_lin
			order by lineaje asc
		';
		$Resultado = $this->db->query($Consulta);
		
		$Lineaje = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Lineaje[$Fila['id_pla_medicion_lin']] = $Fila['lineaje'];
		}
		
		return $Lineaje;
		
	}
	
	
	
	
	
	function referencias($Id_Cliente = 0)
	{
		
		$Id_Cliente += 0;
		$Condicion = '';
		if(0 < $Id_Cliente)
		{
			$Condicion = 'and id_cliente = "'.$Id_Cliente.'"';
		}
		
		
		$Referencias = array();
		
		
		$Consulta = '
			select *
			from pla_medicion
			where tipo = "ref" '.$Condicion.'
			order by version desc, compensacion asc, plancha asc, sistema asc, lineaje asc, id_cliente asc
		';
		$Resultado = $this->db->query($Consulta);
		
		$Id_Mediciones = array();
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Id_Mediciones[] = $Fila['id_pla_medicion'];
			
			$Referencias[$Fila['id_pla_medicion']]['tra'] = $Fila['trama'];
			$Referencias[$Fila['id_pla_medicion']]['alt'] = $Fila['altura'];
			$Referencias[$Fila['id_pla_medicion']]['pla'] = $Fila['plancha'];
			$Referencias[$Fila['id_pla_medicion']]['sis'] = $Fila['sistema'];
			$Referencias[$Fila['id_pla_medicion']]['ver'] = $Fila['version'];
			$Referencias[$Fila['id_pla_medicion']]['lin'] = $Fila['lineaje'];
			$Referencias[$Fila['id_pla_medicion']]['cli'] = $Fila['id_cliente'];
			$Referencias[$Fila['id_pla_medicion']]['com'] = $Fila['compensacion'];
			
			$Referencias['formulas'][] = $Fila['plancha'].'-'.$Fila['sistema'].'-'.$Fila['compensacion'].'-'.$Fila['altura'].'-'.$Fila['trama'].'-'.$Fila['lineaje'].'-'.$Fila['id_cliente'];
			
		}
		
		
		if(0 < count($Id_Mediciones))
		{
			$Consulta = '
				select *
				from pla_medicion_color
				where id_pla_medicion in ('.implode(',', $Id_Mediciones).')
			';
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				
				$Referencias[$Fila['id_pla_medicion']][$Fila['color']][5] = $Fila['p_5'];
				$Referencias[$Fila['id_pla_medicion']][$Fila['color']][25] = $Fila['p_25'];
				$Referencias[$Fila['id_pla_medicion']][$Fila['color']][50] = $Fila['p_50'];
				$Referencias[$Fila['id_pla_medicion']][$Fila['color']][75] = $Fila['p_75'];
				$Referencias[$Fila['id_pla_medicion']][$Fila['color']][100] = $Fila['p_100'];
				
			}
		}
		
		
		return $Referencias;
		
	}
	
	
	
	
	
	function pedido($Id_Pedido = 0)
	{
		
		$Id_Pedido += 0;
		$Valores = array();
		$Id_Medicion = 0;
		
		
		$Consulta = '
			select *
			from pla_medicion
			where tipo = "rea" and id_pedido = "'.$Id_Pedido.'"
		';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Id_Medicion = $Fila['id_pla_medicion'];
			$Valores['tra'] = $Fila['trama'];
			$Valores['ipl'] = $Fila['id_pla_medicion'];
			$Valores['alt'] = $Fila['altura'];
			$Valores['pla'] = $Fila['plancha'];
			$Valores['sis'] = $Fila['sistema'];
			$Valores['ver'] = $Fila['version'];
			$Valores['lin'] = $Fila['lineaje'];
			$Valores['cli'] = $Fila['id_cliente'];
			$Valores['com'] = $Fila['compensacion'];
			
			$Valores['formula'] = $Fila['plancha'].'-'.$Fila['sistema'].'-'.$Fila['compensacion'].'-'.$Fila['altura'].'-'.$Fila['trama'].'-'.$Fila['lineaje'].'-'.$Fila['id_cliente'];
			
		}
		
		
		if(0 < count($Valores))
		{
			$Consulta = '
				select *
				from pla_medicion_color
				where id_pla_medicion = "'.$Id_Medicion.'"
			';
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				
				$Valores[$Fila['color']][5] = $Fila['p_5'];
				$Valores[$Fila['color']][25] = $Fila['p_25'];
				$Valores[$Fila['color']][50] = $Fila['p_50'];
				$Valores[$Fila['color']][75] = $Fila['p_75'];
				$Valores[$Fila['color']][100] = $Fila['p_100'];
				
			}
		}
		else
		{
			$Valores['c'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
			$Valores['m'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
			$Valores['y'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
			$Valores['k'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
			$Valores['r'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
			$Valores['g'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
			$Valores['b'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
		}
		
		return $Valores;
		
	}
	
	
	function pedido_anterior($Id_Pedido = 0, $Id_Proceso)
	{
		
		$Id_Pedido += 0;
		$Id_Proceso += 0;
		$Valores = array();
		$Id_Medicion = 0;
		
		/*
		//Inicializacion
		$Valores['c'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
		$Valores['m'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
		$Valores['y'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
		$Valores['k'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
		$Valores['r'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
		$Valores['g'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
		$Valores['b'] = array(5 => 0, 25 => 0, 50 => 0, 75 => 0, 100 => 100);
		*/
		
		$Consulta = '
			select plan.id_pedido, fecha_reale, id_pla_medicion
			from pedido ped, pla_medicion plan
			where ped.id_pedido = plan.id_pedido and id_proceso = "'.$Id_Proceso.'"
			and plan.id_pedido < "'.$Id_Pedido.'"
			order by plan.id_pedido desc
			limit 0, 3
		';
		$Resultado = $this->db->query($Consulta);
		
		if(0 === $Resultado->num_rows())
		{
			return $Valores;
		}
		
		
		$Id_Pedido = array();
		$Pla_Medicion = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Id_Pedido[] = $Fila['id_pedido'];
			$Valores['fechas'][$Fila['id_pla_medicion']] = $Fila['fecha_reale'];
		}
		
		
		$Consulta = '
			select *
			from pla_medicion
			where tipo = "rea" and id_pedido in ('.implode(',', $Id_Pedido).')
		';
		$Resultado = $this->db->query($Consulta);
		
		$Id_Medicion = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Id_Medicion[] = $Fila['id_pla_medicion'];
			//$Valores['']
			if(!isset($Valores['tra']))
			{
				$Valores['tra'] = $Fila['trama'];
				$Valores['alt'] = $Fila['altura'];
				$Valores['pla'] = $Fila['plancha'];
				$Valores['sis'] = $Fila['sistema'];
				$Valores['ver'] = $Fila['version'];
				$Valores['lin'] = $Fila['lineaje'];
				$Valores['cli'] = $Fila['id_cliente'];
				$Valores['com'] = $Fila['compensacion'];
				$Valores['ipm'] = $Fila['id_pla_medicion'];
				
				$Valores['formula'] = $Fila['plancha'].'-'.$Fila['sistema'].'-';
				$Valores['formula'] .= $Fila['compensacion'].'-'.$Fila['altura'].'-';
				$Valores['formula'] .= $Fila['trama'].'-'.$Fila['lineaje'].'-'.$Fila['id_cliente'];
			}
		}
		
		
		if(0 < count($Valores))
		{
			$Consulta = '
				select *
				from pla_medicion_color
				where id_pla_medicion in ('.implode(',',$Id_Medicion).')
			';
			$Resultado = $this->db->query($Consulta);
			
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Valores['colores'][$Fila['id_pla_medicion']][$Fila['color']][5] = $Fila['p_5'];
				$Valores['colores'][$Fila['id_pla_medicion']][$Fila['color']][25] = $Fila['p_25'];
				$Valores['colores'][$Fila['id_pla_medicion']][$Fila['color']][50] = $Fila['p_50'];
				$Valores['colores'][$Fila['id_pla_medicion']][$Fila['color']][75] = $Fila['p_75'];
				$Valores['colores'][$Fila['id_pla_medicion']][$Fila['color']][100] = $Fila['p_100'];
			}
		}
		
		return $Valores;
		
	}
	
	
	
	
	
	function referencias_Reporte($Id_Cliente)
	{
		
		$Referencias = array();
		
		
		$Consulta = '
			select *
			from pla_medicion medi, pla_medicion_color colo
			where medi.id_pla_medicion = colo.id_pla_medicion and
			tipo = "ref" and id_cliente = "'.$Id_Cliente.'"
			order by colo.id_pla_medicion asc, id_pla_medicion_color asc
		';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Color = array();
			$Color[5] = $Fila['p_5'];
			$Color[25] = $Fila['p_25'];
			$Color[50] = $Fila['p_50'];
			$Color[75] = $Fila['p_75'];
			$Color[100] = $Fila['p_100'];
			
			$Referencias[$Fila['altura']][$Fila['plancha']][$Fila['sistema']][$Fila['compensacion']][$Fila['trama']][$Fila['lineaje']][$Fila['id_cliente']][$Fila['color']] = $Color;
			
		}
		
		
		
		return $Referencias;
		
	}
	
	
	
	
	function reales($Id_Grupo, $Id_Cliente = 0, $Anho = '', $Mes = '', $Id_Proceso = 0)
	{
		
		$Id_Cliente += 0;
		$Id_Proceso += 0;
		$Anho = $this->seguridad_m->mysql_seguro($Anho);
		$Mes = $this->seguridad_m->mysql_seguro($Mes);
		
		if(0 == $Id_Cliente && 0 == $Id_Proceso)
		{
			return array();
		}
		
		$Consulta = '
			select codigo_cliente, proceso, proc.nombre, id_pla_medicion, altura, trama,
			plancha, sistema, version, compensacion, lineaje, plan.id_cliente, fecha_reale
			from cliente clie, procesos proc, pedido ped, pla_medicion plan
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = plan.id_pedido and tipo = "rea"
			and clie.id_grupo = "'.$Id_Grupo.'"
		';
		if(0 < $Id_Cliente)
		{
			$Consulta .= '
				and fecha_reale >= "'.$Anho.'-'.$Mes.'-01"
				and fecha_reale <= "'.$Anho.'-'.$Mes.'-31"
				and clie.id_cliente = "'.$Id_Cliente.'"
			';
		}
		
		if(0 < $Id_Proceso)
		{
			$Consulta .= '
				and ped.id_proceso = "'.$Id_Proceso.'"
			';
		}
		
		$Consulta .= '
			order by fecha_reale asc, ped.id_pedido asc
		';
		
		$Resultado = $this->db->query($Consulta);
		$Trabajos = array();
		$Id_Medicion_v = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Id_Medicion = $Fila['id_pla_medicion'];
			$Trabajos[$Id_Medicion]['tra'] = $Fila['trama'];
			$Trabajos[$Id_Medicion]['nom'] = $Fila['nombre'];
			$Trabajos[$Id_Medicion]['alt'] = $Fila['altura'];
			$Trabajos[$Id_Medicion]['pla'] = $Fila['plancha'];
			$Trabajos[$Id_Medicion]['sis'] = $Fila['sistema'];
			$Trabajos[$Id_Medicion]['ver'] = $Fila['version'];
			$Trabajos[$Id_Medicion]['lin'] = $Fila['lineaje'];
			$Trabajos[$Id_Medicion]['cli'] = $Fila['id_cliente'];
			$Trabajos[$Id_Medicion]['com'] = $Fila['compensacion'];
			$Trabajos[$Id_Medicion]['pro'] = $Fila['codigo_cliente'].'-'.$Fila['proceso'];
			$Trabajos[$Id_Medicion]['fec'] = date('d-m-Y', strtotime($Fila['fecha_reale']));
			
			$Id_Medicion_v[] = $Id_Medicion;
			
		}
		
		
		if(0 < count($Trabajos))
		{
			$Consulta = '
				select *
				from pla_medicion_color
				where id_pla_medicion in ('.implode(',', $Id_Medicion_v).')
			';
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				
				$Trabajos[$Fila['id_pla_medicion']]['col'][$Fila['color']][5] = $Fila['p_5'];
				$Trabajos[$Fila['id_pla_medicion']]['col'][$Fila['color']][25] = $Fila['p_25'];
				$Trabajos[$Fila['id_pla_medicion']]['col'][$Fila['color']][50] = $Fila['p_50'];
				$Trabajos[$Fila['id_pla_medicion']]['col'][$Fila['color']][75] = $Fila['p_75'];
				$Trabajos[$Fila['id_pla_medicion']]['col'][$Fila['color']][100] = $Fila['p_100'];
				
			}
		}
		
		
		return $Trabajos;
		
	}
	
	
}

/* Fin del archivo */