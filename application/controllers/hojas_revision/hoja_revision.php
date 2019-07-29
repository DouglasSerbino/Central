<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hoja_revision extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos las hojas de revision
	 *@return nada.
	*/
	public function index($Id_pedido = '', $tipo = '')
	{
		//Los unicos que no podran acceder a esta pagina son los clientes.
		$this->ver_sesion_m->no_clientes();
		$Hoja = '';
		//Verificamos que tipo es y asi mostraremos el encabezado.
		if($tipo == 'arte')
		{
			$Hoja = ' ARTE';
		}
		elseif($tipo == 'preprensa')
		{
			$Hoja = ' NEGATIVOS';
		}
		elseif($tipo == 'offset')
		{
			$Hoja = ' PLANCHAS OFFSET';
		}
		elseif($tipo == 'tiff')
		{
			$Hoja = ' TIFF';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'HOJA DE REVISION DE'.$Hoja,
			'Mensaje' => ''
		);
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		$Variables['Id_pedido'] = $Id_pedido;
		//Modelo encargado de mostrar la informacion.
		$this->load->model('hojas_revision/hojas_revision_m', 'hojas');
		//Cargamos la funcion encargada de mostrar la informacion.
		$Variables['Cliente_Procesos'] = $this->hojas->cliente_proceso($Id_pedido);
		$Variables['Buscar_color'] = $this->hojas->buscar_colores($Id_pedido);
		
		//Cargamos la vista correspondiente al departamento seleccionado.
		if($tipo == 'arte')
		{
			$this->load->view('hojas_revision/hoja_arte_v', $Variables);
		}
		elseif($tipo == 'preprensa')
		{
			$this->load->view('hojas_revision/hoja_preprensa_v', $Variables);
		}
		elseif($tipo == 'offset')
		{
			$this->load->view('hojas_revision/hoja_offset_v', $Variables);
		}
		elseif($tipo == 'tiff')
		{
			$this->load->view('hojas_revision/hoja_tiff_v', $Variables);
		}
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
	/**
	 *Funcion que nos permitira almacenar la informacion de las hojas de revision.
	*/
	public function hojas_sql()
	{
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que permite ingresar los datos.
		$this->load->model('hojas_revision/hojas_revision_m', 'hojas_m');
		
		$tipo = $this->seguridad_m->mysql_seguro($this->input->post('tipo'));
		
		$Campos = array();
		//Limpieza de variables
		$Id_pedido = $this->seguridad_m->mysql_seguro($this->input->post('id_pedido'));
		$Campos['campo']['fotocelda'] = $this->seguridad_m->mysql_seguro($this->input->post('fotocelda'));
		$Campos['campo']['id_pedido'] = $Id_pedido;
		$Campos['campo']['codigo_barra'] = $this->seguridad_m->mysql_seguro($this->input->post('codigo_barra'));
		$Campos['campo']['diseno'] = $this->seguridad_m->mysql_seguro($this->input->post('diseno'));
		$Campos['campo']['color'] = $this->seguridad_m->mysql_seguro($this->input->post('color'));
		
		if($tipo == 'arte')
		{
			$Campos['campo']['dimensiones'] = $this->seguridad_m->mysql_seguro($this->input->post('dimensiones'));
			$Campos['campo']['textos'] = $this->seguridad_m->mysql_seguro($this->input->post('textos'));
			
			//Llamamos el modelo para poder almacenar los datos.
		}
		elseif($tipo == 'preprensa')
		{
			$Campos['campo']['separado'] = $this->seguridad_m->mysql_seguro($this->input->post('separado'));
			$Campos['campo']['montaje'] = $this->seguridad_m->mysql_seguro($this->input->post('montaje'));
			$Campos['campo']['negativo'] = $this->seguridad_m->mysql_seguro($this->input->post('negativo'));
		}
		elseif($tipo == 'offset')
		{
			foreach($_POST as $nombre_campo => $Datos)
			{
				$Campos['campo'][$nombre_campo] = $this->seguridad_m->mysql_seguro($Datos);
			}
		}
		elseif($tipo == 'tiff')
		{
			$Campos['campo']['textos'] = $this->seguridad_m->mysql_seguro($this->input->post('textos'));
		}
		
		//Llamamos al modelo para guardar la informacion.
		$Almacenar = $this->hojas_m->guardar_datos($Campos, $tipo);
		
		if('' == $Almacenar)
		{//Si el grupo se guardo con exito
			//Enviamos a la pagina de agregar por si se quiere agregar
			//un nuevo grupo.
			header('location: /pedidos/detalle_activo/index/'.$Id_pedido);
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /pedidos/detalle_activo/index/'.$Id_pedido.'/'.$Almacenar);
			//Evitamos que se continue ejecutando el script
			exit();
		}	
	}
	
	/**
	 *Funcion que nos permitira almacenar la informacion de las hojas de revision.
	*/
	public function comprobar_hoja()
	{
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que permite ingresar los datos.
		$this->load->model('hojas_revision/hojas_revision_m', 'hojas_m');
		
		
		
		$Campos = array();
		//Limpieza de variables
		
		$Id_pedido = $this->seguridad_m->mysql_seguro($this->input->post('id_pedido_hoja'));
		$Campos['campo']['hoja_correcta'] = $this->seguridad_m->mysql_seguro($this->input->post("hoja_correcta"));
		$Campos['campo']['hoja'] = $this->seguridad_m->mysql_seguro($this->input->post("hoja"));
		$Campos['campo']['hoja_tipo'] = $this->seguridad_m->mysql_seguro($this->input->post("hoja_tipo"));
		$Campos['campo']['id_pedido'] = $Id_pedido;
		if(isset($_POST["rechazo_hoja"]))
		{
			$Campos['campo']['rechazo_hoja'] = $this->seguridad_m->mysql_seguro($this->input->post("rechazo_hoja"));
		}
		else
		{
			$Campos['campo']['rechazo_hoja'] = '';
		}
		
		//Llamamos al modelo para mostrar la informacion.
		$Almacenar = $this->hojas_m->verificar_hojas_revision($Campos);
		
		
		if('ok' == $Almacenar)
		{//Si el grupo se guardo con exito
			//Enviamos a la pagina de agregar por si se quiere agregar
			//un nuevo grupo.
			header('location: /pedidos/detalle_activo/index/'.$Id_pedido);
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /pedidos/detalle_activo/index/'.$Id_pedido);
			//Evitamos que se continue ejecutando el script
			exit();
		}	
	}
	
	
	
}

/* Fin del archivo */