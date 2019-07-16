<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Preingreso extends CI_Controller {
	
	/**
	 *Ingreso del preingreso y todos sus elementos en la base de datos.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function index($Id_Pedido = 0, $tipo='', $TIngreso = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Id_Pedido += 0;
		
		$Variables = array(
			'Titulo_Pagina' => 'Pre-Ingreso de Pedidos',
			'Mensaje' => '',
			'Id_Pedido' => $Id_Pedido
		);
		
		if(0 < $Id_Pedido)
		{
			$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
			$Proceso = $this->buscar_proc->busqueda_pedido($Id_Pedido);
			
			if(
				0 < count($Proceso)
			)
			{
				$Variables['Mensaje'] = 'El Pre-Ingreso "'.$Proceso['codigo_cliente'].'-'.$Proceso['proceso'].': '.$Proceso['nombre_proceso'].'" fue agregado exitosamente.';
			}
			else
			{
				$Variables['Mensaje'] = 'El Pre-Ingreso fue agregado exitosamente.';
			}
		}

		//Necesito la informacion del proceso a agregar
		//$this->load->model('procesos/buscar_proceso_m', 'info');
		//$Variables['Info_Proceso'] = $this->info->id_proceso(800);
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		$Proceso = $this->buscar_proc->busqueda_pedido($Id_Pedido);

		
		//Si quieren hacer trampa
		// if('' == $Variables['Info_Proceso'])
		// {
		// 	show_404();
		// 	exit();
		// }
		
		
		//Tipos de impresion para las especificaciones
		$this->load->model('pedidos/tipo_impresion_m', 'timpresion');
		$Variables['Tipos_Impresion'] = $this->timpresion->tipos();
		
		//Modulo para obtener el listado de los tipos de trabajo
		$this->load->model('general/tipos_trabajo_m', 'tipos_t');
		//Solicito la informacion completa
		$Variables['Tipos_Trabajo'] = $this->tipos_t->tipos();
		
		//Listado de los materiales recibidos y solicitados
		$this->load->model('pedidos/materiales_m', 'materiales');
		$Variables['Mat_Recibido'] = $this->materiales->recibidos('s');
		$Variables['Mat_Solicitado'] = $this->materiales->solicitados('s');
		
		
		//Materiales de impresion digital
		$this->load->model('pedidos/impresion_digital_m', 'matdigi');
		$Variables['Tipo_Acabado'] = $this->matdigi->tipo_impd_acabado();
		$Variables['Tipo_Material'] = $this->matdigi->tipo_impd_material();
		
		//Listado de las rutas validas para este cliente
		$this->load->model('ruta/ruta_dinamica_m', 'rutad');
		$Variables['Detalle_Rutas'] = $this->rutad->detalle_rutas($Proceso['id_cliente']);
		
		//Ruta Actual
		$Variables['Ruta_Actual'] = array();

		//Departamentos
		//Modulo para obtener el listado de los departamentos
		$this->load->model('departamentos/listado_m', 'departamentos');
		//Listado de departamentos activos y con formato especial
		$Variables['Departamentos'] = $this->departamentos->buscar_dptos('s','si');
		//Departamento Usuario
		//Modulo para obtener el listado de los usuarios
		$this->load->model('usuarios/listado_usuario_m', 'usuarios');
		//Solicito la informacion completa
		$Variables['Usuarios'] = $this->usuarios->listado('s');
		$Variables['Dpto_Usuario'] = $this->usuarios->departamento_usuario();
		
		$this->load->view('encabezado_v', $Variables);
		$this->load->view('ventas/preingreso_repro_v', $Variables);
		$this->load->view('pie_v');
		
	}
	
	
	
	/**
	 *Valida los estados posibles del proceso a ingresar:
	 *-Nuevo.
	 *-En Proceso.
	 *@param string $Proceso.
	 *@param string $Id_Cliente.
	 *@return nada.
	*/
	function validar_proceso($Proceso = '', $Id_Cliente = '')
	{
		
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		
		if('' == $Proceso)
		{
			$Proceso = $this->seguridad_m->mysql_seguro(
				$this->input->post('proceso')
			);
			
			$Id_Cliente = $this->input->post('cliente');
		}
		$Id_Cliente += 0;
		
		
		$this->load->model('ventas/preingreso_m', 'preingreso');
		$Variables['Ajax'] = $this->preingreso->validar_proceso($Proceso, $Id_Cliente);
		
		$this->load->view('ajax_v', $Variables);
		
	}
	
	
	
	/**
	 *Guarda el preingreso en la base de datos.
	 *Hace muchas cosas: Crear Proceso, Crear Pedido, Crear Carpetas, Ingresar Ruta
	 *previa, Agregar Cotizacion, Agregar Especificaciones.
	 *@return nada.
	*/
	public function ingresar()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Limpiamos las variables
		$Id_Cliente = $this->input->post('cliente');
		
		$Id_Cliente += 0;
		if(0 == $Id_Cliente)
		{
			show_404();
			exit();
		}
		
		$UsuarioSP = '';
		if(isset($_POST['usuSP']))
		{
			$UsuarioSP = $this->seguridad_m->mysql_seguro(
				$this->input->post('usuSP')
			);
		}
		
		$Codigo_Cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('codigo_cliente')
		);
		
		$Proceso = $this->seguridad_m->mysql_seguro(
			$this->input->post('proceso')
		);
		
		$Producto= $this->seguridad_m->mysql_seguro(
			$this->input->post('producto')
		);
		
		
		$Id_Proceso = 0;
		
		/**Todos lo modelos necesarios en el ingreso......**/
		$this->load->model('pedidos/tiempo_m',											'tiempo');
		$this->load->model('pedidos/enlaces_m',											'enlace');
		$this->load->model('ruta/ruta_grupo_m',											'ruta');
		$this->load->model('pedidos/ingresar_m',										'ingresar');
		$this->load->model('pedidos/procesando_m',									'procesando');
		$this->load->model('observaciones/guardar_m',								'g_observ');
		$this->load->model('pedidos/ingresar_ruta_m',								'i_ruta');
		$this->load->model('productos/prod_cliente_m',							'productos');
		$this->load->model('clientes/cliente_grupo_m',							'cligru');
		$this->load->model('procesos/buscar_proceso_m',							'info');
		$this->load->model('procesos/crear_procesos_m',							'crea_proc');
		$this->load->model('utilidades/crear_carpetas',							'creacion_carp');
		$this->load->model('pedidos/impresion_digital_m',						'matdigi');
		$this->load->model('pedidos/ingresar_cotizacion_m',					'cotizacion');
		$this->load->model('pedidos/especificacion_ingresar_m',			'esp_ing');
		$this->load->model('pedidos/especificacion_modificar_m',		'esp_modi');
		$this->load->model('pedidos/especificacion_informacion_m',	'esp_inf');
		
		
		
		//Fecha corta
		$Fecha = date('Y-m-d');
		//Fecha larga
		$Fecha_Hora = date('Y-m-d H:i:s');
		
		//Limpieza de variables
		$Fecha_Entrega = $this->seguridad_m->mysql_seguro(
			$this->input->post('fecha_entrega')
		);
		$Fecha_Entrega = $this->fechas_m->fecha_dmy_ymd($Fecha_Entrega);
		//Validacion paranoica
		if('' == $Fecha_Entrega)
		{
			show_404();
			exit();
		}
		
		
		$Tipo_Trabajo = $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo_trabajo')
		);
		$Id_Usu_Rechazo = 0;
		
		
		/*****************************************************/
		//** Trabajos con el Proceso **//
		if('' != $Producto)
		{
			//Si recibo un valor de esta casilla, significa que no estaba deshabilitada
			//y por tanto es para crear pedido nuevo... que gran logica!
			
			
			//Llamamos el modelo para poder almacenar los datos.
			$Id_Proceso = $this->crea_proc->guardar_proceso(
				$Id_Cliente,
				$Proceso,
				$Producto,
				$this->session->userdata('id_grupo')
			);
			
			
			//Solicito la informacion completa
			$Proceso = $this->info->id_proceso($Id_Proceso);
			$Proceso = $Proceso['proceso'];
			
			
			//Creamos la carpeta para el proceso recien creado.
			$this->creacion_carp->creacion_carpetas('', $Id_Proceso);
			
		}
		else
		{
			
			//Solicito la informacion completa
			$Id_Proceso = $this->info->cliente_proceso(
				$Codigo_Cliente,
				$Proceso
			);
			
			if('' != $Id_Proceso)
			{
				$Id_Proceso = $Id_Proceso['id_proceso'];
			}
			else
			{
				$Id_Proceso = 0;
			}
			
		}
		
		$Id_Proceso += 0;
		
		if(0 == $Id_Proceso)
		{
			show_404();
			exit();
		}
		
		
		//Se realiza la verificacion
		$Estado = $this->procesando->proceso($Id_Proceso);
		
		if('activo' == $Estado)
		{
			show_404();
			exit();
		}
		
		
		
		/*****************************************************/
		//** Especificaciones para el Pedido **//
		
		$Especs = $this->esp_inf->ultima($Id_Proceso);
		
		
		/*****************************************************/
		//** Ingreso del pedido en la base de datos **//
		
		
		
		
		//Ingreso del pedido
		$Id_Pedido = $this->ingresar->index(
			$Id_Proceso,
			$Fecha_Entrega,
			'No',//Prioridad
			$Tipo_Trabajo,
			0,//Id_Usu_Rechazo
			$Fecha/*,
			'Nuevo'*/
		);
		
		if('error' == $Id_Pedido)
		{//Si ocurrio un error en el ingreso se notifica al usuario
			show_404();
			exit();
		}
		
		
		
		/*****************************************************/
		//** Ruta del pedido **//
		
		//Necesito conocer el camino que lleva la ruta de trabajo
		
		//Obtencion de la ruta
		//$Ruta = $this->ruta->generar_ruta($this->session->userdata('id_grupo'));
		
		//Ingreso de la Ruta
		$Ruta = $this->i_ruta->index(
			$Id_Pedido,
			array(),
			$Fecha_Hora,
			'Asignado'
		);
		
		
		//Se debe medir el tiempo de respuesta.
		//Se creara un tiempo iniciado para el usuario que quedo asignado.
		$this->tiempo->crear_tiempo($Id_Pedido, 0);
		
		
		
		/*****************************************************/
		//** Cotizacion **//
		
		//Listado de departamentos activos y con formato especial
		$Productos = $this->productos->listado(
			$Id_Cliente,
			's'
		);
		
		//Modulo para almacenar la cotizacion
		
		//Ingreso de la cotizacion
		$Cotizacion = $this->cotizacion->index($Id_Pedido, $Productos, $Id_Cliente);
		
		
		/*****************************************************/
		//** Observacion **//
		
		
		//Limpieza de variables
		$Observacion = $this->seguridad_m->mysql_seguro(
			$this->input->post('observaciones')
		);
		
		$this->g_observ->index($Id_Pedido, $Observacion, $Fecha_Hora);
		
		
		
		
		
		
		/*****************************************************/
		//**Ingreso de las Especificaciones para el Pedido **//
		
		
		$Especs['colores'] = array();
		
		
		$this->esp_ing->ingreso($Especs, $Id_Pedido);
		//Se creo un record con las especificacion anteriores o en blanco segun el proceso.
		//El paso siguiente es modificarla con los nuevos datos.
		
		
		$Materiales['recibido'] = array();
		$Materiales['solicitado'] = array();
		
		//Listado de los tipos de acabados
		$Tipo_Acabado = $this->matdigi->tipo_impd_acabado();
		
		
		//Modificacion de las especificaciones
		$this->esp_modi->modificar(
			$Id_Pedido,
			$Especs,
			$Materiales,
			$Tipo_Acabado
		);
		
		
		
		
		//***********************************//
		//Carpeta para el pedido
		$this->creacion_carp->creacion_carpetas('/'.$Id_Proceso, $Id_Pedido);
		
		
		
		
		
		
		
		
		
		
		
		/*
		$Cliente_grupo = $this->cligru->soy_cliente_de_repro();
		
		
		if(0 < count($Cliente_grupo))
		{
			
			//Ya existe este proceso en repro?
			$Id_Proceso_RP = $this->info->id_cliente_proceso(
				$Cliente_grupo['id_cliente'],
				$Proceso,
				1
			);
			
			
			if(0 == $Id_Proceso_RP)
			{
				
				//Llamamos el modelo para poder almacenar los datos.
				$Id_Proceso_RP = $this->crea_proc->guardar_proceso(
					$Cliente_grupo['id_cliente'],
					$Proceso,
					$Producto,
					1,
					true
				);
				
				
				//Creamos la carpeta para el proceso recien creado.
				//$this->creacion_carp->creacion_carpetas('', $Id_Proceso_RP);
				
			}
			
			
			$Id_Pedido_RP = $this->ingresar->index(
				$Id_Proceso_RP,
				$Fecha_Entrega,
				'No',//Prioridad
				$Tipo_Trabajo,
				0,//Id_Usu_Rechazo
				$Fecha
			);
			
			$this->enlace->enlazar($Id_Pedido, $Id_Pedido_RP);
			
			
			$Ruta = $this->ruta->generar_ruta(1);
			$Ruta = $this->i_ruta->index(
				$Id_Pedido_RP,
				$Ruta,
				$Fecha_Hora,
				array(),
				1,
				23
			);
			
			
			$this->tiempo->crear_tiempo($Id_Pedido_RP, 0);
			
			$this->creacion_carp->creacion_carpetas('/'.$Id_Proceso_RP, $Id_Pedido_RP);
			
			
			//Listado de departamentos activos y con formato especial
			$Productos = $this->productos->listado(
				$Cliente_grupo['id_cliente'],
				's',
				1
			);
			
			
			
			//Ingreso de la cotizacion
			$Cotizacion = $this->cotizacion->index($Id_Pedido_RP, $Productos, $Cliente_grupo['id_cliente'], false, true);
			
		}*/
		
		
		
		
		if('' == $this->session->userdata('id_cliente'))
		{
			header('location: /ventas/preingreso/index/'.$Id_Pedido);
		}
		else
		{
			header('location: /ventas/v_preingreso/index/'.$Id_Pedido);
		}
		
	}
	
	
	/**
	 *Aca mostraremos el nombre del cliente.
	 *@return nombre del cliente.
	*/
	public function busquedad_cliente($Codigo_Cliente)
	{
		
		$mostrar_clientes = '';
		//Modelo que realiza la busqueda de los clientes.
		$this->load->model('procesos/proceso_cli_m', 'proce_cliente');
		//proce_cliente == Proceso_cli_m
		if($Codigo_Cliente != '')
		{
			//Obtencion del nombre del cliente.
			$mostrar_clientes = $this->proce_cliente->buscar_informacion_cliente($Codigo_Cliente);
		}
		
		echo $mostrar_clientes;
	}
	
	
}

/* Fin del archivo */