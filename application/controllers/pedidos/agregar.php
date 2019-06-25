<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar extends CI_Controller {
	
	/**
	 *Agregar pedido, ruta de trabajo y cotizacion en la base de datos.
	 *@param string $Id_Proceso.
	 *@return nada.
	*/
	public function index($Id_Proceso)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Validacion pequenha
		$Id_Proceso += 0;
		if(0 == $Id_Proceso)
		{
			show_404();
			exit();
		}
		
		
		
		//Modelo que verifica si tiene ruta sin finalizar este proceso
		$this->load->model('pedidos/procesando_m', 'procesando');
		//Se realiza la verificacion
		$Estado = $this->procesando->proceso($Id_Proceso);
		
		if('activo' == $Estado)
		{
			//Si el proceso tiene una ruta sin finalizar se redirige
			header('location: /pedidos/buscar/index/ruta');
			exit();
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Agregar Pedido',
			'Mensaje' => '',
			'Formulario' => '/pedidos/ingresar/index/'.$Id_Proceso
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Necesito la informacion del proceso a agregar
		$this->load->model('procesos/buscar_proceso_m', 'info');
		//Solicito la informacion completa
		$Variables['Info_Proceso'] = $this->info->id_proceso($Id_Proceso);
		
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
			where id_proceso = "'.$Id_Proceso.'"
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
		$Variables['Usuarios'] = $this->usuarios->listado('s');
		
		$Variables['Dpto_Usuario'] = $this->usuarios->departamento_usuario();
		//print_r($Variables['Dpto_Usuario']);
		
		$Variables['Detalle_reproceso'] = array();
		
		
		//Listado de las rutas validas para este cliente
		$this->load->model('ruta/ruta_dinamica_m', 'rutad');
		$Variables['Detalle_Rutas'] = $this->rutad->detalle_rutas(
			$Variables['Info_Proceso']['id_cliente']
		);

		//print_r($Variables['Detalle_Rutas']);exit();
		
		
		
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
		
		
		//Extraemos las causas, motivos, razones o circunstancias del porque el trabajo puede ser un reproceso
		$this->load->model('pedidos/detalle_reproceso_m', 'reproceso');
		$Variables['Detalle_reproceso'] = $this->reproceso->detalle_reproceso();
		
		
		$Variables['Ruta_Actual'] = array();
		
		
		
		$Variables['Redir'] = '/pedidos/agregar/index/'.$Id_Proceso;
		$Variables['num_cajas'] = 1;
		
		//Creacion del Formulario e informacion del proceso
		$this->load->view('pedidos/agregar_modificar_v', $Variables);
		
		
		//Ruta de trabajo
		$this->load->view('pedidos/ruta_v', $Variables);
		
		
		//Hoja de cotizacion
		$this->load->view('pedidos/cotizacion_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pedidos/agregar_modificar_pie_v');
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
		
	}
	
}

/* Fin del archivo */