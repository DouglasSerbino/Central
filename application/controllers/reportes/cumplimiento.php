<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cumplimiento extends CI_Controller {
	
	/**
	 *Cumplimiento de pedidos.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function index($tipo = '', $anho = '', $mes = '', $cod_cliente = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Reporte de Cumplimiento',
			'Mensaje' => ''
		);
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
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
		
		if($tipo == '')
		{
			$tipo = $this->seguridad_m->mysql_seguro(
					$this->input->post('tipo')
				);
			
			$Variables['fecha_hoy'] = date("Y-m-d");
			$Variables['dia'] = date("d");
			$mes = date("m");
			$anho = date("Y");
		}
		
		if($_POST)
		{
			$mes = $this->seguridad_m->mysql_seguro(
				$this->input->post('mes1')
			);
			
			$anho = $this->seguridad_m->mysql_seguro(
				$this->input->post('anho1')
			);
		}
		
		if($tipo == 'texto')
		{
			if($anho == '')
			{
				$mes = date("m");
				$anho = date("Y");
			}
		}
		
		$Variables['mostrar'] = 'no';
		$Variables['mes'] = $mes;
		$Variables['anho'] = $anho;
		$Variables['tipo'] = $tipo;
		$Variables['cod_cliente'] = $cod_cliente;
		
		if($tipo == 'grafico')
		{
			header('location: /reportes/cumplimiento_rep/index/'.$anho.'/'.$mes.'/gen');
			exit;
		}
		
		$pagina_cache = 'Cumpli_'.$anho.'_'.$mes.'_'.$tipo.'_g'.$this->session->userdata('id_grupo');
		
		$Variables['Cache'] = $this->generar_cache_m->validar_cache($pagina_cache, $anho, $mes);
	
		if($Variables['Cache']['base_datos'] == 'si')
		{
			//Cargamos el modelo encargado de mostrar la informacion de los procesos.
			$this->load->model('reportes/cumplimiento_m', 'cumplimiento');

			$Variables['cumplimiento_general'] = $this->cumplimiento->cumplimiento_clientes_texto($anho, $mes);

		}
		//print_r($Variables);
		//Cargamos la vista.
		$this->load->view('reportes/cumplimiento_v', $Variables);

		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */