<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cambiar_fecha_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function guardar_solicitud($Informacion)
	{
		//print_r($Informacion);
		$Solicitud = $Informacion['solicitado'];
		$item = array('1'=>'uno', '2' => 'dos', '3'=>'tres');
		for($a=1; $a <= 3; $a++)
		{
			if(isset($Informacion[$item[$a]]))
			{
				
				$Proceso = explode('-', $Informacion[$item[$a]]['proc']);
				$Fecha = $Informacion[$item[$a]]['fecha'];
				$Opcion = $Informacion[$item[$a]]['opcion'];
				
				$Consulta = 'select proc.id_proceso from procesos proc, cliente cli
										where proc.proceso = "'.(trim($Proceso[1])).'"
										and proc.id_cliente = cli.id_cliente
										and cli.codigo_cliente = "'.(trim($Proceso[0])).'"';
				
				$Resultado = $this->db->query($Consulta);
				
				if(0 < $Resultado->num_rows())
				{
					$Consulta = 'insert into sol_cambio_fecha values(null, "'.(trim($Proceso[0])).'", "'.(trim($Proceso[1])).'", "'.(trim($Fecha)).'", "'.(trim($Solicitud)).'" ,"'.(trim($Opcion)).'", "si", "'.$this->session->userdata('id_usuario').'", "'.date('Y-m').'")';
					$Resultado = $this->db->query($Consulta);
				}
			}
		}

		return 'ok';
	}
	
	function info_cambios($Anho, $Mes)
	{
		//if($this->session->userdata('id_grupo'))
		$Consulta = '
				select id_solicitud, codigo_cliente, proceso, fecha, solicita, anho_mes,
				opcion, cam.activo, usu.usuario
				from sol_cambio_fecha cam, usuario usu
				where usu.id_usuario = cam.id_usuario
				and cam.activo = "si"
				and cam.anho_mes = "'.$Anho.'-'.$Mes.'"
				order by id_solicitud desc
		';
		$info = array('si'=>array(),'no' => array());
		$Resultado = $this->db->query($Consulta);
		//print_r($Resultado->result_array());
		foreach($Resultado->result_array() as $Datos)
		{
			$info[$Datos['activo']][$Datos['id_solicitud']]['cod_cliente'] = $Datos['codigo_cliente'];
			$info[$Datos['activo']][$Datos['id_solicitud']]['proceso'] = $Datos['proceso'];
			$info[$Datos['activo']][$Datos['id_solicitud']]['fecha'] = $Datos['fecha'];
			$info[$Datos['activo']][$Datos['id_solicitud']]['solicita'] = $Datos['solicita'];
			$info[$Datos['activo']][$Datos['id_solicitud']]['opcion'] = $Datos['opcion'];
			$info[$Datos['activo']][$Datos['id_solicitud']]['usuario'] = $Datos['usuario'];
			$info[$Datos['activo']][$Datos['id_solicitud']]['anho_mes'] = $Datos['anho_mes'];
			
				
			$Consulta2 = 'select ped.id_pedido, proc.id_proceso, ped.fecha_entrega
				from pedido ped, procesos proc, cliente cli
				where proc.id_proceso = ped.id_proceso
				and proc.proceso = "'.$Datos['proceso'].'"
				and cli.codigo_cliente = "'.$Datos['codigo_cliente'].'"
				and cli.id_cliente = proc.id_cliente
				and id_grupo = "'.$this->session->userdata('id_grupo').'"
				order by ped.id_pedido desc
				';
				//echo $Consulta2;
				$Resultado2 = $this->db->query($Consulta2);
			
				$Datos2 = $Resultado2->row_array();
				$info[$Datos['activo']][$Datos['id_solicitud']]['id_pedido'] = $Datos2['id_pedido'];
				$info[$Datos['activo']][$Datos['id_solicitud']]['id_proceso'] = $Datos2['id_proceso'];
				$info[$Datos['activo']][$Datos['id_solicitud']]['fecha_anterior'] = $Datos2['fecha_entrega'];
			
		}
		
		$Consulta = '
				select cam.id_solicitud, cam.codigo_cliente, cam.proceso, cam.fecha, cam.solicita, cam.anho_mes,
				cam.opcion, cam.activo, usu.usuario
				from sol_cambio_fecha cam, usuario usu
				where cam.anho_mes = "'.$Anho.'-'.$Mes.'"
				and usu.id_usuario = cam.id_usuario
				and cam.activo = "no"
				order by id_solicitud desc
		';
		
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		//print_r($Resultado->result_array());
		foreach($Resultado->result_array() as $Datos)
		{
			$info["no"][$Datos['id_solicitud']]['cod_cliente'] = $Datos['codigo_cliente'];
			$info["no"][$Datos['id_solicitud']]['proceso'] = $Datos['proceso'];
			$info["no"][$Datos['id_solicitud']]['fecha'] = $Datos['fecha'];
			$info["no"][$Datos['id_solicitud']]['solicita'] = $Datos['solicita'];
			$info["no"][$Datos['id_solicitud']]['opcion'] = $Datos['opcion'];
			$info["no"][$Datos['id_solicitud']]['usuario'] = $Datos['usuario'];
			$info["no"][$Datos['id_solicitud']]['anho_mes'] = $Datos['anho_mes'];	
		}
//		print_r($info);
		return $info;
	}
	
	function eliminar_cambio($Proceso, $Cliente, $Tiempo)
	{
		$Consulta = 'delete from sol_cambio_fecha
			where proceso = "'.$Proceso.'"
			and codigo_cliente = "'.$Cliente.'"
			and anho_mes = "'.$Tiempo.'"';
		$Resultado = $this->db->query($Consulta);
		return 'ok';
	}
	
	
	function grafica($Anho)
	{
		$Info = array();
		$Ventas = array();
		$Consulta = '
			select count(codigo_cliente) as venta, anho_mes
			from sol_cambio_fecha
			where anho_mes >= "'.$Anho.'-01" and anho_mes <= "'.$Anho.'-12"
			and activo = "no"
			group by anho_mes
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Ventas[$Fila['anho_mes']] = number_format($Fila['venta'], 0, '.', '');
			}
			//print_r($Ventas);
			for($a = 1; $a<=12; $a++)
			{
				if($a < 10)
				{
					$a = '0'.$a;
				}
				if(isset($Ventas[$Anho.'-'.$a]))
				{
					$Info[$Anho.'-'.$a] = $Ventas[$Anho.'-'.$a];
				}
				else
				{
					$Info[$Anho.'-'.$a] = 0;
				}
			}
		}
		//print_r($Info);
		return $Info;
	}	

}

/* Fin del archivo */