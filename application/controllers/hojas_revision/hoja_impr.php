<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hoja_impr extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos las hojas de revision
	 *@return nada.
	*/
	public function index($Id_pedido = '', $tipo_hoja = '')
	{
		//Los unicos que no podran acceder a esta pagina son los clientes.
		$this->ver_sesion_m->no_clientes();
		$Hoja = '';
		//Verificamos que tipo es y asi mostraremos el encabezado.
		if($tipo_hoja == '1')
		{
			$Hoja = 'HOJA DE REVISION DE ARTES';
		}
		elseif($tipo_hoja == '2')
		{
			$Hoja = 'HOJA DE REVISION DE NEGATIVOS';
		}
		elseif($tipo_hoja == '3')
		{
			$Hoja = 'HOJA DE REVISION DE PLANCHAS OFFSET';
		}
		elseif($tipo_hoja == '4')
		{
			$Hoja = ' ELEMENTOS FINALES DE PRE-PRENSA';
		}
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => $Hoja,
			'Mensaje' => ''
		);
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Declaramos las variables.
		$Variables['Id_pedido'] = $Id_pedido;
		//Modelo encargado de mostrar la informacion.
		$this->load->model('hojas_revision/hojas_revision_m', 'hojas');
		$this->load->model('hojas_revision/buscar_hojas_m', 'buscar_m');
		
		//Cargamos la funcion encargada de mostrar la informacion del cliente y el proceso.
		$Variables['Cliente_Procesos'] = $this->hojas->cliente_proceso($Id_pedido);
		
		//Mostramos la  informacion de la hoja.
		$Variables['mostrar_hoja'] = $this->buscar_m->mostrar_hoja_revision($Id_pedido, $tipo_hoja);
		$Variables['mostrar_colores'] = $this->buscar_m->buscar_colores($Id_pedido);
		
		//Cargamos la vista correspondiente al departamento seleccionado.
		if($tipo_hoja == '1')
		{
			$this->load->view('hojas_revision/hoja_imp_arte_v', $Variables);
		}
		elseif($tipo_hoja == '2')
		{
			$this->load->view('hojas_revision/hoja_imp_preprensa_v', $Variables);
		}
		elseif($tipo_hoja == '3')
		{
			$this->load->view('hojas_revision/hoja_imp_offset_v', $Variables);
		}
		elseif($tipo_hoja == '4')
		{
			$this->load->view('hojas_revision/hoja_imp_tiff_v', $Variables);
		}

		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */