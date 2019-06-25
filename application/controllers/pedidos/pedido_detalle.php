<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_detalle extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos los procesos.
	 *@param string $Id_Proceso.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($Id_Pedido = '')
	{
		
		//Limpiamos las variables
		$Id_Pedido += 0;
		
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		//Llamamos al modelo para mostrar la informacion del proceso.
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		$this->load->model('ruta/buscar_ruta_m', 'buscar_rut');
		$this->load->model('pedidos/pedido_detalle_m', 'ped_det');
		$this->load->model('pedidos/pedido_pedido_m', 'ped_ped');
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Detalle de Pedido',
			'Mensaje' => '',
			'Id_Pedido' => $Id_Pedido,
			'Redir' => '/pedidos/pedido_detalle/index/'.$Id_Pedido,
			'Info_Proceso' => 'asas'
		);
		
		/*
		//Quiero cargar los pedidos que fueron creados para este grupo antes
		//que se convirtiera en grupo, eso fue antes del 14-mayo-2012
		$this->load->model('clientes/cliente_grupo_m', 'cligru');
		$Cliente_grupo = $this->cligru->soy_cliente_de_repro();
		
		//Es este pedido hijo de otro?
		$this->load->model('pedidos/enlaces_m', 'enlace');
		$Variables['Padre'] = $this->enlace->es_padre($Id_Pedido);
		
		
		$Ver_Pedido_Viejos = false;
		
		if(0 < count($Cliente_grupo) && 0 === $Variables['Padre'])
		{
			$Ver_Pedido_Viejos = 1;
		}
		*/
		//Extraemos toda la informacion del proceso
		$Variables['Info_Proceso'] = $this->buscar_proc->busqueda_pedido(
			$Id_Pedido
		);
		
		if(0 == $Variables['Info_Proceso'])
		{
			show_404();
			exit();
		}
		
		
		$Variables['Info_Proceso_T'] = $Variables['Info_Proceso']['codigo_cliente'];
		$Variables['Info_Proceso_T'] .= '-'.$Variables['Info_Proceso']['proceso'];
		$Variables['Info_Descripcion_T'] = $Variables['Info_Proceso']['nombre_proceso'];
		
		
		
		//Si es un cliente, no debe ver cosas prohibidas
		$this->ver_sesion_m->solo_un_cliente(
			$Variables['Info_Proceso']['id_cliente']
		);
		
		
		//Modulo para obtener el listado de los productos para este cliente
		$this->load->model('productos/prod_cliente_m', 'productos');
		//Listado de departamentos activos y con formato especial
		$Productos = $this->productos->listado(
			$Variables['Info_Proceso']['id_cliente'],
			's'
		);
		
		$Variables['Productos'] = array();
		foreach($Productos as $Fila)
		{
			$Variables['Productos'][$Fila['id_producto']] = array(
				'precio' => $Fila['precio'],
				'producto' => $Fila['producto']
			);
		}
		
		
		
		$Variables['ruta_trabajo'] = $this->buscar_rut->buscar_ruta(
			$Id_Pedido
		);
		
		/*$Variables['ruta_trabajo2'] = array();
		if(is_array($Variables['Padre']))
		{
			$Variables['ruta_trabajo2'] = $this->buscar_rut->buscar_ruta(
				$Variables['Padre']['id_ped_secundario'],
				true
			);
		}*/
		$Variables['Cotizacion'] = $this->ped_det->buscar_cotizacion($Id_Pedido);
		$Variables['Observaciones'] = $this->ped_det->observaciones(
			$Id_Pedido
		);
		
		//Consumos de Materia prima
		$this->load->model('pedidos/pedido_consumo_m', 'pconsumo');
		$Variables['Consumo'] = $this->pconsumo->detalle($Id_Pedido);
		
		
		$Variables['num_cajas'] = 3;
		
		
		
		$this->load->model('planchas/lecturas_m', 'lecturas');
		$Variables['Referencias'] = $this->lecturas->referencias($Variables['Info_Proceso']['id_cliente']);
		$Variables['Compensacion'] = $this->lecturas->compensacion();
		$Variables['Plancha'] = $this->lecturas->planchas();
		$Variables['Sistema'] = $this->lecturas->sistema();
		$Variables['Altura'] = $this->lecturas->altura();
		$Variables['Trama'] = $this->lecturas->trama();
		$Variables['Lineaje'] = $this->lecturas->lineaje();
		
		$Variables['Medicion_Ped'] = $this->lecturas->pedido($Id_Pedido);
		$Variables['Medicion_Ped_Ant'] = $this->lecturas->pedido_anterior($Id_Pedido, $Variables['Info_Proceso']['id_proceso']);
		
		
		$this->load->model('clientes/busquedad_clientes_m', 'busqu_cliente');
		$Variables['Info_Cliente'] = $this->busqu_cliente->cliente_caracteristicas(
			$Variables['Info_Proceso']['id_cliente']
		);
		
		
		
		//print_r($Variables);

		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('pedidos/pedido_detalle_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */