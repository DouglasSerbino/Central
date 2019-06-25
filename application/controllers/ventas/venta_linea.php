<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Venta_linea extends CI_Controller {
	
	/**
	 *Pagina que muestra las ventas por linea
	*/
	public function index()
	{
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables = array(
			'Titulo_Pagina' => 'VENTAS POR L&Iacute;NEA',
			'Mensaje' => ''
		);
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		if($_POST)
		{
			$Variables['mes'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('mes')
			);
			$Variables['anho'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('anho')
			);
			$Variables['Id_Cliente'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('idcliente')
			);
			
			$Variables['Divis'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('division')
			);
		}
		else
		{
			$Variables['mes'] = date("m");
			$Variables['anho'] = date("Y");
			$Variables['Id_Cliente'] = 'todos';
			$Variables['Divis'] = '';
		}
		
		
		$pagina_cache = 'VLin_'.$Variables['anho'].'_'.$Variables['mes'].'_g'.$this->session->userdata('id_grupo');
		
		$Variables['Cache'] = $this->generar_cache_m->validar_cache($pagina_cache, $Variables['anho'], $Variables['mes']);
		
		if($Variables['Cache']['base_datos'] == 'si')
		{
			$condicion = '';
			$id_material = '';
			
			//Modelo que realiza la busqueda de las ventas por linea.
			$this->load->model('ventas/venta_linea_m', 'venta');
			
			$Variables['ventas_linea'] = $this->venta->venta_linea($Variables['anho'], $Variables['mes'], $condicion, $id_material, $Variables['Id_Cliente'], $Variables['Divis'] );
			
			$Variables['porcentaje_linea'] = $this->venta->porcentaje_linea($Variables['anho'], $Variables['mes'], $condicion, $id_material, $Variables['Id_Cliente']);
			
			$Variables['Clientes'] = $this->venta->venta_linea_cliente($Variables['anho'], $Variables['mes'], $condicion, $id_material);
			
		}
		//Cargamos la vista
		$this->load->view('ventas/venta_linea_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
}

/* Fin del archivo */