<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar_info_material_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function agregar_informacion($Id_inventario_material, $proveedor, $cod_plancha,
															$numero_individual, $numero_cajas, $plancha_tipo, $tamanho, $tipo)
	{
		//Consulta para almacenar la informacion.
		$Consulta = 'INSERT INTO inventario_material_detalle values(null, "'.$Id_inventario_material.'", 
																										"'.$proveedor.'", "'.$cod_plancha.'" , "'.$numero_individual.'",
																										"'.$numero_cajas.'", "'.$plancha_tipo.'", "'.$tamanho.'", "'.$tipo.'"
																										)';
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		return 'ok';
		
	}
}
/* Fin del archivo */