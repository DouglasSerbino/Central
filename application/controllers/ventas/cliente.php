<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente extends CI_Controller {
	
	
	/**
	 *Detalle de las ventas por clientes
	*/
	public function index()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Ventas por Cliente',
			'Mensaje' => '',
			'filtro_mostrar' => 'pedi'
		);
		
		
		//Variables con valor por default (defecto?)
		$Variables['ianho'] = date('Y');//Anho inicial
		$Variables['imes'] = date('m');//Mes inicial
		$Variables['fanho'] = date('Y');//Anho final
		$Variables['fmes'] = date('m');//Mes final
		$Variables['anho_inicio'] = 2008;//Desde esta fecha se tiene registro en ventas
		$Variables['anho_fin'] = date('Y');//Se puede visualizar hasta este anho
		$Variables['anho_fin'] = $Variables['anho_fin'] + 2;//Mas dos À? si, ('Y') = 2012 + 2 = 2014 À?
		$Variables['filtro_cliente'] = 'todos';
		$Variables['filtro_venta'] = 'todos';
		$Variables['filtro_vendedor'] = 'todos';
		$Variables['proyeccion_global'] = 'on';
		
		$Variables['multiple'] = '';//Deseo ver varios meses? Valor principal: '''] == No! Valor alternativo 'on''] == Si!
		$Variables['vista'] = 'todo';//Que deseo visualizar? Puede ser todo, venta o proyeccion.
		$Variables['id_div_principal'] = '';
		$Variables['ancho_pagina'] = 0;
		//Deseo saber cuantos meses hay para asi reacomodar el ancho del div que contiene el reporte
		$Variables['total_meses'] = 0;
		
		//Listado de los meses
		$Variables['meses_v'] = array(
			'01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
			'05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
			'09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
		);
		
		//Listado de los meses, abreviados
		$Variables['meses_abr_v'] = array(
			'',
			'Jan' => 'Ene',
			'Feb' => 'Feb',
			'Mar' => 'Mar',
			'Apr' => 'Abr',
			'May' => 'May',
			'Jun' => 'Jun',
			'Jul' => 'Jul',
			'Aug' => 'Ago',
			'Sep' => 'Sep',
			'Oct' => 'Oct',
			'Nov' => 'Nov',
			'Dec' => 'Dic'
		);
		
		
		
		
		//Si traigo variables enviadas por formulario
		if('' != $this->input->post('mes'))
		{
			$Variables['imes'] = $this->input->post('mes');
			$Variables['ianho'] = $this->input->post('anho');
			$Variables['fmes'] = $this->input->post('mes2');
			$Variables['fanho'] = $this->input->post('anho2');
			$Variables['vista'] = $this->input->post('vista');
			$Variables['filtro_mostrar'] = $this->input->post('mostrar');
			//$Variables['filtro_cliente'] = $this->input->post('tipo_cl');
			$Variables['filtro_venta'] = $this->input->post('tipo_vt');
			$Variables['filtro_vendedor'] = $this->input->post('vendedor');
			$Variables['multiple'] = $this->input->post('rango_fecha');
		}
		
		
		//Si deseo ver solamente un mes
		if('' == $Variables['multiple'])
		{
			//Aplico valores por default al mes y anho final
			
			$Variables['fmes'] = $Variables['imes'] + 1;//Aumento un mes a partir del mes de inicio
			$Variables['fanho'] = $Variables['ianho'];//Asigno el mismo anho de inicio
			
			if($Variables['fmes'] > 12){//Pero si me paso de diciembre al aumentarlo
				$Variables['fmes'] = 1;//Lo regreso a enero
				$Variables['fanho']++;//Y subo un anho
			}
			
			if($Variables['fmes'] < 10){
				//Si el mes quedo solo con un numero al haberlo sumado
				$Variables['fmes'] = '0'.$Variables['fmes'];//Le pongo un cerito al inicio
			}
		}
		
		

		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);

		/*
		//Obtencion de la informacion perteneciente al departamento seleccionado
		$this->load->model('clientes/agregar_m', 'agre_m');
		
		//La pasamos la varible limpia para ejecutar la consulta
		//$Variables['tipo_cliente'] = $this->agre_m->buscar_tipo_cliente();
		$Variables['vendedores'] = $this->agre_m->buscar_vendedor();
		*/
		
		
		$this->load->view('ventas/cliente_encabezado_v', $Variables);


		//Obtencion de la informacion perteneciente al departamento seleccionado
		$this->load->model('ventas/cliente_venta_m', 'clie_vent');

		
		if('clie' == $Variables['filtro_mostrar'])
		{

			//La pasamos la varible limpia para ejecutar la consulta
			$Variables['Vent_Clie'] = $this->clie_vent->ventas(
				$Variables['ianho'],
				$Variables['imes'],
				$Variables['fanho'],
				$Variables['fmes'],
				$Variables['filtro_cliente'],
				$Variables['filtro_venta'],
				$Variables['filtro_vendedor'],
				$Variables['proyeccion_global'],
				$Variables['multiple'],
				$Variables['vista']
			);
			
			//Listado de los clientes
			$this->load->model('clientes/busquedad_clientes_m', 'buscar_cli');
			$Variables['Clientes'] = $this->buscar_cli->mostrar_clientes(
				'id_cliente, codigo_cliente, nombre, cliente.activo',
				true
			);

			//Listado de las ventas
			$this->load->view('ventas/cliente_v', $Variables);
			
		}


		
		
		if('pedi' == $Variables['filtro_mostrar'])
		{

			//La pasamos la varible limpia para ejecutar la consulta
			$Variables['Vent_Clie'] = $this->clie_vent->ventas_pedido(
				$Variables['ianho'],
				$Variables['imes'],
				$Variables['fanho'],
				$Variables['fmes'],
				$Variables['filtro_cliente'],
				$Variables['filtro_venta'],
				$Variables['filtro_vendedor'],
				$Variables['proyeccion_global'],
				$Variables['multiple'],
				$Variables['vista']
			);
			
			//Listado de las ventas
			$this->load->view('ventas/cliente_pedido_v', $Variables);
			
		}
		
		
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
	
}

/* Fin del archivo */