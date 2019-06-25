<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingreso_reportes_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function guardar_datos($anho, $mes)
	{
		//echo '<strong>'.microtime().'</strong><br>';
		$valor = "";
		$suma_ventas = 0;
		$semanas_v = '';
		$suma = 0;
		$Sema_actual = 2.5;
		$Mayor = 0;
		$Venta = array();
		
		if($mes == 1)
		{
			$mes = 11;
		}
		elseif($mes == 2)
		{
			$mes = 12;
		}
		else
		{
			$mes = $mes - 1;
		}
		
		//echo $mes.'<br><br>';
		for($e = 2012; $e<=$anho;$e++)
		{
			for($a=$mes; $a<=12; $a++)
			{
				$a=$a+0;
				if($a<10)
				{
					$a = '0'.$a;
				}		
				
				//Elimino los registros para ingresar información actualizada.
				$Consulta = 'delete from reporte_anual_ventas
						where anho= "'.$e.'"
						and mes = "'.$a.'"
						and id_grupo = "'.$this->session->userdata('id_grupo').'"';
				$Resultado = $this->db->query($Consulta);
				//echo $Consulta.'<br>';
				
				$dia_inicio = date("w", mktime(0, 0, 0, $a, 1, $anho));
				$dias_mes = date('t', mktime( 0, 0, 0, $a, 1, $anho));
				//echo '***'.$a.'***<br>';
				for($i = 1; $i <= $dias_mes; $i++)
				{
					if($dia_inicio == 0 || $i == 1)
					{
						$inicio_consulta = $i;
					}
					
					$dia_inicio++;
					
					if($dia_inicio == 7 || $i == $dias_mes)
					{
						$dia_inicio = 0;
						$fin_consulta = $i;
									
						//Consultamos la base de datos para  obtener
						//la información de las ventas que se realizaron cada semana
						$Consulta = 'select sum(sapo.venta) as total
								from cliente clie, procesos proc, pedido ped,
								pedido_sap sapo, cliente_tipo tipo
								where clie.id_cliente = proc.id_cliente
								and proc.id_proceso = ped.id_proceso
								and ped.id_pedido = sapo.id_pedido
								and sapo.venta > 0
								and clie.id_tipo = tipo.id_tipo
								and fecha >= "'.$e.'-'.$a.'-'.$inicio_consulta.'"
								and fecha <= "'.$e.'-'.$a.'-'.$fin_consulta.'"
								and confirmada = "si"
								and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
								';
						//echo $Consulta.'-*-*-<br>';
						$Resultado = $this->db->query($Consulta);
						$Info = $Resultado->result_array();
						
						foreach($Info as $Datos)
						{
							$total_semana = $Datos["total"];
							if($total_semana == "")
							{
								$valor = 0;
							}
							else
							{
								$valor = number_format($total_semana, 0, '', '');
							}
							//Ingresamnos las informacion de las ventas semanales.
							$Consulta = 'insert into reporte_anual_ventas values(null,
													"'.$e.'",
													"'.$a.'",
													"'.$valor.'",
													"'.$this->session->userdata('id_grupo').'"
													)';
							//echo $Consulta.'<br>';
							$Resultado = $this->db->query($Consulta);
							
						}
					}
				}
			}
			$mes = 1;
		}
		
		//print_r($Venta);
		//echo $valor.'[-]'.$semanas_v.'[-]'.$suma.'[-]'.$Mayor;
		
		
		
		$anho_ac = date('Y');
		$Total_pedidos = array();
		$Pedidos_tiempo = array();
		$Pedidos_atrasados = array();
		$Reprocesos = array();
		$Extras = array();
		$Venta_datos= array();
		$mes_ac = date('m');
		if($mes_ac == 1)
		{
			$mes_ac = 11;
		}
		elseif($mes_ac==2)
		{
			$mes_ac = 12;
		}
		//echo '<br><br>';
		for($anho=2012; $anho<=$anho_ac; $anho++)
		{
			$Consulta = '
				select count(ped.id_pedido) as tot_pedidos,
				date_format(ped.fecha_entrega, "%m") + 0 as fecha
				from pedido ped, procesos proc, cliente cli
				where proc.id_cliente = cli.id_cliente
				and proc.id_proceso = ped.id_proceso
				and ped.fecha_entrega >= "'.$anho.'-'.$mes_ac.'-01"
				and ped.fecha_entrega <= "'.$anho.'-12-31"
				and ped.fecha_reale != "0000-00-00"
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by date_format(ped.fecha_entrega, "%m")';
								
			$Resultado= $this->db->query($Consulta);
			$Total_pedidos = $Resultado->result_array();
			
			//echo $Consulta.'<br>';
			
			$Consulta = '
				select count(ped.id_pedido) as pedidos_tie,
				date_format(ped.fecha_entrega, "%m") + 0 as fecha
				from pedido ped, procesos proc, cliente cli
				where proc.id_cliente = cli.id_cliente
				and proc.id_proceso = ped.id_proceso
				and ped.fecha_entrega >= "'.$anho.'-'.$mes_ac.'-01"
				and ped.fecha_entrega <= "'.$anho.'-12-31"
				and ped.fecha_reale <= ped.fecha_entrega
				and ped.fecha_reale != "0000-00-00"
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by date_format(ped.fecha_entrega, "%m")';
			
			//echo $Consulta.'<br>';
								
			$Resultado= $this->db->query($Consulta);
			$Pedidos_tiempo = $Resultado->result_array();
			
			
			$Consulta = '
				select count(ped.id_pedido) as pedidos_atra,
				date_format(ped.fecha_entrega, "%m") + 0 as fecha
				from pedido ped, procesos proc, cliente cli
				where proc.id_cliente = cli.id_cliente
				and proc.id_proceso = ped.id_proceso
				and ped.fecha_entrega >= "'.$anho.'-'.$mes_ac.'-01"
				and ped.fecha_entrega <= "'.$anho.'-12-31"
				and ped.fecha_reale > ped.fecha_entrega
				and ped.fecha_reale != "0000-00-00"
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by date_format(ped.fecha_entrega, "%m")';
			
			//echo $Consulta.'<br>';					
			$Resultado= $this->db->query($Consulta);
			$Pedidos_atrasados = $Resultado->result_array();
			
			
			$Consulta = '
				select count(id_pedido) as reprocesos, date_format(ped.fecha_reale, "%m") + 0 as fecha
				from cliente clie, procesos proc, pedido ped
				where clie.id_cliente = proc.id_cliente
				and proc.id_proceso = ped.id_proceso
				and ped.fecha_entrega >= "'.$anho.'-'.$mes_ac.'-01"
				and ped.fecha_entrega <= "'.$anho.'-12-31"
				and id_tipo_trabajo = 4
				and ped.fecha_reale != "0000-00-00"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by date_format(ped.fecha_reale, "%m")
			';
			
			$Resultado = $this->db->query($Consulta);
			$Reprocesos = $Resultado->result_array();
			
			
			
			$Consulta = ' select sum(total_h) as extras,
					date_format(ext.fecha, "%m") + 0 as fecha
					from usuario usu, extra ext, departamentos dpto
					where usu.id_usuario = ext.id_usuario
					and dpto.id_dpto = usu.id_dpto
					and ext.fecha >= "'.$anho.'-'.$mes_ac.'-01"
					and ext.fecha <= "'.$anho.'-12-31"
					and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
					group by date_format(ext.fecha, "%m")
			';
			//echo $Consulta;
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			$Extras = $Resultado->result_array();
			
			
			$Consulta = 'select sum(sapo.venta) as venta, tipo.id_tipo, date_format(fecha, "%m") + 0 as fecha
					from cliente clie, procesos proc, pedido ped,
					pedido_sap sapo, cliente_tipo tipo
					where clie.id_cliente = proc.id_cliente
					and proc.id_proceso = ped.id_proceso
					and ped.id_pedido = sapo.id_pedido
					and sapo.venta > 0
					and clie.id_tipo = tipo.id_tipo
					and sapo.fecha >= "'.$anho.'-'.$mes_ac.'-01"
					and sapo.fecha <= "'.$anho.'-12-31"
					and confirmada = "si"
					and clie.id_grupo = "'.$this->session->userdata('id_grupo').'" 
					group by tipo.id_tipo, date_format(fecha, "%m")
					order by fecha
				';
			//echo $Consulta.'<br />';
			$Resultado = $this->db->query($Consulta);
			$Venta_datos = $Resultado->result_array();
			
			//print_r($Venta_datos);
			if(0 < $Resultado->num_rows())
			{
				foreach($Venta_datos as $Datos)
				{
					if($Datos['venta'] != 0)
					{
						$Ventas[$anho][$Datos['fecha']][$Datos['id_tipo']] = number_format($Datos['venta'], 2, '.', '');
						
					}
					else
					{
						$Ventas[$anho][$Datos['fecha']][$Datos['id_tipo']] = 0;
					}
				}
				ksort($Ventas);
			}
			//echo $anho.'<br>';
			//print_r($Ventas);
			//print_r($Total_pedidos);
			$e = 1;
			$i = 0;
			$cero = 0;
			for($a = $mes_ac; $a <= 12; $a++)
			{
				$Consulta_de = 'delete from reporte_anual where anho = "'.$anho.'" and mes = "'.$mes_ac.'"';
				$Resultado = $this->db->query($Consulta_de);
				if($e < 10)
				{
					$cero = '';
				}
				
				$Tpedidos = (isset($Total_pedidos[$i]))?$Total_pedidos[$i]['tot_pedidos']:0;
				$Ttiempo = (isset($Pedidos_tiempo[$i]))?$Pedidos_tiempo[$i]['pedidos_tie']:0;
				$Tatrasados = (isset($Pedidos_atrasados[$i]))?$Pedidos_atrasados[$i]['pedidos_atra']:0;
				$Trepro = (isset($Reprocesos[$i]))?$Reprocesos[$i]['reprocesos']:0;
				$Textras = (isset($Extras[$i]))?$Extras[$i]['extras']:0;
				$Afi = (isset($Ventas[$anho][$mes_ac]['AFI']))?$Ventas[$anho][$mes_ac]['AFI']:0;
				$Com = (isset($Ventas[$anho][$mes_ac]['COM']))?$Ventas[$anho][$mes_ac]['COM']:0;
				$Div = (isset($Ventas[$anho][$mes_ac]['DIV']))?$Ventas[$anho][$mes_ac]['DIV']:0;
				$Sto = (isset($Ventas[$anho][$mes_ac]['STO']))?$Ventas[$anho][$mes_ac]['STO']:0;
				$Total_venta = $Afi + $Com + $Div + $Sto;

				$Consulta = 'insert into reporte_anual values(null,
										"'.$anho.'",
										"'.$cero.$a.'",
										"'.$Tpedidos.'",
										"'.$Ttiempo.'",
										"'.$Tatrasados.'",
										"'.$Trepro.'",
										"'.number_format($Textras,1,'.','').'",
										"'.$Afi.'",
										"'.$Com.'",
										"'.$Div.'",
										"'.$Sto.'",
										"'.$Total_venta.'",
										"'.$this->session->userdata('id_grupo').'"
										)';
				//echo $Consulta.'<br />';
				$Resultado = $this->db->query($Consulta);
				$e++;
				$i++;
				$mes_ac++;
			}
			$mes_ac = 1;
		}
		
		$Consulta = 'insert into actualizar_reportes values(null, "'.	date('Y-m-d H:i:s:').'", "s")';
		$Resultado = $this->db->query($Consulta);
		//echo '<strong>'.microtime().'</strong><br>';
	}
}


