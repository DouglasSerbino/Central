<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Preingreso extends CI_Controller {
	
	/**
	 *Busca todos los pedidos que este usuario tiene: pendientes, en proceso o
	 *en aprobacion. Y los lista por fecha de entrega y luego id_pedido.
	 *@param string $Estado Mostrar todos, procesando, en aprobacion o atrazados?.
	 *@return nada.
	*/
	public function estado($Estado = 'Pendientes', $Mensaje = '')
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza.
		$Estado = $this->seguridad_m->mysql_seguro($Estado);
		
		$Trabajos = 'no';
		
		if('procesando' != $Estado && 'aprobacion' != $Estado && 'atrazados' != $Estado)
		{
			$Estado = 'Pendientes';
		}
		
		//Mensajes de usuario
		if('iok' == $Mensaje)
		{
			$Mensaje = 'El pedido fue ingresado con &eacute;xito.';
		}
		elseif('rok' == $Mensaje)
		{
			$Mensaje = 'El pedido fue rechazado.';
		}
		elseif('tod' == $Mensaje)
		{
			$Trabajos = 'si';
			$Mensaje = '';
		}
		else
		{
			$Mensaje = '';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Listado de Trabajos '.$Estado,
			'Mensaje' => $Mensaje,
			'Estado' => $Estado
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Pedidos que estan en el puesto de este usuario
		$this->load->model('pedidos/preingreso_m', 'pendientes');
		$Variables['Pedidos'] = $this->pendientes->listado($Estado, 0, $Trabajos);
		
		
		
		$this->load->model('usuarios/listado_usuario_m', 'usuarios');
		$Variables['Usuarios'] = $this->usuarios->listado('s', 's');
		
		
		$this->load->model('rechazos/razones_m', 'rech_raz');
		$Variables['Rech_Razones'] = $this->rech_raz->listado();
		
		$Variables['Redir'] = '/pedidos/preingreso/estado/Pendientes';
		
		$Variables['Trab'] = $Trabajos;
		
		//Listado de trabajos pendientes
		$this->load->view('pedidos/preingreso_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
		
	}
	
}

/* Fin del archivo */