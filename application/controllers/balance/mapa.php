<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mapa extends CI_Controller {
	
	
	public function index($Anho = 0, $Mes = 0)
	{
		/*
		 *Estas sentencias de codigo serviran para
		 *especificar que departamentos tienen acceso a este controlador
		*/
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		/*
		 *Verificaremos si un cliente quiere acceder a esta pagina
		 *Si es asi no puede porque esta informacion es confidencial.
		*/
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza para determinar si hemos recibido un año
		//Si no hemos recibido nada asignamos el año actual para generar el reporte.
		
		$Anho += 0;
		if(0 == $Anho)
		{
			$Anho = date('Y');
		}
		
		//Verificamos si el mes es menor a 9 para asignar el formato correcto
		$Mes += 0;
		if(0 < $Mes && 10 > $Mes)
		{
			$Mes = '0'.$Mes;
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Mapa Estrat&eacute;gico',
			'Mensaje' => '',
			'Ver_Anho' => $Anho,
			'Mes' => $Mes
		);
		
		$Variables['Meses'] = $this->Meses_m->MandarMesesCompletos();
				
		$this->load->model('balance/objetivos_m', 'objetivo');
		
		$Variables['Def_Objetivos'] = $this->objetivo->objetivos();
		
		$Variables['Datos'] = $this->objetivo->datos('todo', $Anho, 0, $Mes);
		
		$Vacios = false;
		foreach($Variables['Def_Objetivos'] as $Id_Perspectiva => $Perspectiva)
		{
			foreach($Perspectiva['Objs'] as $Id_Bsc_Objetivo => $Objetivo)
			{
				if(!isset($Variables['Datos'][$Id_Bsc_Objetivo]))
				{
					$this->objetivo->inserta_datos($Id_Bsc_Objetivo, $Anho);
					$Vacios = true;
				}
			}
		}
		
		if($Vacios)
		{
			$Variables['Datos'] = $this->objetivo->datos('todo', $Anho, 0, $Mes);
		}
		
		
		if(0 == $Mes)
		{
			foreach($Variables['Datos'] as $Index => $Meses)
			{
				$Variables['Datos'][$Index][0]['real'] = 0;
				$Variables['Datos'][$Index][0]['proy'] = 0;
				foreach($Meses as $Valores)
				{
					$Variables['Datos'][$Index][0]['real'] += $Valores['real'];
					$Variables['Datos'][$Index][0]['proy'] += $Valores['proy'];
				}
			}
		}
		
		
		$this->load->view('encabezado_v', $Variables);
		
		$this->load->view('balance/mapa_v');
		
		$this->load->view('pie_v');
		
		
	}
	
	
	
	public function comparar()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Anho = $this->input->post('anho_mapa') + 0;
		if(0 == $Anho)
		{
			$Anho = date('Y');
		}
		$Anho_Anterior = $Anho - 1;
		
		$Mes = $this->input->post('mes_mapa') + 0;
		if(0 < $Mes && 10 > $Mes)
		{
			$Mes = '0'.$Mes;
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Mapa Estrat&eacute;gico Comparativo',
			'Mensaje' => '',
			'Ver_Anho' => $Anho,
			'Anho_Anterior' => $Anho_Anterior,
			'Mes' => $Mes
		);
		
		
		$Variables['Meses'] = $this->Meses_m->MandarMesesCompletos();
		
		$this->load->model('balance/objetivos_m', 'objetivo');
		
		$Variables['Def_Objetivos'] = $this->objetivo->objetivos();
		
		for($i = $Anho_Anterior; $i <= $Anho; $i++)
		{
			
			$Variables['Comparar'][$i] = $this->objetivo->datos('todo', $i, 0, $Mes);
			
			$Vacios = false;
			foreach($Variables['Def_Objetivos'] as $Id_Perspectiva => $Perspectiva)
			{
				foreach($Perspectiva['Objs'] as $Id_Bsc_Objetivo => $Objetivo)
				{
					if(!isset($Variables['Comparar'][$i][$Id_Bsc_Objetivo]))
					{
						$this->objetivo->inserta_datos($Id_Bsc_Objetivo, $i);
						$Vacios = true;
					}
				}
			}
			
			if($Vacios)
			{
				$Variables['Comparar'][$i] = $this->objetivo->datos('todo', $i, 0, $Mes);
			}
			
			
			
			foreach($Variables['Comparar'][$i] as $Index => $Meses)
			{
				$Variables['Comparar'][$i][$Index]['real'] = 0;
				$Variables['Comparar'][$i][$Index]['proy'] = 0;
				
				foreach($Meses as $Indice => $Valores)
				{
					$Variables['Comparar'][$i][$Index]['real'] += $Valores['real'];
					$Variables['Comparar'][$i][$Index]['proy'] += $Valores['proy'];
					unset($Variables['Comparar'][$i][$Index][$Indice]);
				}
			}
			
			
		}
		
		
		$this->load->view('encabezado_v', $Variables);
		
		$this->load->view('balance/mapa_compara_v');
		
		$this->load->view('pie_v');
		
	}
	
	
	
}

/* Fin del archivo */