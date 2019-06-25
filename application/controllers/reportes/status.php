<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status extends CI_Controller {
	
	/**
	 *Reporte de status.
	 *@return nada.
	*/
	public function index()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Reporte de Status',
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
		
		//Listado de los clientes
		$this->load->model('clientes/busquedad_clientes_m', 'buscar_cli');
		$Variables['Clientes'] = $this->buscar_cli->mostrar_clientes(
			'id_cliente, codigo_cliente, nombre'
		);
		
		//Cargamos la vista.
		$this->load->view('reportes/status_v', $Variables);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
	
	
	/**
	 *Reporte del estado de los pedidos para este cliente.
	 *@return nada.
	*/
	public function ver($Codigo_Cliente, $Mes, $Anho, $Finalizado)
	{
		//$this->output->cache(10000);
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		//limpieza de variables
		$Codigo_Cliente = $this->seguridad_m->mysql_seguro($Codigo_Cliente);
		$Mes = $this->seguridad_m->mysql_seguro($Mes);
		$Anho = $this->seguridad_m->mysql_seguro($Anho);
		$Finalizado = $this->seguridad_m->mysql_seguro($Finalizado);
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Reporte de Status',
			'Mensaje' => '',
			'Codigo_Cliente' => $Codigo_Cliente
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
		
		
		$Fecha_Fin = date('Y-m-d', strtotime('+ 1 month', strtotime(date('Y-m-d'))));
		$Fecha_Fin = explode('-', $Fecha_Fin);
		
		$Fechas['dia1'] = '01';
		$Fechas['mes1'] = '01';
		$Fechas['anho1'] = '2012';
		$Fechas['dia2'] = $Fecha_Fin[2];
		$Fechas['mes2'] = $Fecha_Fin[1];
		$Fechas['anho2'] = $Fecha_Fin[0];
		
		
		
		//
		$this->load->model('clientes/busquedad_clientes_m', 'bus_cl');
		$Info_Cliente = $this->bus_cl->busquedad_codigo($Codigo_Cliente);
		
		//2012-01-01
		$this->load->model('carga/seguimiento_m', 'seguim');
		$Variables['TrabProc'] = $this->seguim->carga(
			$Fechas,
			'todos',
			$Info_Cliente['id_cliente'],
			'incompleto'
		);
		
		
		//Listado de los trabajos segun sea necesario
		$this->load->model('reportes/status_m', 'estado');
		
		//$Variables['TrabProc'] = $this->estado->trabajos_procesando($Codigo_Cliente);
		
		
		$Variables['MateProc'] = $this->estado->materiales_procesando($Codigo_Cliente);
		
		
		
		if('si' == $Finalizado)
		{
			$Variables['TrabFina'] = $this->estado->trabajos_finalizados(
				$Codigo_Cliente,
				$Mes,
				$Anho
			);
			
			$Variables['MateFina'] = $this->estado->materiales_finalizados(
				$Codigo_Cliente,
				$Mes,
				$Anho
			);
		}
		
		
		//Cargamos la vista.
		$this->load->view('reportes/status_rep_v', $Variables);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
	
}

/* Fin del archivo */