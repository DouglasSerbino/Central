<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventario_req_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos los materiales.
	 *@return array.
	*/
	function buscar_material($Valor)
	{
			$Consulta = '
								select * from inventario_material
								where nombre_material like "%'.$Valor.'%"
								and id_grupo = "'.$this->session->userdata["id_grupo"].'"
								order by codigo_sap asc';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			$Datos = $Resultado->result_array();
			header('Content-Type: text/xml');
			echo  '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
				echo  '<datos>';
				foreach($Datos as $Material)
				{		
					echo "<id>".$Material["id_inventario_material"]."</id>";
					echo "<codigo>".$Material["codigo_sap"]." -- ".$Material["nombre_material"]."</codigo>";
				}
				echo '</datos>';
		}
		else
		{
			header('Content-Type: text/xml');
			echo  '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
				echo  '<datos>';
				echo '</datos>';
		}
	}
	
		/**
	 *Busca en la base de datos las existencias.
	 *@return array.
	*/
	function buscar_existencia($Valor)
	{
			$Consulta = '
								select * from inventario_material
								where id_inventario_material = "'.$Valor.'"
								and id_grupo = "'.$this->session->userdata["id_grupo"].'"
								';
		
		$Resultado = $this->db->query($Consulta);
		if($Resultado->num_rows() > 0)
		{
			$Datos = $Resultado->result_array();
				foreach($Datos as $Existencias)
				{		
					$cantidad = $Existencias["existencias"];
					$unidad = $Existencias["cantidad_unidad"];
					$tipo = $Existencias["tipo"];
					
					$total = number_format($unidad * $cantidad, 2);
				}
				
				echo "   $cantidad   ( $total $tipo )";
		}
	}
	
	//Funcion que sirve para mostrar los materiales.
	function mostrar_materiales($Valor)
	{
		
			$Consulta = '
				select id_inventario_material, nombre_material
				from inventario_material
				where codigo_sap = "'.$Valor.'"
				and mat_pais = "'.$this->session->userdata('pais').'"
				and id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
			$nombre_mat = "--";
			$id_material = "--";
			
			$Resultado = $this->db->query($Consulta);
			if(0 < $Resultado->num_rows())
			{
				header("Content-Type: text/xml");
				echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				echo "<datos>";
				$Datos = $Resultado->result_array();
				foreach($Datos as $Datos_material)
				{
					echo "<nombre_material>".$Datos_material["nombre_material"]."</nombre_material>";
					echo "<id_material>".$Datos_material["id_inventario_material"]."</id_material>";
				}
				echo "</datos>";
			}
			else
			{
				header('Content-Type: text/xml');
				echo  '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
					echo  '<datos>';
					echo "<nombre_material>[DESCONOCIDO]</nombre_material>";
					echo "<id_material></id_material>";
					echo '</datos>';
			}
			
	}
	
	/**
	 *Busca en la base de datos los materiales.
	 *@param String $Codigo_material_ codigo del material que se quiere requisar.
	 *@param String $Nombre_material_ nombre del material que se quiere requisar.
	 *@param INT $Id_material_ id del material que se quiere requisar.
	 *@param $Cantidad_material_ cantidad que se requisara del material.
	 *@return array.
	*/
	function requisar_materiales(
		$Codigo_material_,
		$Nombre_material_,
		$Id_material_,
		$Cantidad_material_
	)
	{
		
		$id_usuario = $this->session->userdata('id_usuario');
		$fecha = date("Y-m-d");
		
		//Crear un numero de requisicion
		$Consulta = '
			select numero_requ
			from inventario_requisicion
			where numero_requ like "CG-%"
			order by numero_requ
			desc limit 0, 1
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Datos = $Resultado->result_array();
		
		$numero = '10001';
		foreach($Datos as $Datos_requisicion)
		{
			$numero_requ = explode('-', $Datos_requisicion['numero_requ']);
			
			$numero = $numero_requ[1] + 0;
			
			switch(strlen((string)$numero))
			{
				case 1:
					$numero = '1000'.$numero;
					break;
				case 2:
					$numero = '100'.$numero;
					break;
				case 3:
					$numero = '10'.$numero;
					break;
				case 4:
					$numero = '1'.$numero;
					break;
			}
			$numero += 1;
		}
		
		$numero = 'CG-'.$numero;
		
		//Debo recorrer material por material para ingresarlos
		for($i_m_t = 0; $i_m_t < 12; $i_m_t++)
		{
			//Si esta casilla de material no esta vacia
			if('' != $Nombre_material_[$i_m_t])
			{
				//Capturo el id del material asignado
				$id_material = $Id_material_[$i_m_t];
				
				$id_material += 0;
				$cantidad = $Cantidad_material_[$i_m_t];
				$cantidad += 0;
				
				if(0 == $id_material || 0 == $cantidad)
				{
					continue;
				}
				
				$cantidad_f = $cantidad;
				
				//Se necesita saber si hay suficiente en bodega el material a requisar
				//Establecemos la consulta para verificar si hay suficiente
				//en bodega para poder realizar la requisicion.
				$Consulta_verificar = 'select id_inventario_lote, unidades
															from inventario_lote
															where id_inventario_material = "'.$id_material.'"
															and estado > 0';
				
				//Ejecutamos la consulta.
				$Resultado_verificacion = $this->db->query($Consulta_verificar);
				//Asignamos el array a una variable.
				$Datos_verificacion = $Resultado_verificacion->result_array();
				$unidades = 0;
				foreach($Datos_verificacion as $Verificacion)
				{
					//Cuantas unidades posee este lote?
					$unidades = $unidades + $Verificacion["unidades"];
					
					//Establecemos la consulta para determinar cuanto se ha requisado del lote.
					$Consulta_unidades = 'select count(id_inventario_lote) as requisados
																from inventario_requisicion
																where id_inventario_lote = "'.$Verificacion["id_inventario_lote"].'"';
					
					//Ejecutamos la consulta.
					$Resultado_unidades = $this->db->query($Consulta_unidades);
					
					//Asignamos el array a una variable
					$Datos_uni = $Resultado_unidades->result_array();
					$num = 0;
					foreach($Datos_uni as $Datos_unidades)
					{
						$num = $Datos_unidades['requisados'];
					}
					
					//Unidades menos requisiciones
					$unidades = $unidades - $num;
				}
				
				
				if($cantidad > $unidades)
				{
					continue;
				}
				else
				{
					//Tiempo para requisar los materiales
					$Consulta_tiempo = 'select id_inventario_lote, unidades from inventario_lote where id_inventario_material = "'.$id_material.'" and estado > 0 order by id_inventario_lote asc';
					//Ejecutamos la consulta.
					$Resultado_tiempo = $this->db->query($Consulta_tiempo);
					
					//Asignamos el array a una variable
					$Datos_tiem = $Resultado_tiempo->result_array();
										
					$unidades = 0;//Unidades que contiene cada lote
					$estado_0 = 0;//Quien es el lote que queda activo
					foreach($Datos_tiem as $Datos_tiempo)
					{
						$unidades = $Datos_tiempo["unidades"];
						$id_lote = $Datos_tiempo["id_inventario_lote"];
						
						//Si este es el lote que debe quedar activo
						if($estado_0 == 1)
						{
							$Consulta_activo= 'update inventario_lote
																set estado = "1"
																where id_inventario_lote = "'.$id_lote.'"';
							$Resultado = $this->db->query($Consulta_activo);
							
							$estado_0 = 0;
						}
						
						//Cuanto se ha requisado de este lote?
						//Consulta para obtener el total requisado de este lote.
						$Consulta_total = 'select sum(cantidad) as requisados
															from inventario_requisicion
															where id_inventario_lote = "'.$id_lote.'"';
															
						//Se ejecuta la consulta.
						$Resultado_total = $this->db->query($Consulta_total);
						$num = 0;
						//Asignamos el array a una variable.
						$Datos_tot = $Resultado_total->result_array();
						
						//Exploramos el array.
						foreach($Datos_tot as $Datos_total)
						{
							$num = $Datos_total['requisados'];
							$num += 0;
						}
						//Unidades menos requisiciones
						$unidades = $unidades - $num;
						
						$i = 0;
						
						if($cantidad > 0)
						{
							if($unidades >= $cantidad)
							{
								$Consulta_insert = 'insert into inventario_requisicion
																		values(NULL,
																		"'.$id_lote.'",
																		"'.$numero.'",
																		"'.$fecha.'",
																		"'.$id_usuario.'",
																		"'.$cantidad.'")';
								$Resultado = $this->db->query($Consulta_insert);
								
								if($unidades == $cantidad)
								{
									$Consulta = 'update inventario_lote set estado = 0, fecha_fin = "'.$fecha.'" where id_inventario_lote = "'.$id_lote.'"';
									$Resultado = $this->db->query($Consulta);
									$estado_0 = 1;
								}
								
								$i = $cantidad;
							}
							elseif($unidades < $cantidad)
							{
								$Consulta_insert = 'insert into inventario_requisicion
																		values(NULL,
																		"'.$id_lote.'",
																		"'.$numero.'",
																		"'.$fecha.'",
																		"'.$id_usuario.'",
																		"'.$unidades.'")';
								$Resultado = $this->db->query($Consulta_insert);
								//Actualizamos la tabla inventario lote para determinar el estado del lote
								$Consulta_mod = 'update inventario_lote set
																estado = 0
																where id_inventario_lote = "'.$id_lote.'"';
								$Resultado = $this->db->query($Consulta_mod);
								
								$i = $unidades;
								$estado_0 = 1;
								
							}
							$cantidad = $cantidad - $i;
						}
					}
				}
				
				//Se modifica el total en la tabla material
				//Establecemos la consulta para obtener la cantidad total que pertenece a este material.
				$Consulta_material = 'select existencias from inventario_material where id_inventario_material = "'.$id_material.'"';
				
				//Ejecutamos la consulta.
				$Resultado = $this->db->query($Consulta_material);
				
				$cant_mat = 0;
				//Asignamos el array a una variable.
				$Datos_selec = $Resultado->result_array();

				//Exploramos el array.
				foreach($Datos_selec as $Datos_seleccion)
				{
					$cant_mat = $Datos_seleccion["existencias"];
				}
				
				$cant_mat = $cant_mat - $cantidad_f;
				//Establecemos la consulta para actualizar la tabla de inventario_materiales.
				$Consulta_actu = 'update inventario_material set
													existencias = "'.$cant_mat.'"
													where id_inventario_material = "'.$id_material.'"';
				$Resultado = $this->db->query($Consulta_actu);
				
				
				
				//---------------------------
				//Agregado 26-06-2016
				//Actualizar total en bodega de piso|uso|9000
				//Existe este material en bodega?
				$Consulta = '
					select id_inventario_material
					from inventario_bodega_9000
					where id_inventario_material = "'.$id_material.'"
				';
				$Resultado = $this->db->query($Consulta);
				
				if(0 == $Resultado->num_rows())
				{
					$Consulta = '
						insert into inventario_bodega_9000 values(
							null,
							"'.$id_material.'",
							0
						)
					';
					$this->db->query($Consulta);
				}
				
				$Consulta = '
					update inventario_bodega_9000
					set cantidad = cantidad + '.$Cantidad_material_[$i_m_t].'
					where id_inventario_material = "'.$id_material.'"
				';
				$this->db->query($Consulta);
				//---------------------------
				
			}
		}
		//Mando la variable para poder imprimir la respectiva pagina.
		return $numero;
	}
	
	/**
	 *Buscamos el numero de requisicion para poder ser impreso.
	 *@param Buscamos el usuario que realizo la requisicion.
	 *@param String $Codigo de requisicion.
	 *@return array.
	*/
		function numero_requisicion($Id_requisicion)
	{
			$Consulta = '
									select departamento, codigo, fecha_salida, numero_requ
									from inventario_requisicion inven_req,
									usuario usu, departamentos dpto
									where inven_req.id_usuario = usu.id_usuario and
									usu.id_dpto = dpto.id_dpto
									and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
									and numero_requ = "'.$Id_requisicion.'" limit 0, 1
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
	 *Buscamos los materiales que se requisaron.
	 *@return array.
	*/
		function materiales_requisados($Id_requisicion)
	{
			$Consulta = '
									select sum(inven_req.cantidad) as cantidad,
									mat.codigo_sap, mat.nombre_material, mat.tipo
									from inventario_material mat, inventario_lote lote,
									inventario_requisicion inven_req
									where mat.id_inventario_material = lote.id_inventario_material
									and lote.id_inventario_lote = inven_req.id_inventario_lote
									and numero_requ = "'.$Id_requisicion.'"
									group by mat.id_inventario_material
									order by cantidad asc
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
}
		/* Fin del archivo */
?>