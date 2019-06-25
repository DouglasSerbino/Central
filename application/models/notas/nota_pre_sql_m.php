<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nota_pre_sql_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Funcion que permitira agregar los materiales utilizados para
	 *formar la nota de envio.
	 *return id de la nota de envio.
	 **/
	function notas_envio_sql()
	{
		//Establecemos la consulta para mostrar el ultimo correlativo.
		$Consulta = 'select correlativo from pedido_nota_envio order by id_nota_env desc limit 0, 1';
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		//Asignamos el resultado a un array.
		$Informacion = $Resultado->result_array();
		$correlativo = '';
		//Verificamos si hay informacion para mostrar.
		if($Resultado->num_rows > 0)
		{
			//Exploramos el array
			foreach($Informacion as $Datos)
			{
				//Asignamos el resultado a una variable.
				$correlativo = $Datos["correlativo"];
			}
		}

		$salir = false;
		do{
			//Aumentamos el correlativo en uno.
			$correlativo++;
			//Verificamos la longitud del correlativo.
			for($i = strlen($correlativo); $i < 6; $i++)
			{
				$correlativo = "0$correlativo";
				//Extraemos el correlativo
				$Consulta = 'select correlativo from pedido_nota_envio where correlativo = "'.$correlativo.'"';
				//Ejecutamos la cosnulta
				$Resultado = $this->db->query($Consulta);
				//Si la consulta nos da false nos salimos del bucle.
				if($Resultado->num_rows == 0)
				{
					$salir = true;
				}
			}
		}while(!$salir);
		
		
			//Obtenemos la fecha y hora de hoy.
			$fecha_hoy = date("Y-m-d H:i:s");
			//Ingresamos el correlativo y la fecha.
			$Consulta = 'insert into pedido_nota_envio values(NULL,
																												"'.$correlativo.'",
																												"'.$fecha_hoy.'",
																												"",
																												"")';
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			//Extraemos el id de la nota que acabamos de ingresar.
			$Consulta_notas = 'select id_nota_env from pedido_nota_envio order by id_nota_env desc limit 0, 1';
			
			//Ejecutamos la consulta.
			$Resultado_notas = $this->db->query($Consulta_notas);
			//Asignamos el resultado a un array.
			$Informacion_notas = $Resultado_notas->result_array();
			//Exploramos el array.
			foreach($Informacion_notas as $Datos_nota)
			{
				//Asignamos el resultado a una variable.
				$id_nota_env = $Datos_nota["id_nota_env"];
			}
			
			
			$id_material = '';
			$id_pedido = '';
			//Exploramos la informacion que estamos recibiendo por POST.
			foreach($_POST as $nombre_campo => $valor)
			{
				//Exploramos el array de los materiales.
				$materiales = explode('_', $nombre_campo);
				$tipo = $materiales[0];
				//Verificamos cual es el tipo que se quiere ingresar.
				if('mr' == $tipo or 'ms' == $tipo or 'ot' == $tipo)
				{
					$id_material = $materiales[1];
					$id_pedido = $materiales[2];
				}
				//Verificamos si la informacion es correcta.
				if($id_pedido == "" || $tipo == "ot"  || $tipo == "de")
				{
					continue;
				}
				//Ingresamos la informacion de los materiales que se utilizaron.
				$Consulta = 'insert into pedido_nota_material values(NULL,
																														"'.$id_nota_env.'",
																														"'.$id_pedido.'",
																														"'.$valor.'",
																														"'.$tipo.'",
																														"'.$id_material.'", "")';
				
				//echo $Consulta.'<br>';
				//Ejecutamos la consulta
				$Resultado = $this->db->query($Consulta);
			}
			
			//print_r($_POST);
			$tipo = '';
			$id_pedido = '';
			$corr = '';
			//Exploramos el array con la informacion recibida por POST.
			//print_r($_POST);
			foreach($_POST as $nombre_campo => $valor)
			{
				//Exploramos el array de loa materiales.
				$materiales = explode('_', $nombre_campo);
				$tipo = $materiales[0];
				//Verifico que tipo de informacion es la que se quiere ingresar.
				if($tipo == 'de' or $tipo == 'ot')
				{
					$id_pedido = $materiales[1];
					$corr = $materiales[2];
				}
				
				if($tipo != "ot")
				{
					continue;
				}
				//Ingresamos la informacion.
				$Consulta = 'insert into pedido_nota_material values(NULL,
																								"'.$id_nota_env.'",
																								"'.$id_pedido.'",
																								"'.$valor.'",
																								"'.$tipo.'",
																								"0",
																								"'.$this->input->post("de_".$id_pedido."_$corr").'")';
				//echo $Consulta.'<br>';
				//Ejecutamos la  consulta.
				$Resultado = $this->db->query($Consulta);
				
			}
	//Regresamos el id de la nota de envio.
	return $id_nota_env;
	}

}
/* Fin del archivo */