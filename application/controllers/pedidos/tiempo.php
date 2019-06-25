<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tiempo extends CI_Controller {
	
	
	/**
	 *Trabajo con los tiempos, segun indique el usuario
	 *@param string $Accion.
	 *@param string $Id_Pedido.
	 *@param string $Id_Peus.
	 *@return nada.
	*/
	public function accion($Accion = '', $Id_Pedido = '', $Id_Peus = '', $Pagina = '')
	{
		
		
		if('finalizar' != $Accion)
		{
			$this->ver_sesion_m->no_clientes();
		}
		
		//Limpieza.
		if('' == $Accion)
		{
			show_404();
			exit();
		}
		
		
		$Accion = $this->seguridad_m->mysql_seguro($Accion);
		
		$Id_Pedido += 0;
		$Id_Peus += 0;
		
		if(0 == $Id_Pedido)
		{
			$Id_Pedido = $this->seguridad_m->mysql_seguro(
				$this->input->post('rech_pedido')
			);
			$Id_Peus = $this->seguridad_m->mysql_seguro(
				$this->input->post('rech_peus')
			);
			
			
			$Id_Pedido += 0;
			$Id_Peus += 0;
		}
		
		
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		
		
		//Modelo que trabaja con los tiempos
		$this->load->model('pedidos/tiempo_m', 'tiempo');
		
		//Pagina a la que seremos redirigidos
		$Regresar = 'detalle_activo/index/'.$Id_Pedido;
		
		//Accion a realizar
		switch($Accion)
		{
			
			case 'rechazar':
				//Modelo que contiene las razones por las que aplica un rechazo
				$this->load->model('rechazos/razones_m', 'rech_raz');
				$Rech_Razones = $this->rech_raz->listado();
				
				//$Usuario_Asig = 
				$this->tiempo->rechazar($Id_Pedido, $Id_Peus, $Rech_Razones);
				/*
				if(96 == $Usuario_Asig || 95 == $Usuario_Asig)
				{
					$this->crear_pedido_repro($Id_Pedido);
				}
				*/
				$Regresar = 'pendientes';
				break;
			
			case 'iniciar':
				$this->tiempo->iniciar($Id_Pedido, $Id_Peus);
				break;
			
			case 'pausar':
				$this->tiempo->pausar($Id_Pedido, $Id_Peus);
				break;
			
			case 'continuar':
				$this->tiempo->continuar($Id_Pedido, $Id_Peus);
				break;
			
			case 'finalizar':
				//finalizar el pedido para el puesto que lo solicita
				//$Grupo_Asignado = 
				$this->tiempo->finalizar($Id_Pedido, $Id_Peus);
				
				/*
				//Que pasa si el siguiente usuario es un subgrupo?
				if(0 < $Grupo_Asignado)
				{
					$this->crear_pedido_repro($Id_Pedido);
				}
				*/
				$Regresar = 'pendientes';
				break;
			
			case 'retoque':
				//finalizar el pedido para el puesto que lo solicita
				$this->tiempo->retoque($Id_Pedido);
				$Regresar = 'pendientes';
				break;
			
			case 'terminar':
				//terminar el proceso del pedido completo aunque tenga procesos sin iniciar
				$this->tiempo->terminar($Id_Pedido);
				/*
				//Es este pedido hijo de otro pedido?
				$this->load->model('pedidos/enlaces_m', 'enlace');
				$Enlace = $this->enlace->es_hijo(
					$Id_Pedido
				);
				
				if(0 != $Enlace)
				{
					//Cual es el id del peus?
					$this->load->model('pedidos/pedido_usuario_m', 'peus');
					$Peus_Hijo = $this->peus->buscar_id_peus($Enlace['id_ped_primario']);
					$this->tiempo->finalizar($Enlace['id_ped_primario'], $Peus_Hijo['id_peus']);
				}
				*/
				
				$Regresar = 'ajax';
				break;
			
			case 'fecha':
				//Cambio de fecha de entrega
				$this->tiempo->fecha($Id_Pedido);
				
				/*
				//Es probable que este pedido este amarrado a otro, debemos cambiarle
				//su fecha de entrega tambien. No era pensado asi esta accion, pero...
				//Es este pedido padre o hijo?
				$this->load->model('pedidos/enlaces_m', 'enlace');
				$Enlace = $this->enlace->es_hijo(
					$Id_Pedido
				);
				
				if(0 != $Enlace)
				{
					$this->tiempo->fecha($Enlace['id_ped_primario'], false);
				}
				
				
				$Enlace = $this->enlace->es_padre(
					$Id_Pedido
				);
				
				if(0 != $Enlace)
				{
					$this->tiempo->fecha($Enlace['id_ped_secundario'], false);
				}
				*/
				
				$Regresar = 'ajax';
				break;
			
		}
		
		//exit();
		
		if(isset($_POST['cambio_fecha']))
		{
			$this->tiempo->actualizar_cambio_fecha();
			header('location: /herramientas_sis/cambio_fecha/index');				
		}
		
		
		if('ajax' == $Regresar)
		{
			$this->load->view('ajax_v', array('Ajax' => 'ok'));
		}
		elseif(
			'Ventas' == $this->session->userdata('codigo')
			|| 'Plani' == $this->session->userdata('codigo')
			|| 'Gerencia' == $this->session->userdata('codigo')
			|| 'Sistemas' == $this->session->userdata('codigo')
		)
		{
			if('' != $this->session->userdata('id_cliente'))
			{
				header('location: /ventas/v_preingreso/pendientes');
			}
			else
			{
				
				if('' != $Pagina)
				{
					header('location: /pedidos/tra_vendedor/estado/Pendientes/'.$Pagina);
				}
				else
				{
					header('location: /pedidos/preingreso/estado/Pendientes/');
				}
			}
		}
		else
		{
			header('location: /pedidos/'.$Regresar);
		}
		
	}
	
	/**
	 *Funcion que permitira cambiar el tiempo los pedidos asignados a los los usuarios
	**/
	public function cambiar_tiempo()
	{
		$horas = $this->seguridad_m->mysql_seguro($this->input->post('horas'));
		$id_peus = $this->seguridad_m->mysql_seguro($this->input->post('id_peus'));
		$minutos = $this->seguridad_m->mysql_seguro($this->input->post('minutos'));
		$id_usuario = $this->seguridad_m->mysql_seguro($this->input->post('id_usuario'));
		$this->load->model('pedidos/tiempo_m', 'tiempo');
		$Cambiar_tiempo = $this->tiempo->cambiar_tiempo($id_peus, $horas, $minutos, $id_usuario);
		
		if('' != $Cambiar_tiempo)
		{
			$this->load->view('ajax_v', array('Ajax' => $Cambiar_tiempo));
		}
		else
		{
			return '';
		}
	}
	
	
	
	
	
	
	
	
	
	/*
	function crear_pedido_repro($Id_Pedido)
	{
		
		
		$this->load->model('utilidades/crear_carpetas','creacion_carp');
		
		$this->load->model('procesos/buscar_proceso_m', 'info');
		$Proceso = $this->info->busqueda_pedido($Id_Pedido);
		$Id_Proceso_RP = $this->info->id_cliente_proceso(
			94,
			$Proceso['proceso'],
			1
		);
		
		
		if(0 == $Id_Proceso_RP)
		{
			
			$this->load->model('procesos/crear_procesos_m', 'crea_proc');
			
			//Llamamos el modelo para poder almacenar los datos.
			$Id_Proceso_RP = $this->crea_proc->guardar_proceso(
				94,
				$Proceso['proceso'],
				$Proceso['nombre_proceso'],
				1
			);
			
			
			//Creamos la carpeta para el proceso recien creado.
			$this->creacion_carp->creacion_carpetas('', $Id_Proceso_RP);
			
		}
		
		
		
		
		
		//Modelo que verifica si tiene ruta sin finalizar este proceso
		$this->load->model('pedidos/procesando_m', 'procesando');
		//Se realiza la verificacion
		$Estado = $this->procesando->proceso($Id_Proceso_RP);
		//echo '$this->procesando->proceso('.$Id_Proceso_RP.')<br />';
		//echo $Estado.'<br />';
		//echo 'Id_Proceso_RP: '.$Id_Proceso_RP.'<br />';
		if('activo' != $Estado)
		{
			$this->load->model('pedidos/ingresar_m', 'ingresar');
			$Id_Pedido_RP = $this->ingresar->index(
				$Id_Proceso_RP,
				'0000-00-00',
				'No',//Prioridad
				1,
				0,//Id_Usu_Rechazo
				date('Y-m-d')
			);
			
			if('error' == $Id_Pedido_RP)
			{//Si ocurrio un error en el ingreso se notifica al usuario
				show_404();
				exit();
			}
			
			
			
			$this->creacion_carp->creacion_carpetas('/'.$Id_Proceso_RP, $Id_Pedido_RP);
			
			
			//Debo conseguir la ruta de los trabajos de central-g
			$this->load->model('ruta/ruta_grupo_m', 'ruta');
			$Ruta = $this->ruta->generar_ruta(1);
			
			$this->load->model('pedidos/ingresar_ruta_m', 'i_ruta');
			$Ruta = $this->i_ruta->index(
				$Id_Pedido_RP,
				$Ruta,
				date('Y-m-d H:i:s'),
				array(),
				1,
				23
			);
			
			
		}
		else
		{
			
			//No se que comentario poner.
			$this->load->model('pedidos/info_pedido_m', 'info_ped');
			$Id_Pedido_RP = $this->info_ped->procesando_id($Id_Proceso_RP);
			$Id_Pedido_RP = $Id_Pedido_RP['id_pedido'];
			
		}
		
		
		$Consulta = '
			select id_pedido_pedido
			from pedido_pedido
			where id_ped_primario = "'.$Id_Pedido.'" and id_ped_secundario = "'.$Id_Pedido_RP.'"
		';
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows && 0 < $Id_Pedido_RP)
		{
			$this->load->model('pedidos/enlaces_m', 'enlace');
			$this->enlace->enlazar($Id_Pedido, $Id_Pedido_RP);
		}
		
		$this->tiempo->crear_tiempo($Id_Pedido_RP, 0);
		
		
		
		
		
		
	}
	*/
	
	
	
}

/* Fin del archivo */