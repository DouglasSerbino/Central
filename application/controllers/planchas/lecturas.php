<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lecturas extends CI_Controller {
	
	/**
	 *Pagina que mostrara la informacion de las planchas.
	*/
	public function index($Id_Lectura = 0)
	{
		
		
		$Id_Lectura += 0;
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Mediciones de Referencia de Planchas',
			'Mensaje' => '',
			'Id_Lectura' => $Id_Lectura
		);
		
		
		//Lecturas de referencias y los parametros disponibles
		$this->load->model('planchas/lecturas_m', 'lecturas');
		$Variables['Referencias'] = $this->lecturas->referencias();
		$Variables['Compensacion'] = $this->lecturas->compensacion();
		$Variables['Plancha'] = $this->lecturas->planchas();
		$Variables['Sistema'] = $this->lecturas->sistema();
		$Variables['Altura'] = $this->lecturas->altura();
		$Variables['Trama'] = $this->lecturas->trama();
		$Variables['Lineaje'] = $this->lecturas->lineaje();
		
		
		//Listado de los clientes de este grupo
		$this->load->model('clientes/busquedad_clientes_m', 'clientes');
		$Variables['Clientes'] = $this->clientes->mostrar_clientes();
		
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Cargamos la vista
		$this->load->view('planchas/lecturas_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	
	
	
	public function Agregar()
	{
		
		$Tipo = 'ref';
		if('lect' != $this->input->post('redir'))
		{
			$Tipo = 'rea';
		}
		
		
		if(0 == ($this->input->post('id_pedido')+0))
		{
			$Consulta = '
				select id_pla_medicion
				from pla_medicion
				where compensacion = "'.($this->input->post('compensacion')+0).'"
				and plancha = "'.($this->input->post('plancha')+0).'"
				and sistema = "'.($this->input->post('sistema')+0).'"
				and altura = "'.($this->input->post('altura')+0).'"
				and trama = "'.($this->input->post('trama')+0).'"
				and lineaje = "'.($this->input->post('lineaje')+0).'"
				and id_cliente = "'.($this->input->post('cliente')+0).'"
				and tipo = "'.$Tipo.'"
			';
		}
		else
		{
			$Consulta = '
				select id_pla_medicion
				from pla_medicion
				where tipo = "'.$Tipo.'"
				and id_pedido = "'.($this->input->post('id_pedido')+0).'"
			';
		}
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows())
		{
			$Nuevo = true;
			$Consulta = '
				insert into pla_medicion values(
					NULL,
					"'.($this->input->post('cliente')+0).'",
					"'.($this->input->post('compensacion')+0).'",
					"'.($this->input->post('plancha')+0).'",
					"'.($this->input->post('sistema')+0).'",
					"'.($this->input->post('altura')+0).'",
					"'.($this->input->post('trama')+0).'",
					"'.($this->input->post('lineaje')+0).'",
					"'.$Tipo.'",
					"'.($this->input->post('med_version')+0).'",
					"'.($this->input->post('id_pedido')+0).'"
				)
			';
			
			$this->db->query($Consulta);
			$Id_Medicion = $this->db->insert_id();
		}
		else
		{
			$Nuevo = false;
			$Id_Medicion = $Resultado->row_array();
			$Id_Medicion = $Id_Medicion['id_pla_medicion'];
			
			
			$Consulta = '
				update pla_medicion set
				compensacion = "'.($this->input->post('compensacion')+0).'",
				plancha = "'.($this->input->post('plancha')+0).'",
				sistema = "'.($this->input->post('sistema')+0).'",
				altura = "'.($this->input->post('altura')+0).'",
				trama = "'.($this->input->post('trama')+0).'",
				lineaje = "'.($this->input->post('lineaje')+0).'",
				id_cliente = "'.($this->input->post('cliente')+0).'"
				where id_pla_medicion = "'.$Id_Medicion.'"
			';
			$this->db->query($Consulta);
			
			
			$Consulta = '
				delete from pla_medicion_color
				where id_pla_medicion = "'.$Id_Medicion.'"
			';
			$this->db->query($Consulta);
		}
		
		
		
		
		
		$Colores_v = array('c', 'm', 'y', 'k', 'r', 'g', 'b');
		foreach($Colores_v as $Color)
		{
			$Consulta = '
				insert into pla_medicion_color values(
					NULL,
					"'.$Id_Medicion.'",
					"'.$this->seguridad_m->mysql_seguro($this->input->post($Color.'_5')).'",
					"'.$this->seguridad_m->mysql_seguro($this->input->post($Color.'_25')).'",
					"'.$this->seguridad_m->mysql_seguro($this->input->post($Color.'_50')).'",
					"'.$this->seguridad_m->mysql_seguro($this->input->post($Color.'_75')).'",
					"'.$this->seguridad_m->mysql_seguro($this->input->post($Color.'_100')).'",
					"'.$Color.'"
				)
			';
			$this->db->query($Consulta);
		}
		
		
		if($Nuevo)
		{
			mkdir('./mediciones/'.$Id_Medicion, 777);
			chmod('./mediciones/'.$Id_Medicion, 777);
		}
		
		
		if(isset($_FILES['pdf']['tmp_name']) && '' != $_FILES['pdf']['tmp_name'])
		{
			
			$files = glob('./mediciones/'.$Id_Medicion.'/*'); // get all file names
			foreach($files as $file){ // iterate files
				if(is_file($file))
				{
					chmod($file, 0777);
					unlink($file); // delete file
				}
			}
			//exit();
			//Informacion necesaria del archivo a guardar
			$Archivo = $_FILES['pdf']['tmp_name'];
			$Mime_Type = $_FILES['pdf']['type'];
			$Nombre  = $this->seguridad_m->mysql_seguro(
				$_FILES['pdf']['name']
			);
			
			
			//El archivo se mueve de la carpeta temporal a la final
			$mover = move_uploaded_file($Archivo, './mediciones/'.$Id_Medicion.'/'.$Nombre);
			chmod('./mediciones/'.$Id_Medicion.'/'.$Nombre, 0777);
			
			
			$zip = new ZipArchive;
			$res = $zip->open('./mediciones/'.$Id_Medicion.'/'.$Nombre);
			if($res === TRUE)
			{
				$zip->extractTo('./mediciones/'.$Id_Medicion);
				$zip->close();
				
				$files = glob('./mediciones/'.$Id_Medicion.'/*'); // get all file names
				foreach($files as $file){ // iterate files
					if(is_file($file))
					{
						rename($file, str_replace('%', '', $file));
					}
				}
			}
			else
			{
				echo 'falló, código:' . $res;
			}
		}
		
		
		if('pedi' == $this->input->post('redir'))
		{
			header('location: /pedidos/detalle_activo/index/'.($this->input->post('id_pedido')+0));
		}
		elseif('deta' == $this->input->post('redir'))
		{
			header('location: /pedidos/pedido_detalle/index/'.($this->input->post('id_pedido')+0));
		}
		else
		{
			header('location: /planchas/lecturas');
		}
		
		
	}
	
	
	
}

/* Fin del archivo */