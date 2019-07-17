<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seguimiento_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca los trabajos en carga segun los datos del usuario.
	 *@param string $Fechas.
	 *@param string $Puesto.
	 *@param string $Id_Cliente.
	 *@param string $Trabajo.
	 *@return array.
	*/
	function carga(
		$Fechas,
		$Puesto,
		$Id_Cliente,
		$Trabajo,
		$Fecha_Entr = true,
		$Condic_Grupo = false,
		$Condic_Proceso = '',
		$Id_material = '',
		$Id_Grupo = 0,
		$Pais_C = ''
	)
	{
		
		//echo '<br />"'.$Condic_Proceso.'"<br />';
		//Los datos se almacenaran en esta variable
		$Carga = array(
			'imagenes' => array(),
			'trabajos' => array(),
			'ruta' => array(),
			//'enlaces' => array(),
			'Fechas' => array(),
			'material' => ''
		);
		
		
		//Condiciones segun los datos del formulario
		$Condic_Peus = '';
		$Tiempo = '';
		
		
		if('todos' != $Puesto)
		{
			if('finalizado' != $Trabajo)
			{
				$Condic_Peus = '
					and peus.id_usuario = "'.$Puesto.'"
					and estado != "Terminado"	and estado != "Agregado"
				';
			}
			else
			{
				$Condic_Peus = '
					and peus.id_usuario = "'.$Puesto.'"
				';
			}
			
			$Tiempo = ' and petie.id_usuario = "'.$Puesto.'"';
		}
		
		$Condiciones = array();
		
		if('todos' != $Id_Cliente)
		{
			/*
			if($Id_Cliente == 'Flexo')
			{
				$Condiciones[] = ' and proc.proceso like "F%" ';
			}
			else
			{
			*/
				$Condiciones[] = ' and proc.id_cliente = "'.$Id_Cliente.'"';
			//}
		}
		
		if('' != $Condic_Proceso)
		{
			$Condiciones[] = ' and proceso = "'.$Condic_Proceso.'"';
		}
		
		$Condicion_Pais = '';
		if('' != $Pais_C)
		{
			$Condicion_Pais = ' and clie.pais = "'.$Pais_C.'"';
		}
		
		
		//Se buscan los trabajos que tienen fecha de entrega establecida y conincidan
		//con el rango especifico
		
		$Fecha_Inicio = $Fechas['anho1'].'-'.$Fechas['mes1'].'-'.$Fechas['dia1'];
		$Fecha_Fin = $Fechas['anho2'].'-'.$Fechas['mes2'].'-'.$Fechas['dia2'];
		
		if('finalizado' != $Trabajo)
		{
			if($Fecha_Entr)
			{
				$Condicion_Fecha = '
					and fecha_entrega >= "'.$Fecha_Inicio.'"
					and fecha_entrega <= "'.$Fecha_Fin.'"
				';
			}
			else
			{
				//Se buscan los preingresos de los vendedores que no tienen su fecha de
				//entrega definida
				$Condicion_Fecha = '
					and fecha_entrega = "0000-00-00"
				';
			}
		}
		else
		{
			$Condicion_Fecha = '
				and fecha_reale >= "'.$Fecha_Inicio.'"
				and fecha_reale <= "'.$Fecha_Fin.'"
			';
		}
		
		if('finalizado' == $Trabajo)
		{
			if('todos' == $Puesto)
			{
				$Condiciones[] = ' and fecha_reale != "0000-00-00"';
			}
			else
			{
				$Condicion_Fecha = '
				and estado="Terminado"
				and fecha_fin >= "'.$Fecha_Inicio.' 00:00:00"
				and fecha_fin <= "'.$Fecha_Fin.' 23:59:59"';
			}
		}
		
		if('incompleto' == $Trabajo)
		{
			$Condiciones[] = ' and fecha_reale = "0000-00-00"';
		}
		if('atrasado' == $Trabajo)
		{
			$Condiciones[] = '
				and fecha_entrega < "'.date('Y-m-d').'"
				and fecha_reale = "0000-00-00"
			';
		}
		if('reproceso' == $Trabajo)
		{
			//Alguien puede ayudarme a cambiar esto para que no deba poner directamente el ID
			$Condiciones[] = ' and id_tipo_trabajo = 4';
		}
		
		$Material = '';
		$FROM = '';
		$Condicion = '';
		$Fotopolimero = '';
		if('' != $Id_material && 'Plani' != $this->session->userdata('codigo'))
		{
			if(8 == $Id_material)
			{
				$Fotopolimero = ' or matsoli.id_material_solicitado = "12"';
			}
			
			$Material = 'and (matsoli.id_material_solicitado = "'.$Id_material.'"'.$Fotopolimero.')';
			$FROM = ' ,especificacion_matsolgru matsol, material_solicitado_grupo matgru, material_solicitado matsoli';
			$Condicion = ' and matsol.id_material_solicitado_grupo = matgru.id_material_solicitado_grupo
				and ped.id_pedido = matsol.id_pedido
				and matgru.id_material_solicitado = matsoli.id_material_solicitado
				';
		}
		
		
		
		//Consulta de los trabajos
		$Consulta = '
			select distinct
				codigo_cliente as codcl, proceso as proce,
				peus.id_peus, proc.nombre as prod, ped.id_pedido,
				fecha_entrada as entra, peus.tiempo_asignado,
				fecha_entrega as entre, fecha_reale as reale,
				id_tipo_trabajo as tipo, peus.id_usuario, proc.id_proceso
			from
				cliente clie, procesos proc, pedido ped, pedido_usuario peus
				'.$FROM.'
			where
				clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = peus.id_pedido
			'.implode('', $Condiciones).''.$Condic_Peus.''.$Condicion_Fecha.'
			and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
			'.$Material.$Condicion.''.$Condicion_Pais.'
			order by ped_prioridad asc, fecha_entrega asc, ped.id_pedido asc
		';
		
		
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Carga['trabajos'][$Fila['id_pedido']] = $Fila;
				
				$Consulta2 = '
					select ima.url, proc.id_proceso, ima.nombre_adjunto
					from procesos proc, proceso_imagenes ima
					where proc.id_proceso = ima.id_proceso
					and proc.id_proceso = "'.$Fila['id_proceso'].'"
					order by ima.id_proceso_imagenes asc
				';
			
				//echo '<br />'.$Consulta2.'<br />';
				$Resultado2 = $this->db->query($Consulta2);
				
				if(0 < $Resultado2->num_rows())
				{
					//print_r($Resultado2->result_array());
					foreach($Resultado2->result_array() as $Fila2)
					{
						$Carga['trabajos'][$Fila['id_pedido']]['url'] = $Fila2['url'];
						$Carga['trabajos'][$Fila['id_pedido']]['nombre_adjunto'] = $Fila2['nombre_adjunto'];
					}
				}
				else
				{
					$Carga['trabajos'][$Fila['id_pedido']]['url'] = '';
					$Carga['trabajos'][$Fila['id_pedido']]['nombre_adjunto'] = '';
				}
				
				
			}
		}


		
		//Subconsulta las rutas de trabajo
		$Consulta = '
			select distinct ped.id_pedido
			from cliente clie, procesos proc, pedido ped, pedido_usuario peus
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = peus.id_pedido
			'.implode('', $Condiciones).''.$Condic_Peus.''.$Condicion_Fecha.'
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by fecha_entrega asc, ped.id_pedido asc
		';
		
		
		//Rutas de trabajo
		$Consulta = '
			select ped.id_pedido, id_peus, iniciales, estado, usuario,
			fecha_fin
			from procesos proc, pedido ped, pedido_usuario peus, usuario usu,
			departamentos dpto
			where proc.id_proceso = ped.id_proceso and ped.id_pedido = peus.id_pedido
			and peus.id_usuario = usu.id_usuario and usu.id_dpto = dpto.id_dpto
			'.implode('', $Condiciones).'
			and ped.id_pedido in ('.$Consulta.')'.$Condicion_Fecha.'
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by fecha_entrega asc, ped.id_pedido asc, id_peus asc
		';
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Carga['ruta'][$Fila['id_pedido']][$Fila['id_peus']]['est'] = $Fila['estado'];
				$Carga['ruta'][$Fila['id_pedido']][$Fila['id_peus']]['usu'] = $Fila['usuario'];
				$Carga['ruta'][$Fila['id_pedido']][$Fila['id_peus']]['ini'] = $Fila['iniciales'];
				$Carga['ruta'][$Fila['id_pedido']][$Fila['id_peus']]['fin'] = $Fila['fecha_fin'];
			}
		}
		
		
		
		//Extraer el tiempo de cada usuario.
		if('finalizado' != $Trabajo and 'todos' != $Puesto)
		{
			$Consulta2 = '
				select distinct ped.id_pedido, peus.id_peus
				from cliente clie, procesos proc, pedido ped, pedido_usuario peus
				where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = peus.id_pedido
				'.implode('', $Condiciones).''.$Condic_Peus.''.$Condicion_Fecha.'
				and id_grupo = "'.$this->session->userdata('id_grupo').'"
				order by fecha_entrega asc, ped.id_pedido asc
			';
			
			$Resultado = $this->db->query($Consulta2);
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Datos)
				{
					$id_pedido = $Datos['id_pedido'];
					$Consulta_tiempo = 'select sum(petie.tiempo) as tiempo_usuario,
										peus.id_peus, petie.id_pedido
										from pedido_tiempos petie, pedido_usuario peus, pedido ped
										where peus.id_pedido = '.$id_pedido.'
										and ped.id_pedido = peus.id_pedido
										and ped.id_pedido = petie.id_pedido
										'.$Condic_Peus.''.$Tiempo;
					////echo $Consulta_tiempo.'<br>';
					$Resultado = $this->db->query($Consulta_tiempo);
					if(0 < $Resultado->num_rows())
					{
						foreach($Resultado->result_array() as $Datos_tiempo)
						{
							if($Datos_tiempo['tiempo_usuario'] >= 0)
							{
								$Carga['tiempo'][$id_pedido][$Datos_tiempo['id_peus']]['tiempo'] = $Datos_tiempo['tiempo_usuario'];
							}
							else
							{
								$Carga['tiempo'][$id_pedido][$Datos_tiempo['id_peus']]['tiempo'] = 0;
							}
						}
					}
					
					$Consulta_tiempo2 = 'select petie.inicio, peus.id_peus
										from pedido_tiempos petie, pedido_usuario peus, pedido ped
										where peus.id_pedido = "'.$id_pedido.'"
										and petie.id_pedido = "'.$id_pedido.'"
										and ped.id_pedido = peus.id_pedido
										and ped.id_pedido = petie.id_pedido
										and petie.fin = "0000-00-00 00:00:00"
										'.$Condic_Peus.''.$Tiempo;
					
					$Resultado = $this->db->query($Consulta_tiempo2);
					if(0 < $Resultado->num_rows())
					{
						foreach($Resultado->result_array() as $Datos_inicio)
						{
							if($Datos_inicio['inicio'] != '')
							{
								$Carga['tiempo'][$id_pedido][$Datos_inicio['id_peus']]['inicio'] = $Datos_inicio['inicio'];
							}
							else
							{
								$Carga['tiempo'][$id_pedido][$Datos_inicio['id_peus']]['inicio'] = ' ';
							}
						}
					}
				}
			}
		}
		
		return $Carga;
		
	}
	
	/*
	 *Extraer los materiales que serviran en el filtro de busquedad.
	*/
	function materiales()
	{
		$material = array('1' => 0, '2' => 3, '3' => 4, '4' => 10, '5' => 13, '6' => 15, '7' => 16, '8' => 17, '9' => 14, '10' => 18, '11' => 5, '12' => 19, '13' => 21);
		
		$SQL = array();
		foreach($material as $Datos)
		{
			$SQL[] = ' id_material_solicitado != "'.$Datos.'"';
		}
	
		$Consulta = 'Select id_material_solicitado, material_solicitado from  material_solicitado where '.implode(' and ', $SQL).' order by final asc';
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
	}



	/*Metodos para extraer informaciona 
	 *acerca de los datos de administracion 
	 *de la produccion
	*/

	function obtenerOperadores($departamento){
		$this->db->select('*');
		$this->db->from('usuario');
		$this->db->join('departamentos', 'departamentos.id_dpto = usuario.id_dpto');
		$this->db->where('departamentos.id_dpto', $departamento);
		$this->db->where('usuario.activo', "s");
		$query = $this->db->get();
		$operadores = $query->result_array();
		return $operadores;

	}

	function obtenerTrabajosRealizados($departamento,$mes,$ano){
		$this->db->select('*');
		$this->db->from('cliente as c');
		$this->db->join('procesos as p', 'c.id_cliente = p.id_cliente');
		$this->db->join('pedido as pd', 'p.id_proceso = pd.id_proceso');
		$this->db->join('pedido_usuario as pu', 'pd.id_pedido = pu.id_pedido');
		$this->db->join('usuario as u', 'pu.id_usuario = u.id_usuario');
		$this->db->join('departamentos as d', 'u.id_dpto = d.id_dpto');
		$this->db->where('pu.estado', "Terminado");
		$this->db->where('u.activo', "s");
		$this->db->where('d.id_dpto', $departamento);
		$this->db->where('pu.fecha_fin BETWEEN "'.$ano.'-'.$mes.'-01" AND "'.$ano.'-'.$mes.'-31"');
		$query = $this->db->get();
		$trabajos = $query->result_array();
		return $trabajos;
	}

	function obtenerRechazos($departamento,$mes,$ano){

		// if('Anual' == $Mes)
		// {
		// 	$SQL = 'fecha >= "'.$Anho.'-01-01 00:00:01"
		// 					and fecha <= "'.$Anho.'-12-31 23:59:59"';
		// }
		// else
		// {
		// 	$SQL = 'fecha >= "'.$Anho.'-'.$Mes.'-01 00:00:01"
		// 					and fecha <= "'.$Anho.'-'.$Mes.'-31 23:59:59"';
		// }


		$this->db->select('*');
		$this->db->from('pedido_rechazo as pr');
		$this->db->where('pr.fecha BETWEEN "'.$ano.'-'.$mes.'-01 00:00:01" AND "'.$ano.'-'.$mes.'-31 23:59:59"');
		$query = $this->db->get();
		$rechazos = $query->result_array();
		return $rechazos;

		// $Consulta = '
		// 	select count(distinct fecha) as tt_rechazos, id_usuario
		// 	from pedido_rechazo
		// 	where  '.$SQL.'
		// 	'.$Condicion.'
		// 	group by id_usuario
		// ';

	}

	function obtenerHorasExtras($departamento,$mes,$ano){
		$this->db->select_sum('total_h');
		$this->db->from('extra');
		$this->db->where('fecha BETWEEN "'.$ano.'-'.$mes.'-01" AND "'.$ano.'-'.$mes.'-31"');
		$query = $this->db->get();
		$extras = $query->result_array();
		return $extras;
	}


	/*Metodos para extraer la informacion respectiva a cada usuario*/
	function obtenerTrabajosRealizadosUsuario($id_usuario,$mes,$ano){
		$this->db->select('*');
		$this->db->from('cliente as c');
		$this->db->join('procesos as p', 'c.id_cliente = p.id_cliente');
		$this->db->join('pedido as pd', 'p.id_proceso = pd.id_proceso');
		$this->db->join('pedido_usuario as pu', 'pd.id_pedido = pu.id_pedido');
		$this->db->join('usuario as u', 'pu.id_usuario = u.id_usuario');
		$this->db->where('pu.estado', "Terminado");
		$this->db->where('u.activo', "s");
		$this->db->where('pu.id_usuario', $id_usuario);
		$this->db->where('pu.fecha_fin BETWEEN "'.$ano.'-'.$mes.'-01" AND "'.$ano.'-'.$mes.'-31"');
		$query = $this->db->get();
		$trabajos = $query->result_array();
		return $trabajos;
	}

	function obtenerRechazosUsuario($id_usuario,$mes,$ano){
		$this->db->select('*');
		$this->db->from('cliente as c');
		$this->db->join('procesos as p', 'c.id_cliente = p.id_cliente');
		$this->db->join('pedido as pd', 'p.id_proceso = pd.id_proceso');
		$this->db->join('pedido_rechazo as pr', 'pd.id_pedido = pr.id_pedido');
		$this->db->where('pr.id_usuario', $id_usuario);
		$this->db->where('pr.fecha BETWEEN "'.$ano.'-'.$mes.'-01 00:00:00" AND "'.$ano.'-'.$mes.'-31 23:59:59"');
		$query = $this->db->get();
		$rechazos = $query->result_array();
		return $rechazos;
	}

	function obtenerHorasExtrasUsuario($id_usuario,$mes,$ano){
		$this->db->select_sum('total_h');
		$this->db->from('extra as ex');
		$this->db->join('usuario as u', 'ex.id_usuario = u.id_usuario');
		$this->db->where('ex.id_usuario', $id_usuario);
		$this->db->where('ex.fecha BETWEEN "'.$ano.'-'.$mes.'-01" AND "'.$ano.'-'.$mes.'-31"');
		$query = $this->db->get();
		$extras = $query->result_array();
		return $extras;
	}

	function obtenerTiempoUtilizadoUsuario($id_usuario,$mes,$ano){
		

		$this->db->select_sum('pt.tiempo');
		$this->db->from('pedido_tiempos as pt');
		$this->db->join('usuario as u', 'pt.id_usuario = u.id_usuario');
		$this->db->where('pt.id_usuario', $id_usuario);
		$this->db->where('pt.inicio BETWEEN "'.$ano.'-'.$mes.'-01" AND "'.$ano.'-'.$mes.'-31"');
		$this->db->where('SUBSTRING(pt.inicio, 12, 2) < 17');
		$this->db->where('u.id_grupo', $this->session->userdata('id_grupo'));
		$query = $this->db->get();
		$utilizado = $query->result_array();
		return $utilizado;
		
	}
	
}

/* Fin del archivo */