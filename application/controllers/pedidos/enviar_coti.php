<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enviar_coti extends CI_Controller {
	
	public function index($Id_Pedido = 0)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Super validacion
		$Id_Pedido += 0;
		if(0 == $Id_Pedido)
		{
			echo 'pedido';
			exit();
		}

		$Para = $this->seguridad_m->mysql_seguro(
			$this->input->post('para')
		);
		$Atento = $this->seguridad_m->mysql_seguro(
			$this->input->post('atento')
		);
		
		
		//Carga del modelo validador del proceso
		$this->load->model('procesos/buscar_proceso_m', 'buscar');
		//Verificamos la existencia
		$Existe = $this->buscar->busqueda_pedido($Id_Pedido);
		
		
		if(0 == $Existe)
		{//Si el proceso no existe se envia atras y se notifica
			echo 'proceso';
			exit();
		}

		$this->load->model('pedidos/pedido_detalle_m', 'ped_det');
		$Cotizacion = $this->ped_det->buscar_cotizacion($Id_Pedido);


		//Se debe generar el id para la coti
		$Consulta = '
			insert into pedido_cotizacion values(
				NULL,
				"'.$Id_Pedido.'",
				"'.$this->session->userdata('id_usuario').'",
				""
			)
		';
		$this->db->query($Consulta);
		$Id_Pedido_Cotizacion = $this->db->insert_id();
		$Correlativo = str_pad($Id_Pedido_Cotizacion, 4, '0', STR_PAD_LEFT);
		

		
		require 'mail_cg/PHPMailerAutoload.php';


		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		//$mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
		//$mail->SMTPAuth = true;                               // Enable SMTP authentication
		//$mail->Username = 'cotis.cg@gmail.com';               // SMTP username
		//$mail->Password = 'envCotisCG1221';                   // SMTP password
		//$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		//$mail->Port = 587;                                    // TCP port to connect to


		//$mail->isSMTP();
		//$mail->Host = 'relay-hosting.secureserver.net';
		//$mail->Port = 25;
		//$mail->SMTPAuth = false;
		//$mail->SMTPSecure = false;



		$mail->setFrom('customer@centralgraphics-cg.com', 'Central Graphics');
		$mail->addAddress($Para);     // Add a recipient
		//$mail->addAddress('','');               // Name is optional
		$mail->addReplyTo('customer@centralgraphics-cg.com', 'Central Graphics');
		//$mail->addCC('customer@centralgraphics-cg.com', 'Central Graphics');
		//$mail->addBCC('bcc@example.com');

		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'Cotizacion '.$Existe['codigo_cliente'].'-'.$Existe['proceso'];
		$mail->Body    = '
<div id="correo_cuerpo" style="color: #333333; font-size: 13px; line-height: 20px;">
	<img src="https://centralgraphics-cg.com/html/img/lgtps/central-g_inicio.png" />

	<br /><br />

	<div id="correo_cliente" style="float: left;">
		<strong>'.$Existe['nombre'].'</strong>
		<br />
		Att.: '.$Atento.'
		<br />
		Presente
	</div>

	<div id="correo_fecha" style="float: right;">
		San Salvador, '.date('d-m-Y').'
	</div>

	<br style="clear: both;" /><br />

	A continuaci&oacute;n se detalla la cotizaci&oacute;n para el proceso: <strong>'.$Existe['codigo_cliente'].'-'.$Existe['proceso'].': '.$Existe['nombre_proceso'].'</strong>.
	<br />
	Le solicitamos nos env&iacute;e su aprobaci&oacute;n de la misma a la siguiente direcci&oacute;n: customer@centralgraphics-cg.com
	
	<br /><br />	

	<table style="border-collapse: collapse; width: 700px;">
		<tr>
			<th colspan="5" style="color: #333333; font-size: 13px; line-height: 20px; background: #eeeeee; padding: 3px 5px; border: 1px solid #aaaaaa;">COTIZACI&Oacute;N ['.$Correlativo.']</th>
		</tr>
		<tr style="color: #333333; font-size: 13px; line-height: 20px; background: #eeeeee;">
			<th style="padding: 3px 5px; border: 1px solid #aaaaaa;">Item</th>
			<th style="padding: 3px 5px; border: 1px solid #aaaaaa; width: 50%;">Descripci&oacute;n del Servicio</th>
			<th style="padding: 3px 5px; border: 1px solid #aaaaaa;">Cantidad (in2)</th>
			<th style="padding: 3px 5px; border: 1px solid #aaaaaa;">Precio Unitario</th>
			<th style="padding: 3px 5px; border: 1px solid #aaaaaa;">Sub Total US$</th>
		</tr>';

		$Total = 0;
		$Contador = 1;

		foreach($Cotizacion as $PrimerArray)
		{
			foreach($PrimerArray as $iCoti => $Items)
			{

				if('total' !== $iCoti)
				{

					$SubTotal = $Items['pulgadas'] * $Items['precio'];
					$Total += $SubTotal;

					$mail->Body   .= '<tr style="text-align: right; color: #333333; font-size: 13px; line-height: 20px;">
						<td style="padding: 3px 5px; border: 1px solid #aaaaaa;">'.$Contador.'</td>
						<td style="padding: 3px 5px; border: 1px solid #aaaaaa; text-align: left;">'.$Items['producto'].'</td>
						<td style="padding: 3px 5px; border: 1px solid #aaaaaa;">'.number_format($Items['pulgadas'], 0).'</td>
						<td style="padding: 3px 5px; border: 1px solid #aaaaaa;">$'.number_format($Items['precio'], 2).'</td>
						<td style="padding: 3px 5px; border: 1px solid #aaaaaa;">$'.number_format($SubTotal, 2).'</td>
					</tr>';

					$Contador++;

				}

			}
		}

		$mail->Body   .= '<tr style="color: #333333; font-size: 13px; line-height: 20px; background: #eeeeee; text-align: right;">
			<th colspan="4" style="padding: 3px 5px; border: 1px solid #aaaaaa;">TOTAL</th>
			<th style="padding: 3px 5px; border: 1px solid #aaaaaa;">$'.number_format($Total, 2).'</th>
		</tr>
	</table>

	<br /><br />

	<hr style="border: 2px solid #b9c008;" />

	<br /><br />

	<div id="correo_contacto" style="text-align: center;">
		Colonia  San  Benito,  calle  Reforma  y  Av. Las Palmas # 111, San Salvador
		<br />
		Tel. 2223-5941 y 2223-8035, Cel. 7039-3199
		<br />
		erodriguez@centralgraphics-cg.com / customer@centralgraphics-cg.com
		
		<br />
		<br />

		<a href="http://www.centralgraphics-cg.com">http://www.centralgraphics-cg.com</a>
	</div>

	<br /><br />

</div>';
		$mail->AltBody = 'En el presente correo se muestra la cotizaci&oacute;n, le recomendamos utilizar un navegador moderno.';

		//Se debe guardar la cotizacion para futuras referencias
		$Consulta = "
			update pedido_cotizacion
			set cotizacion = '".$mail->Body."'
			where id_pedido_cotizacion = ".$Id_Pedido_Cotizacion."
		";
		$this->db->query($Consulta);

		//echo $mail->Body; exit();
		
		
		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'ok';
		}

	}
	
}

/* Fin del archivo */