<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paginacion_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Modelo para paginacion.
	 *@param string $Direccion.
	 *@param string $Total_Filas.
	 *@param string $Filas_Pagina.
	 *@param string $Pagina_Actual.
	 *@return string $Paginacion.
	*/
	function paginar($Direccion, $Total_Filas, $Filas_Pagina, $Pagina_Actual)
	{
		
		//Total de paginas para esta cantidad de filas, segun el numero de filas deseadas
		$Total_Paginas = ceil($Total_Filas / $Filas_Pagina);
			
		//Paginacion que sera devuelta al usuario
		$Paginacion = ' &nbsp; <a href="'.$Direccion.'1/0">Inicio</a>';
		
		//Solo mostrare siete paginas previas
		$Inicio = $Pagina_Actual - 7;
		if($Inicio < 1)
		{
			$Inicio = 1;
		}
		
		//Solamente se mostraran siete paginas siguientes
		$Fin = $Pagina_Actual + 7;
		if($Fin > $Total_Paginas)
		{
			$Fin = $Total_Paginas;
		}
		
		//Rango de paginas a mostrar
		for($I = $Inicio; $I <= $Fin; $I++)
		{
			
			//Inicio para el limit en el sql
			$Inicio_Limit = ($I * $Filas_Pagina) - $Filas_Pagina;
			
			if($I == $Pagina_Actual)
			{
				//Si es la pagina actual, no posee enlace
				$Paginacion .= ' &nbsp; <strong>'.$I.'</strong>';
			}
			else
			{
				//Creacion del enlace para la paginacion
				$Paginacion .= ' &nbsp; <a href="'.$Direccion.''.$I.'/'.$Inicio_Limit.'">'.$I.'</a>';
			}
			
		}
		
		$Fin_Limit = ($Total_Paginas - 1) * $Filas_Pagina;
		
		$Paginacion .= ' &nbsp; <a href="'.$Direccion.''.$Total_Paginas.'/'.$Fin_Limit.'">Fin</a>';
		
		return $Paginacion;
		
	}
	
	
}


/* Fin del archivo */
