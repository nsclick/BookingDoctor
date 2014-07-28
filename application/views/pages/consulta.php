<div id="wrapper">

	<div class="btn-group btn-group-justified pestanas">
	  <div class="btn-group">
		<button type="button" class="btn btn-default display-control seleccionado" display="all">Todas</button>
	  </div>
	  <div class="btn-group">
		<button type="button" class="btn btn-default display-control" display="active">Activas</button>
	  </div>
	  <div class="btn-group">
		<button type="button" class="btn btn-default display-control" display="past">Pasadas</button>
	  </div>
	</div>

	<?php if( $anulacionRespuesta == 'error' ): ?>
	<br/><div class="alert alert-warning alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<strong>Error!</strong> No fue posible anular la reserva, intentelo nuevamente o comuniquese por teléfono
	</div>
	<?php elseif( $anulacionRespuesta == 'success' ): ?>
	<br/><div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<strong>Éxito!</strong> Su reserva fue anulada.
	</div>
	<?php endif; ?>
	
	
	
	
	<div class="consulta">
		<h1>Consulta de Horas</h1>
		<p>
			Aqu&iacute; podr&aacute; ver las horas solicitadas. Las que a&uacute;n se encuentren vigentes podr&aacute;n ser anuladas.
		</p>
		
		<?php foreach( $reserved as $rd ): ?>
		<p class="hora <?php echo ( $rd['ESTADO'] == 'S' ) ? 'active' : 'past' ?>">
			<span id="block-<?php echo $rd['CORREL_RESERVA'] ?>" >
				<span>Nombre: </span><strong><?php echo $rd['NOMBRE_PACIENTE'] ?></strong><br />
				<span>Código Reserva: </span><strong><?php echo $rd['CORREL_RESERVA'] ?></strong><br />
				<span>Fecha: </span><strong><?php echo $rd['FECHA_RESERVA'] ?></strong><br />
				<span>Hora: </span><strong><?php echo $rd['HORA_RESERVA'] ?></strong><br />
				<span>Profesional: </span><strong><?php echo $rd['NOMBRE_PROFESIONAL'] ?></strong><br />
				<span>Especialidad: </span><strong><?php echo $rd['ESPEC'] ?></strong><br />
				<span>Centro Médico: </span><strong><?php echo $rd['SUCURSAL'] ?></strong>
			</span>
			<?php if( $rd['ESTADO'] == 'S' ): ?>
			<a href="#" class="anular-hora" form-id="<?php echo $rd['CORREL_RESERVA'] ?>">Anular Hora</a>
			<?php echo form_open('agenda/anular', array('role' => 'form', 'id' => $rd['CORREL_RESERVA'], 'class' => 'hidden-form') ); ?>
			<?php foreach($rd as $key => $value): ?>
			<input type="hidden" name="<?php echo strtolower($key) ?>" value="<?php echo $value ?>" />
			<?php endforeach; ?>
			</form>
			<?php else: ?>
			<span class="pasada">Hora Pasada</span>			
			<?php endif; ?>			
		</p>
		<?php endforeach; ?>
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
        <p>¿Esta seguro de anular esta hora? </p>
        <p id="hora-a-nular"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" id="confirma-ok">Si</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
