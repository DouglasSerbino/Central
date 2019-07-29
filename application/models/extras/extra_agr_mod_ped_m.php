<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_agr_mod_ped_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Buscar al usuario seleccionado para ingresar las extras.
	 *Actualmente busca solamente los usuarios activos.
	 *@param $id_usuario: Id del usuario de que se quiere mostrar la informacion.
	 *@return array
	*/
	function Usuarios($id_usuario)
	{
        $Consulta = '
								select nombre, departamento
								from usuario usu, departamentos dpto
								where usu.id_dpto = dpto.id_dpto
								and id_usuario = "'.$id_usuario.'"
								and usu.activo = "s"
								and id_grupo = "'.$this->session->userdata('id_grupo').'"
        ';
        
		$Resultado = $this->db->query($Consulta);
			
		return $Resultado->result_array();
	}
	
		/**
	 *Extraer el inicio y fin de los trabajos.
	 *@param $id_extra: Para realizar la busquedad se necesita el id de la extra.
	 *@return array
	*/
	function Inicio_fin_extra($id_extra)
	{
        $Consulta = '
								select inicio, fin, fecha
								from extra
								where id_extra = "'.$id_extra.'"
        ';
        
		$Resultado = $this->db->query($Consulta);
		return $Resultado->result_array();
	}
	
	/**
	 *Seleccionamos todos los comentarios que pertenecen a la extra_pedido.
	 *@param $id_extra: id de la extra que queremos mostrar.
	 *@return array
	*/
	function comentario_extra($id_extra)
	{
        $Consulta = '
								select id_pedido, comentario
								from extra_pedido
								where id_extra = "'.$id_extra.'"
        ';
        
		$Resultado = $this->db->query($Consulta);
			
		return $Resultado->result_array();
	}
	
	/**
	 *Seleccionamos todos los comentarios que pertenecen a la extra_otro.
	 *@param $id_extra: id de la extra que queremos mostrar.
	 *@return array
	*/
	function comentario_extra_otro($id_extra)
	{
        $Consulta = '
								select otro, id_exto, comentario
								from extra_otro
								where id_extra = "'.$id_extra.'"
        ';
        
		$Resultado = $this->db->query($Consulta);
			
		return $Resultado->result_array();
	}
	
		/**
	 *Seleccionamos todas las horas extras que han hecho los usuarios.
	 *@param $id_extra: id de la extra que queremos mostrar.
	 *@return array
	*/
	function mostrar_extrass($id_extra)
	{
        $Consulta = '
								select extped.id_extped, cli.codigo_cliente,
								proc.proceso, proc.nombre, ped.fecha_entrega
								from procesos proc, pedido ped, extra_pedido extped, cliente cli
								where proc.id_proceso = ped.id_proceso
								and cli.id_cliente = proc.id_cliente
								and ped.id_pedido = extped.id_pedido
								and id_extra = "'.$id_extra.'"
        ';
        
		$Resultado = $this->db->query($Consulta);
			
		return $Resultado->result_array();
	}
	
	/**
	 *Listado de pedidos que estan activos.
	 *@return array
	*/
	function pedido_activo()
	{
        $Consulta = '
						select distinct ped.id_pedido, final
						from pedido ped,
								especificacion_general espgen,
								especificacion_matsolgru espmatsolgru,
								material_solicitado mate,
								material_solicitado_grupo matsolgru
						where ped.id_pedido = espgen.id_pedido
							and ped.id_pedido = espmatsolgru.id_pedido
							and espgen.id_pedido = espmatsolgru.id_pedido
							and espmatsolgru.id_material_solicitado_grupo = matsolgru.id_material_solicitado_grupo
							and mate.id_material_solicitado = matsolgru.id_material_solicitado 
							and ped.fecha_reale = "0000-00-00"
							and (final+0) <= "3"
        ';

		$Resultado = $this->db->query($Consulta);
		return $Resultado->result_array();
	}
	
		/**
	 *Informacion de los pedidos y del usuario.
	 *Mostramos toda la informacion de los trabajos que tienen los usuarios.
	 *@return array
	*/
	function pedido_usuario($id_usuario, $todos)
	{
		$id_dpto = '';
		if('ok' == $todos)
		{
			$Consulta_dpto = 'select usu.id_dpto
												from departamentos dpto, usuario usu
												where usu.id_dpto = dpto.id_dpto
												and usu.id_usuario = "'.$id_usuario.'"
												and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"';
			$Resultado = $this->db->query($Consulta_dpto);
			$Datos = $Resultado->result_array();
			foreach($Datos as $Dpto)
			{
				$id_dpto = $Dpto['id_dpto'];
			}
		}
		if($id_dpto != '')
		{
			$Condicion = "and dpto.id_dpto = $id_dpto";
		}
		else
		{
			$Condicion = "and peus.id_usuario = $id_usuario";
		}
		//echo $id_dpto;
        $Consulta = '
							select distinct(ped.id_pedido), clien.id_cliente, clien.codigo_cliente,
							proc.proceso, proc.nombre, ped.fecha_entrega
							from procesos proc, pedido ped,
							pedido_usuario peus, cliente clien,
							departamentos dpto, usuario usu
							where usu.id_usuario = peus.id_usuario
							and usu.id_dpto = dpto.id_dpto
							and ped.id_pedido = peus.id_pedido
							and ped.id_proceso = proc.id_proceso
							and proc.id_cliente = clien.id_cliente
							and ped.fecha_reale = "0000-00-00"
							and peus.estado != "Terminado"
							and peus.estado != "Aprobacion"
							'.$Condicion.'
							and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
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
	 *Funcion que determinara el monto a pagar a cada usuario
	 **/
	
	function determinar_monto_pagar($inicio, $fin, $id_user_u)
	{
		//Determinamos la longitud de la hora de inicio.
		if(strlen($inicio) < 3)
		{
			$inicio .= ":00";
		}

		//Determinamos la longitud de la cadena de la hora de fin.
		if(strlen($fin) < 3)
		{
			$fin .= ":00";
		}
		//Verificamos si la hora de fin es igual a 24. Hora militar.
		if(substr($fin, 0, 2) == "24")
		{
			$fin_tmp = substr($fin, 3,2);
			$fin = "00:$fin_tmp";
		}
		
		//Buscamos el valor de la hora que se le paga al usuario.
		$Consulta = 'select hora from usuario where id_usuario = "'.$id_user_u.'"';
		
		$Resultado = $this->db->query($Consulta);
		$Hora = $Resultado->result_array();
		foreach($Hora as $Datos_hora)
		{
			//Asignamos el resultado a una variable.
			$hora_d = $Datos_hora["hora"];
		}
		
		$total_h = 0;
		
		//Verificamos si la hora de inicio es mayor a la hora de fin.
		if(substr($inicio, 0,2) > substr($fin, 0,2))
		{
			//Extraemos la hora de inicio.
			$hora_inicio = substr($inicio, 0,2);
			//La hora de inicio la restamos de 24 para saber
			//si esta sobrepasa de este dia.
			$hora = 24 - $hora_inicio;
			//Extraemos los minutos.
			$minuto_inicio = substr($inicio, 3,2);
			//Minutos de inicio
			$minuto_inicio = (($minuto_inicio * 100) / 60) / 100;
			//Definimos la hora de inicio
			$hora = $hora - $minuto_inicio;
			//Total de horas
			$total_h += $hora;
			
			//Extraemos la hora de fin
			$hora_fin = substr($fin, 0,2);
			//Extraemos los minutos de fin.
			$minuto_fin = substr($fin, 3,2);
			//Minutos de fin reales.
			$minuto_fin = (($minuto_fin * 100) / 60) / 100;
			//Hora de fin.
			$hora = $hora_fin + $minuto_fin;
			//Hora total
			$total_h += $hora;
			//Tope de las horas
			$tope = $total_h;
		}
		else
		{
			//Extraemos la hora de inicio 
			$hora_inicio = substr($inicio, 0,2);
			//Extraemos los minutos
			$minuto_inicio = substr($inicio, 3,2);
			//Minutos reales
			$minuto_inicio = (($minuto_inicio * 100) / 60) / 100;
			//Hora total.
			$hora1 = $hora_inicio + $minuto_inicio;
			
			//Extraemos la hora de fin
			$hora_fin = substr($fin, 0,2);
			//Extraemos los minutos de fin
			$minuto_fin = substr($fin, 3,2);
			//Minutos de fin reales
			$minuto_fin = (($minuto_fin * 100) / 60) / 100;
			//Establecemos la hora de fin
			$hora2 = $hora_fin + $minuto_fin;
			//Total de horas.
			$total_h = $hora2 - $hora1;
			//Tope de calculo de las horas.
			$tope = $total_h;
		}
		
		$money = 0;
		//Redondeamos el valor.
		
		$hora_i = floor($hora_inicio[0]);
		$hora_f = floor($hora_fin[0]);
		
		$n_valor = $hora_i;
		$regular = 0;
		$mas = 0;
		
		//Establecemos un FOR que nos permitira
		//determinar el valor de las extras.
		for($cont = 0; $cont < $tope; $cont++)
		{
			if($n_valor >= 6 && $n_valor <= 18)
			{
				//Horas normales
				$regular++;
			}
			else
			{
				//Horas nocturnas.
				$mas++;
			}
			
			$n_valor++;
			if($n_valor > 24)
			{
				$n_valor = 1;
			}
		}
		//Verificamos si las horas son en el dia.
		if(($hora_f >= 6 && $hora_f <= 18) && $minuto_fin > 0 && $regular > 1)
		{
			$regular--;
		}
		//Horas normales
		if($regular > 0)
		{
			//Verificamos si las horas son normales.
			if($hora_i >= 6 && $hora_i <= 18)
			{
				$regular = $regular - $minuto_inicio;
			}
			//Verificamos si la hora final es normal.
			if($hora_f >= 6 && $hora_f <= 18)
			{
				$regular = $regular + $minuto_fin;
			}
		}
		
		$mas = $tope - $regular;
		//Establecemos el monto a pagar del usuario.
		$money = ($regular * $hora_d * 2) + ($mas * $hora_d * 2.5);
		return $money.'-'.$total_h;
	}
	
	
	
	/**
	 *Funcion que servira para almacenar la informacion.
	 **/
	
	function guardar_extras($total, $fecha, $inicio,
													$fin, $id_user_u, $id_extra)
	{
		$monto = $this->determinar_monto_pagar($inicio,$fin, $id_user_u);
		//Extraemos la informacion.
		$explorar = explode('-', $monto);
		//Asignamos el resultado a una variable.
		$monto_pagar = $explorar[0];
		$total_h = $explorar[1];
		
		if('-' == $id_extra or 0 == $id_extra)
		{
			$Consulta = 'insert into extra values (NULL,
																			"'.$id_user_u.'",
																			"'.$inicio.'",
																			"'.$fin.'",
																			"'.$fin.'",
																			"'.$fecha.'",
																			"'.$total_h.'",
																			"'.$monto_pagar.'",
																			"'.$this->session->userdata('id_usuario').'"
																			)';
			$Resultado = $this->db->query($Consulta);
			
			$Consulta = 'select id_extra
									from extra
									where id_usuario = "'.$id_user_u.'"
									order by id_extra desc limit 0, 1';
				
			$Resultado = $this->db->query($Consulta);
			$Informacion = $Resultado->result_array();
			foreach($Informacion as $Datos)
			{
				$id_extra = $Datos["id_extra"];
			}
			
		}
		else
		{
			$Consulta = 'update extra set
														inicio = "'.$inicio.'",
														fin = "'.$fin.'",
														fin_real = "'.$fin.'",
														total_h = "'.$total_h.'",
														total_m = "'.$monto_pagar.'"
														where id_extra = "'.$id_extra.'"';
			$Resultado = $this->db->query($Consulta);
		
			$Consulta = 'delete from extra_pedido where id_extra = "'.$id_extra.'"';
			$Resultado = $this->db->query($Consulta);
			$Consulta = 'delete from extra_otro where id_extra = "'.$id_extra.'"';
			$Resultado = $this->db->query($Consulta);
		}
		
		
		for($i = 0; $i < $total; $i++)
		{
			$comenta_i2 = $this->seguridad_m->mysql_seguro($this->input->post("comenta_$i"));	
			$id_pedido2 = $this->seguridad_m->mysql_seguro($this->input->post("id_pedido$i"));
			$Nombre2 = $this->seguridad_m->mysql_seguro($this->input->post("nombre$i"));
			
			if($this->input->post("check$i") == "on")
			{
				if(substr($id_pedido2, 0,4) != "otro")
				{
					$Consulta = 'insert into extra_pedido values(NULL,
																											"'.$id_extra.'",
																											"'.$id_pedido2.'",
																											"'.$comenta_i2.'"
																											)';
					$Resultado = $this->db->query($Consulta);
				}
				else
				{
					$Consulta = 'insert into extra_otro values(NULL,
																									"'.$id_extra.'",
																									"'.$Nombre2.'",
																									"'.$comenta_i2.'")';
					$Resultado = $this->db->query($Consulta);
				}
			}
		}
		
	return 'ok';

	}
	
	
	/**
	 *Funcion que nos servira para modificar la informacion.
	 **/
	
	function modificar_extras($total, $fecha, $inicio, $fin, $id_user_u, $id_extra)
	{
		$monto = $this->determinar_monto_pagar($inicio,$fin, $id_user_u);
		//Extraemos la informacion.
		$explorar = explode('-', $monto);
		//Asignamos el resultado a una variable.
		$money = $explorar[0];
		$total_h = $explorar[1];
		$Consulta = 'update extra set
									inicio = "'.$inicio.'",
									fin = "'.$fin.'",
									fin_real = "'.$fin.'",
									total_h = "'.$total_h.'",
									total_m = "'.$money.'"
								where id_extra = "'.$id_extra.'"';
		
		$Resultado = $this->db->query($Consulta);
		
		for($i = 0; $i < $total; $i++)
		{
			
			if($this->input->post("check$i") == "on")
			{
				if($this->input->post("id_extped$i") != '')
				{
					$Consulta = "delete from extra_pedido where id_extped = ".$this->seguridad_m->mysql_seguro($this->input->post("id_extped$i"));
					$Resultado = $this->db->query($Consulta);
				}
				else
				{
					if($this->input->post("id_exto$i") != '')
					{
						$Consulta = "delete from extra_otro where id_exto = ".$this->seguridad_m->mysql_seguro($this->input->post("id_exto$i"));
						$Resultado = $this->db->query($Consulta);
					}
				}
			}
		}
		return 'ok';
	}
}

/* Fin del archivo */