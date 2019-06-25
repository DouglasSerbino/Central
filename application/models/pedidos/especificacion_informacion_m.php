<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Especificacion_informacion_m extends CI_Model {
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
  /**
	 *Busca en la base de datos la informacion de las especificaciones del pedido
	 *senhalado.
	 *@param string $Id_Pedido.
	 *@param array $Especs.
	 *@return array.
	*/
	function pedido($Id_Pedido, $Especs = array())
	{
		//echo $Id_Pedido."\n";
		//Array multidimencional en el que se guardaran los datos recolectados
		if(0 == count($Especs))
		{
			$Especs = array(
				'general' => array(),
				'matrecgru' => array(),
				'matsolgru' => array(),
				'colores' => array(),
				'distorsion' => array(),
				'guias' => array()
			);
		}
		
		$Tablas_Directas = array(
			'general' => '',
			'matrecgru' => '',
			'matsolgru' => ''
		);
		
		$Id_Especificacion_General = 0;
		
		
		
		//Se solicita la informacion tabla por tabla
		foreach($Especs as $Tabla => $Informacion)
		{
			
			//Consulta para tomar el nombre de los campos
			$Consulta = '
				describe especificacion_'.$Tabla.'
			';
			
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				//Se crean las casillas para cada tabla de especificaciones y se establece
				//un valor predeterminado.
				$Especs[$Tabla][$Fila['Field']] = '';
			}
			
			
			//Especificaciones de la tabla solicitada
			$Consulta = '
				select *
				from especificacion_'.$Tabla.'
				where
			';
			if(!isset($Tablas_Directas[$Tabla]))
			{
				$Consulta .= '
					id_especificacion_general = "'.$Id_Especificacion_General.'"
				';
			}
			else
			{
				$Consulta .= '
					id_pedido = "'.$Id_Pedido.'"
				';
			}
			
			if('colores' == $Tabla)
			{
				$Consulta .= ' order by id_especificacion_colores';
			}
			
			//echo $Consulta;
			
			$Resultado = $this->db->query($Consulta);
			
			
			
			//De las dos tablas en la condicion se realiza una captura distinta
			if('matrecgru' == $Tabla || 'matsolgru' == $Tabla)
			{
				
				//Debo tomar el valor de los campos id_material_recibido_grupo e
				//id_material_solicitado_grupo, segun sea el nombre de la tabla.
				$Campo = 'recibido';
				if('matsolgru' == $Tabla)
				{
					$Campo = 'solicitado';
				}
				
				//Pondre como indice del array para esta tabla el valor del campo buscado
				//Asi puedo realizar un isset en la vista para saber cuales son los materiales
				//recibidos y solicitados.
				foreach($Resultado->result_array() as $Fila)
				{
					$Especs[$Tabla][$Fila['id_material_'.$Campo.'_grupo']] = '';
				}
				
			}
			elseif('acabado' == $Tabla)
			{
				
				//Pondre como indice del array para esta tabla el valor del campo buscado
				//Asi puedo realizar un isset en la vista para saber cuales son los materiales
				//recibidos y solicitados.
				foreach($Resultado->result_array() as $Fila)
				{
					$Especs[$Tabla][$Fila['id_tipo_impd_acabado']] = '';
				}
				
			}
			elseif('colores' == $Tabla)
			{
				
				//Los colores son extranhos tambien.
				//Pueden varios colores para un pedido, asi que aqui vamos:
				//Inicializamos el array que contendra los colores.
				$Especs_Colores = array();
				$Color_i = 1;
				
				//Necesito una muestra de la estructura de la tabla colores
				$Especs['colores_estr'] = $Especs[$Tabla];
				unset($Especs['colores_estr']['id_especificacion_general']);
				unset($Especs['colores_estr']['id_especificacion_'.$Tabla]);
				
				foreach($Resultado->result_array() as $Fila)
				{
					foreach($Especs[$Tabla] as $Campo => $Vacio)
					{
						$Especs_Colores[$Color_i][$Campo] = $Fila[$Campo];
					}
					//Elimino los id de la tabla, no me serviran
					unset($Especs_Colores[$Color_i]['id_especificacion_general']);
					unset($Especs_Colores[$Color_i]['id_especificacion_'.$Tabla]);
					$Color_i++;
				}
				$Especs[$Tabla] = $Especs_Colores;
				
			}
			else
			{
				
				if(1 == $Resultado->num_rows())
				{
					//Se toman los datos de la planificacion anterior si hubiera
					$Especs[$Tabla] = $Resultado->row_array();
					if('general' == $Tabla)
					{
						$Id_Especificacion_General = $Especs[$Tabla]['id_especificacion_general'];
					}
				}
				
			}
			
			//Elimino los id de la tabla, no me serviran
			unset($Especs[$Tabla]['id_pedido']);
			unset($Especs[$Tabla]['id_especificacion_'.$Tabla]);
			unset($Especs[$Tabla]['id_especificacion_general']);
			
		}
		
		//print_r($Especs);
		
		//Toda la informacion recopilada se envia para ser utilizada
		return $Especs;
		
	}
	
	
  /**
	 *Busca en la base de datos la informacion de las especificaciones del ultimo
	 *pedido para el proceso senhalado.
	 *@param string $Id_Proceso.
	 *@param array $Especs.
	 *@return array.
	*/
	function ultima($Id_Proceso, $Especs = array())
	{
		
		//Id_Pedido anterior
		$Consulta = '
			select id_pedido
			from pedido
			where id_proceso = "'.$Id_Proceso.'"
			order by id_pedido desc
			limit 0, 1
		';
		
		$Resultado = $this->db->query($Consulta);
		
		//Valor predefinido
		$Id_Pedido = 0;
		
		//Si hay resultado
		if(1 == $Resultado->num_rows())
		{
			//Capturo el id_pedido resultante
			$Fila = $Resultado->row_array();
			$Id_Pedido = $Fila['id_pedido'];
		}
		
		//Se regresa la informacion de las especificaciones obtenidas
		if(0 == count($Especs))
		{
			return $this->pedido($Id_Pedido);
		}
		else
		{
			return $this->pedido($Id_Pedido, $Especs);
		}
		
	}
	
	
}

/* Fin del archivo */