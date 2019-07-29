<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_observaciones_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function modificar($observacion, $id_usuario, $id_pedido, $id_observacion)
	{
		$Consulta = '
			update observacion set observacion = "'.$observacion.'"
			where id_usuario = "'.$id_usuario.'"
			and id_pedido = "'.$id_pedido.'"
			and id_observacion = "'.$id_observacion.'"
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		return 'ok';
	}
	
	
	
	function cambio_estado($estado, $id_material, $id_pedido)
	{
		if($estado == 'Si')
		{
			$estado = 'false';
		}
		else
		{
			$estado = 'on';
		}
		$Consulta = '
			update pedido_material set reproceso = "'.$estado.'"
			where id_pedido = "'.$id_pedido.'"
			and id_inventario_material = "'.$id_material.'"
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		return 'ok';
	}
}