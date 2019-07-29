<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cumplimiento_rep extends CI_Controller {
	
	/**
	 *Ingreso del preingreso y todos sus elementos en la base de datos.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function index($anho = '', $mes = '', $cod_cliente = '')
	{
		
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Validacion
		$mes += 0;
		$anho += 0;
		
		if(0 == $anho or $mes == 0)
		{
			show_404();
			exit();
		}
		if($mes < 10)
		{
			$mes = '0'.$mes;
		}
		$cod_cliente = $this->seguridad_m->mysql_seguro($cod_cliente);
		
		$Variables['mes'] = $mes;
		$Variables['anho'] = $anho;
		$Variables['cod_cliente'] = $cod_cliente;
		
		
		
		if($cod_cliente != 'gen')
		{
			$Variables['enviar'] = '/'.$cod_cliente;
		}
		else
		{
			$Variables['enviar'] = '/gen';
		}
		if($anho.'-'.$mes < "2012-06")
		{
			$Variables['trabajos_fin'] = array(
					'1' => 'Arte',
					'2' => 'PDF',
					'4' => 'Prueba de Color',
					'8' => 'Planchas',
					'9' => 'Negativos',
					'10' => 'Prueba de Color',
					'11' => 'TIFF',
					'12' => 'Planchas',
					'15' => 'Prueba de Color',
					'20' => 'Prueba de Color'
					);
		}
		else
		{
			$Variables['trabajos_fin'] = array(
					'1' => 'Arte',
					'2' => 'PDF',
					'8' => 'Planchas',
					'9' => 'Negativos',
					'11' => 'TIFF',
					'12' => 'Planchas',
					'20' => 'Prueba de Color'
					);
		}
		
		$pagina_cache = 'Cumpli_rep_'.$Variables['anho'].'_'.$Variables['mes'].'_'.$cod_cliente.'_g'.$this->session->userdata('id_grupo');
		
		$Variables['Cache'] = $this->generar_cache_m->validar_cache($pagina_cache, $Variables['anho'], $Variables['mes']);
		
		$Variables['Porcentajes'] = array();
		$Variables['Cliente'] = array();
		$Variables['Trabajos_finales'] = array();
		$Variables['tiempos'] = array();
		
		
		$Variables['Informacion_pedidos'] = array();
		
		if($Variables['Cache']['base_datos'] == 'si')
		{
			
			//Cargamos el modelo encargado de mostrar la informacion de los procesos.
			$this->load->model('reportes/cumplimiento_rep_m', 'cumplimiento_rep');
			
			$Variables['Porcentajes'] = $this->cumplimiento_rep->Porcentajes($Variables['anho'], $Variables['mes'], $cod_cliente);
			
			$Variables['Cliente'] = $this->cumplimiento_rep->cliente($cod_cliente);
			
			$Variables['Trabajos_finales'] = $this->cumplimiento_rep->trabajos_finales($Variables['trabajos_fin'], $Variables['anho'], $Variables['mes'], $Variables['Cliente']['id_cliente']);
			
			$Variables['tiempos'] = $this->cumplimiento_rep->tiempos_utilizados($Variables['anho'], $Variables['mes'], $cod_cliente);
			
		}
		
		//Cargamos la vista.
		$this->load->view('reportes/cumplimiento_rep_v', $Variables);
		//echo microtime().'<br>';
	}
}

/* Fin del archivo */