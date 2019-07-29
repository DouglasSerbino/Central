<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reprocesos extends CI_Controller {
	
	/**
	 *Listado de reprocesos.
	 *@return nada.
	*/
	public function index()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '', 'SAP' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Reporte de Reprocesos',
			'Mensaje' => ''
		);
		
		$anual = '';
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		$Variables['Detalle_repro'] = 'todos';
		if($_POST)
		{
			$mes = $this->seguridad_m->mysql_seguro(
				$this->input->post('mes')
			);
			
			$anho = $this->seguridad_m->mysql_seguro(
				$this->input->post('anho')
			);
			
			$Variables['Detalle_repro'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('reproceso_razon')
			);
			if(isset($_POST['anual']))
			{
				$anual = 'si';
			}
		}
		else
		{
			$Variables['fecha_hoy'] = date("Y-m-d");
			$Variables['dia'] = date("d");
			$mes = date("m");
			$anho = date("Y");
			$mes = date("m");
			$anho = date("Y");
		}
		
		$Variables['Meses'] = array(
			'01' => 'Enero',
			'02' => 'Febrero',
			'03' => 'Marzo',
			'04' => 'Abril',
			'05' => 'Mayo',
			'06' => 'Junio',
			'07' => 'Julio',
			'08' => 'Agosto',
			'09' => 'Septiembre',
			'10' => 'Octubre',
			'11' => 'Noviembre',
			'12' => 'Diciembre'
		);
		
		$Variables['mes'] = $mes;
		$Variables['anho'] = $anho;
		$Variables['anual'] = $anual;
		
		
		if($anual == 'si')
		{
			$mes = 'anual';
		}
		
		$pagina_cache = 'reproc_'.$anho.'_'.$mes.'_'.$Variables['Detalle_repro'].'deta_g'.$this->session->userdata('id_grupo');
	
		$Variables['Cache'] = $this->generar_cache_m->validar_cache($pagina_cache, $anho, $mes);
		
		if($Variables['Cache']['base_datos'] == 'si')
		{
			//Cargamos el modelo encargado de mostrar la informacion de los procesos.
			$this->load->model('reportes/reprocesos_m', 'reprocesos');
			$Variables['Reprocesos'] = $this->reprocesos->reprocesos($anho, $mes, $Variables['Detalle_repro']);
			
			//Extraemos las causas, motivos, razones o circunstancias del porque el trabajo puede ser un reproceso
			$this->load->model('pedidos/detalle_reproceso_m', 'reproceso');
			$Variables['Detalle_reproceso'] = $this->reproceso->detalle_reproceso();
		}
		//Cargamos la vista.
		$this->load->view('reportes/reprocesos_v', $Variables);

		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
}

/* Fin del archivo */