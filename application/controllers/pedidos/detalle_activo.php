<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle_activo extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos los procesos.
	 *@param string $Id_Proceso.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($Id_Pedido = '', $rechazo_info = '')
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$Id_Pedido += 0;
		
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		$Proceso = $this->buscar_proc->busqueda_pedido($Id_Pedido);
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Detalle de Trabajo',//: '.$Proceso['codigo_cliente'].'-'.$Proceso['proceso'],
			'Mensaje' => '',
			'Id_Pedido' => $Id_Pedido,
			'Info_Proceso_T' => $Proceso['codigo_cliente'].'-'.$Proceso['proceso'],
			'Info_Descripcion_T' => $Proceso['nombre_proceso']
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Llamamos al modelo para mostrar la informacion del proceso.
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		//
		$this->load->model('ruta/buscar_ruta_m', 'buscar_rut');
		//
		$this->load->model('pedidos/pedido_detalle_m', 'ped_det');
		//
		$this->load->model('pedidos/pendientes_m', 'pendientes');
		
		$this->load->model('pedidos/tiempo_m', 'tiempo');
		//Modulo para obtener el listado de los usuarios
		$this->load->model('usuarios/listado_usuario_m', 'usuarios');
		//
		$this->load->model('rechazos/razones_m', 'rech_raz');
		
		$this->load->model('pedidos/materiales_m', 'materiales');
		
		//$this->load->model('pedidos/repor_reprocesos_m', 'reportes');
		//Extraemos toda la informacion del proceso
		
		$Variables['Proceso'] = $this->buscar_proc->busqueda_pedido($Id_Pedido);
		
		if(0 == $Variables['Proceso'])
		{
			show_404();
			exit();
		}
		
		
		//Informacion del estado actual de este usuario y este pedido
		$this->load->model('pedidos/pendientes_m', 'pendientes');
		$Variables['Pedido_Usuario'] = $this->pendientes->pedido_usuario($Id_Pedido);
		
		
		if(0 == $Variables['Pedido_Usuario'])
		{
			show_404();
			exit();
		}
		
		$Variables['ruta_trabajo'] = $this->buscar_rut->buscar_ruta($Id_Pedido);
		$Variables['Cotizacion'] = $this->ped_det->buscar_cotizacion($Id_Pedido);
		$Variables['Observaciones'] = $this->ped_det->observaciones($Id_Pedido);
		$Variables['Tiempo'] = $this->tiempo->usuario(
			$Id_Pedido,
			$this->session->userdata('id_usuario')
		);
		
		$Variables['Usuarios'] = $this->usuarios->listado('s', 's');
		$Variables['Rech_Razones'] = $this->rech_raz->listado();
		$Variables['Redir'] = '/pedidos/detalle_activo/index/'.$Id_Pedido;
		$Variables['num_cajas'] = 3;
		
		//Informacion del total de venta y datos del SAP
		$this->load->model('pedidos/pedido_sap_m', 'info_sap');
		
		$sap = $this->info_sap->informacion($Id_Pedido);
		$Variables['SAP'] = $sap;
		
		//Consumos de Materia prima
		$this->load->model('pedidos/pedido_consumo_m', 'pconsumo');
		$Variables['Consumo'] = $this->pconsumo->detalle($Id_Pedido);
		
		//Se llamara esta funcion cuando se creen las hojas de revision
		//Y estas no coincidan en la revision de arte y calidad.
		
		$Variables['rechazo'] = '';
		$Variables['hoja_tipo'] = '';
		$this->load->model('hojas_revision/hojas_revision_m', 'hojas');
		if($rechazo_info != '')
		{
			$rechazo_inf = explode('-', $rechazo_info);
			$Variables['rechazo'] = $rechazo_inf[0];
			$hoja_tipo = $rechazo_inf[1];
			$Variables['hoja_tipo'] = $hoja_tipo;
			//Llamamos la funcion si se vana a comparar las hojas de revision.
			$Variables['campos_v'] = array();//$this->hojas->comparar_hojas($Id_Pedido, $hoja_tipo);
		}
		
		//Mostramos la informacion de las hojas de revision.
		$planificacion = '';
		if($this->session->userdata('id_dpto') == "17")
		{
			$Variables['hoja_revision'] = $this->hojas->buscar_hoja_revision($Id_Pedido);
		}
		else
		{
			$this->load->model('hojas_revision/nueva_revision_m', 'hojas_nueva');
			$Variables['hoja_revision'] = $this->hojas_nueva->hoja_revision($Id_Pedido);
		}
		
		$Variables['tipo_material'] = $this->materiales->tipo_material($Id_Pedido);
		//$Reporte_reproceso = count($this->reportes->Info_reproceso($Id_Pedido));
		$Variables['Reporte'] = 0;//count($Reporte_reproceso);
		//Formulario de busqueda de proceso a ingresar
		
		
		$this->load->model('planchas/lecturas_m', 'lecturas');
		$Variables['Referencias'] = $this->lecturas->referencias($Variables['Proceso']['id_cliente']);
		$Variables['Compensacion'] = $this->lecturas->compensacion();
		$Variables['Plancha'] = $this->lecturas->planchas();
		$Variables['Sistema'] = $this->lecturas->sistema();
		$Variables['Altura'] = $this->lecturas->altura();
		$Variables['Trama'] = $this->lecturas->trama();
		$Variables['Lineaje'] = $this->lecturas->lineaje();
		
		$Variables['Medicion_Ped'] = $this->lecturas->pedido($Id_Pedido);
		$Variables['Medicion_Ped_Ant'] = $this->lecturas->pedido_anterior($Id_Pedido, $Proceso['id_proceso']);
		

		//Modulo para obtener el listado de los productos para este cliente
		$this->load->model('productos/prod_cliente_m', 'productos');
		//Listado de departamentos activos y con formato especial
		$Variables['Productos'] = $this->productos->listado(
			$Variables['Proceso']['id_cliente'],
			's'
		);
		
		$this->load->view('pedidos/detalle_activo_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */