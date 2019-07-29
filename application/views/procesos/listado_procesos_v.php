<script type="text/javascript" src="/html/js/procesos.js?n=1"></script>
<style>
    .btn{
        border-radius: 8px;
        background-color: #bc933b;
        color: white;
        padding: 5px 15px;
        text-align: center;
    
    }
    .btn:hover, .btn:active {
    background-color: lightblue;
    }
    .pull-right{
        float: right;
    }
    a:link
    {
        text-decoration:none;
    } 
</style>
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
    <a class="btn pull-right" href="/ventas/preingreso">Crear Proceso</a>
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
<?php
}
?>    
</form>