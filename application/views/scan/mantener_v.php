<script>
	var datos = 'dia1=15';
	$.ajax(
	{
		type: "POST",
		url: "/carga/seguimiento/index",
		data: datos,
		success: function(msg)
		{
			window.location = "/carga/seguimiento/";
		},
		error: function(msg)
		{
			alert('Lo sentimos. Ocurri\xf3 un error en la acci\xf3n solicitada.');
		}
	});
</script>
