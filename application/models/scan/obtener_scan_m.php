<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Obtener_scan_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos el archivo senhalado y devuelve su ruta para descarga
	 *@param string $Id_PA: Id_Pedido_adjuntos
	 *@return array
	*/
	function archivo($Id_PA)
	{
		
		$Consulta = '
			select url, nombre_adjunto, mime_type
			from cliente clie, procesos proc, pedido ped, pedido_adjuntos pead
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = pead.id_pedido and id_pedido_adjuntos = "'.$Id_PA.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Adjunto = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Adjunto['url'] = $Fila['url'];
			$Adjunto['nombre'] = $Fila['nombre_adjunto'];
			$Adjunto['nombre'] = str_replace(' ', '_', $Adjunto['nombre']);
			$Adjunto['mime'] = $Fila['mime_type'];
		}
		
		return $Adjunto;
		
	}
}

/* Fin del archivo */