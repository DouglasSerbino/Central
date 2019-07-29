<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventario_lot extends CI_Controller {
	
	/**
	 *Permite mostrar la rotacion de los materiales.
	 *@param string $Id_inventario_material: Id del material que se quiere mostrar.
	 *@return nada.
	*/
	public function index($Codigo = "", $Pagina = 1, $Inicio = 0, $anho = '')
	{
		
		$this->ver_sesion_m->no_clientes();
		
		if('' == $Codigo)
		{
			show_404();
			exit();
		}
		
		if($Pagina== "ok")
		{
			$Mensaje = "El lote de material fue ingresado con &eacute;xito";
			$Pagina = 1;
		}
		elseif($Pagina == 'error')
		{
			$Mensaje = 'Alerta!  La cantidad ingresada es mayor a la cantidad solicitada<br>
									Favor verificar el reporte de Consumo.';
			$Pagina = 1;
		}
		else
		{
			$Mensaje = '';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Detalles de Material',
			'Mensaje' => $Mensaje,
			);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		if(''==$anho)
		{
			$Variables['anho'] = date('Y');
		}
		else
		{
			$Variables['anho'] = $anho;
		}
		
		$Id_inventario_material = $this->seguridad_m->mysql_seguro($Codigo);
		
		$this->load->model('herramientas_sis/info_transito_m', 'info');
		$Variables['Informacion_material_detalle'] = $this->info->info_adicional($Id_inventario_material);
		
		//Modelo que realiza la busquedad de los lotes.
		$this->load->model('inventario/inventario_lot_m', 'inven_lot');
		$Variables['Lotes_material'] = $this->inven_lot->lotes_materiales($Inicio,$Id_inventario_material);
		$Variables['ConsumoMensual'] = $this->inven_lot->consumoAnual($Inicio,$Id_inventario_material, $Variables['anho']);

		$this->load->model('reportes/reporte_consumo_m', 'consumo');
		$Variables['Consumo'] = $this->consumo->consumo_promedio_mensual($Id_inventario_material);
		
		//Informacion de todos los materiales.
		$this->load->model('inventario/informacion_material_m', 'mos_material');
		$Variables['Materiales'] = $this->mos_material->mostrar_todos_materiales();
		
		//Total de lotes que tiene el material.
		$Total_lotes= $this->inven_lot->total_lotes($Id_inventario_material);
		//$Variables['Consumo_promedio_mensual'] = $this->inven_lot->consumo_promedio_mensual($Id_inventario_material, $Codigo, $Variables['anho']);
		
		
		//Carga del modelo para la paginacion
		$this->load->model('utilidades/paginacion_m', 'paginacion');
		$Variables['Paginar'] = $this->paginacion->paginar(
			'/inventario/inventario_lot/index/'.$Id_inventario_material.'/',
			($Total_lotes + 0),
			50,
			($Pagina + 0)
		);
			

		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		
			
		$Variables['Id_material'] = $Id_inventario_material;
		
		
		//Cargamos la vista para poder modificar la informacion.
		$this->load->view('inventario/inventario_lot_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	//Funcion que servira para enviar las variables
	//Y poder almacenar los lotes correspondientes.
	public function agregar_lote()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Limpieza de variables
		$Pedido = $this->seguridad_m->mysql_seguro(
			$this->input->post('pedido')
		);
		
		$Cantidad = $this->seguridad_m->mysql_seguro(
			$this->input->post('cantidad')
		);
		
		$Date1 = $this->seguridad_m->mysql_seguro(
			$this->input->post('date1')
		);
			
		$Id_inventario_material = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_inventario_material')
		);
		//Carga del modelo que nos permite agregar el lote.
		$this->load->model('inventario/agregar_modificar_material_m', 'agre_mod_mat_m');
		
		//Significado de agre_mod_mat_m:  agregar_modificar_material_m
		
		//Llamamos el modelo para poder modificar los datos.
		$agregar_lote = $this->agre_mod_mat_m->lote_agregar($Id_inventario_material, $Pedido, $Cantidad, $Date1);
		
		if('ok' == $agregar_lote)
		{//Si se modifico la informacion con exito.
			//lo redirigimos al listado de materiales.
			header('location: /inventario/inventario_lot/index/'.$Id_inventario_material.'/ok');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		elseif('error' == $agregar_lote)
		{//Ocurrio un error al intentar modificar los datos.
			//Regresamos a la pagina para mostrar el listado de materiales
			header('location: /inventario/inventario_lot/index/'.$Id_inventario_material.'/error');
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}
}

/* Fin del archivo */