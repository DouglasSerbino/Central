<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class V_preingreso extends CI_Controller
{
	
	/**
	 *Ingreso del preingreso y todos sus elementos en la base de datos.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function index($Id_Pedido = 0)
	{
		
		$Permitido = array('Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
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
				&& $this->session->userdata('codigo_cliente') == $Proceso['codigo_cliente']
			)
			{
				$Variables['Mensaje'] = 'El Pre-Ingreso "'.$Proceso['codigo_cliente'].'-'.$Proceso['proceso'].': '.$Proceso['nombre_proceso'].'" fue agregado exitosamente.';
			}
			else
			{
				$Variables['Mensaje'] = 'El Pre-Ingreso fue agregado exitosamente.';
			}
		}
		
		
		
		//Tipos de impresion para las especificaciones
		$this->load->model('pedidos/tipo_impresion_m', 'timpresion');
		$Variables['Tipos_Impresion'] = $this->timpresion->tipos();
		
		//Modulo para obtener el listado de los tipos de trabajo
		$this->load->model('general/tipos_trabajo_m', 'tipos_t');
		//Solicito la informacion completa
		$Variables['Tipos_Trabajo'] = $this->tipos_t->tipos();


		//Modulo para obtener el listado de los productos para este cliente
		$this->load->model('productos/prod_cliente_m', 'productos');
		//Listado de departamentos activos y con formato especial
		$Productos = $this->productos->listado(
			$this->session->userdata('id_cliente'),
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
		
		
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		
		
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

		$Consulta = '
			select maquina
			from cliente_maquina
			where id_cliente = "'.$this->session->userdata('id_cliente').'"
		';
		$Resultado = $this->db->query($Consulta);

		$Variables['Maquinas'] = array();
		foreach ($Resultado->result_array() as $Fila)
		{
			$Variables['Maquinas'][] = $Fila['maquina'];
		}
		
		
		$this->load->model('pedidos/tiempo_m', 'tiempo');
		$this->tiempo->detener_tiempo(1);
		
		
		$this->load->view('ventas/v_preingreso_repro_v', $Variables);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
	
	
	/**
	 *Trabajos que esten en espera de arreglo por parte del cliente.
	 *@return nada.
	*/
	public function pendientes()
	{
		
		$Permitido = array('Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Trabajos Pendientes',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		/*
		 29-03-2012
		 Codigo cancelado.*/
		//Pedidos que estan en el puesto de este usuario
		$this->load->model('pedidos/preingreso_m', 'pendientes');
		$Variables['Pedidos'] = $this->pendientes->listado(
			'pendientes',
			$this->session->userdata('id_cliente')
		);
		
		
		//Listado de trabajos pendientes
		$this->load->view('pedidos/preingreso_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
}

/* Fin del archivo */