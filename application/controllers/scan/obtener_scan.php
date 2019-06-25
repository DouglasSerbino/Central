<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Obtener_scan extends CI_Controller {
	
	/**
	 *Prepara el archivo adjunto para su descarga.
	 *@return nada.
	*/
	public function archivo($Id_PA)
	{
		
		//Validaciones
		$Id_PA += 0;
		
		if(0 == $Id_PA)
		{
			show_404();
			exit();
		}
		
		$this->load->model('scan/obtener_scan_m', 'oscan');
		$Variables['Adjunto'] = $this->oscan->archivo($Id_PA);
		$this->load->view('scan/obtener_scan_v', $Variables);
		
	}
	
}

/* Fin del archivo */