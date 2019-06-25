<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_con extends CI_Controller {
	
	/**
	 *Permitira mostrar una caja de texto para que el usuario ingrese su contrasenha.
	 *@param string $Contrasenha;
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{
		/*
		 *Departamentos que pueden acceder a las horas extras.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'SAP' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Ningun cliente debe tener acceso a esta informacion.
		$this->ver_sesion_m->no_clientes();
		
		if($Mensaje != '')
		{
			$Mensaje = 'Error: Contrase&ntilde;a Incorrecta.';
		}
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Horas Extras',
			'Mensaje' => $Mensaje
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		//Ser carga la vista.
		$this->load->view('extras/extra_con_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
	/**
	 *Permitira validar si el usuario que quiere ingresar existe.
	 *@param string $Contrasenha;
	 *@return nada.
	*/
	public function verificar_con()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos la variable.
		$Contrasenha = $this->seguridad_m->mysql_seguro(
			$this->input->post('contrasena')
		);
		
		if($this->session->userdata('codigo') == 'SAP' and ($_SERVER['REMOTE_ADDR'] != ('192.168.21.11' OR $_SERVER['REMOTE_ADDR'] != '192.168.21.26')))
		{
			//Muestro un mensaje de error
			show_404();
			//Evito que se siga ejecutando el script
			exit();
		}
		
		//Cargamos el modelo para poder realizar la verificacion.
		$this->load->model('extras/extras_m', 'extras');
		$Verificacion = $this->extras->verificar_contra($Contrasenha);
		
		
		if('ok' == $Verificacion)
		{//Si la contrasenha es correcta.
			//Creamos la session.
			$ok = 'ok';
			$this->session->set_userdata(array('contra_ok' => $ok));
			//Lo dirigimos a la pagina de extras.
			header('location: /extras/extras');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar.
			//Mandamos un mensaje de error al usuario.
			header('location: /extras/extra_con/index/error');
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}
}

/* Fin del archivo */

