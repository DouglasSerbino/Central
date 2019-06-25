<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_imp extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index($tipo = '', $dia = '', $mes = '', $anho = '', $id_usuario = 0)
	{
		//Departamentos que tiene acceso a esta area.
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'SAP' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Pequenha validacion
		$dia += 0;
		$mes += 0;
		$anho += 0;
		//Si el resultado de la suma anterio es igual a cero, significa que el valor
		//recibido era una cadena de letras
		if(0 == $dia or 0 == $mes or 0 == $anho or '' == $tipo)
		{
			//Muestro un mensaje de error
			show_404();
			//Evito que se siga ejecutando el script
			exit();
		}
		
		if($dia < 10)
		{
			$dia = '0'.$dia;
		}
		if($mes < 10)
		{
			$mes = '0'.$mes;
		}
		
		$fecha = $anho.'-'.$mes.'-'.$dia;
		//Cargamos el modelo para poder mostrar la informacion.
		$this->load->model('extras/extra_agr_m', 'ext_agr');
		//Horas extras por usuario
		$buscar_extras = $this->ext_agr->Buscar_extras($tipo, $fecha, $id_usuario);

		if($this->session->userdata('contra_ok') == 'ok')
		{
			//Variables necesarias en el encabezado
			$Variables = array(
				'id_usuario' => $id_usuario,
				'dia' => $dia,
				'mes' => $mes,
				'anho' => $anho,
				'buscar_extras' => $buscar_extras,
				'tipo' => $tipo
			);
			//Cargamos la vista
			$this->load->view('extras/extra_imp_v', $Variables);

		}
		else
		{
			header('location: /extras/extra_con');
		}
	}
}

/* Fin del archivo */