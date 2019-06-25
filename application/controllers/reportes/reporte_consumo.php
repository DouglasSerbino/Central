<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporte_consumo extends CI_Controller {
	
	/**
	 *Muestra el inventario de materiales y sus existencias.
	 *@param string $Codigo;
	 *@param string $Proveedor;
	 *@param string $Cantidad;
	 *@param string $Equipo;
	 *@return nada.
	*/
	public function index(
		$Codigo = 'todos',
		$Proveedor = '--',
		$Cantidad = 'todos',
		$Equipo = '--'
	)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Reporte de Consumo',
			'Mensaje' => '',
			'Cod_Proveedor' => $Proveedor,
			'Cod_Equipo' => $Equipo,
			'Codigo' => $Codigo,
			'Cantidad' => $Cantidad
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Informacion de los equipos por areas
		$this->load->model('inventario/inventario_equipos_m', 'inv_equipos');
		$Variables['Mostrar_Equipo'] = $this->inv_equipos->mostrar_equipos();
		
		$this->load->model('reportes/reporte_consumo_m', 'consumo');
		$Variables['Consumo'] = $this->consumo->consumo_promedio_mensual();
		$Variables['Pedido_transito'] = $this->consumo->mostrar_pedido_transito();
		//Listado de Proveedores
		$this->load->model('inventario/inventario_proveedores_m', 'proveedor');
		$Variables['Mostrar_proveedor'] = $this->proveedor->mostrar_proveedor();	
		
		//Listado de materiales segun los filtros
		$this->load->model('inventario/listado_materiales_m', 'lmateriales');
		$Variables['Materiales'] = $this->lmateriales->listar(
			$Codigo,
			$Proveedor,
			$Cantidad,
			$Equipo
		);
		
		$this->load->view('reportes/reporte_consumo_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	/**
		Funcion que permitira mostrar la informacion de los pedidso en transito.
	*/
	public function pedidos_transito()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables
		$Id_cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('cliente')
		);
		
		$Procesos = $this->seguridad_m->mysql_seguro(
			$this->input->post('proceso')
		);
		
		$Id_proceso = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_proceso')
		);
		
		$Producto= $this->seguridad_m->mysql_seguro(
			$this->input->post('producto')
		);
		
		$this->load->model('procesos/modificar_proceso_m', 'mod_proc');
		
		//LLamamos el metodo encargado de la modificacion del proceso.
		$Modificar = $this->mod_proc->modificar_sql($Id_cliente, $Procesos, $Id_proceso, $Producto);
		

		if('ok' == $Modificar)
		{//Si el proceso se guardo con exito
			//Dirigimos a la pagina de crear pedido, por algo crearon el proceso :)
			header('location: /procesos/buscar_procesos/index/ok');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		elseif('existe' == $Modificar)
		{//Si el proceso se guardo con exito
			//Dirigimos a la pagina de crear pedido, por algo crearon el proceso :)
			header('location: /procesos/buscar_procesos/index/existe');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /procesos/buscar_procesos');
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}
	
	
	/**
	 *Busca la informacion relacionada al pedido seleccionado.
	 *@return array.
	*/
	function buscar_info($Id_inventario_material)
	{
		
		//Limpieza de variables
		$Id_inventario_material = $this->seguridad_m->mysql_seguro($Id_inventario_material);
		
		//Modelo buscador
		$this->load->model('reportes/reporte_consumo_m', 'reporte');
		
		//Modelo que se encarga de mostrar la informacion del pedido en transito.
		$Info['Ajax'] = $this->reporte->buscar_info_transito($Id_inventario_material);
		$Info['Ajax'] = str_replace("\t",' ', $Info['Ajax']);
		
		$this->load->view('ajax_v', $Info);
		
	}
	
}

/* Fin del archivo */