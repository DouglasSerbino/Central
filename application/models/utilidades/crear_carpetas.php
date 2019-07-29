<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crear_carpetas extends CI_Model {
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Creacion de carpetas para almacenar archivos adjuntos.
	 *En esta clase se crearan las carpetas para los procesos y los pedidos.
	 *La ruta base sera la misma siempre.
	 *@param string $Ruta: La carpeta en la que se trabajara.
	 *@param string $Carpeta: El nombre de la nueva carpeta.
	 *@return nada.
	*/
	function creacion_carpetas($Ruta, $Carpeta)
	{
		
		//======Debo crear las carpetas para los archivos adjuntos======
		//arc_adj_copr = Archivos_Adjuntos_Corporativos
		/*$carp = './arc_adj_corp'.$Ruta;
		
		$nue_carp = $carp.'\\'.$Carpeta;*/
		$carp = './arc_adj_corp'.$Ruta;
		//echo $carp;
		$nue_carp = $carp.'/'.$Carpeta;
		
		if(!file_exists($carp))
		{
			mkdir($carp, 0777);
			chmod($carp, 0777);
		}
		
		if(!file_exists($nue_carp))
		{
			mkdir($nue_carp, 0777);
			chmod($nue_carp, 0777);
		}
		
	}
	
}


/* Fin del archivo */
