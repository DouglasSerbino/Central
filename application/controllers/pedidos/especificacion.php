<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Especificacion extends CI_Controller {
	
	/**
	 *Ingreso del pedido y todos sus elementos en la base de datos.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function index($Id_Pedido, $tipo = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		
		//Super validacion
		$Id_Pedido += 0;
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		
		//Validacion: Solo los pedidos que estan en proceso pueden ser modificados
		//en sus especificaciones
		//Modelo que verifica si tiene ruta sin finalizar este proceso
		$this->load->model('pedidos/procesando_m', 'procesando');
		//Se realiza la verificacion
		$Estado = $this->procesando->pedido($Id_Pedido);
		
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Modificar Hoja de Planificaci&oacute;n',
			'Mensaje' => '',
			'Id_Pedido' => $Id_Pedido
		);
		
		
		//Extraemos toda la informacion del proceso
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		
		$Variables['Info_Proceso'] = $this->buscar_proc->busqueda_pedido($Id_Pedido);
		
		if(0 == $Variables['Info_Proceso'])
		{
			show_404();
			exit();
		}
		
		
		
		
		//Si es un cliente, no debe ver cosas prohibidas
		$this->ver_sesion_m->solo_un_cliente(
			$Variables['Info_Proceso']['id_cliente']
		);
		
		
		//Tipos de impresion para las especificaciones
		$this->load->model('pedidos/tipo_impresion_m', 'timpresion');
		$Variables['Tipos_Impresion'] = $this->timpresion->tipos();
		
		
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		
		//Especificaciones del pedido a modificar
		$this->load->model('pedidos/especificacion_informacion_m', 'esp_inf');
		
		
		$Variables['Especs'] = $this->esp_inf->pedido($Id_Pedido);
		
		
		//Listado de los materiales recibidos y solicitados
		$this->load->model('pedidos/materiales_m', 'materiales');
		$Variables['Mat_Recibido'] = $this->materiales->recibidos('s');
		$Variables['Mat_Solicitado'] = $this->materiales->solicitados('s');
		
		
		//Materiales de impresion digital
		$this->load->model('pedidos/impresion_digital_m', 'matdigi');
		$Variables['Tipo_Acabado'] = $this->matdigi->tipo_impd_acabado();
		$Variables['Tipo_Material'] = $this->matdigi->tipo_impd_material();
		$Variables['eleccion'] = '';
		/*
		$this->load->model('pedidos/enlaces_m', 'enlace');
		$Variables['Hijo'] = $this->enlace->es_hijo($Id_Pedido);
		*/
		$Variables['tipo'] = $tipo;
		
		
		/*
		if(isset($Variables['Hijo']['id_pedido_pedido']))
		{
			
			$Variables['Especs'] = $this->esp_inf->pedido(
				$Id_Pedido,
				array(
					'matrecgru' => array(),
					'matsolgru' => array()
				)
			);
			
			
			$Variables['Especs'] = $Variables['Especs'] + $this->esp_inf->pedido(
				$Variables['Hijo']['id_ped_primario'],
				array(
					'general' => array(),
					'colores' => array(),
					'distorsion' => array(),
					'guias' => array()
				)
			);
			
		}
		*/



		$Consulta = '
			select maquina
			from cliente_maquina
			where id_cliente = "'.$Variables['Info_Proceso']['id_cliente'].'"
		';
		$Resultado = $this->db->query($Consulta);

		$Variables['Maquinas'] = array();
		foreach ($Resultado->result_array() as $Fila)
		{
			$Variables['Maquinas'][] = $Fila['maquina'];
		}
		
		$this->load->view('pedidos/especificacion_modificar_repro_v', $Variables);
		
		
		
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
	
	
	
	/**
	 *Modificar las especificaciones del pedido.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	function modificar($Id_Pedido = 0, $tipo = '')
	{
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$Id_Pedido += 0;
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		//Extraemos toda la informacion del proceso
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		$Info_Proceso = $this->buscar_proc->busqueda_pedido($Id_Pedido);
		
		if(0 == $Info_Proceso)
		{
			show_404();
			exit();
		}
		
		
		
		
		
		//Si es un cliente, no debe ver cosas prohibidas
		$this->ver_sesion_m->solo_un_cliente(
			$Info_Proceso['id_cliente']
		);
		
		//Validacion: Solo los pedidos que estan en proceso pueden ser modificados
		//en sus especificaciones
		//Modelo que verifica si tiene ruta sin finalizar este proceso
		$this->load->model('pedidos/procesando_m', 'procesando');
		//Se realiza la verificacion
		$Estado = $this->procesando->pedido($Id_Pedido);
		
		
		//Especificaciones del pedido a modificar
		$this->load->model('pedidos/especificacion_informacion_m', 'esp_inf');
		
		
		
		
		//Listado de los materiales recibidos y solicitados
		$this->load->model('pedidos/materiales_m', 'materiales');
		$Materiales['recibido'] = $this->materiales->recibidos_id('s');
		$Materiales['solicitado'] = $this->materiales->solicitados_id('s');
		
		/*
		$this->load->model('pedidos/enlaces_m', 'enlace');
		$Es_Hijo = $this->enlace->es_hijo($Id_Pedido);
		
		
		//Es este pedido hijo de otro?
		
		if(0 == $Es_Hijo)
		{
			*/
			$Especs = $this->esp_inf->pedido($Id_Pedido);
			/*
			//Listado de los tipos de acabados
			$this->load->model('pedidos/impresion_digital_m', 'matdigi');
			$Tipo_Acabado = $this->matdigi->tipo_impd_acabado();
		}
		else
		{
			$Especs = $this->esp_inf->pedido(
				$Id_Pedido,
				array(
					'matrecgru' => array(),
					'matsolgru' => array()
				)
			);
			//print_r($Especs);
			$Tipo_Acabado = array();
		}
		

		if(isset($Es_Hijo['id_pedido_pedido']))
		{
			
			$Especs = $this->esp_inf->pedido(
				$Es_Hijo['id_ped_primario'],
				array(
					'general' => array(),
					'colores' => array(),
					'distorsion' => array()
				)
			);
			//Modificacion de las especificaciones
			$this->load->model('pedidos/especificacion_modificar_m', 'esp_modi');
			$this->esp_modi->modificar(
				$Es_Hijo['id_ped_primario'],
				$Especs,
				array('recibido' => array(), 'solicitado' => array()),
				array()
			);
			
			$Especs = $this->esp_inf->pedido(
				$Id_Pedido,
				array(
					'matrecgru' => array(),
					'matsolgru' => array()
				)
			);
		}
		*/
		
		
		//Modificacion de las especificaciones
		$this->load->model('pedidos/especificacion_modificar_m', 'esp_modi');
		$this->esp_modi->modificar(
			$Id_Pedido,
			$Especs,
			$Materiales
		);



		

		
		if('Ventas' != $this->session->userdata('codigo'))
		{
			if($tipo == 'm')
			{
				header('location: /pedidos/administrar/info/'.$Info_Proceso['id_proceso']);
			}
			elseif($tipo == 'l')
			{
				header('location: /pedidos/preingreso/estado/Pendientes');
			}
			elseif($tipo == 'i')
			{
				header('location: /pedidos/modificar/index/'.$Id_Pedido.'/i');
			}
			else
			{
				header('location: /pedidos/modificar/index/'.$Id_Pedido);
			}
			
		}
		else{
			if('' != $this->session->userdata('id_cliente'))
			{
				header('location: /ventas/v_preingreso/pendientes');
			}
			else
			{
				header('location: /pedidos/preingreso/estado/Pendientes');
			}
		}
		
	}
	
	
	/**
	 *Informacion completa de las especificacion para el pedido seleccionado.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function informacion($Codigo_Cliente = 0, $Proceso = '')
	{
		
		
		if(0 == $Codigo_Cliente)
		{
			$Codigo_Cliente = $this->input->post('cliente');
			$Proceso = $this->input->post('proceso');
		}
		
		$Codigo_Cliente = $this->seguridad_m->mysql_seguro($Codigo_Cliente);
		$Proceso = $this->seguridad_m->mysql_seguro($Proceso);
		
		//Necesito el id del proceso
		$this->load->model('procesos/buscar_proceso_m', 'info');
		$Id_Proceso = $this->info->cliente_proceso(
			$Codigo_Cliente,
			$Proceso
		);
		
		if(0 != count($Id_Proceso))
		{
			
			$Id_Proceso = $Id_Proceso['id_proceso'];
			
			//Especificaciones del pedido solicitado
			$this->load->model('pedidos/especificacion_informacion_m', 'esp_inf');
			$Variables['Ajax'] = $this->esp_inf->ultima($Id_Proceso);
			
			$Variables['Ajax'] = json_encode($Variables['Ajax']);
			
			$this->load->view('ajax_v', $Variables);
			
		}
		
	}
	
	/**
	 *Ingreso del pedido y todos sus elementos en la base de datos.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function ver($Id_Pedido, $tipo = '')
	{
		
		
		
		//Super validacion
		$Id_Pedido += 0;
		if(0 == $Id_Pedido or $tipo == '')
		{
			show_404();
			exit();
		}
		
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Hoja de Planificaci&oacute;n',
			'Mensaje' => '',
			'Id_Pedido' => $Id_Pedido
		);
		/*
		//Es este pedido hijo de otro?
		$this->load->model('pedidos/enlaces_m', 'enlace');
		
		//Quiero cargar los pedidos que fueron creados para este grupo antes
		//que se convirtiera en grupo, eso fue antes del 14-mayo-2012
		$this->load->model('clientes/cliente_grupo_m', 'cligru');
		$Cliente_grupo = $this->cligru->soy_cliente_de_repro();
		$Variables['Padre'] = $this->enlace->es_padre($Id_Pedido);
		
		
		$Ver_Pedido_Viejos = false;
		
		if(0 < count($Cliente_grupo))
		{
			$Ver_Pedido_Viejos = 1;
		}
		*/
		//Extraemos toda la informacion del proceso
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		$Variables['Info_Proceso'] = $this->buscar_proc->busqueda_pedido(
			$Id_Pedido
		);
		
		if(0 == $Variables['Info_Proceso'])
		{
			show_404();
			exit();
		}
		
		
		//Si es un cliente, no debe ver cosas prohibidas
		$this->ver_sesion_m->solo_un_cliente(
			$Variables['Info_Proceso']['id_cliente']
		);
		
		//Informacion del pedido
		$this->load->model('pedidos/pedido_detalle_m', 'pedido_det');
		//Fechas, reprocesos, etc
		$Variables['Pedido'] = $this->pedido_det->pedido($Id_Pedido);
		
		//Modulo para obtener el listado de los tipos de trabajo
		$this->load->model('general/tipos_trabajo_m', 'tipos_t');
		//Solicito la informacion completa
		$Variables['Tipos_Trabajo'] = $this->tipos_t->tipos();
		
		
		
		//Tipos de impresion para las especificaciones
		$this->load->model('pedidos/tipo_impresion_m', 'timpresion');
		$Variables['Tipos_Impresion'] = $this->timpresion->tipos();
		
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		
		//Especificaciones del pedido
		$this->load->model('pedidos/especificacion_informacion_m', 'esp_inf');
		$Variables['Especs'] = $this->esp_inf->pedido($Id_Pedido);
		
		if('' == $Variables['Especs']['general']['embobinado_cara'])
		{
			$Variables['Especs']['colores'] = array();
		}
		
		//Listado de los materiales recibidos y solicitados
		$this->load->model('pedidos/materiales_m', 'materiales');
		$Variables['Mat_Recibido'] = $this->materiales->recibidos('s');
		$Variables['Mat_Solicitado'] = $this->materiales->solicitados('s');
		
		/*
		//Materiales de impresion digital
		$this->load->model('pedidos/impresion_digital_m', 'matdigi');
		$Variables['Tipo_Acabado'] = $this->matdigi->tipo_impd_acabado();
		$Variables['Tipo_Material'] = $this->matdigi->tipo_impd_material();
		*/
		$Variables['tipo'] = $tipo;
		
		
		//$Variables['Hijo'] = $this->enlace->es_hijo($Id_Pedido);
		
		$this->load->view('pedidos/especificacion_encabezado_v', $Variables);
		/*
		if(isset($Variables['Hijo']['id_pedido_pedido']))
		{
			
			$Variables['Especs'] = $this->esp_inf->pedido(
				$Id_Pedido,
				array(
					'matrecgru' => array(),
					'matsolgru' => array()
				)
			);
			$Variables['Especs'] = $Variables['Especs'] + $this->esp_inf->pedido(
				$Variables['Hijo']['id_ped_primario'],
				array(
					'general' => array(),
					'colores' => array(),
					'distorsion' => array(),
					'guias' => array()
				)
			);
		}
		*/
		$this->load->view('pedidos/especificacion_ver_repro_v', $Variables);
		
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
	
	
}

/* Fin del archivo */