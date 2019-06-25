<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producto extends CI_Controller {
	
	
	public function index($anho = '', $mes = '', $id_cliente = 94, $tipo = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Reporte por Producto',
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
		
		$Variables['Anho'] = date('Y');
		if('' != $anho)
		{
			$Variables['Anho'] = $anho + 0;
		}
		
		$Variables['Mes'] = date('m');
		if('' != $mes)
		{
			$mes += 0;
			if(10 > $mes)
			{
				$mes = '0'.$mes;
			}
			$Variables['Mes'] = $mes;
		}
		
		$Variables['Tipo'] = $this->seguridad_m->mysql_seguro($tipo);
		$Variables['Id_Cliente'] = $id_cliente + 0;
		
		
		$this->load->model('reportes/producto_m', 'producto');
		$Variables['Productos'] = $this->producto->reporte(
			$Variables['Tipo'],
			$Variables['Anho'],
			$Variables['Mes'],
			$Variables['Id_Cliente']
		);
		
		
		//Listado de los clientes
		$this->load->model('clientes/busquedad_clientes_m', 'buscar_cli');
		$Variables['Clientes'] = $this->buscar_cli->mostrar_clientes(
			'id_cliente, codigo_cliente, nombre, cliente.id_tipo',
			true
		);
		
		
		
		
		//Cargamos la vista.
		$this->load->view('reportes/producto_v', $Variables);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */