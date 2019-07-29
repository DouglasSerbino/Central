<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_rep extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index()
	{
		
		//Departamentos que tiene acceso a esta area.
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'SAP' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no deberan tener acceso a este reporte.
		$this->ver_sesion_m->no_clientes();
		
		$dia = date("d");
		$mes = date("m");
		$anho = date("Y");
		$pre_anho = '';
		$pre_mes = '';
		if(!$_POST)
		{
			if($pre_mes != '')
			{
				$pre_mes = $pre_mes;
			}
			else
			{
				$pre_mes = date("m");
			}

			if($pre_anho != '')
			{
				$pre_anho = $pre_anho;
			}
			else
			{
				$pre_anho = date("Y");
			}
			
			$dia1 = date("d");
			$mes1 = date("m");
			$anho1 = date("Y");
			$dia2 = date("d");
			$mes2 = date("m");
			$anho2 = date("Y");
			$tipo = 'detalle';
			$mostrar = '';
		}
		
		if($_POST)
		{
			//Limpiamos las variables.
			$dia1 = $this->seguridad_m->mysql_seguro(
				$this->input->post('dia1')
			);
			$mes1 = $this->seguridad_m->mysql_seguro(
				$this->input->post('mes1')
			);
			$anho1 = $this->seguridad_m->mysql_seguro(
				$this->input->post('anho1')
			);
			$dia2 = $this->seguridad_m->mysql_seguro(
				$this->input->post('dia2')
			);
			$mes2 = $this->seguridad_m->mysql_seguro(
				$this->input->post('mes2')
			);
			$anho2 = $this->seguridad_m->mysql_seguro(
				$this->input->post('anho2')
			);
			$tipo = $this->seguridad_m->mysql_seguro(
				$this->input->post('tipo')
			);
			

			$pre_mes = date("m");
			$pre_anho = date("Y");
			
			if($tipo == 'presupuesto')
			{
				$pre_mes = $this->seguridad_m->mysql_seguro(
					$this->input->post('pre_mes')
				);
				
				$pre_anho = $this->seguridad_m->mysql_seguro(
					$this->input->post('pre_anho')
				);
			}
			$mostrar = 'ok';
		}
		
		//Esta parte se cumplira cuando se regrese desde la pagina extra_rep_dep
		if($tipo == 'presupuesto')
		{
			$pre_anho = $pre_anho;
			$pre_mes = $pre_mes;
			$mostrar = 'ok';
		}
		
		//Cargamos el modelo para poder realizar la verificacion.
		$this->load->model('extras/extra_rep_m', 'ext_rep');
		$HExtras = $this->ext_rep->mostrar_extras($dia1, $mes1, $anho1, $dia2, $mes2, $anho2);
		$Administradores = $this->ext_rep->mostrar_admon($HExtras);
		//Cargamos el modelo para poder realizar la verificacion.
		$this->load->model('extras/extras_m', 'extras');			
		$Proyecciones = $this->extras->proyecciones($pre_anho, $pre_mes);
		
		//Verificamos si la persona que quiere acceder se ha auntenticado
		//Como un usuario que tiene acceso a esta area.
		if($this->session->userdata('contra_ok') == 'ok')
		{
			//Variables necesarias en el encabezado
			$Variables = array(
				'Titulo_Pagina' => 'Reporte General de Horas Extras',
				'Mensaje' => '',
				'tipo' => $tipo,
				'dia1' => $dia1,
				'mes1' => $mes1,
				'anho1' => $anho1,
				'dia2' => $dia2,
				'mes2' => $mes2,
				'anho2' => $anho2,
				'tipo' => $tipo,
				'pre_mes' => $pre_mes,
				'pre_anho' => $pre_anho,
				'dia' => $dia,
				'mes' => $mes,
				'anho' => $anho,
				'mostrar' => $mostrar,
				'HExtras' => $HExtras,
				'proyecciones' => $Proyecciones,
				'Administrador' => $Administradores
			);
			
			//Se carga el encabezado de pagina
			$this->load->view('encabezado_v', $Variables);
			//Se carga la vista.
			$this->load->view('extras/extra_rep_v', $Variables);
			//Se carga el pie de pagina
			$this->load->view('pie_v');
		}
		else
		{
			header('location: /extras/extra_con');
		}
	}
}

/* Fin del archivo */