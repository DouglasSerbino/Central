<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar_sap_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Funcion que nos servira para buscar el proceso digitado por el usuario.
	 *Necesitamos el codigo del cliente y el numero de proceso.
	 **/
	
	public function buscar_procesos($cod_cliente, $proceso)
	{
		//Declaramos los array para almacenar la informacion.
		$contenido = array();
		$pedidos_v = array();
		//Establecemos la consulta para mostrar los pedidos que corresponden a este proceso.
		$Consulta = 'select id_pedido, fecha_entrada, cli.id_cliente
								from procesos proc, pedido ped, cliente cli
								where proc.id_proceso = ped.id_proceso
									and cli.id_cliente = proc.id_cliente
									and codigo_cliente = "'.$cod_cliente.'"
									and proceso = "'.$proceso.'"
									and fecha_entrada > "2012-01-01"
									and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
								order by id_pedido asc';
		//echo $Consulta;
		//Ejecutamos la consulta.
		$Resultado2 = $this->db->query($Consulta);
		//Asignamos el resultado a un array.
		$Info = $Resultado2->result_array();
		//Exploramos el array.
		foreach($Info as $fila)
		{
			$pedidos_v[$fila['id_pedido']] = $fila['fecha_entrada'];
			$id_cliente = $fila['id_cliente'];
		}
		
		//Ahora buscare los pedidos que estan ingresados en pedido_sap
		$pedidos_con_sapo = array();
		//Buscamos los pedidos que estan en pedido_sap.
		$Consulta2 = 'select ped.id_pedido, ped.id_pedido
									from procesos proc, pedido ped,
									pedido_sap ped_sap, cliente cli
									where proc.id_proceso = ped.id_proceso
										and cli.id_cliente = proc.id_cliente
										and ped.id_pedido = ped_sap.id_pedido
										and cli.codigo_cliente = "'.$cod_cliente.'"
										and proceso = "'.$proceso.'"
										and sap != ""
									order by ped.id_pedido asc';
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta2);
		//Asignamos el resultado a un array.
		$Info_ped = $Resultado->result_array();
		foreach($Info_ped as $fila2)
		{
			$pedidos_con_sapo[$fila2['id_pedido']] = true;
		}
		//Asignamos la informacion en un array.
		foreach($pedidos_v as $id_pedido => $fecha_entrada)
		{//Recorro los pedidos que corresponden al proceso dado
			if(!isset($pedidos_con_sapo[$id_pedido]))
			{//Y si uno de estos pedidos no contiene numero sapo
				$contenido[$id_pedido]['id_pedido'] = $id_pedido;
				$contenido[$id_pedido]['fecha_entrada'] = $fecha_entrada;
				$contenido[$id_pedido]['id_cliente'] = $id_cliente;
			}
		}
		return $contenido;
	}
	
	
	/**
	 *Funcion para ingresar el pedido sap en la base de datos.
	*/
	public function agregar_pedido_sap($id_cliente, $id_pedido, $pedido_sap, $venta, $ordenes, $fecha)
	{
		//Es posible que este pedido ya tenga informacion enpedido_sap pero como no lo se, mejor voy a realizar un borrado y si existe que lo borre y luego lo reingreso, sino tiene pues esta consulta regresara cero filas afectadas... he dicho
		$Consulta = 'delete from pedido_sap where id_pedido = "'.$id_pedido.'"';
		
		$Resultado = $this->db->query($Consulta);
	
		$Consulta_insert = 'insert into pedido_sap values(NULL,
																				"'.$id_cliente.'", "'.$id_pedido.'",
																				"'.$pedido_sap.'", "'.$ordenes.'",
																				"'.$venta.'", "'.$fecha.'",
																				"Pe",
																				"",
																				""
																				)';
	
		$Resultado2 = $this->db->query($Consulta_insert);
		
		if($Resultado2)
		{
			return 'ok';
		}
		else
		{
			return '';
		}
	}
	
}

/* Fin del archivo */