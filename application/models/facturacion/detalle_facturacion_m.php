<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle_facturacion_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function facturar_pedido($Informacion)
	{
		$factura = $Informacion['factura'];
		$Id_cliente = $Informacion['id_cliente'];
		$Fecha = $Informacion['fecha_fac'];
		if(isset($Informacion['selected']))
		{
			foreach($Informacion['selected'] as $Datos)
			{
				$Consulta = 'update pedido_sap set
													confirmada = "Si",
													factura = "'.$factura.'",
													fecha = "'.$Fecha.'"
													where id_pedido_sap = "'.$Datos['id_pedido_sap'].'"';
				//echo $Consulta.'<br><br>';
				$Resultado2 = $this->db->query($Consulta);
			}
		}
		return $Id_cliente;
	}
}

/* Fin del archivo */