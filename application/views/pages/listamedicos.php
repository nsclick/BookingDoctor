<div id="wrapper">
<div class="listamedicos">
<h2><b>Paso 2: </b>Seleccione el profesional de su preferencia.</h2>
<h3><? echo $title; ?></h3>
<button type="button" class="btn btn-default volver"><span class="glyphicon glyphicon-circle-arrow-left"></span> Realizar Nueva B&uacute;squeda</button>
<?php if(count($doctors)): ?>
<!--<div class="table-responsive">-->
  <table class="table">
    <thead class="mb-no">
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
	    	<td><span class="mb-on">Profesional: </span>Dr(a). <?php echo "{$d->NOMBRE1_PROF} {$d->APEPAT_PROF} {$d->APEMAT_PROF}" ?></td>
	    	<td><span class="mb-on">&Aacute;rea: </span><?php echo $d->DESC_ITEM ?></td>
	    	<td><span class="mb-on">Centro M&eacute;dico: </span><?php echo $d->SUCURSAL ?></td>
	    	<td><span class="mb-on">Pr&oacute;x. Hora: </span><?php echo $d->PROXIMA_HORA_DISPONIBLE_CHAR ?></td>
	    	<td>
				<?php $attributes = array('role' => 'form'); ?>
				<?php echo form_open('agenda', $attributes); ?>
				<?php foreach($d as $key => $val): ?>
				<input type="hidden" name="<?php echo strtolower($key); ?>" value="<?php echo $val; ?>" />
				<?php endforeach; ?>
	    			<button type="submit" class="btn btn-primary"><span class="mb-on"><span class="glyphicon glyphicon-calendar"></span> Ver Agenda</span><span class="mb-no">Ver</span></button>
				</form>
	    	</td>
    	</tr>
    	<?php endforeach; ?>
    </tbody>
  </table>
  <button type="button" class="btn btn-default volver"><span class="glyphicon glyphicon-circle-arrow-left"></span> Realizar Nueva B&uacute;squeda</button>
<!--</div>-->
<?php else: ?>
<p>Para los datos seleccionados no se encontraron profesionales con agenda.</p>
<p>Por favor intente nuevamente o de lo contrario solicite su hora llamando a los teléfonos : <a href="tel:022702700">270 2700</a></p>
<?php endif; ?>
<script>
	$(document).ready(function() {

		$('.volver').click(function(e) {
			window.history.back();
		});

	}); 
</script>
</div>
</div>
