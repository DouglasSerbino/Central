<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Planchas_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos las planchas que existen.
	*/
	function buscar_planchas($codigo)
	{
		//Si la variable codigo es diferente de 0, entonces
		//Se buscara una plancha en especifico.
		if($codigo != '')
		{
			$SQL = "where cod_plancha = '$codigo'";
		}
		else
		{
			$SQL = 'order by grosor';
		}
		//Consultamos la base de datos para que nos ofresca los menus.
		$Consulta = '
					select * from planchas '.$SQL.'
		';
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
	
	/**
	 *Busca en la base de datos los tipos de planchas que existen.
	*/
	function plancha_tipo()
	{

		//Consultamos la base de datos para que nos muestre los tipos de planchas.
		$Consulta = '
					select * from plancha_tipo order by nombre_tipo
		';
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	

	/**
	 *Buscar todos los retazos de la plancha seleccionada.
	 *Esta funcion se utiliza en consultar_material.
	*/
	function mostrar_cantidades($codigo, $ordenar)
	{
		//$ordenar puede traer los valores:
		//nombre_tipo
		//year, mes, dia.
		$Consulta = 'select ca.codigo, ca.cantidad, ca.ancho, ca. alto, ca.subtotal,
			ca.total, ti.nombre_tipo, ca.dia, ca.mes, ca.year,
			ca.cod_plancha, pl.grosor, pl.tipo, pl.ubicacion
			from plancha_cantidad ca, planchas pl, plancha_tipo ti
			where ca.cod_tipo = ti.cod_tipo
			and ca.cod_plancha = pl.cod_plancha
			and pl.cod_plancha = "'.$codigo.'"
			order by '.$ordenar.'
		';
		
		//echo $Consulta;
		//Se ejecuta la consulta.
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
	/**
	 *Almacenar los nuevos retazos.
	*/
	function guardar_retazos($Cantidad, $Ancho, $Tipo, $Alto, $Codigo)
	{			
			$fecha = date("d/m/Y");//Fecha Actual==
			$dia = date("d");
			$mes = date("m");
			$yeara = date("Y");
			$subtotal = $Alto * $Ancho;
			$total = $subtotal * $Cantidad;
			//Declaramos la consulta para almacenar la informacion.
			$Consulta = 'insert into plancha_cantidad values(null,
				"'.$Tipo.'", "'.$Codigo.'",
				"'.$Cantidad.'", "'.$Ancho.'",
				"'.$Alto.'", "'.$subtotal.'",
				"'.$total.'", "'.$dia.'",
				"'.$mes.'", "'.$yeara.'"
			)';
			$Resultado = $this->db->query($Consulta);
		
		return 'ok';
	}
	
	
	/**
	 *Funcion que nos permitira mostrar la informacion de un retazo especifico.
	*/
	function mostrar_retazos($codigo)
	{
		//Establecemos la consulta para buscar el retazo.
		$Consulta = 'select ca.cantidad, ca.ancho, ca.alto,
			ca.cod_tipo, pl.grosor, pl.ubicacion, pl.cod_plancha, pl.tipo
			from planchas pl, plancha_cantidad ca, plancha_tipo ti
			where pl.cod_plancha = ca.cod_plancha
			and ti.cod_tipo = ca.cod_tipo
			and codigo = "'.$codigo.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
	
	/**
	 *Modificar los retazos.
	*/
	function modificar_retazos($Cantidad, $Ancho, $Tipo, $Alto, $Codigo)
	{
	
		$fecha = date("d/m/Y");//Fecha Actual==
		$dia = date("d");
		$mes = date("m");
		$yeara = date("Y");
		$subtotal = $Alto * $Ancho;
		$total = $subtotal * $Cantidad;
		//Verificamos el valor de la cantidad ingresada.
		//Si es igual a 0 entonces eliminaremos el retazo.
		if($Cantidad == "0")
		{
			$Consulta = 'delete from plancha_cantidad where codigo = "'.$Codigo.'"';
		}
		else
		{
			//Si es mayor a 0 entonces actualizaremos la informacion.
			$Consulta = 'update plancha_cantidad set cod_tipo = "'.$Tipo.'",
																			cantidad = "'.$Cantidad.'",
																			ancho = "'.$Ancho.'",
																			alto = "'.$Alto.'",
																			subtotal = "'.$subtotal.'",
																			total = "'.$total.'",
																			dia = "'.$dia.'",
																			mes = "'.$mes.'",
																			year = "'.$yeara.'"
																			where codigo = "'.$Codigo.'"';
		}
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);

		return 'ok';
	}
	
		/**
	 *Funcion que nos permitira agregar un nuevo proveedor(tipo de plancha).
	*/
	function agregar_proveedor($Nombre)
	{
		//Establecemos la consulta para verificar si la plancha existe.
		$Consulta = 'select * from plancha_tipo where nombre_tipo="'.$Nombre.'"';
		$Resultado = $this->db->query($Consulta);
		//Si existe mandamos un error.
		if($Resultado->num_rows() > 0)
		{
			return 'error';
		}
		else
		{
			//Si no existe guardamos la informacion.
			$Consulta = 'insert into plancha_tipo (cod_tipo, nombre_tipo)
																						values("null","'.$Nombre.'")';
			$Resultado = $this->db->query($Consulta);
			return 'ok';
		}
	}
	
	/**
	 *Funcion que nos permitira mostrar los retazos que existen.
	 *Con las medidas ingresadas.
	*/
	function buscar_retazos($Codigo, $Alto, $Ancho, $Alto2, $Ancho2)
	{
		$Consulta ="select ca.codigo, ca.cantidad, ca.ancho, ca. alto,
						ca.subtotal, ca.total, ti.nombre_tipo,
						ca.dia, ca.mes, ca.year, ca.cod_plancha,
						pl.grosor, pl.tipo, pl.ubicacion
						from plancha_cantidad ca, planchas pl, plancha_tipo ti
						where ca.cod_tipo = ti.cod_tipo
						and ca.cod_plancha = pl.cod_plancha
						and pl.cod_plancha = '$Codigo'
						and (
									(alto >= $Alto and alto < $Alto2
									and ancho >= $Ancho and ancho < $Ancho2)
							or (ancho >= $Alto and ancho < $Alto2
									and alto >= $Ancho and alto < $Ancho2)
								)
						order by ti.nombre_tipo";
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			if(0 < $Resultado->num_rows())
			{
				return $Resultado->result_array();
			}
			else
			{
				return array();
			}
			
	}
		
	/**
	 *Funcion que servira para extraer la informacion y crear el grafico
	*/
	function plancha_grafico($anho, $cod_plancha)
	{
		$valores = '';
		for($i = 1; $i <= 12; $i++)
		{
			$mes = $i;
			if($mes < 10)
			{
				$mes = "0$mes";
			}
			$cantidad = 0;
			
			if($cod_plancha == "todo")
			{
				$Consulta = "
					select sum(cantidad) as cantidad
					from plancha_mensual mens, planchas plan
					where plan.cod_plancha = mens.cod_plancha
					and fecha = '$anho-$mes-01'
				";
			}
			else
			{
				$Consulta = "select cantidad
										from plancha_mensual
										where cod_plancha = '$cod_plancha'
										and fecha = '$anho-$mes-01'";
			}
			
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			//Exploramos el array
			foreach($Resultado->result_array() as $Datos)
			{
				$cantidad = $Datos["cantidad"];
			}
		
			if($mes == date('m') && $anho == date('Y'))
			{//Si estoy en el mes y anho actual
				//Pasa algo importante, los meses anteriores ya estan cerrados,
				//se debe capturar el saldo del mes anterior a los movimientos actuales
				
				if($cod_plancha == "todo")
				{
					$Consulta2 = "
						select sum(placa.total) as cantidad
						from plancha_cantidad placa, planchas, plancha_tipo tipo
						where placa.cod_plancha = planchas.cod_plancha
						and tipo.cod_tipo = placa.cod_tipo
					";
				}
				else
				{
					$Consulta2 = "select sum(total) as cantidad
												from plancha_cantidad
												where cod_plancha = '$cod_plancha'";
				}
				//echo $Consulta2;
				$Resultado2 = $this->db->query($Consulta2);
				foreach($Resultado2->result_array() as $Datos)
				{
					$cantidad = $Datos["cantidad"];
				}
			}
			
			if($i > 1)
			{
				$valores .= ",";
			}
			if($cantidad != 0)
			{
				$valores .= number_format($cantidad, 0, ',', '');
			}
			else
			{
				$valores .= 0;
			}
		}
		return $valores;
	}
	
	/**
	 *Funcion que nos permitira mostrar los pedidos que estan en aprobacion.
	*/
	function pedidos_aprobacion()
	{
		$Consulta = 'select id_pedido from pedido_usuario where estado = "Aprobacion"';
				 
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
	/**
	 *Funcion que nos permitira mostrar las las entregas ordenadas por fecha.
	 *Pertenecientes al departamento de Planchas.
	*/
	function entregas_fecha()
	{
		$Info = array();
		$Consulta = 'select ped.id_pedido, ped.fecha_entrega,
								cli.codigo_cliente, proc.proceso, proc.nombre
								from pedido ped,
										procesos proc,
										material_solicitado matsol,
										cliente cli,
										material_solicitado_grupo matsolgru,
										especificacion_matsolgru espe_matsolgru
								where ped.id_proceso = proc.id_proceso
								and proc.id_cliente = cli.id_cliente
									and espe_matsolgru.	id_material_solicitado_grupo = matsolgru.	id_material_solicitado_grupo
									and espe_matsolgru.id_pedido = ped.id_pedido
									and matsolgru.id_material_solicitado = matsol.id_material_solicitado
									and ped.fecha_reale = "0000-00-00"
									and (matsol.id_material_solicitado = 8
											or matsol.id_material_solicitado = 12)
									and matsolgru.id_grupo = "'.$this->session->userdata('id_grupo').'"
									order by ped.fecha_entrega asc, cli.id_cliente asc';
		
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Datos)
			{
				$Info[$Datos['fecha_entrega']]['fecha_entrega'] = $Datos['fecha_entrega'];
				$Info[$Datos['fecha_entrega']]['datos'][$Datos['id_pedido']]['id_pedido'] = $Datos['id_pedido'];
				$Info[$Datos['fecha_entrega']]['datos'][$Datos['id_pedido']]['codigo_cliente'] = $Datos['codigo_cliente'];
				$Info[$Datos['fecha_entrega']]['datos'][$Datos['id_pedido']]['proceso'] = $Datos['proceso'];
				$Info[$Datos['fecha_entrega']]['datos'][$Datos['id_pedido']]['nombre'] = $Datos['nombre'];
			}
			return $Info;
		}
		else
		{
			return array();
		}
	}
	
	
	/**
	 *Busca en la base de datos las planchas que existen.
	*/
	function guardar_planchas()
	{
		$anho = date('Y');
		$mes = date('m');
		$mes--;
		if($mes < 10)
		{
			$mes = "0$mes";
		}
		
		if($mes < 0)
		{
			$mes = 12;
			$anho--;
		}
		
		$fecha = $anho.'-'.$mes.'-01';
		//Extraemo el codigo de las planchas.
		$Consulta = "select cod_plancha from planchas order by cod_plancha asc";
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);

		//Exploramos el array
		foreach($Resultado->result_array() as $Datos)
		{
			//Establecemos la consulta para extraer el total de las planchas.
			$Consulta_sum = 'select sum(total) as total
												from plancha_cantidad
												where cod_plancha = "'.$Datos["cod_plancha"].'"';
			//Ejecutamos la consulta.
			$Resultado_sum = $this->db->query($Consulta_sum);
			//Exploramos el array.
			$ttFotopolimero = 0;
			foreach($Resultado_sum->result_array() as $Datos_sum)
			{
				if(0 < $Datos_sum["total"])
				{
					$ttFotopolimero = $Datos_sum["total"];
				}
			}
			
			$ConsultaDel = 'delete from plancha_mensual
										where cod_plancha = "'.$Datos["cod_plancha"].'"
										and fecha >= "'.$anho.'-'.$mes.'-01" and fecha <= "'.$anho.'-'.$mes.'-31"';
			
			$ResultadoDel = $this->db->query($ConsultaDel);
			//Ingresamos la informacion.
			$Consulta_men = 'insert into plancha_mensual values (NULL,
																													"'.$Datos["cod_plancha"].'",
																													"'.$ttFotopolimero.'",
																													"'.$fecha.'"	)';
			//echo $Consulta_men.'<br>';
			$Resultado_men = $this->db->query($Consulta_men);
			
		}
		return 'ok';
	}
	
	/**
	 *Funcion que nos permitira agregar o modificar las planchas.
	*/
	function guardar_modificar_planchas($codigo_pla, $codigo, $modi,
																			$altura, $ubicacion, $tipo, $id_plancha)
	{
		//Verificamos si es modificacion o agregar
		if($modi == "no")
		{
			//Establecemos la consulta para buscar si la plancha ya existe.
			$Consulta = 'select * from planchas where cod_plancha = "'.$codigo.'"';
			$Resultado = $this->db->query($Consulta);
			if($Resultado->num_rows() == 0)
			{
				//Si no existe almacenamos la informacion.
				$Consulta = "insert into planchas values(null, '$codigo','$altura', '$ubicacion', '$tipo')";
				$Resultado = $this->db->query($Consulta);
				return 'ok_gua';
			}
			else
			{
				//Si existe, mostraremos un error al usuario.
				return 'existe';
			}
		}
		else
		{
			//Establecemos la consulta para saber si el codigo de la plancha existe.
			$Consulta = 'select * from planchas where cod_plancha = "'.$codigo_pla.'"';
			$Resultado = $this->db->query($Consulta);
			if($Resultado->num_rows() > 0)
			{
				//Exploramos el array.
				foreach($Resultado->result_array() as $Datos)
				{
					//Verificamos si el codigo ingresado existe.
					if($Datos['cod_plancha'] == $codigo_pla and $Datos['id_plancha'] == $id_plancha)
					{
						//Establecemos la consulta para actualizar la informacion.
						$Consulta = 'update planchas set cod_plancha = "'.$codigo.'",
																								grosor = "'.$altura.'",
																								ubicacion = "'.$ubicacion.'",
																								tipo = "'.$tipo.'"
																							where cod_plancha = "'.$codigo_pla.'"';
						//Ejecutamos la consulta.
						$Resultado = $this->db->query($Consulta);
						//Establecemos la consulta para actualizar la informacion de la tabla
						//plancha cantidad.
						$Consulta2 = 'update plancha_cantidad set cod_plancha = "'.$codigo.'"
													where cod_plancha = "'.$codigo_pla.'"';
						//Ejecutamos la consulta.
						$Resultado = $this->db->query($Consulta2);
						//Regresamos ok.
						return 'ok_mod';
					}
					else
					{
						//Si el codigo de la plancha ya existe, mostramos un error.
						return 'existe';
					}
				}
			}
		}
	}
}

/* Fin del archivo */