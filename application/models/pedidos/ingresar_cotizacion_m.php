<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingresar_cotizacion_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Almacena la cotizacion en la base de datos.
	 *@param string $Id_Pedido.
	 *@param string $Productos.
	 *@param string $Id_Cliente.
	 *@param string $Accion [Agregar|Modificar].
	 *@return string ['ok'|'error'|$Pulgadas_Flexo].
	*/
	function index($Id_Pedido, $Productos, $Id_Cliente, $Pulgas = false, $Hijo = false)
	{

		$planchas_v = array(18 => true, 26 => true, 29 => true, 30 => true, 38 => true, 57 => true, 58 => true);
		$Pulgadas_Flexo = 0;

		//Listado de los productos que se han contemplado en la cotizacion
		//0 significa que solo puede haber un producto de ese tipo
		//1 significa que pueden haber mas de un producto de ese tipo
		$Prod_Cotizacion = array(5 => 0, 8 => 0, 7 => 0, 99 => 0, 39 => 1, 29 => 1, 73 => 0, 54 => 0, 103 => 0);
		$Prod_Nombres = array(
			5 => 'Arte Cambio de Formato',
			8 => 'Arte Desarrollo',
			7 => 'Arte Cambio de Textos',
			99 => 'Impresi&oacute;n',
			29 => 'Montaje Planchas',
			39 => 'Montaje Negativos',
			73 => 'Prueba de Color',
			54 => 'Flete',
			103 => 'Destilado de Solvente'
		);
		
		//Se elimina el producto_pedido existente
		$Consulta = '
			delete from producto_pedido where id_pedido = "'.$Id_Pedido.'"
		';
		
		$this->db->query($Consulta);
		
		//Almacenamiento de los productos seleccionados por el cliente
		$Cotizacion = array();
		$Total_Cotizacion = 0;
		$Coti_Nueva = '';
		
		
		//Si se establecio que se ingrese una cotizacion
		if('on' == $this->input->post('cotizacion'))
		{
			//Se recorren los productos de este cliente para tomar de su id los productos
			//ingresados por el planificador.
			
			foreach($Prod_Cotizacion as $iProducto => $tProducto)
			{
				
				$Vueltas = 1;
				if(1 == $tProducto)
				{
					$Vueltas = 4;
				}

				
				for($i = 0; $i < $Vueltas; $i++)
				{
					$Sufijo = $iProducto;
					if(1 == $tProducto)
					{
						$Sufijo = $iProducto.'_'.$i;
					}

					//Si este material ha sido seleccionado
					if(
						0 < ($this->input->post('subt_'.$Sufijo) + 0)
					)
					{
						
						$Coti_Nueva .= $Prod_Nombres[$iProducto].' - Precio: $'.$this->input->post('prec_'.$Sufijo).' - Cantidad: '.$this->input->post('pulg_'.$Sufijo).'<br />';

						
						$Cotizacion[] = '(
							NULL,
							"'.$iProducto.'",
							"'.$Id_Pedido.'",
							"'.$this->input->post('prec_'.$Sufijo).'",
							"'.$this->input->post('pulg_'.$Sufijo).'",
							"'.$this->input->post('cant_'.$Sufijo).'",
							"'.$this->input->post('anch_'.$Sufijo).'",
							"'.$this->input->post('alto_'.$Sufijo).'"

						)';
						
						$Total_Cotizacion += (
							$this->input->post('prec_'.$Sufijo)
							*
							$this->input->post('pulg_'.$Sufijo)
						);
						
						if($Pulgas)
						{
							if(isset($planchas_v[$iProducto]))
							{
								$Pulgadas_Flexo += $this->input->post('pulg_'.$Sufijo);
							}
						}
						
					}
				}
			}
			
			//Si hay elementos en la cotizacion
			if(0 < count($Cotizacion))
			{
				//Deben ser ingresados
				$Consulta = '
					insert into producto_pedido values '.implode(' , ', $Cotizacion).'
				';
				
				$this->db->query($Consulta);
				
			}
		}
		
		$Consulta = '
			select id_pedido_sap, venta
			from pedido_sap
			where id_pedido = "'.$Id_Pedido.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		$Tot_Coti = $Resultado->row_array();
		
		if(0 == $Resultado->num_rows())
		{
			//Se ingresa un registro para este pedido en la facturacion
			$Consulta = '
				insert into pedido_sap values (
					NULL,
					"'.$Id_Cliente.'",
					"'.$Id_Pedido.'",
					"",
					"",
					"'.$Total_Cotizacion.'",
					"0000-00-00",
					"No",
					"",
					"n"
				)
			';
			
		}
		else
		{
			$Modifica_Sap = '';
			if(0 == $Total_Cotizacion)
			{
				$Modifica_Sap = ',
					sap = "",
					orden = ""
				';
			}
			
			$Tot_Coti['venta'] = number_format($Tot_Coti['venta'], 2, '.', '');
			$Total_Cotizacion = number_format($Total_Cotizacion, 2, '.', '');
			
			if($Total_Cotizacion != $Tot_Coti['venta'])
			{
				$Consulta = '
					update pedido_sap
					set actualizar = "s"
					where id_pedido = "'.$Id_Pedido.'"
				';
				$this->db->query($Consulta);
			}
			
			//Se modifica el total de la cotizacion
			$Consulta = '
				update pedido_sap
				set venta = "'.$Total_Cotizacion.'"'.$Modifica_Sap.'
				where id_pedido = "'.$Id_Pedido.'"
			';
		}
		
		$this->db->query($Consulta);

		//Fin del proceso
		if(!$Pulgas)
		{
			return $Coti_Nueva;
		}
		else
		{
			return $Pulgadas_Flexo;
		}
		
	}
	
	
	function actualizar_cotizacion($Id_Pedido)
	{
		$Consulta = '
			update pedido_sap
			set actualizar = "s"
			where id_pedido = "'.$Id_Pedido.'"
	';
		$this->db->query($Consulta);
	}
	
}

/* Fin del archivo */