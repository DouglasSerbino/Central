<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rechazo_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function rechazos()
	{
		$fecha = date('Y-m');
			$Consulta = '
				select fecha, explicacion, usuario, rech.id_pedido, usu.id_usuario, rechazo_razon
				from pedido_rechazo rech, usuario usu, rechazo_razones raz
				where fecha >= "'.$fecha.'-01"
				and fecha <= "'.$fecha.'-31"
				and usu.id_usuario = rech.id_usuario
				and raz.id_rechazo_razones = rech.id_rechazo_razon
				order by id_pedido_rechazo desc
			';
			//echo $Consulta;
			$Resultado = $this->db->query($Consulta);
			if(0 < $Resultado->num_rows())
			{
				return $Resultado->result_array();
			}
			else
			{
				return array();
			}
	}
	
	function quitar_rechazo($pedido, $usuario)
	{
			$Consulta = '
						delete from pedido_rechazo
						where id_pedido = "'.$pedido.'"
						and id_usuario = "'.$usuario.'"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			return 'ok';
	}

}

/* Fin del archivo */