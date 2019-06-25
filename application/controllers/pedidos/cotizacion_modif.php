<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cotizacion_modif extends CI_Controller {
	
	/**
	 *Modificacion del Pedido y su ruta de trabajo.
	 *@param string $Id_Pedido.
	 *@param string $Activo. Si "activo" debe dirigir a detalle
	 *@return nada.
	*/
	public function index($Id_Pedido, $Activo = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Despacho' => '', 'Ventas' => '');
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
		
		
		
		$this->load->model('pedidos/pedido_detalle_m', 'ped_det');
		$Cotizacion = $this->ped_det->buscar_cotizacion($Id_Pedido);
		

		$Coti_Anterior = '';
		foreach($Cotizacion as $Elementos)
		{
			foreach ($Elementos as $Item)
			{
				$Coti_Anterior .= $Item['producto'].' - Precio: $'.$Item['precio'].' - Cantidad: '.$Item['cantidad'].'<br />';
			}
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
		$this->cotizacion->actualizar_cotizacion($Id_Pedido);
		$Observacion = 'La cotizacion fue modificada.<br />Anterior:<br />'.$Coti_Anterior.'Nueva:<br />'.$Cotizacion;
		
		$this->load->model('observaciones/guardar_m', 'g_observ');
		$this->g_observ->index($Id_Pedido, $Observacion, date('Y-m-d H:i:s'), 'p');
		
		
		if('activo' == $Activo)
		{
			header('location: /pedidos/detalle_activo/index/'.$Id_Pedido);
		}
		else
		{
			header('location: /pedidos/pedido_detalle/index/'.$Id_Pedido);
		}
		
	}
	
}

/* Fin del archivo */