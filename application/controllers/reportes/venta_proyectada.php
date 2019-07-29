<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Venta_proyectada extends CI_Controller {
	
	/**
	 *Pagina que muestra el listado de los menus de usuario existentes.
	 *Tiene opciones para modificar y desactivar.
	 *@param string $Pagina.
	 *@param string $Inicio.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($mes = '', $anho = '')
	{
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$mes += 0;
		$anho += 0;
		if(0 == $mes or 0 == $anho)
		{
			$mes = date('m');
			$anho = date('Y');
		}
		$mes += 0;
		$anho += 0;
		if($mes < 10)
		{
			$mes = '0'.$mes;
		}
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables = array(
			'Titulo_Pagina' => 'Gr&aacute;fico de Proyecciones de Venta',
			'Mensaje' => ''
		);
		
		
		$pagina_cache = 'VProy_'.$anho.'_'.$mes.'_g'.$this->session->userdata('id_grupo').microtime();
		
		$Variables['Cache'] = $this->generar_cache_m->validar_cache($pagina_cache, $anho, $mes);
		
		if($Variables['Cache']['base_datos'] == 'si')
		{
			if($anho == '' and $mes == '')
			{
				$Variables['mes'] = date("m");
				$Variables['anho'] = date("Y");
				$anho = date("Y");
				$mes = date("m");
			}
			else
			{
				$Variables['mes'] = $mes;
				$Variables['anho'] = $anho;
			}
			
			if($mes != '' and $anho != '')
			{
				//Que dia de la semana inicia el mes?
				$dia_inicio = date("w", mktime(0, 0, 0, $mes, 1, $anho));
				$dias_mes = date('t', mktime( 0, 0, 0, date('m'), 1, date('Y') ));
			}
			
			//Modelo que realiza la busqueda de los Clientes.
			$this->load->model('reportes/venta_proyectada_m', 'venta_proy');
			$Variables['Proyecciones'] = $this->venta_proy->proyecciones_mensuales($anho, $mes);
			$Variables['Pendiente_facturar'] = $this->venta_proy->pendiente_facturar($anho, $mes);
			$Variables['Ventas_acumuladas'] = $this->venta_proy->ventas_acumuladas($anho, $mes);
			$Variables['dias_mes'] = $dias_mes;
			
			
			$Variables['meses_v'] = array(
				"01" => "Enero",
				"02" => "Febrero",
				"03" => "Marzo",
				"04" => "Abril",
				"05" => "Mayo",
				"06" => "Junio",
				"07" => "Julio",
				"08" => "Agosto",
				"09" => "Septiembre",
				"10" => "Octubre",
				"11" => "Noviembre",
				"12" => "Diciembre"
			);
			
			
			$Variables['Maximo'] = $Variables['Proyecciones'];
			if($Variables['Ventas_acumuladas'] > $Variables['Proyecciones'])
			{
				$Variables['Maximo'] = $Variables['Ventas_acumuladas'];
			}
			
			if($Variables['Pendiente_facturar'] > $Variables['Maximo'])
			{
				$Variables['Maximo'] = $Variables['Pendiente_facturar'];
			}
			
		}
		
		
		
		
		//Encabezado de la pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Pagina que muestra el listado de las unidades existentes y su enlace de
		//ingreso, se adjunta las variables que necesita
		$this->load->view('reportes/venta_proyectada_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */