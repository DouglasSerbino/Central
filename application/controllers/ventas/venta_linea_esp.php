<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Venta_linea_esp extends CI_Controller {
	
	/**
	 *Pagina que muestra las ventas por linea
	 *@return nada.
	*/
	public function index($id_material, $anho, $mes, $Id_Cliente = 0)
	{
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables = array(
			'Titulo_Pagina' => 'VENTAS POR L&Iacute;NEA',
			'Mensaje' => ''
		);
		
		$pagina_cache = 'VLinE_'.$anho.'_'.$mes.'_'.$id_material.'_g'.$Id_Cliente.'_'.$this->session->userdata('id_grupo');
		
		$Variables['Cache'] = $this->generar_cache_m->validar_cache($pagina_cache, $anho, $mes);
		
		if($Variables['Cache']['base_datos'] == 'si')
		{
			$Variables['Cliente'] = array();
			if('todos' != $Id_Cliente)
			{
				//Modelo que realiza la busqueda de las ventas por linea.
				$this->load->model('clientes/busquedad_clientes_m', 'clientes');
				$Variables['Cliente'] = $this->clientes->busquedad_especifica($Id_Cliente);
			}
			
			$this->load->model('ventas/venta_linea_m', 'venta');
			$Variables['ventas_linea'] = $this->venta->venta_linea($anho, $mes, 'lineal', $id_material, $Id_Cliente);
			$Variables['porcentaje_linea'] = $this->venta->porcentaje_linea($anho, $mes, 'lineal', $id_material, $Id_Cliente);
			
			
			$Variables['mostrar_trabajos'] = $this->venta->venta_linea($anho, $mes, 'rango_fechas', $id_material, $Id_Cliente);
			$Variables['mostrar_trabajos2'] = $this->venta->porcentaje_linea($anho, $mes, 'rango_fechas', $id_material, $Id_Cliente);
			$Variables['anho'] = $anho;
			$Variables['Id_Cliente'] = $Id_Cliente;
		}
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Cargamos la vista
		$this->load->view('ventas/venta_linea_esp_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
}
/* Fin del archivo */