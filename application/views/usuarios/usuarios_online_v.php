<script type="text/javascript" src="/html/js/administracion.js?n=1"></script>
<div class='informacion'>
	<table class="table table-bordered table-condensed table-hover" class='tabular' style='width: 95%;'>
		<tr>
			<th style='width: 5%;'><strong>Grupo</strong></th>
			<th colspan='2' style='width: 25%;'><strong>Departamento</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Usuario</strong></th>
			<th style='width: 18%;'><strong>Fecha</strong></th>
			<th style='width: 52%;'><?=('Sistemas' == $this->session->userdata('codigo'))?'<strong>Paginas</strong>':''?></th>
			</tr>
<?php
foreach($usuarios as $Datos)
{
?>
		<tr>
			<td colspan='4'><strong><?=$Datos['grupo']?></strong></td>
		</tr>
<?php
	foreach($Datos['usuarios'] as $Datos_usu)
	{
?>
		<tr>
			<td colspan='5'><strong style='margin-left: 5em'><?=$Datos_usu['departamento']?></strong></td>
		</tr>
<?php
		foreach($Datos_usu['usuarios'] as $Usuario)
		{
?>
		<tr>
			<td colspan='3'><p style='margin-left: 10em'><?=$Usuario['nombre']?></p></td>
			<td><?=date('d-m-Y H:i:s', strtotime($Usuario['fecha']))?></td>
			<td><?=('Sistemas' == $this->session->userdata('codigo'))?$Usuario['pagina']:''?></td>
		</tr>
<?php
		}
	}
?>
		<tr>
			<td colspan='3'><strong>Total de usuarios conectados</strong></td>
			<td colspan='3'><strong><?=count($Datos['usuarios'])?></strong><br />&nbsp;</td>
		</tr>
<?php
}
?>
	</table>
</div>