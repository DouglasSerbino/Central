<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listado_paises_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function paises_cliente()
	{

		$Paises_C = array(
			'sv' => 'El Salvador',
			'hn' => 'Honduras',
			'cr' => 'Costa Rica',
			'gt' => 'Guatemala',
			'nc' => 'Nicaragua',
			'pa' => 'Panam&aacute;'
		);

		return $Paises_C;

	}

}

/* Fin del archivo */