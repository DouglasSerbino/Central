<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingreso_reportes extends CI_Controller {

	public function index()
	{
		/*
		 *Solamente el departamento de sistemas puede realizar esta accion
		 *Se ejecuta automaticamente de 4 a 5 de la tarde.
		 *Pero tambien se puede hacer de forma manual.
		 *Esta accion se ejecuta cuando se quiere actualizar toda la informacion
		 *de ventas y cumplimientos. 
		*/
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes nunca deberan tener acceso a esta pagina.
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que permite ingresar los datos.
		$this->load->model('carga/ingreso_reportes_m', 'reportes');
		
		$anho = date('Y');
		$mes = date('m');
		//Llamamos el modelo para poder almacenar los datos.
		$agregar_dptos= $this->reportes->guardar_datos($anho, $mes);
	}
}