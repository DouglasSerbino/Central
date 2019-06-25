

<table class="tabular" style="width: 100%;">
	<tr>
		<th>Solicitudes</th>
	</tr>
<?
foreach($Cotizaciones as $Fila)
{
?>
	<tr>
		<td><?=date('d-m-Y H:i', strtotime($Fila['fecha']))?><br /><?=$Fila['mensaje']?></td>
	</tr>
<?
}
?>
</table>