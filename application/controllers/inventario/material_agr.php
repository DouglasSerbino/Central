<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Material_agr extends CI_Controller {
	
	/**
	 *Permite Agregar los materiales necesarios a la base de datos.
	 *@param string $Codigo; Codigo de material que se quiere modificar.
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{
		
		$this->ver_sesion_m->no_clientes();
		
		if($Mensaje == 'ok')
		{
			$Mensaje = 'El material fue agregado exitosamente';
		}
		elseif($Mensaje == 'cod')
		{
			$Mensaje = 'El codigo sap ingresado ya existe en otro material. <br />
									Por favor verifique la informacion ingresada.';
		}
		elseif($Mensaje == 'err')
		{
			$Mensaje = 'Ha ocurrido un error al intentar almacenar la informacion.';
		}
		//Informacion de los equipos por areas
		$this->load->model('inventario/inventario_equipos_m', 'inv_equipos');
		$Mos_equipos = $this->inv_equipos->mostrar_equipos();	
		
		//Listado de Proveedores
		$this->load->model('inventario/inventario_proveedores_m', 'proveedor');
		$Mostrar_Prov = $this->proveedor->mostrar_proveedor();	
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Agregar Materiales',
			'Mensaje' => $Mensaje,
			'Mostrar_equipos' => $Mos_equipos,
			'Mostrar_proveedor' => $Mostrar_Prov
		);

		$this->load->model('clientes/listado_paises_m', 'paisesc');
		$Variables['Paises_C'] = $this->paisesc->paises_cliente();
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		//Cargamos la vista para poder modificar la informacion.
		$this->load->view('inventario/material_agr_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}


	
	//Funcion que servira para almacenar nuevos materiales.
	public function agregar_material()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables		
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
		
		$Pais = $this->seguridad_m->mysql_seguro(
			$this->input->post('mpais')
		);
		
		$MP_MT = $this->input->post('mp_mt');
		if('mt' != $MP_MT)
		{
			$MP_MT = 'mp';
		}
		
		//Carga del modelo que nos permite agregar la informacion
		$this->load->model('inventario/agregar_modificar_material_m', 'agre_mod_mat_m');
		
		//Significado de agre_mod_mat_m:  agregar_modificar_material_m
		
		//Llamamos el modelo para poder almacenarla informacion.
		$agregar_material = $this->agre_mod_mat_m->agregar_sql(
			$Codigo_sap,
			$Valor,
			$Cantidad_unidad,
			$Tipo,
			$Proveedor,
			$Id_inventario_equipo,
			$Nombre,
			$Observacion,
			$numero_individual,
			$numero_cajas,
			$MP_MT,
			$Pais
		);
		
		if('ok' == $agregar_material)
		{//Si se agrego el material con exito.
			header('location: /inventario/material_agr/index/ok');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		elseif('cod' == $agregar_material)
		{//Ocurrio un error al intentar agregar los datos.
			header('location: /inventario/material_agr/index/cod');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{
			//Ocurrio un error al intentar agregar los datos.
				header('location: /inventario/material_agr/index/err');
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}
}

/* Fin del archivo */