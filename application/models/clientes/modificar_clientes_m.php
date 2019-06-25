<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_clientes_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}

	
	function modificar_sql(
		$Id_grupo,
		$Id_Cliente,
		$Nombre,
		$Direccion,
		$NIT,
		$Web,
		$Credito,
		$Contactos_v,
		$Maquinas_v,
		$Aniloxes_v,
		$Planchas_v,
		$Productos_v,
		$CPais
	)
	{
		

		$Consulta = '
			update cliente set nombre = "'.$Nombre.'", direccion = "'.$Direccion.'", 
			nit = "'.$NIT.'", web = "'.$Web.'", credito = "'.$Credito.'", pais = "'.$CPais.'"
			where id_cliente = "'.$Id_Cliente.'" and id_grupo = "'.$Id_grupo.'"
		';
		$this->db->query($Consulta);
		

		$Consulta = '
			delete from cliente_contacto
			where id_cliente = "'.$Id_Cliente.'"
		';
		$this->db->query($Consulta);

		//Ingresar los contactos
		if(0 < count($Contactos_v))
		{
			
			$Consulta = array();

			foreach($Contactos_v as $Datos)
			{
				
				$Consulta[] = '
					(
						NULL,
						"'.$Id_Cliente.'",
						"'.$Datos['nomb'].'",
						"'.$Datos['carg'].'",
						"'.$Datos['emai'].'",
						"'.$Datos['tofi'].'",
						"'.$Datos['tdir'].'",
						"'.$Datos['tcel'].'"
					)
				';

			}

			$Consulta = '
				insert into cliente_contacto values '.implode(', ', $Consulta).'
			';
			$this->db->query($Consulta);

		}
		

		$Consulta = '
			delete from cliente_maquina
			where id_cliente = "'.$Id_Cliente.'"
		';
		$this->db->query($Consulta);
		
		//Ingresar las Maquinas
		if(0 < count($Maquinas_v))
		{
			
			$Consulta = array();

			foreach($Maquinas_v as $Datos)
			{
				
				$Consulta[] = '
					(
						NULL,
						"'.$Id_Cliente.'",
						"'.$Datos['maqu'].'",
						"'.$Datos['colo'].'"
					)
				';

			}

			$Consulta = '
				insert into cliente_maquina values '.implode(', ', $Consulta).'
			';
			$this->db->query($Consulta);
			
		}
		

		$Consulta = '
			delete from cliente_placa
			where id_cliente = "'.$Id_Cliente.'"
		';
		$this->db->query($Consulta);
		
		//Ingresar las Planchas
		if(0 < count($Planchas_v))
		{
			
			$Consulta = array();

			foreach($Planchas_v as $Datos)
			{
				
				$Consulta[] = '
					(
						NULL,
						"'.$Id_Cliente.'",
						"'.$Datos['altu'].'",
						"'.$Datos['line'].'",
						"'.$Datos['marc'].'"
					)
				';

			}

			$Consulta = '
				insert into cliente_placa values '.implode(', ', $Consulta).'
			';
			$this->db->query($Consulta);
			
		}
		

		$Consulta = '
			delete from cliente_anilox
			where id_cliente = "'.$Id_Cliente.'"
		';
		$this->db->query($Consulta);
		
		//Ingresar los Anilox
		if(0 < count($Aniloxes_v))
		{
			
			$Consulta = array();

			foreach($Aniloxes_v as $Datos)
			{
				
				$Consulta[] = '
					(
						NULL,
						"'.$Id_Cliente.'",
						"'.$Datos['ani'].'",
						"'.$Datos['bcm'].'",
						"'.$Datos['can'].'"
					)
				';

			}

			$Consulta = '
				insert into cliente_anilox values '.implode(', ', $Consulta).'
			';
			$this->db->query($Consulta);

		}
		

		$Consulta = '
			delete from producto_cliente
			where id_cliente = "'.$Id_Cliente.'"
		';
		$this->db->query($Consulta);
		
		//Ingresar los Productos
		if(0 < count($Productos_v))
		{
			
			$Consulta = array();

			foreach($Productos_v as $Datos)
			{
				
				$Consulta[] = '
					(
						NULL,
						"'.$Id_grupo.'",
						"'.$Id_Cliente.'",
						"",
						"'.$Datos['idp'].'",
						"'.$Datos['pre'].'",
						1,
						"'.$Datos['con'].'",
						"s"
					)
				';//'.$Codigo_cliente.'

			}

			$Consulta = '
				insert into producto_cliente values '.implode(', ', $Consulta).'
			';

			$this->db->query($Consulta);
			
		}


		return 'ok';
			

	}
	
	
	function productos()
	{
		$Consulta = 'select id_producto, producto, activo from producto';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
	function agregar_producto($Id_cliente, $Cod_cliente, $Id_Producto, $Precio, $Cantidad, $Concepto)
	{
		$Consulta = 'select id_producto from producto_cliente where id_producto = "'.$Id_Producto.'" and id_cliente = "'.$Id_cliente.'"';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows())
		{
			$Consulta = 'insert into producto_cliente values(null,
										"'.$this->session->userdata["id_grupo"].'",
										"'.$Id_cliente.'",
										"'.$Cod_cliente.'",
										"'.$Id_Producto.'",
										"'.$Precio.'",
										"'.$Cantidad.'",
										"'.$Concepto.'",
										"s")';
			//echo $Consulta;
			$Resultado = $this->db->query($Consulta);
		}
		return 'ok';
	}
}

/* Fin del archivo */