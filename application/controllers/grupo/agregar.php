<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar extends CI_Controller {

	public function index($Mensaje = '')
	{
		/*Se determina los departamentos q tendran acceso a la información.*/
		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		//Los clientes no deberan tener acceso a esta informacion.
		$this->ver_sesion_m->no_clientes();
		
		if($Mensaje == 'ok')
		{
			$Mensaje = 'Grupo ingresado exitosamente.';
		}
		else
		{
			$Mensaje = '';
		}
		
		$Variables = array(
			'Titulo_Pagina' => 'Agregar Grupo',
			'Mensaje' => $Mensaje
		);
		
		//Listado de los clientes
		$this->load->model('clientes/busquedad_clientes_m', 'buscar_cli');
		$Variables['Clientes'] = $this->buscar_cli->mostrar_clientes(
			'id_cliente, codigo_cliente, nombre'
		);
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		//Cargamos la vista.
		$this->load->view('/grupo/agregar_v');
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
	
	public function guardar()
	{
		/*Se determina los departamentos q tendran acceso a la información.*/
		$Permitido = array('Gerencia' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		//Los clientes no deberan tener acceso a esta informacion.
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Nombre = $this->seguridad_m->mysql_seguro(
			$this->input->post('nombre')
		);
		$Abrev = $this->seguridad_m->mysql_seguro(
			$this->input->post('abrev')
		);
		
		$Tipo = $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo')
		);
		$Id_Cliente = $this->input->post('cliente') + 0;
		
		//Carga del modelo que realiza la validacion
		$this->load->model('grupo/agregar_m', 'agre_m');
		
		//Llamamos el modelo para poder almacenar los datos.
		$agregar_grupos= $this->agre_m->almacenar(
			$Nombre,
			$Abrev,
			$Tipo,
			$Id_Cliente
		);
		
		
		if('error' != $agregar_grupos)
		{//Si el grupo se guardo con exito
			
			
			//Es necesario crear usuarios predeterminados
			$this->load->model('usuarios/crear_m', 'crear');
			
			$Usuarios_Nuevos = array(
				array(
					'usuario' => 'GERENTE',
					'contrasenha' => rand(100000, 999999),
					'codigo' => 'Gerente',
					'nombre' => 'Gerente de Grupo',
					'puesto' => 'Gerente',
					'id_dpto' => 28
				),
				array(
					'usuario' => 'Ventas',
					'contrasenha' => time(),
					'codigo' => 'Gerente',
					'nombre' => 'Ventas (Gen&eacute;rico)',
					'puesto' => 'Ventas',
					'id_dpto' => 23
				),
				array(
					'usuario' => 'Plani',
					'contrasenha' => time(),
					'codigo' => 'Plani',
					'nombre' => 'Plani (Gen&eacute;rico)',
					'puesto' => 'Plani',
					'id_dpto' => 1
				)/*,
				array(
					'usuario' => 'Grupo1',
					'contrasenha' => time(),
					'codigo' => '',
					'nombre' => '',
					'puesto' => '',
					'id_dpto' => 39
				)*/
			);
			
			//Ingreso de usuarios predeterminados
			foreach($Usuarios_Nuevos as $Usuario)
			{
				
				$Ingreso = $this->crear->usuario(
					$Usuario['usuario'],
					$Usuario['contrasenha'],
					$Usuario['codigo'],
					$Usuario['nombre'],
					$Usuario['puesto'],
					$Usuario['id_dpto'],
					'',
					$agregar_grupos,
					true
				);
				
			}
			
			//Se especifica en el nuevo grupo creado que el usuario de central-g
			//debe estar asociado al grupo de central-g.... esto debe ir en un modelo =P
			$Consulta = '
				insert into usuario_grupo values(
					NULL,
					"'.$Ingreso.'",
					"1"
				)
			';
			$this->db->query($Consulta);
			
			
			
			//Enviamos a la pagina de agregar por si se quiere agregar
			//un nuevo grupo.
			header('location: /grupo/agregar/index/ok');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /grupo/agregar');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		
	}
}