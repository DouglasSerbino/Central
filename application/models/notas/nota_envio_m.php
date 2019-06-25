<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nota_envio_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Funcion que permitira buscar la informacion del pedido si tiene
	 *hoja de especificaciones.
	 *@param $pedidos: Listado de pedidos que ha seleccionado el cliente.
	 **/
	function mostrar_especificacion($pedidos)
	{
		//Declaramos las variables.
		$info = array();
		$id_especificacion_general = '';
		//Exploramos el array para extraer todos los pedidos.
		if(count($pedidos) != 0)
		{
			foreach($pedidos as $Datos_pedidos)
			{
				//Asignamos el id del pedido a un array
				$id_pedido = $Datos_pedidos;
				//Establecemos la consulta para extraer el id de las especificaciones.
				$Consulta = 'select id_especificacion_general
										from especificacion_general
										where id_pedido = "'.$id_pedido.'"';
				//Ejecutamos la consulta.
				$Resultado = $this->db->query($Consulta);
				//Asignamos el array a una consulta.
				$Informacion = $Resultado->result_array();
				$info[$id_pedido]['id_especificacion_general'] = '';
				//Mandamos los pedidos
				$info[$id_pedido]['id_pedido'] = $id_pedido;
				//Exploramos el array
				foreach($Informacion as $Datos_informacion)
				{
					//Asignamos el resultado a un nuevo array.
					$id_especificacion_general = $Datos_informacion['id_especificacion_general'];
					$info[$id_pedido]['id_especificacion_general'] = $Datos_informacion['id_especificacion_general'];
				}
				
				//Buscamos los proceso y el nombre de este.
				//Por cada pedido
				$Consulta = 'select distinct proc.proceso, proc.nombre, cli.codigo_cliente
										from procesos proc, pedido ped, cliente cli
										where proc.id_proceso = ped.id_proceso
										and cli.id_cliente = proc.id_cliente
										and id_pedido = "'.$id_pedido.'"';
							
				//Ejecutamos la consulta
				$Resultado = $this->db->query($Consulta);
				//Asignamos el array a una consulta.
				$Informacion = $Resultado->result_array();
				//Declaramos las variables.
				$info[$id_pedido]['proceso'] = '';
				$info[$id_pedido]['nombre'] = '';
				$info[$id_pedido]['codigo_cliente'] =  '';
				//Exploramos el array.
				foreach($Informacion as $Datos_informacion)
				{
					//Asignamos el resultado a un nuevo array.
					$info[$id_pedido]['proceso'] = $Datos_informacion['proceso'];
					$info[$id_pedido]['nombre'] = $Datos_informacion['nombre'];
					$info[$id_pedido]['codigo_cliente'] = $Datos_informacion['codigo_cliente'];
				}
				
				$info[$id_pedido]['material_recibido'] = array();
				//Verificamos si hay una hoja de especificaciones.
				if($id_especificacion_general != "")
				{
					//Buscamos los materiales recibidos.
					$Consulta = 'select material_recibido, materec.id_material_recibido
											from pedido ped,
													 material_recibido materec,
													 material_recibido_grupo matregru,
													 especificacion_matrecgru espmatrecgru,
													 especificacion_general espgen
											where ped.id_pedido = espmatrecgru.id_pedido
											and espmatrecgru.id_material_recibido_grupo = matregru.id_material_recibido_grupo
											and materec.id_material_recibido = matregru.id_material_recibido
											and espgen.id_especificacion_general = "'.$id_especificacion_general.'"
											and espgen.id_pedido = ped.id_pedido
											';
					//echo $Consulta.'<br>';
					//Ejecutamos la consulta.
					$Resultado_material = $this->db->query($Consulta);
					//Asignamos el array a una consulta.
					$Informacion_mate = $Resultado_material->result_array();
					
					//Exploramos el array
					foreach($Informacion_mate as $Datos_informacion_mate)
					{
						//Asignamos el resultado a una variable.
						$info[$id_pedido]['material_recibido'][$Datos_informacion_mate['id_material_recibido']] = $Datos_informacion_mate['material_recibido'];
					}
				}
				
				//Buscamos otros materiales recibidos
				$Consulta_otros = 'select material_rec_otro, material_sol_otro from especificacion_general espgen where espgen.id_pedido = "'.$id_pedido.'"';
				//Ejecutamos la consulta.
				$Resultado_otros = $this->db->query($Consulta_otros);
				//Establecemos las variables.
					$info[$id_pedido]['material_rec_otro']['material_rec_otro'] = '';
					$info[$id_pedido]['material_sol_otro']['material_sol_otro'] = '';
					//Asignamos el resultado en un arrau
					$Informacion_otros = $Resultado_otros->result_array();
					//Exploramos el array
					foreach($Informacion_otros as $Datos_informacion_otros)
					{
						//Verificamos si hay materiales recibidos para mostrar.
						if($Datos_informacion_otros["material_rec_otro"] != "")
						{
							//Asignamos el resultado a una variable.
							$info[$id_pedido]['material_rec_otro']['material_rec_otro'] = $Datos_informacion_otros['material_rec_otro'];
						}
						//Verificamos si hay materiales solicitados para mostrar.
						if($Datos_informacion_otros["material_sol_otro"] != "")
						{
							//Asignamos el resultado a una variable.
							$info[$id_pedido]['material_sol_otro']['material_sol_otro'] = $Datos_informacion_otros['material_sol_otro'];
						}
					}
					$info[$id_pedido]['material_solicitado'] = array();
				//Verificamos si hay una hoja de especificaciones.
				if($id_especificacion_general != "")
				{
					//Establecemos la variable.
					//Establecemos la consulta para extraer los materiales solicitados.
					$Consulta = 'select matesol.material_solicitado,
												matesol.id_material_solicitado
											from pedido ped,
												material_solicitado matesol,
												material_solicitado_grupo matsolgru,
												especificacion_matsolgru espmatsolgru,
												especificacion_general espgen
											where ped.id_pedido = espmatsolgru.id_pedido
												and espmatsolgru.id_material_solicitado_grupo = matsolgru.id_material_solicitado_grupo
												and matesol.id_material_solicitado = matsolgru.id_material_solicitado
												and espgen.id_especificacion_general = "'.$id_especificacion_general.'"
												and espgen.id_pedido = ped.id_pedido
											';
					
					//Ejecutamos la consulta.
					$Resultado_material = $this->db->query($Consulta);
					//Asignamos el resultado a un array.
					$Informacion_mate = $Resultado_material->result_array();
					//Exploramos el array.
					foreach($Informacion_mate as $Datos_informacion_mate)
					{
						//Asignamos el resultado a una variable.
						$info[$id_pedido]['material_solicitado'][$Datos_informacion_mate['id_material_solicitado']] = $Datos_informacion_mate['material_solicitado'];
					}
				}
			}
		}
		//Regresamos la informacion.
		return $info;
	}
}
/* Fin del archivo */