<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cumplimiento_rep_ent extends CI_Controller {
	
	/**
	 *Ingreso del preingreso y todos sus elementos en la base de datos.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function index($tipo = '', $cod_cliente = '', $anho = '', $mes = '')
	{
		
		//echo microtime().'<br>';
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$mes += 0;
		$anho += 0;
		if(0 == $mes or 0 == $anho or '' == $tipo)
		{
			show_404();
			exit();
		}
		
		if($mes < 10)
		{
			$mes = '0'.$mes;
		}
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$cod_cliente = $this->seguridad_m->mysql_seguro($cod_cliente);
		$mes = $this->seguridad_m->mysql_seguro($mes);
		$anho = $this->seguridad_m->mysql_seguro($anho);
		
		$Variables['mes'] = $mes;
		$Variables['anho'] = $anho;
		$Variables['cod_cliente'] = $cod_cliente;
		$Variables['tipo'] = $tipo;
		$Variables['Titulos_v'] = array('tie' => 'ENTREGAS A TIEMPO', 'atr' => 'ENTREGAS ATRASADAS', 'rep' => 'REPROCESOS', 'tot' => 'TOTAL DE ENTREGAS');
		
		$pagina_cache = 'Cumpli_rep_ent_'.$anho.'_'.$mes.'_'.$cod_cliente.'_'.$tipo.'_g'.$this->session->userdata('id_grupo');
		
		$Variables['Cache'] = $this->generar_cache_m->validar_cache($pagina_cache, $anho, $mes);
		
	
		if($Variables['Cache']['base_datos'] == 'si')
		{
			$this->load->model('reportes/cumplimiento_rep_m', 'cumplimiento_rep');
			$Variables['Cliente'] = $this->cumplimiento_rep->cliente($cod_cliente);
			
			
			//Cargamos el modelo encargado de mostrar la informacion de los procesos.
			$this->load->model('reportes/cumplimiento_rep_ent_m', 'cumplimiento_rep_ent');
			$Variables['Resultado_pedidos'] = $this->cumplimiento_rep_ent->busquedad_pedido($tipo, $anho, $mes, $cod_cliente);
		}
			//Cargamos la vista.
			$this->load->view('reportes/cumplimiento_rep_ent_v', $Variables);
			//echo microtime().'<br>';
	}
}

/* Fin del archivo */