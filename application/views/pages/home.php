<?php
	$session_user = get_session_user();
		//debug_var($session_user);		
?> 
<div id="wrapper">
	<div class="webhome">
		<h1>Reserva de Horas</h1>
		<!-- <h2><b>Paso 1:</b> Selecci&oacute;n de Paciente</h2>
		<p>
			Selecciona entre los afiliados al plan, la persona para quien buscas reservar una hora.
		</p>
		<form>
			<div class="form-group">
				<select>
					<option>Titular: Juan P&eacute;rez</option>
					<option>Esposa: Mar&iacute;a Soto</option>
					<option>Hija: Juana Mar&iacute;a</option>
					<option>Otro: Miguel Rosas</option>
				</select>
			</div>
		</form>-->
		<h2><b>Paso 1: </b>Selecci&oacute;n de Profesional</h2>
		<ul class="davila-out">
			<li>Si conoces el apellido del médico con el cual te quieres atender, escríbelo en el espacio asignado.</li>
			<li>Si no tienes médico definido, elige el área en la que te quieres atender.</li>
		</ul>
		<?php $attributes = array('role' => 'form'); ?>
		<?php echo form_open('home', $attributes); ?>
		<div class="form-group">
			<label for="apellido">Apellido del profesional</label>

			<div class='input-group'>
				<input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese el apellido del profesional" value="<?php echo $apellido; ?>">
				<span class="input-group-addon" > <span class="glyphicon glyphicon-search"></span> </span>
			</div>
		</div>
		</form>

		<?php $attributes['id'] = 'buscaarea'; ?>
		<?php echo form_open('home', $attributes); ?>
		<input type="hidden" name="area" id="area" value="<?php echo $area; ?>">
		<div class="form-group">
			<label for="area-label">Área</label>
			<div class='input-group'>
				<input type="text" class="form-control" id="area-label" name="area-label" placeholder="Ingrese el área">
				<span class="input-group-addon" > <span class="glyphicon glyphicon-search"></span> </span>
			</div>

		</div>
		</form>
		
		<?php if(isset($session_user['userName'])): ?>
		<p>
			Para consultar y anular las horas reservadas anteriormente, haz click <a href="<?php echo site_url("consulta/"); ?>">aqu&iacute;</a>.
		</p>
		<?php endif; ?>
		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="myModalLabel">Registra tu correo electrónico</h4>
					</div>
					<form method="POST" role="form">
						<div class="modal-body">
							<div class="form-group">
								<label for="apellido">Correo Electrónico</label>
								<input type="email" class="form-control" id="apellido" name="apellido" placeholder="Ingrese el apellido">
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">
								Cerrar
							</button>
							<button type="button" class="btn btn-primary">
								Enviar
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	
	
<?php if(isset($doctors)): ?>
<div class="listamedicos">
<h2><? echo $title; ?></h2>
<p>Se han encontrado <strong><?php echo count($doctors) ?></strong> médicos</p>
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
<!--</div>-->
<?php else: ?>
<p>Para los datos seleccionados no se encontraron profesionales con agenda.</p>
<p>Por favor intente nuevamente o de lo contrario solicite su hora llamando a los teléfonos : <a href="tel:022702700">270 2700</a></p>
<?php endif; ?>

</div>	
<?php endif; ?>
	
	
	
</div>
