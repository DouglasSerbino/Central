<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salir extends CI_Controller {
	
	/**
	 *Cierra la sesion del usuario.
	*/
	public function index()
	{
    
		//Capturo el grupo que esta activo en estos momentos.
		$redirigir = $this->session->userdata('grupo');
		
		//Se destruyen las sesiones acivas.
		$this->session->sess_destroy();
		
    //Redirigimos a la pagina del grupo que esta activo.    
    header('location: /ingresar/grupo/'.$redirigir);
		
	}
}