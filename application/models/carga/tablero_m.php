<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tablero_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *
	*/
	function pedidos($Usuarios)
	{
		
		//Guarda la condicion para realizar la consulta que busca los trabajos
		$Id_Usuarios_v = array();
		//Guarda la condicion para realizar la consulta que busca el ultimo tiempo
		//para los usuarios en la tabla pedido_tiempos
		$Id_Usuarios_T = array();
		//Almacena todos los trabajos por operador
		$Trabajos = array();
		
		
		//Se recore la lista de usuarios por departamento recibida para crear las condiciones
		foreach($Usuarios as $Dptos)
		{
			//Si es planificacion o ventas no se incluye
			if('n' == $Dptos['tiempo'] && 'SAP' != $Dptos['dpto'] && 'Despacho' != $Dptos['dpto'])
			{
				continue;
			}
			
			//Se recorren los usuarios correspondientes a este departamento
			foreach($Dptos['usuarios'] as $Id_Usuario => $Info)
			{
				//Condiciones para consultas futuras
				$Id_Usuarios_v[] = 'peus.id_usuario = "'.$Id_Usuario.'"';
				$Id_Usuarios_T[] = 'id_usuario = "'.$Id_Usuario.'"';
			}
			
		}
		
		
		//Si se obtuvieron usuarios para comparar se procede
		if(0 < count($Id_Usuarios_v))
		{
			//Se desea conocer la fecha de finalizacion del ultimo tiempo utilizado
			//por cada usuario para representarlo como bloque de inicio en el tablero
			$Consulta = '
				select max(fin) as fin, id_usuario
				from pedido_tiempos
				where fin >= "'.date('Y-m-d').' 00:00:00" and tiempo > 0
				and ('.implode(' or ', $Id_Usuarios_T).')
				group by id_usuario
			';
			$Resultado = $this->db->query($Consulta);
			
			//Si hay resultados, se procede a calcular el ancho de cada uno segun la
			//fecha de finalizacion para graficarla al inicio de cada usuario en el tablero
			if(0 < $Resultado->num_rows())
			{
				//Se recorre cada fila del resultado
				foreach($Resultado->result_array() as $Fila)
				{
					//Unicamente se nececita saber la hora
					$Fecha = explode(' ', $Fila['fin']);
					$Fecha = explode(':', $Fecha[1]);
					//Y la hora se pasa a minutos y se restan los minutos antes de las 8 am
					//La cantidad de minutos sera la cantidad de pixels a graficar
					$Ancho_Li = (($Fecha[0] * 60) + $Fecha[1]) - 480;
					//No se toma la hora del medio dia
					if(12 < $Fecha[0])
					{
						$Ancho_Li -= 60;
					}
					
					//Necesita menos dos pixels por el borde de cada elemento
					$Ancho_Li -= 2;
					
					if(0 > $Ancho_Li)
					{
						$Ancho_Li = 0;
					}
					
					//Asignacion del bloque inicial para cada usuario
					$Trabajos[$Fila['id_usuario']]['finalizado'] = $Ancho_Li;
				}
			}
			
			//Busqueda de los tiempos programados para cada trababajo por cada usuario
			$Consulta = '
				select codigo_cliente, proceso, proc.nombre, ped.id_pedido,
				tiempo_asignado, peus.id_usuario, id_peus, peus.estado
				from cliente clie, procesos proc, pedido ped, pedido_usuario peus
				where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = peus.id_pedido and estado != "Terminado"
				and ('.implode(' or ', $Id_Usuarios_v).')
				order by ped.id_pedido asc
			';
			$Resultado = $this->db->query($Consulta);
			
			//Si se obtienen resultados, se procede a tomar cada uno
			if(0 < $Resultado->num_rows())
			{
				
				foreach($Resultado->result_array() as $Fila)
				{
					
					//Asignacion de la informacion
					$Un_Array['pro'] = $Fila['codigo_cliente'].'-'.$Fila['proceso'];
					$Un_Array['nom'] = $Fila['nombre'];
					$Un_Array['tie'] = $Fila['tiempo_asignado'];
					$Un_Array['peu'] = $Fila['id_peus'];
					$Un_Array['est'] = $Fila['estado'];
					
					//Se guarda la infor en el array principal
					$Trabajos[$Fila['id_usuario']]['trabajos'][$Fila['id_pedido']] = $Un_Array;
					
				}
				
			}
			
			//Busqueda de los tiempos utilizados en los trabajos por operador
			$Consulta = '
				select tiem.id_pedido, tiem.id_usuario, sum(tiempo) as tiempo
				from pedido_tiempos tiem, pedido_usuario peus
				where peus.id_pedido = tiem.id_pedido and peus.id_usuario = tiem.id_usuario
				and estado != "Terminado"
				and ('.implode(' or ', $Id_Usuarios_v).')
				group by peus.id_peus order by peus.id_peus
			';
			//echo $Consulta.'<br>';
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				
				foreach($Resultado->result_array() as $Fila)
				{
					//Si existe este pedido en el array principal
					if(isset($Trabajos[$Fila['id_usuario']]['trabajos'][$Fila['id_pedido']]))
					{
						//Al tiempo programado se le resta el utilizado
						$Trabajos[$Fila['id_usuario']]['trabajos'][$Fila['id_pedido']]['tie'] -= $Fila['tiempo'];
						//Si el tiempo queda menor a cero
						if(0 > $Trabajos[$Fila['id_usuario']]['trabajos'][$Fila['id_pedido']]['tie'])
						{
							//Se asigna a cero para mostrar correctamente el bloque en el tablero
							$Trabajos[$Fila['id_usuario']]['trabajos'][$Fila['id_pedido']]['tie'] = 0;
						}
					}
					
				}
				
			}
			
			
			
		}
		
		//Se regresa el array principal [con o sin datos]
		return $Trabajos;
		
	}
	
	
	
	
	function asignar($Lista_Pedidos)
	{
		//Guardar las prioridades establecidas por el planificador
		
		
		//Se recorre el array con las prioridades provistas por usuario
		foreach($Lista_Pedidos as $Id_Usuario => $Pedidos)
		{
			
			//Se recorren los pedidos para el usuario en turno
			foreach($Pedidos as $Index => $Peus)
			{
				//Se actualiza la prioridad en pedido_usuario
				$Consulta = '
					update pedido_usuario
					set pprioridad = "'.$Index.'"
					where id_peus = "'.$Peus.'"
				';
				$this->db->query($Consulta);
				
			}
			
		}
		
		//Todo 0k
		return 'ok';
		
	}
	
	
}

/* Fin del archivo */