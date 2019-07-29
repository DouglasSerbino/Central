<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente_venta_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
  
	function ventas(
		$ianho,
		$imes,
		$fanho,
		$fmes,
		$filtro_cliente,
		$filtro_venta,
		$filtro_vendedor,
		$proyeccion_global,
		$multiple,
		$vista
	)
	{
		
		$Ventas_Cliente = array(
			'Div_Ancho' => '',
			'Venta' => array(),
			'Proyeccion' => array(),
			'Meses_Rango' => array()
		);
		
		$total_meses = 0;
		$ancho_pagina = '';
		
		
		//Si se desea mostrar mas de un mes en el listado
		if('on' == $multiple)
		{
			//Deseo saber primero si son validas las fechas de inicio y fin
			
			$fecha_inicio = "$ianho-$imes-02 00:00:01";//Fecha de inicio y fin para comparar
			$fecha_fin = "$fanho-$fmes-01 00:00:01";//Les debo agregar hora para que no de error la funcion fecha_mayor
			
			if($this->fechas_m->fecha_mayor($fecha_fin, $fecha_inicio))
			{
				//Si la fecha de fin es mayor a la fecha de inicio podemos hacerlo multiple
				
				//Bandera que confirmara al bucle a que hora debe salir
				$salir = false;
				//El bucle iniciara con el mes elegido como inicio, esta variable
				//cambiara y me evitara modificar la original. Lo necesito como numero
				$mes_a = intval($imes);
				//El bucle iniciara con el anho elegido como inicio, esta variable
				//cambiara y me evitara modificar la original
				$anho_a = intval($ianho);
				
				do
				{
					//Hacer... que bonito bucle
					$mes_rango = $mes_a;
					if(10 > $mes_rango)
					{
						$mes_rango = '0'.$mes_rango;
					}
					
					$Ventas_Cliente['Meses_Rango'][] = $anho_a.'-'.$mes_rango;
					
					//Si ya llegue a la fecha de fin del reporte
					if($mes_a == intval($fmes) && $anho_a == intval($fanho))
					{
						$salir = true;//Cambio la variable salir a verdadero
					}
					
					$mes_a++;//Aumento el mes para el proximo ciclo del bucle
					
					//Si ya me pase de diciembre
					if($mes_a > 12)
					{
						$mes_a = 1;//Lo regreso a enero
						$anho_a++;//Aumento el anho
					}
					
					//Aumento el total de meses conforme de vueltas el buclesirijillo
					$total_meses++;
					
					//Mientras salir sea false, una vez cambie a positivo salimos del bucle	
				}while(!$salir);
				//Y todo lo anterior fue para saber unicamente cuantos meses incluye el reporte
				
				
				//Si hay mas de cuatro meses y se muestra solo ventas o proyecciones
				//se va a desconfigurar la ventana que muestra el reporte.
				if('todo' != $vista && $total_meses > 4)
				{
					//Entonces debo cambiar el tamanho.
					//Ancho de Pagina = (Total de meses x ancho cajita css) + 250ancho cajota css + 15 de regalo
					$ancho_pagina = ($total_meses * 100) + 270;//255
					$ancho_pagina .= 'style="width:'.$ancho_pagina.'px';
				}
				
				
				//Si hay dos o mas meses y se esta viendo ventas y proyecciones
				//se va a desconfigurar la ventana que muestra el reporte.
				if('todo' == $vista && $total_meses > 1)
				{
					//Entonces debo cambiar el tamanho.
					//Ancho de Pagina = ((Total de meses x 3) x ancho cajita css) + 250ancho cajota css + 15 de regalo
					$ancho_pagina = (($total_meses * 3) * 100) + 270;
					$ancho_pagina .= 'style="width:'.$ancho_pagina.'px';
				}
				
				if(0 < $ancho_pagina)
				{
					$Ventas_Cliente['div_ancho'] = 'id="contenedor-pagina2" '.$ancho_pagina;
				}
				
			}
			else
			{
				//Sino, aunque ambos tengan el mismo mes À?
				$multiple = '';//Lo voy a tratar como si fuera un solo mes
			}
			
		}
		
		
		if('' == $multiple)
		{
			$fanho = $ianho;
			$fmes = $imes;
			$Ventas_Cliente['Meses_Rango'][] = $ianho.'-'.$imes;
		}
		
		
		
		$Condicion_Sql = '';
		if('todos' != $filtro_cliente)
		{
			$Condicion_Sql .= ' and id_tipo = "'.$filtro_cliente.'"';
		}
		if('todos' != $filtro_vendedor)
		{
			$Condicion_Sql .= ' and id_usuario = "'.$filtro_vendedor.'"';
		}
		
		
		
		if('todo' == $vista || 'venta' == $vista)
		{
			$Consulta = '
				select sum(venta) as venta, clie.id_cliente, date_format(fecha, "%Y-%m") as fecha
				from cliente clie, procesos proc, pedido ped, pedido_sap sapo
				where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = sapo.id_pedido and venta > 0 and sap != ""
				'.$Condicion_Sql.'
				and fecha >= "'.$ianho.'-'.$imes.'-01" and fecha <= "'.$fanho.'-'.$fmes.'-31"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by clie.id_cliente, date_format(fecha, "%Y-%m")
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				
				foreach($Resultado->result_array() as $Fila)
				{
					
					$Ventas_Cliente['Venta'][$Fila['id_cliente']][$Fila['fecha']] = number_format($Fila['venta'], '2', '.', '');
					
				}
				
			}
		}
		
		
		if('todo' == $vista || 'proyeccion' == $vista)
		{
			$Consulta = '
				select proyeccion, clie.id_cliente, anho, mes
				from cliente clie, venta_proyeccion proy
				where clie.id_cliente = proy.id_cliente and anho >= "'.$ianho.'"
				'.$Condicion_Sql.'
				and mes >= "'.$imes.'" and anho <= "'.$fanho.'" and mes <= "'.$fmes.'"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				
				foreach($Resultado->result_array() as $Fila)
				{
					
					$Ventas_Cliente['Proyeccion'][$Fila['id_cliente']][$Fila['anho'].'-'.$Fila['mes']] = number_format($Fila['proyeccion'], '2', '.', '');
					
				}
				
			}
		}
		
		
		return $Ventas_Cliente;
		
	}
	
	
  
	function ventas_pedido(
		$ianho,
		$imes,
		$fanho,
		$fmes,
		$filtro_cliente,
		$filtro_venta,
		$filtro_vendedor,
		$proyeccion_global,
		$multiple,
		$vista
	)
	{
		
		$Ventas_Cliente = array();
		
		if('' == $multiple)
		{
			$fanho = $ianho;
			$fmes = $imes;
		}
		
		
		
		$Condicion_Sql = '';
		if('todos' != $filtro_cliente)
		{
			$Condicion_Sql .= ' and id_tipo = "'.$filtro_cliente.'"';
		}
		if('todos' != $filtro_vendedor)
		{
			$Condicion_Sql .= ' and id_usuario = "'.$filtro_vendedor.'"';
		}
		
		
		
		$Consulta = '
			select venta, fecha, ped.id_pedido, clie.codigo_cliente, proceso,
			sap as es_factura, proc.nombre
			from cliente clie, procesos proc, pedido ped, pedido_sap sapo
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = sapo.id_pedido and venta > 0
			'.$Condicion_Sql.'
			and fecha >= "'.$ianho.'-'.$imes.'-01" and fecha <= "'.$fanho.'-'.$fmes.'-31"
			and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			//print_r($Resultado->result_array());
			foreach($Resultado->result_array() as $Fila)
			{
				
				$Ventas_Cliente[] = $Fila;
				
			}
			
		}
		
		
		return $Ventas_Cliente;
		
	}
	
}

/* Fin del archivo */