/*
 *
 *BACKUP PARA REALIZAR EL REPORTE DE VARIOS AÑOS
 *
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingreso_reportes_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
/*
	function guardar_datos($anho, $mes)
	{
		$valor = "";
		$suma_ventas = 0;
		$semanas_v = '';
		$suma = 0;
		$Sema_actual = 2.5;
		$Mayor = 0;
		$Venta = array();
		
		if($mes < 10)
		{
			$mes = '0'.$mes;
		}
		
			
		$Consulta = 'delete from reporte_anual_ventas
					where anho =  "'.$anho.'"
					and mes = "'.$mes.'"
					and id_grupo = "'.$this->session->userdata('id_grupo').'"
					';
			//echo $Consulta.'<br>';
		$Resultado = $this->db->query($Consulta);
			
		$dia_inicio = date("w", mktime(0, 0, 0, $mes, 1, $anho));
		$dias_mes = date('t', mktime( 0, 0, 0, $mes, 1, $anho));
		//echo '***'.$a.'***<br>';
		for($i = 1; $i <= $dias_mes; $i++)
		{
			if($dia_inicio == 0 || $i == 1)
			{
				$inicio_consulta = $i;
				if($inicio_consulta < 10)
				{
					$inicio_consulta = '0'.$inicio_consulta;
				}
			}
			
			$dia_inicio++;
			
			
			
			if($dia_inicio == 7 || $i == $dias_mes)
			{
				$dia_inicio = 0;
				$fin_consulta = $i;
				
					$Consulta = 'select sum(sapo.venta) as total
						from cliente clie, procesos proc, pedido ped,
						pedido_sap sapo, cliente_tipo tipo
						where clie.id_cliente = proc.id_cliente
						and proc.id_proceso = ped.id_proceso
						and ped.id_pedido = sapo.id_pedido
						and sapo.venta > 0
						and clie.id_tipo = tipo.id_tipo
						and fecha >= "'.$anho.'-'.$mes.'-'.$inicio_consulta.'"
						and fecha <= "'.$anho.'-'.$mes.'-'.$fin_consulta.'"
						and confirmada = "si"
						and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
						';
				//echo $Consulta.'<br>';
				$Resultado = $this->db->query($Consulta);
				$Info = $Resultado->result_array();
				
				foreach($Info as $Datos)
				{
					$total_semana = $Datos["total"];
					if($total_semana == "")
					{
						$valor = 0;
					}
					else
					{
						$valor = number_format($total_semana, 0, '', '');
					}
					
					$Consulta = 'insert into reporte_anual_ventas values(null,
											"'.$anho.'",
											"'.$mes.'",
											"'.$valor.'",
											"'.$this->session->userdata('id_grupo').'"
											)';
					//echo $Consulta.'<br>';
					$Resultado = $this->db->query($Consulta);
					
				}
			}
		}
		
		//print_r($Venta);
		//echo $valor.'[-]'.$semanas_v.'[-]'.$suma.'[-]'.$Mayor;
		
	
		$Consulta = '
			select count(ped.id_pedido) as tot_pedidos
			from pedido ped, procesos proc, cliente cli
			where proc.id_cliente = cli.id_cliente
			and proc.id_proceso = ped.id_proceso
			and ped.fecha_entrega >= "'.$anho.'-'.$mes.'-01"
			and ped.fecha_entrega <= "'.$anho.'-'.$mes.'-31"
			and ped.fecha_reale != "0000-00-00"
			and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
							
		$Resultado= $this->db->query($Consulta);
		$Total_pedidos = $Resultado->row_array();
		
		//echo $Consulta.'<br>';
		$Consulta = '
			select count(ped.id_pedido) as pedidos_tie
			from pedido ped, procesos proc, cliente cli
			where proc.id_cliente = cli.id_cliente
			and proc.id_proceso = ped.id_proceso
			and ped.fecha_entrega >= "'.$anho.'-'.$mes.'-01"
			and ped.fecha_entrega <= "'.$anho.'-'.$mes.'-31"
			and ped.fecha_reale <= ped.fecha_entrega
			and ped.fecha_reale != "0000-00-00"
			and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
		
		//echo $Consulta.'<br>';
							
		$Resultado= $this->db->query($Consulta);
		$Pedidos_tiempo = $Resultado->row_array();
		
		$Consulta = '
			select count(ped.id_pedido) as pedidos_atra
			from pedido ped, procesos proc, cliente cli
			where proc.id_cliente = cli.id_cliente
			and proc.id_proceso = ped.id_proceso
			and ped.fecha_entrega >= "'.$anho.'-'.$mes.'-01"
			and ped.fecha_entrega <= "'.$anho.'-'.$mes.'-31"
			and ped.fecha_reale > ped.fecha_entrega
			and ped.fecha_reale != "0000-00-00"
			and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
		
		//echo $Consulta.'<br>';					
		$Resultado= $this->db->query($Consulta);
		$Pedidos_atrasados = $Resultado->row_array();
		
		$Consulta = '
			select count(id_pedido) as reprocesos
			from cliente clie, procesos proc, pedido ped
			where clie.id_cliente = proc.id_cliente
			and proc.id_proceso = ped.id_proceso
			and ped.fecha_entrega >= "'.$anho.'-'.$mes.'-01"
			and ped.fecha_entrega <= "'.$anho.'-'.$mes.'-31"
			and id_tipo_trabajo = 4
			and ped.fecha_reale != "0000-00-00"
			and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		$Resultado = $this->db->query($Consulta);
		$Reprocesos = $Resultado->row_array();
		//print_r($Reprocesos);
		
		$Consulta = ' select sum(total_h) as extras
				from usuario usu, extra ext, departamentos dpto
				where usu.id_usuario = ext.id_usuario
				and dpto.id_dpto = usu.id_dpto
				and ext.fecha >= "'.$anho.'-'.$mes.'-01"
				and ext.fecha <= "'.$anho.'-'.$mes.'-31"
				and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
		';
		
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		$Extras = $Resultado->row_array();
		//print_r($Extras);
		
		$Consulta = 'select sum(sapo.venta) as venta, tipo.id_tipo
				from cliente clie, procesos proc, pedido ped,
				pedido_sap sapo, cliente_tipo tipo
				where clie.id_cliente = proc.id_cliente
				and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = sapo.id_pedido
				and sapo.venta > 0
				and clie.id_tipo = tipo.id_tipo
				and sapo.fecha >= "'.$anho.'-'.$mes.'-01"
				and sapo.fecha <= "'.$anho.'-'.$mes.'-31"
				and confirmada  = "si"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by tipo.id_tipo
			';
		//echo $Consulta.'<br>';
		$Resultado = $this->db->query($Consulta);
		$Venta_datos = $Resultado->result_array();
	
		foreach($Venta_datos as $Datos)
		{
			if($Datos['venta'] != 0)
			{
				$Ventas[$Datos['id_tipo']] = number_format($Datos['venta'], 2, '.', '');
			}
			else
			{
				$Ventas[$Datos['id_tipo']] = 0;
			}
		}
		ksort($Ventas);
		//print_r($Ventas);
		//echo $mes_da;
		$Tpedidos = $Total_pedidos['tot_pedidos'];
		$Ttiempo = $Pedidos_tiempo['pedidos_tie'];
		$Tatrasados = $Pedidos_atrasados['pedidos_atra'];
		$Trepro = $Reprocesos['reprocesos'];
		$Textras = $Extras['extras'];
		$Afi = (isset($Ventas['AFI']))?$Ventas['AFI']:0;
		$Com = (isset($Ventas['COM']))?$Ventas['COM']:0;
		$Div = (isset($Ventas['DIV']))?$Ventas['DIV']:0;
		$Sto = (isset($Ventas['STO']))?$Ventas['STO']:0;
		$Total_venta = $Afi + $Com + $Div + $Sto;
		
		
		$Consulta = 'delete from reporte_anual
				where anho = "'.$anho.'"
				and mes = "'.$mes.'"
				and id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
		$Resultado = $this->db->query($Consulta);
		
		
		$Consulta = 'insert into reporte_anual values(null,
								"'.$anho.'",
								"'.$mes.'",
								"'.$Tpedidos.'",
								"'.$Ttiempo.'",
								"'.$Tatrasados.'",
								"'.$Trepro.'",
								"'.number_format($Textras,1,'.','').'",
								"'.$Afi.'",
								"'.$Com.'",
								"'.$Div.'",
								"'.$Sto.'",
								"'.$Total_venta.'",
								"'.$this->session->userdata('id_grupo').'"
								)';
		//echo '<br><br>';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		//header('location: /principal');
	}
}


 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingreso_reportes_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}

	function guardar_datos($anho, $mes)
	{
		$valor = "";
		$suma_ventas = 0;
		$semanas_v = '';
		$suma = 0;
		$Sema_actual = 2.5;
		$Mayor = 0;
		$Venta = array();
		
		for($e = 2009; $e<=$anho;$e++)
		{
			//Elimino los registros para ingresar información actualizada.
			$Consulta = 'delete from reporte_anual_ventas
					where anho= "'.$e.'"
					and id_grupo = "'.$this->session->userdata('id_grupo').'"';
			$Resultado = $this->db->query($Consulta);
			
			
			for($a=1; $a<=12; $a++)
			{
				
				if($a<10)
				{
					$a = '0'.$a;
				}		
				
				$dia_inicio = date("w", mktime(0, 0, 0, $a, 1, $anho));
				$dias_mes = date('t', mktime( 0, 0, 0, $a, 1, $anho));
				//echo '***'.$a.'***<br>';
				for($i = 1; $i <= $dias_mes; $i++)
				{
					if($dia_inicio == 0 || $i == 1)
					{
						$inicio_consulta = $i;
					}
					
					$dia_inicio++;
					
					if($dia_inicio == 7 || $i == $dias_mes)
					{
						$dia_inicio = 0;
						$fin_consulta = $i;
									
						//Consultamos la base de datos para  obtener
						//la información de las ventas que se realizaron cada semana
						$Consulta = 'select sum(sapo.venta) as total
								from cliente clie, procesos proc, pedido ped,
								pedido_sap sapo, cliente_tipo tipo
								where clie.id_cliente = proc.id_cliente
								and proc.id_proceso = ped.id_proceso
								and ped.id_pedido = sapo.id_pedido
								and sapo.venta > 0
								and clie.id_tipo = tipo.id_tipo
								and fecha >= "'.$e.'-'.$a.'-'.$inicio_consulta.'"
								and fecha <= "'.$e.'-'.$a.'-'.$fin_consulta.'"
								and confirmada = "si"
								and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
								';
						//echo $Consulta.'<br>';
						$Resultado = $this->db->query($Consulta);
						$Info = $Resultado->result_array();
						
						foreach($Info as $Datos)
						{
							$total_semana = $Datos["total"];
							if($total_semana == "")
							{
								$valor = 0;
							}
							else
							{
								$valor = number_format($total_semana, 0, '', '');
							}
							//Ingresamnos las informacion de las ventas semanales.
							$Consulta = 'insert into reporte_anual_ventas values(null,
													"'.$e.'",
													"'.$a.'",
													"'.$valor.'",
													"'.$this->session->userdata('id_grupo').'"
													)';
							//echo $Consulta.'<br>';
							$Resultado = $this->db->query($Consulta);
							
						}
					}
				}
			}
		}

		//print_r($Venta);
		//echo $valor.'[-]'.$semanas_v.'[-]'.$suma.'[-]'.$Mayor;
		
			
		$anho_ac = date('Y');
		$Total_pedidos = array();
		$Pedidos_tiempo = array();
		$Pedidos_atrasados = array();
		$Reprocesos = array();
		$Extras = array();
		$Venta_datos= array();
		for($anho=2009; $anho<=$anho_ac; $anho++)
		{
			$Consulta = 'delete from reporte_anual where anho = "'.$anho.'"';
			$Resultado= $this->db->query($Consulta);
			
			
			$Consulta = '
				select count(ped.id_pedido) as tot_pedidos,
				date_format(ped.fecha_entrega, "%m") + 0 as fecha
				from pedido ped, procesos proc, cliente cli
				where proc.id_cliente = cli.id_cliente
				and proc.id_proceso = ped.id_proceso
				and ped.fecha_entrega >= "'.$anho.'-01-01"
				and ped.fecha_entrega <= "'.$anho.'-12-31"
				and ped.fecha_reale != "0000-00-00"
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by date_format(ped.fecha_entrega, "%m")';
								
			$Resultado= $this->db->query($Consulta);
			$Total_pedidos = $Resultado->result_array();
			
			//echo $Consulta.'<br>';
			
			$Consulta = '
				select count(ped.id_pedido) as pedidos_tie,
				date_format(ped.fecha_entrega, "%m") + 0 as fecha
				from pedido ped, procesos proc, cliente cli
				where proc.id_cliente = cli.id_cliente
				and proc.id_proceso = ped.id_proceso
				and ped.fecha_entrega >= "'.$anho.'-01-01"
				and ped.fecha_entrega <= "'.$anho.'-12-31"
				and ped.fecha_reale <= ped.fecha_entrega
				and ped.fecha_reale != "0000-00-00"
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by date_format(ped.fecha_entrega, "%m")';
			
			//echo $Consulta.'<br>';
								
			$Resultado= $this->db->query($Consulta);
			$Pedidos_tiempo = $Resultado->result_array();
			
			
			$Consulta = '
				select count(ped.id_pedido) as pedidos_atra,
				date_format(ped.fecha_entrega, "%m") + 0 as fecha
				from pedido ped, procesos proc, cliente cli
				where proc.id_cliente = cli.id_cliente
				and proc.id_proceso = ped.id_proceso
				and ped.fecha_entrega >= "'.$anho.'-01-01"
				and ped.fecha_entrega <= "'.$anho.'-12-31"
				and ped.fecha_reale > ped.fecha_entrega
				and ped.fecha_reale != "0000-00-00"
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by date_format(ped.fecha_entrega, "%m")';
			
			//echo $Consulta.'<br>';					
			$Resultado= $this->db->query($Consulta);
			$Pedidos_atrasados = $Resultado->result_array();
			
			
			$Consulta = '
				select count(id_pedido) as reprocesos, date_format(ped.fecha_reale, "%m") + 0 as fecha
				from cliente clie, procesos proc, pedido ped
				where clie.id_cliente = proc.id_cliente
				and proc.id_proceso = ped.id_proceso
				and ped.fecha_entrega >= "'.$anho.'-01-01"
				and ped.fecha_entrega <= "'.$anho.'-12-31"
				and id_tipo_trabajo = 4
				and ped.fecha_reale != "0000-00-00"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by date_format(ped.fecha_reale, "%m")
			';
			
			$Resultado = $this->db->query($Consulta);
			$Reprocesos = $Resultado->result_array();
			
			
			
			$Consulta = ' select sum(total_h) as extras,
					date_format(ext.fecha, "%m") + 0 as fecha
					from usuario usu, extra ext, departamentos dpto
					where usu.id_usuario = ext.id_usuario
					and dpto.id_dpto = usu.id_dpto
					and ext.fecha >= "'.$anho.'-01-01"
					and ext.fecha <= "'.$anho.'-12-31"
					and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
					group by date_format(ext.fecha, "%m")
			';
			
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			$Extras = $Resultado->result_array();
			
			
			$Consulta = 'select sum(sapo.venta) as venta, tipo.id_tipo, date_format(fecha, "%m") + 0 as fecha
					from cliente clie, procesos proc, pedido ped,
					pedido_sap sapo, cliente_tipo tipo
					where clie.id_cliente = proc.id_cliente
					and proc.id_proceso = ped.id_proceso
					and ped.id_pedido = sapo.id_pedido
					and sapo.venta > 0
					and clie.id_tipo = tipo.id_tipo
					and sapo.fecha >= "'.$anho.'-01-01"
					and sapo.fecha <= "'.$anho.'-12-31"
					and confirmada = "si"
					and clie.id_grupo = "'.$this->session->userdata('id_grupo').'" 
					group by tipo.id_tipo, date_format(fecha, "%m")
					order by fecha
				';
			
			$Resultado = $this->db->query($Consulta);
			$Venta_datos = $Resultado->result_array();
			
			//print_r($Venta_datos);
			if(0 < $Resultado->num_rows())
			{
				foreach($Venta_datos as $Datos)
				{
					if($Datos['venta'] != 0)
					{
						$Ventas[$anho][$Datos['fecha']][$Datos['id_tipo']] = number_format($Datos['venta'], 2, '.', '');
						
					}
					else
					{
						$Ventas[$anho][$Datos['fecha']][$Datos['id_tipo']] = 0;
					}
				}
				ksort($Ventas);
			}
			//echo $anho.'<br>';
			//print_r($Ventas);
			$e = 1;
			$cero = 0;
			for($a = 0; $a < 12; $a++)
			{
				if($e < 10)
				{
					$cero = '';
				}
				
				$Tpedidos = (isset($Total_pedidos[$a]))?$Total_pedidos[$a]['tot_pedidos']:0;
				$Ttiempo = (isset($Pedidos_tiempo[$a]))?$Pedidos_tiempo[$a]['pedidos_tie']:0;
				$Tatrasados = (isset($Pedidos_atrasados[$a]))?$Pedidos_atrasados[$a]['pedidos_atra']:0;
				$Trepro = (isset($Reprocesos[$a]))?$Reprocesos[$a]['reprocesos']:0;
				$Textras = (isset($Extras[$a]))?$Extras[$a]['extras']:0;
				$Afi = (isset($Ventas[$anho][$e]['AFI']))?$Ventas[$anho][$e]['AFI']:0;
				$Com = (isset($Ventas[$anho][$e]['COM']))?$Ventas[$anho][$e]['COM']:0;
				$Div = (isset($Ventas[$anho][$e]['DIV']))?$Ventas[$anho][$e]['DIV']:0;
				$Sto = (isset($Ventas[$anho][$e]['STO']))?$Ventas[$anho][$e]['STO']:0;
				$Total_venta = $Afi + $Com + $Div + $Sto;

				$Consulta = 'insert into reporte_anual values(null,
										"'.$anho.'",
										"'.$cero.$e.'",
										"'.$Tpedidos.'",
										"'.$Ttiempo.'",
										"'.$Tatrasados.'",
										"'.$Trepro.'",
										"'.number_format($Textras,1,'.','').'",
										"'.$Afi.'",
										"'.$Com.'",
										"'.$Div.'",
										"'.$Sto.'",
										"'.$Total_venta.'",
										"'.$this->session->userdata('id_grupo').'"
										)';
				//echo $Consulta.'<br>';
				$Resultado = $this->db->query($Consulta);
				$e++;
			}
		}
		
		//$Consulta = 'insert into actualizar_reportes values(null, "'.	date('Y-m-d H:i:s:').'", "s")';
		//$Resultado = $this->db->query($Consulta);
	}
}

*/


?>