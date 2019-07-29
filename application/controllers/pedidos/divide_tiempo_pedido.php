<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Divide_tiempo_pedido extends CI_Controller {
	
	
	public function index($fecha_entrega = '')
	{
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		echo '{"pro":"","fes":[""],"val":"si"}';
		exit();
		
		/*
		 *Elementos necesarios:
		 *Lista de departamentos.
		 *Tiempos habiles para cada departamento (85%) en minutos.
		 *Lista de usuarios segun la ruta a programar por planificacion y sus tiempos.
		 *Tiempos de fin del ultimo trabajo de los usuarios en ruta.
		 *Fecha de inicio.
		 *Fecha de entrega solicitada.
		 *
		 *
		 *Tiempos puestos a mano:
		 *Calidad 25 minutos.
		 *SAP 7 minutos.
		 *Despacho 10 minutos.
		*/
		
		
		$Usuarios = $this->seguridad_m->mysql_seguro($this->input->post('usuarios'));
		$Tiempos = $this->seguridad_m->mysql_seguro($this->input->post('tiempos'));
		
		if('' != $Usuarios)
		{
			$Tiempos = explode('-', $Tiempos);
			$Usuarios = explode('-', $Usuarios);
		}
		$Fecha_Entrega_Solicitada = $this->seguridad_m->mysql_seguro($this->input->post('fecha'));
		$Tipo = $this->seguridad_m->mysql_seguro($this->input->post('tipo'));
		
		
		if('prop' == $Tipo)
		{
			$this->load->model('pedidos/cronograma_prop_m', 'dividir');
		}
		
		$Salida['Ajax'] = $this->dividir->dividir(
			$Usuarios,
			$Tiempos,
			$Fecha_Entrega_Solicitada
		);
		
		$this->load->view('ajax_v', $Salida);
		
		
	}
	
	
}

/* Fin del archivo */
