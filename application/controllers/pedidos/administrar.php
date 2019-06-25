<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrar extends CI_Controller {
	
	/**
	 *Existe el proceso ingresado.
	 *@return nada.
	*/
	public function index()
	{
		
		
		$Redir = $this->seguridad_m->mysql_seguro(
			$this->input->post('redir')
		);
		$Direccion = $this->seguridad_m->mysql_seguro(
			$this->input->post('direccion')
		);
		$Codigo_Cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('cliente')
		);
		$Proceso = $this->seguridad_m->mysql_seguro(
			$this->input->post('proceso')
		);
		
		$Id_Producto = $this->input->post('productos');
		if('--' == $Id_Producto)
		{
			$Id_Producto = 0;
		}
		$Id_Producto += 0;
		
		if('' == $Codigo_Cliente && '' == $Proceso && 0 == $Id_Producto)
		{
			
			header('location: '.$Redir.'/existe');
			exit();
			
		}
		
		//Modelo para buscar diversa informacion del proceso
		$this->load->model('procesos/buscar_proceso_m', 'info');
		
		if(0 != $Id_Producto)
		{
			
			//Solicito la informacion completa
			$Variables['Info_Proceso'] = $this->info->id_proceso($Id_Producto);
			
			//Si quieren hacer trampa
			if('' == $Variables['Info_Proceso'])
			{
				header('location: '.$Redir.'/existe');
				exit();
			}
			
			header('location: '.$Direccion.'/'.$Variables['Info_Proceso']['id_proceso']);
			exit();
			
		}
		
		
		//Solicito la informacion completa
		$Variables['Info_Proceso'] = $this->info->cliente_proceso(
			$Codigo_Cliente,
			$Proceso
		);
		
		$Variables['num_cajas'] = 3;
		//Si quieren hacer trampa
		if(0 == count($Variables['Info_Proceso']))
		{
			header('location: '.$Redir.'/existe');
			exit();
		}
		
		$mandar = '';
		if(isset($Variables['Info_Proceso']['id_proceso']))
		{
			$mandar = $Variables['Info_Proceso']['id_proceso'];
		}
		header('location: '.$Direccion.'/'.$mandar);
		exit();
		
		
	}
	
	
	
	/**
	 *Busca la info del proceso enviado y los procesos que le pertenecen.
	*/
	public function info($Id_Proceso = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		
		
		$Id_Proceso += 0;
		
		if(0 == $Id_Proceso)
		{
			show_404();
			exit();
		}
		
		$Variables = array(
			'Titulo_Pagina' => 'Administraci&oacute;n de Pedido',
			'Mensaje' => '',
			'Id_Proceso' => $Id_Proceso
		);
		
		//Informacion del Proceso
		$this->load->model('procesos/buscar_proceso_m', 'info');
		//Solicito la informacion completa
		$Variables['Info_Proceso'] = $this->info->id_proceso($Id_Proceso);
		
		
		if(0 == count($Variables['Info_Proceso']))
		{
			show_404();
			exit();
		}
		
		
		$this->ver_sesion_m->solo_un_cliente($Variables['Info_Proceso']['id_cliente']);
		
		
		$this->load->model('pedidos/lista_pedidos_m', 'listar');
		$Variables['Pedidos'] = $this->listar->listar($Id_Proceso);
		

		//Necesito la miniatura
		$Consulta = '
			select url
			from proceso_imagenes
			where id_proceso = "'.$Id_Proceso.'"
			order by id_proceso_imagenes desc
			limit 0, 1
		';
		$Resultado = $this->db->query($Consulta);

		$Variables['Miniatura'] = '';
		if(1 == $Resultado->num_rows())
		{
			$Variables['Miniatura'] = $Resultado->row_array();
			$Variables['Miniatura'] = $Variables['Miniatura']['url'];
		}
		
		
		//$Variables['Pedidos2'] = array();
		
		//Quiero cargar los pedidos que fueron creados para este grupo antes
		//que se convirtiera en grupo, eso fue antes del 14-mayo-2012
		/*$this->load->model('clientes/cliente_grupo_m', 'cligru');
		$Cliente_grupo = $this->cligru->soy_cliente_de_repro();
		
		print_r($Cliente_grupo); if(0 < count($Cliente_grupo))
		{
			$Proceso_Cliente = $this->info->cliente_proceso(
				$Cliente_grupo['codigo_cliente'],
				$Variables['Info_Proceso']['proceso'],
				1
			);
			
			if(isset($Proceso_Cliente['id_proceso']))
			{
				$Variables['Pedidos'] += $this->listar->listar(
					$Proceso_Cliente['id_proceso'],
					true//Indica que me puede dar la info de los pedidos aunque no me correspondan
				);
			}
			
			
			krsort($Variables['Pedidos']);
			
			
		}*/
		//print_r($Variables['Pedidos']);
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		$Variables['Redir'] = '/pedidos/administrar/info/'.$Id_Proceso;
		$Variables['num_cajas'] = 3;
		
		$this->load->view('pedidos/administrar_v', $Variables);
		
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
	
	
}

/* Fin del archivo */