<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hoja_tiempo_consumo_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
  /**
	 *Buscar en la base de datos los pedidos pendientes de notifiacion
	 *@param string $Id_cliente.
	 *@return string array con informacion del los pedidos.
	*/
	function buscar_pedidos_notificacion($Id_cliente, $no, $mes = '', $anho = '')
	{
		//Establecemos la consulta para extraer la informacion del cliente.
		$Consulta = '
			select nombre as nombre_cliente, id_cliente, codigo_cliente
			from cliente 
			Where id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by id_cliente
		';
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		//Asignamos el array a una variable.
		$Info = $Resultado->result_array();
		$proceso_ven = array();
		//Exploramos el array.
		foreach($Info as $Datos)
		{
			//Si la variable no = ok: Queremos extraer los pedidos que no se han notificado.
			if($no == 'ok')
			{
				$SQL = 'confirmada = "no"';
				$SQL2 = 'and ped.fecha_reale != "0000-00-00"';
			} //Si la variable no = reporte: mostramos un reporte con los pedidos que no se han notificado.
			elseif($no == 'reporte')
			{
				$SQL = ' fecha >= "'.$anho.'-'.$mes.'-01"
										and fecha <= "'.$anho.'-'.$mes.'-31"
										and confirmada = "no"';
				$SQL2 = 'and ped.fecha_reale != "0000-00-00"';
			}
			else
			{
				$SQL = 'confirmada = "Pe"';
				$SQL2 = 'and ped.fecha_reale != "0000-00-00"';
			}
			
			$id_cliente = $Datos['id_cliente'];
			$SQL3 = '';
			/*if(115 == $this->session->userdata('id_usuario'))
			{
				$SQL3 = 'and venta > 0';
			}*/
			//if(94 != $id_cliente)
			//{//, actualizar
				$Pedido_sap = 'select venta, fecha, sap,id_pedido, id_pedido_sap
											from pedido_sap
											where id_cliente = "'.$id_cliente.'"
											and '.$SQL.$SQL3.' and sap != ""
											';
			/*}
			else
			{//, actualizar
				$Pedido_sap = 'select venta, fecha, sap,id_pedido, id_pedido_sap
											from pedido_sap
											where id_cliente = "'.$id_cliente.'"
											and '.$SQL.$SQL3.' and (fecha >= "2013-03-01" or sap != "")
											';
			}*/
			
			//echo $Pedido_sap.'<br>';
			$Resultado_sap = $this->db->query($Pedido_sap);
			$num = $Resultado_sap->num_rows();
			if($num > 0)
			{
				$Info_sap = $Resultado_sap->result_array();
				foreach($Info_sap as $Datos_sap)
				{
					//Establecemos la consulta para extraer los pedidos que no tienen sap.
					$Consulta2 = 'select cli.id_cliente, proc.proceso, proc.nombre,
												ped.id_pedido, ped.fecha_reale, cli.codigo_cliente,
												cli.nombre as nombre_cliente
												from procesos proc, pedido ped, cliente cli
												where proc.id_proceso = ped.id_proceso
												and proc.id_cliente = cli.id_cliente
												'.$SQL2.'
												and id_pedido = "'.$Datos_sap["id_pedido"].'"
												';
					;
					//echo $Consulta2.'<br><br>	';
					$Resultado2 = $this->db->query($Consulta2);
					//Asignamos el array a una variable.
					$Info_total = $Resultado2->result_array();
					//Exploramos el array.
					foreach($Info_total as $Datos_total)
					{
						//Asignamos la informacion en un array.
						$proceso_ven[$Datos['id_cliente']]['nombre_cliente'] = $Datos["nombre_cliente"];
						$proceso_ven[$Datos['id_cliente']]['id_pedido'] = $Datos_total["id_pedido"];
						$proceso_ven[$Datos['id_cliente']]['nombre'] = $Datos_total["nombre"];
						$proceso_ven[$Datos['id_cliente']]['id_cliente'] = $Datos_total["id_cliente"];
						$proceso_ven[$Datos['id_cliente']]['procesos'][$Datos_sap['id_pedido']]['id_pedido'] = $Datos_sap["id_pedido"];
						$proceso_ven[$Datos['id_cliente']]['procesos'][$Datos_sap['id_pedido']]['fecha_reale'] = $Datos_total["fecha_reale"];
						$proceso_ven[$Datos['id_cliente']]['procesos'][$Datos_sap['id_pedido']]['id_pedido_sap'] = $Datos_sap["id_pedido_sap"];
						$proceso_ven[$Datos['id_cliente']]['procesos'][$Datos_sap['id_pedido']]['codigo_cliente'] = $Datos_total["codigo_cliente"];
						$proceso_ven[$Datos['id_cliente']]['procesos'][$Datos_sap['id_pedido']]['proceso'] = $Datos_total["proceso"];
						$proceso_ven[$Datos['id_cliente']]['procesos'][$Datos_sap['id_pedido']]['venta'] = $Datos_sap["venta"];
						$proceso_ven[$Datos['id_cliente']]['procesos'][$Datos_sap['id_pedido']]['fecha'] = $Datos_sap["fecha"];
						$proceso_ven[$Datos['id_cliente']]['procesos'][$Datos_sap['id_pedido']]['sap'] = $Datos_sap["sap"];
						//$proceso_ven[$Datos['id_cliente']]['procesos'][$Datos_sap['id_pedido']]['actualizar'] = $Datos_sap["actualizar"];
					}
				}
			}
		}
		return $proceso_ven;
	}
	
	
	public function reportar_venta()
	{
		$mandar = '';
		if('' !=  $this->input->post('hoj_id_cliente'))
		{
			$mandar = $this->input->post('hoj_id_cliente');
		}
		$checkes = $this->input->post("cuantos_checkes");
		
		for($i = 0; $i < $checkes; $i++)
		{
			if($this->input->post("chk$i") == "on")
			{
				$Consulta = 'update pedido_sap set confirmada = "no", fecha = "'.date('Y-m-d').'"
							where id_pedido_sap = '.$this->input->post("iv$i");

				$Resultado2 = $this->db->query($Consulta);				
			}
		}
		return $mandar;
	}
	
}

/* Fin del archivo */