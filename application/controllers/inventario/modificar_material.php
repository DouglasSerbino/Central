<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_material extends CI_Controller {
	
	/**
	 *Permite modificar los materiales existentes en la base de datos.
	 *@param string $Codigo; Codigo de material que se quiere modificar.
	 *@return nada.
	*/
	public function index($Codigo)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$Id_material = $this->seguridad_m->mysql_seguro($Codigo);
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Modificaci&oacute;n de Materiales',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		$Variables['Id_material'] = $Codigo;
		
		//Informacion del material que se quiere modificar.
		$this->load->model('inventario/informacion_material_m', 'mos_material');
		$Variables['Mostrar_materiales'] = $this->mos_material->mostrar_materiales($Id_material);	
		$Variables['Id_proveedor'] = $this->mos_material->mostrar_id_proveedor($Id_material);	
		//Informacion de los equipos por areas
		$this->load->model('inventario/inventario_equipos_m', 'inv_equipos');
		$Variables['Mostrar_equipos'] = $this->inv_equipos->mostrar_equipos();	
		
		//Listado de Proveedores
		$this->load->model('inventario/inventario_proveedores_m', 'proveedor');
		$Variables['Mostrar_proveedor'] = $this->proveedor->mostrar_proveedor();
		

		$this->load->model('clientes/listado_paises_m', 'paisesc');
		$Variables['Paises_C'] = $this->paisesc->paises_cliente();

		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		
		//Cargamos la vista para poder modificar la informacion.
		$this->load->view('inventario/modificar_material_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	


	//********************************************
	public function modificar_datos()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Id_inventario_material = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_inventario_material')
		);
		
		$Codigo_sap = $this->seguridad_m->mysql_seguro(
			$this->input->post('codigo')
		);
		
		$Valor = $this->seguridad_m->mysql_seguro(
			$this->input->post('valor')
		);
		
		$Cantidad_unidad = $this->seguridad_m->mysql_seguro(
			$this->input->post('cantidad_unidad')
		);
		
		$Tipo= $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo')
		);
		
		$Id_inventario_equipo = $this->seguridad_m->mysql_seguro(
			$this->input->post('equipo')
		);
		
		$Proveedor = $this->seguridad_m->mysql_seguro(
			$this->input->post('proveedor')
		);
		
		$Existencias = $this->seguridad_m->mysql_seguro(
			$this->input->post('existencias')
		);
		
		$Nombre = $this->seguridad_m->mysql_seguro(
			$this->input->post('nombre')
		);
		
		$Observacion = $this->seguridad_m->mysql_seguro(
			$this->input->post('observacion')
		);
		
		$numero_individual = $this->seguridad_m->mysql_seguro(
			$this->input->post('numero_individual')
		);
		
		$numero_cajas = $this->seguridad_m->mysql_seguro(
			$this->input->post('numero_cajas')
		);
		
		$mpais = $this->seguridad_m->mysql_seguro(
			$this->input->post('mpais')
		);
		
		$MP_MT = $this->input->post('mp_mt');
		if('mt' != $MP_MT)
		{
			$MP_MT = 'mp';
		}
		
		//Carga del modelo que nos permite modificar la informacion
		$this->load->model('inventario/agregar_modificar_material_m', 'agre_mod_mat_m');
		
		//Significado de agre_mod_mat_m:  agregar_modificar_material_m
		
		//Llamamos el modelo para poder modificar los datos.
		$modificar_material = $this->agre_mod_mat_m->modificar_sql(
			$Id_inventario_material,
			$Codigo_sap,
			$Valor,
			$Cantidad_unidad,
			$Tipo,
			$Proveedor,
			$Existencias,
			$Id_inventario_equipo,
			$Nombre,
			$Observacion,
			$numero_individual,
			$numero_cajas,
			$MP_MT,
			$mpais
		);
		
		if('ok' == $modificar_material)
		{//Si se modifico la informacion con exito.
			//lo redirigimos al listado de materiales por si se quiere modificar otro.
			header('location: /inventario/existencias/');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar modificar los datos.
			//Regresamos a la pagina para mostrar el listado
			header('location: /inventario/existencias');
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}
}

/* Fin del archivo */