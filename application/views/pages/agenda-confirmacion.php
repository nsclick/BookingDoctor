<div id="wrapper" class="confirmacion">
	<div class="alert alert-danger" role="alert">Error: Ahhhhhhhh!!!!!</div>
	<h2><b>Paso <?php echo $step; ?>: </b>Confirme la reserva antes de registrarla.</h2>
	<h2>Informaci&oacute;n de la reserva</h2>
	<table class="table">
		<thead>
			<tr>
				<th colspan="2"> Nota: La hora no ha sido reservada aún. </th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Paciente</td>
				<td><?php echo "{$post['patient']['nombre_paciente']} {$post['patient']['apepat_paciente']} {$post['patient']['apemat_paciente']}"?></td>
			</tr>
			<tr>
				<td>Profesional</td>
				<td><?php echo "{$post['nombre1_prof']} {$post['apepat_prof']} {$post['apemat_prof']}"?></td>
			</tr>
			<tr>
				<td>&Aacute;rea</td>
				<td><?php echo $post['desc_item']?></td>
			</tr>
			<tr>
				<td>Fecha</td>
				<td><?php echo str_replace('-', '/', $post['available-days'])?></td>
			</tr>
			<tr>
				<td>Hora</td>
				<td><?php echo $post['time']?></td>
			</tr>
			<tr>
				<td>Isapre</td>
				<td><?php echo $post['patient']['desc_prevision']?></td>
			</tr>
			<tr>
				<td>Direcci&oacute;n de atenci&oacute;n</td>
				<td><?php echo $company_addr ?></td>
			</tr>
		</tbody>
	</table>
	<div style="text-align:center">
	<?php $attributes = array('role' => 'form', 'id' => 'form-agenda'); ?>
	<?php echo form_open('agenda/reservar', $attributes); ?>
		<button type="submit" class="btn btn-default">
			Confirmar Reserva
		</button>
		<button type="button" class="btn btn-default volver">
			Cancelar
		</button>		
	</form>	
	</div>
</div>
