<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrar_clientes extends CI_Controller {
	
	/**
	 *Pagina que muestra el listado de los menus de usuario existentes.
	 *Tiene opciones para modificar y desactivar.
	 *@param string $Pagina.
	 *@param string $Inicio.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($Activo = 's', $Pagina = 1, $Inicio = 0, $Mensaje = '')
	{
		/*
		 *Determinamos los departamentos que tendran acceso a esta informacion.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no tendran acceso a este controlador.
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variable
		$Pagina += 0;
		$Inicio += 0;

		if('n' != $Activo)
		{
			$Activo = 's';
		}
		
		if($Mensaje == 'ok')
		{
			$Mensaje = 'El cliente fue modificado exitosamente';
		}
		else
		{
			$Mensaje = '';
		}
		
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables = array(
			'Titulo_Pagina' => 'Administrar Clientes',
			'Mensaje' => $Mensaje,
			'Activo' => $Activo
		);
		
		
		//Modelo que realiza la busqueda de los Clientes.
		$this->load->model('clientes/busquedad_clientes_m', 'busqu_cliente');
		$Variables['Clientes'] = $this->busqu_cliente->rango_clientes($Inicio, $Activo);
		
		//$Tt_Clientes = $this->busqu_cliente->total_clientes($Activo);
		
		//Carga del modelo para la paginacion
		// $this->load->model('utilidades/paginacion_m', 'paginacion');
		// $Variables['Paginacion'] = $this->paginacion->paginar(
		// 	'/clientes/administrar_clientes/index/'.$Activo.'/',
		// 	$Tt_Clientes,
		// 	50,
		// 	$Pagina
		// );



		$this->load->model('clientes/listado_paises_m', 'paisesc');
		$Variables['Paises_C'] = $this->paisesc->paises_cliente();
		
		
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);

		
		$this->load->view('clientes/administrar_clientes_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}


	function ver_info_cliente($Id_Cliente = 0)
	{

		$Info_Cliente = array(
			'general' => array()
		);

		//capturar la informacion del cliente
		//Modelo que realiza la busqueda de los Clientes.
		$this->load->model('clientes/busquedad_clientes_m', 'busqu_cliente');
		$Resultado = $this->busqu_cliente->busquedad_especifica($Id_Cliente);

		if(1 == count($Resultado))
		{
			$Info_Cliente['general'] = $Resultado[0];
		}
		

		$Info_Cliente = $Info_Cliente + $this->busqu_cliente->cliente_caracteristicas($Id_Cliente);


		echo json_encode($Info_Cliente);

	}

	
}

/* Fin del archivo */