<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sol_pedido_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function guardar_solicitud($id_material)
	{
		$Fecha = date('Y-m-d H:i:s', strtotime('+ 1 days', strtotime(date('Y-m-d H:i:s'))));
		//Consulta para insertar el usuario
		$Consulta = '
			insert into pedido_transito_solicitud values(
				NULL,
				"'.$id_material.'",
				"'.date('Y-m-d H:m:s').'",
				"'.$Fecha.'",
				"s"
			)
		';
		//echo $Consulta;
		//Ejecuto la consulta
		$this->db->query($Consulta);
		return 'ok';
		
	}
	
}

/* Fin del archivo */