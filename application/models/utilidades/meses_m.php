<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Meses_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
    function MandarMesesAbre()
    {
        $Meses =
            array(
                '01' => 'Ene',
                '02' => 'Feb',
                '03' => 'Mar',
                '04' => 'Abr',
                '05' => 'May',
                '06' => 'Jun',
                '07' => 'Jul',
                '08' => 'Ago',
                '09' => 'Sep',
                '10' => 'Oct',
                '11' => 'Nov',
                '12' => 'Dic'
               );
        return $Meses;
    }
    
    function MandarMesesCompletos()
    {
        $Meses =
            array(
                '01' => 'Enero',
                '02' => 'Febrero',
                '03' => 'Marzo',
                '04' => 'Abril',
                '05' => 'Mayo',
                '06' => 'Junio',
                '07' => 'Julio',
                '08' => 'Agosto',
                '09' => 'Septiembre',
                '10' => 'Octubre',
                '11' => 'Noviembre',
                '12' => 'Diciembre'
               );
        return $Meses;
    }
}
?>