<script type="text/javascript" src="/html/js/procesos.js?n=1"></script>

<form method="post" >
    <select id="id_cliente" name="id_cliente" onchange="listado_id_cliente()">
        <option value="">-- Seleccione --</option>
<?php
    foreach($Clientes as $Cliente)
    {
        ?>
            <option value="<?=$Cliente["id_cliente"]?>"<?=($Id_Cliente==$Cliente["id_cliente"])?' selected="selected"':''?>><?=$Cliente["codigo_cliente"]?> - <?=$Cliente["nombre"]?></option>
        <?php
    }
?>
    </select>
    <br />
		
		P&aacute;ginas: <?=$Paginacion?>
		
		<br />
<?php
if($Informacion_proc != '')
{
?>
    <table>
        <tr>
            <td><strong>Correlativo</strong></td>
            <td><strong>Trabajo</strong></td>
        </tr>
<?php
foreach($Informacion_proc as $Procesos)
{
?>
        <tr>
            <td><a href="/pedidos/administrar/info/<?=$Procesos["id_proceso"]?>"><?=$Procesos["proceso"]?></a></td>
            <td><?=$Procesos["nombre"]?></td>
        </tr>
<?php
}
?>
    </table>
<?
}
?>    
</form>