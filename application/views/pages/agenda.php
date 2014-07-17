<?php
$days = $this->config->item('dias');
$months = $this->config->item('meses');

//debug_var($post);
?>
<div id="wrapper">
<div class="agendamed">
	<h2><b>Paso 2: </b>Seleccione la hora de la consulta.</h2>
	<button type="button" class="btn btn-default volver"><span class="glyphicon glyphicon-circle-arrow-left"></span> Volver a Resultados</button>
	<h4><?php echo "Dr(a). {$post['nombre1_prof']} {$post['apepat_prof']} {$post['apemat_prof']}" ?></h4>
	<h5><?php echo $post['desc_item'] ?></h5>
	<h5><?php echo $post['sucursal'] ?></h5>
	<p>A continuaci&oacute;n se muestras las horas disponbles para el profesional seleccionado. Puede ver los d&iacute;as disponibles en el men&uacute; despleglable. <b>Se muestran exclusivamente los d&iacute;as y horas disponibles.</b></p>
	<?php $attributes = array('role' => 'form', 'id' => 'form-agenda'); ?>
	<?php echo form_open('agenda/paciente', $attributes); ?>
		<div class="row">
	  		<div class="col-md-4"><label>Seleccione d&iacute;a</label></div>
	  		<div class="col-md-8">
	  			<select name="available-days" class="form-control">
					<?php foreach($available_dates as $date => $times): ?>
					<?php
						$mday = date('w', strtotime($date));
						$day = date('d', strtotime($date));
						$month = date('n', strtotime($date));
						$fdate = $day." de ".$months[$month-1]. " del ".date('Y') . ' - ' . $days[$mday] ;
					?>
					<option value="<?php echo $date?>"><?php echo $fdate ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<hr />
		<div class="row">
	  		<div class="col-md-4"><label>Seleccione hora</label></div>
	  		<div class="col-md-8">
				<?php foreach($available_dates as $date => $times): ?>

	  			<table class="table hide" id="<?php echo $date ?>">
		 			<tbody>	
						<?php foreach($times as $index => $time): ?>

			  			<tr>
			     			<td><a href="#" ct-time="<?php echo $time['time'] ?>" ct-schedule="<?php echo $time['id_schedule'] ?>" ct-box="<?php echo $time['box'] ?>" ct-multi="<?php echo $time['multiplicity'] ?>" class="time-chooser"><?php echo $time['time'] ?> <span class="glyphicon glyphicon-ok-sign"></span> <span>Agendar</span></a></td>
			     		</tr>
						
						<?php endforeach; ?>
	 				</tbody>
				</table>
				
				<?php endforeach; ?>
			</div>
		</div>
		<input type="hidden" name="time" />
		<input type="hidden" name="id_schedule" />
		<input type="hidden" name="box" />
		<input type="hidden" name="multiplicity" />
		
		<?php foreach($post as $key => $val): ?>
		<input type="hidden" name="<?php echo $key ?>" value="<?php echo $val ?>">	
		<?php endforeach; ?>
	</form>
	<button type="button" class="btn btn-default volver"><span class="glyphicon glyphicon-circle-arrow-left"></span> Volver a Resultados</button>
</div>
</div>


<div class="modal fade" id="modal-confirmation">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Confirmación</h4>
      </div>
      <div class="modal-body">
        <p>¿Esta seguro de reservar hora el dia <b><span id="txt-date"></span></b> a las <b><span id="txt-time"></span></b>? </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" id="confirma-ok">Si</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
