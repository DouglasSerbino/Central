<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Especificacion_modificar_m extends CI_Model {
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
  /**
	 *Modifica las especificaciones del pedido segun los nuevos datos.
	 *@param string $Id_Pedido.
	 *@param array $Especs.
	 *@param array $Materiales.
	 *@return nada.
	*/
	function modificar(
		$Id_Pedido,
		$Especs,
		$Materiales
	)
	{
		
		//Este array sirve para saber que campo de cada tabla ha sido modificado.
		//Asi se evita digitar el nombre del campo en la base.
		$Campos_Cambiar = array(
			//Colores
			'color' => 'Color',
			'angulo' => 'Angulo',
			'lineaje' => 'Lineaje',
			'resolucion' => 'Resolucion',
			'anilox' => 'Anilox',
			'bcm' => 'BCM',
			//Distorsion
			'polimero' => 'Fotopol&iacute;mero',
			'stickyback' => 'StickyBack',
			'dp' => 'Distorsi&oacute;n',
			//General
			'unidad_medida' => 'Unidad de Medida',
			'ancho_arte' => 'Ancho',
			'alto_arte' => 'Alto',
			'ancho_fotocelda' => 'Ancho Fotocelda',
			'alto_fotocelda' => 'Alto Fotocelda',
			'color_fotocelda' => 'Color Fotocelda',
			'fotocelda_izquierda' => 'Fotocelda Derecha',
			'fotocelda_derecha' => 'Fotocelda Izquierda',
			'lado_impresion' => 'Lado de Impresi&oacute;n',
			'codb_num' => 'C&oacute;digo de Barra',
			'repet_ancho' => 'Repe. Ancho',
			'repet_alto' => 'Repe. Alto',
			'separ_ancho' => 'Sepa. Ancho',
			'separ_alto' => 'Sepa. Alto',
			'embobinado_cara' => 'Embo. Cara',
			'embobinado_dorso' => 'Embo. Dorso',
			'emulsion_negativo' => 'Emulsi&oacute;n Negativo',
		);
		
		
		$Cambios_Efectuados = array();
		$Campos_Vacios = true;
		
		
		
		
		
		
		
		$Id_Especificacion_General = '';
		//Cada tabla tiene campos personalizados: Textos, radios, cheques, selects.
		//Cada campo tiene una forma de validacion propia, asi que validare campo
		//por campo y tabla por tabla.
		
		
		$Tablas_Directas = array(
			'general' => '',
			'matrecgru' => '',
			'matsolgru' => ''
		);
		
		
		$Consulta = '
			select id_especificacion_general
			from especificacion_general
			where id_pedido = "'.$Id_Pedido.'"
		';
		$Resultado = $this->db->query($Consulta);
		$Id_Especificacion_General = 0;
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
			$Id_Especificacion_General = $Fila['id_especificacion_general'];
		}
		
		
		//Let's go! (No se que significa)
		foreach($Especs as $Tabla => $Campos)
		{
			
			if(
				'colores_estr' == $Tabla
			)
			{
				continue;
			}
			
			
			$Id_Tabla_Externa = 'id_pedido = "'.$Id_Pedido.'"';
			if(!isset($Tablas_Directas[$Tabla]))
			{
				$Id_Tabla_Externa = 'id_especificacion_general = "'.$Id_Especificacion_General.'"';
			}
			
			
			if('matrecgru' == $Tabla || 'matsolgru' == $Tabla)
			{
				//<!-- ********************************** -->
				//echo '<br />'.$Tabla.'<br />';
				
				
				//Se elimina los registros que tuviere esta tabla para luego agregar los nuevos
				$Consulta = '
					delete from especificacion_'.$Tabla.' where '.$Id_Tabla_Externa.'
				';
				$this->db->query($Consulta);
				
				$Campo = 'recibido';
				if('matsolgru' == $Tabla)
				{
					$Campo = 'solicitado';
				}
				
				$Consulta_Array = array();
				
				foreach($Materiales[$Campo] as $Index => $Material)
				{
					if('on' == $this->input->post('mat_'.$Campo.'_'.$Material))
					{
						$Consulta_Array[] = '(NULL, "'.$Material.'", "'.$Id_Pedido.'")';
					}
				}
				
				if(0 < count($Consulta_Array))
				{
					//Se ingresan los nuevos materiales
					$Consulta = 'insert into especificacion_'.$Tabla.' values '.implode(', ', $Consulta_Array);
					$this->db->query($Consulta);
				}
				
			}
			/*elseif('acabado' == $Tabla)
			{
				//<!-- ********************************** -->
				//echo '<br />'.$Tabla.'<br />';
				
				
				//Se elimina los registros que tuviere esta tabla para luego agregar los nuevos
				$Consulta = '
					delete from especificacion_'.$Tabla.' where '.$Id_Tabla_Externa.'
				';
				$this->db->query($Consulta);
				
				$Consulta_Array = array();
				
				foreach($Tipo_Acabado as $Index => $Acabado)
				{
					if('on' == $this->input->post('impd_acabado_'.$Acabado['id_tipo_impd_acabado']))
					{
						$Consulta_Array[] = '(
							NULL,
							"'.$Acabado['id_tipo_impd_acabado'].'",
							"'.$Id_Especificacion_General.'"
						)';
					}
				}
				
				
				if(0 < count($Consulta_Array))
				{
					//Se ingresan los nuevos materiales
					$Consulta = 'insert into especificacion_'.$Tabla.' values '.implode(', ', $Consulta_Array);
					$this->db->query($Consulta);
				}
				
			}*/
			elseif('colores' == $Tabla)
			{
				//<!-- ********************************** -->
				//echo '<br />'.$Tabla.'<br />';
				
				//Se elimina los registros que tuviere esta tabla para luego agregar los nuevos
				$Consulta = '
					delete from especificacion_'.$Tabla.' where '.$Id_Tabla_Externa.'
				';
				$this->db->query($Consulta);
				
				$Consulta_Array = array();
				$Mensaje_Colores = '';
				
				for($i = 1; $i <= 10; $i++)
				{
					
					$Color_Ant = array();
					$Color_Nue = array();
					$Cambia_Color = false;
					
					
					if('' != $this->input->post('color_'.$i))
					{
						$Color_Array = array();
						
						foreach($Especs['colores_estr'] as $Campo => $Valor)
						{
							$Color_Array[] = '"'.$this->seguridad_m->mysql_seguro(
								$this->input->post($Campo.'_'.$i)
							).'"';
							
							$Valor = '';
							if(isset($Especs['colores'][$i][$Campo]))
							{
								$Valor = $Especs['colores'][$i][$Campo];
							}
							
							if(isset($Campos_Cambiar[$Campo]))
							{
								$Color_Ant[] = $Valor;
								$Color_Nue[] = $this->seguridad_m->mysql_seguro(
									$this->input->post($Campo.'_'.$i)
								);
							}
							
							
							if($this->seguridad_m->mysql_seguro($this->input->post($Campo.'_'.$i)) != $Valor && isset($Campos_Cambiar[$Campo]))
							{
								$Cambia_Color = true;
								
								//Era un campo vacio?
								if('' !== $Valor)
								{
									$Campos_Vacios = false;
								}
							}
						}
						
						$Consulta_Array[] = '(
							NULL,
							"'.$Id_Especificacion_General.'",
							'.implode(', ', $Color_Array).'
						)';
					}
					else
					{
						foreach($Especs['colores_estr'] as $Campo => $Valor)
						{
							if(isset($Especs['colores'][$i][$Campo]) && isset($Campos_Cambiar[$Campo]))
							{
								$Color_Ant[] = $Especs['colores'][$i][$Campo];
								$Cambia_Color = true;
							}
						}
					}
					
					
					if($Cambia_Color)
					{
						$Cambios_Efectuados[] = '&nbsp; Color '.$i.': &nbsp; "'.implode(', ', $Color_Ant).'" &nbsp; => &nbsp; '.implode(', ', $Color_Nue);
					}
					
				}
				
				if(0 < count($Consulta_Array))
				{
					
					$Consulta = '
						insert into especificacion_colores values '.implode(', ', $Consulta_Array).'
					';
					$this->db->query($Consulta);
					
				}
				
			}
			else
			{
				//<!-- ********************************** -->
				//echo '<br />'.$Tabla.'<br />';
				
				
				$Consulta_Array = array();
				
				foreach($Especs[$Tabla] as $Index => $Valor)
				{
					if(!isset($_POST[$Index]))
					{
						continue;
					}
					
					$Consulta_Array[] = $Index.' = "'.$this->seguridad_m->mysql_seguro(
						$this->input->post($Index)
					).'"';
					
					//Se ha realizado un cambio?
					if($this->seguridad_m->mysql_seguro($this->input->post($Index)) != $Valor && isset($Campos_Cambiar[$Index]))
					{
						$Cambios_Efectuados[] = '&nbsp; '.$Campos_Cambiar[$Index].': &nbsp; "'.$Valor.'" &nbsp; => &nbsp; '.$this->seguridad_m->mysql_seguro($this->input->post($Index));
						
						//Era un campo vacio?
						if('' !== $Valor && 0 !== $Valor)
						{
							$Campos_Vacios = false;
						}
					}
				}
				
				
				$Id_Tabla_Externa = 'id_pedido = "'.$Id_Pedido.'"';
				if(!isset($Tablas_Directas[$Tabla]))
				{
					$Id_Tabla_Externa = 'id_especificacion_general = "'.$Id_Especificacion_General.'"';
				}
				
				$Consulta = '
					update especificacion_'.$Tabla.'
					set '.implode(', ', $Consulta_Array).'
					where '.$Id_Tabla_Externa.'
				';
				
				if(0 < count($Consulta_Array))
				{
					$this->db->query($Consulta);
				}
				
			}
			
		}
		
		
		if(0 < count($Cambios_Efectuados))// && !$Campos_Vacios)
		{
			$Consulta = "<div onclick=\"$(this).children(\'span\').toggle()\">";
			$Consulta .= "Se realizaron los siguientes cambios en la hoja de planificaci&oacute;n: (Click para ver/ocultar)<br />";
			$Consulta .= "<span style=\"display:none;\">";
			$Consulta .= implode('<br />', $Cambios_Efectuados);
			$Consulta .= "</span></div>";
			$Consulta = "
				insert into observacion values(
					NULL,
					'".$Id_Pedido."',
					'".$this->session->userdata('id_usuario')."',
					'".date('Y-m-d H:i:s')."',
					'".$Consulta."',
					'p'
				)
			";
			$this->db->query($Consulta);
		}
		
		
	}
	
}

/* Fin del archivo */