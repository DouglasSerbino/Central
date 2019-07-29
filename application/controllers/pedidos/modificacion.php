<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificacion extends CI_Controller {
	
	/**
	 *Modificacion del Pedido y su ruta de trabajo.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function index($Id_Pedido, $Tipo_Ir = 0)
	{
		
		
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Super validacion
		$Id_Pedido += 0;
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		
		
		
		
		//Carga del modelo validador del proceso
		$this->load->model('procesos/buscar_proceso_m', 'buscar');
		//Verificamos la existencia
		$Existe = $this->buscar->busqueda_pedido($Id_Pedido);
		
		
		if(0 == $Existe)
		{//Si el proceso no existe se envia atras y se notifica
			header('location: /pedidos/buscar/index/existe');
			exit();
		}
		
		
		//Modelo que verifica si tiene ruta sin finalizar este proceso
		$this->load->model('pedidos/procesando_m', 'procesando');
		//Se realiza la verificacion
		$Estado = $this->procesando->pedido($Id_Pedido);
		
		if('activo' != $Estado)
		{//Si el proceso no existe o no esta activo se envia atras y se notifica
			header('location: /pedidos/buscar/index/ruta');
			exit();
		}


		//Obtencion de la ruta elegida
		$Id_Ruta = $this->input->post('asigna_ruta');
		$Id_Ruta += 0;
		$this->load->model('ruta/ruta_dinamica_m', 'rutad');
		$Ruta = $this->rutad->obtener($Id_Ruta);

		if(0 == count($Ruta['dptos']))
		{
			show_404();
			exit();
		}


		
		
		//Ya que verificamos que todo esta bien, podemos proseguir
		
		//Fecha corta
		$Fecha = date('Y-m-d');
		//Fecha larga
		$Fecha_Hora = date('Y-m-d H:i:s');
		
		
		
		//** Modificamos el pedido en la base de datos **//
		
		
		//Los checkbox requieren un trato especial.
		//Hay que comprenderlos.
		if('on' == $this->input->post('prioridad'))
		{
			$Prioridad = 'Si';
		}
		else
		{
			$Prioridad = 'No';
		}
		$Tipo_Trabajo = $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo_trabajo')
		);
		$Id_Usu_Rechazo = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_usu_rechazo')
		);
		
		
		//Puesto de trabajo al que debe regresar|ser asignado el pedido
		$Puesto_Asignado = $this->input->post('puesto_asignado');
		//Validacion simple
		$Puesto_Asignado += 0;
		if(0 == $Puesto_Asignado)
		{
			header('location: /pedidos/buscar/index/ierror');
			exit();
		}
		
		$Fecha_Entrega = $this->seguridad_m->mysql_seguro(
			$this->input->post('fecha_entrega')
		);
		$Fecha_Entrega = $this->fechas_m->fecha_dmy_ymd($Fecha_Entrega);
		
		$Reproceso_razon = 0;
		if(isset($_POST['reproceso_razon']))
		{
			$Reproceso_razon = $this->seguridad_m->mysql_seguro(
				$this->input->post('reproceso_razon')
			);
		}
		
		//Modificacion del pedido
		$this->load->model('pedidos/modificacion_m', 'modificar_p');
		$Modificado = $this->modificar_p->index(
			$Id_Pedido,
			$Prioridad,
			$Tipo_Trabajo,
			$Id_Usu_Rechazo,
			$Fecha_Entrega,
			$Reproceso_razon
		);
		
		if('ok' != $Modificado)
		{//Si ocurrio un error en el ingreso se notifica al usuario
			header('location: /pedidos/buscar/index/ierror');
			exit();
		}
		
		
		//** Modificacion de la ruta **//
		//** Ingresar con grupos mezclados era una cosa, Modificarlos es otra **//
		
		//Necesito conocer el camino que lleva la ruta de trabajo
		//Modulo para obtener la ruta de trabajo que debe seguir este grupo
		//$this->load->model('ruta/ruta_grupo_m', 'ruta');
		//Obtencion de la ruta
		//$Ruta = $this->ruta->generar_ruta($this->session->userdata('id_grupo'));
		
		
		
		
		//Hay rutas que poseen usuarios "enlaces" a otros grupos.
		//Se solicita la lista de esos usuarios para este grupo.
		$this->load->model('usuarios/usuario_grupo_m', 'usu_grup');
		$Usu_Grup = $this->usu_grup->listado();
		
		//Se modifica la ruta
		$this->load->model('pedidos/modificar_ruta_m', 'm_ruta');
		$Modificado = $this->m_ruta->index(
			$Id_Pedido,
			$Ruta['dptos'],
			$Fecha_Hora,
			$Puesto_Asignado,
			$Usu_Grup
		);
		
		//Es necesario crear tiempos abierto para los usuarios que continuan.
		$this->load->model('pedidos/tiempo_m', 'tiempo');
		
		//Si se indica que debe finalizarse el tiempo que este corriendo para este pedido
		if('true' == $Modificado['Estado'])
		{
			//Se realiza la finalizacion
			$this->tiempo->detener_tiempo($Id_Pedido);
		}
		
		
		
		
		
		//Si uno de los grupos agregados posee el estado "Asignado" se crea un proceso
		//y pedido "espejo" en el grupo agregado.
		if(0 < count($Modificado['Grupos']))
		{
			
			//Se crearan carpetas para los procesos o pedidos que se creen
			$this->load->model('utilidades/crear_carpetas','creacion_carp');
			
			
			//Un tiempo para el usuario del grupo que ha sido asignado en la ruta
			$this->tiempo->crear_tiempo($Id_Pedido, $Modificado['Grupos']['usuario']);
			
			//En las lista de clientes de cada grupo, existe un o mas clientes que son
			//utilizados para ingresar los procesos de otro grupo, esos clientes
			//estan especificados en una tabla y debo buscar los clientes de los grupos
			//antes encontrados que apuntan hacia el grupo que esta ingresando el pedido.
			$this->load->model('clientes/cliente_grupo_m', 'cli_gru');
			$Clientes_Grupos = $this->cli_gru->listado($Usu_Grup);
			
			//Carga del modelo que permite ingresar los datos.
			$this->load->model('procesos/crear_procesos_m', 'crea_proc');
			
			//Llamamos el modelo para poder almacenar los datos.
			$Id_Proceso = $this->crea_proc->guardar_proceso(
				$Clientes_Grupos[$Modificado['Grupos']['id_grupo']],
				$Existe['proceso'],
				$Existe['nombre_proceso'],
				$Modificado['Grupos']['id_grupo']
			);
			
			if('existe' == $Id_Proceso)
			{
				//Si el proceso ya existe se busca su id
				$Existe_ID = $this->buscar->id_cliente_proceso(
					$Clientes_Grupos[$Modificado['Grupos']['id_grupo']],
					$Existe['proceso'],
					$Modificado['Grupos']['id_grupo']
				);
				$Id_Proceso = $Existe_ID;
			}
			else
			{
				$this->creacion_carp->creacion_carpetas('', $Id_Proceso);
			}
			
			
			
			//Se debe crear un pedido para el proceso encontrado, y se carga el
			//modelo que verifica si tiene ruta sin finalizar este proceso
			$this->load->model('pedidos/procesando_m', 'procesando');
			//Se realiza la verificacion
			$Id_Pedido_Hijo = $this->procesando->proceso_ped($Id_Proceso);
			
			if(0 == $Id_Pedido_Hijo)
			{
				
				//Limpieza de variables
				$Fecha_Entrega = $this->seguridad_m->mysql_seguro(
					$this->input->post('fecha_entrega')
				);
				$Fecha_Entrega = $this->fechas_m->fecha_dmy_ymd($Fecha_Entrega);
				
				//Ingreso del pedido
				$this->load->model('pedidos/ingresar_m', 'ingresar');
				
				$Id_Pedido_Hijo = $this->ingresar->index(
					$Id_Proceso,
					$Fecha_Entrega,
					$Prioridad,
					$Tipo_Trabajo,
					$Id_Usu_Rechazo,
					$Fecha
				);
				
				$this->creacion_carp->creacion_carpetas('/'.$Id_Proceso, $Id_Pedido_Hijo);
				
				
				
				//Obtencion de la ruta
				$Ruta = $this->ruta->generar_ruta($Modificado['Grupos']['id_grupo']);
				
				//Se ingresa la ruta
				$this->load->model('pedidos/ingresar_ruta_m', 'i_ruta');
				$Ruta = $this->i_ruta->index(
					$Id_Pedido_Hijo,
					$Ruta,
					$Fecha_Hora,
					array(),
					$Modificado['Grupos']['id_grupo'],
					23
				);
				
				//Tiempo abierto para el planificador que retome el trabajo
				$this->tiempo->crear_tiempo($Id_Pedido_Hijo, 0);
				
			}
			else
			{
				//Debo finalizar el tiempo que esta corriendo para ventas y abrir uno para plani
				$this->load->model('pedidos/pedido_usuario_m', 'peus');
				$Id_Peus = $this->peus->buscar_id_peus($Id_Pedido_Hijo);
				if('Ventas' == $Id_Peus['codigo'])
				{
					$this->tiempo->finalizar($Id_Pedido_Hijo, $Id_Peus['id_peus']);
					//Tiempo abierto para el planificador que retome el trabajo
					$this->tiempo->crear_tiempo($Id_Pedido_Hijo, 0);
				}
			}
			
			
			//Busco si ya existe un enlace para estos dos Id_Pedido
			$this->load->model('pedidos/enlaces_m', 'enlace');
			$Enlace = $this->enlace->existe_enlace($Id_Pedido, $Id_Pedido_Hijo);
			
			//Ya hay un enlace para los dos pedidos?
			if('no' == $Enlace)
			{
				//Se crea el vinculo, enlace, union, como sea; de los pedidos que deben
				//quedar apuntandose
				$this->enlace->enlazar($Id_Pedido, $Id_Pedido_Hijo);
			}
			
			
		}
		//** Eso era todo? **//
		
		
		
		
		//**Si asigno una fecha para un trabajo, esa fecha debe actualizarse
		//en el pedido padre, si lo hubiere**//
		//Debo saber si este pedido que se esta modificando es hijo de otro fulano
		$this->load->model('pedidos/enlaces_m', 'enlace');
		$Enlace = $this->enlace->es_hijo(
			$Id_Pedido
		);
		
		if(0 != $Enlace)
		{
			$this->tiempo->fecha($Enlace['id_ped_primario'], false);
		}
		
		
		$Enlace = $this->enlace->es_padre(
			$Id_Pedido
		);
		
		if(0 != $Enlace)
		{
			$this->tiempo->fecha($Enlace['id_ped_secundario'], false);
		}
		
		
		
		
		//Modulo para obtener el listado de los productos para este cliente
		$this->load->model('productos/prod_cliente_m', 'productos');
		//Listado de departamentos activos y con formato especial
		$Productos = $this->productos->listado(
			$Existe['id_cliente'],
			's'
		);
		
		
		//Modulo para almacenar la cotizacion
		$this->load->model('pedidos/ingresar_cotizacion_m', 'cotizacion');
		//Ingreso de la cotizacion
		$Cotizacion = $this->cotizacion->index($Id_Pedido, $Productos, $Existe['id_cliente']);
		
		
		
		/** FALTA CRONOGRAMA **/
		
		
		//** Ingresamos la observacion **//
		
		$this->load->model('observaciones/guardar_m', 'g_observ');
		
		$this->g_observ->index(
			$Id_Pedido,
			'Pedido Modificado',//por: '.$this->session->userdata('nombre'),
			$Fecha_Hora
		);
		
		
		//Limpieza de variables
		$Observacion = $this->input->post('observaciones');
		$Observacion = str_replace('"', "'", $Observacion);
		$Aprobar = 'n';
		if('on' == $this->input->post('apro'))
		{
			$Aprobar = 's';
		}
		$this->g_observ->index($Id_Pedido, $Observacion, $Fecha_Hora, $Aprobar);
		
		
		
		//$Ruta = $this->ruta->generar_ruta($this->session->userdata('id_grupo'));
		/*$Usuarios = array();
		$Tiempos = array();
		
		foreach($Ruta as $Id_Ruta => $No_Importa)
		{
			if('' != $this->input->post('chk_'.$Id_Ruta))
			{
				$Usuarios[] = $this->input->post('slc_'.$Id_Ruta);
				$Tiempos[] = $this->input->post('tie_'.$Id_Ruta);
			}
		}*/
		
		if('i' == $Tipo_Ir)
		{
			header('location: /pedidos/preingreso/estado/Pendientes');
		}
		else
		{
			header('location: /pedidos/administrar/info/'.$Existe['id_proceso']);
		}
		
	}
	
}

/* Fin del archivo */