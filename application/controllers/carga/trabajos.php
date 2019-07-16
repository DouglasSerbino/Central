<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trabajos extends CI_Controller {
	
	
	public function listar($Id_Usuario = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no deberan tener acceso a esta pagina.
		$this->ver_sesion_m->no_clientes();
		
		$Id_Usuario += 0;
		if(0 == $Id_Usuario)
		{
			show_404();
			exit();
		}


		$Trabajos = array();


		$Consulta = '
			select codigo_cliente, proceso, proc.nombre, fecha_entrada, fecha_entrega,
			ped.id_pedido
			from cliente clie, procesos proc, pedido ped, pedido_usuario peus
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = peus.id_pedido and estado != "Terminado" and estado != "Agregado"
			and peus.id_usuario = "'.$Id_Usuario.'"
			order by fecha_entrega asc, ped.id_pedido asc
		';
		$Resultado = $this->db->query($Consulta);

		echo json_encode($Resultado->result_array());

	}
	
	
	public function finalizado($Id_Usuario = '', $Anho = '', $Mes = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no deberan tener acceso a esta pagina.
		$this->ver_sesion_m->no_clientes();
		
		$Id_Usuario += 0;
		if(0 == $Id_Usuario)
		{
			show_404();
			exit();
		}


		$Trabajos = array();


		// $Consulta = '
		// 	SELECT codigo_cliente, proceso, proc.nombre, fecha_entrada, fecha_entrega,
		// 	ped.id_pedido, fecha_reale
		// 	from cliente clie, procesos proc, pedido ped, pedido_usuario peus
		// 	where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
		// 	and ped.id_pedido = peus.id_pedido and estado = "Terminado"
		// 	and peus.id_usuario = "'.$Id_Usuario.'"
		// 	and fecha_fin >= "'.$Anho.'-'.$Mes.'-01"
		// 	and fecha_fin <= "'.$Anho.'-'.$Mes.'-31"
		// 	order by fecha_entrega asc, ped.id_pedido asc
		// ';

		$Consulta ='SELECT c.codigo_cliente, p.proceso, p.nombre, pd.fecha_entrada, pd.fecha_entrega,
							pd.id_pedido, pd.fecha_reale
					FROM cliente as c
					INNER JOIN procesos p ON c.id_cliente = p.id_cliente
					INNER JOIN pedido pd ON p.id_proceso = pd.id_proceso
					INNER JOIN pedido_usuario pu ON pd.id_pedido = pu.id_pedido
					WHERE pu.estado = "Terminado" 
						  AND pu.id_usuario = "'.$Id_Usuario.'"
						  AND pu.fecha_fin BETWEEN "'.$Anho.'-'.$Mes.'-01" AND "'.$Anho.'-'.$Mes.'-31"
					ORDER BY pd.fecha_entrega ASC, pd.id_pedido ASC
				   ';
		$Resultado = $this->db->query($Consulta);

		echo json_encode($Resultado->result_array());

	}




	//***********************************************************
	public function rechazos($Id_Usuario = '', $Anho = '', $Mes = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no deberan tener acceso a esta pagina.
		$this->ver_sesion_m->no_clientes();
		
		$Id_Usuario += 0;
		if(0 == $Id_Usuario)
		{
			show_404();
			exit();
		}


		$Trabajos = array();

		// $Consulta = '
		// 	select codigo_cliente, proceso, proc.nombre, ped.id_pedido, explicacion
		// 	from cliente clie, procesos proc, pedido ped, pedido_rechazo rech
		// 	where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
		// 	and ped.id_pedido = rech.id_pedido
		// 	and rech.id_usuario = "'.$Id_Usuario.'"
		// 	and fecha >= "'.$Anho.'-'.$Mes.'-01 00:00:00"
		// 	and fecha <= "'.$Anho.'-'.$Mes.'-31 23:59:59"
		// 	order by fecha_entrega asc, ped.id_pedido asc
		// ';

		$Consulta = '
			SELECT c.codigo_cliente, p.proceso, p.nombre, pd.id_pedido, pr.explicacion
			FROM cliente c
			INNER JOIN procesos p ON c.id_cliente = p.id_cliente
			INNER JOIN  pedido pd ON p.id_proceso = pd.id_proceso
			INNER JOIN pedido_rechazo pr ON pd.id_pedido = pr.id_pedido
			WHERE pr.id_usuario = "'.$Id_Usuario.'"
			AND fecha BETWEEN "'.$Anho.'-'.$Mes.'-01 00:00:00" AND fecha <= "'.$Anho.'-'.$Mes.'-31 23:59:59"
			ORDER BY fecha_entrega ASC, pd.id_pedido ASC
		';
		$Resultado = $this->db->query($Consulta);

		echo json_encode($Resultado->result_array());

	}
	//***********************************************************




	//***********************************************************
	public function extras($Id_Usuario = '', $Anho = '', $Mes = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no deberan tener acceso a esta pagina.
		$this->ver_sesion_m->no_clientes();
		
		$Id_Usuario += 0;
		if(0 == $Id_Usuario)
		{
			show_404();
			exit();
		}


		$Trabajos = array();


		$Consulta = '
			select fecha, inicio, fin, total_h
			from extra
			where id_usuario = "'.$Id_Usuario.'"
			and fecha >= "'.$Anho.'-'.$Mes.'-01"
			and fecha <= "'.$Anho.'-'.$Mes.'-31"
			order by fecha asc
		';
		$Resultado = $this->db->query($Consulta);

		echo json_encode($Resultado->result_array());

	}
	//***********************************************************

}

/* Fin del archivo */