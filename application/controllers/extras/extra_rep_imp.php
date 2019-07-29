<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_rep_imp extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index($dia1='', $mes1='', $anho1='', $dia2='', $mes2='', $anho2='')
	{
		/*
		 *Departamentos que tendran acceso a este reporte.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'SAP' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Los clientes no deberan acceder a este reporte.
		$this->ver_sesion_m->no_clientes();
		
		//Pequenha validacion
		$dia1 += 0;
		$mes1 += 0;
		$anho1 += 0;
		$dia2 += 0;
		$mes2 += 0;
		$anho2 += 0;
		//Si el resultado de la suma anterio es igual a cero, significa que el valor
		//recibido era una cadena de letras
		if(0 == $dia1 or 0 == $mes1 or 0 == $anho1 or 0 == $dia2 or 0 == $mes2 or 0 == $anho2)
		{
			//Muestro un mensaje de error
			show_404();
			//Evito que se siga ejecutando el script
			exit();
		}
		//Cargamos el modelo para poder realizar la verificacion.
		$this->load->model('extras/extra_rep_m', 'ext_rep');
		$HExtras = $this->ext_rep->mostrar_extras($dia1, $mes1, $anho1, $dia2, $mes2, $anho2);
		$Administradores = $this->ext_rep->mostrar_admon($HExtras);
		//Verificamos si la persona que quiere acceder se ha auntenticado
		//Como un usuario que tiene acceso a esta area.
		if($this->session->userdata('contra_ok') == 'ok')
		{
		
			$fecha1 = $dia1.'-'.$mes1.'-'.$anho1;
			$fecha2 = $dia2.'-'.$mes2.'-'.$anho2;
			//Variables necesarias en el encabezado
			$Variables = array(
				'Titulo_Pagina' => '',
				'Mensaje' => '',
				'fecha1' => $fecha1,
				'fecha2' => $fecha2,
				'HExtras' => $HExtras
			);
			
			$this->load->view('extras/extra_rep_imp_v', $Variables);
		}
		else
		{
			header('location: /extras/extra_con');
		}
	}
}

/* Fin del archivo */