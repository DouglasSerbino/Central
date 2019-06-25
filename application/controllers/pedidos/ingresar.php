<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingresar extends CI_Controller {
	
	/**
	 *Ingreso del pedido y todos sus elementos en la base de datos.
	 *@param string $Id_Proceso.
	 *@return nada.
	*/
	public function index($Id_Proceso)
	{


		//print_r($_POST); exit();
		
		
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Super validacion
		$Id_Proceso += 0;
		if(0 == $Id_Proceso)
		{
			show_404();
			exit();
		}

		
		
		//Carga del modelo validador del proceso
		$this->load->model('procesos/buscar_proceso_m', 'buscar');
		//Verificamos la existencia
		$Existe = $this->buscar->id_proceso($Id_Proceso);
		
		
		
		
		if('' == $Existe)
		{//Si el proceso no existe se envia atras y se notifica
			header('location: /pedidos/buscar/index/existe');
			exit();
		}
		
		
		$Id_Cliente = $Existe['id_cliente'];
		
		
		//Modelo que verifica si tiene ruta sin finalizar este proceso
		$this->load->model('pedidos/procesando_m', 'procesando');
		//Se realiza la verificacion
		$Estado = $this->procesando->proceso($Id_Proceso);
		
		if('activo' == $Estado)
		{//Si el proceso no existe se envia atras y se notifica
			header('location: /pedidos/buscar/index/ruta');
			exit();
		}


		//Obtencion de la ruta elegida
		$Id_Ruta = $this->input->post('asigna_ruta');
		$Id_Ruta += 0;
		$this->load->model('ruta/ruta_dinamica_m', 'rutad');
		$Ruta = $this->rutad->obtener($Id_Ruta);

		if(0 == count($Ruta['dptos']))
		{
			show_404();
			exit();
		}
		
		
		
		//Ya que verificamos que todo esta bien, podemos proseguir
		
		
		//Fecha corta
		$Fecha = date('Y-m-d');
		//Fecha larga
		$Fecha_Hora = date('Y-m-d H:i:s');
		
		
		
		
		//Algo muy importante es lo siguiente:
		//La hoja de planificacion se debe crear junto con el pedido y el siguiente
		//paso debe ser modificar la hoja en pocos detalles.
		//Si es proceso nuevo la hoja se crea en blanco.
		//Si ya hubo un ingreso previo se copia la informacion.
		$this->load->model('pedidos/especificacion_informacion_m', 'esp_inf');
		
		
		$Especs = $this->esp_inf->ultima($Id_Proceso);
		
		
		//Se toma la info de la hoja de plani anterior antes de crear el pedido por-
		//que si ingreso el pedido entonces el ultimo pedido sera el recien ingresado.
		//Are you understanding me?
		
		if('' == $Especs['general']['embobinado_cara'])
		{
			$Especs['colores'] = array();
			$Especs['acabado'] = array();
		}
		
		
		//** Ingresamos el pedido en la base de datos **//
		
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
		
		//Los checkbox requieren un trato especial.
		//Hay que comprenderlos.
		if('on' == $this->input->post('prioridad'))
		{
			$Prioridad = 'Si';
		}
		else
		{
			$Prioridad = 'No';
		}

		$Tipo_Trabajo = $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo_trabajo')
		);
		$Id_Usu_Rechazo = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_usu_rechazo')
		);
		

		$Reproceso_razon = 0;
		if(isset($_POST['reproceso_razon']))
		{
			$Reproceso_razon = $this->seguridad_m->mysql_seguro(
				$this->input->post('reproceso_razon')
			);
		}

		
		

		//Ingreso del pedido
		$this->load->model('pedidos/ingresar_m', 'ingresar');
		$Id_Pedido = $this->ingresar->index(
			$Id_Proceso,
			$Fecha_Entrega,
			$Prioridad,
			$Tipo_Trabajo,
			$Id_Usu_Rechazo,
			$Fecha,
			$Existe['id_cliente'],
			$Reproceso_razon
		);
		
		
		
		if('error' == $Id_Pedido)
		{//Si ocurrio un error en el ingreso se notifica al usuario
			header('location: /pedidos/buscar/index/ierror');
			exit();
		}
		
		
		
		
		//Ingreso de las especificaciones
		$this->load->model('pedidos/especificacion_ingresar_m', 'esp_ing');
		$this->esp_ing->ingreso($Especs, $Id_Pedido);
		
		
		//Creamos la carpeta para el pedido recien creado.
		$this->load->model('utilidades/crear_carpetas','creacion_carp');
		$this->creacion_carp->creacion_carpetas('/'.$Id_Proceso, $Id_Pedido);
		
		

		//Se ingresa la ruta
		$this->load->model('pedidos/ingresar_ruta_m', 'i_ruta');
		$Ingreso = $this->i_ruta->index(
			$Id_Pedido,
			$Ruta['dptos'],
			$Fecha_Hora,
			$this->session->userdata('id_grupo'),
			$this->session->userdata('id_dpto')
		);
		//echo $Ingreso; exit();
		
		
		
		//Modulo para obtener el listado de los productos para este cliente
		$this->load->model('productos/prod_cliente_m', 'productos');
		//Listado de departamentos activos y con formato especial
		$Productos = $this->productos->listado(
			$Id_Cliente,
			's'
		);
		
		//Modulo para almacenar la cotizacion
		$this->load->model('pedidos/ingresar_cotizacion_m', 'cotizacion');
		//Ingreso de la cotizacion
		$Cotizacion = $this->cotizacion->index($Id_Pedido, $Productos, $Id_Cliente, true);
		
		
		
		
		
		
		//** Ingresamos la observacion **//
		
		$this->load->model('observaciones/guardar_m', 'g_observ');
		
		$this->g_observ->index(
			$Id_Pedido,
			'Pedido ingresado por: '.$this->session->userdata('nombre'),
			$Fecha_Hora
		);
		
		
		//Limpieza de variables
		$Observacion = $this->input->post('observaciones');
		$Observacion = str_replace('"', "'", $Observacion);
		$Aprobar = 'n';
		if('on' == $this->input->post('apro'))
		{
			$Aprobar = 's';
		}
		$this->g_observ->index($Id_Pedido, $Observacion, $Fecha_Hora, $Aprobar);
		
		
		
		header('location: /pedidos/especificacion/index/'.$Id_Pedido.'/m');

	}
	
}

/* Fin del archivo */