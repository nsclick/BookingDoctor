
<h3><? echo $title; ?></h3>

<?php if(count($doctors)): ?>
<?php var_dump($doctors); ?>;
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
	    	<td>
				<?php $attributes = array('role' => 'form'); ?>
				<?php echo form_open('agenda', $attributes); ?>
	    			<input type="hidden" name="appp" value="<?php echo $d->APEPAT_PROF; ?>" />
	    			<input type="hidden" name="ce" value="<?php echo $d->COD_ESPECIALIDAD; ?>" />
	    			<input type="hidden" name="cp" value="<?php echo $d->COD_PROF; ?>" />
	    			<input type="hidden" name="a" value="<?php echo $d->HORARIOPROX_AGENDA; ?>" />
	    			<input type="hidden" name="fa" value="<?php echo $d->FECHAINICPROX_AGENDA; ?>" />
	    			<input type="hidden" name="pfa" value="<?php echo $d->PROXIMA_HORA_DISPONIBLE; ?>" />
	    			<input type="hidden" name="corr" value="<?php echo $d->CORRAGENDA; ?>" />
	    			<input type="hidden" name="cu" value="<?php echo $d->COD_UNIDAD; ?>" />
	    			<button type="submit" class="btn btn-primary">Ver</button>
				</form>
	    	</td>
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