<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos_sin_sap_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	/**
	 *Modificar los materiales del sistema
	 *@param int Fecha de Inicio
	 *@param string Fecha de fin
	 *@return Array.
	*/
	function pedidos_sin_sap($Fecha_Inicio, $Fecha_Fin)
	{
		$Info = array();
		//Validamos que no exista el codigo sap dentro de la base de datos.
		//Verificamos que el material a mostrar pertenezca al grupo seleccionado.
		$Consulta = 'select distinct proc.id_cliente, cli.nombre as nom_cli, proc.id_proceso,
								proc.proceso, proc.nombre, ped.id_pedido, ped.fecha_reale
								from procesos proc, pedido ped, cliente cli,
								producto_pedido prod, pedido_sap sap
								where proc.id_proceso = ped.id_proceso
								and prod.id_pedido = ped.id_pedido
								and ped.id_pedido = sap.id_pedido
								and cli.id_cliente = proc.id_cliente
								and fecha_entrega >= "'.$Fecha_Inicio.'"
								and fecha_entrega <= "'.$Fecha_Fin.'"
								and id_tipo_trabajo != "4"
								and sap=""
								and id_grupo = "'.$this->session->userdata["id_grupo"].'"
								order by cli.codigo_cliente, ped.fecha_reale asc
								';
	//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		//Verificamos el resultado.
		//Si es igual a cero significa que el codigo sap no existe
		//y podemos proceder y actualizar la informacion.
		if(0 < $Resultado->num_rows())
		{
			//return $Resultado->result_array();
			foreach($Resultado->result_array() as $Datos)
			{
				$Info[$Datos['id_cliente']]['nombre_cli'] = $Datos['nom_cli'];
				$Info[$Datos['id_cliente']]['inform'][$Datos['id_pedido']]['id_pedido'] = $Datos['id_pedido'];
				$Info[$Datos['id_cliente']]['inform'][$Datos['id_pedido']]['proceso'] = $Datos['proceso'];
				$Info[$Datos['id_cliente']]['inform'][$Datos['id_pedido']]['nombre'] = $Datos['nombre'];
				$Info[$Datos['id_cliente']]['inform'][$Datos['id_pedido']]['fecha_reale'] = $Datos['fecha_reale'];
			}
			//print_r($Info);
			return $Info;
		}
		else
		{
			return array();
		}
	}
}
?>