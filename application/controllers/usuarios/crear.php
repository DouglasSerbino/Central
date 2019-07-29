<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crear extends CI_Controller {
	
	/**
	 *Formulario de creacion de usuario.
	 *@param string $Mensaje: Notificacion para retroalimentar al usuario.
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{
		
		$Permisos = array('usuarios');
		$this->ver_sesion_m->acceso('autorizados', false, $Permisos);
		
		$this->ver_sesion_m->no_clientes();
		
		if('ok' == $Mensaje)
		{
			$Mensaje = 'El usuario fue ingresado exitosamente.';
		}
		elseif('error' == $Mensaje)
		{
			$Mensaje = 'Ocurri&oacute; un error en el ingreso, favor intentar nuevamente.<br />Se ha creado un registro del error para buscar una soluci&oacute;n';
		}
		else
		{
			$Mensaje = '';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Crear Usuario',
			'Mensaje' => $Mensaje
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Modelo que lista los departamentos asignables
		$this->load->model('departamentos/listado_m', 'listar_d');
		//Listar_D = Listar Departamentos
		$Activo = 's';
		//Se guarda el listado para su uso posterior
		$Variables['Departamentos'] = $this->listar_d->buscar_dptos($Activo);


		$this->load->model('clientes/listado_paises_m', 'paisesc');
		$Variables['Paises_C'] = $this->paisesc->paises_cliente();
		
		//Formulario que toma los datos necesarios para la creacion del menu
		$this->load->view('usuarios/crear_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	/**
	 *Almacena en la base de datos el nuevo usuario
	 *@param nada.
	 *@return redirige a: usuario/crear/index/Mensaje[ok|error].
	*/
	public function usuario()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Usuario = $this->seguridad_m->mysql_seguro(
			$this->input->post('usuario')
		);
		$Password = $this->seguridad_m->mysql_seguro(
			$this->input->post('password')
		);
		$Cod_empleado = $this->seguridad_m->mysql_seguro(
			$this->input->post('cod_empleado')
		);
		$Nombre = $this->seguridad_m->mysql_seguro(
			$this->input->post('nombre')
		);
		$Puesto = $this->seguridad_m->mysql_seguro(
			$this->input->post('puesto')
		);
		$Departamento = $this->seguridad_m->mysql_seguro(
			$this->input->post('departamento')
		);
		$Email = $this->seguridad_m->mysql_seguro(
			$this->input->post('email')
		);
		$Pais = $this->seguridad_m->mysql_seguro(
			$this->input->post('upais')
		);
		
		//Carga del modelo que da ingreso al menu
		$this->load->model('usuarios/crear_m', 'crear');
		
		$Ingreso = $this->crear->usuario(
			$Usuario,
			$Password,
			$Cod_empleado,
			$Nombre,
			$Puesto,
			$Departamento,
			$Email,
			$this->session->userdata('id_grupo'),
			false,
			$Pais
		);
		
		if('ok' == $Ingreso)
		{
			header('location: /usuarios/crear/index/ok');
			exit();
		}
		else
		{
			header('location: /usuarios/crear/index/error');
			exit();
		}
		
	}
	
}

/* Fin del archivo */