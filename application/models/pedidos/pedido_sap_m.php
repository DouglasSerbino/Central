<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_sap_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Infomacion del pedido sap y todo lo que eso conlleva.
	 *@param string $Id_Pedido.
	 *@return array.
	*/
	function informacion($Id_Pedido)
	{
		
		$Consulta = '
			select * from pedido_sap where id_pedido = "'.$Id_Pedido.'" limit 0, 1
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
	
  /**
	 *Modificacion de la informacion del pedido sap segun la informacion recibida.
	 *@param string $Id_Pedido.
	 *@param string $Pedido_SAP.
	 *@param string $Venta.
	 *@param string $Orden.
	 *@return nada.
	*/
	function modificar(
		$Id_Pedido,
		$Pedido_SAP,
		$Venta,
		$Orden
	)
	{
		
		$Consulta = '
			update pedido_sap
			set sap = "'.$Pedido_SAP.'", orden = "'.$Orden.'", venta = "'.$Venta.'"
			where id_pedido = "'.$Id_Pedido.'"
		';
		
		$this->db->query($Consulta);
		
	}
	
}

/* Fin del archivo */