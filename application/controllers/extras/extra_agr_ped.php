<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_agr_ped extends CI_Controller {
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index($id_usuario, $dia, $mes, $anho, $id_extra = '', $todos)
	{
		//Departamentos que tiene acceso a esta area.
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Declaracion de variables.
		$inicio_fin_extra = '';
		$comentario_extra = '';
		$comentario_extra_otro = '';
		if($id_extra == '-')
		{
			$id_extra = 0;
		}
		
		//Cargamos el modelo para poder mostrar la informacion.
			$this->load->model('extras/extra_agr_mod_ped_m', 'ext_agr_ped');
			//Llamamos a las diferentes funcione existentes para poder mostrar la informacion.
			$usuarios = $this->ext_agr_ped->Usuarios($id_usuario);
			//Verificamos si el id de la extra es diferente de 0.
			//Si es asi podemos determinar cual es la hora de inicio y fin de los trabajos.
			//Podemos extraer los comentarios que pertenecen a cada extra.
			if('' != $id_extra)
			{
				//Permite extraer la hora de inicio y fin de las extras.
				$inicio_fin_extra = $this->ext_agr_ped->Inicio_fin_extra($id_extra);
				//Permite extraer los coentarios de las extras.
				$comentario_extra = $this->ext_agr_ped->comentario_extra($id_extra);
				//Permite extraer los comentarios de las extras adicionales.
				$comentario_extra_otro = $this->ext_agr_ped->comentario_extra_otro($id_extra);
			}
			//Permite extraer los pedidos activos.
			$pedido_activo = $this->ext_agr_ped->pedido_activo();
			//Permite extraer la informacion del usuario con respecto al pedido.
			$pedido_usuario = $this->ext_agr_ped->pedido_usuario($id_usuario, $todos);
		
		//Verificamos si la persona que quiere acceder se ha auntenticado
		//Como un usuario que tiene acceso a esta area.
		if($this->session->userdata('contra_ok') == 'ok')
		{
			//Variables necesarias en el encabezado
			$Variables = array(
				'Titulo_Pagina' => 'Requerimiento de Horas Extras',
				'Mensaje' => '',
				'id_usuario' => $id_usuario,
				'dia' => $dia,
				'mes' => $mes,
				'anho' => $anho,
				'usuarios' => $usuarios,
				'id_extra' => $id_extra,
				'inicio_fin_extra' => $inicio_fin_extra,
				'comentario_extra' => $comentario_extra,
				'comentario_extra_otro' => $comentario_extra_otro,
				'pedido_activo' => $pedido_activo,
				'pedido_usuario' => $pedido_usuario,
				'Todos' => $todos
			);
			//Cargamos la vista
			$this->load->view('extras/extra_agr_ped_v', $Variables);

		}
		else
		{
			header('location: /extras/extra_con');
		}
	}
	
	/**
	 *Permitira validar si el usuario que quiere ingresar existe.
	 *@param string $Contrasenha;
	 *@return nada.
	*/
	public function extra_agr_sql()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables.
		$total = $this->seguridad_m->mysql_seguro(
			$this->input->post('total')
		);
		
		$fecha = $this->seguridad_m->mysql_seguro(
			$this->input->post('fecha')
		);
		
		$inicio = $this->seguridad_m->mysql_seguro(
			$this->input->post('inicio')
		);
		
		$fin = $this->seguridad_m->mysql_seguro(
			$this->input->post('fin')
		);
		
		$id_user_u = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_usuario')
		);
		
		$id_extra = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_extra')
		);
		
		//Cargamos el modelo para poder almacenar la informacion.
		$this->load->model('extras/extra_agr_mod_ped_m', 'ext_agr_ped');
		
		$almacenar= $this->ext_agr_ped->guardar_extras($total, $fecha, $inicio,
																									$fin, $id_user_u, $id_extra);
		
		//Recargamos la pagina y la cerramos.
		?>
		<script languaje="javascript">
				window.opener.document.location.reload();
				window.close();
		</script>
		<?php
		exit();
	}
}

/* Fin del archivo */