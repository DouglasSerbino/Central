<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rechazo extends CI_Controller {
	
	/**
	 *Pagina que muestra el listado de los menus de usuario existentes.
	 *Tiene opciones para modificar y desactivar.
	 *@param string $Pagina.
	 *@param string $Inicio.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index()
	{
		
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables = array(
			'Titulo_Pagina' =>'QUITAR RECHAZO',
			'Mensaje' => ''
		);
		$this->load->view('encabezado_v', $Variables);
		
		$this->load->model('herramientas_sis/rechazo_m', 'recha');
		$Variables['Rechazos'] = $this->recha->rechazos();
		
		//Pagina que muestra el listado de las unidades existentes y su enlace de
		//ingreso, se adjunta las variables que necesita
		$this->load->view('herramientas_sis/rechazo_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
		
	/**
		Funcion para modificar la informacion de un pedido en transito.
	**/
	public function quitar($pedido = '', $usuario = '')
	{
		$this->ver_sesion_m->no_clientes();
		$this->load->model('herramientas_sis/rechazo_m', 'rech');
		
		//LLamamos el metodo encargado de la modificacion del proceso.
		$Quitar = $this->rech->quitar_rechazo($pedido, $usuario);
		
		
		if('ok' == $Quitar)
		{//Si el proceso se guardo con exito
			//Dirigimos a la pagina de crear pedido, por algo crearon el proceso :)
			header('location: /herramientas_sis/rechazo');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /herramientas_sis/rechazo/');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		
	}
	
}

/* Fin del archivo */