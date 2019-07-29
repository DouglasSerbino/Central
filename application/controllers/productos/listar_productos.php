<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listar_productos extends CI_Controller {
	
	/**
	 *Busca todos los productos que son aplicables al cliente asignado.
	 *@param string $Id_Cliente.
	 *@return nada.
	*/
	public function index($Id_Cliente)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Validacion pequenha
		$Id_Cliente += 0;
		if(0 == $Id_Cliente)
		{
			show_404();
			exit();
		}
		
		
		//Modulo para obtener el listado de los productos para este cliente
		$this->load->model('productos/prod_cliente_m', 'productos');
		//Listado de departamentos activos y con formato especial
		$Productos = $this->productos->listado(
			$Id_Cliente,
			's'
		);
		
		$Variables['Ajax'] = array();
		//La informacion recibida esta en formato array, debo pasarla a texto
		foreach($Productos as $Index => $Producto)
		{
			$Variables['Ajax'][$Index] = '{"ipc":"'.$Producto['id_prod_clie'].'"';
			$Variables['Ajax'][$Index] .= ',"pro":"'.$Producto['producto'].'"';
			$Variables['Ajax'][$Index] .= ',"pre":"'.$Producto['precio'].'"';
			$Variables['Ajax'][$Index] .= ',"can":"'.$Producto['cantidad'].'"';
			$Variables['Ajax'][$Index] .= ',"idc":"'.$Producto['id_producto'].'"';
			$Variables['Ajax'][$Index] .= ',"con":"'.$Producto['concepto'].'"}';
		}
		
		echo '{"prod":['.implode(', ', $Variables['Ajax']).']}';
		
	}
	
}

/* Fin del archivo */