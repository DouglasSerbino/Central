<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar extends CI_Controller {
	
	/**
	 *Formulario de modificacion de usuario.
	 *@param string $Id_Usuario.
	 *@return nada.
	*/
	public function index($Id_Usuario = '', $Id_grupo = '')
	{
		
		$Permisos = array('usuarios');
		$this->ver_sesion_m->acceso('autorizados', false, $Permisos);
		
		$this->ver_sesion_m->no_clientes();
		$Activo = 's';
		//Pequenha validacion
		$Id_Usuario += 0;
		//Si el resultado de la suma anterio es igual a cero, significa que el valor
		//recibido era una cadena de letras
		if(0 == $Id_Usuario)
		{
			//Muestro un mensaje de error
			show_404();
			//Evito que se siga ejecutando el script
			exit();
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Modificar Usuario',
			'Mensaje' => ''
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Modelo que extrae la informacion del usuario solicitado
		$this->load->model('usuarios/informacion_usuario_m', 'info_u');
		//Captura de la informacion del usuario.
		$Variables['Usuario'] = $this->info_u->datos($Id_Usuario);
		$Dpto = $Variables['Usuario'][0]['id_dpto'];
		
		//Si no recibo valores, es probable que algo raro halla ocurrido
		if(1 != count($Variables['Usuario']))
		{
			show_404();
			exit();
		}
		
		//Modelo que lista los departamentos asignables
		$this->load->model('departamentos/listado_m', 'listar_d');
		//Listar_D = Listar Departamentos
		//Se guarda el listado para su uso posterior
		$Variables['Departamentos'] = $this->listar_d->buscar_dptos($Activo);
		$Variables['Id_grupo'] = $Id_grupo;


		$this->load->model('clientes/listado_paises_m', 'paisesc');
		$Variables['Paises_C'] = $this->paisesc->paises_cliente();
		
		//Formulario que toma los datos necesarios para la creacion del menu
		$this->load->view('usuarios/modificar_v', $Variables);
		
				
		//Modelo que realiza la busqueda de los Menus.
		$this->load->model('menu/listar_m', 'listar_m');
		//listar_m = Listar Menus
		//Obtencion del listado de los Menus.
		$Menu = $this->listar_m->listado();
		//Menus pertenecientes al departamento del usuario
		$Menu_Dpto = $this->listar_m->menu_departamento($Variables['Usuario'][0]['id_dpto']);
		//Menus pertenecientes a este usuario
		$Menu_Usu = $this->listar_m->menu_usuario($Id_Usuario);
		
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables = array(
			'Id_Usuario' => $Id_Usuario,
			'Menu' => $Menu,
			'Pagina' => 'usuario',
			'Menu_Dpto' => $Menu_Dpto,
			'Menu_Usu' => $Menu_Usu
		);
		
		//Pagina que muestra el listado de las unidades existentes y su enlace de
		//ingreso, se adjunta las variables que necesita
		$this->load->view('menu/listado_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	/**
	 *Modifica el usuario con la nueva informacion
	 *@param string $Id_Usuario.
	 *@return redirige a: usuario/crear/index/Mensaje[ok|error].
	*/
	public function usuario($Id_Usuario)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Pequenha validacion
		$Id_Usuario += 0;
		//Si el resultado de la suma anterio es igual a cero, significa que el valor
		//recibido era una cadena de letras
		if(0 == $Id_Usuario)
		{
			//Muestro un mensaje de error
			show_404();
			//Evito que se siga ejecutando el script
			exit();
		}
		
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
		$Usu_prog = $this->seguridad_m->mysql_seguro(
			$this->input->post('usu_prog')
		);
		$Pais = $this->seguridad_m->mysql_seguro(
			$this->input->post('upais')
		);
		
		//Carga del modelo que da ingreso al menu
		$this->load->model('usuarios/modificar_m', 'modificar');
		
		$Ingreso = $this->modificar->usuario(
			$Id_Usuario,
			$Usuario,
			$Password,
			$Cod_empleado,
			$Nombre,
			$Puesto,
			$Departamento,
			$Email,
			$Usu_prog,
			$Pais
		);
		
		if('ok' == $Ingreso)
		{
			header('location: /usuarios/listado/index/ok');
			exit();
		}
		else
		{
			header('location: /usuarios/listado/index/error');
			exit();
		}
		
	}
	
	/**
	 *Asginacion de un menu a un usuario.
	 *@param string $Id_Usuario.
	 *@param string $Id_Menu.
	 *@return nada
	*/
	public function activar_menu($Id_Usuario, $Id_menu)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que nos permite asignar los departamentos.
		$this->load->model('usuarios/modificar_m', 'mod_u');
		
		//Llamamos el modelo para poder activar los departamentos.
		$asignar_menu = $this->mod_u->activar_menu($Id_Usuario, $Id_menu);
		
		header('location: /usuarios/modificar/index/'.$Id_Usuario.'#'.$Id_menu);
		exit();
		
	}
	
	
	/**
	 *Desactivacion de un menu a un usuario.
	 *@param string $Id_Usuario.
	 *@param string $Id_Menu.
	 *@return nada
	*/
	public function desactivar_menu($Id_Usuario, $Id_menu)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que nos permite asignar los departamentos.
		$this->load->model('usuarios/modificar_m', 'mod_u');
		
		//Llamamos el modelo para poder activar los departamentos.
		$asignar_menu = $this->mod_u->eliminar_menu($Id_Usuario, $Id_menu);
		
		header('location: /usuarios/modificar/index/'.$Id_Usuario.'#'.$Id_menu);
		exit();
		
	}
	
	/**
	 *Funcion para agregar un grupo a un usuario.
	 *@param string $Id_Usuario.
	 *@param string $Id_grupo.
	 *@return nada
	*/
	public function asignacion_grupos($Id_grupo, $Id_Usuario)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que nos permite asignar los departamentos.
		$this->load->model('usuarios/modificar_m', 'mod_u');
		
		//Llamamos el modelo para poder activar los departamentos.
		$asignar_grupo = $this->mod_u->asignar_los_grupos($Id_grupo, $Id_Usuario);

		if('ok' == $asignar_grupo)
		{
			header('location: /usuarios/modificar/index/'.$Id_Usuario.'/'.$Id_grupo);
			exit();
		}
		else
		{
			header('location: /usuarios/modificar/index/'.$Id_Usuario.'/'.$Id_grupo);
			exit();
		}
	}
	
}

/* Fin del archivo */