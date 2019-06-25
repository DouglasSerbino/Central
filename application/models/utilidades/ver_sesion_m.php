<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ver_sesion_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		
		//Llamo al metodo que se encarga de validar la existencia de las sesiones
		//o del permiso de acceso
		$this->comprobar();
	}
	
	/**
	 *Validacion del inicio de sesion y de permisos de usuario.
	 *@param nada.
	 *@return 'error' si hay problemas o deja continuar el script si todo esta bien.
	*/
	function comprobar()
	{

		if(!FALSE == $this->session->userdata('id_usuario'))
		{
			
			$fecha = date('Y-m-d H:i:s');
			
			
			/*
			 *Pedidos pendientes de ingresar por parte de Pamela.
			*/
			if('plani' == $this->session->userdata('codigo'))
			{

				$Consulta = '
					select * from pedido_transito_solicitud
					where activo ="s"
					and fecha_a < "'.$fecha.'"
				';
				
				$Resultado = $this->db->query($Consulta);
				
				if(0 < $Resultado->num_rows())
				{
					$this->session->set_userdata(array('sol_pedido'=> count($Resultado->result_array())));
				}
				else
				{
					
					$Consulta = '
						select *
						from pedido_material_solicitud
						where activo ="s"
					';
					
					$Resultado = $this->db->query($Consulta);
					
					if(0 < $Resultado->num_rows())
					{
						$this->session->set_userdata(array('sol_pedido'=> count($Resultado->result_array())));
					}
					else
					{
						$sesiones_activas = array(
							'sol_pedido' => ''
						);
					
						//Se destruyen las sesiones acivas.
						$this->session->unset_userdata($sesiones_activas);
					}
				}
			}
			
			//Pedidos en los que se a realizado cambios de cotizacion.
			if(11 == $this->session->userdata('id_dpto'))
			{
				$Consulta = '
					select count(usu.id_pedido) as id_pedido
					from pedido_sap sap, pedido_usuario usu
					where actualizar ="s"
					and sap.id_pedido = usu.id_pedido
					and usu.id_usuario = 27
					and usu.estado != "Terminado"
				';
			
				$Resultado = $this->db->query($Consulta);
				
				if(0 < $Resultado->num_rows())
				{
					$Total = $Resultado->row_array();
					$this->session->set_userdata(array('modi_coti'=> $Total['id_pedido']));
				}
				else
				{
					$sesiones_activas = array(
						'modi_coti' => ''
					);
				
					//Se destruyen las sesiones acivas.
					$this->session->unset_userdata($sesiones_activas);
				}

			}

			

			$CI =& get_instance();
			$url = $CI->config->site_url($CI->uri->uri_string());

			
			//Usuarios conectados en la web.
			$Consulta = '
				select id_user from
				user_conectados
				where id_usuario = "'.$this->session->userdata('id_usuario').'"
			';
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				if('pie' != $this->uri->segment(1) && 'usuarios_online' != $this->uri->segment(2))
				{
					$Consulta = '
						UPDATE user_conectados
						set fecha = "'.$fecha.'", pagina = "'.$url.'"
						where id_usuario = "'.$this->session->userdata('id_usuario').'"
					';
					$Resultado = $this->db->query($Consulta);
				}
			}
			else
			{
				
				$Consulta = '
					INSERT INTO user_conectados values(
						null,
						"'.$this->session->userdata('id_usuario').'",
						"'.$fecha.'",
						"'.$url.'"
					)
				';
				
				$Resultado = $this->db->query($Consulta);
			}
			
			
		}
		


		if(
			FALSE == $this->session->userdata('id_usuario')
			&& 'inicio' != $this->uri->segment(1)
			&& 'ingresar' != $this->uri->segment(1)
			&& 'almacenar_cotizacion' != $this->uri->segment(1)
			&& 'pie' != $this->uri->segment(1)
		)
		{
			//Si no hay sesion iniciada y quieren entrar a otra seccion que no sea la de
			//elegir el grupo o la de inicio de sesion?
			header('location: /ingresar/grupo/central-g');
			//Evitamos que se siga ejecutando el script
			exit();
		}
		

		if(
			FALSE != $this->session->userdata('id_usuario')
			&& (
				'' == $this->uri->segment(1)
				|| 'inicio' == $this->uri->segment(1)
				|| 'ingresar' == $this->uri->segment(1)
			)
		)
		{
			//El caso de un usuario que ya inicio sesion pero ha sido redirigido a
			//la pagina de inicio o abrio el navegador en esta pagina
			header('location: /principal');
			//Evitamos que se siga ejecutando el script
			exit();
		}
		
	}
	
	
	
	/**
	 *Que pasa si un usuario quiere ingresar digitando en la url la ruta a un
	 *menu que se aprendio de memoria pero no ha iniciado sesion?
	 *O si quiere entrar a un menu al que no tiene permiso?
	 *Ya lo descubriremos
	*/
	function acceso($Aceptados, $Solo_centralg = false, $Permisos = array())
	{
		
		if('autorizados' == $Aceptados)
		{
			//Revisamos las preferencias de los usuarios.
			//A estas paginas solo tienen acceso los usuarios que han sido designados
			//directamente, no solo por ser parte de un departamento con privilegios
			$Preferencias = json_decode($this->session->userdata('preferencias'), true);
			foreach($Permisos as $Item)
			{
				if(!isset($Preferencias[$Item]) && 'si' != $Preferencias[$Item])
				{
					//Si el usuario activo no tiene permiso de ver esto se muestra 404
					show_404();
					exit();
				}
			}
		}
		elseif('todos' != $Aceptados)
		{
			//Si no 'todos' estan permitidos
			//Obtengo un array con los departamentos permitidos para esta pagina
			if(!isset($Aceptados[$this->session->userdata('codigo')]))
			{
				//Si el usuario activo no esta entre los aceptados, pongo error
				show_404();
				exit();
			}
		}
		
		//Hay paginas accesibles solo al personal de central-g y especialmente al gerente
		//o los de sistemas.
		if($Solo_centralg && 'central-g' != $this->session->userdata('grupo'))
		{
			//Si esta pagina esta permitida solo para central-g y el usuario activo no pertenece
			//a central-g, le mando un error
			show_404();
			exit();
		}
		
	}
	
	
	/**
	 *Pero que pasa si un cliente quiere ingresar digitando en la url la ruta a un
	 *menu que se aprendio de memoria pero no le corresponde?
	 *Ya lo descubriremos
	*/
	function no_clientes()
	{
		
		if('' != $this->session->userdata('id_cliente'))
		{
			show_404();
			exit();
		}
		
	}
	
	
	/**
	 *Los clientes no pueden ver los trabajos de otro, aqui lo validamos.
	*/
	function solo_un_cliente($Id_Cliente)
	{
		
		if('' != $this->session->userdata('id_cliente'))
		{
			if($Id_Cliente != $this->session->userdata('id_cliente'))
			{
				show_404();
				exit();
			}
		}
		
	}
	
	
	
	/**
	 *Funcion espia, para mostrarme un listado de los grupos que existen en la base
	 *de datos, este listado se utiliza en el encabezado de los admin.
	 *Si creo un modelo aparte y debo cargarlo en los controlers, me costaria demasiado
	 *ir modelo por modelo, asi como los otros: solo_un_cliente p.e. y si creara
	 *un modelo nuevo y lo cargara al autoload, van a existir demasiados archivos;
	 *mejor espero que aparezca una necesidad real para cargar otro modelo automatico.
	*/
	
	function listado_grupos()
	{
		
		$Consulta = '
			select id_grupo, abreviatura
			from grupos
			order by id_grupo asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
	
	/**
	 *Esta funcion es prima hermana en primer grado de la anterior.
	 *Toma el nuevo id_grupo al que debe ser asignado el usuario actual y modifica
	 *la sesion.
	 *@param string $Nuevo_Grupo.
	 *@return string 'ok'.
	*/
	
	function cambia_grupo($Id_Nuevo_Grupo)
	{
		
		$this->session->set_userdata('id_grupo', $Id_Nuevo_Grupo);
		
		return 'ok';
		
	}
	
	
	
}


/* Fin del archivo */
