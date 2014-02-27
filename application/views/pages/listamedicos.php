
<h3><? echo $title; ?></h3>

<?php if(count($doctors)): ?>
<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
    	<tr>
	    	<th>Profesional</th>
	    	<th>Área</th>
	    	<th>Centro Médico</th>
	    	<th>Próx. Hora</th>
	    	<th>Agenda</th>
    	</tr>
    </thead>

    <tbody>
    	<?php foreach($doctors as $d): ?>
		<tr>
	    	<td>Dr(a). <?php echo "{$d->NOMBRE1_PROF} {$d->APEPAT_PROF} {$d->APEMAT_PROF}" ?></td>
	    	<td><?php echo $d->DESC_ITEM ?></td>
	    	<td><?php echo $d->SUCURSAL ?></td>
	    	<td><?php echo $d->PROXIMA_HORA_DISPONIBLE_CHAR ?></td>
	    	<td><button type="button" class="btn btn-primary">Ver</button></td>
    	</tr>
    	<?php endforeach; ?>
    </tbody>
    
  </table>
</div>
<?php else: ?>
<p>Para los datos seleccionados no se encontraron profesionales con agenda.</p>
<p>Por favor intente nuevamente o de lo contrario solicite su hora llamando a los teléfonos : <a href="tel:022702700">270 2700</a></p>
<?php endif; ?>

<button type="button" class="btn btn-default" id="volver">Volver</button>

<script>
	$( document ).ready(function() {
		
		$( '#volver' ).click(function (e){ 
			window.history.back();
		});

	});
</script>