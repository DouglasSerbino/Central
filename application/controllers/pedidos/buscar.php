<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buscar extends CI_Controller {
	
	/**
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		if('existe' == $Mensaje)
		{
			$Mensaje = 'No se encontraron Procesos coincidentes.<br />Puede que el Proceso no exista &oacute; haya sido mal digitado.';
		}
		elseif('ruta' == $Mensaje)
		{
			$Mensaje = 'No se puede Crear un Pedido Nuevo.<br />Este Proceso tiene una Ruta sin finalizar.';
		}
		elseif('iok' == $Mensaje)
		{
			$Mensaje = 'El pedido fue agregado exitosamente.';
		}
		elseif('ierror' == $Mensaje)
		{
			$Mensaje = 'Ocurri&oacute; un error en el ingreso..';
		}
		elseif('mok' == $Mensaje)
		{
			$Mensaje = 'El pedido fue modificado exitosamente.';
		}
		else
		{
			$Mensaje = '';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Agregar Pedido',
			'Mensaje' => $Mensaje
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('pedidos/buscar_v');
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	
	/**
	 *Buscar si existe el proceso ingresado y no tiene un pedido en ruta.
	 *@param nada.
	 *@return nada.
	*/
	function proceso()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		if(
			'' == $this->input->post('cliente')
			|| '' == $this->input->post('proceso')
		)
		{
			header('location: /pedidos/buscar/index/existe');
			exit();
		}
		
		//Limpieza de variables
		$Id_Cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('cliente')
		);
		$Proceso = $this->seguridad_m->mysql_seguro(
			$this->input->post('proceso')
		);
		
		
		//Carga del modelo validador del proceso
		$this->load->model('procesos/buscar_proceso_m', 'buscar');
		//Verificamos la existencia
		$Id_Proceso = $this->buscar->cliente_proceso($Id_Cliente, $Proceso);
		
		
		if('' == $Id_Proceso || 0 == count($Id_Proceso))
		{//Si el proceso no existe se envia atras y se notifica
			header('location: /pedidos/buscar/index/existe');
			exit();
		}
		
		
		//Modelo que verifica si tiene ruta sin finalizar este proceso
		$this->load->model('pedidos/procesando_m', 'procesando');
		//Se realiza la verificacion
		$Estado = $this->procesando->proceso($Id_Proceso['id_proceso']);
		
		if('activo' == $Estado)
		{//Si el proceso no existe se envia atras y se notifica
			header('location: /pedidos/buscar/index/ruta');
			exit();
		}
		
		
		header('location: /pedidos/agregar/index/'.$Id_Proceso['id_proceso']);
		
	}
	
	
	/**
	 *Opciones para modificar pedidos.
	 *@param $Mensaje.
	 *@return nada.
	*/
	function modificar($Mensaje = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
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
			'Titulo_Pagina' => 'Administracion de Pedido',
			'Mensaje' => $Mensaje,
			'Redir' => '/pedidos/buscar/modificar',
			'Direccion' => '/pedidos/administrar/info'
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Listado de los clientes de este grupo
		$this->load->model('clientes/busquedad_clientes_m', 'clientes');
		//Busqueda del listado
		$Clientes = $this->clientes->mostrar_clientes();
		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('pedidos/consultar_v', array('Clientes' => $Clientes));
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
		
	}
	
	
	/**
	 *Busca los procesos que coincidan con la descripcion proporcionada y
	 *el id_cliente si se especifica.
	 *@param string $Id_Cliente.
	 *@return string.
	*/
	function descripcion($Id_Cliente = '')
	{
		
		//Limpieza de variables
		$Id_Cliente = $this->seguridad_m->mysql_seguro($Id_Cliente);
		$Descripcion = $this->seguridad_m->mysql_seguro(
			$this->input->post('producto')
		);
		
		$this->ver_sesion_m->solo_un_cliente($Id_Cliente);
		
		//Modelo buscador
		$this->load->model('procesos/buscar_proceso_m', 'buscar');
		//Recibe la lista de procesos
		$Info['Ajax'] = $this->buscar->descripcion($Descripcion, $Id_Cliente);
		$Info['Ajax'] = str_replace("\t",' ', $Info['Ajax']);
		
		$this->load->view('ajax_v', $Info);
		
	}
	
	function completo()
	{
		echo $this->input->post('term');
		//Limpieza de variables
		$Id_Cliente = $this->seguridad_m->mysql_seguro($Id_Cliente);
		$Descripcion = $this->seguridad_m->mysql_seguro(
			$this->input->post('producto')
		);
		
		$this->ver_sesion_m->solo_un_cliente($Id_Cliente);
		
		//Modelo buscador
		$this->load->model('procesos/buscar_proceso_m', 'buscar');
		//Recibe la lista de procesos
		$Info['Ajax'] = $this->buscar->descripcion($Descripcion, $Id_Cliente);
		$Info['Ajax'] = str_replace("\t",' ', $Info['Ajax']);
		
		$this->load->view('ajax_v', $Info);
		
	}
	
}

/* Fin del archivo */