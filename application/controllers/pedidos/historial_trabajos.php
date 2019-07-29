<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Historial_trabajos extends CI_Controller {
	
	/**
	 *@param string $Pagina.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($Tipo = 'todos', $Pagina = 1, $Inicio = 0, $Mensaje = '')
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$Inicio += 0;
		$Pagina += 0;
		
		if(1 > $Pagina)
		{
			$Pagina = 1;
		}
		
		if('existe' == $Mensaje)
		{
			$Mensaje = 'No se encontraron Procesos coincidentes.<br />Puede que el Proceso no exista &oacute; haya sido mal digitado.';
		}
		else
		{
			$Mensaje = '';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Historial de Trabajos',
			'Mensaje' => $Mensaje,
			'Redir' => '/pedidos/historial_trabajos/index/1/0',
			'Direccion' => '/pedidos/historial_pedido/index'
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		$Variables['tipo'] = 'todos';
		
		if($_POST)
		{
			$Variables['tipo'] = $this->seguridad_m->mysql_seguro($this->input->post('usuario'));
		}
		if($Tipo  != '')
		{
			$Variables['tipo'] = $Tipo;
		}
		
		//Listado de los clientes de este grupo
		$this->load->model('clientes/busquedad_clientes_m', 'clientes');
		//Busqueda del listado
		$Clientes = $this->clientes->mostrar_clientes();
		
		//Listado de los pedidos finalizados y el total
		$this->load->model('pedidos/lista_pedidos_m', 'lpedidos');
		//Total de pedidos
		$Total_Pedidos = $this->lpedidos->fin_usuario_total(
			$this->session->userdata('id_usuario')
		);
		
		
		
		
		//Listado de pedidos
		$Variables['Listado_Pedidos'] = $this->lpedidos->fin_usuario(
			$this->session->userdata('id_usuario'),
			$Inicio,
			$Variables['tipo']
		);
		
		//Carga del modelo para la paginacion
		$this->load->model('utilidades/paginacion_m', 'paginacion');
		$Variables['Paginacion'] = $this->paginacion->paginar(
			'/pedidos/historial_trabajos/index/'.$Variables['tipo'].'/',
			$Total_Pedidos,
			50,
			$Pagina
		);
		
		$Variables['Pagina'] = $Pagina;
		$Variables['Inicio'] = $Inicio;
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('pedidos/consultar_v', array('Clientes' => $Clientes));
		
		//Listado de los pedidos finalizados por el usuario
		$this->load->view('pedidos/finalizados_usuario_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
}

/* Fin del archivo */