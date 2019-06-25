<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hojas_revision_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada al cliente y el proceso seleccionado.
	 *@param string $Id_pedido: Id del pedido del que se buscara la informacion.
	 *@return array: Informacion del cliente y del proceso.
	*/
	function cliente_proceso($id_pedido)
	{
		
		//Consultamos la base de datos para obtener la informacion del cliente
		//Y del proceso seleccionado.
		$Consulta = 'SELECT cli.nombre as cliente, cli.codigo_cliente,
									proc.proceso, proc.nombre
									FROM cliente cli, procesos proc, pedido ped
									WHERE cli.id_cliente = proc.id_cliente
									AND proc.id_proceso = ped.id_proceso
									AND ped.id_pedido = "'.$id_pedido.'"';
		
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Si la consulta obtuvo resultados
		if(0 < $Resultado->num_rows())
		{
			//Regreso el array con los grupos encontrados
			return $Resultado->result_array();
		}
		else
		{//Si no hay resultados
			return array();
		}
	}
	
	/**
	 *Funcion que nos permitira almacenar la informacion.
	 *@param string $Campos: campos que almacenaremos
	 *@return array: '': si se realizo correctamente
	 *@return array: $con_rechazo.'-'.$hoja_tipo: si las hojas
	 *de revision son diferentes.
	*/
	function guardar_datos($Campos, $tipo)
	{
		//print_r($Campos);
		//Declaracion de variables
		$fecha = date("Y-m-d");
		$id_usuario = $this->session->userdata('id_usuario');
		$id_dpto = $this->session->userdata('id_dpto');
		$id_hoja = '';
		$hoja_tipo = '';
		$con_rechazo = '';
		$comparar = false;
		$SQL = '';
		if($id_dpto == 3)
		{
			$comparar = true;
		}
		$compare_v = array();
		$prod_cali = 0;
		
		//Exploramos la variable $Campos para obtener la informacion.
		foreach($Campos as $Datos)
		{
			$Id_pedido = $Datos['id_pedido'];
			/*
			Orden de los campos que apareceran en las consultas.
			id_hoja, id_pedido, id_usuario, fecha, diseno, fotocelda, cod_barra
			color, observacion, tipo_impresion, texto_diseno, aprobado, medida
			arte_separado, montaje_final, negativo, dimension
			*/
			//Verificaremos que tipo de hoja es la que guardaremos.
			//Y asignaremos parte de la consulta que le corresponde.
			if($tipo == 'arte')
			{
				$hoja_tipo = '1';
				$SQL = '
							"",
							"'.$Datos['fotocelda'].'",
							"'.$Datos['codigo_barra'].'",
							"'.$Datos['color'].'",
							"", "",
							"'.$Datos['textos'].'",
							"", "", "", "",
							"'.$Datos['dimensiones'].'",
							"1"
							)';
			}
			elseif($tipo == 'preprensa')
			{
				$hoja_tipo = '2';
				$SQL = '
							"'.$Datos['diseno'].'",
							"'.$Datos['fotocelda'].'",
							"'.$Datos['codigo_barra'].'",
							"", "", "", "", "",
							"'.$Datos['separado'].'",
							"'.$Datos['montaje'].'",
							"'.$Datos['negativo'].'",
							"",
							"2"
							)';
			}
			elseif($tipo == 'offset')
			{
				$hoja_tipo = '3';
				//Creamos la consulta para almacenar la informacion.
				$Consulta = 'insert into hojas_revision values(null,
																										"'.$Id_pedido.'",
																										"'.$id_usuario.'",
																										"'.$fecha.'",
																										"'.$Datos['diseno'].'",
																										"",
																										"'.(isset($Datos['cod_barra'])).'",
																										"'.$Datos['color'].'",
																										"'.$Datos['observaciones'].'",
																										"'.(isset($Datos['gto'])).'-'.(isset($Datos['m74'])).'-'.(isset($Datos['sor_z'])).'-'.(isset($Datos['speed_master'])).'",
																										"",
																										"'.(isset($Datos['medida'])).'",
																										"", "", "", "",
																										"3"
																										)';
				//Ejecutamos la consulta.
				$Resultado = $this->db->query($Consulta);
				
				//Consulta para seleccionar el id de la hoja que almacenamos.
				$Consulta_id_hoja = 'select id_hoja
														from hojas_revision
														where id_pedido = "'.$Id_pedido.'"';
				//Se ejecuta la consulta.
				$Resultado = $this->db->query($Consulta_id_hoja);
				$Datos_hoja = $Resultado->row_array();
				//Asignamos el resultado a una variable.
				$id_hoja = $Datos_hoja['id_hoja'];
				
				//Consultamos la base de datos para obtener la informacion
				//de los colores que pertenecen al trabajo seleccionado.
				$Consulta_color = 'select color
										from especificacion_colores color,
										especificacion_general gen, pedido ped
										where ped.id_pedido = "'.$Id_pedido.'"
										and ped.id_pedido = gen.id_pedido
										and gen.id_especificacion_general = color.id_especificacion_general
										order by color.id_especificacion_colores asc';
				
				//echo $Consulta;
				//Ejecuto la consulta
				$Resultado = $this->db->query($Consulta_color);
				$i = 1;
				//Exploro el resultado.
				foreach($Resultado->result_array() as $Datos)
				{
					$color = 'N/A';
					//Limpiamos las variables.
					if($this->seguridad_m->mysql_seguro($this->input->post("color_$i")) == 'on')
					{
						$color = 'OK';
					}
					//Creamos la consulta para ingresar los colores.
					$Consulta_hoja_color = 'insert into hoja_offset_color values(NULL,
																																	"'.$id_hoja.'",
																																	"'.$Datos['color'].'",
																																	"'.$color.'")';
					//Ejecutamos la consulta.
					$Resultado = $this->db->query($Consulta_hoja_color);
					$i++;
				}
			}
			elseif($tipo == "tiff")
			{
				$hoja_tipo = '4';
				$SQL = '
							"'.$Datos['diseno'].'",
							"'.$Datos['fotocelda'].'",
							"'.$Datos['codigo_barra'].'",
							"'.$Datos['color'].'",
							"", "",
							"'.$Datos['textos'].'",
							"", "",
							"", "", "",
							"4"
							)';
			}
		}
		
		$Consulta = 'insert into hojas_revision values(null,
																										"'.$Id_pedido.'",
																										"'.$id_usuario.'",
																										"'.$fecha.'", ';
		$Consulta2 = $Consulta.$SQL;
		//echo $Consulta2;
		if($tipo != 'offset')
		{
			//Se ejecuta la consulta.
			$Resultado = $this->db->query($Consulta2);
		}
		
		if($tipo == 'preprensa')
		{
			$tipo = 'negativo';
		}
		
		//Si comparara el igual a TRUE.
		if($comparar)
		{
			//Creamos la consulta para describir la tabla hojas_revision.
			$Consulta = "describe hojas_revision";
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			$campos_v = array();
			$cont = 1;
			//exploramos el array
			foreach($Resultado->result_array() as $Datos)
			{
				//Y asignamos el valor a una variable.
				if($cont > 4)
					$campos_v[] = $Datos["Field"];
				$cont++;
			}
			//Establecemos la consulta para 	extraer la informacion de las hojas de revision.
			$Consulta = 'select * from hojas_revision
									where id_pedido = "'.$Id_pedido.'"
									and tipo_hoja = "'.$hoja_tipo.'"';
			
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			if($Resultado->num_rows() == 2)
			{
				$prod_cali = 0;
				foreach($Resultado->result_array() as $Datos)
				{
					$compare_v[$prod_cali] = array();
					foreach($campos_v as $index => $campo)
					{
						$compare_v[$prod_cali][$campo] = $Datos[$campo];
					}
					$prod_cali++;
				}
				$bueno = true;
				foreach($campos_v as $index => $campo)
				{
					if($compare_v[0][$campo] != $compare_v[1][$campo])
					{
						$bueno = false;
					}
				}
				if(!$bueno)
				{
					$con_rechazo = $tipo;
				}
			}
		}
		
		if($con_rechazo != '')
		{
			return $con_rechazo.'-'.$hoja_tipo;
		}
		else
		{
			return '';
		}
	}
	
	
	/**
	 *Busca en la base de datos la informacion relacionada a los colores del pedido.
	 *@param string $Id_pedido: Id del pedido del que se buscara la informacion.
	 *@return array: Informacion del cliente y del proceso.
	*/
	function comparar_hojas($Id_pedido, $hoja_tipo)
	{
		$Info = array();
		$Consulta = "describe hojas_revision";
		$Resultado = $this->db->query($Consulta);
		$campos_v = array();
		$cont = 1;
		foreach($Resultado->result_array() as $Datos)
		{
			if($cont > 4)
			{
				$campos_v[] = $Datos["Field"];
			}
			$cont++;
		}
		
		$Consulta = 'select * from hojas_revision
								where id_pedido = "'.$Id_pedido.'"
								and tipo_hoja = "'.$hoja_tipo.'"';
		$Resultado = $this->db->query($Consulta);
		if($Resultado->num_rows() == 2)
		{
			$prod_cali = 0;
			$a = 0;
			foreach($Resultado->result_array() as $Datos)
			{
				$compare_v[$prod_cali] = array();
				foreach($campos_v as $index => $campo)
				{
					$compare_v[$prod_cali][$index] = $Datos[$campo];
					if($campo == 'tipo_hoja')
					{				
						$compare_v[$prod_cali][$index] = '';
					}
				}
				$prod_cali++;
			}
		}
		//print_r($campos_va);
		$Info['campos_v'] = $campos_v;
		$Info['compare_v'] = $compare_v;
		return $Info;
	}
	
	
	/**
	 *Busca en la base de datos la informacion relacionada a la hoja de revision.
	 *si las hojas no son iguales.
	 *@param string $Id_pedido: Id del pedido del que se buscara la informacion.
	 *@return array: Informacion del cliente y del proceso.
	*/
	function buscar_colores($Id_pedido)
	{
		
		//Consultamos la base de datos para obtener la informacion del grupo.
		$Consulta = 'select color
								from especificacion_colores color,
								especificacion_general gen, pedido ped
								where ped.id_pedido = "'.$Id_pedido.'"
								and ped.id_pedido = gen.id_pedido
								and gen.id_especificacion_general = color.id_especificacion_general
								order by color.id_especificacion_colores asc';
		
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Si la consulta obtuvo resultados
		if(0 < $Resultado->num_rows())
		{
			//Regreso el array con los grupos encontrados
			return $Resultado->result_array();
		}
		else
		{//Si no hay resultados
			return array();
		}
	}
	
	
	/**
	 *Funcion que permitira comprobar cual de las hojas de revision es la correcta.
	*/
	function verificar_hojas_revision($Campos)
	{
		//print_r($Campos);
		foreach($Campos as $Datos)
		{
			$Id_pedido = $Datos["id_pedido"];
			$hoja_correcta = $Datos["hoja_correcta"];
			$hoja = $Datos["hoja"];
			$hoja_tipo = $Datos['hoja_tipo'];
			$rechazo_hoja = $Datos["rechazo_hoja"];
		}

		$campos_v = array();
		$Consulta = "describe hojas_revision";
		$Resultado = $this->db->query($Consulta);
		$cont = 1;
		foreach($Resultado->result_array() as $Datos)
		{
			if($cont > 4)
			{
				$campos_v[] = $Datos["Field"];
			}
			$cont++;
		}
		
		$i = 0;
		$id_usu_v = array();
		$correcto_v = array();
		$Consulta = 'select * from hojas_revision
								where id_pedido = "'.$Id_pedido.'"
								and tipo_hoja = "'.$hoja_tipo.'"';
		$Resultado = $this->db->query($Consulta);
		foreach($Resultado->result_array() as $Datos)
		{
			$id_usu_v[$i] = $Datos['id_usuario'];
			if($i == $hoja_correcta)
			{
				foreach($campos_v as $index => $campo)
				{
					$correcto_v[$campo] = $Datos[$campo];
				}
			}
			$i++;
		}
		
		$reemplazar = '';
		foreach($correcto_v as $campo => $estado){
			if($reemplazar != '')
			{
				$reemplazar .= ', ';
			}
			$reemplazar .= "$campo = '$estado'";
		}
		$hoja_mala = $hoja_correcta - 1;
		if($hoja_mala < 0)
		{
			$hoja_mala = 1;
		}
		
		$Consulta = "update hojas_revision set $reemplazar
																			where id_pedido = $Id_pedido
																			and id_usuario = ".$id_usu_v[$hoja_mala]."
																			and tipo_hoja = ".$hoja_tipo;
		
		$Resultado = $this->db->query($Consulta);
		
		if($rechazo_hoja != '')
		{
			$Consulta = "insert into pedido_rechazo values(NULL, $Id_pedido, ".$id_usu_v[0].", 0, '".date("Y-m-d H:i:s")."', 'Diferencias entre Hojas de revisi&oacute;n', 'no')";
			$Resultado = $this->db->query($Consulta);
			$Consulta = "insert into observacion values(NULL, $Id_pedido, ".$id_usu_v[1].", '".date("Y-m-d H:i:s")."', 'Se rechaza trabajo por: Diferencias entre Hojas de revisi&oacute;n', 'n')";
			$Resultado = $this->db->query($Consulta);
		}
		return 'ok';
	}
	
	
	/**
	 *Busca en la base de datos la informacion relacionada a la hoja de revision correspondiente al dpto.
	 *@param string $Id_pedido: Id del pedido del que se buscara la informacion.
	 *@return array: Informacion del cliente y del proceso.
	 *Aca se mostrara la hoja para ARTE y para Planchas OFFSET
	*/
	function buscar_hoja_revision($id_pedido)
	{
		$id_dpto = $this->session->userdata('id_dpto');
		$id_usuario = $this->session->userdata('id_usuario');

		$Consulta = 'select id_hoja from hojas_revision
										where id_pedido = "'.$id_pedido.'"
										and id_usuario = "'.$id_usuario.'"
										';
		
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);

		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Datos)
			{
				$id_hoja = $Datos['id_hoja'];
			}
			return $id_hoja;
		}
		else
		{//Si no hay resultados
			return '';
		}
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada
	 *a la hoja de revision correspondiente al dpto.
	 *NEGATIVOS
	 *@param string $Id_pedido: Id del pedido del que se buscara la informacion.
	 *@return array: Informacion del cliente y del proceso.
	*/
	function buscar_hoja_revision_negativo($id_pedido)
	{
		$id_dpto = $this->session->userdata('id_dpto');
		$id_usuario = $this->session->userdata('id_usuario');
		$id_especificacion = '';
		$id_hoja = '';
		if($id_dpto == "5")
		{
			//Consulta para extraer los Negativos
			$Consulta = 'select espegen.id_especificacion_general
								from cliente clie, procesos proc, pedido ped,
								especificacion_general espegen,
								especificacion_matsolgru matsolgru,
								material_solicitado_grupo mat_sol,
								material_solicitado matsol
								where clie.id_cliente = proc.id_cliente
									and proc.id_proceso = ped.id_proceso
									and ped.id_pedido = "'.$id_pedido.'"
									and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
									and ped.id_pedido = espegen.id_pedido
									and matsol.id_material_solicitado = mat_sol.id_material_solicitado
									and mat_sol.id_material_solicitado_grupo = matsolgru.id_material_solicitado_grupo
									and (matsol.id_material_solicitado = "9"
									or matsol.id_material_solicitado = "12")
									and espegen.id_pedido = matsolgru.id_pedido
									group by ped.id_pedido
									order by ped.id_pedido asc
									';
			
			$Resultado = $this->db->query($Consulta);
			//print_r($Resultado->result_array());
			
			foreach($Resultado->result_array() as $Datos)
			{
				$id_especificacion = $Datos['id_especificacion_general'];
			}
			
			if($id_especificacion != '')
			{
				$Consulta = 'select id_hoja from hojas_revision
											where id_pedido = "'.$id_pedido.'"
											and id_usuario = "'.$id_usuario.'"
											';
			
				//echo $Consulta;
				//Ejecuto la consulta
				$Resultado = $this->db->query($Consulta);
				foreach($Resultado->result_array() as $Datos)
				{
					$id_hoja = $Datos['id_hoja'];
				}

				if($id_hoja != '')
				{
					return 'no_hoja';
				}
				else
				{
					return 'si_hoja';
				}
			}
			return 'no_hoja';
		}
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada
	 *a la hoja de revision correspondiente al dpto.
	 *TIFF
	 *@param string $Id_pedido: Id del pedido del que se buscara la informacion.
	 *@return array: Informacion del cliente y del proceso.
	*/
	function buscar_hoja_revision_tiff($id_pedido)
	{
		$id_dpto = $this->session->userdata('id_dpto');
		$id_usuario = $this->session->userdata('id_usuario');
		$id_especificacion = '';
		$id_hoja = '';
		if($id_dpto == "5")
		{
			//Consulta para extraer los Tiff
			$Consulta = 'select espegen.id_especificacion_general
								from cliente clie, procesos proc, pedido ped,
								especificacion_general espegen,
								especificacion_matsolgru matsolgru,
								material_solicitado_grupo mat_sol,
								material_solicitado matsol
								where clie.id_cliente = proc.id_cliente
									and proc.id_proceso = ped.id_proceso
									and ped.id_pedido = "'.$id_pedido.'"
									and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
									and ped.id_pedido = espegen.id_pedido
									and matsol.id_material_solicitado = mat_sol.id_material_solicitado
									and mat_sol.id_material_solicitado_grupo = matsolgru.id_material_solicitado_grupo
									and matsol.id_material_solicitado = "11"
									and espegen.id_pedido = matsolgru.id_pedido
									group by ped.id_pedido
									order by ped.id_pedido asc
									';
			
			$Resultado = $this->db->query($Consulta);
			foreach($Resultado->result_array() as $Datos)
			{
				$id_especificacion = $Datos['id_especificacion_general'];
			}
		
			if($id_especificacion != '')
			{
				$Consulta = 'select id_hoja from hojas_revision
											where id_pedido = "'.$id_pedido.'"
											and id_usuario = "'.$id_usuario.'"
											';
			
				//echo $Consulta;
				//Ejecuto la consulta
				$Resultado = $this->db->query($Consulta);
				foreach($Resultado->result_array() as $Datos)
				{
					$id_hoja = $Datos['id_hoja'];
				}
				
				if($id_hoja !='')
				{
					return 'no_hoja';
				}
				else
				{
					return 'si_hoja';
				}
			}
			return 'no_hoja';
		}
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada a la hoja de revision correspondiente al dpto.
	 *@param string $Id_pedido: Id del pedido del que se buscara la informacion.
	 *@return array: Informacion del cliente y del proceso.
	*/
	function buscar_hoja_revision_calidad_arte($id_pedido)
	{
		$id_usuario = $this->session->userdata('id_usuario');
		$id_hoja = '';
		$nombre_hoja = '';
		$Consulta = 'select tipo_hoja, id_hoja from hojas_revision
										where id_pedido = "'.$id_pedido.'"
										order by id_hoja desc';
		
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			$tipo_hoja = $Resultado->result_array();
			
			$Consulta = 'select nombre_hoja
									from hoja_revision_tipo
									where tipo_hoja = "'.$tipo_hoja[0]['tipo_hoja'].'"';
			$Resultado = $this->db->query($Consulta);
			$nombre_hoja = $Resultado->result_array();
			
			$Consulta = 'select id_hoja
									from hojas_revision rev, hoja_revision_tipo tipo
									where id_pedido = "'.$id_pedido.'"
									and id_usuario = "'.$id_usuario.'"
									and rev.tipo_hoja= tipo.tipo_hoja
									and rev.tipo_hoja = "'.$tipo_hoja[0]['tipo_hoja'].'"
									';
				
			//Ejecuto la consulta
			$Resultado = $this->db->query($Consulta);
			foreach($Resultado->result_array() as $Datos)
			{
				$id_hoja = $Datos['id_hoja'];
			}
			
			if($id_hoja !='')
			{
				return 'no_hoja-'.strtolower($nombre_hoja[0]['nombre_hoja']);
			}
			else
			{
				return 'si_hoja-'.strtolower($nombre_hoja[0]['nombre_hoja']);
			}
		}
		else
		{//Si no hay resultados
			return '';
		}
	}
}

/* Fin del archivo */