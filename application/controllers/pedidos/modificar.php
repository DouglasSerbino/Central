<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar extends CI_Controller {
	
	/**
	 *@param string $Id_Proceso.
	 *@return nada.
	*/
	public function index($Id_Pedido, $Tipo_Ir = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Validacion pequenha
		$Id_Pedido += 0;
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		
		//Esta activa la ruta del pedido a modificar?
		
		//Modelo que verifica si tiene ruta sin finalizar este proceso
		$this->load->model('pedidos/procesando_m', 'procesando');
		//Se realiza la verificacion
		$Estado = $this->procesando->pedido($Id_Pedido);
		
		if('activo' != $Estado)
		{//Si el proceso no existe se envia atras y se notifica
			header('location: /pedidos/buscar/index/ruta');
			exit();
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Modificar Pedido',
			'Mensaje' => '',
			'Id_Pedido' => $Id_Pedido,
			'Formulario' => '/pedidos/modificacion/index/'.$Id_Pedido,
			'Tipo_Ir' => $Tipo_Ir
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		$Variables['Estado_Proy'] = 'nop';
		
		
		//Necesito la informacion del proceso a agregar
		$this->load->model('procesos/buscar_proceso_m', 'info');
		//Solicito la informacion completa
		$Variables['Info_Proceso'] = $this->info->busqueda_pedido($Id_Pedido);
		
		//Si quieren hacer trampa
		if('' == $Variables['Info_Proceso'])
		{
			show_404();
			exit();
		}
		
		//Necesito la miniatura
		$Consulta = '
			select url
			from proceso_imagenes
			where id_proceso = "'.$Variables['Info_Proceso']['id_proceso'].'"
			order by id_proceso_imagenes desc
			limit 0, 1
		';
		$Resultado = $this->db->query($Consulta);

		$Variables['Miniatura'] = '';
		if(1 == $Resultado->num_rows())
		{
			$Variables['Miniatura'] = $Resultado->row_array();
			$Variables['Miniatura'] = $Variables['Miniatura']['url'];
		}
		
		//Modulo para obtener el listado de los tipos de trabajo
		$this->load->model('general/tipos_trabajo_m', 'tipos_t');
		//Solicito la informacion completa
		$Variables['Tipos_Trabajo'] = $this->tipos_t->tipos();
		
		
		//Modulo para obtener el listado de los usuarios
		$this->load->model('usuarios/listado_usuario_m', 'usuarios');
		//Solicito la informacion completa
		$Variables['Usuarios'] = $this->usuarios->listadoUsuariosRepro();
		$Variables['UsuariosRepro'] = $this->usuarios->listadoUsuariosRepro();
		$Variables['Dpto_Usuario'] = $this->usuarios->departamento_usuario();
		
		
		//Extraemos las causas, motivos, razones o circunstancias del porque el trabajo puede ser un reproceso
		$this->load->model('pedidos/detalle_reproceso_m', 'reproceso');
		$Variables['Detalle_reproceso'] = $this->reproceso->detalle_reproceso();
		
		
		//Modulo para obtener la ruta de trabajo que debe seguir este grupo
		/*$this->load->model('ruta/ruta_grupo_m', 'ruta');
		//Obtencion de la ruta
		$Variables['Ruta'] = $this->ruta->generar_ruta($this->session->userdata('id_grupo'));*/
		
		
		
		//Modulo para obtener el listado de los departamentos
		$this->load->model('departamentos/listado_m', 'departamentos');
		//Listado de departamentos activos y con formato especial
		$Variables['Departamentos'] = $this->departamentos->buscar_dptos('s','si');
		
		
		//Modulo para obtener el listado de los productos para este cliente
		$this->load->model('productos/prod_cliente_m', 'productos');
		//Listado de departamentos activos y con formato especial
		$Variables['Productos'] = $this->productos->listado(
			$Variables['Info_Proceso']['id_cliente'],
			's'
		);
		
		
		
		//Obtencion de datos de la ruta, cotizacion y fechas
		
		//Ruta de trabajo ingresada
		$this->load->model('ruta/buscar_ruta_m', 'buscar_ruta');
		$Variables['Ruta_Actual'] = $this->buscar_ruta->pedido_usuario($Id_Pedido);
		//print_r($Variables['Ruta_Actual']); exit();
		

		//Listado de las rutas validas para este cliente
		$this->load->model('ruta/ruta_dinamica_m', 'rutad');
		$Variables['Detalle_Rutas'] = $this->rutad->detalle_rutas(
			$Variables['Info_Proceso']['id_cliente']
		);

		//Necesito saber que ruta es la que ha sido almacenada para este pedido
		$Variables['Ruta_Aplicada'] = $this->rutad->cual_ruta(
			implode(',', array_keys($Variables['Ruta_Actual']))
		);

		
		
		//Informacion del pedido
		$this->load->model('pedidos/pedido_detalle_m', 'pedido_det');
		//Fechas, reprocesos, etc
		$Variables['Pedido'] = $this->pedido_det->pedido($Id_Pedido);
		
		$Variables['Cotizacion'] = $this->pedido_det->buscar_cotizacion($Id_Pedido);
		
		$Variables['num_cajas'] = 1;
		$Variables['Redir'] = '/pedidos/modificar/index/'.$Id_Pedido;
 		
		
		//Creacion del Formulario e informacion del proceso
		$this->load->view('pedidos/agregar_modificar_v', $Variables);
		
		
		//Ruta de trabajo
		$this->load->view('pedidos/ruta_v', $Variables);
		
		
		//Hoja de cotizacion
		$this->load->view('pedidos/cotizacion_v', $Variables);
		
		
		//Se carga el pie de pagina de agregar
		$this->load->view('pedidos/agregar_modificar_pie_v');
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
		
	}
	
}

/* Fin del archivo */