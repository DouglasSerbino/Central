<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Generar_cache_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function preparar_cache($Cache)
	{
		if($Cache['mostrar'] == 'si')
		{
			include($Cache['Pagina']);
			return 'no_mostrar';
		}
		else
		{
			if($Cache['crear_cache'] == 'si')
			{
				//start the output buffer
				ob_start();
			}
			return 'mostrar';
		}
		
		
	}
	
	/**
	 *Esta funcion nos permitira crear una cache de la pagina seleccionada
	 *Si el reporte es tres meses menor a la fecha actual se creara una cache que permitira
	 *que la informacion se cargue mas rapido.
	*/
	function generar_cache($Cache)
	{
		if($Cache['crear_cache'] == 'si')
		{
			$fp = fopen($Cache['Pagina'], 'w'); 
			
			fwrite($fp, ob_get_contents()); 
			
			fclose($fp); 
			
			ob_end_flush();
		}
	}
	
	function validar_cache($direccion, $Anho, $Mes)
	{
		
		$Variables = array();
		$pagina_cache = './application/cache/'.$direccion.'.html';
		$Variables['Pagina'] = $pagina_cache;
		
		if(strtotime($Anho.'-'.$Mes) < strtotime('-80 days', (strtotime(date('Y-m')))) and $Anho.'-'.$Mes < date('Y-m'))
		{
			
			if('anual'==$Mes)
			{
				if($Anho != date('Y'))
				{
					$Variables['crear_cache'] = 'si';
				}
				else
				{
					$Variables['crear_cache'] = 'no';
				}
			}
			else
			{
				$Variables['crear_cache'] = 'si';
			}
		}
		else
		{
			$Variables['crear_cache'] = 'no';
		}
	
		
		if(file_exists($pagina_cache))
		{
			$Variables['base_datos'] = 'no';
			$Variables['mostrar'] = 'si';
		}
		else
		{
			$Variables['base_datos'] = 'si';
			$Variables['mostrar'] = 'no';
		}
		//exit();
		return $Variables;
	}
	
	function quitar_tildes($cadena)
	{
		$a = array('&Acute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', ' ', '&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;');
		$b = array('A', 'E', 'I', 'O', 'U', '', 'a', 'e', 'i', 'o', 'u', 'n');
		
		return str_replace($a, $b, $cadena);
	}
}
/* Fin del archivo */