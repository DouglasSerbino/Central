<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar extends CI_Controller {

	public function index($Mensaje = '')
	{
		/*
		 *Determinamos los departamentos que tendran acceso a esta informacion.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no tendran acceso a este controlador.
		$this->ver_sesion_m->no_clientes();
		
		if($Mensaje == 'ok')
		{
			$Mensaje = 'Cliente ingresado exitosamente';
		}
		elseif($Mensaje == 'ex')
		{
			$Mensaje = 'Error: El cliente ingresado ya existe';
		}
		else
		{
			$Mensaje = '';
		}
		
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Agregar Cliente',
			'Mensaje' => $Mensaje
		);


		$this->load->model('clientes/modificar_clientes_m', 'mod_clientes_m');
		
		//Llamamos el modelo para poder modificar los datos.
		$Variables['productos'] = $this->mod_clientes_m->productos();
		
		/*Se llama de la base de datos todos los productos, sin embargo desde el codigo
		  Se quitan de los resultados que no cumplen los criterios definidos,
		  Esto es una mala practica y genera perdidas de tiempo al seguir la logica del flujo*/
		foreach($Variables['productos'] as $Index => $Datos)
		{
			if('n' == $Datos['activo'])
			{
				unset($Variables['productos'][$Index]);
			}
		}


		$this->load->model('clientes/listado_paises_m', 'paisesc');
		$Variables['Paises_C'] = $this->paisesc->paises_cliente();

		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		//Cargamos la vista.
		$this->load->view('/clientes/agregar_v');
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
	
	
	
	public function agregar_datos()
	{
		/*
		 *Determinamos los departamentos que tendran acceso a esta informacion.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no tendran acceso a este controlador.
		$this->ver_sesion_m->no_clientes();
		
		//Info General
		$Nombre = $this->seguridad_m->mysql_seguro(
			$this->input->post('nombre')
		);
		$Codigo_cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('codigo')
		);
		$Direccion = $this->seguridad_m->mysql_seguro(
			$this->input->post('direccion')
		);
		$NIT = $this->seguridad_m->mysql_seguro(
			$this->input->post('nit')
		);
		$Web = $this->seguridad_m->mysql_seguro(
			$this->input->post('web')
		);
		$Credito = $this->seguridad_m->mysql_seguro(
			$this->input->post('credito')
		);
		$CPais = $this->seguridad_m->mysql_seguro(
			$this->input->post('cpais')
		);

		//Contactos
		$Contacto = $this->input->post('clie_contacto');
		$Cargo = $this->input->post('clie_cargo');
		$Email = $this->input->post('clie_email');
		$TOficina = $this->input->post('clie_tofic');
		$TDirecto = $this->input->post('clie_tdire');
		$TCelular = $this->input->post('clie_tcelu');
		$Contactos_v = array();
		foreach($Contacto as $Index => $Data)
		{

			if('' == $this->seguridad_m->mysql_seguro($Contacto[$Index]))
			{
				continue;
			}

			$Contactos_v[$Index]['nomb'] = $this->seguridad_m->mysql_seguro($Contacto[$Index]);
			$Contactos_v[$Index]['carg'] = $this->seguridad_m->mysql_seguro($Cargo[$Index]);
			$Contactos_v[$Index]['emai'] = $this->seguridad_m->mysql_seguro($Email[$Index]);
			$Contactos_v[$Index]['tofi'] = $this->seguridad_m->mysql_seguro($TOficina[$Index]);
			$Contactos_v[$Index]['tdir'] = $this->seguridad_m->mysql_seguro($TDirecto[$Index]);
			$Contactos_v[$Index]['tcel'] = $this->seguridad_m->mysql_seguro($TCelular[$Index]);

		}


		//Maquinas
		$Maquina = $this->input->post('maquina');
		$Colores = $this->input->post('colores');
		$Maquinas_v = array();
		foreach($Maquina as $Index => $Data)
		{

			if('' == $this->seguridad_m->mysql_seguro($Maquina[$Index]))
			{
				continue;
			}

			$Maquinas_v[$Index]['maqu'] = $this->seguridad_m->mysql_seguro($Maquina[$Index]);
			$Maquinas_v[$Index]['colo'] = $this->seguridad_m->mysql_seguro($Colores[$Index]);

		}


		//Placas
		$Altura = $this->input->post('altura');
		$Lineaje = $this->input->post('lineaje');
		$Marca = $this->input->post('marca');
		$Planchas_v = array();
		foreach($Altura as $Index => $Data)
		{

			if('' == $this->seguridad_m->mysql_seguro($Altura[$Index]))
			{
				continue;
			}

			$Planchas_v[$Index]['altu'] = $this->seguridad_m->mysql_seguro($Altura[$Index]);
			$Planchas_v[$Index]['line'] = $this->seguridad_m->mysql_seguro($Lineaje[$Index]);
			$Planchas_v[$Index]['marc'] = $this->seguridad_m->mysql_seguro($Marca[$Index]);

		}

		
		//Aniloxes
		$Anilox = $this->input->post('anilox');
		$BCM = $this->input->post('bcm');
		$Ani_Cantidad = $this->input->post('ani_cantidad');
		$Aniloxes_v = array();
		foreach($Anilox as $Index => $Data)
		{

			if('' == $this->seguridad_m->mysql_seguro($Anilox[$Index]))
			{
				continue;
			}

			$Aniloxes_v[$Index]['ani'] = $this->seguridad_m->mysql_seguro($Anilox[$Index]);
			$Aniloxes_v[$Index]['bcm'] = $this->seguridad_m->mysql_seguro($BCM[$Index]);
			$Aniloxes_v[$Index]['can'] = $this->seguridad_m->mysql_seguro($Ani_Cantidad[$Index]);

		}

		
		//Productos
		$Prod_Precio = $this->input->post('clie_prod_precio');
		$Prod_Id = $this->input->post('hid_clie_prod');
		$Prod_Concepto = $this->input->post('clie_concepto');
		$Productos_v = array();
		foreach($Prod_Precio as $Index => $Data)
		{

			if('on' !== $this->input->post('chk_clie_prod_'.$Prod_Id[$Index]))
			{
				continue;
			}

			$Productos_v[$Index]['pre'] = $this->seguridad_m->mysql_seguro($Prod_Precio[$Index]);
			$Productos_v[$Index]['idp'] = $this->seguridad_m->mysql_seguro($Prod_Id[$Index]);
			$Productos_v[$Index]['con'] = $this->seguridad_m->mysql_seguro($Prod_Concepto[$Index]);

		}
		

		//Carga del modelo que permite ingresar los datos.
		$this->load->model('clientes/agregar_m', 'agre_m');
		
		//Llamamos el modelo para poder almacenar los datos.
		$agregar_cliente = $this->agre_m->guardar_datos(
			$this->session->userdata('id_grupo'),
			$Nombre,
			$Codigo_cliente,
			$Direccion,
			$NIT,
			$Web,
			$Credito,
			$Contactos_v,
			$Maquinas_v,
			$Aniloxes_v,
			$Planchas_v,
			$Productos_v,
			$CPais
		);
		
		
		

		if('ok' == $agregar_cliente)
		{//Si el grupo se guardo con exito
			//Enviamos a la pagina de agregar por si se quiere agregar
			//un nuevo grupo.
			header('location: /clientes/agregar/index/ok');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		elseif('existe' == $agregar_cliente)
		{//Si el grupo se guardo con exito
			//Enviamos a la pagina de agregar por si se quiere agregar
			//un nuevo grupo.
			// ex es la abreviatura de que existe el cliente.
			header('location: /clientes/agregar/index/ex');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /clientes/agregar/index/error');
			//Evitamos que se continue ejecutando el script
			exit();
		}	
	}
}